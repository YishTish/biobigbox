<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'models/objects/package.php');

class MPackage extends MY_Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'packages';
        $this->item = 'package';
    }
    
    public function getAll() {
        $Q = $this->db->get($this->table);
        //foreach ($Q->result_array() as $row) { 
        //    $this->add_to_cache($this->item . $row['id'], $row);
        //}
        return new packagecollection($Q->result_array()); 
    }
    
    public function getNames() {
        $Q = $this->db->get($this->table);
        $res = array();
        foreach ($Q->result_array() as $row)
            $res[$row['id']] = $row['name'];
        return $res;
    }

}