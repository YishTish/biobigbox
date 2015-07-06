<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<html>

<head>
<title>BioBigBox - Lab or Scan Center Signup</title>
</head>

<body>
The following user has indicated that they are a lab or scan center.<br />
Once you have confirmed the validity of this claim, click the link at the bottom to confirm and move them to a free unlimited account.<br /><br />

<?php
echo 'Email: ' . $user->username . '<br />';
echo 'First Name: ' . $user->firstname . '<br />';
echo 'Last Name: ' . $user->lastname . '<br />';
echo 'Type: ' . $user->getUserType() . '<br />';
echo 'SMS Number: ' . $user->smsnumber . '<br />';
?>
<br />
<a href="<?php echo site_url('users/authorizelab/' . $user->uuid . '/' . $user->id); ?>">Click here to authorize UNLIMITED ACCOUNT</a><br /><br />

Thank you.<br /><br />

BioBigBox
</body>

</html>