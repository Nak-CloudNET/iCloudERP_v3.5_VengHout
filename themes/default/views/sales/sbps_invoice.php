
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("sales") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
        }

        body:before, body:after {
            display: none !important;
        }

        .table th {
            text-align: center;
            padding: 5px;
        }

        .table td {
            padding: 4px;
        }

        .btn {
            border-radius: 0 !important;
            margin-right: 10px;
        }
		
		#SLData .tr td:nth-child(1) {
			border-left:1px solid white !important;
			border-bottom:1px solid white !important;
			
		}
    </style>
</head>

<body>

<div class="container">
	<div class="row">
		<div class="col-xs-12 text-center">
			<h4><?= $biller->company_kh ?></h4>
			<span style="font-weight:bold"><?= $biller->company ?></span>
			
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12" style="margin-top:10px">
			<span><?= $biller->cf4 ?></span><br>
			<span><?=lang('address')?> : <?= $biller->address ?></span><br>
			<span>លេខអត្តសញ្ញាណកម្ម <?=lang('VATTIN')?> : <?= $biller->vat_no ?></span>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 " style="margin-top:10px">
			<span style="font-weight:bold;font-size:17px;margin-left:200px">វិក្កយបត្រ អាករ Tax Invoice</span>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12" style="margin-top:10px">
			<div class="col-xs-6" style="padding-left-5px;">
				<span>អតិថជន / <?=lang('customer')?> : <?= $customer->names; ?></span><br>
				<span>Company Name /Customer : <?= $customer->company ; ?></span><br>
				<span>Tell : <?= $customer->phone ; ?></span><br>
				<span><?= $customer->address ; ?></span><br>
				<span class="text-center">VAT TIN : <?= $customer->vat_no ; ?></span><br>
			</div>
			<div class="col-xs-6">
				<p>លេខរៀងវិក្កយបត្រ៖ <?=lang('invoice')?> <?= $inv->reference_no;; ?></p>
				<p>កាលបរិច្ឆេទ <?=lang('date')?> <?= $this->erp->hrld($inv->date); ?></p>
				<p>លេខបញ្ឆាទិញ ៖  PO Number : <?= $inv->po ; ?></p>
				<p>គិតទៅ៖ &nbsp;&nbsp;&nbsp;&nbsp; Bill TO: <?= $inv->bill_to ; ?></p>
				
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12" style="margin-top:10px">
			<table id="SLData" class="table table-bordered table-hover table-striped" style="width: 100%;">
				<thead  style="font-size: 13px;">
				<tr>
					<th><span style="font-weight:normal">លរ</span><br><?= lang("no"); ?></th>
					<th><span style="font-weight:normal">កូដទំនិញ</span><br><?= lang("item_code"); ?></th>
					<th><span style="font-weight:normal">បរិយាយមុខទំនិញ</span><br><?= lang("description"); ?></th>
					<th><span style="font-weight:normal">ខ្នាតទំនិញ</span><br><?= lang("Packing"); ?></th>
					<th><span style="font-weight:normal">បរិមាណ</span><br><?= lang("QTY Unit"); ?></th>
					<th><span style="font-weight:normal">ថ្លៃឯកតា</span><br><?= lang("Unit Price"); ?></th>
					<th><span style="font-weight:normal">ថ្លៃទំនិញ</span><br><?= lang("subtotal"); ?></th>
				</tr>
				</thead>
				<tbody style="font-size: 13px;">
				<?php $r = 1;
				foreach ($rows as $row):
					?>
					<tr>
						<td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
						<td style="text-align:center; width:150px; vertical-align:middle;"><?= $row->product_code; ?></td>
						<td style="text-align:center;  vertical-align:middle;"><?= $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : ''); ?>
							<?= $row->details ? '<br>' . $row->details : ''; ?>
						</td>
						<td style="text-align:center; vertical-align:middle;">
							<?= $row->unit ?>
						</td>
						<td style="width: 80px; text-align:center; vertical-align:middle;">
							<?= $this->erp->formatQuantity($row->quantity); ?>
						</td>
						<td style="text-align:center; width:90px;vertical-align:middle;">$<?= $this->erp->formatMoney($row->real_unit_price); ?></td>
						<td style="text-align:right; vertical-align:middle; width:110px;">$<?= $this->erp->formatMoney($row->subtotal); ?></td>
					</tr>
					<?php
					$r++;
				endforeach;
				?>
				</tbody>
				<tfoot style="font-size: 13px;">
				<?php
				$col = 4;
				if ($Settings->product_serial) {
					$col++;
				}
				if ($Settings->product_discount) {
					$col++;
				}
				if ($Settings->tax1) {
					$col++;
				}
				if ($Settings->product_discount && $Settings->tax1) {
					$tcol = $col - 2;
				} elseif ($Settings->product_discount) {
					$tcol = $col - 1;
				} elseif ($Settings->tax1) {
					$tcol = $col - 1;
				} else {
					$tcol = $col;
				}
				?>
				<?php if ($inv->grand_total != $inv->total) { ?>
					<tr class="tr">
						<td colspan="<?= $tcol+2; ?>" style="text-align:right;">សរុប <?= lang("sub_total"); ?>
							
						</td>
						<td style="text-align:right;">$<?= $this->erp->formatMoney($inv->total + $inv->product_tax); ?></td>
					</tr>
				<?php } ?>
				<?php if ($inv->order_discount != 0) {
					echo '<tr class="tr"><td colspan="' . $col . '" style="text-align:right;">បញ្ចុះតម្លៃ ' . lang("discount") . '</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
				}
				?>
				<?php if ($inv->shipping != 0) {
					echo '<tr class="tr"><td colspan="' . $col . '" style="text-align:right;">ដឹកជញ្ចូន ' . lang("Delivery Charge") . '</td><td style="text-align:right;">' . abs($this->erp->formatMoney($inv->shipping)) . '</td></tr>';
				}
				?>
				<?php if ($Settings->tax2 && $inv->order_tax != 0) {
					echo '<tr class="tr"><td colspan="' . $col . '" style="text-align:right;">ពន្ឌអាករ ' . lang("VAT") . '</td><td style="text-align:right;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
				}
				?>
				<tr class="tr">
					<td colspan="<?= $col; ?>"
						style="text-align:right;">សរុបរួម <?= lang("Grand Total"); ?>
			
					</td>
					<td style="text-align:right;font-weight:bold;">$<?= $this->erp->formatMoney($inv->grand_total); ?></td>
				</tr>
				</tfoot>
			</table>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12" style="margin-top:40px">
			<div class="col-xs-6" style="font-weight:bold">
				<span>ហត្ថលេខា និង ឈ្មោះអ្នកទិញ</span></br>
				<span>Customer's Signature and Name</span>
			</div>
			<div class="col-xs-6 text-right" style="font-weight:bold">
				<span>ហត្ថលេខា និង ឈ្មោះអ្នកទិញ</span></br>
				<span>Customer's Signature and Name</span>
			</div>
		<div>
	</div>
	
</div>

</body>
</html>