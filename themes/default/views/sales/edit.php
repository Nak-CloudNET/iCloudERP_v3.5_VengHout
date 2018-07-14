
<style>
.select2-result.select2-result-unselectable.select2-disabled {
	display: none;
}
</style>
<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    //var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
    //var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    $(document).ready(function () {
        <?php if ($inv) { ?>
        __setItem('edit_sale', '<?= $edit_sale; ?>');
        __setItem('sldate', '<?= $this->erp->hrld($inv->date); ?>');
        __setItem('slcustomer', '<?= $inv->customer_id ?>');
        __setItem('slbiller', '<?= $inv->biller_id ?>');
        __setItem('slref', '<?= $inv->reference_no ?>');
        __setItem('slwarehouse', '<?= $inv->warehouse_id ?>');
        __setItem('slsale_status', '<?= $inv->sale_status ?>');
        __setItem('slpayment_status', '<?= $inv->payment_status ?>');
        __setItem('slpayment_term', '<?= $inv->payment_term ?>');
        __setItem('slnote', '<?= str_replace(array("\r", "\n","'"), array("","","&#039"), $this->erp->decode_html($inv->note)); ?>');
        __setItem('slinnote', '<?= str_replace(array("\r", "\n","'"), array("","","&#039"), $this->erp->decode_html($inv->staff_note)); ?>');
        __setItem('sldiscount', '<?= $inv->order_discount_id ?>');
        __setItem('sltax2', '<?= $inv->order_tax_id ?>');
        __setItem('slshipping', '<?= $inv->shipping ?>');
        __setItem('slitems', JSON.stringify(<?= $inv_items; ?>));
		<?php /* if (isset($payment->paid_by)) { */ ?>
		//__setItem('paid_by_1', '<?= $payment->paid_by ?>');
		//__setItem('paid', '<?= $payment->amount ?>');
		//__setItem('deposited', '<?= $payment->amount ?>');
		
		//$('#slpayment_status').val(payment_status);
		//$("#dp_details").html('<?=$this->erp->formatDecimal($sale_order->paid);?>');
		//$('#payments').css('display','block');
		//$(".amount").val('<?=$payment->amount;?>');
		//$("#payment_note_1").val('<?=$payment->note;?>');
		<?php /* } */ ?>
        <?php } ?>
		
		if(__getItem('quote_ID')){
			__removeItem('quote_ID');
		}
		
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
        $(document).on('change', '#slbiller', function (e) {
            __setItem('slbiller', $(this).val());
        });
		
        if (slbiller = __getItem('slbiller')) {
            $('#slbiller').val(slbiller);
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
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('sales/suggestionsSale'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#slwarehouse").val(),
                        customer_id: $("#slcustomer").val(),
						category_id: $("#category").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
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
                        message += '- ' + product_name + ' : Sale = ' + formatDecimal(received_qty) + ', Ordered = ' + formatDecimal(order_qty) + ', QOH = ' + formatDecimal(oh_qty) + ' !\n';
						help = true;
					}else {
                        message += '- ' + product_name + ' : Sale = ' + formatDecimal(received_qty) + ', QOH = ' + formatDecimal(oh_qty) + ' !\n';
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
			
			<?php if($setting->credit_limit == 1) {?>
			var payment_status = $("#slpayment_status").val();
			if(payment_status == 'due' || payment_status == 'partial'){				
				var customer_id = $('#slcustomer').val();
				var c_balance= __getItem('cust_balance');
				var c_limit= __getItem('credit_limit');												
				var cust_balance = $('#total_balance').val()-0;				
				cust_balance+= parseFloat(c_balance);				
				if(c_limit >= 0 && c_limit < cust_balance){					
					if (confirm("This customer has over credit limit ("+(cust_balance - c_limit)+"$)!\n Your Balance is "+cust_balance+"$\n Your Credit Balance is "+parseFloat(c_limit)+"$\n Click (OK) if you want to continue  Or Click (Cancel) if you want to Cancel Adding.") == true) {
						
					} else {
						
						return false;
					}
					
				}
			}
			<?php } ?>
            $(window).unbind('beforeunload');
            $('form.edit-so-form').submit();
        });
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('edit_sale'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-so-form');
                echo form_open_multipart("sales/edit/" . $inv->id, $attrib)
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
                                <?= lang("sale_ref", "slref"); ?>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ''), 'class="form-control input-tip" id="slref" required="required" style="pointer-events:none;"'); ?>
                            </div>
                        </div>
						<?php if($sale_order && $sale_order->reference_no) { ?>
						<div class="col-md-4">
							<div class="form-group">
								<?= lang("so_no", "soref"); ?>
								<?php echo form_input('so_reference_no', (isset($_POST['so_reference_no']) ? $_POST['so_reference_no'] : $sale_order->reference_no), 'class="form-control input-tip" id="soref" readonly '); ?>
								<input type="hidden" name="sale_order_id" value="<?= $sale_order->id ?>" />
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
                                <div
                                    class="panel-heading"><?= lang('please_select_these_before_adding_product') ?></div>
                                <div class="panel-body" style="padding: 5px;">

                                    <?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>
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
                                    <?php } else {
                                        $warehouse_input = array(
                                            'type' => 'hidden',
                                            'name' => 'warehouse',
                                            'id' => 'slwarehouse',
                                            'value' => $this->session->userdata('warehouse_id'),
                                        );

                                        echo form_input($warehouse_input);
                                    } ?>
									<?php if($setting->bill_to == 1) { ?>
									<div class="col-md-4">
										<div class="form-group">
											<?= lang("bill_to", "bill_to"); ?>
											<?php echo form_input('bill_to', (isset($_POST['bill_to']) ? $_POST['bill_to'] : $inv->bill_to), 'class="form-control input-tip" id="bill_to"'); ?>
										</div>
									</div>
									<?php } ?>
									<?php if($setting->show_po) { ?>
									<div class="col-md-4">
										<div class="form-group">
											<?= lang("po", "po"); ?>
											<?php echo form_input('po', (isset($_POST['po']) ? $_POST['po'] : $inv->po), 'class="form-control input-tip" id="po"'); ?>
										</div>
									</div>
									<?php } ?>
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
									<div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("category_name", "category_name"); ?>
                                            <?php
                                            $cate[''] = lang('all');
                                            foreach ($categories as $category) {
                                                $cate[$category->id] = $category->name;
                                            }
                                            echo form_dropdown('category', $cate, (isset($_POST['category']) ? $_POST['category'] : ''), 'id="category" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("category") . '" style="width:100%;" ');
                                            ?>
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
										if($this->input->get('editsales')){
											
											$q = $this->db->get_where('erp_products',array('id'=>$this->input->get('editsales')),1);
											$pcode = $q->row()->code;
											
										}
										echo form_input('add_item', (isset($pcode)?$pcode:''), 'class="form-control input-lg" id="add_item" placeholder="' . lang("add_product_to_order") . '"'); ?>
                                        <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <a href="#" id="addManually2">
                                                <i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i>
                                            </a>
											<a href="<?= site_url('sales/edit/'.$id);?>" class="gos" ></a>
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
                                            <?php if ($Owner || $Admin || $GP['sales-price']) { ?>
                                                <th class="col-md-1"><?= lang("unit_price"); ?></th>
                                            <?php } ?>
                                            <th class="col-md-1"><?= lang("quantity"); ?></th>
                                            <th class="col-md-1"><?= lang("qoh"); ?></th>
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
                                <?= lang("delivery_by", "delivery_by"); ?>
                                <select name="delivery_by" id="delivery_by" class="form-control delivery_by">
                                    <?php 
                                        foreach($agencies as $agency){
                                            if($delivery->delivery_by == $agency->id){
                                                echo '<option value="'. $delivery->delivery_by .'" selected>'. $agency->username .'</option>';
                                            }else{
                                                echo '<option value="'. $agency->id .'">'. $agency->username .'</option>';
                                            }
                                        }
                                    ?>
                                </select>
								<input type="hidden" name="delivery_id" id="delivery_id" class="deliery_id" value="<?= $delivery->id; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("payment_term", "slpayment_term"); ?>
                                <?php echo form_input('payment_term', '', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('payment_term_tip') . '" id="slpayment_term"'); ?>

                            </div>
                        </div>
                        
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("sale_status", "slsale_status"); ?>
                                <?php $sst = array('pending' => lang('pending'), 'completed' => lang('completed'));
                                echo form_dropdown('sale_status', $sst, '', 'class="form-control input-tip" required="required" id="slsale_status"');
                                ?>

                            </div>
                        </div>
						-->
						<input type="hidden" name="sale_status" id="slsale_status" value="<?= $inv->sale_status ?>" required="required" />
                        <input type="hidden" name="pos" id="pos" value="<?= $inv->pos ?>">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("payment_term", "slpayment_term"); ?>
								<?php
                                    $ptr[""] = "";
                                    foreach ($payment_term as $term) {
                                        $ptr[$term->id] = $term->description;
                                    }
									echo form_dropdown('payment_term', $ptr,$inv->payment_term?$inv->payment_term:"", 'id="slpayment_term" data-placeholder="' . lang("payment_term_tip") .  '" class="form-control input-tip select" style="width:100%;"'); ?>
                            </div>
                        </div>

                        <!--
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
						
						<div id="payments" style="display: none;">
                            <div class="col-md-12">
                                <div class="well well-sm well_1">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4" id="pay_ref" style="display:none">
                                                <div class="form-group">
                                                    <?= lang("payment_reference_no", "payment_reference_no"); ?>
                                                    <?= form_input('payment_reference_no', isset($payment->reference_no)?$payment->reference_no:$payment_reference, 'class="form-control tip" id="payment_reference_no"'); ?>
													<?php if($payment) { ?>
													<input type="hidden" name="payment_id" value="<?= $payment->id ?>" />
													<?php } ?>
												</div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="payment">
                                                    <div class="form-group ngc">
                                                        <?= lang("amount", "amount_1"); ?>
                                                        <input name="amount-paid" type="text" id="amount_1"
                                                               class="pa form-control kb-pad amount" value="<?php echo isset($payment->amount)?$payment->amount:'' ?>" readonly />
														<!--<input name="amount-paid" type="text" id="amount_1"
                                                               class="pa form-control kb-pad amount" value="<?php echo isset($payment->amount)?$payment->amount:'' ?>"/>-->
                                                    </div>
                                                </div>
                                            </div>
											<div class="col-sm-4" style="display:none">
                                                <div class="form-group">
                                                    <?= lang("paying_by", "paid_by_1"); ?>
                                                    <select name="paid_by" id="paid_by_1" class="form-control paid_by">
														<option value="cash"><?= lang("cash"); ?></option>
														<option value="western union"><?= lang("Western_Union"); ?></option>
														<option value="bank transfer"><?= lang("Bank_Transfer"); ?></option>
														<option value="cheque"><?= lang("cheque"); ?></option>
														<option value="other"><?= lang("other"); ?></option>
														<option value="deposit"><?= lang("deposit"); ?></option>
														<option value="depreciation"><?= lang("loan"); ?></option>
														 <option value="gift_card"><?= lang("gift_card"); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
										<div class="row" style="display:none">
											<div class="col-sm-4" id="bank_acc">
												<div class="form-group">
													<?= lang("bank_account", "bank_account_1"); ?>
													<?php $bank = array('' => '');
													foreach($bankAccounts as $bankAcc) {
														$bank[$bankAcc->accountcode] = $bankAcc->accountcode . ' | '. $bankAcc->accountname;
													}
													echo form_dropdown('bank_account', $bank, (($payment && $payment->bank_account)? $payment->bank_account:''), 'id="bank_account_1" class="ba form-control kb-pad bank_account" required="required"');
													?>
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
                                <button type="submit" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;" id="edit_sale"><?= lang('submit') ?></button>
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
					<div class="form-group" id="dvpiece" >
                        <label for="piece" id="lbpiece" class="col-sm-4 control-label"><?= lang('piece') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="piece">
                        </div>
                    </div>
					<div class="form-group" id="dvwpiece" >
                        <label for="wpiece" class="col-sm-4 control-label"><?= lang('wpiece') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="wpiece">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pquantity">
							<input type="hidden" class="form-control" id="request_quantity">
							<input type="hidden" class="form-control" id="cur_stock_qty">
                        </div>
                    </div>
					<div class="form-group">
                        <label for="expdates" class="col-sm-4 control-label"><?= lang('expdates') ?></label>

                        <div class="col-sm-8">
                            <div id="expdates-div"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="poption" class="col-sm-4 control-label"><?= lang('product_option') ?></label>

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
							<input type="text" class="form-control" id="pprice_show">
                            <input type="hidden" class="form-control" id="pprice">
							<input type="hidden" class="form-control" id="curr_rate">
                        </div>
                    </div>
					
					<div class="form-group">
                        <label for="pnote" class="col-sm-4 control-label"><?= lang('product_note') ?></label>

                        <div class="col-sm-8">
                         <!--   <input type="text" class="form-control kb-pad" id="pnote">-->
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

<div class="modal" id="mModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mcode">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mname" class="col-sm-4 control-label"><?= lang('product_name') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mname">
                        </div>
                    </div>
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label for="mtax" class="col-sm-4 control-label"><?= lang('product_tax') ?> *</label>

                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('mtax', $tr, "", 'id="mtax" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= lang('quantity') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mquantity">
                        </div>
                    </div>
                    <?php if ($Settings->product_serial) { ?>
                        <div class="form-group">
                            <label for="mserial" class="col-sm-4 control-label"><?= lang('product_serial') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mserial">
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($Settings->product_discount) { ?>
                        <div class="form-group">
                            <label for="mdiscount" class="col-sm-4 control-label">
                                <?= lang('product_discount') ?>
                            </label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mdiscount" <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? '' : 'readonly="true"'; ?>>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mprice" class="col-sm-4 control-label"><?= lang('unit_price') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mprice">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="mnet_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="mpro_tax"></span></th>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addItemManually"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	
	var $biller = $("#slbiller");
	$(window).load(function(){ 
	$('#paid_by').trigger('change');
	$('#slpayment_status').trigger('change');
	$('#amount_1').trigger('keyup');
	<?php if($Admin || $Owner){ ?>
		billerChange();
	<?php } ?>
	});
	
	function billerChange(){
        var id = $biller.val();
        //$("#slwarehouse").empty();
        $.ajax({
            url: '<?= base_url() ?>auth/getWarehouseByProject/'+id,
            dataType: 'json',
            success: function(result){
                $.each(result, function(i,val){
                    var b_id = val.id;
					var code = val.code;
                    var name = val.name;
                    var opt = '<option value="' + b_id + '">' +code+'-'+ name + '</option>';
                    $("#slwarehouse").append(opt);
					
                });
				
                $('#slwarehouse option[selected="selected"]').each(
                    function() {
                        //$(this).removeAttr('selected');
                    }
                );
				//$('#slwarehouse').val($('#slwarehouse option:first-child').val()).trigger('change');
                //$("#slwarehouse").select2("val", "<?=$Settings->default_warehouse;?>");
				
				if(slwarehouse = __getItem('slwarehouse')){
					$('#slwarehouse').select2("val", slwarehouse);
				}else{
					$("#slwarehouse").select2("val", "<?=$Settings->default_warehouse;?>");
				}
            }
        });
    }

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
		var al = '<?php echo $this->input->get('editsales');?>';
		if(al){
			var test = $("#add_item").val();
				$.ajax({
					type: 'get',
					url: '<?= site_url('sales/suggestionsSale'); ?>',
					dataType: "json",
					data: {
						term: test,
						warehouse_id: $("#slwarehouse").val(),
						customer_id: $("#slcustomer").val()
					},
					success: function (data) {
						  for(var i = 0; i < data.length; i++){
							comment = data[i];
							add_invoice_item(comment)
						  }
						 $("#add_item").val('');	
						
					}
				});   
				
			//var url = $(".gos").attr('href');
				//		window.location.href = url;
		}
    });
	
	
</script>