$(document).ready(function(){
	if(slwarehouse = __getItem('slwarehouse')){
		$('#slwarehouse').select2("val", slwarehouse);
	}
	if(slcustomer = __getItem('slcustomer')){
		$('#slcustomer').select2("val", slcustomer);
	}
	$(".open-category").click(function () {
		$('#category-slider').toggle('slide', { direction: 'right' }, 700);
	});
	$(".open-subcategory").click(function () {
		$('#subcategory-slider').toggle('slide', { direction: 'right' }, 700);
	});
	$(".open-suspend").click(function () {
		$('input[type="checkbox"],[type="radio"]').not('.skip').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%' // optional
			});
		$('#suspend-slider').toggle('slide', { direction: 'right' }, 700);
	});	
	$(document).on('click', function(e){
		if (!$(e.target).is(".open-category, .cat-child") && !$(e.target).parents("#category-slider").size() && $('#category-slider').is(':visible')) {
			$('#category-slider').toggle('slide', { direction: 'right' }, 700);
		}
		if (!$(e.target).is(".open-subcategory, .cat-child") && !$(e.target).parents("#subcategory-slider").size() && $('#subcategory-slider').is(':visible')) {
			$('#subcategory-slider').toggle('slide', { direction: 'right' }, 700);
		}
		if (!$(e.target).is(".open-suspend, .cat-child") && !$(e.target).parents("#suspend-slider").size() && $('#suspend-slider').is(':visible')) {
			$('#suspend-slider').toggle('slide', { direction: 'right' }, 700);
		}
	});
	$('.po').popover({html: true, placement: 'right', trigger: 'click'}).popover();
	$('#inlineCalc').calculator({layout: ['_%+-CABS','_7_8_9_/','_4_5_6_*','_1_2_3_-','_0_._=_+'], showFormula:true});
	$('.calc').click(function(e) { e.stopPropagation();});
	$(document).on('click', '[data-toggle="ajax"]', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        $.get(href, function( data ) {
            $("#myModal").html(data).modal();
        });
    });
});
$(document).ready(function () {

	// Order level shipping and discoutn localStorage
	if (posdiscount = __getItem('posdiscount')) {
		$('#posdiscount').val(posdiscount);
	}
	
	if (posshipping = __getItem('posshipping')) {
		$('#posshipping').val(posshipping);
	}
	
	$(document).on('change', '#ppostax2', function () {
		__setItem('postax2', $(this).val());
		$('#postax2').val($(this).val());
	});
	
	if (postax2 = __getItem('postax2')) {
		$('#postax2').val(postax2);
	}
	
	$(document).on('blur', '#sale_note', function () {
		__setItem('posnote', $(this).val());
		$('#sale_note').val($(this).val());
	});
	
	if (posnote = __getItem('posnote')) {
		$('#sale_note').val(posnote);
	}

	$(document).on('blur', '#staffnote', function () {
		__setItem('staffnote', $(this).val());
		$('#staffnote').val($(this).val());
	});

	if (staffnote = __getItem('staffnote')) {
		$('#staffnote').val(staffnote);
	}

	$(document).on('blur', '#suspend_room', function () {
		__setItem('suspendroom', $(this).val());
		$('#suspend_room').val($(this).val());
	});

	if (suspendroom = __getItem('suspendroom')) {
		$('#suspend_room').val(suspendroom);
	}

	 /* ----------------------
	 * Order Discount Handler
	 * ---------------------- */
	 $("#ppdiscount").click(function(e) {
	 	e.preventDefault();
	 	var dval = $('#posdiscount').val() ? $('#posdiscount').val() : '0';
	 	$('#order_discount_input').val(dval);
	 	$('#dsModal').modal();
	 });
	 
	 $('#dsModal').on('shown.bs.modal', function() {
	 	$(this).find('#order_discount_input').select().focus();
	 	$('#order_discount_input').bind('keypress', function(e) {
	 		if (e.keyCode == 13) {
	 			e.preventDefault();
	 			var ds = $('#order_discount_input').val();
	 			if (is_valid_discount(ds)) {
	 				$('#posdiscount').val(ds);
	 				__removeItem('posdiscount');
	 				__setItem('posdiscount', ds);
	 				loadItems();
	 			} else {
	 				bootbox.alert(lang.unexpected_value);
	 			}
	 			$('#dsModal').modal('hide');
	 		}
	 	});
	 });
	 
	 $(document).on('click', '#updateOrderDiscount', function() {
	 	var ds = $('#order_discount_input').val() ? $('#order_discount_input').val() : '0';
	 	if (is_valid_discount(ds)) {
	 		$('#posdiscount').val(ds);
	 		__removeItem('posdiscount');
	 		__setItem('posdiscount', ds);
	 		loadItems();
	 	} else {
	 		bootbox.alert(lang.unexpected_value);
	 	}
	 	$('#dsModal').modal('hide');
	 });
	 
	 /* ----------------------
	 * Order Tax Handler
	 * ---------------------- */
	 
	 $("#pptax2").click(function(e) {
	 	e.preventDefault();
	 	var postax2 = __getItem('postax2');
	 	$('#order_tax_input').select2('val', postax2);
	 	$('#txModal').modal();
		
	 });
	 
	 $('#txModal').on('shown.bs.modal', function() {
	 	$(this).find('#order_tax_input').select2('focus');
	 });
	 
	 $('#txModal').on('hidden.bs.modal', function() {
	 	var ts = $('#order_tax_input').val();
	 	$('#postax2').val(ts);
	 	__setItem('postax2', ts);
	 	loadItems();
	 });
	 
	 $(document).on('click', '#updateOrderTax', function () {
	 	var ts = $('#order_tax_input').val();
	 	$('#postax2').val(ts);
	 	__setItem('postax2', ts);
		
	 	loadItems();
	 	$('#txModal').modal('hide');
	 });
	 
	 $("#edit_shipping").click(function(e) {
	 	e.preventDefault();
		var shipping = __getItem('posshipping') ? __getItem('posshipping') : '0';
		$('#shipping').val(shipping);
	 	$('#shipping_modal').modal();
	 });
	 
	 $(document).on('click', '#add_shipping', function () {
	 	var shipping = $('#shipping').val();
	 	$('#posshipping').val(shipping);
	 	__setItem('posshipping', shipping);
	 	loadItems();
	 	$('#shipping_modal').modal('hide');
	 });

	 $(document).on('change', '.rserial', function () {
	 	var item_id = $(this).closest('tr').attr('data-item-id');
	 	positems[item_id].row.serial = $(this).val();
	 	__setItem('positems', JSON.stringify(positems));
	 });

	//If there is any item in localStorage
	if (__getItem('positems')) {
		loadItems();
	}

	//clear localStorage and reload
	$('#reset').click(function (e) {
		var susp_id = $('#suspend_id').val();
		bootbox.confirm(lang.r_u_sure, function (result) {
			if (result) {
	    	/*$.ajax({
	        type: "get",
	        url: site.base_url+"pos/clearPosItem",
	        data: {suspend_id: susp_id},
	        dataType: "json",
	        success: function (data) {
						$('#modal-loading').show();
						//location.reload();
						window.location.href = site.base_url+"pos";
	        }
		    });*/
			window.location.href = site.base_url+"pos";
				if (__getItem('positems')) {
					__removeItem('positems');
				}
				if (__getItem('posdiscount')) {
					__removeItem('posdiscount');
				}
				if (__getItem('postax2')) {
					__removeItem('postax2');
				}
				if (__getItem('posshipping')) {
					__removeItem('posshipping');
				}
				if (__getItem('posref')) {
					__removeItem('posref');
				}
				if (__getItem('poswarehouse')) {
					__removeItem('poswarehouse');
				}
				if (__getItem('posnote')) {
					__removeItem('posnote');
				}
				if (__getItem('posinnote')) {
					__removeItem('posinnote');
				}
				if (__getItem('poscustomer')) {
					__removeItem('poscustomer');
				}
				if (__getItem('poscurrency')) {
					__removeItem('poscurrency');
				}
				if (__getItem('posdate')) {
					__removeItem('posdate');
				}
				if (__getItem('posstatus')) {
					__removeItem('posstatus');
				}
				if (__getItem('posbiller')) {
					__removeItem('posbiller');
				}

				$('#modal-loading').show();
				//location.reload();
				window.location.href = site.base_url+"pos";
			}
		});
	});

	//save and load the fields in and/or from localStorage
	$('#poswarehouse').change(function (e) {
		__setItem('poswarehouse', $(this).val());
	});

	if (poswarehouse = __getItem('poswarehouse')) {
		$('#poswarehouse').select2('val', poswarehouse);
	}

	$('#posnote').redactor('destroy');
	$('#posnote').redactor({
		buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'link', '|', 'html'],
		formattingTags: ['p', 'pre', 'h3', 'h4'],
		minHeight: 100,
		changeCallback: function (e) {
			var v = this.get();
			__setItem('posnote', v);
		}
	});

	if (posnote = __getItem('posnote')) {
		$('#posnote').redactor('set', posnote);
	}

	$('#poscustomer').change(function (e) {
		__setItem('poscustomer', $(this).val());
		
	});

	// prevent default action usln enter
	$('body').bind('keypress', function (e) {
		if (e.keyCode == 13) {
			e.preventDefault();
			return false;
		}
	});

	// Order tax calculation
	if (site.settings.tax2 != 0) {
		$('#postax2').change(function () {
			__setItem('postax2', $(this).val());
			loadItems();
			return;
		});
	}

	// Order discount calculation
	var old_posdiscount;
	$('#posdiscount').focus(function () {
		old_posdiscount = $(this).val();
	}).change(function () {
		var new_discount = $(this).val() ? $(this).val() : '0';
		if (is_valid_discount(new_discount)) {
			__removeItem('posdiscount');
			__setItem('posdiscount', new_discount);
			loadItems();
			return;
		} else {
			$(this).val(old_posdiscount);
			bootbox.alert(lang.unexpected_value);
			return;
		}

	});

	/* ----------------------
	 * Delete Row Method
	 * ---------------------- */
	var pwacc = false;
	$(document).on('click', '.posdel', function () {
		var susp_id = $('#suspend_id').val();
		var item_row = $('#posTable tbody tr').length;
	 	var row = $(this).closest('tr');
	 	var item_id = row.attr('data-item-id');
		var subtotal = $('#total').html();
		var table_	= $('.table_no').val();
	 	var id_product = $(this).parent().parent().find('.rcode').val();
	 	var qty = $(this).parent().parent().find('.rquantity').val();

	 	var qtyinPic = parseInt($("#"+id_product + " span .qty").html()) - qty;
	 	if(qtyinPic > 1 || qtyinPic == 1) {

	 		$("#"+id_product + " span .qty").html(qtyinPic);
	 	}else if($("#"+id_product).length > 0){

	 		$("#"+id_product).remove();
	 	}
		

	 	if(protect_delete == 1) {
	 		var boxd = bootbox.dialog({
	 			title: "<i class='fa fa-key'></i> Pin Code",
	 			message: '<input id="pos_pin" name="pos_pin" type="password" placeholder="Pin Code" class="form-control"> ',
	 			buttons: {
	 				success: {
	 					label: "<i class='fa fa-tick'></i> OK",
	 					className: "btn-success verify_pin",
	 					callback: function () {
	 						var pos_pin = md5($('#pos_pin').val());
	 						if(pos_pin == pos_settings.pin_code) {
	 							delete positems[item_id];
	 							row.remove();
	 							if(positems.hasOwnProperty(item_id)) { } else {
	 								__setItem('positems', JSON.stringify(positems));
	 								loadItems();
	 							}
	 						} else {
	 							bootbox.alert('Wrong Pin Code');
	 						}
	 					}
	 				}
	 			}
	 		});
	 		boxd.on("shown.bs.modal", function() {
	 			$( "#pos_pin" ).focus().keypress(function(e) {
	 				if (e.keyCode == 13) {
	 					e.preventDefault();
	 					$('.verify_pin').trigger('click');
	 					return false;
	 				}
	 			});
	 		});
	 	} else {
	 		delete positems[item_id];
	 		row.remove();
	 		if(positems.hasOwnProperty(item_id)) { } else {
	 			__setItem('positems', JSON.stringify(positems));
				
	 			loadItems();
	 		}
	 	}
	 	return false;
	 });
	 
	/* -----------------------
	 * Search Row Modal Hanlder
	 ----------------------- */
	$(document).on('click', '#search_details', function (e) {
		
		$('#seModal').appendTo("body").modal('show');
		return false;
	});
	
	$(document).on('click', '#discount_shortcut', function (e) {
		$('#dsModal').appendTo("body").modal('show');
		$('.close-payment').trigger('click');
		return false;
	});
	
	$(document).on('click', '#search_floor', function (e) {
		
		$('#seFoModal').appendTo("body").modal('show');
		return false;
	});
    
	/* -----------------------
	 * Edit Row Modal Hanlder
	 ----------------------- */
	$(document).on('click', '.edit', function () {		
		var row = $(this).closest('tr');
		var row_id = row.attr('id');
		item_id = row.attr('data-item-id');
		item = positems[item_id];
		var expdates = item.expdates ? item.expdates : null;
		var qty = row.children().children('.rquantity').val(),
		product_option = row.children().children('.roption').val(),
		qty_in_hand = row.children().children('.inhand').val(),
		qty_order= row.children().children('.qtyorder').val(),
		unit_price = formatDecimal(row.children().children('.realuprice').val()),
		discount = row.children().children('.rdiscount').val();
		var default_price = formatDecimal(row.find('.default_price').val());
		var cost = formatDecimal(row.find('.cost').val());
		var img_note =item.row.img_pnote ? item.row.img_pnote : null;
		$(".images").html(img_note);
		var pnote = item.row.note ? item.row.note : null;
		$("#pnote").val(pnote);
		var net_price = unit_price;
		$('#prModalLabels').text(item.row.name + ' (' + item.row.code + ')');
		if (site.settings.tax1) {
			$('#ptax').select2('val', item.row.tax_rate);
	 		$('#old_tax').val(item.row.tax_rate);
	 		var item_discount = 0, ds = discount ? discount : '0';
	 		if (ds.indexOf("%") !== -1) {
	 			var pds = ds.split("%");
	 			if (!isNaN(pds[0])) {
	 				item_discount = parseFloat(((unit_price) * parseFloat(pds[0])) / 100);
	 			} else {
	 				item_discount = parseFloat(ds/qty);
	 			}
	 		} else {
	 			item_discount = parseFloat(ds/qty);
	 		}
			
			net_price = net_price  - item_discount

	 		var pr_tax = item.row.tax_rate, pr_tax_val = 0;
 		    if (pr_tax !== null && pr_tax != 0) {
 		        $.each(tax_rates, function () {
 		        	if(this.id == pr_tax){
 			        	if (this.type == 1) {

 			        		if (positems[item_id].row.tax_method == 0) {
 			        			pr_tax_val = formatDecimal(((unit_price  - item_discount) * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
 			        			pr_tax_rate = formatDecimal(this.rate) + '%';
 			        			net_price -= pr_tax_val;
 			        		} else {
 			        			pr_tax_val = formatDecimal(((unit_price  - item_discount) * parseFloat(this.rate)) / 100);
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
		
		var exp = '<p style="margin: 12px 0 0 0;">n/a</p>';
		if(expdates !== null) {
			exp = $("<select id=\"expdate\" name=\"expdate\" class=\"form-control select expdate\" />");
			$.each(expdates, function () {
				$("<option />", {value: this.id, text: fsd(this.expiry)}).appendTo(exp);
			});
		}
		
		var opt = '<p style="margin: 12px 0 0 0;">n/a</p>';
		var opt_group_price = '<p style="margin: 12px 0 0 0;">n/a</p>';
		
		if(site.settings.attributes == 1){
			
			if (item.options !== false) {
				var o = 1;
				opt = $("<select id=\"poption\" name=\"poption\" class=\"form-control select\" />");
				$.each(item.options, function () {
					if(o == 1) {
						if(product_option == '') { product_variant = this.id; } else { product_variant = product_option; }
					}
					$("<option />", {value: this.id, text: this.name+" (" + formatDecimal(parseFloat(this.qty_unit)) + ")"}).attr("rate",item.row.cost).attr("qty_unit",this.qty_unit).attr("makeup_cost_percent", item.makeup_cost_percent).attr("option_price",this.price).appendTo(opt);
					o++;
				});
			}
			
		} else {
			
			if(item.all_group_prices !== false && item.all_group_prices) {
				var gp = 1;
				opt_group_price = $("<select id=\"pgroup_price\" name=\"pgroup_price\" class=\"form-control select\" />");
				$.each(item.all_group_prices, function () {
					if(gp == 1) {
						$("<option />", {value: 0, text: 'Default Price' + ' (' + formatDecimal(default_price) + ' USD)'}).appendTo(opt_group_price);
						if(item.row.price_id == 0) {
							$("<option />", {value: this.price_group_id, text: this.group_name + ' (' + formatDecimal(this.price) + ' '+ this.currency_code +')'}).attr("data-currency_code", this.currency_code).appendTo(opt_group_price);
						}else{
							$("<option />", {value: this.price_group_id, text: this.group_name + ' (' + formatDecimal(this.price) + ' '+ this.currency_code +')'}).attr("data-currency_code", this.currency_code).appendTo(opt_group_price);
						}
					}else{
						$("<option />", {value: this.price_group_id, text: this.group_name + ' (' + formatDecimal(this.price) + ' '+ this.currency_code +')'}).attr("data-currency_code", this.currency_code).appendTo(opt_group_price);
					}
					gp++;
				});
			}
		
		}
			
		$('#piece').val(item.row.piece);
		$('#expdates-div').html(exp);
		$('#wpiece').val(item.row.w_piece);
		$('#poptions-div').html(opt);
		$('#pgroup_prices-div').html(opt_group_price);
		$('select.select').select2({minimumResultsForSearch: 6});
		$('#pquantity').val(qty);
		$('#old_qty').val(qty);
		$('#pprice').val(unit_price);
		$('#pprice_show').val(unit_price);
		$('#punit_price').val(formatDecimal(parseFloat(unit_price)+parseFloat(pr_tax_val)));
		$('#poption').select2('val', item.row.option);
		$('#old_price').val(unit_price);
		$('#pgroup_price').select2('val', item.row.price_id);
		$('#row_id').val(row_id);
		$('#item_id').val(item_id);
		$('#pserial').val(row.children().children('.rserial').val());
		$('#pdiscount').val(discount);
		$('#qtyinhand').val(qty_in_hand);
		$('#expdate').select2('val', item.row.expdate);
		if(qty_order !="NaN"){
		  $('#qtyorder').val(qty_order);	
		}if(qty_order=='NaN'){
			$('#qtyorder').val("");
		}
		$("#expdate").change(function(){
			var warehouse_id = $("#poswarehouse").val();
			var exp_id = $("#expdate").val();
			var product_id = item.row.id;
			$.ajax({
				type: 'get',
				url: site.base_url+'sales/getCurrentStockQtyByDate',
				dataType: "json",
				data: {
				exp_id: exp_id,
				product_id : product_id,
				warehouse_id : warehouse_id
				},
				success: function (data) {
					if(data.quantity_balance && data.quantity_balance > 0){
						$("#pquantity").val(Number(data.quantity_balance));
						$("#exp_qty").val(Number(data.quantity_balance));
					}
				}
			});
		});
		
		$("#pquantity").change(function(){
			if(site.settings.product_expiry == 1){
				var pquantity = $("#pquantity").val();
				var exp_qty = $("#exp_qty").val();
				if(exp_qty !== '' || exp_qty != 0){
					if(Number(pquantity) > Number(exp_qty)){
						$("#pquantity").val(exp_qty);
					}
				}
			}			
		});
		
		$('#net_price').text(formatMoney(net_price));
	    $('#pro_tax').text(formatMoney(pr_tax_val));
		$('#prModal').appendTo("body").modal('show');
		$('#pgroup_price').trigger("change");
		$("#expdate").trigger("change");
	});
	
	$(document).on('click', '#paid-ment', function () {
		$('.close-payment').css('display','none');
		$('#payments').css('display','none');
		$('#paymentModal').appendTo("body").modal('show');
		return false;
	});
	
	$('#prModal').on('shown.bs.modal', function (e) {
		if($('#poption').select2('val') != '') {
			$('#poption').select2('val', product_variant);
			product_variant = 0;
		}
	});
	
	$('#pprice_show').change(function(){
		$('#pprice').val($(this).val());
		$("#ptax").trigger("change");
	});
	
	$(document).on('change','#pprice, #ptax, #pdiscount,#pquantity', function () {
	    var row = $('#' + $('#row_id').val()); 
	    var item_id = row.attr('data-item-id');
	    var unit_price = parseFloat($('#pprice').val());
		var item_qty=parseFloat($('#pquantity').val());
	    var item = positems[item_id];
	    var ds = $('#pdiscount').val() ? $('#pdiscount').val() : '0';
		if (ds.indexOf("%") !== -1) {
	        var pds = ds.split("%");
	        if (!isNaN(pds[0])) {
	            item_discount =  parseFloat(((unit_price*item_qty) * parseFloat(pds[0] / 100))/item_qty);
	        } else {
	            item_discount = parseFloat(ds / item_qty);
	        }
	    } else {
	        item_discount =  parseFloat(ds / item_qty);
	    }
		
	    var pr_tax = $('#ptax').val(), item_tax_method = item.row.tax_method;
	    var pr_tax_val = 0, pr_tax_rate = 0;
		unit_price = unit_price - item_discount;
		
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
		var price = 0;
		if(own_rate = parseFloat($('#own_rate').val())){
			price = (parseFloat($('#pprice').val()) * parseFloat($('#own_rate').val()));
		}else{
			price = parseFloat($('#pprice').val());
		}
		
		var own_rate = parseFloat($('#own_rate').val());
		var setting_rate = parseFloat($('#setting_rate').val());

		if (site.settings.product_discount == 1 && $('#pdiscount').val()) {
			if(!is_valid_discount($('#pdiscount').val()) || $('#pdiscount').val() > price) {
				bootbox.alert(lang.unexpected_value);
				return false;
			}
		} 
		
		var piece  = $("#piece").val()-0;
		var wpiece = $("#wpiece").val()-0;
		var img_pnote = $('.images').html();
		positems[item_id].row.img_pnote = img_pnote ? img_pnote : null;
		positems[item_id].row.piece = piece;
		positems[item_id].row.wpiece = wpiece;
		positems[item_id].row.qty = parseFloat($('#pquantity').val()),
		positems[item_id].row.real_unit_price = price,
		positems[item_id].row.exp_qty = parseFloat($('#exp_qty').val()),
		positems[item_id].row.tax_rate = new_pr_tax,
	 	positems[item_id].tax_rate = new_pr_tax_rate,
		positems[item_id].row.discount = $('#pdiscount').val() ? $('#pdiscount').val() : '',
		positems[item_id].row.promo_price = $('#pdiscount').val() ? $('#pdiscount').val() : '',
		positems[item_id].row.option = $('#poption').val() ? $('#poption').val() : '',
		positems[item_id].row.price_id = $('#pgroup_price').val() ? $('#pgroup_price').val() : '',
		positems[item_id].row.expdate = $('#expdate').val() ? $('#expdate').val() : '',
		positems[item_id].row.note = $('#pnote').val() ? $('#pnote').val() : '',
		positems[item_id].row.serial = $('#pserial').val();
		
		if(positems[item_id].group_prices){
			positems[item_id].group_prices[0].price = price;
			positems[item_id].group_prices[0].rate = own_rate;
			positems[item_id].group_prices[0].setting_curr = setting_rate;
			positems[item_id].group_prices[0].price_group_id = $('#pgroup_price').val() ? $('#pgroup_price').val() : '';
		}
		__setItem('positems', JSON.stringify(positems));
		 
		$('#prModal').modal('hide');
		loadItems();
		return;
	});

	/* -----------------------
	 * Product option change
	 ----------------------- */
	 $(document).on('change', '#poption', function () {
	 	var row = $('#' + $('#row_id').val()), opt = $(this).val();
	 	var item_id = row.attr('data-item-id');
	 	var item = positems[item_id];
	 	if(item.options !== false) { 
	 		$.each(item.options, function () {
	 			if(site.settings.attributes == 1){
					if(item.makeup_cost == 1){
						var pro_opt = $("#poption option:selected").attr('rate');
						var pro_qty = $("#poption option:selected").attr('qty_unit');
						var pro_mkp	=  $("#poption option:selected").attr('makeup_cost_percent');
						var price   = (pro_opt*pro_qty)+((pro_opt*pro_qty)*((isNaN(pro_mkp)?0:pro_mkp)/100));
						$('#pprice,#pprice_show').val(formatDecimal(price));
						$("#net_price").text(formatMoney(price));
						
					}else{
						var pro_opt = $("#poption option:selected").attr('option_price');
						$('#pprice').val(pro_opt);
						$('#pprice_show').val(pro_opt);
						$( "#pprice" ).trigger( "change" );
					}
					
				}else{
					$('#pprice,#pprice_show').val(this.price);
					$("#net_price").text(formatMoney(this.price));
				}
				
	 		});
			
	 	}
		
		$("#pprice_show").trigger("change");
	 });
	 
	/* -----------------------
	 * Product Group Price change
	 ----------------------- */
	 $(document).on('change', '#pgroup_price', function () {
		var row 		= $('#' + $('#row_id').val()), opt = $(this).val();
	 	var item_id 	= row.attr('data-item-id');
	 	var item 		= positems[item_id];
		var price 		= 0;
		var default_price = formatDecimal(row.find('.default_price').val());
		if(item.all_group_prices !== false) {
			
			$.each(item.all_group_prices, function () {
				
				if(opt == 0){
					$('#pprice').val(default_price);
					$('#pprice_show').val(formatDecimal(default_price));
					$("#net_price").text(formatMoney(default_price));
				}else{
					if(this.price_group_id == opt && this.price != 0 && this.price != '' && this.price != null) {
						var cur_price_1 = this.price;
						var own_rate	= this.rate;
						var setting_rate= this.setting_curr;
						
						if(own_rate){
							mult_cur = multiCurrFormular(own_rate, setting_rate, cur_price_1);
						}else{
							mult_cur = default_price;
						}
						
						$('#pprice').val(mult_cur);
						$('#pprice_show').val(formatDecimal(mult_cur));
						$('#own_rate').val(own_rate);
						$('#setting_rate').val(setting_rate);
						$("#net_price").text(formatMoney(mult_cur));
					}
				}
			});
		}
	});
	
	 /* ------------------------------
	 * Sell Gift Card modal
	 ------------------------------- */
	 $(document).on('click', '#sellGiftCard', function (e) {
	 	if (count == 1) {
	 		positems = {};
	 		if ($('#poswarehouse').val() && $('#poscustomer').val()) {
	 			$('#poscustomer').select2("readonly", true);
	 			$('#poswarehouse').select2("readonly", true);
	 		} else {
	 			bootbox.alert(lang.select_above);
	 			item = null;
	 			return false;
	 		}
	 	}
		$('.gcerror-con').hide();
	 	$('#gcModal').appendTo("body").modal('show');
	 	return false;
	 });

	 $('#gccustomer').select2({
	 	minimumInputLength: 1,
	 	ajax: {
	 		url: site.base_url+"customers/suggestions",
	 		dataType: 'json',
	 		quietMillis: 15,
	 		data: function (term, page) {
	 			return {
	 				term: term,
	 				limit: 10
	 			};
	 		},
	 		results: function (data, page) {
	 			if(data.results != null) {
	 				return { results: data.results };
	 			} else {
	 				return { results: [{id: '', text: 'No Match Found'}]};
	 			}
	 		}
	 	}
	 });

	 $('#genNo').click(function(){
	 	var no = generateCardNo();
	 	$(this).parent().parent('.input-group').children('input').val(no);
	 	return false;
	 });
	 
	 $('.date').datetimepicker({format: site.dateFormats.js_sdate, fontAwesome: true, language: 'sma', todayBtn: 1, autoclose: 1, minView: 2 });
	 
	 $(document).on('click', '#addGiftCard', function (e) {
	 	var mid = (new Date).getTime(),
	 	gccode = $('#gccard_no').val(),
	 	gcname = $('#gcname').val(),
	 	gcvalue = $('#gcvalue').val(),
	 	gccustomer = $('#gccustomer').val(),
	 	gcexpiry = $('#gcexpiry').val() ? $('#gcexpiry').val() : '',
	 	gcprice = formatMoney($('#gcprice').val());
	 	if(gccode == '' || gcvalue == '' || gcprice == '' || gcvalue == 0 || gcprice == 0) {
	 		$('#gcerror').text('Please fill the required fields');
	 		$('.gcerror-con').show();
	 		return false;
	 	}

	 	var gc_data = new Array();
	 	gc_data[0] = gccode;
	 	gc_data[1] = gcvalue;
	 	gc_data[2] = gccustomer;
	 	gc_data[3] = gcexpiry;
		
		$.ajax({
			type: 'get',
			url: site.base_url+'sales/sell_gift_card',
			dataType: "json",
			data: { gcdata: gc_data },
			success: function (data) {
				if(data.result === 'success') {
					positems[mid] = {"id": mid, "item_id": mid, "label": gcname + ' (' + gccode + ')', "row": {"id": mid, "code": gccode, "name": gcname, "quantity": 1, "price": gcprice, "real_unit_price": gcprice, "tax_rate": 0, "qty": 1, "type": "manual", "discount": "0", "serial": "", "option":""}, "tax_rate": false, "options":false};
					__setItem('positems', JSON.stringify(positems));
					loadItems();
					$('#gcModal').modal('hide');
					$('#gccard_no').val('');
					$('#gcvalue').val('');
					$('#gcexpiry').val('');
					$('#gcprice').val('');
				} else {
					$('#gcerror').text(data.message);
					$('.gcerror-con').show();
				}
			}
		});
		return false;
	});

	/* ------------------------------
	 * Show manual item addition modal
	 ------------------------------- */
	$(document).on('click', '#addManually', function (e) {
		if (count == 1) {
			positems = {};
			if ($('#poswarehouse').val() && $('#poscustomer').val()) {
				$('#poscustomer').select2("readonly", true);
				$('#poswarehouse').select2("readonly", true);
			} else {
				bootbox.alert(lang.select_above);
				item = null;
				return false;
			}
		}
		$('#mnet_price').text('0.00');
		$('#mpro_tax').text('0.00');
		$('#mModal').appendTo("body").modal('show');
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
		
		positems[mid] = {"id": mid, "item_id": mid, "label": mname + ' (' + mcode + ')', "row": {"id": mid, "code": mcode, "name": mname, "quantity": mqty, "price": unit_price, "unit_price": unit_price, "real_unit_price": unit_price, "tax_rate": mtax, "tax_method": 0, "qty": mqty, "type": "manual", "discount": mdiscount, "serial": "", "option":""}, "tax_rate": mtax_rate, "options":false};
		__setItem('positems', JSON.stringify(positems));
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
	
	$(document).on('change','#piece,#wpiece',function(){
		var piece  = $('#piece').val()-0;
		var wpiece = $("#wpiece").val()-0;
		var total  = (piece*wpiece);
		$("#pquantity").val(formatDecimal(total)).trigger("change");
		$("#pnote").val(piece+" x "+wpiece);
	
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
	
	$(document).on("change", '.sdiscount', function () {
		var row = $(this).closest('tr');
	 	var susp_id = $('#suspend_id').val();
		var discount = $(this).val()?$(this).val():0;
	 	item_id = row.attr('data-item-id');
	 	positems[item_id].row.discount = discount;
	 	positems[item_id].row.promo_price = discount;
	 	__setItem('positems', JSON.stringify(positems));
	 	loadItems();
	});
	
	$(document).on("change", '.rquantity', function () {		
	 	var row = $(this).closest('tr');
	 	var susp_id = $('#suspend_id').val();
		var item_row = $('#posTable tbody tr').length;
		var subtotal = $('#total').html();
		var table_	= $('.table_no').val();
	 	var id_product = $(this).parent().parent().find('.rcode').val();
	 	var ruprice = $(this).parent().parent().find('.ruprice').val();
	 	var qty = $(this).val();

	 	var qtyinPic = parseInt($("#"+id_product + " span .qty").html()) - qty;

	 	/* if(qtyinPic > 1 || qtyinPic == 1) {

	 		$("#"+id_product + " span .qty").html(qtyinPic);
	 	}else if($("#"+id_product).length > 0){

	 		$("#"+id_product).remove();
	 	}*/

 		$.ajax({
		  type: "get",
		  url: site.base_url+"pos/updateQty",
		  data: {suspend_id: susp_id, item_code: id_product, quantity: qty, price: ruprice},
		  dataType: "json",
		  success: function (data) {
			//$(".sup_number"+susp_id).html('('+(item_row-1)+')');
					var suspend_html = '<p> '+table_+'</p>';
				suspend_html += '<div class="sup_number'+susp_id+'">('+(item_row)+')</div>';
			if(data != ''){
				suspend_html += '<br/>'+formatMoney(data['sub_total']);
			}else{
				suspend_html += '<br/>'+subtotal;
			}
			$('.wrap_suspend'+susp_id).html(suspend_html);
		  }
		});
		if(site.settings.product_expiry == 1){
			var get_exp_qty = row.find('#exp_qty').val();
			if(parseFloat($(this).val()) > Number(get_exp_qty)){
				bootbox.alert('Quantity is bigger than quantity expiry !');
				$(this).val(get_exp_qty);
			}
		}
	 	if (!is_numeric($(this).val()) || $(this).val() == 0) {
	 		loadItems();
	 		bootbox.alert(lang.unexpected_value);
	 		return false;
	 	}
	 	var new_qty = parseFloat($(this).val()),
	 	item_id = row.attr('data-item-id');
	 	positems[item_id].row.qty = new_qty;
	 	__setItem('positems', JSON.stringify(positems));
	 	loadItems();
	 });

// end ready function
});

/* -----------------------
 * Multi Currencies 
 ----------------------- */
function multiCurrFormular(own_rate, setting_rate, amount){
	var result = 0;
	result = (amount/own_rate)*setting_rate;
	return result;
}

function fsd(oObj) {
    if (oObj != null) {
        var aDate = oObj.split('-');
        if (site.dateFormats.js_sdate == 'dd-mm-yyyy')
            return aDate[2] + "-" + aDate[1] + "-" + aDate[0];
        else if (site.dateFormats.js_sdate === 'dd/mm/yyyy')
            return aDate[2] + "/" + aDate[1] + "/" + aDate[0];
        else if (site.dateFormats.js_sdate == 'dd.mm.yyyy')
            return aDate[2] + "." + aDate[1] + "." + aDate[0];
        else if (site.dateFormats.js_sdate == 'mm/dd/yyyy')
            return aDate[1] + "/" + aDate[2] + "/" + aDate[0];
        else if (site.dateFormats.js_sdate == 'mm-dd-yyyy')
            return aDate[1] + "-" + aDate[2] + "-" + aDate[0];
        else if (site.dateFormats.js_sdate == 'mm.dd.yyyy')
            return aDate[1] + "." + aDate[2] + "." + aDate[0];
        else
            return oObj;
    } else {
        return '';
    }
}

/* -----------------------
 * Load all items
 ----------------------- */

//localStorage.clear();
function loadItems() {
	$('#print_order_drink').css('pointer-events','auto');
	$('#print_order_food').css('pointer-events','auto');
	
	if (__getItem('positems')) {
		total 				= 0;
		count 				= 1;
		an 					= 1;
		product_tax 		= 0;
		test_real 			= 0 ;
		invoice_tax 		= 0;
		product_discount 	= 0;
		order_discount 		= 0;
		total_tax			= 0;
		total_shipping		= __getItem('posshipping') ? __getItem('posshipping') : 0;
		subtotal_discount	= 0;
		total_discount 		= 0;

		$("#posTable tbody").empty();
		if(java_applet == 1) {
			order_data 		= "";
			bill_data 		= "";
			bill_data 	   += chr(27) + chr(69) + "\r" + chr(27) + "\x61" + "\x31\r";
			bill_data 	   += site.settings.site_name + "\n\n";
			order_data 		= bill_data;
			bill_data 	   += "Bill" + "\n";
			order_data 	   += "Order" + "\n";
			bill_data 	   += $('#select2-chosen-1').text() + "\n\n";
			bill_data 	   += " \x1B\x45\x0A\r\n ";
			order_data 	   += $('#select2-chosen-1').text() + "\n\n";
			order_data 	   += " \x1B\x45\x0A\r\n ";
			bill_data 	   += "\x1B\x61\x30";
			order_data 	   += "\x1B\x61\x30";
		} else {
			
			var d = new Date();
			var hourString;
			var hourInt;
			var amPm 		= "AM";
			if ( d.getHours() > 11 ) {
				amPm 		= "PM";
				hourString 	= "0" + (d.getHours() - 12);
			} else {
				amPm 		= "AM";
				hourInt	 	= "0" + d.getHours();
			}

			var formattedDate = "" + d.getDate() + "/" + (d.getMonth()+1) + "/" + d.getFullYear() + " " + d.getHours() + ":" + d.getMinutes() + " " + amPm;
			var u_username 	= __getItem('u_username');
			var queue 		= __getItem('queue');
			$("#order_span").empty(); $("#order_span_drink").empty(); $("#bill_span").empty();
			//var pos_head1 = '<span style="text-align:center;"><h3>'+site.settings.site_name+'</h3><h4>'
			var pos_head2 = '</h4><p>Room | Table: <span style="font-size:18px;">'+ $("#suspend_name").val() +'</span><br/>Waiting No: <span style="font-size:18px;">'+ (parseFloat(queue) + 1) +'</span><br/>Username: '+u_username+'<br/>Customer: '+$('#select2-chosen-1').text()+'<br>In: '+formattedDate+'<br/></p>';
			$("#order_span").prepend('<h5 class="center">Order</h5> '+pos_head2);
			
			$("#order_span_food").empty();
			$('#order_span_drink').empty();
			$("#order_span_drink").prepend('<h5 class="center">Order Drinks</h5> '+pos_head2);
			$("#order_span_food").prepend('<h5 class="center">Order Foods</h5> '+pos_head2);
			$("#bill_span").prepend(pos_head2);
			$("#order-table").empty(); $("#order-table-drink").empty(); $("#order-table-food").empty(); $("#bill-table").empty();
		}
		positems = JSON.parse(__getItem('positems'));
		var n = 1;		
		$.each(positems, function (i, e) {
			var item 			= this;
			var item_id 		= site.settings.item_addition == 1 ? item.id : item.id;
			positems[item_id] 	= item;
			var item_note 		= '';
			var product_id 		= item.row.id, 
			item_type 			= item.row.type, 
			digital_id 			= item.row.digital_id ? item.row.digital_id : '', 
			picture 			= item.row.image, 
			item_promotion 		= item.row.promotion, 
			item_pro_price 		= item.row.promo_price, 
			combo_items 		= item.combo_items, 
			item_price 			= item.row.price, 
			item_qty 			= item.row.qty, 
			item_aqty 			= item.row.quantity, 
			item_tax_method 	= item.row.tax_method, 
			item_ds 			= item.row.discount, 
			item_discount 		= 0, 
			item_option 		= item.row.option, 
			item_cost 			= 0,
			group_prices 		= item.group_prices,
			all_group_price 	= item.all_group_prices,
			price_id 			= item.row.price_id,
			item_code 			= item.row.code, 
			item_serial	 		= item.row.serial, 
			item_details 		= item.row.product_details, 
			item_note 			= item.row.note, 
			item_img_pnote 		= item.row.img_pnote, 
			item_type_by 		= item.row.cate_type,
			item_print 			= item.row.printed, 
			item_name 			= item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;"), 
			digital_name 		= item.row.digital_name ? item.row.digital_name.replace(/"/g, "&#034;").replace(/'/g, "&#039;") : '', 
			sep 				= item.row.sep,
			expdate 			= item.row.expdate ? item.row.expdate : null,
			exp_qty 			= item.row.exp_qty ? item.row.exp_qty : null,
			piece 			    = item.row.piece,	
			wpiece 			    = item.row.wpiece,
			w_piece 			= item.row.w_piece;			
			var unit_price 		= item.row.real_unit_price;
			var real_unit_price = item.row.real_unit_price;
			var p_kh_rate 		= __getItem('exchange_kh'); 
			var default_price 	= 0;
			var cost_items      = item.row.cost;
			if(item_promotion && item.row.start_date && item.row.end_date){
				var pro_start_date 	= moment(item.row.start_date).format('DD/MM/YYYY');
				var pro_end_date 	= moment(item.row.end_date).format('DD/MM/YYYY');
				var currentDate 	= moment().format('DD/MM/YYYY');
				
				if(currentDate >= pro_start_date && currentDate <= pro_end_date){
					item_ds 		= item_pro_price;
				}
			}
			
			if(site.settings.attributes == 0){
				if(group_prices){
					$.each(group_prices, function(){
						var cur_price_1 = this.price;
						var own_rate	= this.rate;
						var setting_rate= this.setting_curr;
						mult_cur = multiCurrFormular(own_rate, setting_rate, cur_price_1);
						if(item.makeup_cost == 1){
							mult_cur = parseFloat(mult_cur) + parseFloat((mult_cur * item.customer_percent) / 100);
						}else{
							mult_cur = parseFloat(mult_cur) + parseFloat((mult_cur * item.customer_percent) / 100);
						}
						
						item_price = mult_cur;
						unit_price = mult_cur;
						real_unit_price = mult_cur;
						item.row.price_id = this.price_group_id;
						default_price = this.default_price;
					});
				}
			}
			
			var pn 				= item_note ? item_note : '';
			var ds 				= item_ds ? item_ds : '0';
			var orderqty 		= item.orderqty;
			ds 					= ds.toString();
			var price_tax_cal 	= unit_price;
			//====================== Tax ====================//
			if (site.settings.tax_calculate != 0) {
				var prt = item.tax_rate;
				if (site.settings.tax1 == 1) {
					if (prt !== false) {
						if (prt.type == 1) {
							
							if (item_tax_method == '0') {  							
								price_tax_cal 	= (unit_price * 100) / (100 + parseFloat(prt.rate)); 		
							} else {
								price_tax_cal 	= unit_price;
							}

						}
					}
				}
				console.log('he');
			} 
			//====================== End ===================//
			if (ds.indexOf("%") !== -1) {
				var pds = ds.split("%");
				var pr_tax = item.tax_rate;
				if (!isNaN(pds[0])) {
					item_discount 		= (parseFloat(((price_tax_cal) * parseFloat(pds[0])) / 100));
					product_discount 	+= parseFloat(item_discount*item_qty);  	
                    subtotal_discount 	= parseFloat(item_discount*item_qty);					
				} else {
					item_discount = formatDecimal(ds); 
				}
			} else {
				item_discount 		= parseFloat(ds); 
				product_discount 	+= parseFloat(item_discount); 
				subtotal_discount 	= parseFloat(item_discount);
			}

			var pr_tax = item.tax_rate;
			
			var pr_tax_val = 0, pr_tax_rate = 0;
			if (site.settings.tax1 == 1) {
				if (pr_tax !== false) {
					if (pr_tax.type == 1) {
						
						if (item_tax_method == '0') {   
							pr_tax_val 	= formatMoney(parseFloat(((unit_price) * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate)))); 		
							pr_tax_rate = parseFloat(pr_tax.rate) + '%';
						} else {
							pr_tax_val 	= formatMoney(((unit_price - formatDecimal(subtotal_discount/item_qty)) * parseFloat(pr_tax.rate)) / 100);
							pr_tax_rate = parseFloat(pr_tax.rate) + '%';
						}

					} else if (pr_tax.type == 2) {

						pr_tax_val = parseFloat(pr_tax.rate); 
						pr_tax_rate = pr_tax.rate;

					}
					
					product_tax += pr_tax_val * item_qty;
				}
			}
			
			item_price = item_tax_method == 0 ? formatDecimal(unit_price-pr_tax_val) : formatDecimal(unit_price);
			//unit_price = formatDecimal(unit_price-subtotal_discount);
			
			var sel_opt = '';
			$.each(item.options, function () {
				if(this.id == item_option) {
					sel_opt = this.name;
				}
			});
			var layouts = '';
			if(user_layout.pos_layout){
				layouts = user_layout.pos_layout;
			}else{
				layouts = pos_settings.pos_layout;
			}
			var first_load = 1;
			var row_no = (new Date).getTime();
			var combo_arr = eval(combo_items);
			var combo_ = '';
			var i = 1;
			
			for(a in combo_arr){
				combo_ += '<div style="border-bottom: 1px solid rgb(204, 204, 204);">#'+i+' '+ combo_arr[a]['name'] + '</div>';
				i++;
			}
			
			var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');
			tr_html = '<td><span>#'+ n +'</span><input type="hidden" class="count" value="' + item_id + '"></td>'; 
			if(layouts == 3 && pos_settings.show_item_img != 0) {
				tr_html += '<td style="text-align:center;"><span><a href="'+site.base_url+'assets/uploads/'+picture+'"><img src="'+site.base_url+'assets/uploads/'+picture+'" alt="' +item_name+ '" style="width:70px; height:70px;" /></a></span></td>'; 
			}
			
			if(layouts != 3) {
				if(pos_settings.show_product_code == 0) {
					tr_html += '<td class="edit" style="cursor:pointer;"><ul class="enlarges"><li><input name="digital_id[]" type="hidden" class="did" value="' + digital_id + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="expdate[]" type="hidden" class="expdate" value="' + expdate + '"><input name="exp_qty" type="hidden" class="exp_qty" id="exp_qty" value="' + exp_qty + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><input name="product_note[]" type="hidden" class="rnote" value="' + pn + '"><span class="sname" id="name_' + row_no + '">' + (item_promotion == 1 ? '<i class="fa fa-check-circle"></i> ' : '') + (digital_id?digital_name:item_name) +(sel_opt != '' ? ' ('+sel_opt+')' : '')+ (pn != '' ? ' [<span id="get_not">'+pn+'</span>]' : '')  +'</span>';
					if(pos_settings.show_item_img != 0) {
						tr_html += '<span class="showimg" style="width:350px;"><table class="table table-bordered"><tr><th>Image</th>'+(item_type == 'combo' ? '<th>Description</th>' : '')+(item_type == 'combo' ? '<th style="width:115px;">Combo Items</th>' : '')+'</tr><tr><td><a href="'+site.base_url+'assets/uploads/'+picture+'" data-toggle="lightbox"><img src="'+site.base_url+'assets/uploads/'+picture+'" alt="' +item_name+ '" style="width:200px;" class="img-thumbnail" /></a></td>'+(item_type == 'combo' ? '<td>'+item_details+'</td>' : '')+(item_type == 'combo' ? '<td><table>'+combo_+'</table></td>' : '')+'</tr></table></span>';
					}
				}else{
					tr_html += '<td class="edit" style="cursor:pointer;"><ul class="enlarges"><li><input name="digital_id[]" type="hidden" class="did" value="' + digital_id + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><input name="expdate[]" type="hidden" class="expdate" value="' + expdate + '"><input name="exp_qty" type="hidden" class="exp_qty" id="exp_qty" value="' + exp_qty + '"><input name="product_note[]" type="hidden" class="rnote" value="' + pn + '"><input name="img_pnote[]" type="hidden" class="img_pnote" value="' + item_img_pnote + '"><span class="sname" id="name_' + row_no + '">' + (item_promotion == 1 ? '<i class="fa fa-check-circle"></i> ' : '') + (digital_id?digital_name:item_name) + ' (' + item_code + ')'+(sel_opt != '' ? ' ('+sel_opt+')' : '')+ (pn != '' ? ' [<span id="get_not">'+pn+'</span>]' : '')  +'</span>';
					if(pos_settings.show_item_img != 0) {
						tr_html += '<span class="showimg" style="width:350px;"><table class="table table-bordered"><tr><th>Image</th>'+(item_type == 'combo' ? '<th>Description</th>' : '')+(item_type == 'combo' ? '<th style="width:115px;">Combo Items</th>' : '')+'</tr><tr><td><a href="'+site.base_url+'assets/uploads/'+picture+'" data-toggle="lightbox"><img src="'+site.base_url+'assets/uploads/'+picture+'" alt="' +item_name+ '" style="width:200px;" class="img-thumbnail" /></a></td>'+(item_type == 'combo' ? '<td>'+item_details+'</td>' : '')+(item_type == 'combo' ? '<td><table>'+combo_+'</table></td>' : '')+'</tr></table></span>';
					}
				}
			}else{
				if(pos_settings.show_product_code == 0) {
					tr_html += '<td class="edit" style="cursor:pointer;"><ul class="enlarges"><li><input name="digital_id[]" type="hidden" class="did" value="' + digital_id + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><input name="expdate[]" type="hidden" class="expdate" value="' + expdate + '"><input name="exp_qty" type="hidden" class="exp_qty" id="exp_qty" value="' + exp_qty + '"><input name="product_note[]" type="hidden" class="rnote" value="' + pn + '"><input name="img_pnote[]" type="hidden" class="img_pnote" value="' + item_img_pnote + '"><span class="sname" id="name_' + row_no + '">' + (item_promotion == 1 ? '<i class="fa fa-check-circle"></i> ' : '') + (digital_id?digital_name:item_name) +(sel_opt != '' ? ' ('+sel_opt+')' : '')+ (pn != '' ? ' [<span id="get_not">'+pn+'</span>]' : '')  +'</span>';
				}else{
					tr_html += '<td class="edit" style="cursor:pointer;"><ul class="enlarges"><li><input name="digital_id[]" type="hidden" class="did" value="' + digital_id + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><input name="expdate[]" type="hidden" class="expdate" value="' + expdate + '"><input name="exp_qty" type="hidden" class="exp_qty" id="exp_qty" value="' + exp_qty + '"><input name="product_note[]" type="hidden" class="rnote" value="' + pn + '"><input name="img_pnote[]" type="hidden" class="img_pnote" value="' + item_img_pnote + '"><span class="sname" id="name_' + row_no + '">' + (digital_id?digital_name:item_name) + ' (' + item_code + ')'+(sel_opt != '' ? ' ('+sel_opt+')' : '')+ (pn != '' ? ' (<span id="get_not">'+pn+'</span>)' : '')  +'</span>';
				
				}
			}

			/* Stock */
			//tr_html += '<td class="text-center" style="background-color:#eee"><span>'+ item.row.quantity +'</span></td>';
			
			if(pos_settings.product_unit != 1){
				tr_html += '<i class="pull-right fa fa-edit tip pointer edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></li></ul></td>';
			}else{
				//tr_html += '<i class="pull-right fa fa-edit tip pointer edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
				tr_html += '</td>';
			}
			tr_html += '<td class="text-right">';
			//alert(sep);
			if(site.settings.product_serial == 1){
				if(sep == ''){
					$('#payment').removeAttr('disabled');
				}else{
					if(item_serial == ''){
						$('#payment').attr('disabled', 'disabled');
					}else{
						$('#payment').removeAttr('disabled');
					}
				}
			}
			
			var p_kh_price = '៛ ' + formatSA(parseFloat(real_unit_price * p_kh_rate).toFixed(0));
			if (site.settings.product_serial == 1) {
				tr_html += '<input class="form-control input-sm rserial" name="serial[]" type="hidden" id="serial_' + row_no + '" value="'+item_serial+'">';
			}
			if (site.settings.product_discount == 1) {
				tr_html += '<input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item_ds + '">';
			}
			if (site.settings.tax1 == 1) {
				tr_html += '<input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><input type="hidden" class="sproduct_tax" id="sproduct_tax_' + row_no + '" value="' + formatMoney(pr_tax_val * item_qty) + '">';
			}
			if (item_promotion == 1){
				tr_html += '<input class="rprice" name="net_price[]" type="hidden" id="price_' + row_no + '" value="' + real_unit_price + '"><input class="ruprice" name="unit_price[]" type="hidden" value="' + real_unit_price + '"><input class="item_cost" name="item_cost[]" type="hidden" value="' + item_cost + '"><input class="realuprice" name="real_unit_price[]" type="hidden" value="' + real_unit_price + '"><span class="text-right sprice" id="sprice_' + row_no + '">' + formatMoney(parseFloat(real_unit_price)) + '</span></td>';
			}else{
				tr_html += '<input class="rprice" name="net_price[]" type="hidden" id="price_' + row_no + '" value="' + real_unit_price + '"><input class="ruprice" name="unit_price[]" type="hidden" value="' + real_unit_price + '"><input class="item_cost" name="item_cost[]" type="hidden" value="' + item_cost + '"><input class="realuprice" name="real_unit_price[]" type="hidden" value="' + real_unit_price + '"><span class="text-right sprice" id="sprice_' + row_no + '">' + formatMoney(parseFloat(real_unit_price)) + '</span></td>';
			}
			
			tr_html += '<input class="default_price" name="default_price[]" type="hidden" value="' + default_price + '">';
			tr_html += '<input class="cost" name="cost[]" type="hidden" value="' + cost_items + '">';
			
			tr_html += '<td class="text-right"><span class="price-kh">'+ p_kh_price +'</span></td>'; 
			
			tr_html += '<td><input class="form-control kb-pad text-center rquantity" name="quantity[]" type="text" value="' + formatDecimal(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"><input type="hidden" value="' + formatDecimal(item_aqty) + '" name="inhand[]" class="inhand"/><input type="hidden" value="' + formatDecimal(orderqty) + '" name="qtyorder[]" class="qtyorder"/></td>';
			
			if (site.settings.product_discount == 1) {
				tr_html += '<td class="text-right"><input class="form-control kb-pad text-center sdiscount" type="text" value="' + item_ds + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
			}else{
				tr_html += '<td class="text-right"><span class="text-right sdiscount" id="discount_' + row_no + '">' + item.row.discount + '</span></td>';
			}
            if (item_promotion == 1){
				tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney(((parseFloat(real_unit_price - subtotal_discount) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) + '</span><input type="hidden" name="grand_total[]" value="' + (((parseFloat(real_unit_price - subtotal_discount) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) + '"></td>';
			} else{   
				tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))-subtotal_discount) + '</span><input type="hidden" name="grand_total[]" value="' + formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))-subtotal_discount) + '"></td>';  				
			 
			}
            
			tr_html += '<td class="text-center posdel" style="cursor:pointer;"><i class="fa fa-2x fa-times tip pointer posdel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';

			$( window ).load(function() {
			  	var suspend_html ='<button id="' + item_code + '" type="button" value="' + item_name + '" title="' + item_id + ' (' + item_id +')" class="btn-prni btn-default product pos-tip" data-container="body" data-original-title="' + item_name + '"><img src="' + site.base_url + 'assets/uploads/thumbs/' + picture + '" alt="' + item_name + '" style="width: 60px; height: 60px;" class="img-rounded"/><span>Qty: <i class="qty"> ' + formatDecimal(item_qty) + '</i> ' + item_name.substring(0,15) + '...(' + formatMoney(real_unit_price) + ')</span></button>';
				$('#product-sale-view').prepend(suspend_html);
			});

			newTr.html(tr_html);
			newTr.prependTo("#posTable");
			if (item_promotion == 1){
				total += formatDecimal(((parseFloat(real_unit_price - subtotal_discount) + parseFloat(pr_tax_val)) * parseFloat(item_qty))-subtotal_discount);
			}else{
				total += formatDecimal(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty))-subtotal_discount);
				
			}
			count += parseFloat(Math.abs(item_qty));
			an++;

			if (item_type == 'standard' && item.options !== false) {
				$.each(item.options, function () {
					if(this.id == item_option && item_qty > this.quantity) {
						$('#row_' + row_no).addClass('danger');
					}
				});
			} else if(item_type == 'standard' && item_qty > item_aqty) {
				$('#row_' + row_no).addClass('danger');
			} 
			if(java_applet == 1) {
				bill_data += "#"+(an-1)+" "+ item_name + " (" + item_code + ")" + "\n";
				bill_data += printLine(item_qty + " x " + formatMoney(parseFloat(item_price) + parseFloat(pr_tax_val))+": "+ formatMoney(((parseFloat(item_price) + parseFloat(pr_tax_val)) * parseFloat(item_qty)))) + "\n";
				order_data += printLine("#"+(an-1)+" "+ item_name + " (" + item_code + "):"+ formatDecimal(item_qty)) + "\n";
			} else {
				if(n == 1){
				var table_b = '<tr class="head">';
					table_b +=  '				<th>No</th>';
					table_b +=  '				<th>Description</th>';
					table_b +=  '				<th>Qty</th>';
					table_b +=  '				<th>Unit</th>';
					table_b +=  '				<th>Dis</th>';
					table_b +=  '				<th>Amount</th>';
					table_b +=  '</tr>';
				}
			
				var bprTr = '<tr style="border-bottom: 1px solid #000;" class="row_' + item_id + '" data-item-id="' + item_id + '">';
				bprTr += '<td class="text-left">#'+(an-1)+'</td><td>'+ item_name+'</td>';
				bprTr += '<td style="text-align:center">'+ currencyFormat(item_qty) +'</td>';
				bprTr += '<td style="text-align:right">'+ formatMoney(real_unit_price) +'</td>';
				bprTr += '<td style="text-align:right">'+ (item.row.discount != 0 ? item.row.discount : '') +'</td>';
				bprTr += '<td style="text-align:right;font-size:18px;">'+ formatMoney(((parseFloat(real_unit_price - item_discount) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) +'</td>';
				bprTr += '</tr>';
				
				
				
				$("#order-table").append(oprTr);
				
				if(item_type_by!="")
				{
					if(item_type_by=='food' && item_print==0)
					{
						var oprTr = '<tr style="font-weight:bold" class="row_' + item_id + ' order_print_food " itemcode="'+item_code+'" data-item-id="' + item_id + '"><td>#'+(an-1)+' ' + item_name + ' (' + item_code + ') '+ (item_note? '['+item_note+']':"") +'</td><td>' + formatDecimal(item_qty) +'</td></tr>';
					    $("#order-table-food").append(oprTr);
					   
					}else if(item_type_by=='drink' && item_print==0){
						var oprTr = '<tr style="font-weight:bold" class="row_' + item_id + ' order_print_drink " itemcode="'+item_code+'" data-item-id="' + item_id + '"><td>#'+(an-1)+' ' + item_name + ' (' + item_code + ') '+ (item_note? '['+item_note+']':"") +'</td><td>' + formatDecimal(item_qty) +'</td></tr>';
					   $("#order-table-drink").append(oprTr);
					}
				}else{
					
					var oprTr = '<tr class="row_' + item_id + ' order_print " itemcode="'+item_code+'" data-item-id="' + item_id + '"><td>#'+(an-1)+' ' + item_name + ' (' + item_code + ') '+ (item_note? '['+item_note+']':"") +'</td><td>' + formatDecimal(item_qty) +'</td></tr>';
				}
				if(n == 1){
					$("#bill-table").append(table_b).append(bprTr);
				}else{
					$("#bill-table").append(bprTr);
				}
			}   
	    
		// Order level discount calculations
		if (posdiscount = __getItem('posdiscount')) {
			var ds = posdiscount;
			if (ds.indexOf("%") !== -1) {
				var pds = ds.split("%");
				if (!isNaN(pds[0])) {
					order_discount = formatDecimal(((total) * parseFloat(pds[0])) / 100);
					 
				} else {
					order_discount = parseFloat((total * ds) / 100);
				}
			} else {
				order_discount = parseFloat((total * ds) / 100);
			}

			//total_discount += parseFloat(order_discount);
		} 
		
		if (site.settings.tax2 != 0) {
            if (postax2 = __getItem('postax2')) {
                $.each(tax_rates, function () {
                    if (this.id == postax2) {
                        if (this.type == 2) {
                            total_tax = formatDecimal(this.rate);
                        }
                        if (this.type == 1) {
                            total_tax = formatDecimal((((total - order_discount) * this.rate) / 100), 4);
                        }
                    }
                });
            }
        }
		
		if (__getItem('posshipping')) {
			
		}
		
		$('#tds').text('('+formatDecimal(product_discount)+') '+formatMoney(order_discount)); 
		$('#ttax2').text('(' + formatDecimal(product_tax)+')' +formatMoney(total_tax));
		$('#text_shipping').text(formatMoney(total_shipping)); 
			n++;
		});

		var table_	= $('.table_no').val();
		var susp_id = $('#suspend_id').val();
		var table_name = $('.suspend-name'+susp_id).html();

		if(susp_id > 0){
			$.ajax({
			  type: "POST",
			  dataType: "json",
			  url: site.base_url+"pos/saveItemList",
			  data: $("#pos-sale-form").serialize(), // serializes the form's elements.
			  success: function(data)
			  {
				var total_amount = data['sub_total'];
				var total_item = data['total_items'];
				var image_item = data['image_'];
				var suspend_html = '<p> '+ table_name +'</p>';
					suspend_html += '<div class="sup_number'+susp_id+'">('+ total_item +')</div>';
					suspend_html += '<br/>'+formatMoney(total_amount);
				$('.wrap_suspend'+susp_id).html(suspend_html);
				/*
				$('#product-sale-view').html(image_item);
				*/
			  }
			});
		}	
				
		// Order level tax calculations
		
		total = formatDecimal(total);
		product_tax = formatDecimal(product_tax); 
		total_discount = formatDecimal(order_discount + product_discount);
		var kh_rate = __getItem('exchange_kh');
		// Totals calculations after item addition
		
		var gtotal = parseFloat(((total - order_discount) + total_tax) + parseFloat(total_shipping));
		$('#total').text(formatMoney(total));
		$('#titems').text((an - 1) + ' (' + (parseFloat(count) - 1) + ')');
		$('#total_items').val((parseFloat(count) - 1));
		$('#gtotal').text(formatMoney(gtotal));
		
		var gtotal_kh = parseFloat(gtotal * kh_rate );
		$("#gtotal_kh").text('(៛ '+formatMoney(parseFloat(gtotal_kh).toFixed(0)) + ')');
		
		if(java_applet == 1) {
			bill_data += "\n"+ printLine(lang_total+': '+ formatMoney(total)) +"\n";
			bill_data += printLine(lang_items+': '+ (an - 1) + ' (' + (parseFloat(count) - 1) + ')') +"\n";
			if(total_discount > 0) {
				bill_data += printLine(lang_discount+': ('+formatMoney(product_discount)+') '+formatMoney(order_discount)) +"\n";
			}
			if (site.settings.tax2 != 0 && invoice_tax != 0) {
				bill_data += printLine(lang_tax2+': '+ formatMoney(invoice_tax)) +"\n";
			}
			bill_data += printLine(lang_total_payable+': '+ formatMoney(gtotal)) +"\n";
		} else {
			var bill_totals = '';

			bill_totals += '</tbody><tfoot>';
			bill_totals += '<tr><td colspan="2" style="font-weight:bold;">Rate : '+ formatSA(parseFloat(kh_rate).toFixed(0))+' ៛</td>';
			bill_totals += '<td colspan="2" style="text-align:right;font-weight:bold;">'+lang_total+'</td><td style="text-align:right;font-weight:bold;font-size:18px;">'+formatMoney(total)+'</td></tr>';
			bill_totals += '<tr><td colspan="4" style="text-align:right;font-weight:bold;">'+lang_items+'</td><td style="text-align:right;font-weight:bold;font-size:18px;">'+(an - 1) + ' (' + (parseFloat(count) - 1) + ')</td></tr>';
			if(order_discount > 0) {
				bill_totals += '<tr><td colspan="4" style="text-align:right;font-weight:bold;">'+lang_discount+'</td><td style="text-align:right;font-weight:bold;font-size:18px;">'+ product_discount +'</td></tr>';
			}
			
			if (site.settings.tax2 != 0 && invoice_tax != 0) {
				bill_totals += '<tr><td colspan="4" style="text-align:right;font-weight:bold;">'+lang_tax2+'</td><td style="text-align:right;font-weight:bold;font-size:18px;">'+formatMoney(invoice_tax)+'</td></tr>';
			}
			
			var bill_amount_kh = 0;
			bill_amount_kh = gtotal * kh_rate;

			bill_totals += '<tr style="border:2px solid #000;padding-right: 12px;width:81%;"><td colspan="4" style="text-align:right;font-weight:bold;border-top:1px solid #000;padding:10px;">'+lang_total_payable+'</td><td style="text-align:right;font-weight:bold;font-size:18px;border-top:1px solid #000;padding:10px;">'+formatMoney(gtotal)+'</td></tr>';
			bill_totals += '<tr style="border:2px solid #000;"><td colspan="4" style="text-align:right;font-weight:bold;" >Total Amount KH</td><td style="text-align:right;font-weight:bold;font-size:18px;">'+formatSA(parseFloat(bill_amount_kh).toFixed(0))+' ៛</td></tr></tfoot>';
			$('#bill-total-table').empty();
			$('#bill-total-table').append(bill_totals);
		}
		if(count > 1) {
			$('#poscustomer').select2("readonly", true);
			$('#poswarehouse').select2("readonly", true);
		} else {
			$('#poscustomer').select2("readonly", false);
			$('#poswarehouse').select2("readonly", false);
		}
		if (KB) { display_keyboards(); }
		//audio_success.play();
		//$('#posTable > tbody > tr:first').children().children('.rquantity').focus(); // to auto focus quantity input of top item
		$('#add_item').focus();
	}
}

function printLine(str) {
	var size = pos_settings.char_per_line;
	var len = str.length;
	var res = str.split(":");
	var newd = res[0];
	for(i=1; i<(size-len); i++) {
		newd += " ";
	}
	newd += res[1];
	return newd;
}

/* -----------------------------
 * Add Purchase Iten Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */

function add_invoice_item(item) {

 	if (count == 1) {
 		positems = {};
 		if ($('#poswarehouse').val() && $('#poscustomer').val()) {
 			$('#poscustomer').select2("readonly", true);
 			$('#poswarehouse').select2("readonly", true);
 		} else {
 			bootbox.alert(lang.select_above);
 			item = null;
 			return;
 		}
 	}
 	if (item == null) {
 		return;
 	}
	
	var ie 		= $("#posTable tbody tr").length+1;
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
	
 	//var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
	
 	if (positems[item_id]) {
 		positems[item_id].row.qty = parseFloat(positems[item_id].row.qty) + 1;
		
 	} else {
 		positems[item_id] = item;
 	}

 	__setItem('positems', JSON.stringify(positems));
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

 function display_keyboards() {

 	$('.kb-text').keyboard({
 		autoAccept: true,
 		alwaysOpen: false,
 		openOn: 'focus',
 		usePreview: false,
 		layout: 'custom',
		//layout: 'qwerty',
		display: {
			'bksp': "\u2190",
			'accept': 'return',
			'default': 'ABC',
			'meta1': '123',
			'meta2': '#+='
		},
		customLayout: {
			'default': [
			'q w e r t y u i o p {bksp}',
			'a s d f g h j k l {enter}',
			'{s} z x c v b n m , . {s}',
			'{meta1} {space} {cancel} {accept}'
			],
			'shift': [
			'Q W E R T Y U I O P {bksp}',
			'A S D F G H J K L {enter}',
			'{s} Z X C V B N M / ? {s}',
			'{meta1} {space} {meta1} {accept}'
			],
			'meta1': [
			'1 2 3 4 5 6 7 8 9 0 {bksp}',
			'- / : ; ( ) \u20ac & @ {enter}',
			'{meta2} . , ? ! \' " {meta2}',
			'{default} {space} {default} {accept}'
			],
			'meta2': [
			'[ ] { } # % ^ * + = {bksp}',
			'_ \\ | &lt; &gt; $ \u00a3 \u00a5 {enter}',
			'{meta1} ~ . , ? ! \' " {meta1}',
			'{default} {space} {default} {accept}'
			]}
		});
 	$('.kb-pad').keyboard({
 		restrictInput: true,
 		preventPaste: true,
 		autoAccept: true,
 		alwaysOpen: false,
 		openOn: 'click',
 		usePreview: false,
 		layout: 'costom',
 		display: {
 			'b': '\u2190:Backspace',
 		},
 		customLayout: {
 			'default': [
 			'1 2 3 {b}',
 			'4 5 6 . {clear}',
 			'7 8 9 0 %',
 			'- {accept} {cancel}'
 			]
 		}
 	});

 }

 $('body').bind('keypress', function(e) {
 	if (e.keyCode == 13) {
 		e.preventDefault();
 		return false;
 	}
 });

if(site.settings.auto_detect_barcode == 1) {
	$(document).ready(function() {
		var pressed = false;
		var chars = [];
		$(window).keypress(function(e) {
			if(e.key == '%') { pressed = true; }
			chars.push(String.fromCharCode(e.which));
			if (pressed == false) {
				setTimeout(function(){
					if (chars.length >= 8) {
						var barcode = chars.join("");
						$( "#add_item" ).focus().autocomplete( "search", barcode );
					}
					chars = [];
					pressed = false;
				},200);
			}
			pressed = true;
		});
	});
}

$(document).ready(function() {
	read_card();
});

function generateCardNo(x) {
	if(!x) { x = 16; }
	chars = "1234567890";
	no = "";
	for (var i=0; i<x; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		no += chars.substring(rnum,rnum+1);
	}
	return no;
}

function roundNumber(number, toref) {
	switch(toref) {
	    case 1:
	        var rn = formatDecimal(Math.round(number * 20)/20);
	        break;
	    case 2:
	        var rn = formatDecimal(Math.round(number * 2)/2);
	        break;
	    case 3:
	        var rn = formatDecimal(Math.round(number));
	        break;
	    case 4:
	        var rn = formatDecimal(Math.ceil(number));
	        break;
	    default:
	        var rn = number;
	}
	return rn;
}

function getNumber(x) {
	return accounting.unformat(x);
}

function formatQuantity(x) {
    return (x != null) ? '<div class="text-center">'+formatNumber(x, site.settings.qty_decimals)+'</div>' : '';
}

function formatNumber(x, d) {
    if(!d && d != 0) { d = site.settings.decimals; }
    if(site.settings.sac == 1) {
        return formatSA(parseFloat(x).toFixed(d));
    }
    return accounting.formatNumber(x, d, site.settings.thousands_sep == 0 ? ' ' : site.settings.thousands_sep, site.settings.decimals_sep);
}

function formatMoney(x, symbol) {
    if(!symbol) { symbol = ""; }
    if(site.settings.sac == 1) {
        return symbol+''+formatSA(parseFloat(x).toFixed(site.settings.decimals));
    }
    return accounting.formatMoney(x, symbol, site.settings.decimals, site.settings.thousands_sep == 0 ? ' ' : site.settings.thousands_sep, site.settings.decimals_sep, "%s%v");
}

function formatDecimal(x) {
	return parseFloat(parseFloat(x).toFixed(site.settings.decimals));
}

function is_valid_discount(mixed_var) {
	return (is_numeric(mixed_var) || (/([0-9]%)/i.test(mixed_var))) ? true : false;
}

function is_numeric(mixed_var) {
	var whitespace =
	" \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
	return (typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
		1)) && mixed_var !== '' && !isNaN(mixed_var);
}

function is_float(mixed_var) {
	return +mixed_var === mixed_var && (!isFinite(mixed_var) || !! (mixed_var % 1));
}

function currencyFormat(x) {
	if (x != null) {
		return formatMoney(x);
	} else {
		return '0';
	}
}

function formatSA (x) {
    x=x.toString();
    var afterPoint = '';
    if(x.indexOf('.') > 0)
       afterPoint = x.substring(x.indexOf('.'),x.length);
    x = Math.floor(x);
    x=x.toString();
    var lastThree = x.substring(x.length-3);
    var otherNumbers = x.substring(0,x.length-3);
    if(otherNumbers != '')
        lastThree = ',' + lastThree;
    var res = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;

    return res;
}

/* Sikeat */
function toFixed(num, fixed) {
    fixed = fixed || 0;
    fixed = Math.pow(10, fixed);
    return Math.floor(num * fixed) / fixed;
}

function floorFigure(figure, decimals){
     if(!decimals){
        decimals = 2;
    }
    return (Math.floor(figure * 100) / 100).toFixed(decimals);
};

function read_card() {
	$('.swipe').keypress( function (e) {
		e.preventDefault();
		var payid = $(this).attr('id'),
		id = payid.substr(payid.length - 1);
		var TrackData = $(this).val();
		if (e.keyCode == 13) {
			e.preventDefault();

			var p = new SwipeParserObj(TrackData);

			if(p.hasTrack1)
			{
		// Populate form fields using track 1 data
		var CardType = null;
		var ccn1 = p.account.charAt(0);
		if(ccn1 == 4)
			CardType = 'Visa';
		else if(ccn1 == 5)
			CardType = 'MasterCard';
		else if(ccn1 == 3)
			CardType = 'Amex';
		else if(ccn1 == 6)
			CardType = 'Discover';
		else
			CardType = 'Visa';

		$('#pcc_no_'+id).val(p.account);
		$('#pcc_holder_'+id).val(p.account_name);
		$('#pcc_month_'+id).val(p.exp_month);
		$('#pcc_year_'+id).val(p.exp_year);
		$('#pcc_cvv2_'+id).val('');
		$('#pcc_type_'+id).val(CardType);

	}
	else
	{
		$('#pcc_no_'+id).val('');
		$('#pcc_holder_'+id).val('');
		$('#pcc_month_'+id).val('');
		$('#pcc_year_'+id).val('');
		$('#pcc_cvv2_'+id).val('');
		$('#pcc_type_'+id).val('');
	}

	$('#pcc_cvv2_'+id).focus();
}

}).blur(function (e) {
	$(this).val('');
}).focus( function (e) {
	$(this).val('');
});
}

$.extend($.keyboard.keyaction, {
	enter : function(base) {
		base.accept();
	}
});

$('#clearData').click(function(event) {
	bootbox.confirm("Are you sure?", function(result) {
		if(result == true) {
			localStorage.clear();
			location.reload();
		}
	});
	return false;
});

$(document).ajaxStart(function(){
  $('#ajaxCall').show();
}).ajaxStop(function(){
  $('#ajaxCall').hide();
});

$(document).ready(function(){
	$('#myModal').on('hidden.bs.modal', function() {
		$(this).find('.modal-dialog').empty();
		$(this).removeData('bs.modal');
	});
	$('#myModal2').on('hidden.bs.modal', function () {
		$(this).find('.modal-dialog').empty();
		$(this).removeData('bs.modal');
		$('#myModal').css('zIndex', '1050');
		$('#myModal').css('overflow-y', 'scroll');
	});
	$('#myModal2').on('show.bs.modal', function () {
		$('#myModal').css('zIndex', '1040');
	});
	$('.modal').on('hidden.bs.modal', function() {
		$(this).removeData('bs.modal');
	});
	$('.modal').on('show.bs.modal', function () {
		$('#modal-loading').show();
		$('.blackbg').css('zIndex', '1041');
		$('.loader').css('zIndex', '1042');
	}).on('hide.bs.modal', function () {
		$('#modal-loading').hide();
		$('.blackbg').css('zIndex', '3');
		$('.loader').css('zIndex', '4');
	});
	$('#clearLS').click(function(event) {
        bootbox.confirm("Are you sure?", function(result) {
        if(result == true) {
            localStorage.clear();
            location.reload();
        }
        });
        return false;
    });
});

//$.ajaxSetup ({ cache: false, headers: { "cache-control": "no-cache" } });
if(pos_settings.focus_add_item != '') { shortcut.add(pos_settings.focus_add_item, function() { $("#add_item").focus(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.add_manual_product != '') { shortcut.add(pos_settings.add_manual_product, function() { $("#addManually").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.customer_selection != '') { shortcut.add(pos_settings.customer_selection, function() { $("#customer").select2("open"); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.add_customer != '') { shortcut.add(pos_settings.add_customer, function() { $("#add-customer").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.toggle_category_slider != '') { shortcut.add(pos_settings.toggle_category_slider, function() { $("#open-category").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.toggle_subcategory_slider != '') { shortcut.add(pos_settings.toggle_subcategory_slider, function() { $("#open-subcategory").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.cancel_sale != '') { shortcut.add(pos_settings.cancel_sale, function() { $("#reset").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.suspend_sale != '') { shortcut.add(pos_settings.suspend_sale, function() { $("#suspend").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.print_items_list != '') { shortcut.add(pos_settings.print_items_list, function() { $("#print_order").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.finalize_sale != '') { shortcut.add(pos_settings.finalize_sale, function() { $("#payment").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.today_sale != '') { shortcut.add(pos_settings.today_sale, function() { $("#today_sale").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.open_hold_bills != '') { shortcut.add(pos_settings.open_hold_bills, function() { $("#opened_bills").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.close_register != '') { shortcut.add(pos_settings.close_register, function() { $("#close_register").click(); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }
if(pos_settings.product_unit != '') { shortcut.add(pos_settings.product_unit, function() { $(".edit:last-child").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }

if(pos_settings.discount != '') { shortcut.add(pos_settings.discount, function() { $("#discount_shortcut:last-child").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }

if(pos_settings.show_search_item != '') { shortcut.add(pos_settings.show_search_item, function() { $("#search_details").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} ); }

shortcut.add("ESC", function() { $("#cp").trigger('click'); }, { 'type':'keydown', 'propagate':false, 'target':document} );

$(document).ready(function(){ $('#add_item').focus(); });
