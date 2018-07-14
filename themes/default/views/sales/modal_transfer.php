<div class="modal-dialog">

    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
			<h4 class="modal-title"><?= lang('transfer_owner');?></h4>
		</div>
		<?php
			$attrib = array('data-toggle' => 'validator', 'role' => 'form');
            echo form_open_multipart("sales/trasfer_submit/".$id, $attrib);
		?>
		<div class="modal-body">
			<div class="row">
			
				<div class="col-md-12">
					<div class="form-group">
						<?= lang("customer", "slcustomer"); ?>
						<?php if ($Owner || $Admin || $GP['customers-add']) { ?><div class="input-group"><?php } ?>
							<?php
							echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'id="slcustomer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control input-tip" style="width:100%;"');
							?>
							<?php if ($Owner || $Admin || $GP['customers-add']) { ?>
							<div class="input-group-addon no-print" style="padding: 2px 5px;">
								<a href="<?= site_url('customers/add'); ?>" data-toggle="modal" data-target="#myModal2"><i class="fa fa-2x fa-plus-circle" id="addIcon"></i></a>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<?php 
					$curDate = date("Y-m-d h:i:sa");
				?>
				<input type="hidden" name="transfer_date" value="<?php echo $curDate ?>">
				
				<div class="col-md-12">
					<div class="form-group">
						<?= lang("sold_amount"); ?>
						<input type="text" name="sold_amount" id="sold_amount" class="form-control" value="<?= $transfer_owner->grand_total ?>" style="pointer-events:none" />
					</div>
				</div>
				<!--
				<div class="col-md-12">
					<div class="form-group">
						<?= lang("charge_amount"); ?>
						<input type="text" name="charge_amount" class="form-control">
					</div>
				</div>
				-->
				<div class="row">
					<div class="col-md-12">
						<?php if ($Owner || $Admin ) { ?>
							<div class="col-sm-6">
								<div class="form-group">
									<?= lang("date", "date"); ?>
									<?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control date" id="date" required="required"'); ?>
								</div>
							</div>
						<?php } ?>
						<div class="col-sm-6" id="payment_ref">
							<div class="form-group">
								<?= lang("reference_no", "reference_no"); ?>
								<div style="float:left;width:100%;">
									<div class="form-group">
										<div class="input-group">  
											<?php echo form_input('reference_no', $reference?$reference:"",'class="form-control input-tip spref" id="reference_no" required="required"'); ?>
											<input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference?$reference:"" ?>" />
											<div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
												<input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div id="payments">

					<div class="well well-sm well_1">
						<div class="col-md-12">
							<div class="row">
								<div class="col-sm-12">
									<div class="payment">
										<div class="form-group">
											<?= lang("charge_amount", "amount_1"); ?>
											<input name="amount-paid" type="text" id="amount_1" amount="<?= $this->erp->formatDecimal($inv->grand_total - $inv->paid); ?>"
												   value="<?= $this->erp->formatDecimal($inv->grand_total - $inv->paid); ?>"
												   class="pa form-control kb-pad amount" required="required"/>
										</div>
									</div>
								</div>
								
								<div class="col-sm-6">
									<div class="form-group">
										<?= lang("paying_by", "paid_by_1"); ?>
										<select name="paid_by" id="paid_by_1" class="form-control paid_by"
												required="required">
											<option value="cash"><?= lang("cash"); ?></option>
											<option value="CC"><?= lang("CC"); ?></option>
											<option value="Cheque"><?= lang("cheque"); ?></option>
											<option value="other"><?= lang("other"); ?></option>
										</select>
									</div>
								</div>
								
								<div class="col-sm-6" id="bank_acc">
									<div class="form-group">
										<?= lang("bank_account", "bank_account_1"); ?>
										<?php
											$bank = array('0' => '-- Select Bank Account --');
											if ($Owner || $Admin) {
												foreach($bankAccounts as $bankAcc) {
													$bank[$bankAcc->accountcode] = $bankAcc->accountcode . ' | '. $bankAcc->accountname;
												}
												echo form_dropdown('bank_account', $bank, '', 'id="bank_account_1" class="ba form-control kb-pad bank_account" data-bv-notempty="true"');
											} else {
												$ubank = array('0' => '-- Select Bank Account --');
												foreach($userBankAccounts as $userBankAccount) {
													$ubank[$userBankAccount->accountcode] = $userBankAccount->accountcode . ' | '. $userBankAccount->accountname;
												}
												echo form_dropdown('bank_account', $ubank, '', 'id="bank_account_1" class="ba form-control kb-pad bank_account" data-bv-notempty="true"');
											}
										?>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="form-group gc" style="display: none;">
								<?= lang("gift_card_no", "gift_card_no"); ?>
								<input name="gift_card_no" type="text" id="gift_card_no" class="pa form-control kb-pad"/>

								<div id="gc_details"></div>
							</div>
							
							<div class="form-group dp" style="display: none;">
								<?= lang("customer", "customer1"); ?>
										<?php
										$customers1[] = array();
										foreach($customers as $customer){
											$customers1[$customer->id] = $customer->text;
										}
									echo form_dropdown('customer', $customers1, $inv->customer_id , 'class="form-control" id="customer1" style="display:none;"');
								?>
								<?= lang("deposit_amount", "deposit_amount"); ?>
								
								<div id="dp_details"></div>
							</div>
							
							<div class="pcc_1" style="display:none;">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<input name="pcc_no" type="text" id="pcc_no_1" class="form-control"
												   placeholder="<?= lang('cc_no') ?>"/>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">

											<input name="pcc_holder" type="text" id="pcc_holder_1" class="form-control"
												   placeholder="<?= lang('cc_holder') ?>"/>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<select name="pcc_type" id="pcc_type_1" class="form-control pcc_type"
													placeholder="<?= lang('card_type') ?>">
												<option value="Visa"><?= lang("Visa"); ?></option>
												<option value="MasterCard"><?= lang("MasterCard"); ?></option>
												<option value="Amex"><?= lang("Amex"); ?></option>
												<option value="Discover"><?= lang("Discover"); ?></option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<input name="pcc_month" type="text" id="pcc_month_1" class="form-control"
												   placeholder="<?= lang('month') ?>"/>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">

											<input name="pcc_year" type="text" id="pcc_year_1" class="form-control"
												   placeholder="<?= lang('year') ?>"/>
										</div>
									</div>
								</div>
							</div>
							<div class="pcheque_1" style="display:none;">
								<div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
									<input name="cheque_no" type="text" id="cheque_no_1" class="form-control cheque_no"/>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<?= lang("note", "note"); ?>
						<?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note"'); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<?php echo form_submit('transfer', lang('add_transfer'), 'class="btn btn-primary" id="add_transfer"'); ?>
		</div>
		<?php echo form_close(); ?>
    </div>
	<?= $modal_js; ?>
	<script>
		$(document).ready(function(){
			var slcustomer = '<?= $id; ?>';
			$('#slcustomer').select2({
				minimumInputLength: 1,
				ajax: {
					url: site.base_url + "customers/suggestions",
					dataType: 'json',
					quietMillis: 15,
					data: function (term, page) {
						return {
							term: term,
							limit: 10
						};
					},
					results: function (data, page) {
						if (data.results != null) {
							return {results: data.results};
						} else {
							return {results: [{id: '', text: 'No Match Found'}]};
						}
					}
				}
			});
			
			$(".spref").attr('readonly', true);
			$('#ref_st').on('ifChanged', function() {
			  if ($(this).is(':checked')) {
				$(".spref").attr('readonly', false);
				$(".spref").val("");
			  }else{
				$(".spref").attr('readonly', true);
				var temp = $("#temp_reference_no").val();
				$(".spref").val(temp);
				
			  }
			});
			
			$("#date").datetimepicker({
				format: site.dateFormats.js_sdate,
				fontAwesome: true,
				language: 'erp',
				weekStart: 1,
				todayBtn: 1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 2,
				forceParse: 0
			}).datetimepicker('update', new Date());
			
			$('.paid_by').change(function () {
				
				var p_val = $(this).val();
				$('#rpaidby').val(p_val);
				if (p_val == 'cash') {
					$('.pcheque_1').hide();
					$('.pcc_1').hide();
					$('.pcash_1').show();
					$('#payment_ref').show();
					$('#bank_acc').show();
					$('#amount_1').focus();
				} else if (p_val == 'CC') {
					$('.pcheque_1').hide();
					$('.pcash_1').hide();
					$('.pcc_1').show();
					$('#payment_ref').show();
					$('#bank_acc').show();
					$('#pcc_no_1').focus();
				} else if (p_val == 'Cheque') {
					$('.pcc_1').hide();
					$('.pcash_1').hide();
					$('.pcheque_1').show();
					$('#payment_ref').show();
					$('#bank_acc').show();
					$('#cheque_no_1').focus();
				} else {
					$('.pcheque_1').hide();
					$('.pcc_1').hide();
					$('.pcash_1').hide();
					$('#payment_ref').show();
					$('#bank_acc').show();
				}
				if (p_val == 'gift_card') {
					$('.gc').show();
					$('#payment_ref').show();
					$('#bank_acc').show();
					$('#gift_card_no').focus();
				} else {
					$('.gc').hide();
					$('#payment_ref').show();
					$('#bank_acc').show();
				}
				if(p_val == 'deposit') {
					$('.dp').show();
					$('#payment_ref').hide();
					$('#bank_acc').hide();
					$('#customer1').trigger('change');
				}else{
					$('.dp').hide();
					$('#payment_ref').show();
					$('#bank_acc').show();
					$('#dp_details').html('');
				}
				$('#amount_1').trigger('keyup');
			});
			$('#pcc_no_1').change(function (e) {
				var pcc_no = $(this).val();
				__setItem('pcc_no_1', pcc_no);
				var CardType = null;
				var ccn1 = pcc_no.charAt(0);
				if (ccn1 == 4)
					CardType = 'Visa';
				else if (ccn1 == 5)
					CardType = 'MasterCard';
				else if (ccn1 == 3)
					CardType = 'Amex';
				else if (ccn1 == 6)
					CardType = 'Discover';
				else
					CardType = 'Visa';

				$('#pcc_type_1').select2("val", CardType);
			});
			$('#add_transfer').click(function() {
				var us_paid = $('#amount_1').val()-0;
				var totalAmount = $("#sold_amount").val()-0;
				
				if(us_paid >= totalAmount){
					alert('Your charge amount more than sold amount ! \nPlease check your charge amount');				
					return false;
				}
				
				if(!$(".bank_account option:selected").val()){
					alert('Bank Account !, Please try again');				
					return false;
				}				
			});
		});
	</script>
</div>