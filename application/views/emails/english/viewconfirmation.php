<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?php echo $sharee->firstname . ' (' . $sharee->username . ')'; ?> has downloaded the file you shared with them on BioBigBox</title>
    </head>

    <body>
    
        <table style="width: 100%;">
            <tr>
                <td style="width: 40%; padding: 5px 15px; background-color: silver">
                    <h3>FILE DOWNLOADED</h3>
                    <p>
                        <?php echo ($sharee->firstname ? $sharee->firstname . ' (' . $sharee->username . ')' : $sharee->username); ?> 
                        has downloaded the file <strong>"<?php echo $file->filename; ?>"</strong>
                    </p>
                </td>
                <td rowspan="3">
                    <a href="<?php echo $images['emailimage3']['link']; ?>" target="_blank"><img src="<?php echo $images['emailimage3']['src']; ?>" width="100%" /></a>
                </td>
            </tr>
            <tr>
                <td style="background-color: #D8D8D8;; padding: 5px 15px;">
                    <h3>FILE DETAILS</h3>
                    <p>
                        <strong>File (<?php echo file_size($file->size); ?>)</strong><br />
                        <?php echo $file->filename; ?><br /><br />
                        <strong>Will be deleted on</strong><br />
                        <?php $expiry = $sharer->getCurrentPackage()->expiry; echo ($expiry > 0 ? date('M j, Y', strtotime('+ ' . $expiry . ' days')) : 'Never'); ?>
                        <?php if ($sharer->getCurrentPackage()->id = 1) : ?>
                        <br /><br />
                        Do more with BioBigBox.  <a href="http://pages.biobigbox.com/pricing">Upgrade Now</a>
                        <?php endif; ?>
                        
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

