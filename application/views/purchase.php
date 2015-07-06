<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->helper('format');
$this->load->view('layout/header');
?>

<div class="title-block">
    <h1><?php echo lang('users_subscribe'); ?></h1>
</div>

<div class="well text-block">

    <p><?php echo lang('user_chosen_package'); ?>: <strong><?php echo $package->name; ?></strong></p>
    <p><?php echo lang('user_cost_is'); ?> <strong>$<?php echo number_format($package->price, 0); ?></strong> <?php echo lang('user_per_month'); ?></p>
    <p><?php echo lang('user_email_subscribe'); ?></p>
    <div style="background-color: white; float: left; margin: 20px;">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="padding: 25px 25px 10px 25px;">
            <input type="hidden" name="cmd" value="_s-xclick" />
            <input type="hidden" name="hosted_button_id" value="<?php echo $button_id; ?>" />
            <label for="email">Email: </label><input type="text" name="email" /><br /><br />
            <input type="hidden" name="custom" value="userid0" />
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" style="width: auto; border: none;" name="submit" alt="PayPal - The safer, easier way to pay online!" />
            <img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" style="width: 1px; height: 1px; border: none;" />
        </form>
    </div>
    <div class="clearfix"></div>


</div>

<?php
$this->load->view('layout/footer');
