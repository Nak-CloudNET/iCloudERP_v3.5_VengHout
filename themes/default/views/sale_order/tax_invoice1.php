<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<?php
	$address = '';
	$address.=$biller->address;
	$address.=($biller->city != '')? ', '.$biller->city : '';
	$address.=($biller->postal_code != '')? ', '.$biller->postal_code : '';
	$address.=($biller->state != '')? ', '.$biller->state : '';
	$address.=($biller->country != '')? ', '.$biller->country : '';
?>
<center>
<br>
	<table class="table-responsive" width="1024px" border="0" cellspacing="0" style="margin:auto;">
		<tr>
			<td rowspan="6">
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
			
		</tr>
		<tr>
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
			<td style="font-family:'Arial'; font-size:12px;">: <?= $biller->phone;?></td>
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
				<div style="font-family:'Khmer OS Muol Light'; font-size:20px;"><?= lang("វិក័យប័ត្រពន្ធ"); ?></div>
				<div style="font-family:'Arial'; font-size:20px;"><?= lang("tax_invoice"); ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<table width="100%">
					<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('ឈ្មោះ​ក្រុមហ៊ុន ឬ អតិថិជន <br/> Company name / Customer');?> </td>
						<td width="50%">: <?= $customer->company ? $customer->company : $customer->name; ?> </td>
						<td width="5px" rowspan="2"> </td>
						<td width="10%" rowspan="2"> <?= lang('លេខវិក្ក័យបត្រ <br/> Invoice No');?> </td>
						<td width="15%" rowspan="2" style="padding-left:0px;">: <?= $inv->reference_no; ?> </td>
					</tr>
					<tr>						
						<td width="25%" style="font-family:'Khmer OS Muol Light'; font-size:14px;"> <?= lang('អតិថិជន / Customer');?> </td>
						<td width="50%">: <?= $customer->name ? $customer->name : $customer->company; ?> </td>
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
							<th style="text-align:center;"><?= lang("លេខកូដទំនិញ<br/> Product Code"); ?></th>
							<th style="text-align:center;"><?= lang("បរិយាយមុខទំនិញ <br/> Description"); ?></th>
							<th style="text-align:center;"><?= lang("ខ្នាត <br/> Unit"); ?></th>
							<th style="text-align:center;"><?= lang("បរិមាណ <br/> Quantity"); ?></th>
							<th style="text-align:center;"><?= lang("ថ្លៃ​ឯកតា <br/> Unit_Price"); ?></th>
							<?php
							if ($Settings->product_discount) {
								echo '<th style="text-align:center;">' . lang("បញ្ចុះតម្លៃឯកតា​ <br/> Discount") . '</th>';
							}
							if ($Settings->tax1) {
								echo '<th style="text-align:center;">' . lang("អាករឯកតា <br/> Tax") . '</th>';
							}
							
							?>
							<th style="text-align:center;"><?= lang("តម្លៃសរុប <br/> Amount"); ?></th>
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
							$product_unit = $row->uname;
						}
						
						$product_name_setting;
						if($setting->show_code == 0) {
							$product_name_setting = $row->product_name;
						}else {
							if($setting->separate_code == 0) {
								$product_name_setting = $row->product_name;
							}else {
								$product_name_setting = $row->product_name;
							}
						}
						
						
						?>
							<tr>
								 <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
								<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
								<td style="vertical-align:middle;">
									<?= $row->product_code ?>
								</td>
								<?php } ?>
								<td style="vertical-align:middle;">
									<?= $product_name_setting ?>
									<?= $row->details ? '<br>' . $row->details : ''; ?>
									<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
								</td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?php echo $product_unit ?></td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
								<!-- <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->net_unit_price); ?></td> -->
								<td style="text-align:right; vertical-align:middle; width:100px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free; ?></td>
								<?php
								if ($Settings->product_discount) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount*$row->quantity) . '</td>';
								}
								if ($Settings->tax1) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
								}
								?>
								<td style="text-align:right; vertical-align:middle; width:120px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; ?></td>
							</tr>
							<?php
							$total += $row->subtotal;
							$r++;
						endforeach;
						?>
						</tbody>
						<tfoot>
						<?php
						$col = 3;
						$rol = 0;
						$tcol = 0;
						
						if ($Settings->product_discount) {
							$col++;
							$tcol++;
						}
						if ($Settings->tax1) {
							$col++;
							$tcol++;
						}
						if ($inv->grand_total != $inv->total) {
							$rol++;
						}
						if ($inv->order_discount != 0) {
							$rol++;
						}
						if ($inv->shipping != 0) {
							$rol++;
						}
						if ($Settings->tax2 && $inv->order_tax != 0) {
							$rol++;
						}
						if($deposit->deposit > 0){
							$rol += 2;
						}
						$rol++;
						$tcol++;
						?>
						<tr>
							<td rowspan="<?= $rol; ?>" colspan="<?= $col; ?>">
								<p style="font-weight: bold !important;"><?= lang("note"); ?>:</p>
								<div><?= $this->erp->decode_html($inv->note); ?></div>
							</td>
							<td style="text-align:right; padding-right:10px;">
								<?= lang("សរុប <br/> Total"); ?>
							</td>
							<?php
							if ($Settings->product_discount) {
								echo '<td style="text-align:right; vertical-align:middle;">' . $this->erp->formatMoney($inv->product_discount) . '</td>';
							}
							if ($Settings->tax1) {
								echo '<td style="text-align:right; padding-top:20px;">' . $this->erp->formatMoney($inv->product_tax) . '</td>';
							}
							?>
							<td style="text-align:right; padding-right:10px; vertical-align:middle;"><?= $this->erp->formatMoney($total); ?></td>
						</tr>
						<?php if ($inv->order_discount != 0) {
							echo '<tr><td colspan="'.$tcol.'" style="text-align:right; padding-right:10px; font-weight:bold;">' . lang("បញ្ចុះតម្លៃ​រួម  <br/> Order_Discount") . '</td><td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
						}
						?>
						<?php if ($inv->shipping != 0) {
							echo '<tr><td colspan="'.$tcol.'" style="text-align:right; vertical-align:middle !important; padding-right:10px;font-weight:bold;">' . lang("ការ​ដឹក​ជញ្ជូន​ <br/> Shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; vertical-align:middle; padding-right:10px;font-weight:bold; padding-top:20px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
						}
						?>
						<?php if ($Settings->tax2 && $inv->order_tax != 0) {
							echo '<tr><td colspan="'.$tcol.'" style="text-align:right; padding-right:10px; font-weight:bold;">' . lang("អាករលើតម្លៃបន្ថែម ".number_format($inv->tax)."% <br/> VAT(".number_format($inv->tax)."%)") . '</td><td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
						}
						?>
						<?php if ($inv->order_discount != 0 || $inv->shipping != 0 || ($Settings->tax2 && $inv->order_tax != 0)) { ?>
							<tr>
								<td colspan="<?= $tcol ?>"
									style="text-align:right; font-weight:bold;"><?= lang("សរុបរួម <br/> Grand_Total"); ?>
								</td>
								<td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
							</tr>
						<?php } ?>
						<?php if($deposit->deposit > 0){?>
						<tr>
							<td colspan="<?= $tcol ?>"
								style="text-align:right; font-weight:bold;"><?= lang("បានកក់ <br/> Deposit"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($deposit->deposit); ?></td>
						</tr>
						<tr>
							<td colspan="<?= $tcol ?>"
								style="text-align:right; font-weight:bold;"><?= lang("សមតុល្យ​ទឹកប្រាក់ <br/> Balance"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->grand_total - $deposit->deposit); ?></td>
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