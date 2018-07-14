
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title>
        <?= $page_title ?>
            <?= $Settings->site_name ?>
    </title>
    <link rel="shortcut icon" href="<?= $assets ?>images/icon.png" />
    <link href="<?= $assets ?>styles/theme.css" rel="stylesheet" />
    <link href="<?= $assets ?>styles/style.css" rel="stylesheet" />
    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?= $assets ?>js/jquery.js"></script>
    <![endif]-->

    <script>

        function dd(e) {
            console.log(e);
        }

        var __c = '<?= $this->router->fetch_class() ?>';
        var __f = '<?= $this->router->fetch_method() ?>';
        function __getRandomUnique() {
            return Math.floor(new Date().valueOf() * Math.random());
        }
        function __getItem(key) {
            return localStorage.getItem(__c + __f + key);
        }
        function __setItem(key, vl) {
            localStorage.setItem(__c + __f + key, vl);
        }
        function __removeItem(arr_key) {
            if ($.isArray(arr_key)) {
                for(var i = 0; i < arr_key.length; i++) {
                    if (__getItem(arr_key[i])) {
                        localStorage.removeItem(__c + __f + arr_key[i]);
                    }
                }
            } else {
                if (__getItem(arr_key)) {
                    localStorage.removeItem(__c + __f + arr_key);
                }
            }
        }
    </script>
    
    <noscript>
        <style type="text/css">
            #loading {
                display: none;
            }
        </style>
    </noscript>
    <?php if ($Settings->rtl) { ?>
        <link href="<?= $assets ?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet" />
        <link href="<?= $assets ?>styles/style-rtl.css" rel="stylesheet" />
        <script type="text/javascript">
            $(document).ready(function () {
                $('.pull-right, .pull-left').addClass('flip');
            });
        </script>
        <?php } ?>
	<script type="text/javascript">
		$(window).load(function () {
			$("#loading").fadeOut("slow");
		});
	</script>
</head>

<body>
    <noscript>
        <div class="global-site-notice noscript">
            <div class="notice-inner">
                <p><strong>JavaScript seems to be disabled in your browser.</strong>
                    <br>You must have JavaScript enabled in your browser to utilize the functionality of this website.</p>
            </div>
        </div>
    </noscript>
    <div id="loading"></div>
    <div id="app_wrapper">
        <header id="header" class="navbar">
            <div class="container">
                <a class="navbar-brand" href="<?= site_url() ?>"><span class="logo"><?= $Settings->site_name ?></span></a>

                <div class="btn-group visible-xs pull-right btn-visible-sm">
                    <a class="btn bdarkGreen" style="margin-left:10px !important;margin-right:10px !important;margin-top:1px !important;padding-right:10px !important" title="<?= lang('pos') ?>" data-placement="left" href="<?= site_url('pos') ?>">
                        <i class="fa fa-th-large"></i> <span class="padding02"><?= lang('pos') ?></span>
                    </a>

                    <button class="navbar-toggle btn" type="button" data-toggle="collapse" data-target="#sidebar_menu">
                        <span class="fa fa-bars"></span>
                    </button>

                    <a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>" class="btn">
                        <span class="fa fa-user"></span>
                    </a>
                    <a href="<?= site_url('logout'); ?>" class="btn">
                        <span class="fa fa-sign-out"></span>
                    </a>
                </div>
                <div class="header-nav">
                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown">
                            <a class="btn account dropdown-toggle" data-toggle="dropdown" href="#">
								<img alt="" src="<?= $this->session->userdata('avatar') ? site_url() . 'assets/uploads/avatars/thumbs/' . $this->session->userdata('avatar') : $assets . 'images/' . $this->session->userdata('gender') . '.png'; ?>" class="mini_avatar img-rounded">                        
								<br>
								<div class="user">
									<p><?= $this->session->userdata('username'); ?></p>
								</div>
							</a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="<?= site_url('users/profile/' . $this->session->userdata('user_id')); ?>">
                                        <i class="fa fa-user"></i>
                                        <?= lang('profile'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= site_url('users/profile/' . $this->session->userdata('user_id') . '/#cpassword'); ?>"><i class="fa fa-key"></i> <?= lang('change_password'); ?>
                                </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?= site_url('logout'); ?>">
                                        <i class="fa fa-sign-out"></i>
                                        <?= lang('logout'); ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown hidden-xs"><a class="btn tip" title="<?= lang('dashboard') ?>" data-placement="left" href="<?= site_url('welcome') ?>"><i class="fa fa-dashboard"></i><p><?= lang('dashboard') ?></p></a></li>
                        <?php if ($Owner) { ?>
                            <li class="dropdown hidden-sm">
                                <a class="btn tip" title="<?= lang('settings') ?>" data-placement="left" href="<?= site_url('system_settings') ?>">
                                    <i class="fa fa-cogs"></i><p><?= lang('settings') ?></p>
                                </a>
                            </li>
                        <?php } ?>
						<li class="dropdown hidden-xs">
							<a class="btn tip" title="<?= lang('calculator') ?>" data-placement="left" href="#" data-toggle="dropdown">
								<i class="fa fa-calculator"></i><p><?= lang('calculator') ?></p>
							</a>
							<ul class="dropdown-menu pull-right calc">
								<li class="dropdown-content">
									<span id="inlineCalc"></span>
								</li>
							</ul>
						</li>
						<!--<li class="dropdown">
                            <a class="btn tip" id="event_to_do" title="<span><?= lang('event_to_do') ?></span>" data-placement="bottom" data-html="true" href="<?= site_url('sales/add_event_to_dos') ?>" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-calendar-o" aria-hidden="true"></i><p><?= lang('event_to_do') ?></p>
                            </a>
                        </li>-->
                        <?php if ($info) { ?>
							<li class="dropdown hidden-sm">
								<a class="btn tip" title="<?= lang('notifications') ?>" data-placement="left" href="#" data-toggle="dropdown">
									<i class="fa fa-comments"></i><p><?= lang('notifications') ?></p>
									<span class="number blightOrange black"><?= sizeof($info) ?></span>
								</a>
								<ul class="dropdown-menu pull-right content-scroll">
									<li class="dropdown-header"><i class="fa fa-comments"></i>
										<?= lang('notifications'); ?>
									</li>
									<li class="dropdown-content">
										<div class="scroll-div">
											<div class="top-menu-scroll">
												<ol class="oe">
													<?php foreach ($info as $n) {
											echo '<li>' . $n->comment . '</li>';
										} ?>
												</ol>
											</div>
										</div>
									</li>
								</ul>
							</li>
						<?php } ?>
                        <?php if ($events) { ?>
							<li class="dropdown hidden-xs">
								<a class="btn tip" title="<?= lang('calendar') ?>" data-placement="left" href="#" data-toggle="dropdown">
									<i class="fa fa-calendar"></i><p><?= lang('Calendar') ?></p>
									<span class="number blightOrange black"><?= sizeof($events) ?></span>
								</a>
								<ul class="dropdown-menu pull-right content-scroll">
									<li class="dropdown-header">
										<i class="fa fa-calendar"></i><p><?= lang('calendar') ?></p>
										<?= lang('upcoming_events'); ?>
									</li>
									<li class="dropdown-content">
										<div class="top-menu-scroll">
											<ol class="oe">
												<?php foreach ($events as $event) {
													echo '<li><strong>' . $event->start . ':</strong><br>' . $this->erp->decode_html($event->title) . '</li>';
												} ?>
											</ol>
										</div>
									</li>
									<li class="dropdown-footer">
										<a href="<?= site_url('calendar') ?>" class="btn-block link">
											<i class="fa fa-calendar"></i><p><?= lang('calendar') ?></p>
										</a>
									</li>
								</ul>
							</li>
						<?php } else { ?>
							<li class="dropdown hidden-xs">
								<a class="btn tip" title="<?= lang('calendar') ?>" data-placement="left" href="<?= site_url('calendar') ?>">
									<i class="fa fa-calendar"></i><p><?= lang('calendar') ?></p>
								</a>
							</li>
						<?php } ?>
						<li class="dropdown hidden-sm">
							<a class="btn tip" title="<?= lang('styles') ?>" data-placement="left" data-toggle="dropdown" href="#">
								<i class="fa fa-css3"></i><p><?= lang('styles') ?></p>
							</a>
							<ul class="dropdown-menu pull-right">
								<li class="bwhite noPadding">
									<a href="#" id="fixed" class="">
										<i class="fa fa-angle-double-left"></i>
										<span id="fixedText">Fixed</span>
									</a>
									<a href="#" id="cssLight" class="grey">
										<i class="fa fa-stop"></i> Grey
									</a>
									<a href="#" id="cssBlue" class="blue">
										<i class="fa fa-stop"></i> Blue
									</a>
									<a href="#" id="cssBlack" class="black">
										<i class="fa fa-stop"></i> Black
									</a>
									<a href="#" id="cssPurpie" class="purple">
										<i class="fa fa-stop"></i> Purple
									</a>
									<a href="#" id="cssGreen" class="green">
										<i class="fa fa-stop"></i> Green
									</a>
								</li>
							</ul>
						</li>
						<li class="dropdown hidden-xs">
							<a class="btn tip" title="<?= lang('language') ?>" data-placement="left" data-toggle="dropdown" href="#">
								<img src="<?= base_url('assets/images/' . $Settings->language . '.png'); ?>" alt=""><p><?= lang('language') ?></p>
							</a>
							<ul class="dropdown-menu pull-right">
								<?php 
									$scanned_lang_dir = array_map(function ($path) {
										return basename($path);
									}, glob(APPPATH . 'language/*', GLOB_ONLYDIR));
									foreach ($scanned_lang_dir as $entry) { ?>
									<li>
										<a href="<?= site_url('welcome/language/' . $entry); ?>">
											<img src="<?= base_url(); ?>assets/images/<?= $entry; ?>.png" class="language-img"> &nbsp;&nbsp;<?= ucwords($entry); ?>
										</a>
									</li>
								<?php } ?>
							</ul>

						</li>
						<?php if ($Owner && $Settings->update) { ?>
							<li class="dropdown hidden-sm">
								<a class="btn blightOrange tip" title="<?= lang('update_available') ?>" data-placement="bottom" data-container="body" href="<?= site_url('system_settings/updates') ?>">
									<i class="fa fa-download"></i>
								</a>
							</li>
						<?php } ?>                                                            
						<li class="dropdown hidden-sm">
							<a class="btn blightOrange tip" title="<?= lang('alerts') ?>" data-placement="left" data-toggle="dropdown" href="#">
								<i class="fa fa-exclamation-triangle"></i><p><?= lang('alerts') ?></p>
							</a>
							<ul class="dropdown-menu pull-right">
							<?php if ($qty_alert_num > 0) { ?>
								<li>
									<a href="<?= site_url('reports/quantity_alerts') ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $qty_alert_num; ?></span>
										<span style="padding-right: 35px;"><?= lang('quantity_alerts') ?></span>
									</a>
								</li>
							<?php } ?>
							
							
							<?php if ($public_charge_num > 0) { ?>
								<li>
									<a href="<?= site_url('reports/public_charge_alerts') ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= sizeof($public_charge_num); ?></span>
										<span style="padding-right: 35px;"><?= lang('public_charge_alerts') ?></span>
									</a>
								</li>
							<?php } 
							

							if ($exp_alert_num > 0) { 
								$alert_num = sizeof($exp_alert_num);
							?>
								<li>
									<a href="<?= site_url('reports/expiry_alerts') ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $alert_num; ?></span>
										<span style="padding-right: 35px;"><?= lang('expiry_alerts') ?></span>
									</a>
								</li>
							<?php }							
							if (!empty($payment_customer_alert_num)) { ?>
								<li>
									<a href="<?= site_url('sales/?alert_id='. $payment_customer_alert_num->id) ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $payment_customer_alert_num->count; ?></span>
										<span style="padding-right: 35px;"><?= lang('ar_alerts') ?></span>
									</a>
								</li>
								<!-- <li>
									<?php foreach($payment_customer_alert_num as $customer_payment) {} ?>
									<a href="<?= site_url('sales/?d='. date('Y-m-d', strtotime($payment_customer_alert_num->date))) ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $payment_customer_alert_num->alert_num; ?></span>
										<span style="padding-right: 35px;"><?= lang('customer_payment_alerts') ?></span>
									</a>
								</li> -->
							<?php } 

							if (!empty($payment_supplier_alert_num)) { ?>
								<li>
									<a href="<?= site_url('purchases/?alert_id='. $payment_supplier_alert_num->id) ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $payment_supplier_alert_num->count; ?></span>
										<span style="padding-right: 35px;"><?= lang('ap_alerts') ?></span>
									</a>
								</li>

								<!-- <li>
									<?php foreach($payment_purchase_alert_num as $purchase_payment) {} ?>
									<a href="<?= site_url('purchases/?d='. date('Y-m-d', strtotime($payment_purchase_alert_num->date))) ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $payment_purchase_alert_num->alert_num; ?></span>
										<span style="padding-right: 35px;"><?= lang('supplier_payment_alerts') ?></span>
									</a>
								</li> -->
							<?php } 

							if($pos_settings->show_suspend_bar){ ?>
								<li>
									<a href="<?= site_url('sales/suspend/?d='. date('Y-m-d', strtotime($sale_suspend_alert_num->date))) ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $sale_suspend_alert_num->alert_num; ?></span>
										<span style="padding-right: 35px;"><?= lang('sale_suspend_alerts') ?></span>
									</a>
								</li>
							<?php } 
							if(!empty($delivery_alert_num)){ ?>
								<!-- <li>
									<a href="<?= isset($delivery_alert_num)?site_url('sales/deliveries_alerts/'.date('Y-m-d', strtotime($delivery_alert_num->date))):""; ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $delivery_alert_num->alert_num; ?></span>
										<span style="padding-right: 35px;"><?= lang('deliveries_alerts') ?></span>
									</a>
								</li> -->
							<?php } 
							if(!empty($customers_alert_num)){ ?>
								<li>
									<a href="<?= site_url('sales/customers_alerts/') ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $customers_alert_num; ?></span>
										<span style="padding-right: 35px;"><?= lang('customers_alerts') ?></span>
									</a>
								</li>
							 <?php } ?>
								<li>
									<a href="<?= site_url('purchases_request/purchases_request_alerts/') ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $get_purchases_request_alerts; ?></span>
										<span style="padding-right: 35px;"><?= lang('purchases_request_alerts') ?></span>
									</a>
								</li>
								<li>
									<a href="<?= site_url('purchases/purchase_order_alerts/') ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $get_purchases_order_alerts; ?></span>
										<span style="padding-right: 35px;"><?= lang('purchase_order_alerts') ?></span>
									</a>
								</li>
								<li>
									<a href="<?= site_url('quotes/quote_alerts/') ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $quoties_alert_num; ?></span>
										<span style="padding-right: 35px;"><?= lang('quote_alerts') ?></span>
									</a>
								</li>
								<li>
									<a href="<?= site_url('sale_order/sale_order_alerts/') ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $get_sale_order_order_alerts; ?></span>
										<span style="padding-right: 35px;"><?= lang('sale_order_alerts') ?></span>
									</a>
								</li>
								<li>
									<a href="<?= site_url('sales/delivery_alerts/') ?>" class="">
										<span class="label label-danger pull-right" style="margin-top:3px;"><?= $deliveries_alert_num; ?></span>
										<span style="padding-right: 35px;"><?= lang('delivery_alerts') ?></span>
									</a>
								</li>
							</ul>
						</li>
                        <?php if (POS) { ?>
							<li class="dropdown hidden-xs">
								<a class="btn bdarkGreen tip" title="<?= lang('pos') ?>" data-placement="left" href="<?= site_url('pos') ?>">
									<i class="fa fa-th-large"></i><p><?= lang('pos') ?></p>
								</a>
							</li>
						<?php } ?>
						<?php if ($Owner) { ?>
							<li class="dropdown">
								<a class="btn bdarkGreen tip" id="today_profit" title="<span><?= lang('today_profit') ?></span>" data-placement="bottom" data-html="true" href="<?= site_url('reports/profits') ?>" data-toggle="modal" data-target="#myModal">
									<i class="fa fa-hourglass-2"></i><p><?= lang('profit') ?></p>
								</a>
							</li>
						<?php } ?>
						<?php if ($Owner || $Admin) { ?>
							<?php if (POS) { ?>
								<li class="dropdown hidden-xs">
									<a class="btn bblue tip" title="<?= lang('list_open_registers') ?>" data-placement="bottom" href="<?= site_url('pos/registers') ?>">
										<i class="fa fa-list"></i><p><?= lang('register'); ?></p>                                                                                                
									</a>
								</li>
							<?php } ?>
						<?php } ?>
						<li class="dropdown hidden-xs">
							<a class="btn bred tip" title="<?= lang('reset') ?>" data-placement="bottom" id="clearLS" href="#">
								<i class="fa fa-eraser"></i><p><?= lang('reset') ?></p> 
							</a>
						</li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="container bblack" id="container">
            <div class="row" id="main-con">
                <div id="sidebar-left" class="col-lg-2 col-md-2">
                    <div class="sidebar-nav nav-collapse collapse navbar-collapse" id="sidebar_menu">
                        <ul class="nav main-menu">
                            <li class="mm_welcome">
                                <a href="<?= site_url() ?>">
                                    <i class="fa fa-dashboard"></i>
                                    <span class="text"> <?= lang('dashboard'); ?></span>
                                </a>
                            </li>
							
							<li class="mm_notifications">
                                <a class="submenu" href="<?= site_url('notifications'); ?>">
                                    <i class="fa fa-comments"></i><span class="text"> <?= lang('notifications'); ?></span>
                                </a>
                            </li>
							
                            <?php
							
								if ($Owner || $Admin) {
                            ?>

                                <li class="mm_products">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-barcode"></i>
                                        <span class="text"> <?= lang('manage_products'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        <li id="products_index" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products'); ?>">
                                                <i class="fa fa-barcode"></i>
                                                <span class="text"> <?= lang('list_products'); ?></span>
                                            </a>
                                        </li>
                                        <li id="products_add" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/add'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_product'); ?></span>
                                            </a>
                                        </li>
                                        <li id="products_list_convert" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/list_convert'); ?>">
                                                <i class="fa fa-barcode"></i>
                                                <span class="text"> <?= lang('list_convert'); ?></span>
                                            </a>
                                        </li>
                                        <li id="products_items_convert" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/items_convert'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_convert'); ?></span>
                                            </a>
                                        </li>
                                        <!--<li id="products_return_products" class="sub_navigation">
                                            <a class="submenu" href="<?/*= site_url('products/return_products'); */?>">
                                                <i class="fa fa-retweet"></i>
                                                <span class="text"> <?/*= lang('list_products_return'); */?></span>
                                            </a>
                                        </li>-->

                                        <li id="products_print_barcodes" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/print_barcodes'); ?>">
                                                <i class="fa fa-tags"></i>
                                                <span class="text"> <?= lang('print_barcode_label'); ?></span>
                                            </a>
                                        </li>
                                        <li id="products_quantity_adjustments" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/quantity_adjustments'); ?>">
                                                <i class="fa fa-filter"></i>
                                                <span class="text"> <?= lang('adjustment_quantity'); ?></span>
                                            </a>
                                        </li>
										<li id="products_add_adjustment_multiple">
											<a class="submenu" href="<?= site_url('products/add_adjustment_multiple'); ?>">
												<i class="fa fa-plus-circle"></i>
												<span class="text"> <?= lang('add_adjustment_multiple'); ?></span>
											</a>
										</li>
										<?php if ($GP['product_import']) { ?>
											<li id="products_import_csv" class="sub_navigation">
												<a class="submenu" href="<?= site_url('products/import_csv'); ?>">
													<i class="fa fa-file-text"></i>
													<span class="text"> <?= lang('import_products'); ?></span>
												</a>
											</li>
										<?php } ?>	
										<?php if ($GP['product_import_quantity']) { ?>
											<li id="products_update_quantity" class="sub_navigation">
												<a class="submenu" href="<?= site_url('products/update_quantity'); ?>">
													<i class="fa fa-file-text"></i>
													<span class="text"> <?= lang('update_quantity'); ?></span>
												</a>
											</li>
										<?php } ?>	
										<?php if ($GP['product_import_price_cost']) { ?>
										<li id="products_update_price" class="sub_navigation">
												<a class="submenu" href="<?= site_url('products/update_price'); ?>">
													<i class="fa fa-file-text"></i>
													<span class="text"> <?= lang('import_price_cost'); ?></span>
												</a>
											</li>
										<?php } ?>
										
                                        <!--
										<li id="products_view_using_stock" class="sub_navigation">
                                            <a class="submenu" href="<? /*= site_url('products/view_using_stock'); */ ?>">
                                                <i class="fa fa-filter"></i>
                                                <span class="text"> <? /*= lang('list_stock_using'); */ ?></span>
                                            </a>
                                        </li>-->
                                        <!--
										<li id="products_enter_using_stock" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/enter_using_stock'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_stock_using'); ?></span>
                                            </a>
                                        </li>-->

                                        <li id="products_view_enter_using_stock" class="sub_navigation">
                                            <a class="submenu"
                                               href="<?= site_url('products/view_enter_using_stock'); ?>">
                                                <i class="fa fa-filter"></i>
                                                <span class="text"> <?= lang('list_stock_using'); ?></span>
                                            </a>
                                        </li>
										<li id="products_using_stock" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/using_stock'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_stock_using'); ?></span>
                                            </a>
                                        </li>
										<!--
										<li id="products_barcode_count_stock" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/barcode_count_stock'); ?>">
                                                <i class="fa fa-filter"></i>
                                                <span class="text"> <?= lang('barcode_count_stock'); ?></span>
                                            </a>
                                        </li>
										<li id="products_list_count_stock" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/list_count_stock'); ?>">
                                                <i class="fa fa-filter"></i>
                                                <span class="text"> <?= lang('list_count_stock'); ?></span>
                                            </a>
                                        </li>
										<li id="products_add_count_stock" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/add_count_stock'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_count_stock'); ?></span>
                                            </a>
                                        </li>
										<li id="products_list_adjust_cost" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/list_adjust_cost'); ?>">
                                                <i class="fa fa-file"></i>
                                                <span class="text"> <?= lang('list_adjust_cost'); ?></span>
                                            </a>
                                        </li>
										<li id="products_adjust_cost" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/adjust_cost'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('adjust_cost'); ?></span>
                                            </a>
                                        </li>
										-->
                                    </ul>
                                </li>
								
                                <li class="mm_sales mm_sale_order <?= strtolower($this->router->fetch_method()) == 'settings' ? '' : 'mm_pos' ?>">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-heart"></i>
                                        <span class="text"> <?= lang('manage_sales'); ?> 
                                    </span> <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        <li id="pos_sales">
                                            <a class="submenu" href="<?= site_url('pos/sales'); ?>">
                                                <i class="fa fa-heart"></i>
                                                <span class="text"> <?= lang('pos_sales'); ?></span>
                                            </a>
                                        </li>
										<li id="add_pos_sales">
                                        <a class="submenu" href="<?= site_url('pos'); ?>">
                                            <i class="fa fa-plus-circle"></i>
                                            <span class="text"> <?= lang('add_pos_sale'); ?></span>
                                        </a>
                                    	</li>
                                    	<li id="sale_order_list_sale_order">
										<a class="submenu" href="<?= site_url('sale_order/list_sale_order'); ?>">
											<i class="fa fa-heart"></i>
											<span class="text"> <?= lang('list_sales_order'); ?></span>
										</a>
										</li>
										<li id="sale_order_add_sale_order">
											<a class="submenu" href="<?= site_url('sale_order/add_sale_order'); ?>">
												<i class="fa fa-plus-circle"></i>
												<span class="text"> <?= lang('add_sale_order'); ?></span>
											</a>
										</li>
										<li id="sales_index">
											<a class="submenu" href="<?= site_url('sales'); ?>">
												<i class="fa fa-heart"></i>
												<span class="text"> <?= lang('list_sales'); ?></span>
											</a>
										</li>
										<li id="sales_add">
											<a class="submenu" href="<?= site_url('sales/add'); ?>">
												<i class="fa fa-plus-circle"></i>
												<span class="text"> <?= lang('add_sale'); ?></span>
											</a>
                                        </li>
                                        
										<li id="sales_deliveries">
                                            <a class="submenu" href="<?= site_url('sales/deliveries'); ?>">
                                                <i class="fa fa-truck"></i>
                                                <span class="text"> <?= lang('list_deliveries'); ?></span>
                                            </a>
                                        </li>
										<li id="sales_add_deliveries">
                                            <a class="submenu" href="<?= site_url('sales/add_deliveries'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_deliveries'); ?></span>
                                            </a>
                                        </li>
										<li id="sales_customer_balance">
                                            <a class="submenu" href="<?= site_url('sales/customer_balance'); ?>">
                                                <i class="fa fa-money"></i>
                                                <span class="text"> <?= lang('customer_balance'); ?></span>
                                            </a>
                                        </li>
										<?php if ($Owner || $Admin) { ?>
											<li id="sales_customer_opening_balance">
												<a class="submenu" href="<?= site_url('sales/customer_opening_balance'); ?>">
													<i class="fa fa-money"></i>
													<span class="text"> <?= lang('opening_ar'); ?></span>
												</a>
											</li>
										<?php } ?>
										
										<!--<li id="sales_gift_cards">
                                            <a class="submenu" href="<?= site_url('sales/gift_cards'); ?>">
                                                <i class="fa fa-credit-card"></i>
                                                <span class="text"> <?= lang('list_gift_cards'); ?></span>
                                            </a>
                                        </li>-->

                                        <li id="sales_return_sales">
                                            <a class="submenu" href="<?= site_url('sales/return_sales'); ?>">
                                                <i class="fa fa-reply"></i>
                                                <span class="text"> <?= lang('list_sales_return'); ?></span>
                                            </a>
                                        </li>
										<!--
                                        <li id="sales_add_return">
                                            <a class="submenu" href="<?= site_url('sales/add_return'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_sale_return'); ?></span>
                                            </a>
                                        </li>
										-->
									</ul>
                                </li>
																
                                <li class="mm_quotes">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-heart-o"></i>
                                        <span class="text"> <?= lang('manage_quotes'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                       <li id="quotes_index">
                                            <a class="submenu" href="<?= site_url('quotes'); ?>">
                                                <i class="fa fa-heart-o"></i>
                                                <span class="text"> <?= lang('list_quotes'); ?></span>
                                            </a>
                                        </li>
                                        <li id="quotes_add">
                                            <a class="submenu" href="<?= site_url('quotes/add'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_quote'); ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="mm_purchases mm_purchases_request">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-star"></i>
                                        <span class="text"> <?= lang('manage_purchases'); ?> 
                                    </span> <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                       <li id="purchases_request_index">
                                            <a class="submenu" href="<?= site_url('purchases_request'); ?>">
                                                <i class="fa fa-star"></i>
                                                <span class="text"> <?= lang('list_purchase_request'); ?></span>
                                            </a>
                                       </li>
                                       <li id="purchases_request_add">
                                            <a class="submenu" href="<?= site_url('purchases_request/add'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_purchase_request'); ?></span>
                                            </a>
                                       </li>
                                       <li id="purchases_purchase_order">
                                            <a class="submenu" href="<?= site_url('purchases/purchase_order'); ?>">
                                                <i class="fa fa-star"></i>
                                                <span class="text"> <?= lang('list_purchase_order'); ?></span>
                                            </a>
                                        </li>
                                        <li id="purchases_add_purchase_order">
                                            <a class="submenu" href="<?= site_url('purchases/add_purchase_order'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_purchase_order'); ?></span>
                                            </a>
                                        </li>
                                    	<li id="purchases_index">
                                            <a class="submenu" href="<?= site_url('purchases'); ?>">
                                                <i class="fa fa-star"></i>
                                                <span class="text"> <?= lang('list_purchases'); ?></span>
                                            </a>
                                        </li>
                                        <li id="purchases_add">
                                            <a class="submenu" href="<?= site_url('purchases/add'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_purchase'); ?></span>
                                            </a>
                                        </li>
										<li id="purchases_supplier_balance">
                                            <a class="submenu" href="<?= site_url('purchases/supplier_balance'); ?>">
                                                <i class="fa fa-money"></i>
                                                <span class="text"> <?= lang('supplier_balance'); ?></span>
                                            </a>
                                        </li>
										<?php if ($Owner || $Admin) { ?>
											<li id="purchases_supplier_opening_balance">
												<a class="submenu" href="<?= site_url('purchases/supplier_opening_balance'); ?>">
													<i class="fa fa-money"></i>
													<span class="text"> <?= lang('opening_ap'); ?></span>
												</a>
											</li>
										<?php } ?>
                                        <!--<li id="purchases_return_purchases">
                                            <a class="submenu" href="<?= site_url('purchases/return_purchases'); ?>">
                                                <i class="fa fa-reply"></i>
                                                <span class="text"> <?= lang('list_purchases_return'); ?></span>
                                            </a>
                                        </li>
                                        <li id="purchases_add_purchase_return">
                                            <a class="submenu" href="<?= site_url('purchases/add_purchase_return'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_purchase_return'); ?></span>
                                            </a>
                                        </li>-->
                                        <li id="purchases_expenses">
                                            <a class="submenu" href="<?= site_url('purchases/expenses'); ?>">
                                                <i class="fa fa-dollar"></i>
                                                <span class="text"> <?= lang('list_expenses'); ?></span>
                                            </a>
                                        </li>
                                        <li id="purchases_add_expense">
                                            <a class="submenu" href="<?= site_url('purchases/add_expense'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_expense'); ?></span>
                                            </a>
                                        </li>
                                        <li id="purchases_expense_by_csv">
                                            <a class="submenu" href="<?= site_url('purchases/expense_by_csv'); ?>">
                                                <i class="fa fa-file-text"></i>
                                                <span class="text"> <?= lang('import_expense'); ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="mm_transfers">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-tags"></i>
                                        <span class="text"> <?= lang('manage_transfers'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        <li id="transfers_list_in_transfer">
                                            <a class="submenu" href="<?= site_url('transfers/list_in_transfer'); ?>">
                                                <i class="fa fa-tags"></i><span class="text"> <?= lang('list_transfers'); ?></span>
                                            </a>
                                        </li>
                                        <li id="transfers_add">
                                            <a class="submenu" href="<?= site_url('transfers/add'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_transfer'); ?></span>
                                            </a>
                                        </li>
										<!--
                                        <li id="transfers_transfer_by_csv">
                                            <a class="submenu" href="<?= site_url('transfers/transfer_by_csv'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_transfer_by_csv'); ?></span>
                                            </a>
                                        </li>
										-->
                                    </ul>
                                </li>

								<!--
                                <li class="mm_account">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-book"></i>
                                        <span class="text"> <?= lang('manage_accounts') ?></span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        
                                        <li id="account_listjournal">
                                            <a class="submenu" href="<?= site_url('account/listJournal'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_journal'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_add_journal">
                                            <a class="submenu" href="<?= site_url('account/add_journal'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_journal'); ?></span>
                                            </a>
                                        </li> 
                                        <li id="account_list_ac_recevable">
                                            <a class="submenu" href="<?= site_url('account/list_ac_recevable'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ac_receivable'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_list_ar_aging">
                                            <a class="submenu" href="<?= site_url('account/list_ar_aging'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ar_aging'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_ar_by_customer">
                                            <a class="submenu" href="<?= site_url('account/ar_by_customer'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('ar_by_customer'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_billreceipt">
                                            <a href="<?= site_url('account/billReceipt') ?>">
                                                <i class="fa fa-money"></i><span class="text"> <?= lang('bill_receipt'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_list_ac_payable">
                                            <a class="submenu" href="<?= site_url('account/list_ac_payable'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('account_payable_list'); ?></span>
                                            </a>
                                        </li>
										<li id="account_list_ap_aging">
                                            <a class="submenu" href="<?= site_url('account/list_ap_aging'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ap_aging'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_ap_by_supplier">
                                            <a class="submenu" href="<?= site_url('account/ap_by_supplier'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('ap_by_supplier'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_billpayable">
                                            <a href="<?= site_url('account/billPayable') ?>">
                                                <i class="fa fa-money"></i><span class="text"> <?= lang('bill_payable'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_index">
                                            <a class="submenu" href="<?= site_url('account'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_ac_head'); ?></span>
                                            </a>
                                        </li>
                                        <li id="account_add">
                                            <a class="submenu" href="<?= site_url('account/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_ac_head'); ?></span>
                                            </a>
                                        </li>
										<li id="account_budget">
                                            <a class="submenu" href="<? /*= site_url('#'); */ ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <? /*= lang('list_budget'); */ ?></span>
                                            </a>
                                        </li>
										<li id="account_budget_add">
                                            <a class="submenu" href="<? /*= site_url('#'); */ ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <? /*= lang('add_budget'); */ ?></span>
                                            </a>
                                        </li>
										<li id="account_deposits">
                                            <a class="submenu" href="<? /*= site_url('account/deposits'); */ ?>">
                                                <i class="fa fa-list"></i><span class="text"> <? /*= lang('list_customer_deposit'); */ ?></span>
                                            </a>
                                        </li>
										<li id="account_deposits">
                                            <a class="submenu" href="<? /*= site_url('quotes/add_deposit'); */ ?>" data-toggle="modal" data-target="#myModal" id="add">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <? /*= lang('add_customer_deposit'); */ ?></span>
                                            </a>
                                        </li> 
										<li id="suppliers_deposits">
                                            <a class="submenu" href="<? /*= site_url('suppliers/deposits'); */ ?>">
                                                <i class="fa fa-list"></i><span class="text"> <? /*= lang('list_supplier_deposit'); */ ?></span>
                                            </a>
                                        </li>
										<li id="suppliers_add_deposits">
                                            <a class="submenu" href="<? /*= site_url('suppliers/add_deposit'); */ ?>" data-toggle="modal" data-target="#myModal" id="add">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <? /*= lang('add_supplier_deposit'); */ ?></span>
                                            </a>
                                        </li>
										<?php if ($Owner) { ?>
										<li id="account_settings">
											<a href="<?= site_url('account/settings') ?>">
												<i class="fa fa-cog"></i><span class="text"> <?= lang('account_settings'); ?></span>
											</a>
                                        </li> 
										<?php }?>
                                    </ul> 
                                </li>
								-->

								<!--<li class="mm_taxes">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-book"></i>
                                        <span class="text"> <?= lang('manage_gov_taxs') ?></span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        <li id="taxes_selling_tax">
                                            <a class="submenu" href="<?= site_url('taxes/selling_tax'); ?>" >
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('selling_tax'); ?></span>
                                            </a>
                                        </li>
                                        <li id="taxes_purchasing_tax">
                                            <a class="submenu" href="<?= site_url('taxes/purchasing_tax'); ?>" >
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('purchasing_tax'); ?></span>
                                            </a>
                                        </li>
                                    	<li id="taxes_staffing_tax">
                                            <a class="submenu" href="<?= site_url('taxes/staffing_tax'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('staffing_tax'); ?></span>
                                            </a>
                                        </li>
                                    	<li id="taxes_condition_tax">
                                            <a class="submenu" href="<?= site_url('taxes/condition_tax'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('condition_tax'); ?></span>
                                            </a>
                                        </li>
                                    	<li id="taxes_exchange_rate_tax">
                                            <a class="submenu" href="<?= site_url('taxes/exchange_rate_tax'); ?>" >
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('exchange_rate_tax'); ?></span>
                                            </a>
                                        </li>
                                    	<li id="taxes_account_tax">
                                            <a class="submenu" href="<?= site_url('taxes/list_tax'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('list_ac_head_tax'); ?></span>
                                            </a>
                                        </li>
                                        <li id="taxes_salary_tax">
                                            <a class="submenu" href="<?= site_url('taxes/salary_tax'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('salary_tax'); ?></span>
                                            </a>
                                        </li>
                                    	<li id="taxes_value_added_tax">
                                            <a class="submenu" href="<?= site_url('taxes/value_added_tax'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('value_added_tax'); ?></span>
                                            </a>
                                        </li>
                                    	<li id="taxes_withholding_tax">
                                            <a class="submenu" href="<?= site_url('taxes/withholding_tax'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('withholding_tax'); ?></span>
                                            </a>
                                        </li>
                                    	<li id="taxes_profit_tax">
                                            <a class="submenu" href="<?= site_url('taxes/profit_tax'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('profit_tax'); ?></span>
                                            </a>
                                        </li>                                       
                                    </ul>
                                </li>-->
                                
                                <li class="mm_auth mm_customers mm_suppliers mm_billers">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-users"></i>
                                        <span class="text"> <?= lang('manage_people'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        <?php if ($Owner) { ?>
                                            <li id="auth_users">
                                                <a class="submenu" href="<?= site_url('users'); ?>">
                                                    <i class="fa fa-users"></i><span class="text"> <?= lang('list_users'); ?></span>
                                                </a>
                                            </li>
                                            <li id="auth_create_user">
                                                <a class="submenu" href="<?= site_url('users/create_user'); ?>">
                                                    <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('new_user'); ?></span>
                                                </a>
                                            </li>
                                            <li id="billers_index">
                                                <a class="submenu" href="<?= site_url('billers'); ?>">
                                                    <i class="fa fa-users"></i><span class="text"> <?= lang('list_billers'); ?></span>
                                                </a>
                                            </li>
											<!--
                                            <li id="billers_index">
                                                <a class="submenu" href="<?= site_url('billers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                    <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_biller'); ?></span>
                                                </a>
                                            </li>
											-->
                                        <?php } ?>
                                                <li id="customers_index">
                                                    <a class="submenu" href="<?= site_url('customers'); ?>">
                                                        <i class="fa fa-users"></i><span class="text"> <?= lang('list_customers'); ?></span>
                                                    </a>
                                                </li>
                                                <li id="customers_index">
                                                    <a class="submenu" href="<?= site_url('customers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                        <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_customer'); ?></span>
                                                    </a>
                                                </li>
												<li id="drivers_index">
													<a class="submenu" href="<?= site_url('drivers'); ?>">
														<i class="fa fa-users"></i><span class="text"> <?= lang('list_drivers'); ?></span>
													</a>
                                            	</li>
												<li id="drivers_index">
                                                    <a class="submenu" href="<?= site_url('drivers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                        <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_driver'); ?></span>
                                                    </a>
                                                </li>
                                                <li id="suppliers_index">
                                                    <a class="submenu" href="<?= site_url('suppliers'); ?>">
                                                        <i class="fa fa-users"></i><span class="text"> <?= lang('list_suppliers'); ?></span>
                                                    </a>
                                                </li>
                                                <li id="suppliers_index">
                                                    <a class="submenu" href="<?= site_url('suppliers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                        <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_supplier'); ?></span>
                                                    </a>
                                                </li>
                                              <!-- <li id="employees_index">
													<a class="submenu" href="<?= site_url('employees'); ?>">
														<i class="fa fa-users"></i><span class="text"> <?= lang('list_employees'); ?></span>
													</a>
                                            	</li>
                                            	<li id="employees_add">
													<a class="submenu" href="<?= site_url('employees/add'); ?>">
														<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_employee'); ?></span>
													</a>
                                            	</li> 
												<li id="employees_create_employee_salary">
													<a class="submenu" href="<?= site_url('employees/create_employee_salary'); ?>" >
														<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('create_employee_salary'); ?></span>
													</a>
												</li>-->
                                    </ul>
                                </li>
                                
								<!--
								<li class="mm_documents">
                                    <a class="submenu" href="<?= site_url('documents'); ?>">
                                        <i class="fa fa-book"></i><span class="text"> <?= lang('documents'); ?></span>
                                    </a>
                                </li>
								-->
								
                                <?php if ($Owner || $Admin) { ?>
                                    <li class="mm_system_settings <?= strtolower($this->router->fetch_method()) != 'settings' ? '' : 'mm_pos' ?>">
                                        <a class="dropmenu" href="#">
                                            <i class="fa fa-cog"></i><span class="text"> <?= lang('settings'); ?> </span>
                                            <span class="chevron closed"></span>
                                        </a>
                                        <ul>
                                            <li id="system_settings_index">
                                                <a href="<?= site_url('system_settings') ?>">
                                                    <i class="fa fa-cog"></i><span class="text"> <?= lang('system_settings'); ?></span>
                                                </a>
                                            </li>
                                            <?php if (POS) { ?>
                                                <li id="pos_settings">
                                                    <a href="<?= site_url('pos/settings') ?>">
                                                        <i class="fa fa-th-large"></i><span class="text"> <?= lang('pos_settings'); ?></span>
                                                    </a>
                                                </li>
                                                <?php } ?>
                                                    <!--<li id="account_settings">
                                                        <a href="<?= site_url('account/settings') ?>">
                                                            <i class="fa fa-cog"></i><span class="text"> <?= lang('account_settings'); ?></span>
                                                        </a>
                                                    </li>-->
                                                    <li id="system_settings_change_logo">
                                                        <a href="<?= site_url('system_settings/change_logo') ?>" data-toggle="modal" data-target="#myModal">
                                                            <i class="fa fa-upload"></i><span class="text"> <?= lang('change_logo'); ?></span>
                                                        </a>
                                                    </li>
													<li id="system_settings_tax_rates">
                                                        <a href="<?= site_url('system_settings/tax_rates') ?>">
                                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('tax_rates'); ?></span>
                                                        </a>
                                                    </li> 
													<!--<li id="system_settings_tax_exchange_rate">
                                                        <a href="<?= site_url('system_settings/tax_exchange_rate') ?>">
                                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('tax_exchange_rate'); ?></span>
                                                        </a>
                                                    </li>-->
                                                    <li id="system_settings_currencies">
                                                        <a href="<?= site_url('system_settings/currencies') ?>">
                                                            <i class="fa fa-money"></i><span class="text"> <?= lang('currencies'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_customer_groups">
                                                        <a href="<?= site_url('system_settings/customer_groups') ?>">
                                                            <i class="fa fa-chain"></i><span class="text"> <?= lang('customer_groups'); ?></span>
                                                        </a>
                                                    </li>
													<li id="system_settings_promotion">
                                                        <a href="<?= site_url('system_settings/promotion') ?>">
                                                            <i class="fa fa-chain"></i><span class="text"> <?= lang('promotion'); ?></span>
                                                        </a>
                                                    </li>
													<li id="system_settings_warehouses">
                                                        <a href="<?= site_url('system_settings/warehouses') ?>">
                                                            <i class="fa fa-building-o"></i><span class="text"> <?= lang('warehouses'); ?></span>
                                                        </a>
                                                    </li>
													<li id="system_settings_categories">
                                                        <a href="<?= site_url('system_settings/categories') ?>">
                                                            <i class="fa fa-sitemap"></i><span class="text"> <?= lang('categories'); ?></span>
                                                        </a>
                                                    </li>
													<!--<li id="system_settings_expense_categories">
														<a href="<?= site_url('system_settings/expense_categories') ?>">
															<i class="fa fa-folder-open"></i><span class="text"> <?= lang('expense_categories'); ?></span>
														</a>
													</li>
													<li id="system_settings_reasons">
                                                        <a href="<?= site_url('system_settings/reasons') ?>">
                                                            <i class="fa fa-sitemap"></i><span class="text"> <?= lang('reasons'); ?></span>
                                                        </a>
                                                    </li>-->
													<li id="system_settings_price_groups">
														<a href="<?= site_url('system_settings/price_groups') ?>">
															<i class="fa fa-dollar"></i><span class="text"> <?= lang('price_groups'); ?></span>
														</a>
													</li>
													<li id="system_settings_product_note">
														<a href="<?= site_url('system_settings/product_note') ?>">
															<i class="fa fa-dollar"></i><span class="text"> <?= lang('product_note'); ?></span>
														</a>
													</li> 
                                                    <li id="system_settings_variants">
                                                        <a href="<?= site_url('system_settings/variants') ?>">
                                                            <i class="fa fa-tags"></i><span class="text"> <?= lang('variants'); ?></span>
                                                        </a>
                                                    </li>
													<li id="system_settings_units">
														<a href="<?= site_url('system_settings/units') ?>">
															 <i class="fa fa-wrench"></i><span class="text"> <?= lang('units'); ?></span>
														</a>
													</li>                                                    
                                                    <li id="system_settings_bom">
                                                        <a href="<?= site_url('system_settings/bom') ?>">
                                                            <i class="fa fa-tags"></i><span class="text"> <?= lang('bom'); ?></span>
                                                        </a>
                                                    </li>
													<!--
                                                    <li id="system_settings_suspend">
                                                        <a href="<?= site_url('system_settings/suspend') ?>">
                                                            <i class="fa fa-tags"></i><span class="text"> <?= lang('suspend'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_suspend_layout">
                                                        <a href="<?= site_url('system_settings/suspend_layout') ?>">
                                                            <i class="fa fa-tags"></i><span class="text"> <?= lang('suspend_layout'); ?></span>
                                                        </a>
                                                    </li>-->                                                                                              
                                                   <!-- <li id="system_settings_email_templates">
                                                        <a href="<?= site_url('system_settings/email_templates') ?>">
                                                            <i class="fa fa-envelope"></i><span class="text"> <?= lang('email_templates'); ?></span>
                                                        </a>
                                                    </li>-->
													<li id="system_settings_payment_term">
                                                        <a href="<?= site_url('system_settings/payment_term') ?>">
                                                            <i class="fa fa-money"></i><span class="text"> <?= lang('payment_term'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_user_groups">
                                                        <a href="<?= site_url('system_settings/user_groups') ?>">
                                                            <i class="fa fa-key"></i><span class="text"> <?= lang('group_permissions'); ?></span>
                                                        </a>
                                                    </li>
													<!--
													<li id="system_settings_define_principle">
                                                        <a href="<?= site_url('system_settings/define_principle') ?>">
                                                            <i class="fa fa-key"></i><span class="text"> <?= lang('define_principle'); ?></span>
                                                        </a>
                                                    </li>
													<li id="system_settings_define_frequency">
                                                        <a href="<?= site_url('system_settings/define_frequency') ?>">
                                                            <i class="fa fa-key"></i><span class="text"> <?= lang('define_frequency'); ?></span>
                                                        </a>
                                                    </li>
													<li id="system_settings_define_frequency">
                                                        <a href="<?= site_url('system_settings/define_term') ?>">
                                                            <i class="fa fa-key"></i><span class="text"> <?= lang('define_term'); ?></span>
                                                        </a>
                                                    </li>
													<li id="system_settings_define_public_charge">
                                                        <a href="<?= site_url('system_settings/public_charge') ?>">
                                                            <i class="fa fa-key"></i><span class="text"> <?= lang('define_public_charge'); ?></span>
                                                        </a>
                                                    </li>-->
													<li id="group_area">
														<a class="submenu" href="<?= site_url('system_settings/group_area'); ?>">
															<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('group_area'); ?></span>
														</a>
													</li>
                                                    <li id="system_settings_backups">
                                                        <a href="<?= site_url('system_settings/backups') ?>">
                                                            <i class="fa fa-database"></i><span class="text"> <?= lang('backups'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_updates">
                                                        <a href="<?= site_url('system_settings/updates') ?>">
                                                            <i class="fa fa-upload"></i><span class="text"> <?= lang('updates'); ?></span>
                                                        </a>
                                                    </li>
													
													<li id="system_settings_audit_trail">
                                                        <a href="<?= site_url('system_settings/audit_trail') ?>">
                                                            <i class="fa fa-pencil"></i><span class="text"> <?= lang('audit_trail'); ?></span>
                                                        </a>
                                                    </li>
                                        </ul>
                                    </li>
                                <?php } ?>
									
										<!--<li class="mm_taxes_reports">
                                            <a class="dropmenu" href="#">
                                                <i class="fa fa-pie-chart"></i>
                                                <span class="text"> <?= lang('gov_reports'); ?> </span>
                                                <span class="chevron closed"></span>
                                            </a>
                                            <ul>
                                                <li id="govreports_salary_tax">
                                                    <a href="<?= site_url('taxes_reports/salary_tax') ?>">
                                                        <i class="fa fa-bars"></i><span class="text"> <?= lang('salary_tax'); ?></span>
                                                    </a>
                                                </li>
                                                <li id="govreports_value_added_tax">
                                                    <a href="<?= site_url('taxes_reports/value_added_tax') ?>">
                                                        <i class="fa fa-bars"></i><span class="text"> <?= lang('value_added_tax'); ?></span>
                                                    </a>
                                                </li>
												<li id="govreports_profit_tax">
                                                    <a href="<?= site_url('taxes_reports/profit_tax') ?>">
                                                        <i class="fa fa-bars"></i><span class="text"> <?= lang('profit_tax'); ?></span>
                                                    </a>
                                                </li>
                                             	<li id="govreports_annual_profit_tax">
                                                    <a href="<?= site_url('taxes_reports/annual_profit_tax') ?>">
                                                        <i class="fa fa-bars"></i><span class="text"> <?= lang('annual_profit_tax'); ?></span>
                                                    </a>
                                                </li>
                                                <li id="govreports_sales_journal_list">
                                                    <a href="<?= site_url('taxes_reports/sales_journal_list') ?>">
                                                        <i class="fa fa-bars"></i><span class="text"> <?= lang('sales_journal_list'); ?></span>
                                                    </a>
                                                </li>
												<li id="taxes_reports_purchase_journal_list">
                                                    <a href="<?= site_url('taxes_reports/purchase_journal_list') ?>">
                                                        <i class="fa fa-bars"></i><span class="text"> <?= lang('purchase_journal_list'); ?></span>
                                                    </a>
                                                </li>
                                             	<li id="govreports_tax_salary_list">
                                                    <a href="<?= site_url('taxes_reports/tax_salary_list') ?>">
                                                        <i class="fa fa-bars"></i><span class="text"> <?= lang('tax_salary_list'); ?></span>
                                                    </a>
                                                </li>
                                             	<li id="govreports_trial_balance">
                                                    <a href="<?= site_url('taxes_reports/trial_balance') ?>">
                                                        <i class="fa fa-bars"></i><span class="text"> <?= lang('trial_balance'); ?></span>
                                                    </a>
                                                </li>
                                             	<li id="govreports_profit_lost">
                                                    <a href="<?= site_url('taxes_reports/profit_lost') ?>">
                                                        <i class="fa fa-bars"></i><span class="text"> <?= lang('profit_lost'); ?></span>
                                                    </a>
                                                </li>
                                             	<li id="govreports_balance_sheet">
                                                    <a href="<?= site_url('taxes_reports/balance_sheet') ?>">
                                                        <i class="fa fa-bars"></i><span class="text"> <?= lang('balance_sheet'); ?></span>
                                                    </a>
                                                </li>
                                             	<li id="govreports_balance_sheet_tax">
                                                    <a href="<?= site_url('taxes_reports/balance_sheet_tax') ?>">
                                                        <i class="fa fa-bars"></i><span class="text"> <?= lang('balance_sheet_tax'); ?></span>
                                                    </a>
                                                </li>
                                             	<li id="govreports_profit_lost_tax">
                                                    <a href="<?= site_url('taxes_reports/profit_lost_tax') ?>">
                                                        <i class="fa fa-building"></i><span class="text"> <?= lang('profit_lost_tax'); ?></span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>-->
                                      

                                        <li class="mm_reports">
                                            <a class="dropmenu" href="#">
                                                <i class="fa fa-pie-chart"></i>
                                                <span class="text"> <?= lang('reports'); ?> </span>
                                                <span class="chevron closed"></span>
                                            </a>
											
                                            <ul>
												<!--<li class="mm_chart_report">
															<a class="dropmenu" href="#">
																<i class="fa fa-bar-chart-o"></i>
																<span class="text"> <?= lang('chart_report'); ?> </span>
																<span class="chevron closed"></span>
															</a>
															<ul>
													<li id="reports_index">
														<a href="<?= site_url('reports') ?>">
															<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('overview_chart'); ?></span>
														</a>
													</li>
													<li id="reports_warehouse_stock">
														<a href="<?= site_url('reports/warehouse_stock') ?>">
															<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('warehouse_stock'); ?></span>
														</a>
													</li>
													<li id="reports_category_stock">
														<a href="<?= site_url('reports/category_stock') ?>">
															<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('category_stock_chart'); ?></span>
														</a>
													</li>
													<li id="reports_profit_chart">
														<a href="<?= site_url('reports/profit_chart') ?>">
															<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('profit_chart'); ?></span>
														</a>
													</li>
													<li id="reports_cash_chart">
														<a href="<?= site_url('reports/cash_chart') ?>">
															<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('cash_analysis_chart'); ?></span>
														</a>
													</li>   
													</ul>
												</li>-->
												<li class="mm_profit_report">
														<a class="dropmenu" href="#">
															<i class="fa fa-money"></i>
															<span class="text"> <?= lang('profit_report'); ?> </span>
															<span class="chevron closed"></span>
														</a>
													<ul> 
													<li id="reports_profit_loss">
                                                        <a href="<?= site_url('reports/profit_loss') ?>">
                                                            <i class="fa fa-money"></i><span class="text"> <?= lang('profit_and_loss'); ?></span>
                                                        </a>
                                                    </li>
													<li id="reports_payments">
                                                        <a href="<?= site_url('reports/payments') ?>">
                                                            <i class="fa fa-money"></i><span class="text"> <?= lang('payments_report'); ?></span>
                                                        </a>
													</li>
                                                        <?php if (POS) { ?>
														<li id="reports_register">
															<a href="<?= site_url('reports/register') ?>">
																<i class="fa fa-money"></i><span class="text"> <?= lang('register_report'); ?></span>
															</a>
														</li>
													</ul>
												</li>
												<li class="mm_product_report">
												<a class="dropmenu" href="#">
													<i class="fa fa-barcode"></i>
													<span class="text"> <?= lang('product_report'); ?> </span>
													<span class="chevron closed"></span>
												</a>
												<ul>  
														<?php } ?>
															<li id="reports_quantity_alerts">
																<a href="<?= site_url('reports/quantity_alerts') ?>">
																	<i class="fa fa-barcode"></i><span class="text"> <?= lang('product_quantity_alerts'); ?></span>
																</a>
															</li>
															<?php if ($this->Settings->product_expiry) { ?>
																<li id="reports_expiry_alerts">
																	<a href="<?= site_url('reports/expiry_alerts') ?>">
																		<i class="fa fa-barcode"></i><span class="text"> <?= lang('product_expiry_alerts'); ?></span>
																	</a>
																</li>
															<?php } ?>
																	<li id="reports_inventory_inout">
																		<a href="<?= site_url('reports/inventory_inout') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('products_in_out'); ?></span>
																		</a>
																	</li>
																	<li id="reports_warehouse_products">
																		<a href="<?= site_url('reports/warehouse_products') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('warehouse_products'); ?></span>
																		</a>
																	</li>
																																
																	<li id="reports_product_monthlyinout">
																		<a href="<?= site_url('reports/product_monthlyinout') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('monthly_product'); ?></span>
																		</a>
																	</li>
																	<li id="reports_product_dailyinout">
																		<a href="<?= site_url('reports/product_dailyinout') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('daily_product'); ?></span>
																		</a>
																	</li>
																	<li id="reports_categories">
																		<a href="<?= site_url('reports/categories') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('categories_report'); ?></span>
																		</a>
																	</li>
																	<li id="reports_categories_value">
																		<a href="<?= site_url('reports/categories_value') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('categories_value_report'); ?></span>
																		</a>
																	</li>
																	<li id="reports_adjustment_report">
																		<a href="<?= site_url('reports/adjustment_report') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('adjustment_report'); ?></span>
																		</a>
																	</li>
																	<li id="reports_inventory_valuation_detail">
																		<a href="<?= site_url('reports/inventory_valuation_detail') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('inventory_valuation_detail'); ?></span>
																		</a>
																	</li>
																	<li id="reports_supplier_details">
																		<a href="<?= site_url('reports/supplier_details') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('supplier_products'); ?></span>
																		</a>
																	</li>
																	<li id="reports_supplier_details">
																		<a href="<?= site_url('reports/product_profit') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('product_profit'); ?></span>
																		</a>
																	</li>
																	<li id="reports_customer_details">
																		<a href="<?= site_url('reports/customer_details') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('product_customers'); ?></span>
																		</a>
																	</li>
																	<li id="reports_customer_sale_top">
																		<a href="<?= site_url('reports/report_sale_top') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('product_sale_top'); ?></span>
																		</a>
																	</li>
																	<li id="reports_production_report">
																		<a href="<?= site_url('reports/production_report') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('production_report'); ?></span>
																		</a>
																	</li>
																	<li id="reports_transfers_report">
																		<a href="<?= site_url('reports/transfers_report') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('transfers_report'); ?></span>
																		</a>
																	</li>																	
																	<li id="reports_list_using_stock_report">
																		<a href="<?= site_url('reports/list_using_stock_report') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('list_using_stock_report'); ?></span>
																		</a>
																	</li>
																	<li id="reports_convert_reports">
																		<a href="<?= site_url('reports/convert_reports') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('convert_report'); ?></span>
																		</a>
																	</li>
																	<li id="reports_track_costs">
																		<a href="<?= site_url('reports/track_costs') ?>">
																			<i class="fa fa-dollar"></i><span class="text"> <?= lang('track_costs'); ?></span>
																		</a>
																	</li>
																	<!--<li id="reports_supplier_by_items">
																		<a href="<?= site_url('reports/supplier_by_items') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('supplier_products'); ?></span>
																		</a>
																	</li>																
																	<li id="reports_customer_products">
																		<a href="<?= site_url('reports/customer_products') ?>">
																			<i class="fa fa-barcode"></i><span class="text"> <?= lang('product_customers'); ?></span>
																		</a>
																	</li>-->
																</ul>
															</li>
												<li class="mm_sale_report">
													<a class="dropmenu" href="#">
														<i class="fa fa-heart"></i>
														<span class="text"> <?= lang('sale_report'); ?> </span>
														<span class="chevron closed"></span>
													</a>
													<ul> 
														<li id="reports_sales">
															<a href="<?= site_url('reports/sales') ?>">
																<i class="fa fa-heart"></i><span class="text"> <?= lang('sales_report'); ?></span>
															</a>
														</li>
														<li id="reports_sales_detail">
															<a href="<?= site_url('reports/sales_detail') ?>">
																<i class="fa fa-heart"></i><span class="text"> <?= lang('sales_detail_report'); ?></span>
															</a>
														</li>
														<li id="reports_sales_profit">
															<a href="<?= site_url('reports/sales_profit') ?>">
																<i class="fa fa-heart"></i><span class="text"> <?= lang('sales_profit_report'); ?></span>
															</a>
														</li>   
														<li id="reports_daily_sales">
															<a href="<?= site_url('reports/daily_sales') ?>">
																<i class="fa fa-calendar-o"></i><span class="text"> <?= lang('daily_sales'); ?></span>
															</a>
														</li>
														<li id="reports_monthly_sales">
															<a href="<?= site_url('reports/monthly_sales') ?>">
																<i class="fa fa-calendar-o"></i><span class="text"> <?= lang('monthly_sales'); ?></span>
															</a>
														</li>
														<li id="reports_sales_discount">
															<a href="<?= site_url('reports/sales_discount') ?>">
																<i class="fa fa-gift"></i><span class="text"> <?= lang('sales_discount_report'); ?></span>
															</a>
														</li>                                                                
														<li id="reports_deliveries">
															<a href="<?= site_url('reports/deliveries') ?>">
																<i class="fa fa-heart"></i><span class="text"> <?= lang('sale_by_delivery_person'); ?></span>
															</a>
														</li>
														<li id="reports_sales_detail_delivery">
															<a href="<?= site_url('reports/sales_detail_delivery') ?>">
																<i class="fa fa-heart"></i><span class="text"> <?= lang('sales_delivery_detail'); ?></span>
															</a>
														</li>
														<!--<li id="reports_suspend_report" <?=($this->uri->segment(2) === 'suspends' ? 'class="active"' : '')?> >
															<a href="<?= site_url('reports/suspends') ?>">
																<i class="fa fa-heart"></i><span class="text"> <?= lang('suspend_report'); ?></span>
															</a>
														</li>-->																
														<li id="reports_customers">
															<a href="<?= site_url('reports/customers') ?>">
																<i class="fa fa-users"></i><span class="text"> <?= lang('customers_report'); ?></span>
															</a>
														</li>
<!--                                                        <li id="reports_users">-->
<!--															<a href="--><?//= site_url('reports/users') ?><!--">-->
<!--																<i class="fa fa-users"></i><span class="text"> --><?//= lang('staff_report'); ?><!--</span>-->
<!--															</a>-->
<!--														</li>-->
														<li id="reports_saleman">
															<a href="<?= site_url('reports/saleman') ?>">
																<i class="fa fa-users"></i><span class="text"> <?= lang('saleman_report'); ?></span>
															</a>
														</li>
														<li id="reports_saleman_detail">
															<a href="<?= site_url('reports/saleman_detail') ?>">
																<i class="fa fa-users"></i><span class="text"> <?= lang('saleman_detail_report'); ?></span>
															</a>
														</li>
                                                        <li id="reports_shops">
															<a href="<?= site_url('reports/shops') ?>">
																<i class="fa fa-building"></i><span class="text"> <?= lang('biller_report'); ?></span>
															</a>
														</li>
														<li id="reports_sale_payment_report">
															<a href="<?= site_url('reports/sale_payment_report') ?>">
																<i class="fa fa-money"></i><span class="text"> <?= lang('sale_payment_report'); ?></span>
															</a>
														</li>
													</ul>
												</li>
															
												<li class="mm_purchase_report">
													<a class="dropmenu" href="#">
														<i class="fa fa-star"></i>
														<span class="text"> <?= lang('purchase_report'); ?> </span>
														<span class="chevron closed"></span>
													</a>
													<ul>
														<li id="reports_purchases">
															<a href="<?= site_url('reports/purchases') ?>">
																<i class="fa fa-star"></i><span class="text"> <?= lang('purchases_report'); ?></span>
															</a>
														</li>
														<li id="reports_daily_purchases">
															<a href="<?= site_url('reports/daily_purchases') ?>">
																<i class="fa fa-star"></i><span class="text"> <?= lang('daily_purchases'); ?></span>
															</a>
														</li>
														<li id="reports_monthly_purchases">
															<a href="<?= site_url('reports/monthly_purchases') ?>">
																<i class="fa fa-star"></i><span class="text"> <?= lang('monthly_purchases'); ?></span>
															</a>
														</li>
														<li id="reports_suppliers">
															<a href="<?= site_url('reports/suppliers') ?>">
																<i class="fa fa-users"></i><span class="text"> <?= lang('suppliers_report'); ?></span>
															</a>
														</li> 
														<li id="reports_expense_report">
															<a href="<?= site_url('reports/expense_report') ?>">
																<i class="fa fa-users"></i><span class="text"> <?= lang('expense_report'); ?></span>
															</a>
														</li> 
														<!--
														<li id="reports_purchase_by_invoice">
															<a href="<?= site_url('reports/purchase_report_by_invoice') ?>">
																<i class="fa fa-users"></i><span class="text"> <?= lang('purchase_report_by_invoice'); ?></span>
															</a>
														</li>
														-->
														
													</ul>
												</li>
												
												<!--
												<li class="mm_ac_report">
													<a class="dropmenu" href="#">
														<i class="fa fa-book"></i>
														<span class="text"> <?= lang('ac_report'); ?> </span>
														<span class="chevron closed"></span>
													</a>
													<ul>
														<li id="reports_ledger">
															<a href="<?= site_url('reports/ledger') ?>">
																<i class="fa fa-book"></i><span class="text"> <?= lang('ledger'); ?></span>
															</a>
														</li>
														<li id="reports_trial_balance">
															<a href="<?= site_url('reports/trial_balance') ?>">
																<i class="fa fa-bars"></i><span class="text"> <?= lang('trial_balance'); ?></span>
															</a>
														</li>
														<li id="reports_balance_sheet">
															<a href="<?= site_url('reports/balance_sheet') ?>">
																<i class="fa fa-balance-scale"></i><span class="text"> <?= lang('balance_sheet'); ?></span>
															</a>
														</li>
														<li id="reports_balance_sheet_details">
															<a href="<?= site_url('reports/balance_sheet_details') ?>">
																<i class="fa fa-balance-scale"></i><span class="text"> <?= lang('balance_sheet_details'); ?></span>
															</a>
														</li>																
														<li id="reports_income_statement">
															<a href="<?= site_url('reports/income_statement') ?>">
																<i class="fa fa-money"></i><span class="text"> <?= lang('income_statement'); ?></span>
															</a>
														</li>														
														<li id="reports_income_statement_detail">
															<a href="<?= site_url('reports/income_statement_detail') ?>">
																<i class="fa fa-money"></i><span class="text"> <?= lang('income_statement_detail'); ?></span>
															</a>
														</li>
														<li id="reports_income_statement_by_customer">
															<a href="<?= site_url('reports/income_statement_by_customer') ?>">
																<i class="fa fa-money"></i><span class="text"> <?= lang('income_statement_by_customer'); ?></span>
															</a>
														</li>
                                                        <li id="reports_income_by_project">
															<a href="<?= site_url('reports/income_statement_by_project')?>">
																<i class="fa fa-money"></i><span class="text">​ <?= lang('income_statement_by_project');?></span>
															</a>
														</li>
														<li id="reports_cash_book_report" <?=($this->uri->segment(2) === 'cash_books' ? 'class="active"' : '')?> >
															<a href="<?= site_url('reports/cash_books') ?>">
																<i class="fa fa-money"></i><span class="text"> <?= lang('cash_book'); ?></span>
															</a>
														</li>
													</ul>
												</li>
												-->
												
											</ul>
                                        </li>
                                        							
									    

                        <?php } else { ?>
							<?php if ($GP['products-index'] || $GP['products-return_list'] || $GP['products-print_barcodes'] || $GP['products-adjustments'] || $GP['products-list_using_stock'] || $GP['products-using_stock'] || $GP['product_stock_count']) { ?>
								<li class="mm_products">
									<a class="dropmenu" href="#">
										<i class="fa fa-barcode"></i>
										<span class="text"> <?= lang('manage_products'); ?> 
										</span> <span class="chevron closed"></span>
									</a>
									<ul>
										<?php if ($GP['products-index']) { ?>
										<li id="products_index">
											<a class="submenu" href="<?= site_url('products'); ?>">
												<i class="fa fa-barcode"></i><span class="text"> <?= lang('list_products'); ?></span>
											</a>
										</li>
											<?php } ?>
											<?php if ($GP['products-add']) { ?>
												<li id="products_add">
													<a class="submenu" href="<?= site_url('products/add'); ?>">
														<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_product'); ?></span>
													</a>
												</li>
											<?php } ?>


											<?php if ($GP['products-items_convert']) { ?>
												<li id="products_list_convert" class="sub_navigation">
													<a class="submenu" href="<?= site_url('products/list_convert'); ?>">
														<i class="fa fa-barcode"></i>
														<span class="text"> <?= lang('list_convert'); ?></span>
													</a>
												</li>
												<li id="products_items_convert" class="sub_navigation">
													<a class="submenu" href="<?= site_url('products/items_convert'); ?>">
														<i class="fa fa-plus-circle"></i>
														<span class="text"> <?= lang('add_convert'); ?></span>
													</a>
												</li>
											<?php } ?>

											
											<?php if ($GP['products_convert_add']) { ?>
												<li id="products_items_convert" class="sub_navigation">
													<a class="submenu" href="<?= site_url('products/items_convert'); ?>">
														<i class="fa fa-plus-circle"></i>
														<span class="text"> <?= lang('add_convert'); ?></span>
													</a>
												</li>
											<?php } ?>
											
											<?php if ($GP['products-return_list']) { ?>
												<!--<li id="products_return_products" class="sub_navigation">
													<a class="submenu" href="<?= site_url('products/return_products'); ?>">
														<i class="fa fa-retweet"></i>
														<span class="text"> <?= lang('list_products_return'); ?></span>
													</a>
												</li>-->
											<?php } ?>
											<?php if ($GP['products-print_barcodes']) { ?>
												<li id="products_print_barcodes" class="sub_navigation">
													<a class="submenu" href="<?= site_url('products/print_barcodes'); ?>">
														<i class="fa fa-tags"></i>
														<span class="text"> <?= lang('print_barcode_label'); ?></span>
													</a>
												</li>
											<?php } ?>
											
											<?php if ($GP['products-adjustments']) { ?>
													<li id="products_quantity_adjustments">
														<a class="submenu" href="<?= site_url('products/quantity_adjustments'); ?>">
															<i class="fa fa-filter"></i><span class="text"> <?= lang('adjustment_quantity'); ?></span>
														</a>
													</li>
													<li id="products_add_adjustment_multiple">
														<a class="submenu" href="<?= site_url('products/add_adjustment_multiple'); ?>">
															<i class="fa fa-plus-circle"></i>
															<span class="text"> <?= lang('add_adjustment_multiple'); ?></span>
														</a>
													</li>
											<?php } ?>
											
											<?php if ($GP['products-list_using_stock']) { ?>
                                                <li id="products_view_using_stock" class="sub_navigation">
                                                    <a class="submenu"
                                                       href="<?= site_url('products/view_using_stock'); ?>">
														<i class="fa fa-filter"></i>
														<span class="text"> <?= lang('list_stock_using'); ?></span>
													</a>
												</li>
											<?php } ?>
											<?php if ($GP['products-using_stock']) { ?>
                                                <li id="products_using_stock" class="sub_navigation">
                                                    <a class="submenu" href="<?= site_url('products/using_stock'); ?>">
														<i class="fa fa-plus-circle"></i>
														<span class="text"> <?= lang('add_stock_using'); ?></span>
													</a>
												</li>
											<?php } ?>
											<!--
											<?php if ($GP['products-count_stocks']) { ?>
												<li id="products_barcode_count_stock" class="sub_navigation">
														<a class="submenu" href="<?= site_url('products/barcode_count_stock'); ?>">
															<i class="fa fa-filter"></i>
															<span class="text"> <?= lang('barcode_count_stock'); ?></span>
														</a>
													</li>
													<li id="products_list_count_stock" class="sub_navigation">
														<a class="submenu" href="<?= site_url('products/list_count_stock'); ?>">
															<i class="fa fa-filter"></i>
															<span class="text"> <?= lang('list_count_stock'); ?></span>
														</a>
													</li>
													<li id="products_add_count_stock" class="sub_navigation">
														<a class="submenu" href="<?= site_url('products/add_count_stock'); ?>">
															<i class="fa fa-plus-circle"></i>
															<span class="text"> <?= lang('add_count_stock'); ?></span>
														</a>
													</li>
											<?php } ?>
											-->
											
											<!-- <?php if ($GP['products-import']) { ?>
												<li id="products_import_csv" class="sub_navigation">
													<a class="submenu" href="<?= site_url('products/import_csv'); ?>">
														<i class="fa fa-file-text"></i>
														<span class="text"> <?= lang('import_products'); ?></span>
													</a>
												</li>
											<?php } ?> -->
											<?php if ($GP['product_import_quantity']) { ?>
												<li id="products_update_quantity" class="sub_navigation">
													<a class="submenu" href="<?= site_url('products/update_quantity'); ?>">
														<i class="fa fa-file-text"></i>
														<span class="text"> <?= lang('update_quantity'); ?></span>
													</a>
												</li>
											<?php } ?>
											<?php if ($GP['product_import_price_cost']) { ?>
												<li id="products_update_price" class="sub_navigation">
													<a class="submenu" href="<?= site_url('products/update_price'); ?>">
														<i class="fa fa-file-text"></i>
														<span class="text"> <?= lang('Import_Price/Cost'); ?></span>
													</a>
												</li>
											<?php } ?>
									</ul>
								</li>
							<?php } ?>

							<?php if ($GP['pos-index'] || $GP['sale_order-index'] || $GP['sale_order-add'] || $GP['sales-index'] || $GP['sales-add'] || $GP['sales-deliveries'] || $GP['sales-add_delivery'] || $GP['sales-gift_cards'] || $GP['sales-opening_ar'] || $GP['sales-loan'] || $GP['sales-return_sales'] || $GP['room-index'] || $GP['sale-room-index']){ ?>
								<li class="mm_sales mm_sale_order <?= strtolower($this->router->fetch_method()) == 'settings' ? '' : 'mm_pos' ?>">
									<a class="dropmenu" href="#">
										<i class="fa fa-heart"></i>
										<span class="text"> <?= lang('manage_sales'); ?> 
										</span> <span class="chevron closed"></span>
									</a>
									<ul>
										<?php 
										if ($GP['pos-index']) { ?>
											<li id="pos_sales">
												<a class="submenu" href="<?= site_url('pos/sales'); ?>">
													<i class="fa fa-heart"></i><span class="text"> <?= lang('pos_sales'); ?></span>
												</a>
											</li>
											<li id="add_pos_sales">
											<a class="submenu" href="<?= site_url('pos'); ?>">
												<i class="fa fa-plus-circle"></i>
												<span class="text"> <?= lang('add_pos_sale'); ?></span>
											</a>
											</li>
										<?php }
										if ($GP['sale_order-index']) { ?>
											<li id="sale_order_list_sale_order">
												<a class="submenu" href="<?= site_url('sale_order/list_sale_order'); ?>">
													<i class="fa fa-heart"></i>
													<span class="text"> <?= lang('list_sales_order'); ?></span>
												</a>
											</li>															
										<?php } 
										if ($GP['sale_order-add']) { ?>
											<li id="sale_order_add_sale_order">
												<a class="submenu" href="<?= site_url('sale_order/add_sale_order'); ?>">
													<i class="fa fa-plus-circle"></i>
													<span class="text"> <?= lang('add_sale_order'); ?></span>
												</a>
											</li>															
										<?php }
										if ($GP['sales-index']) { ?>
										<li id="sales_index">
											<a class="submenu" href="<?= site_url('sales'); ?>">
												<i class="fa fa-heart"></i><span class="text"> <?= lang('list_sales'); ?></span>
											</a>
										</li>
										
										<?php } 
										if ($GP['sales-add']) { ?>
											<li id="sales_add">
												<a class="submenu" href="<?= site_url('sales/add'); ?>">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_sale'); ?></span>
												</a>
											</li>
										<?php }
										if ($GP['sales-deliveries']) { ?>
										<li id="sales_deliveries">
											<a class="submenu" href="<?= site_url('sales/deliveries'); ?>">
												<i class="fa fa-truck"></i><span class="text"> <?= lang('list_deliveries'); ?></span>
											</a>
										</li>
										<?php } 
										if ($GP['sales-add_delivery']) { ?>
											<li id="sales_add_deliveries">
												<a class="submenu" href="<?= site_url('sales/add_deliveries'); ?>">
													<i class="fa fa-plus-circle"></i>
													<span class="text"> <?= lang('add_deliveries'); ?></span>
												</a>
											</li>
										<?php }
										if ($GP['customers_balance']) { ?>
											<li id="sales_customer_balance">
												<a class="submenu" href="<?= site_url('sales/customer_balance'); ?>">
													<i class="fa fa-money"></i>
													<span class="text"> <?= lang('customer_balance'); ?></span>
												</a>
											</li>
										<?php }														
										if ($GP['sales-opening_ar']) { ?>
											<li id="sales_customer_opening_balance">
													<a class="submenu" href="<?= site_url('sales/customer_opening_balance'); ?>">
														<i class="fa fa-money"></i>
														<span class="text"> <?= lang('opening_ar'); ?></span>
													</a>
											</li>
										<?php }
                                        if ($GP['sales-gift_cards']) { ?>
                                            <li id="sales_gift_cards">
                                                <a class="submenu" href="<?= site_url('sales/gift_cards'); ?>">
                                                    <i class="fa fa-credit-card"></i><span class="text"> <?= lang('list_gift_cards'); ?></span>
                                                </a>
                                            </li>
                                        <?php }
										if ($GP['sales-return_sales']) { ?>
											<!--
											<li id="sales_add_return">
												<a class="submenu" href="<?= site_url('sales/add_return'); ?>">
													<i class="fa fa-plus-circle"></i>
													<span class="text"> <?= lang('add_sale_return'); ?></span>
												</a>
											</li>
											-->
										<?php } ?>
										<?php if($GP['room-index']){ ?>
											<li id="sales_house_calendar">
												<a class="submenu" href="<?= site_url('sales/house_calendar'); ?>">
													<i class="fa fa-building-o tip"></i>
													<span class="text"> <?= lang('house_calendar'); ?></span>
												</a>
											</li>
										<?php } ?>
											
										<?php if($GP['sale-room-index']){ ?>
											<li id="sales_house_sales">
												<a class="submenu" href="<?= site_url('sales/house_sales'); ?>">
													<i class="fa fa-building"></i>
													<span class="text"> <?= lang('list_house'); ?></span>
												</a>
											</li>
										<?php } ?>
                                        <?php
                                            if ($GP['sales-loan']) { ?>
                                            <li id="sales_sales_loans">
                                                <a class="submenu" href="<?= site_url('sales/sales_loans'); ?>">
                                                    <i class="fa fa-money"></i>
                                                    <span class="text"> <?= lang('installment'); ?></span>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($GP['sales-return_sales']) { ?>
                                            
                                            <li id="sales_return_sales">
                                                <a class="submenu" href="<?= site_url('sales/return_sales'); ?>">
                                                    <i class="fa fa-reply"></i><span class="text"> <?= lang('list_sales_return'); ?></span>
                                                </a>
                                            </li>
                                            
                                        <?php } ?>
									</ul>
								</li>
							<?php } ?>
											
							<?php if ($GP['quotes-index']) { ?>
								<li class="mm_quotes">
									<a class="dropmenu" href="#">
										<i class="fa fa-heart-o"></i>
										<span class="text"> <?= lang('manage_quotes'); ?> </span>
										<span class="chevron closed"></span>
									</a>
									<ul><?php if ($GP['quotes-index']) { ?>
										<li id="quotes_index">
											<a class="submenu" href="<?= site_url('quotes'); ?>">
												<i class="fa fa-heart-o"></i><span class="text"> <?= lang('list_quotes'); ?></span>
											</a>
										</li>
										<?php } ?>
										<?php if ($GP['quotes-add']) { ?>
											<li id="quotes_add">
												<a class="submenu" href="<?= site_url('quotes/add'); ?>">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_quote'); ?></span>
												</a>
											</li>
										<?php } ?>
									</ul>
								</li>
							<?php } ?>

							<?php if ($GP['purchases-index'] || $GP['purchases_order-index'] || $GP['purchase_request-index'] || $GP['purchases-expenses'] || $GP['purchases-return_list'] || $GP['purchases-return_list'] || $GP['purchases-supplier_balance']) { ?>
								<li class="mm_purchases mm_purchases_request">
									<a class="dropmenu" href="#">
										<i class="fa fa-star"></i>
										<span class="text"> <?= lang('manage_purchases'); ?> 
										</span> <span class="chevron closed"></span>
									</a>
									<ul>
										<?php if ($GP['purchase_request-index']) { ?>
										<li id="purchases_request_index">
											<a class="submenu" href="<?= site_url('purchases_request'); ?>">
												<i class="fa fa-star"></i>
												<span class="text"> <?= lang('list_purchase_request'); ?></span>
											</a>
										</li>
										<?php } ?>
										<?php if ($GP['purchase_request-add']) { ?>
											<li id="purchases_request_add">
												<a class="submenu" href="<?= site_url('purchases_request/add'); ?>">
													<i class="fa fa-plus-circle"></i>
													<span class="text"> <?= lang('add_purchase_request'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['purchases_order-index']){ ?>
										<li id="purchases_purchase_order">
											<a class="submenu" href="<?= site_url('purchases/purchase_order'); ?>">
												<i class="fa fa-star"></i>
												<span class="text"> <?= lang('list_purchase_order'); ?></span>
											</a>
										</li>
										<?php } ?>
										<?php if ($GP['purchases_order-add']) { ?>	
											<li id="purchases_add_purchase_order">
												<a class="submenu" href="<?= site_url('purchases/add_purchase_order'); ?>">
													<i class="fa fa-plus-circle"></i>
													<span class="text"> <?= lang('add_purchase_order'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['purchases-index']){ ?>
										<li id="purchases_index">
											<a class="submenu" href="<?= site_url('purchases'); ?>">
												<i class="fa fa-star"></i><span class="text"> <?= lang('list_purchases'); ?></span>
											</a>
										</li>
										<?php } ?>
										
										<?php if ($GP['purchases-add']) { ?>
											<li id="purchases_add">
												<a class="submenu" href="<?= site_url('purchases/add'); ?>">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_purchase'); ?></span>
												</a>
											</li>
										<?php } ?>
										
										<?php if ($GP['purchases-supplier_balance']) { ?>
											<li id="purchases_supplier_balance">
												<a class="submenu" href="<?= site_url('purchases/supplier_balance'); ?>">
													<i class="fa fa-money"></i>
													<span class="text"> <?= lang('supplier_balance'); ?></span>
												</a>
											</li>
										<?php } ?>
										<!--
										<?php if ($GP['purchases-return_list']) { ?>
                                        <li id="purchases_return_purchases">
                                            <a class="submenu" href="<?= site_url('purchases/return_purchases'); ?>">
                                                <i class="fa fa-reply"></i>
                                                <span class="text"> <?= lang('list_purchases_return'); ?></span>
                                            </a>
                                        </li>
                                        <?php } ?>
                                        -->
										<?php if ($GP['purchases-expenses']) { ?>
											<li id="purchases_expenses">
												<a class="submenu" href="<?= site_url('purchases/expenses'); ?>">
													<i class="fa fa-dollar"></i><span class="text"> <?= lang('list_expenses'); ?></span>
												</a>
											</li>
										<?php } ?>
										
										<?php if ($GP['purchases_add-expenses']) { ?>
											<li id="purchases_add_expense">
												<a class="submenu" href="<?= site_url('purchases/add_expense'); ?>" data-toggle="modal" data-target="#myModal">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_expense'); ?></span>
												</a>
											</li>
										<?php } ?>
										<!--
										<?php if ($GP['purchases-return_add']) { ?>
										<li id="purchases_add_purchase_return">
											<a class="submenu" href="<?= site_url('purchases/add_purchase_return'); ?>">
												<i class="fa fa-plus-circle"></i>
												<span class="text"> <?= lang('add_purchase_return'); ?></span>
											</a>
										</li>
										<?php } ?>
										-->
										<?php if ($GP['purchases-import_expanse']) { ?>
											<li id="purchases_expense_by_csv">
												<a class="submenu" href="<?= site_url('purchases/expense_by_csv'); ?>">
													<i class="fa fa-file-text"></i>
													<span class="text"> <?= lang('import_expense'); ?></span>
												</a>
											</li>
										<?php } ?>
										
										<!-- purchases request -->
										
										<?php if ($GP['purchases-import_expanse']) { ?>
											<!--<li id="purchases_expense_by_csv">
												<a class="submenu" href="<?= site_url('purchases/expense_by_csv'); ?>">
													<i class="fa fa-file-text"></i>
													<span class="text"> <?= lang('import_expense'); ?></span>
												</a>
											</li>-->
										<?php } ?>
										
									</ul>
								</li>
							<?php } ?>
																							
							<?php if ($GP['transfers-index']) { ?>
								<li class="mm_transfers">
									<a class="dropmenu" href="#">
										<i class="fa fa-tags"></i>
										<span class="text"> <?= lang('manage_transfers'); ?> </span>
										<span class="chevron closed"></span>
									</a>
									<ul>
										<?php if ($GP['transfers-index']) { ?>
										<li id="transfers_list_in_transfer">
											<a class="submenu" href="<?= site_url('transfers/list_in_transfer'); ?>">
												<i class="fa fa-tags"></i><span class="text"> <?= lang('list_transfers'); ?></span>
											</a>
										</li>
										<?php } ?>
										<?php if ($GP['transfers-add']) { ?>
											<li id="transfers_add">
												<a class="submenu" href="<?= site_url('transfers/add'); ?>">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_transfer'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['transfers-export']) { ?>
											<!--<li id="transfers_transfer_by_csv">
												<a class="submenu" href="<?= site_url('transfers/transfer_by_csv'); ?>">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_transfer_by_csv'); ?></span>
												</a>
											</li>-->
										<?php } ?>
									</ul>
								</li>
							<?php } ?>
										
							<!--
							<?php if ($GP['accounts-index'] || $GP['account-list_receivable'] || $GP['account-list_ar_aging'] || $GP['account-ar_by_customer'] || $GP['account-bill_receipt'] || $GP['account-list_payable'] || $GP['account-list_ap_aging'] || $GP['account-ap_by_supplier'] || $GP['account-bill_payable'] || $GP['account-list_ac_head'] || $GP['account-list_customer_deposit'] || $GP['account-list_supplier_deposit'] || $GP['account_setting']) { ?>											
								<li class="mm_account">
									<a class="dropmenu" href="#">
										<i class="fa fa-book"></i>
										<span class="text"> <?= lang('manage_accounts') ?></span>
										<span class="chevron closed"></span>
									</a>
									<ul>
										<?php if ($GP['accounts-index']) { ?>
											<li id="account_listjournal">
												<a class="submenu" href="<?= site_url('account/listJournal'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('list_journal'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['accounts-add']) { ?>
											<li id="account_add_journal">
												<a class="submenu" href="<?= site_url('account/add_journal'); ?>" data-toggle="modal" data-target="#myModal">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_journal'); ?></span>
												</a>
											</li>
										<?php } ?>
										
										<?php if ($GP['account-list_receivable']) { ?>
											<li id="account_list_ac_recevable">
												<a class="submenu" href="<?= site_url('account/list_ac_recevable'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('list_ac_receivable'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['account-list_ar_aging']) { ?>
											<li id="account_list_ar_aging">
												<a class="submenu" href="<?= site_url('account/list_ar_aging'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('list_ar_aging'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['account-ar_by_customer']) { ?>
										<li id="account_ar_by_customer">
											<a class="submenu" href="<?= site_url('account/ar_by_customer'); ?>">
												<i class="fa fa-list"></i><span class="text"> <?= lang('ar_by_customer'); ?></span>
											</a>
										</li>
										<?php } ?>
										<?php if ($GP['account-bill_receipt']) { ?>
											<li id="account_billreceipt">
												<a href="<?= site_url('account/billReceipt') ?>">
													<i class="fa fa-money"></i><span class="text"> <?= lang('bill_receipt'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['account-list_payable']) { ?>
											<li id="account_list_ac_payable">
												<a class="submenu" href="<?= site_url('account/list_ac_payable'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('account_payable_list'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['account-list_ap_aging']) { ?>
											<li id="account_list_ap_aging">
												<a class="submenu" href="<?= site_url('account/list_ap_aging'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('list_ap_aging'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['account-ap_by_supplier']) { ?>
											<li id="account_ap_by_supplier">
												<a class="submenu" href="<?= site_url('account/ap_by_supplier'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('ap_by_supplier'); ?></span>
												</a>
											</li>
										<?php } ?>
										
										<?php if ($GP['account-bill_payable']) { ?>
											<li id="account_billpayable">
												<a href="<?= site_url('account/billPayable') ?>">
													<i class="fa fa-money"></i><span class="text"> <?= lang('bill_payable'); ?></span>
												</a>
											</li>
										<?php } ?>
										
										<?php if ($GP['account-list_ac_head']) { ?>
											<li id="account_index">
												<a class="submenu" href="<?= site_url('account'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('list_ac_head'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['account-add_ac_head']) { ?>
											<li id="account_add">
												<a class="submenu" href="<?= site_url('account/add'); ?>" data-toggle="modal" data-target="#myModal">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_ac_head'); ?></span>
												</a>
											</li>
										<?php } ?>
										
										<?php if ($GP['account-list_customer_deposit']) { ?>
											<li id="account_deposits">
												<a class="submenu" href="<?= site_url('account/deposits'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('list_customer_deposit'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['account-add_customer_deposit']) { ?>
											<li id="account_deposits">
												<a class="submenu" href="<?= site_url('quotes/add_deposit'); ?>" data-toggle="modal" data-target="#myModal" id="add">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_customer_deposit'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['account-list_supplier_deposit']) { ?>
											<li id="account_deposits">
												<a class="submenu" href="<?= site_url('suppliers/deposits'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('list_supplier_deposit'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['account-add_supplier_deposit']) { ?>
											<li id="account_deposits">
												<a class="submenu" href="<?= site_url('suppliers/add_deposit'); ?>" data-toggle="modal" data-target="#myModal" id="add">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_supplier_deposit'); ?></span>
												</a>
											</li>
										<?php } ?> 
										<?php if ($GP['account_setting']) { ?>
											<li id="account_settings">
												<a href="<?= site_url('account/settings') ?>">
													<i class="fa fa-cog"></i><span class="text"> <?= lang('account_settings'); ?></span>
												</a>
											</li>
										<?php } ?>
										
									</ul>
								</li>
							<?php } ?>
							-->
												<!--
												<li class="mm_taxes">
													<a class="dropmenu" href="#">
														<i class="fa fa-book"></i>
														<span class="text"> <?= lang('manage_gov_taxs') ?></span>
														<span class="chevron closed"></span>
													</a>
													<ul>
														<li id="taxes_selling_tax">
															<a class="submenu" href="<?= site_url('taxes/selling_tax'); ?>" >
																<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('selling_tax'); ?></span>
															</a>
														</li>
														<li id="taxes_purchasing_tax">
															<a class="submenu" href="<?= site_url('taxes/purchasing_tax'); ?>" >
																<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('purchasing_tax'); ?></span>
															</a>
														</li>
														<li id="taxes_staffing_tax">
															<a class="submenu" href="<?= site_url('account/staffing_tax'); ?>" data-toggle="modal" data-target="#myModal">
																<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('staffing_tax'); ?></span>
															</a>
														</li>
														<li id="taxes_condition_tax">
															<a class="submenu" href="<?= site_url('account/condition_tax'); ?>" data-toggle="modal" data-target="#myModal">
																<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('condition_tax'); ?></span>
															</a>
														</li>
														<li id="taxes_exchange_rate_tax">
															<a class="submenu" href="<?= site_url('taxes/exchange_rate_tax'); ?>" >
																<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('exchange_rate_tax'); ?></span>
															</a>
														</li>
														<li id="taxes_account_tax">
															<a class="submenu" href="<?= site_url('account/list_tax'); ?>" data-toggle="modal" data-target="#myModal">
																<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('list_ac_head_tax'); ?></span>
															</a>
														</li>
														<li id="taxes_salary_tax">
															<a class="submenu" href="<?= site_url('account/salary_tax'); ?>" data-toggle="modal" data-target="#myModal">
																<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('salary_tax'); ?></span>
															</a>
														</li>
														<li id="taxes_value_added_tax">
															<a class="submenu" href="<?= site_url('account/value_added_tax'); ?>" data-toggle="modal" data-target="#myModal">
																<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('value_added_tax'); ?></span>
															</a>
														</li>
														<li id="taxes_withholding_tax">
															<a class="submenu" href="<?= site_url('account/withholding_tax'); ?>" data-toggle="modal" data-target="#myModal">
																<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('withholding_tax'); ?></span>
															</a>
														</li>
														<li id="taxes_profit_tax">
															<a class="submenu" href="<?= site_url('account/profit_tax'); ?>" data-toggle="modal" data-target="#myModal">
																<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('profit_tax'); ?></span>
															</a>
														</li>                                       
													</ul>
												</li>
												-->																				

							<?php if ($GP['customers-index'] || $GP['suppliers-index'] || $GP['drivers-index'] ) { ?>
							
								<li class="mm_auth mm_customers mm_suppliers mm_billers">
									<a class="dropmenu" href="#">
										<i class="fa fa-users"></i>
										<span class="text"> <?= lang('manage_people'); ?> </span>
										<span class="chevron closed"></span>
									</a>
									<ul>
										
										<?php if ($GP['customers-index']) { ?>
											<li id="customers_index">
												<a class="submenu" href="<?= site_url('customers'); ?>">
													<i class="fa fa-users"></i><span class="text"> <?= lang('list_customers'); ?></span>
												</a>
											</li>
										<?php }
										
											if ($GP['customers-add']) { ?>
												<li id="customers_index">
													<a class="submenu" href="<?= site_url('customers/add'); ?>" data-toggle="modal" data-target="#myModal">
														<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_customer'); ?></span>
													</a>
												</li>
										<?php } ?>
										<?php if ($GP['drivers-index']){ ?>	
											<li id="drivers_index">
												<a class="submenu" href="<?= site_url('drivers'); ?>">
													<i class="fa fa-users"></i><span class="text"> <?= lang('list_drivers'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['drivers-add']){ ?>	
											<li id="drivers_index">
												<a class="submenu" href="<?= site_url('drivers/add'); ?>" data-toggle="modal" data-target="#myModal">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_driver'); ?></span>
												</a>
											</li>
										<?php } ?>
                                        <?php if ($GP['suppliers-index']) { ?>
                                                <li id="suppliers_index">
                                                    <a class="submenu" href="<?= site_url('suppliers'); ?>">
                                                        <i class="fa fa-users"></i><span class="text"> <?= lang('list_suppliers'); ?></span>
                                                    </a>
                                                </li>
                                        <?php }
                                            if ($GP['suppliers-add']) { ?>
                                                <li id="suppliers_index">
                                                    <a class="submenu" href="<?= site_url('suppliers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                        <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_supplier'); ?></span>
                                                    </a>
                                                </li>
                                        <?php } ?>
										
									</ul>
								</li>
							<?php } ?>
							<!--
							<li class="mm_taxes_reports">
								<a class="dropmenu" href="#">
									<i class="fa fa-bar-chart-o"></i>
									<span class="text"> <?= lang('gov_reports'); ?> </span>
									<span class="chevron closed"></span>
								</a>
								<ul>
									<li id="govreports_salary_tax">
										<a href="<?= site_url('govreports/salary_tax') ?>">
											<i class="fa fa-bars"></i><span class="text"> <?= lang('salary_tax'); ?></span>
										</a>
									</li>
									
									<li id="govreports_value_added_tax">
										<a href="<?= site_url('govreports/warehouse_stock') ?>">
											<i class="fa fa-building"></i><span class="text"> <?= lang('value_added_tax'); ?></span>
										</a>
									</li>
									<li id="govreports_profit_tax">
										<a href="<?= site_url('govreports/profit_tax') ?>">
											<i class="fa fa-building"></i><span class="text"> <?= lang('profit_tax'); ?></span>
										</a>
									</li>
									<li id="govreports_annual_profit_tax">
										<a href="<?= site_url('govreports/annual_profit_tax') ?>">
											<i class="fa fa-bars"></i><span class="text"> <?= lang('annual_profit_tax'); ?></span>
										</a>
									</li>
									<li id="govreports_sales_journal_list">
										<a href="<?= site_url('taxes_reports/sales_journal_list') ?>">
											<i class="fa fa-building"></i><span class="text"> <?= lang('sales_journal_list'); ?></span>
										</a>
									</li>
									<li id="taxes_reports_purchase_journal_list">
										<a href="<?= site_url('taxes_reports/purchase_journal_list') ?>">
											<i class="fa fa-building"></i><span class="text"> <?= lang('purchase_journal_list'); ?></span>
										</a>
									</li>
									<li id="govreports_tax_salary_list">
										<a href="<?= site_url('govreports/tax_salary_list') ?>">
											<i class="fa fa-building"></i><span class="text"> <?= lang('tax_salary_list'); ?></span>
										</a>
									</li>
									<li id="govreports_trial_balance">
										<a href="<?= site_url('govreports/trial_balance') ?>">
											<i class="fa fa-building"></i><span class="text"> <?= lang('trial_balance'); ?></span>
										</a>
									</li>
									<li id="govreports_profit_lost">
										<a href="<?= site_url('govreports/profit_lost') ?>">
											<i class="fa fa-building"></i><span class="text"> <?= lang('profit_lost'); ?></span>
										</a>
									</li>
									<li id="govreports_balance_sheet">
										<a href="<?= site_url('govreports/balance_sheet') ?>">
											<i class="fa fa-building"></i><span class="text"> <?= lang('balance_sheet'); ?></span>
										</a>
									</li>
									<li id="govreports_balance_sheet_tax">
										<a href="<?= site_url('govreports/balance_sheet_tax') ?>">
											<i class="fa fa-building"></i><span class="text"> <?= lang('balance_sheet_tax'); ?></span>
										</a>
									</li>
									<li id="govreports_profit_lost_tax">
										<a href="<?= site_url('govreports/profit_lost_tax') ?>">
											<i class="fa fa-building"></i><span class="text"> <?= lang('profit_lost_tax'); ?></span>
										</a>
									</li>
								</ul>
							</li>
							-->
							
							<?php if($GP['reports-index']){ ?>
								<li class="mm_reports">
									<a class="dropmenu" href="#">
										<i class="fa fa-pie-chart"></i>
										<span class="text"> <?= lang('reports'); ?> </span>
										<span class="chevron closed"></span>
									</a>
									
									<ul>
										<?php if($GP['chart_report-index']){ ?>
										<li class="mm_chart_report">
											<a class="dropmenu" href="#">
												<i class="fa fa-bar-chart-o"></i>
												<span class="text"> <?= lang('chart_report'); ?> </span>
												<span class="chevron closed"></span>
											</a>
											<ul>
														
												<?php if($GP['chart_report-over_view']){ ?>
													<li id="reports_index">
														<a href="<?= site_url('reports') ?>">
															<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('overview_chart'); ?></span>
														</a>
													</li>
												<?php } ?>
												
												<?php if($GP['chart_report-warehouse_stock']){ ?>
													<li id="reports_warehouse_stock">
														<a href="<?= site_url('reports/warehouse_stock') ?>">
															<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('warehouse_stock'); ?></span>
														</a>
													</li>
												<?php } ?>
												<?php if($GP['chart_report-category_stock']){ ?>
													<li id="reports_category_stock">
														<a href="<?= site_url('reports/category_stock') ?>">
															<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('category_stock_chart'); ?></span>
														</a>
													</li>
												<?php } ?>
												<?php if($GP['chart_report-profit']){ ?>
													<li id="reports_profit_chart">
														<a href="<?= site_url('reports/profit_chart') ?>">
															<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('profit_chart'); ?></span>
														</a>
													</li>
												<?php } ?>
												<?php if($GP['chart_report-cash_analysis']){ ?>
													<li id="reports_cash_chart">
														<a href="<?= site_url('reports/cash_chart') ?>">
															<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('cash_analysis_chart'); ?></span>
														</a>
													</li>
												<?php } ?>
												
											</ul>
										</li>
										<?php } ?>
										
										<?php if($GP['report_profit-index']){ ?>
										<li class="mm_profit_report">
											<a class="dropmenu" href="#">
												<i class="fa fa-money"></i>
												<span class="text"> <?= lang('profit_report'); ?> </span>
												<span class="chevron closed"></span>
											</a>
											<ul>
                                                <?php if ($GP['report_profit-profit_andOr_lose']) { ?>
												<li id="reports_profit_loss">
													<a href="<?= site_url('reports/profit_loss') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('profit_and_loss'); ?></span>
													</a>
												</li>
                                                <?php } ?>

                                                <?php if ($GP['report_profit-payments']) { ?>
												<li id="reports_payments">
													<a href="<?= site_url('reports/payments') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('payments_report'); ?></span>
													</a>
												</li>
                                                <?php } ?>

												<?php if ($GP['sale_report-register']) { ?>
												<li id="reports_register">
													<a href="<?= site_url('reports/register') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('register_report'); ?></span>
													</a>
												</li>
												<?php } ?>

											</ul>
										</li>
										<?php } ?>
										
										<?php if($GP['product_report-index']){ ?>
										<li class="mm_product_report">
											<a class="dropmenu" href="#">
												<i class="fa fa-barcode"></i>
												<span class="text"> <?= lang('product_report'); ?> </span>
												<span class="chevron closed"></span>
											</a>
											<ul>  
												<?php if($GP['product_report-quantity_alert']){ ?>
													<li id="reports_quantity_alerts">
														<a href="<?= site_url('reports/quantity_alerts') ?>">
															<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('product_quantity_alerts'); ?></span>
														</a>
													</li>
												<?php } ?>
												<?php if ($this->Settings->product_expiry) { ?>
													<li id="reports_expiry_alerts">
														<a href="<?= site_url('reports/expiry_alerts') ?>">
															<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('product_expiry_alerts'); ?></span>
														</a>
													</li>
												<?php } ?>
												<?php if($GP['product_report-product']){ ?>
													<!--<li id="reports_products">
														<a href="<?= site_url('reports/products') ?>">
															<i class="fa fa-barcode"></i><span class="text"> <?= lang('products_report'); ?></span>
														</a>
													</li>-->
												<?php } ?>
												<?php if($GP['product_report-in_out']){ ?>
													<li id="reports_inventory_inout">
														<a href="<?= site_url('reports/inventory_inout') ?>">
															<i class="fa fa-money"></i><span class="text"> <?= lang('products_in_out'); ?></span>
														</a>
													</li>
												<?php } ?>
                                                <?php if($GP['product_report-warehouse']){ ?>
                                                    <li id="reports_warehouse_products">
                                                        <a href="<?= site_url('reports/warehouse_products') ?>">
                                                            <i class="fa fa-barcode"></i><span class="text"> <?= lang('warehouse_reports'); ?></span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
											
													<!--<li id="reports_product_price_history">
														<a href="<?= site_url('reports/product_price_history') ?>">
															<i class="fa fa-barcode"></i><span class="text"> <?= lang('product_price_history'); ?></span>
														</a>
													</li>-->
												
												<?php if($GP['product_report-monthly']){ ?>
													<li id="reports_product_monthlyinout">
														<a href="<?= site_url('reports/product_monthlyinout') ?>">
															<i class="fa fa-barcode"></i><span class="text"> <?= lang('monthly_product'); ?></span>
														</a>
													</li>
												<?php } ?>
												<?php if($GP['product_report-daily']){ ?>
													<li id="reports_product_dailyinout">
														<a href="<?= site_url('reports/product_dailyinout') ?>">
															<i class="fa fa-barcode"></i><span class="text"> <?= lang('daily_product'); ?></span>
														</a>
													</li>
												<?php } ?>
                                                <?php if($GP['product_report-categories_value']){ ?>
                                                    <li id="reports_categories_value">
                                                        <a href="<?= site_url('reports/categories_value') ?>">
                                                            <i class="fa fa-folder-open"></i><span class="text"> <?= lang('categories_value_report'); ?></span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if($GP['products-adjustments']){ ?>
                                                <li id="reports_adjustment_report">
                                                    <a href="<?= site_url('reports/adjustment_report') ?>">
                                                        <i class="fa fa-barcode"></i><span class="text"> <?= lang('adjustment_report'); ?></span>
                                                    </a>
                                                </li>
                                                <?php } ?>
                                                <?php if($GP['product_report-inventory_valuation_detail']){ ?>
                                                    <li id="reports_inventory_valuation_details">
                                                        <a href="<?= site_url('reports/inventory_valuation_detail') ?>">
                                                            <i class="fa fa-barcode"></i><span class="text"> <?= lang('inventory_valuation_detail'); ?></span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
												<?php if($GP['product_report-suppliers']){ ?>
													<li id="reports_supplier_details">
														<a href="<?= site_url('reports/supplier_details') ?>">
															<i class="fa fa-barcode"></i><span class="text"> <?= lang('supplier_products'); ?></span>
														</a>
													</li>
												<?php } ?>
												<?php if($GP['product_report-customers']){ ?>
													<li id="reports_customer_details">
														<a href="<?= site_url('reports/customer_details') ?>">
															<i class="fa fa-barcode"></i><span class="text"> <?= lang('product_customers'); ?></span>
														</a>
													</li>
												<?php } ?>
                                                <?php if($GP['reports-product_top_sale']){ ?>
                                                <li id="reports_customer_sale_top">
                                                    <a href="<?= site_url('reports/report_sale_top') ?>">
                                                        <i class="fa fa-barcode"></i><span class="text"> <?= lang('Product_sale_top'); ?></span>
                                                    </a>
                                                </li>
                                                <?php } ?>
												<?php if($GP['report_transfers']){ ?>												
												<li id="reports_transfers_report">
													<a href="<?= site_url('reports/transfers_report') ?>">
														<i class="fa fa-barcode"></i><span class="text"> <?= lang('transfers_report'); ?></span>
													</a>
												</li>	
												<?php } ?>
												<?php if($GP['report_list_using_stock']){ ?>				
												<li id="reports_list_using_stock_report">
													<a href="<?= site_url('reports/list_using_stock_report') ?>">
														<i class="fa fa-barcode"></i><span class="text"> <?= lang('list_using_stock_report'); ?></span>
													</a>
												</li>
												<?php } ?>
												<?php if($GP['report_convert']){ ?>		
												<li id="reports_convert_reports">
													<a href="<?= site_url('reports/convert_reports') ?>">
														<i class="fa fa-barcode"></i><span class="text"> <?= lang('convert_report'); ?></span>
													</a>
												</li>
												<?php } ?>														
												<?php if($GP['product_report-categories']){ ?>
													<!--<li id="reports_categories">
														<a href="<?= site_url('reports/categories') ?>">
															<i class="fa fa-folder-open"></i><span class="text"> <?= lang('categories_report'); ?></span>
														</a>
													</li>-->
												<?php } ?>
											</ul>
										</li>
										<?php } ?>
										
										<?php if($GP['sale_report-index']){ ?>
										<li class="mm_sale_report">
											<a class="dropmenu" href="#">
												<i class="fa fa-heart"></i>
												<span class="text"> <?= lang('sale_report'); ?> </span>
												<span class="chevron closed"></span>
											</a>
											<ul> 
                                                <?php if($GP['sale_report-report_sale']){ ?>
                                                    <li id="reports_sales">
                                                        <a href="<?= site_url('reports/sales') ?>">
                                                            <i class="fa fa-heart"></i><span class="text"> <?= lang('sales_report'); ?></span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
												<?php if($GP['sale_report-detail']){ ?>
													<li id="reports_sales_detail">
														<a href="<?= site_url('reports/sales_detail') ?>">
															<i class="fa fa-heart"></i><span class="text"> <?= lang('sales_detail_report'); ?></span>
														</a>
													</li>
												<?php } ?>
												<?php if($GP['sale_report-sale_profit']){ ?>
													<li id="reports_sales_profit">
														<a href="<?= site_url('reports/sales_profit') ?>">
															<i class="fa fa-heart"></i><span class="text"> <?= lang('sales_profit_report'); ?></span>
														</a>
													</li>
												<?php } ?>	
                                                <?php if($GP['sale_report-daily']){ ?>
                                                    <li id="reports_daily_sales">
                                                        <a href="<?= site_url('reports/daily_sales') ?>">
                                                            <i class="fa fa-calendar-o"></i><span class="text"> <?= lang('daily_sales'); ?></span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <?php if($GP['sale_report-monthly']){ ?>
                                                    <li id="reports_monthly_sales">
                                                        <a href="<?= site_url('reports/monthly_sales') ?>">
                                                            <i class="fa fa-calendar-o"></i><span class="text"> <?= lang('monthly_sales'); ?></span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <!-- <?php if($GP['sale_report-by_invoice']){ ?>
                                                    <li id="reports_getSaleReportByInvoice">
                                                        <a href="<?= site_url('reports/getSaleReportByInvoice') ?>">
                                                            <i class="fa fa-heart"></i><span class="text"> <?= lang('sales_report_by_invoice'); ?></span>
                                                        </a>
                                                    </li>
                                                <?php } ?> -->
												<?php if($GP['sale_report-disccount']){ ?>
													<li id="reports_sales_discount">
														<a href="<?= site_url('reports/sales_discount') ?>">
															<i class="fa fa-gift"></i><span class="text"> <?= lang('sales_discount_report'); ?></span>
														</a>
													</li>
												<?php } ?>
												<?php if($GP['sale_report-by_delivery_person']){ ?>
													<li id="reports_deliveries">
														<a href="<?= site_url('reports/deliveries') ?>">
															<i class="fa fa-heart"></i><span class="text"> <?= lang('sale_by_delivery_person'); ?></span>
														</a>
													</li>
												<?php } ?>
                                                <?php if($GP['sale_report-delivery_detail']){ ?>
                                                <li id="reports_sales_detail_delivery">
                                                    <a href="<?= site_url('reports/sales_detail_delivery') ?>">
                                                        <i class="fa fa-heart"></i><span class="text"> <?= lang('Sales_Delivery_Detail'); ?></span>
                                                    </a>
                                                </li>
                                                <?php } ?>
												<?php if($GP['sale_report-room_table']){ ?>
													<li id="reports_suspend_report" <?=($this->uri->segment(2) === 'suspends' ? 'class="active"' : '')?> >
														<a href="<?= site_url('reports/suspends') ?>">
															<i class="fa fa-heart"></i><span class="text"> <?= lang('suspend_report'); ?></span>
														</a>
													</li>
												<?php } ?>	
                                                <!-- <?php if($GP['sale_report-customer_transfers']){ ?>
                                                    <li id="reports_customers">
                                                    <a href="<?= site_url('reports/customer_transfers') ?>">
                                                        <i class="fa fa-users"></i><span class="text"> <?= lang('customer_transfers'); ?></span>
                                                    </a>
                                                </li>
                                                <?php } ?> -->
                                                <?php if($GP['sale_report-customer']){ ?>
                                                    <li id="reports_customers">
                                                        <a href="<?= site_url('reports/customers') ?>">
                                                            <i class="fa fa-users"></i><span class="text"> <?= lang('customers_report'); ?></span>
                                                        </a>
                                                    </li>
                                                <?php } ?>
<!--												--><?php //if($GP['sale_report-staff']){ ?><!--	-->
<!--													<li id="reports_users">-->
<!--														<a href="--><?//= site_url('reports/users') ?><!--">-->
<!--															<i class="fa fa-users"></i><span class="text"> --><?//= lang('staff_report'); ?><!--</span>-->
<!--														</a>-->
<!--													</li>-->
<!--												--><?php //} ?>
												<?php if($GP['sale_report-saleman']){ ?>
													<li id="reports_saleman">
														<a href="<?= site_url('reports/saleman') ?>">
															<i class="fa fa-users"></i><span class="text"> <?= lang('saleman_report'); ?></span>
														</a>
													</li>
												<?php } ?>
												<?php if($GP['sale_report-saleman_detail']){ ?>
												<li id="reports_saleman_detail">
													<a href="<?= site_url('reports/saleman_detail') ?>">
														<i class="fa fa-users"></i><span class="text"> <?= lang('saleman_detail_report_'); ?></span>
													</a>
												</li>
												<?php } ?>
												<?php if($GP['sale_report-project']){ ?>	
													<li id="reports_shops">
														<a href="<?= site_url('reports/shops') ?>">
															<i class="fa fa-building"></i><span class="text"> <?= lang('biller_report'); ?></span>
														</a>
													</li>
                                                <?php } ?>
                                                <?php if($GP['sale_report-project_manager']){ ?>
                                                    <li id="reports_project_manager_report">
                                                        <a href="<?= site_url('reports/project_manager_report') ?>">
                                                            <i class="fa fa-building"></i><span class="text"> <?= lang('project_manager_report'); ?></span>
                                                        </a>
                                                    </li>
												<?php } ?>
												<?php if($GP['sale_report-sale_payment_report']){ ?>
													<li id="reports_sale_payment_report">
														<a href="<?= site_url('reports/sale_payment_report') ?>">
															<i class="fa fa-money"></i><span class="text"> <?= lang('sale_payment_report'); ?></span>
														</a>
													</li>
												<?php } ?>

											</ul>
										</li>
										<?php }?>
										
										<?php if($GP['purchase_report-index']) { ?>
										<li class="mm_purchase_report">
											<a class="dropmenu" href="#">
												<i class="fa fa-star"></i>
												<span class="text"> <?= lang('purchase_report'); ?> </span>
												<span class="chevron closed"></span>
											</a>
											<ul>
												<?php if($GP['purchase_report-purchas']){ ?>
												<li id="reports_purchases">
													<a href="<?= site_url('reports/purchases') ?>">
														<i class="fa fa-star"></i><span class="text"> <?= lang('purchases_report'); ?></span>
													</a>
												</li>
												<?php } ?>
												<?php if($GP['purchase_report-daily']){ ?>
												<li id="reports_daily_purchases">
													<a href="<?= site_url('reports/daily_purchases') ?>">
														<i class="fa fa-star"></i><span class="text"> <?= lang('daily_purchases'); ?></span>
													</a>
												</li>
												<?php } ?>
												<?php if($GP['purchase_report-monthly']){ ?>
												<li id="reports_monthly_purchases">
													<a href="<?= site_url('reports/monthly_purchases') ?>">
														<i class="fa fa-star"></i><span class="text"> <?= lang('monthly_purchases'); ?></span>
													</a>
												</li>
												<?php } ?>
												
												<?php if($GP['purchase_report-supplier']){ ?>
												 <li id="reports_suppliers">
													<a href="<?= site_url('reports/suppliers') ?>">
														<i class="fa fa-users"></i><span class="text"> <?= lang('suppliers_report'); ?></span>
													</a>
												</li>
												<?php } ?>
											</ul>
										</li>
										<?php } ?>

										<?php if($GP['account_report-index']){ ?>
										<li class="mm_ac_report">
											<a class="dropmenu" href="#">
												<i class="fa fa-book"></i>
												<span class="text"> <?= lang('ac_report'); ?> </span>
												<span class="chevron closed"></span>
											</a>
											<ul>
												<?php if($GP['account_report-ledger']){ ?>
												<li id="reports_ledger">
													<a href="<?= site_url('reports/ledger') ?>">
														<i class="fa fa-book"></i><span class="text"> <?= lang('ledger'); ?></span>
													</a>
												</li>
												<?php } ?>
												<?php if($GP['account_report-trail_balance']){ ?>
												<li id="reports_trial_balance">
													<a href="<?= site_url('reports/trial_balance') ?>">
														<i class="fa fa-bars"></i><span class="text"> <?= lang('trial_balance'); ?></span>
													</a>
												</li>
												<?php } ?>
												<?php if($GP['account_report-balance_sheet']){ ?>
												<li id="reports_balance_sheet">
													<a href="<?= site_url('reports/balance_sheet') ?>">
														<i class="fa fa-balance-scale"></i><span class="text"> <?= lang('balance_sheet'); ?></span>
													</a>
												</li>
												<?php } ?>
												<?php if($GP['account_report-balance_sheet']){ ?>
												<li id="reports_balance_sheet_details">
													<a href="<?= site_url('reports/balance_sheet_details') ?>">
														<i class="fa fa-balance-scale"></i><span class="text"> <?= lang('balance_sheet_details'); ?></span>
													</a>
												</li>
												<?php } ?>
												<?php if($GP['account_report-income_statement']){ ?>
												<li id="reports_income_statement">
													<a href="<?= site_url('reports/income_statement') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('income_statement'); ?></span>
													</a>
												</li>
												<?php } ?>
												<?php if($GP['account_report-income_statement_detail']){ ?>
												<li id="reports_income_statement_detail">
													<a href="<?= site_url('reports/income_statement_detail') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('income_statement_detail'); ?></span>
													</a>
												</li>
												<?php } ?>
												<?php if($GP['account_report-cash_book']){ ?>
												<li id="reports_cash_book_report" <?=($this->uri->segment(2) === 'cash_books' ? 'class="active"' : '')?> >
													<a href="<?= site_url('reports/cash_books') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('cash_book'); ?></span>
													</a>
												</li>
												<?php } ?>
											</ul>
											
										</li>
										<?php }?>
									</ul>
								</li>
							<?php } ?>		
					   <?php } ?>
                        </ul>
                    </div>
                    <a href="#" id="main-menu-act" class="full visible-md visible-lg">
                        <i class="fa fa-angle-double-left"></i>
                    </a>
                </div>

                <div id="content" class="col-lg-10 col-md-10">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <ul class="breadcrumb">
                                <?php
								//$this->erp->print_arrays($bc);
                            foreach ($bc as $b) {
                                if ($b['link'] === '#') {
                                    echo '<li class="active">' . $b['page'] . '</li>';
                                } else {
                                    echo '<li><a href="' . $b['link'] . '">' . $b['page'] . '</a></li>';
                                }
                            }
                            ?>
                                    <li class="right_log hidden-xs">
                                        <?= lang('your_ip') . ' ' . $ip_address . " <span class='hidden-sm'>( " . lang('last_login_at') . ": " . date($dateFormats['php_ldate'], $this->session->userdata('old_last_login')) . " " . ($this->session->userdata('last_ip') != $ip_address ? lang('ip:') . ' ' . $this->session->userdata('last_ip') : '') . " )</span>" ?>
                                    </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <?php if ($message) { ?>
                                <div class="alert alert-success">
                                    <button data-dismiss="alert" class="close" type="button">×</button>
                                    <?= $message; ?>
                                </div>
                                <?php } ?>
                                    <?php if ($error) { ?>
                                        <div class="alert alert-danger">
                                            <button data-dismiss="alert" class="close" type="button">×</button>
                                            <?= $error; ?>
                                        </div>
                                        <?php } ?>
                                            <?php if ($warning) { ?>
                                                <div class="alert alert-warning">
                                                    <button data-dismiss="alert" class="close" type="button">×</button>
                                                    <?= $warning; ?>
                                                </div>
                                                <?php } ?>
                                                    <?php
                        if ($info) {
                            foreach ($info as $n) {
                                if (!$this->session->userdata('hidden' . $n->id)) {
                                    ?>
                                                        <div class="alert alert-info">
                                                            <a href="#" id="<?= $n->id ?>" class="close hideComment external" data-dismiss="alert">&times;</a>
                                                            <?= $n->comment; ?>
                                                        </div>
                                                        <?php }
                            }
                        } ?>
                                                            <div id="alerts"></div>