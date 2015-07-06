<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'models/objects/base.php');

/*
CREATE TABLE `notes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fileid` int(11) unsigned NOT NULL,
  `ownerid` int(11) unsigned NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` text,
  PRIMARY KEY (`id`),
  KEY `ix_file` (`fileid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

class note extends base_item {
    
    protected $data = array('id' => 0,
                            'fileid' => '',
                            'ownerid' => '',
                            'added' => '',
                            'note' => '');
                            
}

class notecollection extends collection {
    
    public function __construct(array $raw = null) {
        parent::__construct($raw);
        $CI = &get_instance();
        $this->model = $CI->MNote;
    }
    
}