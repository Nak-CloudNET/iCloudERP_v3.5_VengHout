
<?php
	//$this->erp->print_arrays($invs);
	$note_arr = explode('|',$invs->note);
	//$this->erp->print_arrays($note_arr[0],$note_arr[1],$note_arr[2]);
	
?>
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
		width: 29.7cm;
		height: 27cm !important;
		margin: 20px auto;
		/*padding: 10px;*/
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
	}
	
	@media print {
		.container {
			width: 29.7cm;
			height: 27cm !important;
		
		}
		.customer_label {
			padding-left: 0 !important;
		}
		
		.invoice_label {
			padding-left: 0 !important;
		}
		
		#footer {
			position: fixed !important;
			bottom: 0 !important;
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
			background-color: #444 !important;
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
	
	<div class="container" style="width: 821px;margin: 0 auto;">
		<div class="col-xs-12" style="width: 794px;">
			<div class="row" style="margin-top: 20px !important;">
		
			
				<div class="col-sm-12 col-xs-12">
					<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                		<i class="fa fa-print"></i> <?= lang('print'); ?>
            		</button>
				</div>
			</div>
			<div class="row">
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
				
			</div>
			<div class="row">
				<div><p style ="text-align:center;font-size:20px; font-weight:bold;">Quotation</p></div>
			</div>
			<div class="row">
				<div class="col-sm-8 col-xs-8">
					<table style="font-size: 11px;">
						<?php if(!empty($customer->company)) { ?>
						<tr>
							<td style="width: 5%;font-weight:bold !important;font-size:13px !important">To</td>
							<td style="width: 2%;">:</td>
							<td style="width: 30%;font-weight:bold !important;font-size:14px !important;font-family:Time New Roman !important;"><?= $customer->company ?></td>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->name_kh || $customer->name)) { ?>
						<tr>
							<td style="font-weight:bold !important;font-size:13px !important;font-family:Time New Roman !important;">Customer Name</td>
							<td>:</td>
							<?php if(!empty($customer->name_kh)) { ?>
							<td style="font-weight:bold !important;font-size:13px !important;font-family:Time New Roman !important;"><?= $customer->name_kh ?></td>
							<?php }else { ?>
							<td>(<?= $customer->code ?>) <?= $customer->name ?></td>
							<?php } ?>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->address_kh || $customer->address)) { ?>
						<tr>
							<td style="font-weight:bold !important;font-size:13px !important;font-family:Time New Roman !important;"> Address</td>
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
							<td style="font-weight:bold !important;font-size:13px !important;font-family:Time New Roman !important;">Tel</td>
							<td>:</td>
							<td><?= $customer->phone ?></td>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->vat_no)) { ?>
						<tr>
							<td style="font-weight:bold !important;font-size:13px !important;font-family:Time New Roman !important;"> VAT No.</td>
							<td>:</td>
							<td><?= $customer->vat_no ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td style="font-weight:bold !important;font-size:13px !important;font-family:Time New Roman !important;"> Fax No.</td>
							<td>:</td>
							
						</tr>
						
					</table>
				</div>
				<div class="col-sm-4 col-xs-4">
					<table style="font-size: 11px;">
						<tr>
							<td style="width: 20%;font-size:13px !important;font-weight:bold !important;font-family:Time New Roman !important;">Quotation No.</td>
							<td style="width: 5%;">:</td>
							<td style="width: 30%;"><?= $invs->reference_no ?></td>
						</tr>
						<tr>
							<td style="font-weight:bold !important;font-size:13px !important;font-family:Time New Roman !important;"> Date</td>
							<td>:</td>
							<td><?= $invs->date; ?></td>
						</tr>
						<tr>
							<td style="font-weight:bold !important;font-size:13px !important;font-family:Time New Roman !important;"> Prepared by:</td>
							<td>:</td>
							<td><?= $saleman->username; ?></td>
						</tr>
						<tr>
							<td style="font-weight:bold !important;font-size:13px !important;font-family:Time New Roman !important;"> Page</td>
							<td>:</td>
							
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
						<tbody style="font-size: 11px;">
							<tr class="thead" style="background-color: #444 !important; color:#FFF  !important;">
								<th style="width: 50px !important;"><?= strtoupper(lang('no')) ?></th>
								<th style="width: 150px !important;"><?= strtoupper(lang('Product_Code')) ?></th>
								<th><?= strtoupper(lang('description')) ?></th>
								<th style="width: 52px;"><?= strtoupper(lang('unit')) ?></th>
								<th style="width: 70px;"><?= strtoupper(lang('qty')) ?></th>
								<th style="width: 90px;"><?= strtoupper(lang('unit_price')) ?></th>
								<th style="width: 100px;"><?= strtoupper(lang('Amount')) ?></th>
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
										$product_unit = $row->product_unit;
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
										<?= $product_unit ?>
									</td>
									<td style="vertical-align: middle; text-align: center">
										<?=$this->erp->formatQuantity($row->quantity);?>
									</td>
									<td style="vertical-align: middle; text-align: center">
										$<?= $this->erp->formatMoney($row->unit_price); ?>
									</td>
									
									<td style="vertical-align: middle; text-align: right">$<?= $this->erp->formatQuantity($row->subtotal);?>
									</td>
								</tr>

							<?php
							$no++;
							}
							?>
							<?php
								if($row<8){
									$k=8 - $row;
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
													
												</tr>';
										}
										
									}
								}
							?>
							<?php
								$row = 5;
								$col =2;
								if ($discount != 0) {
									$col = 1;
								}
								if ($invs->grand_total != $invs->total) {
									$row++;
								}
								if ($invs->order_discount != 0) {
									$row++;
									$col =1;
								}
								if ($invs->shipping != 0) {
									$row++;
									$col =1;
								}
								if ($invs->order_tax != 0) {
									$row++;
									$col =2;
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
								<td rowspan = "<?= $row; ?>" colspan="4" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid white !important;">
									<?php if (!empty($invs->invoice_footer)) { ?>
										<p style="font-size:14px !important;"><strong><u>Note:</u></strong></p>
										<p style="margin-top:-5px !important; line-height: 2"><?= $invs->invoice_footer ?></p>
									<?php } ?>
								</td>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"> <?= strtoupper(lang('total')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?=$this->erp->formatMoney($invs->total); ?></td>
							</tr>
							<?php } ?>
										
							<?php if ($invs->order_discount != 0) : ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"> <?= strtoupper(lang('order_discount')) ?></td>
								<td align="right">$<?php echo $this->erp->formatQuantity($invs->order_discount).' $'; ?></td>
							</tr>
							<?php endif; ?>
							
							<?php if ($invs->shipping != 0) : ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"> <?= strtoupper(lang('shipping')) ?></td>
								<td align="right">$<?php echo $this->erp->formatQuantity($invs->shipping); ?></td>
							</tr>
							<?php endif; ?>
							
							<?php if ($invs->order_tax != 0) : ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('order_tax')) .' '. str_replace('@', '', strstr($invs->order_tax_rate, '@', false)) ?></td>
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
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('total_amount')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?= $this->erp->formatMoney($invs->grand_total); ?></td>
							</tr>
							<?php if($invs->paid != 0 || $invs->deposit != 0){ ?>
							<?php if($invs->deposit != 0) { ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"> <?= strtoupper(lang('deposit')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?php echo $this->erp->formatMoney($invs->deposit); ?></td>
							</tr>
							<?php } ?>
							<?php if($invs->paid != 0) { ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('paid')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?php echo $this->erp->formatMoney($invs->paid-$invs->deposit); ?></td>
							</tr>
							<?php } ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"> <?= strtoupper(lang('balance')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?= $this->erp->formatMoney($invs->grand_total - (($invs->paid-$invs->deposit) + $invs->deposit)); ?></td>
							</tr>
						<?php } ?>
							
						</tbody>
						
					</table>
				</div>
				
			
			<?php
				//$this->erp->print_arrays(explode('|', $invs->note, ));
			?>
			<div class="row" id="footer" style="margin-bottom:100px;">
				<div class="col-lg-12 col-sm-12 col-xs-12">
					<table >
						<tr>
							<td style="font-family: Time New Roman !important;font-size:14px !important;font-weight:bold !important;margin-top:-100px !important;">Term and Condition</td>
							<td style="width: 2%;">:</td>
							<td style="font-family: Time New Roman !important;font-size:14px !important;"><?=strip_tags($note_arr[0])?></td>
						</tr>
						<tr>
							<td style="font-family: Time New Roman !important;font-size:14px !important;font-weight:bold !important;">Validity</td>
							<td style="width: 2%;">:</td>
							<td style="font-family: Time New Roman !important;font-size:14px !important;"><?=strip_tags($note_arr[1])?></td>
						</tr>
						<tr>
							<td style="font-family: Time New Roman !important;font-size:14px !important;font-weight:bold !important;">Term of Payment</td>
							<td>:</td>
							<td style="font-family: Time New Roman !important;font-size:14px !important;"><?=strip_tags($note_arr[2])?></td>
						</tr>
						<tr>
						
							<td style="font-family: Time New Roman !important;font-size:14px !important;font-weight:bold !important;">Remarks</td>
							<td style="width: 2%;">:</td>
							<td style="font-family: Time New Roman !important;font-size:14px !important;"><?=strip_tags($note_arr[3])?></td>
						</tr>
					</table>
					<p style="font-family: Time New Roman !important;font-size:14px !important;">We hope that our quotation is acceptable to you and looking forward to your kind confirmation.</p>
					<p style="font-family: Time New Roman !important;font-size:14px !important;">Thank You,</p>
				</div>
				<div class="col-sm-4 col-xs-4">
					<hr style="margin:0; border:1px solid #000;margin-top:70px !important;">
					<center>
						<p style="margin-top:0px;font-family: Time New Roman !important;font-size:14px !important;">Authorized signature/CO's Stamp</p>
					</center>
				</div>
				<div class="col-sm-4 col-xs-4"></div>
				<div class="col-sm-4 col-xs-4"></div>
			</div>
			
		 </div>	<!--div col sm 6 -->
			</div>
			
		<!--
		<div class="no-print" style="margin-top: 50px;">
			<button class="btn btn-default" onclick="window.print()"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</button>&nbsp;
			<a href="<?= base_url('sales') ?>"><button class="btn btn-warning"><i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;List Sale</button></a>&nbsp;
			<a href="<?= base_url('sales/add') ?>"><button class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add Sale</button></a>&nbsp;
		</div>
		-->
	</div>
	
	<div style="width: 821px;margin: 0 auto;">
		<a class="btn btn-warning no-print" href="<?= site_url('sales'); ?>">
        	<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
     	</a>
	</div>
	
</body>
</html>