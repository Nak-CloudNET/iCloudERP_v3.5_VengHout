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
<style>
    #POData .active th{
            color: #fff;
            background-color: #428BCA;
            border-color: #357ebd;
            text-align: center;
    }

    #POData .foot td {
        color: #fff;
        background-color: #428BCA;
        border-color: #357ebd;
    }

</style>
<div class="box">
    <?php
        echo form_open('reports/saleman_detail_action', 'id="action-form"');
    ?>
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-star"></i><?=lang('saleman_detail_report'); ?>
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
                        <?php if ($Owner || $Admin) { ?>
                            <li>
                                <a href="#" id="excel" data-action="excel">
                                    <i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
                                </a>
                            </li>
                            <li>
                                <a href="#" id="pdf" data-action="pdf">
                                    <i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>
                                </a>
                            </li>
                        <?php }else{ ?>
                            <?php if($GP['accounts-export']) { ?>
                                <li>
                                    <a href="#" id="excel" data-action="excel">
                                        <i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" id="pdf" data-action="pdf">
                                        <i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>
                                    </a>
                                </li>
                            <?php }?>
                        <?php }?>
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
        <input type="hidden" name="start_date2" value="<?= $start_date2 ?>" id="start_date2"/>
        <input type="hidden" name="end_date2" value="<?= $end_date2 ?>" id="end_date2"/>
        <input type="hidden" name="saleman2" value="<?= $saleman2 ?>" id="saleman2"/>
        <input type="hidden" name="sales_type2" value="<?= $sales_type2 ?>" id="sales_type2"/>
        <input type="hidden" name="issued_by2" value="<?= $issued_by2 ?>" id="issued_by2"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?= form_close()?>  
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?=lang('list_results');?></p>
                <div id="form">

                    <?php echo form_open("reports/saleman_detail"); ?>
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
                        <div class="col-md-4">
                            <div class="form-group">
                            <?= lang("saleman", "saleman"); ?>
                                <?php 
                                    $saleman['0'] = lang("all");
                                    foreach($agencies as $agency) {
                                        $saleman[$agency->id] = $agency->username;
                                    }
                                    echo form_dropdown('saleman', $saleman, (isset($_POST['saleman']) ? $_POST['saleman'] : ""), 'id="saleman" class="form-control saleman"');
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            <?= lang("sales_type", "sales_type"); ?>
                                <?php
                                    $sales_type = array(
                                        'all' => lang("all"),
                                        'wholesale' => lang("Wholesale"),
                                        'retail' => lang("Retail")
                                    );
                                    echo form_dropdown('sales_type', $sales_type, (isset($_POST['sales_type']) ? $_POST['sales_type'] : ""), 'id="sales_type" class="form-control sales_type"');
                                ?>                          
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                            <?= lang("issued_by", "issued_by"); ?>
                                <?php
                                    $issued_by = array(
                                        'show' => lang("show"),
                                        'hide' => lang("hide")
                                    );
                                    echo form_dropdown('issued_by', $issued_by, (isset($_POST['issued_by']) ? $_POST['issued_by'] : ""), 'id="issued_by" class="form-control issued_by"');
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
                                <th><?php echo $this->lang->line("date"); ?></th>
                                <th><?php echo $this->lang->line("due_date"); ?></th>
                                <th><?php echo $this->lang->line("reference_no"); ?></th>
                                <th><?php echo $this->lang->line("shop"); ?></th>
                                <th style="width: 30% !important"><?php echo $this->lang->line("customer"); ?></th>
                                <th><?php echo $this->lang->line("issued_by"); ?></th>
                                <th><?php echo $this->lang->line("sale_status"); ?></th>
                                <th><?php echo $this->lang->line("grand_total"); ?></th>
                                <th><?php echo $this->lang->line("return"); ?></th>
                                <th><?php echo $this->lang->line("paid"); ?></th>
                                <th><?php echo $this->lang->line("deposit"); ?></th>
                                <th><?php echo $this->lang->line("discount"); ?></th>
                                <th><?php echo $this->lang->line("balance"); ?></th>
                                <th><?php echo $this->lang->line("payment_status"); ?></th>
                            </tr>
             
                        <?php 
                        
                                $total_sale2 = 0;
                                $total_am2 = 0;
                                $total_pay_amoun2 = 0;
                                $total_return_amoun2 = 0;
                            foreach($salemans as $saleman){
                                $items = $this->reports_model->getSalemanReportDetail($saleman->id, $start_date2, $end_date2, $saleman2, $sales_type2, $issued_by2);
								if(is_array($items)){
									$am = 0;
							?>
                            <tr class="success">
                                <th class="th_parent" colspan="14"><?= lang("saleman")?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?= ucwords($saleman->username) ?></th>
                            </tr>
							
							<?php
								$total_sale = 0;
								$total_return_sale = 0;
								$total_paid = 0;
                                $total_deposit = 0;
                                $total_discount = 0;
								$total_balance = 0;
									foreach($items as $row){

							?>
								<tr>
                                    <td><?= $this->erp->hrld($row->date) ?></td>
									<td><?= $row->due_date ? $this->erp->hrld($row->due_date) : '' ?></td>
                                    <td><?= $row->reference_no ?></td>
                                    <td><?= $row->biller ?></td>
                                    <td><?= $row->customer ?></td>
                                    <td>
                                        <?= $row->note ? $row->note : '' ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($row->sale_status == 'completed') { ?>
                                            <span class="label label-success" ><?= lang($row->sale_status); ?></span>
                                        <?php } elseif ($row->sale_status == 'pending') { ?>
                                            <span class="label label-warning" ><?= lang($row->sale_status); ?></span>
                                        <?php } else { ?>
                                            <span class="label label-danger" ><?= lang($row->sale_status); ?></span>
                                        <?php } ?>
                                    </td>
                                    <td class="text-right"><?= $this->erp->formatMoney($row->grand_total) ?></td>
                                    <td class="text-right"><?= $this->erp->formatMoney($row->return_sale) ?></td>
                                    <td class="text-right"><?= $this->erp->formatMoney($row->paid) ?></td>
                                    <td class="text-right"><?= $this->erp->formatMoney($row->deposit) ?></td>
                                    <td class="text-right"><?= $this->erp->formatMoney($row->discount) ?></td>
                                    <td class="text-right"><?= $this->erp->formatMoney($row->balance) ?></td>
									<td class="text-center">
                                        <?php if ($row->payment_status == 'paid') { ?>
                                            <span class="label label-success" ><?= lang($row->payment_status); ?></span>
                                        <?php } elseif ($row->payment_status == 'partial') { ?>
                                            <span class="label label-info" ><?= lang($row->payment_status); ?></span>
                                        <?php } else { ?>
                                            <span class="label label-danger" ><?= lang($row->payment_status); ?></span>
                                        <?php } ?>
                                    </td>
								</tr>
							<?php
                                    $total_sale += $row->grand_total;
                                    $total_return_sale += $row->return_sale;
                                    $total_paid += $row->paid;
                                    $total_deposit += $row->deposit;
                                    $total_discount += $row->discount;
    								$total_balance += $row->grand_total - $row->paid;

									}
							?>
							<tr>
									<td class="text-right" colspan="7"><b>Total</b></td>
									
									<td class="text-right"><b><?= $this->erp->formatMoney($total_sale) ?></b></td>
									<td class="text-right"><b><?= $this->erp->formatMoney($total_return_sale) ?></b></td>
                                    <td class="text-right"><b><?= $this->erp->formatMoney($total_paid) ?></b></td>
                                    <td class="text-right"><b><?= $this->erp->formatMoney($total_deposit) ?></b></td>
									<td class="text-right"><b><?= $this->erp->formatMoney($total_discount) ?></b></td>
                                    <td class="text-right"><b><?= $this->erp->formatMoney($total_balance) ?></b></td>
									<td class="text-center"><b></b></td>
								</tr>
							<?php
                            $grand_total_sales += $total_sale;
                            $grand_total_return += $total_return_sale;
                            $grand_total_paid += $total_paid;
                            $grand_total_deposit += $total_deposit;
                            $grand_total_discount += $total_discount;
							$grand_total_balance += $total_balance;
								}
							}
							?>
							
							<tr class="foot">
								<td class="text-right" colspan="7"><b>Grand Total</b></td>
								<td class="text-right"><b><?=$this->erp->formatMoney($grand_total_sales)?></b></td>
								<td class="text-right"><b><?=$this->erp->formatMoney($grand_total_return)?></b></td>
                                <td class="text-right"><b><?=$this->erp->formatMoney($grand_total_paid)?></b></td>
                                <td class="text-right"><b><?=$this->erp->formatMoney($grand_total_deposit)?></b></td>
                                <td class="text-right"><b><?=$this->erp->formatMoney($grand_total_discount)?></b></td>
								<td class="text-right"><b><?=$this->erp->formatMoney($grand_total_balance)?></b></td>
                                <td class="text-right"><b></b></td>
							</tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){

       /* $("#excel").click(function(e){
            e.preventDefault();
            window.location.href = "<?= site_url('reports/saleman_detail_action/0/xls/'.$saleman2) ?>";
            return false;
        });
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/saleman_detail_action/pdf/?v=1'.$v)?>";
            return false;
        });*/

    });
</script>