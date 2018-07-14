<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('create_define_principle'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("system_settings/create_define_principle_rate/".$id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			
            <div class="form-group">
                <label><?= lang("period", "period"); ?></label>
                <?php echo form_input('period',$inc+1, 'class="form-control" id="period" required="required"'); ?>
            </div>
			<div class="form-group">
                <label> <?= lang("dateline", "dateline"); ?></label>
                <?php echo form_input('dateline', '', 'class="form-control date" id="dateline" required="required"'); ?>
            </div>
			<div class="form-group">
                <label> <?= lang("principle", "principle"); ?></label>
                <?php echo form_input('value', '', 'class="form-control" id="value" required="required"'); ?>
            </div>
			<div class="form-group">
                 <label><?= lang("remark", "remark"); ?></label>
                <?php echo form_input('remark', '', 'class="form-control" id="remark" '); ?>
            </div>
            <div class="form-group">
			<label> <?= lang("rate", "rate"); ?></label>
                <input type="checkbox" name="rate" value="1">
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('create_define_principle_rate', lang('save'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
