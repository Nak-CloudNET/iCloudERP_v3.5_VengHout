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
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
            <?php } ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">

                    <h3><?php echo $this->lang->line("Delivery Note"); ?></h3>
                    <thead>

                    <tr>

                        <th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("no"); ?></th>
						<th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("product"); ?></th>
						<th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("Item(Quantity)"); ?></th>
                        <th style="vertical-align:middle;"><?php echo $this->lang->line("description"); ?></th>
                        <th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("quantity"); ?></th>
                    </tr>

                    </thead>
                    <tbody>
                    <?php $r = 1;
                    foreach ($rows as $row): ?>
                        <tr>
                            <td style="text-align:center; width:40px; vertical-align:middle;"><?php echo $r; ?></td>
							
                            <td style="vertical-align:middle;"><?php echo $row->product_name . " (" . $row->product_code . ")";?></td>
								<td style="vertical-align:middle;">
							<?php
							if(!empty(array_filter($combo_details))){
								foreach($combo_details as $combo_detail){
									if( $row->product_code == $combo_detail->product_code){
										echo $combo_detail->item_code . '('. $this->erp->formatQuantity($combo_detail->quantity * $row->quantity) .'), ';
									}
								}
							}
								?>
							</td>
							<td><?php
							if ($row->details) {
                                    echo '<br><strong>' . $this->lang->line("product_details") . '</strong> ' . html_entity_decode($row->details);
                                }
							?></td>
                            <td style="width: 70px; text-align:center; vertical-align:middle;"><?php echo $this->erp->formatQuantity($row->quantity); ?></td>
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

