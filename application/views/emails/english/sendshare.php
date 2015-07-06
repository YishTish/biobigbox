<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
if ($sharer->firstname)
    $name = $sharer->firstname . ' (' . $sharer->username . ')';
else   
    $name = $sharer->username;
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>You have recieved the file <?php echo $filename; ?> from <?php echo $name; ?> via the HIPAA compliant BioBigBox system</title>
    </head>

    <body>
    
        <table style="width: 100%;">
            <tr>
                <td style="width: 40%; padding: 5px 15px; background-color: silver">
                    <h3>FILE RECEIVED</h3>
                    <p>
                        <?php echo $name; ?> has sent you a file.<br />
                        You can download the file by logging in to your account at<br />
                        <a href="<?php echo base_url(); ?>"><?php echo base_url(); ?></a>
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
                        <?php $expiry = $sharer->getCurrentPackage()->expiry; echo ($expiry > 0 ? date('M j, Y', strtotime('+ ' . $expiry . ' days')) : 'Never'); ?>
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

