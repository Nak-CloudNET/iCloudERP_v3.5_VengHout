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
					<p><b>PURCHASES REQUEST</b></p>
                </div>
            <?php } ?>
            <div class="well well-sm">
                <div class="row bold">
                    <div class="col-xs-5">
                    <p class="bold">
                        <?= lang("ref"); ?>: <?= $inv->reference_no; ?><br>
                        <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?><br>
							
                        <?= lang("status"); ?>:
						<?php if ($inv->status == 'requested') { ?>
							<span class="label label-default"><?= ucfirst($inv->status); ?></span>
						<?php } else if ($inv->status == 'reject') { ?>
							<span class="label label-danger"><?= ucfirst($inv->status); ?></span>
						<?php } else { ?>
							<span class="label label-success"><?= ucfirst($inv->status); ?></span>
						<?php } ?>
						<br>
                    </p>
                    </div>
                    <div class="col-xs-7 text-right">
                        <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 70, false); ?>
                        <img src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png" alt="<?= $inv->reference_no ?>"/>
                        <?php $this->erp->qrcode('link', urlencode(site_url('purchases/view/' . $inv->id)), 2); ?>
                        <img src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png" alt="<?= $inv->reference_no ?>"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="row" style="margin-bottom:15px;">
                <div class="col-xs-6">
                    <table>
                        <tr>
                            <td><?=$this->lang->line("from");?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?="<span style='font-weight:bold; font-size:17px;'>". $inv->company  ."</span>";?></td>
                        </tr>
                        <tr>
                            <td><?=lang("address") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($warehouse->address ? $warehouse->address." " : ''). ($warehouse->city ? $warehouse->city . " " . $warehouse->postal_code . " " . $warehouse->state . " " : '') . ($warehouse->country ? $warehouse->country : ''); ?></td>
                        </tr>
                        <?php //f ($inv->username != ""){?>
                        <tr>
                            <td>Attn</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$inv->username;?></td>
                        </tr>
                        <?php //}?>
                        <?php if ($warehouse->phone !='' || $warehouse->email !=''): ?>
                        <tr>
                            <td><?=lang("contact") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($warehouse->phone ? $warehouse->phone.'/'.$warehouse->email : $warehouse->email) ?></td>
                        </tr>
                        <?php endif ?>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table>
                        <tr>
                            <td><?=$this->lang->line("to");?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?="<span style='font-weight:bold; font-size:17px;'>". ($supplier->company ? $supplier->company : $supplier->name) ."</span>";?></td>
                        </tr>
                        <tr>
                            <td><?=lang("address") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($supplier->address ? $supplier->address." " : ""). ($supplier->city ? $supplier->city.", " : "") . ($supplier->postal_code ? $supplier->postal_code.", " : "") . ($supplier->state ? $supplier->state.", " : "") .  ($supplier->country ? $supplier->country : ""); ?></td>
                        </tr>
                        <?php //if ($supplier->company == ""){?>
                        <tr>
                            <td>Attn</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$supplier->name;?></td>
                        </tr>
                        <?php //}?>
                        <?php if ($supplier->phone !='' || $supplier->email !=''): ?>
                        <tr>
                            <td><?=lang("contact") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($supplier->phone ? $supplier->phone.'/'.$supplier->email : $supplier->email) ?></td>
                        </tr>
                        <?php endif ?>
                    </table>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped print-table order-table">

                    <thead>

                    <tr>
                        <th><?= lang("no"); ?></th>
                        <th><?= lang("description"); ?></th> 
                        <th><?= lang("quantity"); ?></th>
                        <th><?= lang("unit"); ?></th> 
                        <?php
                            if ($inv->status == 'partial') {
                                echo '<th>'.lang("received").'</th>';
                            }
                        ?> 
						<?php if($Owner || $Admin || $GP['purchase_request-cost']) {?>
							<th><?= lang("unit_cost"); ?></th>
						<?php } ?>
                        
						<?php
                        if ($Settings->product_discount) {
                            echo '<th>' . lang("discount") . '</th>';
                        }
                        if ($Settings->tax1) {
                            echo '<th>' . lang("tax") . '</th>';
                        }
                        ?>
                        <th><?= lang("amount").'('.$default_currency->code.')'; ?></th>
                    </tr>

                    </thead>

                    <tbody>

                    <?php $r = 1;
                    $tax_summary = array();
                    foreach ($rows as $row):
                    ?>
                        <tr>
                            <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                            <td style="vertical-align:middle;">
                                <?= $row->product_name . " (" . $row->product_code . ")";?>
                                <?= $row->details ? '<br>' . $row->details : ''; ?>
                                <?= ($row->expiry && $row->expiry != '0000-00-00') ? '<br>' . $this->erp->hrsd($row->expiry) : ''; ?>
                            </td> 
                            <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                            <td>
                                <?php if($row->variant){ echo $row->variant;}else{echo $row->pro_unit;}?>
                            </td>
                            <?php
                            if ($inv->status == 'partial') {
                                echo '<td style="text-align:center;vertical-align:middle;width:80px;">'.$this->erp->formatQuantity($row->quantity_received).'</td>';
                            }
                            ?>
                            <?php if($Owner || $Admin || $GP['purchase_request-cost']) {?>
								<td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->unit_cost); ?></td>
                            <?php } ?>
                            <?php
                            if ($Settings->product_discount) {
                                $percentage = '%';
                                $discount = $row->discount;
                                $dpos = strpos($discount, $percentage);
                                echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' .($dpos == true ? '<small>('.$discount.')</small>' : '').' '. $this->erp->formatMoney($row->item_discount) . '</td>';
                            }
                            if ($Settings->tax1) {
                                echo '<td style="width: 120px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_name ? '<small>('.$row->tax_name.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
                            }
                            ?>
                            <td style="text-align:right; width:120px;"><?= $this->erp->formatMoney($row->subtotal); ?></td>
                        </tr>
                        <?php
                        $r++;
                    endforeach;
                    ?>
                    </tbody>
                    <tfoot style="font-size: 13px;">
                        <?php
                            $col = 4;
                            if($Owner || $Admin || $GP['purchase_request-cost']) {
                                $col = 4;
                            } else {
                                $col = 3;
                            }
                            
                            if ($Settings->product_discount) {
                                $col++;
                            }
                            if ($Settings->tax1) {
                                $col++;
                            }
                        ?>

                        <tr>
                            <td colspan="<?= $col; ?>"></td>
                            <td
                                style="text-align:right; padding-right:10px; font-weight:bold; vertical-align: middle;"><?= lang("total"); ?>
                                <!-- (<?= $default_currency->code; ?>) -->
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold; vertical-align: middle;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
                        </tr>

                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <?php
                        if ($inv->note || $inv->note != "") { ?>
                            <div class="well well-sm">
                                <p class="bold"><?= lang("note"); ?>:</p>
                                <div><?= $this->erp->decode_html($inv->note); ?></div>
                            </div>
                        <?php
                        }
                        ?>
                </div>

                <div class="col-xs-5 pull-right">
                    <div class="well well-sm">
                        <p>
                            <?= lang("created_by"); ?>: <?= $created_by->first_name . ' ' . $created_by->last_name; ?> <br>
                            <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?>
                        </p>
                        <?php if ($inv->updated_by) { ?>
                        <p>
                            <?= lang("updated_by"); ?>: <?= $updated_by->first_name . ' ' . $updated_by->last_name;; ?><br>
                            <?= lang("update_at"); ?>: <?= $this->erp->hrld($inv->updated_at); ?>
                        </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if (!$Supplier || !$Customer) { ?>
                <div class="buttons">
                    <?php if ($inv->attachment) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>" class="tip btn btn-primary" title="<?= lang('attachment') ?>">
                                <i class="fa fa-chain"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                            </a>
                        </div>
                    <?php } ?>
                    <div class="btn-group btn-group-justified">
                        <?php if($Owner || $Admin || $GP['purchase_request-authorize']) { ?>
                            <?php if ($inv->order_status != 'completed' && $inv->status =='approved') { ?>
    						<div class="btn-group">
                                <a href="<?= site_url('purchases_request/Unapproved/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('unapproved') ?>">
                                    <i class="fa fa-check"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('unapproved') ?></span>
                                </a>
                            </div>
                            <?php }else if($inv->order_status != 'completed'&& $inv->status == 'requested'){ ?>
    						<div class="btn-group">
                                <a href="<?= site_url('purchases_request/update_purchases_request/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('approved') ?>">
                                    <i class="fa fa-check"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('approved') ?></span>
                                </a>
                            </div>
    						<?php }?>
                        <?php } ?>

						<div class="btn-group">
                            <a href="<?= site_url('purchases_request/invoice_phum_meas/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_purchase_request') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print_purchase_request') ?></span>
                            </a>
                        </div>

                        <?php if($Owner || $Admin || $GP['purchases-email']) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('purchases/email/' . $inv->id) ?>" data-toggle="modal" data-target="#myModal2" class="tip btn btn-primary" title="<?= lang('email') ?>">
                                <i class="fa fa-envelope-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('email') ?></span>
                            </a>
                        </div>
                        <?php } ?>
                        <?php if($Owner || $Admin || $GP['purchase_request-export']) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('purchases_request/pdf/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('download_pdf') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                            </a>
                        </div>
                        <?php } ?>
                        <?php  if($Owner || $Admin || $GP['purchase_request-edit']) { ?>
                            <?php if ($inv->status != 'approved' && $inv->status != 'reject') { ?>
                            <div class="btn-group">
                                <a href="<?= site_url('purchases_request/edit/' . $inv->id) ?>" class="tip btn btn-warning sledit" title="<?= lang('edit') ?>">
                                    <i class="fa fa-edit"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
                                </a>
                            </div>
    						<?php } ?>
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
