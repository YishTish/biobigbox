<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
 
    protected $data = array();

    public function __construct() {
        parent::__construct();
        if (strpos(base_url(), 'localhost')) $this->output->enable_profiler();
        if (!$captcha = $this->session->userdata('captcha')) {
            $captcha = create_captcha(array(
                                        'img_path'	 => FCPATH . 'img/captcha/',
                                        'img_url'	 => base_url() . 'img/captcha/'
                                     ));
            $this->session->set_userdata('captcha', $captcha);
        }
        $this->data['captcha'] = $captcha;
        $this->data['is_chrome'] = ($this->agent->is_browser('Chrome'));
    } 
    
}