<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'third_party/aws/sdk.class.php');

class libs3 {
    
    private static $CI;
    private $s3;
    private $bucket;
    private $response;

    function __construct($bucket = 'biobigbox') {
        if (empty(self::$CI)) self::$CI = &get_instance();
        $this->s3 = new AmazonS3(array('key'=> self::$CI->config->item('aws_s3_access_key'), 'secret' => self::$CI->config->item('aws_s3_secret_key')));
        $this->bucket = $bucket;
    }

    function writeFile($filename, $mimetype, $data) {
        $opt = array(
                        'body' => $data,
                        'contentType' => $mimetype,
                        'encryption' => 'AES256',
                        'length' => strlen($data)
                    );
        $this->response = $this->s3->create_object($this->bucket, $filename, $opt);
        return ('20' == substr($this->response->status, 0, 2));
    }

    public function readFile($filename) {
        $res = $this->s3->get_object($this->bucket, $filename);
        if($res->status == 200)
            return $res->body;
        else
            return false;
    }
    
    public function renameFile($oldname, $newname, $newmimetype = '') {
        $this->response = $this->s3->copy_object(array('bucket' => $this->bucket, 'filename' => $oldname), 
                                                 array('bucket' => $this->bucket, 'filename' => $newname),
                                                 ($newmimetype ? array('headers' => array('Content-type' => $newmimetype)) : null));
        if ('20' != substr($this->response->status, 0, 2))
            return false;
        $this->reponse = $this->s3->delete_object($this->bucket, $oldname);
        return ('20' == substr($this->response->status, 0, 2));
    }
    
    public function getFileMeta($filename) {
        $this->response = $this->s3->get_object_headers($this->bucket, $filename);
//echo '<pre>'; print_r($this->response); exit;
        $ret = array();
        foreach ($this->response->header as $key => $value) {
            if (substr($key, 0, 11) == 'x-amz-meta-')
                $ret[substr($key, 11)] = $value;
            elseif ($key == 'content-type')
                $ret['mimetype'] = $value;
            elseif ($key == 'content-length')
                $ret['size'] = $value;
        }        
        return $ret;
    }
    
    public function updateMimetype($filename, $mimetype) {
        $this->response = $this->s3->update_object($this->bucket, $filename, array('headers' => array('Content-type' => $mimetype)));
        return ('20' == substr($this->response->status, 0, 2));
    }
    
    function getError() {
        return $this->response->body->Message;
    }
    
    public function getAuthorisedUrl($filename, $expires) {
        $parts = explode('/', $filename);
        $parts[count($parts) - 1] = urlencode($parts[count($parts) - 1]);
        $filename = implode('/', $parts);
        $stringtosign = "GET\n\n\n$expires\n/" . $this->bucket . '/' . $filename;
        $sig = urlencode(base64_encode(hash_hmac('sha1', utf8_encode($stringtosign), self::$CI->config->item('aws_s3_secret_key'), true)));
        $url = 'https://' . $this->bucket . '.s3.amazonaws.com/' . $filename . '?AWSAccessKeyId=' . self::$CI->config->item('aws_s3_access_key') . '&Expires=' . $expires . '&Signature=' . $sig;
        return $url;
    }
    
}