

<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('define_principle_rate'); ?></h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                  
                  <a href="<?= site_url('system_settings/create_define_principle_rate/'.$id); ?>" data-toggle="modal"
                               data-target="#myModal"><i class="icon fa fa-plus tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("add") ?>"></i> </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo $this->lang->line("list_results"); ?></p>
				<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
				//echo form_open("system_settings/create_define_principle_rate/".$id, $attrib); ?>
                <div class="table-responsive">
				
                    <table id="GPData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:60px; width: 60px; text-align: center;">
                              <?php echo $this->lang->line("period"); ?>
                            </th>
                           
                            <th><?php echo $this->lang->line("dateline"); ?></th>
							<th><?php echo $this->lang->line("principle"); ?></th>
							<th><?php echo $this->lang->line("remark"); ?></th>
							 <th><?php echo $this->lang->line("rate"); ?></th>
                            <th style="width:60px;"><i class="fa-fw fa fa-trash-o"></i></th>
                        </tr>
                        </thead>
                        <tbody id="table_sc">
							<?php 
								foreach($principle_data as $row){
							?>
								<tr>
									<td><input type="hidden" class="idd" value="<?=$row->id?>"><input type="text" style="width:40px;text-align:center;" class="number_only form-control period" name="period" value="<?=$row->period?>"></td>
									<td><?=$this->erp->hrsd($row->dateline)?></td>
									<td><?=$row->value?></td>
									<td><?=$row->remark?></td>
									<td><?=$row->rate?></td>
								
									<td>
									<a href="<?= site_url('system_settings/update_define_principle_rate_byid/'.$row->id.'/'.$id);?>" data-toggle='modal' data-target='#myModal' class='tip' title='<?= lang("update_define_principle_rate")?>'><i class="fa fa-edit"></i></a> | 
									<a class="conf" href="<?= site_url('system_settings/delete_define_principle_rate_byid/'.$row->id.'/'.$id);?>"  class='tip' title='<?= lang("delete_subcategory")?>'> <i class="fa fa-trash-o"></i> </a></td>
								</tr>
							<?php
								}
							?>
                        </tbody>
                    </table>
					<?php// echo form_submit('add_define_principle_rate', lang('submit'), 'class="btn btn-primary"'); ?>
                </div>
				<?php// echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>

$( window ).load(function() {
			$(document).on(' keyup ', '.period', function () {
					var row = $(this).closest('tr');
					 var period = row.find('.period').val();
					 var id = row.find('.idd').val();
					
					 $.ajax({
						type: 'post',
						url: site.base_url+'system_settings/updateperiod/'+id,
						dataType: "json",
						async:false,
						data: { <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>',period:period },
						success: function (data) {
							 
						}
					});	
							 
				});	
				
			$(".conf").click(function(e){
				var url = $(this).attr('href');
				e.preventDefault();
				bootbox.confirm('Are you sure?', function(rs){
					if(rs){
						window.location.href = url;
					}
				});
			});
			
			
	});
    
		

	
</script>