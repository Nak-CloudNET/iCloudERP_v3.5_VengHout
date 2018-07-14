<div class="row">

    <div class="col-sm-2">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div style="max-width:200px; margin: 0 auto;">
                    <?=
                    $user->avatar ? '<img alt="" src="' . base_url() . 'assets/uploads/avatars/thumbs/' . $user->avatar . '" class="avatar">' :
                        '<img alt="" src="' . base_url() . 'assets/images/' . $user->gender . '.png" class="avatar">';
                    ?>
                </div>
                <h4><?= lang('login_email'); ?></h4>

                <p><i class="fa fa-envelope"></i> <?= $user->email; ?></p>
            </div>
        </div>
    </div>

    <div class="col-sm-10">

        <ul id="myTab" class="nav nav-tabs">
            <li class=""><a href="#edit" class="tab-grey"><?= lang('edit') ?></a></li>
            <li class=""><a href="#cpassword" class="tab-grey"><?= lang('change_password') ?></a></li>
            <li class=""><a href="#avatar" class="tab-grey"><?= lang('avatar') ?></a></li>
			<li class=""><a href="#product" class="tab-grey"><?= lang('product') ?></a></li>
        </ul>

        <div class="tab-content">
            <div id="edit" class="tab-pane fade in">

                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-edit nb"></i><?= lang('edit_profile'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                                echo form_open('auth/edit_user/' . $user->id, $attrib);
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <?php echo lang('first_name', 'first_name'); ?>
                                                <div class="controls">
                                                    <?php echo form_input('first_name', $user->first_name, 'class="form-control" id="first_name" required="required"'); ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <?php echo lang('last_name', 'last_name'); ?>

                                                <div class="controls">
                                                    <?php echo form_input('last_name', $user->last_name, 'class="form-control" id="last_name" required="required"'); ?>
                                                </div>
                                            </div>
                                            <?php if (!$this->ion_auth->in_group('customer', $id) && !$this->ion_auth->in_group('supplier', $id)) { ?>
                                                <div class="form-group">
                                                    <?php echo lang('company', 'company'); ?>
                                                    <div class="controls">
                                                        <?php echo form_input('company', $user->company, 'class="form-control" id="company" required="required"'); ?>
                                                    </div>
                                                </div>
                                            <?php } else {
                                                echo form_hidden('company', $user->company);
                                            } ?>
                                            <div class="form-group">

                                                <?php echo lang('phone', 'phone'); ?>
                                                <div class="controls">
                                                    <input type="tel" name="phone" class="form-control" id="phone"
                                                           required="required" value="<?= $user->phone ?>"/>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <?= lang('gender', 'gender'); ?>
                                                <div class="controls">  <?php
                                                    $ge[''] = array('male' => lang('male'), 'female' => lang('female'));
                                                    echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : $user->gender), 'class="tip form-control" id="gender" required="required"');
                                                    ?>
                                                </div>
                                            </div>
                                            <?php if (($Owner || $Admin) && $id != $this->session->userdata('user_id')) { ?>
                                            <div class="form-group">
                                                <?= lang('award_points', 'award_points'); ?>
                                                <?= form_input('award_points', set_value('award_points', $user->award_points), 'class="form-control tip" id="award_points"  required="required"'); ?>
                                            </div>
                                            <?php } ?>

                                            <?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
                                                <div class="form-group">
                                                    <?php echo lang('username', 'username'); ?>
                                                    <input type="text" name="username" class="form-control"
                                                           id="username" value="<?= strtolower($user->username) ?>"
                                                           required="required"/>
                                                </div>
                                                <div class="form-group">
                                                    <?php echo lang('email', 'email'); ?>

                                                    <input type="email" name="email" class="form-control" id="email"
                                                           value="<?= $user->email ?>" required="required"/>
                                                </div>
                                                <div class="row">
                                                    <div class="panel panel-warning">
                                                        <div
                                                            class="panel-heading"><?= lang('if_you_need_to_rest_password_for_user') ?></div>
                                                        <div class="panel-body" style="padding: 5px;">
                                                            <div class="col-md-12">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <?php echo lang('password', 'password'); ?>
                                                                        <?php echo form_input($password); ?>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <?php echo lang('confirm_password', 'password_confirm'); ?>
                                                                        <?php echo form_input($password_confirm); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php } ?>

                                        </div>
                                        <div class="col-md-6 col-md-offset-1">
                                            <?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
												<div class="row">
													<div class="panel panel-warning">
														<div class="panel-heading"><?= lang('user_options') ?></div>
														<div class="panel-body" style="padding: 5px;">
															<div class="col-md-12">
																<div class="col-md-12">
																	<div class="form-group">
																		<?= lang('status', 'status'); ?>
																		<?php
																		$opt = array(1 => lang('active'), 0 => lang('inactive'));
																		echo form_dropdown('status', $opt, (isset($_POST['status']) ? $_POST['status'] : $user->active), 'id="status" required="required" class="form-control input-tip select" style="width:100%;"');
																		?>
																	</div>
																	<?php if (!$this->ion_auth->in_group('customer', $id) && !$this->ion_auth->in_group('supplier', $id)) { ?>
																	<div class="form-group">
																		<?= lang("group", "group"); ?>
																		<?php
																		$gp[""] = "";
																		foreach ($groups as $group) {
																			if ($group['name'] != 'customer' && $group['name'] != 'supplier') {
																				$gp[$group['id']] = $group['name'];
																			}
																		}
																		echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : $user->group_id), 'id="group" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("group") . '" required="required" class="form-control input-tip select" style="width:100%;"');
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
				echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $user->biller_id), 'id="biller" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("biller") . '" class="form-control select" style="width:100%;"');
				?>
			</div>

			<div class="form-group">
				<?= lang("warehouse", "warehouse"); ?>
				<div class="selectWarehouse">
					<?php
						echo form_dropdown('warehouse[]', 'Please select Project', (isset($_POST['warehouse']) ? $_POST['warehouse'] : ''), 'id="warehouse" class="form-control select" placeholder="Please select Project" style="width:100%;" multiple="multiple"');
					?>
				</div>
			</div>
			

			
			<div class="form-group">
				<?= lang("view_right", "view_right"); ?>
				<?php
				$vropts = array(1 => lang('all_records'), 0 => lang('own_records'));
				echo form_dropdown('view_right', $vropts, (isset($_POST['view_right']) ? $_POST['view_right'] : $user->view_right), 'id="view_right" class="form-control select" style="width:100%;"');
				?>
			</div>
			<div class="form-group">
				<?= lang("edit_right", "edit_right"); ?>
				<?php
				$opts = array(1 => lang('yes'), 0 => lang('no'));
				echo form_dropdown('edit_right', $opts, (isset($_POST['edit_right']) ? $_POST['edit_right'] : $user->edit_right), 'id="edit_right" class="form-control select" style="width:100%;"');
				?>
			</div>
		<div class="form-group">
			<?= lang("allow_discount", "allow_discount"); ?>
			<?= form_dropdown('allow_discount', $opts, (isset($_POST['allow_discount']) ? $_POST['allow_discount'] : $user->allow_discount), 'id="allow_discount" class="form-control select" style="width:100%;"'); ?>
		</div>
		
		<div class="form-group">
			<?= lang('pos_layout', 'pos_layout'); 
			$plt = array('' => '','0' => lang('standard'), '1' => lang('standard_plus'), '2' => lang('mart'), '3' => lang('mini_mart'), '4' => lang('restaurant'), '5' => lang('restaurant_plus'));
			echo form_dropdown('pos_layout', $plt, $user->pos_layout, 'class="form-control select" id="pos_layout" placeholder="'.lang("pos_layout").'" ');
			?>
		</div>
																		<?php } ?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>

                                            <?php } ?>
                                            <?php echo form_hidden('id', $id); ?>
                                            <?php echo form_hidden($csrf); ?>
                                        </div>
                                    </div>
                                </div>
                                <p><?php echo form_submit('update', lang('update'), 'class="btn btn-primary" id="updateUser"'); ?></p>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="cpassword" class="tab-pane fade">
                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-key nb"></i><?= lang('change_password'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php echo form_open("auth/change_password", 'id="change-password-form"'); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <?php echo lang('old_password', 'curr_password'); ?> <br/>
                                                <?php echo form_password('old_password', '', 'class="form-control" id="curr_password" required="required" pattern="[A-Za-z0-9]{7,}"'); ?>
                                            </div>

                                            <div class="form-group">
                                                <label
                                                    for="new_password"><?php echo sprintf(lang('new_password'), $min_password_length); ?></label>
                                                <br/>
                                                <?php echo form_password('new_password', '', 'class="form-control" id="new_password" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" data-bv-regexp-message="'.lang('pasword_hint').'"'); ?>
                                                <span class="help-block"><?= lang('pasword_hint') ?></span>
                                            </div>

                                            <div class="form-group">
                                                <?php echo lang('confirm_password', 'new_password_confirm'); ?> <br/>
                                                <?php echo form_password('new_password_confirm', '', 'class="form-control" id="new_password_confirm" required="required" data-bv-identical="true" data-bv-identical-field="new_password" data-bv-identical-message="' . lang('pw_not_same') . '"'); ?>

                                            </div>
                                            <?php echo form_input($user_id); ?>
                                            <p><?php echo form_submit('change_password', lang('change_password'), 'class="btn btn-primary"'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<div id="avatar" class="tab-pane fade">
                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-file-picture-o nb"></i><?= lang('change_avatar'); ?></h2>
                    </div>
                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-md-5">
                                    <div style="position: relative;">
                                        <?php if ($user->avatar) { ?>
                                            <img alt=""
                                                 src="<?= base_url() ?>assets/uploads/avatars/<?= $user->avatar ?>"
                                                 class="profile-image img-thumbnail">
                                            <a href="#" class="btn btn-danger btn-xs po"
                                               style="position: absolute; top: 0;" title="<?= lang('delete_avatar') ?>"
                                               data-content="<p><?= lang('r_u_sure') ?></p><a class='btn btn-block btn-danger po-delete' href='<?= site_url('auth/delete_avatar/' . $id . '/' . $user->avatar) ?>'> <?= lang('i_m_sure') ?></a> <button class='btn btn-block po-close'> <?= lang('no') ?></button>"
                                               data-html="true" rel="popover"><i class="fa fa-trash-o"></i></a><br>
                                            <br><?php } ?>
                                    </div>
                                    <?php echo form_open_multipart("auth/update_avatar"); ?>
                                    <div class="form-group">
                                        <?= lang("change_avatar", "change_avatar"); ?>
                                        <input type="file" data-browse-label="<?= lang('browse'); ?>" name="avatar" id="product_image" required="required"
                                               data-show-upload="false" data-show-preview="false" accept="image/*"
                                               class="form-control file"/>
                                    </div>
                                    <div class="form-group">
                                        <?php echo form_hidden('id', $id); ?>
                                        <?php echo form_hidden($csrf); ?>
                                        <?php echo form_submit('update_avatar', lang('update_avatar'), 'class="btn btn-primary"'); ?>
                                        <?php echo form_close(); ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<div id="product" class="tab-pane fade">
                <div class="box">
                    <div class="box-header">
                        <h2 class="blue"><i class="fa-fw fa fa-file-picture-o nb"></i><?= lang('product'); ?></h2>
                    </div>
                    <div class="box-content">
						<?php echo form_open_multipart("auth/update_permission/".$user->id); ?>
                        <div class="table-responsive">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th colspan="2"><?=lang('sales')?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?=lang('product_type')?></td>
										<td>
											<input type="checkbox" value="1" id="sales-standard" class="checkbox" name="sales_standard" <?php echo $p->{'sales_standard'} ? "checked" : ''; ?>><label
                                            for="sales-standard" class="padding05"><?= lang('standard') ?></label>
											<input type="checkbox" value="1" id="sales-combo" class="checkbox" name="sales_combo" <?php echo $p->{'sales_combo'} ? "checked" : ''; ?>><label
                                            for="sales-combo" class="padding05"><?= lang('combo') ?></label>
											<input type="checkbox" value="1" id="sales-digital" class="checkbox" name="sales_digital" <?php echo $p->{'sales_digital'} ? "checked" : ''; ?>><label
                                            for="sales-digital" class="padding05"><?= lang('digital') ?></label>
											<input type="checkbox" value="1" id="sales-service" class="checkbox" name="sales_service" <?php echo $p->{'sales_service'} ? "checked" : ''; ?>><label
                                            for="sales-service" class="padding05"><?= lang('service') ?></label>
										</td>
									</tr>
									<tr>
										<td style="width:20%;"><?=lang('category')?></td>
										<td style="width:80%;">
											<div class="input-group" style="width:100%;">
												<?php
													$cats = '';
													foreach ($cat as $cate) {
														$cats[$cate->id] = $cate->name;
													}
													echo form_dropdown('cate[]', $cats, '', 'id="cate" class="form-control" multiple="multiple"');
												?>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-hover table-striped">
								<thead>
									<tr>
										<th colspan="2">
											<?=lang('purchases')?>
										</th>
									</tr>
									<tr>
										<td><?=lang('product_type')?></td>
										<td>
											<input type="checkbox" value="1" id="purchases-standard" class="checkbox" name="purchases_standard"  <?php echo $p->{'purchase_standard'} ? "checked" : ''; ?>><label
                                            for="purchases-standard" class="padding05"><?= lang('standard') ?></label>
											
											<input type="checkbox" value="1" id="purchases-combo" class="checkbox" name="purchases_combo" <?php echo $p->{'purchase_combo'} ? "checked" : ''; ?>><label
                                            for="purchases-combo" class="padding05"><?= lang('combo') ?></label> 
											
											<input type="checkbox" value="1" id="purchases-digital" class="checkbox" name="purchases_digital" <?php echo $p->{'purchase_digital'} ? "checked" : ''; ?>><label
                                            for="purchases-digital" class="padding05"><?= lang('digital') ?></label> 
											
											<input type="checkbox" value="1" id="purchases-service" class="checkbox" name="purchases_service" <?php echo $p->{'purchase_service'} ? "checked" : ''; ?>><label
                                            for="purchases-service" class="padding05"><?= lang('service') ?></label>
										</td>
									</tr>
									<tr>
										<td style="width:20%;"><?=lang('category')?></td>
										<td style="width:80%;">
											<div class="input-group" style="width:100%;">
												<?php
													$cats = '';
													foreach ($cat as $cate) {
														$cats[$cate->id] = $cate->name;
													}
													echo form_dropdown('pcate[]', $cats, '', 'id="pcate" class="form-control" multiple="multiple"');
												?>
											</div>
										</td>
									</tr>
								</thead>
							</table>
						</div>
						<?php echo form_submit('update_permission', lang('update_permission'), 'class="btn btn-primary"'); ?>
						<?php echo form_close(); ?>
					</div>
                </div>
            </div>
        </div>
    </div>
	
	
	
    <script>
        $(document).ready(function () {
            $('#change-password-form').bootstrapValidator({
                message: 'Please enter/select a value',
                submitButtons: 'input[type="submit"]'
            });
        });
    </script>
	
	
    <?php if ($Owner && $id != $this->session->userdata('user_id')) {
		?>
    <script type="text/javascript" charset="utf-8">
	
		$("#biller").select('val',"<?= isset($user->biller) ?>");	
		var $biller = $("#biller");
		$(window).load(function(){
			billerChange();
		});
		
        $(document).ready(function () {
            $('#group').change(function (event) {
                var group = $(this).val();
                if (group == 1 || group == 2) {
                    $('.no').slideUp();
                } else {
                    $('.no').slideDown();
                }
            });
			
            var group = <?=$user->group_id?>;
            if (group == 1 || group == 2) {
                $('.no').slideUp();
            } 
			else 
			{
                $('.no').slideDown();
            }
			
			$biller.change(function(){
				billerChange();
			});
			
			$(document).on('click', '#updateUser', function(){
                var g = $("#group").val();
                var n = $("#group option:selected").html();
                
                if(n != "owner" && n != "admin"){
    				if($("#warehouse").val() <= 0){
    					bootbox.alert('Please select warehouse!');
    					return false;
    				}
                }
			});
			
        });
		
		function billerChange(){
			var id = $biller.val();
			$("#warehouse").find('option').remove().end().val('');
			$("#wh1").focus();
			
			$.ajax({
				url: '<?= base_url() ?>auth/getWarehouseByProject/'+id,
				dataType: 'json',
				success: function(result){
					if(result)
					{
						var selected = "<?= $user->warehouse_id; ?>",
						    valued = selected.split(","),
							html ="<select name='warehouse[]' placeholder='<?= lang("Please select Project") ?>' class='select form-control warehouse' multiple>";
						$.each(result, function(){
							html += '<option value="' + this.id + '">' + this.name + '</option>';	
						});
							html += "</select>";
						$(".selectWarehouse").html(html),$("select").select2();
						$(".warehouse").select2("val",valued);
					}
					else
					{
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
						});
					}else{
						alert("Error while ajax request!");
					}
				}
			});	
		}
		
    </script>
<?php } ?>
