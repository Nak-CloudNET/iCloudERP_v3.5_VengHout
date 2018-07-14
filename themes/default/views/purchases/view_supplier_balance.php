<div id="purcahses-con" class="tab-pane fade in">
        <?php
        $v = "&supplier=" . $user_id;
        if ($this->input->post('submit_purchase_report')) {            
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
			if (isset($biller_id)) {
				$v .= "&biller_id=" . $biller_id;
			}
			if ($this->input->post('reference_no')) {
                $v .= "&reference_no=" . $this->input->post('reference_no');
            }
        }
        ?>
		
<script>
        $(document).ready(function () {
            var oTable = $('#PoRData').dataTable({
                "aaSorting": [[0, "desc"]],
                "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "iDisplayLength": <?= $Settings->rows_per_page ?>,
                'bProcessing': true, 'bServerSide': true,
                'sAjaxSource': '<?= site_url('purchases/getViewSupplierBalance/?v=1' .$v) ?>',
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
						nRow.className = "purchase_links";
					return nRow;
				},
                "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
				},{"mRender": fld}, 
				{"mRender": fld}, 
				null,null, 
				{
                    "bSearchable": false,
                    "mRender": pqFormatPurchaseReports
                }, 
				{"mRender": currencyFormat}, 
				{"mRender": currencyFormat}, 
				{"mRender": currencyFormat},
				{"mRender": currencyFormat}, 
				{"mRender": currencyFormat},
				{"mRender": currencyFormat}, 
				{"mRender": row_status},
				{"bSortable": false}],
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                    var gtotal = 0,returnsb=0, paid = 0,tdeposit=0,dis=0, balance = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        gtotal += parseFloat(aaData[aiDisplay[i]][6]);
						returnsb += parseFloat(aaData[aiDisplay[i]][7]);
                        paid += parseFloat(aaData[aiDisplay[i]][8]);
						tdeposit += parseFloat(aaData[aiDisplay[i]][9]);
						dis += parseFloat(aaData[aiDisplay[i]][10]);
                        balance += parseFloat(aaData[aiDisplay[i]][11]);
                    }
                    var nCells = nRow.getElementsByTagName('th');
                    nCells[6].innerHTML = currencyFormat(parseFloat(gtotal));
					nCells[7].innerHTML = currencyFormat(returnsb);			
                    nCells[8].innerHTML = currencyFormat(parseFloat(paid));
					nCells[9].innerHTML = currencyFormat(tdeposit);
					nCells[10].innerHTML = currencyFormat(dis);
                    nCells[11].innerHTML = currencyFormat(parseFloat(balance));
                }
            }).fnSetFilteringDelay().dtFilter([
                {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
                {column_number: 2, filter_default_label: "[<?=lang('due_date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
                {column_number: 3, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
                {column_number: 4, filter_default_label: "[<?=lang('warehouse');?>]", filter_type: "text", data: []},
                {column_number: 5, filter_default_label: "[<?=lang('supplier');?>]", filter_type: "text", data: []},
                {column_number: 12, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
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
			echo form_open('purchases/getSupplierBalance_action/'.$user_id, 'id="action-form"');
		?>
        <div class="box purchases-table">
			
            <div class="box-header">
                <h2 class="blue"><i class="fa-fw fa fa-star nb"></i> <?= lang('view_supplier_balance'); ?> <?php
                if ($this->input->post('start_date')) {
                    echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
                }
                ?></h2>

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
                        <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a data-target="#myModal" data-toggle="modal" href="javascript:void(0)" id="combine_pay" data-action="combine_pay">
                                <i class="fa fa-money"></i> <?=lang('combine_to_pay')?>
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
						<?php }else{ ?>
							<?php if($GP['accounts-export']) { ?>
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
						<?php }?>	
                        <li>
                            <a href="#" id="combine" data-action="combine">
                                <i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>
                            </a>
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

                        <div id="form">

                            <?php echo form_open("purchases/view_supplier_balance/" . $user_id); ?>
                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <?= lang("reference_no", "reference_no"); ?>
                                        <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control" id="reference_no"'); ?>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                        <?php
                                        $wh['0'] = lang('all');
                                        foreach ($warehouses as $warehouse) {
                                            $wh[$warehouse->id] = $warehouse->name;
                                        }
                                        echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'class="form-control" id="warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                        ?>
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
                                class="controls"> <?php echo form_submit('submit_purchase_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                            </div>
                            <?php echo form_close(); ?>

                        </div>
                        <div class="clearfix"></div>


                        <div class="table-responsive">
                            <table id="PoRData"
                            class="table table-bordered table-hover table-striped table-condensed reports-table">
                            <thead>
                                <tr>
									<th style="min-width:30px; width: 30px; text-align: center;">
										<input class="checkbox checkft" type="checkbox" name="check"/>
									</th>
                                    <th><?= lang("date"); ?></th>
                                    <th><?= lang("due_date"); ?></th>
                                    <th><?= lang("reference_no"); ?></th>
                                    <th><?= lang("warehouse"); ?></th>
                                    <th><?= lang("supplier"); ?></th>
                                    <th><?= lang("amount"); ?></th>
									<th><?= lang("return"); ?></th>
                                    <th><?= lang("paid"); ?></th>
									<th><?= lang("deposit"); ?></th>
									<th><?= lang("discount"); ?></th>
                                    <th><?= lang("balance"); ?></th>
                                    <th><?= lang("payment_status"); ?></th>
									<th style="width:80px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
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
                                    <th></th>
									<th style="width:80px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
	<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function () {
			
			// click combine to payment
			$('body').on('click', '#combine_pay', function() {
			 if($('.checkbox').is(":checked") === false){
                    alert('Please select at least one.');
					return false;
                }
                var arrItems = [];
                $('.checkbox').each(function(i){
                    if($(this).is(":checked")){
                        if(this.value != ""){
                            arrItems[i] = $(this).val();   
                        }
                    }
                });
                $('#myModal').modal({remote: '<?=base_url('sales/combine_payment_supplier');?>?data=' + arrItems + ''});
                $('#myModal').modal('show');
			});
			
			
			//======= purchase return =======//
			$('#pdf_return').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchaseReturn/pdf/?v=1'.$v)?>";
				return false;
			});
			$('#xls_return').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchaseReturn/0/xls/?v=1'.$v)?>";
				return false;
			});
			$('#image_return').click(function (event) {
				event.preventDefault();
				html2canvas($('.tbl_return'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
			$('#image_1').click(function (event) {
				event.preventDefault();
				html2canvas($('.product_'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
			
			//========== payments =====//
			$('#pdf1').click(function (event) {
				event.preventDefault();
				window.location.href = "<?= site_url("reports/getPaymentsReport/pdf/?v=1". $p); ?>";
				return false;
			});
			$('#xls1').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPaymentsReport/0/xls/?v=1'.$p)?>";
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
			
			//========== product  ========//
			$('#pdf_pro').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchasedReport/pdf/?v=1'.$v)?>";
				return false;
			});
			$('#xls_pro').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchasedReport/0/xls/?v=1'.$v)?>";
				return false;
			});
			$('#image_pro').click(function (event) {
				event.preventDefault();
				html2canvas($('.Products-table'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
			//=========== purchase ==============//
			$('#xls_purchase').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchasesReport2/0/xls/?v=1'.$v)?>";
				return false;
			});
			$('#pdf_purchase').click(function (event) {
				event.preventDefault();
				window.location.href = "<?=site_url('reports/getPurchasesReport2/pdf/?v=1'.$v)?>";
				return false;
			});
			$('#image_purchase').click(function (event) {
				event.preventDefault();
				html2canvas($('.purchases-table'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
			
			//====== purchase_order ==============//
			// $('#xls_purchase_order').click(function (event) {
				// event.preventDefault();
				// window.location.href = "<?=site_url('reports/getPurchaseOrder/0/xls/?v=1'.$v)?>";
				// return false;
			// });
			// $('#pdf_purchase_order').click(function (event) {
				// event.preventDefault();
				// window.location.href = "<?=site_url('reports/getPurchaseOrder/pdf/?v=1'.$v)?>";
				// return false;
			// });
			$('#image_purchase_order').click(function (event) {
				event.preventDefault();
				html2canvas($('.purchase_order'), {
					onrendered: function (canvas) {
						var img = canvas.toDataURL()
						window.open(img);
					}
				});
				return false;
			});
		});
	</script>
