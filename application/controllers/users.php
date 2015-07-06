<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller {

    function __construct() {
        parent::__construct();
        // make sure user is logged in, or redirect to home page
        if (!$this->data['user'] = $this->MUser->factory($this->session->userdata('user'))){
            if($this->input->get('json')=='true'){
                die("");
            }
            else redirect();
        }
        $this->data['msg'] = $this->session->flashdata('msg');
        $this->load->language('users');
    }

	public function index()
	{

        $this->data['usertypes'] = $this->MUser->getUserTypes(false);
        $this->data['currentfiles'] = $this->MFile->getFileTotals($this->data['user']->id);
        $this->data['monthfiles'] = $this->MFile->getMonthlyMax($this->data['user']->id);
        if(null !== $this->session->userdata('json') && $this->session->userdata('json')===true){
            $this->data['user'] = $this->session->userdata('user');//$this->MUser->factory($this->session->userdata('user'));
            header("Content-Type: application/json");
            echo json_encode($this->data);
            exit();
        }
        $this->load->view('profile', $this->data);
    }
    
    public function profile() {
        $this->index();
    }
    
    public function saveprofile() {
        $user = $this->data['user'];
        //echo $user->emailnotifications." is different from ".$this->input->post('emailnotifications');
        //die();
        if ($this->input->post('userid') != $user->id) {
            $this->session->set_flashdata('msg', array('error', lang('user_other_profile')));
            redirect('profile');
        }
        if ($this->MUser->updateProfile($user))
            $this->session->set_flashdata('msg', array('success', lang('users_profile_updated')));
        else
            $this->session->set_flashdata('msg', array('error', lang('users_profile_update_failed')));
        if (in_array($this->input->post('typeid'), array(2, 3))) { // 2 = Scan Center, 3 = Laboratory
            $this->load->library('libemail');
            if (!$this->libemail->sendScanLabRequest($this->MUser->factory($user->id))) // reload the user
                $this->session->set_flashdata('msg', array('error', 'Failed to send update email - ' . $this->libemail->getError()->Message));
        }
        redirect('profile');
    }
    
    public function authorizelab($uuid, $userid) {
        if ($this->MUser->authorizeLab($uuid, $userid))
            echo 'Authorized OK.';
        else
            echo 'Failed to authorize.';        
    }
    
    public function upgrade($packageid) {
        $this->data['package'] = $this->MPackage->factory($packageid);
        $paypal_button_ids = $this->config->item('paypal_button_ids');
        $this->data['button_id'] = $paypal_button_ids[$packageid];
//echo '<pre>'; print_r($this->data); exit;
        $this->load->view('upgrade', $this->data);
    }
    
    public function updatepackage($userid, $packageid) { // ajax
        if ($this->data['user']->typeid == 1) // must be a admin
            $this->MUser->updatePackageId($userid, $packageid);
        exit;
    }
    
}