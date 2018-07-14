<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_currency'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("system_settings/edit_currency/" . $id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label" for="code"><?php echo $this->lang->line("currency_code"); ?></label>

                <div
                    class="controls"> <?php echo form_input('code', $currency->code, 'class="form-control" id="code" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="name"><?php echo $this->lang->line("currency_name"); ?></label>

                <div
                    class="controls"> <?php echo form_input('name', $currency->name, 'class="form-control" id="name" required="required"'); ?> </div>
            </div>
			<div class="form-group">
                <label class="control-label" for="user"><?= lang("In Out"); ?></label>
				<?php
				$in_out = array(
					''  => '',
					'1' => 'In',
					'0' => 'Out'
				);
				echo form_dropdown('in_out', $in_out, $currency->in_out, 'class="form-control" id="in_out" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("in_out") . '"');
				?>
            </div>
            <div class="form-group">
                <label class="control-label" for="rate"><?php echo $this->lang->line("exchange_rate"); ?></label>

                <div
                    class="controls"> <?php echo form_input('rate', $currency->rate, 'class="form-control" id="rate" required="required"'); ?> </div>
            </div>
            <?php if ($default_currency->code != $currency->code) { ?>
                <div class="form-group">
                    <input type="checkbox" value="1" name="auto_update"
                           id="auto_update" <?= $currency->auto_update ? 'checked="checked"' : ''; ?>>
                    <label class="padding-left-10"
                           for="auto_update"><?php echo $this->lang->line("auto_update_rate"); ?></label>
                </div>
            <?php } ?>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_currency', lang('edit_currency'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>