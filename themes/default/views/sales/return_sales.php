<script>
	function nl2br (str, is_xhtml) {   
		var rs = '';
		var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
		rs = (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
		if(rs == 'null'){
			rs = '';
		}
		return rs;
	}

    $(document).ready(function () {
        var oTable = $('#RESLData').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('sales/getReturns'.($warehouse_id ? '/'.$warehouse_id : '')) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json',
				'type': 'POST', 
				'url': sSource, 
				'data': aoData,
				'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "return_link";
                return nRow;
            },
            "aoColumns": [
                {"bSortable": false, "mRender": checkbox},
                {"mRender": fld}, null, null, null, null,null,
                {"mRender": currencyFormat} /*, {"bVisible ": true}*/
            ],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var sc = 0, gtotal = 0, tpaid = 0, tbalance = 0;
                for (var i = 0; i < aaData.length; i++) {
                    //sc += parseFloat(aaData[aiDisplay[i]][6]);
                  //  gtotal += parseFloat(aaData[aiDisplay[i]][7]);
                   // tpaid += parseFloat(aaData[aiDisplay[i]][8]);
                  tbalance += parseFloat(aaData[aiDisplay[i]][7]);
                }
                var nCells = nRow.getElementsByTagName('th');
               // nCells[6].innerHTML = currencyFormat(parseFloat(sc));
                //nCells[7].innerHTML = currencyFormat(parseFloat(gtotal));
             //   nCells[8].innerHTML = currencyFormat(parseFloat(tpaid));
               nCells[7].innerHTML = currencyFormat(parseFloat(tbalance));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('return_no');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('sales_no');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('biller');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
			 {column_number: 6, filter_default_label: "[<?=lang('saleman');?>]", filter_type: "text", data: []}
        ], "footer");

        <?php if($this->session->userdata('remove_rels')) { ?>
        __setItem('remove_rels', '1');
        <?php $this->erp->unset_data('remove_rels'); } ?>
        if (__getItem('remove_rels')) {
            __removeItem('reref');
            __removeItem('renote');
            __removeItem('reitems');
            __removeItem('rediscount');
            __removeItem('retax2');
            __removeItem('return_surcharge');
            __removeItem('remove_rels');
        }

        $(document).on('click', '.sledit', function (e) {
            if (__getItem('slitems')) {
                e.preventDefault();
                var href = $(this).attr('href');
                bootbox.confirm("<?=lang('you_will_loss_sale_data')?>", function (result) {
                    if (result) {
                        window.location.href = href;
                    }
                });
            }
        });
    });

</script>

<?php
   echo form_open('sales/getReturnsAll_action/'.($warehouse_id ? $warehouse_id : ''), 'id="action-form"');
?>
    <div class="box">
        <div class="box-header">
            <?php if ($warehouse_id) { ?>
                <h2 class="blue">
                    <i class="fa-fw fa fa-barcode"></i>
                    <?= lang('sale_return'); ?>
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
                                echo $warehouse->name;
                            }
                        ?>
                    )
                </h2>
            <?php } else { ?>
                <h2 class="blue">
                    <i class="fa-fw fa fa-barcode"></i>
                    <?= lang('list_sales_return') . ' (' . lang('all_warehouses') . ')'; ?>
                </h2>
            <?php } ?>

            <div class="box-icon">
                <ul class="btn-tasks">
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                      data-placement="left"
                                                                                      title="<?= lang("actions") ?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <?php //if ($Owner || $Admin || $GP['sales-add']) { ?>
                            <!-- <li><a href="<?= site_url('sales/add_return') ?>"><i
                                        class="fa fa-plus-circle"></i> <?= lang('add_sale_return') ?></a></li> -->
                            <?php //} ?>
                            <?php if ($Owner || $Admin || $GP['sales-export']) { ?>
                                <li><a href="#" id="excel" data-action="export_excel"><i
                                            class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                                <li><a href="#" id="pdf" data-action="export_pdf"><i
                                            class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                            <?php } ?>
                            <?php //if ($Owner || $Admin || $GP['sales-import']) { ?>
                                <!-- <li>
                                    <a href="<?= site_url('sales/sale_by_csv'); ?>">
                                        <i class="fa fa-plus-circle"></i>
                                        <span class="text"> <?= lang('add_sale_by_csv'); ?></span>
                                    </a>
                                </li> -->
                            <?php //} ?>

                        </ul>
                    </li>
                    <?php if (!empty($warehouses)) { ?>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip"
                                                                                          data-placement="left"
                                                                                          title="<?= lang("warehouses") ?>"></i></a>
                            <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                                <li><a href="<?= site_url('sales/return_sales') ?>"><i
                                            class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                                <li class="divider"></li>
                                <?php
                                foreach ($warehouses as $warehouse) {
                                    echo '<li><a href="' . site_url('sales/return_sales/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
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
                        <table id="RESLData" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th style="min-width:30px; width: 30px; text-align: center;">
                                        <input class="checkbox checkft" type="checkbox" name="check"/>
                                    </th>
                                    <th><?php echo $this->lang->line("date"); ?></th>
                                    <th><?php echo $this->lang->line("return_no"); ?></th>
                                    <th><?php echo $this->lang->line("sales_no"); ?></th>
                                    <th><?php echo $this->lang->line("biller"); ?></th>
                                    <th><?php echo $this->lang->line("customer"); ?></th>
                                    <th><?php echo $this->lang->line("saleman"); ?></th>
                                    <th><?php echo $this->lang->line("amount"); ?></th>
                                    <!--<th><?php echo $this->lang->line("actions"); ?></th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="9" class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
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
                                    <!--<th class="text-center"><?= lang('action') ?></th>-->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
<?= form_close() ?>
