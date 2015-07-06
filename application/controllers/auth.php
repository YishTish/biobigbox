<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->language('auth');
    }

	public function login()
	{
         if(strpos($_SERVER['CONTENT_TYPE'],"json") > 0){
            $request = file_get_contents('php://input');
            $requestJson = json_decode($request);
            $username = $requestJson->username;
            $password = $requestJson->password;
            $user = $this->MUser->login($username, $password);
            if($user){
                $this->session->set_userdata('user', $user->asArray());
                $this->data['error']="";
                $this->data['user_data']=$user->asArray();
                $this->data['user_id']=$user->id;
             }
            else{
                http_response_code(400);
                $this->data['error']=lang('auth_login_failed');
            }
            $this->session->set_userdata('json', true);
           header("Content-Type: application/json");
           echo json_encode($this->data);
           exit();
       }
       else{
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $user = $this->MUser->login($username, $password);
        if ($user) {
                $this->session->set_userdata('user', $user->asArray());
                redirect('files');
        } 
        else {
               $this->session->set_flashdata('msg', array('error', lang('auth_login_failed')));
                redirect('home');
        }
	   }
    }
    
    public function logout() {
        $this->session->sess_create();
        $this->session->set_flashdata('msg', array('info', lang('auth_logged_out')));
        redirect('home');
    }

    public function lightLogout(){
        $this->session->unset_userdata('user');
        $this->data['error']="";
        $this->data['user_data']="";
        $this->data['user_id']=0;
    }
    
    public function register() {
        $this->lightLogout();
         if(strpos($_SERVER['CONTENT_TYPE'],"json") > 0){
            $request = file_get_contents('php://input');
            $requestJson = json_decode($request);
            $username = $requestJson->email;
            $password = $requestJson->password;
            $password2 = $requestJson->password_2;

        }
        else{
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $password2 = $this->input->post('password_2');
        }
            $this->load->helper('email');
            if (!valid_email($username)) 
                $response = Array('msg', Array('error', lang('auth_invalid_email').$username));
                //$this->session->set_flashdata('msg', array('error', lang('auth_invalid_email')));
            elseif ($password != $password2)
                $response = Array('msg', array('error', lang('auth_password_mismatch')));
            elseif (strlen($password) < 6)
               $response = Array('msg', array('error', lang('auth_password_6_chars')));
            elseif($this->MUser->userExists($username))
                $response = Array('msg', array('error', lang('auth_user_exists')));
            else {
                $user = $this->MUser->register($username, $password);
                
                //TODO: send welcome email here?
                $this->load->library('libemail');
                $this->libemail->newUserRegistration($user);
                
                $response = array('msg', array('success', lang('auth_registered')));
                //$user = $this->MUser->login($username, $password);
            }
            if(strpos($_SERVER['CONTENT_TYPE'], 'json')){
                header("Content-Type: application/json");
                $responseArray  = Array();
                if($response[1][0]=="error"){
                    $responseArray['success']=false;
                    http_response_code(400);
                }
                else{
                    $responseArray['success'] = true;
                    $this->session->set_userdata('json',true);
                }
                $responseArray['responseMessage'] = $response[1][1];
                if(isset($user)){
                                    $responseArray['user'] = $user->asArray();
                                    $this->session->set_userdata('user', $user->asArray());
                                    $this->data['user_data']=$user->asArray();
                                }//$response = new array({$this->session->flashdata['msg'],$user});
                echo json_encode($responseArray);
                exit();
            }
            else{
            $this->session->set_flashdata($response);    
            redirect('home');
            }
    }
    
    public function verifyuser($uuid) {
        if ($this->MUser->confirmEmail($uuid)) {
            $this->session->set_flashdata('msg', array('success' => 'Email confirmed'));    
        } else {
            $this->session->set_flashdata('msg', array('error' => 'Error, please try again.'));
        }
        redirect('/');
    }
    
    public function forgotpwd() {
        $username = $this->input->post('username');
        if(!isset($username) || $username=="")
             $username = $this->input->get('username');
        if (!$user = $this->MUser->getUserFromEmail($username)) {
            $this->session->set_flashdata('msg', array('error', lang('auth_not_reg')));
            $response = array('error', lang('auth_not_reg'));
            http_response_code(400);
            //redirect('home');
        }
        else
        {
            $user->regenerateUuid();
            $this->load->library('libemail');
            if ($this->libemail->sendForgotPwd($user)){
                $this->session->set_flashdata('msg', array('success', lang('auth_pwd_sent')));
                $response = array('success', lang('auth_pwd_sent'));
            }
            else
             {
                $this->session->set_flashdata('msg', array('error', lang('auth_send_error')));
                http_response_code(400);
                $response = array('error', lang('auth_send_error'));
            }
        }
        echo json_encode($response);
        exit();
    } 
    
    public function changepwd() {
//echo '<pre>'; print_r($_POST); exit;
        $userdata = $this->session->userdata('user');
        $username = $userdata['username'];
        $oldpass = $this->input->post('oldpass');
        if (!$this->MUser->login($username, $oldpass)) {
            $this->session->set_flashdata('msg', array('error', lang('auth_login_failed')));
            redirect('profile');
        }
        $newpass = $this->input->post('newpass');
        if (strlen($newpass) < 6) {
            $this->session->set_flashdata('msg', array('error', lang('auth_password_6_chars')));
        } elseif ($newpass != $this->input->post('newpass2')) {
            $this->session->set_flashdata('msg', array('error', lang('auth_password_mismatch')));
        } else {
            $this->MUser->changePwd($userdata['id'], $newpass);
            $this->session->set_flashdata('msg', array('success', lang('auth_pwd_updated')));
        }
        redirect('profile');
    }
    
    public function resetpassword($uuid, $userid) {
        if (!$user = $this->MUser->checkUuid($uuid, $userid)) {
            $this->session->set_flashdata('msg', array('error', lang('auth_uuid_mismatch')));
            redirect('home');
        }
        //$this->MUser->clearUuid($uuid, $userid);
        $this->session->set_userdata('resetpwd', array('uuid' => $uuid, 'userid' => $userid));
        redirect('newpassword');
    }
    
    public function newpassword() {
        $data['user'] = $this->MUser->factory($this->session->userdata('user'));
        $data['msg'] = $this->session->flashdata('msg');
        $this->load->view('newpassword', $data);
    }
    
    public function setpassword() {
        $resetpwd = $this->session->userdata('resetpwd');
        /*
            userid and uuid will be sent in the same form as the password
            $userid = $resetpwd['userid'];
            $uuid = $resetpwd['uuid'];
        */
        $userid =  $this->input->post('userid');
        $uuid =  $this->input->post('uuid');
        $password = $this->input->post('password');
        if (!$user = $this->MUser->checkUuid($uuid, $userid)) {
            $this->session->set_flashdata('msg', array('error', lang('auth_uuid_mismatch')));
            $response = array('error', lang('auth_uuid_mismatch'));
        } elseif (strlen($password) < 6) {
            $this->session->set_flashdata('msg', array('error', lang('auth_password_6_chars')));
            $response = array('error', lang('auth_password_6_chars'));
        } elseif ($password != $this->input->post('retypepwd')) {
            $this->session->set_flashdata('msg', array('error', lang('auth_password_mismatch')));
            array('error', lang('auth_password_mismatch'));
        } else {
            $this->MUser->changePwd($user->id, $password);
            $this->session->unset_userdata('resetpwd');
            $this->session->set_flashdata('msg', array('success', lang('auth_pwd_updated_login')));
            $response = array('success', lang('auth_pwd_updated_login'));
        }

        if($response[0]=="error"){
            http_response_code(400);
        }
        echo json_encode($response);
        exit();
        //redirect('home');
    }

    
}
