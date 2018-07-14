
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
    });
</script>

<?php if ($Owner) {
    echo form_open('reports/adjustment_report_action', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-heart"></i><?= lang('adjustment_report'); ?><?php
            if ($this->input->post('start_date')) {
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
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('customize_report'); ?></p>

                <div id="form">

                    <?php echo form_open("reports/adjustment_report/",'method="GET"'); ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_GET['reference_no']) ? $_GET['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>
                            </div>
                        </div>  
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                <?php
                                $wh[""] = "ALL";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] =  $warehouse->code.' / '.$warehouse->name;
                                }
                                echo form_dropdown('warehouse', $wh, (isset($_GET['warehouse']) ? $_GET['warehouse'] : ""), 'class="form-control" id="warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                ?>
                            </div>
                        </div>
						 <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="created_by"><?= lang("created_by"); ?></label>
                                <?php
                                $ct[""] = "ALL";
                                foreach ($created as $create){
                                    $ct[$create->id] = $create->username;
                                }
                                echo form_dropdown('created_by', $ct, (isset($_GET['created_by']) ? $_GET['created_by'] : ""), 'class="form-control" id="created_by" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("created_by") . '"');
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
                                <?php echo form_input('start_date', (isset($_GET['start_date']) ? $_GET['start_date'] : $this->erp->hrsd($start_date)), 'class="form-control date" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_GET['end_date']) ? $_GET['end_date'] : $this->erp->hrsd($end_date)), 'class="form-control date" id="end_date"'); ?>
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
                                <th style="width:200px;" class="center"><?= lang("item"); ?></th>
                                <th style="width:70px;"><?= lang("QOH"); ?></th>
								<th style="width:130px;"><?= lang("variant"); ?></th>
								<th style="width:90px;"><?= lang("type"); ?></th>
								<th style="width:130px;"><?= lang("quantity_adjustment"); ?></th> 
								 									
							</tr>
						</thead>
						<?php 
						   $grand_total=0;
						   if(is_array($items)){
						foreach($items as $item){ 
						       $query=$this->db->query("
							           SELECT erp_adjustment_items.quantity as qty_adjust,erp_adjustment_items.type,erp_products.code as codes,erp_products.name as product_names,erp_products.quantity as QOH,erp_product_variants.name as variant,erp_units.name as unit, erp_products.image
                                       From erp_adjustment_items
									   left JOIN erp_products ON erp_products.id=erp_adjustment_items.product_id 
										LEFT JOIN erp_product_variants ON erp_product_variants.id = erp_adjustment_items.option_id 
										LEFT JOIN erp_units ON erp_units.id=erp_products.unit  
									   where erp_adjustment_items.adjust_id = {$item->id}
                       
                     ")->result();
		
						?>
                        <tbody>
						       <tr class="bold">
							      <td style="min-width:30px; width: 30px; text-align: center;">
									<input type="checkbox" name="val[]" class="checkbox multi-select input-xs" value="<?= $item->id; ?>" />
								  </td>
                                   <td colspan="6"
                                       style="font-size:14px !important;background-color:#E9EBEC;color:#527E95"><?= $item->date . " <i class='fa fa-angle-double-right' aria-hidden='true'></i> " . $item->reference_no . " <i class='fa fa-angle-double-right' aria-hidden='true'></i> " . $item->warehouse . " <i class='fa fa-angle-double-right' aria-hidden='true'></i> " . $item->username ?> </td>
							   </tr>
					 <?php
                       $quantity=0;
					   
					 foreach($query as $q){ 
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
							       <td><?= $q->codes ? "(".$q->codes .")".$q->product_names :'';?></td> 
							        
								   <td><?= $q->QOH ?> (<?=$q->unit?>)</td>
								   <td class="text-center"><?= !empty($q->variant)?$q->variant :$q->unit ?></td> 
								   <td class="text-center"><?= $q->type?></td> 
								   <td class="text-center"><?= $this->erp->formatQuantity($q->qty_adjust)?></td>  
							   </tr>
							    
					    <?php
						
						}?>
						     
						<?php }  }?>
					</tbody>
                   			
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
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getSalesReport/0/xls/?v=1'.$v)?>";
            return false;
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