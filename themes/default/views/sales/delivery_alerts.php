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
?>

<script>
    $(document).ready(function () {
        var oTable = $('#Sale_Order').dataTable({
            "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?=site_url('sales/getDeliveryAlerts' . ($warehouse_id ? '/' . $warehouse_id : '')).'/?v=1'.$v?>',
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
                // nRow.className = "delivery_alert";
                return nRow;
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, {"mRender": fld}, null, null, null, null, {"mRender": formatQuantity},{"mRender": formatQuantity},{"mRender": formatQuantity}],
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
<?php if ($Owner) {
	   // echo form_open('sales/suspend_actions', 'id="action-form"');
	}
?>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-heart"></i><?=lang('list_delivery_alerts');?>
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
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results');?></p>
				<div id="form">

                    <?php echo form_open("sales/delivery_alerts"); ?>
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                <?php echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="customer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"'); ?>
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
                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>

                <div class="clearfix"></div>
                <div class="table-responsive">
                    <table id="Sale_Order" class="table table-bordered table-hover table-striped table-condensed">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("Sale Order Reference No."); ?></th>
                            <th><?php echo $this->lang->line("project"); ?></th>
                            <th><?php echo $this->lang->line("customer"); ?></th>
                            <th><?php echo $this->lang->line("saleman"); ?></th>
                            <th><?php echo $this->lang->line("quantity"); ?></th>
                            <th><?php echo $this->lang->line("quantity_received"); ?></th>
                            <th><?php echo $this->lang->line("balance"); ?></th>
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
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($Owner) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?>
<?php }
?>
<script>
	$(document).ready(function(){
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
	});
</script>