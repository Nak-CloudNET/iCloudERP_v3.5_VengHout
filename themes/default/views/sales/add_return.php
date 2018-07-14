<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>, tax_rates = <?php echo json_encode($tax_rates); ?>;
    $(document).ready(function () {
        var remove_return = '<?=$this->session->userdata('remove_return');?>';

        if (remove_return) {
            if (__getItem('slitems')) {
                __removeItem('slitems');
            }
            if (__getItem('slref')) {
                __removeItem('slref');
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
            if (__getItem('saleman')) {
                __removeItem('saleman');
            }
            if (__getItem('sldate')) {
                __removeItem('sldate');
            }
            if (__getItem('payment_reference_no')) {
                __removeItem('payment_reference_no');
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
            if (__getItem('payment_note_1')) {
                __removeItem('payment_note_1');
            }
            if (__getItem('slpayment_term')) {
                __removeItem('slpayment_term');
            }
            <?=$this->session->set_userdata('remove_s2', '0');?>
        }

        <?php if($this->input->get('customer')) { ?>
        if (!__getItem('slitems')) {
            __setItem('slcustomer', <?=$this->input->get('customer');?>);
        }
        <?php } ?>
        <?php if ($Owner || $Admin) { ?>
        if (!__getItem('sldate')) {
            $("#sldate").datetimepicker({
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
        $(document).on('change', '#sldate', function (e) {
            __setItem('sldate', $(this).val());
        });
        if (sldate = __getItem('sldate')) {
            $('#sldate').val(sldate);
        }
        $(document).on('change', '#slbiller', function (e) {
            __setItem('slbiller', $(this).val());
        });
        if (slbiller = __getItem('slbiller')) {
            $('#slbiller').val(slbiller);
        }
        <?php } ?>
        if (!__getItem('slref')) {
            __setItem('slref', '<?=$slnumber?>');
        }
		if (!__getItem('slrefnote')) {
            __setItem('slrefnote', '<?=$slnumber?>');
        }
        if (!__getItem('sltax2')) {
            __setItem('sltax2', <?=$Settings->default_tax_rate2;?>);
        }
        ItemnTotals();
        $('.bootbox').on('hidden.bs.modal', function (e) {
            $('#add_item').focus();
        });
        $("#add_item").autocomplete({
            source: function (request, response) {
                if (!$('#slcustomer').val()) {
                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    $('#add_item').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('sales/suggestionsReturn'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#poswarehouse").val(),
                        customer_id: $("#poscustomer").val()
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
                    // $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });
        $(document).on('change', '#gift_card_no', function () {
            var cn = $(this).val() ? $(this).val() : '';
            if (cn != '') {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "sales/validate_gift_card/" + cn,
                    dataType: "json",
                    success: function (data) {
                        if (data === false) {
                            $('#gift_card_no').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('incorrect_gift_card')?>');
                        } else if (data.customer_id !== null && data.customer_id !== $('#slcustomer2').val()) {
                            $('#gift_card_no').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('gift_card_not_for_customer')?>');

                        } else {
                            $('#gc_details').html('<small>Card No: ' + data.card_no + '<br>Value: ' + data.value + ' - Balance: ' + data.balance + '</small>');
                            $('#gift_card_no').parent('.form-group').removeClass('has-error');
                        }
                    }
                });
            }
        });
        $('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_sale_return'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                    $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                    echo form_open_multipart("sales/add_return", $attrib);
                    if ($quote_id) {
                        echo form_hidden('quote_id', $quote_id);
                    }
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "sldate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-4">
                            <?= lang("reference_no", "reref"); ?>
                            <div style="float:left;width:100%;">
                                <div class="form-group">
                                    <div class="input-group">
                                        <?php echo form_input('reference_no', $reference?$reference:"",'class="form-control input-tip" id="reref"'); ?>
                                        <input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference?$reference:"" ?>" />
                                        <div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
                                            <input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
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
                                    echo get_dropdown_project('biller', 'slbiller');
                                } else {
                                    echo get_dropdown_project('biller', 'slbiller', $default_biller[0]);
                                }
                                ?>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading"><?= lang('please_select_these_before_adding_product') ?></div>
                                <div class="panel-body" style="padding: 5px;">
                                    <?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?= lang("warehouse", "slwarehouse"); ?>
                                                <?php
                                                echo form_dropdown('warehouse', '', (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="slwarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');
                                                ?>
                                            </div>
                                        </div>
                                    <?php } else {
                                        $warehouse_input = array(
                                            'type' => 'hidden',
                                            'name' => 'warehouse',
                                            'id' => 'slwarehouse',
                                            'value' => $this->session->userdata('warehouse_id'),
                                        );

                                        echo form_input($warehouse_input);
                                    } ?>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("customer", "slcustomer"); ?>
                                            <?php if ($Owner || $Admin || $GP['customers-add']) { ?><div class="input-group"><?php } ?>
                                            <?php
                                            echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : (isset($sale_order->company_name)?$sale_order->company_name:$this->input->get('customer'))), 'id="slcustomer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control input-tip" style="min-width:100%;"');
                                            ?>
                                            <?php if ($Owner || $Admin || $GP['customers-add']) { ?>

                                            <div class="input-group-addon no-print" style="padding: 2px 5px; border-left: 0;">
                                                <a href="#" id="view-customer" class="external" data-toggle="modal" data-target="#myModal">
                                                    <i class="fa fa-2x fa-user" id="addIcon"></i>
                                                </a>
                                            </div>

                                            <div class="input-group-addon no-print" style="padding: 2px 5px;"><a
                                                        href="<?= site_url('customers/add/sale'); ?>" id="add-customer"
                                                        class="external" data-toggle="modal" data-target="#myModal"><i
                                                            class="fa fa-2x fa-plus-circle" id="addIcon"></i></a></div>
                                        </div>
                                    <?php } ?>
                                        </div>
                                    </div>
									<div class="col-md-4">
										<div class="form-group">
										<?= lang("saleman", "saleman"); ?>
										<select name="saleman" id="saleman" class="form-control saleman">
											<?php
												foreach($agencies as $agency){
													if($this->session->userdata('username') == $agency->username){
														echo '<option value="'.$this->session->userdata('user_id').'" selected>'.lang($this->session->userdata('username')).'</option>';
													}else{
														echo '<option value="'.$agency->id.'">'.$agency->username.'</option>';
													}
												}
											?>
										</select>
										<?php
										/*$sm[''] = '';
										foreach($agencies as $agency){
											$sm[$agency->id] = $agency->username;
										}
										echo form_dropdown('saleman', $sm, (isset($_POST['saleman']) ? $_POST['saleman'] : ''), 'id="slsaleman" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("saleman") . '" style="width:100%;" ');*/
										?>
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
                                        <?php echo form_input('add_item', '', 'class="form-control input-lg" id="add_item" placeholder="' . lang("add_product_to_order") . '"'); ?>
                                        <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <a href="#" id="addManually" class="tip" title="<?= lang('add_product_manually') ?>">
                                                <i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i>
                                            </a>
                                        </div>
                                        <?php } if ($Owner || $Admin || $GP['sales-add_gift_card']) { ?>
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <a href="#" id="sellGiftCard" class="tip" title="<?= lang('sell_gift_card') ?>">
                                               <i class="fa fa-2x fa-credit-card addIcon" id="addIcon"></i>
                                            </a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group table-group">
                                <label class="table-label"><?= lang("order_items"); ?> *</label>

                                <div class="controls table-controls">
                                    <table id="slTable"
                                           class="table items table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                            <tr>
												<!--<th class="col-md-2"><?/*= lang("sale_reference"); */?></th>-->
												<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
													<th class="col-md-2"><?= lang("product_code"); ?></th>
													<th class="col-md-3"><?= lang("product_name"); ?></th>
												<?php } ?>
												<?php if($setting->show_code == 1 && $setting->separate_code == 0) { ?>
													<th class="col-md-3"><?= lang("product_name") .'('. lang('code') .')'; ?></th>
												<?php } ?>
												<?php if($setting->show_code == 0) { ?>
													<th class="col-md-3"><?= lang("description"); ?></th>
												<?php } ?>
												<th class="col-md-1"><?= lang("unit_price"); ?></th>
												<th class="col-md-1"><?= lang("quantity"); ?></th>
                                                <?php
                                                if ($Settings->product_serial) {
                                                    echo '<th class="col-md-2">' . $this->lang->line("serial_no") . '</th>';
                                                }
                                                ?>
                                                <?php
                                                if ($Settings->product_discount) {
                                                    echo '<th class="col-md-1">' . $this->lang->line("discount") . '</th>';
                                                }
                                                ?>
                                                <?php
                                                if ($Settings->tax1) {
                                                    echo '<th class="col-md-1">' . $this->lang->line("product_tax") . '</th>';
                                                }
                                                ?>
												<th>
													<?= lang("subtotal"); ?>
													(<span class="currency"><?= $default_currency->code ?></span>)
												</th>
												<th style="width: 30px !important; text-align: center;">
													<i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
												</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="payments" style="display: block;">
                            <div class="col-md-12">
                                <div class="well well-sm well_1">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4" id="payment_ref">
                                                <div class="form-group">
                                                    <?= lang("payment_reference_no", "payment_reference_no"); ?>
                                                    <?= form_input('payment_reference_no', (isset($_POST['payment_reference_no']) ? $_POST['payment_reference_no'] : $payment_ref), 'class="form-control tip" id="payment_reference_no" required="required"'); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="payment">
                                                    <div class="form-group ngc">
                                                        <?= lang("amount", "amount_1"); ?>
                                                        <input name="amount-paid" type="text" id="amount_1"
                                                               class="pa form-control kb-pad amount"/>
                                                    </div>
                                                    <div class="form-group gc" style="display: none;">
                                                        <?= lang("gift_card_no", "gift_card_no"); ?>
                                                        <input name="gift_card_no" type="text" id="gift_card_no"
                                                               class="pa form-control kb-pad"/>

                                                        <div id="gc_details"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <?= lang("paying_by", "paid_by_1"); ?>
                                                    <select name="paid_by" id="paid_by_1" class="form-control paid_by">
                                                        <option value="cash"><?= lang("cash"); ?></option>
                                                        <option value="gift_card"><?= lang("gift_card"); ?></option>
                                                        <option value="CC"><?= lang("cc"); ?></option>
                                                        <option value="Cheque"><?= lang("cheque"); ?></option>
                                                        <option value="deposit"><?= lang("deposit"); ?></option>
                                                        <option value="other"><?= lang("other"); ?></option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-sm-4" id="bank_acc">
                                                <div class="form-group">
                                                    <?= lang("bank_account", "bank_account_1"); ?>
                                                    <?php
                                                    $bank = array('0' => '-- Select Bank Account --');
                                                    if ($Owner || $Admin) {
                                                        foreach($bankAccounts as $bankAcc) {
                                                            $bank[$bankAcc->accountcode] = $bankAcc->accountcode . ' | '. $bankAcc->accountname;
                                                        }
                                                        echo form_dropdown('bank_account', $bank, '', 'id="bank_account_1" class="ba form-control kb-pad bank_account" required="required" data-bv-notempty="true"');
                                                    } else {
                                                        $ubank = array('0' => '-- Select Bank Account --');
                                                        foreach($userBankAccounts as $userBankAccount) {
                                                            $ubank[$userBankAccount->accountcode] = $userBankAccount->accountcode . ' | '. $userBankAccount->accountname;
                                                        }
                                                        echo form_dropdown('bank_account', $ubank, '', 'id="bank_account_1" class="ba form-control kb-pad bank_account" required="required" data-bv-notempty="true"');
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <div class="form-group dp" style="display: none;">
                                            <?= lang("deposit_amount", "deposit_amount"); ?>

                                            <div id="dp_details"></div>
                                        </div>

                                        <div class="pcc_1" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_no" type="text" id="pcc_no_1"
                                                               class="form-control" placeholder="<?= lang('cc_no') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_holder" type="text" id="pcc_holder_1"
                                                               class="form-control"
                                                               placeholder="<?= lang('cc_holder') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="pcc_type" id="pcc_type_1"
                                                                class="form-control pcc_type"
                                                                placeholder="<?= lang('card_type') ?>">
                                                            <option value="Visa"><?= lang("Visa"); ?></option>
                                                            <option
                                                                value="MasterCard"><?= lang("MasterCard"); ?></option>
                                                            <option value="Amex"><?= lang("Amex"); ?></option>
                                                            <option value="Discover"><?= lang("Discover"); ?></option>
                                                        </select>
                                                        <!-- <input type="text" id="pcc_type_1" class="form-control" placeholder="<?= lang('card_type') ?>" />-->
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_month" type="text" id="pcc_month_1"
                                                               class="form-control" placeholder="<?= lang('month') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_year" type="text" id="pcc_year_1"
                                                               class="form-control" placeholder="<?= lang('year') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_ccv" type="text" id="pcc_cvv2_1"
                                                               class="form-control" placeholder="<?= lang('cvv2') ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pcheque_1" style="display:none;">
                                            <div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
                                                <input name="cheque_no" type="text" id="cheque_no_1"
                                                       class="form-control cheque_no"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?= lang('payment_note', 'payment_note_1'); ?>
                                            <textarea name="payment_note" id="payment_note_1"
                                                      class="pa form-control kb-text payment_note"></textarea>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>

                        <div class="row" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("sale_note", "slnote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("staff_note", "slinnote"); ?>
                                        <?php echo form_textarea('staff_note', (isset($_POST['staff_note']) ? $_POST['staff_note'] : ""), 'class="form-control" id="slinnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>


                            </div>

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_sale', lang("submit"), 'id="add_sale" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
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
                            <td><?= lang('order_discount') ?> <span class="totals_val pull-right" id="tds">0.00</span></td>
                            <?php }?>
                            <?php if ($Settings->tax2) { ?>
                                <td><?= lang('order_tax') ?> <span class="totals_val pull-right" id="ttax2">0.00</span></td>
                            <?php } ?>
                            <td><?= lang('shipping') ?> <span class="totals_val pull-right" id="tship">0.00</span></td>
                            <td><?= lang('grand_total') ?> <span class="totals_val pull-right" id="gtotal">0.00</span></td>
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
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
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
                    <?php if ($Settings->product_serial) { ?>
                        <div class="form-group">
                            <label for="pserial" class="col-sm-4 control-label"><?= lang('serial_no') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pserial">
                            </div>
                        </div>
                    <?php } ?>
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
                        <label for="pg" class="col-sm-4 control-label"><?= lang('price_groups') ?></label>
						<div class="col-sm-8">
							<div id="pg-div"></div>
						</div>
                    </div>
					
                    <?php if ($Settings->product_discount || ($Owner || $Admin || $this->session->userdata('allow_discount'))) { ?>
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
                            <input type="text" class="form-control" id="pprice">
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

<div class="modal" id="mModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mcode">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mname" class="col-sm-4 control-label"><?= lang('product_name') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mname">
                        </div>
                    </div>
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label for="mtax" class="col-sm-4 control-label"><?= lang('product_tax') ?> *</label>

                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('mtax', $tr, "", 'id="mtax" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= lang('quantity') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mquantity">
                        </div>
                    </div>
                    <?php if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount'))) { ?>
                        <div class="form-group">
                            <label for="mdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mdiscount">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mprice" class="col-sm-4 control-label"><?= lang('unit_price') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mprice">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="mnet_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="mpro_tax"></span></th>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addItemManually"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="gcModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="myModalLabel"><?= lang('sell_gift_card'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?= lang('enter_info'); ?></p>

                <div class="alert alert-danger gcerror-con" style="display: none;">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <span id="gcerror"></span>
                </div>
                <div class="form-group">
                    <?= lang("card_no", "gccard_no"); ?> *
                    <div class="input-group">
                        <?php echo form_input('gccard_no', '', 'class="form-control" id="gccard_no"'); ?>
                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#"
                                                                                                           id="genNo"><i
                                    class="fa fa-cogs"></i></a></div>
                    </div>
                </div>
                <input type="hidden" name="gcname" value="<?= lang('gift_card') ?>" id="gcname"/>

                <div class="form-group">
                    <?= lang("value", "gcvalue"); ?> *
                    <?php echo form_input('gcvalue', '', 'class="form-control" id="gcvalue"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("price", "gcprice"); ?> *
                    <?php echo form_input('gcprice', '', 'class="form-control" id="gcprice"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("customer", "gccustomer"); ?>
                    <?php echo form_input('gccustomer', '', 'class="form-control" id="gccustomer"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("expiry_date", "gcexpiry"); ?>
                    <?php echo form_input('gcexpiry', $this->erp->hrsd(date("Y-m-d", strtotime("+2 year"))), 'class="form-control date" id="gcexpiry"'); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="addGiftCard" class="btn btn-primary"><?= lang('sell_gift_card') ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	
	var $biller = $("#slbiller");
    $(window).load(function(){
        billerChange();
    });

    $(document).ready(function() {
        $("#reref").attr('readonly', true);
        $('#ref_st').on('ifChanged', function() {
            if ($(this).is(':checked')) {
                // $("#reref").prop('disabled', false);
                $("#reref").attr('readonly', false);
                $("#reref").val("");
            }else{
                $("#reref").prop('disabled', true);
                var temp = $("#temp_reference_no").val();
                $("#reref").val(temp);

            }
        });

        $biller.change(function(){
            billerChange();
            $('slwarehouse option:first-child').attr("selected", "selected");
        });
    });

    function billerChange(){
        var id = $biller.val();
        var admin = '<?= $Admin?>';
        var owner = '<?= $Owner?>';
        $("#slwarehouse").empty();
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
                    $("#slwarehouse").append(opt);
                    if (default_ware == b_id) {
                        the_same_ware = true;
                    }
                });

                if (slwarehouse = __getItem('slwarehouse')) {
                    $('#slwarehouse').select2("val", slwarehouse);
                } else {
                    if (owner || admin) {
                        if (the_same_ware == true) {
                            $("#slwarehouse").select2("val", "<?=$Settings->default_warehouse;?>");
                        } else {
                            var opt_first = $('#slwarehouse option:first-child').val();
                            $("#slwarehouse").select2("val", opt_first);
                        }
                    } else {
                        var opt_first = $('#slwarehouse option:first-child').val();
                        $("#slwarehouse").select2("val", opt_first);
                    }
                }

            }
        });

        $.ajax({
            url: '<?= base_url() ?>sales/getReferenceByProject/re/'+id,
            dataType: 'json',
            success: function(data){
                $("#reref").val(data);
                $("#temp_reference_no").val(data);
            }
        });

        $.ajax({
            url: '<?= base_url() ?>sales/getReferenceByProject/pp/'+id,
            dataType: 'json',
            success: function(data){
                $("#payment_reference_no").val(data);
            }
        });

    }

    $('#print_depre').click(function () {
        PopupPayments();
    });

    function PopupPayments() {
        var mywindow = window.open('', 'erp_pos_print', 'height=auto,max-width=480,min-width=250px');
        mywindow.document.write('<html><head><title>Print</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?= $assets ?>styles/helpers/bootstrap.min.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write('<center>');
        var issued_date = $('.current_date').val();
        mywindow.document.write("<center><h4 style='font-family:Verdana,Geneva,sans-serif;'>Loan Amortization Schedule</h4></center><br/>");
        mywindow.document.write('<table class="table-condensed" style="width:95%; font-family:Verdana,Geneva,sans-serif; font-size:12px; padding-bottom:10px;">'+
                                    '<tr>'+
                                        '<td><?= lang('Issued Date ') ?><?= lang(": ") ?>'+ issued_date +'</td>'+
                                    '</tr>'+
                                '</table><br/>'
                              );
        mywindow.document.write('<table border="2px" class="table table-bordered table-condensed table_shape" style="width:95%; font-family:Verdana,Geneva,sans-serif; font-size:12px; border-collapse:collapse;">'+
                                    '<thead>'+
                                         '<tr>'+
                                            '<th width="5%" class="td_bor_style"><?= lang('No') ?></th>'+
                                            '<th width="15%" class="td_bor_style td_align_center"><?= lang('Item Code') ?></th>'+
                                            '<th width="45%" class="td_bor_style"><?= lang('Decription') ?></th>'+
                                            '<th width="10%" class="td_bor_style"><?= lang('Unit Price') ?></th>'+
                                            '<th width="10%" class="td_bor_style"><?= lang('Qty') ?></th>'+
                                            '<th width="15%" class="td_bor_botton"><?= lang('Amount') ?></th>'+
                                          '</tr>'+
                                    '</thead>'+
                                        '<tbody>');
                                        var type = $('#depreciation_type_1').val();
                                        var no = 0;
                                        var total_amt = 0;
                                        var total_amount = $('#total_balance').val()-0;
                                        var us_down = $('#amount_1').val()-0;
                                        var down_pay = us_down;
                                        var interest_rate = Number($('#depreciation_rate_1').val()-0);
                                        var term_ = Number($('#depreciation_term_1').val()-0);
                                        $('.rcode').each(function(){
                                            no += 1;
                                            var parent = $(this).parent().parent();
                                            var unit_price = parent.find('.realuprice').val();
                                            var qtt = parent.find('.rquantity').val();
                                            var amt = unit_price * qtt;
                                            total_amt += amt;
        mywindow.document.write(			'<tr>'+
                                                '<td class="td_color_light td_align_center" >'+ no +'</td>'+
                                                '<td class="td_color_light">'+ parent.find('.rcode').val() +'</td>'+
                                                '<td class="td_color_light td_align_center">'+ parent.find('.rname').val() +'</td>'+
                                                '<td class="td_color_light td_align_right">'+ formatDecimal(unit_price) +'</td>'+
                                                '<td class="td_color_light td_align_center">'+ qtt +'</td>'+
                                                '<td class="td_color_bottom_light td_align_right">'+ formatDecimal(amt) +'</td>'+
                                            '</tr>');
                                        });
                                        var loan_amount = total_amt;
                                        if(type != 4){
                                            loan_amount = total_amt - down_pay;
                                        }
                                            if(down_pay != 0 || down_pay != ''){
        mywindow.document.write(			'<tr>'+
                                                '<td colspan="5" style="text-align:right; padding:5px;"><?= lang('Total Amount') ?></td>'+
                                                '<td class="td_align_right"><b>'+ formatDecimal(total_amt) +'</b></td>'+
                                            '</tr>');
        mywindow.document.write(			'<tr>'+
                                                '<td colspan="5" style="text-align:right; padding:5px;"><?= lang('Down Payment') ?></td>'+
                                                '<td class="td_align_right"><b>'+ formatDecimal(down_pay) +'</b></td>'+
                                            '</tr>');
                                            }
        mywindow.document.write(			'<tr>'+
                                                '<td colspan="5" style="text-align:right; padding:5px;"><?= lang('Loan Amount') ?></td>'+
                                                '<td class="td_align_right"><b>'+ formatDecimal(loan_amount) +'</b></td>'+
                                            '</tr>'+
                                                '<td colspan="5" style="text-align:right; padding:5px;"><?= lang('interest_rate_per_month') ?></td>'+
                                                '<td class="td_align_right"><b>'+ formatDecimal(interest_rate/term_) +'</b></td>'+
                                            '</tr>');
        mywindow.document.write(		'</tbody>'+
                                '</table><br/>'
                                );
        mywindow.document.write('<div class="payment_term"><b><?= lang('Payment Term')?></b></div>');
        mywindow.document.write('<table border="2px" class="table table-bordered table-condensed table_shape" style="width:95%; font-family:Verdana,Geneva,sans-serif; font-size:12px; border-collapse:collapse;">'+
                                     '<thead>'+
                                          '<tr>'+
                                            '<th width="10%" class="td_bor_style"><?= lang('Pmt No.') ?></th>'
                                );
                                        if(type == 2){
        mywindow.document.write(			'<th width="10%" class="td_bor_style"><?= lang('Rate') ?></th>');
        mywindow.document.write(			'<th width="10%" class="td_bor_style"><?= lang('Percentage') ?></th>');
        mywindow.document.write(			'<th width="10%" class="td_bor_style"><?= lang('Payment') ?></th>'+
                                            '<th width="15%" class="td_bor_style"><?= lang('Total Payment') ?></th>'
                                );
                                        }else{
        mywindow.document.write(			'<th width="10%" class="td_bor_style"><?= lang('Interest') ?></th>'+
                                            '<th width="10%" class="td_bor_style"><?= lang('Principle') ?></th>'+
                                            '<th width="15%" class="td_bor_style"><?= lang('Total Payment') ?></th>'
                                );
                                        }
        mywindow.document.write(			'<th width="10%" class="td_bor_style"><?= lang('Balance') ?></th>'+
                                            '<th width="15%" class="td_bor_style"><?= lang('Payment Date') ?></th>'+
                                            '<th width="25%" class="td_bor_botton"><?= lang('Note') ?></th>'+
                                          '</tr>'+
                                    '</thead>'+
                                    '<tbody>');
                                    var k = 0;
                                    var total_interest = 0;
                                    var total_princ = 0;
                                    var amount_total_pay = 0;
                                    var total_pay_ = 0;
                                    $('.dep_tbl .no').each(function(){
                                        k += 1;
                                        var tr = $(this).parent().parent();
                                        var balance = formatDecimal(tr.find('.balance').val()-0);
                                    if(type == 2){
                                        total_interest += Number(tr.find('.rate').val()-0);
                                        total_princ += Number(tr.find('.percentage').val()-0);
                                        amount_total_pay += Number(tr.find('.total_payment').val()-0);
                                    }else{
                                        total_interest += Number(tr.find('.interest').val()-0);
                                        total_princ += Number(tr.find('.principle').val()-0);
                                    }
                                        total_pay_ += Number(tr.find('.payment_amt').val()-0);
        mywindow.document.write(		'<tr>'+
                                                '<td class="td_color_light td_align_center">'+ k +'</td>');
                                        if(type == 2){
        mywindow.document.write(				'<td class="td_color_light td_align_center">'+ formatDecimal(tr.find('.rate').val()-0) +'</td>');
        mywindow.document.write(				'<td class="td_color_light td_align_center">'+ formatDecimal(tr.find('.percentage').val()-0) +'</td>');
        mywindow.document.write(				'<td class="td_color_light td_align_center">'+ formatDecimal(tr.find('.payment_amt').val()-0) +'</td>');
        mywindow.document.write(				'<td class="td_color_light td_align_center">'+ formatDecimal(tr.find('.total_payment').val()-0) +'</td>');
                                        }else{
        mywindow.document.write(				'<td class="td_color_light td_align_center">'+ formatDecimal(tr.find('.interest').val()-0) +'</td>');
        mywindow.document.write(				'<td class="td_color_light td_align_center">'+ formatDecimal(tr.find('.principle').val()-0) +'</td>');
        mywindow.document.write(				'<td class="td_color_light td_align_center">'+ formatDecimal(tr.find('.payment_amt').val()-0) +'</td>');
                                        }
        mywindow.document.write(				'<td class="td_color_light td_align_right">'+ balance +'</td>'+
                                                '<td class="td_color_light td_align_center">'+ tr.find('.dateline').val() +'</td>'+
                                                '<td class="td_color_bottom_light">'+ tr.find('.note_1').val() +'</td>'+
                                            '</tr>');
                                    });
                                    if(type == 2){
        mywindow.document.write(			'<tr>'+
                                                '<td style="text-align:right; padding:5px;" colspan="2"><b> Total </b></td>'+
                                                '<td style="text-align:left; padding:5px;"><b>'+ formatDecimal(total_princ) +'</b></td>'+
                                                '<td style="text-align:left; padding:5px;"><b>'+ formatDecimal(total_pay_) +'</b></td>'+
                                                '<td style="text-align:left; padding:5px;"><b>'+ formatDecimal(amount_total_pay) +'</b></td>'+
                                                '<td style="text-align:left; padding:5px;"> &nbsp; </td>'+
                                                '<td style="text-align:left; padding:5px;"> &nbsp; </td>'+
                                                '<td style="text-align:left; padding:5px;"> &nbsp; </td>'+
                                            '</tr>');
                                    }else{
        mywindow.document.write(			'<tr>'+
                                                '<td style="text-align:right; padding:5px;"><b> Total </b></td>'+
                                                '<td style="text-align:left; padding:5px;"><b>'+ formatDecimal(total_interest) +'</b></td>'+
                                                '<td style="text-align:left; padding:5px;"><b>'+ formatDecimal(total_princ) +'</b></td>'+
                                                '<td style="text-align:left; padding:5px;"><b>'+ formatDecimal(total_pay_) +'</b></td>'+
                                                '<td style="text-align:left; padding:5px;"> &nbsp; </td>'+
                                                '<td style="text-align:left; padding:5px;"> &nbsp; </td>'+
                                                '<td style="text-align:left; padding:5px;"> &nbsp; </td>'+
                                            '</tr>');
                                    }
        mywindow.document.write(	'</tbody>'+
                                '</table>'
                                );

        mywindow.document.write('</center>');
        mywindow.document.write('</body></html>');
        mywindow.print();
        //mywindow.close();
        return true;
    }

    $(document).on('click', '#add_sale', function(){
        if ($('.paid_by').select2('val') != "deposit") {
            if($('#bank_account_1').val() == 0){
                bootbox.alert('<?= lang('bank_account_x_select'); ?>');
                return false;
            }
        }
    });
	
</script>
