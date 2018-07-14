<style type="text/css">
    @media print {
        #myModal .modal-content {
            display: none !important;
        }
    }
</style>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body print">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
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
            <div class="clearfix"></div>
            <div class="row padding10">
                <div class="col-xs-12 text-center">
						<p style="padding:0px; font-size:20px;">RECEIPT VOUCHER</p>
                    <?php
                    echo $biller->address . " " . $biller->city . " " . $biller->postal_code . " " . $biller->state . " " . $biller->country;

					echo "<br/>";
                    echo '<b>' . lang("tel") . ": " . $biller->phone . "</b><br />" . lang("email") . ": " . $biller->email;
                    ?>
                    <div class="clearfix"></div>
                </div>
				<!--
                <div class="col-xs-6">
                    <h2 class=""><?= $customer->company ? $customer->company : $customer->name; ?></h2>
                    <?= $customer->company ? "" : "Attn: " . $customer->name ?>
                    <?php
                    echo $customer->address . " " . $customer->city . " " . $customer->postal_code . " " . $customer->state . " " . $customer->country;
/*                    echo "<p>";
                    if ($customer->cf1 != "-" && $customer->cf1 != "") {
                        echo "<br>" . lang("ccf1") . ": " . $customer->cf1;
                    }
                    if ($customer->cf2 != "-" && $customer->cf2 != "") {
                        echo "<br>" . lang("ccf2") . ": " . $customer->cf2;
                    }
                    if ($customer->cf3 != "-" && $customer->cf3 != "") {
                        echo "<br>" . lang("ccf3") . ": " . $customer->cf3;
                    }
                    if ($customer->cf4 != "-" && $customer->cf4 != "") {
                        echo "<br>" . lang("ccf4") . ": " . $customer->cf4;
                    }
                    if ($customer->cf5 != "-" && $customer->cf5 != "") {
                        echo "<br>" . lang("ccf5") . ": " . $customer->cf5;
                    }
                    if ($customer->cf6 != "-" && $customer->cf6 != "") {
                        echo "<br>" . lang("ccf6") . ": " . $customer->cf6;
                    }
                    echo "</p>";
*/
					echo "<br/>";
                    echo lang("tel") . ": " . $customer->phone . "<br />" . lang("email") . ": " . $customer->email;
                    ?>

                </div>
				-->
            </div>

            <div class="row">
                <div class="col-sm-5" style="float:left;">
                    <p><b><?= lang("receipt"); ?></b>: <?= $payment->reference_no; ?></p>
					<p><b><?= lang("date"); ?></b>: <?= $this->erp->hrsd($payment->date); ?></p>
					<p><b>Received From</b>: <?= $biller->company != '-' ? $biller->company : $biller->name; ?></p>
                </div>
				<div class="col-sm-5 text-left" style="float:right;">
					<div class="pull-right">
						
					</div>
                </div>
            </div>
            <div class="well">
				<table class="table receipt">
					<thead>
						<tr>
							<th><?= lang("no"); ?></th>
							<th><?= lang("reference"); ?></th>
							<th style="padding-left:10px;padding-right:10px;"><?= lang("amount"); ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$m_us = 0;
						$payments = 0;
						foreach($rowpay as $row){
							echo '<tr class="item"><td class="text-left">#' . $no . "</td>";
							echo '<td class="text-left">' . $row->reslae. '</td>';
							echo '<td class="text-right">' . ($this->erp->formatMoney($row->amount)) . '</td>';
							$no++;
							$payments += $row->amount;
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="2" class="text-right"><?= lang("total"); ?></th>
							<th class="text-right"><?=  $this->erp->formatMoney($payments); ?></th>
						</tr>
						
						<tr colspan="5">
							<table class="table table-striped">
							
								<tbody>
									
									
									
									<tr>
										<td style="width:150px;"><strong><?= lang("paid_by"); ?>:</strong></td>
										<td>
											<?php
														echo $payment->paid_by;
													
												?>
										</td>
									</tr>
									<tr>
										<td>
											<strong><?= lang("note"); ?>:</strong>
										</td>
										<td><strong><?php echo $payment->note; ?></strong></td>
									</tr>
								</tbody>
							
							</table>
						</tr>
					</tfoot>
				</table>
			<!--
                <table class="table table-borderless" style="margin-bottom:0;">
                    <tbody>
                    <tr>
						<tr>
							<td>
								<strong><?= lang("current_balance"); ?></strong>
							</td>
							<td><strong> : <?php echo $this->erp->formatMoney($curr_balance); ?></strong></td>
						</tr>
						
						<?php if($payment->extra_paid != 0) { ?>
						<tr>
							<td>
								<strong><?= lang("paid"); ?></strong>
							</td>
							<td><strong> : <?php echo $this->erp->formatMoney(($payment->amount-$payment->extra_paid)); ?></strong></td>
						</tr>
						<tr>
							<td>
								<strong><?= lang("extra_paid"); ?></strong>
							</td>
							<td><strong> : <?php echo $this->erp->formatMoney($payment->extra_paid); ?></strong></td>
						</tr>
						<?php } ?>
                        <td width="25%">
                            <strong><?= $payment->type == 'returned' ? lang("payment_returned") : lang("payment_received"); ?></strong>
                        </td>
                        <td>
							<strong> : 
								<?php echo '$ '. $this->erp->formatMoney($payment->amount) .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ( '. $this->erp->convert_number_to_words(($this->erp->fraction($payment->amount) > 0)? $this->erp->formatMoney($payment->amount) : number_format($payment->amount)) .' dolar )'; ?>
							</strong>
                        </td>
						<tr>
							<td>
								<strong><?= lang("balance"); ?></strong>
							</td>
							<td><strong> : <?php echo $this->erp->formatMoney($curr_balance - ($payment->amount - $payment->extra_paid)); ?></strong></td>
						</tr>
                    </tr>
                    <tr>
                        <td><strong><?= lang("paid_by"); ?></strong></td>
                        <td>
							<strong> : 
								<?php echo lang($payment->paid_by);
									if ($payment->paid_by == 'gift_card' || $payment->paid_by == 'CC') {
										echo ' (' . $payment->cc_no . ')';
									} elseif ($payment->paid_by == 'Cheque') {
										echo ' (' . $payment->cheque_no . ')';
									}
                                ?>
							</strong>
						</td>
                    </tr>

					 <tr>
                        <td>
                            <strong><?= lang("note"); ?></strong>
                        </td>
                        <td><strong><?php echo $payment->note; ?></strong></td>
                    </tr>
                    </tbody>
                </table>
				-->
            </div>
			<p>Amount In Word: <?=$this->erp->convert_number_to_words($payments)?> US Dollar Only</p>
			
            <div style="clear: both;"></div>
            <div class="row">
				<div class="col-sm-4 pull-left">
                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <p style="border-bottom: 1px solid #666;">&nbsp;</p>

                    <p><?= lang("Client`s_Signature"); ?></p>
                </div>
				<div class="col-sm-4 pull-left">
                </div>
                <div class="col-sm-4 pull-right">
                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <p style="border-bottom: 1px solid #666;">&nbsp;</p>

                    <p><?= lang("receiver_signature"); ?></p>
                </div>
            </div>
            <div class="clearfix"></div>
			 <div class="buttons">
                <div class="btn-group">
					<a href="<?= site_url('account/bill_reciept_form/' . $payment->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice') ?>">
						<i class="fa fa-print"></i>
						<span class="hidden-sm hidden-xs"><?= lang('invoice') ?></span>
					</a>
				</div>
				<div class="btn-group">
					<a href="<?= site_url('account/bill_reciept_tps/' . $payment->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice') ?>">
						<i class="fa fa-print"></i>
						<span class="hidden-sm hidden-xs"><?= lang('TPS_Invoice') ?></span>
					</a>
				</div>
			</div>
         </div>
    </div>
</div>