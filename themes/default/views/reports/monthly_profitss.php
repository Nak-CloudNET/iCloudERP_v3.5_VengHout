<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('month_profit').' ('.$date.')'; ?></h4>
        </div>
        <div class="modal-body">
            <p><?= lang('unit_and_net_tip'); ?></p>
            <div class="table-responsive">
            <table width="100%" class="stable">
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('products_sale'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;">
                            <h4><span><?= $this->erp->formatMoney($costing->sales); ?></span></h4>
                            <!-- <h4><span><?= $this->erp->formatMoney($costing->sales).' ('.$this->erp->formatMoney($costing->net_sales).')'; ?></span></h4>-->
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('order_discount'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;">
                        <h4>
                            <span><?php $discount = $discount ? $discount->order_discount : 0; echo $this->erp->formatMoney($discount); ?></span>
                        </h4>
                    </td>
                </tr>
				<tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('shipping'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;">
                        <h4>
                            <span><?= $this->erp->formatMoney($shipping->shippings); ?></span>
                        </h4>
                    </td>
                </tr>
				<tr style="display:none;">
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('order_tax'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;">
                        <h4>
                            <span><?= $this->erp->formatMoney($order_tax->order_taxs); ?></span>
                        </h4>
                    </td>
                </tr>
				 <?php if (isset($returns->total)) { ?>
                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><strong><?= lang('sales_refund'); ?></strong>:</h4>
                    </td>
                    <td style="text-align:right;"><h4>
                            <span><strong><?= $this->erp->formatMoney($returns->total); ?></strong></span>
                        </h4></td>
                </tr>
                <?php } ?>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('products_cost'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;">
                        <h4><span><?= $this->erp->formatMoney($costing->cost); ?></span></h4>
                        <!-- <h4><span><?= $this->erp->formatMoney($costing->cost).' ('.$this->erp->formatMoney($costing->net_cost).')'; ?></span></h4> -->
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('expenses'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;">
                        <h4>
                            <span><?php $expense = $expenses ? $expenses->total : 0; echo $this->erp->formatMoney($expense); ?></span>
                        </h4>
                    </td>
                </tr>
                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><strong><?= lang('profit'); ?></strong>:</h4>
                    </td>
                    <td style="text-align:right;">
                        <h4>
                            <span><strong><?= $this->erp->formatMoney($costing->sales - $costing->cost - $discount +$order_tax->order_taxs +$shipping->shippings - $expense); ?></strong></span>
                            <!-- <span><strong><?= $this->erp->formatMoney($costing->sales - $costing->cost - $discount - $expense).' ('.$this->erp->formatMoney($costing->net_sales - $costing->net_cost - $discount - $expense).')'; ?></strong></span> -->
                        </h4>
                    </td>
                </tr>
               
            </table>
            </div>
        </div>
    </div>

</div>
