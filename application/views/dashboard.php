<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->view('layout/header');
//echo '<pre>'; print_r($usertypes); exit;
?>

<div class="title-block">
    <h1>Admin</h1>
</div>

<div class="row">
    <div class="span8 well">

        <h2>Users</h2>
        <table class="table table-bordered table-striped table-condensed">
            <tr>
                <th>Email/username</th><th>Signup date</th><th>Package</th><th>Files</th><th>Type</th>
                <th>SMS number</th><th style="text-align: center;">Email notify</th><th style="text-align: center;">SMS notify</th>
            </tr>
            <?php foreach ($users as $u) : ?>
            <tr>
                <td><?php echo $u->username; ?></td>
                <td><?php echo date('j M Y', strtotime($u->created)); ?></td>
                <td>
                    <?php 
                    echo '<select class="input-small" onchange="change_package(this, ' . $u->id . ');">';
                    foreach ($packages as $id => $name) echo '<option value="' . $id . '"' . ($id == $u->packageid ? ' selected="selected"' : '') . '>' . $name . '</option>';
                    echo '</select>'; 
                    ?>
                </td>
                <td><?php echo (isset($filecounts[$u->id]) ? $filecounts[$u->id] : 0); ?></td>
                <td><?php echo $usertypes[$u->typeid ? $u->typeid : 4]['name']; ?></td>
                <td><?php echo $u->smsnumber; ?></td>
                <td style="text-align: center;">
                    <?php if ($u->emailnotifications) : ?>
                        <i class="icon-ok"></i>
                    <?php else : ?>
                        <i class="icon-remove"></i>
                    <?php endif; ?>
                </td>
                <td style="text-align: center;">
                    <?php if ($u->smsnotifications) : ?>
                        <i class="icon-ok"></i>
                    <?php else : ?>
                        <i class="icon-remove"></i>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>    
    </div>
    
    <div class="span3 well">
    
        <h2>New Users</h2>
        <table class="table table-bordered table-striped">
            <tr class="row"><th>Last 24 hours</th><th>Last 7 days</th><th>Last 30 days</th></tr>
            <tr class="row">
                <td><?php echo $newusers['lastday']; ?></td>
                <td><?php echo $newusers['lastweek']; ?></td>
                <td><?php echo $newusers['lastmonth']; ?></td>
            </tr>
        </table>
    
    </div>
</div>

<?php
$this->load->view('layout/footer');
