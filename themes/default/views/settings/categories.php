<script>
    /*$(document).ready(function () {
        $('#CategoryTable').dataTable({
            "aaSorting": [[1, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('system_settings/getCategories/'.$parent_id) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{"bSortable": false, "mRender": checkbox}, {
                "bSortable": false,
                "mRender": img_hl
            }, null, null, {"bSortable": false}]
        });
    });*/
</script>
<?= form_open('system_settings/category_actions', 'id="action-form"') ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-folder-open"></i><?= lang('categories'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a href="<?php echo site_url('system_settings/add_category/'.$parent_id); ?>" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-plus"></i> <?= lang('add_category') ?>
                            </a>
                        </li>
						 <li><a href="<?php echo site_url('system_settings/add_subcategory/' . $parent_id); ?>"
                               data-toggle="modal" data-target="#myModal"><i
                                    class="fa fa-plus"></i> <?= lang('add_subcategory') ?></a>
						</li>
                        <li>
                            <a href="<?php echo site_url('system_settings/import_categories'); ?>" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-plus"></i> <?= lang('import_categories') ?>
                            </a>
                        </li>
                       <!-- <li>
                            <a href="<?php echo site_url('system_settings/add_subcategory'); ?>" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-plus"></i> <?= lang('add_subcategory') ?>
                            </a>
                        </li>-->
                        <li>
                            <a href="<?php echo site_url('system_settings/import_subcategories'); ?>" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-plus"></i> <?= lang('import_subcategories') ?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="excel" data-action="export_excel">
                                <i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="pdf" data-action="export_pdf">
                                <i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?>
                            </a>
                        </li>
                       <!-- <li>
                        <li class="divider"></li>
                            <a href="#" id="delete" data-action="delete">
                                <i class="fa fa-trash-o"></i> <?= lang('delete_categories') ?>
                            </a>
                        </li>-->
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('list_results'); ?></p>
                <div class="table-responsive">
                    <table id="CategoryTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th  style="min-width:30px; width: 30px; text-align: center;">
                                    <input type="checkbox" name="checkAll" id="checkAll" />
                                   
                                </th>
                                <th style="min-width:40px; width: 40px; text-align: center;">
                                    <?= $this->lang->line("image"); ?>
                                </th>
                                <th><?= $this->lang->line("category_code"); ?></th>
                                <th><?= $this->lang->line("category_name"); ?></th>
                                <th style="width:100px;"><?= $this->lang->line("actions"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
							$this->db->select("id, code, name,image")
							->from("categories");
							$q = $this->db->get();
							$g = 1;
							foreach (($q->result()) as $row){
								
								?>
								<tr>
                                <th style="min-width:30px; width: 30px; text-align: center;">
                                    <input class="check_row" type="checkbox" name="check[]" value="<?= $row->id; ?>" />
                                </th>
								<td><img src="<?=base_url('assets/uploads/'.$row->image);?>" style="width:50px;"></td>
                               <td><?=$row->code?></td>
							    <td><?=$row->name?></td>
								
								<td>
								<div class="text-center"> 
								
								<a class="conf" href="<?= site_url('products/print_barcodes/?category='.$row->id);?>"  class='tip' title='<?= lang("print_barcodes")?>'> <i class="fa fa-print"></i> </a>
								
								<a href="<?= site_url('system_settings/edit_category/'.$row->id);?>" data-toggle='modal' data-target='#myModal' class='tip' title='<?= lang("edit_category")?>'><i class="fa fa-edit"></i></a>

								<a class="conf" href="<?= site_url('system_settings/delete_category/'.$row->id);?>"  class='tip' title='<?= lang("delete_category")?>'> <i class="fa fa-trash-o"></i> </a>
								
								</div>
								
								</td>
                            </tr>
							<?php
								$this->db->select("id,category_id, code, name,image")
								->from("subcategories")->where("category_id",$row->id);
								$q2 = $this->db->get();
								$g = 1;
								foreach (($q2->result()) as $row2){
						    ?>
                            <tr class="warning">
                                <th style="min-width:30px; width: 30px; text-align: center;"></th>
								<td><img src="<?=base_url('assets/uploads/'.$row2->image);?>" style="width:50px;"></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$row2->code?></td>
							    <td><?=$row2->name?></td>
								
								<td>
								<div class="text-center"> 
								
								<a class="conf" href="<?= site_url('products/print_barcodes/?subcategory='.$row->id);?>"  class='tip' title='<?= lang("print_barcodes")?>'> <i class="fa fa-print"></i> </a>
								
								<a href="<?= site_url('system_settings/edit_subcategory/'.$row2->id);?>" data-toggle='modal' data-target='#myModal' class='tip' title='<?= lang("edit_subcategory")?>'><i class="fa fa-edit"></i></a>
								
								<a class="conf" href="<?= site_url('system_settings/delete_subcategory/'.$row2->id);?>"  class='tip' title='<?= lang("delete_subcategory")?>'> <i class="fa fa-trash-o"></i> </a>
								
								<!--<a href="<?=site_url('system_settings/delete_subcategory/'.$row2->id)?>" class="bpo"
                            title="<?=$this->lang->line("delete_subcategory")?>"
                            data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger'  data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"
                            data-html="true" data-placement="left">
                            <i class="fa fa-trash-o"></i> 
                        </a>-->
								</div>
								
								</td>
                            </tr>
						<?php $g++;
							}
							$g++;
							}
							?>
                            <!--<tr>
                                <td colspan="5" class="dataTables_empty">
                                    <?= lang('loading_data_from_server') ?>
                                </td>
                            </tr>-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display: none;">
    <input type="hidden" name="form_action" value="" id="form_action"/>
    <?= form_submit('submit', 'submit', 'id="action-form-submit"') ?>
</div>
<?= form_close() ?>

<script type="text/javascript">
    $(document).ready(function () {

        $('#checkAll').on('ifChanged', function(){

            if($(this).is(':checked')) {
                $('.check_row').each(function() {
                $(this).iCheck('check');
                });
            }else{
                $('.check_row').each(function() {
                    $(this).iCheck('uncheck');
                });
            }
        });

        $('#delete').click(function (e) {
            e.preventDefault();
            $('#form_action').val($(this).attr('data-action'));
            $('#action-form-submit').trigger('click');
        });

        // $('#excel').click(function (e) {
        //     e.preventDefault();
        //     $('#form_action').val($(this).attr('data-action'));
        //     $('#action-form-submit').trigger('click');
        // });

        // $('#pdf').click(function (e) {
        //     e.preventDefault();
        //     $('#form_action').val($(this).attr('data-action'));
        //     $('#action-form-submit').trigger('click');
        // });
        /*$("#excel").click(function(e){
            e.preventDefault();
            window.location.href = "<?=site_url('System_settings/getCategoryAll/0/xls/')?>";
            return false;
        });
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('System_settings/getCategoryAll/pdf/?v=1'.$v)?>";
            return false;
        });*/

    });
</script>

