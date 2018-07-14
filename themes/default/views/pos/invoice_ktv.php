<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Invoice&nbsp;<?= $invs->reference_no ?></title>
	<link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
	<link href="<?php echo $assets ?>styles/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $assets ?>styles/custome.css" rel="stylesheet">
</head>
<style>		
	.container {
		width: 29.7cm;
		margin: 20px auto;		
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
	}
	
	@media print {
		.customer_label {
			padding-left: 0 !important;
		}
		
		.invoice_label {
			padding-left: 0 !important;
		}
		#footer{			
			position:absolute !important;
   			bottom:0 !important;
   		
		}
	}
	.thead th {
		text-align: center !important;
	}
	
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border: 1px solid #000 !important;
	}
	
	.company_addr h3:first-child {
		font-family: Khmer OS Muol !important;		
	}
	
	.company_addr h3:nth-child(2) {
		margin-top:-2px !important;
		font-weight: bold;
	}
	
	.company_addr h3:last-child {
		margin-top:-2px !important;		
	}
	
	.company_addr p {
		margin-top:-10px !important;
		padding-left: 20px !important;
	}
	
	.inv h4:first-child {
		font-family: Khmer OS Muol !important;
	}
	
	.inv h4:last-child {
		margin-top:-5px !important;
	}

	button {
		border-radius: 0 !important;
	}
	
</style>
<?php 
	$datetime1 = new DateTime($inv->start_date);
	$datetime2 = new DateTime($inv->date);
	$total_time = $datetime1->diff($datetime2);
?>

<body>
	<br>
	<div class="container" style="width: 830px;margin: 0 auto;">
		<div class="col-xs-12" style="width:810px !important;">
			<div class="row" style="margin-top: 20px !important;">
				<button type="button" class="btn btn-xs btn-default no-print pull-right btn_print" style="margin-right:15px; cursor:pointer !important;" onclick="window.print();">
					<i class="fa fa-print"></i> <?= lang('print'); ?>
				</button>
				<div class="col-md-12 company_addr" style="margin-top: -35px !important">
					<center>
						<?php if(!empty($biller->cf1)) { ?>
							<h3><?= $biller->cf1 ?></h3>
						<?php }else { ?>
							
						<?php } ?>	

						<?php if(!empty($biller->company)) { ?>
							<h3><?= $biller->company ?></h3>
						<?php } ?>
						
						<?php if(!empty($biller->address)) { ?>
							<p style="margin-top:-10px !important;font-size: 18px !important;"><?= $biller->address; ?></p>
						<?php } ?>
						
						<?php if(!empty($biller->phone)) { ?>
							<p style="margin-top:-10px !important;font-size: 18px !important;">Tel :&nbsp;<?= $biller->phone; ?></p>
						<?php } ?>				
					</center>
				</div>				
			</div>
			<div class="row">
				<div class="col-sm-6 col-xs-6">
					<table style="margin-top:15px;">
						<tr>
							<td style="width: 2%; font-size: 22px !important; line-height:25px !important;">វិក្ក័យប័ត្រ  : </br> Receipt :</td>
							<td style="width: 10%;font-size: 30px !important;font-weight:bold;"><?= $inv->reference_no ?></td>
						</tr>
						<tr>
							<td style="width: 2%; height:5%px;">&nbsp;</td>
						</tr>
						<tr>
							<td style="width: 5%; font-size: 22px !important; line-height:25px !important;">ម៉ោងចូល  : </br> Time In :</td>
							<td style="width: 30%;font-size: 20px !important; line-height:25px !important;"><?= date('d/m/Y g:i:s A', strtotime($inv->start_date)); ?></td>
						</tr>
						<tr>
							<td style="width: 5%;">&nbsp;</td>
						</tr>
						<tr>
							<td style="width: 10%; font-size: 22px !important; line-height:25px !important;">ចំនួនម៉ោង  : </br> Total Time :</td>
							<td style="width: 30%;font-size: 20px !important; line-height:25px !important;"><?= $total_time->format('%H : %I : %S'); ?></td>
						</tr>
						<tr>
							<td style="width: 5%;">&nbsp;</td>
						</tr>
						<tr>
							<td style="width: 5%; font-size: 22px !important; line-height:25px !important;">បេឡាករ  : </br> Cashier :</td>
							<td style="width: 30%;font-size: 20px !important; line-height:25px !important;"><?= $inv->username ?></td>
						</tr>
					</table>
				</div>
				<div class="col-sm-6 col-xs-6" style="float:right;">
					<table style="margin-top:15px;">
						<tr>
							<td style="width: 5%; font-size: 22px !important; line-height:25px !important;">បន្ទប់លេខ : </br> Room No :</td>
							<td style="width: 30%;font-size: 30px !important;font-weight:bold; line-height:25px !important;"><?= $inv->suspend_note; ?></td>
						</tr>
						<tr>
							<td style="width: 5%;">&nbsp;</td>
						</tr>
						<tr>
							<td style="width: 5%; font-size: 22px !important; line-height:25px !important;">ម៉ោងចេញ  : </br> Time Out:</td>
							<td style="width: 35%;font-size: 20px !important; line-height:25px !important;"><?= date('d/m/Y g:i:s A', strtotime($inv->date)); ?></td>
						</tr>
						<tr>
							<td style="width: 5%;">&nbsp;</td>
						</tr>
						<tr>
							<td style="width: 5%; font-size: 22px !important; line-height:25px !important;">&nbsp;</br>&nbsp;</td>
							<td style="width: 30%;font-size: 20px !important; line-height:25px !important;"></td>
						</tr>
						<tr>
							<td style="width: 5%;">&nbsp;</td>
						</tr>
						<tr>
							<td style="width: 5%; font-size: 22px !important; line-height:25px !important;">កាលបរិច្ឆេត: </br> Date &nbsp;:</td>
							<td style="width: 30%;font-size: 20px !important; line-height:25px !important;"><?= date('d-M-Y', strtotime($inv->date)); ?></td>
						</tr>
					</table>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-sm-12 col-xs-12">
					<table class="table table-bordered">
						<tbody>
							<tr class="thead" style="font-size:20px !important; text-align:center;">
								<td>ល.រ<br/><?= lang('no') ?></td>
								<td colspan="3">មុខម្ហូប<br/><?= lang('item_name') ?></td>
								<td>ចំនួន<br/><?= lang('qty') ?></td>
								<td>តម្លៃ<br/><?= lang('price') ?></td>
								<td>សរុប<br/><?= lang('total') ?></td>
							</tr>
							<?php 
								$no = 1;
								foreach ($rows as $row) {
									$free = lang('free');
									$product_unit = '';
									$total = 0;
									
									if($row->variant){
										$product_unit = $row->variant;
									}else{
										$product_unit = $row->uname;
									}
									$product_name_setting;
									if($setting->show_code == 0) {
										$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
									}else {
										if($setting->separate_code == 0) {
											$product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
										}else {
											$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
										}
									}
							?>
								<tr style="font-size:20px !important;">
									<td style="width:5% !important; vertical-align: middle; text-align: center; line-height : 17px !important;"><?php echo $no ?></td>
									<td colspan="3" style="width:40% !important; vertical-align: middle;line-height : 9px !important;">
										<?=$row->product_name;?>
									</td>
									<td style="width:15% !important; vertical-align: middle; text-align: center;line-height : 17px !important;">
										<?=$this->erp->formatQuantity($row->quantity);?>
									</td>
									<td style="width:15% !important; vertical-align: middle; text-align: center;line-height :17px !important;">
										$ <?= $this->erp->formatMoney($row->real_unit_price); ?>
									</td>
									<td style="width:20% !important; vertical-align: middle; text-align: right;line-height : 17px !important;">$ <?= $this->erp->formatQuantity($row->subtotal);?>
									</td>
								</tr>

							<?php
							$no++;
							}
							?>								
							<tr style="font-size:20px !important;">
								<td colspan="2" style="width : 20%; border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important; line-height : 15px !important; vertical-align: middle;"></td>
								<td style="line-height : 15px !important;">សរុប</td>
								<td colspan="2" style="width : 20%; vertical-align: middle; line-height : 15px !important;"><strong>Sub Total</strong></td>
								<td colspan="2" style="line-height : 15px !important; text-align:right; vertical-align: middle;">$ <?= $this->erp->formatMoney($inv->total); ?></td>
							</tr>
							<tr style="font-size:20px !important;">
								<td colspan="2" style="width : 20%; border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important; line-height : 15px !important; vertical-align: middle;"></td>
								<td style="line-height : 15px !important;">បញ្ចុះតំលៃ</td>
								<td colspan="2" style="width : 20%; vertical-align: middle; line-height : 15px !important;"><strong>Discount %</strong></td>
								<td colspan="2" style="line-height : 15px !important; text-align:right; vertical-align: middle;"><?= $inv->order_discount_id ? $this->erp->formatNumber($inv->order_discount_id) . ' %' : 0.00 . ' %' ; ?></td>
							</tr>
							<tr style="font-size:20px !important;">
								<td colspan="2" style="width : 20%; border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important; line-height : 15px !important; vertical-align: middle;"></td>
								<td style="line-height : 15px !important;">សរុបជាដុល្លារ</td>
								<td colspan="2" style="width : 20%; vertical-align: middle; line-height : 15px !important;"><strong>Total in USD</strong></td>
								<td colspan="2" style="line-height : 15px !important; text-align:right; vertical-align: middle;">$ <?= $this->erp->formatMoney($inv->grand_total); ?></td>
							</tr>
							<tr style="font-size:20px !important;">
								<td colspan="2" style="width : 20%; border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important; line-height : 15px !important; vertical-align: middle;"></td>
								<td style="line-height : 15px !important;">សរុបជារៀល</td>
								<td colspan="2" style="width : 20%; vertical-align: middle; line-height : 15px !important;"><strong>Total in Riel</strong></td>
								<td colspan="2" style="line-height : 15px !important; text-align:right; vertical-align: middle;"><?= $this->erp->formatMoney($inv->grand_total * $inv->other_cur_paid_rate); ?></td>
							</tr>		
						</tbody>
					</table>
				</div>
			</div>
			<br/><br/><br/><br/><br/><br/><br/><br/>
			<div class="col-md-12" style="float:right; padding-right:0px !important;">				
				<p style="font-size: 18px !important; float:right;">សូមអរគុណ ! សូមអញ្ចើញមកម្តងទៀត  <br/> <span style=" padding-right:0px !important; margin-left: 28px;"> Thank You ! Please come again </span></p>				
			</div>
		</div>
	</div>
	<br>
	<div style="width: 821px;margin: 0 auto;">
		<a class="btn btn-warning no-print" href="<?= site_url('pos'); ?>">
        	<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
     	</a>
	</div>
	<br>
</body>
</html>
<script src="<?php echo $assets ?>js/jquery.js"></script>
<script>
	<?php if (!$pos_settings->java_applet) { ?>
			$(window).load(function () {
				//window.print();
				<?php
				if($Settings->auto_print){?>
					setTimeout('window.close()', 5000);
					document.location.href = "<?=base_url()?>pos"; 
				<?php }	?>
			});
    <?php } ?>
</script>
