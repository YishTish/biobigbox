<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'models/objects/user.php');

class MUser extends MY_Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'users';
        $this->item = 'user';
    }
    
    public function login($username, $password) {
        $Q = $this->db->where('username', $username)->where('password', 'PASSWORD(' . $this->db->escape($password) . ')', FALSE)->get($this->table);
        if ($Q->num_rows() == 1) {
            $user = $Q->row_array();
            // update last login date/time
            $user['lastlogin'] = date('Y-m-d H:i:s'); // mysql formatted date
            $this->db->set('lastlogin', $user['lastlogin'])->set('confirmed', 1)->where('id', $user['id'])->update($this->table);
            // check if their subscription has expired
            if (time() > strtotime($user['paiduntil'])) {
                $user['packageid'] = $this->config->item('free_package_id');
                $this->updatePackageId($user['id'], $user['packageid']);
            }
            return $this->factory($user);
        } else {
            return false;
        } 
    }
    
    public function userExists($username) {
        $Q = $this->db->where('username', $username)->get($this->table);
        return ($Q->num_rows() == 1);
    }
    
    public function register($username, $password) {
        $this->db->set('username', $username);
        $this->db->set('password', 'PASSWORD("' . $password . '")', FALSE);
        $this->db->set('packageid', 1); // free package
        $this->db->set('emailnotifications', 1);
        $this->db->insert($this->table);
        return $this->factory($this->db->insert_id());
    }
    
    public function getUserFromEmail($email) {
        $Q = $this->db->where('username', $email)->get($this->table);
        return $this->factory($Q->row_array());
    }
    
    public function getUserTypes($includeadmin = TRUE) {
        if (!$includeadmin)
            $this->db->where('id > 1');
        $Q = $this->db->get('usertypes');
        $ret = array();
        foreach ($Q->result_array() as $row)
            $ret[$row['id']] = $row;
        return $ret;
    }
    
    public function getUserType($typeid) {
        $Q = $this->db->get_where('usertypes', 'id = ' . $typeid);
        if ($Q->num_rows() == 1)
            return $Q->row()->name;
        else
            return false;
    }
    
    public function updateProfile($user) {
        $update = false;
        $username = $this->input->post('username');
        if ($username != $user->username) {
            $this->db->set('username', $username);
            $update = true;
        }
        if($this->input->post('emailnotifications')=="true")
            $emailnotifications = 1;
        else
            $emailnotifications = 0;
        if ($emailnotifications != $user->emailnotifications) {
            $this->db->set('emailnotifications', $emailnotifications);
            $update = true;
        }
        $firstname = $this->input->post('firstname');
        if ($firstname != $user->firstname) {
            $this->db->set('firstname', $firstname);
            $update = true;
        }
        $lastname = $this->input->post('lastname');
        if ($lastname != $user->lastname) {
            $this->db->set('lastname', $lastname);
            $update = true;
        }
        $typeid = $this->input->post('typeid');
        if ($typeid != $user->typeid) {
            $this->db->set('typeid', $typeid);
            $update = true;
        }
        $smsnumber = $this->input->post('smsnumber');
        if ($smsnumber != $user->smsnumber) {
            $this->db->set('smsnumber', preg_replace('/\s+/', '', $smsnumber)); // strip all whitespace
            $update = true;
        }
        if($this->input->post('smsnotifications')=="true")
            $smsnotifications = 1;
        else 
            $smsnotifications = 0;//($this->input->post('smsnotifications') ? 1 : 0);
        if ($smsnotifications != $user->smsnotifications) {
            $this->db->set('smsnotifications', $smsnotifications);
            $update = true;
        }
        if ($update) {
            $this->delete_from_cache($this->item . $user->id);
            $this->db->where('id', $user->id);
            $this->db->update($this->table);
            $this->session->set_userdata('user', $this->load($user->id));
            return ($this->db->affected_rows() == 1);
        } else {
            return false;
        }
    }
    
    public function changePwd($userid, $newpass) {
        $this->db->set('password', 'PASSWORD(' . $this->db->escape($newpass) . ')', FALSE)
                 ->where('id', $userid)
                 ->update($this->table);
        return ($this->db->affected_rows() == 1);
    }
    
    public function newUser($email, $newpass = 'dentvault') {
        $this->db->set('username', $email);
        $this->db->set('uuid', uniqid('', true));
        $this->db->set('password', 'PASSWORD(' . $this->db->escape($newpass) . ')', false)->insert($this->table);
        return $this->factory($this->db->insert_id());
    }
    
    public function confirmEmail($uuid) {
        $this->db->set('confirmed', 1);
        $this->db->where('uuid', $uuid);
        $this->db->update($this->table);
        return ($this->db->affected_rows() == 1);
    }
    
    public function regenerateUuid($userid) {
        $this->delete_from_cache($userid);
        $newuuid = uniqid('', true);
        $this->db->update($this->table, array('uuid' => $newuuid), array('id' => $userid));
        return $newuuid;
    }
    
    public function checkUuid($uuid, $userid) {
        $Q = $this->db->where(array('id' => $userid, 'uuid' => $uuid))->get($this->table);
        if ($Q->num_rows() == 1)
            return $this->factory($Q->row_array());
        else
            return false;
    }
    
    public function clearUuid($uuid, $userid) {
        $this->db->update($this->table, array('uuid' => ''), array('uuid' => $uuid, 'id' => $userid));
        return ($this->db->affected_rows() == 1);
    }
    
    public function authorizeLab($uuid, $userid) {
        $this->db->update($this->table, array('packageid' => 4), array('uuid' => $uuid, 'id' => $userid));
        return ($this->db->affected_rows() == 1);
    }
    
    public function getNewUserStats() {
        $Q = $this->db->query('select 
                               sum(if(created > date_sub(now(), interval 1 day), 1, 0)) as lastday,
                               sum(if(created > date_sub(now(), interval 7 day), 1, 0)) as lastweek,
                               sum(if(created > date_sub(now(), interval 30 day), 1, 0)) as lastmonth
                               from users');
        return $Q->row_array();
    }

    public function getAll() {
        $Q = $this->db->order_by('username')->get($this->table);
        return new usercollection($Q->result_array());
    }
    
    public function updatePackageId($userid, $packageid) {
        $this->db->update($this->table, array('packageid' => $packageid), 'id = ' . $userid);
        return ($this->db->affected_rows() == 1);
    }
    
}