<?php
    //$this->erp->print_arrays($stock_item);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("Project_plan_form") ; ?></title>
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


    </style>
</head>

<body>
<div class="print_rec" id="wrap" style="width: 90%; margin: 40px auto;">
    <div class="row">
        <div class="col-lg-12">
            <div class="clearfix"></div>
            <div class="row">  
			<div class="col-sm-12 col-xs-12">
					<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                		<i class="fa fa-print"></i> <?= lang('print'); ?>
            		</button>
				</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12 ">
						<center><img style="width: 165px;margin-top:-18px !important;" src="<?= base_url() ?>assets/uploads/logos/header_logo.png"></center>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12 company_addr" style="margin-top:15px 	!important">
					<?php 
					$st=0;
					if(array($loan)) {
						foreach($billers as $bi){
							if($st==0){
								
								$vat_nos=$bi->vat_no;
								$add=$bi->address;
								$ph=$bi->phone;
								$em=$bi->email;
								$st=1;
							}
							
						}
					}
						?>
							<center>
								<p style="font-size: 12px;">លេខអត្តសញ្ញាណកម្ម អតប (VAT No)&nbsp;:&nbsp;<?=$vat_nos;?></p>
								<p style="margin-top:-10px !important;font-size: 12px;">អាសយដ្ឋាន ៖ &nbsp;:&nbsp;<?=$add ;?></p>
								<p style="margin-top:-10px !important;font-size: 12px;">ទូរស័ព្ទលេខ (Tel)&nbsp;:&nbsp;<?=$ph; ?></p>
								<p style="margin-top:-10px !important;font-size: 12px;">សារអេឡិចត្រូនិច (E-mail)&nbsp;:&nbsp;<?=$em;?></p>
							</center>
					
				</div>
				
			</div>
			
			<div class="row">
				<div class="col-sm-12 col-xs-12 inv" style="margin-top: -15px !important">
					<center>
						<h1 style="margin-top:20px !important; font-family:Time New Roman;font-size:25px;">Project Plan Form</h1>
					</center>
				</div>
			</div>
			
            <div class="row padding10">
                <div class="col-xs-4" style="float: left;font-size:14px">
                    <b><p style="font-size: 17px;"><?= lang('reference');?></p></b>
						<table>
						<tr>
							<td>Reference No</td>
							<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
							<td><b><?=$invs->reference_no; ?></b></td>
						</tr>
						<tr>
							<td>Date</td>
							<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
							<td><b><?=$this->erp->hrsd($invs->date); ?></b></td>
						</tr>
						<tr>
							<td>Plan</td>
							<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
							<td><b><?=$invs->plan; ?></b></td>
						</tr>
						</table>
                 </div>
                <div class="col-xs-4" style="text-align:center;margin-top:-20px">
                </div>
                <div class="col-xs-4"  style="float: right;font-size:14px">
   
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row padding10" style="display:none">
                <div class="col-xs-6" style="float: left;">
                   
                </div>
                <div class="col-xs-5" style="float: right;">
                  
                </div>
            </div>

            <div class="clearfix"></div>
            <div><br/></div>
            <div class="-table-responsive">
                <table class="table table-bordered table-hover table-striped" style="width: 100%;">
                    <thead  style="font-size: 13px;">
                        <tr>
                            <th><?= lang("no"); ?></th>
                            <th><?= lang("products_code"); ?></th>
                            <th><?= lang("products_name"); ?></th>
                            <th><?= lang("quantity"); ?></th>                              
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                        <?php
                        $i=1;
                        $total = 0;
                            foreach($stock_item as $si){
                                echo '
                                    <tr>
                                        <td style="text-align:center;">'.$i.'</td>
                                        <td style="text-align:center;width:15%;">'.$si->code.'</td>
                                        <td>'.$si->name.' </td>
                                        <td style="text-align:center;">' . $this->erp->formatQuantity($si->qty) . '</td>
                                        
                                    </tr>
                                
                                ';
                                $total += $si->qty;
                                $i++;

                            }
						
							if($i<11){
								$k=11 - $i;
								for($j=1;$j<=$k;$j++){
									echo  '<tr>
											<td height="34px" class="text-center">'.$i.'</td>
											<td style="width:34px;"></td>
											<td></td>
											<td></td>
											
										</tr>';
									$i++;
								}
							}
				
                            echo'
                                    <tr>
                                        <td colspan='."6".'>
                                            <p><b>Note:</b>'.'&nbsp;&nbsp;'.strip_tags($invs->note).'</p>
                                        </td>
                                    <tr>
                                ';
                        ?>
						
                    </tfoot>
                </table>
            </div>
            <div class="row">
				
                <div class="col-md-3  pull-left" style="text-align:center">
                    <hr/>
                    <p><b><?= lang("stock_controller"); ?></b></p>
                </div>
				<div class="col-md-3  pull-left" style="text-align:center">
                   
                </div>
				<div class="col-md-3  pull-left" style="text-align:center">
                   
                </div>
                <div class="col-md-3 " style="text-align:center">
                    <hr/>
                    <p><b><?= lang("manager"); ?></b></p>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            
        </div>
    </div>
</div>
<div id="mydiv" style="">
    
<div id="wrap" style="width: 90%; margin: 0 auto;">
    <div class="row">
        <div class="col-lg-12 no-print">
                <button type="button" class="btn btn-primary btn-default  pull-left" onclick="window.print();">
                    <i class="fa fa-print"></i> <?= lang('print'); ?>
                </button>&nbsp;&nbsp;
            <a href="<?= site_url('project_plan'); ?>"><button class="btn btn-warning" ><i class="fa fa-backward "></i>&nbsp;<?= lang("back"); ?></button></a>

        </div>
    </div>
</div>
</div>
<br/><br/>
<div id="wrap" style="width: 90%; margin:0px auto;">
<div class="col-md-12" style="margin-bottom:20px;">
</div>
</div>
<div></div>
</script>
</body>
</html>