<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->load->library('user_agent');
$this->load->helper(array('format', 'file'));
$this->load->view('layout/header');
?>

<script type="text/javascript" src="js/jsc3d.js"></script>

<?php if ($storageexceeded) : ?>
    <div class="alert alert-danger pull-right"><?php echo lang('files_waiting'); ?> <a href="<?php echo site_url('packages'); ?>"><?php echo lang('files_click_upgrade'); ?></a></div>
<?php endif; ?>

<div class="title-block">
    <?php if (($currentpackage->storage == 0) OR (count($files) < $currentpackage->storage)) : // check that user is allowed to upload another file ?>
        <?php if ($this->agent->is_browser('Internet Explorer')) $uploadurl = 'files/upload'; else $uploadurl = 'upload'; ?>
        <a class="btn btn-large btn-info" onclick="window.open('<?php echo site_url($uploadurl); ?>', '_blank', 'width=800, height=600');">
            <i class="icon-arrow-up icon-white"></i> <?php echo lang('files_upload_new'); ?>
        </a>
    <?php else : ?>
        <a class="btn btn-large btn-info disabled">
            <span class="dm-tooltip" data-rel="tooltip" data-placement="bottom" data-title="<?php echo lang('files_max_files'); ?>">
                <i class="icon-arrow-up icon-white"></i> <?php echo lang('files_upload_new'); ?>
            </span>
        </a>
    <?php endif; ?>
</div>

<div class="well filter-bar">
    <a class="btn btn-small pull-right" href="<?php echo site_url('files/clearfilter'); ?>">
        <span class="dm-tooltip" data-rel="tooltip" data-placement="bottom" data-title="<?php echo lang('files_clear_filters'); ?>">
            <?php echo lang('files_show_all'); ?>
        </span>
    </a>
    <div class="pull-right" style="padding-right: 15px;">
        <?php echo lang('files_date'); ?>: <?php echo lang('files_last'); ?>
        <?php foreach (array(30, 60, 90) as $days) {
            echo '<a class="btn btn-small';
            if (isset($filter['days']) && ($filter['days'] == $days)) echo ' btn-inverse';
            echo '" href="' . site_url('files/filterdays/' . $days) . '">'; 
            echo $days . '</a>';
        } ?>
        <?php echo lang('files_days'); ?>
    </div> 
    <div class="pull-right" style="padding-right: 15px;">
        <form class="form-inline" method="post" action="<?php echo site_url('files/filterstring'); ?>">
            <?php echo lang('files_search'); ?>: 
            <input class="input-medium" type="text" placeholder="<?php echo lang('files_patient_name'); ?>" name="filter"
                <?php 
                    if (isset($filter['patient'])) 
                        echo 'value="' . $filter['patient']->firstname . ' ' . $filter['patient']->lastname . '"';
                    elseif (isset($filter['string'])) 
                        echo 'value="' . $filter['string'] . '"';
                ?> /> 
        </form>
    </div>
        
    Select files below, then: 
    <button id="downloadzip" class="btn btn-success btn-small dm-action" disabled="disabled">
        <span class="dm-tooltip" data-rel="tooltip" data-placement="bottom" data-title="<?php echo lang('files_download_zip'); ?>">
            <i class="icon-circle-arrow-down icon-white"></i> <?php echo lang('files_download'); ?>
        </span>
    </button>
    <button class="btn btn-info btn-small dm-upload dm-action" disabled="disabled" onclick="show_versions(0);">
        <span class="dm-tooltip" data-rel="tooltip" data-placement="bottom" data-title="<?php echo lang('files_upload_new_version'); ?>">
            <i class="icon-circle-arrow-up icon-white"></i> <?php echo lang('files_upload'); ?>
        </span>
    </button>
    <button class="btn btn-danger btn-small dm-action" data-toggle="modal" data-target="#deleteform" disabled="disabled">
        <span class="dm-tooltip" data-rel="tooltip" data-placement="bottom" data-title="<?php echo lang('files_click_delete'); ?>">
            <i class="icon-remove-sign icon-white"></i> <?php echo lang('files_delete'); ?>
        </span>
    </button>
</div>

<div class="clearfix">
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th class="column-select"><?php echo lang('files_select'); ?></th>
                <th class="dm-filename"><?php echo lang('files_filename'); ?> <span class="badge"><?php echo lang('files_version'); ?></span></th>
                <th><?php echo lang('files_size'); ?></th><th><?php echo lang('files_patient'); ?></th>
                <th><?php echo lang('files_date_time'); ?></th><th><?php echo lang('files_owner'); ?></th>
                <th><?php echo lang('files_status'); ?></th>
                <th class="column-select"><?php echo lang('files_notes'); ?></th>
                <th class="column-select"><?php echo lang('files_shares'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php 
$shares = array(); 
$notes = array();
$versions = array();
$storage = 0;

if(count($files) == 0) : 
?>
    <tr><td colspan="9"><div class="alert alert-info"><h3><?php echo lang('files_no_files'); ?></h3></div></td></tr>
<?php
else :
    foreach ($files as $f) : 
        $shares[$f->id] = $f->getShares();
        $notes[$f->id] = $f->getNotes();
        $versions[$f->id] = $f->getVersions(true); // true = as array
        $storage += $f->size; 
        if (!$f->viewed($user->id)) 
            echo '<tr class="not-viewed"';
        elseif ($f->status == 2)
            echo '<tr class="status-open"';
        else echo '<tr';
        echo ' id="row' . $f->id . '">'
?>

        <td class="column-select"><input type="checkbox" class="dm-check" value="ck<?php echo $f->id; ?>" /></td>

        <td class="dm-filename">
            <?php if (/* is_cad($f->filename) || */ is_image($f->filename) || is_doc($f->filename)) : ?>
            <span class="dm-tooltip" data-rel="tooltip" data-title="<?php echo lang('files_click_preview'); ?>">
            <?php else : ?>
            <span class="dm-tooltip" data-rel="tooltip" data-title="<?php echo lang('files_no_preview'); ?>" style="cursor: pointer; color: #686868;">
            <?php endif; ?>
                <?php /* if (is_cad($f->filename)) : ?>
                    <a class="download" href="#" onclick="show_cad_file(<?php echo $f->id; ?>, '<?php echo htmlentities($f->getURL()); ?>'); return true;">
                        <span id="fn<?php echo $f->id; ?>"><?php echo $f->filename;?></span>
                    </a>
                <?php else */ if (is_image($f->filename)) : ?>
                    <a class="download" href="#" onclick="show_image(<?php echo $f->id; ?>, '<?php echo htmlentities($f->getURL()); ?>'); return true;">
                        <span id="fn<?php echo $f->id; ?>"><?php echo $f->filename;?></span>
                    </a>
                <?php elseif (is_doc($f->filename)) : ?>
                    <a class="download" href="#" onclick="show_doc(<?php echo $f->id; ?>, '<?php echo htmlentities($f->getURL()); ?>'); return true;">
                        <span id="fn<?php echo $f->id; ?>"><?php echo $f->filename;?></span>
                    </a>
                <?php else : ?>
                        <span id="fn<?php echo $f->id; ?>"><?php echo $f->filename;?></span>
                <?php endif; ?>
            </span>
            <span class="dm-tooltip" data-rel="tooltip" data-title="<?php echo lang('files_click_versions'); ?>">
                <a href="#" onclick="show_versions(<?php echo $f->id; ?>);">
                    <span class="badge"><?php echo $f->version; ?></span>
                </a>
            </span>
        </td>

        <td><?php echo file_size($f->size); ?></td>

        <td>
            <?php 
            if ($p = $f->getPatient()) :
                $n = $p->firstname . ' ' . $p->lastname; 
            ?>
            <span class="dm-tooltip" data-rel="tooltip" data-title="<?php echo ($p ? $n . ($p->identifier ? ' (' . $p->identifier . ')' : '') : '') . '<br />' . lang('files_click_patient_filter'); ?>">
                <a id="dm-patientname<?php echo $f->id; ?>" class="download" href="<?php echo site_url('files/filterpatient/' . $p->id); ?>"><?php echo $n; ?></a>
            </span>
            <?php else : ?>
            (none)
            <?php endif; ?>
            <span class="dm-tooltip" data-rel="tooltip" data-title="<?php echo lang('files_click_edit'); ?>">
                 <a href="#" onclick="change_patient(<?php echo $f->id; ?>);"><i class="icon-pencil"></i></a>
            </span>
        </td>

        <td><?php echo date('M d, Y - H:i', mysql_to_unix($f->created)); ?></td>

        <td>
            <?php $o = $f->getOwner(); $oname = (!$o->firstname && !$o->lastname ? $o->username : ($o->firstname . ' ' . $o->lastname)); ?> 
            <span id="owner<?php echo $f->id; ?>" class="dm-popover" data-rel="popover" data-original-title="<?php echo $oname; ?>" data-content="<?php echo $o->username; ?>">
                <?php echo $oname; ?>
            </span>
        </td>

        <td class="column-status">
            <a class="dm-status" id="status<?php echo $f->id; ?>" href="#" onclick="$('#sdd<?php echo $f->id; ?>').show();$('#status<?php echo $f->id; ?>').hide(); return false;">
                <span class="dm-tooltip" data-rel="tooltip" data-title="<?php echo $statuses[$f->status]['description'] . '<br />' . lang('files_click_edit'); ?>">
                    <?php echo $statuses[$f->status]['name']; ?> <b class="caret"></b>
                </span>
            </a>
            <select id="sdd<?php echo $f->id; ?>" class="statusdd input-medium hide" onchange="set_status(this);" onblur="hidedd(this);">
                <?php
                    foreach ($statuses as $status) 
                        echo '<option value="' . $status['id'] . '"' . ($status['id'] == $f->status ? ' selected="selected"' : '') . '>' . $status['name'] . '</option>'; 
                ?>
            </select>
        </td>

        <td class="column-select">
            <span class="dm-tooltip" data-rel="tooltip" data-title="<?php echo lang('files_click_notes'); ?>">
                <button class="btn btn-inverse btn-mini dm-btn-num" onclick="show_notes(<?php echo $f->id; ?>);">
                    <?php echo count($notes[$f->id]); ?>
                </button>
            </span>
        </td>

        <td class="column-select">
            <span class="dm-tooltip" data-rel="tooltip" data-title="<?php echo lang('files_click_share'); ?>">
                <button class="btn btn-primary btn-mini dm-btn-num" onclick="show_shares(<?php echo $f->id; ?>);">
                    <?php echo count($shares[$f->id]) - 1; // don't include the owner ?>
                </button>
            </span>
        </td>
    </tr>
        
<?php 
    endforeach; 
endif;
?>

            <tr>
                <td>&nbsp;</td>
                <td><strong>Total files: <?php echo count($files); ?></strong></td>
                <td><strong><?php echo file_size($storage); ?></strong></td>
                <td colspan="6">&nbsp;</td>
            </tr>
    
        </tbody>
    </table>
</div>

<div class="modal hide fade" id="deleteform">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">x</button>
        <h3><?php echo lang('files_deleting'); ?></h3>
    </div>
    <div class="modal-body">
        <?php echo lang('files_sure_delete'); ?> <span id="dm-delete-count"></span> <?php echo lang('files_sure_delete2'); ?><br /><br />
        <span id="dm-delete-files"></span>
    </div>
    <div class="modal-footer">
        <form action="<?php echo site_url('files/delete'); ?>" method="post">
            <input type="hidden" name="fileids" id="dm-fileids" value="[]" />
            <button type="submit" class="btn btn-primary"><?php echo lang('files_delete'); ?></button>
            <button class="btn" data-dismiss="modal"><?php echo lang('files_cancel'); ?></button>
        </form>
    </div>
</div>

<div class="modal hide fade" id="shareform">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">x</button>
        <h3><?php echo lang('files_sharing_file'); ?>: <span id="share_file"></span></h3>
    </div>
    <div class="modal-body">
        <?php echo lang('files_shared_with'); ?>:<br /><br />
        <ul id="dm-shares"></ul>
    </div>
    <div class="modal-footer">
<?php if ($currentpackage->sendshare) : ?>    
        <form class="form-inline" action="<?php echo site_url('files/share'); ?>" method="post">
            <input type="hidden" name="fileid" id="dm-shareid" value="" />
            <span class="pull-left">
                <label for="share_email">Also share with: </label>
                <input class="input-xlarge" type="text" name="share_email" id="share_email" placeholder="email" />
            </span>
            <button type="submit" class="btn btn-primary"><?php echo lang('files_share'); ?></button>
            <button class="btn" data-dismiss="modal"><?php echo lang('files_cancel'); ?></button>
        </form>
<?php else : ?>
        <div class="alert alert-danger pull-left no-margin"><?php echo lang('files_no_sharing'); ?></div>
        <button class="btn pull-right" data-dismiss="modal"><?php echo lang('files_cancel'); ?></button>
<?php endif; ?>
    </div>
</div>

<div class="modal hide fade" id="notesform" style="width: 760px; margin-left: -380px;">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">x</button>
        <h3><?php echo lang('files_filename'); ?>: <span id="notes_file"></span></h3>
    </div>
    <div class="modal-body">
        <table class="table table-striped table-bordered table-condensed">
            <thead><tr><th><?php echo lang('files_date'); ?></th><th><?php echo lang('files_addedby'); ?></th><th><?php echo lang('files_note'); ?></th></tr></thead>
            <tbody id="dm-notes"></tbody>
        </table>
    </div>
    <div class="modal-footer">
        <form class="form-inline" action="<?php echo site_url('files/addnote'); ?>" method="post">
            <input type="hidden" name="fileid" id="dm-noteid" value="" />
            <span class="pull-left">
                <p class="pull-left"><?php echo lang('files_add_note'); ?>:</p><br />
                <textarea rows="3" name="note" id="note" placeholder="Note" class="input-xxlarge"></textarea>
            </span>
            <div style="position: absolute; bottom: 15px; right: 15px;">
                <button type="submit" class="btn btn-primary"><?php echo lang('files_add'); ?></button>
                <button class="btn" data-dismiss="modal"><?php echo lang('files_cancel'); ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal hide fade" id="versionform">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">x</button>
        <h3><?php echo lang('files_versions'); ?>: <span id="version_file"></span></h3>
    </div>
    <div class="modal-body">
        <table class="table table-striped table-bordered table-condensed">
            <thead><tr><th><?php echo lang('files_version'); ?></th><th><?php echo lang('files_filename'); ?></th><th><?php echo lang('files_date'); ?></th><th><?php echo lang('files_addedby'); ?></th></tr></thead>
            <tbody id="dm-versions"></tbody>
        </table>
    </div>
    <div class="modal-footer">
        <form class="form-inline" action="<?php echo site_url('files/doupload'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="fileid" id="dm-versionid" value="" />
            <input type="hidden" name="newwindow" value="0" />
            <span class="pull-left">
                <label for="file">Upload new: </label>
                <input class="input-xlarge" type="file" id="file" name="file" />
            </span>
            <button type="submit" class="btn btn-primary"><?php echo lang('files_upload'); ?></button>
            <button class="btn" data-dismiss="modal"><?php echo lang('files_cancel'); ?></button>
        </form>
    </div>
</div>

<div class="modal hide fade" id="imageform">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">x</button>
        <h3><span id="image_file"></span></h3>
    </div>
    <div id="dm-image" style="padding: 20px; text-align: center;"></div>
</div>

<div class="modal hide fade" id="patientform">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">x</button>
        <h3><?php echo lang('files_patient_for'); ?>: <span id="patient_file"></span></h3>
    </div>
    <form class="form-inline" action="<?php echo site_url('files/patient'); ?>" method="post">
        <div class="modal-body">
            <input type="hidden" name="fileid" id="dm-patientfileid" value="" />
            <label for="patient"><?php echo lang('files_patient'); ?>: </label>
            <input type="text" class="input-xlarge" id="patient" name="patient" data-provide="typeahead" data-source='["<?php echo implode('","', $patients); ?>"]' />
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><?php echo lang('files_update'); ?></button>
            <button class="btn" data-dismiss="modal"><?php echo lang('files_cancel'); ?></button>
        </div>
    </form>        
</div>

<div class="modal hide fade" id="stlform" style="width: 680px;">
    <div class="modal-header">
        <button class="close" data-dismiss="modal">x</button>
        <h3><span id="stl_file"></span></h3>
    </div>
    <div id="dm-cadfile" style="padding: 20px; text-align: center;">
    	<div style="width:640px; margin:auto; position:relative; font-size: 9pt; color: #777777;">
    		<canvas id="dm-canvas" style="border: 1px solid;" width="640" height="480" ></canvas>
    		<div id="dm-stltip" style="display:block; color:#ffffff; padding:5px; position:absolute; left:10px; bottom:20px; background-color:#000000; height:32px; width:250px; border-radius:5px; border:1px solid #777777; font-family:Arial,sans-serif; opacity:0.5;"> 
    			Drag mouse to rotate <br> Drag mouse with shift pressed to zoom
    		</div>
    	</div>
    </div>
</div>

<script type="text/javascript">
var shares = <?php echo json_encode($shares); ?>;
var notes = <?php echo json_encode($notes); ?>;
var statuses = <?php echo json_encode($statuses); ?>;
var versions = <?php echo json_encode($versions); ?>;
</script>

<?php
$this->load->view('layout/footer');
