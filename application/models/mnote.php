<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'models/objects/note.php');

class MNote extends MY_Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'notes';
        $this->item = 'note';
    }
    
    public function create($notedata) {
        $this->db->insert($this->table, $notedata);
        return $this->db->insert_id();
    }
    
    public function fileNotesCount($fileid) {
        $Q = $this->db->select('count(*) as count')->where('fileid', $fileid)->get($this->table);
        return $Q->row()->count;
    }
    
    public function getFileNotes($fileid) {
        $Q = $this->db->select('n.added, n.note, concat_ws(" ", u.firstname, u.lastname) as owner', false)
                      ->from($this->table . ' n')->join('users u', 'n.ownerid = u.id')->join('files f', 'f.id = n.fileid')
                      ->where('f.ver1id', '(select ver1id from files where id = ' . $fileid . ')', false)
                      ->order_by('added', 'desc')->get();
        return $Q->result_array();
    }
    
}