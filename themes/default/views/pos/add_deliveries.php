

<div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
                <i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel">POS List</h4>
        </div>
        <div class="modal-body">
			<script type="text/javascript">
				var lang = {paid: '<?=lang('paid');?>', pending: '<?=lang('pending');?>', completed: '<?=lang('completed');?>', ordered: '<?=lang('ordered');?>', received: '<?=lang('received');?>', partial: '<?=lang('partial');?>', sent: '<?=lang('sent');?>', r_u_sure: '<?=lang('r_u_sure');?>', due: '<?=lang('due');?>', returned: '<?=lang('returned');?>', transferring: '<?=lang('transferring');?>', active: '<?=lang('active');?>', inactive: '<?=lang('inactive');?>', unexpected_value: '<?=lang('unexpected_value');?>', select_above: '<?=lang('select_above');?>'};
			</script>
			<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
			<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.dtFilter.min.js"></script>
			<script type="text/javascript" src="<?= $assets ?>js/core.js"></script>
			<script>
				$(document).ready(function () {
					var oTable = $('#DOData').dataTable({
						"aaSorting": [[2, "desc"]],
						"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
						"iDisplayLength": <?= $Settings->rows_per_page ?>,
						'bProcessing': true, 'bServerSide': true,
						'sAjaxSource': '<?= site_url('sales/getSales_items').'/'.$start_date.'/'.$end_date ?>',
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
							nRow.className = "";
							return nRow;
						},
						"aoColumns": [{
							"bSortable": false,
							"mRender": checkbox
						}, {"mRender": fld}, null, null, null, null, 
						{"mRender": currencyFormat},{"mRender": currencyFormat},
						{"mRender": currencyFormat},{"mRender": invoice_delivery_status}],
						"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
							var total_quantity = 0;
							var total_quantity_received=0;
							var total_balance = 0;
							for (var i = 0; i < aaData.length; i++) {
								total_quantity += parseFloat(aaData[aiDisplay[i]][6]);
								total_quantity_received+= parseFloat(aaData[aiDisplay[i]][7]);
								total_balance += parseFloat(aaData[aiDisplay[i]][8]);
							}
							var nCells = nRow.getElementsByTagName('th');
							nCells[6].innerHTML = currencyFormat(parseFloat(total_quantity));
							nCells[7].innerHTML = currencyFormat(parseFloat(total_quantity_received));
							nCells[8].innerHTML = currencyFormat(parseFloat(total_balance));
						}
					}).fnSetFilteringDelay().dtFilter([
						
						{column_number: 1, filter_default_label: "[<?=lang('date');?>]", filter_type: "text", data: []},
						{column_number: 2, filter_default_label: "[<?=lang('sale_reference_no');?>]", filter_type: "text", data: []},
						{column_number: 3, filter_default_label: "[<?=lang('project');?>]", filter_type: "text", data: []},
						{column_number: 4, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
						{column_number: 5, filter_default_label: "[<?=lang('saleman');?>]", filter_type: "text", data: []},
						{column_number: 9, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
					], "footer");
					
					
					var oTable = $('#Sale_Order').dataTable({
						"aaSorting": [[1, "desc"]],
						"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
						"iDisplayLength": <?= $Settings->rows_per_page ?>,
						'bProcessing': true, 'bServerSide': true,
						'sAjaxSource': '<?= site_url('sales/getPOSOrderitems').'/'.$start_date.'/'.$end_date ?>',
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
							nRow.className = "";
							return nRow;
						},
						"aoColumns": [{
							"bSortable": false,
							"mRender": checkbox
						}, {"mRender": fld}, null, null, null, null, {"mRender": formatQuantity},{"mRender": formatQuantity},{"mRender": formatQuantity},{"mRender": pos_delivery_status},{bsortable:false}],
						"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
							var total_quantity = 0;
							var total_quantity_received=0;
							var total_balance = 0;
							for (var i = 0; i < aaData.length; i++) {
								total_quantity += parseFloat(aaData[aiDisplay[i]][6]);
								total_quantity_received+= parseFloat(aaData[aiDisplay[i]][7]);
								total_balance += parseFloat(aaData[aiDisplay[i]][8]);
							}
							var nCells = nRow.getElementsByTagName('th');
							nCells[6].innerHTML = formatQuantity(parseFloat(total_quantity));
							nCells[7].innerHTML = formatQuantity(parseFloat(total_quantity_received));
							nCells[8].innerHTML = formatQuantity(parseFloat(total_balance));
						}
					}).fnSetFilteringDelay().dtFilter([
						
						{column_number: 1, filter_default_label: "[<?=lang('date');?>]", filter_type: "text", data: []},
						{column_number: 2, filter_default_label: "[<?=lang('sale_reference_no');?>]", filter_type: "text", data: []},
						{column_number: 3, filter_default_label: "[<?=lang('project');?>]", filter_type: "text", data: []},
						{column_number: 4, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
						{column_number: 5, filter_default_label: "[<?=lang('saleman');?>]", filter_type: "text", data: []},
						{column_number: 9, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
						
					], "footer");
				});
				$('.close').click(function(){
					$('.ajaxCall').show();
					document.location.reload(true);
					$('.ajaxCall').hide();
				});
			</script>
			<div class="row" style="margin-bottom: 15px;">
				<div class="col-md-12">
					<div class="box">
						<div class="box-header">
							<h2 class="blue"><i class="fa-fw fa fa-tasks"></i> <?= lang('add_deliveries') ?></h2>
						</div>
						<div class="box-content">
							<div class="row">
								<div class="col-md-12">
									<ul id="dbTab" class="nav nav-tabs">
										<?php if ($Owner || $Admin || $GP['sales-add_delivery']) { ?>
											<?php if($Settings->delivery == 'invoice' || $Settings->delivery == 'both') { ?>
													<!--<li class=""><a href="#sales"><?= lang('invoice') ?></a></li>-->
											<?php } ?>
										<?php } if ($Owner || $Admin || $GP['sales-add_delivery']) { ?>
											<?php if($Settings->delivery == 'sale_order' || $Settings->delivery == 'both') { ?>
												<li class=""><a href="#quotes"><?= lang('sale_order') ?></a></li>
											<?php } ?>
										<?php } ?>
									</ul>
									<div class="tab-content">
										<?php if ($Owner || $Admin || $GP['sales-add_delivery']) { ?>
											<div id="sales" class="tab-pane fade in">
												<div class="row">
													<div class="col-sm-12">
														<div class="table-responsive">
															<table id="DOData" class="table table-bordered table-hover table-striped table-condensed">
																<thead>
																<tr>
																	<th style="min-width:30px; width: 30px; text-align: center;">
																		<input class="checkbox checkft" type="checkbox" name="check"/>
																	</th>
																	<th><?php echo $this->lang->line("date"); ?></th>
																	<th><?php echo $this->lang->line("sale_reference_no"); ?></th>
																	<th><?php echo $this->lang->line("project"); ?></th>
																	<th><?php echo $this->lang->line("customer"); ?></th>
																	<th><?php echo $this->lang->line("saleman"); ?></th>
																	<th><?php echo $this->lang->line("quantity"); ?></th>
																	<th><?php echo $this->lang->line("quantity_received"); ?></th>
																	<th><?php echo $this->lang->line("balance"); ?></th>
																	<th><?php echo $this->lang->line("status"); ?></th>
																	<!--<th style="width:150px"><?php echo $this->lang->line("actions"); ?></th>-->
																	
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

										<?php } if ($Owner || $Admin || $GP['quotes-index']) { ?>

											<div id="quotes" class="tab-pane fade">
												<div class="row">
													<div class="col-sm-12">
														<div class="table-responsive">
															<table id="Sale_Order" class="table table-bordered table-hover table-striped table-condensed">
																<thead>
																<tr>
																	<th style="min-width:30px; width: 30px; text-align: center;">
																		<input class="checkbox checkft" type="checkbox" name="check"/>
																	</th>
																	
																	<th><?php echo $this->lang->line("date"); ?></th>
																	<th><?php echo $this->lang->line("sale_reference_no"); ?></th>
																	<th><?php echo $this->lang->line("project"); ?></th>
																	<th><?php echo $this->lang->line("customer"); ?></th>
																	<th><?php echo $this->lang->line("saleman"); ?></th>
																	<th><?php echo $this->lang->line("quantity"); ?></th>
																	<th><?php echo $this->lang->line("quantity_received"); ?></th>
																	<th><?php echo $this->lang->line("balance"); ?></th>
																	<th><?php echo $this->lang->line("status"); ?></th>
																	<th style="width:50px"><?php echo $this->lang->line("actions"); ?></th>
																</tr>
																</thead>
																<tbody>
																<tr>
																	<td colspan="8" class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
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
																	</tr>
																</tfoot>
															</table>
														</div>
													</div>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>