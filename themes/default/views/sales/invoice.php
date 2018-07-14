<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<?php
	$address = '';
	$address.=$biller->address;
	$address.=($biller->city != '')? ', '.$biller->city : '';
	$address.=($biller->postal_code != '')? ', '.$biller->postal_code : '';
	$address.=($biller->state != '')? ', '.$biller->state : '';
	$address.=($biller->country != '')? ', '.$biller->country : '';
	
	$addr_cus = '';
	$addr_cus.=$customer->address;
	$addr_cus.=($customer->city != '')? ', '.$customer->city : '';
	$addr_cus.=($customer->postal_code != '')? ', '.$customer->postal_code : '';
	$addr_cus.=($customer->state != '')? ', '.$customer->state : '';
	$addr_cus.=($customer->country != '')? ', '.$customer->country : '';
?>
<center>
	<table class="table-responsive" width="1024px" border="0" cellspacing="0" style="margin:auto;">
		<tr>
			<td colspan="2" width="35%" style="virticle-align:middle;">
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
			<td colspan="3" width="65%" style="padding-left:50px;">
				<div style="font-family:'Khmer OS Muol Light'; font-size:20px;"><?= lang("វិក័យប័ត្រ"); ?></div>
				<div style="font-family:'Arial'; font-size:20px; padding-left:10px;"><?= lang("INVOICE"); ?></div>
			</td>
		</tr>
		<!--<tr>
			<td colspan="2"> </td>
			<td width="22%">
				<div style="font-family:'Khmer OS'; font-size:12px;"><?= $this->lang->line("លេខអត្តសញ្ញាណកម្ម អតប​ (VATTIN)"); ?></div>
			</td>
			<td colspan="2" width="40%">
				<div style="font-family:'Arial'; font-size:12px;">: <?= $biller->vat_no; ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> </td>
			<td width="22%">
				<div style="font-family:'Khmer OS'; font-size:12px;"><?= $this->lang->line("អាស័យដ្ឋាន"); ?></div>
			</td>
			<td colspan="2" width="40%">
				<div style="font-family:'Khmer OS'; font-size:12px;">: <?= $biller->cf4; ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> </td>
			<td width="22%">
				<div style="font-family:'Arial'; font-size:12px;"><?= $this->lang->line("Address"); ?></div>
			</td>
			<td colspan="2" width="40%">
				<div style="font-family:'Arial'; font-size:12px;">: <?= $address; ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> </td>
			<td  width="22%">
				<div style="font-family:'Arial'; font-size:12px;"><?= lang("tel"); ?></div>
			</td>
			<td style="font-family:'Arial'; font-size:12px;">: <?= $customer->phone;?></td>
		</tr>
		<tr>
			<td colspan="2"> </td>
			<td  width="22%">
				<div style="font-family:'Arial'; font-size:12px;"><?= lang("email"); ?></div>
			</td>
			<td style="font-family:'Arial'; font-size:12px;">: <?= $customer->email; ?></td>
		</tr>
		<tr>
			<td style="height:5px;" colspan="5"> <hr width="100%" size="2px" /> </td>
		</tr>
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<div style="font-family:'Khmer OS Muol Light'; font-size:20px;"><?= lang("វិក័យប័ត្រ​ អាករ"); ?></div>
				<div style="font-family:'Arial'; font-size:20px;"><?= lang("TAX INVOICE"); ?></div>
			</td>
		</tr>-->
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<table width="100%">
					<tr>
						<td width="15%" style="font-family:'Khmer OS Muol Light'; font-size:14px;">&nbsp;</td>
						<td width="35%">&nbsp;</td>
						<td width="25%" style="text-align:right;"> <?= lang('លេខរៀង​វិក្ក័យបត្រ / INVOICE No ');?> : </td>
						<td width="25%" style="padding-left:0px; text-align:right;"><?= $inv->reference_no; ?> </td>
					</tr>
					<tr>
						<td width="15%" rowspan="3" style="vertical-align:middle; font-size:14px; text-align:left;"> <?= lang('អតិថិជន / Customer');?> </td>
						<td width="35%" rowspan="3" style="vertical-align:middle; text-align:left;">: <?= $customer->name ? $customer->name : $customer->company; ?> </td>
						<td width="25%" style="text-align:right;"> <?= lang('កាលបរិច្ឆេទ / Date ');?> : </td>
						<td width="25%" style="padding-left:0px; text-align:right;"><?= date('d/m/Y'); ?> </td>
					</tr>
					<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px; text-align:right;"> <?= lang('អ្នក​លក់​ / Seller ');?> : </td>
						<td width="25%" style="text-align:right;"><?= $seller->username; ?></td>
					</tr>
					<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px; text-align:right;"> <?= lang('លេខទូរស័ព្ទ / Tel ');?> : </td>
						<td width="25%" style="text-align:right;"> <?= $seller->phone; ?> </td>
					</tr>
					<tr>
						<td width="15%" style="font-size:14px;"> <?= lang('អាសយដ្ឋាន / Address ');?></td>
						<td width="85%" colspan="3">: <?= $addr_cus; ?> </td>
					</tr>
					<tr>
						<td width="15%" style="font-size:14px;"> <?= lang('លេខទូរស័ព្ទ / Tel ');?></td>
						<td width="85%" colspan="3">: <?= $customer->phone; ?></td>
					</tr>
					<!--<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('លេខអត្តសញ្ញាណកម្ម អតប  (VATTIN)');?> </td>
						<td width="25%">: <?= $customer->vat_no; ?> </td>
					</tr>-->
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
						$discount_percentage = '';
						if (strpos($inv->order_discount_id, '%') !== false) {
							$discount_percentage = $inv->order_discount_id;
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
							echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px; font-weight:bold;">' . lang("បញ្ចុះតម្លៃ​រួម  <br/> Order_Discount") . '</td><td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;"><span class="pull-left">'.($discount_percentage?"(" . $discount_percentage . ")" : '').'</span>' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
						}
						?>
						<?php if ($Settings->tax2 && $inv->order_tax != 0) {
							echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px; font-weight:bold;">' . lang("អាករលើតម្លៃបន្ថែម ".number_format($vattin->rate)."% <br/> VAT(".number_format($vattin->rate)."%)") . '</td><td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
						}
						?>
						<?php if ($inv->shipping != 0) {
							echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("ការ​ដឹក​ជញ្ជូន​ <br/> Shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
						}
						?>
						<tr>
							<td colspan="<?= $col; ?>"
								style="text-align:right; font-weight:bold;"><?= lang("សរុបរួម <br/> Grand_Total"); ?>
							</td>
							<td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
						</tr>
						<tr>
							<td colspan="<?= $col; ?>"
								style="text-align:right; font-weight:bold;"><?= lang("បានបង់​ <br/> Paid"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->paid); ?></td>
						</tr>
						<tr>
							<td colspan="<?= $col; ?>"
								style="text-align:right; font-weight:bold;"><?= lang("សមតុល្យ​ទឹកប្រាក់ <br/> Balance"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->grand_total - $inv->paid); ?></td>
						</tr>

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
							<b style="font-size:10px;text-align:center;margin-left:3px;"><?= lang('​គណនេយ្យ​ករ​  <br/> Accountant`s Signature'); ?></b>
						</td><td>&nbsp;</td><td>&nbsp;</td>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:70px;">
							<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
							<b style="font-size:10px;text-align:center;margin-left:3px;"><?= lang('អ្នកលក់​​​  <br/> Seller`s Signature'); ?></b>
						</td><td>&nbsp;</td><td>&nbsp;</td>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:70px;">
							<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
							<b style="font-size:10px;text-align:center;margin-left:3px;"><?= lang('អ្នកដឹកជញ្ជូន​  <br/> Delivery`s Signature'); ?></b>
						</td><td>&nbsp;</td><td>&nbsp;</td>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:70px;">
							<hr style="border:dotted 1px; width:160px; vertical-align:bottom !important; " />
							<b style="font-size:10px;text-align:center;margin-left:3px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អតិថិជន  <br/> Customer`s Signature and Name'); ?></b>
						</td> 
					</tr>						
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="5" style="font-size:12px; font-family:'Khmer OS'; padding-top:20px;">
				<b><u>សម្គាល់​</u> : </b> ទំនិញបាន​ដាក់​ និង​ ទទួល​ដោយ​ត្រឹម​ត្រូវ។ <br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				ទំនិញទិញហើយ​មិន​អាច​ប្តូរ​វិញបាន​ទេ!
			</td>
		</tr>
	</table>
</center>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>