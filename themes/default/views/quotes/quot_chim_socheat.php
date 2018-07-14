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
			background-color: #1990d5 !important;
			color: #000 !important;
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
	<br>
	<div class="container" style="width: 821px;margin: 0 auto;">
		<div class="col-xs-12" style="width: 794px;">
			<div class="row" style="margin-top: 20px !important;">
                <div class="col-sm-8 col-xs-8 text-right" >

                </div>
                <div class="col-sm-4 col-xs-4">
                    <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                        <i class="fa fa-print"></i> <?= lang('print'); ?>
                    </button>
                </div>
            </div>
            <div class="row" style="margin-top: 20px !important;">
			<div class="col-sm-12 col-xs-12 company_addr" style="margin-top: -15px !important;border-bottom: 2px solid #000;">
				<center>
                    <h2 style="padding-bottom: 20px;color: #1990d5;"><?=($biller->company != '-' ? $biller->company : $biller->name)?></h2>

					<?php if(!empty($biller->address)) { ?>
						<p style="font-size: 11px;padding-bottom: 10px;">Address<?= $biller->address; ?></p>
					<?php } ?>
					
					<?php if(!empty($biller->phone)) { ?>
						<p style="margin-top:-10px !important;font-size: 11px;">Tel:&nbsp;<?= $biller->phone; ?></p>
					<?php } ?>
					
					<?php if(!empty($biller->email)) { ?>
						<p style="margin-top:-10px !important;font-size: 11px;">E-mail:&nbsp;<?= $biller->email; ?></p>
					<?php } ?>
				</center>
				</div>
			</div>
            <br>
			<div class="row">
				<div class="col-sm-12 col-xs-12 inv" style="">
					<center>
						<h4 style="font-size: 20px !important;color: red;"><b>QUOTATION</b></h4>
					</center>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-7 col-xs-7">
					<table style="width:100%;font-size: 13px;line-height: 25px;">
						<tr>
							<td style="width:30%;">Customer Name</td>
							<td>&nbsp;:&nbsp;</td>
							<td><?= $customer->names ?></td>
						</tr>
						<tr>
							<td>Tel</td>
							<td>&nbsp;:&nbsp;</td>
							<td><?= $customer->phone ?></td>
						</tr>
					</table>
				</div>
				<div class="col-sm-5 col-xs-5 text-right" >
					<table style="width:100%;font-size: 12px;">
						<tr>
							<td height="25px;"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td height="25px;"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td style="width:60%;text-align: right;">Date</td>
							<td style="width:10%;text-align: center;">:</td>
							<td style="width:30%;text-align: left;"><?= $this->erp->hrsd($invs->date); ?></td>
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
							<tr class="thead" style="background-color: #1990d5 !important; color: #000 !important;">
								<th><?= strtoupper(lang('no')) ?></th>
								<!--<th>រូបភាពទំនិញ<br /><?= strtoupper(lang('image')) ?></th>
								<th style="width: 67px;">កូដទំនិញ<br /><?= strtoupper(lang('code')) ?></th>-->
								<th><?= strtoupper(lang('description')) ?></th>
								<!--<th style="width: 52px;">ខ្នាត<br /><?= strtoupper(lang('unit')) ?></th>-->
								<th style="width: 70px;"><?= strtoupper(lang('qty')) ?></th>
								<th style="width: 90px;"><?= strtoupper(lang('unit_price')) ?></th>
								
								<!--<?php if ($Settings->product_discount) { ?>
									<th style="width: 60px;">បញ្ចុះតម្លៃ<br /><?= strtoupper(lang('discount')) ?></th>
								<?php } ?>
								<?php if ($Settings->tax1) { ?>
									<th style="width: 67px;">ពន្ធទំនិញ<br /><?= strtoupper(lang('tax')) ?></th>
								<?php } ?>-->
								<th style="width: 100px;"><?= strtoupper(lang('Total Price')) ?></th>
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
									<!--<td style="text-align:center; vertical-align:middle;"><img style="width:30px;height:30px;" src="<?= base_url() . 'assets/uploads/thumbs/' . $row->image; ?>">
									</td>
									<td style="vertical-align: middle;">
										<?=$row->product_code;?>
									</td>-->
									<td style="vertical-align: middle;">
										<?=$row->product_name;?>
									</td>
									<!--<td style="vertical-align: middle; text-align: center">
										<?= $product_unit ?>
									</td>-->
									<td style="vertical-align: middle; text-align: center">
										<?=$this->erp->formatQuantity($row->quantity);?>
									</td>
									<td style="vertical-align: middle; text-align: center">
										$<?= $this->erp->formatMoney($row->unit_price); ?>
									</td>
									<!--<?php if ($row->item_discount) {?>
										<td style="vertical-align: middle; text-align: center">
										$<?=$this->erp->formatMoney($row->item_discount);?></td>
									<?php } ?>
									<?php if ($row->item_tax) {?>
										<td style="vertical-align: middle; text-align: center">
										$<?=$this->erp->formatMoney($row->item_tax);?></td>
									<?php } ?>-->
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
												</tr>';
										}else {
											echo  '<tr>
													<td height="34px"></td>
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
									$col = 2;
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
									$row += 3;
								}elseif ($invs->paid != 0 && $invs->deposit == 0) {
									$row += 2;
								}elseif ($invs->paid == 0 && $invs->deposit != 0) {
									$row += 2;
								}
								
							?>
							<tr style="background-color: #1990d5;">
								<!--<td rowspan = "<?= $row; ?>" colspan="4" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important;">
									<?php if (!empty($invs->invoice_footer)) { ?>
										<p style="font-size:14px !important;"><strong><u>Note:</u></strong></p>
										<p style="margin-top:-5px !important; line-height: 2"><?= $invs->invoice_footer ?></p>
									<?php } ?>
								</td>-->
								<td></td>
								<td colspan="3" style="text-align: center; font-weight: bold;"><?= strtoupper(lang('Total Amount')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?=$this->erp->formatMoney($invs->total); ?></td>
							</tr>

							<!--<?php if ($invs->order_discount != 0) : ?>
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
										<p><strong><u>Note:</u></strong></p>
										<p><?= $invs->invoice_footer ?></p>
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
						<?php } ?>-->
							
						</tbody>
						
					</table>
				</div>
			</div>
            <?php //$this->erp->print_arrays($invs); ?>
			<?php if($invs->note){ ?>
			<div style="font-size: 14px !important;" id="note" class="col-md-12 col-xs-12">
				<p style="text-decoration: underline;color: #0f7864;">Commercial Condition</p>
			</div>
			<?php } ?>
            <br>

            <div class="row">
                <div style="font-size: 12px !important;padding-left: 50px;" class="col-md-8 col-xs-8">
                    <p style="font-style: italic;"><?php echo strip_tags($invs->note); ?></p>
                </div>
                <div style="font-size: 15px !important;" class="col-md-4 col-xs-4 text-center">
                    <p style=""><b>Chim Socheat</b></p>
                </div>
            </div>
            <br>

            <div style="font-size: 12px !important;" class="col-md-12 col-xs-12">
                <p style="font-style: italic;"><b>Signed : ...................................</b></p>
                <p style="font-style: italic;"><b>Date : ........../........../..........</b> </p>
                <p style="font-style: italic;padding-bottom: 50px;"><b>[For Customer]</b></p>
            </div>

		 </div>	<!--div col sm 6 -->
		<!--
		<div class="no-print" style="margin-top: 50px;">
			<button class="btn btn-default" onclick="window.print()"><i class="fa fa-print" aria-hidden="true"></i>&nbsp;Print</button>&nbsp;
			<a href="<?= base_url('sales') ?>"><button class="btn btn-warning"><i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;List Sale</button></a>&nbsp;
			<a href="<?= base_url('sales/add') ?>"><button class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add Sale</button></a>&nbsp;
		</div>
		-->
	</div>
	<br>
	<div style="width: 821px;margin: 0 auto;">
		<a class="btn btn-warning no-print" href="<?= site_url('sales'); ?>">
        	<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
     	</a>
	</div>
	<br>
</body>
</html>