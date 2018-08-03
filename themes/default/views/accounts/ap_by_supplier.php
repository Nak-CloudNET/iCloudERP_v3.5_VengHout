<!-- <?php //f(isset($this->session->userdata('supplier'))){ echo $this->session->userdata('supplier')exit();}?> -->
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
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-star"></i><?=lang('ap_by_supplier') . ' (' . lang('all_suppliers') . ')';?>
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
            <?php if ($Owner || $Admin || $GP['accounts-export']) { ?>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                         <!-- <li>
                            <a href="javascript:void(0)" id="combine_payable" data-action="combine_payable">
                                <i class="fa fa-money"></i> <?=lang('combine_payable')?>
                            </a>
                        </li> -->
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
                        <!-- <li>
                            <a href="#" id="combine" data-action="combine">
                                <i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>
                            </a>
                        </li> -->
                    </ul>
                </li>
            <?php } ?>
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

                    <?php echo form_open("account/ap_by_supplier"); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', $start_date2?date("d/m/Y", strtotime($start_date2)):'', 'class="form-control date" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', $end_date2?date("d/m/Y", strtotime($end_date2)):'', 'class="form-control date" id="end_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("supplier", "supplier"); ?>
                                <?php echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ""), 'class="form-control" id="supplier"'); ?> </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="control-label" for="balance"><?= lang("balance"); ?></label>
                                <?php
                                    $wh["all"] = "All";
                                    $wh["balance0"] = "Zero Balance";
                                    $wh["owe"] = "Owe";
                                
                                echo form_dropdown('balance', $wh, (isset($_POST['balance']) ? $_POST['balance'] : "all"), 'class="form-control" id="balance" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("balance") . '"');
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
                    <table id="POData" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-condensed table-hover table-striped">
                        <thead>                         
                            <tr class="active">
                                <th class="sorting"><?php echo $this->lang->line("reference_no"); ?></th>
                                <th class="sorting"><?php echo $this->lang->line("date"); ?></th>
                                <th class="sorting" style="width:140px;"><?php echo $this->lang->line("type"); ?></th>
                                <th class="sorting" style="width:140px;"><?php echo $this->lang->line("amount"); ?></th>
                                <th class="sorting" style="width:140px;"><?php echo $this->lang->line("return"); ?></th>
                                <th class="sorting" style="width:140px;"><?php echo $this->lang->line("paid"); ?></th>
                                <th class="sorting" style="width:140px;"><?php echo $this->lang->line("deposit"); ?></th>
                                <th class="sorting" style="width:140px;"><?php echo $this->lang->line("discount"); ?></th>
                                <th class="sorting" style="width:140px;"><?php echo $this->lang->line("balance"); ?></th>
                            </tr>
                        </thead>
                        <?php 
                            $total_amount       = 0;
                            $total_return       = 0;
                            $total_paid         = 0;
                            $total_deposit      = 0;
                            $total_discount     = 0;

                            foreach($suppliers as $supplier)
                            {
                                $items          = $this->accounts_model->getApBySupplier($supplier->id,$start_date2, $end_date2);
                                if($start_date2)
                                {
                                    $old_amount    = $this->accounts_model->getSuppilerOldAmount($supplier->id,$start_date2, $end_date2);
                                    $old_payment   = $this->accounts_model->getSuppilerOldPayment($supplier->id,$start_date2, $end_date2);
                                    $old_balance   = $old_amount[0]->amount-($old_payment[0]->paid+$old_payment[0]->discount); 
                                }else{
                                    $old_balance    = 0;
                                }
                                
                                $sup_balance    = $old_balance;
                                $amount         = $start_date2?$old_balance:0;
                                $return         = 0;
                                $paid           = 0;
                                $deposit        = 0;
                                $discount       = 0;
                                $check          = $this->accounts_model->getTotalSupplierBalance($supplier->id);
                                if($check[0]->amount>0){
                        ?>
                        <tr>
                            <th class="th_parent" colspan="8"><?= lang("supplier")?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?= $supplier->company ?></th>
                            <th class="numeric"><?= $this->erp->formatMoney($old_balance) ?></th>
                        </tr>
                        
                            <?php 
                               
                                foreach($items as $data)
                                {
                                    $amount      += $data->amount;
                                    $return      += $data->return_amount;
                                    $paid        += $data->paid;
                                    $deposit     += $data->deposit;
                                    $discount    += $data->discount;
                                    $sup_balance += $data->amount-($data->return_amount+$data->paid+$data->deposit+$data->discount);
                            ?>
                                <tr>
                                    <td nowrap="nowrap"><?= $data->reference_no ?></td>
                                    <td><?= $this->erp->hrsd($data->date) ?></td>
                                    <td><?= $data->type ?></td>
                                    <td class="numeric"><?= $data->amount!=0?$this->erp->formatMoney($data->amount):'' ?></td>
                                    <td class="numeric"><?= $data->return_amount!=0?$this->erp->formatMoney($data->return_amount):'' ?></td>
                                    <td class="numeric"><?= $data->paid!=0?$this->erp->formatMoney($data->paid):'' ?></td>
                                    <td class="numeric"><?= $data->deposit!=0?$this->erp->formatMoney($data->deposit):'' ?></td>
                                    <td class="numeric"><?= $data->discount!=0?$this->erp->formatMoney($data->discount):'' ?></td>
                                    <td class="numeric"><?= $this->erp->formatMoney($sup_balance) ?></td>
                                </tr>
                        <?php

                                }  
                        ?>
                                 <tr style="font-weight:bold;" class="success">
                                    <td colspan="3" align="right" ><?= lang("total")?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> </td>
                                    <td class="numeric"><?= $this->erp->formatMoney($amount) ?></td>
                                    <td class="numeric"><?= $this->erp->formatMoney($return) ?></td>
                                    <td class="numeric"><?= $this->erp->formatMoney($paid) ?></td>
                                    <td class="numeric"><?= $this->erp->formatMoney($deposit) ?></td>
                                    <td class="numeric"><?= $this->erp->formatMoney($discount) ?></td>
                                    <td class="numeric"><?= $this->erp->formatMoney($sup_balance) ?></td>
                                </tr>
                        <?php
                            }
                            $total_amount   += $amount;
                            $total_return   += $return;
                            $total_paid     += $paid;
                            $total_deposit  += $deposit;
                            $total_discount += $discount;
                            $gbalance       = $total_amount-($total_paid+$total_return+$total_deposit+$total_discount);
                            }                             
                        ?>
                                <tr style="font-weight:bold;" class="warning">
                                    <td colspan="3" align="right" ><?= lang("total")?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> </td>
                                    <td class="numeric"><?= $this->erp->formatMoney($total_amount) ?></td>
                                    <td class="numeric"><?= $this->erp->formatMoney($total_return) ?></td>
                                    <td class="numeric"><?= $this->erp->formatMoney($total_paid) ?></td>
                                    <td class="numeric"><?= $this->erp->formatMoney($total_deposit) ?></td>
                                    <td class="numeric"><?= $this->erp->formatMoney($total_discount) ?></td>
                                    <td class="numeric"><?= $this->erp->formatMoney($gbalance) ?></td>
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
            window.location.href = "<?=site_url('Account/apBySupplier/0/xls/'.$start_date2.'/'.$end_date2.'/'.$supplier2.'/'.$balance2)?>";
            return false;
        });
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('Account/apBySupplier/pdf/?v=1'.$v)?>";
            return false;
        });

    });
</script>