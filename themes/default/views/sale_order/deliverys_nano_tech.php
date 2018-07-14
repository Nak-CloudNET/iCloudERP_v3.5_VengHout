<?php //$this->erp->print_arrays($quote_items);?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("delivery") . " " . $inv->do_reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <style type="text/css">
        html, body {
           
            background: #FFF;
        }
		.container {
			width: 29.7cm;
			margin: 20px auto;
			/*padding: 10px;*/
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
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
		.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border: 1px solid #000 !important;
		}
        @media print{
			.pageBreak {
				display: block; page-break-before: always; 
			}
			.container {
				width: 98% !important;
				margin: 0 auto !important;
			}
			
			.line {
				width:70% !important;
			}
        }
		img{
			margin-top:20px;
		}
    </style>
</head>

<body>
<div class="container" style="width:70%;margin: 0 auto;">
			<div class="col-lg-3 col-sm-3 col-xs-3"​>
				<?php if ($logo) { ?>
				<img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="" width="150px !important;">
			</div>			
			<div class="col-lg-8 col-sm-8 col-xs-8" style="text-align:center !important; margin-left:-60px !important;margin-top:25px !important;">
				<center><h2><?= lang("ប័ណ្ណប្រគល់សម្ភារៈ")?></h2></center>
				<center><h2><?= lang("Delivery")?></h2></center>
			</div>
			<div class="row">
			<div class="col-lg-1 col-sm-1 col-xs-1" style="margin:30px !important;">
				<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                		<i class="fa fa-print"></i> <?= lang('print'); ?>
            	</button>
			</div>
		</div>
				<div class="col-xs-3">
					<?php 
						if($biller->address){echo $biller->address;}
					?> 
                </div>
				
                <div class="col-xs-8">
                   
                </div>
            <?php } ?>
            <div class="clearfix"></div>
          
            <div class="row padding10">
			<br>
                <div class="col-xs-5" style="float: left;font-size:14px;">
                    <table>
                        <tr>
                            <td>
                                <p><?= lang("To");?></p>
                            </td>
                            <td>
                               <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$inv->customer."</b>"; ?></p>
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
                                <p><?= "<b>".$customer->address."</b>"; ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("tel");?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$customer->phone."</b>"; ?></p>
                            </td>
                        </tr>
                    </table>
                    <div class="clearfix"></div>
                </div>
                <div class="col-xs-2">
                    
                </div>
                <div class="col-xs-5"  style="float: right;font-size:14px">
                    <table>
                        <tr>
                            <td>
                                <p><?= lang("Deli_n"); ?><sup>o</sup></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$inv->do_reference_no."</b>";?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("Referent_Invoice_Nº"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$inv->sale_reference_no ."</b>";?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?= lang("date"); ?></p>
                            </td>
                            <td>
                                <p>&nbsp;:&nbsp;</p>
                            </td>
                            <td>
                                <p><?= "<b>".$this->erp->hrsd($inv->date)."</b>";?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="clearfix"></div>
			
                <table class="table table-bordered">
                    <thead  style="font-size: 13px;">
						<tr >
							<th><?= lang("n");?><sup>o</sup></th>
							<th style="width:50% !important"><?= lang("descript"); ?></th>
							<th><?= lang("qty"); ?></th>
							<th><?= lang("Orther"); ?></th>
						</tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                        <?php
                            $no = 1;
                            $total_amount = 0;
                            $row = 0;
                        ?>
                       <?php foreach($inv_items as $inv_item) { ?>
                            <?php
                                $str_unit = "";
                                if($inv_item->option_id){
                                    $var = $this->sale_order_model->getVar($inv_item->option_id);
                                    $str_unit = $var->name;
                                }else{
                                    $str_unit = $inv_item->unit;
                                }
                            ?>
							<tr>
								<td style="text-align:center; width:5%; vertical-align:middle;"><?= $no; ?></td>
								
								<td style="vertical-align:middle;width:30%">
									<?= $inv_item->description?>
								</td>
                               
                                <td style="vertical-align:middle;width:30%" class="text-center">
                                    <?= $this->erp->formatMoney($inv_item->qty)?>
                                </td>
								
								 <td style="vertical-align:middle;width:30%" class="text-center" >
                                 
                                </td>
							</tr>
                            <?php
                            $no++;
                            if($inv_item->option_id){
                                $total_amount +=  $inv_item->variant_qty;
                            }else{
                                $total_amount += $inv_item->qty;
                            }
                            
                            $row++;
                         }
                            if ($no < 13) {
                                $k = 13 - $no;
                                for ($j=1; $j <= $k; $j++) {
                                    echo
                                        '<tr>
                                            <td height="34px" class="text-center"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            
											
                                        </tr>';
                                }
                                
                            }
                        ?>
                    </tbody>
                </table>
				<div class="row" id="footer">
				<br>
						<div class="col-lg-4 col-sm-4 col-xs-4" style="font-size: 17px">
							<center>
							<p class="bold">
								<b>Receiver</b>
							</p>
							<br><br>
							<p> ...............................</p>
							</center>
						</div>
						<div class="col-lg-4 col-sm-4 col-xs-4" style="font-size: 17px">
							<center>
							<p class="bold">
								<b>Deliver</b>
							</p>
							<br><br>
							<p> ...............................</p>
							</center>
						</div>
						<div class="col-lg-4 col-sm-4 col-xs-4" style="font-size: 17px">
							<center>
							<p class="bold">
								<b>Stock Controller</b>
							</p>
							<br><br>
							<p>...............................</p>
							</center>
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
		window.location.href = "<?= site_url('quotes/add'); ?>";
	  });
	});

	</script>
</body>
</html>
