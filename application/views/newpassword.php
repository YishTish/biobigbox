<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->view('layout/header');
?>

<div class="title-block">
    <h1><?php echo lang('auth_newpassword'); ?></h1>
</div>

<div class="well pull-left" style="width: 50%;">
    <form class="form-horizontal" method="post" action="<?php echo site_url('auth/setpassword'); ?>">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="password"><?php echo lang('auth_newpass'); ?></label>
                <div class="controls">
                    <input type="password" class="input-xlarge" name="password" id="password" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="retypepwd"><?php echo lang('auth_retypepass'); ?></label>
                <div class="controls">
                    <input type="password" class="input-xlarge" name="retypepwd" id="retypepwd" />
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo lang('auth_btn_save'); ?></button>
                <button class="btn" onclick="window.location.href = '<?php echo base_url(); ?>'; return false;"><?php echo lang('auth_btn_cancel'); ?></button>
            </div>
        </fieldset>
    </form>
</div>

<div class="clearfix"></div>

<?php
$this->load->view('layout/footer');
