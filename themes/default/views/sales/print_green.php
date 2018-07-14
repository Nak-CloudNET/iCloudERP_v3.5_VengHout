<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <title><?php echo $this->lang->line("invoice") . " " . $inv->reference_no; ?></title>
    <style type="text/css">
         body {
			margin-top:0;
			margin-right:10px;
			margin-left:10px;
			margin-bottom:10px;
            
            background: #FFF;
			font-family: "Times New Roman", Times, serif;
			font-size:9pt;
        }	
		p{
			line-height: 10px; 
		}
		table{
			border-collapse: collapse;
		}
		
		td{
			text-align:center;	
			border-bottom:1px dotted #000000;
			line-height: 20px; 
			border-right:solid 1px black;
			border-left:solid 1px black;
			height:24px;
		}	
		.title{
			padding-top:20px;
		}
		table {
			font-family: 'Khmer OS'; 
			color: #000000;
			font-size: 12px;
		}
		.inv-right {
			margin-right: 35px;
			font-family: Khmer OS;
		}
		.inv-left{
			width: 60%;
			font-family: Khmer OS;
		}
		.inv-lef p {
			padding-top:0;
			line-height:0;
		}
		.img-b {
			width:100px;
			text-align:center;
		}
		.w-50 {
			font-family: Arial;
			width:80%;
			display: inline-block;
			
			border-bottom:1px dotted #000000;			
		}
		.lh-25{
			line-height: 22px;
		}
		
		
		@media print {
			.pbreak {page-break-before:always}
			
		}
		
		
    </style>
</head>

<body>
<?php
$bill_to=$customer->name ? $customer->name : $customer->company;
$address=$customer->address;
$tel=$customer->phone;
$inv_no=$inv->reference_no;
$iss_date=$this->erp->hrsd($inv->date);
$address = $biller->address;
$biller_company = $biller->company;
$logo = $biller->logo;
$customer_name = $customer->company;
$saleman = $user->username;
//$this->erp->print_arrays($biller);
$head='<div class="title">
<div style="margin-top:20px;width:30%;float:left;text-align:center;">
	 <img src="'. base_url() . 'assets/uploads/logos/'.$logo.'" alt="company_logo" width="180"/> 
</div>
<div style="float:left;">
	<div style="width:345px;margin:0 auto;">
		<div style="text-align:center;font-weight:bold;font-size:20px;margin-top: 0;">'.$biller_company.'</div>
		<div style="font-size:16px;font-family:Khmer OS MUOL;text-align:center;font-weight:bold;">ផ្កត់ផ្គង់និងចែកចាយបន្លែគ្រប់ប្រភេទ</div>
		<div style="text-align:center;">'.$address.'</div>
	</div>
	<div style="text-align:center;">
		<p style="font-size:16px;font-family:Khmer OS MUOL;font-weight:bold;">វិក័យប័ត្រ</p>
		<p style="font-size:16px;font-family:Khmer OS MUOL;font-weight:bold;">Invoice</p>
	</div>
</div>
	<div style="float: left" class="inv-left">
		<p>
			<span style="font-size:13px;font-family:Khmer OS"><b>ឈ្មោះអតិថិជន :  </b></span>
			<span style="font-size:13px;font-family:Khmer OS"><b>'.$customer_name.' </b></span>
		</p>
		<p>
			<span style="font-size:13px;font-family:Khmer OS"><b>លេខទូរស័ព្ទ :  </b></span>
			<span style="font-size:13px;font-family:Khmer OS"><b>'.$tel.' </b></span>
		</p>
			<div style="margin-top:-15px;float:left;font-size:13px;font-family:Khmer OS"><b><p style="line-height:20px;">អាស័យដ្ផាន : </p></b></div>
			<div style="margin-top:-15px;float:left;width:300px;font-size:13px;font-family:Khmer OS"><b><p style="line-height:20px;">'.$address.' </p></b></div>
		
		
	</div>
	<div style="float: right" class="inv-right">
		<p>
			<span style="font-size:13px;font-family:Khmer OS"><b>លេខវិក័យប័ត្រ :  </b></span>
			<span style="font-size:13px;font-family:Khmer OS"><b>'.$inv_no.' </b></span>
		</p>
		<p>
			<span style="font-size:13px;font-family:Khmer OS"><b>កាលបរិច្ឆេទ :  </b></span>
			<span style="font-size:13px;font-family:Khmer OS"><b>'.$iss_date.' </b></span>
		</p>
		<p>
			<span style="font-size:13px;font-family:Khmer OS"><b>អ្នកលក់ :  </b></span>
			<span style="font-size:13px;font-family:Khmer OS"><b>'.$saleman.' </b></span>
		</p>
		
	</div>
	<div class="clearfix"></div>
			
	</div>' ;
$table='<table width="100%">
				<tr style="border-top:solid 1px black; 
					border-bottom:solid 1px black;">
					<td width="5%"><b>ល.រ</b><br/>
					<td  width="33%"><b>បរិយាយមុខទំនិញ</b><br/>
					<td  width="10%"><b>ចំនួន</b><br/>
					<td  width="12%"><b>តំលៃ / ឯកតា</b><br/>
					<td  width="18%"><b>បញ្ជុះតម្លៃ</b><br/>
					<td  width="12%"><b>តំលៃសរុប</b><br/>
				</tr>
			';
					$total = 0;
					$i=1;
					// get blank rows
					
					$c=1;
					foreach($rows as $row){
						$total_page = ceil($c/27);
						$c++;
					}
					$max_items = $total_page * 27;
					$total_item = sizeof($rows);
					$blank_rows = $max_items - $total_item;
					
					foreach ($rows as $row):
					$n_page=ceil($i/27);
					$free = lang('free');
					$product_name = $row->product_name;
					$product_name = word_limiter(strip_tags($product_name), 5);
					$discount = $row->discount;
					$unit = $row->unit;
					
					$qty=$row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free;
					$amount=$row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free;
					
					if($i==27 || $i==54 || $i==76 || $i==113 || $i==140){
						$tablebody[$n_page].='<tr  style=" line-height:5px;border-bottom:solid 1px black;">
						<td>'.$i.'</td>
						<td style="text-align:left;">'.$product_name.'</td>
						<td>'.$this->erp->formatDecimal($row->quantity).''.$unit.'</td>
						<td>'.$qty.'</td>
						<td>'.$discount.'</td>
						<td>'.$amount.'</td>
						</tr>';
					}else{
						$tablebody[$n_page].='<tr style="line-height:5px">
						<td>'.$i.'</td>
						<td style="text-align:left;">'.$product_name.'</td>
						<td>'.$this->erp->formatDecimal($row->quantity). ''.$unit.'</td>
						<td>'.$qty.'</td>
						<td>'.$discount.'</td>
						<td>'.$amount.'</td>
						</tr>';
					}
					
					$total += $row->subtotal;
					$i++;
                    endforeach;
					// make blank row 
					$num_row=$i;
					$none_row=$n_page*27-6;
					for($num_row;$num_row<=$none_row;$num_row++){
						if($num_row==$none_row){
							$tablebody[$n_page].='<tr style="border-bottom:solid 1px black;">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							</tr>';
							
						}else{	
							$tablebody[$n_page].='<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							</tr>';
						}						
					}
					
					$total=$this->erp->formatMoney($inv->total);	
					$discount=$this->erp->formatMoney($inv->total_discount);
					$deposit=$this->erp->formatMoney($inv->total - $inv->paid);
					$paid = $inv->paid ? $this->erp->formatMoney($inv->paid): 0;
					$sub_total=$this->erp->formatMoney($inv->grand_total);
					$customer_balance = $inv->old_invoice_balance?$inv->old_invoice_balance:0;
					$grand_total = $inv->grand_total - $inv->paid + $customer_balance;
$tablebtm.='	<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;" ></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>ទឹកប្រាក់សរុប </b></td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">'.$total.' &nbsp;</b></td>
				</tr>
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>បញ្ចុះតំលៃ</b> </td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">'.$discount.' &nbsp;</b></td>
				</tr>
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>ប្រាក់កក់</b> </td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">'.$paid.' &nbsp;</b></td>
				</tr>
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>សរុប </b></td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">'.$sub_total.' &nbsp;</b></td>
				</tr>
				
			'	;	
$tablebtm_last.='
				<tr class="pbreak clearfix">
					<td colspan="3" style="border:0 !important;height:25px !important;" ></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>ទឹកប្រាក់សរុប </b></td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">'.$total.' &nbsp;</b></td>
				</tr>
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>បញ្ចុះតំលៃ</b> </td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">'.$discount.' &nbsp;</b></td>
				</tr>
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>ប្រាក់កក់</b> </td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">'.$paid.' &nbsp;</b></td>
				</tr>
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>សរុប </b></td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">'.$sub_total.' &nbsp;</b></td>
				</tr>
			'	;			
$tablebtmnone='	<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;" ></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>ទឹកប្រាក់សរុប</b></td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">&nbsp;</b></td>
				</tr>
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>បញ្ចុះតំលៃ</b> </td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;"> &nbsp;</b></td>
				</tr>
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>ប្រាក់កក់</b> </td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;"> &nbsp;</b></td>
				</tr>
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>សរុប </b></td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">&nbsp;</b></td>
				</tr>
			'	;
			
$tableend='</table>';
		
$btm='<div style="margin-top:40px;"><table width="100%" >
					
					</tr>
						<td style="width:33.33%;border:0 !important;padding:10px;"><div style="font-family: Arial; border-top: dotted 1px #000 !important;padding-top: 5px;">អតិថិជន</div>
						<td style="width:33.33%;border:0 !important;padding:10px;"><div style="font-family: Arial; border-top: dotted 1px #000 !important;padding-top: 5px;">អ្នកលក់</div>
						<td style="width:33.33%;border:0 !important;padding:10px;"><div style="font-family: Arial; border-top: dotted 1px #000 !important;padding-top: 5px;">រៀបចំដោយ </div>
					</tr>
			</table></div>';
			
/*		
$btmnon='<div style="margin-top:150px;"><table width="100%" >
					
					</tr>
						<td style="width:33.33%;border:0 !important;padding:10px;"><div style="font-family: Arial; border-top: dotted 1px #000 !important;padding-top: 5px;">អតិថិជន</div>
						<td style="width:33.33%;border:0 !important;padding:10px;"><div style="font-family: Arial; border-top: dotted 1px #000 !important;padding-top: 5px;">អ្នកលក់</div>
						<td style="width:33.33%;border:0 !important;padding:10px;"><div style="font-family: Arial; border-top: dotted 1px #000 !important;padding-top: 5px;">រៀបចំដោយ </div>
					</tr>
			</table></div>';
*/
			
?>

<?php if($blank_rows <= 6) { ?>
	<?php			
		for($j=1;$j<=$n_page;$j++){
			echo '<div>'.$head.'</div><div>'.$table.''.$tablebody[$j];
			if($j==$n_page){
				echo $tablebtm_last.''.$tableend.'</div>'.$btm;
			}
			else{
				echo $tableend.'</div><div style="width:100%;height:1px;"class="pbreak clearfix"></div>';
			}
		}
	?>
<?php }else{ ?>
	<?php			
		for($j=1;$j<=$n_page;$j++){
			echo '<div>'.$head.'</div><div>'.$table.''.$tablebody[$j];
			if($j==$n_page){
				echo $tablebtm.''.$tableend.'</div>'.$btm.'<div style="width:100%;height:1px;" class="pbreak clearfix"></div>';
			}
			else{
				echo $tableend.'</div><div style="width:100%;height:1px;"class="pbreak clearfix"></div>';
			}
		}
	?>
<?php } ?>

</body>

</html>










