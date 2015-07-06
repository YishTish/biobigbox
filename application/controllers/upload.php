<?php

class Upload extends MY_Controller {
    
    function uploader() {
        $this->data['user'] = $this->MUser->factory($this->session->userdata('user'));   
        $this->data['msg'] = $this->session->flashdata('msg');
        $policy = '{ "expiration": "' . gmdate('Y-n-d\TH:i:s.000\Z', time() + 3600) . '",
            "conditions": [
                {"bucket": "dentvault"},
                ["starts-with", "$key", "temp/"],
                {"acl": "private"},
                {"success_action_redirect": "' . site_url('upload/s3return') . '"},
                ["starts-with", "$x-amz-meta-patient", ""],
                ["starts-with", "$x-amz-meta-notes", ""],' .
                (!$this->data['user'] ? '["starts-with", "$x-amz-meta-sharer", ""],' : '') . '
                ["starts-with", "$x-amz-meta-sharee", ""]
            ] 
        }';
        
        $policy64 = base64_encode(utf8_encode(preg_replace('/\s\s+|\\f|\\n|\\r|\\t|\\v/', '', $policy)));
        
        $sig = base64_encode(hash_hmac('sha1', $policy64, $this->config->item('aws_s3_secret_key'), true)); 
        $this->data['policy'] = $policy64;
        $this->data['signature'] = $sig;
        $this->load->view('uploader', $this->data);
    }
    
    function s3return() {
        $this->load->library('libs3');
        $this->load->language('files');
        $key = $this->input->get('key');
        $filename = substr($key, strrpos($key, '/') + 1);
        $meta = $this->libs3->getFileMeta($key);
        if ($meta['mimetype'] == 'binary/octet-stream') {
            $this->load->helper('file');
            $meta['mimetype'] = get_mime_by_extension($key);
        }
        $user = $this->MUser->factory($this->session->userdata('user'));
        $sharer = ($user ? $user->username : $meta['sharer']);
        $filedata = array(
            'filename' => $filename,
            'size' => $meta['size'],
            'mimetype' => $meta['mimetype'],
            'sharer' => $sharer,
            'sharee' => $meta['sharee']
        );
//echo '<pre>'; print_r($filedata); exit;
        $this->processUpload($filedata);
        $this->session->set_flashdata('msg', array('success', lang('files_upload_success')));
        redirect('files');
    }

    function uploads3() {
        if ($this->agent->is_browser('MSIE')) {
            $this->uploader();
        } else {
            $this->data['user'] = $this->MUser->factory($this->session->userdata('user'));   
            $this->data['msg'] = $this->session->flashdata('msg');
            $this->load->view('s3upload', $this->data);
        }
    }
    
    function signput() {

        // check user has space for this upload
        if (!$user = $this->MUser->factory($this->session->userdata('user')))
            $user = $this->MUser->getUserFromEmail($this->input->get('email'));
        
        if ($user) {
            $package = $user->getCurrentPackage();
            $totals = $this->MFile->getFileTotals($user->id);
        } else {
            // this is a new user, give them the free package
            $package = $this->MPackage->factory($this->config->item('free_package_id'));
            $totals = $this->MFile->getFileTotals(0);
        }
        if (($totals->total + $this->input->get('size')) > $package->storage)
            // user is over storage quota, reject upload
            exit;
        
//print_r($package); print_r($totals); echo $this->input->get('size'); exit;        
        
        // all is ok, create the PUT url
        $S3_KEY = $this->config->item('aws_s3_access_key');
        $S3_SECRET = $this->config->item('aws_s3_secret_key');
        $S3_BUCKET = '/biobigbox';
        
        $EXPIRE_TIME = (60 * 5); // 5 minutes
        $S3_URL = 'https://s3.amazonaws.com';
        
        $objectName = '/temp/' . urlencode($this->input->get('name'));
        
        $mimeType = $this->input->get('type');
        $expires = time() + $EXPIRE_TIME;
        $amzHeaders = "x-amz-acl:private";
        $stringToSign = "PUT\n\n$mimeType\n$expires\n$amzHeaders\n$S3_BUCKET$objectName";
        $sig = urlencode(base64_encode(hash_hmac('sha1', $stringToSign, $S3_SECRET, true)));
        
        $url = urlencode("$S3_URL$S3_BUCKET$objectName?AWSAccessKeyId=$S3_KEY&Expires=$expires&Signature=$sig");
        
        exit($url); 
    }

    function storefileinfo() { // AJAX call
        $filedata = array(
            'filename' => urldecode($this->input->post('filename')),
            'size' => $this->input->post('size'),
            'mimetype' => $this->input->post('mimetype'),
            'sharer' => $this->input->post('email'),
            'sharee' => $this->input->post('sharee')
        );
        header('Content-type: application/json');
        exit(json_encode($this->processUpload($filedata)));
    }
    
    function processUpload($data) {
        $this->load->library('libemail');
        $this->load->language('files');
        if (!$user = $this->MUser->factory($this->session->userdata('user'))) {
            $confirmed = 0;
            // user not logged in, see if an email was submitted
            if ($data['sharer']) {
                // see if there is a user registered with the submitted email
                if (!$user = $this->MUser->getUserFromEmail($data['sharer'])) {
                    // if not, create new user with that email
                    $pass = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8);
                    $user = $this->MUser->newUser($data['sharer'], $pass);
                    $this->libemail->sendNewUser($user, $pass, $data['filename'], $data['size']);
                }
            } else {
                die("no user no email");
                // no user and no email - fail
                return 0;
            }
        } else {
            // user is logged in
            $confirmed = 1;
        }
        
        $file = array('filename'  => $data['filename'],
                      'ownerid'   => $user->id,
                      'size'      => $data['size'],
                      'mimetype'  => $data['mimetype'],
                      'confirmed' => $confirmed);
        $fileid = $this->MFile->create($file);
        $file['id'] = $fileid;
        $this->MFile->addShare($fileid, $user->id);
        
        // move the file from temp directory on S3 to the correct file location
        $this->load->library('libs3');
        $folder = intval($fileid / 1000);
        $savename = 'files/' . $folder . '/' . $fileid . '/1/' . $data['filename'];
        if (!$this->libs3->renameFile('temp/' . $data['filename'], $savename))
            // failed to move file
            return 0;
        if (!$confirmed) // send file confirm email
            $this->libemail->sendFileConfirm($user, $file);
        // if there is an email to share with, find/create user and share it
        if ($data['sharee']) {
            if (!$shuser = $this->MUser->getUserFromEmail($data['sharee'])) {
                // sharee is a new user, create user and send invite
                $pass = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8);
                $shuser = $this->MUser->newUser($data['sharee'], $pass);
            }
            $this->MFile->addShare($fileid, $shuser->id);
            if ($confirmed) {
                // send sharee email
                if (isset($pass))
                    // sharee is a new user
                    $this->libemail->sendNewUserInvite($shuser, $pass, $user, $file['filename'], $file['size']);
                else
                    // sharee is exsiting user
                    $this->libemail->sendShare($shuser, $user, $file['filename'], $file['size']);
            }
        }

        return ($this->MFile->getFiles($file['id'], $allow_temp=true)->asArray());
        /*$response['id'] = $files[0]['id'];
        $response['filename'] = $files[0]['filename'];
        $response['ownerid'] = $files[0]['ownerid'];
        $response['patientid'] = $files[0]['patientid'];
        $response['size'] = $files[0]['size'];
        $response['mimetype'] = $files[0]['mimetype'];
        $response['status'] = $files[0]['status'];
        $response['version'] = $files[0]['version'];
        $response['mimetype'] = $files[0]['mimetype'];
        $response['created'] = $files[0]['created'];
        $response['confirmed'] = $files[0]['confirmed'];
        
       return $response;
       */
    }

}