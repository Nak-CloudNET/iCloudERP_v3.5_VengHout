<script>$(document).ready(function () {
        CURI = '<?= site_url('reports/balance_sheet_details'); ?>';	
	});
	</script>
<style>
@media print {
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
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-bars"></i><?= lang('balance_sheet_details'); ?> >> <?= (isset($start)?$start:""); ?> >> <?= (isset($end)?$end:""); ?></h2>
        <div class="box-icon">
            <div class="form-group choose-date hidden-xs">
                <div class="controls">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text"
                               value="<?= ($start ? $this->erp->hrld($start) : '') . ' - ' . ($end ? $this->erp->hrld($end) : ''); ?>"
                               id="daterange" class="form-control">
                        <span class="input-group-addon"><i class="fa fa-chevron-down"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>"><i class="icon fa fa-file-pdf-o"></i></a></li>
				<li class="dropdown"><a href="#" id="xls" class="tip" title="<?= lang('download_xls') ?>"><i class="icon fa fa-file-excel-o"></i></a></li>
                <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                            class="icon fa fa-file-picture-o"></i></a></li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
							class="icon fa fa-building-o tip" data-placement="left"
							title="<?= lang("projects") ?>"></i></a>
					<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
						aria-labelledby="dLabel">
						<li><a href="<?= site_url('reports/balance_sheet_details') ?>"><i
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
							//echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/balance_sheet/'.$start.'/'.$end.'/0/0/' . $biller->id) . '"><input type="checkbox" class="checkbox biller_checkbox" value="'. $biller->id .'" >&nbsp;&nbsp;' . $biller->company . '</a></li>';
							
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
                    <table id="SupData" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped table-condensed">
						<thead>
							<tr >                           
								<th><div class="fix-text"><?= lang('type') ?></div></th>
								<th><div class="fix-text"><?= lang('date') ?></div></th>
								<th><div class="fix-text"><?= lang('inv_reference') ?></div></th>
								<th><div class="fix-text"><?= lang('name') ?></div></th>
								<th><div class="fix-text"><?= lang('description') ?></div></th>
								
								<?php
								$new_billers = array();
								foreach ($billers as $b1) {
									if($this->uri->segment(7)){
										$biller_sep = explode('-', $this->uri->segment(7));
										for($i=0; $i < count($biller_sep); $i++){
											if($biller_sep[$i] == $b1->id){
												echo '<th>' . $b1->company . '</th>';
												$new_billers[] = array('id' => $b1->id);
											}
										}
									}else{
										$new_billers = $billers;
										echo '<th><div class="fix-text">' . $b1->company . '</div></th>';
									}
									$num_col++;
								}
								?>
								<th><div class="fix-text" style="width:150px;"><?= lang("total_amount") ?></div></th>
							</tr>
                        </thead>

						<tbody>
							<tr class="primary">                            
								<th style="text-align:left;" colspan="<?= $num_col ?>">
									<div class="fix-text"><?= lang("asset"); ?></div>
								</th>							
							</tr>
					<?php
						$total_asset = 0;
						$totalBeforeAyear_asset = 0;
						$colbot = 0;
						if($this->uri->segment(7)){
								$col1 = 5;
								$colbot = count($new_billers) + 5;
						}else{
							$colcount = count($new_billers);							
							$col1 = 5;
							$colbot = $colcount + 4;
						}
							$total_asset_arr = array();
							$total_lib_arr = array();
							$total_eq_arr = array();
							$sum_asset_arr = array();
							$sum_lib_arr = array();
							$sum_eq_arr = array();
							$total_asset_per_acc = array();
							$total_lib_per_acc = array();
							$total_eq_per_acc = array();
							
							$sum_asset_per_acc = array();
							$sum_lib_per_acc = array();
							$sum_eq_per_acc = array();
							
							
							foreach($dataAsset->result() as $row){
								//$total_asset += $row->amount;
								
								$assetDetails = $this->accounts_model->getBalanceSheetDetailByAccCode($row->account_code, '10,11',$from_date,$end_dates,json_decode($biller_id));
						
								$index = 0;
								$total_per_asset = 0;
								
								$total_amt = 0;
								
								echo '<tr>';
								echo '<td colspan="'.($colbot + 2).'">' . $row->account_code .' - ' .  $row->accountname . '</td>';
								echo '</tr>';
								
								foreach($assetDetails->result() as $asD){
									$s_desc = $this->erp->decode_html(strip_tags($asD->note));
									echo '</tr>';
									echo '<td class="text-center"><div class="fix-text">'. $asD->tran_type .'</div></td>';
									echo '<td><div class="fix-text">'. $this->erp->hrld($asD->tran_date) .'</div></td>';
									echo '<td><div class="fix-text">'. $asD->reference_no .'</div></td>';
									echo '<td><div class="fix-text">'. $asD->customer .'</div></td>';
									echo '<td class="col-md-2"><div class="fix-text">'. $this->erp->limit_words($s_desc, 20) .'</div></td>';
									?>
									
									<?php 
									$bi2_inc = 0;
									foreach ($new_billers as $bi2) {
										$bi2_biller = 0;
										if($this->uri->segment(7)){
											$bi2_biller = $bi2['id'];
										}else{
											$bi2_biller = $bi2->id;
										}
										
										$b_amt = '';
										if($asD->amount >0){
											$b_amt = $this->erp->formatMoney(abs($asD->amount));
										}else{
											$b_amt = '( ' . $this->erp->formatMoney(abs($asD->amount)) . ' )';
										}
										if($bi2_biller == $asD->biller_id){
											$total_asset_per_acc[] = array(
												'biller_id' => $bi2_biller,
												'amount' => $asD->amount
											);
											$sum_asset_per_acc[] = array(
												'biller_id' => $bi2_biller,
												'amount' => $asD->amount
											);
											
											$total_asset_arr[] = array(
												'biller_id' => $bi2_biller,
												'amount' => $asD->amount
											);
									
											echo '<td class="text-right"><div class="fix-text">' . $b_amt . '</div></td>';
										}else{
											echo '<td class="text-right"><div class="fix-text">' . $this->erp->formatMoney(0) . '</div></td>';
										}
									}
									?>
									<td class="text-right"><div class="fix-text"><b><?= $b_amt?></b></div></td>
									<?php
									echo '</tr>';
									
									
									$total_asset += $asD->amount;
								}
								
								
								
								if($total_amt >0){
									$total_amt = $this->erp->formatMoney(abs($total_amt));
								}else{
									$total_amt = '( ' . $this->erp->formatMoney(abs($total_amt)) . ' )';
								}
								
								echo '<tr>';
								echo '<td colspan="'.$col1.'"><div class="fix-text"><b>'. lang('total') . '</b></div></td>';
								
								foreach ($new_billers as $bi2) {
									$bill_id = 0;
									if($this->uri->segment(7)){
										$bill_id = $bi2['id'];
									}else{
										$bill_id = $bi2->id;
									}
									
									$s_total = 0;
									foreach($sum_asset_per_acc as $sac){
										if($bill_id == $sac['biller_id']){
											$s_total += $sac['amount'];
										}
									}
									$total_amt += $s_total;
									if($s_total < 0){
										$s_total = '( ' . $this->erp->formatMoney(abs($s_total)) . ' )';
									}else{
										$s_total = $this->erp->formatMoney(abs($s_total));
									}
									echo '<td class="text-right"><b>' . $s_total . '</b></td>';
									
								}
								$sum_asset_per_acc = array();
								
								if($total_amt < 0){
									$total_amt = '( ' . $this->erp->formatMoney(abs($total_amt)) . ' )';
								}else{
									$total_amt = $this->erp->formatMoney(abs($total_amt));
								}
																
								echo '<td><div class="fix-text text-right"><b>' . $total_amt . '</b></div></td>';
								echo '</tr>';
							}
						?>
							<tr>
								<td colspan="<?=$col1?>">
									<b><?= lang("total_asset"); ?></b>
								</td>
								
								<?php
								for($c= 0; $c < count($new_billers); $c++){
									$in_bill_id1 = 0;
									if($this->uri->segment(7)){
										$in_bill_id1 = $new_billers[$c]['id'];
									}else{
										$in_bill_id1 = $new_billers[$c]->id;
									}
									$total_asset_amt = 0;
									foreach($total_asset_arr as $new_arr){
										if($new_arr['biller_id'] == $in_bill_id1){
											$total_asset_amt += $new_arr['amount'];
										}
									}
									$sum_asset_arr[] = array(
										'biller_id' => $in_bill_id1,
										'amount' => $total_asset_amt
									);
									if($total_asset_amt<0){
										$total_asset_amt = '( '.$this->erp->formatMoney(abs($total_asset_amt)).' )';
									}else{
										$total_asset_amt = $this->erp->formatMoney(abs($total_asset_amt));
									}
									echo '<td><div class="fix-text text-right"><b>'. $total_asset_amt .'</b></div></td>';
								}
								$total_asset_display = '';
								if($total_asset<0){
									$total_asset_display = '( '.$this->erp->formatMoney(abs($total_asset)).' )';
								}else{
									$total_asset_display = $this->erp->formatMoney(abs($total_asset));
								}
								?>
								<td><div class="fix-text text-right"><b><?php echo $total_asset_display;?></b></div></td>
							</tr>
							<!--- liabilities -->
							<tr class="primary">
								<th style="text-align:left;" colspan="<?= $num_col ?>">
									<?= lang("liabilities"); ?>
								</th>
							</tr>
							<?php
							$total_liability = 0;
							$totalBeforeAyear_liability = 0;
							foreach($dataLiability->result() as $rowlia){
								//$total_liability += $rowlia->amount;
								$libDetails = $this->accounts_model->getBalanceSheetDetailPurByAccCode($rowlia->account_code, '20,21',$from_date,$end_dates,json_decode($biller_id));
								
								$index = 0;
								$total_per_lib = 0;
								
								$total_amt = 0;
								
								echo '<tr>';
								echo '<td colspan="'.($colbot+2).'">' . $rowlia->account_code .' - ' .  $rowlia->accountname . '</td>';
								echo '</tr>';
								
								foreach($libDetails->result() as $libD){
									echo '</tr>';
									echo '<td class="text-center"><div class="fix-text">'. $libD->tran_type .'</div></td>';
									echo '<td><div class="fix-text">'. $this->erp->hrld($libD->tran_date) .'</div></td>';
									echo '<td><div class="fix-text">'. $libD->reference_no .'</div></td>';
									echo '<td>'. $libD->customer .'</td>';
									echo '<td><div class="fix-text">'. $this->erp->decode_html(strip_tags($libD->note)) .'</div></td>';
									
									$lib_amt_dis_in = '';
									if((-1)*$libD->amount < 0){
										$lib_amt_dis_in = '( ' . $this->erp->formatMoney(abs($libD->amount)) . ' )';
									}else{
										$lib_amt_dis_in = $this->erp->formatMoney(abs($libD->amount));
									}
									?>
									
									<?php foreach ($new_billers as $bi2) {
										$bi2_biller = 0;
										if($this->uri->segment(7)){
											$bi2_biller = $bi2['id'];
										}else{
											$bi2_biller = $bi2->id;
										}
										if($bi2_biller == $libD->biller_id){
											
											$sum_lib_per_acc[] = array(
												'biller_id' => $bi2_biller,
												'amount' => ($libD->amount)
											);
											
											echo '<td><div class="fix-text text-right">' . $lib_amt_dis_in . '</div></td>';
										}else{
											echo '<td><div class="fix-text text-right">' . $this->erp->formatMoney(0) . '</div></td>';
										}
									}
									
									
									?>
									<td><div class="fix-text text-right"><b><?= $lib_amt_dis_in ?></b></div></td>
									<?php
									echo '</tr>';
									

									$total_lib_arr[] = array(
										'biller_id' => $libD->biller_id,
										'amount' => (-1)*$libD->amount
									);
									$total_liability += (-1)*$libD->amount;
								}

								/*
								if($total_amt < 0){
									$total_amt = '( ' . $this->erp->formatMoney(abs($total_amt)) . ' )';
								}else{
									$total_amt = $this->erp->formatMoney(abs($total_amt));
								}
								*/
								
								echo '<tr>';
								echo '<td colspan="'.$col1.'"><div class="fix-text"><b>' . lang('total') . '</b></div></td>';
								
								foreach ($new_billers as $bi2) {
									$bill_id = 0;
									if($this->uri->segment(7)){
										$bill_id = $bi2['id'];
									}else{
										$bill_id = $bi2->id;
									}
									
									$s_total = 0;
									foreach($sum_lib_per_acc as $sac){
										if($bill_id == $sac['biller_id']){
											$s_total += $sac['amount'];
										}
									}
									
									$total_amt += (-1)*$s_total;
									
									if((-1)*$s_total < 0){
										$s_total = '( ' . $this->erp->formatMoney(abs($s_total)) . ' )';
									}else{
										$s_total = $this->erp->formatMoney(abs($s_total));
									}
									
									echo '<td class="text-right"><b>' . $s_total . '</b></td>';
									
								}
								$sum_lib_per_acc = array();
								
								echo '<td colspan="2"><div class="fix-text text-right"><b>' . $this->erp->formatMoney(abs($total_amt)) . '</b></div></td>';
								echo '</tr>';
							}
							$end_total_lib = $total_liability;
						?>
							<tr>
								<td colspan="<?=$col1?>"><b><?= lang("total_liabilities"); ?></b></td>
								<?php
								for($c= 0; $c < count($new_billers); $c++){
									$in_bill_id1 = 0;
									if($this->uri->segment(7)){
										$in_bill_id1 = $new_billers[$c]['id'];
									}else{
										$in_bill_id1 = $new_billers[$c]->id;
									}
									$total_lib_amt = 0;
									foreach($total_lib_arr as $new_arr){
										if($new_arr['biller_id'] == $in_bill_id1){
											$total_lib_amt += $new_arr['amount'];
										}
									}
									$sum_lib_arr[] = array(
										'biller_id' => $in_bill_id1,
										'amount' => $total_lib_amt
									);
									/*
									if($total_lib_amt<0){
										$total_lib_amt = '( '.$this->erp->formatMoney(abs($total_lib_amt)).' )';
									}else{
										$total_lib_amt = $this->erp->formatMoney(abs($total_lib_amt));
									}
									*/
									
									if($total_lib_amt < 0){
										echo '<td><div class="fix-text text-right"><b>( ' . $this->erp->formatMoney(abs($total_lib_amt)) .' )</b></div></td>';
									}else{
										echo '<td><div class="fix-text text-right"><b>'. $this->erp->formatMoney($total_lib_amt) .'</b></div></td>';
									}
									
									//echo '<td><div class="fix-text text-right"><b>'. $this->erp->formatMoney(abs($total_lib_amt)) .'</b></div></td>';
								}
								
								$end_total_lib = $total_liability;
								/*
								$total_lib_display = '';
								if($total_liability<0){
									$total_lib_display = '( '.$this->erp->formatMoney(abs($total_liability)).' )';
								}else{
									$total_lib_display = $this->erp->formatMoney(abs($total_liability));
								}
								*/
								?>
								<td><div class="fix-text text-right"><b><?php echo $this->erp->formatMoney(abs($total_liability));?></b></div></td>								
							</tr>
							<!--- equities --->
							<tr class="primary">                            
								<th style="text-align:left;" colspan="<?= $num_col ?>">
									<?= lang("equities"); ?>
								</th>                       
							</tr>
							<?php
								
								$total_income = 0;
								$total_expense = 0;
								$total_retained = 0;
								$total_income_beforeAyear = 0;
								$total_expense_beforeAyear = 0;
								$total_retained_beforeAyear = 0;
								$queryIncom = $this->db->query("SELECT sum(erp_gl_trans.amount) AS amount FROM
															erp_gl_trans
														INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
														WHERE DATE(tran_date) = '$totalBeforeAyear' AND	erp_gl_trans.sectionid IN ('40,70') GROUP BY erp_gl_trans.account_code;");
								$total_income_beforeAyear = $queryIncom->amount;

								$queryExpense = $this->db->query("SELECT sum(erp_gl_trans.amount) AS amount FROM
															erp_gl_trans
														INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
														WHERE DATE(tran_date) = '$totalBeforeAyear' AND	erp_gl_trans.sectionid IN ('50,60,80,90') GROUP BY erp_gl_trans.account_code;");
								$total_expense_beforeAyear = $queryExpense->amount;

								$total_retained_beforeAyear = abs($total_income_beforeAyear)-abs($total_expense_beforeAyear);

								$retained_inc_arr = array();
								$retained_exp_arr = array();
								foreach($dataIncome->result() as $rowincome){
									$total_income += $rowincome->amount;
								}
								foreach($dataAllIncome->result() as $rowallinc){
									$retained_inc_arr[] = array(
										'biller_id' => $rowallinc->biller_id,
										'amount' => $rowallinc->amount
									);
								}
								
								foreach($dataExpense->result() as $rowexpense){
									$total_expense += $rowexpense->amount;
								}
								foreach($dataAllExpense->result() as $rowallexp){
									$retained_exp_arr[] = array(
										'biller_id' => $rowallexp->biller_id,
										'amount' => $rowallexp->amount
									);
								}
								$total_retained = $total_income + $total_expense;
								$total_retained = (-1)*$total_retained;
							?>
							<?php
									$retained = $this->db->get("account_settings")->row();
								?>
							<tr>
							<td colspan="<?=$col1?>"><?= $retained->default_retained_earnings ?> - Retained Earnings</td>
							<?php
							$total_retained_arr = array();
							for($c= 0; $c < count($new_billers); $c++){
								$in_bill_id1 = 0;
								if($this->uri->segment(7)){
									$in_bill_id1 = $new_billers[$c]['id'];
								}else{
									$in_bill_id1 = $new_billers[$c]->id;
								}
								$total_per_retained = 0;
								
								$k = 0;
								$r_inc_per = 0;
								$r_exp_per = 0;
								
								if(count($dataIncome->result()) > 0) {
									foreach($dataIncome->result() as $rowincome){
										if($rowincome->biller_id == $in_bill_id1) {
											$r_inc_per += $rowincome->amount;
										}
									}
								}
								
								if(count($dataExpense->result()) > 0) {
									foreach($dataExpense->result() as $rowexpense){
										if($rowexpense->biller_id == $in_bill_id1) {
											$r_exp_per += $rowexpense->amount;
										}
									}
								}
								
								$total_per_retained = $r_exp_per + $r_inc_per;
								$total_per_retained = (-1)*$total_per_retained;
								$total_retained_arr[] = array(
									'biller_id' => $in_bill_id1,
									'amount' => $total_per_retained
								);
								if($total_per_retained<0){
									$total_per_retained = '( '.$this->erp->formatMoney(abs($total_per_retained)).' )';
								}else{
									$total_per_retained = $this->erp->formatMoney(abs($total_per_retained));
								}
								echo '<td><div class="fix-text text-right">'. $total_per_retained .'</div></td>';
							}
							?>
							<?php if($total_retained<0) { ?>						
								<td><div class="fix-text text-right"><?php echo '( ' . $this->erp->formatMoney(abs($total_retained)) . ' )';?></div></td>
							<?php } else { ?>
								<td><div class="fix-text text-right"><?php echo $this->erp->formatMoney(abs($total_retained));?></div></td>
							<?php }	?>							
								</tr>
							<?php
							$total_equity = 0;
							$totalBeforeAyear_equity = 0;
							foreach($dataEquity->result() as $rowequity){
								$total_equity += $rowequity->amount;
								
								$eqDetails = $this->accounts_model->getBalanceSheetDetailByAccCode($rowequity->account_code, '30',$from_date,$end_dates,json_decode($biller_id));
								
								$index = 0;
								$total_per_lib = 0;
								
								$total_amt_eq = 0;
								
								echo '<tr>';
								echo '<td colspan="'.($colbot+2).'">' . $rowequity->account_code .' - ' .  $rowequity->accountname . '</td>';
								echo '</tr>';
								
								foreach($eqDetails->result() as $eqD){
									echo '</tr>';
									echo '<td class="text-center"><div class="fix-text">'. $eqD->tran_type .'</div></td>';
									echo '<td><div class="fix-text">'. $this->erp->hrld($eqD->tran_date) .'</div></td>';
									echo '<td><div class="fix-text">'. $eqD->reference_no .'</div></td>';
									echo '<td><div class="fix-text">'. $eqD->customer .'</div></td>';
									echo '<td><div class="fix-text">'. $this->erp->decode_html(strip_tags($eqD->note)) .'</div></td>';
									?>
									
									<?php foreach ($new_billers as $bi2) {
										$bi2_biller = 0;
										if($this->uri->segment(7)){
											$bi2_biller = $bi2['id'];
										}else{
											$bi2_biller = $bi2->id;
										}
										
										if($bi2_biller == $eqD->biller_id){
											
											$sum_eq_per_acc[] = array(
												'biller_id' => $eqD->biller_id,
												'amount' => (-1)*$eqD->amount
											);
											$eq_display = 0;
											if((-1)*$eqD->amount < 0){
												$eq_display = '( ' . $this->erp->formatMoney(abs($eqD->amount)) . ' )';
											}else{
												$eq_display = $this->erp->formatMoney(abs($eqD->amount));
											}
											
											echo '<td><div class="fix-text text-right">'. $eq_display .'</div></td>';
										}else{
											echo '<td><div class="fix-text text-right">'. $this->erp->formatMoney(0) .'</div></td>';
										}
									}
									$eq_display1 = 0;
									if((-1)*$eqD->amount < 0){
										$eq_display1 = '( ' . $this->erp->formatMoney(abs($eqD->amount)) . ' )';
									}else{
										$eq_display1 = $this->erp->formatMoney(abs($eqD->amount));
									}
									?>
									<td><div class="fix-text text-right"><b><?= $eq_display1 ?></b></div></td>
									<?php
									echo '</tr>';
									$total_amt_eq += (-1)*$eqD->amount;
									
									$total_eq_arr[] = array(
										'biller_id' => $eqD->biller_id,
										'amount' => (-1)*$eqD->amount
									);
								}
								
								echo '<tr>';
								echo '<td colspan="'.$col1.'"><div class="fix-text"><b>' . lang('total') . '</b></div></td>';
								
								foreach ($new_billers as $bi2) {
									$bill_id = 0;
									if($this->uri->segment(7)){
										$bill_id = $bi2['id'];
									}else{
										$bill_id = $bi2->id;
									}
									
									$s_total = 0;
									foreach($sum_eq_per_acc as $sac){
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
								$sum_eq_per_acc = array();
								echo '<td colspan="2"><div class="fix-text text-right"><b>' . $this->erp->formatMoney(abs($total_amt_eq)) . '</b></div></td>';
								echo '</tr>';
								
							}
							$total_eq_sum = ($total_retained - $total_equity);
							//$total_eq_sum = (-1)*($total_equity - $total_retained);
							
							$end_total_eq = $total_eq_sum;

							if($total_eq_sum <0){
								$total_eq_sum = '( ' . $this->erp->formatMoney(abs($total_equity-$total_retained)) . ' )';
							}else{
								$total_eq_sum = $this->erp->formatMoney(abs($total_equity-$total_retained));
							}
						?>							
							<tr>
							<td colspan="<?=$col1?>"><b><?= lang("total_equities"); ?></b></td>
							
							<?php
							// $this->erp->print_arrays($total_eq_arr, $total_retained_arr);
							for($c= 0; $c < count($new_billers); $c++){
								$in_bill_id1 = 0;
								if($this->uri->segment(7)){
									$in_bill_id1 = $new_billers[$c]['id'];
								}else{
									$in_bill_id1 = $new_billers[$c]->id;
								}
								$total_eq_amt = 0;
								$k = 0;
								$count_eq = 0;
								//if(count($total_eq_arr) == 0){
									foreach($total_retained_arr as $new_arr){
										if($new_arr['biller_id'] == $in_bill_id1){
											$total_eq_amt += $total_retained_arr[$k]['amount'] ;
										}
										$k++;
									}
								//}
								foreach($total_eq_arr as $new_arr){
									if($new_arr['biller_id'] == $in_bill_id1){
										if($total_retained_arr[$k]['biller_id'] == $in_bill_id1){
											$total_eq_amt += abs($new_arr['amount']) + $total_retained_arr[$k]['amount'] ;
										}else{
											$total_eq_amt += ($new_arr['amount']);
										}
									}
									$k++;
								}
								
								$sum_eq_arr[] = array(
									'biller_id' => $in_bill_id1,
									'amount' => $total_eq_amt
								);
								
								if($total_eq_amt<0){
									$total_eq_amt = '( '.$this->erp->formatMoney(abs($total_eq_amt)).' )';
								}else{
									$total_eq_amt = $this->erp->formatMoney(abs($total_eq_amt));
								}
								
								echo '<td><div class="fix-text text-right"><b>'. $total_eq_amt .'</b></div></td>';
							}
							$total_eq_sum = (-1)*$total_equity + $total_retained;
							
							$end_total_eq = $total_eq_sum;

							if($total_eq_sum <0){
								$total_eq_sum = '( ' . $this->erp->formatMoney(abs($total_eq_sum)) . ' )';
							}else{
								$total_eq_sum = $this->erp->formatMoney(abs($total_eq_sum));
							}
							?>
							
							<td><div class="fix-text text-right"><b><?php echo $total_eq_sum;?></b></div></td>														
							</tr>
							
							<tr>
							<td colspan="<?=$col1?>"><b><?= lang("total_liabilities_equities"); ?></b></td>
							
							<?php
							for($c= 0; $c < count($new_billers); $c++){
								$in_bill_id1 = 0;
								if($this->uri->segment(7)){
									$in_bill_id1 = $new_billers[$c]['id'];
								}else{
									$in_bill_id1 = $new_billers[$c]->id;
								}
								$total_lib_eq = 0;
								
								$k = 0;
								
								foreach($sum_lib_arr as $lib_row){
									if($lib_row['biller_id'] == $in_bill_id1 && ($lib_row['biller_id'] == $sum_eq_arr[$k]['biller_id'])){
										$total_lib_eq += $lib_row['amount'] + $sum_eq_arr[$k]['amount'];
									}
									$k++;
								}
								if($total_lib_eq<0){
									$total_lib_eq = '( '.$this->erp->formatMoney(abs($total_lib_eq)).' )';
								}else{
									$total_lib_eq = $this->erp->formatMoney(abs($total_lib_eq));
								}
								echo '<td><div class="fix-text text-right"><b>'. $total_lib_eq .'</b></div></td>';
							}
							?>
							<td><div class="fix-text text-right"><b><?php echo $this->erp->formatMoney(abs($end_total_lib) + $end_total_eq);?></b></div></td>
							<!--<td><strong><span class="pull-right blightOrange"><?php echo $this->erp->formatMoney(abs($total_equity+$total_liability-$total_retained));?></span></strong></td>-->
							</tr>
						</tbody>
						<!--
                        <tfoot class="dtFilter">
                        <tr class="active">                            
                            <th colspan="<?=$col1?>"><?= lang("Total ASSET = LIABILITIES + EQUITY"); ?></th>
                            <th><span class="pull-right"><?php echo $this->erp->formatMoney(abs($total_equity+$total_liability-$total_retained)-abs($total_asset));?></span></th>

                        </tr>
                        </tfoot>
						-->
                    </table>
                </div>
            </div>
		</div>
    </div>
</div>
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
				var url = "<?php echo site_url('reports/balance_sheet_details/'.$start.'/'.$end.'/0/0') ?>" + '/' + encodeURIComponent(billers);
				window.location.href = "<?=site_url('reports/balance_sheet_details/'. $start .'/'.$end.'/0/0/')?>" + '/' + encodedName;
			}
			
			if(hasCheck == false){
				bootbox.alert('Please select project first!');
				return false;
			}
			return false;
		});
		
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/balance_sheet/'. $start .'/'.$end.'/pdf/0/'.$biller_id)?>";
            return false;
        });
		$('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/balance_sheet/'. $start .'/'.$end.'/0/xls/'.$biller_id)?>";
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