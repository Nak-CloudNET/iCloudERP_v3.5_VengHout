
<?= form_open('system_settings/tax_actions', 'id="action-form"') ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-money"></i><?= $page_title ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i>
					</a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?php echo site_url('system_settings/add_tax_exchange_rate'); ?>" data-toggle="modal"
                               data-target="#myModal"><i class="fa fa-plus"></i> <?= lang('add_tax_exchange_rate') ?></a></li>

                        <!--<li><a href="#" id="delete" data-action="delete"><i
                                    class="fa fa-trash-o"></i> <?= lang('delete_tax_rates') ?></a></li>
						-->
                    </ul>
                </li>
            </ul>
        </div>
    </div>
	

    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
					<?php
							for($i=1; $i<=12;$i++){
						$month[$i] = date('F', mktime(0, 0, 0, $i, 1));
					}

					?>
                <p class="introtext"><?php echo $this->lang->line("list_results"); ?></p>

                <div class="table-responsive">
                    <table id="CURData" class="table table-bordered table-hover table-striped">
                        <thead>
							<tr>
								<th rowspan="2"><?php echo $this->lang->line("year"); ?></th>
								<th rowspan="2"><?php echo $this->lang->line("month"); ?></th>
								<th colspan="3"><?php echo $this->lang->line("exchange_rate"); ?></th>
								<th rowspan="2" style="width:65px;"><?php echo $this->lang->line("actions"); ?></th>
							</tr>
							<tr>
								<th style="background-color:#428bca;color:#FFF;border:2px solid #357ebd;text-align:center;"><?php echo $this->lang->line("usd"); ?></th>
								<th style="background-color:#428bca;color:#FFF;border:2px solid #357ebd;text-align:center;"><?php echo $this->lang->line("salary_kh"); ?></th>
								<th style="background-color:#428bca;color:#FFF;border:2px solid #357ebd;text-align:center;"><?php echo $this->lang->line("average_kh"); ?></th>
							</tr>
                        </thead>
                        <tbody>
						
                      
							<?php
							if($info){
								foreach($info as $data){
									
									$edit_link = anchor('system_settings/edit_tax_exchange_rate/'.$data->id.'', '<i class="fa fa-edit"></i> ' . lang('edit'), 'data-toggle="modal" data-target="#myModal"');
									$delete_link =anchor('system_settings/delete_tax_exchange_rate/'.$data->id.'', '<i class="fa fa-trash-o"></i> ' . lang('delete'), 'class="delete"');
								
									
									$action = '<div class="text-center"><div class="btn-group text-left">'
										. '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
										. lang('actions') . ' <span class="caret"></span></button>
									<ul class="dropdown-menu pull-right" role="menu">
										<li>' . $edit_link . '</li>
										<li>' . $delete_link . '</li>
									</ul>
									</div></div>';
									
									
									
									echo '<tr>
										<td>'.$data->year.'</td>
										<td>'.$month[$data->month].'</td>
										<td>'.$data->usd.'</td>
										<td>'.$data->salary_khm.'</td>
										<td>'.$data->average_khm.'</td>
										<td>'.$action.'</td>
									</tr>';
								}
							}else{
								echo '<td colspan="6" class="dataTables_empty"><?= lang("loading_data_from_server") ?></td>';
							}
							?>
                 

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


