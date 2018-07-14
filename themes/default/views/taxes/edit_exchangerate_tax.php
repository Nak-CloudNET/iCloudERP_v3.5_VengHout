<div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_condition_tax'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'addAcc');
        echo form_open_multipart("taxes/update_exchange_tax_rate_by_id", $attrib); ?>
        <div class="modal-body" >
            <p><?= lang('enter_info'); ?></p>

            <div class="row">
				
                               <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                       class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
						<th style="width:15%;"><?= $this->lang->line("salary_KHR"); ?></th>
                        <th style="width:15%;"><?= $this->lang->line("average_KHR"); ?></th>
						<th style="width:15%;" rowspan=""><?= $this->lang->line("Month"); ?></th>
						<th style="width:10%;" rowspan=""><?= $this->lang->line("Year"); ?></th>
						<th style="width:2%;display:none"  rowspan=""><button type="button" class="btn btn-primary" id="addDescription"><i class="fa fa-plus-circle"></i></button></th>
					</tr>
                    </thead>
                    <tbody class="tbody">
					<tr>
						<td><?= form_input('salary_khr', $tax_rate->salary_khm, 'class="form-control salary_khr" id="salary_khr"'); ?></td>
						<td><?= form_input('average_khr', $tax_rate->average_khm, 'class="form-control average_khr" id="average_khr"'); ?></td>
						<td>
						<?= form_input('month', $tax_rate->month, 'class="form-control month" id="month"'); ?>
						</td>
						<td>
						<?= form_input('year', $tax_rate->year, 'class="form-control month" id="year"'); ?>
						</td>
						<td style="text-align:center;display:none"><span style="cursor:pointer;" class="btn_delete"><i style="font-size: 15px; color:#2A79B9;" class="fa fa-trash-o"></i></span></td>
					</tr>
                    </tbody>
                </table> 
			
			</div>
        <div class="modal-footer">
			<input name="id" type="hidden" value="<?=$tax_rate->id?>" />
            <?php echo form_submit('add_condition_tax', lang('add_condition_tax'), 'class="btn btn-primary" id="add_condition_tax"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?= $modal_js ?>

<script type="text/javascript">
var InputsWrapper   = jQuery(".tbody");
var AddButton       = jQuery("#addDescription");
$("#addDescription").change();
$(AddButton).click(function (e){
		var my_i = ($(".tbody tr").size())-0+1;
			var div  ='<tr>';
				div +='<td><input type="text" name="salary_khr[]" class="form-control salary_khr" id="salary_khr" /></td>';
				div +='<td><input type="text" name="average_khr[]" class="form-control average_khr" id="average_khr" /></td>';
				div +='<td>';
				div +='<select name="month[]" style="width:100%" class="form-control"><?php for($i=1;$i<=12;$i++){$dateObj   = DateTime::createFromFormat('!m', $i);$monthName = $dateObj->format('F');echo "<option value=".$i.">".$monthName."</option>";}?>"</select></td>';
				div +='<td><select name="year[]" style="width:100%" class="form-control"><?php $startingYear = date('Y');$endingYear = $startingYear + 20;for ($i = $startingYear;$i <= $endingYear;$i++){echo '<option value='.$i.'>'.$i.'</option>';}?>"</select></td>';
				div +='<td style="text-align:center;"><span style="cursor:pointer;" class="btn_delete"><i style="font-size: 15px; color:#2A79B9;" class="fa fa-trash-o"></i></span></td>';
				div +='</tr>';

			$(InputsWrapper).append(div);
		
		return false;
	});
	
	$(document).on("click",".btn_delete",function() {
			var row = $(this).closest('tr').focus();
			row.remove();
			$('.no').each(function(i){
				var j=i-0+1;
				$(this).html(j);
			});
	});
</script>

