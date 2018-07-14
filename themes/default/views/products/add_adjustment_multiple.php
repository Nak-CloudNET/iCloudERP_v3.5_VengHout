<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript">
    var count = 1, an = 1;
    var type_opt = {'addition': '<?= lang('addition'); ?>', 'subtraction': '<?= lang('subtraction'); ?>'};

    $(document).ready(function () {
        var test = '<?=$this->session->userdata('remove_adjustments');?>';
        //alert(test);
        if(test == '1'){

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
            <?= $this->session->set_userdata('remove_adjustments', '0');?>
        }

        <?php if ($adjustment_items) { ?>
        __setItem('qaitems', JSON.stringify(<?= $adjustment_items; ?>));
        <?php } ?>
        <?php if ($warehouse_id) { ?>
        __setItem('qawarehouse', '<?= $warehouse_id; ?>');
        $('#qawarehouse').select2('readonly', true);
        <?php } ?>

        if (!__getItem('qadate')) {
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
            }).datetimepicker('update', new Date());
        }
        $(document).on('change', '#qadate', function (e) {
            __setItem('qadate', $(this).val());
        });
        if (qadate = __getItem('qadate')) {
            $('#qadate').val(qadate);
        }

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
                    bootbox.alert('<?= lang('item_no_cost') ?>', function () {
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
                    bootbox.alert('<?= lang('item_no_cost') ?>');
                }
            }
        });

        $("#slref").attr('readonly', true);
        $('#ref_st').on('ifChanged', function() {
            if ($(this).is(':checked')) {
                $("#slref").prop('readonly', false);
                $("#slref").val("");
            }else{
                $("#slref").prop('readonly', true);
                var temp = $("#temp_reference_no").val();
                $("#slref").val(temp);

            }
        });
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_product_adjustment'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("products/add_adjustment_multiple", $attrib);
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin || $Settings->allow_change_date == 1) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "qadate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="qadate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="col-md-4">
                            <?= lang("reference_no", "slref"); ?>
                            <div style="float:left;width:100%;">
                                <div class="form-group">
                                    <div class="input-group">
                                        <?php echo form_input('reference_no', $reference? $reference :"",'class="form-control input-tip" id="slref"'); ?>
                                        <input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference?$reference:"" ?>" />
                                        <div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
                                            <input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php
                                $default_biller = JSON_decode($this->session->userdata('biller_id'));
                                if ($Owner || $Admin || !$this->session->userdata('biller_id')) {
                                    echo get_dropdown_project('biller', 'slbiller');
                                } else {
                                    echo get_dropdown_project('biller', 'slbiller', $default_biller[0]);
                                }
                                ?>
                            </div>
                        </div>

                        <?= form_hidden('count_id', $count_id); ?>

                        <?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("warehouse", "qawarehouse"); ?>
                                    <?php
                                    $wh[''] = '';
                                    foreach ($warehouses as $warehouse) {
                                        $wh[$warehouse->id] = $warehouse->name;
                                    }
                                    echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ($warehouse_id ? $warehouse_id :$Settings->default_warehouse)), 'id="qawarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" '.($warehouse_id ? 'readonly' : '').' style="width:100%;"');
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
                                        $whu[$warehouse_by_user->id] = $warehouse_by_user->code .'-'.$warehouse_by_user->name;
                                    }
                                    $default_wh = explode(',', $this->session->userdata('warehouse_id'));
                                    echo form_dropdown('warehouse', $whu, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $default_wh[0]), 'id="qawarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" style="width:100%;" ');
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
                            <div class="fprom-group"><?php echo form_submit('add_adjustment', lang("submit"), 'id="add_adjustment" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></div>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $('#slbiller').change(function(){
        billerChange();
        $("#qawarehouse").select2().empty();
    });
    var $biller = $("#slbiller");
    $(window).load(function(){
        billerChange();
    });

    function billerChange(){
        var id = $biller.val();
        $("#qawarehouse").empty();
        $.ajax({
            url: '<?= base_url() ?>auth/getWarehouseByProject/'+id,
            dataType: 'json',
            success: function(result){
                <?php if ($Owner || $Admin) { ?>
                __setItem('default_warehouse', '<?= $Settings->default_warehouse; ?>');
                <?php } else { ?>
                __setItem('default_warehouse', '<?= $default_wh[0] ?>');
                <?php } ?>
                var default_warehouse = __getItem('default_warehouse');

                if(result == null || result == ''){
                    console.log(result);
                }else{
                    $.each(result, function(i,val){
                        var b_id = val.id;
                        var code = val.code;
                        var name = val.name;
                        var opt = '<option value="' + b_id + '">' +code+'-'+ name + '</option>';
                        $("#qawarehouse").append(opt);
                    });
                }

                $('#qawarehouse').val($('#qawarehouse option:first-child').val()).trigger('change');
                $("#qawarehouse").select2("val", default_warehouse);

                if (default_warehouse) {
                    $('#qawarehouse').select2('val', default_warehouse);
                }
                $('#qawarehouse option[selected="selected"]').each(
                    function() {
                        $(this).removeAttr('selected');
                    }
                );

                if(slwarehouse = __getItem('qawarehouse')){
                    $('#qawarehouse').select2("val", slwarehouse);
                }

            }
        });

        $.ajax({
            url: '<?= base_url() ?>products/getReferenceByProject/qa/'+id,
            dataType: 'json',
            success: function(data){
                $("#slref").val(data);
                $("#temp_reference_no").val(data);
            }
        });

    }

    var $warehouse = $('#qawarehouse');
    $warehouse.change(function (e) {
        __setItem('qawarehouse', $(this).val());
    });

</script>