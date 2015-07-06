<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->view('layout/header');
?>

<h3 class="title-block">Upload a new file</h3>

<form class="form-horizontal" method="post" action="<?php echo site_url('files/doupload'); ?>" enctype="multipart/form-data">
    <div class="control-group">
        <label class="control-label" for="file">File:</label>
        <div class="controls"><input type="file" class="input-large" id="file" name="file" /></div>
    </div>
    <div class="control-group">
        <label class="control-label" for="patient">Patient:</label>
        <div class="controls">
            <input type="text" class="input-large" id="patient" name="patient" data-provide="typeahead" data-source='["<?php echo implode('","', $patients); ?>"]' />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="notes">Notes:</label>
        <div class="controls"><textarea class="input-large" id="notes" name="notes" rows="4"></textarea></div>
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