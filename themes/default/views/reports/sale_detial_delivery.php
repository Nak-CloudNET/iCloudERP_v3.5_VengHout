<?php 
	/*$v = "";
	
	if ($this->input->post('reference_no')) {
		$v .= "&reference_no=" . $this->input->post('reference_no');
	}
	if ($this->input->post('customer')) {
		$v .= "&customer=" . $this->input->post('customer');
	}
	if ($this->input->post('driver')) {
		$v .= "&driver=" . $this->input->post('driver');
	}
	if ($this->input->post('warehouse')){
		$v .= "&warehouse=" . $this->input->post('warehouse');
	}
	if ($this->input->post('user')) {
		$v .= "&user=" . $this->input->post('user');
	}
	if ($this->input->post('serial')) {
		$v .= "&serial=" . $this->input->post('serial');
	}
	if ($this->input->post('start_date')){
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')){
		$v .= "&end_date=" . $this->input->post('end_date');
	}
	if (isset($biller_id)){
		$v .= "&biller_id=" . $biller_id;
	}*/ 
?>

<script type="text/javascript">
    $(document).ready(function (){
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
            },
			$('#customer').val(<?= $this->input->post('customer') ?>);
        });

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

<?php
    echo form_open('reports/deliveries_actions', 'id="action-form"');
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-heart"></i><?= lang('delivery_detail'); ?><?php
            if ($this->input->post('start_date')){
                echo " From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
                            class="icon fa fa-toggle-up"></i></a></li>
                <li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
                            class="icon fa fa-toggle-down"></i></a></li>
            </ul>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" id="pdf" data-action="export_pdf" class="tip" title="<?= lang('download_pdf') ?>"><i
                            class="icon fa fa-file-pdf-o"></i></a></li>
                <li class="dropdown"><a href="#" id="excel" data-action="export_excel"  class="tip" title="<?= lang('download_xls') ?>"><i
                            class="icon fa fa-file-excel-o"></i></a></li>
                <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                            class="icon fa fa-file-picture-o"></i></a></li>
				<!--<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("billers") ?>"></i>
					</a>
					<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
						aria-labelledby="dLabel">
						<li><a href="<?= site_url('reports/sales') ?>"><i class="fa fa-building-o"></i> <?= lang('billers') ?></a></li>
						<li class="divider"></li>
						<?php
						foreach ($billers as $biller){
							echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/sales/' . $biller->id) . '"><i class="fa fa-building"></i>' . $biller->company . '</a></li>';
						}
						?>
					</ul>
				</li>-->
            </ul>
        </div>
		
    </div>

    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>

    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('customize_report'); ?></p>

                <div id="form">

                    <?php echo form_open("reports/sales_detail_delivery",'method="GET"'); ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>
                            </div>
                        </div>
                       
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                <?php echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="customer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"'); ?>
                            </div> 
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="biller"><?= lang("Driver"); ?></label>
                                <?php
                                $bl[""] = "";
								
                                foreach ($driver as $dr){
									
                                    $bl[$dr->id] = isset($dr->company) != '' ? $dr->company : $dr->name;
                                }
                                echo form_dropdown('driver', $bl, (isset($_POST['driver']) ? $_POST['driver'] : ""), 'class="form-control" id="driver" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("driver") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                <?php
                                $wh[""] = "";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'class="form-control" id="warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                ?>
                            </div>
                        </div>
						<?php if($this->Settings->product_serial) { ?>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <?= lang('serial_no', 'serial'); ?>
                                    <?= form_input('serial', '', 'class="form-control tip" id="serial"'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : $this->erp->hrsd($start_date)), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : $this->erp->hrsd($end_date)), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>
						 
						
						
                    </div>
                    <div class="form-group col-lg-1"style="padding-left:0px;">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
					<div class="form-group col-lg-1">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("reset"), 'class="btn btn-danger reset"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
                <div class="clearfix"></div>

                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-striped">
						<thead>
							<tr class="info-head">
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="val" />
								</th>
								<th style="width:200px;" class="center"><?= lang("item"); ?></th> 
								<th style="width:130px;"><?= lang("warehouse"); ?></th> 
								<th style="width:130px;"><?= lang("quantity"); ?></th>
								<th style="width:130px;"><?= lang("quantity_received"); ?></th>
								<th style="width:40px;"><?= lang("unit"); ?></th>
								<th style="width:70px;"><?= lang("balance"); ?></th>
								 									
							</tr>
						</thead>
						<?php 
						   $grand_total=0;
						foreach($deliveries as $delivery){ 
						       $queries="SELECT erp_delivery_items.product_name,erp_products.code,   erp_delivery_items.begining_balance as t_qty, erp_delivery_items.quantity_received, erp_delivery_items.ending_balance as balance , erp_warehouses.name as warehouse, erp_product_variants.name as variants  From erp_delivery_items
									   LEFT JOIN erp_deliveries ON erp_deliveries.id =erp_delivery_items.delivery_id
									   LEFT JOIN erp_products ON erp_products.id =erp_delivery_items.product_id 
									   LEFT	JOIN erp_product_variants ON erp_product_variants.id = erp_delivery_items.product_id 
									   LEFT JOIN erp_warehouses ON erp_warehouses.id =erp_delivery_items.warehouse_id where erp_delivery_items.delivery_id = {$delivery->id}"; 
									   if($this->session->userdata('user_id')){   
									        $user = $this->session->userdata('user_id');
										    $this->db->where("erp_deliveries.delivery_by",$user);
									   } 
									  $query=$this->db->query($queries)->result();
									   
		
						?>
                        <tbody>
						       <tr class="bold">
							      <td style="min-width:30px; width: 30px; text-align: center;background-color:#E9EBEC">
									<input type="checkbox" name="val[]" class="checkbox multi-select input-xs" value="<?= $delivery->id; ?>" />
								  </td>
							       <td colspan="6" style="font-size:14px !important;background-color:#E9EBEC;color:#265F7B;"><?= $delivery->do_reference_no ." <i class='fa fa-angle-double-right' aria-hidden='true'></i> ".$delivery->sale_reference_no ." <i class='fa fa-angle-double-right' aria-hidden='true'></i> ".$delivery->customer ." <i class='fa fa-angle-double-right' aria-hidden='true'></i> ". $delivery->date ." <i class='fa fa-angle-double-right' aria-hidden='true'></i> ".$delivery->driver?></td>
							   </tr>
					 <?php
                       $quantity=0;
                       $quantity_re=0;
					   $quantity_balance=0;
					   
					 foreach($query as $q){ 
                             $quantity += $q->t_qty;
                             $quantity_re += $q->quantity_received;
							 $quantity_balance += $q->balance;
					?>
						       <tr>
							       <td></td> 
							       <td><?=$q->product_name ." (".$q->code .") "?></td> 
								   <td><?=$q->warehouse ?></td>
								   <td class="text-center"><?= $this->erp->formatDecimal($q->t_qty); ?></td> 
								   <td class="text-center"><?=$this->erp->formatDecimal($q->quantity_received); ?></td> 
								   <td><?=$q->variants ?></td> 
								   <td class="text-center"><?= $this->erp->formatDecimal($q->balance);?></td>
							   </tr>
							    
					    <?php }?>
						 
						      <tr>
							      <td></td>
							      <td></td>
								  <td class="bold text-right"><?= lang("total")?></td>
								  <td  class="bold text-center"><?= $quantity;?></td>
								  <td  class="bold text-center"><?= $quantity_re;?></td>
								  <td></td>
								  <td  class="bold text-center"><?= $quantity_balance;?></td>
							  </tr>
					 
					<?php
						$grand_total +=$quantity;
						$grand_total_received +=$quantity_re;
						$grand_total_balance +=$quantity_balance;
						
						}
						
						?>
					
					</tbody>
                    <tfoot>
                            <tr>
								 <td colspan="3" class="bold text-right" style="color:blue;"><?= lang("grand_total")?></td>
								 <td class="bold text-center" style="color:blue;"><?= $grand_total;?></td> 
								 <td class="bold text-center" style="color:blue;"><?= $grand_total_received;?></td> 
								 <td></td>
								 <td class="bold text-center" style="color:blue;"><?= $grand_total_balance;?></td> 
							</tr>
                    </tfoot>					
                    </table>
                </div>
				<div class=" text-right">
					<div class="dataTables_paginate paging_bootstrap">
						<?= $pagination; ?>
					</div>
				</div>
				
			 
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
		$('.reset').click(function(){
			window.location.reload(true);
		});
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
    });
</script>
<style type="text/css">
	
</style>