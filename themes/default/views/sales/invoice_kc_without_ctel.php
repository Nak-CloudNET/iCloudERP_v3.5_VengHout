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
	}

	@media print {
		body {
			font-size: 11px !important;
		}
		.container {
			height: 21cm !important;
			margin-top: 0px !important;
		}
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
		.row table tr td {
			font-size: 12px !important;
		}

		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
			background-color: #444 !important;
		}

		.row .col-xs-7 table tr td, .col-sm-5 table tr td{
			font-size: 12px !important;
		}
		#note{
				max-width: 95% !important;
				margin: 0 auto !important;
				border-radius: 5px 5px 5px 5px !important;
				margin-left: 26px !important;
		}
	}
	.thead th {
		text-align: center !important;
	}
	
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border: 1px solid #000 !important;
	}
	
	.company_addr h3 {
		font-family: Khmer Mool1 !important;
	}

	.company_addr h4 {
		font-weight: bold;
		font-family: Times New Roman !important;
	}
	
	.company_addr p {
		font-size: 12px !important;
		margin-top:-10px !important;
		padding-left: 20px !important;
	}
	
	.inv h4:first-child {
		font-family: Khmer Mool1 !important;
		font-size: 16px !important;
	}
	
	.inv h4:last-child {
		margin-top:5px !important;
		font-size: 14px !important;
		font-weight: bold;
		font-family: Times New Roman !important;
	}

	button {
		border-radius: 0 !important;
	}
	
</style>
<body>
	<br>
	<div class="container" style="width: 821px;margin: 0 auto;">
		<div class="col-sm-12 col-xs-12" style="width: 794px;">
			<div class="row" style="margin-top: 20px;">
		
			<div class="col-sm-3 col-xs-3">
				<?php if(!empty($biller->logo)) { ?>
					<img src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" style="width: 165px; margin-left: 25px;" />
				<?php } ?>
			</div>
			
			<div class="col-sm-6 col-xs-6 company_addr" style="margin-top: -15px !important">
				<center>
					<h3><?= $biller->name; ?></h3>
					<h4><?= $biller->company; ?></h4>
				
					<?php if(!empty($biller->vat_no)) { ?>
						<p style="font-size: 11px;">លេខអត្តសញ្ញាណកម្ម អតប (VAT No):&nbsp;<?= $biller->vat_no; ?></p>
					<?php } ?>
					
					<?php if(!empty($biller->address)) { ?>
						<p style="margin-top:-10px !important;font-size: 11px;">អាសយដ្ឋាន ៖ &nbsp;<?= $biller->address; ?></p>
					<?php } ?>
					
					<?php if(!empty($biller->phone)) { ?>
						<p style="margin-top:-10px !important;font-size: 11px;">ទូរស័ព្ទលេខ (Tel):&nbsp;<?= $biller->phone; ?></p>
					<?php } ?>
					
					<?php if(!empty($biller->email)) { ?>
						<p style="margin-top:-10px !important;font-size: 11px;">សារអេឡិចត្រូនិច (E-mail):&nbsp;<?= $biller->email; ?></p>
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
				<div class="col-sm-12 col-xs-12 inv" style="margin-top: -10px !important">
					<center>
						<h4>វិក្កយបត្រ</h4>
						<h4 style="margin-top:-10px !important;">INVOICE</h4>
					</center>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-7 col-xs-7" style="margin-top: -20px !important">
					<table style="font-size: 12px;">
						<?php if(!empty($customer->company)) { ?>
						<tr>
							<td style="width: 5%;">ឈ្មោះក្រុមហ៊ុន</td>
							<td style="width: 5%;">:</td>
							<td style="width: 30%;"><?= $customer->company ?></td>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->name_kh || $customer->name)) { ?>
						<tr>
							<td>អតិថិជន</td>
							<td>:</td>
							<?php if(!empty($customer->name_kh)) { ?>
							<td>&nbsp;<?= strstr($customer->name_kh, '@', true) ?></td>
							<?php }else { ?>
							<td>&nbsp;<?= strstr($customer->name, '@', true) ?></td>
							<?php } ?>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->address_kh || $customer->address)) { ?>
						<tr>
							<td>អាសយដ្ឋាន</td>
							<td>:</td>
							<?php if(!empty($customer->address_kh)) { ?>
							<td>&nbsp;<?= $customer->address_kh?></td>
							<?php }else { ?>
							<td>&nbsp;<?= $customer->address ?></td>
							<?php } ?>
						</tr>
						<?php } ?>
					</table>
				</div>
				<div class="col-sm-5 col-xs-5" style="margin-top: -20px !important">
					<table style="font-size: 12px;">
						<tr>
							<td style="width: 25%;">លេខរៀងវិក្កយបត្រ</td>
							<td style="width: 5%;">:</td>
							<td style="width: 30%;"><span style="font-size: 14px; font-weight: bold"><?= $invs->reference_no ?></span></td>
						</tr>
						<tr>
							<td>កាលបរិច្ឆេទ</td>
							<td>:</td>
							<td><?= $this->erp->hrld($invs->date); ?></td>
						</tr>
						<tr>
							<td>គណនីវីង:</td>
							<td>:</td>
							<td>018 99 777</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12" style="margin-top: 10px">
					<table style="width: 100%" cellspacing="10">
						<tbody style="border:1px solid #000; border-radius: 5px; padding: 5px;">
							<tr>
								<td>Tel</td>
								<td>: 077 55 55 88 (ផ្នែកលក់)</td>
								<td>Tel</td>
								<td>: 077 39 39 39 (ផ្នែកលក់)</td>
								<td>Tel</td>
								<td>: 077 37 38 39 (ផ្នែកលក់)</td>
							</tr>
							<tr>
								<td></td>
								<td>: 089 32 68 68 (ផ្នែកលក់)</td>
								<td></td>
								<td>: 092 91 26 27 (ផ្នែកលក់)</td>
								<td></td>
								<td>: 088 33 22 888 (គណនេយ្យ)</td>
							</tr>
							<tr>
								<td></td>
								<td>: 016 87 377 55 (ជាងបច្ចេកទេស)</td>
								<td></td>
								<td>: 098 87 37 55 (ផ្នែកឃ្លាំង)</td>
								<td></td>
								<td>: 023 639 6390</td>
							</tr>
						</tbody>
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
					<table class="table table-bordered" style="margin-top: 10px;">
						<tbody style="font-size: 11px;">
							<tr class="thead" style="background-color: #333 !important; color: #FFF !important;">
								<th>ល.រ<br /><?= strtoupper(lang('no')) ?></th>
								<th>លេខកូដទំនិញ<br /><?= strtoupper(lang('product_code')) ?></th>
								<th>បរិយាយមុខទំនិញ<br /><?= strtoupper(lang('description')) ?></th>
								<th>ចំនួន<br /><?= strtoupper(lang('qty')) ?></th>
								<th>តម្លៃ<br /><?= strtoupper(lang('unit_price')) ?></th>
								
								<?php if ($Settings->product_discount) { ?>
									<th>បញ្ចុះតម្លៃ<br /><?= strtoupper(lang('discount')) ?></th>
								<?php } ?>
								<th>តម្លៃសរុបតាមមុខទំនិញ<br /><?= strtoupper(lang('subtotal')) ?></th>
							</tr>
							<?php
								$no = 1;
								$erow = 1;
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
									<td style="vertical-align: middle; text-align: center"><?php echo $no ?></td>
									<td style="vertical-align: middle;">
										<?=$row->product_code;?>
									</td>
									<td style="vertical-align: middle;">
										<?=$row->product_name;?>
									</td>
									<td style="vertical-align: middle; text-align: center">
										<?= $this->erp->formatQuantity($row->quantity);?>
									</td>
									<td style="vertical-align: middle; text-align: right">
										<?= $row->real_unit_price != 0 ? '$ '.$this->erp->formatMoney($row->real_unit_price) : 'Free' ?>
									</td>
									<?php if ($Settings->product_discount) { ?>
										<td style="vertical-align: middle; text-align: center">
										$<?=$this->erp->formatMoney($row->item_discount);?></td>
									<?php } ?>
									<td style="vertical-align: middle; text-align: right">
										<?= $row->subtotal != 0 ? '$ '.$this->erp->formatQuantity($row->subtotal) : 'Free' ?>
									</td>
								</tr>

							<?php
							$no++;
							$erow++;
							}
							?>
							<?php
								if($erow < 16){
									$k=16 - $erow;
									for($j = 1; $j <= $k; $j++){
										if($Settings->product_discount) {
											echo  '<tr>
													<td height="30px" class="text-center">'.$no.'</td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>';
										}else {
											echo  '<tr>
													<td height="30px" class="text-center">'.$no.'</td>
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
									$col = 3;
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
								if ($Settings->product_discount) {
									$col = 2;
								}
								if($invs->paid != 0 && $invs->deposit != 0) {
									$row += 3;
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
										<!-- <p style="font-size:14px !important;"><strong><u>Note:</u></strong></p> -->
										<p><?= nl2br($invs->invoice_footer); ?></p>
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
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">បញ្ចុះតម្លៃលើការបញ្ជាទិញ / <?= strtoupper(lang('order_discount')) ?></td>
								<td align="right">$<?php echo $this->erp->formatQuantity($invs->order_discount).' $'; ?></td>
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
										<p style="position: absolute;"><?= nl2br($invs->invoice_footer); ?></p>
									<?php } ?>
								</td>
								<?php } ?>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">សរុបរួម / <?= strtoupper(lang('total_amount')) ?>
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
			<div style="border-radius: 5px 5px 5px 5px;border:1px solid black;font-size: 10px !important;margin-top: 30px !important;height: auto;" id="note" id="note" class="col-md-12 col-xs-12">
				<p style="margin-top:10px;">អ្នកចេញវិក្កយបត្រ&nbsp;:&nbsp;<strong><?php echo strip_tags($invs->note); ?></strong></p>
			</div>
			<?php } ?>
		 </div>
		 <div id="footer" class="row">
			<div class="col-sm-4 col-xs-4">
				<hr style="margin-bottom: 3px; border:1px solid #000; width: 80%">
				<center>
					<p style="font-size: 10px;">ហត្ថលេខា និងឈ្មោះអ្នករៀបចំ</p>
					<p style="margin-top:-10px;">Prepared's Signature & Name</p>
				</center>
			</div>
			<div class="col-sm-4 col-xs-4">
				<hr style="margin-bottom: 3px; border:1px solid #000; width: 80%">
				<center>
					<p style="font-size: 10px;">ហត្ថលេខា និងឈ្មោះអ្នកលក់</p>
					<p style="margin-top:-10px;">Seller's Signature & Name</p>
				</center>
			</div>
			<div class="col-sm-4 col-xs-4">
				<hr style="margin-bottom: 3px; border:1px solid #000; width: 80%">
				<center>
					<p style="font-size: 10px;">ហត្ថលេខា និងឈ្មោះអ្នកទិញ</p>
					<p style="margin-top:-10px;">Customer's Signature & Name</p>
				</center>
			</div>
		</div>
	</div>
	<br>
	<div style="width: 821px;margin: 0 auto;">
		<a class="btn btn-warning no-print" href="<?= site_url('sales'); ?>" style="border-radius: 0; margin-left: 25px">
        	<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
     	</a>

     	<a class="btn btn-success no-print" href="<?= site_url('sales/add'); ?>" style="border-radius: 0; margin-left: 10px">
        	<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;<?= lang("add_sale"); ?>
     	</a>
	</div>
	<br>
</body>
</html>