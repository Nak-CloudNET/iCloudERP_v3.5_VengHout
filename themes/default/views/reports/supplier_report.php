<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <div class="small-box padding1010 col-sm-4 bblue">
                    <h3><?= isset($purchases->total_amount) ? $this->erp->formatMoney($purchases->total_amount) : '0.00' ?></h3>

                    <p><?= lang('purchases_amount') ?></p>
                </div>
                <div class="small-box padding1010 col-sm-4 blightOrange">
                    <h3><?= isset($purchases->paid) ? $this->erp->formatMoney($purchases->paid) : '0.00' ?></h3>

                    <p><?= lang('total_paid') ?></p>
                </div>
                <div class="small-box padding1010 col-sm-4 borange">
                    <h3><?= (isset($purchases->total_amount) || isset($purchases->paid)) ? $this->erp->formatMoney($purchases->total_amount - $purchases->paid) : '0.00' ?></h3>

                    <p><?= lang('due_amount') ?></p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="small-box padding1010 bblue">
                            <div class="inner clearfix">
                                <a>
                                    <h3><?= $total_purchases ?></h3>

                                    <p><?= lang('total_purchases') ?></p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<ul id="myTab" class="nav nav-tabs">
    <li class=""><a href="#purcahses-con" class="tab-grey"><?= lang('purchases') ?></a></li>
    <li class=""><a href="#payments-con" class="tab-grey"><?= lang('payments') ?></a></li>
    <li class=""><a href="#purchase_order-con" class="tab-grey"><?= lang('purchase_order') ?></a></li>
	<li class=""><a href="#deposits-con" class="tab-grey"><?= lang('deposits') ?></a></li>
	<li class=""><a href="#top-products-con" class="tab-grey"><?= lang('products') ?></a></li>
	<li class=""><a href="#top-purchase_expend-con" class="tab-grey"><?= lang('purchase_expend') ?></a></li>
</ul>

<div class="tab-content">
    <div id="purcahses-con" class="tab-pane fade in">
        <?php
        $v = "&supplier=" . $user_id;
		$v .= "&biller_id=" . $biller_id;
        if ($this->input->post('submit_purchase_report')) {
            if ($this->input->post('biller')) {
                $v .= "&biller=" . $this->input->post('biller');
            }
            if ($this->input->post('warehouse')) {
                $v .= "&warehouse=" . $this->input->post('warehouse');
            }
            if ($this->input->post('user')) {
                $v .= "&user=" . $this->input->post('user');
            }
            if ($this->input->post('start_date')) {
                $v .= "&start_date=" . $this->input->post('start_date');
            }
            if ($this->input->post('end_date')) {
                $v .= "&end_date=" . $this->input->post('end_date');
            }
        }
        ?>
		
        <script>
        $(document).ready(function () {
            var oTable = $('#PoRData').dataTable({
                "aaSorting": [[0, "desc"]],
                "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "iDisplayLength": <?= $Settings->rows_per_page ?>,
                'bProcessing': true, 'bServerSide': true,
                'sAjaxSource': '<?= site_url('reports/getPurchasesReport2/?v=1' .$v) ?>',
                'fnServerData': function (sSource, aoData, fnCallback) {
                    aoData.push({
                        "name": "<?= $this->security->get_csrf_token_name() ?>",
                        "value": "<?= $this->security->get_csrf_hash() ?>"
                    });
                    $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                },
                "aoColumns": [{"bVisible": false },{"mRender": fld}, null, null, null,{
                    "bSearchable": false,
                    "mRender": pqFormatPurchaseReports
                }, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": row_status}],
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                    var gtotal = 0, disc = 0, paid = 0, balance = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        gtotal += parseFloat(aaData[aiDisplay[i]][6]);
                        disc += parseFloat(aaData[aiDisplay[i]][7]);
                        paid += parseFloat(aaData[aiDisplay[i]][8])?parseFloat(aaData[aiDisplay[i]][8]):0;
                        balance += parseFloat(aaData[aiDisplay[i]][9]);
                    }
                    var nCells = nRow.getElementsByTagName('th');
                    nCells[5].innerHTML = currencyFormat(parseFloat(gtotal));
                    nCells[6].innerHTML = currencyFormat(parseFloat(disc));
                    nCells[7].innerHTML = currencyFormat(parseFloat(paid));
                    nCells[8].innerHTML = currencyFormat(parseFloat(balance));
                }
            }).fnSetFilteringDelay().dtFilter([
                {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
                {column_number: 2, filter_default_label: "[<?=lang('po_no');?>]", filter_type: "text", data: []},
                {column_number: 3, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
                {column_number: 4, filter_default_label: "[<?=lang('warehouse');?>]", filter_type: "text", data: []},
                {column_number: 5, filter_default_label: "[<?=lang('supplier');?>]", filter_type: "text", data: []},
                {column_number: 10, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
            ], "footer");
        });
        </script>
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
        });
        </script>

        <div class="box purchases-table">
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-star nb"></i> <?= lang('purchases_report'); ?> <?php
                if ($this->input->post('start_date')) {
                    echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
                }
                ?></h2>

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
                            <a href="#" id="pdf_purchase" class="tip" title="<?= lang('download_pdf') ?>">
                                <i class="icon fa fa-file-pdf-o"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" id="xls_purchase" class="tip" title="<?= lang('download_xls') ?>">
                                <i class="icon fa fa-file-excel-o"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" id="image_purchase" class="tip" title="<?= lang('save_image') ?>">
                                <i class="icon fa fa-file-picture-o"></i>
                            </a>
                        </li>
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
									class="icon fa fa-building-o tip" data-placement="left"
									title="<?= lang("billers") ?>"></i></a>
							<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
								aria-labelledby="dLabel">
								<li><a href="<?= site_url('reports/supplier_report/' . $user_id) ?>"><i
											class="fa fa-building-o"></i> <?= lang('billers') ?></a></li>
								<li class="divider"></li>
								<?php
								foreach ($billers as $biller) {
									echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/supplier_report/' . $user_id . '/' . $biller->id) . '"><i class="fa fa-building"></i>' . $biller->company . '</a></li>';
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
                        <p class="introtext"><?= lang('customize_report'); ?></p>

                        <div id="form">

                            <?php echo form_open("reports/supplier_report/" . $user_id); ?>
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
                                class="controls"> <?php echo form_submit('submit_purchase_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                            </div>
                            <?php echo form_close(); ?>

                        </div>
                        <div class="clearfix"></div>

                        <div class="table-responsive">
                            <table id="PoRData"
                            class="table table-bordered table-hover table-striped table-condensed reports-table">
                                <thead>
                                    <tr>
    									<th></th>
                                        <th><?= lang("date"); ?></th>
                                        <th><?= lang("po_no"); ?></th>
                                        <th><?= lang("reference_no"); ?></th>
                                        <th><?= lang("warehouse"); ?></th>
                                        <th><?= lang("supplier"); ?></th>
                                        <th><?= lang("grand_total"); ?></th>
                                        <th><?= lang("discount"); ?></th>
                                        <th><?= lang("paid"); ?></th>
                                        <th><?= lang("balance"); ?></th>
                                        <th><?= lang("status"); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                                    </tr>
                                </tbody>
                                <tfoot class="dtFilter">
                                    <tr class="active">
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

	<div id="payments-con" class="tab-pane fade in">
		<?php
		$p = "&supplier=" . $user_id;
		$p .= "&biller_id=" . $biller_id;
		if ($this->input->post('submit_payment_report')) {
			if ($this->input->post('biller')) {
                $p .= "&biller=" . $this->input->post('biller');
            }
			if ($this->input->post('pay_user')) {
				$p .= "&user=" . $this->input->post('pay_user');
			}
			if ($this->input->post('pay_start_date')) {
				$p .= "&start_date=" . $this->input->post('pay_start_date');
			}
			if ($this->input->post('pay_end_date')) {
				$p .= "&end_date=" . $this->input->post('pay_end_date');
			}
			
		}
		?>
		<script>
		$(document).ready(function () {
			var pb = ['<?=lang('cash')?>', '<?=lang('CC')?>', '<?=lang('Cheque')?>', '<?=lang('paypal_pro')?>', '<?=lang('stripe')?>', '<?=lang('gift_card')?>'];

			function paid_by(x) {
				if (x == 'cash') {
					return pb[0];
				} else if (x == 'CC') {
					return pb[1];
				} else if (x == 'Cheque') {
					return pb[2];
				} else if (x == 'ppp') {
					return pb[3];
				} else if (x == 'stripe') {
					return pb[4];
				} else if (x == 'gift_card') {
					return pb[5];
				} else {
					return x;
				}
			}

			var oTable = $('#PayRData').dataTable({
				"aaSorting": [[0, "desc"]],
				"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
				"iDisplayLength": <?= $Settings->rows_per_page ?>,
				'bProcessing': true, 'bServerSide': true,
				'sAjaxSource': '<?= site_url('reports/getPaymentsReport/?v=1' . $p) ?>',
				'fnServerData': function (sSource, aoData, fnCallback) {
					aoData.push({
						"name": "<?= $this->security->get_csrf_token_name() ?>",
						"value": "<?= $this->security->get_csrf_hash() ?>"
					});
					$.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
				},
				"aoColumns": [{"bVisible": false }, {"mRender": fld}, null, {"bVisible": false }, null,null, {"mRender": paid_by, "bSearchable":false}, {"mRender": currencyFormat, "bSearchable":false}, {"mRender": currencyFormat, "bSearchable":false}, {"mRender": row_status}],
				'fnRowCallback': function (nRow, aData, iDisplayIndex) {
					var oSettings = oTable.fnSettings();
					nRow.className = "warning";
					return nRow;
				},
				"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
					var total = 0, disc = 0 ;
					for (var i = 0; i < aaData.length; i++) {
                        disc += parseFloat(aaData[aiDisplay[i]][7]);
                        total += parseFloat(aaData[aiDisplay[i]][8]);
					}
					var nCells = nRow.getElementsByTagName('th');
                    nCells[5].innerHTML = currencyFormat(parseFloat(disc));
                    nCells[6].innerHTML = currencyFormat(parseFloat(total));
				}
			}).fnSetFilteringDelay().dtFilter([
				{column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
				{column_number: 2, filter_default_label: "[<?=lang('payment_ref');?>]", filter_type: "text", data: []},
				{column_number: 4, filter_default_label: "[<?=lang('purchase_ref');?>]", filter_type: "text", data: []},
				{column_number: 5, filter_default_label: "[<?=lang('note');?>]", filter_type: "text", data: []},
				{column_number: 6, filter_default_label: "[<?=lang('paid_by');?>]", filter_type: "text", data: []},
				{column_number: 9, filter_default_label: "[<?=lang('type');?>]", filter_type: "text", data: []},
			], "footer");
		});
		</script>
		<script type="text/javascript">
		$(document).ready(function () {
			$('#payform').hide();
			$('.paytoggle_down').click(function () {
				$("#payform").slideDown();
				return false;
			});
			$('.paytoggle_up').click(function () {
				$("#payform").slideUp();
				return false;
			});
		});
		</script>

		<div class="box payments-table">
			<div class="box-header">
				<h2 class="blue"><i class="fa-fw fa fa-money nb"></i><?= lang('payments_report'); ?> <?php
				if ($this->input->post('start_date')) {
					echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
				}
				?></h2>

				<div class="box-icon">
					<ul class="btn-tasks">
						<li class="dropdown">
							<a href="#" class="paytoggle_up tip" title="<?= lang('hide_form') ?>">
								<i class="icon fa fa-toggle-up"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" class="paytoggle_down tip" title="<?= lang('show_form') ?>">
								<i class="icon fa fa-toggle-down"></i>
							</a>
						</li>
					</ul>
				</div>
				<div class="box-icon">
					<ul class="btn-tasks">
						<li class="dropdown">
							<a href="#" id="pdf1" class="tip" title="<?= lang('download_pdf') ?>">
								<i class="icon fa fa-file-pdf-o"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" id="xls1" class="tip" title="<?= lang('download_xls') ?>">
								<i class="icon fa fa-file-excel-o"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" id="image1" class="tip" title="<?= lang('save_image') ?>">
								<i class="icon fa fa-file-picture-o"></i>
							</a>
						</li>
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
									class="icon fa fa-building-o tip" data-placement="left"
									title="<?= lang("billers") ?>"></i></a>
							<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
								aria-labelledby="dLabel">
								<li><a href="<?= site_url('reports/supplier_report/' . $user_id) ?>"><i
											class="fa fa-building-o"></i> <?= lang('billers') ?></a></li>
								<li class="divider"></li>
								<?php
								foreach ($billers as $biller) {
									echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/supplier_report/'. $user_id . '/' . $biller->id) . "/#payments-con".'"><i class="fa fa-building"></i>' . $biller->company . '</a></li>';
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

						<p class="introtext"><?= lang('customize_report'); ?></p>

						<div id="payform">

							<?php echo form_open("reports/supplier_report/" . $user_id."/#payments-con"); ?>
							<div class="row">

								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label" for="user"><?= lang("created_by"); ?></label>
										<?php
										$us[""] = "";
										foreach ($users as $user) {
											$us[$user->id] = $user->first_name . " " . $user->last_name;
										}
										echo form_dropdown('pay_user', $us, (isset($_POST['pay_user']) ? $_POST['pay_user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
										?>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<?= lang("start_date", "start_date"); ?>
										<?php echo form_input('pay_start_date', (isset($_POST['pay_start_date']) ? $_POST['pay_start_date'] : ''), 'class="form-control date" id="start_date"'); ?>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<?= lang("end_date", "end_date"); ?>
										<?php echo form_input('pay_end_date', (isset($_POST['pay_end_date']) ? $_POST['pay_end_date'] :  ''), 'class="form-control date" id="end_date"'); ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div
								class="controls"> <?php echo form_submit('submit_payment_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
							</div>
							<?php echo form_close(); ?>

						</div>
						<div class="clearfix"></div>

						<div class="table-responsive">
							<table id="PayRData"
							class="table table-bordered table-hover table-striped table-condensed reports-table">

								<thead>
									<tr>
										<th></th>
										<th><?= lang("date"); ?></th>
										<th><?= lang("payment_ref"); ?></th>
										<th></th>
										<th><?= lang("purchase_ref"); ?></th>
										<th><?= lang("note"); ?></th>
										<th><?= lang("paid_by"); ?></th>
                                        <th><?= lang("discount"); ?></th>
                                        <th><?= lang("amount"); ?></th>
										<th><?= lang("type"); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
									</tr>
								</tbody>
								<tfoot class="dtFilter">
									<tr class="active">
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="purchase_order-con" class="tab-pane fade in">
		<?php 
			$p = "&supplier=" . $user_id;
			
		?>
		<script type="text/javascript">
		$(document).ready(function () {
			var oTable = $('#PurRData').dataTable({
				"aaSorting": [[0, "desc"]],
				"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
				"iDisplayLength": <?= $Settings->rows_per_page ?>,
				'bProcessing': true, 'bServerSide': true,
				'sAjaxSource': '<?=site_url('reports/getPurchaseOrder/?v=1' . $p)?>',
				'fnServerData': function (sSource, aoData, fnCallback) {
					aoData.push({
						"name": "<?= $this->security->get_csrf_token_name() ?>",
						"value": "<?= $this->security->get_csrf_hash() ?>"
					});
					$.ajax({
						'dataType': 'json',
						'type': 'POST',
						'url': sSource,
						'data': aoData,
						'success': fnCallback
					});
				},
				"aoColumns": [{"bSortable": false, "mRender": checkbox}, {"mRender": fld}, null, null, {"mRender": formatQuantity}, {"mRender": formatQuantity}, {"mRender": formatQuantity}, {"mRender": currencyFormat}],
				
				"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0, paid = 0, balance = 0, qty_order = 0, qty_received = 0, qty_balance = 0;
                for (var i = 0; i < aaData.length; i++) {
					qty_order += parseFloat(aaData[aiDisplay[i]][4]);
					qty_received += parseFloat(aaData[aiDisplay[i]][5]);
					qty_balance += parseFloat(aaData[aiDisplay[i]][6]);
					gtotal += parseFloat(aaData[aiDisplay[i]][7]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[4].innerHTML = currencyFormat(parseFloat(qty_order));
                nCells[5].innerHTML = currencyFormat(parseFloat(qty_received));
                nCells[6].innerHTML = currencyFormat(parseFloat(qty_balance));
                nCells[7].innerHTML = currencyFormat(parseFloat(gtotal));
				}
			
			}).fnSetFilteringDelay().dtFilter([
				{column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
				{column_number: 2, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
				{column_number: 3, filter_default_label: "[<?=lang('supplier');?>]", filter_type: "text", data: []},
			], "footer");
		});
		</script>
		
		<div class="box purchase_order">
			<div class="box-header">
				<h2 class="blue"><i class="fa-fw fa fa-heart-o nb"></i><?=  lang('purchase_order'); ?>
				</h2>

				<div class="box-icon">
					<ul class="btn-tasks">
						<li class="dropdown">
							<a href="#" id="pdf_purchase_order" class="tip" title="<?= lang('download_pdf') ?>">
								<i class="icon fa fa-file-pdf-o"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" id="xls_purchase_order" class="tip" title="<?= lang('download_xls') ?>">
								<i class="icon fa fa-file-excel-o"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" id="image_purchase_order" class="tip image" title="<?= lang('save_image') ?>">
								<i class="icon fa fa-file-picture-o"></i>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="box-content">
				<div class="row">
					<div class="col-lg-12">
						<p class="introtext"><?php echo lang('list_results'); ?></p>

						<div class="table-responsive">
							<table id="PurRData" class="table table-bordered table-hover table-striped table-condensed">
								<thead>
									<tr>
										<th style="min-width:30px; width: 30px; text-align: center;">
											<input class="checkbox checkth" type="checkbox" name="check"/>
										</th>
										<th><?= lang("date"); ?></th>
										<th><?= lang("reference_no"); ?></th>
										<th><?= lang("supplier"); ?></th>
										<th><?= lang("qty_po"); ?></th>
										<th><?= lang("quantity_received"); ?></th>
										<th><?= lang("quantity_balance"); ?></th>
										<th><?= lang("grand_total"); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="7"
										class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
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
										<th></th>
										<th></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="deposits-con" class="tab-pane fade in">
    	<?php
    		$v = "&supplier=" . $user_id;
    	?>
		<script>
		$(document).ready(function () {
			
			var oTable = $('#DepoData').dataTable({
					"aaSorting": [[1, "desc"]],
					"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
					"iDisplayLength": <?= $Settings->rows_per_page ?>,
					'bProcessing': true, 'bServerSide': true,
					'sAjaxSource': '<?= site_url('reports/getDepositsReport2/?v=1' .$v) ?>',
					'fnServerData': function (sSource, aoData, fnCallback) {
						aoData.push({
							"name": "<?= $this->security->get_csrf_token_name() ?>",
							"value": "<?= $this->security->get_csrf_hash() ?>"
						});
						$.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
					},
					
					"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
						var total_amount = 0;
						for (var i = 0; i < aaData.length; i++) {
							total_amount += parseFloat(aaData[aiDisplay[i]][3]);
						}
						var nCells = nRow.getElementsByTagName('th');
						nCells[3].innerHTML = currencyFormat(parseFloat(total_amount));
					}
					
			,"aoColumns": [null,{"mRender": fld},{"bSearchable":false}, {"mRender": currencyFormat}, null, null]
			}).fnSetFilteringDelay().dtFilter([
			{column_number: 0, filter_default_label: "[<?=lang('reference_no');?>", filter_type: "text", data: []},
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('Description');?>]", filter_type: "text", data: []},
			{column_number: 4, filter_default_label: "[<?=lang('paid_by');?>]", filter_type: "text", data: []},
			{column_number: 5, filter_default_label: "[<?=lang('create_by');?>]", filter_type: "text", data: []},
			
            
        ], "footer");
		});
		</script>
		<div class="box">
			<div class="box-header">
				<h2 class="blue"><i class="fa-fw fa fa-random nb"></i><?= lang('deposits'); ?>
				</h2>
				
			</div>
			<div class="box-content">
				<div class="row">
					<div class="col-lg-12">
						<p class="introtext"><?php echo lang('list_results'); ?></p>

						<div class="table-responsive">
							<table id="DepoData" class="table table-bordered table-hover table-striped">
								<thead>
								<tr class="primary">
									<th class="col-xs-3"><?= lang("reference_no"); ?></th>
									<th class="col-xs-3"><?= lang("date"); ?></th>
									<th class="col-xs-3"><?= lang("description"); ?></th>
									<th class="col-xs-2"><?= lang("amount"); ?></th>
									<th class="col-xs-3"><?= lang("paid_by"); ?></th>
									<th class="col-xs-3"><?= lang("created_by"); ?></th>
								</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
									</tr>
								</tbody>
								<tfoot class="dtFilter">
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	
	<div id="top-products-con" class="tab-pane fade in">
		<?php
			$v4 = "&supplier=" . $user_id;

			if ($this->input->post('sproduct')) {
				$v4 .= "&product2=" . $this->input->post('sproduct');
			}
			if ($this->input->post('reference_no')) {
				$v4 .= "&reference_no=" . $this->input->post('reference_no');
			}
			if ($this->input->post('category')) {
				$v4 .= "&category=" . $this->input->post('category');
			}
			if ($this->input->post('start_dates')) {
				$v4 .= "&start_date2=" . $this->input->post('start_dates');
			}
			if ($this->input->post('end_dates')) {
				$v4 .= "&end_date2=" . $this->input->post('end_dates');
			}
			
		?>
		<script>
			$(document).ready(function () {
				function spb(x) {
					v = x.split('__');
					return '('+formatQuantity2(v[0])+') <strong>'+formatMoney(v[1])+'</strong>';
				}
				var oTable = $('#tPRData').dataTable({
					"aaSorting": [[4, "desc"]],
					"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
					"iDisplayLength": <?= $Settings->rows_per_page ?>,
					'bProcessing': true, 'bServerSide': true,
					'sAjaxSource': '<?= site_url('reports/getPurchasedReport4/?v=1'.$v4) ?>',
					'fnServerData': function (sSource, aoData, fnCallback) {
						aoData.push({
							"name": "<?= $this->security->get_csrf_token_name() ?>",
							"value": "<?= $this->security->get_csrf_hash() ?>"
						});
						$.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
					},
					"aoColumns": [
						{"bSortable": false, "mRender": checkbox},
						{"mRender": fld}, 
						null, 
						null, 
						{"mRender": currencyFormat},
						null,
						{"mRender": currencyFormat}],
						"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
						var qty = 0;
						var amount = 0;
						for (var i = 0; i < aaData.length; i++) {
							qty += parseFloat(aaData[aiDisplay[i]][4]);
							amount += parseFloat(aaData[aiDisplay[i]][6]);
						}
						var nCells = nRow.getElementsByTagName('th');
						nCells[4].innerHTML = currencyFormat(parseFloat(qty));
						nCells[6].innerHTML = currencyFormat(parseFloat(amount));
					}
				}).fnSetFilteringDelay().dtFilter([
					{column_number: 1, filter_default_label: "[<?=lang('date');?>]", filter_type: "text", data: []},
					{column_number: 2, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
					{column_number: 3, filter_default_label: "[<?=lang('product_name');?>]", filter_type: "text", data: []},
					{column_number: 5, filter_default_label: "[<?=lang('unit');?>]", filter_type: "text", data: []},
				], "footer");
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function () {
				$('#productforms').hide();
				$('.paytoggle_downs').click(function () {
					$("#productforms").slideDown();
					return false;
				});
				$('.paytoggle_ups').click(function () {
					$("#productforms").slideUp();
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
    	<?php 
    		echo form_open('reports/purchases_product_actions', 'id="action-form"');
    	?>
		<div class="box Products-table product_">
			<div class="box-header">
				<h2 class="blue"><i class="fa fa-barcode"></i><?= lang('products_report'); ?> <?php
				if ($this->input->post('start_datess')) {
					echo "From " . $this->input->post('start_datess') . " to " . $this->input->post('end_datess');
				}
				?></h2>

				<div class="box-icon">
					<ul class="btn-tasks">
						<li class="dropdown">
							<a href="#" class="paytoggle_ups tip" title="<?= lang('hide_form') ?>">
								<i class="icon fa fa-toggle-up"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" class="paytoggle_downs tip" title="<?= lang('show_form') ?>">
								<i class="icon fa fa-toggle-down"></i>
							</a>
						</li>
					</ul>
				</div>
				<div class="box-icon">
					<ul class="btn-tasks">
						<li class="dropdown">
							<a href="#" id="pdf" class="tip" data-action="export_pdf" title="<?= lang('download_pdf') ?>">
								<i class="icon fa fa-file-pdf-o"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" id="excel" class="tip" data-action="export_excel" title="<?= lang('download_xls') ?>">
								<i class="icon fa fa-file-excel-o"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" id="image_1" class="tip" title="<?= lang('save_image') ?>">
								<i class="icon fa fa-file-picture-o"></i>
							</a>
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

						<p class="introtext"><?= lang('customize_report'); ?></p>

						<div id="productforms">

							<?php echo form_open("reports/supplier_report/".$user_id."/#top-products-con"); ?>
								<div class="row">
									<div class="col-sm-4">
    									<div class="form-group">
    										<label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
    										<?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

    									</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label class="control-label" for="cat"><?= lang("products"); ?></label>
											<?php
											$cat[""] = "";
											$cat[""] = "ALL";
											foreach ($products as $product) {
												$cat[$product->id] = $product->name;
											}
											echo form_dropdown('sproduct', $cat, (isset($_POST['sproduct']) ? $_POST['sproduct'] : ""), 'class="form-control" id="product" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("producte") . '"');
											?>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<?= lang("category", "category") ?>
											<?php
											$cat[''] = "";
											foreach ($categories as $category) {
												$cat[$category->id] = $category->name;
											}
											echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ''), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" style="width:100%"')
											?>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<?= lang("start_date", "start_dates"); ?>
											<?php echo form_input('start_dates', (isset($_POST['start_dates']) ? $_POST['start_dates'] : ''), 'class="form-control datetime" id="start_date"'); ?>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<?= lang("end_date", "end_dates"); ?>
											<?php echo form_input('end_dates', (isset($_POST['end_dates']) ? $_POST['end_dates'] :  ''), 'class="form-control datetime" id="end_date"'); ?>
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
							<table id="tPRData"
								   class="table table-striped table-bordered table-condensed table-hover dfTable reports-table"
								   style="margin-bottom:5px;">
								<thead>
									<tr class="active">
										<th style="min-width:30px; width: 30px; text-align: center;">
											<input class="checkbox checkth" type="checkbox" name="check"/>
										</th>
										<th><?= lang("date"); ?></th>
										<th><?= lang("reference_no"); ?></th>
										<th><?= lang("product_name"); ?></th>
										<th><?= lang("quantity"); ?></th>
										<th><?= lang("unit"); ?></th>
										<th><?= lang("amount"); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
									</tr>
								</tbody>
								<tfoot class="dtFilter">
									<tr class="active">
										<th style="min-width:30px; width:30px; text-align:center;">
											<input class="checkbox checkth" type="checkbox" name="check" />
										</th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
									</tr>
								</tfoot>
							</table>
						</div>

					</div>
				</div>
			</div>
		
		</div>
	</div>

    <div id="top-purchase_expend-con" class="tab-pane fade in">
        <?php
            $v4 = "&supplier=" . $user_id;

            if ($this->input->post('sproduct')) {
                $v4 .= "&product2=" . $this->input->post('sproduct');
            }
            if ($this->input->post('reference_no')) {
                $v4 .= "&reference_no=" . $this->input->post('reference_no');
            }
            if ($this->input->post('category')) {
                $v4 .= "&category=" . $this->input->post('category');
            }
            if ($this->input->post('start_dates')) {
                $v4 .= "&start_date2=" . $this->input->post('start_dates');
            }
            if ($this->input->post('end_dates')) {
                $v4 .= "&end_date2=" . $this->input->post('end_dates');
            }
            
        ?>
        <script>
            $(document).ready(function () {
                function spb(x) {
                    v = x.split('__');
                    return '('+formatQuantity2(v[0])+') <strong>'+formatMoney(v[1])+'</strong>';
                }
                var oTable = $('#tPRDataEx').dataTable({
                    "aaSorting": [[4, "desc"]],
                    "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                    "iDisplayLength": <?= $Settings->rows_per_page ?>,
                    'bProcessing': true, 'bServerSide': true,
                    'sAjaxSource': '<?= site_url('reports/getPurchasedReport5/?v=1'.$v4) ?>',
                    'fnServerData': function (sSource, aoData, fnCallback) {
                        aoData.push({
                            "name": "<?= $this->security->get_csrf_token_name() ?>",
                            "value": "<?= $this->security->get_csrf_hash() ?>"
                        });
                        $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                    },
                    "aoColumns": [
                        {"bSortable": false, "mRender": checkbox},
                        {"mRender": fld}, 
                        null, 
                        null, 
                        {"mRender": currencyFormat}],
                        "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                    
                        var amount = 0;
                        for (var i = 0; i < aaData.length; i++) {
                            amount += parseFloat(aaData[aiDisplay[i]][4]);
                        }
                        var nCells = nRow.getElementsByTagName('th');
                        nCells[4].innerHTML = currencyFormat(parseFloat(amount));
                    }
                }).fnSetFilteringDelay().dtFilter([
                    {column_number: 1, filter_default_label: "[<?=lang('date');?>]", filter_type: "text", data: []},
                    {column_number: 2, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
                    {column_number: 3, filter_default_label: "[<?=lang('description');?>]", filter_type: "text", data: []},
                ], "footer");
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#productforms2').hide();
                $('.paytoggle_downs2').click(function () {
                    $("#productforms2").slideDown();
                    return false;
                });
                $('.paytoggle_ups2').click(function () {
                    $("#productforms2").slideUp();
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
        <?php 
            echo form_open('reports/purchases_product_actions', 'id="action-form"');
        ?>
        <div class="box Products-table product_">
            <div class="box-header">
                <h2 class="blue"><i class="fa fa-barcode"></i><?= lang('purchase_expend'); ?> <?php
                if ($this->input->post('start_datess')) {
                    echo "From " . $this->input->post('start_datess') . " to " . $this->input->post('end_datess');
                }
                ?></h2>

                <div class="box-icon">
                    <ul class="btn-tasks">
                        <li class="dropdown">
                            <a href="#" class="paytoggle_ups2 tip" title="<?= lang('hide_form') ?>">
                                <i class="icon fa fa-toggle-up"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="paytoggle_downs2 tip" title="<?= lang('show_form') ?>">
                                <i class="icon fa fa-toggle-down"></i>
                            </a>
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

                        <p class="introtext"><?= lang('customize_report'); ?></p>

                        <div id="productforms2">

                            <?php echo form_open("reports/supplier_report/".$user_id."/#top-products-con"); ?>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                            <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label" for="cat"><?= lang("products"); ?></label>
                                            <?php
                                            $cat[""] = "";
                                            $cat[""] = "ALL";
                                            foreach ($products as $product) {
                                                $cat[$product->id] = $product->name;
                                            }
                                            echo form_dropdown('sproduct', $cat, (isset($_POST['sproduct']) ? $_POST['sproduct'] : ""), 'class="form-control" id="product" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("producte") . '"');
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang("category", "category") ?>
                                            <?php
                                            $cat[''] = "";
                                            foreach ($categories as $category) {
                                                $cat[$category->id] = $category->name;
                                            }
                                            echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ''), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" style="width:100%"')
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang("start_date", "start_dates"); ?>
                                            <?php echo form_input('start_dates', (isset($_POST['start_dates']) ? $_POST['start_dates'] : ''), 'class="form-control datetime" id="start_date"'); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <?= lang("end_date", "end_dates"); ?>
                                            <?php echo form_input('end_dates', (isset($_POST['end_dates']) ? $_POST['end_dates'] :  ''), 'class="form-control datetime" id="end_date"'); ?>
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
                            <table id="tPRDataEx"
                                   class="table table-striped table-bordered table-condensed table-hover dfTable reports-table"
                                   style="margin-bottom:5px;">
                                <thead>
                                    <tr class="active">
                                        <th style="min-width:30px; width: 30px; text-align: center;">
                                            <input class="checkbox checkth" type="checkbox" name="check"/>
                                        </th>
                                        <th><?= lang("date"); ?></th>
                                        <th><?= lang("reference_no"); ?></th>
                                        <th><?= lang("description"); ?></th>
                                        <th><?= lang("amount"); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                                    </tr>
                                </tbody>
                                <tfoot class="dtFilter">
                                    <tr class="active">
                                        <th style="min-width:30px; width:30px; text-align:center;">
                                            <input class="checkbox checkth" type="checkbox" name="check" />
                                        </th>
                                        <th></th>
                                        <th></th>
                                        <th></th>   
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        
        </div>
    </div>
</div>

	<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function () {
			
			//======= purchase return =======//
			$('#pdf_return').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchaseReturn/pdf/?v=1'.$v)?>";
				return false;
			});
			$('#xls_return').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchaseReturn/0/xls/?v=1'.$v)?>";
				return false;
			});
			$('#image_return').click(function (event) {
				event.preventDefault();
				html2canvas($('.tbl_return'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
			$('#image_1').click(function (event) {
				event.preventDefault();
				html2canvas($('.product_'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
			
			//========== payments =====//
			$('#pdf1').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPaymentsReport/pdf/?v=1'.$p)?>";
				return false;
			});
			$('#xls1').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPaymentsReport/0/xls/?v=1'.$p)?>";
				return false;
			});
			$('#image1').click(function (event) {
				event.preventDefault();
				html2canvas($('.payments-table'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
			
			//========== product  ========//
			$('#pdf_pro').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchasedReport/pdf/?v=1'.$v)?>";
				return false;
			});
			$('#xls_pro').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchasedReport/0/xls/?v=1'.$v)?>";
				return false;
			});
			$('#image_pro').click(function (event) {
				event.preventDefault();
				html2canvas($('.Products-table'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
			//=========== purchase ==============//
			$('#xls_purchase').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchasesReport2/0/xls/?v=1'.$v)?>";
				return false;
			});
			$('#pdf_purchase').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchasesReport2/pdf/?v=1'.$v)?>";
				return false;
			});
			$('#image_purchase').click(function (event) {
				event.preventDefault();
				html2canvas($('.purchases-table'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
			
			//====== purchase_order ==============//
			$('#xls_purchase_order').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchaseOrder/0/xls/?v=1'.$v)?>";
				return false;
			});
			$('#pdf_purchase_order').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchaseOrder/pdf/?v=1'.$v)?>";
				return false;
			});
			$('#image_purchase_order').click(function (event) {
				event.preventDefault();
				html2canvas($('.purchase_order'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
		});
	</script>
