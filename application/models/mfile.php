<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'models/objects/file.php');

class MFile extends MY_Model {
    
    public function __construct() {
        parent::__construct();
        $this->table = 'files';
        $this->item = 'file';
    }
    
    public function create($filedata) {
        $this->db->insert($this->table, $filedata);
        $fileid = $this->db->insert_id();
        if (!isset($filedata['ver1id']) OR ($filedata['ver1id'] == 0))
            $this->db->update($this->table, array('ver1id' => $fileid), 'id = ' . $fileid); 
        return $fileid;
    }
    
    public function getUserFiles($userid, $pid = false, $days = false, $search = false) {
        $where = array('s.userid = ' . $userid, 
                       'f.version = (select max(version) from files where ver1id = f.ver1id)');
        if ($pid)
            $where[] = 'f.patientid = '. $pid;
        if ($days)
            $where[] = 'f.created > DATE_SUB(NOW(), INTERVAL ' . intval($days) . ' DAY)';
        if ($search)
            $where[] = '(f.filename like "%' . $search . '%" or p.firstname like "%' . $search . '%" or p.lastname like "%' . $search . '%")';
        $Q = $this->db->query('select f.* from ' . $this->table . ' f 
                               left outer join shares s on s.fileid = f.id 
                               left outer join patients p on p.id = f.patientid
                               where ' . implode(' and ', $where) . ' and confirmed = 1 
                               order by s.datetime desc');
        return new filecollection($Q->result_array());
    }
    
    public function getStatuses() {
        $Q = $this->db->get('statuses');
        $ret = array();
        foreach ($Q->result_array() as $row) $ret[$row['id']] = $row;
        return $ret;
    }
    
    // can accept an array of file ids or a comma separated list
    public function getFiles($ids, $allow_temp=false) {
        if (!is_array($ids))
            $ids = explode(',', $ids);
        if(!$allow_temp) {
            $Q = $this->db->where('confirmed', 1)->where_in('id', $ids)->get($this->table);
        }
        else{
            $Q = $this->db->where_in('id', $ids)->get($this->table);
        }

        return new filecollection($Q->result_array());
    }
    
    public function getVersions($id, $as_array = false) {
        $Q = $this->db->select('f.*, u.username, u.firstname, u.lastname')
                      ->from($this->table . ' f')
                      ->join('users u', 'u.id = f.ownerid')
                      ->where('ver1id', '(select ver1id from files where id = ' . intval($id) . ')', false)
                      ->where('confirmed', 1)
                      ->order_by('version', 'desc')
                      ->get();
        if ($as_array) {
            $ret = $Q->result_array();
            foreach ($ret as $k => $f) {
                $folder = intval($f['id'] / 1000);
                $ret[$k]['url'] = $this->libs3->getAuthorisedUrl('files/' . $folder . '/' . $f['id'] . '/' . $f['version'] . '/' . $f['filename'], time() + 7200);
            }
            return $ret;
        } else {
            return new filecollection($Q->result_array());
        }
    }
    
    public function sharesCount($id) {
        $Q = $this->db->select('count(*) as count')->from('shares')->where('fileid', $id)->get();
        return $Q->row()->count;
    }
    
    public function getShares($id) {
        $Q = $this->db->select('u.id as userid, u.username, u.firstname, u.lastname, f.confirmed, s.datetime')->from('users u')->join('shares s', 's.userid = u.id')->join('files f', 's.fileid = f.id')->where('s.fileid = ' . $id)->get();
        return $Q->result_array();
    }
    
    public function removeShare($fileid, $userid) {
        $this->db->delete('shares', array('fileid' => $fileid, 'userid' => $userid));
        return ($this->db->affected_rows() == 1);
    }
    
    public function addShare($fileid, $userid, $date = false) {
        if (!$date) $date = date('Y-m-d H:i:s');
        $this->db->query('REPLACE INTO `shares` (`fileid`, `userid`, `datetime`) VALUES (?, ?, ?)', array($fileid, $userid, $date));
        return $this->db->affected_rows();
    }
    
    public function getUserSharesCount() {
        $Q = $this->db->select('userid, count(*) as count')->group_by('userid')->get('shares');
        $res = array();
        foreach ($Q->result_array() as $row)
            $res[$row['userid']] = $row['count'];
        return $res;
    }
    
    public function isSharedWith($fileid, $userid) {
        $Q = $this->db->where('userid', $userid)->where('fileid', $fileid)->get('shares');
        return($Q->num_rows() > 0);
    }
    
    public function updateStatus($fileid, $status) {
        $this->db->set('status', $status)->where('id', $fileid)->update($this->table);
        return ($this->db->affected_rows() == 1);
    }
    
    public function setViewed($fileid, $userid) {
        $this->db->update('shares', array('viewed' => 1), array('fileid' => $fileid, 'userid' => $userid));
        return ($this->db->affected_rows() == 1);
    }
    
    public function deleteFileEntry($fileid) {
        $this->db->delete($this->table, 'id = ' . $fileid);
        return ($this->db->affected_rows() == 1);
    }
    
    public function getFileTotals($userid) {
        $Q = $this->db->query('
            SELECT count(*) as count, sum(size) as total, sum(if(not isnull(d.fileid), f.size, 0)) as downloads
            FROM files f
            JOIN shares s ON s.fileid = f.id
            LEFT OUTER JOIN downloads d ON d.fileid = f.id and d.userid = s.userid and d.timestamp > subdate(now(), interval 1 month)
            WHERE s.userid = ' . intval($userid) . '
            AND f.confirmed = 1');
        return $Q->row();
    }
    
    public function getMonthlyMax($userid, $month = false) {
        if (!$month) $month = date('Y-m-01');
        while (!$ret = $this->db->get_where('monthtotals', 'userid = ' . intval($userid) . ' and month = "' . $month . '"')->row())
            $this->updateMonthlyMax($userid);
        return $ret;
    }
    
    public function updateMonthlyMax($userid) {
        // calculate total files now and update monthly max total if neccessary
        $stats = $this->getFileTotals($userid);
        $count = $stats->count;
        $total = $stats->total;
        if (is_null($total)) $total = 0;
        $this->db->query('insert into monthtotals (userid, month, maxcount, maxtotal) values (?, "' . date('Y-m-01') . '", ?, ?) 
                          on duplicate key update maxcount = if(maxcount > ?, maxcount, ?), maxtotal = if(maxtotal > ?, maxtotal, ?)', 
                          array($userid, $count, $total, $count, $count, $total, $total));
        return $total;
    }
    
    public function viewedByUser($fileid, $userid) {
        $Q = $this->db->where('fileid', $fileid)->where('userid', $userid)->get('shares');
        if (!$Q->num_rows())
            return false;
        if ($Q->row()->viewed)
            return true;
        else
            return false;
    }
    
    public function getMaxVersion($fileid) {
        $Q = $this->db->select('max(version) as maxversion')
                      ->from($this->table)
                      ->where('ver1id', '(select ver1id from files where id = ' . $fileid . ')', false)
                      ->where('confirmed', 1)
                      ->get();
        return ($Q->row()->maxversion);
    }
    
    public function updatePatientId($fileid, $patientid) {
        $this->db->update($this->table, array('patientid' => $patientid), array('id' => $fileid));
        return $this->db->affected_rows();
    }
    
    public function getDeletedFiles($userid, $days = 0) {
        $Q = $this->db->query('
            select * from files f 
            where ownerid = ? ' .  
            ($days ? 'and created > subdate(now(), "' . intval($days) . ' days")' : '') . '
            and 0 = (select count(*) from shares s where s.fileid = f.id and userid = ?)
            and version = (select max(version) from files where ver1id = f.ver1id)
            order by created desc', 
            array($userid, $userid)
        );
        return new filecollection($Q->result_array());
    }
    
    public function getSharerId($fileid, $userid) {
        $Q = $this->db->select('sharerid')->where('fileid', $fileid)->where('userid', $userid)->get('shares');
        return $Q->row()->sharerid;
    }
    
    public function getBackgroundImages() {
        $this->load->library('libs3');
        $imageuserid = $this->config->item('admin_id');
        $Q = $this->db->query('
            select f.*, g.filename, g.version, n.note from
            (select substring_index(filename, ".", 1) as name, max(id) as id
            from files 
            where ownerid =  ' . intval($imageuserid) . '
            and (filename like "webimage%" or filename like "emailimage%") 
            group by name) f
            join files g on g.id = f.id
            left outer join notes n on n.fileid = f.id
            where ((n.id is null) or (n.id = (select max(id) from notes where fileid = f.id)))');
        $ret = array();
        foreach ($Q->result_array() as $f) {
            $folder = intval($f['id'] / 1000);
            $ret[$f['name']] = array('src' => $this->libs3->getAuthorisedUrl('files/' . $folder . '/' . $f['id'] . '/' . $f['version'] . '/' . $f['filename'], strtotime('+1 year')),
                                     'link' => $f['note']); 
        }
        return $ret;        
    }
    
    public function confirmFile($fileid) {
        $this->db->set('confirmed', 1)->where('id', $fileid)->update($this->table);
        return ($this->db->affected_rows() == 1);
    }
    
    public function storeDownload($fileid, $userid) {
        $this->db->insert('downloads', array('fileid' => $fileid, 'userid' => $userid));
        return ($this->db->affected_rows() == 1);
    }
    
}