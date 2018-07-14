<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title></title>
	<link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
	<link href="<?php echo $assets ?>styles/bootstrap.min.css" rel="stylesheet">
	<style type="text/css">
		@media print {
			.container {
				width: 95% !important;
				margin: 0 auto !important;
				padding: 0 !important;
			}
			h1 {
				margin-top: 30px !important;
			}
		}

		.table tr td, .table tr th {
			border: 1px solid #000 !important;
			line-height: 2 !important;
		}

	</style>
</head>
<body>
<div class="container">
	<?php if ($Settings->system_management == 'project') { ?>
		<div class="row">
			<div class="col-sm-3 col-xs-2">
				<img src="<?= base_url() ?>assets/uploads/logos/<?= $Settings->logo2; ?>" style="width: 220px; margin-top: 20px; margin-bottom: 20px;" />
			</div>
			<div class="col-sm-9 col-xs-10">
				<h1 style="margin-top: 20px;"><?= $Settings->site_name ?></h1>
			</div>
		</div>
	<?php } else { ?>
			<div class="row">
				<div class="col-sm-2 col-xs-2">
					<img src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" style="width: 220px; margin-top: 20px; margin-bottom: 20px;" />
				</div>
				<div class="col-sm-10 col-xs-10">
					<h1 style="margin-top: 20px;"><?= $biller->company ?></h1>
				</div>
			</div>
	<?php } ?>

	<table class="table table-bordered table-hover table-condensed">
		<tbody>
			<tr>
				<th rowspan="2" colspan="2"><h2><?= lang('transfer_invoice_kh') ?></h2></th>
				<th colspan="3"><?= lang('date_kh') ?>&nbsp;<?= $this->erp->hrsd($inv->date) ?></th>
			</tr>
			<tr>
				<th colspan="3"><?= lang('reference_no_kh') ?>&nbsp;<?= $inv->transfer_no ?></th>
			</tr>
			<tr>
				<th colspan="2"><?= lang('from_warehouse_kh') ?>&nbsp;<?= $from_warehouse->name ?></th>
				<th colspan="3"><?= lang('to_warehouse_kh') ?>&nbsp;<?= $to_warehouse->name ?></th>
			</tr>
			<tr>
				<td class="text-center" style="width: 100px !important"><?= lang('no_kh') ?></td>
				<td class="text-center"><?= lang('description_kh') ?></td>
				<td class="text-center"><?= lang('unit_kh') ?></td>
				<td class="text-center"><?= lang('quantity_kh') ?></td>
				<td class="text-center"><?= lang('other_kh') ?></td>
			</tr>
			<?php 
				$row_number = 1;
				$empty_row = 1;
			?>
			<?php foreach ($rows as $row): ?>
				<tr>
					<td class="text-center"><?= $row_number ?></td>
					<td><?= $row->product_name ?></td>
					<td class="text-center"><?= $row->unit ?></td>
					<td class="text-center"><?= $this->erp->formatQuantity($row->quantity) ?></td>
					<td></td>
				</tr>
			<?php
				$row_number++;
				$empty_row++;
			?>
			<?php endforeach ?>
			<?php
				if ($empty_row < 16) {
					$k=16 - $empty_row;
					for ($j = 1; $j <= $k; $j++) {
						echo  '<tr>
								<td class="text-center" height="34px">'.$row_number.'</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>';
							$row_number++;
					}
				}
			?>

			<tr>
				<td colspan="4" class="text-center"><?= lang('total_kh') ?></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2"><?= lang('អ្នកអនុញ្ញាត') ?></td>
				<td></td>
				<td><?= lang('អ្នកដឹកជញ្ជូន') ?></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2"><?= lang('អ្នកត្រួតពិនិត្យ') ?></td>
				<td></td>
				<td><?= lang('អ្នកទទួល') ?></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2"><?= lang('អ្នកប្រគល់ទំនិញ') ?></td>
				<td></td>
				<td><?= lang('អ្នកត្រួតពិនិត្យ') ?></td>
				<td></td>
			</tr>
		</tbody>
	</table>

</div>
</body>
</html>