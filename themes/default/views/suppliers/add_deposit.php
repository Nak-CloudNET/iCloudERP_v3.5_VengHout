
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_deposit') . " (" . $supplier->name . ")"; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("suppliers/add_deposit", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			<input type="hidden" name="supplier_id" value="<?= $supplier->id;?>"/>
            <div class="row">
                <div class="col-sm-12">
					<div class="form-group">
						<?= lang('reference_no', 'reference_no'); ?>
						<!--<div class="input-group">  -->
						<?= form_input('reference_no',$reference, 'class="form-control tip"  required  id="reference_no"'); ?>
						<input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference ?>" />
						<!--<div class="input-group-addon no-print">
								<input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
							</div>
						</div>-->
					</div>
					<?php if ($Owner || $Admin) { ?>
						<div class="form-group">
							<?= lang("project", "biller"); ?>
							<?php
							foreach ($billers as $biller) {
								$bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
							}
							echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $Settings->default_biller), 'class="form-control" id="posbiller" required="required"');
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
					
					<!--<div class="form-group">
                        <?= lang("suppliers", "suppliers"); ?>
							<?php
							$cust = array();
							foreach ($suppliers as $supplier) {
								$cust[$supplier->id] = $supplier->name;
							}
							echo form_dropdown('supplier', $cust, '', 'class="form-control supplier" required="required"');
							?>
                    </div>-->
				
                    <?php if ($Owner || $Admin) { ?>
                    <div class="form-group">
                        <?php echo lang('date', 'date'); ?>
                        <div class="controls">
                            <?php echo form_input('date', set_value('date', date($dateFormats['php_ldate'])), 'class="form-control datetime" id="date" required="required"'); ?>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group">
                        <?php echo lang('amount', 'amount'); ?>
                        <div class="controls">
                            <?php echo form_input('amount', set_value('amount'), 'class="form-control" id="amount" required="required"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
						<?= lang("paying_by", "paid_by_1"); ?>
						<select name="paid_by" id="paid_by_1" class="form-control paid_by"
								required="required">
							<option value="cash"><?= lang("cash"); ?></option>
							<option value="CC"><?= lang("CC"); ?></option>
							<option value="gift_card"><?= lang("gift_card"); ?></option>
							<option value="Cheque"><?= lang("cheque"); ?></option>
							<option value="other"><?= lang("other"); ?></option>
						</select>
					</div>
					
					<div class="form-group">
						<?= lang("bank_account", "bank_account_1"); ?>
						<?php
							$bank = array('0' => '-- Select Bank Account --');
							if ($Owner || $Admin) {
	                            foreach($bankAccounts as $bankAcc) {
	                                $bank[$bankAcc->accountcode] = $bankAcc->accountcode . ' | '. $bankAcc->accountname;
	                            }
	                            echo form_dropdown('bank_account', $bank, '', 'id="bank_account_1" class="ba form-control kb-pad bank_account" required="true"');
	                        } else {
	                        	$ubank = array('0' => '-- Select Bank Account --');
	                            foreach($userBankAccounts as $userBankAccount) {
	                                $ubank[$userBankAccount->accountcode] = $userBankAccount->accountcode . ' | '. $userBankAccount->accountname;
	                            }
	                            echo form_dropdown('bank_account', $ubank, '', 'id="bank_account_1" class="ba form-control kb-pad bank_account" required="true"');
	                        }
						?>
					</div>
                         
					<div class="form-group gc" style="display: none;">
						<?= lang("gift_card_no", "gift_card_no"); ?>
						<input name="gift_card_no" type="text" id="gift_card_no" class="pa form-control kb-pad"/>

						<div id="gc_details"></div>
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
						</div>
					</div>
					<div class="pcheque_1" style="display:none;">
						<div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
							<input name="cheque_no" type="text" id="cheque_no_1" class="form-control cheque_no"/>
						</div>
					</div>
							
					<!--<div class="form-group">
                        <?= lang("po_reference_no", "po_reference_no"); ?>
						<?php
							$po_ref = array();
							$po_ref[""] = "";
							foreach ($po_reference as $por) {
								$po_ref[$por->reference_no] = $por->reference_no;
							}
							echo form_dropdown('po_reference_no', $po_ref, '', 'class="form-control po_reference_no"');
						?>
                    </div>-->
					<div class="view_balance" style="display:none;">
						<?= lang("balance", "balance"); ?> : <span class="balance_by_po"></span>
					</div>
					
                    <div class="form-group">
                        <?php echo lang('note', 'note'); ?>
                        <div class="controls">
                            <?php echo form_textarea('note', set_value('note'), 'class="form-control" id="note"'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
			<input type="hidden"  id="po_grand_total" value="9999999999" />
			<input type="hidden"  id="po_paid" value="0" />
							
            <?php echo form_submit('add_deposit', lang('add_deposit'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
		$("#reference_no").attr('readonly','readonly');
		$('#ref_st').on('ifChanged', function() {
		  if ($(this).is(':checked')) {
			$("#reference_no").prop('readonly', false);
			$("#reference_no").val("");
		  }else{
			$("#reference_no").prop('readonly', true);
			var temp = $("#temp_reference_no").val();
			$("#reference_no").val(temp);
			
		  }
		});
        $(document).on('change', '#gift_card_no', function () {
			var customer_id = $(".supplier").val();
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
                        } else if (data.customer_id != null && data.customer_id != customer_id) {
                            $('#gift_card_no').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('gift_card_not_for_customer')?>');
                        } else {
                            //var due = <?=$inv->grand_total-$inv->paid?>;
							var due = parseFloat($("#amount").val());
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
        $(document).on('change', '.paid_by', function () {
            var p_val = $(this).val();
            $('#rpaidby').val(p_val);
            if (p_val == 'cash') {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').show();
                $('#amount_1').focus();
            } else if (p_val == 'CC') {
                $('.pcheque_1').hide();
                $('.pcash_1').hide();
                $('.pcc_1').show();
                $('#pcc_no_1').focus();
            } else if (p_val == 'Cheque') {
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                $('.pcheque_1').show();
                $('#cheque_no_1').focus();
            } else {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').hide();
            }
            if (p_val == 'gift_card') {
                $('.gc').show();
                $('#gift_card_no').focus();
            } else {
                $('.gc').hide();
            }
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
		$('.po_reference_no').change(function(){
			var ref = $(this).val();
				$.ajax({
					type: 'get',
					url: '<?= site_url('suppliers/getPORef'); ?>',
					dataType: "json",
					data: {
						ref: ref,
					},
					success: function (data) {
						
						var deposit=$('#amount').val();
						$('#po_grand_total').val(data['grand_total']);
						$('#po_paid').val(data['paid']);
						$('.view_balance').show();
						$('.balance_by_po').text(data['grand_total'] - data['paid']);
						if(deposit > data['grand_total'] - data['paid']){
							bootbox.alert('<?=lang('amount_deposit_over_balance!');?>');
							$( "#amount" ).val(data['grand_total']-data['paid']);
						}
						
					}
				});
		});
		$( "#amount" ).keyup(function (){
			var po_grand_total=parseFloat($('#po_grand_total').val());
			var po_paid=parseFloat($('#po_paid').val());
			var amount = parseFloat($(this).val());
			if(amount>po_grand_total-po_paid){
				bootbox.alert('<?=lang('amount_deposit_over_balance!');?>');
				$( "#amount" ).val(po_grand_total-po_paid);
			}
		});
		
    });
</script>

