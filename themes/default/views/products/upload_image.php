<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('Upload_Image'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <?php
                $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("products/upload_image", $attrib)
                ?>
                <div class="row">
                    <div class="col-md-12">
						<div class="col-md-12">
                            <div class="form-group">
                                <label for="image_file"><?= lang("upload_file"); ?></label>
                                <input type="file" multiple name="userfile[]" class="form-control file" data-show-upload="false" data-show-preview="true" id="img_file" required="required"/>
                            </div>
                            
                            <div class="form-group">
                                <?php echo form_submit('upload', $this->lang->line("Upload_Image"), 'class="btn btn-primary"'); ?>
                            </div>
                        </div>
                    </div>
                    <?= form_close(); ?>

                </div>

            </div>
        </div>
    </div>
</div>
