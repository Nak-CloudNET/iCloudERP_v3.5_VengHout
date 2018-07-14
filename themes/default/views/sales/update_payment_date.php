<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('change_payment_date'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("sales/changePaymentDate", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

			<div class="row" style="margin-bottom:15px;padding:0 15px;">
                <table class="table table-bordered table-hover table-striped print-table order-table">

                    <thead>
						<tr>
							<th width="30%"><?= lang("pmt_no"); ?></th>
							<th width="70%"><?= lang("payment_date"); ?></th>
						</tr>
                    </thead>

                    <tbody>
						<?php foreach($loans as $loan) { ?>
							<tr>
								<td class="text-center"> <input type="hidden" name="lid[]" value="<?= $loan->id ?>" /> <?= $loan->period ?> </td>
								<td class="text-center"> <input type="text" name="payment_date[]" class="form-control payment_date date" value="<?= $this->erp->hrsd($loan->dateline) ?>" /> </td>
							</tr>
						<?php } ?>
					</tbody>
					
					<tfoot>
					
					</tfoot>
				</table>
			</div>

        </div>
        <div class="modal-footer">
			<input type="hidden" name="val" id="val" value="<?= ($loans? '1':'0') ?>" />
            <?php echo form_submit('save', lang('save'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {

	});
</script>
