<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('edit_products_develop'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class'=>'form-horizontal');
        echo form_open_multipart("jobs/edit_jobs", $attrib); ?>
        <div class="modal-body">
			<div class="row">
				<div class="col-lg-12">
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Detail Information</legend>
						<div class="col-sm-4"><?= lang("date"); ?>:</div><div class="col-sm-8"><?= $develop->date;?></div>
						<div class="col-sm-4"><?= lang("reference_no"); ?>:</div><div class="col-sm-8"><?= $develop->reference_no;?></div>
						<div class="col-sm-4"><?= lang("customer_name"); ?>:</div><div class="col-sm-8"><?= $develop->customer;?></div>
						<div class="col-sm-4"><?= lang("product_name"); ?>:</div><div class="col-sm-8"><?= $develop->product_name;?></div>
						<div class="col-sm-4"><b><?= lang("quantity"); ?>:</div><div class="col-sm-8"><?= $develop->quantity;?></b></div>
						<input type="hidden" value="<?= $develop_id;?>" name="develop_id">
						<input type="hidden" value="<?= $develop->product_name;?>" name="product_name">
						<input type="hidden" value="<?= $develop->warehouse;?>" name="warehouse">
						<input type="hidden" value="<?= $develop->product_id;?>" name="product_id">
						<input type="hidden" value="<?= $develop->sale_id;?>" name="sale_id">
						<input type="hidden" value="<?= $develop->unit_price;?>" name="unit_price">
					</fieldset>
				</div>	
			</div>
			<div class="row">
				<div class="col-sm-6">
					<?php if ($Owner || $Admin) { ?>
						<div class="form-group">
							<label class="control-label col-sm-5" style="text-align:left;"><?= lang("ថ្ងៃខែឆ្នាំផ្តិត"); ?></label>
							<div class="col-sm-7">
								 <?= form_input('date', (isset($_POST['date']) ? $_POST['date'] : $dev_item->date), 'class="form-control datetime" id="date"'); ?>
							</div>
						</div>
					<?php } ?>
				</div>	
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label col-sm-5" style="text-align:left;"><?= lang("ឈ្មោះម៉ាស៊ីន"); ?></label>
						<div class="col-sm-7">
							<select name="machine" class="form-control">
								<?php
									if($dev_item->machine_name == 'machine 1'){
										echo '<option value="1" selected>'.lang("machine_1").'</option>';
										echo '<option value="2">'.lang("machine_2").'</option>';
										echo '<option value="3">'.lang("machine_3").'</option>';
										echo '<option value="4">'.lang("machine_4").'</option>';
										echo '<option value="5">'.lang("machine_5").'</option>';
									}elseif($dev_item->machine_name == 'machine 2'){
										echo '<option value="1">'.lang("machine_1").'</option>';
										echo '<option value="2" selected>'.lang("machine_2").'</option>';
										echo '<option value="3">'.lang("machine_3").'</option>';
										echo '<option value="4">'.lang("machine_4").'</option>';
										echo '<option value="5">'.lang("machine_5").'</option>';
									}elseif($dev_item->machine_name == 'machine 3'){
										echo '<option value="1">'.lang("machine_1").'</option>';
										echo '<option value="2">'.lang("machine_2").'</option>';
										echo '<option value="3" selected>'.lang("machine_3").'</option>';
										echo '<option value="4">'.lang("machine_4").'</option>';
										echo '<option value="5">'.lang("machine_5").'</option>';
									}elseif($dev_item->machine_name == 'machine 4'){
										echo '<option value="1">'.lang("machine_1").'</option>';
										echo '<option value="2">'.lang("machine_2").'</option>';
										echo '<option value="3">'.lang("machine_3").'</option>';
										echo '<option value="4" selected>'.lang("machine_4").'</option>';
										echo '<option value="5">'.lang("machine_5").'</option>';
									}else{
										echo '<option value="1">'.lang("machine_1").'</option>';
										echo '<option value="2">'.lang("machine_2").'</option>';
										echo '<option value="3">'.lang("machine_3").'</option>';
										echo '<option value="4">'.lang("machine_4").'</option>';
										echo '<option value="5" selected>'.lang("machine_5").'</option>';
									}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label col-sm-5" style="text-align:left;"> <?= lang("ចំនួនផ្តិតបានការ"); ?></label>
						<div class="col-sm-7">
							<input name="developed_quantity" type="text" id="developed_quantity" value="<?=$dev_item->dev_quantity?>" class="pa form-control kb-pad developed_quantity"
							   required="required"/>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label col-sm-5" style="text-align:left;"> <?= lang("ផ្តិតខូច"); ?></label>
						<div class="col-sm-7">
							<input name="quantity_break" type="text" id="developed_quantity" value="<?=$dev_item->quantity_break?>" class="pa form-control kb-pad developed_quantity"/>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label col-sm-5" style="text-align:left;"> <?= lang("ចំនួនរូបអ៊ិនដិច"); ?></label>
						<div class="col-sm-7">
							<input name="quantity_index" type="text" id="developed_quantity" value="<?=$dev_item->quantity_index?>" class="pa form-control kb-pad developed_quantity"/>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label col-sm-5" style="text-align:left;"> <?= lang("ឈ្មោះកុំព្យូទ័រ"); ?></label>
						<div class="col-sm-7">
						<?php
						$computers = array(""=>"");
						foreach($computer as $user1){
							if($user1->group_id == 13){
								$computers[$user1->id] = $user1->first_name.' '.$user1->last_name;
							}
						}
							echo form_dropdown('user_1', $computers, (isset($_POST['user_1']) ? $_POST['user_1'] : $dev_item->user_1) ,'id="user_1" class="form-control input-tip select" style="width:100%;" ');
						?>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label col-sm-5" style="text-align:left;"> <?= lang("ឈ្មោះជាងផ្តិតរូប"); ?></label>
						<div class="col-sm-7">
						<?php
						$photomaker = array(""=>"");
						foreach($computer as $photo){
							if($photo->group_id == 14){
								$photomaker[$photo->id] = $photo->first_name.' '.$photo->last_name;
							}
						}
							echo form_dropdown('user_2', $photomaker, (isset($_POST['user_2']) ? $_POST['user_2'] : $dev_item->user_2) ,'id="user_2" class="form-control input-tip select" style="width:100%;" ');
						?>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label col-sm-5" style="text-align:left;"> <?= lang("ឈ្មោះអ្នកតែង"); ?></label>
						<div class="col-sm-7">
						<?php
						$decor = array(""=>"");
						foreach($computer as $decore){
							if($decore->group_id == 15){
								$decor[$decore->id] = $decore->first_name.' '.$decore->last_name;
							}
						}
							echo form_dropdown('user_3', $decor, (isset($_POST['user_3']) ? $_POST['user_3'] : $dev_item->user_3) ,'id="user_3" class="form-control input-tip select" style="width:100%;" ');
						?>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label col-sm-5" style="text-align:left;"> <?= lang("ឈ្មោះអ្នកថត"); ?></label>
						<div class="col-sm-7">
						<?php
						$photographer = array(""=>"");
						foreach($computer as $grapher){
							if($grapher->group_id == 16){
								$photographer[$grapher->id] = $grapher->first_name.' '.$grapher->last_name;
							}
						}
							echo form_dropdown('user_4', $photographer, (isset($_POST['user_4']) ? $_POST['user_4'] : $dev_item->user_4) ,'id="user_4" class="form-control input-tip select" style="width:100%;" ');
						?>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label col-sm-5" style="text-align:left;"> <?= lang("ឈ្មោះអ្នកផ្តិត"); ?></label>
						<div class="col-sm-7">
						<?php
						$photocreater = array(""=>"");
						foreach($computer as $creater){
							if($creater->group_id == 17){
								$photocreater[$creater->id] = $creater->username;
							}
						}
							echo form_dropdown('user_5', $photocreater, (isset($_POST['user_5']) ? $_POST['user_5'] : $dev_item->user_5) ,'id="user_5" class="form-control input-tip select" style="width:100%;" ');
						?>
						</div>
					</div>
				</div>
			</div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('edit_products_develop', lang('edit_products_develop'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" charset="UTF-8">
    $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
</script>
<?= $modal_js ?>
<script type="text/javascript" charset="UTF-8">
    $(document).ready(function () {
        $.fn.datetimepicker.dates['erp'] = <?=$dp_lang?>;
        $("#date").datetimepicker({
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
    });
</script>
