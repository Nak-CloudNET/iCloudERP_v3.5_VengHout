<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('edit_project_plan'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?php echo lang('enter_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("project_plan/edit/".$id , $attrib)
                ?>

                <div class="row">
                    <div class="col-lg-12">
                        <?php if ($Owner || $Admin || $Settings->allow_change_date == 1) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "pndate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="pndate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>
                        
						<div class="col-md-4">
                            <?= lang("reference_no", "slref"); ?>
							<div style="float:left;width:100%;">
								<div class="form-group">
									<div class="input-group" style="width:100%">  
										<?php echo form_input('reference_no', $reference?$reference:"",'class="form-control input-tip" id="pnref"'); ?>
										<input type="hidden"  name="temp_reference_no"  id="temp_reference_no" value="<?= $reference?$reference:"" ?>" />
									</div>
								</div>
							</div>
                        </div>
						
						<div class="col-md-4">
							<div class="form-group">
								<?= lang("plan", "plan") ?>
								<input id="plans" type="text" name="plans" value="<?= $plan;?>" class="form-control" required/>
							</div>
						</div>
						
						<div class="col-md-4">
							<div class="form-group">
								<?= lang("document", "document") ?>
								<input id="document" type="file" name="document" data-show-upload="false"
									   data-show-preview="false" class="form-control file">
							</div>
						</div>

                        <div class="col-md-12" id="sticker">
                            <div class="well well-sm">
                                <div class="form-group" style="margin-bottom:0;">
                                    <div class="input-group wide-tip">
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <i class="fa fa-2x fa-barcode addIcon"></i>
										</div>
                                        <?php
											if($this->input->get('addquote')){
											
												$q = $this->db->get_where('erp_products',array('id'=>$this->input->get('addquote')),1);
												$pcode = $q->row()->code;
											
											}
											echo form_input('add_item', (isset($pcode)?$pcode:''), 'class="form-control input-lg" id="add_item" placeholder="' . $this->lang->line("add_product_to_order") . '"'); 
										?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        
						<div class="clearfix"></div>
						
                        <div class="col-md-12">
                            <div class="control-group table-group">
                                <label class="table-label"><?= lang("order_items"); ?> *</label>

                                <div class="controls table-controls">
                                    <table id="pnTable" class="table items table-striped table-bordered table-condensed table-hover">
                                        <thead>
											<tr>
												<th class=""><?= lang("no"); ?></th>
												<th class="col-md-9"><?= lang("product_name") . " (" . lang("product_code") . ")"; ?></th>
												<th class="col-md-2"><?= lang("quantity"); ?></th>
												<th style="text-align: center;">
													<i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
												</th>
											</tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
						
                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>

                        <div class="row" id="bt">
                            <div class="col-sm-12">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <?= lang("note", "pnnotes"); ?>
                                        <?php echo form_textarea('note', $note, 'class="form-control" id="pnnotes" style="margin-top: 10px; height: 100px;"'); ?>
                                    </div>
                                </div>
                            </div>
						</div>
                        
						<div class="col-sm-12">
                            <div class="fprom-group"><?php echo form_submit('add_quote', $this->lang->line("submit"), 'id="add_quote" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>
							</div>
                        </div>
						
                    </div>
                </div>

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

<div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="prModalLabel"></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity'); ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pquantity">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="poption" class="col-sm-4 control-label"><?= lang('product_option'); ?></label>

                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>
                    <input type="hidden" id="old_qty" value=""/>
                    <input type="hidden" id="row_id" value=""/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editItem"><?= lang('submit'); ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var count = 1, an = 1, pnitems = {};
    var audio_success 	= new Audio('<?=$assets?>sounds/sound2.mp3');
    var audio_error 	= new Audio('<?=$assets?>sounds/sound3.mp3');
    $(document).ready(function () {
		$("#pnref").attr('readonly', true); 
		$('#ref_st').on('ifChanged', function() {
		  if ($(this).is(':checked')) {
            $("#pnref").attr('readonly', false); 
			$("#pnref").val("");
		  }else{
			$("#pnref").prop('disabled', true);
			var temp = $("#temp_reference_no").val();
			$("#pnref").val(temp);
			
		  }
		});
		
        if (!__getItem('pndate')) {
            $("#pndate").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'erp',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
        }
		
        $(document).on('change', '#pndate', function (e) {
            __setItem('pndate', $(this).val());
        });
		
        if (pndate = __getItem('pndate')) {
            $('#pndate').val(pndate);
        }
		
        $(document).on('change', '#pnbiller', function (e) {
            __setItem('pnbiller', $(this).val());
			billerChange();
        });
		
        if (pnbiller = __getItem('pnbiller')) {
            $('#pnbiller').val(pnbiller);
        }
		
        $("#add_item").autocomplete({
            source: function (request, response) {
                $.ajax({
                    type: 'get',
                    url: '<?= site_url('project_plan/suggestions'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term
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
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                   // $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    //audio_error.play();
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                  //  $(this).val('');

                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_project_plan_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });
        
		$('#add_item').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                $(this).autocomplete("search");
            }
        });

		<?php if ($plan_item) { ?>
			__setItem('pnitems', JSON.stringify(<?=$plan_item;?>));
		<?php } ?>
		
    });
	
</script>