<script>
    $(document).ready(function () {
        function attachment(x) {
            if (x != null) {
                return '<a href="' + site.base_url + 'assets/uploads/' + x + '" target="_blank"><i class="fa fa-chain"></i></a>';
            }
            return x;
        }

        var oTable = $('#EXPData').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('products/getListConvert'.($warehouse_id ? '/'.$warehouse_id : '')) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [
				{
					"bSortable": false,
					"mRender": checkbox
				}, 
				{"mRender": fld}, null, 
				{"mRender": currencyFormat},
                {"mRender": formatQuantity},
				null, null, null,
				{"bSortable": false}
			],
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "convert_link";
                return nRow;
            },
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var total = 0, qty_convert = 0;
                for (var i = 0; i < aaData.length; i++) {
                    total += parseFloat(aaData[aiDisplay[i]][3]);
                    qty_convert += parseFloat(aaData[aiDisplay[i]][4]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[3].innerHTML = currencyFormat(total);
                nCells[4].innerHTML = formatQuantity(qty_convert);
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('reference');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('note');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('warehouse');?>]", filter_type: "text", data: []},
			{column_number: 7, filter_default_label: "[<?=lang('created_by');?>]", filter_type: "text", data: []},
        ], "footer");

    });

</script>

<?php //if ($Owner) {
    echo form_open('products/convert_actions', 'id="action-form"');
// } ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-dollar"></i><?= lang('list_convert'); ?></h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
					<i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <?php if ($Owner || $Admin || $GP['products-items_convert']) {?>
						<li>
							<a href="<?= site_url('products/items_convert') ?>">
								<i class="fa fa-plus-circle"></i> <?= lang('add_convert') ?>
							</a>
						</li>
						<?php } ?>
						<?php if ($Owner || $Admin) {?>
							<li><a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a>
							</li>
							<li><a href="#" id="pdf" data-action="export_pdf">
								<i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a>
							</li>
						<?php }else{ ?>
							<?php if($GP['products-export']) { ?>
								<li>
									<a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a>
								</li>
								<li>
									<a href="#" id="pdf" data-action="export_pdf">
										<i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?>
									</a>
								</li>
							<?php }?>
						<?php }?>
                    </ul>
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

                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
                    <table id="EXPData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th class="col-xs-2"><?php echo $this->lang->line("date"); ?></th>
                            <th class="col-xs-2"><?php echo $this->lang->line("reference"); ?></th>
                            <th class="col-xs-1"><?php echo $this->lang->line("cost"); ?></th>
                            <th class="col-xs-2"><?php echo $this->lang->line("quantity_convert"); ?></th>
                            <th class="col-xs-3"><?php echo $this->lang->line("note"); ?></th>
							<th class="col-xs-3"><?php echo $this->lang->line("warehouses"); ?></th>
                            <th class="col-xs-3"><?php echo $this->lang->line("created_by"); ?></th>
                            
                            <th style="width:100px;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="9" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
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
                            
                            <th style="width:100px; text-align: center;"><?php echo $this->lang->line("actions"); ?></th>
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