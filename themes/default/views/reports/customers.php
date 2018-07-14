<?php
	$v = "&customer=" . isset($user_id);
	if ($this->input->post('submit_sale_report')) {
		if ($this->input->post('biller')) {
				$v .= "&biller=" . $this->input->post('biller');
		}
		if ($this->input->post('warehouse')) {
				$v .= "&warehouse=" . $this->input->post('warehouse');
		}
		if ($this->input->post('user')) {
				$v .= "&user=" . $this->input->post('user');
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
		if ($this->input->post('group_area')) {
				$v .= "&group_area=" . $this->input->post('group_area');
		}
		if ($this->input->post('customer_group')) {
				$v .= "&customer_group=" . $this->input->post('customer_group');
		}
	}
?>

<script>
    $(document).ready(function () {
        var oTable = $('#CusData').dataTable({
            //"aaSorting": [[0, "asc"], [1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getCustomersReport').'/?v=1'.$v ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"bSortable": false, "mRender": checkbox}, null, null, null, null, null, {
                "mRender": formatQuantity,
                "bSearchable": false
            }, {"mRender": currencyFormat, "bSearchable": false}, {
                "mRender": currencyFormat,
                "bSearchable": false
            }, {"mRender": currencyFormat, "bSearchable": false}, {"bSortable": false}],
			"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
				var tSales = 0, tAmount = 0, paid = 0, balance = 0;
				for (var i = 0; i < aaData.length; i++) {
					tSales += parseFloat(aaData[aiDisplay[i]][6]);
					tAmount += parseFloat(aaData[aiDisplay[i]][7]);
					paid += parseFloat(aaData[aiDisplay[i]][8]);
					balance += parseFloat(aaData[aiDisplay[i]][9]);
				}
				var nCells = nRow.getElementsByTagName('th');
				nCells[6].innerHTML = currencyFormat(parseFloat(tSales));
				nCells[7].innerHTML = currencyFormat(parseFloat(tAmount));
				nCells[8].innerHTML = currencyFormat(parseFloat(paid));
				nCells[9].innerHTML = currencyFormat(parseFloat(balance));
			}
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('customer_group');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('group_area');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('company');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('name');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('phone');?>]", filter_type: "text", data: []},
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
   // echo form_open('reports/customers_actions'.($warehouse_id ? '/'.$warehouse_id : ''), 'id="action-form"');
   echo form_open('reports/customers_actions' ,'id="action-form"');
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('customers'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
								class="icon fa fa-toggle-up"></i></a></li>
				<li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
								class="icon fa fa-toggle-down"></i></a></li>
				<li class="dropdown"><a href="#" id="pdf" data-action="export_pdf"  class="tip" title="<?= lang('download_pdf') ?>"><i
                            class="icon fa fa-file-pdf-o"></i></a></li>
                <li class="dropdown"><a href="#" id="excel" data-action="export_excel"  class="tip" title="<?= lang('download_xls') ?>"><i
                            class="icon fa fa-file-excel-o"></i></a></li>
                <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                            class="icon fa fa-file-picture-o"></i></a></li>
				
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
				<p class="introtext"><?= lang('view_report_customer'); ?></p>
				 <div id="form">

                            <?php echo form_open("reports/customers/" . isset($user_id)); ?>
                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="user"><?= lang("created_by"); ?></label>
                                        <?php
                                        $us[""] = "";
                                        foreach ($users as $user) {
                                            $us[$user->id] = $user->first_name . " " . $user->last_name;
                                        }
                                        echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="biller"><?= lang("biller"); ?></label>
                                        <?php
                                        $bl[""] = "";
                                        foreach ($billers as $biller) {
                                            $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                        }
                                        echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                                        ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                        <?php
                                        $wh[""] = "";
                                        foreach ($warehouses as $warehouse) {
                                            $wh[$warehouse->id] = $warehouse->name;
                                        }
                                        echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'class="form-control" id="warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
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
											<label class="control-label" for="biller"><?= lang("customer_group"); ?></label>
											<?php
											$gr["0"] = lang("all");
											foreach ($customer_groups as $group) {
												$gr[$group->id] = $group->name;
											}
											echo form_dropdown('customer_group', $gr, (isset($_POST['customer_group']) ? $_POST['customer_group'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer_group") . '"');
											?>
										</div>
									</div>
									
									<div class="col-sm-4">
										<div class="form-group">
											<label class="control-label" for="biller"><?= lang("group_area"); ?></label>
											<?php
											$ar["0"] = lang("all");
											foreach ($areas as $area) {
												$ar[$area->areas_g_code] = $area->areas_group;
											}
											echo form_dropdown('group_area', $ar, (isset($_POST['group_area']) ? $_POST['group_area'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("group_area") . '"');
											?>
										</div>
									</div>
									
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang("start_date", "start_date"); ?>
                                            <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ''), 'class="form-control datetime" id="start_date"'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang("end_date", "end_date"); ?>
                                            <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ''), 'class="form-control datetime" id="end_date"'); ?>
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
								<th><?= lang("customer_group"); ?></th>
								<th><?= lang("group_area"); ?></th>
								<th><?= lang("company"); ?></th>
								<th><?= lang("name"); ?></th>
								<th><?= lang("phone"); ?></th>
								<th><?= lang("total_sales"); ?></th>
								<th><?= lang("total_amount"); ?></th>
								<th><?= lang("paid"); ?></th>
								<th><?= lang("balance"); ?></th>
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
								<th></th>
								<th class="text-center"><?= lang("total_purchases"); ?></th>
								<th class="text-center"><?= lang("total_amount"); ?></th>
								<th class="text-center"><?= lang("paid"); ?></th>
								<th class="text-center"><?= lang("balance"); ?></th>
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
		/*
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getCustomers/pdf')?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getCustomers/0/xls')?>";
            return false;
        });
		*/
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