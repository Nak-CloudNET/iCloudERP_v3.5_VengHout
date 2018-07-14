<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_address'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("project_plan/edit_address", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			
			<div class="form-group">
				<label class="control-label" for="plan"><?= lang("plan"); ?></label>
				<?php
				$pln["0"] = "None";
				foreach ($plan as $plans) {
					$pln[$plans->id] = $plans->plan;
				}
				echo form_dropdown('plan', $pln, $data->plan_id, 'class="form-control" id="plan" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("plan") . '"');
				?>
			</div>

            <div class="form-group">
                <?= lang("address", "address"); ?>
                <?php echo form_textarea('address', $data->address, 'class="form-control" id="address"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_address', lang('edit_address'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>