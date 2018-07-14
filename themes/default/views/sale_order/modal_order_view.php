<style type="text/css">
    hr {
        border-color: #333;

    }

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
			<!--<div class="text-center">
				<h1><?=lang('invoice')?></h1>
			</div>-->
            <?php if ($logo) { ?>
                <div class="text-center" style="margin-bottom:20px;">
                   <!-- <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">-->
						 <p><b>Sale Order Invoice</b></p>
                </div>
            <?php } ?>
            <div class="well well-sm">
                <div class="row bold" style="font-size:12px;">
                    <div class="col-xs-5">
                    <p class="bold">
                        <?= lang("ref"); ?>: <?= $inv->reference_no; ?><br>
                        <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?><br>

                        <?= lang("status"); ?>:

                        <?php if ($inv->status == 'Approved') { ?>
                            <span class="label label-success"><?= ucfirst($inv->status); ?></span>
                        <?php } elseif ($inv->status == 'Order') { ?>
                            <span class="label label-warning"><?= ucfirst($inv->status); ?></span>
                        <?php } else { ?>
                            <span class="label label-danger"><?= ucfirst($inv->status); ?></span>
                        <?php } ?>
                        <br>

                        <!-- <?= lang("payment_status"); ?>:
                        <?php if ($inv->payment_status == 'paid') { ?>
                            <span class="label label-success" ><?= lang($inv->payment_status); ?></span>
                        <?php } elseif ($inv->payment_status == 'partial') { ?>
                            <span class="label label-info" ><?= lang($inv->payment_status); ?></span>
                        <?php } else { ?>
                            <span class="label label-danger" ><?= lang($inv->payment_status); ?></span>
                        <?php } ?> -->

                    </p>
                    </div>
                    <div class="col-xs-7 text-right">
						<p style="font-size:16px; margin:0 !important;"><!--<?= lang("INVOICE"); ?>--></p>
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
						<?php if($Settings->show_code == 1 && $Settings->separate_code == 1) { ?>
						<th><?= lang('product_code'); ?></th>
						<?php } ?>
                        <th><?= lang("description"); ?></th>
						<!-- <th><?= lang("unit"); ?></th> -->
                        <th><?= lang("quantity"); ?></th>
                        <?php if ($Owner || $Admin || $GP['sale_order-price']) { ?>
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
					$total = 0;
                    $tax_summary = array();
                    foreach ($rows as $row):
                    $free = lang('free');
					$product_unit = '';


					if($row->variant){
						$product_unit = $row->variant;
					}else{
						$product_unit = $row->unit;
					}

					$product_name_setting;
					if($Settings->show_code == 0) {
						$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
					}else {
						if($Settings->separate_code == 0) {
							$product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
						}else {
							$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
						}
					}
                    ?>
                        <tr>
                            <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
							<?php if($Settings->show_code == 1 && $Settings->separate_code == 1) { ?>
							<td style="vertical-align:middle;">
								<?= $row->product_code ?>
							</td>
							<?php } ?>
                            <td style="vertical-align:middle;">
                                <?= $product_name_setting ?>
                                <?= $row->details ? '<br>' . $row->details : ''; ?>
                                <?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
                            </td>
							<!-- <td style="width: 80px; text-align:center; vertical-align:middle;"><?php echo $product_unit ?></td> -->
                            <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                            <?php if ($Owner || $Admin || $GP['sale_order-price']) { ?>
                                <td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->unit_price); ?></td>
                            <?php } ?>
                            <?php
                            if ($Settings->tax1) {
                                echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
                            }
                            if ($Settings->product_discount && $inv->product_discount != 0) {
                                echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . (strpos($row->discount, '%') ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->discount) . '</td>';
                            }
                            ?>
                            <td style="text-align:right; width:120px;"><?= $row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free;
                                $total += $row->subtotal;
                                ?></td>
                        </tr>
                        <?php
                        $r++;
                    endforeach;
                    ?>
                    </tbody>
                    <tfoot>
                    <?php
                        $col = 3;
                        if ($Owner || $Admin || $GP['sale_order-price']) {
                            $col++;
                        }
    					if($Settings->show_code == 1 && $Settings->separate_code == 1) {
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
                            <td style="text-align:right; padding-right:10px;"><?= $this->erp->formatMoney($total); ?></td>
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
                    <?php if ($inv->shipping != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;;">' . lang("shipping") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->shipping) . '</td></tr>';
                    }
                    ?>
                    <?php if ($Settings->tax2 && $inv->order_tax != 0) {
                        echo '<tr><td colspan="' . $col . '" style="text-align:right; padding-right:10px;">' . lang("order_tax") . ' (' . $default_currency->code . ')</td><td style="text-align:right; padding-right:10px;">' . $this->erp->formatMoney($inv->order_tax) . '</td></tr>';
                    }

                    // $thid->erp->print_arrays($inv->total);
                    $total_amount = $total - $inv->order_discount + $inv->shipping + $inv->order_tax;
                    ?>
                    <tr>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoney($total_amount); ?></td>
                    </tr>
					 <?php if($deposit->deposit > 0){?>
                       <tr>
                            <td colspan="<?= $col; ?>"
                                style="text-align:right; font-weight:bold;"><?= lang("deposit"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($deposit->deposit); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if($deposit->deposit > 0){?>
                        <tr>
                            <td colspan="<?= $col; ?>"
                                style="text-align:right; font-weight:bold;"><?= lang("balance"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->grand_total - $deposit->deposit); ?></td>
                        </tr>
                    <?php } ?>
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
						<hr/><?= lang("customer"); ?>
					</div>
					<div class="col-xs-3  pull-right" style="text-align:center">
						<hr/><?= lang("seller"); ?>
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
						<div class="btn-group">
                            <a href="<?= site_url('sale_order/tax_invoice1/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('sale order') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print_sale_order') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('sale_order/print_st_invoice_2/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('sale order a5') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print_sale_order_a5') ?></span>
                            </a>
                        </div>
                        <!--
                        <div class="btn-group">
                            <a href="<?= site_url('sale_order/print_iphoto/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('sale order') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print_iphoto') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= site_url('sale_order/invoice_camera_city/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice_camera_city') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice_camera_city') ?></span>
                            </a>
                        </div>


						<div class="btn-group">
                            <a href="<?= site_url('sale_order/sale_order_thai_san/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('sale order') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print_sale_order') ?></span>
                            </a>
                        </div>
						-->
						<!--
                        <?php if($so_id->id != 0){?>
                        <div class="btn-group">
                            <a href="<?= site_url('sale_order/tax_chales/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('tax_invoice1') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('tax_invoice_charles') ?></span>
                            </a>
                        </div>
                        <?php }?>
						<div class="btn-group">
                            <a href="<?= site_url('sales/print_hch/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('Print_HCH_Invoice') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Print_HCH_Invoice') ?></span>
                            </a>
                        </div>
						<div class="btn-group">
                            <a href="<?= site_url('sale_order/invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('RMS Invoice') ?></span>
                            </a>
                        </div>
                        <div class="btn-group">
                            <a href="<?= site_url('sale_order/print_invoice/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('print_invoice') ?></span>
                            </a>
                        </div>
						-->
						<!--  
						<div class="btn-group">
                            <a href="<?= site_url('sale_order/invoice_order/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('invoice') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('invoice') ?></span>
                            </a>
                        </div> -->
						<!-- 
						<div class="btn-group">
							<a href="<?=base_url()?>sales/Sanagro_Invoice/<?=$inv->id?>" target="_blank" class="tip btn btn-primary" title="<?= lang('Sanagro_Invoice') ?>">
								<i class="fa fa-print"></i>
								<span class="hidden-sm hidden-xs"><?= lang('Sanagro_Invoice') ?></span>
                            </a>
                        </div> -->
						<!--
						<div class="btn-group">
							<a href="<?=base_url()?>sales/cabon_print/<?=$inv->id?>" target="_blank" class="tip btn btn-primary" title="<?= lang('print_cabon') ?>">
								<i class="fa fa-print"></i>
								<span class="hidden-sm hidden-xs"><?= lang('print_cabon') ?></span>
                            </a>
                        </div>
						-->
                        <!--
						<div class="btn-group">
                            <a href="<?= site_url('sale_order/invoice_ppcp/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('ppcp') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('PPCP') ?></span>
                            </a>
                        </div>
                        -->
                        <div class="btn-group">
                            <a href="<?= site_url('sale_order/view/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('view') ?>">
                                <i class="fa fa-file-text-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('view') ?></span>
                            </a>
                        </div>
                        <?php if ($inv->attachment) { ?>
                            <div class="btn-group">
                                <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>" class="tip btn btn-primary" title="<?= lang('attachment') ?>">
                                    <i class="fa fa-chain"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('attachment') ?></span>
                                </a>
                            </div>
                        <?php } ?>

                        <div class="btn-group">
                            <a href="<?= site_url('sales/email/' . $inv->id) ?>" data-toggle="modal" data-target="#myModal2" class="tip btn btn-primary" title="<?= lang('email') ?>">
                                <i class="fa fa-envelope-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('email') ?></span>
                            </a>
                        </div>
						<!--
						<div class="btn-group">
                            <a href="<?= site_url('sale_order/flora/' . $inv->id) ?>" target="_blank" class="tip btn btn-primary" title="<?= lang('flora') ?>">
                                <i class="fa fa-print"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('flora') ?></span>
                            </a>
                        </div>-->

                        <?php if ($Owner || $Admin || $GP['sale_order-export']) { ?>
                        <div class="btn-group">
                            <a href="<?= site_url('sale_order/pdf/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('download_pdf') ?>">
                                <i class="fa fa-download"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                            </a>
                        </div>
                        <?php } ?>
                        <?php if ($inv->order_status != 'completed' && $inv->order_status != 'rejected') { ?>
    						<?php if($Owner || $Admin || $GP['sale_order-edit']) { ?>
                            <div class="btn-group">
                                <a href="<?= site_url('sale_order/edit_sale_order/' . $inv->id) ?>" class="tip btn btn-warning sledit" title="<?= lang('edit') ?>">
                                    <i class="fa fa-edit"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>
                                </a>
                            </div>
    						<?php } ?>
    						<!-- <?php if($Owner || $Admin || $GP['sale_order-delete']) { ?>
                            <div class="btn-group">
                                <a href="#" class="tip btn btn-danger bpo" title="<b><?= $this->lang->line("delete_sale") ?></b>"
                                    data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= site_url('sales/delete/' . $inv->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"
                                    data-html="true" data-placement="top">
                                    <i class="fa fa-trash-o"></i>
                                    <span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>
                                </a>
                            </div>
    						<?php } ?> -->
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
