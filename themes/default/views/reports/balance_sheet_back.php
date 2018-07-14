<script>$(document).ready(function () {
        CURI = '<?= site_url('reports/balance_sheet_back'); ?>';
	});</script>
<style>@media print {
        .fa {
            color: #EEE;
            display: none;
        }

        .small-box {
            border: 1px solid #CCC;
        }
    }</style>
<?php
	$start_date=date('Y-m-d',strtotime($start));
	$rep_space_end=str_replace(' ','_',$end);
	$end_date=str_replace(':','-',$rep_space_end);
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-bars"></i><?= lang('balance_sheet'); ?></h2>
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
                                <li><a href="<?= site_url('reports/balance_sheet_back') ?>"><i
                                            class="fa fa-building-o"></i> <?= lang('projects') ?></a></li>
                                <li class="divider"></li>
                                <?php
                                foreach ($billers as $biller) {
                                    echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/balance_sheet_back/'.$start.'/'.$end.'/0/0/' . $biller->id) . '"><i class="fa fa-building"></i>' . $biller->company . '</a></li>';
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

                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
                    <table id="SupData" cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
						<thead>
							<tr class="primary">                            
								<th style="width:60%;text-align:left;" colspan="2"><?= lang("account_name"); ?></th>
								<th style="width:20%;"><?= lang("debit"); ?></th>
								<th style="width:20%;"><?= lang("credit"); ?></th>
								<th style="width:20%;"><?= lang("debit") . ' (' . $totalBeforeAyear . ')'; ?></th>
								<th style="width:20%;"><?= lang("credit") . ' (' . $totalBeforeAyear . ')'; ?></th>
							</tr>
                        </thead>
                        
                        <thead>
							<tr class="primary">                            
								<th style="width:40%;text-align:left;" colspan="3"><?= lang("asset"); ?></th>							
							</tr>
                        </thead>
                        <tbody>
						<?php
							$total_asset = 0;
							$totalBeforeAyear_asset = 0;

							foreach($dataAsset->result() as $row){
								$total_asset += $row->amount;
								$query = $this->db->query("SELECT
                                SUM(CASE WHEN erp_gl_trans.amount < 0 THEN erp_gl_trans.amount ELSE 0 END) as NegativeTotal,
       							SUM(CASE WHEN erp_gl_trans.amount >= 0 THEN erp_gl_trans.amount ELSE 0 END) as PostiveTotal
	                            FROM
	                                erp_gl_trans
	                            WHERE
	                                DATE(tran_date) = '$totalBeforeAyear' AND account_code = '" . $row->account_code . "';");
	                            $totalBeforeAyearRows = $query->row();
	                            $totalBeforeAyear_asset += ($totalBeforeAyearRows->NegativeTotal + $totalBeforeAyearRows->PostiveTotal);

								if ($row->amount>0){
						?>
							
							<tr>
								<td colspan="2" style="padding-left:30px"><?php echo $row->account_code;?> - <?php echo $row->accountname;?></td>
								<td><span class="pull-right"><?php echo number_format(abs($row->amount),2);?></span></td>
								<td></td>
								<td><span class="pull-right"><?php echo number_format(abs($totalBeforeAyearRows->PostiveTotal),2);?></span></td>
								<td></td>
							</tr>
						<?php
								} else {
						?>	
							<tr>
								<td colspan="2" style="padding-left:30px"><?php echo $row->account_code;?> - <?php echo $row->accountname;?></td>
								<td></td>
								<td><span class="pull-right"><?php echo number_format(abs($row->amount),2);?></span></td>							
								<td></td>
								<td><span class="pull-right"><?php echo number_format(abs($totalBeforeAyearRows->NegativeTotal),2);?></span></td>
							</tr>
								
						<?php
								}
							}
						?>
							<tr>
								<td colspan="2"><?= lang("total_asset"); ?></td>
								<td><strong><span class="pull-right blightOrange"><?php echo number_format(abs($total_asset),2);?></span></strong></td>
								<td></td>
								<td><strong><span class="pull-right blightOrange"><?php echo number_format(abs($totalBeforeAyear_asset),2);?></span></strong></td>
								<td></td>
							</tr>
						                        
                        </tbody>
						
						<thead>
                        <tr class="primary">
                            
                            <th style="width:20%;text-align:left;" colspan="3"><?= lang("liabilities"); ?></th>
                       
                        </tr>
                        </thead>
                        <tbody>
						
						<?php
							$total_liability = 0;
							$totalBeforeAyear_liability = 0;
							foreach($dataLiability->result() as $rowlia){
							$total_liability += $rowlia->amount;

							$query = $this->db->query("SELECT
                                SUM(CASE WHEN erp_gl_trans.amount < 0 THEN erp_gl_trans.amount ELSE 0 END) as NegativeTotal,
       							SUM(CASE WHEN erp_gl_trans.amount >= 0 THEN erp_gl_trans.amount ELSE 0 END) as PostiveTotal
	                            FROM
	                                erp_gl_trans
	                            WHERE
	                                DATE(tran_date) = '$totalBeforeAyear' AND account_code = '" . $rowlia->account_code . "';");
                            $totalBeforeAyearRows = $query->row();
                            $totalBeforeAyear_liability += ($totalBeforeAyearRows->NegativeTotal + $totalBeforeAyearRows->PostiveTotal);

								if ($rowlia->amount>0){
						?>
							
							<tr>
								<td colspan="2" style="padding-left:30px"><?php echo $rowlia->account_code;?> - <?php echo $rowlia->accountname;?></td>
								<td><span class="pull-right"><?php echo number_format(abs($rowlia->amount),2);?></span></td>
								
								<td><span class="pull-right"><?php echo number_format(abs($totalBeforeAyearRows->PostiveTotal),2);?></span></td>
								<td></td>
							</tr>
						<?php
								} else {
						?>	
							<tr>
								<td colspan="2" style="padding-left:30px"><?php echo $rowlia->account_code;?> - <?php echo $rowlia->accountname;?></td>
								<td></td>
								<td><span class="pull-right"><?php echo number_format(abs($rowlia->amount),2);?></span></td>
								<td></td>
								<td><span class="pull-right"><?php echo number_format(abs($totalBeforeAyearRows->NegativeTotal),2);?></span></td>
							</tr>
								
						<?php
								}
							}
						?>
							<tr>
							<td colspan="2"><?= lang("total_liabilities"); ?></td>
							<td></td>
							<td><strong><span class="pull-right"><?php echo number_format(abs($total_liability),2);?></span></strong></td>
							<td></td>
							<td><strong><span class="pull-right"><?php echo number_format(abs($totalBeforeAyear_liability),2);?></span></strong></td>							
							</tr>							
                        </tbody>
						
						<thead>
                        <tr class="primary">                            
                            <th style="width:20%;text-align:left;" colspan="3"><?= lang("equities"); ?></th>                       
                        </tr>
                        </thead>
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

							foreach($dataIncome->result() as $rowincome){
							$total_income += $rowincome->amount;
							}
							foreach($dataExpense->result() as $rowexpense){
							$total_expense += $rowexpense->amount;
							}
							$total_retained = abs($total_income)-abs($total_expense);
						?>
						
                        <tbody>
							<tr>
							<td colspan="2" style="padding-left:30px">300200 - Retained Earnings</td>
						<?php if($total_retained<0) { ?>						
							<td><span class="pull-right"><?php echo number_format(abs($total_retained),2);?></span></td>
							<td></td>
							<td><span class="pull-right"><?php echo number_format($total_retained_beforeAyear,2);?></span></td>
							<td></td>
						<?php } else { ?>
							<td></td>
							<td><span class="pull-right"><?php echo number_format(abs($total_retained),2);?>
							<td></td>
							<td><span class="pull-right"><?php echo number_format($total_retained_beforeAyear,2);?></span></td>
						<?php }	?>							
							
							
							</tr>
						<?php
							$total_equity = 0;
							$totalBeforeAyear_equity = 0;
							foreach($dataEquity->result() as $rowequity){
							$total_equity += $rowequity->amount;

							$query = $this->db->query("SELECT
                                sum(erp_gl_trans.amount) AS amount
                            FROM
                                erp_gl_trans
                            WHERE
                                DATE(tran_date) = '$totalBeforeAyear' AND account_code = '" . $rowequity->account_code . "';");
                            $totalBeforeAyearRows = $query->row();
                            $totalBeforeAyear_equity += $totalBeforeAyearRows->amount;

						?>
							<tr>
								<?php if($rowequity->amount<0) { ?>	
								<td colspan="2" style="padding-left:30px"><?php echo $rowequity->account_code;?> - <?php echo $rowequity->accountname;?></td>
								<td></td>
								<td><span class="pull-right"><?php echo number_format(abs($rowequity->amount),2);?></span></td>								
								<td><span class="pull-right"><?php echo number_format(abs($totalBeforeAyearRows->amount),2);?></span></td>
								<td></td>
								<?php } else { ?>
								<td colspan="2" style="padding-left:30px"><?php echo $rowequity->account_code;?> - <?php echo $rowequity->accountname;?></td>
								<td><span class="pull-right"><?php echo number_format(abs($rowequity->amount),2);?></span></td>
								<td></td>
								<td><span class="pull-right"><?php echo number_format(abs($totalBeforeAyearRows->amount),2);?></span></td>
								<td></td>
								<?php }	?>
							</tr>
						<?php
							}
						?>							
							<tr>
							<td colspan="2"><?= lang("total_equities"); ?></td>
							<td></td>
							<td><strong><span class="pull-right"><?php echo number_format(abs($total_equity-$total_retained),2);?></span></strong></td>														
							<td></td>
							<td><strong><span class="pull-right"><?php echo number_format(abs($totalBeforeAyear_equity-$total_retained_beforeAyear),2);?></span></strong></td>
							</tr>
							
							<tr>
							<td colspan="2"><?= lang("total_liabilities_equities"); ?></td>
							<td></td>
							<td><strong><span class="pull-right blightOrange"><?php echo number_format(abs($total_equity+$total_liability-$total_retained),2);?></span></strong></td>
							<td></td>
							<td><strong><span class="pull-right blightOrange"><?php echo number_format(abs($totalBeforeAyear_equity+$totalBeforeAyear_liability-$total_retained_beforeAyear),2);?></span></strong></td>
							</tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">                            
                            <th colspan="2"><?= lang("Total ASSET = LIABILITIES + EQUITY"); ?></th>
                            <td></td>
                            <th><span class="pull-right"><?php echo number_format(abs($total_equity+$total_liability-$total_retained)-abs($total_asset),2);?></span></th>
                            <td></td>
                            <th><span class="pull-right"><?php echo number_format(abs($totalBeforeAyear_equity+$totalBeforeAyear_liability+$total_retained_beforeAyear)-abs($totalBeforeAyear_asset),2);?></span></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/balance_sheet_back/'. $start .'/'.$end.'/pdf/0/'.$biller_id)?>";
            return false;
        });
		$('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/balance_sheet_back/'. $start .'/'.$end.'/0/xls/'.$biller_id)?>";
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