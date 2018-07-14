<?php

	$v = "";
	$v = "&customer=" . $user_id;
	if ($this->input->post('submit_view_customer_balance')) {
		if ($this->input->post('reference_no')) {
			$v .= "&reference_no=" . $this->input->post('reference_no');
		}
		if ($this->input->post('biller')) {
			$v .= "&biller=" . $this->input->post('biller');
		}
		if ($this->input->post('serial')) {
            $v .= "&serial=" . $this->input->post('serial');
        }
        if ($this->input->post('sales_type')) {
            $v .= "&sales_type=" . $this->input->post('sales_type');
        }
        if ($this->input->post('issued_by')) {
			$v .= "&issued_by=" . $this->input->post('issued_by');
		}
		if ($this->input->post('start_date')) {
			$v .= "&start_date=" . $this->input->post('start_date');
		}
		if ($this->input->post('end_date')) {
			$v .= "&end_date=" . $this->input->post('end_date');
		}
		if(isset($date)){
			$v .= "&d=" . $date;
		}

        $start_date     = $this->erp->fld($this->input->post('start_date'));
        $end_date       = $this->erp->fld($this->input->post('end_date'));
        $sales_type     = $this->erp->fld($this->input->post('sales_type'));
        $issued_by      = $this->erp->fld($this->input->post('issued_by'));
	}
?>

<script>
    $(document).ready(function () {
        var oTable = $('#SLData').dataTable({
            "aaSorting": [[0, "asc"], [1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true, 'bServerSide': true,
			'sAjaxSource': '<?= site_url('reports/getSalemanReportDetail/?v=1' .$v) ?>',
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
                nRow.className = "invoice_link";
                return nRow;
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, {"mRender": fld}, {"mRender": fld}, null, null, null, null, {"mRender": row_status}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": row_status}],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0, paid = 0, balance = 0, tReturn = 0, tDeposit = 0, tDiscount = 0;
                for (var i = 0; i < aaData.length; i++) {
                    gtotal += parseFloat(aaData[aiDisplay[i]][8]);
                    tReturn += parseFloat(aaData[aiDisplay[i]][9]);
                    paid += parseFloat(aaData[aiDisplay[i]][10]);
                    tDeposit += parseFloat(aaData[aiDisplay[i]][11]);
                    tDiscount += parseFloat(aaData[aiDisplay[i]][12]);
                    balance += parseFloat(aaData[aiDisplay[i]][13]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[8].innerHTML = currencyFormat(parseFloat(gtotal));
                nCells[9].innerHTML = currencyFormat(parseFloat(tReturn));
                nCells[10].innerHTML = currencyFormat(parseFloat(paid));
                nCells[11].innerHTML = currencyFormat(parseFloat(tDeposit));
                nCells[12].innerHTML = currencyFormat(parseFloat(tDiscount));
                nCells[13].innerHTML = currencyFormat(parseFloat(balance));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('due_date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('shop');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('issued_by');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('sale_status');?>]", filter_type: "text", data: []},
            {column_number: 14, filter_default_label: "[<?=lang('payment_status');?>]", filter_type: "text", data: []},
        ], "footer");

        $(document).on('click', '.sledit', function (e) {
            if (__getItem('slitems')) {
                e.preventDefault();
                var href = $(this).attr('href');
                bootbox.confirm("<?=lang('you_will_loss_sale_data')?>", function (result) {
                    if (result) {
                        window.location.href = href;
                    }
                });
            }
        });

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
	echo form_open('reports/saleman_report_action/'. $user_id, 'id="action-form"');
?>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
            class="fa-fw fa fa-heart"></i><?=lang('saleman_detail_report_');?>
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
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <?php if ($Owner || $Admin) { ?>
							<li>
								<a href="#" id="excel" data-action="excel">
									<i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
								</a>
							</li>
							<li>
								<a href="#" id="pdf" data-action="pdf">
									<i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>
								</a>
							</li>
							
						<?php }else{ ?>
							<?php if($GP['sales-export']) { ?>
								<li>
									<a href="#" id="excel" data-action="excel">
										<i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
									</a>
								</li>
								<li>
									<a href="#" id="pdf" data-action="pdf">
										<i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>
									</a>
								</li>
							<?php }?>
						<?php }?>
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
        </div>
    </div>
    
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <input type="hidden" name="start_date" value="<?= $start_date ?>" id="start_date"/>
        <input type="hidden" name="end_date" value="<?= $end_date ?>" id="end_date"/>
        <input type="hidden" name="sales_type" value="<?= $sales_type ?>" id="sales_type"/>
        <input type="hidden" name="issued_by" value="<?= $issued_by ?>" id="issued_by"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?>

	<div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?=lang('list_results');?></p>
				<div id="form">
				<?php echo form_open("reports/view_saleman_report/" . $user_id); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>
								
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
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            <?= lang("sales_type", "sales_type"); ?>
                                <?php
                                    $sales_type = array(
                                        'all' => lang("all"),
                                        'wholesale' => lang("Wholesale"),
                                        'retail' => lang("Retail")
                                    );
                                    echo form_dropdown('sales_type', $sales_type, (isset($_POST['sales_type']) ? $_POST['sales_type'] : ""), 'id="sales_type" class="form-control sales_type"');
                                ?>                          
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            <?= lang("issued_by", "issued_by"); ?>
                                <?php
                                    $issued_by = array(
                                        'show' => lang("show"),
                                        'hide' => lang("hide")
                                    );
                                    echo form_dropdown('issued_by', $issued_by, (isset($_POST['issued_by']) ? $_POST['issued_by'] : ""), 'id="issued_by" class="form-control issued_by"');
                                ?>                          
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_view_customer_balance', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
               
					<?php echo form_close(); ?>
                </div>

                <div class="clearfix"></div>
                <div class="table-responsive">
                    <table id="SLData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("due_date"); ?></th>
                            <th><?php echo $this->lang->line("reference_no"); ?></th>
                            <th><?php echo $this->lang->line("shop"); ?></th>
                            <th><?php echo $this->lang->line("customer"); ?></th>
                            <th><?php echo $this->lang->line("issued_by"); ?></th>
                            <th><?php echo $this->lang->line("sale_status"); ?></th>
                            <th><?php echo $this->lang->line("grand_total"); ?></th>
                            <th><?php echo $this->lang->line("return"); ?></th>
                            <th><?php echo $this->lang->line("paid"); ?></th>
                            <th><?php echo $this->lang->line("deposit"); ?></th>
                            <th><?php echo $this->lang->line("discount"); ?></th>
                            <th><?php echo $this->lang->line("balance"); ?></th>
                            <th><?php echo $this->lang->line("payment_status"); ?></th>
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
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><?php echo $this->lang->line("grand_total"); ?></th>
                            <th><?php echo $this->lang->line("paid"); ?></th>
                            <th><?php echo $this->lang->line("balance"); ?></th>
                            <th></th>
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
		/*
		$("#excel").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url('Sales/getSalesAll/0/xls/')?>";
			return false;
		});
		$('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('Sales/getSalesAll/pdf/?v=1'.$v)?>";
            return false;
        });
		*/
		
		$('body').on('click', '#combine_pay', function() {
			 if($('.checkbox').is(":checked") === false){
                    alert('Please select at least one.');
					return false;
                }
                var idd = '<?=$user_id;?>';
                var arrItems = [];
                $('.checkbox').each(function(i){
                    if($(this).is(":checked")){
                        if(this.value != ""){
                            arrItems[i] = $(this).val();   
                        }
                    }
                });
				
                $('#myModal').modal({remote: '<?=base_url('sales/combine_payment_customer');?>?data=' + arrItems + '&idd='+ idd +''});
                $('#myModal').modal('show');
        });
	});
</script>