<?= form_open('system_settings/reason_actions', 'id="action-form"') ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-folder-open"></i><?= lang('reasons'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a href="<?php echo site_url('system_settings/add_group_position/'.$parent_id); ?>" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-plus"></i> <?= lang('add_group_position') ?>
                            </a>
                        </li>
						 <li><a href="<?php echo site_url('system_settings/add_reason/' . $parent_id); ?>"
                               data-toggle="modal" data-target="#myModal"><i
                               class="fa fa-plus"></i> <?= lang('add_reason') ?></a>
						</li>
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
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="check"/>
								</th>
                                <th><?= $this->lang->line("code"); ?></th>
                                <th><?= $this->lang->line("position"); ?></th>
                                <th style="width:100px;"><?= $this->lang->line("actions"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
							<?php
								$this->db->select("id, code, name")
										 ->from("position");
								$q = $this->db->get();
								$g = 1;
								foreach (($q->result()) as $row){
							?>
							<tr>
								<td style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="check"/>
								</td>
								<td><?= $row->code ?></td>
								<td><?= $row->name ?></td>
								<td>
									<div class="text-center"> <a href="<?= site_url('system_settings/edit_group_position/'.$row->id);?>" data-toggle='modal' data-target='#myModal' class='tip' title='<?= lang("edit_group_position")?>'><i class="fa fa-edit"></i></a>

								<!--<a class="conf" href="<?= site_url('system_settings/delete_category/'.$row->id);?>"  class='tip' title='<?= lang("delete_category")?>'> <i class="fa fa-trash-o"></i> </a>-->
								<!--<a href="<?=site_url('system_settings/delete_category/'.$row->id)?>" class="bpo"
									title="<?=$this->lang->line("delete_category")?>"
									data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"
									data-html="true" data-placement="left">
									<i class="fa fa-trash-o"></i> 
								</a>-->
								
									</div>
								</td>
                            </tr>
							<?php
								$this->db->select("id,position_id, code, description")
										 ->from("reasons")
										 ->where("position_id",$row->id);
								$q2 = $this->db->get();
								$g = 1;
								foreach (($q2->result()) as $row2){
							?>
                            <tr class="warning">
								<td style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="check"/>
								</td>
							    <td style="padding-left:40px;"><?= $row2->code ?></td>
							    <td><?= $row2->description ?></td>
								<td>
									<div class="text-center"> <a href="<?= site_url('system_settings/edit_reason/'.$row2->id);?>" data-toggle='modal' data-target='#myModal' class='tip' title='<?= lang("edit_reason")?>'><i class="fa fa-edit"></i></a>
								
								<!--<a class="conf" href="<?= site_url('system_settings/delete_subcategory/'.$row2->id);?>"  class='tip' title='<?= lang("delete_subcategory")?>'> <i class="fa fa-trash-o"></i> </a>-->
								
								<!--<a href="<?=site_url('system_settings/delete_subcategory/'.$row2->id)?>" class="bpo"
									title="<?=$this->lang->line("delete_subcategory")?>"
									data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger'  data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"
									data-html="true" data-placement="left">
									<i class="fa fa-trash-o"></i> 
								</a>-->
									</div>
								</td>
                            </tr>
						<?php
							$g++;
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
<script language="javascript">
    $(document).ready(function () {

        $('#delete').click(function (e) {
            e.preventDefault();
            $('#form_action').val($(this).attr('data-action'));
            $('#action-form-submit').trigger('click');
        });

        $('#excel').click(function (e) {
            e.preventDefault();
            $('#form_action').val($(this).attr('data-action'));
            $('#action-form-submit').trigger('click');
        });

        $('#pdf').click(function (e) {
            e.preventDefault();
            $('#form_action').val($(this).attr('data-action'));
            $('#action-form-submit').trigger('click');
        });

    });
</script>

