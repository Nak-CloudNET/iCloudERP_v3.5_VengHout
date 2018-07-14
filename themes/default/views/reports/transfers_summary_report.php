<?php
$v = "";
if ($this->input->post('product')) {
    $v .= "&product=" . $this->input->post('product');
}
if ($this->input->post('from_warehouse')) {
    $v .= "&from_warehouse=" . $this->input->post('from_warehouse');
}
if ($this->input->post('to_warehouse')) {
    $v .= "&to_warehouse=" . $this->input->post('to_warehouse');
}
if ($this->input->post('start_date')) {
    $v .= "&start_date=" . $this->erp->fld($this->input->post('start_date'));
} else {
    $v .= "&start_date=" . $start_date;
}
if ($this->input->post('end_date')) {
    $v .= "&end_date=" . $this->erp->fld($this->input->post('end_date'));
} else {
    $v .= "&end_date=" . $end_date;
}
?>

<script>
    $(document).ready(function () {
        var oTable = $('#SlRDatas').dataTable({
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('reports/getTransferSummaryReport/?v=1' . $v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "bAutoWidth": false,
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, {"mRender": fld}, null, null, null, {"mRender": formatQuantity}, null],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (y-m-d)]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('product_name');?> ]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('from_warehouse');?> ]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('to_warehouse');?> ]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('quantity');?> ]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('unit');?> ]", filter_type: "text", data: []},
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

<?php if ($Owner) {
    echo form_open('reports/transfers_summary_report_action', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-heart"></i><?= lang('transfers_summary_report'); ?><?php
            if ($this->input->post('start_date')) {
                echo " From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
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
                <li class="dropdown"><a href="#" id="pdf" data-action="export_pdf" class="tip"
                                        title="<?= lang('download_pdf') ?>"><i
                                class="icon fa fa-file-pdf-o"></i></a></li>
                <li class="dropdown"><a href="#" id="excel" data-action="export_excel" class="tip"
                                        title="<?= lang('download_xls') ?>"><i
                                class="icon fa fa-file-excel-o"></i></a></li>
            </ul>
        </div>

    </div>
    <?php
    $product = $this->input->post('product');
    $from_warehouse = $this->input->post('from_warehouse');
    $to_warehouse = $this->input->post('to_warehouse');
    //$this->erp->print_arrays($product);
    if ($this->input->post('start_date')) {
        $start_date = $this->erp->fld($this->input->post('start_date'));
    } else {
        $start_date = $start_date;
    }
    if ($this->input->post('end_date')) {
        $end_date = $this->erp->fld($this->input->post('end_date'));
    } else {
        $end_date = $end_date;
    }

    ?>
    <?php if ($Owner) { ?>
        <div style="display: none;">
            <input type="hidden" name="form_action" value="" id="form_action"/>
            <input type="hidden" name="product" value="<?= $product ?>" id="product"/>
            <input type="hidden" name="from_warehouse" value="<?= $from_warehouse ?>" id="from_warehouse"/>
            <input type="hidden" name="to_warehouse" value="<?= $to_warehouse ?>" id="to_warehouse"/>
            <input type="hidden" name="start_date" value="<?= $start_date ?>" id="start_date"/>
            <input type="hidden" name="end_date" value="<?= $end_date ?>" id="end_date"/>
            <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
        </div>
        <?= form_close() ?>
    <?php } ?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('customize_report'); ?></p>

                <div id="form">

                    <?php echo form_open("reports/transfers_summary_report"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="product_id"><?= lang("product"); ?></label>
                                <?php

                                $pr[0] = $this->lang->line("all");;
                                foreach ($products as $product) {
                                    $pr[$product->id] = $product->name . " | " . $product->code;
                                }
                                echo form_dropdown('product', $pr, (isset($_POST['product']) ? $_POST['product'] : ""), 'class="form-control" id="product" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("product") . '"');
                                ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="from_warehouse"><?= lang("from_warehouse"); ?></label>
                                <?php
                                $wh[""] = "ALL";
                                foreach ($warehousesa as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('from_warehouse', $wh, (isset($_POST['from_warehouse']) ? $_POST['from_warehouse'] : ""), 'class="form-control" id="from_warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("from_warehouse") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="to_warehouse"><?= lang("to_warehouse"); ?></label>
                                <?php
                                $wh2[""] = "ALL";
                                foreach ($warehouses as $warehouse) {
                                    $wh2[$warehouse->id] = $warehouse->code . ' / ' . $warehouse->name;
                                }
                                echo form_dropdown('to_warehouse', $wh2, (isset($_POST['to_warehouse']) ? $_POST['to_warehouse'] : ""), 'class="form-control" id="to_warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : $this->erp->hrsd($start_date)), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : $this->erp->hrsd($end_date)), 'class="form-control datetime" id="end_date"'); ?>
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
                    <table id="SlRDatas"
                           class="table table-bordered table-hover table-striped table-condensed reports-table">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <th><?= lang("date"); ?></th>
                            <th><?= lang("product_name"); ?></th>
                            <th><?= lang("from_warehouse"); ?></th>
                            <th><?= lang("to_warehouse"); ?></th>
                            <th><?= lang("quantity"); ?></th>
                            <th><?= lang("variant"); ?></th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
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