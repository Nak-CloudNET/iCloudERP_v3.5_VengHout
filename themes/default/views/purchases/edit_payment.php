<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_payment'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("purchases/edit_payment/" . $payment->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <?php if ($Owner || $Admin) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?= lang("date", "date"); ?>
                            <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : $this->erp->hrld($payment->date)), 'class="form-control datetime" id="date" required="required"'); ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("reference_no", "reference_no"); ?>
						
                        <?= form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $payment->reference_no), 'class="form-control tip refer_o" id="reference_no" readonly required="required"'); ?>
						<input type="text" name="reference_no_auto" style="display:none;" readonly class="form-control refer_auto" id="reference_no_auto" value="<?=$auto_ref;?>">
						<input type="text" name="reference_no_o" style="display:none;" readonly class="form-control refer" id="reference_no_o" value="<?=$inv->reference_no;?>">
                    </div>
                </div>

                <input type="hidden" value="<?php echo $payment->purchase_id; ?>" name="purchase_id"/>
            </div>
            <div class="clearfix"></div>
            <div id="payments">

                <div class="well well-sm well_1">
                    <div class="col-md-12">
                        <div class="row">
							<div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("discount", "discount"); ?>
                                        <input name="discount" value="<?= $this->erp->formatDecimal($payment->discount); ?>" type="text" class="form-control" id="discount"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("amount", "amount_1"); ?>
                                       <!-- <input name="amount-paid"
                                               value="<?= $payment->amount; ?>" type="text"
                                               id="amount_1" class="pa form-control kb-pad amount number_only" required="required"/>
										<input type="hidden" name="amount_3" id="amount_3" value="<?=$payment->amount;?>">
										<input type="hidden" name="suppliers_id" id="suppliers_id" value="<?=$inv->supplier_id;?>">
										<input name="amount_2"
                                               value="<?= $this->erp->formatDecimal($payment->amount); ?>" type="hidden"
                                               id="amount_2" class="pa form-control kb-pad"/> 
										<input type="hidden" name="g_total" id="g_total" value="<?=$inv->grand_total;?>"> -->
										<input name="amount-paid" type="text" id="amount_1" amount="<?= (($inv->grand_total - $inv->paid) + ($payment->amount + $payment->discount)) ?>"
                                               value="<?= $this->erp->formatDecimal($payment->amount); ?>"
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
                                        <option value="CC"><?= lang("cc"); ?></option>
                                        <option value="Cheque"><?= lang("cheque"); ?></option>
										<option value="deposit"><?= lang("deposit"); ?></option>
                                        <option value="other"><?= lang("other"); ?></option>
                                    </select>
									<input type="hidden" name="paid_2" id="paid_2" value="<?=$payment->paid_by;?>">
                                </div>
                            </div>
							<div class="col-sm-6 bank_o">
								<div class="form-group">
									<?= lang("bank_account*", "bank_account_1"); ?>
									<?php $bank = array('' => '');
									foreach($bankAccounts as $bankAcc) {
										$bank[$bankAcc->accountcode] = $bankAcc->accountcode . ' | '. $bankAcc->accountname;
									}
									echo form_dropdown('bank_account', $bank, $payment->bank_account, 'id="bank_account_1" class="ba form-control kb-pad bank_account"');
									?>
								</div>
                            </div>
							<div class="col-sm-12">
							<div class="form-group dp" style="display: none;">
							<?= lang("supplier", "supplier1"); ?>
							<?php
							//$suppliers1[] = array();
							//foreach($suppliers as $supplier){
							//	$suppliers1[$supplier->id] = $supplier->name;
							//}
							//echo form_dropdown('supplier', $suppliers1, $suppliers_id , 'class="form-control" id="supplier1"');
							?>
							<input type="hidden" name="suppliers_id" id="suppliers_id" value="<?=$inv->supplier_id;?>">
							
							<input type="hidden" name="paid_o" id="paid_o" value="<?=$inv->paid_by;?>">
							<input type="hidden" name="amount_o" id="amount_o" value="<?=$inv->paid;?>">
							<?= lang("deposit_amount", "deposit_amount"); ?>
							<div id="dp_details"></div>
							</div>
						</div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="pcc_1" style="display:none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input name="pcc_no" value="<?= $payment->cc_no; ?>" type="text" id="pcc_no_1"
                                               class="form-control" placeholder="<?= lang('cc_no') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">

                                        <input name="pcc_holder" value="<?= $payment->cc_holder; ?>" type="text"
                                               id="pcc_holder_1" class="form-control"
                                               placeholder="<?= lang('cc_holder') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="pcc_type" id="pcc_type_1" class="form-control pcc_type"
                                                placeholder="<?= lang('card_type') ?>">
                                            <option
                                                value="Visa"<?= $payment->cc_type == 'Visa' ? ' checked="checcked"' : '' ?>><?= lang("Visa"); ?></option>
                                            <option
                                                value="MasterCard"<?= $payment->cc_type == 'MasterCard' ? ' checked="checcked"' : '' ?>><?= lang("MasterCard"); ?></option>
                                            <option
                                                value="Amex"<?= $payment->cc_type == 'Amex' ? ' checked="checcked"' : '' ?>><?= lang("Amex"); ?></option>
                                            <option
                                                value="Discover"<?= $payment->cc_type == 'Discover' ? ' checked="checcked"' : '' ?>><?= lang("Discover"); ?></option>
                                        </select>
                                        <!-- <input type="text" id="pcc_type_1" class="form-control" placeholder="<?= lang('card_type') ?>" />-->
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input name="pcc_month" value="<?= $payment->cc_month; ?>" type="text"
                                               id="pcc_month_1" class="form-control"
                                               placeholder="<?= lang('month') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">

                                        <input name="pcc_year" value="<?= $payment->cc_year; ?>" type="text"
                                               id="pcc_year_1" class="form-control" placeholder="<?= lang('year') ?>"/>
                                    </div>
                                </div>
                                <!--<div class="col-md-3">
                                                        <div class="form-group">
                                                            <input name="pcc_ccv" type="text" id="pcc_cvv2_1" class="form-control" placeholder="<?= lang('cvv2') ?>" />
                                                        </div>
                                                    </div>-->
                            </div>
                        </div>
                        <div class="pcheque_1" style="display:none;">
                            <div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
                                <input name="cheque_no" value="<?= $payment->cheque_no; ?>" type="text" id="cheque_no_1"
                                       class="form-control cheque_no"/>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

            <div class="form-group">
                <?= lang("attachment", "attachment") ?>
                <input id="attachment" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>

            <div class="form-group">
                <?= lang("note", "note"); ?>
                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : $this->erp->decode_html(strip_tags($payment->note))), 'class="form-control" id="note"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_payment', lang('edit_payment'), 'class="btn btn-primary" id="edit_payment" style="display:none;"'); ?>
			<button class="btn btn-primary" id="edit_payment_test">Edit Payment</button>
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
		$(document).on('click', '#edit_payment_test', function(){
			var tt = 0,gt = 0;
			var gtotal = $("#g_total").val()-0;
			var amount_1 = $("#amount_1").val()-0;
			var amount_2 = $("#amount_2").val()-0;
			var total = $("#total").val()-0;
			if(amount_1>amount_2){
				tt = amount_1 - amount_2;
				gt = total + tt;
			}else{
				tt = amount_2 - amount_1;
				gt = total - tt;
			}
			
			
			if(gt>gtotal){
				bootbox.alert('Total Amount ('+gt+') must less than Grands Total ('+gtotal+')');
				return false;
			}
			if(amount_1<0){
				bootbox.alert('Amount is invalid '+amount_1);
				return false;
			}
			$("#edit_payment").trigger("click");
			
		});
		function checkDeposit() {
			var supplier_id = $("#suppliers_id").val();
            if (supplier_id != '') {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "sales/validate_deposit/" + supplier_id,
                    dataType: "json",
                    success: function (data) {
                        if (data === false) {
                            $('#deposit_no_1').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('invalid_supplier')?>');
                        } else if (data.id !== null && data.id !== supplier_id) {
                            $('#deposit_no_1').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('this_supplier_has_no_deposit')?>');
                        } else {
							var am =0;
							var amount3 = $("#amount_3").val();
							var amount = $("#amount_1").val()-0;
							var amount_2 = $("#amount_2").val();
							var paid_2 = $("#paid_2").val();
							var deposit_amount =  (data.dep_amount==null?0: data.dep_amount);
							var deposit_balance = 0;//(deposit_amount - amount);
							if(paid_2 == "cash"){
								deposit_balance = (deposit_amount - amount);
							}else{
								if(amount>amount3){
									am = amount - amount3;
									deposit_balance = (deposit_amount - am);
								}else if(amount<amount3){
									am = amount3 - amount;
									deposit_balance = (deposit_amount + am);
								}else{
									deposit_balance = deposit_amount - amount;
								}
								//deposit_balance = deposit_amount - amount3;
							}
                            $('#dp_details').html('<small>Supplier Name: ' + data.name + '<br>Amount: <span class="deposit_total_amount">' + data.dep_amount + '</span> / Balance: <span class="deposit_total_balance">' + deposit_balance + '</span><input type="hidden" name="deposit_amount" class="deposit_amount" value="'+deposit_balance+'" ></small>');
                            $('#deposit_no').parent('.form-group').removeClass('has-error');
                            //calculateTotals();
                            //$('#amount_1').val(data.deposit_amount - amount).focus();
                        }
                    }
                });
            }
		}
		
		$('#amount_1').on('keyup change', function () {
			var us_paid = parseFloat($('#amount_1').val()-0);
			var disc = parseFloat($('#discount').val() - 0)
			var amount = parseFloat($('#amount_1').attr('amount')-0);
			amount -= disc;
			var p_val = $('#paid_by_1').val();
			var new_deposit_balance = 0;
			if(p_val == 'deposit') {
				var deposit_balance = parseFloat($('#amount_1').attr('deposit_balance')-0);
				new_deposit_balance = deposit_balance - us_paid;
				if(!us_paid) {
					$('#amount_1').val(0);
					$(".deposit_total_balance").text(deposit_balance);
					$('#amount_1').select();
				}else if(new_deposit_balance < 0) {
					if(deposit_balance > amount) {
						$('#amount_1').val(amount);
						$(".deposit_total_balance").text(formatDecimal(new_deposit_balance));
					}else {
						$('#amount_1').val(deposit_balance);
						$(".deposit_total_balance").text(0);
					}
					$('#amount_1').select();
				}else if(us_paid > amount){
					$('#amount_1').val(amount);
					$(".deposit_total_balance").text(formatDecimal(new_deposit_balance));
					$('#amount_1').select();
				}else {
					$(".deposit_total_balance").text(formatDecimal(new_deposit_balance));
				}
				
			}else {
				if(!us_paid) {					
					$('#amount_1').val(0);
					$('#amount_1').select();
				}else if(us_paid > amount) {
					$('#amount_1').val(amount);
					$('#amount_1').select();
				}
			}
		});
		
		$('#discount').on('keyup change', function() {
			var disc = parseFloat($(this).val() - 0);
			var amount = parseFloat($('#amount_1').attr('amount') - 0);
			var paid = amount - disc;
			if(paid < 0) {
				$(this).val(formatDecimal(amount));
				$('#amount_1').val(formatDecimal(0));
				$('#amount_1').trigger('change');
			}else {
				$('#amount_1').val(formatDecimal(paid));
				$('#amount_1').trigger('change');
			}
		});
		
		/* $(document).on('keyup', '#amount_1', function () {
			var deposit_balance = 0;
			var am =0;
			var paid_2 = $("#paid_2").val();
			var amount_2 = $("#amount_2").val();
			var amount = $('#amount_1').val()-0;
			var amount3 = $('#amount_3').val()-0;
			var deposit_amount = parseFloat($(".deposit_amount").val()-0);
			alert(amount_2+'////'+amount+'////'+amount3+'////'+paid_2);
			//deposit_balance = (deposit_amount - us_paid);
			if(paid_2 == "cash"){
				deposit_balance = (deposit_amount - amount);
			}else{
				if(amount>amount3){
					am = amount - amount3;
					deposit_balance = (deposit_amount - am);
				}else if(amount==amount3){
					am = amount3 - amount;
					deposit_balance = (deposit_amount + am);
				}else{
					deposit_balance = deposit_amount - amount;
				}
				//deposit_balance = deposit_amount - amount3;
			}
			$(".deposit_total_balance").text(deposit_balance);
		}).trigger('change'); */
		
        $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
        $(document).on('change', '.paid_by', function () {
			var p_val2 = '<?=$payment->paid_by?>';
            var p_val = $(this).val();
            __setItem('paid_by', p_val);
            $('#rpaidby').val(p_val);
            if (p_val == 'cash') {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').show();
                $('#amount_1').focus();
				$(".refer_o").show();
				$(".bank_o").show();
				$(".refer").hide();
				if(p_val2 == "deposit"){
					$(".refer_o").hide();
					$(".refer_auto").show();
				}
            } else if (p_val == 'CC') {
                $('.pcheque_1').hide();
                $('.pcash_1').hide();
                $('.pcc_1').show();
                $('#pcc_no_1').focus();
				$(".refer_o").show();
				$(".bank_o").show();
				$(".refer").hide();
				if(p_val2 == "deposit"){
					$(".refer_o").hide();
					$(".refer_auto").show();
				}
            } else if (p_val == 'Cheque') {
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                $('.pcheque_1').show();
                $('#cheque_no_1').focus();
				$(".refer_o").show();
				$(".bank_o").show();
				$(".refer").hide();
				if(p_val2 == "deposit"){
					$(".refer_o").hide();
					$(".refer_auto").show();
				}
				
            } else {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').hide();
            }
			if(p_val == 'deposit') {
				$('.dp').show();
				$('#supplier1').trigger('change');
				$(".bank_o").hide();
				$(".refer").show();
				$(".refer_o").hide();
				$(".refer_auto").hide();
				checkDeposit();
				$('#amount_1').trigger('change');
			}else{
				$('.dp').hide();
                $('#dp_details').html('');
			}
        });
        var p_val = '<?=$payment->paid_by?>';
        __setItem('paid_by', p_val);
        if (p_val == 'cash') {
			$('.pcheque_1').hide();
			$('.pcc_1').hide();
			$('.pcash_1').show();
			$('#amount_1').focus();
			$(".refer_o").show();
			$(".bank_o").show();
			
		} else if (p_val == 'CC') {
			$('.pcheque_1').hide();
			$('.pcash_1').hide();
			$('.pcc_1').show();
			$('#pcc_no_1').focus();
			$(".refer_o").show();
			$(".bank_o").show();
			
		} else if (p_val == 'Cheque') {
			$('.pcc_1').hide();
			$('.pcash_1').hide();
			$('.pcheque_1').show();
			$('#cheque_no_1').focus();
			$(".refer_o").show();
			$(".bank_o").show();
			
		} else {
			$('.pcheque_1').hide();
			$('.pcc_1').hide();
			$('.pcash_1').hide();
		}
		if(p_val == 'deposit') {
			$('.dp').show();
			$('#supplier1').trigger('change');
			$(".bank_o").hide();
			$(".refer_o").hide();
			$(".refer").show();
			checkDeposit();
			$('#amount_1').trigger('change');
		}else{
			$('.dp').hide();
			$('#dp_details').html('');
		}
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
        $('#paid_by_1').select2("val", '<?=$payment->paid_by?>');
    });
</script>
