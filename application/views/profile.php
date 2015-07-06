<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->helper('format');
$this->load->view('layout/header');
?>

<div class="title-block">
    <h1><?php echo lang('users_profile'); ?></h1>
</div>

<div class="row">
    <div class="well span6">
        <form class="form-horizontal" method="post" action="<?php echo site_url('users/saveprofile'); ?>">
            <input type="hidden" name="userid" value="<?php echo $user->id; ?>" />
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="username"><?php echo lang('users_username'); ?></label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" name="username" id="username" value="<?php echo $user->username; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="emailnotifications"><?php echo lang('users_email_notifications'); ?></label>
                    <div class="controls">
                        <input type="checkbox" class="input-xlarge" name="emailnotifications" id="emailnotifications" value="1" <?php if ($user->emailnotifications) echo 'checked="checked"'; ?> />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="firstname"><?php echo lang('users_firstname'); ?></label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" name="firstname" id="firstname" value="<?php echo $user->firstname; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="lastname"><?php echo lang('users_lastname'); ?></label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" name="lastname" id="lastname" value="<?php echo $user->lastname; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="typeid"><?php echo lang('users_type'); ?></label>
                    <div class="controls">
                        <?php if ($user->typeid == 1) : // admin ?>
                            <p class=" dm-top5">Admin</p>
                        <?php else : ?>
                            <select class="input-xlarge" name="typeid" id="typeid">
                            <option value="0">Select ...</option>
                                <?php 
                                    foreach ($usertypes as $type) 
                                        echo '<option value="' . $type['id'] . '"' . ($type['id'] == $user->typeid ? ' selected="selected"' : '') . '>' . 
                                            $type['name'] . '</option>'; 
                                ?>
                            </select>
                            <br />
                            <div class="note dm-top5"><?php echo lang('users_select_scan'); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="smsnumber"><?php echo lang('users_smsnumber'); ?></label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" name="smsnumber" id="smsnumber" value="<?php echo $user->smsnumber; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="smsnotifications"><?php echo lang('users_sms_notifications'); ?></label>
                    <div class="controls">
                        <input type="checkbox" class="input-xlarge" name="smsnotifications" id="smsnotifications" value="1" <?php if ($user->smsnotifications) echo 'checked="checked"'; ?> />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="profilepic"><?php echo lang('users_profilepic'); ?></label>
                    <div class="controls">
                        <a href="https://en.gravatar.com/site/login" target="_blank">
                            <img src="https://secure.gravatar.com/avatar/<?php echo md5(strtolower(trim($user->username))); ?>?s=150&d=mm" class="thumbnail dm_thumb" width="150" height="150" />
                        </a>
                        <p class="dm-top5"><?php echo lang('users_use_gravatar'); ?></p>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><?php echo lang('users_btn_save'); ?></button>
                    <button class="btn" onclick="return false;"><?php echo lang('users_btn_cancel'); ?></button>
                    <button class="btn btn-mini btn-inverse pull-right" data-toggle="modal" data-target="#passwordform">Change Password</button>
                </div>
            </fieldset>
        </form>
    </div>
    <div class="span5">
        <div class="well">
            <h3>Usage stats:</h3>
            <br /><br />
            <p>
                Current files: <strong><?php echo $currentfiles->count; ?></strong>
                <br />
                Current usage: <strong><?php echo file_size($currentfiles->total); ?></strong>
            </p>
            <br />
            <p>
                Monthly files for <?php echo date('F Y'); ?>: <strong><?php echo $monthfiles->maxcount; ?></strong>
                <br />
                Monthly usage for <?php echo date('F Y'); ?>: <strong><?php echo file_size($monthfiles->maxtotal); ?></strong>
            </p>
        </div>
        <div class="well">
            <h3>Package:</h3>
            <br /><br />
            <p>
                <?php $pack = $user->getCurrentPackage(); ?>
                Your current package is: <b><?php echo $pack->name; ?>.<br /></b>
                <?php if ($pack->storage) : ?>
                    You can have a maximum of <b><?php echo $pack->storage; ?></b> files in your account.
                <?php endif; ?>
            </p>
            <?php if ($pack->id != 4) : // not unlimited ?>
                <br /><br />
                &nbsp; &nbsp; <a class="btn btn-large btn-warning btn-black" href="<?php echo site_url('packages'); ?>">Upgrade your package</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="modal hide fade" id="passwordform">
    <form class="form-horizontal" action="<?php echo site_url('auth/changepwd'); ?>" method="post">
        <div class="modal-header">
            <button class="close" data-dismiss="modal">x</button>
            <h3><?php echo lang('users_change_password'); ?></h3>
        </div>
        <div class="modal-body">
            <div class="control-group">
                <label class="control-label" for="oldpass"><?php echo lang('users_oldpass'); ?> </label>
                <div class="controls">
                    <input type="password" class="input-medium" name="oldpass" id="oldpass" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="newpass"><?php echo lang('users_newpass'); ?> </label>
                <div class="controls">
                    <input type="password" class="input-medium" name="newpass" id="newpass" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="newpass2"><?php echo lang('users_retypepass'); ?> </label>
                <div class="controls">
                    <input type="password" class="input-medium" name="newpass2" id="newpass2" />
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><?php echo lang('users_btn_change'); ?></button>
            <button class="btn" data-dismiss="modal"><?php echo lang('users_btn_cancel'); ?></button>
        </div>
    </form>
</div>

<div class="clearfix"></div>

<?php
$this->load->view('layout/footer');
