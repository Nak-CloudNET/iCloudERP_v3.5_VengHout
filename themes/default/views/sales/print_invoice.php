<!doctype>
<html>
	<head>
		<title>RULE</title>
		<meta charset="utf-8">
		<link href="<?php echo $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet">
		<style>
			@media print{
				footer{
					display: none !important;
				}
				.col-lg-12 .print p{
					font-size: 11px !important;
				}
				#tb tr th{
					background-color: #DCDCDC !important;
				}
				#body{
					width:1000px;
					height:100%;
					margin:0 auto;
				}
				#print{
					display:none;
				}
				.fon{
					color: rgba(0, 0, 0, 0.3) !important;
				}
				.left_ch{
					 left: 80px !important;
				}
			}
			#print{
				
				width:60px;
				height:45px;
				border:0px;
				background: #4169E1;
				color:#fff;
				cursor:pointer;
				-webkit-box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.75);
box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.75);
			}
			#body,h2,h3,h4,h5,p{
				margin:0px;
				padding:5px;
				
			}
			
			#body{
				width:95%;
				height:100%;
				margin:0 auto;
			}		

		
			#top{
				width:95%;
				height:100px;
				margin:0 auto;
				
				padding-top:20px;
			}
			#top_l{
				width:220px;
				float:left;
			}
			#top_r{
				width:200px;
				float:right;
				text-align:center;
			}
			h1,h2,h3,h4{
				font-family:"Khmer OS Muol";
			}
			
			p{
				font-size:15px;
				font-family:"Arial Narrow";
			}
			#top2{
				width:95%;
				margin:0 auto;
				text-align:center;
				height:170px;
				
				margin-bottom:10px;
			}

			#top2_c{
				width:30%;
				margin:0 auto;
				text-align:center;
				height:150px;
				
				float:left;
			}
			#top2 h5{
				font-family:"Khmer OS Muol";
			}
			#tb tr th{
				font-size:16px;
				padding:5px;
				font-family:"Arial Narrow";
				 font-weight: bold;
			}
			#tb tr td{
					font-size:15px;
					padding:4px;
					font-family:"Arial Narrow";
				}
				#tb {
					width:98%;
					margin:0 auto;
				}
				#foot{
				width:100%;
				height:150px;
			}		
			#tb2 tr td,#tb3 tr td{
				font-size:15px;
				border-radius:20px;
				font-family:"Arial Narrow";
				text-align:left;
				padding-left:10px;
			}

			.thead th {
				background-color: #CCC;
			}
			#bank_detail{
				font-size: 13px;
			}
			
		</style>
	</head>
	<body>
	
	<div id="body">	
	<button id="print" onclick="window.print()">
		<img src="<?= base_url() . 'assets/uploads/printer.png'; ?>">
	</button>
		<!--<div class="left_ch" style="
		width:700px;
		text-align:center;
		 position: absolute;
    left: 300px;
    top: 580px;
		z-index:1;
	-webkit-transform: rotate(350deg);
-moz-transform: rotate(350deg);
-o-transform: rotate(350deg);
writing-mode: lr-tb;
		">
			<span class="fon" style="font-size:40px;font-family:Khmer OS;
color: rgba(0, 0, 0, 0.3);
			" >វិក័យប័ត្រនេះមិនអាចប្រកាសជា<br>បន្ទុកចំណាយបានទេ</span>
		</div>-->
		
		<div class="row container" style="margin-left:5px;">
			<!--<h1>ខេ អិន អិន លក់ រថយន្ដ និង គ្រឿងចក្រ</h1>
			<h1>KNN Cambodia Co., Ltd (KNN Group)</h1>-->
			
				<h1 class="text-center"><b>DEBIT NOTE</b></h1>
				<br>
				<div style="border-radius: 10px 10px 10px 10px;
	-moz-border-radius: 10px 10px 10px 10px;
	-webkit-border-radius: 10px 10px 10px 10px;
	border: 1px solid #000000;height:110px;width:55%;padding-top:5px;" class="col-xs-8">
					<table>
						<tr>
							<td><b>Bill To</b></td>
							<td><b>: <?=$invs->biller;?></b></td>
						</tr>
						<tr>
							<td>Address</td>
							<td>: <?=$invs->address;?></td>
						</tr>
						<tr>
							<td>Att</td>
							<td><b>: <?=$invs->att;?></b></td>
						</tr>
						<tr>
							<td>Tel</td>
							<td><b>: <?=$invs->phone;?></b></td>
						</tr>
						<tr>
							<td>Email</td>
							<td>: <?=$invs->email;?></td>
						</tr>
					</table>
				</div>
				<div style="border-radius: 10px 10px 10px 10px;
	-moz-border-radius: 10px 10px 10px 10px;
	-webkit-border-radius: 10px 10px 10px 10px;
	border: 1px solid #000000;height:110px;width:42%;margin-left:10px;padding-top:25px;" class="col-xs-4">
					<table id="tb3">
						<tr>
							<td><b>No.</b></td>
							<td><b>: <?=$sales->reference_no;?></b></td>
						</tr>
						<tr>
							<td>Date</td>
							<td>: <?=$this->erp->hrsd($invs->date);?></td>
						</tr>
						<tr>
							<td>Ref DO No</td>
							<td>: <?=$invs->reference_no;?></td>
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
			<table id="tb" style="border-collapse: collapse;text-align:center;" border="1" >
				<tr class="thead">
					<th style="text-align:center;">Item</th>
					<th style="text-align:center;">Description</th>
					<th style="text-align:center;">Unit</th>
					<th style="text-align:center;">Qty</th>
					<th style="text-align:center;width:150px;">Unit Price</th>
					<?php if($invs->product_discount != 0){ ?>
					<th style="text-align:center;width:130px;">Discount</th>
					<?php } ?>
					<th style="text-align:center;">Amount</th>
					
				</tr>
				<?php 
				$i = 1;
				$stotal = 0;
				$unit = "";
				
				$qty = 0;
					foreach($rows as $row){
						if($row->option_id == 0 || $row->option_id==""){
							$unit = $row->units;
							$qty = $row->quantity;
						}else{
							$unit = $row->vname;
							$qty = $row->qty_unit*$row->quantity;
						}
					
				?>
				<tr>
					<td style="text-align: center; vertical-align: top"><?=$i?></td>
					<td class="text-left">
						<strong><?=$row->description; ?></strong>
						<?=$row->product_details; ?>
					</td>
					<td style="text-align: center; vertical-align: top"><?=$unit;?></td>
					<td style="text-align: center; vertical-align: top"><?=$this->erp->formatDecimal($qty);?></td>
					<td style="text-align: right; vertical-align: top">
						<div class="row">
							<div class="col-sm-6 col-xs-6 text-left">$</div>
							<div class="col-sm-6 col-xs-6"><?=$this->erp->formatMoney($row->unit_price);?></div>
						</div>
					</td>
					<?php if($invs->product_discount != 0){ ?>
						<td style="text-align: right; vertical-align: top">
							<div class="row">
								<div class="col-sm-6 col-xs-6 text-left">$</div>
								<div class="col-sm-6 col-xs-6"><?=$this->erp->formatMoney($invs->product_discount)?></div>
							</div>
						</td>
					<?php } ?>
					<td style="text-align: right; vertical-align: top">
						<div class="row">
							<div class="col-sm-6 col-xs-6 text-left">$</div>
							<div class="col-sm-6 col-xs-6"><?=$this->erp->formatMoney($qty*$row->unit_price-$invs->product_discount)?></div>
						</div>
					</td>
					
				</tr>
				<?php
				$i++;
			
				$stotal +=$qty*$row->unit_price-$invs->product_discount;
					}
				?>
				<tr>
					<?php  if($invs->product_discount != 0){ ?>
						<td colspan="6"  style="text-align:right;"> Sub Total</td>
					<?php  }else{?>
						<td colspan="5"  style="text-align:right;"> Sub Total</td>
					<?php  }?>
					<td  style="text-align:right;">
						<div class="row">
							<div class="col-sm-6 col-xs-6 text-left">$</div>
							<div class="col-sm-6 col-xs-6"><?=$this->erp->formatMoney($stotal);?></div>
						</div>
					</td>
					
				</tr>
				<tr>
					<?php  if($invs->product_discount != 0){ ?>
						<td colspan="6"  style="text-align:right;">Special Discount <?=$invs->order_discount_id .'%';?></td>
					<?php  }else{?>
					<td colspan="5"  style="text-align:right;">Special Discount <?=$invs->order_discount_id;?></td>
					<?php  }?>
					<td style="text-align:right;">
						<div class="row">
							<div class="col-sm-6 col-xs-6 text-left">$</div>
							<div class="col-sm-6 col-xs-6"><?=$this->erp->formatMoney($invs->order_discount);?></div>
						</div>
					</td>
					
				</tr>
				<?php if($invs->order_tax != 0){ ?>
				<tr>
					<?php  if($invs->product_discount != 0){ ?>
						<td colspan="6"  style="text-align:right;">Order Tax </td>
					<?php  }else{?>
					<td colspan="5"  style="text-align:right;">Order Tax </td>
					<?php  }?>
					<td style="text-align:right;">
						<div class="row">
							<div class="col-sm-6 col-xs-6 text-left">$</div>
							<div class="col-sm-6 col-xs-6"><?=$this->erp->formatMoney($invs->order_tax);?></div>
						</div>
					</td>
					
				</tr>
				<?php } ?>
				<?php if($invs->shipping != 0){ ?>
				<tr>
					<?php  if($invs->product_discount != 0){ ?>
						<td colspan="6"  style="text-align:right;">Shipping </td>
					<?php  }else{?>
					<td colspan="5"  style="text-align:right;">Shipping </td>
					<?php  }?>
					<td style="text-align:right;">
						<div class="row">
							<div class="col-sm-6 col-xs-6 text-left">$</div>
							<div class="col-sm-6 col-xs-6"><?=$this->erp->formatMoney($invs->shipping);?></div>
						</div>
					</td>
					
				</tr>
				<?php } ?>
				<?php if($invs->product_discount != 0){ ?>
				<tr>
					<?php  if($invs->product_discount != 0){ ?>
						<td colspan="6" ​ style="text-align:right;" >Total Amount (USD)</td>
					<?php  }else{?>
						<td colspan="5" ​ style="text-align:right;" >Total Amount (USD)</td>
					<?php  }?>
					<td   style="text-align:right;" >
						<div class="row">
							<div class="col-sm-6 col-xs-6 text-left">$</div>
							<div class="col-sm-6 col-xs-6"><?=$this->erp->formatMoney($stotal-$invs->order_discount+$invs->shipping+$invs->order_tax);?></div>
						</div>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<?php $rate = ($dp->deposit*100)/$stotal;?>
					<td colspan="2" ​ style="text-align:center;"><?= $this->erp->decode_html(strip_tags($invs->sale_note));?></td>
					<td></td>
					<td></td>
					<td></td>
					<?php if($invs->product_discount != 0){ ?>
					<td></td>
					<?php } ?>
					<td   style="text-align:right;" >
						<div class="row">
							<div class="col-sm-6 col-xs-6 text-left">$</div>
							<div class="col-sm-6 col-xs-6"><?=$invs->total_amount;?></div>
						</div>
					</td>
				</tr>
				<?php if($dp->paid != 0){ ?>
				<tr>
					<?php  if($invs->product_discount != 0){ ?>
						<td colspan="6"  style="text-align:right;">Paid </td>
					<?php  }else{?>
					<td colspan="5"  style="text-align:right;">Paid </td>
					<?php  }?>
					<td style="text-align:right;">
						<div class="row">
							<div class="col-sm-6 col-xs-6 text-left">$</div>
							<div class="col-sm-6 col-xs-6"><?=$this->erp->formatMoney($dp->paid);?></div>
						</div>
					</td>
					
				</tr>
				<?php } ?>
				<tr>
					<?php  if($invs->product_discount != 0){ ?>
						<td colspan="6" ​ style="text-align:right;background-color:#ccc;" ><b>Total amount w/o VAT (USD)</b></td>
					<?php  }else{?>
						<td colspan="5" ​ style="text-align:right;background-color:#ccc;" ><b>Total amount w/o VAT (USD)</b></td>
					<?php  }?>
					<td   style="text-align:right;background-color:#ccc;" >
						<div class="row">
							<div class="col-sm-6 col-xs-6 text-left"></div>
							<div class="col-sm-6 col-xs-6"><b><?=$invs->total_amount?></b></div>
						</div>
					</td>
				</tr>
			</table>
			<p><b>Total Amount in word: <?=$this->erp->convert_number_to_words($invs->total_amount);?></b></p>
			<p><b>Term of Payment: <?php echo strip_tags($invs->note); ?></b></p>
			<p><?=(isset($invs->invoice_footer)?$invs->invoice_footer:"");?></p>
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
	</body>
</html>