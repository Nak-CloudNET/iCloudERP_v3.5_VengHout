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
            },
            minLength: 1,
            autoFocus: false,
            delay: 300,
        });
    });
</script>
<style type="text/css">
    .numeric {
        text-align:right !important;
    }
	
</style>
<?php //if ($Owner || $Admin) {
    echo form_open('account/arByCustomer_actions', 'id="action-form"');
    //}
?>
<style>
	#POData .active th,#POData .foot td{
			color: #fff;
			background-color: #428BCA;
			border-color: #357ebd;
	}

</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-star"></i><?=lang('ar_by_customer') . ' (' . lang('All_Customer') . ')';?>
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
                            <a href="javascript:void(0)" id="combine_payable" data-action="combine_payable">
                                <i class="fa fa-money"></i> <?=lang('combine_payable')?>
                            </a>
                        </li>                    
                        <?php if ($Owner || $Admin) { ?>
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
                        <?php }else{ ?>
                            <?php if($GP['accounts-export']) { ?>
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
                            <?php }?>
                        <?php }?>   
                        <li>
                            <a href="#" id="combine" data-action="combine">
                                <i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>
                            </a>
                        </li>
                        <li class="divider"></li>
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
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?>  
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?=lang('list_results');?></p>
                <div id="form">

                    <?php echo form_open("account/ar_by_customer"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ''), 'class="form-control date" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ''), 'class="form-control date" id="end_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("customer", "customer"); ?>
                                <?php echo form_input('customer', (isset($_POST['customer'])? $_POST['customer'] : ''), 'class="form-control" id="customer"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="balance"><?= lang("balance"); ?></label>
                                <?php
                                    $wh["all"] = "All";
                                    $wh["balance0"] = "Zero Balance";
                                    $wh["owe"] = "Owe";
                                
                                echo form_dropdown('balance', $wh, (isset($_POST['balance']) ? $_POST['balance'] : ''), 'class="form-control" id="balance" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("balance") . '"');
                                ?>
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
                    <table id="POData" cellpadding="0" cellspacing="0" border="0" class="table table-condensed table-bordered table-hover table-striped">
                      
                            <tr class="active">
                                <th class="text-center" ><?php echo $this->lang->line("saleman"); ?></th>
                                <th class="text-center"><?php echo $this->lang->line("Project"); ?></th>
                                <th class="text-center"><?php echo $this->lang->line("type"); ?></th>
                                <th class="text-center"><?php echo $this->lang->line("date"); ?></th>
                                <th class="text-center"><?php echo $this->lang->line("reference"); ?></th>
                                <th class="text-center"><?php echo $this->lang->line("amount"); ?></th>
                                <th class="text-center"><?php echo $this->lang->line("return"); ?></th>
                                <th class="text-center"><?php echo $this->lang->line("paid"); ?></th>
                                <th class="text-center"><?php echo $this->lang->line("deposit"); ?></th>
								<th class="text-center"><?php echo $this->lang->line("discount"); ?></th>
								<th class="text-center"><?php echo $this->lang->line("balance"); ?></th>
                            </tr>
             
                        <?php 
								$total_sale2 = 0;
								$total_am2 = 0;
								$total_pay_amoun2 = 0;
								$total_return_amoun2 = 0;
                            foreach($customers as $cus){ 
								$items = $this->accounts_model->getSaleByCustomerV2($cus->customer_id);
								if(is_array($items)){
									$am = 0;
							?>
                            <tr class="success">
                                <th class="th_parent" colspan="11"><?= lang("customer")?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?= $cus->customer?></th>
                            </tr>
							
							<?php
								$total_sale = 0;
								$total_pay_amoun = 0;
								$total_return_amoun = 0;
								$total_am = 0;
									foreach($items as $row){
										$sale = $this->accounts_model->getSaleBySID($row->id);
										$am = $sale->grand_total;
							?>
								<tr>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;<?=$row->fullname?></td>
									<td><?=$sale->biller?></td>
									<td>Invoice</td>
									<td><?=$this->erp->hrsd($sale->date)?></td>
									<td><?=$sale->reference_no?></td>
									<td class="text-right"><?=$this->erp->formatMoney($sale->grand_total)?></td>
									<td  class="text-right"></td>
									<td  class="text-right"></td>
									<td  class="text-right"></td>
									<td  class="text-right"></td>
									<td  class="text-right"><?=$this->erp->formatMoney($am)?></td>
								</tr>
								
								<?php
									$total_pay = 0;
									
									$payment = $this->accounts_model->getPaymentBySID($row->id);
									foreach($payment  as $pay){
										
										if($pay->return_id){
											$pay_return = (-1)*$pay->amount;
											$payy = '('.$this->erp->formatMoney($pay->amount).')';
										}else{
											$pay_return = $pay->amount;
											$payy = $this->erp->formatMoney($pay->amount);
										}
										
										if($pay->paid_by == "deposit"){
											$deposit = 	$payy;
											$payy = '';
										}else{
											$deposit = '';
											
										}
										$am = $am - ($pay_return+$pay->discount);
								?>
								<tr>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$row->fullname?></td>
									<td><?=$pay->biller?></td>
									<td>Payment</td>
									<td><?=$this->erp->hrsd($pay->date)?></td>
									<td><?=$pay->reference_no?></td>
									<td></td>
									<td></td>
									<td class="text-right"><?= $payy?></td>
									<td  class="text-right"><?= $deposit?></td>
									<td  class="text-right"><?=$pay->discount?></td>
									<td  class="text-right"><?php if($am<0){?>(<?=$this->erp->formatMoney(abs($am))?>) <?php }else{ echo $this->erp->formatMoney($am);}?></td>
								</tr>
								
							<?php 
										if($pay->paid_by != "deposit"){
											$total_pay += $pay_return;
										}
									}
							?>
								<?php
								$total_return = 0;
									$return_sale = $this->accounts_model->getReturnBySID($row->id);
									foreach($return_sale as $return){
										$am = $am - $return->grand_total;
								?>
								<tr>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$row->fullname?></td>
									<td><?=$return->biller?></td>
									<td>Return</td>
									<td><?=$this->erp->hrsd($return->date)?></td>
									<td><?=$return->reference_no?></td>
									<td></td>
									<td class="text-right"><?=$this->erp->formatMoney($return->grand_total)?></td>
									<td class="text-right"></td>
									<td class="text-right"></td>
									<td class="text-right"></td>
									<td  class="text-right"><?php if($am<0){?>(<?=$this->erp->formatMoney(abs($am))?>) <?php }else{ echo $this->erp->formatMoney($am);}?></td>
								</tr>
							
							<?php
							$total_return += $return->grand_total;
									}
							?>
							<tr class="noBorder">
								<td colspan="11" style="border:0;"></td>
							</tr>
							<?php
								$total_sale += $sale->grand_total;
								$total_pay_amoun += $total_pay;
								$total_return_amoun += $total_return;
								$total_am += $am;
									}
							?>
							<tr>
									<td class="text-right" colspan="5"><b>Total</b></td>
									
									<td class="text-right"><b><?=$this->erp->formatMoney($total_sale)?></b></td>
									<td class="text-right"><b><?=$this->erp->formatMoney($total_return_amoun)?></b></td>
									<td class="text-right"><b><?=$this->erp->formatMoney($total_pay_amoun)?></b></td>
									<td class="text-right"><b></b></td>
									<td class="text-right"><b></b></td>
									<td class="text-right"><b><?php if($total_am<0){?>(<?=$this->erp->formatMoney(abs($total_am))?>) <?php }else{ echo $this->erp->formatMoney($total_am);}?></b></td>
								</tr>
							<?php
							$total_sale2 +=$total_sale;
							$total_pay_amoun2 +=$total_pay_amoun;
							$total_return_amoun2 +=$total_return_amoun;
							$total_am2 += $total_am;
								}
							}
							?>
							
							<tr class="foot">
								<td class="text-right" colspan="5"><b>Grand Total</b></td>
								<td class="text-right"><b><?=$this->erp->formatMoney($total_sale2)?></b></td>
								<td class="text-right"><b><?=$this->erp->formatMoney($total_return_amoun2)?></b></td>
								<td class="text-right"><b><?=$this->erp->formatMoney($total_pay_amoun2)?></b></td>
								<td class="text-right"><b></b></td>
								<td class="text-right"><b></b></td>
								<td class="text-right"><b><?php if($total_am2<0){?>(<?=$this->erp->formatMoney(abs($total_am2))?>) <?php }else{ echo $this->erp->formatMoney($total_am2);}?></b></td>
							</tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){

        $("#excel").click(function(e){
            e.preventDefault();
            window.location.href = "<?=site_url('Account/arByCustomer/0/xls/'.$customer2.'/'.$start_date2.'/'.$end_date2.'/'.$balance2)?>";
            return false;
        });
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('Account/arByCustomer/pdf/?v=1'.$v)?>";
            return false;
        });

    });
</script>