<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
    
    protected $table = ''; // set in child with table name
    protected $item = ''; // set in child with object name
    protected $cache = array();
    
    public function __construct() {
        parent::__construct();
    }

    public function add_to_cache($id, $data) {
        $this->cache[$id] = $data;
    }
    
    public function get_from_cache($id) {
        if (array_key_exists($id, $this->cache))
            return $this->cache[$id];
        else
            return false;
    }

    public function delete_from_cache($id) {
        unset($this->cache[$id]);
    }
    
    protected function load($id) {
        if (!$ret = $this->get_from_cache($this->item . $id)) {
            $Q = $this->db->get_where($this->table, 'id = ' . intval($id));
            $ret = $Q->row_array();
            unset($ret['password']);
            $this->add_to_cache($this->item . $id, $ret);
        }
        return $ret;
    }
    
    // $data can be an array of properties or a single integer id
    public function factory($data) {
        if (!$data)
            return false;
        if (is_array($data))
            $this->add_to_cache($this->item . $data['id'], $data);
        elseif (intval($data) > 0) 
            $data = $this->load(intval($data));
        else
            show_error('Factory function expects integer > 0 or array.');
        if ($data)
            return new $this->item($data);
        else
            return false;
    }
    
}