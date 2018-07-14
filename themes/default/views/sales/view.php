<?php 
	//$this->erp->print_arrays($rows);
?>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.sledit', function (e) {
            if (__getItem('slitems')) {
                e.preventDefault();
                var href = $(this).attr('href');
                bootbox.confirm("<?=lang('you_will_loss_sale_data')?>", function (result) {
                    if (result) {
                        window.location.href = href;
                    }
                });
            }
        });
    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-file"></i><?= lang("sale_no") . ' ' . $inv->reference_no; ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
            <?php if ($Owner || $Admin || $GP['sales-edit'] || $GP['sales-payments'] || $GP['sales-email'] || $GP['sales-export'] || $GP['sales-return_sales']) { ?>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>">
                        </i>
                    </a>
                    <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu" aria-labelledby="dLabel">
                        <?php if ($inv->attachment) { ?>
                            <li>
                                <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>">
                                    <i class="fa fa-chain"></i> <?= lang('attachment') ?>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($Owner || $Admin || $GP['sales-edit']) { ?>
                            <li>
                                <a href="<?= site_url('sales/edit/' . $inv->id) ?>" class="sledit">
                                    <i class="fa fa-edit"></i> <?= lang('edit_sale') ?>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($Owner || $Admin || $GP['sales-payments']) { ?>
                            <li>
                                <a href="<?= site_url('sales/payments/' . $inv->id) ?>" data-target="#myModal" data-toggle="modal">
                                    <i class="fa fa-money"></i> <?= lang('view_payments') ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('sales/add_payment/' . $inv->id) ?>" data-target="#myModal" data-toggle="modal">
                                    <i class="fa fa-money"></i> <?= lang('add_payment') ?>
                                </a>
                            </li>
                        <?php } ?>
                        <!-- <li>
                            <a href="<?= site_url('sales/add_delivery/' . $inv->id) ?>" data-target="#myModal" data-toggle="modal">
                                <i class="fa fa-truck"></i> <?= lang('add_delivery') ?>
                            </a>
                        </li> -->
                        <?php if ($Owner || $Admin || $GP['sales-email']) { ?>
                            <li>
                                <a href="<?= site_url('sales/email/' . $inv->id) ?>" data-target="#myModal" data-toggle="modal">
                                    <i class="fa fa-envelope-o"></i> <?= lang('send_email') ?>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($Owner || $Admin || $GP['sales-export']) { ?>
                            <li>
                                <a href="<?= site_url('sales/pdf/' . $inv->id) ?>">
                                    <i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($Owner || $Admin || $GP['sales-return_sales']) { ?>
                            <li>
                                <a href="<?= site_url('sales/return_sale/' . $inv->id) ?>">
                                    <i class="fa fa-angle-double-left"></i> <?= lang('return_sale') ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <?php if ($return_sale) { ?>
                    <div class="alert alert-info"
                         role="alert"><?= lang('return_has_been_added') . ' <a class="btn btn-primary btn-sm" href="' . site_url('sales/view_return/' . $return_sale->id) . '">' . lang('view_details') . '</a>'; ?></div>
                <?php } ?>
                <div class="print-only col-xs-12">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
                <div class="well well-sm">
                    <div class="col-xs-4 border-right">

                        <div class="col-xs-2"><i class="fa fa-3x fa-building padding010 text-muted"></i></div>
                        <div class="col-xs-10">
                            <h2 class=""><?= $biller->company != '' ? $biller->company : $biller->name; ?></h2>
                            <?= $biller->company ? "" : "Attn: " . $biller->name ?>

                            <?php
                            echo $biller->address . "<br>" . $biller->city . " " . $biller->postal_code . " " . $biller->state . "<br>" . $biller->country;

                            // echo "<p>";

                            if ($biller->cf1 != "-" && $biller->cf1 != "") {
                                echo "<br>" . lang("bcf1") . ": " . $biller->cf1;
                            }
                            if ($biller->cf2 != "-" && $biller->cf2 != "") {
                                echo "<br>" . lang("bcf2") . ": " . $biller->cf2;
                            }
                            if ($biller->cf3 != "-" && $biller->cf3 != "") {
                                echo "<br>" . lang("bcf3") . ": " . $biller->cf3;
                            }
                            if ($biller->cf4 != "-" && $biller->cf4 != "") {
                                echo "<br>" . lang("bcf4") . ": " . $biller->cf4;
                            }
                            if ($biller->cf5 != "-" && $biller->cf5 != "") {
                                echo "<br>" . lang("bcf5") . ": " . $biller->cf5;
                            }
                            if ($biller->cf6 != "-" && $biller->cf6 != "") {
                                echo "<br>" . lang("bcf6") . ": " . $biller->cf6;
                            }

                            // echo "</p>";
                            echo ($biller->phone ? '<br>'.lang("tel") . ": " . $biller->phone . "<br>" : ''). ($biller->email ? lang("email") . ": " . $biller->email : '');
                            ?>
                        </div>
                        <div class="clearfix"></div>

                    </div>
                    <div class="col-xs-4 border-right">

                        <div class="col-xs-2"><i class="fa fa-3x fa-user padding010 text-muted"></i></div>
                        <div class="col-xs-10">
                            <h2 class=""><?= $customer->company ? $customer->company : $customer->name; ?></h2>
                            <?= $customer->company ? "" : "Attn: " . $customer->name ?>

                            <?php
                            echo ($customer->address ? $customer->address . "<br>" : '') . ($customer->city ? $customer->city : ''). " " . ($customer->postal_code ? $customer->postal_code : ''). " " . ($customer->state ? $customer->state . "<br>" : '') . ($customer->country ? $customer->country : '');

                            // echo "<p>";

                            if ($customer->cf1 != "-" && $customer->cf1 != "") {
                                echo "<br>" . lang("ccf1") . ": " . $customer->cf1;
                            }
                            if ($customer->cf2 != "-" && $customer->cf2 != "") {
                                echo "<br>" . lang("ccf2") . ": " . $customer->cf2;
                            }
                            if ($customer->cf3 != "-" && $customer->cf3 != "") {
                                echo "<br>" . lang("ccf3") . ": " . $customer->cf3;
                            }
                            if ($customer->cf4 != "-" && $customer->cf4 != "") {
                                echo "<br>" . lang("ccf4") . ": " . $customer->cf4;
                            }
                            if ($customer->cf5 != "-" && $customer->cf5 != "") {
                                echo "<br>" . lang("ccf5") . ": " . $customer->cf5;
                            }
                            if ($customer->cf6 != "-" && $customer->cf6 != "") {
                                echo "<br>" . lang("ccf6") . ": " . $customer->cf6;
                            }

                            // echo "</p>";
                            echo ($customer->phone ? lang("tel") . ": " . $customer->phone . "<br>" : '') . ($customer->email ? lang("email") . ": " . $customer->email : '');
                            ?>
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
                <?php if ($Settings->invoice_view == 1) { ?>
                    <div class="col-xs-12 text-center">
                        <h1><?= lang('tax_invoice'); ?></h1>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
                <div class="col-xs-8 pull-right">
                    <div class="col-xs-12 text-right">
                        <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 70, false); ?>
                        <img src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>"/>
					 <?php 
						if($pos->display_qrcode) {
						?>
                        <?php $this->erp->qrcode('link', urlencode(site_url('sales/view/' . $inv->id)), 2); ?>
                        <img src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>"/>
					<?php } ?>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="col-xs-4">
                    <div class="col-xs-2"><i class="fa fa-3x fa-file-text-o padding010 text-muted"></i></div>
                    <div class="col-xs-10">
                        <h2 class=""><?= lang("ref"); ?>: <?= $inv->reference_no; ?></h2>

                        <p style="font-weight:bold;"><?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?></p>

                        <p style="font-weight:bold;"><?= lang("sale_status"); ?>:
                            <?php if ($inv->sale_status == 'completed') { ?>
                            <span class="label label-success"><?= ucfirst($inv->sale_status); ?></span>
                            <?php } elseif($inv->sale_status == 'ordered'){ ?>
                                <span class="label label-warning"><?= ucfirst($inv->sale_status); ?></span>
                            <?php } else{?>
                                <span class="label label-danger"><?= ucfirst($inv->sale_status); ?></span>
                            <?php } ?>
                        </p>

                        <p style="font-weight:bold;"><?= lang("payment_status"); ?>:
                            <?php if ($inv->payment_status == 'paid') { ?>
                            <span class="label label-success"><?= ucfirst($inv->payment_status); ?></span>
                            <?php } elseif($inv->payment_status == 'due'){ ?>
                                <span class="label label-danger"><?= ucfirst($inv->payment_status); ?></span>
                            <?php } else{?>
                                <span class="label label-info"><?= ucfirst($inv->payment_status); ?></span>
                            <?php } ?>
                        </p>

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
							<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
							<th><?= lang('product_code'); ?></th>
							<?php } ?>
                            <th><?= lang("description"); ?> (<?= lang("code"); ?>)</th>
							<th><?= lang("unit"); ?></th>
                            <th><?= lang("quantity"); ?></th>
                            <?php
                            if ($Settings->product_serial) {
                                echo '<th style="text-align:center; vertical-align:middle;">' . lang("serial_no") . '</th>';
                            }
                            ?>
                            <?php if ($Owner || $Admin || $GP['sales-price']) { ?>
                                <th style="padding-right:20px;"><?= lang("unit_price"); ?></th>
                            <?php } ?>
                            <?php
                            if ($Settings->tax1) {
                                echo '<th style="padding-right:20px; text-align:center; vertical-align:middle;">' . lang("tax") . '</th>';
                            }
                            if ($Settings->product_discount && $inv->product_discount != 0) {
                                echo '<th style="padding-right:20px; text-align:center; vertical-align:middle;">' . lang("discount") . '</th>';
                            }
                            ?>
                            <th style="padding-right:20px;"><?= lang("subtotal"); ?></th>
                        </tr>

                        </thead>

                        <tbody>

                        <?php $r = 1;
                        $tax_summary = array();
                        foreach ($rows as $row):
						$free = lang('free');
						$product_unit = '';
						if($row->variant){
							$product_unit = $row->variant;
						}else{
							$product_unit = $row->uname;
						}
						
						$product_name_setting;
						if($setting->show_code == 0) {
							$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
						}else{
							if($setting->separate_code == 0) {
								$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
							}else {
								$product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
							}
						}
								
						
                            if (isset($tax_summary[$row->tax_code])) {
                                $tax_summary[$row->tax_code]['items'] += $row->quantity;
                                $tax_summary[$row->tax_code]['tax'] += $row->item_tax;
                                $tax_summary[$row->tax_code]['amt'] += ($row->quantity * $row->net_unit_price) - $row->item_discount;
                            } else {
                                $tax_summary[$row->tax_code]['items'] = $row->quantity;
                                $tax_summary[$row->tax_code]['tax'] = $row->item_tax;
                                $tax_summary[$row->tax_code]['amt'] = ($row->quantity * $row->net_unit_price) - $row->item_discount;
                                $tax_summary[$row->tax_code]['name'] = $row->tax_name;
                                $tax_summary[$row->tax_code]['code'] = $row->tax_code;
                                $tax_summary[$row->tax_code]['rate'] = $row->tax_rate;
                            }
                            ?>
                            <tr>
                                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
								<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
								<td style="vertical-align:middle;">
									<?= $row->product_code ?>
								</td>
								
								<?php } ?>
                                <td style="vertical-align:middle;"><?= $product_name_setting ?>
                                    <?= $row->details ? '<br>' . $row->details : ''; ?> 
									<?= $row->product_noted ? '<br>' . $row->product_noted : ''; ?>
								</td>
								
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?php echo $product_unit ?></td>
                                <td style="width: 100px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                                
								<?php
                                if ($Settings->product_serial) {
                                    echo '<td>' . $row->serial_no . '</td>';
                                }
                                ?>
                                <?php if ($Owner || $Admin || $GP['sales-price']) { ?>
                                    <td style="text-align:right; width:120px; padding-right:10px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->net_unit_price):$free; ?></td>
                                <?php } ?>
                                <?php
                                if ($Settings->tax1) {
                                    echo '<td style="width: 120px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
                                }
                                if ($Settings->product_discount && $inv->product_discount != 0) {
                                    echo '<td style="width: 120px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
                                }
                                ?>
                                <td style="text-align:right; width:120px; padding-right:10px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; ?></td>
                            </tr>
                            <?php
                            $r++;
                        endforeach;
                        ?>
                        </tbody>
                        <tfoot>
                        <?php
                        $col = 4;
                        if ($Owner || $Admin || $GP['sales-price']) {
                            $col++;
                        }
						if($setting->show_code == 1 && $setting->separate_code == 1) {
							$col += 1;
						}
                        if ($Settings->product_serial) {
                            $col++;
                        }
                        if ($Settings->product_discount && $inv->product_discount != 0) {
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
                                <td colspan="<?= $tcol; ?>"
                                    style="text-align:right; padding-right:10px;"><?= lang("total"); ?>
                                    (<?= $default_currency->code; ?>)
                                </td>
                                <?php
                                if ($Settings->tax1) {
                                    echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_tax) . '</td>';
                                }
                                if ($Settings->product_discount && $inv->product_discount != 0) {
                                    echo '<td style="text-align:right;">' . $this->erp->formatMoney($inv->product_discount) . '</td>';
                                }
                                ?>
                                <td style="text-align:right; padding-right:10px;"><?= $this->erp->formatMoney($inv->total + $inv->product_tax); ?></td>
                            </tr>
                        <?php } ?>
                        <?php if ($return_sale && $return_sale->surcharge != 0) {
                            echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("return_surcharge") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($return_sale->surcharge) . '</td></tr>';
                        }
                        ?>
                        <?php if ($inv->order_discount != 0) {
                            echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
                        }
                        ?>
                        <?php if ($Settings->tax2 && $inv->order_tax != 0) {
                            echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
                        }
                        ?>
                        <?php if ($inv->shipping != 0) {
                            echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
                        }
                        ?>
                        <tr>
                            <td colspan="<?= $col; ?>"
                                style="text-align:right; padding-right:10px; font-weight:bold;"><?= lang("total_amount"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
                        </tr>
                        <?php if($inv->paid != 0 || $inv->deposit != 0) {?>
						<?php if($inv->deposit != 0) {?>
						<tr>
                            <td colspan="<?= $col; ?>"
                                style="text-align:right; padding-right:10px; font-weight:bold;"><?= lang("deposit"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoney($inv->deposit); ?></td>
                        </tr>
						<?php } ?>
						<?php if($inv->paid != 0) {?>
                        <tr>
                            <td colspan="<?= $col; ?>"
                                style="text-align:right; padding-right:10px; font-weight:bold;"><?= lang("paid"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoney($inv->paid-$inv->deposit); ?></td>
                        </tr>
						<?php } ?>
                        <tr>
                            <td colspan="<?= $col; ?>"
                                style="text-align:right; padding-right:10px; font-weight:bold;"><?= lang("balance"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total - (($inv->paid-$inv->deposit) + $inv->deposit)); ?></td>
                        </tr>
                        <?php }?>
                        </tfoot>
                    </table>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <?php
                        if ($inv->note || $inv->note != "") { ?>
                            <div class="well well-sm">
                                <p class="bold"><?= lang("note"); ?>:</p>

                                <div><?= $this->erp->decode_html($inv->note); ?></div>
                            </div>
                        <?php
                        }
                        if ($inv->staff_note || $inv->staff_note != "") { ?>
                            <div class="well well-sm staff_note">
                                <p class="bold"><?= lang("staff_note"); ?>:</p>

                                <div><?= $this->erp->decode_html($inv->staff_note); ?></div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="col-xs-6">
                        <?php
                        if ($Settings->invoice_view == 1) {
                            if (!empty($tax_summary)) {
                                echo '<h3 class="bold">' . lang('tax_summary') . '</h3>';
                                echo '<table class="table table-bordered table-condensed"><thead><tr><th>' . lang('name') . '</th><th>' . lang('code') . '</th><th>' . lang('qty') . '</th><th>' . lang('tax_excl') . '</th><th>' . lang('tax_amt') . '</th></tr></td><tbody>';
                                foreach ($tax_summary as $summary) {
                                    echo '<tr><td>' . $summary['name'] . '</td><td class="text-center">' . $summary['code'] . '</td><td class="text-center">' . $this->erp->formatQuantity($summary['items']) . '</td><td class="text-right">' . $this->erp->formatMoney($summary['amt']) . '</td><td class="text-right">' . $this->erp->formatMoney($summary['tax']) . '</td></tr>';
                                }
                                echo '</tbody></tfoot>';
                                echo '<tr><th colspan="4" class="text-right">' . lang('total_tax_amount') . '</th><th class="text-right">' . $this->erp->formatMoney($inv->product_tax) . '</th></tr>';
                                echo '</tfoot></table>';
                            }
                        }
                        ?>
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

                <?php if ($inv->payment_status != 'paid') { ?>
                    <div id="payment_buttons" class="row text-center padding10 no-print">

                        <?php
						if(isset($paypal->active)){
							if ($paypal->active == "1" && $inv->grand_total != "0.00") {
								if (trim(strtolower($customer->country)) == $biller->country) {
									$paypal_fee = $paypal->fixed_charges + ($inv->grand_total * $paypal->extra_charges_my / 100);
								} else {
									$paypal_fee = $paypal->fixed_charges + ($inv->grand_total * $paypal->extra_charges_other / 100);
                            }
                            ?>
                            <div class="col-xs-6 text-center">
                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                    <input type="hidden" name="cmd" value="_xclick">
                                    <input type="hidden" name="business" value="<?= $paypal->account_email; ?>">
                                    <input type="hidden" name="item_name" value="<?= $inv->reference_no; ?>">
                                    <input type="hidden" name="item_number" value="<?= $inv->id; ?>">
                                    <input type="hidden" name="image_url"
                                           value="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>">
                                    <input type="hidden" name="amount"
                                           value="<?= ($inv->grand_total - $inv->paid) + $paypal_fee; ?>">
                                    <input type="hidden" name="no_shipping" value="1">
                                    <input type="hidden" name="no_note" value="1">
                                    <input type="hidden" name="currency_code" value="<?= $default_currency->code; ?>">
                                    <input type="hidden" name="bn" value="FC-BuyNow">
                                    <input type="hidden" name="rm" value="2">
                                    <input type="hidden" name="return"
                                           value="<?= site_url('sales/view/' . $inv->id); ?>">
                                    <input type="hidden" name="cancel_return"
                                           value="<?= site_url('sales/view/' . $inv->id); ?>">
                                    <input type="hidden" name="notify_url"
                                           value="<?= site_url('payments/paypalipn'); ?>"/>
                                    <input type="hidden" name="custom"
                                           value="<?= $inv->reference_no . '__' . ($inv->grand_total - $inv->paid) . '__' . $paypal_fee; ?>">
                                    <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block"><i
                                            class="fa fa-money"></i> <?= lang('pay_by_paypal') ?></button>
                                </form>
                            </div>
                        <?php }
						}
						?>


                        <?php 
							if(isset($skrill->active)){
						    if ($skrill->active == "1" && $inv->grand_total != "0.00") {
								if (trim(strtolower($customer->country)) == $biller->country) {
									$skrill_fee = $skrill->fixed_charges + ($inv->grand_total * $skrill->extra_charges_my / 100);
								} else {
									$skrill_fee = $skrill->fixed_charges + ($inv->grand_total * $skrill->extra_charges_other / 100);
								}
                            ?>
                            <div class="col-xs-6 text-center">
                                <form action="https://www.moneybookers.com/app/payment.pl" method="post">
                                    <input type="hidden" name="pay_to_email" value="<?= $skrill->account_email; ?>">
                                    <input type="hidden" name="status_url"
                                           value="<?= site_url('payments/skrillipn'); ?>">
                                    <input type="hidden" name="cancel_url"
                                           value="<?= site_url('sales/view/' . $inv->id); ?>">
                                    <input type="hidden" name="return_url"
                                           value="<?= site_url('sales/view/' . $inv->id); ?>">
                                    <input type="hidden" name="language" value="EN">
                                    <input type="hidden" name="ondemand_note" value="<?= $inv->reference_no; ?>">
                                    <input type="hidden" name="merchant_fields" value="item_name,item_number">
                                    <input type="hidden" name="item_name" value="<?= $inv->reference_no; ?>">
                                    <input type="hidden" name="item_number" value="<?= $inv->id; ?>">
                                    <input type="hidden" name="amount"
                                           value="<?= ($inv->grand_total - $inv->paid) + $skrill_fee; ?>">
                                    <input type="hidden" name="currency" value="<?= $default_currency->code; ?>">
                                    <input type="hidden" name="detail1_description" value="<?= $inv->reference_no; ?>">
                                    <input type="hidden" name="detail1_text"
                                           value="Payment for the sale invoice <?= $inv->reference_no . ': ' . $inv->grand_total . '(+ fee: ' . $skrill_fee . ') = ' . $this->erp->formatMoney($inv->grand_total + $skrill_fee); ?>">
                                    <input type="hidden" name="logo_url"
                                           value="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>">
                                    <button type="submit" name="submit" class="btn btn-primary btn-lg btn-block"><i
                                            class="fa fa-money"></i> <?= lang('pay_by_skrill') ?></button>
                                </form>
                            </div>
							<?php }
							} ?>
                        <div class="clearfix"></div>
                    </div>
                <?php } ?>
                <?php if ($payments) { ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-condensed print-table">
                                    <thead>
                                    <tr>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('payment_reference') ?></th>
                                        <th><?= lang('paid_by') ?></th>
                                        <th><?= lang('amount') ?></th>
                                        <th><?= lang('created_by') ?></th>
                                        <th><?= lang('type') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($payments as $payment) { ?>
                                        <tr <?= $payment->type == 'returned' ? 'class="warning"' : ''; ?>>
                                            <td><?= $this->erp->hrld($payment->date) ?></td>
                                            <td><?= $payment->reference_no; ?></td>
                                            <td><?= lang($payment->paid_by);
                                                if ($payment->paid_by == 'gift_card' || $payment->paid_by == 'CC') {
                                                    echo ' (' . $payment->cc_no . ')';
                                                } elseif ($payment->paid_by == 'Cheque') {
                                                    echo ' (' . $payment->cheque_no . ')';
                                                }
                                                ?></td>
                                            <td><?= $this->erp->formatMoney($payment->amount); ?></td>
                                            <td><?= $payment->first_name . ' ' . $payment->last_name; ?></td>
                                            <td><?= lang($payment->type); ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php if (!$Supplier || !$Customer) { ?>
            <div class="buttons">
                <div class="btn-group btn-group-justified">
                
                    <?php if ($inv->attachment) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>" class="tip btn btn-primary" title="<?= lang('attachment') ?>">
                                <i class="fa fa-chain"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                            </a>
                        </div>
                    <?php } ?>

                    <?php if ($Owner || $Admin || $GP['sales-payments']) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/payments/' . $inv->id) ?>" data-toggle="modal"
                                data-target="#myModal" class="tip btn btn-primary tip"
                                title="<?= lang('view_payments') ?>">
                                <i class="fa fa-money"></i> <span class="hidden-sm hidden-xs"><?= lang('view_payments') ?></span>
                            </a>
                        </div>

                        <div class="btn-group">
                            <a href="<?= site_url('sales/add_payment/' . $inv->id) ?>"
                                data-toggle="modal" data-target="#myModal" class="tip btn btn-primary tip"
                                title="<?= lang('add_payment') ?>">
                                <i class="fa fa-money"></i> <span class="hidden-sm hidden-xs"><?= lang('add_payment') ?></span>
                            </a>
                        </div>
                    <?php } ?>
						<div class="btn-group  ">
                            <a href="<?= site_url('sales/print_/' . $inv->id) ?>"
                                target="_blank" class="tip btn btn-primary"
                                title="<?= lang('print_invoice') ?>">
                                <i class="fa fa-download"></i> <span class="hidden-sm hidden-xs"><?= lang('print_invoice') ?></span>
                            </a>
                        </div>

						<div class="btn-group">
                            <a href="<?= site_url('sales/tax_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('tax_invoice') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('tax_invoice') ?></span>
                            </a>
                        </div>

						<!-- <div class="btn-group  ">
                            <a href="<?= site_url('sales/tax_invoice1/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_invoice') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print_invoice_1') ?></span>
                            </a>
                        </div> -->

						<div class="btn-group  ">
                            <a href="<?= site_url('sales/print_st_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_invoice') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('st_invoice') ?></span>
                            </a>
                        </div>
						
                    <!-- <div class="btn-group"><a href="<?= site_url('sales/add_delivery/' . $inv->id) ?>"
                                              data-toggle="modal" data-target="#myModal" class="tip btn btn-primary tip"
                                              title="<?= lang('add_delivery') ?>"><i class="fa fa-truck"></i> <span
                                class="hidden-sm hidden-xs"><?= lang('add_delivery') ?></span></a></div> -->
					<!--
                    <div class="btn-group"><a href="<?= site_url('sales/email/' . $inv->id) ?>" data-toggle="modal"
                                              data-target="#myModal" class="tip btn btn-primary tip"
                                              title="<?= lang('email') ?>"><i class="fa fa-envelope-o"></i> <span
                                class="hidden-sm hidden-xs"><?= lang('email') ?></span></a></div>
					-->

                    <?php if ($Owner || $Admin || $GP['sales-export']) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/pdf/' . $inv->id) ?>"
                                class="tip btn btn-primary"
                                title="<?= lang('download_pdf') ?>">
                                <i class="fa fa-download"></i> <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                            </a>
                        </div>
                    <?php } ?>

                    <?php if ($GP['sales-edit']) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/edit/' . $inv->id) ?>"
                                class="tip btn btn-warning tip sledit" title="<?= lang('edit') ?>">
                                <i class="fa fa-edit"></i> <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
                            </a>
                        </div>
                    <?php } ?>

					<!--
                    <div class="btn-group"><a href="#" class="tip btn btn-danger bpo"
                                              title="<b><?= $this->lang->line("delete_sale") ?></b>"
                                              data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= site_url('sales/delete/' . $inv->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                                              data-html="true" data-placement="top"><i class="fa fa-trash-o"></i> <span
                                class="hidden-sm hidden-xs"><?= lang('delete') ?></span></a></div>
					-->
                    <!--<div class="btn-group"><a href="<?= site_url('sales/excel/' . $inv->id) ?>" class="tip btn btn-primary"  title="<?= lang('download_excel') ?>"><i class="fa fa-download"></i> <?= lang('excel') ?></a></div>-->

                </div>
            </div>
        <?php } ?>
    </div>
</div>
