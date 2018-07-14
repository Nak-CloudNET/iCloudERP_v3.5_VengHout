<?php
	$v = "";
	if ($this->input->post('warehouse')) {
		$v .= "&warehouse=" . $this->input->post('warehouse');
	}
	if ($this->input->post('product_id')) {
        $v .= "&product_id=" . $this->input->post('product_id');
    }
?>
<style>
    #PVRDATA tbody tr td:nth-child(6) {
        text-align: right !important;
    }
</style>
<script>
    $(document).ready(function () {
        var oTable = $('#PVRDATA').dataTable({
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
            'sAjaxSource': '<?=site_url('reports/getProductValueReport' . ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v ?>',
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
				null, null, null,
				{"mRender": currencyFormat},
                null,
				{"mRender": currencyFormat}
			],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var qty = 0, cost = 0, tcost = 0;
                for (var i = 0; i < aaData.length; i++) {
                    qty += parseFloat(aaData[aiDisplay[i]][4]);
                    cost += parseFloat(aaData[aiDisplay[i]][5]);
                    tcost += parseFloat(aaData[aiDisplay[i]][6]);
                }
                var nCells = nRow.getElementsByTagName('th');
				nCells[4].innerHTML = currencyFormat(parseFloat(qty));
                nCells[5].innerHTML = parseFloat(cost);
				nCells[6].innerHTML = currencyFormat(parseFloat(tcost));
            }
        }).fnSetFilteringDelay().dtFilter([
			 {column_number: 1, filter_default_label: "[<?=lang('product_code');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('product_name');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('warehouse');?>]", filter_type: "text", data: []}
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
	echo form_open('reports/product_value_action', 'id="action-form"');
?>

<div class="box">
    <div class="box-header">
        
        <?php if ($warehouse_id) { ?>
            <h2 class="blue">
                <i class="fa-fw fa fa-barcode"></i>
                <?= lang('product_value_report'); ?>
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
                <?= lang('product_value_report') . ' (' . lang('all_warehouses') . ')'; ?>
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
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?>
	<div class="box-content" style="overflow-x:scroll; width: 100%;">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?=lang('list_results');?></p>
				<div id="form">

                    <?php echo form_open("reports/product_value"); ?>
                    <div class="row">
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


                    </div>
                    <div class="form-group">
                        <div class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>

                <div class="clearfix"></div>
                <div class="table-responsive">
                    <table id="PVRDATA" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th><?php echo $this->lang->line("product_code"); ?></th>
							<th><?php echo $this->lang->line("product_name"); ?></th>
                            <th><?php echo $this->lang->line("warehouse"); ?></th>
                            <th><?php echo $this->lang->line("quantity"); ?></th>
                            <th><?php echo $this->lang->line("unit_cost"); ?></th>
							<th><?php echo $this->lang->line("total_cost"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="8"
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
                            <th class="text-right" style="color:#000"></th>
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