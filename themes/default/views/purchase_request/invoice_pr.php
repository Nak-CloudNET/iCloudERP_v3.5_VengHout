<?php //$this->erp->print_arrays($quote_items);?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("invoice_purchase_request") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
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
		hr{
			border-color: #333;
			width:100px;
			margin-top: 70px;
		}
        @media print,screen{
            body {
                width: 100%;
            }
        }
    </style>
</head>

<body>
<div class="print_rec" id="wrap" style="width: 90%; margin: 0 auto;">
    <div class="row">
        <div class="col-lg-12">
            <?php if ($logo) { ?>
				<div class="col-xs-3 col-ms-3 col-lg-3 text-center" style="margin-bottom:20px; margin-top: 20px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $billers->logo; ?>"
                         alt="">
                </div>
                <div class="col-xs-6 text-center">
                    <h1><?= $billers->company;?></h1>
                    <p><?= $billers->address ?></p>
                    <P>
                        <?php 
                            if($billers->phone){
                                echo lang("tell")."&nbsp;&nbsp;:&nbsp;&nbsp;".$billers->phone.",";
                            }
                            if ($billers->email) {
                                echo lang("email")."&nbsp;&nbsp;:&nbsp;&nbsp;".$billers->email;
                            }
                        ?>
                    </p>
					<h2><?= lang("purchase_request")?></h2>
                </div>
                <div class="col-xs-3">
                    
                </div>
            <?php } ?>
            <div class="clearfix"></div>
            <br>
            <div class="row padding10">
                <div class="col-xs-5" style="float: left;font-size:14px">
                    <h4><b><?= lang("request_by"); ?></b></h4>
                    <table>
                        <tr>
                            <td>
                                <p><?= lang("name");?></p>
                            </td>
                            <td>
                               <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$supplier->name."</b>"?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("address");?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$supplier->address."</b>" ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("phone");?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$supplier->phone."</b>" ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("email");?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$supplier->email."</b>" ?></p>
                            </td>
                        </tr>
                    </table>
                    <div class="clearfix"></div>
                </div>
                <div class="col-xs-2">
                    
                </div>
                <div class="col-xs-5"  style="float: right;font-size:14px">
                    <h4><b><?= lang("reference");?></b></h4>
                    <table>
                        <tr>
                            <td>
                                <p><?= lang("pr_no"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$warehouse->reference_no."</b>";?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("pr_date"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$warehouse->date."</b>";?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("delivery"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$warehouse->name."</b>";?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row padding10" style="display:none">
                <div class="col-xs-6" style="float: left;">
                    <span class="bold"><?= $Settings->site_name; ?></span><br>
                    <?= $warehouse->name ?>
                    <?php
                    echo $warehouse->address . "<br>";
                    echo ($warehouse->phone ? lang("tel") . ": " . $warehouse->phone . "<br>" : '') . ($warehouse->email ? lang("email") . ": " . $warehouse->email : '');
                    ?>
                    <div class="clearfix"></div>
                </div>
                <div class="col-xs-5" style="float: right;">
                    <div class="bold">
                        <?= lang("date"); ?>: <?= $this->erp->hrld($inv->date); ?><br>
                        <?= lang("ref"); ?>: <?= $inv->reference_no; ?>
                        <div class="clearfix"></div>
                        <?php $this->erp->qrcode('link', urlencode(site_url('sales/view/' . $inv->id)), 1); ?>
                        <img src="<?= base_url() ?>assets/uploads/qrcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>" class="pull-right"/>
                        <?php $br = $this->erp->save_barcode($inv->reference_no, 'code39', 50, false); ?>
                        <img src="<?= base_url() ?>assets/uploads/barcode<?= $this->session->userdata('user_id') ?>.png"
                             alt="<?= $inv->reference_no ?>" class="pull-left"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="clearfix"></div>
			<div><br/></div>
            <div class="-table-responsive">
                <table class="table table-bordered table-hover table-striped" style="width: 100%;">
                    <thead  style="font-size: 13px;">
						<tr>
							<th><?= lang("no"); ?></th>
                            <th><?= lang("description"); ?></th> 
                            <th><?= lang("unit"); ?></th> 
                            <th><?= lang("quantity"); ?></th>
                            <?php
                                if ($inv->status == 'partial') {
                                    echo '<th>'.lang("received").'</th>';
                                }
                            ?> 
                            <?php if($Owner || $Admin || $GP['purchase_request-cost']) {?>
                                <th><?= lang("unit_cost"); ?></th>
                            <?php } ?>
                            
                            <?php
                            if ($Settings->tax1) {
                                echo '<th>' . lang("tax") . '</th>';
                            }
                            if ($Settings->product_discount) {
                                echo '<th>' . lang("discount") . '</th>';
                            }
                            ?>
                            <th><?= lang("subtotal"); ?></th>
							<th><?= lang("remark"); ?></th>
						</tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                        <?php $r = 1;
                        $tax_summary = array();
                        foreach ($rows as $row):
                        ?>
                            <tr>
                                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                                <td style="vertical-align:middle;">
                                    <?= $row->product_name. " (" . $row->product_code . ")";?>
                                    <?= $row->details ? '<br>' . $row->details : ''; ?>
                                    <?= ($row->expiry && $row->expiry != '0000-00-00') ? '<br>' . $this->erp->hrsd($row->expiry) : ''; ?>
                                </td> 
                                <td style="vertical-align: middle; text-align: center;">
                                    <?php if($row->variant){ echo $row->variant;}else{echo $row->pro_unit;}?>
                                </td>
                                <td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
                                <?php
                                if ($inv->status == 'partial') {
                                    echo '<td style="text-align:center;vertical-align:middle;width:80px;">'.$this->erp->formatQuantity($row->quantity_received).'</td>';
                                }
                                ?>
                                <?php if($Owner || $Admin || $GP['purchase_request-cost']) {?>
                                    <td style="text-align:right; width:100px; vertical-align: middle;"><?= $this->erp->formatMoney($row->unit_cost); ?></td>
                                <?php } ?>
                                <?php
                                if ($Settings->tax1) {
                                    echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatMoney($row->item_tax) . '</td>';
                                }
                                if ($Settings->product_discount) {
                                    echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->discount != 0 ? '<small>(' . $row->discount . ')</small> ' : '') . $this->erp->formatMoney($row->item_discount) . '</td>';
                                }
                                ?>
                                <td style="text-align:right; width:120px; vertical-align: middle;"><?= $this->erp->formatMoney($row->subtotal); ?></td>
								<td style="text-align:right; width:120px; vertical-align: middle;">
								<?= $row->note; ?></td>
                            </tr>
                            <?php
                            $r++;
                        endforeach;
                        ?>
                    </tbody>
                    <tfoot style="font-size: 13px;">
                        <?php
                            $col = 4;
                            if($Owner || $Admin || $GP['purchase_request-cost']) {
                                $col = 4;
                            } else {
                                $col = 3;
                            }
                            
                            if ($Settings->product_discount) {
                                $col++;
                            }
                            if ($Settings->tax1) {
                                $col++;
                            }
                        ?>

                        <tr>
                            <td colspan="<?= $col; ?>">
                                <p style="text-align:left; font-weight:bold;"><?= lang("note"); ?>:<?= $this->erp->decode_html($inv->note); ?></p>
                            </td>
                            <td
                                style="text-align:right; padding-right:10px; font-weight:bold; vertical-align: middle;"><?= lang("total"); ?>
                                (<?= $default_currency->code; ?>)
                            </td>
                            <td style="text-align:right; padding-right:10px; font-weight:bold; vertical-align: middle;"><?= $this->erp->formatMoney($inv->grand_total); ?></td>
							 <td style="text-align:right; padding-right:10px; font-weight:bold; vertical-align: middle;"></td>
                        </tr>

                    </tfoot>
                </table>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="col-lg-2 col-xs-4 pull-left">
                        <p class="bold"><?= lang("request_by"); ?></p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p style="border-bottom: 1px solid #666;">&nbsp;</p>
                        <p><?= lang("name"); ?> :  ...................................</p>
                        <p><?= lang("date"); ?> :  ......../........./.................</p>
                    </div>
					<div class="col-lg-3"> 
                    </div>
					<div class="col-lg-2 col-xs-4 pull-left">
                        <p class="bold"><?= lang("checked_by"); ?></p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p style="border-bottom: 1px solid #666;">&nbsp;</p>
                        <p><?= lang("name"); ?> :  ...................................</p>
                        <p><?= lang("date"); ?> :  ......../........./.................</p>
                    </div>
                    <div class="col-lg-4"> 
                    </div>
                    <div class="col-lg-2 col-xs-4 pull-right">
                        <p class="bold"><?= lang("approve_by"); ?></p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p style="border-bottom: 1px solid #666;">&nbsp;</p>
                        <p><?= lang("name"); ?> :  ...................................</p>
                        <p><?= lang("date"); ?> :  ......../........./.................</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $(document).on('click', '#b-add-quote' ,function(event){
    event.preventDefault();
    __removeItem('slitems');
    window.location.href = "<?= site_url('purchases_request/add'); ?>";
  });
  $(document).on('click', '#b-view-pr' ,function(event){
    event.preventDefault();
    __removeItem('slitems');
    window.location.href = "<?= site_url('purchases_request/index'); ?>";
  });
});

</script>
</body>
</html>
