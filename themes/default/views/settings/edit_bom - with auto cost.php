<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    //var audio_success = new Audio('<?=$assets?>sounds/sound2.mp3');
    //var audio_error = new Audio('<?=$assets?>sounds/sound3.mp3');
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
            __removeItem('remove_slls');
        }
        
        <?php if ($Owner || $Admin) { ?>
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
        <?php } ?>

        ItemnTotals();
        $('.bootbox').on('hidden.bs.modal', function (e) {
            $('#bom_from_items').focus();
            $('#convert_to_item').focus();
        });
		
        $("#bom_from_items").autocomplete({
            source: function (request, response) {
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('system_settings/suggestions'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#slwarehouse").val()
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
                        $('#bom_from_items').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
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
                        $('#bom_from_items').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                	var rows        = "";
                    var data        = ui.item.uom;
					
					var opt = $("<select id=\"poption\" name=\"bom_from_items_uom\[\]\" class=\"form-control select rvariant\" />");
					if(ui.item.options !== false) {
						$.each(ui.item.options, function () {
							$("<option />", {value: this.id, text: this.name}).appendTo(opt);
						});
					} else {
						$("<option />", {value: 0, text: 'n/a'}).appendTo(opt);
						opt = opt.hide();
					}
					rows = "<tr>"
	        				+ "<td>	<input type='hidden' value='"+ui.item.id+"' name='bom_from_items_id[]' />"
	        				+ " <input type='hidden' value='"+ui.item.code+"' name='bom_from_items_code[]' />"
	        				+ " <input type='hidden' value='"+ui.item.name+"' name='bom_from_items_name[]' />"
	        				+ ui.item.label+"</td>"
                            + "<td>" + (opt.get(0).outerHTML) + "</td>"
	        				+ "<td><input type='text' required='required' class='quantity form-control input-tip' value='' name='bom_from_items_qty[]' /></td>"
							+ "<td class='text-center'><span class='unit_cost_raw'>"+ formatMoney(ui.item.cost) +"</span></td>"
							+ "<td class='text-center'><span class='total_cost_raw' ></span></td>"
	        				+ '<td><i style="cursor:pointer;" title="Remove" id="1449892339552" class="fa fa-times tip pointer sldel"></i></td>'
						+ "</tr>";
						
                	$('#tbody-convert-from-items').append(rows);
					
                	$(this).val('');
					$('.quantity').change(function(){
						var tr 	 = $(this).parent().parent();
						var qty  = $(this).val();
						var cost = tr.find('.unit_cost_raw').html();
						var total = formatMoney(cost * qty);
						tr.find('.total_cost_raw').html(total);
					});
                } else {
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });
        $("#convert_to_item").autocomplete({
            source: function (request, response) {
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('system_settings/suggestions'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#slwarehouse").val()
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
                        $('#convert_to_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
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
                        $('#convert_to_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                	var rows       = "";
                    var data       = ui.item.uom;
					var cost 	   = (ui.item.cost > 0 ? ui.item.cost : ui.item.price);
                    var option     = "";
                    var opt = $("<select id=\"poption\" name=\"convert_to_items_uom\[\]\" class=\"form-control select rvariant\" />");
					if(ui.item.options !== false) {
						$.each(ui.item.options, function () {
							$("<option />", {value: this.id, text: this.name}).appendTo(opt);
						});
					} else {
						$("<option />", {value: 0, text: 'n/a'}).appendTo(opt);
						opt = opt.hide();
					}
                	rows = "<tr>"
	        				+ "<td>	<input type='hidden' value='"+ui.item.id+"' name='convert_to_items_id[]' />"
	        				+ " <input type='hidden' value='"+ui.item.code+"' name='convert_to_items_code[]' />"
	        				+ " <input type='hidden' value='"+ui.item.name+"' name='convert_to_items_name[]' />"
	        				+ ui.item.label+"</td>"
                            + "<td>" + (opt.get(0).outerHTML) + "</td>"
	        				+ "<td><input type='text' required='required' class='quantity form-control qty_count input-tip' value='' name='convert_to_items_qty[]' /></td>"
							+ "<td class='text-center'><input type='hidden' value='"+cost+"' class='real_cost'/><span class='unit_cost'></span></td>"
							+ "<td class='text-center'><span class='total_cost' ></span></td>"
	        				+ '<td><i style="cursor:pointer;" title="Remove" id="1449892339552" class="fa fa-times tip pointer sldel"></i></td>'
						+ "</tr>";
                	$('#tbody-convert-to-items').append(rows);
                	$(this).val('');
					
					$('.quantity').change(function(){
						var shipping = 0;
						var avg_cost = 0;
						var tr 	 	= $(this).parent().parent();
						var qty  	= $(this).val();
						var f_cost 	= tr.find('.real_cost').val();
						var total_f	= f_cost * qty;
						
						//============== Get Total Raw Cost ================//
						var total_raw_cost = 0;
						$('.total_cost_raw').each(function(){
							total_raw_cost += parseFloat($(this).html());
						});
						//====================== End =======================//
						
						//============== Get Total Raw Cost ================//
						var real_cost = 0;
						$('.real_cost').each(function(){
							real_cost += parseFloat($(this).val());
						});
						//====================== End =======================//
						
						//================== Get All Qty ===================//
						var count = $('.qty_count').length;
						//====================== End =======================//
						
						shipping = (total_f / real_cost);
						avg_cost = (total_raw_cost * (shipping > 0? shipping:1) )/(shipping > 0? qty:count);
						
						total_cost = avg_cost * qty;
						console.log(f_cost +'=='+ total_f +'=='+ real_cost +'=='+ shipping +'=='+ total_raw_cost);
						tr.find('.unit_cost').html(formatMoney(avg_cost));
						tr.find('.total_cost').html(formatMoney(total_cost));
					});
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });
        
		$('.quantity').change(function(){
			var shipping = 0;
			var avg_cost = 0;
			var tr 	 	= $(this).parent().parent();
			var qty  	= $(this).val();
			var f_cost 	= tr.find('.real_cost').val();
			var total_f	= f_cost * qty;
			
			//============== Get Total Raw Cost ================//
			var total_raw_cost = 0;
			$('.total_cost_raw').each(function(){
				total_raw_cost += parseFloat($(this).html());
			});
			//====================== End =======================//
			
			//============== Get Total Raw Cost ================//
			var real_cost = 0;
			$('.real_cost').each(function(){
				real_cost += parseFloat($(this).val());
			});
			//====================== End =======================//
			
			//================== Get All Qty ===================//
			var count = $('.qty_count').length;
			//====================== End =======================//
			
			shipping = (total_f / real_cost);
			avg_cost = (total_raw_cost * (shipping > 0? shipping:1) )/(shipping > 0? qty:count);
			
			total_cost = avg_cost * qty;
			console.log(f_cost +'=='+ total_f +'=='+ real_cost +'=='+ shipping +'=='+ total_raw_cost);
			tr.find('.unit_cost').html(formatMoney(avg_cost));
			tr.find('.total_cost').html(formatMoney(total_cost));
			$('.quantity').each(function(){
				$(this).trigger('change');
			});
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
        $('#bom_from_items').bind('keypress', function (e) {
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
    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Edit_Bom'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("system_settings/edit_bom/".$id, $attrib);
				foreach($all_bom as $bom){
					$date = date("m/d/Y H:i", strtotime($bom->date));
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "sldate"); ?>
                                    <?php echo form_input('date', (isset($bom->date) ? $date : ""), 'class="form-control input-tip datetime" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("Name", "slrefs"); ?>
                                <?php echo form_input('name', (isset($bom->name) ? $bom->name : ""), 'class="form-control input-tip" id="slrefs"'); ?>
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                <?= lang("Note", "Note"); ?>
								 <?php echo form_textarea('note', (isset($bom->noted) ? $bom->noted : ""), 'class="form-control" id="note"'); ?>
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
									<?php echo form_input('bom_from_items', '', 'class="form-control input-lg" id="bom_from_items" placeholder="' . lang("add_product_to_order") . '"'); ?>                                        
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<!-- table show convert from items -->
					<div class="col-md-12">
						<div class="control-group table-group">
							<label class="table-label"><?= lang("bom_items_from"); ?> *</label>

							<div class="controls table-controls">
								<table id="slTable_" class="table items table-striped table-bordered table-condensed table-hover">
									<thead>
										<tr>
											<th class="col-md-5"><?= lang("product_name") . " (" . lang("product_code") . ")"; ?></th>
											<th class="col-md-1"><?= lang("unit"); ?></th>
											<th class="col-md-2"><?= lang("quantity"); ?></th>
											<th class="col-md-2"><?= lang("unit_cost"); ?></th>
											<th class="col-md-2"><?= lang("total"); ?></th>
											<th class="col-md-1" style="width:30px !important;text-align: center;">
												<i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
											</th>
										</tr>
									</thead>
									<tbody id="tbody-convert-from-items">
										<?php
											if(isset($top_bom)){
												foreach($top_bom as $top){
										?>
											<tr>
												<td>	
													<input type='hidden' value='<?= $top->product_id;?>' name='bom_from_items_id[]' />
													<input type='hidden' value='<?= $top->product_code;?>' name='bom_from_items_code[]' />
													<input type='hidden' value='<?= $top->product_name;?>' name='bom_from_items_name[]' />
													<?= $top->product_name .' ('.$top->product_code .')';?>
												</td>
												<td>
													<?php
														$variant = $this->settings_model->getVariants($top->product_id);
														if($variant){
															echo "<select name='bom_from_items_uom[]' class='form-control'>";
															foreach($variant as $var){
																if($var->id == $top->option_id){
																	echo '<option value="'.$var->id.'" selected>'.$var->name.'</option>';
																}else{
																	echo '<option value="'.$var->id.'">'.$var->name.'</option>';
																}
																
															}
															echo "</select>";
														}else{
															echo "<select name='bom_from_items_uom[]' class='form-control' style='display:none;'><option value='0'>n/b</option></select>";
														}
													?>
												</td>
												<td><input type='text' required='required' class='quantity form-control input-tip' value='<?= $this->erp->formatQuantity($top->quantity);?>' name='bom_from_items_qty[]' /></td>
												
												<td class='text-center'><span class='unit_cost_raw'><?= $this->erp->formatMoney($top->cost);?></span></td>
												
												<td class='text-center'>
													<span class='total_cost_raw' ><?= $this->erp->formatMoney($top->cost * $top->quantity);?></span>
												</td>
												
												<td><i style="cursor:pointer;" title="Remove" id="1449892339552" class="fa fa-times tip pointer sldel"></i></td>
											</tr>
										<?php
												}
											}
										?>
									</tbody>
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
									<?php echo form_input('bom_to_item', '', 'class="form-control input-lg" id="convert_to_item" placeholder="' . lang("add_product_to_order") . '"'); ?>                                     
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<!-- table convert to items -->
					<div class="col-md-12">
						<div class="control-group table-group">
							<label class="table-label"><?= lang("bom_items_to"); ?> *</label>

							<div class="controls table-controls">
								<table id="slTable_ " class="table items table-striped table-bordered table-condensed table-hover">
									<thead>
										<tr>
											<th class="col-md-5"><?= lang("product_name") . " (" . lang("product_code") . ")"; ?></th>
											<th class="col-md-1"><?= lang("unit"); ?></th>
											<th class="col-md-2"><?= lang("quantity"); ?></th>
											<th class="col-md-2"><?= lang("unit_cost"); ?></th>
											<th class="col-md-2"><?= lang("total"); ?></th>
											<th class="col-md-1" style="width:30px !important;text-align: center;">
												<i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
											</th>
										</tr>
									</thead>
									<tbody id="tbody-convert-to-items">
										<?php
											if(isset($bottom_bom)){
												foreach($bottom_bom as $bottom){
										?>
											<tr>
												<td>	
													<input type='hidden' value='<?= $bottom->product_id;?>' name='convert_to_items_id[]' />
													<input type='hidden' value='<?= $bottom->product_code;?>' name='convert_to_items_code[]' />
													<input type='hidden' value='<?= $bottom->product_name;?>' name='convert_to_items_name[]' />
													<?= $bottom->product_name .' ('.$bottom->product_code .')';?>
												</td>
												<td>
													<?php
														$variant = $this->settings_model->getVariants($bottom->product_id);
														if($variant){
															echo "<select name='convert_to_items_uom[]' class='form-control'>";
															foreach($variant as $var){
																if($var->id == $bottom->option_id){
																	echo '<option value="'.$var->id.'" selected>'.$var->name.'</option>';
																}else{
																	echo '<option value="'.$var->id.'">'.$var->name.'</option>';
																}
																
															}
															echo "</select>";
														}else{
															echo "<select name='convert_to_items_uom[]' class='form-control' style='display:none;'><option value='0'>n/b</option></select>";
														}
													?>
												</td>
												<td><input type='text' required='required' class='quantity form-control input-tip' value='<?= $this->erp->formatQuantity($bottom->quantity);?>' name='convert_to_items_qty[]' /></td>
												
												<td class='text-center'><input type='hidden' value='<?= $this->erp->formatMoney($bottom->cost);?>' class='real_cost'/><span class='unit_cost'><?= $this->erp->formatMoney($bottom->cost);?></span></td>
												
												<td class='text-center'><span class='total_cost' ><?= $this->erp->formatMoney($bottom->quantity * $bottom->cost);?></span></td>
												
												<td><i style="cursor:pointer;" title="Remove" id="1449892339552" class="fa fa-times tip pointer sldel"></i></td>
											</tr>
										<?php
												}
											}else{}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- Button Submit -->
					<div class="col-md-12">
                            <div class="fprom-group"><?php echo form_submit('add_sale', lang("submit"), 'id="bth_convert_items" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" name="convert_items" class="btn btn-danger" id="reset"><?= lang('reset') ?></div>
                        </div>
                    </div>
                </div>
                <?php }echo form_close(); ?>

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
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pquantity">
                        </div>
                    </div>
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
                        <label for="pprice" class="col-sm-4 control-label"><?= lang('unit_price') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pprice">
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
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
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
                    <?php if ($Settings->product_discount) { ?>
                        <div class="form-group">
                            <label for="mdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mdiscount">
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
                        <?php echo form_input('gccard_no', '', 'class="form-control" id="gccard_no"'); ?>
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
                    <?= lang("price", "gcprice"); ?> *
                    <?php echo form_input('gcprice', '', 'class="form-control" id="gcprice"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("customer", "gccustomer"); ?>
                    <?php echo form_input('gccustomer', '', 'class="form-control" id="gccustomer"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("expiry_date", "gcexpiry"); ?>
                    <?php echo form_input('gcexpiry', '', 'class="form-control date" id="gcexpiry"'); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="addGiftCard" class="btn btn-primary"><?= lang('sell_gift_card') ?></button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=base_url('themes/default/assets/js/sales.js')?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
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
