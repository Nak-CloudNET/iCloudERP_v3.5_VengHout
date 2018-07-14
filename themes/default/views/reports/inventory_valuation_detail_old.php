<?php


?>
<style type="text/css" media="all">
	#PRData{ 
		white-space:nowrap; 
		width:100%; 
		display: block;  
	}
    #PRData td:nth-child(6), #PRData td:nth-child(7) {
        text-align: right;
    }
    <?php if($Owner || $Admin || $this->session->userdata('show_cost')) { ?>
    #PRData td:nth-child(8) {
        text-align: right;
    }
    <?php } ?>
</style>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('inventory_valuation_detail') ; ?>
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
					<a href="#" id="pdf" data-action="export_pdf"  class="tip" title="<?= lang('download_pdf') ?>">
						<i class="icon fa fa-file-pdf-o"></i>
					</a>
				</li>
                <li class="dropdown">
						<a href="#" id="excel" data-action="export_excel"  class="tip" title="<?= lang('download_xls') ?>">
								<i class="icon fa fa-file-excel-o"></i>
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
				<?php echo form_open('reports/inventory_valuation_detail/', 'id="action-form"'); ?>
					<div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="cat"><?= lang("categories"); ?></label>
                                <?php
                                $cat[""] = "";
								$cat[""] = "ALL";
                                foreach ($categories as $category) {
                                    $cat[$category->id] = $category->name;
                                }
                                echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ""), 'class="form-control" id="category" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("category") . '"');
                                ?>
                            </div>
                        </div>
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="cat"><?= lang("products"); ?></label>
                                <?php
                                $cat[""] = "";
								$cat[""] = "ALL";
                                foreach ($products as $product) {
                                    $cat[$product->id] = $product->name;
                                }
                                echo form_dropdown('product', $cat, (isset($_POST['product']) ? $_POST['product'] : ""), 'class="form-control" id="product" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("producte") . '"');
                                ?>
                            </div>
                        </div>
						
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("type", "type"); ?>
                                  <?php $types = array(''=>'ALL','SALE' => lang('SALES'), 'PURCHASE' => lang('PURCHASES'),'TRANSFER' => lang('TRANSFER'),'SALES RETURN' => lang('SALES RETURN'),'USING STOCKS' => lang('USING STOCK'),'EXPENSE' => lang('expanse'),'DELIVERY' => lang('delivery'),'ADJUSTMENT' => lang('ADJUSTMENTS'),'STOCK COUNT' => lang('stock_count'));
                                echo form_dropdown('type', $types, (isset($_POST['type']) ? $_POST['type'] : "") , 'class="form-control input-tip" id="type" data-placeholder="'. $this->lang->line("select type") .'"'); ?>
							</div>
                        </div>
						
						
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                <?php
                                $wh[""] = "";
								$wh[""] = "ALL";
                                foreach ($swarehouses as $swarehouse) {
                                    $wh[$swarehouse->id] = $swarehouse->name;
                                }
                                echo form_dropdown('swarehouse', $wh, (isset($_POST['swarehouse']) ? $_POST['swarehouse'] : ""), 'class="form-control" id="swarehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                ?>
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
						
						<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("in_out", "in_out"); ?>
                                <?php $in_out = array(''=>lang('IN\OUT'),'in' => lang('in'), 'out' => lang('out'));
                                echo form_dropdown('in_out', $in_out, (isset($_POST['in_out']) ? $_POST['in_out'] : ""), 'class="form-control input-tip" id="in_out" data-placeholder="'.$this->lang->line("Select_in_out").'"'); ?>
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
                    <table id="PRData" class="table table-bordered table-hover table-striped table-condensed">
                        <thead>
                        <tr class="primary">
                            <th style="wi"></th>
							<th class="sorting"><?= lang("type") ?></th>
							<th class="sorting"><?= lang("date") ?></th>
							<th class="sorting"><?= lang("name") ?></th>
							<th class="sorting"><?= lang("reference") ?></th>
							<th class="sorting"><?= lang("biller") ?></th>
							<th class="sorting"><?= lang("qty") ?></th>
							<th class="sorting"><?= lang("cost") ?></th>
							<th class="sorting"><?= lang("on_hand") ?></th>
							<th class="sorting"><?= lang("avg_cost") ?></th>
							<th class="sorting"><?= lang("asset_value") ?></th>
                        </tr>
                        </thead>
                        <tbody>
							<?php 
							$gtt = 0;
							   foreach($warehouses as $warehouse){
									
									
							?>
							<tr>
								<td colspan="11" class="text-left" style="font-weight:bold; font-size:19px !important; color:blue;">
									<?= lang("warehouse"); ?>
									<i class="fa fa-angle-double-right" aria-hidden="true"></i>
									&nbsp;&nbsp;<?=$warehouse->warehouse?>
								</td>
							</tr>
							
							<?php 
							$categories = $this->reports_model->getCategoriesInventoryValuationByWarehouse($warehouse->warehouse_id,$cate_id,$product_id,$stockType,$stock_in_out,$from_date,$to_date,$reference);							
							foreach($categories AS $category){ ?>
							<tr>
								<td colspan="11" class="text-left" style="font-weight:bold; color:#ff5454;">								
									<?= lang("category"); ?>
									<i class="fa fa-angle-double-right" aria-hidden="true"></i>
									<?=$category->category_name?>
								</td>
							</tr>
							
							<?php 
							$products = $this->reports_model->getProductsInventoryValuationByWhCat($warehouse->warehouse_id,($cate_id!=""?$cate_id:$category->category_id),$product_id,$stockType,$stock_in_out,$from_date,$to_date,$reference);							
							foreach($products as $product){ 
								
								if(!empty($product->product_id)){
							?>
							<tr>
								<td class="center" style="font-weight:bold;"><?=$product->product_code?$product->product_code:$product->product_id?></td>
								<td colspan="10"></td>
							</tr>
							
							<?php 
							}
							$qty_on_hand = 0;
							$total_on_hand = 0;
							$total_asset_val = 0;
							$total_qoh_per_warehouse = 0;
							$total_assetVal_per_warehouse = 0;
							$prDetails = $this->reports_model->getProductsInventoryValuationByProduct($warehouse->warehouse_id,($cate_id!=""?$cate_id:$category->category_id),($product_id==""?$product->product_id:$product_id),$stockType,$stock_in_out,$from_date,$to_date,$reference);
							foreach($prDetails as $pr)
							{
								$p_cost = 0;
								$p_qty = 0;
								
								if($pr->type == 'PURCHASE' 
								|| $pr->type == 'SALE RETURN' 
								|| $pr->type == 'ADJUSTMENTS' 
								|| $pr->type == 'OPENING QUANTITY' )
								{								
									$p_qty = abs($pr->quantity);
								}else if($pr->type == 'TRANSFER'){
										if($pr->quantity<0){
											$p_qty = abs($pr->quantity);
										}else{
											$p_qty = (-1)*$pr->quantity;
										}
								}
								else
								{
									$p_qty = (-1) * $pr->quantity;
								}
								
								$qa = $this->db->get_where('purchase_items',array('id'=> $pr->field_id),1)->row()->option_id;
								if($qa){
									$unit_n = $this->db->get_where('erp_product_variants',array('id'=> $qa),1)->row();
									$unit_q = $unit_n->qty_unit;
									$unit_name = ' ( '.($this->erp->formatQuantity(abs($p_qty)/$unit_q)).' '.$unit_n->name.' )';
									
								}else{
									$this->db->select("units.name")->join("units","units.id=erp_products.unit","LEFT")->where("erp_products.id",$pr->product_id);
									$unit = $this->db->get("erp_products")->row();
									$unit_name = $unit->name;
								}
								
								$qty_on_hand += $p_qty ;// $pr->qty_on_hand;
								
								$p_cost = $this->erp->formatDecimal($pr->cost);
								$avg_cost = $pr->avg_cost;
								$this->db->select("cost")->where("erp_products.id",$pr->product_id);
									$cost = $this->erp->formatDecimal($this->db->get_where("erp_products",array("id"=>$product->product_id),1)->row()->cost);
								$asset_value = $cost * $qty_on_hand;
								
							?>
							<tr>
								<td style="border-top:none;border-bottom:none;"></td>
								<td><?= $pr->type ?></td>
								<td><?= $this->erp->hrsd($pr->date) ?></td>
								<td><?= $pr->name ?></td>
								<td><?= $pr->reference_no ?></td>
								<td><?= $pr->biller_name ?></td>
								<td class="text-right"><?= $this->erp->formatQuantity($p_qty) ?> <?=$unit_name?></td>
								<td class="text-right"><?= $p_cost ?></td>
								<td class="text-right"><?= $this->erp->formatQuantity($qty_on_hand) ?></td>
								<td class="text-right"><?= $cost ?></td>
								<td class="text-right"><?= $this->erp->formatDecimal($asset_value) ?></td>
							</tr>
							<?php 
								$total_on_hand = $qty_on_hand;
								$total_asset_val = $qty_on_hand * $cost;
							} ?>
							
							<tr class="active">
								<td colspan="8" class="right" style="font-weight:bold;"><?= lang("total") ?> 
									<i class="fa fa-angle-double-right" aria-hidden="true"></i> 
								</td>
								<td class="text-right"><b><?= $this->erp->formatDecimal($total_on_hand); ?></b></td>
								<td></td>
								<td class="text-right"><b><?= $this->erp->formatDecimal($total_asset_val); ?></b></td>
							</tr>
							<?php 
								$total_qoh_per_warehouse += $total_on_hand;
								$total_assetVal_per_warehouse += $total_asset_val;
							} 
							?>	
							<?php } ?>
							<tr>
								<td class="right" colspan="8" style="font-weight:bold; color:blue; "><?= lang("total") ?> 
									<i class="fa fa-angle-double-right" aria-hidden="true"></i> 
									<?=$warehouse->warehouse?></td>
								<td class="text-right"><b><?= $this->erp->formatDecimal($total_qoh_per_warehouse); ?></b></td>
								<td></td>
								<td class="text-right"><b><?= $this->erp->formatDecimal($total_assetVal_per_warehouse); ?></b></td>
							</tr>							
							<?php
							$gtt +=$total_assetVal_per_warehouse;
							}  ?>	
								<tr>
								<td class="right" colspan="8" style="font-weight:bold; color:blue; "><?= lang("grand_total") ?> 
									
								<td class="text-right"><b></td>
								<td></td>
								<td class="text-right"><b><?= $this->erp->formatDecimal($gtt); ?></b></td>
							</tr>		
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
	$(document).ready(function(){
		/*
		$("#excel").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url('products/getProductAll/0/xls/')?>";
			return false;
		});
		$('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('products/getProductAll/pdf/?v=1'.$v)?>";
            return false;
        });
		*/
		$('body').on('click', '#multi_adjust', function() {
			 if($('.checkbox').is(":checked") === false){
				alert('Please select at least one.');
				return false;
			}
			var arrItems = [];
			$('.checkbox').each(function(i){
				if($(this).is(":checked")){
					if(this.value != ""){
						arrItems[i] = $(this).val();   
					}
				}
			});
			$('#myModal').modal({remote: '<?=base_url('products/multi_adjustment');?>?data=' + arrItems + ''});
			$('#myModal').modal('show');
        });
		$('#excel').on('click', function (e) {
            e.preventDefault();
            if ($('.checkbox:checked').length <= 0) {
                window.location.href = "<?= site_url('reports/inventory/0/xls/'.$reference1.'/'.$wahouse_id1.'/'.$product_id1.'/'.$from_date1.'/'.$to_date1.'/'.$stock_in_out1.'/'.$stockType1.'/'.$cate_id1) ?>";
                return false;
            }
        });
        $('#pdf').on('click', function (e) {
            e.preventDefault();
            if ($('.checkbox:checked').length <= 0) {
                window.location.href = "<?= site_url('reports/inventory/pdf/0'.$reference1.'/'.$wahouse_id1.'/'.$product_id1.'/'.trim($from_date1).'/'.trim($to_date1).'/'.$stock_in_out1.'/'.$stockType1.'/'.$cate_id1) ?>";
                return false;
            }
        });
	});
</script>

