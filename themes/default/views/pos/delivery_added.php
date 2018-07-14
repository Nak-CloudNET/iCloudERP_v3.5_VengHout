<div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
                <i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel">New Delivery</h4>
        </div>
        <div class="modal-body">
			<div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_delivery'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-so-form');
                echo form_open_multipart("sales/add_new_delivery/", $attrib)
                ?>
				
				<input type ="hidden" value="<?= $deliveries->id ?>" name="sale_id">
				<input type ="hidden" value="<?= $deliveries->biller_id ?>" name="biller_id" />
				<input type ="hidden" value="<?= $deliveries->customer ?>" name="customer">
				<input type ="hidden" value="<?= $deliveries->delivery_by ?>" name="delivery_by">
				<input type ="hidden" value="<?= $deliveries->saleman_by ?>" name = "saleman_by">
				<input type ="hidden" value="<?= $status?>" name = "status">
				
                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "sldate"); ?>
                                    <?php echo form_input('date',$date, 'class="form-control input-tip datetime" id="sldate"'); ?>
									<input type="hidden" name="pos" id = "pos" value="<?=$POS?>">
								</div>
                            </div>
                        <?php } ?>
						
                        <div class="col-md-4">
                            <div class="form-group">
								<?= lang("delivery_by", "delivery_by"); ?>
                                <?php
									$driver[''] = '';
									foreach($drivers as $dr) {
										$driver[$dr->id] = $dr->name;
									}
									echo form_dropdown('delivery_by', $driver, '', 'class="form-control input-tip" id="delivery_person" required="required"');
								?>
                            </div>
                        </div>
						
						<div class="col-md-4">
							<?= lang("do_no", "slref"); ?>
							<div style="float:left;width:100%;">
								<div class="form-group">
									<div class="input-group">  
											<?php echo form_input('delivery_reference', $reference ? $reference: "", 'class="form-control input-tip slref"'); ?>
											<input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference?$reference:"" ?>" />
										<div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
											<input type="checkbox" name="ref_status" class="ref_st" value="1" style="margin-top:3px;">
										</div>
									</div>
								</div>
							</div>
                        </div>
						
                        <div class="col-md-4">
							<?php
								if($status=="invoice"){
									$ref_no = "sale_ref";
								}else{
									$ref_no = "so_no";
								}
							?>
							<?= lang($ref_no, "dref"); ?>
							<div class="form-group">
								<?php echo form_input('sale_reference',$deliveries->reference_no,'class="form-control input-tip" id="sref" style="pointer-events:none;"'); ?>
							</div>
								
                        </div>
						
						<div class="col-md-4">
							<?= lang("customer", "cust"); ?>
								<div class="form-group">
									<?php echo form_input('customer',($deliveries->name ? ($deliveries->name .' '. ($deliveries->company ? '('. $deliveries->company .')':'')):$deliveries->company),'class="form-control input-tip" id="dcustomer" style="pointer-events:none;"'); ?>		
									<input type="hidden" name="customer_id" value="<?= $deliveries->customer_id ?>" />
								</div>
                        </div>
						
						<div class="col-md-4">
							<?= lang("saleman_by", "saleman_by"); ?>
								<div class="form-group">
									<?php echo form_input('saleman_by',$user->username,'class="form-control input-tip" id="saleman_by" style="pointer-events:none;"'); ?>
								</div>
                        </div>
						
						
                        <div class="clearfix"></div>
                        
                        <div class="col-md-12">
                            <div class="control-group table-group">
                                <label class="table-label"><?= lang("order_items"); ?> *</label>
                                <div class="controls table-controls">
                                    <table id="slTable"
                                           class="table items table-striped table-bordered table-condensed table-hover">
                                        <thead>
											<tr>
												<th class="col-md-1" style="text-align:center;"><?= lang("no"); ?></th>
												<?php if($setting->show_code == 0) { ?>
													<th class="col-md-4"><?= lang("product_name"); ?></th>
												<?php }else if($setting->separate_code == 0){ ?>
													<th class="col-md-4"><?= lang("product_name") . " (" . lang("product_code") . ")"; ?></th>
												<?php }else { ?>
													<th class="col-md-4"><?= lang("product_code"); ?></th>
													<th class="col-md-4"><?= lang("product_name"); ?></th>
												<?php } ?>
												<th class="col-md-2"><?= lang("quantity"); ?></th>
												<th class="col-md-2"><?= lang("quantity_received"); ?></th>
												<th class="col-md-2"><?= lang("balance"); ?></th>
												<th class="col-md-1" style="width: 30px !important; text-align: center;"><i class="fa fa-trash-o col-md-1"
														style="opacity:0.5; filter:alpha(opacity=50);"></i>
												</th>
											</tr>
                                        </thead>
                                        <tbody>
											<?php
												$number=0;
												
												foreach($delivery_items as $delivery){
													
													$unit_qty = $this->site->getProductVariantByOptionID($delivery['option_id']);
													$real_qty=0;
													if($unit_qty){
														$qty  = ($delivery['quantity']);
														$bqty = (($delivery['quantity']) - ($delivery['quantity_received']));
														$real_qty =  (($delivery['quantity']) - ($delivery['quantity_received']));
													}else{
														$qty  = $delivery['quantity'];
														$bqty = (($delivery['quantity']) - ($delivery['quantity_received']));
													}
													
													$number++;
													echo '<tr style="height:45px;" class="row_number" id="'. $delivery['id'] .'">
															<td style="text-align:center;">'.$number.'</td>';
															if($setting->show_code == 0){
																echo'<td>'.$delivery['product_name'].($delivery['name']!=""?" (".$delivery['name'].") ":"" ).'</td>';
															}else if($setting->separate_code == 0){
																echo'<td class="pro_name">'.$delivery['product_code'].'  '.$delivery['product_name'].($delivery['name']!=""?" (".$delivery['name'].") ":"" ).'</td>';
															}else{
																echo'<td>'.$delivery['product_code'].'</td>';
																echo'<td class="pro_name">'.$delivery['product_name'].($delivery['name']!=""?" (".$delivery['name'].") ":"" ).'</td>';
															}
													echo   '<input type="hidden" value="'.$delivery['id'].'" name="delivery_id[]" id="delivery_id" style="width: 150px; height: 30px;text-align:center;">
															<input type="hidden" value="'.$delivery['product_id'].'" name="product_id[]" id="product_id" style="width: 150px; height: 30px;text-align:center;">
															<input type="hidden" value="'.$delivery['product_name'].'" name="product_name[]" id="product_name" style="width: 150px; height: 30px;text-align:center;">
															<input type="hidden" value="'.$delivery['product_code'].'" name="product_code[]" id="product_code" style="width: 150px; height: 30px;text-align:center;">
															<input type="hidden" value="'.$delivery['item_discount'].'" name="item_discount[]" id="item_discount" style="width: 150px; height: 30px;text-align:center;">
															<input type="hidden" value="'.$delivery['option_id'].'" name="option_id[]" id="option_id" style="width: 150px; height: 30px;text-align:center;">
															<input type="hidden" value="'.$delivery['product_type'].'" name="product_type[]" id="product_type" style="width: 150px; height: 30px;text-align:center;">
															<input type="hidden" value="'.$delivery['unit_price'].'" name="unit_price[]" id="unit_price" style="width: 150px; height: 30px;text-align:center;">
															<input type="hidden" value="'.$delivery['warehouse_id'].'" name="warehouse_id[]" id="warehouse_id" style="width: 150px; height: 30px;text-align:center;">
															<input type="hidden" value="'.$delivery['product_noted'].'" name="product_noted[]" id="product_noted" style="width: 150px; height: 30px;text-align:center;">
															<input type="hidden" value="'.$delivery['net_unit_price'].'" name="net_unit_price[]" id="net_unit_price" style="width: 150px; height: 30px;text-align:center;">
															<input type="hidden" value="'.$delivery['item_tax'].'" name="item_tax[]" id="item_tax" style="width: 150px; height: 30px;text-align:center;">
															
															<td id="quantity" style="text-align:center;">'.$this->erp->formatDecimal($qty).'</td>
															
															<input type="hidden" value="'.$this->erp->formatDecimal($qty).'" name="quantity[]" id="quantity-x">
															<input type="hidden" value="'.$this->erp->formatDecimal($bqty).'" name="bquantity[]" id="bquantity">
															<input type="hidden" value="'.($unit_qty!=""?$this->erp->formatDecimal($delivery['quantity_received']*$unit_qty->qty_unit):$this->erp->formatDecimal($delivery['quantity_received'])).'" name="rquantity[]" id="rquantity">
															<input type="hidden" value="'.$this->erp->formatDecimal($real_qty).'" name="real_qty" id="real_qty">
															<td>
																<input type="text" class="quantity_received" value ="'.$this->erp->formatDecimal($bqty).'"name="quantity_received[]" id="quantity_received" style="width: 150px; height: 30px;text-align:center;">
																<input type="hidden" class="cur_quantity_received" value ="'.$this->erp->formatDecimal($bqty).'"name="cur_quantity_received[]" id="cur_quantity_received" style="width: 150px; height: 30px;text-align:center;">
															</td>
															
															<td>
																<p class="balance" name="balance[]" id="balance" style="width: 150px; height: 30px; text-align:center;">0</p>
															</td>
															<td style="text-align:center;">
																<i class="fa fa-times remove-row" aria-hidden="true" style="color:red; cursor: pointer;"></i>
															</td>
														  </tr>';
												}
											?>
											
										</tbody>
                                        <tfoot>
											
										</tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
						
						<div class="col-sm-12">
							<div class="col-sm-4">
								<div class="form-group">
									<?= lang("delivery_status", "delivery_status"); ?>
									<?php 
									
									if($status =="invoice"){
										$sst2 = array('pending' => lang('pending'));
									}else{
										$sst2 = array('completed' => lang('completed'));
									}
									echo form_dropdown('delivery_status', $sst2, '', 'class="form-control input-tip" required="required" id="delivery_status"'); ?>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<?= lang("document", "document") ?>
									<input id="document" type="file" name="document" data-show-upload="true" data-show-preview="false" class="form-control file">
								</div>
							</div>
						</div>
						
						<div class="col-sm-12">
							
							
							<div class="clearfix"></div>
							
							<div class="row" id="bt">
								<div class="col-md-12">
									<div class="col-md-6">
										<div class="form-group">
											<?= lang("delivery_note", "delivery_note"); ?>
											<?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>
											
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div
									class="fprom-group"><?php echo form_submit('edit_sale', lang("submit"), 'id="edit_sale" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
									<button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>
								</div>
							</div>
						</div>
                </div>
                
                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>


<div class="modal" id="option" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="prModalLabel"></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    
                    <?php if ($Settings->product_serial) { ?>
                        <div class="form-group">
                            <label for="pserial" class="col-sm-4 control-label"><?= lang('serial_no') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pserial" value="">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pquantity"value="">
							<input type="hidden" class="form-control" id="cquantity" value="">
							<input type="hidden" class="form-control" id="pro_name" value="">
							<input type="hidden" class="form-control" id="item_quantity" value="">
							<input type="hidden" class="form-control" id="real_qty_change" value="">
							<input type="hidden" class="item_id" id="item_id" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="poption" class="col-sm-4 control-label"><?= lang('product_option') ?></label>
                        <div class="col-sm-8">
                            <div id="poptions-div">
								<select id="pro-option" class="col-sm-12"></select>
								<input type="hidden" name ="fixed_option" id="fixed_option">
							</div>
							<input type="hidden" name ="product_id" id ="product_id" value="">
                        </div>
                    </div>
                   
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
		<?php if($deliveries) {?>
			__setItem('delivery_by', '<?= $deliveries->delivery_by ?>');
			__setItem('delivery_items', JSON.stringify(<?= $delivery_items; ?>));
        <?php } ?>
		if (delivery_by = __getItem('delivery_by')) {
            $('#delivery_person').val(delivery_by);
        }
		
		$('input[type="checkbox"],[type="radio"]').not('.skip').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '20%' // optional
		});	
		
		
		$(".slref").attr('disabled','disabled');
		$('.ref_st').on('ifChanged', function() {
		  if ($(this).is(':checked')) {
			$(".slref").prop('disabled', false);
			$(".slref").val("");
		  }else{
			$(".slref").prop('disabled', true);
			var temp = $("#temp_reference_no").val();
			$(".slref").val(temp);
		  }
		});
		
		$( "#slref" ).blur(function(){
			var ref_no = $("#slref").val();
			if(ref_no){
				$.ajax({
                    type: "get",
                    url: site.base_url + "sales/verifyReference/"+ref_no,
                    dataType: "json",
                    success: function (data) {
						if(data){
							alert("Duplicated reference number");
						}
                    }
                });
			}
		});
		
		$( "#other" ).click(function() {
			$( ".target" ).change();
		});
		
		$(".balance").prop('disabled', true);
		
		$( ".quantity_received" ).keyup(function(e) {
			if ((e.which >= 48 && e.which <= 57) || (e.which >=96 && e.which <=105 || e.which ==13 || e.which == 8 || e.which == 46) ){
				var str = $.trim($(this).val());
				if(parseInt(str) || str==0){
					var tr = $(this).parent().parent();
					var qty  = parseInt($(this).closest('tr').children('td:eq(2)').text());
					var bqty  = parseInt($(this).closest('tr').children('#bquantity').val());
					var rqty  = parseInt($(this).closest('tr').children('#rquantity').val());
					var curQty = Number(str);
					if(curQty >= 0 && curQty <= bqty){
						var balance = bqty - curQty; 
						tr.find(".balance").text(balance);
						tr.find('.cur_quantity_received').val(curQty);
					}else if(curQty >= 0 && curQty > bqty){
						tr.find("#quantity_received").val(bqty);
					}
					
				}
			}else{
				var tr = $(this).parent().parent();
				var bqty  = parseInt($(this).closest('tr').children('#bquantity').val());
				tr.find("#quantity_received").val(bqty);
				alert("allow only number");
			}
			
			// calculate balance
			var quantity_balance = $('#bquantity').val();
			var current_quantity = $(this).val();
			var last_balance =  quantity_balance - current_quantity;
			$('#balance').val();
			
		});
		
		$(document).on('click', '.edit', function () {
			
			var row = $(this).closest('tr');
			var row_id = row.attr('id');
			var real_qty =  row.find('#real_qty').val();
			var product_id = row.find('#product_id').val();
			__setItem('product_id', product_id);
			var product_code = row.find('#product_code').val();
			var product_name = row.find('#product_name').val();
			var product_option = row.find('#option_id').val();
			var item_discount = row.find('#item_discount').val();
			var unit_price = row.find('#unit_price').val();
			var product_noted = row.find('#product_noted').val();
			var net_unit_price = row.find('#net_unit_price').val();
			var item_tax = row.find('#item_tax').val();
			var option_id = row.find('#option_id').val();
			var pro_name  = row.find('#product_name').val();
			 $('.item_id').val(row_id);
			 $("#pro_name").val(pro_name);
			 $('#prModalLabel').text(product_code +"-"+ product_name);
			 $('#pdiscount').val(item_discount);
			 $('#pprice').val(unit_price);
			 $('#remember_pprice').val(unit_price);
			 $('#pnote').val(product_noted);
			 $('#net_price').text(net_unit_price);
			 $('#pro_tax').text(item_tax);
			 $('#fixed_option').val(option_id);
			// $('#item_id').val(data-item);
			$('#real_qty_change').val(real_qty);
			$.ajax({
				type: 'get',
				url: site.base_url+'sales/getProductVariant',
				dataType: "json",
				data: { pro_id: product_id },
				success: function (data){
					if(data){
						$("#pro-option").empty();
						$.each(data, function (i,item) {
							var tr = $(this).parent().parent();
							$("#pro-option").append('<option att='+item.qty_unit+' value='+item['id']+'>'+item['name']+'='+item.qty_unit+'</option>');
							if(item['id'] == option_id) {
								$('#pro-option').select2("val",option_id);
								var rqty = row.find('.cur_quantity_received').val();
								var quantity = rqty;//item.qty_unit;
								if(quantity % 1 != 0){
									var qty = (quantity - (quantity));
									//var qty = (quantity - (quantity % 1));
									
									 $('#pquantity').val(qty);
									 $('#cquantity').val(real_qty);
									 $('#item_quantity').val(real_qty);
								}else{
									 $('#pquantity').val(quantity);
									 $('#cquantity').val(real_qty);
									 $('#item_quantity').val(real_qty);
								}
								
							}
						});
						$("#pro-option").trigger("change");
					}else{
						var rqty = row.find('.cur_quantity_received').val();
						var quantity = rqty;//item.qty_unit;
						var qty = (quantity);
						 $('#pquantity').val(qty);
						 $('#cquantity').val(quantity);
						 $('#item_quantity').val(quantity);
						
						 $("#pro-option").select2("val", "");
						 $("#pro-option").empty();
					}
				}
			});
			
			
			$('#option').appendTo("body").modal('show');
		});
		
		$('#editItem').click(function (){
			
		    var row = $('#' + $('#item_id').val());
			var item_id = $('#item_id').val();
			var pro_name = $('#pro_name').val();
			var quantity = $('#pquantity').val();
			var element = $('#pro-option').find('option:selected'); 
			var unit = element.attr("att"); 
			var option_id  = (element.val());
			var total_quantity = (quantity*unit);
			var balance_quantity = (($('#real_qty_change').val()));
			var last_balance = (balance_quantity- total_quantity);
			var unit_name = $("#pro-option option:selected").text();
			var name  = unit_name.split("=");
			//row.find('#quantity').html(formatDecimal(balance_quantity));
			row.find('#quantity-x,#bquantity,#rquantity').val(balance_quantity);
			row.find('#option_id').val(option_id);
			row.find('.pro_name').html(pro_name+"("+name[0]+") <span class='pull-right fa fa-edit tip pointer edit' data-item="+item_id+"  title='' data-original-title=''> </span>");
			row.find('#quantity_received').val(formatDecimal(total_quantity));
			row.find('#cur_quantity_received').val(formatDecimal(total_quantity));
			row.find('#balance').text(formatDecimal(last_balance));
			$('#option').modal('hide');
		});
		
		
		$("#pro-option").change(function(){
			var product_id = __getItem('product_id');
			var option_id = $(this).val();
			var fixed_option = $('#fixed_option').val();
			var qty      = $("#real_qty_change").val()-0;
			var option   = $(this).find('option:selected').val();
			var qty_unit = $(this).find('option:selected').attr("att");
			var cal_qty  = (qty/qty_unit);
			$("#pquantity").val((cal_qty));
		});
		
		
		
		
		$("#pquantity").keyup(function(){
			var item_quantity = Number($('#item_quantity').val());
			if($(this).val()>item_quantity){
				$(this).val(item_quantity);
			}
		});
			
		
		
		$(".remove-row").click(function(){
			$(this).closest('tr').remove();
		});

		$('#myModal').on('shown.bs.modal', function () {
		  $('#myInput').focus();
		});
		
		if (product_variant = __getItem('product_variant')) {
			//var variants = JSON.parse(product_variant);
			//console.log(product_variant);
        }
		
		if (product_option = __getItem('product_option')) {
			$('#option_id').val(product_option);
		}
		
		
    });
		

</script>

		</div>
    </div>
</div>