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
		
	}
	@media print {
		.pageBreak {
			page-break-after: always;
		}
		.container {
			height: 29.5cm !important;
		}
		.customer_label {
			padding-left: 0 !important;
		}
		
		.invoice_label {
			padding-left: 0 !important;
		}
		#footer {
			position: fixed !important;
   			bottom: 50px !important;
		}
		
		.row table tr td {
			font-size: 14px !important;
		}
		img{
			margin-left:40px !important;
		}
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
			background-color: #444 !important;
			color: #FFF !important;
		}
		
		.row .col-xs-7 table tr td, .col-sm-5 table tr td{
			font-size: 14px !important;
		}
		#note{
				max-width: 95% !important;
				margin: 0 auto !important;
				border-radius: 5px 5px 5px 5px !important;
				margin-left: 26px !important;
			}
	}
	.thead th{
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
	img{
		width:150px;height:100px;
		margin-top:20px;
		margin-left
	}
	.no{
			font-size:14px; 
		}
</style>
<body>
	
		
                <div class="col-xs-12 text-center">
                    <h2 style="font-family: Khmer M1;font-size:27px;padding-top:-20px;"><?= lang("invoice_kh"); ?></h2>
                </div>
			<div class="clearfix"></div>
            <div class="row padding10">
			<br>
                <div class="col-xs-6">
                    <table style="font-size: 14px;line-height:25px;margin-left:50px;">
						<tr>
							<td>អតិថិជន​</td>
							<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
							<td><b><?=$customer->name?></b></td>
						</tr>
						<tr>
							<td>លេខទូរស័ព្ទ</td>
							<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
							<td><b><?=$customer->phone?></b></td>
						</tr>
						<tr>
							<td>អ្នកលក់</td>
							<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
							<td><b><?=$invs->saleman?></b></td>
						</tr>	
					</table>
                </div>
                <div class="col-xs-5">
                    <table style="font-size: 14px;line-height:25px;">
						<tr>
							<td>វិក្កយបត្រលេខ </td>
							<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
							<td><b><?= $invs->reference_no ?></b></td>
						</tr>
						<tr>
							<td>កាលបរិច្ឆេទ </td>
							<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
							<td><b><?= $this->erp->hrld($invs->date); ?></b></td>
						</tr>
					</table>
                </div>
            </div>
            <div class="clearfix"></div>
			
		<div class="col-xs-12">
			<div class="row">
				<div class="col-sm-12 col-xs-12">
					<table class="table table-bordered" style="margin-top: 10px;">
						<tbody style="font-size: 14px;">
							<tr class="thead" >
								<th style="width:50px;height:40px;">ល.រ</th>
								<th>ឈ្មោះទំនិញ</th>
								<th>ចំនួន</th>
								<th style="width:100px;">តម្លៃ</th>
								<th>បញ្ចុះតម្លៃ</th>
								<th>តម្លៃសរុប</th>
							</tr>
							<?php 
								$no = 1;
								$erow = 1;
								$totalRow = 0;
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
								$str_unit = "";
                               
                                if($row->option_id){
									
                                   $getvar = $this->sales_model->getAllProductVarain($row->product_id);
										
										 foreach($getvar as $varian){
											 if($varian->product_id){
												
												$var = $this->erp->sales_model->getVariantName($row->product_id,$row->option_id);
												$str_unit = $var->name;
												
											 }else{
												$str_unit = $row->uname;
											}
										}
                                }else{
                                    $str_unit = $row->uname;
								}
							?>
								<tr>
									<td style="vertical-align: middle; text-align: center " height="30px"><?php echo $no ?></td>
									<td style="vertical-align: middle;">
										<?=$row->product_name;?>
									</td>
									<td style="vertical-align: middle; text-align: center">
										<?php
											if ($row->piece == 0) {
												echo round($row->quantity);
											} else {
												echo round($row->wpiece);
											}
										?>
									</td>
									<td style="vertical-align: middle; text-align: right">
										$<?= $this->erp->formatMoney($row->real_unit_price); ?>
									</td>
									 
									<?php if ($row->item_discount) {?>
										<td style="vertical-align: middle; text-align: center">
										$<?=$this->erp->formatMoney($row->item_discount);?></td>
									<?php } ?>
									<td style="vertical-align: middle; text-align: right"> <?= $row->subtotal!=0 ? $this->erp->formatMoney($row->subtotal):$free; 
									$total += $row->subtotal;
									?>
									</td>
								</tr>

							<?php
							$no++;
							$erow++;
							$totalRow++;
								if ($totalRow % 25 == 0) {
									echo '<tr class="pageBreak"></tr>';
								}

							}
							?>
							<?php
								if($erow<11){
									$k=11 - $erow;
									for($j=1;$j<=$k;$j++) {
											echo  '<tr>
													<td height="30px" style="text-align: center; vertical-align: middle">'.$no.'</td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
																										
												</tr>';
										$no++;
									}
								}
							?>
							<?php
							
								$row = 6;
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
								
							<tr>
								<td colspan="3" rowspan="1" style="border-left: 1px solid #FFF !important;  ">អត្រាប្រាក់​ 1$ =<b>&nbsp;&nbsp;<?=$this->erp->formatMoney($rates->rate);?></b></td>
								
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;height:30px;">ប្រាក់សរុប 
								</td>
								<td align="right">$<?= $this->erp->formatMoney($invs->total); ?></td>
							</tr>
							
							<tr>
								<td colspan="3" rowspan="<?= $row; ?>" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important">
									<?php
									//$this->erp->print_arrays($invs);
									if ($invs->invoice_footer || $invs->invoice_footer != "") { ?>
										<div style="font-size:10px; line-height:25px;">
											<div><?= $this->erp->decode_html($invs->invoice_footer); ?></div>
										</div>
									<?php
									}
									?>
								</td>
								<?php if ($invs->order_discount != 0) : ?>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;height:30px;">ការបញ្ចុះតម្លៃសរុប</td>
								<td align="right">$<?php echo $this->erp->formatQuantity($invs->order_discount); ?></td>
								<?php endif; ?>
							</tr>
							
							<?php if($invs->order_tax !=0){?>
							<tr>
								<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
								<td colspan="<?= $col; ?>" style="text-align:right; vertical-align:middle;height:30px;">ពន្ធ</td>
								<td style="text-align:right; vertical-align:middle;">
										$<?=$this->erp->formatMoney($invs->order_tax);?>
								</td>
							</tr>
							<?php }?>
							
							
							<?php if($invs->deposit != 0) { ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;height:30px;">បានកក់ 
									
								</td>
								<td align="right">$<?php echo $this->erp->formatMoney($invs->deposit); ?></td>
							</tr>
							<?php } ?>
							<?php //$this->erp->print_arrays($invs->paid);?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;height:30px;">ប្រាក់ដែលត្រូវបង់ 
								</td>
								<td align="right">$<?= $this->erp->formatMoney($grand_total=$invs->total-$invs->order_discount+$invs->order_tax); ?></td>
							</tr>
							<?php if($invs->paid >0) { ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;height:30px;">បានបង់ 
								</td>
								<td align="right">$<?= $this->erp->formatMoney($invs->paid); ?></td>
							</tr>
							<?php } ?>
							<?php if($invs->paid < $invs->grand_total && $invs->paid !=0) { ?>
							<tr>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;height:30px;">នៅខ្វះ 
								</td>
								<td align="right">$<?= $this->erp->formatMoney($invs->grand_total-$invs->paid); ?></td>
							</tr>
							<?php } ?>
							<?php if($invs->paid>$invs->total) { ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;height:30px;">ប្រាក់អាប់
								</td>
								<td align="right">$<?= $this->erp->formatMoney($invs->paid-$invs->total); ?></td>
							</tr>
							<?php } ?>
						</tbody>
						
					</table>
				</div>
			</div>
		 </div>	<!--div col sm 6 -->
		<div id="footer" class="row" style="margin-left: 20px !important">
			<div class="col-sm-3 col-xs-3 text-center">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អ្នកទិញ</p>
					<br><br>
					<p>...............................</p>
				</center>
			</div>
			<div class="col-sm-4 col-xs-4 text-center">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អ្នកទទួល</p>
					<br><br>
					<p>...............................</p>
				</center>
			</div>
			<div class="col-sm-3 col-xs-3 text-center">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អ្នកលក់ / Seller</p>
					<br><br>
					<p>..............................</p>
				</center>
			</div>
		</div>
		
	

</body>
</html>