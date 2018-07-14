<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<style type="text/css">
	/*tbody tr td {
		border-bottom: none !important;
	}*/

	@media print {
		thead th,b {
			font-size: 11px !important;
		}
		tr td{
			font-size: 12px !important;
		}
	}

</style>
<div class="container" style=" margin-top: 20%;">
	<div class="row" style="border: 1px solid black; border-radius: 15px;">
		<div class="col-lg-12" >
			<div class="col-lg-6 col-sm-6" style="padding: 10px;">
				<table>
					<tr>
						<td><?=lang('លក់ជូន​​​​​​ <br/> sold_to')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?= $customer->name ? $customer->name : $customer->company; ?></td>
					</tr>
					<tr>
						<td><?=lang('អស័យដ្ឋាន​ </br> Address')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?= $customer->address;?></td>
					</tr>
					<tr>
						<td><?=lang('ទំនាក់ទំនង </br> Contact')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?= $customer->phone; ?></td>
					</tr>
					<!-- <tr>
						<td><?//=lang('ផ្នែកទីផ្សារ </br> Sales Executive')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?= $inv->saleman?></td>
					</tr> -->
				</table>
			</div>
			<div class="col-lg-6 col-sm-6" style="padding: 10px;">
				<table>
					<tr>
						<td><?=lang('លេខវិក័យប័ត្រ </br> Inv No')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?= $inv->reference_no; ?></td>
					</tr>
					<tr>
						<td><?=lang('លេខវិក័យប័ត្រកក់ </br> SO No')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?=$inv->ref ? $inv->ref : ' ';?></td>
					</tr>
					<tr>
						<td><?=lang('ផ្នែកលក់ </br> Showroom Sales')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?= $inv->username?></td>
					</tr>
					<tr>
						<td><?=lang('ថ្ងៃខែ </br> Date')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?= $this->erp->hrld($inv->date);?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	</br>
	<div class="row">
		<div class="col-lg-12 col-sm-12" style="border: 1px solid black;">
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
					<th  class="text-center"><?=lang('ល.រ </br> Nº')?></th>
					<th  class="text-center"><?=lang('រូបភាព </br> Image')?></th>
					<th class="text-center"><?=lang('លេខកូដទំនិញ </br> Article No')?></th>
					<th class="text-center"><?=lang('ឈ្មោះទំនិញ </br> Description')?></th>
					<th class="text-center"><?=lang('ឯកតា </br> Unit')?></th>
					<th class="text-center"><?=lang('ចំនួន </br> Quanity')?></th>
					<th class="text-center"><?=lang('ម៉េត្រការ៉េ/កេស </br> Measurement')?></th>
					<th class="text-center"><?=lang('តម្លៃ </br> Unit Price')?></th>
					<th class="text-center"><?=lang('បញ្ចុះតម្លៃ </br> Discount')?></th>
					<th class="text-center"><?=lang('សរុប </br> Amount')?></th>
				</tr>
			</thead>
			<tbody>
				<?php //for($i=0; $i<20; $i++){
					$i=1;
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
									// $var = $this->sales_model->getVarain($varian->product_id);
									$Max_unitqty = $this->sales_model->getMaxqty($varian->product_id);
									$Min_unitqty = $this->sales_model->getMinqty($varian->product_id);
									$maxqty =  $Max_unitqty->maxqty;
									$minqty =  $row->quantity;
									$Max_unit = $this->sales_model->getMaxunit($maxqty,$row->product_id);
									$Min_unit = $this->sales_model->getMinunit($Min_unitqty->minqty,$row->product_id);
									$maxunit = $Max_unit->name;
									$minunit = $Min_unit->name;
									$min_price = $row->unit_price;  

								}
                        }else{
                        	$maxqty =  $row->quantity;
							$minqty =  $row->quantity;
							$maxunit = " ";
							$minunit = $row->uname;
							$min_price = $row->unit_price;                                  
						}

				?>
				<tr>
					<td style="border-top:none !important;border-bottom:none !important; text-align:center; vertical-align:middle;"><?=$i;?></td>
					<td style="text-align:center; vertical-align:middle;">
					<img class="img-rounded img-thumbnail" style="width:60px;height:60px;" src="<?= base_url() . 'assets/uploads/thumbs/' . $row->image; ?>" alt="<?= $Settings->site_name; ?>">
                    </td>
					<td style="border-top:none !important;border-bottom:none !important; text-align:left; vertical-align:middle;"><?= $row->product_code ?></td>
					<td style="border-top:none !important;border-bottom:none !important; text-align:left; vertical-align:middle;">
							<?= $product_name_setting ?>
							<?= $row->details ? '<br>' . $row->details : ''; ?>
							<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
					</td>
					<td style="text-align:right; vertical-align:middle;width: 15px;">
                        <?php
                        if($row->option_id){
                        	if($row->variant == $minunit){
                        		echo $minunit;
                        	}else{
                        		echo $maxunit;
                        	}
                        }else{
                        	echo $minunit;
                        }  ?>
                    </td>
					<td style="border-top:none !important;border-bottom:none !important; text-align:center; vertical-align:middle;">
						<?php if($row->piece != 0){ echo $row->piece; }else{ echo $this->erp->formatDecimal($row->quantity);}?>
					</td>
					<td style="border-top:none !important;border-bottom:none !important; text-align:center; vertical-align:middle;"><?=$row->wpiece;?></td>
					<td style="border-top:none !important;border-bottom:none !important; text-align:center; vertical-align:middle;">
						<div class="col-xs-1 text-left">$</div>
						<div class="col-xs-5 text-left">
							<?=$this->erp->formatMoney($row->unit_price); ?>
						</div>
					</td>
					<td style="border-top:none !important;border-bottom:none !important; text-align:center; vertical-align:middle;">
						<div class="col-xs-1 text-left">$</div>
						<div class="col-xs-5 text-left">
							<?=$row->discount ? $row->discount:'0';?>
						</div>
					</td>
					<td style="border-top:none !important;border-bottom:none !important; text-align:left; vertical-align:middle;">
						<div class="col-xs-1 text-left">$</div>
						<div class="col-xs-6 text-left">
							<?= $row->subtotal!=0 ? $this->erp->formatMoney($row->subtotal):$free; 
									$total += $row->subtotal;
									?>
						</div>
						
					</td>
				</tr>
				<?php 
					$i++;
					endforeach;
				}
					$rSpan = 0;
					if ($total != $inv->grand_total) {
						$rSpan = 5;
					}
					// if ($inv->paid != 0)  {
					// 	$rSpan = 7;
					// }
					
				?>

				<tr>
					<td colspan="8" rowspan="<?= $rSpan ?>" style="text-align:left; font-weight: bold;">
						<?=lang('note').':';?>
					</td>
					<td style="text-align:left; vertical-align:middle;"><?='សរុប / '.lang('total').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
							<div class="col-xs-1 text-left">$</div>
						<div class="col-xs-6 text-left">
							<?=$this->erp->formatMoney($total);?>
						</div>
					</td>
				</tr>
				<?php if($inv->order_discount != 0){?>
				<tr>
					<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
					<td style="text-align:left; vertical-align:middle;"><?='បញ្ចុះតម្លៃ​ / '.lang('order_discount').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-1 text-left">$</div>
						<div class="col-xs-9 text-left">
							<?=$this->erp->formatMoney($inv->order_discount);?>
						</div>
					</td>
				</tr>
				<?php }?>
				<?php if($inv->shipping != 0){?>
				<tr>
					<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
					<td style="text-align:left; vertical-align:middle;"><?='វេចខ្ចប់ / '.lang('shipping').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-1 text-left">$</div>
						<div class="col-xs-9 text-left">
							<?=$this->erp->formatMoney($inv->shipping);?>
						</div>
					</td>
				</tr>
				<?php }?>
				<?php if($inv->order_tax !=0){?>
				<tr>
					<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
					<td style="text-align:left; vertical-align:middle;"><?='ពន្ធកម្ម៉ុង / '.lang('order_tax').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-1 text-left">$</div>
						<div class="col-xs-9 text-left">
							<?=$this->erp->formatMoney($inv->order_tax);?>
						</div>
					</td>
				</tr>
				<?php }?>

				<?php if($inv->order_tax !=0 || $inv->shipping !=0 || $inv->order_discount !=0){?>
				<tr>
					<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
					<td style="text-align:left; vertical-align:middle;"><?='ប្រាក់សរុប / '.lang('total_amount').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-1 text-left">$</div>
						<div class="col-xs-9 text-left">
							<?=$this->erp->formatMoney($inv->grand_total);?>
						</div>
					</td>
				</tr>
				<?php }?>
				<?php //if($inv->paid !=0){?>
				<!-- <tr> -->
					<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
					<!-- <td style="text-align:left; vertical-align:middle;"><?//='បង់ប្រាក់ / '.lang('paid').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?//=$this->erp->formatMoney($inv->paid);?>
						</div>
					</td> -->
				<!-- </tr> -->
				<!-- <tr> -->
					<!-- <td colspan="7" style="text-align:center; vertical-align:middle;"></td> -->
					<!-- <td style="text-align:left; vertical-align:middle;"><?//='នៅខ្វះ / '.lang('balance').':'?></td>
					<td style="text-align:left; vertical-align:middle;">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?//=$this->erp->formatMoney($inv->grand_total - $inv->paid); ?>
						</div>
					</td> -->
				<!-- </tr> -->
				<?php //}?>
			</tbody>
		</table>
	</div>
	</br>
	</br>
	</br>
	</br>
	</br>
	<div class="row">
		<div class="col-lg-12 col-sm-12">
			<div class="col-lg-4 col-sm-4 text-center">
				<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
				<b style="text-align:center;margin-left:3px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​ទិញ​  <br/> Customer`s Signature & Name'); ?></b>
			</div>
			<div class="col-lg-4 col-sm-4 text-center">
			<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
				<b style="text-align:center;margin-left:3px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​ដឹក  <br/> Deliver`s Signature & Name'); ?></b>
			</div>
			<div class="col-lg-4 col-sm-4 text-center">
			<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
				<b style="text-align:center;margin-left:3px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​លក់  <br/> Saller`s Signature & Name'); ?></b>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>
