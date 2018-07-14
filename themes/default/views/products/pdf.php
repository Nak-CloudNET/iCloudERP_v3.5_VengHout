
       
        <div class="modal-header">
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
                <div class="col-xs-5">
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
                                    if ($this->session->userdata('show_cost')) {
                                        echo '<tr><td>' . $this->lang->line("product_cost") . '</td><td>' . $this->erp->formatMoney($product->cost) . '</td></tr>';
                                    }
                                    if ($this->session->userdata('show_price')) {
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
                            <h4 class="bold"><?= lang('custom_fields') ?></h4>
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
                                            echo '<tr><td>' . lang("pcf1") . '</td><td>' . $product->cf1 . '</td></tr>';
                                        }
                                        if ($product->cf2) {
                                            echo '<tr><td>' . lang("pcf2") . '</td><td>' . $product->cf2 . '</td></tr>';
                                        }
                                        if ($product->cf3) {
                                            echo '<tr><td>' . lang("pcf3") . '</td><td>' . $product->cf3 . '</td></tr>';
                                        }
                                        if ($product->cf4) {
                                            echo '<tr><td>' . lang("pcf4") . '</td><td>' . $product->cf4 . '</td></tr>';
                                        }
                                        if ($product->cf5) {
                                            echo '<tr><td>' . lang("pcf5") . '</td><td>' . $product->cf5 . '</td></tr>';
                                        }
                                        if ($product->cf6) {
                                            echo '<tr><td>' . lang("pcf6") . '</td><td>' . $product->cf6 . '</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>

                            <?php if ((!$Supplier || !$Customer) && !empty($warehouses) && $product->type == 'standard') { ?>
                            <h5 class="bold"><?= lang('warehouse_quantity') ?></h5>
                            <div class="table-responsive">
                                <table
                                class="table table-bordered table-striped table-condensed dfTable two-columns">
                                    <thead>
                                        <tr>
                                            <th><?= lang('warehouse_name') ?></th>
                                            <th><?= lang('quantity') . ' (' . lang('rack') . ')'; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($warehouses as $warehouse) {
                                            if ($warehouse->quantity != 0) {
                                                echo '<tr><td>' . $warehouse->name . ' (' . $warehouse->code . ')</td><td><strong>' . $this->erp->formatQuantity($warehouse->quantity) . '</strong>' . ($warehouse->rack ? ' (' . $warehouse->rack . ')' : '') . '</td></tr>';
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-xs-5">
                            <?php if ($product->type == 'combo') { ?>
                            <h4 class="bold"><?= lang('combo_items') ?></h4>
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
                                <h5 class="bold"><?= lang('product_variants_quantity'); ?></h5>
                                <div class="table-responsive">
                                    <table
                                    class="table table-bordered table-striped table-condensed dfTable">
                                        <thead>
                                            <tr>
                                            
                                                <th><?= lang('product_variant'); ?></th>
                                                <th><?= lang('quantity'); ?></th>
                                                <?php if ($Owner || $Admin) {
                                                    echo '<th>' . lang('cost') . '</th>';
                                                    echo '<th>' . lang('price') . '</th>';
                                                } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                            <?= $this->erp->convert_unit_2_string_by_unit($product->id,$product->quantity); ?>
                                             <?php/*
                                                foreach ($variants as $variant) {
                                            
                                            <tr>
                                                <td><?= $variant->name ?></td>
                                                <td>
                                                    <?php
                                                        $this->erp->unit_measure($variant->qty_unit, $product->quantity, $cal_qty);
                                                    ?>
                                                </td>
                                                
                                                    echo '<td>'. $this->erp->formatMoney($product->cost * $variant->qty_unit) .'</td>';
                                                    echo '<td>'.$this->erp->formatMoney($variant->price).'</td>';
                                                } ?>
                                            </tr>
                                            
                                                } */
                                            ?> 
                                            
                                        </tbody>
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
</div>
</div>
</div>
