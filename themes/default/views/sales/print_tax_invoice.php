<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<style>

	.img_logo
	{
		position: absolute;
		margin-top: -20px;
		margin-left: 0px; 
	}

	div.vertical-line
	{
		position: absolute;
		border-left: 2px dotted black;
		margin-left: -18px;
		margin-top: -16px;
    }
	table.order-table{
		border-collapse: collapse;
		border: 1px solid black !important;
	  }
	table.order-table td, table.order-table th{
		border: 1px solid black !important;
	}
	.rowspan{
		padding:0px 20px;
	}
	.nested-desc-table tr td, .nested-desc-table tr th{
		border: 0px !important;
	}
	
	.row-border{
		border:1px solid black !important;
	}
	.row-border table tr td, .row-border table tr th{
		border: 0px !important;
	}
</style>

<?php
	$address = '';
	$address.=$biller->address;
	//$address.=($biller->city != '')? ', '.$biller->city : '';
	//$address.=($biller->postal_code != '')? ', '.$biller->postal_code : '';
	//$address.=($biller->state != '')? ', '.$biller->state : '';
	//$address.=($biller->country != '')? ', '.$biller->country : '';
	
	$customer_address = '';
	$customer_address.=$customer->address;
	$customer_address.=($customer->city != '')? ', '.$customer->city : '';
	$customer_address.=($customer->postal_code != '')? ', '.$customer->postal_code : '';
	$customer_address.=($customer->state != '')? ', '.$customer->state : '';
?>
<center>
	<table class="table-responsive" width="1024px" border="0" cellspacing="0" style="margin:auto;">
		<tr>
			<td colspan="2" width="35%" style="virticle-align:middle;">
				<?php if ($logo) { ?>
					<div class="text-center" style="margin-bottom:20px;">
						<img class="img_logo" src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
							 alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
					</div>
				<?php } ?>
			</td> 
			<td colspan="3" width="65%" style="padding-left:50px;">
				<div style="font-family:'Khmer OS Muol'; font-size:25px;"><?= $biller->cf1 != '-' ? $biller->cf1 : $biller->cf2; ?></div>
				<div style="font-family:'Arial'; font-size:20px;font-weight:bold;"><?= $biller->company != '-' ? $biller->company : $biller->name; ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td width="22%">
				<div style="font-family:'Khmer OS'; font-size:14px;font-weight:bold"><?= $this->lang->line("លេខអត្តសញ្ញាណកម្ម អតប​ (VATTIN)"); ?>&nbsp:<?= $biller->vat_no; ?></div>	
			</td>	
		</tr>
		<tr>
			<td colspan="2"> </td>
			<td width="100%">
				<div style="font-size:14px;display:-moz-inline-box;"><?= $this->lang->line("អាស័យដ្ឋាន"); ?>&nbsp:&nbsp<?= $biller->cf4; ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> </td>
			<td width="22%">
			  <div style="width:100%; font-size:14px"><?= $this->lang->line("Address"); ?>&nbsp:&nbsp<?= $address; ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> </td>
			<td  width="22%">
				<div style="font-size:14px;"><?= lang("ទូរស័ព្ទ​ / Tel"); ?>&nbsp:&nbsp(855)<?= $biller->phone;?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2"> </td>
			<td  width="22%">
				<div style="font-size:14px;"><?= lang("សារអេឡិចត្រូនិច(Email)"); ?>&nbsp:&nbsp<?= $biller->email; ?></div>
			</td>
		</tr>
		<tr>
			<td style="height:5px;" colspan="5"> <hr width="100%" size="2px" style="border-top: 3px solid black;"/> </td>
		</tr>
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<div style="font-family:'Khmer OS Muol Light'; font-size:20px;"><?= lang("វិក័យប័ត្រ​អាករ"); ?></div>
				<div style="font-family:'Arial'; font-size:20px;font-weight:bold "><?= lang("TAX INVOICE"); ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<table width="100%">
					<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px;font-weight: bold;"> <?= lang('អតិថិជន / Customer');?> :</td>
						<td width="50%"></td>
						<td width="5px" rowspan="2">
						<div class="vertical-line" style="height: 225px;"></div>	
						</td>
						<td width="10%" rowspan="2" style="font-weight:bold; font-size:14px;"> <?= lang('លេខវិក្ក័យបត្រ <br/> Invoice No');?> </td>
						<td width="15%" rowspan="2" style="padding-left:0px;font-weight:bold; font-size:14px;">: <?= $inv->reference_no; ?> </td>	
					</tr>
					<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('ឈ្មោះ​ក្រុមហ៊ុន ឬ អតិថិជន');?> </td>
						<td width="50%" style=" font-size:14px;">: <?= $customer->cf1 != '-' ? $customer->cf1 : $customer->cf2; ?></td>
					</tr>
					<tr>
						<td width="25%" style="font-family:'Khmer OS Muol Light'; font-size:14px;"> <?= lang('Company name');?> </td>
						<td width="50%" style=" font-size:14px;">: <?= $customer->company != '-' ? $customer->company : $customer->name;  ?> </td>
						<td width="5px" rowspan="2"> </td>
						<td width="10%" rowspan="2" style=" font-size:14px;"> <?= lang('ថ្ងៃចេញវិក្កយបត្រ<br/> Bill Date');?> </td>
						<td width="15%" rowspan="2" style="padding-left:0px; font-size:14px;">: <?= $this->erp->hrld($inv->date); ?> </td>
					</tr>
					<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('អាស័យដ្ឋាន');?> </td>
						<td width="25%" style=" font-size:14px;padding-right:2px;">: <?= $customer->cf4;?> </td>
					</tr>
					<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('Address');?></td>
						<td width="25%">
						<div style="font-size:14px;width:95%">:<?= $customer_address; ?> </div>
						</td>
						<td width="5px" rowspan="2"> </td>
					</tr>
					<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('ទូរស័ព្ទ​លេខ / Telephone No');?> </td>
						<td width="25%" style=" font-size:14px;">:(855) <?= $customer->phone; ?> </td>
					</tr>
					<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('សារអេឡិចត្រូនិច / Email');?> </td>
						<td width="25%">: <?= $customer->email; ?> </td>
						<td width="5px" rowspan="2"> </td>
						<!--
						<td width="10%" rowspan="2" style=" font-size:14px;"> <?= lang('រយះពេល <br/> Peroid');?> </td>
						<td width="15%" rowspan="2" style="padding-left:0px; font-size:14px;">: <?= $this->erp->hrld($inv->date); ?> </td>
						-->
					</tr>
					<tr>
						<td width="25%" style="font-family:'Khmer OS'; font-size:14px;display: -moz-inline-box;font-weight: bold;"> <?= lang('លេខអត្តសញ្ញាណកម្ម អតប  (VATTIN)');?></td>
						<td width="25%">: <?= $customer->vat_no; ?> </td>
					</tr>
				</table><br>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<div class="table-responsive">
					<table class="table print-table order-table">

						<thead>

						<tr>
							<th style="text-align:center;"><?= lang("ល-រ <br/> Nº"); ?></th>
							<th style="text-align:center;"><?= lang("បរិយាយមុខទំនិញ <br/> Description"); ?></th>
							<th style="text-align:center;"><?= lang("ចំនួន <br/> Quantity"); ?></th>
							<th style="text-align:center;"><?= lang("ថ្លៃ​ឯកតា <br/> Unit_Price"); ?></th>
							<?php
								echo '<th style="text-align:center;">' . lang("បញ្ចុះតម្លៃ​ <br/> Discount") . '</th>';
							?>
							<th style="text-align:center;"><?= lang("តំលៃសរុប <br/> Amount"); ?></th>
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
							$product_name_setting = $row->product_name;
						}else{
							$product_name_setting = $row->product_name . " (" . $row->product_code . ")";
						}
						?>
							<tr>
								<td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
								<td style="vertical-align:middle;">
									<?= $product_name_setting ?>
								</td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
								<!-- <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->net_unit_price); ?></td> -->
								<td style="text-align:right; width:100px;vertical-align:middle;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free; ?></td>
								<?php
								//if ($Settings->product_discount && $inv->product_discount != 0) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
								//}
								?>
								<td style="text-align:right; width:120px;vertical-align:middle;"><?= $this->erp->formatMoney($row->subtotal); ?></td>
							</tr>
							<?php
							$r++;
						endforeach;
						?>
						</tbody>
						<tfoot>
						<?php
						$col = 2;
						$col_desc = 4;
						if ($Settings->product_discount && $inv->product_discount != 0 && $Settings->tax1) {
							$tcol = $col;
							$col_desc = $col_desc+1;
						} elseif ($Settings->product_discount && $inv->product_discount != 0) {
							$tcol = $col;
							$col_desc = $col_desc+1;
						} elseif ($Settings->tax1) {
							$tcol = $col;
						} else {
							$tcol = $col;
						}
						?>
						
						<tr>
							<td rowspan="<?=$col_desc;?>" colspan="3" style="border:1px solid black !important">
								<div class="rowspan">
									<p>*Please make payment by transfer to Human Protect's Account</p>
									<table class="nested-desc-table">
										<tr>
											<td>Currency</td>
											<td>&nbsp;: USD Dollars</td>
										</tr>
										<tr>
											<td>Bank</td>
											<td>&nbsp;: CAMBODIAN PUBLIC BANK PLC</td>
										</tr>
										<tr>
											<td>Swift Code</td>
											<td>&nbsp;: CPBLKHPP</td>
										</tr>
										<tr>
											<td>Account Name</td>
											<td>&nbsp;: HUMAN PROTECT SECURITY Co,LTD</td>
										</tr>
										<tr>
											<td>Account Number</td>
											<td>&nbsp;: 130-02-30-00107-3</td>
										</tr>
									</table>
									
									
									<!--
									<p></p>
									<p>Bank : CAMBODIAN PUBLIC BANK PLC</p>
									<p>Swift Code : CPBLKHPP</p>
									<p>Account Name : HUMAN PROTECT SECURITY Co,LTD</p>
									<p>Account Number : 130-02-30-00107-3</p>
									-->
								</div>
							</td>
						</tr>
						
						<?php if ($inv->grand_total != $inv->total) { ?>
							<tr>
								<td colspan="<?= $tcol; ?>"
									style="text-align:right; padding-right:10px;"><?= lang("សរុប <br/> Total"); ?>
								</td>
								<?php
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
						
						<?php if ($inv->shipping != 0) {
							echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("ការ​ដឹក​ជញ្ជូន​ <br/> Shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
						}
						?>
						<tr>
							<td colspan="<?= $col; ?>"
								style="text-align:right; font-weight:bold;"><?= lang("សរុប <br/> Subtotal"); ?>
							</td>
							<td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
						</tr>
						<!--
						<tr>
							<td colspan="<?= $col; ?>"
								style="text-align:right; font-weight:bold;"><?= lang("បានបង់​ <br/> Paid"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->paid); ?></td>
						</tr>
						-->
						
						<?php //if ($Settings->tax2 && $inv->order_tax != 0) {
							echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px; font-weight:bold;">' . lang("អាករលើតម្លៃបន្ថែម ១០% <br/> VAT(".number_format(10)."%)") . '</td><td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;">' . $this->erp->formatMoney(($inv->grand_total)*0.1) . '</td></tr>';
						//}
						?>
						
						<tr>
							<td colspan="<?= $col; ?>"
								style="text-align:right; font-weight:bold;"><?= lang("សរុបរួម <br/> Total Amount"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney(($inv->grand_total) + ($inv->grand_total)*0.1); ?></td>
						</tr>

						</tfoot>
					</table>
					
					<table class="table print-table order-table">
						<thead>
						<tr>
							<th style="text-align:center;width:40%;"><?= lang("រៀបចំដោយ <br/> Prepared By"); ?></th>
							<th style="text-align:center;width:40%;"><?= lang("អតិថិជន <br/> Customer"); ?></th>
							<th style="text-align:center;width:10%;"><?= lang("ប្រមូលប្រាក់ដោយ <br/> Collected By"); ?></th>
							<th style="text-align:center;width:10%;"><?= lang("ទទួលប្រាក់ដោយ <br/> Received By"); ?></th>
						</tr>

						</thead>
						<tbody>
							<tr>
								<td style="height:200px;"></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>	
					</table>
					<br/>
						<table class="table-note">
							<tr style="vertical-align:top;">
								<td style="padding-left:5px;padding-right:5px;">ចំណាំ</td>
								<td>: សូមអញ្ញើញមកបង់ប្រាក់អោយបានមុនថ្ងៃទី១០។ ក្នុងករណីខកខានមិនបានបង់ប្រាក់ទាន់ពេលវេលាទេ ក្រុមហ៊ុន <br/>&nbsp;Huamn Protect Security Co., LTD នឹងមិនទទួលខុសត្រូវក្នុងកំឡុងពេលនោះឡើយ។</td>
							</tr>
							<tr style="vertical-align:top;">
								<td style="padding-left:5px;padding-right:5px;">Note</td>
								<td>: Please make the payment before the 10th. If late or fails to pay, we will not responsible on that period.</td>
							</tr>
						</table>
				</div>
			</td>
		</tr>
		<!--
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
		-->
	</table>
	
</center>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>