<?php
	$note_arr = explode('/',$biller->phone);
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
		width:17cm;
		height:auto;
		margin: 20px auto;
		box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
	}

    .table-bordered {
        margin-bottom: 0 !important;
    }

    .table-bordered tr > td {
        width: 50% !important;
    }

    .table th {
        text-align: center;
    }
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border: 1px solid #000 !important;
	}

	@media print {
        .container {
            width: 95% !important;
            margin: 0 auto !important;
        }

        .table-bordered td, .table th, td {
            font-size: 10px !important;
        }

        .table th:nth-child(3) {
            width: 31% !important;
        }
	}

</style>
<body>
    <div class="container">
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
				<center><h3 style="font-weight:bold !important;font-family:Time New Roman !important;margin-bottom:20px !important;">ប័ណ្ណឥណទាន CREDIT MEMMO</h3></center>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
                <table class="table table-bordered">
					<tr>
                        <td>លេខកូដ/Cust.ID &nbsp;&nbsp;:&nbsp;&nbsp;<?= $biller->code ?></td>
                        <td>អតិថិជនឈ្មោះ/Cust.Name &nbsp;&nbsp;:&nbsp;&nbsp;<?= $customer->names ?></td>
					</tr>
					<tr>
                        <td>អាស័យដ្ឋាន/Address &nbsp;&nbsp;:&nbsp;&nbsp;<?= $customer->address; ?></td>
                        <td>ភ្ជាប់ជួន/Att &nbsp;&nbsp;:&nbsp;&nbsp;</td>
					</tr>
					<tr>
                        <td></td>
                        <td></td>
					</tr>
					<tr>
                        <td>ទូរស័ព្ទ/Tel &nbsp;&nbsp;:&nbsp;&nbsp;<?= $biller->phone; ?></td>
                        <td>ទូរសារ/Fax &nbsp;&nbsp;:&nbsp;&nbsp;<?= $biller->email; ?></td>
					</tr>
					<tr>
                        <td style="border-bottom: 1px solid #FFF !important">លេខវិក័យប័ត្រយោង/ Ref.Inv number &nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<?= $inv->s_no; ?></td>
                        <td style="border-bottom: 1px solid #FFF !important">លេខវិក័យប័ត្រឥណទាន/No.Credit Memo &nbsp;&nbsp;:&nbsp;&nbsp;<?= $inv->reference_no; ?></td>
					</tr>
				</table>
                <?php
                $totaldis = 0;
                $totaltax = 0;
                foreach ($rows as $row) {
                    $totaldis += $row->discount;
                    $totaltax += $row->item_tax;

                }
                ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th style="width: 5%"> ល.រ <br><?= lang('NO') ?></th>
                        <th style="width: 15%">កូដ <br><?= lang('Code') ?></th>
                        <th style="width: 35%">បរិយាយ​ <br><?= lang('Description') ?></th>
                        <th>ឯកតា <br><?= lang(' Unit') ?></th>
                        <th>បរិមាណ <br><?= lang('QTY') ?></th>
                        <th>តំលៃ <br><?= lang('Price') ?></th>
                        <?php if ($totaldis != 0) { ?>
                            <th>បញ្ចុះតម្លៃ <br><?= lang('Discount') ?></th>
                        <?php } ?>
                        <?php if ($totaltax != 0) { ?>
                            <th>ពន្ធ <br><?= lang('Tax') ?></th>
                        <?php } ?>
                        <th>សរុប <br><?= lang('Total') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
						$i=1;$erow=1;
						if(is_array($rows)){
							$total = 0;
							foreach ($rows as $row):
							$free = lang('free');
							$product_unit = '';
							
							
							if($row->variant){
								$product_unit = $row->variant;
							}else{
								$product_unit = $row->uname;
							}
							$product_name_setting;
							if($Settings->show_code == 0) {
								$product_name_setting = $row->product_name ;
							}else {
								if($Settings->separate_code == 0) {
									$product_name_setting = $row->product_name . " (" . $row->product_code . ")";
								}else {
									$product_name_setting = $row->product_name;
								}
							}

							if($row->option_id){
										
							   $getvar = $this->sales_model->getAllProductVarain($row->product_id);
									 foreach($getvar as $varian){
										 if($varian->product_id){
											 if($varian->qty_unit == 0){
												$var = $this->sales_model->getVarain($row->option_id);
												$str_unit = $var->name;
											 }else{
												$var = $this->sales_model->getMaxqtyByProID($row->product_id);
												$var1 = $this->sales_model->getVarain($var->product_id);									
												$str_unit = $var1->name;
											}
										 }else{
											$str_unit = $row->uname;
										}
									}
							}else{
								$str_unit = $row->uname;
							}

					?>

                                <tr>
                                    <td class="text-center"><?= $i; ?></td>
                                    <td><?= $row->product_code ?></td>
                                    <td>
								<?= $product_name_setting ?>
								<?= $row->details ? '<br>' . $row->details : ''; ?>
								<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if ($row->piece != 0) {
                                            echo $str_unit;
                                        } else {
                                            echo $row->product_unit;
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if ($row->piece != 0) {
                                            echo $row->piece;
                                        } else {
                                            echo $this->erp->formatQuantity($row->quantity);
                                        }
                                        ?>
                                    </td>
                                    <td class="text-right"><?= $this->erp->formatMoney($row->unit_price); ?></td>

                                    <?php if ($totaldis != 0) { ?>
                                        <td style="text-align:center; vertical-align:middle;">
                                            <?= $row->discount ?>
                                        </td>
                                    <?php } ?>
                                    <?php if ($totaltax != 0) { ?>
                                        <td style="text-align:center; vertical-align:middle;">
                                            <?= $row->item_tax ?>
                                        </td>
                                    <?php } ?>

                                    <td class="text-right">
                                        <?= $row->subtotal != 0 ? $this->erp->formatMoney($row->subtotal) : $free;
										$total += $row->subtotal;
										?>
                                    </td>
                                </tr>

                                <?php
                                $i++;
                                $erow++;
						endforeach;
					}
						if($erow<10){
							$k=10 - $erow;
							for($j=1;$j<=$k;$j++){
                                if($totaldis != 0) {
                                    echo  '<tr style="border: #000 1px solid;">
										<td height="34px" class="text-center" >'.$i.'</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>';
                                }else {
                                    echo  '<tr style="border: #000 1px solid;">
										<td height="34px" class="text-center" >'.$i.'</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>';
                                }

								$i++;
							}
						}
					?>

                    <?php

                    $colspan = 0;
                    $rowspan = 1;
                    if ($totaldis != 0) {
                        $colspan = 2;
                    }
                    if ($totaldis != 0 && $totaltax != 0) {
                        $colspan = 3;
                    }


                    if ($inv->grand_total != $inv->total) {
                        $rowspan++;
                    }
                    if ($inv->order_discount != 0) {
                        $rowspan++;
                    }
                    if ($inv->shipping != 0) {
                        $rowspan++;
                    }
                    if ($inv->order_tax != 0) {
                        $rowspan++;
                    }
                    if ($inv->paid != 0) {
                        $rowspan += 2;
                    }

					?>
					<tr style="border: #000 1px solid;">
                        <td colspan="5" rowspan="<?= $rowspan ?>">
							<p style="text-align:left; font-weight: bold;border-left:1px solid white!important;border-bottom:"></p>
							<p><?= $inv->invoice_footer ?></p>
						</td>
						<td colspan="<?= $colspan ?>" style="text-align:left; "><?= lang('total')?></td>
						<td style="text-align:left; ">
                            <?= $this->erp->formatMoney($total); ?>
						</td>
					</tr>
                    <?php if ($inv->order_discount != 0) { ?>
					<tr style="border: #000 1px solid;">
						<td colspan="<?= $colspan ?>" style="text-align:left; vertical-align:middle;"><?=lang('order_discount')?></td>
						<td style="text-align:left; vertical-align:middle;">
                            <?= $this->erp->formatMoney($inv->order_discount); ?>
						</td>
					</tr>
                    <?php } ?>
                    <?php if ($inv->shipping != 0) { ?>
					<tr style="border: #000 1px solid;">
                        <td colspan="<?= $colspan ?>"
                            style="text-align:left; vertical-align:middle;"><?= 'ដឹកជញ្ជូន / ' . lang('shipping') ?></td>
						<td style="text-align:left; vertical-align:middle;">
                            <?= $this->erp->formatMoney($inv->shipping); ?>
						</td>
					</tr>
                    <?php } ?>
					<?php if($inv->order_tax !=0){?>
					<tr style="border: #000 1px solid;">
                        <td colspan="<?= $colspan ?>"
                            style="text-align:left; vertical-align:middle;"><?= 'ពន្ធកម្ម៉ុង / ' . lang('order_tax') ?></td>
						<td style="text-align:left; vertical-align:middle;">
                            <?= $this->erp->formatMoney($inv->order_tax); ?>
						</td>
					</tr>
                    <?php } ?>
                    <?php if ($inv->grand_total != $inv->total) { ?>
					<tr style="border: #000 1px solid;">
                        <td colspan="<?= $colspan ?>"
                            style="text-align:left; vertical-align:middle;"><?= lang('Total_Amount') ?></td>
						<td style="text-align:left; vertical-align:middle;">
                            <?= $this->erp->formatMoney($inv->grand_total); ?>
						</td>
					</tr>
                    <?php } ?>
					<?php if($inv->paid !=0){?>
					<tr style="border: #000 1px solid;">
                        <td colspan="<?= $colspan ?>"
                            style="text-align:left; vertical-align:middle;"><?= 'បង់ប្រាក់ / ' . lang('paid') ?></td>
						<td style="text-align:left; vertical-align:middle;">
                            <?= $this->erp->formatMoney($inv->paid); ?>
						</td>
					</tr>
					<tr style="border: #000 1px solid;">
                        <td colspan="<?= $colspan ?>"
                            style="text-align:left; vertical-align:middle;"><?= 'នៅខ្វះ / ' . lang('balance') ?></td>
						<td style="text-align:left; vertical-align:middle;">
                            <?= $this->erp->formatMoney($inv->grand_total - $inv->paid); ?>
						</td>
					</tr>
                    <?php } ?>
                        <tr style="font-size: 12px;">
                            <td colspan="3" style="border: #000 solid 1px;height: 25px;text-align: center;">រៀបចំដោយ/Prepared By </td>
                            <td colspan="2" style="width: 16%; border: #000 solid 1px;height: 25px;"></td>
                            <td colspan="4"
                                style="width:37.5%; border-left: #000 solid 1px;border-right: #000 solid 1px;border-bottom: #000 solid 1px;height: 25px;">
                                ទទូលដោយ​/Received By
                            </td>
                        </tr>
                        <tr style="font-size: 12px;">
                            <td colspan="3" style="border: #000 solid 1px;height: 25px;"></td>
                            <td colspan="2" style="width: 16%;border: #000 solid 1px;height: 50px;"></td>
                            <td colspan="4" style="border: #000 solid 1px;height: 25px;"></td>
                        </tr>
                        <tr style="font-size: 12px;">
                            <td colspan="3" style="border: #000 solid 1px;height: 25px;text-align: center;">អ្នកផ្គត់ផ្គង់ /Supplier </td>
                            <td colspan="2" style="width: 16%;border: #000 solid 1px;height: 25px;"></td>
                            <td colspan="4" style="border: #000 solid 1px;height: 25px;">អតិថិជន/ Customer</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
	</div>
	
</body>
</html>