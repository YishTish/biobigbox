<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>DentVault - Cloud storage for your digital patient files in the cloud</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">

    <!-- Le styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 20px;
      }
      .hero-unit p {
        margin-top: 20px;
      }
      form.form-inline {
        margin-bottom: 0;
      }
      .blocks {
        margin-bottom: 20px;
      }
    </style>
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

    <div class="navbar">
      <div class="navbar-inner">
        <div class="container">
          <a class="brand" href="#">Dent Vault</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
            <?php if ($user) : ?>
            <ul class="nav pull-right">
              <li id="fat-menu" class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $user->firstname . ' ' . $user->lastname; ?><b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="profile">View Profile</a></li>
                  <li class="divider"></li>
                  <li><a href="auth/logout">Logout</a></li>
                </ul>
              </li>
            </ul>
            <?php else : ?>
            <!-- Login form -->
            <form class="form-inline pull-right" action="auth/login" method="post">
              <input type="text" name="username" placeholder="username" class="input-small" />
              <input type="password" name="password" placeholder="password" class="input-small" /><input type="submit" class="btn" value="Login" />
            </form>
            <?php endif; ?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <?php if  ($msg) : ?>
      <div class="alert alert-error fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <?php echo $msg; ?>
      </div>
    <?php endif; ?>

      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
        <h1><img src="img/cloudsafe.png"> Dent Vault</h1>
        <p>Helping you store and share your digital patient files in the cloud.</p>
        <p><a class="btn btn-primary btn-large">Learn more &raquo;</a></p>
      </div>
      
      <!-- Example row of columns -->
      <div class="row blocks">
        <div class="span4">
          <h2>Heading</h2>
           <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
        </div>
        <div class="span4">
          <h2>Heading</h2>
           <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
       </div>
        <div class="span4">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn" href="#">View details &raquo;</a></p>
        </div>
      </div>

      <footer class="well">
        <p class="pull-right">&copy; Dent Vault, 2012.  All worldwide rights reserved.</p>
      </footer>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-alert.js"></script>
  </body>
</html>
