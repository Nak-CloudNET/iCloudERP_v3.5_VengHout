<link href="<?= $assets ?>fullcalendar/css/bootstrap-colorpicker.min.css" rel="stylesheet" />

<style type="text/css">
	.modal-title {
		text-transform: capitalize;
	}
	.modal-body {
		min-height: auto;
		overflow: hidden;
	}
	.col-sm-4 {
		margin-bottom: 20px !important;
	}
	.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
		color: green;
		font-weight: bolder;
	}
	.nav a {
		color: #999;
	}
	.error {
        color: #ac2925;
        margin-bottom: 15px;
    }
    .event-tooltip {
        width:150px;
        background: rgba(0, 0, 0, 0.85);
        color:#FFF;
        padding:10px;
        position:absolute;
        z-index:10001;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 11px;
    }

</style>

<div class="modal-dialog modal-lg" style="width:1000px; height: auto;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h2 class="modal-title" id="myModalLabel"><?= lang('event_to_do_title'); ?></h2>
        </div>
		<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form'); ?>
			<?= form_open('sales/add_event_to_dos', $attrib); ?>
				<div class="modal-body">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#event" id="iEvent"><?= lang('event') ?></a></li>
						<!--<li><a data-toggle="tab" href="#to_do" id="i2Do"><?= lang('to_do') ?></a></li>-->
					</ul>
					<div class="tab-content">
						<div id="event" class="tab-pane fade in active event">
						  <div class="form-group">
							<label class="control-label col-sm-2" for="text"><?= lang('customer', 'slcustomer') ?></label>
							<div class="col-md-4">
										<?php if ($Owner || $Admin || $GP['customers-add']) { ?><div class="input-group"><?php } ?>
										<?php
										$cust["0"] = "None";
										foreach ($customers as $customer) {
											$cust[$customer->id] = $customer->text;
										}
										echo form_dropdown('customer_invoice', $cust, (isset($_POST['customer_invoice']) ? $_POST['customer_invoice'] : ""), 'class="form-control"  id="slcustomer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer_invoice") . '" required="required"');
										?>

										<div class="input-group-addon no-print" style="padding: 2px 5px; border-left: 0;">
											<a href="#" id="view-customer" class="external" data-toggle="modal" data-target="#myModal">
												<i class="fa fa-2x fa-user" id="addIcon"></i>
											</a>
										</div>

										<div class="input-group-addon no-print" style="padding: 2px 5px;"><a
																href="<?= site_url('customers/add/event2do'); ?>" id="add-customer"
																class="external" data-toggle="modal" data-target="#myModal2"><i
																	class="fa fa-2x fa-plus-circle" id="addIcon"></i></a></div>
									</div>
							</div>
						  </div>

						  <div class="form-group">
							<label class="control-label col-sm-2" for="text"><?= lang('subject', 'subject') ?></label>
							<div class="col-sm-4">
							  <?php echo form_input('subject', '', 'class="form-control input-tip" id="subject" required="required"'); ?>
							</div>
						  </div>
						  <div class="form-group">
							<label class="control-label col-sm-2" for="text"><?= lang('assign_to', 'useer_id') ?></label>
							<div class="col-sm-4">
								<?php
									$u[''] = 'None';
									foreach ($users as $user) {
										$u[$user->id] = $user->first_name .' '.$user->last_name;
									}
									echo form_dropdown('user_id', $u, '', 'id="useer_id" class="form-control input-tip select" required="required" data-placeholder="' . lang("select") . ' ' . lang("user") . '" ');
								?>
							</div>
						  </div>
						  <div class="form-group">
							<label class="control-label col-sm-2" for="text"><?= lang('status', 'status') ?></label>
							<div class="col-sm-4">
								 <?php
									$status = array('planned'=> lang('Planned'), 'held'=> lang('Held'), 'not_held' => lang('Not Held'));

									echo form_dropdown('status', $status, (isset($_POST['status']) ? $_POST['status'] :''), 'id="status" data-placeholder="' . lang("select") . ' ' . lang("status") . '"  class="form-control input-tip select" required="required" style="width:100%;"'); 
								 ?>
							</div>
						  </div>
						  <div class="form-group">
							<label class="control-label col-sm-2" for="text"><?= lang('start_date', 'start_date') ?></label>
							<div class="col-sm-4">
								 <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control input-tip datetime" id="start_date" required="required"'); ?>
							</div>
						  </div>
						  <div class="form-group">
							<label class="control-label col-sm-2" for="text"><?= lang('end_date', 'end_date') ?></label>
							<div class="col-sm-4">
								 <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control input-tip datetime" id="end_date" required="required"'); ?>
							</div>
						  </div>
						  <div class="form-group">
							<label class="control-label col-sm-2" for="text"><?= lang('activity_type', 'activity_type') ?></label>
							<div class="col-sm-4">
								 <?php
									$type = array('call'=> lang('Call'), 'meeting'=> lang('Meeting'), 'mobile_call' => lang('Mobile Call'));

									echo form_dropdown('activity_type', $type, (isset($_POST['activity_type']) ? $_POST['activity_type'] :''), 'id="activity_type" data-placeholder="' . lang("select") . ' ' . lang("activity_type") . '"  class="form-control input-tip select" required="required" style="width:100%;"'); 
								 ?>
							</div>
						  </div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="text"><?= lang('color', 'color') ?></label>
								<div class="col-md-4">
									<div class="input-group">
										<span class="input-group-addon" id="event-color-addon" style="width:2em;"></span>
										<input id="color" name="color" type="text" class="form-control" readonly="readonly" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<div class="modal-footer">
				<?php echo form_submit('add_event_to_do', lang('save'), 'class="btn btn-success" id="add_event_to_do" style="float:right"'); ?>
			</div>
			<?= form_close(); ?>
    </div>
</div>
<?= $modal_js ?>

<script type="text/javascript">
    var currentLangCode = '<?= $cal_lang; ?>', moment_df = '<?= strtoupper($dateFormats['js_sdate']); ?> HH:mm', cal_lang = {},
    tkname = "<?=$this->security->get_csrf_token_name()?>", tkvalue = "<?=$this->security->get_csrf_hash()?>";
    cal_lang['add_event'] = '<?= lang('add_event'); ?>';
    cal_lang['edit_event'] = '<?= lang('edit_event'); ?>';
    cal_lang['delete'] = '<?= lang('delete'); ?>';
    cal_lang['event_error'] = '<?= lang('event_error'); ?>';
</script>

<script src='<?= $assets ?>fullcalendar/js/bootstrap-colorpicker.min.js'></script>
<script src='<?= $assets ?>fullcalendar/js/main.js'></script>

<script type="text/javascript">

	$(document).ready(function () {

		$('#iEvent').click(function() {
			$('#slcustomer2').removeAttr('required');
			$('#subject2').removeAttr('required');
			$('#useer_id2').removeAttr('required');
			$('#status2').removeAttr('required');
			$('#start_date2').removeAttr('required');
			$('#end_date2').removeAttr('required');
		});

		$('#i2Do').on('click', function() {
			$('#slcustomer').removeAttr('required');
			$('#subject').removeAttr('required');
			$('#useer_id').removeAttr('required');
			$('#status').removeAttr('required');
			$('#start_date').removeAttr('required');
			$('#end_date').removeAttr('required');
			$('#activity_type').removeAttr('required');
		});

		$("#start_date").datetimepicker({
	        format: site.dateFormats.js_ldate,
	        fontAwesome: true,
	        language: 'erp',
	        weekStart: 1,
	        todayBtn: 1,
	        autoclose: 1,
	        todayHighlight: 1,
	        startView: 2,
	        forceParse: 0
	    }).datetimepicker('update', new Date());

	    $("#start_date2").datetimepicker({
	        format: site.dateFormats.js_ldate,
	        fontAwesome: true,
	        language: 'erp',
	        weekStart: 1,
	        todayBtn: 1,
	        autoclose: 1,
	        todayHighlight: 1,
	        startView: 2,
	        forceParse: 0
	    }).datetimepicker('update', new Date());

	});
</script>