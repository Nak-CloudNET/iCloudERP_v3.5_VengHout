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
                id="myModalLabel"><?= lang('close_register') . ' (' . $this->erp->hrld($register_open_time ? $register_open_time : $this->session->userdata('register_open_time')) . ' - ' . $this->erp->hrld(date('Y-m-d H:i:s')) . ')'; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("pos/close_register/" . $user_id, $attrib);
        ?>
        <div class="modal-body">
            <div id="alerts"></div>
			<?php if($pos_settings->count_cash) {
				$totalsales = $cash_in_hand + $cashsales->paid + $chsales->paid + $ccsales->paid + $memsales->paid + $vouchersales->paid;
				$total_sales = $cashsales->paid + $chsales->paid + $ccsales->paid + $memsales->paid + $vouchersales->paid;
				$total_sales2 = $cashsales->total + $chsales->total + $ccsales->total + $memsales->total + $vouchersales->total;
				$total_cash = $totalsales - $refunds->returned;
				if($Admin || $Owner) { 
			?>
			
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

					<tr>
						<td width="300px;" style="font-weight:bold;"><h4><?= lang('total_sales'); ?>:</h4></td>
						<td width="200px;" style="font-weight:bold;text-align:right;">
							<h4>
								<span><?= $this->erp->formatMoney($total_sales ? $total_sales : '0.00') . ' (' . $this->erp->formatMoney($total_sales2 ? $total_sales2 : '0.00') . ')'; ?></span>
							</h4>
						</td>
					</tr>
					<tr>
						<td style="border-top: 1px solid #DDD;"><h4><?= lang('refunds'); ?>:</h4></td>
						<td style="text-align:right;border-top: 1px solid #DDD;"><h4>
								<span><?= $this->erp->formatMoney($refunds->returned ? $refunds->returned : '0.00') . ' (' . $this->erp->formatMoney($refunds->total ? $refunds->total : '0.00') . ')'; ?></span>
							</h4></td>
					</tr>
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
			<?php } ?>
				
            <div class="row no-print">
                <div class="col-sm-6">
                    <div class="form-group">
                        <?= lang("total_cash", "total_cash_submitted"); ?>
                        <?= form_hidden('total_cash', $total_cash); ?>
                        <?= form_hidden('hidden_total_cash', $total_cash); ?>
                        <?= form_input('total_cash_submitted', (isset($_POST['total_cash_submitted']) ? $_POST['total_cash_submitted'] : $total_cash), 'class="form-control input-tip" id="total_cash_submitted" required="required" readonly="readonly"'); ?>
                    </div>
					
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
					
                    <div class="form-group">
                        <?= lang("total_cheques", "total_cheques_submitted"); ?>
                        <?= form_hidden('total_cheques', $chsales->total_cheques); ?>
                        <?= form_input('total_cheques_submitted', (isset($_POST['total_cheques_submitted']) ? $_POST['total_cheques_submitted'] : $chsales->total_cheques), 'class="form-control input-tip" id="total_cheques_submitted" required="required"'); ?>
                    </div>	
					
					<div class="form-group">
                        <?= lang("total_voucher_slips", "total_voucher_slips_submitted"); ?>
                        <?= form_hidden('total_voucher_slips', $vouchersales->total_voucher); ?>
                        <?= form_input('total_voucher_slips_submitted', (isset($_POST['total_voucher_slips_submitted']) ? $_POST['total_voucher_slips_submitted'] : $vouchersales->total_voucher), 'class="form-control input-tip" id="total_voucher_slips_submitted" required="required"'); ?>
                    </div>
					
					<div class="form-group">
                        <?= lang("total_member_slips", "total_member_slips_submitted"); ?>
                        <?= form_hidden('total_member_slips', $memsales->total_mem); ?>
                        <?= form_input('total_member_slips_submitted', (isset($_POST['total_member_slips_submitted']) ? $_POST['total_member_slips_submitted'] : $memsales->total_mem), 'class="form-control input-tip" id="total_member_slips_submitted" required="required"'); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="col-sm-6 text-top">
						<div class="form-group">
							<br/>
							<span style="font-size: 1.2em; font-weight: bold; margin-left: 60px !important;"><?= lang('cur_us'); ?></span>
							<br/>
							<div class="col-sm-10">
								<input name="cur_us" type="text" id="cur_us" class="pa form-control input-lg kb-pad cur_us" style="text-align:left;"/>
							</div>
							<div class="col-sm-10 btn-group btn-group-vertical ">
								<?php
								$arr_us = array("1","2","5","10","20","50","100");
								foreach ($arr_us as $data_us) {
									echo '<button type="button" class="btn btn-lg btn-warning btncurr_us">' . $data_us . '</button>';
								}
								?>
								<button type="button" class="btn btn-lg btn-danger"
										id="clear-cash-notes"><?= lang('clear'); ?></button>
								<br/>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<br/>
							<span style="font-size: 1.2em; font-weight: bold;margin-left: 60px;"><?= lang('cur_kh'); ?></span>
							<br/>
							<div class="col-sm-10">
								<input name="cur_kh" type="text" id="cur_kh" class="pa form-control input-lg kb-pad cur_kh" style="text-align:left;" />
							</div>
							<div class="col-sm-10 btn-group btn-group-vertical ">
								<?php
								$arr_kh = array("100","200","500","1000","2000","5000","10000","20000");
								foreach ($arr_kh as $data_kh) {
									echo '<button type="button" class="btn btn-lg btn-warning btncurr_kh">' . $data_kh . '</button>';
								}
								?>
								<button type="button" class="btn btn-lg btn-danger"
										id="clear-cash-notes_kh"><?= lang('clear'); ?></button>
								<br/>
							</div>
						</div>
					</div>
                </div>
			<?php }else { ?>
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
						$totalsales = $cash_in_hand + $cashsales->paid + $chsales->paid + $ccsales->paid + $memsales->paid + $vouchersales->paid;
						$total_sales = $cashsales->paid + $chsales->paid + $ccsales->paid + $memsales->paid + $vouchersales->paid;
						
						$total_sales2 = $cashsales->total + $chsales->total + $ccsales->total + $memsales->total + $vouchersales->total;
					?>

					<tr>
						<td width="300px;" style="font-weight:bold;"><h4><?= lang('total_sales'); ?>:</h4></td>
						<td width="200px;" style="font-weight:bold;text-align:right;">
							<h4>
								<span><?= $this->erp->formatMoney($total_sales ? $total_sales : '0.00') . ' (' . $this->erp->formatMoney($total_sales2 ? $total_sales2 : '0.00') . ')'; ?></span>
							</h4>
						</td>
					</tr>
					<tr>
						<td style="border-top: 1px solid #DDD;"><h4><?= lang('refunds'); ?>:</h4></td>
						<td style="text-align:right;border-top: 1px solid #DDD;"><h4>
								<span><?= $this->erp->formatMoney($refunds->returned ? $refunds->returned : '0.00') . ' (' . $this->erp->formatMoney($refunds->total ? $refunds->total : '0.00') . ')'; ?></span>
							</h4></td>
					</tr>
				<!--    <tr>
						<td style="border-bottom: 1px solid #DDD;"><h4><?= lang('expenses'); ?>:</h4></td>
						<td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
								<span><?php $expense = $expenses ? $expenses->total : 0; echo $this->erp->formatMoney($expense) . ' (' . $this->erp->formatMoney($expense) . ')'; ?></span>
							</h4></td>
					</tr> -->
					<?php
						//$total_cash = $totalsales - $refunds->returned - $expenses->total;
						
						$total_cash = $totalsales - $refunds->returned;
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
			<?php } ?>
			<?php if($pos_settings->count_cash) { ?>
	            <div class="form-group no-print">
	                <label for="note" style="padding-left: 15px; padding-right: 15px"><?= lang("note"); ?></label>

	                <div class="controls" style="padding-left: 15px; padding-right: 15px"> <?= form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note" style="margin-top: 10px; height: 100px;"'); ?> </div>
	            </div>
		        <div class="text-right no-print" style="padding-left: 15px; padding-right: 15px">
		            <?= form_submit('close_register', lang('close_register'), 'class="btn btn-primary"'); ?>
		        </div>
		    <?php } else { ?>
				<div class="form-group no-print">
	                <label for="note"><?= lang("note"); ?></label>

	                <div class="controls"> <?= form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note" style="margin-top: 10px; height: 100px;"'); ?> </div>
	            </div>
		        <div class="text-right no-print">
		            <?= form_submit('close_register', lang('close_register'), 'class="btn btn-primary"'); ?>
		        </div>
		    <?php } ?>
        </div>

        </div><!-- // Modal Body-->
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
		var pi = "cur_us";
		var khmrate = '<?= $exchange_rate->rate ?>';
		$(document).on('keyup', '.cur_us', function () {
			var amt = $(this).val();
			var boxValue = $("#cur_kh").val();
			if(boxValue == ""){
				boxValue = 0;
			}
			if(khmrate == ""){
				khmrate = 0;
			}
			if(amt == ""){
				amt = 0;
			}
			var totalmoney = parseFloat(amt) + (parseFloat(boxValue)/khmrate);			
			$("#hidden_total_cash").val(parseFloat(totalmoney));
			$("#total_cash_submitted").val(formatMoney(totalmoney));
		});
		$(document).on('keyup', '.cur_kh', function () {
				var amt = $(this).val();
				var boxValue = $("#cur_us").val();
				if(boxValue == ""){
					boxValue = 0;
				}
				if(khmrate == ""){
					khmrate = 0;
				}
				if(amt == ""){
					amt = 0;
				}
				var totalmoney = (parseFloat(amt)/khmrate) +parseFloat(boxValue);
				$("#hidden_total_cash").val(parseFloat(totalmoney));
				$("#total_cash_submitted").val(formatMoney(totalmoney));
		});

		$(document).on('click', '.btncurr_us', function () {
            var $btncurr_us = $(this);
			var boxValue = $("#cur_kh").val();
			if(boxValue == ""){
				boxValue = 0;
			}
			if(khmrate == ""){
				khmrate = 0;
			}
            var amt = $btncurr_us.contents().filter(function () {
                return this.nodeType == 3;
            }).text();
            var th = site.settings.thousands_sep == 0 ? '' : site.settings.thousands_sep;
            var dollar_pi = $('#' + pi);
            amt = formatDecimal(amt.split(th).join("")) * 1 + dollar_pi.val() * 1;
            dollar_pi.val(formatDecimal(amt)).focus();
            var note_count = $btncurr_us.find('span');
            if (note_count.length == 0) {
                $btncurr_us.append('<span class="badge" style="text-align:right;">1</span>');
            } else {
                note_count.text(parseInt(note_count.text()) + 1);
            }
			var totalmoney = parseFloat(amt) + (parseFloat(boxValue)/khmrate);
			$("#hidden_total_cash").val(parseFloat(totalmoney));
			$("#total_cash_submitted").val(formatMoney(totalmoney));
        });
		$(document).on('click', '#clear-cash-notes', function () {
			$('.btncurr_us').find('.badge').remove();
            $('#' + pi).val(0).focus();
			var valuecurr_us = $("#cur_us").val();
			var valuecurr_kh = $("#cur_kh").val();
			if(valuecurr_us == ""){
				valuecurr_us = 0;
			}
			if(khmrate == ""){
				khmrate = 0;
			}
			if(valuecurr_kh == ""){
				valuecurr_kh = 0;
			}
			var totalmoney = parseFloat(valuecurr_us) + (parseFloat(valuecurr_kh)/khmrate);
			$("#hidden_total_cash").val(parseFloat(totalmoney));
			$("#total_cash_submitted").val(formatMoney(totalmoney));
        });

		var pi_kh = "cur_kh";
		$(document).on('click', '.btncurr_kh', function () {
            var $btncurr_kh = $(this);
			var boxValue = $("#cur_us").val();
			if(boxValue == ""){
				boxValue = 0;
			}
			if(khmrate == ""){
				khmrate = 0;
			}
            var amt = $btncurr_kh.contents().filter(function () {
                return this.nodeType == 3;
            }).text();
            var th = site.settings.thousands_sep == 0 ? '' : site.settings.thousands_sep;
            var dollar_pi = $('#' + pi_kh);
            amt = formatDecimal(amt.split(th).join("")) * 1 + dollar_pi.val() * 1;
            dollar_pi.val(formatDecimal(amt)).focus();
            var note_count = $btncurr_kh.find('span');
            if (note_count.length == 0) {
                $btncurr_kh.append('<span class="badge" style="text-align:right;">1</span>');
            } else {
                note_count.text(parseInt(note_count.text()) + 1);
            }
			var totalmoney = (parseFloat(amt)/khmrate) + parseFloat(boxValue);
			$("#hidden_total_cash").val(parseFloat(totalmoney));
			$("#total_cash_submitted").val(formatMoney(totalmoney));
        });
		$(document).on('click', '#clear-cash-notes_kh', function () {
			$('.btncurr_kh').find('.badge').remove();
            $('#' + pi_kh).val(0).focus();
			var valuecurr_us = $("#cur_us").val();
			var valuecurr_kh = $("#cur_kh").val();
			if(valuecurr_us == ""){
				valuecurr_us = 0;
			}
			if(khmrate == ""){
				khmrate = 0;
			}
			if(valuecurr_kh == ""){
				valuecurr_kh = 0;
			}
			var totalmoney = parseFloat(valuecurr_us) + (parseFloat(valuecurr_kh)/khmrate);
			$("#hidden_total_cash").val(parseFloat(totalmoney));
			$("#total_cash_submitted").val(formatMoney(totalmoney));
        });
</script>


