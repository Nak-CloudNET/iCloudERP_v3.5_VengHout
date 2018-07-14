<!DOCTYPE html>
<html>
<head>
<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
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
@page {
	  size: A4;
	  margin: 10px !important;
	}	
	@media print {
		body {
                width: 100%;
                font-size: 20px !important;
            }
		#btn_print{
			display:none;
		}
	}
	.container {
		width: 29.7cm;
		margin: 20px auto;
		padding:30px;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
	}

</style>
</head>

<body>

	

	<?php
		$this->db->select('erp_categories_note.*');
		$this->db->join('delivery_items','deliveries.id = delivery_items.delivery_id', 'left');
		$this->db->join('erp_products','erp_delivery_items.product_id = erp_products.id', 'left');
		$this->db->join('erp_categories','erp_products.category_id = erp_categories.id', 'left');
		$this->db->join('erp_categories_note','erp_categories.categories_note_id = erp_categories_note.id', 'left');
		$this->db->where('erp_deliveries.id',$inv->id);
		$this->db->from('deliveries');
		$q = $this->db->get();
			foreach (($q->result()) as $cnote){
	?>
				<div class="container" style=" margin-top: 15%;">
					<button type="button" class="btn btn-xs btn-default no-print pull-right" id="btn_print" style="margin-top:-35px;margin-right:15px;" onclick="window.print();">
							<i class="fa fa-print"></i> <?= lang('print'); ?>
					</button>
				<div class="row" style="border: 1px solid black; border-radius: 15px;">
					<div class="col-lg-12" >
						<div class="col-lg-6 col-sm-6 col-xs-6" style="padding: 10px;">
							<table>
								<tr>
									<td><?=lang('លក់ជូន​​​​​​ <br/> sold_to')?></td>
									<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
									<td><?= $customer->names ? $customer->names : $customer->company; ?></td>
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
							</table>
						</div>
						<div class="col-lg-6 col-sm-6 col-xs-6" style="padding: 10px;">
							<table>
								<tr>
									<td><?=lang('លេខវិក័យប័ត្រ </br> DO No')?></td>
									<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
									<td><?= $inv->do_reference_no; ?></td>
								</tr>
								
								<tr>
									<td><?=lang('លេកវិក័យប័ត្រលក់ </br>Sales​ Reference No​​')?></td>
									<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
									<td><?php echo $inv->sale_reference_no; ?></td>
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
					<div class="table-responsive">
						<table class="table "  border="1">
							<thead>
								<tr>
									<th class="text-center"><?=lang('ល.រ </br> Nº')?></th>
									<th class="text-center"><?=lang('លេខកូដទំនិញ </br> Article No')?></th>
									<th class="text-center"><?=lang('ឈ្មោះទំនិញ </br> Description')?></th>
									<th class="text-center"><?=lang('ឯកតា </br> Unit')?></th>
									<th class="text-center"><?=lang('ចំនួន </br> Quanity')?></th>
									<th class="text-center"><?=lang('ម៉េត្រការ៉េ/កេស </br> Measurement')?></th>	
								</tr>
							</thead>
							<tbody>
							<?php
								$this->db->select('erp_deliveries.*,
						        					erp_delivery_items.product_name, erp_products.id as product_id, erp_products.code,erp_delivery_items.piece,erp_delivery_items.wpiece,delivery_items.option_id,
						        					COALESCE(SUM(erp_delivery_items.quantity_received)) as quantity_received,
						        					erp_companies.name,
													product_variants.name as variant,
						        					units.name as unit,
						        					product_variants.name as variant
						        				');
								$this->db->from('deliveries');
								$this->db->join('erp_companies','deliveries.delivery_by = erp_companies.id','left');
								$this->db->join('delivery_items','delivery_items.delivery_id = deliveries.id', 'left');
								$this->db->join('erp_products','delivery_items.product_id = erp_products.id', 'left');
								$this->db->join('erp_product_variants','delivery_items.option_id = erp_product_variants.id', 'left');
								$this->db->join('units','erp_products.unit = units.id', 'left');
								$this->db->join('erp_categories','erp_products.category_id = erp_categories.id', 'left');
								$this->db->join('erp_categories_note','erp_categories.categories_note_id = erp_categories_note.id', 'left');
								$this->db->where('erp_deliveries.id', $inv->id);
								$this->db->where_in('erp_categories_note.id',$cnote->id);
								$this->db->group_by('delivery_items.id');
								
								$query = $this->db->get();
								$no = 1;$r=1;
								$total = 0;
								foreach (($query->result()) as $row) :
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
									<td style=" text-align:center; vertical-align:middle;"><?=$no ;?></td>
									<td style="text-align:left; vertical-align:middle;"><?=$row->code?></td>
									<td style="text-align:left; vertical-align:middle;">
											<?=$row->product_name ?>
									</td>
									<td style="text-align:center; vertical-align:middle;">
										<?php
											if($row->piece != 0){ 
												echo  $str_unit;
												//$this->erp->print_arrays($str_unit);
											}else{ 
												echo $row->unit;
											}
										?>
										
									</td>
									<td style=" text-align:center; vertical-align:middle;">
										<?php 
											if($row->piece != 0){ 
												echo $row->piece; 
												//$this->erp->print_arrays($inv_item);
											}else{ 
												echo $this->erp->formatQuantity($row->quantity_received);}
										?>
									</td>
									<td style=" text-align:center; vertical-align:middle;">
										<?php
											if($row->piece!=0){
												echo $row ->wpiece;
											}
										?>
									</td>
								</tr>
								<?php
									$no++;$r++;
									endforeach;
								 
								?>
								<?php
									if($r<16){
										$k=16 - $r;
										for($j=1;$j<=$k;$j++){
											echo  '<tr>
													<td height="34px" class="text-center">'.$no.'</td>
													<td style="width:34px;"></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													
												</tr>';
											$no++;
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				</br>
				</br>
				</br>
				</br>
				<div class="row">
					<div class="col-lg-12 col-sm-12 col-xs-12" id="footer">
						<div class="col-lg-3 col-sm-3 col-xs-3 text-center">
							<hr style="border:dotted 1px; width:90px; vertical-align:bottom !important; " />
							<b style="text-align:center;margin-left:3px; font-size:17px !important;"><?= lang('​ អ្នកទទួល '); ?></b>
						</div>
						<div class="col-lg-3 col-sm-3 col-xs-3 text-center">
						<hr style="border:dotted 1px; width:90px; vertical-align:bottom !important; " />
							<b style="text-align:center;margin-left:3px;font-size:17px !important;;"><?= lang('​​អ្នកដឹក'); ?></b>
						</div>
						<div class="col-lg-3 col-sm-3 col-xs-3 text-center">
						<hr style="border:dotted 1px; width:90px; vertical-align:bottom !important; " />
							<b style="text-align:center;margin-left:3px;font-size:17px !important;"><?= lang('​អ្នកលក់'); ?></b>
						</div>
						<div class="col-lg-3 col-sm-3 col-xs-3 text-center">
						<hr style="border:dotted 1px; width:90px; vertical-align:bottom !important; " />
							<b style="text-align:center;margin-left:3px;font-size:17px !important;"><?= lang('ប្រធានឃ្លាំង'); ?></b>
						</div>
					</div>
				</div>
			</div>
	<?php } ?>

<script type="text/javascript">
 //window.onload = function() { window.print(); }
</script>
</body>
</html>