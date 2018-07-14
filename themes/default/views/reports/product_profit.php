<style type="text/css" media="all">
	#PRData{ 
		white-space:nowrap; 
		width:100%; 
		
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
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('product_profit') ; ?>
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
				<?php echo form_open('reports/product_profit/', 'id="action-form"'); ?>
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
                               
								$pro[""] = "ALL";
                                foreach ($products as $product) {
                                    $pro[$product->id] = $product->code.' / '.$product->name;
                                }
                                echo form_dropdown('product', $pro, (isset($_POST['product']) ? $_POST['product'] : ""), 'class="form-control" id="product" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("producte") . '"');
                                ?>
                            </div>
                        </div>
						
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                <?php
								$wh[""] = "ALL";
                                foreach ($swarehouses as $swarehouse) {
                                    $wh[$swarehouse->id] =  $swarehouse->code.' / '.$swarehouse->name;
                                }
                                echo form_dropdown('swarehouse', $wh, (isset($_POST['swarehouse']) ? $_POST['swarehouse'] : ""), 'class="form-control" id="swarehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                ?>
                            </div>
                        </div>
						<div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("biller"); ?></label>
                                <?php
								$bill[""] = "ALL";
                                foreach ($billers as $biller) {
                                    $bill[$biller->id] =  $biller->code.' / '.$biller->name;
                                }
                                echo form_dropdown('biller', $bill, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("from_date", "from_date"); ?>
                                <?php echo form_input('from_date', (isset($_POST['from_date']) ? $_POST['from_date'] : $this->erp->hrsd($from_date1)), 'class="form-control date" id="from_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("to_date", "to_date"); ?>
                                <?php echo form_input('to_date', (isset($_POST['to_date']) ? $_POST['to_date'] : $this->erp->hrsd($to_date1)), 'class="form-control date" id="to_date"'); ?>
                            </div>
                        </div>
						
						<!--<div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("in_out", "in_out"); ?>
                                <?php $in_out = array(''=>lang('IN\OUT'),'in' => lang('in'), 'out' => lang('out'));
                                echo form_dropdown('in_out', $in_out, (isset($_POST['in_out']) ? $_POST['in_out'] : ""), 'class="form-control input-tip" id="in_out" data-placeholder="'.$this->lang->line("Select_in_out").'"'); ?>
							</div>
                        </div>-->
						
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
                            <th style="width: 100px"><?= lang("image") ?></th>
							<th><?= lang("date") ?></th>
							<th ><?= lang("reference") ?></th>
							<th ><?= lang("customer") ?></th>
							<th ><?= lang("biller") ?></th>
							<th ><?= lang("qty") ?></th>
							<th ><?= lang("price_amount") ?></th>
							<th ><?= lang("cost_amount") ?></th>
							<th ><?= lang("profit") ?></th>
				
                        </tr>
                        </thead>
                        <tbody>
							<?php 
							$gqty = 0;
							$gprice = 0;
							$gcost = 0;
							$gprofit = 0;
							
							if(is_array($warehouses)){
								foreach($warehouses as $warehouse){
									?>
									<tr>
                                        <td colspan="10" class="text-left"
                                            style="font-weight:bold; font-size:19px !important; color:green;">
											<?= lang("warehouse"); ?>
											<i class="fa fa-angle-double-right" aria-hidden="true"></i>
											&nbsp;&nbsp;<?=$warehouse->warehouse?>
										</td>
									</tr>
									
									<?php 
									
									$categories = $this->reports_model->getCategoriesProductProfitByWarehouse($warehouse->warehouse_id,$cate_id1,$product_id1,$from_date1,$to_date1,$reference1,$biller1);	
									
									$total_quantity_by_warehouse =0;
									$total_cost_by_warehouse =0;
									$total_price_by_warehouse =0;
									$total_profit_by_warehouse =0;
									
									foreach($categories AS $category){ ?>
										
										<tr>
                                            <td colspan="10" class="text-left" style="font-weight:bold; color:orange;">
                                                &nbsp;&nbsp;&nbsp;&nbsp;
												<?= lang("category"); ?>
												<i class="fa fa-angle-double-right" aria-hidden="true"></i>
												<?=$category->category_name?>
											</td>
										</tr>
										
										<?php 
										
										$products = $this->reports_model->getProductsProfitByWhCat($warehouse->warehouse_id,($cate_id1?$cate_id1:$category->id),$product_id1,$from_date1,$to_date1,$reference1,$biller1);							
										
										foreach($products as $product){
											if(!empty($product->product_id)){
												
												?>
												<tr>
                                                    <td colspan="10" class="left" style="font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $product->product_code ? $product->product_code : $product->product_id ?>
                                                        <i class="fa fa-angle-double-right"
                                                           aria-hidden="true"></i> <?= $product->product_name ?>
                                                        (<?= $product->un; ?>)
                                                    </td>
													
												</tr>
												<?php 
											}
												$total_quantity = 0;
												$total_cost = 0;
												$total_price = 0;
												$total_profit = 0;
												$unit_name = "";
												$prDetails = $this->reports_model->getProductsProfitByProduct($warehouse->warehouse_id,($cate_id1?$cate_id1:$category->category_id),($product_id1?$product_id1:$product->product_id),$from_date1,$to_date1,$reference1,$biller1);
												foreach($prDetails as $pr)
												{
													
													$p_qty = $this->erp->formatDecimal($pr->option_id ? ($pr->quantity * $pr->qty_variant) : $pr->quantity);
													$unit_name = $this->erp->convert_unit_2_string($pr->product_id,$p_qty);
													$unit_cost_amount = $this->erp->formatMoney($pr->unit_cost * $p_qty);
													$unit_price_amount = $this->erp->formatMoney($pr->option_id ? ($pr->unit_price * $pr->quantity) : ($pr->unit_price * $p_qty));
													$profit = $this->erp->formatMoney(($pr->option_id ? ($pr->unit_price * $pr->quantity) : ($pr->unit_price * $p_qty)) - ($pr->unit_cost * $p_qty));
													$total_quantity+=$p_qty;
													$total_cost+=$unit_cost_amount;
													$total_price+=$unit_price_amount;
													$total_profit+=$profit;
													
													?>
													<tr>
														<td style="border-top:none;border-bottom:none;"></td>
                                                        <td style="text-align:center !important;">
                                                            <ul class="enlarge">
                                                                <li>
                                                                    <img src="<?= base_url() ?>/assets/uploads/thumbs/<?= $pr->image ?>"
                                                                         class="img-responsive" style="width:50px;"/>
                                                                    <span>
                                                                      <a href="<?= base_url() ?>/assets/uploads/thumbs/<?= $pr->image ?>"
                                                                         data-toggle="lightbox">
                                                                        <img src="<?= base_url() ?>/assets/uploads/thumbs/<?= $pr->image ?>"
                                                                             style="width:150px; z-index: 9999999999999;"
                                                                             class="img-thumbnail"/>
                                                                      </a>
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </td>
														<td><?= $this->erp->hrsd($pr->date) ?></td>
														<td><?= $pr->reference_no ?></td>
														<td><?= $pr->customer ?></td>
														<td><?= $pr->biller_name ?></td>
														<td class="text-right"><?= $p_qty ?> <br><?php  echo $unit_name;?></td>
														<td class="text-right"><?= $unit_price_amount ?></td>
														<td class="text-right"><?= $unit_cost_amount ?></td>
														<td class="text-right"><?= $profit ?></td>
														
													</tr>
													
													<?php 
														
												} ?>
												
												<tr class="active">
                                                    <td colspan="6" class="right"
                                                        style="font-weight:bold;"><?= lang("total") ?>
														<i class="fa fa-angle-double-right" aria-hidden="true"></i> 
													</td>
													<td class="text-right"><b><?= $this->erp->formatDecimal($total_quantity); ?></b></td>
													<td class="text-right"><b><?= $this->erp->formatMoney($total_price); ?></b></td>
													<td class="text-right"><b><?= $this->erp->formatMoney($total_cost); ?></b></td>
													<td class="text-right"><b><?= $this->erp->formatMoney($total_profit); ?></b></td>
												</tr>
												<?php 
													
													$total_quantity_by_warehouse += $total_quantity;
													$total_cost_by_warehouse += $total_cost;
													$total_price_by_warehouse += $total_price;
													$total_profit_by_warehouse += $total_profit;
										}
											
									} ?>
									
									<tr>
                                        <td class="right" colspan="6" style="font-weight:bold; color:green;"><span
                                                    style=" font-size:17px;"><?= lang("total") ?>
											<i class="fa fa-angle-double-right" aria-hidden="true"></i> 
											<?=$warehouse->warehouse?></span></td>
										<td class="text-right"><b><?= $this->erp->formatDecimal($total_quantity_by_warehouse); ?></b></td>
										<td class="text-right"><b><?= $this->erp->formatMoney($total_price_by_warehouse); ?></b></td>
										<td class="text-right"><b><?= $this->erp->formatMoney($total_cost_by_warehouse); ?></b></td>
										<td class="text-right"><b><?= $this->erp->formatMoney($total_profit_by_warehouse); ?></b></td>
									</tr>
									
									<?php
									$gqty +=$total_quantity_by_warehouse;
									$gprice +=$total_cost_by_warehouse;
									$gcost +=$total_price_by_warehouse;
									$gprofit +=$total_profit_by_warehouse;
								} 
							}
							?>
								
							
							<tr>
                                <td class="right" colspan="6"
                                    style="font-weight:bold; background-color: #428BCA;color:white;text-align:right;">
                                    <span style=" font-size:17px;"><?= lang("grand_total") ?></span></td>
								<td class="text-right" style='background-color: #428BCA;color:white;text-align:right;'><span style=" font-size:17px;"><b><?= $this->erp->formatDecimal($gqty); ?></b></span></td>
								<td class="text-right" style='background-color: #428BCA;color:white;text-align:right;'><span style=" font-size:17px;"><b><?= $this->erp->formatDecimal($gcost); ?></b></span></td>
								<td class="text-right" style='background-color: #428BCA;color:white;text-align:right;'><span style=" font-size:17px;"><b><?= $this->erp->formatDecimal($gprice); ?></b></span></td>
								<td class="text-right" style='background-color: #428BCA;color:white;text-align:right;'><span style=" font-size:17px;"><b><?= $this->erp->formatMoney($gprofit); ?></b></span></td>
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
                window.location.href = "<?= site_url('reports/inventory_profit/0/xls/'.$reference1.'/'.$wahouse_id1.'/'.$product_id1.'/'.$from_date1.'/'.$to_date1.'/'.$stockType1.'/'.$cate_id1.'/'.$biller1) ?>";
                return false;
        });
        $('#pdf').on('click', function (e) {
            e.preventDefault();
                window.location.href = "<?= site_url('reports/inventory/pdf/0'.$reference1.'/'.$wahouse_id1.'/'.$product_id1.'/'.trim($from_date1).'/'.trim($to_date1).'/'.$stockType1.'/'.$cate_id1.'/'.$biller1) ?>";
                return false;  
        });
	});
</script>

