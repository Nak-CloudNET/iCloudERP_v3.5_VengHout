<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<?php
	$address = '';
	$address.=$biller->address;
	$address.=($biller->city != '')? ', '.$biller->city : '';
	$address.=($biller->postal_code != '')? ', '.$biller->postal_code : '';
	$address.=($biller->state != '')? ', '.$biller->state : '';
	$address.=($biller->country != '')? ', '.$biller->country : '';
for($i=0;$i<2;$i++){
?>
<center>
	<table class="table-responsive" width="1024px" border="0" cellspacing="0" style="margin:auto;">
		<tr>
			<td rowspan="3" width="20%" style="virticle-align:middle;">
				<?php
			        if ($Settings->system_management == 'project') { ?>
			            <div class="text-center" style="margin-bottom:20px;">
			                <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo2; ?>"
			                     alt="<?= $Settings->site_name; ?>">
			            </div>
			    <?php } else { ?>
			            <?php if ($logo) { ?>
			                <div class="text-center" style="margin-bottom:20px;">
			                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
			                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
			                </div>
			            <?php } ?>	
			    <?php } ?>
			</td>
			<td colspan="3" width="80%" style="padding-left:50px;">
				<div style="font-family:'Arial'; font-size:24px; font-weight:bold;"><?= lang('sales_receipt'); ?></div>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="40%"></td>
						<td width="30%"> <?= lang('invoice_no_');?> </td>
						<td width="30%" style="padding-left:0px;">: <?= $inv->reference_no; ?> </td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="40%"></td>
						<td width="30%"> <?= lang('date_');?> </td>
						<td width="30%" style="padding-left:0px;">: <?= $this->erp->hrld($inv->date); ?> </td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="height:10px;" colspan="2"></td>
		</tr>
		<tr>
			<td colspan="2" width="65%" align="center" style="padding-top:5px;">
				<table width="100%">
					<tr>
						<td width="15%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('full_name_');?>: </td>
						<td width="25%">: <?= $customer->name; ?> </td>
						<td width="10%"> <?= lang('sex_'); ?> </td>
						<td width="10%"> <?= $customer->gender; ?> </td>
						<td width="15%" style="text-align:right; padding-right:15px;"> <?= lang('student_id_');?> </td>
						<td width="19%" style="padding-left:0px;">: <?= $customer->company; ?> </td>
					</tr>
					<tr>
						<td width="15%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('tel_');?> </td>
						<td width="25%">: <?= $customer->phone; ?> </td>
						<td> </td>
						<td width="15%" colspan="2" style="text-align:right; padding-right:20px;"> <?= lang('cashier_');?> </td>
						<td width="19%" style="padding-left:0px;">: <?= $cashier->username; ?> </td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped print-table order-table" style="'font-family:Khmer OS'; font-size:14px;">

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
							<th style="text-align:center;"><?= lang("ថ្លៃ​ទំនិញ <br/> Amount"); ?></th>
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
									<?= $product_name_setting ?>
									<?= $row->details ? '<br>' . $row->details : ''; ?>
									<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
									<?= $row->product_noted ? '<br>' . $row->product_noted : ''; ?>
								</td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?php echo $product_unit ?></td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
								<!-- <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->net_unit_price); ?></td> -->
								<td style="text-align:right; width:100px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free; ?></td>
								<?php
								if ($Settings->tax1) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
								}
								if ($Settings->product_discount && $inv->product_discount != 0) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
								}
								?>
								<td style="text-align:right; width:120px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; ?></td>
							</tr>
							<?php
							$r++;
						endforeach;
						if($r < 6) {
							for($k=$r;$k<=6;$k++){
								?>
								<tr>
									<td style="text-align:center; width:40px; vertical-align:middle;"> &nbsp; </td>
									<td style="vertical-align:middle;"> &nbsp; </td>
									<td style="width: 80px; text-align:center; vertical-align:middle;"> &nbsp; </td>
									<td style="width: 80px; text-align:center; vertical-align:middle;"> &nbsp; </td>
									<td style="text-align:right; width:100px;"> &nbsp; </td>
									<?php
									if ($Settings->tax1) {
										echo '<td style="width: 100px; text-align:right; vertical-align:middle;"> &nbsp; </td>';
									}
									if ($Settings->product_discount && $inv->product_discount != 0) {
										echo '<td style="width: 100px; text-align:right; vertical-align:middle;"> &nbsp; </td>';
									}
									?>
									<td style="text-align:right; width:120px;"> &nbsp; </td>
								</tr>
								<?php
							}
						}
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
									style="text-align:right; padding-right:10px;"><?= lang("សរុប/Total"); ?>
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
							echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px; font-weight:bold;">' . lang("បញ្ចុះតម្លៃ​រួម/Order_Discount") . '</td><td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
						}
						?>
						<?php if ($Settings->tax2 && $inv->order_tax != 0) {
							echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px; font-weight:bold;">' . lang("អាករលើតម្លៃបន្ថែម ".number_format($vattin->rate)."%/VAT(".number_format($vattin->rate)."%)") . '</td><td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
						}
						?>
						<?php if ($inv->shipping != 0) {
							echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("ការ​ដឹក​ជញ្ជូន/Shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . abs($this->erp->formatMoney($inv->shipping)) . '</td></tr>';
						}
						?>
						<tr>
							<td colspan="<?= $col; ?>"
								style="text-align:right; font-weight:bold;"><?= lang("សរុបរួម/Grand_Total"); ?>
							</td>
							<td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
						</tr>
						<tr>
							<td colspan="<?= $col; ?>"
								style="text-align:right; font-weight:bold;"><?= lang("បានបង់​/Paid"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->paid); ?></td>
						</tr>
						<tr>
							<td colspan="<?= $col; ?>"
								style="text-align:right; font-weight:bold;"><?= lang("សមតុល្យ​ទឹកប្រាក់/Balance"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->grand_total - $inv->paid); ?></td>
						</tr>

						</tfoot>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table width="100%" style="font-family:'Arial'; font-size:14px;">
					<tr>
						<td width="15%"> <?= lang('in_word'); ?> </td>
						<td width="85%">: <?= $this->erp->convert_number_to_words($this->erp->formatMoney($inv->paid)).' dolar'; ?> </td>
					</tr>
					<!--<tr>
						<td width="15%"> <?= lang('payment_method'); ?> </td>
						<td width="85%">: <?= $payment->paid_by; ?> </td>
					</tr>-->
					<tr>
						<td width="15%"> <?= lang('note'); ?> </td>
						<td width="85%">: <?= $payment->note; ?> </td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table border="0" cellspacing="0">
					<tr>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:50px;">
							<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important;  margin-bottom:2px;" />
							<b style="font-size:12px !important;text-align:center;margin-left:3px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​​អតិថិជន​  <br/> Customer`s Signature & Name'); ?></b>
						</td><td>&nbsp;</td><td>&nbsp;</td>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:50px;">
							&nbsp;
						</td><td>&nbsp;</td><td>&nbsp;</td>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:50px;">
							<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; margin-bottom:2px;" />
							<b style="font-size:12px !important;text-align:center;margin-left:3px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​ទទួល​  <br/> Receiver`s Signature & Name'); ?></b>
						</td> 
					</tr>						
				</table>
			</td>
		</tr>
		<?php
		if($i == 0){
		?>
		<tr>
			<td colspan="2">
				<hr width="100%" size="5" style="margin-top:15px; margin-bottom:10px;" />
			</td>
		</tr>
		<?php } ?>
	</table>
</center>
<?php
 } 
?>