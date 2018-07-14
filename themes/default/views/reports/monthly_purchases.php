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
<?php
if($warehouse_id){
    $warehouse_id = explode(',',$warehouse_id);
}
//$this->erp->print_arrays($warehouse_id);
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-calendar"></i>
            <?= lang('daily_purchases')?><?php
            if(count($warehouse_id) > 1){
                echo '('.lang('all_warehouses').')';
            }elseif (count($warehouse_id) == 1){
                echo '('. $sel_warehouse->name .')';
            }else{
                echo '('.lang('all_warehouses').')';
            }; ?>
        </h2>

        <div class="box-icon">
            <ul class="btn-tasks">
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

                <div class="table-responsive" id="style">
                    <table class="table table-bordered table-striped dfTable reports-table">
                        <thead>
                        <tr class="year_roller">
                            <th><a class="white" href="<?= site_url('reports/monthly_purchases/'.($warehouse_id ? $warehouse_id : 0).'/'.($year-1)); ?>">&lt;&lt;</a></th>
                            <th colspan="10"> <?php echo $year; ?></th>
                            <th><a class="white" href="<?= site_url('reports/monthly_purchases/'.($warehouse_id ? $warehouse_id : 0).'/'.($year+1)); ?>">&gt;&gt;</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/purchase_monthly/'.$year.'/01'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_january"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/purchase_monthly/'.$year.'/02'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_february"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/purchase_monthly/'.$year.'/03'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_march"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/purchase_monthly/'.$year.'/04'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_april"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/purchase_monthly/'.$year.'/05'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_may"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/purchase_monthly/'.$year.'/06'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_june"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/purchase_monthly/'.$year.'/07'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_july"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/purchase_monthly/'.$year.'/08'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_august"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/purchase_monthly/'.$year.'/09'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_september"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/purchase_monthly/'.$year.'/10'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_october"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/purchase_monthly/'.$year.'/11'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_november"); ?>
                                </a>
                            </td>
                            <td class="bold text-center">
                                <a href="<?= site_url('reports/purchase_monthly/'.$year.'/12'); ?>" data-toggle="modal" data-target="#myModal">
                                    <?= lang("cal_december"); ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <?php
                            if (!empty($purchases)) {
                                foreach ($purchases as $value) {
                                    $array[$value->date] = "<table class='table table-bordered table-hover table-striped table-condensed data' style='margin:0;'>
									<tbody>
										<tr>
											<td>" . $this->lang->line("amount") . "</td>
										</tr>
										<tr>
											<td>" . $this->erp->formatMoney($value->total) . "</td>
										</tr>
										<tr>
											<td>" . $this->lang->line("order_discount") . "</td>
										</tr>
										<tr>
											<td>" . $this->erp->formatMoney($value->discount) . "</td>
										</tr>
										<tr>
											<td>" . $this->lang->line("shipping") . "</td>
										</tr>
										<tr>
											<td>" . $this->erp->formatMoney($value->shipping) . "</td>
										</tr>
										<tr>
											<td>" . $this->lang->line("product_tax") . "</td>
										</tr>
										<tr>
											<td>" . $this->erp->formatMoney($value->tax1) . "</td>
										</tr>
										<tr>
											<td>" . $this->lang->line("order_tax") . "</td>
										</tr>
										<tr>
											<td>" . $this->erp->formatMoney($value->tax2) . "</td>
										</tr>
										<tr>
											<td>" . $this->lang->line("total") . "</td>
										</tr>
										<tr>
											<td>" . $this->erp->formatMoney(($value->total + $value->shipping + $value->tax2) - $value->discount) . "</td>
										</tr>
										</tbody>
									</table>";
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
            window.location.href = "<?=site_url('reports/monthly_purchases/'.($warehouse_id ? $warehouse_id : 0).'/'.$year.'/pdf')?>";
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
