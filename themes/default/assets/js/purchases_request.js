$(document).ready(function () {

// Order level shipping and discoutn localStorage
if (podiscount = __getItem('podiscount')) {
    $('#podiscount').val(podiscount);
}
$('#potax2').change(function (e) {
    __setItem('potax2', $(this).val());
});
if (potax2 = __getItem('potax2')) {
    $('#potax2').select2("val", potax2);
}
$('#postatus').change(function (e) {
    __setItem('postatus', $(this).val());
});
if (postatus = __getItem('postatus')) {
    $('#postatus').select2("val", postatus);
}
var old_shipping;
$('#poshipping').focus(function () {
    old_shipping = $(this).val();
}).change(function () {
    if (!is_numeric($(this).val())) {
        $(this).val(0);
        //bootbox.alert(lang.unexpected_value);
        return;
    } else {
        shipping = $(this).val() ? parseFloat($(this).val()) : '0';
    }
    __setItem('poshipping', shipping);
    var gtotal = ((total + invoice_tax) - order_discount) + shipping;
    $('#gtotal').text(formatPurDecimal(gtotal));
    $('#tship').text(formatPurDecimal(shipping));
	loadItems();
});
if (poshipping = __getItem('poshipping')) {
    shipping = parseFloat(poshipping);
    $('#poshipping').val(shipping);
}

$('#slpayment_status').change(function (e) {
	var ps = $(this).val();
	__setItem('slpayment_status', ps);
	if (ps == 'partial' || ps == 'paid') {
		if(ps == 'paid') {
			$('#amount_1').val(formatDecimal(parseFloat(((total + formatDecimal(invoice_tax)) - order_discount) + shipping)));
			$('#total_balance_1').val(formatDecimal(parseFloat(((total + formatDecimal(invoice_tax)) - order_discount) + shipping)));
		}
		$('#payments').slideDown();
		$('#pcc_no_1').focus();
	} else {
		$('#amount_1').val('');
		$('#payments').slideUp();
	}
});
if (slpayment_status = __getItem('slpayment_status')) {
	$('#slpayment_status').select2("val", slpayment_status);
	var ps = slpayment_status;
	if (ps == 'partial' || ps == 'paid') {
		$('#payments').slideDown();
		$('#pcc_no_1').focus();
	} else {
		$('#payments').slideUp();
	}
}
$(document).on('load', function(){
	$(".paid_by").trigger('change');
});

$(document).on('change', '.paid_by', function () {
	var p_val = $(this).val();
	__setItem('paid_by', p_val);
	$('#rpaidby').val(p_val);
	if (p_val == 'cash' ||  p_val == 'other') {
		$('.pcheque_1').hide();
		$('.pcc_1').hide();
		$('.depreciation_1').hide();
		$('.pcash_1').show();
		$('#payment_note_1').focus();
	} else if (p_val == 'CC') {
		$('.pcheque_1').hide();
		$('.pcash_1').hide();
		$('.depreciation_1').hide();
		$('.pcc_1').show();
		$('#pcc_no_1').focus();
	} else if (p_val == 'Cheque') {
		$('.pcc_1').hide();
		$('.pcash_1').hide();
		$('.depreciation_1').hide();
		$('.pcheque_1').show();
		$('#cheque_no_1').focus();
	} else if (p_val == 'depreciation') {
		$('.pcheque_1').hide();
		$('.pcash_1').hide();
		$('.pcc_1').hide();
		$('.depreciation_1').show();
		$('#rate_1').focus();
	} else {
		$('.pcheque_1').hide();
		$('.depreciation_1').hide();
		$('.pcc_1').hide();
		$('.pcash_1').hide();
	}
	if (p_val == 'gift_card') {
		$('.gc').show();
		$('.ngc').hide();
		$('#gift_card_no').focus();
	} else {
		$('.ngc').show();
		$('.gc').hide();
		$('#gc_details').html('');
	}
	if(p_val == 'deposit') {
		$('.dp').show();
		$('#customer1').trigger('change');
	}else{
		$('.dp').hide();
		$('#dp_details').html('');
	}
}).trigger('change');

function checkDeposit() {
	var customer_id = $("#customer1").val();
	if (customer_id != '') {
		$.ajax({
			type: "get", async: false,
			url: site.base_url + "sales/validate_deposit/" + customer_id,
			dataType: "json",
			success: function (data) {
				if (data === false) {
					$('#deposit_no_1').parent('.form-group').addClass('has-error');
					bootbox.alert(lang('invalid_customer'));
				} else if (data.id !== null && data.id !== customer_id) {
					$('#deposit_no_1').parent('.form-group').addClass('has-error');
					bootbox.alert(lang('this_customer_has_no_deposit'));
				} else {
					var amount = $("#amount_1").val();
					var deposit_amount =  (data.deposit_amount==null?0: data.deposit_amount);
					var deposit_balance = (data.deposit_amount - amount);
					$('#dp_details').html('<small>Customer Name: ' + data.name + '<br>Amount: <span class="deposit_total_amount">' + deposit_amount + '</span> - Balance: <span class="deposit_total_balance">' + deposit_balance + '</span> </small>');
					$('#deposit_no').parent('.form-group').removeClass('has-error');
					//calculateTotals();
					//$('#amount_1').val(data.deposit_amount - amount).focus();
				}
			}
		});
	}
}

$(document).on('keyup', '#amount_1', function () {
	var us_paid = $('#amount_1').val()-0;
	var deposit_amount = parseFloat($(".deposit_total_amount").text());
	var deposit_balance = parseFloat($(".deposit_total_balance").text());
	deposit_balance = (deposit_amount - us_paid);
	$(".deposit_total_balance").text(deposit_balance);
});

/*$(document).on('change', '#customer1', function(){
	checkDeposit();
	$('#amount_1').trigger('change');
});
*/
/*$(document).on('change', '#posupplier', function(){
	//checkDeposit();
	//$('#amount_1').trigger('change');
	if ($('#powarehouse').val() == "" || $('#slbiller').val() == "" || $('#slpayment_term').val() == "" ) {
		bootbox.alert(lang.select_above);
		$("#posupplier").select2('val', '');
		return false;
		//$('#powarehouse').select2("readonly", true);
    }

});*/

if (paid_by = __getItem('paid_by')) {
	var p_val = paid_by;
	$('.paid_by').select2("val", paid_by);
	$('#rpaidby').val(p_val);
	if (p_val == 'cash' ||  p_val == 'other') {
		$('.pcheque_1').hide();
		$('.pcc_1').hide();
		$('.depreciation_1').hide();
		$('.pcash_1').show();
		$('#payment_note_1').focus();
	} else if (p_val == 'CC') {
		$('.pcheque_1').hide();
		$('.pcash_1').hide();
		$('.depreciation_1').hide();
		$('.pcc_1').show();
		$('#pcc_no_1').focus();
	} else if (p_val == 'Cheque') {
		$('.pcc_1').hide();
		$('.pcash_1').hide();
		$('.depreciation_1').hide();
		$('.pcheque_1').show();
		$('#cheque_no_1').focus();
	} else if (p_val == 'depreciation'){
		$('.pcheque_1').hide();
		$('.pcash_1').hide();
		$('.pcc_1').hide();
		$('.depreciation_1').show();
		$('#rate_1').focus();
	} else {
		$('.pcheque_1').hide();
		$('.pcc_1').hide();
		$('.depreciation_1').hide();
		$('.pcash_1').hide();
	}
	if (p_val == 'gift_card') {
		$('.gc').show();
		$('.ngc').hide();
		$('#gift_card_no').focus();
	} else {
		$('.ngc').show();
		$('.gc').hide();
		$('#gc_details').html('');
	}
}

// If there is any item in localStorage
if (__getItem('poitems')) {
    loadItems();
}

    // clear localStorage and reload
    $('#reset').click(function (e) {
        bootbox.confirm(lang.r_u_sure, function (result) {
            if (result) {
                if (__getItem('poitems')) {
                    __removeItem('poitems');
                }
                if (__getItem('podiscount')) {
                    __removeItem('podiscount');
                }
                if (__getItem('potax2')) {
                    __removeItem('potax2');
                }
                if (__getItem('poshipping')) {
                    __removeItem('poshipping');
                }
                if (__getItem('poref')) {
                    __removeItem('poref');
                }
                if (__getItem('powarehouse')) {
                    __removeItem('powarehouse');
                }
				if (__getItem('slbiller')) {
                    __removeItem('slbiller');
                }
				if (__getItem('slpayment_term')) {
                    __removeItem('slpayment_term');
                }
                if (__getItem('ponote')) {
                    __removeItem('ponote');
                }
                if (__getItem('posupplier')) {
                    __removeItem('posupplier');
                }
                if (__getItem('pocurrency')) {
                    __removeItem('pocurrency');
                }
                if (__getItem('poextras')) {
                    __removeItem('poextras');
                }
                if (__getItem('podate')) {
                    __removeItem('podate');
                }
                if (__getItem('postatus')) {
                    __removeItem('postatus');
                }

                 $('#modal-loading').show();
                 location.reload();
             }
        });
	});

	// save and load the fields in and/or from localStorage
	var $supplier = $('#posupplier'), $currency = $('#pocurrency');

	$('#poref').change(function (e) {
		__setItem('poref', $(this).val());
	});
	if (poref = __getItem('poref')) {
		$('#poref').val(poref);
	}
	$('#powarehouse').change(function (e) {
		__setItem('powarehouse', $(this).val());
	});
	if (powarehouse = __getItem('powarehouse')) {
		$('#powarehouse').select2("val", powarehouse);
	}

	$('#slbiller').change(function (e) {
		__setItem('slbiller', $(this).val());
	});
	if (slbiller = __getItem('slbiller')) {
		$('#slbiller').select2("val", slbiller);
	}
	$('#slpayment_term').change(function (e) {
		__setItem('slpayment_term', $(this).val());
	});
	if (slpayment_term = __getItem('slpayment_term')) {
		$('#slpayment_term').select2("val", slpayment_term);
	}

        $('#ponote').redactor('destroy');
        $('#ponote').redactor({
            buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'link', '|', 'html'],
            formattingTags: ['p', 'pre', 'h3', 'h4'],
            minHeight: 100,
            changeCallback: function (e) {
                var v = this.get();
                __setItem('ponote', v);
            }
        });
        if (ponote = __getItem('ponote')) {
            $('#ponote').redactor('set', ponote);
        }
        $supplier.change(function (e) {
            __setItem('posupplier', $(this).val());
            $('#supplier_id').val($(this).val());
        });
        /*if (posupplier = __getItem('posupplier')) {
            $supplier.val(posupplier).select2({
                minimumInputLength: 1,
                data: [],
                initSelection: function (element, callback) {
                    $.ajax({
                        type: "get", async: false,
                        url: site.base_url+"suppliers/getSupplier/" + $(element).val(),
                        dataType: "json",
                        success: function (data) {
                            callback(data[0]);
                        }
                    });
                },
                ajax: {
                    url: site.base_url + "suppliers/suggestions",
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

		} else {
			nsSupplier();
		}*/

    /*$('.rexpiry').change(function (e) {
        var item_id = $(this).closest('tr').attr('data-item-id');
        poitems[item_id].row.expiry = $(this).val();
        __setItem('poitems', JSON.stringify(poitems));
    });*/
	if (__getItem('poextras')) {
		$('#extras').iCheck('check');
		$('#extras-con').show();
	}
	$('#extras').on('ifChecked', function () {
		__setItem('poextras', 1);
		$('#extras-con').slideDown();
	});
	$('#extras').on('ifUnchecked', function () {
		__removeItem("poextras");
		$('#extras-con').slideUp();
	});
	$(document).on('change', '.rexpiry', function () {
		var item_id = $(this).closest('tr').attr('data-item-id');
		poitems[item_id].row.expiry = $(this).val();
		__setItem('poitems', JSON.stringify(poitems));
	});

	// prevent default action upon enter
	$('body').bind('keypress', function (e) {
		if (e.keyCode == 13) {
			e.preventDefault();
			return false;
		}
	});

// Order tax calcuation
if (site.settings.tax2 != 0) {
    $('#potax2').change(function () {
        __setItem('potax2', $(this).val());
        loadItems();

    });
}

// Order discount calcuation
var old_podiscount;
$('#podiscount').focus(function () {
    old_podiscount = $(this).val();
}).change(function () {
    if (is_valid_discount($(this).val())) {
        __removeItem('podiscount');
        __setItem('podiscount', $(this).val());
        loadItems();

    } else {
        $(this).val(old_podiscount);
        bootbox.alert(lang.unexpected_value);

    }
});


    /* ----------------------
     * Delete Row Method
     * ---------------------- */

$(document).on('click', '.podel', function () {
    var row = $(this).closest('tr');
    var item_id = row.attr('data-item-id');
    if (site.settings.product_discount == 1) {
        idiscount = formatPurDecimal($.trim(row.children().children('.rdiscount').text()));
        total_discount -= idiscount;
    }
    if (site.settings.tax1 == 1) {
        var itax = row.children().children('.sproduct_tax').text();
        var iptax = itax.split(') ');
        var iproduct_tax = parseFloat(iptax[1]);
        product_tax -= iproduct_tax;
    }
    var iqty = parseFloat(row.children().children('.rquantity').val());
    var icost = parseFloat(row.children().children('.rcost').val());
    an -= 1;
    total -= (iqty * icost);
    count -= iqty;

    var gtotal = ((total + product_tax + formatPurDecimal(invoice_tax)) - total_discount) + shipping;
    $('#total').text(formatPurDecimal(total));
    $('#tds').text(formatPurDecimal(total_discount));
    $('#titems').text(count - 1);
    $('#ttax1').text(formatPurDecimal(product_tax));
    $('#gtotal').text(formatPurDecimal(gtotal));
    if (count == 1) {
        $('#posupplier').select2('readonly', false);
        $('#slbiller').select2('readonly', false);
        $('#powarehouse').select2('readonly', false);

    }
        //console.log(poitems[item_id].row.name + ' is being removed.');
        delete poitems[item_id];
        __setItem('poitems', JSON.stringify(poitems));
        row.remove();
		loadItems();
    });

    /* -----------------------
     * Edit Row Modal Hanlder
     ----------------------- */
     $(document).on('click', '.edit', function () {
        var row 		= $(this).closest('tr');
        var row_id 		= row.attr('id');
        item_id 		= row.attr('data-item-id');
        item 			= poitems[item_id];
        var qty 		= row.children().children('.rquantity').val(),
        product_option 	= row.children().children('.roption').val(),
        unit_cost 		= row.children().children('.realucost').val(),
        discount 		= row.children().children('.rdiscount').val(),
        supplier 		= row.children().children('.rsupplier_id').val();
		tax_method 		= row.children().children('.tax_method').val();
		pnote 			= row.children().children('.pnote').val();

        var net_cost 	= unit_cost;
        $('#prModalLabel').text(item.row.name + ' (' + item.row.code + ')');
		var code 		= item.row.code;

        var results 	= [];
        $.ajax({
			type: "get",
			dataType: "json",
			async: false,
			url: site.base_url+"purchases/getSupplierProduct/",
			data: { code: code},
			success: function (data) {
                results = data;
			}
		});

        if (site.settings.tax1) {
            $('#ptax').select2('val', item.row.tax_rate);
            $('#old_tax').val(item.row.tax_rate);
            var item_discount = 0, ds = discount ? discount : '0';
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    item_discount = parseFloat(((unit_cost) * parseFloat(pds[0])) / 100);
                } else {
                    item_discount = parseFloat(ds/qty);
                }
            } else {
                item_discount = parseFloat(ds/qty);
            }

			net_cost = net_cost  - item_discount;

            var pr_tax = item.row.tax_rate, pr_tax_val = 0;
            if (pr_tax !== null && pr_tax != 0) {
                $.each(tax_rates, function () {
                    if(this.id == pr_tax){
                        if (this.type == 1) {

                            if (poitems[item_id].row.tax_method == 0) {
                                pr_tax_val 	= parseFloat(((unit_cost - item_discount) * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
                                pr_tax_rate = formatPurDecimal(this.rate) + '%';
                                net_cost -= pr_tax_val;
                            } else {
                                pr_tax_val 	= parseFloat(((unit_cost - item_discount) * parseFloat(this.rate)) / 100);
                                pr_tax_rate = formatPurDecimal(this.rate) + '%';
                            }

                        } else if (this.type == 2) {

                            pr_tax_val = parseFloat(this.rate);
                            pr_tax_rate = this.rate;

                        }
                    }
                });
            }
        }

        if (site.settings.product_serial !== 0) {
            $('#pserial').val(row.children().children('.rserial').val());
        }

        var opt = '<p style="margin: 12px 0 0 0;">n/a</p>';
		if(site.settings.attributes == 1){
			if(item.options !== false) {
				var o = 1;
				opt = $("<select id=\"poption\" name=\"poption\" class=\"form-control select\" />");
				$.each(item.options, function () {
					if(o == 1) {
						$('#lbpiece').text(this.name);
						$('#piece').val(item.row.piece);
						$('#wpiece').val(formatDecimal((item.row.wpiece ? item.row.wpiece : this.qty_unit)));
					}
					if(o == item.options.length) {
						if(product_option == '') { product_variant = this.id; } else { product_variant = product_option; }
					}
					$("<option />", {value: this.id,title: this.qty_unit, text: this.name}).appendTo(opt);
					o++;
				});
			}else {
				$('#lbpiece').text("Piece");
				$('#piece').val(item.row.piece);
				$('#wpiece').val(formatDecimal((Number(item.row.wpiece) ? item.row.wpiece : 1)));
			}
		}

        $('#poptions-div').html(opt);
        $('select.select').select2({minimumResultsForSearch: 6});
        $('#pquantity').val(qty);
        $('#old_qty').val(qty);
        $('#pcost').val(formatDecimal(unit_cost));
        $('#punit_cost').val(formatPurDecimal(parseFloat(unit_cost)+parseFloat(pr_tax_val)));
        $('#poption').select2('val', item.row.option);
		$('#tax_method').select2('val', tax_method);
        $('#old_cost').val(unit_cost);
        $('#row_id').val(row_id);
        $('#item_id').val(item_id);
        $('#pnote').val(pnote);
        $('#pexpiry').val(row.children().children('.rexpiry').val());
        $('#pdiscount').val(discount);
        $('#net_cost').text(formatMoney(net_cost));
        $('#pro_tax').text(formatMoney(pr_tax_val));
        $('#prModal').appendTo("body").modal('show');

    });

	$('#piece').focus(function() {
		$(this).select();
	});

	$(document).on('change','#piece,#wpiece',function(){
		var piece  = $('#piece').val()-0;
		var wpiece = $("#wpiece").val()-0;
		if(Number(piece) && Number(wpiece)) {
			var total  = (piece*wpiece);
			$("#pquantity").val(formatDecimal(total)).trigger("change");
			$("#pnote").val(piece+" x "+wpiece);
		}else {
			$("#pnote").val('');
		}
	});

    $('#prModal').on('shown.bs.modal', function (e) {
        if($('#poption').select2('val') != '') {
            $('#poption').select2('val', product_variant);
            product_variant = 0;
        }
    });
    $(document).on('change', '#pcost',function () {
        poitems[item_id].row.net_cost 		= parseFloat($('#pcost').val());
    });

	$(document).on("change","#poption",function(){
		var qty_unit = 0;
		var total_unit_cost =0;
		var net_cost 	= item.row.net_cost;
        var qty 		= $('#pquantity').val();
        var discount	= $('#pdiscount').val();
	    qty_unit = $('option:selected', this).attr('title');
		total_unit_cost =(net_cost * qty_unit);
		$('#net_cost').val(total_unit_cost);
		$('#pcost').val(total_unit_cost);

	});
	$(document).on('change','.rdiscount',function(){
		var row = $(this).parent().parent();
		item_id = row.find('.rdiscount').attr('data-item-id');
		//var sub = row.find('.ssubtotal').html();
		var item_id = row.attr('data-item-id');
		var item = poitems[item_id];
		var ds   = row.find('.rdiscount').val() ? row.find('.rdiscount').val() : '0';

		poitems[item_id].row.discount = ds;
		__setItem('poitems', JSON.stringify(poitems));
		loadItems();
	});

    $(document).live('change', '#pcost, #ptax, #pdiscount',function () {
        var row 		= $('#' + $('#row_id').val());
        var item_id 	= row.attr('data-item-id');
		var qty 		= $('#pquantity').val();
		var tax_method  = $('#tax_method').val();
        var item 		= poitems[item_id];
        var unit_cost 	= parseFloat($('#pcost').val());
        var ds 			= $('#pdiscount').val() ? $('#pdiscount').val() : '0';
		if (ds.indexOf("%") !== -1) {
            var pds 	= ds.split("%");
            if (!isNaN(pds[0])) {
                item_discount = parseFloat(((unit_cost) * parseFloat(pds[0])) / 100);
            } else {
                item_discount = parseFloat(ds/qty);
            }
        } else {
            item_discount = parseFloat(ds/qty);
        }

       	unit_cost 	   -= item_discount;

        var pr_tax 		= $('#ptax').val(), item_tax_method = (tax_method?tax_method:item.row.tax_method);
        var pr_tax_val 	= 0, pr_tax_rate = 0;
        if (pr_tax !== null && pr_tax != 0) {
            $.each(tax_rates, function () {
                if(this.id == pr_tax){
                    if (this.type == 1) {

                        if (item_tax_method == 0) {
                            pr_tax_val 	= parseFloat(((unit_cost) * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
                            pr_tax_rate = formatMoney(this.rate) + '%';
                            unit_cost -= pr_tax_val;
                        } else {
                            pr_tax_val 	= parseFloat(((unit_cost) * parseFloat(this.rate)) / 100);
                            pr_tax_rate = formatMoney(this.rate) + '%';
                        }

                    } else if (this.type == 2) {

                        pr_tax_val = parseFloat(this.rate);
                        pr_tax_rate = this.rate;

                    }
                }
            });
        }
        $('#net_cost').text(formatMoney(unit_cost));
        $('#pro_tax').text(formatMoney(pr_tax_val));
    });

	$(document).on('change', '#tax_method', function(){
		var row 		= $('#' + $('#row_id').val());
        var item_id 	= row.attr('data-item-id');
		var qty 		= $('#pquantity').val();
		var tax_method  = $(this).val();
        var unit_cost 	= parseFloat($('#pcost').val());
        var item 		= poitems[item_id];
        var ds 			= $('#pdiscount').val() ? $('#pdiscount').val() : '0';
		if (ds.indexOf("%") !== -1) {
            var pds 	= ds.split("%");
            if (!isNaN(pds[0])) {
                item_discount = parseFloat(((unit_cost) * parseFloat(pds[0])) / 100);
            } else {
                item_discount = parseFloat(ds/qty);
            }
        } else {
            item_discount = parseFloat(ds/qty);
        }

       	unit_cost 	   -= item_discount;

        var pr_tax 		= $('#ptax').val(), item_tax_method = (tax_method?tax_method:item.row.tax_method);
        var pr_tax_val 	= 0, pr_tax_rate = 0;
        if (pr_tax !== null && pr_tax != 0) {
            $.each(tax_rates, function () {
                if(this.id == pr_tax){
                    if (this.type == 1) {

                        if (item_tax_method == 0) {
                            pr_tax_val = formatPurDecimal(((unit_cost) * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
                            pr_tax_rate = formatPurDecimal(this.rate) + '%';
                            unit_cost -= pr_tax_val;
                        } else {
                            pr_tax_val = formatPurDecimal(((unit_cost) * parseFloat(this.rate)) / 100);
                            pr_tax_rate = formatPurDecimal(this.rate) + '%';
                        }

                    } else if (this.type == 2) {

                        pr_tax_val = parseFloat(this.rate);
                        pr_tax_rate = this.rate;

                    }
                }
            });
        }

        $('#net_cost').text(formatPurDecimal(unit_cost));
        $('#pro_tax').text(formatPurDecimal(pr_tax_val));

	});

    /* -----------------------
     * Edit Row Method
     ----------------------- */
     $(document).on('click', '#editItem', function () {
        var row 		= $('#' + $('#row_id').val());
        var item_id 	= row.attr('data-item-id'), new_pr_tax = $('#ptax').val(), new_pr_tax_rate = {};
		var tax_method 	= $('#tax_method').val();
		var ser_arr 	= [];
        if (new_pr_tax) {
            $.each(tax_rates, function () {
                if (this.id == new_pr_tax) {
                    new_pr_tax_rate = this;
                }
            });
        }
		var input 		= '';
		$(".serial_no").each(function() {
			ser_arr += $(this).val()+'|';
		});

		poitems[item_id].row.piece 			= $("#piece").val()-0;
		poitems[item_id].row.wpiece 		= $("#wpiece").val()-0;
        poitems[item_id].row.qty 			= parseFloat($('#pquantity').val()),
        poitems[item_id].row.real_unit_cost = parseFloat($('#pcost').val()),
		poitems[item_id].row.net_cost 		= parseFloat($('#pcost').val()),
		poitems[item_id].row.cost 			= parseFloat($('#pcost').val()),
		poitems[item_id].row.tax_method 	= tax_method;
        poitems[item_id].row.tax_rate 		= new_pr_tax,
        poitems[item_id].tax_rate 			= new_pr_tax_rate,
		poitems[item_id].row.pnote	 		= $('#pnote').val(),
        poitems[item_id].row.discount 		= $('#pdiscount').val() ? $('#pdiscount').val() : '0',
        poitems[item_id].row.option 		= $('#poption').val(),
        poitems[item_id].row.expiry 		= $('#pexpiry').val() ? $('#pexpiry').val() : '';
		poitems[item_id].row.serial 		= ser_arr;
        __setItem('poitems', JSON.stringify(poitems));
        $('#prModal').modal('hide');
        loadItems();

     });


	/* -----------------------
	 * Product option change
	 ----------------------- */
	/*$(document).on('change', '#poption', function () {
		var row = $('#' + $('#row_id').val()), opt = $(this).val();
		var item_id = row.attr('data-item-id');
		var item = poitems[item_id];
		if(item.options !== false) {
			$.each(item.options, function () {
				if(this.id == opt && this.cost != 0 && this.cost != '' && this.cost != null) {
					$('#pcost').val(this.cost);
					$("#net_cost").text(formatPurDecimal(this.cost));
				}
			});
		}
	});
	*/
    /* ------------------------------
     * Show manual item addition modal
     ------------------------------- */
    $(document).on('click', '#addManually', function (e) {
		 if ($('#powarehouse').val() && $('#posupplier').val()) {
			 var sup = $("#posupplier").val();
					var wh = $("#powarehouse").val();
				 __setItem('posupplier', sup);
				 __setItem('powarehouse', wh);
				$('#posupplier').select2("readonly", true);
				$('#powarehouse').select2("readonly", true);

			} else {
				bootbox.alert(lang.select_above);
				item = null;
				return false;
			}
		$('#mnet_price').text('0.00');
		$('#mpro_tax').text('0.00');
        $('#mModal').appendTo("body").modal('show');
        return false;
    });
	$(document).on('click', '#addManually2', function (e) {
		 if ($('#powarehouse').val() && $('#posupplier').val()) {
			 var sup = $("#posupplier").val();
					var wh = $("#powarehouse").val();
				 __setItem('posupplier', sup);
				 __setItem('powarehouse', wh);
				$('#posupplier').select2("readonly", true);
				$('#powarehouse').select2("readonly", true);

			} else {
				bootbox.alert(lang.select_above);
				item = null;
				return false;
			}
		$('#mnet_price').text('0.00');
		$('#mpro_tax').text('0.00');
        $('#mModal2').appendTo("body").modal('show');
        return false;
    });
    /* --------------------------
     * Edit Row Quantity Method
     -------------------------- */
    var old_row_qty;
    $(document).on("focus", '.rquantity', function () {
        old_row_qty = $(this).val();

    }).on("change", '.rquantity', function () {
        var row = $(this).closest('tr');
        if (!is_numeric($(this).val())) {
            $(this).val(old_row_qty);
            bootbox.alert(lang.unexpected_value);
            return;
        }
        var new_qty = parseFloat($(this).val()),
        item_id = row.attr('data-item-id');
        poitems[item_id].row.qty = new_qty;
        __setItem('poitems', JSON.stringify(poitems));
        loadItems();

    });

	$('.rquantity').bind('keypress', function (e) {
		if (e.keyCode == 13) {
			e.preventDefault();
			$("#add_item").focus();
		}
	});

    /* --------------------------
     * Edit Row Cost Method
     -------------------------- */
    var old_cost;
    $(document).on("focus", '.rcost', function () {
        old_cost = $(this).val();
    }).on("change", '.rcost', function () {
        var row = $(this).closest('tr');
        if(!is_numeric($(this).val())){
			if($(this).val() == ''){
				$(this).val(0);
			}else{
				$(this).val(old_cost);
				bootbox.alert(lang.unexpected_value);
			}
            return;
		}
        var new_cost = parseFloat($(this).val());
        item_id = row.attr('data-item-id');
        //poitems[item_id].row.cost = new_cost;
		poitems[item_id].row.real_unit_cost = new_cost;
		poitems[item_id].row.unit_cost = new_cost;
		poitems[item_id].row.cost = new_cost;
		poitems[item_id].row.net_cost = new_cost;
        __setItem('poitems', JSON.stringify(poitems));
        loadItems();
    });

	var old_price;
	$(document).on('focus', '.rprice', function(){
		old_price = $(this).val();
	}).on('change', '.rprice', function(){
		var row = $(this).closest('tr');
		if(!is_numeric($(this).val())){
			if($(this).val() == ''){
				$(this).val(0);
			}else{
				$(this).val(old_price);
				bootbox.alert(lang.unexpected_value);
			}
            return;
		}
		var new_price = parseFloat($(this).val());

        item_id = row.attr('data-item-id');
		poitems[item_id].row.price = new_price;
        __setItem('poitems', JSON.stringify(poitems));
        loadItems();
	});

    $(document).on("click", '#removeReadonly', function () {
     $('#posupplier').select2('readonly', false);
     return false;
 });

});
/* -----------------------
 * Misc Actions
 ----------------------- */

// hellper function for supplier if no localStorage value
/*function nsSupplier() {
    $('#posupplier').select2({
        minimumInputLength: 1,
        ajax: {
            url: site.base_url + "suppliers/suggestions",
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
}*/

function loadItems() {
    if (__getItem('poitems')) {
        total 				= 0;
        count 				= 1;
        cost 				= 0;
        an 					= 1;
        product_tax 		= 0;
        invoice_tax 		= 0;
        product_discount 	= 0;
        order_discount 		= 0;
        order_discount 		= 0;
        stock 		= 0;
        $("#poTable tbody").empty();
        poitems 			= JSON.parse(__getItem('poitems'));
		var no_ 			= 1;
		var purchase_request_price = 0;
		var purchase_request_cost  = 0;
		if(gp){
			purchase_request_price = gp["purchase_request-price"];
			purchase_request_cost  = gp["purchase_request-cost"];
		}


        $.each(poitems, function () {
            $('#powarehouse').select2("readonly", true);
            //$('#posupplier').select2("readonly", true);
            //$('#poref').select2("readonly", true);

            var item = this;
            var item_id = site.settings.item_addition == 1 ? item.id : item.id;
            poitems[item_id] = item;

            var product_id 		= item.row.id,
				item_type 		= item.row.type,
				combo_items 	= item.combo_items,
				item_cost 		= item.row.cost,
				item_qty 		= (item.row.type == 'service'?1:item.row.qty),
				item_bqty 		= item.row.quantity_balance,
				item_expiry 	= item.row.expiry,
				item_tax_method = item.row.tax_method,
				item_ds 		= item.row.discount,
				item_discount 	= 0,
				item_option 	= item.row.option,
				piece			= item.row.piece,
				wpiece			= item.row.wpiece,
				item_code 		= item.row.code,
				item_name 		= item.row.name,
				serial_no 		= item.row.serial;
			if(product_id){
            var qty_received 	= (item.row.received >= 0) ? item.row.received : item.row.qty;
            var item_supplier_part_no = item.row.supplier_part_no ? item.row.supplier_part_no : '';
            var supplier 		= __getItem('posupplier'), belong = false;
            var type 			= item.row.type;
			var item_note 		= item.row.pnote;
            var supplier_name 	= '';
            var supplier_id 	= item.supplier_id?item.supplier_id:'';

			var rdiscount = item.rdisc?item.rdisc:'0';

            if (supplier == item.row.supplier1) {
                belong = true;
            } else
            if (supplier == item.row.supplier2) {
                belong = true;
            } else
            if (supplier == item.row.supplier3) {
                belong = true;
            } else
            if (supplier == item.row.supplier4) {
                belong = true;
            } else
            if (supplier == item.row.supplier5) {
                belong = true;
            }

			var disable = '';
            if(serial_no == null){
                disable = 'sp';
            }
            if(site.settings.purchase_serial == 1){
                if(disable == 'sp'){
                    $('#add_pruchase').attr('disabled', 'disabled');
                }else{
                    $('#add_pruchase').removeAttr('disabled');
                }
            }

            var unit_cost = item.row.net_cost;
            var last_cost = item.row.cost;

            var net_unit_cost = item.row.net_cost?item.row.net_cost:0;

            var checkNetCost = 'net_cost' in item.row;

            if(checkNetCost == false){
                net_unit_cost = item.row.cost?item.row.cost:0;
            }

            var ds = item_ds ? item_ds : '0';
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    item_discount = (parseFloat(((net_unit_cost) * parseFloat(pds[0])) / 100));
                } else {
                    item_discount = (parseFloat(ds)/item_qty);
                }
            } else {
                 item_discount = (parseFloat(ds)/item_qty);
            }
            product_discount += (item_discount * item_qty);

            unit_cost = parseFloat(unit_cost-item_discount);

                price = 0;
            var pr_tax = item.tax_rate;
            var pr_tax_val = 0, pr_tax_rate = 0;
            if (site.settings.tax1 == 1) {
                if (pr_tax !== false) {
                    if (pr_tax.type == 1) {
                        if (item_tax_method == '0') {
                            pr_tax_val = ((unit_cost) * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate));
                            pr_tax_rate = formatPurDecimal(pr_tax.rate) + '%';
                        } else {
                            pr_tax_val = ((unit_cost) * parseFloat(pr_tax.rate)) / 100;
                            pr_tax_rate = formatPurDecimal(pr_tax.rate) + '%';
                        }
                    } else if (pr_tax.type == 2) {
                        pr_tax_val = parseFloat(pr_tax.rate);
                        pr_tax_rate = pr_tax.rate;
                    }
                    product_tax += pr_tax_val * item_qty;
                }
            }

            item_cost = item_tax_method == 0 ? formatPurDecimal(net_unit_cost-pr_tax_val) : formatPurDecimal(unit_cost);

			item_costs = item_tax_method == 0 ? formatPurDecimal(net_unit_cost-pr_tax_val) : formatPurDecimal(net_unit_cost);

			if (item_tax_method == 0) {
				unit_cost = formatPurDecimal(net_unit_cost);
			}else{
				unit_cost = formatPurDecimal(unit_cost+item_discount);
			}

            var sel_opt = '';
            var option_qty_unit = '';

            $.each(item.options, function () {
                if(this.id == item_option) {
                    sel_opt = this.name;
                    option_qty_unit = this.qty_unit;
                    //item_cost = this.cost * option_qty_unit;
                }
            });

			var stock_in_hand =  formatPurDecimal(item.row.quantity);
			if(isNaN(stock_in_hand)){
				stock_in_hand = 0;
			}

            var row_no = (new Date).getTime();
            var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');
			tr_html = '<td class="text-right"><span class="text-center">#'+ no_ +'<input type="hidden" class="count" value="' + item_id + '"></td>';

            tr_html += '<td><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="type[]" type="hidden" class="rtype" value="' + type + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><input name="part_no[]" type="hidden" class="rpart_no" value="' + item_supplier_part_no + '"><input type="hidden" name="tax_method[]" class="tax_method" value="' + item_tax_method + '" /><input name="rsupplier_id[]" type="hidden" class="rsupplier_id" value="' + supplier_id + '"><input name="piece[]" type="hidden" class="piece" value="' + piece + '"><input name="wpiece[]" type="hidden" class="wpiece" value="' + wpiece + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')'+(sel_opt != '' ? ' ('+sel_opt+')' : '')+' <span class="">'+ supplier_name +'</span><span class="label label-default">'+item_supplier_part_no+'</span></span> <i class="pull-right fa fa-edit tip edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
           /* if (site.settings.product_expiry == 1){
                tr_html += '<td><input class="form-control date rexpiry" name="expiry[]" type="text" value="' + item_expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
            }*/
			/* Price */

			if(owner || admin || purchase_request_price) {
				tr_html += '<td class="text-right"><input style="background:#C15858;color:#fff" type="text" class="form-control text-center rprice" value="' + price + '" name="price[]"></td>';
			}else{
                tr_html += '<td class="text-right"><input style="background:#C15858;color:#fff" type="text" class="form-control text-center rprice" value="' + price + '" name="price[]"></td>';
            }

            /* Unit Cost */

			if(owner || admin || purchase_request_cost) {
				tr_html += '<td class="text-right"><input class="form-control text-center sp" name="serial[]" type="hidden" value="' + serial_no + '"><input class="form-control number_only text-center rcost" name="net_cost[]" type="text" id="cost_' + row_no + '" value="' + formatMoney(net_unit_cost) + '"><input class="rucost" name="unit_cost[]" type="hidden" value="' + net_unit_cost + '"><input class="realucost" name="real_unit_cost[]" type="hidden" value="' + net_unit_cost + '"></td>';

			} else {
                tr_html += '<td class="text-right"><input class="form-control text-center sp" name="serial[]" type="hidden" value="' + serial_no + '"><input class="form-control number_only text-center rcost" name="net_cost[]" type="text" id="cost_' + row_no + '" value="' + formatMoney(net_unit_cost) + '"><input class="rucost" name="unit_cost[]" type="hidden" value="' + net_unit_cost + '"><input class="realucost" name="real_unit_cost[]" type="hidden" value="' + net_unit_cost + '"></td>';
				//document.write (net_unit_cost);
			}

			tr_html += '<td><input name="quantity_balance[]" type="hidden" class="rbqty" value="' + item_bqty + '"><input class="form-control number_only text-center rquantity" name="quantity[]" type="text" value="' + formatPurDecimal(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"><input type="hidden" name="pro_note[]" class="pnote" value="'+ item_note +'" /></td>';

			/* Stock In Hand */
			tr_html += '<td class="text-right"><input class="form-control input-sm text-right rstock" name="rstock[]" type="hidden" id="stock_' + stock_in_hand + '" value="' + stock_in_hand + '"><input class="rstock" name="rstock[]" type="hidden" value="' + stock_in_hand + '"><input class="rstock" name="rstock[]" type="hidden" value="' + stock_in_hand + '"><span class="text-right scost" id="sstock_' + row_no + '">' + stock_in_hand + '</span></td>';

            if (po_edit) {
                tr_html += '<td class="rec_con"><input name="ordered_quantity[]" type="hidden" class="oqty" value="' + item_qty + '"><input class="form-control text-center received" name="received[]" type="text" value="' + formatPurDecimal(qty_received) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="received_' + row_no + '" onClick="this.select();"><input class="form-control text-center received_hidden" name="received_hidden[]" type="hidden" value="' + formatPurDecimal(qty_received) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="received_hidden_' + row_no + '""></td>';
            }
            if (site.settings.product_discount == 1) {
                tr_html +='<td><input class="form-control text-center"  type="hidden" value="' + item_discount + '" data-id="' + row_no + '" data-item="' + item_id + '" id="rdiscount_' + row_no + '"><input type="text" value="' + item_ds + '" class="form-control text-center rdiscount" name="rdiscount[]" /></td>';
            }

            if (site.settings.tax1 == 1) {
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + (pr_tax_rate == 0 ? formatPurDecimal(pr_tax_rate) : pr_tax_rate ) + ')' : '') + ' ' + formatMoney(pr_tax_val * item_qty) + '</span></td>';
            }

			/* Sub Total */
			//alert( ((net_unit_cost)* (item_qty) - ((item_discount)*(item_qty)) ) );
			tr_html += '<td class="text-right"><span id="sub" class="text-right ssubtotal" id="subtotal_' + row_no + '">' +(item_tax_method==0?formatMoney( (parseFloat(net_unit_cost)* parseFloat(item_qty) - (parseFloat(item_discount)*parseFloat(item_qty)) ) ):formatMoney( (parseFloat(net_unit_cost)* parseFloat(item_qty)+ (parseFloat(pr_tax_val)* parseFloat(item_qty)  ) - (parseFloat(item_discount)*parseFloat(item_qty)) ) )) + '</span></td>';



            tr_html += '<td class="text-center"><i class="fa fa-times tip podel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
            newTr.html(tr_html);
            newTr.appendTo("#poTable");

			//alert(item_cost);

			/* Total */

			total += ( item_tax_method==0?( formatPurDecimal(parseFloat(net_unit_cost)* parseFloat(item_qty) - (parseFloat(item_discount)*parseFloat(item_qty)) ) ):( formatPurDecimal(parseFloat(net_unit_cost)* parseFloat(item_qty)+ (parseFloat(pr_tax_val)* parseFloat(item_qty)  ) - (parseFloat(item_discount)*parseFloat(item_qty)) )  ) );

            count += parseFloat(item_qty);
            cost += parseFloat(net_unit_cost);
            stock += parseFloat(stock_in_hand);
            an++;
            if(belong == false){
                //$('#row_' + row_no).addClass('danger');
			}
			no_++;
			}
        });



		// Permission on COST and PRICE
		if(owner || admin){
			var col = 3 ;
		} else{
			if(purchase_request_price == 1 || purchase_request_cost == 1){
                if(purchase_request_price == 1 && purchase_request_cost == 1){
					var col = 3;
				}
				else{
					var col = 2;
				}
			} else{
				var col = 1;
			}


		}
        var tfoot = '<tr id="tfoot" class="tfoot active"><th colspan="'+col+'">Total</th><th class="text-center">'+formatNumber(parseFloat(cost))+'</th><th class="text-center">' + formatNumber(parseFloat(count) - 1) + '</th><th class="text-right">'+formatNumber(parseFloat(stock))+'</th>';
        if (po_edit) {
            tfoot += '<th class="rec_con"></th>';
        }
         // tfoot += '<th class="rec_con">'+'dd'+'</th>';
        if (site.settings.product_discount == 1) {
            tfoot += '<th class="text-right">'+formatMoney(product_discount)+'</th>';
        }
        if (site.settings.tax1 == 1) {
            tfoot += '<th class="text-right">'+formatMoney(product_tax)+'</th>';
        }
        tfoot += '<th class="text-right">'+ formatMoney(total)+'</th><th class="text-center"><i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i></th></tr>';
        $('#poTable tfoot').html(tfoot);

        // Order level discount calculations
        if (podiscount = __getItem('podiscount')) {
            var ds = podiscount;
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    order_discount = parseFloat(((total) * parseFloat(pds[0])) / 100);
                } else {
                    order_discount = parseFloat(ds);
                }
            } else {
                order_discount = parseFloat(ds);
            }
        }

        // Order level tax calculations
        if (site.settings.tax2 != 0) {
            if (potax2 = __getItem('potax2')) {
                $.each(tax_rates, function () {
                    if (this.id == potax2) {
                        if (this.type == 2) {
                            invoice_tax = parseFloat(this.rate);
                        }
                        if (this.type == 1) {
                            invoice_tax = parseFloat((((total - order_discount)+shipping) * this.rate) / 100);
                        }
                    }
                });
            }
        }
        total_discount = parseFloat(order_discount + product_discount);
        // Totals calculations after item addition
		//var gtotal = parseFloat(total + invoice_tax);
		var gtotal = parseFloat(((total - order_discount) + shipping) + formatPurDecimal(invoice_tax));
        $('#total').text(formatPurDecimal(total));
        $('#titems').text((an-1)+' ('+(parseFloat(count)-1)+')');
        $('#tds').text(formatPurDecimal(order_discount));
        if (site.settings.tax1) {
            $('#ttax1').text(formatPurDecimal(product_tax));
        }
        if (site.settings.tax2 != 0) {
            $('#ttax2').text(formatPurDecimal(invoice_tax));
        }
        $('#gtotal').text(formatPurDecimal(gtotal));
        if (an > parseInt(site.settings.bc_fix) && parseInt(site.settings.bc_fix) > 0) {
			//This Link of code is animate the screen when too products select.
            //$("html, body").animate({scrollTop: $('#sticker').offset().top}, 500);
            //$(window).scrollTop($(window).scrollTop() + 1);
        }
		//$('#postatus').trigger('change');
    }
}

$('.net_cost, .quantity').live('change',function(){
	var row = $(this).parent().parent();
	var net_price = $('.net_cost').val()-0;
	var quantity = row.find('.quantity').val()-0;
	var tax_per = row.find('.tax_percent').val();
	var tax_pay = 0;
	if(tax_per != '') {
		var rate = tax_per.split('%');
		tax_pay = ((net_price * quantity) * (rate[0]/100));
		row.find('.getTax').val(tax_pay);
		row.find('.sproduct_tax').text('('+tax_per+') '+formatPurDecimal(tax_pay));
	}
	var getTotal = formatPurDecimal(((parseFloat(net_price) * parseFloat(quantity))  + parseFloat(tax_pay)));
	row.find('.get_total').text(getTotal);
});

/* -----------------------------
 * Add Purchase Iten Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */
 function add_purchase_item(item) {

    if (count == 1) {
        poitems = {};
        if ($('#posupplier').val() && $('#powarehouse').val()) {
            $('#posupplier').select2("readonly", true);
            $('#slbiller').select2("readonly", true);


        } else {
			bootbox.alert("Please select supplier and warehouse.");
            //bootbox.alert(lang.select_above);
            item = null;
            return;
        }
    }
    if (item == null) {
        return;
    }

    var rounded = item.id;
	$( ".rid" ).each(function() {
		var rid = $(this).val();
		row     = $(this).closest('tr');
		var opt = row.find('.roption').val();
		if ((parseFloat(rid) === parseFloat(item.item_id) && parseFloat(opt) === parseFloat(item.row.option)) || (parseFloat(rid) === parseFloat(item.item_id) && item.row.option === false) ) {
			rounded = row.find('.count').val();
		}
	});

    var item_id = site.settings.item_addition == 1 ? rounded : item.id;

    if (poitems[item_id]) {
        poitems[item_id].row.qty = parseFloat(poitems[item_id].row.qty) + 1;
    } else {
        poitems[item_id] = item;
    }

    __setItem('poitems', JSON.stringify(poitems));
    loadItems();
    return true;

}

if (typeof (Storage) === "undefined") {
    $(window).bind('beforeunload', function (e) {
        if (count > 1) {
            var message = "You will loss data!";
            return message;
        }
    });
}