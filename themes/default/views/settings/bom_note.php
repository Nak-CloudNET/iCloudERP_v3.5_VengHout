<style type="text/css">
    @media print {
        #myModal .modal-content {
            display: none !important;
        }
    }
</style>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body print">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i></button>
				<h4 class="modal-title"><?= lang('product_analysis'); ?></h4>
			</div>
            
			<!-- table show convert from items -->
			<div class="col-md-12">
				<div class="control-group table-group">
					<label class="table-label"><?= lang("bom_items_from"); ?> *</label>

					<div class="controls table-controls">
						<table id="slTable_"
							   class="table items table-striped table-bordered table-condensed table-hover">
							<thead>
								<tr>
									<th class="col-md-6"><?= lang("product_name") . " (" . lang("product_code") . ")"; ?></th>
									<th class="col-md-1"><?= lang("unit"); ?></th>
									<th class="col-md-1"><?= lang("qoh"); ?></th>
									<th class="col-md-1"><?= lang("quantity"); ?></th>
									<th class="col-md-4"><?= lang("result"); ?></th>
								</tr>
							</thead>
							<tbody id="tbody-convert-from-items">
								<?php
									$i = 0;
									foreach($bom as $get_bom){
										if($get_bom['status'] == 'deduct'){
								?>
								<tr>
									<td><?= $get_bom['product_name'] .' ('.$get_bom['product_code'].')';?></td>
									<td class="text-center"><span class="label label-primary"><?= $get_bom['var_name']; ?></span></td>
									<td><?= $get_bom['qoh'];?></td>
									<td class="qty"><?= $get_bom['quantity'];?></td>
									<td class="result<?= $i;?> show-result"></td>
								</tr>
								<?php
										$i++;
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
                       
			<!-- table convert to items -->
			<div class="col-md-12">
				<div class="control-group table-group">
					<label class="table-label"><?= lang("bom_items_to"); ?> *</label>

					<div class="controls table-controls">
						<table id="slTable_ "
							class="table items table-striped table-bordered table-condensed table-hover">
							<thead>
							<tr>
								<th class="col-md-6"><?= lang("product_name") . " (" . lang("product_code") . ")"; ?></th>
								<th class="col-md-1"><?= lang("unit"); ?></th>
								<th class="col-md-1"><?= lang("qoh"); ?></th>
								<th class="col-md-1"><?= lang("quantity"); ?></th>
								<th class="col-md-4"><?= lang("value"); ?></th>
							</tr>
							</thead>
							<tbody id="tbody-convert-to-items">
								<?php
									foreach($bom as $get_bom){
										if($get_bom['status'] == 'add'){
								?>
								<tr>
									<td><?= $get_bom['product_name'] .' ('.$get_bom['product_code'].')';?></td>
									<td class="text-center"><span class="label label-primary"><?= $get_bom['var_name']; ?></span></td>
									<td><?= $get_bom['qoh'];?></td>
									<td><?= $get_bom['quantity'];?></td>
									<td><input type="type" value="" class="form-control type" /></td>
								</tr>
								<?php
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
            
            <div style="clear: both;"></div>
            <!--
			<div class="row">
                <div class="col-sm-4 pull-left">
                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <p>&nbsp;</p>

                    <p style="border-bottom: 1px solid #666;">&nbsp;</p>

                    <p><?= lang("stamp_sign"); ?></p>
                </div>
                <div class="clearfix"></div>
            </div>
			-->
        </div>
    </div>
</div>
<script>
	$(document).ready(function(){
		$('.type').keyup(function(){
			var num = $(this).val();
			$('.qty').each(function(i){
				var qty = parseFloat(num) * parseFloat($(this).text());
				$('.result'+i).text(qty);
			});
			
		});
	});
</script>