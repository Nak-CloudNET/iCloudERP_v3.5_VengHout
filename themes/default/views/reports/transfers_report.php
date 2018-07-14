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
            ajax:{
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page){
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    }else{
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            },
			$('#customer').val(<?= $this->input->post('customer') ?>);
    })

        <?php } ?>
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
		$("#to_warehouse").click(function(e){
			      var from_warehouse =$("#from_warehouse").val();
				  var to_warehouse =$("#to_warehouse").val();
				  if(from_warehouse == to_warehouse){
                     $('#to_warehouse').val('').trigger('change');				  
				  }
		});
		$("#from_warehouse").click(function(e){
			      var to_warehouse =$("#to_warehouse").val();
				  var from_warehouse =$("#from_warehouse").val();
				  if(from_warehouse == to_warehouse){
                     $('#from_warehouse').val('').trigger('change');				  
				  }
					 
		});
		
    });
</script>

<?php
    echo form_open('reports/transferReport', 'id="action-form"');
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-heart"></i><?= lang('transfers_report'); ?><?php
            if ($this->input->post('start_date')){
                echo " From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
            }
            ?>
		</h2>

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

                    <?php echo form_open("reports/transfers_report",'method="GET"'); ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_GET['reference_no']) ? $_GET['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>
                            </div>
                        </div> 
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="from_warehouse"><?= lang("from_warehouse"); ?></label>
                                <?php
                                $wh[""] = "ALL";
                                foreach ($warehousesa as $warehouse){
                                    $wh[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('from_warehouse', $wh, (isset($_GET['from_warehouse']) ? $_GET['from_warehouse'] : ""), 'class="form-control" id="from_warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("from_warehouse") . '"');
                                ?>
                            </div>
                        </div>
						 <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="to_warehouse"><?= lang("to_warehouse"); ?></label>
                                <?php
                                $wh2[""] = "ALL";
                                foreach ($warehouses as $warehouse) {
                                    $wh2[$warehouse->id] = $warehouse->code.' / '.$warehouse->name;
                                }
                                echo form_dropdown('to_warehouse', $wh2, (isset($_GET['to_warehouse']) ? $_GET['to_warehouse'] : ""), 'class="form-control" id="to_warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
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
                                <?php echo form_input('start_date', (isset($_GET['start_date']) ? $_GET['start_date'] : $this->erp->hrsd($start_date)), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_GET['end_date']) ? $_GET['end_date'] : $this->erp->hrsd($end_date)), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>
						 
						
						
                    </div>
                    <div class="form-group col-lg-1"style="padding-left:0px;">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
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
                                <th style="width:100px;" class="center"><?= lang("image"); ?></th>
                                <th style="width:200px;" class="center"><?= lang("Description"); ?></th>
                                <th style="width:150px;"><?= lang("quantity"); ?></th>
								<th style="width:150px;"><?= lang("unit"); ?></th>
								<th style="width:150px;display:none;"><?= lang("unit_cost"); ?></th>
								<th style="width:150px;display:none;"><?= lang("tax"); ?></th>
								<th style="width:150px;display:none;"><?= lang("subtotal"); ?></th>
								 									
							</tr>
						</thead>
						<?php 
						   $g_qty=0;
						   $g_cost=0;
						   $g_tax=0;
						   $g_subtotal=0;
						   if(is_array($transfers)){
						foreach($transfers as $transfer){ 
						       $query=$this->db->query("
							      SELECT product_name,
									product_code,
									unit_cost,
									item_tax, 
									subtotal,
									erp_transfer_items.quantity,
									erp_product_variants.name as var_name,
									erp_units.name as unit_name, erp_products.image
								  From erp_transfer_items
								  LEFT JOIN erp_products ON erp_products.id=erp_transfer_items.product_id 
								  LEFT JOIN erp_units ON erp_units.id =erp_products.unit
								  LEFT JOIN erp_product_variants ON option_id = erp_product_variants.id
							      where erp_transfer_items.transfer_id = '$transfer->id'")->result();
						?>
                        <tbody>
						       <tr class="bold" style="">
							      <td style="min-width:30px; width: 30px; text-align: center;background-color:#E9EBEC;">
									<input type="checkbox" name="val[]" class="checkbox multi-select input-xs" value="<?= $transfer->id; ?>" />
								  </td>
                                   <td colspan="7"
                                       style="font-size:14px !important;background-color:#E9EBEC;color:#27267B; ">
										<?= $transfer->transfer_no ." <i class='fa fa-angle-double-right' aria-hidden='true'></i> ".$transfer->date ." <i class='fa fa-angle-double-right' aria-hidden='true'></i> " . lang('from').' : '. $transfer->from_warehouse_name . " <i class='fa fa-angle-double-right' aria-hidden='true'></i> " .lang('to').' : '. $transfer->to_warehouse_name ."<i class='fa fa-angle-double-right' aria-hidden='true'></i>By:".$transfer->username?>
								   </td>
								 
							   </tr>
					 <?php 
					     $Tqty=0;$t_cost=0;$t_tax=0;$t_subtotal=0;
					   foreach($query as $q){
						   $Tqty +=$q->quantity;
						   $t_cost +=$q->unit_cost;
						   $t_tax +=$q->item_tax;
						   $t_subtotal +=$q->subtotal;
  					 ?>
						       <tr>
							       <td></td>
                                   <td style="text-align:center !important;">
                                       <ul class="enlarge">
                                           <li>
                                               <img src="<?= base_url() ?>/assets/uploads/thumbs/<?= $q->image ?>"
                                                    class="img-responsive" style="width:50px;"/>
                                               <span>
                                                      <a href="<?= base_url() ?>/assets/uploads/thumbs/<?= $q->image ?>"
                                                         data-toggle="lightbox">
                                                        <img src="<?= base_url() ?>/assets/uploads/thumbs/<?= $q->image ?>"
                                                             style="width:150px; z-index: 9999999999999;"
                                                             class="img-thumbnail"/>
                                                      </a>
                                                    </span>
                                           </li>
                                       </ul>
                                   </td>
							       <td><?= $q->product_name ." (".$q->product_code .")"?></td>  
								   <td class="text-center"><?= $this->erp->formatQuantity($q->quantity)?></td> 
								   <td class="text-center"><?= !empty($q->var_name)?$q->var_name:$q->unit_name ?></td> 
							       <td class="text-right"style="display:none;"><?= $this->erp->formatMoney($q->unit_cost)?></td> 
								   <td class="text-right"style="display:none;"><?= $this->erp->formatMoney($q->item_tax)?></td>
								   <td class="text-right"style="display:none;"><?= $this->erp->formatMoney($q->subtotal)?></td> 
							   </tr> 
					   <?php } ?>
					           <tr> 
							       <td></td>
                                   <td class="bold right"><?= lang("total") ?> :</td>
                                   <td></td>
								   <td class="text-center"><?=$Tqty ?></td> 
								   <td class="text-center"></td> 
							       <td class="text-right"style="display:none;"><?=$this->erp->formatMoney($t_cost) ?></td> 
								   <td class="text-right"style="display:none;"><?=$this->erp->formatMoney($t_tax) ?></td>
								   <td class="text-right"style="display:none;"><?=$this->erp->formatMoney($t_subtotal) ?></td> 
							   </tr>
					   <?php
                           $g_qty += $Tqty;
						   $g_cost += $t_cost;
						   $g_tax += $t_tax;
						   $g_subtotal += $t_subtotal;
					   } 
						   }?>
						
					</tbody>
					<tfoot>
					           <tr style="background:#428BCA; color:white; font-size:16px !important;"> 
							       <td></td>
                                   <td class="bold right"><?= lang("grand_total") ?>:</td>
                                   <td></td>
								   <td class="bold text-center"><?=$g_qty ?></td> 
								   <td class="bold text-center"></td> 
							       <td class="bold text-right"style="display:none;"><?=$this->erp->formatMoney($g_cost) ?></td> 
								   <td class="bold text-right"style="display:none;"><?=$this->erp->formatMoney($g_tax) ?></td>
								   <td class="bold text-right"style="display:none;"><?=$this->erp->formatMoney($g_subtotal) ?></td> 
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
		
        // $('#pdf').click(function (event) {
            // event.preventDefault();
            // window.location.href = "<?=site_url('reports/getSalesReport/pdf/?v=1'.$v)?>";
            // return false;
        // });
        // $('#xls').click(function (event) {
            // event.preventDefault();
            // window.location.href = "<?=site_url('reports/getSalesReport/0/xls/?v=1'.$v)?>";
            // return false;
        // });
		$('.reset').click(function(){
			window.location.reload(true);
		});
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL();
                    window.open(img);
                }
            });
            return false;
        });
    });
</script>
<style type="text/css">
	
</style>