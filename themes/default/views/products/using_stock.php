<script type="text/javascript">
	var count = 1;
    $(document).ready(function () {
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
		
		$("#add_item").autocomplete({
            source: function (request, response) {
				$.ajax({
					type: 'get',
					url: '<?= site_url('products/suggestionsStock'); ?>',
					dataType: "json",
					data: {
						term: request.term,
						warehouse_id: $("#from_location").val(),
						plan: $("#plan").val(),
						address: $("#address").val()
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
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_using_stock_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
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
		
		$("#date").datetimepicker({
				format: site.dateFormats.js_sdate, 
				fontAwesome: true, 
				todayBtn: 1, 
				autoclose: 1, 
				minView: 2 
			}).datetimepicker('update', new Date());
		
		$('.datetime').datetimepicker({format: 'yyyy-mm-dd H:i:s'});
		
		var remove = '<?=$this->session->userdata('remove_usitem');?>';
		
		if (remove == '1') {
			if (__getItem('usitems')) {
				__removeItem('usitems');
			}
			if (__getItem('from_location')) {
				__removeItem('from_location');
			}
			if (__getItem('authorize_id')) {
				__removeItem('authorize_id');
			}
			if (__getItem('employee_id')) {
				__removeItem('employee_id');
			}
			if (__getItem('shop')) {
				__removeItem('shop');
			}
			if (__getItem('account')) {
				__removeItem('account');
			}
			if (__getItem('slref')) {
				__removeItem('slref');
			}
			if (__getItem('plan')) {
				__removeItem('plan');
			}
			if (__getItem('address')) {
				__removeItem('address');
			}
			if (__getItem('cusotmer')) {
				__removeItem('cusotmer');
			}
			<?php $this->session->set_userdata('remove_usitem', '0');?>
		}
		
		if (__getItem('remove_slls')) {
            if (__getItem('slref')) {
                __removeItem('slref');
            }
            __removeItem('remove_slls');
        }
		
		$("#address").select2("destroy").empty().attr("placeholder", "<?= lang('select_plan_to_load') ?>").select2({
            placeholder: "<?= lang('select_plan_to_load') ?>", data: [
                {id: '', text: '<?= lang('select_plan_to_load') ?>'}
            ]
        });
    });
</script>

<?php
	$attrib = array('data-toggle' => 'validator', 'role' => 'form');
	echo form_open("products/add_using_stock", $attrib);
?>

<div class="box">
    <div class="box-header">
        <h2 class="blue">
            <i class="fa-fw fa fa-heart"></i><?= lang('add_product_using'); ?>
		</h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				
				<div class="clearfix"></div>
					
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<?= lang('date', 'date'); ?>
							<?= form_input('date', '', 'class="form-control tip datetime" required id="date"'); ?>
						</div>
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
						
						<div class="form-group">
							<?= lang('customer', 'customer'); ?>
							<?php
								echo form_input('customer', '', 'id="customer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" class="form-control input-tip" style="min-width:100%;"');
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
							<div class="form-group">

                                <?php
                                $default_biller = JSON_decode($this->session->userdata('biller_id'));
                                if ($Owner || $Admin || !$this->session->userdata('biller_id')) {
                                    echo get_dropdown_project('shop', 'shop');
                                } else {
                                    echo get_dropdown_project('shop', 'shop', $default_biller);
                                }
                                ?>
							</div>
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
                            <?= lang("from_location", "from_location") ?>
                            <?php
                            if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) {
                                $wh[""] = "";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->code . '-' . $warehouse->name;
                                }

                                echo form_dropdown('from_location', $wh, (isset($_POST['from_location']) ? $_POST['from_location'] : ($Settings->default_warehouse)), 'class="form-control"   required  id="from_location" placeholder="' . lang("select") . ' ' . lang("location") . '" style="width:100%"');
                            } else {

                                $whu[''] = '';
                                foreach ($warehouses_by_user as $warehouse_by_user) {
                                    $whu[$warehouse_by_user->id] = $warehouse_by_user->code .'-'.$warehouse_by_user->name;
                                }
                                $default_wh = explode(',', $this->session->userdata('warehouse_id'));
                                echo form_dropdown('from_location', $whu, (isset($_POST['from_location']) ? $_POST['from_location'] : $default_wh[0]), 'id="from_location" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" style="width:100%;" ');
                            }
                         ?>
                        </div>
					</div>
                </div>
				
				<div class="row">
					<div class="col-md-12 pr_form" id="sticker">
						<div class="well well-sm">
							<div class="form-group" style="margin-bottom:0;">
								<div class="input-group wide-tip">
									<div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
									<i class="fa fa-2x fa-barcode addIcon"></i></div>
									<?php echo form_input('add_item', '', 'class="form-control input-lg" id="add_item" placeholder="' . $this->lang->line("add_product_to_order") . '"'); ?>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12 pr_form">
						<div class="table-responsive">
							<table id="UsData" class="table table-bordered table-hover table-striped table-condensed reports-table">
								<thead>
									<tr>
										<th style="width:30% !important;">
											<span><?= lang("item_code"); ?></span>
										</th>
										<?php
                                            if ($Settings->product_expiry) {
                                                echo '<th style="width:14% !important;">' . $this->lang->line("expiry_date") . '</th>';
                                            }
                                        ?>
										<th style="width:18% !important;"><?= lang("description"); ?></th>
										<th style="width:8% !important;"><?= lang("QOH"); ?></th>
										<th style="width:8% !important;"><?= lang("qty_use"); ?></th>
										<th style="width:10% !important;"><?= lang("units"); ?></th>
										<th style="width:2% !important;"><i class="fa fa-trash-o" aria-hidden="true"></i></th>
									</tr>
								</thead>
								<tbody class="tbody"></tbody>
							</table>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<div class="form-group all">
							<?= lang("note", "note") ?>
							<?= form_textarea('note','', 'class="form-control" id="note"'); ?>
						</div>
					</div>
				</div>
				
				<!-- Button Submit -->
				<div class="row">
					<div class="col-md-12">
						<div class="fprom-group">
							<input type="hidden"  name="total_item_cost" required id="total_item_cost" class=" form-control total_item_cost" value="">
							
							<?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary" style="display:none;" id="btn_submit"'); ?>
							
							<button type="button" name="submit_report" class="btn btn-primary" id="btn_using"><?= lang('submit') ?></button>
							
							<button type="button" name="convert_items" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>
						</div>
					</div>
				</div>
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
<script>
    $(document).ready(function () {
        $('#shop').change(function(){
            billerChange();
            $("#from_location").select2().empty();
        });

        var $shop = $("#shop");
        $(window).load(function(){
            billerChange();
        });

        function billerChange(){
            var id = $shop.val();

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
                        $("#from_location").empty();
                        $.each(result, function(i,val){
                            var b_id = val.id;
                            var code = val.code;
                            var name = val.name;
                            var opt = '<option value="' + b_id + '">' +code+'-'+ name + '</option>';
                            $("#from_location").append(opt);
                        });
                    }

                    $('#from_location').val($('#from_location option:first-child').val()).trigger('change');
                    $("#from_location").select2("val", default_warehouse);

                    if (default_warehouse) {
                        $('#from_location').select2('val', default_warehouse);
                    }
                    $('#from_location option[selected="selected"]').each(
                        function() {
                            $(this).removeAttr('selected');
                        }
                    );
                    if(slwarehouse = __getItem('from_location')){
                        $('#from_location').select2("val", slwarehouse);
                    }

                }
            });

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
