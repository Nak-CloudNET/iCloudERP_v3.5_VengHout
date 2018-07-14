<?php
	//$this->erp->print_arrays($suspend);
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_suspend'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/updateRoom", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			<input type="hidden" value="<?=$id?>" name="id_suspend"/>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group company">
                        <?= lang("floor", "floor"); ?>
						<?php
							echo form_input('floor', $suspend->floor, 'class="form-control" id="floor"');
                        ?>
                    </div>
					<div class="form-group">
                        <?= lang("warehouse", "swarehouse"); ?>
						<?php
							$wh[''] = '';
							foreach ($warehouses as $warehouse) {
								$wh[$warehouse->id] = $warehouse->name;
							}
							echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $suspend->warehouse_id), 'id="swarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');
						?>
                    </div>
					
					<div class="form-group person sub_textbox">
                        <?= lang("people", "people"); ?>
                        <?php 
							echo form_input('people', $suspend->ppl_number, 'class="form-control" id="people" ');
						?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("name", "name"); ?>
                        <?php echo form_input('name', $suspend->name, 'class="form-control" id="name" required="required"'); ?>
                    </div>
                    <div class="form-group">
                        <?= lang("active", "active"); ?>
                        <?php 
							$ssp = array('0' => lang('yes'), '1' => lang('no'));
                            echo form_dropdown('inactive', $ssp, $suspend->inactive, 'class="form-control select" id="inactive" placeholder="' . lang('select_active') . '" ');
						?>
                    </div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<?= lang("description", "description"); ?>
                        
						<?php echo form_textarea('description', $suspend->description, 'class="form-control" id="ponote" style="margin-top: 10px; height: 100px;"'); ?>
					</div>
				</div>
			</div>
        <div class="modal-footer">
            <?php echo form_submit('edit_suspend', lang('edit_suspend'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
<script type="text/javascript">
	$(document).ready(function () {
		$('#account_section').change(function () {
			$(".sub_textbox").show();
			$(".sub_combobox").hide();
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
