<?php
	$v = "";
	/* if($this->input->post('name')){
	  $v .= "&product=".$this->input->post('product');
	  } */
	
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	$total_vat_input=null;
	$total_vat_output=null;
?>

<script type="text/javascript">
   $(document).ready(function () {
	   $(document).on('focus','.date-year', function(t) {
			$(this).datetimepicker({
				format: "yyyy",
				startView: 'decade',
				minView: 'decade',
				viewSelect: 'decade',
				autoclose: true,
			});
		});
		/*$('.months').datetimepicker({format: "mm/yyyy", startView:3, minViewMode:2,maxViewMode:1, todayBtn: 1, autoclose:true});
		*/
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });	
		/*
		$('.months')
		.datetimepicker()
		.on('changeMonth', function(ev){
			$('#datetimepicker').datetimepicker('update');
			$('.months').datetimepicker('hide');
		});
         */
		 $('.months').datetimepicker({format: "mm/yyyy", fontAwesome: true, language: 'erp', todayBtn: 1, autoclose: 1, minView: 2, startView:3,maxViewMode:2 });
		$(document).on('focus','.months', function(t) {
			$(this).datetimepicker({format:"mm/yyyy", fontAwesome: true, todayBtn: 1, autoclose: 1, minView: 2, startView:3,maxViewMode:2 });
		});
		 
		 
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
		$("#excel").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url('Sales/getSalesAll/0/xls/')?>";
			return false;
		});
		$('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('Sales/getSalesAll/pdf/?v=1'.$v)?>";
            return false;
        });
    });
</script>
<?php if ($Owner) {
	    echo form_open('sales/sale_actions', 'id="action-form"');
	}
?>
<style>
	.tr_c td{text-align:center;}
	.t_c{text-align:center;}
	.t_r{text-align:right;}
	
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-heart"></i><?=lang('vat_gov_tax_report');?>
        </h2>
		<div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>">
                        <i class="icon fa fa-toggle-up"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>">
                        <i class="icon fa fa-toggle-down"></i>
                    </a>
                </li>
            </ul>
        </div>
			<!--
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a href="<?=site_url('sales/add')?>">
                                <i class="fa fa-plus-circle"></i> <?=lang('add_sale')?>
                            </a>
                        </li>
						<?php if ($Owner || $Admin) { ?>
							<li>
								<a href="#" id="excel" data-action="export_excel">
									<i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
								</a>
							</li>
							<li>
								<a href="#" id="pdf" data-action="export_pdf">
									<i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>
								</a>
							</li>
							
							<li>
								<a href="<?= site_url('sales/sale_by_csv'); ?>">
									<i class="fa fa-plus-circle"></i>
									<span class="text"> <?= lang('add_sale_by_csv'); ?></span>
								</a>
							</li>
						<?php }else{ ?>
							<?php if($GP['sales-export']) { ?>
								<li>
									<a href="#" id="excel" data-action="export_excel">
										<i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
									</a>
								</li>
								<li>
									<a href="#" id="pdf" data-action="export_pdf">
										<i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>
									</a>
								</li>
							<?php }?>
							
							<?php if($GP['sales-import']) { ?>
								<li>
									<a href="<?= site_url('sales/sale_by_csv'); ?>">
										<i class="fa fa-plus-circle"></i>
										<span class="text"> <?= lang('add_sale_by_csv'); ?></span>
									</a>
								</li>
							<?php }?>
						<?php }?>
                        <li>
                            <a href="#" id="combine" data-action="combine">
                                <i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#" class="bpo"
                            title="<?=$this->lang->line("delete_sales")?>"
                            data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"
                            data-html="true" data-placement="left">
                            <i class="fa fa-trash-o"></i> <?=lang('delete_sales')?>
                        </a>
                    </li>
                    </ul>
                </li>
                <?php if (!empty($warehouses)) {
                    ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?=lang("warehouses")?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?=site_url('sales')?>"><i class="fa fa-building-o"></i> <?=lang('all_warehouses')?></a></li>
                            <li class="divider"></li>
                            <?php
                            	foreach ($warehouses as $warehouse) {
                            	        echo '<li><a href="' . site_url('sales/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            	    }
                                ?>
                        </ul>
                    </li>
                <?php }
                ?>
            </ul>
        </div>
			-->
	</div>
	<?php if ($Owner) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?>
<?php }
?>  
	<div class="box-content">
        <div class="row">
            <div class="col-lg-12">
					<?php
					$month =date('m');
					$year =date('Y');
					?>
                <p class="introtext"><?=lang('list_results');?></p>
				<div id="form">

                    <?php echo form_open("taxes_reports/vat_gov_tax_report"); ?>
                    <div class="row">			
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("month", "month"); ?>
                                <?php 
								$months = array(
											'01' => 'January',
											'02' => 'February',
											'03' => 'March',
											'04' => 'April',
											'05' => 'May',
											'06' => 'June',
											'07' => 'July',
											'08' => 'August',
											'09' => 'September',
											'10' => 'October',
											'11' => 'November',
											'12' => 'December',
										);
								echo form_dropdown('month', $months, (isset($_POST['month']) ? $_POST['month'] : $month), 'id="month" class="form-control"') ?>
                            </div>
                        </div>
						
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("year", "year"); ?>
                                <?php echo form_input('year', (isset($_POST['year']) ? $_POST['year'] : $year), 'class="form-control date-year" id="year"'); ?>
                            </div>
                        </div>  
						<div class="col-md-4">
							<div class="form-group">
								<?= lang("date", "create_date"); ?>
								<?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : date('d/m/Y h:i')), 'class="form-control input-tip datetime" id="create_date" required="required"'); ?>
							</div>
						</div>
                    </div>
					
                    <div class="form-group">
                        <div class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <div class="clearfix"></div>
                <div class="table-responsive">
				
				
				
                    <div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">
						<table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
							<tr class="warning">
								<td>
									<?= lang('vat_credit_carried_forward') ?> : <span class="totals_val pull-right" id="titems">
									<?=$this->erp->formatMoney($previous_vat);?> $</span>
									<input type="hidden" value="<?=abs(round($previous_vat,4))?>" id="vat_credit" name="vat_credit" >
								</td>
							</tr>
						</table>
					</div>
					
					<p><?=lang('vat_input','vat_input')?></p>
					<div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">
						<p><?=lang('vat_name')?> : <?=lang('vat_input')?></p>
						<table class="table table-bordered " style="margin-bottom:0;background-color:white;font-weight:bold;">
							<tr class="warning tr_c">
								<td><?= lang('ref_number') ?> 	</td>
								<td><?= lang('supplier') ?> 		</td>
								<td><?= lang('location') ?> 		</td>
								<td><?= lang('date') ?> 			</td>
								<td><?= lang('tax') ?>				</td>
							</tr>
							<?php
							if($vat_input){
								foreach($vat_input->result() as $row){
									
										echo '
										<tr class="" style="font-weight: normal;">
											<td class="t_c">'.$row->reference_no.'</td>
											<td class="t_c">'.$row->name.'</td>	
											<td class="t_c">'.$row->journal_location.'</td>	
											
											<td class="t_c">'.$this->erp->hrsd($row->issuedate).'</td>
											<td class="t_r">'.$this->erp->formatMoney($row->amount_tax_declare).' $</td>
										</tr>
										';
									$total_vat_input+=$row->amount_tax_declare;
								}
							}
								
								echo '
									<tr>
										<td colspan="4" class="t_r">Total Tax</td>
										<td class="t_r">'.$this->erp->formatMoney(isset($total_vat_input)?$total_vat_input:0).' $</td>
										<input type="hidden" value="'.$total_vat_input.'" id="vat_input" name="vat_input" >
									</tr>
								';
							?>
						</table>
					</div>
					
					<p><?=lang('vat_output','vat_output')?></p>
					<div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">
						<p><?=lang('vat_name')?> : <?=lang('vat_output')?></p>
						<table class="table table-bordered " style="margin-bottom:0;background-color:white;font-weight:bold;">
							<tr class="warning tr_c">
								<td><?= lang('ref_number') ?> 	</td>
								<td><?= lang('customer') ?> 		</td>
								<td><?= lang('location') ?> 		</td>
								<td><?= lang('date') ?> 			</td>
								<td><?= lang('tax') ?>				</td>
							</tr>
							<?php
							if($vat_output){
								foreach($vat_output->result() as $row){
									
										echo '
										<tr class="" style="font-weight: normal;">
											<td class="t_c">'.$row->referent_no.'</td>
											<td class="t_c">'.$row->customer_name.'</td>	
											<td class="t_c">'.$row->journal_location.'</td>									
											<td class="t_c">'.$this->erp->hrsd($row->issuedate).'</td>
											<td class="t_r">'.$this->erp->formatMoney($row->amount_tax_declare).' $</td>
										</tr>
										';
										$total_vat_output+=$row->amount_tax_declare;
								}
							}								
								echo '
									<tr>
										<td colspan="4" class="t_r">Total Tax</td>
										<td class="t_r">'.$this->erp->formatMoney(isset($total_vat_output)?$total_vat_output:0).' $</td>
										<input type="hidden" value="'.$total_vat_output.'" id="vat_output" name="vat_output" >
									</tr>
								';
							?>
							
						</table>
					</div>
					
					<div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">
						<table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
							<tr class="warning">
								<td class="t_c"><?php  
									$vat_forward=abs(round((($previous_vat+$total_vat_input)-$total_vat_output),4));
									if($previous_vat+$total_vat_input<=$total_vat_output){
										echo lang('vat_payable_is'); 
										echo '<input type="hidden" value="'.($vat_forward).'" id="vat_payable" name="vat_payable" >';
										echo '<input type="hidden" value="payable" id="payable" name="vat_payable" >';
									}else{
										echo lang('vat_credit_carried_forward_is'); 
										echo '<input type="hidden" value="'.($vat_forward).'" id="vat_credit_f" name="vat_credit_f" >';
									}	?>  <span>
								<?=$this->erp->formatMoney(abs($vat_forward));?> $
								</span></td>
							</tr>
						</table>
					</div>
					
                </div>
					<center>
						<button class="btn btn-primary" style="margin-top: 20px;" id="btn_save" type="button"><i aria-hidden="true" class="fa fa-floppy-o"></i> Save</button>
					</center>
			</div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function(){
		$('#btn_save').click(function(){
			var vat_credit	=$('#vat_credit').val();
			var vat_input	=$('#vat_input').val();
			var vat_output	=$('#vat_output').val();
			var vat_credit_f	=$('#vat_credit_f').val();
			var vat_payable	=$('#vat_payable').val();
			var payable	=$('#payable').val();
			var create_date	=$('#create_date').val();
			
				$.ajax({
					type: 'get',
					url: '<?= site_url('taxes_reports/vat_gov_tax_save'); ?>',
					dataType: "json",
					data: {
						create_date: create_date,
						vat_credit: vat_credit,
						vat_input: vat_input,
						vat_output: vat_output,
						vat_credit_f: vat_credit_f,
						payable: payable,
						vat_payable: vat_payable
					},
					success: function (data) {

					}
				});
			
		});
	});
</script>

