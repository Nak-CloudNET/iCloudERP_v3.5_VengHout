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
		<center>
			<?php if ($Settings->system_management == 'project') { ?>
				<img src="<?= base_url() ?>assets/uploads/logos/<?= $Settings->logo2; ?>" style="width: 300px; margin-top: 20px" />
				<h2><strong><?= $Settings->site_name ?></strong></h2>
			<?php } else { ?>
					<img src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" style="width: 300px; margin-top: 20px" />
					<h2><strong><?= $biller->company ?></strong></h2>
			<?php } ?>
		</center>

		<div class="row">
			<div class="col-sm-12 col-xs-12">	
				<center>
					<p><?= $biller->address ?></p>
					<p><?= lang('tel') ?>: (855) <?= $biller->phone ?></p>
					<p>www.charleswembley.com</p>
					<h4><u><strong><?= strtoupper(lang('delivery_order')) ?></strong></u></h4>
				</center>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6 col-xs-6">
				<div class="row abc">
					<div class="col-sm-3 col-xs-3">
						<p><strong><?= lang('to') ?></strong></p>
						<p><?= lang('address') ?></p>
						<p><?= lang('attn') ?></p>
						<p><?= lang('tel_no') ?></p>
					</div>
					<div class="col-sm-9 col-xs-9">
						<p><strong>: <?= $customer->name ?></strong></p>
						<p>: <?= $customer->address ?></p>
						<p><strong>: <?= $inv->saleman ?></strong></p>
						<p>: <?= $customer->phone ?></p>
					</div>
				</div>
			</div>
			<!-- <div class="col-sm-1 col-xs-1"></div> -->
			<div class="col-sm-6 col-xs-6">
				<div class="row referno" style="padding-left: 300px;">
					<div class="col-sm-3 col-xs-3" style="padding-right: 0; padding-left: 0">
						<p><?= lang('do_no') ?></p>
						<p><strong><?= lang('refer_no') ?></strong></p>
						<p><?= lang('date') ?></p>
					</div>
					<div class="col-sm-9 col-xs-9">
						<p>: <?= $inv->do_reference_no ?></p>
						<p><strong>: <?= $inv->sale_reference_no ?></strong></p>
						<p>: <?= $this->erp->hrsd($inv->date) ?></p>
					</div>
				</div>
			</div>
		</div>

		<table class="table table-bordered table-hover" border="1">
			<thead>
				<tr>
					<th><?= lang('item') ?></th>
					<th><?= lang('brand') ?></th>
					<th><?= lang('description') ?></th>
					<th><?= lang('model') ?></th>
					<th><?= lang('unit') ?></th>
					<th><?= lang('qty') ?></th>
					<th><?= lang('remark') ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
				$no = 1;
				$row = 1;
			?>
			<?php foreach($inv_items as $inv_item) { ?>
				<tr>
					<td style="border-top:none !important;border-bottom:none !important; text-align: center;"><?= $no ?></td>
					<td style="border-top:none !important;border-bottom:none !important; text-align: center;"><?= $inv_item->brand ?></td>
					<td style="border-top:none !important;border-bottom:none !important;"><?= $inv_item->description ?></td>
					<td style="border-top:none !important;border-bottom:none !important;"></td>
					<?php if ($inv_item->option_id >= 1) { ?>
						<td style="border-top:none !important;border-bottom:none !important; text-align: center;"><?= $inv_item->variant ?></td>
						<td style="border-top:none !important;border-bottom:none !important; text-align: center;"><?= $this->erp->formatQuantity($inv_item->qty) ?></td>
					<?php } else { ?>
						<td style="border-top:none !important;border-bottom:none !important; text-align: center;"><?= $inv_item->unit ?></td>
						<td style="border-top:none !important;border-bottom:none !important; text-align: center;"><?= $this->erp->formatQuantity($inv_item->qty) ?></td>
					<?php } ?>
					<td style="border-top:none !important;border-bottom:none !important;"></td>
				</tr>
			<?php
				$no++;
				$row++;
			 }
				if ($row < 15) {
					$k = 15 - $row;
					for ($j=1; $j <= $k; $j++) {
						echo
							'<tr>
								<td height="34px" style="border-top:none !important;border-bottom:none !important;"></td>
								<td style="border-top:none !important;border-bottom:none !important;"></td>
								<td style="border-top:none !important;border-bottom:none !important;"></td>
								<td style="border-top:none !important;border-bottom:none !important;"></td>
								<td style="border-top:none !important;border-bottom:none !important;"></td>
								<td style="border-top:none !important;border-bottom:none !important;"></td>
								<td style="border-top:none !important;border-bottom:none !important;"></td>
							</tr>';
					}
					
				}
			?>
			</tbody>
		</table>

		<div class="row" style="margin-bottom: 50px !important;">
			<div class="col-sm-3 col-xs-3">
				<p><strong><?= lang('prepared_by') ?></strong></p>
			</div>
			<div class="col-sm-3 col-xs-3">
				<p><strong><?= lang('approved_by') ?></strong></p>
			</div>
			<div class="col-sm-3 col-xs-3">
				<p><strong><?= lang('deliveried_by') ?></strong></p>
			</div>
			<div class="col-sm-3 col-xs-3">
				<p><strong><?= lang('received_by') ?></strong></p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-3 col-xs-3">
				<p><strong><?= lang('name') ?>:</strong> .......................</p>
				<br />
				<p><?= lang('date') ?>: .........................</p>
			</div>
			<div class="col-sm-3 col-xs-3">
				<p><strong><?= lang('name') ?>:</strong> .......................</p>
				<br />
				<p><?= lang('date') ?>: .........................</p>
			</div>
			<div class="col-sm-3 col-xs-3">
				<p><strong><?= lang('name') ?>:</strong> .......................</p>
				<br />
				<p><?= lang('date') ?>: .........................</p>
			</div>
			<div class="col-sm-3 col-xs-3">
				<p><strong><?= lang('name') ?>:</strong> .......................</p>
				<br />
				<p><?= lang('date') ?>: .........................</p>
			</div>
		</div>

	</div>
	
</body>
</html>