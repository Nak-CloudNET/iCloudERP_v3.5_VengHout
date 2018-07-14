<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-cogs"></i><?= lang('pos_settings'); ?></h2>
        <?php if(isset($pos->purchase_code) && ! empty($pos->purchase_code) && $pos->purchase_code != 'purchase_code') { ?>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="<?= site_url('pos/updates') ?>" class="toggle_down"><i
                            class="icon fa fa-upload"></i><span class="padding-right-10"><?= lang('updates'); ?></span></a>
                </li>
            </ul>
        </div>
        <?php } ?>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('update_info'); ?></p>

                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'pos_setting');
                echo form_open("pos/settings", $attrib);
                ?>

                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?= lang('pos_config') ?></legend>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('pro_limit', 'limit'); ?>
                            <?php echo form_input('pro_limit', $pos->pro_limit, 'class="form-control" id="limit" required="required"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('delete_code', 'pin_code'); ?>
                            <?php echo form_password('pin_code', $pos->pin_code, 'class="form-control" pattern="[0-9]{4,8}"id="pin_code"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('default_category', 'default_category'); ?>
                            <?php
                            $ct[''] = '';
                            foreach ($categories as $catrgory) {
                                $ct[$catrgory->id] = $catrgory->name;
                            }
                            echo form_dropdown('category', $ct, $pos->default_category, 'class="form-control select" id="default_category" required="required"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('default_biller', 'default_biller'); ?>
                            <?php
                            $bl[0] = "";
                            foreach ($billers as $biller) {
                                $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                            }
                            if (isset($_POST['biller'])) {
                                $biller = $_POST['biller'];
                            } else {
                                $biller = "";
                            }
                            echo form_dropdown('biller', $bl, $pos->default_biller, 'class="form-control select" id="default_biller" required="required"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('default_customer', 'customer1'); ?>
                            <?= form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : $pos->default_customer), 'id="customer1" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control" style="width:100%;"'); ?>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('display_time', 'display_time'); ?>
                            <?php
                            $yn = array('1' => lang('yes'), '0' => lang('no'));
                            echo form_dropdown('display_time', $yn, $pos->display_time, 'class="form-control select" id="display_time" required="required"');
                            ?>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('onscreen_keyboard', 'keyboard'); ?>
                            <?php
                            echo form_dropdown('keyboard', $yn, $pos->keyboard, 'class="form-control select" id="keyboard" required="required"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('product_button_color', 'product_button_color'); ?>
                            <?php $col = array('default' => lang('default'), 'primary' => lang('primary'), 'info' => lang('info'), 'warning' => lang('warning'), 'danger' => lang('danger'));
                            echo form_dropdown('product_button_color', $col, $pos->product_button_color, 'class="form-control select" id="product_button_color" required="required"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('tooltips', 'tooltips'); ?>
                            <?php
                            echo form_dropdown('tooltips', $yn, $pos->tooltips, 'class="form-control select" id="tooltips" required="required"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('rounding', 'rounding'); ?>
                            <?php
                            $rnd = array('0' => lang('disable'), '1' => lang('to_nearest_005'), '2' => lang('to_nearest_050'), '3' => lang('to_nearest_number'), '4' => lang('to_next_number'));
                            echo form_dropdown('rounding', $rnd, $pos->rounding, 'class="form-control select" id="rounding" required="required"');
                            ?>
                        </div>
                    </div>
                    <!--<div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('enable_java_applet', 'enable_java_applet'); ?>
                            <?= form_dropdown('enable_java_applet', $yn, $pos->java_applet, 'class="form-control select" id="enable_java_applet" required="required"');
                            ?>
                        </div>
                    </div>-->
					<div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('pos_layout', 'pos_layout'); 
                        	$plt = array('0' => lang('standard'), '1' => lang('standard_plus'), '2' => lang('mart'), '3' => lang('mini_mart'), '4' => lang('restaurant'), '5' => lang('restaurant_plus'), '6' => lang('mobile'));
                            echo form_dropdown('pos_layout', $plt, $pos->pos_layout, 'class="form-control select" id="pos_layout" required="required"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('item_photo', 'show_item_img'); ?>
                            <?= form_dropdown('show_item_img', $yn, $pos->show_item_img, 'class="form-control select" id="show_item_img" required="required"');
                            ?>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('display_qrcode', 'display_qrcode'); ?>
                            <?= form_dropdown('display_qrcode', $yn, $pos->display_qrcode, 'class="form-control select" id="display_qrcode" required="required"');
                            ?>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('show_payment_noted', 'show_payment_noted'); ?>
                            <?php
                            $yn = array('1' => lang('yes'), '0' => lang('no'));
                            echo form_dropdown('show_payment_noted', $yn, $pos->show_payment_noted, 'class="form-control select" id="show_payment_noted" required="required"');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('payment_balance', 'payment_balance'); ?>
                            <?php
                            $yn = array('1' => lang('yes'), '0' => lang('no'));
                            echo form_dropdown('payment_balance', $yn, $pos->payment_balance, 'class="form-control select" id="payment_balance" required="required"');
                            ?>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-4">
                        <div class="form-group">
                           
                           <?= lang('show_suspend_bar', 'show_suspend_bar'); ?>                            
                            <?php
                            $ssp = array('0' => lang('disable'), '1' => lang('daily'), '7' => lang('weekly'), '30' => lang('monthly'), '90' => lang('quarterly'), '180' => lang('semesterly'), '365' => lang('yearly'), '40' => lang('once_time') );
                            echo form_dropdown('show_suspend_bar', $ssp, $pos->show_suspend_bar, 'class="form-control select" id="show_suspend_bar" required="required"');
                            ?>
                        </div>
                    </div>
					
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang('show_product_code', 'show_product_code'); ?>
							<?= form_dropdown('show_product_code', $yn, $pos->show_product_code, 'class="form-control select" id="show_product_code" required="required"');
							?>
						</div>
					</div>
					
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang('auto_delivery', 'auto_delivery'); ?>
							<?= form_dropdown('auto_delivery', $yn, $pos->auto_delivery, 'class="form-control select" id="auto_delivery" required="required"');
							?>
						</div>
					</div>
					
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang('POS_Exchange_Rate', 'POS_Exchange_Rate'); ?>
							<?= form_dropdown('in_out_rate', $yn, $pos->in_out_rate, 'class="form-control select" id="in_out_rate" required="required"');
							?>
						</div>
					</div>
					
					<div class="col-md-4 col-sm-4">
						<div class="form-group">
							<?= lang('count_cash', 'count_cash'); ?>
							<?= form_dropdown('count_cash', $yn, $pos->count_cash, 'class="form-control select" id="count_cash" required="required"');
							?>
						</div>
					</div>
					
                    <div class="clearfix"></div>
                    <div id="jac" class="col-md-12" style="display: none;">
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?= lang('receipt_printer', 'rec1'); ?>
                                <?php echo form_input('receipt_printer', $pos->receipt_printer, 'class="form-control tip" id="rec1"'); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?= lang('char_per_line', 'char_per_line'); ?>
                                <?php echo form_input('char_per_line', $pos->char_per_line, 'class="form-control tip" id="char_per_line" placeholder="' . lang('char_per_line') . '"'); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?= lang('cash_drawer_codes', 'cash1'); ?>
                                <?php echo form_input('cash_drawer_codes', $pos->cash_drawer_codes, 'class="form-control tip" id="cash1" placeholder="Hex value (x1C)"'); ?>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?= lang('pos_list_printers', 'pos_printers'); ?>
                                <?php echo form_input('pos_printers', $pos->pos_printers, 'class="form-control tip" id="pos_printers"'); ?>
                            </div>
                        </div>
                        <div class="well well-sm">
                            <p>Please add <strong><?= base_url() ?></strong> to your java Exception Site List under
                                Security tab.</p>

                            <p><strong>Access Java Control Panel</strong></p>
                            <pre><strong>Windows:</strong> Control Panel > (Java Icon) Java > Security tab > Exception Site List > Edit Site List > add<br><strong>Mac:</strong> System Preferences > (Java Icon) Java > Security tab > Exception Site List > Edit Site List > add</pre>
                        </div>

                    </div>
                </fieldset>
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?= lang('custom_fileds') ?></legend>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <?= lang('cf_title1', 'tcf1'); ?>
                            <?php echo form_input('cf_title1', $pos->cf_title1, 'class="form-control tip" id="tcf1"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <?= lang('cf_value1', 'vcf1'); ?>
                            <?php echo form_input('cf_value1', $pos->cf_value1, 'class="form-control tip" id="vcf1"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <?= lang('cf_title2', 'tcf2'); ?>
                            <?php echo form_input('cf_title2', $pos->cf_title2, 'class="form-control tip" id="tcf2"'); ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <?= lang('cf_value2', 'vcf2'); ?>
                            <?php echo form_input('cf_value2', $pos->cf_value2, 'class="form-control tip" id="vcf2"'); ?>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?= lang('shortcuts') ?></legend>
                    <p><?= lang('shortcut_heading') ?></p>

                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('focus_add_item', 'focus_add_item'); ?>
                            <?php echo form_input('focus_add_item', $pos->focus_add_item, 'class="form-control tip" id="focus_add_item"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('add_manual_product', 'add_manual_product'); ?>
                            <?php echo form_input('add_manual_product', $pos->add_manual_product, 'class="form-control tip" id="add_manual_product"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('customer_selection', 'customer_selection'); ?>
                            <?php echo form_input('customer_selection', $pos->customer_selection, 'class="form-control tip" id="customer_selection"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('add_customer', 'add_customer'); ?>
                            <?php echo form_input('add_customer', $pos->add_customer, 'class="form-control tip" id="add_customer"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('toggle_category_slider', 'toggle_category_slider'); ?>
                            <?php echo form_input('toggle_category_slider', $pos->toggle_category_slider, 'class="form-control tip" id="toggle_category_slider"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('toggle_subcategory_slider', 'toggle_subcategory_slider'); ?>
                            <?php echo form_input('toggle_subcategory_slider', $pos->toggle_subcategory_slider, 'class="form-control tip" id="toggle_subcategory_slider"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('cancel_sale', 'cancel_sale'); ?>
                            <?php echo form_input('cancel_sale', $pos->cancel_sale, 'class="form-control tip" id="cancel_sale"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('suspend_sale', 'suspend_sale'); ?>
                            <?php echo form_input('suspend_sale', $pos->suspend_sale, 'class="form-control tip" id="suspend_sale"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('print_items_list', 'print_items_list'); ?>
                            <?php echo form_input('print_items_list', $pos->print_items_list, 'class="form-control tip" id="print_items_list"'); ?>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('show_search_item', 'show_search_item'); ?>
                            <?php echo form_input('show_search_item', $pos->show_search_item, 'class="form-control tip" id="show_search_item"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('product_unit', 'product_unit'); ?>
                            <?php echo form_input('product_unit', $pos->product_unit, 'class="form-control tip" id="product_unit"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('finalize_sale', 'finalize_sale'); ?>
                            <?php echo form_input('finalize_sale', $pos->finalize_sale, 'class="form-control tip" id="finalize_sale"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('today_sale', 'today_sale'); ?>
                            <?php echo form_input('today_sale', $pos->today_sale, 'class="form-control tip" id="today_sale"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('open_hold_bills', 'open_hold_bills'); ?>
                            <?php echo form_input('open_hold_bills', $pos->open_hold_bills, 'class="form-control tip" id="open_hold_bills"'); ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('close_register', 'close_register'); ?>
                            <?php echo form_input('close_register', $pos->close_register, 'class="form-control tip" id="close_register"'); ?>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('discount', 'discount'); ?>
                            <?php echo form_input('discount', $pos->discount, 'class="form-control tip" id="discount"'); ?>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"><?= lang('payment_gateways') ?></legend>
                    <?php
                    if ($paypal_balance) {
                        echo '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button">×</button><h2>' . lang('paypal_balance') . '</h2><p>';
                        $blns = sizeof($paypal_balance['amount']);
                        $r = 1;
                        foreach ($paypal_balance['amount'] as $balance) {
                            echo lang('balance') . ': ' . $balance['L_AMT'] . ' (' . $balance['L_CURRENCYCODE'] . ')';
                            if ($blns != $r) {
                                echo ', ';
                            }
                            $r++;
                        }
                        echo '</p></div>';
                    }
                    ?>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('paypal_pro', 'paypal_pro'); ?>
                            <?= form_dropdown('paypal_pro', $yn, $pos->paypal_pro, 'class="form-control select" id="paypal_pro" required="required"'); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div id="paypal_pro_con">
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?= lang('APIUsername', 'APIUsername'); ?>
                                <?php echo form_input('APIUsername', $APIUsername, 'class="form-control tip" id="APIUsername"'); ?>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <?= lang('APIPassword', 'APIPassword'); ?>
                                <?php echo form_input('APIPassword', $APIPassword, 'class="form-control tip" id="APIPassword"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <?= lang('APISignature', 'APISignature'); ?>
                                <?php echo form_input('APISignature', $APISignature, 'class="form-control tip" id="APISignature"'); ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <?php
                    if ($stripe_balance) {
                        echo '<div class="alert alert-success"><button data-dismiss="alert" class="close" type="button">×</button><h2>' . lang('stripe_balance') . '</h2>';
                        echo '<p>' . lang('pending_amount') . ': ' . $stripe_balance['pending_amount'] . ' (' . $stripe_balance['pending_currency'] . ')';
                        echo ', ' . lang('available_amount') . ': ' . $stripe_balance['available_amount'] . ' (' . $stripe_balance['available_currency'] . ')</p>';
                        echo '</div>';
                    }
                    ?>
                    <div class="col-md-4 col-sm-4">
                        <div class="form-group">
                            <?= lang('stripe', 'stripe'); ?>
                            <?= form_dropdown('stripe', $yn, $pos->stripe, 'class="form-control select" id="stripe" required="required"'); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div id="stripe_con">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <?= lang('stripe_secret_key', 'stripe_secret_key'); ?>
                                <?php echo form_input('stripe_secret_key', $stripe_secret_key, 'class="form-control tip" id="stripe_secret_key"'); ?>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <?= lang('stripe_publishable_key', 'stripe_publishable_key'); ?>
                                <?php echo form_input('stripe_publishable_key', $stripe_publishable_key, 'class="form-control tip" id="stripe_publishable_key"'); ?>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </fieldset>

                <?php echo form_submit('update_settings', lang('update_settings'), 'class="btn btn-primary"'); ?>

                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function (e) {
        $('#pos_setting').bootstrapValidator({
            feedbackIcons: {
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh'
            }, excluded: [':disabled']
        });
        $('select.select').select2({minimumResultsForSearch: 6});
        fields = $('.form-control');
        $.each(fields, function () {
            var id = $(this).attr('id');
            var iname = $(this).attr('name');
            var iid = '#' + id;
            if (!!$(this).attr('data-bv-notempty') || !!$(this).attr('required')) {
                $("label[for='" + id + "']").append(' *');
                $(document).on('change', iid, function () {
                    $('#pos_setting').bootstrapValidator('revalidateField', iname);
                });
            }
        });
        $('input[type="checkbox"],[type="radio"]').not('.skip').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $('#customer1').val('<?= $pos->default_customer; ?>').select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url+"customers/getCustomer/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
						console.log(data);
                        callback(data[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });

        $('#enable_java_applet').change(function () {
            var ja = $(this).val();
            if (ja == 1) {
                $('#jac').slideDown();
            } else {
                $('#jac').slideUp();
            }
        });
        var ja = '<?=$pos->java_applet?>';
        if (ja == 1) {
            $('#jac').slideDown();
        } else {
            $('#jac').slideUp();
        }
        $('#paypal_pro').change(function () {
            var pp = $(this).val();
            if (pp == 1) {
                $('#paypal_pro_con').slideDown();
            } else {
                $('#paypal_pro_con').slideUp();
            }
        });
        $('#stripe').change(function () {
            var st = $(this).val();
            if (st == 1) {
                $('#stripe_con').slideDown();
            } else {
                $('#stripe_con').slideUp();
            }
        });
        var st = '<?=$pos->stripe?>';
        var pp = '<?=$pos->paypal_pro?>';
        if (st == 1) {
            $('#stripe_con').slideDown();
        } else {
            $('#stripe_con').slideUp();
        }
        if (pp == 1) {
            $('#paypal_pro_con').slideDown();
        } else {
            $('#paypal_pro_con').slideUp();
        }

    });
</script>
