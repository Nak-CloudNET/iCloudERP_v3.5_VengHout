<?php
$wm = array('0' => lang('no'), '1' => lang('yes'));
$ps = array('0' => lang("disable"), '1' => lang("enable"));
?>
<script>
    $(document).ready(function () {
        <?php if(isset($message)) { echo 'localStorage.clear();'; } ?>
        var timezones = <?php echo json_encode(DateTimeZone::listIdentifiers(DateTimeZone::ALL)); ?>;
        $('#timezone').autocomplete({
            source: timezones
        });

        if ($('#protocol').val() == 'smtp') {
            $('#smtp_config').slideDown();
        } else if ($('#protocol').val() == 'sendmail') {
            $('#sendmail_config').slideDown();
        }

        $('#protocol').change(function () {
            if ($(this).val() == 'smtp') {
                $('#sendmail_config').slideUp();
                $('#smtp_config').slideDown();
            } else if ($(this).val() == 'sendmail') {
                $('#smtp_config').slideUp();
                $('#sendmail_config').slideDown();
            } else {
                $('#smtp_config').slideUp();
                $('#sendmail_config').slideUp();
            }
        });

        $('#overselling').change(function () {
			
			if($(this).val() == 1){
				$('#product_expiry').select2("val",0);
			}
			
            if ($(this).val() == 1) {
                if ($('#accounting_method').select2("val") != 2) {
                    bootbox.alert('<?=lang('overselling_will_only_work_with_AVCO_accounting_method_only')?>');
                    $('#accounting_method').select2("val", '2');
                }
            }
			
        });
		
		$('#product_expiry').change(function () {
			
        });
		
        $('#accounting_method').change(function () {
            var oam = <?=$Settings->accounting_method?>, nam = $(this).val();
            if (oam != nam) {
                bootbox.alert('<?=lang('accounting_method_change_alert')?>');
            }
        });

        $('#accounting_method').change(function () {
            if ($(this).val() != 2) {
                if ($('#overselling').select2("val") == 1) {
                    bootbox.alert('<?=lang('overselling_will_only_work_with_AVCO_accounting_method_only')?>');
                    $('#overselling').select2("val", 0);
                }
            }
        });

        $('#item_addition').change(function () {
            if ($(this).val() == 1) {
                bootbox.alert('<?=lang('product_variants_feature_x')?>');
            }
        });

        var sac = $('#sac').val();
        if(sac == 1) {
            $('.nsac').slideUp();
        } else {
            $('.nsac').slideDown();
        }

        $('#sac').change(function () {
            if ($(this).val() == 1) {
                $('.nsac').slideUp();
            } else {
                $('.nsac').slideDown();
            }
        });

        $('#biller').change(function(){
            billerChange();
            //$('#warehouse').select2().empty();
        });
        var $biller = $('#biller');
        $(window).load(function(){
            billerChange();
        });

        function billerChange() {

            var id = $biller.val();
            $('#warehouse').select2().empty();
            $.ajax({
                url: '<?= base_url() ?>auth/getWarehouseByProject/' + id,
                dataType: 'json',
                success: function (result) {
                    $.each(result, function (i, val) {
                        var b_id = val.id;
                        var code = val.code;
                        var name = val.name;
                        var opt = '<option value="' + b_id + '">' + code + '-' + name + '</option>';
                        $("#warehouse").append(opt);
                    });
                    //$('#warehouse').val($('#warehouse option:first-child').val()).trigger('change');
                }
            });

        }
    });
</script>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-cog"></i><?= lang('system_settings'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="<?= site_url('system_settings/paypal') ?>" class="toggle_up"><i class="icon fa fa-paypal"></i><span class="padding-right-10"><?= lang('paypal'); ?></span></a></li>
                <li class="dropdown"><a href="<?= site_url('system_settings/skrill') ?>" class="toggle_down"><i class="icon fa fa-bank"></i><span class="padding-right-10"><?= lang('skrill'); ?></span></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?= lang('update_info'); ?></p>

                <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("system_settings", $attrib);
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info" role="alert"><p>
                            <strong>Cron Job:</strong> <code>0 1 * * * wget -qO- <?php echo site_url('cron/run'); ?> &gt;/dev/null 2&gt;&amp;1</code> to run at 1:00 AM daily. For local installation, you can run cron job manually at any time.
                            <a class="btn btn-primary btn-xs pull-right" target="_blank" href="<?= site_url('cron/run'); ?>">Run cron job now</a>
                        </p></div>
                        
						<fieldset class="scheduler-border">
                            <legend class="scheduler-border"><?= lang('site_config') ?></legend>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("site_name", "site_name"); ?>
                                    <?php echo form_input('site_name', $Settings->site_name, 'class="form-control tip" id="site_name"  required="required"'); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("language", "language"); ?>
                                    <?php
                                    $lang = array(
                                        'english' => 'English',
                                        'spanish' => 'Spanish',
                                    );
                                    echo form_dropdown('language', $lang, $Settings->language, 'class="form-control tip" id="language" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="currency"><?= lang("default_currency"); ?></label>

                                    <div class="controls"> <?php
                                        foreach ($currencies as $currency) {
                                            $cu[$currency->code] = $currency->name;
                                        }
                                        echo form_dropdown('currency', $cu, $Settings->default_currency, 'class="form-control tip" id="currency" required="required" style="width:100%;"');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("accounting_method", "accounting_method"); ?>
                                    <?php
                                    $am = array(0 => 'FIFO (First In First Out)', 1 => 'LIFO (Last In First Out)', 2 => 'AVCO (Average Cost Method)');
                                    echo form_dropdown('accounting_method', $am, $Settings->accounting_method, 'class="form-control tip" id="accounting_method" required="required" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="email"><?= lang("default_email"); ?></label>

                                    <?php echo form_input('email', $Settings->default_email, 'class="form-control tip" required="required" id="email"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="customer_group"><?= lang("default_customer_group"); ?></label>

									<div class="controls"> <?php
										foreach ($customer_groups as $customer_group) {
											$cgs[$customer_group->id] = $customer_group->name;
										}
										echo form_dropdown('customer_group', $cgs, $Settings->customer_group, 'class="form-control tip" id="customer_group" style="width:100%;" required="required"');
										?>
									</div>
								</div>
							</div>
							<!--<div class="col-md-4">
								<div class="form-group">
									<?= lang('maintenance_mode', 'mmode'); ?>
									<div class="controls">  <?php
										echo form_dropdown('mmode', $wm, (isset($_POST['mmode']) ? $_POST['mmode'] : $Settings->mmode), 'class="tip form-control" required="required" id="mmode" style="width:100%;"');
										?> </div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="theme"><?= lang("theme"); ?></label>

									<div class="controls">
										<?php
										$themes = array(
											'default' => 'Default'
										);
										echo form_dropdown('theme', $themes, $Settings->theme, 'id="theme" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="rtl"><?= lang("rtl_support"); ?></label>

									<div class="controls">
										<?php
										echo form_dropdown('rtl', $ps, $Settings->rtl, 'id="rtl" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="captcha"><?= lang("login_captcha"); ?></label>

									<div class="controls">
										<?php
										echo form_dropdown('captcha', $ps, $Settings->captcha, 'id="captcha" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>-->
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="rows_per_page"><?= lang("rows_per_page"); ?></label>

									<?php echo form_input('rows_per_page', $Settings->rows_per_page, 'class="form-control tip" id="rows_per_page" required="required"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="dateformat"><?= lang("dateformat"); ?></label>

									<div class="controls">
										<?php
										foreach ($date_formats as $date_format) {
											$dt[$date_format->id] = $date_format->js;
										}
										echo form_dropdown('dateformat', $dt, $Settings->dateformat, 'id="dateformat" class="form-control tip" style="width:100%;" required="required"');
										?>
									</div>
								</div>
							</div>
							<!--<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="timezone"><?= lang("timezone"); ?></label>
									<?php
									$timezone_identifiers = DateTimeZone::listIdentifiers();
									foreach ($timezone_identifiers as $tzi) {
										$tz[$tzi] = $tzi;
									}
									?>
									<?php echo form_dropdown('timezone', $tz, TIMEZONE, 'class="form-control tip" id="timezone" required="required"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('reg_ver', 'reg_ver'); ?>
									<div class="controls">  <?php
										echo form_dropdown('reg_ver', $wm, (isset($_POST['reg_ver']) ? $_POST['reg_ver'] : $Settings->reg_ver), 'class="tip form-control" required="required" id="reg_ver" style="width:100%;"');
										?> </div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('allow_reg', 'allow_reg'); ?>
									<div class="controls">  <?php
										echo form_dropdown('allow_reg', $wm, (isset($_POST['allow_reg']) ? $_POST['allow_reg'] : $Settings->allow_reg), 'class="tip form-control" required="required" id="allow_reg" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('reg_notification', 'reg_notification'); ?>
									<div class="controls">  <?php
										echo form_dropdown('reg_notification', $wm, (isset($_POST['reg_notification']) ? $_POST['reg_notification'] : $Settings->reg_notification), 'class="tip form-control" required="required" id="reg_notification" style="width:100%;"');
										?>
									</div>
								</div>
							</div>-->
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="restrict_calendar"><?= lang("calendar"); ?></label>

									<div class="controls">
										<?php
										$opt_cal = array(1 => lang('private'), 0 => lang('shared'));
										echo form_dropdown('restrict_calendar', $opt_cal, $Settings->restrict_calendar, 'class="form-control tip" required="required" id="restrict_calendar" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang("default_biller", "biller"); ?>
									<?php
									$bl[""] = "";
									foreach ($billers as $biller) {
										$bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                    }
                                    //$this->erp->print_arrays($bl);
									echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $Settings->default_biller), 'id="biller" data-placeholder="' . lang("select") . ' ' . lang("biller") . '" required="required" class="form-control input-tip select" style="width:100%;"');
									?>
								</div>
							</div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="warehouse"><?= lang("default_warehouse"); ?></label>

                                    <div class="controls"> <?php
                                        $wh[""] = "";
                                        foreach ($warehouses as $warehouse) {
                                            $wh[$warehouse->id] = $warehouse->name . ' (' . $warehouse->code . ')';
                                        }
                                        //$this->erp->print_arrays($wh);
                                        echo form_dropdown('warehouse', $wh, $Settings->default_warehouse, 'class="form-control tip" id="warehouse" required="required" style="width:100%;"');
                                        ?>
                                    </div>
                                </div>
                            </div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="alert_day"><?= lang("alert_day"); ?></label>

									<?php echo form_input('alert_day', $Settings->alert_day, 'class="form-control tip" id="alert_day" required="required"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="acc_cate_sep"><?= lang("account_category_separate"); ?></label>

									<?php 
										$acc_cate_sep = array('0'=>'No', '1'=>'Yes');
										echo form_dropdown('acc_cate_sep', $acc_cate_sep, (isset($_POST['acc_cate_sep']) ? $_POST['acc_cate_sep'] : $Settings->acc_cate_separate), 'id="acc_cate_sep" data-placeholder="' . lang("select") . ' ' . lang("account_category_separate") . '" required="required" class="form-control input-tip select" style="width:100%;"'); 
									?>
								</div>
							</div>
							<!--
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="stock_deduction"><?= lang("stock_deduction"); ?></label>
									<?php 
										$sd = array('delivery'=>'on_delivery', 'invoice'=>'on_invoice');
										echo form_dropdown('stock_deduction', $sd, (isset($_POST['stock_deduction']) ? $_POST['stock_deduction'] : $Settings->stock_deduction), 'id="stock_deduction" data-placeholder="' . lang("select") . ' ' . lang("stock_deduction") . '" required="required" class="form-control input-tip select" style="width:100%;"'); 
									?>
								</div>
							</div>
							
							-->
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="authorization"><?= lang("authorization"); ?></label>
									<?php 
										$sd = array('auto'=>'Auto', 'manual'=>'Manaul');
										echo form_dropdown('authorization', $sd, (isset($_POST['authorization']) ? $_POST['authorization'] : $Settings->authorization), 'id="authorization" data-placeholder="' . lang("select") . ' ' . lang("authorization") . '" required="required" class="form-control input-tip select" style="width:100%;"'); 
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="boms_method"><?= lang("boms_method"); ?></label>

									<?php
										$lang = array(
											'0' => lang("none"),
											'1' => lang("finish_base"),
											'2' => lang("raw_base")
											//'3' => lang("both")
										);
										echo form_dropdown('boms_method', $lang, $Settings->boms_method, 'class="form-control tip" id="language" required="required" style="width:100%;"');
                                    ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="show_com_code"><?= lang("show_people_code"); ?></label>
									<div class="controls">
										<?php
										$opt = array(1 => lang('yes'), 0 => lang('no'));
										echo form_dropdown('show_com_code', $opt, $Settings->show_company_code, 'class="form-control tip" id="show_com_code" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="delivery"><?= lang('delivery'); ?></label>
									<?php 
										$deli = array('sale_order'=> 'on_Sale_Order', 'invoice'=>'on_Invoice', 'both'=>'Both');
										echo form_dropdown('delivery', $deli, (isset($_POST['delivery']) ? $_POST['delivery'] : $Settings->delivery), 'id="delivery" data-placeholder="' . lang("select") . ' ' . lang("delivery") . '" required="required" class="form-control input-tip select" style="width:100%;"'); 
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="shipping">
										<?= lang('ship_on_pur'); ?>
									</label>
									<?php 
										$shipping = array('0'=> lang('no'), '1'=> lang('yes'));
										echo form_dropdown('shipping', $shipping, (isset($_POST['shipping']) ? $_POST['shipping'] : $Settings->shipping), 'id="shipping" data-placeholder="' . lang("select") . ' ' . lang("shipping") . '" required="required" class="form-control input-tip select" style="width:100%;"'); 
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="separate_ref">
										<?= lang('seperate_ref_by_proj'); ?>
									</label>
									<?php 
										$separate = array('0'=> lang('no'), '1'=> lang('yes'));
										
										echo form_dropdown('separate_ref', $separate, (isset($_POST['separate_ref']) ? $_POST['separate_ref'] : $Settings->separate_ref), 'id="separate_ref" data-placeholder="' . lang("select") . ' ' . lang("separate") . '" required="required" class="form-control input-tip select" style="width:100%;"'); 
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="system_management">
										<?= lang('system_management'); ?>
									</label>
									<?php 
										$sm = array('project'=> lang('manage_by_project'), 'biller'=> lang('manage_by_biller'));

										echo form_dropdown('system_management', $sm, (isset($_POST['system_management']) ? $_POST['system_management'] : $Settings->system_management), 'id="system_management" data-placeholder="' . lang("select") . ' ' . lang("sm") . '" required="required" class="form-control input-tip select" style="width:100%;"'); 
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="table_item">
										<?= lang('Choose_table_item'); ?>
									</label>
									<?php 
										$sm = array('table'=> lang('choose_table_first'), 'item'=> lang('choose_item_first'));

										echo form_dropdown('table_item', $sm, (isset($_POST['table_item']) ? $_POST['table_item'] : $Settings->system_management), 'id="table_item" data-placeholder="' . lang("select") . ' ' . lang("sm") . '" class="form-control input-tip select" style="width:100%;"'); 
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="allow_change_date">
										<?= lang('allow_change_date'); ?>
									</label>
									<?php 
										$acd = array('1'=> lang('yes'), '0'=> lang('no'));

										echo form_dropdown('allow_change_date', $acd, (isset($_POST['allow_change_date']) ? $_POST['allow_change_date'] : $Settings->allow_change_date), 'id="allow_change_date" data-placeholder="' . lang("select") . ' ' . lang("acd") . '" required="required" class="form-control input-tip select" style="width:100%;"'); 
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="business_type">
										<?= lang('business_type'); ?>
									</label>
									<?php 
										$bt = array('whole_sale'=> lang('whole_sale'), 'contruction'=> lang('contruction'));

										echo form_dropdown('business_type', $bt, (isset($_POST['business_type']) ? $_POST['business_type'] : $Settings->business_type), 'id="business_type" data-placeholder="' . lang("select") . ' ' . lang("business_type") . '" required="required" class="form-control input-tip select" style="width:100%;"'); 
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="business_type">
										<?= lang('tax_calculate'); ?>
									</label>
									<?php 
										$tm = array('0'=> lang('tax_before'), '1'=> lang('tax_after'));

										echo form_dropdown('tax_calculate', $tm, (isset($_POST['tax_calculate']) ? $_POST['tax_calculate'] : $Settings->tax_calculate), 'id="tax_calculate" data-placeholder="' . lang("select") . ' ' . lang("tax_calculate") . '" required="required" class="form-control input-tip select" style="width:100%;"'); 
									?>
								</div>
							</div>
						</fieldset>
					
						<fieldset class="scheduler-border">
							<legend class="scheduler-border"><?= lang('products') ?></legend>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang("product_tax", "tax_rate"); ?>
									<?php
									echo form_dropdown('tax_rate', $ps, $Settings->default_tax_rate, 'class="form-control tip" id="tax_rate" required="required" style="width:100%;"');
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="racks"><?= lang("racks"); ?></label>

									<div class="controls">
										<?php
										echo form_dropdown('racks', $ps, $Settings->racks, 'id="racks" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="attributes"><?= lang("attributes"); ?></label>

									<div class="controls">
										<?php
										echo form_dropdown('attributes', $ps, $Settings->attributes, 'id="attributes" class="form-control tip"  required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="product_expiry"><?= lang("product_expiry"); ?></label>
									<div class="controls">
										<?php
										echo form_dropdown('product_expiry', $ps, $Settings->product_expiry, 'id="product_expiry" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="image_size"><?= lang("image_size"); ?> (Width :
										Height) *</label>

									<div class="row">
										<div class="col-xs-6">
											<?php echo form_input('iwidth', $Settings->iwidth, 'class="form-control tip" id="iwidth" placeholder="image width" required="required"'); ?>
										</div>
										<div class="col-xs-6">
											<?php echo form_input('iheight', $Settings->iheight, 'class="form-control tip" id="iheight" placeholder="image height" required="required"'); ?></div>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="thumbnail_size"><?= lang("thumbnail_size"); ?>
										(Width : Height) *</label>

									<div class="row">
										<div class="col-xs-6">
											<?php echo form_input('twidth', $Settings->twidth, 'class="form-control tip" id="twidth" placeholder="thumbnail width" required="required"'); ?>
										</div>
										<div class="col-xs-6">
											<?php echo form_input('theight', $Settings->theight, 'class="form-control tip" id="theight" placeholder="thumbnail height" required="required"'); ?>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('watermark', 'watermark'); ?>
									<?php
										echo form_dropdown('watermark', $wm, (isset($_POST['watermark']) ? $_POST['watermark'] : $Settings->watermark), 'class="tip form-control" required="required" id="watermark" style="width:100%;"');
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('display_all_products', 'display_all_products'); ?>
									<?php
										$dopts = array(0 => lang('hide_with_0_qty'), 1 => lang('show_with_0_qty'));
										echo form_dropdown('display_all_products', $dopts, (isset($_POST['display_all_products']) ? $_POST['display_all_products'] : $Settings->display_all_products), 'class="tip form-control" required="required" id="display_all_products" style="width:100%;"');
									?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="product_serial"><?= lang("serial"); ?></label>

									<div class="controls">
										<?php
										echo form_dropdown('purchase_serial', $ps, $Settings->purchase_serial, 'id="purchase_serial" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="product_serial"><?= lang("separate_code"); ?></label>

									<div class="controls">
										<?php
										echo form_dropdown('separate_code', $wm, $Settings->separate_code, 'id="separate_code" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="product_serial"><?= lang("show_code"); ?></label>

									<div class="controls">
										<?php
										echo form_dropdown('show_code', $wm, $Settings->show_code, 'id="show_code" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="product_serial"><?= lang("increase_stock_import"); ?></label>

									<div class="controls">
										<?php
											echo form_dropdown('increase_stock_import', $wm, $Settings->increase_stock_import, 'id="increase_stock_import" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
						</fieldset>

						<fieldset class="scheduler-border">
							<legend class="scheduler-border"><?= lang('sales') ?></legend>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="overselling"><?= lang("over_selling"); ?></label>

									<div class="controls">
										<?php
										$opt = array(1 => lang('yes'), 0 => lang('no'));
										echo form_dropdown('restrict_sale', $opt, $Settings->overselling, 'class="form-control tip" id="overselling" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="reference_format"><?= lang("reference_format"); ?></label>

									<div class="controls">
										<?php
										$ref = array(1 => lang('prefix_name_ym_no'), 2 => lang('prefix_year_no'), 3 => lang('prefix_month_year_no'), 4 => lang('sequence_number'), 5 => lang('sequence_number_only'), 6 => lang('random_number'));
										echo form_dropdown('reference_format', $ref, $Settings->reference_format, 'class="form-control tip" required="required" id="reference_format" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang("invoice_tax", "tax_rate2"); ?>
									<?php $tr['0'] = lang("disable");
									foreach ($tax_rates as $rate) {
										$tr[$rate->id] = $rate->name;
									}
									echo form_dropdown('tax_rate2', $tr, $Settings->default_tax_rate2, 'id="tax_rate2" class="form-control tip" required="required" style="width:100%;"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="product_discount"><?= lang("product_level_discount"); ?></label>

									<div class="controls">
										<?php
										echo form_dropdown('product_discount', $ps, $Settings->product_discount, 'id="product_discount" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="product_serial"><?= lang("product_serial"); ?></label>

									<div class="controls">
										<?php
										echo form_dropdown('product_serial', $ps, $Settings->product_serial, 'id="product_serial" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="detect_barcode"><?= lang("auto_detect_barcode"); ?></label>

									<div class="controls">
										<?php
										echo form_dropdown('detect_barcode', $ps, $Settings->auto_detect_barcode, 'id="detect_barcode" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="bc_fix"><?= lang("bc_fix"); ?></label>


									<?php echo form_input('bc_fix', $Settings->bc_fix, 'class="form-control tip" required="required" id="bc_fix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="item_addition"><?= lang("item_addition"); ?></label>

									<div class="controls">
										<?php
										$ia = array(0 => lang('add_new_item'), 1 => lang('increase_quantity_if_item_exist'));
										echo form_dropdown('item_addition', $ia, $Settings->item_addition, 'id="item_addition" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="invoice_view"><?= lang("invoice_view"); ?></label>

									<div class="controls">
										<?php
										$opt_inv = array(
															0 => lang('standard'),
															1 => lang('invoice'),
															2 => lang('tax_invoice'),
															3 => lang('a4'),
															4 => lang('a5'),
															6 => lang('a6'),
														);
										echo form_dropdown('invoice_view', $opt_inv, $Settings->invoice_view, 'class="form-control tip" required="required" id="invoice_view" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="auto_print"><?= lang("auto_print"); ?></label>

									<div class="controls">
										<?php
										echo form_dropdown('auto_print', $ps, $Settings->auto_print, 'id="auto_print" class="form-control tip" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="bill_to"><?= lang("show_bill_to"); ?></label>
									<div class="controls">
										<?php
										$opt = array(1 => lang('yes'), 0 => lang('no'));
										echo form_dropdown('bill_to', $opt, $Settings->bill_to, 'class="form-control tip" id="bill_to" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="show_po"><?= lang("show_po"); ?></label>
									<div class="controls">
										<?php
										$opt = array(1 => lang('yes'), 0 => lang('no'));
										echo form_dropdown('show_po', $opt, $Settings->show_po, 'class="form-control tip" id="show_po" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="cdl"><?= lang("credit_limit"); ?></label>
									<div class="controls">
										<?php
										$cdl = array(1 => lang('yes'), 0 => lang('no'));
										echo form_dropdown('credit_limit', $cdl, $Settings->credit_limit, 'class="form-control tip" id="credit_limit" required="required" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="mcx"><?= lang("member_card_expiry"); ?></label>
									<div class="controls">
										<?php
										$mcx = array(1 => lang('yes'), 0 => lang('no'));
										echo form_dropdown('member_card_expiry', $mcx, $Settings->member_card_expiry, 'class="form-control tip" id="member_card_expiry" style="width:100%;"');
										?>
									</div>
								</div>
							</div>
						</fieldset>

						<fieldset class="scheduler-border">
							<legend class="scheduler-border"><?= lang('prefix') ?></legend>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="sales_prefix"><?= lang("sales_prefix"); ?></label>
									<?php echo form_input('sales_prefix', $Settings->sales_prefix, 'class="form-control tip" id="sales_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="sale_order_prefix"><?= lang("sale_order_prefix"); ?></label>
									<?php echo form_input('sale_order_prefix', $Settings->sale_order_prefix, 'class="form-control tip" id="sales_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="return_prefix"><?= lang("return_prefix"); ?></label>

									<?php echo form_input('return_prefix', $Settings->return_prefix, 'class="form-control tip" id="return_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="payment_prefix"><?= lang("payment_prefix"); ?></label>

									<?php echo form_input('payment_prefix', $Settings->payment_prefix, 'class="form-control tip" id="payment_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="delivery_prefix"><?= lang("delivery_prefix"); ?></label>

									<?php echo form_input('delivery_prefix', $Settings->delivery_prefix, 'class="form-control tip" id="delivery_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="quote_prefix"><?= lang("quote_prefix"); ?></label>

									<?php echo form_input('quote_prefix', $Settings->quote_prefix, 'class="form-control tip" id="quote_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="purchase_prefix"><?= lang("purchase_prefix"); ?></label>

									<?php echo form_input('purchase_prefix', $Settings->purchase_prefix, 'class="form-control tip" id="purchase_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label"
										   for="transfer_prefix"><?= lang("transfer_prefix"); ?></label>
									<?php echo form_input('transfer_prefix', $Settings->transfer_prefix, 'class="form-control tip" id="transfer_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('expense_prefix', 'expense_prefix'); ?>
									<?= form_input('expense_prefix', $Settings->expense_prefix, 'class="form-control tip" id="expense_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('sale_payment_prefix','sale_payment_prefix'); ?>
									<?= form_input('sale_payment_prefix',$settings->sale_payment_prefix,'class="form-control tip" id="sale_payment_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('purchase_payment_prefix','purchase_payment_prefix'); ?>
									<?= form_input('purchase_payment_prefix',$settings->purchase_payment_prefix,'class="form-control tip" id="purchase_payment_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('sale_loan_prefix','sale_loan_prefix'); ?>
									<?= form_input('sale_loan_prefix',$settings->sale_loan_prefix,'class="form-control tip" id="sale_loan_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('convert_prefix','convert_prefix'); ?>
									<?= form_input('convert_prefix',$settings->convert_prefix,'class="form-control tip" id="convert_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('purchase_retrun_prefix','purchase_retrun_prefix'); ?>
									<?= form_input('purchase_retrun_prefix',$settings->returnp_prefix,'class="form-control tip" id="purchase_retrun_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('enter_using_stock_prefix','enter_using_stock_prefix'); ?>
									<?= form_input('enter_using_stock_prefix',$settings->enter_using_stock_prefix,'class="form-control tip" id="enter_using_stock_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('enter_using_stock_return_prefix','enter_using_stock_return_prefix'); ?>
									<?= form_input('enter_using_stock_return_prefix',$settings->enter_using_stock_return_prefix,'class="form-control tip" id="enter_using_stock_return_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('supplier_deposit_prefix','supplier_deposit_prefix'); ?>
									<?= form_input('supplier_deposit_prefix',$settings->supplier_deposit_prefix,'class="form-control tip" id="supplier_deposit_prefix" required="required"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('purchase_order_prefix','purchase_order_prefix'); ?>
									<?= form_input('purchase_order_prefix',$settings->purchase_order_prefix,'class="form-control tip" id="purchase_order_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('purchase_request_prefix','purchase_request_prefix'); ?>
									<?= form_input('purchase_request_prefix',$settings->purchase_request_prefix,'class="form-control tip" id="purchase_request_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('journal_prefix','journal_prefix'); ?>
									<?= form_input('journal_prefix',$settings->journal_prefix,'class="form-control tip" id="journal_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('adjustment_prefix','adjustment_prefix'); ?>
									<?= form_input('adjustment_prefix',$settings->adjustment_prefix,'class="form-control tip" id="adjustment_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('stock_count_prefix','stock_count_prefix'); ?>
									<?= form_input('stock_count_prefix',$settings->stock_count_prefix,'class="form-control tip" id="stock_count_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('adjust_cost_prefix','adjust_cost_prefix'); ?>
									<?= form_input('adjust_cost_prefix',$settings->adjust_cost_prefix,'class="form-control tip" id="adjust_cost_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('project_code_prefix','project_code_prefix'); ?>
									<?= form_input('project_code_prefix',$settings->project_code_prefix,'class="form-control tip" id="project_code_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('customer_code_prefix','customer_code_prefix'); ?>
									<?= form_input('customer_code_prefix',$settings->customer_code_prefix,'class="form-control tip" id="customer_code_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('supplier_code_prefix','supplier_code_prefix'); ?>
									<?= form_input('supplier_code_prefix',$settings->supplier_code_prefix,'class="form-control tip" id="supplier_code_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('employee_code_prefix','employee_code_prefix'); ?>
									<?= form_input('employee_code_prefix',$settings->employee_code_prefix,'class="form-control tip" id="employee_code_prefix"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('project_plan_prefix','project_plan_prefix'); ?>
									<?= form_input('project_plan_prefix',$settings->project_plan_prefix,'class="form-control tip" id="project_plan_prefix"'); ?>
								</div>
							</div>
							
						</fieldset>

						<fieldset class="scheduler-border">
							<legend class="scheduler-border"><?= lang('money_number_format') ?></legend>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="decimals"><?= lang("decimals"); ?></label>

									<div class="controls"> <?php
										$decimals = array(0 => lang('disable'), 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6');
										echo form_dropdown('decimals', $decimals, $Settings->decimals, 'class="form-control tip" id="decimals"  style="width:100%;" required="required"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="purchase_decimals"><?= lang("purchase_decimals"); ?></label>

									<div class="controls"> <?php
										$purchase_decimals = array(0 => lang('disable'), 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6');
										echo form_dropdown('purchase_decimals', $purchase_decimals, $Settings->purchase_decimals, 'class="form-control tip" id="purchase_decimals"  style="width:100%;" required="required"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="qty_decimals"><?= lang("qty_decimals"); ?></label>

									<div class="controls"> <?php
										$qty_decimals = array(0 => lang('disable'), 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6');
										echo form_dropdown('qty_decimals', $qty_decimals, $Settings->qty_decimals, 'class="form-control tip" id="qty_decimals"  style="width:100%;" required="required"');
										?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<?= lang('sac', 'sac'); ?>
									<?= form_dropdown('sac', $ps, set_value('sac', $Settings->sac), 'class="form-control tip" id="sac"  required="required"'); ?>
								</div>
							</div>
							<div class="nsac">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="decimals_sep"><?= lang("decimals_sep"); ?></label>

										<div class="controls"> <?php
											$dec_point = array('.' => lang('dot'), ',' => lang('comma'));
											echo form_dropdown('decimals_sep', $dec_point, $Settings->decimals_sep, 'class="form-control tip" id="decimals_sep"  style="width:100%;" required="required"');
											?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label" for="thousands_sep"><?= lang("thousands_sep"); ?></label>
										<div class="controls"> <?php
											$thousands_sep = array('.' => lang('dot'), ',' => lang('comma'), '0' => lang('space'));
											echo form_dropdown('thousands_sep', $thousands_sep, $Settings->thousands_sep, 'class="form-control tip" id="thousands_sep"  style="width:100%;" required="required"');
											?>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<!-- display_symbol form dropdown -->
								<div class="form-group">
									<?= lang('display_currency_symbol', 'display_symbol'); ?>
									<?php $opts = array(0 => lang('disable'), 1 => lang('before'), 2 => lang('after')); ?>
									<?= form_dropdown('display_symbol', $opts, $Settings->display_symbol, 'class="form-control" id="display_symbol" style="width:100%;" required="required"'); ?>
								</div>
							</div>
							<div class="col-md-4">
								<!-- symbol form input -->
								<div class="form-group">
									<?= lang('currency_symbol', 'symbol'); ?>
									<?= form_input('symbol', $Settings->symbol, 'class="form-control" id="symbol" style="width:100%;"'); ?>
								</div>
							</div>
						</fieldset>

						<fieldset class="scheduler-border">
							<legend class="scheduler-border"><?= lang('email') ?></legend>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label" for="protocol"><?= lang("email_protocol"); ?></label>

									<div class="controls"> <?php
										$popt = array('mail' => 'PHP Mail Function', 'sendmail' => 'Send Mail', 'smtp' => 'SMTP');
										echo form_dropdown('protocol', $popt, $Settings->protocol, 'class="form-control tip" id="protocol"  style="width:100%;" required="required"');
										?>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="row" id="sendmail_config" style="display: none;">
								<div class="col-md-12">
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label" for="mailpath"><?= lang("mailpath"); ?></label>

											<?php echo form_input('mailpath', $Settings->mailpath, 'class="form-control tip" id="mailpath"'); ?>
										</div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="row" id="smtp_config" style="display: none;">
								<div class="col-md-12">
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label"
												   for="smtp_host"><?= lang("smtp_host"); ?></label>

											<?php echo form_input('smtp_host', $Settings->smtp_host, 'class="form-control tip" id="smtp_host"'); ?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label"
												   for="smtp_user"><?= lang("smtp_user"); ?></label>

											<?php echo form_input('smtp_user', $Settings->smtp_user, 'class="form-control tip" id="smtp_user"'); ?> </div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label"
												   for="smtp_pass"><?= lang("smtp_pass"); ?></label>

											<?php echo form_password('smtp_pass', $smtp_pass, 'class="form-control tip" id="smtp_pass"'); ?> </div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label"
												   for="smtp_port"><?= lang("smtp_port"); ?></label>

											<?php echo form_input('smtp_port', $Settings->smtp_port, 'class="form-control tip" id="smtp_port"'); ?> </div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="control-label"
												   for="smtp_crypto"><?= lang("smtp_crypto"); ?></label>

											<div class="controls"> <?php
												$crypto_opt = array('' => lang('none'), 'tls' => 'TLS', 'ssl' => 'SSL');
												echo form_dropdown('smtp_crypto', $crypto_opt, $Settings->smtp_crypto, 'class="form-control tip" id="smtp_crypto"');
												?> </div>
										</div>
									</div>
								</div>
							</div>
						</fieldset>

						<fieldset class="scheduler-border">
							<legend class="scheduler-border"><?= lang('award_points') ?></legend>
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label"><?= lang("customer_award_points"); ?></label>

									<div class="row">
										<div class="col-sm-4 col-xs-6">
											<?= lang('each_spent'); ?><br>
											<?= form_input('each_spent', $this->erp->formatDecimal($Settings->each_spent), 'class="form-control"'); ?>
										</div>
										<div class="col-sm-1 col-xs-1 text-center"><i class="fa fa-arrow-right"></i>
										</div>
										<div class="col-sm-4 col-xs-5">
											<?= lang('award_points'); ?><br>
											<?= form_input('ca_point', $Settings->ca_point, 'class="form-control"'); ?>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label"><?= lang("staff_award_points"); ?></label>

									<div class="row">
										<div class="col-sm-4 col-xs-6">
											<?= lang('each_in_sale'); ?><br>
											<?= form_input('each_sale', $this->erp->formatDecimal($Settings->each_sale), 'class="form-control"'); ?>
										</div>
										<div class="col-sm-1 col-xs-1 text-center"><i class="fa fa-arrow-right"></i>
										</div>
										<div class="col-sm-4 col-xs-5">
											<?= lang('award_points'); ?><br>
											<?= form_input('sa_point', $Settings->sa_point, 'class="form-control"'); ?>
										</div>
									</div>

								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<div style="clear: both; height: 10px;"></div>
				<div class="col-md-12">
					<div class="form-group">
						<div class="controls">
							<?php echo form_submit('update_settings', lang("update_settings"), 'class="btn btn-primary"'); ?>
						</div>
					</div>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
		<div class="alert alert-warning" role="alert"><p><strong>Cron Job:</strong> <code>0 1 * * * wget
                -qO- <?php echo site_url('cron/run'); ?> &gt;/dev/null 2&gt;&amp;1</code> to run at 1:00 AM daily.
        </p></div>
	</div>
</div>
