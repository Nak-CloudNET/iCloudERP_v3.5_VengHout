<?php
	//$this->erp->print_arrays($invs);
	$note_arr = explode('/',$biller->phone);
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
		width:17cm;
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
				<center><h3 style="font-weight:bold !important;font-family:Time New Roman !important;margin-bottom:20px !important;">វិក្កយបត្រ<br>INVOICE</h3></center>
			</div>
		</div>

		<div class="row">
            <?php
            $totaldis=0;
            foreach ($rows as $row){
                $totaldis+=$row->discount;

            }?>
			<div class="col-lg-12 col-sm-12 col-xs-12">
					<table class="table table-bordered table-hover" border="1">
				<thead>
                    <tr >
                        <th style="line-height: 5px;font-size: 11px;padding-bottom: 5px;">
                            <p>ឈ្មោះអ្នកទិញ </p>
                            <p>Buyer Name &nbsp;&nbsp;:</p>
                        </th>
                        <th>
                            <p><?=$customer->name ?></p>
                        </th>
                        <th style="line-height: 5px;font-size: 11px;border-bottom: #FFFFFF solid 1px !important;padding-bottom: 5px;padding-left: 70px;border-top: #FFFFFF solid 1px !important;border-right: #FFFFFF solid 1px !important;" colspan="2">
                            <p>លេខវិក័យបត្រ</p>
                            <p>Invoice No &nbsp;&nbsp;:&nbsp;&nbsp;</p>
                        </th>
                        <th style="border-top: #FFFFFF solid 1px !important;border-right: #FFFFFF solid 1px !important;border-bottom: #FFFFFF solid 1px !important;" colspan="2">
                            <p><?=$invs->reference_no ?></p>
                        </th>
                    </tr>
                    <tr >
                        <th style="line-height: 5px;font-size: 11px;padding-bottom: 5px;">
                            <p>អាស័យដ្ឋាន </p>
                            <p>Address &nbsp;&nbsp;:</p>
                        </th>
                        <th>
                            <p><?=$customer->address ?></p>
                        </th>
                        <th style="line-height: 5px;font-size: 11px;border-bottom: #FFFFFF solid 1px !important;padding-bottom: 5px;padding-left: 70px;border-top: #FFFFFF solid 1px !important;border-right: #FFFFFF solid 1px !important;" colspan="2">

                        </th>
                        <th style="border-top: #FFFFFF solid 1px !important;border-bottom: #FFFFFF solid 1px !important;border-right: #FFFFFF solid 1px !important;" colspan="2">

                        </th>
                    </tr>
                    <tr >
                        <th style="line-height: 5px;font-size: 11px;padding-bottom: 5px;">
                            <p>ទូរស័ព្ទលេខ </p>
                            <p>Telephone &nbsp;&nbsp;:</p>
                        </th>
                        <th>
                            <p><?=$customer->phone ?></p>
                        </th>
                        <th style="line-height: 5px;font-size: 11px;padding-bottom: 5px;padding-left: 70px;border-top: #FFFFFF solid 1px !important;border-right: #FFFFFF solid 1px !important;" colspan="2">
                            <p>ថ្ងៃចេញវិក័យបត្រ</p>
                            <p>Date &nbsp;&nbsp;:&nbsp;&nbsp;</p>
                        </th>
                        <th style="border-top: #FFFFFF solid 1px !important;border-right: #FFFFFF solid 1px !important;" colspan="2">
                            <p><?=$this->erp->hrsd($invs->date); ?></p>
                        </th>
                    </tr>
					<tr>
						<th style="font-size:13px !important;width: 17%;" class="text-center"> ល.រ <br><?=lang('NO')?></th>
						<th style="width:100px;font-size:13px !important;"class="text-center">បរិយាយមុខទំនិញ​ <br><?=lang('Descript')?></th>
						<th style="font-size:13px !important;width: 150px;"class="text-center">ឯកតា <br><?=lang(' Unit')?></th>
						<th style="font-size:13px !important;"class="text-center">បរិមាណ <br><?=lang('QTY')?></th>
						<th style="width:150px;font-size:13px !important;"class="text-center"> ថ្លៃ១ឯកតា <br><?=lang('Price')?></th>
						<th style="font-size:13px !important;" class="text-center"> តម្លៃសរុប <br><?=lang('Total')?></th>
					</tr>
				</thead>
				<tbody>
					<?php //for($i=0; $i<20; $i++){
						$i=1;$erow=1;
						if(is_array($rows)){
							$total = 0;
							foreach ($rows as $row):
							//$this->erp->print_arrays($row);
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
					<tr style="border: #000 1px solid;">
						<td style=" text-align:center; vertical-align:middle;"><?=$i;?></td>
						<td style="text-align:left; vertical-align:middle;width:200px;">
								<?= $product_name_setting ?>
								<?= $row->details ? '<br>' . $row->details : ''; ?>
								<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
						</td>
						<td style="text-align:center; vertical-align:middle;">
							<?php
                            echo $product_unit
							?>
						</td>
						<td style=" text-align:center; vertical-align:middle;">
							<?php
								if($row->piece != 0){
									echo $row->piece;
								}else{
									echo $this->erp->formatQuantity($row->quantity);}
							?>
						</td>
						
						<td style="text-align:center; vertical-align:middle;">
							
							<div class="col-xs-6 text-right">
								<?=$this->erp->formatMoney($row->unit_price); ?>
							</div>
						</td>
                        <?php if($totaldis !=0){ ?>
                            <td style="text-align:center; vertical-align:middle;">
                                <div class="col-xs-3"></div>

                                <div class="col-xs-7 text-left">
                                    <?=$row->discount ? $row->discount:'0';?>
                                </div>
                            </td>
                        <?php } ?>

						<td style=" vertical-align:middle;">
							<div class="col-xs-3 text-left">
								<?php
									if ($row->subtotal!=0) {
										echo '';
									} else {
										echo '';
									}
								?>
							</div>
							<div class="col-xs-7 text-left">
								<?= $row->subtotal!=0 ? $this->erp->formatMoney($row->subtotal):$free; 
										$total += $row->subtotal;
										?>
							</div>
							
						</td>
					</tr>
					<?php 
						$i++;$erow++;
						endforeach;
					}
						if($erow<10){
							$k=10 - $erow;
							for($j=1;$j<=$k;$j++){
                                if($totaldis != 0) {
                                    echo  '<tr style="border: #000 1px solid;">
										<td height="34px" class="text-center" >'.$i.'</td>
										<td style="width:34px;"></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>';
                                }else {
                                    echo  '<tr style="border: #000 1px solid;">
										<td height="34px" class="text-center" >'.$i.'</td>
										<td style="width:34px;"></td>
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
					<tr style="border: #000 1px solid;">
						<td colspan="3" style="text-align:left; "></td>
						<td colspan="2" style="text-align:left; "><?= lang('total')?></td>
						<td style="text-align:left; ">
							<div class="col-xs-3 text-left"></div>
							<div class="col-xs-7 text-left">
								<?=$this->erp->formatMoney($total);?>
							</div>
						</td>
					</tr>
					<tr style="border: #000 1px solid;">
						<!-- <td colspan="6" style="text-align:center; vertical-align:middle;"></td> -->
						<td colspan="3"style="text-align:left; vertical-align:middle;"></td>
						<td colspan="2"style="text-align:left; vertical-align:middle;"><?='ពន្ធកម្ម៉ុង / '.lang('order_tax')?></td>
						<td style="text-align:left; vertical-align:middle;">
							<div class="col-xs-3 text-left"></div>
							<div class="col-xs-7 text-left">
								<?=$this->erp->formatMoney($invs->order_tax);?>
							</div>
						</td>
					</tr>
					<tr style="border: #000 1px solid;">
						
						<td colspan="3"style="text-align:left; vertical-align:middle;"></td>
						<td colspan="2"style="text-align:left; vertical-align:middle;"><?=lang('Total_Amount')?></td>
						<td style="text-align:left; vertical-align:middle;">
							<div class="col-xs-3 text-left"></div>
							<div class="col-xs-7 text-left">
								<?=$this->erp->formatMoney($invs->grand_total);?>
							</div>
						</td>
					</tr>
                        <tr style="font-size: 12px;">
                            <td colspan="3" style="border: #000 solid 1px;height: 25px;text-align: center;">ទឹកប្រាក់ជាអក្សរ/Amount in letter </td>
                            <td colspan="3" style="width:37.5%; border-left: #000 solid 1px;border-right: #000 solid 1px;border-bottom: #000 solid 1px;height: 25px;"><?=$this->erp->convert_number_to_words($total)?></td>
                        </tr>




				</tbody>
			</table>


                <div id="footer" class="row">
                    <div class="col-lg-12 col-sm-12 col-xs-12" style="font-size: 11px !important;line-height: 15px;">
                            <p>លក្ខខណ្ឌក្នុងការទូទាត់ប្រាក់ ​: បើទូទាត់ជាសែកសូមដាក់ឈ្មោះ   <span>..........................................................</span></p>
                            <p>Trem of payment : if pay by cheque ,please pay to name <span>..........................................................</span></p>
                    </div>

                </div>

                <br>
                <br>
                <br>
            </div>
            <div id="footer" class="row">
                <div class="col-lg-6 col-sm-6 col-xs-6">
                    <center>
                        <p>..................................................</p>
                        <p style="font-size: 12px !important;">ឈ្មោះ និង ហត្ថលេខាអ្នកលក់</p>
                    </center>
                </div>
                <div class="col-lg-6 col-sm-6 col-xs-6">
                    <center>
                        <p>..................................................</p>
                        <p style="font-size: 12px !important;">ឈ្មោះ និង​ ហត្ថលេខាអ្នកទិញ</p>
                    </center>
                </div>

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