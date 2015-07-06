<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>You have successfully uploaded the file <?php echo $filename; ?> to BioBigBox</title>
    </head>

    <body>
    
        <table style="width: 100%;">
            <tr>
                <td style="width: 40%; padding: 5px 15px; background-color: silver">
                <h3>UPLOAD SUCCESSFUL</h3>
                    <p>
                        You have successfully uploaded the file <?php echo $filename; ?> to <a href="<?php echo base_url(); ?>">BioBigBox</a><br />
                        You can now login using your email address and the password "<?php echo $password; ?>".
                    </p>
                </td>
                <td rowspan="3">
                    <a href="<?php echo $images['emailimage1']['link']; ?>" target="_blank"><img src="<?php echo $images['emailimage1']['src']; ?>" width="100%" /></a>
                </td>
            </tr>
            <tr>
                <td style="background-color: #D8D8D8;; padding: 5px 15px;">
                    <h3>FILE DETAILS</h3>
                    <p>
                        <strong>File (<?php echo file_size($size); ?>)</strong><br />
                        <?php echo $filename; ?><br /><br />
                        <strong>Will be deleted on</strong><br />
                        <?php $expiry = $newuser->getCurrentPackage()->expiry; echo ($expiry > 0 ? date('M j, Y', strtotime('+ ' . $expiry . ' days')) : 'Never'); ?>
                    </p>
                </td>
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
