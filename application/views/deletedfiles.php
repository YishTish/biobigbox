<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->helper(array('format', 'file'));
$this->load->view('layout/header');
?>

<div class="title-block">
    <h1><?php echo lang('files_deleted'); ?></h1>
</div>

<?php if (!$currentpackage->recovery) : ?>

    <div class="alert alert-danger">
        Your package does not allow you to recover your deleted files. Upgrade you package to recover these files.&nbsp;
        <a href="<?php echo site_url('packages'); ?>" class="btn btn-warning">Upgrade</a>
    </div>

<?php endif; ?>

<div class="clearfix">
    <table class="table table-bordered table-condensed span10">
        <thead>
            <tr>
                <?php if ($currentpackage->recovery) : ?><th>&nbsp;</th><?php endif; ?>
                <th class="dm-filename"><?php echo lang('files_filename'); ?> <span class="badge"><?php echo lang('files_version'); ?></span></th>
                <th><?php echo lang('files_size'); ?></th>
                <th><?php echo lang('files_date_time'); ?></th>
                <th><?php echo lang('files_patient'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($files as $f) : ?>
    
            <tr class="text-middle">
                <?php if ($currentpackage->recovery) : ?>
                    <td class="center"><a class="btn btn-mini btn-info" href="<?php echo site_url('files/recover/' . $f->id); ?>">Recover</a></td>
                <?php endif; ?>
                <td><?php echo $f->filename; ?> <span class="badge"><?php echo $f->version; ?></span></td>
                <td><?php echo file_size($f->size); ?></td>
                <td><?php echo date('M d, Y - H:i', mysql_to_unix($f->created)); ?></td>
                <?php if ($p = $f->getPatient()) $n = $p->firstname . ' ' . $p->lastname; else $n = '(none)'; ?>
                <td><?php echo $n; ?></td>
            </tr>
    
            <?php endforeach; ?>
        </tbody>
    </table>    
</div>
<?php
$this->load->view('layout/footer');
