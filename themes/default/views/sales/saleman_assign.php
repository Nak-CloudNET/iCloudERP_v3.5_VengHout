<?php
	$v = "";
	
	if ($this->input->post('reference_no')) {
		$v .= "&reference_no=" . $this->input->post('reference_no');
	}
	if ($this->input->post('customer')) {
		$v .= "&customer=" . $this->input->post('customer');
	}
	if ($this->input->post('saleman')) {
		$v .= "&saleman=" . $this->input->post('saleman');
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
	if ($this->input->post('product_id')) {
        $v .= "&product_id=" . $this->input->post('product_id');
    }
    if ($this->input->post('group_area')) {
		$v .= "&group_area=" . $this->input->post('group_area');
	}
	if ($this->input->post('payment_status')) {
		$arr = array();
		$arr = $this->input->post('payment_status');
		$get_status = "";
		$get_status = implode('_', $arr);
		$v .= "&payment_status=" . $get_status;
	}
    
    if(isset($alert_id)){
		$v .= "&a=" . $alert_id;
	}

?>

<script>
    $(document).ready(function () {
        var oTable = $('#SLData').dataTable({
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
            'sAjaxSource': '<?=site_url('sales/getListSale' . ($sale_id ? '/' . $sale_id : '')).'/?v=1'.$v ?>',
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

                var action = $('td:eq(18)', nRow);
				
				if (aData[17] !=null) 
				{
					action.find('.down_payment').remove(); 
				}else{
					action.find('.edit_down_payment').remove(); 	
				}
				
				if(aData[9] == 'returned') {
					action.find('.edit').remove();
				}
				
				nRow.className = "invoice_link";
				
                return nRow;
            },
            "aoColumns": [
				{"bSortable": false, "mRender": checkbox}, 
				{"mRender": fld},
				null, null, null, null, null, null, null,
				{"mRender": row_status}, 
				{"mRender": currencyFormat}, 
				{"mRender": currencyFormat}, 
				{"mRender": currencyFormat}, 
				{"mRender": currencyFormat}, 
				{"mRender": currencyFormat}, 
				{"mRender": currencyFormat}, 
				{"mRender": row_status}, 
				{"sClass": "join_lease_id"},
				{"bSortable": false}
			],
            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0, tdeposit = 0, paid = 0, balance = 0, status = ' ',gpaid=0,dis = 0,return_s = 0;
                for (var i = 0; i < aaData.length; i++) {
					status = aaData[aiDisplay[i]][8];
					gtotal += parseFloat(aaData[aiDisplay[i]][10]);
					return_s += parseFloat(aaData[aiDisplay[i]][11]);
					gpaid += parseFloat(aaData[aiDisplay[i]][12]);  
					tdeposit += parseFloat(aaData[aiDisplay[i]][13]);
					dis += parseFloat(aaData[aiDisplay[i]][14]);
					balance += parseFloat(aaData[aiDisplay[i]][15]);
                }
                var nCells = nRow.getElementsByTagName('th');
				nCells[10].innerHTML = currencyFormat(parseFloat(gtotal));
                nCells[11].innerHTML = currencyFormat(parseFloat(return_s));
				nCells[12].innerHTML = currencyFormat(parseFloat(gpaid));
                nCells[13].innerHTML = currencyFormat(parseFloat(tdeposit));
				nCells[14].innerHTML = currencyFormat(parseFloat(dis));
                nCells[15].innerHTML = currencyFormat(parseFloat(balance));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
			 {column_number: 2, filter_default_label: "[<?=lang('quote_no');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('so_no');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('sale_no');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('biller');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('group_area');?>]", filter_type: "text", data: []},
			{column_number: 7, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text", data: []},
			{column_number: 8, filter_default_label: "[<?=lang('saleman');?>]", filter_type: "text", data: []},
            {column_number: 9, filter_default_label: "[<?=lang('sale_status');?>]", filter_type: "text", data: []},
            {column_number: 16, filter_default_label: "[<?=lang('payment_status');?>]", filter_type: "text", data: []},
        ], "footer");
		
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

<script>

	$(document).ready(function(){

        $('body').on('click', '#combine_pay', function(e) {
            e.preventDefault();
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

            var i = 0;
                var items = [];
                var b=false;
                var k = false;
                $.each($("input[name='val[]']:checked"), function(){
                    items[i] = {'id': $(this).val()};
                    i++;
                });
                
                $.ajax({
                    type: 'get',
                    url: site.base_url+'account/checkrefer',
                    dataType: "json",
                    async:false,
                    data: { <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>',items:items },
                    success: function (data) {
                        if(data.isAuth == 1){
                            b = true;
                        }
                        if(data.customer == 2){
                            k = true;
                        }
                    }
                });

                if(b == true){
                    bootbox.alert('Customer is not match!');
                    return false;
                }else {
                    $('#myModal').modal({remote: '<?=base_url('sales/combine_payment');?>?data=' + arrItems + ''});
                    $('#myModal').modal('show');
                    return false;
                }

            $('#form_action').val($('#combine_pay').attr('data-action'));
            $('#action-form-submit').trigger('click');
        });

	});

</script>
<style type="text/css">
.pay_interest_status,.join_lease_id
{
	display:none;
}
</style>
<?php
	echo form_open('sales/sale_actions/'.($sale_id ? $sale_id : ''), 'id="action-form"');
?>

<div class="box">
    <div class="box-header">
        
        <?php if ($warehouse_id) { ?>
            <h2 class="blue">
                <i class="fa-fw fa fa-barcode"></i>
                <?= lang('assign_sale_man'); ?>
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
                <?= lang('assign_sale_man') . ' (' . lang('all_warehouses') . ')'; ?>
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
            <?php if ($Owner || $Admin || $GP['sales-payments'] || $GP['sales-add'] || $GP['sales-export'] || $GP['sales-import'] || $GP['sales-combine_pdf']) { ?>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">

						<?php if ($Owner || $Admin || $GP['sales-payments']) { ?>
                       
                        <?php } ?>
                        <?php if ($Owner || $Admin || $GP['sales-add']) { ?>
							<li>
								<a href="<?=site_url('sales/add')?>">
									<i class="fa fa-plus-circle"></i> <?=lang('add_sale')?>
								</a>
							</li>
							<li>
								<a href="#" id="assign_sale_man" data-action="assign_sale_man">
								<i class="fa fa-plus"></i> <?= lang('assign_sale_man') ?></a>
							</li>
							
						<?php } ?>
						<?php if ($Owner || $Admin || $GP['sales-export']) { ?>
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
						
						<?php if($Owner || $Admin || $GP['sales-import']) { ?>
							<li>
								<a href="<?= site_url('sales/sale_by_csv'); ?>">
									<i class="fa fa-plus-circle"></i>
									<span class="text"> <?= lang('add_sale_by_csv'); ?></span>
								</a>
							</li>
						<?php }?>
						
						<?php if($Owner || $Admin || $GP['sales-payments']) { ?>
							<li>
								<a href="<?= site_url('sales/payment_by_csv'); ?>">
									<i class="fa fa-plus-circle"></i>
									<span class="text"> <?= lang('add_payment_by_csv'); ?></span>
								</a>
							</li>
						<?php }?>
						
						<?php if($Owner || $Admin || $GP['sales-combine_pdf']) { ?>
							<li>
								<a href="#" id="combine" data-action="combine">
									<i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>
								</a>
							</li>
						<?php }?>
						
                    </ul>
                </li>
            <?php } ?>
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
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?>
	<div class="box-content" style="overflow-x:scroll; width: 100%;">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?=lang('list_results');?></p>
				<div id="form">

                   
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
                                <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                <?php echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="customer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"'); ?>
                            </div>
                        </div>
						
						<div class="col-md-4">
							<div class="form-group">
							<?= lang("saleman", "saleman"); ?>
								<?php 
									$salemans['0'] = lang("all");
									foreach($agencies as $agency){
										$salemans[$agency->id] = $agency->username;
									}
									echo form_dropdown('saleman', $salemans, (isset($_POST['saleman']) ? $_POST['saleman'] : ""), 'id="saleman" class="form-control saleman"');
								?>							
							</div>
						</div>
						
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
                                <label class="control-label" for="project"><?= lang("project"); ?></label>
                                <?php
                                if ($Owner || $Admin) {
                                    $bl[""] = "";
                                foreach ($billers as $biller) {
                                    $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                }
                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                                } else {
                                    $user_pro[""] = "";
                                    foreach ($user_billers as $user_biller) {
                                        $user_pro[$user_biller->id] = $user_biller->company;
                                    }
                                    echo form_dropdown('biller', $user_pro, (isset($_POST['biller']) ? $_POST['biller'] : ''), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
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
                                <label class="control-label" for="group_area"><?= lang("group_area"); ?></label>
                                <?php
                                $garea[""] = "";
                                foreach ($areas as $area) {
                                    $garea[$area->areas_g_code] = $area->areas_group;
                                }
                                echo form_dropdown('group_area', $garea, (isset($_POST['group_area']) ? $_POST['group_area'] : ""), 'class="form-control" id="group_area" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("group_area") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                        <?= lang("payment_status", "payment_status"); ?>
                                        <?php
                                        $payment_status = array('due' => 'Due', 'partial' => 'Partial', 'paid' => 'Paid');
                                        echo form_dropdown('payment_status[]', $payment_status, (isset($_POST['payment_status']) ? $_POST['payment_status'] : ''), 'id="payment_status" class="form-control select" placeholder="Please select Project" style="width:100%;" multiple="multiple"');
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
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("assign_sale_man"); ?></label>
                                <?php
                                $us[""] = "";
                                foreach ($users as $user) {
                                    $us[$user->id] = $user->first_name . " " . $user->last_name;
                                }
                                echo form_dropdown('assign_sale_man', $us, (isset($_POST['user']) ? $_POST['assign_sale_man'] : ""), 'class="form-control" id="assign_sale_man" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("assign_sale_man") . '"');
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                  
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
							<th><?php echo $this->lang->line("quote_no"); ?></th>
                            <th><?php echo $this->lang->line("so_no"); ?></th>
                            <th><?php echo $this->lang->line("sale_ref"); ?></th>
                            <th><?php echo $this->lang->line("biller"); ?></th>
							<th><?php echo $this->lang->line("group_area"); ?></th>
                            <th><?php echo $this->lang->line("customer"); ?></th>
							<th><?php echo $this->lang->line("saleman"); ?></th>
                            <th><?php echo $this->lang->line("sale_status"); ?></th>
                            <th><?php echo $this->lang->line("amount"); ?></th>
							<th><?php echo $this->lang->line("return"); ?></th>
							<th><?php echo $this->lang->line("paid"); ?></th>
                            <th><?php echo $this->lang->line("deposit"); ?></th>
							<th><?php echo $this->lang->line("discount"); ?></th>  
                            <th><?php echo $this->lang->line("balance"); ?></th>
                            <th><?php echo $this->lang->line("payment_status"); ?></th>
							<th></th>
                            <th style="width:80px; text-align:center;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="18"
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
				<div class="clearfix"></div>
				<div class="form-group">
					<div class="controls"> <?php echo form_submit('btn_save_assign', $this->lang->line("save"), 'class="btn btn-primary" id="btn_save_assign" data-action="save_assign"'); ?> </div>
					 <?php echo form_close(); ?>
				</div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

 		$('body').on('click', '#btn_save_assign', function(e) {
	        e.preventDefault();
			
	        $('#form_action').val($('#btn_save_assign').attr('data-action'));
	        $('#action-form-submit').trigger('click');
    	});   
	
</script>