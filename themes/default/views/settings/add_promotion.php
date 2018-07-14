<?php
//$this->erp->print_arrays($unit);

if (!empty($categories)) {
    foreach ($categories as $category) {
        $vars[] = $category->id .'-'. addslashes($category->name);
    }
} else {
    $vars = array();
}
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_promotion'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("system_settings/add_promotion", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <label for="name"><?php echo $this->lang->line("description"); ?></label>
                <div
                    class="controls"> <?php echo form_input('description', '', 'class="form-control" id="description" required="required"'); ?> 
				</div>
            </div>
			<div id="attrs"></div>
		    <div>
				<div class="form-group" id="ui" style="margin-bottom: 0;">
					<div class="input-group">
						<?php
						echo form_input('categories', '', 'class="form-control select-tags" id="categories" placeholder="' . $this->lang->line("enter_attributes") . '"'); ?>
						<div class="input-group-addon" style="padding: 2px 5px;">
							<a href="#" id="addAttributes">
								<i class="fa fa-2x fa-plus-circle" id="addIcon"></i>
							</a>
						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
				
				<div class="table-responsive">
					<table id="attrTable" class="table table-bordered table-condensed table-striped" style="margin-bottom: 0; margin-top: 10px;">
						<thead>
						<tr class="active">
							<th><?= lang('name') ?></th>
							<th><?= lang('discount') ?></th>
							<th><i class="fa fa-times attr-remove-all"></i></th>
						</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
			
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_promotion', lang('add_promotion'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<?= $modal_js ?>

<script type="text/javascript">
	
	var variants = <?=json_encode($vars);?>;
	
	$(".select-tags").select2({
		tags: variants,
		tokenSeparators: [","],
		multiple: true
	});
	
	
	$('#addAttributes').click(function (e) {
		e.preventDefault();
		var attrs_val = $('#categories').val(), attrs;
		attrs = attrs_val.split(',');
		for (var i in attrs) {
			if (attrs[i] !== '') {
			  var cate = attrs[i].split('-');
			  $('#attrTable').show().append('<tr class="attr"><td><input type="hidden" name="arr_cate[]" value="' + cate[0] + '"><span>' + cate[1] + '</span></td><td class="form-control"><input type="text" name="percent_tag[]" value=""><span></span></td><td class="text-center"><i class="fa fa-times delAttr"></i></td></tr>');
			}
		}
	});
	
		//=====================Related Strap=========================
	$(document).on('ifChecked', '#related_strap', function (e) {
		$('#strap-con').slideDown();
		$('.group_price').css("display","none");
	});
	$(document).on('ifUnchecked', '#related_strap', function (e) {
		$(".select-strap").select2("val", "");
		$('.attr-remove-all').trigger('click');
		$('#strap-con').slideUp();
	});
	//=====================end===================================
	$(document).on('click', '.delAttr', function () {
		$(this).closest("tr").remove();
	});
	$(document).on('click', '.attr-remove-all', function () {
		$('#attrTable tbody').empty();
		$('#attrTable').hide();
	});
	
</script>