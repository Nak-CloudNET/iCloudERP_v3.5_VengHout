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
			height:28px;
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
		
    </style>
</head>

<body>
<?php
$bill_to=$customer->name ? $customer->name : $customer->company;
$address=$customer->address;
$tel=$customer->phone;
$inv_no=$inv->reference_no;
$iss_date=$this->erp->hrsd($inv->date);

$head='<div class="title"><center style="display:block;"><p style=" font-weight:bold;font-size:50px;margin-top: 0;"><span style="border-bottom:2px solid black;">H . C . H</span></p><p style="font-weight:bold;font-size:20px;"><span style="">RECEIPT</span></p><p style="margin-top:-13px;"><img class="img-b" src="'.base_url().'assets/images/border-b.PNG"><p></center></div>
			<div style="margin-top:-15px;">
			<div style="float: left" class="inv-left">
			<p><span style="font-size:13px;font-family:Khmer OS"><b>Bill To</b></span> :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="w-50 lh-25" style="font-size:15px;"><b> '.$bill_to.' </b></span></p>
			<p><span style="font-size:13px;font-family:Khmer OS"><b>Address</b></span> :&nbsp;&nbsp;&nbsp;<label class="w-50 lh-25" style="font-size:15px;"><b>'.$address.'</b></span></p>
			<p><span style="font-size:13px;font-family:Khmer OS"><b>Tel</b></span> :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label class="w-50"><b style="font-size:12px;"> '.$tel.'</b> </span></p>
			</div>
			<div style="float: right" class="inv-right">
				<p class="lh-25"><b>Invoice No&nbsp;&nbsp;&nbsp;: '.$inv_no.'</b></p>
				<p class="lh-25"><b>Issue Date&nbsp;&nbsp;&nbsp;: '.$iss_date.'</b></p>
				<p class=""><b>Tel &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</b><span><b style="font-size:12px;">'.$biller->phone.'</b></span></p>
			</div>
			<div class="clearfix"></div>
			
			</div>	
				' ;
$table='<table width="100%">
				<tr style="border-top:solid 1px black; 
					border-bottom:solid 1px black;">
					<td width="5%"><b>ល.រ</b><br/>
					<td  width="33%"><b>បរិយាយមុខទំនិញ</b><br/>
					<td  width="18%"><b>ប្រភេទទំនិញ</b><br/>
					<td  width="10%"><b>ចំនួន</b><br/>
					<td  width="12%"><b>តំលៃ / ឯកតា</b><br/>
					<td  width="12%"><b>តំលៃសរុប</b><br/>
				</tr>
			';
				/*
				$q = "SELECT (b.grand_total - b.paid) AS balance
					  FROM 
						 ( SELECT MAX(y.id) prev 
							 FROM erp_sales x 
							 JOIN erp_sales y 
							   ON y.id < x.id 
							  AND y.customer_id = x.customer_id 
							GROUP 
							   BY x.id
						 ) a 
					  JOIN erp_sales b 
						ON b.id = a.prev 
					WHERE b.customer_id = '". $customer->id ."'
					 ORDER 
						BY id DESC LIMIT 1;";
			*/
					$total = 0;
					$i=1;
                   
				   foreach ($rows as $row):
					$n_page=ceil($i/20) ;
					
					
					$free = lang('free');
					$category_name = $row->category_name;
					$product_unit = $category_name;
					$product_name_setting;
					$product_name_setting = $row->product_details;
					
					$product_name_setting = word_limiter(strip_tags($product_name_setting), 5);
					/*
					if($pos->show_product_code == 0) {
						$product_name_setting = $row->product_name . ($row->variant ? ' (' . $row->variant . ')' : '');
					}else{
						$product_name_setting = $row->product_name . " (" . $row->product_code . ")" . ($row->variant ? ' (' . $row->variant . ')' : '');
					}
					*/
					$qty=$row->subtotal!=0?$this->erp->formatMoney($row->unit_price):$free;
					$amount=$row->subtotal!=0?$this->erp->formatMoney($row->subtotal):$free;
					if($i==20||$i==40||$i==60 || $i==70){
							$tablebody[$n_page].='<tr  style="border-bottom:solid 1px black;">
					<td>'.$i.'</td>
					<td>'.$product_name_setting.'</td>
					<td>'.$category_name.'</td>
					<td>'.number_format($row->quantity, 0).'</td>
					<td>'.$qty.'</td>
					<td>'.$amount.'</td>
							</tr>';
						
					}else{
					
							$tablebody[$n_page].='<tr>
					<td>'.$i.'</td>
					<td>'.$product_name_setting.'</td>
					<td>'. ($category_name == 'Others' ? '' : $category_name) .'</td>
					<td>'.number_format($row->quantity, 0).'</td>
					<td>'.$qty.'</td>
					<td>'.$amount.'</td>
							</tr>';
							}
					$total += $row->subtotal;
					$i++;
                    endforeach;
					$num_row=$i;
					$none_row=$n_page*20;
					
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
						}
						else{
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
				<!--
				<tr>
					<td colspan="3" style="border:0 !important;"></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>ប៉ុងចាស់ ' . ($old_invoice_balance_date ? "(" . $this->erp->hrsd($old_invoice_balance_date) . ")" : "") . '</b></td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">'. $this->erp->formatMoney($customer_balance) .' &nbsp;</b></td>
				</tr>
				-->
				<!--
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td  colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>ប្រាក់កក់ ' . ($inv->old_paid_date ? "(" . $this->erp->hrsd($inv->old_paid_date) . ")" : "" ) . '</b></td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">'.$paid.' &nbsp;</b></td>
				</tr>
				-->
				<!--
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td  colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>ទឹកប្រាក់សរុប </b></td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">'.$this->erp->formatMoney($grand_total).' &nbsp;</b></td>
				</tr>
				-->
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
				<!--
				<tr>
					<td colspan="3" style="border:0 !important;"></td>
					<td colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>ប៉ុងចាស់  </b></td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">&nbsp;</b></td>
				</tr>
				-->
				<!--
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td  colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>ប្រាក់បង់ </b></td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">&nbsp;</b></td>
				</tr>
				-->
				<!--
				<tr>
					<td colspan="3" style="border:0 !important;height:25px !important;"></td>
					<td  colspan="2" style="text-align:left;border:solid 1px black;height:25px !important;"><b>ទឹកប្រាក់សរុប</b></td>
					<td style="border:solid 1px black;text-align:right;height:25px !important;"><b style="font-size:12pt;font-family:Arial;">&nbsp;</b></td>
				</tr>
				-->
			'	;
			
$tableend='</table>';
		
$btm='<div style="margin-top:40px;"><table width="100%" >
					
					</tr>
						<td style="width:33.33%;border:0 !important;padding:10px;"><div style="font-family: Arial; border-top: dotted 1px #000 !important;padding-top: 5px;">អតិថិជន</div>
						<td style="width:33.33%;border:0 !important;padding:10px;"><div style="font-family: Arial; border-top: dotted 1px #000 !important;padding-top: 5px;">អ្នកលក់</div>
						<td style="width:33.33%;border:0 !important;padding:10px;"><div style="font-family: Arial; border-top: dotted 1px #000 !important;padding-top: 5px;">បានទទួលព្រមពី </div>
					</tr>
			</table></div>';							
?>
<?php
for($j=1;$j<=$n_page;$j++){
	echo '<div>'.$head.'</div><div>'.$table.''.$tablebody[$j];
		if($j==$n_page){
		echo $tablebtm.''.$tableend.'</div>'.$btm.'<div class="page-break"></div>';
		}
		else{
			echo $tablebtmnone.''.$tableend.'</div>'.$btm;
		}
}
?>
</body>

</html>










