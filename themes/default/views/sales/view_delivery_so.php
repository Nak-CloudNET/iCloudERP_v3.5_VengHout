<style type="text/css">
    @media print {
        #noprint {
            display: none !important;
        }
    }
</style>
<?php
	//$this->erp->print_arrays($delivery);
?>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <?php if ($logo) { ?>
                <div class="text-center" style="margin-bottom:20px; font-weight:bold;">
					ប័ណ្ណបញ្ចេញទំនិញ​​​​​<br/>
					DELIVERY GOODS NOTE (<?= $biller->company != '-' ? $biller->company : $biller->name; ?>)
                </div>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="30%"><?php echo $this->lang->line("date"); ?></td>
                            <td width="24%"><?php echo $this->erp->hrld($delivery->date); ?></td>
                            <td><?php echo $delivery->do_reference_no; ?></td>
                            <td><?php echo $delivery->sale_reference_no; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->lang->line("address"); ?></td>
                            <td colspan="3"><?php echo $delivery->address; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" style="font-size:15px;">

                    <h3><?php echo $this->lang->line("items"); ?></h3>
                    <thead>
						<tr>
							<th text-align:center; vertical-align:middle;"><?php echo $this->lang->line("no"); ?></th>
							
							<?php if($setting->show_code == 0){ ?>
								<th style="vertical-align:middle;"><?php echo $this->lang->line("product_name"); ?></th>
							<?php }else if($setting->separate_code == 0){ ?>
								<th style="vertical-align:middle;"><?php echo $this->lang->line("product_name || product_code"); ?></th>
							<?php }else{ ?>
								<th style="vertical-align:middle;"><?php echo $this->lang->line("product_name"); ?></th>
								<th style="vertical-align:middle;"><?php echo $this->lang->line("product_code"); ?></th>
							<?php } ?>
							
                            <th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("quantity"); ?></th>
							<th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("unit"); ?></th>
						</tr>

                    </thead>

                    <tbody>

                    <?php $r = 1;
                    foreach ($rows as $row): ?>
                        <tr>
                            <td style="text-align:center; width:40px; vertical-align:middle;"><?php echo $r; ?></td>
							<?php if($setting->show_code==0){ ?>
								<td style="vertical-align:middle;"><?php echo $row->product_name; ?></td>
							<?php }else if($setting->separate_code==0){ ?>
								<td style="vertical-align:middle;"><?php echo $row->product_name . " (" . $row->code . ")"; ?></td>
							<?php }else{ ?>
								<td style="vertical-align:middle;"><?php echo $row->product_name; ?></td>
								<td style="vertical-align:middle;"><?php echo $row->code; ?></td>
							<?php } ?>
                            <td style="width: 70px; text-align:center; vertical-align:middle;"><?php echo $this->erp->formatQuantity($row->quantity_received); ?></td>
                            <?php if ( !empty($row->variant)) { ?>
                                <td style="width: 70px; text-align:center; vertical-align:middle;"><?php echo $row->variant; ?></td>
                            <?php } else { ?>
                                <td style="width: 70px; text-align:center; vertical-align:middle;"><?php echo $row->unit; ?></td>
                            <?php } ?>
                        </tr>
                        <?php
                        $r++;
                    endforeach;
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <p style="height:80px;font-weight:bold;"><?= lang("prepared_by"); ?> </p>
                    <hr>
                    <p style="font-weight:bold;"><?= lang("stamp_sign"); ?></p>
                </div>
                <div class="col-xs-4">
                    <p style="height:50px;font-weight:bold;"><?= lang("delivered_by"); ?>: </p>
					<span><?php echo $row->name; ?></span>
                    <hr>
                    <p style="font-weight:bold;"><?= lang("stamp_sign"); ?></p>
                </div>
                <div class="col-xs-4">
                    <p style="height:80px;font-weight:bold;"><?= lang("received_by"); ?>: </p>
                    <hr>
                    <p style="font-weight:bold;"><?= lang("stamp_sign"); ?></p>
                </div>
            </div>

            <!-- Footer buttons -->
            <div class="btn-group btn-group-justified" id="noprint">
                <!-- <div class="btn-group">
                    <a href="<?= site_url('sales/add/' . $delivery->id) ?>" class="tip btn btn-primary" title="<?= lang('create_sale') ?>">
                        <i class="fa fa-heart"></i>
                        <span class="hidden-sm hidden-xs"><?= lang('create_sale') ?></span>
                    </a>
                </div> -->

                <div class="btn-group">
                    <a href="<?= site_url('sale_order/delivery_invoice/' . $delivery->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_delivery_invoice') ?>">
                        <i class="fa fa-print"></i>
                        <span class="hidden-sm hidden-xs"><?= lang('print_delivery_invoice') ?></span>
                    </a>
                </div>
                <div class="btn-group">
                    <a href="<?= site_url('sale_order/delivery_note/' . $delivery->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_delivery_invoice') ?>">
                        <i class="fa fa-print"></i>
                        <span class="hidden-sm hidden-xs"><?= lang('delivery_note') ?></span>
                    </a>
                </div>
                <div class="btn-group">
                    <a href="<?= site_url('quotes/edit/' . $delivery->id) ?>" class="tip btn btn-warning sledit" title="<?= lang('edit') ?>">
                        <i class="fa fa-edit"></i>
                        <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

