<?php
if (!empty($variants)) {
    foreach ($variants as $variant) {
        $vars[] = addslashes($variant->name);
    }
} else {
    $vars = array();
}
$pQty = '';
foreach ($warehouses_products as $warehouses_product) {
    $pQty += $warehouses_product->quantity;
}

?>
<script type="text/javascript">
   $(document).on('click', '.deleteVariants', function(e){
			e.preventDefault();
            var id=$(this).attr('id');
			var row=$(this).closest("tr");
			var url="<?= site_url('products/deleteProductVariant') ?>/" + <?= $product->id ?>+"/"+id;
            $.ajax({
                type: "get",
                async: false,
                url: url,
                dataType: "json",
                success: function (data) {
					if(data){
						row.remove();
						$('#alertMessage').html('<div class="alert alert-success alert-dismissable">\n' +
                       ' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                        '<?=lang("variants_delete_sccess") ?>' +
                        '</div>');
					}else{
						 $('#alertMessage').html('<div class="alert alert-danger alert-dismissable">\n' +
                        ' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                        '<?=lang("item_has_been_sold") ?>' +
                        ' </div>');
					}
                    
                },
                error: function () {
                    $('#alertMessage').html('<div class="alert alert-danger alert-dismissable">\n' +
                        ' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+
                        '<?=lang("connection_timeout") ?>' +
                        ' </div>');
                }
            });
        });
    $(document).ready(function () {
        $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
            placeholder: "<?= lang('select_category_to_load') ?>", data: [
                {id: '', text: '<?= lang('select_category_to_load') ?>'}
            ]
        });
     
        $('#brand').change(function () {
            var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url( 'products/getCategories') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
                            $("#category").select2("destroy").empty();
                            var newOptions = '';
                            var option = '<option></option>';
                            $("#category").select2('destroy').append(option).select2();
                            $.each(scdata, function(i, item) {
                                newOptions = '<option value="'+ item.id +'">'+ item.text +'</option>';
                                $("#category").select2('destroy').append(newOptions ).select2();
                            });
                        }
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            }
            $('#modal-loading').hide();
        });
        $('#category').change(function () {
            var v = $(this).val();
            $('#modal-loading').show();
            if (v) {
                $.ajax({
                    type: "get",
                    async: false,
                    url: "<?= site_url('products/getSubCategories') ?>/" + v,
                    dataType: "json",
                    success: function (scdata) {
                        if (scdata != null) {
                            $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: scdata
                            });
                        }else{
                            $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                                placeholder: "<?= lang('select_category_to_load') ?>",
                                data: 'not found'
                            });
                        }
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_error') ?>');
                        $('#modal-loading').hide();
                    }
                });
            } else {
                $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_category_to_load') ?>").select2({
                    placeholder: "<?= lang('select_category_to_load') ?>",
                    data: [{id: '', text: '<?= lang('select_category_to_load') ?>'}]
                });
            }
            $('#modal-loading').hide();
        });
        $('#code').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-edit"></i><?= lang('edit_product'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('update_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("products/edit/" . $product->id, $attrib)
                ?>
                <div class="col-md-5">
                    <div class="form-group">
                        <?= lang("product_type", "type") ?>
                        <?php
                        $opts = array('standard' => lang('standard'), 'combo' => lang('combo'), 'digital' => lang('digital'), 'service' => lang('service'));
                        echo form_dropdown('type', $opts, (isset($_POST['type']) ? $_POST['type'] : ($product ? $product->type : '')), 'class="form-control ptype" id="type" required="required"');
                        ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("product_name", "name") ?>
                        <?= form_input('name', (isset($_POST['name']) ? $_POST['name'] : ($product ? $product->name : '')), 'class="form-control" id="name" required="required"'); ?>
                    </div>
                    <div class="form-group all">
                        <?= lang("product_name_kh", "name_kh") ?>
                        <?= form_input('name_kh', (isset($_POST['name_kh']) ? $_POST['name_kh'] : ($product ? $product->name_kh : '')), 'class="form-control" id="name_other" '); ?>
                    </div>
                    <?php
                    if(empty($product_item)){
                    ?>
                        <div class="form-group all">
                            <?= lang("product_code", "code") ?>
                            <?= form_input('code', (isset($_POST['code']) ? $_POST['code'] : ($product ? $product->code : '')), 'class="form-control" id="code"  required="required"') ?>
                            <span class="help-block"><?= lang('you_scan_your_barcode_too') ?></span>
                        </div>
                    <?php 
                    }else{
                    ?>
                        <div class="form-group all">
                            <?= lang("product_code", "code") ?>
                            <?= form_input('code', (isset($_POST['code']) ? $_POST['code'] : ($product ? $product->code : '')), 'class="form-control pcode" id="code"  required="required"') ?>
                            <span class="help-block"><?= lang('you_scan_your_barcode_too') ?></span>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="form-group all">
                        <?= lang("barcode_symbology", "barcode_symbology") ?>
                        <?php
                        $bs = array('code25' => 'Code25', 'code39' => 'Code39', 'code128' => 'Code128', 'ean8' => 'EAN8', 'ean13' => 'EAN13', 'upca ' => 'UPC-A', 'upce' => 'UPC-E');
                        echo form_dropdown('barcode_symbology', $bs, (isset($_POST['barcode_symbology']) ? $_POST['barcode_symbology'] : ($product ? $product->barcode_symbology : 'code128')), 'class="form-control select" id="barcode_symbology" required="required" style="width:100%;"');
                        ?>

                    </div>
                    <?php
                    if(empty($product_item)){
                    ?>
                        <div class="form-group all">
                            <?= lang("category", "category") ?>
                            <?php
                            $cat[''] = "";
                            foreach ($categories as $category) {
                                $cat[$category->id] = $category->name;
                            }
                            echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ($product ? $product->category_id : '')), 'class="form-control" id="category" placeholder="' . lang("select") . " " . lang("category") . '" required="required" style="width:100%"')
                            ?>
                        </div>
                   <?php 
                    }else{
                    ?>
                        <div class="form-group all">
                            <?= lang("category", "category") ?>
                            <?php
                            $cat[''] = "";
                            foreach ($categories as $category) {
                                $cat[$category->id] = $category->name;
                            }
                            echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ($product ? $product->category_id : '')), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" required="required" style="width:100%"')
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if(empty($product_item)){
                    ?>
                        <div class="form-group all">
                            <?= lang("subcategory", "subcategory") ?>
                            <div class="controls" id="subcat_data"> <?php
                                echo form_input('subcategory', ($product ? $product->subcategory_id : ''), 'class="form-control" id="subcategory"  placeholder="' . lang("select_category_to_load") . '"');
                                ?>
                            </div>
                        </div>
                    <?php
                     }else{
                    ?>
                        <div class="form-group all">
                            <?= lang("subcategory", "subcategory") ?>
                            <div class="controls" id="subcat_data"> <?php
                                echo form_input('subcategory', ($product ? $product->subcategory_id : ''), 'class="form-control scate" id="subcategory"  placeholder="' . lang("select_category_to_load") . '"');
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if(empty($product_item)){
                    ?>
                        <div class="form-group all">
                            <label class="control-label" for="unit"><?= lang("product_unit") ?></label>
                            <?php
                            $ut[""] = "";                       
                            foreach($unit as $uts){
                                $ut[$uts->id] = $uts->name;
                            }
                            echo form_dropdown('unit', $ut, (isset($_POST['unit']) ? $_POST['unit'] : ($product ? $this->erp->formatDecimal($product->unit) : '')), 'class="form-control" id="unit" required="required" placeholder="'.lang('select_units').'" style="width:100%;"');
                            ?>
                        </div>
                    <?php
                    }else{
                    ?>
                        <div class="form-group all">
                            <label class="control-label" for="unit"><?= lang("product_unit") ?></label>
                            <?php
                            $ut[""] = "";                       
                            foreach($unit as $uts){
                                $ut[$uts->id] = $uts->name;
                            }
                            echo form_dropdown('unit', $ut, (isset($_POST['unit']) ? $_POST['unit'] : ($product ? $this->erp->formatDecimal($product->unit) : '')), 'class="form-control select" id="unit" required="required" placeholder="'.lang('select_units').'" style="width:100%;"');
                            ?>
                        </div>
                    <?php
                    }
                    ?>
                    <?php  if(empty($product_item)){ ?>
                        <div class="form-group standard">
                            <?= lang("product_cost", "cost") ?>
                            <?= form_input('cost', (isset($_POST['cost']) ? $_POST['cost'] : ($product ? $this->erp->formatDecimal($product->cost) : '')), 'class="form-control tip" id="costs" required="required"') ?>
                        </div>
                    <?php }else{ ?>
                        <div class="form-group standard">
                            <?= lang("product_cost", "cost") ?>
                            <?= form_input('cost', (isset($_POST['cost']) ? $_POST['cost'] : ($product ? $this->erp->formatDecimal($product->cost) : '')), 'class="form-control tip pcost" id="costs" required="required"') ?>
                        </div>
                    <?php  } ?>
                    
                    <?php if(empty($product_item)) { ?>
						<div class="form-group all">
							<?= lang("product_price", "price") ?>
							
							<?= form_input('price', (isset($_POST['price']) ? $_POST['price'] : ($product ? $this->erp->formatDecimal($product->price) : '')), 'class="form-control tip" id="prices" required="required"') ?>
						</div>
                    <?php }else { ?>
						<div class="form-group all">
							<?= lang("product_price", "price") ?>
							
							<?= form_input('price', (isset($_POST['price']) ? $_POST['price'] : ($product ? $this->erp->formatDecimal($product->price) : '')), 'class="form-control tip pprice" id="prices" required="required"') ?>
						</div>
                    <?php } ?>

                    <div class="form-group">
                        <label class="control-label" for="currency"><?= lang("default_currency"); ?></label>
                        <div class="controls"> 
                            <?php
                                foreach ($currencies as $currency) {
                                    $cu[$currency->code] = $currency->name;
                                }
                                echo form_dropdown('currency', $cu, $product->currentcy_code, 'class="form-control tip pcurrency" id="currency" style="width:100%;"');
                            ?>
                        </div>
                    </div>
                    <!--<div class="form-group promotion">
                        <input type="checkbox" class="checkbox" value="1" name="promotion" class="ppromo" id="promotion" <?/*= $this->input->post('promotion') ? 'checked="checked"' : ''; */?>>
                        <label for="promotion" class="padding05">
                            <?/*= lang('promotion'); */?>
                        </label>
                    </div>-->

                    <div id="promo"<?= $product->promotion ? '' : ' style="display:none;"'; ?>>
                        <div class="well well-sm">
                            <div class="form-group">
                                <?= lang('discount_price', 'promo_price'); ?>
                                <?= form_input('promo_price', set_value('promo_price', $product->promo_price ? $product->promo_price : ''), 'class="form-control tip" id="promo_price"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('start_date', 'start_date'); ?>
                                <?= form_input('start_date', set_value('start_date', $product->start_date ? $this->erp->hrsd($product->start_date) : ''), 'class="form-control tip date" id="start_date"'); ?>
                            </div>
                            <div class="form-group">
                                <?= lang('end_date', 'end_date'); ?>
                                <?= form_input('end_date', set_value('end_date', $product->end_date ? $this->erp->hrsd($product->end_date) : ''), 'class="form-control tip date" id="end_date"'); ?>
                            </div>
                        </div>
                    </div>

                    
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group all">
                            <?= lang("product_tax", "tax_rate") ?>
                            <?php
                            $tr[""] = "";
                            foreach ($tax_rates as $tax) {
                                $tr[$tax->id] = $tax->name;
                            }
                            echo form_dropdown('tax_rate', $tr, (isset($_POST['tax_rate']) ? $_POST['tax_rate'] : ($product ? $product->tax_rate : $Settings->default_tax_rate)), 'class="form-control select" id="tax_rate" placeholder="' . lang("select") . ' ' . lang("product_tax") . '" style="width:100%"')
                            ?>
                        </div>
                        <div class="form-group all">
                            <?= lang("tax_method", "tax_method") ?>
                            <?php
                            $tm = array('0' => lang('inclusive'), '1' => lang('exclusive'));
                            echo form_dropdown('tax_method', $tm, (isset($_POST['tax_method']) ? $_POST['tax_method'] : ($product ? $product->tax_method : '')), 'class="form-control select" id="tax_method" placeholder="' . lang("select") . ' ' . lang("tax_method") . '" style="width:100%"')
                            ?>
                        </div>
                    <?php } ?>
                    <div class="form-group standard">
                        <?= lang("alert_quantity", "alert_quantity") ?>
                        <div
                            class="input-group"> <?= form_input('alert_quantity', (isset($_POST['alert_quantity']) ? $_POST['alert_quantity'] : ($product ? $this->erp->formatDecimal($product->alert_quantity) : '')), 'class="form-control tip palert" id="alert_quantity"') ?>
                            <span class="input-group-addon">
                            <input type="checkbox" name="track_quantity" id="inlineCheckbox1"
                                   value="1" <?= ($product ? (isset($product->track_quantity) ? 'checked="checked"' : '') : 'checked="checked"') ?>>
                        </span>
                        </div>
                    </div>
					
					<!--
                    <div class="form-group standard">
                        <?= lang("supplier", "supplier") ?>
                        <button type="button" class="btn btn-primary btn-xs supplier" id="addSupplier"><i class="fa fa-plus"></i>
                        </button>
                        <div class="row" id="supplier-con">
                            <div class="col-md-8 col-sm-8 col-xs-8">
                                <?php
                                echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ''), 'class="form-control psupplier' . ($product ? '' : 'suppliers') . '" id="supplier1" placeholder="' . lang("select") . ' ' . lang("supplier") . '" style="width:100%;"')
                                ?></div>
                            <div
                                class="col-md-4 col-sm-4 col-xs-4"><?= form_input('supplier_price', (isset($_POST['supplier_price']) ? $_POST['supplier_price'] : ""), 'class="form-control tip psupplierprice" id="supplier_price" placeholder="' . lang('supplier_price') . '"') ?></div>
                        </div>
                        <div id="ex-suppliers"></div>
                    </div> -->

                    <div class="form-group all product_image">
                        <?= lang("product_image", "product_image") ?>
                        <input id="product_image" type="file" name="product_image" data-show-upload="false"
                               data-show-preview="false" accept="image/*" class="form-control file pimg">
                    </div>

                    <div class="form-group all product_gallery_images">
                        <?= lang("product_gallery_images", "images") ?>
                        <input id="images" type="file" name="userfile[]" multiple="true" data-show-upload="false"
                               data-show-preview="false" class="form-control file pgallery" accept="image/*">
                    </div>
                    <div id="img-details"></div>
                </div>
                
                <div class="col-md-6 col-md-offset-1">
                    <div class="standard">
                        
                        <div class="clearfix"></div>
                        <div id="attrs"></div>

                        <div class="well well-sm">                            
                            <?php
                            if ($product_variants) { ?>
                                <h3 class="bold"><?=lang('update_variants');?></h3>
                                <div id="alertMessage"></div>
                                <table class="table table-bordered table-condensed table-striped" style="margin-top: 10px;">
                                <thead>
                                <tr class="active">
                                    <th class="col-xs-4"><?= lang('name') ?></th>
                                    <th class="col-xs-4"><?= lang('quantity_unit') ?></th>
                                    <th class="col-xs-4"><?= lang('price') ?></th>
                                    <th class="col-xs-4"><i class="fa fa-times attr-remove-all"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($product_variants as $pv) {
                               
                                     echo '<tr class="items">
                                                <td class="col-xs-3">
                                                    <input type="hidden" name="variant_id_' . $pv->id . '" value="' . $pv->id . '" ><input type="text" name="variant_name_' . $pv->id . '" value="' . $pv->name . '" class="form-control" readonly>
                                                 </td>
                                                <td class="qty_unit text-right col-xs-2">
                                                    <input type="text" name="variant_qty_unit_' . $pv->id . '" value="' . $pv->qty_unit . '" class="form-control qty_unit" '.($product_item?"readonly":"").' >
                                                </td>
                                                <td class="price text-right col-xs-2">
                                                    <input type="text" name="variant_price_' . $pv->id . '" value="' . $pv->price . '" class="form-control" >
                                                </td>
                                                <td class="price text-right col-xs-2">
                                                    <div class="input-group-addon del" style="padding: 2px 5px;">
                                                        <a href="#" class="deleteVariants" id="' . $pv->id . '" onclick="return false;"><i  class="fa fa-times"></i></a>
                                                    </div>
                                                </td>
                                           </tr>';
                                }
                                ?>
                                </tbody>
                                </table>
                                <?php
                            }
                            ?>
                            <div class="form-group add_more_variants">
                                <input type="checkbox" class="checkbox" name="attributes" id="attributes" <?= $this->input->post('attributes') ? 'checked="checked"' : ''; ?> <?php echo $product_item?"disabled":""?> >
                                <label for="attributes" class="padding05"><?= lang('add_more_variants'); ?></label>
                                <?= lang('eg_sizes_colors'); ?>
                            </div>

                            <div id="attr-con" <?= $this->input->post('attributes') ? '' : 'style="display:none;"'; ?>>
                            <div class="form-group" id="ui" style="margin-bottom: 0;">
                                <div class="input-group">
                                    <?php
                                    echo form_input('attributesInput', '', 'class="form-control select-tags" id="attributesInput" placeholder="' . $this->lang->line("enter_attributes") . '"'); ?>
                                    <div class="input-group-addon" style="padding: 2px 5px;">
                                        <a href="#" id="addAttributes">
                                            <i class="fa fa-2x fa-plus-circle" id="addIcon"></i>
                                        </a>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                            </div>
                            <div class="table-responsive">
                                <table id="attrTable" class="table table-bordered table-condensed table-striped" style="margin-bottom: 0; margin-top: 10px;">
                                    <thead>
                                    <tr class="active">
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('quantity_unit') ?></th>
                                        <th><?= lang('price') ?></th>
                                        <th><i class="fa fa-times attr-remove-all"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody><?php
                                    if ($this->input->post('attributes')) {
                                        $a = sizeof($_POST['attr_name']);
                                        for ($r = 0; $r <= $a; $r++) {
                                            if (isset($_POST['attr_name'][$r]) && (isset($_POST['attr_warehouse'][$r]) || isset($_POST['attr_quantity_unit'][$r]) || isset($_POST['attr_quantity'][$r]))) {
                                                echo '<tr class="attr">
                                                <td><input type="hidden" name="attr_name[]" value="' . $_POST['attr_name'][$r] . '"><span>' . $_POST['attr_name'][$r] . '</span></td>
                                                <!--<td class="code text-center"><input type="hidden" name="attr_warehouse[]" value="' . $_POST['attr_warehouse'][$r] . '"><input type="hidden" name="attr_wh_name[]" value="' . $_POST['attr_wh_name'][$r] . '"><span>' . $_POST['attr_wh_name'][$r] . '</span></td>-->
                                                <td class="quantity text-center"><input type="hidden" name="attr_quantity[]" value="' . $_POST['attr_quantity'][$r] . '"><span>' . $_POST['attr_quantity'][$r] . '</span></td>
                                                <!--<td class="quantity text-center"><input type="hidden" name="attr_quantity[]" value="' . $_POST['attr_quantity'][$r] . '"><span>' . $_POST['attr_quantity'][$r] . '</span></td>  
                                                <td class="cost text-right"><input type="hidden" name="attr_cost[]" value="' . $_POST['attr_cost'][$r] . '"><span>' . $_POST['attr_cost'][$r] . '</span></td>-->        
                                                <td class="price text-right"><input type="hidden" name="attr_price[]" value="' . $_POST['attr_price'][$r] . '"><span>' . $_POST['attr_price'][$r] . '</span></span></td>
                                                <td class="text-center"><i class="fa fa-times delAttr"></i></td>
                                                </tr>';
                                            }
                                        }
                                    }
                                    ?></tbody>
                                </table>
                            </div>
                        </div>
                        </div>

                    </div>
                    
                    <div class="combo" style="display:none;">

                        <div class="form-group">
                            <?= lang("add_product", "add_item") . ' (' . lang('not_with_variants') . ')'; ?>
                            <?php echo form_input('add_item', '', 'class="form-control ttip" id="add_item" data-placement="top" data-trigger="focus" data-bv-notEmpty-message="' . lang('please_add_items_below') . '" placeholder="' . $this->lang->line("add_item") . '"'); ?>
                        </div>
                        <div class="control-group table-group">
                            <label class="table-label" for="combo"><?= lang("combo_products"); ?></label>
                            <!--<div class="row"><div class="ccol-md-10 col-sm-10 col-xs-10"><label class="table-label" for="combo"><?= lang("combo_products"); ?></label></div>
                            <div class="ccol-md-2 col-sm-2 col-xs-2"><div class="form-group no-help-block" style="margin-bottom: 0;"><input type="text" name="combo" id="combo" value="" data-bv-notEmpty-message="" class="form-control" /></div></div></div>-->
                            <div class="controls table-controls">
                                <table id="prTable"
                                       class="table items table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th class="col-md-5 col-sm-5 col-xs-5"><?= lang("product_name") . " (" . $this->lang->line("product_code") . ")"; ?></th>
                                        <th class="col-md-2 col-sm-2 col-xs-2"><?= lang("quantity"); ?></th>
                                        <th class="col-md-3 col-sm-3 col-xs-3"><?= lang("unit_price"); ?></th>
                                        <th class="col-md-3 col-sm-3 col-xs-3"><?= lang("total"); ?></th>
                                        <th class="col-md-1 col-sm-1 col-xs-1 text-center">
                                            <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>

                    </div>
					
					<div class="combo2" style="display:none;">

                        <div class="form-group">
                            <?= lang("add_product", "add_item") . ' (' . lang('not_with_variants') . ')'; ?>
                            <?php echo form_input('add_item', '', 'class="form-control ttip" id="add_item2" data-placement="top" data-trigger="focus" data-bv-notEmpty-message="' . lang('please_add_items_below') . '" placeholder="' . $this->lang->line("add_item") . '"'); ?>
                        </div>
                        <div class="control-group table-group">
                            <label class="table-label" for="combo"><?= lang("digital_products"); ?></label>

                            <div class="controls table-controls">
                                <table id="prTable2"
                                       class="table items table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th class="col-md-5 col-sm-5 col-xs-5"><?= lang("product_name") . " (" . $this->lang->line("product_code") . ")"; ?></th>
                                        <th class="col-md-1 col-sm-1 col-xs-1 text-center"><i class="fa fa-trash-o"
                                                                                              style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
									<tfoot></tfoot>
                                </table>
                            </div>
                        </div>

                    </div>
					
                    <div class="digital2" style="display:none;">
                        <div class="form-group digital">
                            <?= lang("digital_file", "digital_file") ?>
                            <input id="digital_file" type="file" name="digital_file" data-show-upload="false"
                                   data-show-preview="false" class="form-control file">
                        </div>
                    </div>
					<div class="form-group all supplier" style="display:none;">
                        <?= lang("supplier", "supplier") ?>
                        <div class="row" id="supplier-con all">                           
							<div class="col-md-8 col-sm-8 col-xs-8">
								<?php
								$sup[''] = "";
								foreach($suppliers as $supplier){
									$sup[$supplier->id] = $supplier->name . '(' .$supplier->company .')';
								}								
								echo form_dropdown('supplier_product', $sup, (isset($_POST['supplier_product']) ? $_POST['supplier_product'] : ($product ? $product->supplier5 : '')), 'class="form-control" id="type" placeholder="' . lang('supplier') . '"');
								?>
							</div>
                            <div
                                class="col-md-4 col-sm-4 col-xs-4"><?= form_input('supplier_product_price', (isset($_POST['supplier_product_price']) ? $_POST['supplier_product_price'] : ($product ? $product->supplier5price : '')), 'class="form-control tip" id="supplier_product_price" placeholder="' . lang('supplier_price') . '"') ?>
							</div>							
                        </div>
                        <div id="ex-suppliers"></div>
						<div class="row all">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<?= lang("include_cost", "include_cost") ?>
								<?php
								$opts = array('0' => lang('no'),'1' => lang('yes'));
								echo form_dropdown('include_cost', $opts, (isset($_POST['include_cost']) ? $_POST['include_cost'] : ($product ? $product->service_type : '')), 'class="form-control" id="include_cost"');
								?>
							</div>
						</div>
                    </div>
                </div>

                <div class="col-md-12">
                    
                    <div class="form-group inactive">
                        <input type="checkbox" class="checkbox inactive" value="1" name="inactive" id="inactive" <?= $product->inactived ? 'checked="checked"' : ''; ?>>
                        <label for="inactive" class="padding05">
                            <?= lang('inactive'); ?>
                        </label>
                    </div>

                    <div class="form-group extras">
                        <input name="cf" type="checkbox" class="checkbox" id="extras" value="" <?= isset($_POST['cf']) ? 'checked="checked"' : '' ?>/>
                        <label for="extras" class="padding05"><?= lang('custom_fields') ?></label>
                    </div>

                    <div class="row" id="extras-con" style="display: none;">

                        <div class="col-md-4">
                            <div class="form-group all">
                                <?= lang('pcf1', 'pcf1') ?>
                                <?= form_input('cf1', (isset($_POST['cf1']) ? $_POST['cf1'] : ($product ? $product->cf1 : '')), 'class="form-control tip" id="cf1"') ?>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group all">
                                <?= lang('pcf2', 'pcf2') ?>
                                <?= form_input('cf2', (isset($_POST['cf2']) ? $_POST['cf2'] : ($product ? $product->cf2 : '')), 'class="form-control tip" id="cf2"') ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group all">
                                <?= lang('pcf3', 'pcf3') ?>
                                <?= form_input('cf3', (isset($_POST['cf3']) ? $_POST['cf3'] : ($product ? $product->cf3 : '')), 'class="form-control tip" id="cf3"') ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group all">
                                <?= lang('pcf4', 'pcf4') ?>
                                <?= form_input('cf4', (isset($_POST['cf4']) ? $_POST['cf4'] : ($product ? $product->cf4 : '')), 'class="form-control tip" id="cf4"') ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group all">
                                <?= lang('pcf5', 'pcf5') ?>
                                <?= form_input('cf5', (isset($_POST['cf5']) ? $_POST['cf5'] : ($product ? $product->cf5 : '')), 'class="form-control tip" id="cf5"') ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group all">
                                <?= lang('pcf6', 'pcf6') ?>
                                <?= form_input('cf6', (isset($_POST['cf6']) ? $_POST['cf6'] : ($product ? $product->cf6 : '')), 'class="form-control tip" id="cf6"') ?>
                            </div>
                        </div>



                    </div>


                    <div class="form-group all product_details">
                        <?= lang("product_details", "product_details") ?>
                        <?= form_textarea('product_details', (isset($_POST['product_details']) ? $_POST['product_details'] : ($product ? $product->product_details : '')), 'class="form-control pdetail" id="details"'); ?>
                    </div>
                    <div class="form-group all product_details">
                        <?= lang("product_details_for_invoice", "details") ?>
                        <?= form_textarea('details', (isset($_POST['details']) ? $_POST['details'] : ($product ? $product->details : '')), 'class="form-control pdetailinvoice" id="details4inv"'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo form_submit('edit_product', $this->lang->line("edit_product"), 'class="btn btn-primary"'); ?>
                    </div>

                </div>
                <?= form_close(); ?>

            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(window).load(function(){
        //checkComboPrice();
        //$(".qty").trigger('change');
    });
    $(document).ready(function () {
        var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
        var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
        var items = {};
        <?php
        if($combo_items) {
            echo '
                var ci = '.json_encode($combo_items).';
                $.each(ci, function() { add_product_item(this); });
                ';
        }
        ?>
		
		 <?php
        if($digital_items) {
            echo '
                var ci = '.json_encode($digital_items).';
                $.each(ci, function() { add_product_item2(this); });
                ';
        }
        ?>
		
        <?php 
            if (isset($beforeDelPro->id) != NULL) {
                echo '
                    $(".pcode").css("pointer-events","none");
                    $(".select").css("pointer-events","none");
                    $(".scate").css("pointer-events","none");
                    $(".pcost").css("pointer-events","none");
                   // $(".pprice").css("pointer-events","none");
                   //$(".pcurrency").css("pointer-events","none");
                   //$(".palert").css("pointer-events","none");
                    $(".supplier").css("pointer-events","none");
                   //$(".psupplier").css("pointer-events","none");
                   //$(".psupplierprice").css("pointer-events","none");
                   //$(".inactive").css("pointer-events","none");
                   //$(".custom_fields").css("pointer-events","none");
                   // $(".promotion").css("pointer-events","none");
                    $(".ptype").css("pointer-events","none");
                    //$("#name").css("pointer-events","none");
                    //$("#name_other").css("pointer-events","none");
                    $("#code").css("pointer-events","none");
                    $("#category").css("pointer-events","none");
                    $("#subcategory").css("pointer-events","none");
                    $("#unit").css("pointer-events","none");
                    $("#costs").css("pointer-events","none");
                    $("#currency").css("pointer-events","none");
                    //$("#alert_quantity").css("pointer-events","none");
                    //$("#supplier1").css("pointer-events","none");
                    //$("#supplier_price").css("pointer-events","none");
                   //$(".product_image").css("pointer-events","none");
                   //$(".product_gallery_images").css("pointer-events","none");
                   /*$(".inactive").css("pointer-events","none");
                   $(".extras").css("pointer-events","none");
                    $(".product_details").css("pointer-events","none");
                   $("#cf1").css("pointer-events","none");
                    $("#cf2").css("pointer-events","none");
                    $("#cf3").css("pointer-events","none");
                   $("#cf4").css("pointer-events","none");
                    $("#cf5").css("pointer-events","none");
                    $("#cf6").css("pointer-events","none");*/
                    $(".add_more_variants").css("pointer-events","none");
                    $(".qty_unit").css("pointer-events","none");
                    $(".del").css("pointer-events","none");
                ';
            } else {
                echo '
                    $(".pcode").css("");
                    $(".select").css("");
                    $(".pcost").css("");
                    $(".pprice").css("");
                    $(".pcurrency").css("");
                    $(".palert").css("");
                    $(".supplier").css("");
                    $(".psupplier").css("");
                    $(".psupplierprice").css("");
                    $(".inactive").css("");
                    $(".custom_fields").css("");
                    $(".promotion").css("");
                    $(".ptype").css("");
                    $(".product_image").css("");
                    $(".product_gallery_images").css("");
                    $(".product_details").css("");
                    $("#cf1").css("");
                    $("#cf2").css("");
                    $("#cf3").css("");
                    $("#cf4").css("");
                    $("#cf5").css("");
                    $("#cf6").css("");
                ';
            }
        ?>

        <?=isset($_POST['cf']) ? '$("#extras").iCheck("check");': '' ?>
        $('#extras').on('ifChecked', function () {
            $('#extras-con').slideDown();
        });
        $('#extras').on('ifUnchecked', function () {
            $('#extras-con').slideUp();
        });

        $('.attributes').on('ifChecked', function (event) {
            $('#options_' + $(this).attr('id')).slideDown();
        });
        $('.attributes').on('ifUnchecked', function (event) {
            $('#options_' + $(this).attr('id')).slideUp();
        });
        //$('#cost').removeAttr('required');
        $('#type').change(function () {
            var t = $(this).val();
            if (t !== 'standard') {
                $('.standard').slideUp();
                $('#cost').attr('required', 'required');
                $('form[data-toggle="validator"]').bootstrapValidator('addField', 'cost');
            } else {
                $('.standard').slideDown();
                $('#cost').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'cost');
            }
            if (t !== 'digital') {
                $('#digital_file').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'digital_file');
				$('.combo2').slideUp();
            } else {
                $('#digital_file').removeAttr('required');
                $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'digital_file');
				$('.combo2').slideDown();
            }
			if (t !== 'service') {
				$('.supplier').slideUp();
				$('.service').slideUp();
            } else {				
				$('.supplier').slideDown();
				$('.service').slideDown();
            }
            if (t !== 'combo') {
                $('.combo').slideUp();
            } else {
                $('.combo').slideDown();
            }
        }).trigger('change');

        $("#add_item").autocomplete({
            source: '<?= site_url('products/suggestions'); ?>',
            minLength: 1,
            autoFocus: false,
            delay: 5,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_product_item(ui.item);
                    checkComboPrice();
                    if (row) {
                        $(this).val('');
                        $('#add_item').removeAttr('required');
                        $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
                    }
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>');
                }
            }
        });
        $('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });
        $('#add_item').removeAttr('required');
		
		$("#add_item2").autocomplete({
            source: '<?= site_url('products/suggestions'); ?>',
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>', function () {
                        $('#add_item2').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>', function () {
                        $('#add_item2').focus();
                    });
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_product_item2(ui.item);
					checkComboPrice();
                    if (row) {
                        $(this).val('');
                    }
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_product_found') ?>');
                }
            }
        });
        $('#add_item2').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });
		 $('#add_item2').removeAttr('required');
		 
        $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
        function add_product_item(item) {
            if (item == null) {
                return false;
            }
            item_id = item.id;
            if (items[item_id]) {
                items[item_id].qty = (parseFloat(items[item_id].qty) + 1).toFixed(2);
            } else {
                items[item_id] = item;
            }

            $("#prTable tbody").empty();
            $("#prTable tfoot").empty();
            var total = 0;
            $.each(items, function () {
                var row_no = this.id;
                var tr_html = '';
                total = this.price * this.qty;
                var newTr = $('<tr id="row_' + row_no + '" class="item_' + this.id + '"></tr>');
                tr_html = '<td><input name="combo_item_id[]" type="hidden" value="' + this.id + '"><input name="combo_item_name[]" type="hidden" value="' + this.name + '"><input name="combo_item_code[]" type="hidden" value="' + this.code + '"><span id="name_' + row_no + '">' + this.name + ' (' + this.code + ')</span></td>';
                
                tr_html += '<td><input class="form-control text-center qty" name="combo_item_quantity_unit[]" type="text" value="' + formatDecimal(this.qty) + '" data-id="' + row_no + '" data-item="' + this.id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
                
                tr_html += '<td><input class="combo_item_price form-control text-center cb-price" name="combo_item_price[]" type="text" value="' + formatDecimal(this.price) + '" data-id="' + row_no + '" data-item="' + this.id + '" id="combo_item_price_' + row_no + '" onClick="this.select();"><input type="hidden" name="cb_cost[]" value="'+this.cost+'" class="cb-cost"><input type="hidden" name="cb_unit_cost[]" value="'+this.cost+'" class="cb-unit_cost"></td>';
                
                tr_html += '<td style="text-align:center;"><span class="total">'+formatDecimal(total)+'</span><input class="form-control text-center cb-total" name="combo_item_total[]" type="hidden" value="' + formatDecimal(total) + '" data-id="' + row_no + '" data-item="' + this.id + '" id="combo_item_total_' + row_no + '"><input type="hidden" name="toal_cost[]" value="" class="total-cost"></td>';
                
                tr_html += '<td class="text-center"><i class="fa fa-times tip del" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                
                newTr.html(tr_html);
                newTr.prependTo("#prTable");
            });
            
            var cb_foot = $("#prTable").find('tfoot');
            if (!cb_foot.length) cb_foot = $('<tfoot>').appendTo("#prTable"); 
            cb_foot.append($('<td style="padding:5px;" colspan="3"><b>Total Cost</b> : <span class="cb_footer_cost"></span><input type="hidden" name="cost_combo_item" id="cost_combo_item"></td>'));
            
            $('.item_' + item_id).addClass('warning');
            //audio_success.play();
            return true;
        }
		
		function add_product_item2(item) {
            if (item == null) {
                return false;
            }
            item_id = item.id;
         
			 items[item_id] = item;
            $("#prTable2 tbody").empty();
			$("#prTable2 tfoot").empty();
			var total = 0;
            $.each(items, function () {
                var row_no = this.id;
                var newTr = $('<tr id="row_' + row_no + '" class="item_' + this.id + '"></tr>');
                tr_html = '<td><input name="combo_item_id[]" type="hidden" value="' + this.id + '"><input name="combo_item_name[]" type="hidden" value="' + this.name + '"><input name="combo_item_code[]" type="hidden" value="' + this.code + '"><span id="name_' + row_no + '">' + this.name + ' (' + this.code + ')</span></td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip del" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#prTable2");
            });

            $('.item_' + item_id).addClass('warning');
            //audio_success.play();
            return true;

        }
		
        $(document).on('change keyup paste', ".cb-price", function(){
            checkComboPrice();
        }).trigger('change');

        $(document).on('click', '.del', function () {
            var id = $(this).attr('id');
            $(this).closest('#row_' + id).remove();
            $.each(items, function (i, v) {
                if (v.id == id) {
                    delete items[i];
                }
            });
            checkComboPrice();
        });
        var su = 2;
        $('#addSupplier').click(function () {
            if (su <= 5) {
                $('#supplier_1').select2('destroy');
                var html = '<div style="clear:both;height:15px;"></div><div class="row"><div class="col-md-8 col-sm-8 col-xs-8"><input type="hidden" name="supplier_' + su + '", class="form-control" id="supplier_' + su + '" placeholder="<?= lang("select") . ' ' . lang("supplier") ?>" style="width:100%;display: block !important;" /></div><div class="col-md-4 col-sm-4 col-xs-4"><input type="text" name="supplier_' + su + '_price" class="form-control tip" id="supplier_' + su + '_price" placeholder="<?= lang('supplier_price') ?>" /></div></div>';
                $('#ex-suppliers').append(html);
                var sup = $('#supplier_' + su);
                suppliers(sup);
                su++;
            } else {
                bootbox.alert('<?= lang('max_reached') ?>');
                return false;
            }
        });

        var _URL = window.URL || window.webkitURL;
        $("input#images").on('change.bs.fileinput', function () {
            var ele = document.getElementById($(this).attr('id'));
            var result = ele.files;
            $('#img-details').empty();
            for (var x = 0; x < result.length; x++) {
                var fle = result[x];
                for (var i = 0; i <= result.length; i++) {
                    var img = new Image();
                    img.onload = (function (value) {
                        return function () {
                            ctx[value].drawImage(result[value], 0, 0);
                        }
                    })(i);

                    img.src = 'images/' + result[i];
                }
            }
        });
        var variants = <?=json_encode($vars);?>;
        $(".select-tags").select2({
            tags: variants,
            tokenSeparators: [","],
            multiple: true
        });
        $(document).on('ifChecked', '#attributes', function (e) {
            $('#attr-con').slideDown();
        });
        $(document).on('ifUnchecked', '#attributes', function (e) {
            $(".select-tags").select2("val", "");
            $('.attr-remove-all').trigger('click');
            $('#attr-con').slideUp();
        });
        $('#addAttributes').click(function (e) {
            e.preventDefault();
            var attrs_val = $('#attributesInput').val(), attrs;
            attrs = attrs_val.split(',');
            for (var i in attrs) {
                if (attrs[i] !== '') {
                    //$('#attrTable').show().append('<tr class="attr"><td><input type="hidden" name="attr_name[]" value="' + attrs[i] + '"><span>' + attrs[i] + '</span></td><td class="code text-center"><input type="hidden" name="attr_warehouse[]" value=""><span></span></td><td class="quantity_unit text-center"><input type="hidden" name="attr_quantity_unit[]" value=""><span></span></td><td class="quantity text-center"><input type="hidden" name="attr_quantity[]" value=""><span></span></td><td class="cost text-right"><input type="hidden" name="attr_cost[]" value="0"><span>0</span></td><td class="price text-right"><input type="hidden" name="attr_price[]" value="0"><span>0</span></span></td><td class="text-center"><i class="fa fa-times delAttr"></i></td></tr>');
                    $('#attrTable').show().append('<tr class="attr"><td><input type="hidden" name="attr_name[]" value="' + attrs[i] + '"><span>' + attrs[i] + '</span></td><td class="quantity_unit text-center"><input type="hidden" name="attr_quantity_unit[]" value=""><span></span></td><td class="price text-right"><input type="hidden" name="attr_price[]" value="0"><span>0</span></span></td><td class="text-center"><i class="fa fa-times delAttr"></i></td></tr>');
                }
            }
        });
        $(document).on('click', '.delAttr', function () {
            $(this).closest("tr").remove();
            //console.log(site.base_url);
        });
        $(document).on('click', '.attr-remove-all', function () {
            $('#attrTable tbody').empty();
            $('#attrTable').hide();
        });
        var row, warehouses = <?= json_encode($warehouses); ?>;
        $(document).on('click', '.attr td:not(:last-child)', function () {
            row = $(this).closest("tr");
            $('#aModalLabel').text(row.children().eq(0).find('span').text());
            //$('#awarehouse').select2("val", (row.children().eq(1).find('input').val()));
            $('#aquantity_unit').val(row.children().eq(1).find('span').text());
            //$('#aquantity').val(row.children().eq(3).find('span').text());
            //$('#acost').val(row.children().eq(4).find('span').text());
            $('#aprice').val(row.children().eq(2).find('span').text());
            $('#aModal').appendTo('body').modal('show');
        });
        
        //=====================Related Strap=========================
        $(document).on('ifChecked', '#related_strap', function (e) {
            $('#strap-con').slideDown();
        });
        $(document).on('ifUnchecked', '#related_strap', function (e) {
            $(".select-strap").select2("val", "");
            $('.attr-remove-all').trigger('click');
            $('#strap-con').slideUp();
        });
        //=====================end===================================

        $(document).on('click', '#updateAttr', function () {
            var wh = $('#awarehouse').val(), wh_name;
            $.each(warehouses, function () {
                if (this.id == wh) {
                    wh_name = this.name;
                }
            });
            //row.children().eq(1).html('<input type="hidden" name="attr_warehouse[]" value="' + wh + '"><input type="hidden" name="attr_wh_name[]" value="' + wh_name + '"><span>' + wh_name + '</span>');
            row.children().eq(1).html('<input type="hidden" name="attr_quantity_unit[]" value="' + $('#aquantity_unit').val() + '"><span>' + decimalFormat($('#aquantity_unit').val()) + '</span>');
            //row.children().eq(3).html('<input type="hidden" name="attr_quantity[]" value="' + $('#aquantity').val() + '"><span>' + decimalFormat($('#aquantity').val()) + '</span>');
            //row.children().eq(4).html('<input type="hidden" name="attr_cost[]" value="' + $('#acost').val() + '"><span>' + currencyFormat($('#acost').val()) + '</span>');
            row.children().eq(2).html('<input type="hidden" name="attr_price[]" value="' + $('#aprice').val() + '"><span>' + $('#aprice').val() + '</span>');
            $('#aModal').modal('hide');
        });
        $(document).on('change', ".cb-price", function(){
            var qty = parseFloat($(this).parent().parent().find('.qty').val());
            var price   = $(this).val();
            var total = price * qty;
            $(this).parent().parent().find('.total').html(total);
            $(this).parent().parent().find('.cb-total').val(total);
            var tpprice = 0;
            $(".cb-total").each(function(){
                tpprice +=$(this).val()-0;
            });
            $('#prices').val(formatDecimal(tpprice));
        });
        $(document).on('change', ".qty", function(){
            var price = parseFloat($(this).parent().parent().find('.cb-price').val());
            var cost = parseFloat($(this).parent().parent().find('.cb-unit_cost').val());
            var qty   = parseFloat($(this).val());
            var total = price * qty;
            var total_cost = cost * qty;
            $(this).parent().parent().find('.total').html(total);
            $(this).parent().parent().find('.cb-total').val(total);
            $(this).parent().parent().find('.cb-cost').val(total_cost);
            
            var tpprice = 0; 
            $(".cb-total").each(function(){
                tpprice +=$(this).val()-0;
            });
            
            var subt = 0;
            $(".cb-cost").each(function(){
                subt +=$(this).val()-0;
            });
            
            $('.cb_footer_cost').html(subt);
            $('#cost_combo_item').val(formatDecimal(subt));
            $('#prices').val(formatDecimal(tpprice));
            checkComboPrice(tpprice);
        });
        
    });
    var total_product_price = $('#prices').val();
    function checkComboPrice(param = null){
        var total_price = 0;
        var total_cost = 0;
        var tpprice = 0;
        $(".cb-price").each(function(){
            total_price += $(this).val()-0;
        });
        $(".cb-cost").each(function(){
            total_cost += $(this).val()-0;
        });
        $(".cb-total").each(function(){
            tpprice +=$(this).val()-0;

        });
        if(param) {
            $('#prices').val(formatDecimal(param));
        }
        if(total_product_price > tpprice) {
            if(tpprice){
                $("#prices").val(total_product_price);
            }
        } else {
            if(tpprice){
                $("#prices").val(tpprice);
            }
        }
        $(".cb_footer_cost").text(formatDecimal(total_cost));
        $("#cost_combo_item").val(formatDecimal(total_cost));
    }

    <?php if ($product) { ?>
    $(document).ready(function () {
        $('#enable_wh').click(function () {
            var whs = $('.wh');
            $.each(whs, function () {
                $(this).val($('#v' + $(this).attr('id')).val());
            });
            $('#warehouse_quantity').val(1);
            $('.wh').attr('disabled', false);
            $('#show_wh_edit').slideDown();
        });
        $('#disable_wh').click(function () {
            $('#warehouse_quantity').val(0);
            $('#show_wh_edit').slideUp();
        });
        $('#show_wh_edit').hide();
        $('.wh').attr('disabled', true);
        var t = "<?=$product->type?>";
        if (t !== 'standard') {
            $('.standard').slideUp();
            $('#cost').attr('required', 'required');
            $('form[data-toggle="validator"]').bootstrapValidator('addField', 'cost');
        } else {
            $('.standard').slideDown();
            $('#cost').removeAttr('required');
            $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'cost');
        }
        if (t !== 'digital') {
            $('.digital').slideUp();
            $('#digital_file').removeAttr('required');
            $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'digital_file');
        } else {
            $('.digital').slideDown();
            $('#digital_file').attr('required', 'required');
            $('form[data-toggle="validator"]').bootstrapValidator('addField', 'digital_file');
        }
        if (t !== 'combo') {
            $('.combo').slideUp();
        } else {
            $('.combo').slideDown();
        }
        $('#add_item').removeAttr('required');
        $('form[data-toggle="validator"]').bootstrapValidator('removeField', 'add_item');
        //$("#code").parent('.form-group').addClass("has-error");
        //$("#code").focus();
        $("#product_image").parent('.form-group').addClass("text-warning");
        $("#images").parent('.form-group').addClass("text-warning");
        $.ajax({
            type: "get", async: false,
            url: "<?= site_url('products/getSubCategories') ?>/" + <?= $product->category_id ?>,
            dataType: "json",
            success: function (scdata) {
                if (scdata != null) {
                    $("#subcategory").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
                        placeholder: "<?= lang('select_category_to_load') ?>",
                        data: scdata
                    });
                }
            }
        });
        <?php if ($product->supplier1) { ?>
        select_supplier('supplier1', "<?= $product->supplier1; ?>");
        $('#supplier_price').val("<?= $this->erp->formatDecimal($product->supplier1price); ?>");
        <?php } else { ?>
            $('#supplier1').addClass('rsupplier');
        <?php } ?>
        <?php if ($product->supplier2) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_2', "<?= $product->supplier2; ?>");
        $('#supplier_2_price').val("<?= $this->erp->formatDecimal($product->supplier2price); ?>");
        <?php } ?>
        <?php if ($product->supplier3) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_3', "<?= $product->supplier3; ?>");
        $('#supplier_3_price').val("<?= $this->erp->formatDecimal($product->supplier3price); ?>");
        <?php } ?>
        <?php if ($product->supplier4) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_4', "<?= $product->supplier4; ?>");
        $('#supplier_4_price').val("<?= $this->erp->formatDecimal($product->supplier4price); ?>");
        <?php } ?>
        <?php if ($product->supplier5) { ?>
        $('#addSupplier').click();
        select_supplier('supplier_5', "<?= $product->supplier5; ?>");
        $('#supplier_5_price').val("<?= $this->erp->formatDecimal($product->supplier5price); ?>");
        <?php } ?>
        function select_supplier(id, v) {
            $('#' + id).val(v).select2({
                minimumInputLength: 1,
                data: [],
                initSelection: function (element, callback) {
                    $.ajax({
                        type: "get", async: false,
                        url: "<?= site_url('suppliers/getSupplier') ?>/" + $(element).val(),
                        dataType: "json",
                        success: function (data) {
                            callback(data[0]);
                        }
                    });
                },
                ajax: {
                    url: site.base_url + "suppliers/suggestions",
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
                }
            });
        }
    });
    <?php } ?>
    $(document).ready(function () {
        $('#enable_wh').trigger('click');
    });
</script>

<div class="modal" id="aModal" tabindex="-1" role="dialog" aria-labelledby="aModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="aModalLabel"><?= lang('add_product_manually') ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                            <label for="aquantity_unit" class="col-sm-4 control-label"><?= lang('quantity_unit') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="aquantity_unit">
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="aprice" class="col-sm-4 control-label"><?= lang('price') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="aprice">
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="updateAttr"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>
