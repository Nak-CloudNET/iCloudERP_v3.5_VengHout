<script src="<?= $assets ?>js/jquery.validate.min.js"></script>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_biller'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("billers/add", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("logo", "biller_logo"); ?>
                        <?php
                        $biller_logos[''] = 'None';
                        foreach ($logos as $key => $value) {
                            $biller_logos[$value] = $value;
                        }
                        echo form_dropdown('logo', $biller_logos, '', 'class="form-control select" id="biller_logo"'); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="logo-con" class="text-center"></div>
                </div>
            </div>
			<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("code", "code"); ?>
                        <?php
                            if (!empty($Settings->project_code_prefix)) {
                                $reference = $reference;
                            } else {
                                $reference = substr($reference, 5);
                            }
                        ?>
                        <?php echo form_input('code', $reference ? $reference : "",'  class="form-control input-tip" data-bv-notempty="true" id="code"'); ?>
                    </div>
                </div>

                <div class="col-md-6">
					<div class="form-group">
                        <?= lang("biller_prefix", "biller_prefix"); ?>
                        <?php echo form_input('biller_prefix', '', 'class="form-control tip" id="biller_prefix"'); ?>
                    </div>
				</div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group company">
                        <?= lang("company", "company"); ?>
                        <?php echo form_input('company', '', 'class="form-control tip" id="company" '); ?>
                    </div>
                    <div class="form-group person">
                        <?= lang("name", "name"); ?>
                        <?php echo form_input('name', '', 'class="form-control tip" id="name" data-bv-notempty="true"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("Business", "Business"); ?>
                        <?php echo form_input('business', '', 'class="form-control" id="business"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("vat_no", "vat_no"); ?>
                        <?php echo form_input('vat_no', '', 'class="form-control" id="vat_no"'); ?>
                    </div>                    
                    <div class="form-group">
                        <?= lang("email_address", "email_address"); ?>
                        <input type="email" name="email" class="form-control" id="email_address"/>
                    </div>
                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
                        <input type="tel" name="phone" class="form-control" id="phone" required="required"/>
                    </div>
                    <div class="form-group">
                        <?= lang("address", "address"); ?>
                        <?php echo form_input('address', '', 'class="form-control" id="address"'); ?>
                    </div> 
					<div class="form-group">
                        <?= lang("Street", "Street"); ?>
                        <?php echo form_input('Street', '', 'class="form-control" id="Street"'); ?>
                    </div>
					
					<div class="form-group">
                        <?= lang("Group", "Group"); ?>
                        <?php echo form_input('group', '', 'class="form-control" id="group"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("Commune", "Commune"); ?>
                        <?php echo form_input('Commune', '', 'class="form-control" id="Commune"'); ?>
                    </div>

					<div class="form-group">
                        <?= lang("city", "city"); ?>
                        <?php echo form_input('city', '', 'class="form-control" id="city"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("country", "country"); ?>
                        <?php echo form_input('country', '', 'class="form-control" id="country"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("postal_code", "postal_code"); ?>
                        <?php echo form_input('postal_code', '', 'class="form-control" id="postal_code"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("wifi_code", "wifi_code"); ?>
                        <?php echo form_input('wifi_code', '', 'class="form-control" id="wifi_code"'); ?>
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("&#6016;&#6098;&#6042;&#6075;&#6040;&#6048;&#6090;&#6075;&#6035;", "cf1"); ?>
                        <?php echo form_input('cf1', '', 'class="form-control" id="cf1"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("&#6024;&#6098;&#6040;&#6084;&#6087;", "cf2"); ?>
                        <?php echo form_input('cf2', '', 'class="form-control" id="cf2"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("&#6050;&#6070;&#6047;&#6096;&#6041;&#6026;&#6098;&#6027;&#6070;&#6035;", "cf4"); ?>
                        <?php echo form_input('cf4', '', 'class="form-control" id="cf4"'); ?>
                    </div>                    
                    <div class="form-group company">
                    <?= lang("contact_person", "contact_person"); ?>
                    <?php echo form_input('contact_person', '', 'class="form-control tip" id="contact_person" '); ?>
                	</div>
                    <div class="form-group">
                        <?= lang("contact_phone", "cf3"); ?>
                        <?php echo form_input('cf3', '', 'class="form-control" id="cf3"'); ?>
                    </div> 
					
                    <div class="form-group" id="cf5_fg">
						<label class="control-label"><?= lang("warehouse", "cf5") ?></label><span id="cf5_span" style="float: right;"></span>
						<div id="cf5_input"> 
						<?php
					
                            foreach ($warehouses as $warehouse) {
                                $wh[$warehouse->id] = $warehouse->name;
                            }
                            echo form_dropdown('cf5[]', $wh,  (isset($_GET['cf5']) ? $_GET['cf5'] : ''), 'id="cf5" class="form-control" multiple="multiple" required ');
							
						?>
						</div>
                    </div>
                    <div class="form-group">
                        <?= lang("invoice_footer", "invoice_footer"); ?>
                        <?php echo form_textarea('invoice_footer', '', 'class="form-control skip" id="invoice_footer" style="height:115px;"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("Village", "Village"); ?>
                        <?php echo form_input('village', '', 'class="form-control" id="village"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("District", "District"); ?>
                        <?php echo form_input('District', '', 'class="form-control" id="District"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("state", "state"); ?>
                        <?php echo form_input('state', '', 'class="form-control" id="state"'); ?>
                    </div>
					<div class="form-group">
						<?= lang("start_date","start_date");?>
						<?php echo form_input('start_date', '', 'class="form-control  date" id="start_date" ');?>
					</div>
					<div class="form-group">
						<?= lang("end_date","end_date");?>
						<?php echo form_input('end_date', '', 'class="form-control  date" id="end_date" ');?>
					</div>
					<div class="form-group">
						<?= lang("period","period");?>
						<?php echo form_input('period', '', 'class="form-control " id="period" ');?>
					</div>
					<div class="form-group">
						<?= lang("amount","amount");?>
						<?php echo form_input('amount','','class="form-control number_only" id="amount" ');?>
					</div>
                    <div class="form-group">
                        <?= lang("beginning_balance","beginning_balance");?>
                        <?php echo form_input('beginning_balance','','class="form-control number_only" id="beginning_balance"');?>
                    </div>
                </div>
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_biller', $this->lang->line('add_biller'), 'class="btn btn-primary test" id="add"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#biller_logo').change(function (event) {
            var biller_logo = $(this).val();
            $('#logo-con').html('<img src="<?=base_url('assets/uploads/logos')?>/' + biller_logo + '" alt="">');
        });
		
		 $(".test").click(function () {
            var cf5 = $("#cf5");
            if (cf5.val() == null) {
				$('#cf5_fg').css('color', 'rgb(174, 13, 13)');
				$('#cf5_input').css('border', '1px solid rgb(174, 13, 13)');
				$('#cf5_span').text('Please select a warehouse!');
				return false;
            }

            return true;

        });

    });
</script>
<?= $modal_js ?>
