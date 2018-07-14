<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
    product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0,
    tax_rates = <?php echo json_encode($tax_rates); ?>;
    $(document).ready(function () {
        if (__getItem('remove_slls')) {
            if (__getItem('slitems')) {
                __removeItem('slitems');
            }
            if (__getItem('sldiscount')) {
                __removeItem('sldiscount');
            }
            if (__getItem('sltax2')) {
                __removeItem('sltax2');
            }
            if (__getItem('slshipping')) {
                __removeItem('slshipping');
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
            if (__getItem('slcurrency')) {
                __removeItem('slcurrency');
            }
            if (__getItem('sldate')) {
                __removeItem('sldate');
            }
            if (__getItem('slsale_status')) {
                __removeItem('slsale_status');
            }
            if (__getItem('slpayment_status')) {
                __removeItem('slpayment_status');
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
			if (!__getItem('reference_no')) {
				__setItem('reference_no', '<?=$conumber?>');
			}
            __removeItem('remove_slls');
        }
        
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

        ItemnTotals();
        $('.bootbox').on('hidden.bs.modal', function (e) {
            $('#convert_from_items').focus();
            $('#convert_to_item').focus();
        });
		
        $("#convert_from_items").autocomplete({
            source: function (request, response) {
				var test = request.term;
				if($.isNumeric(test)){
					$.ajax({
						type: 'get',
						url: '<?= site_url('products/suggests'); ?>',
						dataType: "json",
						data: {
							term: request.term,
							warehouse_id: $("#slwarehouse").val()
						},
						success: function (data) {
							response(data);
						}
					});
				}else{
					$.ajax({
						type: 'get',
						url: '<?= site_url('products/suggestions'); ?>',
						dataType: "json",
						data: {
							term: request.term,
							warehouse_id: $("#slwarehouse").val()
						},
						success: function (data) {
							response(data);
						}
					});
				}
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#convert_from_items').focus();
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
                        $('#convert_from_items').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    // $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                	var rows        = "";
                    var opt = $("<select id=\"poption\" name=\"bom_from_items_uom\[\]\" class=\"form-control select rvariant\" />");
					if(ui.item.uom !== false) {
						$.each(ui.item.uom, function () {
							$("<option />", {value: this.id, qty: this.qty_unit, text: this.name}).appendTo(opt);
						});
					} else {
						$("<option />", {value: 0, qty:1, text: 'n/a'}).appendTo(opt);
						opt = opt.hide();
					}
					
					var text = '<div class="text-center">' + formatMoney(0) + '</div>';
                	rows = "<tr>"
	        				+ "<td>	<input type='hidden' value='"+ui.item.id+"' name='convert_from_items_id[]' />"
	        				+ " <input type='hidden' value='"+ui.item.code+"' name='convert_from_items_code[]' />"
	        				+ " <input type='hidden' value='"+ui.item.name+"' name='convert_from_items_name[]' />"
	        				+ ui.item.name+"("+ ui.item.code +")</td>"
                            + "<td>" + (opt.get(0).outerHTML) + "</td>"
							+ "<td><span class='qoh_raw text-center'>"+ (ui.item.qoh == undefined ? text : ui.item.qoh) +"</span></td>"
	        				+ "<td><input type='text' required='required' class='quantity qty_input form-control input-tip' value='' name='convert_from_items_qty[]' /></td>"
	        				+ '<td><i style="cursor:pointer;" title="Remove" id="1449892339552" class="fa fa-times tip pointer sldel"></i></td>'
						+ "</tr>";
                	$('#tbody-convert-from-items').append(rows);
                	$(this).val('');
					$('.quantity').change(function(){
						var tr 	 = $(this).parent().parent();
						var qty  = $(this).val();
						var cost = tr.find('.cost_raw').html();
						var total = formatMoney(cost * qty);
						tr.find('.total_raw').html(total);
					});
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
				
            }
        });
		
        $("#convert_to_item").autocomplete({
            source: function (request, response) {
				var test = request.term;
				if($.isNumeric(test)){
					$.ajax({
						type: 'get',
						url: '<?= site_url('products/suggests'); ?>',
						dataType: "json",
						data: {
							term: request.term,
							warehouse_id: $("#slwarehouse").val()
						},
						success: function (data) {
							response(data);
						}
					});
				}else{
					$.ajax({
						type: 'get',
						url: '<?= site_url('products/suggestions'); ?>',
						dataType: "json",
						data: {
							term: request.term,
							warehouse_id: $("#slwarehouse").val()
						},
						success: function (data) {
							response(data);
						}
					});
				}
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#convert_to_item').focus();
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
                        $('#convert_to_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    // $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                	var rows       = "";
                    var opt = $("<select id=\"poption\" name=\"bom_from_items_uom\[\]\" class=\"form-control select rvariant\" />");
					if(ui.item.uom !== false) {
						$.each(ui.item.uom, function () {
							$("<option />", {value: this.id, text: this.name}).appendTo(opt);
						});
					} else {
						$("<option />", {value: 0, text: 'n/a'}).appendTo(opt);
						opt = opt.hide();
					}
					var text = '<div class="text-center">' + formatMoney(0) + '</div>';
                	rows = "<tr>"
	        				+ "<td>	<input type='hidden' value='"+ui.item.id+"' name='convert_to_items_id[]' />"
	        				+ " <input type='hidden' value='"+ui.item.code+"' name='convert_to_items_code[]' />"
	        				+ " <input type='hidden' value='"+ui.item.name+"' name='convert_to_items_name[]' />"
	        				+ ui.item.name+"(" + ui.item.code  + ") </td>"
                            + "<td>" + (opt.get(0).outerHTML) + "</td>"
							+ "<td><span class='qoh_finish text-center'>"+ (ui.item.qoh == undefined?text: formatMoney(ui.item.qoh)) +"</span></td>"
	        				+ "<td><input type='text' required='required' class='quantity form-control input-tip' value='' name='convert_to_items_qty[]' /></td>"
	        				+ '<td><i style="cursor:pointer;" title="Remove" id="1449892339552" class="fa fa-times tip pointer sldel"></i></td>'
						+ "</tr>";
                	$('#tbody-convert-to-items').append(rows);
                	$(this).val('');
					$('.quantity').change(function(){
						var shipping = 0;
						var avg_cost = 0;
						var tr 	 	= $(this).parent().parent();
						var qty  	= $(this).val();
						var f_cost 	= tr.find('.cost_finish').val();
						var total_f	= f_cost * qty;
						
						//============== Get Total Raw Cost ================//
						var total_raw_cost = 0;
						$('.total_raw').each(function(){
							total_raw_cost += parseFloat($(this).html());
						});
						//====================== End =======================//
						
						//============== Get Total Raw Cost ================//
						var real_cost = 0;
						$('.cost_finish').each(function(){
							real_cost += parseFloat($(this).val());
						});
						//====================== End =======================//
						
						//================== Get All Qty ===================//
						var count = $('.qty_count').length;
						//====================== End =======================//
						
						shipping = (total_f / real_cost);
						avg_cost = (total_raw_cost * (shipping > 0? shipping:1) )/(shipping > 0? qty:count);
						
						total_cost = avg_cost * qty;
						tr.find('.cost_finish').html(formatMoney(avg_cost));
						tr.find('.total_finish').html(formatMoney(total_cost));
					});
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
                        } else {
                            $('#gc_details').html('<small>Card No: ' + data.card_no + '<br>Value: ' + data.value + ' - Balance: ' + data.balance + '</small>');
                            $('#gift_card_no').parent('.form-group').removeClass('has-error');
                        }
                    }
                });
            }
        });
		
        $('#convert_from_items').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });
		
        $('#convert_to_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });
		
		$('#bom_id').select2().on("change", function(e) {
            var id = $(this).val();			
            $.ajax({
                type: 'get',
                url: '<?= site_url('products/getDatabyBom_id'); ?>',
                dataType: "json",
                data: {
                    term: id,
					warehouse_id: $("#slwarehouse").val()
                },
                success: function (data) {				 
                    checkItem(data);	
					var boms_method = '<?= $Settings->boms_method?>';
					if(boms_method == 1){
						$('.qty_input').attr('readonly', 'readonly');
					}else if(boms_method == 2){
						$('.qty_count').attr("readonly",true);
					}
                }
            });
            
        });
		
		$('.quantity').change(function(){
			calculate();
		});
		
        function checkItem(data){
            var qty_deduct = 0;
            var qty_add = 0;
            var rows = '';  
			
			$('#tbody-convert-from-items').html("");
			$('#tbody-convert-to-items').html("");
			
            for(var i=0; i < data.length; i++){
                if(data[i].row.status == 'deduct'){
                    var idd = data[i].row.product_id;                    
                    qty_deduct = parseFloat(data[i].row.quantity);                   
                    $(".convert_from_items_id").each(function(){
                        var tr = $(this).parent().parent();
                        var itemid = tr.find(".convert_from_items_id").val();
                        var qty = tr.find(".quantity").val();
                        if(idd == itemid){
                            //qty_deduct = parseFloat(qty) * 2;							
                            $(this).closest('tr').remove();							
                        }
                    });	
					
					var opt = $("<select id=\"from_option\" name=\"convert_from_items_uom\[\]\" class=\"form-control select rvariant\" />");
					if(data[i].variant !== false) {
						$.each(data[i].variant, function () {
							if (data[i].row.option_id == this.id)
								$("<option />", {value: this.id, text: this.name, qty: this.qty_unit, selected: 'selected'}).appendTo(opt);
							else
								$("<option />", {value: this.id, text: this.name, qty: this.qty_unit}).appendTo(opt);
						});
					} else {
                        $("<option />", {value: 0, text: data[i].row.unit}).appendTo(opt);
						/*$("<option />", {value: 0, text: 'n/a'}).appendTo(opt);
						opt = opt.hide();*/
					}
					
                    rows = "<tr>"
                        + "<td> <input type='hidden' value='"+data[i].row.product_id+"' class='convert_from_items_id' name='convert_from_items_id[]' />"
                        + " <input type='hidden' value='"+data[i].row.product_code+"' class='procode' name='convert_from_items_code[]' />"
                        + " <input type='hidden' value='"+data[i].row.product_name+"' name='convert_from_items_name[]' />"
                        + data[i].row.product_name+" ("+ data[i].row.product_code +")</td>"
                        + "<td>" + (opt.get(0).outerHTML) + "</td>"
						+ "<td><div class='qoh_raw'>"+ (data[i].row.qoh == undefined ? 0 : formatQuantity2(data[i].row.qoh)) +"</div></td>"
                        + "<td><input type='text' required='required' class='quantity qty_input qty_to form-control input-tip' value='"+(qty_deduct)+"' name='convert_from_items_qty[]' /><input type='hidden' required='required' class='quantity hidden_qty_input form-control input-tip' value='"+(qty_deduct)+"' name='hidden_convert_from_items_qty[]' /></td>"
                        + '<td><i style="cursor:pointer;" title="Remove" id="1449892339552" class="fa fa-times tip pointer sldel"></i></td>'
						+ "</tr>";
						
					$('#tbody-convert-from-items').append(rows);
				}       
				else if(data[i].row.status == 'add'){
					var idd = data[i].product_id;
                    qty_add = parseFloat(data[i].row.quantity);     
					//$('#tbody-convert-to-items').html("");
                    $(".convert_to_items_id").each(function(){
                        var tr = $(this).parent().parent();
                        var itemid = tr.find(".convert_to_items_id").val();
                        var qty = tr.find(".quantity").val();
                        if(idd == itemid){
                            $(this).closest('tr').remove();							
                        }
                    });	
					var opt = $("<select id=\"to_option\" name=\"convert_to_items_uom\[\]\" class=\"form-control select rvariant\" />");

					if(data[i].variant !== false) {
						$.each(data[i].variant, function () {
							if (data[i].row.option_id == this.id)
								$("<option />", {value: this.id, text: this.name, selected: 'selected'}).appendTo(opt);
							else
								$("<option />", {value: this.id, text: this.name}).appendTo(opt);
						});
					} else {
						$("<option />", {value: 0, text: data[i].row.unit}).appendTo(opt);
					}
                    rows = "<tr>"
                        + "<td> <input type='hidden' value='"+data[i].row.product_id+"' class='convert_to_items_id' name='convert_to_items_id[]' />"
                        + " <input type='hidden' value='"+data[i].row.product_code+"' class='procodes' name='convert_to_items_code[]' />"
                        + " <input type='hidden' value='"+data[i].row.product_name+"' name='convert_to_items_name[]' />"
                        + data[i].row.product_name+" ("+ data[i].row.product_code +")</td>"
                        + "<td>" + (opt.get(0).outerHTML) + "</td>"
						+ "<td><span class='qoh_finish'>"+ (data[i].row.qoh == undefined ? 0 : formatQuantity2(data[i].row.qoh)) +"</span></td>"
                        + "<td><input type='text' required='required' class='quantity qty_output form-control input-tip qty_count' value='"+qty_add+"' name='convert_to_items_qty[]' /><input type='hidden' required='required' class='hide_quantity qty_output form-control input-tip' value='"+qty_add+"' name='hide_convert_to_items_qty[]' /></td>"
                        + '<td><i style="cursor:pointer;" title="Remove" id="1449892339552" class="fa fa-times tip pointer sldel"></i></td>'
                    + "</tr>";
					
                    $('#tbody-convert-to-items').append(rows);
					
                }				
			}			
            calculate();
        }
		
		function calculate(){
			var boms_method = '<?= $Settings->boms_method?>';
			$(".qty_input").keyup(function(){
				var list 		  = [];	
				var sumQty 		  = 0;	
				var get 		  = 0;
				var qtyHidden 	  = 0;
				var curProductQty = 0;
				$(".convert_to_items_id").each(function(){
					var tr 		= $(this).parent().parent();
					var itemid 	= tr.find(".convert_to_items_id").val();
					var gqty 	= tr.find(".hide_quantity").val();
					var pcode 	= tr.find(".procodes").val();
					var obj 	= {procode:pcode,qty:gqty};
					list.push(obj);	
					sumQty 		= sumQty + Number(gqty);						
				});
				
				$('.hidden_qty_input').each(function(){
					qtyHidden 		+= parseFloat($(this).val());
				});
				$('.qty_to').each(function(){
					curProductQty 	+= parseFloat($(this).val());
				});
				var newQty 			= 0;
				if(curProductQty == "" || curProductQty == 0){
					var i			= 0;
					$('.qty_count').each(function() {
						var temp 	= Number(list[i].qty);
						temp 		= 0;				
						$(this).val(formatPurDecimal(temp));
						i++;
					});		
				}
				else
				{
					if(boms_method == 2){
						newQty 		= curProductQty / qtyHidden;	
					}
					
					$('.qty_count').each(function(i) {
						var temp 	= Number(list[i].qty);
						var newTemp = 0;
						if(boms_method == 2){
							newTemp = formatPurDecimal(newQty)*temp;
							$(this).val(formatPurDecimal(newTemp));
						}			
						
					});		
				}
				
				//================ Auto Cost ==============//
                var tr = $(this).parent().parent();
                var qty = tr.find(".qty_input").val();
				var qty  = $(this).val();
				var cost = tr.find('.cost_raw').html();
				var total = formatMoney(cost * qty);
				tr.find('.total_raw').html(total);
            });				
			
			$(".qty_output").keyup(function(){
		        var list 		  = [];	
				var sumQty 		  = 0;	
				var get 		  = 0;
				var qtyHidden 	  = 0;
				var curProductQty = 0;
				$(".convert_from_items_id").each(function(){
					var tr 		= $(this).parent().parent();
					var itemid 	= tr.find(".convert_from_items_id").val();
					var gqty 	= tr.find(".hidden_qty_input").val();
					var pcode 	= tr.find(".procode").val();
					var obj 	= {procode:pcode,qty:gqty};
					list.push(obj);	
					sumQty 		= sumQty + Number(gqty);						
				});
				$('.hide_quantity').each(function(){
					qtyHidden 		+= parseFloat($(this).val());
				});
				$('.qty_count').each(function(){
					curProductQty 	+= parseFloat($(this).val());
				});
				var newQty 			= 0;
				if(curProductQty == "" || curProductQty == 0){
					var i			= 0;
					$('.qty_input').each(function() {
						var temp 	= Number(list[i].qty);
						temp 		= 0;				
						$(this).val(formatPurDecimal(temp));
						i++;
					});		
				}
				else
				{
					if(boms_method == 1){
						newQty 		= curProductQty / qtyHidden;	
					}
					var i 			= 0;
					$('.qty_input').each(function() {
						var temp 	= Number(list[i].qty);
						var newTemp = 0;
						if(boms_method == 1){
							newTemp = formatPurDecimal(newQty)*temp;
							$(this).val(formatPurDecimal(newTemp));
						}			
						
						i++;
					});		
				}
				
				var shipping = 0;
				var avg_cost = 0;
				var tr 	 	 = $(this).parent().parent();
				var qty  	 = $(this).val();
				var f_cost   = tr.find('.cost_finish').val();
				var total_f	 = f_cost * qty;
				
				//============== Get Total Raw Cost ================//
				var total_raw_cost = 0;
				var qty_raw        = new Array();
				var i = 0;
				var a = 0;
				$('.qty_input').each(function(){
					qty_raw[i] = $(this).val();
					i++;
				});
				var cost_arr	= new Array();
				$('.cost_raw').each(function(){
					cost_arr[a]	 = $(this).html();
					a++;
				});
				
				//====================== End =======================//
				
				//============== Get Total Raw Cost ================//
				var cost_finish = 0;
				$('.cost_finish').each(function(){
					cost_finish += parseFloat($(this).val());
				});
				
				$.each(qty_raw, function(i,value){
					total_raw_cost += parseFloat(cost_arr[i]) * parseFloat(value);
				});
				//====================== End =======================//
				
				//================== Get All Qty ===================//
				var count = $('.qty_count').length;
				//====================== End =======================//
				
				shipping 	= (total_f / cost_finish);
				avg_cost 	= (total_raw_cost * (shipping > 0? shipping:1) )/(shipping > 0? qty:count);
				total_cost 	= avg_cost * qty;
				tr.find('.cost_finish').html(formatMoney(avg_cost));
				tr.find('.total_finish').html(formatMoney(total_cost));
				$(".qty_input").trigger('change');
			});		
		}
		
		$('#bth_convert_items').click(function(e){
			var qual = 0;
			
			//================== Get Stock On Hand ======================//
			var qoh  = new Array();
			var i 	 = 0;
			$('.qoh_raw').each(function(){
				qoh[i] = $(this).html().replace(/,/g, '');
				i++;
			});
			//========================= End ============================//
			
			//================== Get variants Qty ======================//
			var option = new Array();
			var o      = 0;
			$('.rvariant').each(function(){
				option[o] = $(this).find('option:selected').attr('qty');
				o++;
			});
			//========================= End ============================//
			
			//================== Get Quantity Input ====================//
			var qty  = new Array();
			var a 	 = 0;
			$('.qty_input').each(function(){
				var inputQ = $(this).val();
				qty[a] = parseFloat(inputQ) * parseFloat(option[a]);
				a++;
			});
			//========================= End ============================//
			
			//================== Comparing Quantity ====================//
			qual = new Array();
			$.each(qoh, function(i,value){
				console.log(parseFloat(value) < parseFloat(qty[i]));
				if(parseFloat(value) < parseFloat(qty[i])){
					qual[i] = 1;
				}else{
					qual[i] = 0;
				}
			});
			
			if(parseFloat(qual) > 0){
				bootbox.alert('<?= lang('raw_quantity_invalid') ?>');
				e.preventDefault();
			}
			//========================= End ============================//

			//return false;
		});
		
		$(document).on('change', '#from_option',function(){
			var qty_unit = $("option:selected", this).attr("qty");
		});
		
		$("#reference_no").attr('readonly', true);
		$('#ref_st').on('ifChanged', function() {
			if ($(this).is(':checked')) {
				// $("#reference_no").prop('disabled', false);
                $("#reference_no").attr('readonly', false);
				$("#reference_no").val("");
			}else{ 
				$("#reference_no").prop('disabled', true);
				var temp = $("#temp_reference_no").val();
				$("#reference_no").val(temp);
			}
		});
	});
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('convert_product'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("products/items_convert", $attrib);
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin || $Settings->allow_change_date == 1) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "sldate"); ?>
                                    <?php echo form_input('sldate', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>
						<div class="col-md-4">
                            <div class="form-group">
                                <?= lang("reference_no", "reference_no"); ?>


									<div class="form-group">
										<div class="input-group">  
											<?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $conumber),'class="form-control input-tip" id="reference_no"'); ?>
											<input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $conumber?$conumber:"" ?>" />
											<input type="hidden"  name="order_id"  id="order_id" value="" />
											<input type="hidden"  name="quote_id"  id="quote_id" value="" />
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
						
						<div class="col-md-4">
                            <div class="form-group">
                                <?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>
                                        
                                    <?= lang("warehouse", "slwarehouse"); ?>
                                    <?php
                                    $wh[''] = '';
                                    foreach ($warehouses as $warehouse) {
                                        $wh[$warehouse->id] = $warehouse->code .'-'.$warehouse->name;
                                    }
                                    echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="slwarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');
                                } else {
									echo lang("warehouse", "slwarehouse");
									$whu[''] = '';
									foreach ($warehouses_by_user as $warehouse_by_user) {
										$whu[$warehouse_by_user->id] = $warehouse_by_user->code .'-'.$warehouse_by_user->name;
									}

									$default_wh = explode(',', $this->session->userdata('warehouse_id'));
									echo form_dropdown('warehouse', $whu, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $default_wh[0]), 'id="slwarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');
                                } ?>
                            </div>
                        </div> 
						
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("Boms"); ?></label>
                                <?php
                                $boms[""] = "";
								if($bom == null){
									
								}else{
									foreach ($bom as $bomss) {
										$boms[$bomss->id] = $bomss->name;
									}
								}
                                echo form_dropdown('bom_id', $boms, (isset($_POST['bom_id']) ? $_POST['bom_id'] : ""), 'class="form-control" id="bom_id" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("bom") . '"');
                                ?>
                            </div>
                        </div>
						
						<div class="col-md-12">
							<div class="form-group">
								<?= lang("note", "ponote"); ?>
								
								<?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="ponote" style="margin-top: 10px; height: 100px;"'); ?>
							</div>
						</div>
					</div>
					<!-- convert from items -->
					<div class="col-md-12" id="sticker">
						<div class="well well-sm">
							<div class="form-group" style="margin-bottom:0;">
								<div class="input-group wide-tip">
									<div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
										<i class="fa fa-2x fa-barcode addIcon"></i></div>
									<?php echo form_input('convert_from_items', '', 'class="form-control input-lg" id="convert_from_items" placeholder="' . lang("add_product_to_order") . '"'); ?>                                        
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<!-- table show convert from items -->
					<div class="col-md-12">
						<div class="control-group table-group">
							<label class="table-label"><?= lang("convert_items_from"); ?> *</label>

							<div class="controls table-controls table-responsive">
								<table id="cfTable" class="table items table-striped table-bordered table-condensed table-hover">
									<thead>
										<tr>
											<th class="col-md-7"><?= lang("product_name") . " (" . lang("product_code") . ")"; ?></th>
											<th class="col-md-2"  style="width: 250px;"><?= lang("unit"); ?></th>
											<th class="col-md-1"  style="width: 250px;"><?= lang("qoh"); ?></th>
											<th class="col-md-2"><?= lang("quantity"); ?></th>
											<th style="width: 30px !important; text-align: center;">
												<i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
											</th>
										</tr>
									</thead>
									<tbody id="tbody-convert-from-items"></tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- Select Convert to Items -->
					<div class="col-md-12" id="sticker">
						<div class="well well-sm">
							<div class="form-group" style="margin-bottom:0;">
								<div class="input-group wide-tip">
									<div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
										<i class="fa fa-2x fa-barcode addIcon"></i></div>
									<?php echo form_input('convert_to_item', '', 'class="form-control input-lg" id="convert_to_item" placeholder="' . lang("add_product_to_order") . '"'); ?>                                     
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<!-- table convert to items -->
					<div class="col-md-12">
						<div class="control-group table-group">
							<label class="table-label"><?= lang("convert_items_to"); ?> *</label>

							<div class="controls table-controls table-responsive">
								<table id="ctTable" class="table items table-striped table-bordered table-condensed table-hover">
									<thead>
										<tr>
											<th class="col-md-7"><?= lang("product_name") . " (" . lang("product_code") . ")"; ?></th>
											<th class="col-md-2"  style="width: 250px;"><?= lang("unit"); ?></th>
											<th class="col-md-1"  style="width: 250px;"><?= lang("qoh"); ?></th>
											<th class="col-md-2"><?= lang("quantity"); ?></th>
											<th style="width: 30px !important; text-align: center;">
												<i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
											</th>
										</tr>
									</thead>
									<tbody id="tbody-convert-to-items"></tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- Button Submit -->
					<div class="col-md-12">
						<div class="fprom-group"><?php echo form_submit('add_sale', lang("submit"), 'id="bth_convert_items" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
							<button type="button" name="convert_items" class="btn btn-danger" id="reset"><?= lang('reset') ?></button></div>
					</div>
                </div>
                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#slbiller').change(function(){
            billerChange();
            $("#slwarehouse").select2().empty();
        });

        var $biller = $("#slbiller");
        $(window).load(function(){
            billerChange();
        });

        function billerChange(){
            var id = $biller.val();

            $.ajax({
                url: '<?= base_url() ?>auth/getWarehouseByProject/'+id,
                dataType: 'json',
                success: function(result){
                    <?php if ($Owner || $Admin) { ?>
                    __setItem('default_warehouse', '<?= $Settings->default_warehouse; ?>');
                    <?php } else { ?>
                    __setItem('default_warehouse', '<?= $default_wh[0] ?>');
                    <?php } ?>
                    var default_warehouse = __getItem('default_warehouse');

                    if(result == null || result == ''){
                        console.log(result);
                    }else{
                        $("#slwarehouse").empty();
                        $.each(result, function(i,val){
                            var b_id = val.id;
                            var code = val.code;
                            var name = val.name;
                            var opt = '<option value="' + b_id + '">' +code+'-'+ name + '</option>';
                            $("#slwarehouse").append(opt);
                        });
                    }

                    $('#slwarehouse').val($('#slwarehouse option:first-child').val()).trigger('change');
                    $("#slwarehouse").select2("val", default_warehouse);

                    if (default_warehouse) {
                        $('#slwarehouse').select2('val', default_warehouse);
                    }
                    $('#slwarehouse option[selected="selected"]').each(
                        function() {
                            $(this).removeAttr('selected');
                        }
                    );
                    if(slwarehouse = __getItem('slwarehouse')){
                        $('#slwarehouse').select2("val", slwarehouse);
                    }

                }
            });

            $.ajax({
                url: '<?= base_url() ?>sales/getReferenceByProject/con/'+id,
                dataType: 'json',
                success: function(data){
                    $("#reference_no").val(data);
                    $("#temp_reference_no").val(data);
                }
            });
        }

        var $warehouse = $('#slwarehouse');
        $warehouse.change(function (e) {
            __setItem('slwarehouse', $(this).val());
        });

        function requireQty(){
            var result = true;
            $(".quantity").each(function(){
                if($(this).val() === null || $(this).val() === ""){
                    result = false;
                }
            });
            return result;
        }
        $('#gccustomer').select2({
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
        $("#bth_convert_items").click(function(){
        	if($('.quantity').length < 1){
        		bootbox.alert('<?= lang('please_add_items_below') ?>');
        		return false;
        	}
            if($('#tbody-convert-from-items tr').length < 1){
                bootbox.alert('<?= lang('please_add_items_below') ?>');
                return false;   
            }
            if($('#tbody-convert-to-items tr').length < 1){
                bootbox.alert('<?= lang('please_add_items_below') ?>');
                return false;   
            }
            var requireField = requireQty();
    		if(requireField === false){
    			bootbox.alert('<?= lang('quantity_require') ?>');
    			return false;
    		}
        });
        $('#genNo').click(function () {
            var no = generateCardNo();
            $(this).parent().parent('.input-group').children('input').val(no);
            return false;
        });
    });
	
    /***** Sikeat Remove Convert Item *****/
    $(document).on('click', '.sldel', function () {
        var row = $(this).closest('tr');
        row.remove();
    });
</script>
