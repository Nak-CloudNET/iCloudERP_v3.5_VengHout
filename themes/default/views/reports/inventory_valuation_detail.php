<style type="text/css" media="all">
    #PRData {
        white-space: nowrap;
        width: 100%;
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
                                <?= lang("type", "type"); ?>
                                  <?php $types = array(''=>'ALL','SALE' => lang('SALES'), 'PURCHASE' => lang('PURCHASES'),'TRANSFER' => lang('TRANSFER'),'SALES RETURN' => lang('SALES RETURN'),'USING STOCK' => lang('USING STOCK'),'EXPENSE' => lang('EXPENSE'),'DELIVERY' => lang('DELIVERY'),'ADJUSTMENT' => lang('ADJUSTMENT'),'CONVERT' => lang('CONVERT'), 'OPENING QUANTITY' => lang('OPENING QUANTITY'));
                                echo form_dropdown('type', $types, (isset($_POST['type']) ? $_POST['type'] : "") , 'class="form-control input-tip" id="type" data-placeholder="'. $this->lang->line("select type") .'"'); ?>
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
                                    $bill[$biller->id] =  $biller->company ; //$biller->code.' / '.$biller->name;
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
                                <th style="width: 120px"><?= lang("image") ?></th>
                                <th ><?= lang("type") ?></th>
                                <th><?= lang("date") ?></th>
                                <th ><?= lang("name") ?></th>
                                <th ><?= lang("reference") ?></th>
                                <th ><?= lang("biller") ?></th>
                                <th ><?= lang("qty") ?></th>
                                <th ><?= lang("cost") ?></th>
                                <th ><?= lang("on_hand") ?></th>
                                <th><?= lang("avg_cost") ?></th>
                                <th ><?= lang("asset_value") ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
							$gtt = 0;
							$gqty = 0;
							if(is_array($warehouses)){
							   foreach($warehouses as $warehouse){

							?>
							<tr>
								<td colspan="11" class="text-left" style="font-weight:bold; font-size:19px !important; color:green;">
									<?= lang("warehouse"); ?>
									<i class="fa fa-angle-double-right" aria-hidden="true"></i>
									&nbsp;&nbsp;<?=$warehouse->warehouse?>
								</td>
							</tr>

                                   <?php
                                   $categories = $this->reports_model->getCategoriesInventoryValuationByWarehouse($warehouse->warehouse_id, $cate_id1, $product_id1, $stockType1, $from_date1, $to_date1, $reference1, $biller1);
							$total_qoh_per_warehouse_cat = 0;
							$total_assetVal_per_warehouse_cat = 0;
							foreach($categories AS $category){ ?>
							<tr>
                                <td colspan="11" class="text-left" style="font-weight:bold; color:orange;">&nbsp;&nbsp;&nbsp;&nbsp;
									<?= lang("category"); ?>
									<i class="fa fa-angle-double-right" aria-hidden="true"></i>
									<?=$category->category_name?>
								</td>
							</tr>

                                <?php
							$total_qoh_per_warehouse = 0;
							$total_assetVal_per_warehouse = 0;
                                $products = $this->reports_model->getProductsInventoryValuationByWhCat($warehouse->warehouse_id, ($cate_id1 ? $cate_id1 : $category->category_id), $product_id1, $stockType1, $from_date1, $to_date1, $reference1, $biller1);
                                foreach ($products as $product) {
								if(!empty($product->product_id)){
							?>
							<tr>
								<td colspan="11" class="left" style="font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$product->product_code?$product->product_code:$product->product_id?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?=$product->product_name?> (<?=$product->un;?>)</td>

							</tr>

                                    <?php
							}
							$qty_on_hand = 0;
							$total_on_hand = 0;
							$total_asset_val = 0;
							$unit_name = "";
							$prDetails = $this->reports_model->getProductsInventoryValuationByProduct($warehouse->warehouse_id,($cate_id1?$cate_id1:$category->category_id),($product_id1?$product_id1:$product->product_id),$stockType1,$from_date1,$to_date1,$reference1,$biller1);
							foreach($prDetails as $pr)
							{
								$p_cost = 0;
								$p_qty = 0;

                                if ($pr->type == 'PURCHASE'
                                    || $pr->type == 'SALE RETURN'
                                    || $pr->type == 'OPENING QUANTITY')
								{
                                    $p_qty = $pr->quantity_balance_unit;
								}else if($pr->type == 'TRANSFER') {
                                    $p_qty = $pr->quantity_balance_unit;
								}
								else if( $pr->type == 'ADJUSTMENT') {
                                    $p_qty = $pr->quantity_balance_unit;
								}
								else if( $pr->type == 'USING STOCK') {
                                    $p_qty = $pr->quantity_balance_unit;
								}
								else if( $pr->type == 'RETURN USING STOCK') {
                                    $p_qty = $pr->quantity_balance_unit;
								}
								else if( $pr->type == 'CONVERT') {
                                    $p_qty = $pr->quantity_balance_unit;
								}
								else if( $pr->type == 'SALE' || $pr->type == 'DELIVERY')
								{
									if($pr->quantity > 0) {
                                        $p_qty = (-1) * $pr->quantity_balance_unit;
									}else {
                                        $p_qty = $pr->quantity_balance_unit;
									}
								}else {
                                    $p_qty = $pr->quantity_balance_unit;
								}
								$unit_name = $this->erp->convert_unit_2_string($pr->product_id,$p_qty);
								$qty_on_hand += $p_qty ;

                                $p_cost = $this->erp->formatDecimal($pr->total_cost);
								$avg_cost = $pr->avg_cost;
								$this->db->select("cost")->where("erp_products.id",$pr->product_id);
								$cost = $this->erp->formatDecimal($this->db->get_where("erp_products", array("id"=>$product->product_id),1)->row()->cost, 4);
								$asset_value = $cost * $qty_on_hand;
							?>
							<tr>
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
                                <td><?= $pr->tran_type ?></td>
                                <td><?= $this->erp->hrsd($pr->tran_date) ?></td>
								<td><?= $pr->product_name ?></td>
								<td><?= $pr->reference_no ?></td>
                                <td><?= $pr->biller_company ? $pr->biller_company : $pr->biller_name ?></td>
								<td class="text-right"><?= $this->erp->formatQuantity($p_qty) ?> <br><?php  echo $unit_name;?></td>
								<td class="text-right"><?= $p_cost ?></td>
								<td class="text-right"><?= $this->erp->formatQuantity($qty_on_hand) ?></td>
								<td class="text-right"><?= $cost ?></td>
								<td class="text-right"><?= $this->erp->formatMoney($asset_value) ?></td>
							</tr>
                                <?php
                                $total_on_hand = $qty_on_hand;
								$total_asset_val +=$asset_value;
							} ?>

							<tr class="active">
                                <td colspan="8" class="right" style="font-weight:bold;"><?= lang("total") ?>
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i>
								</td>
                                <td class="text-right"><b><?= $this->erp->formatQuantity($total_on_hand); ?></b></td>
								<td></td>
								<td class="text-right"><b><?= $this->erp->formatMoney($total_asset_val); ?></b></td>
							</tr>
                                    <?php
								$total_qoh_per_warehouse += $total_on_hand;
								$total_assetVal_per_warehouse += $total_asset_val;
                                }
                                ?>
							<tr>
                                <td class="right" colspan="8"
                                    style="font-weight:bold; color:orange; "><?= lang("total") ?>
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i>
									<?=$category->category_name?></td>
                                <td class="text-right">
                                    <b><?= $this->erp->formatQuantity($total_qoh_per_warehouse); ?></b></td>
								<td></td>
								<td class="text-right"><b><?= $this->erp->formatMoney($total_assetVal_per_warehouse); ?></b></td>
                            </tr>
							<?php

								$total_qoh_per_warehouse_cat +=$total_qoh_per_warehouse;
								$total_assetVal_per_warehouse_cat +=$total_assetVal_per_warehouse;
							} ?>

							<tr>
                                <td class="right" colspan="8" style="font-weight:bold; color:green;"><span
                                            style=" font-size:17px;"><?= lang("total") ?>
                                        <i class="fa fa-angle-double-right" aria-hidden="true"></i>
									<?=$warehouse->warehouse?></span></td>
                                <td class="text-right">
                                    <b><?= $this->erp->formatQuantity($total_qoh_per_warehouse_cat); ?></b></td>
								<td></td>
								<td class="text-right"><b><?= $this->erp->formatMoney($total_assetVal_per_warehouse_cat); ?></b></td>
							</tr>

                                   <?php
							$gtt +=$total_qoh_per_warehouse_cat;
							$gqty +=$total_assetVal_per_warehouse_cat;
							}
							}
							?>
								<tr>
                                    <td class="right" colspan="8"
                                        style="font-weight:bold; background-color: #428BCA;color:white;text-align:right;">
                                        <span style=" font-size:17px;"><?= lang("grand_total") ?></span></td>
                                    <td class="text-right"
                                        style='background-color: #428BCA;color:white;text-align:right;'><span
                                                style=" font-size:17px;"><b><?= $this->erp->formatQuantity($gtt); ?></b></span>
                                    </td>
								<td style='background-color: #428BCA;color:white;text-align:right;'></td>
								<td class="text-right" style='background-color: #428BCA;color:white;text-align:right;'><span style=" font-size:17px;"><b><?= $this->erp->formatMoney($gqty); ?></b></span></td>
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
    $(document).ready(function () {

        $('body').on('click', '#multi_adjust', function () {
            if ($('.checkbox').is(":checked") === false) {
                alert('Please select at least one.');
                return false;
            }
            var arrItems = [];
            $('.checkbox').each(function (i) {
                if ($(this).is(":checked")) {
                    if (this.value != "") {
                        arrItems[i] = $(this).val();
                    }
                }
            });
            $('#myModal').modal({remote: '<?=base_url('products/multi_adjustment');?>?data=' + arrItems + ''});
            $('#myModal').modal('show');
        });
        $('#excel').on('click', function (e) {

            e.preventDefault();
            window.location.href = "<?= site_url('reports/inventory/0/xls/' . $reference1 . '/' . $wahouse_id1 . '/' . $product_id1 . '/' . $from_date1 . '/' . $to_date1 . '/' . $stockType1 . '/' . $cate_id1 . '/' . $biller1) ?>";
            return false;
        });
        $('#pdf').on('click', function (e) {

            e.preventDefault();
            window.location.href = "<?= site_url('reports/inventory/xls/0/' . $reference1 . '/' . $wahouse_id1 . '/' . $product_id1 . '/' . $from_date1 . '/' . $to_date1 . '/' . $stockType1 . '/' . $cate_id1 . '/' . $biller1) ?>";
            return false;
        });
    });
</script>

