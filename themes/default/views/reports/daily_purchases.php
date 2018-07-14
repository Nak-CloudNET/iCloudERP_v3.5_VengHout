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
		cursor: pointer;
        width: 100%;
        text-align: left;
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
			var ware = '<?= $warehouse_id; ?>';
			
            var date = "<?= $year.'-'.$month.'-'; ?>"+day;
            var href = '<?= site_url('reports/profitPurchase'); ?>/' + date +'/'+ware;
            $.get(href, function( data ) {
                $("#myModal").html(data).modal();
            });

        });
        $('#pdf').click(function (event) {
            event.preventDefault();
            window.location.href = "<?=site_url('reports/daily_purchases/'.($warehouse_id ? $warehouse_id : 0).'/'.$year.'/'.$month.'/pdf')?>";
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
