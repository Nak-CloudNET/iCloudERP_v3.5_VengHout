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
    $('#biller_id').change(function (e) {
        __setItem('biller_id', $(this).val());
    });

    if (biller_id = __getItem('biller_id')) {
        $('#biller_id').select2("val", biller_id);
        if (count > 1) {
            $('#biller_id').select2("readonly", true);
        }
    }

    if (to_warehouse = __getItem('to_warehouse')) {
        $('#to_warehouse').select2("val", to_warehouse);
        if (count > 1) {
            $('#to_warehouse').select2("readonly", true);
        }
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

    /* ----------------------
     * Expiry Row Method
     * ---------------------- */
    $(document).on('change', '.rexpiry', function () {
        var row     = $(this).closest('tr');
        item_id 	= row.attr('data-item-id');
        var qty_exp	= row.find('#exp').find('option:selected').attr('qty');
        var qty		= row.find('.rquantity').val();
        var qty_opt	= row.find('.poption').find('option:selected').attr('qty_by_unit');
        var real_qoh= row.find('.real_qoh').val();

        toitems[item_id].row.expiry = $(this).val();
        toitems[item_id].row.qty    = qty_exp;
        __setItem('toitems', JSON.stringify(toitems));
        loadItems();
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
            $('#biller_id').select2('readonly', false);
            $('#from_warehouse').select2('readonly', false);
            $('#to_warehouse').select2('readonly', false);
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
        var row 	= $(this).closest('tr');
        if (!is_numeric($(this).val())) {
            $(this).val(old_row_qty);
            bootbox.alert(lang.unexpected_value);
            return;
        }

        var rqty   	= $(this).val()-0;
        var qohb   	= row.find(".real_qoh").val()-0;
        var qold   	= row.find(".old_qty").val()-0;

        if(rqty > (qohb+qold))
        {
            bootbox.alert(lang.unexpected_value);
            $(this).val(qold);
        }else{
            var new_qty = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');
            toitems[item_id].row.qty = new_qty;
        }

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
        $('#to_warehouse').select2('readonly', false);
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
        cost = row.children().children('.rcost').val(),
        qoh_real = row.children().children('.real_qoh').val();
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
        opt = $("<select id=\"poption\" name=\"poption\" class=\"form-control select poption\" />");
        var r_cost = 0 ;
        $.each(item.options, function () {
            if(o == 1) {
                if(product_option == '') { product_variant = this.id; } else { product_variant = product_option; }
            }

            if(this.total_qty == null){
                this.total_qty = 0;
                this.total_qty = this.each_cost;
            }

            if((this.cost)==0){
                r_cost = (r_cost+(this.cost));
            }else{
                r_cost = (this.cost);
            }

            $("<option />", {value: this.id,text: this.name, qty_by_unit: this.qty_unit ,cost : (r_cost) }).appendTo(opt);
            o++;
        });
    }
    $('#qoh').val(qoh_real);
    $('#poptions-div').html(opt);
    $('select.select').select2({minimumResultsForSearch: 6});
    $('#pquantity').val(qty);
    $('#old_qty').val(qty);
    $('#pprice').val(formatDecimal(cost));
    $('#poption').select2('val', item.row.option);
    $("#poption").select2().on('change', function(e){
        var s = $('#poption option:selected').attr('id');
        $("#pprice").val(s);
        $("#s_unit_cost").html($("#pprice").val());
    });

    $('#old_price').val(formatDecimal(cost));
    $('#row_id').val(row_id);
    $('#item_id').val(item_id);
    $('#pserial').val(row.children().children('.rserial').val());
    $('#pproduct_tax').select2('val', row.children().children('.rproduct_tax').val());
    $('#pdiscount').val(row.children().children('.rdiscount').val());
    $('#prModal').appendTo("body").modal('show');

});

$(document).on("change",".poption",function(e){
    var row 	= $(this).closest('tr');
    var item_id = row.attr('data-item-id');
    var qtyunit = $('option:selected',this).attr('qty_by_unit');
    var poption = $(this).val();
    var qoh_r   = row.find('.real_qoh').val();

    var qoh_onstock =  parseFloat(qoh_r);

    if(qtyunit){
        qtyunit = qtyunit;
    }else{
        qtyunit = 1;
    }

    toitems[item_id].unit_qty 		= qtyunit;
    toitems[item_id].QOHBYWH.qty 	= qoh_onstock;
    toitems[item_id].row.option 	= poption;
    __setItem('toitems', JSON.stringify(toitems));

    loadItems();

}).trigger('change');

$('#prModal').on('shown.bs.modal', function (e) {
    if($('#poption').select2('val') != '') {
        $('#poption').select2('val', product_variant);
        product_variant = 0;
    }
});

/* -----------------------
 * Check Transfer Qty
 ---------------------- */
$(document).on('click', '#add_transfer', function (){
    var qoh 	= new Array();
    var qty 	= new Array();
    var option 	= new Array();
    var expriy 	= new Array();
    $('.real_qoh').each(function(i){
        qoh[i] = $(this).val();
    });
    $('.poption').each(function(i){
        option[i] = $(this).find('option:selected').attr('qty_by_unit');
    });
    $('.rexpiry').each(function(i){
        expriy[i] = $(this).find('option:selected').attr('qty');
    });
    $('.rquantity').each(function(i){
        qty[i] = $(this).val() * option[i];
    });
    //================== Comparing Quantity ====================//
    qual = new Array();
    $.each(qoh, function(i,value){
        if(parseFloat(value) < parseFloat(qty[i])){
            qual[i] = 1;
        }else{
            qual[i] = 0;
        }
    });

    if($.inArray(1,qual) >= 0) {
        bootbox.alert(lang.invalidqty);
        return false;
    }

    if (site.settings.product_expiry == 1) {
        qual_exp = new Array();
        $.each(expriy, function(i,value){
            if(parseFloat(value) < parseFloat(qty[i])){
                qual_exp[i] = 1;
            }else{
                qual_exp[i] = 0;
            }
        });
        if($.inArray(1,qual_exp) >= 0) {
            bootbox.alert(lang.qty_expiry);
            return false;
        }
    }

    //========================= End ============================//
});

/* -----------------------
 * Edit Row Method
 ----------------------- */
$(document).on('click', '#editItem', function () {
    var row = $('#' + $('#row_id').val());
    var item_id = row.attr('data-item-id'), new_pr_tax = $('#pproduct_tax').val(), new_pr_tax_rate;
    var qtyunit = $('option:selected', $("#poption")).attr('qty_by_unit');
    var qoh_r   = row.find('.real_qoh').val();

    if (new_pr_tax) {
        $.each(tax_rates, function () {
            if (this.id == new_pr_tax) {
                new_pr_tax_rate = this;
            }
        });
    } else {
        new_pr_tax_rate = false;
    }

    var qoh_onstock =  parseFloat(qoh_r);

    if(qtyunit){
        qtyunit=qtyunit;
    }else{
        qtyunit=1;
    }

    toitems[item_id].unit_qty = qtyunit;
    toitems[item_id].row.real_unit_cost =  parseFloat($('#pprice').val());
    toitems[item_id].QOHBYWH.qty = qoh_onstock;
    toitems[item_id].row.qty = parseFloat($('#pquantity').val()),
        toitems[item_id].row.cost = parseFloat($('#pprice').val()),
        toitems[item_id].row.tax_rate = new_pr_tax_rate,
        toitems[item_id].row.discount = $('#pdiscount').val(),
        toitems[item_id].row.option = $('#poption').val(),
        toitems[item_id].row.tax_method = 1;
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
            var item 			= this;
            var item_id 		= site.settings.item_addition == 1 ? item.item_id : item.id;
            toitems[item_id] 	= item;

            var from_warehouse 	= __getItem('from_warehouse'), check = false;
            var product_id 		= item.row.id,
                item_type 		= item.row.type,
                combo_items 	= item.combo_items,
                item_cost 		= item.row.cost,
                item_qty 		= item.row.qty,
                item_bqty 		= item.row.quantity_balance,
                item_expiry 	= item.row.expiry,
                item_aqty 		= item.row.quantity,
                item_tax_method = item.row.tax_method,
                item_ds 		= item.row.discount,
                item_discount 	= 0,
                item_option 	= item.row.option,
                item_code 		= item.row.code,
                item_name 		= item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;"),
                item_serial 	= item.row.serial;

            var item_idd 		= item.item_idd;
            var unit_cost 		= item.row.real_unit_cost;

            var pr_tax 			= item.tax_rate;
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
                }
            });

            var exp_date = $("<select id=\"exp\" name=\"expiry\[\]\" style=\"padding-top: 2px !important;\" class=\"form-control rexpiry\" />");
            if (site.settings.product_expiry == 1) {
                if(item.expiry_date !== false && item.expiry_date !==undefined) {
                    $("<option />", {value: 0, text: lang.select_exp, qty: 1}).appendTo(exp_date);
                    $.each(item.expiry_date, function () {
                        if (item.row.expiry == this.expiry) {
                            $("<option />", {value: this.expiry, text: fsd(this.expiry), qty: this.quantity_balance, selected: 'selected'}).appendTo(exp_date);
                        } else {
                            $("<option />", {value: this.expiry, text: fsd(this.expiry), qty: this.quantity_balance}).appendTo(exp_date);
                        }
                    });

                } else {
                    $("<option />", {value: 0, text: 'n/a'}).appendTo(exp_date);
                    exp_date = exp_date.hide();
                }
            }

            var row_no = (new Date).getTime();
            var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');
            tr_html = '<td><input name="item_id[]" type="hidden" class="item_id" value="' + item_idd + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')'+'</span></td>';
            if (site.settings.product_expiry == 1) {
                tr_html += '<td>'+(exp_date.get(0).outerHTML)+'</td>';
            }

            tr_html += '<td class="text-right"><span class="text-right">' + formatDecimal(item.QOHBYWH.qty) + '</span></td>';

            tr_html += '<td><input name="real_qoh" type="hidden" class="real_qoh" value="'+formatDecimal(item.QOHBYWH.qty)+'"><input type="hidden" value="'+ item_bqty +'" class="old_qty" name="old_qty[]" /><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + formatDecimal(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';

            if(item.options !== false) {
                tr_html += '<td><select class="form-control poption">';
                $.each(item.options, function () {
                    tr_html += '<option value="'+ this.id +'" qty_by_unit="'+ this.qty_unit +'" cost="'+ this.cost +'" '+ ((item_option == this.id) ? 'selected':'') +'>'+ this.name +'</option>';

                });
                tr_html +=  '</select></td>';
            }else {
                tr_html += '<td><span style="padding-left:5%;">'+ item.row.pro_unit +'</span></td>';
            }

            tr_html += '<td class="text-center"><i class="fa fa-times tip todel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
            newTr.html(tr_html);
            newTr.prependTo("#toTable");

            total += formatDecimal(((parseFloat(item_cost) + parseFloat(pr_tax_val)) * parseFloat(item_qty)));
            count += parseFloat(item_qty);
            an++;
            if (item.options !== false) {
                $.each(item.options, function () {
                    if(this.id == item_option && item_qty > item.QOHBYWH.qty) {
                        $('#row_' + row_no).addClass('danger');
                    }
                });
            } else if(item_qty > item.QOHBYWH.qty) {
                $('#row_' + row_no).addClass('danger');
                if(site.settings.overselling != 1) {
                    //$('#add_transfer, #edit_transfer').attr('disabled', true);
                }
            }

        });

        var col = 2;
        if (site.settings.product_expiry == 1) { col++; }
        var tfoot = '<tr id="tfoot" class="tfoot active"><th colspan="'+col+'">Total</th><th class="text-center">' + formatNumber(parseFloat(count) - 1) + '</th>';

        tfoot += '<th></th>';

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
        if ($('#from_warehouse').val() && $('#to_warehouse').val()) {
            $('#from_warehouse').select2("readonly", true);
            $('#to_warehouse').select2("readonly", true);
            $('#biller_id').select2("readonly", true);
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