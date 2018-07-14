<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_payment'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("pos/add_payment/" . $inv->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			
			<?php if ($Owner || $Admin) { ?>
				<div class="form-group" style="display:none !important;">
					<?= lang("biller", "biller"); ?>
					<?php
					foreach ($billers as $biller) {
						$bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
					}
					echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $biller_id), 'class="form-control" id="posbiller" required="required"');
					?>
				</div>
			<?php } else {
				$biller_input = array(
					'type' => 'hidden',
					'name' => 'biller',
					'id' => 'posbiller',
					'value' => $this->session->userdata('biller_id'),
				);

				echo form_input($biller_input);
			}
			?>

            <div class="row">
                <?php if ($Owner || $Admin ) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?= lang("date", "date"); ?>
                            <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control datetime" id="date" required="required"'); ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-6" id="payment_ref">
                    <div class="form-group">
                        <?= lang("reference_no", "reference_no"); ?>
                        <div class="form-group">
                            <div class="input-group">
                                <?php echo form_input('reference_no', $reference?$reference:"",'class="form-control input-tip spref" id="reference_no"'); ?>
                                <input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference?$reference:"" ?>" />
                                <div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
                                    <input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" value="<?php echo $inv->id; ?>" name="sale_id"/>
				<input type="hidden" value="<?= $inv->reference_no ?>" name="sale_reference_no" />
				<input type="hidden" value="<?= $inv->customer ?>" name="customer_name" />
            </div>
            <div class="clearfix"></div>
            <div id="payments">

                <div class="well well-sm well_1">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("amount", "amount_1"); ?>
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
                                        <option value="gift_card"><?= lang("gift_card"); ?></option>
										<option value="deposit"><?= lang("deposit"); ?></option>
                                        <option value="Cheque"><?= lang("cheque"); ?></option>
                                        <option value="other"><?= lang("other"); ?></option>
                                    </select>
                                </div>
                            </div>
							
							<div class="col-sm-12" id="bank_acc">
								<div class="form-group">
									<?= lang("bank_account", "bank_account_1"); ?>
									<?php $bank = array('0' => '-- Select Bank Account --');
									foreach($bankAccounts as $bankAcc) {
										$bank[$bankAcc->accountcode] = $bankAcc->accountcode . ' | '. $bankAcc->accountname;
									}
									echo form_dropdown('bank_account', $bank, '', 'id="bank_account_1" class="ba form-control kb-pad bank_account"');
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
                                        <!-- <input type="text" id="pcc_type_1" class="form-control" placeholder="<?= lang('card_type') ?>" />-->
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
                                <!--<div class="col-md-3">
                                                        <div class="form-group">
                                                            <input name="pcc_ccv" type="text" id="pcc_cvv2_1" class="form-control" placeholder="<?= lang('cvv2') ?>" />
                                                        </div>
                                                    </div>-->
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

            <div class="form-group">
                <?= lang("attachment", "attachment") ?>
                <input id="attachment" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>

            <div class="form-group">
                <?= lang("note", "note"); ?>
                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_payment', lang('add_payment'), 'class="btn btn-primary" id="add_payment"'); ?>
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
		
		$(".spref").attr('disabled','disabled');
		$('#ref_st').on('ifChanged', function() {
		  if ($(this).is(':checked')) {
			$(".spref").prop('disabled', false);
			$(".spref").val("");
		  }else{
			$(".spref").prop('disabled', true);
			var temp = $("#temp_reference_no").val();
			$(".spref").val(temp);
			
		  }
		});
		
		$('#add_payment').click(function(){
			var us_paid = $('#amount_1').val()-0;
			var deposit_amount = parseFloat($(".deposit_total_amount").text());
			var deposit_balance = parseFloat($(".deposit_total_balance").text());
			deposit_balance = (deposit_amount - Math.abs(us_paid));
			$(".deposit_total_balance").text(deposit_balance);

			if(deposit_balance > deposit_amount || deposit_balance < 0 || deposit_amount == 0){
				bootbox.alert('Your Deposit Limited: ' + deposit_amount);
				$('#amount_1').val(deposit_amount);
				$(".deposit_total_balance").text(deposit_amount - $('#amount_1').val()-0);
				return false;
			}
		});
		
        $('#gift_card_no').change(function () {
            var cn = $(this).val() ? $(this).val() : '';
            if (cn != '') {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "sales/validate_gift_card/" + cn,
                    dataType: "json",
                    success: function (data) {
                        if (data === false) {
                            $('#gift_card_no').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('incorrect_gift_card')?>');
                        } else if (data.customer_id !== null && data.customer_id != <?=$inv->customer_id?>) {
                            $('#gift_card_no').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('gift_card_not_for_customer')?>');

                        } else {
                            var due = <?=$inv->grand_total-$inv->paid?>;
                            if (due > data.balance) {
                                $('#amount_1').val(formatDecimal(data.balance));
                            }
                            $('#gc_details').html('<small>Card No: <span style="max-width:60%;float:right;">' + data.card_no + '</span><br>Value: <span style="max-width:60%;float:right;">' + currencyFormat(data.value) + '</span><br>Balance: <span style="max-width:60%;float:right;">' + currencyFormat(data.balance) + '</span></small>');
                            $('#gift_card_no').parent('.form-group').removeClass('has-error');
                        }
                    }
                });
            }
        });
		$('#customer1').change(function(){
				checkDeposit();
				$('#amount_1').trigger('change');
		});
		
		function checkDeposit() {
			var customer_id = $("#customer1").val();
            if (customer_id != '') {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "sales/validate_deposit/" + customer_id,
                    dataType: "json",
                    success: function (data) {
                        if (data === false) {
                            $('#deposit_no_1').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('invalid_customer')?>');
                        } else if (data.id !== null && data.id !== customer_id) {
                            $('#deposit_no_1').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('this_customer_has_no_deposit')?>');
                        } else {
							//var amount = $("#amount_1").val();
							var deposit_amount =  ((data.dep_amount==null)? 0:data.dep_amount);
							var deposit_balance = ((data.balance==null)? 0:data.balance);
                            $('#dp_details').html('<small>Customer Name: ' + data.name + '<br/>Amount: <span class="deposit_total_amount">' + data.balance + '</span> - Balance: <span class="deposit_total_balance">' + deposit_balance + '</span></small>');
                            $('#amount_1').attr('deposit_balance', deposit_balance);
							$('#deposit_no').parent('.form-group').removeClass('has-error');
                            //calculateTotals();
                            //$('#amount_1').val(data.deposit_amount - amount).focus();
                        }
                    }
                });
            }
		}
		
		$('#amount_1').keyup(function () {
			var us_paid = parseFloat($('#amount_1').val()-0);
			var amount = parseFloat($('#amount_1').attr('amount')-0);
			var p_val = $('#paid_by_1').val();
			var new_deposit_balance = 0;
			if(p_val == 'deposit') {
				var deposit_balance = parseFloat($('#amount_1').attr('deposit_balance')-0);
				new_deposit_balance = deposit_balance - us_paid;
				if(!us_paid) {
					$('#amount_1').val(0);
					$('#amount_1').select();
				}else if(new_deposit_balance < 0) {
					$('#amount_1').val(deposit_balance);
					$(".deposit_total_balance").text(0);
					$('#amount_1').select();
				}else {
					$(".deposit_total_balance").text(new_deposit_balance);
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
        $("#date").datetimepicker({
            format: site.dateFormats.js_ldate,
            fontAwesome: true,
            language: 'erp',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0
        }).datetimepicker('update', new Date());
    });
</script>
