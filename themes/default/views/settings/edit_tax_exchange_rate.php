<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_tax_exchange_rate'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open("system_settings/update_tax_exchange_rate/".$id, $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
            <div class="form-group">
                <label for="code"><?php echo $this->lang->line("usd"); ?></label>

                <div class="controls"> <?php echo form_input('usd', $info->usd, 'class="form-control" id="usd" readonly="readonly" required="required"'); ?> </div>
            </div>
            <div class="form-group">
                <label for="khm"><?php echo $this->lang->line("salary_khm"); ?></label>

                <div class="controls"> <?php echo form_input('salary_khm',$info->salary_khm, 'class="form-control"  id="salary_khm"  '); ?> </div>
            </div>
			<div class="form-group">
                <label for="khm"><?php echo $this->lang->line("average_khm"); ?></label>

                <div
                    class="controls"> <?php echo form_input('average_khm',$info->average_khm , 'class="form-control" id="average_khm"   required="required"'); ?> </div>
            </div>
			 <div class="form-group">
                <label for="month"><?php echo $this->lang->line("year"); ?></label>

                <div class="controls"> 
					<?php 
					for($i=2016; $i<=2026;$i++){
						$year[$i] =$i;
					}
                    echo form_dropdown('year', $year, $info->year, 'class="form-control" id="year" required="required" '); 
					?> 
				</div>
            </div>
            <div class="form-group">
                <label for="month"><?php echo $this->lang->line("month"); ?></label>

                <div class="controls"> 
					<?php 
					for($i=1; $i<=12;$i++){
						$month[$i] = date('F', mktime(0, 0, 0, $i, 1));
					}
                    echo form_dropdown('month', $month,  $info->month , 'class="form-control" id="month" required="required" '); 
					?> 
				</div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo form_submit('update_tax_rate', lang('update_tax_rate'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<?= $modal_js ?>