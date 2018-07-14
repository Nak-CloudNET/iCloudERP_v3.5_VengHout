$(document).ready(function () {

// Order level shipping and discoutn localStorage 
$('#tostatus').change(function (e) {
    __setItem('tostatus', $(this).val());
});
if (tostatus = __getItem('tostatus')) {
    $('#tostatus').select2("val", tostatus);
    if(tostatus == 'completed') {
        $('#tostatus').select2("readonly", true);
    }
}
var old_shipping;
$('#toshipping').focus(function () {
    old_shipping = $(this).val();
}).change(function () {
    if (!is_numeric($(this).val())) {
        $(this).val(old_shipping);
        bootbox.alert(lang.unexpected_value);
        return;
    } else {
        shipping = $(this).val() ? parseFloat($(this).val()) : '0';
    }
    __setItem('toshipping', shipping);
    var gtotal = total  + shipping;
    $('#gtotal').text(formatMoney(gtotal));
    $('#tship').text(formatMoney(shipping));
});
if (toshipping = __getItem('toshipping')) {
    shipping = parseFloat(toshipping);
    $('#toshipping').val(shipping);
}
//localStorage.clear();
// If there is any item in localStorage
if (__getItem('toitems')) {
    loadItems();
}

    // clear localStorage and reload
    $('#reset').click(function (e) {
        bootbox.confirm(lang.r_u_sure, function (result) {
            if (result) {
                if (__getItem('toitems')) {
                    __removeItem('toitems');
                }
                if (__getItem('toshipping')) {
                    __removeItem('toshipping');
                }
                if (__getItem('toref')) {
                    __removeItem('toref');
                }
                if (__getItem('to_warehouse')) {
                    __removeItem('to_warehouse');
                }
                if (__getItem('tonote')) {
                    __removeItem('tonote');
                }
                if (__getItem('from_warehouse')) {
                    __removeItem('from_warehouse');
                }
                if (__getItem('todate')) {
                    __removeItem('todate');
                }
                if (__getItem('tostatus')) {
                    __removeItem('tostatus');
                }
                /*total = 0; count = 0;product_tax = 0; invoice_tax = 0; total_discount = 0;
                 $('#posupplier').select2('readonly', false);
                 $('#pocurrency').select2('readonly', false);
                 $('#ponote').redactor('set', '');
                 $('#toTable tbody').empty();
                 $('#total').text(formatMoney(total));
                 $('#titems').text(0);
                 $('#tds').text(formatMoney(0));
                 if (site.settings.tax1) {
                 $('#ttax1').text(formatMoney(0));
                 }
                 if (site.settings.tax2 != 0) {
                 $('#ttax2').text(formatMoney(0));
                 }
                 $('#gtotal').text(formatMoney(0));
                 $(this).parent("form").trigger('reset');*/
                 $('#modal-loading').show();
                 location.reload();
             }
         });
});

// save and load the fields in and/or from localStorage

$('#toref').change(function (e) {
    __setItem('toref', $(this).val());
});
if (toref = __getItem('toref')) {
    $('#toref').val(toref);
}
$('#to_warehouse').change(function (e) {
    __setItem('to_warehouse', $(this).val());
});
if (to_warehouse = __getItem('to_warehouse')) {
    $('#to_warehouse').select2("val", to_warehouse);
}
$('#from_warehouse').change(function (e) {
    __setItem('from_warehouse', $(this).val());
});
if (from_warehouse = __getItem('from_warehouse')) {
    $('#from_warehouse').select2("val", from_warehouse);
    if (count > 1) {
        $('#from_warehouse').select2("readonly", true);
    }
}

    //$(document).on('change', '#tonote', function (e) {
        $('#tonote').redactor('destroy');
        $('#tonote').redactor({
            buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'link', '|', 'html'],
            formattingTags: ['p', 'pre', 'h3', 'h4'],
            minHeight: 100,
            changeCallback: function (e) {
                var v = this.get();
                __setItem('tonote', v);
            }
        });
        if (tonote = __getItem('tonote')) {
            $('#tonote').redactor('set', tonote);
        }

        $(document).on('change', '.rexpiry', function () { 
            var item_id = $(this).closest('tr').attr('data-item-id');
            toitems[item_id].row.expiry = $(this).val();
            __setItem('toitems', JSON.stringify(toitems));
        });


// prevent default action upon enter
$('body').bind('keypress', function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        return false;
    }
});


    /* ---------------------- 
     * Delete Row Method 
     * ---------------------- */
    /*$(document).on('click', '.podel', function () {
     var row = $(this).closest('tr'), item_id = row.attr('data-item-id');
     delete poitems[item_id];
     row.remove();
     if(poitems.hasOwnProperty(item_id)) { } else {
     __setItem('poitems', JSON.stringify(poitems));
     loadItems();
     return;
     }
 });*/

$(document).on('click', '.todel', function () {
    var row = $(this).closest('tr');
    var item_id = row.attr('data-item-id');
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

    var gtotal = (total + product_tax) + shipping;
    $('#total').text(formatMoney(total));
    $('#titems').text(count - 1);
    $('#ttax1').text(formatMoney(product_tax));
    $('#gtotal').text(formatMoney(gtotal));
    if (count == 1) {
        $('#from_warehouse').select2('readonly', false);
    }
        //console.log(poitems[item_id].row.name + ' is being removed.');
        delete toitems[item_id];
        __setItem('toitems', JSON.stringify(toitems));
        row.remove();

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
        toitems[item_id].row.qty = new_qty;
        __setItem('toitems', JSON.stringify(toitems));
        loadItems();
    });
    
    /* --------------------------
     * Edit Row Cost Method 
     -------------------------- */
     var old_cost;
     $(document).on("focus", '.rcost', function () {
        old_cost = $(this).val();
    }).on("change", '.rcost', function () {
        var row = $(this).closest('tr');
        if (!is_numeric($(this).val())) {
            $(this).val(old_cost);
            bootbox.alert(lang.unexpected_value);
            return;
        }
        var new_cost = parseFloat($(this).val()),
        item_id = row.attr('data-item-id');
        toitems[item_id].row.cost = new_cost;
        __setItem('toitems', JSON.stringify(toitems));
        loadItems();
    });
    
    $(document).on("click", '#removeReadonly', function () { 
     $('#from_warehouse').select2('readonly', false); 
     return false;
 });
    
    
});

/* -----------------------
 * Edit Row Modal Hanlder 
 ----------------------- */
 $(document).on('click', '.edit', function () {
    var row = $(this).closest('tr');
    var row_id = row.attr('id');
    item_id = row.attr('data-item-id');
    item = toitems[item_id];
    var qty = row.children().children('.rquantity').val(), 
    product_option = row.children().children('.roption').val(),
    cost = row.children().children('.rcost').val();
    $('#prModalLabel').text(item.row.name + ' (' + item.row.code + ')');
    if (site.settings.tax1) {
        var tax = item.tax_rate != 0 ? item.tax_rate.name + ' (' + item.tax_rate.rate + ')' : 'N/A';
        $('#ptax').text(tax);
        $('#old_tax').val($('#sproduct_tax_' + row_id).text());
    }
	var poption_select = "poption";
    var opt = '<p style="margin: 12px 0 0 0;">n/a</p>';
    if(item.options !== false) {
        var o = 1;
        opt = $("<select id=\"poption\" name=\"poption\" class=\"form-control select\" />");
        $.each(item.options, function () {
            if(o == 1) {
                if(product_option == '') { product_variant = this.id; } else { product_variant = product_option; }
            }
			
			if(this.total_qty == null){
				this.total_qty = 0;
				this.total_qty = this.each_cost;
			}
            $("<option />", {value: this.id, text: this.name, id: this.total_qty}).appendTo(opt);
            o++;
        });
    } 
    $('#poptions-div').html(opt);
    $('select.select').select2({minimumResultsForSearch: 6});
    $('#pquantity').val(qty);
    $('#old_qty').val(qty);
    $('#pprice').val(cost);
    $('#poption').select2('val', item.row.option);
	$("#poption").select2().on('change', function(e){
			var s = $('#poption option:selected').attr('id');
			$("#pprice").val(s);
			$("#s_unit_cost").html($("#pprice").val());
	});
    
	$('#old_price').val(cost);
    $('#row_id').val(row_id);
    $('#item_id').val(item_id);
    $('#pserial').val(row.children().children('.rserial').val());
    $('#pproduct_tax').select2('val', row.children().children('.rproduct_tax').val());
    $('#pdiscount').val(row.children().children('.rdiscount').val());
    $('#prModal').appendTo("body").modal('show');

});


	

$('#prModal').on('shown.bs.modal', function (e) {
    if($('#poption').select2('val') != '') {
        $('#poption').select2('val', product_variant);
        product_variant = 0;
    }
});

/* -----------------------
 * Edit Row Method 
 ----------------------- */
 $(document).on('click', '#editItem', function () {
    var row = $('#' + $('#row_id').val());
    var item_id = row.attr('data-item-id'), new_pr_tax = $('#pproduct_tax').val(), new_pr_tax_rate;
    if (new_pr_tax) {
        $.each(tax_rates, function () {
            if (this.id == new_pr_tax) {
                new_pr_tax_rate = this;
            }
        });
    } else {
        new_pr_tax_rate = false;
    }
    
    toitems[item_id].row.qty = parseFloat($('#pquantity').val()),
    toitems[item_id].row.cost = parseFloat($('#pprice').val()),
    toitems[item_id].row.tax_rate = new_pr_tax_rate,
    toitems[item_id].row.discount = $('#pdiscount').val(),
    toitems[item_id].row.option = $('#poption').val(),
    toitems[item_id].row.tax_method = 1;
    //toitems[item_id].row.serial = $('#pserial').val();
    __setItem('toitems', JSON.stringify(toitems));
    $('#prModal').modal('hide');
    
    loadItems();
    return;
});

/* -----------------------
 * Misc Actions
 ----------------------- */

 function loadItems() {

    if (__getItem('toitems')) {
        total = 0;
        count = 1;
        an = 1;
        product_tax = 0;
        $("#toTable tbody").empty();
        $('#add_transfer, #edit_transfer').attr('disabled', false);
        toitems = JSON.parse(__getItem('toitems'));
        $.each(toitems, function () {
            var item = this;
            var item_id = item.id;

            var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
            toitems[item_id] = item;

            var from_warehouse = __getItem('from_warehouse'), check = false;
            var product_id = item.row.id, item_type = item.row.type, combo_items = item.combo_items, item_cost = item.row.cost, item_qty = item.row.qty, item_bqty = item.row.quantity_balance, item_expiry = item.row.expiry, item_aqty = item.row.quantity, item_tax_method = item.row.tax_method, item_ds = item.row.discount, item_discount = 0, item_option = item.row.option, item_code = item.row.code, item_serial = item.row.serial, item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;"), picture = item.row.image, description = item.row.product_details;
            
            var unit_cost = item.row.real_unit_cost;

            var pr_tax = item.tax_rate;
            var pr_tax_val = 0, pr_tax_rate = 0;
            if (site.settings.tax1 == 1) {
                if (pr_tax !== false) {
                    if (pr_tax.type == 1) {

                        if (item_tax_method == '0') {
                            pr_tax_val = formatDecimal(((unit_cost) * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate)));
                            pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
                        } else {
                            pr_tax_val = formatDecimal(((unit_cost) * parseFloat(pr_tax.rate)) / 100);
                            pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
                        }

                    } else if (pr_tax.type == 2) {

                        pr_tax_val = parseFloat(pr_tax.rate);
                        pr_tax_rate = pr_tax.rate;

                    }
                    product_tax += pr_tax_val * item_qty;
                }
            }
            item_cost = item_tax_method == 0 ? formatDecimal(unit_cost-pr_tax_val) : formatDecimal(unit_cost);
            unit_cost = formatDecimal(unit_cost+item_discount);
            var sel_opt = '';
            $.each(item.options, function () {
                if(this.id == item_option) {
					
                    sel_opt = this.name;
					item_cost = this.cost;
                }
            });

            var row_no = (new Date).getTime();
            var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');
			tr_html = '<td><center><ul class="enlarge"><li><img src="'+site.base_url+'assets/uploads/thumbs/' + picture + '" alt="' + picture + '" style="width:30px; height:30px;" class="img-circle" /><span><a href="'+site.base_url+'assets/uploads/' + picture + '" data-toggle="lightbox"><img src="'+site.base_url+'assets/uploads/' + picture + '" alt="' + picture + '" style="width:200px;" class="img-thumbnail" /></a></span></li></ul></center></td>';
            tr_html += '<td><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')'+(sel_opt != '' ? ' ('+sel_opt+')' : '')+'</span> <i class="pull-right fa fa-edit tip tointer edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
            if (site.settings.product_expiry == 1) {
                tr_html += '<td><input class="form-control date rexpiry" name="expiry[]" type="text" value="' + item_expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
            }
            tr_html += '<td class="text-right" style="display:none;"><input class="form-control input-sm text-right rcost" name="net_cost[]" type="hidden" id="cost_' + row_no + '" value="' + formatDecimal(item_cost) + '"><input class="rucost" name="unit_cost[]" type="hidden" value="' + unit_cost + '"><input class="realucost" name="real_unit_cost[]" type="hidden" value="' + item.row.real_unit_cost + '"><span class="text-right scost" id="scost_' + row_no + '">' + formatMoney(item_cost) + '</span></td>';
            tr_html += '<td><input name="quantity_balance[]" type="hidden" class="rbqty" value="' + formatDecimal(item_bqty) + '"><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + formatDecimal(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
            if (site.settings.tax1 == 1) {
                //tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item_qty) + '</span></td>';
            }
			tr_html += '<td>'+description+'</td>';
            //tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney(((parseFloat(item_cost) - item_discount + parseFloat(pr_tax_val)) * parseFloat(item_qty))) + '</span></td>';
            tr_html += '<td class="text-center"><i class="fa fa-times tip todel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
            newTr.html(tr_html);
            newTr.prependTo("#toTable");
            //total += parseFloat(item_cost * item_qty);
            total += formatDecimal(((parseFloat(item_cost) + parseFloat(pr_tax_val)) * parseFloat(item_qty)));
            count += parseFloat(item_qty);
            an++;
            if (item.options !== false) {
				
                $.each(item.options, function () {
                    if(this.id == item_option && item_qty > this.quantity) {
                        $('#row_' + row_no).addClass('danger');
                        // if(site.settings.overselling != 1) { 
                            $('#add_transfer, #edit_transfer').attr('disabled', true); 
                        // }
                    }
                });
            } else if(item_qty > item_aqty) { 
                $('#row_' + row_no).addClass('danger');
                // if(site.settings.overselling != 1) { 
                    $('#add_transfer, #edit_transfer').attr('disabled', true); 
                // }
            }
            
        });

        var col = 2;
        if (site.settings.product_expiry == 1) { col++; }
        var tfoot = '<tr id="tfoot" class="tfoot active"><th colspan="'+col+'">Total</th><th class="text-center">' + formatNumber(parseFloat(count) - 1) + '</th>';
        tfoot += '<th class="text-center">Description</th>';
		tfoot += '<th class="text-center"><i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i></th></tr>';
        $('#toTable tfoot').html(tfoot);

        // Totals calculations after item addition
        var gtotal = total + shipping;
        $('#total').text(formatMoney(total));
        $('#titems').text((an-1)+' ('+(parseFloat(count)-1)+')');
        if (site.settings.tax1) {
            $('#ttax1').text(formatMoney(product_tax));
        }
        $('#gtotal').text(formatMoney(gtotal));
        if (an > site.settings.bc_fix && site.settings.bc_fix != 0) {
            $("html, body").animate({scrollTop: $('#toTable').offset().top - 150}, 500);
            $(window).scrollTop($(window).scrollTop() + 1);
        }
        //audio_success.play();
    }
}

/* -----------------------------
 * Add Purchase Iten Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */
 function add_transfer_item(item) {

    if (count == 1) {
        toitems = {};
        if ($('#from_warehouse').val()) {
            $('#from_warehouse').select2("readonly", true);
        } else {
            bootbox.alert(lang.select_above);
            item = null;
            return;
        }
    }
    if (item == null) {
        return;
    }

    var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
    if (toitems[item_id]) {
        toitems[item_id].row.qty = parseFloat(toitems[item_id].row.qty) + 1;
    } else {
        toitems[item_id] = item;
    }

    __setItem('toitems', JSON.stringify(toitems));
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