<div class="box">
    <div class="box-header no-print">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('print_barcode_label'); ?></h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="#" onclick="window.print();return false;" id="print-icon" class="tip" title="<?= lang('print') ?>">
                        <i class="icon fa fa-print"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo sprintf(lang('print_barcode_heading'), 
                anchor('system_settings/categories', lang('categories')),
                anchor('system_settings/subcategories', lang('subcategories')),
                anchor('purchases', lang('purchases')),
                anchor('transfers', lang('transfers'))
                ); ?></p>

                <div class="well well-sm no-print">
                    <div class="form-group">
                        <?= lang("add_product", "add_item"); ?>
                        <?php echo form_input('add_item', '', 'class="form-control" id="add_item" placeholder="' . $this->lang->line("add_item") . '"'); ?>
                    </div>
                    <?= form_open("products/print_barcodes", 'id="barcode-print-form" data-toggle="validator"'); ?>
                    <div class="controls table-controls">
                        <table id="bcTable"
                               class="table items table-striped table-bordered table-condensed table-hover">
                            <thead>
                            <tr>
                                <th class="col-xs-4"><?= lang("product_name") . " (" . $this->lang->line("product_code") . ")"; ?></th>
                                <th class="col-xs-1"><?= lang("quantity"); ?></th>
                                <th class="col-xs-7"><?= lang("variants"); ?></th>
                                <th class="text-center" style="width:30px;">
                                    <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

					<div class="form-group">
						<?= lang('style', 'style'); ?>
						<?php
                            $opts = array(
                                            '' => lang('select').' '.lang('style'),
                                111 => lang('maman_barcode'), // MAMAN Barcode
                                65 => lang('65_labels_a4'),
                                            11 => lang('SWAP'),
                                            6 => lang('SBPS'),
                                            10 => lang('AYCollection'),
                                            40 => lang('40_per_sheet'),
                                            30 => lang('30_per_sheet'),
                                            24 => lang('24_per_sheet'),
                                            20 => lang('20_per_sheet'),
                                            18 => lang('18_per_sheet'),
                                            14 => lang('14_per_sheet'),
                                            8 => lang('City_Goal'),
                                            12 => lang('12_per_sheet'),
                                            50 => lang('continuous_feed'),
                                            16=>lang('new'),
                                            90 => lang('a4-col-5'));
                        ?>
                        <?= form_dropdown('style', $opts, set_value('style', 111), 'class="form-control tip" id="style" required="required"'); ?>
						<div class="row cf-con" style="margin-top: 10px; display: none;">
							<div class="col-xs-4">
								<div class="form-group">
									<div class="input-group">
										<?= form_input('cf_width', '', 'class="form-control" id="cf_width" placeholder="' . lang("width") . '"'); ?>
										<span class="input-group-addon" style="padding-left:10px;padding-right:10px;"><?= lang('inches'); ?></span>
									</div>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="form-group">
									<div class="input-group">
										<?= form_input('cf_height', '', 'class="form-control" id="cf_height" placeholder="' . lang("height") . '"'); ?>
										<span class="input-group-addon" style="padding-left:10px;padding-right:10px;"><?= lang('inches'); ?></span>
									</div>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="form-group">
								<?php $oopts = array(0 => lang('portrait'), 1 => lang('landscape')); ?>
									<?= form_dropdown('cf_orientation', $oopts , '', 'class="form-control" id="cf_orientation" placeholder="' . lang("orientation") . '"'); ?>
								</div>
							</div>
						</div>
						<span class="help-block"><?= lang('barcode_tip'); ?></span>
						<span class="aflinks pull-right">
							<a href="http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=158618&merchantID=6360&programmeID=17564&mediaID=-1&tracking=A4Lables&url=https://www.a4labels.com" target="_blank">A4Lables.com</a> |
							<a href="http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=158618&merchantID=6360&programmeID=17564&mediaID=0&tracking=&url=https://www.a4labels.com/products/white-self-adhesive-printer-labels-63-5-x-72mm/23585" target="_blank">12 per sheet</a> |
							<a href="http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=158618&merchantID=6360&programmeID=17564&mediaID=0&tracking=&url=https://www.a4labels.com/products/white-self-adhesive-printer-labels-63-x-47mm/23586" target="_blank">18 per sheet</a> |
							<a href="http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=158618&merchantID=6360&programmeID=17564&mediaID=0&tracking=&url=https://www.a4labels.com/products/white-self-adhesive-printer-labels-63-x-34mm/23588" target="_blank">24 per sheet</a> |
							<a href="http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=158618&merchantID=6360&programmeID=17564&mediaID=0&tracking=&url=https://www.a4labels.com/products/white-self-adhesive-printer-labels-46-x-25mm/23587" target="_blank">40 per sheet</a>
						</span>
						<div class="clearfix"></div>
					</div>
					<div class="form-group">
                        <span style="font-weight: bold; margin-right: 15px;"><?= lang('print'); ?>:</span>
                        <input name="site_name" type="checkbox" id="site_name" value="1" style="display:inline-block;"/>
                        <label for="site_name" class="padding05"><?= lang('site_name'); ?></label>
                        <input name="product_name" type="checkbox" id="product_name" value="1" checked="checked"
                               style="display:inline-block;"/>
                        <label for="product_name" class="padding05"><?= lang('product_name'); ?></label>
                        <input name="price" type="checkbox" id="price" value="1" checked="checked"
                               style="display:inline-block;"/>
                        <label for="price" class="padding05"><?= lang('price'); ?></label>
                        <input name="currencies" type="checkbox" id="currencies" value="1"
                               style="display:inline-block;"/>
                        <label for="currencies" class="padding05"><?= lang('currencies'); ?></label>
                        <input name="unit" type="checkbox" id="unit" value="1" style="display:inline-block;"/>
                        <label for="unit" class="padding05"><?= lang('unit'); ?></label>
                        <input name="category" type="checkbox" id="category" value="1" style="display:inline-block;"/>
                        <label for="category" class="padding05"><?= lang('category'); ?></label>
                        <input name="variants" type="checkbox" id="variants" value="1" style="display:inline-block;"/>
                        <label for="variants" class="padding05"><?= lang('variants'); ?></label>
                        <input name="product_image" type="checkbox" id="product_image" value="1"
                               style="display:inline-block;"/>
                        <label for="product_image" class="padding05"><?= lang('product_image'); ?></label>
                    </div>
                    <div class="form-group col-sm-4">
                        <label class="control-label" for="project"><?= lang("project"); ?></label>
                        <?php

                        foreach ($billers as $biller) {
                            $bill[$biller->company] = $biller->company;
                        }
                        echo form_dropdown('biller', $bill, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                        ?>
                        </div>

                    <div class="form-group col-sm-12">
                        <?php echo form_submit('print', lang("update"), 'class="btn btn-primary"'); ?>
                        <button type="button" id="reset" class="btn btn-danger"><?= lang('reset'); ?></button>
                    </div>
                    <?= form_close(); ?>
                    <div class="clearfix"></div>
                </div>
                <div id="barcode-con">
                    <?php
                        if ($this->input->post('print')) {
                            if (!empty($barcodes)) {
                                echo '<button type="button" onclick="window.print();return false;" class="btn btn-primary btn-block tip no-print" title="'.lang('print').'"><i class="icon fa fa-print"></i> '.lang('print').'</button>';
                                $c = 1;
                                if ($style == 12 || $style == 18 || $style == 24 || $style == 40 || $style == 8){
                                    echo '<div class="barcodea4">';
									echo '<div id="contain" style="width:73%">';
                                } elseif ($style == 111) { // MAMAN Barcode
                                    echo '<div class="barcode111">';
                                } elseif ($style == 65) {
                                    echo '<div class="barcode65">';
                                }elseif($style == 16){
									echo '<div class="barcode">';
								}elseif ($style == 6) {
                                    echo '<div class="barcode">';
                                }elseif ($style != 50) {
                                    echo '<div class="barcode">';
                                }
                                foreach ($barcodes as $item) {
									
									if($style == 90 && $item['image'])
									{
										$style = 95;
									}
									
                                    for ($r = 1; $r <= $item['quantity']; $r++) {
                                        echo '<div class="item style'.$style.'" '.
                                        ($style == 50 && $this->input->post('cf_width') && $this->input->post('cf_height') ?
                                            'style="width:'.$this->input->post('cf_width').'in;height:'.$this->input->post('cf_height').'in;border:0;"' : '')
                                        .'>';
										
                                        if ($style == 50) {
                                            if ($this->input->post('cf_orientation')) {
                                                $ty = (($this->input->post('cf_height')/$this->input->post('cf_width'))*100).'%';
                                                $landscape = '
                                                -webkit-transform-origin: 0 0;
                                                -moz-transform-origin:    0 0;
                                                -ms-transform-origin:     0 0;
                                                transform-origin:         0 0;
                                                -webkit-transform: translateY('.$ty.') rotate(-90deg);
                                                -moz-transform:    translateY('.$ty.') rotate(-90deg);
                                                -ms-transform:     translateY('.$ty.') rotate(-90deg);
                                                transform:         translateY('.$ty.') rotate(-90deg);
                                                ';
                                                echo '<div class="div50" style="width:'.$this->input->post('cf_height').'in;height:'.$this->input->post('cf_width').'in;border: 1px dotted #CCC;'.$landscape.'">';
                                            } else {
                                                echo '<div class="div50" style="width:'.$this->input->post('cf_width').'in;height:'.$this->input->post('cf_height').'in;border: 1px dotted #CCC;padding-top:0.025in;">';
                                            }
                                        }
										
                                        if($style == 6) {
                                            if($item['image']) {
                                                echo '<span class="product_image" style="height:100%"><img  style="width:120px;height:90px" src="'.base_url('assets/uploads/thumbs/'.$item['image']).'" alt="" /></span>';
                                            }
                                            if($item['site']) {
                                                echo '<span class="barcode_site" style="font-size:12px">'.$item['site'].'</span>';
                                            }
                                            if($item['name']) {
                                                echo '<span class="barcode_name" style="font-size:12px">'.$item['name'].'</span>';
                                            }
                                            if($item['unit']) {
                                                echo '<span class="barcode_unit">'.lang('unit').': '.$item['unit'].'</span>, ';
                                            }
                                            if($item['category']) {
                                                echo '<span class="barcode_category">'.lang('category').': '.$item['category'].'</span> ';
                                            }
                                            if($item['variants']) {
                                                echo '<span class="variants">'.lang('variants').': ';
                                                foreach ($item['variants'] as $variant) {
                                                    echo $variant->name.', ';
                                                }
                                                echo '</span> ';
                                            }
                                            echo '<span class="barcode_image sokhan" style="margin-top: -7px;">';
                                            echo $item['barcode'];
                                            
                                            if($item['price']) {
                                                echo '<br>';
                                                echo '<div class="col-sm-8 col-sm-offset-2">';
                                                echo '<table>';
                                                    echo '<tr>';
                                                        echo '<td style="border:1px solid #CCC;width:400px;height:30px;">';
                                                            echo "USD";
                                                        echo '</td>';
                                                        echo '<td style="border:1px solid #CCC;width:400px;height:30px;">';
                                                            echo $item['price'];
                                                        echo '</td>';
                                                    echo '</tr>';
                                                    echo '<tr>';
                                                        echo '<td style="border:1px solid #CCC;width:400px;height:30px;">';
                                                            echo "KHM";
                                                        echo '</td>';
                                                        echo '<td style="border:1px solid #CCC;width:400px;height:30px;">';
                                                            echo $item['price']* $cur->rate;
                                                        echo '</td>';
                                                    echo '</tr>';
                                                echo '</table>';
                                                echo '</div>';
                                            }
                                            echo '</span>';
                                            echo '<br>';
                                            echo '<br>';
                                            echo '<br>';
                                            echo '<br>';
                                            echo '<br>';
                                        } elseif ($style == 111) { // MAMAN Barcode
                                            if ($item['site']) {
                                                echo '<p class="barcode_site">' . $item['site'] . '</p>';
                                            }

                                            if ($item['biller']) {
                                                if (strstr($item['biller'], '(', false)) {
                                                    echo '<p class="barcode_biller">' . strstr($item['biller'], '(', true) . '</p>';
                                                } else {
                                                    echo '<p class="barcode_biller">' . $item['biller'] . '</p>';
                                                }
                                            }

                                            echo '<div class="row">';
                                            echo '<div class="col-sm-3 col-xs-3"><div class="product_code rotate">';
                                            echo $item['code'];
                                            echo '</div></div>';
                                            echo '<div class="col-sm-6 col-xs-6" style="height: 280px"><div class="barcode_img rotate">';
                                            echo $item['barcode'];
                                            echo '</div></div>';
                                            if ($item['image']) {
                                                echo '<span class="product_image" style="height:100%"><img src="' . base_url('assets/uploads/thumbs/' . $item['image']) . '" alt="" /></span>';
                                            }
                                            if ($item['unit']) {
                                                echo '<span class="barcode_unit">' . lang('unit') . ': ' . $item['unit'] . '</span>, ';
                                            }
                                            if ($item['category']) {
                                                echo '<span class="barcode_category">' . lang('category') . ': ' . $item['category'] . '</span> ';
                                            }
                                            if ($item['variants']) {
                                                echo '<span class="variants">' . lang('variants') . ': ';
                                                foreach ($item['variants'] as $variant) {
                                                    echo $variant->name . ', ';
                                                }
                                                echo '</span> ';
                                            }
                                            echo '<div class="col-sm-3 col-xs-3">';

                                            if ($item['name']) {
                                                echo '<div><p class="barcode_name rotate" style="margin-left: -25px !important">' . $item['name'] . '</p></div>';
                                            }
                                            echo '</div>';
                                            echo '</div>';

                                            if ($item['price']) {
                                                echo '<p class="barcode_price" style="border-top: 3px solid #000">';
                                                if ($item['currencies']) {
                                                    foreach ($currencies as $currency) {
                                                        echo $currency->code . ': ' . $this->erp->formatMoney($item['price'] * $currency->rate) . ', ';
                                                    }
                                                } else {
                                                    echo '<div style="float:left; font-size: 22px; font-weight:bold; font-family:Times New Roman; padding-left: 0.5cm"><span style="text-transform: none !important">Price:</span></div>';

                                                    echo '<div style="float:right; font-size: 28px; font-weight:bold; font-family:Times New Roman; padding-right: 0.80cm">';
                                                    echo $item['price'];
                                                    echo '</div>';
                                                }
                                                echo '</p> ';
                                            }

                                        } elseif ($style == 65) {
                                            if($item['site']) {
                                                echo '<p class="barcode_site" style="font-size:12px; font-weight: bold; margin-bottom: 0 !important">'.$item['site'].'</p>';
                                            }
                                            if($item['name']) {
                                                echo '<span class="barcode_name" style="font-size:10px">'.$item['name'].'</span>';
                                            }
                                            if($item['image']) {
                                                echo '<span class="product_image" style="height:100%"><img  style="width:120px;height:126px" src="'.base_url('assets/uploads/thumbs/'.$item['image']).'" alt="" /></span>';
                                            }
                                            if($item['unit']) {
                                                echo '<span class="barcode_unit">'.lang('unit').': '.$item['unit'].'</span>, ';
                                            }
                                            if($item['category']) {
                                                echo '<span class="barcode_category">'.lang('category').': '.$item['category'].'</span> ';
                                            }
                                            if($item['variants']) {
                                                echo '<span class="variants">'.lang('variants').': ';
                                                foreach ($item['variants'] as $variant) {
                                                    echo $variant->name.', ';
                                                }
                                                echo '</span> ';
                                            }
                                            echo '<span class="barcode_image sokhan" style="margin-top: -7px;">';
                                            echo $item['barcode'];
                                            
                                            if($item['price']) {
                                                echo '<span class="barcode_price" style="font-weight:bold; font-size:14px; display:block; padding-top:2px !important;margin-bottom:9px;">';
                                                if($item['currencies']) {
                                                    foreach ($currencies as $currency) {
                                                        echo $currency->code . ': ' . $this->erp->formatMoney($item['price'] * $currency->rate).', ';
                                                    }
                                                } else {
                                                    echo $item['price'];
                                                }
                                                echo '</span> ';
                                            }
                                        }else{
                                            if($item['site']) {
                                                echo '<span class="barcode_site" style="font-size:12px">'.$item['site'].'</span>';
                                            }
                                            if($item['name']) {
                                                echo '<span class="barcode_name" style="font-size:12px">'.$item['name'].'</span>';
                                            }
    										if($item['image']) {
                                                echo '<span class="product_image" style="height:100%"><img  style="width:120px;height:126px" src="'.base_url('assets/uploads/thumbs/'.$item['image']).'" alt="" /></span>';
                                            }
                                            if($item['unit']) {
                                                echo '<span class="barcode_unit">'.lang('unit').': '.$item['unit'].'</span>, ';
                                            }
                                            if($item['category']) {
                                                echo '<span class="barcode_category">'.lang('category').': '.$item['category'].'</span> ';
                                            }
                                            if($item['variants']) {
                                                echo '<span class="variants">'.lang('variants').': ';
                                                foreach ($item['variants'] as $variant) {
                                                    echo $variant->name.', ';
                                                }
                                                echo '</span> ';
                                            }
                                            echo '<span class="barcode_image sokhan" style="margin-top: -7px;">';
    										echo $item['barcode'];
    										
                                            if($item['price']) {
                                                echo '<span class="barcode_price" style="margin-top:5px;font-weight:bold; font-size:5px; display:block; padding-top:2px !important;margin-bottom:9px;">' . lang('$') . ' ';
                                                if($item['currencies']) {
                                                    foreach ($currencies as $currency) {
                                                        echo $currency->code . ': ' . $this->erp->formatMoney($item['price'] * $currency->rate).', ';
                                                    }
                                                } else {
                                                    echo $item['price'];
                                                }
                                                echo '</span> ';
                                            }
										}

										echo '</span>';
                                        if ($style == 50) {
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                        if ($style == 40) {
                                            if ($c % 40 == 0) {
                                                echo '</div><div class="clearfix"></div><div class="barcodea4">';
                                            }
                                        } elseif ($style == 111) { // MAMAN Barcode
                                            echo '</div><div class="clearfix"></div><div class="barcode111">';
                                        } elseif ($style == 65) {
                                            if ($c % 65 == 0) {
                                                echo '</div><div class="clearfix"></div><div class="barcode65">';
                                            }
                                        } elseif ($style == 30) {
                                            if ($c % 30 == 0) {
                                                echo '</div><div class="clearfix"></div><div class="barcode">';
                                            }
                                        } elseif ($style == 24) {
                                            if ($c % 24 == 0) {
                                                echo '</div><div class="clearfix"></div><div class="barcodea4">';
                                            }
                                        } elseif ($style == 20) {
                                            if ($c % 20 == 0) {
                                                echo '</div><div class="clearfix"></div><div class="barcode">';
                                            }
                                        } elseif ($style == 18) {
                                            if ($c % 18 == 0) {
                                                echo '</div><div class="clearfix"></div><div class="barcodea4">';
                                            }
                                        } elseif ($style == 14) {
                                            if ($c % 14 == 0) {
                                                echo '</div><div class="clearfix"></div><div class="barcode">';
                                            }
                                        } elseif ($style == 12) {
                                            if ($c % 12 == 0) {
                                                echo '</div><div class="clearfix"></div><div class="barcodea4">';
                                            }
                                        } elseif ($style == 10) {
                                            if ($c % 10 == 0) {
                                                echo '</div><div class="clearfix"></div><div class="barcode">';
                                            }
                                        }
										elseif ($style == 11) {
                                            if ($c % 11 == 0) {
                                                echo '</div><div class="clearfix"></div><div class="barcode">';
                                            }
                                        }

                                        elseif ($style == 6) {
                                            if ($c % 2 == 0) {
                                                echo '</div><div class="clearfix" ></div><div class="barcode">';
                                            }
                                        }
										
										elseif ($style == 16) {
                                            if ($c % 2 == 0) {
                                                echo '</div><div class="clearfix" ></div><div class="barcode">';
                                            }
                                        }
										
                                        $c++;
                                    }
                                }
								
                                if ($style != 50) {
									echo '</div>';
                                    echo '</div>';
                                }
                                echo '<button type="button" onclick="window.print();return false;" class="btn btn-primary btn-block tip no-print" title="'.lang('print').'"><i class="icon fa fa-print"></i> '.lang('print').'</button>';
                            } else {
                                echo '<h3>'.lang('no_product_selected').'</h3>';
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
   @media print{
	   #contain{
		    margin-left:-14px !important;
	   }
   }
</style>
<script type="text/javascript">
    var ac = false; bcitems = {};
    if (__getItem('bcitems')) {
        bcitems = JSON.parse(__getItem('bcitems'));
    }
    <?php if($items) { ?>
    __setItem('bcitems', JSON.stringify(<?= $items; ?>));
    <?php } ?>
    $(document).ready(function() {
        <?php if ($this->input->post('print')) { ?>
            $( window ).load(function() {
                $('html, body').animate({
                    scrollTop: ($("#barcode-con").offset().top)-15
                }, 1000);
            });
        <?php } ?>
        if (__getItem('bcitems')) {
            loadItems();
        }
        $("#add_item").autocomplete({
            source: '<?= site_url('products/get_suggestions'); ?>',
            minLength: 1,
            autoFocus: false,
            delay: 200,
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
                    $(this).removeClass('ui-autocomplete-loading');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
					//console.log(ui.item.id);return;
                    var row = add_product_item(ui.item);
					
                    if (row) {
                        $(this).val('');
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

        $('#style').change(function (e) {
            __setItem('bcstyle', $(this).val());
            if ($(this).val() == 50) {
                $('.cf-con').slideDown();
            } else {
                $('.cf-con').slideUp();
            }
        });
        if (style = __getItem('bcstyle')) {
            $('#style').val(style);
            $('#style').select2("val", style);
            if (style == 50) {
                $('.cf-con').slideDown();
            } else {
                $('.cf-con').slideUp();
            }
        }

        $('#cf_width').change(function (e) {
            __setItem('cf_width', $(this).val());
        });
        if (cf_width = __getItem('cf_width')) {
            $('#cf_width').val(cf_width);
        }

        $('#cf_height').change(function (e) {
            __setItem('cf_height', $(this).val());
        });
        if (cf_height = __getItem('cf_height')) {
            $('#cf_height').val(cf_height);
        }

        $('#cf_orientation').change(function (e) {
            __setItem('cf_orientation', $(this).val());
        });
        if (cf_orientation = __getItem('cf_orientation')) {
            $('#cf_orientation').val(cf_orientation);
        }

        $(document).on('ifChecked', '#site_name', function(event) {
            __setItem('bcsite_name', 1);
        });
        $(document).on('ifUnchecked', '#site_name', function(event) {
            __setItem('bcsite_name', 0);
        });
        if (site_name = __getItem('bcsite_name')) {
            if (site_name == 1)
                $('#site_name').iCheck('check');
            else
                $('#site_name').iCheck('uncheck');
        }

        $(document).on('ifChecked', '#product_name', function(event) {
            __setItem('bcproduct_name', 1);
        });
        $(document).on('ifUnchecked', '#product_name', function(event) {
            __setItem('bcproduct_name', 0);
        });
        if (product_name = __getItem('bcproduct_name')) {
            if (product_name == 1)
                $('#product_name').iCheck('check');
            else
                $('#product_name').iCheck('uncheck');
        }

        $(document).on('ifChecked', '#price', function(event) {
            __setItem('bcprice', 1);
        });
        $(document).on('ifUnchecked', '#price', function(event) {
            __setItem('bcprice', 0);
            $('#currencies').iCheck('uncheck');
        });
        if (price = __getItem('bcprice')) {
            if (price == 1)
                $('#price').iCheck('check');
            else
                $('#price').iCheck('uncheck');
        }

        $(document).on('ifChecked', '#currencies', function(event) {
            __setItem('bccurrencies', 1);
        });
        $(document).on('ifUnchecked', '#currencies', function(event) {
            __setItem('bccurrencies', 0);
        });
        if (currencies = __getItem('bccurrencies')) {
            if (currencies == 1)
                $('#currencies').iCheck('check');
            else
                $('#currencies').iCheck('uncheck');
        }

        $(document).on('ifChecked', '#unit', function(event) {
            __setItem('bcunit', 1);
        });
        $(document).on('ifUnchecked', '#unit', function(event) {
            __setItem('bcunit', 0);
        });
        if (unit = __getItem('bcunit')) {
            if (unit == 1)
                $('#unit').iCheck('check');
            else
                $('#unit').iCheck('uncheck');
        }

        $(document).on('ifChecked', '#category', function(event) {
            __setItem('bccategory', 1);
        });
        $(document).on('ifUnchecked', '#category', function(event) {
            __setItem('bccategory', 0);
        });
        if (category = __getItem('bccategory')) {
            if (category == 1)
                $('#category').iCheck('check');
            else
                $('#category').iCheck('uncheck');
        }

        $(document).on('ifChecked', '#product_image', function(event) {
            __setItem('bcproduct_image', 1);
        });
        $(document).on('ifUnchecked', '#product_image', function(event) {
            __setItem('bcproduct_image', 0);
        });
        if (product_image = __getItem('bcproduct_image')) {
            if (product_image == 1)
                $('#product_image').iCheck('check');
            else
                $('#product_image').iCheck('uncheck');
        }

        $(document).on('ifChecked', '#variants', function(event) {
            __setItem('bcvariants', 1);
        });
        $(document).on('ifUnchecked', '#variants', function(event) {
            __setItem('bcvariants', 0);
        });
        if (variants = __getItem('bcvariants')) {
            if (variants == 1)
                $('#variants').iCheck('check');
            else
                $('#variants').iCheck('uncheck');
        }

        $(document).on('ifChecked', '.checkbox', function(event) {
            var item_id = $(this).attr('data-item-id');
            var vt_id = $(this).attr('id');
            bcitems[item_id]['selected_variants'][vt_id] = 1;
            __setItem('bcitems', JSON.stringify(bcitems));
        });
        $(document).on('ifUnchecked', '.checkbox', function(event) {
            var item_id = $(this).attr('data-item-id');
            var vt_id = $(this).attr('id');
            bcitems[item_id]['selected_variants'][vt_id] = 0;
            __setItem('bcitems', JSON.stringify(bcitems));
        });

        $(document).on('click', '.del', function () {
            var id = $(this).attr('id');
            delete bcitems[id];
            __setItem('bcitems', JSON.stringify(bcitems));
            $(this).closest('#row_' + id).remove();
        });

        $('#reset').click(function (e) {

            bootbox.confirm(lang.r_u_sure, function (result) {
                if (result) {
                    if (__getItem('bcitems')) {
                        __removeItem('bcitems');
                    }
                    if (__getItem('bcstyle')) {
                        __removeItem('bcstyle');
                    }
                    if (__getItem('bcsite_name')) {
                        __removeItem('bcsite_name');
                    }
                    if (__getItem('bcproduct_name')) {
                        __removeItem('bcproduct_name');
                    }
                    if (__getItem('bcprice')) {
                        __removeItem('bcprice');
                    }
                    if (__getItem('bccurrencies')) {
                        __removeItem('bccurrencies');
                    }
                    if (__getItem('bcunit')) {
                        __removeItem('bcunit');
                    }
                    if (__getItem('bccategory')) {
                        __removeItem('bccategory');
                    }
                    // if (__getItem('cf_width')) {
                    //     __removeItem('cf_width');
                    // }
                    // if (__getItem('cf_height')) {
                    //     __removeItem('cf_height');
                    // }
                    // if (__getItem('cf_orientation')) {
                    //     __removeItem('cf_orientation');
                    // }

                    $('#modal-loading').show();
                    window.location.replace("<?= site_url('products/print_barcodes'); ?>");
                }
            });
        });

        var old_row_qty;
        $(document).on("focus", '.quantity', function () {
            old_row_qty = $(this).val();
        }).on("change", '.quantity', function () {
            var row = $(this).closest('tr');
            if (!is_numeric($(this).val())) {
                $(this).val(old_row_qty);
                bootbox.alert(lang.unexpected_value);
                return;
            }
            var new_qty = parseFloat($(this).val()),
            item_id = row.attr('data-item-id');
            bcitems[item_id].qty = new_qty;
            __setItem('bcitems', JSON.stringify(bcitems));
            loadItems();
        });

    });

    function add_product_item(item) {
        ac = true;
        if (item == null) {
            return false;
        }
        // console.log(item.variants);
        var rounded = item.id;

        $(".rid").each(function () {
            var rid = $(this).val();
            row = $(this).closest('tr');
            var opt = row.find('.roption').val();
            if ((parseFloat(rid) === parseFloat(item.item_id) && parseFloat(opt) === parseFloat(item.variants)) || (parseFloat(rid) === parseFloat(item.item_id) && item.variants === false)) {
                rounded = row.find('.count').val();
            }
        });

        var item_id = site.settings.item_addition == 1 ? rounded : item.id;

        if (bcitems[item_id]) {
            bcitems[item_id].qty = parseFloat(bcitems[item_id].qty) + 1;
        } else {
            bcitems[item_id] = item;
            bcitems[item_id]['selected_variants'] = {};
            $.each(item.variants, function () {
                bcitems[item_id]['selected_variants'][this.id] = 1;
            });
        }

        __setItem('bcitems', JSON.stringify(bcitems));
        loadItems();
        return true;

    }

    function loadItems () {

        if (__getItem('bcitems')) {
            $("#bcTable tbody").empty();
            bcitems = JSON.parse(__getItem('bcitems'));

            $.each(bcitems, function () {
                var item = this;

                var row_no = item.id;
                var vd = '';
                var item_id = site.settings.item_addition == 1 ? item.id : item.id;

                var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');
                tr_html = '<td><input name="product[]" type="hidden" value="' + item_id + '"><input name="product_id[]" type="hidden" value="' + item.pro_id + '"><span id="name_' + row_no + '">' + item.name + ' (' + item.code + ')</span><input type="hidden" class="count" value="' + item_id + '"><input type="hidden" class="rid" value="' + item.pro_id + '"></td>';
                tr_html += '<td><input class="form-control quantity text-center" name="quantity[]" type="text" value="' + formatDecimal(item.qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
                if(item.variants) {
                    $.each(item.variants, function () {
                        vd += '<input name="vars[]" type="checkbox" class="checkbox" id="' + this.id + '" data-item-id="' + item_id + '" value="' + this.id + '" ' + (item.selected_variants[this.id] == 1 ? 'checked="checked"' : '') + ' style="display:inline-block;" /><input type="hidden" class="roption" value="' + this.id + '"><label for="' + this.id + '" class="padding05">' + this.name + '</label>';
                    });
                }

                tr_html += '<td>'+vd+'</td>';
                tr_html += '<td class="text-center"><i class="fa fa-times tip del" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
                newTr.html(tr_html);
                newTr.prependTo("#bcTable");
            });
            $('input[type="checkbox"],[type="radio"]').not('.skip').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
            return true;
        }
    }

</script>