<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $page_title . " " . lang("no") . " " . $inv->id; ?></title>
    <base href="<?= base_url() ?>"/>
    <meta http-equiv="cache-control" content="max-age=0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>
    <link rel="stylesheet" href="<?= $assets ?>styles/theme.css" type="text/css"/>
    <style type="text/css" media="all">
        body {
            color: #000;
        }

        #wrapper {
            max-width: 480px;
            margin: 0 auto;
            padding-top: 20px;
        }

        .btn {
            border-radius: 0;
            margin-bottom: 5px;
        }

        h3 {
            margin: 5px 0;
        }

        @media print {
            .no-print {
                display: none;
            }

            #wrapper {
                max-width: 480px;
                width: 100%;
                min-width: 250px;
                margin: 0 auto;
            }
        }
    </style>

</head>

<body>
<div id="wrapper">
    <div id="receiptData">

        <div class="text-center">
            <h3 style="text-transform:uppercase;"><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h3>
            <?php
            echo "<p>" . $biller->address . " " . $biller->city . " " . $biller->postal_code . " " . $biller->state . " " . $biller->country .
                "<br>" . lang("tel") . ": " . $biller->phone . "</p>";
            ?>
        </div>
        <?php
        if ($pos->cf_title1 != "" && $pos->cf_value1 != "") {
            echo $pos->cf_title1 . ": " . $pos->cf_value1 . "<br>";
        }
        if ($pos->cf_title2 != "" && $pos->cf_value2 != "") {
            echo $pos->cf_title2 . ": " . $pos->cf_value2 . "<br>";
        }
        echo "<p>" . lang("reference_no") . ": " . $inv->reference_no . "<br>";
        echo lang("customer") . ": " . $inv->customer . "<br>";
        echo lang("date") . ": " . $this->erp->hrld($inv->date) . "</p>";
        ?>
        <div style="clear:both;"></div>
        <table class="table table-striped table-condensed">
            <tbody>
            <?php
            $r = 1;
            foreach ($rows as $row) {
                echo '<tr><td colspan="2">#' . $r . ': &nbsp;&nbsp;' . $row->product_name . '</td></tr>';
                echo '<tr><td>' . $this->erp->formatQuantity($row->quantity) . ' x ' . $this->erp->formatMoney($row->net_unit_price + ($row->item_tax / $row->quantity)) . '</td><td class="text-right">' . $this->erp->formatMoney($row->subtotal) . '</td></tr>';
                $r++;
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th><?= lang("total"); ?></th>
                <th class="text-right"><?= $this->erp->formatMoney($inv->total); ?></th>
            </tr>
            <?php
            if ($inv->order_tax != 0 && $Settings->tax2) {
                echo '<tr><th>' . lang("tax") . '</th><th class="text-right">' . $this->erp->formatMoney($inv->order_tax) . '</th></tr>';
            }
            if ($inv->total_discount != 0) {
                echo '<tr><th>' . lang("discount") . '</th><th class="text-right">' . $this->erp->formatMoney($inv->total_discount) . '</th></tr>';
            }
            ?>
            <tr>
                <th><?= lang("grand_total"); ?></th>
                <th class="text-right"><?= $this->erp->formatMoney($inv->grand_total); ?></th>
            </tr>
            <!--<tr><th><?= lang("total_items"); ?></th> <th class="text-right"><?= $inv->total_items; ?></th></tr>-->
            </tfoot>
        </table>
        <?php
        if ($payments) {
            echo '<table class="table table-striped table-condensed"><tbody>';
            foreach ($payments as $payment) {
                echo '<tr>';
                if ($payment->paid_by == 'cash' && $payment->paid) {
                    echo '<td>' . lang("amount") . ': ' . $payment->amount . '</td>';
                    echo '<td>' . lang("change") . ': ' . $this->erp->formatMoney($payment->paid - $inv->total) . '</td>';
                }
                if (($payment->paid_by == 'CC' || $payment->paid_by == 'ppp' || $payment->paid_by == 'stripe') && $payment->cc_no) {
                    echo '<td>' . lang("amount") . ': ' . $payment->amount . '</td>';
                    echo '<td>' . lang("no") . ': ' . 'xxxx xxxx xxxx ' . substr($payment->cc_no, -4) . '</td>';
                    echo '<td>' . lang("name") . ': ' . $payment->cc_holder . '</td>';
                }
                if ($payment->paid_by == 'Cheque' && $payment->cheque_no) {
                    echo '<td>' . lang("amount") . ': ' . $payment->amount . '</td>';
                    echo '<td>' . lang("cheque_no") . ': ' . $payment->cheque_no . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
        ?>

        <p class="text-center">
            <?= $this->erp->decode_html($biller->invoice_footer); ?>
        </p>
        <?php $this->erp->qrcode('link', urlencode(site_url('pos/view/' . $inv->id)), 2); ?>
        <div class="text-center"><img
                src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                alt="<?= $inv->reference_no ?>"/></div>
        <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39'); ?>
        <div class="text-center"><img
                src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                alt="<?= $inv->reference_no ?>"/></div>
        <div style="clear:both;"></div>
    </div>

</div>
</body>
</html>
