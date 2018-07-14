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
                <div class="text-center" style="margin-bottom:20px;">
                    <!--<img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">-->
						 <p><b>DELIVERIES</b></p> 
                </div>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-bordered">

                    <tbody>
                    <tr>
                        <td width="30%"><?php echo $this->lang->line("date"); ?></td>
                        <td width="70%"><?php echo $this->erp->hrld($delivery->date); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("do_reference_no"); ?></td>
                        <td><?php echo $delivery->do_reference_no; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("sale_reference_no"); ?></td>
                        <td><?php echo $delivery->sale_reference_no; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("customer"); ?></td>
                        <td><?php echo $delivery->customer; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line("address"); ?></td>
                        <td><?php echo $delivery->address; ?></td>
                    </tr>
                    <?php if ($delivery->note) { ?>
                        <tr>
                            <td><?php echo $this->lang->line("note"); ?></td>
                            <td><?php echo $this->erp->decode_html($delivery->note); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>

                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">

                    <h3><?php echo $this->lang->line("items"); ?></h3>
                    <thead>
						<tr>
							<th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("no"); ?></th>
							
							<?php if($setting->show_code == 0){ ?>
								<th style="vertical-align:middle;"><?php echo $this->lang->line("product_name"); ?></th>
							<?php }else if($setting->separate_code == 0){ ?>
								<th style="vertical-align:middle;"><?php echo $this->lang->line("product_name || product_code"); ?></th>
							<?php }else{ ?>
								<th style="vertical-align:middle;"><?php echo $this->lang->line("product_name"); ?></th>
								<th style="vertical-align:middle;"><?php echo $this->lang->line("product_code"); ?></th>
							<?php } ?>
							
							<th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("quantity"); ?></th>
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
                    <p style="height:80px;"><?= lang("prepared_by"); ?>
                        : <?= $user->first_name . ' ' . $user->last_name; ?> </p>
                    <hr>
                    <p><?= lang("stamp_sign"); ?></p>
                </div>
                <div class="col-xs-4">
                    <p style="height:80px;"><?= lang("delivered_by"); ?>: </p>
					<span><?php echo $row->name; ?></span>
                    <hr>
                    <p><?= lang("stamp_sign"); ?></p>
                </div>
                <div class="col-xs-4">
                    <p style="height:80px;"><?= lang("received_by"); ?>: </p>
                    <hr>
                    <p><?= lang("stamp_sign"); ?></p>
                </div>
            </div>

        </div>
    </div>
</div>

