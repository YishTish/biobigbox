<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->data['user'] = $this->MUser->factory($this->session->userdata('user'));
        $this->data['msg'] = $this->session->flashdata('msg');
    }

	public function index()	{
		$this->load->view('main', $this->data);
	}
    
    public function about() {
        $this->load->view('snippets/about', $this->data);
    }
    
    public function faq() {
        $this->load->view('snippets/faq', $this->data);
    }

    public function contact() {
        $this->load->view('snippets/contact', $this->data);
    }
    
    public function tutorials() {
        $this->load->view('tutorials', $this->data);
    }
    
    public function packages() {
        $this->data['packages'] = $this->MPackage->getAll();
        $this->load->view('snippets/plans', $this->data);
    }
    
    public function purchase($packageid) {
        $this->load->language('users');
        $this->data['package'] = $this->MPackage->factory($packageid);
        $paypal_button_ids = $this->config->item('paypal_button_ids');
        $this->data['button_id'] = $paypal_button_ids[$packageid];
        $this->load->view('purchase', $this->data);
    }
    
    public function backgroundimages($link = 'src') {
        $images = $this->MFile->getBackgroundImages();
        $imageRequested = $this->input->get('image');
//echo '<pre>'; print_r($images); exit;
        redirect($images[$imageRequested][$link]);
        exit;
    }
    
    public function confirm($filehash) {
        list($fileid, $hash) = explode('_', $filehash);
        $file = $this->MFile->factory($fileid);
//echo '<pre>' . $hash . ' ' . sha1($file->id . $file->filename . 'b10b1gb0x'); exit;
        if ((sha1($file->id . $file->filename . 'b10b1gb0x') == $hash) && $this->MFile->confirmFile($fileid)) {
            // check if there are any shares for this file and send emails
            $shares = $file->getshares();
            foreach ($shares as $s) {
                if ($s['userid'] == $file->ownerid) continue; // don't send to owner
                $this->load->library('libemail');
                $shuser = $this->MUser->factory($s['userid']);
                if ($shuser->confirmed) {
                    $this->libemail->sendShare($shuser, $file->getOwner(), $file->filename, $file->size);
                } else {
                    // create and save a new password for the user
                    $pass = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 8);
                    $this->MUser->changePwd($shuser->id, $pass);
                    $this->libemail->sendNewUserInvite($shuser, $pass, $file->getOwner(), $file->filename, $file->size);
                }
            }

            $this->session->set_userdata('message', array('status' => 'success', 'message' => 'File confirmed.'));
            redirect('/#/message/Success/File Confirmed - ' . $file->filename);//.$this->session->userdata('message[0]').'a/b'.$this->session->userdata('message[1]'));
        } else {
            redirect('/#/message/Error/An error occurred while trying to confirm the file. Please try again');
            //$this->session->set_userdata('message', array('status' => 'error', 'message' => 'An error occurred while trying to confirm the file.'));
        }
    }
    
    public function doUpgrade($packageid = 2) { // package 2 is "Paid"
        $package = $this->MPackage->factory($packageid);
//echo '<pre>'; print_r($package); exit;
        if (!$package)
            redirect('/#/message/Error/There was an unexpected error, please try again.');
        if ($this->data['user'])
            // user is logged in, redirect to PayPal
            redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_xclick-subscriptions&business=QNR4RRN736BJA&notify_url=' . urlencode('http://biobigbox.com/api/ppipn') . 
                     '&lc=US&item_name=' . urlencode('BioBigBox Paid Account') . '&src=1&a3=' . $package->price . '&p3=1&t3=M&currency_code=USD&no_note=1' .
                     '&custom=' . $this->data['user']->id . '/' . intval($packageid) . '&bn=PP-SubscriptionsBF:btn_subscribe_LG.gif:NonHosted');
        else
            // user is not logged in, show error message
            redirect('/#/message/Error/You must be logged in to upgrade.  Please log in above, or register for an account.');
    }
    
}
