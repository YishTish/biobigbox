<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->view('layout/html_open');
$this->load->view('layout/navigation'); 
?>

    <noscript>
        <div class="alert alert-error">
            Site requires Javascript for full functionality.
        </div>
    </noscript>
    
    <?php if ($msg) : ?>
      <div id="dm-msg" class="alert alert-<?php echo $msg[0]; ?> fade in">
        <button type="button" class="close fade in" data-dismiss="alert">×</button>
        <?php echo $msg[1]; ?>
      </div>
      <script type="text/javascript">
      /*
        setTimeout(function (){
            $('#dm-msg').alert('close');
        }, 8000);
      */
      </script>
    <?php endif; ?>

