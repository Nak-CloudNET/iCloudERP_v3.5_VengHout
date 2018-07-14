<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
    var count = 1, an = 1, qaitems = {};
    var type_opt = {'addition': '<?= lang('addition'); ?>', 'subtraction': '<?= lang('subtraction'); ?>'};
    $(document).ready(function () {
        if (__getItem('remove_qals')) {
            if (__getItem('qaitems')) {
                __removeItem('qaitems');
            }
            if (__getItem('qaref')) {
                __removeItem('qaref');
            }
            if (__getItem('qawarehouse')) {
                __removeItem('qawarehouse');
            }
            if (__getItem('qanote')) {
                __removeItem('qanote');
            }
            if (__getItem('qadate')) {
                __removeItem('qadate');
            }
            __removeItem('remove_qals');
        }
        <?php if ($adjustment) { ?>
        __setItem('qadate', '<?= $this->erp->hrld($adjustment->date); ?>');
        __setItem('qaref', '<?= $adjustment->reference_no; ?>');
        __setItem('qawarehouse', '<?= $adjustment->warehouse_id; ?>');
        __setItem('customer', '<?= $adjustment->customer_id; ?>');
        __setItem('qanote', '<?= str_replace(array("\r", "\n"), "", $this->erp->decode_html($adjustment->note)); ?>');
        __setItem('qaitems', JSON.stringify(<?= $adjustment_items; ?>));
        __setItem('remove_qals', '1');
        <?php } ?>
		
        $("#qadate").datetimepicker({
			format: site.dateFormats.js_ldate,
			fontAwesome: true,
			language: 'erp',
			weekStart: 1,
			todayBtn: 1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 0
		}).datetimepicker('update', '<?= $this->erp->hrld($adjustment->date); ?>');
		
        $("#add_item").autocomplete({
            source: function (request, response) {
				$.ajax({
					type: 'get',
					url: '<?= site_url('products/qa_suggestions'); ?>',
					dataType: "json",
					data: {
						term: request.term,
						warehouse_id: $("#qawarehouse").val()
					},
					success: function (data) {
						response(data);
					}
				});
            },
            minLength: 1,
            autoFocus: false,
            delay: 250,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_adjustment_item(ui.item);
                } else {
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('edit_adjustment'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("products/edit_multi_adjustment/".$adjustment->id, $attrib);
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin || $Settings->allow_change_date == 1) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "qadate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : $this->erp->hrld($adjustment->date)), 'class="form-control input-tip datetime" id="qadate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("reference_no", "qaref"); ?>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $adjustment->reference_no), 'class="form-control input-tip" id="qaref" readonly '); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= get_dropdown_project('biller', 'slbiller', $adjustment->biller_id); ?>
                            </div>
                        </div>
						
                        <?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("warehouse", "qawarehouse"); ?>
                                    <?php
                                    $wh[''] = '';
                                    foreach ($warehouses as $warehouse) {
                                        $wh[$warehouse->id] = $warehouse->name;
                                    }
                                    echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $adjustment->warehouse_id), 'id="qawarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" readonly');
                                    ?>
                                </div>
                            </div>
                            <?php } else { ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?= lang("warehouse", "qawarehouse"); ?>
                                        <?php
                                        $whu[''] = '';
                                        foreach ($warehouses_by_user as $warehouse_by_user) {
                                            $whu[$warehouse_by_user->id] = $warehouse_by_user->name;
                                        }
                                        echo form_dropdown('warehouse', $whu, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ''), 'id="qawarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" readonly');
                                        ?>
                                    </div>
                                </div>
                         <?php } ?>


                             <div class="col-md-4">
                                 <div class="form-group">
                                     <?= lang('customer', 'customer'); ?>
                                     <?php
                                     echo form_input('customer', '', 'id="customer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" class="form-control input-tip" style="min-width:100%;"');
                                     ?>
                                 </div>
                             </div>

						 
                         <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("document", "document") ?>
                                <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                         </div>

                        <div class="clearfix"></div>


                        <div class="col-md-12" id="sticker">
                            <div class="well well-sm">
                                <div class="form-group" style="margin-bottom:0;">
                                    <div class="input-group wide-tip">
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <i class="fa fa-2x fa-barcode addIcon"></i></a></div>
                                        <?php echo form_input('add_item', '', 'class="form-control input-lg" id="add_item" placeholder="' . lang("add_product_to_order") . '"'); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="control-group table-group table-responsive">
                                <label class="table-label"><?= lang("products"); ?> *</label>

                                <div class="controls table-controls">
                                    <table id="qaTable" class="table items table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                        <tr>
                                            <th><?= lang("product_name") . " (" . lang("product_code") . ")"; ?></th>
											<?php  if ($Settings->product_expiry) { ?>
												<th class="col-md-2"><?= lang("expiry_date"); ?></th>
											<?php } ?>
											<th class="col-md-1"><?= lang("qoh"); ?></th>
                                            <th class="col-md-2"><?= lang("variant"); ?></th>
                                            <th class="col-md-1"><?= lang("type"); ?></th>
                                            <th class="col-md-1"><?= lang("quantity"); ?></th>
                                            <?php
                                            if ($Settings->product_serial) {
                                                echo '<th class="col-md-4">' . lang("serial_no") . '</th>';
                                            }
                                            ?>
                                            <th style="max-width: 30px !important; text-align: center;">
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

                        <div class="clearfix"></div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <?= lang("note", "qanote"); ?>
                                    <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="qanote" style="margin-top: 10px; height: 100px;"'); ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('edit_adjustment', lang("submit"), 'id="edit_adjustment" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>
<script>
 $(document).ready(function () {
	  $(".select").css("pointer-events","none");
 });
</script>
