<style>
	.return_purchases,tr{
		cursor: pointer;
	}
</style>
<script>
    $(document).ready(function () {
        var oTable = $('#RESLData').dataTable({
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
			"bStateSave": true,
            'sAjaxSource': '<?= site_url('purchases/getReturns'.($warehouse_id ? '/'.$warehouse_id : '')) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "return_purchase_link";
                return nRow;
            },
            "aoColumns": [{"bSortable": false, "mRender": checkbox},{"mRender": fld}, null, null, null, {"mRender": formatPurDecimal}, {"mRender": formatPurDecimal}, {"mRender": formatPurDecimal}, {"mRender": formatPurDecimal}],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var sc = 0, gtotal = 0, paid = 0, balance = 0;
                for (var i = 0; i < aaData.length; i++) {
                    sc 		+= parseFloat(aaData[aiDisplay[i]][5]);
                    gtotal 	+= parseFloat(aaData[aiDisplay[i]][6]);
					paid 	+= parseFloat(aaData[aiDisplay[i]][7]);
					balance += parseFloat(aaData[aiDisplay[i]][8]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[5].innerHTML = formatPurDecimal(parseFloat(sc));
                nCells[6].innerHTML = formatPurDecimal(parseFloat(gtotal));
				nCells[7].innerHTML = formatPurDecimal(parseFloat(paid));
				nCells[8].innerHTML = formatPurDecimal(parseFloat(balance));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('purchase_reference');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('supplier');?>]", filter_type: "text", data: []},
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

    });

</script>

<?php if ($Owner || !$Admin) {
        echo form_open('purchases/return_purchase_actions', 'id="action-form"');
    }
?>
<div class="box">
    <div class="box-header">
        <!-- <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('purchases_return') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'; ?>
        </h2> -->

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
						<?php if ($Owner || $Admin || $GP['purchases-import_expanse']) { ?>
							<li>
								<a href="<?= site_url('purchases/expense_by_csv'); ?>">
									<i class="fa fa-file-text-o"></i>
									<span class="text"> <?= lang('import_expense'); ?></span>
								</a>
							</li>
						<?php } ?>
						<?php if ($Owner || $Admin || $GP['purchases-export']) { ?>
							<li><a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
							<li><a href="#" id="pdf" data-action="export_pdf"><i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
						<?php } ?>
						<?php if ($Owner || $Admin || $GP['purchases-import']) { ?>
							<li>
								<a href="<?= site_url('purchases/purchase_by_csv'); ?>">
									<i class="fa fa-file-text-o"></i>
									<span class="text"> <?= lang('import_purchase'); ?></span>
								</a>
							</li>
						<?php } ?>
						<?php if ($Owner || $Admin || $GP['purchases-delete']) { ?>
							<li class="divider"></li>
							<li><a href="#" class="bpo" title="<?= $this->lang->line("delete_purchases") ?>" data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_purchases') ?></a></li>
						<?php } ?>
					</ul>
                </li>
                <?php if (!empty($warehouses)) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                        <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= site_url('purchases/return_purchases') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                            <li class="divider"></li>
                            <?php
                            foreach ($warehouses as $warehouse) {
                                echo '<li><a href="' . site_url('purchases/return_purchases/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
	 <?php if ($Owner || !$Admin) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <input type="hidden" name="warehId" value="<?php echo $warehouse_id ?>" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?>
    <?php }
    ?>
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
								<th><?php echo $this->lang->line("reference_no"); ?></th>
								<th><?php echo $this->lang->line("purchase_reference"); ?></th>
								<th><?php echo $this->lang->line("supplier"); ?></th>
								<th><?php echo $this->lang->line("surcharges"); ?></th>
								<th><?php echo $this->lang->line("grand_total"); ?></th>
								<th><?php echo $this->lang->line("paid"); ?></th>
								<th><?php echo $this->lang->line("balance"); ?></th>
							</tr>
                        </thead>
                        <tbody>
							<tr>
								<td colspan="7"
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
							</tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>
<script>
	
</script>