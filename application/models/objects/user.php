<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'models/objects/base.php');

/*
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `typeid` int(11) unsigned NOT NULL,
  `packageid` int(11) unsigned NOT NULL,
  'paiduntil' date NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastlogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `password` varchar(64) NOT NULL,
  `uuid` varchar(64) NOT NULL,
  `smsnumber` varchar(64) NOT NULL,
  `emailnotifications` tinyint(1) NOT NULL,
  `smsnotifications` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/

class user extends base_item {
    
    protected $data = array('id' => 0,
                          'username' => '',
                          'firstname' => '',
                          'lastname' => '',
                          'typeid' => 0, 
                          'packageid' => 0,
                          'paiduntil' => 0,
                          'created' => 0,
                          'lastlogin' => 0,
                          'uuid' => '',
                          'smsnumber' => '',
                          'emailnotifications' => 1,
                          'smsnotifications' => 1,
                          'confirmed' => 0);
                          
    public function regenerateUuid() {
        $this->data['uuid'] = parent::$CI->MUser->regenerateUuid($this->data['id']);
    }
    
    public function getCurrentPackage() {
        return parent::$CI->MPackage->factory($this->data['packageid'] ? $this->data['packageid'] : 1);
    }
    
    public function getUserType() {
        return parent::$CI->MUser->getUserType($this->data['typeid']);
    }
    
    public function verifyurl() {
        if (!$this->uuid)
            $this->regenerateUuid();
        return site_url('auth/verifyuser') . '/' . $this->uuid;
    }
    
}

class usercollection extends collection {
    
    public function __construct($raw = null) {
        parent::__construct($raw);
        $CI = &get_instance();
        $this->model = $CI->MUser;
    }
    
}