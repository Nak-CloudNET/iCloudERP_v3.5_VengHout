<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("transfer") . " " . $transfer->transfer_no; ?></title>
    <link href="<?php echo $assets ?>styles/style.css" rel="stylesheet">
    <style type="text/css">
        html, body {
            height: 100%;
            background: #FFF;
        }

        body:before, body:after {
            display: none !important;
        }

        .table th {
            text-align: center;
            padding: 5px;
        }

        .table td {
            padding: 4px;
        }
    </style>
</head>

<body>
<div id="wrap">
    <?php if ($logo) { ?>
        <div class="text-center" style="margin-bottom:20px;">
            <img src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>"
                 alt="<?= $Settings->site_name; ?>">
        </div>
    <?php } ?>
    <div class="well well-sm">
        <div class="row bold">
            <div class="col-xs-4"><?= lang("date"); ?>: <?= $this->erp->hrld($transfer->date); ?>
                <br><?= lang("ref"); ?>: <?= $transfer->transfer_no; ?></div>
            <div class="col-xs-6 pull-right text-right">
                <?php $br = $this->erp->save_barcode($transfer->transfer_no, 'code39', 35, false, $transfer->id); ?>
                <img src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id').$transfer->id; ?>.png"
                     alt="<?= $transfer->transfer_no ?>"/>
                <?php $this->erp->qrcode('link', urlencode(site_url('transfers/view/' . $transfer->id)), 1, false, $transfer->id); ?>
                <img src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id').$transfer->id; ?>.png"
                     alt="<?= $transfer->transfer_no ?>"/>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <div class="col-xs-5">
            <p><?php echo $this->lang->line("from"); ?>:</p>

            <h3><?php echo $from_warehouse->name . " ( " . $from_warehouse->code . " )"; ?></h3>
            <?php echo $from_warehouse->address . "<br>" . $from_warehouse->phone . "<br>" . $from_warehouse->email;
            ?>
        </div>
        <div class="col-xs-5 col-xs-offset-1">

            <p><?php echo $this->lang->line("to"); ?>:</p>

            <h3><?php echo $to_warehouse->name . " ( " . $to_warehouse->code . " )"; ?></h3>
            <?php echo strip_tags($to_warehouse->address) . "<br>" . $to_warehouse->phone . "<br>" . $to_warehouse->email;
            ?>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("no"); ?></th>
                <th style="vertical-align:middle;"><?php echo $this->lang->line("description"); ?></th>
                <th style="text-align:center; vertical-align:middle;"><?php echo $this->lang->line("quantity"); ?></th>
            </tr>
            </thead>

            <tbody>
            <?php $r = 1;
            foreach ($rows as $row):
                ?>
                <tr>
                    <td style="text-align:center; width:25px;"><?php echo $r; ?></td>
                    <td style="text-align:left;"><?= $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : ''); ?></td>
                    <td style="text-align:center; width:80px; "><?php echo $this->erp->formatQuantity($row->quantity); ?></td>
                </tr>
                <?php $r++;
                $tQty += $row->quantity;
            endforeach;
            ?>
            </tbody>
            <tfoot>
            <?php
            $col = 1;
            if ($this->Settings->tax1) {
                $col += 1;
            }
            ?>

            <?php if ($this->Settings->tax1) { ?>
                <tr>
                    <td colspan="<?php echo $col; ?>"
                        style="text-align:right; padding-right:10px;"><?php echo $this->lang->line("total"); ?>
                    </td>
                    <td style="text-align:center; padding-right:10px;"><?php echo $this->erp->formatQuantity($tQty); ?></td>
                </tr>
            <?php } ?>
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
        <div class="col-xs-4 pull-right">
            <p><?= lang("received_by"); ?>: </p>

            <p>&nbsp;</p>

            <p>&nbsp;</p>
            <hr>
            <p><?= lang("stamp_sign"); ?></p>
        </div>
    </div>
</div>
</body>
</html>
