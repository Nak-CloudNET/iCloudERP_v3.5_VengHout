<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<style>
	.btn {
		padding: 0;
		border-radius: 0;
		color: #000;
		width: 22px;
		height: 25px;
		background-color: transparent;
		border: 1px solid #000;
	}

	@media print {
		.container {
			width: 98% !important;
			margin: 10px auto !important;
			padding: 0;
		}
		#footer {
			position: absolute !important;
			bottom: 10px !important;
			width: 100%;
		}
		#footer .col-sm-6 {			width: 50% !important;
		}
	}
</style>
<?php
	$address = '';
	$address.=$biller->address;
	$address.=($biller->city != '')? ', '.$biller->city : '';
	$address.=($biller->postal_code != '')? ', '.$biller->postal_code : '';
	$address.=($biller->state != '')? ', '.$biller->state : '';
	$address.=($biller->country != '')? ', '.$biller->country : '';
?>
<div class="container">
<center>
<br>
	<table class="table-responsive" width="1024px" border="0" cellspacing="0" style="margin:auto;">
		<tr>
			<td rowspan="6" style="vertical-align: top">
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
			<td style="width: 50px !important"></td>
			<td>
				<table>
					<tr>
						<td colspan="2">
							<h2 style="font-family: Khmer OS Muol Light; text-align: center;"><?= $biller->company_kh ?></h2>
							<h4 style="text-align: center; font-weight: bold"><?= $biller->company ?></h4>
						</td>
					</tr>
					<tr>
						<td width="30%">
							<div style="font-family:'Khmer OS'; font-size:12px;"><?= $this->lang->line("លេខអត្តសញ្ញាណកម្ម អតប​ (VATTIN)"); ?></div>
						</td>
						<td>
							<div style="font-family:'Arial'; font-size:12px;">
								<?php for($i=strlen($biller->vat_no);$i>=1 ; $i--) { ?>
									<?php 
										$sign ="";
										if ($i == 10) {
													$sign = "-";
										}
									?>
								    	<button type="button" class="btn"><?= $biller->vat_no[strlen($biller->vat_no)-$i]?></button> <?= $sign ?>
								<?php } ?>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div style="font-family:'Khmer OS'; font-size:12px;"><?= $this->lang->line("អាស័យដ្ឋាន"); ?></div>
						</td>
						<td>
							<div style="font-family:'Khmer OS'; font-size:12px;">: <?= $biller->cf4; ?></div>
						</td>
					</tr>
					<tr>
						<td>
							<div style="font-family:'Arial'; font-size:12px;"><?= $this->lang->line("Address"); ?></div>
						</td>
						<td>
							<div style="font-family:'Arial'; font-size:12px;">: <?= $address; ?></div>
						</td>
					</tr>
					<tr>
						<td>
							<div style="font-family:'Arial'; font-size:12px;"><?= lang("tel"); ?></div>
						</td>
						<td style="font-family:'Arial'; font-size:12px;">: <?= $biller->phone;?></td>
					</tr>
					<tr>
						<td >
							<div style="font-family:'Arial'; font-size:12px;"><?= lang("email"); ?></div>
						</td>
						<td style="font-family:'Arial'; font-size:12px;">: <?= $customer->email; ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<table class="table-responsive" width="1024px" border="0" cellspacing="0" style="margin:auto;">
		<tr>
			<td style="height:5px;" colspan="5"> <hr width="100%" size="2px" /> </td>
		</tr>
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<div style="font-family:'Khmer OS Muol Light'; font-size:20px;"><?= lang("វិក្កយបត្រអាករ"); ?></div>
				<div style="font-family:'Arial'; font-size:18px; font-weight: bold"><?= lang("tax_invoice"); ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<div class="row">
					<div class="col-sm-6 col-xs-6">
						<div class="row">
							<div class="col-sm-7 col-xs-7" style="font-family:'Khmer OS Muol Light'; font-size:14px; text-align: left"><?= lang('អតិថិជន / Customer');?></div>
							<div class="col-sm-5 col-xs-5 text-left">
								<?= $customer->name ? $customer->name : $customer->company; ?>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-7 col-xs-7" style="font-size:14px; text-align: left"><?= lang('ឈ្មោះ​ក្រុមហ៊ុន ឬ អតិថិជន <br/> Company name / Customer');?></div>
							<div class="col-sm-5 col-xs-5 text-left" style="height: 41px; padding-top: 12px">
								<?= $customer->company ? $customer->company : $customer->name; ?>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-7 col-xs-7" style="font-size:14px; text-align: left"><?= lang('ទូរស័ព្ទ​លេខ <br> Telephone No');?></div>
							<div class="col-sm-5 col-xs-5 text-left" style="height: 41px; padding-top: 12px">
								<?= $customer->phone; ?>
							</div>
						</div>
					</div>
					<div class="col-sm-6 col-xs-6">
						<div class="row">
							<div class="col-sm-6 col-xs-6 text-left" style="padding-left: 100px">
								<?= lang('លេខវិក្ក័យបត្រ <br/> Invoice No');?>
							</div>
							<div class="col-sm-6 col-xs-6 text-left" style="height: 41px; padding-top: 12px">
								<?= $inv->reference_no; ?>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6 col-xs-6 text-left" style="padding-left: 100px">
								<?= lang('កាលបរិច្ឆេទ <br/> Date');?>
							</div>
							<div class="col-sm-6 col-xs-6 text-left" style="height: 41px; padding-top: 12px">
								<?php
									$date = str_replace('/', '', $this->erp->hrsd($inv->date));
								?>
								<?php for($i=strlen($date);$i>=1 ; $i--) { ?>
									<?php
										$signd ="";
										if ($i == 7 || $i == 5) {
											$signd = "&nbsp;&nbsp;";
										}
									?>
								    <button type="button" class="btn"><?= $date[strlen($date)-$i]?></button><?= $signd ?>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-4 col-xs-4 text-left">
						<?= lang('លេខអត្តសញ្ញាណកម្ម អតប  (VATTIN)');?>
					</div>
					<div class="col-sm-8 col-xs-8 text-left" style="margin-left: -45px">
						<?php for($i=strlen($customer->vat_no);$i>=1 ; $i--) { ?>
						<?php 
							$sign ="";
							if ($i == 10) {
										$sign = "-";
							}
						?>
					    	<button type="button" class="btn"><?= $customer->vat_no[strlen($customer->vat_no)-$i]?></button> <?= $sign ?>
						<?php } ?>
					</div>
				</div>

			</td>
		</tr>
		<tr>
			<td colspan="5"> 
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped print-table order-table" style="margin-top: 10px;">
						<thead>
						<tr>
						
							<th style="text-align:center;"><?= lang("ល-រ <br/> Nº"); ?></th>
							<?php
								$isTax=false;
								$isDiscount=false;
								if ($Settings->tax1 && $rows['item_tax'] != 0 && $rows['tax_code'] ) {
									$isTax= true;
								}
								if ($Settings->product_discount && $rows['discount'] != 0) {
									$isDiscount = true;
								}
							?>
							<th style="text-align:center;" colspan="<?=($isTax && $isDiscount)? 1:4 ?>" ><?= lang("បរិយាយមុខទំនិញ <br/> Description"); ?></th>
							<th style="text-align:center;"><?= lang("ខ្នាត <br/> Unit"); ?></th>
							<th style="text-align:center;"><?= lang("បរិមាណ <br/> Quantity"); ?></th>
							<th style="text-align:center;"><?= lang("ថ្លៃ​ឯកតា <br/> Unit_Price"); ?></th>
							<?php
							if ($isTax) {
								echo '<th style="text-align:center;">' . lang("អាករឯកតា <br/> Tax") . '</th>';
								
							}
							 if ($isDiscount) {
								echo '<th style="text-align:center;">'.lang("បញ្ចុះតម្លៃឯកតា​ <br/> Discount").'</th>';
							 }
							?>
							<th style="text-align:center;" colspan="<?=($isTax && $isDiscount)? 2:1 ?>"><?= lang("តម្លៃ <br/> Amount"); ?></th>
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
							$product_name_setting = $row->product_name;
						}
						?>
							<tr>
								<td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
								<td style="vertical-align:middle;" colspan="<?=($isTax && $isDiscount)? 1:4 ?>">
									<?= $product_name_setting ?>
									<?= $row->details ? '<br>' . $row->details : ''; ?>
									<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
								</td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?php echo $product_unit ?></td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
								<!-- <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->net_unit_price); ?></td> -->
								<td style="text-align:right; vertical-align:middle; width:100px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free; ?></td>
								<?php
								if ($Settings->tax1 && $rows['item_tax'] != 0 && $rows['tax_code']) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
								}
								if ($Settings->product_discount && $rows['discount'] != 0) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">'.($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount).'</td>';
								}
								?>
								<td style="text-align:right; vertical-align:middle; width:120px;" colspan="<?=($isTax && $isDiscount)? 2:1 ?>"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; ?></td>
							</tr>
							<?php
							$r++;
						endforeach;
						?>
						<?php
							if($r<11){
								$k=11 - $r;
								for($j=1;$j<=$k;$j++){
									if( $isTax && $isDiscount ){
										echo  '<tr>
											<td height="34px" class="text-center">'.$r.'</td>
											<td style="width:34px;"></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td colspan="2"></td>
											
										</tr>';
										
								    }else{
										echo  '<tr>
											<td height="34px" class="text-center">'.$r.'</td>
											<td style="width:34px;" colspan="4"></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											
										</tr>';
									}
									$r++;
								}
							}
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
						if($inv->paid != 0 && $inv->deposit != 0) {
							$rol += 3;
						}elseif ($inv->paid != 0 && $inv->deposit == 0) {
							$rol += 2;
						}elseif ($inv->paid == 0 && $inv->deposit != 0) {
							$rol += 2;
						}
						$rol++;
						$tcol++;
						?>
						<tr>
							<td rowspan="<?= $rol; ?>" colspan="<?= $col; ?>"></td>
							<td style="text-align:right; padding-right:10px;" colspan="<?= $tcol; ?>"><?= lang("សរុប <br/> Total"); ?></td>
							<?php
							if ($Settings->tax1 && $rows['item_tax'] != 0 && $rows['tax_code']) {
								echo '<td style="text-align:right; padding-top:20px;">' . $this->erp->formatMoney($inv->product_tax) . '</td>';
							}
							if ($Settings->product_discount && $rows['discount'] != 0) {
								echo '<td style="text-align:right; vertical-align:middle;">' . $this->erp->formatMoney($inv->product_discount) . '</td>';
							}
							?>
							<td style="text-align:right; padding-right:10px; vertical-align:middle;"><?= $this->erp->formatMoney($inv->total + $inv->product_tax); ?></td>
						</tr>
						<?php if ($inv->order_discount != 0) {
							echo '<tr><td colspan="' . $tcol . '" style="text-align:right; padding-right:10px; font-weight:bold;">' . lang("បញ្ចុះតម្លៃ​រួម  <br/> Order_Discount") . '</td><td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
						}
						?>
						<?php if ($inv->shipping != 0) {
							echo '<tr><td colspan="' . $tcol . '" style="text-align:right; vertical-align:middle !important; padding-right:10px;;">' . lang("ការ​ដឹក​ជញ្ជូន​ <br/> Shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; vertical-align:middle; padding-right:10px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
						}

						?>
						<?php if ($Settings->tax2 && $inv->order_tax != 0) {
							$vat = str_replace('@', '', (strstr($inv->vat, '@', false)));
							if ($vat == '10%') {
								$vat_kh = '១០%';
							}
							echo '<tr><td colspan="' . $tcol . '" style="text-align:right; vertical-align:middle !important; padding-right:10px; font-weight: bold;">' . lang("អាករលើតម្លែបន្ថែម <span>". $vat_kh ."</span><br/><span style='font-size:12px'> VAT (". $vat .")</span>") . '</td><td style="text-align:right; vertical-align:middle; padding-right:10px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
						}
						?>
						<?php if ($inv->order_discount != 0 || $inv->shipping != 0 || ($Settings->tax2 && $inv->order_tax != 0)) { ?>
						<tr>
							<td colspan="<?= $tcol ?>"
								style="text-align:right; font-weight:bold;"><?= lang("សរុបរួម <br/> Grand_Total"); ?>
							</td>
							<td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->total + $inv->product_tax + $inv->order_tax);?></td>
						</tr>
						<?php } ?>
						<?php if($inv->paid != 0 || $inv->deposit != 0) {?>
						<?php if($inv->deposit != 0) {?>
						<tr>
							<td colspan="<?= $tcol ?>"
								style="text-align:right; font-weight:bold;"><?= lang("បានកក់ <br/> Deposit"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->deposit); ?></td>
						</tr>
						<?php } ?>
						<?php if($inv->paid != 0) {?>
						<tr>
							<td colspan="<?= $tcol; ?>"
								style="text-align:right; font-weight:bold;" ><?= lang("បានបង់​ <br/> Paid"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;"><?= $this->erp->formatMoney($inv->paid-$inv->deposit); ?></td>
						</tr>
						<?php } ?>
						<tr>
							<td colspan="<?= $tcol; ?>"
								style="text-align:right; font-weight:bold;"><?= lang("សមតុល្យ​ទឹកប្រាក់ <br/> Balance"); ?>
							</td>
							<td style="text-align:right; font-weight:bold; padding-top:20px;">
								<?= $this->erp->formatMoney($inv->grand_total - (($inv->paid - $inv->deposit) + $inv->deposit)); ?>
							</td>
						</tr>
						<?php }?>
						</tfoot>
					</table>
				</div>
			</td>
		</tr>
	</table>
</center>

	<div id="footer" class="row">
		<div class="col-sm-6 col-xs-6">
			<center>
				<hr style="border:dotted 1px; width:50%; vertical-align:bottom !important; " />
				<p style="margin-top: -20px !important"><b style="font-size:14px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​ទិញ​  <br/> Customer`s Signature & Name'); ?></b></p>
			</center>
		</div>
		<div class="col-sm-6 col-xs-6" style="float: right;">
			<center>
				<hr style="border:dotted 1px; width:50%; vertical-align:bottom !important; " />
				<p style="margin-top: -20px !important"><b style="font-size:14px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​លក់​  <br/> Seller`s Signature & Name'); ?></b></p>
			</center>
		</div>
		<div colspan="5"  style="font-size:12px; font-family:'Khmer OS'; padding-top:20px; margin-left:70px;">
			<b><u>សម្គាល់​</u> : </b> ច្បាប់​​ដើម​សម្រាប់​អ្នក​ទិញ​ ច្បាប់​ចម្លង​សម្រាប់​អ្ន​ក​លក់​។ <br/>
			<b><u>Note</u> : </b> Original invoice for customer copy invoice for seller. 
		</div>
	</div>
</div>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>