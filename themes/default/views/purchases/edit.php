<style type="text/css">
	.select2-container {
		width: 100% !important;
	}
</style>
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
			div += '	<div class="col-md-6">';
			div += '			<div class="form-group company">';
			div += '				<select class="form-control input-tip select" id="select" name="account_section[]" style="width:100% !important;" required="required">';
			div += '				<?php foreach($sectionacc as $section){ ?>';
			div += '					<option value="<?=$section->accountcode?>"><?=$section->accountcode . " | " . $section->accountname; ?></option>';
			div += '				<?php } ?>';
			div += '				</select>';
			div += '			</div>';
			div += '		</div>';
			
			div += '		<div class="col-md-5">';
			div += '			<div class="form-group">';
			div += '				<input type="text" name="debit[]" value="" class="form-control debit'+FieldCount+'" id="debit"> ';
			div += '			</div>';
			div += '		</div>';
					
			div += '		<div class="col-md-1">';
			div += '			<button type="button" data="'+FieldCount+'" class="removefile btn btn-danger">&times;</button>';
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
		/*
		$("#potax2").on("change",function()
		{
			var Debit_t = $("#calDebit").html()-0;
			
			if (site.settings.tax2 != 0) {
				if (potax2 = __getItem('potax2')) {
					$.each(tax_rates, function () {
						if (this.id == potax2) {
							if (this.type == 2) {
								invoice_tax = parseFloat(this.rate);
							}
							if (this.type == 1) {
								invoice_tax = parseFloat(((Debit_t) * this.rate) / 100);
							}
						}
						
					});
				}
			}
			alert(invoice_tax);
			if (site.settings.tax2 != 0) {
            $('#ttax2').text(formatPurDecimal(invoice_tax));
			}
			
			var gtotal = (parseFloat(Debit_t) + formatPurDecimal(invoice_tax));
			$('#gtotal').text(formatPurDecimal(gtotal));
		});
		*/
		
		
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
		
		$("#checkSave").click(function(){
			if($("#calDebit").text() != $("#calCredit").text()){
				bootbox.alert('Your Debit Credit is difference ! \nPlease check your amount');
				return false;
			}
		});
        
        $(".datetime").datetimepicker({
            format: site.dateFormats.js_ldate,
            fontAwesome: true,
            language: 'erp',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0
        }).datetimepicker('update', '<?= $this->erp->hrld($purchase->date);?>');
		
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
 
	$('#pcost').live('change',function(){
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
 
 });
</script>
<!--End of Expense JS-->
<script type="text/javascript">
    var count = 1, an = 1, po_edit = true, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>, DC = '<?=$default_currency->code?>', shipping = 0,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>, poitems = {},
        audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3'),
        audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    $(window).bind("load", function() {
        <?= ($inv->status == 'received' || $inv->status == 'partial') ? '$(".rec_con").show();' : '$(".rec_con").hide();'; ?>
    });
	
    $(document).ready(function () {
		$(window).load(function(){
			$("#slbiller").attr('readonly',true);
		});
		$('body').on('click', '#add_pruchase_test', function(e) {
			e.preventDefault();
			var actual_total_balance = parseFloat($(".actual_total_balance").text());
			var pay_s = $("#slpayment_status").val();
			var paid_by_1 = $("#paid_by_1").val();
			var bank = $("#bank_account_1").val();
			var am1= $("#amount_1").val()-0;
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
				
				if(am1<=0){
					bootbox.alert('Total amount can not less than 0.');
					return false;
				}
			}
			$('#edit_pruchase').trigger('click');
		});
		
        <?= ($inv->status == 'received' || $inv->status == 'partial') ? '$(".rec_con").show();' : '$(".rec_con").hide();'; ?>
        $('#postatus').change(function(){
            var st = $(this).val();
            if (st == 'received' || st == 'partial') {
                $(".rec_con").show();
            } else {
                $(".rec_con").hide();
            }
        });
        <?php if ($inv) {  ?>
		
		if (__getItem('posupplier')) {
			__removeItem('posupplier');
		}
		if (__getItem('poid')) {
			__removeItem('poid');
		}		
        __setItem('podate', '<?= date($dateFormats['php_ldate'], strtotime($inv->date))?>');
		__setItem('poexpance','<?=$inv->type_of_po?>');
        __setItem('posupplier', '<?=$inv->supplier_id?>');
        __setItem('poref', '<?=$inv->reference_no?>');
		__setItem('order_ref', '<?=$inv->order_ref?>');
        __setItem('powarehouse', '<?=$inv->warehouse_id?>');
		__setItem('edit_status', '<?=$edit_status?>');
        __setItem('postatus', '<?=$inv->status?>');
        __setItem('ponote', '<?= str_replace(array("\r", "\n","'"), array("","","&#039"), $this->erp->decode_html($inv->note)); ?>');
        __setItem('podiscount', '<?=$inv->order_discount_id?>');
        __setItem('potax2', '<?=$inv->order_tax_id?>');
        __setItem('poshipping', '<?=$inv->shipping?>');
        __setItem('popayment_term', '<?=$inv->payment_term?>');
        __setItem('slpayment_status', '<?=$inv->payment_status?>');
        if (parseFloat(__getItem('potax2')) >= 1 || __getItem('podiscount').length >= 1 || parseFloat(__getItem('poshipping')) >= 1) {
            __setItem('poextras', '1');
        }
        //__setItem('posupplier', '<?=$inv->supplier_id?>');
		 <?php if ($inv->type_of_po=='po') { ?>
		
        __setItem('poitems', JSON.stringify(<?=$inv_items;?>));
		
		 <?php 
			}
		 } 
		 ?>
		
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
		
        $(document).on('change', '#podate', function (e) {
            __setItem('podate', $(this).val());
        });
        if (podate = __getItem('podate')) {
            $('#podate').val(podate);
        }

        ItemnTotals();
        $("#add_item").autocomplete({
            source: '<?= site_url('purchases/suggestions'); ?>',
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
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
                    $(this).removeClass('ui-autocomplete-loading');
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
            if (!$('#mcode').val()) {
                $('#mError').text('<?=lang('product_code_is_required')?>');
                $('#mError-con').show();
                return false;
            }
            if (!$('#mname').val()) {
                $('#mError').text('<?=lang('product_name_is_required')?>');
                $('#mError-con').show();
                return false;
            }
            if (!$('#mcategory').val()) {
                $('#mError').text('<?=lang('product_category_is_required')?>');
                $('#mError-con').show();
                return false;
            }
            if (!$('#munit').val()) {
                $('#mError').text('<?=lang('product_unit_is_required')?>');
                $('#mError-con').show();
                return false;
            }
            if (!$('#mcost').val()) {
                $('#mError').text('<?=lang('product_cost_is_required')?>');
                $('#mError-con').show();
                return false;
            }
            if (!$('#mprice').val()) {
                $('#mError').text('<?=lang('product_price_is_required')?>');
                $('#mError-con').show();
                return false;
            }

            var msg, row = null, product = {
                type: 'standard',
                code: $('#mcode').val(),
                name: $('#mname').val(),
                tax_rate: $('#mtax').val(),
                tax_method: $('#mtax_method').val(),
                category_id: $('#mcategory').val(),
                unit: $('#munit').val(),
                cost: $('#mcost').val(),
                price: $('#mprice').val()
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
        $(window).bind('beforeunload', function (e) {
            $.get('<?=site_url('welcome/set_data/remove_pols/1');?>');
            if (count > 1) {
                var message = "You will loss data!";
                return message;
            }
        });
        $('#reset').click(function (e) {
            $(window).unbind('beforeunload');
        });
        $('#edit_pruchase').click(function (e) {
            $(window).unbind('beforeunload');
            $('form .edit-po-form').submit();
        });
			
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
			  $(".customers_info").css("display", "none");
			  $(".items").css("display", "");
              $(".order_discount").css("display", "");
              $(".shipping").css("display", "");
              $(".postatus").css("display", "block");
			}else{
			  $(".pr_form").css("display","none");
			  $(".exp_form").css("display","");
			  $(".powarehouse_o").css("display","none");
			  $('input[name="debit[]"], input[name="credit[]"]').trigger("change");
			  $("#potax2").change();
			  $(".customers_info").css("display", "block");
			  $(".items").css("display", "none");
              $(".order_discount").css("display", "none");
              $(".shipping").css("display", "none");
              $(".postatus").css("display", "none");
			}
			
		});
		
		$("#pocustomer").change(function()
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
								$("#pocustomer_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer_invoice') ?>").select2({
									placeholder: "<?= lang('select_customer_to_load') ?>",
									data: scdata
								});
							}else{
								$("#pocustomer_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer_invoice') ?>").select2({
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
					$("#pocustomer_no").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
						placeholder: "<?= lang('select_customer_to_load') ?>",
						data: [
							{id: '', text: 'None'},
							<?php if($invoices) { foreach($invoices as $invoice) { ?>
								{id: "<?= $invoice->id; ?>", text: "<?= $invoice->text; ?>"},
							<?php } } ?>
						]
					});
				}
		   $('#modal-loading').hide();
		
		}).trigger("change");
		
    });


</script>
<!--Content Products--->
<div class="box">

    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-edit"></i><?= lang('edit_purchase'); ?></h2>
    </div>
    
	<div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-po-form');
                echo form_open_multipart("purchases/edit/" . $inv->id, $attrib)
                ?>

                <div class="row">
                    <div class="col-lg-12">

                        <?php if ($Owner || $Admin || $Settings->allow_change_date == 1) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "podate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : $this->erp->hrld($purchase->date)), 'class="form-control input-tip datetime" id="podate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>
						
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("reference_no", "poref"); ?>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $purchase->reference_no), 'class="form-control input-tip" id="poref" required="required" readonly'); ?>
								<input type="hidden"  name="quote_id"  id="quote_id" value="<?= $quote_id?$quote_id:'' ?>" />
							</div>
                        </div>
						
						<?php if($inv->order_ref){ ?>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang("order_ref", "order_ref"); ?>
									<?php echo form_input('order_ref', $inv->order_ref, 'class="form-control input-tip" id="order_ref" readonly'); ?>
								</div>
							</div>
						<?php } ?>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php
                                    $default_value = $billers[0]->id;
                                    echo get_dropdown_project('biller', 'slbiller', $default_value);
                                ?>
                            </div>
                        </div>
                        
						<div class="col-md-4 powarehouse_o" >
                            <div class="form-group">
                                <?= lang("warehouse", "powarehouse"); ?>
                                <?php
                                $wh[''] = '';
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->code .'-'.$warehouse->name;
                                }
                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $purchase->warehouse_id), 'id="powarehouse" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" required="required" style="width:100%;pointer-events:none;" ');
                                ?>
                            </div>
                        </div>
                        
						<div class="customers">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("customers", "pocustomer"); ?>
                                    <?php
                                    $cuss[''] = 'None';
                                    foreach ($customers as $customer) {
                                        $cuss[$customer->id] = $customer->text;
                                    }
                                    echo form_dropdown('customers', $cuss, ($purchase->customer_id? $purchase->customer_id : ''), 'id="pocustomer" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("customers") . '" style="width:100%;" ');
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("document", "document") ?>
                                <input id="document" type="file" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>

						<div class="col-md-4">
							<div class="form-group">
								<?= lang("expense", "expense"); ?>
								<?php
									if($inv->type_of_po == "po"){
										$exp['po']    = 'Purchase';
									}else{
										$exp['exp']   = 'Expense';
									}
								 
								 
						 
								echo form_dropdown('expance', $exp,(isset($_POST['expense']) ? $_POST['expense'] : $inv->type_of_po), 'id="poexpance" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("expance") . '"  style="width:100%;" ');
								?>
							</div>
                        </div>
						
						<div class="customers_info">
							<div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("customers_invoice", "customer_no"); ?>
                                    <?php echo form_input('customer_no', ($purchase->sale_id? $purchase->sale_id : ''), 'class="form-control" id="pocustomer_no"  placeholder="' . lang("select") . " " . lang("customer_no") . '" '); ?>
                                </div>
                            </div>
						</div>

                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div
                                    class="panel-heading"><?= lang('please_select_these_before_adding_product') ?>
								</div>
							
                                <div class="panel-body" style="padding: 5px;">
									<div class="col-sm-4">
										<div class="form-group">
										<?= lang("supplier", "posupplier"); ?>
										<?php 
											$sup[''] = '';
											foreach($suppliers as $supplier){
												$sup[$supplier->id] = $supplier->code .'-'. $supplier->username;
											}
											if($inv->order_ref!=""){
												echo form_dropdown('supplier', $sup, (isset($_POST['supplier']) ? $_POST['supplier'] : $purchase->supplier_id), 'id="posupplier" readonly class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("supplier") . '"  style="width:100%;" required="required" ');
											}else{
												echo form_dropdown('supplier', $sup, (isset($_POST['supplier']) ? $_POST['supplier'] : $purchase->supplier_id), 'id="posupplier" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("supplier") . '"  style="width:100%;" required="required" ');
											}
										?>
										<input type="hidden" name="supplier_id" value="" id="supplier_id" class="form-control">
										</div>
									</div>
									
                                    <!--<div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("supplier", "posupplier"); ?>
											<?php if($inv->order_ref!=""){?>
                                            <div class="input-group">
                                                <input type="hidden" name="supplier" readonly  id="posupplier"
                                                       class="form-control" style="width:100%;"
                                                       placeholder="<?= lang("select") . ' ' . lang("supplier") ?>">

                                                <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                                    <a href="#" id="removeReadonly">
                                                        <i class="fa fa-unlock" id="unLock"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <input type="hidden" name="supplier_id" value="" id="supplier_id" class="form-control">
											<?php }else{ ?>
											<div class="input-group">
                                                <input type="hidden" name="supplier"  id="posupplier"
                                                       class="form-control" style="width:100%;"
                                                       placeholder="<?= lang("select") . ' ' . lang("supplier") ?>">

                                                <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                                    <a href="#" id="removeReadonly">
                                                        <i class="fa fa-unlock" id="unLock"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <input type="hidden" name="supplier_id" value="" id="supplier_id" class="form-control">
											<?php } ?>
                                        </div>
                                    </div>-->

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
										
											if($this->input->get('editpur')){
											
											$q = $this->db->get_where('erp_products',array('id'=>$this->input->get('editpur')),1);
											$pcode = $q->row()->code;
											
										}
										echo form_input('add_item', isset($pcode) ? $pcode : '', 'class="form-control input-lg" id="add_item" placeholder="' . $this->lang->line("add_product_to_order") . '"'); ?>
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <a href="#" id="addManually4">
												<i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i>
											</a>
										</div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="col-md-12 pr_form">
                            <div class="control-group table-group">
                                <label class="table-label"><?= lang("order_items"); ?></label>

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
                                                    class="currency"><?= $default_currency->code ?></span>)
                                            </th>
                                            <th style="width: 30px !important; text-align: center;">
                                                <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                            </th>
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
											$old_transno = $journal1->tran_no;
											if($journal1->description != ""){
											
												$description = $journal1->description;
											}
										}
									}
									?>
								
									<div class="col-md-8"></div>
									
									<div class="col-md-12">
										<div class="form-group">
											<button style="margin-right: 30px;" type="button" class="btn btn-primary pull-right" id="addDescription"><i class="fa fa-plus-circle"></i></button>
										</div>
									</div>
									
								</div>
								<div class="col-md-12">
									<div class="col-md-12">
										<div class="form-group">
											<?= lang("description", "description") ?>
											<?= form_textarea('description', $purchase->note, 'rows="5" class="form-control" id="details" required="required" '); ?>
										</div>
									</div>
									<div class="col-md-1"></div>
								</div>
								<div class="row journalContainer">
									<div class="col-md-12">
										<div class="col-md-6">
											<div class="form-group margin-b-5"><?= lang("chart_account", "chart_account"); ?></div>
										</div>
										<div class="col-md-6"><div class="form-group margin-b-5"><?= lang("amount", "amount"); ?></div></div>
										<input type="hidden" name="old_transno" value="<?= $old_transno; ?>">
									</div>
									<?php
									$n = 1;
									$debit = 0;
									$credit = 0;
									if(isset($journals)){
										foreach($journals as $journal){
											
											if($journal->debit != 0 && $acc_setting->default_purchase_tax != $journal->account_code){
											
										?>
											<div class="col-md-12 journal-list">
												<div class="col-md-6">
													<div class="form-group company">
														<?php
														$acc_section = array(""=>"");
														foreach($sectionacc as $section){
															$acc_section[$section->accountcode] = $section->accountcode.' | '.$section->accountname;
														}
														echo form_dropdown('account_section[]', $acc_section, $journal->account_code,'', 'id="account_section" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("Account") . ' ' . $this->lang->line("Section") . '" required="required" style="width: 100% !important;"');
														?>
														<input type="hidden" name="tran_id[]" value="<?= $journal->tran_id ?>">
													</div>
												</div>
												
												<div class="col-md-5">
													<div class="form-group">
														<?php echo form_input('debit[]', ($journal->debit!=0?$journal->debit:$journal->credit), 'class="form-control debit" id="debit debit'.$n.'"'); ?>
													</div>
												</div>

												<div class="col-md-1">
													<div class="form-group ">
														<button type="button" class="removefiles btn btn-danger">&times;</button>
													</div>
												</div>
											</div>
											
											<?php 
											$debit += $journal->debit;
											$n++;
											}
										}
									}
									?>
									
								</div>
								<div class="col-md-6"></div>
								<div class="col-md-5">
									<div class="form-group">
										<label id="calDebit" style="padding-left: 18px;"><?=$debit?></label>
										<input type="hidden" id="in_calDebit" value="<?=$debit?>"  class="in_calDebit" name="in_calDebit" />
										<input type="hidden" id="in_calOrdTax"  class="in_calOrdTax" name="in_calOrdTax" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>	
						
				<!--End Customize Form Expance-->
				
				<div class="col-md-12">
					<div class="form-group">
						<input type="checkbox" class="checkbox" id="extras" value=""/>
						<label for="extras" class="padding05"><?= lang('more_options') ?></label>
					</div>
					<div class="row" id="extras-con" style="display: none;">
						
						<div class="col-md-4 pr_form">
							<div class="form-group">
								<?= lang("discount_percent", "podiscount"); ?>
								<?php echo form_input('discount', '', 'class="form-control input-tip" id="podiscount"'); ?>
							</div>
						</div>

						<div class="col-md-4 pr_form">
							<div class="form-group">
								<?= lang("shipping", "poshipping"); ?>
								<?php echo form_input('shipping', '', 'class="form-control input-tip" id="poshipping"'); ?>
							</div>
						</div>
						
						<?php if ($Settings->tax1) { ?>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('order_tax', 'potax2') ?>
									<?php
									$tr[""] = "";
									foreach ($tax_rates as $tax) {
										$tr[$tax->id] = $tax->name;
									}
									echo form_dropdown('order_tax', $tr, "", 'id="potax2" class="form-control input-tip select" style="width:100%;"');
									?>
								</div>
							</div>
						<?php } ?>
						<!--<div class="postatus">
							<div class="col-sm-4">
								<div class="form-group">
									<?= lang("purchase_status", "postatus"); ?>
									
									<?php 
									$sst = array('received' => lang('received'), 'pending' => lang('pending'));
									echo form_dropdown('purchase_status', $sst, '', 'class="form-control input-tip" required="required" id="postatus"'); ?>
								</div>
							</div>
						</div>-->

                        <!--<div class="col-sm-4">
							<div class="form-group">
								<?= lang("payment_term", "slpayment_term"); ?>
								<?php 
									$pt[''] = '';
									foreach($payment_term as $pterm){
										$pt[$pterm->id] = $pterm->description;
									}
									echo form_dropdown('payment_term', $pt, (isset($_POST['payment_term']) ? $_POST['payment_term'] : $purchase->payment_term), 'id="slpayment_term" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("payment_term") . '" required="required" style="width:100%;" ');
								?>
							</div>
						</div>-->

					   <!-- <div class="col-sm-4 pr_form">
							<div class="form-group">
								<?= lang("payment_status", "slpayment_status"); ?>
								<?php $pst = array('pending' => lang('pending'), 'due' => lang('due'), 'partial' => lang('partial'), 'paid' => lang('paid'));
								echo form_dropdown('payment_status', $pst, $purchase->payment_status, 'class="form-control input-tip" id="slpayment_status"'); ?>
								
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
                    <div class="from-group">
                        <?php echo form_submit('edit_pruchase', $this->lang->line("submit"), 'id="edit_pruchase" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;display:none;"'); ?>
						<button type="button" class="btn btn-primary" id="add_pruchase_test" style="padding: 6px 15px; margin:15px 0;"><?= lang('submit') ?></button>
						<button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>
					</div>
				</div>
                
				<div class="clearfix"></div>
				<div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">
					<table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
						<tr class="warning">
							<td class="items"><?= lang('items') ?> <span class="totals_val pull-right" id="titems">0</span></td>
							<td><?= lang('total') ?> <span class="totals_val pull-right" id="total">0.00</span></td>
							<td class="order_discount"><?= lang('order_discount') ?> <span class="totals_val pull-right" id="tds">0.00</span></td>
							<?php if ($Settings->tax2) { ?>
								<td><?= lang('order_tax') ?> <span class="totals_val pull-right" id="ttax2">0.00</span></td>
							<?php } ?>
							<td class="shipping"><?= lang('shipping') ?> <span class="totals_val pull-right" id="tship">0.00</span></td>
							<td><?= lang('grand_total') ?> <span class="totals_val pull-right" id="gtotal">0.00</span></td>
						</tr>
					</table>
				</div>
				
			</div>
			
		</div>
		

        <?php echo form_close(); ?>

    </div>

</div>
<!---End of Products--->
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
					<div class="form-group" style="display:none !important;">
						<label class="col-sm-4 control-label"><?= lang('suppliers') ?></label>
						<div class="col-sm-8">
						   <input type="hidden" name="psupplier[]" value="" id="psupplier"class="form-control" style="width:100%;" placeholder="<?= lang("select") . ' ' . lang("supplier") ?>">
							
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
                            <label class="col-sm-4 control-label"><?= lang('product_tax') ?></label>
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
                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pquantity">
                        </div>
                    </div>
                    <?php if ($Settings->product_expiry) { ?>
                        <div class="form-group">
                            <label for="pexpiry" class="col-sm-4 control-label"><?= lang('expiry_date') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control date" id="pexpiry">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="poption" class="col-sm-4 control-label"><?= lang('product_option') ?></label>

                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>
                    <?php if ($Settings->product_discount) { ?>
                        <div class="form-group">
                            <label for="pdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pdiscount">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pcost" class="col-sm-4 control-label"><?= lang('unit_cost') ?></label>

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
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_cost'); ?></th>
                            <th style="width:25%;"><span id="net_cost"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="pro_tax"></span></th>
                        </tr>
                    </table>
                    <input type="hidden" id="punit_cost" value=""/>
                    <input type="hidden" id="old_tax" value=""/>
                    <input type="hidden" id="old_qty" value=""/>
                    <input type="hidden" id="old_cost" value=""/>
                    <input type="hidden" id="row_id" value=""/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editItem"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
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
		
	   
	
        

		
		 
    });
	
$(window).load(function(){	
		var al = '<?php echo $this->input->get('editpur');?>';
		
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
						//var url = $(".gos").attr('href');
						//window.location.href = url;
						
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