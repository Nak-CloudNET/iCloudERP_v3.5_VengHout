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
        $("#product").autocomplete({
            source: '<?= site_url('reports/suggestions'); ?>',
            select: function (event, ui) {
                $('#product_id').val(ui.item.id);
                //$(this).val(ui.item.label);
            },
            minLength: 1,
            autoFocus: false,
            delay: 300,
        });
    });
</script>
<style>
	.tables th{
		background-color:#dff0d8;
		text-align:center;
	}
	.tables th, .tables td{
		width:16% !important;
		padding:5px;
		border:1px solid #ddd;
	}
	.bold {
		font-weight:bold;
	}
</style>
<?php
	echo form_open('reports/sale_payment_report_actions', 'id="action-form"');
?>

<div class="box">
    <div class="box-header">
        <h2 class="blue">
			<i class="fa-fw fa fa-money"></i><?=lang('sale_payment_report'); ?>
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
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        
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
                    </ul>
                </li>
                
            </ul>
        </div>
    </div>

    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>

	<div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?=lang('list_results');?></p>
				<div id="form">

                    <?php echo form_open("reports/sale_payment_report"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("inv_start_date", "inv_start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ''), 'class="form-control date" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("inv_start_date", "inv_start_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ''), 'class="form-control date" id="end_date"'); ?>
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
                <div class="table-responsive">
					<?php
						foreach ($sale_invoice as $saleInvoice) {
					?>
						<table id="SLData" class="table table-bordered table-striped table-condensed">
							<thead>
								<tr>
									<th style="width: 3% !important; text-align: center;">
										<input class="checkbox checkth input-xs" type="checkbox" name="check"/>
									</th>
									<th><?php echo $this->lang->line("date"); ?></th>
									<th><?php echo $this->lang->line("reference_no"); ?></th>
									<th><?php echo $this->lang->line("project"); ?></th>
									<th><?php echo $this->lang->line("group_area"); ?></th>
									<th><?php echo $this->lang->line("customer"); ?></th>
									<th><?php echo $this->lang->line("saleman"); ?></th>
									<th><?php echo $this->lang->line("amount"); ?></th>
								</tr>
							</thead>
							<tbody>					
								<tr>
									<td style="width: 3% !important; text-align: center;">
										<input class="checkbox multi-select input-xs" type="checkbox" value="<?= $saleInvoice->id?>" name="val[]"/>
									</td>
									<td><?= $this->erp->hrsd($saleInvoice->date)?></td>
									<td><?= $saleInvoice->reference_no?></td>
									<td><?= $saleInvoice->biller?></td>
									<td><?= $saleInvoice->areas_group?></td>
									<td><?= $saleInvoice->customer?></td>
									<td><?= $saleInvoice->saleman?></td>
									<td><?= $this->erp->formatMoney($saleInvoice->grand_total)?></td>
								</tr>
								<tr>
									<td></td>
									<td colspan="7">
										<table class="tables" border="1">
											<thead>
												<tr >
													<th><?php echo $this->lang->line("date"); ?></th>
													<th><?php echo $this->lang->line("reference_no"); ?></th>
													<th><?php echo $this->lang->line("project"); ?></th>
													<th><?php echo $this->lang->line("amount"); ?></th>
													<th><?php echo $this->lang->line("paid_by"); ?></th>
													<th><?php echo $this->lang->line("created_by"); ?></th>
													<th><?php echo $this->lang->line("status"); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php
													$payment 	= $this->reports_model->getPaidInvoice($saleInvoice->id);
													$total_amt 	= 0;
													foreach($payment as $pay) {
												?>
													<tr>
														<td><?= $this->erp->hrsd($pay->date)?></td>
														<td><?= $pay->reference_no?></td>
														<td><?= $pay->company?></td>
														<td>
															<?php 
																if ($pay->type == 'returned') {
																	$amount = (-1) * $pay->amount;
																	echo $this->erp->formatMoney((-1) * $pay->amount);
																} else {
																	$amount = $pay->amount;
																	echo $this->erp->formatMoney($pay->amount);
																}
																$total_amt += $amount;
															?>
														</td>
														<td><?= $pay->paid_by?></td>
														<td><?= $pay->username?></td>
														<td>
															<?php 
																if($pay->type == 'returned'){
																	echo '<span class="label label-danger">'.lang($pay->type).'</span>';
																} else {
																	echo '<span class="label label-primary">'.lang($pay->type).'</span>';
																}
															?>
														</td>
													</tr>
												<?php
													}
												?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="3" class="text-right bold">Total</td>
													<td colspan="4" class="text-left bold"><?= $this->erp->formatMoney($total_amt); ?></td>
												</tr>
											</tfoot>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					<?php
						}
					?>
                </div>
            </div>
        </div>
    </div>
</div>