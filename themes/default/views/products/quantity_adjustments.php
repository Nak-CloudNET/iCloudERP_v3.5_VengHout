<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script>
    $(document).ready(function () {
        oTable = $('#dmpData').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('products/getadjustments'.($warehouse_id ? '/'.$warehouse_id : '')) ?>',
            // "sAjaxSource": "<?= site_url('products/getadjustments'); ?>",
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
				null, null,{"mRender": currencyFormat},
                null,               
				{"mRender": decode_html},
				{"bSortable": false, "mRender": attachment}, 
				{"bSortable": false}
			],
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {

				var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "adjustment_link";
                return nRow;
            },
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0;
                for (var i = 0; i < aaData.length; i++) {
                    
                    gtotal += parseFloat(aaData[aiDisplay[i]][4]);
                    
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[4].innerHTML = currencyFormat(parseFloat(gtotal));
               
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('warehouse');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('created_by');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('note');?>]", filter_type: "text", data: []},
        ], "footer");

        if (__getItem('remove_qals')) {
            if (__getItem('qaitems')) {
                __removeItem('qaitems');
            }
            if (__getItem('qaref')) {
                __removeItem('qaref');
            }
            if (__getItem('qawarehouse')) {
                __removeItem('qawarehouse');
            }
            if (__getItem('qanote')) {
                __removeItem('qanote');
            }
            if (__getItem('qadate')) {
                __removeItem('qadate');
            }
            __removeItem('remove_qals');
        }

        <?php if ($this->session->userdata('remove_qals')) { ?>
            if (__getItem('qaitems')) {
                __removeItem('qaitems');
            }
            if (__getItem('qaref')) {
                __removeItem('qaref');
            }
            if (__getItem('qawarehouse')) {
                __removeItem('qawarehouse');
            }
            if (__getItem('qanote')) {
                __removeItem('qanote');
            }
            if (__getItem('qadate')) {
                __removeItem('qadate');
            }
        <?php $this->erp->unset_data('remove_qals');}
        ?>
    });
</script>

<?php if ($Owner || $Admin || $GP['products-export']) {
        echo form_open('products/adjustment_actions', 'id="action-form"');
    }
?>
<div class="box">
    <div class="box-header">
        <!-- <h2 class="blue"><i class="fa-fw fa fa-filter"></i><?= lang('quantity_adjustments').' ('.($warehouse ? $warehouse->name : lang('all_warehouses')).')'; ?></h2> -->
        <h2 class="blue">
            <i class="fa-fw fa fa-barcode"></i>
            <?= lang('product_adjustment_list'); ?>
            (
                <?php
                    if (count($warehouses) > 1) {
                        echo lang('all_warehouses');
                    } else {
                        foreach ($warehouses as $ware) {
                            echo $ware->name;
                        }
                    }
                ?>
            )
        </h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a href="<?= site_url('products/add_adjustment_multiple') ?>">
                                <i class="fa fa-plus-circle"></i> <?= lang('add_product_adjustment') ?>
                            </a>
                        </li>
						<!--
                        <li>
                            <a href="<?= site_url('products/add_adjustment_by_csv') ?>">
                                <i class="fa fa-plus-circle"></i> <?= lang('add_adjustment_by_csv') ?>
                            </a>
                        </li>
						-->

                        <?php if ($GP['products-export'] || $Owner || $Admin) { ?>
                        <li>
                            <a href="#" id="excel" data-action="export_excel">
                                <i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="pdf" data-action="export_pdf">
                                <i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?>
                            </a>
                        </li>

                        <?php } ?>
                        <!-- <li>
                        <li class="divider"></li>
                            <a href="#" class="bpo" title="<b><?= $this->lang->line("delete_products") ?></b>"
                                data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>"
                                data-html="true" data-placement="left">
                            <i class="fa fa-trash-o"></i> <?= lang('delete_products') ?>
                             </a>
                         </li> -->
                    </ul>
                </li>
                <?php if (!empty($warehouses)) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                        <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= site_url('products/quantity_adjustments') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                            <li class="divider"></li>
                            <?php
                            foreach ($warehouses as $warehouse) {
                                echo '<li><a href="' . site_url('products/quantity_adjustments/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
                    <table id="dmpData" class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th class="col-xs-2"><?= lang("date"); ?></th>
                            <th class="col-xs-2"><?= lang("reference_no"); ?></th>
                            <th class="col-xs-2"><?= lang("warehouse"); ?></th>
							<th class="col-xs-2"><?= lang("quantity"); ?></th>
                            <th class="col-xs-2"><?= lang("created_by"); ?></th>
                            <th><?= lang("note"); ?></th>
                            <th style="min-width:30px; width: 30px; text-align: center;"><i class="fa fa-chain"></i></th>
                            <th style="min-width:75px; text-align:center;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
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
                            <th style="min-width:30px; width: 30px; text-align: center;"><i class="fa fa-chain"></i></th>
                            <th style="width:75px; text-align:center;"><?= lang("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($Owner || $Admin || $GP['products-export']) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?=form_close()?>
<?php }
?>
