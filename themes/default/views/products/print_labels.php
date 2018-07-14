<script type="text/javascript">
    $(document).ready(function () {
        $('#category').val('<?=$category_id?>');
        $('#category').change(function () {
            window.location.replace("<?php echo site_url('products/print_labels'); ?>/" + $(this).val());
            return false;
        });
    });
</script>
<div class="box">
    <div class="box-header no-print">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('print_labels'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <?php if(isset($print_link)) { ?><li class="dropdown" style="margin-right:10px;"><?= $print_link; ?></li><?php } ?>
                <?php if(isset($inputs)) { ?><li class="dropdown" style="margin-right:10px;"><a href="#" id="labelPrinter" class="tip"
                                        title="<?= lang('label_printer') ?>"><i class="icon fa fa-print"></i> <?= lang('label_printer') ?></a></li><?php } ?>
                <li class="dropdown"><a href="javascript:void();" onclick="window.print();" id="print-icon" class="tip"
                                        title="<?= lang('print') ?>"><i class="icon fa fa-print"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('print_barcode_heading'); ?></p>

                <div class="well well-sm no-print">

                    <?php
                    $cat[''] = $this->lang->line("select") . " " . $this->lang->line("category");
                    foreach ($categories as $category) {
                        $cat[$category->id] = $category->name;
                    }
                    echo form_dropdown('category', $cat, $category, 'class="tip form-control" id="category" placeholder="' . $this->lang->line("select") . " " . $this->lang->line("category") . '" required="required"');
                    ?>

                    <!--<span style="margin-top:15px; display: block;"><div class="btn-group">
                            <a class="btn btn-success" href="<?= site_url() ?>"><i class="fa fa-home"></i> <?= lang('home') ?></a>
                            <a class="btn btn-info" onclick="window.history.back()"><i class="fa fa-arrow-circle-left"></i> <?= lang('go_back'); ?></a>
                            <a class="btn btn-primary" href="#" onclick="window.print(); return false;"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></a>
                    </div></span>-->

                    <div class="clearfix"></div>


                </div>
                <?php if ($r != 1) { ?>

                    <?php if (!empty($links)) {
                        echo '<div class="text-center">' . $links . '</div>';
                    } ?>
                    <?php echo $html; ?>
                    <?php if (!empty($links)) {
                        echo '<div class="text-center">' . $links . '</div>';
                    } ?>
                <?php
                } else {
                    echo '<h3>' . $this->lang->line('empty_category') . '</h3>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php if(isset($inputs)) { ?>
<div id="formData" style="display: none;">
<?= form_open('products/print_labels2', 'id="PrintLabels"'); ?>
<?= $inputs; ?>
<?= form_hidden('print_selected', 1); ?>
<?= form_close(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#labelPrinter').click(function(e) {
            e.preventDefault();
            //$('#PrintLabels').submit();
            var fdata = $('#formData').html();
            Popup(fdata);
        });
    });
    function Popup(data) {
        var mywindow = window.open('', 'erp_popup', 'height=600,width=900');
        mywindow.document.write('<!DOCTYPE html><html><head><title>Print</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?=$assets;?>bootstrap/css/bootstrap.min.css" type="text/css" />');
        mywindow.document.write('<style>a {color: #333;} #totaltbl td, #totaltbl th { vertical-align: middle; }</style>');
        mywindow.document.write('</head><body>');
        mywindow.document.write(data);
        mywindow.document.write('<script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"><\/script>');
        mywindow.document.write('<script>setInterval(function(){$(\'#PrintLabels\').submit();}, 10);<\/script>');
        mywindow.document.write('</body></html>');
    }
</script>
<?php } ?>

