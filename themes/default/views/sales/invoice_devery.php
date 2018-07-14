<!doctype>
<html>
	<head>
		<title>RULE</title>
		<meta charset="utf-8">
		<style>
			body {
    margin: 0;
    padding: 0;
    background-color: #FAFAFA;
    font: 12pt "Tahoma";
}
* {
    box-sizing: border-box;
    -moz-box-sizing: border-box;
}
.page {
    width: 21cm;
    min-height: 29.7cm;
    /*padding: 2cm;*/
    margin: 1cm auto;
    border: 1px #D3D3D3 solid;
    /*border-radius: 5px;*/
    background: white;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}
.subpage {
    padding: 1cm;
    border: 5px red solid;
    height: 256mm;
    outline: 2cm #FFEAEA solid;
}

@page {
    size: A4;
    margin: 0;
}
@media print {
    .page {
        margin: 0;
        border: initial;
        border-radius: initial;
        width: initial;
        min-height: initial;
        box-shadow: initial;
        background: initial;
        page-break-after: always;
    }
    .left_ch .fon{
    	color: rgba(0, 0, 0, 0.3) !important;

    }
    #print{
    	display: none !important;
    }
}
			#print{
				
				width:60px;
				height:45px;
				border:0px;
				background: #4169E1;
				color:#fff;
				cursor:pointer;
				-webkit-box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.75);
box-shadow: 0px 4px 5px 0px rgba(0,0,0,0.75);
			}
			#body,h2,h3,h4,h5,p{
				margin:0px;
				padding:5px;
				
			}
			
			#body{
				width:95%;
				height:100%;
				margin:0 auto;
				background:#F0F8FF;
				
				
			}		

		
			#top{
				width:95%;
				height:100px;
				margin:0 auto;
				padding-top:20px;
			}
			#top_l{
				width:220px;
				float:left;
			}
			#top_r{
				width:200px;
				float:right;
				text-align:center;
			}
			h1,h2,h3,h4{
				font-family:"Khmer OS Muol";
			}
			
			p{
				font-size:12px;
				font-family:"Arial Narrow";
			}
			#top2{
				width:95%;
				margin:0 auto;
				text-align:center;
				height:430px;
				
				margin-bottom:10px;
			}
			#top2_l{
				width:37%;
				margin:0 auto;
				text-align:center;
				
				float:left;
				height:5px;
			}
			#top2_c{
				width:30%;
				margin:0 auto;
				text-align:center;
				height:65px;
				
				float:left;
			}
			#top2_r{
				width:30%;
				margin:0 auto;
				text-align:center;
				
				float:left;
				height:65px;
			}
			#top2 h5{
				font-family:"Khmer OS Muol";
			}
			#tb tr th{
				font-size:13px;
				font-weight: bold;
				padding:5px;
				font-family:"Arial Narrow";
				 font-weight: bold;
			}
			#tb tr td{
					font-size:12px;
					padding:4px;
					font-family:"Arial Narrow";
				}
				#tb {
					width:98%;
					margin:0 auto;
				}
				#foot{
				width:100%;
				height:50px;
				margin-top: -25px;
				background:#F0F8FF;	
			}		
			#tb2 tr td,#tb3 tr td{
				font-size:15px;
				border-radius:20px;
				font-family:"Arial Narrow";
				text-align:left;
				padding-left:10px;
			}
			
		</style>
	</head>
	<body>
	<!-- <div class="page"> -->
	<div id="body">	
	<button id="print" onclick="window.print()">
		<img src="<?= base_url() . 'assets/uploads/printer.png'; ?>">
	</button>
		<div class="left_ch" style="
		width:700px;
		text-align:center;
		 position: absolute;
    left: 150px;
    top: 560px;
		z-index:1;
	-webkit-transform: rotate(350deg);
-moz-transform: rotate(350deg);
-o-transform: rotate(350deg);
writing-mode: lr-tb;
		">
			<span class="fon" style="font-size:40px;font-family:Khmer OS;
color: rgba(0, 0, 0, 0.3);
			" >វិក័យប័ត្រនេះមិនអាចប្រកាសជា<br>បន្ទុកចំណាយបានទេ</span>
		</div>
		
		<div id="top2">
			<!--<h1>ខេ អិន អិន លក់ រថយន្ដ និង គ្រឿងចក្រ</h1>
			<h1>KNN Cambodia Co., Ltd (KNN Group)</h1>-->
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<h2>វិក័យប័ត្រ</h2>
			<h2>Invoice</h2>
			<br>
			<div style="float:left; width:50%;border-radius: 10px 10px 10px 10px;
-moz-border-radius: 10px 10px 10px 10px;
-webkit-border-radius: 10px 10px 10px 10px;
border: 1px solid #000000;">
				<table id="tb2" style=" width:100%;">
					<tr>
						<td>Customer</td>
						<td>: <?=$invs->customer;?></td>
					</tr>
					<tr>
						<td>Address</td>
						<td>: <?=$invs->group_area;?></td>
					</tr>
					<tr>
						<td>Tel</td>
						<td>: <?=$invs->phone;?></td>
					</tr>
					<tr>
						<td>Model</td>
						<td>: </td>
					</tr>
					<tr>
						<td>Time</td>
						<td>: <?= date('H:II')?></td>
					</tr>
				</table>
			</div>
			<div style="float:right; width:40%;border-radius: 10px 10px 10px 10px;
-moz-border-radius: 10px 10px 10px 10px;
-webkit-border-radius: 10px 10px 10px 10px;
border: 1px solid #000000;">
				<table id="tb3" style=" width:100%;">
					<tr>
						<td>From</td>
						<td>: <?=$bill->biller;?></td>
					</tr>
					<tr>
						<td>Date</td>
						<td>: <?=$this->erp->hrsd($bill->date);?></td>
					</tr>
					<!--<tr>
						<td>lnv. No</td>
						<td>: <?=$bill->reference_no;?></td>
					</tr>
					<tr>
						<td>VAT</td>
						<td>:  <?=$bill->vat_no;?></td>
					</tr>
					<tr>
						<td>Division</td>
						<td>: </td>
					</tr>-->
				</table>
			</div>
			
			</div>
			
			<table id="tb" style="border-collapse: collapse;text-align:center;" border="1" >
				<tr>
					<th style="text-align:center; width:70px;">Item/ប្រការ</th>
					<th style="text-align:center; width:220px;">Delivery Date/ថ្ងៃបញ្ចេញទំនិញ</th>
					<th style="text-align:center;​ width:180px;">Typer of Concrete /ប្រភេទ</th>
					<th style="text-align:center;">Quantity/ចំនួន</th>
					<th style="text-align:center;">Unit Price/តំលៃរាយ</th>
					<th style="text-align:center; width: 130px;">Amount/តំលៃសរុប</th>
					
				</tr>

				<?php 
				$i = 1;
				$stotal = 0;
				$tqty = 0;

					foreach($rows as $row){
						$unit_price = $this->sales_model->getSaleByDeliveryID2($idd,$row->product_id);
						
					?>
					<tr>
						<td><?=$i?></td>
						<td><?=$this->erp->hrsd($row->date);?></td>
						<td style="text-align:left;"><?=$row->product_name?></td>
						<td><?=$this->erp->formatDecimal($row->quantity_received);?></td>
						<td><?=$this->erp->formatMoney($unit_price->unit_price)?> $</td>
						<td style="text-align:right;"><?=$this->erp->formatMoney($row->quantity_received*$unit_price->unit_price)?> $</td>	
						</tr>
						<?php
						$i++;
						$tqty +=$row->quantity_received;
						$stotal +=$row->quantity_received*$unit_price->unit_price;
						}
						for($k = 0;$k<12;$k++){
								?>
							<tr class="blank">
								<td><?=$i?></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td style="text-align:right;">$</td>
							</tr>
							
							<?php 
							$i++;
					}
					?>
				<tr>
				
					<td colspan="2" rowspan="3" style="text-align:left; font-size: 11px;">បញ្ជាក់៖<br><?php echo nl2br($bill->invoice)?></td>
					<td  style="text-align:right;">សរុប/Sub Total</td>
					<td ><?=$this->erp->formatDecimal($tqty);?></td>
					<td></td>
					
					<td  style="text-align:right;"><?=$this->erp->formatMoney($stotal);?> $</td>
					
				</tr>
				<tr>
					<td  colspan="3" style="text-align:right;​">ចំនួនទឹកប្រាក់ដែលមិនទាន់បានបង់ពីមុន/Unpaid previous period  USD</td>
					<td   ></td>
					
				</tr>
				<tr>
					<td  colspan="3"​ style="text-align:right;" >ចំនួនទឹកប្រាក់សរុប/Grand total  USD</td>
					<td   style="text-align:right;" ><?=$this->erp->formatMoney($stotal);?> $</td>
					
				</tr>
			
			</table>
			<p>Remark: The invoice above is excluding 10% VAT.</p>
			<p>ផ្សេងៗ៖ តំលៃខាងលើមិនបូករួមពន្ធ 10% នៃ VAT</p>
			<br>
			<div id="foot">
			<div id="top2_l">
				<p>ហត្ថលេខាអ្នកទទួល</p>
				<p>Received By :</p>
			</div>
			
			<!--<div id="top2_c">
				<p>បានពិនិត្យ ត្រឹមត្រូវ</p>
				<p>ប្រធានការិយាល័យគណនេយ្យ</p>
			</div>
			<div id="top2_c">
				<p>អ្នកគ្រប់គ្រងស្ដុក</p>
				
			</div>-->
			<div id="top2_c">
			<p>អនុម័តដោយ</p>
				<p>Approved By :</p>
				
			</div>
			<div id="top2_r">
				<p>គណនេយ្យ</p>
				<p>Accountant By:</p>
			</div>
			</div>
		</div>
		<!-- </div> -->
	</div>
	</body>
</html>