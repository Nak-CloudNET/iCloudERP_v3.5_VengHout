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
		
		$("#add_item").autocomplete({
            source: function (request, response) {
				$.ajax({
					type: 'get',
					url: '<?= site_url('products/adjust_suggestions'); ?>',
					dataType: "json",
					data: {
						term: request.term,
						warehouse_id: $("#warehouse").val()
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
                    var row = add_adjust_item(ui.item);
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
		
    });
</script>

<?php echo form_open("products/adjust_cost"); ?>

<div class="box">
    <div class="box-header">
        <h2 class="blue">
			<i class="fa-fw fa fa-plus-circle"></i><?= lang('adjust_cost'); ?> 
		</h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				
				<div class="clearfix"></div>
					
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<?= lang("reference_no", "slref"); ?>
							<div class="input-group">  
									<?php echo form_input('reference_no', $reference ? $reference :"",'class="form-control input-tip" id="slref"'); ?>
									<input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference ? $reference :"" ?>" />
								<div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
									<input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?= lang('start_date', 'start_date'); ?>
							<?= form_input('start_date', '', 'class="form-control tip date" required id="start_date"'); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?= lang('end_date', 'end_date'); ?>
							<?= form_input('end_date', '', 'class="form-control tip date" required id="end_date"'); ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?= lang('project', 'project'); ?>
							<?php
								$billers[""] = "";
								foreach ($biller as $bl) {
										$billers[$bl->id] = $bl->code .'-'.$bl->company;
									}
								echo form_dropdown('project', $billers,$setting->site_name, 'class="form-control"   required  id="project" placeholder="' . lang("select") . ' ' . lang("project") . '" style="width:100%"')
                            ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?= lang("warehouse", "warehouse") ?>
                            <?php
								$wh[""]="";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->code .'-'. $warehouse->name;
                                }
                          
								echo form_dropdown('warehouse', $wh, '', 'class="form-control"   required  id="warehouse" placeholder="' . lang("select") . ' ' . lang("warehouse") . '" style="width:100%"')
                            ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<?= lang('adjust_by', 'adjust_by'); ?>
							<?php
								$users[""] = "";
                                foreach ($allusers as $AU) {
                                    $users[$AU->id] = $AU->username;
                                }
                          
								echo form_dropdown('adjust', $users,'', 'class="form-control" required id="adjust" placeholder="' . lang("select") . ' ' . lang("adjuster") . '" style="width:100%"')
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
											<span><?= lang("item_description"); ?></span>
										</th>
										<th style="width:15% !important;"><?= lang("current_cost"); ?></th>
										<th style="width:15% !important;"><?= lang("new_cost"); ?></th>
										<th style="width:25% !important;"><?= lang("reason"); ?></th>
										<th style="width:5% !important;"><i class="fa fa-trash-o" aria-hidden="true"></i></th>
									</tr>
								</thead>
								<tbody class="tbody"></tbody>
							</table>
						</div>
					</div>
				</div>
				
				<!-- Button Submit -->
				<div class="row">
					<div class="col-md-12">
						<div class="fprom-group">
							<input type="hidden"  name="total_item_cost" required id="total_item_cost" class=" form-control total_item_cost" value="">
							
							<?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?>
							
							<button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>
						</div>
					</div>
				</div>
				
			</div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>
