<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'models/objects/base.php');

/*
CREATE TABLE `patients` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(64) NOT NULL,
  `lastname` varchar(64) NOT NULL,
  `identifier` varchar(64) NOT NULL,
  `notes` text NOT NULL,
  `createdby` int(11) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ix_name` (`firstname`,`lastname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/

class patient extends base_item {
    
    protected $data = array('id' => 0,
                          'firstname' => '',
                          'lastname' => '',
                          'identifier' => '',
                          'notes' => '',
                          'createdby' => 0, // id of user
                          'created' => 0);
    
}