<?php //$this->erp->print_arrays($invs) ?>

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
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
        position:relative;
    }
    .title-header tr{
        border: 1px solid #000 !important;
    }
    .border td,.border th{
        border: 1px solid #000 !important;
        border-top: 1px solid #000 !important;
    }

    @media print {
        .pageBreak {
            page-break-after: always;
            -webkit-page-break-after: always;
        }

        .customer_label {
            padding-left: 0 !important;
        }
        .print th{
            color:white !important;
            background: #444 !important;

        }
        tbody{
            display:table-row-group;
        }
        thead {
            display: table-header-group;
            overflow: visible !important;
        }
        thead tr{
            break:inside: auto;
            clear:both;
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
        #note{
            max-width: 95% !important;
            margin: 0 auto !important;
            border-radius: 5px 5px 5px 5px !important;
            margin-left: 26px !important;
        }
        .col-xs-12, .col-sm-12{
            padding-left:1px;
            padding-right:1px;
            margin-left:0px;
            margin-right:0px;
        }
        table {border-collapse: collapse;}
        tr {
            page-break-inside: avoid;
            -webkit-page-break-inside: avoid;
        }
    }

    body{
        font-size: 12px !important;
        font-family: "Khmer OS System";
        -moz-font-family: "Khmer OS System";
    }
    .header{
        font-family:"Khmer OS Muol Light";
        -moz-font-family: "Khmer OS System";
        font-size: 18px;
    }

    .table > thead > tr > th,.table > thead > tr > td, tbody > tr > th, .table > tfoot > tr > th, .table > tbody > tr > td, .table > tfoot > tr > td{
        padding:5px;
    }
    .title{
        font-family:"Khmer OS Muol Light";
        -mox-font-family:"Khmer OS Muol Light";
        font-size: 15px;
    }
    h4{
        margin-top: 0px;
        margin-bottom: 0px;
    }
    .noPadding tr{
        padding: 0px 0px;
        margin-top: 0px;
        margin-bottom: 0px;
        border: none;
    }
    .noPadding tr td{
        padding: 0px;
        margin-top: 0px;
        margin-bottom: 0px;
        border:1px solid white;
    }
    .border-foot td{
        border: 1px solid #000 !important;
    }
    .border tr td{
        /*background: rgba(0,0,0,0.6);*/
    }
    thead tr th{
        font-weight: normal;
        text-align: center;
    }

</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $("#hide").click(function(){
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
    <?php
    //$this->erp->print_arrays($invs);
    ?>
    <div class="row">
        <table class="table">
            <thead>
            <tr class="thead" style="border-left:none;border-right: none;border-top:none;">
                <th colspan="9" style="border-left:none;border-right: none;border-top:none;border-bottom: 1px solid #000 !important;">
                    <div class="row" style="margin-top: 0px !important;">
                        <div class="col-sm-8 col-xs-8 " style="margin-top: 0px !important; text-align: left">
                            <h2><?= $invs->company ?></h2>
                            <div style="width: 80%;border-top: 1px solid black;margin-left: -10px" ></div>
                        </div>
                        <div  class="col-sm-4 col-xs-4 company_addr "  style="margin-top: -20px !important;">

                            <div class="invoice" style="margin-top:0px;">
                                <center>
                                    <h3 class="title">ប័ណ្ណប្រគល់ទំនិញ</h3>
                                    <h3 >Delivery note</h3>
                                </center>

                            </div>
                        </div>
                    </div>
                    <div class="row" style="text-align: left;width: 100%; margin: 0 auto; ">
                        <div class="col-sm-6 col-xs-6 pull-left " style="font-size: 12px; border: 1px solid black;border-radius: 5px !important; padding: 6px">
                                <table >
                                    <tr>
                                        <td style="width: 15%;"><?= lang('ប្រគល់ជូន / Deliver to') ?></td>
                                        <td style="width: 2%;">:</td>
                                        <td style="width: 25%;"><?=  $customer->company ?></td>
                                    </tr>
                                    <tr>
                                        <td><?= lang('តំណាង​/ Rep') ?> </td>
                                        <td>:</td>
                                        <td><?= $customer->name ?></td>
                                    </tr>

                                        <tr>
                                            <td><?= lang('ទូរស័ព្ទ/ Phone') ?> </td>
                                            <td>:</td>
                                            <td><?= $customer->phone ?></td>
                                        </tr>



                                    <tr>
                                        <td><?= lang('បញ្ជូនទៅ / Ship To') ?></td>
                                        <td>:</td>
                                        <td><?= $customer->address ?></td>
                                    </tr>

                            </table>
                        </div>

                        <div class="col-sm-3 col-xs-3 pull-right " style="font-size: 12px; border: 1px solid black;border-radius: 5px !important; width: 340px;padding: 6px">
                            <table class="">
                                <tr>
                                    <td style="width: 45%"><?= lang('កាលបរិឆ្ឆេទ​/Date') ?></td>
                                    <td style="width: 4%;">:</td>
                                    <td style=""><?= $this->erp->hrsd($invs->date) ?></td>
                                </tr>
                                <?php
                                    if($invs->so_no){
                                        echo "<tr>
                                                        <td>".lang('ល.រ​វិក័យប័ត្រ / Inv No')."</td>
                                                        <td>:</td>
                                                        <td> $invs->so_no</td>
                                                    </tr>";
                                    }
                                ?>
                                <tr>
                                    <td><?= lang('លេខប័ណ្ណប្រគល់ទំនិញ​ / DN No') ?> </td>
                                    <td>:</td>
                                    <td><?= $invs->reference_no ?></td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </th>
            </tr>
            <tr class="border thead print" style=" font-size: 12px;  background-color: #444 !important; color: #FFF !important;">
<!--                <th>ល.រ<br/>--><?//= strtoupper(lang('no')) ?><!--</th>-->
<!--                <th>បរិយាយមុខទំនិញ<br/>--><?//= strtoupper(lang('description')) ?><!--</th>-->
<!--                <th>បរិមាណបញ្ជាទិញ<br/>--><?//= strtoupper(lang('qty ordered')) ?><!--</th>-->
<!--                <th>​ឯកតា<br />--><?//= strtoupper(lang('unit')) ?><!--</th>-->
<!--                <th>បរិមាណបានបញ្ជូន<br/>--><?//= strtoupper(lang('qty delivered')) ?><!--</th>-->
<!--                <th>ឃ្លាំង<br />--><?//= strtoupper(lang('site')) ?><!--</th>-->
<!--                <th>បរិមាណនៅសល់<br/>--><?//= strtoupper(lang('qty remain')) ?><!--</th>-->
<!--                <th> នាយឃ្លាំង<br/>--><?//= strtoupper(lang('wh counter')) ?><!--</th>-->
                <th><?= strtoupper(lang('no')) ?></th>
                <th><?= strtoupper(lang('description')) ?></th>
                <th><?= strtoupper(lang('qty ordered')) ?></th>
                <th><?= strtoupper(lang('unit')) ?></th>
                <th><?= strtoupper(lang('qty delivered')) ?></th>
                <th><?= strtoupper(lang('site')) ?></th>
                <th><?= strtoupper(lang('qty remain')) ?></th>
                <th><?= strtoupper(lang('wh counter')) ?></th>
            </thead>
            <tbody>
            <?php
//            $this->erp->print_arrays($invs);
            $no = 1;
            $erow = 1;
            $totalRow = 0;  $no = 1;
            foreach ($rows as $row) {
                //$this->erp->print_arrays($row);

                //$row = 1;
                ?>
                <tr class="border ">
                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"><?php echo $no ?></td>
                    <td style="border-top:none !important;border-bottom:none !important; ">
                        <?=$row->product_name;?>
                    </td>
                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;">
                        <?=$this->erp->formatQuantity($row->quantity);?>
                    </td>
                    <?php if ($row->option_id >= 1) { ?>
                        <td style="border-top:none !important;border-bottom:none !important; text-align: center;"><?= $row->variant ?></td>
                    <?php } else { ?>
                        <td style="border-top:none !important;border-bottom:none !important; text-align: center;"><?= $row->unit ?></td>
                    <?php } ?>
                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;">
                        <?=$this->erp->formatQuantity($row->quantity_received)?>
                    </td>
                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;">
                        <?=$row->code?>
                    </td>
                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;">
                        <?=($row->quantity)-($row->quantity_received)?>
                    </td>
                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;">

                    </td>

                </tr>
                <?php
                $no++;
                $erow++;
                $totalRow++;
//                    if ($totalRow % 25 == 0) {
//                        echo '<tr class="pageBreak"></tr>';
//                    }
            }
            ?>
            <?php
            if($erow<11){
                $k=11 - $erow;
                for($j=1;$j<=$k;$j++) {
                    if($discount != 0) {
                        echo  '<tr class="border">
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"height="34px" style="text-align: center; vertical-align: middle"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                </tr>
                                ';
                    }else {
                        echo  '<tr class="border">
                                   <td style="border-top:none !important;border-bottom:none !important; text-align: center;"height="34px" style="text-align: center; vertical-align: middle"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    <td style="border-top:none !important;border-bottom:none !important; text-align: center;"></td>
                                    
                                </tr>                              
                                ';
                    }
                    $no++;
                }
            }
            ?>
            <tr class="border">
                <td colspan="8" style="font-size: 12px"><p>សម្គាល់ / Message :</p><p><?php echo $invs->invoice_footer ?></p></td>
            </tr>
            </tbody>
        </table>
        <div class="row" style="display: none">
            <div class="col-sm-12">
                <div style="float: left; width: 140px; margin-right: 10px; font-size: 12px;border: 1px solid black;​​" >
                   <p style="text-align: center"> អ្នកទទួល​<br>Received by</p>ឈ្មោះ​៖<span></span>​<br>Name<br>ទូរស័ព្ទ៖<span></span><br>Phone<br>ហត្ថលេខា៖<br>Sign
                </div>
                <div  style=" float: left;width: 140px; margin-right: 10px; font-size: 12px;border: 1px solid black;​​" >
                    <p style="text-align: center"> ដឹកជញ្ជូនដោយ<br>Delivered by</p>
                    ឈ្មោះ​៖<span></span>​<br>Name<br>លេខឡាន៖<span></span><br>Truck No<br>ហត្ថលេខា៖<br>Sign
                </div>
                <div  style="float: left; width: 140px;margin-right: 10px;  font-size: 12px;border: 1px solid black;​​" >
                    <p style="text-align: center"> នាយឃ្លាំង<br>WH Controller</p>
                    ឈ្មោះ​៖<span></span>​<br>Name<br><br>ហត្ថលេខា៖<br>Sign<br><br>
                </div>
                <div  style="float: left;width: 150px; margin-right: 10px; font-size: 12px;border: 1px solid black;​​" >
                    <p style="text-align: center"> គណនេយ្យករ<br>Accountant</p>
                    ឈ្មោះ​៖​<span></span><br>Name<br>ទូរស័ព្ទ៖<span></span><br>Phone<br>ហត្ថលេខា៖<br>Sign
                </div>
                <div  style="float: left;width: 150px; margin-right: 10px; font-size: 12px;border: 1px solid black;​​" >
                    <p style="text-align: center"> តំណាងផ្នែកលក់<br>Sales Rep</p>
                   ឈ្មោះ​៖<span></span>​<br>Name<br>ទូរស័ព្ទ៖<span></span><br>Phone<br>ហត្ថលេខា៖<br>Sign
                </div>
            </div>
        </div>
        <br>
    </div>
    <div style="width: 821px;margin: 20px">
        <a class="btn btn-warning no-print" href="<?= site_url('sales'); ?>" style="border-radius: 0">
            <i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
        </a>
    </div>
</div>

</body>
</html>