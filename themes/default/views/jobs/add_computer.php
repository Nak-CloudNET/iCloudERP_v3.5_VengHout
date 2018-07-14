<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_computer'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("jobs/add_computer", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("marchine_name*", "marchine_name"); ?>
						<?php
							$Marchine_name = array(""=>"");
							foreach($marchine as $marchines){
								$Marchine_name[$marchines->id] = $marchines->name;
							}
							echo form_dropdown('marchine_name', $Marchine_name, '', 'id="account_section" required="required" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("marchine"). '" style="width:100%;" ');
						?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("number", "number"); ?>
						<?php echo form_input('number', '', 'class="form-control" id="number"'); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("date*", "date"); ?>
						<?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : date('d/m/Y h:i')), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>
					</div>
				</div>
			</div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_computer', lang('add_computer'), 'class="btn btn-primary" id="checkSave"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
