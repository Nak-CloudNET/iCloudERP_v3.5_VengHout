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
            "aoColumns": [{"bSortable": false}, null, null, {"bSortable": false}
            ]
        });
		
		$(".conf").click(function(e){
			var url = $(this).attr('href');
			e.preventDefault();
			bootbox.confirm('Are you sure?', function(rs){
				if(rs){
					window.location.href = url;
				}
			});
		});
    });
</script>

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('define_frequency'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('system_settings/create_define_frequency'); ?>" data-toggle="modal"
                               data-target="#myModal"><i class="fa fa-plus"></i> <?= lang('add_frequency') ?></a></li>
                       
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
                            <th><?php echo $this->lang->line("description"); ?></th>
                            <th><?php echo $this->lang->line("day"); ?></th>
                            <th style="width:45px;"><?php echo $this->lang->line("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($rows as $row) {
                            ?>
                            <tr>
                                <td>
                                    <center><input class="checkbox multi-select" type="checkbox" name="val[]"
                                                   value="<?= $row->id ?>"/></center>
                                </td>
                                <td><?php echo $row->description; ?></td>
                                <td><?php echo $row->day; ?></td>
                                <td style="text-align:center;">
                                    <?php echo '<a class="tip" title="' . $this->lang->line("edit_principle") . '" data-toggle="modal" data-target="#myModal" href="' . site_url('system_settings/edit_define_frequency/' . $row->id) . '"><i class="fa fa-edit"></i></a> <a href="#" class="tip po" title="' . $this->lang->line("delete_principle") . '" data-content="<p>' . lang('r_u_sure') . '</p><a  class=\'btn btn-danger\'  href=\'' . site_url('system_settings/delete_define_principle/' . $row->id) . '\'>' . lang('i_m_sure') . '</a> <button class=\'btn po-close\'>' . lang('no') . '</button>"><i class="fa fa-trash-o"></i></a>'; ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>