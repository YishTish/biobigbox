<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en" ng-app="BioBigBox">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">
    
    <link href='https://fonts.googleapis.com/css?family=Audiowide' rel='stylesheet' type='text/css'>
    <title>BioBigBox</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    <link href="/css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="/css/BioBigBox.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <meta name="globalsign-domain-verification" content="Jw48BMWIGH1MhWH3PBtMVagZLNbPOlfAinnio2_PTX" />    

    <!-- Custom styles for this template -->
   

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Cloud storage for your digital patient dental files" />
    <meta name="author" content="Daniel Morris @ dan35" />
    <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico" />

    <!-- Le styles -->
    
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
      <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    
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
  <body ng-controller="mainCtrl">
  
    <div class="site-wrapper" style="position: relative; ">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div x-bio-big-box-header></div>

          <div ng-view ng-show="centerDivCurrent()"> </div>
        
          <div class="clearfix"></div>
          
          <div x-bio-big-box-footer></div>
          
        </div>
           
      </div>
    
    </div>
    
    <a id="background-link" href="#" target="_blank" style="display: none;"></a>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="/js/ie10-viewport-bug-workaround.js"></script>

    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.0/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.0/angular-route.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.0/angular-cookies.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/0.10.0/ui-bootstrap-tpls.min.js"></script>

    <!--Dedicated Javascript file for this project -->
    <script src="/js/BigBoxBio.js"></script>
    <script src="/js/angularConfig.js"></script>
    <script src="/js/angularServices.js"></script>
    <script src="/js/httpRequestHelpers.js"></script>
    <script src="/js/angularControllers.js"></script>
    <script src="/js/angularDirectives.js"></script>

  </body>
</html>
