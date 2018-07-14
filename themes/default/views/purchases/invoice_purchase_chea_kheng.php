<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<title><?php echo $this->lang->line("invoice_purchase_chea_kheng") . " " . $inv->reference_no; ?></title>
<style type="text/css">
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
	@media print {
		thead th,b {
			font-size: 14px !important;
		}
		tr td{
			font-size: 18px !important;
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
	.container {
		width: 29.7cm;
		margin: 20px auto;
		padding:30px;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
	}

</style>
<div class="container" style=" margin-top: 15%;">
		<button type="button" class="btn btn-xs btn-default no-print pull-right" id="btn_print" style="margin-top:-35px;margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
        </button>
	<div class="row" style="border: 1px solid black; border-radius: 15px;">
		<div class="col-lg-12" >
			<div class="col-lg-6 col-sm-6 col-xs-6" style="padding: 10px;">
				<table style="line-height:35px;">
					<tr>
						<td style="font-size:19px !important;"><b><?=lang('សេចក្តីយោង')?></b></td>
					</tr>
					<tr>
						<td><?=lang('មកពី')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?="<span style='font-weight:bold; font-size:17px;'>". $inv->company  ."</span>";?></td>
					</tr>
					<tr>
						<td><?=lang('អាស័យដ្ឋាន')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?=($warehouse->address ? $warehouse->address." " : ''). ($warehouse->city ? $warehouse->city . " " . $warehouse->postal_code . " " . $warehouse->state . " " : '') . ($warehouse->country ? $warehouse->country : ''); ?></td>
					</tr>
					<tr>
                        <td>អ្នកប្រចាំការ</td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><?=$inv->username;?></td>
                    </tr>
					<?php if ($warehouse->phone !='' || $warehouse->email !=''): ?>
					<tr>
						<td><?=lang('ទំនាក់ទំនង')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?=($warehouse->phone ? $warehouse->phone.'/'.$warehouse->email : $warehouse->email) ?></td>
					</tr>
					<?php endif ?>
				</table>
			</div>
			<div class="col-lg-6 col-sm-6 col-xs-6" style="padding: 10px;">
				<table style="line-height:35px;">
					<tr>
						<td style="font-size:19px !important;"><b><?=lang('អ្នកផ្គត់ផ្គង់')?></b></td>
					</tr>
					<tr>
						<td style="font-size:17px !important;"><?=lang('ទៅ')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><?="<span style='font-weight:bold; font-size:17px;'>". ($supplier->company ? $supplier->company : $supplier->name) ."</span>";?></td>
					</tr>
					
					<tr>
						<td style="font-size:17px !important;"><?=lang('អាស័យដ្ឋាន')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><?=($supplier->address ? $supplier->address : ""). ($supplier->city ? "<br>" . $supplier->city : "") . ($supplier->postal_code ? " " .$supplier->postal_code : "") . ($supplier->state ? " " .$supplier->state : "") .  ($supplier->country ? "<br>" .$supplier->country : ""); ?></td>
						
					</tr>
					<tr>
                        <td>អ្នកប្រចាំការ</td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><?=$supplier->name;?></td>
                    </tr>
					<tr>
						<td style="font-size:17px !important;"><?=lang('ទំនាក់ទំនង')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><?=($supplier->phone ? $supplier->phone.'/'.$supplier->email : $supplier->email) ?></td>
					</tr>
					<tr>
                        <td><?= lang("ថ្ងៃទី"); ?></td>
                        <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                        <td><?= $this->erp->hrld($inv->date); ?></td>
                    </tr>
				</table>
			</div>
		</div>
	</div>
	</br>
		<table class="table table-bordered table-hover" border="1">
			<thead>
				<tr>
					<th style="font-size:17px !important;" class="text-center"><?=lang('ល.រ </br> Nº')?></th>
					<th style="font-size:17px !important;"class="text-center"><?=lang('លេខកូដទំនិញ </br> Article No')?></th>
					<th style="width:25% !important;font-size:17px !important;"class="text-center"><?=lang('ឈ្មោះទំនិញ </br> Description')?></th>
					<th style="font-size:17px !important;"class="text-center"><?=lang('ឯកតា </br> Unit')?></th>
					<th style="font-size:17px !important;"class="text-center"><?=lang('ម៉េត្រការ៉េ/កេស </br> Measurement')?></th>
					<th style="font-size:17px !important;"class="text-center"><?=lang('ចំនួន </br> Quanity')?></th>
					<th style="width:10% !important;font-size:17px !important;"class="text-center"><?=lang('តម្លៃ </br> Unit Price')?></th>
					<th style="width:10% !important;font-size:17px !important;" class="text-center"><?=lang('បញ្ចុះតម្លៃ </br> Discount')?></th>
					<th style="width:12% !important;font-size:17px !important;"class="text-center"><?=lang('សរុប </br> Amount')?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				
					$i=1;$erow=1;
					if(is_array($rows)){
						$total = 0;
						foreach ($rows as $row):
						$free = lang('free');
						$product_unit = '';
						//$this->erp->print_arrays($row);
						
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
					<td style="text-align:left; vertical-align:middle;">
						<?=$row->product_code ?>
					
					</td>
					<td style="text-align:left; vertical-align:middle;">
						<?= $row->product_name?>
					</td>
					
					<td style="text-align:center; vertical-align:middle;width: 15px;">
                       <?php
							if($row->piece != 0){ 
								echo $str_unit;
								
							}else{ 
								echo $row->unit;
								//$this->erp->print_arrays($row);
							}
						
						?>
                    </td>
					<td style=" text-align:center; vertical-align:middle;">
						<?php 
							if($row->piece != 0){
								echo $row ->wpiece;
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
					<td style=" text-align:center; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-6 text-right">
							 <?= $this->erp->formatMoney($row->unit_cost); ?>
						</div>
					</td>
					<td style="text-align:center; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?=$row->discount ? $row->discount:'0';?>
						</div>
					</td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-3 text-left">
							<?php
								if ($row->subtotal!=0) {
									echo '$';
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
						<p style="text-align:left; font-weight: bold;border-left:1px solid white!important;border-bottom:"></p>
						<p><?= $this->erp->decode_html($inv->note);?></p>
					</td>
					<td colspan="2" style="text-align:left; "><?='សរុប / '.lang('total').':'?></td>
					<td style="text-align:left; ">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?=$this->erp->formatMoney($total);?>
						</div>
					</td>
				</tr>
				<?php if($inv->order_discount != 0){?>
				<tr>
					<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
					<td colspan="2" style="text-align:left; vertical-align:middle;"><?='បញ្ចុះតម្លៃ​ / '.lang('order_discount').':'?></td>
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

	</br>
	</br>
	</br>
	</br>
	</br>
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-xs-12" id="footer">
			<div class="col-lg-4 col-sm-4 col-xs-4 text-center">
				<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
				<b style="text-align:center;margin-left:3px; font-size:16px !important;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​ទិញ​  <br/> Customer`s Signature & Name'); ?></b>
			</div>
			<div class="col-lg-4 col-sm-4 col-xs-4 text-center">
			<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
				<b style="text-align:center;margin-left:3px;font-size:16px !important;;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​ដឹក  <br/> Deliver`s Signature & Name'); ?></b>
			</div>
			<div class="col-lg-4 col-sm-4 col-xs-4 text-center">
			<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
				<b style="text-align:center;margin-left:3px;font-size:16px !important;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​លក់  <br/> Saller`s Signature & Name'); ?></b>
			</div>
		</div>
	</div><br><br>
</div>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>