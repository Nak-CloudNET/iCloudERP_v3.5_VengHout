<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_customer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("customers/edit/" . $customer->id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label class="control-label"
                       for="customer_group"><?php echo $this->lang->line("default_customer_group"); ?></label>

                <div class="controls"> <?php
                    foreach ($customer_groups as $customer_group) {
                        $cgs[$customer_group->id] = $customer_group->name;
                    }
                    echo form_dropdown('customer_group', $cgs, $customer->customer_group_id, 'class="form-control tip select" id="customer_group" style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
			<div class="form-group">
                <label class="control-label"
                       for="customer_group_price"><?php echo $this->lang->line("customer_group_price"); ?></label>

                <div class="controls"> <?php
                    foreach ($price_groups as $p_g) {
                        $cgs[$p_g->id] = $p_g->name;
                    }
                    echo form_dropdown('customer_group_price', $cgs, $customer->customer_group_id, 'class="form-control tip select" id="customer_group_price" style="width:100%;" required="required"');
                    ?>
                </div>
            </div>
			

            <div class="row">
                <div class="col-md-6">
					<div class="form-group">
                        <?= lang("code", "code"); ?>
                        <?php echo form_input('code', $customer->code, 'class="form-control tip" id="code"  data-bv-notempty="true" readonly="true"'); ?>
                    </div>
                    <div class="form-group company">
                        <?= lang("company", "company"); ?>
                        <?php echo form_input('company', $customer->company, 'class="form-control tip" id="company" required="required"'); ?>
                    </div>
                    <div class="form-group person">
                        <?= lang("name", "name"); ?>
                        <?php echo form_input('name', $customer->name, 'class="form-control tip" id="name" required="required"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("vat_no", "vat_no"); ?>
                        <?php echo form_input('vat_no', $customer->vat_no, 'class="form-control" id="vat_no"'); ?>
                    </div>
                    <!--<div class="form-group company">
                    <?= lang("contact_person", "contact_person"); ?>
                    <?php //echo form_input('contact_person', $customer->contact_person, 'class="form-control" id="contact_person" required="required"'); ?>
                </div> -->
                    <div class="form-group">
                        <?= lang("email_address", "email_address"); ?>
                        <input type="email" name="email" class="form-control" id="email_address"
                               value="<?= $customer->email ?>"/>
                    </div>
                    <div class="form-group">
                        <?= lang("phone", "phone"); ?>
                        <input type="tel" name="phone" class="form-control" id="phone"
                               value="<?= $customer->phone ?>"/>
                    </div>
                    <div class="form-group">
                        <?= lang("address", "address"); ?>
                        <?php echo form_input('address', $customer->address, 'class="form-control" id="address"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("city", "city"); ?>
                        <?php echo form_input('city', $customer->city, 'class="form-control" id="city" "'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("state", "state"); ?>
                        <?php echo form_input('state', $customer->state, 'class="form-control" id="state"'); ?>
                    </div>
					<div class="form-group">
						<?= lang('award_points', 'award_points'); ?>
						<?= form_input('award_points', set_value('award_points', $customer->award_points), 'class="form-control tip" id="award_points"'); ?>
					</div>
                </div>
                <div class="col-md-6">
                    <!-- <div class="form-group">
                        <?= lang("postal_code", "postal_code"); ?>
                        <?php echo form_input('postal_code', $customer->postal_code, 'class="form-control" id="postal_code"'); ?>
                    </div> -->
                    <div class="form-group">
                        <?= lang("country", "country"); ?>
                        <?php echo form_input('country', $customer->country, 'class="form-control" id="country"'); ?>
                    </div>
                    
                    <div class="form-group">
                        <?= lang("postal_code", "postal_code"); ?>
                        <?php echo form_input('postal_code', $customer->postal_code, 'class="form-control" id="postal_code"'); ?>

                    </div>
					
					<div class="form-group">
                        <?= lang("Marital Status", "status"); ?>
                        <?php
                        $status[""] = "Select Status";
                        $status['single'] = "Single";
                        $status['married'] = "Married";
                        echo form_dropdown('status', $status, $customer->status, 'class="form-control select" id="status" placeholder="' . lang("select") . ' ' . lang("status") . '" style="width:100%"')
                        ?>
                    </div>
					
                    <div class="form-group">
                        <?= lang("gender", "gender"); ?>
                        <?php
                        $gender[""] = "Select Gender";
                        $gender['male'] = "Male";
                        $gender['female'] = "Female";
                        echo form_dropdown('gender', $gender, $customer->gender, 'class="form-control select" id="gender" placeholder="' . lang("select") . ' ' . lang("gender") . '" style="width:100%"')
                        ?>
                    </div>
					
					<div class="form-group">
                        <?= lang("Identity Number", "cf1"); ?>
                        <?php echo form_input('cf1', $customer->cf1, 'class="form-control" id="cf1"'); ?>
                    </div>
					
                    <div class="form-group">
                        <?= lang("attachment", "cf4"); ?><input id="attachment" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="file">

                    </div>
                    <div class="form-group">
                        <?= lang("date_of_birth", "cf5"); ?> <?= lang("Ex: YYYY-MM-DD"); ?>
                         <?php echo form_input('date_of_birth', isset($customer->date_of_birth)?date('Y-m-d', strtotime($customer->date_of_birth)):'', 'class="form-control date" id=" date_of_birth"'); ?>
					</div>
                    
					<div class="form-group">
                        <?= lang("start_date", "cf6"); ?> <?= lang("Ex: YYYY-MM-DD"); ?>
                        <?php echo form_input('start_date', isset($customer->start_date)?date('Y-m-d', strtotime($customer->start_date)):'', 'class="form-control date" id=" start_date"'); ?>
                        <!--<a href="javascript:void(0);" class="btn btn-info">Add End Date</a>-->
                    </div>
					<div class="form-group">
                        <?= lang("end_date", "cf7"); ?> <?= lang("Ex: YYYY-MM-DD"); ?>
                        <?php echo form_input('end_date', isset($customer->end_date)?date('Y-m-d', strtotime($customer->end_date)):'', 'class="form-control date" id=" end_date"'); ?>
                    </div>
                </div>
            </div>
            

        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_customer', lang('edit_customer'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
        $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
        $(".date").datetimepicker({
            format: site.dateFormats.js_ldate,
            fontAwesome: true,
            language: 'erp',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0
        });
    });
</script>

