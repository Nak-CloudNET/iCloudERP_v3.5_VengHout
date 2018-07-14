$(document).ready(function () {
	
	//Order level shipping and discoutn localStorage
    if (qudiscount = __getItem('qudiscount')) {
        $('#qudiscount').val(qudiscount);
    }
	
	if (slarea = __getItem('slarea')) {
        $('#slarea').select2("val", slarea);
    }
	
    $('#qutax2').change(function (e) {
        __setItem('qutax2', $(this).val());
        $('#qutax2').val($(this).val());
    });
    if (qutax2 = __getItem('qutax2')) {
        $('#qutax2').select2("val", qutax2);
    }
    $('#qustatus').change(function (e) {
        __setItem('qustatus', $(this).val());
    });
    if (qustatus = __getItem('qustatus')) {
        $('#qustatus').select2("val", qustatus);
    }
    var old_shipping;
    $('#qushipping').focus(function () {
        old_shipping = $(this).val();
    }).change(function () {
        if (!is_numeric($(this).val())) {
            $(this).val(old_shipping);
            bootbox.alert(lang.unexpected_value);
            return;
        } else {
            shipping = $(this).val() ? parseFloat($(this).val()) : '0';
        }
        __setItem('qushipping', shipping);
        loadItems();
        //var gtotal = ((total + invoice_tax) - order_discount) + shipping;
        //$('#gtotal').text(formatMoney(gtotal));
        //$('#tship').text(formatMoney(shipping));
		
    });
    if (qushipping = __getItem('qushipping')) {
        shipping = parseFloat(qushipping);
        $('#qushipping').val(shipping);
    } else {
        shipping = 0;
    }

// If there is any item in localStorage
    if (__getItem('quitems')) {
        loadItems();
    }

    // clear localStorage and reload
    $('#reset').click(function (e) {
            bootbox.confirm(lang.r_u_sure, function (result) {
                if (result) {
                    if (__getItem('quitems')) {
                        __removeItem('quitems');
                    }
					
                    if (__getItem('qudiscount')) {
                        __removeItem('qudiscount');
                    }
                    if (__getItem('qutax2')) {
                        __removeItem('qutax2');
                    }
                    if (__getItem('qushipping')) {
                        __removeItem('qushipping');
                    }
                    if (__getItem('quref')) {
                        __removeItem('quref');
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
                    if (__getItem('qucurrency')) {
                        __removeItem('qucurrency');
                    }
                    if (__getItem('qudate')) {
                        __removeItem('qudate');
                    }
                    if (__getItem('qustatus')) {
                        __removeItem('qustatus');
                    }
                    if (__getItem('qubiller')) {
                        __removeItem('qubiller');
                    }

                    $('#modal-loading').show();
                    location.reload();
                }
            });
    });

// save and load the fields in and/or from localStorage

    $('#quref').change(function (e) {
        __setItem('quref', $(this).val());
    });
    if (quref = __getItem('quref')) {
        $('#quref').val(quref);
    }
    $('#quwarehouse').change(function (e) {
        __setItem('quwarehouse', $(this).val());
    });
    if (quwarehouse = __getItem('quwarehouse')) {
        $('#quwarehouse').select2("val", quwarehouse);
    }

    $('#qunote').redactor('destroy');
    $('#qunote').redactor({
        buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'link', '|', 'html'],
        formattingTags: ['p', 'pre', 'h3', 'h4'],
        minHeight: 100,
        changeCallback: function (e) {
            var v = this.get();
            __setItem('qunote', v);
        }
    });
    if (qunote = __getItem('qunote')) {
        $('#qunote').redactor('set', qunote);
    }
	
    var $customer = $('#qucustomer');
    $customer.change(function (e) {
        __setItem('qucustomer', $(this).val());
    });
	
    if (qucustomer = __getItem('qucustomer')) {
        $customer.val(qucustomer).select2({
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
    } else {
        nsCustomer();
    }
	
	
	var $customer = $('#slarea');
    $customer.change(function (e) {
        __setItem('slarea', $(this).val());
    });
	
    /*if (slarea = __getItem('slarea')) {
        $customer.val(slarea).select2({
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
    } else {
        nsCustomer();
    }
*/

// prevent default action upon enter
    $('body').bind('keypress', function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });

// Order tax calculation
if (site.settings.tax2 != 0) {
    $('#qutax2').change(function () {
        __setItem('qutax2', $(this).val());
        loadItems();

    });
}

// Order discount calculation
var old_qudiscount;
$('#qudiscount').focus(function () {
    old_qudiscount = $(this).val();
}).change(function () {
    var new_discount = $(this).val() ? $(this).val() : '0';
    if (is_valid_discount(new_discount) && (new_discount > -1 && new_discount < 101)) {
        __removeItem('qudiscount');
        __setItem('qudiscount', new_discount);
        loadItems();

    } else {
        bootbox.alert(lang.unexpected_value);
        $(this).val(old_qudiscount);

    }

});

/* -----------------------
	 * Product Group Price change
	 ----------------------- */
	 $(document).on('change', '#pgroup_price', function () {
		var row = $('#' + $('#row_id').val()), opt = $(this).val();
		var item_id = row.attr('data-item-id');
		var item = quitems[item_id];
		var default_price = formatDecimal(row.find('.default_price').val());
		if(item.all_group_prices !== false) {
			$.each(item.all_group_prices, function () {
			    /*
				if(opt ==0) {
					$('#pprice').val(default_price);
					$('#pprice_show').val(default_price);
					//item.row.price_id = opt;
					//__setItem('quitems', JSON.stringify(item));
					$('#poption').trigger('change');
					$('#ptax').trigger('change');
					$('#curr_rate').val(0);
				}else {
					if(this.price_group_id == opt) {
						$('#pprice').val(this.price);
						$('#pprice_show').val(this.price);
					}
					//item.row.price_id = opt;
					//__setItem('quitems', JSON.stringify(item));
					$('#poption').trigger('change');
					$('#ptax').trigger('change');
				}
				*/

                if(opt ==0){
                    if(item.options !== false) {
                        $('#poption').trigger('change');
                    }else{
                        price = (default_price+((default_price*item.customer_percent)/100));
                        $('#pprice,#pprice_show').val(price);
                        $("#net_price").text(formatMoney(price));
                    }
                }else{
                    if(this.id == opt) {
                        if(this.rate!=0)
                        {
                            rate  = (this.price)-0;
                            price = (rate+((rate*item.customer_percent)/100));
                            $('#pprice,#pprice_show').val(parseFloat((price/this.rate)*1).toFixed(2));
                            $("#net_price").text(formatMoney((price/this.rate)*1));
                        }else{
                            $('#pprice,#pprice_show').val(parseFloat(price).toFixed(2));
                            $("#net_price").text(formatMoney(price));
                        }
                    }
                }

			});
		}
	});

/* ----------------------
 * Delete Row Method
 * ---------------------- */

$(document).on('click', '.qudel', function () {
    var row = $(this).closest('tr');
    var item_id = row.attr('data-item-id');
    delete quitems[item_id];
    row.remove();
    if(quitems.hasOwnProperty(item_id)) { } else {
        __setItem('quitems', JSON.stringify(quitems));
        loadItems();

    }
});

    /* -----------------------
     * Edit Row Modal Hanlder
     ----------------------- */
	 
     $(document).on('click', '.edit', function () {
        var row = $(this).closest('tr');
        var row_id = row.attr('id');
        item_id = row.attr('data-item-id');
        item = quitems[item_id];
		var product_variant = 0;
		
        var qty = row.children().children('.rquantity').val(),
        product_option = row.children().children('.roption').val(),
        unit_price = row.children().children('.realuprice').val(),
        discount = row.children().children('.rdiscount').val();
		var default_price = formatDecimal(row.find('.default_price').val());
		var currRate = formatDecimal(row.find('.curr_rate').val());
        var net_price = unit_price;
        $('#prModalLabel').text(item.row.name + ' (' + item.row.code + ')');
        if (site.settings.tax1) {
            $('#ptax').select2('val', item.row.tax_rate);
            $('#old_tax').val(item.row.tax_rate);
            var item_discount = 0, ds = discount ? discount : '0';
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    item_discount = parseFloat(((unit_price*qty) * (parseFloat(pds[0]) / 100))/qty);
                } else {
                    item_discount = parseFloat(ds/qty);
                }
            } else {
                item_discount = parseFloat(ds/qty);
            }
			
			net_price-=item_discount;
            var pr_tax = item.row.tax_rate, pr_tax_val = 0;
            if (pr_tax !== null && pr_tax != 0) {
				
                $.each(tax_rates, function () {
                    if(this.id == pr_tax){
                        if (this.type == 1) {
                            if (quitems[item_id].row.tax_method == 0) {
                                pr_tax_val = formatDecimal(((net_price) * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
                                pr_tax_rate = formatDecimal(this.rate) + '%';
                                net_price -= pr_tax_val;
								
                            } else {
                                pr_tax_val = formatDecimal(((net_price) * parseFloat(this.rate)) / 100);
                                pr_tax_rate = formatDecimal(this.rate) + '%';
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
		var opt1 = '<p style="margin: 12px 0 0 0;">n/a</p>';
		var opt_group_price = '<p style="margin: 12px 0 0 0;">n/a</p>';
		
			
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
				$("<option />", {value: this.id, text: this.name}).attr("rate",item.row.cost).attr("qty_unit",this.qty_unit).attr("makeup_cost_percent",item.makeup_cost_percent).appendTo(opt);
				o++;
			});
		}else {
			$('#lbpiece').text("Piece");
			$('#piece').val(item.row.piece);
			$('#wpiece').val(formatDecimal((Number(item.row.wpiece) ? item.row.wpiece : 1)));
		}

         if(item.all_group_prices) {
             var gp = 1;
             opt_group_price = $("<select id=\"pgroup_price\" name=\"pgroup_price\" class=\"form-control select\" />");

             $.each(item.all_group_prices, function () {
                 if(gp == 1) {
                     $("<option />", {value: 0, text: 'Default Price'}).appendTo(opt_group_price);
                     if(item.row.price_id == 0) {
                         $("<option />", {value: this.id, text: this.group_name + ' (' + formatDecimal(this.price) + ' '+ this.currency_code +') (' + this.variant_name + ')'}).attr("rate",this.rate).attr("datacurrency_code",this.currency_code).attr("data-unit-id", this.unit_id).appendTo(opt_group_price);
                     }else{
                         $("<option />", {value: this.id, text: this.group_name + ' (' + formatDecimal(this.price) + ' '+ this.currency_code +') (' + this.variant_name + ')'}).attr("rate",this.rate).attr("datacurrency_code",this.currency_code).attr("data-unit-id", this.unit_id).appendTo(opt_group_price);
                     }
                 }else{
                     $("<option />", {value: this.id, text: this.group_name + ' (' + formatDecimal(this.price) + ' '+ this.currency_code +') (' + this.variant_name + ')'}).attr("rate",this.rate).attr("datacurrency_code",this.currency_code).attr("data-unit-id", this.unit_id).appendTo(opt_group_price);
                 }
                 gp++;
             });
         }
		
		/* Price Group */
		$('#pg-div').html(opt1);
        $('#poptions-div').html(opt);
		$('#pgroup_prices-div').html(opt_group_price);
        $('select.select').select2({minimumResultsForSearch: 6});
        $('#pquantity').val(qty);
        $('#old_qty').val(qty);
        $('#pprice').val(unit_price);
		$('#pprice_show').val(unit_price);
		$('#curr_rate').val(currRate);
        $('#punit_price').val(formatDecimal(parseFloat(unit_price)+parseFloat(pr_tax_val)));
		$('#poption').select2('val', item.row.option);
		$('#pgroup_price').select2('val', item.row.price_id);
		$('#pnote').redactor('set',item.row.product_noted?item.row.product_noted:'');
        $('#old_price').val(unit_price);
        $('#row_id').val(row_id);
        $('#item_id').val(item_id);
        $('#pserial').val(row.children().children('.rserial').val());
        $('#pdiscount').val(discount);
        $('#net_price').text(formatMoney(net_price));
        $('#pro_tax').text(formatMoney(pr_tax_val));
        $('#prModal').appendTo("body").modal('show');
		
		$('#piece').trigger('change');
		$('#poption').trigger('change');
		$('#pgroup_price').trigger('change');
    });
	
	$('#piece').focus(function() {
		$(this).select();
	});
	
    $('#prModal').on('shown.bs.modal', function (e) {
		var row = $(this).closest('tr');
		var product_variant = row.children().children('.poption').val();
        if($('#poption').select2('val') != '') {
			if(site.settings.attributes == 1){
				//$('#poption').select2('val', product_variant);
				product_variant = 0;
			}
        }
    });
	

    $(document).on('change', '#pprice_show, #ptax, #pdiscount,#pquantity', function () {
		
        var row = $('#' + $('#row_id').val());
        var item_id = row.attr('data-item-id');
        var unit_price = parseFloat($('#pprice_show').val());
		
        var item = quitems[item_id];
		
        var ds = $('#pdiscount').val() ? $('#pdiscount').val() : '0';
		var item_qty = parseFloat($('#pquantity').val());
		
        if (ds.indexOf("%") !== -1) {
            var pds = ds.split("%");
            if (!isNaN(pds[0])) {
                item_discount = parseFloat(((unit_price*item_qty) * parseFloat(pds[0] / 100))/item_qty);
				
            } else {
                item_discount = parseFloat(ds / item_qty);
            }
        } else {
            item_discount = parseFloat(ds / item_qty);
        }
		
        var pr_tax = $('#ptax').val(), item_tax_method = item.row.tax_method;
        var pr_tax_val = 0, pr_tax_rate = 0;
		unit_price = unit_price - item_discount;
		
		
        if (pr_tax !== null && pr_tax != 0) {
            $.each(tax_rates, function () {
                if(this.id == pr_tax){
                    if (this.type == 1) {
                        if (item_tax_method == 0) {
                            pr_tax_val = formatDecimal((unit_price * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
                            pr_tax_rate = formatDecimal(this.rate) + '%';
                            unit_price -= pr_tax_val;
                        } else {
                            pr_tax_val = formatDecimal(((unit_price) * parseFloat(this.rate)) / 100);
                            pr_tax_rate = formatDecimal(this.rate) + '%';
						}
						
                    } else if (this.type == 2) {
                        pr_tax_val = parseFloat(this.rate);
                        pr_tax_rate = this.rate;

                    }
                }
            });
        }
		
		
        $('#net_price').text(formatMoney(unit_price));
        $('#pro_tax').text(formatMoney(pr_tax_val));
    });

    /* -----------------------
     * Edit Row Method
     ----------------------- */
     $(document).on('click', '#editItem', function () {
        var row = $('#' + $('#row_id').val());
        var item_id = row.attr('data-item-id'), new_pr_tax = $('#ptax').val(), new_pr_tax_rate = {};
        if (new_pr_tax) {
            $.each(tax_rates, function () {
                if (this.id == new_pr_tax) {
                    new_pr_tax_rate = this;
                }
            });
        } else {
            new_pr_tax_rate = false;
        }
		var pquantity = parseFloat($('#pquantity').val());
        var price = parseFloat($('#pprice_show').val());
		var opt_cur = $("#pgroup_price option:selected").attr('rate');
		
		//alert(JSON.stringify(opt_cur));
		if(opt_cur == undefined)
		{
			price = (price);
		}else{
			price = (price*opt_cur);
		}
		
		var total_price = price * pquantity;
		
		if (site.settings.product_discount == 1 && $('#pdiscount').val()) {
			if(!is_valid_discount($('#pdiscount').val()) || $('#pdiscount').val() > total_price) {
				bootbox.alert(lang.unexpected_value);
				return false;
			}
		}
		
		var piece  = $("#piece").val()-0;
		var wpiece = $("#wpiece").val()-0;
		quitems[item_id].row.piece = piece;
		quitems[item_id].row.wpiece = wpiece;
        quitems[item_id].row.qty = parseFloat($('#pquantity').val()),
        quitems[item_id].row.real_unit_price = price,
        quitems[item_id].row.tax_rate = new_pr_tax,
        quitems[item_id].tax_rate = new_pr_tax_rate,
        quitems[item_id].row.discount = $('#pdiscount').val() ? $('#pdiscount').val() : '',
        quitems[item_id].row.option = $('#poption').val() ? $('#poption').val() : '',
		quitems[item_id].row.promo_price = $('#pdiscount').val() ? $('#pdiscount').val() : '',
		quitems[item_id].row.price_id = $('#pgroup_price').val() ? $('#pgroup_price').val() : '',
		quitems[item_id].row.product_noted = $('#pnote').val() ? $('#pnote').val() : '',
        quitems[item_id].row.serial = $('#pserial').val();
		
		if(quitems[item_id].group_prices){
			quitems[item_id].row.load_item=0;
			quitems[item_id].group_prices[0].price = price;
			quitems[item_id].group_prices[0].rate = opt_cur;
			quitems[item_id].group_prices[0].id = $('#pgroup_price').val() ? $('#pgroup_price').val() : '';
		}
        __setItem('quitems', JSON.stringify(quitems));
        $('#prModal').modal('hide');

        loadItems();

     });

	$(document).on('change', '#group_price', function () {

	 	var row = $('#' + $('#row_id').val()), opt = $(this).val();
	 	var item_id = row.attr('data-item-id');
	 	var item = quitems[item_id];
		var final_price = '';
		var p_kh_rate = __getItem('exchange_kh');

	 	if(item.group_price) {
	 		$.each(item.group_price, function () {
	 			if(this.id == opt && this.price != 0) {
					if(this.currency_code == 'KHM'){
						final_price = this.price/p_kh_rate;
					}else{
						final_price = this.price;
					}
	 				$('#pprice').val(formatMoney(parseFloat(final_price)));
					$('#pprice').trigger('change');
					
	 			}
				
				
				
				/*
				else{
					$('#pprice').val(formatDecimal(parseFloat(opt)));
				}
				*/
	 		});
	 	}
	 });
	 
	
	
	$(document).on('change', '#poption', function () {
	 	var row = $('#' + $('#row_id').val()), opt = $(this).val();
	 	var item_id = row.attr('data-item-id');
	 	var item = quitems[item_id];
        var current_price_group = $("#pgroup_price").val();
	 	if(item.options !== false) {
	 		$.each(item.options, function () {
	 			if(this.id == opt) {
	 			    var price= parseFloat(this.price);
                    var pro_mkp	=  $("#poption option:selected").attr('makeup_cost_percent');
                    if(item.makeup_cost == 1 && pro_mkp != undefined) {
                        var pro_opt = $("#poption option:selected").attr('rate');
                        var pro_qty = $("#poption option:selected").attr('qty_unit');
                        var pro_mkp	=  $("#poption option:selected").attr('makeup_cost_percent');
                        price = (pro_opt*pro_qty)+((pro_opt*pro_qty)*(isNaN(pro_mkp)?0:pro_mkp)/100);

                    }
                    price = (parseFloat(price)+((parseFloat(price)*item.customer_percent)/100));
                    $('#pprice').val(price);
                    $("#net_price").text(formatMoney(price));
                    $('#pprice_show').val(formatDecimal(price));
	 			}
	 		});
	 	}

        if(item.all_group_prices !== false){
            var new_group_price_id = $("#pgroup_price option[data-unit-id='"+opt+"']").val();
            $('#pgroup_price option').attr('disabled', false);
            $.each($('#pgroup_price option').not(':first-child'), function () {
                var val = $(this).val();
                var data_unit_id = $(this).attr('data-unit-id');
                if(data_unit_id != opt){
                    $(this).attr('disabled', true);
                }
            });

            if(current_price_group > 0){
                if(item.row.price_id == current_price_group && opt == item.row.option){
                    $('#pgroup_price').select2('val', item.row.price_id);
                }else{
                    $('#pgroup_price').select2('val', new_group_price_id);
                }
                $("#pgroup_price").trigger('change');
            }else{
                $('#pgroup_price').select2('val', current_price_group);
            }
        }
		
		$("#pprice_show").trigger("change");
	});
	 
	 
	 
	 /* -----------------------
     * Product option change
     ----------------------- */
    /* $(document).on('change', '#poption', function () {
        var row = $('#' + $('#row_id').val()), opt = $(this).val();
        var item_id = row.attr('data-item-id');
        var item = quitems[item_id];
        if(item.options !== false) {
            $.each(item.options, function () {
                if(this.id == opt && this.price != 0 && this.price != '' && this.price != null) {
					$('#pprice').val(formatMoney(this.price));
					$("#net_price").text(formatMoney(this.price));
					$("#pprice_show").val(formatMoney(this.price));
					
                }
            });
        }
    }); */
	

    /* ------------------------------
     * Show manual item addition modal
     ------------------------------- */
     $(document).on('click', '#addManually', function (e) {
		 e.preventDefault();
		// alert(0);
        if (count == 1) {
            quitems = {};
            if ($('#quwarehouse').val() && $('#qucustomer').val()) {
				var sup = $("#qucustomer").val();
					var wh = $("#quwarehouse").val();
				 __setItem('qucustomer', sup);
				 __setItem('quwarehouse', wh);
                $('#qucustomer').select2("readonly", true);
                $('#quwarehouse').select2("readonly", true);
            } else {
                bootbox.alert(lang.select_above);
                item = null;
                return false;
            }
        }
        $('#mnet_price').text('0.00');
        $('#mpro_tax').text('0.00');
        $('#mModal2').appendTo("body").modal('show');
        return false;
    });

     $(document).on('click', '#addItemManually', function (e) {
        var mid = (new Date).getTime(),
        mcode = $('#mcode').val(),
        mname = $('#mname').val(),
        mtax = parseInt($('#mtax').val()),
        mqty = parseFloat($('#mquantity').val()),
        mdiscount = $('#mdiscount').val() ? $('#mdiscount').val() : '0',
        unit_price = parseFloat($('#mprice').val()),
        mtax_rate = {};
        $.each(tax_rates, function () {
            if (this.id == mtax) {
                mtax_rate = this;
            }
        });

        quitems[mid] = {"id": mid, "item_id": mid, "label": mname + ' (' + mcode + ')', "row": {"id": mid, "code": mcode, "name": mname, "quantity": mqty, "price": unit_price, "unit_price": unit_price, "real_unit_price": unit_price, "tax_rate": mtax, "tax_method": 0, "qty": mqty, "type": "manual", "discount": mdiscount, "serial": "", "option":""}, "tax_rate": mtax_rate, "options":false};
        __setItem('quitems', JSON.stringify(quitems));
        loadItems();
        $('#mModal').modal('hide');
        $('#mcode').val('');
        $('#mname').val('');
        $('#mtax').val('');
        $('#mquantity').val('');
        $('#mdiscount').val('');
        $('#mprice').val('');
        return false;
    });

    $(document).on('change', '#mprice, #mtax, #mdiscount', function () {
        var unit_price = parseFloat($('#mprice').val());
        var ds = $('#mdiscount').val() ? $('#mdiscount').val() : '0';
        if (ds.indexOf("%") !== -1) {
            var pds = ds.split("%");
            if (!isNaN(pds[0])) {
                item_discount = parseFloat(((unit_price) * parseFloat(pds[0])) / 100);
            } else {
                item_discount = parseFloat(ds);
            }
        } else {
            item_discount = parseFloat(ds);
        }
        unit_price -= item_discount;
        var pr_tax = $('#mtax').val(), item_tax_method = 0;
        var pr_tax_val = 0, pr_tax_rate = 0;
        if (pr_tax !== null && pr_tax != 0) {
            $.each(tax_rates, function () {
                if(this.id == pr_tax){
                    if (this.type == 1) {

                        if (item_tax_method == 0) {
                            pr_tax_val = formatDecimal(((unit_price) * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
                            pr_tax_rate = formatDecimal(this.rate) + '%';
                            unit_price -= pr_tax_val;
                        } else {
                            pr_tax_val = formatDecimal(((unit_price) * parseFloat(this.rate)) / 100);
                            pr_tax_rate = formatDecimal(this.rate) + '%';
                        }

                    } else if (this.type == 2) {

                        pr_tax_val = parseFloat(this.rate);
                        pr_tax_rate = this.rate;

                    }
                }
            });
        }

        $('#mnet_price').text(formatMoney(unit_price));
        $('#mpro_tax').text(formatMoney(pr_tax_val));
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
        quitems[item_id].row.qty = new_qty;
        __setItem('quitems', JSON.stringify(quitems));
        loadItems();
    });

    /* --------------------------
     * Edit Row Price Method
     -------------------------- */
    var old_price;
    $(document).on("focus", '.rprice', function () {
        old_price = $(this).val();
    }).on("change", '.rprice', function () {
        var row = $(this).closest('tr');
        if (!is_numeric($(this).val())) {
            $(this).val(old_price);
            bootbox.alert(lang.unexpected_value);
            return;
        }
        var new_price = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');
        quitems[item_id].row.price = new_price;
        __setItem('quitems', JSON.stringify(quitems));
        loadItems();
    });

    $(document).on("click", '#removeReadonly', function () {
       $('#qucustomer').select2('readonly', false);
       //$('#quwarehouse').select2('readonly', false);
       return false;
    });


});


	$(document).on('change','#piece,#wpiece',function(){
		var piece  = $('#piece').val()-0;
		var wpiece = $("#wpiece").val()-0;
		if(Number(piece) && Number(wpiece)) {
			var total  = (piece*wpiece);
			$("#pquantity").val(formatDecimal(total)).trigger("change");
			$("#pnote").val(piece+" x "+wpiece);
		}else {
			//$("#pnote").val('');
		}	
	});

/* -----------------------
 * Misc Actions
 ----------------------- */

// hellper function for customer if no localStorage value
function nsCustomer() {
    $('#qucustomer').select2({
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
//localStorage.clear();
function loadItems() {
    if (__getItem('quitems') ) {
        total = 0;
        count = 1;
        an = 1;
        product_tax = 0;
        invoice_tax = 0;
        product_discount = 0;
        order_discount = 0;
        total_discount = 0;

        $("#quTable tbody").empty();
		quitems = JSON.parse(__getItem('quitems'));
		var no=1;
        $.each(quitems, function () {

            var item = this;
            var item_id 	= site.settings.item_addition == 1 ? item.id : item.id;
            quitems[item_id]= item;
			
			var item_qty 	= item.row.qty;
			var item_type 	= item.row.type;
			var product_id 	= item.row.id, 
			combo_items 	= item.combo_items, 
			item_promotion 	= item.row.promotion, 
			item_pro_price 	= item.row.promo_price,
			item_price 		= item.row.price, 
			item_aqty 		= item.row.quantity, 
			item_qoh  		= item.row.qoh,
			item_tax_method = item.row.tax_method, 
			item_ds 		= item.row.discount, 
			item_discount 	= 0, 
			item_option 	= item.row.option,
			group_prices 	= item.group_prices,
			group_price_id 	= item.row.price_id,
			all_group_price = item.all_group_price,
			price_id 		= item.row.price_id,
			item_note 		= item.row.product_noted,
			item_code 		= item.row.code, 
			digital_code 	= item.row.digital_code,
			item_item_cur 	= item.row.rate_item_cur,
			item_load     	= item.row.load_item,
			item_serial 	= item.row.serial, 
			piece 			= item.row.piece, 	
			wpiece 			= item.row.wpiece,
			w_piece 		= item.row.w_piece,
			item_name 		= item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
			digital_name 	= item.row.digital_name.replace(/"/g, "&#034;").replace(/'/g, "&#039;"),
			digital_id 		= item.row.digital_id?item.row.digital_id:0;

            var unit_price = parseFloat(item.row.real_unit_price);
            var real_unit_price = parseFloat(item.row.real_unit_price);
			var exchange_rate = $("#exchange_rate").val();
			var is_edit = $("#is_edit").val() ? $("#is_edit").val() : 0;
			var default_price = item_price;
			var currRate = 0;

			if(is_edit == 0){
				if(item_promotion && item.row.start_date && item.row.end_date){
					var pro_start_date = moment(item.row.start_date).format('DD/MM/YYYY');
					var pro_end_date = moment(item.row.end_date).format('DD/MM/YYYY');
					var currentDate = moment().format('DD/MM/YYYY');
					
					if(currentDate >= pro_start_date && currentDate <= pro_end_date){
						item_ds = item_pro_price;
					}
				}
				
				if(all_group_price){
					
					$.each(all_group_price, function(){
						
						var cur_price_1 = this.price;
					
						if(item.makeup_cost == 1){
							
							if(item_item_cur!=0){
								cur_price_1 = ((cur_price_1/item_item_cur)*1);
							}else{
								cur_price_1 = ((cur_price_1)*1);
							}
								
							if(item_load==0)
							{
								//cur_price_1 = parseFloat(cur_price_1) + parseFloat((cur_price_1 * item.customer_percent) / 100);
							}
							
							if(item.options) {
								if(this.id == price_id && this.product_id == product_id && this.unit_id == item_option && this.unit_type == 'variant')
								{
									item_price = cur_price_1;
									unit_price = cur_price_1;
									real_unit_price = cur_price_1;
									item.row.price_id = this.price_group_id;
								}
							}else {
								item_price = cur_price_1;
								unit_price = cur_price_1;
								real_unit_price = cur_price_1;
								item.row.price_id = this.price_group_id;
							}
							
						}else{
							
							if(item_item_cur!=0){
								cur_price_1 = ((cur_price_1/item_item_cur)*1);
							}else{
								cur_price_1 = ((cur_price_1)*1);
							}
						
							cur_price_1 = parseFloat(cur_price_1) + parseFloat((cur_price_1 * item.customer_percent) / 100);
						
							if(item.options) {
								if(this.id == price_id && this.product_id == product_id && this.unit_id == item_option && this.unit_type == 'variant')
								{
									item_price = cur_price_1;
									unit_price = cur_price_1;
									real_unit_price = cur_price_1;
									item.row.price_id = this.price_group_id;
								}
							}else {
								item_price = cur_price_1;
								unit_price = cur_price_1;
								real_unit_price = cur_price_1;
								item.row.price_id = this.price_group_id;
							}
						} 
						
						//alert(JSON.stringify(group_price_id));
						
						//default_price = this.default_price;
						//group_price_id    = item.row.group_price_id; 
					});
				}
				
			}else{
				if(item_load==0)
				{
					
					if(all_group_price){
						$.each(all_group_price, function(){
							var cur_price_1 = this.price;
						
							if(item.makeup_cost == 1) {
								
								if(item_item_cur!=0){
									cur_price_1 = ((cur_price_1/item_item_cur)*1);
								}else{
									cur_price_1 = ((cur_price_1)*1);
								}
								
								if(item_load==0)
								{
									cur_price_1 = parseFloat(cur_price_1) + parseFloat((cur_price_1 * item.customer_percent) / 100);
								}
								
								if(item.options) {
									if(this.id == price_id && this.product_id == product_id && this.unit_id == item_option && this.unit_type == 'variant')
									{
										item_price = cur_price_1;
										unit_price = cur_price_1;
										real_unit_price = cur_price_1;
										item.row.price_id = this.id;
									}
								}else {
									item_price = cur_price_1;
									unit_price = cur_price_1;
									real_unit_price = cur_price_1;
									item.row.price_id = this.id;
								}
								
							}else {
								if(item_item_cur!=0){
									cur_price_1 = ((cur_price_1/item_item_cur)*1);
								}else{
									cur_price_1 = ((cur_price_1)*1);
								}
								
								cur_price_1 = parseFloat(cur_price_1) + parseFloat((cur_price_1 * item.customer_percent) / 100);
								
								if(item.options) {
									if(this.id == price_id && this.product_id == product_id && this.unit_id == item_option && this.unit_type == 'variant')
									{
										item_price = cur_price_1;
										unit_price = cur_price_1;
										real_unit_price = cur_price_1;
										item.row.price_id = this.id;
									}
								}else {
									item_price = cur_price_1;
									unit_price = cur_price_1;
									real_unit_price = cur_price_1;
									item.row.price_id = this.id;
								}
							}
							
							//default_price = this.default_price;
							//group_price_id    = item.row.group_price_id; 
						});
						
					}
				}
			}
			
			var pn = item_note ? item_note : '';
            var ds = item_ds ? item_ds : '0';
			
			if (ds.indexOf("%") !== -1) {
				var pds = ds.split("%");
				if (!isNaN(pds[0])) {
					item_discount = parseFloat((((unit_price) * parseFloat(pds[0])) / 100), 4);
				} else {
					item_discount = parseFloat(ds / item_qty);
				}
			} else {
				item_discount = parseFloat(ds / item_qty);
			}
			
			unit_price = unit_price - item_discount;
			if(isNaN(item_discount)){
				item_discount = 0;
			}
			
            product_discount += parseFloat(item_discount * item_qty);
            var pr_tax = item.tax_rate;
            var pr_tax_val = 0, pr_tax_rate = 0;
            
			if (site.settings.tax1 == 1) {
				if (pr_tax !== false) {
					if (pr_tax.type == 1) {

						if (item_tax_method == '0') {
							pr_tax_val = (((unit_price) * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate)));
							pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
						} else {
							pr_tax_val = ((unit_price * parseFloat(pr_tax.rate)) / 100);
							pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
						}

					} else if (pr_tax.type == 2) {

						pr_tax_val = parseFloat(pr_tax.rate);
						pr_tax_rate = pr_tax.rate;

					}
					product_tax += pr_tax_val * item_qty;
				}
			}
			
			//alert(unit_price);
			
            item_price = item_tax_method == 0 ? formatDecimal(real_unit_price - pr_tax_val, 4) : formatDecimal(unit_price, 4);
			
            if (item_tax_method == 0) {
                unit_price = (real_unit_price);
            
            }else{
                unit_price = (unit_price+item_discount);                
            }
			
			
			//alert(real_unit_price);
            var sel_opt = '';
            $.each(item.options, function () {
                if(this.id == item_option) {
                    sel_opt = this.name;
                }
            });
			
            var subtotal =(item_tax_method == 0?formatMoney(((formatDecimal(real_unit_price) * parseFloat(item_qty))) - (item_discount*parseFloat(item_qty))):formatMoney(((parseFloat(unit_price) * parseFloat(item_qty))) - (item_discount*parseFloat(item_qty)) + parseFloat(pr_tax_val)* parseFloat(item_qty)));
          
			if (item_promotion == 1 && (current_date >= start_date && current_date <= end_date)){
				total += ((((parseFloat(real_unit_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) - item_discount);
			}else{
				total += (item_tax_method == 0?(((parseFloat(real_unit_price) * parseFloat(item_qty))) - (item_discount*parseFloat(item_qty))):(((parseFloat(unit_price) * parseFloat(item_qty))) - (item_discount*parseFloat(item_qty)) + parseFloat(pr_tax_val)* parseFloat(item_qty)));
			}
			
		    var row_no = (new Date).getTime();
            var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');

            tr_html ='<td class="text-center"><span class="text-center">#'+no+'</span><input type="hidden" class="count" value="' + item_id + '"></td>';
            tr_html += '<input name="price_id[]" type="hidden" id="price_id_' + row_no + '" value="' + price_id + '">';
			if(site.settings.show_code == 1 && site.settings.separate_code == 1) {
				tr_html+='<td class="text-left"><span class="text-left">'+ (digital_id?digital_code:item_code) +'</span></td>';
				tr_html += '<td><input name="digital_id[]" type="hidden" class="did" value="' + digital_id + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="piece[]" type="hidden" class="piece" value="' + piece + '"><input name="wpiece[]" type="hidden" class="wpiece" value="' + wpiece + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><input name="product_note[]" type="hidden" class="rnote" value="' + pn + '"><input name="product_group_id[]" type="hidden" class="product_group_id" value="' + item.row.price_id + '"><span class="sname" id="name_' + row_no + '">' + ((item_promotion == 1 && (current_date >= start_date && current_date <= end_date)) ? '<i class="fa fa-check-circle"></i> ' : '') + (digital_id?digital_name:item_name) +(sel_opt != '' ? ' ('+sel_opt+')' : '') + (pn != '' ? ' [<span id="get_not">' + pn + '</span>]' : '') + '</span> <i class="pull-right fa fa-edit tip pointer edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
			}
			if(site.settings.show_code == 1 && site.settings.separate_code == 0) {
				tr_html += '<td><input name="digital_id[]" type="hidden" class="did" value="' + digital_id + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="piece[]" type="hidden" class="piece" value="' + piece + '"><input name="wpiece[]" type="hidden" class="wpiece" value="' + wpiece + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><input name="product_note[]" type="hidden" class="rnote" value="' + pn + '"><input name="product_group_id[]" type="hidden" class="product_group_id" value="' + item.row.price_id + '"><span class="sname" id="name_' + row_no + '">' + ((item_promotion == 1 && (current_date >= start_date && current_date <= end_date)) ? '<i class="fa fa-check-circle"></i> ' : '') + (digital_id?digital_name:item_name) + ' (' + (digital_id?digital_code:item_code) + ')'+(sel_opt != '' ? ' ('+sel_opt+')' : '') + (pn != '' ? ' [<span id="get_not">' + pn + '</span>]' : '') + '</span> <i class="pull-right fa fa-edit tip pointer edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
			}
			if(site.settings.show_code == 0) {
				tr_html += '<td><input name="digital_id[]" type="hidden" class="did" value="' + digital_id + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="piece[]" type="hidden" class="piece" value="' + piece + '"><input name="wpiece[]" type="hidden" class="wpiece" value="' + wpiece + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><input name="product_note[]" type="hidden" class="rnote" value="' + pn + '"><input name="product_group_id[]" type="hidden" class="product_group_id" value="' + item.row.price_id + '"><span class="sname" id="name_' + row_no + '">' + ((item_promotion == 1 && (current_date >= start_date && current_date <= end_date)) ? '<i class="fa fa-check-circle"></i> ' : '') + (digital_id?digital_name:item_name) +(sel_opt != '' ? ' ('+sel_opt+')' : '') + (pn != '' ? ' [<span id="get_not">' + pn + '</span>]' : '') + '</span> <i class="pull-right fa fa-edit tip pointer edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
			}
			
			tr_html += '<td class="text-right"><input class="form-control input-sm text-right rprice" name="net_price[]" type="hidden" id="price_' + row_no + '" value="' + formatDecimal(item_price) + '"><input class="ruprice" name="unit_price[]" type="hidden" value="' + unit_price + '"><input class="realuprice" name="real_unit_price[]" type="hidden" value="' + (unit_price) + '"><span class="text-right sprice" id="sprice_' + row_no + '">' + formatMoney(unit_price) + '</span></td>';
			
            tr_html += '<td><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + formatDecimal(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
			tr_html += '<input class="default_price" name="default_price[]" type="hidden" value="' + default_price + '">';
			tr_html += '<input class="curr_rate" name="curr_rate[]" type="hidden" value="' + currRate + '">';

            if(item_qoh == null) {item_qoh = 0.00;} else {item_qoh = item_qoh;}
            tr_html += '<td class="text-right" class="qoh_by_item"><input class="form-control input-sm text-right rprice" name="qoh[]" type="hidden">'+formatDecimal(item_qoh)+'</td>';
			
            if (site.settings.product_discount == 1) {
                tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item_ds + '"><span class="text-right sdiscount text-danger" id="sdiscount_' + row_no + '">' + formatMoney(item_discount * item_qty ) + '</span></td>';
            }
            if (site.settings.tax1 == 1) {
                tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item_qty) + '</span></td>';
            }
            tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney(subtotal) + '</span></td>';
            tr_html += '<td class="text-center"><i class="fa fa-times tip pointer qudel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
            newTr.html(tr_html);
            newTr.prependTo("#quTable");
			
            count += parseFloat(item_qty);
            an++;
            if (item_type == 'standard' && item.options !== false) {
                $.each(item.options, function () {
					
                    if(this.id == item_option && item_qty > item_qoh) {
                    
					  $('#row_' + row_no).addClass('danger');
                        if(site.settings.overselling != 1) { $('#add_sale, #edit_sale').attr('disabled', true); }
                    }
                });
            } else if(item_type == 'standard' && item_qty > item_qoh) {
                $('#row_' + row_no).addClass('danger');
            } else if (item_type == 'combo') {
                if(combo_items === false) {
                    $('#row_' + row_no).addClass('danger');
                } else {
                    $.each(combo_items, function() {
                       if(parseFloat(this.quantity) < (parseFloat(this.qty)*item_qty) && this.type == 'standard') {
                           $('#row_' + row_no).addClass('danger');
                       }
                   });
                }
            }
			no++;

        });

        if (no == 1) {
            $('#qubiller').select2("readonly", false);
            $('#quwarehouse').select2("readonly", false);
        }

        var col = 3;
		if (site.settings.product_serial == 1) { col++; }
		if (site.settings.show_code == 1 && site.settings.separate_code == 1) { col++; }
		
        var tfoot = '<tr id="tfoot" class="tfoot active"><th colspan="'+col+'">Total</th><th class="text-center">' + formatNumber(parseFloat(count) - 1) + '</th><th></th>';
        if (site.settings.product_discount == 1) {
            tfoot += '<th class="text-right">'+formatMoney(product_discount)+'</th>';
        }
		
        if (site.settings.tax1 == 1) {
            tfoot += '<th class="text-right">'+formatMoney(product_tax)+'</th>';
        }

		tfoot += '<th class="text-right"><input type="hidden" name="total_balance" id="total_balance" class="total_balance" value="'+total+'" />'+formatMoney(total)+'</th><th class="text-center"><i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i></th></tr>';

        total = formatDecimal(total);
        $('#quTable tfoot').html(tfoot);

        // Order level discount calculations
		
        if (qudiscount = __getItem('qudiscount')) {
            var ds = qudiscount;
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    order_discount = parseFloat(((total) * parseFloat(pds[0])) / 100);
                } else {
                    order_discount = parseFloat((total * ds) / 100);
                }
            } else {
                order_discount = (parseFloat(total) * parseFloat(ds)) / 100;
            }
        }
		
		
        // Order level tax calculations
        if (site.settings.tax2 != 0) {
            if (qutax2 = __getItem('qutax2')) {
                $.each(tax_rates, function () {
                    if (this.id == qutax2) {
                        if (this.type == 2) {
                            invoice_tax = parseFloat(this.rate);
                        }
                        if (this.type == 1) {
                            invoice_tax = parseFloat((((total - order_discount) + shipping) * this.rate) / 100);
                        }
                    }
                });
            }
        }
		
        total_discount = parseFloat(order_discount + product_discount);
        // Totals calculations after item addition
        var gtotal = parseFloat(((total + invoice_tax) - order_discount) + shipping);
		
        $('#total').text(formatMoney(total));
        $('#titems').text((an - 1) + ' (' + formatNumber(parseFloat(count) - 1) + ')');
        $('#total_items').val((parseFloat(count) - 1));
        $('#tds').text(formatMoney(order_discount));
        if (site.settings.tax2 != 0) {
            $('#ttax2').text(formatMoney(invoice_tax));
        }
        $('#tship').text(formatMoney(shipping));
        $('#gtotal').text(formatMoney(gtotal));
        if (an > site.settings.bc_fix && site.settings.bc_fix != 0) {
            $("html, body").animate({scrollTop: $('#quTable').offset().top - 150}, 500);
            $(window).scrollTop($(window).scrollTop() + 1);
        }
		
        //audio_success.play();
    }
}

function multiCurrFormular(own_rate, setting_rate, amount){
	var result = 0;
	result = (amount/own_rate)*setting_rate;
	return result;
}


/* -----------------------------
 * Add Quotation Item Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */
 function add_invoice_item(item) {
    if (count == 1) {
        quitems = {};
        if ($('#quwarehouse').val() && $('#qucustomer').val()) {
            $('#qucustomer').select2("readonly", true);
            $('#qubiller').select2("readonly", true);
            $('#quwarehouse').select2("readonly", true);
        } else {
            bootbox.alert(lang.select_above);
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
		if (parseFloat(rid) === parseFloat(item.item_id)) {
			row = $(this).closest('tr');
			rounded = row.find('.count').val();
		}
	});
	var item_id = site.settings.item_addition == 1 ? rounded : item.id;
	if (quitems[item_id]) {
		quitems[item_id].row.qty = parseFloat(quitems[item_id].row.qty) + 1;
	} else {
		quitems[item_id] = item;
	}		
		__setItem('wid', item.warehouse_id);	
		__setItem('cid', item.customer_id);				
		__setItem('qid', item.row.id);
		__setItem('type', item.row.type);
	__setItem('quitems', JSON.stringify(quitems));
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
