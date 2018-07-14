<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<title><?php echo $this->lang->line("transfer_item_chea_kheng") . " " . $inv->reference_no; ?></title>
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
				<table>
					<tr>
						<td><?=lang('មកពី ')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><strong><?= $from_warehouse->name ."&nbsp;&nbsp;(&nbsp".$from_warehouse->code." )"; ?></strong></td>
					</tr>
					<tr>
						<td><?=lang('អស័យដ្ឋាន​ ')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?= $from_warehouse->address?></td>
					</tr>
					<tr>
						<td><?=lang("អ្នកប្រចាំការ")?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
					</tr>
					<?php if($from_warehouse->phone){ ?>
					<tr>
						<td><?=lang('ទំនាក់ទំនង ')?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td><?= $from_warehouse->phone ?> 
								<?php if($from_warehouse->email){ ?>
								<?php echo ' / '.$from_warehouse->email ?>
						</td>
								<?php } ?>
					</tr>
					<?php } ?>
				</table>
			</div>
			<div class="col-lg-6 col-sm-6 col-xs-6" style="padding: 10px;">
				<table>
					<tr>
						<td style="font-size:17px !important;"><?=lang('ទៅ')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><strong><?= $to_warehouse->name ."&nbsp;&nbsp;(&nbsp".$to_warehouse->code." )"; ?></strong></td>
					</tr>
					
					<tr>
						<td style="font-size:17px !important;"><?=lang('អស័យដ្ឋាន​ ')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><?= $to_warehouse->address?></td>
						
					</tr>
					<tr>
						<td><?=lang("អ្នកប្រចាំការ")?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
					</tr>
					<?if($to_warehouse->phone){?>
					<tr>
						<td><?=lang("ទំនាក់ទំនង")?></td>
						<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td> 
						<td><?= $to_warehouse->phone?> / <?= $to_warehouse->email?></td>
					</tr>
					<?php }?>
					<tr>
						<td style="font-size:17px !important;"><?=lang('ថ្ងៃខែ')?></td>
						<td style="font-size:17px !important;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
						<td style="font-size:17px !important;"><?= $this->erp->hrld($inv->date);?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	</br>
	<div class="row">
		<table class="table table-bordered table-hover" border="1">
			<thead>
				<tr>
					<th style="font-size:17px !important;" class="text-center"><?=lang('ល.រ ')?></th>
					<th style="font-size:17px !important;"class="text-center"><?=lang('លេខកូដទំនិញ ')?></th>
					<th style="width:25% !important;font-size:17px !important;"class="text-center"><?=lang('ឈ្មោះទំនិញ ')?></th>
					<th style="width:10% !important;font-size:17px !important;"class="text-center"><?=lang('ឯកតា')?></th>
					<th style="font-size:17px !important;"class="text-center"><?=lang('ម៉េត្រការ៉េ/កេស ')?></th>
					<th style="font-size:17px !important;"class="text-center"><?=lang('ចំនួន')?></th>
				</tr>
			</thead>
			<tbody>
			<?php 
				$r= 1;
				$erow = 1;
				$tQty = 0;
			?>
			<?php foreach ($rows as $row){ 
				//$this->erp->print_arrays($row);
				$product_unit = '';
                if($row->variant){
                    $product_unit = $row->variant;
                }else{
                    $product_unit = $row->name;
                }
			?>
				<tr>
					<td style=" text-align:center; vertical-align:middle;"><?=$r;?></td>
					<td style="text-align:left; vertical-align:middle;">
						<?=$row->product_code ?>
					
					</td>
					<td style="text-align:left; vertical-align:middle;">
						<?= $row->product_name;?>					
					</td>
					
					<td style="text-align:center; vertical-align:middle;width: 15px;">
                       <?php
							if($row->piece != 0){ 
								echo $str_unit;
								
							}else{ 
								echo $product_unit;
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
								echo $this->erp->formatQuantity($row->quantity);
							}
						?>
					</td>
				</tr>
				<?php
					$r++;
					$erow++;
					
				} ?>
				
				<?php
					if($erow<16){
						$k=16 - $erow;
						for($j=1;$j<=$k;$j++){
							echo  '<tr>
									<td height="34px" class="text-center">'.$r.'</td>
									<td style="width:34px;"></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>

									
									
								</tr>';
							$r++;
						}
					}
				?>

				<tr>
					<td colspan="5" style="text-align:left; "><?='សរុប :'?></td>
					<td style="text-align:left; ">
						<div class="col-xs-3 text-left">$</div>
						<div class="col-xs-7 text-left">
							<?= $this->erp->formatQuantity($row->TQty); ?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	</br>
	</br>
	</br>
	</br>
	</br>
	<div class="row">
		<div class="col-lg-12 col-sm-12 col-xs-12" id="footer">
			<div class="col-lg-4 col-sm-4 col-xs-4 text-center">
				<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
				<b style="text-align:center;margin-left:3px; font-size:16px !important;"><?= lang('អ្នក​បញ្ជេញទំនេញ '); ?></b>
			</div>
			<div class="col-lg-4 col-sm-4 col-xs-4 text-center">
			<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
				<b style="text-align:center;margin-left:3px;font-size:16px !important;;"><?= lang('អ្នកដឹកជញ្ជូនទំនិញ '); ?></b>
			</div>
			<div class="col-lg-4 col-sm-4 col-xs-4 text-center">
			<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
				<b style="text-align:center;margin-left:3px;font-size:16px !important;"><?= lang('អ្នកទទួលទំនិញ'); ?></b>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>