<!DOCTYPE html>
<html lang="en">
<head>
  <title>Purchas Journal Form</title>
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
	<div><center><h4><b style="font-family:'Khmer OS Muol'">ទិន្នានុប្បវត្តិទិញ</b></h4>
							<h6><b>PURCHASE JOURNAL</b></h6>
							<h6><b style="font-family:'Khmer OS Muol'">សំរាប់ខែ  <?=$this->erp->KhmerMonth($purc_list->row()->monthly);?> ឆ្នាំ <?=$purc_list->row()->yearly?></b></h6>
							<h6><b>For <?php $dateObj   = DateTime::createFromFormat('!m', $purc_list->row()->monthly);$monthName = $dateObj->format('F');echo $monthName ;?> <?=$purc_list->row()->yearly?></b></h6>
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
							<p>អត្រា 	:<b><?=$this->erp->formatMoney($exchange_rate->rate);?></b></p>
							<p>Rate 	:<b><?=$this->erp->formatMoney($exchange_rate->rate);?></b></p>
				</div>
	</div>
</div>
<div class="col-md-12 col-lg-12 row">
<table class="table table-bordered">
    <tr>
        <td style="text-align:center !important"   colspan="6">វិក័យប័ត្រ<br>Invoice    </td>
        <td style="text-align:center !important"   colspan="6">ការផ្គត់ផ្គង់<br>Supplies    </td>
    </tr>
    <tr>
        <td style="text-align:center !important;vertical-align: middle !important"   rowspan="3">ថ្ងៃទី<br>Date</td>
        <td style="text-align:center !important;vertical-align: middle !important"   rowspan="3">លេខវិក័យប័ត្រ<br>Invoice n<sup>o</sup></td>
        <td style="text-align:center !important;vertical-align: middle !important"   rowspan="3">អ្នកទិញ<br>Client</td>
        <td style="text-align:center !important;vertical-align: middle !important"   rowspan="3">លេខ អ ត ប<br>VAT Tin    </td>
        <td style="text-align:center !important;vertical-align: middle !important"   rowspan="3">បរិយាយ<br>Description    </td>
        <td style="text-align:center !important;vertical-align: middle !important"   rowspan="3">បរិមាណ<br>Qty    </td>
        <td style="text-align:center !important;vertical-align: middle !important"   rowspan="3">ការទិញមិនជាប់អាករ<br>Non-taxable Purchase    </td>
        <td style="text-align:center !important;vertical-align: middle !important"   colspan="4">ការទិញជាប់អាករ<br>Taxable Purchase    </td>
        <td style="text-align:center !important;vertical-align: middle !important"   rowspan="3">សរុបតម្លៃទិញរួមទាំងអាករ<br>Total Value Including VAT    </td>
    </tr>
    <tr>
        <td style="text-align:center !important"   colspan="2">ការនាំចូល<br>Imports    </td>
        <td style="text-align:center !important"   colspan="2">ការទិញក្នុងស្រុក<br>Local Purchases    </td>
    </tr>
    <tr>
        <td style="text-align:center !important"  >តម្លៃជាប់អាករ<br>Taxable Value</td>
        <td style="text-align:center !important"  >អាករ<br>VAT    </td>
        <td style="text-align:center !important"  >តម្លៃជាប់អាករ<br>Taxable Value    </td>
        <td style="text-align:center !important"  >អាករ<br>VAT    </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align:center !important"  >រៀល<br>Riel</td>
        <td style="text-align:center !important"  >រៀល<br>Riel</td>
        <td style="text-align:center !important"  >រៀល<br>Riel</td>
        <td style="text-align:center !important"  >រៀល<br>Riel</td>
        <td style="text-align:center !important"  >រៀល<br>Riel</td>
        <td style="text-align:center !important"  >រៀល<br>Riel</td>
    </tr>
    <tr>
        <td style="text-align:center !important"  >P1</td>
        <td style="text-align:center !important"  >P2</td>
        <td style="text-align:center !important"  >P3</td>
        <td style="text-align:center !important"  >P4</td>
        <td style="text-align:center !important"  >P5</td>
        <td style="text-align:center !important"  >P6</td>
        <td style="text-align:center !important"  >P7</td>
        <td style="text-align:center !important"  >P8</td>
        <td style="text-align:center !important"  >P9</td>
        <td style="text-align:center !important"  >P10</td>
        <td style="text-align:center !important"  >P11</td>
        <td style="text-align:center !important"  >P13=sum(P7:P11)</td>
    </tr>
	<?php
	  $i=1;
	  $S7=0;
	  $S8=0;
	  $S9=0;
	  $S10=0;
	  $S11=0;
	  $S13=0;
	  $G13=0;
	  foreach($purc_list->result() as $row){
	  $S13 += $row->non_tax_pur + $row->tax_value + $row->vat + ($row->amount*$exchange_rate->rate)+($row->amount_tax*$exchange_rate->rate);
	  $S7  += $row->non_tax_pur;
	  $S8  += ($row->amount*$exchange_rate->rate);
	  $S9  += ($row->amount_tax*$exchange_rate->rate);
	  $S10 += $row->tax_value;
	  $S11 += $row->vat;
	  $G13 +=  $S13;
	  
	?>
	<tr>
       
        <td style="text-align:center !important"  ><?=$row->issuedate;?></td>
        <td style="text-align:center !important"  ><?=$row->reference_no;?></td>
        <td style="text-align:center !important"  ><?=$row->name;?></td>
        <td style="text-align:center !important"  ><?=$row->vatin;?></td>
        <td style="text-align:center !important"  ><?=$row->description;?></td>
        <td style="text-align:center !important"  ><?=$row->qty;?></td>
        <td style="text-align:right !important"  ><?=$this->erp->formatMoney($row->non_tax_pur);?></td>
        <td style="text-align:right !important"   ><?=$this->erp->formatMoney(($row->amount*$exchange_rate->rate));?></td>
        <td style="text-align:right !important"   ><?=$this->erp->formatMoney(($row->amount_tax*$exchange_rate->rate));?></td>
        <td style="text-align:right !important"  ><?=$this->erp->formatMoney($row->tax_value);?></td>
        <td style="text-align:right !important"  ><?=$this->erp->formatMoney($row->vat);?></td>
		<td style="text-align:right !important"   ><?=$this->erp->formatMoney($S13);?></td>
    </tr>
	<?php $i++;}?>
    <tr>
        <td style="text-align:right !important"  colspan="6">សរុបទិញជារៀល<br>Total Purchase in KHR</td>
       
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($S7)?></td>
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($S8)?></td>
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($S9)?></td>
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($S10)?></td>
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($S11)?></td>
        <td style="text-align:right !important" ><?=$this->erp->formatMoney($G13)?></td>
    </tr>
    <tr  id="hide-border">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Box06</td>
        <td>Box09</td>
        <td>Box10</td>
        <td>Box07</td>
        <td>Box08</td>
        <td></td>
    </tr>
</table>
</div>
<div>
<div class="text" style="float:right;padding-right:30px;">
<p>
<?php $loc=explode("|",$purc_list->row()->journal_location); ?>
ធ្វើនៅ <?=$loc[0]?> ថ្ងៃទី  <?php $a=explode("-",$purc_list->row()->journal_date); echo $this->erp->KhmerNumDate($a[2]);?>ខែ <?=$this->erp->KhmerMonth($a[1]);?> ឆ្នាំ <?=$a[0];?></p>
<p>Field In <?=$loc[1]."&nbsp;&nbsp;,".$a[2]." &nbsp;".$monthName." &nbsp;".$a[0]?> </p>
  	<p>ហត្ថលេខា និង ត្រា Signature & Stamp</p>
</div>
</div>
</div>
</body>
</html>