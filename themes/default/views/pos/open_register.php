<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-briefcase"></i><?= lang("open_register"); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
			<?php if($pos_settings->count_cash) { ?>
				<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'open-register-form');
				echo form_open_multipart("pos/open_register", $attrib); ?>
				<div class="col-lg-12">
					<div class="col-sm-3">
						<div class="form-group">
							<?= lang("cash_in_hand_kh", "cash_in_hand_kh"); ?>
							<?php echo form_input('cash_in_hand_kh', '', 'class="form-control input-tip" id="cash_in_hand_kh"'); ?>
						</div>
					</div>
				</div>

				<div class="col-lg-12">
					<div class="col-sm-3">
						<div class="form-group">
							<?= lang("cash_in_hand_us", "cash_in_hand_us"); ?>
							<?php echo form_input('cash_in_hand_us', '', 'class="form-control input-tip" id="cash_in_hand_us"'); ?>
						</div>
					</div>
				</div>
				
				<div class="col-lg-12">
					<div class="col-sm-3">                    
						<div class="form-group">
							<?= lang('cash_in_hand', 'cash_in_hand') ?>
							<?= form_input('cash_in_hand', '', 'id="cash_in_hand" class="form-control" readonly="readonly"'); ?>
						</div>
						<?php echo form_submit('open_register', lang('open_register'), 'class="btn btn-primary"'); ?>
						<?php echo form_close(); ?>
						<div class="clearfix"></div>
					</div>
				</div>
			<?php }else{ ?>
				<div class="col-lg-12">
					<div class="well well-sm col-sm-6">
						<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'open-register-form');
						echo form_open_multipart("pos/open_register", $attrib); ?>
						<div class="form-group">
							<?= lang('cash_in_hand', 'cash_in_hand') ?>
							<?= form_input('cash_in_hand', '', 'id="cash_in_hand" class="form-control"'); ?>
						</div>
						<?php echo form_submit('open_register', lang('open_register'), 'class="btn btn-primary"'); ?>
						<?php echo form_close(); ?>
						<div class="clearfix"></div>
					</div>
				</div>
			<?php } ?>
		</div>
    </div>
</div>
<script>
	var kh_curr = '<?= $exchange_rate->rate ?>';
	var total_cash_in_hand = 0;
	$(document).on("keyup", "#cash_in_hand_kh, #cash_in_hand_us", function() {
		var cash_in_hand_us = $('#cash_in_hand_us').val() ? parseFloat($('#cash_in_hand_us').val()) : 0;
		var cash_in_hand_kh = $('#cash_in_hand_kh').val() ? parseFloat($('#cash_in_hand_kh').val()) : 0;
		total_cash_in_hand = cash_in_hand_us + parseFloat(cash_in_hand_kh / parseFloat(kh_curr));
		$('#cash_in_hand').val(formatDecimal(total_cash_in_hand));
	});
</script>
