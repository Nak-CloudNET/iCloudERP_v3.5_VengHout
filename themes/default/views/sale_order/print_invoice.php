<!-- <?php //echo "hello";exit();?> -->
<!doctype>
<html>
	<head>
		<title>RULE</title>
		<meta charset="utf-8">
		<style>
			@media print{
			
				#tb tr th{
					background-color: #DCDCDC !important;
				}
				#body{
					width:1000px;
					height:100%;
					margin:0 auto;
					background:#fff !important;
				}
				#print{
					display:none;
				}
				#foot{
					width:100%;
					background:#fff !important;
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
				background:#F0F8FF;
				
				
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
			#top2_l{
				width:30%;
				margin:0 auto;
				text-align:left;
				
				float:left;
				height:150px;
			}
			#top2_c{
				width:30%;
				margin:0 auto;
				text-align:center;
				height:150px;
				
				float:left;
			}
			#top2_r{
				width:30%;
				margin:0 auto;
				text-align:left;
				
				float:left;
				height:210px;
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
				background:#F0F8FF;	
			}		
			#tb2 tr td,#tb3 tr td{
				font-size:15px;
				border-radius:20px;
				font-family:"Arial Narrow";
				text-align:left;
				padding-left:10px;
			}
			
		</style>
	</head>
	<body>
	
	<div id="body">	
	<button id="print" onclick="window.print()">
		<img src="<?= base_url() . 'assets/uploads/printer.png'; ?>">
	</button>	
			<div id="top2">
				<h1><b>DEBIT NOTE</b></h1>
				<br>
				<div style="float:left; width:50%;border-radius: 10px 10px 10px 10px;
-moz-border-radius: 10px 10px 10px 10px;
-webkit-border-radius: 10px 10px 10px 10px;
border: 1px solid #000000;">
					<table id="tb2" style=" width:100%;">
						<tr>
							<td>Bill To</td>
							<td>: <?php echo $bill->company; ?> </td>
						</tr>
						<tr>
							<td>Address</td>
							<td>: <?php echo $bill->address; ?> </td>
						</tr>
						<tr>
							<td>Att</td>
							<td>: <?php echo $bill->name; ?> </td>
						</tr>
						<tr>
							<td>HP</td>
							<td>: <?php echo $bill->phone; ?> </td>
						</tr>
						<tr>
							<td>Email</td>
							<td>: <?php echo $bill->email; ?> </td>
						</tr>
					</table>
				</div>
				<div style="float:right; width:40%;border-radius: 10px 10px 10px 10px;
	-moz-border-radius: 10px 10px 10px 10px;
	-webkit-border-radius: 10px 10px 10px 10px;
	border: 1px solid #000000;">
					<table id="tb3" style=" width:100%;">
						<tr>
							<td>No.</td>
							<td>: <?php echo $invs->reference_no; ?></td>
						</tr>
						<tr>
							<td>Date</td>
							<td>: <?php echo $this->erp->hrsd($invs->date); ?></td>
						</tr>
						<tr>
							<td>Ref DO No</td>
							<td>: <?php if(isset($ref->do_reference_no)?$ref->do_reference_no:""); ?></td>
						</tr>
					</table>
				</div>
			</div>
			
			<table id="tb" style="border-collapse: collapse;text-align:center;" border="1" >
				<tr>
					<th style="text-align:center;">Item</th>
					<th style="text-align:center;">Description</th>
					<th style="text-align:center;">Unit</th>
					<th style="text-align:center;">Qty</th>
					<th style="text-align:center;width:150px;">Unit Price</th>
					<th style="text-align:center;width:150px;">Discount</th>
					<?php if ($Settings->tax1) { ?>
						<th style="text-align:center;width:150px;">Tax</th>
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
							$unit = $row->uname;
							$qty = $row->quantity;
						}else{
							$unit = $row->variant;
							$qty = $row->quantity;
						}
					
				?>
				<tr>
					<td><?=$i?></td>
					<td><?=$row->product_name?></td>
					<td style="text-align:left;"><?=$unit;?></td>
					<td><?=$this->erp->formatDecimal($qty);?></td>
					<td><?=$this->erp->formatMoney($row->unit_price);?> </td>
					<?php
						echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount*$qty) . '</td>';
						if ($Settings->tax1) {
							echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
						}
					?>
					<td style="text-align:right;"><?=$this->erp->formatMoney($row->subtotal)?> $</td>
					
					
				</tr>
				<?php
				$i++;
				
				$stotal +=$qty*$row->unit_price;
					}
					for($k = 0;$k<5;$k++){
				?>
				<tr class="blank">
					<td><?=$a?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<?php 
						if($Settings->tax1 != 1){
					?>
						<td></td>
					?>
					<?php
						}else if($Settings->tax1 ==1){
					?>
						<td></td>
						<td></td>
					<?php
						}
					?>
					<td style="text-align:right;">$</td>
				</tr>
				
				<?php 
				$i++;
					}
					?>
				<?php 
					if($Settings->tax1 == 0){
						$cols = 6;
					}else if($Settings->tax1 ==1){
						$cols = 7;
					}
				?>
				<?php if($invs->order_discount != 0){?>
				<tr>
					<td colspan="<?= $cols ?>"  style="text-align:right;">Special Discount</td>
					<td style="text-align:right;"><?=$this->erp->formatMoney($invs->order_discount);?> $</td>
					
				</tr>
				<?php }?>
				<?php if($invs->shipping !=0){?>
				<tr>
					<td colspan="<?= $cols ?>"  style="text-align:right;">Shipping</td>
					<td style="text-align:right;"><?=$this->erp->formatMoney($invs->shipping);?> $</td>
					
				</tr>
				<?php }?>
				<?php if($invs->order_tax !=0){?>
				<tr>
					<td colspan="<?= $cols ?>"  style="text-align:right;">Order Tax</td>
					<td style="text-align:right;"><?=$this->erp->formatMoney($invs->order_tax);?> $</td>
					
				</tr>
				<?php }?>
				<?php if($invs->order_discount !=0 || $invs->paid !=0 || $invs->shipping !=0 || $invs->order_tax !=0){?>
				<tr>
					<td colspan="<?= $cols ?>"  style="text-align:right;"> Sub Total</td>
					<td  style="text-align:right;"><?=$this->erp->formatMoney($invs->grand_total);?> $</td>
				</tr>
				<? } ?>
				<?php if($deposit->deposit > 0){?>
					<tr>
						<td colspan="<?= $cols ?>" style="text-align:right;"><?= lang("Deposit"); ?>
						</td>
						<td style="text-align:right;"><?= $this->erp->formatMoney($deposit->deposit); ?> $</td>
					</tr>
					
					<tr>
						<td colspan="<?= $cols ?>" ​ style="text-align:right;" >Balance</td>
						<td   style="text-align:right;" ><?=$this->erp->formatMoney($invs->grand_total-$deposit->deposit);?> $</td>
					</tr>
				<?php } ?>
			
			</table>
			<p>Total Amount inword: <?=$this->erp->convert_number_to_words($stotal-$invs->order_discount);?></p>
			<p>Term of Payment: COD</p>
			<p><?=(isset($invs->invoice_footer)?$invs->invoice_footer:"");?></p>
			<h5><u>Bank Detail:</u></h5>
			<p>&nbsp;&nbsp;&nbsp;Name: ..............</p>
			<p>&nbsp;&nbsp;&nbsp;Acct Number: ..............</p>
			<br>
			<div id="foot">
			<div id="top2_l">
				
				<p>Prepared By :</p>
				<br><br><br><br>
				<p><b>SOM CHANDAVY</b></p>
				<p>Finance & Admin Manager</p>
			</div>
			
			<!--<div id="top2_c">
				<p>បានពិនិត្យ ត្រឹមត្រូវ</p>
				<p>ប្រធានការិយាល័យគណនេយ្យ</p>
			</div>
			<div id="top2_c">
				<p>អ្នកគ្រប់គ្រងស្ដុក</p>
				
			</div>-->
			<div id="top2_c">
			<!--<p>អនុម័តដោយ</p>
				<p>Approved By :</p>-->
				
			</div>
			<div id="top2_r">
				
				<p>Acknowledged By:</p>
				<br><br><br><br>
				<p>Customer`s Name:</p>
				<p>Date: ............./.............../.................</p>
			</div>
			</div>
		</div>
	</div>	
		
	</body>
</html>