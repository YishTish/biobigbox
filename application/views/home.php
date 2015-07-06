<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->view('layout/header');
?>

    <!-- Main hero unit for a primary marketing message or call to action -->
    <div class="slider-outer">
        <div id="slideshow">
            <div class="slide row">
                <div class="image span4">
                    <img src="<?php echo site_url(); ?>img/cloud_9_devices.png" alt="Cloud devices" />
                </div>
                <div class="textblock span8">
                    <h2>All your files. All the time. Everywhere.</h2>
                    <p>Access your files from any device, in any location, directly from your browser.</p>
                    <p><a class="btn btn-primary btn-large" href="#" data-toggle="modal" data-target="#registerform">Start Now &raquo;</a></p>
                </div>
            </div>
            <div class="slide row" style="display: none;">
                <div class="image span4">
                    <img src="<?php echo site_url(); ?>img/flame-cd.jpg" alt="Flame CD" />
                </div>
                <div class="textblock span8">
                    <h2>Burning CDs and emailing large files are history!</h2>
                    <p>Share the right files, with the right associates, at the right time. Benefit from optimized workflow and teamwork.</p>
                    <p><a class="btn btn-primary btn-large" href="#" data-toggle="modal" data-target="#registerform">Start Now &raquo;</a></p>
                </div>
            </div>
            <div class="slide row" style="display: none;">
                <div class="image span4">
                    <img src="<?php echo site_url(); ?>img/vaultdoor.jpg" alt="Vault door" />
                </div>
                <div class="textblock span8">
                    <h2>Your personal digital dental vault.</h2>
                    <p>HIPPA compliant, state of the art, secure storage. SSL encrypted data transfer with server redundancy.</p>
                    <p><a class="btn btn-primary btn-large" href="#" data-toggle="modal" data-target="#registerform">Start Now &raquo;</a></p>
                </div>
            </div>
            <div class="slide row" style="display: none;">
                <div class="image span4">
                    <img src="<?php echo site_url(); ?>img/data-protection.jpg" alt="Data protection" />
                </div>
                <div class="textblock span8">
                    <h2>All files, all versions, all backed up.</h2>
                    <p>CT scans, treatment plans,  images, reports, recordings and all your digital files can be saved in Dentvault.</p>
                    <p><a class="btn btn-primary btn-large" href="#" data-toggle="modal" data-target="#registerform">Start Now &raquo;</a></p>
                </div>
            </div>
        </div>
        <div id="buttons">
            <button id="prev"><i class="icon-chevron-left"></i></button>
            <button id="next"><i class="icon-chevron-right"></i></button>
        </div>
    </div>
      
      <!-- Example row of columns -->
    <div class="row blocks dv-blocks">
        <div class="span8">
            <div class="row">
                <div class="span4">
                    <div class="image-outer">
                        <img src="<?php echo base_url(); ?>img/home1.jpg" />
                    </div>
                    <h3>Get started now at no charge</h3>
                    <p>Start using your own digital dental vault right now at no charge. No obligations. No commitment. Start profiting from the 
                    comfort, convenience and collaboration of your digital dental vault.</p>
                    <p><a class="btn btn-info" href="#" data-toggle="modal" data-target="#registerform">Register now &raquo;</a></p>
                </div>
                <div class="span4">
                    <div class="image-outer">
                        <img src="<?php echo base_url(); ?>img/home2.jpg" />
                    </div>
                    <h3>Special offer for labs and scan centers</h3>
                    <p>Get your own branded environment built for you and your patients at no charge.  Eliminate the costs of  third party companies 
                    managing and transferring  your digital files.  Upload a file to your vault and let the dentist download it from theirs. </p>
                    <p><a class="btn btn-info" href="#" data-toggle="modal" data-target="#offermodal" >See offer &raquo;</a></p>
                </div>
            </div>
            <div class="row">
                <div class="span4">
                    <div class="image-outer">
                        <img src="<?php echo base_url(); ?>img/home3.jpg" />
                    </div>
                    <h3>How to use DentVault</h3>
                    <p>View step-by-step instructions and video tutorials on maximizing the tools DentVault has to offer. </p>
                    <p><a class="btn btn-info" href="<?php echo site_url('tutorials'); ?>">View how tos &raquo;</a></p>
                </div>
                <div class="span4">
                    <div class="image-outer">
                        <img src="<?php echo base_url(); ?>img/tooth-x-ray.jpg" />
                    </div>
                    <h3>Packed with Dental Features</h3>
                    <p>DentVault is packed with dental specific tools that will help you and your practice. With Dentvault you can view many of your 
                    dental files online, sort by patient, receive confirmation when your shared files are viewed, share case notes with associates 
                    and much more. </p>
                    <p><a class="btn btn-info" href="#" data-toggle="modal" data-target="#aboutmodal">Read more &raquo;</a></p>
                </div>
            </div>
        </div>
        <div class="span4">
            <a href="<?php echo base_url('upload'); ?>">
                <img class="sendafile" src="<?php echo base_url(); ?>img/btn_send.png" />
                <img class="sendafile" src="<?php echo base_url(); ?>img/btn_secure.png" />
            </a>
        </div>
    </div>
    
    <div class="row blocks">
        <div class="span10 offset1">
            <img src="<?php echo base_url(); ?>img/ad_v1.jpg" />
        </div>
    </div>

<div class="modal hide fade" id="offermodal">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">x</button>
        <h3>Special offer for labs and scan centers</h3>
    </div>
    <div class="modal-body">
        DentVault offers your lab or scan center, at NO charge,  its own vault that will never get filled including:
        <ul>
            <li>Unlimited file uploads</li>
            <li>Storage of files online for a minimum of 180 days</li>
            <li>Unlimited sharing of files with dentists</li>
            <li>30 day deleted file recovery</li>
            <li>Confirmation when a shared file is viewed</li>
            <li>Email notification when a dentist adds a file to your vault</li> 
            <li>A personally  branded environment for all your dentists. When a user is retrieving a file you placed in their vault 
            they will see your company's banner and logo.</li> 
        </ul>
        <p>Get started by creating an account and entering your lab or scan center's web address in the web address field, and uploading 
        a custom banner of your choice.  Following DentVault's account approval you can start adding files to your vault and allowing dentists 
        to download the files from their vault. When the dentist access the file page they will see the branding of your business.</p> 
        <p>Can it get any better?</p>
        <p><a class="btn btn-info" href="#" onclick="$('#offermodal').modal('hide'); $('#registerform').modal('show');">Register &raquo;</a></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><?php echo lang('layout_close'); ?></button>
    </div>
</div>

<?php
$this->load->view('layout/footer');
