<?php
	
?>
<style type="text/css">
	@page {
	  size: A5;
	  margin:10px !important;
	}
    @media print {
        #myModal .modal-content {
            display: none !important;
        }
        .modal-dialog {
    		width: 98% !important;
    		height: 100% !important;
    		margin: 0 auto !important;
    		padding: 0 !important;
    	}
        .modal-content{
        	border: none !important;
        }

        .modal-body {
        	height: 100% !important;
        	padding: 0 !important;
        	line-height: 95% !important;
        }
        tr td {
        	height: 5px !important;
			font-size:12px;
			line-height:20px;
        }
		th{
			font-size:12px;
			line-height:20px;
		}
		.table th {
        	height: 5px !important;
			background-color: #444 !important;
			color: #FFF !important;
        }
		p{
			font-size:12px;
		}
		span{
			font-size:12px;
		}
    }
	.tables{
		width:100%;
		border:1px solid black;
		margin-top:10px;
		line-height:35px;
		
	}
	tr th{
		border:1px solid black;
		text-align:center;
		
	}
	td{
		border:1px solid black;
		text-align:center;
	}
	li{
		list-style-type: none;
	}
</style>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body print">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
			<div class="container">
				<div class="row">
					<div class="col-xs-12 text-center">
						<h2>បង្កាន់ដៃទទួលប្រាក់</h2><br>
						<h2 style=" margin-top:-10px;">OFFICIAL RECEIPT</h2>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-8">
						<p>ភូមិ អង្គ សង្កាត់ចោមចៅ ខ័ណ្ឌ ពោធិសែនជ័យ​​ ភ្នំពេញ</p><br>
						<p style=" margin-top:-20px;">Phum Ang, Sangkat Chom Chao, Khan Pour Sen Chay, Phnom Penh</p><br>
						<p style=" margin-top:-20px;">Tel: 023 729 888, 077 58 1111, 077 63 1111</p>
					</div>
					<div class="col-xs-4 text-center">
						
					</div>
				</div>
				
				<div class="row">
					<div class="col-xs-8 text-center">
						
					</div>
					<div class="col-xs-4" style="float:left;">
						<li>
							<span class="frt"><?= lang("ថ្ងៃទី/Date"); ?></span>
							<span>&nbsp;&nbsp;:&nbsp;&nbsp;</span>
							<span><?php echo date("d/m/Y h:i");?></span>
							
						</li>
					</div>					
				</div>
				
				<div class="row">
					<div class="col-xs-8">
						<li>
							<span class="frt"><?= lang("បានទទួលពី/Received From"); ?></span>
							<span>&nbsp;&nbsp;:&nbsp;&nbsp;</span>
							<span><?=$inv->customer_name?></span>
							
						</li>
					</div>
					<div class="col-xs-4 text-center">
						
					</div>
				</div><br>
				
				<table class="tables">
					<thead>
						<tr>
							<th rowspan="2" style="width:5%">
								ល.រ </br> Nº
							</th>
							<th rowspan="2" style="width:50%">
								បរិយាយ </br> Description
							</th>
							<th colspan="2" style="width:45%">
								ចំនួន/Amount
							</th>
						</tr>
						<tr>
							<th>USD</th>
							<th>Cents</th>
						</tr>
					</thead>
					<tbody>
					
						<?php
							$r = 1;
							$no = 1;
							$total=0;
                        foreach ($payments as $payment):
						//$this->erp->print_arrays($payment->amount);
												
                        ?>
							<tr>
								<td><?=$r?></td>
								<td style="text-align:left;padding-left:30px;"><?= lang($payment->reference_no); ?></td>
								<td><?$ip = $this->erp->formatNumber($payment->amount); 
										   $iparr = split ("\.", $ip); 									   
										   echo "$iparr[0]";									
										?>
							   </td>
								<td>
										<?$ip = $this->erp->formatNumber($payment->amount); 
										   $iparr = split ("\.", $ip); 									   
										   echo "$iparr[1]";									
										?>
								</td>
							</tr>
						<?php
                            $r++;
							$no++;
							$total+=$payment->amount;
                        endforeach;
                        ?>
						<?php
								if($no<4){
									$k=4 - $no;
									for($j=1;$j<=$k;$j++){
										echo  '<tr>
												<td height="34px" class="text-center">'.$r.'</td>
												<td style="width:34px;"></td>
												<td></td>
												<td></td>												
												
											</tr>';
										$r++;
									}
								}
							?>
						<tr>
							<td colspan="2" class="text-right" style="padding-right:20px;"><b>សរុប/Total :</b></td>
							<td colspan="2"><?=$this->erp->formatNumber($total);?></td>
							
						</tr>
					</tbody>
				</table><br>
				<div class="row" style="margin-top:10px;">
					<div class="col-xs-12">
						<li>
							<span class="frt" style="text-decoration:underline">សរុបទឹកប្រាក់ជាអក្សរ/Amount in Words :</span>
							<span><?php echo '<b>' .$this->erp->convert_number_to_words($total) . '</b>'; ?></span>
						</li>
						
					</div>
				</div><br>
				
				<table class="table table-bordered" style="margin-top:10px;">
					<thead>
					  <tr>
						<th>រៀបចំដោយ/Prepared By</th>
						<th>ពិនិត្យដោយ/Cheked By</th>
						<th>អនុម័តដោយ/Approved By</th>
						<th>ទទួលដោយ/Received By</th>
					  </tr>
					</thead>
					<tbody>
					  <tr>
						<td class="text-left"></p><p>&nbsp;</p><p>&nbsp;</p>
							<p>ឈ្មោះ/Name</p>
							<p>ថ្ងៃទី/Date</p>
						</td>
						<td class="text-left"></p><p>&nbsp;</p><p>&nbsp;</p>
							<p>ឈ្មោះ/Name</p>
							<p>ថ្ងៃទី/Date</p>
						</td>
						<td class="text-left"></p><p>&nbsp;</p><p>&nbsp;</p>
							<p>ឈ្មោះ/Name</p>
							<p>ថ្ងៃទី/Date</p>
						</td>
						<td class="text-left"></p><p>&nbsp;</p><p>&nbsp;</p>
							<p>ឈ្មោះ/Name</p>
							<p>ថ្ងៃទី/Date</p>
						</td>
					  </tr>
					</tbody>
				</table>
        </div><br>
    </div>
</div>