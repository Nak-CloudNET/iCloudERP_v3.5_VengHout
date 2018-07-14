<?php
	$v = "";
	if ($this->input->post('submit_sale_report')) {
		if ($this->input->post('customer')) {
				$v .= "&customer=" . $this->input->post('customer');
		}
		if ($this->input->post('serial')) {
				$v .= "&serial=" . $this->input->post('serial');
		}
		if ($this->input->post('start_date')) {
				$v .= "&start_date=" . $this->input->post('start_date');
		}
		if ($this->input->post('end_date')) {
				$v .= "&end_date=" . $this->input->post('end_date');
		}	
	}
	$warehouse_id = str_replace(',', '-', $warehouse_id);
?>

<script>
    $(document).ready(function () {
        var oTable = $('#CusData').dataTable({
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('sales/getCustomerBalance/'.($warehouse_id ? $warehouse_id : '')).'/?v=1'.$v ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"bSortable": false, "mRender": checkbox}, null, null, null, null, {
                "mRender": formatQuantity,
                "bSearchable": false
            }, {"mRender": currencyFormat, "bSearchable": false}, {
                "mRender": currencyFormat,
                "bSearchable": false
            }, {"mRender": currencyFormat, "bSearchable": false}, {"mRender": currencyFormat, "bSearchable": false}, {"mRender": currencyFormat, "bSearchable": false}, {"mRender": currencyFormat, "bSearchable": false}, {"bSortable": false}],
			"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
				var tSales = 0, tAmount = 0, tReturn = 0, tPaid = 0, balance = 0, tDeposit = 0, tDiscount = 0;
				for (var i = 0; i < aaData.length; i++) {
					tSales += parseFloat(aaData[aiDisplay[i]][5]);
					tAmount += parseFloat(aaData[aiDisplay[i]][6]);
					tReturn += parseFloat(aaData[aiDisplay[i]][7]);
					tPaid += parseFloat(aaData[aiDisplay[i]][8]);
					tDeposit += parseFloat(aaData[aiDisplay[i]][9]);
					tDiscount += parseFloat(aaData[aiDisplay[i]][10]);
					balance += parseFloat(aaData[aiDisplay[i]][11]);
				}
				var nCells = nRow.getElementsByTagName('th');
				nCells[5].innerHTML = formatQuantity(parseFloat(tSales));
				nCells[6].innerHTML = currencyFormat(parseFloat(tAmount));
				nCells[7].innerHTML = currencyFormat(parseFloat(tReturn));
				nCells[8].innerHTML = currencyFormat(parseFloat(tPaid));
				nCells[9].innerHTML = currencyFormat(parseFloat(tDeposit));
				nCells[10].innerHTML = currencyFormat(parseFloat(tDiscount));
				nCells[11].innerHTML = currencyFormat(parseFloat(balance));
			}
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('company');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('name');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('phone');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('email_address');?>]", filter_type: "text", data: []},
        ], "footer");
    });
	
	$(document).ready(function(){
		$('#form').hide();
		$('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
		
	});
</script>
<?php 
   echo form_open('reports/customers_actions/'.($warehouse_id ? $warehouse_id : ''), 'id="action-form"');
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('customer_balance_list'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
								class="icon fa fa-toggle-up"></i></a></li>
				<li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
								class="icon fa fa-toggle-down"></i></a></li>
                <?php if ($Owner || $Admin || $GP['sales-export']) { ?>
    				<li class="dropdown"><a href="#" id="pdf" data-action="export_pdf"  class="tip" title="<?= lang('download_pdf') ?>"><i
                                class="icon fa fa-file-pdf-o"></i></a></li>
                    <li class="dropdown"><a href="#" id="excel" data-action="export_excel"  class="tip" title="<?= lang('download_xls') ?>"><i
                                class="icon fa fa-file-excel-o"></i></a></li>
                    <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                            class="icon fa fa-file-picture-o"></i></a></li>
				<?php } ?>
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
				<p class="introtext"><?= lang('list_results'); ?></p>
				 <div id="form">

                            <?php echo form_open("sales/customer_balance"); ?>
                            <div class="row">                            
								<div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="biller"><?= lang("customer"); ?></label>
                                        <?php
                                        $cus["0"] = lang('all');
                                        foreach ($customers as $customer) {
                                            $cus[$customer->id] =  $customer->name;
                                        }
                                        echo form_dropdown('customer', $cus, (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"');
                                        ?>
                                    </div>
                                </div>
                                <?php if($this->Settings->product_serial) { ?>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang('serial_no', 'serial'); ?>
                                            <?= form_input('serial', '', 'class="form-control tip" id="serial"'); ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang("start_date", "start_date"); ?>
                                            <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang("end_date", "end_date"); ?>
                                            <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                                        </div>
                                    </div>
                            </div>
                                <div class="form-group">
                                    <div
                                    class="controls"> <?php echo form_submit('submit_sale_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                                </div>
                                <?php echo form_close(); ?>

                        </div>               

                <div class="table-responsive">
                    <table id="CusData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-condensed table-hover table-striped reports-table">
                        <thead>
							<tr class="primary">
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="check"/>
								</th>
								<th><?= lang("company"); ?></th>
								<th><?= lang("name"); ?></th>
								<th><?= lang("phone"); ?></th>
								<th><?= lang("email_address"); ?></th>
								<th><?= lang("total_sales"); ?></th>
								<th><?= lang("total_amount"); ?></th>
								<th><?= lang("total_return"); ?></th>
								<th><?= lang("total_paid"); ?></th>
								<th><?= lang("total_deposit"); ?></th>
								<th><?= lang("total_discount"); ?></th>
								<th><?= lang("total_balance"); ?></th>
								<th style="width:85px;"><?= lang("actions"); ?></th>
							</tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="9" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
							<tr class="active">
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="check"/>
								</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th class="text-center"></th>
								<th class="text-center"></th>
								<th class="text-center"></th>
								<th class="text-center"></th>
								<th class="text-center"></th>
								<th class="text-center"></th>
								<th class="text-center"></th>
								<th style="width:85px; text-align: center;"><?= lang("actions"); ?></th>
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