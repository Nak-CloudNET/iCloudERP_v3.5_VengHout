<?php
	$v = "";

	if ($this->input->post('reference_no')) {
		$v .= "&reference_no=" . $this->input->post('reference_no');
	}
	if ($this->input->post('supplier')) {
		$v .= "&supplier=" . $this->input->post('supplier');
    }
if ($this->input->post('project')) {
    $v .= "&project=" . $this->input->post('project');
}
	if ($this->input->post('warehouse')) {
		$v .= "&warehouse=" . $this->input->post('warehouse');
	}
	if ($this->input->post('user')) {
		$v .= "&user=" . $this->input->post('user');
	}
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	}
	if ($this->input->post('note')) {
		$v .= "&note=" . $this->input->post('note');
	}
	if(isset($date)){
		$v .= "&d=" . $date;
	}

?>


<script>
    $(document).ready(function () {
        var oTable = $('#POData').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true, 'bServerSide': true,
			"bStateSave": true,
            'sAjaxSource': '<?=site_url('purchases_request/getPurchasesRequest' . ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?=$this->security->get_csrf_token_name()?>",
                    "value": "<?=$this->security->get_csrf_hash()?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, {"mRender": fld}, null, null, null, {"mRender": row_status},{"mRender":currencyFormat},{"mRender": row_status}, {"bSortable": false}],
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
				var action =$('td:eq(8)',nRow);
                nRow.id = aData[0];
                nRow.className = "purchase_request_links"; 
				if(aData[6]=="approved"){
					 action.find('.approved').remove();
					 action.find('.unreject').remove();
				}else if(aData[6]=="requested"){
					action.find('.unapproved').remove();
					action.find('.unreject').remove();
				}else if(aData[6]=="reject"){
					action.find('.edit').remove();
					action.find('.create').remove();
					action.find('.unapproved').remove(); 
					action.find('.reject').remove();
				}
				if(aData[4]=="completed"){
					action.find('.edit').remove();
					action.find('.create').remove();
					action.find('.unapproved').remove(); 
					action.find('.reject').remove();
				}
                if (aData[7] == 'approved') {
                    action.find('.edit').remove();
                    action.find('.approved').remove();
                }
                if (aData[7] == 'requested') {
                    action.find('.unapproved').remove();
					action.find('.create').remove();
                }
                if (aData[7] == 'reject') {
                    action.find('.edit').remove();
                    action.find('.reject').remove();
					action.find('.create').remove();
                }
                if (aData[5] == 'completed') {
                    action.find('.approved').remove();
                    action.find('.unapproved').remove();
                    action.find('.reject').remove();
                    action.find('.create').remove();
                }
                
                return nRow;
            },
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var total = 0;
                for (var i = 0; i < aaData.length; i++) {
                    total += parseFloat(aaData[aiDisplay[i]][6]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[6].innerHTML = currencyFormat(total);
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('ref_no');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('project');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('supplier');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('request_status');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
        ], "footer");

        <?php if ($this->session->userdata('remove_pols')) {?>
        if (__getItem('poitems')) {
            __removeItem('poitems');
        }
        if (__getItem('podiscount')) {
            __removeItem('podiscount');
        }
        if (__getItem('potax2')) {
            __removeItem('potax2');
        }
        if (__getItem('poshipping')) {
            __removeItem('poshipping');
        }
        if (__getItem('poref')) {
            __removeItem('poref');
        }
        if (__getItem('powarehouse')) {
            __removeItem('powarehouse');
        }
        if (__getItem('ponote')) {
            __removeItem('ponote');
        }
        if (__getItem('posupplier')) {
            __removeItem('posupplier');
        }
        if (__getItem('pocurrency')) {
            __removeItem('pocurrency');
        }
        if (__getItem('poextras')) {
            __removeItem('poextras');
        }
        if (__getItem('podate')) {
            __removeItem('podate');
        }
        if (__getItem('postatus')) {
            __removeItem('postatus');
        }
        <?php $this->erp->unset_data('remove_pols');}
        ?>

		
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
		
		$('body').on('click', '#delete', function(e) {
			e.preventDefault();
			$('#form_action').val($(this).attr('data-action'));
			$('#action-form-submit').trigger('click');
		});
		
    });
</script>
<?php if ($Owner || !$Admin) {
	    echo form_open('purchases_request/purchase_request_actions', 'id="action-form"');
	}
?>
<style>
	
	#POData th:nth-child(1) {
		width: 4%;
	}
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue">
            <i class="fa-fw fa fa-barcode"></i><?= lang('purchase_request') . ' (' . (sizeof(explode('-',$warehouse_id)) > 1 ? lang('all_warehouses') : (empty($warehouse_id) || $warehouse_id == NULL || '' ? lang('all_warehouses') : $warehouse->name) ) . ')'; ?>
        </h2>
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
            <?php if ($Owner || $Admin || $GP['purchase_request-add'] || $GP['purchase_request-export'] || $GP['purchase_request-import'] || $GP['purchase_request-import_expanse']) { ?>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <?php if ($Owner || $Admin || $GP['purchase_request-add']) {?>
							<li>
								<a href="<?=site_url('purchases_request/add')?>">
									<i class="fa fa-plus-circle"></i> <?=lang('add_purchase_request')?>
								</a>
							</li>
						<?php } ?>
						<?php if ($Owner || $Admin || $GP['purchase_request-export']) {?>
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
						<?php if($Owner || $Admin || $GP['purchase_request-import']) { ?>
							<!--<li>
								<a class="submenu" href="<?= site_url('purchases_request/purchase_by_csv'); ?>">
									<i class="fa fa-file-text-o"></i>
									<span class="text"> <?= lang('import_purchase'); ?></span>
								</a>
							</li>-->
							
						<?php } ?>
						<?php if($Owner || $Admin || $GP['purchase_request-combine_pdf']) { ?>
							<!--<li>
								<a href="#" id="combine" data-action="combine">
									<i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>
								</a>
							</li>-->
						<?php } ?>
						<!-- <?php if ($Owner || $Admin || $GP['purchase_request-delete']) {?>
							<li class="divider"></li>
							<li>
								<a href="#" class="bpo" title="<?=$this->lang->line("delete_purchases")?>"
									data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"
									data-html="true" data-placement="left">
									<i class="fa fa-trash-o"></i> <?=lang('delete_purchases')?>
								</a>
							</li>
						<?php } ?> -->
                    </ul>
                </li>
            <?php } ?>
                <?php if (!empty($warehouses)) {
                    ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?=lang("warehouses")?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?=site_url('purchases_request')?>"><i class="fa fa-building-o"></i> <?=lang('all_warehouses')?></a></li>
                            <li class="divider"></li>
                            <?php
                            	foreach ($warehouses as $warehouse) {
                            	        echo '<li ' . ($warehouse_id && $warehouse_id == $warehouse->id ? 'class="active"' : '') . '><a href="' . site_url('purchases_request/index/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            	    }
                                ?>
                        </ul>
                    </li>
                <?php }
                ?>
            </ul>
        </div>
    </div>
	<?php if ($Owner || !$Admin) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?>
<?php }
?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?=lang('list_results');?></p>
				<div id="form">

                    <?php echo form_open("purchases_request"); ?>
                        <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

                            </div>
                        </div>

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
                                <?= lang("supplier", "supplier"); ?>
                                <?php echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ""), 'class="form-control" id="supplier"'); ?> </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="project"><?= lang("project"); ?></label>
                                <?php
                                if ($Owner || $Admin) {
                                    $pro[""] = "";
                                    foreach ($billers as $project) {
                                        $pro[$project->id] = $project->company;
                                    }
                                    echo form_dropdown('project', $pro, (isset($_POST['project']) ? $_POST['project'] : ""), 'class="form-control" id="project" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("project") . '"');
                                } else {
                                    $user_pro[""] = "";
                                    foreach ($user_billers as $user_biller) {
                                        $user_pro[$user_biller->id] = $user_biller->company;
                                    }
                                    echo form_dropdown('project', $user_pro, (isset($_POST['project']) ? $_POST['project'] : ''), 'class="form-control" id="project" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("project") . '"');
                                }
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
                        <div class="col-sm-4">
                           <div class="form-group">
                                <?= lang("note", "note"); ?>
                                <?php echo form_input('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control tip" id="note"'); ?>
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

                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>

                <div class="clearfix"></div>
                <div class="table-responsive">
                    <table id="POData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr class="active">
                            <th style="text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("ref_no"); ?></th>
                            <th><?php echo $this->lang->line("project"); ?></th>
                            <th><?php echo $this->lang->line("supplier"); ?></th>
                            <th><?php echo $this->lang->line("request_status"); ?></th>
                            <th><?php echo $this->lang->line("grand_total"); ?></th>
							<th><?php echo $this->lang->line("status"); ?></th>
                            <th style="width:100px;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="10" class="dataTables_empty"><?=lang('loading_data_from_server');?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
							
                            <th><?php echo $this->lang->line("grand_total"); ?></th>
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
<script type="text/javascript">
         $(document).ready(function(){
			     $(this).load(function(){
					  $('a.disabled-link').css({"pointer-events": "none", "cursor": "default"});
				 }); 
				 //$('a.disabled-link').css({"pointer-events": "auto", "cursor": "pointer"});
			
		 });
</script>
