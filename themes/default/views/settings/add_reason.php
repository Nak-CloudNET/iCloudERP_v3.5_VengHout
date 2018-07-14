<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_reason'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/add_reason", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <?php echo lang('position', 'position'); ?>
                <div class="controls">
				<?php
                    $po[""] = $this->lang->line("select") . " " . $this->lang->line("position");
                    foreach ($positions as $position) {
                        $po[$position->id] = $position->name;
                    }
                    echo form_dropdown('position', $po, (isset($_POST['position']) ? $_POST['position'] : $parent_id), 'class="form-control select" id="position" required="required"');
					//$this->erp->print_arrays($position->name);
                ?>
				</div>
            </div>
            <div class="form-group">
                <?php echo lang('code', 'code'); ?>
                <div class="controls">
                    <?php echo form_input($code); ?>
                </div>
            </div>
			<div class="form-group">
                <?php echo lang('description', 'description'); ?>
                <div class="controls">
                    <?php echo form_input($name); ?>
                </div>
            </div>
            
			
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_reason', lang('add_reason'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>