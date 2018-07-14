<style>
    .table th {
        text-align: center;
    }

    .ctable td {
        text-align: center;
    }

    .table a:hover {
        text-decoration: none;
    }

    .cl_wday {
        text-align: center;
        font-weight: bold;
    }

    .cl_equal {
        width: 14%;
    }

    td.day {
        width: 14%;
        padding: 0 !important;
        vertical-align: top !important;
    }

    .day_num {
        width: 100%;
        text-align: left;
        cursor: pointer;
        margin: 0;
        padding: 8px;
    }

    .day_num:hover {
        background: #F5F5F5;
    }

    .content {
        width: 100%;
        text-align: left;
        color: #428bca;
        padding: 8px;
    }

    .highlight {
        color: #0088CC;
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <div class="small-box padding1010 col-sm-4 bblue">
                    <h3 class="bold"><?= isset($sales->total_amount) ? $this->erp->formatMoney($sales->total_amount) : '0.00' ?></h3>

                    <p class="bold"><?= $sales->total . ' ' . lang('sales') ?></p>
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
                <div class="small-box padding1010 col-sm-4 bblue">
                    <h3 class="bold"><?= isset($purchases->total_amount) ? $this->erp->formatMoney($purchases->total_amount) : '0.00' ?></h3>

                    <p class="bold"><?= $purchases->total . ' ' . lang('purchases') ?></p>
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
        </div>
    </div>
</div>
<div style="clear:both;height:20px;"></div>
<ul id="myTab" class="nav nav-tabs">
    <li class=""><a href="#daily-con" class="tab-grey"><?= lang('staff_daily_sales') ?></a></li>
    <li class=""><a href="#monthly-con" class="tab-grey"><?= lang('staff_monthly_sales') ?></a></li>
    <li class=""><a href="#sales-con" class="tab-grey"><?= lang('sales_report') ?></a></li>
    <li class=""><a href="#so-con" class="tab-grey"><?= lang('sales_order_report') ?></a></li>
</ul>

<div class="tab-content">
    <div id="daily-con" class="tab-pane fade in">
        <style>
        .table th {
            text-align: center;
        }

        .table td {
            padding: 2px;
        }

        .table td .table td:nth-child(odd) {
            text-align: left;
        }

        .table td .table td:nth-child(even) {
            text-align: right;
        }

        .table a:hover {
            text-decoration: none;
        }

        .cl_wday {
            text-align: center;
            font-weight: bold;
        }

        .cl_equal {
            width: 14%;
        }

        td.day {
            width: 14%;
            padding: 0 !important;
            vertical-align: top !important;
        }

        .day_num {
            width: 100%;
            text-align: left;
            cursor: pointer;
            margin: 0;
            padding: 8px;
        }

        .day_num:hover {
            background: #F5F5F5;
        }

        .content {
            width: 100%;
            text-align: left;
            color: #428bca;
            padding: 8px;
        }

        .highlight {
            color: #0088CC;
            font-weight: bold;
        }
    </style>
    <div class="box">
        <div class="box-header">
            <h2 class="blue"><i class="fa-fw fa fa-calendar"></i><?= lang('daily_sales'); ?></h2>

            <div class="box-icon">
                <ul class="btn-tasks">
                    <li class="dropdown">
                        <a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>">
                            <i class="icon fa fa-file-pdf-o"></i>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="#" id="image" class="tip" title="<?= lang('save_image') ?>">
                            <i class="icon fa fa-file-picture-o"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-lg-12">
                    <p class="introtext"><?= lang('get_day_profit').' '.lang("reports_calendar_text") ?></p>

                    <div id="style">
                        <?php echo $calender; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.table .day_num').click(function () {
                var user_id = <?= $user_id ?>;
                var day = $(this).html();
                var date = '<?= $year.'-'.$month.'-'; ?>'+day;
                var href = '<?= site_url('reports/profit_by_pm'); ?>/'+ date + '/' + user_id;
                $.get(href, function( data ) {
                    $("#myModal").html(data).modal();
                });

            });
            $('#pdf').click(function (event) {
                event.preventDefault();
                window.location.href = "<?=site_url('reports/daily_sales/'.$year.'/'.$month.'/pdf')?>";
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
            if ($(window).width() < 1024) {
                $('#style').css('width', '100%');
                $('#style').css('overflow-x', 'scroll');
                $('#style').css('white-space', 'nowrap');
            }
        });
    </script>
    </div>

    <div id="monthly-con" class="tab-pane fade in">
        <style type="text/css">
            .dfTable th, .dfTable td {
                text-align: center;
                vertical-align: middle;
            }

            .dfTable td {
                padding: 2px;
            }

            .data tr:nth-child(odd) td {
                color: #2FA4E7;
            }

            .data tr:nth-child(even) td {
                text-align: right;
            }
        </style>
        <div class="box">
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-calendar"></i><?= lang('monthly_sales'); ?></h2>

                <div class="box-icon">
                    <ul class="btn-tasks">
                        <?php if (!empty($warehouses) && !$this->session->userdata('warehouse_id')) { ?>
                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?=lang("warehouses")?>"></i></a>
                                <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                                    <li><a href="<?=site_url('reports/monthly_sales_by_pm/0/'.$year)?>"><i class="fa fa-building-o"></i> <?=lang('all_warehouses')?></a></li>
                                    <li class="divider"></li>
                                    <?php
                                        foreach ($warehouses as $warehouse) {
                                                echo '<li><a href="' . site_url('reports/monthly_sales_by_pm/'.$warehouse->id.'/'.$year) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                                            }
                                        ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <li class="dropdown">
                            <a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>">
                                <i class="icon fa fa-file-pdf-o"></i>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" id="image" class="tip" title="<?= lang('save_image') ?>">
                                <i class="icon fa fa-file-picture-o"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-lg-12">
                        <p class="introtext"><?= lang("reports_calendar_text") ?></p>

                        <div class="table-responsive" id="style">
                            <table class="table table-bordered table-striped dfTable reports-table">
                                <thead>
                                    <tr class="year_roller">
                                        <th><a class="white" href="reports/monthly_sales_by_pm/<?php echo $year - 1; ?>">&lt;&lt;</a></th>
                                        <th colspan="10"> <?php echo $year; ?></th>
                                        <th><a class="white" href="reports/monthly_sales_by_pm/<?php echo $year + 1; ?>">&gt;&gt;</a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="bold text-center">
                                        <a href="<?= site_url('reports/monthly_profit_by_pm/'.$year.'/01/'.$user_id); ?>" data-toggle="modal" data-target="#myModal">
                                            <?= lang("cal_january"); ?>
                                        </a>
                                    </td>
                                    <td class="bold text-center">
                                        <a href="<?= site_url('reports/monthly_profit_by_pm/'.$year.'/02/'.$user_id); ?>" data-toggle="modal" data-target="#myModal">
                                            <?= lang("cal_february"); ?>
                                        </a>
                                    </td>
                                    <td class="bold text-center">
                                        <a href="<?= site_url('reports/monthly_profit_by_pm/'.$year.'/03/'.$user_id); ?>" data-toggle="modal" data-target="#myModal">
                                            <?= lang("cal_march"); ?>
                                        </a>
                                    </td>
                                    <td class="bold text-center">
                                        <a href="<?= site_url('reports/monthly_profit_by_pm/'.$year.'/04/'.$user_id); ?>" data-toggle="modal" data-target="#myModal">
                                            <?= lang("cal_april"); ?>
                                        </a>
                                    </td>
                                    <td class="bold text-center">
                                        <a href="<?= site_url('reports/monthly_profit_by_pm/'.$year.'/05/'.$user_id); ?>" data-toggle="modal" data-target="#myModal">
                                            <?= lang("cal_may"); ?>
                                        </a>
                                    </td>
                                    <td class="bold text-center">
                                        <a href="<?= site_url('reports/monthly_profit_by_pm/'.$year.'/06/'.$user_id); ?>" data-toggle="modal" data-target="#myModal">
                                            <?= lang("cal_june"); ?>
                                        </a>
                                    </td>
                                    <td class="bold text-center">
                                        <a href="<?= site_url('reports/monthly_profit_by_pm/'.$year.'/07/'.$user_id); ?>" data-toggle="modal" data-target="#myModal">
                                            <?= lang("cal_july"); ?>
                                        </a>
                                    </td>
                                    <td class="bold text-center">
                                        <a href="<?= site_url('reports/monthly_profit_by_pm/'.$year.'/08/'.$user_id); ?>" data-toggle="modal" data-target="#myModal">
                                            <?= lang("cal_august"); ?>
                                        </a>
                                    </td>
                                    <td class="bold text-center">
                                        <a href="<?= site_url('reports/monthly_profit_by_pm/'.$year.'/09/'.$user_id); ?>" data-toggle="modal" data-target="#myModal">
                                            <?= lang("cal_september"); ?>
                                        </a>
                                    </td>
                                    <td class="bold text-center">
                                        <a href="<?= site_url('reports/monthly_profit_by_pm/'.$year.'/10/'.$user_id); ?>" data-toggle="modal" data-target="#myModal">
                                            <?= lang("cal_october"); ?>
                                        </a>
                                    </td>
                                    <td class="bold text-center">
                                        <a href="<?= site_url('reports/monthly_profit_by_pm/'.$year.'/11/'.$user_id); ?>" data-toggle="modal" data-target="#myModal">
                                            <?= lang("cal_november"); ?>
                                        </a>
                                    </td>
                                    <td class="bold text-center">
                                        <a href="<?= site_url('reports/monthly_profit_by_pm/'.$year.'/12/'.$user_id); ?>" data-toggle="modal" data-target="#myModal">
                                            <?= lang("cal_december"); ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <?php
                                    // $this->erp->print_arrays($sales);
                                    if (!empty($sales_monthly)) {
                                        foreach ($sales_monthly as $value) {
                                            $array[$value->date] = "<table class='table table-bordered table-hover table-striped table-condensed data' style='margin:0;'><tbody>
                                            <tr><td>" . $this->lang->line("amount") . "</td></tr>
                                            <tr><td>" . $this->erp->formatMoney($value->total) . "</td></tr>
                                            <tr><td>" . $this->lang->line("order_discount") . "</td></tr>
                                            <tr><td>" . $this->erp->formatMoney($value->order_discount) . "</td></tr>
                                            <tr><td>" . $this->lang->line("shipping") . "</td></tr>
                                            <tr><td>" . $this->erp->formatMoney($value->shipping) . "</td></tr>
                                            <tr><td>" . $this->lang->line("product_tax") . "</td></tr>
                                            <tr><td>" . $this->erp->formatMoney($value->tax1) . "</td></tr>
                                            <tr><td>" . $this->lang->line("order_tax") . "</td></tr>
                                            <tr><td>" . $this->erp->formatMoney($value->tax2) . "</td></tr>
                                            <tr><td>" . $this->lang->line("total") . "</td></tr>
                                            <tr><td>" . $this->erp->formatMoney($value->total - $value->order_discount)  . "</td></tr>
                                            <tr><td>" . $this->lang->line("award_points") . "</td></tr>
                                            <tr><td>" . intval($value->total / $this->Settings->each_sale)  . "</td></tr>
                                            </tbody></table>";
                                        }
                                        
                                        for ($i = 1; $i <= 12; $i++) {
                                            echo '<td width="8.3%">';
                                            if (isset($array[$i])) {
                                                echo $array[$i];
                                            } else {
                                                echo '<strong>0</strong>';
                                            }
                                            echo '</td>';
                                        }
                                    } else {
                                        for ($i = 1; $i <= 12; $i++) {
                                            echo '<td width="8.3%"><strong>0</strong></td>';
                                        }
                                    }
                                    ?>
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
                $('#pdf').click(function (event) {
                    event.preventDefault();
                    window.location.href = "<?=site_url('reports/monthly_sales_by_pm/'.$year.'/pdf')?>";
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
                if ($(window).width() < 1024) {
                    $('#style').css('width', '100%');
                    $('#style').css('overflow-x', 'scroll');
                    $('#style').css('white-space', 'nowrap');
                }
            });
        </script>

    </div>

    <div id="sales-con" class="tab-pane fade in">
        <?php
        $v = "&user=" . $user_id;
        if ($this->input->post('submit_sale_report')) {
            if ($this->input->post('biller')) {
                $v .= "&biller=" . $this->input->post('biller');
            }
            if ($this->input->post('warehouse')) {
                $v .= "&warehouse=" . $this->input->post('warehouse');
            }
            if ($this->input->post('csutomer')) {
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
        ?>
        <script>
            $(document).ready(function () {
                var oTable = $('#SlRData').dataTable({
                    "aaSorting": [[0, "desc"]],
                    "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                    "iDisplayLength": <?= $Settings->rows_per_page ?>,
                    'bProcessing': true, 'bServerSide': true,
                    'sAjaxSource': '<?= site_url('reports/get_sale_report_by_pm/?v=1' . $v) ?>',
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

        <div class="box">
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-heart nb"></i> <?= lang('sales_report'); ?> <?php
                    if ($this->input->post('start_date')) {
                        echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
                    }
                    ?></h2>

                <div class="box-icon">
                    <ul class="btn-tasks">
                        <li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
                                    class="icon fa fa-toggle-up"></i></a></li>
                        <li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
                                    class="icon fa fa-toggle-down"></i></a></li>
                    </ul>
                </div>
                <div class="box-icon">
                    <ul class="btn-tasks">
                        <li class="dropdown"><a href="#" id="pdf2" class="tip" title="<?= lang('download_pdf') ?>"><i
                                    class="icon fa fa-file-pdf-o"></i></a></li>
                        <li class="dropdown"><a href="#" id="xls2" class="tip" title="<?= lang('download_xls') ?>"><i
                                    class="icon fa fa-file-excel-o"></i></a></li>
                        <li class="dropdown"><a href="#" id="image2" class="tip image"
                                                title="<?= lang('save_image') ?>"><i
                                    class="icon fa fa-file-picture-o"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-lg-12">

                        <p class="introtext"><?= lang('customize_report'); ?></p>

                        <div id="form">

                            <?= form_open("reports/staff_report/" . $user_id . '#sales-con'); ?>
                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                        <?php
                                        echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="customer" data-placeholder="' . lang("select") . " " . lang("customer") . '"');
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
                                        echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . lang("select") . " " . lang("biller") . '"');
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
                                        echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'class="form-control" id="warehouse" data-placeholder="' . lang("select") . " " . lang("warehouse") . '"');
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
                                        <?= form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?= lang("end_date", "end_date"); ?>
                                        <?= form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div
                                    class="controls"> <?= form_submit('submit_sale_report', lang("submit"), 'class="btn btn-primary"'); ?> </div>
                            </div>
                            <?= form_close(); ?>

                        </div>
                        <div class="clearfix"></div>


                        <div class="table-responsive">
                            <table id="SlRData"
                                   class="table table-bordered table-hover table-striped table-condensed reports-table">
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
                                    <td colspan="9"
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

    <div id="so-con" class="tab-pane fade in">
        <?php
        $v = "&user=" . $user_id;
        if ($this->input->post('submit_sale_report')) {
            if ($this->input->post('biller')) {
                $v .= "&biller=" . $this->input->post('biller');
            }
            if ($this->input->post('warehouse')) {
                $v .= "&warehouse=" . $this->input->post('warehouse');
            }
            if ($this->input->post('csutomer')) {
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
        ?>
        <script>
            $(document).ready(function () {
                var oTable = $('#SORBPMData').dataTable({
                    "aaSorting": [[0, "desc"]],
                    "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                    "iDisplayLength": <?= $Settings->rows_per_page ?>,
                    'bProcessing': true, 'bServerSide': true,
                    'sAjaxSource': '<?= site_url('reports/get_so_report_by_pm/?v=1' . $v) ?>',
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
                $('#so_form').hide();
                $('.toggle_down').click(function () {
                    $("#so_form").slideDown();
                    return false;
                });
                $('.toggle_up').click(function () {
                    $("#so_form").slideUp();
                    return false;
                });
            });
        </script>

        <div class="box">
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-heart nb"></i> <?= lang('sales_order_report'); ?> <?php
                    if ($this->input->post('start_date')) {
                        echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
                    }
                    ?></h2>

                <div class="box-icon">
                    <ul class="btn-tasks">
                        <li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
                                    class="icon fa fa-toggle-up"></i></a></li>
                        <li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
                                    class="icon fa fa-toggle-down"></i></a></li>
                    </ul>
                </div>
                <div class="box-icon">
                    <ul class="btn-tasks">
                        <li class="dropdown"><a href="#" id="pdf2" class="tip" title="<?= lang('download_pdf') ?>"><i
                                    class="icon fa fa-file-pdf-o"></i></a></li>
                        <li class="dropdown"><a href="#" id="xls2" class="tip" title="<?= lang('download_xls') ?>"><i
                                    class="icon fa fa-file-excel-o"></i></a></li>
                        <li class="dropdown"><a href="#" id="image2" class="tip image"
                                                title="<?= lang('save_image') ?>"><i
                                    class="icon fa fa-file-picture-o"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-lg-12">

                        <p class="introtext"><?= lang('customize_report'); ?></p>

                        <div id="so_form">

                            <?= form_open("reports/staff_report/" . $user_id . '#sales-con'); ?>
                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                        <?php
                                        echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="customer" data-placeholder="' . lang("select") . " " . lang("customer") . '"');
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
                                        echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . lang("select") . " " . lang("biller") . '"');
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
                                        echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'class="form-control" id="warehouse" data-placeholder="' . lang("select") . " " . lang("warehouse") . '"');
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
                                        <?= form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?= lang("end_date", "end_date"); ?>
                                        <?= form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div
                                    class="controls"> <?= form_submit('submit_sale_report', lang("submit"), 'class="btn btn-primary"'); ?> </div>
                            </div>
                            <?= form_close(); ?>

                        </div>
                        <div class="clearfix"></div>


                        <div class="table-responsive">
                            <table id="SORBPMData"
                                   class="table table-bordered table-hover table-striped table-condensed reports-table">
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
                                    <td colspan="9"
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

</div>

<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/staff_report/'.$user_id.'/'.$year.'/'.$month.'/pdf')?>";
            return false;
        });
        $('#pdf1').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/staff_report/'.$user_id.'/'.$year.'/'.$month.'/pdf/1')?>";
            return false;
        });
        $('#pdf2').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getSalesReport/pdf/?v=1'.$v)?>";
            return false;
        });
        $('#xls2').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getSalesReport/0/xls/?v=1'.$v)?>";
            return false;
        });
        $('#pdf3').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getPurchasesReport/pdf/?v=1'.$v1)?>";
            return false;
        });
        $('#xls3').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getPurchasesReport/0/xls/?v=1'.$v1)?>";
            return false;
        });
        $('#pdf4').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getPaymentsReport/pdf/?v=1'.$p)?>";
            return false;
        });
        $('#xls4').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getPaymentsReport/0/xls/?v=1'.$p)?>";
            return false;
        });
        $('#pdf5').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getUserLogins/'.$user_id.'/pdf/?v=1'.$l)?>";
            return false;
        });
        $('#xls5').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getUserLogins/'.$user_id.'/0/xls?v=1'.$l)?>";
            return false;
        });
        $('.image').click(function (event) {
            var box = $(this).closest('.box');
            event.preventDefault();
            html2canvas(box, {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL()
                    window.open(img);
                }
            });
            return false;
        });
    });
</script>
