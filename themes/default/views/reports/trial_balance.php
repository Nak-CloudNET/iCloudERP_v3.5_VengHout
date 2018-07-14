<script>
	$(document).ready(function () {
        CURI = '<?= site_url('reports/trial_balance'); ?>';
    });
</script>

<?php
	$start_date=date('Y-m-d',strtotime($start));
	$rep_space_end=str_replace(' ','_',$end);
	$end_date=str_replace(':','-',$rep_space_end);
?>
<style>
	#SupData .primary2 th{
		background-color: #428BCA;
		color: white;
		border-color: #357EBD;
	}
	#SupData .primary1 th{
		background-color: #428BCA;
		color: white;
		border-color: #357EBD;
	}
	#SupData .primary th{
		background-color: #F2DEDE;
		
	}
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-bars"></i><?= lang('trial_balance'); ?> >> <?= (isset($start)?$start:""); ?> >> <?= (isset($end)?$end:""); ?></h2>
        <div class="box-icon">
            <div class="form-group choose-date hidden-xs">
                <div class="controls">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" value="<?= ($start ? $this->erp->hrld($start) : '') . ' - ' . ($end ? $this->erp->hrld($end) : ''); ?>" id="daterange" class="form-control">
                        <span class="input-group-addon"><i class="fa fa-chevron-down"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>"><i class="icon fa fa-file-pdf-o"></i></a></li>
				<li class="dropdown"><a href="#" id="xls" class="tip" title="<?= lang('download_xls') ?>"><i class="icon fa fa-file-excel-o"></i></a></li>
                <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i class="icon fa fa-file-picture-o"></i></a></li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("billers") ?>"></i>
					</a>
					<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"aria-labelledby="dLabel">
						<li><a href="<?= site_url('reports/trial_balance') ?>"><i class="fa fa-building-o"></i> <?= lang('billers') ?></a></li>
						<li class="divider"></li>
						<?php
							foreach ($billers as $biller) {
								echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/trial_balance/'.$start.'/'.$end.'/0/0/' . $biller->id) . '"><i class="fa fa-building"></i>' . $biller->company . '</a></li>';
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
                    <table id="SupData" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped table-condensed">
						<thead>
							<tr class="primary1">								
								<th style="width:60%;text-align:left;"><?= lang("account_name"); ?></th>
								<th style="width:20%;"><?= lang("debit"); ?></th>
								<th style="width:20%;"><?= lang("credit"); ?></th>						   
							</tr>
                        </thead>
                       
						<tr class="primary">
							<th style="width:40%;text-align:left;" colspan="3"><?= lang("current_assets"); ?></th>
						</tr>
                       
						<?php
							$from = explode("%",$this->uri->segments["3"])[0];
							$to = explode("%",$this->uri->segments["4"])[0];
							
							$from_st = !empty($from)? "&start_date=".$this->erp->hrld($from) : "";
							$to_st = !empty($to)? "&end_date=".$this->erp->hrld($to) : "";							
					
							$total_10 = 0;
							$total_C = 0;
							$total_D = 0;
							foreach($data10->result() as $row10){
								if ($row10->amount>0){ ?>
								<tr>
									<td  style="padding-left:30px">
										<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row10->account_code) ?>">
											<?php echo $row10->account_code;?> - <?php echo $row10->accountname;?>
										</a>
									</td>
									
									<td>
										<span class="pull-right">
											<?php echo number_format(abs($row10->amount),2); $total_C += $row10->amount;?>
										</span>
									</td>
									<td></td>
								</tr>
							<?php } else { ?>	
								<tr>
									<td  style="padding-left:30px">
										<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row10->account_code) ?>">
											<?php echo $row10->account_code;?> - <?php echo $row10->accountname;?>
										</a>
									</td>
									<td></td>
									<td>
										<span class="pull-right">
											<?php echo number_format(abs($row10->amount),2); $total_D += $row10->amount;?>
										</span>
									</td>
								</tr>
								
							<?php } } ?>
								<tr class="primary">
									<th style="width:40%;text-align:left;" colspan="3"><?= lang("fixed_assets"); ?></th>
								</tr>
							<?php
								$total_11 = 0;
								foreach($data11->result() as $row11){
									if ($row11->amount>0){
							?>
								<tr>
									<td  style="padding-left:30px">
										<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row11->account_code) ?>">
											<?php echo $row11->account_code;?> - <?php echo $row11->accountname;?>
										</a>
									</td>
									<td>
										<span class="pull-right">
											<?php echo number_format(abs($row11->amount),2); $total_C += $row11->amount;?>
										</span>
									</td>
									<td></td>
								</tr>
							<?php } else { ?>	
								<tr>
									<td style="padding-left:30px">
										<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row11->account_code) ?>">
											<?php echo $row11->account_code;?> - <?php echo $row11->accountname;?>
										</a>
									</td>
									<td></td>
									<td><span class="pull-right"><?php echo number_format(abs($row11->amount),2); $total_D += $row11->amount;?></span>
									</td>
								</tr>
								
							<?php } } ?>
                       
                        <tr class="primary">
                            <th style="width:40%;text-align:left;" colspan="3"><?= lang("current_liabilities"); ?></th>
                        </tr>
                       
						<?php
							$total_20 = 0;
							foreach($data20->result() as $row20){
								if ($row20->amount>0){
						?>
							
							<tr>
								<td  style="padding-left:30px">
									<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row20->account_code) ?>">
										<?php echo $row20->account_code;?> - <?php echo $row20->accountname;?>
									</a>
								</td>
								<td><span class="pull-right"><?php echo number_format(abs($row20->amount),2); $total_C += $row20->amount;?></span></td>
								<td></td>
							</tr>
						<?php
								} else {
						?>	
							<tr>
								<td style="padding-left:30px">
									<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row20->account_code) ?>">
										<?php echo $row20->account_code;?> - <?php echo $row20->accountname;?>
									</a>
								</td>
								<td></td>
								<td><span class="pull-right"><?php echo number_format(abs($row20->amount),2); $total_D += $row20->amount;?></span></td>
							</tr>
								
						<?php
								}
							}
						?>
							<tr class="primary">
								<th style="width:40%;text-align:left;" colspan="3"><?= lang("non_liabilities"); ?></th>
							</tr>
                       
						<?php
							$total_21 = 0;
							foreach($data21->result() as $row21){
								if ($row21->amount>0){
						?>
							<tr>
								<td style="padding-left:30px">
									<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row21->account_code) ?>">
										<?php echo $row21->account_code;?> - <?php echo $row21->accountname;?>
									</a>
								</td>
								<td><span class="pull-right"><?php echo number_format(abs($row21->amount),2); $total_C += $row21->amount;?></span></td>
								<td></td>
							</tr>
						<?php
								} else {
						?>	
							<tr>
							<td style="padding-left:30px">
								<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row21->account_code) ?>">
									<?php echo $row21->account_code;?> - <?php echo $row21->accountname;?>
								</a>
							</td>
							<td></td>
							<td><span class="pull-right"><?php echo number_format(abs($row21->amount),2); $total_D += $row21->amount;?></span></td>
							</tr>
								
						<?php
								}
							}
						?>
                        
                        <tr class="primary">
                            <th style="width:40%;text-align:left;" colspan="3"><?= lang("equity_retained_erning"); ?></th>
                        </tr>
                      
						<?php
							$total_30 = 0;
							foreach($data30->result() as $row30){
								if ($row30->amount>0){
						?>
							
							<tr>
								<td  style="padding-left:30px">
									<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row30->account_code) ?>">
										<?php echo $row30->account_code;?> - <?php echo $row30->accountname;?>
									</a>
								</td>
								<td><span class="pull-right"><?php echo number_format(abs($row30->amount),2); $total_C += $row30->amount;?></span></td>
								<td></td>
							</tr>
						<?php
								} else {
						?>	
							<tr>
							<td style="padding-left:30px">
								<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row30->account_code) ?>">
									<?php echo $row30->account_code;?> - <?php echo $row30->accountname;?>
								</a>
							</td>
							<td></td>
							<td><span class="pull-right"><?php echo number_format(abs($row30->amount),2); $total_D += $row30->amount;?></span></td>
							</tr>
								
						<?php
								}
							}
						?>
                        
                        <tr class="primary">
                            <th style="width:40%;text-align:left;" colspan="3"><?= lang("income"); ?></th>
                        </tr>
						
						<?php
							$total_40 = 0;
							foreach($data40->result() as $row40){
								if ($row40->amount>0){
						?>
							
							<tr>
								<td  style="padding-left:40px">
									<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row40->account_code) ?>">
										<?php echo $row40->account_code;?> - <?php echo $row40->accountname;?>
									</a>
								</td>
								<td><span class="pull-right"><?php echo number_format(abs($row40->amount),2); $total_C += $row40->amount;?></span></td>
								<td></td>
							</tr>
						<?php
								} else {
						?>	
							<tr>
							<td  style="padding-left:40px">
								<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row40->account_code) ?>">
									<?php echo $row40->account_code;?> - <?php echo $row40->accountname;?>
								</a>
							</td>
							<td></td>
							<td><span class="pull-right"><?php echo number_format(abs($row40->amount),2); $total_D += $row40->amount;?></span></td>
							</tr>
								
						<?php
								}
							}
						?>
                       
                        <tr class="primary">
                            <th style="width:50%;text-align:left;" colspan="3"><?= lang("cost"); ?></th>
                        </tr>
						
						<?php
							$total_50 = 0;
							foreach($data50->result() as $row50){
								if ($row50->amount>0){
						?>
							
							<tr>
								<td  style="padding-left:50px">
									<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row50->account_code) ?>">
										<?php echo $row50->account_code;?> - <?php echo $row50->accountname;?>
									</a>
								</td>
								<td><span class="pull-right"><?php echo number_format(abs($row50->amount),2); $total_C += $row50->amount;?></span></td>
								<td></td>
							</tr>
						<?php
								} else {
						?>	
							<tr>
							<td  style="padding-left:50px">
								<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row50->account_code) ?>">
									<?php echo $row50->account_code;?> - <?php echo $row50->accountname;?>
								</a>
							</td>
							<td></td>
							<td><span class="pull-right"><?php echo number_format(abs($row50->amount),2); $total_D += $row50->amount;?></span></td>
							</tr>
								
						<?php
								}
							}
						?>
                       
                        <tr class="primary">
                            <th style="width:40%;text-align:left;" colspan="3"><?= lang("operating_expense"); ?></th>
                        </tr>
						
						<?php
							$total_60 = 0;
							foreach($data60->result() as $row60){
								if ($row60->amount>0){
						?>
							<tr>
								<td  style="padding-left:60px">
									<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row60->account_code) ?>">
										<?php echo $row60->account_code;?> - <?php echo $row60->accountname;?>
									</a>
								</td>
								<td><span class="pull-right"><?php echo number_format(abs($row60->amount),2); $total_C += $row60->amount;?></span></td>
								<td></td>
							</tr>
						<?php
								} else {
						?>	
							<tr>
							<td  style="padding-left:60px">
								<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row60->account_code) ?>">
									<?php echo $row60->account_code;?> - <?php echo $row60->accountname;?>
								</a>
							</td>
							<td></td>
							<td><span class="pull-right"><?php echo number_format(abs($row60->amount),2); $total_D += $row60->amount;?></span></td>
							</tr>
								
						<?php
								}
							}
						?>
						
                        <tr class="primary">
                            <th style="width:40%;text-align:left;" colspan="3"><?= lang("other_income"); ?></th>
                        </tr>
						
						<?php
							$total_70 = 0;
							foreach($data70->result() as $row70){
								if ($row70->amount>0){
						?>
							
							<tr>
								<td style="padding-left:70px">
									<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row70->account_code) ?>">
										<?php echo $row70->account_code;?> - <?php echo $row70->accountname;?>
									</a>
								</td>
								<td><span class="pull-right"><?php echo number_format(abs($row70->amount),2); $total_C += $row70->amount;?></span></td>
								<td></td>
							</tr>
						<?php
								} else {
						?>	
							<tr>
							<td  style="padding-left:70px">
								<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row70->account_code) ?>">
									<?php echo $row70->account_code;?> - <?php echo $row70->accountname;?>
								</a>
							</td>
							<td></td>
							<td><span class="pull-right"><?php echo number_format(abs($row70->amount),2);$total_D += $row70->amount;?></span></td>
							</tr>
								
						<?php
								}
							}
						?>
						
                        <tr class="primary">
                            <th style="width:40%;text-align:left;" colspan="3"><?= lang("other_expense"); ?></th>
                        </tr>

						<?php
							$total_80 = 0;
							foreach($data80->result() as $row80){
								if ($row80->amount>0){
						?>
							
							<tr>
								<td  style="padding-left:80px">
									<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row80->account_code) ?>">
										<?php echo $row80->account_code;?> - <?php echo $row80->accountname;?>
									</a>
								</td>
								<td><span class="pull-right"><?php echo number_format(abs($row80->amount),2);$total_C += $row80->amount;?></span></td>
								<td></td>
							</tr>
						<?php
								} else {
						?>	
							<tr>
							<td style="padding-left:80px">
								<a href="<?= site_url('reports/ledger/0/0/'.$biller_id.'?w=1'.$from_st.$to_st.'&account='.$row80->account_code) ?>">
									<?php echo $row80->account_code;?> - <?php echo $row80->accountname;?>
								</a>
							</td>
							<td></td>
							<td><span class="pull-right"><?php echo number_format(abs($row80->amount),2); $total_D += $row80->amount;?></span></td>
							</tr>
								
						<?php
								}
							}
						?>
						
                       
							<tr class="primary2">                            
								<th ><?= lang("total"); ?></th>
								<th><span class="pull-right"><?php echo number_format(abs($total_D),2);?></span></th>
								<th><span class="pull-right"><?php echo number_format(abs($total_C),2);?></span></th>
							</tr>
                       
						
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
            window.location.href = "<?=site_url('reports/trial_balance/'. $start .'/'.$end.'/pdf/0/'.$biller_id)?>";
            return false;
        });
		$('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/trial_balance/'. $start .'/'.$end.'/0/xls/'.$biller_id)?>";
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