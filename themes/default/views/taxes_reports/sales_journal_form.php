<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sales Journal</title>
  <meta charset="utf-8">
  <meta http-equiv="cache-control" content="max-age=0"/>
  <meta http-equiv="cache-control" content="no-cache"/>
  <meta http-equiv="expires" content="0"/>
  <meta http-equiv="pragma" content="no-cache"/>
  <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<style>td,th{padding:4px !important;}#hide-border td{border: 0px  !important;} .table{border:none !important;}body{font-family: Khmer OS System,Nida Sowanaphum;}.text{font-size:12px;line-height: 80%;} table{font-size:11px;}</style>
<style type="text/css">
@media print
{
body * { visibility: hidden; }
#printcontent * { visibility: visible; }
#printcontent { position: absolute; top: 40px; left: 30px; }
}
</style>
<body>
<div class="col-md-12" id="printcontent">
<br/>

<div class="row print">
	<div><center><h4><b style="font-family:'Khmer OS Muol'">ទិន្នានុប្បវត្តិលក់</b></h4>
							<h6><b>SALES JOURNAL</b></h6>
							<h6><b style="font-family:'Khmer OS Muol'">សំរាប់ខែ <?=$this->erp->KhmerMonth($sales_list->row()->monthly);?> ឆ្នាំ <?=$sales_list->row()->yearly?></b></h6>
							<h6><b>For <?php $dateObj   = DateTime::createFromFormat('!m', $sales_list->row()->monthly);$monthName = $dateObj->format('F');echo $monthName ;?> <?=$sales_list->row()->yearly?></b></h6>
				</center>
				<div class="col-md-10 col-xs-10 text">
					<table>
						<tr>
						
							<td><p>នាមករណ័សហគ្រាស 	<td>:<b><?=$company->cf1?></b></p>
						<tr>	
							<td><p>Company Name 	<td>: <b><?=$company->company?></b></p>
						<tr>
						<td><p>លេខអត្តសញ្ញាណកម្មអតប 	<td>:<b><?=$this->erp->KhmerNumDate($company->vat_no)?></b></p>
						<tr>
						<td><p>VAT TIN 	<td>:<b><?=$company->vat_no?></b></p>
						<tr>
						<td><p>អាស័យដ្ឋាន 	<td>:<b> <?=$company->cf4?></b></p>
						<tr>
						<td><p>Address 	<td>:<b><?=$company->address." ,".$company->state." ,".$company->country?></b> </p>
					</table>
				</div>
				<div class="col-md-2 col-xs-2 text">
							<p>អត្រា 	:<b><?=$this->erp->formatMoney($exchange_rate->rate);?></b> 	 </p>
							<p>Rate 	:<b><?=$this->erp->formatMoney($exchange_rate->rate);?></b>	</p>
				</div>
	</div>
</div>
<div class="col-md-12 col-lg-12 row">
<table class="table table-bordered">
    <tr>
        <td style="vertical-align: middle !important" rowspan="5">N<sup>o</sup></td>
		<th style="text-align:center !important" colspan="6">វិក័យប័ត្រ<br>Invoice</th>
        <th style="text-align:center !important" colspan="7">ការផ្គត់ផ្គង់<br>Supplies</th>	
    </tr>
    <tr>
        <td style="text-align:center !important;vertical-align: middle !important"  rowspan="3">ថ្ងៃទី<br>Date</td>
        <td  style="text-align:center !important;vertical-align: middle !important" rowspan="3">លេខវិក័យប័ត្រ<br>Invoicen<sup>o</sup> <br></td>
        <td style="text-align:center !important;vertical-align: middle !important"  rowspan="3">អ្នកទិញ<br>Client</td>
        <td  style="text-align:center !important;vertical-align: middle !important" rowspan="3">លេខអតប<br>VAT Tin</td>
        <td style="text-align:center !important;vertical-align: middle !important"  rowspan="3">បរិយាយ<br>Description<br></td>
        <td style="text-align:center !important;vertical-align: middle !important"  rowspan="3">បរិមាណ<br>Qty<br></td>
        <td style="text-align:center !important;vertical-align: middle !important"  rowspan="3">រផ្គត់ផ្គង់មិនជាប់អាករ<br>Non-taxable sale<br></td>
        <td style="text-align:center !important;vertical-align: middle !important"  rowspan="3">ការនាំចេញ<br>Value of Exports<br></td>
        <td style="text-align:center !important;vertical-align: middle !important"   colspan="4">ការលក់ជាប់អាករ<br>Taxable sale</td>
        <td style="text-align:center !important;vertical-align: middle !important"  rowspan="3">សរុបតម្លៃលក់រួមទាំងអាករ<br>Total Taxable Value</td>
    </tr>
    <tr>
        <td colspan="2">លក់អោយបុគ្គលជាប់អាករ<br>Sales to Taxable Persons</td>
        <td colspan="2">លក់អោយអ្នកប្រើប្រាស់<br>Sales to Customers</td>
    </tr>
    <tr>
        <td  style="text-align:center !important"  >តម្លៃជាប់អាករ<br>Taxable Value</td>
        <td style="text-align:center !important"  >អាករ<br>VAT<br></td>
        <td  style="text-align:center !important" >តម្លៃជាប់អាករ<br>Taxable Value</td>
        <td  style="text-align:center !important" >អាករ<br>VAT</td>
    </tr>
    <tr>
        <td style="text-align:center !important"  >S1</td>
        <td style="text-align:center !important"  >S2</td>
        <td style="text-align:center !important"  >S3</td>
        <td style="text-align:center !important"  >S4</td>
        <td style="text-align:center !important"  >S5</td>
        <td style="text-align:center !important"  >S6</td>
        <td style="text-align:center !important"  >S7</td>
        <td style="text-align:center !important"  >S8</td>
        <td style="text-align:center !important"  >S9</td>
        <td style="text-align:center !important"  >S10</td>
        <td style="text-align:center !important"  >S11</td>
        <td style="text-align:center !important"  >S12</td>
        <td style="text-align:center !important"  >S13=sum(S7:S12)       </td>
    </tr>
	
	<?php
	  $i=1;
	  $S7=0;
	  $S8=0;
	  $S9=0;
	  $S10=0;
	  $S11=0;
	  $S12=0;
	  $S13=0;
	  $G13=0;
	  foreach($sales_list->result() as $row){
	  $S13 = $row->non_tax_sale + $row->value_export + ($row->amound*$exchange_rate->rate) + ($row->amound_tax*$exchange_rate->rate) + $row->tax_value + $row->vat;
	  $S7  += $row->non_tax_sale;
	  $S8  += $row->value_export;
	  $S9  += ($row->amound*$exchange_rate->rate);
	  $S10 += ($row->amound_tax*$exchange_rate->rate);
	  $S11 += $row->tax_value;
	  $S12 += $row->vat;
	  $G13 +=  $S13;
	?>
	<tr>
        <td style="text-align:center !important" ><?=$i;?></td>
        <td style="text-align:center !important" ><?=$row->issuedate;?></td>
        <td style="text-align:center !important" ><?=$row->referent_no;?></td>
        <td style="text-align:center !important" ><?=$row->customer_id;?></td>
        <td style="text-align:center !important" ><?=$row->vatin;?></td>
        <td style="text-align:center !important" ><?=$row->description;?></td>
        <td style="text-align:center !important" ><?=$row->sale_id == 0 ? $row->qty : $row->total_items;?></td>
        <td style="text-align:right !important"  ><?=$this->erp->formatMoney($row->non_tax_sale);?></td>
        <td style="text-align:right !important"  ><?=$this->erp->formatMoney($row->value_export);?></td>
        <td style="text-align:right !important"  ><?=$this->erp->formatMoney(($row->amound*$exchange_rate->rate));?></td>
        <td style="text-align:right !important"  ><?=$this->erp->formatMoney(($row->amound_tax*$exchange_rate->rate));?></td>
        <td style="text-align:right !important"  ><?=$this->erp->formatMoney($row->tax_value);?></td>
        <td style="text-align:right !important"  ><?=$this->erp->formatMoney($row->vat);?></td>
		<td style="text-align:right !important"  ><?=$this->erp->formatMoney($S13);?></td>
    </tr>
	<?php $i++;}?>
    <tr>
        <td  style="text-align:right !important"  colspan="7">សរុបលក់ជារៀល<br>Total Sale in KHR</td>
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($S7)?></td>
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($S8)?></td>
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($S9)?></td>
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($S10)?></td>
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($S11)?></td>
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($S12)?></td>
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($G13)?></td>
    </tr>
	<tr id="hide-border">
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td></td>
		<td style="text-align:center !important"  >Box12</td>
		<td style="text-align:center !important"  >Box13</td>
		<td style="text-align:center !important"  >Box14</td>
		<td style="text-align:center !important"  >Box15</td>
		<td style="text-align:center !important"  >Box14</td>
		<td style="text-align:center !important"  >Box15</td>
		<td></td>	
	</tr>
</table>
</div>
<div>
<div class="text" style="float:right;padding-right:30px;">
<p>
<?php $loc=explode("|",$sales_list->row()->journal_location); ?>
ធ្វើនៅ <?=$loc[0]?>ថ្ងៃទី  <?php $a=explode("-",$sales_list->row()->journal_date); echo $this->erp->KhmerNumDate($a[2]);?>ខែ <?=$this->erp->KhmerMonth($a[1]);?> ឆ្នាំ <?=$a[0];?></p>
<p>Field In <?=$loc[1]."&nbsp;&nbsp;,".$a[2]." &nbsp;".$monthName." &nbsp;".$a[0]?> </p>
  	<p>ហត្ថលេខា និង ត្រា Signature & Stamp</p>
</div>
</div>
</div>
</body>
</html>