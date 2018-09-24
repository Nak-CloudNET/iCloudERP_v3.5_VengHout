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
       /* border: 1px solid black;*/
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
        .container{
            /*padding: 10px!important;*/
        }
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
        font-size: 17px;
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
span.com_name{
    font-size: 30px;
    font-weight: 700;
}
    span.inv_name{
        font-size: 25px;
        font-weight: 700;
    }
    .my_tb {
        height: 180px;
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
        <table width="100%" style="vertical-align: top;" class="my_tb"  >
            <tr>
                <td width="57%"  style="padding: 3px;">
                    <table width="100%" style="height: 100%" >
                        <tr>
                            <td>
                                <span class="com_name"><?= $invs->company ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="width: 100%;border-top: 1px solid black;padding-bottom: 10px" ></div>
                            </td>
                        </tr>
                       <tr>
                           <td style="vertical-align: bottom">
                               <div class=" " style="font-size: 12px; border: 1px solid black;border-radius: 5px !important; padding: 6px;width: 100%;height: 110px; ">
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
                           </td>

                        </tr>
                    </table>
                </td>
                <td class="" style="padding: 3px;">
                    <table  style="height: 100%" width="100%">
                        <tr>
                            <td class="text-center"><span class="title">ប័ណ្ណប្រគល់ទំនិញ</span></td>
                        </tr>
                        <tr>
                            <td  class="text-center" style=""><span class="inv_name " ><b>Delivery Note</b></span></td>
                        </tr>
                        <tr>
                            <td style="vertical-align: bottom">
                                <div class="" style="font-size: 12px; border: 1px solid black;border-radius: 5px !important; width:100%;padding: 6px;height: 100%">
                                    <table class="">
                                        <tr>
                                            <td style=""><?= lang('កាលបរិឆ្ឆេទ​/Date') ?></td>
                                            <td style="width: 4%;">:</td>
                                            <td style=""><?= $this->erp->hrsd($invs->date) ?></td>
                                        </tr>
                                        <?php

                                            echo "<tr>
                                                        <td>".lang('ល.រ​វិក័យប័ត្រ / Inv No')."</td>
                                                        <td>:</td>
                                                        <td> $invs->so_no</td>
                                                    </tr>";

                                        ?>
                                        <tr >
                                            <td><?= lang('លេខប័ណ្ណប្រគល់ទំនិញ​ / DN No') ?> </td>
                                            <td>:</td>
                                            <td ><?= $invs->reference_no ?></td>
                                        </tr>

                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <table class="table">
            <thead>
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
            <?php
            $sql=$this->db->query('select erp_users.first_name,erp_users.last_name from erp_users where id="'.$invs->created_by.'"')->result();
            foreach ($sql as $cname){
                $fn=$cname->first_name;
                $ln=$cname->last_name;
            }
            //          $this->erp->print_arrays($sql);
            ?>
            <tr class="border">
                <td colspan="8" style="font-size: 12px"><p>សម្គាល់ / Message :</p><p><?php echo $invs->invoice_footer ?></p></td>
            </tr>
            </tbody>
        </table>
        <div class="row" style="">
            <div class="col-sm-12">
                <div style="float: left; width: 146px; margin-right: 10px; font-size: 11px;border: 1px solid black;​​" >
                   <p style="text-align: center"> អ្នកទទួល​<br>Received by</p>&nbsp;ឈ្មោះ​៖<span></span>​<br>&nbsp;Name<br>&nbsp;ទូរស័ព្ទ៖<span></span><br>&nbsp;Phone<br>&nbsp;ហត្ថលេខា៖<br>&nbsp;Sign
                </div>
                <div  style=" float: left;width: 146px; margin-right: 10px; font-size: 11px;border: 1px solid black;​​" >
                    <p style="text-align: center"> ដឹកជញ្ជូនដោយ<br>Delivered by</p>
                    &nbsp;ឈ្មោះ​៖<span></span>​<br>&nbsp;Name<br>&nbsp;លេខឡាន៖<span></span><br>&nbsp;Truck No<br>&nbsp;ហត្ថលេខា៖<br>&nbsp;Sign
                </div>
                <div  style="float: left; width: 146px;margin-right: 10px;  font-size: 11px;border: 1px solid black;​​" >
                    <p style="text-align: center"> នាយឃ្លាំង<br>WH Controller</p>&nbsp;ឈ្មោះ​៖<span></span>​<br>&nbsp;Name<br><br>&nbsp;ហត្ថលេខា៖<br>&nbsp;Sign<br><br>
                </div>
                <div  style="float: left;width: 146px; margin-right: 10px; font-size: 11px;border: 1px solid black;​​" >
                    <p style="text-align: center"> គណនេយ្យករ<br>Accountant</p>
                    &nbsp;ឈ្មោះ​៖​<span></span><br>&nbsp;Name<br>&nbsp;ទូរស័ព្ទ៖<span></span><br>&nbsp;Phone<br>&nbsp;ហត្ថលេខា៖<br>&nbsp;Sign
                </div>
                <div  style="float: left;width: 146px; font-size: 11px;border: 1px solid black;​​" >
                    <p style="text-align: center"> តំណាងផ្នែកលក់<br>Sales Rep</p>
                    &nbsp;ឈ្មោះ​៖<span> <?= $invs->saleman_first.' '.$invs->saleman_last ?></span>​<br>&nbsp;Name<br>&nbsp;ទូរស័ព្ទ៖<span></span><br>&nbsp;Phone<br>&nbsp;ហត្ថលេខា៖<br>&nbsp;Sign
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