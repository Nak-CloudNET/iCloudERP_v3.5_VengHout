<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice&nbsp;<?= $invs->reference_no ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <link href="<?php echo $assets ?>styles/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $assets ?>styles/custome.css" rel="stylesheet">
</head>
<style>
    .container {
        width: 100%;
        margin: 20px auto;
        padding: 10px;
        font-size: 14px;
        /*box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);*/
        position: relative;
    }

    .title-header tr {
        border: 1px solid #000 !important;
    }

    .border td, .border th {
        border: 1px solid #000 !important;
        border-top: 1px solid #000 !important;
    }

    @media print {

        .container {
            width: 95% !important;
            margin: 0 auto !important;
        }

        .container .col-xs-12 {
            margin-top: 20px !important;
        }

        .pageBreak {
            page-break-after: always;
            -webkit-page-break-after: always;
        }

        .customer_label {
            padding-left: 0 !important;
        }

        tbody {
            display: table-row-group;
            -webkit-print-color-adjust: exact;
        }

        .print th {
            color: white !important;
            background: #444 !important;

        }

        tfoot {
            display: table-footer-group;
            -webkit-display: table-footer-group;
            page-break-after: always;
        }

        .invoice_label {
            padding-left: 0 !important;
        }

        #footer {
            bottom: 10px !important;
        }

        #note {
            max-width: 95% !important;
            margin: 0 auto !important;
            border-radius: 5px 5px 5px 5px !important;
            margin-left: 26px !important;
        }

        .col-xs-12, .col-sm-12 {
            padding-left: 1px;
            padding-right: 1px;
            margin-left: 0px;
            margin-right: 0px;
        }

        table {
            border-collapse: collapse;
        }

        #footer {
            position: fixed !important;
            bottom: 10px !important;
        }

        #footer .col-sm-4 {
            padding-left: 0 !important;
            margin-left: -5px !important;
        }

    }

    body {
        font-size: 12px !important;
        font-family: "Khmer OS System";
        -moz-font-family: "Khmer OS System";
    }

    .header {
        font-family: "Khmer OS Muol Light";
        -moz-font-family: "Khmer OS System";
        font-size: 18px;
    }

    .table > thead > tr > th, .table > thead > tr > td, tbody > tr > th, .table > tfoot > tr > th, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 5px;
    }

    .title {
        font-family: "Khmer OS Muol Light";
        -mox-font-family: "Khmer OS Muol Light";
        font-size: 15px;
    }

    h4 {
        margin-top: 0px;
        margin-bottom: 0px;
    }

    .noPadding tr {
        padding: 0px 0px;
        margin-top: 0px;
        margin-bottom: 0px;
        border: none;
    }

    .noPadding tr td {
        padding: 0px;
        margin-top: 0px;
        margin-bottom: 0px;
        border: 1px solid white;
    }

    .border-foot td {
        border: 1px solid #000 !important;
    }

    thead tr th {
        font-weight: normal;
        text-align: center;
    }

    .table {
        margin-bottom: 60px;
    }

</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $("#hide").click(function () {
            $(".myhide").toggle();
        });
    });
</script>
<body>
<div class="container" style="width: 821px;margin: 0 auto;">
    <div class="col-xs-12"
    <?php
    $cols = 6;
    if ($discount != 0) {
        $cols = 7;
    }
    ?>
    <div class="row">
        <table class="table">
            <thead>
            <tr class="thead" style="border-left:none;border-right: none;border-top:none;">
                <th colspan="9"
                    style="border-left:none;border-right: none;border-top:none;border-bottom: 1px solid #000 !important;">
                    <div class="row" style="margin-top: 0px !important;">
                        <div class="col-sm-3 col-xs-3 " style="margin-top: 0px !important;">
                            <?php if (!empty($biller->logo)) { ?>
                                <img class="img-responsive myhide"
                                     src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" id="hidedlo"
                                     style="width: 140px; margin-left: 25px;margin-top: -10px;"/>
                            <?php } ?>
                        </div>
                        <div class="col-sm-7 col-xs-7 company_addr " style="margin-top: -20px !important;">
                            <div class="myhide">
                                <center>
                                    <?php if ($biller->company) { ?>
                                        <h3 class="header"><?= $biller->company ?></h3>
                                    <?php } ?>

                                    <div style="margin-top: 15px;">
                                        <?php if (!empty($biller->vat_no)) { ?>
                                            <p>លេខអត្តសញ្ញាណកម្ម អតប (VAT No):&nbsp;<?= $biller->vat_no; ?></p>
                                        <?php } ?>

                                        <?php if (!empty($biller->address)) { ?>
                                            <p style="margin-top:-10px !important;">អាសយដ្ឋាន ៖
                                                &nbsp;<?= $biller->address; ?></p>
                                        <?php } ?>

                                        <?php if (!empty($biller->phone)) { ?>
                                            <p style="margin-top:-10px ;">ទូរស័ព្ទលេខ
                                                (Tel):&nbsp;<?= $biller->phone; ?></p>
                                        <?php } ?>

                                        <?php if (!empty($biller->email)) { ?>
                                            <p style="margin-top:-10px !important;">សារអេឡិចត្រូនិច
                                                (E-mail):&nbsp;<?= $biller->email; ?></p>
                                        <?php } ?>
                                    </div>

                                </center>
                            </div>
                            <div class="invoice" style="margin-top:20px;">
                                <center>
                                    <h4 class="title">វិក្កយបត្រ</h4>
                                    <h4 class="title" style="margin-top: 3px;">Invoice</h4>
                                </center>

                            </div>
                        </div>
                        <div class="col-sm-2 col-xs-2 pull-right">
                            <div class="row">
                                <button type="button" class="btn btn-xs btn-default no-print pull-right"
                                        style="margin-right:15px;" onclick="window.print();">
                                    <i class="fa fa-print"></i> <?= lang('print'); ?>
                                </button>
                            </div>
                            <div class="row">
                                <button type="button" class="btn btn-xs btn-default no-print pull-right " id="hide"
                                        style="margin-right:15px;" onclick="">
                                    <i class="fa"></i> <?= lang('Hide/Show_header'); ?>
                                </button>
                            </div>

                        </div>
                    </div>
                    <div class="row" style="text-align: left;">
                        <div class="col-sm-7 col-xs-7">
                            <table>
                                <?php if (!empty($invs->authorize_name)) { ?>
                                    <tr>
                                        <td>Authorize by</td>
                                        <td>:</td>
                                        <td> <?= $invs->authorize_name ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($invs->warehouse_name)) { ?>
                                    <tr>
                                        <td style="width: 60% !important">From Location</td>
                                        <td>:</td>
                                        <td> <?= $invs->warehouse_name ?></td>
                                    </tr>
                                <?php } ?>
                                <?php if (!empty($invs->employee_name)) { ?>
                                    <tr>
                                        <td>Employee</td>
                                        <td>:</td>
                                        <td> <?= $invs->employee_name ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                        <div class="col-sm-5 col-xs-5">
                            <table class="noPadding" border="none">
                                <tr>
                                    <td style="width: 45%;">លេខរៀង / N<sup>o</sup></sup></td>
                                    <td style="width: 5%;">:</td>
                                    <td style="width: 50%;"><?= $invs->reference_no ?></td>
                                </tr>
                                <tr>
                                    <td>កាលបរិច្ឆេទ / Date</td>
                                    <td>:</td>
                                    <td><?= $this->erp->hrld($invs->date); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </th>
            </tr>
            <tr class="border thead print" style="background-color: #444 !important; color: #FFF !important;">
                <th>ល.រ<br/><?= strtoupper(lang('no')) ?></th>
                <th>លេខកូដទំនិញ<br/><?= strtoupper(lang('products_code')) ?></th>
                <th>ឈ្មោះទំនិញ<br/><?= strtoupper(lang('products_name')) ?></th>
                <th>បរិយាយមុខទំនិញ<br/><?= strtoupper(lang('description')) ?></th>
                <th>ខ្នាត<br/><?= strtoupper(lang('unit')) ?></th>
                <th>ចំនួន<br/><?= strtoupper(lang('quantity')) ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            $erow = 1;
            $total = 0;
            foreach ($stock_item as $si) {
                echo '
                    <tr class="border">
                        <td style="text-align:center;">' . $i . '</td>
                        <td>' . $si->code . '</td>
                        <td>' . $si->product_name . ' </td>
                        <td>' . $si->description . '</td>
                        <td style="text-align:center;">' . $si->unit_name . '</td>
                        <td style="text-align:center;">' . $this->erp->formatQuantity($si->qty_by_unit) . '</td>
                    </tr>     
                    ';
                $total += $si->qty_by_unit;
                $i++;
                $erow++;
            }
            ?>
            <?php
            if ($erow < 16) {
                $k = 16 - $erow;
                for ($j = 1; $j <= $k; $j++) {
                    echo '<tr class="border">
                            <td height="34px" style="text-align: center; vertical-align: middle">' . $i . '</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
                    $i++;
                }
            }
            ?>
            <tr class="border-foot">
                <td colspan="5" style="text-align: right; font-weight: bold;">ចំនួនសរុប
                    / <?= strtoupper(lang('total_qty')) ?></td>
                <td style="text-align: center; font-weight: bold;"><?= $total ?></td>
            </tr>
            </tbody>
        </table>

        <div id="footer" class="row">
            <div class="col-lg-4 col-sm-4 col-xs-4" style="text-align:center">
                <hr style="border: 0.5px solid #000; width: 80%">
                <p style=" margin-top: -20px !important"><b><?= lang("Signature Stock Manager"); ?></b></p>
            </div>
            <div class="col-lg-4 col-sm-4 col-xs-4 " style="text-align:center">
                <hr style="border: 0.5px solid #000; width: 80%">
                <p style=" margin-top: -20px !important"><b><?= lang("Stock"); ?></b></p>
            </div>
            <div class="col-lg-4 col-sm-4 col-xs-4" style="text-align:center">
                <hr style="border: 0.5px solid #000; width: 80%">
                <p style=" margin-top: -20px !important"><b><?= lang("Reciever"); ?></b></p>
            </div>
        </div>
    </div>
    <div style="width: 821px;margin: 20px">
        <a class="btn btn-warning no-print" href="<?= site_url('products/view_enter_using_stock'); ?>"
           style="border-radius: 0">
            <i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("Back"); ?>
        </a>
    </div>
</div>
</body>
</html>