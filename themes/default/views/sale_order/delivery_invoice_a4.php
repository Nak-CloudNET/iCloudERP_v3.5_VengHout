
<?php
	
?>
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
	@media print {
		body {
                width: 100%;
                font-size: 12px !important;
            }
		thead th {
			font-size: 12px !important;
		}
		tr td{
			font-size: 14px !important;
		}
		#footer {
			position: absolute !important;
			width:47% !important;
		}
		#btn_print{
			display:none;
		}
		.table{
			width:47% !important;
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
	<div class="container">
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
							<td><?=lang('ផ្នែកលក់ </br> Showroom Sales')?></td>
							<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
							<td><?= $inv->saleman?></td>
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
			<div class="col-lg-12 col-sm-12 col-xs-12" style="border: 1px solid black;">
				<div class="pull-left">
					<p style="padding-top: 14px;"><b><?=lang('ទំនិញ/Order Details')?></b></p>
				</div>
				<div class="pull-right">
					<p style="padding-top: 5px; font-size: 20px;"><b><?=lang('BRANCH1')?></b></p>
				</div>
			</div>
			</br>
			</br>
			</br>
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
						$no = 1;
						$row = 1;
						?>
					<?php foreach($inv_items as $inv_item) {
					//$this->erp->print_arrays($inv_item);
						
					?>
					
						<tr>
							<td style=" text-align:center; vertical-align:middle;"><?=$no ;?></td>
							<td style="text-align:left; vertical-align:middle;"><?=$inv_item->code?></td>
							<td style="text-align:left; vertical-align:middle;">
									<?=$inv_item->description ?>
							</td>
							<td style="text-align:center; vertical-align:middle;">
								<?php
									if($inv_item->piece != 0){ 
										echo 'hello';
									}else{ 
										echo $inv_item->unit;
									}
								?>
								
							</td>
							<td style=" text-align:center; vertical-align:middle;">
								<?php 
									if($inv_item->piece != 0){ 
										echo $inv_item->piece; 
										//$this->erp->print_arrays($inv_item);
									}else{ 
										echo $this->erp->formatQuantity($inv_item->qty);}
								?>
							</td>
							<td style=" text-align:center; vertical-align:middle;">
								<?php
									if($inv_item->piece!=0){
										echo $inv_item ->wpiece;
									}
								?>
							</td>
						</tr>
						<?php
							$no++;
							$row++;
						 }
						?>
						<?php
							if($row<16){
								$k=16 - $row;
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
					<b style="text-align:center;margin-left:3px; font-size:12px !important;"><?= lang('​ អ្នកទទួល '); ?></b>
				</div>
				<div class="col-lg-3 col-sm-3 col-xs-3 text-center">
				<hr style="border:dotted 1px; width:90px; vertical-align:bottom !important; " />
					<b style="text-align:center;margin-left:3px;font-size:12px !important;;"><?= lang('​​អ្នកដឹក'); ?></b>
				</div>
				<div class="col-lg-3 col-sm-3 col-xs-3 text-center">
				<hr style="border:dotted 1px; width:90px; vertical-align:bottom !important; " />
					<b style="text-align:center;margin-left:3px;font-size:12px !important;"><?= lang('​អ្នកលក់'); ?></b>
				</div>
				<div class="col-lg-3 col-sm-3 col-xs-3 text-center">
				<hr style="border:dotted 1px; width:90px; vertical-align:bottom !important; " />
					<b style="text-align:center;margin-left:3px;font-size:12px !important;"><?= lang('ប្រធានឃ្លាំង'); ?></b>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>
</body>
</html>