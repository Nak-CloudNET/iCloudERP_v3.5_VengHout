<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_customer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-customer-form');
		if($sale){
        echo form_open_multipart("customers/add/".$sale, $attrib);

		}else{
			echo form_open_multipart("customers/add", $attrib);

		}		?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label"
                       for="customer_group"><?php echo $this->lang->line("default_customer_group"); ?></label>
                <span class="text-danger" id="message"></span>

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
					<div class="form-group">
						<label class="control-label"
							   for="price_group"><?php echo $this->lang->line("price_groups"); ?></label>

						<div class="controls"> <?php
							$pr_group[''] = 'No Price Group';
							foreach ($price_groups as $price_group) {
								$pr_group[$price_group->id] = $price_group->name;
							}
							echo form_dropdown('price_group', $pr_group, '', 'class="form-control tip select" id="price_groups" style="width:100%;" placeholder="Select Price Group" ');
							?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group"> <?php
						echo lang("group_area", "group_area");
						$ga_group[""] = "Select Group Area";
						foreach ($group_areas as $group_area) {
							$ga_group[$group_area->areas_g_code] = $group_area->areas_group;
						}
						echo form_dropdown('group_area', $ga_group, '', 'class="form-control tip select" id="group_area" style="width:100%;" placeholder="' . lang("select") . ' ' . lang("group_area") . '" ');
						?>
					</div>
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
					<div class="form-group person">
                        <?= lang("name_kh", "name_kh"); ?>
                        <?php echo form_input('name_kh', '', 'class="form-control tip" id="name_kh"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("vat_no", "vat_no"); ?>
                        <?php echo form_input('vat_no', '', 'class="form-control" id="vat_no"'); ?>
                    </div>
                    <!--<div class="form-group company">
						<?= lang("contact_person", "contact_person"); ?>
						<?php echo form_input('contact_person', '', 'class="form-control" id="contact_person" data-bv-notempty="true"'); ?>
					</div>-->
					
                    <div class="form-group">
                        <?= lang("email_address", "email_address"); ?>
                        <input type="email" name="email" class="form-control" id="email_address"/>
                    </div>
                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
						<?php echo form_input('phone', '', 'class="form-control" id="phone" type="tel" data-bv-notempty="true" '); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("address", "address"); ?> <span style="float:right;"><button class="btn btn-sm btn-primary add_more">Add More</button></span>
                        <?php echo form_input('address', '', 'class="form-control" id="address" data-bv-notempty="true"'); ?>
                    </div>
					<div id="address_show" style="display:none;">
						<div class="form-group">
							<?= lang("address1", "address1"); ?> 
							<?php echo form_input('address1', '', 'class="form-control" id="address1" '); ?>
						</div>
						<div class="form-group">
							<?= lang("address2", "address2"); ?> 
							<?php echo form_input('address2', '', 'class="form-control" id="address2" '); ?>
						</div>
						<div class="form-group">
							<?= lang("address3", "address3"); ?> 
							<?php echo form_input('address3', '', 'class="form-control" id="address3" '); ?>
						</div>
						<div class="form-group">
							<?= lang("address4", "address4"); ?> 
							<?php echo form_input('address4', '', 'class="form-control" id="address4" '); ?>
						</div>
						<div class="form-group">
							<?= lang("address5", "address5"); ?> 
							<?php echo form_input('address5', '', 'class="form-control" id="address5" '); ?>
						</div>
					</div>
					<div class="form-group">
                        <?= lang("village", "village"); ?>
                        <?php echo form_input('village', '', 'class="form-control" id="village"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("district", "district"); ?>
                        <?php echo form_input('district', '', 'class="form-control" id="district"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("state", "state"); ?>
                        <?php echo form_input('state', '', 'class="form-control" id="state"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("country", "country"); ?>
                        <?php echo form_input('country', '', 'class="form-control" id="country"'); ?>
                    </div>
					<?php if($setting->credit_limit == 1) {?>
					 <div class="form-group">
                        <?= lang("credit_limit", "credit_limit"); ?>
                        <?php echo form_input('credit_limit', '', 'class="form-control" id="credit_limit"'); ?>
                    </div>
					<?php } ?>
					
					<div class="form-group"> <?php
						echo lang("public_charge", "public_charge");
						//$pub_c[""] = "Select Public Charge";
						foreach ($public_charge as $pub_ch) {
							$pub_c[$pub_ch->id] = $pub_ch->description;
						}
						echo form_dropdown('public_charge', $pub_c, '', 'class="form-control tip select" id="public_charge" multiple="multiple" style="width:100%;" placeholder="' . lang("select") . ' ' . lang("public_charge") . '" ');
						?>
					</div>
				
                </div>
                <div class="col-md-6">
                    <!-- <div class="form-group">
                        <?= lang("postal_code", "postal_code"); ?>
                        <?php echo form_input('postal_code', '', 'class="form-control" id="postal_code"'); ?>
                    </div> -->
                    <div class="form-group company">
                        <?= lang("company", "company"); ?>
                        <?php echo form_input('company', '', 'class="form-control tip" id="company" '); ?>
                    </div>
					<div class="form-group">
                        <?= lang("marital_status", "status"); ?>
                        <?php
                        $status[""] = "Select Marital Status";
                        $status['single'] = "Single";
                        $status['married'] = "Married";
                        echo form_dropdown('status', $status, isset($customer->status)?$customer->status:'', 'class="form-control select" id="status" placeholder="' . lang("select") . ' ' . lang("Marital Status") . '" style="width:100%"')
                        ?>
                    </div>
					
                    <div class="form-group">
                        <?= lang("gender", "gender"); ?>
                        <?php
                        $gender[""] = "Select Gender";
                        $gender['male'] = "Male";
                        $gender['female'] = "Female";
                        echo form_dropdown('gender', $gender, isset($customer->gender)?$customer->gender:'', 'class="form-control select" id="gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%"')
                        ?>
                    </div>
					
					<div class="form-group">
                        <?= lang("identify_number", "cf1"); ?>
                        <?php echo form_input('cf1', isset($customer->cf1)?$customer->cf1:'', 'class="form-control" id="cf1"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("identify_date", "identify_date"); ?>
                        <?php echo form_input('identify_date', isset($customer->identify_date)?$customer->identify_date:'', 'class="form-control date" id="identify_date"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("attachment", "cf4"); ?><input class="file" id="attachment" type="file" name="userfile[]" multiple data-show-upload="true" data-show-upload="true" data-show-preview="true"
                       class="file">

                    </div>
                    <div class="form-group">
                        <?= lang("date_of_birth", "cf5"); ?> <?= lang("Ex: YYYY-MM-DD"); ?>
                        <?php echo form_input('date_of_birth', isset($customer->date_of_birth)?date('d-m-Y', strtotime($customer->date_of_birth)):'', 'class="form-control date" id="datepicker date_of_birth"'); ?>

                    </div>
                    <!--
					<div class="form-group">
                        <?= lang("start_date", "cf5"); ?> <?= lang("Ex: YYYY-MM-DD"); ?>
                        <?php echo form_input('start_date', isset($customer->start_date)?date('d-m-Y', strtotime($customer->start_date)):'', 'class="form-control date" id="datepicker start_date"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("end_date", "cf5"); ?> <?= lang("Ex: YYYY-MM-DD"); ?>
                        <?php echo form_input('end_date', isset($customer->end_date)?date('d-m-Y', strtotime($customer->end_date)):'', 'class="form-control date" id="datepicker end_date"'); ?>
                    </div>
					-->
					<div class="form-group">
                        <?= lang("street_no", "street_no"); ?>
                        <?php echo form_input('street', '', 'class="form-control" id="street"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("sangkat", "sangkat"); ?>
                        <?php echo form_input('sangkat', '', 'class="form-control" id="sangkat"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("city", "city"); ?>
                        <?php echo form_input('city', '', 'class="form-control" id="city"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("postal_code", "postal_code"); ?>
                        <?php echo form_input('postal_code', isset($customer->postal_code)?$customer->postal_code:'', 'class="form-control" id="postal_code"'); ?>
					</div>
					<div class="form-group">
                        <?= lang("note", "note"); ?>
                        <?php echo form_textarea('note', '', 'class="form-control skip" id="note" style="height:115px;"'); ?>
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

	$('body').on('click', '.add_more', function(e) {
		  e.preventDefault();
		$("#address_show").toggle();
	});

    $('#customer_group').on('change', function(e) {
        e.preventDefault();
        var makeup_cost=$('#customer_group').val();
        $.ajax({
            url: '<?= base_url() ?>customers/makeupCost/'+makeup_cost,
            dataType: 'json',
            success: function(result){
                $.each(result, function(i,val){
                    var cost = val.makeup_cost;
                    if(cost==1){
                        var option=$('#price_groups option:first-child').val();
                        $("#price_groups").select2("val", option);
                        $("#price_groups").select2("readonly", true);
                        $('#message').html('  This customer group has makeup cost.So, can not select price groups !');
                    }
                    else{
                        $("#price_groups").select2("readonly", false);
                        $('#message').html('');
                    }
                });
            }
        });
    });


});
</script>