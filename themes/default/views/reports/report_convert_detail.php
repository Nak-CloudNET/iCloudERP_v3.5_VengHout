<script type="text/javascript">
    $(document).ready(function () {
          $('#form').hide();
		  $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
		$("#reset").click(function(){
			window.location.reload(true);
			$("#warehouse").empty();
			$("#reference_no").empty();
			$("#created_by").empty();
			$("#start_date").empty();
			$("#end_date").empty();
		});
    });
</script>
 
<?php if ($Owner) {
    echo form_open('reports/convertReportDetails_action', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-file"></i><?= lang("report_convert_detail") . ' ' . $id; ?></h2>
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
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>">
                        </i>
                    </a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <?php if (isset($inv->attachment)){ ?>
						<li>
							<a href="<?= site_url('welcome/download/' . $inv->attachment) ?>">
								<i class="fa fa-chain"></i> <?= lang('attachment') ?>
							</a>
						</li>
                        <?php } ?>
                         <li>
                            <a href="#" id="excel" data-action="export_excel">
                                <i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="pdf" data-action="export_pdf">
                                <i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>
                            </a>
                        </li>
						
                    </ul>
                </li>
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
                      
                    <?php echo form_open("reports/convert_report_detail/".$convert->product_id .'/'. 0 . '/'.$start.'/'.$end,'method="GET"'); ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?= form_input('reference_no', (isset($_GET['reference_no']) ? $_GET['reference_no'] : ''), 'class="form-control tip" id="reference_no"'); ?>
								<input type="hidden" name="id_id" value="<?=$id;?>">
                            </div>
                        </div>
						 <?php if(isset($biller_idd)){?>
						<div class="col-sm-3">
						 <div class="form-group">
                                    <?= lang("biller", "biller"); ?>
                                    <?php 
									$str = "";
									$q = $this->db->get_where("companies",array("id"=>$biller_idd),1);
									 if ($q->num_rows() > 0) {
										 $str = $q->row()->name.' / '.$q->row()->company;
										echo form_input('biller',$str , 'class="form-control" id="biller"');
									 }
									?>
                                </div>
						 </div>
						<?php } ?>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                <?php
                                $wh[""] = "ALL";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->code.' / '.$warehouse->name;
                                }
                                echo form_dropdown('warehouse', $wh, (isset($_GET['warehouse']) ? $_GET['warehouse'] : ''), 'class="form-control" id="warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                ?>
                            </div>
                        </div>
						 <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="created_by"><?= lang("created_by"); ?></label>
                                <?php
                                $ct[""] = "ALL";
                                foreach ($created_by as $created){
                                    $ct[$created->id] = $created->username;
                                }
                                echo form_dropdown('created_by', $ct, (isset($_GET['created_by']) ? $_GET['created_by'] : ''), 'class="form-control" id="created_by" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("created_by") . '"');
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
                                <?= lang("start_date", "start_date");?>
                                <?php echo form_input('start_date', (isset($_GET['start_date']) ? $_GET['start_date'] : $start_date), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_GET['end_date']) ? $_GET['end_date'] : $end_date), 'class="form-control datetime" id="end_date"'); ?>
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
                    <table class="table table-bordered table-hover table-striped print-table order-table">

                        <thead>
							<tr>
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="val[]" />
								</th> 
								<th style="width:200px;"><?= lang("type"); ?></th>
								<th><?= lang("item_code"); ?></th> 
								<th><?= lang("item_name"); ?></th>
								<th><?= lang("quantity"); ?></th> 
								<th><?= lang("unit"); ?></th>
								<th><?= lang("cost"); ?></th>
								<th><?= lang("total_costs"); ?></th>
							 
							</tr>
                        </thead>
                        <?php 
						 $n=1;
						 if(is_array($convert_detail)){
						foreach($convert_detail as $convert){
							if($convert->bom_id){
						    $query = $this->db->query("
							                    SELECT erp_convert_items.status,erp_convert_items.product_code,erp_convert_items.product_name,erp_convert_items.quantity,erp_units.name as unit,erp_product_variants.name as var_name,erp_convert_items.cost From erp_convert_items LEFT JOIN erp_products ON erp_products.id=erp_convert_items.product_id LEFT JOIN erp_units ON erp_units.id =erp_products.unit
                                                LEFT JOIN erp_product_variants ON erp_product_variants.id=erp_convert_items.option_id  
												where erp_convert_items.convert_id ='$convert->id'")->result();	
						?>
						      
                        <tbody>
                            <tr>
							    <td style="min-width:30px; width: 30px; text-align: center;background-color:#E9EBEC">
									<input type="checkbox" name="val[]" class="checkbox multi-select input-xs" value="<?= $convert->id; ?>" />
								</td>
							    <td colspan="7" class="bold"style="font-size:14px"><?=$convert->date .' >> '.$convert->reference_no .' >> '.$convert->warehouse .' >> '.$convert->username?></td> 
								
							</tr>
							<?php foreach($query as $q){?>
							   
							<tr>
							    <td></td>
							    <td><?=lang($q->status)?></td>
							    <td><?=$q->product_code?></td>
								<td><?=$q->product_name?></td>
								<td class="text-center"><?=$this->erp->formatQuantity($q->quantity)?></td>
								<td><?=!empty($q->var_name)?$q->var_name :$q->unit?></td>
								<td class="text-right"><?=$q->cost?></td>
								<td class="text-right"><?=$q->cost*$q->quantity?></td>
								
							</tr>
							<?php } ?>
                        </tbody>
						 <?php }  }  } ?>
                        <tfoot>
                               

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
