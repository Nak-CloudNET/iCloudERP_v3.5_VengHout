<?php if ($Owner || $Admin) {
    foreach ($monthly_sales as $month_sale) {
        $months[] = date('M-Y', strtotime($month_sale->month));
        $sales[] = $month_sale->sales;
        $tax1[] = $month_sale->tax1;
        $tax2[] = $month_sale->tax2;
        $purchases[] = $month_sale->purchases;
        $tax3[] = $month_sale->ptax;
    }
    ?>
    <style type="text/css" media="screen">
        .tooltip-inner {
            max-width: 500px;
        }
    </style>
    <script src="<?= $assets; ?>js/hc/highcharts.js"></script>
    <script type="text/javascript">
        $(function () {
            Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
                return {
                    radialGradient: {cx: 0.5, cy: 0.3, r: 0.7},
                    stops: [[0, color], [1, Highcharts.Color(color).brighten(-0.3).get('rgb')]]
                };
            });
            $('#chart').highcharts({
                chart: {},
                credits: {enabled: false},
                title: {text: ''},
                xAxis: {categories: <?= json_encode($months); ?>},
                yAxis: {min: 0, title: ""},
                tooltip: {
                    shared: true,
                    followPointer: true,
                    formatter: function () {
                        if (this.key) {
                            return '<div class="tooltip-inner hc-tip" style="margin-bottom:0;">' + this.key + '<br><strong>' + currencyFormat(this.y) + '</strong> (' + formatNumber(this.percentage) + '%)';
                        } else {
                            var s = '<div class="well well-sm hc-tip" style="margin-bottom:0;"><h2 style="margin-top:0;">' + this.x + '</h2><table class="table table-striped"  style="margin-bottom:0;">';
                            $.each(this.points, function () {
                                s += '<tr><td style="color:{series.color};padding:0">' + this.series.name + ': </td><td style="color:{series.color};padding:0;text-align:right;"> <b>' +
                                currencyFormat(this.y) + '</b></td></tr>';
                            });
                            s += '</table></div>';
                            return s;
                        }
                    },
                    useHTML: true,
                    borderWidth: 0,
                    shadow: false,
                    valueDecimals: site.settings.decimals,
                    style: {
                        fontSize: '14px',
                        padding: '0',
                        color: '#000000'
                    }
                },
                series: [{
                    type: 'column',
                    name: '<?= lang("sp_tax"); ?>',
                    data: [<?php
                    echo implode(', ', $tax1);
                    ?>]
                },
                    {
                        type: 'column',
                        name: '<?= lang("order_tax"); ?>',
                        data: [<?php
                    echo implode(', ', $tax2);
                    ?>]
                    },
                    {
                        type: 'column',
                        name: '<?= lang("sales"); ?>',
                        data: [<?php
                    echo implode(', ', $sales);
                    ?>]
                    }, {
                        type: 'spline',
                        name: '<?= lang("purchases"); ?>',
                        data: [<?php
                    echo implode(', ', $purchases);
                    ?>],
                        marker: {
                            lineWidth: 2,
                            states: {
                                hover: {
                                    lineWidth: 4
                                }
                            },
                            lineColor: Highcharts.getOptions().colors[3],
                            fillColor: 'white'
                        }
                    }, {
                        type: 'spline',
                        name: '<?= lang("pp_tax"); ?>',
                        data: [<?php
                    echo implode(', ', $tax3);
                    ?>],
                        marker: {
                            lineWidth: 2,
                            states: {
                                hover: {
                                    lineWidth: 4
                                }
                            },
                            lineColor: Highcharts.getOptions().colors[3],
                            fillColor: 'white'
                        }
                    }, {
                        type: 'pie',
                        name: '<?= lang("stock_value"); ?>',
                        data: [
                            ['', 0],
                            ['', 0],
                            ['<?= lang("stock_value_by_price"); ?>', <?php echo $stock->stock_by_price; ?>],
                            ['<?= lang("stock_value_by_cost"); ?>', <?php echo $stock->stock_by_cost; ?>],
                        ],
                        center: [80, 42],
                        size: 80,
                        showInLegend: false,
                        dataLabels: {
                            enabled: false
                        }
                    }]
            });
        });
    </script>
<?php } ?>
    <div class="box">
        <div class="box-header">
            <h2 class="blue"><i class="fa fa-th"></i><span class="break"></span><?= lang('chart_report') ?></h2>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/warehouse_stock') ?>">
                        <i class="fa fa-building"></i>

                        <p><?= lang('warehouse_stock') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/category_stock') ?>">
                        <i class="fa fa-bar-chart-o"></i>

                        <p><?= lang('category_stock_chart') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/profit_chart') ?>">
                        <i class="fa fa-bar-chart-o"></i>

                        <p><?= lang('profit_chart') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/cash_chart') ?>">
                        <i class="fa fa-bar-chart-o"></i>

                        <p><?= lang('cash_analysis_chart') ?></p>
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
		<div class="box-header">
            <h2 class="blue"><i class="fa fa-money"></i><span class="break"></span><?= lang('profit_report') ?></h2>
        </div>
		<div class="box-content">
			<div class="row">
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="blightOrange white quick-button" href="<?= site_url('reports/profit_loss') ?>">
                        <i class="fa fa-money"></i>

                        <p><?= lang('profit_and_loss') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="blightOrange white quick-button" href="<?= site_url('reports/payments') ?>">
                        <i class="fa fa-money"></i>

                        <p><?= lang('payments_report') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="blightOrange white quick-button" href="<?= site_url('reports/register') ?>">
                        <i class="fa fa-money"></i>

                        <p><?= lang('register_report') ?></p>
                    </a>
                </div>
			</div>
		</div>
		<div class="box-header">
            <h2 class="blue"><i class="fa fa-barcode"></i><span class="break"></span><?= lang('product_report') ?></h2>
        </div>
		<div class="box-content">
			<div class="row">
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bred white quick-button" href="<?= site_url('reports/quantity_alerts') ?>">
                        <i class="fa fa-bar-chart-o"></i>

                        <p><?= lang('product_quantity_alerts') ?></p>
                    </a>
                </div>
				<!--<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bred white quick-button" href="<?= site_url('reports/expiry_alerts') ?>">
                        <i class="fa fa-bar-chart-o"></i>

                        <p><?= lang('product_expiry_alerts') ?></p>
                    </a>
                </div>-->
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/products') ?>">
                        <i class="fa fa-barcode"></i>

                        <p><?= lang('products_report') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/warehouse_reports') ?>">
                        <i class="fa fa-barcode"></i>

                        <p><?= lang('warehouse_reports') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/product_in_out') ?>">
                        <i class="fa fa-barcode"></i>

                        <p><?= lang('products_in_out') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/product_monthly_in_out') ?>">
                        <i class="fa fa-barcode"></i>

                        <p><?= lang('monthly_product') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/product_daily_in_out') ?>">
                        <i class="fa fa-barcode"></i>

                        <p><?= lang('daily_product') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/categories') ?>">
                        <i class="fa fa-barcode"></i>

                        <p><?= lang('categories_report') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/categories_value') ?>">
                        <i class="fa fa-barcode"></i>

                        <p><?= lang('categories_value_report') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/inventory_valuation_detail') ?>">
                        <i class="fa fa-barcode"></i>

                        <p><?= lang('inventory_valuation_detail') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/supplier_by_items') ?>">
                        <i class="fa fa-barcode"></i>

                        <p><?= lang('supplier_products') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bblue white quick-button" href="<?= site_url('reports/customer_products') ?>">
                        <i class="fa fa-barcode"></i>

                        <p><?= lang('product_customers') ?></p>
                    </a>
                </div>
			</div>
		</div>
		<div class="box-header">
            <h2 class="blue"><i class="fa fa-heart"></i><span class="break"></span><?= lang('sale_report') ?></h2>
        </div>
		<div class="box-content">
			<div class="row">
				 <div class="col-md-2 col-xs-4 padding1010">
                    <a class="bdarkGreen white quick-button" href="<?= site_url('reports/sales') ?>">
                        <i class="fa fa-heart"></i>

                        <p><?= lang('sales_report') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bdarkGreen white quick-button" href="<?= site_url('reports/sales_detail') ?>">
                        <i class="fa fa-heart"></i>

                        <p><?= lang('sales_detail_report') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bdarkGreen white quick-button" href="<?= site_url('reports/sales_profit') ?>">
                        <i class="fa fa-heart"></i>

                        <p><?= lang('sales_profit_report') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bdarkGreen white quick-button" href="<?= site_url('reports/daily_sales') ?>">
                        <i class="fa fa-calendar-o"></i>

                        <p><?= lang('daily_sales') ?></p>
                    </a>
                </div>

                <div class="col-md-2 col-xs-4 padding1010">
                    <a class="bdarkGreen white quick-button" href="<?= site_url('reports/monthly_sales') ?>">
                        <i class="fa fa-calendar-o"></i>

                        <p><?= lang('monthly_sales') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bdarkGreen white quick-button" href="<?= site_url('reports/sales_discount') ?>">
                        <i class="fa fa-heart"></i>

                        <p><?= lang('sales_discount_report') ?></p>
                    </a>
                </div>

                <div class="col-md-2 col-xs-4 padding1010">
                    <a class="bdarkGreen white quick-button" href="<?= site_url('reports/deliveries') ?>">
                        <i class="fa fa-users"></i>

                        <p><?= lang('sale_by_delivery_person') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="bdarkGreen white quick-button" href="<?= site_url('reports/suspends') ?>">
                        <i class="fa fa-heart"></i>

                        <p><?= lang('suspend_report') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="borange white quick-button" href="<?= site_url('reports/customer_transfers') ?>">
                        <i class="fa fa-users"></i>

                        <p><?= lang('customer_transfers') ?></p>
                    </a>
                </div>
				 <div class="col-md-2 col-xs-4 padding1010">
                    <a class="borange white quick-button" href="<?= site_url('reports/customers') ?>">
                        <i class="fa fa-users"></i>

                        <p><?= lang('customers_report') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="borange white quick-button" href="<?= site_url('reports/staff_report') ?>">
                        <i class="fa fa-users"></i>

                        <p><?= lang('staff_report') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="borange white quick-button" href="<?= site_url('reports/saleman') ?>">
                        <i class="fa fa-users"></i>

                        <p><?= lang('saleman_report') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="borange white quick-button" href="<?= site_url('reports/shops') ?>">
                        <i class="fa fa-building"></i>

                        <p><?= lang('biller_report') ?></p>
                    </a>
                </div>
			</div>
		</div>
		<div class="box-header">
            <h2 class="blue"><i class="fa fa-star"></i><span class="break"></span><?= lang('purchase_report') ?></h2>
        </div>
		<div class="box-content">
			<div class="row">
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="blightBlue white quick-button" href="<?= site_url('reports/purchases') ?>">
                        <i class="fa fa-star"></i>

                        <p><?= lang('purchases_report') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="blightBlue white quick-button" href="<?= site_url('reports/daily_purchases') ?>">
                        <i class="fa fa-star"></i>

                        <p><?= lang('daily_purchases') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="blightBlue white quick-button" href="<?= site_url('reports/monthly_purchases') ?>">
                        <i class="fa fa-star"></i>

                        <p><?= lang('monthly_purchases') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="borange white quick-button" href="<?= site_url('reports/suppliers') ?>">
                        <i class="fa fa-users"></i>

                        <p><?= lang('suppliers_report') ?></p>
                    </a>
                </div>
			</div>
		</div>
		<div class="box-header">
            <h2 class="blue"><i class="fa fa-book"></i><span class="break"></span><?= lang('ac_report') ?></h2>
        </div>
		<div class="box-content">
			<div class="row">
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="blightOrange white quick-button" href="<?= site_url('reports/ledger') ?>">
                        <i class="fa fa-book"></i>

                        <p><?= lang('ledger') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="blightOrange white quick-button" href="<?= site_url('reports/trial_balance') ?>">
                        <i class="fa fa-list"></i>

                        <p><?= lang('trial_balance') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="borange white quick-button" href="<?= site_url('reports/balance_sheet') ?>">
                        <i class="fa fa-balance-scale"></i>

                        <p><?= lang('balance_sheet') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="borange white quick-button" href="<?= site_url('reports/balance_sheet_details') ?>">
                        <i class="fa fa-balance-scale"></i>

                        <p><?= lang('balance_sheet_details') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="blightOrange white quick-button" href="<?= site_url('reports/income_statement') ?>">
                        <i class="fa fa-money"></i>

                        <p><?= lang('income_statement') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="blightOrange white quick-button" href="<?= site_url('reports/income_statement_detail') ?>">
                        <i class="fa fa-money"></i>

                        <p><?= lang('income_statement_detail') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="blightOrange white quick-button" href="<?= site_url('reports/income_statement_by_project') ?>">
                        <i class="fa fa-money"></i>

                        <p><?= lang('income_statement_by_project') ?></p>
                    </a>
                </div>
				<div class="col-md-2 col-xs-4 padding1010">
                    <a class="blightOrange white quick-button" href="<?= site_url('reports/cash_books') ?>">
                        <i class="fa fa-money"></i>

                        <p><?= lang('cash_book') ?></p>
                    </a>
                </div>
			</div>
		</div>
    </div>

<?php if ($Owner || $Admin) { ?>
    <div class="box" style="margin-top: 15px;">
        <div class="box-header">
            <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i><?= lang('overview_chart'); ?></h2>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-lg-12">
                    <p class="introtext"><?php echo lang('overview_chart_heading'); ?></p>

                    <div id="chart" style="width:100%; height:450px;"></div>
                    <p class="text-center"><?= lang("chart_lable_toggle"); ?></p>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
