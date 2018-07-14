<style>
.error{
	color: #ef233c;
}
.margin-b-5{
	margin-bottom: 0;
}
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_exchange_tax_rate'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("account/update_exchange_tax_rate/".$condition_tax->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang(''); ?></p>
		<div class="row">
		
			<div class="col-md-4">
					<div class="form-group">
				<?= lang("exchange_rate_code"); ?>
						<?php echo form_input('code', (isset($_POST['code']) ? $_POST['code'] : $condition_tax->code), 'class="form-control  " disabled id="exchange_rate_code" required="required"'); ?>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<?= lang("exchange_rate_name"); ?>
						<?php echo form_input('name', (isset($_POST['name']) ? $_POST['name'] : $condition_tax->name), 'class="form-control " disabled id="exchange_rate_name" required="required"'); ?>
					</div>
				</div>
		
				<div class="col-md-4">
					<div class="form-group">
						<?= lang("exchange_rate"); ?>
						<?php echo form_input('rate', (isset($_POST['rate']) ? $_POST['rate'] : intval($condition_tax->rate)), 'class="form-control " id="exchange_rate" required="required"'); ?>
					</div>
				</div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_journal', lang('Update'), 'class="btn btn-primary" id="checkSave"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
