<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <div class="small-box padding1010 col-sm-4 bblue">
                    <h3><?= isset($sales->total_amount) ? $this->erp->formatMoney($sales->total_amount) : '0.00' ?></h3>

                    <p><?= lang('sales_amount') ?></p>
                </div>
                <div class="small-box padding1010 col-sm-4 bdarkGreen">
                    <h3><?= isset($sales->paid) ? $this->erp->formatMoney($sales->paid) : '0.00' ?></h3>

                    <p><?= lang('total_paid') ?></p>
                </div>
                <div class="small-box padding1010 col-sm-4 borange">
                    <h3><?= (isset($sales->total_amount) || isset($sales->paid)) ? $this->erp->formatMoney($sales->total_amount - $sales->paid) : '0.00' ?></h3>

                    <p><?= lang('due_amount') ?></p>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="small-box padding1010 bblue">
                            <div class="inner clearfix">
                                <a>
                                    <h3><?= $total_sales ?></h3>

                                    <p><?= lang('total_sales') ?></p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="small-box padding1010 blightBlue">
                            <div class="inner clearfix">
                                <a>
                                    <h3><?= $total_quotes ?></h3>

                                    <p><?= lang('total_quotes') ?></p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="small-box padding1010 borange">
                            <div class="inner clearfix">
                                <a>
                                    <h3><?= $total_returns ?></h3>

                                    <p><?= lang('total_returns') ?></p>
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
    <li class=""><a href="#sales-con" class="tab-grey"><?= lang('sales') ?></a></li>
	<li class=""><a href="#purchase-con" class="tab-grey"><?= lang('purchase') ?></a></li>
    <li class=""><a href="#payments-con" class="tab-grey"><?= lang('payments') ?></a></li>
    <li class=""><a href="#quotes-con" class="tab-grey"><?= lang('quotes') ?></a></li>
    <li class=""><a href="#returns-con" class="tab-grey"><?= lang('return_sales') ?></a></li>
	<li class=""><a href="#product-con" class="tab-grey"><?= lang('products') ?></a></li>
</ul>

<div class="tab-content">
    <div id="sales-con" class="tab-pane fade in">

        <?php
        $v = "&biller=" . $user_id;

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
			
        }
        ?>
        <script>
        $(document).ready(function () {
			var date_c = '<?= $date ?>';
            var oTable = $('#SlRData').dataTable({
                "aaSorting": [[0, "desc"]],
                "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "iDisplayLength": <?= $Settings->rows_per_page ?>,
                'bProcessing': true, 'bServerSide': true,
                'sAjaxSource': '<?= site_url('reports/getSellReport/?v=1' .$v) ?>',
                'fnServerData': function (sSource, aoData, fnCallback) {
                    aoData.push({
                        "name": "<?= $this->security->get_csrf_token_name() ?>",
                        "value": "<?= $this->security->get_csrf_hash() ?>"
                    });
                    $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                },
                "aoColumns": [{"mRender": fld}, null, null, null, {
                    "bSearchable": false,
                    "mRender": pqFormat
                }, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": row_status}],
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                    var gtotal = 0, paid = 0, balance = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        gtotal += parseFloat(aaData[aiDisplay[i]][5]);
                        paid += parseFloat(aaData[aiDisplay[i]][6]);
                        balance += parseFloat(aaData[aiDisplay[i]][7]);
                    }
                    var nCells = nRow.getElementsByTagName('th');
                    nCells[5].innerHTML = currencyFormat(parseFloat(gtotal));
                    nCells[6].innerHTML = currencyFormat(parseFloat(paid));
                    nCells[7].innerHTML = currencyFormat(parseFloat(balance));
                }
            }).fnSetFilteringDelay().dtFilter([
                {column_number: 0, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
                {column_number: 1, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
                {column_number: 2, filter_default_label: "[<?=lang('biller');?>]", filter_type: "text", data: []},
                {column_number: 3, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
                {column_number: 8, filter_default_label: "[<?=lang('payment_status');?>]", filter_type: "text", data: []},
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

        <div class="box sales-table">
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-heart nb"></i><?= lang('customer_sales_report'); ?> <?php
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
                            <a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>">
                                <i
                                class="icon fa fa-file-pdf-o"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" id="xls" class="tip" title="<?= lang('download_xls') ?>">
                                <i
                                class="icon fa fa-file-excel-o"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" id="image" class="tip" title="<?= lang('save_image') ?>">
                                <i
                                class="icon fa fa-file-picture-o"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-lg-12">
                        <p class="introtext"><?= lang('customize_report'); ?></p>

                        <div id="form">

                            <?php echo form_open("reports/customer_report/" . $user_id); ?>
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
                            <div class="clearfix"></div>


                            <div class="table-responsive">
                                <table id="SlRData"
                                class="table table-bordered table-hover table-striped table-condensed reports-table reports-table">
                                <thead>
                                    <tr>
                                        <th><?= lang("date"); ?></th>
                                        <th><?= lang("reference_no"); ?></th>
                                        <th><?= lang("biller"); ?></th>
                                        <th><?= lang("customer"); ?></th>
                                        <th><?= lang("product_qty"); ?></th>
                                        <th><?= lang("grand_total"); ?></th>
                                        <th><?= lang("paid"); ?></th>
                                        <th><?= lang("balance"); ?></th>
                                        <th><?= lang("payment_status"); ?></th>
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
                                        <th><?= lang("product_qty"); ?></th>
                                        <th><?= lang("grand_total"); ?></th>
                                        <th><?= lang("paid"); ?></th>
                                        <th><?= lang("balance"); ?></th>
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
    <div id="purchase-con" class="tab-pane fade in">
        <?php
			$p = "&biller=" . $user_id;
			if ($this->input->post('submit_payment_report')) {
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

				var oTable = $('#PurRData').dataTable({
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
					"aoColumns": [{"mRender": fld}, null, null, {"bVisible": false}, {"mRender": paid_by}, {"mRender": currencyFormat}, {"mRender": row_status}],
					'fnRowCallback': function (nRow, aData, iDisplayIndex) {
						var oSettings = oTable.fnSettings();
						if (aData[6] == 'returned') {
							nRow.className = "danger";
						}
						return nRow;
					},
					"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
						var total = 0;
						for (var i = 0; i < aaData.length; i++) {
							total += parseFloat(aaData[aiDisplay[i]][5]);
						}
						var nCells = nRow.getElementsByTagName('th');
						nCells[4].innerHTML = currencyFormat(parseFloat(total));
					}
				}).fnSetFilteringDelay().dtFilter([
					{column_number: 0, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
					{column_number: 1, filter_default_label: "[<?=lang('payment_ref');?>]", filter_type: "text", data: []},
					{column_number: 2, filter_default_label: "[<?=lang('sale_ref');?>]", filter_type: "text", data: []},
					{column_number: 4, filter_default_label: "[<?=lang('paid_by');?>]", filter_type: "text", data: []},
					{column_number: 6, filter_default_label: "[<?=lang('type');?>]", filter_type: "text", data: []},
				], "footer");
			});
        </script>
        <script type="text/javascript">
			$(document).ready(function () {
				$('#purchaseform').hide();
				$('.paytoggle_down').click(function () {
					$("#purchaseform").slideDown();
					return false;
				});
				$('.paytoggle_up').click(function () {
					$("#purchaseform").slideUp();
					return false;
				});
			});
        </script>

        <div class="box payments-table">
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-money nb"></i><?= lang('customer_purchase_report'); ?> <?php
                if ($this->input->post('start_date')) {
                    echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
                }
                ?></h2>

                <div class="box-icon">
                    <ul class="btn-tasks">
                        <li class="dropdown">
                            <a href="#" class="paytoggle_up tip" title="<?= lang('hide_form') ?>">
                                <i
                                class="icon fa fa-toggle-up"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="paytoggle_down tip" title="<?= lang('show_form') ?>">
                                <i
                                class="icon fa fa-toggle-down"></i>
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
                    </ul>
                </div>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-lg-12">
                        <p class="introtext"><?= lang('customize_report'); ?></p>

                        <div id="purchaseform">

                            <?php echo form_open("reports/customer_report/" . $user_id."/#payments-con"); ?>
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
                                        <?php echo form_input('pay_start_date', (isset($_POST['pay_start_date']) ? $_POST['pay_start_date'] : ""), 'class="form-control date" id="start_date"'); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?= lang("end_date", "end_date"); ?>
                                        <?php echo form_input('pay_end_date', (isset($_POST['pay_end_date']) ? $_POST['pay_end_date'] : ""), 'class="form-control date" id="end_date"'); ?>
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
                            <table id="PurRData"
                            class="table table-bordered table-hover table-striped table-condensed reports-table reports-table">

                            <thead>
                                <tr>
                                    <th><?= lang("date"); ?></th>
                                    <th><?= lang("payment_ref"); ?></th>
                                    <th><?= lang("sale_ref"); ?></th>
                                    <th><?= lang("purchase_ref"); ?></th>
                                    <th><?= lang("paid_by"); ?></th>
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
                                    <th><?= lang("amount"); ?></th>
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
        $p = "&biller=" . $user_id;
        if ($this->input->post('submit_payment_report')) {
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
                "aoColumns": [{"mRender": fld}, null, null, {"bVisible": false}, {"mRender": paid_by}, {"mRender": currencyFormat}, {"mRender": row_status}],
                'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                    var oSettings = oTable.fnSettings();
                    if (aData[6] == 'returned') {
                        nRow.className = "danger";
                    }
                    return nRow;
                },
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                    var total = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        total += parseFloat(aaData[aiDisplay[i]][5]);
                    }
                    var nCells = nRow.getElementsByTagName('th');
                    nCells[4].innerHTML = currencyFormat(parseFloat(total));
                }
            }).fnSetFilteringDelay().dtFilter([
                {column_number: 0, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
                {column_number: 1, filter_default_label: "[<?=lang('payment_ref');?>]", filter_type: "text", data: []},
                {column_number: 2, filter_default_label: "[<?=lang('sale_ref');?>]", filter_type: "text", data: []},
                {column_number: 4, filter_default_label: "[<?=lang('paid_by');?>]", filter_type: "text", data: []},
                {column_number: 6, filter_default_label: "[<?=lang('type');?>]", filter_type: "text", data: []},
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
                <h2 class="blue"><i class="fa-fw fa fa-money nb"></i><?= lang('customer_payments_report'); ?> <?php
                if ($this->input->post('start_date')) {
                    echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
                }
                ?></h2>

                <div class="box-icon">
                    <ul class="btn-tasks">
                        <li class="dropdown">
                            <a href="#" class="paytoggle_up tip" title="<?= lang('hide_form') ?>">
                                <i
                                class="icon fa fa-toggle-up"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="paytoggle_down tip" title="<?= lang('show_form') ?>">
                                <i
                                class="icon fa fa-toggle-down"></i>
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
                    </ul>
                </div>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-lg-12">
                        <p class="introtext"><?= lang('customize_report'); ?></p>

                        <div id="payform">

                            <?php echo form_open("reports/customer_report/" . $user_id."/#payments-con"); ?>
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
                                        <?php echo form_input('pay_start_date', (isset($_POST['pay_start_date']) ? $_POST['pay_start_date'] : ""), 'class="form-control date" id="start_date"'); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?= lang("end_date", "end_date"); ?>
                                        <?php echo form_input('pay_end_date', (isset($_POST['pay_end_date']) ? $_POST['pay_end_date'] : ""), 'class="form-control date" id="end_date"'); ?>
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
                            class="table table-bordered table-hover table-striped table-condensed reports-table reports-table">

                            <thead>
                                <tr>
                                    <th><?= lang("date"); ?></th>
                                    <th><?= lang("payment_ref"); ?></th>
                                    <th><?= lang("sale_ref"); ?></th>
                                    <th><?= lang("purchase_ref"); ?></th>
                                    <th><?= lang("paid_by"); ?></th>
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
                                    <th><?= lang("amount"); ?></th>
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
	<div id="quotes-con" class="tab-pane fade in">
		<script type="text/javascript">
		$(document).ready(function () {
			var oTable = $('#QuRData').dataTable({
				"aaSorting": [[0, "desc"]],
				"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
				"iDisplayLength": <?= $Settings->rows_per_page ?>,
				'bProcessing': true, 'bServerSide': true,
				'sAjaxSource': '<?= site_url('reports/getQuotesReport/?v=1&biller='.$user_id) ?>',
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
				"aoColumns": [{"mRender": fld}, null, null, null, {
					"bSearchable": false,
					"mRender": pqFormat
				}, {"mRender": currencyFormat}, {"mRender": row_status}],
			}).fnSetFilteringDelay().dtFilter([
				{column_number: 0, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
				{column_number: 1, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
				{column_number: 2, filter_default_label: "[<?=lang('biller');?>]", filter_type: "text", data: []},
				{column_number: 3, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
				{column_number: 5, filter_default_label: "[<?=lang('grand_total');?>]", filter_type: "text", data: []},
				{column_number: 6, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
			], "footer");
		});
		</script>
		<div class="box">
			<div class="box-header">
				<h2 class="blue"><i class="fa-fw fa fa-heart-o nb"></i><?=  lang('quotes'); ?>
				</h2>

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
							<a href="#" id="image1" class="tip image" title="<?= lang('save_image') ?>">
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
							<table id="QuRData" class="table table-bordered table-hover table-striped table-condensed">
								<thead>
									<tr>
										<th><?= lang("date"); ?></th>
										<th><?= lang("reference_no"); ?></th>
										<th><?= lang("biller"); ?></th>
										<th><?= lang("customer"); ?></th>
										<th><?= lang("product_qty"); ?></th>
										<th><?= lang("grand_total"); ?></th>
										<th><?= lang("status"); ?></th>
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
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th><?= lang("product_qty"); ?></th>
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
	<div id="returns-con" class="tab-pane fade in">
		<script>
		$(document).ready(function () {
			var oTable = $('#PRESLData').dataTable({
				"aaSorting": [[0, "desc"]],
				"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
				"iDisplayLength": <?= $Settings->rows_per_page ?>,
				'bProcessing': true, 'bServerSide': true,
				'sAjaxSource': '<?= site_url('reports/getReturnsReport/?v=1&biller='.$user_id) ?>',
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
				'fnRowCallback': function (nRow, aData, iDisplayIndex) {
					var oSettings = oTable.fnSettings();
					nRow.id = aData[7];
					nRow.className = "return_link";
					return nRow;
				},
				"aoColumns": [{"mRender": fld}, null, null, null, null, {
					"bSearchable": false,
					"mRender": pqFormat
				}, {"mRender": currencyFormat}, {"mRender": currencyFormat}],
				"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
					var sc = 0, gtotal = 0;
					for (var i = 0; i < aaData.length; i++) {
						sc += parseFloat(aaData[aiDisplay[i]][6]);
						gtotal += parseFloat(aaData[aiDisplay[i]][7]);
					}
					var nCells = nRow.getElementsByTagName('th');
					nCells[6].innerHTML = currencyFormat(parseFloat(sc));
					nCells[7].innerHTML = currencyFormat(parseFloat(gtotal));
				}
			}).fnSetFilteringDelay().dtFilter([
				{column_number: 0, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
				{column_number: 1, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
				{
					column_number: 2,
					filter_default_label: "[<?=lang('sale_reference');?>]",
					filter_type: "text",
					data: []
				},
				{column_number: 3, filter_default_label: "[<?=lang('biller');?>]", filter_type: "text", data: []},
				{column_number: 4, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
			], "footer");
		});
		</script>
		<div class="box">
			<div class="box-header">
				<h2 class="blue"><i class="fa-fw fa fa-random nb"></i><?= lang('return_sales'); ?>
				</h2>

				<div class="box-icon">
					<ul class="btn-tasks">
						<li class="dropdown">
							<a href="#" id="pdf5" class="tip" title="<?= lang('download_pdf') ?>">
								<i class="icon fa fa-file-pdf-o"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" id="xls5" class="tip" title="<?= lang('download_xls') ?>">
								<i class="icon fa fa-file-excel-o"></i>
							</a>
						</li>
						<li class="dropdown">
							<a href="#" id="image5" class="tip image" title="<?= lang('save_image') ?>">
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
							<table id="PRESLData" class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th><?= lang("date"); ?></th>
										<th><?= lang("reference_no"); ?></th>
										<th><?= lang("sale_reference"); ?></th>
										<th><?= lang("biller"); ?></th>
										<th><?= lang("customer"); ?></th>
										<th class="col-sm-2"><?= lang("product_qty"); ?></th>
										<th class="col-sm-1"><?= lang("surcharges"); ?></th>
										<th class="col-sm-1"><?= lang("grand_total"); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="9" class="dataTables_empty"><?= lang("loading_data"); ?></td>
									</tr>
								</tbody>
								<tfoot class="dtFilter">
									<tr class="active">
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th></th>
										<th class="col-sm-2"><?= lang("product_qty"); ?></th>
										<th class="col-sm-1"><?= lang("surcharges"); ?></th>
										<th class="col-sm-1"><?= lang("grand_total"); ?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="product-con" class="tab-pane fade in">
		<?php
			$v = "&biller=" . $user_id;

			if ($this->input->post('product')) {
				$v .= "&product=" . $this->input->post('product');
			}
			if ($this->input->post('category')) {
				$v .= "&category=" . $this->input->post('category');
			}
			if ($this->input->post('start_date')) {
				$v .= "&start_date=" . $this->input->post('start_date');
			}
			if ($this->input->post('end_date')) {
				$v .= "&end_date=" . $this->input->post('end_date');
			}
			if ($this->input->post('cf1')) {
				$v .= "&cf1=" . $this->input->post('cf1');
			}
			if ($this->input->post('cf2')) {
				$v .= "&cf2=" . $this->input->post('cf2');
			}
			if ($this->input->post('cf3')) {
				$v .= "&cf3=" . $this->input->post('cf3');
			}
			if ($this->input->post('cf4')) {
				$v .= "&cf4=" . $this->input->post('cf4');
			}
			if ($this->input->post('cf5')) {
				$v .= "&cf5=" . $this->input->post('cf5');
			}
			if ($this->input->post('cf6')) {
				$v .= "&cf6=" . $this->input->post('cf6');
			}
		?>
		<script>
			$(document).ready(function () {
				function spb(x) {
					v = x.split('__');
					return '('+formatQuantity2(v[0])+') <strong>'+formatMoney(v[1])+'</strong>';
				}
				var oTable = $('#PrRData').dataTable({
					"aaSorting": [[3, "desc"], [2, "desc"]],
					"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
					"iDisplayLength": <?= $Settings->rows_per_page ?>,
					'bProcessing': true, 'bServerSide': true,
					'sAjaxSource': '<?= site_url('reports/getProductsBiller/?v=1'.$v) ?>',
					'fnServerData': function (sSource, aoData, fnCallback) {
						aoData.push({
							"name": "<?= $this->security->get_csrf_token_name() ?>",
							"value": "<?= $this->security->get_csrf_hash() ?>"
						});
						$.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
					},
					"aoColumns": [null, null, {"mRender": spb}, {"mRender": currencyFormat}, {"mRender": spb},null],
					"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
						var pq = 0, sq = 0, bq = 0, pa = 0, sa = 0, ba = 0, pl = 0;
						for (var i = 0; i < aaData.length; i++) {
							p = (aaData[aiDisplay[i]][2]).split('__');
							s = (aaData[aiDisplay[i]][3]).split('__');
							b = (aaData[aiDisplay[i]][5]).split('__');
							pq += parseFloat(p[0]);
							pa += parseFloat(p[1]);
							sq += parseFloat(s[0]);
							sa += parseFloat(s[1]);
							bq += parseFloat(b[0]);
							ba += parseFloat(b[1]);
							pl += parseFloat(aaData[aiDisplay[i]][4]);
						}
						var nCells = nRow.getElementsByTagName('th');
						nCells[2].innerHTML = '<div class="text-right">('+formatQuantity2(pq)+') '+formatMoney(pa)+'</div>';
						nCells[3].innerHTML = '<div class="text-right">('+formatQuantity2(sq)+') '+formatMoney(sa)+'</div>';
						nCells[4].innerHTML = currencyFormat(parseFloat(pl));
						nCells[5].innerHTML = '<div class="text-right">('+formatQuantity2(bq)+') '+formatMoney(ba)+'</div>';
					}
				}).fnSetFilteringDelay().dtFilter([
					{column_number: 0, filter_default_label: "[<?=lang('product_code');?>]", filter_type: "text", data: []},
					{column_number: 1, filter_default_label: "[<?=lang('product_name');?>]", filter_type: "text", data: []},
				], "footer");
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function () {
				$('#productform').hide();
				$('.paytoggle_down').click(function () {
					$("#productform").slideDown();
					return false;
				});
				$('.paytoggle_up').click(function () {
					$("#productform").slideUp();
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

		<div class="box Products-table">
			<div class="box-header">
				<h2 class="blue"><i class="fa fa-barcode"></i><?= lang('products_report'); ?> <?php
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
					</ul>
				</div>
			</div>
			
			<div class="box-content">
				<div class="row">
					<div class="col-lg-12">

						<p class="introtext"><?= lang('customize_report'); ?></p>

						<div id="productform">

							<?php echo form_open("reports/products"); ?>
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<?= lang("product", "product"); ?>
											<?php echo form_input('sproduct', (isset($_POST['sproduct']) ? $_POST['sproduct'] : ""), 'class="form-control" id="product"'); ?>
											<input type="hidden" name="product"
												   value="<?= isset($_POST['product']) ? $_POST['product'] : "" ?>"
												   id="product_id"/>
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

									<div class="col-md-4">
										<div class="form-group all">
											<?= lang('pcf1', 'cf1') ?>
											<?= form_input('cf1', (isset($_POST['cf1']) ? $_POST['cf1'] : ''), 'class="form-control tip" id="cf1"') ?>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group all">
											<?= lang('pcf2', 'cf2') ?>
											<?= form_input('cf2', (isset($_POST['cf2']) ? $_POST['cf2'] : ''), 'class="form-control tip" id="cf2"') ?>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group all">
											<?= lang('pcf3', 'cf3') ?>
											<?= form_input('cf3', (isset($_POST['cf3']) ? $_POST['cf3'] : ''), 'class="form-control tip" id="cf3"') ?>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group all">
											<?= lang('pcf4', 'cf4') ?>
											<?= form_input('cf4', (isset($_POST['cf4']) ? $_POST['cf4'] : ''), 'class="form-control tip" id="cf4"') ?>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group all">
											<?= lang('pcf5', 'cf5') ?>
											<?= form_input('cf5', (isset($_POST['cf5']) ? $_POST['cf5'] : ''), 'class="form-control tip" id="cf5"') ?>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group all">
											<?= lang('pcf6', 'cf6') ?>
											<?= form_input('cf6', (isset($_POST['cf6']) ? $_POST['cf6'] : ''), 'class="form-control tip" id="cf6"') ?>
										</div>
									</div>
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
										class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
								</div>
							<?php echo form_close(); ?>

						</div>

						<div class="clearfix"></div>

						<div class="table-responsive">
							<table id="PrRData"
								   class="table table-striped table-bordered table-condensed table-hover dfTable reports-table"
								   style="margin-bottom:5px;">
								<thead>
									<tr class="active">
										<th><?= lang("product_code"); ?></th>
										<th><?= lang("product_name"); ?></th>
										<th><?= lang("purchased"); ?></th>
										<th><?= lang("profit_loss"); ?></th>
										<th><?= lang("stock_in_hand"); ?></th>
										<th><?= lang("action"); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="6" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
									</tr>
								</tbody>
								<tfoot class="dtFilter">
									<tr class="active">
										<th></th>
										<th></th>
										<th><?= lang("purchased"); ?></th>
										<th><?= lang("profit_loss"); ?></th>
										<th><?= lang("stock_in_hand"); ?></th>
										<th><?= lang("action"); ?></th>
									</tr>
								</tfoot>
							</table>
						</div>

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
        window.location.href = "<?=site_url('reports/getSalesReport/pdf/?v=1'.$v)?>";
        return false;
    });
    $('#xls').click(function (event) {
        event.preventDefault();
        window.location.href = "<?=site_url('reports/getSalesReport/0/xls/?v=1'.$v)?>";
        return false;
    });
    $('#image').click(function (event) {
        event.preventDefault();
        html2canvas($('.sales-table'), {
            onrendered: function (canvas) {
                var img = canvas.toDataURL()
                window.open(img);
            }
        });
        return false;
    });
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
});
</script>
