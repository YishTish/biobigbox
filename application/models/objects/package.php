<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'models/objects/base.php');

/*
CREATE TABLE `packages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `storage` bigint(20) NOT NULL COMMENT '0 for unlimited',
  `bandwidth` bigint(20) NOT NULL COMMENT '0 for unlimited',
  `expiry` int(11) NOT NULL COMMENT 'file duration - 0 for never',
  `receiveshare` tinyint(1) NOT NULL,
  `sendshare` tinyint(1) NOT NULL,
  `recovery` tinyint(1) NOT NULL COMMENT 'of deleted files',
  `basicviewing` tinyint(1) NOT NULL,
  `advancedviewing` tinyint(1) NOT NULL,
  `shareconfirm` tinyint(1) NOT NULL,
  `emailnotify` tinyint(1) NOT NULL,
  `smsnotify` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

class package extends base_item {
    
    protected $data = array('id' => 0,
                            'name' => '',
                            'price' => 0.0,
                            'storage' => 0,
                            'bandwidth' => 0,
                            'expiry' => 0,
                            'receiveshare' => 0,
                            'sendshare' => 0,
                            'recovery' => 0,
                            'basicviewing' => 0,
                            'advancedviewing' => 0,
                            'shareconfirm' => 0,
                            'emailnotify' => 0,
                            'smsnotify' => 0);
}

class packagecollection extends collection {
    
    public function __construct(array $raw = null) {
        parent::__construct($raw);
        $CI = &get_instance();
        $this->model = $CI->MPackage;
    }
    
}