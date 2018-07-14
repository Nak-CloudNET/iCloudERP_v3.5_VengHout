<script>$(document).ready(function () {
        CURI = '<?= site_url('reports/profit_loss'); ?>';
    });</script>
<style>@media print {
        .fa {
            color: #EEE;
            display: none;
        }

        .small-box {
            border: 1px solid #CCC;
        }
    }</style>
<?php
	$start_date=date('Y-m-d',strtotime($start));
	$rep_space_end=str_replace(' ','_',$end);
	$end_date=str_replace(':','-',$rep_space_end);
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-bars"></i><?= lang('profit_loss'); ?></h2>

        <div class="box-icon">
            <div class="form-group choose-date hidden-xs">
                <div class="controls">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text"
                               value="<?= ($start ? $this->erp->hrld($start) : '') . ' - ' . ($end ? $this->erp->hrld($end) : ''); ?>"
                               id="daterange" class="form-control">
                        <span class="input-group-addon"><i class="fa fa-chevron-down"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
					
					<li class="dropdown"><a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>"><i
								class="icon fa fa-file-pdf-o"></i></a></li>
					<li class="dropdown"><a href="#" id="image" class="tip" title="<?= lang('save_image') ?>"><i
								class="icon fa fa-file-picture-o"></i></a></li>
					<li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
                                    class="icon fa fa-building-o tip" data-placement="left"
                                    title="<?= lang("billers") ?>"></i></a>
                            <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
                                aria-labelledby="dLabel">
                                <li><a href="<?= site_url('reports/profit_loss') ?>"><i
                                            class="fa fa-building-o"></i> <?= lang('billers') ?></a></li>
                                <li class="divider"></li>
                                <?php
                                foreach ($billers as $biller) {
                                    echo '<li ' . ($biller_id && $biller_id == $biller->id ? 'class="active"' : '') . '><a href="' . site_url('reports/profit_loss/'.$start.'/'.$end.'/' . $biller->id) . '"><i class="fa fa-building"></i>' . $biller->company . '</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('view_pl_report'); ?></p>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <div class="small-box padding1010 borange">
                                <h4 class="bold">
								<?php
									
									echo anchor("reports/view_profit_lost_purchase/$start_date/$end_date",'<i class="fa fa-money"></i>'. lang('purchases'), 'data-toggle="modal" data-target="#myModal"');
								?>
								</h4>
                                <i class="fa fa-star"></i>
                                <h3 class="bold"><?= $this->erp->formatMoney($total_purchases->total_amount) ?></h3>

                                <p class="bold"><?= $total_purchases->total . ' ' . lang('purchases') ?> </p>

                                <p><?= $total_purchases->total . ' ' . lang('purchases') ?>
                                    & <?= $this->erp->formatMoney($total_purchases->paid) . ' ' . lang('paid') ?>
                                    & <?= $this->erp->formatMoney($total_purchases->tax) . ' ' . lang('tax') ?></p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="small-box padding1010 bdarkGreen">
                                <h4 class="bold">
								<?php
									
									echo anchor("reports/view_profit_lost_sale/$start_date/$end_date",lang('sales'),'data-toggle="modal" data-target="#myModal"');
								?>
								</h4>
                                <i class="fa fa-heart"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_sales->total_amount) ?></h3>

                                <p class="bold"><?= $total_sales->total . ' ' . lang('sales') ?> </p>

                                <p><?= $total_sales->total . ' ' . lang('sales') ?>
                                    & <?= $this->erp->formatMoney($total_sales->paid) . ' ' . lang('paid') ?>
                                    & <?= $this->erp->formatMoney($total_sales->tax) . ' ' . lang('tax') ?> </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <div class="small-box padding1010 bdarkGreen">
                                <h4 class="bold">
								<?php
									
									echo anchor("reports/view_profit_payments_received/$start_date/$end_date",lang('payment_received'),'data-toggle="modal" data-target="#myModal"');
								?>
                                <i class="fa fa-usd"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_received->total_amount) ?></h3>

                                <p class="bold"><?= $total_received->total . ' ' . lang('received') ?> </p>

                                <p><?= $this->erp->formatMoney($total_received_cash->total_amount) . ' ' . lang('cash') ?>
                                    , <?= $this->erp->formatMoney($total_received_cc->total_amount) . ' ' . lang('CC') ?>
                                    , <?= $this->erp->formatMoney($total_received_cheque->total_amount) . ' ' . lang('cheque') ?>
                                    , <?= $this->erp->formatMoney($total_received_ppp->total_amount) . ' ' . lang('paypal_pro') ?>
                                    , <?= $this->erp->formatMoney($total_received_stripe->total_amount) . ' ' . lang('stripe') ?> </p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="small-box padding1010 bgrey">
                                <h4 class="bold">
								<?php
									
									echo anchor("reports/view_profit_payment_return/$start_date/$end_date",lang('payments_return'),'data-toggle="modal" data-target="#myModal"');
								?>
								</h4>
                                <i class="fa fa-usd"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_returned->total_amount) ?></h3>

                                <p><?= $total_returned->total . ' ' . lang('returned') ?></p>

                                <p>&nbsp;</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="small-box padding1010 borange">
                                <h4 class="bold">
								<?php
									
									echo anchor("reports/view_profit_payment_sent/$start_date/$end_date",lang('payments_sent'),'data-toggle="modal" data-target="#myModal"');
								?>
								</h4>
                                <i class="fa fa-usd"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_paid->total_amount) ?></h3>

                                <p><?= $total_paid->total . ' ' . lang('sent') ?></p>

                                <p>&nbsp;</p>
                            </div>
                        </div>
                        <div class="col-sm-2">
							<a href="#">
                            <div class="small-box padding1010 bpurple">
                                <h4 class="bold">
									<?php
									
									echo anchor("reports/view_expense/$start_date/$end_date",lang('expanses'),'data-toggle="modal" data-target="#myModal"');
								?>
								</h4>
                                <i class="fa fa-usd"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_expenses->total_amount) ?></h3>

                                <p class="bold"><?= $total_expenses->total . ' ' . lang('expenses') ?></p>

                                <p>&nbsp;</p>
                            </div>
							</a>
                        </div>
                    </div>
                </div>
				<div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-12">
                            <div class="small-box padding1010 bmGreen">
                                <h4 class="bold"><?= lang('payments') ?></h4>
                                <i class="fa fa-pie-chart"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_received->total_amount - $total_returned->total_amount - $total_paid->total_amount - $total_expenses->total_amount) ?></h3>

                                <p class="bold"><?= $this->erp->formatMoney($total_received->total_amount) . ' ' . lang('received') ?>
                                    - <?= $this->erp->formatMoney($total_returned->total_amount) . ' ' . lang('returned') ?>
                                    - <?= $this->erp->formatMoney($total_paid->total_amount) . ' ' . lang('sent') ?>
                                    -<?= $this->erp->formatMoney($total_expenses->total_amount) . ' ' . lang('expenses') ?></p>
                            </div>
                        </div>
						
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <div class="small-box padding1010 bred">
                                <h4 class="bold"><?= lang('profit_loss') ?></h4>
                                <i class="fa fa-money"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_sales->total_amount - $total_purchases->total_amount) ?></h3>

                                <p><?= $this->erp->formatMoney($total_sales->total_amount) . ' ' . lang('sales') ?>
                                    - <?= $this->erp->formatMoney($total_purchases->total_amount) . ' ' . lang('purchases') ?></p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="small-box padding1010 bpink">
                                <h4 class="bold"><?= lang('profit_loss') ?></h4>
                                <i class="fa fa-money"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney($total_sales->total_amount - $total_purchases->total_amount - $total_sales->tax) ?></h3>

                                <p><?= $this->erp->formatMoney($total_sales->total_amount) . ' ' . lang('sales') ?>
                                    - <?= $this->erp->formatMoney($total_sales->tax) . ' ' . lang('tax') ?>
                                    - <?= $this->erp->formatMoney($total_purchases->total_amount) . ' ' . lang('purchases') ?> </p>
                            </div>
                        </div>
                        
                    </div>
                </div>
				<div class="row">
                    <div class="col-sm-12">
                        
                        <div class="col-sm-6">
                            <div class="small-box padding1010 bblue">
                                <h4 class="bold"><?= lang('net_profit_loss') ?></h4>
                                <i class="fa fa-money"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney(($total_sales->total_amount - $total_sales->tax) - ($total_purchases->total_amount - $total_purchases->tax)) ?></h3>

                                <p>(<?= $this->erp->formatMoney($total_sales->total_amount) . ' ' . lang('sales') ?>
                                    - <?= $this->erp->formatMoney($total_sales->tax) . ' ' . lang('tax') ?>) -
                                    (<?= $this->erp->formatMoney($total_purchases->total_amount) . ' ' . lang('purchases') ?>
                                    - <?= $this->erp->formatMoney($total_purchases->tax) . ' ' . lang('tax') ?>)</p>
                            </div>
                        </div>
						<div class="col-sm-6">
                            <div class="small-box padding1010 bpurple">
                                <h4 class="bold"><?= lang('net_profit_loss') ?></h4>
                                <i class="fa fa-usd"></i>

                                <h3 class="bold"><?= $this->erp->formatMoney(($total_sales->total_amount - $total_sales->tax) - ($total_costs->cost)) ?></h3>

                                <p>(<?= $this->erp->formatMoney($total_sales->total_amount) . ' ' . lang('sales') ?>
                                    - <?= $this->erp->formatMoney($total_sales->tax) . ' ' . lang('tax') ?>) -
                                    (<?= $this->erp->formatMoney($total_costs->cost) . ' ' . lang('costs') ?>
                                    )</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/profit_loss_pdf')?>/" + encodeURIComponent('<?=$start?>') + "/" + encodeURIComponent('<?=$end?>');
            return false;
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
	
    });
</script>
