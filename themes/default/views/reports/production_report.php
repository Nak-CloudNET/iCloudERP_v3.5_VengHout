<script type="text/javascript">
$(document).ready(function(){
	$('body').on('click', '#excel1', function(e) {
	   e.preventDefault();
	   var k = false;
	   $.each($("input[name='val[]']:checked"), function(){
	    k = true;

	   });
	   // if(k == false){
	    // bootbox.alert('Please select!');
	    // return false;
	   // }
	   $('#form_action').val($('#excel1').attr('data-action'));
	   $('#action-form-submit').trigger('click');
  	});
  	$('body').on('click', '#pdf1', function(e) {
	   e.preventDefault();
	   var k = false;
	   $.each($("input[name='val[]']:checked"), function(){
	    
	    k = true;
	   });
	   if(k == false){
	    bootbox.alert('Please select tag5!');
	    return false;
	   }
	   $('#form_action').val($('#pdf1').attr('data-action'));
	   $('#action-form-submit').trigger('click');
  	});
});
</script>
<?php 
if ($Owner) {
   echo form_open('reports/productionReports' ,'id="action-form"');
} 
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('production_report') ; ?>
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
				<li class="dropdown">
					<a href="#" id="pdf1" data-action="export_pdf"  class="tip" title="<?= lang('download_pdf') ?>">
						<i class="icon fa fa-file-pdf-o"></i>
					</a>
				</li>
                <li class="dropdown">
					<a href="#" id="excel1" data-action="export_excel"  class="tip" title="<?= lang('download_xls') ?>">
						<i class="icon fa fa-file-excel-o"></i>
					</a>
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

                <p class="introtext"><?= lang('list_results'); ?></p>
                <div id="form">
				<?php echo form_open('reports/production_report', 'id="action-form" method="GET"'); ?>
					<div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_GET['reference_no']) ? $_GET['reference_no'] : ''), 'class="form-control tip" id="reference_no"'); ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("from_date", "from_date"); ?>
                                <?php echo form_input('from_date', (isset($_GET['from_date']) ? $_GET['from_date'] : ''), 'class="form-control date" id="from_date"'); ?>
								
							</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("to_date", "to_date"); ?>
                                <?php echo form_input('to_date', (isset($_GET['to_date']) ? $_GET['to_date'] : ''), 'class="form-control date" id="to_date"'); ?>
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
									<input class="checkbox checkth" type="checkbox"/>
								</th>								
								<th class="sorting"><?= lang("type") ?></th>
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
							 
							$bom_id = 0;
							foreach($converts as $convert){
								$bom_id = $convert->bom_id;
						?>
							<tr style="font-weight:bold;">
								<td style="min-width:30px; width: 30px; text-align: center;background-color:#E9EBEC">
									<input type="checkbox" name="val[]" class="checkbox multi-select input-xs" value="<?= $convert->id;?>"/>
								</td>
								<td colspan="7" style="font-size:14px !important;background-color:#E9EBEC;color:#265F7B;">
									<?=$this->erp->hrsd($convert->date)?> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
									<?=$convert->reference_no?> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
									<?=$convert->name?> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
									<?=$convert->wname?>
								</td>							
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
							?>
							
							<?php 
							$total_input = 0;			
							foreach($convert_items as $convert_item){ 
								$total_input_cost = $convert_item->cost*$convert_item->quantity;
								$total_input += $total_input_cost
							?>
								<tr>
									<td class="text-center"></td>
									<td class="text-center"><?= lang("INPUT"); ?></td>
									<td class="text-center"><?=$convert_item->product_code?></td>
									<td class="text-center"><?=$convert_item->product_name?></td>
									<td class="text-center"><?=$convert_item->quantity?></td>
									<td class="text-center"><?=$convert_item->uname?></td>
									<td class="text-right"><?= $this->erp->formatMoney($convert_item->cost)?></td>
									<td class="text-right"><?= $this->erp->formatMoney($total_input_cost)?></td>									
								</tr>
							<?php } ?>
								<tr style="font-weight:bold;  text-transform: uppercase;">
									<td class="text-right" colspan="7"><?= lang("total") ?> <?= lang("INPUT"); ?> :</td>							
									<td class="text-right"><?= $this->erp->formatMoney($total_input)?></td>
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
							?>
							<?php 
							$total_output = 0;			
							foreach($convert_items1 as $convert_item1){ 
								$total_output_cost = $convert_item1->cost*$convert_item1->quantity;
								$total_output += $total_output_cost;
							?>
								<tr>
									<td class="text-center"></td>
									<td class="text-center"><?= lang("OUTPUT"); ?></td>
									<td class="text-center"><?=$convert_item1->product_code?></td>
									<td class="text-center"><?=$convert_item1->product_name?></td>
									<td class="text-center"><?=$convert_item1->quantity?></td>
									<td class="text-center"><?=$convert_item1->uname?></td>
									<td class="text-right"><?= $this->erp->formatMoney($convert_item1->cost)?></td>
									<td class="text-right"><?= $this->erp->formatMoney($total_output_cost)?></td>
								</tr>
							<?php } ?>
								<tr style="font-weight:bold; text-transform: uppercase;">
									<td class="text-right" colspan="7"><?= lang("total") ?> <?= lang("OUTPUT"); ?> :</td>							
									<td class="text-right"><?= $this->erp->formatMoney($total_output)?></td>
								</tr>
							<?php } ?>
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
<?php
function status($x){
	if($x == 'completed' || $x == 'paid' || $x == 'sent' || $x == 'received' || $x == 'deposit' || $x == 'add') {
		return '<div class="text-center"><span class="label label-success">'.lang($x).'</span></div>';
	}elseif($x == 'pending' || $x == 'book' || $x == 'free' || $x == 'taken' || $x == 'inactive'){
		return '<div class="text-center"><span class="label label-warning">'.lang($x).'</span></div>';
	}elseif($x == 'partial' || $x == 'transferring' || $x == 'ordered'  || $x == 'processing'){
		return '<div class="text-center"><span class="label label-info">'.lang($x).'</span></div>';
	}elseif($x == 'due' || $x == 'returned' || $x == 'regular' || $x == 'deduct'){
		return '<div class="text-center"><span class="label label-danger">'.lang($x).'</span></div>';
	}else{
		return '<div class="text-center"><span class="label label-default">'.lang($x).'</span></div>';
	}
}
 ?>
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