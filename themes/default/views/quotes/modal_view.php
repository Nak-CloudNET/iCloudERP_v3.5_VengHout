<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
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
			<div class="row">
				<div class="col-sm-12" style="text-align:right; margin-bottom:5px;">
					<b><?= lang('quotation') ?></b>
				</div>
			</div>
            <div class="well well-sm">
                <div class="row bold">
                    <div class="col-xs-5">
                    <p class="bold">
                        <?= lang("ref"); ?>: <?= $inv->reference_no; ?><br>
                        <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?><br>
                        <?= lang("quote_status"); ?>:

                        <?php if ($inv->status == 'approved') { ?>
                            <span class="label label-success"><?= ucfirst($inv->status); ?></span>
                        <?php } elseif ($inv->status == 'pending') { ?>
                            <span class="label label-warning"><?= ucfirst($inv->status); ?></span>
                        <?php } else { ?>
                            <span class="label label-danger"><?= ucfirst($inv->status); ?></span>
                        <?php } ?>
                        <br>
                    </p>
                    </div>
                    <div class="col-xs-7 text-right">
                        <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 70, false); ?>
                        <img src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>"/>
                        <?php $this->erp->qrcode('link', urlencode(site_url('quotes/view/' . $inv->id)), 2); ?>
                        <img src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>"/>
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
                            <td><?="<span style='font-weight:bold; font-size:17px;'>". ($biller->company != '-' ? $biller->company : $biller->name) ."</span>";?></td>
                        </tr>
                        <tr>
                            <td><?=lang("address") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($biller->address ? $biller->address." " : ''). ($biller->city ? $biller->city . " " . $biller->postal_code . " " . $biller->state . " " : '') . ($biller->country ? $biller->country : ''); ?></td>
                        </tr>
                        <?php //if ($supplier->company == ""){?>
                        <tr>
                            <td>Attn</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$biller->name;?></td>
                        </tr>
                        <?php //}?>
                        <?php if ($biller->phone !='' || $biller->email !=''): ?>
                        <tr>
                            <td><?=lang("contact") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($biller->phone ? $biller->phone.'/'.$biller->email : $biller->email) ?></td>
                        </tr>
                        <?php endif ?>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table>
                        <tr>
                            <td><?=$this->lang->line("to");?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?="<span style='font-weight:bold; font-size:17px;'>". ($customer->company ? $customer->company : $customer->names)."</span>";?></td>
                        </tr>
                        <tr>
                            <td><?=lang("address") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$customer->address?></td>
                        </tr>
                        <tr>
                            <td>Attn</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$customer->names;?></td>
                        </tr>
                        <?php if ($customer->phone !='' || $customer->email !=''): ?>
                        <tr>
                            <td><?=lang("contact") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=($customer->phone ? $customer->phone.'/'.$customer->email : $customer->email) ?></td>
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
                        <th><?= lang("image"); ?></th>
                        <th><?= lang("description"); ?></th>
                        <th><?= lang("quantity"); ?></th>
                        <th><?= lang("unit_price"); ?></th>
                        <?php
                        if ($Settings->product_discount) {
                            echo '<th>' . lang("discount") . '</th>';
                        }
                        if ($Settings->tax1) {
                            echo '<th>' . lang("tax") . '</th>';
                        }
                        ?>
                        <th><?= lang("amount"); ?>(<?= $default_currency->code; ?>)</th>
                    </tr>

                    </thead>

                    <tbody>
					
                    <?php $r = 1;
                    $tax_summary = array();
                    if (is_array($rows)) {
                        foreach ($rows as $row):
                    ?>
                        <tr>
                            <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                            <td style="text-align:center; vertical-align:middle;"><?= '<img class="img-rounded img-thumbnail" style="width:60px;height:60px;" src="assets/uploads/thumbs/'.$row->image.'">' ?></td>
                            <td style="vertical-align:middle;">
                                <?= $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : ''); ?>
                                <?= $row->details ? '<br>' . $row->details : ''; ?>
                            </td>
                            <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                            <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->unit_price); ?></td>
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
                    }
                    ?>
                    </tbody>
                    <tfoot>
                    <?php
                    $col = 5;
                    if ($Settings->product_discount) {
                        $col++;
                    }
                    if ($Settings->tax1) {
                        $col++;
                    }
                    if ($Settings->product_discount && $inv->product_discount != 0 && $Settings->tax1) {
                        $tcol = $col - 2;
                    } elseif ($Settings->product_discount && $inv->product_discount != 0) {
                        $tcol = $col - 1;
                    } elseif ($Settings->tax1) {
                        $tcol = $col - 1;
                    } else {
                        $tcol = $col;
                    }
                    ?>
                    <?php if ($inv->grand_total != $inv->total) { ?>
                        <tr>
                            <td colspan="7"
                                style="text-align:right; padding-right:10px;"><?= lang("total"); ?>
                                
                            </td>
                            <?php
                            // if ($Settings->product_discount && $inv->product_discount != 0) {
                            //     echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_discount) . '</td>';
                            // }
                            // if ($Settings->tax1) {
                            //     echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_tax) . '</td>';
                            // }
                            ?>
                            <td style="text-align:right; padding-right:10px;"><?= $this->erp->formatMoney($inv->total); ?></td>
                        </tr>
                    <?php } ?>

                    <?php if ($inv->order_discount != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") .'</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
                    }
                    ?>
                    <?php if ($inv->shipping != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . '</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
                    }
                    ?>
                    <?php if ($Settings->tax2 && $inv->order_tax != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . $inv->tax_name . '</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
                    }
                    ?>
					
                    <tr>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                        </td>
                        <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total - (isset($deposit->deposit_amount)?$deposit->deposit_amount:0)); ?></td>
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
                        <?php } ?>
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

                        <!--<?php if ($inv->issue_invoice == 'pending' && ($inv->status != 'rejected' && $inv->status == 'approved')) { ?>
    						<div class="btn-group">
                                <a href="<?= site_url('sale_order/add_sale_order/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('create_sale_order') ?>">
                                    <i class="fa fa-star"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('create_sale_order') ?></span>
                                </a>
                            </div>
    						
                            <div class="btn-group">
                                <a href="<?= site_url('sales/add/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('create_sale') ?>">
                                    <i class="fa fa-heart"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('create_sale') ?></span>
                                </a>
                            </div>
                        <?php } ?>
						
						<div class="btn-group">
                            <a target='_blank' href="<?= site_url('Quotes/invoice_quotes/'.$inv->id) ?>" class="tip btn btn-primary" title="<?= lang('quotes_invoice') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('quotes_invoice') ?></span>
                            </a>
                        </div>
						<!--
                        <div class="btn-group">
                            <a target='_blank' href="<?= site_url('Quotes/quotes_chea_kheng/'.$inv->id) ?>" class="tip btn btn-primary" title="<?= lang('quotes_chea_kheng') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('quotes_chea_kheng') ?></span>
                            </a>
                        </div>

                        <div class="btn-group">
                            <a href="<?= site_url('Quotes/quote_/'.$inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice') ?></span>
                            </a>
                        </div>-->
                  <!--       <div class="btn-group">
                            <a href="<?= site_url('Quotes/quote_invoice_thai_san/' . $inv->id) ?>" target="_blank"
                               class="tip btn btn-primary" title="<?= lang('print_quote') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Print_Quote') ?></span>
                            </a>
                        </div> -->
                        <div class="btn-group">
                            <a href="<?= site_url('Quotes/invoice_standard/' . $inv->id) ?>" target="_blank"
                               class="tip btn btn-primary" title="<?= lang('print_quote') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Print_Quote') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= site_url('Quotes/invoice_iphoto/' . $inv->id) ?>" target="_blank"
                               class="tip btn btn-primary" title="<?= lang('print_quote') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Print_iPhoto_invoice') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= site_url('Quotes/invoice_camera_city/' . $inv->id) ?>" target="_blank"
                               class="tip btn btn-primary" title="<?= lang('print_quote') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('camera_city') ?></span>
                            </a>
                        </div>
                        <!--<div class="btn-group">
                            <a href="<?= site_url('Quotes/invoice_quote_chea_kheng/'.$inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_quote_chea_kheng') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice_quote_chea_kheng') ?></span>
                            </a>
                        </div>
						 <div class="btn-group">
                            <a href="<?= site_url('Quotes/quote_vat/'.$inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('quote_vat') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('quote_vat') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('Quotes/quote_without_vat/'.$inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('quote_without_vat') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('quote_without_vat') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('Quotes/quote_without_vat_logo/'.$inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('quote_vat_logo') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('quote_vat_logo') ?></span>
                            </a>
                        </div>-->
                        <?php if ($Owner || $Admin || $GP['quotes-email']) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('quotes/email/' . $inv->id) ?>" data-toggle="modal" data-target="#myModal2" class="tip btn btn-primary" title="<?= lang('email') ?>">
                                <i class="fa fa-envelope-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('email') ?></span>
                            </a>
                        </div>
                        <?php } ?>
						
                        <?php if ($Owner || $Admin || $GP['quotes-export']) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('quotes/pdf/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('download_pdf') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                            </a>
                        </div>
                        <?php } ?>
                        <?php 
						if ($inv->issue_invoice == 'pending' && ($inv->status != 'rejected' && $inv->status == 'pending')) {
							if ($Owner || $Admin || $GP['quotes-edit']) { ?>
							<div class="btn-group">
								<a href="<?= site_url('quotes/edit/' . $inv->id) ?>" class="tip btn btn-warning sledit" title="<?= lang('edit') ?>">
									<i class="fa fa-edit"></i>
									<span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
								</a>
							</div>
                        <?php 
							}
						} ?>
						
                    </div>

                    <!--<div class="btn-group btn-group-justified">
						<div class="btn-group">
                            <a href="<?= site_url('Quotes/invoice_quote_eang_tay_a5/'.$inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_quote_eang_tay_a5') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('quote_eang_tay_a5') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('Quotes/invoice_quote_eang_tay_a4/'.$inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_quote_eang_tay_a4') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('quote_eang_tay_a4') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('Quotes/quote_invoice_chim_socheat/'.$inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('Chim_Socheat') ?>">
                                <i class="fa fa-heart"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Chim_Socheat') ?></span>
                            </a>
                        </div>
					</div>
                </div>-->
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function() {
        $('.tip').tooltip();
    });
</script>
