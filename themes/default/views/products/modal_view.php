<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= $product->name; ?></h4>
        </div>
        <div class="modal-body">
			<div class="row">
                <div class="col-xs-5">
                    <img id="pr-image" src="<?= base_url() ?>assets/uploads/<?= $product->image ?>"
                    alt="<?= $product->name ?>" class="img-responsive img-thumbnail"/>

                    <div id="multiimages" class="padding10">
                        <?php if (!empty($images)) {
                            echo '<a class="img-thumbnail change_img" href="' . base_url() . 'assets/uploads/' . $product->image . '" style="margin-right:5px;"><img class="img-responsive" src="' . base_url() . 'assets/uploads/thumbs/' . $product->image . '" alt="' . $product->image . '" style="width:' . $Settings->twidth . 'px; height:' . $Settings->theight . 'px;" /></a>';
                            foreach ($images as $ph) {
                                echo '<div class="gallery-image"><a class="img-thumbnail change_img" href="' . base_url() . 'assets/uploads/' . $ph->photo . '" style="margin-right:5px;"><img class="img-responsive" src="' . base_url() . 'assets/uploads/thumbs/' . $ph->photo . '" alt="' . $ph->photo . '" style="width:' . $Settings->twidth . 'px; height:' . $Settings->theight . 'px;" /></a>';
                                if ($Owner || $Admin || $GP['products-edit']) {
                                    //echo '<a href="'.$ph->id.'" class="delimg" data-item-id="'.$ph->id.'"><i class="fa fa-times"></i></a>';
									echo '<button class="delimg" id="'.$ph->id.'"><i class="fa fa-times"></i></button>';
                                }
                                echo '</div>';
                            }
                        }
                        ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="col-xs-7">
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped dfTable table-right-left">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="background-color:#FFF;"></td>
                                </tr>
                                <?php
                                foreach ($supplier as $supp) {
                                    ?>
                                <tr>
                                    <td><?= lang("Supplier"); ?></td>
                                    <td><?=$supp->name?></td>
                                </tr>
                                    <?php
                                }
                                ?>
                                <tr>
                                    <td style="width:30%;"><?= lang("barcode_qrcode"); ?></td>
                                    <td style="width:70%;"><?= $barcode ?>
                                        <?php $this->erp->qrcode('link', urlencode(site_url('products/view/' . $product->id)), 1); ?>
                                        <img
                                        src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                                        alt="<?= $product->name ?>" class="pull-right"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= lang("product_type"); ?></td>
                                    <td><?= lang($product->type); ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang("product_name"); ?></td>
                                    <td><?= $product->name; ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang("product_code"); ?></td>
                                    <td><?= $product->code; ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang("category"); ?></td>
                                    <td><?= $category->name; ?></td>
                                </tr>
                                <?php if ($product->subcategory_id) { ?>
                                <tr>
                                    <td><?= lang("subcategory"); ?></td>
                                    <td><?= $subcategory->name; ?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td><?= lang("product_unit"); ?></td>
                                    <td><?= $product->unit; ?></td>
                                </tr>
                                <?php if ($Owner || $Admin) {
                                    echo '<tr><td>' . $this->lang->line("product_cost") . '</td><td>' . $this->erp->formatMoney($product->cost) . '</td></tr>';
                                    echo '<tr><td>' . $this->lang->line("product_price") . '</td><td>' . $this->erp->formatMoney($product->price) . '</td></tr>';
                                } else {
                                    if ($GP['products-cost']) {
                                        echo '<tr><td>' . $this->lang->line("product_cost") . '</td><td>' . $this->erp->formatMoney($product->cost) . '</td></tr>';
                                    }
                                    if ($GP['products-price']) {
                                        echo '<tr><td>' . $this->lang->line("product_price") . '</td><td>' . $this->erp->formatMoney($product->price) . '</td></tr>';
                                    }
                                }
                                ?>

                                <?php if ($product->tax_rate) { ?>
                                <tr>
                                    <td><?= lang("tax_rate"); ?></td>
                                    <td><?= $tax_rate->name; ?></td>
                                </tr>
                                <tr>
                                    <td><?= lang("tax_method"); ?></td>
                                    <td><?= $product->tax_method == 0 ? lang('inclusive') : lang('exclusive'); ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($product->alert_quantity != 0) { ?>
                                <tr>
                                    <td><?= lang("alert_quantity"); ?></td>
                                    <td><?= $this->erp->formatQuantity($product->alert_quantity); ?></td>
                                </tr>
                                <?php } ?>
                                <?php if ($variants) { ?>
                                <tr>
                                    <td><?= lang("product_variants"); ?></td>
                                    <td><?php foreach ($variants as $variant) {
                                        echo '<span class="label label-primary">' . $variant->name . '</span> ';
                                    } ?></td>
                                </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-5">
                            <?php if ($product->cf1 || $product->cf2 || $product->cf3 || $product->cf4 || $product->cf5 || $product->cf6) { ?>
                            <h3 class="bold"><?= lang('custom_fields') ?></h3>
                            <div class="table-responsive">
                                <table
                                class="table table-bordered table-striped table-condensed dfTable two-columns">
									<thead>
										<tr>
											<th><?= lang('custom_field') ?></th>
											<th><?= lang('value') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($product->cf1) {
											echo '<tr><td>' . lang("home_type") . '</td><td>' . $product->cf1 . '</td></tr>';
										}
										if ($product->cf2) {
											echo '<tr><td>' . lang("address") . '</td><td>' . $product->cf2 . '</td></tr>';
										}
										if ($product->cf3) {
											echo '<tr><td>' . lang("home_no") . '</td><td>' . $product->cf3 . '</td></tr>';
										}
										if ($product->cf4) {
											echo '<tr><td>' . lang("street_no") . '</td><td>' . $product->cf4 . '</td></tr>';
										}
										if ($product->cf5) {
											echo '<tr><td>' . lang("size") . '</td><td>' . $product->cf5 . '</td></tr>';
										}
										if ($product->cf6) {
											echo '<tr><td>' . lang("height") . '</td><td>' . $product->cf6 . '</td></tr>';
										}
										?>
									</tbody>
								</table>
							</div>
                        <?php } ?>

							<?php if ((!$Supplier || !$Customer) && !empty($warehouses) && $product->type == 'standard') { ?>
							<h3 class="bold"><?= lang('warehouse_quantity') ?></h3>
							<div class="table-responsive">
								<table
								class="table table-bordered table-striped table-condensed dfTable two-columns">
									<thead>
										<tr>
											<th><?= lang('warehouse_name') ?></th>
											<th><?= lang('quantity') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($warehouses as $warehouse) {
											if ($warehouse->quantity != 0) {
												echo '<tr><td>' . $warehouse->name . ' (' . $warehouse->code . ')</td><td><strong>' . $this->erp->formatQuantity($warehouse->quantity) . '</strong>' . '</td></tr>';
											}
										} ?>
									</tbody>
								</table>
							</div>
							<?php } ?>
						</div>
						<div class="col-xs-7">
							<?php if ($product->type == 'combo') { ?>
							<h3 class="bold"><?= lang('combo_items') ?></h3>
							<div class="table-responsive">
								<table
								class="table table-bordered table-striped table-condensed dfTable two-columns">
									<thead>
										<tr>
											<th><?= lang('product_name') ?></th>
											<th><?= lang('quantity') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($combo_items as $combo_item) {
											echo '<tr><td>' . $combo_item->name . ' (' . $combo_item->code . ') </td><td>' . $this->erp->formatQuantity($combo_item->qty) . '</td></tr>';
										} ?>
									</tbody>
								</table>
							</div>
							<?php } ?>
							<?php if (!empty($options)) { ?>
								<h3 class="bold"><?= lang('product_variants_quantity'); ?></h3>
								<div class="table-responsive">
									<table
									class="table table-bordered table-striped table-condensed dfTable">
										<thead>
											<tr>
											
												<th><?= lang('product_variant'); ?></th>
												<th><?= lang('quantity'); ?></th>
                                                <?php if ($Owner || $Admin || $GP['products-cost']) {
													echo '<th>' . lang('cost') . '</th>';
                                                } ?>
                                                <?php if ($Owner || $Admin || $GP['products-price']) {
                                                    echo '<th>' . lang('price') . '</th>';
                                                } ?>
											</tr>
										</thead>
										<tbody>

                                        <?php

                                        if ($Owner || $Admin) {
                                            echo $this->erp->convert_unit_2_string_by_unit($product->id, $product->qty);
                                        } else {
                                            echo $this->erp->convert_unit_2_string_by_unit($product_user->id, $product_user->qty);
                                        }
                                        ?>

										</tbody>
									</table>
								</div>
							<?php } ?>

                            <?php if($ordered_products_qty) { ?>
                            <h3 class="bold"><?= lang('warehouse_quantity_order') ?></h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-condensed dfTable two-columns">
                                        <thead>
                                            <tr>
                                                <th><?= lang('reference_no') ?></th>
                                                <th><?= lang('warehouse_name') ?></th>
                                                <th><?= lang('quantity') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $tQTY = 0;
										
                                                foreach ($ordered_products_qty as $ordered_product_qty) {
                                            ?>
											
                                                    <tr>
                                                        <td><?= $ordered_product_qty->reference_no ?></td>
                                                        <td><?= $ordered_product_qty->name ?></td>
                                                        <td class="text-center">
															<?php if($ordered_product_qty -> piece!=0){
																	echo $this-> erp -> formatQuantity($ordered_product_qty -> piece);
																	 $tQTY += $ordered_product_qty->piece;
																}
																	else{
																		echo $this -> erp -> formatQuantity($ordered_product_qty -> qty);
																		 $tQTY += $ordered_product_qty->qty;
																		}
															?>
														</td>
													
                                                    </tr>
                                            <?php
                                               
                                                
                                                }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" align="right"><strong>Total:</strong></td>
                                                <td class="text-center"><strong><?= $this->erp->formatQuantity($tQTY) ?></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            <?php } ?>
                            
						</div>
					</div>
				</div>

				<div class="col-xs-12">

					<?= $product->details ? '<div class="panel panel-success"><div class="panel-heading">' . lang('product_details_for_invoice') . '</div><div class="panel-body">' . $product->details . '</div></div>' : ''; ?>
					<?= $product->product_details ? '<div class="panel panel-primary"><div class="panel-heading">' . lang('product_details') . '</div><div class="panel-body">' . $product->product_details . '</div></div>' : ''; ?>

				</div>
			</div>
<?php if (!$Supplier || !$Customer) { ?>
<div class="buttons">
    <div class="btn-group btn-group-justified">
    <?php if ($Owner || $Admin || $GP['products-print_barcodes']) { ?>
        <div class="btn-group">
            <a onclick="window.open('<?= site_url('products/single_barcode/' . $product->id) ?>', 'erp_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;"
                href="#" class="tip btn btn-primary" title="<?= lang('barcode') ?>">
                <i class="fa fa-print"></i>
                <span class="hidden-sm hidden-xs"><?= lang('print_barcode') ?></span>
            </a>
        </div>
        <div class="btn-group">
            <a onclick="window.open('<?= site_url('products/single_label/' . $product->id) ?>', 'erp_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;"
                href="#" class="tip btn btn-primary" title="<?= lang('label') ?>">
                <i class="fa fa-print"></i>
                <span class="hidden-sm hidden-xs"><?= lang('print_label') ?></span>
            </a>
        </div>
        <div class="btn-group">
            <a onclick="window.open('<?= site_url('products/single_label2/' . $product->id) ?>', 'erp_popup', 'width=900,height=600,menubar=yes,scrollbars=yes,status=no,resizable=yes,screenx=0,screeny=0'); return false;"
                href="#" class="tip btn btn-primary" title="<?= lang('label_printer') ?>">
                <i class="fa fa-print"></i>
                <span class="hidden-sm hidden-xs"><?= lang('label_printer') ?></span>
            </a>
        </div>
    <?php } ?>
    
    <?php if ($Owner || $Admin || $GP['products-export']) { ?>
        <div class="btn-group">
            <a href="<?= site_url('products/pdf/' . $product->id) ?>" class="tip btn btn-primary" title="<?= lang('pdf') ?>">
                <i class="fa fa-download"></i>
                <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
            </a>
        </div>
    <?php } ?>

	<?php if($Owner || $Admin || $GP['products-edit']) { ?>
    <div class="btn-group">
        <a href="<?= site_url('products/edit/' . $product->id) ?>" class="tip btn btn-warning tip" title="<?= lang('edit_product') ?>">
            <i class="fa fa-edit"></i>
            <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
        </a>
    </div>
	<?php } ?>
	<?php if($Owner || $Admin || $GP['products-delete']) { ?>
    <div class="btn-group">
        <a href="#" class="tip btn btn-danger bpo" title="<b><?= $this->lang->line("delete_product") ?></b>"
            data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= site_url('products/delete/' . $product->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
            data-html="true" data-placement="top">
            <i class="fa fa-trash-o"></i>
            <span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>
        </a>
    </div>
	<?php } ?>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.tip').tooltip();
    });
</script>
<?php } ?>
</div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.change_img').click(function(event) {
            event.preventDefault();
            var img_src = $(this).attr('href');
            $('#pr-image').attr('src', img_src);
            return false;
        });
		
		//Delete product photo without reload page
		$('.delimg').click(function() {
			var id = $(this).attr('id');

			$.ajax({
				url: site.base_url + 'products/delete_image/' + id,
				async: false,
				dataType: "data",
				success: function() {}
			});

			$(this).parents(".gallery-image").animate("fast").animate({
				opacity: "hide"
			}, "slow");
		});
    });
</script>
