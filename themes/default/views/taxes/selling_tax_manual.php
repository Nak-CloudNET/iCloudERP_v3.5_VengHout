<style>
	.modal-dialog{
		font-size:11px;
	}
	.none-padding td{
		padding:0 !important;
	}
	.none-padding td input{
		border:none !important;
		font-size:11px;
	}
	.tax-model td,th{padding:4px !important;}
	#hide-border td{border: 0px  !important;} 
	.table{border:none !important;}
	body{font-family: Khmer OS System,Nida Sowanaphum;}
	.text-tax{font-size:12px;line-height: 80%;} 
	.tax-model table{font-size:11px;}
</style>
<?= $modal_js; ?>
<div class="modal-dialog modal-lg tax-model" style="width:100%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('selling_tax_manual'); ?></h4>
        </div>
		<?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("taxes/selling_tax_manual", $attrib); ?>
        <div class="modal-body" style="	overflow-x: auto;">
            <p><?= lang('enter_info'); ?></p>
				<div>
					<div class="row print">
					<div>
						<center>
							<h4><b style="font-family:'Khmer OS Muol';">ទិន្នានុប្បវត្តិលក់</b></h4>
							<h6><b>SALES JOURNAL</b></h6>
							<h6><b style="font-family:'Khmer OS Muol';">សំរាប់ខែ <?php $month= date('m'); echo $this->erp->KhmerMonth($month);?> ឆ្នាំ <?php echo date('Y');?></b></h6>
							<h6><b>For <?=date('M');?> <?=date('Y');?></b></h6>
						</center>
						<div class="col-md-10 col-xs-10 text-tax">
							<table>
								<tr>
									<td><p>នាមករណ័សហគ្រាស  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </td>
									<td style="vertical-align:middle !important;">
										<div style="width:300px; ">
											<select name="enterprise" id="enterprise" class="form-control enterprise" required="required">
												<?php			
													echo '<option value=""></option>';
													foreach($enterprise as $ent){
														echo '<option value="'.$ent->id.'">'.$ent->company.'</option>';
													}
												?>
											</select>
										</div>	
									</td></p>
								</tr>
								<tr>	
									<td><p>Company Name 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:<td><b></b></p>
								<tr>
								<tr>
									<td><p>លេខអត្តសញ្ញាណកម្មអតប  <span style="padding-left:4px;"> :</span></p></td><td> <span name="vat_no" id="vat_no" class="vat_no"></span></td>
								</tr>
								<tr>
									<td><p>អាស័យដ្ឋាន  <span style="padding-left:74px;">:</span></div></p></td><td><span name="address_kh" id="address_kh" class="address_kh"></span></td>
								</tr>
							</table>
						</div>
						<div class="col-md-2 col-xs-2 text">
							<p style="float:right;">អត្រា  :&nbsp;<b><?=$this->erp->formatMoney($exchange_rate->rate);?> <input type="hidden" name="exc_rate" value="<?=$exchange_rate->rate;?>" /></b></p>
						</div>
					</div>
					</div>
					<table class="table table-bordered">
						<tr>
							<td style="vertical-align: middle !important" rowspan="5">N<sup>o</sup></td>
							<th style="text-align:center !important" colspan="6">វិក័យប័ត្រ<br>Invoice</th>
							<th style="text-align:center !important" colspan="7">ការផ្គត់ផ្គង់<br>Supplies</th>	
							<th></th>	
						</tr>
						<tr>
							<td style="text-align:center !important;vertical-align: middle !important;" rowspan="3">ថ្ងៃទី<br>Date</td>
							<td style="text-align:center !important;vertical-align: middle !important;" rowspan="3">លេខវិក័យប័ត្រ<br>Invoicen<sup>o</sup> <br></td>
							<td style="text-align:center !important;vertical-align: middle !important" rowspan="3">អ្នកទិញ<br>Client</td>
							<td style="text-align:center !important;vertical-align: middle !important" rowspan="3">លេខអតប<br>VAT Tin</td>
							<td style="text-align:center !important;vertical-align: middle !important" rowspan="3">បរិយាយ<br>Description<br></td>
							<td style="text-align:center !important;vertical-align: middle !important" rowspan="3">បរិមាណ<br>Qty<br></td>
							<td style="text-align:center !important;vertical-align: middle !important" rowspan="3">រផ្គត់ផ្គង់មិនជាប់អាករ<br>Non-taxable sale<br></td>
							<td style="text-align:center !important;vertical-align: middle !important" rowspan="3">ការនាំចេញ<br>Value of Exports<br></td>
							<td style="text-align:center !important;vertical-align: middle !important" colspan="4">ការលក់ជាប់អាករ<br>Taxable sale</td>
							<td style="text-align:center !important;vertical-align: middle !important" rowspan="3">សរុបតម្លៃលក់រួមទាំងអាករ<br>Total Taxable Value</td>
							<td  rowspan="3"></td>
						</tr>
						<tr>
							<td colspan="2" style="text-align:center;">លក់អោយបុគ្គលជាប់អាករ<br>Sales to Taxable Persons</td>
							<td colspan="2" style="text-align:center;">លក់អោយអ្នកប្រើប្រាស់<br>Sales to Customers</td>
						</tr>
						<tr>
							<td  style="text-align:center !important"  >តម្លៃជាប់អាករ<br>Taxable Value</td>
							<td style="text-align:center !important"  >អាករ<br>VAT<br></td>
							<td  style="text-align:center !important" >តម្លៃជាប់អាករ<br>Taxable Value</td>
							<td  style="text-align:center !important" >អាករ<br>VAT</td>
						</tr>
						<tr>
							<td style="text-align:center !important"  >S1</td>
							<td style="text-align:center !important"  >S2</td>
							<td style="text-align:center !important"  >S3</td>
							<td style="text-align:center !important"  >S4</td>
							<td style="text-align:center !important"  >S5</td>
							<td style="text-align:center !important"  >S6</td>
							<td style="text-align:center !important"  >S7</td>
							<td style="text-align:center !important"  >S8</td>
							<td style="text-align:center !important"  >S9</td>
							<td style="text-align:center !important"  >S10</td>
							<td style="text-align:center !important"  >S11</td>
							<td style="text-align:center !important"  >S12</td>
							<td style="text-align:center !important"  >S13=sum(S7:S12)</td>
							<td><span style="cursor:pointer;" name="btn-add" id="btn-add" class="btn-add"><i style="padding:2px; color:#2A79B9;" class="fa fa-2x fa-plus-circle"></i></span></td>
						</tr>
						<tbody class="sale_tax">
							<tr class="none-padding">
								<td><p style="padding:5px !important; padding-left:10px !important;" class="no">1</p></td>
								<td><input type="text"  name="s1[]" id="s1" class="form-control datetime s1" value="" style="width:100px;"></td>
								<td><input type="text"  name="s2[]" id="s2" class="form-control s2" value=""></td>
								<td><input type="text"  name="s3[]" id="s3" class="form-control s3" value="" style="width:100px;"></td>
								<td><input type="text"  name="s4[]" id="s4" class="form-control s4" value=""></td>
								<td><input type="text"  name="s5[]" id="s5" class="form-control s5" value="" style="width:100px;"></td>
								<td><input type="text"  name="s6[]" id="s6" class="checknb form-control text-center s6" value=""></td>
								<td><input type="text"  name="s7[]" id="s7" class="checknb form-control text-right s7" value=""></td>
								<td><input type="text"  name="s8[]" id="s8" class="checknb form-control text-right s8" value=""  style="width:100px;"></td>
								<td><input type="text"  name="s9[]" id="s9" class="checknb form-control text-right s9" value=""></td>
								<td><input type="text"  name="s10[]" id="s10" class="checknb form-control text-right s10" value=""></td>
								<td><input type="text"  name="s11[]" id="s11" class="checknb form-control text-right s11" value=""></td>
								<td><input type="text"  name="s12[]" id="s12" class="checknb form-control text-right s12" value=""></td>
								<td><input type="text"  name="s13[]" id="s13" class="form-control text-right s13" style="font-weight:bold;" value="" readonly ></td>
								<td style="text-align:center;"><span style="cursor:pointer;" class="btn_delete"><i style="font-size: 15px; color:#2A79B9;" class="fa fa-trash-o"></i></span></td>
							</tr>
						</tbody>						
						<tr>
							<td  style="text-align:right !important"  colspan="7">សរុបលក់ជារៀល<br>Total Sale in KHR</td>
							<td name="total_s7" id="total_s7" class="total_s7" style="padding-right:15px; text-align:right; font-weight:bold;"></td>
							<td name="total_s8" id="total_s8" class="total_s8" style="padding-right:15px; text-align:right; font-weight:bold;"></td>
							<td name="total_s9" id="total_s9" class="total_s9" style="padding-right:15px; text-align:right; font-weight:bold;"></td>
							<td name="total_s10" id="total_s10" class="total_s10" style="padding-right:15px; text-align:right; font-weight:bold;"></td>
							<td name="total_s11" id="total_s11" class="total_s11" style="padding-right:15px; text-align:right; font-weight:bold;"></td>
							<td name="total_s12" id="total_s12" class="total_s12" style="padding-right:15px; text-align:right; font-weight:bold;"></td>
							<td name="total_s13" id="total_s13" class="total_s13" style="padding-right:15px; text-align:right; font-weight:bold;"></td>
							<td></td>
						</tr>
						<tr id="hide-border">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td style="text-align:center !important"  >Box12</td>
							<td style="text-align:center !important"  >Box13</td>
							<td style="text-align:center !important"  >Box14</td>
							<td style="text-align:center !important"  >Box15</td>
							<td style="text-align:center !important"  >Box14</td>
							<td style="text-align:center !important"  >Box15</td>
							<td></td>	
						</tr>
					</table>
				</div>
		</div>
		<div class="modal-footer">
            <?php echo form_submit('add_selling_tax', lang('add_selling_tax'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".checknb").keypress(function (e) {
			var st= $(this);
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				
				setTimeout(function(){
				$({alpha:1}).animate({alpha:0}, {
					duration: 2000,
					step: function(){
						st.css('border-color','rgba(255,0,0,'+this.alpha+')');
					}
				});
				}, 10);
				   return false;
		}
	   });
		$('.enterprise').change(function(){
			var ent_id = $(this).val();
			$.ajax({
				type: 'get',
				url: '<?= site_url('taxes/getEnterpriceInfo'); ?>',
				dataType: "json",
				data: {
					ent_id: ent_id
				},
				success: function (data) {
					var address_kh = data.address +" "+ data.street +" "+ data.group +" "+ data.village +" "+ data.sangkat +" "+ data.district +" "+ data.city;
					$('#vat_no').text(data.vat_no);
					$('#address_kh').text(address_kh);
				}
			});
		});
		$('#btn-add').click(function() {
			var my_i = ($(".sale_tax tr").size())-0+1;
			var row = '<tr class="none-padding">'+
							'<td><p style="padding:5px !important; padding-left:10px !important;" class="no">1</p></td>'+
							'<td><input type="text"  name="s1[]" id="s1" class="form-control datetime s1" value="" style="width:100px;"></td>'+
							'<td><input type="text"  name="s2[]" id="s2" class="form-control s2" value=""></td>'+
							'<td><input type="text"  name="s3[]" id="s3" class="form-control s3" value="" style="width:100px;"></td>'+
							'<td><input type="text"  name="s4[]" id="s4" class="form-control s4" value=""></td>'+
							'<td><input type="text"  name="s5[]" id="s5" class="form-control s5" value="" style="width:100px;"></td>'+
							'<td><input type="text"  name="s6[]" id="s6" class="checknb form-control text-center s6" value=""></td>'+
							'<td><input type="text"  name="s7[]" id="s7" class="checknb form-control text-right s7" value=""></td>'+
							'<td><input type="text"  name="s8[]" id="s8" class="checknb form-control text-right s8" value=""  style="width:100px;"></td>'+
							'<td><input type="text"  name="s9[]" id="s9" class="checknb form-control text-right s9" value=""></td>'+
							'<td><input type="text"  name="s10[]" id="s10" class="checknb form-control text-right s10" value=""></td>'+
							'<td><input type="text"  name="s11[]" id="s11" class="checknb form-control text-right s11" value=""></td>'+
							'<td><input type="text"  name="s12[]" id="s12" class="checknb form-control text-right s12" value=""></td>'+
							'<td><input type="text"  name="s13[]" id="s13" class="form-control text-right s13" style="font-weight:bold;" value="" readonly ></td>'+
							'<td style="text-align:center;"><span style="cursor:pointer;" class="btn_delete"><i style="font-size: 15px; color:#2A79B9;" class="fa fa-trash-o"></i></span></td>'+
						'</tr>';
			$('.sale_tax').append(row);
		});
		$(document).on("click",".btn_delete",function() {
			var row = $(this).closest('tr').focus();
			row.remove();
			$('.no').each(function(i){
				var j=i-0+1;
				$(this).html(j);
			});
		});
		function getTotal(cont) {
			var total = 0;
			$('.'+cont).each(function() {
				total += $(this).val()-0;
			});
			return total;
		}
		$('.s7, .s8, .s9, .s10, .s11, .s12').live('change', function() {
			var tr = $(this).parent().parent();
			var s7 = tr.find('.s7').val()-0;
			var s8 = tr.find('.s8').val()-0;
			var s9 = tr.find('.s9').val()-0;
			var s10 = tr.find('.s10').val()-0;
			var s11 = tr.find('.s11').val()-0;
			var s12 = tr.find('.s12').val()-0;
			var s13 = s7 + s8 + s9 + s10 + s11 + s12;
			tr.find('.s13').val(s13);
			
			$('#total_'+$(this).attr('id')).text(getTotal($(this).attr('id')));
			$('#total_s13').text(getTotal('s13'));
		});
	});
</script>