<div class="modal-dialog"  tabindex="1">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_subcategory'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/add_subcategory", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <?php echo lang('main_category', 'category'); ?>
                <div class="controls"> <?php
                    $ct[""] = $this->lang->line("select") . " " . $this->lang->line("main_category");
                    foreach ($categories as $category) {
                        $ct[$category->id] = $category->name;
                    }
                    echo form_dropdown('category', $ct, (isset($_POST['category']) ? $_POST['category'] : $parent_id), 'class="form-control select" id="category" required="required"');
                    ?> </div>
            </div>
            <div class="form-group">
                <?php echo lang('subcategory_code', 'code'); ?>
                <div class="controls">
                    <?php echo form_input($code); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo lang('subcategory_name', 'name'); ?>
                <div class="controls">
                    <?php echo form_input($name); ?>
                </div>
            </div>
			 <div class="form-group">
                <?php echo lang('cate_type', 'cate_type'); ?>
                <div class="controls">
                    <?php 
					$type = array(''=>'None','food'=>'FOOD','drink'=>'DRINK');
					echo form_dropdown('cate_type', $type, '' ,'id="cate_type" class="form-control select" " style="width:100%;" '); 
					?>
                </div>
            </div>
            <div class="form-group">
                <?= lang("category_image", "image") ?>
                <input id="image" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_subcategory', lang('add_subcategory'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<?= $modal_js ?>