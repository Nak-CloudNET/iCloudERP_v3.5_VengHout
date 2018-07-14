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
					var oTable = $('#POSData').dataTable({
						"aaSorting": [[0, "asc"], [1, "desc"]],
						"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
						"iDisplayLength": <?= $Settings->rows_per_page ?>,
						'bProcessing': true, 'bServerSide': true,
						'sAjaxSource': '<?= site_url('pos/getPos'.($warehouse_id ? '/'.$warehouse_id : '')) ?>',
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
							nRow.className = "receipt_link";
							var delivery_status = aData[10].split('___');
							if(delivery_status[1] == "completed"){
								$('td:eq(11)', nRow).find('.add_delivery').remove();
							}
							return nRow;
						},
						"aoColumns": [{
							"bSortable": false,
							"mRender": checkbox
						}, {"mRender": fld}, null, null, null, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": row_status}, {"mRender": row_status},{"mRender": pos_delivery_status},{"bSortable": false}],
						"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay){
							var gtotal = 0, paid = 0, balance = 0;
							for (var i = 0; i < aaData.length; i++) {
								gtotal += parseFloat(aaData[aiDisplay[i]][5]);
								paid += parseFloat(aaData[aiDisplay[i]][6]);
								balance += parseFloat(aaData[aiDisplay[i]][7]);
							}
							var nCells = nRow.getElementsByTagName('th');
							nCells[5].innerHTML = currencyFormat(parseFloat(gtotal));
							nCells[6].innerHTML = currencyFormat(parseFloat(paid));
							nCells[7].innerHTML = currencyFormat(parseFloat(balance));
						}
					}).fnSetFilteringDelay().dtFilter([
						{column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
						{column_number: 2, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
						{column_number: 3, filter_default_label: "[<?=lang('biller');?>]", filter_type: "text", data: []},
						{column_number: 4, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text"},
						{column_number: 8, filter_default_label: "[<?=lang('payment_status');?>]", filter_type: "text", data: []},
						{column_number: 9, filter_default_label: "[<?=lang('payment_status');?>]", filter_type: "text", data: []},
						{column_number: 10, filter_default_label: "[<?=lang('payment_status');?>]", filter_type: "text", data: []},
					], "footer");
				});
				$('.close').click(function(event){
                    event.preventDefault();
					$('.ajaxCall').show();
					document.location.reload(true);
					$('.ajaxCall').hide();
				});
			</script>
			<?php if ($Owner) {
				echo form_open('sales/sale_actions', 'id="action-form"');
			} ?>
			<div class="box">
				<div class="box-header">
					<h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('pos_sales') //. ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'; ?>
					</h2>

					<div class="box-icon">
						<ul class="btn-tasks">
							<li class="dropdown">
								<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"  data-placement="left" title="<?= lang("actions") ?>"></i></a>
								<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
									<li><a href="<?= site_url('pos') ?>"><i class="fa fa-plus-circle"></i> <?= lang('add_sale') ?></a></li>
									<li><a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
									<li><a href="#" id="pdf" data-action="export_pdf"><i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
									<li class="divider"></li>
									<li><a href="#" class="bpo" title="<b><?= $this->lang->line("delete_sales") ?></b>" data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_sales') ?></a></li>
								</ul>
							</li>
							<?php if (!empty($warehouses)) { ?>
								<li class="dropdown">
									<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
									<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
										<li><a href="<?= site_url('pos/sales') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
										<li class="divider"></li>
										<?php
										foreach ($warehouses as $warehouse) {
											echo '<li><a href="' . site_url('pos/sales/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
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
								<table id="POSData" class="table table-bordered table-hover table-striped">
									<thead>
											<tr>
												<th style="min-width:30px; width: 30px; text-align: center;">
												<input class="checkbox checkth" type="checkbox" name="check"/>
											</th>
											<th><?= lang("date"); ?></th>
											<th><?= lang("reference_no"); ?></th>
											<th><?= lang("biller"); ?></th>
											<th><?= lang("customer"); ?></th>
											<th><?= lang("grand_total"); ?></th>
											<th><?= lang("paid"); ?></th>
											<th><?= lang("balance"); ?></th>
											<th><?= lang("payment_status"); ?></th>
											<th><?= lang("sale_status"); ?></th>
											<th><?= lang("delivery_status"); ?></th>
											<th style="width:80px; text-align:center;"><?= lang("actions"); ?></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td colspan="10" class="dataTables_empty"><?= lang("loading_data"); ?></td>
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
											<th><?= lang("grand_total"); ?></th>
											<th><?= lang("paid"); ?></th>
											<th><?= lang("balance"); ?></th>
											<th class="defaul-color"></th>
											<th class="defaul-color"></th>
											<th class="defaul-color"></th>
											<th style="width:80px; text-align:center;"><?= lang("actions"); ?></th>
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
						window.location.href = "<?=site_url('Pos/getPosSales/0/xls/')?>";
						return false;
					});
					$('#pdf').click(function (event) {
						event.preventDefault();
						window.location.href = "<?=site_url('Pos/getPosSales/pdf/?v=1'.$v)?>";
						return false;
					});
				});
			</script>
        </div>
    </div>
</div>