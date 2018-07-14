<script type="text/javascript">
$(document).ready(function(){
	
	$('body').on('click', '#excel1', function(e) {
	   e.preventDefault();
	   var k = false;
	   $.each($("input[name='val[]']:checked"), function(){
	    k = true;

	   });
	   $('#form_action').val($('#excel1').attr('data-action'));
	   $('#action-form-submit').trigger('click');
  	});
  	$('body').on('click', '#pdf1', function(e) {
	   e.preventDefault();
	   var k = false;
	   $.each($("input[name='val[]']:checked"), function(){
	    
	    k = true;
	   });
	   $('#form_action').val($('#pdf1').attr('data-action'));
	   $('#action-form-submit').trigger('click');
  	});
});
</script>
<style>
	#tbstock .shead th{
		background-color: #428BCA;border-color: #357EBD;color:white;text-align:center;
	}

</style>
<?php 
		// if ($Owner) {
			echo form_open('reports/expenseReport_action', 'id="action-form"');
		// } 
		?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-barcode"></i><?= lang('expense_report') ; ?>
        </h2>
		<div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="toggle_up tip" title="<?= lang('hide_form') ?>">
                        <i class="icon fa fa-toggle-up"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0);" class="toggle_down tip" title="<?= lang('show_form') ?>">
                        <i class="icon fa fa-toggle-down"></i>
                    </a>
                </li>
				<li class="dropdown">
					<a href="#" id="pdf" data-action="export_pdf"  class="tip" title="<?= lang('download_pdf') ?>">
						<i class="icon fa fa-file-pdf-o"></i>
					</a>
				</li>
                <li class="dropdown">
					<a href="#" id="excel" data-action="export_excel"  class="tip" title="<?= lang('download_xls') ?>">
						<i class="icon fa fa-file-excel-o"></i>
					</a>
				</li>
				 <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("warehouses")?>"></i></a>
                        <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
							<li><a id="five" href="">Show Data 5 Rows</a></li>
							<li><a id="all" href="">Show All Data </a></li>
                        </ul>
                    </li>
            </ul>
        </div>       
    </div>
	<div style="display: none;">
				<input type="hidden" name="form_action" value="" id="form_action"/>
				<?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
			</div>
			<?= form_close() ?>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
				
                <p class="introtext"><?= lang('list_results'); ?></p>
                <div id="form">
				<?php echo form_open('reports/expense_report', 'id="action-form" method="GET"'); ?>
					<div class="row">
    
						<div class="col-sm-4">
                             <div class="form-group">
                                <label class="control-label" for="reference_no"><?= lang("reference_no"); ?></label>
                                <?php echo form_input('reference_no', (isset($_GET['reference_no']) ? $_GET['reference_no'] : ""), 'class="form-control tip" id="reference_no"'); ?>
                            </div>
                        </div>
						 <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("from_date", "from_date"); ?>
                                <?php echo form_input('from_date', (isset($_GET['from_date']) ? $_GET['from_date'] : $this->erp->hrsd($from_date2)), 'class="form-control date" id="from_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("to_date", "to_date"); ?>
                                <?php echo form_input('to_date', (isset($_GET['to_date']) ? $_GET['to_date'] : $this->erp->hrsd($to_date2)), 'class="form-control date" id="to_date"'); ?>
                            </div>
                        </div>		
					
						
						</div>
					<div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary sub"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>
					
                </div>
                <div class="clearfix"></div>
				
                <div class="table-responsive" style="width:100%;overflow:auto;">
					
                    <table id="tbstock" class="table table-condensed table-bordered table-hover table-striped" >
                        <thead>
							<tr>	
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="checkbox" />
								</th> 
								<th><?= lang("date") ?></th>
								<th><?= lang("reference") ?></th>
								<th><?= lang("amount") ?></th>
								<th><?= lang("note") ?></th>
								<th><?= lang("created_by") ?></th>
								
							</tr>
							
						</thead>
                        <tbody>
						<?php
						if(is_array($expense_cat)){
							foreach($expense_cat as $cat){
								$this->db->select($this->db->dbprefix('expenses') . ".id as id, expenses.date, expenses.reference  ,expenses.amount ,expenses.note, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name) as user,expenses.account_code", false)
								->from('expenses')
								->join('users', 'users.id=expenses.created_by', 'left');
								if($reference_no2){
									$this->db->where('expenses.reference',$reference_no2);
								}
								if($from_date2 && $to_date2){
									$this->db->where('date_format(erp_expenses.date,"%Y-%m-%d") >="'.$from_date2.'" AND date_format(erp_expenses.date,"%Y-%m-%d") <="'.$to_date2.'"');
								}
								$q = $this->db->get();
								if($q->num_rows()>0){
									
						?>
							<tr>
								<td style="min-width:30px; width: 30px; text-align: center;background-color:#E9EBEC">
									<input type="checkbox" name="val[]" class="checkbox multi-select input-xs" value="<?=$cat->account_code?>" />
								</td>
								<td colspan="5"><b><span style="color:orange;">Categoty expense</span><span style="color:blue;"> >></span> <span style="color:green;"><?= $cat->account_code?></span> / <span style="color:green;"><?=$cat->narrative?></span></b></td>
								
							</tr>
							<?php 
								$total = 0;
									foreach($q->result() as $row){ 
										if($row->account_code == $cat->account_code){
							?>
							<tr>
								<td></td>
								<td style="text-align:center;"><?=$this->erp->hrld($row->date)?></td>
								<td style="text-align:center;"><?=$row->reference?></td>
								<td style="text-align:right;"><?=$this->erp->formatDecimal($row->amount)?></td>
								<td><?=$row->note?></td>
								<td style="text-align:center;"><?=$row->user?></td>
							</tr>
							
							<?php 
									$total  +=$row->amount;
										}
									}
									?>
							<tr>
								<td></td>
								<td></td>
								<td style="text-align:right;color:green;"><b>Total:</b></td>
								<td style="text-align:right;color:green;"><b><?=$this->erp->formatDecimal($total)?></b></td>
								<td></td>
								<td></td>
							</tr>
						<?php
								}
							}
						}
							?>
                        </tbody>                       
                    </table>
                </div>
				<div class=" text-right">
					<div class="dataTables_paginate paging_bootstrap">
						<?= $pagination; ?>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {

	$(document).on('focus','.date-year', function(t) {
			$(this).datetimepicker({
				format: "yyyy",
				startView: 'decade',
				minView: 'decade',
				viewSelect: 'decade',
				autoclose: true,
			});
	});
    $('#form').hide();
    $('.toggle_down').click(function () {
        $("#form").slideDown();
        return false;
    });
    $('.toggle_up').click(function () {
        $("#form").slideUp();
        return false;
    });
    $('#excel').on('click', function (e) {
		e.preventDefault();
		if ($('.checkbox:checked').length <= 0) {
			//window.location.href = "<?= site_url('reports/expense_report_action/0/xls/'.$reference_no1.'/'.$from_date1.'/'.$to_date1) ?>";
			return false;
		}
	});
	$('#pdf').on('click', function (e) {
		e.preventDefault();
		if ($('.checkbox:checked').length <= 0) {
			//window.location.href = "<?= site_url('reports/expense_report_action/pdf/0/'.$reference_no1.'/'.$from_date1.'/'.$to_date1) ?>";
			return false;
		}
	});	
	
	
	$("#all,#five").click(function(e){
			e.preventDefault();
			var str = $(this).attr("id");	
			if(str == "all"){
				window.location.href = '<?= site_url('reports/expense_report?str=all'); ?>';
			}else{
				window.location.href = '<?= site_url('reports/expense_report?str=five'); ?>';
			}
	});
	
	
});
</script>