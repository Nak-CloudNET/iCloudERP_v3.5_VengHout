<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= lang('pos_module') . " | " . $Settings->site_name; ?></title>
    <!--<script type="text/javascript">if(parent.frames.length !== 0){top.location = '<?= site_url('pos') ?>';}</script>-->
    <base href="<?= base_url() ?>"/>
    <meta http-equiv="cache-control" content="max-age=0"/>
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>
    <link rel="stylesheet" href="<?= $assets ?>styles/theme.css" type="text/css"/>
    <link rel="stylesheet" href="<?= $assets ?>styles/style.css" type="text/css"/>
    <link rel="stylesheet" href="<?= $assets ?>pos/css/posajax.css" type="text/css"/>
    <link rel="stylesheet" href="<?= $assets ?>pos/css/print.css" type="text/css" media="print"/>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>
	<script>
	var __c = '<?= $this->router->fetch_class() ?>';
	var __f = '<?= $this->router->fetch_method() ?>';

    function isString(val) {
        return typeof val === 'string' || ((!!val && typeof val === 'object') && Object.prototype.toString.call(val) === '[object String]');
    }

    function _escapQuote(str) {
        return str.replace(/(['"])/g, "\\$1");
    }

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
        if(isString(arr_key)) {
            localStorage.removeItem(__c + __f + arr_key);
        } else {
            for (var i = 0; i < arr_key.length; i++) {
                if (__getItem(arr_key[i])) {
                    localStorage.removeItem(arr_key[i]);
                }
            }
        }
	}
	</script>
    <!--[if lt IE 9]>
    <script src="<?= $assets ?>js/jquery.js"></script>
    <![endif]-->
    <?php if ($Settings->rtl) {?>
        <link href="<?= $assets ?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet"/>
        <link href="<?= $assets ?>styles/style-rtl.css" rel="stylesheet"/>
        <script type="text/javascript">
            $(document).ready(function () {
                $('.pull-right, .pull-left').addClass('flip');
            });
        </script>
    <?php } ?>
	<script>
		__setItem('exchange_kh', '<?php echo $exchange_rate->rate ? $exchange_rate->rate : 0; ?>');
		__setItem('u_username', '<?php echo $this->session->userdata('username') ?>');
		__setItem('queue', '<?php echo isset($queue->queue) ?>');
		__setItem('gp', '<?= json_encode($GP) ?>');
        __setItem('owner', '<?= json_encode($Owner) ?>');
        __setItem('admin', '<?= json_encode($Admin) ?>');
	</script>

	<script>

		<?php if($sale_order_id){ ?>
			__setItem('positems', JSON.stringify(<?= $sale_order_items; ?>));
			__setItem('poscustomer', '<?= $sale_order->customer_id ?>');
			__setItem('poswarehouse', '<?= $sale_order->warehouse_id ?>');
			__setItem('posdiscount', '<?= $sale_order->order_discount_id ?>');
			__setItem('postax2', '<?= $sale_order->order_tax_id ?>');
			__setItem('posbiller', '<?= $sale_order->biller_id ?>');
			__setItem('saleman', '<?= $sale_order->saleman_by ?>');
			__setItem('delivery_by', '<?= $sale_order->delivery_by ?>');

		<?php } ?>

		<?php if($combine_table){ ?>
			__setItem('positems', JSON.stringify(<?= $combine_items; ?>));
		<?php } ?>


	</script>
	<style>

		.modal-body-scroll{
			height: 650px;
			overflow-y: auto;
		}

		.select2-result.select2-result-unselectable.select2-disabled {
			display: none;
		}

		.btn-group .btn + .btn, .btn-group .btn + .btn-group, .btn-group .btn-group + .btn, .btn-group .btn-group + .btn-group {
			margin-left: 0 !important;
		}

        @media screen and (max-width: 768px) {
            #centerdiv .col-md-6, .col-md-12 {
                padding: 0 !important;
            }
          .btn-group .btn-group-justified {
            bottom: 0 !important;
          }
        }

        @media screen and (max-width: 600px) {
            #print_order_food, #print_order_drink {
                height: 43px;
            }

            #print_bill {
                height: 42px;
            }
        }

        #suspend-slider {
            border: 1px solid lightblue;
            border-right: none;
            overflow-y: scroll !important;
        }

	</style>
</head>
<body>

<noscript>
    <div class="global-site-notice noscript">
        <div class="notice-inner">
            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in
                your browser to utilize the functionality of this website.</p>
        </div>
    </div>
</noscript>
<?php
if($user_layout->pos_layout != ''){
	$layout = $user_layout->pos_layout;
}else{
	$layout = $pos_settings->pos_layout;
}

$arrSuspend = array();
$this->db->select('*');
$this->db->from('erp_suspended_bills');
//$this->db->where('created_by', $this->session->userdata('user_id'));
$q = $this->db->get();
if ($q->num_rows() > 0) {
    foreach ($q->result() as $row) {
        $arrSuspend[$row->suspend_id]['suspend'] = "suspend";
		if(strlen($row->customer)>10){
			$cust = substr($row->customer,0,10);
		}else{
			$cust = $row->customer;
		}
        $arrSuspend[$row->suspend_id]['cust']   = $cust;
        $arrSuspend[$row->suspend_id]['date']   = $row->date;
        $arrSuspend[$row->suspend_id]['count']  = $row->count;
        //$arrSuspend[$row->suspend_id]['total']  = $row->total;
        $arrSuspend[$row->suspend_id]['id']     = $row->id;
        $arrSuspend[$row->id]['suspend_not']    = $row->suspend_id;
		$arrSuspend[$row->id]['suspend_name']   = $row->suspend_name;
		//echo $row->total;exit;
    }
}
?>
<div id="wrapper">
    <header id="header" class="navbar">
        <div class="container bblack" id="container">
            <a class="navbar-brand" href="<?= site_url() ?>"><span class="logo"><span class="pos-logo-lg"><?= $Settings->site_name ?></span><span class="pos-logo-sm"><?= lang('pos') ?></span></span><br><span class "btn bblack" id="display_time"></span></a>

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
                                <a href="<?= site_url('auth/profile/' . $this->session->userdata('user_id')); ?>">
                                    <i class="fa fa-user"></i> <?= lang('profile'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?= site_url('auth/profile/' . $this->session->userdata('user_id') . '/#cpassword'); ?>">
                                    <i class="fa fa-key"></i> <?= lang('change_password'); ?>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?= site_url('auth/logout'); ?>">
                                    <i class="fa fa-sign-out"></i> <?= lang('logout'); ?>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav navbar-nav pull-right">
					<li class="dropdown">
						<a class="btn pos-tip" title="<?= lang('dashboard') ?>" data-placement="left" href="<?= site_url('welcome') ?>">
							<i class="fa fa-dashboard"></i><p><?= lang('dashboard'); ?></p>
						</a>
					</li>
                    <?php if ($Owner) { ?>
                        <li class="dropdown">
                            <a class="btn pos-tip" title="<?= lang('settings') ?>" data-placement="left" href="<?= site_url('pos/settings') ?>">
                                <i class="fa fa-cogs"></i><p><?= lang('settings'); ?></p>
                            </a>
                        </li>
                    <?php } ?>
                    <li class="dropdown hidden-xs">
                        <a class="btn pos-tip" title="<?= lang('calculator') ?>" data-placement="left" href="#" data-toggle="dropdown">
                            <i class="fa fa-calculator"></i><p><?= lang('calculator'); ?></p>
                        </a>
                        <ul class="dropdown-menu pull-right calc">
                            <li class="dropdown-content">
                                <span id="inlineCalc"></span>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown hidden-sm">
                        <a class="btn pos-tip" title="<?= lang('shortcuts') ?>" data-placement="left" href="#" data-toggle="modal" data-target="#sckModal">
                            <i class="fa fa-key"></i><p><?= lang('shortcuts'); ?></p>
                        </a>
                    </li>
					<?php if ($pos_settings->show_suspend_bar == 40) { ?>
					<li class="dropdown">
                        <a class="btn pos-tip" title="<?= lang('view_kitchen') ?>" data-placement="bottom" href="<?= site_url('pos/view_kitchen') ?>" target="_blank">
                            <i class="fa fa-laptop"></i><p><?= lang('kitchen'); ?></p>
                        </a>
                    </li>
					<li class="dropdown">
                        <a class="btn pos-tip" title="<?= lang('view_complete') ?>" data-placement="bottom" href="<?= site_url('pos/view_complete') ?>" target="_blank">
                            <i class="fa fa-laptop"></i><p><?= lang('complete'); ?></p>
                        </a>
                    </li>
					<?php } ?>
					<!--<li class="dropdown">
                        <a class="btn pos-tip" title="<?/*= lang('view_bill_screen') */?>" data-placement="bottom" href="<?/*= site_url('pos/view_bill') */?>" target="_blank">
                            <i class="fa fa-laptop"></i><p><?/*= lang('screen'); */?></p>
                        </a>
                    </li>
					<li class="dropdown">
                        <a class="btn pos-tip" title="<?/*= lang('delivery') */?>" data-placement="bottom" href="<?/*= site_url('pos/deliveries') */?>" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-car"></i><p><?/*= lang('delivery'); */?></p>
                        </a>
                    </li>-->
					<!--
                    <li class="dropdown">
                        <a class="btn bdarkGreen pos-tip" id="register_details" title="<?= lang('register_details') ?>" data-placement="bottom" data-html="true" href="<?= site_url('pos/register_details') ?>" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-check-circle"></i><p><?= lang('register'); ?></p>
                        </a>
                    </li>-->
                    <li class="dropdown">
                        <a class="btn borange pos-tip" id="close_register" title="<?= lang('close_register') ?>" data-placement="bottom" data-html="true" href="<?= site_url('pos/close_register/' .$this->session->userdata('user_id')) ?>" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-times-circle"></i><p><?= lang('clear'); ?></p>
                        </a>
                    </li>
                    <?php if (!$Owner) { ?>
                        <li class="dropdown hidden-xs">
                            <a href="#" id="pos-list" title="<?= lang('list_sales') ?>"
                               class="btn blightOrange pos-tip external" data-toggle="modal" data-target="#poslist">
                                <i class="fa fa-print"></i>
                                <p><?= lang('list_sales'); ?></p>
                            </a>
                        </li>
                    <?php } ?>
                    <li class="dropdown">
                        <a class="btn borange pos-tip" id="add_expense" title="<span><?= lang('add_expense') ?>" data-placement="bottom" data-html="true" href="<?= site_url('purchases/add_expense') ?>" data-toggle="modal" data-target="#myModal">
                            <i class="fa fa-dollar"></i><p><?= lang('expense'); ?></p>
                        </a>
                    </li>
                    <?php if ($Owner) { ?>
                        <li class="dropdown">
                            <a class="btn bdarkGreen pos-tip" id="today_profit" title="<?= lang('today_profit') ?>" data-placement="bottom" data-html="true" href="<?= site_url('reports/profit') ?>" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-hourglass-half"></i><p><?= lang('profit'); ?></p>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($Owner || $Admin) { ?>
                        <li class="dropdown">
                            <a class="btn bdarkGreen pos-tip" id="today_sale" title="<?= lang('today_sale') ?>" data-placement="bottom" data-html="true" href="<?= site_url('pos/today_sale') ?>" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-heart"></i><p><?= lang('today'); ?></p>
                            </a>
                        </li>
                        <li class="dropdown hidden-xs">
                            <a class="btn bblue pos-tip" title="<?= lang('list_open_registers') ?>" data-placement="bottom" href="<?= site_url('pos/registers') ?>">
                                <i class="fa fa-list"></i><p><?= lang('register'); ?></p>
                            </a>
                        </li>
						<li class="dropdown hidden-xs">
							<a href="#" id="pos-list" title="<?= lang('list_sales') ?>" class="btn blightOrange pos-tip external" data-toggle="modal" data-target="#poslist">
								<i class="fa fa-print"></i><p><?= lang('list_sales'); ?></p>
							</a>
                        </li>
                        <li class="dropdown hidden-xs">
                            <a class="btn bred pos-tip" title="<?= lang('clear_ls') ?>" data-placement="bottom" id="clearLS" href="#">
                                <i class="fa fa-eraser"></i><p><?= lang('reset'); ?></p>
                            </a>
                        </li>
                    <?php } ?>
                </ul>

            </div>
        </div>
    </header>
<?php if ($layout != 0 && $layout != 1 && $layout != 3 && $layout != 5) {?>
	<div id="content" style="margin-bottom: -40px !Important; ">
		<div class="grid-view" style="width:98%; overflow: hidden;">
			<div id="proContainer" style="height: 118px !important;">
				<div id="product-sale-view">
				</div>
			</div>
		</div>
	</div>
<?php } else if($layout == 5) { ?>
	<div id="cp">
		<div id="cpinner" style="padding:0;">
			<div class="quick-menu">
				<div id="proContainer">
					<div id="ajaxproducts" style="height: 260px !important;">
						<div id="item-list">
							<div class="slide_item text-center" id="slide_item">
								<?php
									$totimg = 0;
									$dir = "assets/uploads/landscape/";
									$allowed_types = array('png','jpg','jpeg','gif');
									if (is_dir($dir)) {
										if ($dh = opendir($dir)) {
											while (($file = readdir($dh)) !== false) {
												if( in_array(strtolower(substr($file,-3)),$allowed_types) OR
												in_array(strtolower(substr($file,-4)),$allowed_types) )
												{$a_img[] = $file;}
											}
											$totimg = count($a_img);
											closedir($dh);
										}
									}
								?>
								<div class="">
								  <div id="myCarousel" class="carousel slide col-xs-12 col-md-12 col-lg-12" data-ride="carousel" style="padding-left:0;padding-right:0;">
									<!-- Indicators -->
									<ol class="carousel-indicators">
									<?php
									for($x=0; $x < $totimg; $x++){
										if($x == 0) {
									?>
											  <li data-target="#myCarousel" data-slide-to="<?= $x; ?>" class="active"></li>
									<?php
										} else {
									?>
											  <li data-target="#myCarousel" data-slide-to="<?= $x; ?>"></li>
									<?php
										}
									}
									?>
									</ol>

									<!-- Wrapper for slides -->
									<div class="carousel-inner" role="listbox" >
									<?php
										for($x=0; $x < $totimg; $x++){
											if($x == 0) {
									?>
									  <div class="row item active">
										<img src="<?= base_url(). $dir . $a_img[$x]; ?>" class="img-responsive" style="height:265px; width:100%;"/>
									  </div>
									<?php
											} else {
									?>
									  <div class="row item">
										<img src="<?= base_url(). $dir . $a_img[$x]; ?>" class="img-responsive" style="height:265px; width:100%;"/>
									  </div>
									<?php
											}
										}
									?>
									</div>

									<!-- Left and right controls -->
									<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev" style="padding-top:100px;">
									  <span class="fa fa-angle-left fa-3x" aria-hidden="true"></span>
									  <span class="sr-only">Previous</span>
									</a>
									<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next" style="padding-top:100px;">
									  <span class="fa fa-angle-right fa-3x" aria-hidden="true"></span>
									  <span class="sr-only">Next</span>
									</a>
								  </div>
								</div>
							</div>
						</div>
						<div id="btn_gift_card" class="btn-group btn-group-justified btn_gift_card" style="position:relative; margin:auto;bottom:30px;width:35%;">
							<div class="btn-group" >
								<button style="" class="btn btn-primary pos-tip" title="<?= lang('previous') ?>" type="button" id="previous">
									<i class="fa fa-chevron-left"></i>
								</button>
							</div>
							<?php if ($Owner || $Admin || $GP['sales-add_gift_card']) { ?>
							<div class="btn-group">
								<button style="" class="btn btn-primary pos-tip" type="button" id="sellGiftCard" title="<?= lang('sell_gift_card') ?>">
									<i class="fa fa-credit-card" id="addIcon"></i> <?= lang('sell_gift_card') ?>
								</button>
							</div>
							<?php } ?>
							<div class="btn-group">
								<button style="" class="btn btn-primary pos-tip" title="<?= lang('next') ?>" type="button" id="next">
									<i class="fa fa-chevron-right"></i>
								</button>
							</div>
						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>

		<div style="clear:both;"></div>
	</div>
	<div style="clear:both;"></div>
<?php } else if ($layout == 6) { ?>
    <div id="content" style="margin-bottom: -40px !Important; ">
        <div class="grid-view" style="width:98%; overflow: hidden;">
            <div id="proContainer" style="height: 118px !important;">
                <div id="product-sale-view">
                </div>
            </div>
        </div>
    </div>
<?php } ?>
    <div id="content">

		<div class="c1">
            <div class="pos">
                <?php
                if ($error) {
                    echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close fa-2x\" data-dismiss=\"alert\">&times;</button>" . $error . "</div>";
                }
                ?>
                <?php
                if ($message) {
                    echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close fa-2x\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
                }
                ?>
				<div id="pos">
                    <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'pos-sale-form');
                    echo form_open("pos", $attrib); ?>
                    <?php if ($layout == 2) {?>
                        <div id="leftdiv100">
                    <?php } elseif($layout == 3) { ?>
					    <div id="leftdiv110">
					<?php } elseif($layout == 5) { ?>
					    <div id="leftdiv115">
                    <?php } elseif($layout == 6) { ?>
                        <div id="centerdiv">
					<?php } else { ?>
                        <div id="leftdiv">
                    <?php } ?>
                        <div id="printhead">
                            <h4 style="text-transform:uppercase;"><?php echo $Settings->site_name; ?></h4>
                            <?php
                            echo "<h5 style=\"text-transform:uppercase;\">" . $this->lang->line('order_list') . "</h5>";
                            echo $this->lang->line("date") . " " . $this->erp->hrld(date('Y-m-d H:i:s'));
                            ?>
                        </div>
					<?php if($layout == 5) { ?>
						<div id="left-top">
                            <div style="position: absolute; <?= $Settings->rtl ? 'right:-9999px;' : 'left:-9999px;'; ?>"><?php echo form_input('test', '', 'id="test" class="kb-pad"'); ?></div>
                            <div class="row">
								<div class="form-group col-md-6">
									<?php if ($Owner || $Admin || $GP['customers-add']) { ?><div class="input-group"><?php } ?>
										<?php
										echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'id="poscustomer" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("customer") . '" required="required" class="form-control pos-input-tip" style="width:100%;"');
										?>

										<div class="input-group-addon no-print" style="padding: 2px 5px; border-left: 0;">
											<a href="#" id="view-customer" class="external" data-toggle="modal" data-target="#myModal">
												<i class="fa fa-2x fa-user" id="addIcon"></i>
											</a>
										</div>
										<?php if ($Owner || $Admin || $GP['customers-add']) { ?>
										<div class="input-group-addon no-print" style="padding: 2px 5px;">
											<a href="<?= site_url('customers/add_customer_pos'); ?>" id="add-customer" class="external" data-toggle="modal" data-target="#myModal">
												<i class="fa fa-2x fa-plus-circle" id="addIcon"></i>
											</a>
										</div>
									</div>
									<?php } ?>
									<div style="clear:both;"></div>
								</div>
								<div class="no-print">
									<?php if ($Owner || $Admin) { ?>
										<div class="form-group col-md-6">
											<?php
											$wh[''] = '';
											foreach ($warehouses as $warehouse) {
												$wh[$warehouse->id] = $warehouse->name;
											}
											echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="poswarehouse" class="form-control pos-input-tip" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" required="required" style="width:100%;" ');
											?>

										</div>
									<?php } else { ?>

										<div class="form-group col-md-6">
											<?php
											$wh[''] = '';
											foreach ($user_ware as $warehouse) {
												$wh[$warehouse->id] = $warehouse->name;
											}
											echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="poswarehouse" class="form-control pos-input-tip" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" required="required" style="width:100%;" ');
											?>

										</div>

									<?php } ?>
								</div>
							</div>
							<div class="row">
								<div class="no-print">
									<div class="form-group col-md-12" id="ui">
										<?php if ($Owner || $Admin || $GP['products-add']) { ?><div class="input-group"><?php } ?>
											<?php echo form_input('add_item', '', 'class="form-control pos-tip" id="add_item" data-placement="top" data-trigger="focus" placeholder="' . $this->lang->line("search_product_by_name_code") . '" title="' . $this->lang->line("au_pr_name_tip") . '"'); ?>
											<?php if ($Owner || $Admin || $GP['products-add']) { ?>
											<div class="input-group-addon" style="padding: 2px 5px;">
												<a href="#" id="search_details">
													<i class="fa fa-2x fa-search" id="addIcon"></i>
												</a>
											</div>
											<div class="input-group-addon" style="padding: 2px 5px;">
												<a href="#" id="addManually">
													<i class="fa fa-2x fa-plus-circle" id="addIcon"></i>
												</a>
											</div>
											<div class="input-group-addon" style="padding: 2px 5px;">
												<a href="#" id="search_floor">
													<i class="fa fa-2x fa-th" id="addIcon"></i>
												</a>
											</div>
										</div>
										<?php } ?>
										<div style="clear:both;"></div>
									</div>
								</div>
							</div>
                        </div>
					<?php } else { ?>
						<div id="left-top">
                            <div style="position: absolute; <?= $Settings->rtl ? 'right:-9999px;' : 'left:-9999px;'; ?>"><?php echo form_input('test', '', 'id="test" class="kb-pad"'); ?></div>
							<div class="col-md-6" style="padding-left:0; margin-bottom: 5px">
                                <div class="form-group">
                                   	<div class="input-group">
                                    <?php
                                        echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'id="poscustomer" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("customer") . '" required="required" class="form-control pos-input-tip" style="width:100%;"');
                                        ?>
                                        <div class="input-group-addon no-print" style="padding: 2px 5px; border-left: 0;">
                                            <a href="#" id="view-customer" class="external" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-2x fa-user" id="addIcon"></i>
                                            </a>
                                        </div>
                                        <?php if ($Owner || $Admin || $GP['customers-add']) { ?>
                                        <div class="input-group-addon no-print" style="padding: 2px 5px;">
                                            <a href="<?= site_url('customers/add_customer_pos'); ?>" id="add-customer" class="external" data-toggle="modal" data-target="#myModal">
                                                <i class="fa fa-2x fa-plus-circle" id="addIcon"></i>
                                            </a>
                                        </div>
                                    	<?php } ?>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                            </div>
							<?php if ($Owner || $Admin) { ?>
								<div class="col-md-6" style="padding-right:0;">
									<div class="form-group">
										<?php
										$wh[''] = '';
										foreach ($warehouses as $warehouse) {
											$wh[$warehouse->id] = $warehouse->name;
										}
										echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="poswarehouse" class="form-control pos-input-tip" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" required="required" style="width:100%;" ');
										?>
									</div>
								</div>
							<?php } else { ?>

								<div class="form-group col-md-6">
									<?php
									$wh[''] = '';
									foreach ($user_ware as $warehouse) {
										$wh[$warehouse->id] = $warehouse->name;
									}
									echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="poswarehouse" class="form-control pos-input-tip" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" required="required" style="width:100%;" ');
									?>

								</div>

							<?php } ?>
							<div class="col-md-12" style="padding:0;">
								<div class="no-print">
									<div class="form-group" id="ui">
										<?php if ($Owner || $Admin || $GP['products-add']) { ?><div class="input-group"><?php } ?>
											<?php echo form_input('add_item', '', 'class="form-control pos-tip" id="add_item" data-placement="top" data-trigger="focus" placeholder="' . $this->lang->line("search_product_by_name_code") . '" title="' . $this->lang->line("au_pr_name_tip") . '"'); ?>
											<?php if ($Owner || $Admin || $GP['products-add']) { ?>
											<div class="input-group-addon" style="padding: 2px 5px;">
												<a href="#" id="search_details">
													<i class="fa fa-2x fa-search" id="addIcon"></i>
												</a>
											</div>
											<div class="input-group-addon" style="padding: 2px 5px;">
												<a href="#" id="addManually">
													<i class="fa fa-2x fa-plus-circle" id="addIcon"></i>
												</a>
											</div>
											<div class="input-group-addon" style="padding: 2px 5px;">
												<a href="#" id="search_floor">
													<i class="fa fa-2x fa-th" id="addIcon"></i>
												</a>
											</div>
										</div>
										<?php } ?>
										<div style="clear:both;"></div>
									</div>
								</div>
							</div>
							<div style="clear:both;"></div>
                        </div>
						<div style="clear:both;"></div>
					<?php } ?>
                        <div id="print">
                            <div id="left-middle">
                                <div id="product-list">
								<?php if ($layout == 3) { ?>
                                    <table class="table items table-striped table-bordered table-condensed table-hover" id="posTable" style="margin-bottom: 0; font-size: 16px;">
								<?php } else { ?>
									<table class="table items table-striped table-bordered table-condensed table-hover" id="posTable" style="margin-bottom: 0;">
								<?php } ?>
                                        <thead>
                                        <tr>
											<th width="5%"><?= lang("num_"); ?></th>
											<?php if ($layout == 3 && $pos_settings->show_item_img != 0) { ?>
											<th width="10%"><?= lang("image"); ?></th>
											<?php } ?>
											<?php if($pos_settings->show_product_code == 1) { ?>
                                            <th width="15%"><?= lang("code"); ?></th>
                                            <?php } ?>
                                            <th width="30%"><?= lang("product"); ?></th>
											<?php if($this->session->userdata('view_stock')){ ?>
											<th width="10%"><?= lang("stock"); ?></th>
											<?php } ?>
                                            <th width="10%"><?= lang("price"); ?></th>
											<th width="10%"><?= lang("price_kh"); ?></th>
                                            <th width="10%"><?= lang("qty"); ?></th>
											<th width="10%"><?= lang("discount"); ?></th>
                                            <th width="15%"><?= lang("subtotal"); ?></th>
                                            <th style="text-align: center;"><i class="fa fa-trash-o"  style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
									<input type="hidden" name="table_no" class=" table_no" id="table_no" value="<?=(isset($arrSuspend[$sid]['suspend_not']) ? $arrSuspend[$sid]['suspend_not'] : '')?>"/>
                                    <input type="hidden" name="suspend_" id="suspend_id" value="<?=(isset($sid) ? $sid : 0)?>" />
									<input type="hidden" name="suspend_date" id="suspend_date" value="<?=(isset($arrSuspend[$sid]['date'])?$arrSuspend[$sid]['date']:"")?>">
									<input type="hidden" name="suspend_name" id="suspend_name" value="<?=(isset($arrSuspend[$sid]['suspend_name'])?$arrSuspend[$sid]['suspend_name']:0)?>">

                                    <div style="clear:both;"></div>
                                </div>
                            </div>
                            <div style="clear:both;"></div>
                            <div id="left-bottom">
                                <table id="totalTable" style="width:100%; float:right; padding:5px; color:#000; background: #FFF;">
									<tr>
                                        <td style="padding: 5px 10px; <?php echo ($layout == 6?'font-size:20px;':'font-size:16px;') ?>"><?= lang('items'); ?> <span style="padding: 5px 10px; <?php echo ($layout == 6?'font-size:20px;':'font-size:16px;') ?> font-weight:bold;" id="titems">0</span></td>
										<td></td>
										
										<td style="padding: 5px 10px; <?php echo ($layout == 6?'font-size:20px;':'font-size:16px;') ?>"><?= lang('rate'); ?></td>
                                        <td class="text-right" style="padding: 5px 10px; font-weight:bold;<?php echo ($layout == 6?'font-size:20px;':'font-size:16px;') ?>">
                                            <span id="khmer_rate"><?php echo $exchange_rate->rate?number_format($exchange_rate->rate):0 ?>  ៛</span>
                                        </td>
										
                                        <td style="padding: 5px 10px; <?php echo ($layout == 6?'font-size:20px;':'font-size:16px;') ?>"><?= lang('total'); ?></td>
                                        <td class="text-right" style="padding: 5px 10px; font-weight:bold;<?php echo ($layout == 6?'font-size:20px;':'font-size:16px;') ?>">
                                            <span id="total">0.00</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td style="padding: 5px 10px; <?php echo ($layout == 6?'font-size:20px;':'font-size:16px;') ?>"><?= lang('order_tax'); ?>
                                            <a href="#" id="pptax2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                        <td class="text-right" style="padding: 5px 10px;font-weight:bold;<?php echo ($layout == 6?'font-size:20px;':'font-size:16px;') ?>">
                                            <span id="ttax2">0.00</span>
                                        </td>

										<td style="padding: 5px 10px;<?php echo ($layout == 6?'font-size:20px;':'font-size:16px;') ?>"><?= lang('shipping'); ?>
                                            <a href="#" id="edit_shipping">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                        <td class="text-right" style="padding: 5px 10px; font-weight:bold; <?php echo ($layout == 6?'font-size:20px;':'font-size:16px;') ?>">
                                            <span id="text_shipping">0.00</span>
                                        </td>

                                        <td style="padding: 5px 10px; <?php echo ($layout == 6?'font-size:20px;':'font-size:16px;') ?>"><?= lang('discount'); ?>
                                            <a href="#" id="ppdiscount">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                        <td class="text-right" style="padding: 5px 10px; font-weight:bold; <?php echo ($layout == 6?'font-size:20px;':'font-size:16px;') ?>">
                                            <span id="tds">0.00</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="padding: 5px 10px; border-top: 1px solid #666; font-size: 20px; font-weight:bold; background:#333; color:#FFF;" colspan="2">
                                            <?= lang('total_payable'); ?>
                                        </td>

                                        <td colspan ="4" class="text-right" style="padding:5px 10px 5px 10px; font-size: 30px; border-top: 1px solid #666; font-weight:bold; background:#333; color:#FFF;" colspan="2">
                                            <span style="float:left" id="gtotal_kh"></span>
											<span id="gtotal">0.00</span>
                                        </td>
                                    </tr>
                                </table>

                                <div class="clearfix"></div>
                                <div id="botbuttons" style="text-align:center;">
                                    <input type="hidden" name="biller" id="biller"
                                           value=" <?= ($Owner || $Admin) ? $pos_settings->default_biller : $this->session->userdata('biller_id') ?>" />
									<input type="hidden" name="saleman_1" id="saleman_1" value=""/>
									<input type="hidden" name="delivery_by_1" id="delivery_by_1" value=""/>
									<input type="hidden" name="reference_nob" id="reference_nob" class="reference_nob" value=""/>
									<input type="hidden" name="sale_status" id="sale_status_1" value=""/>
									<input type="hidden" name="address" id="address" value=""/>
									<input type="hidden" name="date" value="" class="date_c">
									<input type="hidden" name="sale_type" value="<?= $type; ?>" class="sale_type">
									<input type="hidden" name="sale_type_id" value="<?= $type_id; ?>" class="sale_type_id">

                                    <div class="btn-group btn-group-justified">
                                        <div class="btn-group">
                                            <div class="btn-group btn-group-justified">
                                                <div class="btn-group">
                                                    <button type="button" title="Cancel Order - <?= $pos_settings->cancel_sale ?>" style="<?php echo ($layout == 6?'height:85px':'height:68px') ?>" class="btn btn-danger <?php echo ($layout == 6?'font6':'') ?>" id="reset">
														<i class="fa fa-remove"></i> <?= lang('cancel'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($layout == 2 || $layout == 3) { ?>
                                        <div class="btn-group">
                                            <div class="btn-group btn-group-justified">
                                                <div class="btn-group">
                                                    <button type="button" title="Products - <?= $pos_settings->show_search_item ?>" style="height:68px;" class="btn btn-warning addButton" id="search_details">
                                                        <i class="fa fa-search"></i> <?= lang('products'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                    						<?php if($pos_settings->show_suspend_bar){ ?>
                    					<div class="btn-group">
                                        	<div class="btn-group btn-group-justified">
                                           		<div class="btn-group">
													<button type="button" title="Table / Room - <?= (isset($arrSuspend[$sid]['suspend_name']) ? '#'.$arrSuspend[$sid]['suspend_name'] : '')?>" style="height:68px;" class="btn btn-warning open-suspend" id="suspend">
                                                        <i class="fa fa-info-circle"></i> <?= lang('suspend'); ?><?=(isset($arrSuspend[$sid]['suspend_not']) ? '#'.$arrSuspend[$sid]['suspend_not'] : '')?>
                                                    </button>
                                            	</div>
                                    		</div>
                                    	</div>
                                        	<?php } ?>
                                         <input type="hidden" class="reference_nob" name="reference_nob" id="reference_nob" value="<?= $reference ? $reference : '' ?>"/>
                                        <div class="btn-group">
                                            <div class="btn-group btn-group-justified">
                                                <div class="btn-group">
                                                    <button type="button" title="Print Order - <?= $pos_settings->print_items_list ?>" style="height:68px;width:50%;" class="btn btn-primary" id="print_order_food">
                                                        <i class="fa fa-print"></i> <?= lang('order_food'); ?>
                                                    </button>
													<button type="button" title="Print Order - <?= $pos_settings->print_items_list ?>" style="height:68px;width:50%;" class="btn btn-primary" id="print_order_drink">
                                                        <i class="fa fa-print"></i> <?= lang('order_drink'); ?>
                                                    </button>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="btn-group">
                                            <div class="btn-group btn-group-justified">
                                                <div class="btn-group">
                                                    <button type="button" title="Print Bill - <?= $pos_settings->print_bill ?>" style="height:68px;" class="btn btn-primary" id="print_bill" style="margin-left: 0 !important;">
                                                        <i class="fa fa-print"></i> <?= lang('print_bill'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                    					<?php } else { ?>
                                        <input type="hidden" class="reference_nob" name="reference_nob" id="reference_nob" value="<?= $reference ? $reference : '' ?>"/>
                    					<div class="btn-group">
                                            <div class="btn-group btn-group-justified">
                                                <div class="btn-group">
                                                    <button type="button" title="Print Order - <?= $pos_settings->print_items_list ?>" class="btn btn-primary <?php echo ($layout == 6?'font6':'') ?>" id="print_order_food" style="width:50%;">
                                                        <i class="fa fa-print"></i> <?= lang('order_food'); ?>
                                                    </button>
													<button type="button" title="Print Order - <?= $pos_settings->print_items_list ?>" class="btn btn-primary <?php echo ($layout == 6?'font6':'') ?>" id="print_order_drink" style="width:50%;">
                                                        <i class="fa fa-print"></i> <?= lang('order_drink'); ?>
                                                    </button>

                                                    <button type="button" title="Print Bill - <?= $pos_settings->print_bill ?>" class="btn btn-primary <?php echo ($layout == 6?'font6':'') ?>" id="print_bill"  style="margin-left: 0 !important;width:100%;">
                                                        <i class="fa fa-print"></i> <?= lang('print_bill'); ?>
                                                    </button>

                                                </div>
                                            </div>
                                        </div>

                    					<?php } ?>
                                        <div class="btn-group">
                                            <button type="button" title="Payment - <?= $pos_settings->finalize_sale ?>" style="<?php echo ($layout == 6?'height:85px':'height:68px') ?>" class="btn btn-success <?php echo ($layout == 6?'font6':'') ?>" id="payment">
                                                <i class="fa fa-money"></i> <?= lang('payment'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div style="clear:both; height:5px;"></div>
                                <div id="num">
                                    <div id="icon"></div>
                                </div>
                                <span id="hidesuspend"></span>
                                <input type="hidden" name="pos_note" value="" id="pos_note">
                                <input type="hidden" name="staff_note" value="" id="staff_note">
								<input type="hidden" name="suspend_room" value="" id="suspend_room1">
								<input type="hidden" name="suppend_name" value="<?= isset($suppend_name);?>">
								<input type="hidden" name="pos_date" value="" id="pos_date">

                                <div id="payment-con">
                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                        <input type="hidden" name="amount[]" id="amount_val_<?= $i ?>" value=""/>
                                        <input type="hidden" name="other_cur_paid[]" id="other_cur_paid_val_<?= $i ?>" value=""/>
                                        <input type="hidden" name="balance_amount[]" id="balance_amount_<?= $i ?>" value=""/>
                                        <input type="hidden" name="paid_by[]" id="paid_by_val_<?= $i ?>" value="cash"/>
                                        <input type="hidden" name="bank_account[]" id="bank_account_val_<?= $i ?>" value=""/>
                                        <input type="hidden" name="cc_no[]" id="cc_no_val_<?= $i ?>" value=""/>
                                        <input type="hidden" name="paying_gift_card_no[]" id="paying_gift_card_no_val_<?= $i ?>" value=""/>
										<input type="hidden" name="paying_deposit[]" id="paying_deposit_val_<?= $i ?>" value=""/>
                                        <input type="hidden" name="cc_holder[]" id="cc_holder_val_<?= $i ?>" value=""/>
                                        <input type="hidden" name="cheque_no[]" id="cheque_no_val_<?= $i ?>" value=""/>
                                        <input type="hidden" name="voucher_no[]" id="voucher_no_val_<?= $i ?>" value=""/>
                                        <input type="hidden" name="cc_month[]" id="cc_month_val_<?= $i ?>" value=""/>
                                        <input type="hidden" name="cc_year[]" id="cc_year_val_<?= $i ?>" value=""/>
                                        <input type="hidden" name="cc_type[]" id="cc_type_val_<?= $i ?>" value="Visa"/>
                                        <input type="hidden" name="cc_cvv2[]" id="cc_cvv2_val_<?= $i ?>" value=""/>
                                        <input type="hidden" name="payment_note[]" id="payment_note_val_<?= $i ?>" value=""/>
										<!-- Loan -->
                                    <?php }
									?>
                                </div>

								<input type="hidden" name="depreciation_rate1[]" id="depreciation_rate1_val_1" value=""/>
								<input type="hidden" name="depreciation_term[]" id="depreciation_term_val_1" value=""/>
								<input type="hidden" name="depreciation_type[]" id="depreciation_type_val_1" value=""/>
								<div id="loan1" style="display:none"></div>

								<input type="hidden" name="loan_rate" id="loan_rate" value=""/>
								<input type="hidden" name="loan_type" id="loan_type" value=""/>
								<input type="hidden" name="loan_term" id="loan_term" value=""/>

                                <input name="order_tax" type="hidden" value="<?= $suspend_sale ? $suspend_sale->order_tax_id : $Settings->default_tax_rate2; ?>" id="postax2">
                                <input name="combine_table_id" type="hidden" value="<?= $combine_table ? $combine_table : '' ?>" id="combine_table">
                                <input name="discount" type="hidden" value="<?= $suspend_sale ? $suspend_sale->order_discount_id : ''; ?>" id="posdiscount">
                                <input type="hidden" name="rdiscount" id="rdiscount" value=""/>
                                <input type="hidden" name="order_discount" id="order_discount" value=""/>
                                <input name="shipping" type="hidden" value="" id="posshipping">
                                <input type="hidden" name="rpaidby" id="rpaidby" value="cash" style="display: none;"/>
                                <input type="hidden" name="total_items" id="total_items" value="0" style="display: none;"/>
                                <input type="submit" id="submit_sale" value="Submit Sale" style="display: none;"/>
                            </div>
                        </div>

                    </div>
                   <?php if ($layout != 2 && $layout != 3 && $layout != 5 && $layout != 6) {?>
                    <div id="cp">
                        <div id="cpinner" style="padding:0;">
                            <div class="quick-menu">
                                <div id="proContainer">
                                    <div id="ajaxproducts">
										<?php if($layout == 1) { ?>
                                        <div id="item-list">
											<div class="slide_item text-center" id="slide_item">
												<?php
													$totimg = 0;
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
															closedir($dh);
														}
													}
												?>
												<div class="">
												  <div id="myCarousel" class="carousel slide col-xs-12 col-md-12 col-lg-12" data-ride="carousel" style="padding-left:0;padding-right:0;height=70%;">
													<!-- Indicators -->
													<ol class="carousel-indicators">
													<?php
													for($x=0; $x < $totimg; $x++){
														if($x == 0) {
													?>
															  <li data-target="#myCarousel" data-slide-to="<?= $x; ?>" class="active"></li>
													<?php
														} else {
													?>
															  <li data-target="#myCarousel" data-slide-to="<?= $x; ?>"></li>
													<?php
														}
													}
													?>
													</ol>

													<!-- Wrapper for slides -->
													<div class="carousel-inner" role="listbox" >
													<?php
														for($x=0; $x < $totimg; $x++){
															if($x == 0) {
													?>
													  <div class="item active">
														<img src="<?= base_url(). $dir . $a_img[$x]; ?>" width="100%" class="img-responsive" style="height:640px;"/>
													  </div>
													<?php
															} else {
													?>
													  <div class="item">
														<img src="<?= base_url(). $dir . $a_img[$x]; ?>" width="100%" class="img-responsive" style="height:640px;"/>
													  </div>
													<?php
															}
														}
													?>
													</div>

													<!-- Left and right controls -->
													<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev" style="padding-top:70%;">
													  <span class="fa fa-angle-left fa-3x" aria-hidden="true"></span>
													  <span class="sr-only">Previous</span>
													</a>
													<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next" style="padding-top:70%;">
													  <span class="fa fa-angle-right fa-3x" aria-hidden="true"></span>
													  <span class="sr-only">Next</span>
													</a>
												  </div>
												</div>
											</div>
                                        </div>
										<?php } else { ?>
										<div id="item-list">
                                            <?php echo $products; ?>
                                        </div>
										<?php } ?>
                                        <div class="btn-group btn-group-justified" style="z-index:1000;">
                                            <div class="btn-group" >
                                                <button style="z-index:10002;" class="btn btn-primary pos-tip" title="<?= lang('previous') ?>" type="button" id="previous">
                                                    <i class="fa fa-chevron-left"></i>
                                                </button>
                                            </div>
                                            <?php if ($Owner || $Admin || $GP['sales-add_gift_card']) { ?>
                                            <div class="btn-group">
                                                <button style="z-index:10003;" class="btn btn-primary pos-tip" type="button" id="sellGiftCard" title="<?= lang('sell_gift_card') ?>">
                                                    <i class="fa fa-credit-card" id="addIcon"></i> <?= lang('sell_gift_card') ?>
                                                </button>
                                            </div>
                                            <?php } ?>
                                            <div class="btn-group">
                                                <button style="z-index:10004;" class="btn btn-primary pos-tip" title="<?= lang('next') ?>" type="button" id="next">
                                                    <i class="fa fa-chevron-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                            </div>
                        </div>

                        <div style="clear:both;"></div>
                    </div>
                    <div style="clear:both;"></div>
					<?php } elseif ($layout == 6){?>
						<div id="centercp">
							<div id="cpinner" style="padding:0;">
								<div class="quick-menu">
									<div id="proContainer">
										<div id="ajaxproducts">
											<?php if($layout == 1) { ?>
											<div id="item-list">
												<div class="slide_item text-center" id="slide_item">
													<?php
														$totimg = 0;
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
																closedir($dh);
															}
														}
													?>
													<div class="">
													  <div id="myCarousel" class="carousel slide col-xs-12 col-md-12 col-lg-12" data-ride="carousel" style="padding-left:0;padding-right:0;height=70%;">
														<!-- Indicators -->
														<ol class="carousel-indicators">
														<?php
														for($x=0; $x < $totimg; $x++){
															if($x == 0) {
														?>
																<li data-target="#myCarousel" data-slide-to="<?= $x; ?>" class="active"></li>
														<?php
															} else {
														?>
																<li data-target="#myCarousel" data-slide-to="<?= $x; ?>"></li>
														<?php
															}
														}
														?>
														</ol>

														<!-- Wrapper for slides -->
														<div class="carousel-inner" role="listbox" >
														<?php
															for($x=0; $x < $totimg; $x++){
																if($x == 0) {
														?>
														  <div class="item active">
															<img src="<?= base_url(). $dir . $a_img[$x]; ?>" width="100%" class="img-responsive" style="height:640px;"/>
														  </div>
														<?php
																} else {
														?>
														  <div class="item">
															<img src="<?= base_url(). $dir . $a_img[$x]; ?>" width="100%" class="img-responsive" style="height:640px;"/>
														  </div>
														<?php
																}
															}
														?>
														</div>

														<!-- Left and right controls -->
														<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev" style="padding-top:70%;">
														  <span class="fa fa-angle-left fa-3x" aria-hidden="true"></span>
														  <span class="sr-only">Previous</span>
														</a>
														<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next" style="padding-top:70%;">
														  <span class="fa fa-angle-right fa-3x" aria-hidden="true"></span>
														  <span class="sr-only">Next</span>
														</a>
													  </div>
													</div>
												</div>
											</div>
											<?php } else { ?>
											<div id="item-list">
												<?php echo $products; ?>
											</div>
											<?php } ?>
                                            <div class="btn-group btn-group-justified" style="z-index:1000;">
												<div class="btn-group" >
													<button style="z-index:10002;" class="btn btn-primary pos-tip" title="<?= lang('previous') ?>" type="button" id="previous">
														<i class="fa fa-chevron-left"></i>
													</button>
												</div>
												<?php if ($Owner || $Admin || $GP['sales-add_gift_card']) { ?>
												<div class="btn-group">
													<button style="z-index:10003;" class="btn btn-primary pos-tip" type="button" id="sellGiftCard" title="<?= lang('sell_gift_card') ?>">
														<i class="fa fa-credit-card" id="addIcon"></i> <?= lang('sell_gift_card') ?>
													</button>
												</div>
												<?php } ?>
												<div class="btn-group">
													<button style="z-index:10004;" class="btn btn-primary pos-tip" title="<?= lang('next') ?>" type="button" id="next">
														<i class="fa fa-chevron-right"></i>
													</button>
												</div>
											</div>
										</div>
										<div style="clear:both;"></div>
									</div>
								</div>
							</div>

							<div style="clear:both;"></div>
						</div>
						<div style="clear:both;"></div>
					<?php } ?>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
    </div>
</div>
<?php if ($layout != 2 && $layout != 3) {?>
<?php if ($layout == 5) { ?>
<div class="rotate btn-cat-con-layout5">
<?php } else { ?>
<div class="rotate btn-cat-con">
<?php } ?>
	<?php if($pos_settings->show_suspend_bar){
		$this->db->select('name');
		$this->db->from('erp_suspended');
        $this->db->where('id', isset($arrSuspend[$sid]['suspend_not']));
		$q = $this->db->get();
		$namef = '';
		if ($q->num_rows() > 0) {
			foreach ($q->result() as $frow) {
				$namef = $frow->name;
			}
		}
	?>
    <button type="button" id="suspend" class="btn btn-danger open-suspend <?php echo ($layout == 6?'font6':'')?>"><?= lang('suspend') ?>&nbsp;</button>
	<?php } ?>
    <button type="button" id="open-subcategory" class="btn btn-warning open-subcategory <?php echo ($layout == 6?'font6':'')?>"><?= lang('subcategories') ?></button>
    <button type="button" id="open-category" class="btn btn-primary open-category <?php echo ($layout == 6?'font6':'')?>" ><?= lang('categories') ?></button>
</div>

<div id="suspend-slider" style="top:10%;width:63%; overflow:hidden;z-index:10;">
    <div id="suspend-list" style="overflow:hidden;">
		<?php
			if ($this->Owner || $this->Admin){
				$this->db->distinct();
				$this->db->select('*');
				$this->db->from('erp_suspended');
				$this->db->group_by('floor');
				$q = $this->db->get();
				$i=1;
				
				$class  = '';
				$width  = $this->Settings->twidth;
				$height = $this->Settings->theight;
				$font   = '';
				$room   = '';
				if ($layout == 6) {
					$class  = 'room6';
					$width  = '150';
					$height = '150';
					$font   = 'font-size:20px;';
					$room   = 'room_size';
				}

				if ($q->num_rows() > 0) {
					foreach ($q->result() as $row) {
						echo '<h3>'.$row->floor.'</h3>';
						$this->db->select('erp_suspended_bills.id as ids, floor, erp_suspended.name, customer, suspend_id, count, total, erp_suspended.id as sid, erp_suspended_bills.date, erp_suspended.startdate as sus_start, erp_suspended.enddate as sus_end, erp_suspended.note as sus_note, erp_companies.name as com_name, erp_suspended.inactive as inactive');
						$this->db->from('erp_suspended');
						$this->db->join('erp_suspended_bills', 'erp_suspended_bills.suspend_id = erp_suspended.id', 'left');
						$this->db->join('erp_companies', 'erp_companies.id = erp_suspended.customer_id', 'left');
						$this->db->where('floor', $row->floor);
						$this->db->order_by('name', 'asc');
						$query = $this->db->get();

						$this->db->select('*');
						$this->db->from('erp_suspended');
						$this->db->join('erp_suspended_bills', 'erp_suspended_bills.suspend_id = erp_suspended.id');
						$data = $this->db->get();
						if($data->num_rows() > 0){
							$j=0;
							foreach($data->result() as $data_){
								$j++;
								$fname[] = $data_->name;
								$floor[] = $data_->floor;

							}
						}else{
							$fname[] = '';
							$floor[] = '';
						}

						if ($query->num_rows() > 0) {
							foreach ($query->result() as $suspend) {
								$suspens = 'suspend';
								if(strlen($suspend->customer)>10){
									$cust = substr($suspend->customer,0,10);
								}else{
									$cust = $suspend->customer;
								}

								$this->db->select('COUNT(suspend_id) AS ord');
								$this->db->from('suspended_items');
								$this->db->where(array('suspend_id' => $suspend->ids));
								$sus = $this->db->get();
								$count = 0;
								if ($sus->num_rows() > 0) {
									foreach($sus->result() as $susp){
										$count = $susp->ord;
									}
								}

								if(in_array($suspend->floor,$floor) and in_array($suspend->name,$fname)){
									$default=date("H:i",strtotime($suspend->date));$currenttime=date("H:i");
									if($pos_settings->show_suspend_bar == 40){
										if($suspend->inactive == 1){
										}else{
											echo "<span class='".$class."' style='position:relative;display: inline-table;'>
												<a id='clear_suspend' hrefs='".base_url()."pos/delete_suspend/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:-5%;left:0;padding-left:5%;padding-right:5%;cursor:pointer;' class='btn-danger clear_suspend'><i class='fa fa-times'></i></a>
												
												<a href='".base_url()."pos/seperate/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:-5%;right:5%;cursor:pointer;padding-left:5%;padding-right:5%;' class='btn-primary' data-toggle='modal' data-target='#myModal'><i class='fa fa-hourglass-half'></i></a>";

												if($count > 0){
													echo "<p style='text-decoration:none;position:absolute;top:0;left:0;padding:5px;' class='btn-warning clear_suspend'>".$count."</p>";
												}
												
												echo "<input type='checkbox' name='chsuspend' class='chsuspend' value='". $suspend->ids ."' style='position:absolute;right:5px;'/>
												<button style='".$font."' type=\"button\" value='" . $suspend->suspend_id . "' ".($suspens === "suspend" ? 'id="'.$suspend->ids.'"' : '' )." class='".($suspens === "suspend" ? 'btn-prni btn '.($suspend->sus_start == '0000-00-00 00:00:00'? 'btn-info': ($suspend->sus_start == ''? 'btn-info': 'btn-warning')).' sus_sale '.$room : 'btn-prni btn suspend-button' )."' >
													<span class='wrap_suspend".($suspens === "suspend" ? $suspend->ids : '')."'>" . ($suspens === "suspend" ? "<p class='suspend-name".$suspend->ids."'>" . $suspend->name . "</p><div class='sup_number".$suspend->ids."'>" . ($suspend->com_name == "" ? $cust : $suspend->com_name) . "</div><br/>" . $suspend->total : "Number " . $i ) . " (" . $suspend->count . ")</span>
													".$default." (".kpTime($default,$currenttime).")
												</button>
											 </span>";
										}
									}else{

										echo "<span class='".$class."' style='position:relative;display: inline-table;'>
											<a id='clear_suspend' hrefs='".base_url()."pos/delete_suspend/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:-5%;left:0;padding-left:5%;padding-right:5%;cursor:pointer;' class='btn-danger clear_suspend'><i class='fa fa-times'></i></a><a  href='".base_url()."pos/seperate/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:-5%;right:5%;cursor:pointer;padding-left:5%;padding-right:5%;' class='btn-primary' data-toggle='modal' data-target='#myModal'><i class='fa fa-hourglass-half'></i></a>";
											if($count > 0){
												echo "<p style='text-decoration:none;position:absolute;top:0;left:0;padding:5px;' class='btn-warning clear_suspend'>".$count."</p>";
											}
											echo "
											<input type='checkbox' name='chsuspend' class='chsuspend checkbox' value='". $suspend->ids ."' style='position:absolute;top:0;right:5px;'/>
											<button style='".$font."' type=\"button\" value='" . $suspend->suspend_id . "' ".($suspens === "suspend" ? 'id="'.$suspend->ids.'"' : '' )." class='".($suspens === "suspend" ? 'btn-prni btn '.($suspend->sus_start == '0000-00-00 00:00:00'? 'btn-info': ($suspend->sus_start == ''? 'btn-info': 'btn-warning')).' sus_sale '.$room : 'btn-prni btn suspend-button' )."' >
												<span class='wrap_suspend".($suspens === "suspend" ? $suspend->ids : '')."'>" . ($suspens === "suspend" ? "<p class='suspend-name".$suspend->ids."'>" . $suspend->name . "</p><p class='suspend-date".$suspend->ids."'>" . $suspend->date . "</p><div class='sup_number".$suspend->ids."'>" . ($suspend->com_name == "" ? $cust : $suspend->com_name) . "</div><br/>" . $suspend->total : "Number " . $i ) . " (" . $suspend->count . ")</span>
												".$default." (".kpTime($default,$currenttime).")
											</button>
										 </span>";
									}
								}elseif($suspend->sus_start <= date('Y-m-d H:i:s') and $suspend->sus_end >= date('Y-m-d H:i:s')){
										$default=date("H:i",strtotime($suspend->sus_start));$currenttime=date("H:i");
										echo "<span class='".$class."' style='position:relative;display: inline-table;'>
												<a id='clear_suspend' hrefs='".base_url()."pos/delete_suspend/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:-5%;left:0;padding-left:5%;padding-right:5%;cursor:pointer;' class='btn-danger clear_suspend'><i class='fa fa-times'></i></a><a href='".base_url()."pos/seperate/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:-5%;right:5%;cursor:pointer;padding-left:5%;padding-right:5%;' class='btn-primary' data-toggle='modal' data-target='#myModal'><i class='fa fa-hourglass-half'></i></a>";

												if($count > 0){
													echo "<p style='text-decoration:none;position:absolute;top:0;left:0;padding:5px;' class='btn-warning clear_suspend'>".$count."</p>";
												}

												echo "<input type='checkbox' name='chsuspend' class='chsuspend' value='". $suspend->ids ."' style='position:absolute;right:5px;'/>
												<button style='".$font."' type=\"button\" value='" . $suspend->sid . "' class='btn-prni btn btn-warning suspend-button' id='". $suspend->name ."' >
													<span class='wrap_suspend".($suspens === "suspend" ? $suspend->ids : '')."'>" . ($suspens === "suspend" ? "<p class='suspend-name".$suspend->ids."'>" . $suspend->name . "</p><div class='sup_number".$suspend->ids."'>" . ($suspend->com_name == "" ? $suspend->sus_note : $suspend->com_name) . "</div><br/>" . $suspend->total : "Number " . $i ) . " (" . $suspend->count . ")</span>
													".$default." (".kpTime($default,$currenttime).")
												</button>
											 </span>";
								}else{
									echo "<span>
											<button type=\"button\" value='" . $suspend->sid . "' class='btn-prni btn suspend-button ".$class." ' id='". $suspend->name ."' >
												<span style='".$font."' >" . $suspend->name . "</span>
											</button>
										 </span>";
								}
								$i++;
							}
						}
					}
				}
			}else{

				$warehouses = explode(',',$this->session->userdata('warehouse_id'));
				$this->db->distinct();
				$this->db->select('*');
				$this->db->where_in('warehouse_id',$warehouses);
				$this->db->from('erp_suspended');
				$this->db->group_by('floor');
				$q = $this->db->get();
				$i=1;

				if ($q->num_rows() > 0) {
					foreach ($q->result() as $row) {
						echo '<h3>'.$row->floor.'</h3>';
						$this->db->select('erp_suspended_bills.id as ids, floor, erp_suspended.name, customer, suspend_id, count, total, erp_suspended.id as sid, erp_suspended_bills.date, erp_suspended.startdate as sus_start, erp_suspended.enddate as sus_end, erp_suspended.note as sus_note, erp_companies.name as com_name, erp_suspended.inactive as inactive');
						$this->db->from('erp_suspended');
						$this->db->join('erp_suspended_bills', 'erp_suspended_bills.suspend_id = erp_suspended.id', 'left');
						$this->db->join('erp_companies', 'erp_companies.id = erp_suspended.customer_id', 'left');
						$this->db->where('floor', $row->floor);
						$this->db->where_in('erp_suspended.warehouse_id',$warehouses);
						$this->db->order_by('name', 'asc');
						$query = $this->db->get();

						$this->db->select('*');
						$this->db->from('erp_suspended');
						$this->db->join('erp_suspended_bills', 'erp_suspended_bills.suspend_id = erp_suspended.id');

						$data = $this->db->get();
						if($data->num_rows() > 0){
							$j=0;
							foreach($data->result() as $data_){
								$j++;
								$fname[] = $data_->name;
								$floor[] = $data_->floor;
							}
						}else{
							$fname[] = '';
							$floor[] = '';
						}

						if ($query->num_rows() > 0) {
							foreach ($query->result() as $suspend) {
								$suspens = 'suspend';
								if(strlen($suspend->customer)>10){
									$cust = substr($suspend->customer,0,10);
								}else{
									$cust = $suspend->customer;
								}

								$this->db->select('COUNT(suspend_id) AS ord');
								$this->db->from('suspended_items');
								$this->db->where(array('suspend_id' => $suspend->ids));
								$sus = $this->db->get();
								$count = 0;
								if ($sus->num_rows() > 0) {
									foreach($sus->result() as $susp){
										$count = $susp->ord;
									}
								}

								if(in_array($suspend->floor,$floor) and in_array($suspend->name,$fname)){
									$default=date("H:i",strtotime($suspend->date));$currenttime=date("H:i");
									if($pos_settings->show_suspend_bar == 40){
										if($suspend->inactive == 1){
										}else{
											echo "<span style='position:relative;display: inline-table;'>
												<a id='clear_suspend' hrefs='".base_url()."pos/delete_suspend/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:-5%;left:0;padding-left:5%;padding-right:5%;cursor:pointer;' class='btn-danger clear_suspend'><i class='fa fa-times'></i></a><a href='".base_url()."pos/seperate/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:-5%;right:5%;cursor:pointer;padding-left:5%;padding-right:5%;' class='btn-primary' data-toggle='modal' data-target='#myModal'><i class='fa fa-hourglass-half'></i></a>";

												if($count > 0){
													echo "<p style='text-decoration:none;position:absolute;top:0;left:0;padding:5px;' class='btn-warning clear_suspend'>".$count."</p>";
												}
												echo "<input type='checkbox' name='chsuspend' class='chsuspend' value='". $suspend->ids ."' style='position:absolute;right:5px;'/>
												<button type=\"button\" value='" . $suspend->suspend_id . "' ".($suspens === "suspend" ? 'id="'.$suspend->ids.'"' : '' )." class='".($suspens === "suspend" ? 'btn-prni btn '.($suspend->sus_start == '0000-00-00 00:00:00'? 'btn-info': ($suspend->sus_start == ''? 'btn-info': 'btn-warning')).' sus_sale' : 'btn-prni btn suspend-button' )."' >
													<span class='wrap_suspend".($suspens === "suspend" ? $suspend->ids : '')."'>" . ($suspens === "suspend" ? "<p class='suspend-name".$suspend->ids."'>" . $suspend->name . "</p><div class='sup_number".$suspend->ids."'>" . ($suspend->com_name == "" ? $cust : $suspend->com_name) . "</div><br/>" . $suspend->total : "Number " . $i ) . " (" . $suspend->count . ")</span>
													".$default." (".kpTime($default,$currenttime).")
												</button>
											 </span>";
										}
									}else{

										echo "<span style='position:relative;display: inline-table;'>
											<a id='clear_suspend' hrefs='".base_url()."pos/delete_suspend/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:-5%;left:0;padding-left:5%;padding-right:5%;cursor:pointer;' class='btn-danger clear_suspend'><i class='fa fa-times'></i></a><a  href='".base_url()."pos/seperate/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:-5%;right:5%;cursor:pointer;padding-left:5%;padding-right:5%;' class='btn-primary' data-toggle='modal' data-target='#myModal'><i class='fa fa-hourglass-half'></i></a>";
											if($count > 0){
												echo "<p style='text-decoration:none;position:absolute;top:0;left:0;padding:5px;' class='btn-warning clear_suspend'>".$count."</p>";
											}
											echo "
											<input type='checkbox' name='chsuspend' class='chsuspend checkbox' value='". $suspend->ids ."' style='position:absolute;top:0;right:5px;'/>
											<button type=\"button\" value='" . $suspend->suspend_id . "' ".($suspens === "suspend" ? 'id="'.$suspend->ids.'"' : '' )." class='".($suspens === "suspend" ? 'btn-prni btn '.($suspend->sus_start == '0000-00-00 00:00:00'? 'btn-info': ($suspend->sus_start == ''? 'btn-info': 'btn-warning')).' sus_sale' : 'btn-prni btn suspend-button' )."' >
												<span class='wrap_suspend".($suspens === "suspend" ? $suspend->ids : '')."'>" . ($suspens === "suspend" ? "<p class='suspend-name".$suspend->ids."'>" . $suspend->name . "</p><div class='sup_number".$suspend->ids."'>" . ($suspend->com_name == "" ? $cust : $suspend->com_name) . "</div><br/>" . $suspend->total : "Number " . $i ) . " (" . $suspend->count . ")</span>
												".$default." (".kpTime($default,$currenttime).")
											</button>
										 </span>";
									}
								}elseif($suspend->sus_start <= date('Y-m-d H:i:s') and $suspend->sus_end >= date('Y-m-d H:i:s')){
										$default=date("H:i",strtotime($suspend->sus_start));$currenttime=date("H:i");
										echo "<span style='position:relative;display: inline-table;'>
												<a id='clear_suspend' hrefs='".base_url()."pos/delete_suspend/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:-5%;left:0;padding-left:5%;padding-right:5%;cursor:pointer;' class='btn-danger clear_suspend'><i class='fa fa-times'></i></a><a href='".base_url()."pos/seperate/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:-5%;right:5%;cursor:pointer;padding-left:5%;padding-right:5%;' class='btn-primary' data-toggle='modal' data-target='#myModal'><i class='fa fa-hourglass-half'></i></a>";

												if($count > 0){
													echo "<p style='text-decoration:none;position:absolute;top:0;left:0;padding:5px;' class='btn-warning clear_suspend'>".$count."</p>";
												}

												echo "<input type='checkbox' name='chsuspend' class='chsuspend' value='". $suspend->ids ."' style='position:absolute;right:5px;'/>
												<button type=\"button\" value='" . $suspend->sid . "' class='btn-prni btn btn-warning suspend-button' id='". $suspend->name ."' >
													<span class='wrap_suspend".($suspens === "suspend" ? $suspend->ids : '')."'>" . ($suspens === "suspend" ? "<p class='suspend-name".$suspend->ids."'>" . $suspend->name . "</p><div class='sup_number".$suspend->ids."'>" . ($suspend->com_name == "" ? $suspend->sus_note : $suspend->com_name) . "</div><br/>" . $suspend->total : "Number " . $i ) . " (" . $suspend->count . ")</span>
													".$default." (".kpTime($default,$currenttime).")
												</button>
											 </span>";
								}else{
									echo "<span>
											<button type=\"button\" value='" . $suspend->sid . "' class='btn-prni btn suspend-button' id='". $suspend->name ."' >
												<span>" . $suspend->name . "</span>
											</button>
										 </span>";
								}
								$i++;
							}
						}
					}
				}
			}

		?>

	</div>
	<div style="position:relative;">
		<button class="btn btn-primary combine_table">Apply</button>
	</div>
</div>
<div id="category-slider">
    <!--<button type="button" class="close open-category"><i class="fa fa-2x">&times;</i></button>-->
    <div id="category-list">
        <?php
        //for ($i = 1; $i <= 40; $i++) {
			$class  = '';
			$width  = $this->Settings->twidth;
			$height = $this->Settings->theight;
			$font   = '';
			if ($layout == 6) {
				$class  = 'btn6';
				$width  = '150';
				$height = '150';
				$font   = 'font-size:20px;';
			}
			if(($this->Owner || $this->Admin)){
				foreach ($categories as $category) {
					echo "<button id=\"category-" . $category->id . "\" type=\"button\" value='" . $category->id . "' class=\"btn-prni category ".$class." \" ><img src=\"assets/uploads/thumbs/" . ($category->image ? $category->image : 'no_image.png') . "\" style='width:" . $width . "px;height:" . $height . "px;' class='img-rounded img-thumbnail' /><span style='".$font."'>" . $category->name . "</span></button>";  
				}
			}else{
				foreach ($categories as $category) {
					$cat = $user_settings->sales_category;
					$cate = explode(',', $cat);
					if(in_array($category->id, $cate)){

					}else{
						echo "<button id=\"category-" . $category->id . "\" type=\"button\" value='" . $category->id . "' class=\"btn-prni category ".$class." \" ><img src=\"assets/uploads/thumbs/" . ($category->image ? $category->image : 'no_image.png') . "\" style='width:" . $width . "px;height:" . $height . "px;' class='img-rounded img-thumbnail' /><span style='".$font."'>" . $category->name . "</span></button>";
					}
				}
			}
        
        //}
        ?>
    </div>
</div>
<div id="subcategory-slider">
    <!--<button type="button" class="close open-category"><i class="fa fa-2x">&times;</i></button>-->
    <div id="subcategory-list">
        <?php
        if (!empty($subcategories)) {
			$class  = '';
			$width  = $this->Settings->twidth;
			$height = $this->Settings->theight;
			$font   = '';
			if ($layout == 6) {
				$class  = 'btn6';
				$width  = '150';
				$height = '150';
				$font   = 'font-size:20px;';
			}
            foreach ($subcategories as $category) {
                echo "<button id=\"subcategory-" . $category->id . "\" type=\"button\" value='" . $category->id . "' class=\"btn-prni subcategory ".$class." \" ><img src=\"assets/uploads/thumbs/" . ($category->image ? $category->image : 'no_image.png') . "\" style='width:" . $width . "px;height:" . $height . "px;' class='img-rounded img-thumbnail' /><span style='".$font."'>" . $category->name . "</span></button>";
            }
        }
        ?>
    </div>
</div>
<?php } else {?>

<div id="suspend-slider" style="top:10%;width:63%; overflow:hidden;z-index:10;">
    <div id="suspend-list" style="overflow:hidden;">
		<?php
			$this->db->distinct();
			$this->db->select('*');
			$this->db->from('erp_suspended');
			$this->db->group_by('floor');
			$q = $this->db->get();
			$i=1;
			$class  = '';
			$width  = $this->Settings->twidth;
			$height = $this->Settings->theight;
			$font   = '';
			if ($layout == 6) {
				$class  = 'btn6';
				$width  = '150';
				$height = '150';
				$font   = 'font-size:20px;';
			}
			if ($q->num_rows() > 0) {
				foreach ($q->result() as $row) {
					echo '<h3>'.$row->floor.'</h3>';
					$this->db->select('erp_suspended_bills.id as ids, floor, erp_suspended.name, customer, suspend_id, count, total, erp_suspended.id as sid, erp_suspended_bills.date, erp_suspended.startdate as sus_start, erp_suspended.enddate as sus_end, erp_suspended.note as sus_note, erp_companies.name as com_name');
					$this->db->from('erp_suspended');
					$this->db->join('erp_suspended_bills', 'erp_suspended_bills.suspend_id = erp_suspended.id', 'left');
					$this->db->join('erp_companies', 'companies.id=suspended.customer_id', 'left');
					$this->db->order_by('name', 'ASC');
					$this->db->where('floor', $row->floor);
					$query = $this->db->get();
					$this->db->select('*');
					$this->db->from('erp_suspended');
					$this->db->join('erp_suspended_bills', 'erp_suspended_bills.suspend_id = erp_suspended.id');
					$data = $this->db->get();
					if ($data->num_rows() > 0) {
						$j=0;
						foreach($data->result() as $data_){
							$j++;
							$fname[] = $data_->name;
							$floor[] = $data_->floor;
						}
					} else {
						$fname[] = '';
						$floor[] = '';
					}
					if ($query->num_rows() > 0){
						foreach ($query->result() as $suspend) {
							$suspens = 'suspend';
							if (strlen($suspend->customer)>10) {
								$cust = substr($suspend->customer,0,10);
							} else {
								$cust = $suspend->customer;
							}
						
		if(in_array($suspend->floor,$floor) and in_array($suspend->name,$fname)){
			$default=date("H:i",strtotime($suspend->date));$currenttime=date("H:i");
			if($pos_settings->show_suspend_bar == 40){
				echo "<span style='position:relative;display: inline-table;'>
				<a id='clear_suspend' hrefs='".base_url()."pos/delete_suspend/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:5%;left:0;' class='btn-danger clear_suspend'>clear</a>
				<button type=\"button\" value='" . $suspend->suspend_id . "' ".($suspens === "suspend" ? 'id="'.$suspend->ids.'"' : '' )." class='".($suspens === "suspend" ? 'btn-prni btn '.($suspend->sus_start == ''? 'btn-info': ($suspend->sus_start == '0000-00-00 00:00:00'? 'btn-info': 'btn-warning')).' sus_sale' : 'btn-prni btn suspend-button' )."' >
					<span class='wrap_suspend".($suspens === "suspend" ? $suspend->ids : '')."'>" . ($suspens === "suspend" ? "<p class='suspend-name".$suspend->ids."'>" . $suspend->name . "</p><div class='sup_number".$suspend->ids."'>" . ($suspend->com_name == "" ? $cust : $suspend->com_name) . "</div><br/>" . $suspend->total : "Number " . $i ) . " (" . $suspend->count . ")</span>
					".$default." (".kpTime($default,$currenttime).")</button></span>";
			}else{
				echo "<span style='position:relative;display: inline-table;'>
				<a id='clear_suspend' hrefs='".base_url()."pos/delete_suspend/".$suspend->ids."' style='text-decoration:none;position:absolute;bottom:5%;left:0;' class='btn-danger clear_suspend'>clear</a>
				<button type=\"button\" value='" . $suspend->suspend_id . "' ".($suspens === "suspend" ? 'id="'.$suspend->ids.'"' : '' )." class='".($suspens === "suspend" ? 'btn-prni btn '.($suspend->sus_start == ''? 'btn-info': ($suspend->sus_start == '0000-00-00 00:00:00'? 'btn-info': 'btn-warning')).' sus_sale' : 'btn-prni btn suspend-button' )."' >
					<span class='wrap_suspend".($suspens === "suspend" ? $suspend->ids : '')."'>" . ($suspens === "suspend" ? "<p class='suspend-name".$suspend->ids."'>" . $suspend->name . "</p><div class='sup_number".$suspend->ids."'>" . ($suspend->com_name == "" ? $cust : $suspend->com_name) . "</div><br/>" . $suspend->total : "Number " . $i ) . " (" . $suspend->count . ")</span>
					".$default." (".kpTime($default,$currenttime).")</button></span>";

			}
		}elseif($suspend->sus_start <= date('Y-m-d H:i:s') and $suspend->sus_end >= date('Y-m-d H:i:s')){
			$default=date("H:i",strtotime($suspend->sus_start));$currenttime=date("H:i");
			echo "<span style='position:relative;display: inline-table;'>
			<a id='clear_suspend' hrefs='".base_url()."pos/delete_room/".$suspend->sid."' style='text-decoration:none;position:absolute;bottom:5%;left:0;' class='btn-danger clear_suspend'>clear</a>
				<button type=\"button\" value='" . $suspend->sid . "' class='btn-prni btn btn-warning suspend-button' id='". $suspend->name ."' >
					<span class='wrap_suspend".($suspens === "suspend" ? $suspend->ids : '')."'>" . ($suspens === "suspend" ? "<p class='suspend-name".$suspend->ids."'>" . $suspend->name . "</p><div class='sup_number".$suspend->ids."'>" . ($suspend->com_name == "" ? $suspend->sus_note : $suspend->com_name) . "</div><br/>" . $suspend->total : "Number " . $i ) . " (" . $suspend->count . ")</span>
					".$default." (".kpTime($default,$currenttime).")
				</button>
			</span>";
		}else{
			echo "<span><button type=\"button\" value='" . $suspend->sid . "' class='btn-prni btn suspend-button' id='". $suspend->name ."' >
				<span>" . $suspend->name . "</span>
			</button></span>";
		}
						$i++;
						}
					}
				}
			}
		?>
	</div>
</div>

<?php } ?>

<div class="modal fade in" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="payModalLabel"><?= lang('finalize_sale'); ?></h4>
            </div>
            <div class="modal-body" id="payment_content">
                <div class="row">
                    <div class="col-md-10 col-sm-9">
                        <?php if ($Owner || $Admin || !$this->session->userdata('biller_id')) { ?>
                            <div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<?= lang("biller", "biller"); ?>
										<?php
										foreach ($billers as $biller1) {
											$bl[$biller1->id] = $biller1->company != '-' ? $biller1->company : $biller1->name;
										}
										echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $this->site->get_setting()->default_biller), 'class="form-control" id="posbiller" required="required"');
										?>
									</div>
									<div class="col-sm-6">
										<?= lang("date", "date"); ?> (yyyy-mm-dd)
										<?php echo form_input('date', "", 'class="form-control input-tip datetime" id="date"'); ?>
									</div>
								</div>
                            </div>
                        <?php } else { ?>
                            <div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<?= lang("biller", "biller"); ?>
										<?php
										foreach ($billers as $biller1) {
											$bl[$biller1->id] = $biller1->company != '-' ? $biller1->company : $biller1->name;
										}
										echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ''), 'class="form-control" id="posbiller" required="required"');
										?>
									</div>
									<div class="col-sm-6">
										<?= lang("date", "date"); ?> (yyyy-mm-dd)
										<?php echo form_input('date', "", 'class="form-control input-tip datetime" id="date"'); ?>
									</div>
								</div>
                            </div>
                        <?php } ?>

                        <div class="form-group">
                            <div class="row">
									<div class="col-sm-6">
										<?= form_textarea('sale_note', '', 'id="sale_note" class="form-control kb-text skip" style="height: 35px;" placeholder="' . lang('sale_note') . '" maxlength="250"'); ?>
									</div>

								<?php if(isset($suppend_name)) {  ?>

									<div class="col-sm-6">
										<?php
											echo form_dropdown('suspend_room', $suppend_name, "", 'id="suspend_room" placeholder="'.lang('suspend').'" disabled class="form-control pos-input-tip" style="width:100%;"');
										?>
									</div>

								<?php } else{ ?>

									<div class="col-sm-6">
										<?php
											//form_textarea('staffnote', '', 'id="staffnote" class="form-control kb-text skip" style="height: 50px;" placeholder="' . lang('staff_note') . '" maxlength="250"');
											if($suspend_sale){
												$suspend_room[""] = "";
												foreach ($room as $rooms) {
													$suspend_room[$rooms->name] = $rooms->name;
												}
												echo form_dropdown('suspend_room', $suspend_room, $suspend_sale->suspend_name, 'id="suspend_room" placeholder="'.lang('suspend').'" disabled class="form-control pos-input-tip" style="width:100%;"');
											}else{
												$suspend_room[""] = "";
												if (is_array($room)) {
													foreach ($room as $rooms) {
														$suspend_room[$rooms->name] = $rooms->name;
													}
												}
												echo form_dropdown('suspend_room', $suspend_room, "", 'id="suspend_room" placeholder="'.lang('suspend').'" class="form-control pos-input-tip" style="width:100%;"');
											}
										?>
									</div>
								<?php } ?>
                            </div>
                        </div>
						<div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <?= lang("saleman", "saleman"); ?>
									<select name="saleman[]" id="saleman" class="form-control saleman">
									<?php
										foreach($agencies as $agency){
											if($this->session->userdata('username') == $agency->username){
												echo '<option value="'.$this->session->userdata('user_id').'" selected>'.lang($this->session->userdata('username')).'</option>';
											}else{
												echo '<option value="'.$agency->id.'">'.$agency->username.'</option>';
											}
										}
									?>
									</select>
                                </div>

                                <div class="col-sm-6">
									<?= lang("delivery_by", "delivery_by"); ?>
									<select name="delivery_by[]" id="delivery_by" class="form-control delivery_by">
										<?php
											foreach($drivers as $driver){
												echo '<option value="'.$driver->id.'" selected>'.$driver->name.'</option>';
											}
										?>
									</select>
                                </div>

								<div class="col-sm-6">
									<div class="form-group">
										<?= lang("sale_status", "sale_status"); ?>
										<?php $sst = array('completed' => lang('completed'), 'pending' => lang('pending'), 'ordered' => lang('ordered'));
										echo form_dropdown('sale_status', $sst, '', 'class="form-control input-tip" required="required" id="sale_status"'); ?>
									</div>
								</div>

								<div class="col-sm-6">
									<?= lang("reference_no", "slref"); ?>
                                    <div class="form-group">
                                        <div class="input-group">
                                                <?php echo form_input('reference_no', $reference ? $reference : "",'  class="form-control input-tip" id="slref"'); ?>
                                            <input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference ? $reference : "" ?>" />
                                            <div class="input-group-addon no-print" style="padding: 2px 5px;background-color:white;">
                                                <input type="checkbox" name="ref_status" id="ref_st" value="1" style="margin-top:3px;">
                                            </div>
                                        </div>
                                    </div>
								</div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?= lang("document", "document") ?>
                                        <input id="document" type="file" name="document" data-show-upload="false"
                                               data-show-preview="false" class="form-control file">
                                        <input type="hidden"
                                               value="<?= (isset($sale_order->attachment) ? $sale_order->attachment : "") ?>"
                                               name="attachment">
                                        <?php if (!empty($sale_order->attachment)) { ?>
                                            <a target="_blank"
                                               href="<?php echo $this->config->base_url() ?>files/<?= $sale_order->attachment ?>"
                                               class="btn btn-primary pull-left"><i class="fa fa-download"></i> Download
                                                File</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

						<div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <!--<?= form_textarea('staffnote', '', 'id="staffnote" class="form-control kb-text skip" style="height: 35px;" placeholder="' . lang('staff_note') . '" maxlength="250"'); ?>-->
                                </div>
                                <div class="col-sm-6">
									<button type="button" class="btn btn-primary col-md-12 addButton">
										<i class="fa fa-plus"></i> <?= lang('add_more_payments') ?>
									</button>
                                </div>
                            </div>
                        </div>
                        <div class="clearfir"></div>
                        <div id="payments">
							<!-- <div class="col-md-12 col-sm-9" style="padding:0;"> -->
							<div style="padding:0;">
								<div class="font16">
									<table class="table table-bordered table-condensed table-striped" style="font-size: 1.2em; font-weight: bold; margin-bottom: 0;">
										<tbody>
											<tr>
												<th width="30%" style="text-align:left;font-size:16px !important;"><?= lang("currency"); ?></th>
												<th  style="text-align:center;font-size:16px !important;"><?= lang("USD"); ?></th>
												<?php
													$this->db->select('*');
													$this->db->from('erp_currencies');
													$this->db->where('code != "USD" and in_out = 1');
													$query = $this->db->get()->result();
													$column = (2 + count($query));
													foreach ($query as $row)
													{
												?>
														<th  style="text-align:center;font-size:16px !important;"><?=$row->code?></th>
												<?php
													}
												?>
											</tr>
											<tr>
												<td width="30%" style="height: 50px;font-size:16px !important;"><?= lang("total_items"); ?></td>
												<td class="text-right" style="font-size:16px !important;"><span id="item_count">0.00</span></td>
												<?php
													foreach ($query as $row)
													{
												?>
														<td class="text-right" style="font-size:16px !important;"><span class="item_count">0.00</span></td>
												<?php
													}
												?>
											</tr>
											<tr>
												<td width="30%" style="height: 50px; font-size:16px !important;"><?= lang("total_payable"); ?></td>
												<td class="text-right" style="font-size:16px !important;"><span id="twt">0.00</span></td>
												<?php
													foreach ($query as $row)
													{
												?>
														<td class="text-right" style="font-size:16px !important;"><span class="curr_tpay" rate="<?=$row->rate?>" id="twt">0.00</span></td>
												<?php
													}
												?>
											</tr>
											<tr>
												<td width="30%" style="height: 50px; font-size:16px !important;"><?= lang("paid_amount"); ?></td>
												<td class="text-right" style="font-size:16px !important;"><input name="amount[]" type="text" id="amount_1" value="" class="pa form-control input-lg kb-pad amount" style="text-align:right;"/></td>
												<?php
													foreach ($query as $row)
													{
												?>
														<td class="text-right" style="font-size:16px !important;">
															<input name="other_cur_paid[]" rate="<?=$row->rate?>" type="text" id="other_cur_paid_1" class="form-control input-lg kb-pad currencies_payment" style="text-align:right;"/>
														</td>
												<?php
													}
												?>
											</tr>

											<tr>
												<td  rowspan="2" width="30%" style="text-align:left;font-size:16px !important;"><?= lang("remaining"); ?></td>
												<td class="text-right" style="font-size:16px !important;"><span id="remain_1" class="main_remain_1 main_remain_">0.00</span></td>
												<?php
													foreach ($query as $row)
													{
												?>
														<td class="text-right" style="font-size:16px !important;"><span class="curr_remain_1 curr_remain_" rate="<?=$row->rate?>" id="remain_1">0</span></td>
												<?php
													}
												?>
											</tr>
											<tr>
												<td class="text-right" style="font-size:16px !important;"><span id="remain" class="main_remain" style="font-size:16px !important;">0.00</span></td>
												<?php
													foreach ($query as $row)
													{
												?>
														<td class="text-right" style="font-size:16px !important;"><span class="curr_remain" rate="<?=$row->rate?>" id="remain">0</span></td>
												<?php
													}
												?>
											</tr>
											<tr>
												<td  rowspan="2" width="30%" style="text-align:left;font-size:16px !important;"><?= lang("change"); ?></td>
												<td class="text-right" style="font-size:16px !important;"><span id="change_1">0.00</span></td>
												<?php
												$this->db->select('*');
												$this->db->from('erp_currencies');
												$this->db->where('code != "USD" and in_out = 1');
												$q = $this->db->get()->result();
												if($pos_settings->in_out_rate == 1){
													foreach ($q as $row)
													{
													?>
														<td class="text-right" style="font-size:16px !important;"><span class="curr_change_1" rate="<?=$row->rate?>" id="change">0</span></td>
													<?php
													}
												}else{
													foreach ($query as $row)
													{
													?>
														<td class="text-right" style="font-size:16px !important;"><span class="curr_change_1" rate="<?=$row->rate?>" id="change_1">0</span></td>
													<?php
													}
												}

												?>
											</tr>
											<tr>
												<td class="text-right" style="font-size:16px !important;"><span id="change">0.00</span></td>
												<?php
												$this->db->select('*');
												$this->db->from('erp_currencies');
												$this->db->where('code != "USD" and in_out = 1');
												$q = $this->db->get()->result();
												if($pos_settings->in_out_rate == 1){
													foreach ($q as $row)
													{
													?>
														<td class="text-right" style="font-size:16px !important;"><span class="curr_change" rate="<?=$row->rate?>" id="change">0</span></td>
													<?php
													}
												}else{
													foreach ($query as $row)
													{
													?>
														<td class="text-right" style="font-size:16px !important;"><span class="curr_change" rate="<?=$row->rate?>" id="change">0</span></td>
													<?php
													}
												}

												?>
											</tr>
										</tbody>
									</table>
									<!--<table class="table table-bordered table-condensed table-striped" style="margin-bottom: 0;">
										<tbody>
										<tr>
											<td width="20%"><?= lang("total_items"); ?></td>
											<td width="20%" class="text-right"><span id="item_count">0.00</span></td>
											<td width="20%"><?= lang("total_payable"); ?></td>
											<td width="20%" class="text-right"><span id="twt">0.00</span></td>
											<td rowspan="2" width="10%">USD</td>
											<td rowspan="2" width="10%"><input class="calExchangerate form-control kb-pad ui-keyboard-input ui-widget-content ui-corner-all" rate="1" class="form-control" type="text" /></td>
										</tr>
										<tr>
											<td><?= lang("total_paying"); ?></td>
											<td class="text-right"><span id="total_paying">0.00</span></td>
											<td><?= lang("balance"); ?></td>
											<td class="text-right"><span id="balance">0.00</span></td>
										</tr>
										<?php
											$this->db->select('*');
                                            $this->db->from('erp_currencies');
                                            $this->db->where('code != "USD"');
                                            $query = $this->db->get()->result();
											foreach ($query as $row)
											{
												?>
												<tr style="border-top:2px solid #999 !important">
													<td width="20%"><?= lang("total_items"); ?></td>
													<td width="20%" class="text-right"><span class="item_count">0.00</span></td>
													<td width="20%"><?= lang("total_payable"); ?></td>
													<td width="20%" class="text-right"><span class="curr_tpay" rate="<?=$row->rate?>" id="twt">0.00</span></td>
													<td rowspan="2" width="10%"><?=$row->code?></td>
													<td rowspan="2" width="10%"><input class="calExchangerate form-control kb-pad ui-keyboard-input ui-widget-content ui-corner-all" rate="<?=$row->rate?>" class="form-control currecies_id" type="text" /></td>
												</tr>
												<tr>
													<td><?= lang("total_paying"); ?></td>
													<td class="text-right"><span class="curr_total_p" rate="<?=$row->rate?>" >0.00</span></td>
													<td><?= lang("balance"); ?></td>
													<td class="text-right"><span class="curr_balance" rate="<?=$row->rate?>" >0.00</span></td>
												</tr>
												<?php
											}
										?>
										</tbody>
									</table>
-->
									<div class="clearfix"></div>
								</div>
							</div>
                            <div class="well well-sm well_1">
                                <div class="payment">
                                    <div class="row" style="font-size: 1.2em; font-weight: bold; margin-bottom: 0;">
									<!--<div class="col-sm-5">
										<div class="form-group">
											<?= lang("amount(USD)", "amount_1"); ?>
											<input name="amount[]" type="text" id="amount_1"
												   class="pa form-control input-lg kb-pad amount"/>
										</div>
										<?php

										foreach($query as $val) {
											?>
											<div class="form-group">
												<?= lang("amount(".$val->code.")", "amount_2"); ?>
												<input name="other_cur_paid" rate="<?=$val->rate?>" type="text" id="other_cur_paid"
													   class="form-control input-lg kb-pad currencies_payment"/>
											</div>
											<?php
										}
										?>
                                    </div>-->
										<div class="col-sm-6">
											<div class="form-group" id="bank_account_fg">
												<?= lang("bank_account", "bank_account_1"); ?><span style="float: right;" id="bank_account_span"></span>
												<?php
													if ($Owner || $Admin) {
														foreach($bankAccounts as $bankAcc) {
															$bank[$bankAcc->accountcode] = $bankAcc->accountcode . ' | '. $bankAcc->accountname;
														}
														echo form_dropdown('bank_account', $bank, '', 'id="bank_account_1" required="required" class="ba form-control kb-pad bank_account"');
													} else {
														foreach($userBankAccounts as $userBankAccount) {
															$ubank[$userBankAccount->accountcode] = $userBankAccount->accountcode . ' | '. $userBankAccount->accountname;
														}
														echo form_dropdown('bank_account', $ubank, '', 'id="bank_account_1" required="required" class="ba form-control kb-pad bank_account"');
													}
												?>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<?= lang("paying_by", "paying_by"); ?>
                                                <select name="paid_by[]" id="paid_by_1" class="form-control paid_by" >
                                                    <option value="cash"><?= lang("cash"); ?></option>
                                                    <option value="CC"><?= lang("cc"); ?></option>
                                                    <option value="Cheque"><?= lang("cheque"); ?></option>
                                                    <option value="gift_card"><?= lang("gift_card"); ?></option>
													<option value="deposit"><?= lang("deposit"); ?></option>
                                                    <?= $pos_settings->paypal_pro ? '<option value="ppp">' . lang("paypal_pro") . '</option>' : ''; ?>
                                                    <?= $pos_settings->stripe ? '<option value="stripe">' . lang("stripe") . '</option>' : ''; ?>
                                                    <option value="depreciation"><?= lang("loan"); ?></option>
                                                    <option value="Voucher"><?= lang("voucher"); ?></option>
													<option value="other"><?= lang("other"); ?></option>
                                                </select>
                                            </div>
										</div>
                                        <div class="col-sm-12">
                                            <textarea name="payment_note[]" id="payment_note_1"  style="height: 60px;" class="pa form-control kb-text payment_note" placeholder="<?php echo lang('payment_note') ?>"></textarea>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group gc_1" style="display: none;">
                                                <?= lang("gift_card_no", "gift_card_no_1"); ?>
                                                <input name="paying_gift_card_no[]" type="text" id="gift_card_no_1"
                                                       class="pa form-control kb-pad gift_card_no"/>

                                                <div id="gc_details_1"></div>
                                            </div>

											<div class="form-group dp_1" style="display: none;">
                                                <?= lang("deposit_amount", "deposit_amount_1"); ?>
                                                <div id="dp_details_1"></div>
                                            </div>

                                            <div class="pcc_1" style="display:none;">
                                                <div class="form-group">
                                                    <input type="text" id="swipe_1" class="form-control swipe"
                                                           placeholder="<?= lang('swipe') ?>"/>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input name="cc_no[]" type="text" id="pcc_no_1"
                                                                   class="form-control"
                                                                   placeholder="<?= lang('cc_no') ?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">

                                                            <input name="cc_holer[]" type="text" id="pcc_holder_1"
                                                                   class="form-control"
                                                                   placeholder="<?= lang('cc_holder') ?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <select name="cc_type[]" id="pcc_type_1"
                                                                    class="form-control pcc_type"
                                                                    placeholder="<?= lang('card_type') ?>">
                                                                <option value="Visa"><?= lang("Visa"); ?></option>
                                                                <option
                                                                    value="MasterCard"><?= lang("MasterCard"); ?></option>
                                                                <option value="Amex"><?= lang("Amex"); ?></option>
                                                                <option value="Discover"><?= lang("Discover"); ?></option>
                                                            </select>
                                                            <!-- <input type="text" id="pcc_type_1" class="form-control" placeholder="<?= lang('card_type') ?>" />-->
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input name="cc_month[]" type="text" id="pcc_month_1"
                                                                   class="form-control"
                                                                   placeholder="<?= lang('month') ?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">

                                                            <input name="cc_year" type="text" id="pcc_year_1"
                                                                   class="form-control"
                                                                   placeholder="<?= lang('year') ?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">

                                                            <input name="cc_cvv2" type="text" id="pcc_cvv2_1"
                                                                   class="form-control"
                                                                   placeholder="<?= lang('cvv2') ?>"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

											<div class="depreciation_1" style="display:none;">
                                                <div class="form-group">
													<?= lang("depreciation_term", "depreciation_1"); ?>
                                                </div>
												<!--<div style="display:inline-flex;padding-left:2%">
													<?php foreach($define_principle as $data){ ?>
															<div style="display:contents;"><input  type='checkbox' name='chk_principle[]' value="<?=$data->id?>" id="chk_principle" class='chk_principle form-control' ><?=$data->code ."(".$data->name .")";?></div>
													<?php } ?>
												</div>-->
                                                <div class="row">
													<div class="col-md-12">
														<div class="col-md-4">
															<div class="form-group">
																<input name="depreciation_rate1[]" type="text" id="depreciation_rate_1"
																	   class="form-control depreciation_rate1"
																	   placeholder="<?= lang('rate_percentage') ?>"/>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">

																<input name="depreciation_term[]" type="text" id="depreciation_term_1"
																	   class="form-control kb-pad" value=""
																	   placeholder="<?= lang('term_months') ?>"/>
																<input type="hidden" id="current_date" class="current_date" class="current_date[]" value="<?php echo date('m/d/Y'); ?>" />
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group">
																<select name="depreciation_type[]" id="depreciation_type_1"
																		class="form-control depreciation_type"
																		placeholder="<?= lang('payment_type') ?>">
																	<option value=""> &nbsp; </option>
																	<option value="1"><?= lang("normal"); ?></option>
																	<option value="2"><?= lang("custom"); ?></option>
																	<option value="3"><?= lang("fixed"); ?></option>
																	<option value="4"><?= lang("normal_fixed"); ?></option>
																</select>
															</div>
														</div>
													</div>
													<div class="col-md-12">
														<div class="col-md-4">
															<div class="form-group">
																<select name="principle_type[]" id="principle_type_1"
																		class="form-control principle_type"
																		placeholder="<?= lang('principle_type') ?>">
																	<option value=""> &nbsp; </option>
																	<?php foreach($define_principle as $data){ ?>
																		<option value="<?=$data->id?>"><?= $data->name; ?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group" id="print_" style="display:none">
																<button type="button" class="btn btn-primary col-md-12 print_depre" id="print_depre" style="margin-bottom:5px;"><i class="fa fa-print"> &nbsp; </i>
																	<?= lang('print') ?>
																</button>
															</div>
														</div>
														<div class="col-md-4">
															<div class="form-group" id="export_" style="display:none">
																<button type="button" class="btn btn-primary col-md-12 export_depre" id="export_depre" style="margin-bottom:5px;"><i class="fa fa-file-excel-o"> &nbsp; </i>
																	<?= lang('export') ?>
																</button>
																<div style="clear:both; height:15px;"></div>
															</div>
														</div>
													</div>
                                                </div>
												<div class="form-group">
													<div class="dep_tbl" style="display:none;">
														<table border="1" width="100%" class="table table-bordered table-condensed tbl_dep" id="tbl_dep">
															<tbody>

															</tbody>
														</table>
														<table id="export_tbl" width="70%" style="display:none;">

														</table>
													</div>
												</div>
                                            </div>

                                            <div class="pcheque_1" style="display:none;">
                                                <div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
                                                    <input name="cheque_no[]" type="text" id="cheque_no_1"
                                                           class="form-control cheque_no"/>
                                                    <!-- <input name="ggwp[]" type="text" id="ggwp_1"
                                                           class="form-control ggwp"/> -->
                                                </div>
                                            </div>

                                            <div class="pvoucher_1" style="display:none;">
                                                <div class="form-group"><?= lang("voucher_no", "voucher_no_1"); ?>
                                                    <input name="voucher_no[]" type="text" id="voucher_no_1"
                                                           class="form-control voucher_no"/>
                                                </div>
                                            </div>
<!--
                                            <div class="form-group">
                                                <?= lang('payment_note', 'payment_note'); ?>
                                                <textarea name="payment_note[]" id="payment_note_1"  class="pa form-control kb-text payment_note"></textarea>
                                            </div>
-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="multi-payment"></div>
							<!--<button type="button" class="btn btn-primary col-md-12 addButton"><i
                                class="fa fa-plus"></i> <?= lang('add_more_payments') ?></button>-->

                        <div style="clear:both; height:15px;"></div>

                    </div>
                    <div class="col-md-2 col-sm-3 text-center">
                        <span style="font-size: 1.2em; font-weight: bold;"><?= lang('quick_cash'); ?></span>

                        <div class="btn-group btn-group-vertical">
                            <button type="button" class="btn btn-lg btn-info quick-cash" id="quick-payable">0.00
                            </button>
							<input type="hidden" id="payable_amount" class="payable_amount" name="payable_amount" value="0.00" />
                            <?php
                            foreach (lang('quick_cash_notes') as $cash_note_amount) {
                                echo '<button type="button" class="btn btn-lg btn-warning quick-cash">' . $cash_note_amount . '</button>';
                            }
                            ?>
                            <button type="button" class="btn btn-lg btn-danger"
                                    id="clear-cash-notes"><?= lang('clear'); ?></button>
							<hr />
							<div class="btn-group">
                                <button type="button" style="font-size: 1.2em; font-weight: bold; height:80px;" class="btn btn-success" id="submit-sale">
                                    <i class="fa fa-money"></i> <?= lang('save'); ?>
                                </button>
							</div>

						</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="dep_tbl" style="display:none;">
	<table border="1" width="100%" class="table table-bordered table-condensed tbl_dep" id="tbl_dep1">
		<tbody>

		</tbody>
	</table>

</div>
<div class="dep_export" style="display:none;"></div>

<!-- Scroll Dialog -->

<div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scroll">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="prModalLabels"></h4>
            </div>
            <div class="modal-body modal-body-scroll" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-4 control-label"><?= lang('product_tax') ?></label>
                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('ptax', $tr, "", 'id="ptax" class="form-control pos-input-tip" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($Settings->product_serial) { ?>
                        <div class="form-group col-sm-12">
                            <label for="pserial" class="col-sm-4 control-label"><?= lang('serial_no') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-text" id="pserial">
                            </div>
                        </div>
                    <?php } ?>
					<div class="form-group col-sm-12">
                        <label for="piece" class="col-sm-4 control-label"><?= lang('piece') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="piece">
                        </div>
                    </div>
					<div class="form-group col-sm-12">
                        <label for="wpiece" class="col-sm-4 control-label"><?= lang('wpiece') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="wpiece">
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-pad" id="pquantity">
                        </div>
                    </div>
					<div class="form-group col-sm-12">
                        <label for="qtyinhand" class="col-sm-4 control-label"><?= lang('quantity_in_hand') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-pad" id="qtyinhand" disabled>
                        </div>
                    </div>
					<div class="form-group col-sm-12">
                        <label for="qtyorder" class="col-sm-4 control-label"><?= lang('quantity_order') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-pad" id="qtyorder" disabled>
                        </div>
                    </div>
					<?php  if ($Settings->product_expiry) { ?>
						<div class="form-group col-sm-12">
							<label for="expdates" class="col-sm-4 control-label"><?= lang('expdates') ?></label>

							<div class="col-sm-8">
								<div id="expdates-div"></div>
							</div>
						</div>
					<?php } ?>
                    <div class="form-group col-sm-12">
                        <label for="poption" class="col-sm-4 control-label"><?= lang('product_option') ?></label>

                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>

					<div class="form-group col-sm-12">
                        <label for="pgroup_prices" class="col-sm-4 control-label"><?= lang('group_price') ?></label>

                        <div class="col-sm-8">
                            <div id="pgroup_prices-div"></div>
                        </div>
                    </div>
                    <?php if ($Settings->product_discount) { ?>
                        <?php if ($Admin || $Owner || $GP['sales-discount']) { ?>
                            <div class="form-group col-sm-12">
                                <label for="pdiscount"
                                       class="col-sm-4 control-label"><?= lang('product_discount') ?></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control kb-pad" id="pdiscount">
                                </div>
                            </div>
                        <?php }
                    }
                    ?>
					<?php if ($Admin || $Owner || $GP['sales-price']) { ?>
                    <div class="form-group col-sm-12">
                        <label for="pprice" class="col-sm-4 control-label"><?= lang('unit_price') ?></label>
                        <div class="col-sm-8">
                            <input type="hidden" class="form-control kb-pad" id="pprice">
							<input type="text" class="form-control kb-pad" id="pprice_show">
							<input type="hidden" class="form-control" id="own_rate">
							<input type="hidden" class="form-control" id="setting_rate">
							<input type="hidden" class="form-control" value="" id="cost">
                        </div>
                    </div>
					<?php }else { ?>
							<div>
								<input type="hidden" class="form-control kb-pad" id="pprice">
								<input type="hidden" class="form-control kb-pad" id="pprice_show">
								<input type="hidden" class="form-control" id="own_rate">
								<input type="hidden" class="form-control" id="setting_rate">
								<input type="hidden" class="form-control" value="" id="cost">
							</div>
					<?php } ?>
					<div class="form-group col-sm-12">
                        <label for="pnote" class="col-sm-4 control-label"><?= lang('product_note') ?></label>

                        <div class="col-sm-8">
							<div class="input-group">
								<textarea class="form-control kb-pad" id="pnote" rows="5" cols="20" style="width: 291px; height: 35px;"></textarea>
								<div class="input-group-addon no-print" style="padding: 2px 5px;">
									<a href="<?= site_url('system_settings/show_note'); ?>" id="add-productnote" class="external" data-toggle="modal" data-target="#myModal">
										<i class="fa fa-2x fa-plus-circle" id="addIcon"></i>
									</a>
								</div>
							</div>
                        </div>
					</div>
					<div class="form-group col-sm-12" >
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="net_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="pro_tax"></span></th>
                        </tr>
                    </table>
					<div class="images row">

					</div>
                    <input type="hidden" id="punit_price" value=""/>
                    <input type="hidden" id="old_tax" value=""/>
                    <input type="hidden" id="old_qty" value=""/>
                    <input type="hidden" id="old_price" value=""/>
                    <input type="hidden" id="row_id" value=""/>
					</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editItem"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<!--------- Search Form ------------->

<div class="modal" id="seModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="prModalLabel">Search Filter</h4>
            </div>
            <div class="modal-body scroll_F" id="pr_popover_content" style="height:400px;overflow:hidden;">
                <form class="form-horizontal" role="form" id="s_seModal">
					<table>
						<thead>
							<tr>
								<td style="border:1px;width:5%"><input type="text" class="form-control" id="chk" disabled/></td>
								<td style="padding:0;margin:0;border:1px;width:20%"><input type="text" class="form-control" id="Pcode"/></td>
								<td style="padding:0;margin:0;border:1px;width:25%"><input type="text" class="form-control" id="Pname"/></td>
								<!--<td style="padding:0;margin:0;border:1px;"><input type="text" style="width:113px;border-right:none" class="form-control" id="Pdescription" /></td>-->
								<td style="padding:0;margin:0;border:1px;width:20%"><input type="text" class="form-control" id="Pcategory" ></td>
								<td style="padding:0;margin:0;border:1px;width:10%"><input type="text" class="form-control" id="Pprice"></td>
								<td style="padding:0;margin:0;border:1px;width:20%"><input type="text" class="form-control" id="dd" disabled></td>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
                    <table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th style="width:5%;">
									<center>
										<input class="checkbox checkth input-xs" type="checkbox" name="check"/>
									</center>
								</th>
								<th style="width:20%"><?= lang("product_code");?></th>
								<th style="width:25%"><?= lang("product_name");?></th>
								<!--<th style="width:104px"><?= lang("description");?></th>-->
								<th style="width:20%"><?= lang("category")?></th>
								<th style="width:10%"><?= lang("price");?></th>
								<!--<th style="width:200px"><?= lang("strap");?></th>-->
								<th style="width:20px"><i class="fa fa-chain"></i></th>
							</tr>
						</thead>
						<tbody class="test">

						</tbody>
					</table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addItem"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="ShowImage" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="prModalLabel" aria-hidden="true" style="z-index:9999;">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-body">
			<button type="button" class="close" data-dismiss="modal">
				<span aria-hidden="true"><i class="fa fa-2x">×</i></span>
			</button>
			<div class="getImg"></div>
		</div>
    </div>
  </div>
</div>

<div class="modal" id="seFoModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="prModalLabel">Search Filter</h4>
            </div>
            <div class="modal-body scroll_F" id="pr_popover_content" style="height:300px;overflow:hidden;">
                <form class="form-horizontal" role="form" id="s_seModal">
					<table>
						<thead>
							<tr>
								<td style="padding:0;margin:0;border:1px;"><input type="text" style="width:184px;border-right:none" class="form-control" id="fcode"/></td>
								<td style="padding:0;margin:0;border:1px;"><input type="text" style="width:239px;border-right:none" class="form-control" id="fdescription"/></td>
								<td style="padding:0;margin:0;border:1px;"><input type="text" style="width:145px;" class="form-control" id="ffloor" /></td>
							</tr>
						</thead>
					</table>
                    <table class="table table-bordered">
						<thead>
							<tr>
								<th style="width:45px;"></th>
								<th><?php echo lang('code'); ?></th>
								<th><?php echo lang('description'); ?></th>
								<th><?php echo lang('status'); ?></th>
								<th><?php echo lang('floor'); ?></th>
							</tr>
						</thead>
						<tbody class="floor">

						</tbody>
					</table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addSearch"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="gcModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="myModalLabel"><?= lang('sell_gift_card'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?= lang('enter_info'); ?></p>

                <div class="alert alert-danger gcerror-con" style="display: none;">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <span id="gcerror"></span>
                </div>
                <div class="form-group">
                    <?= lang("card_no", "gccard_no"); ?> *
                    <div class="input-group">
                        <?php echo form_input('gccard_no', '', 'class="form-control" id="gccard_no"'); ?>
                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                            <a href="#" id="genNo"><i class="fa fa-cogs"></i></a>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="gcname" value="<?= lang('gift_card') ?>" id="gcname"/>

                <div class="form-group">
                    <?= lang("value", "gcvalue"); ?> *
                    <?php echo form_input('gcvalue', '', 'class="form-control" id="gcvalue"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("price", "gcprice"); ?> *
                    <?php echo form_input('gcprice', '', 'class="form-control" id="gcprice"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("customer", "gccustomer"); ?>
                    <?php echo form_input('gccustomer', '', 'class="form-control" id="gccustomer"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("expiry_date", "gcexpiry"); ?>
                    <?php echo form_input('gcexpiry', '', 'class="form-control date" id="gcexpiry"'); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="addGiftCard" class="btn btn-primary"><?= lang('sell_gift_card') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="mModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-text" id="mcode">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mname" class="col-sm-4 control-label"><?= lang('product_name') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-text" id="mname">
                        </div>
                    </div>
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label for="mtax" class="col-sm-4 control-label"><?= lang('product_tax') ?> *</label>

                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('mtax', $tr, "", 'id="mtax" class="form-control pos-input-tip" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= lang('quantity') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-pad" id="mquantity">
                        </div>
                    </div>
                    <?php if ($Settings->product_discount) { ?>
                        <div class="form-group">
                            <label for="mdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control kb-pad" id="mdiscount">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="mprice" class="col-sm-4 control-label"><?= lang('unit_price') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control kb-pad" id="mprice">
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="mnet_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="mpro_tax"></span></th>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addItemManually"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="sckModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="mModalLabel"><?= lang('shortcut_keys') ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <table class="table table-bordered table-striped table-condensed table-hover" style="margin-bottom: 0px;">
                    <thead>
						<tr>
							<th><?= lang('shortcut_keys') ?></th>
							<th><?= lang('description') ?></th>
							<th><?= lang('actions') ?></th>
						</tr>
                    </thead>
                    <tbody>
						<tr>
							<td><?= $pos_settings->focus_add_item ?></td>
							<td><?= lang('focus_add_item') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12" id="add_item"> <?= $pos_settings->focus_add_item ?></button>
							</td>
						</tr>
						<tr>
							<td><?= $pos_settings->customer_selection ?></td>
							<td><?= lang('customer_selection') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12 addButton" id="customer"> <?= $pos_settings->customer_selection ?></button>
							</td>
						</tr>
						<tr>
							<td><?= $pos_settings->toggle_category_slider ?></td>
							<td><?= lang('toggle_category_slider') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12 addButton open-category"> <?= $pos_settings->toggle_category_slider ?></button>
							</td>
						</tr>
						<tr>
							<td><?= $pos_settings->toggle_subcategory_slider ?></td>
							<td><?= lang('toggle_subcategory_slider') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12 addButton open-subcategory"> <?= $pos_settings->toggle_subcategory_slider ?></button>
							</td>
						</tr>
						<tr>
							<td><?= $pos_settings->today_sale ?></td>
							<td><?= lang('today_sale') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12" id="today_profit" data-placement="bottom" data-html="true" href="<?= site_url('pos/today_sale') ?>" data-toggle="modal" data-target="#myModal"> <?= $pos_settings->today_sale ?></button>
							</td>
						</tr>
						<tr>
							<td><?= $pos_settings->close_register ?></td>
							<td><?= lang('close_register') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12" id="close_register" data-placement="bottom" data-html="true" href="<?= site_url('pos/close_register') ?>" data-toggle="modal" data-target="#myModal"> <?= $pos_settings->close_register ?></button>
							</td>
						</tr>
						<tr>
							<td><?= $pos_settings->show_search_item ?></td>
							<td><?= lang('show_search_item') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12 addButton" id="search_details"> <?= $pos_settings->show_search_item ?></button>
							</td>
						</tr>
						<tr>
							<td><?= $pos_settings->product_unit ?></td>
							<td><?= lang('product_unit') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12 addButton editor"> <?= $pos_settings->product_unit ?></button>
							</td>
						</tr>
						<tr>
							<td><?= $pos_settings->cancel_sale ?></td>
							<td><?= lang('cancel_sale') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12 addButton" id="clearData"> <?= $pos_settings->cancel_sale ?></button>
							</td>
						</tr>

						<tr>
							<td><?= $pos_settings->suspend_sale ?></td>
							<td><?= lang('suspend_sale') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12 addButton open-suspend"> <?= $pos_settings->suspend_sale ?></button>
							</td>
						</tr>

						<tr>
							<td><?= $pos_settings->print_items_list ?></td>
							<td><?= lang('print_items_list') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12 addButton" id="print_orders"> <?= $pos_settings->print_items_list ?></button>
							</td>
						</tr>

						<tr>
							<td><?= $pos_settings->print_bill ?></td>
							<td><?= lang('print_bill') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12 addButton" id="print_bills"> <?= $pos_settings->print_bill ?></button>
							</td>
						</tr>

						<tr>
							<td><?= $pos_settings->finalize_sale ?></td>
							<td><?= lang('finalize_sale') ?></td>
							<td>
								<button type="button" class="btn bdarkGreen col-md-12 addButton " id="paid-ment"> <?= $pos_settings->finalize_sale ?></button>
							</td>
						</tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="dsModal" tabindex="-1" role="dialog" aria-labelledby="dsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="dsModalLabel"><?= lang('edit_order_discount'); ?></h4>
            </div>
            <?php if ($Admin || $Owner || $GP['sales-discount']) { ?>
                <div class="modal-body">
                    <div class="form-group">
                        <?= lang("order_discount", "order_discount_input"); ?>
                        <?php echo form_input('order_discount_input', '', 'class="form-control kb-pad" id="order_discount_input"'); ?>

                    </div>
                </div>
            <?php } else { ?>
                <div class="modal-body">
                    <div class="form-group">
                        <?= lang("order_discount", "order_discount_input"); ?>
                        <?php echo form_input('order_discount_input', '', 'class="form-control kb-pad" id="order_discount_input" readonly="readonly"'); ?>
                    </div>
                </div>
            <?php } ?>
            <div class="modal-footer">
                <button type="button" id="updateOrderDiscount" class="btn btn-primary"><?= lang('update') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="txModal" tabindex="-1" role="dialog" aria-labelledby="txModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="txModalLabel"><?= lang('edit_order_tax'); ?></h4>
            </div>

			<div class="modal-body">
                <div class="form-group">
                    <?=lang("order_tax", "order_tax_input");?>
					<?php
						$tr[""] = "";
						foreach ($tax_rates as $tax) {
							$tr[$tax->id] = $tax->name;
						}
						echo form_dropdown('order_tax_input', $tr, "", 'id="order_tax_input" class="form-control pos-input-tip" style="width:100%;"');
					?>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="updateOrderTax" class="btn btn-primary"><?= lang('update') ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="shipping_modal" tabindex="-1" role="dialog" aria-labelledby="txModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="txModalLabel"><?= lang('edit_shipping'); ?></h4>
            </div>

			<div class="modal-body">
                <div class="form-group">
                    <?=lang("shipping", "shipping");?>
					<?php echo form_input('shipping', "", 'class="form-control input-tip" id="shipping" style="width:100%;"'); ?>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="add_shipping" class="btn btn-primary"><?= lang('add') ?></button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="b-footer" value="<?php echo $biller->invoice_footer; ?>">

<div class="modal fade in" id="susModal" tabindex="-1" role="dialog" aria-labelledby="susModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="susModalLabel"><?= lang('suspend_sale'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?= lang('type_reference_note'); ?></p>

                <div class="form-group suspend-add">
                    <?= lang("reference_note", "reference_note"); ?>
                    <input type="hidden" id="reference_note" class="form-control kb-text" value="" name="reference_note">
                    <?php //echo form_input('reference_note', $reference_note, 'class="form-control kb-text" id="reference_note" style="display: none;"'); ?>
                </div>
                <div class="form-group">
                    <?php
                    for($i=1; $i < 21; $i++){
                        if($i == 12) echo '</div><div class="form-group">';
                        ?>
                        <button class="btn-primary btn num_suspend" value="<?=$i?>" type="button">
                            <span><?=$i?></span>
                        </button>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="suspend_sale" class="btn btn-primary"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<div id="order_tbl_drink" style="display:none"> <span id="order_span_drink"></span>
    <table id="order-table-drink" class="prT table table-striped" style="margin-bottom:0;" width="100%"></table>
</div>
<div id="order_tbl_food" style="display:none"><span id="order_span_food"></span>
    <table id="order-table-food" class="prT table table-striped" style="margin-bottom:0;" width="100%"></table>
</div>
<div id="order_tbl" style="display:none"><span id="order_span"></span>
    <table id="order-table" class="prT table table-striped" style="margin-bottom:0;" width="100%"></table>
</div>
<div id="bill_tbl"><span id="bill_span"></span>
	<table id="bill-table" width="100%" class="prT table table-striped table-condensed receipt" style="margin-bottom:0;"></table>
    <table id="bill-total-table" class="prT table" style="margin-bottom:0;" width="100%"></table>
</div>
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:9999;"></div>

<div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true"></div>

<div class="modal fade in" id="poslist" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true"></div>
<div id="modal-loading" style="display: none;">
    <div class="blackbg"></div>
    <div class="loader"></div>
</div>
<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification, $Settings->protocol, $Settings->mailpath, $Settings->smtp_crypto, $Settings->corn, $Settings->customer_group, $Settings->envato_username, $Settings->purchase_code); ?>
<script type="text/javascript">
var site = <?= json_encode(array('base_url' => base_url(), 'settings' => $Settings, 'dateFormats' => $dateFormats)) ?>, pos_settings = <?= json_encode($pos_settings); ?>, user_layout = <?= json_encode($user_layout); ?>;
var lang = {unexpected_value: '<?=lang('unexpected_value');?>', select_above: '<?=lang('select_above');?>', r_u_sure: '<?= lang('r_u_sure'); ?>'};
</script>

<script type="text/javascript">
    var product_variant = 0, shipping = 0, p_page = 0, per_page = 0, tcp = "<?= $tcp ?>",
        cat_id = "<?= $pos_settings->default_category ?>", ocat_id = "<?= $pos_settings->default_category ?>", sub_cat_id = 0, osub_cat_id,
        count = 1, an = 1, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, total_paid = 0, grand_total = 0,
        KB = <?= $pos_settings->keyboard ?>, tax_rates = <?php echo json_encode($tax_rates); ?>;
    var protect_delete = <?php if(!$Owner && !$Admin) { echo $pos_settings->pin_code ? '1' : '0'; } else { echo '0'; } ?>;
    //var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');
    //var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');
    var lang_total = '<?=lang('total');?>', lang_items = '<?=lang('items');?>', lang_discount = '<?=lang('discount');?>', lang_tax2 = '<?=lang('order_tax');?>', lang_total_payable = '<?=lang('total_payable');?>';
    var java_applet = <?=$pos_settings->java_applet?>, order_data = '', bill_data = '';

    function widthFunctions(e) {
        var wh = $(window).height(),
        lth = $('#left-top').height(),
        lbh = $('#left-bottom').height();
		var bar_t = 0;
        <?php if($layout && $layout != 1) { ?>
        var bar_t = 98;
        <?php } ?>
        <?php if($layout == 5) {?>
        $('#box-item').remove();
		$('#box-item #box-item').remove();
		$('.btn_gift_card').hide();
		cat_id=0;
        $('#item-list').css("height", wh - bar_t - 610);
        $('#item-list').css("min-height", 265);
        <?php } else { ?>
        $('#item-list').css("height", wh - bar_t - 140);
        $('#item-list').css("min-height", 515);
        <?php } ?>
        <?php if($layout == 3) {?>
        $('#left-middle').css("height", wh - lth - lbh - bar_t + 18);
		$('#left-middle').css("min-height", 325);
		$('#product-list').css("height", wh - lth - lbh - bar_t + 12);
        <?php } else { ?>
        $('#left-middle').css("height", wh - lth - lbh - bar_t - 100);
		$('#left-middle').css("min-height", 325);
        $('#product-list').css("height", wh - lth - lbh - bar_t - 105);
        <?php } ?>
        $('#product-list').css("min-height", 320);
		$('#suspend-slider').css("height", wh - lth - lbh - bar_t - 100);
        $('#suspend-slider').css("min-height", 555);
		$('#suspend-list').css("height", wh - lth - lbh - bar_t - 105);
        $('#suspend-list').css("min-height", 550);


        <?php if($layout == 5) {?>
        $('#product-list').css("min-height", 20);
        $('#product-list').css("height", wh - lth - lbh - bar_t - 250);
        $('#left-middle').css("height", wh - lth - lbh - bar_t - 250);
		$('#left-middle').css("min-height", 10);
        <?php } ?>
		<?php if($layout == 6) {?>
        $('#product-list').css("min-height", 300);
        $('#product-list').css("overflow-x", 'scroll');
        $('#product-list').css("height", wh - 950);
        $('#left-middle').css("height", wh - 950);
		$('#left-middle').css("min-height", 10);
        $('#left-bottom').css("margin-top", 300);
        <?php } ?>
        <?php if($layout == 1) {?>
        $('#box-item').remove();
		$('#box-item #box-item').remove();
		cat_id=0;
        <?php } ?>
    }
    $(window).bind("resize", widthFunctions);

    $(document).ready(function () {

		$('#view-customer').click(function(){

            $('#myModal').modal({remote: site.base_url + 'customers/view/' + $("input[name=customer]").val()});
            $('#myModal').modal('show');
        });

		$('#pos-list').click(function(event){
            event.preventDefault();
            $('#poslist').modal({remote: site.base_url + 'pos/pos_list/' + $("#poswarehouse").val()});
            $('#poslist').modal('show');
        });

        <?php if ($sid) { ?>
        __setItem('positems', JSON.stringify(<?=$items;?>));
        <?php } ?>
        <?php if($this->session->userdata('remove_posls')) { ?>
            if (__getItem('positems')) {
                __removeItem('positems');
            }
            if (__getItem('posdiscount')) {
                __removeItem('posdiscount');
            }
            if (__getItem('postax2')) {
                __removeItem('postax2');
            }
            if (__getItem('posshipping')) {
                __removeItem('posshipping');
            }
            if (__getItem('poswarehouse')) {
                __removeItem('poswarehouse');
            }
            if (__getItem('posnote')) {
                __removeItem('posnote');
            }
            if (__getItem('poscustomer')) {
                __removeItem('poscustomer');
            }
            if (__getItem('posbiller')) {
                __removeItem('posbiller');
            }
            if (__getItem('date')) {
                __removeItem('date');
            }
            if (__getItem('poscurrency')) {
                __removeItem('poscurrency');
            }
            if (__getItem('posnote')) {
                __removeItem('posnote');
            }
            if (__getItem('staffnote')) {
                __removeItem('staffnote');
            }
        <?php $this->erp->unset_data('remove_posls'); } ?>
        widthFunctions();
        <?php if($suspend_sale) { ?>
			__setItem('postax2', <?=$suspend_sale->order_tax_id;?>);
			__setItem('posdiscount', '<?=$suspend_sale->order_discount_id;?>');
			__setItem('poswarehouse', '<?=$suspend_sale->warehouse_id;?>');
			__setItem('poscustomer', '<?=($cus_suspend == "" ? $suspend_sale->customer_id : $cus_suspend->customer_id );?>');
			__setItem('posbiller', '<?=$suspend_sale->biller_id;?>');
        <?php } ?>
        <?php if($this->input->get('customer')) { ?>
			if (!__getItem('positems')) {
				__setItem('poscustomer', '<?=$this->input->get('customer');?>');
			} else if (!__getItem('poscustomer')) {
				__setItem('poscustomer', '<?= $customer->id; ?>');
			}
        <?php } else { ?>

			if (!__getItem('poscustomer')) {
				__setItem('poscustomer', '<?= $customer->id; ?>' );
			}
        <?php } ?>
        if (!__getItem('postax2')) {
            __setItem('postax2', '<?=$Settings->default_tax_rate2;?>');
        }
        $('.select').select2({minimumResultsForSearch: 6});
        var cutomers = [{
            id: '<?= $customer->id; ?>',
            text: '<?= $customer->company == '-' ? $customer->names : $customer->company; ?>'
        }];

        $('#poscustomer').val(__getItem('poscustomer')).select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: "<?= site_url('customers/getCustomer') ?>/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        var order_discount = data[0].order_discount == false ? 0 : data[0].order_discount;
                        $('#order_discount').val(order_discount + '%');
                        //$('#order_discount').val(data[0].order_discount == null ? 0 : data[0].order_discount + '%');
                        if (order_discount > 0) {
                            $('#order_discount_input').attr('readonly', 'true');
                        }
                        callback(data[0]);
                    }
                });
            },
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });

        if (KB) {
            display_keyboards();

            var result = false;
            $('#poscustomer').on('select2-opening', function () {
                $('.select2-input').addClass('kb-text');
                display_keyboards();
                $('.select2-input').bind('change.keyboard', function (e, keyboard, el) {
                    if (el && el.value != '' && el.value.length >= 4) {
                        $('.select2-input').addClass('select2-active');
                        $.ajax({
                            type: "get",
                            async: false,
                            url: "<?= site_url('customers/suggestions') ?>/" + el.value,
                            dataType: "json",
                            success: function (res) {
                                if (res.results != null) {
                                    $('#poscustomer').select2({data: res}).select2('open');
                                    $('.select2-input').removeClass('select2-active');
                                    result = true;
                                } else {
                                    result = false;
                                }
                            }
                        });
                        if (!result) {
                            bootbox.alert('no_match_found');
                            $('#poscustomer').select2('close');
                            $('#test').click();
                        }
                    }
                });
            });

            $('#poscustomer').on('select2-close', function () {
                $('.select2-input').removeClass('kb-text');
                $('#test').click();
                $('select, .select').select2('destroy');
                $('select, .select').select2({minimumResultsForSearch: 6});
            });
            $(document).bind('click', '#test', function () {
                var kb = $('#test').keyboard().getkeyboard();
                kb.close();
                //kb.destroy();
                $('#add-item').focus();
            });

        }

		if(posbiller = __getItem('posbiller')){
			$("#posbiller").val(posbiller);
		}

		if(saleman = __getItem('saleman')){
			$('#saleman').val(saleman);
		}

		$('#posbiller').change(function(){
			$('#biller').val($(this).val());
			var id = $(this).val();
			$.ajax({
            url: '<?= base_url() ?>sales/getReferenceByProject/pos/'+id,
            dataType: 'json',
				success: function(data){
					$("#slref").val(data);
					$("#temp_reference_no").val(data);
					$(".reference_nob").val(data);
					$("#slref").prop('readonly', true);
				}
			});
		});

		$('#saleman').change(function(){
			$('#saleman_1').val($(this).val());
		});

		$('#delivery_by').change(function(){
			$('#delivery_by_1').val($(this).val());
		});
		if(__getItem('delivery_by')){
			//$('#delivery_by').val(delivery_by);
		}

		$('#sale_status').live('change keyup paste', function() {
			$('#sale_status_1').val($(this).val());
		}).trigger('change');

        $('#document').live('change keyup paste', function () {
            $('#document_1').val($(this).val());
        }).trigger('change');

		$(document).on('change', '#other_cur_paid', function () {
            $('.other_cur_paid').val($(this).val());
			$("#amount_1").trigger('change');
        });

        <?php for($i=1; $i<=5; $i++) { ?>
        $('#paymentModal').on('change', '#amount_<?=$i?>', function (e) {
            $('#amount_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('blur', '#amount_<?=$i?>', function (e) {
            $('#amount_val_<?=$i?>').val($(this).val());
        });

		$('#paymentModal').on('change', '#other_cur_paid_<?=$i?>', function (e) {
            $('#other_cur_paid_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('blur', '#other_cur_paid_<?=$i?>', function (e) {
            $('#other_cur_paid_val_<?=$i?>').val($(this).val());
        });

        $('#paymentModal').on('select2-close', '#paid_by_<?=$i?>', function (e) {
            $('#paid_by_val_<?=$i?>').val($(this).val());

        });

		$('#paymentModal').on('select2-close', '#bank_account_<?=$i?>', function (e) {
            $('#bank_account_val_<?=$i?>').val($(this).val());

        });
        $('#paymentModal').on('change', '#pcc_no_<?=$i?>', function (e) {
            $('#cc_no_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#pcc_holder_<?=$i?>', function (e) {
            $('#cc_holder_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#gift_card_no_<?=$i?>', function (e) {
            $('#paying_gift_card_no_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#pcc_month_<?=$i?>', function (e) {
            $('#cc_month_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#pcc_year_<?=$i?>', function (e) {
            $('#cc_year_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#pcc_type_<?=$i?>', function (e) {
            $('#cc_type_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#pcc_cvv2_<?=$i?>', function (e) {
            $('#cc_cvv2_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#cheque_no_<?=$i?>', function (e) {
            $('#cheque_no_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#voucher_no_<?=$i?>', function (e) {
            $('#voucher_no_val_<?=$i?>').val($(this).val());
        });
        $('#paymentModal').on('change', '#payment_note_<?=$i?>', function (e) {
            $('#payment_note_val_<?=$i?>').val($(this).val());
        });
		$('#paymentModal').on('change', '#depreciation_rate1_<?=$m?>', function (e) {
			$('#depreciation_rate1_val_<?=$m?>').val($(this).val());
		});
		$('#paymentModal').on('change', '#depreciation_term_<?=$m?>', function (e) {
			$('#depreciation_term_val_<?=$m?>').val($(this).val());
		});
		$('#paymentModal').on('change', '#depreciation_type_<?=$m?>', function (e) {
			$('#depreciation_type_val_<?=$i?>').val($(this).val());
		});

		<?php }
		?>
        $('#payment').click(function () {
            var GP = '<?= $GP['sales-discount'];?>';
			var Owner = '<?= $Owner?>';
			var Admin = '<?= $Admin?>';
			var user_log = '<?= $this->session->userdata('user_id');?>';

			if(__getItem('addre')){
				//$("#sale_note").attr("value", __getItem('addre'));
				//var nott = $("#sale_note").val();
				//__setItem('nott',nott);
			}

			if(Owner || Admin || (GP == 1)){
				<?php if ($sid) { ?>
				suspend = $('<span></span>');
				suspend.html('<input type="hidden" name="delete_id" value="<?php echo $sid; ?>" />');
				suspend.appendTo("#hidesuspend");
				<?php } ?>
				var twt = formatDecimal(((total + total_tax) - order_discount) + parseFloat(total_shipping));

				if (an == 1) {
					bootbox.alert('<?= lang('x_total'); ?>');
					return false;
				}
				gtotal = formatDecimal(twt);


				<?php if($pos_settings->rounding) { ?>

				round_total = roundNumber(gtotal, <?=$pos_settings->rounding?>);
				var rounding = formatDecimal(0 - (gtotal - round_total));
				var total_p = formatMoney(round_total) + ' (' + formatMoney(rounding) + ')';
				$('#twt').text(total_p);

				$('#quick-payable').text(round_total);
				$('#payable_amount').val(round_total);
				<?php } else { ?>
				$('#twt').text(formatMoney(gtotal));
				$('#quick-payable').text(gtotal);
				$('#payable_amount').val(gtotal);
				// $('#amount_1').val(gtotal);
				<?php } ?>
				$('#product_note').val($('#get_not').text());
				$('#item_count').text(count - 1);
				$('.item_count').text(count - 1);
				$('#paymentModal').appendTo("body").modal('show');
				$('input[type="checkbox"],[type="radio"]').not('.skip').iCheck({
					checkboxClass: 'icheckbox_square-blue',
					radioClass: 'iradio_square-blue',
					increaseArea: '20%' // optional
				});
				$("#posbiller").trigger("change");
				//$('.currencies_payment').focus();
				$("#date").trigger('change');
				$("#saleman").trigger('change');
				$("#delivery_by").trigger('change');


				autoCalcurrencies(gtotal);
			}else{
				var val = '';
				$('.sdiscount').each(function(){
					var value = $(this).val();
					if(value != 0){
						val = value;
					}

				});
				if(val == 0){
					<?php if ($sid) { ?>
					suspend = $('<span></span>');
					suspend.html('<input type="hidden" name="delete_id" value="<?php echo $sid; ?>" />');
					suspend.appendTo("#hidesuspend");
					<?php } ?>
					var twt = formatDecimal((total + invoice_tax) - order_discount);
					if (an == 1) {
						bootbox.alert('<?= lang('x_total'); ?>');
						return false;
					}
					gtotal = formatDecimal(twt);
					<?php if($pos_settings->rounding) { ?>
					round_total = roundNumber(gtotal, <?=$pos_settings->rounding?>);
					var rounding = formatDecimal(0 - (gtotal - round_total));
					var total_p = formatMoney(round_total) + ' (' + formatMoney(rounding) + ')';
					$('#twt').text(total_p);
					$('#quick-payable').text(round_total);
					$('#payable_amount').val(round_total);
					<?php } else { ?>
					$('#twt').text(formatMoney(gtotal));
					$('#quick-payable').text(gtotal);
					$('#payable_amount').val(gtotal);
					<?php } ?>
					$('#product_note').val($('#get_not').text());
					$('#item_count').text(count - 1);
					$('.item_count').text(count - 1);
					$('#paymentModal').appendTo("body").modal('show');
					$('input[type="checkbox"],[type="radio"]').not('.skip').iCheck({
						checkboxClass: 'icheckbox_square-blue',
						radioClass: 'iradio_square-blue',
						increaseArea: '20%' // optional
					});

					$("#posbiller").trigger("change");
					//$('.currencies_payment').focus();
					$("#date").trigger('change');
					$("#saleman").trigger('change');
					$("#delivery_by").trigger('change');

					autoCalcurrencies(gtotal);
				}else{
					bootbox.prompt("Please insert password", function(result){
						$.ajax({
							type: 'get',
							url: '<?= site_url('auth/checkPassDiscount'); ?>',
							dataType: "json",
							data: {
								password: result
							},
							success: function (data) {
								if(data == 1){
									<?php if ($sid) { ?>
									suspend = $('<span></span>');
									suspend.html('<input type="hidden" name="delete_id" value="<?php echo $sid; ?>" />');
									suspend.appendTo("#hidesuspend");
									<?php } ?>
									var twt = formatDecimal((total + invoice_tax) - order_discount);
									if (an == 1) {
										bootbox.alert('<?= lang('x_total'); ?>');
										return false;
									}
									gtotal = formatDecimal(twt);
									<?php if($pos_settings->rounding) { ?>
									round_total = roundNumber(gtotal, <?=$pos_settings->rounding?>);
									var rounding = formatDecimal(0 - (gtotal - round_total));
									var total_p = formatMoney(round_total) + ' (' + formatMoney(rounding) + ')';
									$('#twt').text(total_p);
									$('#quick-payable').text(round_total);
									$('#payable_amount').val(round_total);
									<?php } else { ?>
									$('#twt').text(formatMoney(gtotal));
									$('#quick-payable').text(gtotal);
									$('#payable_amount').val(gtotal);
									<?php } ?>
									$('#product_note').val($('#get_not').text());
									$('#item_count').text(count - 1);
									$('.item_count').text(count - 1);
									$('#paymentModal').appendTo("body").modal('show');
									$('input[type="checkbox"],[type="radio"]').not('.skip').iCheck({
										checkboxClass: 'icheckbox_square-blue',
										radioClass: 'iradio_square-blue',
										increaseArea: '20%' // optional
									});

									$("#posbiller").trigger("change");
									//$('.currencies_payment').focus();
									$("#date").trigger('change');
									$("#saleman").trigger('change');
									$("#delivery_by").trigger('change');

									autoCalcurrencies(gtotal);
								}else{
									alert('Incorrect passord');
								}
							}
						});
					});
				}
			}
			$('#pos_note').val(__getItem('address'));
			//$('#sale_note').text(__getItem('address'));
		});

		$("#payment").bind('keypress', function(){
			$('#amount_1').focus();
		});

        function autoCalcurrencies(total_p){
            $(".curr_tpay").each(function(){
                var rate = $(this).attr('rate');
                $(this).html((total_p*rate).toFixed(0));
            });
        }

		function autoCalremain(total_p){
            $(".curr_remain").each(function(){
                var rate = $(this).attr('rate');
				if(total_p != 0){
					$(this).html(formatDecimal((parseFloat(total_p)*rate).toFixed(0)));
				}else{
					$(this).html('0');
				}
            });
			$(".curr_remain_").each(function(){
                var rate = $(this).attr('rate');
				var ch = (total_p).toFixed(4);
				var str = ch.split('.');
				var dot_cash = '0.'+str[1];
				if(parseFloat(dot_cash) != 0){
					$(this).html(formatDecimal((parseFloat(dot_cash)*rate).toFixed(0)));
				}else{
					$(this).html('0');
				}
            });
        }

		function autoCalchange(total_p){
            $(".curr_change").each(function(){
                var rate = $(this).attr('rate');
				if(total_p != 0){
					$(this).html((parseFloat(total_p)*rate).toFixed(0));
				}else{
					$(this).html('0');
				}
            });
			$(".curr_change_1").each(function(){
                var rate = $(this).attr('rate');
				var ch = (total_p).toFixed(4);
				var str = ch.split('.');
				var dot_cash = '0.'+str[1];
				if(parseFloat(dot_cash) != 0){
					$(this).html((parseFloat(dot_cash) * rate).toFixed(0));
				}else{
					$(this).html('0');
				}
            });
        }

		function other_curr_paid_2_us(){
			var total_other_paid = 0;
            $(".currencies_payment").each(function(){
                var rate = $(this).attr('rate');
				var paid = $(this).val()-0;
				if(paid != '' || Number(paid)){
					total_other_paid += (paid/rate);
				}
			});
			return total_other_paid;
        }
//////////////
		function grandtotalval(cls=""){
			var gtotal = 0;
				$("."+cls).each(function(){
					gtotal+=parseFloat($(this).val()-0);
				});
			return gtotal;
		}
		///////////////
        var pi = 'amount_1', pa = 2;
        $(document).on('click', '.quick-cash', function () {
            var $quick_cash = $(this);
            var amt = $quick_cash.contents().filter(function () {
                return this.nodeType == 3;
            }).text();
            var th = site.settings.thousands_sep == 0 ? '' : site.settings.thousands_sep;
            var dollar_pi = $('#' + pi);
			var total_amount = $('#payable_amount').val()-0;
            amt = formatDecimal(amt.split(th).join("")) * 1 + dollar_pi.val() * 1;

			var balance = total_amount - amt;

			if(balance > 0){
				autoCalremain(formatDecimal(balance));
				autoCalchange(0);
				$('#remain').text(formatMoney(balance));
				$('#change').text('0.00');
			}else if(balance < 0){
				balance = balance * (-1);
				autoCalremain(0);
				autoCalchange(formatDecimal(balance));
				$('#change').text(formatMoney(balance));
				$('#remain').text('0.00');
			}else{
				autoCalremain(0);
				autoCalchange(0);
				$('#change').text('0.00');
				$('#remain').text('0.00');
			}

			//$('#amount_1').val(formatDecimal(amt));
            dollar_pi.val(formatDecimal(amt)).focus();
            var note_count = $quick_cash.find('span');
            if (note_count.length == 0) {
                $quick_cash.append('<span class="badge">1</span>');
            } else {
                note_count.text(parseInt(note_count.text()) + 1);
            }
        });

        $(document).on('click', '#clear-cash-notes', function () {
            $('.quick-cash').find('.badge').remove();
            $('#' + pi).val('0').focus();
            // set balance to zero when clear cash
            $('#balance, .curr_balance, .currencies_payment').html('0');
        });

        $(document).on('change', '.gift_card_no', function () {
            var cn = $(this).val() ? $(this).val() : '';
            var payid = $(this).attr('id'),
                id = payid.substr(payid.length - 1);
            if (cn != '') {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "sales/validate_gift_card/" + cn,
                    dataType: "json",
                    success: function (data) {
                        if (data === false) {
                            $('#gift_card_no_' + id).parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('incorrect_gift_card')?>');
                        } else if (data.customer_id !== null && data.customer_id !== $('#poscustomer').val()) {
                            $('#gift_card_no_' + id).parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('gift_card_not_for_customer')?>');
                        } else {
                            $('#gc_details_' + id).html('<small>Card No: ' + data.card_no + '<br>Value: ' + data.value + ' - Balance: ' + data.balance + '</small>');
                            $('#gift_card_no_' + id).parent('.form-group').removeClass('has-error');
                            //calculateTotals();
                            $('#amount_' + id).val(data.balance).focus();
                        }
                    }
                });
            }
        });

        $(document).on('click', '.addButton', function () {
            if (pa <= 5) {
                $('#paid_by_1, #pcc_type_1, #bank_account_1').select2('destroy');
                var phtml = $('#payments').html(), update_html = phtml.replace(/_1/g, '_' + pa);
                pi = 'amount_' + pa;
                $('#multi-payment').append('<button type="button" class="close close-payment" style="margin: -10px 0px 0 0;"><i class="fa fa-2x">&times;</i></button>' + update_html);
                $('#paid_by_1, #pcc_type_1, #bank_account_1, #paid_by_' + pa + ', #pcc_type_' + pa, '#other_cur_paid_' + pa, '#bank_account_' + pa).select2({minimumResultsForSearch: 6});
				$('select').select2();
                read_card();
                pa++;
            } else {
                bootbox.alert('<?= lang('max_reached') ?>');
                return false;
            }
            $('.paid_by').trigger('change');
            $('#paymentModal').css('overflow-y', 'scroll');
        });

        $(document).on('click', '.close-payment', function () {
            $(this).next().remove();
			$(this).next().remove();
            $(this).remove();
            pa--;
        });

		$(".calExchangerate").on('focus', function(){
            var amount = $(this).val();
            var ownRate = $(this).attr('rate');
            calExchangerate(amount, ownRate);
        });

		function calExchangerate(amount, ownRate){
            $(".calExchangerate").each(function(i){
                var rate = $(this).attr('rate');
                if(ownRate != rate){
                    if(ownRate > 1 && rate == 1){
                        var ex = formatMoney(amount/ownRate);
                        $(this).val(ex);
                    }else{
                        var ex = formatMoney(amount*rate);
                        $(this).val(ex);
                    }
                }
            });
        }
        $(".num_suspend").click(function(){
            $(".suspend-add input").val($(this).val());
        });
        // function calculate payment when we focus payment button.
        $(document).on('focus keyup paste', '.amount, .currencies_payment', function () {
            pi = $(this).attr('id');
            calculateTotals();
        }).on('blur', '.amount, .currencies_payment', function () {
            calculateTotals();
        });
        function calculateTotals() {
            var other_curr_amt = 0;
            var total_paying = 0;
            var ia = $(".amount");
            $.each(ia, function (i) {
                total_paying += parseFloat($(this).val());
            });

            $(".currencies_payment").each(function(i){
                other_curr_amt += parseFloat(($(this).val() / $(this).attr('rate')));
                if(other_curr_amt > 0){total_paying += parseFloat(other_curr_amt);}
            });
            if(other_curr_amt > 0){$('.other_cur_paid').val($(".currencies_payment").val());}

            $('#total_paying').text(formatMoney(total_paying));
            $(".curr_total_p").each(function(i){
                var rate = $(this).attr('rate');
                if(!isNaN(total_paying)){
                    var t = formatMoney(total_paying * rate);
                    $(this).html(t);
                }else{
					$(this).html(formatMoney(0));
				}
            });
            <?php if($pos_settings->rounding) { ?>
            $('#amt_balance').text(formatMoney(total_paying - round_total));
            $('#balance' + pi).val(formatDecimal(total_paying - round_total));
            $(".curr_balance").each(function(i){
                $(this).html(total_paying);
            });
            total_paid = total_paying;
            grand_total = round_total;
            <?php } else { ?>
            $('#amt_balance').text(formatMoney(total_paying - gtotal));
            $('#balance' + pi).val(formatDecimal(total_paying - gtotal));
            $(".curr_balance").each(function(i){
                var rate = $(this).attr('rate');
                if(!isNaN(total_paying)){
                    var b = formatMoney((total_paying -gtotal) * rate);
                    $(this).html(b);
					b = b.replace('-', '');
					//$("#other_cur_paid").val(b);
                }else{
					//$(this).html(formatMoney(0));
					//$("#other_cur_paid").val('');
				}
            });
            total_paid = total_paying;
            grand_total = gtotal;
            <?php } ?>
        }

        $("#add_item").autocomplete({
            source: function (request, response) {
                if (!$('#poscustomer').val()) {
                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    //response('');
                    $('#add_item').focus();
                    return false;
                }
				var test = request.term;
				if($.isNumeric(test)){
					$.ajax({
						type: 'get',
						url: '<?= site_url('sales/suggests'); ?>',
						dataType: "json",
						data: {
							term: request.term,
							warehouse_id: $("#poswarehouse").val(),
							customer_id: $("#poscustomer").val()
						},
						success: function (data) {
							response(data);


						}
					});
				}else{
					$.ajax({
						type: 'get',
						url: '<?= site_url('sales/suggestions'); ?>',
						dataType: "json",
						data: {
							term: request.term,
							warehouse_id: $("#poswarehouse").val(),
							customer_id: $("#poscustomer").val()
						},
						success: function (data) {
							response(data);

						}
					});
				}

            },
            minLength: 1,
            autoFocus: false,
            delay: 300,
            response: function (event, ui) {

                if ($(this).val().length >= 16 && ui.content[0].id == 0)
				{
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {

                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                //*********add autocomplete prepend item*******//
                var susp_id = $('#suspend_id').val();
	            var table_no = $('#table_no').val();
	            var item_row = $('#posTable tbody tr').length;
	            wh = $('#poswarehouse').val(),
	            cu = $('#poscustomer').val();
	            var subtotal = $('#total').html();
	            var code = ui.item.row.code;
				
                $.ajax({
					type: "get",
					url: "<?= site_url('pos/getProductDataByCode') ?>",
					data: { code: code, warehouse_id: wh, customer_id: cu, suspend_id: susp_id, item_rows: item_row, sub_total: subtotal},
					dataType: "json",
					success: function (data) {
						
						if (data !== null) {
							var item_id  = data['item_id'];
							var image    = "<?php echo site_url();?>assets/uploads/thumbs/"+data['image'];
							var title    = data['row']['name'];
							var code     = data['row']['code'];
							var total    = data['sub_total'];
							/* zz */

							var item ='<button id="'+code+'" type="button" value="'+code+'" title="" class="btn-prni btn-default product pos-tip" data-container="body" data-original-title="'+title+'"><img src="'+image+'" alt="'+title+'" style="width: 60px; height: 60px;" class="img-rounded"/><span>'+title+'</span></button>';

						$('#product-sale-view').prepend(item);
							/*var suspend_html ='<button id="p' + item_id + ' ' +code+'" type="button" value="'+code+'" title="'+title+' ('+ui.item.row.code+')" class="btn-prni btn-default product pos-tip" data-container="body" data-original-title="'+title+'"><img src="'+image+'" alt="'+title+'" style="width: 60px; height: 60px;" class="img-rounded"/><span>'+title.substring(0,15)+'...('+formatMoney(ui.item.row.price)+')</span></button>';
							$('#product-sale-view').prepend(suspend_html); */
							}
						}
					});
                //*********add autocomplete prepend item********//
                if (ui.item.id !== 0) {
                    var product_type = ui.item.row.type;
					if (product_type == 'digital') {
						$.ajax({
							type: 'get',
							url: '<?= site_url('sales/getDigitalPro'); ?>',
							dataType: "json",
							data: {
								id: ui.item.item_id
							},
							success: function (result) {
								$.each( result, function(key, value) {
									var row = add_invoice_item(value);
									if (row)
										$(this).val('');
								});
							}
						});
						$(this).val('');
					} else {
						
						var row = add_invoice_item(ui.item);
						if (row)
							$(this).val('');
					}
                } else {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>');
					$('#add_item').focus();
					$(this).val('');
                }
            }


        });


        $(document.body).bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $('#add_item').focus();
            }
        });


        <?php if($pos_settings->tooltips) { echo '$(".pos-tip").tooltip();'; } ?>

        $('#product-list, #suspend-slider, #category-list, #subcategory-list, #suspend-list, .scroll_F').perfectScrollbar({suppressScrollX: true});
        $('select, .select').select2({minimumResultsForSearch: 6});
        $('.rquantity').focusout(function(){
			
        });

		$(document).on('click', '.product', function (e) {
            $('#modal-loading').show();
            var susp_id = $('#suspend_id').val();
            var table_no = $('#table_no').val();
            var item_row = $('#posTable tbody tr').length;
            code = $(this).val(),
            wh = $('#poswarehouse').val(),
            cu = $('#poscustomer').val();
            var subtotal = $('#total').html();
            $.ajax({
                type: "get",
                url: "<?= site_url('pos/getProductDataByCode') ?>",
                data: {code: code, warehouse_id: wh, customer_id: cu, suspend_id: susp_id, item_rows: item_row, sub_total: subtotal},
                dataType: "json",
                success: function (data) {
					
                    e.preventDefault();
                    if (data !== null) {
						var item_id = data['item_id'];
						var image = "<?php echo site_url();?>assets/uploads/thumbs/"+data['image'];
						var title = data['row']['name'];
						var code = data['row']['code'];
                        var total = data['sub_total'];
                        var item_price = data['item_price'];
                        
						
						var qty = 0;
						qty += data['row']['qty'];
						
						if (data.row.id !== 0) {
							var product_type = data.row.type;
							if (product_type == 'digital') {

								$.ajax({
									type: 'get',
									url: '<?= site_url('sales/getDigitalPro'); ?>',
									dataType: "json",
									data: {
										id: data.row.id
									},
									success: function (result) {
										$.each( result, function(key, value) {
											var row = add_invoice_item(value);
											if (row)
												$(this).val('');
										});
									}
								});
								$(this).val('');
							}else{

								//*******Addition*******//
								var arr = [];
								var arr_qty = qty;
								var condition = true;
								$.each($("#product-sale-view button"),function(i,e){
									arr.push($(this).attr("id"));

								});

								$.each(arr,function(i,e){
									if(code == e){
										condition = false;
										var c_qty = $("#"+e).children("span").find(".qty").text();
										arr_qty = Number(c_qty)+Number(qty);
										$("#"+e).children("span").find(".qty").text(arr_qty);
									}
                                });

								var item ='<button id="'+code+'" type="button" value="'+code+'" title="'+title+'" class="btn-prni btn-default product pos-tip edit" data-container="body" data-original-title="'+title+'"><img src="'+image+'" alt="'+title+'" style="width: 60px; height: 60px;" class="img-rounded"/><span>Qty :<i class="qty">'+(arr_qty)+'</i> ($ '+item_price+') '+title+'</span></button>';

								var suspend_html = '<p> '+ table_no +'</p>';
									suspend_html += '<div class="sup_number'+susp_id+'">('+(item_row+1)+')</div>';
									suspend_html += '<br/>'+formatMoney(total);
								$('.wrap_suspend'+susp_id).html(suspend_html);
								if(condition == true){
									$('#product-sale-view').prepend(item);
								}
								//*******Addition*******//

								add_invoice_item(data);
							}
						}


                        $('#modal-loading').hide();
                    } else {
                        //audio_error.play();
                        bootbox.alert('<?= lang('no_match_found') ?>');
                        $('#modal-loading').hide();
                    }
                }
            });
        });

        $(document).on('click', '.category', function () {

            if (cat_id != $(this).val()) {
				$('#box-item').remove();
				$('#box-item #box-item').remove();
                $('#open-category').click();
                $('#modal-loading').show();
                cat_id = $(this).val();
                $.ajax({
                    type: "get",
                    url: "<?= site_url('pos/ajaxcategorydata'); ?>",
                    data: {category_id: cat_id},
                    dataType: "json",
                    success: function (data) {

                        $('#slide_item').hide();
                        var newPrs = $('<div id=box-item ></div>');
                        newPrs.html(data.products);
                        newPrs.appendTo("#item-list");
                        $('#subcategory-list').empty();
                        var newScs = $('<div></div>');
                        newScs.html(data.subcategories);
                        newScs.appendTo("#subcategory-list");
                        tcp = data.tcp;
                    }
                }).done(function () {
                    p_page = 'n';
                    $('#category-' + cat_id).addClass('active');
                    $('#category-' + ocat_id).removeClass('active');
                    ocat_id = cat_id;
                    $('#modal-loading').hide();
					var exist_slider= $('#check-slider-exist').text();
					if(exist_slider==5){
						$('.btn_gift_card').show();
					}
                });
            }
        });
        $('#category-' + cat_id).addClass('active');

        $(document).on('click', '.subcategory', function () {

            if (sub_cat_id != $(this).val()) {
				$('#box-item').remove();
				$('#box-item #box-item').remove();
                $('#open-subcategory').click();
                $('#modal-loading').show();
                sub_cat_id = $(this).val();
                $.ajax({
                    type: "get",
                    url: "<?= site_url('pos/ajaxproducts'); ?>",
                    data: {category_id: cat_id, subcategory_id: sub_cat_id, per_page: p_page},
                    dataType: "html",
                    success: function (data) {
                        $('#slide_item').hide();
                        var newPrs = $('<div id=box-item ></div>');
                        newPrs.html(data);
                        newPrs.appendTo("#item-list");
                    }
                }).done(function () {
                    p_page = 'n';
                    $('#subcategory-' + sub_cat_id).addClass('active');
                    $('#subcategory-' + osub_cat_id).removeClass('active');
                    $('#modal-loading').hide();
                });
            }
        });

        $('#next').click(function () {
            if (p_page == 'n') {
                p_page = 0
            }
            p_page = p_page + <?php echo $pos_settings->pro_limit; ?>;
            if (tcp >= <?php echo $pos_settings->pro_limit; ?> && p_page < tcp) {
                $('#box-item').remove();
				$('#box-item #box-item').remove();
				$('#modal-loading').show();
                $.ajax({
                    type: "get",
                    url: "<?= site_url('pos/ajaxproducts'); ?>",
                    data: {category_id: cat_id, subcategory_id: sub_cat_id, per_page: p_page},
                    dataType: "html",
                    success: function (data) {
                        $('#slide_item').hide();
                        var newPrs = $('<div id=box-item ></div>');
                        newPrs.html(data);
                        newPrs.appendTo("#item-list");
                    }
                }).done(function () {
                    $('#modal-loading').hide();
                });
            } else {
                p_page = p_page - <?php echo $pos_settings->pro_limit; ?>;
            }
        });

        $('#previous').click(function () {
            if (p_page == 'n') {
                p_page = 0;
            }
            if (p_page != 0) {
				$('#box-item').remove();
				$('#box-item #box-item').remove();
                $('#modal-loading').show();
                p_page = p_page - <?php echo $pos_settings->pro_limit; ?>;
                if (p_page == 0) {
                    p_page = 'n'
                }
                $.ajax({
                    type: "get",
                    url: "<?= site_url('pos/ajaxproducts'); ?>",
                    data: {category_id: cat_id, subcategory_id: sub_cat_id, per_page: p_page},
                    dataType: "html",
                    success: function (data) {
                        $('#slide_item').hide();
                        var newPrs = $('<div id=box-item ></div>');
                        newPrs.html(data);
                        newPrs.appendTo("#item-list");
                    }

                }).done(function () {
                    $('#modal-loading').hide();
                });

            }
        });

		$('#print_depre').click(function () {
			PopupPayments();
		});

		$('#export_depre').click(function () {
			var customer_id = $('#poscustomer').val();
			var customer_name = '';
			var customer_address = '';
			var customer_tel ='';
			var customer_mail = '';

			$.ajax({
                    type: "get",
                    url: "<?= site_url('pos/getCustomerInfo'); ?>",
                    data: {customer_id: customer_id},
                    dataType: "html",
					async: false,
                    success: function (data) {
						var obj = jQuery.parseJSON(data);
						customer_name = obj.company;
						customer_address = obj.address+', '+obj.city+', '+obj.state;
						customer_tel = obj.phone;
						customer_mail = obj.email;
					}
                });
			var issued_date = $('.current_date').val();
			var myexport = '<tbody>';
				myexport+= 		'<tr><td colspan="7" style="vertical-align:middle;"><center><h4 style="font-family:Verdana,Geneva,sans-serif; font-weight:bold;"><?= lang("loan_amortization_schedule") ?></h4></center></td></tr>';
				myexport+=		'<tr>';
				myexport+=			'<td colspan="2" width="25%"  style="padding-left:50px;"><?= lang('issued_date') ?></td>';
				myexport+=			'<td colspan="2" width="25%"><?= lang(": ") ?>'+ issued_date +'</td>';
				myexport+=			'<td colspan="3" width="50%">&nbsp;</td>';
				myexport+=		'</tr>';
				myexport+=		'<tr>';
				myexport+=			'<td colspan="2" style="padding-left:50px;"><?= lang('customer') ?></td>';
				myexport+=			'<td colspan="2"><?= lang(": ") ?>'+ customer_name +'</td>';
				myexport+=			'<td style="text-align:right; padding-right:30px;"><?= lang('address') ?></td>';
				myexport+=			'<td colspan="2"><?= lang(": ") ?>'+ customer_address +'</td>';
				myexport+=		'</tr>'+
								'<tr>'+
									'<td colspan="2" style="padding-left:50px;"><?= lang('tel') ?></td>'+
									'<td colspan="2"><?= lang(": ") ?>'+ customer_tel +'</td>'+
									'<td style="text-align:right; padding-right:30px;"><?= lang('email') ?></td>'+
									'<td colspan="2"><?= lang(": ") ?>'+ customer_mail +'</td>'+
								'</tr>';
				myexport+=		'<tr style="height:50px; vertical-align:middle;">'+
									'<th class="td_bor_style"><?= lang('No') ?></th>'+
									'<th class="td_bor_style td_align_center"><?= lang('item_code') ?></th>'+
									'<th colspan="2" class="td_bor_style"><?= lang('decription') ?></th>'+
									'<th class="td_bor_style"><?= lang('unit_price') ?></th>'+
									'<th class="td_bor_style"><?= lang('qty') ?></th>'+
									'<th class="td_bor_botton"><?= lang('amount') ?></th>'+
								  '</tr>';
			var type = $('#depreciation_type_1').val();
			var no = 0;
			var total_amt = 0;
			var total_amount = $('#quick-payable').text()-0;
			var rate_kh = $('#other_cur_paid').attr('rate')-0;
			var kh_down = $('#other_cur_paid').val()-0;
			var kh_2_us = kh_down/rate_kh;
			var us_down = $('#amount_1').val()-0;
			var down_pay = kh_2_us + us_down;
			var interest_rate = formatDecimal($('#depreciation_rate_1').val()-0);
			var term_ = formatDecimal($('#depreciation_term_1').val()-0);
			var counter = 0;
			var items = $('.edit').length;
			$('.edit').each(function(){
				if(counter < items-1){
					no += 1;
					var parent = $(this).parent().parent();
					var unit_price = parent.find('.realuprice').val();
					var qtt = parent.find('.rquantity').val();
					var amt = unit_price * qtt;
					total_amt += amt;
					myexport +=	'<tr>'+
									'<td class="td_color_light td_align_center" align="center">'+ no +'</td>'+
									'<td class="td_color_light">'+ parent.find('.rcode').val() +'</td>'+
									'<td colspan="2" class="td_color_light td_align_center">'+ parent.find('.rname').val() +'</td>'+
									'<td class="td_color_light td_align_right" align="right">$ &nbsp;'+ formatMoney(unit_price) +'</td>'+
									'<td class="td_color_light" align="right">'+ qtt +'</td>'+
									'<td class="td_color_bottom_light td_align_right" align="right">$ &nbsp;'+ formatMoney(amt) +'</td>'+
								'</tr>';
				}
				counter += 1;
			});
			var loan_amount = total_amt;
			//if(type != 4){
				loan_amount = total_amt - down_pay;
			//}
				if(down_pay != 0 || down_pay != ''){
			myexport+=			'<tr>'+
									'<td colspan="6" style="text-align:right; padding:5px;"><?= lang('total_amount') ?></td>'+
									'<td class="td_align_right" align="right"><b>$ &nbsp;'+ formatMoney(total_amt) +'</b></td>'+
								'</tr>';
			myexport+=			'<tr>'+
									'<td colspan="6" style="text-align:right; padding:5px;"><?= lang('down_payment') ?></td>'+
									'<td class="td_align_right" align="right"><b>$ &nbsp;'+ formatMoney(down_pay) +'</b></td>'+
								'</tr>';
				}
			myexport+=			'<tr>'+
									'<td colspan="6" style="text-align:right; padding:5px;"><?= lang('loan_amount') ?></td>'+
									'<td class="td_align_right" align="right"><b>$ &nbsp;'+ formatMoney(loan_amount) +'</b></td>'+
								'</tr>'+
								'<tr>'+
									'<td colspan="6" style="text-align:right; padding:5px;"><?= lang('interest_rate_per_month') ?></td>'+
									'<td class="td_align_right" align="right"><b>'+ formatMoney(interest_rate/12) +'&nbsp; %</b></td>'+
								'</tr>';
			myexport+=			'<tr><td colspan="7" style="height:70px; vertical-align:middle; text-align:center; font-weight:bold; font-size:14px;"><?= lang('payment_term')?></td></tr>';
			myexport+=			'<tr style="height:50px; vertical-align:middle;">'+
									'<th width="10%" class="td_bor_style"><?= lang('Pmt No.') ?></th>'+
									'<th width="15%" class="td_bor_style"><?= lang('payment_date') ?></th>';
									if(type == 2){
			myexport+=				'<th width="10%" class="td_bor_style"><?= lang('rate') ?></th>';
			myexport+=				'<th width="10%" class="td_bor_style"><?= lang('percentage') ?></th>';
			myexport+=				'<th width="10%" class="td_bor_style"><?= lang('payment') ?></th>'+
									'<th width="15%" class="td_bor_style"><?= lang('total_payment') ?></th>';
									}else{
			myexport+=				'<th width="10%" class="td_bor_style"><?= lang('interest') ?></th>'+
									'<th width="10%" class="td_bor_style"><?= lang('principle') ?></th>'+
									'<th width="15%" class="td_bor_style"><?= lang('total_payment') ?></th>';
									}
			myexport+=				'<th width="10%" class="td_bor_style"><?= lang('balance') ?></th>'+
									'<th width="25%" class="td_bor_botton"><?= lang('note') ?></th>'+
								  '</tr>';
			var k = 0;
			var total_interest = 0;
			var total_princ = 0;
			var amount_total_pay = 0;
			var total_pay_ = 0;
			$('.dep_tbl .no').each(function(){
				k += 1;
				var tr = $(this).parent().parent();
				var balance = formatMoney(tr.find('.balance').val()-0);
			if(type == 2){
				total_interest += formatDecimal(tr.find('.rate').val()-0);
				total_princ += formatDecimal(tr.find('.percentage').val()-0);
				amount_total_pay += formatDecimal(tr.find('.total_payment').val()-0);
			}else{
				total_interest += formatDecimal(tr.find('.interest').val()-0);
				total_princ += formatDecimal(tr.find('.principle').val()-0);
			}
				total_pay_ += formatDecimal(tr.find('.payment_amt').val()-0);
			myexport+=			'<tr>'+
									'<td class="td_color_light td_align_center" align="center">'+ k +'</td>'+
									'<td class="td_color_light td_align_center" align="center">'+ tr.find('.dateline').val() +'</td>';
				if(type == 2){
			myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.rate').val()-0) +'</td>';
			myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.percentage').val()-0) +'</td>';
			myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.payment_amt').val()-0) +'</td>';
			myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.total_payment').val()-0) +'</td>';
				}else{
			myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.interest').val()-0) +'</td>';
			myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.principle').val()-0) +'</td>';
			myexport+=				'<td class="td_color_light td_align_center" align="right">$ &nbsp;'+ formatMoney(tr.find('.payment_amt').val()-0) +'</td>';
				}
			myexport+=				'<td class="td_color_light td_align_right" align="right">$ &nbsp;'+ balance +'</td>'+
									'<td class="td_color_bottom_light" style="padding-left:20px;">'+ tr.find('.note').val() +'</td>'+
								'</tr>';
			});
			if(type == 2){
			myexport+=			'<tr>'+
									'<td style="text-align:right; padding:5px;"><b> Total </b></td>'+
									'<td style="text-align:right; padding:5px;"> &nbsp; </td>'+
									'<td style="text-align:right; padding:5px;"><b>$ &nbsp;'+ formatMoney(total_princ) +'</b></td>'+
									'<td style="text-align:right; padding:5px;"><b>$ &nbsp;'+ formatMoney(total_pay_) +'</b></td>'+
									'<td style="text-align:right; padding:5px;"><b>$ &nbsp;'+ formatMoney(amount_total_pay) +'</b></td>'+
									'<td style="text-align:right; padding:5px;"> &nbsp; </td>'+
									'<td style="text-align:right; padding:5px;"> &nbsp; </td>'+
								'</tr>';
			}else{
			myexport+=			'<tr>'+
									'<td style="text-align:right; padding:5px; border-top:1px solid black;"><b> Total </b></td>'+
									'<td style="text-align:right; padding:5px; border-top:1px solid black;"> &nbsp; </td>'+
									'<td style="text-align:right; padding:5px; border-top:1px solid black;"><b>$ &nbsp;'+ formatMoney(total_interest) +'</b></td>'+
									'<td style="text-align:right; padding:5px; border-top:1px solid black;"><b>$ &nbsp;'+ formatDecimal(total_princ) +'</b></td>'+
									'<td style="text-align:right; padding:5px; border-top:1px solid black;"><b>$ &nbsp;'+ formatMoney(total_pay_) +'</b></td>'+
									'<td style="text-align:right; padding:5px; border-top:1px solid black;"> &nbsp; </td>'+
									'<td style="text-align:right; padding:5px;"> &nbsp; </td>'+
								'</tr>';
			}
			myexport+= '</tbody>';
			$('#export_tbl').append(myexport);
			var htmltable= document.getElementById('export_tbl');
			var html = htmltable.outerHTML;
			window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));
		});

		function PopupPayments() {
			var customer_id = $('#poscustomer').val();
			var customer_name = '';
			var customer_address = '';
			var customer_tel ='';
			var customer_mail = '';

			$.ajax({
                    type: "get",
                    url: "<?= site_url('pos/getCustomerInfo'); ?>",
                    data: {customer_id: customer_id},
                    dataType: "html",
					async: false,
                    success: function (data) {
						var obj = jQuery.parseJSON(data);
						customer_name = obj.company;
						customer_address = obj.address+', '+obj.city+', '+obj.state;
						customer_tel = obj.phone;
						customer_mail = obj.email;
                    }
                });

			var mywindow = window.open('', 'erp_pos_print', 'height=auto,max-width=480,min-width=250px');
			mywindow.document.write('<html><head><title>Print</title>');
			mywindow.document.write('<link rel="stylesheet" href="<?= $assets ?>styles/helpers/bootstrap.min.css" type="text/css" />');
			mywindow.document.write('</head><body >');
			mywindow.document.write('<center>');
			var issued_date = $('.current_date').val();


			mywindow.document.write('<center><h4 style="font-family:Verdana,Geneva,sans-serif;"><?= lang("loan_amortization_schedule") ?></h4></center>');
			mywindow.document.write('<table class="table-condensed" style="width:95%; font-family:Verdana,Geneva,sans-serif; font-size:12px; padding-bottom:10px;">'+
										'<tr>'+
											'<td colspan="2" style="width:100% !important;"><?= lang('issued_date') ?> <?= lang(": ") ?>'+ issued_date +'</td>'+
										'</tr>'+
										'<tr>'+
											'<td style="width:50% !important;"><?= lang('customer') ?> <?= lang(": ") ?>'+ customer_name +'</td>'+
											'<td style="width:50% !important;"><?= lang('address') ?> <?= lang(": ") ?>'+ customer_address +'</td>'+
										'</tr>'+
										'<tr>'+
											'<td style="width:50% !important;"><?= lang('tel') ?> <?= lang(": ") ?>'+ customer_tel +'</td>'+
											'<td style="width:50% !important;"><?= lang('email') ?> <?= lang(": ") ?>'+ customer_mail +'</td>'+
										'</tr>'+
									'</table><br/>'
								  );
			mywindow.document.write('<table border="2px" class="table table-bordered table-condensed table_shape" style="width:95%; font-family:Verdana,Geneva,sans-serif; font-size:12px; border-collapse:collapse;">'+
										'<thead>'+
											 '<tr>'+
												'<th width="5%" class="td_bor_style"><?= lang('No') ?></th>'+
												'<th width="15%" class="td_bor_style td_align_center"><?= lang('item_code') ?></th>'+
												'<th width="45%" class="td_bor_style"><?= lang('decription') ?></th>'+
												'<th width="10%" class="td_bor_style"><?= lang('unit_price') ?></th>'+
												'<th width="10%" class="td_bor_style"><?= lang('qty') ?></th>'+
												'<th width="15%" class="td_bor_botton"><?= lang('amount') ?></th>'+
											  '</tr>'+
										'</thead>'+
											'<tbody>');
											var type = $('#depreciation_type_1').val();
											var no = 0;
											var total_amt = 0;
											var total_amount = $('#quick-payable').text()-0;
											var rate_kh = $('#other_cur_paid').attr('rate')-0;
											var kh_down = $('#other_cur_paid').val()-0;
											var kh_2_us = kh_down/rate_kh;
											var us_down = $('#amount_1').val()-0;
											var down_pay = kh_2_us + us_down;
											var interest_rate = Number($('#depreciation_rate_1').val()-0);
											var term_ = Number($('#depreciation_term_1').val()-0);
											var counter = 0;
											var items = $('.edit').length;
											$('.edit').each(function(){
												if(counter < items-1){
													no += 1;
													var parent = $(this).parent().parent();
													var unit_price = parent.find('.realuprice').val();
													var qtt = parent.find('.rquantity').val();
													var amt = unit_price * qtt;
													total_amt += amt;
				mywindow.document.write(			'<tr>'+
														'<td class="td_color_light td_align_center" >'+ no +'</td>'+
														'<td class="td_color_light">'+ parent.find('.rcode').val() +'</td>'+
														'<td class="td_color_light td_align_center">'+ parent.find('.rname').val() +'</td>'+
														'<td class="td_color_light td_align_right">$ '+ formatMoney(unit_price) +'</td>'+
														'<td class="td_color_light td_align_center">'+ qtt +'</td>'+
														'<td class="td_color_bottom_light td_align_right">$ '+ formatMoney(amt) +'</td>'+
													'</tr>');
												}
												counter += 1;
											});
											var loan_amount = total_amt;
											//if(type != 4){
												loan_amount = total_amt - down_pay;
											//}
												if(down_pay != 0 || down_pay != ''){
			mywindow.document.write(			'<tr>'+
													'<td colspan="5" style="text-align:right; padding:5px;"><?= lang('total_amount') ?></td>'+
													'<td class="td_align_right"><b>$ '+ formatMoney(total_amt) +'</b></td>'+
												'</tr>');
			mywindow.document.write(			'<tr>'+
													'<td colspan="5" style="text-align:right; padding:5px;"><?= lang('down_payment') ?></td>'+
													'<td class="td_align_right"><b>$ '+ formatMoney(down_pay) +'</b></td>'+
												'</tr>');
												}
			mywindow.document.write(			'<tr>'+
													'<td colspan="5" style="text-align:right; padding:5px;"><?= lang('loan_amount') ?></td>'+
													'<td class="td_align_right"><b>$ '+ formatMoney(loan_amount) +'</b></td>'+
												'</tr>'+
												'<tr>'+
													'<td colspan="5" style="text-align:right; padding:5px;"><?= lang('interest_rate_per_month') ?></td>'+
													'<td class="td_align_right"><b>'+ formatMoney(interest_rate/12) +' %</b></td>'+
												'</tr>');
			mywindow.document.write(		'</tbody>'+
									'</table><br/>'
									);
			mywindow.document.write('<div class="payment_term"><b><?= lang('payment_term')?></b></div>');
			mywindow.document.write('<table border="2px" class="table table-bordered table-condensed table_shape" style="width:95%; font-family:Verdana,Geneva,sans-serif; font-size:12px; border-collapse:collapse;">'+
										 '<thead>'+
											  '<tr>'+
												'<th width="10%" class="td_bor_style"><?= lang('Pmt No.') ?></th>'+
												'<th width="15%" class="td_bor_style"><?= lang('payment_date') ?></th>'
									);
											if(type == 2){
			mywindow.document.write(			'<th width="10%" class="td_bor_style"><?= lang('rate') ?></th>');
			mywindow.document.write(			'<th width="10%" class="td_bor_style"><?= lang('percentage') ?></th>');
			mywindow.document.write(			'<th width="10%" class="td_bor_style"><?= lang('payment') ?></th>'+
												'<th width="15%" class="td_bor_style"><?= lang('total_payment') ?></th>'
									);
											}else{
			mywindow.document.write(			'<th width="10%" class="td_bor_style"><?= lang('interest') ?></th>'+
												'<th width="10%" class="td_bor_style"><?= lang('principle') ?></th>'+
												'<th width="15%" class="td_bor_style"><?= lang('total_payment') ?></th>'
									);
											}
			mywindow.document.write(			'<th width="10%" class="td_bor_style"><?= lang('balance') ?></th>'+
												'<th width="25%" class="td_bor_botton"><?= lang('note') ?></th>'+
											  '</tr>'+
										'</thead>'+
										'<tbody>');
										var k = 0;
										var total_interest = 0;
										var total_princ = 0;
										var amount_total_pay = 0;
										var total_pay_ = 0;
										$('.dep_tbl .no').each(function(){
											k += 1;
											var tr = $(this).parent().parent();
											var balance = formatMoney(tr.find('.balance').val()-0);
										if(type == 2){
											total_interest += Number(tr.find('.rate').val()-0);
											total_princ += Number(tr.find('.percentage').val()-0);
											amount_total_pay += Number(tr.find('.total_payment').val()-0);
										}else{
											total_interest += Number(tr.find('.interest').val()-0);
											total_princ += Number(tr.find('.principle').val()-0);
										}
											total_pay_ += Number(tr.find('.payment_amt').val()-0);
			mywindow.document.write(		'<tr>'+
													'<td class="td_color_light td_align_center">'+ k +'</td>'+
													'<td class="td_color_light td_align_center">'+ tr.find('.dateline').val() +'</td>'
											);
											if(type == 2){
			mywindow.document.write(				'<td class="td_color_light td_align_center">$ '+ formatMoney(tr.find('.rate').val()-0) +'</td>');
			mywindow.document.write(				'<td class="td_color_light td_align_center">$ '+ formatMoney(tr.find('.percentage').val()-0) +'</td>');
			mywindow.document.write(				'<td class="td_color_light td_align_center">$ '+ formatMoney(tr.find('.payment_amt').val()-0) +'</td>');
			mywindow.document.write(				'<td class="td_color_light td_align_center">$ '+ formatMoney(tr.find('.total_payment').val()-0) +'</td>');
											}else{
			mywindow.document.write(				'<td class="td_color_light td_align_center">$ '+ formatMoney(tr.find('.interest').val()-0) +'</td>');
			mywindow.document.write(				'<td class="td_color_light td_align_center">$ '+ formatMoney(tr.find('.principle').val()-0) +'</td>');
			mywindow.document.write(				'<td class="td_color_light td_align_center">$ '+ formatMoney(tr.find('.payment_amt').val()-0) +'</td>');
											}
			mywindow.document.write(				'<td class="td_color_light td_align_right">$ '+ balance +'</td>'+
													'<td class="td_color_bottom_light">'+ tr.find('.note').val() +'</td>'+
												'</tr>');
										});
										if(type == 2){
			mywindow.document.write(			'<tr>'+
													'<td style="text-align:right; padding:5px;" colspan="2"><b> Total </b></td>'+
													'<td style="text-align:left; padding:5px;"> &nbsp; </td>'+
													'<td style="text-align:left; padding:5px;"><b>$ '+ formatMoney(total_princ) +'</b></td>'+
													'<td style="text-align:left; padding:5px;"><b>$ '+ formatMoney(total_pay_) +'</b></td>'+
													'<td style="text-align:left; padding:5px;"><b>$ '+ formatMoney(amount_total_pay) +'</b></td>'+
													'<td style="text-align:left; padding:5px;"> &nbsp; </td>'+
													'<td style="text-align:left; padding:5px;"> &nbsp; </td>'+
												'</tr>');
										}else{
			mywindow.document.write(			'<tr>'+
													'<td style="text-align:right; padding:5px;"><b> Total </b></td>'+
													'<td style="text-align:left; padding:5px;"> &nbsp; </td>'+
													'<td style="text-align:left; padding:5px;"><b>$ '+ formatMoney(total_interest) +'</b></td>'+
													'<td style="text-align:left; padding:5px;"><b>$ '+ formatDecimal(total_princ) +'</b></td>'+
													'<td style="text-align:left; padding:5px;"><b>$ '+ formatMoney(total_pay_) +'</b></td>'+
													'<td style="text-align:left; padding:5px;"> &nbsp; </td>'+
													'<td style="text-align:left; padding:5px;"> &nbsp; </td>'+
												'</tr>');
										}
			mywindow.document.write(	'</tbody>'+
									'</table>'
									);

			mywindow.document.write('</center>');
			mywindow.document.write('</body></html>');
			mywindow.print();
			//mywindow.close();
			return true;
		}

        $(document).on('click', '.category', function () {
             if (cat_id != $(this).val()) {
				$('#box-item').remove();
				$('#box-item #box-item').remove();
                $('#open-category').click();
                $('#modal-loading').show();
                cat_id = $(this).val();
                $.ajax({
                    type: "get",
                    url: "<?= site_url('pos/ajaxcategorydata'); ?>",
                    data: {category_id: cat_id},
                    dataType: "json",
                    success: function (data) {
                        $('#slide_item').hide();
                        var newPrs = $('<div id=box-item ></div>');
                        newPrs.html(data.products);
                        newPrs.appendTo("#item-list");
                        $('#subcategory-list').empty();
                        var newScs = $('<div></div>');
                        newScs.html(data.subcategories);
                        newScs.appendTo("#subcategory-list");
                        tcp = data.tcp;
                    }
                }).done(function () {

                    p_page = 'n';
                    $('#category-' + cat_id).addClass('active');
                    $('#category-' + ocat_id).removeClass('active');
                    ocat_id = cat_id;
                    $('#modal-loading').hide();
                });
            }
        });
        $('#category-' + cat_id).addClass('active');

        if (sub_cat_id != $(this).val()) {
				$('#box-item').remove();
                $('#open-subcategory').click();
                $('#modal-loading').show();
                sub_cat_id = $(this).val();
                $.ajax({
                    type: "get",
                    url: "<?= site_url('pos/ajaxproducts'); ?>",
                    data: {category_id: cat_id, subcategory_id: sub_cat_id, per_page: p_page},
                    dataType: "html",
                    success: function (data) {
                        $('#slide_item').hide();
                        var newPrs = $('<div id=box-item ></div>');
                        newPrs.html(data);
                        newPrs.appendTo("#item-list");
                    }
                }).done(function () {

                    p_page = 'n';
                    $('#subcategory-' + sub_cat_id).addClass('active');
                    $('#subcategory-' + osub_cat_id).removeClass('active');
                    $('#modal-loading').hide();
                });
            }

		$('.chk_principle').on('change', function() {

		  if ($(this).is(':checked')) {
			    $.ajax({
                    type: "get",
                    url: "<?= site_url('pos/ajaxproducts'); ?>",
                    data: {category_id: cat_id, subcategory_id: sub_cat_id, per_page: p_page},
                    dataType: "html",
                    success: function (data) {
                        $('#slide_item').hide();
                        var newPrs = $('<div id=box-item ></div>');
                        newPrs.html(data);
                        newPrs.appendTo("#item-list");
                    }
                });
			define_depreciation(amount,rate,term,p_type,total_amount);
		  }else{

		  }
		});

		function define_depreciation(amount,rate,term,p_type,total_amount){

			tr += '<tr>';
			tr += '<th class="text-center"> <?= lang("Pmt No."); ?> </th>';
			tr += '<th class="text-center"> <?= lang("interest"); ?> </th>';
			tr += '<th class="text-center"> <?= lang("principal"); ?> </th>';
			tr += '<th class="text-center"> <?= lang("total_payment"); ?> </th>';
			tr += '<th class="text-center"> <?= lang("balance"); ?> </th>';
			tr += '<th class="text-center"> <?= lang("note"); ?> </th>';
			tr += '<th class="text-center"> <?= lang("payment_date"); ?> </th>';
			tr += '</tr>';

			var dateline ='';
			var principle = 0;
			var interest = 0;
			var balance = amount;
			var rate_amount = ((rate/100)/12);
			var payment = ((amount * rate_amount)*((Math.pow((1+rate_amount),term))/(Math.pow((1+rate_amount),term)-1)));
			var i=0;
			var k=1;
			var total_principle = 0;
			var total_payment = 0;
			for(i=0;i<term;i++){
				if(i== 0){
					interest = amount*((rate/100)/12);
					dateline = moment(new Date()).add(0,'months').format('DD/MM/YYYY');
				}else{
					interest = balance *((rate/100)/12);
					dateline = moment(new Date()).add((k-1),'months').format('DD/MM/YYYY');
				}
				principle = payment - interest;
				balance -= principle;
				if(balance <= 0){
					balance = 0;
				}
				tr += '<tr> <td class="text-center">'+ k +'<input type="hidden" name="no[]" id="no" class="no" value="'+ k +'" /></td> ';
				tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
				tr += '<td>'+ formatMoney(principle) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ principle +'"/></td>';
				tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';
				tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ formatDecimal(balance) +'"/></td>';
				tr += '<td> <input name="note[]" class="note form-control" id="'+i+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+i+'" width="90%"/></td>';
				tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /></td> </tr>';
				total_principle += principle;
				total_payment += payment;
				k++;
			}
			tr += '<tr> <td colspan="2"> <?= lang("Total"); ?> </td>';
			tr += '<td>'+ formatMoney(total_principle) +'</td>';
			tr += '<td>'+ formatMoney(total_payment) +'</td>';
			tr += '<td colspan="3"> &nbsp; </td> </tr>';
			$('.dep_tbl').show();
			$('#tbl_dep').html(tr);
			//$('#tbl_dep1').html(tr);
			$("#loan1").html(tr);
		}

		function depreciation(amount,rate,term,p_type,total_amount){
			var dateline = '';
			var d = new Date();
			if(p_type == ''){
				$('#print_').hide();
				$('#export_').hide();
				return false;
			}else{
				$('#print_').show();
				$('#export_').show();
				if(rate == '' || rate < 0) {
					if(term == '' || term <= 0) {
						$('.dep_tbl').hide();
						alert("Please choose Rate and Term again!");
						return false;
					}else{
						$('.dep_tbl').hide();
						alert("Please choose Rate again!");
						return false;
					}
				}else{
					if(term == '' || term <= 0) {
						$('.dep_tbl').hide();
						alert("Please choose Term again!");
						return false;
					}else{
						var tr = '';
						if(p_type == 1 || p_type == 3 || p_type == 4){
							tr += '<tr>';
							tr += '<th class="text-center"> <?= lang("Pmt No."); ?> </th>';
							tr += '<th class="text-center"> <?= lang("interest"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("principal"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("total_payment"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("balance"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("note"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("payment_date"); ?> </th>';
							tr += '</tr>';
						}else if(p_type == 2){
							tr += '<tr>';
							tr += '<th class="text-center"> <?= lang("period"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("rate"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("percentage"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("payment"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("total_payment"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("balance"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("note"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("dateline"); ?> </th>';
							tr += '</tr>';
						}
						if(p_type == 1){
							var principle = amount/term;
							var interest = 0;
							var balance = amount;
							var payment = 0;
							var i=0;
							var k=1;
							var total_principle = 0;
							var total_payment = 0;
							for(i=0;i<term;i++){
								if(i== 0){
									interest = amount*((rate/term)/100);
									dateline = moment(new Date()).add(0,'months').format('DD/MM/YYYY');
								}else{
									interest = balance *((rate/term)/100);
									dateline = moment(new Date()).add((k-1),'months').format('DD/MM/YYYY');
								}
								balance -= principle;
								if(balance <= 0){
									balance = 0;
								}
								payment = principle + interest;
								tr += '<tr> <td class="text-center">'+ k +'<input type="hidden" name="no[]" id="no" class="no" value="'+ k +'" /></td> ';
								tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
								tr += '<td>'+ formatMoney(principle) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
								tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';
								tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ formatDecimal(balance) +'"/></td>';
								tr += '<td> <input name="note[]" class="note form-control" id="'+i+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+i+'" width="90%"/></td>';
								tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /> </td> </tr>';
								total_principle += principle;
								total_payment += payment;
								k++;
							}
							tr += '<tr> <td colspan="2"> <?= lang("total"); ?> </td>';
							tr += '<td>'+ formatMoney(total_principle) +'</td>';
							tr += '<td>'+ formatMoney(total_payment) +'</td>';
							tr += '<td colspan="3"> &nbsp; </td> </tr>';
						}else if(p_type == 2) {
							var principle = 0;
							var interest = 0;
							var percent = 0;
							var balance = amount;
							var rate_amount = ((rate/100)/12);
							var payment = ((amount * rate_amount)*((Math.pow((1+rate_amount),term))/(Math.pow((1+rate_amount),term)-1)));
							var i=0;
							var k=1;
							var total_principle = 0;
							var total_payment = 0;
							for(i=0;i<term;i++){
								if(i== 0){
									interest = amount*((rate/100)/12);
									dateline = moment(new Date()).add(0,'months').format('DD/MM/YYYY');
									principle = payment - interest;
									percent = (principle / balance) * 100;
									balance -= principle;
									if(balance <= 0){
										balance = 0;
									}
									tr += '<tr> <td class="text-center">'+ k +'<input type="hidden" name="no[]" id="no" class="no" value="'+ k +'" /></td> ';
									tr += '<td><input type="text" name="rate[]" id="rate" class="rate" style="width:60px;" value="'+ formatDecimal(interest) +'"/><input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
									tr += '<td><input type="text" name="percentage[]" id="percentage" class="percentage" style="width:60px;" value="'+ percent.toFixed(4) +'"/><input type="hidden" name="percentage_[]" id="percentage_" class="percentage_" style="width:60px;" value="'+ percent +'"/></td>';
									tr += '<td><input type="text" name="pmt_principle[]" id="pmt_principle" class="pmt_principle" style="width:60px;" value="'+ formatDecimal(principle) +'" /><input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
									tr += '<td><input type="text" name="total_payment[]" id="total_payment" class="total_payment" style="width:60px;" value="'+ formatDecimal(payment) +'" readonly/><input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';
									tr += '<td><input type="text" name="amt_balance[]" id="amt_balance" class="amt_balance" style="width:60px;" value="'+ formatDecimal(balance) +'" readonly/><input type="hidden" name="balance[]" id="balance" class="balance" style="width:60px;" value="'+ formatDecimal(balance) +'"/></td>';
									tr += '<td> <input name="note[]" class="note form-control" id="'+i+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+i+'" width="90%"/></td>';
									tr += '<td><input type="text" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" size="6" /></td> </tr>';
								}else{
									interest = balance *((rate/100)/12);
									dateline = moment(new Date()).add((k-1),'months').format('DD/MM/YYYY');
									principle = payment - interest;
									percent = (principle / balance) * 100;
									balance -= principle;
									if(balance <= 0){
										balance = 0;
									}
									tr += '<tr> <td class="text-center">'+ k +'<input type="hidden" name="no[]" id="no" class="no" value="'+ k +'" /></td> ';
									tr += '<td><input type="text" name="rate[]" id="rate" class="rate" style="width:60px;" value="'+ formatDecimal(interest) +'"/><input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
									tr += '<td><input type="text" name="percentage[]" id="percentage" class="percentage" style="width:60px;" value="'+ percent.toFixed(4) +'"/><input type="hidden" name="percentage_[]" id="percentage_" class="percentage_" style="width:60px;" value="'+ percent +'"/></td>';
									tr += '<td><input type="text" name="pmt_principle[]" id="pmt_principle" class="pmt_principle" style="width:60px;" value="'+ formatDecimal(principle) +'" /><input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ formatDecimal(principle) +'"/></td>';
									tr += '<td><input type="text" name="total_payment[]" id="total_payment" class="total_payment" style="width:60px;" value="'+ formatDecimal(payment) +'" readonly/><input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';
									tr += '<td><input type="text" name="amt_balance[]" id="amt_balance" class="amt_balance" style="width:60px;" value="'+ formatDecimal(balance) +'" readonly/><input type="hidden" name="balance[]" id="balance" class="balance" style="width:60px;" value="'+ formatDecimal(balance) +'"/></td>';
									tr += '<td> <input name="note[]" class="note form-control" id="'+i+'" ></input><input type="hidden" name="note1[]" id="note1" class="note1_'+i+'" width="90%"/></td>';
									tr += '<td><input type="text" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" size="6" /></td> </tr>';
								}
								total_principle += principle;
								total_payment += payment;
								k++;
							}
							tr += '<tr> <td colspan="3"> <?= lang("Total"); ?> </td>';
							//tr += '<td><input type="text" name="total_percen" id="total_percen" class="total_percen" style="width:60px;" value="" readonly/></td>';
							tr += '<td><input type="text" name="total_pay" id="total_pay" class="total_pay" style="width:60px;" value="'+ formatDecimal(total_principle) +'" readonly/></td>';
							tr += '<td><input type="text" name="total_amount" id="total_amount" class="total_amount" style="width:60px;" value="'+ formatDecimal(total_payment) +'" readonly/></td>';
							tr += '<td colspan="3"> &nbsp; </td> </tr>';
						}else if(p_type == 3) {
							var principle = 0;
							var interest = 0;
							var balance = amount;
							var rate_amount = ((rate/100)/12);
							var payment = ((amount * rate_amount)*((Math.pow((1+rate_amount),term))/(Math.pow((1+rate_amount),term)-1)));
							var i=0;
							var k=1;
							var total_principle = 0;
							var total_payment = 0;
							for(i=0;i<term;i++){
								if(i== 0){
									interest = amount*((rate/100)/12);
									dateline = moment(new Date()).add(0,'months').format('DD/MM/YYYY');
								}else{
									interest = balance *((rate/100)/12);
									dateline = moment(new Date()).add((k-1),'months').format('DD/MM/YYYY');
								}
								principle = payment - interest;
								balance -= principle;
								if(balance <= 0){
									balance = 0;
								}
								tr += '<tr> <td class="text-center">'+ k +'<input type="hidden" name="no[]" id="no" class="no" value="'+ k +'" /></td> ';
								tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
								tr += '<td>'+ formatMoney(principle) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ principle +'"/></td>';
								tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ formatDecimal(payment) +'"/></td>';
								tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ formatDecimal(balance) +'"/></td>';
								tr += '<td> <input name="note[]" class="note form-control" id="'+i+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+i+'" width="90%"/></td>';
								tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /></td> </tr>';
								total_principle += principle;
								total_payment += payment;
								k++;
							}
							tr += '<tr> <td colspan="2"> <?= lang("Total"); ?> </td>';
							tr += '<td>'+ formatMoney(total_principle) +'</td>';
							tr += '<td>'+ formatMoney(total_payment) +'</td>';
							tr += '<td colspan="3"> &nbsp; </td> </tr>';
						} else if(p_type == 4){
							var principle = amount/term;
							var interest = (amount * (rate/100))/12;
							var balance = amount;
							var payment = 0;
							var i=0;
							var k=1;
							var total_principle = 0;
							var total_payment = 0;
							for(i=0;i<term;i++){
								if(i== 0){
									dateline = moment(new Date()).add(0,'months').format('DD/MM/YYYY');
								}else{
									dateline = moment(new Date()).add((k-1),'months').format('DD/MM/YYYY');
								}
								payment = principle + interest;

								balance -= principle;
								if(balance <= 0){
									balance = 0;
								}
								tr += '<tr> <td class="text-center">'+ k +'<input type="hidden" name="no[]" id="no" class="no" value="'+ k +'" /></td> ';
								tr += '<td>'+ formatMoney(interest) +'<input type="hidden" name="interest[]" id="interest" class="interest" width="90%" value="'+ interest +'"/></td>';
								tr += '<td>'+ formatMoney(principle) +'<input type="hidden" name="principle[]" id="principle" class="principle" width="90%" value="'+ principle +'"/></td>';
								tr += '<td>'+ formatMoney(payment) +'<input type="hidden" name="payment_amt[]" id="payment_amt" class="payment_amt" width="90%" value="'+ payment +'"/></td>';
								tr += '<td>'+ formatMoney(balance) +'<input type="hidden" name="balance[]" id="balance" class="balance" width="90%" value="'+ balance +'"/></td>';
								tr += '<td> <input name="note[]" class="note form-control" id="'+i+'" ></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+i+'" width="90%"/></td>';
								tr += '<td>'+ dateline +'<input type="hidden" class="dateline" name="dateline[]" id="dateline" value="'+ dateline +'" /> </td> </tr>';
								total_principle += principle;
								total_payment += payment;
								k++;
							}
							tr += '<tr> <td colspan="2"> <?= lang("total"); ?> </td>';
							tr += '<td>'+ formatMoney(total_principle) +'</td>';
							tr += '<td>'+ formatMoney(total_payment) +'</td>';
							tr += '<td colspan="3"> &nbsp; </td> </tr>';
						}
						$('.dep_tbl').show();
						$('#tbl_dep').html(tr);
						//$('#tbl_dep1').html(tr);
						$("#loan1").html(tr);
					}
				}
			}
		}

		$("#depreciation_rate_1").on('change', function(){
			$("#loan_rate").val($(this).val());
		});

		$("#depreciation_type_1").on('change', function(){
			$("#loan_type").val($(this).val());
		});

		$("#depreciation_term_1").on('change', function(){
			$("#loan_term").val($(this).val());
		});

		$("#suspend_room").on('change', function(){
			$("#suspend_room1").val($(this).val());
		});

		$("#tbl_dep .note").live('change', function(){
			var id = ($(this).attr('id'));
			var value = $(this).val();

			$('.note1_'+id+'').val(value);
		});

		$(document).on('click','#depreciation_print',function(){
			var amount = $('#amount_1').val();
			alert(amount);
			$.ajax({
                    type: "get",
                    url: "<?= site_url('pos/getData'); ?>",
                    data: {
						amount: amount
					},success: function (data) {

                    }

                });

		});

		$(document).on('keyup','#amount_1, #amount_2, #amount_3, #amount_4, #amount_5, .currencies_payment', function(){
			//var total_amount = $('#quick-payable').text()-0;
			var total_amount = $('#payable_amount').val()-0;
			var us_paid = $('#amount_1').val()-0;
			var us_paid2 = $('#amount_2').val()? $('#amount_2').val()-0 : 0;
			var us_paid3 = $('#amount_3').val()? $('#amount_3').val()-0 : 0;
			var us_paid4 = $('#amount_4').val()? $('#amount_4').val()-0 : 0;
			var us_paid5 = $('#amount_5').val()? $('#amount_5').val()-0 : 0;
			var other_paid = other_curr_paid_2_us();

            var balance = total_amount - (us_paid + us_paid2 + us_paid3 + us_paid4 + us_paid5 + other_paid);
			var ch = (balance).toFixed(3);
			var str = ch.split('.');
			if(balance > 0){
				autoCalremain(formatDecimal(balance));
				autoCalchange(0);
				$('.main_remain_').text(formatMoney(str[0]));
				$('.main_remain').text(formatMoney(balance));
				$('#change').text('0.00');
				$('#change_1').text('0.00');
			}else if(balance < 0){
				balance = balance * (-1);
				var ch = (balance).toFixed(3);
				var str = ch.split('.');
				autoCalremain(0);
				autoCalchange(formatDecimal(balance));
				$('#change').text(formatMoney(balance));
				$('#change_1').text(formatMoney(str[0]));
				$('.main_remain').text('0.00');
				$('.main_remain_').text('0.00');
			}else{
				autoCalremain(0);
				autoCalchange(0);
				$('#change').text('0.00');
				$('.main_remain').text('0.00');
				$('.main_remain_').text('0.00');
			}
			var deposit_amount = parseFloat($(".deposit_total_amount").text());
			var deposit_balance = parseFloat($(".deposit_total_balance").text());
			deposit_balance = (deposit_amount - Math.abs(us_paid));
			$(".deposit_total_balance").text(deposit_balance);

		});

		$(document).on('change','#depreciation_type_1, #depreciation_rate_1, #depreciation_term_1',function() {
			var p_type = $('#depreciation_type_1').val();
			var pr_type = $('#principle_type_1').val();
			var rate = $('#depreciation_rate_1').val();
			var term = $('#depreciation_term_1').val();
			var total_amount = $('#quick-payable').text()-0;
			var rate_kh = $('#other_cur_paid').attr('rate')-0;
			var kh_down = $('#other_cur_paid').val()-0;
			var kh_2_us = kh_down/rate_kh;
			if(!kh_2_us) {
				kh_2_us = 0;
			}
			var us_down = $('#amount_1').val();
			var down_pay = kh_2_us + us_down;
			var loan_amount = total_amount - down_pay;
			if(!pr_type) {
				depreciation(loan_amount,rate,term,p_type,total_amount);
			}
		});


		var datal = 0;
		$(document).on('change','#principle_type_1',function() {
			//var p_type = $('#depreciation_type_1').val();
			var pr_type = $('#principle_type_1').val();
			//var rate = $('#depreciation_rate_1').val();
			//var term = $('#depreciation_term_1').val();
			var total_amount = $('#quick-payable').text()-0;
			var rate_kh = $('#other_cur_paid').attr('rate')-0;
			var kh_down = $('#other_cur_paid').val()-0;
			var kh_2_us = kh_down/rate_kh;
			if(!kh_2_us) {
				kh_2_us = 0;
			}
			var us_down = $('#amount_1').val();
			var down_pay = kh_2_us + us_down;
			var amount = total_amount - down_pay;

				//depreciation(loan_amount,rate,term,p_type,total_amount);
			//alert(pr_type);
			if(pr_type ) {

				var principle = 0;
				var interest = 0;
				var percent = 0;
				var balance = amount;

				var i=0;
				var k=1;

				var total_principle = 0;
				var total_payment = 0;

				$.ajax({
                    type: 'get',
                    url: '<?= site_url('pos/getPrincipleCustomer'); ?>',
                    dataType: "json",
					async:false,
                    data: {
						pr_type:pr_type
                    },
                    success: function (data) {
						var c ="";
						var tr ="";
						tr += '<tr>';
							tr += '<th class="text-center"> <?= lang("period"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("rate"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("percentage"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("payment"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("total_payment"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("balance"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("note"); ?> </th>';
							tr += '<th class="text-center"> <?= lang("dateline"); ?> </th>';
							tr += '</tr>';
							datal = data.length;
						for(i=0;i<data.length;i++){
							c = data[i];
							//alert(JSON.stringify(data));
							var yong_yong = c.principle_rate.replace('%', '');
							//alert(yong_yong);
							//var rate_amount = ((yong_yong/100)/12);

							var payment = amount *(yong_yong/100); //((amount * rate_amount)*((Math.pow((1+rate_amount),data.length))/(Math.pow((1+rate_amount),data.length)-1)));

									if(c.rates == 1){
										interest = balance*((yong_yong/100)/12);
									}else{
										interest = 0 ;
									}
									//dateline = moment(new Date()).add(0,'months').format('DD/MM/YYYY');

									//principle = payment - interest;
								  var total_pay = interest + payment;

									//percent = (principle / balance) * 100;
									//balance -= principle;
									balance = balance - payment;
									if(balance <= 0){
										balance = 0;
									}

									tr += '<tr> <td class="text-center">'+ c.period +'<input type="hidden" name="no[]" id="no" class="no" value="'+ c.period +'" /></td> ';
									tr += '<td><input type="text" name="rate[]" id="rate" readonly class="rate2" style="width:60px;" value="'+ formatDecimal(interest) +'"/><input type="hidden" name="interest[]" id="interest" class="interest2" width="90%" value="'+ formatDecimal(interest) +'"/></td>';
									tr += '<td><input type="text" name="percentage[]" id="percentage" class="percentage2" style="width:60px;" value="'+ yong_yong +'"/><input type="hidden" name="percentage_[]" id="percentage_" class="percentage_2" style="width:60px;" value="'+ yong_yong +'"/></td>';
									tr += '<td><input type="text" name="pmt_principle[]" id="pmt_principle" class="pmt_principle2" style="width:60px;" value="'+ formatDecimal(payment) +'" /><input type="hidden" name="principle[]" id="principle" class="principle2" width="90%" value="'+ formatDecimal(payment) +'"/></td>';
									tr += '<td><input type="text" name="total_payment[]" id="total_payment" class="total_payment2" style="width:60px;" value="'+ formatDecimal(total_pay) +'" readonly/><input type="hidden" name="payment_amt[]" id="payment_amt2" class="payment_amt" width="90%" value="'+ formatDecimal(total_pay) +'"/></td>';
									tr += '<td><input type="text" name="amt_balance[]" id="amt_balance" class="amt_balance2" style="width:60px;" value="'+ formatDecimal(balance) +'" readonly/><input type="hidden" name="balance[]" id="balance" class="balance2" style="width:60px;" value="'+ formatDecimal(balance) +'"/></td>';
									tr += '<td> <input type="text" style="width:60px;" name="note[]" class="note " id="'+i+'" value="'+c.remark+'"></input> <input type="hidden" name="note1[]" id="note1" class="note1_'+i+'" width="90%"/></td>';
									tr += '<td><input type="text" class="dateline" name="dateline[]" id="dateline" value="'+ c.dateline +'" size="6" /></td> </tr>';

								total_principle += payment;
								total_payment += total_pay;
								k++;
							}
							tr += '<tr> <td colspan="3" style="text-align:right;"> <?= lang("Total"); ?> </td>';
							//tr += '<td><input type="text" name="total_percen" id="total_percen" class="total_percen" style="width:60px;" value="" readonly/></td>';
							tr += '<td><input type="text" name="total_pay" id="total_pay" class="total_pay2" style="width:60px;" value="'+ formatDecimal(total_principle) +'" readonly/></td>';
							tr += '<td><input type="text" name="total_amount" id="total_amount" class="total_amount2" style="width:60px;" value="'+ formatDecimal(total_payment) +'" readonly/></td>';
							tr += '<td colspan="3"> &nbsp; </td> </tr>';

							$('.dep_tbl').show();
						$('#tbl_dep').html(tr);
						//$('#tbl_dep1').html(tr);
						$("#loan1").html(tr);
                    },
					error: function (xhr, ajaxOptions, thrownError) {
						alert(xhr.status);
						alert(thrownError);
					  }
                });
			}
		});


		//###################### Search Filter #####################//
		$(document).on('keyup', '#tbl_dep .percentage, #tbl_dep .rate', function () {

			var rate_all = $('#depreciation_rate_1').val()-0;
			var term = $('#depreciation_term_1').val()-0;
			var amount = 0;
			var payment = 0;
			var amount_payment = 0;
			var rate = 0;
			var balance = 0;
			var per = $(this).val()-0;
			var tr = $(this).parent().parent();
			if(per < 0 || per > 100) {
				alert("sorry you can not input the rate value less than zerro or bigger than 100");
				$(this).val('');
				$(this).focus();
				return false;
			}else {
				amount = tr.find('.balance').val()-0;
				rate = tr.find('.interest').val()-0;

				payment = amount *(per/100);
				amount_payment = rate + payment;
				balance = amount - payment;
				tr.find('.pmt_principle').val(formatDecimal(payment));
				tr.find('.principal').val(formatDecimal(payment));
				tr.find('.total_payment').val(formatDecimal(amount_payment));
				tr.find('.payment_amt').val(formatDecimal(amount_payment));
				tr.find('.amt_balance').val(formatDecimal(balance));
				tr.find('.balance').val(formatDecimal(balance));

				var total_percent = 0;
				$('#tbl_dep .percentage').each(function(){
					var parent_ = $(this).parent().parent();
					var per_tage_ = parent_.find('.percentage').val()-0;
					total_percent += per_tage_;
				});

				var j = 1;
				var i = 1;
				var balance = 0;
				var amount_percent = 0;
				var amount_pay = 0;
				var amount_total_payment = 0;
				$('#tbl_dep .percentage').each(function(){
					var parent = $(this).parent().parent();
					var per_tage = parent.find('.percentage').val()-0;
					if(per_tage == '' || per_tage == 0) {
						per_tage = 0;
					}
					amount_percent += per_tage;
					var rate = parent.find('.rate').val()-0;
					//if(j == 1) {
						var total_amount = $('#quick-payable').text()-0;
						var rate_kh = $('#other_cur_paid').attr('rate')-0;
						var kh_down = $('#other_cur_paid').val()-0;
						var kh_2_us = kh_down/rate_kh;
						var us_down = $('#amount_1').val();
						var down_pay = kh_2_us + us_down;
						var loan_amount = total_amount - down_pay;
						balance = loan_amount;
					//}else {
					//	balance = parent.prev().find('.balance').val()-0;
					//}

					if(rate == 0) {
						var new_rate = 0;
					}else {
						var new_rate = balance * ((rate_all/term)/100);
					}
					var payment = formatDecimal(loan_amount * (per_tage/100));
					amount_pay += payment;
					var total_payment = payment + new_rate;
					amount_total_payment += total_payment;
					var balance = balance - payment;

					//alert(total_percent +" | "+ amount_percent);
					//alert(new_rate +" | "+ payment +" | "+ total_payment +" | "+ balance);

					if(total_percent != amount_percent) {
						parent.find('.rate').val(formatDecimal(new_rate));
						parent.find('.interest').val(formatDecimal(new_rate));
						parent.find('.pmt_principle').val(formatDecimal(payment));
						parent.find('.principle').val(formatDecimal(payment));
						parent.find('.total_payment').val(formatDecimal(total_payment));
						parent.find('.payment_amt').val(formatDecimal(total_payment));
						parent.find('.amt_balance').val(formatDecimal(balance));
						parent.find('.balance').val(formatDecimal(balance));
					}else{
						if(i == 1) {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.interest').val(formatDecimal(new_rate));
							parent.find('.pmt_principle').val(formatDecimal(payment));
							parent.find('.principle').val(formatDecimal(payment));
							parent.find('.total_payment').val(formatDecimal(total_payment));
							parent.find('.payment_amt').val(formatDecimal(total_payment));
							parent.find('.amt_balance').val(formatDecimal(balance));
							parent.find('.balance').val(formatDecimal(balance));
						}else {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.interest').val(formatDecimal(new_rate));
							parent.find('.pmt_principle').val("");
							parent.find('.principle').val(formatDecimal(payment));
							parent.find('.total_payment').val("");
							parent.find('.payment_amt').val(formatDecimal(total_payment));
							parent.find('.amt_balance').val("");
							parent.find('.balance').val(formatDecimal(balance));
						}
						i++;
					}
					j++;
				});
				//$('.total_percen').val(formatDecimal(amount_percent));
				$('.total_pay').val(formatDecimal(amount_pay));
				$('.total_amount').val(formatDecimal(amount_total_payment));
			}
		});
		$(document).on('keyup', '#tbl_dep .percentage2', function () {
			//var rate_all = $('.percentage2').val()-0;
			//var term = datal;
			var amount = 0;
			var payment = 0;
			var amount_payment = 0;
			var rate = 0;
			var balance = 0;
			var per = $(this).val()-0;
			var tr = $(this).parent().parent();
			if(per < 0 || per > 100) {
				alert("sorry you can not input the rate value less than zerro or bigger than 100");
				$(this).val('');
				$(this).focus();
				return false;
			}else {
				amount = tr.find('.balance2').val()-0;
				rate = tr.find('.interest2').val()-0;

				payment = amount *(per/100);
				amount_payment = rate + payment;
				balance = amount - payment;
				tr.find('.pmt_principle2').val(formatDecimal(payment));
				tr.find('.principal2').val(formatDecimal(payment));
				tr.find('.total_payment2').val(formatDecimal(amount_payment));
				tr.find('.payment_amt2').val(formatDecimal(amount_payment));
				tr.find('.amt_balance2').val(formatDecimal(balance));
				tr.find('.balance2').val(formatDecimal(balance));

				var total_percent = 0;
				$('#tbl_dep .percentage2').each(function(){
					var parent_ = $(this).parent().parent();
					var per_tage_ = parent_.find('.percentage2').val()-0;
					total_percent += per_tage_;
				});

				var j = 1;
				var i = 1;
				var balance = 0;
				var amount_percent = 0;
				var amount_pay = 0;
				var amount = 0;
				var new_rate = 0;
				var total_payment = 0;
				var payment = 0;
				var loan_amount = 0;
				var amount_total_payment = 0;
				$('#tbl_dep .percentage2').each(function(){
					var parent = $(this).parent().parent();
					var per_tage = parent.find('.percentage2').val()-0;
					if(per_tage == '' || per_tage == 0) {
						per_tage = 0;
					}
					amount_percent += per_tage;
					var rate = parent.find('.rate2').val()-0;
					//if(j == 1) {
						var total_amount = $('#quick-payable').text()-0;
						var rate_kh = $('#other_cur_paid').attr('rate')-0;
						var kh_down = $('#other_cur_paid').val()-0;
						var kh_2_us = kh_down/rate_kh;
						var us_down = $('#amount_1').val();
						var down_pay = kh_2_us + us_down;
						loan_amount = total_amount - down_pay;
						balance = loan_amount;
					//}else {
					//	balance = parent.prev().find('.balance2').val()-0;
					//	loan_amount = parent.prev().find('.balance2').val()-0;
					//}
					 new_rate = balance * ((per_tage/100)/12);

					payment = formatDecimal(loan_amount * (per_tage/100));
					amount_pay += payment;
					total_payment = payment + new_rate;
					amount_total_payment += total_payment;
					balance = balance - payment;

					//alert(total_percent +" | "+ amount_percent);
					//alert(new_rate +" | "+ payment +" | "+ total_payment +" | "+ balance);


					if(total_percent != amount_percent) {
						parent.find('.rate2').val(formatDecimal(new_rate));
						parent.find('.interest2').val(formatDecimal(new_rate));
						parent.find('.pmt_principle2').val(formatDecimal(payment));
						parent.find('.principle2').val(formatDecimal(payment));
						parent.find('.total_payment2').val(formatDecimal(total_payment));
						parent.find('.payment_amt2').val(formatDecimal(total_payment));
						parent.find('.amt_balance2').val(formatDecimal(balance));
						parent.find('.balance2').val(formatDecimal(balance));
					}else{
						if(i == 1) {
							parent.find('.rate2').val(formatDecimal(new_rate));
							parent.find('.interest2').val(formatDecimal(new_rate));
							parent.find('.pmt_principle2').val(formatDecimal(payment));
							parent.find('.principle2').val(formatDecimal(payment));
							parent.find('.total_payment2').val(formatDecimal(total_payment));
							parent.find('.payment_amt2').val(formatDecimal(total_payment));
							parent.find('.amt_balance2').val(formatDecimal(balance));
							parent.find('.balance2').val(formatDecimal(balance));
						}else {
							parent.find('.rate2').val(formatDecimal(new_rate));
							parent.find('.interest2').val(formatDecimal(new_rate));
							parent.find('.pmt_principle2').val("");
							parent.find('.principle2').val(formatDecimal(payment));
							parent.find('.total_payment2').val("");
							parent.find('.payment_amt2').val(formatDecimal(total_payment));
							parent.find('.amt_balance2').val("");
							parent.find('.balance2').val(formatDecimal(balance));
						}
						i++;
					}
					j++;

				});
				//$('.total_percen').val(formatDecimal(amount_percent));
				$('.total_pay2').val(formatDecimal(amount_pay));
				$('.total_amount2').val(formatDecimal(amount_total_payment));
			}
		});
		$(document).on('keyup', '#tbl_dep .pmt_principle2', function () {


			var amount_payment = 0;
			var rate = 0;

			var pri = $(this).val()-0;
			var parent = $(this).parent().parent();
				var balance = 0;
				var amount_percent = 0;
				var amount_pay = 0;
				var amount = 0;
				var new_rate = 0;
				var total_payment = 0;
				var payment = 0;
				var perc = 0;
				var loan_amount = 0;
				var amount_total_payment = 0;

				var per_tage = parent.find('.percentage2').val()-0;
					if(per_tage == '' || per_tage == 0) {
						per_tage = 0;
					}
					//amount_percent += per_tage;
					var rate = parent.find('.rate2').val()-0;

						var total_amount = $('#quick-payable').text()-0;
						var rate_kh = $('#other_cur_paid').attr('rate')-0;
						var kh_down = $('#other_cur_paid').val()-0;
						var kh_2_us = kh_down/rate_kh;
						var us_down = $('#amount_1').val();
						var down_pay = kh_2_us + us_down;
						loan_amount = total_amount - down_pay;
						balance = loan_amount;
						perc = (loan_amount /100);
						perc = pri/perc;
					 new_rate = balance * ((perc/100)/12);

					//payment = formatDecimal(loan_amount * (per_tage/100));
					total_payment = pri + new_rate;



					balance = balance - pri;

						parent.find('.rate2').val(formatDecimal(new_rate));
						parent.find('.interest2').val(formatDecimal(new_rate));
						parent.find('.percentage2').val(formatDecimal(perc));
						parent.find('.percentage_2').val(formatDecimal(perc));

						parent.find('.total_payment2').val(formatDecimal(total_payment));
						parent.find('.payment_amt2').val(formatDecimal(total_payment));
						parent.find('.amt_balance2').val(formatDecimal(balance));
						parent.find('.balance2').val(formatDecimal(balance));
					$('#tbl_dep .percentage2').each(function(){
						var p = $(this).parent().parent();
						amount_pay += p.find('.pmt_principle2').val()-0;
                        amount_total_payment += p.find('.total_payment2').val() - 0;
                    });

				//$('.total_percen').val(formatDecimal(amount_percent));
				$('.total_pay2').val(formatDecimal(amount_pay));
				$('.total_amount2').val(formatDecimal(amount_total_payment));

		});
		//######################## Code Search #############################//
		$("#Pcode").autocomplete({
			search: function(event, ui) {
				$('.test').empty();
			},
            source: function (request, response) {
                if (!$('#poscustomer').val()) {
                    $('#Pcode').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    //response('');
                    $('#Pcode').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('sales/Pcode'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#poswarehouse").val(),
                        customer_id: $("#poscustomer").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>', function () {
                    //    $('#Pcode').focus();
                    //});
                    $(this).val('');
                }
				/*
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                }
				*/
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>', function () {
                    //    $('#Pcode').focus();
                    //});
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>');
                }
            },
			open: function(event, ui) {
				//$(".ui-autocomplete").css("position", "absolute");
				$(".ui-autocomplete").css("width", "0");
				$(".ui-autocomplete").css("z-index", "99999");
				$(".data").css("z-index", "99999");
			}
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
			var inner_html = '';
			if(item.no_pro == 0){
				inner_html = '<td colspan="6" style="text-align:center;"><h2 style="font-size:37px"><b><?=lang('no_products');?></b></h2></td>';
			}else{
				var inner_html  = 	'<td style="width:50px;height:30px;">' +
									'<center>'+
										'<input type="checkbox" class="form-control" id="chk" name="val[]" value="'+ item.code +'"/>'+
									'</center>' +
								'</td>' +
								'<td style="width:140px;">'+
									item.code +
								'</td>' +
								'<td style="width:142px;">'+
									item.label +
								'</td>' +
								//'<td style="width:114px;">'+
								//	item.detail +
								//'</td>' +
								'<td style="width:102px;">'+
									item.cate_id +
								'</td>' +
								'<td style="width:64px;">'+
									item.price +
								'</td>' +
								//'<td style="width:64px;">'+
								//	item.strap +
								//'</td>' +
								'<td class=""><a href="#ShowImage" data-toggle="modal"><img class="passimage" src="http://192.168.1.168:8181/CloudNET/iCloudERP_ACC/assets/uploads/thumbs/'+item.pic+'" alt="'+item.pic+'" style="width: 70px; height: 70px; margin: 0 auto; display: block;"></a></td>';
			}
			return $( "<tr></tr>")
				.data( "item.autocomplete", item )
				.append(inner_html)
				.appendTo($('.test'));
		};

		$('#Pcode').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

		$(document).on("click", ".passimage", function () {
			var img = $(this).attr('alt');
			var image = '<img src="http://192.168.1.168:8181/CloudNET/iCloudERP_ACC/assets/uploads/'+img+'" style="width:100%;"/>';
			$('.getImg').html(image);
		});

		//######################## Name Search #############################//
		$("#Pname").autocomplete({
			search: function(event, ui) {
				$('.test').empty();
			},
            source: function (request, response) {
                if (!$('#poscustomer').val()) {
                    $('#Pname').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    //response('');
                    $('#Pname').focus();
                    return false;
                }
				var code     = $('#Pcode').val();
				var category = $('#Pcategory').val();
				var price    = $('#Pprice').val();
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('sales/Pname'); ?>',
                    dataType: "json",
                    data: {
						code:code,
						category:category,
						price:price,
                        term: request.term,
                        warehouse_id: $("#poswarehouse").val(),
                        customer_id: $("#poscustomer").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>', function () {
                       // $('#Pname').focus();
                    //});
                    $(this).val('');
                }
				/*
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                }
				*/
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
					// bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        //$('#Pname').focus();
                    //});
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>');
                }
            },
			open: function(event, ui) {
				//$(".ui-autocomplete").css("position", "absolute");
				$(".ui-autocomplete").css("width", "0");
				$(".ui-autocomplete").css("z-index", "99999");
				$(".data").css("z-index", "99999");
			}
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
			if(item.no_pro == 0){
				inner_html = '<td colspan="6" style="text-align:center;"><h2 style="font-size:37px"><b><?=lang('no_products');?></b></h2></td>';
			}else{
				var inner_html  = 	'<td style="width:50px;height:30px;">' +
									'<center>'+
										'<input type="checkbox" class="form-control" id="chk" name="val[]" value="'+ item.code +'"/>'+
									'</center>' +
								'</td>' +
								'<td style="width:140px;">'+
									item.code +
								'</td>' +
								'<td style="width:142px;">'+
									item.label +
								'</td>' +
								//'<td style="width:114px;">'+
								//	item.detail +
								//'</td>' +
								'<td style="width:102px;">'+
									item.cate_id +
								'</td>' +
								'<td style="width:64px;">'+
									item.price +
								'</td>' +
								//'<td style="width:64px;">'+
								//	item.strap +
								//'</td>' +
								'<td class=""><a href="#ShowImage" data-toggle="modal"><img class="passimage" src="http://192.168.1.168:8181/CloudNET/iCloudERP_ACC/assets/uploads/thumbs/'+item.pic+'" alt="'+item.pic+'" style="width: 70px; height: 70px; margin: 0 auto; display: block;"></a></td>';
			}
			return $( "<tr></tr>")
				.data( "item.autocomplete", item )
				.append(inner_html)
				.appendTo($('.test'));
		};

		$('#Pname').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

		//######################## Description Search #############################//
		$("#Pdescription").autocomplete({
			search: function(event, ui) {
				$('.test').empty();
			},
            source: function (request, response) {
                if (!$('#poscustomer').val()) {
                    $('#Pdescription').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    //response('');
                    $('#Pdescription').focus();
                    return false;
                }
				var coding = $('#Pcode').val();
				var nameing = $('#Pname').val();
				var category = $('#Pcategory').val();
				var price    = $('#Pprice').val();
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('sales/Pdescription'); ?>',
                    dataType: "json",
                    data: {
						code:coding,
						named: nameing,
						price:price,
						category:category,
                        term: request.term,
                        warehouse_id: $("#poswarehouse").val(),
                        customer_id: $("#poscustomer").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>', function () {
                    //    $('#Pdescription').focus();
                    //});
                    $(this).val('');
                }
				/*
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                }
				*/
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>', function () {
                    //    $('#Pdescription').focus();
                    //});
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            },
			open: function(event, ui) {
				//$(".ui-autocomplete").css("position", "absolute");
				$(".ui-autocomplete").css("width", "0");
				$(".ui-autocomplete").css("z-index", "99999");
				$(".data").css("z-index", "99999");
			}
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
			if(item.no_pro == 0){
				inner_html = '<td colspan="6" style="text-align:center;"><h2 style="font-size:37px"><b><?=lang('no_products');?></b></h2></td>';
			}else{
				var inner_html  = 	'<td style="width:50px;height:30px;">' +
									'<center>'+
										'<input type="checkbox" class="form-control" id="chk" name="val[]" value="'+ item.code +'"/>'+
									'</center>' +
								'</td>' +
								'<td style="width:140px;">'+
									item.code +
								'</td>' +
								'<td style="width:142px;">'+
									item.label +
								'</td>' +
								//'<td style="width:114px;">'+
								//	item.detail +
								//'</td>' +
								'<td style="width:102px;">'+
									item.cate_id +
								'</td>' +
								'<td style="width:64px;">'+
									item.price +
								'</td>' +
								//'<td style="width:64px;">'+
								//	item.strap +
								//'</td>' +
								'<td class=""><a href="#ShowImage" data-toggle="modal"><img class="passimage" src="http://192.168.1.168:8181/CloudNET/iCloudERP_ACC/assets/uploads/thumbs/'+item.pic+'" alt="'+item.pic+'" style="width: 70px; height: 70px; margin: 0 auto; display: block;"></a></td>';
			}
			return $( "<tr></tr>")
				.data( "item.autocomplete", item )
				.append(inner_html)
				.appendTo($('.test'));
		};

		$('#Pdescription').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

		//######################## Category Search #############################//
		$("#Pcategory").autocomplete({
			search: function(event, ui) {
				$('.test').empty();
			},
            source: function (request, response) {
                if (!$('#poscustomer').val()) {
                    $('#Pcategory').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    //response('');
                    $('#Pcategory').focus();
                    return false;
                }
				var coding = $('#Pcode').val();
				var nameing = $('#Pname').val();
				var price    = $('#Pprice').val();
				$.ajax({
                    type: 'get',
                    url: '<?= site_url('sales/Pcategory'); ?>',
                    dataType: "json",
                    data: {
						code: coding,
						named: nameing,
						price: price,
                        term: request.term,
                        warehouse_id: $("#poswarehouse").val(),
                        customer_id: $("#poscustomer").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>', function () {
                    //    $('#Pcategory').focus();
                    //});
                    $(this).val('');
                }
				/*
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                }
				*/
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
					// bootbox.alert('<?= lang('no_match_found') ?>', function () {
                    //    $('#Pcategory').focus();
                    //});
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);

                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            },
			open: function(event, ui) {
				//$(".ui-autocomplete").css("position", "absolute");
				$(".ui-autocomplete").css("width", "0");
				$(".ui-autocomplete").css("z-index", "99999");
				$(".data").css("z-index", "99999");
			}
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
			if(item.no_pro == 0){
				inner_html = '<td colspan="6" style="text-align:center;"><h2 style="font-size:37px"><b><?=lang('no_products');?></b></h2></td>';
			}else{
			var inner_html  = 	'<td style="width:50px;height:30px;">' +
									'<center>'+
										'<input type="checkbox" class="form-control" id="chk" name="val[]" value="'+ item.code +'"/>'+
									'</center>' +
								'</td>' +
								'<td style="width:140px;">'+
									item.code +
								'</td>' +
								'<td style="width:142px;">'+
									item.label +
								'</td>' +
								//'<td style="width:114px;">'+
								//	item.detail +
								//'</td>' +
								'<td style="width:102px;">'+
									item.cate_id +
								'</td>' +
								'<td style="width:64px;">'+
									item.price +
								'</td>' +
								//'<td style="width:64px;">'+
								//	item.strap +
								//'</td>'+
								'<td class=""><a href="#ShowImage" data-toggle="modal"><img class="passimage" src="http://192.168.1.168:8181/CloudNET/iCloudERP_ACC/assets/uploads/thumbs/'+item.pic+'" alt="'+item.pic+'" style="width: 70px; height: 70px; margin: 0 auto; display: block;"></a></td>';
			}
			return $( "<tr></tr>")
				.data( "item.autocomplete", item )
				.append(inner_html)
				.appendTo($('.test'));
		};

		$('#Pcategory').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

		//######################## Price Search #############################//
		$("#Pprice").autocomplete({
			search: function(event, ui) {
				$('.test').empty();
			},
            source: function (request, response) {
                if (!$('#poscustomer').val()) {
                    $('#Pprice').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    //response('');
                    $('#Pprice').focus();
                    return false;
                }
				var coding = $('#Pcode').val();
				var nameing = $('#Pname').val();
				var category    = $('#Pcategory').val();
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('sales/Pprice'); ?>',
                    dataType: "json",
                    data: {
						code:coding,
						name:nameing,
						category:category,
                        term: request.term,
                        warehouse_id: $("#poswarehouse").val(),
                        customer_id: $("#poscustomer").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>', function () {
                    //    $('#Pprice').focus();
                    //});
                    $(this).val('');
                }
				/*
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                }
				*/
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                   // bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        //$('#Pprice').focus();
                   // });
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);

                    $('#product-sale-view').html("sdfsfd");

                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    //bootbox.alert('<?= lang('no_match_found') ?>');
                }
            },
			open: function(event, ui) {
				//$(".ui-autocomplete").css("position", "absolute");
				$(".ui-autocomplete").css("width", "0");
				$(".ui-autocomplete").css("z-index", "99999");
				$(".data").css("z-index", "99999");
			}
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
			if(item.no_pro == 0){
				inner_html = '<td colspan="6" style="text-align:center;"><h2 style="font-size:37px"><b><?=lang('no_products');?></b></h2></td>';
			}else{
				var inner_html  = 	'<td style="width:50px;height:30px;">' +
									'<center>'+
										'<input type="checkbox" class="form-control" id="chk" name="val[]" value="'+ item.code +'"/>'+
									'</center>' +
								'</td>' +
								'<td style="width:140px;">'+
									item.code +
								'</td>' +
								'<td style="width:142px;">'+
									item.label +
								'</td>' +
								//'<td style="width:114px;">'+
								//	item.detail +
								//'</td>' +
								'<td style="width:102px;">'+
									item.cate_id +
								'</td>' +
								'<td style="width:64px;">'+
									item.price +
								'</td>' +
								//'<td style="width:64px;">'+
								//	item.strap +
								//'</td>'+
								'<td class=""><a href="#ShowImage" data-toggle="modal"><img class="passimage" src="http://192.168.1.168:8181/CloudNET/iCloudERP_ACC/assets/uploads/thumbs/'+item.pic+'" alt="'+item.pic+'" style="width: 70px; height: 70px; margin: 0 auto; display: block;"></a></td>';
			}
			return $( "<tr></tr>")
				.data( "item.autocomplete", item )
				.append(inner_html)
				.appendTo($('.test'));
		};

		$('#Pprice').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

		//######################## Strap Search #############################//
		$("#Pstrap").autocomplete({
			search: function(event, ui) {
				$('.test').empty();
			},
            source: function (request, response) {
                if (!$('#poscustomer').val()) {
                    $('#Pstrap').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    $('#Pstrap').focus();
                    return false;
                }

                $.ajax({
                    type: 'get',
                    url: '<?= site_url('sales/Pstrap'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#poswarehouse").val(),
                        customer_id: $("#poscustomer").val()
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#Pstrap').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#Pstrap').focus();
                    });
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);

                    $('#product-sale-view').html("sdfsfd");

                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            },
			open: function(event, ui) {
				//$(".ui-autocomplete").css("position", "absolute");
				$(".ui-autocomplete").css("width", "0");
				$(".ui-autocomplete").css("z-index", "99999");
				$(".data").css("z-index", "99999");
			}
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
			var inner_html  = 	'<td style="width:50px;height:30px;">' +
									'<center>'+
										'<input class="checkbox multi-select input-xs" type="checkbox" name="val[]" value="'+ item.code +'"/>'+
									'</center>' +
								'</td>' +
								'<td style="width:140px;">'+
									item.code +
								'</td>' +
								'<td style="width:142px;">'+
									item.label +
								'</td>' +
								'<td style="width:114px;">'+
									item.detail +
								'</td>' +
								'<td style="width:102px;">'+
									item.cate_id +
								'</td>' +
								'<td style="width:64px;">'+
									item.price +
								'</td>' +
								'<td style="width:64px;">'+
									item.strap +
								'</td>'+
								'<td class=""><a href="#ShowImage" data-toggle="modal"><img class="img-circle passimage" src="http://192.168.1.168:8181/CloudNET/iCloudERP_ACC/assets/uploads/thumbs/'+item.pic+'" alt="'+item.pic+'" style="width:30px; height:30px;"></a></td>';
			return $( "<tr></tr>")
				.data( "item.autocomplete", item )
				.append(inner_html)
				.appendTo($('.test'));
		};

		$('#Pstrap').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });


		$('#Pcode').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

		//#################### Search Floor #######################//
		function floor_status(x){
			var lang = {'0': '<?=lang('free');?>', '2': '<?=lang('book');?>', '1': '<?=lang('busy');?>'};
			if(x == 0){
				return '<div class="text-center"><span class="label label-default">'+lang[x]+'</span></div>';
			}else if(x == 1){
				return '<div class="text-center"><span class="label label-warning">'+lang[x]+'</span></div>';
			}else{
				return '<div class="text-center"><span class="label label-primary">'+lang[x]+'</span></div>';
			}
		}
		$("#fcode").autocomplete({
			search: function(event, ui) {
				$('.floor').empty();
			},
            source: function (request, response) {
                if (!$('#poscustomer').val()) {
                    $('#fcode').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    //response('');
                    $('#fcode').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('sales/fcode'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#fcode').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#fcode').focus();
                    });
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            },
			open: function(event, ui) {
				//$(".ui-autocomplete").css("position", "absolute");
				$(".ui-autocomplete").css("width", "0");
				$(".ui-autocomplete").css("z-index", "99999");
				$(".data").css("z-index", "99999");
			}
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
			var inner_html  = 	'<td style="width:50px;height:30px;">' +
									'<center>'+
										'<input class="checkbox multi-select input-xs" type="checkbox" name="val[]" value="'+ item.item_id +'"/>'+
									'</center>' +
								'</td>' +
								'<td style="width:140px;">'+
									item.code + '<input type="hidden" value="'+ item.code +'" id="code'+item.item_id+'"/>' +
								'</td>' +
								'<td style="width:142px;">'+
									item.label +
								'</td>' +
								'<td style="width:142px;">'+
									floor_status(item.status) + '<input type="hidden" value="'+ item.status +'" id="status'+item.item_id+'"/>' +
								'</td>' +
								'<td style="width:114px;">'+
									item.floor +
								'</td>';
			return $( "<tr></tr>")
				.data( "item.autocomplete", item )
				.append(inner_html)
				.appendTo($('.floor'));
		};

		$('#fcode').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

		$("#fdescription").autocomplete({
			search: function(event, ui) {
				$('.floor').empty();
			},
            source: function (request, response) {
                if (!$('#poscustomer').val()) {
                    $('#fdescription').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    //response('');
                    $('#fdescription').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('sales/fdescription'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#fdescription').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#fdescription').focus();
                    });
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            },
			open: function(event, ui) {
				//$(".ui-autocomplete").css("position", "absolute");
				$(".ui-autocomplete").css("width", "0");
				$(".ui-autocomplete").css("z-index", "99999");
				$(".data").css("z-index", "99999");
			}
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
			var inner_html  = 	'<td style="width:50px;height:30px;">' +
									'<center>'+
										'<input class="checkbox multi-select input-xs" type="checkbox" name="val[]" value="'+ item.item_id +'"/>'+
									'</center>' +
								'</td>' +
								'<td style="width:140px;">'+
									item.code + '<input type="hidden" value="'+ item.code +'" id="code'+item.item_id+'"/>' +
								'</td>' +
								'<td style="width:142px;">'+
									item.label +
								'</td>' +
								'<td style="width:142px;">'+
									floor_status(item.status) + '<input type="hidden" value="'+ item.status +'" id="status'+item.item_id+'"/>' +
								'</td>' +
								'<td style="width:114px;">'+
									item.floor +
								'</td>';
			return $( "<tr></tr>")
				.data( "item.autocomplete", item )
				.append(inner_html)
				.appendTo($('.floor'));
		};

		$('#fdescription').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

		$("#ffloor").autocomplete({
			search: function(event, ui) {
				$('.floor').empty();
			},
            source: function (request, response) {
                if (!$('#poscustomer').val()) {
                    $('#ffloor').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    //response('');
                    $('#ffloor').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('sales/ffloor'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 200,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#ffloor').focus();
                    });
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#ffloor').focus();
                    });
                    $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            },
			open: function(event, ui) {
				//$(".ui-autocomplete").css("position", "absolute");
				$(".ui-autocomplete").css("width", "0");
				$(".ui-autocomplete").css("z-index", "99999");
				$(".data").css("z-index", "99999");
			}
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
			var inner_html  = 	'<td style="width:50px;height:30px;">' +
									'<center>'+
										'<input class="checkbox multi-select input-xs" type="checkbox" name="val[]" value="'+ item.item_id +'"/>'+
									'</center>' +
								'</td>' +
								'<td style="width:140px;">'+
									item.code + '<input type="hidden" value="'+ item.code +'" id="code'+item.item_id+'"/>' +
								'</td>' +
								'<td style="width:142px;">'+
									item.label +
								'</td>' +
								'<td style="width:142px;">'+
									floor_status(item.status) + '<input type="hidden" value="'+ item.status +'" id="status'+item.item_id+'"/>' +
								'</td>' +
								'<td style="width:114px;">'+
									item.floor +
								'</td>';
			return $( "<tr></tr>")
				.data( "item.autocomplete", item )
				.append(inner_html)
				.appendTo($('.floor'));
		};

		$('#ffloor').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

		$(document).on('click', '#addItem', function (e) {

			$('#modal-loading').show();
            var susp_id = $('#suspend_id').val();
            var table_no = $('#table_no').val();
            var item_row = $('#posTable tbody tr').length;
           // code = $(this).val(),
			var val = [];
            wh = $('#poswarehouse').val(),
            cu = $('#poscustomer').val();
            var subtotal = $('#total').html();
            $(':checkbox:checked').each(function(i){
				code = val[i] = $(this).val();
				$.ajax({
					type: "get",
					url: "<?= site_url('pos/getProductDataByCode') ?>",
					data: {code: code, warehouse_id: wh, customer_id: cu, suspend_id: susp_id, item_rows: item_row, sub_total: subtotal},
					dataType: "json",
					success: function (data) {
						e.preventDefault();
						if (data !== null) {
							var item_id = data['item_id'];
							var image = "<?php echo site_url();?>assets/uploads/thumbs/"+data['image'];
							var title = data['row']['name'];
							var code = data['row']['code'];
							var total = data['sub_total'];
							var item ='<button id="'+code+'" type="button" value="'+code+'" title="" class="btn-prni btn-default product pos-tip" data-container="body" data-original-title="'+title+'"><img src="'+image+'" alt="'+title+'" style="width: 60px; height: 60px;" class="img-rounded"/><span>'+title+'</span></button>';
							var suspend_html = '<p> '+ table_no +'</p>';
								suspend_html += '<div class="sup_number'+susp_id+'">('+(item_row+1)+')</div>';
								suspend_html += '<br/>'+formatMoney(total);
							$('.wrap_suspend'+susp_id).html(suspend_html);
							$('#product-sale-view').prepend(item);
							add_invoice_item(data);
							$('#modal-loading').hide();
						} else {
							//audio_error.play();
							bootbox.alert('<?= lang('no_match_found') ?>');
							$('#modal-loading').hide();
						}
					}
				});
			});
		});

		$(document).on('click', '#addSearch', function (e) {

			$('#modal-loading').show();
            var susp_id = $('#suspend_id').val();
            var table_no = $('#table_no').val();
            var item_row = $('#posTable tbody tr').length;
           // code = $(this).val(),
			var val = [];
            wh = $('#poswarehouse').val(),
            cu = $('#poscustomer').val();
            var subtotal = $('#total').html();
            $(':checkbox:checked').each(function(i){
				code = val[i] = $(this).val();
				status = val[i] = $("#status"+code).val();
				co_id = val[i] = $("#code"+code).val();
				if(status == 0){
					//$('#'+co_id).trigger('dblclick');
					$("[id='"+co_id+"']").trigger('dblclick');
				}else{
					$.ajax({
						type: "get",
						url: "<?= site_url('pos/getProductSearchByCode') ?>",
						data: {code: code, warehouse_id: wh, customer_id: cu, suspend_id: susp_id, item_rows: item_row, sub_total: subtotal},
						dataType: "json",
						success: function (data) {
							if(data === null){
								bootbox.alert('<?= lang('no_match_found') ?>');
									$('#modal-loading').hide();
							}else{
								$.each(data, function(i, items) {
									e.preventDefault();

									var item_id = items['item_id'];
									var image = "<?php echo site_url();?>assets/uploads/thumbs/"+items['image'];
									var title = items['row']['name'];
									var code = items['row']['code'];
									var total = items['sub_total'];
									var item ='<button id="'+code+'" type="button" value="'+code+'" title="" class="btn-prni btn-default product pos-tip" data-container="body" data-original-title="'+title+'"><img src="'+image+'" alt="'+title+'" style="width: 60px; height: 60px;" class="img-rounded"/><span>'+title+'</span></button>';
									var suspend_html = '<p> '+ table_no +'</p>';
										suspend_html += '<div class="sup_number'+susp_id+'">('+(item_row+1)+')</div>';
										suspend_html += '<br/>'+formatMoney(total);
									$('.wrap_suspend'+susp_id).html(suspend_html);
									$('#product-sale-view').prepend(item);
									add_invoice_item(items);
									//$('#modal-loading').hide();
								})
							}
						}
					});
				}
			});
		});

		$(document).on('keyup','#tbl_dep .pmt_principle, #tbl_dep .rate', function () {
			var rate_all = $('#depreciation_rate_1').val()-0;
			var term = $('#depreciation_term_1').val()-0;
			var amount = 0;
			var percent = 0;
			var amount_payment = 0;
			var rate = 0;
			var balance = 0;
			var payment = $(this).val()-0;
			var tr = $(this).parent().parent();
			if(payment < 0 ) {
				alert("sorry you can not input the rate value less than zerro or bigger than 100");
				$(this).val('');
				$(this).focus();
				return false;
			}else {
				amount = tr.find('.balance').val();
				rate = tr.find('.interest').val()-0;
				percent = (payment / amount) * 100;
				amount_payment = rate + payment;
				balance = amount - payment;
				tr.find('.percentage').val(percent.toFixed(4));
				tr.find('.percentage_').val(percent);
				tr.find('.total_payment').val(formatDecimal(amount_payment));
				tr.find('.payment_amt').val(amount_payment);
				tr.find('.amt_balance').val(formatDecimal(balance));
				tr.find('.balance').val(balance);

				var total_pay = 0;
				$('#tbl_dep .pmt_principle').each(function(){
					var parent = $(this).parent().parent();
					var pay_amt_ = parent.find('.pmt_principle').val()-0;
					total_pay += pay_amt_;
				});

				var j = 1;
				var i = 1;
				var balance = 0;
				var amount_pay = 0;
				var total_per = 0;
				var amount_total_payment  = 0;
				$('#tbl_dep .pmt_principle').each(function(){
					var parent = $(this).parent().parent();
					var pay_amt = parent.find('.pmt_principle').val()-0;
					if(pay_amt == '' || pay_amt < 0) {
						pay_amt = 0;
					}
					amount_pay += pay_amt;
					var rate = parent.find('.rate').val()-0;
					if(j == 1) {
						var total_amount = $('#quick-payable').text()-0;
						var rate_kh = $('#other_cur_paid').attr('rate')-0;
						var kh_down = $('#other_cur_paid').val()-0;
						var kh_2_us = kh_down/rate_kh;
						var us_down = $('#amount_1').val();
						var down_pay = kh_2_us + us_down;
						var loan_amount = total_amount - down_pay;
						balance = loan_amount;
					}else {
						balance = parent.prev().find('.balance').val()-0;
					}
					if(rate == 0) {
						var new_rate = 0;
					}else {
						var new_rate = balance * ((rate_all/term)/100);
					}
					var percen = (pay_amt / balance) * 100;
					total_per += percen;
					//var payment = balance * (per_tage/100);
					var total_payment = pay_amt + new_rate;
					amount_total_payment += total_payment;
					var balance = balance - pay_amt;

					//alert(new_rate);
					if(total_pay != amount_pay) {
						parent.find('.rate').val(formatDecimal(new_rate));
						parent.find('.interest').val(new_rate);
						parent.find('.percentage').val(percen.toFixed(4));
						parent.find('.percentage_').val(percen);
						parent.find('.total_payment').val(formatDecimal(total_payment));
						parent.find('.payment_amt').val(total_payment);
						parent.find('.amt_balance').val(formatDecimal(balance));
						parent.find('.balance').val(balance);
					}else{
						if(i == 1) {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.interest').val(new_rate);
							parent.find('.percentage').val(percen.toFixed(4));
							parent.find('.percentage_').val(percen);
							parent.find('.total_payment').val(formatDecimal(total_payment));
							parent.find('.payment_amt').val(total_payment);
							parent.find('.amt_balance').val(formatDecimal(balance));
							parent.find('.balance').val(balance);
						}else {
							parent.find('.rate').val(formatDecimal(new_rate));
							parent.find('.interest').val(new_rate);
							parent.find('.percentage').val("");
							parent.find('.percentage_').val(percen);
							parent.find('.total_payment').val("");
							parent.find('.payment_amt').val(total_payment);
							parent.find('.amt_balance').val("");
							parent.find('.balance').val(balance);
						}
						i++;
					}
					j++;
				});
				$('.total_percen').val(formatDecimal(total_per));
				$('.total_pay').val(formatDecimal(amount_pay));
				$('.total_amount').val(formatDecimal(amount_total_payment));
			}
		});

		function checkDeposit() {
			var customer_id = $("#poscustomer").val();

            if (customer_id != '') {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "sales/validate_deposit/" + customer_id,
                    dataType: "json",
                    success: function (data) {
                        if (data === false) {
                            $('#deposit_no_1').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('invalid_customer')?>');
                        } else if (data.id !== null && data.id !== $('#poscustomer').val()) {
                            $('#deposit_no_1').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('this_customer_has_no_deposit')?>');

							$(".paid_by").val($(".paid_by option:first").val());
                        } else {
							/*amount1 = $("#amount_1").val() - 0;
							amount2 = $("#amount_2").val() ? $("#amount_2").val()-0 : 0;
                            $('#dp_details_1').html('<small>Customer Name: ' + data.name + '<br>Amount: <span class="deposit_total_amount">' + (data.dep_amount == null ? 0 : data.dep_amount) + '</span> - Balance: <span class="deposit_total_balance">' +(data.dep_amount - amount1) + '</span></small>');

							if(amount2){
								$('#dp_details_2').html('<small>Customer Name: ' + data.name + '<br>Amount: <span class="deposit_total_amount">' + (data.dep_amount == null ? 0 : data.dep_amount) + '</span> - Balance: <span class="deposit_total_balance">' +(data.dep_amount - amount2) + '</span></small>');
							}*/

							$('#deposit_no_1').parent('.form-group').removeClass('has-error');
                            for (i = 1; i < 6; i++) {
                                amount = $("#amount_"+i).val() ? $("#amount_"+i).val()-0 : 0;
                                if(amount){
                                    $('#dp_details_'+i).html('<small>Customer Name: ' + data.name + '<br>Amount: <span class="deposit_total_amount">' + (data.dep_amount == null ? 0 : data.dep_amount) + '</span> - Balance: <span class="deposit_total_balance">' +(data.dep_amount - amount) + '</span></small>');
                                }
                            }
                        }
                    }
                });
            }
		}

        $(document).on('change', '.paid_by', function () {
            var p_val = $(this).val(),
                id = $(this).attr('id'),
                pa_no = id.substr(id.length - 1);
            $('#rpaidby').val(p_val);
            if (p_val == 'cash' || p_val == 'other') {
                $('.pcheque_' + pa_no).hide();
                $('.pvoucher_' + pa_no).hide();
                $('.pcc_' + pa_no).hide();
				$('.depreciation_' + pa_no).hide();
                $('.pcash_' + pa_no).show();
                $('#payment_note_' + pa_no).focus();
            } else if (p_val == 'CC' || p_val == 'stripe' || p_val == 'ppp') {
                $('.pcheque_' + pa_no).hide();
                $('.pvoucher_' + pa_no).hide();
                $('.pcash_' + pa_no).hide();
                $('.pcc_' + pa_no).show();
				$('.depreciation_' + pa_no).hide();
                $('#swipe_' + pa_no).focus();
            } else if (p_val == 'Cheque') {
                $('.pcc_' + pa_no).hide();
                $('.depreciation_' + pa_no).hide();
                $('.pcash_' + pa_no).hide();
                $('.pcheque_' + pa_no).show();
                $('#cheque_no_' + pa_no).focus();
                $('.pvoucher_' + pa_no).hide();
            }else if (p_val == 'Voucher') {
                $('.pcc_' + pa_no).hide();
                $('.depreciation_' + pa_no).hide();
                $('.pcash_' + pa_no).hide();
                $('.pvoucher_' + pa_no).show();
                $('#voucher_no_' + pa_no).focus();
                $('.pcheque_' + pa_no).hide();
            } else if(p_val == 'depreciation') {
                $('.pcheque_' + pa_no).hide();
				$('.pvoucher_' + pa_no).hide();
                $('.pcash_' + pa_no).hide();
                $('.pcc_' + pa_no).hide();
				$('.depreciation_' + pa_no).show();
                $('#swipe_' + pa_no).focus();
			} else {
                $('.pcheque_' + pa_no).hide();
                $('.pvoucher_' + pa_no).hide();
                $('.pcc_' + pa_no).hide();
				$('.depreciation_' + pa_no).hide();
                $('.pcash_' + pa_no).hide();
            }
            if (p_val == 'gift_card') {
                $('.gc_' + pa_no).show();
                $('.ngc_' + pa_no).hide();
                $('#gift_card_no_' + pa_no).focus();
            } else {
                $('.ngc_' + pa_no).show();
                $('.gc_' + pa_no).hide();
                $('#gc_details_' + pa_no).html('');
            }
			if(p_val == 'deposit') {
				$('.dp_' + pa_no).show();
                $('.ngc_' + pa_no).hide();
				checkDeposit();
			}else{
				$('.ngc_' + pa_no).show();
                $('.dp_' + pa_no).hide();
                $('#dp_details_' + pa_no).html('');
			}
        });

        $(document).on('click', '#submit-sale', function () {
			var balance = parseFloat($("#balance").text());
			var other_balance = parseFloat($(".curr_balance").text());

			var amount_txt = $("#amount_1").val();
			var other_amount_txt = $("#other_cur_paid").val();

			var cur_pay = $(".curr_tpay").text();

			var deposit_amount = parseFloat($(".deposit_total_amount").text());
			var deposit_balance = parseFloat($(".deposit_total_balance").text());
			deposit_balance = (deposit_amount - Math.abs(amount_txt));

			if(deposit_balance > deposit_amount || deposit_balance < 0 || deposit_amount == 0){
				bootbox.alert('Your Deposit Limited: ' + deposit_amount);
				$('#amount_1').val(deposit_amount);
				$(".deposit_total_balance").text(deposit_amount - $('#amount_1').val()-0);
				return false;
			}

			var arr_push=[];
			$('.sprice').each(function (i) {
				var price = $(this).text();
				arr_push.push(price);

			});
			var i = 0;
			var chks = false;
			$('.cost').each(function (i){
				var cost = $(this).val();
				if(parseFloat(arr_push[i]) >= parseFloat(cost)){
					  chks=false;
                }else{
					chks=true;
					return false;
				}

			});
			var pname = $('.rname').val();

			if(chks == true){
				bootbox.confirm('Product <strong>' + pname + '</strong> its <i>Price</i> is less than or equal to <i>Cost</i>, <br>So do you want to sell it?', function (res) {
					if (res == true) {
						$('#pos_note').val(__getItem('posnote'));
						$('#staff_note').val(__getItem('staffnote'));
						$('#suspend_room').val(__getItem('suspendroom'));
						$('#pos_date').val(__getItem('date'));
						$('#submit-sale').text('<?=lang('loading');?>').attr('disabled', true);
						$('#pos-sale-form').submit();
					}
				});
				return false;
			}else{
				$('#pos_note').val(__getItem('posnote'));
				$('#staff_note').val(__getItem('staffnote'));
				$('#suspend_room').val(__getItem('suspendroom'));
				$('#pos_date').val(__getItem('date'));
				$('#submit-sale').text('<?=lang('loading');?>').attr('disabled', true);
				$('#pos-sale-form').submit();
			}

			if(balance < 0 && other_balance < 0 || (amount_txt == 0 && other_amount_txt ==0)){
				<?php if ($pos_settings->payment_balance == 1) {?>
				bootbox.confirm("<?= lang('paid_l_t_payable'); ?>", function (res) {
					if (res == true) {
						$('#pos_note').val(__getItem('posnote'));
						$('#staff_note').val(__getItem('staffnote'));
						$('#suspend_room').val(__getItem('suspendroom'));
						$('#pos_date').val(__getItem('date'));
						$('#submit-sale').text('<?=lang('loading');?>').attr('disabled', true);
						$('#pos-sale-form').submit();
					}
				});
				return false;
				<?php }else{ ?>
					bootbox.alert("<?= lang('paid_l_t_payable_amount'); ?>");
					return false;
				<?php } ?>
			}else{
				$('#pos_note').val(__getItem('posnote'));
				$('#staff_note').val(__getItem('staffnote'));
				$('#suspend_room').val(__getItem('suspendroom'));
				$('#pos_date').val(__getItem('date'));
				$(this).text('<?=lang('loading');?>').attr('disabled', true);
				$('#pos-sale-form').submit();
			}
        });

		$('.sus_sale').on('click', function (e) {
            var sid = $(this).attr("id");
            if (count > 1) {
                bootbox.confirm("<?= $this->lang->line('leave_alert') ?>", function (gotit) {
                    if (gotit == false) {
                        return true;
                    } else {
                       window.location.href = "<?= site_url('pos/index') ?>/" + sid;
                    }
                });
            } else {
                window.location.href = "<?= site_url('pos/index') ?>/" + sid;
            }
            return false;
        });

		$('.combine_table').on('click', function(e){
			var joined = [];
			$('.chsuspend:checked').each(function(e) {
				valu = $(this).val();
				joined.push(valu);
			});
			joined = joined.join('_');
			window.location.href = "<?= site_url('pos/index') ?>/0/0/" + joined;
		});

        $('.clear_suspend').on('click', function (e) {
			var hrefs = $(this).attr("hrefs");
			bootbox.confirm("<?= $this->lang->line('leave_alert') ?>", function (gotit) {
				if (gotit == true) {
					window.location.href = hrefs;
				}
			});
            return false;
        });

		$('.suspend-button').dblclick(function () {

            ref = $(this).val();
			nref = $(this).attr('id');

            if (!ref || ref == '') {
                bootbox.alert('<?= lang('type_reference_note'); ?>');
                return false;
            } else {
                suspend = $('<span></span>');
                <?php if ($sid) { ?>
                suspend.html('<input type="hidden" name="delete_id" value="<?php echo $sid; ?>" /><input type="hidden" name="suspend" value="yes" /><input type="hidden" name="suspend_id" value="' + ref + '" /><input type="hidden" name="suspend_name" value="' + nref + '" />');
                <?php } else { ?>
                suspend.html('<input type="hidden" name="suspend" value="yes" /><input type="hidden" name="suspend_id" value="' + ref + '" /><input type="hidden" name="suspend_name" value="' + nref + '" />');
                <?php } ?>

                suspend.appendTo("#hidesuspend");
                $('#total_items').val(count - 1);
               $('#pos-sale-form').submit();

            }
        });

		$("#date").datetimepicker({
			format: 'yyyy-mm-dd h:i:ss',
			fontAwesome: true,
			language: 'erp',
			weekStart: 1,
			todayBtn: 1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 0
		}).datetimepicker('update', new Date()).trigger('change');

		$("#date").live('change keyup paste', function(){
			$(".date_c").val($(this).val());
		});

		$('.dateline').datetimepicker({
			format: site.dateFormats.js_sdate,
			fontAwesome: true,
			language: 'erp',
			todayBtn: 1,
			autoclose: 1,
			minView: 2
		});

		$(document).on('focus','.dateline', function(t) {
			$(this).datetimepicker({format: site.dateFormats.js_sdate, fontAwesome: true, todayBtn: 1, autoclose: 1, minView: 2 });
		});

	});

    <?php if($pos_settings->java_applet) { ?>
    $(document).ready(function () {
		$('#print_order_drink').click(function () {
            printBill(order_data);
        });
		$('#print_order_food').click(function () {
            printBill(order_data);
        });
        $('#print_order').click(function () {
            printBill(order_data);
        });
		$('#print_orders').click(function () {
            printBill(order_data);
        });
        $('#print_bill').click(function () {
            printBill(bill_data);
        });
		 $('#print_bills').click(function () {
            printBill(bill_data);
        });
    });
    <?php } else { ?>
    $(document).ready(function () {

        $('#print_order_drink').click(function () {

            Popup($('#order_tbl_drink').html());
            var item_id = Array();
            var susp_id = $("#suspend_id").val();
            $(".order_print_drink").each(function (i) {
                item_id[i] = $(this).attr('itemcode');
            });

            $.ajax({
                type: "get",
                async: false,
                data: {'update_printed': 1, suspend_id: susp_id, item_id: item_id},
                url: site.base_url + "pos/updated_print/",
                dataType: "json",
                success: function (data) {
                    // Popup($('#order_tbl_drink').html());
                }
            });

            $('#print_order_drink').css('pointer-events', 'none');

        });

        $('#print_order_food').click(function () {

            Popup($('#order_tbl_food').html());

            var item_id = Array();
            var susp_id = $("#suspend_id").val();
            $(".order_print_food").each(function (i) {
                item_id[i] = $(this).attr('itemcode');
            });

            $.ajax({
                type: "get",
                async: false,
                data: {'update_printed': 1, suspend_id: susp_id, item_id: item_id},
                url: site.base_url + "pos/updated_print/",
                dataType: "json",
                success: function (data) {
                    // Popup($('#order_tbl_drink').html());
                }
            });
            $('#print_order_food').css('pointer-events', 'none');

        });

        $('#print_order').click(function () {
            Popup($('#order_tbl').html());
        });
		$('#print_orders').click(function () {
            Popup($('#order_tbl').html());
        });
        $('#print_bill').click(function () {
            Popup($('#bill_tbl').html());
        });
		$('#print_bills').click(function () {
            Popup($('#bill_tbl').html());
        });


    });


    <?php } ?>
    $(function () {
        $(".alert").effect("shake");
        setTimeout(function () {
            $(".alert").hide('blind', {}, 500)
        }, 8000);
        <?php if($pos_settings->display_time) { ?>
        var now = new moment();
        $('#display_time').text(now.format((site.dateFormats.js_sdate).toUpperCase() + " HH:mm"));
        setInterval(function () {
            var now = new moment();
            $('#display_time').text(now.format((site.dateFormats.js_sdate).toUpperCase() + " HH:mm"));
        }, 1000);
        <?php } ?>
    });
    <?php if(!$pos_settings->java_applet) { ?>


    function Popup(data) {

		var cssPrint = '' +
        '<style type="text/css">' +
		'@media print {' +
				'* {-webkit-print-color-adjust:exact;-moz-print-color-adjust:exact;}'+
			'  .table {' +
			'	border-collapse: collapse !important;' +
			'  }' +
			'  .table td,' +
			'  .table th {' +
			'	background-color: #fff !important;' +
			'	padding-left: 5px ;'+
			'	padding-right: 5px ;'+
			'	width:20%;' +
			'  }' +
			'  .receipt th {'+
			'	background-color:#07090f !important;'+
			'	color: #fff;'+
			'}'+
			'  .table-bordered th,' +
			'  .table-bordered td {' +
			'	border: 1px solid #ddd !important;' +
			'  }' +
			'	tfoot tr{border-bottom:1px solid #eee}'+
			'	img {display:block;}'+
			'}' +
		'</style>';

        var mywindow = window.open('', 'erp_pos_print', 'height=800,width=450');

					mywindow.document.write('<html><head><title>Print</title>');
					//mywindow.document.write('<link rel="stylesheet" href="<?= $assets ?>styles/helpers/bootstrap.min.css"/>');
					mywindow.document.write(cssPrint);
					mywindow.document.write('</head>');
					mywindow.document.write('<div style="text-align:center"><img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="<?= $biller->company; ?>" /> </div>');

					baz = document.getElementById('b-footer').value;

					mywindow.document.write(data);

					mywindow.document.write('<hr>');
					mywindow.document.write('<div class="well well-sm" style="text-align:center">'+nl2br(baz)+'</div>');
					mywindow.document.write('</html>');
					mywindow.print();

        mywindow.close();
        return true;
    }
    <?php } ?>
	function nl2br (str, is_xhtml) {
		var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
		return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
	}
</script>
<?php
$s2_lang_file = read_file('./assets/config_dumps/s2_lang.js');
foreach (lang('select2_lang') as $s2_key => $s2_line) {
    $s2_data[$s2_key] = str_replace(array('{', '}'), array('"+', '+"'), $s2_line);
}
$s2_file_date = $this->parser->parse_string($s2_lang_file, $s2_data, true);
?>
<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.calculator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>pos/js/plugins.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>pos/js/parse-track-data.js"></script>
<script type="text/javascript" src="<?= $assets ?>pos/js/pos.ajax.js"></script>
<?php if ($pos_settings->java_applet) { ?>
    <script type="text/javascript" src="<?= $assets ?>pos/qz/js/deployJava.js"></script>
    <script type="text/javascript" src="<?= $assets ?>pos/qz/qz-functions.js"></script>
    <script type="text/javascript">
        deployQZ('themes/<?=$Settings->theme?>/assets/pos/qz/qz-print.jar', '<?= $assets ?>pos/qz/qz-print_jnlp.jnlp');
        function printBill(bill) {
            usePrinter("<?= $pos_settings->receipt_printer; ?>");
            printData(bill);
        }
        <?php
        $printers = json_encode(explode('|', $pos_settings->pos_printers));
        echo $printers.';';
        ?>
        function printOrder(order) {
            for (index = 0; index < printers.length; index++) {
                usePrinter(printers[index]);
                printData(order);
            }
        }
    </script>

<?php } ?>
<script>
$(document).ready(function(){

	$("#slref").attr('readonly','readonly');
	$('#ref_st').on('ifChanged', function() {
	  if ($(this).is(':checked')) {
		$("#slref").prop('readonly', false);
		$("#slref").val("");
	  }else{
		$("#slref").prop('readonly', true);
		var temp = $("#temp_reference_no").val();
		$("#slref").val(temp);
		$(".reference_nob").val(temp);

	  }
	});

	$('#slref').change(function(){
		$('.reference_nob').val($(this).val());
	});


    $(".grid-view-btn").click(function(){
        //$(".grid-view").slideToggle();
    });

    // prevent default action upon enter // rothana
	$('body').bind('keypress', function (e) {
		var payable = $("#quick-payable").text();
		var amount_1 = $("#amount_1").val();
        if(amount_1 == 0){
		    if (e.keyCode == 13) {
		        $("#amount_1").val(payable);
                $("#amount_1").focus();
	            $(".currencies_payment").focus();
		    }
        }else if(amount_1 != 0)
        {
           if (e.keyCode == 13) {
           		//$("#amount_1").val(payable);

					var balance = parseFloat($("#balance").text());
					var other_balance = parseFloat($(".curr_balance").text());

					if(balance < 0 && other_balance < 0){

						<?php if ($pos_settings->payment_balance == 1) { ?>

						bootbox.confirm("<?= lang('paid_l_t_payable'); ?>", function (res) {
							if (res == true) {
								$('#pos_note').val(__getItem('posnote'));
								$('#staff_note').val(__getItem('staffnote'));
								$('#suspend_room').val(__getItem('suspendroom'));
								$('#submit-sale').text('<?=lang('loading');?>').attr('disabled', true);
								$('#pos-sale-form').submit();
							}else{
								$("#amount_1").focus();
							}
						});
						<?php }else{ ?>
							bootbox.alert("<?= lang('paid_l_t_payable_amount'); ?>",function(res){
								if(res){
									$(this).hide();
								}
							});
							return false;
						<?php } ?>
					}
					$('#pos-sale-form').submit();
		   }
        }
	});

		var exist_slider= $('#check-slider-exist').text();
		if(exist_slider==1 || exist_slider==5){
			var checkmouse=0;

		var checkmove = $(document).mousemove(function() {
		  checkmouse=0;
		  $('.test-mouse').html(checkmouse);
		});
		setInterval(function () {
			checkmouse=1;
			},1000);

		setInterval(function () {
			if(checkmouse==1){
						$('#category-' + ocat_id).removeClass('active');
						$('#box-item').remove();
						$('#box-item #box-item').remove();
						$('#slide_item').show();
						cat_id=0;
						if(exist_slider==5) {
							$('.btn_gift_card').hide();
						}
			}
			},60000);
		}

	});


</script>
<script type="text/javascript">
$(document).ready(function(){
	
	// Ctrl + S = Save in Payment
    $(window).keypress(function(event) {
        if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19)) return true;
        $('#submit-sale').trigger('click');
        event.preventDefault();
        return false;
    });

	$('body').on('change', '#addr,#addr1,#addr2,#addr3,#addr4,#addr5', function(e) {
		  e.preventDefault();
		  var addr = $(this).val();
			__setItem('addre',addr);
	});

    $('#submit-sale').click(function() {
    // Validation on Bank Account
        var bank_account = $("#bank_account_1").val();
        if (bank_account == 0) {
                $('#bank_account_fg').css('color', 'rgb(174, 13, 13)');
                $('#bank_account_span').text('Please select a Bank Account!');
            return false;
        }

        // return true;
    });

});
</script>
<script type="text/javascript" charset="UTF-8"><?= $s2_file_date ?></script>
<div id="ajaxCall"><i class="fa fa-spinner fa-pulse"></i></div>
<p style="display:none;" id="check-slider-exist" ><?php  echo $layout; ?> </p>
</body>
</html>
