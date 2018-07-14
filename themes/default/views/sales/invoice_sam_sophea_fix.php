<?php //$this->erp->print_arrays($discount['discount']) ?>
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
	body {
		font-size: 14px !important;
		font-family:khmer Os;
	}
		
	.container {
		width: 29.7cm;
		margin: 20px auto;
		/*padding: 10px;*/
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
	}
	
	@media print {
	
		.container {
			height: 27cm !important;
		}
		
		.customer_label {
			padding-left: 0 !important;
		}
		.container {
		height: 27cm !important;
		}
		.invoice_label {
			padding-left: 0 !important;
		}
		#footer{
			position:absolute !important;
   			bottom:0 !important;
		}
		.row table tr td {
			font-size: 10px !important;
		}
		
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
			background-color: #444 !important;
			color: #FFF !important;
		}
		
		.row .col-xs-7 table tr td, .col-sm-5 table tr td{
			font-size: 10px !important;
		}
		
		#note{
			max-width: 95% !important;
			margin: 0 auto !important;
			border-radius: 5px 5px 5px 5px !important;
			margin-left: 26px !important;
		}
		
		table th, td {
			font-size: 9px !important;
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
		//padding-left: 12% !important;
	}
	
	.company_addr h3:nth-child(2) {
		margin-top:-2px !important;
		//padding-left: 130px !important;
		font-size: 26px !important;
		font-weight: bold;
	}
	
	.company_addr h3:last-child {
		margin-top:-2px !important;
		//padding-left: 100px !important;
	}
	
	.company_addr p {
		font-size: 12px !important;
		margin-top:-10px !important;
		padding-left: 20px !important;
	}
	
	.inv h4:first-child {
		font-family: Khmer OS Muol !important;
		font-size: 14px !important;
	}
	
	.inv h4:last-child {
		margin-top:-5px !important;
		font-size: 20px !important;
	}

	button {
		border-radius: 0 !important;
	}
	
</style>
<body>
	<br>
	<div class="container" style="width: 830px;margin: 0 auto;">
		<div class="col-xs-12" style="width:810px !important;">
			<div class="row" style="margin-top: 5px !important;">
				<div class="col-sm-3 col-xs-3">
					<?php if(!empty($biller->logo)) { ?>
						<img src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" style="width: 165px; margin-left: 25px;" />
					<?php } ?>
				</div>
			
				<div class="col-sm-6 col-xs-6 company_addr" style="margin-top: -15px !important">
					<center>
						<?php if(!empty($biller->cf1)) { ?>
							<h3><?= $biller->cf1 ?></h3>
						<?php }else { ?>
							<h3>CloudNET Cambodia</h3>
						<?php } ?>
						<?php if(!empty($biller->address)) { ?>
							<p style="margin-top:-10px !important;font-size: 11px;">អាសយដ្ឋាន ៖ &nbsp;
								ភូមិបាក់ខែង​ សង្កាត់ព្រែកលាប ខ័ណ្ឌជ្រោយចង្វា រាជធានីភ្នំពេញ។
							</p>
						<?php } ?>
						
						<?php if(!empty($biller->phone)) { ?>
							<p style="margin-top:-10px !important;font-size: 11px;">ទូរស័ព្ទលេខ (Tel):&nbsp;
								093 608 632 / 092 65 28 45
							</p>
						<?php } ?>
						
						<?php if(!empty($biller->email)) { ?>
							<!--<p style="margin-top:-10px !important;font-size: 11px;">សារអេឡិចត្រូនិច (E-mail):&nbsp;<?= $biller->email; ?></p>-->
						<?php } ?>
					</center>
				</div>
				<div class="col-sm-3 col-xs-3">
					<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                		<i class="fa fa-print"></i> <?= lang('print'); ?>
            		</button>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12 inv" style="margin-top: -22px !important">
					<center>
						<h4 style="font-size: 16px !important;">វិក្កយបត្រ</h4>
						<br/>
						<!--<h4 style="font-size: 16px !important; margin-top:-10px !important; font-weight:bold">INVOICE</h4>-->
					</center>
				</div>
				<clearfix>
			</div>
			<div class="row">
				<div class="col-sm-7 col-xs-7">
					<table style="font-size:12px;margin-top:-20px;">
						<?php if(!empty($customer->company)) { ?>
						<tr>
							<td style="width: 5%; font-size:13px !important;">ឈ្មោះក្រុមហ៊ុន </td>
							<td style="width: 5%;">:</td>
							<td style="width: 30%;5%; font-size:13px !important;"><?= $customer->company ?></td>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->name_kh || $customer->name)) { ?>
						<tr>
							<td style="font-size:12px !important;">អតិថិជន</td>
							<td>:</td>
							<?php if(!empty($customer->name_kh)) { ?>
							<td><?= $customer->name_kh ?></td>
							<?php }else { ?>
							<td style="font-size:13px !important;"><?= $customer->name ?></td>
							<?php } ?>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->address_kh || $customer->address)) { ?>
						<tr>
							<td style="font-size:13px !important;">អាសយដ្ឋាន </td>
							<td>:</td>
							<?php if(!empty($customer->address_kh)) { ?>
							<td style="font-size:13px !important;"><?= $customer->address_kh?></td>
							<?php }else { ?>
							<td style="font-size:13px !important;"><?= $customer->address ?></td>
							<?php } ?>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->address_kh || $customer->address)) { ?>
						<tr>
							<td style="font-size:13px !important;">ទូរស័ព្ទលេខ (Tel)</td>
							<td>:</td>
							<td style="font-size:13px !important;"><?= $customer->phone ?></td>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->vat_no)) { ?>
						<tr>
							<td style="font-size:12px !important;">លេខអត្តសញ្ញាណកម្ម អតប </td>
							<td>:</td>
							<td style="font-size:12px !important;"><?= $customer->vat_no ?></td>
						</tr>
						<?php } ?>
					</table>
				</div>
				<div class="col-sm-5 col-xs-5">
					<table style="font-size: 12px;margin-top:-20px;">
						<tr>
							<td style="width: 20%;font-size:12px !important;">លេខរៀងបញ្ជារទិញ </td>
							<td style="width: 5%;">:</td>
							<td style="width: 30%;font-size:12px !important;"><?= $sale_order->reference_no ?></td>
						</tr>
						<tr>
							<td style="width: 20%;font-size:12px !important;">លេខរៀងវិក្កយបត្រ </td>
							<td style="width: 5%;">:</td>
							<td style="width: 30%;font-size:12px !important;"><?= $invs->reference_no ?></td>
						</tr>
						<tr>
							<td style="font-size:12px !important;">កាលបរិច្ឆេទ </td>
							<td>:</td>
							<td style="font-size:12px !important;"><?= $invs->date; ?></td>
						</tr>
						<tr>
							<td style="font-size:12px !important;">អ្នកលក់</td>
							<td>:</td>
							<td style="font-size:13px !important;"><?= $saleman->username; ?></td>
						</tr><tr>
							<td style="font-size:12px !important;">រយះពេលបង់ប្រាក់</td>
							<td>:</td>
							<td style="font-size:12px !important;"><?= $invs->due_date; ?></td>
							
						</tr>
					</table>
				</div>
			</div>
			
			<?php
				$cols = 6;
				if ($discount != 0) {
					$cols = 7;
				}
			?>
			<div class="row">
				<div class="col-sm-12 col-xs-12">
					<table class="table table-bordered">
						<tbody style="font-size: 11px;">
							<tr class="thead" style="background-color: #444 !important; color: #FFF !important;">
								<th>ល.រ<br /><?= strtoupper(lang('no')) ?></th>
								<th>កូដទំនិញ<br /><?= strtoupper(lang('code')) ?></th>
								<th style="width:200px !important;">បរិយាយមុខទំនិញ<br /><?= strtoupper(lang('description')) ?></th>
								<!--<th>ថ្ងៃផុតកំណត់<br /><?= strtoupper(lang('Expirey Date')) ?></th>-->
								<th>ខ្នាត<br /><?= strtoupper(lang('unit')) ?></th>
								<th>ចំនួន<br /><?= strtoupper(lang('qty')) ?></th>
								<th style="width:75px !important;">តម្លៃ<br /><?= strtoupper(lang('unit_price')) ?></th>
								
								<?php if ($Settings->product_discount) { ?>
									<!--<th>បញ្ចុះតម្លៃ<br /><?= strtoupper(lang('discount')) ?></th>-->
								<?php } ?>
								<?php if ($Settings->tax1) { ?>
									<!--<th>ពន្ធទំនិញ<br /><?= strtoupper(lang('tax')) ?></th>-->
								<?php } ?>
								<th>តម្លៃសរុបតាមមុខទំនិញ<br /><?= strtoupper(lang('subtotal')) ?></th>
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
								<tr>
									<td style="vertical-align: middle; text-align: center;font-size:12px !important;"><?php echo $no ?></td>
									<td style="vertical-align: middle;font-size:12px !important;">
										<?=$row->product_code;?>
									</td>
									<td style="vertical-align: middle; font-size:12px !important;">
										<?=$row->product_name;?>
									</td>
									<!--
									<td style="vertical-align: middle;font-size:12px !important;">
										<?=$row->expiry;?>
									</td>
									-->
									<td style="vertical-align: middle; text-align: center;font-size:12px !important;">
										<?= $product_unit ?>
									</td>
									<td style="vertical-align: middle; text-align: center;font-size:12px !important;">
										<?=$this->erp->formatQuantity($row->quantity);?>
									</td>
									<td style="vertical-align: middle; text-align: right;font-size:12px !important;">
										$<?= $this->erp->formatMoney($row->real_unit_price); ?>
									</td>
									<?php if ($row->item_discount) {?>
										<!--
										<td style="vertical-align: middle; text-align: center">
											<?=($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') .$this->erp->formatMoney.'$'.($row->item_discount);?>
										</td>
										-->
									<?php } ?>
									<?php if ($row->item_tax) {?>
										<!--
										<td style="vertical-align: middle; text-align: center;font-size:12px !important;">
											$<?=$this->erp->formatMoney($row->item_tax);?>
										</td>
										-->
									<?php } ?>
									<td style="text-align:right;vertical-align:middle;font-size:12px !important;"><?= $row->subtotal !=0? '$' .$this->erp->formatMoney($row->subtotal):$free;$total += $row->subtotal;
										?>
									</td>
								</tr>

							<?php
							$no++;
							}
							?>
							<?php
								if($no<21){
									$k=21 - $no;
									for($j=1;$j<=$k;$j++){
										if($discount != 0) {
											echo  '<tr>
													<td height="34px" class="text-center">'.$no.'</td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													
													
													
												</tr>';
										}else {
											echo  '<tr>
													<td height="34px" class="text-center">'.$no.'</td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													
												</tr>';
										}
										$no++;
									}
								}
							?>
							<?php
								$row = 3;
								$col =2;
								if ($discount != 0) {
									$col =2;
								}
								if ($invs->grand_total != $invs->total) {
									$row++;
								}
								if ($invs->order_discount != 0) {
									$row++;
									$col =2;
								}
								if ($invs->shipping != 0) {
									$row++;
									$col =2;
								}
								if ($invs->order_tax != 0) {
									$row++;
									$col =2;
								}
								if($invs->paid != 0 && $invs->deposit != 0) {
									$row += 2;
								}elseif ($invs->paid != 0 && $invs->deposit == 0) {
									$row += 2;
								}elseif ($invs->paid == 0 && $invs->deposit != 0) {
									$row += 2;
								}
							?>
										
							<?php if ($invs->grand_total != $invs->total) { ?>
							<tr>
								<td rowspan = "<?= $row; ?>" colspan="4" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important;">
									<?php if (!empty($invs->invoice_footer)) { ?>
										<p style="font-size:14px !important;"><strong><u>Note:</u></strong></p>
										<p style="margin-top:-5px !important; line-height: 2"><?= $invs->invoice_footer ?></p>
									<?php } ?>
								</td>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">សរុប​ / <?= strtoupper(lang('total')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?=$this->erp->formatMoney($invs->total); ?></td>
							</tr>
							<?php } ?>
										
							<?php if ($invs->order_discount != 0) : ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">បញ្ចុះតម្លៃលើការបញ្ជាទិញ / <?= strtoupper(lang('order_discount')).'(' .$invs->order_discount_id.')'  ?></td>
								<td align="right">$<?php echo $this->erp->formatQuantity($invs->order_discount); ?></td>
							</tr>
							<?php endif; ?>
							
							<?php if ($invs->shipping != 0) : ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">ដឹកជញ្ជូន / <?= strtoupper(lang('shipping')) ?></td>
								<td align="right">$<?php echo $this->erp->formatQuantity($invs->shipping); ?></td>
							</tr>
							<?php endif; ?>
							
							<?php if ($invs->order_tax != 0) : ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">ពន្ធអាករ / <?= strtoupper(lang('order_tax')) ?></td>
								<td align="right">$<?= $this->erp->formatQuantity($invs->order_tax); ?></td>
							</tr>
							<?php endif; ?>
							
							<tr>
								<?php if ($invs->grand_total == $invs->total) { ?>
								<td rowspan="<?= $row; ?>" colspan="4" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important;">
									<?php if (!empty($invs->invoice_footer)) { ?>
										<p><strong><u>Note:</u></strong></p>
										<p><?= $invs->invoice_footer ?></p>
									<?php } ?>
								</td>
								<?php } ?>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">តម្លៃសរុប/ <?= strtoupper(lang('total_amount')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?= $this->erp->formatMoney($invs->grand_total); ?></td>
							</tr>
							<?php if($invs->paid != 0 || $invs->deposit != 0){ ?>
							<?php if($invs->deposit != 0) { ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">បានកក់ / <?= strtoupper(lang('deposit')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?php echo $this->erp->formatMoney($invs->deposit); ?></td>
							</tr>
							<?php } ?>
							<?php if($invs->paid != 0) { ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">បានបង់ / <?= strtoupper(lang('paid')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?php echo $this->erp->formatMoney($invs->paid-$invs->deposit); ?></td>
							</tr>
							<?php } ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">នៅខ្វះ / <?= strtoupper(lang('balance')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?= $this->erp->formatMoney($invs->grand_total - (($invs->paid-$invs->deposit) + $invs->deposit)); ?></td>
							</tr>
						<?php } ?>
							
						</tbody>
						
					</table>
				</div>
			</div>
			<?php if($invs->note){ ?>
			<div style="border-radius: 5px 5px 5px 5px;border:1px solid black;font-size: 10px !important;height: auto; margin-bottom:70px" id="note" class="col-md-12 col-xs-12">
				<p style="margin-left: 10px;margin-top:10px;"><?php echo strip_tags($invs->note); ?></p>
			</div>
			<?php } ?>
			
		</div>	
		 
		<div id="footer" class="row">
			<div class="col-lg-4 col-sm-4 col-xs-4">
				<hr style="margin:0; border:1px solid #000;">
				<center>
					<p style="font-size: 12px !important;">ហត្ថលេខា និងឈ្មោះអ្នកទិញ</p>
				</center>
			</div>
			<div class="col-lg-4 col-sm-4 col-xs-4">
				<hr style="margin:0; border:1px solid #000;">
				<center>
					<p style="font-size: 12px !important;">ហត្ថលេខា និងឈ្មោះអ្នកដឹកជញ្ជូន</p>
				</center>
			</div>
			
			<div class="col-lg-4 col-sm-4 col-xs-4">
				<hr style="margin:0; border:1px solid #000;">
				<center>
					<p style="font-size: 12px !important;">ហត្ថលេខា និងឈ្មោះអ្នកលក់</p>
				</center>
			</div>
		</div>
	</div>
	<div style="width: 821px;margin: 0 auto; margin-top:20px; margin-bottom:20px">
		<a class="btn btn-warning no-print" href="<?= site_url('sales'); ?>" style="border-radius:0" >
        	<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
     	</a>
	</div>
</body>
</html>