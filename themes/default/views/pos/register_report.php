<style>
    @media print {
        .modal-content {
            width: 100% !important;
        }
        table tr h4 {
            font-size: 10px !important;
        }

        thead tr th {
            font-size: 10px !important;
        }

        h3, h4 {
            font-size: 10px !important;
        }

        table thead tr th {
            font-size: 10px !important;   
        }

        .no-print {
            display: none !important;
        }
        #bills {
            font-size: 10px !important;
        }
    }
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title"
                id="myModalLabel"><?= lang('close_register') . ' (' . $this->erp->hrld($register_open_time ? $register_open_time : $this->session->userdata('register_open_time')) . ' - ' . $this->erp->hrld($register_close_time) .')'; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("pos/close_register/" . $user_id, $attrib);
        ?>
        <div class="modal-body">
            <div id="alerts"></div>
            <table width="100%" class="stable">
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('cash_in_hand'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                            <span><?= $this->erp->formatMoney($cash_in_hand ? $cash_in_hand : $this->session->userdata('cash_in_hand')); ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('cash_sale'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                            <span><?= $this->erp->formatMoney($cashsales->paid ? $cashsales->paid : '0.00') . ' (' . $this->erp->formatMoney($cashsales->total ? $cashsales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('ch_sale'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #EEE;"><h4>
                            <span><?= $this->erp->formatMoney($chsales->paid ? $chsales->paid : '0.00') . ' (' . $this->erp->formatMoney($chsales->total ? $chsales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
				
                <tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('cc_sale'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                            <span><?= $this->erp->formatMoney($ccsales->paid ? $ccsales->paid : '0.00') . ' (' . $this->erp->formatMoney($ccsales->total ? $ccsales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
				
				<tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('mm_sale'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                            <span><?= $this->erp->formatMoney($memsales->paid ? $memsales->paid : '0.00') . ' (' . $this->erp->formatMoney($memsales->total ? $memsales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
				
				<tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('voucher_sale'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                            <span><?= $this->erp->formatMoney($vouchersales->paid ? $vouchersales->paid : '0.00') . ' (' . $this->erp->formatMoney($vouchersales->total ? $vouchersales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
				
				
				
                <?php if ($pos_settings->paypal_pro) { ?>
                    <tr>
                        <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('paypal_pro'); ?>:</h4></td>
                        <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                                <span><?= $this->erp->formatMoney($pppsales->paid ? $pppsales->paid : '0.00') . ' (' . $this->erp->formatMoney($pppsales->total ? $pppsales->total : '0.00') . ')'; ?></span>
                            </h4></td>
                    </tr>
                <?php } ?>
                <?php if ($pos_settings->stripe) { ?>
                    <tr>
                        <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('stripe'); ?>:</h4></td>
                        <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                                <span><?= $this->erp->formatMoney($stripesales->paid ? $stripesales->paid : '0.00') . ' (' . $this->erp->formatMoney($stripesales->total ? $stripesales->total : '0.00') . ')'; ?></span>
                            </h4></td>
                    </tr>
                <?php } ?>

                <?php
                    $total_sales = ((($cash_in_hand ? $cash_in_hand : $this->session->userdata('cash_in_hand')) + $cashsales->paid) + ($chsales->paid + $ccsales->paid) + ($memsales->paid + $vouchersales->paid));
                    $total_sales2 = $cashsales->total + $chsales->total + $ccsales->total + $memsales->total + $vouchersales->total;
                ?>

                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><?= lang('total_sales'); ?>:</h4></td>
                    <td width="200px;" style="font-weight:bold;text-align:right;"><h4>
                            <span><?= $this->erp->formatMoney($total_sales ? $total_sales : '0.00') . ' (' . $this->erp->formatMoney($total_sales2 ? $total_sales2 : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #DDD;"><h4><?= lang('refunds'); ?>:</h4></td>
                    <td style="text-align:right;border-top: 1px solid #DDD;"><h4>
                            <span><?= $this->erp->formatMoney($refunds->returned ? $refunds->returned : '0.00') . ' (' . $this->erp->formatMoney($refunds->total ? $refunds->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('expenses'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                            <span><?php $expense = $expenses ? $expenses->total : 0; echo $this->erp->formatMoney($expense) . ' (' . $this->erp->formatMoney($expense) . ')'; ?></span>
                        </h4></td>
                </tr>
                <?php
                    $total_cash = $total_sales - $refunds->returned - $expenses->total;
                ?>
                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><strong><?= lang('total_cash'); ?></strong>:</h4>
                    </td>
                    <td style="text-align:right;">
                        <h4>
                             <span><strong><?= $this->erp->formatMoney($total_cash ? $total_cash : '0.00') ?></strong></span>
                        </h4>
                    </td>
                </tr>
            </table>

            <?php

            if ($suspended_bills) {
                echo '<hr><h3>' . lang('opened_bills') . '</h3><table class="table table-hovered table-bordered"><thead><tr><th id="bills">' . lang('customer') . '</th><th id="bills">' . lang('date') . '</th><th id="bills">' . lang('total_items') . '</th><th id="bills">' . lang('amount') . '</th><th class="no-print"><i class="fa fa-trash-o"></i></th></tr></thead><tbody>';
                foreach ($suspended_bills as $bill) {
                    echo '<tr><td id="bills">' . $bill->customer . '</td><td id="bills">' . $this->erp->hrld($bill->date) . '</td><td class="text-center" id="bills">' . $bill->count . '</td><td class="text-right" id="bills">' . $bill->total . '</td><td class="text-center no-print"><a href="#" class="tip po" title="<b>' . $this->lang->line("delete_bill") . '</b>" data-content="<p>' . lang('r_u_sure') . '</p><a class=\'btn btn-danger po-delete\' href=\'' . site_url('pos/delete/' . $bill->id) . '\'>' . lang('i_m_sure') . '</a> <button class=\'btn po-close\'>' . lang('no') . '</button>"  rel="popover"><i class="fa fa-trash-o"></i></a></td></tr>';
                }
                echo '</tbody></table>';
            }

            ?>
            <hr>
            <?php //$this->erp->print_arrays($_SERVER['REQUEST_URI']); ?>
            <div class="row no-print">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("total_cash", "total_cash_submitted"); ?>
                        <?= form_hidden('total_cash', $total_cash); ?>
                        <?= form_input('total_cash_submitted', (isset($_POST['total_cash_submitted']) ? $_POST['total_cash_submitted'] : $total_cash), 'class="form-control input-tip" id="total_cash_submitted" required="required"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("total_cheques", "total_cheques_submitted"); ?>
                        <?= form_hidden('total_cheques', $chsales->total_cheques); ?>
                        <?= form_input('total_cheques_submitted', (isset($_POST['total_cheques_submitted']) ? $_POST['total_cheques_submitted'] : $chsales->total_cheques), 'class="form-control input-tip" id="total_cheques_submitted" required="required"'); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?php if ($suspended_bills) { ?>
                        <div class="form-group">
                            <?= lang("transfer_opened_bills", "transfer_opened_bills"); ?>
                            <?php $u = $user_id ? $user_id : $this->session->userdata('user_id');
                            $usrs[-1] = lang('delete_all');
                            $usrs[0] = lang('leave_opened');
                            foreach ($users as $user) {
                                if ($user->id != $u) {
                                    $usrs[$user->id] = $user->first_name . ' ' . $user->last_name;
                                }
                            }
                            ?>
                            <?= form_dropdown('transfer_opened_bills', $usrs, (isset($_POST['transfer_opened_bills']) ? $_POST['transfer_opened_bills'] : 0), 'class="form-control input-tip" id="transfer_opened_bills" required="required"'); ?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <?= lang("total_cc_slips", "total_cc_slips_submitted"); ?>
                        <?= form_hidden('total_cc_slips', $ccsales->total_cc_slips); ?>
                        <?= form_input('total_cc_slips_submitted', (isset($_POST['total_cc_slips_submitted']) ? $_POST['total_cc_slips_submitted'] : $ccsales->total_cc_slips), 'class="form-control input-tip" id="total_cc_slips_submitted" required="required"'); ?>
                    </div>
                </div>
				<div class="col-sm-6">
					<div class="form-group">
                        <?= lang("total_member_slips", "total_member_slips_submitted"); ?>
                        <?= form_hidden('total_member_slips', $memsales->total_mem); ?>
                        <?= form_input('total_member_slips_submitted', (isset($_POST['total_member_slips_submitted']) ? $_POST['total_member_slips_submitted'] : $memsales->total_mem), 'class="form-control input-tip" id="total_member_slips_submitted" required="required"'); ?>
                    </div>
				</div>
				
				<div class="col-sm-6">
					<div class="form-group">
                        <?= lang("total_voucher_slips", "total_voucher_slips_submitted"); ?>
                        <?= form_hidden('total_voucher_slips', $vouchersales->total_voucher); ?>
                        <?= form_input('total_voucher_slips_submitted', (isset($_POST['total_voucher_slips_submitted']) ? $_POST['total_voucher_slips_submitted'] : $vouchersales->total_voucher), 'class="form-control input-tip" id="total_voucher_slips_submitted" required="required"'); ?>
                    </div>
				</div>
            </div>
            <div class="form-group no-print">
                <label for="note"><?= lang("note"); ?></label>

                <div
                    class="controls"> <?= form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note" style="margin-top: 10px; height: 100px;"'); ?> </div>
            </div>

        </div>
        <div class="modal-footer no-print">
            <?= form_submit('close_register', lang('close_register'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?= form_close(); ?>
</div>

</div>
<?= $modal_js ?>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.po', function (e) {
            e.preventDefault();
            $('.po').popover({
                html: true,
                placement: 'left',
                trigger: 'manual'
            }).popover('show').not(this).popover('hide');
            return false;
        });
        $(document).on('click', '.po-close', function () {
            $('.po').popover('hide');
            return false;
        });
        $(document).on('click', '.po-delete', function (e) {
            var row = $(this).closest('tr');
            e.preventDefault();
            $('.po').popover('hide');
            var link = $(this).attr('href');
            $.ajax({
                type: "get", url: link,
                success: function (data) {
                    row.remove();
                    addAlert(data, 'success');
                },
                error: function (data) {
                    addAlert('Failed', 'danger');
                }
            });
            return false;
        });
    });
    function addAlert(message, type) {
        $('#alerts').empty().append(
            '<div class="alert alert-' + type + '">' +
            '<button type="button" class="close" data-dismiss="alert">' +
            '&times;</button>' + message + '</div>');
    }
</script>


