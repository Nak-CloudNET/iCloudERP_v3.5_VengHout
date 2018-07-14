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
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : $start_date), 'class="form-control date" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : $end_date), 'class="form-control date" id="end_date"'); ?>
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
                            foreach($my_data as $sup){
                                $gbalance=0;
                        ?>
                        <tr>
                            <th class="th_parent" colspan="9"><?= lang("supplier")?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <?= $sup['supplierName'] ?></th>
                        </tr>
                        
                        <?php 
                            $subTotal = $subReturn = $subDeposit = $subPaid = $subDiscount = 0;
                                foreach($sup['supplierDatas']['suppPO'] as $suppData){
                                    $subTotal += $suppData->grand_total;
                                    $subReturn += $suppData->amount_return;
                                    $subDeposit += $suppData->amount_deposit;
                                    $subDiscount += $suppData->order_discount;
                                    $sub_balance = ($suppData->grand_total - $suppData->amount_return - $suppData->amount_deposit - $suppData->order_discount);
                                    $gbalance += $sub_balance;
                                    $type = (explode('/', $suppData->reference_no)[0]=='PO'?"Purchase":(explode('/', $suppData->reference_no)[0]=='PV'?"Payment":"Not Assigned"));
                        ?>
                                    <tr>
                                        <td nowrap="nowrap"><?= $suppData->reference_no ?></td>
                                        <td><?= $this->erp->hrsd($suppData->date) ?></td>
                                        <td><?= $type ?></td>
                                        <td class="numeric"><?= $this->erp->formatMoney($suppData->grand_total) ?></td>
                                        <td class="numeric"><?= $this->erp->formatMoney($suppData->amount_return) ?></td>
                                        <td class="numeric"><?= $this->erp->formatMoney(0) ?></td>
                                        <td class="numeric"><?= $this->erp->formatMoney($suppData->amount_deposit) ?></td>
                                        <td class="numeric"><?= $this->erp->formatMoney($suppData->order_discount) ?></td>
                                        <td class="numeric"><?= $this->erp->formatMoney($sub_balance) ?></td>
                                    </tr>
                            <?php   
                                if(is_array($suppData->payments)){
                                    foreach($suppData->payments as $supPmt){
                                        $subPaid += abs($supPmt->amount);
                                        $typePV = (explode('/', $supPmt->reference_no)[0]=='PO'?"Purchase":(explode('/', $supPmt->reference_no)[0]=='PV'?"Payment":"Not Assigned"));
                                
                            ?>
                                        <tr class="success">
                                            <td nowrap="nowrap" style="text-align:right;"><?= $supPmt->reference_no ?></td>
                                            <td><?= $this->erp->hrsd($supPmt->date) ?></td>
                                            <td><?= $typePV ?></td>
                                            <td class="numeric"></td>
                                            <td class="numeric"></td>
                                            <td class="numeric"><?= $this->erp->formatMoney(abs($supPmt->amount)) ?></td>
                                            <td class="numeric"></td>
                                            <td class="numeric"></td>
                                            <td class="numeric"><?= $this->erp->formatMoney($sub_balance - abs($supPmt->amount)) ?></td>
                                        </tr>
                            <?php
                                        $gbalance -= abs($supPmt->amount);
                                        $sub_balance -= abs($supPmt->amount);
                                    }
                                }
                                }                               
                            ?>
                                    <tr style="font-weight:bold;">
                                        <td colspan="3" align="right" ><?= lang("total")?> <i class="fa fa-angle-double-right" aria-hidden="true"></i> </td>
                                        <td class="numeric"><?= $this->erp->formatMoney($subTotal) ?></td>
                                        <td class="numeric"><?= $this->erp->formatMoney($subReturn) ?></td>
                                        <td class="numeric"><?= $this->erp->formatMoney($subPaid) ?></td>
                                        <td class="numeric"><?= $this->erp->formatMoney($subDeposit) ?></td>
                                        <td class="numeric"><?= $this->erp->formatMoney($subDiscount) ?></td>
                                        <td class="numeric"><?= $this->erp->formatMoney($gbalance) ?></td> 
                                    </tr>
                        <?php
                            }
                        ?>
                        
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