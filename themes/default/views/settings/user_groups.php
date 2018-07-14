<script>
    $(document).ready(function () {
        $('#GPData').dataTable({
            "aaSorting": [[0, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            "oTableTools": {
                "sSwfPath": "assets/media/swf/copy_csv_xls_pdf.swf",
                "aButtons": ["csv", {"sExtends": "pdf", "sPdfOrientation": "landscape", "sPdfMessage": ""}, "print"]
            },
            "aoColumns": [{"bSortable": false}, null, null, null, null, {"bSortable": false}
            ]
        });
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('groups'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('system_settings/create_group'); ?>" data-toggle="modal"
                               data-target="#myModal"><i class="fa fa-plus"></i> <?= lang('add_group') ?></a></li>
                        <li><a href="#" id="excelProducts" data-action="export_excel"><i
                                    class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                        <li><a href="#" id="pdfProducts" data-action="export_pdf"><i
                                    class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                        <li class="divider"></li>
                        <li><a href="#" id="deleteGroups" data-action="delete"><i
                                    class="fa fa-trash-o"></i> <?= lang('delete_groups') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo $this->lang->line("list_results"); ?></p>

                <div class="table-responsive">
                    <table id="GPData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <th><?php echo $this->lang->line("group_id"); ?></th>
                            <th><?php echo $this->lang->line("group_name"); ?></th>
                            <th><?php echo $this->lang->line("group_description"); ?></th>
                            <th><?php echo $this->lang->line("type"); ?></th>
                            <th style="width:45px;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($groups != NULL) {
                            foreach ($groups as $group) {
                                ?>
                                <tr>
                                    <td>
                                        <center><input class="checkbox multi-select" type="checkbox" name="val[]"
                                                       value="<?= $group->id ?>"/></center>
                                    </td>
                                    <td><?php echo $group->id; ?></td>
                                    <td><?php echo $group->name; ?></td>
                                    <td><?php echo $group->description; ?></td>
                                    <td><?php echo $group->type; ?></td>
                                    <td style="text-align:center;">
                                        <?php echo '<a class="tip" title="' . $this->lang->line("change_permissions") . '" href="' . site_url('system_settings/permissions/' . $group->id) . '"><i class="fa fa-tasks"></i></a> <a class="tip" title="' . $this->lang->line("edit_group") . '" data-toggle="modal" data-target="#myModal" href="' . site_url('system_settings/edit_group/' . $group->id) . '"><i class="fa fa-edit"></i></a> <a href="#" class="tip po" title="' . $this->lang->line("delete_group") . '" data-content="<p>' . lang('r_u_sure') . '</p><a class=\'btn btn-danger\' href=\'' . site_url('system_settings/delete_group/' . $group->id) . '\'>' . lang('i_m_sure') . '</a> <button class=\'btn po-close\'>' . lang('no') . '</button>"><i class="fa fa-trash-o"></i></a>'; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>