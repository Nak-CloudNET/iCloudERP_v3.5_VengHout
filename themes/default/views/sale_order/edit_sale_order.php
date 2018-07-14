<?php
	// $this->erp->print_arrays($inv);
?>
<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    //var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
    //var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    $(document).ready(function () {
	
        <?php if ($inv) { ?>
        __setItem('sldate', '<?= $this->erp->hrld($inv->date) ?>');
        __setItem('sldelidate', '<?= $this->erp->hrsd($inv->delivery_date) ?>');
        __setItem('slcustomer', '<?= $inv->customer_id ?>');
        __setItem('slbiller', '<?= $inv->biller_id ?>');
        __setItem('slref', '<?= $inv->reference_no ?>');        
        __setItem('slwarehouse', '<?= $inv->warehouse_id ?>');
        __setItem('slsale_status', '<?= $inv->sale_status ?>');
        __setItem('slpayment_status', '<?= $inv->payment_status ?>');
        __setItem('slpayment_term', '<?= $inv->payment_term ?>');
        __setItem('slnote', '<?= str_replace(array("'", ""), "&#039", $this->erp->decode_html($inv->note)); ?>');
        __setItem('slinnote', '<?= str_replace(array("'", ""), "&#039", $this->erp->decode_html($inv->staff_note)); ?>');
        __setItem('sldiscount', '<?= $inv->order_discount_id ?>');
		__setItem('delivery_by', '<?= $inv->delivery_by ?>');
        __setItem('sltax2', '<?= $inv->order_tax_id ?>');
        __setItem('slshipping', '<?= $inv->shipping ?>');
		__setItem('paid', '<?= $inv->paid ?>');
        __setItem('sloitems', JSON.stringify(<?= $inv_items; ?>));
        <?php } ?>

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
		
        $(document).on('change', '#sldate', function (e) {
            __setItem('sldate', $(this).val());
        });
        if (sldate = __getItem('sldate')) {
            $('#sldate').val(sldate);
			
        }
		if (slpayment_status = __getItem('slpayment_status')) {
            $('#slpayment_status').val(slpayment_status);
			$('#payments').css('display','block');
        }
		if (paid = __getItem('paid')) {
            $('#amount_1').val(paid);
        }
        $(document).on('change', '#slbiller', function (e) {
            __setItem('slbiller', $(this).val());
        });
		
        if (slbiller = __getItem('slbiller')) {
            $('#slbiller').val(slbiller);
        }		

        if (delivery_date = __getItem('sldelidate')) {
            $('#delivery_date').val(delivery_date);
            
        }

        ItemnTotals();
	    $("#add_item").autocomplete({
            source: function (request, response) {
                if (!$('#slcustomer').val()) {
                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    $('#add_item').focus();
                    return false;
                }
                var test = request.term;
				if($.isNumeric(test)){
					$.ajax({
						type: 'get',
						url: '<?= site_url('sale_order/suggests'); ?>',
						dataType: "json",
						data: {
							term: request.term,
							warehouse_id: $("#slwarehouse").val(),
							customer_id: $("#slcustomer").val()
						},
						success: function (data) {
							
							response(data);
						}
					});
				}else{
					$.ajax({
						type: 'get',
						url: '<?= site_url('sale_order/suggestionsSale'); ?>',
						dataType: "json",
						data: {
							term: request.term,
							warehouse_id: $("#slwarehouse").val(),
							customer_id: $("#slcustomer").val()
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
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    // $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
				
                if (ui.item.id !== 0) {
                   var product_type = ui.item.row.type;
					if (product_type == 'digital') {
						$.ajax({
							type: 'get',
							url: '<?= site_url('sales/getDigitalPro'); ?>',
							dataType: "json",
							data: {
								id: ui.item.item_id
							},
							success: function (result) {
								$.each( result, function(key, value) {
									var row = add_invoice_item(value);
									if (row)
										$(this).val('');
								});
							}
						});
						$(this).val('');
					} else {
						var row = add_invoice_item(ui.item);
						if (row)
							$(this).val('');
					}
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

        $(window).bind('beforeunload', function (e) {
            __setItem('remove_slls', true);
            if (count > 1) {
                var message = "You will loss data!";
                return message;
            }
        });
        $('#reset').click(function (e) {
            $(window).unbind('beforeunload');
        });
        $('#edit_sale').click(function (e) {
			
			e.preventDefault();
			var message = '';
			var help = false;
			$('.qty_rec').each(function() {
				var tr = $(this).closest('tr');
				var order_qty = tr.find('.psoqty').val() - 0;
				var received_qty = $(this).val() - 0;
				var oh_qty = tr.find('.qty_oh').val() - 0;
				var qty_balance = oh_qty - (received_qty + order_qty);
				if(qty_balance < 0) {
					var product_name = $(this).parent().parent().closest('tr').find('.rname').val();
					if(order_qty > 0) {
						message += '- ' + product_name +' : Order = ' + formatDecimal(received_qty) + ', Ordered = ' + formatDecimal(order_qty) + ', QOH = ' + formatDecimal(oh_qty) + ' !\n';
						help = true;
					}else {
						message += '- ' + product_name +' : Order = ' + formatDecimal(received_qty) + ', QOH = ' + formatDecimal(oh_qty) + ' !\n';
						help = true;
					}
				}
			});
			if(help) {
				message += "\n Do you want to continue your sale order? \n";
				message += "Press \"OK\" is continue and \"Cancel\" is recheck!!!";
				var result = confirm(message);
					if (result) {
						//return false;
					}else {
						return false;
					}
			}
			
            $(window).unbind('beforeunload');
            $('form.edit-so-form').submit();
        });
    });
</script>

<style>
.select2-result.select2-result-unselectable.select2-disabled {
	display: none;
}
</style>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('edit_sale_order'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12" style="margin-bottom:30px;">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-so-form');
                echo form_open_multipart("sale_order/edit_sale_order/" . $inv->id, $attrib)
                ?>


                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin || $Settings->allow_change_date == 1) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "sldate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : $this->erp->hrld($inv->date)), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("reference_no", "slref"); ?>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ''), 'class="form-control input-tip" id="slref" required="required" readonly'); ?>
                            </div>
                        </div>
						<?php if($quote && $quotes->reference_no) { ?>
						<div class="col-md-4">
							<div class="form-group">
								<?= lang("quote_reference_no", "qtref"); ?>
								<?php echo form_input('quote_reference_no',$quotes->reference_no, 'class="form-control input-tip" id="qtref" readonly'); ?>
							</div>
                        </div>
						<?php } ?>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= get_dropdown_project('biller', 'slbiller', $inv->biller_id); ?>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading"><?= lang('please_select_these_before_adding_product') ?></div>
                                <div class="panel-body" style="padding: 5px;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("warehouse", "slwarehouse"); ?>
                                            <?php
                                            $wh[''] = '';
                                            foreach ($warehouses as $warehouse) {
                                                $wh[$warehouse->id] = $warehouse->name;
                                            }
                                            echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $inv->warehouse_id), 'id="slwarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');
                                            ?>
                                        </div>
                                    </div>

									<?php if($setting->bill_to == 1) { ?>
									<div class="col-md-4">
										<div class="form-group">
											<?= lang("bill_to", "bill_to"); ?>
											<?php echo form_input('bill_to', $inv->bill_to, 'class="form-control input-tip" id="bill_to"'); ?>
										</div>
									</div>
									<?php } ?>
									
									<?php if($setting->show_po) { ?>
									<div class="col-md-4">
										<div class="form-group">
											<?= lang("po", "po"); ?>
											<?php echo form_input('po', $inv->po, 'class="form-control input-tip" id="po"'); ?>
										</div>
									</div>
									<?php } ?>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("delivery_date", "delivery_date"); ?>
                                            <?php echo form_input('delivery_date', (isset($_POST['delivery_date']) ? $_POST['delivery_date'] : $inv->delivery_date), 'class="form-control input-tip date" id="delivery_date"'); ?>
                                        </div>
                                    </div>
                                    
									<div class="col-md-4">
										<div class="form-group">
										<?= lang("saleman", "saleman"); ?>
										<?php
										$sm[''] = '';
										foreach($agencies as $agency){
											$sm[$agency->id] = $agency->username;
										}
										echo form_dropdown('saleman', $sm, (($inv->saleman_by != "")? $inv->saleman_by : ''), 'id="slsaleman" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("saleman") . '" style="width:100%;" ');
										?>
										</div>
                                    </div>
									
									 <div class="col-md-4">
										<div class="form-group">
											<?= lang("group_area", "group_area"); ?>
											<?php
											 $ar[''] = '';
											foreach ($areas as $area) {
												$ar[$area->areas_g_code] = $area->areas_group;
											}
											echo form_dropdown('area', $ar, (isset($_POST['area']) ? $_POST['area'] : $inv->group_areas_id), 'id="slarea" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("group_area") . '" required="required" style="width:100%; pointer-events:none;" ');
											?>
										</div>
                                    </div>
									
									<div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("customer", "slcustomer"); ?>
                                            <div class="input-group">
                                                <?php
                                                    echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'id="slcustomer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control input-tip" style="width:100%;"');
                                                ?>
                                                <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                                    <a href="#" id="removeReadonly">
                                                        <i class="fa fa-unlock" id="unLock"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="col-md-12" id="sticker">
                            <div class="well well-sm">
                                <div class="form-group" style="margin-bottom:0;">
                                    <div class="input-group wide-tip">
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <i class="fa fa-2x fa-barcode addIcon"></i></a></div>
                                        <?php 
										if($this->input->get('editsaleorder')){
											
											$q = $this->db->get_where('erp_products',array('id'=>$this->input->get('editsaleorder')),1);
											$pcode = $q->row()->code;
											
										}
										echo form_input('add_item', (isset($pcode)?$pcode:''), 'class="form-control input-lg" id="add_item" placeholder="' . lang("add_product_to_order") . '"'); ?>
                                        <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <a href="#" id="addManually">
                                                <i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i>
                                            </a>
											<a href="<?= site_url('sale_order/edit_sale_order/'.$id);?>" class="gos" ></a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group table-group">
                                <label class="table-label"><?= lang("order_items"); ?> *</label>

                                <div class="controls table-controls table-responsive">
                                    <table id="slTable"
                                           class="table items table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                        <tr>
											<th><?= lang("no"); ?></th>
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
                                            <?php
                                                if ($Settings->product_serial) {
                                                    echo '<th class="col-md-2">' . lang("serial_no") . '</th>';
                                                }
                                            ?>
                                            <?php if ($Owner || $Admin || $GP['sale_order-price']) { ?>
                                                <th class="col-md-1"><?= lang("unit_price"); ?></th>
                                            <?php } ?>
                                            <th class="col-md-1"><?= lang("quantity"); ?></th>
											<th class="col-md-1"><?= lang("QOH"); ?></th>
                                            <?php
                                                if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount') || $inv->product_discount)) {
                                                    echo '<th class="col-md-1">' . lang("discount") . '</th>';
                                                }
                                            ?>
                                            <?php
                                                if ($Settings->tax1) {
                                                    echo '<th class="col-md-1">' . lang("product_tax") . '</th>';
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
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("order_discount_percent", "sldiscount"); ?>
                                <?php echo form_input('order_discount', '', 'class="form-control input-tip" id="sldiscount" '.(($Owner || $Admin || $this->session->userdata('allow_discount')) ? '' : 'readonly="true"')); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("shipping", "slshipping"); ?>
                                <?php echo form_input('shipping', '', 'class="form-control input-tip" id="slshipping"'); ?>

                            </div>
                        </div>
						<?php if ($Settings->tax2) { ?>
                            <div class="col-md-4">
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
						
						<!--
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("sale_status", "slsale_status"); ?>
                                <?php $sst = array('pending' => lang('pending'), 'completed' => lang('completed'));
                                echo form_dropdown('sale_status', $sst, '', 'class="form-control input-tip" required="required" id="slsale_status"');
                                ?>

                            </div>
                        </div>
						-->
						<div class="col-sm-4" style="display:none;">
							<div class="form-group">
								<?= lang("delivery_by", "delivery_by"); ?>
								<?php
									$driver[''] = '';
									foreach($drivers as $dr) {
										$driver[$dr->id] = $dr->name;
									}
									echo form_dropdown('delivery_by', $driver, '', 'class="form-control input-tip" required="required" id="delivery_by"');
								?>
							</div>
						</div>
						<!--
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("payment_term", "slpayment_term"); ?>
                                <?php echo form_input('payment_term', '', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('payment_term_tip') . '" id="slpayment_term"'); ?>

                            </div>
                        </div>-->
					<!--
						<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("payment_term", "slpayment_term"); ?>
								<?php
                                    $ptr[""] = "";
                                    foreach ($payment_term as $term) {
                                        $ptr[$term->id] = $term->description;
                                    }
									echo form_dropdown('payment_term', $ptr,$inv->payment_term?$inv->payment_term:"", 'id="slpayment_term" data-placeholder="' . lang("payment_term_tip") .  '" class="form-control input-tip select" style="width:100%;"');
									//echo form_input('payment_term',$ptr,'11', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('payment_term_tip') . '" id="slpayment_term"'); ?>
                            </div>
                        </div>
						<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("payment_status", "slpayment_status"); ?>
                                <?php $pst = array('due' => lang('due'), 'partial' => lang('partial'), 'paid' => lang('paid'));
                                echo form_dropdown('payment_status', $pst, '', 'class="form-control input-tip" required="required" id="slpayment_status"');
                                ?>
                            </div>
                        </div>
					-->
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-4">
									<div class="form-group">
										<?= lang("document", "document") ?>
										<input id="document" type="file" name="document" data-show-upload="false" data-show-preview="false" class="form-control file">
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<?= lang("document", "document") ?>
										<input id="document1" type="file" name="document1" data-show-upload="false" data-show-preview="false" class="form-control file">
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<?= lang("document", "document") ?>
										<input id="document2" type="file" name="document2" data-show-upload="false" data-show-preview="false" class="form-control file">
									</div>
								</div>
							</div>
						</div>
                        <div class="clearfix"></div>
						<!--
						<div id="payments" style="display: none;">
                            <div class="col-md-12">
                                <div class="well well-sm well_1">
                                    <div class="col-md-12">
                                        <div class="row">
                                            
                                            <div class="col-sm-4">
                                                <div class="payment">
                                                    <div class="form-group ngc">
                                                        <?= lang("amount", "amount_1"); ?>
                                                        <input name="amount-paid" type="text" id="amount_1"
                                                               class="pa form-control kb-pad amount" amount = "<?= $inv->paid ?>" value="<?php echo isset($inv->paid)?$inv->paid:'' ?>"/>
                                                    </div>
													
                                                </div>
                                            </div>
											
											<div class="col-sm-4">
                                                <div class="form-group">
                                                    <?= lang("paying_by", "paid_by_1"); ?>
                                                    <select name="paid_by" id="paid_by_1" class="form-control paid_by">
														<option value="deposit"><?= lang("deposit"); ?></option>
                                                    </select>
                                                </div>
                                            </div>
											
                                        </div>
                                        <div class="clearfix"></div>
										
										<div class="form-group dp" style="display: block;">
											<!--
											<?= lang("customer", "customer1"); ?>
													<?php
													$customers1[] = array();
													foreach($customers as $customer){
														$customers1[$customer->id] = $customer->name;
													}
												echo form_dropdown('customer', $customers1, '' , 'class="form-control" id="customer1"');
											?>
											-->
						<!--
											<?= lang("deposit_amount", "deposit_amount"); ?>
											
											<div id="dp_details"></div>
										</div>
										
                                        <div class="form-group">
                                            <?= lang('payment_note', 'payment_note_1'); ?>
                                            <textarea name="payment_note" id="payment_note_1"
                                                      class="pa form-control kb-text payment_note"></textarea>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
						-->

                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>
						<input type="hidden" id="exchange_rate" value="<?= $exchange_rate->rate ?>">
						<input type="hidden" id="is_edit" value="1">

                        <div class="row" id="bt">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("sale_note", "slnote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("staff_note", "slinnote"); ?>
                                        <?php echo form_textarea('staff_note', (isset($_POST['staff_note']) ? $_POST['staff_note'] : ""), 'class="form-control" id="slinnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>


                            </div>

                        </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php // echo form_submit('edit_sale', lang("submit"), 'id="edit_sale" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
							   <button type="submit"  id="edit_sale" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;" ><?= lang('submit') ?></button>
							   <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">
					<input type="hidden" id="edit_id" value="<?=$id?>">
					<input type="hidden" id="warehouse_id" value="<?=$inv->warehouse_id?>">
                    <table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
                        <tr class="warning">
                            <td><?= lang('items') ?> <span class="totals_val pull-right" id="titems">0</span></td>
                            <td><?= lang('total') ?> <span class="totals_val pull-right" id="total">0.00</span></td>
                            <?php if (($Owner || $Admin || $this->session->userdata('allow_discount')) || $inv->total_discount) { ?>
                            <td><?= lang('order_discount') ?> <span class="totals_val pull-right" id="tds">0.00</span></td>
                            <?php } ?>
                            <?php if ($Settings->tax2) { ?>
                                <td><?= lang('order_tax') ?> <span class="totals_val pull-right" id="ttax2">0.00</span></td>
                            <?php } ?>
                            <td><?= lang('shipping') ?> <span class="totals_val pull-right" id="tship">0.00</span></td>
                            <td><?= lang('grand_total') ?> <span class="totals_val pull-right" id="gtotal">0.00</span></td>
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
                            class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="prModalLabel"></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <?php if ($Settings->tax1) { ?>
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
                    <?php if ($Settings->product_serial) { ?>
                        <div class="form-group">
                            <label for="pserial" class="col-sm-4 control-label"><?= lang('serial_no') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pserial">
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
					<!--
                    <div class="form-group">
                        <label for="pg" class="col-sm-4 control-label"><?= lang('price_groups') ?></label>

                        <div class="col-sm-8">
                            <div id="pg-div"></div>
                        </div>
                    </div>
					-->
					
					

					<div class="form-group">
                        <label for="progroupprice" class="col-sm-4 control-label"><?= lang('product_option') ?></label>

                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>
					
					<div class="form-group">
                        <label for="pgroup_prices" class="col-sm-4 control-label"><?= lang('group_price') ?></label>

                        <div class="col-sm-8">
                            <div id="pgroup_prices-div"></div>
                        </div>
                    </div>
					
                    <?php if ($Settings->product_discount) { ?>
                        <div class="form-group">
                            <label for="pdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pdiscount" <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? '' : 'readonly="true"'; ?>>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pprice" class="col-sm-4 control-label"><?= lang('unit_price') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pprice">
                        </div>
                    </div>
					<div class="form-group">
                        <label for="pnote" class="col-sm-4 control-label"><?= lang('product_note') ?></label>

                        <div class="col-sm-8">
                            <!--<input type="text" class="form-control kb-pad" id="pnote">-->
                            <textarea id="pnote"></textarea>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="net_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="pro_tax"></span></th>
                        </tr>
                    </table>
                    <input type="hidden" id="punit_price" value=""/>
                    <input type="hidden" id="old_tax" value=""/>
                    <input type="hidden" id="old_qty" value=""/>
                    <input type="hidden" id="old_price" value=""/>
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

	$(window).load(function(){ 
		$('#paid_by').trigger('change');
		$('#slpayment_status').trigger('change');
		$('#amount_1').trigger('keyup');
		
	});
		
		
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
		

        $("#slcustomer").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer_to_load') ?>").select2({
            placeholder: "<?= lang('select_area_to_load') ?>", data: [
                {id: '', text: '<?= lang('select_area_to_load') ?>'}
            ]
        });

	
        $('#slarea').change(function () {
           var v = $(this).val();
            $('#modal-loading').show();			
            if (v) {				
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('sales/getCustomersByArea') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
							
                            $("#slcustomer").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: scdata
                            });
                        }else{
							
							$("#slcustomer").select2("destroy").empty().attr("placeholder", "<?= lang('select_customer') ?>").select2({
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
                $("#slcustomer").select2("destroy").empty().attr("placeholder", "<?= lang('select_area_to_load') ?>").select2({
                    placeholder: "<?= lang('select_area_to_load') ?>",
                    data: [{id: '', text: '<?= lang('select_area_to_load') ?>'}]
                });
            }
            $('#modal-loading').hide();
        });      
		 
    });
	
	$(window).load(function(){
		var al = '<?php echo $this->input->get('editsaleorder');?>';
		if(al){
			var test = $("#add_item").val();
		
				$.ajax({
					type: 'get',
					url: '<?= site_url('sales/suggestionsSale'); ?>',
					dataType: "json",
					data: {
						term: test,
						warehouse_id: __getItem('slwarehouse'),
						customer_id: __getItem('slcustomer')
					},
					success: function (data) {
						  for(var i = 0; i < data.length; i++){
							comment = data[i];
							add_invoice_item(comment)
						  }
						 $("#add_item").val('');	
						//var url = $(".gos").attr('href');
						//window.location.href = url;
					}
				});   
				
	
		}
    });
</script>
