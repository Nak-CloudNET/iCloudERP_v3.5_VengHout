<style type="text/css">
    @media print {
        #myModal .modal-content {
            display: none !important;
        }
    }
</style>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
		<div class="modal-body">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			
            <?php if ($logo) { ?>
                <div class="text-center" style="margin-bottom:20px;">
                   <!-- <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">-->
						 <p><b><h4 class="modal-title"><?=lang('product_anlysis');?></h4></b></p>
                </div>
            <?php } ?>
			<!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<h4 class="modal-title"><?=lang('product_anlysis');?></h4>-->
			 <div class="well well-sm">
                <div class="row bold" style="font-size:12px;">
					
                    <div class="col-xs-5">
						<p class="bold">
							<?= lang("ref"); ?>:  <?= $header->reference_no; ?><br>
							<?= lang("date"); ?>:  <?= $this->erp->hrld($header->date); ?><br>
							<?= lang("created_by"); ?>:  <?= lang($header->username); ?><br>
							<?= lang("warehouse"); ?>:  <?= lang($header->name); ?>
						</p>
                    </div>
                    <div class="col-xs-7 text-right">
						<p style="font-size:16px; margin:0 !important;"><!--<?= lang("INVOICE"); ?>--></p>
                        <?php $br = $this->erp->save_barcode($header->reference_no, 'code39', 70, false); ?>
                        <img height="45px" src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $header->reference_no ?>"/>
                       <!-- <?php $this->erp->qrcode('link', urlencode(site_url('sales/view/' . $header->id)), 2); ?>-->
                        <img height="45px" src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $header->reference_no ?>"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
		</div>
        <div class="modal-body print">
			<!-- table show convert from items -->
			<div class="col-md-12">
				<div class="control-group table-group">
					<label class="table-label"><?= lang("convert_items_from"); ?></label>

					<div class="controls table-controls">
						<div class="table-responsive">
							<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped reports-table">
								<thead>
									<tr>
										<th><?= lang('items'); ?></th>
										<th><?= lang('unit'); ?></th>
										<th><?= lang('quantity'); ?></th>
										<th><?= lang('cost'); ?></th>
										<th><?= lang('Total Cost'); ?></th>
										<th><?= lang('percentage'); ?></th>
									</tr>
								</thead>
								<tbody id="show_data">
									<?php
                                    $total_percent = 0;
                                    $total_cost = 0;
                                    $total_qty = 0;
                                    $tcost = 0;
                                    $tocost = 0;
                                    $percentage = 0;
                                        if ($deduct != NULL) {
                                            foreach ($deduct as $anlysis) {
                                                $total_cost = $anlysis->Ccost * $anlysis->Cquantity;
                                                $tcost += $total_cost;
                                            }
                                        }
                                        if ($deduct != NULL) {
                                            foreach ($deduct as $anlysis) {
                                                $num = count($deduct);
                                                $total_qty += $anlysis->Cquantity;
                                                $total_costs = $anlysis->Ccost * $anlysis->Cquantity;
                                                $tocost += $anlysis->Ccost;
                                                if ($tcost != 0) {
                                                    $percentage = ($total_costs * 100) / $tcost;

                                                } else {
                                                    $percentage = ($total_costs * 100);
                                                }
                                                $total_percent += $percentage;
                                                ?>
                                                <tr>
                                                    <td><?= $anlysis->product_name . ' (' . $anlysis->product_code . ')'; ?></td>

                                                    <?php
                                                    $variant = $this->products_model->getProductOptions($anlysis->pid);
                                                    if ($variant) {
                                                        foreach ($variant as $var) {
                                                            if ($var->id == $anlysis->option_id) {
                                                                echo '<td class="text-center"><span class="label label-primary">' . $var->name . '</span></td>';
                                                            }
                                                        }
                                                    } else {
                                                        echo '<td class="text-center"><span class="label label-primary">' . $anlysis->unit . '</span></td>';
                                                    }
                                                    //$this->erp->print_arrays($percentage);
                                                    ?>
                                                    <td class="text-center"><?= $anlysis->Cquantity; ?></td>
                                                    <td class="text-right"><?= $this->erp->formatMoney($anlysis->Ccost); ?></td>
                                                    <td class="text-right"><?= $this->erp->formatMoney($anlysis->Ccost * $anlysis->Cquantity); ?></td>
                                                    <td class="text-right"><?= $this->erp->formatPercentage($percentage); ?>
                                                        %
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
									?>
								</tbody>
								<tfoot>
									<tr>
                                        <th colspan="3"><?= lang('total'); ?></th>
                                        <th class="text-right"><?= $this->erp->formatQuantity($tocost); ?></th>
                                        <th class="text-right"><?= $this->erp->formatQuantity($tcost); ?></th>
                                        <th class="text-right"><?= $this->erp->formatPercentage($total_percent); ?>%
                                        </th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- table show convert from items -->
			<div class="col-md-12">
				<div class="control-group table-group">
					<label class="table-label"><?= lang("convert_to_from"); ?></label>

					<div class="controls table-controls">
						<div class="table-responsive">
							<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped reports-table">
								<thead>
									<tr>
										<th><?= lang('items'); ?></th>
										<th><?= lang('unit'); ?></th>
										<th><?= lang('quantity'); ?></th>
										<th><?= lang('cost'); ?></th>
										<th><?= lang('Total Cost'); ?></th>
										<th><?= lang('percentage'); ?></th>
									</tr>
								</thead>
								<tbody id="show_data">
									<?php
                                    $add_percent = 0;
                                    $add_quantity = 0;
                                    $addCost = 0;
                                    $add_cost = 0;
										$tadd_cost		= 0;
                                    $percentage = 0;
										if ($add != NULL) {
                                            foreach ($add as $total) {
                                                $add_quantity += $total->Cquantity;
                                                $addCost += $total->Ccost;
                                                $add_cost = $addCost;
                                                $tadd_cost += ($total->Ccost * $total->Cquantity);
                                            }
                                        }
                                    if ($add != NULL) {
                                        foreach ($add as $anlysis) {
                                            $cost = $anlysis->Ccost * $anlysis->Cquantity;
                                            if ($tadd_cost != 0) {
                                                $percentage = ($cost * 100) / $tadd_cost;
                                            } else {
                                                $percentage = ($cost * 100);
                                            }
                                            $add_percent += $percentage;
                                            $qty_unit = $anlysis->qty_unit ? $anlysis->qty_unit : 1;
                                            ?>
                                            <tr>
                                                <td><?= $anlysis->product_name . ' (' . $anlysis->product_code . ')'; ?></td>

                                                <?php
                                                $variant = $this->products_model->getProductOptions($anlysis->pid);
                                                if ($variant) {
                                                    foreach ($variant as $var) {
                                                        if ($var->id == $anlysis->option_id) {
                                                            echo '<td class="text-center"><span class="label label-primary">' . $var->name . '</span></td>';
                                                        }
                                                    }
                                                } else {
                                                    echo '<td class="text-center"><span class="label label-primary">' . $anlysis->unit . '</span></td>';
                                                }
                                                ?>
                                                <td class="text-center"><?= $anlysis->Cquantity; ?></td>
                                                <td class="text-right"><?= $this->erp->formatMoney($anlysis->Ccost); ?></td>
                                                <td class="text-right"><?= $this->erp->formatMoney($cost); ?></td>
                                                <td class="text-right"><?= $this->erp->formatPercentage($percentage); ?>
                                                    %
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
									?>
								</tbody>
								<tfoot>
									<tr>
                                        <th colspan="3"><?= lang('total') ?></th>
                                        <th class="text-right"><?= $this->erp->formatMoney($add_cost); ?></th>
                                        <th class="text-right"><?= $this->erp->formatMoney($tadd_cost); ?></th>
                                        <th class="text-right"><?= $this->erp->formatPercentage($add_percent); ?>%</th>
									</tr>
								</tfoot>
							</table>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<div class="well well-sm">
									  <p class="bold"><?= lang("note"); ?>:</p>
									   <div><?= $add[0]->noted; ?></div>
								</div>
							</div>
						  
						</div>
					</div>
				</div>
			</div>
			<div style="clear: both;"></div>
        </div>
    </div>
</div>