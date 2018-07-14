<!doctype>
<html>
	<head>
		<title></title>
		<meta charset="utf-8">
		<link href="<?php echo $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet">
		<style>
			@media print{
				thead th {
					background: #CCC;
					font-size: 14px;
				}
				tbody tr td{
					font-size: 12px;
				}
				tfoot tr td{
					font-size: 12px;
				}
				.row #footer {
					font-size: 12px;
				}
			}
			
			thead th {
				background-color: #CCC;
			}
		</style>
	</head>
	<body>
	<div id="body">
	<div class="container">
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1">
				<h1 class="text-center"><b>DEBIT NOTE</b></h1>
				<br>
			</div>
		</div>
		<div class="row">
			
				<div class="col-lg-8 col-sm-8 col-xs-8" style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px;-webkit-border-radius: 10px 10px 10px 10px;border: 1px solid #000000;height:130px;width:55%;padding-top:5px;margin-left:9px;">
					<table>
						<tr>
							<td><b>Bill To</b></td>
							<td><b>: <?=$customer->company;?></b></td>
						</tr>
						<tr>
							<td>Address</td>
							<td>: <?=$customer->address;?></td>
						</tr>
						<tr>
							<td>Att</td>
							<td><b>: <?=$customer->name;?></b></td>
						</tr>
						<tr>
							<td>Tel</td>
							<td><b>: <?=$customer->phone;?></b></td>
						</tr>
						<tr>
							<td>Email</td>
							<td>: <?=$customer->email;?></td>
						</tr>
					</table>
				</div>
				<div class="col-lg-4 col-ms-4 col-xs-4" style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px;-webkit-border-radius: 10px 10px 10px 10px;border: 1px solid #000000;height:130px;width:42%;margin-left:7px;padding-top:25px;">
					<table id="tb3">
						<tr>
							<td><b>No.</b></td>
							<td><b>: <?=$inv->reference_no;?></b></td>
						</tr>
						<tr>
							<td>Date</td>
							<td>: <?=$this->erp->hrsd($sale_order->date);?></td>
						</tr>
						<tr>
							<td>Ref DO No</td>
							<td>: <?=$sale_order->reference_no;?></td>
						</tr>
						<!--<tr>
							<td>VAT</td>
							<td>:  <?=$bill->vat_no;?></td>
						</tr>
						<tr>
							<td>Division</td>
							<td>: </td>
						</tr>-->
					</table>
				</div>
		</div>
		<br>
		<br>
		<div class="row">
			<div class="col-lg-12 col-ms-12 col-xs-12">
				<table class="table table-bordered table-hover table-striped print-table order-table">
					<thead style="border-collapse: collapse;text-align:center;" border="1">
						<th style="text-align:center;width:40px;">Item</th>
						<th style="text-align:center;width:256px;">Description</th>
						<th style="text-align:center;width:115px;">Unit</th>
						<th style="text-align:center;width:90px;">Qty</th>
						<th style="text-align:center;width:121px;">Unit Price</th>
						<?php if($inv->product_discount != 0){ ?>
						<th style="text-align:center;width:100px;">Discount</th>
						<?php } ?>
						<?php if($inv->product_tax != 0){ ?>
						<th style="text-align:center;width:100px;">Tax</th>
						<?php } ?>
						<th style="text-align:center;width:123px;">Amount</th>
					</thead>
					<tbody>
						<?php 
						$i = 1;
						$stotal = 0;
						$unit = "";
						$sub_total = 0;
						$qty = 0;
						$col=5;
						if($inv->product_discount !=0){
							$col= $col+1;
						}
						if($inv->product_tax !=0){
							$col= $col+1;
						}
						foreach($rows as $row){
							if($row->option_id == 0 || $row->option_id==""){
								$unit = $row->unit_name;
								$qty = $row->quantity;
							}else{
								$unit = $row->variant_name;
								$qty = $row->quantity;
							}
						?>
						<tr>
							<td style="text-align: center; vertical-align: top"><?=$i?></td>
							<td class="text-left" style="vertical-align: top">
								<?=$row->product_code.' - ';?>
								<strong><?=$row->pro_name; ?></strong>
							</td>
							<td style="text-align: center; vertical-align: top">
								<?=$unit;?>
							</td>
							<td style="text-align: center; vertical-align: top">
								<?=$qty;?>
							</td>
							<td>
								<div class="col-xs-1" style="text-align: left; vertical-align: top;">$</div>
								<div style="text-align: right; vertical-align: top"><?=$this->erp->formatMoney($row->unit_price);?></div>
							</td>
							<?php if($inv->product_discount != 0){ ?>
							<td>
								<div class="col-xs-1" style="text-align: left; vertical-align: top;">$</div>
								<div style="text-align: right; vertical-align: top"><?=$this->erp->formatMoney($inv->product_discount);?></div>
							</td>
							<?php }?>
							<?php if($inv->product_tax != 0){ ?>
							<td>
								<div class="col-xs-1" style="text-align: left; vertical-align: top;">$</div>
								<div style="text-align: right; vertical-align: top"><?=$this->erp->formatMoney($inv->product_tax);?></div>
							</td>
							<?php }?>
							<td>
								<div class="col-xs-1" style="text-align: left; vertical-align: top;">$</div>
								<div style="text-align: right; vertical-align: top"><?=$this->erp->formatMoney($qty*$row->unit_price);?></div>
							</td>
						</tr>
						<?php 
							$sub_total += $qty*$row->unit_price;
							$i++;
						}?>
						
					</tbody>
					<tfoot>
						<tr>
							<td colspan="<?=$col;?>" style="text-align: right; vertical-align: top">Sub Total</td>
							<td style="text-align: right; vertical-align: top">
								<div class="col-xs-1" style="text-align: left; vertical-align: top;">$</div>
								<div style="text-align: right; vertical-align: top"><?=$this->erp->formatMoney($sub_total);?></div>
							</td>
						</tr>
						<?php if($inv->order_discount !=0){?>
						<tr>
							<td colspan="<?=$col;?>" style="text-align: right; vertical-align: top">Order Discount</td>
							<td style="text-align: right; vertical-align: top">
								<div class="col-xs-1" style="text-align: left; vertical-align: top;">$</div>
								<div style="text-align: right; vertical-align: top"><?=$this->erp->formatMoney($inv->order_discount);?></div>
							</td>
						</tr>
						<?php }?>
						<?php if($inv->order_tax !=0){?>
						<tr>
							<td colspan="<?=$col;?>" style="text-align: right; vertical-align: top">Order Tax</td>
							<td style="text-align: right; vertical-align: top">
								<div class="col-xs-1" style="text-align: left; vertical-align: top;">$</div>
								<div style="text-align: right; vertical-align: top"><?=$this->erp->formatMoney($inv->order_tax);?></div>
							</td>
						</tr>
						<?php }?>
						<?php if($inv->shipping !=0){?>
						<tr>
							<td colspan="<?=$col;?>" style="text-align: right; vertical-align: top">Shipping</td>
							<td style="text-align: right; vertical-align: top">
								<div class="col-xs-1" style="text-align: left; vertical-align: top;">$</div>
								<div style="text-align: right; vertical-align: top"><?=$this->erp->formatMoney($inv->shipping);?></div>
							</td>
						</tr>
						<?php }?>
						<tr>
							<td colspan="2" style="text-align: center; vertical-align: top"><?=$this->erp->decode_html(strip_tags($deposit->note));?></td>
							<td></td>
							<td></td>
							<td></td>
							<?php if($inv->product_discount != 0){ ?>
							<td></td>
							<?php } ?>
							<?php if($inv->product_tax != 0){ ?>
							<td></td>
							<?php } ?>
							<td style="text-align: right; vertical-align: top">
								<div class="col-xs-1" style="text-align: left; vertical-align: top;">$</div>
								<div style="text-align: right; vertical-align: top"><?=$this->erp->formatMoney(($inv->paid - $discount) - abs($deposit->amount));?></div>
							</td>
						</tr>
						<tr>
							<td colspan="<?=$col;?>" style="text-align: right; vertical-align: top">Total amount w/o VAT (USD)</td>
							<td style="text-align: right; vertical-align: top">
								<div class="col-xs-1" style="text-align: left; vertical-align: top;">$</div>
								<div style="text-align: right; vertical-align: top"><?=$this->erp->formatMoney(($inv->paid - $discount) - abs($deposit->amount));?></div>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="row" style="margin-left:5px;">
			<div id='footer' class="col-lg-10 col-lg-offset-1">
				<p><b>Total Amount in word:  <?=$this->erp->numberToWordsCur(($inv->paid - $discount) - abs($deposit->amount),"");?></b></p>
				<p><b>Term of Payment: </b></p>
				<p></p>
				<div class="col-lg-12 col-xs-12">
					<div class="col-lg-6 col-xs-6 print">
						<p id='bank_detail'><u><b><i>Bank Detail:</i></b></u></p>
						<p id='bank_detail'><b><i>1. ABA Bank</i></b></p>
						<p style="margin-left:40px;" id='bank_detail'><i><b>Name: Charles Wembley (Cambodia) Pte.Ltd</b></i></p>
						<p style="margin-left:40px;" id='bank_detail'><i><b>Acct Number: 000149268</b></i></p>
					</div>
				</div>
				<div class="col-lg-12 col-xs-12">
					<div class="col-lg-4 col-xs-4">
						<p><b>Prepared by :</b></p>
						<br><br><br><br>
						<p><b>SOM CHANDAVY</b></p>
						<p>Finance & Admin Manager</p>
					</div>
					<div class="col-lg-4 col-xs-4">
						
					</div>
					<div class="col-lg-4 col-xs-4">
						<p><b>Acknowledged by:</b></p>
						<br><br><br><br>
						<p>Customer`s Name:</p>
						<p>Date: ........../.........../...........</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>