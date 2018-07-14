<?php 
	
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-file"></i><?= lang("quote_no") . '. ' . $inv->id; ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <?php if ($inv->attachment) { ?>
                            <li>
                                <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>">
                                    <i class="fa fa-chain"></i> <?= lang('attachment') ?>
                                </a>
                            </li>
                        <?php } ?>
                        <?php 
						if ($inv->issue_invoice == 'pending' && ($inv->status != 'rejected' && $inv->status == 'pending')) {
							if ($Owner || $Admin || $GP['quotes-edit']) { ?>
							<li><a href="<?= site_url('quotes/edit/' . $inv->id) ?>"><i
										class="fa fa-edit"></i> <?= lang('edit_quote') ?></a></li>
                        <?php 
							}
						} ?>
                        <?php if ($Owner || $Admin || $GP['quotes-email']) { ?>
                        <li><a href="<?= site_url('quotes/email/' . $inv->id) ?>" data-target="#myModal"
                               data-toggle="modal"><i class="fa fa-envelope-o"></i> <?= lang('send_email') ?></a></li>
                        <?php } ?>
                        <?php if ($Owner || $Admin || $GP['quotes-export']) { ?>
                        <li><a href="<?= site_url('quotes/pdf/' . $inv->id) ?>"><i
                                    class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <div class="print-only col-xs-12">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
                <div class="well well-sm">
                    <div class="col-xs-4 border-right">

                        <div class="col-xs-2"><i class="fa fa-3x fa-building padding010 text-muted"></i></div>
                        <div class="col-xs-10">
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
                        <div class="clearfix"></div>

                    </div>
                    <div class="col-xs-4 border-right">

                        <div class="col-xs-2"><i class="fa fa-3x fa-user padding010 text-muted"></i></div>
                        <div class="col-xs-10">
                            <table>
                        <tr>
                            <td><?=$this->lang->line("to");?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?="<span style='font-weight:bold; font-size:17px;'>". ($customer->company ? $customer->company : $customer->name)."</span>";?></td>
                        </tr>
                        <tr>
                            <td><?=lang("address") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$customer->address?></td>
                        </tr>
                        <tr>
                            <td>Attn</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?=$customer->name;?></td>
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
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-xs-4">
                        <div class="col-xs-2"><i class="fa fa-3x fa-building-o padding010 text-muted"></i></div>
                        <div class="col-xs-10">
                            <h2 class=""><?= $Settings->site_name; ?></h2>
                            <?= $warehouse->name ?>
                            <?php
                            echo $warehouse->address . "<br>";
                            echo ($warehouse->phone ? lang("tel") . ": " . $warehouse->phone . "<br>" : '') . ($warehouse->email ? lang("email") . ": " . $warehouse->email : '');
                            ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-8 pull-right">
                    <div class="col-xs-12 text-right">
                        <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 70, false); ?>
                        <img src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>"/>
                        <?php $this->erp->qrcode('link', urlencode(site_url('quotes/view/' . $inv->id)), 2); ?>
                        <img src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>"/>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="col-xs-4">
                    <div class="col-xs-2"><i class="fa fa-3x fa-file-text-o padding010 text-muted"></i></div>
                    <div class="col-xs-10">
                        <h2 class=""><?= lang("ref"); ?>: <?= $inv->reference_no; ?></h2>

                        <p style="font-weight:bold;"><?= lang("date"); ?>
                            : <?= $this->erp->hrld($inv->date); ?></p>

                        <p style="font-weight:bold;"><?= lang("status"); ?>:

                            <?php if ($inv->status == 'approved') { ?>
                                <span class="label label-success"><?= ucfirst($inv->status); ?></span>
                            <?php } elseif ($inv->status == 'pending') { ?>
                                <span class="label label-warning"><?= ucfirst($inv->status); ?></span>
                            <?php } else { ?>
                                <span class="label label-danger"><?= ucfirst($inv->status); ?></span>
                            <?php } ?>
                        </p>
                        <br>

                        <p>&nbsp;</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped print-table order-table">
                        <thead>
                        <tr>
                            <th><?= lang("no"); ?></th>
                            <th><?= lang("description"); ?> (<?= lang("code"); ?>)</th>
                            <th><?= lang("qoh"); ?></th>
                            <th><?= lang("quantity"); ?></th>
                            <th style="padding-right:20px;"><?= lang("unit_price"); ?></th>
                            <?php
                            if ($Settings->product_discount) {
                                echo '<th style="padding-right:20px; text-align:center; vertical-align:middle;">' . lang("discount") . '</th>';
                            }
                            if ($Settings->tax1) {
                                echo '<th style="padding-right:20px; text-align:center; vertical-align:middle;">' . lang("tax") . '</th>';
                            }
                            ?>
                            <th style="padding-right:20px;"><?= lang("amount"); ?>(<?= $default_currency->code; ?>)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $r = 1;
                        foreach ($rows as $row):
						
                            ?>
                            <tr>
                                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                                <td style="vertical-align:middle;"><?= $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : ''); ?>
                                    <?= $row->details ? '<br>' . $row->details : ''; ?></td>
                                 <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->qoh); ?></td>
                                <td style="width: 120px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                                <td style="text-align:right; width:100px; padding-right:10px;"><?= $this->erp->formatMoney($row->net_unit_price); ?></td>
                                <?php
                                if ($Settings->product_discount) {
                                    $percentage = '%';
                                    $discount = $row->discount;
                                    $dpos = strpos($discount, $percentage);
                                    echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' .($dpos == true ? '<small>('.$discount.')</small>' : '').' '. $this->erp->formatMoney($row->item_discount) . '</td>';
                                }
                                if ($Settings->tax1) {
                                    echo '<td style="width: 130px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_name ? '<small>('.$row->tax_name.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
                                }
                                ?>
                                <td style="text-align:right; width:120px; padding-right:10px;"><?= $this->erp->formatMoney($row->subtotal); ?></td>
                            </tr>
                            <?php
                            $r++;
                        endforeach;
                        ?>
                        </tbody>
                        <tfoot>
                        <?php
                        $col =5;
                        if ($Settings->product_discount && $inv->product_discount != 0) {
                            $col++;
                        }
                        if ($Settings->tax1) {
                            $col = $col+2;
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
                            <td style="text-align:right; padding-right:10px;"><?= $this->erp->formatMoney($inv->total + $inv->product_tax); ?></td>
                        </tr>
                        <?php
                        if ($inv->order_discount != 0) {
                            echo '<tr><td colspan="' . 7 . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . '</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
                        }
                        if ($inv->shipping != 0) {
                            echo '<tr><td colspan="' . 7 . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . '</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
                        }
                        if ($Settings->tax2 && $inv->order_tax != 0) {
                            echo '<tr><td colspan="' . 7 . '" style="text-align:right; padding-right:10px;;">' . $inv->tax_name . '</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
                        }
                        ?>
						<!-- <tr>
                            <td colspan="<?= $col; ?>"
                                style="text-align:right; padding-right:10px; font-weight:bold;"><?//= lang("deposit"); ?>
                                (<?//= $default_currency->code; ?>)
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?//= (isset($deposit->deposit_amount)?$this->erp->formatMoney($deposit->deposit_amount):0); ?></td>
                        </tr> -->
                        <tr>
                            <td colspan="7"
                                style="text-align:right; padding-right:10px; font-weight:bold;"><?= lang("total_amount"); ?>
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total - (isset($deposit->deposit_amount)?$deposit->deposit_amount:0)); ?></td>
                        </tr>

                        </tfoot>
                    </table>
                </div>

                <div class="row">
                    <div class="col-xs-7">
                        <?php if ($inv->note || $inv->note != "") { ?>
                            <div class="well well-sm">
                                <p class="bold"><?= lang("note"); ?>:</p>
                                <div><?= $this->erp->decode_html($inv->note); ?></div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="col-xs-4 col-xs-offset-1">
                        <div class="well well-sm">
                            <p><?= lang("created_by"); ?>
                                : <?= $created_by->first_name . ' ' . $created_by->last_name; ?> </p>

                            <p><?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?></p>
                            <?php if ($inv->updated_by) { ?>
                                <p><?= lang("updated_by"); ?>
                                    : <?= $updated_by->first_name . ' ' . $updated_by->last_name;; ?></p>
                                <p><?= lang("update_at"); ?>: <?= $this->erp->hrld($inv->updated_at); ?></p>
                            <?php } ?>
                        </div>
                    </div>
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
					<?php if ($inv->issue_invoice == 'pending' && ($inv->status != 'rejected' && $inv->status == 'approved')) { ?>
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
						<a href="<?= site_url('Quotes/invoice_quotes/'.$inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('quotes_invoice') ?>">
							<i class="fa fa-heart"></i>
							<span class="hidden-sm hidden-xs"><?= lang('quotes_invoice') ?></span>
						</a>
					</div>
					<div class="btn-group">
						<a href="<?= site_url('Quotes/quote_/'.$inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice') ?>">
							<i class="fa fa-heart"></i>
							<span class="hidden-sm hidden-xs"><?= lang('invoice') ?></span>
						</a>
					</div>
                    <?php if ($Owner || $Admin || $GP['quotes-export']) { ?>
                    <div class="btn-group"><a href="<?= site_url('quotes/pdf/' . $inv->id) ?>"
                                              class="tip btn btn-primary" title="<?= lang('download_pdf') ?>"><i
                                class="fa fa-download"></i> <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span></a>
                    </div>
                    <?php } ?>
                    <?php if ($Owner || $Admin || $GP['quotes-email']) { ?>
                    <div class="btn-group"><a href="<?= site_url('quotes/email/' . $inv->id) ?>" data-toggle="modal"
                                              data-target="#myModal" class="tip btn btn-info tip"
                                              title="<?= lang('email') ?>"><i class="fa fa-envelope-o"></i> <span
                                class="hidden-sm hidden-xs"><?= lang('email') ?></span></a></div>
                    <?php } ?>
                    <?php 
					if ($inv->issue_invoice == 'pending' && ($inv->status != 'rejected' && $inv->status == 'pending')) {
						if ($Owner || $Admin || $GP['quotes-edit']) { ?>
						<div class="btn-group"><a href="<?= site_url('quotes/edit/' . $inv->id) ?>"
												  class="tip btn btn-warning tip" title="<?= lang('edit') ?>"><i
									class="fa fa-edit"></i> <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span></a>
						</div>
                    <?php
						}					
					} ?>
                    <?php if ($Owner || $Admin || $GP['quotes-delete']) { ?>
                    <!--<div class="btn-group"><a href="#" class="tip btn btn-danger bpo"
                                              title="<b><?= $this->lang->line("delete_quote") ?></b>"
                                              data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= site_url('quotes/delete/' . $inv->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                                              data-html="true" data-placement="top"><i class="fa fa-trash-o"></i> <span
                                class="hidden-sm hidden-xs"><?= lang('delete') ?></span></a></div>-->
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
