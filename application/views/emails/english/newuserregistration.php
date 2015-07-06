<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>You have successfully registered on BioBigBox.com</title>
    </head>

    <body>
    
        <table style="width: 100%;">
            <tr>
                <td style="width: 40%; padding: 5px 15px; background-color: silver">
                <h3>BIOBIGBOX REGISTRATION</h3>
                    <p>
                        You have successfully registered on <a href="http://biobigbox.com">biobigbox.com</a><br /><br /> 
                        <strong><a href="<?php echo $newuser->verifyurl(); ?>">Click here to verify your account.</a></strong><br /><br />
                        If the above link does not work, copy and paste the following URL into your browser.<br />
                        <?php echo $newuser->verifyurl(); ?>
                    </p>
                </td>
                <td rowspan="3">
                    <a href="<?php echo $images['emailimage2']['link']; ?>" target="_blank"><img src="<?php echo $images['emailimage2']['src']; ?>" width="100%" /></a>
                </td>
            </tr>
            <tr>
                <td style="background-color: #D8D8D8;; padding: 5px 15px;">
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
