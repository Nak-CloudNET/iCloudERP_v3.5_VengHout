<script>
//var isValid = true;
//$(window).load(function(){
	$(".referent_line").addClass('f-cus');
//});

$(".remove_line").on('click',function() {
    var row = $(this).closest('tr').focus();
    row.remove();
});
</script>

<div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("taxes/combine_tax", $attrib); ?>
        <div class="modal-body">>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_tax', lang('add_tax'), 'class="btn btn-primary" id="add_submit"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>