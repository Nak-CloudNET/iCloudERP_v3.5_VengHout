<?php //$this->erp->print_arrays($discount['discount']) ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Invoice&nbsp;<?= $invs->reference_no ?></title>
	<link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
	<link href="<?php echo $assets ?>styles/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $assets ?>styles/custome.css" rel="stylesheet">
</head>
<style>
	body {
		font-size: 14px !important;
	}
		
	.container {
		width: 29.7cm;
		margin: 20px auto;
		/*padding: 10px;*/
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
	}
	@media print {
		.customer_label {
			padding-left: 0 !important;
		}
		
		.invoice_label {
			padding-left: 0 !important;
		}
		#footer  hr p {
			font-size: 1px !important;
			position:absolute !important;
   			bottom:0 !important;
   			/*margin-top: -30px !important;*/
		}
		.row table tr td {
			font-size: 10px !important;
		}
		/*.row table tr th {
			font-size: 8px !important;
		}*/
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
			background-color: #444 !important;
			color: #FFF !important;
		}
		footer {page-break-after: always;}
		.row .col-xs-7 table tr td, .col-sm-5 table tr td{
			font-size: 10px !important;
		}
		#note{
				max-width: 95% !important;
				margin: 0 auto !important;
				border-radius: 5px 5px 5px 5px !important;
				margin-left: 26px !important;
			}
	}
	.thead th {
		text-align: center !important;
	}
	
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border: 1px solid #000 !important;
	}
	
	.company_addr h3:first-child {
		font-family: Khmer OS Muol !important;
		//padding-left: 12% !important;
	}
	
	.company_addr h3:nth-child(2) {
		margin-top:-2px !important;
		//padding-left: 130px !important;
		font-size: 26px !important;
		font-weight: bold;
	}
	
	.company_addr h3:last-child {
		margin-top:-2px !important;
		//padding-left: 100px !important;
	}
	
	.company_addr p {
		font-size: 12px !important;
		margin-top:-10px !important;
		padding-left: 20px !important;
	}
	
	.inv h4:first-child {
		font-family: Khmer OS Muol !important;
		font-size: 14px !important;
	}
	
	.inv h4:last-child {
		margin-top:-5px !important;
		font-size: 14px !important;
	}

	button {
		border-radius: 0 !important;
	}
	
</style>
<body>
	<br>
	<div class="container" style="width: 821px;margin: 0 auto;">
		<div class="col-xs-12" style="width: 794px;">
			<div class="row" style="margin-top: 20px !important;">
		
				<div class="col-sm-3 col-xs-3">
					<?php if(!empty($biller->logo)) { ?>
						<img src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" style="width: 165px; margin-left: 25px;" />
					<?php } ?>
				</div>
			
				<div class="col-sm-6 col-xs-6 company_addr" style="margin-top: -15px !important">
					<center>
						<?php if(!empty($biller->cf1)) { ?>
							<h3><?= $biller->cf1 ?></h3>
						<?php }else { ?>
							<h3>CloudNET Cambodia</h3>
						<?php } ?>
					
						<?php if(!empty($biller->vat_no)) { ?>
							<p style="font-size: 11px;">លេខអត្តសញ្ញាណកម្ម អតប (VAT No):&nbsp;<?= $biller->vat_no; ?></p>
						<?php } ?>
						
						<?php if(!empty($biller->address)) { ?>
							<p style="margin-top:-10px !important;font-size: 11px;">អាសយដ្ឋាន ៖ &nbsp;<?= $biller->address; ?></p>
						<?php } ?>
						
						<?php if(!empty($biller->phone)) { ?>
							<p style="margin-top:-10px !important;font-size: 11px;">ទូរស័ព្ទលេខ (Tel):&nbsp;<?= $biller->phone; ?></p>
						<?php } ?>
						
						<?php if(!empty($biller->email)) { ?>
							<p style="margin-top:-10px !important;font-size: 11px;">សារអេឡិចត្រូនិច (E-mail):&nbsp;<?= $biller->email; ?></p>
						<?php } ?>
					</center>
				</div>
				<div class="col-sm-3 col-xs-3">
					<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                		<i class="fa fa-print"></i> <?= lang('print'); ?>
            		</button>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12 inv" style="margin-top: -10px !important">
					<center>
						<h3 style="margin-top:20px !important;"><b>PURCHASE</b></h3>
					</center>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-7 col-xs-7">
					<table style="font-size: 11px;">
						<?php if(!empty($customer->company)) { ?>
						<tr>
							<td style="width: 5%;">ឈ្មោះក្រុមហ៊ុន </br> Company Name</td>
							<td style="width: 5%;">:</td>
							<td style="width: 30%;"><?= $customer->company ?></td>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->name_kh || $customer->name)) { ?>
						<tr>
							<td>អតិថិជន </br> Customer Name</td>
							<td>:</td>
							<?php if(!empty($customer->name_kh)) { ?>
							<td><?= $customer->name_kh ?></td>
							<?php }else { ?>
							<td><?= $customer->name ?></td>
							<?php } ?>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->address_kh || $customer->address)) { ?>
						<tr>
							<td>អាសយដ្ឋាន </br> Address</td>
							<td>:</td>
							<?php if(!empty($customer->address_kh)) { ?>
							<td><?= $customer->address_kh?></td>
							<?php }else { ?>
							<td><?= $customer->address ?></td>
							<?php } ?>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->address_kh || $customer->address)) { ?>
						<tr>
							<td>ទូរស័ព្ទលេខ (Tel)</td>
							<td>:</td>
							<td><?= $customer->phone ?></td>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->vat_no)) { ?>
						<tr>
							<td>លេខអត្តសញ្ញាណកម្ម អតប </br> VAT No.</td>
							<td>:</td>
							<td><?= $customer->vat_no ?></td>
						</tr>
						<?php } ?>
					</table>
				</div>
				<div class="row" style="margin:10px;">
                <div class="col-xs-6">
                    <table>
                        <tr>
                            <td><?=$this->lang->line("from");?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?="<span style='font-weight:bold; font-size:12px;'>". $inv->company  ."</span>";?></td>
                        </tr>
                        <tr>
                            <td><?=lang("address") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($warehouse->address ? $warehouse->address." " : ''). ($warehouse->city ? $warehouse->city . " " . $warehouse->postal_code . " " . $warehouse->state . " " : '') . ($warehouse->country ? $warehouse->country : ''); ?></td>
                        </tr>
                        <?php //if ($inv->username != ""){?>
                        <tr>
                            <td>Attn</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$inv->username;?></td>
                        </tr>
                        <?php //}?>
                        <?php if ($warehouse->phone !='' || $warehouse->email !=''): ?>
                        <tr>
                            <td><?=lang("contact") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($warehouse->phone ? $warehouse->phone.'/'.$warehouse->email : $warehouse->email) ?></td>
                        </tr>
                        <?php endif ?>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table>
                        <tr>
                            <td><?=$this->lang->line("to");?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?="<span style='font-weight:bold; font-size:13px;'>". ($supplier->company ? $supplier->company : $supplier->name) ."</span>";?></td>
                        </tr>
                        <tr>
                            <td><?=lang("address") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($supplier->address ? $supplier->address : ""). ($supplier->city ? "<br>" . $supplier->city : "") . ($supplier->postal_code ? " " .$supplier->postal_code : "") . ($supplier->state ? " " .$supplier->state : "") .  ($supplier->country ? "<br>" .$supplier->country : ""); ?></td>
                        </tr>
                        <?php //if ($supplier->company == ""){?>
                        <tr>
                            <td>Attn</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$supplier->name;?></td>
                        </tr>
                        <?php //}?>
                        <?php if ($supplier->phone !='' || $supplier->email !=''): ?>
                        <tr>
                            <td><?=lang("contact") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($supplier->phone ? $supplier->phone.'/'.$supplier->email : $supplier->email) ?></td>
                        </tr>
                        <?php endif ?>
						<tr>
							<td>Date</td>
							<td>:</td>
							<td><?= $this->erp->hrsd($inv->date); ?></td>
						</tr>
                    </table>
                </div>
            </div>
			</div>
			
		
			<div class="row">
				<div class="col-sm-12 col-xs-12">
					<table class="table table-bordered" style="margin-top: 10px;">
						<tbody style="font-size: 11px;">
							<tr class="thead" style="background-color: #444 !important; color: #FFF !important;">
								<th>ល.រ<br /><?= strtoupper(lang('no')) ?></th>
								<th>រូបភាព<br /><?= strtoupper(lang('Image')) ?></th>
								<th>បរិយាយមុខទំនិញ<br /><?= strtoupper(lang('description')) ?></th>
								<th>ខ្នាត<br /><?= strtoupper(lang('unit')) ?></th>
								<th>ចំនួន<br /><?= strtoupper(lang('qty')) ?></th>
								<th>តម្លៃ<br /><?= strtoupper(lang('unit_price')) ?></th>
								
								<?php if ($Settings->product_discount) { ?>
									<th>បញ្ចុះតម្លៃ<br /><?= strtoupper(lang('discount')) ?></th>
								<?php } ?>
								<?php if ($Settings->tax1) { ?>
									<th>ពន្ធទំនិញ<br /><?= strtoupper(lang('tax')) ?></th>
								<?php } ?>
								<th>តម្លៃសរុបតាមមុខទំនិញ<br /><?= strtoupper(lang('subtotal')) ?></th>
							</tr>
							<?php 
								
								$no = 1;
								$tax_summary = array();
								foreach ($rows as $row) {
									
									$free = lang('free');
									$product_unit = '';
									$total = 0;
									
									if($row->variant){
										$product_unit = $row->variant;
									}else{
										$product_unit = $row->uname;
									}
									$product_name_setting;
									if($setting->show_code == 0) {
										$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
									}else {
										if($setting->separate_code == 0) {
											$product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
										}else {
											$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
										}
									}
									if($row->subtotal == 0){
										$subtotal = lang('free');
									} else {
										$subtotal = $this->erp->formatMoney($row->subtotal);
									}
							?>
								<tr>
									<td style="vertical-align: middle; text-align: center"><?php echo $no ?></td>
									<td style="vertical-align: middle;">
										<img class="img-circle" style="width:30px; height:30px;" src ="<?= base_url() ."assets/uploads/thumbs/".$row->image; ?>">
									</td>
									<td style="vertical-align: middle;text-align: center">
										<?=$row->product_name . " (" . $row->product_code . ")" ;?>
									</td>
									<td style="vertical-align: middle; text-align: center">
										<?php
												if ($row->variant != '') {
													echo $row->variant;
												} else {
													echo $row->unit;
												}
										?>
									</td>
									<td style="vertical-align: middle; text-align: center">
										<?=$this->erp->formatQuantity($row->quantity);?>
									</td>
									<td style="vertical-align: middle; text-align: right">
										$&nbsp;<?= $this->erp->formatMoney($row->unit_cost); ?>
									</td>
									<?php
									if ($Settings->product_discount) {
										$percentage = '%';
										$discount = $row->discount;
										$dpos = strpos($discount, $percentage);
										echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' .($dpos == true ? '<small>('.$discount.')</small>' : '').' '. $this->erp->formatMoney($row->item_discount) . '</td>';
									}
									if ($Settings->tax1) {
										echo '<td style="width: 110px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_name ? '<small>('.$row->tax_name.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
									}
									?>
									<td style="vertical-align: middle; text-align: right">$<?= $subtotal; ?>
									</td>
								</tr>

							<?php
							$no++;
							}
							?>
							<?php
								if($row<16){
									$k=1 - $row;
									for($j=1;$j<=$k;$j++){
										if($discount != 0) {
											echo  '<tr>
													<td height="34px"></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>';
										}else {
											echo  '<tr>
													<td height="34px"></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>';
										}
										
									}
								}
							?>
							<?php
								$row = 3;
								$col =5;
								if ($discount != 0) {
									$col = 3;
								}
								if ($inv->grand_total != $inv->total) {
									$row++;
								}
								if ($inv->order_discount != 0) {
									$row++;
									$col =4;
								}
								if ($inv->shipping != 0) {
									$row++;
									$col =4;
								}
								if ($inv->order_tax != 0 || $Settings->tax1==1) {
									$row++;
									$col =5;
								}else{
									$row++;
									$col =4;
								}
								if($inv->paid != 0 && $inv->deposit != 0) {
									$row += 3;
								}elseif ($inv->paid != 0 && $inv->deposit == 0) {
									$row += 2;
								}elseif ($inv->paid == 0 && $inv->deposit != 0) {
									$row += 2;								}
							?>
								<?php //if ($inv->grand_total == $inv->total) { ?>
								<td rowspan="<?= $row; ?>" colspan="3" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important;">
									<?php if (!empty($inv->invoice_footer)) { ?>
										<p><strong><u>Note:</u></strong></p>
										<p><?= $inv->invoice_footer ?></p>
									<?php } ?>
								</td>
								<?php //} ?>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">សរុបរួម / <?= strtoupper(lang('total_amount')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?= $this->erp->formatMoney($inv->grand_total); ?></td>
							</tr>
							<?php if($inv->paid != 0 || $inv->deposit != 0){ ?>
							<?php if($inv->deposit != 0) { ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">បានកក់ / <?= strtoupper(lang('deposit')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?php echo $this->erp->formatMoney($inv->deposit); ?></td>
							</tr>
							<?php } ?>
							<?php if($inv->paid != 0) { ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">បានបង់ / <?= strtoupper(lang('paid')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?php echo $this->erp->formatMoney($inv->paid-$inv->deposit); ?></td>
							</tr>
							<?php } ?>
							<tr>
								<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;">នៅខ្វះ / <?= strtoupper(lang('balance')) ?>
									(<?= $default_currency->code; ?>)
								</td>
								<td align="right">$<?= $this->erp->formatMoney($inv->grand_total - (($inv->paid-$inv->deposit) + $inv->deposit)); ?></td>
							</tr>
						<?php } ?>
							
						</tbody>
						
					</table>
				</div>
			</div>
			<?php if($invs->note){ ?>
			<div style="border-radius: 5px 5px 5px 5px;border:1px solid black;font-size: 10px !important;margin-top: 10px;height: auto;" id="note" class="col-md-12 col-xs-12">
				<p style="margin-left: 10px;margin-top:10px;"><?php echo strip_tags($invs->note); ?></p>
			</div>
			<?php } ?>
			<br><br><br>
			<div id="footer" class="row" style="margin-top: 80px !important;">
				<div class="col-sm-4 col-xs-4">
					<hr style="margin:0; border:1px solid #000;">
					<center>
						<p style="font-size: 10px;">ហត្ថលេខា និងឈ្មោះអ្នករៀបចំ</p>
						<p style="margin-top:-10px;">Prepared's Signature & Name</p>
					</center>
				</div>
				<div class="col-sm-4 col-xs-4">
					<hr style="margin:0; border:1px solid #000;">
					<center>
						<p style="font-size: 10px;">ហត្ថលេខា និងឈ្មោះអ្នកលក់</p>
						<p style="margin-top:-10px;">Seller's Signature & Name</p>
					</center>
				</div>
				<div class="col-sm-4 col-xs-4">
					<hr style="margin:0; border:1px solid #000;">
					<center>
						<p style="font-size: 10px;">ហត្ថលេខា និងឈ្មោះអ្នកទិញ</p>
						<p style="margin-top:-10px;">Customer's Signature & Name</p>
					</center>
				</div>
			</div>
			<footer></footer>
			<br>
		 </div>
	</div>
	<br>
	<div style="width: 821px;margin: 0 auto;">
		<a class="btn btn-warning no-print" href="<?= site_url('purchases'); ?>">
        	<i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
     	</a>
	</div>
	<br>
</body>
</html>