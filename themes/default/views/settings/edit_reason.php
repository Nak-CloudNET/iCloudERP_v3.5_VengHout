<?php
	$name = array(
		'name' => 'name',
		'id' => 'name',
		'value' => $reason->name,
		'class' => 'form-control',
	);
	$code = array(
		'name' => 'code',
		'id' => 'code',
		'value' => $reason->description,
		'class' => 'form-control',
		'required' => 'required',
	);
?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_reason'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/edit_reason/" . $id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('update_info'); ?></p>

            <div class="form-group">
                <?php echo lang('position', 'position'); ?>
                <div class="controls"> <?php
                    $ct[""] = $this->lang->line("select") . " " . $this->lang->line("position");
                    foreach ($positions as $position) {
                        $ct[$position->id] = $position->name;
                    }
                    echo form_dropdown('position', $ct, (isset($_POST['position']) ? $_POST['position'] : $reason->position_id), 'class="form-control" id="position"');
                ?>
				</div>
            </div>
			
            <div class="form-group">
                <?php echo lang('code', 'code'); ?>
                <div class="controls" style="pointer-events:none">
					<?= form_input('code', $reason->code, 'class="form-control" id="code" '); 
				?>
                </div>
            </div>

            <div class="form-group">
                <?php echo lang('description', 'description'); ?>
                <div class="controls">
					<?= form_input('description', $reason->description, 'class="form-control" id="description" '); ?>
                </div>
            </div>
            <?php echo form_hidden('id', $id); ?>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_reason', lang('edit_reason'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
