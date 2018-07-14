<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iCloudERP - POS Page</title>
    <link href="<?= $assets ?>styles/theme.css" rel="stylesheet">
    <style> 
		a,a:focus,a:hover{color:#000}
		.btn-default,
		.btn-default:focus,
		.btn-default:hover{color:#333;text-shadow:none;background-color:#fff;border:1px solid #fff}
		body,html{height:100%;background-color:#FFF}
		body{color:#333;text-align:center;text-shadow:0 1px 3px rgba(0,0,0,.05)}
		.site-wrapper{
			display:table;
			width:100%;
			height:100%;
			min-height:100%;-webkit-box-shadow:inset 0 0 100px rgba(0,0,0,.5);
			border:1px solid #ddd;border-radius:10px;
		}
		.site-wrapper-inner{display:table-cell;vertical-align:top}
		.cover-container{margin-right:auto;margin-left:auto}
		.inner{padding:30px}
		.masthead-brand{margin-top:10px;margin-bottom:10px}
		.masthead-nav>li{display:inline-block}
		.masthead-nav>li+li{margin-left:20px}
		.masthead-nav>li>a{
			padding-right:0;
			padding-left:0;
			font-size:16px;
			font-weight:700;
			color:#333;color:rgba(255,255,255,.75);
			border-bottom:2px solid transparent
		}
		.masthead-nav>li>a:focus,
		.masthead-nav>li>a:hover{
			background-color:transparent;
			border-bottom-color:#a9a9a9;
			border-bottom-color:rgba(255,255,255,.25)
		}
		.masthead-nav>.active>a,.masthead-nav>.active>a:focus,
		.masthead-nav>.active>a:hover{color:#333;border-bottom-color:#fff}
		.cover{padding:0 20px; margin-top:20px;}
		.cover .btn-lg{padding:10px 20px;font-weight:700}
		.mastfoot{color:#ccc;color:#333}
		@media (min-width:320px) {
			.masthead{postion:fixed;top:0;}
			.mastfoot{position:fixed;bottom:0}
			.site-wrapper-inner{vertical-align:top}
			.cover-container,.mastfoot,.masthead{width:100%}
			.products{padding:10px;background-color:#F5F5F5;width:100%;}
			.products .span {padding:8px;float:left;border:1px solid #eee; margin:0 0 5px 0;background-color:#f0ad4e;}
			.products .span:hover{background-color:#EC971F;cursor:pointer;}
			.carousel-inner > .item > img,
			.carousel-inner > .item > a > img {
				width: 70%;
				margin: auto;
			}
			.carousel-inner > .item > img:hover,
			.carousel-inner > .item > a > img:hover {
				cursor:pointer;
			}
			.span img{width:100%;height:130px;}
			.img{width:100% !important; height:350px !important;}
			.marTop{
				padding-top:22%;
			}
		}
		@media (min-width:481px) {
			.masthead{postion:fixed;top:0;}
			.mastfoot{position:fixed;bottom:0}
			.site-wrapper-inner{vertical-align:top}
			.cover-container,.mastfoot,.masthead{width:100%}
			.products{padding:10px;background-color:#F5F5F5;width:100%;}
			.products .span {padding:8px;float:left;border:1px solid #eee; margin:0 0 5px 0;background-color:#f0ad4e;}
			.products .span:hover{background-color:#EC971F;cursor:pointer;}
			.carousel-inner > .item > img,
			.carousel-inner > .item > a > img {
				width: 70%;
				margin: auto;
			}
			.carousel-inner > .item > img:hover,
			.carousel-inner > .item > a > img:hover {
				cursor:pointer;
			}
			.span img{width:100%;height:100px;}
			.img{width:100% !important; height:auto !important;}
			.marTop{
				padding-top:70%;
			}
		}
		@media (min-width:641px) {
			.masthead{postion:fixed;top:0;}
			.mastfoot{position:fixed;bottom:0}
			.site-wrapper-inner{vertical-align:top}
			.cover-container,.mastfoot,.masthead{width:100%}
			.products{padding:10px;background-color:#F5F5F5;width:100%;}
			.products .span {padding:8px;float:left;border:1px solid #eee; margin:0 0 5px 0;background-color:#f0ad4e;}
			.products .span:hover{background-color:#EC971F;cursor:pointer;}
			.carousel-inner > .item > img,
			.carousel-inner > .item > a > img {
				width: 70%;
				margin: auto;
			}
			.carousel-inner > .item > img:hover,
			.carousel-inner > .item > a > img:hover {
				cursor:pointer;
			}
			.span img{width:100%;height:150px;}
			.img{width:100% !important; height:400px !important;}
			.marTop{
				padding-top:22%;
			}
		}
		@media (min-width:961px) {
			.masthead{postion:fixed;top:0;}
			.mastfoot{position:fixed;bottom:0}
			.site-wrapper-inner{vertical-align:top}
			.cover-container,.mastfoot,.masthead{width:100%}
			.products{padding:10px;background-color:#F5F5F5;width:100%;}
			.products .span {padding:8px;float:left;border:1px solid #eee; margin:0 0 5px 0;background-color:#f0ad4e;}
			.products .span:hover{background-color:#EC971F;cursor:pointer;}
			.carousel-inner > .item > img,
			.carousel-inner > .item > a > img {
				width: 70%;
				margin: auto;
			}
			.carousel-inner > .item > img:hover,
			.carousel-inner > .item > a > img:hover {
				cursor:pointer;
			}
			.span img{width:100%;height:130px;}
			.img{width:100% !important; height:350px !important;}
			.marTop{
				padding-top:22%;
			}
		}
		@media (min-width:1025px) {
			.masthead{postion:fixed;top:0;}
			.mastfoot{position:fixed;bottom:0}
			.site-wrapper-inner{vertical-align:top}
			.cover-container,.mastfoot,.masthead{width:100%}
			.products{padding:10px;background-color:#F5F5F5;width:100%;}
			.products .span {padding:8px;float:left;border:1px solid #eee; margin:0 0 5px 0;background-color:#f0ad4e;}
			.products .span:hover{background-color:#EC971F;cursor:pointer;}
			.carousel-inner > .item > img,
			.carousel-inner > .item > a > img {
				width: 70%;
				margin: auto;
			}
			.carousel-inner > .item > img:hover,
			.carousel-inner > .item > a > img:hover {
				cursor:pointer;
			}
			.span img{width:100%;height:200px;}
			.img{width:100% !important; height:765px !important;}
			.marTop{
				padding-top:22%;
			}
		}
		@media (min-width:1281px) {
			.masthead{postion:fixed;top:0;}
			.mastfoot{position:fixed;bottom:0}
			.site-wrapper-inner{vertical-align:top}
			.cover-container,.mastfoot,.masthead{width:100%}
			.products{padding:10px;background-color:#F5F5F5;width:100%;}
			.products .span {padding:8px;float:left;border:1px solid #eee; margin:0 0 5px 0;background-color:#f0ad4e;}
			.products .span:hover{background-color:#EC971F;cursor:pointer;}
			.carousel-inner > .item > img,
			.carousel-inner > .item > a > img {
				width: 70%;
				margin: auto;
			}
			.carousel-inner > .item > img:hover,
			.carousel-inner > .item > a > img:hover {
				cursor:pointer;
			}
			.span img{width:100%;height:130px;}
			.img{width:100% !important; height:830px !important;}
			.marTop{
				padding-top:22%;
			}
		}
    </style>
</head>

<body>
    <div class="site-wrapper">
        <div class="site-wrapper-inner">
            <div class="cover-container">
                <div class="masthead clearfix products"></div>
                <div class="inner cover">
                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
						<!-- Wrapper for slides -->
						<div class="carousel-inner" role="listbox">
							<?php
								$dir = "assets/uploads/gallary/";
								$allowed_types = array('png','jpg','jpeg','gif');
								if (is_dir($dir)) {
									if ($dh = opendir($dir)) {
										while (($file = readdir($dh)) !== false) {
											if( in_array(strtolower(substr($file,-3)),$allowed_types) OR
											in_array(strtolower(substr($file,-4)),$allowed_types) )
											{$a_img[] = $file;}
										}
										$totimg = count($a_img);
										for($x=0; $x < $totimg; $x++){
											//echo base_url(). $dir . $a_img[$x].'<br/>';
											echo '<div class="item">';
												echo '<img class="img" src="'.base_url(). $dir . $a_img[$x].'"/>';
											echo '</div>';
										}
										closedir($dh);
									}
								}
							?>
						</div>
						<!-- Left and right controls -->
						<a class="left marTop carousel-control" href="#myCarousel" role="button" data-slide="prev">
							<span class="fa fa-angle-left fa-3x" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="right marTop carousel-control" href="#myCarousel" role="button" data-slide="next">
							<span class="fa fa-angle-right fa-3x" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?= $assets ?>js/jquery.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
	<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification); ?>
	<script type="text/javascript">var site = <?= json_encode(array('base_url' => base_url(), 'settings' => $Settings, 'dateFormats' => $dateFormats)) ?>, pos_settings = <?= json_encode($pos_settings); ?>;</script>

	<script type="text/javascript">
		$(document).ready(function () {
			loadItems();
			$('.carousel').carousel({
				interval: 8000
			});
			$('#myCarousel').find('.item').first().addClass('active');
			window.setInterval(function () {
				loadItems();
			}, 1000);
		});
		function loadItems() {

			if (__getItem('positems')) {
				total = 0;
				count = 1;
				an = 1;
				
				$(".products").empty();
				positems = JSON.parse(__getItem('positems'));
				$.each(positems, function () {
					var item = this;
					var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
					positems[item_id] = item;

					var product_id = item.row.id, item_type = item.row.type, combo_items = item.combo_items, picture = item.row.image, item_price = item.row.price, item_qty = item.row.qty, item_aqty = item.row.quantity, item_tax_method = item.row.tax_method, item_ds = item.row.discount, item_discount = 0, item_option = item.row.option, item_code = item.row.code, item_serial = item.row.serial, item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");

					var unit_price = item.row.real_unit_price;

					var ds = item_ds ? item_ds : '0';                
					
					var imgs = $('<div class="span col-lg-2 col-md-2 col-xs-2 col-sm-2"></div>');
					var newImage = '<img src="'+site.base_url+'assets/uploads/'+picture+'" alt="' +item_name+ '" />';
					imgs.html(newImage); 
					imgs.prependTo(".products");
					/*
					var slide = $('<div class="item"></div>');
					var slider = '<img src="'+site.base_url+'assets/uploads/'+picture+'" alt="' +item_name+ '" style="width:320px; height:350px;" />';
					slide.html(slider); 
					slide.prependTo(".carousel-inner");
					*/
					an++;
				});

			} else {
				
			}
		}
	   
	</script>
</body>
</html>
