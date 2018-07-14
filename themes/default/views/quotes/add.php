<script type="text/javascript">

    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
        allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, shipping = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    var audio_success = new Audio('<?=$assets?>sounds/sound2.mp3');
    var audio_error = new Audio('<?=$assets?>sounds/sound3.mp3');
    $(document).ready(function () {
        $("#reref").attr('readonly', true);
        $('#ref_st').on('ifChanged', function () {
            if ($(this).is(':checked')) {
                // $("#reref").prop('disabled', false);
                $("#reref").attr('readonly', false);
                $("#reref").val("");
            } else {
                $("#reref").prop('readonly', true);
                var temp = $("#temp_reference_no").val();
                $("#reref").val(temp);

            }
        });

        var test2 = '<?=$this->session->userdata('remove_q2');?>';
        if (test2 == '1') {
            if (__getItem('quitems')) {
                __removeItem('quitems');
            }
            if (__getItem('qudiscount')) {
                __removeItem('qudiscount');
            }
            if (__getItem('qutax2')) {
                __removeItem('qutax2');
            }
            if (__getItem('quref')) {
                __removeItem('quref');
            }
            if (__getItem('qushipping')) {
                __removeItem('qushipping');
            }
            if (__getItem('quwarehouse')) {
                __removeItem('quwarehouse');
            }
            if (__getItem('qunote')) {
                __removeItem('qunote');
            }
            if (__getItem('quinnote')) {
                __removeItem('quinnote');
            }
            if (__getItem('qucustomer')) {
                __removeItem('qucustomer');
            }
            if (__getItem('qubiller')) {
                __removeItem('qubiller');
            }
            if (__getItem('qucurrency')) {
                __removeItem('qucurrency');
            }
            if (__getItem('qudate')) {
                __removeItem('qudate');
            }
            <?=$this->session->set_userdata('remove_q2', '0');?>
        }



        <?php if($this->input->get('customer')) { ?>
        if (!__getItem('quitems')) {
            __setItem('qucustomer', <?=$this->input->get('customer');?>);
        }

        <?php } ?>

        if (!__getItem('qudate')) {
            $("#qudate").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'erp',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
        }

        $(document).on('change', '#qudate', function (e) {
            __setItem('qudate', $(this).val());
        });
        if (qudate = __getItem('qudate')) {
            $('#qudate').val(qudate);
        }
        $(document).on('change', '#qubiller', function (e) {
            __setItem('qubiller', $(this).val());
            billerChange();
        });
        if (qubiller = __getItem('qubiller')) {
            $('#qubiller').val(qubiller);
        }

        $(document).on('change', '#slarea', function (e) {
            __setItem('group_area', $(this).val());
        });
        if (group_area = __getItem('group_area')) {
            $('#slarea').val(group_area);
        }

        $(document).on('change', '#slpayment_term', function (e) {
            __setItem('payment_term', $(this).val());
        });
        if (payment_term = __getItem('payment_term')) {
            $('#slpayment_term').val(payment_term);
        }

        if (__getItem('qucustomer')) {
            $("#add-deposit").attr('href', site.base_url + 'customers/add_deposit/' + __getItem('qucustomer'));
        }
        if (!__getItem('qutax2')) {
            __setItem('qutax2', <?=$Settings->default_tax_rate2;?>);
        }
        ItemnTotals();
        $("#add_item").autocomplete({
            source: function (request, response) {
                if (!$('#qucustomer').val()) {
                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    //response('');
                    $('#add_item').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('quotes/suggestions'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#quwarehouse").val(),
                        customer_id: $("#qucustomer").val()
                    },
                    success: function (data) {
                        response(data);

                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    // $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    //  $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var product_type = ui.item.row.type;
                    if (product_type == 'digital') {
                        $.ajax({
                            type: 'get',
                            url: '<?= site_url('sales/getDigitalPro'); ?>',
                            dataType: "json",
                            data: {
                                id: ui.item.item_id
                            },
                            success: function (result) {
                                $.each(result, function (key, value) {
                                    var row = add_invoice_item(value);
                                    if (row)
                                        $(this).val('');
                                });
                            }
                        });
                    } else {
                        var row = add_invoice_item(ui.item);
                        if (row)
                            $(this).val('');
                    }
                } else {
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });
        $('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

        $('#slarea').change(function () {
            var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('sales/getCustomersByArea') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
                            $("#qucustomer").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: scdata
                            });
                        } else {

                            $("#qucustomer").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: 'not found'
                            });
                        }
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            } else {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('sales/getCustomersCodeByArea') ?>",
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
                            $("#qucustomer").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: scdata
                            });
                        } else {
                            $("#qucustomer").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: 'not found'
                            });
                        }
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            }
            $('#modal-loading').hide();
        });

    });

    $(document).on('change', '.paid_by', function () {
        var p_val = $(this).val(),
            id = $(this).attr('id');
        if (p_val == 'none') {
            $('.dp').hide();
        }
        if (p_val == 'deposit') {
            $('.dp').show();
            checkDeposit();
        }
    });

    $(document).on('change', '.paid_by', function () {
        $('#qucustomer').trigger('change.select2');
    });

    function checkDeposit() {
        var customer_id = $("#qucustomer").val();

        if (customer_id != '') {
            $.ajax({
                type: "get", async: false,
                url: site.base_url + "sales/validate_deposit/" + customer_id,
                dataType: "json",
                success: function (data) {
                    if (data === false) {
                        $('#deposit_no').parent('.form-group').addClass('has-error');
                        bootbox.alert('<?=lang('invalid_customer')?>');
                    } else if (data.id !== null && data.id !== customer_id) {
                        $('#deposit_no').parent('.form-group').addClass('has-error');
                        bootbox.alert("<?=lang('this_customer_has_no_deposit')?>");
                        $('select').select2("val", 'none');
                    } else {
                        amount = $("#amount_1").val();
                        $('#dp_details').html('<small>Customer Name: ' + data.name + '<br>Amount: <span class="deposit_total_amount">' + (data.deposit_amount == null ? 0 : formatDecimal(data.deposit_amount)) + '</span> - Balance: <span class="deposit_total_balance">' + formatDecimal(data.deposit_amount - amount) + '</span></small>');
                        $('#deposit_no').parent('.form-group').removeClass('has-error');
                        //calculateTotals();
                        //$('#amount_1').val(data.deposit_amount - amount).focus();
                    }
                }
            });
        }
    }

    $(document).on('keyup', '#amount_1', function (event) {
        //var total_amount = $('#quick-payable').text()-0;
        var us_paid = $('#amount_1').val() - 0;

        var balance = us_paid;

        var deposit_amount = parseFloat($(".deposit_total_amount").text());
        var deposit_balance = parseFloat($(".deposit_total_balance").text());
        deposit_balance = (deposit_amount - Math.abs(us_paid));
        $(".deposit_total_balance").text(deposit_balance);
    }).on('keydown', '#amount_1', function (event) {
        // Allow: backspace, delete, tab, escape, and enter
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
            // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) ||
            // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
            // let it happen, don't do anything

        } else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                event.preventDefault();
            }
        }
    });
</script>

<style>
    .select2-result.select2-result-unselectable.select2-disabled {
        display: none;
    }
</style>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_quote'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("quotes/add", $attrib)
                ?>


                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin || $Settings->allow_change_date == 1) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "qudate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="qudate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-4">
                            <?= lang("reference_no", "slref"); ?>
                            <div style="float:left;width:100%;">
                                <div class="form-group">
                                    <div class="input-group" style="width:100%">
                                        <?php echo form_input('reference_no', $reference ? $reference : "", 'class="form-control input-tip" id="reref"'); ?>
                                        <input type="hidden" name="temp_reference_no" id="temp_reference_no"
                                               value="<?= $reference ? $reference : "" ?>"/>

                                        <div class="input-group-addon no-print"
                                             style="padding: 2px 5px;background-color:white;">
                                            <input type="checkbox" name="ref_status" id="ref_st" value="1"
                                                   style="margin-top:3px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php
                                $default_biller = JSON_decode($this->session->userdata('biller_id'));
                                if ($Owner || $Admin || !$this->session->userdata('biller_id')) {
                                echo get_dropdown_project('biller', 'qubiller');
                                } else {
                                echo get_dropdown_project('biller', 'qubiller', $default_biller[0]);
                                }
                                ?>
                            </div>
                        </div>


                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("saleman", "saleman"); ?>
                                <?php
                                $sm[''] = '';
                                foreach ($agencies as $agency) {
                                    $sm[$agency->id] = $agency->emp_code . '-' . $agency->username;
                                }
                                echo form_dropdown('saleman', $sm, (isset($_POST['saleman']) ? $_POST['saleman'] : $this->session->userdata('user_id')), 'id="slsaleman" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("saleman") . '" style="width:100%;" ');
                                ?>
                            </div>
                        </div>

                        <!--
						<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("payment_term", "slpayment_term"); ?>
								<?php
                        $ptr[""] = "";
                        foreach ($payment_term as $term) {
                            $ptr[$term->id] = $term->description;
                        }
                        echo form_dropdown('payment_term', $ptr, $sale_order->payment_term ? $sale_order->payment_term : "", 'id="slpayment_term" data-placeholder="' . lang("payment_term_tip") . '" required="required" class="form-control input-tip select" style="width:100%;"'); ?>
                            </div>
                        </div>
						-->

                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading"><?= lang('please_select_these_before_adding_product') ?></div>
                                <div class="panel-body" style="padding: 5px;">
                                    <?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?= lang("warehouse", "quwarehouse"); ?>
                                                <?php
                                                $wh[''] = '';
                                                foreach ($warehouses as $warehouse) {
                                                    $wh[$warehouse->id] = $warehouse->name;
                                                }
                                                echo form_dropdown('warehouse', '', (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="quwarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');
                                                ?>
                                            </div>
                                        </div>
                                    <?php } else if ($this->session->userdata('warehouse_id')) { ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?= lang("warehouse", "quwarehouse"); ?>
                                                <?php
                                                $wh[''] = '';
                                                foreach ($warehouses as $warehouse) {
                                                    $wh[$warehouse->id] = $warehouse->name;
                                                }
                                                echo form_dropdown('warehouse', '', (isset($_POST['warehouse']) ? $_POST['warehouse'] : $this->session->userdata('warehouse_id')), 'id="quwarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');
                                                ?>
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("group_area", "group_area"); ?>
                                            <?php
                                            $ar[''] = '';
                                            foreach ($areas as $area) {
                                                $ar[$area->areas_g_code] = $area->areas_group;
                                            }
                                            echo form_dropdown('area', $ar, (isset($_POST['area']) ? $_POST['area'] : ''), 'id="slarea" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("group_area") . '" required="required" style="width:100%;" ');
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("customer", "qucustomer"); ?>
                                            <?php if ($Owner || $Admin || $GP['customers-add']) { ?>
                                            <div class="input-group"><?php } ?>
                                                <?php
                                                echo form_input('customer_1', (isset($_POST['customer']) ? $_POST['customer'] : (isset($sale_order->company_name) ? $sale_order->company_name : $this->input->get('customer'))), 'id="qucustomer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control input-tip" style="min-width:100%;"');
                                                ?>
                                                <?php if ($Owner || $Admin || $GP['customers-add']) { ?>

                                                <div class="input-group-addon no-print"
                                                     style="padding: 2px 5px; border-left: 0;">
                                                    <a href="#" id="view-customer" class="external" data-toggle="modal"
                                                       data-target="#myModal">
                                                        <i class="fa fa-2x fa-user" id="addIcon"></i>
                                                    </a>
                                                </div>

                                                <div class="input-group-addon no-print" style="padding: 2px 5px;"><a
                                                            href="<?= site_url('customers/add/aquote'); ?>"
                                                            id="add-customer"
                                                            class="external" data-toggle="modal" data-target="#myModal"><i
                                                                class="fa fa-2x fa-plus-circle" id="addIcon"></i></a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <!--
                                        <div class="form-group">
                                            <?= lang("deposit", "qudeposit"); ?>
											
                                            <?php if ($Owner || $Admin || $GP['customers-add']) { ?>
											<div class="input-group"><?php } ?>
                                                <select name="paid_by" id="paid_by" class="form-control paid_by">
                                                    <option value="none"><?= lang("none"); ?></option>
													<option value="deposit"><?= lang("deposit"); ?></option>
                                                </select>
                                                <?php if ($Owner || $Admin || $GP['customers-add']) { ?>
                                                <div class="input-group-addon no-print" style="padding: 2px 5px;"><a
                                                        href="<?= site_url('quotes/add_deposit'); ?>" id="add-deposit"
                                                        class="external" data-toggle="modal" data-target="#myModal"><i
                                                            class="fa fa-2x fa-plus-circle" id="addIcon"></i></a></div>
                                            </div>
                                            <?php } ?>
                                        </div>
										-->
                                        <div class="form-group dp" style="display: none;">
                                            <?= lang("deposit_amount", "deposit_amount"); ?>
                                            <div class="">
                                                <input type="text" name="amount" class="form-control amount_1"
                                                       id="amount_1" placeholder="amount">
                                            </div>
                                            <div id="dp_details"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="sticker">
                            <div class="well well-sm">
                                <div class="form-group" style="margin-bottom:0;">
                                    <div class="input-group wide-tip">
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <i class="fa fa-2x fa-barcode addIcon"></i></a></div>
                                        <?php
                                        if ($this->input->get('addquote')) {

                                            $q = $this->db->get_where('erp_products', array('id' => $this->input->get('addquote')), 1);
                                            $pcode = $q->row()->code;

                                        }
                                        echo form_input('add_item', (isset($pcode) ? $pcode : ''), 'class="form-control input-lg" id="add_item" placeholder="' . $this->lang->line("add_product_to_order") . '"'); ?>
                                        <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                                            <div class="input-group-addon"
                                                 style="padding-left: 10px; padding-right: 10px;">
                                                <a href="#" id="addManually" class="tip"
                                                   title="<?= lang('add_product_manually') ?>"><i
                                                            class="fa fa-2x fa-plus-circle addIcon"
                                                            id="addIcon"></i></a>
                                                <a href="<?= site_url('quotes/add'); ?>" class="gos"></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <input type="hidden" id="exchange_rate" value="<?= $exchange_rate->rate ?>">

                        <div class="col-md-12">
                            <div class="control-group table-group">
                                <label class="table-label"><?= lang("order_items"); ?> *</label>

                                <div class="controls table-controls table-responsive">
                                    <table id="quTable"
                                           class="table items table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                        <tr>
                                            <th class=""><?= lang("no"); ?></th>
                                            <?php if ($setting->show_code == 1 && $setting->separate_code == 1) { ?>
                                                <th class="col-md-2"><?= lang("product_code"); ?></th>
                                                <th class="col-md-4"><?= lang("product_name"); ?></th>
                                            <?php } ?>
                                            <?php if ($setting->show_code == 1 && $setting->separate_code == 0) { ?>
                                                <th class="col-md-4"><?= lang("product_name") . " (" . lang("product_code") . ")"; ?></th>
                                            <?php } ?>
                                            <?php if ($setting->show_code == 0) { ?>
                                                <th class="col-md-4"><?= lang("product_name"); ?></th>
                                            <?php } ?>

                                            <th class="col-md-1"><?= lang("unit_price"); ?></th>
                                            <th class="col-md-1"><?= lang("quantity"); ?></th>

                                            <?php if ($Settings->shipping && isset($quote_id)) { ?>
                                                <th class="col-md-1"><?= lang("quantity_recieve"); ?></th>
                                            <?php } ?>

                                            <th class="col-md-1"><?= lang("qoh"); ?></th>
                                            <?php

                                            if ($Settings->product_discount || ($Owner || $Admin || $this->session->userdata('allow_discount'))) {
                                                echo '<th class="col-md-1">' . $this->lang->line("discount") . '</th>';
                                            }
                                            ?>
                                            <?php
                                            if ($Settings->tax1) {
                                                echo '<th class="col-md-2">' . $this->lang->line("product_tax") . '</th>';
                                            }
                                            ?>
                                            <th><?= lang("subtotal"); ?> (<span
                                                        class="currency"><?= $default_currency->code ?></span>)
                                            </th>
                                            <th style="width: 30px !important; text-align: center;"><i
                                                        class="fa fa-trash-o"
                                                        style="opacity:0.5; filter:alpha(opacity=50);"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?= lang("quote_discount", "qudiscount"); ?>
                                        <?php echo form_input('discount', '', 'class="form-control input-tip" id="qudiscount"'); ?>
                                    </div>
                                </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("shipping", "qushipping"); ?>
                                    <?php echo form_input('shipping', '', 'class="form-control input-tip" id="qushipping"'); ?>

                                </div>
                            </div>
                            <?php if ($Settings->tax2) { ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?= lang("order_tax", "qutax2"); ?>
                                        <?php
                                        $tr[""] = "";
                                        foreach ($tax_rates as $tax) {
                                            $tr[$tax->id] = $tax->name;
                                        }
                                        echo form_dropdown('order_tax', $tr, (isset($_POST['tax2']) ? $_POST['tax2'] : $Settings->default_tax_rate2), 'id="qutax2" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("order_tax") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-md-4" style="display:none">
                                <div class="form-group">
                                    <?= lang("status", "qustatus"); ?>
                                    <?php $st = array('pending' => lang('pending'), 'sent' => lang('sent'));
                                    echo form_dropdown('status', $st, '', 'class="form-control input-tip" id="qustatus"'); ?>

                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("document", "document") ?>
                                    <input id="document" type="file" name="document" data-show-upload="false"
                                           data-show-preview="false" class="form-control file">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>

                        <div class="row" id="bt">
                            <div class="col-sm-12">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <?= lang("note", "qunote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="qunote" style="margin-top: 10px; height: 100px;"'); ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12">
                            <div
                                    class="fprom-group"><?php echo form_submit('add_quote', $this->lang->line("submit"), 'id="add_quote" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></div>
                        </div>
                    </div>
                </div>
                <div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">
                    <table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
                        <tr class="warning">
                            <td><?= lang('items') ?> <span class="totals_val pull-right" id="titems">0</span></td>
                            <td><?= lang('total') ?> <span class="totals_val pull-right" id="total">0.00</span></td>
                            <?php if ($Owner || $Admin || $this->session->userdata('allow_discount')) { ?>
                                <td><?= lang('discount') ?> <span class="totals_val pull-right"
                                                                        id="tds">0.00</span></td>
                            <?php } ?>
                            <td><?= lang('shipping') ?> <span class="totals_val pull-right" id="tship">0.00</span></td>
                            <?php if ($Settings->tax2) { ?>
                                <td><?= lang('order_tax') ?> <span class="totals_val pull-right" id="ttax2">0.00</span>
                                </td>
                            <?php } ?>
                            <td><?= lang('grand_total') ?> <span class="totals_val pull-right" id="gtotal">0.00</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

<div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                                class="fa fa-2x">&times;</i></span><span class="sr-only"><?= lang('close'); ?></span>
                </button>
                <h4 class="modal-title" id="prModalLabel"></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?= lang('product_tax') ?></label>
                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('ptax', $tr, "", 'id="ptax" class="form-control pos-input-tip" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group" id="dvpiece">
                        <label for="piece" id="lbpiece" class="col-sm-4 control-label"><?= lang('piece') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="piece">
                        </div>
                    </div>
                    <div class="form-group" id="dvwpiece">
                        <label for="wpiece" class="col-sm-4 control-label"><?= lang('wpiece') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="wpiece">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pquantity">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="poption" class="col-sm-4 control-label"><?= lang('product_option') ?></label>

                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pgroup_prices" class="col-sm-4 control-label"><?= lang('group_price') ?></label>

                        <div class="col-sm-8">
                            <div id="pgroup_prices-div"></div>
                        </div>
                    </div>

                    <!--
					<div class="form-group">
                        <label for="pg" class="col-sm-4 control-label"><?= lang('price_groups') ?></label>

                        <div class="col-sm-8">
                            <div id="pg-div"></div>
                        </div>
                    </div>
					-->
                    <?php if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount'))) { ?>
                        <div class="form-group">
                            <label for="pdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pdiscount">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pprice" class="col-sm-4 control-label"><?= lang('unit_price') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pprice_show">
                            <input type="hidden" class="form-control" id="pprice">
                            <input type="hidden" class="form-control" id="curr_rate">
                        </div>
                    </div>
					
					<div class="form-group">
                        <label for="pnote" class="col-sm-4 control-label"><?= lang('product_note') ?></label>

                        <div class="col-sm-8">
							<!--<input type="text" class="form-control kb-pad" id="pnote">-->
                            <textarea id="pnote"></textarea>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="net_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="pro_tax"></span></th>
                        </tr>
                    </table>
                    <input type="hidden" id="punit_price" value=""/>
                    <input type="hidden" id="old_tax" value=""/>
                    <input type="hidden" id="old_qty" value=""/>
                    <input type="hidden" id="old_price" value=""/>
                    <input type="hidden" id="row_id" value=""/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editItem"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(window).load(function () {
        var al = '<?php echo $this->input->get('addquote');?>';
        if (al) {
            var test = $("#add_item").val();
            $.ajax({
                type: 'get',
                url: '<?= site_url('quotes/suggestions'); ?>',
                dataType: "json",
                data: {
                    term: test,
                    warehouse_id: __getItem('quwarehouse'),
                    customer_id: __getItem('qucustomer')
                },
                success: function (data) {
                    for (var i = 0; i < data.length; i++) {
                        comment = data[i];
                        add_invoice_item(comment);
                    }
                    $("#add_item").val('');
                    var url = $(".gos").attr('href');
                    window.location.href = url;
                }
            });


        }
    });
    var $biller = $("#qubiller");
    $(window).load(function () {
        billerChange();
        $('#slarea').change();
    });

    function billerChange() {
        var id = $biller.val();
        $("#quwarehouse").empty();

        $.ajax({
            url: '<?= base_url() ?>auth/getWarehouseByProject/'+id,
            dataType: 'json',
            success: function(result){
                var the_same_ware = false;
                var default_ware  = "<?=$Settings->default_warehouse;?>";
                $.each(result, function(i,val){
                    var b_id = val.id;
                    var code = val.code;
                    var name = val.name;
                    var opt = '<option value="' + b_id + '">' +code+'-'+ name + '</option>';
                    $("#quwarehouse").append(opt);
                    if (default_ware == b_id) {
                        the_same_ware = true;
                    }
                });

                if (quwarehouse = __getItem('quwarehouse')) {
                    $('#quwarehouse').select2("val", quwarehouse);
                } else {
                    if (owner || admin) {
                        if (the_same_ware == true) {
                            $("#quwarehouse").select2("val", "<?=$Settings->default_warehouse;?>");
                        } else {
                            var opt_first = $('#quwarehouse option:first-child').val();
                            $("#quwarehouse").select2("val", opt_first);
                        }
                    } else {
                        var opt_first = $('#quwarehouse option:first-child').val();
                        $("#quwarehouse").select2("val", opt_first);
                    }
                }
            }
        });

        $.ajax({
            url: '<?= base_url() ?>sales/getReferenceByProject/qu/' + id,
            dataType: 'json',
            success: function (data) {
                $("#reref").val(data);
                $("#temp_reference_no").val(data);

            }
        });

    }

    $(document).ready(function () {

        $('body').on('click', '.add_product_auto', function (e) {
            e.preventDefault();
            var pname = $("#name").val();
            var code = $("#code").val();
            var category = $("#category").val();
            var unit = $("#unit").val();
            var cost = $("#cost").val();
            var price = $("#price").val();
            if (pname && code && category && unit && cost && price) {
                $(".add_product").trigger("click");
            }
            $(".request_").text("Please input required fields (*)");
        });


        $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
            placeholder: "<?= lang('select_category_to_load') ?>", data: [
                {id: '', text: '<?= lang('select_category_to_load') ?>'}
            ]
        });
        $('#category').change(function () {
            var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('products/getSubCategories') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
                            $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: scdata
                            });
                        } else {
                            $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: 'not found'
                            });
                        }
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            } else {
                $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
                    placeholder: "<?= lang('select_category_to_load') ?>",
                    data: [{id: '', text: '<?= lang('select_category_to_load') ?>'}]
                });
            }
            $('#modal-loading').hide();
        });
    });


</script>