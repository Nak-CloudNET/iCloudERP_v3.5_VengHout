<style>
    .profit {
        margin-top: -10px;
        margin-bottom: 10px;
    }
    .profit td {
        padding-right: 5px;
    }
</style>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			<a href="#" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="sendEmail();">
				<i class="fa fa-send"></i> <?= lang('Send'); ?>
			</a>
            <h4 class="modal-title" id="myModalLabel"><?= lang('day_profit').' ('.$this->erp->hrsd($date).')'; ?></h4>
        </div>
        <div class="modal-body">
            
            <table class="profit">
                <tr>
                    <td><?= lang("start_date", "start_date"); ?></td>
                    <td> <?= lang("end_date", "end_date"); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <div class="controls">
                             <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control input-tip datetime" id="start_date"'); ?>
                        </div>
                    </td>
                    <td>
                        <div class="controls">
                             <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control input-tip datetime" id="end_date"'); ?>
                        </div>
                    </td>
                    <td>
                        <div class="controls">
                            <button type="button" id="filter" class="btn btn-md btn-default no-print pull-right">
                                <i class="fa fa-search" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang('filter'); ?>
                            </button>
                        </div>
                    </td>
                </tr>
            </table>


            <p><?= lang('unit_and_net_tip'); ?></p>
            <div class="table-responsive">
            <table width="100%" class="stable">
                <tr>
                    <td style="border-bottom: 1px solid #EEE;">
						<h4><?= lang('sales_revenue'); ?>:</h4>
					</td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;">
						<h4><span id="revenue">(<?= $this->erp->formatQuantity($revenues->total_items); ?>)<?= $this->erp->formatMoney($revenues->total); ?></span></h4>
                    </td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #DDD;">
						<h4><?= lang('order_discount'); ?>:</h4>
					</td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;">
						<h4>
                            <span id="discount"><?php $discount = $refunds ? $discount : 0; echo '('.$this->erp->formatQuantity($count_dis->count_id).')'.$this->erp->formatMoney($discount); ?></span>
                        </h4>
					</td>
                </tr>
				<tr>
                    <td style="border-bottom: 1px solid #DDD;">
						<h4><?= lang('shipping'); ?>:</h4>
					</td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;">
						<h4>
                            <span id="shipping"><?= '('.$this->erp->formatMoney($count_ship->count_id).')';?><?=$this->erp->formatMoney($shipping); ?></span>
                        </h4>
					</td>
                </tr>
				<tr style="display:none;">
                    <td>
						<h4><?= lang('order_tax'); ?>:</h4>
					</td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;">
						<h4>
                            <span id="order_tax"><?= $this->erp->formatMoney($order_tax); ?></span>
                        </h4>
					</td>
                </tr>
				<tr>
                    <td style="border-bottom: 1px solid #DDD;"><strong><h4><?= lang('sales_refund'); ?>:</h4></strong></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
						<span id="sales_refund">(<?= $this->erp->formatQuantity($refunds->quantity); ?>)<?= $this->erp->formatMoney($refunds->paid); ?></span></h4>
                    </td>
                </tr>
				
				
				<?php if($Admin || $Owner){ ?>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('products_cost'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                        <span id="products_cost">(<?= $this->erp->formatQuantity($costing->total_items); ?>)<?= $this->erp->formatMoney($costing->cost); ?></span>
                    </h4></td>
                </tr>
				<tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('expenses'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                            <span id="expenses"><?php echo '('.$this->erp->formatQuantity($expenses->count_ex).')'. $expense = $expenses ? $this->erp->formatMoney($expenses->total) : 0; ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td width="300px;" style="font-weight:bold;"><h4><strong><?= lang('profit'); ?></strong>:</h4></td>
                    <td style="text-align:right;">
                        <h4>
                            <?php
                                $profit = $revenues->total - $costing->cost - $discount + $shipping + $order_tax - $expense;
                            ?>
                        <span><strong id="profit"><?= $this->erp->formatMoney($profit); ?></strong></span>
                        </h4>
                    </td>
                </tr>
				<?php } ?>
            </table>
            </div>
        </div>
    </div>

</div>

<script>

$(document).ready(function () {
    // CHANTHY
    $('#filter').click(function() {
        var start_date  = $('#start_date').val();
        var end_date    = $('#end_date').val();
         $.ajax({
            type: 'get',
            url: '<?= site_url('reports/profits_json'); ?>',
            data: {
                start_date:start_date,
                end_date:end_date
            },
            dataType: "json",
            success: function (data) {
                console.log(data);

                if (data.revenues.total_items == null) {
                    $('#revenue').html('(<?= $this->erp->formatQuantity() ?>)'+ '<?= $this->erp->formatMoney() ?>');
                } else {
                    $('#revenue').html('('+ data.revenues.total_items +')'+ formatMoney(data.revenues.total));
                }

                if (data.count_dis.count_id == null) {
                    $('#discount').html('(<?= $this->erp->formatQuantity() ?>)'+ '<?= $this->erp->formatMoney() ?>');
                } else {
                    $('#discount').html('('+ data.count_dis.count_id +')'+ formatMoney(data.discount));
                    $('#shipping').html('('+ data.count_dis.count_id +')'+ formatMoney(data.shipping));
                }

                if (data.refunds.quantity == null) {
                    $('#sales_refund').html('(<?= $this->erp->formatQuantity() ?>)'+ '<?= $this->erp->formatMoney() ?>');
                } else {
                    $('#sales_refund').html('('+ data.refunds.quantity +')'+ formatMoney(data.refunds.paid));
                }

                if (data.costing.total_items == null) {
                    $('#products_cost').html('(<?= $this->erp->formatQuantity() ?>)'+ '<?= $this->erp->formatMoney() ?>');
                } else {
                    $('#products_cost').html('('+ data.costing.total_items +')'+ formatMoney(data.costing.cost));
                }

                if (data.expenses.count_ex == 0) {
                    $('#expenses').html('(<?= $this->erp->formatQuantity() ?>)'+ '<?= $this->erp->formatMoney() ?>');
                } else {
                    $('#expenses').html('('+ data.expenses.count_ex +')'+ formatMoney(data.expenses.total));
                }

                var profit = JSON.parse(data.revenues.total) - JSON.parse(data.costing.cost) - JSON.parse(data.discount) + JSON.parse(data.shipping) + JSON.parse(data.order_tax) - JSON.parse(data.expenses.total);

                $('#profit').html(formatMoney(profit));
            }
        });
    });

    // -----------------------------------

	function sendEmail(){
	var email = prompt("<?= lang("email_address"); ?>", "<?= isset($customer->email)?$customer->email:''; ?>");
	if (email != null) {
		$.ajax({
			type: "post",
			url: "<?= site_url('reports/email_receipt') ?>",
            data: {;<?= $this->security->get_csrf_token_name(); ?>:
        "<?= $this->security->get_csrf_hash(); ?>", email;
    :
        email
    },
        "json",
            success;
    :

        function (data) {
				alert(data.msg);
			},
			error: function () {
				alert('<?= lang('ajax_request_failed'); ?>');
				return false;
			}
    })
    }
	return false;
	}
});

</script>
