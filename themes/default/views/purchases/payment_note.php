<style type="text/css">
    @media print {
        #myModal .modal-content {
            display: none !important;
        }
        .modal-dialog {
    		width: 98% !important;
    		height: 842px !important;
    		margin: 0 auto !important;
    		padding: 0 !important;
    	}
        .modal-content{
        	border: none !important;
        }

        .modal-body {
        	height: 515px !important;
        	padding: 0 !important;
        	line-height: 95% !important;
        }
        .table tr td {
        	height: 5px !important;
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
            <?php for($i=0; $i<=1; $i++){?>
            <div class="clearfix"></div>
            <div class="row padding10">
                <div class="col-xs-12 text-center">
                	<div class="col-xs-3">
                		<?php if ($logo) { ?>
		                <div class="text-center" style="margin-bottom:20px;">
		                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
		                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
		                </div>
		                <?php } ?>
                	</div>
                	<div class="col-xs-6">
						<p style="font-size:20px;"><?php echo $biller->company;?></p>
	                    <?php if($biller->address){
	                    echo'<b>' . lang("address") . ": ". "</b>".$biller->address."</br>";}
	                    if($biller->phone){
	                    echo '<b>' . lang("tel") . ": " . "</b>" .$biller->phone."</br>";}
	                    if($biller->email){
	                    echo '<b>' . lang("email") . ": " ."</b>". $biller->email."</br>";}
	                    ?>
                    	<div class="clearfix"></div>
                    	</br>
                    	<p style="padding:0px; font-size:20px;">PAYMENT NOTED</p>
					</div>
					<div class="col-xs-3"></div>
                </div>
            </div>

            <div class="row" style="width: 90%; margin-left: 3%;">
            	<div class="col-sm-5 col-xs-5" style="float:left;">
                	<b><p>Payment To</p></b>
                	<!-- <p>Name: <?= $supplier->name?></p>
                    <p><?= lang("address"); ?>: <?= $supplier->address;?></p>
					<p><?= lang("phone"); ?>: <?= $supplier->phone; ?></p> -->
					 <table>
                        <tr>
                            <td><?=$this->lang->line("name");?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?="<span>". ($supplier->company ? $supplier->company : $supplier->name) ."</span>";?></td>
                        </tr>
                        <tr>
                            <td><?=lang("address") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($supplier->address ? $supplier->address : ""). ($supplier->city ? "<br>" . $supplier->city : "") . ($supplier->postal_code ? " " .$supplier->postal_code : "") . ($supplier->state ? " " .$supplier->state : "") .  ($supplier->country ? "<br>" .$supplier->country : ""); ?></td>
                        </tr>
                        <?php if ($supplier->company == ""){?>
                        <tr>
                            <td>Attn</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$supplier->name;?></td>
                        </tr>
                        <?php }?>
                        <?php if ($supplier->phone !='' || $supplier->email !=''): ?>
                        <tr>
                            <td><?=lang("contact") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($supplier->phone ? $supplier->phone.'/'.$supplier->email : $supplier->email) ?></td>
                        </tr>
                        <?php endif ?>
                    </table>
					
                </div>
                <div class="col-sm-5 col-xs-5 text-left" style="float:right;">
					<div class="pull-right">
					<b><p>Reference</p></b>
						<table>
							<tr>
	                            <td><?=lang("voucher_no");?></td>
	                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
	                            <td><?= $payment->reference_no; ?></td>
	                        </tr>
	                        <tr>
	                            <td><?=lang("date");?></td>
	                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
	                            <td><?= $this->erp->hrsd($payment->date); ?></td>
	                        </tr>
						</table>					
					</div>
                </div>
            </div>
            <br>
            <div class="well">
				<table class="table receipt">
					<thead>
						<tr>
							<th><?= lang("no"); ?></th>
							<!-- <th><?= lang("date"); ?></th> -->
							<th><?= lang("reference_no"); ?></th>
							<th style="padding-left:10px;padding-right:10px;"><?= lang("amount"); ?> </th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$m_us = 0;
						$payments = 0;
						foreach($rows as $row){
							$free = lang('free');
							//$this->erp->print_arrays($row);
							echo '<tr class="item"><td class="text-center">#' . $no . "</td>";
							// echo '<td class="text-center">' . $this->erp->hrsd($row->payment_date) . '</td>';
							echo '<td class="text-center">' . $row->reference_no .'</td>';
							echo '<td class="text-center">' . $this->erp->formatMoney($row->amount) .'</td></tr>';
							$payments += $row->amount;
							$no++;
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th></th>
							<th class="text-center"><?= lang("total"); ?></th>
							<th class="text-center"><?=  $this->erp->formatMoney($payments); ?></th>
						</tr>
						<tr colspan="5">
							<table class="table table-striped" style="margin: 0; padding: 0">
								<tbody>
									<tr>
										<td style="width:150px;"><strong><?= lang("paid_by"); ?>:</strong></td>
										<td>
										<?php
											if ($payment->paid_by == 'Cheque') {
												echo ucwords($payment->paid_by).'  #'.$payment->cheque_no;
											}elseif ($payment->paid_by == 'CC') {
												echo "Credit Card";
											} else {
												echo ucwords($payment->paid_by);
											}
										?>
										</td>
									</tr>
									<tr>
										<td>
											<strong><?= lang("note"); ?>:</strong>
										</td>
										<td><strong><?=ucwords($this->erp->decode_html(strip_tags($payment->note)));?></strong></td>
									</tr>
								</tbody>
							</table>
						</tr>
					</tfoot>
				</table>
            </div>
            <p>Amount In Word: <?=ucwords($this->erp->convert_number_to_words($payments));?> US Dollar Only</p>
			<p class="alert text-center"><?= $this->erp->decode_html($biller->invoice_footer); ?></p>
            <div style="clear: both;"></div>
            <div class="row">
				<div class="col-sm-4 pull-left">
					<p>&nbsp;</p>

                    <p style="border-bottom: 1px solid #666;">&nbsp;</p>

                    <p style="text-align: center;"><?= lang("cashier"); ?></p>
                </div>
				<div class="col-sm-4 pull-left">
                </div>
                <div class="col-sm-4 pull-right">
                    <p>&nbsp;</p>

                    <p style="border-bottom: 1px solid #666;">&nbsp;</p>

                    <p style="text-align: center;"><?= lang("supplier"); ?></p>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr>
            <?php }?>
        </div>
    </div>
</div>