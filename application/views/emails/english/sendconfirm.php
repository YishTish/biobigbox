<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>BioBigBox: ACTION REQUIRED - confirm the file <?php echo $file['filename']; ?></title>
    </head>

    <body>
    
        <table style="width: 100%;">
            <tr>
                <td style="width: 40%; padding: 5px 15px; background-color: silver">
                <h3>ACTION REQUIRED</h3>
                    <p>The file shown below was uploaded to BioBigBox using your email address.
                    Please confirm that you sent this file otherwise it will not be stored or shared.<br /><br /> 
                    <strong><a href="<?php echo site_url('home/confirm') . '/' . $file['id'] . '_' . $hash; ?>">I confirm. Upload and share the file.</a></strong>
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
                        <strong>File (<?php echo file_size($file['size']); ?>)</strong><br />
                        <?php echo $file['filename']; ?><br /><br />
                        <strong>Will be deleted on</strong><br />
                        <?php $expiry = $user->getCurrentPackage()->expiry; echo ($expiry > 0 ? date('M j, Y', strtotime('+ ' . $expiry . ' days')) : 'Never'); ?>
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