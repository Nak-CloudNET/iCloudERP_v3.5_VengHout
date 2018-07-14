<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<?php
	$address = '';
	$address.=$biller->address;
	$address.=($biller->city != '')? ', '.$biller->city : '';
	$address.=($biller->postal_code != '')? ', '.$biller->postal_code : '';
	$address.=($biller->state != '')? ', '.$biller->state : '';
	$address.=($biller->country != '')? ', '.$biller->country : '';
?>
	<div class="container" style="width: 1024px;">
		<div class="row">
			<div class="col-sm-3 col-xs-3">
				<img style="width:120px; float: right;" src="<?=base_url() . 'assets/uploads/logos/ptc.png'; ?>">
			</div>
			<div class="col-sm-9 col-xs-9">
				<p style="padding:0px; font-size:20px;font-family:'Khmer OS Muol Light';">ក្រុមហ៊ុន ភី ធី & ស៊ី អេជីនារីង</p>
				<p>P T & C ENGINEERING CO., LTD
				<br>ផ្ទះ 48, ផ្លូវលេខ 11AZ, សង្កាត់ ទឹកថ្លា, ខណ្ឌ សែនសុខ, រាជធានីភ្នំពេញ<br>
				#48, Street 11AZ, Sangkat Teuk Thla , Khan Sen Sok, Phnom Penh, Cambodia<br>
				លេខអត្តសញ្ញាណកម្មសារពីពន្ធ / VAT TIN #K009-901500714
				</p>
			</div>
		</div>
	</div>

	<table class="table-responsive" width="1024px" border="0" cellspacing="0" style="margin:auto;">
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<div style="font-family:'Khmer OS Muol Light'; font-size:20px;"><?= lang("វិក័យប័ត្រពន្ធ"); ?></div>
				<div style="font-family:'Arial'; font-size:20px;"><?= lang("tax_invoice"); ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<table width="100%">
					<tr>
						<td width="25%" style="font-family:'Khmer OS Muol Light'; font-size:14px;"> <?= lang('អតិថិជន / Customer');?> </td>
						<td width="50%">: <?= $customer->name ? $customer->name : $customer->company; ?> </td>
						<td width="5px" rowspan="2"> </td>
						<td width="10%" rowspan="2"> <?= lang('លេខវិក្ក័យបត្រ <br/> Invoice No');?> </td>
						<td width="15%" rowspan="2" style="padding-left:0px;">: <?= $inv->reference_no; ?> </td>
					</tr>
					<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('ឈ្មោះ​ក្រុមហ៊ុន ឬ អតិថិជន <br/> Company name / Customer');?> </td>
						<td width="50%">: <?= $customer->company ? $customer->company : $customer->name; ?> </td>
					</tr>
					<tr>
						<td width="25%" style="font-family:'Khmer OS Muol Light'; font-size:14px;"> <?= lang('ទូរស័ព្ទ​លេខ / Telephone No');?> </td>
						<td width="50%">: <?= $customer->phone; ?> </td>
						<td width="5px" rowspan="2"> </td>
						<td width="10%" rowspan="2"> <?= lang('កាលបរិច្ឆេទ <br/> Date');?> </td>
						<td width="15%" rowspan="2" style="padding-left:0px;">: <?= $this->erp->hrld($inv->date); ?> </td>
					</tr>
					<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('លេខអត្តសញ្ញាណកម្ម អតប  (VATTIN)');?></td>
						<td width="50%">: <?= $customer->vat_no; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped print-table order-table">

						<thead>

						<tr>
							<th style="text-align:center;"><?= lang("ល-រ <br/> Nº"); ?></th>
							<th style="text-align:center;"><?= lang("បរិយាយមុខទំនិញ <br/> Description"); ?></th>
							<th style="text-align:center;"><?= lang("ខ្នាត <br/> Unit"); ?></th>
							<th style="text-align:center;"><?= lang("បរិមាណ <br/> Quantity"); ?></th>
							<th style="text-align:center;"><?= lang("ថ្លៃ​ឯកតា <br/> Unit_Price"); ?></th>
							<?php
							if ($Settings->tax1) {
								echo '<th style="text-align:center;">' . lang("អាករឯកតា <br/> Tax") . '</th>';
							}
							if ($Settings->product_discount && $inv->product_discount != 0) {
								echo '<th style="text-align:center;">' . lang("បញ្ចុះតម្លៃឯកតា​ <br/> Discount") . '</th>';
							}
							?>
							<th style="text-align:center;"><?= lang("តម្លៃ <br/> Amount"); ?></th>
						</tr>

						</thead>

						<tbody>

						<?php $r = 1;
						$tax_summary = array();
						foreach ($rows as $row):
						$free = lang('free');
						$product_unit = '';
						if($row->variant){
							$product_unit = $row->variant;
						}else{
							$product_unit = $row->unit;
						}
						
						$product_name_setting;
						if($pos->show_product_code == 0) {
							$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
						}else{
							$product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
						}
						?>
							<tr>
								<td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
								<td style="vertical-align:middle;">
									<?= $row->product_noted ?>
								</td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?php echo $product_unit ?></td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
								<!-- <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->net_unit_price); ?></td> -->
								<td style="text-align:right; vertical-align:middle; width:100px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free; ?></td>
								<?php
								if ($Settings->tax1) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
								}
								if ($Settings->product_discount && $inv->product_discount != 0) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
								}
								?>
								<td style="text-align:right; vertical-align:middle; width:120px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; ?></td>
							</tr>
							<?php
							$r++;
						endforeach;
						?>
						</tbody>
						<tfoot>
						<?php
						$col = 5;
						if ($Settings->product_discount && $inv->product_discount != 0) {
							$col++;
						}
						if ($Settings->tax1) {
							$col++;
						}
						if ($Settings->product_discount && $inv->product_discount != 0 && $Settings->tax1) {
							$tcol = $col - 2;
						} elseif ($Settings->product_discount && $inv->product_discount != 0) {
							$tcol = $col - 1;
						} elseif ($Settings->tax1) {
							$tcol = $col - 1;
						} else {
							$tcol = $col;
						}
						?>
						<?php if ($inv->grand_total != $inv->total) { ?>
							<tr>
								<td colspan="<?= $tcol; ?>"
									style="text-align:right; padding-right:10px;"><?= lang("សរុប <br/> Total"); ?>
								</td>
								<?php
								if ($Settings->tax1) {
									echo '<td style="text-align:right; padding-top:20px;">' . $this->erp->formatMoney($inv->product_tax) . '</td>';
								}
								if ($Settings->product_discount && $inv->product_discount != 0) {
									echo '<td style="text-align:right; vertical-align:middle;">' . $this->erp->formatMoney($inv->product_discount) . '</td>';
								}
								?>
								<td style="text-align:right; padding-right:10px; vertical-align:middle;"><?= $this->erp->formatMoney($inv->total + $inv->product_tax); ?></td>
							</tr>
						<?php } ?>
						<?php if ($inv->order_discount != 0) {
							echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px; font-weight:bold;">' . lang("បញ្ចុះតម្លៃ​រួម  <br/> Order_Discount") . '</td><td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
						}
						?>
						<?php if ($Settings->tax2 && $inv->order_tax != 0) {
							echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px; font-weight:bold;">' . lang("អាករលើតម្លៃបន្ថែម ".number_format($vattin->rate)."% <br/> VAT(".number_format($vattin->rate)."%)") . '</td><td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
						}
						?>
						<?php if ($inv->shipping != 0) {
							echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("ការ​ដឹក​ជញ្ជូន​ <br/> Shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; vertical-align:middle; padding-right:10px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
						}
						?>
						<tr>
						<?php 
							$cols = 4;
							if($row->item_discount>0){
								$cols = 5;
							}
						?>
						<td colspan="<?= $cols ?>"  rowspan="1"><?=$biller->invoice_footer;?></td>
							<td colspan="2"
								style="text-align:right; font-weight:bold;"><?= lang("សរុបរួម <br/> Grand_Total"); ?>
							</td>
							<td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
						</tr>
						<?php if($inv->paid != 0) {?>
						<tr>
							<td colspan="2"
								style="text-align:right; font-weight:bold;"><?= lang("បានបង់​ <br/> Paid"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->paid); ?></td>
						</tr>
						<tr>
							<td colspan="2"
								style="text-align:right; font-weight:bold;"><?= lang("សមតុល្យ​ទឹកប្រាក់ <br/> Balance"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->grand_total - $inv->paid); ?></td>
						</tr>
						<?php }?>
						</tfoot>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<table border="0" cellspacing="0">
					<tr>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:70px;">
							<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
							<b style="font-size:10px;text-align:center;margin-left:3px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​ទិញ​  <br/> Customer`s Signature & Name'); ?></b>
						</td><td>&nbsp;</td><td>&nbsp;</td>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:50px;">
							&nbsp;
						</td><td>&nbsp;</td><td>&nbsp;</td>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:70px;">
							<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
							<b style="font-size:10px;text-align:center;margin-left:3px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​លក់​  <br/> Seller`s Signature & Name'); ?></b>
						</td> 
					</tr>						
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="5" style="font-size:12px; font-family:'Khmer OS'; padding-top:20px;">
				<b><u>សម្គាល់​</u> : </b> ច្បាប់​​ដើម​សម្រាប់​អ្នក​ទិញ​ ច្បាប់​ចម្លង​សម្រាប់​អ្ន​ក​លក់​។ <br/>
				<b><u>Note</u> : </b> Original invoice for customer copy invoice for seller. 
			</td>
		</tr>
	</table>
</center>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>