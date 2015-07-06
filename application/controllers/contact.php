<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->language('contact');
    }

    public function handleform() {
        if (!$this->session->userdata('user')) {  //not logged in, require captcha
            $captcha = $this->session->userdata('captcha');
            $this->session->unset_userdata('captcha');
            if (strtolower($captcha['word']) != $this->input->post('captcha')) {
                $this->session->set_flashdata('contactform', array('oldform' => $this->input->post(), 'error' => lang('contact_wrong_captcha')));
                redirect($this->input->post('redirect'));
            }
        }
        echo '<pre>'; print_r($_POST); exit;
    }

}