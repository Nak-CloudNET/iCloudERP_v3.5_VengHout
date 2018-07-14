<?php
	$v='';
	if ($this->input->post('start_date')) {
			$v .= "&start_date=" . $this->input->post('start_date');
		}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	}
	if ($this->input->post('referno')) {
		$v .= "&referno=" . $this->input->post('referno');
	}
	if ($this->input->post('empno')) {
		$v .= "&empno=" . $this->input->post('empno');
	}
?>


<script>
    $(document).ready(function () {
				
		function row_statusX(x) {
			if(x == null) {
				return '';
			} else if(x == 'return' || x == 'book' || x == 'free') {
				return '<div class="text-center"><span class="label label-warning">'+lang[x]+'</span></div>';
			} else if(x == 'use' || x == 'paid' || x == 'sent' || x == 'received') {
				return '<div class="text-center"><span class="label label-success">'+lang[x]+'</span></div>';
			} else if(x == 'partial' || x == 'partial_payment' || x == 'transferring' || x == 'ordered'  || x == 'busy'  || x == 'processing') {
				return '<div class="text-center"><span class="label label-info">'+lang[x]+'</span></div>';
			} else if(x == 'due' || x == 'returned') {
				return '<div class="text-center"><span class="label label-danger">'+lang[x]+'</span></div>';
			} else {
				return '<div class="text-center"><span class="label label-default">'+lang[x]+'</span></div>';
			}
		}
        $('#UnitTable').dataTable({
            "aaSorting": [[7, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('products/datatable_using_stock').'/?v=1'.$v ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },

			'fnRowCallback': function (nRow, aaData, iDisplayIndex) {
					var action = $('td:eq(8)', nRow);
					var returned = aaData[7];
					alert(returned);
					if(returned=="return"){
						action.find('.add_return').remove();
						action.find('.edit_using').remove();
					}else{
						action.find('.edit_return').remove();
					}
				return nRow;
			},
		"aoColumns": [{"bSortable": false, "mRender": checkbox},null,null,null,null,null,null,{"mRender":row_statusX}, {"mRender": currencyFormat},{"bSortable": false},],
			"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0, paid = 0, balance = 0, status = ' ';
		
            }
        }).fnSetFilteringDelay().dtFilter([
			
			{column_number: 1, filter_default_label: "[<?=lang('date');?>]", filter_type: "text", data: []},
			{column_number: 2, filter_default_label: "[<?=lang('reference_no');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('Project');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('Warehouse');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('Employee');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('Description');?>]", filter_type: "text", data: []},
			{column_number: 7, filter_default_label: "[<?=lang('Status');?>]", filter_type: "text", data: []},
			{column_number: 8, filter_default_label: "[<?=lang('cost');?>]", filter_type: "text", data: []},
		
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
    });
</script>
<?php
	echo form_open('products/using_stock_action', 'id="action-form"');

?>

<div class="box">
    <div class="box-header">
		<h2 class="blue"><i	class="fa-fw fa fa-heart"></i><?=lang('list_using_stock') . ' (' . (isset($warehouses->id) ? $warehouses->name : lang('all_warehouses')) . ')';?>		
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

                <?php if ($GP['products-export']) { ?>
				<li class="dropdown">
                    <a href="#" id="excel" data-action="export_excel" title="<?= lang('download_xls') ?>">
                        <i class="icon fa fa-file-excel-o"></i>
                    </a>
                </li>
                <?php } ?>

            </ul>
        </div>
        <!--<div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a href="<?=site_url('sales/add')?>">
                                <i class="fa fa-plus-circle"></i> <?=lang('add_sale')?>
                            </a>
                        </li>
						<?php if ($Owner || $Admin) { ?>
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
							
							<li>
								<a href="<?= site_url('sales/customer_opening_balance'); ?>">
									<i class="fa fa-plus-circle"></i>
									<span class="text"> <?= lang('add_sale_by_csv'); ?></span>
								</a>
							</li>
						<?php }else{ ?>
							<?php if($GP['sales-export']) { ?>
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
							<?php }?>
							
							<?php if($GP['sales-import']) { ?>
								<li>
									<a href="<?= site_url('sales/sale_by_csv'); ?>">
										<i class="fa fa-plus-circle"></i>
										<span class="text"> <?= lang('add_sale_by_csv'); ?></span>
									</a>
								</li>
							<?php }?>
						<?php }?>
                        <li>
                            <a href="#" id="combine" data-action="combine">
                                <i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#" class="bpo"
                            title="<?=$this->lang->line("delete_sales")?>"
                            data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"
                            data-html="true" data-placement="left">
                            <i class="fa fa-trash-o"></i> <?=lang('delete_sales')?>
                        </a>
                    </li>
                    </ul>
                </li>
                <?php if (!empty($warehouses)) {
                    ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?=lang("warehouses")?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?=site_url('sales')?>"><i class="fa fa-building-o"></i> <?=lang('all_warehouses')?></a></li>
                            <li class="divider"></li>
                            <?php
                            	foreach ($warehouses as $warehouse) {
                            	        echo '<li><a href="' . site_url('sales/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            	    }
                                ?>
                        </ul>
                    </li>
                <?php }
                ?>
            </ul>
        </div>-->
    </div>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?> 
	<div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?=lang('list_results');?></p>
				<div id="form">

                   <?php echo form_open("products/view_using_stock"); ?>			
					<div class="box-content">
							<div class="row">
								<div class="col-lg-12">


					<div class="clearfix"></div>
						
					<div class="col-md-3">
					<div class="form-group">
						<?= lang("start_date", "start_date"); ?>
                        <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control tip date" id="start_date"'); ?>
					</div>	
					</div>
					<div class="col-md-3">
					<div class="form-group">
						<?= lang("end_date", "end_date"); ?>
                        <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control date" id="end_date"'); ?>				
					</div>
										
					</div>
					<div class="col-md-3">
					 <div class="form-group">
							<label class="control-label" for="user"><?= lang("reference_no"); ?></label>
							<?php
							$ust = array(""=>"ALL");
							foreach ($enter_using_stock as $es) {
								$ust[$es->reference_no] = $es->reference_no;
							}
							echo form_dropdown('referno', $ust, (isset($_POST['referno']) ? $_POST['referno'] : ""), 'class="form-control" id="referno2" ');
							?>
					</div>		
					</div>
					
					
					<div class="col-md-3">
					<div class="form-group">
							<label class="control-label" for="user"><?= lang("Employee_No"); ?></label>
							<?php
							$emps = array(""=>"ALL");
							foreach ($empno as $es) {
								$emps[$es->username] = $es->username;
							}
							echo form_dropdown('empno', $emps, (isset($_POST['empno']) ? $_POST['empno'] : ''), 'class="form-control" id="empno2" ');
							?>
					</div>		
					</div>
					 
					</div>
					<div class="col-md-12">
					
						<div class="col-md-3">
						<div class="form-group">

						<button type="submit" class="btn btn-primary input-xs">Search</button>
						</div>
						</div>
					</div>
					
					</div>
					</div>
					<?php echo form_close(); ?>

                </div>

                <div class="clearfix"></div>
                <div class="table-responsive">
                    <table id="UnitTable" class="table table-condensed table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="min-width:30px; width: 30px; text-align: center;">
                                    <input class="checkbox checkth" type="checkbox" name="check"/>
                                </th>
                                
                             
							<th><?php echo $this->lang->line("date"); ?></th> 
                            <th><?php echo $this->lang->line("reference_no"); ?></th>
                            <th><?php echo $this->lang->line("project"); ?></th>
                            <th><?php echo $this->lang->line("warehouse"); ?></th>
                            <th><?php echo $this->lang->line("employee"); ?></th>
							<th><?php echo $this->lang->line("description"); ?></th>
							<th><?php echo $this->lang->line("status");?></th>
							<th><?php echo $this->lang->line("cost"); ?></th>
                                <th style="width:100px;"><?= lang("actions"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="dataTables_empty">
                                    <?= lang('loading_data_from_server') ?>
                                </td>
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
                            <th><?=lang('action');?></th>
							
                            
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	$(document).ready(function(){

		// $("#excel").click(function(e){
			// e.preventDefault();
			// window.location.href = "<?=site_url('Sales/getSalesAll/0/xls/')?>";
			// return false;
		// });
		// $('#pdf').click(function (event) {
            // event.preventDefault();
            // window.location.href = "<?=site_url('Sales/getSalesAll/pdf/?v=1'.$v)?>";
            // return false;
        // });
		
	});
</script>