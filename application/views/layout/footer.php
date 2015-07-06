<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

      <footer class="well hidden-tablet hidden-phone">
        <p class="pull-left">
            <a data-toggle="modal" data-target="#aboutmodal" href="#"><?php echo lang('layout_about'); ?></a> &verbar;
            <a href="<?php echo site_url('tutorials'); ?>"><?php echo lang('layout_tutorials'); ?></a> &verbar;
            <a href="<?php echo site_url('faq'); ?>"><?php echo lang('layout_faq'); ?></a>
        </p>
        <p class="pull-right">&copy; DentVault, <?php echo date('Y'); ?>.  <?php echo lang('layout_rights_reserved'); ?></p>
        <!-- <p class="pull-right">[ Debug info - Elapsed time: {elapsed_time}s - Memory usage: {memory_usage} ]</p> -->
      </footer>

    </div> <!-- /container -->

<?php $this->load->view('layout/html_close');
