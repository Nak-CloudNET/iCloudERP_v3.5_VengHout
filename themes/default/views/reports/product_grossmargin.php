<?php
    $v = "";
    if ($this->input->post('reference_no')) {
        $v .= "&reference_no=" . $this->input->post('reference_no');
    }
    if ($this->input->post('warehouse')) {
        $v .= "&warehouse=" . $this->input->post('warehouse');
    }
    if ($this->input->post('product_id')) {
        $v .= "&product_id=" . $this->input->post('product_id');
    }
    if ($this->input->post('start_date')) {
        $v .= "&start_date=" . $this->input->post('start_date');
        $sdate = $this->input->post('start_date');
    }
    if ($this->input->post('end_date')) {
        $v .= "&end_date=" . $this->input->post('end_date');
        $edate = $this->input->post('end_date');
    }
?>

<script>
    $(document).ready(function () {
        var oTable = $('#PGMDATA').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true, 'bServerSide': true,
            "bStateSave": true,
            "fnStateSave": function (oSettings, oData) {
                __setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
            },
            "fnStateLoad": function (oSettings) {
                var data = __getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
            },
            'sAjaxSource': '<?=site_url('reports/getProductGrossmarginReport' . ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?=$this->security->get_csrf_token_name()?>",
                    "value": "<?=$this->security->get_csrf_hash()?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
            },
            "aoColumns": [
                {"bSortable": false, "mRender": checkbox},
                {"mRender": fld},
                null, null, null,
                {"mRender": formatQuantity},
                null,
                {"mRender": currencyFormat},
                {"mRender": currencyFormat},
                {"mRender": currencyFormat}
            ],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var tqty = 0, tcost = 0, tprice = 0, profit = 0;
                for (var i = 0; i < aaData.length; i++) {
                    tqty += parseFloat(aaData[aiDisplay[i]][5]);
                    tcost += parseFloat(aaData[aiDisplay[i]][7]);
                    tprice += parseFloat(aaData[aiDisplay[i]][8]);
                    profit += parseFloat(aaData[aiDisplay[i]][9]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[5].innerHTML = formatQuantity(parseFloat(tqty));
                nCells[7].innerHTML = currencyFormat(parseFloat(tcost));
                nCells[8].innerHTML = currencyFormat(parseFloat(tprice));
                nCells[9].innerHTML = currencyFormat(parseFloat(profit));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('invoice_reference');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('product_code');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('product_name');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('unit');?>]", filter_type: "text", data: []},
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

<?php
    echo form_open('reports/product_grossmargin_action', 'id="action-form"');
?>

<div class="box">
    <div class="box-header">

        <?php if ($warehouse_id) { ?>
            <h2 class="blue">
                <i class="fa-fw fa fa-barcode"></i>
                <?= lang('product_grossmargin_report'); ?>
                (
                <?php
                if (count($warehouse) > 1) {
                    echo lang('all_warehouses');
                } else {
                    if (is_array($warehouse)) {
                        foreach ($warehouse as $ware) {
                            echo $ware->name;
                        }
                    }
                    //echo $warehouse->name;
                }
                ?>
                )
            </h2>
        <?php } else { ?>
            <h2 class="blue">
                <i class="fa-fw fa fa-barcode"></i>
                <?= lang('product_grossmargin_report') . ' (' . lang('all_warehouses') . ')'; ?>
            </h2>
        <?php } ?>

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
                <?php if ($Owner || $Admin || $GP['products-export'] || $GP['products-import']) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                        </a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">

                            <?php if ($Owner || $Admin || $GP['products-export']) { ?>
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

                            <?php } ?>

                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action" />
        <input type="hidden" name="sdate" value="<?= isset($sdate); ?>" id="sdate" />
        <input type="hidden" name="edate" value="<?= isset($edate); ?>" id="edate" />
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"'); ?>
    </div>
    <?= form_close()?>
    <div class="box-content" style="overflow-x:scroll; width: 100%;">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?=lang('list_results');?></p>
                <div id="form">

                    <?php echo form_open("reports/product_grossmargin"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control date" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" id="end_date"'); ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="product_id"><?= lang("product"); ?></label>
                                <?php
                                $pr[""] = "";
                                foreach ($products as $product) {
                                    $pr[$product->id] = $product->name . " | " . $product->code ;
                                }
                                echo form_dropdown('product_id', $pr, (isset($_POST['product_id']) ? $_POST['product_id'] : ""), 'class="form-control" id="product_id" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("product") . '"');
                                ?>
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
                    <table id="PGMDATA" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("invoice_reference"); ?></th>
                            <th><?php echo $this->lang->line("product_code"); ?></th>
                            <th><?php echo $this->lang->line("product_name"); ?></th>
                            <th><?php echo $this->lang->line("qty"); ?></th>
                            <th><?php echo $this->lang->line("unit"); ?></th>
                            <th><?php echo $this->lang->line("cost_amount"); ?></th>
                            <th><?php echo $this->lang->line("price_amount"); ?></th>
                            <th><?php echo $this->lang->line("profit"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="10"
                                class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
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

<script type="text/javascript">

    $('body').on('click', '#assign_sale_man', function(e) {
        e.preventDefault();

        $('#form_action').val($('#assign_sale_man').attr('data-action'));
        $('#action-form-submit').trigger('click');
    });

</script>