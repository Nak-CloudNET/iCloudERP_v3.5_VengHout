<style>
	.form-group .select2-container {
	  position: relative;
	  z-index: 2;
	  float: left;
	  width: 100%;
	  margin-bottom: 0;
	  display: table;
	  table-layout: fixed;
	}
</style>
<script type="text/javascript">
    var count = 1, an = 1, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, surcharge = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
		
		$(window).load(function(){
			<?php if($Admin || $Owner){ ?>
			billerChange();
			<?php } ?>
		});

    $(document).ready(function () {
        <?php if ($inv) { ?>
        //__setItem('redate', '<?= $this->erp->hrld($inv->date) ?>');
        __setItem('slcustomer', '<?= $inv->customer_id ?>');
        __setItem('reref', '<?= $reference ?>');
        __setItem('renote', '<?= $this->erp->decode_html($inv->note); ?>');
		__setItem('iref', '<?= $inv->reference_no ?>');
		__setItem('slbiller', <?= $inv->biller_id ?>);
        __setItem('reitems', JSON.stringify(<?= $inv_items; ?>));
        __setItem('rediscount', '<?= $inv->order_discount_id ?>');
		__setItem('sldiscount', '<?= $inv->order_discount_id ?>');
		__setItem('slshipping', '<?= $inv->shipping ?>');
        __setItem('retax2', '<?= $inv->order_tax_id ?>');
        __setItem('return_surcharge', '0');
        <?php } ?>
        <?php if($this->input->get('customer')) { ?>
        if (!__getItem('slitems')) {
            __setItem('slcustomer', <?=$this->input->get('customer');?>);
        }
        <?php } ?>

        if (!__getItem('redate')) {
            $("#redate").datetimepicker({
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
        $(document).on('change', '#redate', function (e) {
            __setItem('redate', $(this).val());
        });
        if (redate = __getItem('redate')) {
            $('#redate').val(redate);
        }

        if (reref = __getItem('reref')) {
            $('#reref').val(reref);
        }
		if(iref = __getItem('iref')) {
			$('#iref').val(iref);
		}
        if (rediscount = __getItem('rediscount')) {
            $('#rediscount').val(rediscount);
        }
		if (sldiscount = __getItem('sldiscount')) {
            $('#sldiscount').val(sldiscount);
        }
		if (slshipping = __getItem('slshipping')) {
			slshipping = parseFloat(slshipping);
            $('#slshipping').val(formatMoney(slshipping));
        }
        if (retax2 = __getItem('retax2')) {
            $('#retax2').val(retax2);
        }
		if (sltax2 = __getItem('retax2')) {
            $('#sltax2').val(sltax2);
        }
        if (return_surcharge = __getItem('return_surcharge')) {
            $('#return_surcharge').val(return_surcharge);
        }
		$(document).on('change', '#slbiller', function (e) {
            __setItem('slbiller', $(this).val());
        });
        if (slbiller = __getItem('slbiller')) {
            $('#slbiller').val(slbiller);
        }
		 
		var $customer = $('#slcustomer');
		$customer.change(function (e) {
			__setItem('slcustomer', $(this).val());
			//$('#slcustomer_id').val($(this).val());
		});
        $('#customer1').change(function(){
            checkDeposit();
            $('#amount_1').trigger('change');
        });

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
                            alert('<?=lang('invalid_customer')?>');
                        } else if (data.id !== null && data.id !== customer_id) {
                            $('#deposit_no_1').parent('.form-group').addClass('has-error');
                            alert('<?=lang('this_customer_has_no_deposit')?>');
                        } else {
                            var deposit_amount =  ((data.dep_amount==null)? 0:data.dep_amount);
                            var deposit_balance = ((data.balance==null)? 0:data.balance);
                            $('#dp_details').html('<small>Customer Name: ' + (data.company?data.company:data.name) + '<br/>Amount: <span class="deposit_total_amount">' + data.balance + '</span> - Balance: <span class="deposit_total_balance">' + deposit_balance + '</span></small>');
                            $('#amount_1').attr('deposit_balance', deposit_balance);
                            $('#deposit_no').parent('.form-group').removeClass('has-error');
                        }
                    }
                });
            }
        }

        $('#amount_1').on('keyup change', function () {
            var us_paid = parseFloat($('#amount_1').val()-0);
            var p_val = $('#paid_by_1').val();
            var new_deposit_balance = 0;
            if(p_val == 'deposit') {
                var deposit_balance = parseFloat($('#amount_1').attr('deposit_balance')-0);
                new_deposit_balance = deposit_balance + us_paid;
                $(".deposit_total_balance").text(formatDecimal(new_deposit_balance));
            }
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
		} else {
			nsCustomer();
		}
		 
        if (__getItem('reitems')) {
            loadItems();
        }
		$("#slbiller").change(function(){
			<?php if($Admin || $Owner){ ?>
			billerChange();
			<?php } ?>
			//$("#slwarehouse").select2("val", "<?=$Settings->default_warehouse;?>");
			$('#slwarehouse').val($('#slwarehouse option:first-child').val()).trigger('change');
		});
        $(document).on('change', '.paid_by', function () {
            var p_val = $(this).val();
            //__setItem('paid_by', p_val);
            $('#rpaidby').val(p_val);
            if (p_val == 'cash') {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').show();
                //$('#amount_1').focus();
            } else if (p_val == 'CC') {
                $('.pcheque_1').hide();
                $('.pcash_1').hide();
                $('.pcc_1').show();
                $('#pcc_no_1').focus();
            } else if (p_val == 'Cheque') {
                $('.pcc_1').hide();
                $('.pcash_1').hide();
                $('.pcheque_1').show();
                $('#cheque_no_1').focus();
            } else {
                $('.pcheque_1').hide();
                $('.pcc_1').hide();
                $('.pcash_1').hide();
            }
            if(p_val == 'deposit') {
                $('.dp').show();
                $('#payment_reference_no').val('<?= $deposit_ref?>');
                $('#bank_acc').hide();
                $('#customer1').trigger('change');
            }else{
                $('.dp').hide();
                $('#payment_reference_no').val('<?= $payment_ref?>');
                $('#bank_acc').show();
                $('#dp_details').html('');
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
        });
        /* ------------------------------
         * Sell Gift Card modal
         ------------------------------- */

        $(document).on('click', '#sellGiftCard', function (e) {
            $('#gcvalue').val($('#amount_1').val());
            $('#gccard_no').val(generateCardNo());
            $('#gcModal').appendTo("body").modal('show');
            return false;
        });
        $('#gccustomer').val(<?=$inv->customer_id?>).select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: "<?= site_url('customers/getCustomer') ?>/" + $(element).val(),
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
        $(document).on('click', '#add_return', function(){
            if ($('.paid_by').select2('val') != "deposit") {
                if($('#bank_account_1').val() == 0){
                    bootbox.alert('<?= lang('bank_account_x_select'); ?>');
                    return false;
                }
            }
        });

        $(document).on('click', '#noCus', function (e) {
            e.preventDefault();
            $('#gccustomer').select2('val', '');
            return false;
        });

        $('#genNo').click(function () {
            var no = generateCardNo();
            $(this).parent().parent('.input-group').children('input').val(no);
            return false;
        });

        $(document).on('click', '#addGiftCard', function (e) {
            var mid = (new Date).getTime(),
                gccode = $('#gccard_no').val(),
                gcname = $('#gcname').val(),
                gcvalue = $('#gcvalue').val(),
                gccustomer = $('#gccustomer').val(),
                gcexpiry = $('#gcexpiry').val() ? $('#gcexpiry').val() : '',
                gcprice = parseFloat($('#gcprice').val());
            if (gccode == '' || gcvalue == '' || gcprice == '' || gcvalue == 0 || gcprice == 0) {
                $('#gcerror').text('Please fill the required fields');
                $('.gcerror-con').show();
                return false;
            }

            var gc_data = [];
            gc_data[0] = gccode;
            gc_data[1] = gcvalue;
            gc_data[2] = gccustomer;
            gc_data[3] = gcexpiry;
            if (typeof slitems === "undefined") {
                var slitems = {};
            }

            $.ajax({
                type: 'get',
                url: site.base_url + 'sales/sell_gift_card',
                dataType: "json",
                data: {gcdata: gc_data},
                success: function (data) {
                    if (data.result === 'success') {
                        $('#gift_card_no').val(gccode);
                        $('#gc_details').text('<?=lang('gift_card_added')?>');
                        $('#gcModal').modal('hide');
                    } else {
                        $('#gcerror').text(data.message);
                        $('.gcerror-con').show();
                    }
                }
            });
            return false;
        });
        var old_row_qty;
        $(document).on("focus", '.rquantity', function () {
            old_row_qty = $(this).val();
        }).on("change", '.rquantity', function () {
            var row = $(this).closest('tr');
            var new_qty = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');
            if (!is_numeric(new_qty) || (new_qty > reitems[item_id].row.bqty)) {
                $(this).val(old_row_qty);
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                return false;
            }
            if(new_qty > reitems[item_id].row.bqty) {
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                $(this).val(old_row_qty);
                return false;
            }
            reitems[item_id].row.qty = new_qty;
            __setItem('reitems', JSON.stringify(reitems));
            loadItems();
        });
		var old_discount;
        $(document).on("focus", '#sldiscount', function () {
            old_discount = $(this).val() ? $(this).val() : '0';
        }).on("change", '#sldiscount', function () {
            var new_discount = $(this).val() ? $(this).val() : '0';
            if (!is_valid_discount(new_discount)) {
                $(this).val(new_discount);
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                return;
            }
            __setItem('rediscount', new_discount);
            loadItems();
        });
		var old_shipping;
        $(document).on("focus", '#slshipping', function () {
            old_shipping = $(this).val() ? $(this).val() : '0';
        }).on("change", '#slshipping', function () {
            var new_shipping = $(this).val() ? $(this).val() : '0';
            if (!is_valid_discount(new_shipping)) {
                $(this).val(new_shipping);
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                return;
            }
			slshipping = parseFloat(new_shipping);
            __setItem('slshipping', new_shipping);
            loadItems();
        });
		$('#sltax2').on('change', function() {
			var sltax2 = $(this).val();
			__setItem('retax2', sltax2);
            loadItems();
		});
        var old_surcharge;
        $(document).on("focus", '#return_surcharge', function () {
            old_surcharge = $(this).val() ? $(this).val() : '0';
        }).on("change", '#return_surcharge', function () {
            var new_surcharge = $(this).val() ? $(this).val() : '0';
            if (!is_valid_discount(new_surcharge)) {
                $(this).val(new_surcharge);
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                return;
            }
            __setItem('return_surcharge', JSON.stringify(new_surcharge));
            loadItems();
        });
        $(document).on('click', '.redel', function () {
            var row = $(this).closest('tr');
            var item_id = row.attr('data-item-id');
            delete reitems[item_id];
            row.remove();
            if(reitems.hasOwnProperty(item_id)) { } else {
                __setItem('reitems', JSON.stringify(reitems));
                loadItems();

            }
        });
		var old_row_piece;
        $(document).on("focus", '.piece', function () {
            old_row_piece = $(this).val();
        }).on("change", '.piece', function () {
            var row = $(this).closest('tr');
            var new_piece = parseFloat($(this).val()),
                item_id = row.attr('data-item-id');
            if (!is_numeric(new_piece) || (new_piece > (reitems[item_id].row.bqty/reitems[item_id].row.wpiece))) {
                $(this).val(old_row_piece);
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                return false;
            }
            if(new_piece > (reitems[item_id].row.bqty/reitems[item_id].row.wpiece)) {
                bootbox.alert('<?= lang('unexpected_value'); ?>');
                $(this).val(old_row_piece);
                return false;
            }
			reitems[item_id].row.qty = formatDecimal(new_piece * reitems[item_id].row.wpiece);
            reitems[item_id].row.piece = new_piece;
            __setItem('reitems', JSON.stringify(reitems));
            loadItems();
        });
    });
    //localStorage.clear();
    function loadItems() {
		 
        if (__getItem('reitems')) {
            total = 0;
            count = 1;
            an = 1;
            product_tax = 0;
            invoice_tax = 0;
            product_discount = 0;
            order_discount = 0;
            total_discount = 0;
            surcharge = 0;

            $("#reTable tbody").empty();
            reitems = JSON.parse(__getItem('reitems'));
			var no=1;
            $.each(reitems, function () {
                var item = this;
                var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
                reitems[item_id] = item;
				
				//alert(JSON.stringify(item.row.unit));

                var item_type = item.row.type, 
				product_id 		= item.row.id, 
				combo_items 	= item.combo_items, 
				sale_item_id 	= item.row.sale_item_id,
				item_option 	= item.row.option, 
				item_price 		= item.row.price, 
				item_qty 		= item.row.qty, 
				item_aqty 		= item.row.quantity, 
				item_tax_method = item.row.tax_method, 
				item_ds 		= item.row.discount, 
				expiry_date 	= item.row.expiry, 
				expiry_id 		= item.row.expiry_id, 
				item_discount 	= 0, item_option = item.row.option, 
				item_code 		= item.row.code, item_serial = item.row.serial, 
				item_name 		= item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
				var real_unit_price = Number(item.row.real_unit_price);
                var unit_price 		= Number(item.row.real_unit_price);
				var piece 			= item.row.piece;
				var wpiece 			= item.row.wpiece;
				var pn 				= '';
				
				item_discount = (item.row.item_discount ? (item.row.item_discount/item.row.oqty) : 0);
				
				unit_price = unit_price - item_discount;
                product_discount += parseFloat(item_discount*item_qty);
				unit_price = formatDecimal(unit_price);
				
                var pr_tax = item.tax_rate;
                var pr_tax_val = 0, pr_tax_rate = 0;
                if (site.settings.tax1 == 1) {
                    if (pr_tax !== false) {
                        if (pr_tax.type == 1) {

                            if (item_tax_method == '0') {
                                pr_tax_val = (((unit_price) * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate)));
                                pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
                            } else {
                                pr_tax_val = (((unit_price) * parseFloat(pr_tax.rate)) / 100);
                                pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
                            }

                        } else if (pr_tax.type == 2) {

                            pr_tax_val = parseFloat(pr_tax.rate);
                            pr_tax_rate = pr_tax.rate;

                        }
                        product_tax += pr_tax_val * item_qty;
                    }
                }
                item_price = item_tax_method == 0 ? formatDecimal(real_unit_price-pr_tax_val, 4) : formatDecimal(unit_price);
				iprice = item_tax_method == 0 ? formatDecimal(real_unit_price-pr_tax_val, 4) : formatDecimal(real_unit_price);
              
			  
				if (item_tax_method == 0) {
					unit_price = formatPurDecimal(real_unit_price);
			
				}else{
					unit_price = formatPurDecimal(unit_price+item_discount);
					
				}
			  
				var sel_opt = '';
                $.each(item.options, function () {
                    if(this.id == item_option) {
                        sel_opt = this.name;
                    }
                });
				
                var row_no = (new Date).getTime();
                var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');
				tr_html ='<td class="text-center"><span class="text-center">#'+no+'</span></td>';
                
                if(site.settings.show_code == 1 && site.settings.separate_code == 1) {
					tr_html+='<td class="text-left"><span class="text-left">'+ item_code +'</span></td>';
					tr_html += '<td><input name="sale_item_id[]" type="hidden" class="rsiid" value="' + sale_item_id + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_cost[]" type="hidden" class="product_cost" value="' + item.row.cost + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><input name="product_note[]" type="hidden" class="rnote" value="' + pn + '"><span class="sname" id="name_' + row_no + '">' + item_name + ''+(sel_opt != '' ? ' ('+sel_opt+')' : '')+'</span></td>';
				}
				if(site.settings.show_code == 1 && site.settings.separate_code == 0) {
					tr_html += '<td><input name="sale_item_id[]" type="hidden" class="rsiid" value="' + sale_item_id + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><input name="product_note[]" type="hidden" class="rnote" value="' + pn + '"><span class="sname" id="name_' + row_no + '">' + item_name + ''+(sel_opt != '' ? ' ('+sel_opt+')' : '')+'</span></td>';
				}
				if(site.settings.show_code == 0) {
					tr_html += '<td><input name="sale_item_id[]" type="hidden" class="rsiid" value="' + sale_item_id + '"><input name="product_id[]" type="hidden" class="rid" value="' + product_id + '"><input name="product_type[]" type="hidden" class="rtype" value="' + item_type + '"><input name="product_code[]" type="hidden" class="rcode" value="' + item_code + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="product_option[]" type="hidden" class="roption" value="' + item_option + '"><input name="product_note[]" type="hidden" class="rnote" value="' + pn + '"><span class="sname" id="name_' + row_no + '">' + item_name + ''+(sel_opt != '' ? ' ('+sel_opt+')' : '')+'</span></td>';
				}
				if(site.settings.product_expiry == 1) {
					tr_html += '<td class="text-center"><input class="form-control input-sm text-center expiry_date" name="expiry_date[]" type="hidden" id="expiry_date_' + row_no + '" value="' + expiry_date + '"><input name="expiry_id[]" type="hidden" class="expiry_id" value="' + expiry_id + '"><span class="text-center" style="text-align:center !important;" id="expiry_date_' + row_no + '">' + expiry_date + '</span></td>';
				}
				
				tr_html += '<td class="text-right"><input class="form-control input-sm text-right rprice" name="net_price[]" type="hidden" id="price_' + row_no + '" value="' + unit_price + '"><input class="ruprice" name="unit_price[]" type="hidden" value="' + unit_price + '"><input class="realuprice" name="real_unit_price[]" type="hidden" value="' + item.row.real_unit_price + '"><span class="text-right sprice" id="sprice_' + row_no + '">' + formatMoney(unit_price) + '</span></td>';
                tr_html += '<td><input type="text" class="form-control text-center piece" value ="'+ formatDecimal(piece) +'" name="piece[]"></td>';
				tr_html += '<td><input type="text" class="form-control text-center wpiece" value ="'+ formatDecimal(wpiece) +'"name="wpiece[]" style="pointer-events: none;"></td>';
				tr_html += '<td><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + formatDecimal(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
                if (site.settings.product_serial == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm rserial" name="serial[]" type="text" id="serial_' + row_no + '" value="' + item_serial + '"></td>';
                }
                if (site.settings.product_discount == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + formatDecimal((item_discount * item_qty)) + '"><span class="text-right sdiscount text-danger" id="sdiscount_' + row_no + '">' + formatMoney((item_discount * item_qty)) + '</span></td>';
                }
                if (site.settings.tax1 == 1) {
                    tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item_qty) + '</span></td>';
                }
                tr_html += '<td class="text-right"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + (item_tax_method == 0?formatMoney(((parseFloat(real_unit_price) * parseFloat(item_qty))) - (item_discount*parseFloat(item_qty))):formatMoney(((parseFloat(unit_price) * parseFloat(item_qty))) - (item_discount*parseFloat(item_qty)) + parseFloat(pr_tax_val)* parseFloat(item_qty))) + '</span></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip pointer redel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#reTable");
                total += (item_tax_method == 0?(((parseFloat(real_unit_price) * parseFloat(item_qty))) - (item_discount*parseFloat(item_qty))):(((parseFloat(unit_price) * parseFloat(item_qty))) - (item_discount*parseFloat(item_qty)) + parseFloat(pr_tax_val)* parseFloat(item_qty)));
                count += parseFloat(item_qty);
                an++;
				no++;
            });
            // Order level discount calculations
            if (rediscount = __getItem('rediscount')) {
                var ds = rediscount;
                if (ds.indexOf("%") !== -1) {
                    var pds = ds.split("%");
                    if (!isNaN(pds[0])) {
                        order_discount = parseFloat((total * parseFloat(pds[0])) / 100);
                    } else {
                        order_discount = parseFloat((total * ds) / 100);
                    }
                } else {
                    order_discount = parseFloat((total * ds) / 100);
                }
            }

            // Order level tax calculations
            if (site.settings.tax2 != 0) {
                if (retax2 = __getItem('retax2')) {
                    $.each(tax_rates, function () {
                        if (this.id == retax2) {
                            if (this.type == 2) {
                                invoice_tax = parseFloat(this.rate);
                            }
                            if (this.type == 1) {
                                invoice_tax = parseFloat(((total + slshipping - order_discount) * this.rate) / 100);
                            }
                        }
                    });
                }
            }
            total_discount = parseFloat(order_discount + product_discount);

            // Totals calculations after item addition
            var gtotal = parseFloat(((total + invoice_tax + slshipping) - order_discount));
			var balance = <?= $inv->paid - $inv->refunded ?>;

            if (return_surcharge = __getItem('return_surcharge')) {
                var rs = return_surcharge.replace(/"/g, '');
                if (rs.indexOf("%") !== -1) {
                    var prs = rs.split('%');
                    var percentage = parseFloat(prs[0]);
                    if (!isNaN(prs[0])) {
                        surcharge = parseFloat((gtotal * percentage) / 100);
                    } else {
                        surcharge = parseFloat(rs);
                    }
                } else {
                    surcharge = parseFloat(rs);
                }
            }
            //console.log(surcharge);
            gtotal -= surcharge;

            $('#total').text(formatMoney(total));
            $('#titems').text((an - 1) + ' (' + (parseFloat(count) - 1) + ')');
            $('#total_items').val((parseFloat(count) - 1));
            $('#trs').text(formatMoney(surcharge));
			$('#tds').text(formatMoney(order_discount));
			$('#tship').text(formatMoney(slshipping));
            if (site.settings.tax1) {
                $('#ttax1').text(formatMoney(product_tax));
            }
            if (site.settings.tax2 != 0) {
                $('#ttax2').text(formatMoney(invoice_tax));
            }
            $('#gtotal').text(formatMoney(gtotal));
            <?php if($inv->payment_status == 'paid') { ?>
            if (gtotal > balance) {
                $('#amount_1').val(formatDecimal(balance));
            } else {
                $('#amount_1').val(formatDecimal(gtotal));
            }
            <?php } ?>
			<?php if($inv->payment_status == 'partial') { ?>
            if (gtotal > balance) {
                $('#amount_1').val(formatDecimal(balance));
            } else {
                $('#amount_1').val(formatDecimal(gtotal));
            }
			<?php } ?>
            if (an > site.settings.bc_fix && site.settings.bc_fix != 0) {
                $("html, body").animate({scrollTop: $('#reTable').offset().top - 150}, 500);
                $(window).scrollTop($(window).scrollTop() + 1);
            }
            if (count > 1) {
                $('#add_item').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
            }
            //audio_success.play();
        }
    }
	
	function billerChange(){
        var id = $("#slbiller").val();
        $("#slwarehouse").empty();
        $.ajax({
            url: '<?= base_url() ?>auth/getWarehouseByProject/'+id,
            dataType: 'json',
            success: function(result){
                $.each(result, function(i,val){
                    var b_id = val.id;
                    var name = val.name;
                    var opt = '<option value="' + b_id + '">' + name + '</option>';
                    $("#slwarehouse").append(opt);
                });
                $('#slwarehouse option[selected="selected"]').each(
                    function() {
                        $(this).removeAttr('selected');
                    }
                );
				$('#slwarehouse').val($('#slwarehouse option:first-child').val()).trigger('change');
                //$("#slwarehouse").select2("val", "<?=$Settings->default_warehouse;?>");
            }
        });
    }
	
	$(document).ready(function() {
		$("#reref").attr('readonly', true);
		$('#ref_st').on('ifChanged', function() {
		  if ($(this).is(':checked')) {
			// $("#reref").prop('disabled', false);
            $("#reref").attr('readonly', false);
			$("#reref").val("");
		  }else{
			$("#reref").prop('disabled', true);
			var temp = $("#temp_reference_no").val();
			$("#reref").val(temp);
			
		  }
		});
	});
	
</script>

<div class="row">
	<div class="col-md-12">
		<?php
		if ($inv->payment_status == 'paid') {
			echo '<div class="alert alert-success">' . lang('payment_status') . ': <strong>' . $inv->payment_status . '</strong> & ' . lang('paid_amount') . ' <strong>' . $this->erp->formatMoney($inv->paid) . '</strong> & ' . lang('refunded') . ' <strong>' . $this->erp->formatMoney($inv->refunded) . '</strong></div>';
		} else {
			echo '<div class="alert alert-warning">' . lang('payment_status_not_paid') . ' ' . lang('payment_status') . ': <strong>' . $inv->payment_status . '</strong> & ' . lang('paid_amount') . ' <strong>' . $this->erp->formatMoney($inv->deposit + $inv->paid) . '</strong> & ' . lang('refunded') . ' <strong>' . $this->erp->formatMoney($inv->refunded) . '</strong></div>';
		}
		?>
	</div>
</div>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-minus-circle"></i><?= lang('return_sale'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-resl-form');
                echo form_open_multipart("sales/return_sale/" . $inv->id, $attrib)
                ?>

                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin || $Settings->allow_change_date == 1) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "redate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="redate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>
						
                        <!--<div class="col-md-4">
                            <div class="form-group">
                                <?= lang("reference_no", "reref"); ?>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ''), 'class="form-control input-tip" id="reref"'); ?>
                            </div>
                        </div>-->
						<div class="col-md-4">
							<?= lang("reference_no", "reref"); ?>
							<div style="float:left;width:100%;">
								<div class="form-group">
									<div class="input-group">  
										<?php echo form_input('reference_no', $reference?$reference:"",'class="form-control input-tip" id="reref"'); ?>
										<input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference?$reference:"" ?>" />
										<div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
											<input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
										</div>
									</div>
								</div>
							</div>
                        </div>
						<div class="col-md-4">
                            <div class="form-group">
                                <?= lang("invoice_reference_no", "iref"); ?>
                                <?php echo form_input('inv_ref_no', (isset($_POST['inv_ref_no']) ? $_POST['inv_ref_no'] : ''), 'class="form-control input-tip" id="iref" style="pointer-events: none;"'); ?>
                            </div>
                        </div>
						<div class="clearfix"></div>
                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading"><?= lang('please_select_these_before_adding_product') ?></div>
                                <div class="panel-body" style="padding: 5px;">			
									<?php if ($Owner || $Admin || !$this->session->userdata('biller_id')) { ?>
										<div class="col-md-4">
											<div class="form-group">
												<?= lang("biller", "slbiller"); ?>
												<?php
												$bl[""] = "";
												foreach ($billers as $biller) {
													$bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
												}
												echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ($inv->biller_id ? $inv->biller_id : $Settings->default_biller)), 'id="slbiller" data-placeholder="' . lang("select") . ' ' . lang("biller") . '" required="required" class="form-control input-tip select" style="width:100%; pointer-events: none;"');
												?>
											</div>
										</div>
									<?php } else {
										$biller_input = array(
											'type' => 'hidden',
											'name' => 'biller',
											'id' => 'slbiller',
											'value' => $inv->biller_id,
											'style' => 'pointer-events: none;'
										);
										echo form_input($biller_input);
									} ?>
									<?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>
										<div class="col-md-4">
											<div class="form-group">
												<?= lang("warehouse", "slwarehouse"); ?>
												<?php
												 $wh[''] = '';
												foreach ($warehouses as $warehouse) {
													$wh[$warehouse->id] = $warehouse->name;
												}
												echo form_dropdown('warehouse', '', (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="slwarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%; pointer-events: none;" ');
												?>
											</div>
										</div>
									<?php } else {
										$warehouse_input = array(
											'type' => 'hidden',
											'name' => 'warehouse',
											'id' => 'slwarehouse',
											'value' => $this->session->userdata('warehouse_id'),
											'style' => 'pointer-events: none;'
										);

										echo form_input($warehouse_input);
									} ?>
									<div class="col-md-4">
										<div class="form-group">
										<?= lang("saleman", "saleman"); ?>
										<select name="saleman" id="saleman" class="form-control saleman" style="pointer-events: none;">
											<?php
												foreach($agencies as $agency){
													if($this->session->userdata('username') == $agency->username){
														echo '<option value="'.$this->session->userdata('user_id').'" selected>'.lang($this->session->userdata('username')).'</option>';
													}else{
														echo '<option value="'.$agency->id.'">'.$agency->username.'</option>';
													}
												}
											?>
										</select>
										<?php
										/*$sm[''] = '';
										foreach($agencies as $agency){
											$sm[$agency->id] = $agency->username;
										}
										echo form_dropdown('saleman', $sm, (isset($_POST['saleman']) ? $_POST['saleman'] : ''), 'id="slsaleman" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("saleman") . '" style="width:100%;" ');*/
										?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?= lang("customer", "slcustomer"); ?>
											<?php if ($Owner || $Admin || $GP['customers-add']) { ?><div class="input-group"><?php } ?>
												<?php
													echo form_input('customer_1', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'id="slcustomer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control input-tip" style="min-width:100%; pointer-events: none;"');
												?>
												<?php if ($Owner || $Admin || $GP['customers-add']) { ?>

												<div class="input-group-addon no-print" style="padding: 2px 5px; border-left: 0; pointer-events: none;">
													<a href="#" id="view-customer" class="external" data-toggle="modal" data-target="#myModal">
														<i class="fa fa-2x fa-user" id="addIcon"></i>
													</a>
												</div>

												<div class="input-group-addon no-print" style="padding: 2px 5px; pointer-events: none;"><a
														href="<?= site_url('customers/add'); ?>" id="add-customer"
														class="external" data-toggle="modal" data-target="#myModal"><i
															class="fa fa-2x fa-plus-circle" id="addIcon"></i></a></div>
											</div>
											<?php } ?>
											<input type="hidden" name="cust_id" id="cust_id" class="cust_id" value="<?= $inv->id ?>" />
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<?= lang("delivery_by", "delivery_by"); ?>
											<select name="delivery_by" id="delivery_by" class="form-control delivery_by" style="pointer-events: none;">
												<?php
													foreach($agencies as $agency){
														if($this->session->userdata('username') == $agency->username){
															echo '<option value="'.$this->session->userdata('username').'" selected>'.lang($this->session->userdata('username')).'</option>';
														}else{
															echo '<option value="'.$agency->id.'">'.$agency->username.'</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<!--
									<div class="col-md-4">
										<div class="form-group">
											<?= lang("document", "document") ?>
											<input id="document" type="file" name="document" data-show-upload="false"
												   data-show-preview="false" class="form-control file">
										</div>
									</div>
									-->
									
								</div>
							</div>
						</div>

                        <div class="col-md-12">
                            <div class="control-group table-group">
                                <label class="table-label"><?= lang("order_items"); ?> *</label>

                                <div class="controls table-controls">
                                    <table id="reTable"
                                           class="table items table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                        <tr>
                                            <!--<th class="col-md-4"><?= lang("product_name") . " (" . $this->lang->line("product_code") . ")"; ?></th>-->
											<th class=""><?= lang("no"); ?></th>
											<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
												<th class="col-md-2"><?= lang("product_code"); ?></th>
												<th class="col-md-4"><?= lang("product_name"); ?></th>
											<?php } ?>
                                            <?php if($setting->show_code == 1 && $setting->separate_code == 0) { ?>
												<th class="col-md-4"><?= lang("product_name") . " (" . lang("product_code") . ")"; ?></th>
											<?php } ?>
											<?php if($setting->show_code == 0) { ?>
												<th class="col-md-4"><?= lang("product_name"); ?></th>
											<?php } ?>
											<?php if($setting->product_expiry) { ?>
												<th class="col-md-1"><?= lang("expiry_date"); ?></th>
											<?php } ?>
                                            <th class="col-md-1"><?= lang("net_unit_price"); ?></th>
                                            <th class="col-md-1"><?= lang("piece"); ?></th>
                                            <th class="col-md-1"><?= lang("wpiece"); ?></th>
                                            <th class="col-md-1"><?= lang("quantity"); ?></th>
                                            <?php
                                            if ($Settings->product_serial) {
                                                echo '<th class="col-md-2">' . $this->lang->line("serial_no") . '</th>';
                                            }
                                            ?>
                                            <?php
                                            if ($Settings->product_discount) {
                                                echo '<th class="col-md-1">' . $this->lang->line("discount") . '</th>';
                                            }
                                            ?>
                                            <?php
                                            if ($Settings->tax1) {
                                                echo '<th class="col-md-1">' . $this->lang->line("product_tax") . '</th>';
                                            }
                                            ?>
                                            <th><?= lang("subtotal"); ?> (<span
                                                    class="currency"><?= $default_currency->code ?></span>)
                                            </th>
                                            <th style="width: 30px !important; text-align: center;"><i
                                                    class="fa fa-trash-o"
                                                    style="opacity:0.5; filter:alpha(opacity=50);"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
							<div class="clearfix"></div>
							<div class="col-sm-12">
								<?php if ($Owner || $Admin || $this->session->userdata('allow_discount')) { ?>
									<div class="col-sm-4">
										<div class="form-group">
											<?= lang("order_discount", "sldiscount"); ?>
											<?php echo form_input('order_discount', '', 'class="form-control input-tip" id="sldiscount"'); ?>
										</div>
									</div>
								<?php } ?>

								<div class="col-sm-4">
									<div class="form-group">
										<?= lang("shipping", "slshipping"); ?>
										<?php echo form_input('shipping', '', 'class="form-control input-tip" id="slshipping"'); ?>

									</div>
								</div>
								<?php if ($Settings->tax2) { ?>
									<div class="col-sm-4">
										<div class="form-group">
											<?= lang("order_tax", "sltax2"); ?>
											<?php
											$tr[""] = "";
											foreach ($tax_rates as $tax) {
												$tr[$tax->id] = $tax->name;
											}
											echo form_dropdown('order_tax', $tr, (isset($_POST['order_tax']) ? $_POST['order_tax'] : $Settings->default_tax_rate2), 'id="sltax2" data-placeholder="' . lang("select") . ' ' . lang("order_tax") . '" class="form-control input-tip select" style="width:100%;"');
											?>
										</div>
									</div>
								<?php } ?>
							</div>
							<div class="col-sm-12">
								<div class="col-sm-4">
									<div class="form-group">
										<?= lang("document", "document") ?>
										<input id="document" type="file" name="document" data-show-upload="false" data-show-preview="false" class="form-control file">
									</div>
								</div>

								<div class="col-sm-4">
									<div class="form-group">
										<?= lang("document", "document") ?>
										<input id="document1" type="file" name="document1" data-show-upload="false" data-show-preview="false" class="form-control file">
									</div>
								</div>

								<div class="col-sm-4">
									<div class="form-group">
										<?= lang("document", "document") ?>
										<input id="document2" type="file" name="document2" data-show-upload="false" data-show-preview="false" class="form-control file">
									</div>
								</div>
							</div>
							<!--
							<?php if($inv->paid > 0) { ?>
							<div class="col-sm-12">
								<div class="col-md-4">
									<div class="form-group">
										<?= lang("return_surcharge", "return_surcharge"); ?>
										<?php echo form_input('return_surcharge', (isset($_POST['return_surcharge']) ? $_POST['return_surcharge'] : ''), 'class="form-control input-tip" id="return_surcharge" required="required"'); ?>
									</div>
								</div>
							</div>
							<?php } ?>
							-->
                        </div>
                        <div style="height:15px; clear: both;"></div>
                        <?php if(($inv->paid - $inv->refunded) > 0) { ?>
                        <div id="payments">
                            <div class="col-md-12">
                                <div class="well well-sm well_1">
                                    <div class="col-md-12">
                                        <div class="row">
											<div class="col-sm-12">
												<div class="col-md-4" id="payment_ref">
													<div class="form-group">
														<?= lang("payment_reference_no", "payment_reference_no"); ?>
														<?= form_input('payment_reference_no', (isset($_POST['payment_reference_no']) ? $_POST['payment_reference_no'] : $payment_ref), 'class="form-control tip" id="payment_reference_no" required="required"'); ?>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="payment">
														<div class="form-group">
															<?= lang("amount", "amount_1"); ?>
                                                            <input name="amount-paid" type="text" id="amount_1"
                                                                   class="pa form-control kb-pad amount"
                                                                   value="<?= $inv->paid; ?>"/>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="form-group">
														<?= lang("paying_by", "paid_by_1"); ?>
														<select name="paid_by" id="paid_by_1" class="form-control paid_by">
															<option value="cash"><?= lang("cash"); ?></option>
															<option value="gift_card"><?= lang("gift_card"); ?></option>
															<option value="Cheque"><?= lang("cheque"); ?></option>
															<option value="deposit"><?= lang("deposit"); ?></option>
															<option value="other"><?= lang("other"); ?></option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-sm-12">
												<div class="col-sm-4" id="bank_acc">
													<div class="form-group">
														<?= lang("bank_account", "bank_account_1"); ?>
														<?php $bank = array('0' => '-- Select Bank Account --');
														foreach($bankAccounts as $bankAcc) {
															$bank[$bankAcc->accountcode] = $bankAcc->accountcode . ' | '. $bankAcc->accountname;
														}
														echo form_dropdown('bank_account', $bank, '', 'id="bank_account_1" class="ba form-control kb-pad bank_account" required="required"');
														?>
													</div>
												</div>
											</div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="pcc_1" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_no" type="text" id="pcc_no_1"
                                                               class="form-control" placeholder="<?= lang('cc_no') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_holder" type="text" id="pcc_holder_1"
                                                               class="form-control"
                                                               placeholder="<?= lang('cc_holder') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="pcc_type" id="pcc_type_1"
                                                                class="form-control pcc_type"
                                                                placeholder="<?= lang('card_type') ?>">
                                                            <option value="Visa"><?= lang("Visa"); ?></option>
                                                            <option
                                                                value="MasterCard"><?= lang("MasterCard"); ?></option>
                                                            <option value="Amex"><?= lang("Amex"); ?></option>
                                                            <option value="Discover"><?= lang("Discover"); ?></option>
                                                        </select>
                                                        <!-- <input type="text" id="pcc_type_1" class="form-control" placeholder="<?= lang('card_type') ?>" />-->
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_month" type="text" id="pcc_month_1"
                                                               class="form-control" placeholder="<?= lang('month') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_year" type="text" id="pcc_year_1"
                                                               class="form-control" placeholder="<?= lang('year') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_ccv" type="text" id="pcc_cvv2_1"
                                                               class="form-control" placeholder="<?= lang('cvv2') ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pcheque_1" style="display:none;">
                                            <div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
                                                <input name="cheque_no" type="text" id="cheque_no_1"
                                                       class="form-control cheque_no"/>
                                            </div>
                                        </div>
                                        <div class="gc" style="display: none;">
                                            <div class="form-group">
                                                <?= lang("gift_card_no", "gift_card_no"); ?>
                                                <div class="input-group">

                                                    <input name="gift_card_no" type="text" id="gift_card_no"
                                                           class="pa form-control kb-pad"/>

                                                    <div class="input-group-addon"
                                                         style="padding-left: 10px; padding-right: 10px; height:25px;">
                                                        <a href="#" id="sellGiftCard" class="tip"
                                                           title="<?= lang('sell_gift_card') ?>"><i
                                                                class="fa fa-credit-card"></i></a></div>
                                                </div>
                                            </div>
                                            <div id="gc_details"></div>
                                        </div>
                                        <div class="form-group dp" style="display: none;">
                                            <?= lang("customer", "customer1"); ?>
                                            <?php
                                            $customers1[] = array();
                                            foreach($customers as $customer){
                                                $customers1[$customer->id] = $customer->text;
                                            }
                                            echo form_dropdown('customer', $customers1, $inv->customer_id , 'class="form-control" id="customer1" style="display:none;"');
                                            ?>
                                            <?= lang("deposit_amount", "deposit_amount"); ?>

                                            <div id="dp_details"></div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
						<?php } ?>
                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>
                        <input type="hidden" name="order_tax" value="" id="retax2" required="required"/>
                        <input type="hidden" name="discount" value="" id="rediscount" required="required"/>

                        <div class="row" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?= lang("return_note", "renote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="renote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_return', $this->lang->line("submit"), 'id="add_return" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?></div>
                        </div>
                    </div>
                </div>
				
				<div class="clearfix"></div>
				<div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">
					<table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
						<tr class="warning">
							<td><?= lang('items') ?> <span class="totals_val pull-right"
														   id="titems">0</span></td>
							<td><?= lang('total') ?> <span class="totals_val pull-right"
														   id="total">0.00</span></td>
							<?php if ($Owner || $Admin || $this->session->userdata('allow_discount')) { ?>
                            <td><?= lang('order_discount') ?> <span class="totals_val pull-right" id="tds">0.00</span></td>
                            <?php }?>
							<td><?= lang('shipping') ?> <span class="totals_val pull-right" id="tship">0.00</span></td>
							<td><?= lang('surcharges') ?> <span class="totals_val pull-right"
																id="trs">0.00</span></td>
							<?php if ($Settings->tax2) { ?>
								<td><?= lang('order_tax') ?> <span class="totals_val pull-right" id="ttax2">0.00</span>
								</td>
							<?php } ?>
							<td><?= lang('return_amount') ?> <span class="totals_val pull-right"
																   id="gtotal">0.00</span></td>
						</tr>
					</table>
				</div>

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

<div class="modal" id="gcModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="myModalLabel"><?= lang('sell_gift_card'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?= lang('enter_info'); ?></p>

                <div class="alert alert-danger gcerror-con" style="display: none;">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <span id="gcerror"></span>
                </div>
                <div class="form-group">
                    <?= lang("card_no", "gccard_no"); ?> *
                    <div class="input-group">
                        <?php echo form_input('gccard_no', '', 'class="form-control" id="gccard_no" onClick="this.select();"'); ?>
                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#"
                                                                                                           id="genNo"><i
                                    class="fa fa-cogs"></i></a></div>
                    </div>
                </div>
                <input type="hidden" name="gcname" value="<?= lang('gift_card') ?>" id="gcname"/>

                <div class="form-group">
                    <?= lang("value", "gcvalue"); ?> *
                    <?php echo form_input('gcvalue', '', 'class="form-control" id="gcvalue"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("customer", "gccustomer"); ?>
                    <div class="input-group">
                        <?php echo form_input('gccustomer', '', 'class="form-control" id="gccustomer"'); ?>
                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#"
                                                                                                           id="noCus"
                                                                                                           class="tip"
                                                                                                           title="<?= lang('unselect_customer') ?>"><i
                                    class="fa fa-times"></i></a></div>
                    </div>
                </div>
                <div class="form-group">
                    <?= lang("expiry_date", "gcexpiry"); ?>
                    <?php echo form_input('gcexpiry', '', 'class="form-control date" id="cgexpiry"'); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="addGiftCard" class="btn btn-primary"><?= lang('sell_gift_card') ?></button>
            </div>
        </div>
    </div>
</div>
