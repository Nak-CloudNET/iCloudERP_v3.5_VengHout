<style type="text/css">
    @media print {
        .modal {
            position: relative;
        }
        .modal-dialog {
            width: 98% !important;
        }
        .modal-content {
            border: none !important;
        }

    }
    hr{
    border-color:#333;
    
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
            <?php
                if ($Settings->system_management == 'project') { ?>
                    <div class="text-center" style="margin-bottom:20px;">
                        <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo2; ?>"
                             alt="<?= $Settings->site_name != '-' ? $Settings->site_name : $Settings->site_name; ?>">
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
                <div class="row bold" style="font-size:12px;">
                    <div class="col-xs-5">
                    <p class="bold">
                        <?= lang("ref"); ?>: <?= $inv->reference_no; ?><br>
                        <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?><br>
						<?php if($inv->due_date) { ?>
							<?= lang("due_date"); ?>: <?= $inv->due_date; ?><br>
						<?php  } ?>
                        
                        <?= lang("sale_status"); ?>:
                        <?php if ($inv->sale_status == 'completed') { ?>
                            <span class="label label-success" ><?= lang($inv->sale_status); ?></span>
                        <?php } elseif ($inv->sale_status == 'pending') { ?>
                            <span class="label label-warning" ><?= lang($inv->sale_status); ?></span>
                        <?php } else { ?>
                            <span class="label label-danger" ><?= lang($inv->sale_status); ?></span>
                        <?php } ?>
                        <br>

                        <?= lang("payment_status"); ?>:
                        <?php if ($inv->payment_status == 'paid') { ?>
                            <span class="label label-success" ><?= lang($inv->payment_status); ?></span>
                        <?php } elseif ($inv->payment_status == 'partial') { ?>
                            <span class="label label-info" ><?= lang($inv->payment_status); ?></span>
                        <?php } elseif($inv->payment_status == 'pending'){?>
                            <span class="label label-warning" ><?= lang($inv->payment_status); ?></span>
                        <?php }else { ?>
                            <span class="label label-danger" ><?= lang($inv->payment_status); ?></span>
                        <?php } ?>

                    </p>
                    </div>
                    <div class="col-xs-7 text-right">
						<p style="font-size:16px; margin:0 !important;"><?= lang("INVOICE"); ?></p>
                        <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 70, false); ?>
                        <img height="45px" src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>"/>
                        <?php $this->erp->qrcode('link', urlencode(site_url('sales/view/' . $inv->id)), 2); ?>
                        <img height="45px" src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
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
                            <td><?= $this->lang->line("from"); ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?= "<span style='font-weight:bold; font-size:17px;'>" . ($biller->company != '-' ? $biller->company : $biller->name) . "</span>"; ?></td>
                        </tr>
                        <tr>
                            <td><?= lang("address") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?= ($biller->address ? $biller->address . " " : '') . ($biller->city ? $biller->city . " " . $biller->postal_code . " " . $biller->state . " " : '') . ($biller->country ? $biller->country : ''); ?></td>
                        </tr>
                        <?php //if ($supplier->company == ""){?>
                        <tr>
                            <td>Attn</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?= $biller->name; ?></td>
                        </tr>
                        <?php //}?>
                        <?php if ($biller->phone != '' || $biller->email != ''): ?>
                            <tr>
                                <td><?= lang("contact") ?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td><?= ($biller->phone ? $biller->phone . '/' . $biller->email : $biller->email) ?></td>
                            </tr>
                        <?php endif ?>
                    </table>
                </div>
                <div class="col-xs-6">
                    <table>
                        <tr>
                            <td><?= $this->lang->line("to"); ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?= "<span style='font-weight:bold; font-size:17px;'>" . ($customer->company ? $customer->company : $customer->names) . "</span>"; ?></td>
                        </tr>
                        <tr>
                            <td><?= lang("address") ?></td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?= $customer->address ?></td>
                        </tr>
                        <tr>
                            <td>Attn</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><?= $customer->names; ?></td>
                        </tr>
                        <?php if ($customer->phone != '' || $customer->email != ''): ?>
                            <tr>
                                <td><?= lang("contact") ?></td>
                                <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                <td><?= ($customer->phone ? $customer->phone . '/' . $customer->email : $customer->email) ?></td>
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
						<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
						<th><?= lang('product_code'); ?></th>
						<?php } ?>
                        <th><?= lang("description"); ?></th>
						<th><?= lang("unit"); ?></th>
                        <th><?= lang("quantity"); ?></th>
                        <?php if ($Owner || $Admin || $GP['sales-price']) { ?>
                            <th><?= lang("unit_price"); ?></th>
                        <?php } ?>
                        <?php
                        if ($Settings->tax1) {
                            echo '<th>' . lang("tax") . '</th>';
                        }
                        if ($Settings->product_discount && $inv->product_discount != 0) {
                            echo '<th>' . lang("discount") . '</th>';
                        }
                        ?>
                        <th><?= lang("subtotal"); ?></th>
                    </tr>

                    </thead>

                    <tbody>

                    <?php $r = 1;
                    $tax_summary = array();
					if(is_array($rows)){
						foreach ($rows as $row):
						$free = lang('free');
						$product_unit = '';
						$total = 0;
						
						if($row->variant){
							$product_unit = $row->variant;
						}else{
							$product_unit = $row->uname;
						}
						$product_name_setting;
						if($setting->show_code == 0) {
							$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
						}else {
							if($setting->separate_code == 0) {
								$product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
							}else {
								$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
							}
						}
						?>
							<tr>
								<td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
								<?php if($setting->show_code == 1 && $setting->separate_code == 1) { ?>
								<td style="vertical-align:middle;">
									<?= $row->product_code ?>
								</td>
								<?php } ?>
								<td style="vertical-align:middle;">
									<?= $product_name_setting ?>
									<?= $row->details ? '<br>' . $row->details : ''; ?>
									<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
								</td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?php echo $product_unit ?></td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                                <?php if ($Owner || $Admin || $GP['sales-price']) { ?>
								    <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->unit_price); ?></td>
                                <?php } ?>
								<?php
								if ($Settings->tax1) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
								}
								if ($Settings->product_discount && $inv->product_discount != 0) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
								}
								?>
								<td style="text-align:right; width:120px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free; 
									$total += $row->subtotal;
									?></td>
							</tr>
							<?php
							$r++;
						endforeach;
					}
                    ?>
                    <?php
                    $col = 3;
                    if ($Owner || $Admin || $GP['sales-price']) {
                        $col++;
                    }
                    if($setting->show_code == 1 && $setting->separate_code == 1) {
                        $col += 1;
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
                            <td></td>
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
                        echo '<tr><td></td><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("return_surcharge") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($return_sale->surcharge) . '</td></tr>';
                    }
                    ?>
                    <?php if ($inv->order_discount != 0) {
                        echo '<tr><td></td><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("order_discount") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_discount) . '</td></tr>';
                    }
                    ?>
                    <?php if ($Settings->tax2 && $inv->order_tax != 0) {
                        echo '<tr><td></td><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
                    }
                    ?>
                    <?php if ($inv->shipping != 0) {
                        echo '<tr><td></td><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
                    }
                    ?>
					
                    <tr>
                        <td></td>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
                    </tr>
					<?php if ($inv->deposit != 0) {?>
					<tr>
                        <td></td>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("deposit"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->deposit); ?></td>
                    </tr>
					<?php } ?>
                    <?php if(($inv->paid) != 0){?>
                    <tr>
                        <td></td>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("paid"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->paid-$inv->deposit); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("balance"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total - ($inv->deposit+($inv->paid-$inv->deposit))); ?></td>
                    </tr>
                    <?php }?>
                    </tbody>
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
                        if ($inv->staff_note || $inv->staff_note != "") { ?>
                            <div class="well well-sm staff_note">
                                <p class="bold"><?= lang("staff_note"); ?>:</p>
                                <div><?= $this->erp->decode_html($inv->staff_note); ?></div>
                            </div>
                        <?php } ?>
                </div>
				<br/>
				
				<br/>
				<div class="row">
					<div class="clearfix"></div>
					<div class="col-xs-3  pull-left" style="text-align:center">
						<hr/>
						<p><?= lang("seller"); ?>
							<!--: <?= $biller->company != '-' ? $biller->company : $biller->name; ?> --></p>
						<!--<p><?= lang("stamp_sign"); ?></p>-->
					</div>
					<div class="col-xs-3  pull-right" style="text-align:center">
						<hr/>
						<p><?= lang("customer"); ?>
						   <!-- : <?= $customer->company ? $customer->company : $customer->names; ?> --></p>
						<!--<p><?= lang("stamp_sign"); ?></p>-->
					</div>
					<div class="col-xs-3  pull-right" style="text-align:center">
						<hr/>
						<p><?= lang("Account"); ?>
							<!--: <?= $customer->company ? $customer->company : $customer->names; ?>--> </p>
						<!--<p><?= lang("stamp_sign"); ?></p>-->
					</div>
					<div class="col-xs-3  pull-right" style="text-align:center">
						<hr/>
                        <p><?= lang("Warehouse"); ?>
							<!--: <?= $warehouse->company ? $warehouse->company : $warehouse->name; ?>--> </p>
						<!--<p><?= lang("stamp_sign"); ?></p>-->
					</div>
				</div>
                <div class="col-xs-5 pull-right no-print" >
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
                    <div class="btn-group btn-group-justified">
						<!--
						<div class="btn-group">
                            <a href="<?= site_url('sales/tax_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('tax_invoice') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print_tax_invoice') ?></span>
                            </a>
                        </div>

						<div class="btn-group">
                            <a href="<?= site_url('sales/sbps_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('SBPS') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('sales/print_/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('sales/print_w/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_w') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print_w') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('sales/print_w_a5/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_w_a5') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print_w_a5') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/print_st_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('st_invoice') ?></span>
                            </a>
                        </div>
						-->
                        <div class="btn-group">
                            <a href="<?= site_url('sales/print_st_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('st_invoice') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('st_invoice') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/print_st_invoice_2/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_st_a5') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice_st_a5') ?></span>
                            </a>
                        </div>
						<!--<div class="btn-group">
                            <a href="<?= site_url('sales/print_iphoto_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('st_invoice') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('iphoto_invoice') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/invoice_camera_city/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_camera_city') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice_camera_city') ?></span>
                            </a>
                        </div>-->
						
						<div class="btn-group">
                            <a href="<?= site_url('sales/tax_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('tax_invoice') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('tax_invoice') ?></span>
                            </a>
                        </div>
                        <!--
                        <div class="btn-group">
                            <a href="<?= site_url('sales/primo_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('primo') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('sales/invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
							<a href="<?=base_url()?>sales/cabon_print/<?=$inv->id?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_cabon') ?>">
								<i class="fa fa-print"></i>
								<span class="hidden-sm hidden-xs"><?= lang('print_cabon') ?></span>
                            </a>
                        </div>

                        <div class="btn-group">
                            <a href="<?= site_url('sales/view/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('view') ?>">
                                <i class="fa fa-file-text-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('view') ?></span>
                            </a>
                        </div>
						<!--
						<div class="btn-group">
                            <a href="<?= site_url('sales/contrast_sale/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('flora') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('flora') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/knk_group/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('flora') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('knk_group') ?></span>
                            </a>
                        </div>-->
                        <?php if ($inv->attachment) { ?>
                            <div class="btn-group">
                                <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>" class="tip btn btn-primary" title="<?= lang('attachment') ?>">
                                    <i class="fa fa-chain"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                                </a>
                            </div>
                        <?php } ?>
                        <?php if ($Owner || $Admin || $GP['sales-email']) { ?>
                            <div class="btn-group">
                                <a href="<?= site_url('sales/email/' . $inv->id) ?>" data-toggle="modal" data-target="#myModal2" class="tip btn btn-primary" title="<?= lang('email') ?>">
                                    <i class="fa fa-envelope-o"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('email') ?></span>
                                </a>
                            </div>
                        <?php } ?>
                        <?php if ($Owner || $Admin || $GP['sales-export']) { ?>
                            <div class="btn-group">
                                <a href="<?= site_url('sales/pdf/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('download_pdf') ?>">
                                    <i class="fa fa-download"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                                </a>
                            </div>
                        <?php } ?>
						<!--
						<div class="btn-group">
                            <a href="<?= site_url('sales/invoice_deveryStatement/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_delivery_statement') ?>">
                                <i class="fa fa-car"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('delivery_statement') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('sales/sales_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('sales_invoice') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('sales_invoice') ?></span>
                            </a>
                        </div>
						
                        <?php if ($inv->sale_status != 'completed') { ?>
						<?php if ($GP['sales-edit']) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/edit/' . $inv->id) ?>" class="tip btn btn-warning sledit" title="<?= lang('edit') ?>">
                                <i class="fa fa-edit"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
                            </a>
                        </div>
                        <?php } ?>
                        <?php if ($GP['sales-delete']) { ?>
                        <div class="btn-group">
                            <a href="#" class="tip btn btn-danger bpo" title="<b><?= $this->lang->line("delete_sale") ?></b>"
                                data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= site_url('sales/delete/' . $inv->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                                data-html="true" data-placement="top">
                                <i class="fa fa-trash-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>
                            </a>
                        </div>
						<?php } ?>
						<?php } ?>-->
                    </div>
                </div>

                <!--<div class="buttons" >
                    <div class="btn-group btn-group-justified">

                        <!--<div class="btn-group">
                            <a href="<?= site_url('sales/invoice_devery/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_delivery') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice_delivery') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('sales/sales_invoice_a5/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('sales_invoice_a5') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('sales_invoice_a5') ?></span>
                            </a>
                        </div>

                        <?php if($inv->deposit_so_id != 0){?>
				        <div class="btn-group  ">
                            <a href="<?= site_url('sales/print_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_invoice') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print_invoice') ?></span>
                            </a>
                        </div>
                        <?php }?>
						<div class="btn-group">
                            <a href="<?= site_url('sales/invoice_landscap_a5/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_yon_wang') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice_yon_wang') ?></span>
                            </a>
                        </div>

						<div class="btn-group">
                            <a href="<?= site_url('sales/invoice_dragon_fly/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_dragon_fly') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice_dragon_fly') ?></span>
                            </a>
                        </div>
                     
						
						<!--<div class="btn-group">
                            <a href="<?= site_url('sales/print_st_invoice_uy_sing/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('uy_sing') ?></span>
                            </a>
                        </div>-->
						<!--<div class="btn-group">
                            <a href="<?= site_url('sales/tax_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('tax_invoice') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('tax_invoice') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/tax_invoice2/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('tax_invoice_2') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('tax_invoice_2') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('sales/tax_invoice3/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('tax_invoice_3') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('tax_invoice_ck') ?></span>
                            </a>
                        </div>-->
                       <!-- <div class="btn-group">
                            <a href="<?= site_url('sales/idd_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('idd_invoice') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('idd_invoice') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/the_flora_form/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('the_flora_form') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('the_flora_form') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= site_url('sales/payment_schedule_flora/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('payment_schedule_flora') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('payment_schedule_flora') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('sales/print_invoice_charles/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_charles') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice_charles') ?></span>
                            </a>
                        </div>
					 </div>
				</div>
				<div class="buttons" >
					<div class="btn-group">
                        <a href="<?= site_url('sales/invoice_cid/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_cid') ?>">
                            <i class="fa fa-download"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Invoice Cid') ?></span>
                        </a>
                    </div>
					<div class="btn-group">
                        <a href="<?= site_url('sales/invoice_seng_hout/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_seng_hout') ?>">
                            <i class="fa fa-download"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Invoice Seng Hout') ?></span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="<?= site_url('sales/invoice_kc/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_kc') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('invoice_kc_with_ctel') ?></span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="<?= site_url('sales/invoice_kc_without_ctel/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_kc') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('invoice_kc_without_ctel') ?></span>
                        </a>
                    </div>
					<div class="btn-group">
                        <a href="<?= site_url('sales/invoice_teatry/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_teatry') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('invoice_teatry') ?></span>
                        </a>
                    </div>
					<div class="btn-group">
                        <a href="<?= site_url('sales/invoice_phum_meas/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_phum_meas') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('invoice_phum_meas') ?></span>
                        </a>
                    </div>
					<div class="btn-group  ">
                            <a href="<?= site_url('sales/invoice_ppcp/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_ppcp') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice_ppcp') ?></span>
                            </a>
                    </div>
					<div class="btn-group">
                        <a href="<?= site_url('sales/invoice_eng_tay/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_engtay') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('invoice_standard_no_letter_head') ?></span>
                        </a>
                    </div>
					<div class="btn-group  ">
                            <a href="<?= site_url('sales/invoice_chea_kheng/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_chea_kheng') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('chea_kheng') ?></span>
                            </a>
                     </div>
					<div class="btn-group  ">
                            <a href="<?= site_url('sales/invoice_chea_kheng_head/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_chea_kheng') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('chea_kheng_head') ?></span>
                            </a>
                    </div>
					<div class="btn-group">
                        <a href="<?= site_url('sales/invoice_standard_nlh/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('st_invoice_no_letter_head') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('invoice_standard_no_letter_head') ?></span>
                        </a>
                    </div>
					<div class="btn-group">
                        <a href="<?= site_url('sales/invoice_eang_tay_a4/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_eang_tay_a4') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('invoice_eang_tay_a4') ?></span>
                        </a>
                    </div>
					<div class="btn-group">
                        <a href="<?= site_url('sales/invoice_eang_tay_a5/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_eang_tay_a5') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('invoice_eang_tay_a5') ?></span>
                        </a>
                    </div>
					<div class="btn-group">
                        <a href="<?= site_url('sales/invoice_nano_tech/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_nano_tech') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('invoice_nano_tech') ?></span>
                        </a>
                    </div>
					<div class="btn-group">
                        <a href="<?= site_url('sales/print_hch/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('Print HCH') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Print HCH') ?></span>
                        </a>
                    </div>
					<div class="btn-group">
                        <a href="<?= site_url('sales/print_green/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('Print Green Fresh') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Print Green') ?></span>
                        </a>
                    </div>
					<div class="btn-group">
					
                        <a href="<?= site_url('sales/invoice_chim_socheat/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('Chim Socheat') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Chim Socheat') ?></span>
                        </a>
                    </div>
					<div class="btn-group">

                        <a href="<?= site_url('sales/comercial_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('Comercial_Invoice') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Comercial_Invoice') ?></span>
                        </a>
                    </div>
					<div class="btn-group">

                        <a href="<?= site_url('sales/invoice_jessica_shop/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_jessica_shop') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('jessica_shop') ?></span>
                        </a>
                    </div>
					<div class="btn-group">

                        <a href="<?= site_url('sales/invoice_selling_concrete_detail/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('Angkor_concrete_selling_detail') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Angkor_concrete_selling_detail') ?></span>
                        </a>
                    </div>
					<div class="btn-group">

                        <a href="<?= site_url('sales/invoice_concrete_angkor/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('Angkor_concrete') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Angkor_concrete') ?></span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="<?= site_url('sales/invoice_LHK/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_LHK') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('invoice_LHK') ?></span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="<?= site_url('sales/invoice_LHK_a5/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('Ly_Huy_Khim') ?>">
                            <i class="fa fa-print"></i>
                            <span class="hidden-sm hidden-xs"><?= lang('Ly_Huy_Khim') ?></span>
                        </a>
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
