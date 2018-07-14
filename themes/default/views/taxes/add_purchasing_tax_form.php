<div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('purchasing_tax'); ?></h4>
        </div>
		<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("taxes/add_purchasing_tax_form", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			<div class="table-responsive">
                <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th style="width:15%;"><?= $this->lang->line("date"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("reference_no"); ?></th>
						<th style="width:15%;"><?= $this->lang->line("amount"); ?></th>
						<th style="width:15%;"><?= $this->lang->line("amount_tax"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("type"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("total_amount"); ?></th>
						<th style="width:10%;"><?= $this->lang->line("action"); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($purchase_taxes)) {
                        foreach ($purchase_taxes as $purchases) {?>
						
                            <tr class="row<?= $purchases->id ?>">
                                <td>
									<?= $purchases->date; ?>
									<input type="hidden" name="purchase_id[]" class="purchase_id" value="<?= $purchases->id; ?>" />
									<input type="hidden" name="ptype[]" class="purchase_id" value="<?= $purchases->ptype; ?>" />
								</td>
                                <td>
									<?= $purchases->reference_no; ?>
									<input type="hidden" name="journal_check[]" class="supplier_id" value="0" />
									<input type="hidden" name="supplier_id[]" class="supplier_id" value="<?= $purchases->supplier_id; ?>" />
									<input type="hidden" name="warehouse_id[]" class="warehouse_id" value="<?= $purchases->warehouse_id; ?>" />
									<input type="hidden" name="purchase_ref[]" class="purchase_ref" value="<?= $purchases->reference_no; ?>" />
								</td>    
								<td>
								<?php
								if($purchases->remark_id==1){
									echo lang('taxable_purchase');
								}elseif($purchases->remark_id==2){
									echo lang('non_taxable_purchase');
								}elseif($purchases->remark_id==3){
									echo lang('import');   
								}else{
									echo lang('can_not_define_type!');   
								}
									
								?>
								</td>							
								<td class="balance">
									
									<?php
									if($purchases->remark_id==1){
											echo '<input type="text" name="amount[]" class="form-control amount" value="'.$purchases->amount.'" />';
											echo '<input type="hidden" name="non_tax_pur[]" class="amount" value="" />';
											echo '<input type="hidden" name="value_import[]" class="amount" value="" />';
									}
									if($purchases->remark_id==2){
											echo '<input type="hidden" name="amount[]" class="amount" value="" />';
											echo '<input type="hidden" name="non_tax_pur[]" class="amount" value="'.$purchases->amount.'" />';
											echo '<input type="hidden" name="value_import[]" class="amount" value="" />';
									}
									if($purchases->remark_id==3){
											echo '<input type="hidden" name="amount[]" class="amount" value="" />';
											echo '<input type="hidden" name="non_tax_pur[]" class="amount" value="" />';
											echo '<input type="hidden" name="value_import[]" class="amount" value="'.$purchases->amount.'" />';
									}
									?>
								</td>
								<td>
									<?php
										$ptype["0"] = lang('none');
										$ptype["2"] = lang('non_taxable_sales');
										$ptype["1"] = lang('taxable_sales');
										$ptype["3"] = lang('export');                 
										echo form_dropdown('purchase_type[]', $ptype, '', 'id="sale_type" data-placeholder="' . lang("select") . ' ' . lang("sale_type") . '" class="form-control input-tip" style="width:100%;"');
                                    ?>
								</td>
                                <td style="display:none;">
									<?= $this->erp->formatMoney($purchases->vat); ?>
									<input type="hidden" name="amount_tax[]" class="tax_id" value="<?= $purchases->amount_declear; ?>" />
									<input type="hidden" name="tax_id[]" class="tax_id" value="<?= $purchases->order_tax_id; ?>" />
								</td>
								<td class="text-right">
									<?= $this->erp->formatMoney($purchases->vat+$purchases->amount);?>
								</td>
								<?php
									if($purchases->amount_declear){
										$amount_declare=$purchases->amount_declear;
									}else{
										$amount_declare=$purchases->amount;
									}
								?>
								<td style="display:none;">
									<input type="text" name="amount_decleared[]" style="width:150px;" class="amount_decleared form-control" value="<?php echo $amount_declare;?>" />
								</td>
								<?php
									if($purchases->vat_declare){
										$vat_declare=$purchases->vat_declare;
									}else{
										$vat_declare=$purchases->vat;
									}
								?>
								<td class="vat_declare"  style="display:none;">
									<?= $this->erp->formatMoney($vat_declare); ?>
								</td>
	
								<td class="total_amount_declare"  style="display:none;">
									<?= $this->erp->formatMoney($amount_declare+$vat_declare); ?>
								</td>
								<td style="display:none;">
									<input type="text" name="tax_ref[]" class="tax_ref form-control">
									<input type="hidden" class="amount_tax_declare" name="amount_tax_declare[]" class="tax_type" value="<?=$vat_declare?>" />
									<!--<input type="hidden" class="purchase_type" name="purchase_type[]" class="tax_type" value="<?=$purchases->remark_id; ?>" />-->
								
								</td>
								<td class="text-center">
									<button type="button" class="btn btn-primary remove_line"><i class="fa fa-trash-o"></i></button>
								</td>
                            </tr>
                        <?php }
                    } ?>
					
					
					
					
					
					
                    </tbody>
                </table>
            </div>
			<div class="row" style="display:none;">
				<?php if ($Owner || $Admin) { ?>
					<div class="col-sm-3">
						<div class="form-group">
							<?= lang("declear_date", "date"); ?>
							<?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : date('Y/m/d h:m')), 'class="form-control datetime" id="date" style="pointer-events: none;" required="required"'); ?>
						</div>
					</div>
				<?php } ?>
			</div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_purchasing_tax', lang('add_purchasing_tax'), 'class="btn btn-primary" id="add_submit"'); ?>
        </div>
    <?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.amount_decleared').keyup(function() {
			var parent = $(this).parent().parent();
			var purchase_type=parent.find('.purchase_type').val();
			var amount_declare = $(this).val()-0;
			if(purchase_type==2){
				var vat_declare=0;
			}else{
				var vat_declare= amount_declare *0.1;
			}

			parent.find('.vat_declare').text(vat_declare);
			parent.find('.total_amount_declare').text(vat_declare+amount_declare);
			parent.find('.amount_tax_declare').val(vat_declare);
		});
		$(".remove_line").on('click',function() {
			var row = $(this).closest('tr').focus();
			row.remove();
		});
		
		$("select").select2();
	});
</script>