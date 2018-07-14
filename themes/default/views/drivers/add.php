<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_driver'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
        echo form_open_multipart("drivers/create_driver", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			
			<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("driver_name", "driver_name"); ?>
                        <?php echo form_input('driver_name', '', 'class="form-control" id="driver_name" required'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("phone", "phone"); ?>
                        <?php echo form_input('phone', '', 'class="form-control" id="phone"'); ?>
                    </div>
                </div>
                <div class="col-md-6">
					<div class="form-group">
                        <?= lang("driver_code", "driver_code"); ?>
                        <?php echo form_input('driver_code', '', 'class="form-control" id="driver_code" required'); ?>
                    </div>
                    
					<div class="form-group">
                        <?= lang("email_address", "email_address"); ?>
                        <input type="email" name="email" class="form-control" id="email_address"/>
                    </div>
					
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_driver', lang('add_driver'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>