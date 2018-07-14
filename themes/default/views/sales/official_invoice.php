<style type="text/css">
    @media print {
        #myModal .modal-content {
            display: none !important;
        }
    }
	p{
		font-family:'Khmer OS';
	}
</style>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body print">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
           
		   
            <div class="clearfix"></div>
            <div class="row padding10">
                <div class="col-xs-12 text-center">
						<img style="width:120px;position:absolute;left:10px;top:0px;" src="<?=base_url() . 'assets/uploads/logos/ptc.png'; ?>">
						<p style="padding:0px; font-size:20px;font-family:'Khmer OS Muol Light';">ក្រុមហ៊ុន ភី ធី & ស៊ី អេជីនារីង</p>
						<p>P T & C ENGINEERING CO., LTD
						<br>ផ្ទះ 48, ផ្លូវលេខ 11AZ, សង្កាត់ ទឹកថ្លា, ខណ្ឌ សែនសុខ, រាជធានីភ្នំពេញ<br>
						#48, Street 11AZ, Sangkat Teuk Thla , Khan Sen Sok, Phnom Penh, Cambodia<br>
						លេខអត្តសញ្ញាណកម្មសារពីពន្ធ / VAT TIN #K009-901500714
						</p>
						<hr>
						<p style="padding:0px; font-size:18px;font-family:'Khmer OS Muol Light';">វិក្ក័យបត្រទទួលប្រាក់ <br>OFFICIAL INVOICE</p>
						
                    
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-5" style="float:left;">
                    <p><b><?= lang("ទទួលពី"); ?></b>: <?= $customer->company ? $customer->company : $customer->names; ?></p>
					<p><b><?= lang("Received Form"); ?></b>: <?= $customer->company ? $customer->company : $customer->names; ?><br><b>Dear Sir,</b> </p>
					<p>This is certifying that we have received the below mentioned amount.<br>Thank you very much for your early payment to us.</p>
					
                </div>
				<div class="col-sm-5 text-left" style="float:right;">
					<div class="pull-right">
						<p><b><?= lang("វិក្ក័យបត្រទទួលប្រាក់លេខ"); ?></b>: <?= $inv->reference_no; ?></p>
						<p><b><?= lang("Receipt No"); ?></b>: <?= $inv->reference_no; ?></p>
						<p><b><?= lang("លេខរៀងវិក្ក័យបត្រ"); ?></b>: <?= $inv->reference_no; ?></p>
						<p><b><?= lang("Invoice No"); ?></b>: <?= $inv->reference_no; ?></p>
						<p><b><?= lang("Project"); ?></b>: <?= $biller->company != '-' ? $biller->company : $biller->name; ?></p>
					</div>
                </div>
            </div>
            <div class="well">
				<table class="table receipt">
					<thead>
						<tr>
							<th>#</th>
							<th>បរិយាយមុខទំនិញ<br><?= lang("description"); ?></th>
							<th>បរិមាណ<br><?= lang("quantity"); ?></th>
							<th>ផ្ទៃឯកតា<br><?= lang("unit_price US$"); ?></th>
							
							<th style="padding-left:10px;padding-right:10px;">ថ្លៃទំនិញ<br><?= lang("amount"); ?> US$ </th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$m_us = 0;
						$total_quantity = 0;
						$sub = 0;
						foreach($rows as $row){
							
							//$this->erp->print_arrays($row);
							echo '<tr class="item"><td class="text-left">#' . $no . "</td>";
							echo '<td class="text-left">' . $row->details . '</td>';
							echo '<td class="text-center"></td>'; 
							
							echo '<td class="text-center"></td>';
							
							echo '<td class="text-right">' . ($this->erp->formatMoney($row->quantity*$row->real_unit_price)). '</td>';
							$no++;
							$total_quantity += $row->quantity;
							$sub += $row->quantity*$row->real_unit_price;
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="4" class="text-right">សរុប/Sub-Total</td>
							<td class="text-right"><?=$this->erp->formatMoney($sub)?></td>
						</tr>	
						<tr>
							<?php
							$q = $this->db->get_where('erp_tax_rates',array('id'=>$inv->order_tax_id),1);
							?>
							<td colspan="4" class="text-right">អាករលើតម្លៃបន្ថែម <?=$q->row()->rate?>%/VAT <?=$q->row()->rate?>%</td>
							<td class="text-right"><?=$this->erp->formatMoney($inv->order_tax)?></td>
						</tr>
						<tr>
							<td colspan="4" class="text-right">សរុប/Total US$</td>
							<td class="text-right"><?=$this->erp->formatMoney($sub-$inv->order_tax)?></td>
						</tr>
					</tfoot>
				</table>
			
            </div>
			
            <div style="clear: both;"></div>
            <div class="row">
				<div class="col-sm-4 pull-left">
                    <p>ហត្ថលេខា និង ឈ្មោះអ្នកទទួល <br>Receicer`s Signature & Name</p>
                  <br><br><br><br>
                    <p>Mr. Ourng Kimsary<br>Managing Director</p>
                </div>	
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>