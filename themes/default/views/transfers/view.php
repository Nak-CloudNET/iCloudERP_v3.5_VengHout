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
                    <!--<img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>"
                         alt="<?= $Settings->site_name; ?>">-->
					<p style="font-size:17px;"><b>LIST TRANSFER</b></p>
                </div>
            <?php } ?>
            <?php
                if ($Settings->system_management == 'project') { ?>
                    <div class="text-center" style="margin-bottom:20px;">
                        <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo2; ?>"
                             alt="<?= $Settings->site_name; ?>">
                    </div>
            <?php } else { ?>
                    <?php if ($logo) { ?>
                        <div class="text-center" style="margin-bottom:20px;">
                            <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                                 alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                        </div>
                    <?php } ?>
            <?php } ?>
            <div class="well well-sm">
                <div class="row bold">
                    <div class="col-xs-4"><?= lang("date"); ?>: <?= $this->erp->hrld($transfer->date); ?>
                        <br><?= lang("ref"); ?>: <?= $transfer->transfer_no; ?>
						<br><?= lang("status"); ?>: <?= lang($transfer->status); ?>
					</div>
                    <div class="col-xs-6 pull-right text-right">
                        <?php $br = $this->erp->save_barcode($transfer->transfer_no, 'code39', 35, false); ?>
                        <img src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $transfer->transfer_no ?>"/>
                        <?php $this->erp->qrcode('link', urlencode(site_url('transfers/view/' . $transfer->id)), 1); ?>
                        <img src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $transfer->transfer_no ?>"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
				
                <div class="clearfix"></div>
            </div>

            <table style="width:100%; margin-bottom:10px !important;">
				<tr>
					<td style="width:50%">
						<table>
							<tr>
								<td><?= lang("from");?>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;</td>
								<td><span style="font-size:17px;"><strong><?= $from_warehouse->name ."&nbsp;&nbsp;(&nbsp".$from_warehouse->code." )"; ?></strong></span></td>
							</tr>
							<tr>
								<td><?=lang("address") ?></td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;</td>
								<td><?= $from_warehouse->address?></td>
							</tr>
							<tr>
								<td><?=lang("Attn")?></td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;</td>
							</tr>
							<?php if($from_warehouse->phone){ ?>
								<tr>
								<td><?=lang("Contact")?></td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;</td> 
								<td><?= $from_warehouse->phone ?> 
								<?php if($from_warehouse->email){ ?>
									<?php echo ' / '.$from_warehouse->email ?></td>
								<?php } ?>
							</tr>
							<?php } ?>
							
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td><?= lang("from");?>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;</td>
								<td><span style="font-size:17px;"><strong><?= $to_warehouse->name ."&nbsp;&nbsp;(&nbsp".$to_warehouse->code." )"; ?></strong></span></td>
							</tr>
							<tr>
								<td><?=lang("address") ?></td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;</td>
								<td><?= $to_warehouse->address?></td>
							</tr>
							<tr>
								<td><?=lang("Attn")?></td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;</td>
							</tr>
							<?php if($to_warehouse->phone){?>
							<tr>
								<td><?=lang("Contact")?></td>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;</td> 
								<td><?= $to_warehouse->phone?> / <?= $to_warehouse->email?></td>
							</tr>
							<?php }?>
						</table>
					</td>
				</tr>
			</table>

            <div class="table-responsive" style="margin-bottom:-20px;">
                <table class="table table-bordered table-hover table-striped order-table">
                    <thead>
                    <tr>
                        <th style="text-align:center; vertical-align:middle;"><?= lang("no"); ?></th>
                        <th style="vertical-align:middle;"><?= lang("description"); ?></th>
                        <th style="vertical-align:middle;"><?= lang("unit"); ?></th>
                        <th style="text-align:center; vertical-align:middle;"><?= lang("quantity"); ?></th>
                        
                    </tr>
                    </thead>

                    <tbody>
                    <?php $r = 1;
					// $total_qty = 0;
                    foreach ($rows as $row){ 
						
                        $product_unit = '';                        
                        if($row->variant){
                            $product_unit = $row->variant;
                        }else{
                            $product_unit = $row->name;
                        }
                    ?>

                        <tr>
                            <td style="text-align:center; width:25px;"><?= $r; ?></td>
                            <!-- <td style="text-align:left;"><?//= $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : ''); ?></td> -->
                            <td><?= $row->product_name;?></td>
                            <td style="text-align:center; width:190px;"><?= $product_unit;?></td>
                            <td style="text-align:center; width:80px; "><?= $this->erp->formatQuantity($row->quantity); ?></td>
                        </tr>
                        <?php $r++;
                        $Tqty += $row->TQty;
						//$this->erp->print_arrays($row);
                    } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;font-weight: bold;"><?=lang('total_qty')?></td>
                            <td style="text-align: center;"><?= $this->erp->formatQuantity($Tqty); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-xs-12">
				
                    <?php if ($transfer->note || $transfer->note != "") { ?>
                        <div class="well well-sm">
                            <p class="bold"><?= lang("note"); ?>:</p>

                            <div><?= $this->erp->decode_html($transfer->note); ?></div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-xs-4 pull-left">
                    <p><?= lang("created_by"); ?>: <?= $created_by->first_name.' '.$created_by->last_name; ?> </p>

                    <p>&nbsp;</p>

                    <p>&nbsp;</p>
                    <hr>
                    <p><?= lang("stamp_sign"); ?></p>
                </div>
                <div class="col-xs-4 col-xs-offset-1 pull-right">
                    <p><?= lang("received_by"); ?>: </p>

                    <p>&nbsp;</p>

                    <p>&nbsp;</p>
                    <hr>
                    <p><?= lang("stamp_sign"); ?></p>
                </div>
            </div>
            <?php if (!$Supplier || !$Customer) { ?>
                <div class="buttons">
                    <div class="btn-group btn-group-justified">
                        <?php if ($transfer->attachment) { ?>
                            <div class="btn-group">
                                <a href="<?= site_url('welcome/download/' . $transfer->attachment) ?>" class="tip btn btn-primary" title="<?= lang('attachment') ?>">
                                    <i class="fa fa-chain"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                                </a>
                            </div>
                        <?php } ?>
                        <div class="btn-group">
                            <a href="<?= site_url('transfers/invoice/' . $transfer->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= site_url('transfers/invoice_chea_kheng/' . $transfer->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('chea_kheng') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('chea_kheng') ?></span>
                            </a>
                        </div><div class="btn-group">
                            <a href="<?= site_url('transfers/invoice_transfer_kh_chea_kheng/' . $transfer->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('transfer_chea_kheng') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice_transfer_kh_chea_kheng') ?></span>
                            </a>
                        </div>
						 <div class="btn-group">
                            <a href="<?= site_url('transfers/invoice_uy_sing/' . $transfer->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('Uy_Sing') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Uy_Sing') ?></span>
                            </a>
                        </div>
                        <?php if ($Owner || $Admin || $GP['transfers-email']) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('transfers/email/' . $transfer->id) ?>" data-toggle="modal" data-target="#myModal2" class="tip btn btn-primary" title="<?= lang('email') ?>">
                                <i class="fa fa-envelope-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('email') ?></span>
                            </a>
                        </div>
                        <?php } ?>

                        <?php if ($Owner || $Admin || $GP['transfers-export']) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('transfers/pdf/' . $transfer->id) ?>" class="tip btn btn-primary" title="<?= lang('download_pdf') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                            </a>
                        </div>
                        <?php } ?>
                        <?php if ($Owner || $Admin || $GP['transfers-edit']) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('transfers/edit/' . $transfer->id) ?>" class="tip btn btn-warning sledit" title="<?= lang('edit') ?>">
                                <i class="fa fa-edit"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
                            </a>
                        </div>
                        <?php } ?>

                        <?php if ($Owner || $Admin || $GP['transfers-delete']) { ?>
                        <div class="btn-group">
                            <a href="#" class="tip btn btn-danger bpo" title="<b><?= $this->lang->line("delete") ?></b>"
                                data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= site_url('transfers/delete/' . $transfer->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                                data-html="true" data-placement="top">
                                <i class="fa fa-trash-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>
                            </a>
                        </div>
                        <?php } ?>
                        
                    </div>
					
					
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function() {
        $('.tip').tooltip();
    });
</script>
