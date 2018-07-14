<script>
    $(document).ready(function () {
        $('#GCData').dataTable({
            "aaSorting": [[3, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
			"bStateSave": true,
            'sAjaxSource': '<?= site_url('sales/getGiftCards') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, null, 
			{"mRender": currencyFormat}, 
			{"mRender": currencyFormat}, 
			null, null, 
			<?php if($Settings->member_card_expiry) { ?>
				{"mRender": fsd},
			<?php } ?>
			{"bSortable": false}]
        });
    });
</script>
<?= form_open('sales/gift_card_actions', 'id="action-form"') ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-gift"></i><?= lang('list_gift_cards') ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        
						<?php if ($Owner || $Admin) { ?>
							<li><a href="<?php echo site_url('sales/add_gift_card'); ?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> <?= lang('add_gift_card') ?></a></li>
							<li><a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
							<li><a href="#" id="pdf" data-action="export_pdf"><i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
							<li>
								<a href="<?= site_url('sales/import_gift_card'); ?>">
									<i class="fa fa-plus-circle"></i>
									<span class="text"> <?= lang('import_gift_card'); ?></span>
								</a>
							</li>
						<?php }else{ ?>
							<?php  if($GP['sales-add_gift_card']){ ?>
								<li><a href="<?php echo site_url('sales/add_gift_card'); ?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> <?= lang('add_gift_card') ?></a></li>
							<?php } ?>
						
							<?php if($GP['sales-export_gift_card']) { ?>
								<li><a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
								<li><a href="#" id="pdf" data-action="export_pdf"><i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
							<?php }?>
							
							<?php if($GP['sales-import_gift_card']) { ?>
								<li>
									<a href="<?= site_url('sales/import_gift_card'); ?>">
										<i class="fa fa-plus-circle"></i>
										<span class="text"> <?= lang('import_gift_card'); ?></span>
									</a>
								</li>
							<?php } ?>
							
						<?php }?>
						
                        <li class="divider"></li>
                        <li><a href="#" id="delete" data-action="delete"><i class="fa fa-trash-o"></i> <?= lang('delete_gift_cards') ?></a></li>
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
                    <table id="GCData" class="table table-bordered table-hover table-striped">
                        <thead>
							<tr>
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="check"/>
								</th>
								<th><?php echo $this->lang->line("card_no"); ?></th>
								<th><?php echo $this->lang->line("value"); ?></th>
								<th><?php echo $this->lang->line("balance"); ?></th>
								<th><?php echo $this->lang->line("created_by"); ?></th>
								<th><?php echo $this->lang->line("customer"); ?></th>
								<?php if($Settings->member_card_expiry) { ?>
									<th><?php echo $this->lang->line("expiry"); ?></th>
								<?php } ?>
								<th style="min-width:110px !important; width: 100px !important;"><?php echo $this->lang->line("actions"); ?></th>
							</tr>
                        </thead>
                        <tbody>
							<tr>
								<td colspan="8" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
							</tr>
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

