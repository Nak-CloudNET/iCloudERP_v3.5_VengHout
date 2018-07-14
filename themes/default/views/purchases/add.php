<!--Costomize Javascript-->

<script type="text/javascript">
 $(document).ready(function () {
	var MaxInputs       = 30;
	var InputsWrapper   = jQuery(".journalContainer");
	var AddButton       = jQuery("#addDescription");

	var InputCount = jQuery(".journal-list");
	var x = InputCount.length;

	var FieldCount=2;

	$(AddButton).click(function (e)
	{
		if(x <= MaxInputs)
		{
			FieldCount++;
			var div = '<div class="col-md-12 journal-list divwrap'+FieldCount+'">';
			div += '	<div class="col-md-4">';
			div += '			<div class="form-group company">';
			div += '				<select class="form-control input-tip select" id="select" name="account_section[]" style="width:100%;" required="required">';
			div += '				<?php foreach($sectionacc as $section){ ?>';
			div += '					<option value="<?=$section->accountcode?>"><?=$section->accountcode . " | " . $section->accountname; ?></option>';
			div += '				<?php } ?>';
			div += '				</select>';
			div += '			</div>';
			div += '		</div>';

			div += '		<div class="col-md-4">';
			div += '			<div class="form-group">';
			div += '				<input type="text" name="debit[]" value="" class="form-control debit'+FieldCount+'" id="debit"> ';
			div += '			</div>';
			div += '		</div>';

			div += '		<div class="col-md-1">';
			div += '			<label><label><button type="button" data="'+FieldCount+'" class="removefile btn btn-danger">&times;</button></label></label>';
			div += '		</div>';
			div += '	</div>';

			$(InputsWrapper).append(div);
			x++;
		}
		return false;
	});

	function AutoDebit() {
		var v_debit = 0;
		var i = 1;
		var expense_tax = 0;

		$('[name^=debit]').each(function(i, item) {
			v_debit +=  parseFloat($(item).val()) || 0;
		});

		if (site.settings.tax2 != 0) {
            if (potax2 = __getItem('potax2')) {
                $.each(tax_rates, function () {
                    if (this.id == potax2) {
                        if (this.type == 2) {
                            expense_tax = parseFloat(this.rate);
                        }
                        if (this.type == 1) {
                            expense_tax = parseFloat((v_debit * this.rate) / 100);
                        }
                    }
                });
            }
        }

		$("#calDebit").text(v_debit);
		$("#in_calDebit").val(v_debit);
		$('#total').text(formatPurDecimal(v_debit));
		$('#ttax2').text(formatPurDecimal(expense_tax));
		$('#in_calOrdTax').val(expense_tax);

		var v_grand_total = v_debit + expense_tax;
		$('#gtotal').text(formatPurDecimal(v_grand_total));
	}

	$('#potax2').change(function() {
		var type = $('#poexpance').val();
		if(type == 'exp') {
			__setItem('potax2', $(this).val());
			AutoDebit();
		}
	});

	$(document).ready(function () {

		$('.removefile').live('click', function(){
			var divId 	= $(this).attr('data');
			if( FieldCount == 2 ) {
				bootbox.alert('Journal must be at least two transaction!');

				return false;
			}else{
				$('.divwrap'+divId+'').remove();
			}
		});

		$('input[name="debit[]"], input[name="credit[]"]').live('change keyup paste',function(){
			AutoDebit();

			if($("#calDebit").text() != $("#calCredit").text()){
				$("#calDebit").addClass('error');
				$("#calCredit").addClass('error');
			}else{
				$("#calDebit").removeClass('error');
				$("#calCredit").removeClass('error');
			}
		});

		$('#pcost,#pcost_none').live('change',function(){
			value = $(this).val();
			autotherMoney(value);
		});

		$('input[name="other_amount"]').live('change keyup paste',function(){
			value = $(this).val();
			rate = $(this).attr('rate');
			var val = value / rate;
			autoMoney(value, rate);
			autotherMoney(val);
		});

		function autotherMoney(value){
            $(".amount_other").each(function(){
                var rate = $(this).attr('rate');
				if(value != 0){
					$(this).val(formatDecimal(value*rate));
				}else{
					$(this).val('0');
				}
            });
        }

        function autoMoney(value, rate){
        	$(".amount_other").each(function(){
				if(value != 0){
					$('#pcost').val(value / rate);
					$('#pcost_none').val(formatDecimal(value / rate));
				}else{
					$('#pcost').val('0');
					$('#pcost_none').val('0');
				}
            });
			$('#pcost').trigger('change');
        }

		function chart_account(){
			$('#account_section').bind("change", function(){
				$(".sub_textbox").show();
				$(".sub_combobox").hide();
				var v = $(this).val();
				$('#modal-loading').show();
				if (v) {
					$.ajax({
						type: "get",
						async: false,
						url: "<?= site_url('account/getSubAccount') ?>/" + v,
						dataType: "json",
						success: function (scdata) {
							if (scdata != null) {
								$("#sub_account").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
									placeholder: "<?= lang('select_category_to_load') ?>",
									data: scdata
								});
							}
						},
						error: function () {
							bootbox.alert('<?= lang('ajax_error') ?>');
							$('#modal-loading').hide();
						}
					});
				}
				$('#modal-loading').hide();
			});
		}
		chart_account();
	});
 });
</script>

<script type="text/javascript">
    $(document).ready(function () {

        $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
            placeholder: "<?= lang('select_category_to_load') ?>", data: [
                {id: '', text: '<?= lang('select_category_to_load') ?>'}
            ]
        });
		$('#category').change(function () {
            var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('products/getSubCategories') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
                            $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: scdata
                            });
                        }else{
							$("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: 'not found'
                            });
						}
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            } else {
                $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
                    placeholder: "<?= lang('select_category_to_load') ?>",
                    data: [{id: '', text: '<?= lang('select_category_to_load') ?>'}]
                });
            }
            $('#modal-loading').hide();
        });
		$('#add_category').click(function (){
			$('#myModal').show();
			$('#mModal').hide();


		});
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
	});
    var test = '<?=$this->session->userdata('remove_puitem');?>';
    if (test == '1') {
        localStorage.removeItem('purchasesaddpoitems');
        if (__getItem('transfersedittoitems')) {
            __removeItem('transfersedittoitems');
        }

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
		if (__getItem('ponote')) {
			__removeItem('ponote');
		}
		if (__getItem('slpayment_status')) {
			__removeItem('slpayment_status');
		}
		if (__getItem('slpayment_term')) {
			__removeItem('slpayment_term');
		}
		if (__getItem('posupplier')) {
			__removeItem('posupplier');
		}
		if (__getItem('psupplier')) {
			__removeItem('psupplier');
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
		if (__getItem('scustomer')) {
			__removeItem('scustomer');
		}
		if (__getItem('scustomer_no')) {
			__removeItem('scustomer_no');
		}
		if (__getItem('purchase_order_id')) {
			__removeItem('purchase_order_id');
		}
		$(document).on('change', '#slbiller', function (e) {
			__setItem('slbiller', $(this).val());
		});
		if (slbiller = __getItem('slbiller')) {
			$('#slbiller').val(slbiller);
		}
		if (customers = __getItem('scustomer')) {
			$('#scustomer').val(customers);
		}
		if (customers = __getItem('scustomer_no')) {
			$('#scustomer_no').val(customers);
		}
		if (posupplier = __getItem('posupplier')) {
			$('#posupplier').val(posupplier);
		}
		//$this->erp->unset_data('remove_pols');
		<?= $this->session->set_userdata('remove_puitem', '0');?>
	}
    <?php if($quote_id) { ?>
		__setItem('quote_id','<?=$quote_id?>');
		__setItem('powarehouse', '<?= $quote->warehouse_id ?>');
		__setItem('ponote', '<?= str_replace(array("\r", "\n"), "", $this->erp->decode_html($quote->note)); ?>');
		__setItem('podiscount', '<?= $quote->order_discount_id ?>');
		__setItem('potax2', '<?= $quote->order_tax_id ?>');
		__setItem('poshipping', '<?= $quote->shipping ?>');
		__setItem('poitems', JSON.stringify(<?= $quote_items; ?>));
    <?php }?>

	<?php if(isset($sale_order_id)) { ?>
		__setItem('so_id','<?=$sale_order_id?>');
		__setItem('powarehouse', '<?= $sale_order->warehouse_id ?>');
		__setItem('ponote', '<?= str_replace(array("\r", "\n"), "", $this->erp->decode_html($quote->note)); ?>');
		__setItem('podiscount', '<?= $sale_order->order_discount_id ?>');
		__setItem('potax2', '<?= $sale_order->order_tax_id ?>');
		__setItem('poshipping', '<?= $sale_order->shipping ?>');
		__setItem('poitems', JSON.stringify(<?= $sale_order_items; ?>));
    <?php }?>

	<?php if (isset($purchase_order_id)) {?>
		__setItem('purchase_order_id','<?=$purchase_order_id?>');
	<?php } ?>

	<?php if (isset($inv)) { ?>
		__setItem('poid', '<?=$inv->id?>');
		__setItem('podate', '<?= date($dateFormats['php_sdate'], strtotime($inv->date))?>');
		__setItem('slbiller', '<?=$inv->biller_id ?>');
        __setItem('posupplier', '<?=$inv->supplier_id?>');
        __setItem('poref', '<?=$inv->reference_no?>');
        __setItem('powarehouse', '<?=$inv->warehouse_id?>');
		__setItem('order_ref', '<?=isset($inv->order_ref)?>');
        __setItem('postatus', 'received');
        __setItem('ponote', '<?= str_replace(array("\r", "\n"), "", $this->erp->decode_html($inv->note)); ?>');
        __setItem('podiscount', '<?=$inv->order_discount_id?>');
        __setItem('potax2', '<?=$inv->order_tax_id?>');
        __setItem('poshipping', '<?=$inv->shipping?>');
        __setItem('popayment_term', '<?=$inv->payment_term?>');
        __setItem('slpayment_status', '<?=$inv->payment_status?>');
        if (parseFloat(__getItem('potax2')) >= 1 || __getItem('podiscount').length >= 1 || parseFloat(__getItem('poshipping')) >= 1) {
            __setItem('poextras', '1');
        }
        //__setItem('posupplier', '<?=$inv->supplier_id?>');
        __setItem('poitems', JSON.stringify(<?=$inv_items;?>));
		__setItem('fDisable','yes');
     <?php }else{ ?>
			__removeItem('poid');
	 <?php } ?>

    var count = 1, an = 1, po_edit = false, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>, DC = '<?= $default_currency->code ?>', shipping = 0,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>, poitems = {},
        audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3'),
        audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    $(document).ready(function () {

		if(quote_id = __getItem('quote_id')){
			$("#quote_id").val(quote_id);

		}
		if(sale_order_id = __getItem('so_id')){
			$("#so_id").val(sale_order_id);

		}
        <?php if($this->input->get('supplier')) { ?>

        if (!__getItem('poitems')) {
            __setItem('posupplier', <?=$this->input->get('supplier');?>);
        }
		if (!__getItem('poitems')) {
            __setItem('psupplier', <?=$this->input->get('supplier');?>);
        }
        <?php } ?>

        if (!__getItem('podate')) {
            $("#podate").datetimepicker({
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
        $(document).on('change', '#podate', function (e) {
            __setItem('podate', $(this).val());
        });
        if (podate = __getItem('podate')) {
            $('#podate').val(podate);
        }

        if (!__getItem('potax2')) {
            __setItem('potax2', <?=$Settings->default_tax_rate2;?>);
            setTimeout(function(){ $('#extras').iCheck('check'); }, 1000);
        }
        ItemnTotals();
		$("#add_item").autocomplete({
            source: function (request, response) {
				var test = request.term;
				if($.isNumeric(test)){
					$.ajax({
						type: 'get',
						url: '<?= site_url('purchases/suggests'); ?>',
						dataType: "json",
						data: {
							term: request.term,
							warehouse_id: $("#powarehouse").val(),
							customer_id: $("#poscustomer").val()
						},
						success: function (data) {
							response(data);
						}
					});
				}else{
					$.ajax({
						type: 'get',
						url: '<?= site_url('purchases/suggestions'); ?>',
						dataType: "json",
						data: {
							term: request.term,
							warehouse_id: $("#powarehouse").val(),
							customer_id: $("#poscustomer").val()
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
                        $('#add_item').focus();
                    });


                    $(this).val('');
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
                        $('#add_item').focus();
                    });
					//$(location).attr("href", "<?= site_url('products/add'); ?>");
                    //$(this).removeClass('ui-autocomplete-loading');
                    // $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_purchase_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });

		$('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

        $(document).on('click', '#addItemManually', function (e) {
            if (!$('#type').val()) {
                $('#mError').text('<?= lang('product_type_is_required') ?>');
                $('#mError-con').show();
                return false;
            }
			if (!$('#code').val()) {
                $('#mError').text('<?= lang('product_code_is_required') ?>');
                $('#mError-con').show();
                return false;
            }
            if (!$('#name').val()) {
                $('#mError').text('<?= lang('product_name_is_required') ?>');
                $('#mError-con').show();
                return false;
            }
			if (!$('#barcode_symbology').val()) {
                $('#mError').text('<?= lang('barcode_symbology_is_required') ?>');
                $('#mError-con').show();
                return false;
            }
            if (!$('#category').val()) {
                $('#mError').text('<?= lang('product_category_is_required') ?>');
                $('#mError-con').show();
                return false;
            }
            if (!$('#unit').val()) {
                $('#mError').text('<?= lang('product_unit_is_required') ?>');
                $('#mError-con').show();
                return false;
            }
            if (!$('#cost').val()) {
                $('#mError').text('<?= lang('product_cost_is_required') ?>');
                $('#mError-con').show();
                return false;
            }
            if (!$('#price').val()) {
                $('#mError').text('<?= lang('product_price_is_required') ?>');
                $('#mError-con').show();
                return false;
            }
            var msg, row = null, product = {
                type: $('#type').val(),
                code: $('#code').val(),
                name: $('#name').val(),
				barcode_symbology: $('#barcode_symbology').val(),
				subcategory: $('#subcategory').val(),
                tax_rate: $('#tax').val(),
                tax_method: $('#tax_method').val(),
                category_id: $('#category').val(),
                unit: $('#unit').val(),
                cost: $('#cost').val(),
                price: $('#price').val(),
				alert_quantity: $('#alert_quantity').val(),
				supplier1: $('#supplier'),
				image: $('#product_image').val(),
				product_details: $('#details').val(),
				warehouse_id: $('#rwh_qty_'+<?= isset($wh_pr->id)?$wh_pr->id:1; ?>).val()
            };

            $.ajax({
                type: "get", async: false,
                url: site.base_url + "products/addByAjax",
                data: {token: "<?= $csrf; ?>", product: product},
                dataType: "json",
                success: function (data) {
                    if (data.msg == 'success') {
                        row = add_purchase_item(data.result);
                    } else {
                        msg = data.msg;
                    }
                }
            });
            if (row) {
                $('#mModal').modal('hide');
                //audio_success.play();
            } else {
                $('#mError').text(msg);
                $('#mError-con').show();
            }
            return false;

        });

		$("#scustomer").change(function()
		{
			var v = $(this).val();
			$('#modal-loading').show();
			if (v) {
				$.ajax({
					type: "get",
					async: false,
					url: "<?= site_url('account/getCustomerInvoices') ?>/" + v,
					dataType: "json",
					success: function (scdata) {

						if (scdata != null) {
							$("#scustomer_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer_invoice') ?>").select2({
								placeholder: "<?= lang('select_customer_to_load') ?>",
								data: scdata
							});
						}else{
							$("#scustomer_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer_invoice') ?>").select2({
								placeholder: "<?= lang('select_customer_to_load') ?>",
								data: 'not found'
							});
						}
					},
					error: function () {
						bootbox.alert('<?= lang('ajax_error') ?>');
						$('#modal-loading').hide();
					}
				});
			} else {
				$("#scustomer_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
					placeholder: "<?= lang('select_customer_to_load') ?>",
					data: [
						{id: '', text: 'None'},
						<?php
						if(isset($invoices)) {
							foreach($invoices as $invoice) { ?>
								{id: '<?= (isset($invoice->id) ? $invoice->id : "") ?>', text: '<?= (isset($invoice->text) ? $invoice->text : "") ?>'},
						<?php
							}
						} ?>
					]
				});
			}
		   $('#modal-loading').hide();

		}).trigger("change");

    });

</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_purchase'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?> </p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
				if(isset($id)){
					echo form_open_multipart("purchases/add/".$id, $attrib);
				}elseif(isset($qid)){
					echo form_open_multipart("purchases/add/0/".$qid, $attrib);
				}else{
					echo form_open_multipart("purchases/add", $attrib);
				}

                ?>

                <div class="row">
                    <div class="col-lg-12">

                        <?php if ($Owner || $Admin || $Settings->allow_change_date == 1) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "podate"); ?>
                                    <?php echo form_input('date', "", 'class="form-control input-tip datetime" id="podate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>
						<!--
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("reference_no", "poref"); ?>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $ponumber), 'class="form-control input-tip"'); ?>
								<input type="hidden"  name="reference_no_order"  id="reference_no_order" value="<?= $purchase->reference_no?$purchase->reference_no:'' ?>" />
								<input type="hidden"  name="reference_no_request"  id="reference_no_request" value="<?= $purchase->purchase_ref?$purchase->purchase_ref:'' ?>" />



                            </div>

                        </div>
						-->

						<div class="col-md-4">
							<?= lang("reference_no", "poref"); ?>
							<div style="float:left;width:100%;">
								<div class="form-group">
									<div class="input-group">
											<?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $ponumber),'class="form-control input-tip" id="poref"'); ?>
											<input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $ponumber?$ponumber:""; ?>" />
											<input type="hidden"  name="order_id"  id="order_id" value="<?= isset($id) ? $id :''; ?>" />
											<input type="hidden"  name="quote_id"  id="quote_id" value="" />
											<input type="hidden"  name="so_id"  id="so_id" value="" />
										    <div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
											<input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
										</div>
									</div>
								</div>
							</div>
                        </div>

						<?php if(isset($id)){ ?>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang("order_ref", "order_ref"); ?>
									<?php echo form_input('order_ref', $purchase->reference_no, 'class="form-control input-tip" id="order_ref" readonly'); ?>
								</div>
							</div>
						<?php } ?>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php
                                    $default_value = $billers[0]->id;
                                    echo  get_dropdown_project('biller', 'slbiller',$default_value);
                                ?>
                            </div>
                        </div>

						<div class="warehouse">
							<?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>
								<div class="col-md-4">
									<div class="form-group">
										<?= lang("warehouse", "powarehouse"); ?><b> *</b>
										<?php
										 $wh = array(""=>"");
										foreach ($warehouses as $warehouse) {
											$wh[$warehouse->id] = $warehouse->name;
										}
										echo form_dropdown('warehouse','', (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="powarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" style="width:100%;" ');
										?>
									</div>
								</div>
							<?php } else if($this->session->userdata('warehouse_id')){ ?>
								<div class="col-md-4">
									<div class="form-group">
										<?= lang("warehouse", "powarehouse"); ?>
										<?php
										 $wh = array(""=>"");
										foreach ($warehouses as $warehouse) {
											$wh[$warehouse->id] = $warehouse->name;
										}
										echo form_dropdown('warehouse', '', (isset($_POST['warehouse']) ? $_POST['warehouse'] : $this->session->userdata('warehouse_id')), 'id="powarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');
										?>
									</div>
								</div>
							<?php } ?>
						</div>

                        <div class="customers">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("customers", "scustomer"); ?>
                                    <?php
                                    $cus[""] = "None";
                                    //$this->erp->print_arrays($customers);
                                    if(isset($customers) && $customers != NULL){
                                        foreach ($customers as $customer) {
                                            $cus[$customer->id] = $customer->text;
                                        }
                                    }

                                    echo form_dropdown('customers', $cus, (isset($_POST['customers']) ? $_POST['customers'] : ''), 'id="scustomer" data-placeholder="' . lang("select") . ' ' . lang("customers") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("document", "document"); ?>
                                <input id="document" type="file" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("expense", "expense"); ?>
                                <?php
                                 $exp['po']    = 'Purchase';
                                 $exp['exp']   = 'Expense';

                                echo form_dropdown('expance', $exp, ' ', 'id="poexpance" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("expance") . '"  style="width:100%;" ');
                                ?>
                            </div>
                        </div>

                        <div class="customer_info">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("customer_invoices", "scustomer_no"); ?>
                                    <?php echo form_input('customer_invoices', (isset($_POST['customer_invoices']) ? $_POST['customer_invoices'] : ''), 'class="form-control" id="scustomer_no"  placeholder="' . lang("select") . " " . lang("customer_no") . '" '); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading"><?= lang('please_select_these_before_adding_product'); ?></div>

                               <div class="panel-body" style="padding: 5px;">
									<div class="col-sm-4">
										<div class="form-group">
										<?= lang("supplier", "posupplier"); ?>
										<?php
											$sup[''] = '';
											if(isset($suppliers) && $suppliers!=null){
                                                foreach($suppliers as $supplier){
                                                    $sup[$supplier->id] = $supplier->code .'-'. ($supplier->company ? $supplier->company : $supplier->name);
                                                }
                                            }

											echo form_dropdown('supplier', $sup, (isset($_POST['supplier']) ? $_POST['supplier'] : ''), 'id="posupplier" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("supplier") . '"  style="width:100%;" required="required" ');

										?>
										<input type="hidden" name="supplier_id" value="" id="supplier_id" class="form-control">
										</div>
									</div>

                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="col-md-12 pr_form" id="sticker">
                            <div class="well well-sm">
                                <div class="form-group" style="margin-bottom:0;">
                                    <div class="input-group wide-tip">
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                        <i class="fa fa-2x fa-barcode addIcon"></i></div>
                                        <?php
											if($this->input->get('addpur')){

											$q = $this->db->get_where('erp_products',array('id'=>$this->input->get('addpur')),1);
											$pcode = $q->row()->code;

										}
										echo form_input('add_item', isset($pcode) ? $pcode :'', 'class="form-control input-lg" id="add_item" placeholder="' . $this->lang->line("add_product_to_order") . '"'); ?>
                                        <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <a href="#" id="addManually4"><i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i></a>
											<a href="<?= site_url('purchases/add');?>" class="gos" ></a>
											</div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="col-md-12 pr_form">
                            <div class="control-group table-group">
                                <label class="table-label"><?= lang("order_items"); ?> *</label>
                                <div class="controls table-controls table-responsive">
                                    <table id="poTable"
                                           class="table items table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                        <tr>
											<th  class=""><?= lang("no"); ?></th>
                                            <th class="col-md-4"><?= lang("product_name") . " (" . $this->lang->line("product_code") . ")"; ?></th>
                                            <?php
                                            if ($Settings->product_expiry) {
                                                echo '<th class="col-md-2">' . $this->lang->line("expiry_date") . '</th>';
                                            }
                                            ?>
											<?php
												if ($Owner || $Admin || $GP['purchases-price']) {
													echo '<th class="col-md-1">'.lang("price").'</th>';
												}
                                            ?>
											<?php
												if ($Owner || $Admin || $GP['purchases-cost']) {
													echo '<th class="col-md-1">'.lang("unit_cost").'</th>';
												}
                                            ?>
                                            <th class="col-md-1"><?= lang("quantity"); ?></th>

											<?php
                                            if(isset($inv)){ if($Settings->shipping && $inv) ?>
                                                <th class="col-md-1"><?= lang("quantity_recieve"); ?></th>
                                            <?php } ?>

											<th class="col-md-1"><?= lang("stock_in_hand"); ?></th>
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
                                                    class="currency"><?= $default_currency->code; ?></span>)
                                            </th>
                                            <th style="width: 30px !important; text-align: center;"><i
                                                    class="fa fa-trash-o"
                                                    style="opacity:0.5; filter:alpha(opacity=50);"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>

						<!--Customize Form Expance-->

						<div class="clearfix"></div>
						<div class="exp_form">

							<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
							echo form_open_multipart("account/save_journal", $attrib); ?>
							<div class="panel-body">

								<div class="row">
									<?php
									$description = '';
									if(isset($journals)){
										foreach($journals as $journal1){
											if($journal1->description != ""){
												$description = $journal1->description;
											}
										}
									}
									?>

										<div class="col-md-8"></div>

										<div class="col-md-1">
											<div class="form-group">
												<button type="button" class="btn btn-primary pull-left" id="addDescription"><i class="fa fa-plus-circle"></i></button>
											</div>
										</div>

								</div>
								<div class="col-md-9">
									<div class="col-md-11">
										<div class="form-group">
											<?= lang("description", "description"); ?>
											<?= form_textarea('description', '', 'rows="5" class="form-control" id="details" required="required" '); ?>
										</div>
									</div>
									<div class="col-md-1"></div>
								</div>
								<div class="journalContainer">
									<div class="col-md-12 journal-list">
										<div class="col-md-4">
											<div class="form-group company">
												<?= lang("chart_account", "chart_account"); ?>
												<?php
												$acc_section = array(""=>"");
												foreach($sectionacc as $section){
													$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
												}
												echo form_dropdown('account_section[]', $acc_section, '', 'id="account_section" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("Account") . ' ' . $this->lang->line("Section") . '" required="required" style="width:100%;" ');
												?>
											</div>
										</div>

										<div class="col-md-4">
											<div class="form-group">
												<?= lang("amount", "amount"); ?>
												<?php echo form_input('debit[]', '', 'class="form-control debit1" id="debit"'); ?>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="col-md-offset-9">
										<div class="form-group">
											<label id="calDebit"></label>
											<input type="hidden" id="in_calDebit"  class="in_calDebit" name="in_calDebit" />
											<input type="hidden" id="in_calOrdTax"  class="in_calOrdTax" name="in_calOrdTax" />
										</div>
									</div>
								</div>

							</div>

						</div>
					</div>

					<div class="col-md-12 ">
						<div class="form-group">
							<input type="checkbox" class="checkbox" id="extras" value=""/>
							<label for="extras" class="padding05"><?= lang('more_options'); ?></label>
						</div>
						<div class="row" id="extras-con" style="display: none;">

							<div class="col-md-4 pr_form">
								<div class="form-group">
									<?= lang("discount_percent", "podiscount"); ?>
									<?php echo form_input('discount', '', 'class="form-control input-tip" id="podiscount"'); ?>
								</div>
							</div>

							<div class="col-md-4 pr_form">
								<div class="form-group" style="margin-bottom:5px;">
									<?= lang("shipping", "poshipping"); ?>
									<?php echo form_input('shipping', '', 'class="form-control input-tip" id="poshipping"'); ?>

								</div>
							</div>

							<?php if ($Settings->tax1) { ?>
								<div class="col-md-4">
									<div class="form-group">
										<?= lang('order_tax', 'potax2'); ?>
										<?php
										$tr[""] = "";
										if(isset($tax_rates) && $tax_rates!=null){
                                            foreach ($tax_rates as $tax) {
                                                $tr[$tax->id] = $tax->name;
                                            }
                                        }

										echo form_dropdown('order_tax', $tr, "", 'id="potax2" class="form-control input-tip select" style="width:100%;"');
										?>
									</div>
								</div>
							<?php } ?>
							<!--<div class="status">
    							<div class="col-sm-4">
    								<div class="form-group">
    									<?= lang("purchase_status", "postatus"); ?>
    									<?php
    									//$sst = array('received' => lang('received'), 'pending' => lang('pending'));
    									echo form_dropdown('purchase_status', $sst, '', 'class="form-control input-tip" required="required" id="postatus"'); ?>
    								</div>
    							</div>
                            </div>-->

                            <!-- <div class="col-sm-4">
	                            <div class="form-group">
	                                <?= lang("payment_term", "slpayment_term"); ?>
									<?php
	                                    $ptr[""] = "";
	                                    foreach ($payment_term as $term) {
	                                        $ptr[$term->id] = $term->description;
	                                    }
										echo form_dropdown('payment_term', $ptr, isset($sale_order->payment_term) ? $sale_order->payment_term :"", 'id="slpayment_term" data-placeholder="' . lang("payment_term_tip") .  '" class="form-control input-tip select" style="width:100%;"');
										//echo form_input('payment_term',$ptr,'11', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('payment_term_tip') . '" id="slpayment_term"'); ?>
	                            </div>
	                        </div>-->

						</div>

						<div class="clearfix"></div>

						<div class="form-group pr_form">
							<?= lang("note", "ponote"); ?>
							<?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="ponote" style="margin-top: 10px; height: 100px;"'); ?>
						</div>

					</div>

					<div class="col-md-12">
						<div class="from-group"><?php echo form_submit('add_pruchase', $this->lang->line("submit"), 'id="add_pruchase" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0; display:none;"'); ?>
							<button type="button" class="btn btn-primary" id="add_pruchase_test" style="padding: 6px 15px; margin:15px 0;"><?= lang('submit'); ?></button>
							<button type="button" class="btn btn-danger" id="reset"><?= lang('reset'); ?></button>
						</div>
					</div>
                </div>
                <div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">
                    <table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
                        <tr class="warning">
                            <td class="items"><?= lang('items'); ?> <span class="totals_val pull-right" id="titems">0</span></td>
                            <td><?= lang('total'); ?> <span class="totals_val pull-right" id="total">0.00</span></td>
                            <td class="order_discount"><?= lang('order_discount'); ?> <span class="totals_val pull-right" id="tds">0.00</span></td>
                            <td class="shipping"><?= lang('shipping'); ?> <span class="totals_val pull-right" id="tship">0.00</span></td>
                            <?php if ($Settings->tax2) { ?>
                                <td><?= lang('order_tax'); ?> <span class="totals_val pull-right" id="ttax2">0.00</span></td>
                            <?php } ?>
                            <td><?= lang('grand_total'); ?> <span class="totals_val pull-right" id="gtotal">0.00</span></td>
                        </tr>
                    </table>
                </div>

                <?php echo form_close(); ?>
			</div>

        </div>
    </div>
</div>

<div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="prModalLabel"></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
					<div class="form-group" style="display:none;">
						<label class="col-sm-4 control-label"><?= lang('supplier_products'); ?></label>
						<div class="col-sm-8">
							<input type="hidden" name="psupplier[]" value="" id="psupplier"class="form-control" style="width:100%;" placeholder="<?= lang("select") . ' ' . lang("supplier"); ?>">
						</div>
					</div>
                    <?php if ($Settings->tax1) { ?>
						<div class="form-group">
                            <label class="col-sm-4 control-label"><?= lang('cost_method') ?></label>
                            <div class="col-sm-8">
                                <?php
									$tm = array(
                                        '1' => lang('exclusive'),
                                        '0' => lang('inclusive')
									);
									echo form_dropdown('tax_method', $tm, '', 'class="form-control select" id="tax_method" style="width:100%"')
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?= lang('product_tax'); ?></label>
                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('ptax', $tr, "", 'id="ptax" class="form-control pos-input-tip" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
					<?php if ($Settings->purchase_serial) { ?>
                        <div class="form-group">
							<label for="serial_no" class="col-sm-4 control-label"><?= lang('serial_no'); ?></label>

							<div class="col-sm-8" id="serial"></div>
						</div>
                    <?php } ?>
					<div class="form-group" id="dvpiece">
                        <label for="piece" id="lbpiece" class="col-sm-4 control-label"><?= lang('piece') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="piece">
                        </div>
                    </div>
					<div class="form-group" id="dvwpiece">
                        <label for="wpiece" class="col-sm-4 control-label"><?= lang('wpiece') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="wpiece">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity'); ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pquantity">
                        </div>
                    </div>
                    <?php if ($Settings->product_expiry) { ?>
                        <div class="form-group">
                            <label for="pexpiry" class="col-sm-4 control-label"><?= lang('expiry_date'); ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control date" id="pexpiry">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="poption" class="col-sm-4 control-label"><?= lang('product_option'); ?></label>

                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>
					<div class="form-group">
					      <input type="hidden" name="qty_unit" value="" id="qty_unit">
					</div>
                    <?php if ($Settings->product_discount) { ?>
                        <div class="form-group">
                            <label for="pdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount'); ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pdiscount">
                            </div>
                        </div>
                    <?php } ?>
					<?php if($Owner || $Admin || $GP['purchases-cost']) {?>
                    <div class="form-group">
                        <label for="pcost" class="col-sm-4 control-label"><?= lang('unit_cost'); ?></label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="pcost" style="display:none;">
							<input type="text" class="form-control" id="pcost_none">
                        </div>
                    </div>
					<?php
						foreach($currency as $money){
							if($money->code != 'USD'){
					?>
						<div class="form-group">
							<label for="pcost" class="col-sm-4 control-label">
								<?= lang($money->code); ?>
							</label>
							<div class="col-sm-8">
								<input name="other_amount" type="text" id="<?=$money->code;?>" value="" rate="<?=$money->rate?>" class="pa form-control kb-pad amount_other"/>
							</div>
						</div>
					<?php
							}
						}
					?>
					<?php } ?>
                    <table class="table table-bordered table-striped">
                        <tr>
						    <?php if($Owner || $Admin || $GP['purchases-cost']) {?>
							<th style="width:25%;"><?= lang('net_unit_cost'); ?></th>
							<th style="width:25%;"><span id="net_cost"></span></th>
                            <?php } ?>
							<th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="pro_tax"></span></th>
                        </tr>
                    </table>
                    <input type="hidden" id="punit_cost" value=""/>
                    <input type="hidden" id="old_tax" value=""/>
                    <input type="hidden" id="old_qty" value=""/>
                    <input type="hidden" id="old_cost" value=""/>
                    <input type="hidden" id="realcost" value=""/>
                    <input type="hidden" id="row_id" value=""/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editItem"><?= lang('submit'); ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#slbiller').change(function(){
		billerChange();
		$("#powarehouse").select2().empty();
	});
    var $biller = $("#slbiller");
	$(window).load(function(){
		billerChange();
	});
	function billerChange(){
        var id = $biller.val();

        $("#powarehouse").empty();
        $.ajax({
            url: '<?= base_url() ?>auth/getWarehouseByProject/'+id,
            dataType: 'json',
            success: function(result){
				__setItem('default_warehouse','<?= $setting->default_warehouse ?>');
				var default_warehouse = __getItem('default_warehouse');
                $.each(result, function(i,val){
                    var b_id = val.id;
					var code = val.code;
                    var name = val.name;
                    var opt = '<option value="' + b_id + '">' +code+'-'+ name + '</option>';
                    $("#powarehouse").append(opt);
                });

				//if (default_warehouse) {
				//	$('#powarehouse').select2('val', default_warehouse);
				//}
                //$('#powarehouse option[selected="selected"]').each(
                 //   function() {
                 //       $(this).removeAttr('selected');
                 //   }
                //);
                //
				//if(slwarehouse = __getItem('powarehouse')){
				//	$('#powarehouse').select2("val", powarehouse);
				//}else{
				//	//$('#powarehouse').val($('#powarehouse option:first-child').val()).trigger('change');
				//	//$("#powarehouse").select2("val", "<?//=$Settings->default_warehouse;?>//");
				//}
				var option=$('#powarehouse option:first-child').val();
                $("#powarehouse").select2("val", option);


            }
        });

		$.ajax({
            url: '<?= base_url() ?>sales/getReferenceByProject/po/'+id,
            dataType: 'json',
            success: function(data){
                $("#poref").val(data);
				$("#temp_reference_no").val(data);
            }
        });

    }
    $(document).ready(function () {
		$('body').on('click', '.add_product_auto', function(e) {
			e.preventDefault();
			var pname = $("#name").val();
			var code = $("#code").val();
			var category = $("#category").val();
			var unit = $("#unit").val();
			var cost = $("#cost").val();
			var price = $("#price").val();
			if(pname && code && category && unit && cost && price){
				$(".add_product").trigger("click");
			}
			$(".request_").text("Please input required fields (*)");
		});
		$('body').on('click', '#add_pruchase_test', function(e) {

			var actual_total_balance = parseFloat($(".actual_total_balance").text());
			var pay_s 		= $("#slpayment_status").val();
			var paid_by_1 	= $("#paid_by_1").val();
			var bank 		= $("#bank_account_1").val();
			if(pay_s == "paid" || pay_s == "partial"){
				if(paid_by_1 == "deposit"){
					if(actual_total_balance<=0){
						bootbox.alert('Not allow save: Actual Balance can not less than 0');
						return false;
					}
				}else{
					if(bank == ""){
						bootbox.alert('Please select Bank Account.');
						return false;
					}
				}

				var am1= $("#amount_1").val()-0;
				if(am1<=0){
					bootbox.alert('Total amount can not less than 0.');
					return false;
				}
			}

			$('#add_pruchase').trigger('click');
		});


        var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
        var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
        var items = {};
        <?php
        if(isset($combo_items)) {
            foreach($combo_items as $item) {
            //echo 'ietms['.$item->id.'] = '.$item.';';
                if($item->code) {
                    echo 'add_product_item('.  json_encode($item).');';
                }
            }
        }
        ?>

		$(window).load(function(){
			$("#poexpance").trigger("change");
		});

		$("#poexpance").change(function()
		{
			if($(this).val()=='po')
			{
			  $(".pr_form").css("display","");
			  $(".exp_form").css("display","none");
              $(".powarehouse_o").css("display","block");
              $(".warehouse").css("display","block");
              $(".customer_info").css("display","none");
              $(".status").css("display","block");
              $(".items").css("display","");
              $(".order_discount").css("display","");
              $(".shipping").css("display","");
            }else{
              $(".pr_form").css("display","none");
              $(".exp_form").css("display","");
              $(".powarehouse_o").css("display","none");
              $(".warehouse").css("display","none");
              $(".customer_info").css("display","block");
              $(".status").css("display","none");
              $(".items").css("display","none");
              $(".order_discount").css("display","none");
              $(".shipping").css("display","none");
            }

		});

        <?=isset($_POST['cf']) ? '$("#extras").iCheck("check");': '' ?>
        $('#extras').on('ifChecked', function () {
            $('#extras-con').slideDown();
        });
        $('#extras').on('ifUnchecked', function () {
            $('#extras-con').slideUp();
        });

        $('.attributes').on('ifChecked', function (event) {
            $('#options_' + $(this).attr('id')).slideDown();
        });
        $('.attributes').on('ifUnchecked', function (event) {
            $('#options_' + $(this).attr('id')).slideUp();
        });


		// $("#slref").attr('readonly','readonly');

        $("#poref").attr('readonly', true);
		$('#ref_st').on('ifChanged', function() {
		  if ($(this).is(':checked')) {
			// $("#poref").prop('disabled', false);
			$("#poref").attr('readonly', false);
			$("#poref").val("");
		  }else{
			$("#poref").prop('disabled', true);
			var temp = $("#temp_reference_no").val();
			$("#poref").val(temp);

		  }
		});
        $('#type').change(function () {
            var t = $(this).val();
            if (t !== 'standard') {
                $('.standard').slideUp();
                $('#cost').attr('required', 'required');
                $('#track_quantity').iCheck('uncheck');
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'cost');
            } else {
                $('.standard').slideDown();
                $('#track_quantity').iCheck('check');
                $('#cost').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'cost');
            }
            if (t !== 'digital') {
                $('.digital').slideUp();
                $('#digital_file').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'digital_file');
            } else {
                $('.digital').slideDown();
                $('#digital_file').attr('required', 'required');
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'digital_file');
            }
            if (t !== 'combo') {
                $('.combo').slideUp();
            } else {
                $('.combo').slideDown();
            }
        });

        var t = $('#type').val();
        if (t !== 'standard') {
            $('.standard').slideUp();
            $('#cost').attr('required', 'required');
            $('#track_quantity').iCheck('uncheck');
            $('form[data-toggle="validator"]').bootstrapValidator('addField', 'cost');
        } else {
            $('.standard').slideDown();
            $('#track_quantity').iCheck('check');
            $('#cost').removeAttr('required');
            $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'cost');
        }
        if (t !== 'digital') {
            $('.digital').slideUp();
            $('#digital_file').removeAttr('required');
            $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'digital_file');
        } else {
            $('.digital').slideDown();
            $('#digital_file').attr('required', 'required');
            $('form[data-toggle="validator"]').bootstrapValidator('addField', 'digital_file');
        }
        if (t !== 'combo') {
            $('.combo').slideUp();
        } else {
            $('.combo').slideDown();
        }

        <?php
        if($this->input->post('type') == 'combo') {
            $c = sizeof($_POST['combo_item_code']);
            for ($r = 0; $r <= $c; $r++) {
                if(isset($_POST['combo_item_code'][$r]) && isset($_POST['combo_item_quantity'][$r]) && isset($_POST['combo_item_price'][$r])) {
                    $items[] = array('id' => $_POST['combo_item_id'][$r], 'name' => $_POST['combo_item_name'][$r], 'code' => $_POST['combo_item_code'][$r], 'qty' => $_POST['combo_item_quantity'][$r], 'price' => $_POST['combo_item_price'][$r]);
                }
            }
            echo '
            var ci = '.json_encode($items).';
            $.each(ci, function() { add_product_item(this); });
            ';
        }
        ?>
        function add_product_item(item) {
            if (item == null) {
                return false;
            }
            item_id = item.id;
            if (items[item_id]) {
                items[item_id].qty = (parseFloat(items[item_id].qty) + 1).toFixed(2);
            } else {
                items[item_id] = item;
            }

            $("#prTable tbody").empty();
            $.each(items, function () {
                var row_no = this.id;
                var newTr = $('<tr id="row_' + row_no + '" class="item_' + this.id + '"></tr>');
                tr_html = '<td><input name="combo_item_id[]" type="hidden" value="' + this.id + '"><input name="combo_item_name[]" type="hidden" value="' + this.name + '"><input name="combo_item_code[]" type="hidden" value="' + this.code + '"><span id="name_' + row_no + '">' + this.name + ' (' + this.code + ')</span></td>';
				tr_html += '<td><input class="form-control text-center" name="combo_item_quantity_unit[]" type="text" value="' + formatPurDecimal(this.qty) + '" data-id="' + row_no + '" data-item="' + this.id + '" id="quantity_unit_' + row_no + '" onClick="this.select();"></td>';
                tr_html += '<td><input class="form-control text-center" name="combo_item_price[]" type="text" value="' + formatPurDecimal(this.price) + '" data-id="' + row_no + '" data-item="' + this.id + '" id="combo_item_price_' + row_no + '" onClick="this.select();"></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip del" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#prTable");
            });
            $('.item_' + item_id).addClass('warning');
            return true;

        }

        $(document).on('click', '.del', function () {
            var id = $(this).attr('id');
            delete items[id];
            $(this).closest('#row_' + id).remove();
        });
        var su = 2;
        $('#addSupplier').click(function () {
            if (su <= 5) {
                $('#supplier_1').select2('destroy');
                var html = '<div style="clear:both;height:15px;"></div><div class="row"><div class="col-md-8 col-sm-8 col-xs-8"><input type="hidden" name="supplier_' + su + '", class="form-control" id="supplier_' + su + '" placeholder="<?= lang("select") . ' ' . lang("supplier") ?>" style="width:100%;display: block !important;" /></div><div class="col-md-4 col-sm-4 col-xs-4"><input type="text" name="supplier_' + su + '_price" class="form-control tip" id="supplier_' + su + '_price" placeholder="<?= lang('supplier_price') ?>" /></div></div>';
                $('#ex-suppliers').append(html);
                var sup = $('#supplier_' + su);
                suppliers(sup);
                su++;
            } else {
                bootbox.alert('<?= lang('max_reached') ?>');
                return false;
            }
        });

        var _URL = window.URL || window.webkitURL;
        $("input#images").on('change.bs.fileinput', function () {
            var ele = document.getElementById($(this).attr('id'));
            var result = ele.files;
            $('#img-details').empty();
            for (var x = 0; x < result.length; x++) {
                var fle = result[x];
                for (var i = 0; i <= result.length; i++) {
                    var img = new Image();
                    img.onload = (function (value) {
                        return function () {
                            ctx[value].drawImage(result[value], 0, 0);
                        }
                    })(i);
                    img.src = 'images/' + result[i];
                }
            }
        });
        var variants = <?=json_encode(isset($vars)?$vars:'');?>;
        $(".select-tags").select2({
            tags: variants,
            tokenSeparators: [","],
            multiple: true
        });
        $(document).on('ifChecked', '#attributes', function (e) {
            $('#attr-con').slideDown();
        });
        $(document).on('ifUnchecked', '#attributes', function (e) {
            $(".select-tags").select2("val", "");
            $('.attr-remove-all').trigger('click');
            $('#attr-con').slideUp();
        });
        $('#addAttributes').click(function (e) {
            e.preventDefault();
            var attrs_val = $('#attributesInput').val(), attrs;
            attrs = attrs_val.split(',');
            console.log(attrs);
            for (var i in attrs) {
                if (attrs[i] !== '') {

				   $('#attrTable').show().append('<tr class="attr"><td><input type="hidden" name="attr_name[]" value="' + attrs[i] + '"><span>' + attrs[i] + '</span></td><td class="quantity_unit text-center"><input type="hidden" name="attr_quantity_unit[]" value=""><span></span></td><td class="price text-right"><input type="hidden" name="attr_price[]" value="0"><span>0</span></span></td><td class="text-center"><i class="fa fa-times delAttr"></i></td></tr>');
                }
            }
        });
        $(document).on('click', '.delAttr', function () {
            $(this).closest("tr").remove();
        });
        $(document).on('click', '.attr-remove-all', function () {
            $('#attrTable tbody').empty();
            $('#attrTable').hide();
        });
        var row, warehouses = <?= json_encode($warehouses); ?>;
        $(document).on('click', '.attr td:not(:last-child)', function () {
            row = $(this).closest("tr");
            $('#aModalLabel').text(row.children().eq(0).find('span').text());
			$('#aquantity_unit').val(row.children().eq(1).find('input').val());
            $('#aprice').val(row.children().eq(2).find('span').text());
            $('#aModal').appendTo('body').modal('show');
        });

        $(document).on('click', '#updateAttr', function () {
            var wh = $('#awarehouse').val(), wh_name;
            $.each(warehouses, function () {
                if (this.id == wh) {
                    wh_name = this.name;
                }
            });
			row.children().eq(1).html('<input type="hidden" name="attr_quantity_unit[]" value="' + $('#aquantity_unit').val() + '"><span>' + decimalFormat($('#aquantity_unit').val()) + '</span>');
            row.children().eq(2).html('<input type="hidden" name="attr_price[]" value="' + $('#aprice').val() + '"><span>' + currencyFormat($('#aprice').val()) + '</span>');
            $('#aModal').modal('hide');
        });

		$(document).on('click', '.edit',function(){
			var tr = $(this).parent().parent();
			var qty = tr.find('.rquantity').val();
			var input = '';
			for(i=0;i < qty; i++){
				input += '<input type="text" class="form-control serial_no"><br/>';
			}
			$('#serial').html(input);
		});
		$(document).on('change', '#pquantity',function(){
			var qty = $(this).val();
			var input = '';
			for(i=0;i < qty; i++){
				input += '<input type="text" class="form-control serial_no"><br/>';
			}
			$('#serial').html(input);
		});

	});

    <?php if (isset($product)) { ?>
    $(document).ready(function () {

        var t = "<?=$product->type?>";
        if (t !== 'standard') {
            $('.standard').slideUp();
            $('#cost').attr('required', 'required');
            $('#track_quantity').iCheck('uncheck');
            $('form[data-toggle="validator"]').bootstrapValidator('addField', 'cost');
        } else {
            $('.standard').slideDown();
            $('#track_quantity').iCheck('check');
            $('#cost').removeAttr('required');
            $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'cost');
        }
        if (t !== 'digital') {
            $('.digital').slideUp();
            $('#digital_file').removeAttr('required');
            $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'digital_file');
        } else {
            $('.digital').slideDown();
            $('#digital_file').attr('required', 'required');
            $('form[data-toggle="validator"]').bootstrapValidator('addField', 'digital_file');
        }
        if (t !== 'combo') {
            $('.combo').slideUp();
            //$('#add_item').removeAttr('required');
            //$('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
        } else {
            $('.combo').slideDown();
            //$('#add_item').attr('required', 'required');
            //$('form[data-toggle="validator"]').bootstrapValidator('addField', 'add_item');
        }
        $("#code").parent('.form-group').addClass("has-error");
        $("#code").focus();
        $("#product_image").parent('.form-group').addClass("text-warning");
        $("#images").parent('.form-group').addClass("text-warning");
        $.ajax({
            type: "get", async: false,
            url: "<?= site_url('products/getSubCategories') ?>/" + <?= $product->category_id ?>,
            dataType: "json",
            success: function (scdata) {
                if (scdata != null) {
                    $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                        placeholder: "<?= lang('select_category_to_load') ?>",
                        data: scdata
                    });
                }
            }
        });
        <?php if ($product->supplier1) { ?>
        select_supplier('supplier1', "<?= $product->supplier1; ?>");
        $('#supplier_price').val("<?= $product->supplier1price == 0 ? '' : $this->erp->formatPurDecimal($product->supplier1price); ?>");
        <?php } ?>
        <?php if ($product->supplier2) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_2', "<?= $product->supplier2; ?>");
        $('#supplier_2_price').val("<?= $product->supplier2price == 0 ? '' : $this->erp->formatPurDecimal($product->supplier2price); ?>");
        <?php } ?>
        <?php if ($product->supplier3) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_3', "<?= $product->supplier3; ?>");
        $('#supplier_3_price').val("<?= $product->supplier3price == 0 ? '' : $this->erp->formatPurDecimal($product->supplier3price); ?>");
        <?php } ?>
        <?php if ($product->supplier4) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_4', "<?= $product->supplier4; ?>");
        $('#supplier_4_price').val("<?= $product->supplier4price == 0 ? '' : $this->erp->formatPurDecimal($product->supplier4price); ?>");
        <?php } ?>
        <?php if ($product->supplier5) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_5', "<?= $product->supplier5; ?>");
        $('#supplier_5_price').val("<?= $product->supplier5price == 0 ? '' : $this->erp->formatPurDecimal($product->supplier5price); ?>");
        <?php } ?>
        function select_supplier(id, v) {
            $('#' + id).val(v).select2({
                minimumInputLength: 1,
                data: [],
                initSelection: function (element, callback) {
                    $.ajax({
                        type: "get", async: false,
                        url: "<?= site_url('suppliers/getSupplier') ?>/" + $(element).val(),
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
        }

        var whs = $('.wh');
        $.each(whs, function () {
            $(this).val($('#r' + $(this).attr('id')).text());
        });
    });

    <?php } ?>
</script>

<div class="modal" id="aModal" tabindex="-1" role="dialog" aria-labelledby="aModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="aModalLabel"><?= lang('add_product_manually'); ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">

					<div class="form-group">
							<label for="aquantity_unit" class="col-sm-4 control-label"><?= lang('quantity_unit'); ?></label>

							<div class="col-sm-8">
								<input type="text" class="form-control" id="aquantity_unit">
							</div>
					</div>

                    <div class="form-group">
                        <label for="aprice" class="col-sm-4 control-label"><?= lang('price'); ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="aprice">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateAttr"><?= lang('submit'); ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

	$(window).load(function(){
		var al = '<?php echo $this->input->get('addpur');?>';

		if(al){

			var test = $("#add_item").val();
				$.ajax({
					type: 'get',
					url: '<?= site_url('purchases/suggestions'); ?>',
					dataType: "json",
					data: {
						term: test,
						warehouse_id: __getItem('powarehouse'),
							supplier_id: __getItem('posupplier')
					},
					success: function (data) {
						  for(var i = 0; i < data.length; i++){
							comment = data[i];
							add_purchase_item(comment);
						  }
						 $("#add_item").val('');
						var url = $(".gos").attr('href');
						window.location.href = url;

					}
				});
				 if (__getItem('posupplier')) {
					//__removeItem('posupplier');
				}
				 if (__getItem('powarehouse')) {
					//__removeItem('powarehouse');
				}
		}
    });
</script>