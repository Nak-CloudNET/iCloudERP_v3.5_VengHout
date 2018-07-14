<?php
	//$this->erp->print_arrays($invs);
	$note_arr = explode('/',$biller->phone);
	//$this->erp->print_arrays($note_arr[0],$note_arr[1],$note_arr[2]);
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
	<meta charset="UTF-8">
	<title>Credit Note</title>
	<link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
	<link href="<?php echo $assets ?>styles/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $assets ?>styles/custome.css" rel="stylesheet">
    <link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
</head>
<style>
	.container {
		width:17cm;
		height:21 cm;
		margin: 20px auto;
		/*padding: 10px;*/
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
	}
	tbody{
		font-family:khmer Os;
		font-family:Times New Roman !important;
	}
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
			background-color: #444 !important;
			color: #FFF !important;
		}
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border: 1px solid #000 !important;
	}
	#tels span {
		padding-left: 23px;
	}
	#tels span:first-child {
		padding-left: 0 !important;
	}
	@media print {
		thead th,b {
			font-size: 12px !important;
		}
		tr td{
			font-size: 13px !important;
		}
		#footer {
			bottom:70px !important;
			position: absolute !important;
			width:100% !important;
		}
		#btn_print{
			display:none;
		}
	}
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
			background-color: #444 !important;
			color: #FFF !important;
		}
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border: 1px solid #000 !important;
	}	
</style>
<body>
    <div class="container">
		<div class="row">
			<div class="col-lg-3 col-sm-3 col-xs-3">
				<?php if(!empty($biller->logo)) { ?>
					<img src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" style="width: 150px; margin-left: 25px !important;margin-bottom:50px !important;" />
				<?php } ?>
			</div>
			<div class="col-lg-6 col-sm-6 col-xs-6">
				<center><h2 style="font-weight:bold !important;font-family:Time New Roman !important;margin-bottom:20px !important;">Return Sale</h2></center>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 col-sm-6 col-xs-6">
				<table>
					<tr>
						<td style="font-size:13px !important;"><?= $biller->address; ?></td>
					</tr>
					<tr style="line-height:37px !important;">
						<td style="font-size:17px;font-weight:bold;"><?=strtoupper(lang('customer'))?></td>
					</tr>
					<tr>
						<td style="font-size:15px !important;"><?=lang('name')?>&nbsp;&nbsp;:&nbsp;&nbsp;<?= $customer->names ? $customer->names : $customer->company; ?></td>
					</tr>
					<tr>
						<td style="font-size:15px !important;"><?=lang('Address')?>&nbsp;&nbsp;:&nbsp;&nbsp;<?= $customer->address;?></td>
					</tr>
					<tr>
						<td style="font-size:15px !important;"><?=lang('tel')?>&nbsp;&nbsp;:&nbsp;&nbsp;<?= $customer->phone; ?></td>
					</tr>
					
				</table><br>
			</div>
			<div class="col-lg-6 col-sm-6 col-xs-6">
				<table>
					<tr>
						<td style="font-size:13px !important;"><?=lang('No')?>&nbsp;&nbsp;:&nbsp;&nbsp;<?= $inv->reference_no; ?></td>
					</tr>
					<tr>
						<td style="font-size:13px !important;"><?=lang(' Reference Invoice No')?>&nbsp;&nbsp;:&nbsp;&nbsp;<?=$inv->so_no ? $inv->so_no : ' ';?></td>
					</tr>
					<tr>
						<td style="margin-top:-10px !important;font-size: 13px;" id="tels">
						Tel:
					    <?php if(!empty($biller->phone)) { ?>
							<?php for($i=0; $i<sizeof($note_arr);$i++){ ?>
								<?= '<span>'.$note_arr[$i]."</span><br/>"?>
							<?php } ?>
						<?php } ?>
						</td>
					
					</tr>
					<tr>
						<td style="margin-top:-10px !important;font-size: 13px;font-weight:bold;">
							<?php if(!empty($biller->email)) { ?>E-mail :&nbsp;<?= $biller->email; ?></p>
					      <?php } ?>
						</td>
					</tr>
					<tr>
						<td style="font-size:13px !important;"><?=lang('Date')?>&nbsp;&nbsp;:&nbsp;&nbsp;<?= $this->erp->hrld($inv->date);?></td>
					</tr>
				</table><br>
			</div>
		</div>

        <div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
					<table class="table table-bordered table-hover" border="1">
				<thead>
					<tr>
						<th style="font-size:13px !important;" class="text-center"><?=lang('Nº')?></th>
						<th style="font-size:13px !important;"class="text-center"><?=lang('CODE')?></th>
						<th style="width:100% !;font-size:13px !important;"class="text-center"><?=lang('DESCRIPTION')?></th>
						<th style="font-size:13px !important;"class="text-center"><?=lang('UNIT')?></th>
						<th style="font-size:13px !important;"class="text-center"><?=lang('QUANTITY')?></th>
						<th style="font-size:13px !important;"class="text-center"><?=lang('PRICE')?></th>
						<th style="font-size:13px !important;" class="text-center"><?=lang('DISCOUNT')?></th>
						<th style="font-size:13px !important;"class="text-center"><?=lang(' AMOUNT')?></th>
					</tr>
				</thead>
				<tbody>
					<?php //for($i=0; $i<20; $i++){
						$i=1;$erow=1;
						if(is_array($rows)){
							$total = 0;
							foreach ($rows as $row):
							//$this->erp->print_arrays($row);
							$free = lang('free');
							$product_unit = '';
							
							
							if($row->variant){
								$product_unit = $row->variant;
							}else{
								$product_unit = $row->uname;
							}
							$product_name_setting;
							if($Settings->show_code == 0) {
								$product_name_setting = $row->product_name ;
							}else {
								if($Settings->separate_code == 0) {
									$product_name_setting = $row->product_name . " (" . $row->product_code . ")";
								}else {
									$product_name_setting = $row->product_name;
								}
							}

							if($row->option_id){
										
							   $getvar = $this->sales_model->getAllProductVarain($row->product_id);
									 foreach($getvar as $varian){
										 if($varian->product_id){
											 if($varian->qty_unit == 0){
												$var = $this->sales_model->getVarain($row->option_id);
												$str_unit = $var->name;
											 }else{
												$var = $this->sales_model->getMaxqtyByProID($row->product_id);
												$var1 = $this->sales_model->getVarain($var->product_id);									
												$str_unit = $var1->name;
											}
										 }else{
											$str_unit = $row->uname;
										}
									}
							}else{
								$str_unit = $row->uname;
							}

					?>
					<tr>
						<td style=" text-align:center; vertical-align:middle;"><?=$i;?></td>
						<td style="text-align:left; vertical-align:middle;"><?= $row->product_code ?></td>
						<td style="text-align:left; vertical-align:middle;width:200px;">
								<?= $product_name_setting ?>
								<?= $row->details ? '<br>' . $row->details : ''; ?>
								<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
						</td>
						<td style="text-align:center; vertical-align:middle;">
							<?php
								if($row->piece != 0){
									echo $str_unit;
								}else{ 
									echo $row->product_unit;
								}

							?>
						</td>
						<td style=" text-align:center; vertical-align:middle;">
							<?php 
								if($row->piece != 0){ 
									echo $row->piece; 
								}else{ 
									echo $this->erp->formatQuantity($row->quantity);}
							?>
						</td>
						
						<td style="text-align:center; vertical-align:middle;">
							
							<div class="col-xs-6 text-right">
								<?=$this->erp->formatMoney($row->unit_price); ?>
							</div>
						</td>
						<td style="text-align:center; vertical-align:middle;">
							<div class="col-xs-3"></div>
							    
							<div class="col-xs-7 text-left">
								<?=$row->discount ? $row->discount:'0';?>
							</div>
						</td>
						<td style=" vertical-align:middle;">
							<div class="col-xs-3 text-left">
								<?php
									if ($row->subtotal!=0) {
										echo '';
									} else {
										echo '';
									}
								?>
							</div>
							<div class="col-xs-7 text-left">
								<?= $row->subtotal!=0 ? $this->erp->formatMoney($row->subtotal):$free; 
										$total += $row->subtotal;
										?>
							</div>
							
						</td>
					</tr>
					<?php 
						$i++;$erow++;
						endforeach;
					}
						$rSpan = 0;
						if ($total != $inv->grand_total) {
							$rSpan = 5;
						}
						if ($inv->paid != 0)  {
							$rSpan = 7;
						}
						
					?>
					<?php
						if($erow<10){
							$k=10 - $erow;
							for($j=1;$j<=$k;$j++){
								echo  '<tr>
										<td height="34px" class="text-center">'.$i.'</td>
										<td style="width:34px;"></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										
									</tr>';
								$i++;
							}
						}
					?>

					<tr>
						<td colspan="5" rowspan="<?= $rSpan ?>" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important;">
							<p style="text-align:left; font-weight: bold;border-left:1px solid white!important;border-bottom:"></p>
							<p><?= $inv->invoice_footer ?></p>
						</td>
						<td colspan="2" style="text-align:left; "><?= lang('total')?></td>
						<td style="text-align:left; ">
							<div class="col-xs-3 text-left"></div>
							<div class="col-xs-7 text-left">
								<?=$this->erp->formatMoney($total);?>
							</div>
						</td>
					</tr>
					<?php if($inv->order_discount != 0){?>
					<tr>
						<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
						<td colspan="2" style="text-align:left; vertical-align:middle;"><?=lang('order_discount')?></td>
						<td style="text-align:left; vertical-align:middle;">
							<div class="col-xs-3 text-left"></div>
							<div class="col-xs-7 text-left">
								<?=$this->erp->formatMoney($inv->order_discount);?>
							</div>
						</td>
					</tr>
					<?php }?>
					<?php if($inv->shipping != 0){?>
					<tr>
						<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
						<td colspan="2"style="text-align:left; vertical-align:middle;"><?='ដឹកជញ្ជូន / '.lang('shipping')?></td>
						<td style="text-align:left; vertical-align:middle;">
							<div class="col-xs-3 text-left"></div>
							<div class="col-xs-7 text-left">
								<?=$this->erp->formatMoney($inv->shipping);?>
							</div>
						</td>
					</tr>
					<?php }?>
					<?php if($inv->order_tax !=0){?>
					<tr>
						<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
						<td colspan="2"style="text-align:left; vertical-align:middle;"><?='ពន្ធកម្ម៉ុង / '.lang('order_tax')?></td>
						<td style="text-align:left; vertical-align:middle;">
							<div class="col-xs-3 text-left"></div>
							<div class="col-xs-7 text-left">
								<?=$this->erp->formatMoney($inv->order_tax);?>
							</div>
						</td>
					</tr>
					<?php }?>

					<?php if($inv->order_tax !=0 || $inv->shipping !=0 || $inv->order_discount !=0){?>
					<tr>
						
						<td colspan="2"style="text-align:left; vertical-align:middle;"><?=lang('Total_Amount')?></td>
						<td style="text-align:left; vertical-align:middle;">
							<div class="col-xs-3 text-left"></div>
							<div class="col-xs-7 text-left">
								<?=$this->erp->formatMoney($inv->grand_total);?>
							</div>
						</td>
					</tr>
					<?php }?>
					<?php if($inv->paid !=0){?>
					<tr>
						<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
						<td colspan="2"style="text-align:left; vertical-align:middle;"><?='បង់ប្រាក់ / '.lang('paid')?></td>
						<td style="text-align:left; vertical-align:middle;">
							<div class="col-xs-3 text-left"></div>
							<div class="col-xs-7 text-left">
								<?=$this->erp->formatMoney($inv->paid);?>
							</div>
						</td>
					</tr>
					<tr>
						<!-- <td colspan="7" style="text-align:center; vertical-align:middle;"></td> -->
						<td colspan="2"style="text-align:left; vertical-align:middle;"><?='នៅខ្វះ / '.lang('balance')?></td>
						<td style="text-align:left; vertical-align:middle;">
							<div class="col-xs-3 text-left"></div>
							<div class="col-xs-7 text-left">
								<?=$this->erp->formatMoney($inv->grand_total - $inv->paid); ?>
							</div>
						</td>
					</tr>
					<?php }?>
				</tbody>
				
			</table>
			</div>
		    <div class="row">
				<div class="col-lg-4 col-sm-4 col-xs-4" style="magin-left:20px !important;">
					<center><p>Customer</p></center>
				</div>
				<div class="col-lg-4 col-sm-4 col-xs-4">
					<center><p>Delivery</p></center>
				</div>
				<div class="col-lg-4 col-sm-4 col-xs-4">
					<center><p>Stock Controller</p></center>
				</div>
			</div>
			
		</div>
	</div>
	
</body>
</html>