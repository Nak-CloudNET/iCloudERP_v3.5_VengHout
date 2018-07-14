<script>
	$(".amount_paid_line").on('keyup',function() {
		var val = $(this).val();
		var balance = removeCommas($(this).closest('tr').find('td.balance').text());
		if(val > balance){
			alert("Your Amount is greater than Balance");
			$(this).addClass('f-cus');
			return false;
		}
        calculateSum();
	});
		
	function calculateSum() {
		var sum = 0;
		//iterate through each textboxes and add the values
		$(".amount_paid_line").each(function() {
			//add only if the value is number
			if (!isNaN(this.value) && this.value.length != 0) {
				sum += parseFloat(this.value);
			}
		 
		});

		$("#amount_1").val(sum.toFixed(2));
	}
	$(".remove_line").on('click',function() {
		var row = $(this).closest('tr').focus();
		row.remove();
		var total_balance = 0;
		var total_amount_paid = 0; 
		$('#CompTable tr').each(function() {
			var balance = $(this).find(".balance").text();
			var amount_paid = $(this).find(".amount_paid_line").val();
		if(balance  != "" && (amount_paid != "" || amount_paid!=0)){
				total_balance+=parseFloat(balance);
				total_amount_paid += parseFloat(amount_paid);
			}
		});
		
		$('#CompTable tfoot').each(function() {
		  $('#total_balance').text(total_balance);
		  $('#paid_amount').text(total_amount_paid);
		  $('#amount_1').val(total_amount_paid);
		  
		});
	});
    
    $("#add_submit").on('click', function(){
		var sum = 0;
		$(".amount_paid_line").each(function() {
			var balance = removeCommas($(this).closest('tr').find('td.balance').text());
			//add only if the value is number 
			var val = $(this).val();
			var balance = removeCommas($(this).closest('tr').find('td.balance').text());
			if(val > balance){
				isValid = false;
				$(this).closest(".f-cus").focus();
				return false;
			}else{
				isValid = true;
			}
		});
		
		if(isValid == false){
			alert("Your Amount is greater than Balance");
			return false;
		}
	});
	
	$("#slref").attr('readonly','readonly');
    $('#ref_st').on('ifChanged', function() {
      if ($(this).is(':checked')) {
        $("#slref").prop('readonly', false);
        $("#slref").val("");
      }else{
        $("#slref").prop('readonly', true);
        var temp = $("#temp_reference_no").val();
        $("#slref").val(temp);
        
      }
    });
	
</script>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_payment'); ?></h4>
        </div>
		
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("purchases/combine_payment_supplier", $attrib); ?>
        <div class="modal-body">
			<div class="table-responsive">
                <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th style="width:30%;"><?= $this->lang->line("date"); ?></th>
                        <th style="width:30%;"><?= $this->lang->line("reference_no"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("balance"); ?></th>
                        
                        <th style="width:10%;"><?= $this->lang->line("amount_paid"); ?></th>
						<th style="width:10%;"><?= $this->lang->line("Action"); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($combine_purchases)) {
						$total=0;
                        foreach ($combine_purchases->result() as $combine_purchase) { ?>
                            <tr class="row<?= $combine_purchase->id ?>">
                                <td><?= $this->erp->hrld($combine_purchase->date); ?></td>
                                <td><?= $combine_purchase->reference_no; ?></td>                           
								<td class="balance" val='<?=$combine_purchase->balance?>'><?= $this->erp->formatMoney($combine_purchase->balance); ?></td>
                                <td class="col-xs-3">
									<input type="hidden" name="supplier_balance" class="form-control" value="<?= $supplier_balance ?>" id="supplier_balance">
									<input type="hidden" name="purchase_id[]" class="form-control" value="<?= $combine_purchase->id ?>">
                                    <input type="text" name="amount_paid_line[]" class="amount_paid_line form-control" value="<?= $combine_purchase->balance; ?>">
                                </td>
								<td style="text-align: center;cursor:default;">
									<i class="fa fa-2x remove_line">&times;</i>
								</td>
                            </tr>
                        <?php 
						$total+=$combine_purchase->balance;
                        $tamountpaid += $combine_purchase->balance;
						}
                    } else {
                        echo "<tr><td colspan='4'>" . lang('no_data_available') . "</td></tr>";
                    } ?>
                    </tbody>
					<tfoot>
                        <tr>
                            <td class="text-right" colspan="2"><strong><?= lang('total') ?></strong></td>
							<td><strong id="total_balance"><?= $this->erp->formatDecimal($total); ?></strong></td>
                            <td><strong id="total_paid"><?= $this->erp->formatDecimal($tamountpaid); ?></strong></td>
                            <td style="text-align: center;cursor:default;"><i class="fa fa-trash-o"></i></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
			
			<?php if ($Owner || $Admin) { ?>
				<div class="form-group" style="display:none !important;">
					<?= lang("biller", "biller"); ?>
					<?php
					foreach ($billers as $biller) {
						$bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
					}
					echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $setting->default_biller), 'class="form-control" id="posbiller" required="required"');
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
                <?php if ($Owner || $Admin) { ?>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?= lang("date", "date"); ?>
                            <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control datetime" id="date" required="required"'); ?>
                        </div>
                    </div>
                <?php } ?>				
				<div class="col-md-6">
                    <?= lang("reference_no", "slref"); ?>
                    <div style="float:left;width:100%;">
                        <div class="form-group">
                            <div class="input-group">  
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $payment_ref),'class="form-control input-tip" id="slref"'); ?>
                                <input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= (isset($_POST['reference_no']) ? $_POST['reference_no'] : $payment_ref); ?>" />
								<input type="hidden" name="biller_id" id="biller_id" value="<?=$biller_id?>">
                                <div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
                                    <input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
                                </div>
                            </div>
                        </div>
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
                                        <?= lang("amount", "amount_1"); ?>
                                        <input name="amount-paid" type="text" id="amount_1"
                                               value="<?= $this->erp->formatMoney($total); ?>"
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
                                        <option value="other"><?= lang("other"); ?></option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="clearfix"></div>
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
                                <div class="col-md-3">
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
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input name="pcc_month" type="text" id="pcc_month_1" class="form-control"
                                               placeholder="<?= lang('month') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">

                                        <input name="pcc_year" type="text" id="pcc_year_1" class="form-control"
                                               placeholder="<?= lang('year') ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">

                                        <input name="pcc_ccv" type="text" id="pcc_cvv2_1" class="form-control"
                                               placeholder="<?= lang('cvv2') ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pcheque_1" style="display:none;">
                            <div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
                                <input name="cheque_no" type="text" id="cheque_no_1" class="form-control cheque_no"/>
                            </div>
                        </div>
						
						<div class="bank_account_1" id="bank_acc">
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
                </div>

            </div>

            <div class="form-group">
                <?= lang("attachment", "attachment") ?>
                <input id="attachment" type="file" name="userfile" data-show-upload="false" data-show-preview="false" class="form-control file">
            </div>

            <div class="form-group">
                <?= lang("note", "note"); ?>
                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_payment', lang('add_payment'), 'class="btn btn-primary" id="add_submit"'); ?>
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
        $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
        $(document).on('change', '.paid_by', function () {
            var p_val = $(this).val();
            __setItem('paid_by', p_val);
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
		
		$('#amount_1').keyup(function(){
			var amount = parseFloat($(this).val());
			var totalAmount = 0;
			$('.balance').each(function(){
				var paid = parseFloat($(this).attr('val'));
				totalAmount=totalAmount+paid;
				if(amount>0&&(amount-paid>0)||amount>0&&(amount-paid==0)){
					$(this).parent().find('.amount_paid_line').val(paid);
					amount= amount-paid;
				}else if(amount>0 && (amount-paid<0)){
					$(this).parent().find('.amount_paid_line').val(amount);
					amount = 0; 
				}else{
					$(this).parent().find('.amount_paid_line').val(0);
				}
				
			});
			var curAmount = parseFloat($(this).val());
			if(curAmount>totalAmount){
				$(this).val(totalAmount);
				$('#total_paid').text(formatDecimal(totalAmount));
			}else {
				if(curAmount) {
					$('#total_paid').text(formatDecimal(curAmount));
				}else {
					$('#total_paid').text(formatDecimal(0));
				}
			}
		});
		
    });
	function removeCommas(str) {
		while (str.search(",") >= 0) {
			str = (str + "").replace(',', '');
		}
		return Number(str);
	}
</script>
