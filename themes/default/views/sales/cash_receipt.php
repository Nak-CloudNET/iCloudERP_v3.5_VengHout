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
	<table class="table-responsive" width="1024px" height="50%" border="0" cellspacing="0" style="margin:auto;">
		<tr>
			<td rowspan="2" width="20%" style="virticle-align:middle;">
				<?php if ($logo) { ?>
					<div class="text-center" style="margin-bottom:20px;">
						<img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
							 alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
					</div>
				<?php } ?>
			</td>
			<td>
				<table width="100%">
					<tr>
						<td width="50%"></td>
						<td width="20%"> <?= lang('receipt_no');?> </td>
						<td width="30%" style="padding-left:0px; text-align:right;"> <?= $payment->reference_no; ?> </td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="50%"></td>
						<td width="20%"> <?= lang('date');?> </td>
						<td width="30%" style="padding-left:0px; text-align:right;"> <?= $this->erp->hrld($inv->date); ?> </td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="height:100px;" colspan="2" style="text-align:center;">
				<div style="font-family:'Arial'; font-size:24px; font-weight:bold; text-align:center;"><?= lang('cash_receipt'); ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2" width="65%" align="center" style="padding-top:5px;">
				<table width="100%">
					<tr style="height:40px;">
						<td width="10%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('received_from');?> </td>
						<td width="60%" colspan="3" align="center" style="border-bottom:1px solid black;"> <?= $customer->name ? $customer->name : $customer->company; ?> </td>
						<td width="15%" style="text-align:left;"> <?= lang('amount_of');?> </td>
						<td width="15%" style="padding-left:0px; text-align:right; border-bottom:1px solid black;"> <?php echo '$ '. $this->erp->formatMoney($payment->amount); ?> </td>
					</tr>
					<tr style="height:40px;">
						<td width="10%" style="font-family:'Khmer OS'; font-size:14px;"> <?= lang('for');?> </td>
						<td colspan="5" width="90%" style="text-align:center; border-bottom:1px solid black;">  </td>
					</tr>
					<tr style="height:40px;">
						<td width="10%" style="font-family:'Khmer OS'; font-size:14px;">  </td>
						<td width="60%" colspan="3" align="center">  </td>
						<td width="15%" style="text-align:left;"> <?= lang('payment_method');?> </td>
						<td width="15%" style="padding-left:0px; text-align:center; border-bottom:1px solid black;"> 
							<?php echo lang($payment->paid_by);
								if ($payment->paid_by == 'gift_card' || $payment->paid_by == 'CC') {
									echo ' (' . $payment->cc_no . ')';
								} elseif ($payment->paid_by == 'Cheque') {
									echo ' (' . $payment->cheque_no . ')';
								}
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" width="65%" align="center" style="padding-top:5px;">
				<table width="100%">
					<tr style="height:40px;">
						<td width="20%" style="font-family:'Khmer OS'; font-size:16px;"> <?= lang('current_balance');?> : </td>
						<td width="30%" colspan="3" align="right" style="border-bottom:1px solid black; padding-right:10px;"> <?php echo '$ '. $this->erp->formatMoney($curr_balance); ?> </td>
						<td width="25%" style="text-align:left;"></td>
						<td width="25%" style="padding-left:0px; text-align:right;"></td>
					</tr>
					<tr style="height:40px;">
						<td width="20%" style="font-family:'Khmer OS'; font-size:16px;"> <?= lang('payment_amount');?> : </td>
						<td width="30%" colspan="3" align="right" style="border-bottom:1px solid black; padding-right:10px;"> <?php echo '$ '. $this->erp->formatMoney($payment->amount); ?> </td>
						<td width="25%" style="text-align:left;"></td>
						<td width="25%" style="padding-left:0px; text-align:right;"></td>
					</tr>
					<tr style="height:40px;">
						<td width="20%" style="font-family:'Khmer OS'; font-size:16px;"> <?= lang('balance_due');?> : </td>
						<td width="30%" colspan="3" align="right" style="border-bottom:1px solid black; padding-right:10px;"> <?php echo '$ '. $this->erp->formatMoney($curr_balance - $payment->amount); ?> </td>
						<td width="25%" style="text-align:left;"></td>
						<td width="25%" style="padding-left:0px; text-align:right;"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" width="65%" align="center" style="padding-top:5px;">
				<table width="100%">
					<tr style="height:40px;">
						<td width="10%" style="font-family:'Khmer OS'; font-size:16px; font-weight:bold;"> <?= lang('note');?> : </td>
						<td width="65%" colspan="3" align="right" style="border-bottom:1px solid black;"> <?php echo $payment->note; ?> </td>
						<td width="25%" style="padding-left:0px; text-align:right;"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table border="0" cellspacing="0">
					<tr>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;">
							<b style="margin-top:10px;"> <?= lang('paid_by'); ?> </b>
							<hr style="border:1px solid black; width:160px; vertical-align:bottom !important;  margin-bottom:20px; margin-top:50px;" />
						</td><td>&nbsp;</td><td>&nbsp;</td>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;padding-top:50px;">
							&nbsp;
						</td><td>&nbsp;</td><td>&nbsp;</td>
						<td colspan="3" width="33%" valign="bottom" style="text-align:center;">
							<b style="margin-top:10px;"> <?= lang('received_by'); ?> </b>
							<hr style="border:1px solid black; width:160px; vertical-align:bottom !important;  margin-bottom:20px; margin-top:50px;" />
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