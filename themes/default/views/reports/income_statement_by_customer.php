<script>$(document).ready(function () {
        CURI = '<?= site_url('reports/income_statement_by_customer'); ?>';
    });</script>
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
<?php if ($Owner) {
    echo form_open('reports/income_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-bars"></i><?= lang('income_statement_by_customer'); ?> >> <?= (isset($start)?$start:""); ?> >> <?= (isset($end)?$end:""); ?> </h2>

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
							title="<?= lang("customers") ?>"></i></a>
					<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
						aria-labelledby="dLabel">
						<li><a href="<?= site_url('reports/income_statement_by_customer') ?>"><i
									class="fa fa-building-o"></i> <?= lang('projects') ?></a></li>
						<li class="divider"></li>
						<?php
						$b_sep = 0;
						foreach ($customers as $customer) {
							$customer_sep = explode('-', $this->uri->segment(7));
							if($customer_sep[$b_sep] == $customer->id){
								echo '<li ' . ($customer_id && $customer_id == $customer->id ? 'class="active"' : '') . '>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="customer_checkbox[]" class="checkbox customer_checkbox" checked value="'. $customer->id .'" >&nbsp;&nbsp;' . $customer->name . '</li>';
								echo '<li class="divider"></li>';
								$b_sep++;
							}else{
								echo '<li ' . ($customer_id && $customer_id == $customer->id ? 'class="active"' : '') . '>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="customer_checkbox[]" class="checkbox customer_checkbox" value="'. $customer->id .'" >&nbsp;&nbsp;' . $customer->name . '</li>';
								echo '<li class="divider"></li>';
							}							
						}
						?>
						<li class="text-center"><a href="#" id="customer-filter" class="btn btn-primary"><?=lang('submit')?></a></li>
					</ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('list_results'); ?></p>
				<?php $num_col=2; ?>
                <div class="table-scroll">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped table-condensed">
						<thead>
                        <tr>
                            <th style="text-align:left; width:300px;"><?= lang("account_name"); ?></th>
							<?php 
							$new_customers = array();
								foreach ($customers as $b1) {
									if($this->uri->segment(7)){
										$customer_sep = explode('-', $this->uri->segment(7));
										for($i=0; $i < count($customer_sep); $i++){
											if($customer_sep[$i] == $b1->id){
												echo '<th>' . $b1->name . '</th>';
												$new_customers[] = array('id' => $b1->id);
											}
										}
									}else{
										$new_customers = $customers;
										echo '<th>' . $b1->name . '</th>';
									}
									$num_col++;
								}
							?>
							<th><?= lang("total"); ?></th>
                        </tr>
						<tr class="primary">
                            <th style="text-align:left;" colspan="<?=$num_col?>"><?= lang("income"); ?></th>	
                        </tr>
                        </thead>
					
                        <tbody>
						<?php
							$total_income = 0;
                            $totalBeforeAyear_income = 0;
							$total_income_array = array();
							$total_cost_array = array();
							$total_op_array = array();
							$sum_total_income = array();
							$sum_total_cost = array();
							$sum_total_op = array();
							$sum_total_gross = array();
							$from = explode("%",$this->uri->segments["3"])[0];
							$to = explode("%",$this->uri->segments["4"])[0];							
							$from_st = !empty($from)? "&start_date=".$this->erp->hrld($from) : "";
							$to_st = !empty($to)? "&end_date=".$this->erp->hrld($to) : "";		
							
							foreach($dataIncome->result() as $row){
							$total_income += (-1)*$row->amount;
							
						?>
							<tr>
					    <?php 
						$index = 0;
						$total_per_income = 0;
						for($i = 1; $i <= count($new_customers); $i++){
							$customer_id = 0;
							if($this->uri->segment(7)){
								$customer_id = $new_customers[$index]['id'];
							}else{
								$customer_id = $new_customers[$index]->id;
							}
						  $query = $this->db->query("SELECT
                                SUM(COALESCE(erp_gl_trans.amount, 0)) AS amount
                            FROM
                                erp_gl_trans
                            WHERE
                                 account_code = '" . $row->account_code . "'
								AND erp_gl_trans.customer_id = '" . $customer_id . "'
								AND DATE_FORMAT(erp_gl_trans.tran_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date';");
							
                            $totalBeforeAyearRows = $query->row();
								$amount_income = (-1)*$totalBeforeAyearRows->amount;
								if($amount_income < 0){
									$amount_income = '( '.number_format(abs($totalBeforeAyearRows->amount),2).' )';
								}else{
									$amount_income = number_format(abs($totalBeforeAyearRows->amount),2);
								}
								
								if(($index+1)==1){
								?>
									<td>
										<a href="<?= site_url('reports/ledger_by_customer/?w=1'.$from_st.$to_st.'&account='.$row->account_code) ?>">
											<?php echo $row->account_code;?> - <?php echo $row->accountname;?>
										</a>
									</td>
									<td class="right">
										<a href="<?= site_url('reports/ledger_by_customer/?w=1'.$from_st.$to_st.'&customer='.$customer_id.'&account='.$row->account_code) ?>">
											<?php echo $amount_income;?>
										</a>
									</td>
								<?php 
								$total_income_array[] = array(
									'id' => $customer_id,
									'amount' => (-1)*$totalBeforeAyearRows->amount
								);
								}else{?>
									
									<td class="right">
										<a href="<?= site_url('reports/ledger_by_customer/?w=1'.$from_st.$to_st.'&customer='.$customer_id.'&account='.$row->account_code) ?>">
											<?php echo $amount_income;?>
										</a>
									</td>
									
									<?php
									$total_income_array[] = array(
										'id' => $customer_id,
										'amount' => (-1)*$totalBeforeAyearRows->amount
									);
								}
								$total_per_income += (-1)*$totalBeforeAyearRows->amount;
								$index++;
							}
							if($total_per_income < 0){
								$total_per_income = '( ' . number_format(abs($total_per_income),2) . ' )';
							}else{
								$total_per_income = number_format(abs($total_per_income),2);
							}
							echo '<td class="right">'. $total_per_income .'</td>';
								?>
							</tr>
						<?php
							}
							$col1 = 2;
							$colbot = 0;

							if($this->uri->segment(7)){
								//$col1 = 2;
								$colbot = 3;
							}else{
								$colcount = count($new_customers);
								//$col1 = $colcount-1;
								$colbot = $colcount + 2;
							}
					
							$inc_amt_arr = array();
	
							for($c= 0; $c <= count($new_customers); $c++){
								$in_customer_id1 = 0;
								if($this->uri->segment(7)){
									$in_customer_id1 = $new_customers[$c]['id'];
								}else{
									$in_customer_id1 = $new_customers[$c]->id;
								}
								$total_inc_amt = 0;
								foreach($total_income_array as $new_arr){
									if($new_arr['id'] == $in_customer_id1){
										$total_inc_amt += $new_arr['amount'];
									}
								}
								$inc_amt_arr[] = array(
									'id' => $in_customer_id1,
									'amount' => $total_inc_amt
								);
							}
						?>
							<tr>
							<td style="font-weight:bold;"><?= lang("total_income"); ?></td>
							<?php
							for($i = 0; $i < count($new_customers); $i++){
								$customer_id = 0;
								if($this->uri->segment(7)){
									$customer_id = $new_customers[$i]['id'];
								}else{
									$customer_id = $new_customers[$i]->id;
								}
								$total_amt_inc = 0;
								foreach ($total_income_array as $val) {
									if($customer_id == $val['id']){
										$total_amt_inc += $val['amount'];
									}
								}
								$sum_total_income[] = array(
									'customer_id' => $customer_id,
									'amount' => $total_amt_inc
								);
								
								echo '<td class="right" style="font-weight:bold;border-top:2px solid #000">' . number_format(abs($total_amt_inc), 2) . '</td>';
							}
							?>
							<?php 
							$total_income_display = '';
							if($total_amt_inc < 0){
								$total_income_display = '( '.number_format(abs($total_amt_inc),2).' )';
							}else{
								$total_income_display = number_format(abs($total_amt_inc),2);
							}
							?>
								<td class="right" style="font-weight:bold;border-top:2px solid #000;border-left:2px solid #000;">
									<?php echo $total_income_display;?>
								</td>
							</tr>
							<tr class="primary">
								<th style="text-align:left;"  colspan="<?=$num_col?>"><?= lang("cost_of_goods_sold"); ?></th>
							</tr>
							<?php
							$total_cost = 0;
                            $totalBeforeAyear_cost = 0;
							foreach($dataCost->result() as $rowcost){
							$total_cost += $rowcost->amount;
						?>
							<tr>
								
								<?php
								$index1 = 0;
								$total_per_cost = 0;
								for($j = 1; $j <= count($new_customers); $j++){
									
									$customer_id = 0;
									if($this->uri->segment(7)){
										$customer_id = $new_customers[$index1]['id'];
									}else{
										$customer_id = $new_customers[$index1]->id;
									}
									
									$query = $this->db->query("SELECT
										sum(erp_gl_trans.amount) AS amount
									FROM
										erp_gl_trans
									WHERE
										account_code = '" . $rowcost->account_code . "'
										AND erp_gl_trans.customer_id = '" . $customer_id . "'
										AND DATE_FORMAT(erp_gl_trans.tran_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date';");
									$totalBeforeAyearRows = $query->row();
									$totalBeforeAyear_cost += $totalBeforeAyearRows->amount;
									
									$amount_cost = 0;
									if($totalBeforeAyearRows->amount < 0){
										$amount_cost = '( '.number_format(abs($totalBeforeAyearRows->amount),2).' )';
									}else{
										$amount_cost = number_format(abs($totalBeforeAyearRows->amount),2);
									}
									
									if(($index1+1)==1){
										$total_cost_array[] = array(
											'id' => $customer_id,
											'amount' => $totalBeforeAyearRows->amount
										);
								?>
								
								<td>
									<a href="<?= site_url('reports/ledger_by_customer/?w=1'.$from_st.$to_st.'&account='.$rowcost->account_code) ?>">
										<?php echo $rowcost->account_code;?> - <?php echo $rowcost->accountname;?>
									</a>
								</td>
								
								<td class="right">
									<a href="<?= site_url('reports/ledger_by_customer/?w=1'.$from_st.$to_st.'&customer='.$customer_id.'&account='.$rowcost->account_code) ?>">
										<?php echo $amount_cost;?>
									</a>
								</td>
								
						<?php
								}else{
									?>
									<td class="right">
										<a href="<?= site_url('reports/ledger_by_customer/?w=1'.$from_st.$to_st.'&customer='.$customer_id.'&account='.$rowcost->account_code) ?>">
											<?php echo $amount_cost;?>
										</a>
									</td>
									<?php 									
									$total_cost_array[] = array(
										'id' => $customer_id,
										'amount' => $totalBeforeAyearRows->amount
									);
								}
								$total_per_cost += $totalBeforeAyearRows->amount;
								$index1++;
								}
								if($total_per_cost < 0){
									$total_per_cost = '( '.number_format(abs($total_per_cost),2).' )';
								}else{
									$total_per_cost = number_format(abs($total_per_cost),2);
								}
								echo '<td class="right">'. $total_per_cost .'</td>';
								echo '</tr>';
							}
						?>
							<tr>
								<td style="font-weight:bold;"><?= lang("total_cost"); ?></td>
								<?php
								for($in = 0; $in < count($new_customers); $in++){
									$in_customer_id = 0;
									if($this->uri->segment(7)){
										$in_customer_id = $new_customers[$in]['id'];
									}else{
										$in_customer_id = $new_customers[$in]->id;
									}
									$total_amt_cost = 0;
									foreach ($total_cost_array as $val) {
										if($in_customer_id == $val['id']){
											$total_amt_cost += $val['amount'];
										}
									}
									
									$sum_total_cost[] = array(
										'customer_id' => $in_customer_id,
										'amount' => $total_amt_cost
									);
									
									if($total_amt_cost < 0){
										$total_amt_cost = '( '.number_format(abs($total_amt_cost),2).' )';
									}else{
										$total_amt_cost = number_format(abs($total_amt_cost),2);
									}
									echo '<td class="right" style="font-weight:bold;border-top:2px solid #000">' . $total_amt_cost . '</td>';
								}
								$total_cost_display = '';
								if($total_amt_cost < 0){
									$total_cost_display = '( '.number_format(abs($total_amt_cost),2).' )';
								}else{
									$total_cost_display = number_format(abs($total_amt_cost),2);
								}
								?>
								
								<td class="right" style="font-weight:bold;border-top:2px solid #000;border-left:2px solid #000;">
									<?php echo $total_cost_display; ?>
								</td>
							</tr>							
							<tr>
								<td style="font-weight:bold;"><?= lang("gross_margin"); ?></td>
								<?php 
								for($i =0; $i < count($sum_total_income); $i++){
									$amount_per_gross =0;
									$amount_per_inc = 0;
									$amount_per_cost = 0;
									$amount_per_inc = $sum_total_income[$i]['amount'];
									$amount_per_cost = $sum_total_cost[$i]['amount'];
									$amount_per_gross = $amount_per_inc - $amount_per_cost;
									$sum_total_gross[] = array(
										'customer_id' => $sum_total_cost[$i]['customer_id'],
										'amount' => $amount_per_gross
									);
									if($amount_per_gross < 0){
										$total_amount_per_gross = '( '.number_format(abs($amount_per_gross),2).' )';
									}else{
										$total_amount_per_gross = number_format(abs($amount_per_gross),2);
									}
									echo '<td style="font-weight:bold;" class="right">' . $total_amount_per_gross . '</td>';
								}
								?>
								<td class="right" style="font-weight:bold;">
									<?php 
									if($total_amt_inc - $total_amt_cost < 0){
										echo "(".number_format(abs($total_amt_inc - $total_amt_cost),2).")";
									}else{
										echo number_format(abs($total_amt_inc - $total_amt_cost),2);
									}
									?>
								</td>
							</tr>
							<tr class="primary">
								<th style="font-weight:bold;width:20%;text-align:left;"><?= lang("operating_expense"); ?></th>
							</tr>
							<?php
							$total_expense = 0;
                            $totalBeforeAyear_expense = 0;
							foreach($dataExpense->result() as $row){
							//$total_expense += $row->amount;
						?>
							<tr>
								<?php
								$in_op = 0;
								$total_per_op = 0;
								for($i = 1; $i <= count($new_customers); $i++){
									$customer_id = 0;
									if($this->uri->segment(7)){
										$customer_id = $new_customers[$in_op]['id'];
									}else{
										$customer_id = $new_customers[$in_op]->id;
									}
									
									$query = $this->db->query("SELECT
										SUM(COALESCE(erp_gl_trans.amount, 0)) AS amount
									FROM
										erp_gl_trans
									WHERE
										account_code = '" . $row->account_code . "'
										AND customer_id = '" . $customer_id . "' 
										AND DATE_FORMAT(erp_gl_trans.tran_date, '%Y-%m-%d') BETWEEN '$from_date' AND '$to_date';");
									$totalBeforeAyearRows = $query->row();
									$totalBeforeAyear_expense += $totalBeforeAyearRows->amount;
									$amount_op = 0;
									if($totalBeforeAyearRows->amount < 0){
										$amount_op = '( '.number_format(abs($totalBeforeAyearRows->amount),2).' )';
									}else{
										$amount_op = number_format(abs($totalBeforeAyearRows->amount),2);
									}
									
									if($i==1){
										$total_op_array[] = array(
											'id' => $customer_id,
											'amount' => $totalBeforeAyearRows->amount
										);
									?>
										<td>
											<a href="<?= site_url('reports/ledger_by_customer/?w=1'.$from_st.$to_st.'&account='.$row->account_code) ?>">
												<?php echo $row->account_code;?> - <?php echo $row->accountname;?>
											</a>
										</td>
										
										<td class="right">
											<a href="<?= site_url('reports/ledger_by_customer/?w=1'.$from_st.$to_st.'&customer='.$customer_id.'&account='.$row->account_code) ?>">	
												<?php echo $amount_op;?>
											</a>
										</td>
								
								<?php }else{
										$total_op_array[] = array(
											'id' => $customer_id,
											'amount' => $totalBeforeAyearRows->amount
										);?>
										<td class="right">
											<a href="<?= site_url('reports/ledger_by_customer/?w=1'.$from_st.$to_st.'&customer='.$customer_id.'&account='.$row->account_code) ?>">	
												<?php echo $amount_op;?>
											</a>
										</td>
										<?php
									}
									$total_per_op += $totalBeforeAyearRows->amount;
									$in_op++;
								}
								if($total_per_op < 0){
									$total_per_op = '( '.number_format(abs($total_per_op),2).' )';
								}else{
									$amount_op = number_format(abs($total_per_op),2);
								}
								echo '<td class="right">' . $total_per_op .'</td>';
								?>
							</tr>
						<?php
							}
						?>
							<tr>
							<td style="font-weight:bold"><?= lang("total_expense"); ?></td>
							<?php
							for($i = 0; $i < count($new_customers); $i++){
								$customer_id = 0;
								if($this->uri->segment(7)){
									$customer_id = $new_customers[$i]['id'];
								}else{
									$customer_id = $new_customers[$i]->id;
								}
								$total_amt_op = 0;
								foreach ($total_op_array as $val) {
									if($customer_id == $val['id']){
										$total_amt_op += $val['amount'];
                                        $total_expense += $val['amount'];
									}
								}
								
								$sum_total_op[] = array(
									'customer_id' => $customer_id,
									'amount' => $total_amt_op
								);
								
								if($total_amt_op < 0){
									$total_amt_op = '( '.number_format(abs($total_amt_op),2).' )';
								}else{
									$total_amt_op = number_format(abs($total_amt_op),2);
								}
								echo '<td class="right" style="border-top:2px solid #000;font-weight:bold">' . $total_amt_op . '</td>';
							}
							$total_expense_display = '';
							if($total_expense < 0){
								$total_expense_display = '( '.number_format(abs($total_expense),2).' )';
							}else{
								$total_expense_display = number_format(abs($total_expense),2);
							}
							?>
							<td class="right" style="border-top:2px solid #000;border-left:2px solid #000;font-weight:bold">
								<?php echo $total_expense_display;?>
							</td>
							</tr>
							<tr class="active">                            
								<th><?= lang("profits"); ?></th>
								<?php
								for($i = 0; $i < count($sum_total_gross); $i++){
									$per_gross = 0;
									$per_exp = 0;
									$per_gross = $sum_total_gross[$i]['amount'];
									$per_exp = $sum_total_op[$i]['amount'];
									$total_per_op_ex = ($per_gross - $per_exp);
									if($total_per_op_ex < 0){
										$total_per_op_ex = '( '.number_format(abs($total_per_op_ex),2).' )';
									}else{
										$total_per_op_ex = number_format(abs($total_per_op_ex),2);
									}
									echo '<th class="right">' . $total_per_op_ex . '</th>';
								}
								?>
								
								<?php 
								$total_profit_per = ($total_amt_inc - $total_amt_cost)-$total_expense;
								$total_profit_loss_display = '';
								if($total_profit_per < 0){
									$total_profit_loss_display = '( '.number_format(abs($total_profit_per),2).' )';
								}else{
									$total_profit_loss_display = number_format(abs($total_profit_per),2);
								}
								?>
								<th class="right"><?php echo $total_profit_loss_display;?></th>
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
		$("#customer-filter").on('click', function(event){
			event.preventDefault();
			var hasCheck = false;
			customer_ids = '';
			$.each($("input[name='customer_checkbox[]']:checked"), function(){
				hasCheck = true;
				customer_ids += $(this).val() + '-';
			});
			var customers = removeSymbolLastString(customer_ids, '-');
			if(hasCheck == true){
				var encodedName = encodeURIComponent(customers);
				var url = "<?php echo site_url('reports/income_statement_by_customer/'.$start.'/'.$end.'/0/0') ?>" + '/' + encodeURIComponent(customers);
				window.location.href = "<?=site_url('reports/income_statement_by_customer/'. $start .'/'.$end.'/0/0/')?>" + '/' + encodedName;
			}
			
			if(hasCheck == false){
				bootbox.alert('Please select project first!');
				return false;
			}
			return false;
		});
		
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/income_statement_by_customer/'. $start .'/'.$end.'/pdf/0/'.$customer_id)?>";
            return false;
        });
		
		$('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/income_statement_by_customer/'. $start .'/'.$end.'/0/xls/'.$customer_id)?>";
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