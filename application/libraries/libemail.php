<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'third_party/aws/sdk.class.php');

class libemail {
    
    private static $CI;
    private $ses;
    private $source;
    private $response;
    private $images;
    
    function __construct() {
        if (empty(self::$CI)) self::$CI = &get_instance();
        $this->ses = new AmazonSES(array('key'=> self::$CI->config->item('aws_ses_access_key'), 'secret' => self::$CI->config->item('aws_ses_secret_key')));
        $this->source = self::$CI->config->item('email_source');
        $this->images = self::$CI->MFile->getBackgroundImages();
        self::$CI->load->helper('format');
    }
    
    function getResponse() {
        return $this->response;
    }
    
    function getError() {
        return $this->response->body->Error;
    }
    
    function newUserRegistration($user) {
        $view = 'emails/' . self::$CI->config->item('language') . '/newuserregistration';
        $html = self::$CI->load->view($view, array('newuser' => $user, 'images' => $this->images), TRUE);
        $message = array(
            'Subject' => array('Data' => lang('files_newuser')),
            'Body' => array(
                'Text' => array('Data' => strip_tags($html)),
                'Html' => array('Data' => $html)
            )
        );
        $this->response = $this->ses->send_email($this->source, array('ToAddresses' => array($user->username)), $message); 
//var_dump($this->response); exit;
        return (substr($this->response->status, 0, 2) == '20');
    }
    
    function sendNewUser($user, $pass, $filename, $size) {
        $view = 'emails/' . self::$CI->config->item('language') . '/newuserupload';
        $html = self::$CI->load->view($view, array('newuser' => $user, 'password' => $pass, 'filename' => $filename, 'size' => $size, 'images' => $this->images), TRUE);
        $message = array(
            'Subject' => array('Data' => 'You have successfully uploaded the file ' . $filename . ' to BioBigBox'),
            'Body' => array(
                'Text' => array('Data' => strip_tags($html)),
                'Html' => array('Data' => $html)
            )
        );
        $this->response = $this->ses->send_email($this->source, array('ToAddresses' => array($user->username)), $message);
//var_dump($this->response); exit;
        return (substr($this->response->status, 0, 2) == '20');
    }
    
    function sendNewUserInvite($newuser, $newpass, $inviter, $filename, $size) {
        if ($inviter->firstname)
            $subject = $inviter->firstname . ' (' . $inviter->username . ') ' . lang('files_has_shared') . ': ' . $filename;
        else
            $subject = $inviter->username . lang('files_has_shared') . ': ' . $filename;
        $view = 'emails/' . self::$CI->config->item('language') . '/sendnewuserinvite';
        $html = self::$CI->load->view($view, array('newuser' => $newuser, 'newpass' => $newpass, 'inviter' => $inviter, 'filename' => $filename, 'size' => $size, 'images' => $this->images), TRUE);
        $message = array(
            'Subject' => array('Data' => $subject),
            'Body' => array(
                'Text' => array('Data' => strip_tags($html)),
                'Html' => array('Data' => $html)
            )
        );
        $this->response = $this->ses->send_email($this->source, array('ToAddresses' => array($newuser->username)), $message); 
//var_dump($this->response); exit;
        return (substr($this->response->status, 0, 2) == '20');
    }
    
    function sendShare($sharee, $sharer, $filename, $size) {
        if ($sharer->firstname)
            $subject = $sharer->firstname . ' (' . $sharer->username . ') ' . lang('files_has_shared') . ': ' . $filename;
        else
            $subject = $sharer->username . lang('files_has_shared') . ': ' . $filename;
        $view = 'emails/' . self::$CI->config->item('language') . '/sendshare';
        $html = self::$CI->load->view($view, array('sharee' => $sharee, 'sharer' => $sharer, 'filename' => $filename, 'size' => $size, 'images' => $this->images), TRUE);
        $message = array(
            'Subject' => array('Data' => $subject),
            'Body' => array(
                'Text' => array('Data' => strip_tags($html)),
                'Html' => array('Data' => $html)
            )
        );
        $this->response = $this->ses->send_email($this->source, array('ToAddresses' => array($sharee->username)), $message);
        return (substr($this->response->status, 0, 2) == '20'); 
    }
    
    function sendFileConfirm($user, $file) {
        $hash = sha1($file['id'] . $file['filename'] . 'b10b1gb0x'); // ready salted
        $subject = 'ACTION REQUIRED TO UPLOAD/SEND FILE. You have sent file ' . $file['filename'] . ' via the HIPAA compliant BioBigBox system';
        $view = 'emails/' . self::$CI->config->item('language') . '/sendconfirm';
        $html = self::$CI->load->view($view, array('user' => $user, 'file' => $file, 'hash' => $hash, 'images' => $this->images), TRUE);
        $message = array(
            'Subject' => array('Data' => $subject),
            'Body' => array(
                'Text' => array('Data' => strip_tags($html)),
                'Html' => array('Data' => $html)
            )
        );
        $this->response = $this->ses->send_email($this->source, array('ToAddresses' => array($user->username)), $message);
        return (substr($this->response->status, 0, 2) == '20'); 
    }
    
    function sendForgotPwd($user) {
        $subject = lang('auth_pwd_reminder');
        $view = 'emails/' . self::$CI->config->item('language') . '/sendforgotpwd';
        $html = self::$CI->load->view($view, array('user' => $user, 'images' => $this->images), TRUE);
        $message = array(
            'Subject' => array('Data' => $subject),
            'Body' => array(
                'Text' => array('Data' => strip_tags($html)),
                'Html' => array('Data' => $html)
            )
        );
        $this->response = $this->ses->send_email($this->source, array('ToAddresses' => array($user->username)), $message); 
        return (substr($this->response->status, 0, 2) == '20');
    }
    
    function sendScanLabRequest($user) {
        $subject = 'BioBigBox - Lab or Scan Center Signup';
        $view = 'emails/' . self::$CI->config->item('language') . '/scanlabrequest';
        $html = self::$CI->load->view($view, array('user' => $user, 'images' => $this->images), TRUE);
        $message = array(
            'Subject' => array('Data' => $subject),
            'Body' => array(
                'Text' => array('Data' => strip_tags($html)),
                'Html' => array('Data' => $html)
            )
        );
        $this->response = $this->ses->send_email($this->source, array('ToAddresses' => array('admin@biobigbox.com')), $message); 
//var_dump($this->response); exit;
        return (substr($this->response->status, 0, 2) == '20');
    }
    
    function sendViewConfirmation($sharer, $sharee, $file) {
        $subject = 'Download confirmation from ' . $sharee->username . ' via BioBigBox';
        $view = 'emails/' . self::$CI->config->item('language') . '/viewconfirmation';
        $html = self::$CI->load->view($view, array('sharer' => $sharer, 'sharee' => $sharee, 'file' => $file, 'images' => $this->images), TRUE);
        $message = array(
            'Subject' => array('Data' => $subject),
            'Body' => array(
                'Text' => array('Data' => strip_tags($html)),
                'Html' => array('Data' => $html)
            )
        );
        $this->response = $this->ses->send_email($this->source, array('ToAddresses' => array($sharer->username)), $message); 
        return (substr($this->response->status, 0, 2) == '20');
    }
    
}