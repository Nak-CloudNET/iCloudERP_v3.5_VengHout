<?php
	//$this->erp->print_arrays($biller);
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_biller'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("billers/edit/" . $biller->id, $attrib); ?>
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
                        echo form_dropdown('logo', $biller_logos, $biller->logo, 'class="form-control select" id="biller_logo"'); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="logo-con" class="text-center"><img
                            src="<?= base_url('assets/uploads/logos/' . $biller->logo) ?>" alt=""></div>
                </div>
            </div>
			<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("code", "code"); ?>
                        <?php echo form_input('code', $biller->code, 'class="form-control tip" id="code" data-bv-notempty="true"'); ?>
                    </div>
                </div>

                <div class="col-md-6">
					<div class="form-group">
                        <?= lang("biller_prefix", "biller_prefix"); ?>
                        <?php echo form_input('biller_prefix', $biller->biller_prefix, 'class="form-control tip" id="biller_prefix"'); ?>
                    </div>
				</div>
				
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group company">
                        <?= lang("company", "company"); ?>
                        <?php echo form_input('company', $biller->company, 'class="form-control tip" id="company" '); ?>
                    </div>
                    <div class="form-group person">
                        <?= lang("name", "name"); ?>
                        <?php echo form_input('name', $biller->name, 'class="form-control tip" id="name" required="required"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("Business", "Business"); ?>
                        <?php echo form_input('business', $biller->business_activity, 'class="form-control" id="business"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("vat_no", "vat_no"); ?>
                        <?php echo form_input('vat_no', $biller->vat_no, 'class="form-control" id="vat_no"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("email_address", "email_address"); ?>
                        <input type="email" name="email" class="form-control" id="email_address"
                               value="<?= $biller->email ?>"/>
                    </div>
                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
                        <input type="tel" name="phone" class="form-control" id="phone" required="required"
                               value="<?= $biller->phone ?>"/>
                    </div>
                    <div class="form-group">
                        <?= lang("address", "address"); ?>
                        <?php echo form_input('address', $biller->address, 'class="form-control" id="address" '); ?>
                    </div>
					<div class="form-group">
                        <?= lang("Street", "Street"); ?>
                        <?php echo form_input('Street', $biller->street, 'class="form-control" id="Street"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("Group", "Group"); ?>
                        <?php echo form_input('group', $biller->group, 'class="form-control" id="group"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("Commune", "Commune"); ?>
                        <?php echo form_input('Commune', $biller->sangkat, 'class="form-control" id="Commune"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("city", "city"); ?>
                        <?php echo form_input('city', $biller->city, 'class="form-control" id="city" '); ?>
                    </div>
					<div class="form-group">
                        <?= lang("country", "country"); ?>
                        <?php echo form_input('country', $biller->country, 'class="form-control" id="country"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("postal_code", "postal_code"); ?>
                        <?php echo form_input('postal_code', $biller->postal_code, 'class="form-control" id="postal_code"'); ?>
                    </div> 
                    <div class="form-group">
                        <?= lang("wifi_code", "wifi_code"); ?>
                        <?php echo form_input('wifi_code', $biller->wifi_code, 'class="form-control" id="wifi_code"'); ?>
                    </div>
                </div>
                <div class="col-md-6">
					<div class="form-group">
                        <?= lang("ក្រុមហ៊ុន", "cf1"); ?>
                        <?php echo form_input('cf1', $biller->cf1, 'class="form-control" id="cf1"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("ឈ្មោះ", "cf2"); ?>
                        <?php echo form_input('cf2', $biller->cf2, 'class="form-control" id="cf2"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("អាស័យដ្ឋាន", "cf4"); ?>
                        <?php echo form_input('cf4', $biller->cf4, 'class="form-control" id="cf4"'); ?>
                    </div>
                    <div class="form-group company">
                    <?= lang("contact_person", "contact_person"); ?>
                    <?php echo form_input('contact_person', $biller->contact_person, 'class="form-control" id="contact_person"'); ?>
                	</div>
                    <div class="form-group">
                        <?= lang("contact_phone", "cf3"); ?>
                        <?php echo form_input('cf3', $biller->cf3, 'class="form-control" id="cf3"'); ?>
                    </div>                    
                    <div class="form-group">
						<?php
                            foreach ($warehouses as $warehouse) {
                                $wh[$warehouse->id] = $warehouse->name;
                            }
							echo lang("warehouse", "cf5");
                            echo form_dropdown('cf5[]', $wh, $biller->cf5, 'id="cf5" class="form-control" multiple="multiple"');
						?>
                    </div>
                    <div class="form-group">
                        <?= lang("invoice_footer", "invoice_footer"); ?>
                        <?php echo form_textarea('invoice_footer', $biller->invoice_footer, 'class="form-control skip" id="invoice_footer" style="height:115px;"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("Village", "Village"); ?>
                        <?php echo form_input('village', $biller->village, 'class="form-control" id="village"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("District", "District"); ?>
                        <?php echo form_input('District', $biller->district, 'class="form-control" id="District"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("state", "state"); ?>
                        <?php echo form_input('state', $biller->state, 'class="form-control" id="state"'); ?>
                    </div>
					<div class="form-group">
						<?= lang("start_date","start_date");?>
						<?php echo form_input('start_date',$this->erp->hrsd($biller->start_date), 'class="form-control tip date" id="start_date" ');?>
					</div>
					<div class="form-group">
						<?= lang("end_date","end_date");?>
						<?php echo form_input('end_date',$this->erp->hrsd($biller->end_date), 'class="form-control tip date" id="end_date" ');?>
					</div>
					<div class="form-group">
						<?= lang("period","period");?>
						<?php echo form_input('period', $biller->period, 'class="form-control" id="period" ');?>
					</div>
					<div class="form-group">
						<?= lang("amount","amount");?>
						<?php echo form_input('amount', $this->erp->formatDecimal($biller->amount), 'class="form-control number_only" id="amount" data-bv-notempty="true"');?>
					</div>
                    <div class="form-group">
                        <?= lang("beginning_balance","beginning_balance");?>
                        <?php echo form_input('beginning_balance', $this->erp->formatDecimal($biller->begining_balance),'class="form-control number_only" id="beginning_balance"');?>
                    </div>
                </div>
            </div>


        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_biller', lang('edit_biller'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
		var warehouses = <?php echo json_encode($warehouses); ?>;
		var string = '<?php echo $biller->cf5 ?>';
		var array = string.split(',');

		var warehouse_id = new Array();
		var v = 0;
		
		$.each(warehouses, function(){
			warehouse_id[v] = this.id;
			v++;
		});
		$("#cf5").val(array);


        $('#biller_logo').change(function (event) {
            var biller_logo = $(this).val();
            $('#logo-con').html('<img src="<?=base_url('assets/uploads/logos')?>/' + biller_logo + '" alt="">');
        });
    });
</script>
<?= $modal_js ?>

