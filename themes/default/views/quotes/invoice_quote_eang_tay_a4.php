<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Invoice quote</title>
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
   			bottom: 10px !important;
		}
		.row table tr td {
			font-size: 12px !important;
		}
		img{
			margin-left:40px !important;
		}
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
			background-color: #444 !important;
			color: #FFF !important;
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
		margin-top:20px;
		margin-left
	}
	
</style>
<body>
	<div class="container" style="width: 821px;margin: 0 auto;">
		<div class="col-sm-12 col-xs-12">
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px; margin-top: 10px" onclick="window.print();">
        		<i class="fa fa-print"></i> <?= lang('print'); ?>
    		</button>
		</div>
			<?php if (isset($biller->logo)) { ?>
				<div class="col-xs-3 text-center" style="margin-bottom:20px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
			 <?php }else{ ?>
				<div class="col-xs-3 text-center" style="margin-bottom:20px;"></div>
			 <?php } ?>
                <div class="col-xs-6 text-center" style="margin-top:10px !important">
                    <h2 style="font-family: Khmer M1;"><?= lang("Quotation"); ?></h2>
                </div>
		<div class="col-xs-12">
			<div class="row" >
				<div class="col-sm-12 col-xs-12 inv" style="margin-top: -30px !important">
					<center>
						<h3 style="font-size:14px !important;line-height:23px;"><b>មានទទួលកិនភ្លីធំ ភ្លីក្បឿង <br> និងផ្តត់ផ្គង់សំភារៈសំណង់ ដែក <br> ស័ង្គសី អាលុយមីញ៉ូមគ្រប់ប្រភេទ</b></h3>
						<h4></h4>
					</center>
				</div>
			</div>
			<div class="col-sm-12 col-xs-12" style="font-size:11px;">
				<div  class="col-sm-6 col-xs-6">
				
				</div>
				<div  class="col-sm-6 col-xs-6 text-right" style="font-size:12px; padding-right:80px;">
					N<sup>0</sup>&nbsp;:&nbsp;&nbsp;<b><?= $invs->reference_no?></b>
				</div>
			</div>
			
			<div class="col-sm-12 col-xs-12">
			<br>
				<div  class="col-sm-3 col-xs-3">
						អតិថិជន <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $customer->name?></b></div>
				<div  class="col-sm-5 col-xs-5">
						អាស័យដ្ឋាន : <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $customer->address?></b></div>
				<div  class="col-sm-4 col-xs-4">
						Tel :<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $customer->phone?></b></div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12">
					<table class="table table-bordered" style="margin-top: 10px;">
						<tbody style="font-size: 11px;">
							<tr class="thead" style="background-color: #444 !important; color: #FFF !important;">
								<th>ល.រ<br /><?= strtoupper(lang('no')) ?></th>
								<th>ឈ្មោះទំនិញ<br /><?= strtoupper(lang('description')) ?></th>
								<th>ប្រវែង<br /><?= strtoupper(lang('Length')) ?></th>
								<th>បរិមាណ<br /><?= strtoupper(lang('Quantity')) ?></th>
								<th>ខ្នាត<br /><?= strtoupper(lang('Unit')) ?></th>
								<th>សរុបចំនួន<br /><?= strtoupper(lang('Total QTY')) ?></th>
								<th>តម្លៃរាយ<br /><?= strtoupper(lang('Unit Price')) ?></th>
								<th>សរុបទឹកប្រាក់<br /><?= strtoupper(lang('Total Amount')) ?></th>
							</tr>
							<?php 
							
								$no = 1;
								$erow = 1;
								$totalRow = 0;
								foreach ($rows as $row) {
									//$this->erp->print_arrays($row);
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
										<?=$row->product_name;?>
									</td>
									<td style="vertical-align: middle; text-align: center">
										<?php
											if ($row->piece == 0) {
												echo round($row->quantity);
											} else {
												echo round($row->piece);
											}
										?>
									</td>
									<td style="vertical-align: middle; text-align: center">
										<?php
											if ($row->wpiece == 0) {
												echo '1.00';
											} else {
												echo round($row->wpiece);
											}
										?>
									</td>
									<td style="width: 80px; text-align:center;"><?= $product_unit; ?></td>
									<td style="vertical-align: middle; text-align: center">
										  <?= round($row->quantity) ?>
									</td>
									 
									<?php if($Owner || $Admin || $GP['sales-price']){ ?>
									<td class="text-center">
										<?=$row->unit_price>0 ? "$ ".$this->erp->formatMoney($row->unit_price) : $free; ?>
									</td>
									<?php }?>
									<td style="vertical-align: middle; text-align: right"> <?= $row->subtotal!=0 ? "$ ".$this->erp->formatMoney($row->subtotal):$free; 
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
								if($erow<15){
									$k=15 - $erow;
									for($j=1;$j<=$k;$j++) {
											echo  '<tr>
													<td height="34px" style="text-align: center; vertical-align: middle">'.$no.'</td>
													<td></td>
													<td></td>
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
								$row = 3;
								$col =4;
								if ($discount != 0) {
									$col = 3;
								}
								if ($invs->grand_total != $invs->total) {
									$row++;
								}
								if ($invs->order_discount != 0) {
									$row++;
									$col =4;
								}
								if ($invs->shipping != 0) {
									$row++;
									$col =4;
								}
								if ($invs->order_tax != 0) {
									$row++;
									$col =4;
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
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">ប្រាក់សរុប / <?= strtoupper(lang('TOTAL :')) ?>
								</td>
								<td align="right">$<?= $this->erp->formatMoney($invs->total); ?></td>
							</tr>
							
						</tbody>
						
					</table>
				</div>
			</div>
		 </div>	<!--div col sm 6 -->
		 
		 <div class="col-ls-12"> 
		 
			<div class="col-sm-6 col-xs-6">
				
			</div>
			<div class="col-sm-6 col-xs-6">
					<p>
					<span class='pull-right' style="margin-right:10px;">
						នៅថ្ងៃទី<b>&nbsp;<?php $d=explode("-",$invs->date); $dt=explode(" ",$d[2]); echo "$dt[0]";?>&nbsp;</b>
						ខែ<b>&nbsp;<?php echo "$d[1]"?>&nbsp;</b>
						ឆ្នាំ​<b>&nbsp;<?php echo "$d[0]"?>&nbsp;</b>
						</span>
					</p>
			</div>
			
		 </div>
		 &nbsp;
		 

		<div id="footer" class="row">
			<div class="col-sm-4 col-xs-4">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អតិថិជន​ / Customer</p>
					<br><br>
					<p>....................................................</p>
				</center>
			</div>
			<div class="col-sm-4 col-xs-4">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អ្នកដឹកជញ្ជូន / Deliver</p>
					<br><br>
					<p>....................................................</p>
				</center>
			</div>
			<div class="col-sm-4 col-xs-4">
				<center>
					<p style="font-size: 12px; margin-top: 4px !important">អ្នកលក់ / Seller</p>
					<br><br>
					<p>.....................................................</p>
				</center>
			</div>
		</div>

		<div style="width: 821px;margin: 20px">
			<a class="btn btn-warning no-print" href="<?= site_url('quotes'); ?>" style="border-radius: 0">
	        	<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
	     	</a>
		</div>
	</div>

</body>
</html>