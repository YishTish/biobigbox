<?php

class Test extends MY_Controller {
    
    function ajaxdownload() {
?>        
     
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.fileDownload.js"></script>
        
<script type="text/javascript">
$(function() {
    $('#filedownload').on('click', function() {
        $.fileDownload('<?php echo base_url(); ?>img/dentvault-logo-1.jpg').successCallback(function() {
            alert('Success!');
        });
        return false;
    });
});
</script>

<a href="<?php echo base_url(); ?>img/dentvault-logo-1.jpg" id="filedownload">Click to download</a>

<?php        
    }
    
    function sms() {
        $this->load->library('bulksms');
        $this->bulksms->run_tests();
    }
    
    function rss() {
        header('Content-Type: text/html; charset=utf-8');
        $rss = new DOMDocument();
        $rss->load('http://wordpress.org/news/feed/');
        $feed = array();
        foreach ($rss->getElementsByTagName('item') as $node) {
        	$item = array ( 
        		'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
        		'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
        		'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
        		'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
        		);
        	array_push($feed, $item);
        }
        $limit = 5;
        for($x=0;$x<$limit;$x++) {
        	$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
        	$link = $feed[$x]['link'];
        	$description = $feed[$x]['desc'];
        	$date = date('l F d, Y', strtotime($feed[$x]['date']));
        	echo '<p><strong><a href="'.$link.'" title="'.$title.'">'.$title.'</a></strong><br />';
        	echo '<small><em>Posted on '.$date.'</em></small></p>';
        	echo '<p>'.$description.'</p>';
        }
    }
    
    function md5() {
        $Q = $this->db->query('select md5("ses@dan35.com") as md5');
        echo $Q->row()->md5 . '<br />' . md5('ses@dan35.com');
    }
    
    function newlayout() {
        $this->load->view('');
    }
    
}