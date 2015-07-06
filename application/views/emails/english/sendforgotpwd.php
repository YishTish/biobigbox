<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>BioBigBox - Password Reset</title>
    </head>

    <body>
    
        <table style="width: 100%;">
            <tr>
                <td style="width: 40%; padding: 5px 15px; background-color: silver">
                    <h3>RESET PASSWORD</h3>
                    <p>
                        A password reset request has been received for your BioBigBox account.<br />
                        If you did not make this request, you can safely ignore this email.<br /><br />
                        
                        To reset your password simply click on the following link.<br />
                        <a href="<?php echo site_url('#/newpassword/' . $user->uuid . '/' . $user->id); ?>">Reset My Password</a><br /><br />
                        
                        Thank you.<br /><br />
                        
                        BioBigBox
                    </p>
                </td>
                <td rowspan="3">
                    <a href="<?php echo $images['emailimage2']['link']; ?>" target="_blank"><img src="<?php echo $images['emailimage2']['src']; ?>" width="100%" /></a>
                </td>
            </tr>
            <tr>
                &nbsp;
            </tr>
            <tr>
                <td style="padding: 5px 15px;">
                    <p>
                        <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>/img/biobigbox-logo.png" /></a><br />
                        <strong>HIPAA compliant file transfer and storage system</strong>
                    </p>
                </td>
            </tr>
            <p></p>
            <p></p>
        </table>
        
    </body>

</html>

