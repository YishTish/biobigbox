<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'models/objects/patient.php');

class MPatient extends MY_Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'patients';
        $this->item = 'patient';
    }
    
    public function newPatient($patientname, $createdby) {
        if (false !== ($sppos = strpos($patientname, ' '))) {
            $firstname = substr($patientname, 0, $sppos);
            $lastname = substr($patientname, $sppos + 1, strlen($patientname));
        } else {
            $firstname = $patientname;
            $lastname = '';
        }
        $patient = array('firstname' => $firstname,
                         'lastname'  => $lastname,
                         'createdby' => $createdby);
        $this->db->insert($this->table, $patient);
        return $this->db->insert_id();
    }
    
    public function getPatientNamesByOwner($ownerid) {
        $Q = $this->db->get_where($this->table, 'createdby = ' . intval($ownerid));
        $ret = array();
        foreach ($Q->result() as $row)
            $ret[$row->id] = $row->firstname . ' ' . $row->lastname . ($row->identifier ? ' (' . $row->identifier . ')' : '');
        return $ret;
    }
    
    public function getIdFromName($name, $identifier = '') {
        if ($identifier)
            $Q = $this->db->query('select id from ' . $this->table . ' where concat_ws(" ", firstname, lastname) = ? and identifier = ?', array($name, $identifier));
        else
            $Q = $this->db->query('select id from ' . $this->table . ' where concat_ws(" ", firstname, lastname) = ?', array($name));
        if($Q->num_rows() == 1)
            return $Q->row()->id;
        else
            return 0;
    }
    
    public function getIdFromString($search) {
        $Q = $this->db->where('concat_ws(" ", firstname, lastname) like "%' . $search . '%"')->get($this->table);
        if ($Q->num_rows() > 0)
            return $Q->row()->id;
        else
            return false;
    }
    
}