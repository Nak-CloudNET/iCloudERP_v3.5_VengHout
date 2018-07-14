<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('create_define_frequency'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("system_settings/create_define_frequency", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <?= lang("Description", "Description"); ?></label>
                <?php echo form_input('description', '', 'class="form-control" id="description" required="required"'); ?>
            </div>

            <div class="form-group">
                <?= lang("Day", "Day"); ?></label>
                <?php echo form_input('day', '', 'class="form-control" id="day" required="required"'); ?>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('create_define_frequency', lang('save'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
