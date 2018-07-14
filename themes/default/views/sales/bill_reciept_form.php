<?php 
	//$this->erp->print_arrays($rows);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->lang->line("sales") . " " . $inv->reference_no; ?></title>
    <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Moul|Raleway" rel="stylesheet"> 
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

        table,tr,td{
            font-size: 13px !important;
        }
    </style>
</head>

<body class="form">
<div id="wrap" style="width:794px;margin: 0 auto;padding:10px;height:700px;">
    <div class="row">
		<div>
			<span>
				<img style="width:175px;height:45px;" src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo2; ?>" alt="<?= $Settings->site_name; ?>">
			</span>
			<span style="font-size:20px;font-weight:bold;padding-left: 138px;color:#ff6633;font-family: Khmer OS Siemreap">ប័ណ្ណទទួលប្រាក់</span>
			<span style="padding-left: 145px;">
				<img style="width:175px;height:45px;" src="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo2; ?>" alt="<?= $Settings->site_name; ?>">
			</span>
		</div>
		<div style="font-family: Khmer Siemreap">
			<br>
			
			<table  style="width:95%">
				<tr>
					<td style="width:45%">
						<b>បុរី ឌឹ ផ្លររ៉ា-រ៊ើប៊ីណា</b>
					</td>
					<td style="padding-right:60px"></td>
					<td style="width:10%"> <b>លេខប័ណ្ណ :<b></td>
					
					<td style="text-align:right; color:#8B0000;"><strong><?=$payment->reference_no; ?></strong></td>
				</tr>
				<tr>
					<td style="width:45%">
						<b><?php echo $biller->address . " " . $biller->city . " " . $biller->postal_code . " " . $biller->state . " " . $biller->country;?></b>
					</td>
					<td style="padding-right:38px"></td>
					<td style="width:11%"> <b>កាលបរិច្ឆេទ :<b></td>
					<td style="text-align:right; color:#8B0000;"><strong><?= $this->erp->hrsd($payment->date); ?></strong></td>
				</tr>
			</table>
			
		</div>
		<hr style="width: 99%; border: 1px solid black; margin-left: 5px;">
		
	    <div style="font-family: Kh Siemreap">
			<table style="width:100%;font-weight:bold;">
				<tr>
					<?php
						$payments = 0;
						foreach($rowpay as $row){
							$payments += $row->amount;
						}
						$payment_ = $this->erp->formatMoney($payments);
					?>
					<td style="width:10%;">ទឹកប្រាក់</td>
					<td style="width: 34%; color:#8B0000;text-align:center;height:45px;padding-top:25px;font-family:Calibri;"><b style="font-size: 20px"><?php echo 
					$payment->amount;?></b></td>
					<td >ប្រភេទទូទាត់</td>
					<td style="width: 41%;padding-top:25px; color:#8B0000;text-align:center;font-family:'Calibri';" colspan="5"><b style="font-size: 20px"><?php echo $payment->paid_by;?></b></td>
				</tr>
				<tr>
					<td style="width:10%;">Amount:</td>
					<td style="width: 34%;"><?php echo 
					"...........................................................................";?></td>
					<td >Pay By:</td>
					<td style="width: 41%;" colspan="5"><?php echo "..........................................................................................................."?></td>
				</tr>
				<tr style="height:40px;"><td colspan="8"></td></tr>
				<tr>
					<td style="width:15%;">ជាអក្សរ </td>
					<td colspan="7" style="color:#8B0000;height:45px;padding-left:80px;font-family:Calibri;"><b style="font-size: 15px"><?php echo $this->erp->numberToWordsCur($payment->amount,"kh","ដុល្លា");?></b></td>
					
				</tr>
				<tr>
					<td style="width:15%;">In Writing:</td>
					<td colspan="7"><?php echo "..............................................................................................................................................................................................................." ;?></td>
					
				</tr>
				<tr style="height:40px;"><td colspan="8"></td></tr>
				<tr>
					<? $recieved = $biller->company != '-' ? $biller->company : $biller->name;?>
					<td style="width:12%;">ទទួលពី</td>
					<td style="padding-top:25px;color:#8B0000;height:45px;text-align:center;font-family:Calibri;" ><b style="font-size: 15px"><?php echo $recieved ?></b></td>
					<td >ប្រភេទផ្ទះ</td>
					<td style="width:10%;padding-top:25px;color:#8B0000;text-align:center;font-family:Calibri;"><b style="font-size: 15px"><?=$products->cf1;?></b></td>
					<td style="width:10%;"> ផ្ទះលេខ </td>
					<td style="padding-top:25px;color:#8B0000;text-align:center;font-family:Calibri;"><b style="font-size: 15px"><?=$products->cf3;?></b></td>
					<td >ផ្លូវលេខ</td>
					<td style="width:10%;padding-top:25px;color:#8B0000;text-align:center;font-family:Calibri;"><b style="font-size: 15px"><?=$products->cf4;?></b></td>
				</tr>
				<tr>
					<td style="width:12%;">Recieved From:</td>
					<td  ><?php echo "...........................................................................";?></td>
					<td>Type:</td>
					<td style="width:10%;"><?php echo "........................"?></td>
					<td style="width:10%;">House No.</td>
					<td><?php echo "........................"?></td>
					<td>Street:</td>
					<td style="width:10%;"><?php echo "........................"?></td>
				</tr>
				<tr style="height:40px;"><td></td></tr>
				<tr>
					<td>ទូទាត់សម្រាប់</td>
					<td style="height:45px;padding-top:25px;color:#8B0000;text-align:center;">
						<?if($jl_data->principle_type != 0){?>
							
							<b style="font-size: 15px"><?php echo $jl_data->term_name;?></b>
						
						<?}else if($jl_data->term_id !=0  && $jl_data->principle_type == 0){?>
							
							<b style="font-size: 15px"><?php echo $jl_data->description;?></b>
							
						<?}else{
							echo " ";
						
						}?>
					</td>
					<td>លើកទី</td>
					<td style="width:10%;padding-top:25px;color:#8B0000;text-align:center;font-family:Calibri;"><b style="font-size: 15px"><?php echo $payment->period;?></b></td>
					<td>ប្រាក់ដើម </td>
					<td style="padding-top:25px;color:#8B0000;text-align:center;font-family:Calibri;"><b style="font-size: 15px"><?php echo $this->erp->formatMoney($payment->principle);?></b></td>
					<td>ការប្រាក់</td>
					<td style="width:10%;padding-top:25px;color:#8B0000;text-align:center;font-family:Calibri;"><b style="font-size: 15px"><?php echo $this->erp->formatMoney($payment->interest);?></b></td>
				</tr>
				<tr>
					<td>Pay:</td>
					<td ><?php echo "..........................................................................."?></td>
					<td>For:</td>
					<td style="width:10%;"><?php echo "........................"?></td>
					<td>Principle:</td>
					<td><?php echo "........................"?></td>
					<td>Interest:</td>
					<td style="width:10%;"><?php echo "........................"?></td>
				</tr>
				
			</table>
		</div>
		<br><br><br>
		<div style="font-family: 'Moul';">
			<div style="float:left;width: 30%;">
				អតិថិជន
				<br>
				<b>Paid By</b>
				<p style="height: 105px;line-height:190px">..............................................</p>
			</div>
			<div style="float:left;padding-left:88px ;width:40% ">អ្នកទទួល
				<br><b>Recieved By</b>
				<p style="height: 105px;line-height:190px">..............................................</p>				
			</div>
			<div style="float:right; width: 30%; text-align:right;padding-center: 36px;">អ្នកពិនិត្យ
				<br><b>Verified By</b>
				<p style="height: 105px;line-height:190px">..............................................</p>
			</div>
		</div>
	</div>
</div>
</body>
</html>