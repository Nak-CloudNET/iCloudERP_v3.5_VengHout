
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_payment'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("sales/add_payment_loans/" . $loan->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <?php if ($Owner || $Admin|| $GP["sales-loan"] || $GP["sales-payments"]) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?= lang("date", "date"); ?>
                            <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : date("d/m/Y h:m")), 'class="form-control datetime" id="date" required="required"'); ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("reference_no", "reference_no"); ?>
                        <?= form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $payment_ref), 'class="form-control tip" id="reference_no"'); ?>
                    </div>
                </div>

                
				
            </div>
            <div class="clearfix"></div>
            <div id="payments">
				<div class="well well-sm well_1">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="payment">
                                    <div class="form-group">
                                        <?= lang("principle", "principle"); ?>
                                        <input name="amount-paid" type="text" id="amount_1"
                                               value="<?= $this->erp->formatDecimal($total_payment); ?>"
                                               class="pa form-control kb-pad amount number_only" style="pointer-events:none" required="required"/>
										<input name="loan_id" type="hidden" value="<?=$id?>" />
										<input name="sale_id" type="hidden" value="<?= $sale_id; ?>" />
										<input name="paid_amount" type="hidden" value="<?=$paid_amount?>" />
										<input name="principle" type="hidden" value="<?=$principle?>" />
                                    </div>
                                </div>
								<div class="payment">
                                    <div class="form-group">
                                        <?= lang("interest", "interest"); ?>
                                        <input name="interest type="text" id="interest"
                                               value="<?= $this->erp->formatDecimal($interest); ?>"
                                               class="pa form-control kb-pad interest number_only" style="pointer-events:none" required="required"/>
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
                                        <option value="Cheque"><?= lang("cheque"); ?></option>
                                        <option value="Bank_Sleep"><?= lang("bank_sleep"); ?></option>
                                        <option value="other"><?= lang("other"); ?></option>
                                    </select>
                                </div>
								<div class="form-group">
                                        <?= lang("payment", "payment"); ?>
                                        <input name="payment type="text" id="payment"
                                               value=""
                                               class="pa form-control kb-pad payment number_only" required="required"/>
                                </div>
                            </div>
                        </div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="balance">Owed Balance</label>											<input name="balance" value="" class="form-control" id="balance" style="pointer-events: none;" type="text">
									<input name="cus_deposit" id="cus_deposit" value="" type="hidden">
									<input name="cus_depositid" id="cus_depositid" value="" type="hidden">
								</div>
							</div>	
                            <div class="col-sm-6">
                                <div class="extra_payment">
                                    <div class="form-group">
                                        <?= lang("extra_payment", "extra_amt_1"); ?>
                                        <input name="extra_amt" type="text" id="extra_amt_1"
                                               class="pa form-control kb-pad extra_amt number_only"/>
                                    </div>
                                </div>
                            </div>
						</div>
                        <div class="clearfix"></div>
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
						
						<!-- Chanthy -->
						<div class="row">
							<div class="col-sm-6">
								<div class="sbank_1" style="display:none;">
									<div class="form-group"><?= lang("bank_no", "bank_no_1"); ?>
										<input name="bank_no" type="text" id="bank_no_1" class="form-control bank_no"/>
									</div>
								</div>
							</div>
							<?php if ($Owner || $Admin|| $GP["sales-loan"] || $GP["sales-payments"]) { ?>
								<div class="col-sm-6">
									<div class="sdate_1" style="display:none;">
										<div class="form-group">
											<?= lang("date", "date"); ?>
											<?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : date("d/m/Y")), 'class="form-control date" id="date" required="required"'); ?>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
						<!-- //Chanthy -->
						
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

            <div class="form-group">
                <?= lang("note", "note"); ?>
                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_payment', lang('add_payment'), 'class="btn btn-primary"'); ?>
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
		
		
		$(".payment").on('change',function(){
			var principle = $("#amount_1").val()-0;
			var payment   = $("#payment").val()-0;
			var balance   = (principle-payment);
			$("#balance").val(balance);
		});

        $(document).on('change', '#gift_card_no', function () {
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
                        } else if (data.customer_id !== null && data.customer_id != '<?=$loan->customer_id?>') {
                            $('#gift_card_no').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('gift_card_not_for_customer')?>');

                        } else {
                            var due = <?=$loan->grand_total-$loan->paid?>;
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
		
		//Chanthy
		$(document).on('change', '.paid_by', function () {
            var p_val = $(this).val();
            $('#rpaidby').val(p_val);
            if (p_val == 'cash') {
                $('.sbank_1').hide();
                $('.sdate_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').show();
                $('#amount_1').focus();
            } else if (p_val == 'CC') {
                $('.sbank_1').hide();
                $('.sdate_1').hide();
                $('.pcash_1').hide();
                $('.pcc_1').show();
                $('#pcc_no_1').focus();
            } else if (p_val == 'Bank_Sleep') {
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                $('.sbank_1').show();
                $('.sdate_1').show();
                $('#bank_no_1').focus();
            } else {
                $('.sbank_1').hide();
                $('.sdate_1').hide();
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
		//------------------------------------
		
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
