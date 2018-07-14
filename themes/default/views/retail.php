<?php
	//$this->erp->print_arrays();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <base href="<?= site_url() ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                                                    <span class="number blightOrange black"><?= sizeof($events) ?>Calander Me</span>
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
                                                echo '<li><strong>' . date($dateFormats['php_sdate'], strtotime($event->date)) . ':</strong><br>' . $this->erp->decode_html($event->data) . '</li>';
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
                                                            <?php $scanned_lang_dir = array_map(function ($path) {
                                return basename($path);
                            }, glob(APPPATH . 'language/*', GLOB_ONLYDIR));
                            foreach ($scanned_lang_dir as $entry) { ?>
                                                                <li>
                                                                    <a href="<?= site_url('welcome/language/' . $entry); ?>">
                                        <img src="<?= base_url(); ?>assets/images/<?= $entry; ?>.png" class="language-img"> 
                                        &nbsp;&nbsp;<?= ucwords($entry); ?>
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
                                                            <?php if (($Owner || $Admin) || ($qty_alert_num > 0 || $exp_alert_num > 0 || !empty($payment_customer_alert_num) || !empty($payment_purchase_alert_num) || !empty($delivery_alert_num))) { ?>
                                                                <li class="dropdown hidden-sm">
                                                                    <a class="btn blightOrange tip" title="<?= lang('alerts') ?>" data-placement="left" data-toggle="dropdown" href="#">
                                                                        <i class="fa fa-exclamation-triangle"></i><p><?= lang('alerts') ?></p>
                                                                    </a>
                                                                    <ul class="dropdown-menu pull-right">
                                                                        <li>
                                                                            <a href="<?= site_url('reports/quantity_alerts') ?>" class="">
                                                                                <span class="label label-danger pull-right" style="margin-top:3px;"><?= $qty_alert_num; ?></span>
                                                                                <span style="padding-right: 35px;"><?= lang('quantity_alerts') ?></span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="<?= site_url('reports/expiry_alerts') ?>" class="">
                                                                                <span class="label label-danger pull-right" style="margin-top:3px;"><?= $exp_alert_num; ?></span>
                                                                                <span style="padding-right: 35px;"><?= lang('expiry_alerts') ?></span>
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <?php foreach($payment_customer_alert_num as $customer_payment) {} ?>
																			<a href="<?= site_url('sales/?d='. date('Y-m-d', strtotime($payment_customer_alert_num->date))) ?>" class="">
																				<span class="label label-danger pull-right" style="margin-top:3px;"><?= $payment_customer_alert_num->alert_num; ?></span>
																				<span style="padding-right: 35px;"><?= lang('customer_payment_alerts') ?></span>
																			</a>
                                                                        </li>
                                                                        <li>
                                                                            <?php foreach($payment_purchase_alert_num as $purchase_payment) {} ?>
																			<a href="<?= site_url('purchases/?d='. date('Y-m-d', strtotime($payment_purchase_alert_num->date))) ?>" class="">
																				<span class="label label-danger pull-right" style="margin-top:3px;"><?= $payment_purchase_alert_num->alert_num; ?></span>
																				<span style="padding-right: 35px;"><?= lang('supplier_payment_alerts') ?></span>
																			</a>
                                                                        </li><?php if($pos_settings->show_suspend_bar){ ?>
                                                                        <li>
																			<a href="<?= site_url('sales/suspend/?d='. date('Y-m-d', strtotime($sale_suspend_alert_num->date))) ?>" class="">
																				<span class="label label-danger pull-right" style="margin-top:3px;"><?= $sale_suspend_alert_num->alert_num; ?></span>
																				<span style="padding-right: 35px;"><?= lang('sale_suspend_alerts') ?></span>
																			</a>
                                                                        </li><?php } ?>
                                                                        <!-- Delivery Alert -->
                                                                        <li>
																			<a href="<?= site_url('sales/deliveries_alerts/'. date('Y-m-d', strtotime($delivery_alert_num->date))) ?>" class="">
																				<span class="label label-danger pull-right" style="margin-top:3px;"><?= $delivery_alert_num->alert_num; ?></span>
																				<span style="padding-right: 35px;"><?= lang('deliveries_alerts') ?></span>
																			</a>
                                                                        </li>
                                                                        
                                                                        <!-- Customer Alert -->
                                                                        <li>
                                                                            <a href="<?= site_url('sales/customers_alerts/') ?>" class="">
                                                                                <span class="label label-danger pull-right" style="margin-top:3px;"><?= $customers_alert_num; ?></span>
                                                                                <span style="padding-right: 35px;"><?= lang('customers_alerts') ?></span>
                                                                            </a>
                                                                        </li>

                                                                    </ul>
                                                                </li>
                                                                <?php } ?>
                                                                    <?php if (POS) { ?>
                                                                        <li class="dropdown hidden-xs">
                                                                            <a class="btn bdarkGreen tip" title="<?= lang('pos') ?>" data-placement="left" href="<?= site_url('pos') ?>">
                                                                                <i class="fa fa-th-large"></i><p><?= lang('pos') ?></p>
                                                                            </a>
                                                                        </li>
                                                                        <?php } ?>
                                                                            <?php if ($Owner) { ?>
                                                                                <li class="dropdown">
                                                                                    <a class="btn bdarkGreen tip" id="today_profit" title="<span><?= lang('today_profit') ?></span>" data-placement="bottom" data-html="true" href="<?= site_url('reports/profit') ?>" data-toggle="modal" data-target="#myModal">
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
                                                                                                <li class="dropdown hidden-xs">
                                                                                                    <a class="btn bred tip" title="<?= lang('clear_ls') ?>" data-placement="bottom" id="clearLS" href="#">
                                                                                                        <i class="fa fa-eraser"></i><p><?= lang('clear') ?></p> 
                                                                                                    </a>
                                                                                                </li>
                                                                                                <?php } ?>
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
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('list_convert'); ?></span>
                                            </a>
                                        </li>
                                        <li id="products_items_convert" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/items_convert'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_convert'); ?></span>
                                            </a>
                                        </li> 
                                        <li id="products_return_products" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/return_products'); ?>">
                                                <i class="fa fa-retweet"></i>
                                                <span class="text"> <?= lang('list_products_return'); ?></span>
                                            </a>
                                        </li>

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
                                        <li id="products_import_csv" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/import_csv'); ?>">
                                                <i class="fa fa-file-text"></i>
                                                <span class="text"> <?= lang('import_products'); ?></span>
                                            </a>
                                        </li>
                                        <li id="products_update_quantity" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/update_quantity'); ?>">
                                                <i class="fa fa-file-text"></i>
                                                <span class="text"> <?= lang('update_quantity'); ?></span>
                                            </a>
                                        </li>
                                        <li id="products_update_price" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/update_price'); ?>">
                                                <i class="fa fa-file-text"></i>
                                                <span class="text"> <?= lang('Import_Price/Cost'); ?></span>
                                            </a>
                                        </li>
								<!--		<li id="products_enter_using_stock" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/enter_using_stock'); ?>">
                                                <i class="fa fa-filter"></i>
                                                <span class="text"> <?= lang('add_stock_using'); ?></span>
                                            </a>
                                        </li>
										<li id="products_view_using_stock" class="sub_navigation">
                                            <a class="submenu" href="<?= site_url('products/view_using_stock'); ?>">
                                                <i class="fa fa-filter"></i>
                                                <span class="text"> <?= lang('list_stock_using'); ?></span>
                                            </a>
                                        </li>  -->
                                    </ul>
                                </li>
								
                                <li class="mm_sales <?= strtolower($this->router->fetch_method()) == 'settings' ? '' : 'mm_pos' ?>">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-heart"></i>
                                        <span class="text"> <?= lang('manage_sales'); ?> 
                                    </span> <span class="chevron closed"></span>
                                    </a>
                                    <ul><?php if (POS) { ?>
                                            <li id="pos_sales">
                                                <a class="submenu" href="<?= site_url('pos/sales'); ?>">
                                                    <i class="fa fa-heart"></i>
                                                    <span class="text"> <?= lang('pos_sales'); ?></span>
                                                </a>
                                            </li>
											<li id="sales_index">
												<a class="submenu" href="<?= site_url('sales'); ?>">
													<i class="fa fa-heart"></i>
													<span class="text"> <?= lang('list_sales'); ?></span>
												</a>
											</li>
                                            <?php } ?>
                                        <?php // if($pos_settings->show_suspend_bar){ ?>
										<!--	<li id="sales_suspends_calendar" <?=($this->uri->segment(2) === 'suspends_calendar' ? 'class="active"' : '')?> >
												<a class="submenu" href="<?= site_url('sales/suspends_calendar'); ?>">
													<i class="fa fa-building-o tip"></i>
													<span class="text"> <?= lang('suspend_calendar'); ?></span>
												</a>
											</li>
											<li id="sales_suspend">
												<a class="submenu" href="<?= site_url('sales/suspend'); ?>">
													<i class="fa fa-building"></i>
													<span class="text"> <?= lang('list_sales_suspend'); ?></span>
												</a>
											</li> -->

                                        
                                        <li id="sales_add">
                                            <a class="submenu" href="<?= site_url('sales/add'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_sale'); ?></span>
                                            </a>
                                        </li>
										<?php if (POS) { ?>
									<!--	<li id="sale_order_list_sale_order">
											<a class="submenu" href="<?= site_url('Sale_Order/list_sale_order'); ?>">
												<i class="fa fa-heart"></i>
												<span class="text"> <?= lang('list_sales_order'); ?></span>
											</a>
										</li>
                                        <?php } ?>
                                        <li id="sale_order_add_sale_order">
                                            <a class="submenu" href="<?= site_url('Sale_Order/add_sale_order'); ?>">
                                                <i class="fa fa-heart"></i>
                                                <span class="text"> <?= lang('add_sale_order'); ?></span>
                                            </a>
                                        </li> -->
										
										<li id="sales_add_deliveries">
                                            <a class="submenu" href="<?= site_url('sales/add_deliveries'); ?>">
                                                <i class="fa fa-truck"></i>
                                                <span class="text"> <?= lang('add_deliveries'); ?></span>
                                            </a>
                                        </li>
										<li id="sales_deliveries">
                                            <a class="submenu" href="<?= site_url('sales/deliveries'); ?>">
                                                <i class="fa fa-truck"></i>
                                                <span class="text"> <?= lang('list_deliveries'); ?></span>
                                            </a>
                                        </li>
                                       <li id="sales_customer_opening_balance">
                                            <a class="submenu" href="<?= site_url('sales/customer_opening_balance'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('opening_ar'); ?></span>
                                            </a>
                                        </li>   
<!--
<li id="sales_customer_opening_balance">
                                            <a class="submenu" href="<?= site_url('sales/customer_opening_balance'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_customer_opening_balance'); ?></span>
                                            </a>
                                        </li>
-->
										
                                        <!--<li id="sales_gift_cards">
                                            <a class="submenu" href="<?= site_url('sales/gift_cards'); ?>">
                                                <i class="fa fa-credit-card"></i>
                                                <span class="text"> <?= lang('list_gift_cards'); ?></span>
                                            </a>
                                        </li>
                                        <li id="sales_house_calendar">
                                            <a class="submenu" href="<?= site_url('sales/house_calendar'); ?>">
                                                <i class="fa fa-building-o tip"></i>
                                                <span class="text"> <?= lang('suspend_calendar'); ?></span>
                                            </a>
                                        </li>
                                        <li id="sales_house_sales">
                                            <a class="submenu" href="<?= site_url('sales/house_sales'); ?>">
                                                <i class="fa fa-building"></i>
                                                <span class="text"> <?= lang('list_sales_suspend'); ?></span>
                                            </a>
                                        </li> 
                                        <li id="sales_sales_loans">
                                            <a class="submenu" href="<?= site_url('sales/sales_loans'); ?>">
                                                <i class="fa fa-money"></i>
                                                <span class="text"> <?= lang('list_loans'); ?></span>
                                            </a>
                                        </li> -->
                                        <li id="sales_return_sales">
                                            <a class="submenu" href="<?= site_url('sales/return_sales'); ?>">
                                                <i class="fa fa-reply"></i>
                                                <span class="text"> <?= lang('list_sales_return'); ?></span>
                                            </a>
                                        </li>
                                        <li id="sales_add_return">
                                            <a class="submenu" href="<?= site_url('sales/add_return'); ?>">
                                                <i class="fa fa-plus-circle"></i>
                                                <span class="text"> <?= lang('add_sale_return'); ?></span>
                                            </a>
                                        </li> 
										
                                    </ul>
                                </li>

                        <!--        <li class="mm_quotes">
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
                                </li> -->

                                <li class="mm_purchases">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-star"></i>
                                        <span class="text"> <?= lang('manage_purchases'); ?> 
                                    </span> <span class="chevron closed"></span>
                                    </a>
                                    <ul>
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
										
                                        <li id="purchases_supplier_opening_balance">
                                            <a class="submenu" href="<?= site_url('purchases/supplier_opening_balance'); ?>">
                                                <i class="fa fa-file-text"></i>
                                                <span class="text"> <?= lang('opening_ap'); ?></span>
                                            </a>
                                        </li>
                                        <li id="purchases_return_purchases">
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
                                        </li>
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

                        <!--        <li class="mm_transfers">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-star-o"></i>
                                        <span class="text"> <?= lang('manage_transfers'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        <li id="transfers_index">
                                            <a class="submenu" href="<?= site_url('transfers'); ?>">
                                                <i class="fa fa-star-o"></i><span class="text"> <?= lang('list_transfers'); ?></span>
                                            </a>
                                        </li>
                                        <li id="transfers_add">
                                            <a class="submenu" href="<?= site_url('transfers/add'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_transfer'); ?></span>
                                            </a>
                                        </li>
                                        <li id="transfers_transfer_by_csv">
                                            <a class="submenu" href="<?= site_url('transfers/transfer_by_csv'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_transfer_by_csv'); ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>  -->
	
								<!--<li class="mm_jobs">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-star-o"></i>
                                        <span class="text"> <?= lang('manage_jobs'); ?> </span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        <li id="jobs_index">
                                            <a class="submenu" href="<?= site_url('jobs'); ?>">
                                                <i class="fa fa-star-o"></i><span class="text"> <?= lang('list_jobs'); ?></span>
                                            </a>
                                        </li>
                                        <li id="jobs_by_csv">
                                            <a class="submenu" href="<?= site_url('jobs/jobs_by_csv'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_jobs_by_csv'); ?></span>
                                            </a>
                                        </li>										
										
                                    	<li id="jobs_job_activities">
                                            <a class="submenu" href="<?= site_url('jobs/job_activities'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('job_activities'); ?></span>
                                            </a>
                                        </li>   
                                    	<li id="jobs_job_employees">
                                            <a class="submenu" href="<?= site_url('jobs/job_employees'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('job_employees'); ?></span>
                                            </a>
                                        </li>
                                        <li id="jobs_marchines">
                                            <a class="submenu" href="<?= site_url('jobs/marchines'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('list_marchine'); ?></span>
                                            </a>
                                        </li>
                                        <li id="jobs_marchine_activities">
                                            <a class="submenu" href="<?= site_url('jobs/marchine_activities'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('marchine_activities'); ?></span>
                                            </a>
                                        </li>										
                                    </ul>
                                </li>-->
								
                        <!--         <li class="mm_account">
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
										<!--<li id="account_ap_by_supplier">
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
									<!--	<li id="account_budget">
                                            <a class="submenu" href="<?= site_url('#'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('list_budget'); ?></span>
                                            </a>
                                        </li>
										<li id="account_budget_add">
                                            <a class="submenu" href="<?= site_url('#'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_budget'); ?></span>
                                            </a>
                                        </li> 
										<li id="account_deposits">
                                            <a class="submenu" href="<?= site_url('account/deposits'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('list_customer_deposit'); ?></span>
                                            </a>
                                        </li>
										<li id="account_deposits">
                                            <a class="submenu" href="<?= site_url('quotes/add_deposit'); ?>" data-toggle="modal" data-target="#myModal" id="add">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('add_customer_deposit'); ?></span>
                                            </a>
                                        </li>
										<li id="account_deposits">
                                            <a class="submenu" href="<?= site_url('suppliers/deposits'); ?>">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('list_supplier_deposit'); ?></span>
                                            </a>
                                        </li>
										<li id="account_deposits">
                                            <a class="submenu" href="<?= site_url('suppliers/add_deposit'); ?>" data-toggle="modal" data-target="#myModal" id="add">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_supplier_deposit'); ?></span>
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
                                </li> -->
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
                                                    <i class="fa fa-user-plus"></i><span class="text"> <?= lang('new_user'); ?></span>
                                                </a>
                                            </li>
                                    <!--        <li id="billers_index">
                                                <a class="submenu" href="<?= site_url('billers'); ?>">
                                                    <i class="fa fa-users"></i><span class="text"> <?= lang('list_billers'); ?></span>
                                                </a>
                                            </li>
                                            <li id="billers_index">
                                                <a class="submenu" href="<?= site_url('billers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                    <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_biller'); ?></span>
                                                </a>
                                            </li> -->
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
											<!--	<li id="drivers_index">
													<a class="submenu" href="<?= site_url('drivers'); ?>">
														<i class="fa fa-users"></i><span class="text"> <?= lang('list_drivers'); ?></span>
													</a>
                                            	</li>
												<li id="drivers_index">
                                                    <a class="submenu" href="<?= site_url('drivers/add'); ?>" data-toggle="modal" data-target="#myModal">
                                                        <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_driver'); ?></span>
                                                    </a>
                                                </li> -->
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
                                         <!--       <li id="employees_index">
                                                <a class="submenu" href="<?= site_url('employees'); ?>">
                                                    <i class="fa fa-users"></i><span class="text"> <?= lang('list_employees'); ?></span>
                                                </a>
                                            	</li>
                                            	<li id="employees_add">
                                                <a class="submenu" href="<?= site_url('employees/add'); ?>">
                                                    <i class="fa fa-user-plus"></i><span class="text"> <?= lang('add_employee'); ?></span>
                                                </a>
                                            	</li>  -->
                                    </ul>
                                </li>

                                <li class="mm_notifications">
                                    <a class="submenu" href="<?= site_url('notifications'); ?>">
                                        <i class="fa fa-comments"></i><span class="text"> <?= lang('notifications'); ?></span>
                                    </a>
                                </li> 
								
                                <!--<li class="mm_documents">
                                    <a class="submenu" href="<?= site_url('documents'); ?>">
                                        <i class="fa fa-book"></i><span class="text"> <?= lang('documents'); ?></span>
                                    </a>
                                </li>-->
                                <?php if ($Owner) { ?>
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
                                                    <li id="account_settings">
                                                        <a href="<?= site_url('account/settings') ?>">
                                                            <i class="fa fa-cog"></i><span class="text"> <?= lang('account_settings'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_change_logo">
                                                        <a href="<?= site_url('system_settings/change_logo') ?>" data-toggle="modal" data-target="#myModal">
                                                            <i class="fa fa-upload"></i><span class="text"> <?= lang('change_logo'); ?></span>
                                                        </a>
                                                    </li>
													<li id="group_area">
														<a class="submenu" href="<?= site_url('system_settings/group_area'); ?>">
															<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('group_area'); ?></span>
														</a>
													</li>
                                                    <li id="system_settings_currencies">
                                                        <a href="<?= site_url('system_settings/currencies') ?>">
                                                            <i class="fa fa-money"></i><span class="text"> <?= lang('currencies'); ?></span>
                                                        </a>
                                                    </li>
                                                <!--    <li id="system_settings_customer_groups">
                                                        <a href="<?= site_url('system_settings/customer_groups') ?>">
                                                            <i class="fa fa-chain"></i><span class="text"> <?= lang('customer_groups'); ?></span>
                                                        </a>
                                                    </li> -->
													<li id="system_settings_price_groups">
														<a href="<?= site_url('system_settings/price_groups') ?>">
															<i class="fa fa-dollar"></i><span class="text"> <?= lang('price_groups'); ?></span>
														</a>
													</li>
                                                    <li id="system_settings_categories">
                                                        <a href="<?= site_url('system_settings/categories') ?>">
                                                            <i class="fa fa-sitemap"></i><span class="text"> <?= lang('categories'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_units">
														<a href="<?= site_url('system_settings/units') ?>">
															 <i class="fa fa-wrench"></i><span class="text"> <?= lang('units'); ?></span>
														</a>
													</li>
                                                    <li id="system_settings_variants">
                                                        <a href="<?= site_url('system_settings/variants') ?>">
                                                            <i class="fa fa-tags"></i><span class="text"> <?= lang('variants'); ?></span>
                                                        </a>
                                                    </li>
                                                <!--    <li id="system_settings_bom">
                                                        <a href="<?= site_url('system_settings/bom') ?>">
                                                            <i class="fa fa-tags"></i><span class="text"> <?= lang('bom'); ?></span>
                                                        </a>
                                                    </li> -->
                                                    <li id="system_settings_suspend">
                                                        <a href="<?= site_url('system_settings/suspend') ?>">
                                                            <i class="fa fa-tags"></i><span class="text"> <?= lang('suspend'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_suspend_layout">
                                                        <a href="<?= site_url('system_settings/suspend_layout') ?>">
                                                            <i class="fa fa-tags"></i><span class="text"> <?= lang('suspend_layout'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_tax_rates">
                                                        <a href="<?= site_url('system_settings/tax_rates') ?>">
                                                            <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('tax_rates'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_warehouses">
                                                        <a href="<?= site_url('system_settings/warehouses') ?>">
                                                            <i class="fa fa-building-o"></i><span class="text"> <?= lang('warehouses'); ?></span>
                                                        </a>
                                                    </li>
                                                <!--    <li id="system_settings_email_templates">
                                                        <a href="<?= site_url('system_settings/email_templates') ?>">
                                                            <i class="fa fa-envelope"></i><span class="text"> <?= lang('email_templates'); ?></span>
                                                        </a>
                                                    </li> -->
                                                    <li id="system_settings_user_groups">
                                                        <a href="<?= site_url('system_settings/user_groups') ?>">
                                                            <i class="fa fa-key"></i><span class="text"> <?= lang('group_permissions'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li id="system_settings_backups">
                                                        <a href="<?= site_url('system_settings/backups') ?>">
                                                            <i class="fa fa-database"></i><span class="text"> <?= lang('backups'); ?></span>
                                                        </a>
                                                    </li>
                                                <!--    <li id="system_settings_updates">
                                                        <a href="<?= site_url('system_settings/updates') ?>">
                                                            <i class="fa fa-upload"></i><span class="text"> <?= lang('updates'); ?></span>
                                                        </a>
                                                    </li> -->
                                        </ul>
                                    </li>
                                    <?php } ?>

                                        <li class="mm_reports">
                                            <a class="dropmenu" href="#">
                                                <i class="fa fa-bar-chart-o"></i>
                                                <span class="text"> <?= lang('reports'); ?> </span>
                                                <span class="chevron closed"></span>
                                            </a>
                                            <ul>
                                                <li id="reports_index">
                                                    <a href="<?= site_url('reports') ?>">
                                                        <i class="fa fa-bars"></i><span class="text"> <?= lang('overview_chart'); ?></span>
                                                    </a>
                                                </li>
                                                <li id="reports_warehouse_stock">
                                                    <a href="<?= site_url('reports/warehouse_stock') ?>">
                                                        <i class="fa fa-building"></i><span class="text"> <?= lang('warehouse_stock'); ?></span>
                                                    </a>
                                                </li>
												<li id="reports_category_stock">
                                                    <a href="<?= site_url('reports/category_stock') ?>">
                                                        <i class="fa fa-building"></i><span class="text"> <?= lang('category_stock_chart'); ?></span>
                                                    </a>
                                                </li>
												<li id="reports_profit_chart">
                                                    <a href="<?= site_url('reports/profit_chart') ?>">
                                                        <i class="fa fa-building"></i><span class="text"> <?= lang('profit_chart'); ?></span>
                                                    </a>
                                                </li>
                                            <!--	<li id="reports_cash_chart">
                                                    <a href="<?= site_url('reports/cash_chart') ?>">
                                                        <i class="fa fa-building"></i><span class="text"> <?= lang('cash_analysis_chart'); ?></span>
                                                    </a>
                                                </li> -->
                                                <?php if (POS) { ?>
                                                    <li id="reports_register">
                                                        <a href="<?= site_url('reports/register') ?>">
                                                            <i class="fa fa-th-large"></i><span class="text"> <?= lang('register_report'); ?></span>
                                                        </a>
                                                    </li>
                                                    <?php } ?>
                                                        <li id="reports_quantity_alerts">
                                                            <a href="<?= site_url('reports/quantity_alerts') ?>">
                                                                <i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('product_quantity_alerts'); ?></span>
                                                            </a>
                                                        </li>
                                                        <?php if ($this->Settings->product_expiry) { ?>
                                                            <li id="reports_expiry_alerts">
                                                                <a href="<?= site_url('reports/expiry_alerts') ?>">
                                                                    <i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('product_expiry_alerts'); ?></span>
                                                                </a>
                                                            </li>
                                                            <?php } ?>
                                                                <li id="reports_products">
                                                                    <a href="<?= site_url('reports/products') ?>">
                                                                        <i class="fa fa-barcode"></i><span class="text"> <?= lang('products_report'); ?></span>
                                                                    </a>
                                                                </li>
																<li id="reports_warehouse_reports">
                                                                    <a href="<?= site_url('reports/warehouse_reports') ?>">
                                                                        <i class="fa fa-barcode"></i><span class="text"> <?= lang('warehouse_reports'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li id="reports_product_in_out">
                                                                    <a href="<?= site_url('reports/product_in_out') ?>">
                                                                        <i class="fa fa-money"></i><span class="text"> <?= lang('products_in_out'); ?></span>
                                                                    </a>
                                                                </li>
																<li id="reports_product_monthly_in_out">
                                                                    <a href="<?= site_url('reports/getSalesReportDetail') ?>">
                                                                        <i class="fa fa-money"></i><span class="text"> <?= lang('sales_report_detail'); ?></span>
                                                                    </a>
                                                                </li>
																<li id="reports_product_monthly_in_out">
                                                                    <a href="<?= site_url('reports/getSaleReportByInvoice') ?>">
                                                                        <i class="fa fa-money"></i><span class="text"> <?= lang('sales_report_by_invoice'); ?></span>
                                                                    </a>
                                                                </li>
																<li id="reports_product_monthly_in_out">
                                                                    <a href="<?= site_url('reports/product_monthly_in_out') ?>">
                                                                        <i class="fa fa-barcode"></i><span class="text"> <?= lang('monthly_product'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li id="reports_product_daily_in_out">
                                                                    <a href="<?= site_url('reports/product_daily_in_out') ?>">
                                                                        <i class="fa fa-barcode"></i><span class="text"> <?= lang('daily_product'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li id="reports_supplier_by_items">
                                                                    <a href="<?= site_url('reports/supplier_by_items') ?>">
                                                                        <i class="fa fa-barcode"></i><span class="text"> <?= lang('supplier_products'); ?></span>
                                                                    </a>
                                                                </li>
                                                               <!-- <li id="reports_product_customers">
                                                                    <a href="<?= site_url('reports/customer_by_items') ?>">
                                                                        <i class="fa fa-barcode"></i><span class="text"> <?= lang('product_customers'); ?></span>
                                                                    </a>
                                                                </li>
																
																
                                                                <li id="reports_categories">
                                                                    <a href="<?= site_url('reports/categories') ?>">
                                                                        <i class="fa fa-folder-open"></i><span class="text"> <?= lang('categories_report'); ?></span>
                                                                    </a>
                                                                </li>
																<li id="reports_categories_value">
                                                                    <a href="<?= site_url('reports/categories_value') ?>">
                                                                        <i class="fa fa-folder-open"></i><span class="text"> <?= lang('categories_value_report'); ?></span>
                                                                    </a>
                                                                </li> -->
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
                                                                <li id="reports_sales">
                                                                    <a href="<?= site_url('reports/sales') ?>">
                                                                        <i class="fa fa-heart"></i><span class="text"> <?= lang('sales_report'); ?></span>
                                                                    </a>
                                                                </li>
																<li id="reports_sales_profit">
                                                                    <a href="<?= site_url('reports/sales_profit') ?>">
                                                                        <i class="fa fa-heart"></i><span class="text"> <?= lang('sales_profit_report'); ?></span>
                                                                    </a>
                                                                </li>
																<li id="reports_sales_discount">
																	<a href="<?= site_url('reports/sales_discount') ?>">
																		<i class="fa fa-gift"></i><span class="text"> <?= lang('sales_discount_report'); ?></span>
																	</a>
																</li>
                                                                <li id="reports_payments">
                                                                    <a href="<?= site_url('reports/payments') ?>">
                                                                        <i class="fa fa-money"></i><span class="text"> <?= lang('payments_report'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li id="reports_profit_loss">
                                                                    <a href="<?= site_url('reports/profit_loss') ?>">
                                                                        <i class="fa fa-money"></i><span class="text"> <?= lang('profit_and_loss'); ?></span>
                                                                    </a>
                                                                </li>
															<!--	<li id="reports_deliveries">
                                                                    <a href="<?= site_url('reports/deliveries') ?>">
                                                                        <i class="fa fa-heart"></i><span class="text"> <?= lang('sale_by_delivery_person'); ?></span>
                                                                    </a>
                                                                </li> -->
                                                                <li id="reports_purchases">
                                                                    <a href="<?= site_url('reports/purchases') ?>">
                                                                        <i class="fa fa-star"></i><span class="text"> <?= lang('purchases_report'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li id="reports_daily_purchases">
                                                                    <a href="<?= site_url('reports/daily_purchases') ?>">
                                                                        <i class="fa fa-calendar-o"></i><span class="text"> <?= lang('daily_purchases'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li id="reports_monthly_purchases">
                                                                    <a href="<?= site_url('reports/monthly_purchases') ?>">
                                                                        <i class="fa fa-calendar-o"></i><span class="text"> <?= lang('monthly_purchases'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li id="reports_customers">
                                                                    <a href="<?= site_url('reports/customers') ?>">
                                                                        <i class="fa fa-users"></i><span class="text"> <?= lang('customers_report'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li id="reports_suppliers">
                                                                    <a href="<?= site_url('reports/suppliers') ?>">
                                                                        <i class="fa fa-users"></i><span class="text"> <?= lang('suppliers_report'); ?></span>
                                                                    </a>
                                                                </li>                                                                
                                                                <!--<li id="reports_suspend_report" <?=($this->uri->segment(2) === 'suspends' ? 'class="active"' : '')?> >
                                                                    <a href="<?= site_url('reports/suspends') ?>">
                                                                        <i class="fa fa-users"></i><span class="text"> <?= lang('suspend_report'); ?></span>
                                                                    </a>
                                                                </li>-->
                                                                <li id="reports_users">
                                                                    <a href="<?= site_url('reports/users') ?>">
                                                                        <i class="fa fa-users"></i><span class="text"> <?= lang('staff_report'); ?></span>
                                                                    </a>
                                                                </li>
                                                                <li id="reports_saleman">
                                                                    <a href="<?= site_url('reports/saleman') ?>">
                                                                        <i class="fa fa-users"></i><span class="text"> <?= lang('saleman_report'); ?></span>
                                                                    </a>
                                                                </li>
                                                        <!--        <li id="reports_shops">
                                                                    <a href="<?= site_url('reports/shops') ?>">
                                                                        <i class="fa fa-users"></i><span class="text"> <?= lang('biller_report'); ?></span>
                                                                    </a>
                                                                </li>
                                                               
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
                                                                <li id="reports_income_statement">
                                                                    <a href="<?= site_url('reports/income_statement') ?>">
                                                                        <i class="fa fa-money"></i><span class="text"> <?= lang('income_statement'); ?></span>
                                                                    </a>
                                                                </li> -->
                                                                <li id="reports_cash_book_report" <?=($this->uri->segment(2) === 'cash_books' ? 'class="active"' : '')?> >
                                                                    <a href="<?= site_url('reports/cash_books') ?>">
                                                                        <i class="fa fa-money"></i><span class="text"> <?= lang('cash_book'); ?></span>
                                                                    </a>
                                                                </li>
                                            </ul>
                                        </li>
                                        							
									<!--	 <li class="mm_taxes_reports">
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
                                        </li> -->

                                        <?php
                        } else {
                            ?>
                                            <?php if ($GP['products-index']) { ?>
                                                <li class="mm_products">
                                                    <a class="dropmenu" href="#">
                                                        <i class="fa fa-barcode"></i>
                                                        <span class="text"> <?= lang('manage_products'); ?> 
														</span> <span class="chevron closed"></span>
                                                    </a>
                                                    <ul>
                                                        <li id="products_index">
                                                            <a class="submenu" href="<?= site_url('products'); ?>">
                                                                <i class="fa fa-barcode"></i><span class="text"> <?= lang('list_products'); ?></span>
                                                            </a>
                                                        </li>
															<?php if ($GP['products-add']) { ?>
																<li id="products_add">
																	<a class="submenu" href="<?= site_url('products/add'); ?>">
																		<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_product'); ?></span>
																	</a>
																</li>
                                                            <?php } ?>
															
															<?php if ($GP['product_items_convert']) { ?>
                                                                <li id="products_list_convert" class="sub_navigation">
                                                                    <a class="submenu" href="<?= site_url('products/list_convert'); ?>">
                                                                        <i class="fa fa-plus-circle"></i>
                                                                        <span class="text"> <?= lang('list_convert'); ?></span>
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
															
															<?php if ($GP['product_return_list']) { ?>
																<li id="products_return_products" class="sub_navigation">
																	<a class="submenu" href="<?= site_url('products/return_products'); ?>">
																		<i class="fa fa-retweet"></i>
																		<span class="text"> <?= lang('list_products_return'); ?></span>
																	</a>
																</li>
															<?php } ?>
															<?php if ($GP['product_print_barcodes']) { ?>
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
                                                                            <i class="fa fa-filter"></i><span class="text"> <?= lang('quantity_adjustments'); ?></span>
                                                                        </a>
                                                                    </li>
                                                            <?php } ?>
															<?php if ($GP['product_using_stock']) { ?>
																<li id="products_enter_using_stock" class="sub_navigation">
																	<a class="submenu" href="<?= site_url('products/enter_using_stock'); ?>">
																		<i class="fa fa-filter"></i>
																		<span class="text"> <?= lang('add_stock_using'); ?></span>
																	</a>
																</li>
															<?php } ?>
															<?php if ($GP['product_list_using_stock']) { ?>
																<li id="products_view_using_stock" class="sub_navigation">
																	<a class="submenu" href="<?= site_url('products/view_using_stock'); ?>">
																		<i class="fa fa-filter"></i>
																		<span class="text"> <?= lang('list_stock_using'); ?></span>
																	</a>
																</li>
															<?php } ?>
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
																		<span class="text"> <?= lang('Import_Price/Cost'); ?></span>
																	</a>
																</li>
															<?php } ?>
                                                    </ul>
                                                </li>
                                                <?php } ?>

												<?php if ($GP['sales-index']) { ?>
													<li class="mm_sales <?= strtolower($this->router->fetch_method()) == 'settings' ? '' : 'mm_pos' ?>">
														<a class="dropmenu" href="#">
															<i class="fa fa-heart"></i>
															<span class="text"> <?= lang('manage_sales'); ?> 
															</span> <span class="chevron closed"></span>
														</a>
														<ul>
															<li id="sales_index">
																<a class="submenu" href="<?= site_url('sales'); ?>">
																	<i class="fa fa-heart"></i><span class="text"> <?= lang('list_sales'); ?></span>
																</a>
															</li>
															
															<?php if ($GP['pos-index']) { ?>
																<li id="pos_sales">
																	<a class="submenu" href="<?= site_url('pos/sales'); ?>">
																		<i class="fa fa-heart"></i><span class="text"> <?= lang('pos_sales'); ?></span>
																	</a>
																</li>
															<?php } ?>
															
															<?php if ($GP['sales-add']) { ?>
																<li id="sales_add">
																	<a class="submenu" href="<?= site_url('sales/add'); ?>">
																		<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_sale'); ?></span>
																	</a>
																</li>
															<?php }
															
															if ($GP['sale_order-index']) { ?>
																<li id="sale_order_list_sale_order">
																	<a class="submenu" href="<?= site_url('Sale_Order/list_sale_order'); ?>">
																		<i class="fa fa-heart"></i>
																		<span class="text"> <?= lang('list_sales_order'); ?></span>
																	</a>
																</li>
															<?php } ?>
															
															<?php if ($GP['sale_order-add']) { ?>
																<li id="sale_order_add_sale_order">
																	<a class="submenu" href="<?= site_url('Sale_Order/add_sale_order'); ?>">
																		<i class="fa fa-heart"></i>
																		<span class="text"> <?= lang('add_sale_order'); ?></span>
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
																		<i class="fa fa-truck"></i>
																		<span class="text"> <?= lang('add_deliveries'); ?></span>
																	</a>
																</li>
															<?php }
															
															if ($GP['sales-gift_cards']) { ?>
																<li id="sales_gift_cards">
																	<a class="submenu" href="<?= site_url('sales/gift_cards'); ?>">
																		<i class="fa fa-credit-card"></i><span class="text"> <?= lang('gift_cards'); ?></span>
																	</a>
																</li>
															<?php }
															
															if ($GP['sales-opening_ar']) { ?>
																<li id="sales_customer_opening_balance">
																		<a class="submenu" href="<?= site_url('sales/customer_opening_balance'); ?>">
																			<i class="fa fa-plus-circle"></i>
																			<span class="text"> <?= lang('opening_ar'); ?></span>
																		</a>
																</li>
															<?php }
															
															if ($GP['sales-loan']) { ?>
																<li id="sales_sales_loans">
																	<a class="submenu" href="<?= site_url('sales/sales_loans'); ?>">
																		<i class="fa fa-money"></i>
																		<span class="text"> <?= lang('list_loans'); ?></span>
																	</a>
																</li>
															<?php }
															if ($GP['sales-return_sales']) { ?>
																<li id="sales_return_sales">
																	<a class="submenu" href="<?= site_url('sales/return_sales'); ?>">
																		<i class="fa fa-reply"></i><span class="text"> <?= lang('list_sales_return'); ?></span>
																	</a>
																</li>
															<?php }
															if ($GP['sales-return_sales']) { ?>
																<li id="sales_add_return">
																	<a class="submenu" href="<?= site_url('sales/add_return'); ?>">
																		<i class="fa fa-plus-circle"></i>
																		<span class="text"> <?= lang('add_sale_return'); ?></span>
																	</a>
																</li>
															<?php } ?>
															
															<?php if($GP['room-index']){ ?>
																<li id="sales_house_calendar">
																	<a class="submenu" href="<?= site_url('sales/house_calendar'); ?>">
																		<i class="fa fa-building-o tip"></i>
																		<span class="text"> <?= lang('suspend_calendar'); ?></span>
																	</a>
																</li>
															<?php } ?>
																
															<?php if($GP['sale-room-index']){ ?>
																<li id="sales_house_sales">
																	<a class="submenu" href="<?= site_url('sales/house_sales'); ?>">
																		<i class="fa fa-building"></i>
																		<span class="text"> <?= lang('list_sales_suspend'); ?></span>
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
														<ul>
															<li id="sales_index">
																<a class="submenu" href="<?= site_url('quotes'); ?>">
																	<i class="fa fa-heart-o"></i><span class="text"> <?= lang('list_quotes'); ?></span>
																</a>
															</li>
															<?php if ($GP['quotes-add']) { ?>
																<li id="sales_add">
																	<a class="submenu" href="<?= site_url('quotes/add'); ?>">
																		<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_quote'); ?></span>
																	</a>
																</li>
																<?php } ?>
														</ul>
													</li>
												<?php } ?>

												<?php if ($GP['purchases-index']) { ?>
													<li class="mm_purchases">
														<a class="dropmenu" href="#">
															<i class="fa fa-star"></i>
															<span class="text"> <?= lang('manage_purchases'); ?> 
															</span> <span class="chevron closed"></span>
														</a>
														<ul>
															<li id="purchases_index">
																<a class="submenu" href="<?= site_url('purchases'); ?>">
																	<i class="fa fa-star"></i><span class="text"> <?= lang('list_purchases'); ?></span>
																</a>
															</li>
															<?php if ($GP['purchases-add']) { ?>
																<li id="purchases_add">
																	<a class="submenu" href="<?= site_url('purchases/add'); ?>">
																		<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_purchase'); ?></span>
																	</a>
																</li>
															<?php } ?>
															<?php if ($GP['purchases_order-index']) { ?>
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
															
															<?php if ($GP['purchases-expenses']) { ?>
																<li id="purchases_expenses">
																	<a class="submenu" href="<?= site_url('purchases/expenses'); ?>">
																		<i class="fa fa-dollar"></i><span class="text"> <?= lang('expenses'); ?></span>
																	</a>
																</li>
																<li id="purchases_add_expense">
																	<a class="submenu" href="<?= site_url('purchases/add_expense'); ?>" data-toggle="modal" data-target="#myModal">
																		<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_expense'); ?></span>
																	</a>
																</li>
															<?php } ?>
															<?php if ($GP['purchases-return_list']) { ?>
															<li id="purchases_return_purchases">
																<a class="submenu" href="<?= site_url('purchases/return_purchases'); ?>">
																	<i class="fa fa-reply"></i>
																	<span class="text"> <?= lang('list_purchases_return'); ?></span>
																</a>
															</li>
															<?php } ?>
															
															<?php if ($GP['purchases-return_add']) { ?>
															<li id="purchases_add_purchase_return">
																<a class="submenu" href="<?= site_url('purchases/add_purchase_return'); ?>">
																	<i class="fa fa-plus-circle"></i>
																	<span class="text"> <?= lang('add_purchase_return'); ?></span>
																</a>
															</li>
															<?php } ?>
															<?php if ($GP['purchases-import_expanse']) { ?>
																<li id="purchases_expense_by_csv">
																	<a class="submenu" href="<?= site_url('purchases/expense_by_csv'); ?>">
																		<i class="fa fa-file-text"></i>
																		<span class="text"> <?= lang('import_expense'); ?></span>
																	</a>
																</li>
															<?php } ?>
															
														</ul>
													</li>
												<?php } ?>

												<?php if ($GP['transfers-index']) { ?>
													<li class="mm_transfers">
														<a class="dropmenu" href="#">
															<i class="fa fa-star-o"></i>
															<span class="text"> <?= lang('manage_transfers'); ?> </span>
															<span class="chevron closed"></span>
														</a>
														<ul>
															<li id="transfers_index">
																<a class="submenu" href="<?= site_url('transfers'); ?>">
																	<i class="fa fa-star-o"></i><span class="text"> <?= lang('list_transfers'); ?></span>
																</a>
															</li>
															<?php if ($GP['transfers-add']) { ?>
																<li id="transfers_add">
																	<a class="submenu" href="<?= site_url('transfers/add'); ?>">
																		<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_transfer'); ?></span>
																	</a>
																</li>
															<?php } ?>
															<?php if ($GP['transfers-export']) { ?>
																<li id="transfers_transfer_by_csv">
																	<a class="submenu" href="<?= site_url('transfers/transfer_by_csv'); ?>">
																		<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_transfer_by_csv'); ?></span>
																	</a>
																</li>
															<?php } ?>
														</ul>
													</li>
												<?php } ?>
																				
								<?php if ($GP['accounts-index']){ ?>												
								<li class="mm_account">
                                    <a class="dropmenu" href="#">
                                        <i class="fa fa-book"></i>
                                        <span class="text"> <?= lang('manage_accounts') ?></span>
                                        <span class="chevron closed"></span>
                                    </a>
                                    <ul>
                                        <!-- Add New -->
										<?php if ($GP['accounts-index']) { ?>
											<li id="account_listJournal">
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
										
										<?php if ($GP['list_account_receivable']) { ?>
											<li id="account_list_ac_recevable">
												<a class="submenu" href="<?= site_url('account/list_ac_recevable'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('list_ac_receivable'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['list_account_receivable']) { ?>
											<li id="list_ar_aging">
												<a class="submenu" href="<?= site_url('account/list_ar_aging'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('list_ar_aging'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['ar_by_customer']) { ?>
                                        <li id="account_ar_by_customer">
                                            <a class="submenu" href="<?= site_url('account/ar_by_customer'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('ar_by_customer'); ?></span>
                                            </a>
                                        </li>
										<?php } ?>
										<?php if ($GP['bill_receipt']) { ?>
											<li id="account_billreceipt">
												<a href="<?= site_url('account/billReceipt') ?>">
													<i class="fa fa-money"></i><span class="text"> <?= lang('bill_receipt'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['list_acount_payable']) { ?>
											<li id="account_list_ac_payable">
												<a class="submenu" href="<?= site_url('account/list_ac_payable'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('account_payable_list'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['list_ap_aging']) { ?>
											<li id="account_list_ap_aging">
												<a class="submenu" href="<?= site_url('account/list_ap_aging'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('list_ap_aging'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['ap_by_supplier']) { ?>
											<li id="account_ap_by_supplier">
												<a class="submenu" href="<?= site_url('account/ap_by_supplier'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('ap_by_supplier'); ?></span>
												</a>
											</li>
										<?php } ?>
										<!--<li id="account_ap_by_supplier">
                                            <a class="submenu" href="<?= site_url('account/ap_by_supplier'); ?>">
                                                <i class="fa fa-list"></i><span class="text"> <?= lang('ap_by_supplier'); ?></span>
                                            </a>
                                        </li>-->
                                        <?php if ($GP['bill_payable']) { ?>
											<li id="account_billpayable">
												<a href="<?= site_url('account/billPayable') ?>">
													<i class="fa fa-money"></i><span class="text"> <?= lang('bill_payable'); ?></span>
												</a>
											</li>
										<?php } ?>
										
										<?php if ($GP['list_ac_head']) { ?>
											<li id="account_index">
												<a class="submenu" href="<?= site_url('account'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('list_ac_head'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['add_ac_head']) { ?>
											<li id="account_add">
												<a class="submenu" href="<?= site_url('account/add'); ?>" data-toggle="modal" data-target="#myModal">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_ac_head'); ?></span>
												</a>
											</li>
										<?php } ?>
										
										<!--
										<li id="account_budget">
                                            <a class="submenu" href="<?= site_url('#'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('list_budget'); ?></span>
                                            </a>
                                        </li>
										<li id="account_budget_add">
                                            <a class="submenu" href="<?= site_url('#'); ?>" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_budget'); ?></span>
                                            </a>
                                        </li> 
										-->
										<?php if ($GP['list_customer_deposit']) { ?>
											<li id="account_deposits">
												<a class="submenu" href="<?= site_url('account/deposits'); ?>">
													<i class="fa fa-list"></i><span class="text"> <?= lang('list_customer_deposit'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['add_customer_deposit']) { ?>
											<li id="account_deposits">
												<a class="submenu" href="<?= site_url('quotes/add_deposit'); ?>" data-toggle="modal" data-target="#myModal" id="add">
													<i class="fa fa-list"></i><span class="text"> <?= lang('add_customer_deposit'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['list_supplier_deposit']) { ?>
											<li id="account_deposits">
												<a class="submenu" href="<?= site_url('suppliers/deposits'); ?>">
													<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('list_supplier_deposit'); ?></span>
												</a>
											</li>
										<?php } ?>
										<?php if ($GP['add_supplier_deposit']) { ?>
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
																				

								<?php if ($GP['customers-index'] || $GP['suppliers-index'] || $GP['users-index'] || $GP['drivers-index'] || $GP['employees-index'] || $GP['projects-index'] ) { ?>
									<li class="mm_auth mm_customers mm_suppliers mm_billers">
										<a class="dropmenu" href="#">
											<i class="fa fa-users"></i>
											<span class="text"> <?= lang('manage_people'); ?> </span>
											<span class="chevron closed"></span>
										</a>
										<ul>
											<?php if ($GP['users-index']){ ?>	
												<li id="auth_users">
													<a class="submenu" href="<?= site_url('users'); ?>">
														<i class="fa fa-users"></i><span class="text"> <?= lang('list_users'); ?></span>
													</a>
												</li>
											<?php } ?>
											<?php if ($GP['users-add']){ ?>	
												<li id="auth_create_user">
													<a class="submenu" href="<?= site_url('users/create_user'); ?>">
														<i class="fa fa-user-plus"></i><span class="text"> <?= lang('new_user'); ?></span>
													</a>
												</li>
											<?php } ?>
										
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
											<?php }
												if ($GP['suppliers-index']) { ?>
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
											<?php if ($GP['employees-index']){ ?>
												<li id="employees_index">
                                                <a class="submenu" href="<?= site_url('employees'); ?>">
                                                    <i class="fa fa-users"></i><span class="text"> <?= lang('list_employees'); ?></span>
                                                </a>
                                            	</li>
											<?php } ?>
											<?php if ($GP['employees-add']){ ?>
                                            	<li id="employees_add">
                                                <a class="submenu" href="<?= site_url('employees/add'); ?>">
                                                    <i class="fa fa-user-plus"></i><span class="text"> <?= lang('add_employee'); ?></span>
                                                </a>
                                            	</li>
											<?php } ?>
											<?php if ($GP['projects-index']){ ?>
												<li id="billers_index">
                                                <a class="submenu" href="<?= site_url('billers'); ?>">
                                                    <i class="fa fa-users"></i><span class="text"> <?= lang('list_billers'); ?></span>
                                                </a>
												</li>
											<?php } ?>
											<?php if ($GP['projects-add']){ ?>
												<li id="billers_index">
													<a class="submenu" href="<?= site_url('billers/add'); ?>" data-toggle="modal" data-target="#myModal">
														<i class="fa fa-plus-circle"></i><span class="text"> <?= lang('add_biller'); ?></span>
													</a>
												</li>
											<?php } ?>
										</ul>
									</li>
								<?php } ?>
								
								

								<?php if ($GP['reports-quantity_alerts'] || $GP['reports-expiry_alerts'] || $GP['reports-products'] || $GP['reports-monthly_sales'] || $GP['reports-sales'] || $GP['reports-payments'] || $GP['reports-purchases'] || $GP['reports-customers'] || $GP['reports-suppliers'] || $GP['reports-profit_loss']) { ?>
								<li class="mm_reports">
								<a class="dropmenu" href="#">
								<i class="fa fa-bar-chart-o"></i>
								<span class="text"> <?= lang('reports'); ?> </span>
								<span class="chevron closed"></span>
								</a>
								<ul>
								<?php if ($GP['reports-quantity_alerts']) { ?>
								<li id="reports_quantity_alerts">
								<a href="<?= site_url('reports/quantity_alerts') ?>">
								<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('product_quantity_alerts'); ?></span>
								</a>
								</li>
								<?php }
								if ($GP['reports-expiry_alerts']) { ?>
								<?php if ($this->Settings->product_expiry) { ?>
								<li id="reports_expiry_alerts">
								<a href="<?= site_url('reports/expiry_alerts') ?>">
								<i class="fa fa-bar-chart-o"></i><span class="text"> <?= lang('product_expiry_alerts'); ?></span>
								</a>
								</li>
								<?php } ?>
								<?php }
								if ($GP['reports-products']) { ?>
								<li id="reports_products">
									<a href="<?= site_url('reports/products') ?>">
										<i class="fa fa-barcode"></i><span class="text"> <?= lang('products_report'); ?></span>
									</a>
								</li>
								<?php }
								if ($GP['reports-daily_sales']) { ?>
									<li id="reports_daily_sales">
										<a href="<?= site_url('reports/daily_sales') ?>">
											<i class="fa fa-calendar-o"></i><span class="text"> <?= lang('daily_sales'); ?></span>
										</a>
									</li>
									<?php }
								if ($GP['reports-monthly_sales']) { ?>
										<li id="reports_monthly_sales">
											<a href="<?= site_url('reports/monthly_sales') ?>">
												<i class="fa fa-calendar-o"></i><span class="text"> <?= lang('monthly_sales'); ?></span>
											</a>
										</li>
										<?php }
										if ($GP['reports-sales']) { ?>
											<li id="reports_sales">
												<a href="<?= site_url('reports/sales') ?>">
													<i class="fa fa-heart"></i><span class="text"> <?= lang('sales_report'); ?></span>
												</a>
											</li>
											<li id="reports_sales_profit">
												<a href="<?= site_url('reports/sales_profit') ?>">
													<i class="fa fa-heart"></i><span class="text"> <?= lang('sales_profit_report'); ?></span>
												</a>
											</li>
											<li id="reports_sales_discount">
												<a href="<?= site_url('reports/sales_discount') ?>">
													<i class="fa fa-gift"></i><span class="text"> <?= lang('sales_discount_report'); ?></span>
												</a>
											</li>
											<?php }
												if ($GP['reports-payments']) { ?>
												<li id="reports_payments">
													<a href="<?= site_url('reports/payments') ?>">
														<i class="fa fa-money"></i><span class="text"> <?= lang('payments_report'); ?></span>
													</a>
												</li>
												<?php }

													if ($GP['reports-profit_loss']) { ?>
													<li id="reports_profit_loss">
														<a href="<?= site_url('reports/profit_loss') ?>">
															<i class="fa fa-money"></i><span class="text"> <?= lang('profit_and_loss'); ?></span>
														</a>
													</li>
													<?php }
													if ($GP['reports-deliveries']) { ?>																			
													<li id="reports_deliveries">
														<a href="<?= site_url('reports/deliveries') ?>">
															<i class="fa fa-heart"></i><span class="text"> <?= lang('sale_by_delivery_person'); ?></span>
														</a>
													</li>
													<?php }
													if ($GP['reports-purchases']) { ?>
														<li id="reports_purchases">
															<a href="<?= site_url('reports/purchases') ?>">
																<i class="fa fa-star"></i><span class="text"> <?= lang('purchases_report'); ?></span>
															</a>
														</li>
														<?php }
															if ($GP['reports-customers']) { ?>
															<li id="reports_customer_report">
																<a href="<?= site_url('reports/customers') ?>">
																	<i class="fa fa-users"></i><span class="text"> <?= lang('customers_report'); ?></span>
																</a>
															</li>
															<?php }
															if ($GP['reports-suppliers']) { ?>
																<li id="reports_supplier_report">
																	<a href="<?= site_url('reports/suppliers') ?>">
																		<i class="fa fa-users"></i><span class="text"> <?= lang('suppliers_report'); ?></span>
																	</a>
																</li>
																<?php }
															if ($GP['reports-suspends']) { ?>
																	<li id="reports_supplier_report" <?=($this->uri->segment(2) === 'suspends' ? 'class="active"' : '')?>>
																		<a href="<?= site_url('reports/suspends') ?>">
																			<i class="fa fa-users"></i><span class="text"> <?= lang('suspend_report'); ?></span>
																		</a>
																	</li>
																	<?php } 
																?>
																<?php if ($GP['reports-account']) { ?>
																<li id="reports_ledger_report">
																	<a href="<?= site_url('reports/ledger') ?>">
																		<i class="fa fa-bars"></i><span class="text"> <?= lang('ledger'); ?></span>
																	</a>
																</li>
																<li id="reports_trial_balance_report">
																	<a href="<?= site_url('reports/trial_balance') ?>">
																		<i class="fa fa-bars"></i><span class="text"> <?= lang('trial_balance'); ?></span>
																	</a>
																</li>
																<li id="reports_balance_sheet_report">
																	<a href="<?= site_url('reports/balance_sheet') ?>">
																		<i class="fa fa-balance-scale"></i><span class="text"> <?= lang('balance_sheet'); ?></span>
																	</a>
																</li>
																<li id="reports_income_statement_report">
																	<a href="<?= site_url('reports/income_statement') ?>">
																		<i class="fa fa-money"></i><span class="text"> <?= lang('income_statement'); ?></span>
																	</a>
																</li>
																<li id="reports_cash_book_report" <?=($this->uri->segment(2) === 'cash_books' ? 'class="active"' : '')?> >
																	<a href="<?= site_url('reports/cash_books') ?>">
																		<i class="fa fa-money"></i><span class="text"> <?= lang('cash_book'); ?></span>
																	</a>
																</li><?php } 
																?>
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