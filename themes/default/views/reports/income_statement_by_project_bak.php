<script>$(document).ready(function () {
        CURI = '<?= site_url('reports/income_statement_by_project'); ?>';
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

<?php if ($Owner) {
    echo form_open('reports/actions_income_statement_by_project', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-bars"></i><?= lang('income_by_project'); ?></h2>
		
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
						<li><a href="<?= site_url('reports/income_statement_by_project') ?>"><i
									class="fa fa-building-o"></i> <?= lang('billers') ?></a></li>
						<li class="divider"></li>
						<?php
						$b_sep = 0;
						$new_billers = array();
						foreach ($billers as $biller) {
							$biller_sep = explode('-', $this->uri->segment(7));
							
							if($biller_sep[$b_sep] == $biller->id){
								echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="biller_checkbox[]" class="checkbox biller_checkbox" checked value="'. $biller->id .'" >&nbsp;&nbsp;' . $biller->company . '</li>';
								echo '<li class="divider"></li>';
								$b_sep++;
								$new_billers[] = $biller;
							}else{
								echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="biller_checkbox[]" class="checkbox biller_checkbox" value="'. $biller->id .'" >&nbsp;&nbsp;' . $biller->company . '</li>';
								echo '<li class="divider"></li>';
							}
							//echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/balance_sheet/'.$start.'/'.$end.'/0/0/' . $biller->id) . '"><input type="checkbox" class="checkbox biller_checkbox" value="'. $biller->id .'" >&nbsp;&nbsp;' . $biller->company . '</a></li>';
						}
						if(!$new_billers){
							$new_billers = $billers;
						}
						?>
						<li class="text-center"><a href="#" id="biller-filter" class="btn btn-primary"><?=lang('submit')?></a></li>
					</ul>
                </li>
            </ul>
        </div>

    </div>
	<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?php echo form_close(); ?>
<?php } ?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12" style="overflow-x: scroll">

                <p class="introtext"><?= lang('list_results'); ?></p>
				<!--
				<div id="form">

                    <?php echo form_open("reports/income_statement_by_project"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="biller"><?= lang("biller"); ?></label>
                                <?php
                                $bl[''] = lang("all");
                                foreach ($billers as $biller) {
                                    $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                }
                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                                ?>
                            </div>
                        </div>
						
						<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("year", "year"); ?>
                                <?php echo form_input('year', (isset($_POST['year']) ? $_POST['year'] : $year), 'class="form-control number_only date-year" id="year"'); ?>
                            </div>
                        </div>
						
                    </div>
                    <div class="form-group">
                        <div class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
				-->
				<?php
				$m_year = '';
				$m_start = date('Y', strtotime($start));
				$m_end = date('Y', strtotime($end));
				if($m_start != $m_end){
					$m_year = $m_start . ' - ' . $m_end;
				}else{
					$m_year = $m_start;
				}
				
				$years_arr = array();
				$m_y = date("Y", strtotime($start));
				while($m_y <= date("Y", strtotime($end))){
					$years_arr[] = $m_y;
					$m_y++;
				}
				?>
                <div class="table-scroll">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th colspan="7"></th>
							<?php
							for($m_i = 0; $m_i < sizeOf($years_arr); $m_i++){
							?>
								<th class="text-center" colspan="14"><?php echo $years_arr[$m_i]; ?></th>
								
							<?php } ?>
							</tr>
							
							<tr class="primary">
								<th class="text-center"><div class="fix-text"><?= lang('Code');?></div></th>
								<th class="text-center"><div class="fix-text"><?= lang('Project');?></div></th>
								<th class="text-center"><div class="fix-text"><?= lang('Amount');?></div></th>
								<th class="text-center"><div class="fix-text"><?= lang('Period');?></div></th>
								<th class="text-center"><div class="fix-text"><?= lang('Start_Date');?></div></th>
								<th class="text-center"><div class="fix-text"><?= lang('End_Date');?></div></th>
								<th class="text-center"><div class="fix-text"><?= lang('Begin');?></div></th>
								
							<?php
							for($m_i = 0; $m_i < sizeOf($years_arr); $m_i++){ ?>
								<th style="text-align:left;"><div class="fix-text-moth"><?= lang("Jan"); ?></div></th>
								<th style="text-align:left;"><div class="fix-text-moth"><?= lang("Feb"); ?></div></th>
								<th style="text-align:left;"><div class="fix-text-moth"><?= lang("Mar"); ?></div></th>
								<th style="text-align:left;"><div class="fix-text-moth"><?= lang("Apr"); ?></div></th>
								<th style="text-align:left;"><div class="fix-text-moth"><?= lang("May"); ?></div></th>
								<th style="text-align:left;"><div class="fix-text-moth"><?= lang("Jun"); ?></div></th>
								<th style="text-align:left;"><div class="fix-text-moth"><?= lang("Jul"); ?></div></th>
								<th style="text-align:left;"><div class="fix-text-moth"><?= lang("Aug"); ?></div></th>
								<th style="text-align:left;"><div class="fix-text-moth"><?= lang("Sep"); ?></div></th>
								<th style="text-align:left;"><div class="fix-text-moth"><?= lang("Oct"); ?></div></th>
								<th style="text-align:left;"><div class="fix-text-moth"><?= lang("Nov"); ?></div></th>
								<th style="text-align:left;"><div class="fix-text-moth"><?= lang("Dec"); ?></div></th>
								
								<?php } ?>
								<th><div class="fix-text">Total</div></th>
								<th><div class="fix-text">Balance</div></th>
							</tr>
						</thead>
	
						<tbody>
								<?php 
								
								$sum_begin = 0;
								
								$sum_amount = 0;
								
								?>
								
									
								<?php
								$p = 1;
								$total_m = 0;
								$total_balance_m = 0;
								$total_per = 0;
								$total_balance_per = 0;
								$sum_arr = array();
								print_r(sizeOf($new_billers));
								foreach ($new_billers as $biller1) {
									?>
									<tr>
										<td><div class="fix-text"><?=$biller1->code?></div></td>
										<td><div class="fix-text"><?=$biller1->company?></div></td>
										<td><div class="fix-text"><?= $this->erp->formatDecimal($biller1->amount)?></div></td>
										<td><div class="fix-text"><?=$biller1->period?></div></td>
										<td><div class="fix-text"><?=$this->erp->hrsd($biller1->start_date)?></div></td>
										<td>
											<div class="fix-text"><?=$this->erp->hrsd($biller1->end_date)?></div>
										</td>
										<td><div class="fix-text"><?= $this->erp->formatDecimal(abs($biller1->begining_balance)) ?></div></td>
									<?php
									$f_begin += $biller1->begining_balance;
									$sum_begin += abs($biller1->begining_balance);
									for($m_i = 0; $m_i < sizeOf($years_arr); $m_i++){
										$from_date_m = date('m-d', strtotime($start));
										$from_date = date('Y-m-d H:m', strtotime($years_arr[$m_i] . '-' . $from_date_m . ' 00:00'));
										//$year_m = date('Y', strtotime($years_arr[$m_i]));
										if($p == sizeOf($years_arr)){
											$to_date_m = date('m-d', strtotime($end));
										}else{
											$to_date_m = date('12-t');
										}
										$to_date = date('Y-m-d H:m', strtotime($years_arr[$m_i] . '-' . $to_date_m . ' 23:59'));

										$monthlyIncomesYear = $this->accounts_model->getMonthlyIncomes($acc_setting->default_sale_discount,'40',$from_date,$to_date,$biller1->id);
										
										$first_i = 1;
										$biller_id_m = '';
										//$this->erp->print_arrays($monthlyIncomesYear->result());
										
										$sum_jan = 0;
										$sum_feb = 0;
										$sum_mar = 0;
										$sum_apr = 0;
										$sum_may = 0;
										$sum_jun = 0;
										$sum_jul = 0;
										$sum_aug = 0;
										$sum_sep = 0;
										$sum_oct = 0;
										$sum_nov = 0;
										$sum_dec = 0;
										$total = 0;
										$sum_total = 0;
										$sum_balance = 0;
										
										foreach($monthlyIncomesYear->result() as $project){
											if($project->year == $years_arr[$m_i]){
												$total = $project->begining_balance + $project->jan +
														$project->feb + $project->mar +
														$project->apr + $project->may +
														$project->jun + $project->jul +
														$project->aug + $project->sep +
														$project->oct + $project->nov +
														$project->dec;
												
												if($biller_id_m != $project->biller_id){
											?>
											
											<td><div class="fix-text-moth"><?=number_format(abs($project->jan), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->feb), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->mar), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->apr), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->may), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->jun), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->jul), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->aug), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->sep), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->oct), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->nov), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->dec), 2)?></div></td>
											
											<!--
											<td><div class="fix-text-moth"><b><?= $this->erp->formatDecimal(abs($total))?></b></div></td>
											<td style="border-right:1px solid #000 !important;"><div class="fix-text-moth"><b><?=$this->erp->formatDecimal($project->total_amount - abs($total))?></b></div></td>
											-->
											
											<?php }else{ ?>
											
											<td><div class="fix-text-moth"><?=number_format(abs($project->jan), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->feb), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->mar), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->apr), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->may), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->jun), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->jul), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->aug), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->sep), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->oct), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->nov), 2)?></div></td>
											<td><div class="fix-text-moth"><?=number_format(abs($project->dec), 2)?></div></td>
											
											<!--
											<td><div class="fix-text-moth"><b><?= $this->erp->formatDecimal(abs($total))?></b></div></td>
											<td style="border-right:1px solid #000 !important;"><div class="fix-text-moth"><b><?=$this->erp->formatDecimal($project->total_amount - abs($total))?></b></div></td>
											-->
											
											<?php 
											}
												$sum_amount += abs($project->total_amount);
												$sum_jan += abs($project->jan);
												$sum_feb += abs($project->feb);
												$sum_mar += abs($project->mar);
												$sum_apr += abs($project->apr);
												$sum_may += abs($project->may);
												$sum_jun += abs($project->jun);
												$sum_jul += abs($project->jul);
												$sum_aug += abs($project->aug);
												$sum_sep += abs($project->sep);
												$sum_oct += abs($project->oct);
												$sum_nov += abs($project->nov);
												$sum_dec += abs($project->dec);
												
												$total_per += abs($project->jan) +
														abs($project->feb) + abs($project->mar) +
														abs($project->apr) + abs($project->may) +
														abs($project->jun) + abs($project->jul) +
														abs($project->aug) + abs($project->sep) +
														abs($project->oct) + abs($project->nov) +
														abs($project->dec);
												
												$sum_total += abs($project->total);
												$sum_balance += $project->total_amount - abs($project->total);
												
											}
											$biller_id_m = $project->biller_id;
											$first_i++;
										}
										
										$sum_arr[] = array(
											'year' => $years_arr[$m_i],
											'jan' => $sum_jan,
											'feb' => $sum_feb,
											'mar' => $sum_mar,
											'apr' => $sum_apr,
											'may' => $sum_may,
											'jun' => $sum_jun,
											'jul' => $sum_jul,
											'aug' => $sum_aug,
											'sep' => $sum_sep,
											'oct' => $sum_oct,
											'nov' => $sum_nov,
											'dec' => $sum_dec,
											'total' => $sum_total,
											'balance' => $sum_balance
										);
										
									$p++;
									}
									?>
									<td><div class="fix-text-moth"><b><?= $this->erp->formatDecimal(abs($total_per))?></b></div></td>
											<td style="border-right:1px solid #000 !important;"><div class="fix-text-moth"><b><?=$this->erp->formatDecimal($project->total_amount - abs($total_per))?></b></div></td>
									
									</tr>
									<?php
									$total_per = 0;
								}
								?>
							<tr>
							
							</tr>
							
							<tr class="active">
								<th><div class="fix-text-moth"></div></th>
								<th><div class="fix-text-moth"></div></th>
								<th><div class="fix-text"><?=number_format($sum_amount,2)?></div></th>
								<th colspan="3"></th>
								<th><?= $this->erp->formatDecimal($f_begin) ?></th>
							<?php
							
							for($m_i = 0; $m_i < sizeOf($years_arr); $m_i++){ 
								
								$jan_a = 0;
								$feb_a = 0;
								$mar_a = 0;
								$apr_a = 0;
								$may_a = 0;
								$jun_a = 0;
								$jul_a = 0;
								$aug_a = 0;
								$sep_a = 0;
								$oct_a = 0;
								$nov_a = 0;
								$dec_a = 0;
								$total_a = 0;
								$balance = 0;
								foreach($sum_arr as $arr){
									if($arr['year'] == $years_arr[$m_i]){
										$jan_a += $arr['jan'];
										$feb_a += $arr['feb'];
										$mar_a += $arr['mar'];
										$apr_a += $arr['apr'];
										$may_a += $arr['may'];
										$jun_a += $arr['jun'];
										$jul_a += $arr['jul'];
										$aug_a += $arr['aug'];
										$sep_a += $arr['sep'];
										$oct_a += $arr['oct'];
										$nov_a += $arr['nov'];
										$dec_a += $arr['dec'];
										$total_a += $arr['total'];
										$balance += $arr['balance'];
									}
								}
								?>
								<th><div class="fix-text-moth"><?=number_format($jan_a,2)?></div></th>
								<th><div class="fix-text-moth"><?=number_format($feb_a,2)?></div></th>
								<th><div class="fix-text-moth"><?=number_format($mar_a,2)?></div></th>
								<th><div class="fix-text-moth"><?=number_format($apr_a,2)?></div></th>
								<th><div class="fix-text-moth"><?=number_format($may_a,2)?></div></th>
								<th><div class="fix-text-moth"><?=number_format($jun_a,2)?></div></th>
								<th><div class="fix-text-moth"><?=number_format($jul_a,2)?></div></th>
								<th><div class="fix-text-moth"><?=number_format($aug_a,2)?></div></th>
								<th><div class="fix-text-moth"><?=number_format($sep_a,2)?></div></th>
								<th><div class="fix-text-moth"><?=number_format($oct_a,2)?></div></th>
								<th><div class="fix-text-moth"><?=number_format($nov_a,2)?></div></th>
								<th><div class="fix-text-moth"><?=number_format($dec_a,2)?></div></th>
								
							<?php 
								} ?>
								<th><div class="fix-text"><?=number_format($sum_total,2)?></div></th>
								<th><div class="fix-text"><?=number_format($sum_balance,2)?></div></th>
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
				var url = "<?php echo site_url('reports/income_statement_by_project/'.$start.'/'.$end.'/0/0') ?>" + '/' + encodeURIComponent(billers);
				window.location.href = "<?=site_url('reports/income_statement_by_project/'. $start .'/'.$end.'/0/0/')?>" + '/' + encodedName;
			}
			
			if(hasCheck == false){
				bootbox.alert('Please select project first!');
				return false;
			}
			return false;
		});
		
		$(document).on('focus','.date-year', function(t) {
			$(this).datetimepicker({
				format: "yyyy",
				startView: 'decade',
				minView: 'decade',
				viewSelect: 'decade',
				autoclose: true,
			});
		});
		
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/income_statement_by_project/'.$start. '/' . $end . '/pdf/0/'.$biller_id_no_sep)?>";
            return false;
        });
		
		$('#xls').click(function (event) {
            event.preventDefault();
			window.location.href = "<?=site_url('reports/income_statement_by_project/'.$start. '/' . $end . '/0/xls/'.$biller_id_no_sep)?>";
			
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