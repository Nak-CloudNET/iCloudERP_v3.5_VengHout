$(document).ready(function () {
	
	// If there is any item in localStorage
	if (__getItem('pnitems')) {
		loadItems();
	}

    // clear localStorage and reload
    $('#reset').click(function (e) {
        bootbox.confirm(lang.r_u_sure, function (result) {
            if (result) {
                if (__getItem('pnitems')) {
                    __removeItem('pnitems');
                }
                if (__getItem('poref')) {
                    __removeItem('poref');
                }
				if (__getItem('slbiller')) {
                    __removeItem('slbiller');
                }
                if (__getItem('pnnote')) {
                    __removeItem('pnnote');
                }
                $('#modal-loading').show();
                location.reload();
            }
        });
	});

	$('#pnref').change(function (e) {
		__setItem('pnref', $(this).val());
	});
	
	if (pnref = __getItem('pnref')) {
		$('#pnref').val(pnref);
	}
	
	$('#plan').change(function (e) {
		__setItem('plan', $(this).val());
	});
	
	if (plan = __getItem('plan')) {
		$('#plan').val(plan);
	}
	
	$('#pnnote').redactor('destroy');
	$('#pnnote').redactor({
		buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'link', '|', 'html'],
		formattingTags: ['p', 'pre', 'h3', 'h4'],
		minHeight: 100,
		changeCallback: function (e) {
			var v = this.get();
			__setItem('pnnote', v);
		}
	});
	
	if (pnnote = __getItem('pnnote')) {
		$('#pnnote').redactor('set', pnnote);
	}
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

	$(document).on('click', '.podel', function () {
		var row = $(this).closest('tr'), item_id = row.attr('data-item-id');
		delete pnitems[item_id];
		row.remove();
		if(pnitems.hasOwnProperty(item_id)) { } else {
			__setItem('pnitems', JSON.stringify(pnitems));
			loadItems();
			return;
		}
    });
	
    /* -----------------------
     * Edit Row Modal Hanlder 
     ----------------------- */
    $(document).on('click', '.edit', function (){
        var row 			= $(this).closest('tr');
        var row_id 			= row.attr('id');
        item_id 			= row.attr('data-item-id');
        item 				= pnitems[item_id];
		var qty 			= row.children().children('.rquantity').val(); 
        var product_option 	= row.children().children('.roption').val();
		
		var opt = '<p style="margin: 12px 0 0 0;">n/a</p>';
		 
		if(site.settings.attributes == 1){ 
			if(item.options !== false) {
				opt = $("<select id=\"poption\" name=\"poption\" class=\"form-control select\" />");
				var o = 1;
				$.each(item.options, function () {
					if(o == 1) {
						if(product_option == '') { product_variant = this.id; } else { product_variant = product_option; }
					}
					$("<option />", {value: this.id, title: this.qty_unit,text: this.name}).appendTo(opt);
					o++;
				});
			} 
		}
		
		$('#poptions-div').html(opt);
		$('select.select').select2({minimumResultsForSearch: 6});
        $('#prModalLabel').text(item.row.name + ' (' + item.row.code + ')');
		$('#poption').select2('val', item.row.option);
		$('#pquantity').val(qty);
		$('#row_id').val(row_id);
        $('#prModal').appendTo("body").modal('show');

    });
	
	$(document.body).on("change", "#poption",function(){
		
       var qty_unit =  $('option:selected', this).attr('title'); 
	  // var net_cost = $('#     table').find('#net_cost').text();  
       	  
	   var net_cost = item.row.net_cost; 
	   $("#qty_unit").val(qty_unit);
       var total_net_cost='';
       total_net_cost = net_cost *qty_unit; 
	   $('#pcost').val(total_net_cost);
       $('#net_cost').text(total_net_cost);	   
    });
	
    $(document).on('change', '#pquantity', function () {
		var row 		= $('#' + $('#row_id').val());
        var item_id 	= row.attr('data-item-id');
		var qty 		= parseFloat($('#pquantity').val());
        var item 		= pnitems[item_id];

    });
	
    /* -----------------------
     * Edit Row Method 
     ----------------------- */
    $(document).on('click', '#editItem', function () {
        var row 	= $('#' + $('#row_id').val());
        var item_id = row.attr('data-item-id');
		pnitems[item_id].row.qty 		= parseFloat($('#pquantity').val());
        pnitems[item_id].row.option 	= $('#poption').val(),
        __setItem('pnitems', JSON.stringify(pnitems));
        $('#prModal').modal('hide');
        loadItems();
        return;
    });
	
    /* --------------------------
     * Edit Row Quantity Method 
     -------------------------- */
    var old_row_qty;
    var old_row_rqty;
    $(document).on("focus", '.rquantity', function () {
		var tr = $(this).closest('tr');
        old_row_qty = tr.find('.rquantity').val();
		
    }).on("change", '.rquantity', function () {
        var row 	= $(this).closest('tr');
		item_id 	= row.attr('data-item-id');
		var qoh 	= row.find('.qoh').val();
		var new_qty = $(this).val();
		
        if (!is_numeric($('.rquantity').val())) {
            row.find('.rquantity').val(old_row_qty);
            bootbox.alert(lang.unexpected_value);
            return;
        }
		
        pnitems[item_id].row.qty 		= new_qty;
        __setItem('pnitems', JSON.stringify(pnitems));
        loadItems();
    });
	
	$('.rquantity').bind('keypress', function (e) {
		if (e.keyCode == 13) {
			e.preventDefault();
			$("#add_item").focus();
		}
	});
	
	/* --------------------------
	 * Stock In Hand > Input Stock
	 -------------------------- */
	$(document).on("click", "#add_quote", function(){
		var qoh = 0;
		$(".qoh").each(function() {
			if($(this).val() <= 0){
				qoh = 1;
			} else {
				qoh = 0;
			}
		});
		if(qoh == 1){
			bootbox.alert(lang.qoh_small);
			return false;
		}
	});
	
});
/* -----------------------
 * Misc Actions
 ----------------------- */

function loadItems() {
    if (__getItem('pnitems')) {
        total 	= 0;
        count 	= 1;
        an 		= 1;
        $("#pnTable tbody").empty();
        pnitems = JSON.parse(__getItem('pnitems'));
		console.log(pnitems);
		var no_ = 1;
		$('#pnbiller').select2("readonly", true);
		$('#plan').select2("readonly", true);
        $.each(pnitems, function () {
            var item 	     = this;
            var item_id 	 = site.settings.item_addition == 1 ? item.item_id : item.id;
            pnitems[item_id] = item;

            var product_id 	 = item.row.id,
			    item_type 	 = item.row.type,
			    combo_items  = item.combo_items,
			    item_qty 	 = (item.row.type == 'service' ? 1 : item.row.qty),
			    item_option  = item.row.option,
			    item_code 	 = item.row.code,
			    item_name 	 = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
            var sel_opt      = '';
            var option_qty_unit = '';
            
            $.each(item.options, function () {
                if(this.id == item_option) {
                    sel_opt = this.name;
                    option_qty_unit = this.qty_unit;
                }
            });
			
			var stock_in_hand = formatPurDecimal(item.row.quantity);
			if(isNaN(stock_in_hand)){
				stock_in_hand = 0;
			}
			
            var row_no = (new Date).getTime();
            var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');
			
			tr_html = '<td class="text-right"><span class="text-center">#'+ no_ +'</td>';
			
            tr_html += '<td><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')'+(sel_opt != '' ? ' ('+sel_opt+')' : '')+'</span> <i class="pull-right fa fa-edit tip edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
			
			/* Stock Received */
			tr_html += '<td><input type="hidden" value=""/><input type="text" value="' + formatPurDecimal(item_qty) + '" class="form-control rquantity" name="quantity[]" /><input type="hidden" class="opt_qty" /></td>';
			
            tr_html += '<td class="text-center"><i class="fa fa-times tip podel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
			
            newTr.html(tr_html);
            newTr.appendTo("#pnTable");
			
			total += (parseFloat(item_qty));

            count += parseFloat(item_qty);
			
            an++;
			no_++;
        });
		
    }
}

/* -----------------------------
 * Add Purchase Iten Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */
function add_project_plan_item(item) {
	
    if (count == 1) {
        pnitems = {};
			
		if ($('#plan').val()) {
			$('#plan').attr("readonly", true);
		} else {
			
			bootbox.alert("Please select biller.");
			item = null;
			return;
			
		}
		
    }
    if (item == null) {
        return;
    }
	
    var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
    if (pnitems[item_id]) {
        pnitems[item_id].row.qty = parseFloat(pnitems[item_id].row.qty) + 1;
    } else {
        pnitems[item_id] = item;
    }
	
    __setItem('pnitems', JSON.stringify(pnitems));
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


