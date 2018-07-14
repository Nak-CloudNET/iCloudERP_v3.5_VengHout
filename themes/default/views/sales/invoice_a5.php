<!DOCTYPE html>
<html>

	<head>
		<title>Invoice</title>
		<meta charset="utf-8">
		<link href="<?php echo $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet">
		<style>
			table {
				border-collapse: collapse;
				width:100%;
				font-size:11px;
			}

			table, th, td {
				border: 1px solid black;
				border:1px solid #999;
			}
			table, td.td{
				border:none !important;
			}
			.table thead tr.tr {
				background-color:#E9EBEC;
				border:1px solid #999;
				border-collapse: collapse;
			}
			.foot-left{
				padding:5px;
			}
			.foot-right{
				padding:5px;
				width:99%;
			}
			.list{
				font-size:10px;
			}
			.frt{
				font-weight:bold;
			}
			.moneyshow{
				text-align:center;
				padding:0px;
				border-bottom:dotted 1px black;
			}
			ul.list-right{
				list-style-type: none;
				padding-left:0;
				line-height:2;
				width:100%;
			}
			.hidden{
				display:none;
			}
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
					height:100%;
					margin:0 auto;
				}
				#print{
					display:none;
				}
				.fon{
					color: rgba(0, 0, 0, 0.3) !important;
				}
				
	
			}
			.box1 {
				border: 1px solid #000000;
				border-radius: 10px;
				height: 110px;
				padding: 25px;
				width: 36%;
				font-size:11px;
			}
			.box2{
				border-radius: 10px 10px 10px 10px;
				-moz-border-radius: 10px 10px 10px 10px;
				-webkit-border-radius: 10px 10px 10px 10px;
				border: 1px solid #000000;height:110px;
				width:36%;
				padding: 25px;
				font-size:11px;
			}
			.box3{
				height: 110px;
				width: 28%;
			}
			.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
				border-top: 1px solid #ddd;
				line-height: 1.42857;
				padding: 5px;
				vertical-align: top;
			}
			th{
				text-align:center;
			}
		
		</style>
	</head>
	<body>
		<div class="container">
			<h2 class="text-center"><b>Tea Try IT</b></h2>
			<br>
			<div class="col-xs-12">
				<div class="box1 col-xs-3">
					<div>
						<div>
							<span><b>Customer</b></span><span>: <?=$customer->company;?></span>
						</div>
						<div>
							<span><b>Address</b></span><span>: <?=$customer->address;?></span>
						</div>
						<div>
							<span><b>Tel/Fax</b></span><span>: <?=$customer->phone;?></span>
						</div>
					</div>
				</div>
				<div class="box3 col-xs-6"></div>
				<div class="box2 col-xs-3">
					<div>
						<div>
							<span><b>Date</b></span><span>: <?=$this->erp->hrsd($inv->date);?></span>
						</div>
						<div>
							<span><b>Invoice no</b></span><span>: <?=$inv->reference_no?></span>
						</div>
					</div>	
				</div>
			</div>
		</div>
		<div class="container">
			<table class="table table-bordered table-hover table-striped" style="width: 100%;" >
				<br>
				 <thead  style="font-size: 12px;">
					<tr>
						<th style="width:0px;">ល.រ</br>Nº</th>
						<th style="width:0px;">លេខកូដទំនិញ<br>Product code</th>
						<th>បិរយាយ<br>Description</th>
						<th>ខ្នាត<br>Unit</th>
						<th>ចំនួន<br>Qty</th>
						<th>តំលៃ<br>Unit Price</th>
						<?php 
						if ($Settings->product_discount) {
							echo '<th>បញ្ចុះតំលៃ<br>Discount</th>';
						}
					   ?>
						<th>សរុប<br>Amount</th>
					</tr>
				</thead>
				<tbody style="font-size: 13px;">
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
						<td><center><?= $i;?></center></td>
						<td><?=$row->product_code?></td>
						<td><?=$row->product_name?></td>
						<td><center><?=$row->uname?></center></td>
						<td style="text-align:center;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
						<td style="text-align:center;">
							<?php if($row->unit_price == 0){echo "Free";}else{echo $this->erp->formatMoney($row->unit_price);} ?>
						</td>
						<?php
							if ($Settings->product_discount){
									echo '<td class="border" style="width: 100px; text-align:center; vertical-align:middle;">' . ($rows[$i]->discount != 0 ? '<small>(' . $rows[$i]->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
							}
						?>
						<td style="text-align:center;">
						<?php if($row->unit_price == 0){echo "Free";}else{ echo $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$t; ?>&nbsp<?php echo $sym;}?> </td>		
					</tr>
					<?php
					$i++;
				
					$stotal +=$qty*$row->unit_price;
						}
						for($k = 0;$k<5;$k++){
					?>
					<tr class="blank">
						<td><center><?=$i?><center></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				
					<?php 
					$i++;
						}
						?>
				</tbody>
				<tfoot style="font-size: 13px;">
					<tr class="footer">
						<td colspan="4">
							<div class="foot-left">
								<h4>Additional Remark</h4>
								<ul class="list" style="line-height:1.8">
									<li>ទំនិញទិញហើយមិនអាចប្តូរយកប្រាក់វិញបានឡើយ.</li>
									<li>អ្នកទិញត្រូវរាប់និងពិនិត្យទំនិញឲបានត្រឺមត្រូវ មុនចុះហត្ថលេខាទទួល.</li>
									<li>ទំនិញដែលប្រើសល់អាចសង់ចូលវិញបានតែក្នុងរយះពេល ៣០ថ្ងៃចាប់ ពីថ្ងៃទទួលទំនិញ.</li>
									<li>ចំពោះទំនិញដែលបានកម្មង់គឺមិនអាចសង់ចូលវិញបានទេ.</li>
									<li>តំលៃទំនិញទាំងអស់នេះប្រាក់ពន្ធបន្ថែម VAT 10%​ ជាបន្ទុករបស់នាក់ទិញ.</li>
								</ul>
								<div style="margin-top:93px;font-size:11px;"><p style=""></p>
									<p style="text-align:center">Customer Name And Signature</p>
									<p style="">Date :.........../............/............/&nbsp;&nbsp;Phone:.........................................................</p>
								</div>
							</div>
						</td>
						<td colspan="4">
							<div class="foot-right">
								<ul class="list-right">
									<li>
										<span class="frt"><?= lang("grand_total") ?> :</span>
										<span class="moneyshow"> <?=$this->erp->formatMoney($total)?> $</span>
									</li>
									<li>
										<span class="frt"><?= lang("paid") ?> :</span>
										<span class="moneyshow"> <?=$this->erp->formatMoney($inv->paid)?> $</span>
									</li>
									<li>
										<span class="frt"><?= lang("balance") ?> :</span>
										<span class="moneyshow"> <?=$this->erp->formatMoney($inv->grand_total-$inv->paid);?> $</span>
									</li>
									<li>
										<span class="frt"><?= lang("amount_in_word") ?> :</span>
										<span class="moneyshow">
											<?= $this->erp->convert_number_to_words($inv->grand_total-$inv->paid); ?>
										</span>
									</li>
								</ul>
								<p style="border-top:dotted 1px black;"></p>
								<div style="margin-top:122px;font-size:11px;line-height:2.9"><p style=""></p>
									<p style="border-bottom:dotted 1px black;"></p>
									<p style="font-size:11px;">Authorized Signature <span style="padding-left:50px;">Seller</span></p>
								</div>
							</div>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</body>
</html>