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
		
	.container {
		width: 21cm;
		min-height: 29.7cm;
		margin: 20px auto;
		padding: 10px;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
	}
	
	@media print {
		.customer_label {
			padding-left: 0 !important;
		}
		
		.invoice_label {
			padding-left: 0 !important;
		}
		.container {
			height: 29.7cm !important;
		}
		#footer  hr p {
			position:absolute !important;
   			bottom:0 !important;
   			/*margin-top: -30px !important;*/
		}
		.row table tr td {
			font-size: 12px;
		}
		.row table tbody .thead {
			background: #444 !important;
		}
		footer {page-break-after: always;}
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
		font-size: 14px !important;
	}

	button {
		border-radius: 0 !important;
	}
	
</style>
<body>
	<div class="container">
		<!-- <div class="row">
			<div class="col-sm-12 col-xs-12 company_addr">
				<center>
					<?php if(!empty($biller->cf1)) { ?>
						<h3><?= $biller->cf1 ?></h3>
						<h3>Ellesse Furniture</h3>
					<?php }else { ?>
						<h3>CloudNET Cambodia</h3>
					<?php } ?>
				</center>
			</div>
		</div> -->
		
		<div class="row" style="margin-top: 20px !important;">
		
			<div class="col-sm-3 col-xs-3">
				<?php if(!empty($biller->logo)) { ?>
					<img src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" style="width: 100px; margin-top: -10px; margin-left: 25px;" />
				<?php } ?>
			</div>
			
			<div class="col-sm-6 col-xs-6 company_addr" style="margin-top: -15px !important">
				<center>
					<?php //if(!empty($biller->cf1)) { ?>
						<!-- <h3><?//= $biller->cf1 ?></h3> -->
						<!-- <h3>Ellesse Furniture</h3> -->
					<?php //}else { ?>
						<!-- <h3>CloudNET Cambodia</h3> -->
					<?php //} ?>
				
				<?php //if(!empty($biller->vat_no)) { ?>
					<!-- <p>លេខអត្តសញ្ញាណកម្ម អតប (VAT No):&nbsp;<?//= $biller->vat_no; ?></p> -->
				<?php //} ?>
				
				<?php //if(!empty($biller->cf4)) { ?>
					<!-- <p style="margin-top:-10px !important;">អាសយដ្ឋាន ៖ &nbsp;<?//= $biller->cf4; ?></p> -->
				<?php //} ?>
				
				<?php //if(!empty($biller->phone)) { ?>
					<!-- <p style="margin-top:-10px !important;">ទូរស័ព្ទលេខ (Tel):&nbsp;<?//= $biller->phone; ?></p> -->
				<?php //} ?>
				
				<?php //if(!empty($biller->email)) { ?>
					<!-- <p style="margin-top:-10px !important;">សារអេឡិចត្រូនិច (E-mail):&nbsp;<?//= $biller->email; ?></p> -->
				<?php //} ?>
				</center>
			</div>
			<div class="col-sm-3 col-xs-3">
				
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
			<div class="col-sm-6 col-xs-6">
				<table>
					<?php if(!empty($customer->company)) { ?>
					<tr>
						<td>Company Name</td>
						<td>:</td>
						<td><?= $customer->company ?></td>
					</tr>
					<?php } ?>
					<?php if(!empty($customer->name_kh || $customer->name)) { ?>
					<tr>
						<td>Customer Name</td>
						<td>:</td>
						<?php if(!empty($customer->name_kh)) { ?>
						<td><?= $customer->name_kh ?></td>
						<?php }else { ?>
						<td><?= $customer->name ?></td>
						<?php } ?>
					</tr>
					<?php } ?>
					<?php if(!empty($customer->address_kh || $customer->address)) { ?>
					<tr>
						<td>Address</td>
						<td>:</td>
						<?php if(!empty($customer->address_kh)) { ?>
						<td><?= $customer->address_kh?></td>
						<?php }else { ?>
						<td><?= $customer->address ?></td>
						<?php } ?>
					</tr>
					<?php } ?>
					<?php if(!empty($customer->address_kh || $customer->address)) { ?>
					<tr>
						<td>Phone</td>
						<td>:</td>
						<td><?= $customer->phone ?></td>
					</tr>
					<?php } ?>
					<?php if(!empty($customer->vat_no)) { ?>
					<tr>
						<td>VAT No.</td>
						<td>:</td>
						<td><?= $customer->vat_no ?></td>
					</tr>
					<?php } ?>
					<tr>
						<td>Term Payment</td>
						<td>:</td>
						<td>..................</td>
					</tr>
				</table>
			</div>
			<div class="col-sm-2 col-xs-2">
				
			</div>
			<div class="col-sm-4 col-xs-4">
				<table>
					<tr>
						<td>Noº</td>
						<td>:</td>
						<td><?= $invs->reference_no ?></td>
					</tr>
					<tr>
						<td>Date</td>
						<td>:</td>
						<td><?= $invs->date; ?></td>
					</tr>
					<tr>
						<td>Saller</td>
						<td>:</td>
						<td><?= $invs->saleman;?></td>
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
					<tbody style="font-size: 12px;">
						<tr class="thead">
							<th><?= strtoupper(lang('no')) ?></th>
							<th><?= strtoupper(lang('description')) ?></th>
							<th><?= strtoupper(lang('size')) ?></th>
							<th><?= strtoupper(lang('qty')) ?></th>
							<th><?= strtoupper(lang('unit_price')) ?></th>
							<?php if ($Settings->product_discount) { ?>
								<th><?= strtoupper(lang('discount')) ?></th>
							<?php } ?>
							<?php if ($Settings->tax1) { ?>
								<th><?= strtoupper(lang('tax')) ?></th>
							<?php } ?>
							<th><?= strtoupper(lang('amount')) ?></th>
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
								<td style="vertical-align: middle;">
									<?=$row->product_name;?>
								</td>
								<!-- <td style="margin: 0; padding: 0">
									<?=$row->product_noted;?>
								</td> -->
								<td style="vertical-align: middle; text-align: center">
									<?= $product_unit ?>
								</td>
								<td style="vertical-align: middle; text-align: center">
									<?=$this->erp->formatQuantity($row->quantity);?>
								</td>
								<td style="vertical-align: middle; text-align: right">
									$<?= $this->erp->formatMoney($row->real_unit_price); ?>
								</td>
								<?php if ($row->item_discount) {?>
									<td style="vertical-align: middle; text-align: right;">
									$<?=$this->erp->formatMoney($row->item_discount);?></td>
								<?php } ?>
								<?php if ($row->item_tax) {?>
									<td style="vertical-align: middle; text-align: right;">
									$<?=$this->erp->formatMoney($row->item_tax);?></td>
								<?php } ?>
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
							$row = 5;
							$col = 2;
							if ($discount != 0) {
								$col = 3;
							}
							if ($invs->grand_total != $invs->total) {
								$row++;
							}
							if ($invs->order_discount != 0) {
								$row++;
							}
							if ($invs->shipping != 0) {
								$row++;
							}
							if ($invs->order_tax != 0) {
								$row++;
							}
						?>
									
						<?php if ($invs->grand_total != $invs->total) { ?>
						<tr>
							<td rowspan = "<?= $row; ?>" colspan="5" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important;">
								<?php if (!empty($invs->invoice_footer)) { ?>
									<p style="font-size:14px !important;"><strong><u>Note:</u></strong></p>
									<p style="margin-top:-5px !important; line-height: 2"><?= $invs->invoice_footer ?></p>
								<?php } ?>
							</td>
							<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('total')) ?>
							</td>
							<td align="right">$<?=$this->erp->formatMoney($invs->total); ?></td>
						</tr>
						<?php } ?>
									
									<?php if ($invs->order_discount != 0) : ?>
									<tr>
										<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('order_discount')) ?></td>
										<td align="right">$<?php echo $this->erp->formatQuantity($invs->order_discount).' $'; ?></td>
									</tr>
									<?php endif; ?>
									
									<?php if ($invs->shipping != 0) : ?>
									<tr>
										<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('shipping')) ?></td>
										<td align="right">$<?php echo $this->erp->formatQuantity($invs->shipping); ?></td>
									</tr>
									<?php endif; ?>
									
									<?php if ($invs->order_tax != 0) : ?>
									<tr>
										<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('order_tax')) ?></td>
										<td align="right">$<?= $this->erp->formatQuantity($invs->order_tax); ?></td>
									</tr>
									<?php endif; ?>
									
									<tr>
										<?php if ($invs->grand_total == $invs->total) { ?>
										<td rowspan="<?= $row; ?>" colspan="3" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important;">
											<?php if (!empty($invs->invoice_footer)) { ?>
												<p><strong><u>Note:</u></strong></p>
												<p><?= $invs->invoice_footer ?></p>
											<?php } ?>
										</td>
										<?php } ?>
										<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('total_amount')) ?>
										</td>
										<td align="right">$<?= $this->erp->formatMoney($invs->grand_total); ?></td>
									</tr>
									<?php if($invs->paid != 0 ) {?>
									<tr>
										<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('paid')) ?>
										</td>
										<td align="right">$<?php echo $this->erp->formatMoney($invs->paid); ?></td>
									</tr>
									<tr>
										<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('balance')) ?>
										</td>
										<td align="right">$<?= $this->erp->formatMoney($invs->grand_total - $invs->paid); ?></td>
									</tr>
									<?php }?>
						
					</tbody>
					
				</table>
			</div>
		</div>
		<div id="footer" class="row" style="margin-top: 80px !important;">
			<div class="col-sm-4 col-xs-4">
				<!-- <hr style="margin:0; border:1px solid #000;"> -->
				<center>
					<p>អ្នកទិញ / Buyer</p>
					<!-- <p style="margin-top:-10px;">Customer's Signature & Name</p> -->
				</center>
			</div>
			<div class="col-sm-4 col-xs-4">
				<!-- <hr style="margin:0; border:1px solid #000;"> -->
				<center>
					<p>អ្នកប្រគល់ / Deliver</p>
					<!-- <p style="margin-top:-10px;">Prepared's Signature & Name</p> -->
				</center>
			</div>
			<div class="col-sm-4 col-xs-4">
				<!-- <hr style="margin:0; border:1px solid #000;"> -->
				<center>
					<p>អ្នកលក់ / Seller</p>
					<!-- <p style="margin-top:-10px;">Seller's Signature & Name</p> -->
				</center>
			</div>
		</div>
		<footer></footer>
		<div class="no-print" style="margin-top: 50px;">
			<button class="btn btn-default" onclick="window.print()"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</button>&nbsp;
			<a href="<?= base_url('sales') ?>"><button class="btn btn-warning"><i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;List Sale</button></a>&nbsp;
			<a href="<?= base_url('sales/add') ?>"><button class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add Sale</button></a>&nbsp;
		</div>
	</div>
</body>
</html>