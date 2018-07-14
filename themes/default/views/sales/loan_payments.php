<style type="text/css">
    @media print {
        #myModal .modal-content {
            display: block !important;
        }
		#myModal .modal-content .noprint {
			display: none !important;
		}
    }
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content payment" id="payment">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('view_payments'); ?></h4>
        </div>
        <div class="modal-body print">
            <div class="table-responsive">
                <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th style="width:30%;"><?= $this->lang->line("date"); ?></th>
                        <th style="width:30%;"><?= $this->lang->line("reference_no"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("amount"); ?></th>
						<th style="width:10%;"><?= $this->lang->line("discount"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("paid_by"); ?></th>
						<th style="width:10%;"><?= $this->lang->line("account"); ?></th>
                        <th class="noprint" style="width:10%;"><?= $this->lang->line("actions"); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if (!empty($payments)) {
                        $total_amount = 0;
                        foreach ($payments as $payment) { ?>
                            <tr class="row<?= $payment->id ?>">
                                <td><?= $this->erp->hrld($payment->date); ?></td>
                                <td><?= lang($payment->reference_no); ?></td>
                                <td><?= $this->erp->formatMoney($payment->amount) . ' ' . (($payment->attachment) ? '<a href="' . base_url('assets/uploads/' . $payment->attachment) . '" target="_blank"><i class="fa fa-chain"></i></a>' : ''); ?></td>
								<td><?= lang($payment->discount); ?></td>
                                <td><?= lang($payment->paid_by); ?></td>
								<td><?= lang($payment->accountname); ?></td>
                                <td class="noprint">
                                    <div class="text-center">
                                  
										<a href="<?= site_url('sales/bill_reciept_form/' . $payment->id) ?>"
                                           target="_blank"><i class="fa fa-file-text-o"></i></a> |

                                        <?php if ($payment->paid_by != 'gift_card') { ?>
                                            <a href="<?= site_url('sales/edit_payment/' . $payment->id) ?>"
                                               data-toggle="modal" data-target="#myModal2"><i
                                                    class="fa fa-edit"></i></a>
                                           
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                        <?php $total_amount += $payment->amount;}
                    } else {
                        echo "<tr><td colspan='4'>" . lang('no_data_available') . "</td></tr>";
                    } ?>
                    </tbody>
					<tfoot>
						<tr>
							<td colspan="2" class="text-right">Total</td>
							<td colspan="2" class="text-left"><?php echo $this->erp->formatMoney($total_amount) ?></td>
							<td></td>
						</tr>
					</tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
        $(document).on('click', '.po-delete', function () {
            var id = $(this).attr('id');
            $(this).closest('tr').remove();
        });
    });
</script>
