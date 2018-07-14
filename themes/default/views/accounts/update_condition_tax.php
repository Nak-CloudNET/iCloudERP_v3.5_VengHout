<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('update_condition_tax'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'addAcc');
        echo form_open_multipart("account/edit_condition", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group company">
                        <?= lang("code", "code"); ?>
						<?php
							echo form_input('code', 'Salary', 'class="form-control" id="sub_account" disabled');
                        ?>
						<input type="hidden" value="<?= $id?>" name="id">
                    </div>
                    <div class="form-group person">
                        <?= lang("rate", "rate"); ?>
                        <?php 
							echo form_input('rate', $data->rate, 'class="form-control" id="rate" required="required" ');
						?>
                    </div>
                    
                </div>
                <div class="col-md-6">
                    <div class="form-group person">
                        <?= lang("name", "name"); ?>
                        <?php 
							echo form_input('name', $data->name, 'class="form-control" id="name" required="required"');
						?>
                    </div>
                    <div class="form-group">
                        <?= lang("reduct_tax", "reduct_tax"); ?>
                        <?php echo form_input('reduct_tax', $data->reduct_tax, 'class="form-control" id="reduct_tax" required="required"'); ?>
                    </div>
				</div>
				<div class="col-md-6">
                    <div class="form-group person">
                        <?= lang("min_salary", "min_salary"); ?>
                        <?php 
							echo form_input('min_salary', $data->min_salary, 'class="form-control" id="min_salary" required="required"');
						?>
                    </div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
                        <?= lang("max_salary", "max_salary"); ?>
                        <?php echo form_input('max_salary', $data->max_salary, 'class="form-control" id="max_salary" required="required"'); ?>
                    </div>
				</div>
			</div>
        <div class="modal-footer">
            <?php echo form_submit('add_condition_tax', lang('add_condition_tax'), 'class="btn btn-primary" id="add_condition_tax"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
