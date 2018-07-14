<?php
 foreach ($monthly_incomes as $monthly_income) {
		$row_income = abs($monthly_income->income);
		$expend = ($monthly_income->cost+$monthly_income->operation);
        $months[] = date('M-Y', strtotime($monthly_income->MONTH));
        $income[] = $row_income;
        $cost[] = $monthly_income->cost;
        $operation[] = $monthly_income->operation;
		$profit[] = $row_income-$expend;
        //$purchases[] = $month_sale->purchases;
        //$tax3[] = $month_sale->ptax;
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
                    name: '<?= lang("income"); ?>',
                    data: [<?php
                    echo implode(', ', $income);
                    ?>]
                },
                  
                    {
                        type: 'column',
                        name: '<?= lang("cost"); ?>',
                        data: [<?php
                    echo implode(', ', $cost);
                    ?>]
                    }, 
					{
                        type: 'column',
                        name: '<?= lang("operation"); ?>',
                        data: [<?php
                    echo implode(', ', $operation);
                    ?>]
                    }, {
                        type: 'spline',
                        name: '<?= lang("profit"); ?>',
                        data: [<?php
                    echo implode(', ', $profit);
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
                    },{
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
    
	
<script type="text/javascript">
    $(document).ready(function () {
        $('#form_search').hide();
        
        $('.toggle_down').click(function () {
            $("#form_search").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form_search").slideUp();
            return false;
        });
    });
</script>

    <div class="box" style="margin-top: 15px;">
        <div class="box-header">
            <h2 class="blue"><i class="fa-fw fa fa-bar-chart-o"></i><?= lang('profit_chart'); ?></h2>
			<div class="box-icon">
						<ul class="btn-tasks">
							<li class="dropdown"><a href="javascript:void(0)" title="<?= lang('print') ?>" onclick="window.print()"><i
										class="icon fa fa-print"></i></a></li>
							<li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
										class="icon fa fa-toggle-up"></i></a></li>
							<li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
										class="icon fa fa-toggle-down"></i></a></li>
						</ul>
					</div>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-lg-12">
                    <p class="introtext"><?= lang('customize_report'); ?></p>
					
                <div id="form_search">

                    <?php echo form_open("reports/profit_chart"); ?>
                    <div class="row">
                        
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="biller"><?= lang("biller"); ?></label>
                                <?php
                                $bl[""] = "";
                                foreach ($billers as $biller) {
                                    $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                }
                                echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="year"><?= lang("year"); ?></label>
                                <?php
                                $years[""] = "";
								$cur_year = date('Y');
								for ($i=0; $i<=20; $i++) {
									$year_value = $cur_year--;
									$years[$year_value] = $year_value;
								}
                                
                                echo form_dropdown('year', $years, (isset($_POST['year']) ? $_POST['year'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("year") . '"');
                                ?>
                            </div>
                        </div>
                    <div class="form-group">
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
				</div>
                <div class="clearfix"></div>
				<hr />

                    <div id="chart" style="width:100%; height:450px;"></div>
                    <p class="text-center"><?= lang("chart_lable_toggle"); ?></p>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getSalesReport/pdf/?v=1'.$v)?>";
            return false;
        });
        $('#xls').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/getSalesReport/0/xls/?v=1'.$v)?>";
            return false;
        });
        $('#image').click(function (event) {
            event.preventDefault();
            html2canvas($('.box'), {
                onrendered: function (canvas) {
                    var img = canvas.toDataURL()
                    window.open(img);
                }
            });
            return false;
        });
    });
</script>