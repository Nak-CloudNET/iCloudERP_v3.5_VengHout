<?php

?>

<script type="text/javascript">
    $(document).ready(function () {

        $('#biller_id').change(function () {
            billerChange();
            $("#from_warehouse").select2().empty();
            $("#to_warehouse").select2().empty();
        });
        var $biller = $("#biller_id");
        $(window).load(function () {
            billerChange();
        });

        function billerChange(){
            var id = $biller.val();
            $("#from_warehouse").empty();
            $("#to_warehouse").empty();
            $.ajax({
                url: '<?= base_url() ?>auth/getWarehouseByProject/' + id,
                dataType: 'json',
                success: function (result) {
                    <?php if ($Owner || $Admin) { ?>
                    __setItem('default_warehouse', '<?= $Settings->default_warehouse; ?>');
                    <?php } else { ?>
                    __setItem('default_warehouse', '<?= $default_wh[0] ?>');
                    <?php } ?>
                    var default_warehouse = __getItem('default_warehouse');

                    if (result == null || result == '') {
                        console.log(result);
                    } else {
                        $.each(result, function (i, val) {
                            var b_id = val.id;
                            var code = val.code;
                            var name = val.name;
                            var opt = '<option value="' + b_id + '">' + code + '-' + name + '</option>';
                            $("#from_warehouse").append(opt);
                            $("#to_warehouse").append(opt);
                        });
                    }

                    if (from_warehouse = __getItem('from_warehouse')) {
                        $('#from_warehouse').select2("val", from_warehouse);
                    } else {
                        $("#from_warehouse").select2("val", default_warehouse);
                    }

                }
            });

            $.ajax({
                url: '<?= base_url() ?>sales/getReferenceByProject/to/'+id,
                dataType: 'json',
                success: function(data){
                    $("#ref").val(data);
                    $("#temp_reference_no").val(data);
                }
            });
        }

        var $warehouse = $('#from_warehouse');
        $warehouse.change(function (e) {
            __setItem('from_warehouse', $(this).val());
        });

        if (!__getItem('todate')) {
            $("#todate").datetimepicker({
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
        $(document).on('change', '#todate', function (e) {
            __setItem('todate', $(this).val());
        });
        if (todate = __getItem('todate')) {
            $('#todate').val(todate);
        }

        ItemnTotals();
        $("#add_item").autocomplete({
            source: function (request, response) {
                if (!$('#from_warehouse').val()) {
                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    $('#add_item').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('transfers/suggestions'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#from_warehouse").val()
                    },
                    success: function (data) {
                        response(data);
                        // $('#to_warehouse').select2("readonly", true);
                        // $('#from_warehouse').select2("readonly", true);
                        // $('#biller_id').select2("readonly", true);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {

                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    // if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    if ($('#from_warehouse').val()) {
                        bootbox.alert('<?= lang('no_match_found') ?>', function () {
                            $('#add_item').focus();
                        });
                    } else {
                        bootbox.alert('<?= lang('please_select_warehouse') ?>', function () {
                            $('#add_item').focus();
                        });
                    }
                    $(this).removeClass('ui-autocomplete-loading');
                    // $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>', function () {
                    //$('#add_item').focus();
                    //});
                    $(this).removeClass('ui-autocomplete-loading');
                    // $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_transfer_item(ui.item);
                    if (row)
                        $(this).val('');
                }
            }
        });
        $('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

        var to_warehouse;
        $('#to_warehouse').on("select2-focus", function (e) {
            to_warehouse = $(this).val();
        }).on("select2-close", function (e) {
            if ($(this).val() != '' && $(this).val() == $('#from_warehouse').val()) {
                $(this).select2('val', to_warehouse);
                bootbox.alert('<?= lang('please_select_different_warehouse') ?>');
            }
        });
        var from_warehouse;
        $('#from_warehouse').on("select2-focus", function (e) {
            from_warehouse = $(this).val();
        }).on("select2-close", function (e) {
            if ($(this).val() == '' && $(this).val() == $('#to_warehouse').val()) {
                $(this).select2('val', from_warehouse);
                bootbox.alert('<?= lang('please_select_different_warehouse') ?>');
            }
        });
        $("#ref").attr('readonly', true);
        $('#ref_st').on('ifChanged', function() {
            if ($(this).is(':checked')) {
                $("#ref").attr('readonly', false);
                $("#ref").val("");
            }else{
                $("#ref").prop('disabled', true);
                var temp = $("#temp_reference_no").val();
                $("#ref").val(temp);

            }
        });

    });

</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('transfer_by_csv'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("transfers/transfer_by_csv", $attrib)
                ?>


                <div class="row">
                    <div class="col-lg-12">

                        <?php if ($Owner) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "todate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="todate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("reference_no", "toref"); ?>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $rnumber), 'class="form-control input-tip" id="toref"'); ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("status", "tostatus"); ?>
                                <?php
                                $post = array('completed' => lang('completed'), 'pending' => lang('pending'), 'sent' => lang('sent'));
                                echo form_dropdown('status', $post, (isset($_POST['status']) ? $_POST['status'] : ''), 'id="tostatus" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("status") . '" required="required" style="width:100%;" ');
                                ?>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <?php
                                $default_biller = JSON_decode($this->session->userdata('biller_id'));
                                if ($Owner || $Admin || !$this->session->userdata('biller_id')) {
                                    echo get_dropdown_project('biller', 'biller_id');
                                } else {
                                    echo get_dropdown_project('biller', 'biller_id', $default_biller[0]);
                                }
                                ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="panel panel-warning">
                                <div
                                    class="panel-heading"><?= lang('please_select_these_before_adding_product') ?></div>
                                <div class="panel-body" style="padding: 5px;">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("from_warehouse", "from_warehouse"); ?>
                                            <?php /*
											$wh[''] = '';
											foreach ($warehouses as $warehouse) {
												$wh[$warehouse->id] = $warehouse->name;
											}
											echo form_dropdown('from_warehouse', $wh, (isset($_POST['from_warehouse']) ? $_POST['from_warehouse'] : ''), 'id="from_warehouse" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("from_warehouse") . '" required="required" style="width:100%;" ');
                                            */ ?>

                                            <?php
                                            if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) {
                                                $wh[""] = "";
                                                foreach ($warehouses as $warehouse) {
                                                    $wh[$warehouse->id] = $warehouse->code . '-' . $warehouse->name;
                                                }

                                                echo form_dropdown('from_warehouse', $wh, (isset($_POST['from_warehouse']) ? $_POST['from_warehouse'] : ($Settings->default_warehouse)), 'class="form-control"   required  id="from_warehouse" placeholder="' . lang("select") . ' ' . lang("from_warehouse") . '" style="width:100%"');
                                            } else {

                                                $whu[''] = '';
                                                foreach ($warehouses_by_user as $warehouse_by_user) {
                                                    $whu[$warehouse_by_user->id] = $warehouse_by_user->code . '-' . $warehouse_by_user->name;
                                                }
                                                $default_wh = explode(',', $this->session->userdata('warehouse_id'));
                                                echo form_dropdown('from_warehouse', $whu, (isset($_POST['from_warehouse']) ? $_POST['from_warehouse'] : $default_wh[0]), 'id="from_warehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("from_warehouse") . '" style="width:100%;" ');
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= lang("to_warehouse", "to_warehouse"); ?>
                                            <?php
                                            $wh[''] = '';
                                            foreach ($warehouses as $warehouse) {
                                                $wh[$warehouse->id] = $warehouse->code . '-' . $warehouse->name;
                                            }
                                            echo form_dropdown('to_warehouse', $wh, (isset($_POST['to_warehouse']) ? $_POST['to_warehouse'] : ''), 'id="to_warehouse" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("to_warehouse") . '" required="required" style="width:100%;" ');
                                            ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="col-md-12" id="sticker">
                            <div class="well well-sm">
                                <a href="<?php echo $this->config->base_url(); ?>assets/csv/sample_transfer_products.csv"
                                   class="btn btn-info pull-right"><i class="icon-download icon-white"></i> Download
                                    Sample File</a>
                                <span class="text-warning"><?= lang("csv1"); ?></span><br/><?= lang("csv2"); ?> <span
                                    class="text-info">(<?= lang("product_code"); ?>, <?= lang("quantity"); ?>
                                    , <?= lang("product_variant"); ?>, <?= lang("expiry"); ?>
                                    )</span> <?= lang("csv3"); ?><br>
                                <?= lang('first_2_are_required_other_optional'); ?>

                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("csv_file", "csv_file") ?>
                                <input id="csv_file" type="file" name="userfile" required="required"
                                       data-show-upload="false" data-show-preview="false" class="form-control file">
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <!--
                        <div class="col-md-6">
                            <div class="form-group">
                                <?= lang("document", "document") ?>
                                <input id="document" type="file" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>
                        -->
                        <div class="clearfix"></div>

                        <div class="col-md-12">
                            <div class="from-group">
                                <?= lang("note", "tonote"); ?>
                                <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'id="tonote" class="form-control" style="margin-top: 10px; height: 100px;"'); ?>
                            </div>


                            <div
                                class="from-group"><?php echo form_submit('add_transfer', $this->lang->line("submit"), 'id="add_transfer" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?></div>
                        </div>

                    </div>
                </div>


                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>
