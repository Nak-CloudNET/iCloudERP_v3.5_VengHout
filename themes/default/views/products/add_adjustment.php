<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('adjust_quantity').' - '. $product->name .'('.$product->code.')'; ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("products/add_adjustment/" . $product_id, $attrib);
        ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <p style="font-weight: bold;"><?= lang("product_code") . ": " . $product->code . " " . lang("product_name") . ": " . $product->name ?></p>
            <?php if ($Owner || $Admin) { ?>
                <div class="form-group">
                    <?php echo lang('date', 'date'); ?>
                    <div class="controls">
                        <?php echo form_input('date', '', 'class="form-control datetime" id="date" required="required"'); ?>
                    </div>
                </div>
            <?php } ?>
            <?= form_hidden('code', $product->code) ?>
            <?= form_hidden('name', $product->name) ?>
            <div class="form-group">
                <?= lang('type', 'type'); ?>
                <?php $opts = array('addition' => lang('addition'), 'subtraction' => lang('subtraction')); ?>
                <?= form_dropdown('type', $opts, set_value('type', 'subtraction'), 'class="form-control tip" id="type"  required="required"'); ?>
            </div>
            <div class="form-group">
                <label for="quantity"><?php echo $this->lang->line("quantity"); ?></label>

                <div
                    class="controls"> <?php echo form_input('quantity', (isset($_POST['quantity']) ? $_POST['quantity'] : ""), 'class="form-control input-tip number_only" id="quantity" required="required"'); ?> </div>
            </div>
            <?php if (!empty($options)) { ?>
                <div class="form-group">
                    <label for="option"><?php echo $this->lang->line("product_variant"); ?></label>

                    <div class="controls">  <?php
                        $op[''] = '';
                        foreach ($options as $option) {
                            $op[$option->id] = $option->name;
                        }
                        echo form_dropdown('option', $op, (isset($_POST['option']) ? $_POST['option'] : ''), 'id="option" class="form-control input-pop" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("option") . '" required="required"');
                        ?> </div>
                </div>
            <?php } else {
                echo form_hidden('option', 0);
            } ?>
            <div class="form-group">
                <label for="warehouse"><?php echo $this->lang->line("warehouse"); ?></label>

                <div class="controls">  <?php
                    $wh[''] = '';
                    foreach ($warehouses as $warehouse) {
                        $wh[$warehouse->id] = $warehouse->name;
                    }
                    echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $warehouse_id ? $warehouse_id : $Settings->default_warehouse), 'id="warehouse" class="form-control input-pop" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" required="required"');
                    ?> </div>
            </div>
            <div class="form-group">
                <label for="note"><?php echo $this->lang->line("note"); ?></label>

                <div
                    class="controls"> <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note" required="required" style="margin-top: 10px; height: 100px;"'); ?> </div>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('adjust_quantity', lang('adjust_quantity'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
        $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
    });
</script>

