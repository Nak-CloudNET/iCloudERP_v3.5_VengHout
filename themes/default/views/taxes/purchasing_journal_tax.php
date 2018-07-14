<script>
    $(document).ready(function () {
		function format(x){
			if (x != null) {
				return '<div class="text-right">'+x.toFixed(2)+'</div>';
			} else {
				return '<div class="text-right">0</div>';
			}
		}
			var oTable =$('#PrRData').dataTable({
			 "aaSorting": [[1, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            "aoColumns": [{
                "bSortable": false,
                "mRender": checkbox
            }, {"mRender": fld}, null, null, null, {"mRender": row_status}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat},{"mRender": currencyFormat},{"mRender": currencyFormat},{"mRender": currencyFormat},null, {"mRender": row_status_confirm}],
           
		    "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ){
             
            }
		}).fnSetFilteringDelay().dtFilter([
            {column_number: 0, filter_default_label: "[<?=lang('Nº');?>]", filter_type: "text", data: []},
			//{column_number: 1, filter_default_label: "[<?=lang('enterprise');?>]", filter_type: "text", data: []},
            {column_number: 1, filter_default_label: "[<?=lang('Year');?>]", filter_type: "text", data: []},
			{column_number: 2, filter_default_label: "[<?=lang('Month');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('Taxable Value');?>]", filter_type: "text", data: []},
			{column_number: 4, filter_default_label: "[<?=lang('Journal Date');?>]", filter_type: "text", data: []},
			{column_number: 5, filter_default_label: "[<?=lang('VAT');?>]", filter_type: "text", data: []},
			{column_number: 6, filter_default_label: "[<?=lang('Journal Location');?>]", filter_type: "text", data: []},
			{column_number: 7, filter_default_label: "[<?=lang('action');?>]", filter_type: "text", data: []},
			
        ], "footer");;
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
        $("#product").autocomplete({
            source: '<?= site_url('reports/suggestions'); ?>',
            select: function (event, ui) {
                $('#product_id').val(ui.item.id);
                //$(this).val(ui.item.label);
            },
            minLength: 1,
            autoFocus: false,
            delay: 300,
        });
    });
</script>
<?php if ($Owner) {
	    echo form_open('purchases/purchase_actions', 'id="action-form"');
	}
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-star"></i><?=lang('purchases') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')';?>
        </h2>
		<div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>">
                        <i class="icon fa fa-toggle-up"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>">
                        <i class="icon fa fa-toggle-down"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        
						<li>
                            <a href="javascript:void(0)" id="purchase_tax" data-action="purchase_tax">
                                <i class="fa fa-plus-circle"></i> <?=lang('declare')?>
                            </a>
                        </li>
						<li>
                            <a href="#" id="undeclare">
                                <i class="fa fa-plus-circle"></i> <?= lang('undeclare') ?>
                            </a>
                        </li>
						<!--
						<li>
                            <a href="<?=site_url('purchases/add')?>">
                                <i class="fa fa-plus-circle"></i> <?=lang('add_purchase')?>
                            </a>
                        </li>
						<li>
                            <a href="#" id="declare">
                                <i class="fa fa-plus-circle"></i> <?= lang('declare') ?>
                            </a>
                        </li>
						-->
						
					
                        <li>
                            <a href="#" id="excel" data-action="export_excel">
                                <i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="pdf" data-action="export_pdf">
                                <i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="combine" data-action="combine">
                                <i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#" class="bpo" title="<b><?=$this->lang->line("delete_purchases")?></b>"
                                data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"
                                data-html="true" data-placement="left">
                                <i class="fa fa-trash-o"></i> <?=lang('delete_purchases')?>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php if (!empty($warehouses)) {
                    ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?=lang("warehouses")?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?=site_url('purchases')?>"><i class="fa fa-building-o"></i> <?=lang('all_warehouses')?></a></li>
                            <li class="divider"></li>
                            <?php
                            	foreach ($warehouses as $warehouse) {
                            	        echo '<li ' . ($warehouse_id && $warehouse_id == $warehouse->id ? 'class="active"' : '') . '><a href="' . site_url('purchases/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            	    }
                                ?>
                        </ul>
                    </li>
                <?php }
                ?>
            </ul>
        </div>
    </div>
	<?php if ($Owner) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?>
<?php }
?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?=lang('list_results');?></p>
				<div id="form">

                    <?php echo form_open("purchases"); ?>
                                        <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="user"><?= lang("created_by"); ?></label>
                                <?php
                                $us[""] = "";
                                foreach ($users as $user) {
                                    $us[$user->id] = $user->first_name . " " . $user->last_name;
                                }
                                echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control" id="user" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("user") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("supplier", "supplier"); ?>
                                <?php echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ""), 'class="form-control" id="supplier"'); ?> </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="warehouse"><?= lang("warehouse"); ?></label>
                                <?php
                                $wh[""] = "";
                                foreach ($warehouses as $warehouse) {
                                    $wh[$warehouse->id] = $warehouse->name;
                                }
                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'class="form-control" id="warehouse" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("warehouse") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>
						
						<div class="col-sm-4">
                           <div class="form-group">
                                <?= lang("note", "note"); ?>
                                <?php echo form_input('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control tip" id="note"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>

                <div class="clearfix"></div>

                <div class="table-responsive">
                    <table id="PrRData"
                           class="table table-bordered table-hover table-striped table-condensed reports-table">
                        <thead>
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th><?php echo $this->lang->line("date"); ?></th>
                            <th><?php echo $this->lang->line("reference_no"); ?></th>
                            <th><?php echo $this->lang->line("supplier"); ?></th>
                            <th><?php echo $this->lang->line("description"); ?></th>
                            <th><?php echo $this->lang->line("purchase_status"); ?></th>
                            <th><?php echo $this->lang->line("amount"); ?></th>
                            <th><?php echo $this->lang->line("vat"); ?></th>
                            <th><?php echo $this->lang->line("total_amount"); ?></th>
                            <th><?php echo $this->lang->line("amount_declare"); ?></th>
                            <th><?php echo $this->lang->line("vat_declare"); ?></th>
                            <th><?php echo $this->lang->line("total_amount_declare"); ?></th>
                            <th><?php echo $this->lang->line("remark"); ?></th>
                            <th style="width:100px;"><?php echo $this->lang->line("status"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
						<!--Place Edit Code-->
						
						<?php
							foreach($getJournal_tax as $dt){	
							
						?>
							<tr>
								<td style="min-width:5%; width: 5%; text-align: center;">
									<?=$dt->tran_id.'_j'?>
								</td>
								<td><?=$dt->tran_date;?></td>
								<td><?=$dt->reference_no;?></td>
								<td></td>
								<td><?=$dt->description;?></td>
								<input type="hidden" class="journal" value="1" />
								<td></td>
								<td><?=$dt->amount;?></td>
								<td><?=$dt->order_tax;?></td>
								<td><?=$dt->balance;?></td>
								<td></td>
								<td></td>
								<td></td>
								<td><?=$dt->remark;?></td>
								<td><?=$dt->status_tax;?></td>
							</tr>
						<?php
						} 
						?>
						
						
						
						<?php
							foreach($purchasing_tax as $pt){	
						?>
							<tr class="purchase_link" id="<?=$pt->id;?>">
								<td style="min-width:5%; width: 5%; text-align: center;">
									<?=$pt->id;?>
								</td>
								<td><?=$pt->date;?></td>
								<td><?=$pt->reference_no;?></td>
								<td><?=$pt->supplier;?></td>
								<td><?=$pt->note;?></td>
								<td><?=$pt->status;?></td>
								<td><?=$pt->amount;?></td>
								<td><?=$pt->order_tax;?></td>
								<td><?=$pt->balance;?></td>
								<td><?=$pt->amount_declear;?></td>
								<td><?=$pt->amount_tax_declare;?></td>
								<td><?=$pt->total_amount_declare;?></td>
								<td><?=$pt->remark;?></td>
								<td><?=$pt->status_tax;?></td>
							</tr>
						<?php
						} 
						?>
						<!--Place Edit Code-->
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th></th>
							<!--
							<th></th>
							-->
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
							<th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('body').on('click', '#purchase_tax', function() {
                if($('.checkbox').is(":checked") === false){
                    alert('Please select at least one.');
					return false;
                }
                var arrItems = [];
                $('.checkbox').each(function(i){
                    if($(this).is(":checked")){
                        if($(this).val() != ""){
                            arrItems[i] = $(this).val();  
                        }
                    }
                });
                $('#myModal').modal({remote: '<?=base_url('taxes/add_purchasing_tax_form');?>?data=' + arrItems + ''});
                $('#myModal').modal('show');
            });
		$('#declare').click(function (e) {
            e.preventDefault();
            $('#form_action').val(this.id);
            $('#action-form').submit();
		
        });
		$('#undeclare').click(function (e) {
            e.preventDefault();
            $('#form_action').val(this.id);
            $('#action-form').submit();
		
        });	
			
        });
</script>