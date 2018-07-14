<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?= $inv->do_reference_no ?></title>
	<link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
	<link href="<?php echo $assets ?>styles/bootstrap.min.css" rel="stylesheet">

	<style type="text/css">
		.container p {
			font-size: 14px !important;
		}

		thead tr {
			background-color: #333;
		}

		thead tr th {
			text-align: center !important;
			color: #FFF;
		}

		.table, .table tr td, .table tr th {
			border: 1px solid #000 !important;
		}

		@media print {
			.container {
				width: 95% !important;
				margin: 0 auto !important;
				padding: 0 !important;
			}

			.referno {
				padding-left: 90px !important;
			}

			.abc {
				margin-left: -25px !important;
			}
		}

	</style>

</head>
<body>
	<div class="container">

		<div class="row">
			<div class="col-sm-12 col-xs-12">	
				<center>
					<h3><u><strong><?= strtoupper(lang('delivery_order')) ?></strong></u></h3>
				</center>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-xs-6">
				<div class="row abc">
					<div class="col-sm-3 col-xs-3">
						<p><strong><?= lang('COMPANY NAME ') ?></strong></p>
						<p><?= lang('ADDRESS') ?></p>
						<p><?= lang('TEL') ?></p>
						<p><?= lang('SHIP TO') ?></p>
						<p><?= lang('ADDRESS') ?></p>
						
					</div>
					<div class="col-sm-9 col-xs-9">
						<p><strong>: <?= $biller->company ?></strong></p>
						<p>: <?= $biller->address ?></p>
						<p>: <?= $biller->phone ?></p>
						<p>: <?= $delivery->company_name ?></p>
						<p>: <?= $delivery->address ?></p>
						
					</div>
				</div>
			</div>
			
			<!-- <div class="col-sm-1 col-xs-1"></div> -->
			<div class="col-sm-6 col-xs-6">
				<div class="row referno" style="padding-left: 300px;">
					<div class="col-sm-4 col-xs-4" style="padding-right: 0; padding-left: 0">
						<p><?= lang('date') ?></p>
						<p><?= lang('PO_NO') ?></p>
						<p><?= lang('INVOICE_NO') ?></p>
						
					</div>
					<div class="col-sm-8 col-xs-8">
						<p>: <?= $this->erp->hrsd($inv->date) ?></p>
						<?php if ($inv->po_no) { ?>
							<p>: <?= $inv->po_no ?></p>
						<?php } elseif ($inv->po_no_so) { ?>
							<p>: <?= $inv->po_no_so ?></p>
						<?php } else { ?>
							<p>: <?php echo "";?></p>
						<?php } ?>
						<p>: <?= $delivery->sale_reference_no ?></p>
						
					</div>
				</div>
			</div>
		</div>

		<table class="table table-bordered table-hover table-striped" border="1">
			<thead>
				<tr>
					<th><?= lang("No"); ?></th>
                    <th style="width: 150px !important"><?= lang("Barcode"); ?></th> 
                    <th style="width: 150px !important"><?= lang("Description"); ?></th>  
                    <th><?= lang("UOM"); ?></th> 
                    <th><?= lang("Packing"); ?></th> 
                    <th><?= lang("QTY_Unit"); ?></th>
					<th><?= lang("Expiry_Date"); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
				$no = 1;
				
				if(is_array($rows)){
				foreach($rows as $row):
				//$this->erp->print_arrays($row);
				?>
				
				<tr>
					<td class="text-center"><?= $no ?></td>
					<td class="text-center"><?= $row->code ?></td>
					<td><?= $row->description ?></td>
					<?php if ($row->option_id >= 1) { ?>
					<td class="text-center"><?= $row->variant ?></td>
					<?php } else { ?>
					<td class="text-center"><?= $row->unit ?></td>		
					<?php } ?>
					
					<?php if ($row->option_id >= 1) { ?>
					<td class="text-center"><?= round($row->qty_unit); ?></td>
					<?php } else { ?>
					<td class="text-center"><?php echo "";?></td>		
					<?php } ?>
					
					<td class="text-center"><?= round($row->qty);?></td>
					<?php if ($row->expiry) { ?>
					<td class="text-center"><?= $this->erp->hrsd($row->expiry); ?></td>
					<?php } ?>
					<?php if ($row->sale_expiry) { ?>
					<td class="text-center"><?= $this->erp->hrsd($row->sale_expiry); ?></td>
					<?php } ?>
				</tr>
			<?php
				$no++;
				
				endforeach;
			}
			 ?>
			 <?php
				if ($no < 11) {
					$k = 11 - $no;
					for ($j=1; $j <= $k; $j++) {
						echo
							'<tr>
								<td height="34px" class="text-center">'.$no.'</td>
								<td></td>
								<td></td>
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

		<div class="row" style="margin-bottom: 50px !important;padding-left:50px;padding-right:50px;">
			
			<div class="col-sm-6 col-xs-6">
				<p><strong><?= lang('approved_by') ?></strong></p>
			</div>
			
			<div class="col-sm-6 col-xs-6 text-right">
				<p><strong><?= lang('received_by') ?></strong></p>
			</div>
		</div>
		<div class="row" style="margin-bottom: 50px !important;padding-left:50px;padding-right:50px;">
			
			<div class="col-sm-6 col-xs-6">
				<p><strong><?= lang('SBPS International Co.,ltd') ?></strong></p>
			</div>
			
			<div class="col-sm-6 col-xs-6 text-right" >
				<p><strong><?= lang('Customer') ?></strong></p>
			</div>
		</div>

	</div>
	
</body>
</html>