$(document).ready(function () {
    
	var $customer = $('#customer');
	$customer.change(function (e) {
        __setItem('customer', $(this).val());
        //$('#slcustomer_id').val($(this).val());
    });
	
    if (customer = __getItem('customer')) {
        //document.write(customer);
        if(customer !=0){
            $customer.val(customer).select2({
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
        }else {

        }

    }
	

	if (!__getItem('qaref')) {
        __setItem('qaref', '');
    }

    ItemnTotals();
    $('.bootbox').on('hidden.bs.modal', function (e) {
        $('#add_item').focus();
    });
    $('body a, body button').attr('tabindex', -1);
    //check_add_item_val();
    if (site.settings.set_focus != 1) {
        $('#add_item').focus();
    }

    //localStorage.clear();
    // If there is any item in localStorage
    if (__getItem('qaitems')) {
        loadItems();
    }

    // clear localStorage and reload
    $('#reset').click(function (e) {
        bootbox.confirm(lang.r_u_sure, function (result) {
            if (result) {
                if (__getItem('slitems')) {
                    __removeItem('qaitems');
                }
                if (__getItem('qaref')) {
                    __removeItem('qaref');
                }
                if (__getItem('qawarehouse')) {
                    __removeItem('qawarehouse');
                }
                if (__getItem('qanote')) {
                    __removeItem('qanote');
                }
                if (__getItem('qadate')) {
                    __removeItem('qadate');
                }				
                $('#modal-loading').show();
                location.reload();
            }
        });
    });

    // save and load the fields in and/or from localStorage
    $('#qaref').change(function (e) {
        __setItem('qaref', $(this).val());
    });
    if (qaref = __getItem('qaref')) {
        $('#qaref').val(qaref);
    }
    $('#qawarehouse').change(function (e) {
        __setItem('qawarehouse', $(this).val());
    });
    if (qawarehouse = __getItem('qawarehouse')) {
        $('#qawarehouse').select2("val", qawarehouse);
    }

	$('#qanote').redactor('destroy');
	$('#qanote').redactor({
		buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'link', '|', 'html'],
		formattingTags: ['p', 'pre', 'h3', 'h4'],
		minHeight: 100,
		changeCallback: function (e) {
			var v = this.get();
			__setItem('qanote', v);
		}
	});
    if (qanote = __getItem('qanote')) {
        $('#qanote').redactor('set', qanote);
    }
	//
	/*if (qanote = __getItem('qanote')) {
        $('#qanote').redactor('set', qanote);
        }*/

    // prevent default action upon enter
    $('body').bind('keypress', function (e) {
        if ($(e.target).hasClass('redactor_editor')) {
            return true;
        }
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });


    /* ---------------------- 
     * Delete Row Method 
     * ---------------------- */

    $(document).on('click', '.qadel', function () {
        var row = $(this).closest('tr');
        var item_id = row.attr('data-item-id');
        delete qaitems[item_id];
        row.remove();
        if(qaitems.hasOwnProperty(item_id)) { } else {
            __setItem('qaitems', JSON.stringify(qaitems));
            loadItems();

        }
    });

    /* --------------------------
     * Edit Row Quantity Method 
     -------------------------- */

    $(document).on("change", '.rquantity', function () {
        var row = $(this).closest('tr');
        if (!is_numeric($(this).val()) || parseFloat($(this).val()) < 0) {
            $(this).val(old_row_qty);
            bootbox.alert(lang.unexpected_value);
            return;
        }
		
        var new_qty = parseFloat($(this).val()),
        item_id 	= row.attr('data-item-id');
        qaitems[item_id].row.qty = new_qty;
        __setItem('qaitems', JSON.stringify(qaitems));
        loadItems();
    });

    $(document).on("change", '.rtype', function () {
        var row = $(this).closest('tr');
        var new_type = $(this).val(),
        item_id = row.attr('data-item-id');
        qaitems[item_id].row.type = new_type;
        __setItem('qaitems', JSON.stringify(qaitems));
    });
	$(document).on("change", '.rexpiry', function () {
        var row = $(this).closest('tr');
        var new_expiry = $(this).val(),
        item_id = row.attr('data-item-id');
        qaitems[item_id].row.expiry = new_expiry;
        __setItem('qaitems', JSON.stringify(qaitems));
    });
    $(document).on("change", '.rvariant', function () {
        var row = $(this).closest('tr');
        var new_opt = $(this).val(),
        item_id = row.attr('data-item-id');
        qaitems[item_id].row.option = new_opt;
        __setItem('qaitems', JSON.stringify(qaitems));
    });
        

});

/* -----------------------
 * Load Items to table
 ----------------------- */

function loadItems() {

    if (__getItem('qaitems')) {
        count = 1;
        an = 1;
        $("#qaTable tbody").empty();
        //qaitems = JSON.parse(__getItem('qaitems'));
        qaitems = JSON.parse(__getItem('qaitems'));
        $.each(qaitems, function () {
            var item = this;
            var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
            item.order = item.order ? item.order : new Date().getTime();
            var product_id = item.row.id,
                item_qty = item.row.qty,
                item_option = item.row.option,
                item_code = item.row.code,
                item_serial = item.row.serial,
                qoh = item.row.qoh,
                var_name = item.row.vname,
                item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");

            var type = item.row.type ? item.row.type : '';
            var itemid = item.item_ids;
			var item_expiry = item.row.expiry ? item.row.expiry : '';
            var opt = $("<select id=\"poption\" name=\"variant\[\]\" class=\"form-control select rvariant\" />");

            if(item.options !== false) {
                $.each(item.options, function () {
                    if (item.row.option == this.id)
                        $("<option />", {value: this.id, text: this.name, selected: 'selected'}).appendTo(opt);
                    else
                        $("<option />", {value: this.id, text: this.name}).appendTo(opt);
                });
            } else {
                $("<option />", {value: 0, text: 'n/a'}).appendTo(opt);
                opt = opt.hide();
            }

            var row_no = (new Date).getTime();
            var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');
            tr_html = '<td><input name="itemid[]" type="hidden"  value="' + itemid + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><span class="sname" id="name_' + row_no + '">' + item_code +' - ' + item_name +'</span></td>';
			
			if (site.settings.product_expiry == 1) {
                tr_html += '<td><input class="form-control date rexpiry" name="expiry[]" type="text" value="' + item_expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
            }
			
			tr_html += '<td>'+ (qoh == null ? formatQuantity(0):formatQuantity(qoh)) +'</td>';

            tr_html += '<td>' + (item.options !== false ? opt.get(0).outerHTML : var_name) + '</td>';
			
            tr_html += '<td><select name="type[]" class="form-contol select rtype" style="width:100%;"><option value="subtraction"'+(type == 'subtraction' ? ' selected' : '')+'>'+type_opt.subtraction+'</option><option value="addition"'+(type == 'addition' ? ' selected' : '')+'>'+type_opt.addition+'</option></select></td>';
            tr_html += '<td><input class="form-control text-center rquantity" tabindex="'+((site.settings.set_focus == 1) ? an : (an+1))+'" name="quantity[]" type="text" value="' + formatDecimal(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
            if (site.settings.product_serial == 1) {
                tr_html += '<td class="text-right"><input class="form-control input-sm rserial" name="serial[]" type="text" id="serial_' + row_no + '" value="'+item_serial+'"></td>';
            }
            tr_html += '<td class="text-center"><i class="fa fa-times tip qadel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
            count += parseFloat(item_qty);
            newTr.html(tr_html);
            newTr.prependTo("#qaTable");
            an++;
        });

        if (count > 1) {
            $('#slbiller').attr('readonly', 'true');
            $('#qawarehouse').attr('readonly', 'true');
        } else {
            $('#slbiller').removeAttr('readonly');
            $('#qawarehouse').removeAttr('readonly');
        }

        var col = 3;
		 if (site.settings.product_expiry == 1) { col++; }
        var tfoot = '<tr id="tfoot" class="tfoot active"><th colspan="'+col+'">Total</th><th></th><th class="text-center">' + formatNumber(parseFloat(count) - 1) + '</th>';
        if (site.settings.product_serial == 1) { tfoot += '<th></th>'; }
        tfoot += '<th class="text-center"><i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i></th></tr>';
        $('#qaTable tfoot').html(tfoot);
        $('select.select').select2({minimumResultsForSearch: 7});
        if (an > parseInt(site.settings.bc_fix) && parseInt(site.settings.bc_fix) > 0) {
            $("html, body").animate({scrollTop: $('#sticker').offset().top}, 500);
            $(window).scrollTop($(window).scrollTop() + 1);
        }
        //set_page_focus();
    }
}

/* -----------------------------
 * Add Purchase Item Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */
function add_adjustment_item(item) {
    if (count == 1) {
        qaitems = {};
    }
    if (item == null){
        return;
	}
    var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;

    if (qaitems[item_id]) {
        var new_qty = parseFloat(qaitems[item_id].row.qty) + 1;
        qaitems[item_id].row.base_quantity = new_qty;
        
        qaitems[item_id].row.qty = new_qty;
    } else {
        qaitems[item_id] = item;
    }

	__setItem('qaitems', JSON.stringify(qaitems));
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