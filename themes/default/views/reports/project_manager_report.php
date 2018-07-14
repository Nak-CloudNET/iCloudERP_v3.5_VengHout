<style>
.table td:nth-child(6)
{
	text-align: center;
}
.tbl_users
{
	text-align: center !important;
	background:#F3E5F5 !important;
}
.tbl_sales
{
	text-align: center !important;
	background:#BBDEFB !important;
}	
.tbl_sal{
	text-align:right !important;
}
</style>
<?php
	function status($x){
	  
	  if($x == 'completed' || $x == 'paid' || $x == 'sent' || $x == 'received' || $x == 'deposit' || $x == 'active') {
	   return '<div class="text-center"><span class="label label-success">'.lang($x).'</span></div>';
	  }elseif($x == 'pending' || $x == 'book' || $x == 'free' || $x == 'taken' || $x == 'inactive'){
	   return '<div class="text-center"><span class="label label-warning">'.lang($x).'</span></div>';
	  }elseif($x == 'partial' || $x == 'transferring' || $x == 'ordered'  || $x == 'busy'  || $x == 'processing'){
	   return '<div class="text-center"><span class="label label-info">'.lang($x).'</span></div>';
	  }elseif($x == 'due' || $x == 'returned' || $x == 'regular' || $x == 'tranfered'){
	   return '<div class="text-center"><span class="label label-danger">'.lang($x).'</span></div>';
	  }else{
	   return '<div class="text-center"><span class="label label-default">'.lang($x).'</span></div>';
	  }
	}
?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        <?php if ($this->input->post('customer')) { ?>
        $('#customer').val(<?= $this->input->post('customer') ?>).select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "customers/suggestions/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data.results[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            },
			$('#customer').val(<?= $this->input->post('customer') ?>);
        });

        <?php } ?>
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });
    });
</script>
<?php
$datt =$this->reports_model->getLastDate("sales","date");
	if($this->input->post('reference_no')){
		 $reference_no = $this->input->post('reference_no');
	}else{
		 $reference_no =null;
	}  
	if($this->input->post('customer')){
		 $customer = $this->input->post('customer');
	}else{
		 $customer =null;
	} 
	if($this->input->post('start_date')){
		 $start_date =$this->erp->fsd($this->input->post('start_date'));
	}else{
		$start_date =$datt;
	}
	if($this->input->post('end_date')){
		 $end_date =$this->erp->fsd($this->input->post('end_date'));
	}else{
		$end_date =$datt;
	}

?>
<?php
    echo form_open('reports/user_actions', 'id="action-form"');
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('project_manager_report'); ?>
			<?php
				if ($this->input->post('start_date')) {
					echo " From " . $this->input->post('start_date') . " to " . $this->input->post('end_date');
				}
            ?>
		</h2>	
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>">
				<i class="icon fa fa-toggle-up"></i></a></li>
                <li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>">
				<i class="icon fa fa-toggle-down"></i></a></li>
            </ul>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" id="pdf" data-action="export_pdf" class="tip" title="<?= lang('download_pdf') ?>"><i
                            class="icon fa fa-file-pdf-o"></i></a></li>
                <li class="dropdown"><a href="#" id="excel" data-action="export_excel"  class="tip" title="<?= lang('download_xls') ?>"><i
                            class="icon fa fa-file-excel-o"></i></a></li>
                <li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
                            class="icon fa fa-file-picture-o"></i></a></li>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
							class="icon fa fa-building-o tip" data-placement="left"
							title="<?= lang("billers") ?>"></i></a>
					<ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
						aria-labelledby="dLabel">
						<li><a href="<?= site_url('reports/project_manager_report') ?>"><i
									class="fa fa-building-o"></i> <?= lang('billers') ?></a></li>
						<li class="divider"></li>
						<?php
						foreach ($billers as $biller) {
							echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/project_manager_report/' . $biller->id) . '"><i class="fa fa-building"></i>' . $biller->company . '</a></li>';
						}
						?>
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
                <p class="introtext"><?= lang('view_report_staff'); ?></p>
				<div id="form">
                    <?php echo form_open("reports/project_manager_report"); ?>
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
                                <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                <?php echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="customer" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("customer") . '"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="biller"><?= lang("biller"); ?></label>
                                <?php
                                $bl[""] = "";
                                foreach ($billers as $biller) {
                                    $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                }
                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : $this->erp->hrsd($start_date)), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : $this->erp->hrsd($end_date)), 'class="form-control datetime" id="end_date"'); ?>
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
				
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered  table-condensed">
                       
						<thead>
							 
							<tr>
								<th style="min-width:30px; width: 30px; text-align: center;">
									<input class="checkbox checkth" type="checkbox" name="val" />
								</th>
								<th><?php echo lang('first_name'); ?></th>
								<th><?php echo lang('last_name'); ?></th>
								<th><?php echo lang('email'); ?></th>
								<th><?php echo lang('company'); ?></th>
								<th colspan="6"><?php echo lang('group'); ?></th>
							</tr>
							<?php foreach($projects as $pro){ ?>
							<tr class="tbl_users">
								<td style="min-width:30px; width: 30px; text-align: center;">
									<input type="checkbox" name="val[]" class="checkbox multi-select input-xs" value="<?= $pro->id; ?>" />
								</td>
								<td><?php echo $pro->first_name; ?></td>
								<td><?php echo $pro->last_name;?></td>
								<td><?php echo $pro->email;?></td>
								<td><?php echo $pro->company;?></td>
								<td colspan="6"><?php echo $pro->name;?></td>
							</tr>
							
                        </thead>
						
						<?php
						$where ="";
						if($reference_no){
						     $where .=" AND reference_no ='{$reference_no}'";
						}
						if($this->input->post('biller')){
							$where	.=" AND erp_sales.biller_id ='{$this->input->post('biller')}'";
						}
						if($this->input->post('customer')){
							$where .="AND customer_id ='{$this->input->post('customer')}'";
						}
						if($this->input->post('user')){
							$where .="AND erp_users.id ='{$this->input->post('user')}'";
						}
						if($start){
							$where .="AND date_format(date,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' ";
						}
						if($biller_id){
							$where .=" AND erp_sales.biller_id ='{$biller_id}'";
						}
						$sales = $this->db->query("SELECT 
														date,
														erp_sales.id,
														reference_no,
														biller,
														customer,
														quantity,
														grand_total,
														paid,
														(grand_total - paid )as balance,
														payment_status
														FROM erp_sales
														INNER JOIN erp_sale_items on erp_sales.id = erp_sale_items.sale_id
														INNER JOIN erp_users on erp_sales.assign_to_id = erp_users.id
														WHERE assign_to_id={$pro->id} {$where}")->result();	
											
						if(count($sales) > 0){
						?>
						
						<tr class="tbl_sales">
							<td></td>
							<th class="tbl_sales"><?= lang('date'); ?></th>
							<th class="tbl_sales"><?= lang('reference_no'); ?></th>
							<th class="tbl_sales"><?= lang('project'); ?></th>
							<th class="tbl_sales"><?= lang('customer'); ?></th>
							<th class="tbl_sales"><?= lang('quantity'); ?></th>
							<th class="tbl_sales"><?= lang('grand_total'); ?></th>
							<th class="tbl_sales"><?= lang('paid'); ?></th>
							<th class="tbl_sales"><?= lang('balance'); ?></th>
							<th class="tbl_sales"><?= lang('status'); ?></th>
						</tr>
                        <tbody>
							
							<?php 
								$grand_total =0;
								$quantity = 0;
								$paid = 0;
								$balance = 0;
								foreach($sales as $sale)
							{
								$grand_total += $sale->grand_total;
								$paid += $sale->paid;
								$balance += $sale->balance;
								$quantity += $sale->quantity;
								
							?>
							
							<tr style="text-align:center">
							   <td><a  href="<?= site_url('sales/modal_view/'.$sale->id) ?>" data-toggle="modal"
                               data-target="#myModal"><i class="fa fa-list"></i></a></td>
							   <td><?= $sale->date;?></td>
							   <td><?= $sale->reference_no;?></td>
							   <td><?= $sale->biller;?></td>
							   <td><?= $sale->customer;?></td>
							   <td><?= $this->erp->formatMoney($sale->quantity);?></td>
							   <td class="tbl_sal"><?= $this->erp->formatMoney($sale->grand_total);?></td>
							   <td class="tbl_sal"><?= $this->erp->formatMoney($sale->paid);?></td>
							   <td class="tbl_sal"><?= $this->erp->formatMoney($sale->balance);?></td>
							   <td><?= status($sale->payment_status);?></td>
							</tr>
						
							<?php }?>
							
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td style="font-weight:bold"><?= $this->erp->formatMoney($quantity);?></td>
								<td style="font-weight:bold" class="tbl_sal"><?= $this->erp->formatMoney($grand_total);?></td>
								<td style="font-weight:bold" class="tbl_sal"><?= $this->erp->formatMoney($paid);?></td>
								<td style="font-weight:bold" class="tbl_sal"><?= $this->erp->formatMoney($balance);?></td>
								<td></td>
							</tr>
                        </tbody>
                        
						<?php }?>
						<?php }?>
                    </table>
                </div>
					<div class=" text-right">
						<div class="dataTables_paginate paging_bootstrap">
						
						</div>
					</div>
            </div>
        </div>
    </div>
</div>

<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
<?= form_close() ?>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script language="javascript">
	$(document).ready(function () {
		$('#set_admin').click(function () {
			$('#usr-form-btn').trigger('click');
		});
		$('#image').click(function (event) {
		event.preventDefault();
		html2canvas($('.box'), {
			onrendered: function (canvas) {
				var img = canvas.toDataURL()
				window.open(img);
			}
		});
		return false;
	});
	
	$('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/project_manager_report/pdf/?v=1'.$v)?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/project_manager_report/0/xls/?v=1'.$v)?>";
            return false;
        });
	});
</script>
<?php } ?>
