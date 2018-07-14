<?php
//$this->erp->print_arrays($invs);
//$note_arr = explode('/',$biller->phone);
//$this->erp->print_arrays($note_arr[0],$note_arr[1],$note_arr[2]);

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <title>Credit Note</title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <link href="<?php echo $assets ?>styles/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $assets ?>styles/custome.css" rel="stylesheet">
    <link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
</head>
<style>
    .container {
        width:19.7cm;
        height:auto;
        margin: 20px auto;
        /*padding: 10px;*/
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    }
    tbody{
        font-family:khmer Os;
        font-family:Times New Roman !important;
    }
    .table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
        background-color: #444 !important;
        color: #FFF !important;
    }
    .table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
        border: 1px solid #000 !important;
    }
    #tels span {
        padding-left: 23px;
    }
    #tels span:first-child {
        padding-left: 0 !important;
    }
    @page  {
        size: A4;
        margin:20px;
    }
    @media print {
        thead th,b {
            font-size: 12px !important;
        }
        tr td{
            font-size: 13px !important;
        }
        .no-print {
            display: none;
        }
    }
    .table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
        background-color: #fff !important;
        color: #000 !important;

    }
    .table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
        border: 1px solid #000 !important;
    }
</style>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-xs-12">
            <center><h3 style="font-weight:bold !important;font-family:Time New Roman !important;margin-bottom:20px !important;">របាយការណ៍លំអិតអំពីការលក់បេតុង<br>Detail about Selling Concrete</h3></center>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <table style="width: 100%">
                <tr>
                    <td style="width: 20%">
                        period :
                    </td>
                    <td style="width: 20%">

                    </td>
                    <td style="width: 20%">
                        IO :
                    </td>
                    <td style="width: 40%">

                    </td>
                </tr><tr>
                    <td style="width: 20%">
                        Project name :
                    </td>
                    <td style="width: 20%">
                        <?=$biller->company?>
                    </td>
                    <td style="width: 20%">

                    </td>
                    <td style="width: 40%">

                    </td>
                </tr><tr>
                    <td style="width: 20%">
                        Location :
                    </td>
                    <td colspan="3" style="width: 20%">
                        <?=$biller->address?>
                    </td>

                </tr>
            </table>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12 col-sm-12 col-xs-12">
            <table class="table table-bordered table-hover" style="width: 100%" border="1">
                <thead>
                <tr>
                    <th style="width:30%;font-size:13px !important;" class="text-center"><?=lang('Date/Time')?></th>
                    <th style="width:10%;font-size:13px !important;"class="text-center"><?=lang(' truck #')?></th>
                    <th style="width:30%;font-size:13px !important;"class="text-center"><?=lang('Delivery paper Number')?></th>
                    <th style="width:10%;font-size:13px !important;"class="text-center"><?=lang('Qty')?>(m<sup>2</sup>)</th>
                    <th style="width:20%;font-size:13px !important;" class="text-center"><?=lang('Concrete Type')?></th>
                </tr>
                </thead>
                <tbody>
                <?php

                foreach ($rows as $row) :
                    $totalqty+=$row->quantity_received;
                    //$this->erp->print_arrays($row);
                    ?>
                    <tr style="border: #000 1px solid;">
                        <td style=" vertical-align:middle;"><?=$row->date;?></td>
                        <td style="vertical-align:middle;"> <?=$driver->name;?></td>
                        <td style="vertical-align:middle;"><?=$row->do_reference_no;?></td>
                        <td style="text-align:center; vertical-align:middle;"><?= round($row->quantity_received)?></td>
                        <td style=" vertical-align:middle;"><?=$row->note;?></td>
                    </tr>
                    <?php
                    $i++;$erow++;
                    endforeach;

                if($erow<21){
                    $k=21 - $erow;
                    for($j=1;$j<=$k;$j++){
                        echo  '<tr style="border: #000 1px solid;">
                                    <td height="34px" class="text-center" ></td>
                                    <td style="width:34px;"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>';


                    }
                }
                ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?=$this->erp->formatQuantity($totalqty);?></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div style="width: 821px;margin: 20px">
        <a class="btn btn-warning no-print" href="<?= site_url('sales/return_sales'); ?>" style="border-radius: 0">
            <i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
        </a>
    </div>
</div>

</body>
</html>