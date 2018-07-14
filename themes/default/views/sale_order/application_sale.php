<!DOCTYPE html>
<html>
<head>
	<title>Application Home sale</title>
	<meta charset="UTF-8">
	 <link href="<?php echo $assets ?>styles/theme.css" rel="stylesheet">
	<style type="text/css" >
		.moul{
		  font-family: Khmer OS Muol Light;
		}
		p,li{
		  font-family: Battambang;
		   line-height: 2.1;
		   font-size: 17px;
		}
		li{
		  text-indent: -2em;
		}
		footer {
		  font-size: 9px;
		}
		@media print {
		  footer {
			position: fixed;
			bottom: 0;
		  }
	</style>
<body>
	<div class='container'>
			<div class="col-lg-12">
				<h2 class='text-center moul'><b>ពាក្យស្នើរសុំទិញ-លក់ផ្ទះ</b></h2><br><br>
			</div>
			<div class="col-lg-10 col-lg-offset-2">
			<button type="button" class="btn btn-primary">Default</button>
				<p>ធ្វើនៅថ្ងៃទី <?php echo $date_day ?> ខែ <?php echo $date_month ?> ឆ្នាំ <?php echo $date_year ?></p>
				<p>១. អ្នកទិញ : លោក/លោកស្រី 
				<?php
					if($customer->name_kh){
						echo $customer->name_kh;
					}else{
						echo $inv->customer;
					}
				?> 
				អត្ត/ប័ណ្ណ 
				<?php
				if($customer->cf1 == null){
					echo "N/A";
				}else{
					echo $customer->cf1;
				}	 
				?> 
				និងឈ្មោះ…………………….</p>
				<p>អត្ត/ប័ណ្ណ………………………………. អាស័យដ្ឋានផ្ទះលេខ 
				<?php 
				if($customer->address == null){
					echo "N/A";
				}else{
					echo $customer->address;
				}
					
				?>
				ផ្លូវលេខ 
				<?php
				if($customer->street == null){
					echo "N/A";
				}else{
					echo $customer->street;
				}
				?>
				ភូមិ <?php
				if($customer->village == null){
					echo "N/A";
				}else{
					echo $customer->village;
				}		 
				?></p>
				<p>ឃុំ/សង្កាត់ <?php
				if($customer->sangkat == null){
					echo "N/A";
				}else{
					echo $customer->sangkat;
				}
				?> ស្រុក/ខ័ណ្ឌ <?php
				if($customer->district  == null){
					echo "N/A";
				}else{
					echo $customer->district ;
				}
				?> ខេត្ត/រាជធានី  <?php
				if($customer->city){
					echo $customer->city;
				}else{
					echo $customer->state;
				}
				?> </p>
				<p>២. ការទិញលក់: ប្រភេទផ្ទះ………………..……..………ដែលមានទទឹង……..…………….ម៉ែត្រ, បណ្ដោយ………………….ម៉ែត្រ</p>
				<p>ផ្ទះលេខ….………..ផ្លូវលេខ……………………….ទីតាំង………………………….រយះពេលសាងសង់………………………..ខែ</p>
				<p>តម្លៃដើម………………………US$ បញ្ចុះតម្លៃ…………….…………………US$ តម្លៃលក់……….…..……………………….US$</p>
				<p>តាមរយ:……………………….………បរិយាយ…………………………………………………………………………………….</p>
				<p>………………………………………………………………………………………………………………………………………………</p>
				<form action="" method="get">
					<p><span>៣. គោលការណ៍បង់ :    </span>			
					<input type="checkbox" name="" value="1"> ដំណាក់កាល………………….
					<input type="checkbox" name="" value="2"> ផ្ដាច់………………….
					<input type="checkbox" name="" value="3"> រំលស់…………………………………………….</p>
				</form>
				<p>- ប្រាក់កក់ដំបូង………….……$ ថ្ងៃទី……ខែ…….ឆ្នាំ……… - លើកទី៤……..………………$ ថ្ងៃទី……ខែ…….ឆ្នាំ….....………</p>
				<p>- លើកទី១ ………….…………$ ថ្ងៃទី……ខែ…….ឆ្នាំ……… - លើកទី៥……..………………$ ថ្ងៃទី……ខែ…….ឆ្នាំ….....………</p>
				<p>- លើកទី២ ………….…………$ ថ្ងៃទី……ខែ…….ឆ្នាំ……… - លើកទី៦……..………………$ ថ្ងៃទី……ខែ…….ឆ្នាំ….....………</p>
				<p>- លើកទី៣ ………….…………$ ថ្ងៃទី……ខែ…….ឆ្នាំ……… - លើកទី៧……..………………$ ថ្ងៃទី……ខែ…….ឆ្នាំ….....………</p>
				<p>**បង់ផ្ដាច់………….…………..$ ក្រោយពេលផ្ទះសាងសង់រួចរាល់</p>
				<p>**បង់ដំណាក់កាល………………………....$ ក្រោយពេលផ្ទះសាងសង់រួចរាល់ ដោយគិតចាប់ពីថ្ងៃទី……..ខែ………ឆ្នាំ…………….</p>
				<p>**រំលស់……………………...$សំរាប់រយះពេល…………ឆ្នាំ(…………..ខែ) ដោយគិតចាប់ពីថ្ងៃទី……..ខែ………ឆ្នាំ……………...</p>
				<div class='row col-lg-8'>
					<p><span class='pull-left'>អ្នកទិញ</span>
					<span style='margin-left: 20%'>អ្នកលក់</span>
					<span style='margin-left: 30%'>អ្នកពិនិត្យ</span>
					<span class='pull-right'>អ្នកអនុញ្ញាត</span></p>
				</div><br><br><br><br><br>
				<p>ឈ្មោះ…………………… ឈ្មោះ………..…………. ឈ្មោះ…………..………. ឈ្មោះ………………..…. ឈ្មោះ………………..</p>
				<p>ទូរស័ព្ទ………………… ទូរស័ព្ទ…………………… ទូរស័ព្ទ…………………… ទូរស័ព្ទ…………………… ទូរស័ព្ទ………………</p>
				<p>ថ្ងៃទី…………………… ថ្ងៃទី……………………… ថ្ងៃទី……………………… ថ្ងៃទី……………………… ថ្ងៃទី………………….</p>
				<p><u>បញ្ជាក់</u> : ប្រាក់ដែលកក់រួច មិនអាចដកវិញបានទេ ។</p>
				<p​ style='margin-left: 45px'>ប្រសិនបើអ្នកទិញមិនបានបង់ប្រាក់បន្ថែមតាមកំណត់នោះ​ ប្រាក់កក់នឹងចាត់ទុកជាមោឃៈដោយស្វ័យប្រវិិត្ត</p>
				<br>
				<div style="font-size: 12px;color:#006666">
					<span>បុរី អាទាំងមាស​ ( គំរោងឌឹផ្លរ៉ា )</span><br>
					<span>ការិយាល័យកណ្ដាល: សង្កាត់បាក់ខែង ខ័ណ្ឌជ្រោយចង្វា រាជធានីភ្នំពេញ</span><br>
					<span>ទូរស័ព្ទលេខ: 061 77 67 67 / 097 777 0678 គេហទំព័រ : www.boreytheflora.com</span>
				</div>
			</div>
	</div>
</body>
</html>