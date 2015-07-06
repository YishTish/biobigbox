<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->data['user'] = $this->MUser->factory($this->session->userdata('user'))) {
            header("HTTP/1.1 401 Unauthorized");
            exit();
        }
            //redirect();
        if ($this->data['user']->typeid != 1) {
            header("HTTP/1.1 401 Unauthorized");
            exit();
        }
//        redirect('files');
        $this->data['msg'] = $this->session->flashdata('msg');
    }

    
    public function dashboard() {
        $this->data['users'] = $this->MUser->getAll()->asArray();
        //die(json_encode($this->MUser->getAll()->asArray()));
        $this->data['newusers'] = $this->MUser->getNewUserStats();
        $this->data['packages'] = $this->MPackage->getNames();
        $this->data['usertypes'] = $this->MUser->getUserTypes();
        $this->data['filecounts'] = $this->MFile->getUserSharesCount();
        foreach($this->data['users'] as $key=>$user){
            $date = new DateTime($user['created']);
            $this->data['users'][$key]['createdFormatted'] =  date('j M Y', strtotime($user['created']));
            $this->data['users'][$key]['lastLoginFormatted'] =  date('j M Y', strtotime($user['lastlogin']));
        }

        if($this->input->get('json')=='true')
        {
            echo json_encode($this->data);
            die();
        }
        else {
            $this->data['users'] = $this->MUser->getAll();
            $this->load->view('dashboard', $this->data);
        }
    }
}