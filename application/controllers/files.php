<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files extends MY_Controller {
    
    const MAX_UPLOAD_SIZE = 100; // Mb
    
    public function __construct() {
        parent::__construct();
        if (!$this->data['user'] = $this->MUser->factory($this->session->userdata('user')))
          redirect();
        $this->data['msg'] = $this->session->flashdata('msg');
        $this->load->language('files');
        $this->load->library('libs3');
        $this->load->helper('email');
    }

	public function index()
	{
        $currentpackage = $this->MPackage->factory($this->data['user']->packageid);
        $this->data['storageexceeded'] = false;
        if ($filter = $this->session->userdata('filter')) {
            if (isset($filter['patientid'])) {
                $pid = $filter['patientid'];
                $this->data['filter']['patient'] = $this->MPatient->factory($pid);
            } else {
                $pid = false;
            }
            if (isset($filter['days'])) {
                $days = $filter['days'];
                $this->data['filter']['days'] = $days;
            } else {
                $days = false;
            }
            if (isset($filter['string'])) {
                $search = $filter['string'];
                $this->data['filter']['string'] = $search;
            } else {
                $search = false;
            }
            $this->data['files'] = $this->MFile->getUserFiles($this->data['user']->id, $pid, $days, $search);
        } else {
            $files = $this->MFile->getUserFiles($this->data['user']->id);
            if ($currentpackage->storage && (count($files) > $currentpackage->storage)) {
                $this->data['files'] = $files->slice(count($files) - $currentpackage->storage, $currentpackage->storage);
                $this->data['storageexceeded'] = true;
            } else {
                $this->data['files'] = $files;
            }
        }
        //$this->data['filesArray'] = $this->data['files']->asArray();
        
        $this->data['filesArray'] = array();
        foreach ($files as $key => $file) {
            $patient = $this->MPatient->factory($file->patientid);
            $this->data['filesArray'][$key]['filename'] = $file->filename;
            $this->data['filesArray'][$key]['id'] = $file->id;
            $this->data['filesArray'][$key]['ownerid'] = $file->ownerid;
            $this->data['filesArray'][$key]['size'] = $file->size;
            $this->data['filesArray'][$key]['created'] = $file->created;
            $this->data['filesArray'][$key]['mimetype'] = $file->mimetype;
            $this->data['filesArray'][$key]['status'] = $file->status;
            $this->data['filesArray'][$key]['url'] = base_url() . 'files/download/' . $file->id; // $file->getURL();
            $this->data['filesArray'][$key]['notes'] = $file->getNotes();
            $this->data['filesArray'][$key]['shares'] = $file->getShares();
        }
        $this->data['patients'] = $this->MPatient->getPatientNamesByOwner($this->data['user']->id);
        $this->data['statuses'] = $this->MFile->getStatuses();
        $this->data['currentpackage'] = $currentpackage;

        header("Content-Type: application/json");
        echo json_encode($this->data);
        exit();
//       $this->load->view('filelist', $this->data);
    }
    
    function download($fileid) {
        //check user has permission for this file
        if(!$this->MFile->isSharedWith($fileid, $this->data['user']->id))
            redirect (base_url() . '/#/message/Error/A%20file%20error%20occurred.');
        $file = $this->MFile->factory($fileid);
        $package = $this->data['user']->getCurrentPackage();
        $totals = $this->MFile->getFileTotals($this->data['user']->id);
        if ($totals->downloads + $file->size > $package->bandwidth)
            redirect (base_url() . '/#/message/Over%20Quota/You%20have%20exceeded%20your%20monthly%20download%20limit.%20Please%20upgrade.');
        $this->MFile->storeDownload($fileid, $this->data['user']->id);
        if (!$this->MFile->viewedByUser($fileid, $this->data['user']->id)) {
            $this->MFile->setViewed($fileid, $this->data['user']->id);
            $sharer = $file->getOwner();
            if ($sharer->id != $this->data['user']->id) {
                $this->load->library('libemail');
                $this->libemail->sendViewConfirmation($sharer, $this->data['user'], $file);
            }
        }
        redirect($file->getURL());
    }
    
    public function upload() {
        $this->data['patients'] = $this->MPatient->getPatientNamesByOwner($this->data['user']->id);
        $this->data['max_upload_size'] = self::MAX_UPLOAD_SIZE;
        $this->load->view('upload', $this->data);
    }
    
    public function doupload() {
//echo '<pre>'; print_r($_POST); exit;
        if ($oldfileid = $this->input->post('fileid')) {
            $oldfile = $this->MFile->factory($oldfileid);
            $patientid = $oldfile->patientid;
            $ver1id = $oldfile->ver1id;
            $version = $oldfile->getMaxVersion() + 1;
            $shares = $oldfile->getShares();
        } else {
            $patient = explode('(', $this->input->post('patient'));
            $patientname = trim($patient[0]);
            if ($patientname) {
                $identifier = (isset($patient[1]) ? substr($patient[1], 0, strlen($patient[1]) - 1) : ''); 
                if (!$patientid = $this->MPatient->getIdFromName($patientname, $identifier))
                    $patientid = $this->MPatient->newPatient($patientname, $this->data['user']->id);
            } else {
                $patientid = 0;
            }
            $ver1id = 0;
            $version = 1;
            $shares = false;
        }
        $filename = $_FILES['file']['name'];
        $file = array('filename' => $filename,
                      'ownerid' => $this->data['user']->id,
                      'patientid' => $patientid,
                      'size' => $_FILES['file']['size'],
                      'mimetype' => $_FILES['file']['type'],
                      'version' => $version,
                      'ver1id' => $ver1id);
        $fileid = $this->MFile->create($file);
        $folder = intval($fileid / 1000);
        $savename = 'files/' . $folder . '/' . $fileid . '/' . $version . '/' . $filename;
        if ($this->libs3->writeFile($savename, $_FILES['file']['type'], file_get_contents($_FILES['file']['tmp_name']))) {
            // file saved successfully in S3
            if ($shares) {
                foreach ($shares as $s)
                    $this->MFile->addShare($fileid, $s['userid']);
            } else {
                $this->MFile->addShare($fileid, $this->data['user']->id);
            }
            if ($note = $this->input->post('notes')) 
                $this->MNote->create(array('fileid' => $fileid, 'ownerid' => $this->data['user']->id, 'note' => $note));
            $this->session->set_flashdata('msg', array('success', lang('files_upload_success')));
        } else {
            // failed to save file in S3 so remove entry in DB
            $this->MFile->deleteFileEntry($fileid);
            $this->session->set_flashdata('msg', array('error', lang('files_upload_failed') . ' - ' . $this->libs3->getError()));
        }
        $this->MFile->updateMonthlyMax($this->data['user']->id);
        if ($this->input->post('newwindow'))
            echo "<script>window.opener.location.reload(); window.close();</script>";
        else
            redirect('files'); 
    }
    
    public function delete() {
        $files = $this->MFile->getFiles($this->input->post('fileids'));
        foreach ($files as $f) {
            if(!$f->removeShare($this->data['user']->id)){
                $this->session->set_flashdata('msg', array('error', lang('files_delete_failure')));
                redirect('files');
            }
        }
        $this->session->set_flashdata('msg', array('success', lang('files_delete_success')));
        redirect('files');
    }
    
    public function share() {
        $fileid = $this->input->post('fileid');
        $file = $this->MFile->factory($fileid);
        $email = $this->input->post('share_email');
//echo $fileid . ' ' . $email; exit;
        $response = array();
        if(!valid_email($email)){
            $response['status'] = "Failure";
            $response['msg'] = "Invalid email. Please type in valid address";
        }
        else {

            $this->load->library('libemail');
            if (!$user = $this->MUser->getUserFromEmail($email)) {
                // create new user, send intro email
                $user = $this->MUser->newUser($email);
                // create and save a new password for the user
                $pass = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8);
                $this->MUser->changePwd($user->id, $pass);
                $this->libemail->sendNewUserInvite($user, $pass, $this->data['user'], $file->filename);
            } else {
                if ($user->emailnotifications)
                    $this->libemail->sendShare($user, $this->data['user'], $file->filename);
                if ($user->smsnotifications && $user->smsnumber) {
                    $this->load->library('nexmo');
                    $this->nexmo->set_format('json');
                    $from = 'DentVault';
                    $to = $user->smsnumber;
                    $message = array(
                        'text' => $this->data['user']->firstname . ' (' . $this->data['user']->username . ') has added the file "' . $file->filename . '" to your dental vault. Login at dentvault.com to view the file.'
                    );
                    $messageSent = $this->nexmo->send_message($from, $to, $message);
                }
                $this->MFile->addShare($fileid, $user->id);
                $this->MFile->updateMonthlyMax($user->id);
                $this->session->set_flashdata('msg', array('success', lang('files_share_success')));
               }
            $response['status'] = "success";
            $response['msg'] = "File was shared with " . $email;
        }
        header("Content-Type: application/json");
        echo(json_encode($response));
        exit();
        //redirect('files');
    }
    
    public function status($fileid, $status) {
        if ($this->MFile->updateStatus($fileid, $status))
            echo 'success';
        else
            echo 'failed';
        exit;
    }
    
    public function viewed($fileid) {
        if ($this->MFile->setViewed($fileid, $this->data['user']->id)) {
            // notify sharer
            $file = $this->MFile->factory($fileid);
            $sharer = $file->getSharer($this->data['user']->id);
            if ($sharer->getCurrentPackage()->shareconfirm) {
                if ($sharer->emailnotifications) {
                    $this->load->library('libemail');
                    $this->libemail->sendViewConfirmation($sharer, $this->data['user'], $file);
                }
                if ($sharer->smsnotifications && $sharer->smsnumber) {
                    $this->load->library('nexmo');
                    $this->nexmo->set_format('json');
                    $from = 'DentVault';
                    $to = $sharer->smsnumber;
                    $message = array(
                        'text' => $this->data['user']->firstname . ' (' . $this->data['user']->username . ') has viewed the file "' . $file->filename . '" which you shared with them on DentVault.com'
                    );
                    $response = $this->nexmo->send_message($from, $to, $message);
                }
            }
            echo 'success';
        } else {
            echo 'failed';
        }
        exit;
    }
    
    public function addnote() {
        $fileid = $this->input->post('fileid');
        $note = $this->input->post('note');
        $response = array();
        if ($note) {
            $this->MNote->create(array('fileid' => $fileid, 'ownerid' => $this->data['user']->id, 'note' => $note));
            $this->session->set_flashdata('msg', array('success', lang('files_note_add_success')));

            $response['status'] = "success";
            $response['msg'] = "Note added successfully";

        } else {
             $this->session->set_flashdata('msg', array('error', lang('files_note_add_failed')));
            $response['status'] = "failure";
            $response['msg'] = "Note was not added. Please try again";
        }
        header("Content-Type: application/json");
        echo(json_encode($response));
        exit();
        //redirect('files');
    }
    
    public function filterpatient($pid = 0) {
        if ($pid) {
            if (!$filter = $this->session->userdata('filter'))
                $filter = array();
            $filter['patientid'] = intval($pid);
            $this->session->set_userdata('filter', $filter);
        }
        redirect('files');
    }

    public function filterdays($days = 0) {
        if ($days) {
            if (!$filter = $this->session->userdata('filter'))
                $filter = array();
            $filter['days'] = intval($days);
            $this->session->set_userdata('filter', $filter);
        }
        redirect('files');
    }
    
    public function filterstring() {
        $str = $this->input->post('filter');
        //if ($pid = $this->MPatient->getIdFromString($str)) $this->filterpatient($pid);
        if (!$filter = $this->session->userdata('filter'))
            $filter = array();
        $filter['string'] = $str;
        $this->session->set_userdata('filter', $filter);
        redirect('files');
    }
    
    public function clearfilter() {
        $this->session->unset_userdata('filter');
        redirect('files');
    }
    
    public function patient() {
        $fileid = $this->input->post('fileid');
        $patientname = $this->input->post('patient');
        if ($patientname) {
            if (!$patientid = $this->MPatient->getIdFromName($patientname))
                $patientid = $this->MPatient->newPatient($patientname, $this->data['user']->id);
        } else {
            $patientid = 0;
        }
        if ($this->MFile->updatePatientId($fileid, $patientid))
            $this->session->set_flashdata('msg', array('success', lang('files_update_success')));
        else
            $this->session->set_flashdata('msg', array('error', lang('files_update_failed')));
        redirect('files');
    }
    
    public function zip($fileids) {
        $fileids = str_replace(' ', ',', urldecode($fileids));
        $files = $this->MFile->getFiles($fileids);
        if (count($files) == 1) {
//            $this->load->helper('download');
            $files->rewind();
            $f = $files->current();
            $folder = intval($f->id / 1000);
            $filename = 'files/' . $folder . '/' . $f->id . '/' . $f->version . '/' . $f->filename;
//            force_download($f->filename, $this->libs3->readFile($filename));
//            exit;
            $url = $this->libs3->getAuthorisedUrl($filename, time() + 3600);
            redirect($url);
        } else {
            $this->load->library('zip');
            $totalsize = 0;
            foreach ($files as $f) $totalsize += $f->size;
            if ($totalsize >= 16777216) { // 16Mb
                $this->session->set_flashdata('msg', array('error', lang('files_zip_too_large')));
                redirect('files');
            }
            foreach ($files as $f) {
                $folder = intval($f->id / 1000);
                $filename = 'files/' . $folder . '/' . $f->id . '/' . $f->version . '/' . $f->filename;
                $this->zip->add_data($f->filename, $this->libs3->readFile($filename));
            }
            $this->zip->download('dentvault.zip');
            exit;
        }
    }
    
    public function showdeleted() {
        $currentpackage = $this->MPackage->factory($this->data['user']->packageid);
        $this->data['files'] = $this->MFile->getDeletedFiles($this->data['user']->id, $this->config->item('deleted_file_recovery_days'));
        $this->data['currentpackage'] = $currentpackage;
//echo '<pre>'; print_r($this->data); exit;
        $this->load->view('deletedfiles', $this->data);
    }
    
    public function recover($fileid) {
        $currentpackage = $this->MPackage->factory($this->data['user']->packageid);
        if (!$currentpackage->recovery) {
            $this->session->set_flashdata('msg', array('error', lang('files_no_recovery')));
            redirect('files/showdeleted');
        }
        $file = $this->MFile->factory($fileid);
        if ($file->ownerid == $this->data['user']->id) {
            // user is the owner, allow recovery
            $this->MFile->addShare($fileid, $this->data['user']->id, $file->created);
            $this->MFile->setViewed($fileid, $this->data['user']->id);
            $this->session->set_flashdata('msg', array('success', lang('files_recovered')));
            redirect('files/showdeleted');
        } else {
            $this->session->set_flashdata('msg', array('error', lang('files_not_owner')));
            redirect('files/showdeleted');
        }
    }
    
}
