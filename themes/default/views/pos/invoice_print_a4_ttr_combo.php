<!DOCTYPE html>
<html>

	<head>
		<title>Invoice</title>
		<meta charset="utf-8">
		<link href="<?php echo $assets ?>styles/helpers/bootstrap.min-inv.css" rel="stylesheet">
		<script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
		<style>
			table {
				border-collapse: collapse;
				width:100%;
				font-size:11px;
			}

			table, th, td {
				border: 1px solid black;
				border:1px solid #000000;
			}
			table, td.td{
				border:none !important;
			}
			.table thead tr.tr {
				background-color:#E9EBEC;
				border:1px solid #000000;
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
				font-size:14px;

			}
			.bold{font-weight:bold;border-bottom:dotted 2px black !important;}
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
				.bottom-print{display:none;}
			}
			.box1 {
				border: 1px solid #000000;
				height: 95px;
				padding: 20px;
				width: 50%;
				font-size:11px;
			}
			.box2{
				border: 1px solid #000000;
				height:95px;
				width:45%;
				padding: 20px;
				font-size:11px;
			}
			.box3{
				height: 95px;
				width: 5%;
			}
			.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
				border:1px solid #000000;
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
		<div class="container bottom-print">
			<div class="text-center" style="padding:10px;">
				<button class="btn btn-xs btn-default no-print pull-left" onclick="window.print()"><i class="fa fa-print"></i>&nbsp;<?= lang("print"); ?></button>
			</div>
		</div>
		<div class="container">
			<h2 class="text-center"><b style="float:left">TTR II</b><b>វិក័យប័ត្រ /  Invoice</b></h2>
			<br>
			<div class="col-xs-12">
				<div class="box1 col-xs-3">
					<div style="font-size: 12px;">
						<div>
							<span><b>អតិថិជន​ / Customer</b></span><span>: <?=$customer->company;?></span>
						</div>
						<div>
							<span><b>អាស័យដ្ឋាន / Address</b></span><span>: <?=$customer->address;?></span>
						</div>
						<div>
							<span><b>លេខទូរស័ព្ទ / Phone No</b></span><span>: <?=$customer->phone;?></span>
						</div>
					</div>
				</div>
				<div class="box3 col-xs-6"></div>
				<div class="box2 col-xs-3">
					<div style="font-size: 12px;">
						<div>
							<span><b>ថ្ងៃខែ​ឆ្នាំ / Date</b></span><span>: <?=$this->erp->hrsd($inv->date);?></span>
						</div>
						<div>
							<span><b>លេខ​វិ​ក័​យ​ប័ត្រ / Invoice no</b></span><span style="font-weight:bold;">: <?=$inv->reference_no?></span>
						</div>
						<div>
							<span><b>PO</b></span><span style="font-weight:bold;">: <?=$inv->po?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="col-xs-12">
				<table class="table table-bordered table-hover table-striped" id="table-bordered" style="width: 100%;" >
					<br>
						<body>
							<tr style="font-size: 12px;border-top: 1px solid #ddd;">
								<th style="width:0px;">ល.រ</br>Nº</th>
								<th style="width:0px;">លេខកូដទំនិញ<br>Product code</th>
								<th>ឈ្មោះ<br>Product Name</th>
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
						</body>
						<body class="body_append">
							<?php
							$i = 1;
							$stotal = 0;
							$unit = "";
							$comboLoop = "";
							$qty = 0;
								foreach($rows as $row){     
									//$this->erp->print_arrays($rows);
									if($row->option_id == 0 || $row->option_id==""){
										$unit = $row->uname;
										$qty = $row->quantity;
									}else{
										$unit = $row->variant;
										$qty = $row->quantity;
									}
									
									
										
										
									
									
							?>
								<tr class="blank" style="font-size: 13px;border-bottom: 1px solid #ddd;">
									<td><center><?= $i;?></center></td>
									<td><?=$row->product_code?>
									<?php 
										if($row->product_type === 'combo')
											{
												$this->db->select('*, (select name from erp_products p where p.id = erp_combo_items.product_id) as p_name ');
												$this->db->where('erp_combo_items.product_id = "' . $row->product_id . '"');
												$comboLoop = $this->db->get('erp_combo_items');
												
												$c = 1;
												$cTotal = count($comboLoop->result());
													foreach ($comboLoop->result() as $val) {
														echo '<div style="margin-left:10px;"' . ($c === $cTotal ? 'class="item"' : '') . '>';
																echo $c . '. ' . $val->item_code;
														echo '</div>';
														$c++;
													}
													
												}
										?>
									</td>
									<td><?=$row->product_name?>
										<?php 
										if($row->product_type === 'combo')
											{
												$this->db->select('*, (select name from erp_products p where p.id = erp_combo_items.product_id) as p_name ');
												$this->db->where('erp_combo_items.product_id = "' . $row->product_id . '"');
												$comboLoop = $this->db->get('erp_combo_items');
												
												$c = 1;
												$cTotal = count($comboLoop->result());
													foreach ($comboLoop->result() as $val) {
														echo '<div style="margin-left:20px;"' . ($c === $cTotal ? 'class="item"' : '') . '>';
																echo $c . '. ' . $val->p_name;
														echo '</div>';
														$c++;
													}
													
												}
										?>
										
										
									</td>
									<td><center><?=$unit?></center></td>
									<td style="text-align:center;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
									<td style="text-align:center;">
										<?php if($row->unit_price == 0){echo "Free";}else{echo '$'.$this->erp->formatMoney($row->unit_price);} ?>
									</td>
									<?php
										if ($Settings->product_discount){
												echo '<td class="border" style=" text-align:center;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
										}
									?>
									<td style="text-align:center;">
									<?php if($row->unit_price == 0){echo "Free";}else{ echo $row->subtotal!=0?'$'.$this->erp->formatMoney($row->subtotal):$t; ?>&nbsp<?php echo $sym;}?> </td>
								</tr>
								<?php
								$i++;

								$stotal +=$qty*$row->unit_price;
									}
									for($k = 0;$k<0;$k++){
								?>
		
							<?php
							$i++;
								}
								?>
								
						</tbody>
						<tfoot>
						<tr>
							<td colspan="8" style="height:70px">
								<p style="width:45px;font-size:14px;font-weight:bold">Note :</p>
								<?php
									if($inv->note !="")
									{
										echo "<p style='font-size:12px;'>".$inv->note."</p>";
									}else{

									}
								?>

							</td>
						</tr>
						<tr style="font-size: 13px;" class="footer">
							<td colspan="8">
								<table style="border: none !important;" class="footer">
									<tr style="font-size: 13px;" class="footer">
										<td colspan="4">
											<div class="foot-left">
												<h4>Additional Remark</h4>
												<ul class="list" style="line-height:1.8">
													<li>ទំនិញទិញហើយមិនអាចប្តូរយកប្រាក់វិញបានឡើយ.</li>
													<li>អ្នកទិញត្រូវរាប់និងពិនិត្យទំនិញឲ្យបានត្រឺមត្រូវ មុនចុះហត្ថលេខាទទួល.</li>
													<li>ចំពោះទំនិញដែលបានកម្មង់គឺមិនអាចសងចូលវិញបានទេ.</li>
													<li>តំលៃទំនិញទាំងអស់នេះប្រាក់ពន្ធបន្ថែម VAT 10%​ ជាបន្ទុករបស់អ្នកទិញ.</li>
												</ul>
											</div>
										</td>
										<td colspan="3">
											<div class="foot-right" style="width: 322px;">
												<ul class="list-right">
													<?php
														if($inv->shipping !=0)
														{
															?>
																<li>
																	<span class="frt">ការដឹកជញ្ជូន / <?= lang("shipping") ?> :</span>
																	<span class="moneyshow"> <?=$this->erp->formatMoney($inv->shipping)?> $</span>
																</li>
															<?php
														}
													?>
													<li>
														<span class="frt">សរុប / <?= lang("total") ?> :</span>
														<span class="moneyshow"> <?=$this->erp->formatMoney($inv->grand_total)?> $</span>
													</li>
													<li>
														<span class="frt">បញ្ចុះតម្លៃ / <?= lang("discount") ?> :</span>
														<span class="moneyshow"> <?=$this->erp->formatMoney($inv->order_discount)?> $</span>
													<li>
													<li>
														<span class="frt">ប្រាក់កក់​ / <?= lang("deposited") ?> :</span>
														<span class="moneyshow"> <?=$this->erp->formatMoney($inv->paid)?> $</span>
													</li>
													<li>
														<span class="frt">ប្រាក់នៅខ្វះ / <?= lang("balance") ?> :</span>
														<span class="moneyshow bold"> <?=$this->erp->formatMoney($inv->grand_total-$inv->paid);?> $</span>
													</li>
													<li>
														<span class="frt"><?= lang("amount_in_word") ?> :</span>
														<span class="moneyshow">
															<?= $this->erp->convert_number_to_words($inv->grand_total-$inv->paid); ?>
														</span>
													</li>
												</ul>

											</div>
										</td>
									</tr>
									<tr style="font-size: 13px;" class="footer" >
										<td colspan="8">
											<div style="margin-top:60px;font-size:11px;width:100%">
												<div style="float:left;width:25%">
													<p style="text-align:center">.......................................</p>
													<p style="text-align:center">អ្នកទិញ / BUYER</p>
												</div>
												<div style="float:left;width:25%">
													<p style="text-align:center">.......................................</p>
													<p style="text-align:center">អ្នកទទួល / RECEIVER</p>
												</div>
												<div style="float:left;width:25%">
													<p style="text-align:center">.......................................</p>
													<p style="text-align:center">អ្នកដឹក / TRANSPORTER</p>
												</div>
												<div style="float:right;width:25%">
													<p style="text-align:center">.......................................</p>
													<p style="text-align:center">អ្នកលក់ / SELLER</p>
												</div>
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						</tfoot>
				</table>
			</div>

			<!--------count Page-------------->
			<div class="container" id="pagefooter">
				<p id='page-number'></p>
			</div>
			<style>
			  #pagefooter{
					position:fixed;
					bottom:0;
					text-align: right;
					padding-right: 40px;
				}

				body {
					counter-reset: page;
				}

				#page-number:after {
					counter-increment: page;
					content: "Page " counter(page);
				}
			</style>
			<!------------------------------------>
		</div>

	</body>
</html>
<script>
	$("document").ready(function(e){
		window.print();
		var sum = 0;
		var a=1;
		$('#table-bordered tr.blank').each(function(i,el) {
			var hgt = $(this).height();
			sum += hgt;
			a++;
		});
		for(i=0;i<=19;i++)
		{
			if((sum+29) < 300)
			{
				$('#table-bordered > tbody').append('<tr style="font-size: 13px;border 1px solid #000000;" class="blank"><td><center>'+a+'<center></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><tr>');
				sum=sum+29;
				a++;
			}

		}
	});
</script>
