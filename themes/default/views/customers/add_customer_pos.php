<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_customer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
		if($sale){
        echo form_open_multipart("customers/add_customer_pos/".$sale, $attrib);

		}else{
			echo form_open_multipart("customers/add_customer_pos", $attrib);

		}		?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label"
                       for="customer_group"><?php echo $this->lang->line("default_customer_group"); ?></label>

                <div class="controls"> <?php
                    foreach ($customer_groups as $customer_group) {
                        $cgs[$customer_group->id] = $customer_group->name;
                    }
                    echo form_dropdown('customer_group', $cgs, $this->Settings->customer_group, 'class="form-control tip select" id="customer_group" style="width:100%;" required="required"');
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
					<?php if($setting->show_company_code == 1) { ?>
					<div class="form-group">
                        <?= lang("code", "code"); ?>
                        <?php
                            if (!empty($Settings->customer_code_prefix)) {
                                $reference = $reference;
                            } else {
                                $reference = substr($reference, 5);
                            }
                        ?>
                        <?php echo form_input('code', $reference ? $reference : "",'class="form-control input-tip" id="code" data-bv-notempty="true"'); ?>
                    </div>
					<?php } ?>
                    <div class="form-group person">
                        <?= lang("name", "name"); ?>
                        <?php echo form_input('name', '', 'class="form-control tip" id="name" data-bv-notempty="true"'); ?>
                    </div>			
                    <div class="form-group company">
                        <?= lang("company", "company"); ?>
                        <?php echo form_input('company', '', 'class="form-control tip" id="company" '); ?>
                    </div>
                </div>
                <div class="col-md-6">                    
                    <div class="form-group">
                        <?= lang("email_address", "email_address"); ?>
                        <input type="email" name="email" class="form-control" id="email_address"/>
                    </div>
                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
						<?php echo form_input('phone', '', 'class="form-control" id="phone" type="tel"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("address", "address"); ?> 
                        <?php echo form_input('address', '', 'class="form-control" id="address"'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_customer', lang('add_customer'), 'class="btn btn-primary" id="addCustomer"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript">
$(document).ready(function(){

    $('#name').focus();

    // Ctrl + S = Save in add customer
    $(window).keypress(function(event) {
        if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19)) return true;
        $('#addCustomer').trigger('click');
        event.preventDefault();
        return false;
    });

	$('body').on('click', '.add_more', function(e) {
		  e.preventDefault();
		$("#address_show").toggle();
	});

});
</script>