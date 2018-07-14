<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_marchine'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("jobs/edit_marchine", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
			<div class="row">
				<input type="hidden" value="<?= $marchine_id;?>" name="id_marchine">
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("marchine_name", "marchine_name"); ?>
						<?php echo form_input('marchine_name', $marchine->name, 'class="form-control" id="marchine_name" required="required"'); ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("marchine_type", "marchine_type"); ?>
						<?php
							$mt[""] = "";
							$marchines = array('Marchine Copy', 'Marchine Print', 'Marchine Photo');
							foreach ($marchines as $marc) {
								$mt[$marc] = $marc;
							}
							echo form_dropdown('type', $mt, $marchine->type, 'class="form-control" id="marchine" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("marchine") . '"');
						?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("shop", "shop"); ?>
						<?php
							$getBiller = array(""=>"");
							foreach($biller as $biller_id){
								$getBiller[$biller_id->id] = $biller_id->company;
							}
							echo form_dropdown('biller', $getBiller, $marchine->biller_id, 'id="account_section" class="form-control input-tip select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("shop"). '" style="width:100%;" ');
						?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<?= lang("description", "description"); ?>
						<?php echo form_input('description', $marchine->description, 'class="form-control" id="description"'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= lang("status", "status"); ?>
						<?php
							$st[""] = "";
							$status = array('Yes'=>1, 'No'=>0);
							foreach ($status as $sta => $val) {
								$st[$val] = $sta;
							}
							echo form_dropdown('status', $st, $marchine->status, 'class="form-control" id="status" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("status") . '"');
						?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= lang("13", "13"); ?>
						<?php echo form_input('13s', $marchine->first, 'class="form-control" id="13s"'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= lang("15", "15"); ?>
						<?php echo form_input('15s', $marchine->second, 'class="form-control" id="15s"'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= lang("25", "25"); ?>
						<?php echo form_input('25s', $marchine->third, 'class="form-control" id="25s"'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= lang("30", "30"); ?>
						<?php echo form_input('30s', $marchine->fourth, 'class="form-control" id="30s"'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= lang("50", "50"); ?>
						<?php echo form_input('50s', $marchine->sixth, 'class="form-control" id="50s"'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= lang("60", "60"); ?>
						<?php echo form_input('60s', $marchine->seventh, 'class="form-control" id="60s"'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= lang("76", "76"); ?>
						<?php echo form_input('76s', $marchine->eighth, 'class="form-control" id="76s"'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= lang("80", "80"); ?>
						<?php echo form_input('80s', $marchine->nineth, 'class="form-control" id="80s"'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= lang("100", "100"); ?>
						<?php echo form_input('100s', $marchine->tenth, 'class="form-control" id="100s"'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= lang("120", "120"); ?>
						<?php echo form_input('120s', $marchine->eleven, 'class="form-control" id="120s"'); ?>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<?= lang("150", "150"); ?>
						<?php echo form_input('150s', $marchine->tween, 'class="form-control" id="150s"'); ?>
					</div>
				</div>
			</div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_marchine', lang('add_marchine'), 'class="btn btn-primary" id="checkSave"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>
