<?php

$v = "";
/* if($this->input->post('name')){
  $v .= "&product=".$this->input->post('product');
} */

if ($this->input->post('account')) {
    $v .= "&account=" . $this->input->post('account');
}
if ($this->input->post('start_date')) {
    $v .= "&start_date=" . $this->input->post('start_date');
}
if ($this->input->post('end_date')) {
    $v .= "&end_date=" . $this->input->post('end_date');
}

?>
<style type="text/css">
    .topborder div { border-top: 1px solid #CCC; }
</style>

<style>
   .table td:nth-child(6) {
        text-align: center;
    }
   #registerTable { white-space: nowrap; }
</style>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-th-large"></i><?= lang('Cash_Books_report'); ?><?php
            if ($this->input->post('start_date')) {
                echo " From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?></h2>

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
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
                                    class="icon fa fa-building-o tip" data-placement="left"
                                    title="<?= lang("billers") ?>"></i></a>
                            <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
                                aria-labelledby="dLabel">
                                <li><a href="<?= site_url('reports/cash_books') ?>"><i
                                            class="fa fa-building-o"></i> <?= lang('billers') ?></a></li>
                                <li class="divider"></li>
                                <?php
                                foreach ($billers as $biller) {
                                    echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/cash_books/0/' . $biller->id) . '"><i class="fa fa-building"></i>' . $biller->company . '</a></li>';
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

                <p class="introtext"><?= lang('customize_report'); ?></p>

                <div id="form">

                    <?php echo form_open("reports/cash_books"); ?>
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("account_name"); ?></label>
                                <?php
                                $accounntCode = $this->db;
                                $accOption = $accounntCode->select('*')->from('gl_charts')->where('bank', 1)->get()->result();
                                $account_[""] = " ";
                                foreach ($accOption as $a) {
                                    $account_[$a->accountcode] = $a->accountcode . " " . $a->accountname;
                                }
                                echo form_dropdown('account', $account_, (isset($_POST['account']) ? $_POST['account'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("account") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : $this->erp->hrsd($start_date2)), 'class="form-control date" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : $this->erp->hrsd($end_date2)), 'class="form-control date" id="end_date"'); ?>
                            </div>
                        </div>
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("cashier"); ?></label>
                                <?php
                                $us["0"] = lang("all");
                                foreach ($users as $user) {
                                    $us[$user->id] = $user->first_name . " " . $user->last_name;
                                }
                                echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("cashier") . '"');
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
                <div class="clearfix"></div>

                <div class="table-scroll">
                    <table id="registerTable" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped reports-table table-condensed">
						<thead>
							<tr>
								<th><div class="fix-text"><?= lang('batch'); ?></div></th>
								<th><div class="fix-text"><?= lang('ref'); ?></div></th>
								<th><div class="fix-text"><?= lang('Seq'); ?></div></th>
								<th><div class="fix-text"><?= lang('cashier'); ?></div></th>
								<th><div class="fix-text"><?= lang('description'); ?></div></th>
								<th><div class="fix-text"><?= lang('date'); ?></div></th>
								<th><div class="fix-text"><?= lang('type'); ?></div></th>
								<th><div class="fix-text"><?= lang('debit_amount'); ?></div></th>
								<th><div class="fix-text"><?= lang('credit_amount'); ?></div></th>
							</tr>
                        </thead>
					
                        <tbody>
							<?php
								$numday=1;
								$startDate=date('Y-m-d',strtotime($start_date2 . " - $numday day"));
								
								$accounntCode = $this->db;
								$accounntCode->select('*')->from('gl_charts')->where('bank', 1);
								if ($this->input->post('account') ) {
									$accounntCode->where('accountcode', $this->input->post('account'));
								}
								
								$acc = $accounntCode->get()->result();
								foreach($acc as $val){
									$gl_tranStart = $this->db->select('sum(amount) as startAmount')->from('gl_trans');
									$gl_tranStart->where("date_format(tran_date,'%Y-%m-%d')!='0000-00-00'");
									$gl_tranStart->where(array('date_format(tran_date,"%Y-%m-%d") <= '=> $startDate, 'account_code'=> $val->accountcode));
									$startAmount = $gl_tranStart->get()->row();
									
									$endAccountBalance = 0;
									$getListGLTran = $this->db->select("gl_trans.*, users.username as cashier")->from('gl_trans')->join('users', 'gl_trans.created_by = users.id', 'left')->where('account_code =', $val->accountcode);
								
									$getListGLTran->where('date_format(tran_date,"%Y-%m-%d") >="'.$start_date2.'" AND date_format(tran_date,"%Y-%m-%d") <="'.$end_date2.'"');
									
									if (!$this->input->post('end_date') && !$this->input->post('end_date'))
									{
										$current_month = date('m');
										$getListGLTran->where('MONTH(tran_date)', $current_month);
									}
									if($biller_id != "" && $biller_id != NULL){
										$getListGLTran->where_in('gl_trans.biller_id', json_decode($biller_id));
									}
									if($cashier != '' || $cashier != NULL){
										$getListGLTran->where('gl_trans.created_by', $cashier);
									}
									$gltran_list = $getListGLTran->get()->result();
									if($gltran_list) { ?>
										<tr>
											<td colspan="5">Account: <?=$val->accountcode . ' ' .$val->accountname?></td>
											<td colspan="3"><b><?= lang('begining_balance') ?>: <b></td>
											<td colspan="3"><b>
												<?=$this->erp->formatMoney($startAmount->startAmount)?>
											</b></td>
										</tr>
										<?php		
											$endAccountBalance = $startAmount->startAmount;
										foreach($gltran_list as $rw)
										{
											$endAccountBalance += $rw->amount; ?>
											<tr>
												<td><div class="fix-text text-center"><?=$rw->tran_id?></div></td>
												<td><div class="fix-text"><?=$rw->reference_no?></div></td>
												<td><div class="fix-text text-center"><?=$rw->tran_no?></div></td>
												<td><div class="fix-text text-center"><?=$rw->cashier?></div></td>
                                                <td>
                                                    <div class="fix-text text-left"><?= $rw->description ?></div>
                                                </td>
												<td><div class="fix-text"><?=$rw->tran_date?></div></td>
												<td><div class="fix-text text-center"><?=$rw->tran_type?></div></td>
												<td><div class="fix-text text-center"><?=($rw->amount > 0 ? $this->erp->formatMoney($rw->amount) : '0.00')?></div></td>
												<td><div class="fix-text text-center"><?=($rw->amount < 1 ? $this->erp->formatMoney(abs($rw->amount)) : '0.00')?></div></td>
											</tr>
											<?php
										} ?>
										<tr>
											<td colspan="5"> </td>
											<td colspan="3"><b><?= lang('ending_balance') ?>: </b></td>
											<td colspan="3"><b><?=$this->erp->formatMoney($endAccountBalance)?></b></td>
										</tr>
										<?php
									}
								}
							
								?>
								
							<tr class="active">
								<th><div class="fix-text"></div></th>
								<th><div class="fix-text"></div></th>
								<th><div class="fix-text"></div></th>
								<th><div class="fix-text"></div></th>
								<th><div class="fix-text"></div></th>
								<th><div class="fix-text"></div></th>
								<th><div class="fix-text"></div></th>
								<th><div class="fix-text"></div></th>
								<th><div class="fix-text"></div></th>
							</tr>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>
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
            window.location.href = "<?=site_url('reports/cash_books/pdf')?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/cash_books/0/0/xls/?v=1'.$v)?>";
            return false;
        });
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL();
                    window.open(img);
                }
            });
            return false;
        });
    });
</script>