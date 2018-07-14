<?php //$this->erp->print_arrays($attachments) ?>
<style>
    .img-del:hover{
        color: red;
        cursor: pointer;
    }
</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('attachment') . " (" . $company->name . ")"; ?></h4>
        </div>

        <div class="modal-body">
            <!--<p><?= lang('list_results'); ?></p>-->

            <div class="table-responsive">
                <span class="text-success" id="message"></span>
                <table class="table table-bordered table-condensed table-hover table-striped">
                    <thead>
                    <tr class="primary">
                        <th>Data</th>
                        <th>Link Download</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $datas = json_decode($attachments[0]->attachment);
                    if (!empty($datas)) {

                        foreach ($datas as $data){
                            echo "<tr><td><img class='img-responsive' width='100px' height='80px' src=".base_url().'assets/uploads/'.$data."></td>";
                            echo " <td><a target='_blank' href=".base_url()."assets/uploads/".$data.">Download</a></td>";
                            echo " <td style='width: 30px;text-align: center'><i id='".$data."' title='Delete it?' class='fa fa-2x fa-trash-o img-del'></i></td></tr>";
                        }
                    } else { ?>
                        <tr>
                            <td colspan="6" class="dataTables_empty"><?= lang('sEmptyTable') ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>


        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
        </div>
    </div>
    <?= $modal_js ?>
    <script type="text/javascript">
        $(document).ready(function () {
            var image=<?=$attachments[0]->attachment?>;
            $('.tip').tooltip();
            $('.img-del').click(function () {
                var customer_id=<?=$customer_id?>;
                var key=$(this).attr('id');
                var tr=$(this).closest('tr');
                var index=image.indexOf(key);
                bootbox.confirm({
                        message: "Are you sure ?",
                        buttons: {
                            confirm: {
                                label: 'Yes',
                                className: 'btn-success'
                            },
                            cancel: {
                                label: 'No',
                                className: 'btn-danger'
                            }
                        },
                    callback: function (result) {
                        if(result){
                            $.ajax({
                                type: 'POST',
                                url: site.base_url+'customers/deleteAttachment/'+customer_id+'/'+key,
                                dataType: "json",
                                data: image,
                                success: function (data) {
                                    tr.remove();
                                    if(data==1){
                                        $('#message').html(' <div class="alert alert-success" id="success-alert">\n' +
                                            '                    <button type="button" class="close" data-dismiss="alert">x</button>\n' +
                                            '                    <strong>Success! </strong>\n' +
                                            '                    Attachment deleted.\n' +
                                            '                </div>');
                                    }
                                    window.setTimeout(function() {
                                        $("#success-alert").fadeTo(500, 0).slideUp(500, function(){
                                            $(this).remove();
                                        });
                                    }, 4000);
                                },
                                errr:function (data) {
                                    $('#message').html(' <div class="alert alert-danger" id="success-alert">\n' +
                                        '                    <button type="button" class="close" data-dismiss="alert">x</button>\n' +
                                        '                    <strong>Success! </strong>\n' +
                                        '                    Someth.\n' +
                                        '                </div>');
                                }
                            });
                        }
                    }
                });

            });
        });
    </script>

