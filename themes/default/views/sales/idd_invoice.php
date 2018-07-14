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
		font-family: Time New Roman !important;
	}
		
	.container {
		width: 29.7cm;
		margin: 20px auto;
		/*padding: 10px;*/
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
	}
	
	@media print {
		.customer_label {
			padding-left: 0 !important;
		}
		
		.invoice_label {
			padding-left: 0 !important;
		}
		#footer  hr p {
			font-size: 1px !important;
			position:absolute !important;
   			bottom:0 !important;
   			/*margin-top: -30px !important;*/
		}
		.row table tr td {
			font-size: 10px !important;
		}
		/*.row table tr th {
			font-size: 8px !important;
		}*/
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
			background-color: #738A92 !important;
			color: #FFF !important;
		}
		footer {page-break-after: always;}
		.row .col-xs-7 table tr td, .col-sm-5 table tr td{
			font-size: 10px !important;
		}
		#note{
				max-width: 95% !important;
				margin: 0 auto !important;
				border-radius: 5px 5px 5px 5px !important;
				margin-left: 26px !important;
			}
	 	#total{
			background-color: #C0673D !important;
			font-size: 16px !important;
		}
		#bil{
			font-size: 13px !important;
		}
	}
	.thead th {
		text-align: center !important;
	}
	
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border: 1px solid #000 !important;
	}
	
	.company_addr h5:first-child {
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
		font-size: 14px !important;
	}

	button {
		border-radius: 0 !important;
	}
	
</style>
<body>
	<br>
	<div class="container" style="width: 821px;margin: 0 auto;">
		<div class="col-xs-12" style="width: 794px;">
			<div class="row" style="margin-top: 20px !important;">
		
			<div class="col-sm-3 col-xs-3">
				<?php if(!empty($biller->logo)) { ?>
					<img src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" style="width: 165px; margin-left: 25px;" />
				<?php } ?>
			</div>
			
			<div class="col-sm-6 col-xs-6 company_addr" style="margin-top: -10px !important">
				<center>
					<?php if(!empty($biller->cf1 or $biller->company)) { ?>
						<h5><?= $biller->cf1 ?></h5>
						<h4><?= $biller->company ?></h3>
					<?php }else { ?>
						<h3>CloudNET Cambodia</h3>
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
				<div class="col-sm-12 col-xs-12 inv" style="margin-top: 5px !important">
					<center>
						<h3 style="margin-top:-10px !important;font-family: Time New Roman !important;"><b>INVOICE</b></h3>
					</center>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-7 col-xs-7">
					<table style="font-size: 13px !important;">
						<?php if(!empty($customer->company)) { ?>
						<tr>
							<td style="width: 15%;" id="bil">Invoice No</td>
							<td style="width: 5%;" id="bil">:</td>
							<td style="width: 70%;" id="bil"><?= $invs->reference_no ?></td>
						</tr>
						<?php } ?>
						<?php if(!empty($invs->date)) { ?>
						<tr>
							<td id="bil">Date</td>
							<td id="bil">:</td>
							<td id="bil"><?= $date ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td colspan="3" id="bil"><b><?= $biller->company ?></b></td>
						</tr>
						<tr>
							<td colspan="3" id="bil"><?= $biller->street.", ".$biller->sangkat ?></td>
						</tr>
						<tr>
							<td colspan="3" id="bil"><?= $biller->district.", ".$biller->city.", ".$biller->country ?></td>
						</tr>
						<tr>
							<td id="bil">H/P</td>
							<td id="bil">:</td>
							<td id="bil"><?= $date ?></td>
						</tr>
					</table>
				</div>
				<div class="col-sm-5 col-xs-5">
					<table style="font-size: 13px !important;text-align: right;width: 100% !important;">
						<tr>
							<td colspan="3" id="bil"><b>To: <?= $customer->company ?></b></td>
						</tr>
						<tr>
							<td colspan="3" id="bil"><?= $customer->address." ".$customer->street.", ".$customer->sangkat.", ".$customer->district.", ".$customer->city ?></td>
						</tr>
						<tr>
							<td colspan="3" id="bil"><?= "Tel:"." ".$customer->phone ?></td>
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
					<table class="table table-bordered" style="margin-top: 10px;">
						<tbody style="font-size: 13px;">
							<tr class="thead" style="background-color: #738A92 !important; color: #FFF !important;">
								<th>ល.រ</th>
								<th>កូដទំនិញ</th>
								<th>ឈ្មោះទំនិញ</th>
								<th>ពិពណ៍នា</th>
								<th>ឯកតា</th>
								<th>តម្លៃរាយ</th>
								<th>សរុប</th>
							</tr>
							<tr class="thead" style="background-color: #738A92 !important; color: #FFF !important;">
								<th><?= lang('no') ?></th>
								<th><?= lang('unit_price') ?></th>
								<th><?= lang('Rroducts') ?></th>
								<th><?= lang('description') ?></th>
								<th><?= lang('Unit') ?></th>
								<th><?= lang('USD/Pcs') ?></th>
								<th><?= lang('Total') ?></th>
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
									<td style="vertical-align: middle; text-align: center"><?php echo $no ?></td>
									<td style="vertical-align: middle;text-align: center;">
										<?=$row->product_code;?>
									</td>
									<td style="margin: 0; padding: 0;text-align: center;vertical-align: middle;">
										<?=$row->product_name;?>
									</td>
									<td style="vertical-align: middle; text-align: center">
										<?= $row->name_kh ?>
									</td>
									<td style="vertical-align: middle; text-align: center">
										<?= $product_unit ?>
									</td>
									<td style="vertical-align: middle; text-align: right">
										$<?= $this->erp->formatMoney($row->real_unit_price); ?>
									</td>
									<td style="vertical-align: middle; text-align: right">$<?= $this->erp->formatQuantity($row->subtotal);?>
									</td>
								</tr>

							<?php
							$no++;
							}
							?>
							<?php
								if($row<16){
									$k=1 - $row;
									for($j=1;$j<=$k;$j++){
										if($discount != 0) {
											echo  '<tr>
													<td height="34px"></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>';
										}else {
											echo  '<tr>
													<td height="34px"></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>';
										}
										
									}
								}
							?>
							<?php
								$row = 3;
								$col =3;
								if ($discount != 0) {
									$col = 2;
								}
								if ($invs->grand_total != $invs->total) {
									$row++;
								}
								if ($invs->order_discount != 0) {
									$row++;
									$col =3;
								}
								if ($invs->shipping != 0) {
									$row++;
									$col =3;
								}
								if ($invs->order_tax != 0) {
									$row++;
									$col =3;
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
								<td colspan="5" style="text-align: right; font-weight: bold;">សរុប​ / <?= strtoupper(lang('total')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right" colspan="2">$<?=$this->erp->formatMoney($invs->total); ?></td>
							</tr>
							<?php } ?>
										
							<?php if ($invs->order_discount != 0) : ?>
							<tr>
								<td colspan="5" style="text-align: right; font-weight: bold;">បញ្ចុះតម្លៃលើការបញ្ជាទិញ / <?= strtoupper(lang('order_discount')) ?></td>
								<td align="right" colspan="2">$<?php echo $this->erp->formatQuantity($invs->order_discount); ?></td>
							</tr>
							<?php endif; ?>
							<?php if ($invs->shipping != 0) : ?>
							<tr>
								<td colspan="5" style="text-align: right; font-weight: bold;">ដឹកជញ្ជូន / <?= strtoupper(lang('shipping')) ?></td>
								<td align="right" colspan="2">$<?php echo $this->erp->formatQuantity($invs->shipping); ?></td>
							</tr>
							<?php endif; ?>
							
							<?php if ($invs->order_tax != 0) : ?>
							<tr>
								<td colspan="5" style="text-align: right; font-weight: bold;">ពន្ធអាករ / <?= strtoupper(lang('order_tax')) ?></td>
								<td align="right" colspan="2">$<?= $this->erp->formatQuantity($invs->order_tax); ?></td>
							</tr>
							<?php endif; ?>
							<?php if($invs->paid != 0 || $invs->deposit != 0){ ?>
							<tr>
								<td colspan="5" style="text-align: right; font-weight: bold;font-size: 16px;"><b><?= lang('Total') ?></b>
								</td>
								<td align="right" colspan="2" style="font-size: 16px;"><b>$<?= $this->erp->formatMoney($invs->grand_total); ?></b></td>
							</tr>
							<?php }else{ ?>
							<tr style="background: #C0673D !important;">
								<td colspan="5" style="text-align: center; font-weight: bold;font-size: 16px;" id="total"><b><?= lang('Total') ?></b>
								</td>
								<td align="right" colspan="2" style="text-align: right;font-size: 16px;" id="total"><b>$<?= $this->erp->formatMoney($invs->grand_total); ?></b></td>
							</tr>
							<?php } ?>
							<?php if($invs->paid != 0 || $invs->deposit != 0){ ?>
								<?php if($invs->deposit != 0) { ?>
									<tr>
										<td colspan="5" style="text-align: right; font-weight: bold;">បានកក់ / <?= strtoupper(lang('deposit')) ?>
											(<?= $default_currency->code; ?>)
										</td>
										<td align="right" colspan="2">$<?php echo $this->erp->formatMoney($invs->deposit); ?></td>
									</tr>
								<?php } ?>
								<?php if($invs->paid != 0) { ?>
									<tr>
										<td colspan="5" style="text-align: right; font-weight: bold;">បានបង់ / <?= strtoupper(lang('paid')) ?>
											(<?= $default_currency->code; ?>)
										</td>
										<td align="right" colspan="2">$<?php echo $this->erp->formatMoney($invs->paid-$invs->deposit); ?></td>
									</tr>
								<?php } ?>
								<tr style="background: #C0673D !important;">
									<td colspan="5" style="text-align: center; font-weight: bold;font-size: 15px;" id="total">នៅខ្វះ / <?= strtoupper(lang('balance')) ?>
										(<?= $default_currency->code; ?>)
									</td>
									<td align="right" colspan="2" style="font-size: 15px;text-align: right;" id="total"><b>$<?= $this->erp->formatMoney($invs->grand_total - (($invs->paid-$invs->deposit) + $invs->deposit)); ?></b></td>
								</tr>
						<?php } ?>
						<?php if($invs->note){ ?>
							<tr>
								<td colspan="5" style="border-color: white !important;font-size: 12px;">
									<p style="margin-left: -8px !important;" id="bil"><b>Note : <?php echo strip_tags($invs->note); ?></b></p>
								</td>
								<td colspan="2" style="border-color: white !important;"></td>
							</tr>
						<?php }else{ ?>
							<tr>
								<td colspan="7" style="border-color: white !important;"></td>
							</tr>
							<tr>
								<td colspan="7" style="border-color: white !important;"></td>
							</tr>
						<?php } ?>			
						</tbody>
					</table>
				</div>
			</div>
			<div id="footer" class="row">
				<div class="col-sm-4 col-xs-4">
						<p style="margin-top:-30px;font-size: 13px;"><b>Acknowledged accepted</b></p>
				</div>
				<div class="col-sm-4 col-xs-4">
					<center>
						<p style="margin-top:-30px;font-size: 13px;"><b>Delivered by</b></p>
					</center>
				</div>
				<div class="col-sm-4 col-xs-4" style="text-align: right;">
						<p style="margin-top:-30px;font-size: 13px;"><b>IDD,GM Approval</b></p>
				</div>
			</div>
			<footer></footer>
			<br><br><br><br><br><br>
		 </div>
		 <div style="text-align: center;font-size: 11px;" id="footer1">
		 	<p><?= $biller->street.", ".$biller->sangkat.", ".$biller->district.", ".$biller->city.", ".$biller->country ?></p>
		 	<p>Phone: <?= $biller->phone ?> ,Email: <?= $biller->email ?></p>
		 </div>
		 <br><br>
	</div>
	<br>
	<div style="width: 821px;margin: 0 auto;">
		<a class="btn btn-warning no-print" href="<?= site_url('sales'); ?>">
        	<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
     	</a>
	</div>
	<br><br>
</body>
</html>