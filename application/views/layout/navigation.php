<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
      <a class="brand" href="<?php echo site_url($user ? 'files' : ''); ?>"><span style="color: white;">Dent</span><span style="font-weight: bold; color: rgb(21,184,206);">Vault</span></a>
      <div class="nav-collapse">
        <ul class="nav">
            <li><a href="<?php echo base_url(); ?>"><?php echo lang('layout_home'); ?></a></li>
            <li class="divider"></li>
            <li><a href="<?php echo site_url('tutorials'); ?>"><?php echo lang('layout_tutorials'); ?></a></li>
            <li><a href="<?php echo site_url('faq'); ?>"><?php echo lang('layout_faq'); ?></a></li>
            <li><a href="<?php echo site_url('packages'); ?>"><?php echo lang('layout_packages'); ?></a></li>
            <li><a href="#" data-toggle="modal" data-target="#contactform"><?php echo lang('layout_contact'); ?></a></li>
            <li><a data-toggle="modal" data-target="#aboutmodal" href="#"><?php echo lang('layout_about'); ?></a></li>
        </ul>
        <?php if ($user) : // user is logged in
        $fullname = (!$user->firstname && !$user->lastname ? $user->username : ($user->firstname . ' ' . $user->lastname));  
        ?>
        <ul class="nav pull-right">
          <li class="dm-tooltip" data-rel="tooltip" data-placement="bottom" data-title="<?php echo lang('layout_click_view_profile'); ?>">
                <a href="<?php echo site_url('profile'); ?>">
                    <img src="https://secure.gravatar.com/avatar/<?php echo md5(strtolower(trim($user->username))); ?>?s=20&amp;d=mm" width="20" height="20" alt="<?php echo $user->username; ?>" />
                    <?php echo $fullname; ?>
                </a>
          </li>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo lang('layout_my_files'); ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo site_url('files'); ?>"><?php echo lang('layout_my_files'); ?></a></li>
                <li><a href="<?php echo site_url('files/showdeleted'); ?>"><?php echo lang('layout_deleted_files'); ?></a></li>
            </ul>
          </li>
          <?php if ($user->typeid == 1) : ?>
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><?php echo lang('layout_admin'); ?></a></li>
          <?php endif; ?>
          <li><a href="<?php echo site_url('logout'); ?>"><?php echo lang('layout_logout'); ?></a></li>
        </ul>
        <?php else : ?>
        <!-- Login form -->
        <div class="dm-head-text pull-right">
            <a href="#" data-toggle="modal" data-target="#registerform"><?php echo lang('layout_register'); ?></a><br />
            <a href="#" data-toggle="modal" data-target="#forgotpwd"><?php echo lang('layout_forgot_pwd'); ?></a>
        </div>
        <form class="form-inline pull-right" action="<?php echo site_url('login'); ?>" method="post">
          <input type="text" name="username" id="login-username" placeholder="<?php echo lang('layout_email'); ?>" class="input-small" />
          <input type="password" name="password" placeholder="<?php echo lang('layout_password'); ?>" class="input-small" /><input type="submit" class="btn btn-primary" value="<?php echo lang('layout_login'); ?>" />
        </form>
        <?php endif; ?>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<div class="modal hide fade" id="registerform">
    <form class="form-horizontal" method="post" action="<?php echo site_url('register'); ?>">
        <fieldset>
            <div class="modal-header">
                <button class="close" data-dismiss="modal">x</button>
                <h3><?php echo lang('layout_register'); ?></h3>
            </div>
            <div class="modal-body">
                <p><?php echo lang('layout_please_enter_email'); ?></p>
                <div class="control-group">
                    <label class="control-label" for="username"><?php echo lang('layout_email_address'); ?> </label>
                    <div class="controls">
                        <input type="text" class="input-medium" name="username" id="username" />
                        <p class="help-block"><?php echo lang('layout_must_valid_email'); ?></p>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="password"><?php echo lang('layout_choose_password'); ?></label>
                    <div class="controls">
                        <input type="password" class="input-medium" name="password" id="password" />
                        <p class="help-block"><?php echo lang('layout_must_6_chars'); ?></p>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="password"><?php echo lang('layout_retype_password'); ?> </label>
                    <div class="controls">
                        <input type="password" class="input-medium" name="password2" id="password2" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" value="<?php echo lang('layout_register'); ?>" class="btn btn-primary" />
                <button class="btn" data-dismiss="modal"><?php echo lang('layout_cancel'); ?></button>
            </div>
        </fieldset>
    </form>
</div>

<div class="modal hide fade" id="forgotpwd">
    <form class="form-horizontal" method="post" action="<?php echo site_url('forgotpwd'); ?>">
        <fieldset>
            <div class="modal-header">
                <button class="close" data-dismiss="modal">x</button>
                <h3><?php echo lang('layout_forgot_pwd'); ?></h3>
            </div>
            <div class="modal-body">
                <p><?php echo lang('layout_email_forgot_pwd'); ?></p>
                <div class="control-group">
                    <label class="control-label" for="username"><?php echo lang('layout_email_address'); ?> </label>
                    <div class="controls">
                        <input type="text" class="input-medium" name="username" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" value="<?php echo lang('layout_send_pwd'); ?>" class="btn btn-primary" />
                <button class="btn" data-dismiss="modal"><?php echo lang('layout_cancel'); ?></button>
            </div>
        </fieldset>
    </form>
</div>

<div class="modal hide fade" id="contactform">
    <form class="form-horizontal" method="post" action="<?php echo site_url('contact/handleform'); ?>">
        <input type="hidden" name="redirect" value="<?php echo current_url(); ?>" />
        <?php 
            if ($formdata = $this->session->flashdata('contactform')) { 
                $oldform = $formdata['oldform'];
                echo '<input type="hidden" name="oldform" id="oldform" value="contactform" />'; 
            }
        ?>
        <fieldset>
            <div class="modal-header">
                <button class="close" data-dismiss="modal">x</button>
                <h3><?php echo lang('layout_contact_form'); ?></h3>
            </div>
            <div class="modal-body">
                <p><?php echo lang('layout_contact_intro'); ?></p>
                <?php if ($formdata) echo '<span class="error">' . $formdata['error'] . '</span><br />'; ?><br />
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo lang('layout_name'); ?> </label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" name="name" id="name" value="<?php if ($formdata) echo $oldform['name']; elseif ($user) echo $fullname; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email"><?php echo lang('layout_email_address'); ?> </label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" name="email" id="email" value="<?php if ($formdata) echo $oldform['email']; elseif ($user) echo $user->username; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="subject"><?php echo lang('layout_subject'); ?> </label>
                    <div class="controls">
                        <input type="text" class="input-xlarge" name="subject" id="subject" value="<?php if ($formdata) echo $oldform['subject']; ?>" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="message"><?php echo lang('layout_message'); ?> </label>
                    <div class="controls">
                        <textarea class="input-xlarge" name="message" id="message" rows="4"><?php if ($formdata) echo $oldform['message']; ?></textarea>
                    </div>
                </div>
                <?php if (!$user) : ?>
                    <div class="control-group">
                        <label class="control-label" for="captcha"><?php echo lang('layout_type_chars'); ?> </label>
                        <div class="controls">
                            <?php echo $captcha['image']; ?> &nbsp; <input type="text" class="input-small" name="captcha" />
                        </div>
                    </div>
                <?php endif; ?>                                
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value="<?php echo lang('layout_send'); ?>" />
                <button class="btn" data-dismiss="modal"><?php echo lang('layout_cancel'); ?></button>
            </div>
        </fieldset>
    </form>
</div>

<div class="modal hide fade" id="aboutmodal">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">x</button>
        <h3>About Dentvault</h3>
    </div>
    <div class="modal-body">
    
        <img src="<?php echo base_url(); ?>img/ad_v4.png" width="530" height="112" alt="dentvault vs generic systems" />
        
        <br /><br />
        
        <p><strong>Dentvault</strong> was created to solve the problem of digital dentistry.</p>
        
        <p>CT scans, Panoramics, treatment plans, STL files, and videos are just a few of the digital examples that are strewn between the lab, 
        the scan center and dentists either somewhere in cyberspace or on the computer in the "other office" on the "other device" or on the 
        computer that just crashed. </p>
        
        <p><strong>Dentvault</strong> makes all your digital files extremely accessible by placing them at your fingertips where ever you are, while keeping them 
        extremely secure in your private online vault. All your files are organized, noted, and taged wherever and whenever you need. </p>
        
        <p><strong>Dentvault</strong> organizes your digital dentistry world. </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><?php echo lang('layout_close'); ?></button>
    </div>
</div>
