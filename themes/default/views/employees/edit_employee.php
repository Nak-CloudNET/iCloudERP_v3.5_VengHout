<?php
	//$this->erp->print_arrays($employee); 
?>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('edit_employee'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('edit_employee'); ?></p>
                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo form_open("employees/edit_employee", $attrib);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="form-group">
                                <?php echo lang('name', 'name'); ?>
                                <div class="controls">
                                    <?php echo form_input('name', $employee->name?$employee->name:null, 'class="form-control" id="name" required="required" pattern=".{3,10}"'); ?>
									<input type="hidden" value="<?=$employee->id?>" id="employee_id" name="employee_id">
								</div>
                            </div>
                            <div class="form-group">
                                <?php echo lang('name_kh', 'name_kh'); ?>
                                <div class="controls">
                                    <?php echo form_input('name_kh', $employee->name_kh?$employee->name_kh:null, 'class="form-control" id="name_kh" required="required"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
								<?= lang('gender', 'gender'); ?>							
								<?php
									$selected = ($employee->gender) ? $employee->gender : 'male';  
									$gender = array("male" => "Male", "female" => "Female");
								?>
                                <?php
                                echo form_dropdown('gender', $gender, $selected, 'class="tip form-control" id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" required="required"');
                                ?>
                            </div>
							
							<div class="form-group">
                                <?php echo lang('position', 'position'); ?>
                                <div class="controls">
                                    <?php echo form_input('position',$employee->position?$employee->position:null, 'class="form-control" id="position" required="required"'); ?>
                                </div>
                            </div>
							
							<!--
                            <div class="form-group">
                                <?php echo lang('username', 'username'); ?>
                                <div class="controls">
                                    <input type="text" id="username" name="username" class="form-control"
                                           required="required" pattern=".{4,20}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo lang('password', 'password'); ?>
                                <div class="controls">
                                    <?php echo form_password('password', '', 'class="form-control tip" id="password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-regexp-message="'.lang('pasword_hint').'"'); ?>
                                    <span class="help-block"><?= lang('pasword_hint') ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('confirm_password', 'confirm_password'); ?>
                                <div class="controls">
                                    <?php echo form_password('confirm_password', '', 'class="form-control" id="confirm_password" required="required" data-bv-identical="true" data-bv-identical-field="password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>
                                </div>
                            </div>
							-->

                        </div>
                        <div class="col-md-5 col-md-offset-1">
							
							<div class="form-group">
                                <?php echo lang('company', 'company'); ?>
                                <div class="controls">
                                    <?php echo form_input('company',$employee->company?$employee->company:null, 'class="form-control" id="company" required="required"'); ?>
                                </div>
                            </div>
							<div class="form-group">
                                <?php echo lang('company_kh', 'company_kh'); ?>
                                <div class="controls">
                                    <?php echo form_input('company_kh', $employee->company_kh?$employee->company_kh:null, 'class="form-control" id="company_kh" required="required"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('phone', 'phone'); ?>
                                <div class="controls">
                                    <?php echo form_input('phone', $employee->phone?$employee->phone:null, 'class="form-control" id="phone" required="required"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('email', 'email'); ?>
                                <div class="controls">
                                    <?php  echo form_input('email', $employee->email?$employee->email:null, 'class="form-control" id="email" required="required"'); ?>
                                </div>
                            </div>
						
							<!--
                            <div class="form-group">
                                <?= lang('status', 'status'); ?>
                                <?php
                                $opt = array(1 => lang('active'), 0 => lang('inactive'));
                                echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : ''), 'id="status" required="required" class="form-control select" style="width:100%;"');
                                ?>
                            </div>
                            <div class="form-group">
                                <?= lang("group", "group"); ?>
                                <?php
                                foreach ($groups as $group) {
                                    if ($group['name'] != 'customer' && $group['name'] != 'supplier') {
                                        $gp[$group['id']] = $group['name'];
                                    }
                                }
                                echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : ''), 'id="group" required="required" class="form-control select" style="width:100%;"');
                                ?>
                            </div>
							

                            <div class="clearfix"></div>
                            <div class="no">
                                <div class="form-group">
                                    <?= lang("biller", "biller"); ?>
                                    <?php
                                    $bl[""] = lang('select').' '.lang('biller');
                                    foreach ($billers as $biller) {
                                        $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                    }
                                    echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ''), 'id="biller" class="form-control select" style="width:100%;"');
                                    ?>
                                </div>
                                <div class="form-group">
                                    <?= lang("warehouse", "warehouse"); ?>
                                    <?php
                                    /* $wh[''] = lang('select').' '.lang('warehouse');
                                    foreach ($warehouses as $warehouse) {
                                        $wh[$warehouse->id] = $warehouse->name;
                                    } */
                                    echo form_dropdown('warehouse', 'Please select Project', (isset($_POST['warehouse']) ? $_POST['warehouse'] : ''), 'id="warehouse" class="form-control select" placeholder="Please select Project" style="width:100%;" ');
                                    ?>
                                </div>

                                <div class="form-group">
                                    <?= lang("view_right", "view_right"); ?>
                                    <?php
                                    $vropts = array(1 => lang('all_records'), 0 => lang('own_records'));
                                    echo form_dropdown('view_right', $vropts, (isset($_POST['view_right']) ? $_POST['view_right'] : 1), 'id="view_right" class="form-control select" style="width:100%;"');
                                    ?>
                                </div>
                                <div class="form-group">
                                    <?= lang("edit_right", "edit_right"); ?>
                                    <?php
                                    $opts = array(1 => lang('yes'), 0 => lang('no'));
                                    echo form_dropdown('edit_right', $opts, (isset($_POST['edit_right']) ? $_POST['edit_right'] : 0), 'id="edit_right" class="form-control select" style="width:100%;"');
                                    ?>
                                </div>
                                <div class="form-group">
                                    <?= lang("allow_discount", "allow_discount"); ?>
                                    <?= form_dropdown('allow_discount', $opts, (isset($_POST['allow_discount']) ? $_POST['allow_discount'] : 0), 'id="allow_discount" class="form-control select" style="width:100%;"'); ?>
                                </div>
                            </div>
							
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="checkbox" for="notify">
                                        <input type="checkbox" name="notify" value="1" id="notify" checked="checked"/>
                                        <?= lang('notify_user_by_email') ?>
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                            </div>
							-->

                        </div>
                    </div>
                </div>

                <p><?php echo form_submit('add_employee', lang('add_employee'), 'class="btn btn-primary"'); ?></p>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="utf-8">
	var $biller = $("#biller");
	$(window).load(function(){
		billerChange();
	});
    $(document).ready(function () {
        $('.no').slideUp();
        $('#group').change(function (event) {
            var group = $(this).val();
            if (group == 1 || group == 2) {
                $('.no').slideUp();
            } else {
                $('.no').slideDown();
            }
        });
		
    });
	
	$biller.change(function(){
		billerChange();
	});
	
	function billerChange(){
			var id = $biller.val();
			$("#warehouse").empty();
			$.ajax({
				url: '<?= base_url() ?>auth/getWarehouseByProject/'+id,
				dataType: 'json',
				success: function(result){
					if(result){
						$.each(result, function(i,val){
							var id = val.id;
							var name = val.name;
							var opt = '<option value="' + id + '">' + name + '</option>';
							$("#warehouse").append(opt);
							$("#warehouse").prop("selectedIndex", 0)
						});
					}else{
						getWarehouses();
					}
				}
			});
			
	}
	function getWarehouses(){
			$.ajax({
				url: '<?= base_url() ?>auth/getWarehousesAjax/',
				dataType: 'json',
				success: function(result){
					if(result){
						$.each(result, function(i,val){
							var id = val.warehouse_id;
							var name = val.name;
							
							var opt = '<option value="' + id + '">' + name + '</option>';
							
							$("#warehouse").append(opt);
							$("#warehouse").prop("selectedIndex", 0)
						});
					}else{
						alert("Error while ajax request!");
					}
				}
			});	
		}
	
</script>
