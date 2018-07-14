<?php

	$v = "";
	
	if ($this->input->post('reference_no')) {
		$v .= "&reference_no=" . $this->input->post('reference_no');
	}
	if ($this->input->post('customer')) {
		$v .= "&customer=" . $this->input->post('customer');
	}
	if ($this->input->post('biller')) {
		$v .= "&biller=" . $this->input->post('biller');
	}
	if ($this->input->post('warehouse')) {
		$v .= "&warehouse=" . $this->input->post('warehouse');
	}
	if ($this->input->post('user')) {
		$v .= "&user=" . $this->input->post('user');
	}
	if ($this->input->post('serial')) {
		$v .= "&serial=" . $this->input->post('serial');
	}
	if ($this->input->post('start_date')) {
		$v .= "&start_date=" . $this->input->post('start_date');
	}
	if ($this->input->post('end_date')) {
		$v .= "&end_date=" . $this->input->post('end_date');
	}
	if (isset($biller_id)) {
		$v .= "&biller_id=" . $biller_id;
	}

?>

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
            };
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

<?php
    echo form_open('reports/salesDetail_actions', 'id="action-form"');
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-heart"></i><?= lang('sales_detail_report'); ?><?php
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

                    <?php echo form_open("reports/sales_detail"); ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>
                            </div>
                        </div>
                       <?php if($this->session->userdata('view_right')==0){?>
                        <div class="col-sm-3" style="display:none">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("created_by"); ?></label>
                                <?php
                                $us[""] = "";
                                foreach ($users as $user) {
									
                                    $us[$user->id] = $user->first_name . " " . $user->last_name;
                                }
                                echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
                                ?>
                            </div>
                        </div>
					   <?php }else{ ?>
					     <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("created_by"); ?></label>
                                <?php
                                $us[""] = "";
                                foreach ($users as $user) {
                                    $us[$user->id] = $user->first_name . " " . $user->last_name;
                                }
                                echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
                                ?>
                            </div>
                        </div>    
					   <?php } ?>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                <?php echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="customer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="biller"><?= lang("biller"); ?></label>
                                <?php
                                $bl[""] = "";
                                foreach ($billers as $biller) {
                                    $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                }
                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
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
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : $start_date), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : $end_date), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>
						<div class="col-sm-3">
                            <div class="form-group">
                                <label class="control-label" for="type"><?= lang("sale_type"); ?></label>
                                <?php
									$types = array(""=> "...", 1 => lang("sales"), 2 => lang("return"));
									echo form_dropdown('type', $types, isset($type) ? $type :'', 'class="form-control" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("type") . '"');
                                ?>
                            </div>
                        </div>
						
						<div class="col-sm-3">
							<div class="form-group">
								<?= lang("type", "type"); ?>
								<?php
								$sale_types = array('' => '...', 0 => 'SALE', 1 => 'POS');
								echo form_dropdown('types', $sale_types, (isset($_POST['types']) ? $_POST['types'] : ""), 'id="types" class="form-control select" placeholder="Please select Type" style="width:100%;"');
								?>
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
                    <table class="table table-bordered table-condensed table-striped">
						<thead>
							<tr class="info-head">
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="check"/>
								</th>
                                <th style="width:200px;" class="center"><?= lang("image"); ?></th>
								<th style="width:200px;" class="center"><?= lang("item"); ?></th>
								<th style="width:200px;" class="center"><?= lang("project"); ?></th>
								<th style="width:150px;"><?= lang("warehouse"); ?></th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th style="width:150px;"><?= lang("unit_cost"); ?></th>
                                <?php } ?>
								<th style="width:150px;"><?= lang("unit_price"); ?></th>
								<th style="width:150px;"><?= lang("tax"); ?></th>
								<th style="width:150px;"><?= lang("discount"); ?></th>
								<th style="width:150px;"><?= lang("quantity"); ?></th>
								<th style="width:150px;"><?= lang("unit"); ?></th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th style="width:150px;"><?= lang("total_costs"); ?></th>
                                <?php } ?>
                                <th style="width:150px;"><?= lang("total_price"); ?></th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th style="width:150px;"><?= lang("gross_mg"); ?></th>
                                <?php } ?>
							</tr>
						</thead>
                        <tbody>
						<?php 
							
							$warehouses_arr = array();
							$warehouses = $this->db->get("warehouses")->result();
							foreach($warehouses as $warehouse){
								$warehouses_arr[$warehouse->id] = $warehouse->name;
							}
							
							$g_total = 0;
							$g_order_discounts = 0;
							$g_amounts = 0;
							$g_total_costs = 0;
							$g_gross_margin = 0;
							$g_total_shipping = 0;
							$g_total_tax =0;
							$grand_totals = 0;
							if(count($sales) > 0){
								foreach($sales as $key => $sale){
								//$this->erp->print_arrays( $sale);
								$table_return_items = "erp_return_items"; 
								$table_sale_items 	= "erp_sale_items";
								
								$sql = "SELECT
										 erp_sale_items.id,
										 erp_sale_items.sale_id,
										 erp_sale_items.category_id,
										 erp_sale_items.product_id,
										 erp_products.image,
										 erp_sale_items.product_code,
										 erp_sale_items.product_name,
										 erp_sale_items.net_unit_price,
										 erp_sale_items.unit_price,
										 erp_sale_items.unit_cost,
										 erp_sale_items.quantity,
										 erp_sale_items.warehouse_id,
										 erp_sale_items.discount,
										 erp_sale_items.item_discount,
										 erp_sale_items.subtotal,
                                         erp_sale_items.item_tax,
                                         erp_sale_items.option_id,
										 erp_product_variants.qty_unit,
										 (CASE WHEN erp_product_variants.name = 0  THEN erp_product_variants.name ELSE erp_units.name END) as unit
									FROM ";
									
								$sales_detail = $this->db->query("{$sql}{$table_sale_items} AS erp_sale_items
											LEFT JOIN `erp_products` ON `erp_products`.`id` = `erp_sale_items`.`product_id`
                                            LEFT JOIN `erp_units` ON `erp_units`.`id` = `erp_products`.`unit`
											LEFT JOIN `erp_product_variants` ON `erp_sale_items`.`option_id` = `erp_product_variants`.`id`
											WHERE erp_sale_items.sale_id={$sale->id}  GROUP BY id")->result();
								//$this->erp->print_arrays( $sales_detail);				
								$sales_detail_returned = $this->db->query("{$sql}{$table_return_items} AS erp_sale_items
											LEFT JOIN `erp_products` ON `erp_products`.`id` = `erp_sale_items`.`product_id`
											LEFT JOIN `erp_units` ON `erp_units`.`id` = `erp_products`.`unit`
                                            LEFT JOIN `erp_product_variants` ON `erp_sale_items`.`option_id` = `erp_product_variants`.`id`
											WHERE erp_sale_items.return_id={$sale->id} GROUP BY id")->result();
							
								
							?>

                                    <?php

                                    $col = 10;
                                    $fcol = 9;
                                    $fcol2 = 10;
                                    if ($this->Owner || $this->Admin) {
                                        $col = 13;
                                        $fcol = 10;
                                        $fcol2 = 11;
                                    } else {
                                        if ($GP['products-cost']) {
                                            $col = 10;
                                            $fcol = 10;
                                            $fcol2 = 11;
                                        }
                                    }

                                    ?>
								<tr class="info-reference_no">
									<td><input type="checkbox" class="checkbox multi-select input-xs" name='val[]' value="<?php echo $sale->id ?>" /></td>
                                    <td colspan="<?= $col; ?>" style="font-size:18px;" class="left">
										<b style="<?php if($sale->type == 2){ echo "color:red"; } ?>">
											<?= $sale->reference_no; ?> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
											<?= $sale->customer ?> <i class="fa fa-angle-double-right" aria-hidden="true"></i>
											<?= date('d/M/Y h:i A',strtotime($sale->date)); ?>
											
										</b>
									</td>									
								</tr>
								<?php 
									$warehouse = "";
									$total_item_tax = 0;
									$total_discount = 0;
									$total_quantity = 0;
									$total_costs = 0;
									$total_gross_margin = 0;
									$total_amount = 0;
									$sub_total = 0;
									$total_amounts=0;
						            $amount=0;	
                                    $amounts=0;
									$total_overh = 0;
									
									$sales_by_gls = $this->db->query("SELECT
																	erp_gl_trans.sale_id,
																	erp_gl_trans.customer_id,
																	erp_gl_trans.biller_id,
																	erp_gl_trans.tran_date,
																	erp_gl_trans.reference_no,
																	erp_gl_trans.description,
																	erp_gl_trans.amount,
																	erp_gl_trans.narrative,
																	erp_gl_trans.tran_type,
																	erp_gl_trans.account_code
																	FROM
																		erp_gl_trans
																	INNER JOIN erp_sales ON erp_sales.id = erp_gl_trans.sale_id																	
																	WHERE erp_sales.id = {$sale->id}
																	AND sectionid = 50
																	GROUP BY reference_no
																	");

									if($sale->type == 1){
                                        foreach ($sales_detail as $sale_detail) {
										
											//$this->erp->print_arrays( $sale_detail);
											$unit = isset($sale_detail->variant) ? $sale_detail->variant : $sale_detail->unit;

                                            if ($sale_detail->option_id != 0) {
                                                $total_cost = ($sale_detail->unit_cost * $sale_detail->qty_unit) * $sale_detail->quantity;
												$unit_cost	= $sale_detail->unit_cost * $sale_detail->qty_unit;
                                            } else {
                                                $total_cost = $sale_detail->unit_cost * $sale_detail->quantity;
												$unit_cost	= $sale_detail->unit_cost;
                                            }
											//$this->erp->print_arrays( $sale_detail->unit_cost);
											$gross_margin = ($sale_detail->subtotal - $sale_detail->item_tax) - $total_cost;
											$sub_total = ($total_amount - $sale->order_discount) + $sale->order_tax + $total_item_tax + $sale->shipping;

											$total_discount += $sale_detail->item_discount;
											$total_quantity += $sale_detail->quantity;
											$total_costs += $total_cost;
											$total_gross_margin += $gross_margin;
											$total_amount += $sale_detail->subtotal - $sale_detail->item_tax;
											$total_amounts += $sale_detail->subtotal - $sale_detail->item_tax;
											$total_item_tax += $sale_detail->item_tax; 
										    $amount = $total_amount- $sale->order_discount + $sale->shipping;
											//$amounts +=	$amount;

								?>
										<tr>			
											<td></td>
                                            <td style="text-align:center !important;">
                                                <ul class="enlarge">
                                                    <li>
                                                        <img src="<?= base_url() ?>/assets/uploads/thumbs/<?= $sale_detail->image ?>"
                                                             class="img-responsive" style="width:50px;"/>
                                                        <span>
                                                          <a href="<?= base_url() ?>/assets/uploads/thumbs/<?= $sale_detail->image ?>"
                                                             data-toggle="lightbox">
                                                            <img src="<?= base_url() ?>/assets/uploads/thumbs/<?= $sale_detail->image ?>"
                                                                 style="width:150px; z-index: 9999999999999;"
                                                                 class="img-thumbnail"/>
                                                          </a>
                                                        </span>
                                                    </li>
                                                </ul>
                                            </td>
											<td>(<?= $sale_detail->product_name; ?>) <?= $sale_detail->product_code ?></td>
											
											<td><?= $sale->biller ?></td>
											<td class="center"><?= $warehouses_arr[$sale_detail->warehouse_id]; ?></td>
                                            <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                                <td class="right"><?= $this->erp->formatMoney($unit_cost); ?></td>
                                            <?php } ?>
                                            <?php if ($Owner || $Admin || $GP['products-price']) { ?>
                                                <td class="right"><?= $this->erp->formatMoney($sale_detail->unit_price); ?></td>
                                            <?php } ?>
											<td class="right">( <?= $this->erp->formatMoney($sale_detail->item_tax); ?> )</td>
											<td class="right">( <?= $this->erp->formatMoney($sale_detail->item_discount); ?> )</td>
											<td class="center"><?= $this->erp->formatQuantity($sale_detail->quantity); ?></td>
											<td class="center"><?= $unit; ?></td>
                                            <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                                <td class="right"><?= $this->erp->formatMoney($total_cost); ?></td>
                                            <?php } ?>
                                            <?php if ($Owner || $Admin || $GP['products-price']) { ?>
                                                <td class="right"><?= $this->erp->formatMoney($sale_detail->subtotal - $sale_detail->item_tax); ?></td>
                                            <?php } ?>
                                            <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                                <td class="right"><?= $this->erp->formatMoney($gross_margin); ?></td>
                                            <?php } ?>
										</tr>
								<?php 
										
										}
									
									$html = "";
									if($sales_by_gls->num_rows() > 0){
											$e_total = 0;
											$i_gross_margin = "";
										
										$html .="<tr style='font-weight:bold;'>
													<td></td>
													<td colspan='10'>".lang("OVERHEAD")."</td>
													<td class='text-right'></td>
													<td></td>
												 </tr>";
													 
										foreach($sales_by_gls->result() as $sales_by_gl){
											$e_total += $sales_by_gl->amount;
											$e_amount = $this->erp->formatMoney($sales_by_gl->amount);
											$d_gross_margin = ($total_gross_margin - $sale->order_discount + $sale->shipping) + (-1)* $e_total;
											$e_sub_total = "(".$this->erp->formatMoney(abs($e_total)).")";
											
											
											
											$html .="<tr>
														<td></td>
														<td>{$this->erp->hrld($sales_by_gl->tran_date)}</td>
														<td>{$sales_by_gl->reference_no}</td>
														<td colspan='7'>{$sales_by_gl->description}</td>
														<td class='text-right'>{$e_amount}</td>
														<td></td>
														<td></td>
													 </tr>";
										}
											$total_overh += $e_total;
											
											$html .="<tr>
														<td class='right' colspan='10'>".lang("subtotal")." : </td>
														<td class='text-right'>{$this->erp->formatMoney($e_total)}</td>
														<td></td>
														<td class='text-right'>{$e_sub_total}</td>
													</tr>";
													
											$html .="<tr>
														<td class='right' colspan='10'>".lang("total_gross_margin")." : </td>
														<td></td>
														<td class='text-right'></td>
														<td class='text-right'>{$this->erp->formatMoney($d_gross_margin)}</td>
													</tr>";
								}
									
									}else{									
										foreach($sales_detail_returned as $sale_detail_returned){										
											$unit = isset($sale_detail_returned->variant) ? $sale_detail_returned->variant : $sale_detail_returned->unit;
											
											if ($sale_detail_returned->option_id != 0) {
                                                $total_cost = ($sale_detail_returned->unit_cost * $sale_detail_returned->qty_unit) * $sale_detail_returned->quantity;
												$unit_cost	= $sale_detail_returned->unit_cost * $sale_detail_returned->qty_unit;
                                            } else {
												$total_cost = $sale_detail_returned->unit_cost * $sale_detail_returned->quantity;
												$unit_cost	= $sale_detail_returned->unit_cost;
                                            }
																						
											$gross_margin = ($sale_detail_returned->subtotal - $sale_detail_returned->item_tax) - $total_cost;
											$sub_total = ($total_amount - $sale->order_discount) + $sale->order_tax + $total_item_tax + $sale->shipping;
											
											$total_discount += $sale_detail_returned->item_discount;
											$total_quantity += $sale_detail_returned->quantity;
											$total_costs += $total_cost;
											$total_gross_margin += $gross_margin;
											$total_amount += $sale_detail_returned->subtotal - $sale_detail_returned->item_tax;
											$total_item_tax += $sale_detail_returned->item_tax;
										    $amount = $total_amount- $sale->order_discount + $sale->shipping;
											$amounts +=	$amount; 
									?>
                                            <tr>
											<td></td>
                                                <td style="text-align:center !important;">
                                                    <ul class="enlarge">
                                                        <li>
                                                            <img src="<?= base_url() ?>/assets/uploads/thumbs/<?= $sale_detail_returned->image ?>"
                                                                 class="img-responsive" style="width:50px;"/>
                                                            <span>
                                                          <a href="<?= base_url() ?>/assets/uploads/thumbs/<?= $sale_detail_returned->image ?>"
                                                             data-toggle="lightbox">
                                                            <img src="<?= base_url() ?>/assets/uploads/thumbs/<?= $sale_detail_returned->image ?>"
                                                                 style="width:150px; z-index: 9999999999999;"
                                                                 class="img-thumbnail"/>
                                                          </a>
                                                        </span>
                                                        </li>
                                                    </ul>
                                                </td>
											<td>(<?= $sale_detail_returned->product_name; ?>) <?= $sale_detail_returned->product_code ?></td>
											<td><?= $sale->biller ?></td>
											<td class="center"><?= $warehouses_arr[$sale_detail_returned->warehouse_id]; ?></td>
                                            <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                                <td class="right"><?= $this->erp->formatMoney($unit_cost); ?></td>
                                            <?php } ?>
											<td class="right"><?= $this->erp->formatMoney($sale_detail_returned->unit_price); ?></td>
											<td class="right">( <?= $this->erp->formatMoney($sale_detail_returned->item_tax); ?> )</td>
											<td class="right">( <?= $this->erp->formatMoney($sale_detail_returned->item_discount); ?> )</td>
											<td class="center"><?= $this->erp->formatQuantity($sale_detail_returned->quantity); ?></td>
                                            <td class=center"><?= $unit; ?></td>
                                            <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
											<td class="right"><?= $this->erp->formatMoney($total_cost); ?></td>
                                            <?php } ?>
											<td class="right"><?= $this->erp->formatMoney($sale_detail_returned->subtotal - $sale_detail_returned->item_tax); ?></td>
                                            <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                                <td class="right"><?= $this->erp->formatMoney($gross_margin); ?></td>
                                            <?php } ?>
										</tr>
									<?php }										
									}									
								?>
									
								<tr style="font-weight:bold;">
									<td></td>
                                    <td colspan="<?= $fcol; ?>" class="info-reference_no right"><?= lang("total") ?>:
                                    </td>
                                    <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
									<td class="right"><?= $this->erp->formatMoney($total_costs); ?></td>
                                    <?php } ?>
									<td class="right"><?= $this->erp->formatMoney($total_amount); ?></td>
                                    <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                        <td class="right"><?= $this->erp->formatMoney($total_gross_margin); ?></td>
                                    <?php } ?>
								</tr>
								
								<tr style="font-weight:bold;">
									<td></td>
                                    <td colspan="<?= $fcol; ?>"
                                        class="info-reference_no right"><?= lang("order_discount") ?>:
                                    </td>
                                    <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                        <td></td>
                                    <?php } ?>
									<td class="right"><?= "(".$this->erp->formatMoney($sale->order_discount).")"; ?></td>
                                    <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                        <td class="right"><?= "(".$this->erp->formatMoney($sale->order_discount).")"; ?></td>
                                    <?php } ?>
								</tr>
								<tr style="font-weight:bold;">
									<td></td>
                                    <td colspan="<?= $fcol; ?>" class="info-reference_no right"><?= lang("shipping") ?>
                                        :
                                    </td>
                                    <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
									<td></td>
                                    <?php } ?>
									<td class="right"><?= $this->erp->formatMoney($sale->shipping); ?></td>
                                    <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                        <td class="right"><?= $this->erp->formatMoney($sale->shipping); ?></td>
                                    <?php } ?>
								</tr>
								<!--<tr style="font-weight:bold;">
									<td></td>
									<td colspan="9" class="info-reference_no right"><?= lang("order_tax")?> :</td>
									<td></td>
									<td class="right"><?= $this->erp->formatMoney($sale->order_tax); ?></td>
									<td colspan="3"></td>
								</tr>-->
								<tr style="font-weight:bold;">
									<td></td>
                                    <td colspan="<?= $fcol; ?>" class="info-reference_no right"><?= lang("subtotal") ?>
                                        :
                                    </td>
                                    <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
									<td class="right"></td>
                                    <?php } ?>
									<td class="right"><?= $this->erp->formatMoney($amount); ?></td>
                                    <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                        <td class="right"><?=$this->erp->formatMoney($amount-$total_costs)?></td>
                                    <?php } ?>
								</tr>
								<tr style="font-weight:bold; display:none;">
									<td></td>
                                    <td colspan="<?= $fcol; ?>"
                                        class="info-reference_no right"><?= lang("total_amount") ?> :
                                    </td>
                                    <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
									<td></td>
                                    <?php } ?>
									<td class="right"><?= $this->erp->formatMoney($sub_total); ?></td>
                                    <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                        <td></td>
                                    <?php } ?>
								</tr>

                                    <?php
									
									echo $html;
									
									if($sale->type == 2 ){
                                        $g_order_discounts -= $sale->order_discount;
                                        $g_amounts -= $total_amount;
                                        $grand_totals -= $amount;
										
									}else{
                                        $g_order_discounts += $sale->order_discount;
                                        $g_amounts += $total_amount;
                                        $grand_totals += (float)($amount);
                                    }

                                    $g_total_costs += $total_costs;
									//$g_gross_margin = ($g_amounts) - $g_total_costs ;
									$g_gross_margin = ($g_amounts) - $g_total_costs ;
									$g_total_shipping += $sale->shipping;
									$g_total_tax += $sale->order_tax + $total_item_tax;								
									$g_totals = ($g_amounts + $g_total_shipping + $g_total_tax) - $g_order_discounts; 
								} 
								 
							}else{ ?>
								<tr>
									<td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
								</tr>
						<?php } ?>
                        </tbody>
                        <tfoot>
					
							<tr>
                                <th colspan="<?= $fcol2; ?>" style="color:#0586ff"
                                    class="right info-foot"><?= lang("total") ?>:
                                </th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
								<th class="right" style="color:#0586ff"><?= $this->erp->formatMoney($g_total_costs); ?></th>
                                <?php } ?>
								<th class="right" style="color:#0586ff"><?= $this->erp->formatMoney($g_amounts); ?></th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th class="right" style="color:#0586ff" title=" (Amount - Order Discount) - Total Cost "><?= $this->erp->formatMoney($g_amounts - $g_total_costs); ?></th>
                                <?php } ?>
							</tr>
							
							<tr>
                                <th colspan="<?= $fcol2 ?>" class="right info-foot"
                                    style="color:#0586ff"><?= lang("total_order_discount"); ?> :
                                </th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th></th>
                                <?php } ?>
								<th class="right" style="color:#0586ff"><?= "(".$this->erp->formatMoney($g_order_discounts).")"; ?></th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th class="right" style="color:#0586ff"><?= "(".$this->erp->formatMoney($g_order_discounts).")"; ?></th>
                                <?php } ?>
							</tr>
							
							<tr>
                                <th colspan="<?= $fcol2; ?>" class="right info-foot"
                                    style="color:#0586ff"><?= lang("total_shipping"); ?> :
                                </th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th></th>
                                <?php } ?>
								<th class="right" style="color:#0586ff"><?= $this->erp->formatMoney($g_total_shipping); ?></th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th class="right" style="color:#0586ff"><?= $this->erp->formatMoney($g_total_shipping); ?></th>
                                <?php } ?>
							</tr>
							
							<!--<tr style="display:none">
								<th colspan="10" class="right info-foot" style="color:#0586ff"><?= lang("total_tax"); ?> : </th>							
								<th></th>
								<th class="right" style="color:#0586ff"><?= $this->erp->formatMoney($g_total_tax); ?></th>
								<th></th>
							</tr>-->

                            <tr>
                                <th colspan="<?= $fcol2; ?>" class="right info-foot"
                                    style="color:#0586ff"><?= lang("grand_total"); ?> :
                                </th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th class="right"
                                        style="color:#0586ff"><?= $this->erp->formatMoney($g_total_costs); ?></th>
                                <?php } ?>
                                <th class="right"
                                    style="color:#0586ff"><?= $this->erp->formatMoney($g_amounts - $g_order_discounts + $g_total_shipping); ?></th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th class="right"
                                        style="color:#0586ff"><?= $this->erp->formatMoney($g_amounts - $g_total_costs - $g_order_discounts + $g_total_shipping); ?></th>
                                <?php } ?>
                            </tr>

                            <tr>
                                <th colspan="<?= $fcol2; ?>" class="right info-foot"
                                    style="color:#0586ff"><?= lang("total_overhead"); ?> :
                                </th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th class="right" style="color:#0586ff"><?= "(".$this->erp->formatMoney($total_overh).")"; ?></th>
                                <?php } ?>
								<th></th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th class="right" style="color:#0586ff"><?= "(".$this->erp->formatMoney($total_overh).")"; ?></th>
                                <?php } ?>
							</tr>
							
							<tr>
                                <th colspan="<?= $fcol2; ?>" style="color:#0586ff"
                                    class="right info-foot"><?= lang("total_gross_margin"); ?> :
                                </th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th class="right" style="color:#0586ff"></th>
                                <?php } ?>
								<th class="right" style="color:#0586ff"></th>
                                <?php if ($Owner || $Admin || $GP['products-cost']) { ?>
                                    <th class="right" style="color:#0586ff"><?= $this->erp->formatMoney($grand_totals-$g_total_costs - ($total_overh !=0 ? $total_overh : 0)); ?></th>
                                <?php } ?>
							</tr>
							
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
	table { 
		white-space: nowrap; 
		font-size:12px !important; 
		overflow-x: scroll; 
		width:100%;
		display:block;
		}
	table .info-head{
		
		text-align:center;
	}
	table .info-reference_no{
		
	}
	table .info-foot{
		text-transform: uppercase;
        /*font-weight: 100px;*/
	}
</style>