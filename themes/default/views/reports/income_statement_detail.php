<script>$(document).ready(function () {
        CURI = '<?= site_url('reports/income_statement_detail'); ?>';
    });</script>
<style>@media print {
        .fa {
            color: #EEE;
            display: none;
        }
        .small-box {
            border: 1px solid #CCC;
        }
    }
	</style>
<?php
	$start_date=date('Y-m-d',strtotime($start));
	$rep_space_end=str_replace(' ','_',$end);
	$end_date=str_replace(':','-',$rep_space_end);
?>
<?php if ($Owner) {
    echo form_open('reports/income_details_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-bars"></i><?= lang('income_statement_detail'); ?> >> <?= (isset($start)?$start:""); ?> >> <?= (isset($end)?$end:""); ?></h2>

        <div class="box-icon">
            <div class="form-group choose-date hidden-xs">
                <div class="controls">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" value="<?= ($start ? $this->erp->hrld($start) : '') . ' - ' . ($end ? $this->erp->hrld($end) : ''); ?>"
                               id="daterange" class="form-control">
                        <span class="input-group-addon"><i class="fa fa-chevron-down"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
				<li class="dropdown"><a href="#" id="xls" data-action="export_excel" class="tip" title="<?= lang('download_excel') ?>"><i
                            class="icon fa fa-file-excel-o"></i></a></li>
                <li class="dropdown"><a href="#" id="pdf" data-action="export_pdf" class="tip" title="<?= lang('download_pdf') ?>"><i
                            class="icon fa fa-file-pdf-o"></i></a></li>
                <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                            class="icon fa fa-file-picture-o"></i></a></li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
							class="icon fa fa-building-o tip" data-placement="left"
							title="<?= lang("billers") ?>"></i></a>
					<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
						aria-labelledby="dLabel">
						<li><a href="<?= site_url('reports/income_statement_detail') ?>"><i
									class="fa fa-building-o"></i> <?= lang('projects') ?></a></li>
						<li class="divider"></li>
						<?php
						$b_sep = 0;
						foreach ($billers as $biller) {
							
							$biller_sep = explode('-', $this->uri->segment(7));
							if($biller_sep[$b_sep] == $biller->id){
								echo '<li class="active">&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="biller_checkbox[]" class="checkbox biller_checkbox" checked value="'. $biller->id .'" >&nbsp;&nbsp;' . $biller->company . '</li>';
								echo '<li class="divider"></li>';
								$b_sep++;
							}else{
								echo '<li>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="biller_checkbox[]" class="checkbox biller_checkbox" value="'. $biller->id .'" >&nbsp;&nbsp;' . $biller->company . '</li>';
								echo '<li class="divider"></li>';
							}
							
						}
						?>
						<li class="text-center"><a href="#" id="biller-filter" class="btn btn-primary"><?=lang('submit')?></a></li>
					</ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				
                <p class="introtext"><?= lang('list_results'); ?></p>
                <?php $num_col=6; ?>
                <div class="table-scroll">
                    <table cellpadding="0" cellspacing="0" border="0"class="table table-bordered table-condensed table-striped">
						<thead>
                        <tr class="primary">
							<th style="width:400px;"><div class="fix-text"><?= lang('type') ?></div></th>
							<th style="width:200px;"><div class="fix-text"><?= lang('date') ?></div></th>
							<th style="width:200px;"><div class="fix-text"><?= lang('inv_reference') ?></div></th>
							<th style="width:200px;"><div class="fix-text"><?= lang('name') ?></div></th>
							<th style="width:200px;"><div class="fix-text"><?= lang('description') ?></div></th>
							<?php 
							$new_billers = array();
							foreach ($billers as $b1) {
								if($this->uri->segment(7)){
									$biller_sep = explode('-', $this->uri->segment(7));
									for($i=0; $i < count($biller_sep); $i++){
										if($biller_sep[$i] == $b1->id){
											echo '<th style="width:200px;"><div class="fix-text">' . $b1->company . '</div></th>';
											$new_billers[] = array('id' => $b1->id);
										}
									}
								}else{
									$new_billers = $billers;
									echo '<th style="width:200px;"><div class="fix-text">' . $b1->company . '</div></th>';
								}
								$num_col++;
							}
								if($this->uri->segment(7)){
									$count_bill = count($new_billers);
									$col1 =$count_bill + 5;
								}else{
									$count_bill = count($new_billers);
									$col1 = 6;
								}
							?>
							<th style="width:200px;"><div class="fix-text"><?= lang('total') ?></div></th>
                        </tr>
						<tr class="primary"> 
                            <th style="text-align:left;" colspan="<?=$num_col?>"><?= lang("income"); ?></th>
                        </tr>
                        </thead>

                        <tbody>
						<?php
						
							$total_income_array = array();
							$total_cost_array = array();
							$total_op_array = array();
							
							$sum_total_income = array();
							$sum_total_cost = array();
							$sum_total_op = array();
							$sum_total_gross = array();
							
							$sum_income_per_acc = array();
							$sum_cost_per_acc = array();
							$sum_op_per_acc = array();
						
							$total_income = 0;
                            $totalBeforeAyear_income = 0;
							foreach($dataIncome->result() as $row){
							$total_income += $row->amount;
							
                            $query = $this->db->query("SELECT
                                sum(erp_gl_trans.amount) AS amount
                            FROM
                                erp_gl_trans
                            WHERE
                                account_code = '" . $row->account_code . "'
								AND erp_gl_trans.tran_date BETWEEN '$from_date' AND '$to_date' ;");
                            $totalBeforeAyearRows = $query->row();
                            $totalBeforeAyear_income += $totalBeforeAyearRows->amount;

							$itotal_amount = 0;
							$incDetails = $this->accounts_model->getBalanceSheetDetailByAccCode($row->account_code, '40,70,10',$from_date,$to_date, json_decode($biller_id));
							
							if($incDetails->num_rows() > 0){
								?>
								<tr>
									<td colspan="<?=$num_col?>" class="text-left"><?php echo $row->account_code;?> - <?php echo $row->accountname;?></td>
								</tr>
								<?php
								foreach($incDetails->result() as $ide) {
									$total_income_array[] = array(
										'biller_id' => $ide->biller_id,
										'amount' => (-1)*$ide->amount
									);
									
									$itotal_amount += (-1)*$ide->amount;
							?>
								<tr>
									<td class="text-center"><div class="fix-text"><?= $ide->tran_type; ?></div></td>		
									<td class="text-center"><div class="fix-text"><?= $this->erp->hrld($ide->tran_date); ?></div></td>		
									<td><div class="fix-text"><?= $ide->reference_no; ?></div></td>		
									<td><div class="fix-text"><?= $ide->customer; ?></div></td>
									<td><div class="fix-text"><?= $this->erp->decode_html(strip_tags($ide->note)); ?></div></td>
									<!--<td><?= ($ide->account_code .' . '. $ide->accountname); ?></td>-->
									
									<?php 
									foreach ($new_billers as $biG) {
										$biG_biller = 0;
										if($this->uri->segment(7)){
											$biG_biller = $biG['id'];
										}else{
											$biG_biller = $biG->id;
										}

										if($biG_biller == $ide->biller_id){
											$sum_income_per_acc[] = array(
												'biller_id' => $ide->biller_id,
												'amount' => (-1)*($ide->amount)
											);
											$ide_display = (-1)*($ide->amount);
											if($ide_display < 0){
												$ide_display = '( ' . $this->erp->formatMoney(abs($ide->amount)) . ' )';
											}else{
												$ide_display = $this->erp->formatMoney(abs($ide->amount));
											}
											echo '<td class="text-right"><div class="fix-text">'. $ide_display .'</div></td>';
										}else{
											echo '<td class="text-right"><div class="fix-text">'. $this->erp->formatMoney(0) .'</div></td>';
										}
									}
									
									$ide_display1 = (-1)*($ide->amount);
									if($ide_display1 < 0){
										$ide_display1 = '( ' . $this->erp->formatMoney(abs($ide->amount)) . ' )';
									}else{
										$ide_display1 = $this->erp->formatMoney(abs($ide->amount));
									}
									?>
									<td class="text-right"><div class="fix-text text-right"><b><?= $ide_display1 ?></b></div></td>
								</tr>
							<?php }
							}else{
								$itotal_amount = $row->amount;
							}
							?>
								<tr>
									<td colspan="<?=$col1-1?>" class="text-left"><b><?php echo lang('total');?></b></td>
									
									<?php 
									foreach ($new_billers as $bi2) {
										$bill_id = 0;
										if($this->uri->segment(7)){
											$bill_id = $bi2['id'];
										}else{
											$bill_id = $bi2->id;
										}
										
										$s_total = 0;
										foreach($sum_income_per_acc as $sac){
											if($bill_id == $sac['biller_id']){
												$s_total += $sac['amount'];
											}
										}
										if($s_total < 0){
											$s_total = '( ' . $this->erp->formatMoney(abs($s_total)) . ' )';
										}else{
											$s_total = $this->erp->formatMoney(abs($s_total));
										}
										echo '<td class="text-right"><b>' . $s_total . '</b></td>';
									}
									$sum_income_per_acc = array();

									$itotal_display = $itotal_amount;
									if($itotal_display < 0){
										$itotal_display = '( ' . $this->erp->formatMoney(abs($itotal_amount)) . ' )';
									}else{
										$itotal_display = $this->erp->formatMoney(abs($itotal_amount));
									}
									?>
									
									<td class="text-right"><div class="fix-text text-right"><b><?= $itotal_display ?><b></div></td>
								</tr>

						<?php } ?>
							<tr>
							<td colspan="<?=$col1-1?>"><b><?= lang("total_income"); ?></b></td>
							
							<?php
							for($i = 0; $i < count($new_billers); $i++){
								$bill_id = 0;
								if($this->uri->segment(7)){
									$bill_id = $new_billers[$i]['id'];
								}else{
									$bill_id = $new_billers[$i]->id;
								}
								$total_amt_inc = 0;
								foreach ($total_income_array as $val) {
									if($bill_id == $val['biller_id']){
										$total_amt_inc += $val['amount'];
									}
								}
								$sum_total_income[] = array(
									'biller_id' => $bill_id,
									'amount' => $total_amt_inc
								);
								/*
								if($total_amt_inc < 0){
									$total_amt_inc = '( '.$this->erp->formatMoney(abs($total_amt_inc)).' )';
								}else{
									$total_amt_inc = $this->erp->formatMoney(abs($total_amt_inc));
								}
								*/
								echo '<td style="font-weight:bold;border-top:2px solid #000"><div class="fix-text text-right">' . $this->erp->formatMoney(abs($total_amt_inc)) . '</div></td>';
							}
							?>
							
							<td style="border-top:2px solid #000"><div class="fix-text text-right"><b><?php echo $this->erp->formatMoney((-1)*$total_income);?></b></div></td>
							</tr>
							<tr class="primary">
								<th style="text-align:left;" colspan="<?=$num_col?>"><?= lang("cost"); ?></th>
							</tr>
							<?php
							$total_cost = 0;
                            $totalBeforeAyear_cost = 0;
							foreach($dataCost->result() as $rowcost){
							$total_cost += $rowcost->amount;

                            $query = $this->db->query("SELECT
                                sum(erp_gl_trans.amount) AS amount
                            FROM
                                erp_gl_trans
                            WHERE
                                account_code = '" . $rowcost->account_code . "'
								AND erp_gl_trans.tran_date BETWEEN '$from_date' AND '$to_date' ;");
                            $totalBeforeAyearRows = $query->row();
                            $totalBeforeAyear_cost += $totalBeforeAyearRows->amount;
						?>
						
						<tr>				
							<td colspan="<?=$num_col?>"><?php echo $rowcost->account_code;?> - <?php echo $rowcost->accountname;?></td>
						</tr>
						
						<?php 
							$ctotal_amount = 0;
							$cost_couple = $this->accounts_model->getBalanceSheetDetailPurByAccCode($rowcost->account_code, '50',$from_date,$to_date,json_decode($biller_id));

							foreach($cost_couple->result() as $cde) {
							$ctotal_amount += ($cde->amount);
							?>
							<tr>
								<td class="text-center"><?= $cde->tran_type; ?></td>		
								<td class="text-center"><?= $this->erp->hrld($cde->tran_date); ?></td>		
								<td><?= $cde->reference_no; ?></td>		
								<td><?= $cde->customer; ?></td>
								<td><?= $this->erp->decode_html(strip_tags($cde->note)); ?></td>
								<!--<td><?= ($cde->account_code .' . '. $cde->accountname); ?></td>-->
								
								<?php 
								$cost_display = $cde->amount;
								if($cost_display < 0) {
									$cost_display = '( ' . $this->erp->formatMoney(abs($cde->amount)) . ' )';
								}else{
									$cost_display = $this->erp->formatMoney(abs($cde->amount));
								}
								
								
								foreach ($new_billers as $bi2) {
									if($this->uri->segment(7)){
										$sum_cost_per_acc[] = array(
											'biller_id' => $cde->biller_id,
											'amount' => $cde->amount
										);
										$total_cost_array[] = array(
											'biller_id' => $cde->biller_id,
											'amount' => $cde->amount
										);
										echo '<td class="text-right"><div class="fix-text">' . $cost_display . '</div></td>';
									}else{
									
									  if($bi2->id == $cde->biller_id){
										$sum_cost_per_acc[] = array(
											'biller_id' => $cde->biller_id,
											'amount' => $cde->amount
										);
										
										$total_cost_array[] = array(
											'biller_id' => $cde->biller_id,
											'amount' => $cde->amount
										);
								?>
										<td class="text-right"><div class="fix-text"><?= $cost_display ?></div></td>
								<?php }else{
										echo '<td class="text-right"><div class="fix-text">'. $this->erp->formatMoney(0) .'</div></td>';
									}
								   }
								}
								?>
								
								<td class="text-right"><div class="fix-text"><b><?= $cost_display; ?></b></div></td>
							</tr>
						
							<?php } 
							$t_cost_display = $ctotal_amount;
							
							if($t_cost_display < 0) {
								$t_cost_display = '( ' . $this->erp->formatMoney(abs($ctotal_amount)) . ' )';
							}else{
								$t_cost_display = $this->erp->formatMoney(abs($ctotal_amount));
							}
							?>
							<tr>				
								<td colspan="<?=$col1-1?>"><b><?php echo lang('total');?></b></td>
								
								<?php 
								foreach ($new_billers as $bi2) {
									$bill_id = 0;
									if($this->uri->segment(7)){
										$bill_id = $bi2['id'];
									}else{
										$bill_id = $bi2->id;
									}
									
									$s_total = 0;
									foreach($sum_cost_per_acc as $sac){
										if($bill_id == $sac['biller_id']){
											$s_total += $sac['amount'];
										}
									}
									if($s_total < 0){
										$s_total = '( ' . $this->erp->formatMoney(abs($s_total)) . ' )';
									}else{
										$s_total = $this->erp->formatMoney(abs($s_total));
									}
									echo '<td class="text-right"><b>' . $s_total . '</b></td>';
								}
								$sum_cost_per_acc = array();
								?>
								
								<td class="text-right"><div class="fix-text"><b><?= $t_cost_display ?></b></div></td>
							</tr>
						<?php
							}
						?>
							<tr>
							<td colspan="<?=$col1-1?>"><b><?= lang("total_cost"); ?></b></td>
							
								<?php
									for($in = 0; $in < count($new_billers); $in++){
										$in_bill_id = 0;
										if($this->uri->segment(7)){
											$in_bill_id = $new_billers[$in]['id'];
										}else{
											$in_bill_id = $new_billers[$in]->id;
										}
										$total_amt_cost = 0;
										foreach ($total_cost_array as $val) {
											if($in_bill_id == $val['biller_id']){
												$total_amt_cost += $val['amount'];
											}
										}
										
										$sum_total_cost[] = array(
											'biller_id' => $in_bill_id,
											'amount' => $total_amt_cost
										);
										
										if($total_amt_cost < 0){
											$total_amt_cost = '( '.$this->erp->formatMoney(abs($total_amt_cost)).' )';
										}else{
											$total_amt_cost = $this->erp->formatMoney(abs($total_amt_cost));
										}
										echo '<td style="font-weight:bold;border-top:2px solid #000"><div class="fix-text text-right">' . $total_amt_cost . '</div></td>';
									}
									$total_cost_display = '';
									if($total_cost < 0){
										$total_cost_display = '( '.$this->erp->formatMoney(abs($total_cost)).' )';
									}else{
										$total_cost_display = $this->erp->formatMoney(abs($total_cost));
									}
									?>
									
									<td style="font-weight:bold;border-top:2px solid #000;border-left:2px solid #000;"><div class="fix-text text-right"><?php echo $total_cost_display; ?></div></td>
							<!--
							<td><div class="fix-text">						
							<?php 
								echo $this->erp->formatMoney(abs($total_cost));
							?>
							<?php //echo $this->erp->formatMoney((-1)*$total_cost);?>
							</div></td>
							-->
							
							</tr>
							<tr>
								<td colspan="<?=$col1-1?>"><b><?= lang("gross_margin"); ?></b></td>
								
								<?php 
								for($i =0; $i < count($sum_total_income); $i++){
									$amount_per_gross =0;
									$amount_per_inc = 0;
									$amount_per_cost = 0;
									
									$amount_per_inc = $sum_total_income[$i]['amount'];
									$amount_per_cost = $sum_total_cost[$i]['amount'];
									
									$amount_per_gross = $amount_per_inc - $amount_per_cost;
									
									$sum_total_gross[] = array(
										'biller_id' => $sum_total_cost[$i]['biller_id'],
										'amount' => $amount_per_gross
									);
									
									echo '<td style="font-weight:bold;"><div class="fix-text text-right">' . $this->erp->formatMoney($amount_per_gross) . '</div></td>';
								}
								?>
								
								<td><div class="fix-text text-right"><b>
									<?php 
									if((-1)*$total_income - $total_cost < 0){
										echo "(".$this->erp->formatMoney(abs((-1)*$total_income - $total_cost)).")";
									}else{
										echo $this->erp->formatMoney(abs((-1)*$total_income - $total_cost));
									}
									?>
									
								</b></div></td>
							</tr>
							<tr class="primary">
								<th style="text-align:left;" colspan="<?=$num_col?>"><?= lang("operating_expense"); ?></th>
							</tr>
							
							<?php
							$total_expense = 0;
                            $totalBeforeAyear_expense = 0;
							foreach($dataExpense->result() as $row){
							$total_expense += $row->amount;

                            $query = $this->db->query("SELECT
                                SUM(erp_gl_trans.amount) AS amount
                            FROM
                                erp_gl_trans
                            WHERE
								account_code = '" . $row->account_code . "'
								AND erp_gl_trans.tran_date BETWEEN '$from_date' AND '$to_date' ;");
                            $totalBeforeAyearRows = $query->row();
                            $totalBeforeAyear_expense += $totalBeforeAyearRows->amount;
							
							$total_op_per = 0;
							?>
							<tr>
									<td colspan="<?=$col1?>" class="text-left"><?php echo $row->account_code;?> - <?php echo $row->accountname;?></td>
									<!--<td class="text-right"><div class="fix-text"><?= $total_op_per ?></div></td>-->
								</tr>
								<?php
							$ex_details = $this->accounts_model->getBalanceSheetDetailByAccCode($row->account_code, '60,80,90',$from_date,$to_date, json_decode($biller_id));
							if($ex_details->num_rows() > 0){
								foreach($ex_details->result() as $ex) {
								$total_op_per += $ex->amount;
								$ex_amount = 0;
								$ex_amount = $ex->amount;
								if($ex_amount < 0){
									$ex_amount = '( ' . $this->erp->formatMoney(abs($ex->amount)) . ' )';
								}else{
									$ex_amount = $this->erp->formatMoney(abs($ex->amount));
								}
							?>
							
								<tr>
									<td class="text-center"><div class="fix-text"><?= $ex->tran_type; ?></div></td>		
									<td class="text-center"><div class="fix-text"><?= $this->erp->hrld($ex->tran_date); ?></div></td>		
									<td><div class="fix-text"><?= $ex->reference_no; ?></div></td>		
									<td><div class="fix-text"><?= $ex->customer; ?></div></td>
									<td><div class="fix-text"><?= $this->erp->decode_html(strip_tags($ex->note)); ?></div></td>
									<!--<td><?= ($ex->account_code .' . '. $ex->accountname); ?></td>-->
									<?php foreach ($billers as $bi2) {
										if($this->uri->segment(7)){
											if($bi2->id == $this->uri->segment(7)){
												$total_op_array[] = array(
													'biller_id' => $ex->biller_id,
													'amount' => $ex->amount
												);
												
												$sum_op_per_acc[] = array(
													'biller_id' => $ex->biller_id,
													'amount' => $ex->amount
												);
												
												echo '<td class="text-right"><div class="fix-text">' . $ex_amount . '</div></td>';
											}
										}else{
										
										if($bi2->id == $ex->biller_id){
											$sum_op_per_acc[] = array(
												'biller_id' => $ex->biller_id,
												'amount' => $ex->amount
											);
											
											$total_op_array[] = array(
												'biller_id' => $ex->biller_id,
												'amount' => $ex->amount
											);
									?>
										<td class="text-right"><div class="fix-text"><?= $ex_amount ?></div></td>
									<?php }else{
											echo '<td class="text-right"><div class="fix-text">'. $this->erp->formatMoney(0) .'</div></td>';
										}
									   }
									}
									?>
									<td><div class="fix-text text-right"><b><?= $ex_amount ?></b></div></td>
								</tr>
								
								
								
							<?php }
							}else{
								$total_op_per = $row->amount;
								
							}
							if($total_op_per < 0){
								$total_op_per = '( ' . $this->erp->formatMoney(abs($total_op_per)) . ' )';
							}else{
								$total_op_per = $this->erp->formatMoney(abs($total_op_per));
							}
							?>
							
							<tr>				
								<td colspan="<?=$col1-1?>"><b><?php echo lang('total');?></b></td>
								
								<?php 
								foreach ($new_billers as $bi2) {
									$bill_id = 0;
									if($this->uri->segment(7)){
										$bill_id = $bi2['id'];
									}else{
										$bill_id = $bi2->id;
									}
									
									$s_total1 = 0;
									foreach($sum_op_per_acc as $sac){
										if($bill_id == $sac['biller_id']){
											$s_total1 += $sac['amount'];
										}
									}
									if($s_total1 < 0){
										$s_total1 = '( ' . $this->erp->formatMoney(abs($s_total1)) . ' )';
									}else{
										$s_total1 = $this->erp->formatMoney(abs($s_total1));
									}
									echo '<td class="text-right"><b>' . $s_total1 . '</b></td>';
								}
								$sum_op_per_acc = array();
								?>
								
								<td class="text-right"><div class="fix-text"><b><?= $total_op_per ?></b></div></td>
							</tr>
								
						<?php
							}
							$total_expense_display = '';
							if($total_expense < 0){
								$total_expense_display = '( '.$this->erp->formatMoney(abs($total_expense)).' )';
							}else{
								$total_expense_display = $this->erp->formatMoney(abs($total_expense));
							}
						?>							
							<tr>
							
							<td colspan="<?=$col1-1?>"><?= lang("total_expense"); ?></td>
							
							<?php
							for($i = 0; $i < count($new_billers); $i++){
								$bill_id = 0;
								if($this->uri->segment(7)){
									$bill_id = $new_billers[$i]['id'];
								}else{
									$bill_id = $new_billers[$i]->id;
								}
								$total_amt_op = 0;
								foreach ($total_op_array as $val) {
									if($bill_id == $val['biller_id']){
										$total_amt_op += $val['amount'];
									}
								}
								
								$sum_total_op[] = array(
									'biller_id' => $bill_id,
									'amount' => $total_amt_op
								);
								
								if($total_amt_op < 0){
									$total_amt_op = '( '.$this->erp->formatMoney(abs($total_amt_op)).' )';
								}else{
									$total_amt_op = $this->erp->formatMoney(abs($total_amt_op));
								}
								echo '<td style="border-top:2px solid #000;font-weight:bold"><div class="fix-text text-right">' . $total_amt_op . '</div></td>';
							}
							$total_expense_display = '';
							if($total_expense < 0){
								$total_expense_display = '( '.$this->erp->formatMoney(abs($total_expense)).' )';
							}else{
								$total_expense_display = $this->erp->formatMoney(abs($total_expense));
							}
							?>
							<td style="border-top:2px solid #000;border-left:2px solid #000;font-weight:bold"><div class="fix-text text-right"><?php echo $total_expense_display;?></div></td>
							
							<!-- <td><div class="fix-text"><?php echo $total_expense_display;?></div></td> -->
							</tr>
							<tr class="active">                            
								<th colspan="<?=$col1-1?>"><?= lang("profits"); ?></th>
								
								<?php
								for($i = 0; $i < count($sum_total_gross); $i++){
									$per_gross = 0;
									$per_exp = 0;
									
									$per_gross = $sum_total_gross[$i]['amount'];
									$per_exp = $sum_total_op[$i]['amount'];
									
									$total_per_op_ex = ($per_gross - $per_exp);

									if($total_per_op_ex < 0){
										$total_per_op_ex = '( '.$this->erp->formatMoney(abs($total_per_op_ex)).' )';
									}else{
										$total_per_op_ex = $this->erp->formatMoney(abs($total_per_op_ex));
									}
									echo '<th><div class="fix-text text-right">' . $total_per_op_ex . '</div></th>';
								}
								?>
								
								<?php 
								$total_profit_per = ((-1)*$total_income - $total_cost)-$total_expense;
								$total_profit_loss_display = '';
								if($total_profit_per < 0){
									$total_profit_loss_display = '( '.$this->erp->formatMoney(abs($total_profit_per)).' )';
								}else{
									$total_profit_loss_display = $this->erp->formatMoney(abs($total_profit_per));
								}
								?>
								
								<th><div class="fix-text text-right"><?php echo $total_profit_loss_display;?></div></th>								
							</tr>
                        </tbody>
                    </table>
                </div>
				
				
            </div>
        </div>
    </div>
</div>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?php echo form_close(); ?>
<?php } ?>
<style type="text/css">
	table{ 
		white-space: nowrap; 
		overflow-x: scroll; 
		display:block;
		width:100%;
	}
</style>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
		
		$("#biller-filter").on('click', function(event){
			event.preventDefault();
			var hasCheck = false;
			biller_ids = '';
			$.each($("input[name='biller_checkbox[]']:checked"), function(){
				hasCheck = true;
				biller_ids += $(this).val() + '-';
			});
			var billers = removeSymbolLastString(biller_ids, '-');
			if(hasCheck == true){
				var encodedName = encodeURIComponent(billers);
				var url = "<?php echo site_url('reports/income_statement_detail/'.$start.'/'.$end.'/0/0') ?>" + '/' + encodeURIComponent(billers);
				window.location.href = "<?=site_url('reports/income_statement_detail/'. $start .'/'.$end.'/0/0/')?>" + '/' + encodedName;
			}
			
			if(hasCheck == false){
				bootbox.alert('Please select project first!');
				return false;
			}
			return false;
		});
		
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/income_statement_detail/'. $start .'/'.$end.'/pdf/0/'.$biller_id)?>";
            return false;
        });
		
		$('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/income_statement_detail/'. $start .'/'.$end.'/0/xls/'.$biller_id)?>";
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
	function removeSymbolLastString(string, symbol = ','){
		var strVal = $.trim(string);
		var lastChar = strVal.slice(-1);
		if (lastChar == symbol) {
			strVal = strVal.slice(0, -1);
		}
		return strVal;
	}
</script>