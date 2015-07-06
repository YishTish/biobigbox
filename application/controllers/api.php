<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends MY_Controller {
    
    function ppipn() {
        $postdata = file_get_contents("php://input");
        parse_str($postdata, $postarr);
        log_message('info', 'PPIPN: ' . $postdata);

        if (isset($postarr['test_ipn']) && ($postarr['test_ipn'] == 1)) {
            $curl_url = 'https://www.sandbox.paypal.com'; 
        } else {
            $curl_url = 'https://www.paypal.com';
        }
        log_message('info', 'PPIPN: CURL URL: ' . $curl_url);

        //$res = file_get_contents($curl_url . '/cgi-bin/webscr?cmd=_notify-validate&' . $postdata);
        $ch = curl_init($curl_url . '/cgi-bin/webscr?cmd=_notify-validate&' . $postdata);
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $error = '';
        if (!$res = curl_exec($ch)) {
            $error = curl_error($ch);
        };
        $info = curl_getinfo($ch);
        curl_close($ch);

        log_message('info', 'PPIPN: CURL res: /' . $res . '/ error: /' . $error . '/ getinfo: ' . print_r($info, true));

        if($error) {
            log_message('error', 'PPIPN: curl error ' . $error);
            exit;
        }
        
        $userid = 0; $packageid = 0;
        if (isset($postarr['custom']))
            list($userid, $packageid) = explode('/', $postarr['custom']); // custom field is "userid/packageid"
        
        // Save the IPN data to a DB table
        $data = array(
            'txn_id' => $postarr['txn_id'],
            'payment_status' => $postarr['payment_status'],
            'validate' => $res,
            'amount' => $postarr['mc_gross'],
            'fee' => $postarr['mc_fee'],
            'email' => $postarr['payer_email'],
            'name' => $postarr['address_name'],
            'state' => $postarr['address_state'],
            'country' => $postarr['address_country'],
            'userid' => $userid,
            'packageid' => $packageid,
            'postdata' => serialize($postarr)
        );
        $this->db->insert('ppipn', $data);
        
        if ($res != 'VERIFIED') {
            // what to do if the IPN is not verified
            log_message('error', 'PPIPN: NOT VERIFIED');
            exit; // do nothing 
        } 

        if (($postarr['payment_status'] != 'Completed') && ($postarr['payment_status'] != 'Refunded') && ($postarr['payment_status'] != 'Pending')) {  
            // what to do if the payment failed
            // for now do nothing
        }

        if ($postarr['payment_status'] == 'Completed') {
            // what to do with a completed payment
            // update the user's package & expiry date
            $this->db->set('packageid', $packageid);
            $this->db->set('paiduntil', date('Y-m-d', strtotime('+ 1 month')));
            $this->db->where('id', $userid);
            $this->db->update('users');
        }
    }

    function getStatuses(){
        echo json_encode($this->MFile->getStatuses());
        exit();
    }


}