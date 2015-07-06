<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->view('layout/header');
?>

<div class="title-block">
    <h1><?php echo lang('layout_packages'); ?></h1>
</div>

<div class="well">

    <?php $this->load->view('packagetable'); ?>

</div>

<div class="row blocks">
    <div class="span10 offset1">
        <img src="<?php echo base_url(); ?>img/ad_v1.jpg" />
    </div>
</div>

<?php
$this->load->view('layout/footer');
