<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<style type="text/css">
	.container {
		width: 29.7cm;
		margin: 20px auto;
		padding:30px;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
	}
	tbody tr td {
		border-bottom: none !important;
		
	}
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
			background-color: #444 !important;
			color: #FFF !important;
		}
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border: 1px solid #000 !important;
	}	
	@media print {
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
			background-color: #444 !important;
			color: #FFF !important;
		}
		thead th,b {
			font-size: 13px !important;
		}
		tr td{
			font-size: 15px !important;
			
		}
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border: 1px solid #000 !important;
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
	
</style>
<div class="container">
	<div class="row">
		<div class="col-lg-4 col-sm-4 col-xs-4">
			<?php
                if ($Settings->system_management == 'project') { ?>
                    <div class="text-center" style="margin-bottom:20px;">
                        <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo2; ?>"
                             alt="<?= $Settings->site_name != '-' ? $Settings->site_name : $Settings->site_name; ?>">
                    </div>
            <?php } else { ?>
                    <?php if ($logo) { ?>
                        <div class="text-center" style="margin-bottom:20px;">
                            <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                                 alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                        </div>
                    <?php } ?>
            <?php } ?>
		</div>
		<div class="col-lg-8 col-sm-8 col-xs-8">
			 <button type="button" class="btn btn-xs btn-default no-print pull-right" id="btn_print" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			<h2 style="font-family:Khmer Mool1 !important; color:red !important;">លក់សំភារះសំណង់ទំនើបៗ គ្រប់ប្រភេទ</h2>
		</div>
	</div>
	<div class="row">	
		<div class="col-lg-12 col-sm-12 col-xs-12">
			<center><h2 style=" font-family:Times New Roman, Georgia, Serif;font-weight:bold;">INVOICE</h2></center>
		</div>
	</div>
	<div class="row" style="border: 1px solid black; border-radius: 15px;">
		<div class="col-lg-12" >
			<div class="col-lg-6 col-sm-6 col-xs-6" style="padding: 10px;">
				<table>
					<tr>
						<td style="font-size:17px !important;"><?=lang('លក់ជូន​​​​​​ <br/> sold_to')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><?= $customer->name ? $customer->name : $customer->company; ?></td>
					</tr>
					<tr>
						<td style="font-size:17px !important;"><?=lang('អស័យដ្ឋាន​ </br> Address')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><?= $customer->address;?></td>
					</tr>
					<tr>
						<td style="font-size:17px !important;"><?=lang('ទំនាក់ទំនង </br> Contact')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><?= $customer->phone; ?></td>
					</tr>
					<!-- <tr>
						<td><?//=lang('ផ្នែកទីផ្សារ </br> Sales Executive')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?= $inv->saleman?></td>
					</tr> -->
				</table>
			</div>
			<div class="col-lg-6 col-sm-6 col-xs-6" style="padding: 10px;">
				<table>
					<tr>
						<td style="font-size:17px !important;"><?=lang('លេខវិក័យប័ត្រ </br> Inv No')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><?= $inv->reference_no; ?></td>
					</tr>
					<tr>
						<td style="font-size:17px !important;"><?=lang('លេខវិក័យប័ត្រកក់ </br> SO No')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><?=$inv->so_no ? $inv->so_no : ' ';?></td>
					</tr>
					<tr>
						<td style="font-size:17px !important;"><?=lang('ផ្នែកលក់ </br> Showroom Sales')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><?= $inv->saleman?></td>
					</tr>
					<tr>
						<td style="font-size:17px !important;"><?=lang('ថ្ងៃខែ </br> Date')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><?= $this->erp->hrld($inv->date);?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	</br>
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-xs-12" style="border: 1px solid black;">
			<div class="pull-left">
				<p style="padding-top: 12px;"><b><?=lang('ទំនិញ/Order Details')?></b></p>
			</div>
			<div class="pull-right">
				<p style="padding-top: 5px; font-size: 20px;"><b><?=lang('BRANCH1')?></b></p>
			</div>
		</div>
		</br>
		</br>
		</br>
		<table class="table table-bordered table-hover" border="1">
			<thead>
				<tr>
					<th style="font-size:17px !important;" class="text-center"><?=lang('ល.រ </br> Nº')?></th>
					<th style="font-size:17px !important;"class="text-center"><?=lang('លេខកូដទំនិញ </br> Article No')?></th>
					<th style="font-size:17px !important;width:25% !important;"class="text-center"><?=lang('ឈ្មោះទំនិញ </br> Description')?></th>
					<th style="font-size:17px !important;"class="text-center"><?=lang('ឯកតា </br> Unit')?></th>
					<th style="font-size:17px !important;"class="text-center"><?=lang('ចំនួន </br> Quanity')?></th>
					<th style="font-size:17px !important;"class="text-center"><?=lang('ម៉េត្រការ៉េ/កេស </br> Measurement')?></th>
					<th style="font-size:17px !important;width:10% !important;"class="text-center"><?=lang('តម្លៃ </br> Unit Price')?></th>
					<th style="font-size:17px !important;width:10% !important;" class="text-center"><?=lang('បញ្ចុះតម្លៃ </br> Discount')?></th>
					<th style="font-size:17px !important;width:12% !important;"class="text-center"><?=lang('សរុប </br> Amount')?></th>
				</tr>
			</thead>
			<tbody>
				<?php //for($i=0; $i<20; $i++){
					$i=1; $erow = 1;
					if(is_array($rows)){
						$total = 0;
						foreach ($rows as $row):
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
					<td style="text-align:center; vertical-align:middle;"><?=$i;?></td>
					<td style="text-align:left; vertical-align:middle;"><?= $row->product_code ?></td>
					<td style="text-align:left; vertical-align:middle;">
							<?= $product_name_setting ?>
							<?= $row->details ? '<br>' . $row->details : ''; ?>
							<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
					</td>
					<td style="text-align:center; vertical-align:middle;">
						<?php echo $str_unit ?>
						
					</td>
					<td style="text-align:center; vertical-align:middle;">
						<?php if($row->piece != 0){ echo $row->piece; }else{ echo $this->erp->formatQuantity($row->quantity);}?>
					</td>
					<td style=" text-align:center; vertical-align:middle;">
						<?php
							if($row->piece !=0){
								echo $row -> wpiece;
							}
						?>
					</td>
					<td style="text-align:center; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-6 text-left">
							<?=$this->erp->formatMoney($row->unit_price); ?>
						</div>
					</td>
					<td style="text-align:center; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?=$row->discount ? $row->discount:'0';?>
						</div>
					</td>
					<td style=" text-align:left; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?= $row->subtotal!=0 ? $this->erp->formatMoney($row->subtotal):$free; 
									$total += $row->subtotal;
									?>
						</div>
						
					</td>
				</tr>
				<?php 
					$i++;
					$erow++;
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
					if($erow<16){
						$k=16 - $erow;
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
									<td></td>
								</tr>';
							$i++;
						}
					}
				?>

				<tr>
					<td colspan="6" rowspan="<?= $rSpan ?>" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important;">
						<p style="text-align:left; font-weight: bold;border-left:1px solid white!important;"><?=lang('note').':';?></p>
						<p><?= $inv->invoice_footer ?></p>
					</td>
					<td colspan="2"style="text-align:left; vertical-align:middle;"><?='សរុប / '.lang('total').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?=$this->erp->formatMoney($total);?>
						</div>
					</td>
				</tr>
				<?php if($inv->order_discount != 0){?>
				<tr>
					<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
					<td colspan="2"style="text-align:left; vertical-align:middle;"><?='បញ្ចុះតម្លៃ​ / '.lang('order_discount').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?=$this->erp->formatMoney($inv->order_discount);?>
						</div>
					</td>
				</tr>
				<?php }?>
				<?php if($inv->shipping != 0){?>
				<tr>
					<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
					<td colspan="2"style="text-align:left; vertical-align:middle;"><?='ដឹកជញ្ជូន / '.lang('shipping').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?=$this->erp->formatMoney($inv->shipping);?>
						</div>
					</td>
				</tr>
				<?php }?>
				<?php if($inv->order_tax !=0){?>
				<tr>
					<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
					<td colspan="2"style="text-align:left; vertical-align:middle;"><?='ពន្ធកម្ម៉ុង / '.lang('order_tax').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?=$this->erp->formatMoney($inv->order_tax);?>
						</div>
					</td>
				</tr>
				<?php }?>

				<?php if($inv->order_tax !=0 || $inv->shipping !=0 || $inv->order_discount !=0){?>
				<tr>
					<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
					<td colspan="2"style="text-align:left; vertical-align:middle;"><?='ប្រាក់សរុប / '.lang('total_amount').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?=$this->erp->formatMoney($inv->grand_total);?>
						</div>
					</td>
				</tr>
				<?php }?>
				<?php if($inv->paid !=0){?>
				<tr>
					<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
					<td colspan="2"style="text-align:left; vertical-align:middle;"><?='បង់ប្រាក់ / '.lang('paid').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?=$this->erp->formatMoney($inv->paid);?>
						</div>
					</td>
				</tr>
				<tr>
					<!-- <td colspan="7" style="text-align:center; vertical-align:middle;"></td> -->
					<td colspan="2"style="text-align:left; vertical-align:middle;"><?='នៅខ្វះ / '.lang('balance').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?=$this->erp->formatMoney($inv->grand_total - $inv->paid); ?>
						</div>
					</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
	</br>
	</br>
	</br>
	</br>
	</br>
	<div class="row" id="footer">
		<div class="col-lg-12 col-sm-12 col-xs-12">
			<div class="col-lg-4 col-sm-4 col-xs-4 text-center">
				<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
				<b style="text-align:center;margin-left:3px;font-size:16px !important;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​ទិញ​  <br/> Customer`s Signature & Name'); ?></b>
			</div>
			<div class="col-lg-4 col-sm-4 col-xs-4 text-center">
			<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
				<b style="text-align:center;margin-left:3px;font-size:16px !important;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​ដឹក  <br/> Deliver`s Signature & Name'); ?></b>
			</div>
			<div class="col-lg-4 col-sm-4 col-xs-4 text-center">
			<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
				<b style="text-align:center;margin-left:3px;font-size:16px !important;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​លក់  <br/> Saller`s Signature & Name'); ?></b>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>