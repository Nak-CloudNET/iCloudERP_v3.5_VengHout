<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('assign_to_user'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("sales/assign_to_user", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("users", "users"); ?>
                        <?php
                            $user[''] = 'None';
                            foreach ($AllUser as $value) {
                                $user[$value->id] = $value->first_name .' '.$value->last_name;
                            }
                            echo form_dropdown('user_id', $user, (isset($SO_NUM->assign_to_id)?($SO_NUM->assign_to_id):""), 'class="form-control  select" required id="user_id"');
                        ?>
                    </div>
                </div>

                 <div class="col-md-6">
                    <div class="form-group">
                        <?= lang("sale_reference", "sales_num"); ?>
                        <?php echo form_input('so_num',$SO_NUM->reference_no, 'class="form-control tip" required readonly id="so_num" data-bv-notempty="true"'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('assign_to_user', lang('assign_to_user'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('#biller_logo').change(function (event) {
            var biller_logo = $(this).val();
            $('#logo-con').html('<img src="<?=base_url('assets/uploads/logos')?>/' + biller_logo + '" alt="">');
        });

    });
</script>
<?= $modal_js ?>
