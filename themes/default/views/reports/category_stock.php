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
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {text: ''},
            credits: {enabled: false},
            tooltip: {
                formatter: function () {
                    return '<div class="tooltip-inner hc-tip" style="margin-bottom:0;">' + this.key + '<br><strong>' + currencyFormat(this.y) + '</strong> (' + formatNumber(this.percentage) + '%)';
                },
                followPointer: true,
                useHTML: true,
                borderWidth: 0,
                shadow: false,
                valueDecimals: site.settings.decimals,
                style: {fontSize: '14px', padding: '0', color: '#000000'}
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            return '<h3 style="margin:-15px 0 0 0;"><b>' + this.point.name + '</b>:<br><b> ' + currencyFormat(this.y) + '</b></h3>';
                        },
                        useHTML: true
                    }
                }
            },
            series: [{
                type: 'pie',
                name: '<?php echo $this->lang->line("stock_value"); ?>',
                data: [
					<?php 
						foreach($stocks as $stock){
					?>
							['<?php echo $stock->category_name; ?>', <?php echo $stock->by_price; ?>],
					<?php
						}
					?>
                    
                ]

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
            <h2 class="blue"><i
                    class="fa-fw fa fa-bar-chart-o"></i><?= lang('category_stock_chart') . ' (' . ($warehouse ? $warehouse->name : lang('all_warehouses')) . ')'; ?>
            </h2>

            <div class="box-icon">
                <ul class="btn-tasks">
					<li class="dropdown"><a href="javascript:void(0)" title="<?= lang('print') ?>" onclick="window.print()"><i
										class="icon fa fa-print"></i></a></li>
                    <?php if (!empty($warehouses) && ($Owner || $Admin)) { ?>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i
                                    class="icon fa fa-building-o tip" data-placement="left"
                                    title="<?= lang("warehouses") ?>"></i></a>
                            <ul class="dropdown-menu pull-right" class="tasks-menus" role="menu"
                                aria-labelledby="dLabel">

                                <li><a href="<?= site_url('reports/category_stock') ?>"><i
                                            class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                                <li class="divider"></li>
                                <?php
                                foreach ($warehouses as $warehouse) {
                                    echo '<li ' . ($warehouse_id && $warehouse_id == $warehouse->id ? 'class="active"' : '') . '><a href="' . site_url('reports/category_stock/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
						<li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
										class="icon fa fa-toggle-up"></i></a></li>
							<li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
										class="icon fa fa-toggle-down"></i></a></li>
                    <?php } ?>
					
                </ul>
            </div>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-lg-12">
                    <p class="introtext"><?php echo lang('warehouse_stock_heading'); ?></p>
					
					<div id="form_search">

                    <?php echo form_open("reports/category_stock"); ?>
                    <div class="row">
						<div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="customer"><?= lang("customer"); ?></label>
                                <?php
                                $bl[""] = "";
                                foreach ($customers as $biller) {
                                    $bl[$biller->id] = $biller->name;
                                }
                                echo form_dropdown('customer', $bl, (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                                ?>
                            </div>
                        </div>
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
                                <?= lang("start_date", "start_date"); ?>
                                <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control datetime" id="start_date"'); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <?= lang("end_date", "end_date"); ?>
                                <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control datetime" id="end_date"'); ?>
                            </div>
                        </div>

                    <div class="form-group">
						<input type="hidden" name="user_post" value="1"/>
                        <div
                            class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>
                    <?php echo form_close(); ?>

                </div>
				</div>
					
					
                    <?php if ($totals) { ?>

                        <div class="small-box padding1010 col-sm-6 bblue">
                            <div class="inner clearfix">
                                <a>
                                    <h3><?= $this->erp->formatQuantity($totals->total_items) ?></h3>

                                    <p><?= lang('total_items') ?></p>
                                </a>
                            </div>
                        </div>

                        <div class="small-box padding1010 col-sm-6 bdarkGreen">
                            <div class="inner clearfix">
                                <a>
                                    <h3><?= $this->erp->formatQuantity($totals->total_quantity) ?></h3>

                                    <p><?= lang('total_quantity') ?></p>
                                </a>
                            </div>
                        </div>
                        <div class="clearfix" style="margin-top:20px;"></div>
                    <?php } ?>
                    <div id="chart" style="width:100%; height:450px;"></div>
                </div>
            </div>
        </div>
    </div>