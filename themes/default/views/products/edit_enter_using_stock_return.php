<?php
$v = "";
/* if($this->input->post('name')){
  $v .= "&product=".$this->input->post('product');
  } */
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

<script>
    $(document).ready(function () {
		
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        <?php if ($this->input->post('customer')) { ?>
        $('#customer').val(<?= $this->input->post('customer') ?>).select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "customers/suggestions/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.results[0]);
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

        $('#customer').val(<?= $this->input->post('customer') ?>);
        <?php } ?>
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
    });
</script>



<?php echo form_open("products/update_enter_using_stock_return_by_id"); ?>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-heart"></i><?= lang('enter_using_stock_return'); ?> <?php
            if ($this->input->post('start_date')) {
                echo "From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				<input type="hidden"  name="stock_id"  id="stock_id" value="<?=$stock->id?>" />
                <div class="clearfix"></div>
					
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<?= lang('date', 'date'); ?>
							<?= form_input('date', $stock->date , 'class="form-control tip date" required id="date"'); ?>
						</div>
						<div class="form-group">
							<?= lang('reference_no', 'reference_no'); ?>
							 
							<?= form_input('reference_no',$stock->reference_no, 'class="form-control tip"  required  id="reference_no"'); ?>
							<input type="hidden"  name="ref_prefix"  id="ref_prefix" value="es" />
							<input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $stock->reference_no ?>" />
							
							<!--<div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
									<input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
							</div>-->
							
						</div>
						<div class="form-group">
							<?= lang('using_reference_no', 'using_reference_no'); ?>
							 
							<?= form_input('using_reference_no',$stock->using_reference_no, 'class="form-control tip" readonly  required  id="using_reference_no"'); ?>
						</div>
						<div class="form-group all">
                            <?= lang("from_location", "from_location") ?>
                            <?php
								$wh[""]="";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->name;
                                }
                          
                            echo form_dropdown('from_location', $wh,$stock->warehouse_id, 'class="form-control select"   required  id="from_location" placeholder="' . lang("select") . ' ' . lang("location") . '" style="width:100%"')
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
                          
                            echo form_dropdown('authorize_id', $users,$stock->authorize_id, 'class="form-control select"  required  id="authorize_id" placeholder="' . lang("select") . ' ' . lang("authorize_id") . '" style="width:100%"')
                            ?>
						</div>
						<div class="form-group">
							<?= lang('employee', 'employee'); ?>
							<?php
                            
                                foreach ($employees as $epm) {
                                    $em[$epm->id] = $epm->fullname;
                                }
                          
                            echo form_dropdown('employee_id', $em,$stock->employee_id, 'class="form-control select"    id="employee_id" placeholder="' . lang("select") . ' ' . lang("employee") . '" style="width:100%"')
                            ?>
						</div>
						<div class="form-group">
							<?= lang('shop', 'shop'); ?>
							 <?php
							 foreach ($biller as $bl) {
                                    $billers[$bl->id] = $bl->company;
                                }
                            echo form_dropdown('shop', $billers,$stock->shop, 'class="form-control select"   required  id="shop" placeholder="' . lang("select") . ' ' . lang("shop") . '" style="width:100%"')
                            ?>
						</div>
					</div>
					<div class="col-md-4">			
						<div class="form-group">
							<?= lang('account', 'account'); ?>
							<?php
                            
                                foreach ($getGLChart as $GLChart) {
                                    $gl[$GLChart->accountcode] = $GLChart->accountcode.' - '.$GLChart->accountname;
                                }
                          
                            echo form_dropdown('account', $gl,$stock->account, 'class="form-control select"    id="account" placeholder="' . lang("select") . ' ' . lang("account") . '" style="width:100%"')
                            ?>
						</div>
						<div class="form-group all">
                        <?= lang("note", "note") ?>
                        <?= form_textarea('note',$stock->note, 'class="form-control" id="note"'); ?>
                    </div>
					</div>
				</div>	
			
				<div class="table-responsive">
                    <table id="PrRData"
                           class="table table-bordered table-hover table-striped table-condensed reports-table">
                        <thead>
                        <tr>
                            <th style="width:3% !important;">
                                                
							<?= lang("item_code"); ?>
							</th>
							<!--
							<th style="width:25% !important;"><?= lang("enterprise"); ?></th>
							-->
                            <th style="width:7% !important;"><?= lang("description"); ?></th>
                            <th style="width:7% !important;"><?= lang("reason"); ?></th>
                            <th style="width:10% !important;"><?= lang("QOH"); ?></th>
                            <th style="width:10% !important;"><?= lang("qty_use"); ?></th>
                            <th style="width:10% !important;"><?= lang("qty_return"); ?></th>
							<th style=""><?= lang("units"); ?></th>
                            <th style="width:10% !important;"><?= lang("remove"); ?></th>
                        </tr>
                        </thead>
                        <tbody class="tbody">
						<?php
						foreach($stock_item as $row){
							//print_r($row);
							//echo '|||<br/>';
							
							$product_qqh=NULL;
							foreach($qqh as $qqh_row){
								if($qqh_row->warehouse_id==$row->wh_id && $qqh_row->product_code == $row->product_code){
									$product_qqh=$qqh_row->qqh;
								}
							}
							
						?>
							<tr>
								<td style="width:25%;">
								<input type="text"  name="item_code_and_name[]" required id="add_item" class=" form-control add_item" value="<?=$row->name.' ( '.$row->product_code.' ) '?>">
								<input type="hidden"  name="stock_item_id[]" id="stock_item_id" class=" form-control stock_item_id" value="<?=$row->e_id?>">
								<input type="hidden"  name="item_code[]" id="item_code" class=" form-control item_code" value="<?=$row->product_code?>">
								<input type="hidden"  name="cost[]" id="cost" class=" form-control cost" value="<?=$row->cost?>">
								<input type="hidden"  name="total_cost[]" id="total_cost" class=" form-control total_cost" value="">
								<input type="hidden"  name="wh_id[]" id="wh_id" class=" form-control wh_id" value="<?=$row->wh_id?>">
								</td>
								<td><input type="text"  name="description[]" id="description" class="checknb form-control description" value="<?=$row->description?>"></td>
								<td><input type="text"  name="reason[]" id="s4" class="checknb form-control reason" value="<?=$row->reason?>"></td>
								<td class="qqh"><?=$product_qqh?></td>
								<td class="qty_use_by_using_stock"><?=$row->qty_use_from_using_stock?></td>
								<td><input type="text"  name="qty_return[]" required id="qty_return" class="checknb form-control qty_return" value="<?=$row->qty_by_unit?>"></td>
								<input type="hidden"  name="last_qty_return[]" id="last_qty_return" class=" form-control" value="<?=$row->qty_use?>">
								<td>
								<div class="form-group" style="margin-bottom: 0px;">
									<?php
                                    $unit="";
                                    foreach ($unit_of_measure_by_code as $au) {
										if($row->product_code==$au["product_code"] ){
											 $unit[$au["description"] ]= $au["description"];
										}
                                       
                                    }
                                    echo form_dropdown('unit[]', $unit,$row->unit, 'id="unit" data-placeholder="' . lang("select") . ' ' . lang("unit") . '"  class="form-control input-tip select" style="width:100%;"');
                                    ?>
								</div>
								</td>
								<td style="text-align:center;"><span class="btn_delete sotre_delete_id" id="<?=$row->e_id?>" style="cursor:pointer;"><i class="fa fa-trash-o" style="font-size: 15px; color:#2A79B9;"></i></span></td>
							</tr>
						<?php
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
                        </tr>
                        </tfoot>
                    </table>
                </div>
				<center>
				<div class="form-group">
						<input type="hidden"  name="total_item_cost"  id="total_item_cost" class=" form-control total_item_cost" value="">
						<input type="hidden"  name="sotre_delete_id"  id="sotre_delete_id" class=" form-control" value="">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("update"), 'class="btn btn-primary"'); ?> </div>
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
		$("#date").datetimepicker({
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
		$('.datetime').datetimepicker({format: 'yyyy-mm-dd'});
		
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
								'<input type="text"  name="item_code_and_name[]" required id="add_item" class=" form-control add_item" value="">'+
								'<input type="hidden"  name="stock_item_id[]" id="stock_item_id" class=" form-control stock_item_id" value="NULL">'+
								'<input type="hidden"  name="item_code[]" id="item_code" class=" form-control item_code" value="">'+
								'<input type="hidden"  name="cost[]" id="cost" class=" form-control cost" value="">'+
								'<input type="hidden"  name="total_cost[]" id="total_cost" class=" form-control total_cost" value="">'+
								'<input type="hidden"  name="wh_id[]" id="wh_id" class=" form-control wh_id" value="">'+
								'</td>'+
								'<td><input type="text"  name="description[]" id="description" class="checknb form-control description" value=""></td>'+
								'<td><input type="text"  name="reason[]" id="s4" class="checknb form-control reason" value=""></td>'+
								'<td class="qqh"></td>'+
								'<td><input type="text"  name="qty_use[]" required id="qty_use" class="checknb form-control qty_use" value=""></td>'+
								'<td>'
									'<div class="form-group">';
								row+='<select class="form-control input-tip select unit" name="unit[]" style="width:100%;"></select>'; 
								row+='</div>'+
								'</td>'+
								'<td style="text-align:center;"><span class="btn_delete" style="cursor:pointer;"><i class="fa fa-trash-o" style="font-size: 15px; color:#2A79B9;"></i></span></td>'+
							'</tr>';			
			$('.tbody').append(row);i_for_add_row++;
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
		if($("#from_location").val()!=""){
		var parent=$(this).parent().parent();
		var wh_id=parent.find("#wh_id").val();
		if(wh_id!=""){
			$("#from_location").select2("val", wh_id);
			$("#from_location").trigger("change");
		}	
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
		}).trigger('focus');
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
					parent.find(".qqh").text(ui.item.quantity);
					if(ui.item.qqh<=0){
						parent.find('.qty_use').attr("disabled", 'disabled');
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
											unit_option+='<option value="'+item.description+'">'+item.description+'</option>'; 
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
		
		
		
		$("#reference_no").attr('readonly','yeadonly');
		$('#ref_st').on('ifChanged', function() {
		  if ($(this).is(':checked')) {
			$("#reference_no").prop('readonly', false);
			$("#reference_no").val("");
		  }else{
			$("#reference_no").prop('readonly', true);
			var temp = $("#temp_reference_no").val();
			$("#reference_no").val(temp);
			
		  }
		});
		
		
		var delete_id="";
	$(".sotre_delete_id").click(function(){
		delete_id+=($(this).attr("id"))+"-";
		$('#sotre_delete_id').val(delete_id);
	});
		
		/*$( "#reference_no" ).live('focusout',function(){
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
			
		});*/
		
		
		
	});
</script>

	
	
	
	<?=$modal_js ?>
	
	

