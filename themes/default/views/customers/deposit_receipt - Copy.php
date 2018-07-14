<style type="text/css">
	
    @media print {
        #myModal .modal-content {
            display: none !important;
        }
    }
	
	
		
</style>
<div class="container">
<div class="modal-dialog no-modal-header">
    <div class="modal-content">
        <div class="modal-body print">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
			<div class="row">
				<div class="col-sm-3 col-xs-3">
					<?php if(!empty($biller->logo)) { ?>
						<img src="<?= base_url() ?>assets/uploads/logos/<?= $biller->logo; ?>" style="width: 165px; margin-left: 25px;" />
					<?php } ?>
				</div>
		
				<div class="col-sm-6 col-xs-6 company_addr" style="margin-top: 15px !important">
				<center>
					<?=$biller->biller_id; ?>
				
					<?php if(!empty($biller->vat_no)) { ?>
						<p style="font-size: 11px;">លេខអត្តសញ្ញាណកម្ម អតប (VAT No):&nbsp;<?= $biller->vat_no; ?></p>
					<?php } ?>
					
					<?php if(!empty($biller->address)) { ?>
						<p style="margin-top:-10px !important;font-size: 11px;">អាសយដ្ឋាន ៖ &nbsp;<?= $biller->address; ?></p>
					<?php } ?>
					
					<?php if(!empty($biller->phone)) { ?>
						<p style="margin-top:-10px !important;font-size: 11px;">ទូរស័ព្ទលេខ (Tel):&nbsp;<?= $biller->phone; ?></p>
					<?php } ?>
					
					<?php if(!empty($biller->email)) { ?>
						<p style="margin-top:-10px !important;font-size: 11px;">សារអេឡិចត្រូនិច (E-mail):&nbsp;<?= $biller->email; ?></p>
					<?php } ?>
				</center>
				</div>
				<div class="col-sm-3 col-xs-3">
					<button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
						<i class="fa fa-print"></i> <?= lang('print'); ?>
					</button>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12" style="margin-top: -10px !important">
					<center><h4	style="font-weight:bold;"><u><?= strtoupper(lang('official receipt')) ?></u></h4></center>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-7 col-sm-7 col-xs-7">
					<table style="font-size: 11px;">
						<?php if(!empty($customer->company)) { ?>
						<tr>
							<td style="width: 5%; font-size:12px !important;font-weight: bold !important;">Recieved From</td>
							<td style="width: 5%;">:</td>
							<td style="font-size:14px !important;font-weight: 900 !important;"><?= $customer->company ?></td>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->name_kh || $customer->name)) { ?>
						<tr>
							<td style="font-size:12px !important;font-weight: bold !important;">ATTN</td>
							<td>:</td>
							<?php if(!empty($customer->name_kh)) { ?>
							<td style="width: 50%; font-size:12px !important;font-weight: 900 !important;"><?= $customer->name_kh ?></td>
							<?php }else { ?>
							<td style="width:50%; font-size:12px !important;font-weight: bold !important;"><?= $customer->name ?></td>
							<?php } ?>
						</tr>
						<?php } ?>
						<?php if(!empty($customer->address_kh || $customer->address)) { ?>
						<?php } ?>
						<?php if(!empty($customer->address_kh || $customer->address)) { ?>
						<tr>
							<td style="width: 30%; font-size:12px !important;font-weight: bold !important;">Tel</td>
							<td>:</td>
							<td style="width: 1%; font-size:12px !important;font-weight: 900 !important;"><?= $customer->phone ?></td>
						</tr>
						<?php } ?>
						
					</table>
				</div>
				<div class="col-lg-5 col-sm-5 col-xs-5">
					<table width="100%">
						<tr>
							<td style="font-size:12px;"><b>RECIEPT No:</b></td>
							<td style="text-align:right; color:#8B0000; font-size:12px"><strong><?=$deposit->reference;?></strong></td>
						</tr>
						<tr>
							<td style="width:50%; font-size:12px"> <b>DATE:<b></td>
						<td style="text-align:right; color:#8B0000; font-size:12px"><strong><?=$this->erp->hrsd($sale_order->date);?></strong></td>
						</tr>
					</table>
				</div>
				
			</div>
			<div class="row">
				<div class="col-sm-12 col-xs-12">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th  style="width:100px !important;">Item</th>
							<th  style="width:300px !important;">Description</th>
							<th  style="width:150px !important;">Cheque No</th>
							<th  style="width:200px !important;">Reference</th>
							<th  style="width:200px !important;">Amount</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text-center">1</td>
							<td class="text-center"><?=$this->erp->decode_html(strip_tags($deposit->note));?></td>
							<td class="text-center"><?=$payments->cheque_no ?></td>
							<td class="text-center"><?=$sale_order->reference_no;?></td>
							<td class="text-center">$<?=$this->erp->formatMoney($deposit->amount) ?></td>
						</tr>
						<tr>
							<td colspan="3">
								<p><b>Amount In Word:</b></p>
								<p style="line-height: 1 !important"><?=  ucwords($this->erp->convert_number_to_words($deposit->amount)); ?> US Dollar Only</p>
							</td>
							<th class="text-center"><?= lang("total"); ?></th>
							<th class="text-center">$<?=  $this->erp->formatMoney($deposit->amount); ?></th>
						</tr>
					</tbody>
				</table>
				</div>
			</div>
			<div id="footer" class="row" style="margin-bottom: 50px;">
				<div class="col-lg-4 col-sm-4 col-xs-4">
					<p>Prepared By</p>
					<hr style="margin:0;margin-top:70px; border:1px solid #000;">
				</div>
				<div class="col-lg-4 col-sm-4 col-xs-4"></div>
				<div class="col-lg-4 col-sm-4 col-xs-4">
					<p>Recieved By</p>
					<hr style="margin:0;margin-top:70px;border:1px solid #000;">
				</div>
			</div>
			
            <div class="clearfix"></div>
        </div>
    </div>
</div>
</div>