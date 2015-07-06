<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->view('layout/header');
?>

<h3 class="title-block">Upload a new file</h3>

<form class="form-horizontal" method="post" action="https://dentvault.s3.amazonaws.com/" enctype="multipart/form-data">
    <input type="hidden" name="key" value="temp/${filename}">
    <input type="hidden" name="AWSAccessKeyId" value="<?php echo $this->config->item('aws_access_key'); ?>"> 
    <input type="hidden" name="acl" value="private"> 
    <input type="hidden" name="success_action_redirect" value="<?php echo site_url('upload/s3return'); ?>">
    <input type="hidden" name="policy" value="<?php echo $policy; ?>">
    <input type="hidden" name="signature" value="<?php echo $signature; ?>">
<?php if (!$user) : ?>
    <div class="control-group">
        <label class="control-label" for="file">Your email:</label>
        <div class="controls"><input type="text" class="input-xlarge" id="sharer" name="x-amz-meta-sharer" /> 
        If this is your registered email, the file will automatically be added to your vault</div>
    </div>
<?php endif; ?>
    <div class="control-group">
        <label class="control-label" for="patient">Patient:</label>
        <div class="controls">
            <?php if (isset($patients)) : ?>
            <input type="text" class="input-xlarge" id="patient" name="x-amz-meta-patient" data-provide="typeahead" data-source='["<?php echo implode('","', $patients); ?>"]' />
            <?php else : ?>
            <input type="text" class="input-xlarge" id="patient" name="x-amz-meta-patient" />
            <?php endif; ?>
            The patient's name
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="notes">Notes:</label>
        <div class="controls"><textarea class="input-xlarge" id="notes" name="x-amz-meta-notes" rows="4"></textarea>
        Notes about this file.</div>
    </div>
    <div class="control-group">
        <label class="control-label" for="notes">Share file with:</label>
        <div class="controls"><input type="text" class="input-xlarge" id="sharee" name="x-amz-meta-sharee" /> Their email address (not required)</div>
    </div>
    <div class="control-group">
        <label class="control-label" for="file">File:</label>
        <div class="controls"><input type="file" class="input-xlarge" id="file" name="file" /></div>
    </div>
    <div class="alert alert-error">After clicking "Upload", do not close this window.  The window will close automatically once the upload completes.</div>
    <div class="form-actions">
        <input type="hidden" name="newwindow" value="1" />
        <input class="btn btn-primary" type="submit" value="Upload" />
        <button class="btn" onclick="window.close();">Close</button>
    </div>
</form>

<?php
$this->load->view('layout/footer');