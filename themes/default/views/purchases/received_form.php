        <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
            <i class="fa fa-print"></i> <?= lang('print'); ?>
        </button>   
        <?php if ($logo) { ?>
        <div class="text-center" style="margin-bottom:20px;">
           <!-- <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>"
                 alt="<?= $Settings->site_name; ?>">-->
                 <p><b>ITEMS RECEIVED</b></p>
        </div>
    <?php } ?>
    <div class="well well-sm">
        <div class="row bold">
            <div class="col-xs-5">
            <p class="bold">
                <?= lang("ref"); ?>: <?= $inv->reference_no; ?><br>
                <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?><br>
            </p>
            </div>
            <div class="col-xs-7 text-right">
                <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 70, false); ?>
                <img src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                     alt="<?= $inv->reference_no ?>"/>
                <?php $this->erp->qrcode('link', urlencode(site_url('purchases/view/' . $inv->id)), 2); ?>
                <img src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                     alt="<?= $inv->reference_no ?>"/>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row" style="margin-bottom:15px;">
        <div class="col-xs-6">
            <?php echo $this->lang->line("from"); ?>:
            
            
            <h2 style="margin-top:10px;"><?= $supplier->company ? $supplier->company : $supplier->name; ?></h2>
            <?= $supplier->company ? "" : "Attn: " . $supplier->name ?>

            <?php
            echo $supplier->address . "<br />" . $supplier->city . " " . $supplier->postal_code . " " . $supplier->state . "<br />" . $supplier->country;

            echo "<p>";

            if ($supplier->cf1 != "-" && $supplier->cf1 != "") {
                echo "<br>" . lang("scf1") . ": " . $supplier->cf1;
            }
            if ($supplier->cf2 != "-" && $supplier->cf2 != "") {
                echo "<br>" . lang("scf2") . ": " . $supplier->cf2;
            }
            if ($supplier->cf3 != "-" && $supplier->cf3 != "") {
                echo "<br>" . lang("scf3") . ": " . $supplier->cf3;
            }
            if ($supplier->cf4 != "-" && $supplier->cf4 != "") {
                echo "<br>" . lang("scf4") . ": " . $supplier->cf4;
            }
            if ($supplier->cf5 != "-" && $supplier->cf5 != "") {
                echo "<br>" . lang("scf5") . ": " . $supplier->cf5;
            }
            if ($supplier->cf6 != "-" && $supplier->cf6 != "") {
                echo "<br>" . lang("scf6") . ": " . $supplier->cf6;
            }

            echo "</p>";
            echo lang("tel") . ": " . $supplier->phone . "<br />" . lang("email") . ": " . $supplier->email;
            ?>
        </div>
        <div class="col-xs-6">
            <?php echo $this->lang->line("to"); ?>:<br/>
            <h2 style="margin-top:10px;"><?= $Settings->site_name; ?></h2>
            <?= $warehouse->name ?>

            <?php
            echo $warehouse->address;
            echo ($warehouse->phone ? lang("tel") . ": " . $warehouse->phone . "<br>" : '') . ($warehouse->email ? lang("email") . ": " . $warehouse->email : '');
            ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped print-table order-table">

            <thead>

            <tr>
                <th><?= lang("no"); ?></th>
                <th><?= lang("code"); ?></th>
				<th><?= lang("name"); ?></th>
                <th><?= lang("unit"); ?></th>
                <th><?= lang("quantity"); ?></th>
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
                        <?= $row->product_code ?>
                    </td>
					<td style="vertical-align:middle;">
                        <?= $row->product_name?>
                    </td>
					<td style="width: 80px; text-align:center; vertical-align:middle;">
						<?= ($row->variant ? ' ' . $row->variant . '' : ''); ?>
                        <?= $row->details ? '<br>' . $row->details : ''; ?>
                        <?= ($row->expiry && $row->expiry != '0000-00-00') ? '<br>' . $this->erp->hrsd($row->expiry) : ''; ?>
					</td>
                    <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                    
                </tr>
                <?php
                $r++;
                $total += $row->quantity;
            endforeach;
            ?>
            </tbody>
            <tfoot>
            <?php
            $col = 4;
            if($Owner || $Admin || $GP['purchases-cost']){
                $col++;
            }
            if ($inv->status == 'partial') {
                $col++;
            }
            if ($Settings->product_discount) {
                $col++;
            }
            if ($Settings->tax1) {
                $col++;
            }
            if ($Settings->product_discount && $Settings->tax1) {
                $tcol = $col - 2;
            } elseif ($Settings->product_discount) {
                $tcol = $col - 1;
            } elseif ($Settings->tax1) {
                $tcol = $col - 1;
            } else {
                $tcol = $col;
            }
            ?>

            <tr>
                <td colspan="3"
                    style="text-align:right; font-weight:bold;"><?= lang("total"); ?>
                </td>
                <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $this->erp->formatMoneyPurchase($total); ?></td>
            </tr>
            </tfoot>
        </table>
    </div>
    <br/>
        
    <br/>
    
    <div class="row">
        <div class="col-sm-4 col-xs-4">
            <center>
                <hr style="border: 1px solid #CCC !important; width: 80%">
                <p><?= lang('prepared_by') ?></p>
            </center>
        </div>
        <div class="col-sm-4 col-xs-4">
            <center>
                 <hr style="border: 1px solid #CCC !important; width: 80%">
                <p><?= lang('shipper') ?></p>
            </center>
        </div>
        <div class="col-sm-4 col-xs-4">
            <center>
                 <hr style="border: 1px solid #CCC !important; width: 80%">
                <p><?= lang('stock_keeper') ?></p>
            </center>
        </div>
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

<script type="text/javascript">
$(document).ready( function() {
$('.tip').tooltip();
});
</script>