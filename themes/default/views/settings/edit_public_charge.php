<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_define_public_charge'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("system_settings/edit_public_charge", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			
            <div class="form-group">
                <?= lang("description", "description"); ?></label>
				<input type="hidden" value="<?=$rows->id?>" name="old_id">
                <?php echo form_input('description', ($rows->description !=""?$rows->description:""), 'class="form-control" id="description" required="required"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_public_charge', lang('save'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
