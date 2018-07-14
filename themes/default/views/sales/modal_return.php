<style type="text/css">
    @media print {
        .modal-dialog {
            width: 95% !important;
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
			<!--<div class="text-center">
				<h1><?=lang('invoice')?></h1>
			</div>-->
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
                        
                        <br>
                    </p>
                    </div>
                    <div class="col-xs-7 text-right">
						<p style="font-size:16px; margin:0 !important;"><?= lang("sales_receipt"); ?></p>
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
                    <?php echo $this->lang->line("from"); ?>:
                    <?php if ($Settings->system_management == 'project') { ?>
          
                        <h2 style="margin-top:10px;"><?= $inv->biller ?></h2>
                    <?php } ?>
                    <?php  if(isset($biller->address)){
						echo $biller->address . "<br>" ; 
					}?>
					<?php  if($biller->phone != null){
						echo lang("tel") . ": " . $biller->phone . "<br>"; 
                    }?>
					<?php if(isset($biller->email)){
						echo  lang("email") . ": " . $biller->email;
					}?>
                </div>
                <div class="col-xs-6">
                    <?php echo $this->lang->line("to"); ?>:<br/>
					<?php if(isset($inv->customer)){ ?>
						<h2 style="margin-top:10px;"><?= $inv->customer; ?></h2>
					<?php } ?>
                    <?php
					if(isset($customer->address) || isset($customer->city) ){
						echo $customer->address . "<br>" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br>" . $customer->country;
					}
                    echo "<p>";
					if(isset($customer->cf1)){
						if ($customer->cf1 != "-" && $customer->cf1 != "") {
							echo "<br>" . lang("ccf1") . ": " . $customer->cf1;
						}
					}
					if(isset($customer->cf2)){
						if ($customer->cf2 != "-" && $customer->cf2 != "") {
							echo "<br>" . lang("ccf2") . ": " . $customer->cf2;
						}
					}
					if(isset($customer->cf3)){
						if ($customer->cf3 != "-" && $customer->cf3 != "") {
							echo "<br>" . lang("ccf3") . ": " . $customer->cf3;
						}
					}
					if(isset($customer->cf4)){
						if ($customer->cf4 != "-" && $customer->cf4 != "") {
							echo "<br>" . lang("ccf4") . ": " . $customer->cf4;
						}
					}
					if(isset($customer->cf5)){
						if ($customer->cf5 != "-" && $customer->cf5 != "") {
							echo "<br>" . lang("ccf5") . ": " . $customer->cf5;
						}
					}
					if(isset($customer->cf6)){
						if ($customer->cf6 != "-" && $customer->cf6 != "") {
							echo "<br>" . lang("ccf6") . ": " . $customer->cf6;
						}
					}

                    echo "</p>";
					if(($customer->phone) != null){
						echo lang("tel") . ": " . $customer->phone . "<br>";
					}
					if(($customer->email) != null){
						echo lang("email") . ": " . $customer->email;
					}
                    ?>
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
                        <th><?= lang("unit_price"); ?></th>
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
                    $total = 0;
                    if (is_array($rows)) {
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
								<td style="text-align:right; width:100px;"><?= $this->erp->formatMoney($row->unit_price); ?></td>
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
                    $col = 4;
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
					<?php if($inv->surcharge != 0){ ?>
                        <tr>
                            <td></td>
                            <td colspan="<?= $col; ?>"
                                style="text-align:right; font-weight:bold;"><?= lang("surcharge"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->surcharge); ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td></td>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoney($total); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("paid"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney($inv->paid); ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="<?= $col; ?>"
                            style="text-align:right; font-weight:bold;"><?= lang("balance"); ?>
                            (<?= $default_currency->code; ?>)
                        </td>
                        <td style="text-align:right; font-weight:bold;"><?= $this->erp->formatMoney(($total) - ($inv->paid)); ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>			
			<br/>
			<div class="row">
				<div class="clearfix"></div>
				<div class="col-xs-3  pull-left" style="text-align:center">
				<hr/>
					<p><?= lang("seller"); ?>
					<?= $biller->company != '-' ? $biller->company : $biller->name; ?></p>
					<p><?= lang("stamp_sign"); ?></p>
					</div>
					<div class="col-xs-3  pull-right" style="text-align:center">
					<hr/>
						<p><?= lang("customer"); ?>
						 <?= $customer->company ? $customer->company : $customer->names; ?></p>
						<p><?= lang("stamp_sign"); ?></p>
					</div>
					<div class="col-xs-3  pull-right" style="text-align:center">
						<hr/>
						<p><?= lang("Account"); ?>
						<?= $customer->company ? $customer->company : $customer->names; ?></p>
						<p><?= lang("stamp_sign"); ?></p>
					</div>
					<div class="col-xs-3  pull-right" style="text-align:center">
						<hr/>
						<p><?= lang("warehouse"); ?>
						<?= $warehouse->name ?></p>
						<p><?= lang("stamp_sign"); ?></p>
					</div>
				</div>
                <!-- <div class="col-xs-5 pull-right no-print" >
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
                </div> -->
            </div>
            <?php if (!$Supplier || !$Customer) { ?>
							 
                <div class="buttons">
                    <div class="btn-group btn-group-justified">
                        <!--<div class="btn-group">
                            <a href="<? /*= site_url('sales/invoice_return_chea_kheng/' . $inv->id) */ ?>" class="tip btn btn-primary" title="<? /*= lang('Return_Chea_Kheng') */ ?>">
                                <i class="fa fa-angle-double-left"></i>
                                <span class="hidden-sm hidden-xs"><? /*= lang('Return_Chea_Kheng') */ ?></span>
                            </a>
                        </div>-->
						<div class="btn-group">
                            <a href="<?= site_url('sales/creadit_note/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('Creadit_Note') ?>">
                                <i class="fa fa-angle-double-left"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('Creadit_Note') ?></span>
                            </a>
                        </div>
                        <!--<div class="btn-group">
                            <a href="<? /*= site_url('sales/invoice_return_sbps/' . $inv->id) */ ?>" class="tip btn btn-primary" title="<? /*= lang('SBPS') */ ?>">
                                <i class="fa fa-angle-double-left"></i>
                                <span class="hidden-sm hidden-xs"><? /*= lang('SBPS') */ ?></span>
                            </a>
                        </div>-->
						<div class="btn-group">
							<button type="button" class="btn btn-primary"  onclick="window.print();">
								<i class="fa fa-print"></i> <?= lang('print'); ?>
							</button>
                        </div>						
                        <div class="btn-group">
                            <a href="<?= site_url('sales/view_return/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('view') ?>">
                                <i class="fa fa-file-text-o"></i>
                                <span class="hidden-sm hidden-xs"><?= lang('view') ?></span>
                            </a>
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
