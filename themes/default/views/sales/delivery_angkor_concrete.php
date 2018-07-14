<?php //$this->erp->print_arrays($discount['discount']) ?>
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
    body {
        font-size: 14px !important;
    }

    .container {
        width: 29.7cm;
        margin: 20px auto;
        /*padding: 10px;*/
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    }
    @page{margin-left:50px;margin-right:50px;margin-bottom:20px;}}
    @media print {
        .customer_label {
            padding-left: 0 !important;
        }

        .invoice_label {
            padding-left: 0 !important;
        }
        #footer  hr p {
            font-size: 1px !important;
            position:absolute !important;
            bottom:0 !important;
            /*margin-top: -30px !important;*/
        }

        thead{
            display:table-header-group;
        }
        tabel {
            page-break-after: always;
        }

        .row table tr td {
            font-size: 10px !important;
        }
        /*.row table tr th {
            font-size: 8px !important;
        }*/
        .table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th {
            background-color: #444 !important;
            color: #FFF !important;
        }
        .row .col-xs-7 table tr td, .col-sm-5 table tr td{
            font-size: 10px !important;
        }
        #note td{
            max-width: 95% !important;
            margin: 0 auto !important;
            border-radius: 5px 5px 5px 5px !important;
            margin-left: 26px !important;
        }
    }
    .thead th {
        text-align: center !important;
    }

    #titems tr > td {
        border-top: none !important;
        border-bottom: none !important;
    }
    #titems tr > td {
        padding: 5px;
    }

    .table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
        border: 1px solid #000 !important;
    }


    .company_addr h3:first-child {
        font-family: Khmer OS Muol !important;
    //padding-left: 12% !important;
    }

    .company_addr h3:nth-child(2) {
        margin-top:-2px !important;
    //padding-left: 130px !important;
        font-size: 26px !important;
        font-weight: bold;
    }

    .company_addr h3:last-child {
        margin-top:-2px !important;
    //padding-left: 100px !important;
    }

    .company_addr p {
        font-size: 12px !important;
        margin-top:-10px !important;
        padding-left: 20px !important;
    }

    .inv h4:first-child {
        font-family: Khmer OS Muol !important;
        font-size: 14px !important;
    }

    .inv h4:last-child {
        margin-top:-5px !important;
        font-size: 14px !important;
    }

    button {
        border-radius: 0 !important;
    }

</style>
<body>
<br>
<div class="container" style="width: 1000px;margin: 0 auto;">
    <div class="col-xs-12" style="width: 973px;">
        <div class="col-sm-12 col-xs-12">
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;margin-top: 10px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
        </div>
        <br>
        <div class="row" style="margin-top: 20px !important;">
            <div class="col-sm-2 col-xs-2">
                <?php if(!empty($biller->logo)) { ?>
                    <img src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" style="width: 130px;" />
                <?php } ?>

            </div>

            <div class="col-sm-5 col-xs-5 company_addr" style="font-weight: bold;">
                <p style="font-size: 17px !important;"><?=$biller->name?></p>
                <p style="font-size: 17px !important;"><?=$biller->company?></p>
            </div>
            <div class="col-sm-5 col-xs-5 text-right">
                <p style="text-decoration: underline;">DELIVERY PAPER</p>
                <p>AC&nbsp;:&nbsp;<b><?=$inv->do_reference_no?></b></p>
            </div>
            <?php //$this->erp->print_arrays($inv);?>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-12 inv">
                <div class="col-sm-7 col-xs-7 inv">
                    <p>In case the sale agreement has a compressive strength guarantee :</p>
                    <p> - Test the compressive strength shall be conducted in cubical sample.</p>
                    <p> - The specified slump of each concrete class shall be tested when the concrete truck arrives at the customer's job site.</p>
                    <p> - Unless otherwise specified. the concrete in the truck mixer shall be completely used within 180 minutes after the concrete truck leaves the batching plant.</p>
                </div>
                <div class="col-sm-5 col-xs-5 inv">
                    <b style="text-decoration: underline">Warning </b><span>- Additional water mixed into the concrete delivered at the job site may reduce the compressive strength originally specified by customer. </span>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12 col-xs-12 inv">
                <table width="100%">
                    <tr>
                        <td style="width: 60%;border-top: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;padding: 15px;line-height: 25px;">

                            <table style="font-size:11px;">
                                <tr>
                                    <td style="width: 20%">Supplied by</td>
                                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td><?= $biller->company ?></td>
                                </tr>

                                <tr>
                                    <td> Address</td>
                                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td><?= $biller->address ?></td>
                                </tr>

                                <tr>
                                    <td>Tel</td>
                                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td><?= $biller->phone ?></td>
                                </tr>

                                <tr>
                                    <td>Email</td>
                                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td><?= $biller->email ?></td>
                                </tr>

                            </table>
                        </td>
                        <td style="text-align:center;width: 40%;border-top: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;">
                            <br><br><br><br>Remark(s) approved by <br> Authorized Agent
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-12 inv" style="">
                <table width="100%">
                    <tr>
                        <td style="width: 50%;height:40px;border: 1px solid #000;">
                            Delivery Code : <b><?=$customer->address?></b>
                        </td>
                        <td style="width: 50%;height:40px;border: 1px solid #000;">
                            Issued date : <b><?=$this->erp->hrsd($inv->date);?></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50%;height:40px;border-top: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;">
                            Customer Name : <b><?=$customer->names?></b>
                        </td>
                        <td style="width: 50%;height:40px;border-top: 1px solid #000;border-left: 1px solid #000;border-right: 1px solid #000;">
                            Job site : <b><?=$customer->phone?></b>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <table class="table table-bordered" style="width: 100%; margin-bottom: 0 !important">
                    <thead>
                    <tr>
                        <th style="width: 16.66%"><?= strtoupper(lang('Produce Name')) ?></th>
                        <th style="width: 16.66%"><?= strtoupper(lang('Produce Code')) ?></th>
                        <th style="width: 16.69%"><?= strtoupper(lang('qty')) ?></th>
                        <th colspan="2" style="width: 100%;height:33px;border-top: 1px solid #000;border-bottom: 1px solid #000;border-right: 1px solid #000;">Additional water content at the job site to ajust slump
                            <br>from....................c.m to....................c.m at....................a.m/p.m.</th>
                    </tr>
                    </thead>
                   <tbody>
                   <?php
                   $this->db->select('erp_deliveries.*,
                                        erp_delivery_items.product_name, erp_products.id as product_id, erp_products.code,erp_delivery_items.piece,erp_delivery_items.wpiece,delivery_items.option_id,
                                        COALESCE(SUM(erp_delivery_items.quantity_received)) as quantity_received,
                                        erp_companies.name,
                                        product_variants.name as variant,
                                        units.name as unit,
                                        product_variants.name as variant
                                    ');
                   $this->db->from('deliveries');
                   $this->db->join('erp_companies','deliveries.delivery_by = erp_companies.id','left');
                   $this->db->join('delivery_items','delivery_items.delivery_id = deliveries.id', 'left');
                   $this->db->join('erp_products','delivery_items.product_id = erp_products.id', 'left');
                   $this->db->join('erp_product_variants','delivery_items.option_id = erp_product_variants.id', 'left');
                   $this->db->join('units','erp_products.unit = units.id', 'left');
                   $this->db->join('erp_categories','erp_products.category_id = erp_categories.id', 'left');
                   $this->db->join('erp_categories_note','erp_categories.categories_note_id = erp_categories_note.id', 'left');
                   $this->db->where('erp_deliveries.id', $inv->id);
                   $this->db->where_in('erp_categories_note.id',$cnote->id);
                   $this->db->group_by('delivery_items.id');

                   $query = $this->db->get();
                   $no = 1;$r=1;
                   $total = 0;
                   $num_rows =0;
                   $row_span = 0;
                   $rows = $query->result();
                   foreach (($query->result()) as $row) :
                   $free = lang('free');

                   ?>

                   <tr>
                       <td style="vertical-align:middle"><?=$row->product_name;?></td>
                       <td style="vertical-align:middle"><?=$row->code;?></td>
                       <td style="vertical-align:middle"><?=$this->erp->formatQuantity($row->quantity);?></td>
                       <?php if ($num_rows == 0) { ?>
                           <td rowspan="<?= sizeof($rows); ?>" style="width: 20%; vertical-align:bottom">
                               <p class="text-center" style="margin-bottom: 0">Deliverer</p>
                           </td>
                           <td rowspan="<?= sizeof($rows); ?>" style="height: 150px">
                               <div class="text-center">The produce have already been delivered<br>is good condition and colect quality</div>
                               <div style="position: absolute; bottom: 0; padding-left: 80px; padding-bottom: 10px;">Riciplent's Name</></div>
                           </td>
                       <?php } ?>
                   </tr>

                       <?php
                       $no++;
                       $num_rows++;
                   endforeach;
                   ?>
                   </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-12 inv" style="margin-top: 20px;">
                <table width="100%">
                    <tr>
                        <td style="height:40px;border: 1px solid #000;">
                            Customer's Suggestion
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-12  text-center">
                <table width="100%" style="border-left: 1px solid #000;border-bottom: 1px solid #000;border-right: 1px solid #000;">
                    <tr>
                        <td style="width: 30%; height:40px;">
                            Truck No : <b><?=$inv->id?></b>
                        </td>
                        <td style="width: 35%;height:40px;">
                            Delivery-man : <b><?=$inv->id?></b>
                        </td>
                        <td style="width: 35%;height:40px;">
                            Departure Time : .......................................
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-xs-12 inv text-right">
                <table width="100%" style="border-left: 1px solid #000;border-bottom: 1px solid #000;border-right: 1px solid #000;">
                    <tr>
                        <td style="height:40px;padding-right: 30px;">
                            Source Code : .......................................
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <br>
        <br>
        <br>
    </div>

    <div style="width: 821px;margin: 0 auto;">
        <a class="btn btn-warning no-print" href="<?= site_url('sales'); ?>">
            <i class="fa fa-hand-o-left" aria-hidden="true"></i>&nbsp;<?= lang("back"); ?>
        </a>
    </div>
    <br>
</body>
</html>