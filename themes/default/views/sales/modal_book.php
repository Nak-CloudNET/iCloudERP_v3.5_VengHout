<div class="modal-dialog modal-sm no-modal-header">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Booking Room</h4>
		</div>
		<?php 
			$attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'add-book');
			echo form_open_multipart("sales/modal_book", $attrib); 
		?>
		<div class="modal-body">
			<input type="hidden" value="<?= $id;?>" name="room_id">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<?= lang('customer', 'customer'); ?>
						<?= form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : $pos->default_customer), 'id="customer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control" style="width:100%;"'); ?>
					</div>
					<div class="form-group">
						<?= lang("start_date", "start_date"); ?>
						<?php echo form_input('start_date', date('d/m/Y h:i'), 'class="form-control datetime" id="start_date" required="required"'); ?>
					</div>
					<div class="form-group">
						<?= lang("end_date", "end_date"); ?>
						<?php echo form_input('end_date', '', 'class="form-control datetime" id="end_date" required="required"'); ?>
					</div>
					<div class="form-group">
						<?= lang("note", "note"); ?>
						<?php echo form_input('note', '', 'class="form-control" required="required"'); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<?php echo form_submit('add_booking', lang('add_booking'), 'class="btn btn-primary"'); ?>
		</div>
		<?php echo form_close(); ?>
    </div>
</div>
<script>
	$('#customer').val('<?= $pos->default_customer; ?>').select2({
		minimumInputLength: 1,
		data: [],
		initSelection: function (element, callback) {
			$.ajax({
				type: "get", async: false,
				url: site.base_url+"customers/getCustomer/" + $(element).val(),
				dataType: "json",
				success: function (data) {
					callback(data[0]);
				}
			});
		},
		ajax: {
			url: site.base_url + "customers/suggestions",
			dataType: 'json',
			quietMillis: 15,
			data: function (term, page) {
				return {
					term: term,
					limit: 10
				};
			},
			results: function (data, page) {
				if (data.results != null) {
					return {results: data.results};
				} else {
					return {results: [{id: '', text: 'No Match Found'}]};
				}
			}
		}
	});
</script>
<?= $modal_js ?>