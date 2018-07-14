<?php
	$v = "";
	if ($this->input->post('reference_no')) {
		$v .= "&reference_no=" . $this->input->post('reference_no');
	}
	if ($this->input->post('customer')) {
		$v .= "&customer=" . $this->input->post('customer');
	}
	if ($this->input->post('biller')) {
		$v .= "&biller=" . $this->input->post('biller');
	}
	if ($this->input->post('warehouse')) {
		$v .= "&warehouse=" . $this->input->post('warehouse');
	}
	if ($this->input->post('user')) {
		$v .= "&user=" . $this->input->post('user');
	}
	if ($this->input->post('serial')) {
		$v .= "&serial=" . $this->input->post('serial');
	}
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	}

?>

<?php echo form_open("products/add_enter_using_stock"); ?>

<div class="box">
    <div class="box-header">
        <h2 class="blue">
			<i class="fa-fw fa fa-heart"></i><?= lang('add_stock_using'); ?> 
			<?php
				if ($this->input->post('start_date')) {
					echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
				}
            ?>
		</h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				
				<div class="clearfix"></div>
					
				<div class="row">
					<div class="col-md-4">
						<?php if ($Owner || $Admin || $Settings->allow_change_date == 1) { ?>
							<div class="form-group">
								<?= lang('date', 'date'); ?>
								<?= form_input('date', '', 'class="form-control tip date" required id="date"'); ?>
							</div>
						<?php } ?>

                        <div class="form-group">
                            <?= lang("reference_no", "slref"); ?>
                            <div class="form-group">
                                <div class="input-group">
                                    <?php echo form_input('reference_no', $reference? $reference :"",'class="form-control input-tip" id="slref"'); ?>
                                    <input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference?$reference:"" ?>" />
                                    <div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
                                        <input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="form-group all">
                            <?= lang("from_location", "from_location") ?>
                            <?php
								$wh[""]="";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->code .'-'. $warehouse->name;
                                }
                          
								echo form_dropdown('from_location', $wh, isset($purchase->warehouse_id), 'class="form-control"   required  id="from_location" placeholder="' . lang("select") . ' ' . lang("location") . '" style="width:100%"')
                            ?>
                        </div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?= lang('authorize_by', 'authorize_by'); ?>
							<?php
                            
                                foreach ($AllUsers as $AU) {
                                    $users[$AU->id] = $AU->username;
                                }
                          
                            echo form_dropdown('authorize_id', $users,'', 'class="form-control"  required  id="authorize_id" placeholder="' . lang("select") . ' ' . lang("authorize_id") . '" style="width:100%"')
                            ?>
						</div>
						<div class="form-group">
							<?= lang('employee', 'employee'); ?>
							<?php
                            
                                foreach ($employees as $epm) {
                                    $em[$epm->id] = $epm->fullname;
                                }
                          
                            echo form_dropdown('employee_id', $em,'', 'class="form-control"    id="employee_id" placeholder="' . lang("select") . ' ' . lang("employee") . '" style="width:100%"')
                            ?>
							
						</div>
						<div class="form-group">
                            <?= get_dropdown_project('biller', 'shop'); ?>
						</div>
					</div>
					<div class="col-md-4">			
						<div class="form-group">
							<?= lang('account', 'account'); ?>
							<?php
								$gl[""] = "";
                                foreach ($getGLChart as $GLChart) {
                                    $gl[$GLChart->accountcode] = $GLChart->accountcode.' - '.$GLChart->accountname;
                                }
                          
                            echo form_dropdown('account', $gl, '', 'class="form-control"  required  id="account" placeholder="' . lang("select") . ' ' . lang("account") . '" style="width:100%"')
                            ?>
						</div>
						<div class="form-group all">
                        <?= lang("note", "note") ?>
                        <?= form_textarea('note','', 'class="form-control" id="note"'); ?>
                    </div>
					</div>
					
				</div>	
			
				<div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped table-condensed reports-table">
                        <thead>
							<tr>
								<th style="width:10% !important;">
									<i class="fa fa-plus-circle add_row" style="cursor:pointer;"></i>
								<span><?= lang("item_code"); ?></span>
								</th>
								<th style="width:15% !important;"><?= lang("category_expense"); ?></th>
								<th style="width:17% !important;"><?= lang("description"); ?></th>
								<th style="width:12% !important;"><?= lang("reason"); ?></th>
								<th style="width:8% !important;"><?= lang("QOH"); ?></th>
								<th style="width:8% !important;"><?= lang("qty_use"); ?></th>
								<th style="width:10% !important;"><?= lang("units"); ?></th>
								<th style="width:5% !important;"><?= lang("remove"); ?></th>
							</tr>
                        </thead>
                        <tbody class="tbody">
							<?php
							
								if(isset($items)){
									foreach($items as $item){ ?>
										<tr>
											<td style="width:25%;">
												<input type="text"  name="item_code_and_name[]" required id="add_item" class=" form-control add_item" value="<?= $item->code; ?>" />
												<input type="hidden"  name="item_code[]" id="item_code" class=" form-control item_code" value="<?= $item->code ?>">
												<input type="hidden"  name="cost[]" id="cost" class=" form-control cost" value="<?= $item->cost ?>">
												<input type="hidden"  name="total_cost[]" id="total_cost" class=" form-control total_cost" value="<?= $item->total_cost ?>">
												<input type="hidden"  name="wh_id[]" id="wh_id" class=" form-control wh_id" value="">
											</td>';
											<td><input type="text"  name="exp_catid[]" id="exp_catid" class="checknb form-control categories_exp" value=""></td>
											<td><input type="text"  name="description[]" id="description" class="checknb form-control description" value=""></td>
											<td><input type="text"  name="reason[]" id="s4" class="checknb form-control reason" value=""></td>
											<td class="qqh"><?= $this->erp->formatQuantity($item->quantity) ?></td>
											<td><input type="text"  name="qty_use[]" required id="qty_use" class="checknb form-control qty_use" value=""></td>
											<td><div class="form-group">
												<select class="form-control input-tip unit" name="unit[]" style="width:100%;">
													<option value="<?= $item->unit ?>"><?= $item->uname ?></option>
												</select>
											</div></td>
											<td style="text-align:center;"><span class="btn_delete" style="cursor:pointer;"><i class="fa fa-trash-o" style="font-size: 15px; color:#2A79B9;"></i></span></td>
										</tr>
                            <?php
									}
								}
							?>
						</tbody>
                        <tfoot class="dtFilter">
							<tr class="active">
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
                        </tfoot>
                    </table>
                </div>
				<center>
					<div class="form-group">
						<input type="hidden"  name="total_item_cost" required id="total_item_cost" class=" form-control total_item_cost" value="">
						<div class="controls"> <?php echo form_submit('submit_report', $this->lang->line("save"), 'class="btn btn-primary"'); ?> </div>
					</div>
				</center>
				
				<?php echo form_close(); ?>
				
            </div>
        </div>
    </div>
	<?php
		$units[""] = "";
		foreach ($all_unit as $getunits) {
			$units[$getunits->id] = $getunits->name;
		}
		$dropdown= form_dropdown("purchase_type", $units, '', 'id="purchase_type"  class="form-control input-tip select" style="width:100%;"');
	?>
</div>

<?php
    $unit_option='';
	foreach($all_unit as $getunits){
		$unit_option.= '<option value='.$getunits->id.'>'.$getunits->name.'</option>';
	}
?>

<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
		setTimeout(function () {
		   jQuery('.add_row').trigger('click');
		}, 1000);

		<?php if ($Owner || $Admin || $Settings->allow_change_date == 1) { ?>
			$("#date").datetimepicker({
					format: site.dateFormats.js_sdate, 
					fontAwesome: true, 
					todayBtn: 1, 
					autoclose: 1, 
					minView: 2 
				}).datetimepicker('update', new Date());
		<?php } else { ?>
			$('.datetime').datetimepicker({format: 'yyyy-mm-dd'});
		<?php } ?>
		
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getSalesReport/pdf/?v=1'.$v)?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getSalesReport/0/xls/?v=1'.$v)?>";
            return false;
        });
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL()
                    window.open(img);
                }
            });
            return false;
        });
	
		 $('.jn_date').live('change ',function(){
				if(confirm("Are you sure you want to update this?")){
					var journal_date = $(this).val();
					var parent = $(this).parent().parent();
					var month = parent.children("td:nth-child(3)").find(".month").val();
					var year = parent.children("td:nth-child(2)").find(".year").val();
					
					$.ajax({
						type: "get",
						async: false,
						url: "<?= site_url('taxes_reports/update_journal_date') ?>",
                        data: {journal_date:journal_date,month:month,year:year,'type':'SALE','tax_type':'2'},
						success:function(re){
						
						}
					});
				}else{
					return false;
				}
			});	
			
			$('.location').live('change ',function(){
				if(confirm("Are you sure you want to update this?")){
					var loc = $(this).val();
					var parent = $(this).parent().parent();
					var month = parent.children("td:nth-child(3)").find(".month").val();
					var year = parent.children("td:nth-child(2)").find(".year").val();
					var location = loc.replace("%20","");
					
					$.ajax({
						type: "get",
						async: false,
						url: "<?= site_url('taxes_reports/update_journal_loc') ?>",
                        data: {location:location,month:month,year:year,'type':'SALE','tax_type':'2'},
						success:function(re){
						 
						}
					});
				}else{
					return false;
				}
			});	
			
			
			var i_for_add_row=2;
		$('.add_row').click(function() {
			var unit_option="<?= $unit_option ?>";
			var my_i = ($(".tbody tr").size())-0+1;
			var row = '<tr>'+
				'<td style="width:25%;">'+
				'<input type="text" name="item_code_and_name[]" required id="add_item" class=" form-control add_item" value="">'+
				'<input type="hidden" name="item_code[]" id="item_code" class=" form-control item_code" value="">'+
				'<input type="hidden" name="cost[]" id="cost" class=" form-control cost" value="">'+
				'<input type="hidden" name="total_cost[]" id="total_cost" class=" form-control total_cost" value="">'+
				'<input type="hidden" name="wh_id[]" id="wh_id" class=" form-control wh_id" value="">'+
				'</td>'+
				'<td><select name="exp_catid[]" id="exp_catid" style="padding-top: 2px !important;" class="form-control">'+
					'<option value="" disabled selected>Select Categories Expense</option>'+
					<?php if ($getExpense) { foreach ($getExpense as $exps) : ?>
						'<option id="testing" value="<?= $exps->id ?>"><?= $exps->name ?></option>'+
					<?php endforeach; } ?> '</select></td>'
				+'<td><select id="position" name="description[]" style="padding-top: 2px !important;" class="form-control">'+
					'<option value="" disabled selected>Select Position</option>'+
					<?php if ($positions) {  foreach ($positions as $position) : ?>
						'<option id="testing" value="<?= $position->id ?>"><?= $position->name ?></option>'+
					<?php endforeach; } ?>
				'</select></td>'+

				'<td><input type="text" name="reason[]" id="reason" class="form-control" value=""></td>'+
				'<td style="text-align : center !important;" class="qqh"></td>'+
				'<td><input type="text" name="qty_use[]" required id="qty_use" style="text-align : center !important;" class="checknb form-control qty_use" value=""></td>'+
				'<td>'+
					'<div class="form-group">'+
				'<select class="form-control input-tip select unit" name="unit[]" style="width:100%; padding-top: 2px !important;"></select>'+
				'</div>'+
				'</td>'+
				'<td style="text-align:center;"><span class="btn_delete" style="cursor:pointer;"><i class="fa fa-trash-o" style="font-size: 15px; color:#2A79B9;"></i></span></td>'+
			'</tr>';			
			$('.tbody').append(row);
			i_for_add_row++;
		});
			
		$( "#position" ).live( "change", function() {
            var v = $(this).val();
			var row = $(this).parent().parent();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('products/getReasons') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
                            row.find("#reason").select2("destroy").empty().attr("placeholder", "<?= lang('select_reason') ?>").select2({
                                placeholder: "<?= lang('select_position_to_load') ?>",
								data: scdata
                            });
                        }else{
							row.find("#reason").select2("destroy").empty().attr("placeholder", "<?= lang('select_reason') ?>").select2({
                                placeholder: "<?= lang('select_position_to_load') ?>",
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
                $("#reason").select2("destroy").empty().attr("placeholder", "<?= lang('select_position_to_load') ?>").select2({
                    placeholder: "<?= lang('select_position_to_load') ?>",
                    data: [{id: '', text: '<?= lang('select_position_to_load') ?>'}]
                });
            }
            $('#modal-loading').hide();
		});
		
		
	var data;
		
	$('.btn_delete').live('click',function(){
		var parent=$(this).parent().parent();
		parent.remove();
	});
	
	$('.add_item').live('focusout',function(){
		var this_value= $(this).val();
			if(this_value==""){
				var parent=$(this).parent().parent();
				parent.find("#item_code").val('');
				parent.find(".qqh").text('');
			}
	});
	
	$('.add_item').live('focusout',function(){
		$('#add_item').removeClass('ui-autocomplete-loading');
	});	
	$('.add_item').live('focus',function(){
		var parent = $(this).parent().parent();
		var wh_id  = parent.find("#wh_id").val();
		if(wh_id !=""){
			$("#from_location").select2("val", wh_id);
			$("#from_location").trigger("change");
		}
		$('#add_item').removeClass('ui-autocomplete-loading');
			var wharehouse_id=$('#from_location').val();
			if(wharehouse_id){
				product_autocomplete($(this));
			}else{
				bootbox.alert('<?=lang('please_select_warehouse_first!');?>');
				var parent=$(this).parent().parent();
				parent.find("#item_code").val('');
				parent.find(".qqh").text('');
			}
			var this_value= $(this).val();
			if(this_value==""){
				var parent=$(this).parent().parent();
				parent.find("#item_code").val('');
				parent.find(".qqh").text('');
			}
	});
	function product_autocomplete(this_box){
		this_box.autocomplete({
			source: data,
			focus: function(event, ui) {
				// prevent autocomplete from updating the textbox
				event.preventDefault();
				// manually update the textbox
				$(this).val(ui.item.label);
			},
			select: function(event, ui) {
				// prevent autocomplete from updating the textbox
				event.preventDefault();
				// manually update the textbox and hidden field
				$(this).val(ui.item.label);
				var parent=$(this).parent().parent();
				parent.find("#item_code").val(ui.item.value);
				var product_code=ui.item.value;
				var qqh = formatQuantity2(ui.item.quantity);
				parent.find(".qqh").text(qqh);
				if(ui.item.qqh<=0){
					parent.find('.qty_use').attr("disabled", 'disabled');
				}else{
					parent.find('.qty_use').removeAttr( "disabled" )
				}
				parent.find(".cost").val(ui.item.cost);
				parent.find("#wh_id").val($('#from_location').val());
				$.ajax({
					type: 'get',
					url: '<?= site_url('products/getUnitOfMeasureByProductCode'); ?>',
					dataType: "json",
					data: {
						product_code: product_code
					},
					success: function (data) {
						var unit_option='';
						$.each(data, function(i, item) {
							unit_option+='<option id="zz" value="'+item.description+'">'+item.description+'</option>'; 
						});
						parent.find(".unit").html(unit_option);
					}
				});
				
				$('#add_item').removeClass('ui-autocomplete-loading');
			}
		});			
	}
	
	$('#qty_use').keyup(function (){
		var qty=($(this).val());
		var cost=($('.cost').val());
		var total_cost=qty*cost;
		
		var parent=$(this).parent().parent();
		parent.find(".total_cost").val(total_cost);
		
	});
			
	$('#from_location').change(function(){
		var w_id = $(this).val();
			$.ajax({
				type: 'get',
				url: '<?= site_url('products/getProductByWarehouses'); ?>',
				dataType: "json",
				data: {
					w_id: w_id,
				},
				success: function (getdata) {
					data=getdata;
				}
			});
	}).trigger('change');

    $("#slref").attr('readonly', true);
    $('#ref_st').on('ifChanged', function() {
        if ($(this).is(':checked')) {
            $("#slref").prop('readonly', false);
            $("#slref").val("");
        }else{
            $("#slref").prop('readonly', true);
            var temp = $("#temp_reference_no").val();
            $("#slref").val(temp);

        }
    });
			
	$( "#reference_no" ).live('focusout',function(){
		var ref_no = $("#reference_no").val();
		
		var ref_r=ref_no.replace("/", "-");
		var ref_r=ref_r.replace("/", "-");
	
		if(ref_r){
			$.ajax({
				type: "get",
				url: site.base_url + "products/verifyReference/"+ref_r,
				dataType: "json",
				
				success: function (data) {
					if(data){
						bootbox.alert('<?=lang('reference_no_already_use!');?>');
						$( "#reference_no" ).val('');
					}
				}
			});
		}
		
	});

        $(document).on('change', '#shop', function (e) {
            billerChange();
        });
        var $shop = $("#shop");
        $(window).load(function(){
            billerChange();
        });

        function billerChange(){
            var id = $shop.val();
            $.ajax({
                url: '<?= base_url() ?>products/getReferenceByProject/es/'+id,
                dataType: 'json',
                success: function(data){
                    $("#slref").val(data);
                    $("#temp_reference_no").val(data);
                }
            });

        }
		
});
</script>
	
	


	
	

