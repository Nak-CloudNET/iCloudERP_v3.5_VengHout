<?php
	$v = "";
	/* if($this->input->post('name')){
	  $v .= "&product=".$this->input->post('product');
	  } */
	if ($this->input->post('reference_no')) {
		$v .= "&reference_no=" . $this->input->post('reference_no');
	}
	if ($this->input->post('customer')) {
		$v .= "&customer=" . $this->input->post('customer');
	}
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
	if(isset($date)){
		$v .= "&d=" . $date;
	}

?>
<!--
<style>
	#SlRData {
		overflow-x: scroll;
		max-width: 100%;
		min-height: 300px;
		display: block;
		white-space: nowrap;
	}
</style>
-->
<script>
    $(document).ready(function () {
        var oTable = $('#SlRData').dataTable({
            "aaSorting": [[0, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('taxes/getLargeSalesReport/?v=1' . $v) ?>',
            'fnServerData': function (sSource, aoData, fnCallback,nRow) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "invoice_link";
                return nRow;
            },"aoColumns":[{
                "bSortable": false,
                "mRender": checkbox
            },{"mRender": fld}, null, null, null,  null,{"mRender": currencyFormat}, {"mRender": currencyFormat},{"mRender": currencyFormat},{"mRender": currencyFormat},{"mRender": currencyFormat},{"mRender": currencyFormat},null,{"mRender": row_status_confirm}],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var amount = 0, amount_tax = 0, total_amount = 0,amount_d = 0, amount_tax_d = 0, total_amount_d = 0;
                for (var i = 0; i < aaData.length; i++) {
					amount +=parseFloat(aaData[aiDisplay[i]][6]);
                    amount_tax 	+=parseFloat(aaData[aiDisplay[i]][7]);
					total_amount+=parseFloat(aaData[aiDisplay[i]][8]);
					if((aaData[aiDisplay[i]][9])!=null){
						amount_d+=parseFloat(aaData[aiDisplay[i]][9]);
					}
					if((aaData[aiDisplay[i]][10])!=null){
                    amount_tax_d+=parseFloat(aaData[aiDisplay[i]][10]);
					}
					if((aaData[aiDisplay[i]][11])!=null){
					total_amount_d+=parseFloat(aaData[aiDisplay[i]][11]);
					}
                }
				var nCells = nRow.getElementsByTagName('th');
				nCells[6].innerHTML = currencyFormat(parseFloat(amount));
                nCells[7].innerHTML = currencyFormat(parseFloat(amount_tax));
				nCells[8].innerHTML = currencyFormat(parseFloat(total_amount));
				nCells[9].innerHTML = currencyFormat(parseFloat(amount_d));
                nCells[10].innerHTML = currencyFormat(parseFloat(amount_tax_d));
				nCells[11].innerHTML = currencyFormat(parseFloat(total_amount_d));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, 	filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
            {column_number: 2, 	filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
            {column_number: 3, 	filter_default_label: "[<?=lang('biller');?>]", filter_type: "text", data: []},
            {column_number: 4, 	filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
            {column_number: 5, 	filter_default_label: "[<?=lang('description');?>]", filter_type: "text", data: []},
			{column_number: 6, 	filter_default_label: "[<?=lang('amount');?>]", filter_type: "text", data: []},
			{column_number: 7, 	filter_default_label: "[<?=lang('amount_tax');?>]", filter_type: "text", data: []},
			{column_number: 8, 	filter_default_label: "[<?=lang('total_amount');?>]", filter_type: "text", data: []},
			{column_number: 9, 	filter_default_label: "[<?=lang('amount_declare');?>]", filter_type: "text", data: []},
			{column_number: 10, 	filter_default_label: "[<?=lang('amount_tax_declare');?>]", filter_type: "text", data: []},
			{column_number: 11, 	filter_default_label: "[<?=lang('total_amount_declare');?>]", filter_type: "text", data: []},		
			{column_number: 12, 	filter_default_label: "[<?=lang('remark');?>]", filter_type: "text", data: []},
        ], "footer");
    });
</script>
<script>
 $(document).ready(function () {
        if (__getItem('remove_slls')) {
            if (__getItem('slitems')) {
                __removeItem('slitems');
            }
            if (__getItem('sldiscount')) {
                __removeItem('sldiscount');
            }
            if (__getItem('sltax2')) {
                __removeItem('sltax2');
            }
            if (__getItem('slref')) {
                __removeItem('slref');
            }
            if (__getItem('slshipping')) {
                __removeItem('slshipping');
            }
            if (__getItem('slwarehouse')) {
                __removeItem('slwarehouse');
            }
            if (__getItem('slnote')) {
                __removeItem('slnote');
            }
            if (__getItem('slinnote')) {
                __removeItem('slinnote');
            }
            if (__getItem('slcustomer')) {
                __removeItem('slcustomer');
            }
            if (__getItem('slbiller')) {
                __removeItem('slbiller');
            }
            if (__getItem('slcurrency')) {
                __removeItem('slcurrency');
            }
            if (__getItem('sldate')) {
                __removeItem('sldate');
            }
            if (__getItem('slsale_status')) {
                __removeItem('slsale_status');
            }
            if (__getItem('slpayment_status')) {
                __removeItem('slpayment_status');
            }
            if (__getItem('paid_by')) {
                __removeItem('paid_by');
            }
            if (__getItem('amount_1')) {
                __removeItem('amount_1');
            }
            if (__getItem('paid_by_1')) {
                __removeItem('paid_by_1');
            }
            if (__getItem('pcc_holder_1')) {
                __removeItem('pcc_holder_1');
            }
            if (__getItem('pcc_type_1')) {
                __removeItem('pcc_type_1');
            }
            if (__getItem('pcc_month_1')) {
                __removeItem('pcc_month_1');
            }
            if (__getItem('pcc_year_1')) {
                __removeItem('pcc_year_1');
            }
            if (__getItem('pcc_no_1')) {
                __removeItem('pcc_no_1');
            }
            if (__getItem('cheque_no_1')) {
                __removeItem('cheque_no_1');
            }
            if (__getItem('slpayment_term')) {
                __removeItem('slpayment_term');
            }
            __removeItem('remove_slls');
        }

        <?php if ($this->session->userdata('remove_slls')) {?>
        if (__getItem('slitems')) {
            __removeItem('slitems');
        }
        if (__getItem('sldiscount')) {
            __removeItem('sldiscount');
        }
        if (__getItem('sltax2')) {
            __removeItem('sltax2');
        }
        if (__getItem('slref')) {
            __removeItem('slref');
        }
        if (__getItem('slshipping')) {
            __removeItem('slshipping');
        }
        if (__getItem('slwarehouse')) {
            __removeItem('slwarehouse');
        }
        if (__getItem('slnote')) {
            __removeItem('slnote');
        }
        if (__getItem('slinnote')) {
            __removeItem('slinnote');
        }
        if (__getItem('slcustomer')) {
            __removeItem('slcustomer');
        }
        if (__getItem('slbiller')) {
            __removeItem('slbiller');
        }
        if (__getItem('slcurrency')) {
            __removeItem('slcurrency');
        }
        if (__getItem('sldate')) {
            __removeItem('sldate');
        }
        if (__getItem('slsale_status')) {
            __removeItem('slsale_status');
        }
        if (__getItem('slpayment_status')) {
            __removeItem('slpayment_status');
        }
        if (__getItem('paid_by')) {
            __removeItem('paid_by');
        }
        if (__getItem('amount_1')) {
            __removeItem('amount_1');
        }
        if (__getItem('paid_by_1')) {
            __removeItem('paid_by_1');
        }
        if (__getItem('pcc_holder_1')) {
            __removeItem('pcc_holder_1');
        }
        if (__getItem('pcc_type_1')) {
            __removeItem('pcc_type_1');
        }
        if (__getItem('pcc_month_1')) {
            __removeItem('pcc_month_1');
        }
        if (__getItem('pcc_year_1')) {
            __removeItem('pcc_year_1');
        }
        if (__getItem('pcc_no_1')) {
            __removeItem('pcc_no_1');
        }
        if (__getItem('cheque_no_1')) {
            __removeItem('cheque_no_1');
        }
        if (__getItem('slpayment_term')) {
            __removeItem('slpayment_term');
        }
        <?php $this->erp->unset_data('remove_slls');}
        ?>

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
<?php if ($Owner) {
	    //echo form_open('sales/sale_actions', 'id="action-form"');
	}
?>
<?= form_open('taxes/selling_tax_action', 'id="action-form"') ?>
 <input type="hidden" name="form_action" value="" id="form_action"/>
  <input type="hidden" name="check_action" value="123" id="check_action"/>
<div class="box">
	    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('large_salling_tax'); ?></h2>

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
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
            		<i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                      <!--<li>
                            <a href="#" id="declare">
                                <i class="fa fa-plus-circle"></i> <?= lang('declare') ?>
                            </a>
                        </li>
						-->
					  <li><a href="<?= site_url('taxes/large_combine_tax'); ?>" data-toggle="modal" data-target="#myModal"
                               id="add_salllingtax"><i class="fa fa-plus-circle"></i> <?= lang("declare"); ?></a></li>
					   
						<!--	   
						<li><a href="<?= site_url('taxes/edit_selling_tax'); ?>" data-toggle="modal" data-target="#myModal"
                               id="edit_salllingtax"><i class="fa fa-plus-circle"></i> <?= lang("edit_sale_tax"); ?></a></li>	   
						<!--<li><a href="<?= site_url('taxes/remove_selling_tax'); ?>" data-toggle="modal"  data-target="#myModal"
                               id="remove_salllingtax"><i class="fa fa-plus-circle"></i> <?= lang("remove_from_journal"); ?></a></li>	-->

						<li>
                            <a href="#" id="delete" data-action="delete">
                                <i class="fa fa-trash-o"></i> <?= lang('non-declare') ?>
                            </a>
                        </li>
							   
							   
                        <li><a href="<?= site_url('account/import_journal_csv'); ?>" data-toggle="modal"
                               data-target="#myModal"><i class="fa fa-plus-circle"></i> <?= lang("Add Journal By CSV"); ?>
                            </a></li>
                        <li><a href="#" id="excel" data-action="export_excel"><i
                                    class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                        <li><a href="#" id="pdf" data-action="export_pdf"><i
                                    class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <div id="form">

                   
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
                                <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                <?php echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="customer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="biller"><?= lang("project"); ?></label>
                                <?php
                                $bl[""] = "";
                                foreach ($billers as $biller) {
                                    $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                }
                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("project") . '"');
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
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label"
									for="customer_group"><?php echo $this->lang->line("group_customer"); ?>
								</label>
								<div class="controls"> <?php
									foreach ($customer_groups as $customer_group) {
										$cgs[$customer_group->id] = $customer_group->name;
									}
									echo form_dropdown('customer_group', $cgs, $this->Settings->customer_group, 'class="form-control tip select" id="customer_group" style="width:100%;"');
									?>
								</div>
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
                    <table id="SlRData"
                           class="table table-bordered table-hover table-striped table-condensed reports-table">
                        <thead>
                        <tr>
							<th style="min-width:5%; width: 5%; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <th><?= lang("date"); ?></th>
                            <th><?= lang("reference_no"); ?></th>
                            <th><?= lang("biller"); ?></th>
                            <th><?= lang("customer"); ?></th>
                            <th><?= lang("description"); ?></th>
							<th><?= lang("amount"); ?></th>
							<th><?= lang("vat"); ?></th>
							<th><?= lang("total_amount"); ?></th>
							<th><?= lang("amount_declare"); ?></th>
							<th><?= lang("vat_declare"); ?></th>
							<th><?= lang("total_amount_declare"); ?></th>
							<th><?= lang("Remark"); ?></th>
							<th><?= lang("status"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="9" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
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
							<th>Status</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    <div style="display: none;">
       
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('body').on('click', '#add_salllingtax', function() {
			if($('.checkbox').is(":checked") === false){
				alert('Please select at least one.');
				return false;
			}
			var arrItems = [];
			$('.checkbox').each(function(i){
				if($(this).is(":checked")){
					if($(this).val() != ""){
						arrItems[i] = $(this).val();   
					}
				}
			});
			$('#myModal').modal({remote: '<?=base_url('taxes/large_combine_tax');?>?data=' + arrItems + ''});
			$('#myModal').modal('show');
		});

		$("#excel").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url('Taxes/getSalesReport/0/xls/')?>";
			return false;
		});
		$('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('Taxes/getSalesReport/pdf/?v=1'.$v)?>";
            return false;
        });
		
		
		$('body').on('click', '#edit_salllingtax', function() {
			if($('.checkbox').is(":checked") === false){
				alert('Please select at least one.');
				return false;
			}
			var arrItems = [];
			$('.checkbox').each(function(i){
				if($(this).is(":checked")){
					if($(this).val() != ""){
						arrItems[i] = $(this).val();   
					}
				}
			});
			$('#myModal').modal({remote: '<?=base_url('taxes/edit_selling_tax');?>?data=' + arrItems + ''});
			$('#myModal').modal('show');
		});
		$('body').on('click', '#remove_salllingtax', function() {
			if($('.checkbox').is(":checked") === false){
				alert('Please select at least one.');
				return false;
			}
			var arrItems = [];
			$('.checkbox').each(function(i){
				if($(this).is(":checked")){
					if($(this).val() != ""){
						arrItems[i] = $(this).val();   
					}
				}
			});
			$('#myModal').modal({remote: '<?=base_url('taxes/remove_selling_tax');?>?data=' + arrItems + ''});
			$('#myModal').modal('show');
		});
		
		$('#delete').click(function (e) {
            e.preventDefault();
            $('#form_action').val(this.id);
            $('#action-form-submit').trigger('click');
		
        });
		$('#declare').click(function (e) {
            e.preventDefault();
            $('#form_action').val(this.id);
            $('#action-form').submit();
		
        });

		
		
	});
</script>