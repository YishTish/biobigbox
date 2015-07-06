<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo lang('layout_title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Cloud storage for your digital patient dental files" />
    <meta name="author" content="Daniel Morris @ dan35" />
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>img/favicon.ico" />

    <!-- Le styles -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>css/app.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>css/bootstrap-responsive.min.css" rel="stylesheet" />
    
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <script type="text/javascript">
        var is_chrome = <?php echo ($is_chrome ? 'true' : 'false'); ?>;
        var user = <?php echo json_encode($user);?>;
    </script>

    <script type="text/javascript">
      // Google Analytics
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-35782055-1']);
      _gaq.push(['_trackPageview']);
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    
    </script>
  </head>

  <body>

    <div class="container">
