<style>
    .table th {
        text-align: center;
    }

    .table td {
        padding: 2px;
    }

    .table td .table td:nth-child(odd) {
        text-align: left;
    }

    .table td .table td:nth-child(even) {
        text-align: right;
    }

    .table a:hover {
        text-decoration: none;
    }

    .cl_wday {
        text-align: center;
        font-weight: bold;
    }

    .cl_equal {
        width: 14%;
    }

    td.day {
        width: 14%;
        padding: 0 !important;
        vertical-align: top !important;
    }

    .day_num {
        width: 100%;
        text-align: left;
        cursor: pointer;
        margin: 0;
        padding: 8px;
    }

    .day_num:hover {
        background: #F5F5F5;
    }

    .content {
        width: 100%;
        text-align: left;
        color: #428bca;
        padding: 8px;
    }

    .highlight {
        color: #0088CC;
        font-weight: bold;
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
        <h2 class="blue"><i class="fa-fw fa fa-calendar"></i><?= lang('daily_sales'); ?>   <?php if(is_numeric($biller_id)){ ?>
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
            <div class="row">
                <div class="col-lg-12">

                    <p class="introtext"><?= lang('customize_report'); ?></p>

                    <div id="form">

                        <?php echo form_open("reports/daily_sales/$year/$month",'method="POST"'); ?>
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
                    <div class="clearfix"></div>

                    <div class="table-responsive">

                        <table class="table table-bordered table-condensed table-striped">
                            <thead>
                            <tr class="info-head">
                                <th style="min-width:30px; width: 30px; text-align: center;">
                                    <input class="checkbox checkth" type="checkbox" name="val" />
                                </th>
                                <th style="width:200px;" class="center"><?= lang("item"); ?></th>
                                <th style="width:150px;"><?= lang("category_expense"); ?></th>
                                <th style="width:150px;"><?= lang("item_description"); ?></th>
                                <th style="width:150px;"><?= lang("quantity"); ?></th>
                                <th style="width:150px;"><?= lang("unit"); ?></th>
                                <th style="width:150px;display:none"><?= lang("cost"); ?></th>
                                <th style="width:150px;"><?= lang("Total"); ?></th>

                            </tr>
                            </thead>
                            <?php
                            if(is_array($using_stock)){
                                foreach($using_stock as $stock){
                                    $query=$this->db->query("
							         SELECT
										erp_enter_using_stock_items.*, erp_products. NAME AS product_name,
										erp_expense_categories. NAME AS exp_cate_name,
										erp_enter_using_stock_items.unit AS unit_name,
										erp_products.cost,
										erp_position. NAME AS pname,
										erp_reasons.description AS rdescription,
										erp_product_variants.qty_unit AS variant_qty,
										erp_product_variants.name as var_name
									FROM
										erp_enter_using_stock_items
									LEFT JOIN erp_products ON erp_products. CODE = erp_enter_using_stock_items. CODE
									LEFT JOIN erp_position ON erp_enter_using_stock_items.description = erp_position.id 
									LEFT JOIN erp_reasons ON erp_enter_using_stock_items.reason = erp_reasons.id
									LEFT JOIN erp_product_variants ON erp_enter_using_stock_items.option_id = erp_product_variants.id
									LEFT JOIN erp_expense_categories ON erp_enter_using_stock_items.exp_cate_id = erp_expense_categories.id where erp_enter_using_stock_items.reference_no='{$stock->refno}' 
									 ")->result();


                                    ?>
                                    <tbody>
                                    <tr class="bold">
                                        <td style="min-width:30px; width: 30px; text-align: center;background-color:#E9EBEC">
                                            <input type="checkbox" name="val[]" class="checkbox multi-select input-xs" value="<?= $stock->id; ?>" />
                                        </td>
                                        <td colspan="7" style="font-size:14px;background-color:#E9EBEC;color:#265F7B  "><?=$stock->refno ." >> ".$this->erp->hrld($stock->date) ." >> ".$stock->company ." >> ".$stock->warehouse_name ." >> ".$stock->username ?></td>

                                    </tr>
                                    <?php foreach($query as $q){ ?>
                                        <tr>
                                            <td style="min-width:30px; width: 30px; text-align: center;">

                                            </td>
                                            <td><?=$q->product_name ."(".$q->code .")" ?></td>
                                            <td><?=$q->exp_cate_name ?></td>
                                            <td><?=$q->rdescription ?></td>
                                            <td class="text-center"><?=$this->erp->formatQuantity($q->qty_use)?></td>
                                            <td class="text-center"><?=!empty($q->var_name)?$q->var_name :$q->unit_name ?></td>
                                            <td class="text-right"style="display:none;"><?=$this->erp->formatMoney($q->cost)?></td>
                                            <td class="text-right"><?=$this->erp->formatMoney($q->cost*$q->qty_use) ?></td>
                                        </tr>
                                    <?php }?>




                                    </tbody>
                                <?php } } ?>
                        </table>
                    </div>
                    <div class=" text-right">
                        <div class="dataTables_paginate paging_bootstrap">
                            <?= $pagination; ?>
                        </div>
                    </div>


                </div>
            </div>
            <div class="col-lg-12">
                <p class="introtext"><?= lang('get_day_profit').' '.lang("reports_calendar_text") ?></p>

                <div id="style">
                    <?php echo $calender; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= $assets ?>js/html2canvas.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.table .day_num').click(function () {
            var day = $(this).html();
            var biller="";
            <?php if($this->input->post('biller')){?>
                var biller=<?=$this->input->post('biller')?>;
            <?php } ?>
            var date = '<?= $year.'-'.$month.'-'; ?>'+day;
            if(biller)
            {
                var href = '<?= site_url('reports/profitByBiller'); ?>/'+date+'/'+biller;
            }else
            {
                var href = '<?= site_url('reports/profitByBiller'); ?>/'+date;
            }
            $.get(href, function( data ) {
                $("#myModal").html(data).modal();
            });

        });
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/daily_sales/'.$year.'/'.$month.'/pdf')?>";
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
