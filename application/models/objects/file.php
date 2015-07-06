<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'models/objects/base.php');

/*
CREATE TABLE `files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(256) NOT NULL,
  `ownerid` int(11) unsigned NOT NULL,
  `patientid` int(11) unsigned NOT NULL,
  `size` bigint(20) NOT NULL,
  `mimetype` varchar(64) NOT NULL DEFAULT 'application/octet-stream',
  `status` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT '1',
  `prevverid` int(11) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ix_owner` (`ownerid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
*/

class file extends base_item {
    
    protected $data = array('id' => 0,
                            'filename' => '',
                            'ownerid' => '',
                            'patientid' => 0,
                            'size' => 0,
                            'mimetype' => '',
                            'status' => 1,
                            'version' => 1,
                            'ver1id' => 0,
                            'created' => 0);
                            
    



    public function getPatient() {
        if ($this->data['patientid'] == 0)
            return false;
        $patient = parent::$CI->MPatient->factory($this->data['patientid']);
        return $patient;
    }
    
    public function getOwner() {
        if ($this->data['ownerid'] == 0)
            return false;
        $owner = parent::$CI->MUser->factory($this->data['ownerid']);
        return $owner;
    }

    public function notesCount() {
        return parent::$CI->MNote->fileNotesCount($this->data['id']);
    }
    
    public function sharesCount() {
        return parent::$CI->MFile->sharesCount($this->data['id']);
    }
    
    public function getVersions($as_array = false) {
        return parent::$CI->MFile->getVersions($this->data['id'], $as_array);
    }
    
    public function getNotes() {
        return parent::$CI->MNote->getFileNotes($this->data['id']);
    }
    
    public function getShares() {
        return parent::$CI->MFile->getShares($this->data['id']);
    }
    
    public function removeShare($userid) {
        return parent::$CI->MFile->removeShare($this->data['id'], $userid); 
    }
    
    public function getURL($version = 0) {
        $folder = intval($this->data['id'] / 1000);
        if (!$version) $version = $this->data['version'];
        return parent::$CI->libs3->getAuthorisedUrl('files/' . $folder . '/' . $this->data['id'] . '/' . $version . '/' . $this->data['filename'], time() + 7200);
    }
    
    public function viewed($userid) {
        return parent::$CI->MFile->viewedByUser($this->data['id'], $userid);
    }
    
    public function getMaxVersion() {
        return parent::$CI->MFile->getMaxVersion($this->data['id']);
    }
    
    public function getSharer($userid) { // get the user who shared this file with $userid
        $sharerid = parent::$CI->MFile->getSharerId($this->data['id'], $userid);
        return parent::$CI->MUser->factory($sharerid);
    }

}

class filecollection extends collection {
    
    public function __construct(array $raw = null) {
        parent::__construct($raw);
        $CI = &get_instance();
        $this->model = $CI->MFile;
    }
    
}