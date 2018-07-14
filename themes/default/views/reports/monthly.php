<style type="text/css">
    .dfTable th, .dfTable td {
        text-align: center;
        vertical-align: middle;
    }

    .dfTable td {
        padding: 2px;
    }

    .data tr:nth-child(odd) td {
        color: #2FA4E7;
    }

    .data tr:nth-child(even) td {
        text-align: right;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $('#form').hide();
        $('.toggle_down').click(function () {
            $("#form").slideDown();
            return false;
        });
        $('.toggle_up').click(function () {
            $("#form").slideUp();
            return false;
        });

    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-calendar"></i><?= lang('monthly_sales'); ?><?php if(is_numeric($biller_id)){ ?>
                <tr>
                    <th colspan="8" style="text-align: left;"> >>
                        <?php
                        $this->db->select('company')->from('companies')->where('id',$biller_id);
                        $q=$this->db->get();
                        if($q->num_rows()>0){
                            foreach (($q->result()) as $row) {
                                $data[] = $row;
                            }
                            echo $data[0]->company;
                        }
                        ?>
                    </th>
                </tr>
            <?php }else{
                $this->session->set_userdata('biller_id',"");
                echo ">>All Project";
            } ?></h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" class="toggle_up tip" title="<?= lang('hide_form') ?>"><i
                                class="icon fa fa-toggle-up"></i></a></li>
                <li class="dropdown"><a href="#" class="toggle_down tip" title="<?= lang('show_form') ?>"><i
                                class="icon fa fa-toggle-down"></i></a></li>
            </ul>
        </div>
        <div class="box-icon">
            <ul class="btn-tasks">
                <?php if (!empty($warehouses) && !$this->session->userdata('warehouse_id')) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?=lang("warehouses")?>"></i></a>
                        <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?=site_url('reports/monthly_sales/0/'.$year)?>"><i class="fa fa-building-o"></i> <?=lang('all_warehouses')?></a></li>
                            <li class="divider"></li>
                            <?php
                                foreach ($warehouses as $warehouse) {
                                        echo '<li><a href="' . site_url('reports/monthly_sales/'.$warehouse->id.'/'.$year) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                                    }
                                ?>
                        </ul>
                    </li>
                <?php } ?>
                <li class="dropdown">
                    <a href="#" id="pdf" class="tip" title="<?= lang('download_pdf') ?>">
                        <i class="icon fa fa-file-pdf-o"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" id="image" class="tip" title="<?= lang('save_image') ?>">
                        <i class="icon fa fa-file-picture-o"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang("reports_calendar_text") ?></p>
                <div id="form">

                    <?php echo form_open("reports/monthly_sales",'method="GET"'); ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <?= lang("biller", "biller"); ?>
                                <?php
                                $wh[""] = "ALL";
                                foreach ($billers as $biller) {
                                    $wh[$biller->id] = $biller->company.' / '.$biller->name;
                                }
                                echo form_dropdown('biller', $wh, isset($biller_id)?$biller_id:"", 'class="form-control" id="biller" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("biller") . '"');
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-1"style="padding-left:0px;">
                        <div
                                class="controls"> <?php echo form_submit('submit_report', $this->lang->line("submit"), 'class="btn btn-primary"'); ?> </div>
                    </div>

                    <?php echo form_close(); ?>

                </div>
                <div class="table-responsive" id="style">
                    <table class="table table-bordered table-striped dfTable reports-table">
                        <thead>
							<tr class="year_roller">
								<th><a class="white" href="reports/monthly_sales/<?php echo $year - 1; ?>">&lt;&lt;</a></th>
								<th colspan="10"> <?php echo $year; ?></th>
								<th><a class="white" href="reports/monthly_sales/<?php echo $year + 1; ?>">&gt;&gt;</a></th>
							</tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/monthly_profitByBiller/'.$year.'/01'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_january"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/monthly_profitByBiller/'.$year.'/02'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_february"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/monthly_profitByBiller/'.$year.'/03'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_march"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/monthly_profitByBiller/'.$year.'/04'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_april"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/monthly_profitByBiller/'.$year.'/05'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_may"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/monthly_profitByBiller/'.$year.'/06'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_june"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/monthly_profitByBiller/'.$year.'/07'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_july"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/monthly_profitByBiller/'.$year.'/08'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_august"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/monthly_profitByBiller/'.$year.'/09'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_september"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/monthly_profitByBiller/'.$year.'/10'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_october"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/monthly_profitByBiller/'.$year.'/11'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_november"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/monthly_profitByBiller/'.$year.'/12'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_december"); ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <?php
                            if (!empty($sales)) {
                                foreach ($sales as $value) {
                                    $array[$value->date] = "<table class='table table-bordered table-hover table-striped table-condensed data' style='margin:0;'><tbody>
									<tr><td>" . $this->lang->line("amount") . "</td></tr>
									<tr><td>" . $this->erp->formatMoney($value->total) . "</td></tr>
									<tr><td>" . $this->lang->line("order_discount") . "</td></tr>
									<tr><td>" . $this->erp->formatMoney($value->order_discount) . "</td></tr>
									<tr><td>" . $this->lang->line("shipping") . "</td></tr>
									<tr><td>" . $this->erp->formatMoney($value->shipping) . "</td></tr>
									<tr><td>" . $this->lang->line("product_tax") . "</td></tr>
									<tr><td>" . $this->erp->formatMoney($value->tax1) . "</td></tr>
									<tr><td>" . $this->lang->line("refund") . "</td></tr>
									<tr><td>" . $this->erp->formatMoney($value->t_return) . "</td></tr>
									<tr><td>" . $this->lang->line("order_tax") . "</td></tr>
									<tr><td>" . $this->erp->formatMoney($value->tax2) . "</td></tr>
									<tr><td>" . $this->lang->line("total") . "</td></tr>
									<tr><td>" . $this->erp->formatMoney(($value->total - $value->t_return - $value->order_discount) + $value->tax2 + $value->shipping)  . "</td></tr>
									<tr><td>" . $this->lang->line("award_points") . "</td></tr>
									<tr><td>" . intval($value->total / $this->Settings->each_sale)  . "</td></tr>
									</tbody></table>";
                                }
                                
                                for ($i = 1; $i <= 12; $i++) {
                                    echo '<td width="8.3%">';
                                    if (isset($array[$i])) {
                                        echo $array[$i];
                                    } else {
                                        echo '<strong>0</strong>';
                                    }
                                    echo '</td>';
                                }
                            } else {
                                for ($i = 1; $i <= 12; $i++) {
                                    echo '<td width="8.3%"><strong>0</strong></td>';
                                }
                            }
                            ?>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/monthly_sales/'.$year.'/pdf')?>";
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
		if ($(window).width() < 1024) {
		    $('#style').css('width', '100%');
			$('#style').css('overflow-x', 'scroll');
			$('#style').css('white-space', 'nowrap');
		}
    });
</script>
