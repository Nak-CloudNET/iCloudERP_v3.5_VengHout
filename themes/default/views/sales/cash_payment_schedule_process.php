
 <?php
	//$sale->total = $this->erp->convertCurrency($sale_item->currency_code, $setting->default_currency, $sale->total);
	//$this->erp->print_arrays($services);
?>
 <style type="text/css">
    .container {
        width: 800px;
        margin-left: auto;
        margin-right: auto;
	}	
	.t_c{text-align:center;}
	.t_r{text-align:right;}
    @media print
	{    
		.no-print, .no-print *
		{
			display: none !important;
		}
		
	}
	.kh_m{
		font-family: "Khmer OS Muol";
	}
	.b_top{
		border-top:1px solid black;
		margin-bottom: 20px;
		max-width: 100%;
		width: 100%;
		}
	.b_bottom{border-bottom:1px solid black}
	.b_left{border-left:1px solid black;}
	.b_right{border-right:1px solid black;}
	.text-bold td{font-weight:bold;}
	.p_l_r td{padding-left:5px;padding-right:5px;}
	.top_info tr td{
		height:25px;
	}
	.color_blue{color:#3366cc;}
	.color_blue{color:#3366cc;}
	#logo img{
		width:110px;
	}
	.table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td{
		border:none;
		border: 1px ;
		padding:4px;
	}
	
	
</style>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('payment_schedule'); ?></h4>
        </div>
        <div class="modal-body">
			<div class="row">
				<div class="container">
						<table width="100%" style="line-height:31px">
							<tr>
								<input type="hidden" name="customer_id" class="customer_id" id="customer_id" value="<?=$inv->customer_id?>">
								<input type="hidden" name="sale_id" class="sale_id" id="sale_id" value="<?=$inv->id?>">
								<td width="25%" style="font-family:'Khmer OS Muol Light'; font-size:14px;"> <?= lang('អតិថិជន / Customer');?> </td>
								
								<td width="50%">: <?= $customer->name ? $customer->name : $customer->company; ?> </td>
								<td width="5px" rowspan="2"> </td>
								<td width="10%" rowspan="2"> <?= lang('លេខវិក្ក័យបត្រ <br/> Invoice No');?> </td>
								<td width="15%" rowspan="2" style="padding-left:0px;">: <?= $inv->reference_no; ?> </td>
							</tr>
							<tr>
								<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('ឈ្មោះ​ក្រុមហ៊ុន ឬ អតិថិជន <br/> Company name / Customer');?> </td>
								<td width="50%">: <?= $customer->company ? $customer->company : $customer->name; ?> </td>
							</tr>
							<tr>
								<td width="25%" style="font-family:'Khmer OS Muol Light'; font-size:14px;"> <?= lang('ទូរស័ព្ទ​លេខ / Telephone No');?> </td>
								<td width="50%">: <?= $customer->phone; ?> </td>
								<td width="5px" rowspan="2"> </td>
								<td width="10%" rowspan="2"> <?= lang('កាលបរិច្ឆេទ <br/> Date');?> </td>
								<td width="15%" rowspan="2" style="padding-left:0px;">: <?= $this->erp->hrld($inv->date); ?> </td>
							</tr>
							<tr>
								<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('លេខអត្តសញ្ញាណកម្ម អតប  (VATTIN)');?></td>
								<td width="50%">: <?= $customer->vat_no; ?></td>
							</tr>
						</table>
						
						<table style="font-size:11px;border-collapse:collapse;width:100%;" class="schedule">
							<tr class="p_l_r" style="background-color:#009900;color:white;">
							<?php 
								echo '<td class="t_c" style="width: 5%;text-align:-moz-center !important" ><div  style="background-color: white; width: 21px; margin-left: 0;"><input type="checkbox" name="all_check" id="all_check"></div></td>' ;
							?>				
								<td  class="t_c" style="width: 5%;"> <?= lang("no") ?> </td>
								<td  class="t_c" style="width: 10%;"><?= lang("intallment_date") ?></td>
								<td  class="t_c" style="width:  10%;"><?= lang("principle_paid") ?></td>
								<td  class="t_c" style="width:  10%;"><?= lang("interest_paid") ?></td>
								<td  class="t_c" style="width:  10%;"><?= lang("total_intallment") ?></td>
								<td  class="t_c" style="width:  10%;"><?= lang("principle_balance") ?></td>
							<!--<td  class="t_c" style="padding: 5px; width:  15%;"><?= lang("action") ?></td>-->
							</tr>
							
					<?php
							
							$total_principle = 0;
							$total_interest = 0;
							$total_payment = 0;
							$total_alls = 0 ;
							$total_haft = 0 ;
							$total_insurence = 0;
							$total_pay = 0;
							$countrows = count($countloans);
							$countrow  = count($countloans) /2;
							$counter = 1;
							
							if(array($loan)) {
								foreach($loan as $pt){
									
										$princ           = $this->erp->formatMoney($pt->principle);
										$interest        = $this->erp->formatMoney($pt->interest);									
										$overdue_amt     = (($pt->paid_amount > 0)? $pt->overdue_amount : 0);
										$payment         = $pt->payment + $overdue_amt;
										$paid            = $pt->paid_amount? $pt->paid_amount : 0;
										
										$paid_amount     = $paid + (($pt->paid_amount > 0)? $overdue_amt : 0);
										$balance         = $payment - $paid_amount;
										$balance_moeny   = $this->erp->formatMoney($pt->balance);
										
										$Principles		 = $this->erp->formatMoney($pt->payment-$pt->interest);
										$interests 		 = $this->erp->formatMoney($pt->interest);
										
									echo '<tr class="row-data" '.(($pt->paid_amount > 0)? 'style="background-color:#B4D8E8;pointer-events:none;" disabled="disabled"':'').'>
										
										<td class="t_c" style="width: 5%;" > <input type="hidden" class="paid" value="'.$pt->paid_amount .'" /><input type="checkbox" name="ch_check[]" class="ch_check" value="'.(($pt->paid_amount == 0)? $pt->period:'').'" '.(($pt->paid_amount > 0)? 'checked':'') .'></td>
										<td class="t_c" style="padding-left:5px; padding-right: 5px; height: 25px;">'. $pt->period .'</td>
										<td class="t_c" style="padding-left:5px; padding-right:5px;">'. $this->erp->hrsd($pt->dateline) .'</td>
										<td class="t_r" style="padding-left:5px; padding-right:5px;">'. $Principles .'</td>
										<td class="t_r" style="padding-left:5px; padding-right:5px;">'. $interests .'</td>';
										$balances = (($pt->balance > 0)? $pt->balance : 0);
										$balances = str_replace(',', '', $this->erp->formatMoney($balances));
										$principle_amt = str_replace(',', '', $Principles);
										$loan_balance = $balances + $principle_amt;
										$haft_paid = 0;
										$insurences_paid = 0;
										$all_paid = 0;
									
										$Principles_amount = str_replace(',', '', $Principles);
										$interests_amount = str_replace(',', '', $interests);
										if($pt->period >= 1 && $pt->period <= $countrow){
											$payment = $Principles_amount + $interests_amount + $all_paid + $haft_paid + $insurences_paid;
										}else{
											$payment = $Principles_amount + $interests_amount + $all_paid;
										}
										$balances = (($pt->balance > 0)? $pt->balance : 0);
									echo '<td class="t_r" style="padding-left:5px;padding-right:5px;">'. $this->erp->formatMoney($payment) .'</td>
										  <td class="t_r" style="padding-left:5px;padding-right:5px;">'. $this->erp->formatMoney($balances) .'</td>
										 ';	
										
								}
							}
						?>	
							
							
							
						</table>
				</div>
			</div>
        </div>
        
		<div class="buttons">
			<div class="btn-group btn-group-justified no-print">
				<?php if ($this->Owner || $this->Admin || $this->permission['payment-add']) { ?> 
					<div class="btn-group">
						<a href="#" data-toggle="modal" data-target="#myModal2" class="add_payment tip btn btn-primary" id="add_payment" title="<?= lang('add_payment') ?>">
							<i class="fa fa-money"></i>
							<span class="hidden-sm hidden-xs"><?= lang('add_payment') ?></span>
						</a>
					</div>
				<?php } ?>
				<div class="btn-group">
					<a href="#" data-toggle="modal" data-target="#myModal2" class="change_date tip btn btn-primary" title="<?= lang('change_date') ?>">
						<i class="fa fa-edit"></i>
						<span class="hidden-sm hidden-xs"><?= lang('change_date') ?></span>
					</a>
				</div>
				<div class="btn-group">
					<a href="#" data-toggle="modal" data-target="#myModal2" class="change_term tip btn btn-primary" title="<?= lang('change_term') ?>">
						<i class="fa fa-edit"></i>
						<span class="hidden-sm hidden-xs"><?= lang('change_term') ?></span>
					</a>
                </div>
				<div class="btn-group">
					<a href="#" data-toggle="modal" data-target="#myModal2" class="pdf tip btn btn-primary" id="pdf" title="<?= lang('add_payment') ?>">
						<i class="fa fa-money"></i>
						<span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
					</a>
				</div>
				<div class="btn-group">
					<a href="#" data-toggle="modal" data-target="#myModal2" class="excel tip btn btn-primary" id="excel" title="<?= lang('add_payment') ?>">
						<i class="fa fa-money"></i>
						<span class="hidden-sm hidden-xs"><?= lang('excel') ?></span>
					</a>
				</div>
				<div class="btn-group">
					<a class="tip btn btn-warning" title="<?= lang('print') ?>" onclick="window.print();">
						<i class="fa fa-print"></i>
						<span class="hidden-sm hidden-xs"><?= lang('print') ?></span>
					</a>
				</div>
			</div>
        </div>
    </div>
</div>
<?= isset($modal_js) ?$modal_js  : ('') ?>
<script type="text/javascript">

	$(document).ready(function() {
		$('#all_check').on('ifChanged', function(){
			if($(this).is(':checked')) {
				$('.ch_check').each(function() {
					$(this).iCheck('check');
				});
			}else{
				$('.ch_check').each(function() {
					$(this).iCheck('uncheck');
				});
			}
		});		
		$('#add_payment').on('click', function() {
			var total_payment = 0;
			var id            = '';
			var paid_amount   = '';
			var principle     = '';
			var interest      = '';
			if($(".schedule .ch_check:checked").length > 0){
					var sale_id = <?= $sale_id ?>;
				$(".schedule .ch_check:checked").each(function(){
					
				    var parent = $(this).parent().parent().parent();					
					
					if(parent.find(".paid").val()==0)
					{
						id += $(this).val() +'_';
						total_payment += parseFloat((parent.children("td:nth-child(4)").html()).replace(',', ''));
						paid_amount   += parseFloat((parent.children("td:nth-child(4)").html()).replace(',', '')) +'_';
						principle     += parseFloat((parent.children("td:nth-child(3)").html()).replace(',', '')) +'_';
						interest      += parseFloat((parent.children("td:nth-child(5)").html()).replace(',', ''));
					}
				});
			
				if(id){
					$(this).attr('href', "<?= site_url('sales/add_payment_loans') ?>/"+total_payment+"/"+id+"/"+paid_amount+"/"+principle+"/"+interest+"/"+sale_id);
				}else{
					alert("Please check..");
					return false;
				}
			}else {
				alert("Please check..");
				return false;
			}
		});
		
		$(".change_date").on('click',function(){
			var id = '';
			if($(".schedule .ch_check:checked").length > 0){
		
				$(".schedule .ch_check:checked").each(function(){				
					var parent = $(this).parent().parent().parent().parent();
					id += $(this).val() +'_';
				});
				$(this).attr('href', "<?= site_url('sales/changePaymentDate') ?>/"+id);
			}else {
				alert("Please check..");
				return false;
			}
			
		});
		
		$(".change_term").on('click',function(){
				var id = <?= $sale_id; ?>;
				$(this).attr('href', "<?= site_url('sales/changeLoanTerm') ?>/"+id);
		});
		
	});


</script>
