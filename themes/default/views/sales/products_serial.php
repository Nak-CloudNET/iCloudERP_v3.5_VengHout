<script>
    $(document).ready(function () {
        var oTable = $('#RESLData').dataTable({
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('sales/getProductSerial'.($warehouse_id ? '/'.$warehouse_id : '')) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[7];
                nRow.className = "return_link";
                return nRow;
            },
            "aoColumns": [
				{"bSortable": false, "mRender": checkbox}, {
                    "bSortable": false,
                    "mRender": img_hl
                }, {"mRender": fld}, null, null, null, null, {"mRender": currencyFormat}, {"mRender": currencyFormat}, null, null, null],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var sc = 0, gtotal = 0, g = 0;
                for (var i = 0; i < aaData.length; i++) {
                    sc += parseFloat(aaData[aiDisplay[i]][7]);
                    gtotal += parseFloat(aaData[aiDisplay[i]][8]);
					g  += parseFloat(aaData[aiDisplay[i]][9]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[7].innerHTML = currencyFormat(parseFloat(sc));
                nCells[8].innerHTML = currencyFormat(parseFloat(gtotal));
				nCells[9].innerHTML = currencyFormat(parseFloat(g));
            }
        }).fnSetFilteringDelay().dtFilter([
			{column_number: 1, filter_default_label: "[<?=lang('image');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('sale_reference');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('product_code');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('product_name');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('category');?>]", filter_type: "text", data: []},
			{column_number: 10, filter_default_label: "[<?=lang('product_unit');?>]", filter_type: "text", data: []},
			{column_number: 11, filter_default_label: "[<?=lang('serial_no');?>]", filter_type: "text", data: []},
        ], "footer");

    });

</script>

<?php if ($Owner) {
   // echo form_open('sales/return_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('products_serial'); ?></h2>
	
        <div class="box-icon">
            <ul class="btn-tasks">
                <?php if (!empty($warehouses)) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<i class="icon fa fa-building-o tip"data-placement="left" title="<?= lang("warehouses") ?>"></i>
						</a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= site_url('sales/product_serial') ?>">
								<i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a>
							</li>
                            <li class="divider"></li>
                            <?php
                            foreach ($warehouses as $warehouse) {
                                echo '<li><a href="' . site_url('sales/product_serial/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
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
								<th><?php echo $this->lang->line("image"); ?></th>
								<th><?php echo $this->lang->line("date"); ?></th>
								<th><?php echo $this->lang->line("sale_reference"); ?></th>
								<th><?php echo $this->lang->line("product_code"); ?></th>
								<th><?php echo $this->lang->line("product_name"); ?></th>
								<th><?php echo $this->lang->line("category"); ?></th>
								<th><?php echo $this->lang->line("product_cost"); ?></th>
								<th><?php echo $this->lang->line("product_price"); ?></th>
								<th><?php echo $this->lang->line("quantity"); ?></th>
								<th><?php echo $this->lang->line("product_unit"); ?></th>
								<th><?php echo $this->lang->line("serial_no"); ?></th>
							</tr>
                        </thead>
                        <tbody>
							<tr>
								<td colspan="9"
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
	$(document).ready(function(){
		$("#excel").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url('Sales/getReturnsAll/0/xls/')?>";
			return false;
		});
		$('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('Sales/getReturnsAll/pdf/?v=1'.$v)?>";
            return false;
        });
	});
</script>