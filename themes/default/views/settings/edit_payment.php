<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_payment_term'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("system_settings/edit_payment_term/".$id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label" for="description"><?php echo $this->lang->line("description"); ?></label>

                <div class="controls"> <?php echo form_input('description', $data->description, 'class="form-control" id="description" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="due_day"><?php echo $this->lang->line("due_day"); ?></label>

                <div class="controls"> <?php echo form_input('due_day', $data->due_day, 'class="form-control" id="due_day"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="due_day_for_discount"><?php echo $this->lang->line("due_day_for_discount"); ?></label>

                <div class="controls"> <?php echo form_input('due_day_for_discount', $data->due_day_for_discount, 'class="form-control" id="due_day_for_discount"'); ?> </div>
            </div>
			<div class="form-group">
                <label class="control-label" for="discount"><?php echo $this->lang->line("discount"); ?></label>

                <div class="controls"> <?php echo form_input('discount', $data->discount, 'class="form-control" id="discount"'); ?> </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_payment_term', lang('edit_payment_term'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
