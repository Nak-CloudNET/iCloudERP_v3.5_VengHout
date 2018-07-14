<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('tranfer_report') ; ?>
        </h2>
		<div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="toggle_up tip" title="<?= lang('hide_form') ?>">
                        <i class="icon fa fa-toggle-up"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0);" class="toggle_down tip" title="<?= lang('show_form') ?>">
                        <i class="icon fa fa-toggle-down"></i>
                    </a>
                </li>
            </ul>
        </div>       
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('list_results'); ?></p>
                <div id="form">
				<?php echo form_open('reports/production_report', 'id="action-form"'); ?>
					<div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("from_date", "from_date"); ?>
                                <?php echo form_input('from_date', (isset($_POST['from_date']) ? $_POST['from_date'] : ""), 'class="form-control date" id="from_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("to_date", "to_date"); ?>
                                <?php echo form_input('to_date', (isset($_POST['to_date']) ? $_POST['to_date'] : ""), 'class="form-control date" id="to_date"'); ?>
                            </div>
                        </div>				
                    </div>
					<div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>
					
                </div>
                <div class="clearfix"></div>

                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-hover table-striped">
                        <thead>
							<tr>
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="val" />
								</th>
								<th class="sorting"><?= lang("item_code") ?></th>
								<th class="sorting"><?= lang("item_name") ?></th>
								<th class="sorting"><?= lang("quantity") ?></th>
								<th class="sorting"><?= lang("unit") ?></th>
								<th class="sorting"><?= lang("cost") ?></th>
								<th class="sorting"><?= lang("total_costs") ?></th>							
							</tr>                        
						</thead>
                        <tbody>
						<?php
							 $this->db->select("
												erp_convert.id,
												bom_id,
												erp_convert.reference_no,
												erp_convert.date,
												erp_bom.name,
												erp_warehouses.name as wname")
										->join("erp_bom","erp_bom.id=erp_convert.bom_id","LEFT")
										->join("erp_warehouses","erp_warehouses.id=erp_convert.warehouse_id","LEFT");
										if($reference){
											$this->db->where("erp_convert.reference_no",$reference);
										}
										if($from_date && $to_date){
											$this->db->where('erp_convert.date >="'.$from_date.'" AND erp_convert.date<="'.$to_date.'"');
										}
										$converts = $this->db->get("erp_convert")->result();
							$bom_id = 0;
							foreach($converts as $convert){
								$bom_id = $convert->bom_id;
						?>
							<tr style="font-weight:bold;">
								
								<td style="min-width:30px; width: 30px; text-align: center;">
									<input type="checkbox" name="val" class="checkbox multi-select input-xs" />
								</td>
								<th colspan="5">
									<?=$this->erp->hrsd($convert->date)?> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
									<?=$convert->reference_no?> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
									<?=$convert->name?> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
									<?=$convert->wname?>
								<td></td>							
							</tr>
							<tr style="font-weight:bold; text-transform: uppercase;">
								<td></td>
								<td><?= lang("input") ?></td>							
								<td colspan="5"></td>
							</tr>
							<?php
							$convert_items = $this->db->select("
												convert_items.*,
												product_variants.name as uname,
												product_variants.qty_unit")
										->join("product_variants","product_variants.id=convert_items.option_id","LEFT")										
										->where("convert_items.status","deduct")
										->where("convert_id",$convert->id)
										->get("convert_items")->result();
							$total_input = 0;			
							foreach($convert_items as $convert_item){ 
								$total_input_cost = $convert_item->cost*$convert_item->quantity;
								$total_input += $total_input_cost
							?>
								<tr>
									<td ></td>
									<td class="text-center"><?=$convert_item->product_code?></td>
									<td class="text-center"><?=$convert_item->product_name?></td>
									<td class="text-center"><?=$convert_item->quantity?></td>
									<td class="text-center"><?=$convert_item->uname?></td>
									<td class="text-right"><?= $this->erp->formatMoney($convert_item->cost)?></td>
									<td class="text-right"><?= $this->erp->formatMoney($total_input_cost)?></td>									
								</tr>
							<?php } ?>
								<tr style="font-weight:bold; text-transform: uppercase;">
									<td class="text-right" colspan="6"><?= lang("total") ?> :</td>							
									<td class="text-right"><?= $this->erp->formatMoney($total_input)?></td>
								</tr>
							
							<tr style="font-weight:bold; text-transform: uppercase;">
								<td></td>
								<td><?= lang("output") ?></td>							
								<td colspan="5"></td>
							</tr>
							<?php
							$convert_items1 = $this->db->select("
												convert_items.*,
												product_variants.name as uname,
												product_variants.qty_unit")
										->join("product_variants","product_variants.id=convert_items.option_id","LEFT")										
										->where("convert_items.status","add")
										->where("convert_id",$convert->id)
										->get("convert_items")->result();
							$total_output = 0;			
							foreach($convert_items1 as $convert_item1){ 
								$total_output_cost = $convert_item1->cost*$convert_item1->quantity;
								$total_output += $total_output_cost;
							?>
								<tr>
									<td ></td>
									<td class="text-center"><?=$convert_item1->product_code?></td>
									<td class="text-center"><?=$convert_item1->product_name?></td>
									<td class="text-center"><?=$convert_item1->quantity?></td>
									<td class="text-center"><?=$convert_item1->uname?></td>
									<td class="text-right"><?= $this->erp->formatMoney($convert_item1->cost)?></td>
									<td class="text-right"><?= $this->erp->formatMoney($total_output_cost)?></td>
								</tr>
							<?php } ?>
								<tr style="font-weight:bold; text-transform: uppercase;">
									<td class="text-right" colspan="6"><?= lang("total") ?> :</td>							
									<td class="text-right"><?= $this->erp->formatMoney($total_output)?></td>
								</tr>
							<?php } ?>
                        </tbody>                       
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#form').hide();
    $('.toggle_down').click(function () {
        $("#form").slideDown();
        return false;
    });
    $('.toggle_up').click(function () {
        $("#form").slideUp();
        return false;
    });	
</script>