<!--<div class="box" style="margin-bottom: 15px;">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-folder"></i><?= lang('file_backups'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="#" id="backup_files"><i class="icon fa fa-file-zip-o"></i><span
                            class="padding-right-10"><?= lang('backup_files'); ?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('restore_heading'); ?></p>

                <div class="row">
                    <div class="col-md-12">
                        <?php
                        foreach ($files as $file) {
                            $file = basename($file);
                            echo '<div class="well well-sm">';
                            $date_string = substr($file, 12, 10);
                            $time_string = substr($file, 23, 8);
                            $date = $date_string . ' ' . str_replace('-', ':', $time_string);
                            $bkdate = $this->erp->hrld($date);
                            echo '<h3>' . lang('backup_on') . ' <span class="text-primary">' . $bkdate . '</span><div class="btn-group pull-right" style="margin-top:-12px;">' . anchor('system_settings/download_backup/' . substr($file, 0, -4), '<i class="fa fa-download"></i> ' . lang('download'), 'class="btn btn-primary"') . ' ' . anchor('system_settings/restore_backup/' . substr($file, 0, -4), '<i class="fa fa-database"></i> ' . lang('restore'), 'class="btn btn-warning restore_backup"') . ' ' . anchor('system_settings/delete_backup/' . substr($file, 0, -4), '<i class="fa fa-trash-o"></i> ' . lang('delete'), 'class="btn btn-danger delete_file"') . ' </div></h3>';
                            echo '<div class="clearfix"></div></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-database"></i><?= lang('database_backups'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown"><a href="<?= site_url('system_settings/backup_database') ?>"><i
                            class="icon fa fa-database"></i><span
                            class="padding-right-10"><?= lang('backup_database'); ?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('restore_heading'); ?></p>

                <div class="row">
                    <div class="col-md-12">
                        <?php
                        foreach ($dbs as $file) {
                            $file = basename($file);
                            echo '<div class="well well-sm">';
                            $date_string = substr($file, 13, 10);
                            $time_string = substr($file, 24, 8);
                            $date = $date_string . ' ' . str_replace('-', ':', $time_string);
                            $bkdate = $this->erp->hrld($date);
							$restore = array(
								'onclick' => "restoreData('" . substr($file, 0, -4) ."')",
								'class' => "btn btn-warning restore_db"
							);
                            echo '<h3>' . lang('backup_on') . ' <span class="text-primary">' . $bkdate . '</span><div class="btn-group pull-right" style="margin-top:-12px;">' . anchor('system_settings/download_database/' . substr($file, 0, -4), '<i class="fa fa-download"></i> ' . lang('download'), 'class="btn btn-primary"') . ' ' . anchor('system_settings/backups#', '<i class="fa fa-database"></i> ' . lang('restore'), $restore) . ' ' . anchor('system_settings/delete_database/' . substr($file, 0, -4), '<i class="fa fa-trash-o"></i> ' . lang('delete'), 'class="btn btn-danger delete_file"') . ' </div></h3>';
                            echo '<div class="clearfix"></div></div>';
                        }
                        ?>
                    </div>
					
					<div class="col-md-12">
						<!--<progress value="0" id="progress" class="form-control">25%</progress>-->
						<div id="progressCounter"></div><br>
						<div id="proccess-load" style="display:none;"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><span class="">Restoring...</span></div><br>
						<div id="data"></div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="wModal" tabindex="-1" role="dialog" aria-labelledby="wModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="wModalLabel"><?= lang('please_wait'); ?></h4>
            </div>
            <div class="modal-body">
                <?= lang('backup_modal_msg'); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#backup_files').click(function (e) {
            e.preventDefault();
            $('#wModalLabel').text('<?=lang('backup_modal_heading');?>');
            $('#wModal').modal({backdrop: 'static', keyboard: true}).appendTo('body').modal('show');
            window.location.href = '<?= site_url('system_settings/backup_files'); ?>';
        });
        $('.restore_backup').click(function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            bootbox.confirm("<?=lang('restore_confirm');?>", function (result) {
                if (result) {
                    $('#wModalLabel').text('<?=lang('restore_modal_heading');?>');
                    $('#wModal').modal({backdrop: 'static', keyboard: true}).appendTo('body').modal('show');
                    window.location.href = href;
                }
            });
        });
		/*
        $('.restore_db').click(function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            bootbox.confirm("<?=lang('restore_confirm');?>", function (result) {
                if (result) {
                    window.location.href = href;
                }
            });
        });
		*/
        $('.delete_file').click(function (e) {
            e.preventDefault();
            var href = $(this).attr('href');
            bootbox.confirm("<?=lang('delete_confirm');?>", function (result) {
                if (result) {
                    window.location.href = href;
                }
            });
        });
    });
	/*
	(function addXhrProgressEvent($) {
		var originalXhr = $.ajaxSettings.xhr;
		$.ajaxSetup({
			xhr: function() {
				var req = originalXhr(), that = this;
				if (req) {
					if (typeof req.addEventListener == "function" && that.progress !== undefined) {
						req.addEventListener("progress", function(evt) {
							that.progress(evt);
						}, false);
					}
					if (typeof req.upload == "object" && that.progressUpload !== undefined) {
						req.upload.addEventListener("progress", function(evt) {
							that.progressUpload(evt);
						}, false);
					}
				}
				return req;
			}
		});
	})(jQuery);
	*/
	
	function restoreData(file){
		bootbox.confirm("<?=lang('restore_confirm');?>", function (result) {
			if (result) {
				var proccess_load = $('#proccess-load');
				var progressElem = $('#progressCounter');
				var URL = "<?php echo base_url() ?>system_settings/restore_database/" + file;
				// progressElem.text(URL);
				
				$.ajax({
					type: 'GET',
					dataType: 'json',
					url: URL,
					cache: false,
					error: function (xhr, ajaxOptions, thrownError) {
						//alert(xhr.responseText);
						//alert(thrownError);
					},
					xhr: function () {
						var xhr = new window.XMLHttpRequest();
						//Download progress
						xhr.addEventListener("progress", function (evt) {
							console.log(evt.lengthComputable);
							if (evt.lengthComputable) {
								var percentComplete = evt.loaded / evt.total;
								progressElem.html(Math.round(percentComplete * 100) + "%");
							}
						}, false);
						return xhr;
					},
					beforeSend: function () {
						$('#ajaxCall').show();
						proccess_load.show();
					},
					complete: function () {
						$("#ajaxCall").hide();
						proccess_load.hide('slow');
					},
					success: function (response) {
						if(response){
							$("#data").html('<div class="alert alert-success">Database has been restore</div>');
						}else{
							$("#data").html('<div class="alert alert-danger">Error while restoring database!</div>');
						}
					}
				});
			}
		});
	}
	
</script>