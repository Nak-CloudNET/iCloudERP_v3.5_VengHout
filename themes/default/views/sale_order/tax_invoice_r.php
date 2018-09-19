<link href="<?= $assets ?>styles/helpers/bootstrap.min.css" rel="stylesheet"/>
<style>
	.btn {
		padding: 0;
		border-radius: 0;
		color: #000;
		width: 22px;
		height: 25px;
		background-color: transparent;
		border: 1px solid #000;
	}

	@media print {
		.container {
			width: 98% !important;
			margin: 10px auto !important;
			padding: 0;
		}
		#footer {
			position: absolute !important;
			bottom: 10px !important;
			width: 100%;
		}
		#footer .col-sm-6 {			width: 50% !important;
		}
	}
</style>
<?php
	$address = '';
	$address.=$biller->address;
	$address.=($biller->city != '')? ', '.$biller->city : '';
	$address.=($biller->postal_code != '')? ', '.$biller->postal_code : '';
	$address.=($biller->state != '')? ', '.$biller->state : '';
	$address.=($biller->country != '')? ', '.$biller->country : '';
//	$this->erp->print_arrays($rows);
?>
<div class="container">
<center>
<br>
    <table width="100%">
        <tr>
            <td>
                <h2 style="font-family: Khmer OS Muol Light; text-align: center;"><?= $biller->company_kh ?></h2>
                <h4 style="text-align: center; font-weight: bold"><?= $biller->company ?></h4>
            </td>
        </tr>
    </table>
	<table class="table-responsive" width="1024px" border="0" cellspacing="0" style="margin:auto;">
		<tr>

			<td style="width: 50px !important"></td>
			<td>


			</td>
		</tr>
	</table>
    <table >

        <tr>
            <td>
                <div style="font-family:'Khmer OS'; font-size:12px;"><?= $this->lang->line("លេខអត្តសញ្ញាណកម្ម អតប​ (VATTIN)"); ?></div>
            </td>
            <td>
                <div style="font-family:'Arial'; font-size:12px;">
                    <?php for($i=strlen($biller->vat_no);$i>=1 ; $i--) { ?>
                        <?php
                        $sign ="";
                        if ($i == 10) {
                            $sign = "-";
                        }
                        ?>
                        <button type="button" class="btn"><?= $biller->vat_no[strlen($biller->vat_no)-$i]?></button> <?= $sign ?>
                    <?php } ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="font-family:'Khmer OS'; font-size:12px;"><?= $this->lang->line("អាស័យដ្ឋាន"); ?></div>
            </td>
            <td>
                <div style="font-family:'Khmer OS'; font-size:12px;">: <?= $biller->cf4; ?></div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="font-family:'Arial'; font-size:12px;"><?= $this->lang->line("Address"); ?></div>
            </td>
            <td>
                <div style="font-family:'Arial'; font-size:12px;">: <?= $biller->address; ?></div>
            </td>
        </tr>
        <tr>
            <td>
                <div style="font-family:'Arial'; font-size:12px;"><?= lang("tel"); ?></div>
            </td>
            <td style="font-family:'Arial'; font-size:12px;">: <?= $biller->phone;?></td>
        </tr>
        <tr>
            <td >
                <div style="font-family:'Arial'; font-size:12px;"><?= lang("email"); ?></div>
            </td>
            <td style="font-family:'Arial'; font-size:12px;">: <?= $biller->email; ?></td>
        </tr>
    </table>
	<table class="table-responsive" width="1024px" border="0" cellspacing="0" style="margin:auto;">
		<tr>
			<td style="height:5px;" colspan="5"> <hr width="100%" size="2px" /> </td>
		</tr>
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<div style="font-family:'Khmer OS Muol Light'; font-size:20px;"><?= lang("វិក្កយបត្រអាករ"); ?></div>
				<div style="font-family:'Arial'; font-size:18px; font-weight: bold"><?= lang("tax_invoice"); ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="5" width="65%" align="center" style="padding-top:5px;">
				<div class="row">
                    <div class="col-sm-7 col-xs-7" style="">
                        <div class="row">
                            <div class="col-sm-6 col-xs-6" style="font-size:14px; text-align: left">ឈ្មោះ​ក្រុមហ៊ុន /  Company</div>
                            <div class="col-sm-6 col-xs-6 text-left">
                                <?= $customer->company; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-xs-6" style="font-size:14px; text-align: left"><?= lang('ជូនចំពោះ​ / Attn');?></div>
                            <div class="col-sm-6 col-xs-6 text-left">
                                <?= $customer->name ? $customer->name : $customer->company; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-xs-6" style="font-size:14px; text-align: left"><?= lang('ទូរស័ព្ទ​លេខ / Tel');?></div>
                            <div class="col-sm-6 col-xs-6 text-left" >
                                <?= $customer->phone; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-xs-6" style="font-size:14px; text-align: left"><?= lang('អាសយដ្ឋាន');?></div>
                            <div class="col-sm-6 col-xs-6 text-left" >
                                <?= $customer->address; ?>
                            </div>
                        </div>
                        <div class="row" style="">
                            <div class="col-sm-6 col-xs-6 text-left ">
                                <?= lang('លេខអត្តសញ្ញាណកម្ម អតប  (VATTIN)');?>
                            </div>
                            <div class="col-sm-6 col-xs-6 text-left " style="">
                                <?php for($i=strlen($customer->vat_no);$i>=1 ; $i--) { ?>
                                    <?php
                                    $sign ="";
                                    if ($i == 10) {
                                        $sign = "-";
                                    }
                                    ?>
                                    <button type="button" class="btn"><?= $customer->vat_no[strlen($customer->vat_no)-$i]?></button> <?= $sign ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 col-xs-5" >
                        <div class="row">
                            <div class="col-sm-6 col-xs-6 text-left" style="padding-left: 30px">
                                <?= lang('លេខវិក្ក័យបត្រ <br/> Invoice No');?>
                            </div>
                            <div class="col-sm-6 col-xs-6 text-left" style="height: 41px; padding-top: 12px">
                                <?= $invs->reference_no; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-xs-6 text-left" style="padding-left: 30px">
                                <?= lang('កាលបរិច្ឆេទ <br/> Date');?>
                            </div>
                            <div class="col-sm-6 col-xs-6 text-left" style="height: 41px; padding-top: 12px">
                                <?php
                                $date = $this->erp->hrsd($invs->date);
                                echo $date;
                                ?>

                                <!--<?php for($i=strlen($date);$i>=1 ; $i--) { ?>
									<?php
                                    $signd ="";
                                    if ($i == 7 || $i == 5) {
                                        $signd = "&nbsp;&nbsp;";
                                    }
                                    ?>
								    <?= $date[strlen($date)-$i]; ?><?= $signd ?>
								<?php } ?>-->
                            </div>
                        </div>
                    </div>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="5"> 
				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped print-table order-table" style="margin-top: 10px;">
						<thead>
						<tr>
						
							<th style="text-align:center;"><?= lang("ល-រ <br/> Nº"); ?></th>
							<?php
								$isTax=false;
								$isDiscount=false;
								if ($Settings->tax1 && $rows['item_tax'] != 0 && $rows['tax_code'] ) {
									$isTax= true;
								}
								if ($Settings->product_discount && $rows['discount'] != 0) {
									$isDiscount = true;
								}
                            $dis=0;
                            $tax=0;
                            foreach ($rows as $row1):
                            $free = lang('free');
                            $product_unit = '';
                            $tax+=$row1->item_tax;
                            $dis+=$row1->item_discount;
                            endforeach;
                           // echo '<h1  >'.$dis.'</h1>';
							?>
							<th style="text-align:center;" ><?= lang("បរិយាយមុខទំនិញ <br/> Description"); ?></th>
							<th style="text-align:center;"><?= lang("ខ្នាត <br/> Unit"); ?></th>
							<th style="text-align:center;"><?= lang("បរិមាណ <br/> Quantity"); ?></th>
							<th style="text-align:center;"><?= lang("ថ្លៃ​ឯកតា <br/> Unit_Price"); ?></th>
							<?php
							if ($tax>0) {
								echo '<th style="text-align:center;">' . lang("អាករឯកតា <br/> Tax") . '</th>';
								
							}
							 if ($dis>0) {
								echo '<th style="text-align:center;">'.lang("បញ្ចុះតម្លៃឯកតា​ <br/> Discount").'</th>';
							 }
							?>
							<th style="text-align:center;" colspan="<?=($isTax && $isDiscount)? 2:1 ?>"><?= lang("តម្លៃ <br/> Amount"); ?></th>
						</tr>

						</thead>

						<tbody>

						<?php $r = 1;
						$tax_summary = array();
						foreach ($rows as $row):
						$free = lang('free');
						$product_unit = '';
						$total+=$row->subtotal;

//						if($row->variant){
//							$product_unit = $row->variant;
//						}else{
//							$product_unit = $row->unit;
//						}

						$product_name_setting;
						if($pos->show_product_code == 0) {
							$product_name_setting = $row->product_name;
						}else{
							$product_name_setting = $row->product_name;
						}
						?>
							<tr>
								<td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
								<td style="vertical-align:middle;">
									<?= $product_name_setting ?>
									<?= $row->details ? '<br>' . $row->details : ''; ?>
									<?= $row->serial_no ? '<br>' . $row->serial_no : ''; ?>
								</td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?= $row->uname ?></td>
								<td style="width: 80px; text-align:center; vertical-align:middle;"><?= $this->erp->formatQuantity($row->quantity); ?></td>
								<!-- <td style="text-align:right; width:100px;"><?= $this->erp->formatNumber($row->net_unit_price); ?></td> -->
								<td style="text-align:right; vertical-align:middle; width:100px;"><?= $row->subtotal!=0?$this->erp->formatNumber($row->unit_price):$free; ?></td>
								<?php
								if ($tax>0) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">' . ($row->item_tax != 0 && $row->tax_code ? '<small>('.$row->tax_code.')</small>' : '') . ' ' . $this->erp->formatNumber($row->item_tax) . '</td>';
								}
								if ($dis>0) {
									echo '<td style="width: 100px; text-align:right; vertical-align:middle;">'. $this->erp->formatNumber($row->item_discount).'</td>';
								}
								?>
								<td style="text-align:right; vertical-align:middle; width:120px;" colspan="<?=($isTax && $isDiscount)? 2:1 ?>"><?= $row->subtotal!=0?$this->erp->formatNumber($row->subtotal):$free; ?></td>
							</tr>
							<?php
							$r++;
						endforeach;
						?>
						<?php
							if($r<11){
								$k=11 - $r;
								for($j=1;$j<=$k;$j++){
									if( $dis >0){
									    if($tax>0){
                                            echo  '<tr>
											<td height="34px" class="text-center"></td>
											<td ></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td ></td>

										</tr>';
                                        }else{
                                            echo  '<tr>
											<td height="34px" class="text-center"></td>
											<td ></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td ></td>

										</tr>';
                                        }


								    }else{
                                        if($tax>0){
                                            echo  '<tr>
											<td height="34px" class="text-center"></td>
											<td ></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td ></td>

										</tr>';

                                        }
									    else{
										echo  '<tr>
											<td height="34px" class="text-center"></td>
											<td ></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>

										</tr>';
									}
								}
									$r++;
								}
							}
						?>
						<?php
//                            echo $dis.'__Tax = '.$tax;
                            ?>
						
						</tbody>
						<tfoot>
						<?php
						$col = 1;
						$rol = 1;
						$tcol = 0;

						if ($Settings->product_discount) {
							$col++;
							$tcol++;
						}
						if ($Settings->tax1) {
							$col++;
							$tcol++;
						}
						if ($invs->grand_total != $inv->total) {
							$rol++;
						}
						if ($invs->order_discount != 0) {
							$rol++;
						}
						if ($invs->shipping != 0) {
							$rol++;
						}
//						if ($Settings->tax2 && $inv->order_tax != 0) {
//							$rol++;
//						}
//						if($invs->paid != 0 && $invs->deposit != 0) {
//							$rol += 3;
//						}elseif ($invs->paid != 0 && $invs->deposit == 0) {
//							$rol += 2;
//						}elseif ($invs->paid == 0 && $invs->deposit != 0) {
//							$rol += 2;
//						}
						$rol++;
						$tcol++;
//						 echo '<h1>'.$col.'_'.$tcol.'</h1>';
						if($dis>0){
						    $col+=1;
						    $tcol-=1;
                        }
                        if($tax>0){
                            $col+=1;
                            $tcol-=1;
                        }
                        if($tax>0&& $dis>0){
                            $col+=1;
                        }
                        if($dis<=0&&$tax<=0){
                            $tcol-=1;
                        }
                        if($invs->paid>0){
						    $rol+=2;
                        }
						?>
<!--                        <h1>--><?//= $col.'_----'.$rol; ?><!--</h1>-->
						<tr>
							<td rowspan="<?= $rol; ?>" colspan="<?= $col; ?>"><b>Note:</b><br><?= $this->erp->decode_html($customer->invoice_footer);  ?></td>
							<td style="text-align:right; padding-right:10px;font-weight:bold;" colspan="<?= $tcol; ?>"><?= lang("សរុប <br/> Total"); ?></td>
							<?php
							if ($Settings->tax1 && $rows['item_tax'] != 0 && $rows['tax_code']) {
								echo '<td style="text-align:right; padding-top:20px; font-weight:bold;">' . $this->erp->formatNumber($inv->product_tax) . '</td>';
							}
							if ($Settings->product_discount && $rows['discount'] != 0) {
								echo '<td style="text-align:right; vertical-align:middle; font-weight:bold;">' . $this->erp->formatNumber($inv->product_discount) . '</td>';
							}
							?>
							<td style="text-align:right; padding-right:10px; vertical-align:middle; font-weight:bold;"><?= $this->erp->formatNumber($total); ?></td>
						</tr>
						<?php if ($invs->order_discount != 0) {
							echo '<tr><td colspan="' . $tcol . '" style="text-align:right; padding-right:10px; font-weight:bold;">' . lang("បញ្ចុះតម្លៃ​  <br/> Order_Discount") . '</td><td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px; font-weight:bold;">' . $this->erp->formatNumber($invs->order_discount) . '</td></tr>';
						}
						?>
						<?php if ($invs->shipping > 0) {
							echo '<tr><td colspan="' . $tcol . '" style="text-align:right; vertical-align:middle !important; padding-right:10px; font-weight:bold;" >' . lang("​ដឹក​ជញ្ជូន​ <br/> Shipping") . ' </td><td style="text-align:right; vertical-align:middle; padding-right:10px;font-weight:bold;">' . $this->erp->formatNumber($invs->shipping) . '</td></tr>';
						}

						?>
						<?php if ($invs->order_tax != 0) {
							$vat = str_replace('@', '', (strstr($invs->vat, '@', false)));
							if ($vat == '10%') {
								$vat_kh = '១០%';
							}
							echo '<tr><td colspan="' . $tcol . '" style="text-align:right; vertical-align:middle !important; padding-right:10px; font-weight: bold;">' . lang("អាករលើតម្លែបន្ថែម <span>". $vat_kh ."</span><br/><span style='font-size:12px'>". $invs->tax_name ."</span>") . '</td><td style="text-align:right; vertical-align:middle; padding-right:10px;font-weight:bold;">' . $this->erp->formatNumber($invs->order_tax) . '</td></tr>';
						}
						?>
                        <?php
                            if($invs->order_tax>0||$invs->shipping>0||$invs->order_discount>0){
                        ?>
                                <tr>
                                    <td colspan="<?= $tcol ?>"
                                        style="text-align:right; font-weight:bold;"><?= lang("សរុប <br/> Grand_Total"); ?>
                                    </td>
                                    <td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;"><?= $this->erp->formatNumber(($total +  $invs->order_tax+$invs->shipping)-$invs->order_discount);?></td>
                                </tr>
                        <?php
                        }
                        ?>
                        <?php
                        if($invs->paid>0){
                            ?>
                            <tr>
                                <td colspan="<?= $tcol ?>" style="text-align:right; font-weight:bold;">ប្រាក់កក់<br>Deposite</td>
                                <td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;" ><?= $this->erp->formatNumber($invs->paid) ?></td>
                            </tr>
                            <tr>
                                <td  colspan="<?= $tcol ?>" style="text-align:right; font-weight:bold;">ប្រាក់នៅសល់<br>Balance</td>
                                <td style="text-align:right; padding-right:10px; font-weight:bold; padding-top:20px;"><?= $this->erp->formatNumber($invs->grand_total-$invs->paid) ?></td>
                            </tr>
                            <?php
                        }
                        ?>


						</tfoot>
					</table>
				</div>
			</td>
		</tr>
	</table>
    <?php
        //$this->erp->print_arrays($invs);
    ?>
</center>

	<div id="footer" class="row">
		<div class="col-sm-6 col-xs-6">
			<center>
				<hr style="border:dotted 1px; width:50%; vertical-align:bottom !important; " />
				<p style="margin-top: -20px !important"><b style="font-size:14px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​ទិញ​  <br/> Customer`s Signature & Name'); ?></b></p>
			</center>
		</div>
		<div class="col-sm-6 col-xs-6" style="float: right;">
			<center>
				<hr style="border:dotted 1px; width:50%; vertical-align:bottom !important; " />
				<p style="margin-top: -20px !important"><b style="font-size:14px;"><?= lang('​ហត្ថលេខា និង ឈ្មោះ​អ្នក​លក់​  <br/> Seller`s Signature & Name'); ?></b></p>
			</center>
		</div>
		<div colspan="5"  style="font-size:12px; font-family:'Khmer OS'; padding-top:20px; margin-left:70px;">
			<b><u>សម្គាល់​</u> : </b> ច្បាប់​​ដើម​សម្រាប់​អ្នក​ទិញ​ ច្បាប់​ចម្លង​សម្រាប់​អ្ន​ក​លក់​។ <br/>
			<b><u>Note</u> : </b> Original invoice for customer copy invoice for seller. 
		</div>
	</div>
</div>
<script type="text/javascript">
 window.onload = function() { window.print(); }
</script>