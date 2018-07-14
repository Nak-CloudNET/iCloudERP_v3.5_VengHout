<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>, DC = '<?= $default_currency->code ?>', shipping = 0,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0,
        tax_rates = <?php echo json_encode($tax_rates); ?>, poitems = {},
        audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3'),
        audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    $(document).ready(function () {
        <?php if($this->input->get('supplier')) { ?>
        if (!__getItem('poitems')) {
            __setItem('posupplier', <?=$this->input->get('supplier');?>);
        }
        <?php } ?>
        <?php if ($Owner || $Admin) { ?>
        if (!__getItem('podate')) {
            $("#podate").datetimepicker({
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
        $(document).on('change', '#podate', function (e) {
            __setItem('podate', $(this).val());
        });
        if (podate = __getItem('podate')) {
            $('#podate').val(podate);
        }
        <?php } ?>
        $('#extras').on('ifChecked', function () {
            $('#extras-con').slideDown();
        });
        $('#extras').on('ifUnchecked', function () {
            $('#extras-con').slideUp();
        });
    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_payment_by_csv'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-po-form');
                echo form_open_multipart("purchases/payment_by_csv", $attrib)
                ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="clearfix"></div>
                        <div class="well well-sm">
                            <a href="<?php echo $this->config->base_url(); ?>assets/csv/sample_purchase_payments.csv"
                               class="btn btn-primary pull-right"><i class="fa fa-download"></i> Download Sample
                                File</a>
                            <span class="text-warning"><?php echo $this->lang->line("csv1"); ?></span><br>
                            <?php echo $this->lang->line("csv2"); ?> <span 
                                class="text-info">(<?= lang("product_code") . ', ' . lang("net_unit_cost") . ', ' . lang("quantity") . ', ' . lang("warehouse_code") . ', ' . lang("reference_no") . ', ' . lang("date"). ', ' . lang("shop_id"). ', ' . lang("purchase_status"). ', ' . lang("payment_term"). ', ' . lang("payment_status"). ', ' . lang("shipping"). ', ' . lang("order_discount"). ', ' . lang("order_tax"); ?>
                                )</span> <?php echo $this->lang->line("csv3"); ?><br>
                            <?= lang('first_3_are_required_other_optional'); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("csv_file", "csv_file") ?>
                            <input id="csv_file" type="file" name="userfile" required="required"
                                   data-show-upload="false" data-show-preview="false" class="form-control file">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= lang("document", "document") ?>
                            <input id="document" type="file" name="document" data-show-upload="false"
                                   data-show-preview="false" class="form-control file">
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <input type="hidden" name="total_items" value="" id="total_items" required="required"/>
                    
                    </div>
                    <div class="col-md-12">
                        <div
                            class="from-group"><?php echo form_submit('add_pruchase', $this->lang->line("submit"), 'id="add_pruchase" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?></div>
                    </div>
                </div>
            </div>

            <?php echo form_close(); ?>

        </div>

    </div>
</div>
</div>

