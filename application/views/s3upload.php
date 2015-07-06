<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->helper(array('format', 'file'));
$this->load->view('layout/header');
?>

<link href="<?php echo base_url(); ?>css/upload.css" rel="stylesheet" type="text/css" />

<div class="outer row">
    <div class="span10 offset1">
        <div class="alert">
            <h3>Instantly add files to your vault. Instantly share them with others. No registration necessary.</h3>
        </div>
        <?php if (!$user) : ?>
        <div class="well">
            <h3>Your Vault</h3>
            <label>Enter your email address:</label>
            <input class="input input-xlarge" name="owner" id="owner" /> (required)
        </div>
        <?php endif; ?>
        <div class="well">
            <h3>Do you want to share this file?</h3>
            <label>Instantly share with:</label>
            <input class="input input-xlarge" name="sharee" id="sharee" /><br />
            <label>&nbsp;</label> (email address, may be left blank)
        </div>
        <div id="filechooser">
            <h2>Drop files here</h2>
            <h3>or click to select</h3>
            <input type="file" id="files" name="files[]" multiple />
        </div>
        <div class="alert alert-error"><h3>After clicking "Upload", do not close this window until the upload is complete.</h3></div>
        <div id="statuses" class="well">
            <h3>Upload Progress</h3><br />
        </div>
    </div>
</div>

    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/upload.js"></script>

<?php
$this->load->view('layout/footer');
