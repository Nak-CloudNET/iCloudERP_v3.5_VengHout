<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>;    
    $(document).ready(function () {        
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

    });
</script>


<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('customer_opening_balance'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("sales/customer_opening_balance", $attrib);
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-md-12">
                            <div class="clearfix"></div>
                            <div class="well well-sm">
                                <a href="<?php echo $this->config->base_url(); ?>assets/csv/customer_opening_balance.csv"
                                   class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download Sample
                                    File</a>
                                <span class="text-warning"><?php echo $this->lang->line("csv1"); ?></span><br>
                                <?php echo $this->lang->line("csv2"); ?> 
                                <span class="text-info">
                                    <?php echo lang("customer_no") ?>
									,<?php echo lang("company") ?>
                                    ,<?php echo lang("customer_name") ?>
                                    ,<?php echo lang("invoice_reference_no") ?>
                                    ,<?php echo lang("opening_date") ?>
									,<?php echo lang("invoice_date") ?>
									,<?php echo lang("term") ?>
                                    ,<?php echo lang("shop_id") ?>
                                    ,<?php echo lang("sale_man_id") ?>
                                    ,<?php echo lang("balance") ?>
                                    ,<?php echo lang("deposit") ?>
                                </span> <?php echo $this->lang->line("csv3"); ?>.
                                <?= lang('first_3_are_required_other_optional'); ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("csv_file", "csv_file") ?>
                                <input id="csv_file" type="file" name="userfile" required="required"
                                       data-show-upload="false" data-show-preview="false" class="form-control file">
                            </div>
                        </div>                        
                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_sale', $this->lang->line("import"), 'id="add_sale" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></div>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var $customer = $('#slcustomer');
    $customer.change(function (e) {
        __setItem('slcustomer', $(this).val());
    });
    if (slcustomer = __getItem('slcustomer')) {
        $customer.val(slcustomer).select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url+"customers/getCustomer/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
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
        if (count > 1) {
            $customer.select2("readonly", true);
            $customer.val(slcustomer);
            $('#slwarehouse').select2("readonly", true);
        }
    } else {
        nsCustomer();
    }

// Order level shipping and discount localStorage 
if (sldiscount = __getItem('sldiscount')) {
    $('#sldiscount').val(sldiscount);
}
$('#sltax2').change(function (e) {
    __setItem('sltax2', $(this).val());
});
if (sltax2 = __getItem('sltax2')) {
    $('#sltax2').select2("val", sltax2);
}
$('#slsale_status').change(function (e) {
    __setItem('slsale_status', $(this).val());
});
if (slsale_status = __getItem('slsale_status')) {
    $('#slsale_status').select2("val", slsale_status);
}


var old_payment_term;
$('#slpayment_term').focus(function () {
    old_payment_term = $(this).val();
}).change(function (e) {
    var new_payment_term = $(this).val() ? parseFloat($(this).val()) : 0;
    if (!is_numeric($(this).val())) {
        $(this).val(old_payment_term);
        bootbox.alert('Unexpected value provided!');
        return;
    } else {
        __setItem('slpayment_term', new_payment_term);
        $('#slpayment_term').val(new_payment_term);
    }
});
if (slpayment_term = __getItem('slpayment_term')) {
    $('#slpayment_term').val(slpayment_term);
}

var old_shipping;
$('#slshipping').focus(function () {
    old_shipping = $(this).val();
}).change(function () {
    if (!is_numeric($(this).val())) {
        $(this).val(old_shipping);
        bootbox.alert('Unexpected value provided!');
        return;
    } else {
        shipping = $(this).val() ? parseFloat($(this).val()) : '0';
    }
    __setItem('slshipping', shipping);
    var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
    $('#gtotal').text(formatMoney(gtotal));
});
if (slshipping = __getItem('slshipping')) {
    shipping = parseFloat(slshipping);
    $('#slshipping').val(shipping);
} else {
    shipping = 0;
}

$('#slref').change(function (e) {
    __setItem('slref', $(this).val());
});
if (slref = __getItem('slref')) {
    $('#slref').val(slref);
}

$('#slwarehouse').change(function (e) {
    __setItem('slwarehouse', $(this).val());
});
if (slwarehouse = __getItem('slwarehouse')) {
    $('#slwarehouse').select2("val", slwarehouse);
}

        // prevent default action usln enter
$('body').bind('keypress', function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        return false;
    }
});

// Order tax calcuation 
if (site.settings.tax2 != 0) {
    $('#sltax2').change(function () {
        __setItem('sltax2', $(this).val());
        loadItems();
        return;
    });
}

// Order discount calcuation 
var old_sldiscount;
    $('#sldiscount').focus(function () {
        old_sldiscount = $(this).val();
    }).change(function () {
        var new_discount = $(this).val() ? $(this).val() : '0';
        if (is_valid_discount(new_discount)) {
            __removeItem('sldiscount');
            __setItem('sldiscount', new_discount);
            loadItems();
            return;
        } else {
            $(this).val(old_sldiscount);
            bootbox.alert('Unexpected value provided!');
            return;
        }

    });
});

function nsCustomer() {
    $('#slcustomer').select2({
        minimumInputLength: 1,
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
}
</script>
