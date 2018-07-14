<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<style>
        @media print{
			  #box{
				  width:100% !important; 
				  margin:0px auto !important;
			  }
			  #pd{
				  padding-top:5px !important;
			  }
			  tr td div .table tr th{
			  	font-size: 11px !important;
			  }
			  table tbody tr td {
			  	font-size: 10px !important;
			  }
		 }
		}
        #head-box tr td {
				  font-size:12px !important;
		}
		thead th {
			font-size:12px !important;
		}
		tbody td{
			font-size:12px !important;
		}
</style>


 <div id ="box" style="max-width:100%;">
	<table class="table-responsive" width="48%" cellspacing="0" style="margin: 0 auto;">
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<div><?php if($biller->logo){?>
						<img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="<?= $biller->company; ?>" style="width: 87%;">
						<p style="font-weight: bold; text-align: center;"><?= $biller->phone;?></p>
	            	<?php }?></div>
				<div style="font-family:'Khmer OS Muol Light'; font-size:20px;"><?= lang("វិក័យប័ត្រ"); ?></div>
				<div style="font-family:'Arial'; font-size:20px;"><?= lang("invoice"); ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<table id="head-box" width="100%">
					<!-- <?php if(!empty($customer->company)) { ?>
					<tr>
						<td width="22%">Company Name :</td>
						<td width="38%"><?= $customer->company ?></td>
						<td width="20%" style="text-align: right;">Noº</td>
						<td width="20%">: <?= $invs->reference_no ?></td>
					</tr>
					<?php } ?> -->
					<?php if(!empty($customer->name_kh || $customer->name)) { ?>
					<tr>
						<td width="22%">Customer Name</td>
						<?php if(!empty($customer->name_kh)) { ?>
						<td width="30%">: <?= $customer->name_kh ?></td>
						<?php }else { ?>
						<td width="38%">: <?= $customer->name ?></td>
						<?php } ?>
						<td width="10%" style="text-align: left;">Noº</td>
						<td width="30%">: <?= $invs->reference_no ?></td>
					</tr>
					<?php } ?>
					<?php if(!empty($customer->address_kh || $customer->address)) { ?>
					<tr>
						<td width="22%">Address</td>
						<?php if(!empty($customer->address_kh)) { ?>
						<td width="30%">: <?= $customer->address_kh?></td>
						<?php }else { ?>
						<td width="38%">: <?= $customer->address ?></td>
						<?php } ?>
						<td width="10%" style="text-align: left;">Date</td>
						<td width="30%">: <?= $invs->date; ?></td>
					</tr>
					<?php } ?>
					<?php if(!empty($customer->address_kh || $customer->address)) { ?>
					<tr>
						<td width="22%">Phone</td>
						<td width="30%">: <?= $customer->phone ?></td>
						<td width="10%" style="text-align: left;">Saller</td>
						<td width="30%">: <?= $invs->saleman;?></td>
					</tr>
					<?php } ?>
					<?php if(!empty($customer->vat_no)) { ?>
					<tr>
						<td width="22%">VAT No</td>
						<td width="30%">: <?= $customer->vat_no ?></td>
					</tr>
					<?php } ?>
					<tr>
						<td width="22%">Term Payment</td>
						<td width="30%">: <?= $payment_term->description ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<div>
					<table class="table table-bordered">
						<thead> 
						<tr>
							<th style="text-align: center;"><?= strtoupper(lang('no')) ?></th>
							<th style="text-align: center;"><?= strtoupper(lang('description')) ?></th>
							<!-- <th style="text-align: center;"><?= strtoupper(lang('size')) ?></th> -->
							<th style="text-align: center;"><?= strtoupper(lang('qty')) ?></th>
							<th style="text-align: center;"><?= strtoupper(lang('unit_price')) ?></th>
							<?php if ($Settings->product_discount) { ?>
								<?php if($con->item_discount != 0) {?>
								<th style="text-align: center;"><?= strtoupper(lang('discount')) ?></th>

							<?php } }?>
							<?php if ($Settings->tax1) { ?>
								<?php if($con->item_tax != 0) {?>
								<th style="text-align: center;"><?= strtoupper(lang('tax')) ?></th>
							<?php } } ?>
							<th style="text-align: center;"><?= strtoupper(lang('amount')) ?></th>
						</tr>

						</thead>

						<tbody>
						<?php 
							$no = 1;
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
						?>
							<tr>
								<td style="vertical-align: middle; text-align: center"><?php echo $no ?></td>
								<td style="vertical-align: middle;">
									<?=$row->product_name;?>
								</td>
								<td style="vertical-align: middle; text-align: center">
									<?=$this->erp->formatQuantity($row->quantity);?>
								</td>
								<td style="vertical-align: middle; text-align: right;">
									$<?= $this->erp->formatMoney($row->real_unit_price); ?>
								</td>
								<?php if ($row->item_discount != 0) {?>
									<td style="vertical-align: middle; text-align: right;">
									$<?=$this->erp->formatMoney($row->item_discount);?></td>
								<?php } ?>
								<?php if ($row->item_tax != 0) {?>
									<td style="vertical-align: middle; text-align: right;">
									$<?=$this->erp->formatMoney($row->item_tax);?></td>
								<?php } ?>
								<td style="vertical-align: middle; text-align: right;">$<?= $this->erp->formatQuantity($row->subtotal);?>
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
							$row = 4;
							$col = 1;
							if ($con->discount != 0) {
								$col = 3;
							}
							if ($invs->grand_total != $invs->total) {
								$row++;
							}
							if ($invs->order_discount != 0) {
								$row++;
							}
							if ($invs->shipping != 0) {
								$row++;
							}
							if ($invs->order_tax != 0) {
								$row++;
							}

						?>
						<?php if ($invs->grand_total != $invs->total) { ?>
						<tr>
							<td rowspan = "<?= $row; ?>" colspan="4" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important;">
								<?php if (!empty($invs->invoice_footer)) { ?>
									<p style="font-size:14px !important;"><strong><u>Note:</u></strong></p>
									<p style="margin-top:-5px !important; line-height: 2"><?= $invs->invoice_footer ?></p>
								<?php } ?>
							</td>
							<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('total')) ?>
							</td>
							<td align="right">$<?=$this->erp->formatMoney($invs->total); ?></td>
						</tr>
						<?php } ?>
						<?php if ($invs->order_discount != 0) : ?>
									<tr>
										<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('order_discount')) ?></td>
										<td align="right">$<?php echo $this->erp->formatQuantity($invs->order_discount).' $'; ?></td>
									</tr>
									<?php endif; ?>
									
									<?php if ($invs->shipping != 0) : ?>
									<tr>
										<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('shipping')) ?></td>
										<td align="right">$<?php echo $this->erp->formatQuantity($invs->shipping); ?></td>
									</tr>
									<?php endif; ?>
									
									<?php if ($invs->order_tax != 0) : ?>
									<tr>
										<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('order_tax')) ?></td>
										<td align="right">$<?= $this->erp->formatQuantity($invs->order_tax); ?></td>
									</tr>
									<?php endif; ?>
									
									<tr>
										<?php if ($invs->grand_total == $invs->total) { ?>
										<td rowspan="<?= $row; ?>" colspan="3" style="border-left: 1px solid #FFF !important; border-bottom: 1px solid #FFF !important;">
											<?php if (!empty($invs->invoice_footer)) { ?>
												<p><strong><u>Note:</u></strong></p>
												<p><?= $invs->invoice_footer ?></p>
											<?php } ?>
										</td>
										<?php } ?>
										<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('total_amount')) ?>
										</td>
										<td align="right">$<?= $this->erp->formatMoney($invs->grand_total); ?></td>
									</tr>
									<?php if($invs->paid != 0 ) {?>
									<tr>
										<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('paid')) ?>
										</td>
										<td align="right">$<?php echo $this->erp->formatMoney($invs->paid); ?></td>
									</tr>
									<tr>
										<td colspan="<?= $col; ?>" style="text-align: right; font-weight: bold;"><?= strtoupper(lang('balance')) ?>
										</td>
										<td align="right">$<?= $this->erp->formatMoney($invs->grand_total - $invs->paid); ?></td>
									</tr>
									<?php }?>
						</tbody>
						
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<div id="footer" class="row" style="margin-top: 80px !important;">
			<div class="col-sm-4 col-xs-4">
				<!-- <hr style="margin:0; border:1px solid #000;"> -->
				<center>
					<p>អ្នកទិញ / Buyer</p>
					<!-- <p style="margin-top:-10px;">Customer's Signature & Name</p> -->
				</center>
			</div>
			<div class="col-sm-4 col-xs-4">
				<!-- <hr style="margin:0; border:1px solid #000;"> -->
				<center>
					<p>អ្នកប្រគល់ / Deliver</p>
					<!-- <p style="margin-top:-10px;">Prepared's Signature & Name</p> -->
				</center>
			</div>
			<div class="col-sm-4 col-xs-4">
				<!-- <hr style="margin:0; border:1px solid #000;"> -->
				<center>
					<p>អ្នកលក់ / Seller</p>
					<!-- <p style="margin-top:-10px;">Seller's Signature & Name</p> -->
				</center>
			</div>
		</div>
			</td>
		</tr>
		
	</table>
</div>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>