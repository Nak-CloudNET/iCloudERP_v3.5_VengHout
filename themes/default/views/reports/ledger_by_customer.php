<style type="text/css">
	/*.table {
		white-space: nowrap; 
		overflow-x: scroll; 
		width:100%;
		display:block;
	}*/
</style>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-th-large"></i><?= lang('general_ledger_by_customer'); ?><?php
            if ($this->input->post('start_date')) {
                echo " From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?>
		</h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
                            class="icon fa fa-toggle-up"></i></a></li>
                <li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
                            class="icon fa fa-toggle-down"></i></a></li>
            </ul>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>"><i
                            class="icon fa fa-file-pdf-o"></i></a></li>
                <li class="dropdown"><a href="#" id="xls" class="tip" title="<?= lang('download_xls') ?>"><i
                            class="icon fa fa-file-excel-o"></i></a></li>
                <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                            class="icon fa fa-file-picture-o"></i></a></li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("customers") ?>"></i>
					</a>
					<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
						<li><a href="<?= site_url('reports/ledger_by_customer') ?>"><i class="fa fa-building-o"></i> <?= lang('customers') ?></a></li>
						<li class="divider"></li>
						<?php
							foreach ($customers as $customer) {
								echo '<li ' . ($customer_id && $customer_id == $customer->id ? 'class="active"' : '') . '>
										<a href="' . site_url('reports/ledger_by_customer/0/0/' . $customer->id) . '"><i class="fa fa-building"></i>' . $customer->company . '</a></li>';
							}
						?>
					</ul>
				</li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <div id="form">
                    <?php echo form_open("reports/ledger_by_customer/".$v_form); ?>
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("account_name"); ?></label>
                                <?php
                                $code = $this->db;
                                $accOption = $code->select('*')->from('gl_charts')->get()->result();
                                $accountArray[""] = " ";
                                foreach ($accOption as $a) {
                                    $accountArray[$a->accountcode] = $a->accountcode . " " . $a->accountname;
                                }
                                echo form_dropdown('account', $accountArray, (isset($v_account) ? $v_account : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("account") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($start_date) ? $start_date : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($end_date) ? $end_date : ""), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls"> <?php echo form_submit('', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <div class="clearfix"></div>

                <div class="table-scroll">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped table-condensed">
						<thead>
							<tr>
								<th><?= lang('no'); ?></th>
								<th style="width:150px;"><?= lang('project');?></th>
								<th style="width:150px;"><?= lang('type'); ?></th>
								<th style="width:150px;"><?= lang('date'); ?></th>
								<th style="width:200px;"><?= lang('ref'); ?></th>
								<th style="width:150px;"><?= lang('name');?></th>
								<th style="width:250px;"><?= lang('description'); ?></th>
								<th style="width:50px;"><?= lang('created_by'); ?></th>
								<th style="width:150px;"><?= lang('debit_amount'); ?></th>
								<th style="width:150px;"><?= lang('credit_amount'); ?></th>
								<th style="width:150px;"><?= lang('balance');?></th>								
							</tr>
                        </thead>
						<tbody>
						<?php
                            $code = $this->db;
                            $code->select('*')->from('gl_charts');
                            if ($v_account) {
                                $code->where('accountcode', $v_account);
                            }
                            $accounts = $code->get()->result();							
                            foreach($accounts as $account){								
                                $startAmount = $this->db->select('sum(amount) as startAmount')
												   ->from('gl_trans')
												   ->where(
														array(
															'tran_date < '=> $this->erp->fld($start_date),
															'account_code'=> $account->accountcode
															)
														)->get()->row();
                                						
								$endAccountBalance = 0.00;
                                $glTrans = $this->db->select("
									gl_trans.*,
									(CASE WHEN erp_gl_trans.amount>0 THEN erp_gl_trans.amount END ) as am1,
									(CASE WHEN erp_gl_trans.amount<0 THEN erp_gl_trans.amount END ) as am2,
									companies.company,
									companies.name,
									users.username")
								->from('gl_trans')
								->join('companies','companies.id=gl_trans.customer_id')
								->join('users', 'users.id = gl_trans.created_by', 'left')
								->order_by('tran_date', 'asc')
								->where('account_code', $account->accountcode);
								
							    if ($start_date) {
                                    $glTrans->where('date(tran_date) >=', $this->erp->fld($start_date));
                                }
                                if ($end_date) {
                                    $glTrans->where('date(tran_date) <=', $this->erp->fld($end_date));
                                }
								
								if($customer_id != ""){
									$glTrans->where('gl_trans.customer_id' ,$customer_id);
								}

                                $glTranLists = $glTrans->get()->result();
								
								if($glTranLists) {?>
                                <tr>
                                    <td colspan="4" style="font-weight: bold;"><?= lang("Account"); ?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?=$account->accountcode . ' ' .$account->accountname?></td>
									<td colspan="4" style="font-weight: bold;">Begining Account Balance <i class="fa fa-caret-right" aria-hidden="true"></i></td>
									<td></td>
                                    <td class="right"><?= $this->erp->formatMoney(abs($startAmount->startAmount))?></td>
                                </tr>
                                <?php
								$endDebitAmount = 0;
								$endCreditAmount = 0;
                                foreach($glTranLists as $gltran)
                                {
									$endAccountBalance += $gltran->amount;
									$endDebitAmount += $gltran->am1;
									$endCreditAmount += $gltran->am2;
                                    ?>
									<tr>
										<td><?= $gltran->tran_no ?></td>
										<td><?= $gltran->company ?></td>
										<td><?= $gltran->tran_type ?></td>
										<td><?= $this->erp->hrld($gltran->tran_date); ?></td>
										<td><?= $gltran->reference_no ?></td>
										<td><?= ($gltran->tran_type!='JOURNAL'?$gltran->name:$gltran->created_name) ?></td>
										<td><?= $gltran->description ?></td>
										<td><?= $gltran->username ?></td>
										<td class="right"><?= ($gltran->am1 > 0 ? $this->erp->formatMoney($gltran->am1) : '0.00'); ?></td>
										<td class="right"><?= ($gltran->am2 < 1 ? $this->erp->formatMoney(abs($gltran->am2)) : '0.00')?></td>
										<td class="right"><?= $this->erp->formatMoney($endAccountBalance)?></td>
									</tr>
										<?php } ?>
									<tr>
										<td colspan="5"></td>
										<td colspan="3" style="font-weight: bold;">Ending Account Balance <i class="fa fa-caret-right" aria-hidden="true"></i></td>
										<td class="right"><?=$this->erp->formatMoney($endDebitAmount)?></div></td>
										<td class="right"><?=$this->erp->formatMoney(abs($endCreditAmount))?></div></td>
										<td class="right"><?=$this->erp->formatMoney($endAccountBalance)?></div></td>
									</tr>
                                <?php
								}
                            }
                       ?>
						</tbody>
					</table>    
                </div>
            </div>
        </div>
    </div>
</div>
<h1>
    <?php
        if ($v_account) {
            $v .= "&ac=" . $v_account;
        }
    ?>
</h1>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
		$('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/ledger_by_customer/pdf/0/'.$customer_id . '?v=1'.$v)?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/ledger_by_customer/0/xls/'.$customer_id . '?v=1'.$v)?>";
            return false;
        });
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL()
                    window.open(img);
                }
            });
            return false;
        });
    });
</script>