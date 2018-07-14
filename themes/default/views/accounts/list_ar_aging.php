<ul id="myTab" class="nav nav-tabs">
    <li class=""><a href="#sales-con" class="tab-grey"><?= lang('AR Aging'); ?></a></li>
    <li class=""><a href="#payments-con" class="tab-grey"><?= lang('0 - 30 Days'); ?></a></li>
    <li class=""><a href="#quotes-con" class="tab-grey"><?= lang('30 - 60 Days'); ?></a></li>
    <li class=""><a href="#returns-con" class="tab-grey"><?= lang('60 - 90 Days'); ?></a></li>
    <li class=""><a href="#deposits-con" class="tab-grey"><?= lang('Over 90'); ?></a></li>
</ul>

<div class="tab-content">
    <div id="sales-con" class="tab-pane fade in">
        <?php
        $v = "&customer=" . $user_id;

        if ($this->input->post('submit_sale_report')) {
            if ($this->input->post('biller')) {
               $v .= "&biller=" . $this->input->post('biller');
           }
           if ($this->input->post('warehouse')) {
               $v .= "&warehouse=" . $this->input->post('warehouse');
           }
           if ($this->input->post('user')) {
               $v .= "&user=" . $this->input->post('user');
           }
           if ($this->input->post('serial')) {
               $v .= "&serial=" . $this->input->post('serial');
           }
           if ($this->input->post('start_date')) {
               $v .= "&start_date=" . $this->input->post('start_date');
           }
           if ($this->input->post('end_date')) {
               $v .= "&end_date=" . $this->input->post('end_date');
           }	
       }
       
       ?>
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
		<?php //if ($Owner || $Admin) {
	    		echo form_open('account/list_ar_aging_actions/'.(isset($warehouse_id) ? $warehouse_id : ''), 'id="action-form"');
				//}
			?>
		<div class="box sales-table">
				<div class="box-header">
					<div class="box-icon">
						<ul class="btn-tasks">
							<li class="dropdown">
								<a href="#" class="toggle_up tip" title="<?= lang('hide_form'); ?>">
									<i class="icon fa fa-toggle-up"></i>
								</a>
							</li>
							<li class="dropdown">
								<a href="#" class="toggle_down tip" title="<?= lang('show_form'); ?>">
									<i class="icon fa fa-toggle-down"></i>
								</a>
							</li>
						</ul>
					</div>
					<div class="box-icon">
						<ul class="btn-tasks">
							<?php if ($Owner || $Admin) { ?>
							<li class="dropdown"><a href="#" id="pdf1" data-action="export_pdf1" class="tip" title="<?= lang('download_pdf'); ?>"><i class="icon fa fa-file-pdf-o"></i></a></li>
						    <li class="dropdown"><a href="#" id="xls1" data-action="export_excel1" class="tip" title="<?= lang('download_xls'); ?>"><i class="icon fa fa-file-excel-o"></i></a></li>
					   		<?php }else{ ?>
						 	<?php if($GP['accounts-export']) { ?>
						    <li class="dropdown">
						    	<a href="#" id="pdf1" data-action="export_pdf1" class="tip" title="<?= lang('download_pdf'); ?>"><i class="icon fa fa-file-pdf-o"></i></a>
							</li>
							<li class="dropdown">
							   <a href="#" id="xls1" data-action="export_excel1" class="tip" title="<?= lang('download_xls'); ?>"><i class="icon fa fa-file-excel-o"></i></a>
							</li>
							<?php }?>
							<?php }?>	
							<li class="dropdown">
								<a href="#" id="image" class="tip" title="<?= lang('save_image'); ?>"><i class="icon fa fa-file-picture-o"></i></a>
							</li>
						</ul>
					</div>
				</div>
			    <div style="display: none;">
			        <input type="hidden" name="form_action" value="" id="form_action"/>
			        <input type="hidden" name="warehouse2" value="<?php echo isset($warehouse2); ?>" id="warehouse2" />
			        <input type="hidden" name="created_by2" value="<?php echo isset($created_by2); ?>" id="created_by2" />
			        <input type="hidden" name="biller2" value="<?php echo isset($biller2); ?>" id="biller2" />
			        <input type="hidden" name="start_date2" value="<?php echo isset($start_date2); ?>" id="start_date2" />
			        <input type="hidden" name="end_date2" value="<?php echo isset($end_date2); ?>" id="end_date2" />
			        
			        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"'); ?>

			    </div>
			    <?php echo form_close();?>

	    	<div class="box-content">
				<div class="row">
					<div class="col-lg-12">
						<p class="introtext"><?= lang('A-R Aging'); ?></p>

						<div id="form">

							<?php echo form_open("account/list_ar_aging".$user_id); ?>
							<div class="row">

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
										<label class="control-label" for="biller"><?= lang("biller"); ?></label>
										<?php
										$bl[""] = "";
										foreach ($billers as $biller) {
											$bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
										}
										echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
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
								<?php if($this->Settings->product_serial) { ?>
									<div class="col-sm-4">
										<div class="form-group">
											<?= lang('serial_no', 'serial'); ?>
											<?= form_input('serial', '', 'class="form-control tip" id="serial"'); ?>
										</div>
									</div>
									<?php } ?>
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
								class="controls"> <?php echo form_submit('submit_sale_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
							</div>
							<?php echo form_close(); ?>

						</div>
						<div class="clearfix"></div>
						<!-- AR Aging Column -->
						<script>
							$(document).ready(function () {
								var oTable = $('#SLData').dataTable({
									"aaSorting": [[0, "asc"], [1, "desc"]],
									"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
									"iDisplayLength": <?=$Settings->rows_per_page?>,
									'bProcessing': true,'bServerSide': true,
									'sAjaxSource': "<?= site_url('account/getSales_pending' . ($warehouse_id ? '/' . $warehouse_id : '')) . '/?v=1' . $v ?>",
									'fnServerData': function (sSource, aoData, fnCallback) {
										aoData.push({
											"name": "<?=$this->security->get_csrf_token_name()?>",
											"value": "<?=$this->security->get_csrf_hash()?>"
										});
										$.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
									},
									'fnRowCallback': function (nRow, aData, iDisplayIndex) {
										var oSettings = oTable.fnSettings();										
										nRow.id = aData[0];
										nRow.className = "invoice_link_ar";										
										return nRow;
									},
									"aoColumns": [{
										"bSortable": false,
										"mRender": checkbox
									},
									null, 
									{"mRender": currencyFormat,"bSortable" : false}, 
									{"mRender": currencyFormat,"bSortable" : false}, 
									{"mRender": currencyFormat,"bSortable" : false}, 
									null,
									// {"mRender" : fld, "sClass": "center"},
									{"bVisible": false}],
									"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
										var gtotal = 0, paid = 0, balance = 0, ar_n = 0;
										for (var i = 0; i < aaData.length; i++) {
											gtotal += parseFloat(aaData[aiDisplay[i]][2]);
											paid += parseFloat(aaData[aiDisplay[i]][3]);
											balance += parseFloat(aaData[aiDisplay[i]][4]);
										}
										var nCells = nRow.getElementsByTagName('th');
										nCells[2].innerHTML = currencyFormat(parseFloat(gtotal));
										nCells[3].innerHTML = currencyFormat(parseFloat(paid));
										nCells[4].innerHTML = currencyFormat(parseFloat(balance));
									}
								}).fnSetFilteringDelay().dtFilter([
										
								{column_number: 1, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},], "footer");
							});
						</script>
						<div class="table-responsive">
							<table id="SLData" class="table table-striped table-bordered table-condensed table-hover dtable">
								<thead>
									<tr>
										<th style="min-width:3%; width: 3%; text-align: center;">
											<input class="checkbox checkft" type="checkbox" name="check"/>
										</th>
										<th style="min-width:30px; width: 30px; text-align: center;"><?php echo $this->lang->line("customer"); ?></th>
										<th><?php echo $this->lang->line("grand_total"); ?></th>
										<th><?php echo $this->lang->line("paid"); ?></th>
										<th><?php echo $this->lang->line("balance"); ?></th>
										<th><?php echo $this->lang->line("ar_number"); ?></th>
										<!-- <th><?php echo $this->lang->line("date"); ?></th> -->
										<th style="text-align:center;"><?= lang("actions"); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="13"
										class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
									</tr>
								</tbody>
								<tfoot class="dtFilter">
									<tr class="active">
										<th style="min-width:30px; width: 30px; text-align: center;">
											<input class="checkbox checkft" type="checkbox" name="check"/>
										</th>
										<th style="min-width:30px; width: 30px; text-align: center;"></th>
										<th><?php echo $this->lang->line("grand_total"); ?></th>
										<th><?php echo $this->lang->line("paid"); ?></th>
										<th><?php echo $this->lang->line("balance"); ?></th>
										<th><?php echo $this->lang->line("ar_number"); ?></th>
										<!-- <th><?php echo $this->lang->line("date"); ?></th> -->
										<th style="text-align:center;"><?= lang("actions"); ?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>			
		</div>
	</div>

	<div id="payments-con" class="tab-pane fade in">
		<?php //if ($Owner || $Admin) {
	    		echo form_open('account/list_ar_aging_actions2', 'id="action-form2"');
			//	}
			?>
		<div class="box payments-table">
			<div class="box-header">
				<div class="box-icon">
					<ul class="btn-tasks">
						<?php if ($Owner || $Admin) { ?>
						<li class="dropdown"><a href="#" id="pdf2" data-action="export_pdf2" class="tip" title="<?= lang('download_pdf') ?>"><i class="icon fa fa-file-pdf-o"></i></a></li>
					    <li class="dropdown"><a href="#" id="xls2" data-action="export_excel2" class="tip" title="<?= lang('download_xls') ?>"><i class="icon fa fa-file-excel-o"></i></a></li>
				   		<?php }else{ ?>
					 	<?php if($GP['accounts-export']) { ?>
					    <li class="dropdown">
					    	<a href="#" id="pdf2" data-action="export_pdf2" class="tip" title="<?= lang('download_pdf') ?>"><i class="icon fa fa-file-pdf-o"></i></a>
						</li>
						<li class="dropdown">
						   <a href="#" id="xls2" data-action="export_excel2" class="tip" title="<?= lang('download_xls') ?>"><i class="icon fa fa-file-excel-o"></i></a>
						</li>
						<?php }?>
						<?php }?>	
						<li class="dropdown">
							<a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i class="icon fa fa-file-picture-o"></i></a>
						</li>
					</ul>
				</div>
			
				
		    </div>
			    <div style="display: none;">
			        <input type="hidden" name="form_action2" value="export_excel2" id="form_action2"/>
			        <?= form_submit('performAction', 'performAction', 'id="action-form-submit2"'); ?>
			    </div>	
			 <?php echo form_close();?>	 
   		
			<div class="box-content">
				<div class="row">
					<div class="col-lg-12">
						<p class="introtext"><?= lang('0 - 30'); ?></p><div class="clearfix"></div>
						<!--  AR Column 0 - 30  -->
						<script>
							$(document).ready(function () {
								var oTable = $('#SLData0_30').dataTable({
									"aaSorting": [[0, "asc"], [1, "desc"]],
									"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
									"iDisplayLength": <?=$Settings->rows_per_page?>,
									'bProcessing': true, 'bServerSide': true,
									'sAjaxSource': '<?= site_url('account/list_ar_aging_0_30' . ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v ?>',
									'fnServerData': function (sSource, aoData, fnCallback) {
										aoData.push({
											"name": "<?=$this->security->get_csrf_token_name()?>",
											"value": "<?=$this->security->get_csrf_hash()?>"
										});
										$.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
									},
									'fnRowCallback': function (nRow, aData, iDisplayIndex) {
										var oSettings = oTable.fnSettings();
											nRow.id = aData[0];
											nRow.className = "invoice_link_ar";
											return nRow;
										},
										"aoColumns": [{
											"bSortable": false,
											"mRender": checkbox
										}, 
										null, 
										{"mRender": currencyFormat}, 
										{"mRender": currencyFormat}, 
										{"mRender": currencyFormat}, 
										null, 
										{"bVisible": false}],
										"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
											var gtotal = 0, paid = 0, balance = 0, ar_n = 0;
											for (var i = 0; i < aaData.length; i++) {
												gtotal += parseFloat(aaData[aiDisplay[i]][2]);
												paid += parseFloat(aaData[aiDisplay[i]][3]);
												balance += parseFloat(aaData[aiDisplay[i]][4]);
												ar_n += parseFloat(aaData[aiDisplay[i]][5]);
											}
											var nCells = nRow.getElementsByTagName('th');
											nCells[2].innerHTML = currencyFormat(parseFloat(gtotal));
											nCells[3].innerHTML = currencyFormat(parseFloat(paid));
											nCells[4].innerHTML = currencyFormat(parseFloat(balance));
											nCells[5].innerHTML = currencyFormat(parseFloat(ar_n));
										}
									}).fnSetFilteringDelay().dtFilter([

									{column_number: 1, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
									], "footer");
								});
						</script>
						<div class="table-responsive">
							<table id="SLData0_30" class="table table-striped table-bordered table-condensed table-hover dtable">
								<thead>
									<tr>
										<th style="min-width:30px; width: 30px; text-align: center;">
											<input class="checkbox checkft" type="checkbox" name="check"/>
										</th>
										<th><?php echo $this->lang->line("customer"); ?></th>
										<th><?php echo $this->lang->line("grand_total"); ?></th>
										<th><?php echo $this->lang->line("paid"); ?></th>
										<th><?php echo $this->lang->line("balance"); ?></th>
										<th><?php echo $this->lang->line("AR Number"); ?></th>
										<th style="text-align:center;"><?= lang("actions"); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="12"
										class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
									</tr>
								</tbody>
								<tfoot class="dtFilter">
									<tr class="active">
										<th style="min-width:30px; width: 30px; text-align: center;">
											<input class="checkbox checkft" type="checkbox" name="check"/>
										</th>
										<th></th>
										<th><?php echo $this->lang->line("grand_total"); ?></th>
										<th><?php echo $this->lang->line("paid"); ?></th>
										<th><?php echo $this->lang->line("balance"); ?></th>
										<th><?php echo $this->lang->line("AR Number"); ?></th>
										<th style="text-align:center;"><?= lang("actions"); ?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="quotes-con" class="tab-pane fade in">
		<script type="text/javascript">
			$(document).ready(function () {
				$('#payform').hide();
				$('.paytoggle_down').click(function () {
					$("#payform").slideDown();
					return false;
				});
				$('.paytoggle_up').click(function () {
					$("#payform").slideUp();
					return false;
				});
			});
		</script>
		<div class="box">
			<?php //if ($Owner || $Admin) {
	    		echo form_open('account/list_ar_aging_actions3', 'id="action-form3"');
				//}
			?>
			<div class="box-header">
				<div class="box-icon">
					<ul class="btn-tasks">
						<?php if ($Owner || $Admin) { ?>
						<li class="dropdown"><a href="#" id="pdf3" data-action="export_pdf3" class="tip" title="<?= lang('download_pdf') ?>"><i class="icon fa fa-file-pdf-o"></i></a></li>
					    <li class="dropdown"><a href="#" id="xls3" data-action="export_excel3" class="tip" title="<?= lang('download_xls') ?>"><i class="icon fa fa-file-excel-o"></i></a></li>
				   		<?php }else{ ?>
					 	<?php if($GP['accounts-export']) { ?>
					    <<li class="dropdown"><a href="#" id="pdf3" data-action="export_pdf3" class="tip" title="<?= lang('download_pdf') ?>"><i class="icon fa fa-file-pdf-o"></i></a></li>
					    <li class="dropdown"><a href="#" id="xls3" data-action="export_excel3" class="tip" title="<?= lang('download_xls') ?>"><i class="icon fa fa-file-excel-o"></i></a></li>
						<?php }?>
						<?php }?>	
						<li class="dropdown">
							<a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i class="icon fa fa-file-picture-o"></i></a>
						</li>
					</ul>
				</div>
			</div>
			    <div style="display: none;">
			        <input type="hidden" name="form_action3" value="export_excel3" id="form_action3"/>

			        <?= form_submit('performAction', 'performAction', 'id="action-form-submit3"'); ?>
			    </div>	
			 <?php echo form_close();?>	
			<div class="box-content">
				<div class="row">
					<div class="col-lg-12">
						<p class="introtext"><?php echo lang('30 - 60'); ?></p>
						<!--  AR Column 30 - 60  -->
						<script>
							$(document).ready(function () {
								var oTable = $('#SLData30_60').dataTable({
									"aaSorting": [[0, "asc"], [1, "desc"]],
									"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
									"iDisplayLength": <?=$Settings->rows_per_page?>,
									'bProcessing': true, 'bServerSide': true,
									'sAjaxSource': '<?= site_url('account/list_ar_aging_30_60' . ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v ?>',
									'fnServerData': function (sSource, aoData, fnCallback) {
										aoData.push({
											"name": "<?=$this->security->get_csrf_token_name()?>",
											"value": "<?=$this->security->get_csrf_hash()?>"
										});
										$.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
									},
									'fnRowCallback': function (nRow, aData, iDisplayIndex) {
										var oSettings = oTable.fnSettings();										
										nRow.id = aData[0];
										nRow.className = "invoice_link_ar";										
										return nRow;
									},
									"aoColumns": [{
										"bSortable": false,
										"mRender": checkbox
									}, null, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"bVisible": false}],
									"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
										var gtotal = 0, paid = 0, balance = 0, ar_n = 0;
										for (var i = 0; i < aaData.length; i++) {
											gtotal += parseFloat(aaData[aiDisplay[i]][2]);
											paid += parseFloat(aaData[aiDisplay[i]][3]);
											balance += parseFloat(aaData[aiDisplay[i]][4]);
											ar_n += parseFloat(aaData[aiDisplay[i]][5]);
										}
										var nCells = nRow.getElementsByTagName('th');
										nCells[2].innerHTML = currencyFormat(parseFloat(gtotal));
										nCells[3].innerHTML = currencyFormat(parseFloat(paid));
										nCells[4].innerHTML = currencyFormat(parseFloat(balance));
										nCells[5].innerHTML = currencyFormat(parseFloat(ar_n));
									}
								}).fnSetFilteringDelay().dtFilter([
								
								{column_number: 1, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
								], "footer");
							});
						</script>
						<div class="table-responsive">
							<table id="SLData30_60" class="table table-striped table-bordered table-condensed table-hover dtable">
								<thead>
									<tr>
										<th style="min-width:30px; width: 30px; text-align: center;">
											<input class="checkbox checkft" type="checkbox" name="check"/>
										</th>
										<th><?php echo $this->lang->line("customer"); ?></th>
										<th><?php echo $this->lang->line("grand_total"); ?></th>
										<th><?php echo $this->lang->line("paid"); ?></th>
										<th><?php echo $this->lang->line("balance"); ?></th>
										<th><?php echo $this->lang->line("AR Number"); ?></th>
										<th style="text-align:center;"><?= lang("actions"); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="13"
										class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
									</tr>
								</tbody>
								<tfoot class="dtFilter">
									<tr class="active">
										<th style="min-width:30px; width: 30px; text-align: center;">
											<input class="checkbox checkft" type="checkbox" name="check"/>
										</th>
										<th></th>
										<th><?php echo $this->lang->line("grand_total"); ?></th>
										<th><?php echo $this->lang->line("paid"); ?></th>
										<th><?php echo $this->lang->line("balance"); ?></th>
										<th><?php echo $this->lang->line("AR Number"); ?></th>
										<th style="text-align:center;"><?= lang("actions"); ?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="returns-con" class="tab-pane fade in">
		<div class="box">
		<?php //if ($Owner || $Admin) {
    		echo form_open('account/list_ar_aging_actions4', 'id="action-form4"');
			//}
		?>
		 <div class="box-header">
			<div class="box-icon">
			  <ul class="btn-tasks">
					<?php if ($Owner || $Admin) { ?>
					<li class="dropdown"><a href="#" id="pdf4" data-action="export_pdf4" class="tip" title="<?= lang('download_pdf') ?>"><i class="icon fa fa-file-pdf-o"></i></a></li>
				    <li class="dropdown"><a href="#" id="xls4" data-action="export_excel4" class="tip" title="<?= lang('download_xls') ?>"><i class="icon fa fa-file-excel-o"></i></a></li>
			   		<?php }else{ ?>
				 	<?php if($GP['accounts-export']) { ?>
				    <<li class="dropdown"><a href="#" id="pdf4" data-action="export_pdf4" class="tip" title="<?= lang('download_pdf') ?>"><i class="icon fa fa-file-pdf-o"></i></a></li>
				    <li class="dropdown"><a href="#" id="xls4" data-action="export_excel4" class="tip" title="<?= lang('download_xls') ?>"><i class="icon fa fa-file-excel-o"></i></a></li>
					<?php }?>
					<?php }?>	
					<li class="dropdown">
						<a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i class="icon fa fa-file-picture-o"></i></a>
					</li>
				</ul>
			</div>
		</div>
			    <div style="display: none;">
			        <input type="hidden" name="form_action4" value="export_excel4" id="form_action4"/>
			        <?= form_submit('performAction', 'performAction', 'id="action-form-submit4"'); ?>
			    </div>	
			 <?php echo form_close();?>	
	<div class="box-content">
		<div class="row">
		   <div class="col-lg-12">
			  <p class="introtext"><?php echo lang('60 - 90'); ?></p>
			  <!--  AR Column 60 - 90  -->
			  <script>
				$(document).ready(function () {
					var oTable = $('#SLData60_90').dataTable({
						"aaSorting": [[0, "asc"], [1, "desc"]],
						"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
						"iDisplayLength": <?=$Settings->rows_per_page?>,
						'bProcessing': true, 'bServerSide': true,
						'sAjaxSource': '<?= site_url('account/list_ar_aging_60_90' . ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v ?>',
						'fnServerData': function (sSource, aoData, fnCallback) {
							aoData.push({
								"name": "<?=$this->security->get_csrf_token_name()?>",
								"value": "<?=$this->security->get_csrf_hash()?>"
							});
							$.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
						},
						'fnRowCallback': function (nRow, aData, iDisplayIndex) {
							var oSettings = oTable.fnSettings();
					nRow.id = aData[0];
					nRow.className = "invoice_link_ar";
					return nRow;
				},
				"aoColumns": [{
					"bSortable": false,
					"mRender": checkbox
				}, null, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"bVisible": false}],
				"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
					var gtotal = 0, paid = 0, balance = 0, ar_n = 0;
					for (var i = 0; i < aaData.length; i++) {
						gtotal += parseFloat(aaData[aiDisplay[i]][2]);
						paid += parseFloat(aaData[aiDisplay[i]][3]);
						balance += parseFloat(aaData[aiDisplay[i]][4]);
						ar_n += parseFloat(aaData[aiDisplay[i]][5]);
					}
					var nCells = nRow.getElementsByTagName('th');
					nCells[2].innerHTML = currencyFormat(parseFloat(gtotal));
					nCells[3].innerHTML = currencyFormat(parseFloat(paid));
					nCells[4].innerHTML = currencyFormat(parseFloat(balance));
					nCells[5].innerHTML = currencyFormat(parseFloat(ar_n));
				}
			}).fnSetFilteringDelay().dtFilter([
			
			{column_number: 1, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
			], "footer");
		});
	</script>
	<div class="table-responsive">
	  <table id="SLData60_90" class="table table-striped table-bordered table-condensed table-hover dtable">
		<thead>
			<tr>
				<th style="min-width:30px; width: 30px; text-align: center;">
					<input class="checkbox checkft" type="checkbox" name="check"/>
				</th>
				<th><?php echo $this->lang->line("customer"); ?></th>
				<th><?php echo $this->lang->line("grand_total"); ?></th>
				<th><?php echo $this->lang->line("paid"); ?></th>
				<th><?php echo $this->lang->line("balance"); ?></th>
				<th><?php echo $this->lang->line("AR Number"); ?></th>
				<th style="text-align:center;"><?= lang("actions"); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="13"
				class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
			</tr>
		</tbody>
		<tfoot class="dtFilter">
			<tr class="active">
				<th style="min-width:30px; width: 30px; text-align: center;">
					<input class="checkbox checkft" type="checkbox" name="check"/>
				</th>
				<th></th>
				<th><?php echo $this->lang->line("grand_total"); ?></th>
				<th><?php echo $this->lang->line("paid"); ?></th>
				<th><?php echo $this->lang->line("balance"); ?></th>
				<th><?php echo $this->lang->line("AR Number"); ?></th>
				<th style="text-align:center;"><?= lang("actions"); ?></th>
			</tr>
		</tfoot>
	</table>
	</div>
	</div>
	</div>
	</div>
	</div>
	</div>
	<div id="deposits-con" class="tab-pane fade in">
		<!--  AR Column over 90  -->
		<script>
			$(document).ready(function () {
				var oTable = $('#SLData_over_90').dataTable({
					"aaSorting": [[0, "asc"], [1, "desc"]],
					"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
					"iDisplayLength": <?=$Settings->rows_per_page?>,
					'bProcessing': true, 'bServerSide': true,
					'sAjaxSource': '<?= site_url('account/list_ar_aging_over_90' . ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v ?>',
					'fnServerData': function (sSource, aoData, fnCallback) {
						aoData.push({
							"name": "<?=$this->security->get_csrf_token_name()?>",
							"value": "<?=$this->security->get_csrf_hash()?>"
						});
						$.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
					},
					'fnRowCallback': function (nRow, aData, iDisplayIndex) {
						var oSettings = oTable.fnSettings();
						nRow.id = aData[0];
						nRow.className = "invoice_link_ar";
						return nRow;
					},
					"aoColumns": [{
						"bSortable": false,
						"mRender": checkbox
					}, 
					null, 
					{"mRender": currencyFormat}, 
					{"mRender": currencyFormat}, 
					{"mRender": currencyFormat}, 
					{"bVisible": false}],
					"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
						var gtotal = 0, paid = 0, balance = 0, ar_n = 0;
						for (var i = 0; i < aaData.length; i++) {
							gtotal += parseFloat(aaData[aiDisplay[i]][2]);
							paid += parseFloat(aaData[aiDisplay[i]][3]);
							balance += parseFloat(aaData[aiDisplay[i]][4]);
							ar_n += parseFloat(aaData[aiDisplay[i]][5]);
						}
						var nCells = nRow.getElementsByTagName('th');
						nCells[2].innerHTML = currencyFormat(parseFloat(gtotal));
						nCells[3].innerHTML = currencyFormat(parseFloat(paid));
						nCells[4].innerHTML = currencyFormat(parseFloat(balance));
						nCells[5].innerHTML = currencyFormat(parseFloat(ar_n));
					}
				}).fnSetFilteringDelay().dtFilter([
				
				{column_number: 1, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
				], "footer");
			});
		</script>
		<div class="box">
		<?php //if ($Owner || $Admin) {
    		echo form_open('account/list_ar_aging_actions5', 'id="action-form5"');
			//}
		?>
			<div class="box-header">
				<div class="box-icon">
					<ul class="btn-tasks">
					<?php if ($Owner || $Admin) { ?>
					<li class="dropdown"><a href="#" id="pdf5" data-action="export_pdf5" class="tip" title="<?= lang('download_pdf') ?>"><i class="icon fa fa-file-pdf-o"></i></a></li>
				    <li class="dropdown"><a href="#" id="xls5" data-action="export_excel5" class="tip" title="<?= lang('download_xls') ?>"><i class="icon fa fa-file-excel-o"></i></a></li>
			   		<?php }else{ ?>
				 	<?php if($GP['accounts-export']) { ?>
				    <<li class="dropdown"><a href="#" id="pdf5" data-action="export_pdf5" class="tip" title="<?= lang('download_pdf') ?>"><i class="icon fa fa-file-pdf-o"></i></a></li>
				    <li class="dropdown"><a href="#" id="xls5" data-action="export_excel5" class="tip" title="<?= lang('download_xls') ?>"><i class="icon fa fa-file-excel-o"></i></a></li>
					<?php }?>
					<?php }?>	
					<li class="dropdown">
						<a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i class="icon fa fa-file-picture-o"></i></a>
					</li>
				</ul>
				</div>
			</div>
			    <div style="display: none;">
			        <input type="hidden" name="form_action5" value="export_excel5" id="form_action5"/>
			        <?= form_submit('performAction', 'performAction', 'id="action-form-submit5"'); ?>
			    </div>	
			 <?php echo form_close();?>	 
			<div class="box-content">
				<div class="row">
					<div class="col-lg-12">
						<p class="introtext"><?php echo lang('Over 90'); ?></p>

						<div class="table-responsive">
							<table id="SLData_over_90" class="table table-striped table-bordered table-condensed table-hover dtable">
								<thead>
									<tr>
										<th style="min-width:30px; width: 30px; text-align: center;">
											<input class="checkbox checkft" type="checkbox" name="check"/>
										</th>
										<th><?php echo $this->lang->line("customer"); ?></th>
										<th><?php echo $this->lang->line("grand_total"); ?></th>
										<th><?php echo $this->lang->line("paid"); ?></th>
										<th><?php echo $this->lang->line("balance"); ?></th>
										<th><?php echo $this->lang->line("AR Number"); ?></th>
										<th style="width:80px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="11"
										class="dataTables_empty"><?php echo $this->lang->line("loading_data"); ?></td>
									</tr>
								</tbody>
								<tfoot class="dtFilter">
									<tr class="active">
										<th style="min-width:30px; width: 30px; text-align: center;">
											<input class="checkbox checkft" type="checkbox" name="check"/>
										</th>
										<th></th>
										<th><?php echo $this->lang->line("grand_total"); ?></th>
										<th><?php echo $this->lang->line("paid"); ?></th>
										<th><?php echo $this->lang->line("balance"); ?></th>
										<th><?php echo $this->lang->line("AR Number"); ?></th>
										<th style="width:80px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<style type="text/css">
	.dtable { white-space:nowrap; }
</style>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		// tag1
		$('body').on('click', '#xls1', function(e) {
			e.preventDefault();
			var k = false;
			$.each($("input[name='val[]']:checked"), function(){
				
				k = true;
			});
			if(k == false){
				bootbox.alert('Please select!');
				return false;
			}
			$('#form_action').val($('#xls1').attr('data-action'));
			$('#action-form-submit').trigger('click');
		});
		$('body').on('click', '#pdf1', function(e) {
			e.preventDefault();
			var k = false;
			$.each($("input[name='val[]']:checked"), function(){
				
				k = true;
			});
			if(k == false){
				bootbox.alert('Please select!');
				return false;
			}
			$('#form_action').val($('#pdf1').attr('data-action'));
			$('#action-form-submit').trigger('click');
		});
		///////////////////////////////////////

		// tag2
		$('body').on('click', '#xls2', function(e) {
			e.preventDefault();
			var k = false;
			$.each($("input[name='val[]']:checked"), function(){
				
				k = true;
			});
			if(k == false){
				bootbox.alert('Please select!');
				return false;
			}
			//$('#form_actio2').val($('#xls2').attr('data-action'));
			$('#action-form-submit2').trigger('click');
		});
		$('body').on('click', '#pdf2', function(e) {
			e.preventDefault();
			var k = false;
			$.each($("input[name='val[]']:checked"), function(){
				
				k = true;
			});
			if(k == false){
				bootbox.alert('Please select!');
				return false;
			}
			// $('#form_action2').val($('#pdf2').attr('data-action'));
			$('#action-form-submit2').trigger('click');
		});
		///////////////////////////////////////

		// tag3
		$('body').on('click', '#xls3', function(e) {
			e.preventDefault();
			var k = false;
			$.each($("input[name='val[]']:checked"), function(){
				
				k = true;
			});
			if(k == false){
				bootbox.alert('Please select tag3!');
				return false;
			}
			//$('#form_actio2').val($('#xls2').attr('data-action'));
			$('#action-form-submit3').trigger('click');
		});
		$('body').on('click', '#pdf3', function(e) {
			e.preventDefault();
			var k = false;
			$.each($("input[name='val[]']:checked"), function(){
				
				k = true;
			});
			if(k == false){
				bootbox.alert('Please select tag3!');
				return false;
			}
			// $('#form_action2').val($('#pdf2').attr('data-action'));
			$('#action-form-submit3').trigger('click');
		});
		///////////////////////////////////////

		// tag4
		$('body').on('click', '#xls4', function(e) {
			e.preventDefault();
			var k = false;
			$.each($("input[name='val[]']:checked"), function(){
				
				k = true;
			});
			if(k == false){
				bootbox.alert('Please select tag4!');
				return false;
			}
			//$('#form_actio2').val($('#xls2').attr('data-action'));
			$('#action-form-submit4').trigger('click');
		});
		$('body').on('click', '#pdf4', function(e) {
			e.preventDefault();
			var k = false;
			$.each($("input[name='val[]']:checked"), function(){
				
				k = true;
			});
			if(k == false){
				bootbox.alert('Please select tag4!');
				return false;
			}
			// $('#form_action2').val($('#pdf2').attr('data-action'));
			$('#action-form-submit4').trigger('click');
		});
		///////////////////////////////////////

		// tag5
		$('body').on('click', '#xls5', function(e) {
			e.preventDefault();
			var k = false;
			$.each($("input[name='val[]']:checked"), function(){
				
				k = true;
			});
			if(k == false){
				bootbox.alert('Please select tag5!');
				return false;
			}
			//$('#form_actio2').val($('#xls2').attr('data-action'));
			$('#action-form-submit5').trigger('click');
		});
		$('body').on('click', '#pdf5', function(e) {
			e.preventDefault();
			var k = false;
			$.each($("input[name='val[]']:checked"), function(){
				
				k = true;
			});
			if(k == false){
				bootbox.alert('Please select tag5!');
				return false;
			}
			// $('#form_action2').val($('#pdf2').attr('data-action'));
			$('#action-form-submit5').trigger('click');
		});
		///////////////////////////////////////


		$('#image').click(function (event) {
			event.preventDefault();
			html2canvas($('.sales-table'), {
				onrendered: function (canvas) {
					var img = canvas.toDataURL()
					window.open(img);
				}
			});
			return false;
		});
		$('#image1').click(function (event) {
			event.preventDefault();
			html2canvas($('.payments-table'), {
				onrendered: function (canvas) {
					var img = canvas.toDataURL()
					window.open(img);
				}
			});
			return false;
		});
	});
</script>

