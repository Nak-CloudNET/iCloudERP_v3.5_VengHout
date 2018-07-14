<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_chart_account_tax'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'addAcc');
        echo form_open_multipart("taxes/add", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group company">
                        <?= lang("account_section", "account_section"); ?>
						<?php
						$acc_section = array(""=>"");
						foreach($sectionacc as $section){
							$acc_section[$section["sectionid"]] = $section["sectionname"];
						}	
							echo form_dropdown('account_section', $acc_section, (isset($_POST['status']) ? $_POST['status'] : ''), 'id="account_section" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("Account") . ' ' . $this->lang->line("Section") . '" required="required" style="width:100%;" ');
                        ?>
                    </div>
                    <div class="form-group">
                        <?= lang("account_name", "account_name"); ?>
                        <?php echo form_input('account_name', '', 'class="form-control" id="account_name" required="required"'); ?>
                    </div>
                    
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("account_code", "account_code"); ?>
                        <?php echo form_input('account_code', '', 'class="form-control" id="account_code" required="required"'); ?>
                    </div>
					<div class="form-group">
                        <?= lang("account_name_kh", "account_name_kh"); ?>
                        <?php echo form_input('account_name_kh', '', 'class="form-control" id="account_name_kh" required="required"'); ?>
                    </div>
				</div>
			</div>
			<div class="modal-footer">
				<?php echo form_submit('add_tax', lang('add_chart_account_tax'), 'class="btn btn-primary" id="add_tax"'); ?>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
	<?= $modal_js ?>
	<script type="text/javascript">
		$(document).ready(function () {
			$("#account_code").live('change', function(){
				var field = $(this).attr('name');
				var code = $(this).val();
				var account_code = $('#account_code');
				$.ajax({
					url: '<?php echo base_url(); ?>account/checkAccount',
					data: {code:code},
					success: function(result){
						if(result == 1){
							account_code.parents('.form-group').addClass('has-error').find('label').addClass('has-error');
							account_code.addClass('text-danger');
							var text = '<p class="text-danger">Account code is already exist!</p>';
							
							account_code.parents('.form-group').append(text);
							
							account_code.focus();
						}else{
							account_code.parents('.form-group').find('p').remove();
						}
					}
				});
			});
			
			$("#add_tax").on('click', function(){
				var checkClassError = $('#account_code').parents('.form-group');
				if(checkClassError.hasClass('has-error')){
					bootbox.alert('<?= lang('accountcode_exist') ?>', function () {
					   $('#account_code').focus();
					});
					return false;
				}
			});
			
			$('#account_section').change(function () {
				var v = $(this).val();
				$('#modal-loading').show();
				if (v) {
					$.ajax({
						type: "get",
						async: false,
						url: "<?= site_url('account/getSubAccount') ?>/" + v,
						dataType: "json",
						success: function (scdata) {
							if (scdata != null) {
								$("#sub_account").select2("destroy").empty().attr("placeholder", "<?= lang('select_subcategory') ?>").select2({
									placeholder: "<?= lang('select_category_to_load') ?>",
									data: scdata
								});
							}
						},
						error: function () {
							bootbox.alert('<?= lang('ajax_error') ?>');
							$('#modal-loading').hide();
						}
					});
				}
				$('#modal-loading').hide();
			});
		});
	</script>
</div>