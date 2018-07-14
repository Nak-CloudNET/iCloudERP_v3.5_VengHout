
<div class="modal-dialog" style="width:70%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('change_term'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("sales/changeLoanTerm/". $sale_id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			
			<div class="box">
				<div class="box-content">
					<div class="row">
						<div class="col-md-0">
							<div class="col-md-4">
								
								<div class="form-group">
									<label for="slcustomer"><?= lang('total_loan_amount') ?></label>
									<input name="total_loan_amount" type="text" id="total_loan_amount" value="<?= $this->erp->formatDecimal($loaned_amt->loan_amount); ?>" style="pointer-events:none"
										   class="form-control total_loan_amount"
										   placeholder="<?= lang('total_loan_amount') ?>"/>
								</div>
							</div>
							<div class="col-md-4">
								
								<div class="form-group">
									<label for="slcustomer"><?= lang('total_paid') ?></label>
									<input name="total_paid" type="text" id="total_paid" value="<?= (isset($loaned_amt->paid_amount)?$this->erp->formatDecimal($loaned_amt->paid_amount):0)?>" style="pointer-events:none"
										   class="form-control total_paid"
										   placeholder="<?= lang('total_paid') ?>"/>
								</div>
							</div>
							<div class="col-md-4">
								
								<div class="form-group">
									<label for="loan_balance"><?= lang('loan_balance') ?></label>
									<input name="loan_balance" type="text" id="balance"
										   class="form-control balance" required="required" value="<?=$this->erp->formatDecimal($loaned_amt->loan_amount-$loaned_amt->paid_amount);?>"
										   placeholder="<?= lang('loan_balance') ?>"/>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						
						<div class="col-md-0">

							<div class="col-md-4">
								<div class="form-group">
									<label for="slcustomer"><?= lang('rate_percentage') ?></label>
									<input name="depreciation_rate1" type="text" id="depreciation_rate_1"
										   class="form-control number_only depreciation_rate1" value="<?=$sale->interest_rate?>"
										   placeholder="<?= lang('rate_percentage') ?>"/>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="slcustomer"><?= lang('term') ?></label>
								
									<select name="depreciation_term" id="depreciation_term_1" class="form-control kb-pad" placeholder="<?= lang('term') ?>">
									<?php
									
										$opt ='<option value=""></option>';
										foreach($terms AS $term)
										{
											$opt.="<option ".($sale->term==$term->day?"selected":"")." value=".$term->day .">".$term->description ."</option>";
										}
										echo $opt;
										?>
									</select>
									<input type="hidden" id="current_date" class="current_date" class="current_date[]" value="<?php echo date('m/d/Y'); ?>" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="slcustomer"><?= lang('frequency') ?></label>
									<select name="frequency" id="frequency"
											class="form-control frequency"
											placeholder="<?= lang('frequency') ?>">
										<?php
										$opt ='<option value=""></option>';
										foreach($frequency AS $fre)
										{
											$opt.="<option ".($sale->frequency==$fre->day?"selected":"")." value=".$fre->day .">".$fre->description ."</option>";
										}
										echo $opt;
										?>
									</select>
								</div>
							</div>
						</div>
						
					</div>
				
					<div class="row">
						<div class="depreciation_1" >
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-4">
										<div class="form-group">
											<label for="slcustomer"><?= lang('payment_type') ?></label>
											<select name="depreciation_type" id="depreciation_type_1"
													class="form-control depreciation_type" required="required"
													placeholder="<?= lang('payment_type') ?>">
												<option value=""> &nbsp; </option>
												<option <?=($sale->depreciation_type==1?"selected":"");?> value="1"><?= lang("normal"); ?></option>
												<option <?=($sale->depreciation_type==2?"selected":"");?> value="2"><?= lang("custom"); ?></option>
												<option <?=($sale->depreciation_type==3?"selected":"");?> value="3"><?= lang("fixed"); ?></option>
												<option <?=($sale->depreciation_type==4?"selected":"");?> value="4"><?= lang("normal_fixed"); ?></option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
										<label for="slcustomer"><?= lang('principle_type') ?></label>
											<select name="principle_type" id="principle_type_1"
													class="form-control principle_type"
													placeholder="<?= lang('principle_type') ?>">
												<option value="none"> None </option>
												<?php foreach($principle as $data){ ?>
													<option value="<?=$data->id?>"><?= $data->name; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
										<label for="down_date"><?= lang('down_date') ?></label>
											 <?php echo form_input('down_date', date("d/m/Y",strtotime($sale->installment_date)), 'class="form-control date" id="down_date" required="required"'); ?>
										</div>
									</div>
									
								</div>
								
								<div class="col-md-12">
									
									<div class="col-md-4">
										<div class="form-group" id="print_" style="display:none">
											<button type="button" class="btn btn-primary col-md-12 print_depre" id="print_depre" style="margin-bottom:5px;"><i class="fa fa-print"> &nbsp; </i>
												<?= lang('print') ?>
											</button>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group" id="export_" style="display:none">
											<button type="button" class="btn btn-primary col-md-12 export_depre" id="export_depre" style="margin-bottom:5px;"><i class="fa fa-file-excel-o"> &nbsp; </i>
												<?= lang('export') ?>
											</button>
											<div style="clear:both; height:15px;"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="dep_tbl" style="display:none;">
									<table border="1" width="100%" class="table table-bordered table-condensed tbl_dep" id="tbl_dep1">
										<tbody>

										</tbody>
									</table>

								</div>
								<div class="dep_export" style="display:none;"></div>
							</div>
							<div class="form-group">
								<div class="dep_tbl" style="display:none;">
									<table border="1" width="100%" class="table table-bordered table-condensed tbl_dep" id="tbl_dep">
										<tbody>
									
										</tbody>
									</table>
									<table id="export_tbl" width="70%" style="display:none;">
									
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
        <div class="modal-footer">
			<input type="hidden" name="val" id="val" value="" />
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
	
	$("#priciple_loan").on("change",function(){
			var ds        = $(this).val();
			var total     = $("#grand_total").val()-0;
			var deposit   = $("#deposit").val()-0;
			var down_pay  = $("#down_payment").val()-0;
			
 			if (ds.indexOf("%") !== -1) {
				var pds = ds.split("%");
				if (!isNaN(pds[0])) {
					principal_loan = parseFloat(((total) * parseFloat(pds[0])) / 100);
				
				} else {
					principal_loan = parseFloat((total * ds) / 100);
				}
				
				$(this).val((principal_loan-(deposit+down_pay)).toFixed(2));
				
			} else {
				principal_loan = parseFloat(ds);
			}
			
			var prin_loan = $("#priciple_loan").val()-0;
			
			$("#loan_amount").val((total-(prin_loan+deposit+down_pay)).toFixed(2));
			
	});
	
	$('#depreciation_type_1, #depreciation_rate_1, #depreciation_term_1, #loan_amount, #frequency, #down_date').on('change',function() {
	$("#depreciation_term_1,#frequency,#depreciation_type_1").attr("disabled",false);
		
		var p_type        = $('#depreciation_type_1').val();
		var pr_type       = $('#principle_type_1').val();
		var rate          = $('#depreciation_rate_1').val();
		var term          = $('#depreciation_term_1').val();
		var frequency     = $("#frequency option:selected").val();
		var option        = $("#principle_type_1 option:selected").val();
		var total_amount  = $('#total_loan_amount').val()-0;
		var dateString    = $("#down_date").val(); // Oct 23
		var dateParts     = dateString.split("/");
		var dateObject    = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]); // month is 0-based
		var down_date     = dateObject;
		var down_pay      = $('#total_paid').val()-0;
		
		var loan_amount   = total_amount - down_pay;
			
		
		if(pr_type=='none') {
			depreciation(loan_amount,rate,term,frequency,p_type,total_amount,down_date);
		}else{
			$("#frequency,#depreciation_term_1").attr("disabled",true);
			principal(option,loan_amount,p_type,down_date,rate);
		}
		
	}).trigger("change");

	function depreciation(amount,rate,term_of_day,frequency,p_type,total_amount, start_date){
		
		var term = (term_of_day/frequency).toFixed(0);
		frequency = parseFloat(frequency);
		
		var d = new Date();
		if(p_type == ''){
			$('#print_').hide();
			$('#export_').hide();
			return false;
		}else{
			$('#print_').show();
			$('#export_').show();
			if(rate == '' || rate < 0) {
				if(term == '' || term <= 0) {
					$('.dep_tbl').hide();
					alert("Please choose Rate and Term again!");
					return false;
				}else{
					$('.dep_tbl').hide();
					alert("Please choose Rate again!"); 
					return false;
				}
			}else{
				if(term == '' || term <= 0) {
					$('.dep_tbl').hide();
					alert("Please choose Term again!"); 
					return false;
				}else{
					var tr = '';
					if(p_type == 1 || p_type == 3 || p_type == 4){
						tr += '<tr>';
						tr += '<th class="text-center"> <?= lang("Pmt No."); ?> </th>';
						tr += '<th class="text-center"> <?= lang("interest"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("principal"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("total_payment"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("balance"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("note"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("payment_date"); ?> </th>';
						tr += '</tr>';
					}else if(p_type == 2){
						tr += '<tr>';
						tr += '<th class="text-center"> <?= lang("period"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("rate"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("percentage"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("payment"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("total_payment"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("balance"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("note"); ?> </th>';
						tr += '<th class="text-center"> <?= lang("dateline"); ?> </th>';
						tr += '</tr>';
					}
					
					
					var total_loan	  	= (amount);
					var balance 	  	= Math.round(total_loan,2);
					var total_principle = 0;
					var total_payment 	= 0;
					var k=0;
					//Loan Priciple Calculate
					var a =0;
					//End
					
					if(p_type == 1){
						var principle = total_loan/term;
						var interest  = 0;
						      balance = (balance?balance:total_loan);
						var payment   = 0;
						
						for(i=1;i<=term;i++){
							if(i== 1){
								interest = (total_loan*(rate/100));
								var dateline = moment(start_date).add(k,'days').format('DD/MM/YYYY');
							}else{
								interest = balance *((rate/100));
								var dateline = moment(start_date).add(k,'days').format('DD/MM/YYYY');
							}
							balance -= principle;
							if(balance <= 0){
								balance = 0;
							}
							payment = principle + interest;
							tr += '<tr> <td class="text-center">'+ a +'<input type="hidden" name="no[]" id="no" class="no" value="'+ a +'" /></td> ';
							tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
							tr += '<td>'+ formatMoney(principle) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
							tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';
							tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ formatDecimal(balance) +'"/></td>';
							tr += '<td> <input name="note[]" class="note form-control" id="'+a+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+a+'" width="90%"/></td>';
							tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /> </td> </tr>';
							total_principle += principle;
							total_payment   += payment;
							k+= frequency;
							a++;
						}
						tr += '<tr> <td colspan="2"> <?= lang("total"); ?> </td>';
						tr += '<td>'+ formatMoney(total_principle) +'</td>';
						tr += '<td>'+ formatMoney(total_payment) +'</td>';
						tr += '<td colspan="3"> &nbsp; </td> </tr>';
					}else if(p_type == 2) {
						

						var interest = 0;
						var principle = Math.round(balance/term);
						var rate_amount = ((rate/100));
						var payment = ((total_loan * rate_amount)*((Math.pow((1+rate_amount),term))/(Math.pow((1+rate_amount),term)-1)));
						var k=0;
						
						for(i=1;i<=term;i++){
								
								if(i== 1){
									interest 	 = (balance*(rate/100)/frequency)*frequency;
									var dateline = moment(start_date).format('DD/MM/YYYY');
								}else{
									interest 	 = (balance*(rate/100)/frequency)*frequency;
									var dateline = moment(start_date).add(k,'days').format('DD/MM/YYYY');
								}
								percent   = (principle / (balance)) * 100;
								payment   = principle + interest;
								balance  -= principle;
								if(balance <= 0){
									balance = 0;
								}else if(i==term){
									principle   = principle+balance;
									balance 	= 0;
								}
								
								tr += '<tr> <td class="text-center">'+ a +'<input type="hidden" name="no[]" id="no" class="no" value="'+ a +'" /></td> ';
								tr += '<td><input type="text" name="rate[]" id="rate" class="rate" style="width:60px;" value="'+ formatDecimal(interest) +'"/><input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
								tr += '<td><input type="text" name="percentage[]" id="percentage" class="percentage" style="width:60px;" value="'+ percent.toFixed(4) +'"/><input type="hidden" name="percentage_[]" id="percentage_" class="percentage_" style="width:60px;" value="'+ percent +'"/></td>';
								tr += '<td><input type="text" name="pmt_principle[]" id="pmt_principle" class="pmt_principle" style="width:60px;" value="'+ formatDecimal(principle) +'" /><input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
								tr += '<td><input type="text" name="total_payment[]" id="total_payment" class="total_payment" style="width:60px;" value="'+ formatDecimal(payment) +'" readonly/><input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';
								tr += '<td><input type="text" name="amt_balance[]" id="amt_balance" class="amt_balance" style="width:60px;" value="'+ formatDecimal(balance) +'" readonly/><input type="hidden" name="balance[]" id="balance" class="balance" style="width:60px;" value="'+ formatDecimal(balance) +'"/></td>';
								tr += '<td> <input name="note[]" class="note form-control" id="'+a+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+a+'" width="90%"/></td>';
								tr += '<td><input type="text" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" size="6" /></td> </tr>';

							
							total_principle += principle;
							total_payment   += payment;
							k+= frequency;
							a++;
						}
						tr += '<tr> <td colspan="3"> <?= lang("Total"); ?> </td>';
						tr += '<td><input type="text" name="total_pay" id="total_pay" class="total_pay" style="width:60px;" value="'+ formatDecimal(total_principle) +'" readonly/></td>';
						tr += '<td><input type="text" name="total_amount" id="total_amount" class="total_amount" style="width:60px;" value="'+ formatDecimal(total_payment) +'" readonly/></td>';
						tr += '<td colspan="3"> &nbsp; </td> </tr>';
					}else if(p_type == 3) {
						var principle = 0;
						var interest = 0;
						var balance = (balance?balance:total_loan);
						var rate_amount = ((rate/100));
						var payment = ((total_loan * rate_amount)*((Math.pow((1+rate_amount),term))/(Math.pow((1+rate_amount),term)-1)));
						var k=0;
						var total_principle = 0;
						var total_payment = 0;
						for(i=1;i<=term;i++){
							if(i== 1){
								interest = (total_loan*(rate/100));
								var dateline = moment(start_date).format('DD/MM/YYYY');
							}else{
								interest = ( balance *(rate/100));
								var dateline = moment(start_date).add(k,'days').format('DD/MM/YYYY');
							}
							principle = payment - interest;
							balance -= principle;
							if(balance <= 0){
								balance = 0;
							}
							tr += '<tr> <td class="text-center">'+ a +'<input type="hidden" name="no[]" id="no" class="no" value="'+ a +'" /></td> ';
							tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
							tr += '<td>'+ formatMoney(principle) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ principle +'"/></td>';
							tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';								
							tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ formatDecimal(balance) +'"/></td>';
							tr += '<td> <input name="note[]" class="note form-control" id="'+a+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+a+'" width="90%"/></td>';
							tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /></td> </tr>';
							total_principle += principle;
							total_payment += payment;
							k+= frequency;
							a++;
						}
						tr += '<tr> <td colspan="2"> <?= lang("Total"); ?> </td>';
						tr += '<td>'+ formatMoney(total_principle) +'</td>';
						tr += '<td>'+ formatMoney(total_payment) +'</td>';
						tr += '<td colspan="3"> &nbsp; </td> </tr>';
					} else if(p_type == 4){
						var principle = total_loan/term;
						var interest = (total_loan * (rate/100));
						var balance = (balance?balance:total_loan);
						var payment = 0;
						var k=0;
						var total_principle = 0;
						var total_payment = 0;
						for(i=1;i<=term;i++){
							if(i== 1){
								var dateline = moment(start_date).format('DD/MM/YYYY');
							}else{
								var dateline = moment(start_date).add(k,'days').format('DD/MM/YYYY');
							}
							payment = principle + interest;
							
							balance -= principle;
							if(balance <= 0){
								balance = 0;
							}
							tr += '<tr> <td class="text-center">'+ a +'<input type="hidden" name="no[]" id="no" class="no" value="'+ a+'" /></td> ';
							tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ interest +'"/></td>';
							tr += '<td>'+ formatMoney(principle) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ principle +'"/></td>';
							tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ payment +'"/></td>';
							tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ balance +'"/></td>';
							tr += '<td> <input name="note[]" class="note form-control" id="'+a+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+a+'" width="90%"/></td>';
							tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /> </td> </tr>';
							total_principle += principle;
							total_payment += payment;
							k+= frequency;
							a++;
						}
						tr += '<tr> <td colspan="2"> <?= lang("total"); ?> </td>';
						tr += '<td>'+ formatMoney(total_principle) +'</td>';
						tr += '<td>'+ formatMoney(total_payment) +'</td>';
						tr += '<td colspan="3"> &nbsp; </td> </tr>';
					}
					$('.dep_tbl').show();
					$('#tbl_dep').html(tr);
					$("#loan1").html(tr);
			   }
			}
		}
	}
		
		$("#depreciation_rate_1").on('change', function(){
			$("#loan_rate").val($(this).val());
		});

		$("#depreciation_type_1").on('change', function(){
			$("#loan_type").val($(this).val());
		});

		$("#depreciation_term_1").on('change', function(){
			$("#loan_term").val($(this).val());
		});

		$("#tbl_dep .note").live('change', function(){
			var id = ($(this).attr('id'));
			var value = $(this).val();

			$('.note1_'+id+'').val(value);
		});

		$(document).on('keyup', '#tbl_dep .percentage', function () {
			var rate_all = $('#depreciation_rate_1').val()-0;
			var amount = 0;
			var payment = 0;
			var amount_payment = 0;
			var rate = 0;
			var balance = 0;
			var per = $(this).val()-0;
			var tr = $(this).parent().parent();
			if(per < 0 || per > 100) {
				alert("sorry you can not input the rate value less than zerro or bigger than 100");
				$(this).val('');
				$(this).focus();
				return false;
			}else {
				amount = tr.find('.balance_').val()-0;
				rate = tr.find('.rate_').val()-0;
				payment = amount *(per/100);
				amount_payment = rate + payment;
				balance = amount - payment;
				tr.find('.payment_amt').val(formatDecimal(payment));
				tr.find('.payment_').val(formatDecimal(payment));
				tr.find('.total_payment').val(formatDecimal(amount_payment));
				tr.find('.total_payment_').val(formatDecimal(amount_payment));
				tr.find('.balance').val(formatDecimal(balance));
				tr.find('.balance_').val(formatDecimal(balance));

				var total_percent = 0;
				$('#tbl_dep .percentage').each(function(){
					var parent_ = $(this).parent().parent();
					var per_tage_ = parent_.find('.percentage').val()-0;
					total_percent += per_tage_;
				});

				var j = 1;
				var i = 1;
				var balance = 0;
				var amount_percent = 0;
				var amount_pay = 0;
				var amount_total_payment = 0;
				$('#tbl_dep .percentage').each(function(){
					var parent = $(this).parent().parent();
					var per_tage = parent.find('.percentage').val()-0;
					if(per_tage == '' || per_tage == 0) {
						per_tage = 0;
					}
					amount_percent += per_tage;
					var rate = parent.find('.rate').val()-0;
					if(j == 1) {
						var str = $('#amount').val();
						var total_amount = str.replace(',', '');
						var loan_amount = total_amount;
						balance = loan_amount;
					}else {
						balance = parent.prev().find('.balance_').val()-0;
					}
					var new_rate = balance * (rate_all/100);
					var payment = balance * (per_tage/100);
					amount_pay += payment;
					var total_payment = payment + new_rate;
					amount_total_payment += total_payment;
					var balance = balance - payment;


					if(total_percent != amount_percent) {
						parent.find('.rate').val(formatDecimal(new_rate));
						parent.find('.rate_').val(formatDecimal(new_rate));
						parent.find('.payment_amt').val(formatDecimal(payment));
						parent.find('.payment_').val(formatDecimal(payment));
						parent.find('.total_payment').val(formatDecimal(total_payment));
						parent.find('.total_payment_').val(formatDecimal(total_payment));
						parent.find('.balance').val(formatDecimal(balance));
						parent.find('.balance_').val(formatDecimal(balance));
					}else{
						if(i == 1) {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.rate_').val(formatDecimal(new_rate));
							parent.find('.payment_amt').val(formatDecimal(payment));
							parent.find('.payment_').val(formatDecimal(payment));
							parent.find('.total_payment').val(formatDecimal(total_payment));
							parent.find('.total_payment_').val(formatDecimal(total_payment));
							parent.find('.balance').val(formatDecimal(balance));
							parent.find('.balance_').val(formatDecimal(balance));
						}else {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.rate_').val(formatDecimal(new_rate));
							parent.find('.payment_amt').val("");
							parent.find('.payment_').val(formatDecimal(payment));
							parent.find('.total_payment').val("");
							parent.find('.total_payment_').val(formatDecimal(total_payment));
							parent.find('.balance').val("");
							parent.find('.balance_').val(formatDecimal(balance));
						}
						i++;
					}
					j++;
				});
				$('.total_percen').val(formatDecimal(amount_percent));
				$('.total_pay').val(formatDecimal(amount_pay));
				$('.total_amount').val(formatDecimal(amount_total_payment));
			}
		});
		
				$(document).on('keyup', '#tbl_dep .percentage', function () {
			var rate_all = $('#depreciation_rate_1').val()-0;
			var amount = 0;
			var payment = 0;
			var amount_payment = 0;
			var rate = 0;
			var balance = 0;
			var per = $(this).val()-0;
			var tr = $(this).parent().parent();
			if(per < 0 || per > 100) {
				alert("sorry you can not input the rate value less than zerro or bigger than 100");
				$(this).val('');
				$(this).focus();
				return false;
			}else {
				amount = tr.find('.balance').val()-0;
				rate = tr.find('.interest_').val()-0;
				payment = amount *(per/100);
				amount_payment = rate + payment;
				balance = amount - payment;
				tr.find('.pmt_principle').val(formatDecimal(payment));
				tr.find('.principle').val(formatDecimal(payment));
				tr.find('.total_payment').val(formatDecimal(amount_payment));
				tr.find('.payment_amt').val(formatDecimal(amount_payment));
				tr.find('.amt_balance').val(formatDecimal(balance));
				tr.find('.balance').val(formatDecimal(balance));
				
				var total_percent = 0;
				$('#tbl_dep .percentage').each(function(){
					var parent_ = $(this).parent().parent();
					var per_tage_ = parent_.find('.percentage').val()-0;
					total_percent += per_tage_;
				});
				
				var j = 1;
				var i = 1;
				var balance = 0;
				var amount_percent = 0;
				var amount_pay = 0;
				var amount_total_payment = 0;
				$('#tbl_dep .percentage').each(function(){
					var parent = $(this).parent().parent();
					var per_tage = parent.find('.percentage').val()-0;
					if(per_tage == '' || per_tage == 0) {
						per_tage = 0;
					}
					amount_percent += per_tage;
					var rate = parent.find('.rate').val()-0;
					
					if(j == 1) {
						var total_amount = $('#loan_amount').val()-0;
						balance = total_amount;
					}else {
						balance = parent.prev().find('.balance').val()-0;
					}
					
	
					var new_rate = balance * (rate_all/100);
					var payment = balance * (per_tage/100);
					amount_pay += payment;
					var total_payment = payment + new_rate;
					amount_total_payment += total_payment;
					var balance = balance - payment;
					
					
					if(total_percent != amount_percent) {
						parent.find('.rate').val(formatDecimal(new_rate));
						parent.find('.interest').val(formatDecimal(new_rate));
						parent.find('.pmt_principle').val(formatDecimal(payment));
						parent.find('.principle').val(formatDecimal(payment));
						parent.find('.total_payment').val(formatDecimal(total_payment));
						parent.find('.payment_amt').val(formatDecimal(total_payment));
						parent.find('.amt_balance').val(formatDecimal(balance));
						parent.find('.balance').val(formatDecimal(balance));
					}else{
						if(i == 1) {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.interest').val(formatDecimal(new_rate));
							parent.find('.pmt_principle').val(formatDecimal(payment));
							parent.find('.principle').val(formatDecimal(payment));
							parent.find('.total_payment').val(formatDecimal(total_payment));
							parent.find('.payment_amt').val(formatDecimal(total_payment));
							parent.find('.amt_balance').val(formatDecimal(balance));
							parent.find('.balance').val(formatDecimal(balance));
						}else {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.interest').val(formatDecimal(new_rate));
							parent.find('.pmt_principle').val("");
							parent.find('.principle').val(formatDecimal(payment));
							parent.find('.total_payment').val("");
							parent.find('.payment_amt').val(formatDecimal(total_payment));
							parent.find('.amt_balance').val("");
							parent.find('.balance').val(formatDecimal(balance));
						}
						i++;
					}
					j++;
				});
				$('.total_percen').val(formatDecimal(amount_percent));
				$('.total_pay').val(formatDecimal(amount_pay));
				$('.total_amount').val(formatDecimal(amount_total_payment));
			}
		});
		
		
		$(document).on('keyup','#tbl_dep  .pmt_principle', function () {
			var rate_all = $('#depreciation_rate_1').val()-0;
			var amount = 0;
			var percent = 0;
			var amount_payment = 0;
			var rate = 0;
			var balance = 0;
			var payment = $(this).val()-0;
			var tr = $(this).parent().parent();
			if(payment < 0 ) {
				alert("sorry you can not input the rate value less than zerro or bigger than 100");
				$(this).val('');
				$(this).focus();
				return false;
			}else {
				amount = tr.find('.balance').val();
				rate = tr.find('.interest').val()-0;
				percent = (payment / amount) * 100;
				amount_payment = rate + payment;
				balance = amount - payment;
				tr.find('.percentage').val(formatDecimal(percent));
				tr.find('.percentage_').val(percent);
				tr.find('.total_payment').val(formatDecimal(amount_payment));
				tr.find('.payment_amt').val(amount_payment);
				tr.find('.amt_balance').val(formatDecimal(balance));
				tr.find('.balance').val(balance);
				
				var total_pay = 0;
				$('#tbl_dep .pmt_principle').each(function(){
					var parent = $(this).parent().parent();
					var pay_amt_ = parent.find('.pmt_principle').val()-0;
					total_pay += pay_amt_;
				});
				
				var j = 1;
				var i = 1;
				var balance = 0;
				var amount_pay = 0;
				var total_per = 0;
				var amount_total_payment  = 0;
				$('#tbl_dep .pmt_principle').each(function(){
					var parent = $(this).parent().parent();
					var pay_amt = parent.find('.pmt_principle').val()-0;
					if(pay_amt == '' || pay_amt < 0) {
						pay_amt = 0;
					}
					amount_pay += pay_amt;
					var rate = parent.find('.rate').val()-0;
					
					if(j == 1) {
						var total_amount = $('#loan_amount').val()-0;
						balance = total_amount;
					}else {
						balance = parent.prev().find('.balance').val()-0;
					}
					if(rate!=0)
					{
						var new_rate = balance * (rate_all/100);
					}else{
						var new_rate = 0;
					}
					
					var percen = (pay_amt / balance) * 100;
					total_per += percen;
					
					var total_payment = pay_amt + new_rate;

				
					
					amount_total_payment += total_payment;
					var balance = balance - pay_amt;
					
				
					if(total_pay != amount_pay) {
						parent.find('.rate').val(formatDecimal(new_rate));
						parent.find('.interest').val(formatDecimal(new_rate));
						parent.find('.pmt_principle').val(formatDecimal(payment));
						parent.find('.principle').val(formatDecimal(payment));
						parent.find('.total_payment').val(formatDecimal(total_payment));
						parent.find('.payment_amt').val(formatDecimal(total_payment));
						parent.find('.amt_balance').val(formatDecimal(balance));
						parent.find('.balance').val(formatDecimal(balance));
						
						
					}else{
						if(i == 1) {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.interest').val(formatDecimal(new_rate));
							parent.find('.pmt_principle').val(formatDecimal(payment));
							parent.find('.principle').val(formatDecimal(payment));
							parent.find('.total_payment').val(formatDecimal(total_payment));
							parent.find('.payment_amt').val(formatDecimal(total_payment));
							parent.find('.amt_balance').val(formatDecimal(balance));
							parent.find('.balance').val(formatDecimal(balance));
							
						}else {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.interest').val(formatDecimal(new_rate));
							parent.find('.pmt_principle').val("");
							parent.find('.principle').val(formatDecimal(payment));
							parent.find('.total_payment').val("");
							parent.find('.payment_amt').val(formatDecimal(total_payment));
							parent.find('.amt_balance').val("");
							parent.find('.balance').val(formatDecimal(balance));
						}
						i++;
					}
					j++;
				});
				$('.total_percen').val(formatDecimal(total_per));
				$('.total_pay').val(formatDecimal(amount_pay));
				$('.total_amount').val(formatDecimal(amount_total_payment));
			}
		});
	
		$(document).on('keyup','#tbl_dep  .rate', function () {
			var rate_all = $('#depreciation_rate_1').val()-0;
			var amount = 0;
			var percent = 0;
			var amount_payment = 0;
			var rate = 0;
			var balance = 0;
			var payment = $(this).val()-0;
			var tr = $(this).parent().parent();
			if(payment < 0 ) {
				alert("sorry you can not input the rate value less than zerro or bigger than 100");
				$(this).val('');
				$(this).focus();
				return false;
			}else {
				amount = tr.find('.balance').val();
				rate = tr.find('.interest').val()-0;
				percent = (payment / amount) * 100;
				amount_payment = rate + payment;
				balance = amount - payment;
				tr.find('.total_payment').val(formatDecimal(amount_payment));
				tr.find('.payment_amt').val(amount_payment);
				tr.find('.amt_balance').val(formatDecimal(balance));
				tr.find('.balance').val(balance);
			}
		});
		//==============================end loan=================================

	});
</script>
