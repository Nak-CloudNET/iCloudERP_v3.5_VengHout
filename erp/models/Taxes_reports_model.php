<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Taxes_reports_model extends CI_Model
{	
	function getConfirmTax($id){
	  /*$this->db->select("YEAR(erp_sale_tax.issuedate) AS yearly,journal_date,journal_location, MONTH(erp_sale_tax.issuedate) AS monthly,SUM(erp_sale_tax.amound) AS amount, SUM(erp_sale_tax.amound_tax) AS amount_tax, SUM(erp_sale_tax.amound_declare) AS amount_dec,erp_companies.company, erp_sale_tax.group_id", FALSE)
				 ->join('erp_companies', 'erp_sale_tax.group_id=erp_companies.id', 'INNER')
				 ->group_by('MONTH(erp_sale_tax.issuedate), erp_sale_tax.group_id');
		*/	
		$this->db->where('tax_type',$id);
		$this->db->select("YEAR(erp_sale_tax.issuedate) AS yearly,journal_date,journal_location, MONTH(erp_sale_tax.issuedate) AS monthly,SUM(erp_sale_tax.amound) AS amount, SUM(erp_sale_tax.amound_tax) AS amount_tax,
		
		SUM(erp_sale_tax.non_tax_sale) AS amount_non_tax_sale,
		SUM(erp_sale_tax.value_export) AS amount_value_export,
		SUM(erp_sale_tax.amound_declare) AS amount_dec,
		SUM(erp_sale_tax.amount_tax_declare) AS amount_tax_dec,
		
		erp_companies.company, erp_sale_tax.group_id", FALSE)
				 ->join('erp_companies', 'erp_sale_tax.group_id=erp_companies.id', 'INNER')
				 ->group_by('MONTH(erp_sale_tax.issuedate)');
	  $q =$this->db->get('erp_sale_tax');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
	}
	
	function getDatebyId($vat_id){
		$this->db->select('DATE(issuedate) as issuedate')
				 ->from('sale_tax')
				 ->where('vat_id',$vat_id);
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
		
	}
	
	function tax_return_payment($month=null, $year=null, $previous=null){
		if($previous){
			$currDate = $year.'-'.$month.'-01' ;
			$this->db->where('covreturn_start<', $currDate);
		}else{
			$this->db->where('month', $month);
			$this->db->where('year', $year);
		}
		
		
		$this->db->select("id as ref_id,
				SUM(precaba_month05) AS precaba_month05,
				SUM(premonth_rate06) AS premonth_rate06,
				SUM(sptax_calbase09) AS sptax_calbase09,
				SUM(sptax_duerate10) AS sptax_duerate10,
				SUM(sptax_calbase11) AS sptax_calbase11,
				SUM(sptax_duerate12) AS sptax_duerate12,
				SUM(taxacc_calbase13) AS taxacc_calbase13,
				SUM(taxacc_duerate14) AS taxacc_duerate14,
				SUM(taxpuli_calbase15) AS taxpuli_calbase15,
				SUM(taxpuli_duerate16) AS taxpuli_duerate16,
				SUM(tax_calbase17) AS tax_calbase17,
				SUM(tax_duerate18) AS tax_duerate18", false);
		
		$this->db->group_by('group_id');
		$q = $this->db->get('erp_return_tax_front');
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	function return_withholding_tax($month=null, $year=null, $previous=null){
		if($previous){
			$currDate = $year.'-'.$month.'-01' ;
			$this->db->where('erp_return_withholding_tax.covreturn_start<', $currDate);
		}else{
			$this->db->where('erp_return_withholding_tax.month', $month);
			$this->db->where('erp_return_withholding_tax.year', $year);
		}
		
		$this->db->select("erp_return_withholding_tax_front.id as ref_id,
				SUM(amount_paid) AS amount_paid,
				tax_rate,
				SUM(withholding_tax) AS withholding_tax", false);
				
		$this->db->group_by('erp_return_withholding_tax_front.id');
		$this->db->order_by('erp_return_withholding_tax_front.id', 'asc');
		$this->db->join("erp_return_withholding_tax_front","erp_return_withholding_tax_front.withholding_id = erp_return_withholding_tax.id");
		$q = $this->db->get('erp_return_withholding_tax');
		if($q->num_rows()>0){
			return $q->result();
		}
		return false;
	}
	
	function return_tax_on_salary($month=null, $year=null, $previous=null){
		if($previous){
			$currDate = $year.'-'.$month.'-01' ;
			$this->db->where('erp_salary_tax.covreturn_start<', $currDate);
		}else{
			$this->db->where('erp_salary_tax.month', $month);
			$this->db->where('erp_salary_tax.year', $year);
		}
		
		$this->db->select("erp_salary_tax_front.id as ref_id,
					sum(tax_salcalbase) as tax_salcalbase,
					tax_rate,
					sum(salary_paid) as salary_paid,
					tax_type", false);
				
		$this->db->group_by('erp_salary_tax_front.tax_type');
		$this->db->group_by('erp_salary_tax.month');
		$this->db->group_by('erp_salary_tax.year');
		
		$this->db->order_by('erp_salary_tax_front.tax_type', 'desc');
		$this->db->join("erp_salary_tax","erp_salary_tax.id = erp_salary_tax_front.salary_tax_id");
		$q = $this->db->get('erp_salary_tax_front');
		if($q->num_rows()>0){
			return $q->result();
		}
		return false;
	}
	
	function return_value_added_tax($month=null, $year=null, $previous=null){
		if($month && $year){
			if($previous){
				$currDate = $year.'-'.$month.'-01' ;
				$this->db->where('erp_return_value_added_tax.covreturn_start<', $currDate);
			}else{
				$this->db->where('erp_return_value_added_tax.month', $month);
				$this->db->where('erp_return_value_added_tax.year', $year);
			}
			
			$this->db->select("erp_return_value_added_tax.id as ref_id,
								SUM(pay_difference16) AS pay_difference16", false);
				
			$this->db->group_by('erp_return_value_added_tax.month');
			$this->db->group_by('erp_return_value_added_tax.year');
			
			$q = $this->db->get('return_value_added_tax');
			if($q->num_rows()>0){
				return $q->result();
			}
			return false;
		}
		
	}
	
	function delete_tax_payment($month=null, $year=null){
		if($month && $year){
			$this->db->where('month', $month);
			$this->db->where('year', $year);
			$this->db->delete('erp_tax_payments');
			return true;
		}
	}
	
	function add_payment_tax($data = array())
    {
		if ($this->db->insert('erp_tax_payments', $data)) {
           return true;
        }
        return false;
    }
	
	function add_payment_tax_detail($data_d = array())
    {
		if ($this->db->insert_batch('erp_tax_payments_detail', $data_d)) {
           return true;
        }
        return false;
    }
	
	function getMaxTaxPaymentId(){
		$this->db->select("IFNULL(MAX(erp_tax_payments.id), 0) AS payment_id");
		$q =$this->db->get('erp_tax_payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	function tax_payments_view($month=null, $year=null, $cond=null, $previous=null){
		if($month && $year){	
			if($previous){
				$currDate = $year.'-'.$month.'-01' ;
				$this->db->where('erp_tax_payments.covreturn_start<', $currDate);
				
				//NOTE NOT YET//
				$this->db->where('erp_tax_payments_detail.main_tax_type', $cond);
				$this->db->select("erp_tax_payments_detail.ref_id,
									erp_tax_payments_detail.base,
									erp_tax_payments_detail.rate,
									erp_tax_payments_detail.tap_current_amount,
									erp_tax_payments_detail.tap_unpaid_amount,
									erp_tax_payments_detail.tap_amount_paid,
									erp_tax_payments_detail.pan_current_amount,
									erp_tax_payments_detail.pan_unpaid_amount,
									erp_tax_payments_detail.pan_amount_paid,
									erp_tax_payments_detail.total_balance,
									erp_tax_payments_detail.total_payment", false);
				
				$this->db->join("erp_tax_payments_detail","erp_tax_payments_detail.payment_id = erp_tax_payments.id");
				$q = $this->db->get('erp_tax_payments');
				//NOTE//
			}else{
				$this->db->where('erp_tax_payments.month', $month);
				$this->db->where('erp_tax_payments.year', $year);
				$this->db->where('erp_tax_payments_detail.main_tax_type', $cond);
				
				$this->db->select("erp_tax_payments_detail.ref_id,
									erp_tax_payments_detail.base,
									erp_tax_payments_detail.rate,
									erp_tax_payments_detail.tap_current_amount,
									erp_tax_payments_detail.tap_unpaid_amount,
									erp_tax_payments_detail.tap_amount_paid,
									erp_tax_payments_detail.pan_current_amount,
									erp_tax_payments_detail.pan_unpaid_amount,
									erp_tax_payments_detail.pan_amount_paid,
									erp_tax_payments_detail.total_balance,
									erp_tax_payments_detail.total_payment", false);
				
				$this->db->join("erp_tax_payments_detail","erp_tax_payments_detail.payment_id = erp_tax_payments.id");
				$q = $this->db->get('erp_tax_payments');
			}
			
			if($q->num_rows()>0){
				return $q->result();
			}
			return false;
		}
	}
	
	
	function getConfirmTax_purch($id){
	 $this->db->where('tax_type',$id);
	 $this->db->select("YEAR(erp_purchase_tax.issuedate) AS yearly,journal_date,journal_location, MONTH(erp_purchase_tax.issuedate) AS monthly,SUM(erp_purchase_tax.amount) AS amount, SUM(erp_purchase_tax.amount_tax) AS amount_tax,  
	 SUM(erp_purchase_tax.non_tax_pur) AS amount_non_tax_pur,
	 SUM(erp_purchase_tax.value_import) AS amount_value_import,
	 SUM(erp_purchase_tax.amount_declear) AS amount_dec,
	 SUM(erp_purchase_tax.amount_tax_declare) AS amount_tax_dec,

	 erp_purchase_tax.group_id", FALSE)
				
				// ->group_by('MONTH(erp_purchase_tax.issuedate), erp_purchase_tax.group_id');
				 ->group_by('MONTH(erp_purchase_tax.issuedate)');
	  $q =$this->db->get('erp_purchase_tax');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
	}
	
	function update_journal_date($data= array()){
	 $update=$this->db->update('sale_tax', array('journal_date' => $data['date']),array('MONTH(issuedate)' => $data['month'],'YEAR(issuedate)' => $data['year'],'tax_type' => $data['tax_type']));
	 if ($update) {
            return true;
        }
        return false;	
	}
	
	function update_journal_date_pur($data= array()){
	
	 $update=$this->db->update('purchase_tax', array('journal_date' => $data['date']),array('MONTH(issuedate)' => $data['month'],'YEAR(issuedate)' => $data['year'],'tax_type' => $data['tax_type']));
	 if ($update) {
            return true;
        }
        return false;	
	}
	
	function update_journal_loc_pur($data= array()){
	 $update=$this->db->update('purchase_tax', array('journal_location' => $data['location']),array('MONTH(issuedate)' => $data['month'],'YEAR(issuedate)' => $data['year'],'tax_type' => $data['tax_type']));
	 if ($update) {
            return true;
        }
        return false;	
	}
	
	
	function update_journal_loc($data= array()){
	 $update=$this->db->update('sale_tax', array('journal_location' => $data['location']),array('MONTH(issuedate)' => $data['month'],'YEAR(issuedate)' => $data['year'],'tax_type' => $data['tax_type']));
	 if ($update) {
            return true;
        }
        return false;	
	}
	
	function company_info($group_id=NULL){
		$this->db->select("company,vat_no,address,state,country,cf1,cf2,cf3,cf4")
	    ->from('companies')
		->where('id',$group_id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	
	function v_sale_journal_list($month=NULL,$year=NULL,$group_id=NULL,$tax_type=2){
	//$this->db->where('sale_tax.tax_type',$tax_type);	
    $this->db->
	         select($this->db->dbprefix('sale_tax') . ".customer_id,
				" . $this->db->dbprefix('sales') . ".reference_no as referent_no,
				" . $this->db->dbprefix('sale_tax') . ".group_id,
				" . $this->db->dbprefix('companies') . ".name as customer_name,
				" . $this->db->dbprefix('sale_tax') . ".vatin,
				" . $this->db->dbprefix('sale_tax') . ".description,
				" . $this->db->dbprefix('sale_tax') . ".qty,
				" . $this->db->dbprefix('sale_tax') . ".sale_id,
				" . $this->db->dbprefix('sale_tax') . ".non_tax_sale,
				" . $this->db->dbprefix('sale_tax') . ".value_export,
				" . $this->db->dbprefix('sale_tax') . ".tax_value,
				" . $this->db->dbprefix('sale_tax') . ".vat,
				" . $this->db->dbprefix('sales') .".note,
				" . $this->db->dbprefix('sales') .".created_by,
				" . $this->db->dbprefix('sales') .".total_items,
				" . $this->db->dbprefix('sales') .".customer,
				" . $this->db->dbprefix('sales') .".sale_type,
				" . $this->db->dbprefix('sales') .".revenues_type,
				" . $this->db->dbprefix('sale_tax') . ".tax_id, 
				" . $this->db->dbprefix('sale_tax') . ".amound_declare, 
				" . $this->db->dbprefix('sale_tax') . ".amound,
				" . $this->db->dbprefix('sale_tax') . ".amound_tax,
				" . $this->db->dbprefix('sale_tax') . ".journal_date,
				" . $this->db->dbprefix('sale_tax') . ".journal_location,
				" . $this->db->dbprefix('sale_tax') . ".amount_tax_declare,
				MONTH(" . $this->db->dbprefix('sale_tax') . ".issuedate) AS monthly,
				YEAR(" . $this->db->dbprefix('sale_tax') . ".issuedate) AS yearly,
				" . $this->db->dbprefix('sale_tax') . ".issuedate")
                ->from('sale_tax')
                ->join('sales', 'sale_tax.sale_id=sales.id','left')
                ->join('companies', 'sale_tax.customer_id=companies.id','left')
				//->where(array('MONTH ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$year,$this->db->dbprefix('sale_tax') .'.group_id='=>$group_id));
				->where(array('MONTH ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$year));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
		
            return $q;
        }
        return FALSE;
	}
	function small_v_sale_journal_list($month=NULL,$year=NULL,$group_id=NULL){
	$this->db->where('sale_tax.tax_type',1);	
    $this->db->
	         select($this->db->dbprefix('sale_tax') . ".customer_id,
				" . $this->db->dbprefix('companies') . ".id as biller_id,
				" . $this->db->dbprefix('sales') . ".reference_no as referent_no,
				" . $this->db->dbprefix('sale_tax') . ".group_id,
				" . $this->db->dbprefix('sale_tax') . ".vatin,
				" . $this->db->dbprefix('sale_tax') . ".description,
				" . $this->db->dbprefix('sale_tax') . ".qty,
				" . $this->db->dbprefix('sale_tax') . ".sale_id,
				" . $this->db->dbprefix('sale_tax') . ".non_tax_sale,
				" . $this->db->dbprefix('sale_tax') . ".value_export,
				" . $this->db->dbprefix('sale_tax') . ".tax_value,
				" . $this->db->dbprefix('sale_tax') . ".vat,
				" . $this->db->dbprefix('sales') .".note,
				" . $this->db->dbprefix('sales') .".created_by,
				" . $this->db->dbprefix('sales') .".total_items,
				" . $this->db->dbprefix('sales') .".customer,
				" . $this->db->dbprefix('sales') .".revenues_type,
				" . $this->db->dbprefix('sales') .".sale_type,
				" . $this->db->dbprefix('sale_tax') . ".tax_id, 
				" . $this->db->dbprefix('sale_tax') . ".amound_declare, 
				" . $this->db->dbprefix('sale_tax') . ".amound,
				" . $this->db->dbprefix('sale_tax') . ".amound_tax,
				" . $this->db->dbprefix('sale_tax') . ".journal_date,
				" . $this->db->dbprefix('sale_tax') . ".journal_location,
				" . $this->db->dbprefix('sale_tax') . ".amount_tax_declare,
				" . $this->db->dbprefix('sale_tax') . ".pns,
				" . $this->db->dbprefix('companies') . ".tax_description,
				" . $this->db->dbprefix('companies') . ".revenue_type,
				" . $this->db->dbprefix('revenues') . ".goods_and_service,
				" . $this->db->dbprefix('revenues') . ".revenue,
				" . $this->db->dbprefix('revenues') . ".description,
				MONTH(" . $this->db->dbprefix('sale_tax') . ".issuedate) AS monthly,
				YEAR(" . $this->db->dbprefix('sale_tax') . ".issuedate) AS yearly,
				" . $this->db->dbprefix('sale_tax') . ".issuedate")
                ->from('sale_tax')
                ->join('sales', 'sale_tax.sale_id=sales.id','left')
                ->join('revenues', 'sales.revenues_type=revenues.id')
                ->join('companies', 'companies.id=sales.biller_id')
				//->where(array('MONTH ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$year,$this->db->dbprefix('sale_tax') .'.group_id='=>$group_id));
				->where(array('MONTH ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$year));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
		
            return $q;
        }
        return FALSE;
	}

	function small_v_sale_journal_list_biller($month=NULL,$year=NULL,$group_id=NULL){
	$this->db->where('sale_tax.tax_type',1);	
    $this->db->
	         select($this->db->dbprefix('companies') . ".id as biller_id,
				" . $this->db->dbprefix('companies') . ".tax_description,
				" . $this->db->dbprefix('companies') . ".revenue_type,
				" . $this->db->dbprefix('sale_tax') . ".journal_date,
				" . $this->db->dbprefix('sale_tax') . ".journal_location,
				MONTH(" . $this->db->dbprefix('sale_tax') . ".issuedate) AS monthly,
				YEAR(" . $this->db->dbprefix('sale_tax') . ".issuedate) AS yearly,
				" . $this->db->dbprefix('sale_tax') . ".issuedate")
                ->from('sale_tax')
                ->join('sales', 'sale_tax.sale_id=sales.id','left')
                ->join('companies', 'companies.id=sales.biller_id')
				//->where(array('MONTH ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$year,$this->db->dbprefix('sale_tax') .'.group_id='=>$group_id));
				->group_by("biller_id")
				->where(array('MONTH ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$year));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
		
            return $q;
        }
        return FALSE;
	}
	
	function v_purch_journal_list($month=NULL,$year=NULL,$group_id=NULL,$tax_type=NULL) {
    $this->db->where('purchase_tax.tax_type',$tax_type);
	$this->db->
	         select($this->db->dbprefix('companies') . ".name as company_name,
				" . $this->db->dbprefix('purchasing_taxes') . ".invoice_no,
				" . $this->db->dbprefix('purchase_tax') . ".reference_no,
				" . $this->db->dbprefix('purchase_tax') . ".amount_declear, 
				" . $this->db->dbprefix('purchase_tax') . ".amount_tax_declare, 
				" . $this->db->dbprefix('purchase_tax') . ".vatin,
				" . $this->db->dbprefix('purchasing_taxes') . ".note as description,
				" . $this->db->dbprefix('purchase_tax') . ".qty,
				" . $this->db->dbprefix('purchase_tax') . ".non_tax_pur,
				" . $this->db->dbprefix('purchase_tax') . ".vat,
				" . $this->db->dbprefix('purchase_tax') . ".tax_value,
				" . $this->db->dbprefix('purchase_tax') . ".amount,
				" . $this->db->dbprefix('purchase_tax') . ".amount_tax,
				" . $this->db->dbprefix('purchases') . ".good_or_services,
				" . $this->db->dbprefix('purchase_tax') . ".purchase_type,
				" . $this->db->dbprefix('purchase_tax') . ".value_import,
				" . $this->db->dbprefix('purchase_tax') . ".journal_date,
				" . $this->db->dbprefix('purchase_tax') . ".journal_location,client.name,
				MONTH(" . $this->db->dbprefix('purchase_tax') . ".issuedate) AS monthly,
				YEAR(" . $this->db->dbprefix('purchase_tax') . ".issuedate) AS yearly,
				" . $this->db->dbprefix('purchase_tax') . ".issuedate")
                ->from('purchase_tax')
                ->join('purchasing_taxes', 'purchase_tax.reference_no=purchasing_taxes.reference_no')
                ->join('companies', 'purchase_tax.group_id=companies.id','left')
                ->join('companies as client', 'purchase_tax.supplier_id=client.id','left')
                ->join('purchases', 'purchase_tax.purchase_id=purchases.id','left')
				//->where(array('MONTH ('. $this->db->dbprefix('purchase_tax') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('purchase_tax') .'.issuedate)='=>$year,$this->db->dbprefix('purchase_tax') .'.group_id='=>$group_id));
				->where(array('MONTH ('. $this->db->dbprefix('purchase_tax') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('purchase_tax') .'.issuedate)='=>$year));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q;
        }
        return FALSE;
	}
	function small_v_purch_journal_list($month=NULL,$year=NULL,$group_id=NULL) {
    $this->db->where('purchase_tax.tax_type',1);
	$this->db->
	         select($this->db->dbprefix('companies') . ".name as company_name,
				" . $this->db->dbprefix('purchasing_taxes') . ".invoice_no,
				" . $this->db->dbprefix('purchasing_taxes') . ".reference_no,
				" . $this->db->dbprefix('purchasing_taxes') . ".amount_declear, 
				" . $this->db->dbprefix('purchasing_taxes') . ".amount_tax_declare, 
				" . $this->db->dbprefix('purchasing_taxes') . ".vatin,
				" . $this->db->dbprefix('purchasing_taxes') . ".note as description,
				" . $this->db->dbprefix('purchasing_taxes') . ".qty,
				" . $this->db->dbprefix('purchasing_taxes') . ".non_tax_pur,
				" . $this->db->dbprefix('purchasing_taxes') . ".vat,
				" . $this->db->dbprefix('purchasing_taxes') . ".tax_value,
				" . $this->db->dbprefix('purchasing_taxes') . ".amount,
				" . $this->db->dbprefix('purchasing_taxes') . ".amount_tax,
				" . $this->db->dbprefix('purchasing_taxes') . ".purchase_type,
				" . $this->db->dbprefix('purchasing_taxes') . ".pns,
				" . $this->db->dbprefix('purchasing_taxes') . ".value_import,
				" . $this->db->dbprefix('purchasing_taxes') . ".journal_date,
				" . $this->db->dbprefix('purchasing_taxes') . ".journal_location,client.name,
				MONTH(" . $this->db->dbprefix('purchasing_taxes') . ".issuedate) AS monthly,
				YEAR(" . $this->db->dbprefix('purchasing_taxes') . ".issuedate) AS yearly,
				" . $this->db->dbprefix('purchasing_taxes') . ".issuedate")
                ->from('purchasing_taxes')
                ->join('purchase_taxes', 'purchasing_taxes.reference_no=purchase_taxes.reference_no')
                ->join('companies', 'purchasing_taxes.group_id=companies.id','left')
                ->join('companies as client', 'purchasing_taxes.supplier_id=client.id')
				//->where(array('MONTH ('. $this->db->dbprefix('purchase_tax') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('purchase_tax') .'.issuedate)='=>$year,$this->db->dbprefix('purchase_tax') .'.group_id='=>$group_id));
				->where(array('MONTH ('. $this->db->dbprefix('purchasing_taxes') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('purchasing_taxes') .'.issuedate)='=>$year));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q;
        }
        return FALSE;
	}
	
	
	public function getSalaryTaxList()
	{
		$this->db->select(
							$this->db->dbprefix('salary_tax').'.id, '.
							$this->db->dbprefix('salary_tax').'.group_id, '.
							$this->db->dbprefix('companies').'.company, '.
							$this->db->dbprefix('salary_tax').'.month, '.
							$this->db->dbprefix('salary_tax').'.year, '.
							$this->db->dbprefix('salary_tax').'.covreturn_start, '.
							$this->db->dbprefix('salary_tax').'.covreturn_end, '.
							$this->db->dbprefix('salary_tax').'.created_date '
						 );
		$this->db->join('companies',$this->db->dbprefix('salary_tax').'.group_id = '.$this->db->dbprefix('companies').'.id','INNER');
		$this->db->group_by('salary_tax.id');
		$q = $this->db->get('salary_tax');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getSalaryTaxByID($id = NULL)
	{
		$this->db->select('companies.*,salary_tax.*');
		$this->db->join('companies',$this->db->dbprefix('salary_tax').'.group_id = '.$this->db->dbprefix('companies').'.id','INNER');
		$q = $this->db->get_where('salary_tax',array($this->db->dbprefix('salary_tax').'.id'=>$id));
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getSalaryTaxFrontByID($id=NULL, $type=NULL)
	{
		$q = $this->db->get_where('salary_tax_front',array($this->db->dbprefix('salary_tax_front').'.salary_tax_id'=>$id,$this->db->dbprefix('salary_tax_front').'.tax_type'=>$type));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getSalaryTaxBackByID($id=NULL, $type=NULL)
	{
		$this->db->select('salary_tax_back.*, users.first_name,users.last_name,users.username,users.nationality_kh');
		$this->db->join('users',$this->db->dbprefix('salary_tax_back').'.empcode = '.$this->db->dbprefix('users').'.id','INNER');
		$q = $this->db->get_where('salary_tax_back',array($this->db->dbprefix('salary_tax_back').'.salary_tax_id'=>$id,$this->db->dbprefix('salary_tax_back').'.tax_type'=>$type));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getWithholdingTaxList()
	{
		$this->db->select(
							$this->db->dbprefix('return_withholding_tax').'.id, '.
							$this->db->dbprefix('return_withholding_tax').'.group_id, '.
							$this->db->dbprefix('companies').'.company, '.
							$this->db->dbprefix('return_withholding_tax').'.month, '.
							$this->db->dbprefix('return_withholding_tax').'.year, '.
							$this->db->dbprefix('return_withholding_tax').'.covreturn_start, '.
							$this->db->dbprefix('return_withholding_tax').'.covreturn_end, '.
							$this->db->dbprefix('return_withholding_tax').'.created_date '
						 );
		$this->db->join('companies',$this->db->dbprefix('return_withholding_tax').'.group_id = '.$this->db->dbprefix('companies').'.id','INNER');
		$this->db->group_by('return_withholding_tax.id');
		$q = $this->db->get('return_withholding_tax');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getWithholdingTaxFrontByID($id=NULL, $type=NULL)
	{
		$q = $this->db->get_where('return_withholding_tax_front',array($this->db->dbprefix('return_withholding_tax_front').'.withholding_id'=>$id,$this->db->dbprefix('return_withholding_tax_front').'.type'=>$type));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getWithholdingTaxBackByID($id=NULL, $type=NULL)
	{
		$q = $this->db->get_where('return_withholding_tax_back',array($this->db->dbprefix('return_withholding_tax_back').'.withholding_id'=>$id,$this->db->dbprefix('return_withholding_tax_back').'.type'=>$type));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getValueAddTaxList()
	{
		$this->db->select(
							$this->db->dbprefix('return_value_added_tax').'.id, '.
							$this->db->dbprefix('return_value_added_tax').'.group_id, '.
							$this->db->dbprefix('companies').'.company, '.
							$this->db->dbprefix('return_value_added_tax').'.month, '.
							$this->db->dbprefix('return_value_added_tax').'.year, '.
							$this->db->dbprefix('return_value_added_tax').'.covreturn_start, '.
							$this->db->dbprefix('return_value_added_tax').'.covreturn_end, '.
							$this->db->dbprefix('return_value_added_tax').'.created_date '
						 );
		$this->db->join('companies',$this->db->dbprefix('return_value_added_tax').'.group_id = '.$this->db->dbprefix('companies').'.id','INNER');
		$this->db->group_by('return_value_added_tax.id');
		$q = $this->db->get('return_value_added_tax');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}

	public function getInfoFrontPage($id=NULL)
	{
		$this->db->select('return_value_added_tax.*,companies.*');
		$this->db->join('companies',$this->db->dbprefix('return_value_added_tax').'.group_id = '.$this->db->dbprefix('companies').'.id','INNER');
		$q = $this->db->get_where('return_value_added_tax',array($this->db->dbprefix('return_value_added_tax').'.id'=>$id));
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getInfoFrontPageWHT($id=NULL)
	{
		$this->db->select('return_withholding_tax.*,companies.*');
		$this->db->join('companies',$this->db->dbprefix('return_withholding_tax').'.group_id = '.$this->db->dbprefix('companies').'.id','INNER');
		$q = $this->db->get_where('return_withholding_tax',array($this->db->dbprefix('return_withholding_tax').'.id'=>$id));
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	
	
	public function getInfoBackPage($id=NULL, $type=NULL)
	{
		$this->db->select('return_value_added_tax_back.*,products.name, companies.name as supp_name');
		$this->db->join('products',$this->db->dbprefix('return_value_added_tax_back').'.productid = '.$this->db->dbprefix('products').'.code','INNER');
		$this->db->join('companies',$this->db->dbprefix('return_value_added_tax_back').'.supp_exp_inn = '.$this->db->dbprefix('companies').'.id','left');
		$q = $this->db->get_where('return_value_added_tax_back',array($this->db->dbprefix('return_value_added_tax_back').'.value_id'=>$id,$this->db->dbprefix('return_value_added_tax_back').'.type'=>$type));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	function getReturnTaxList()
	{
		$this->db->select(
							$this->db->dbprefix('return_tax_front').'.id, '.
							$this->db->dbprefix('return_tax_front').'.group_id, '.
							$this->db->dbprefix('companies').'.company, '.
							$this->db->dbprefix('return_tax_front').'.month, '.
							$this->db->dbprefix('return_tax_front').'.year, '.
							$this->db->dbprefix('return_tax_front').'.covreturn_start, '.
							$this->db->dbprefix('return_tax_front').'.covreturn_end, '.
							$this->db->dbprefix('return_tax_front').'.created_date '
						 );
		$this->db->join('companies',$this->db->dbprefix('return_tax_front').'.group_id = '.$this->db->dbprefix('companies').'.id','INNER');
		$this->db->group_by('return_tax_front.id');
		$q = $this->db->get('return_tax_front');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getReturnTaxFront($id=NULL)
	{
		$this->db->select('return_tax_front.*,companies.*');
		$this->db->join('companies',$this->db->dbprefix('return_tax_front').'.group_id = '.$this->db->dbprefix('companies').'.id','INNER');
		$q = $this->db->get_where('return_tax_front',array($this->db->dbprefix('return_tax_front').'.id'=>$id));
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getReturnTaxBack($id=NULL, $type=NULL)
	{
		$this->db->select('return_tax_back.*,products.name');
		$this->db->join('products',$this->db->dbprefix('return_tax_back').'.itemcode = '.$this->db->dbprefix('products').'.code','INNER');
		$q = $this->db->get_where('return_tax_back',array($this->db->dbprefix('return_tax_back').'.tax_return_id'=>$id,$this->db->dbprefix('return_tax_back').'.type'=>$type));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	function delete_value_add_tax($id){
		$this->db->where('id', $id);
		if($this->db->delete('erp_return_value_added_tax')){
			$this->db->where('value_id', $id);
			$this->db->delete('erp_return_value_added_tax_back');
			return true;
		}		
	}
	function salary_tax_delete($id){
		$this->db->where('id', $id);
		if($this->db->delete('erp_salary_tax')){
			$this->db->where('salary_tax_id', $id);
			$this->db->delete('erp_salary_tax_back');
			$this->db->where('salary_tax_id', $id);
			$this->db->delete('erp_salary_tax_front');
			return true;
		}		
	}
	
	function prepayment_profit_tax_delete($id){
		$this->db->where('id', $id);
		if($this->db->delete('erp_return_tax_front')){
			$this->db->where('tax_return_id', $id);
			$this->db->delete('erp_return_tax_back');
			return true;
		}		
	}
	
	function withholding_tax_report_delete($id,$reference_no){
		$this->db->where('id', $id);
		if($this->db->delete('erp_return_withholding_tax')){
			$this->db->where('withholding_id', $id);
			$this->db->delete('erp_return_withholding_tax_back');
			
			$this->db->where('withholding_id', $id);
			$this->db->delete('erp_return_withholding_tax_front');
			
			$this->db->where('reference_no', $reference_no);
			$this->db->delete('gl_trans');
			
			return true;
		}		
	}
	function getWH_Tax($id){
		$this->db->where('id',$id);
		$q=$this->db->get('erp_return_withholding_tax');
		if($q){
			return $q->row();
		}return false;
	}
	public function getShopInfo($id=NULL)
	{
		$this->db->select('companies.*');
		$this->db->from('settings');
		$this->db->join('companies','settings.default_biller = companies.id');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	function get_set_forward($date){
		$this->db->select('gl_trans.*, ');
		$this->db->where('gl_trans.account_code',100442);
		$this->db->where('gl_trans.tran_date<',$date);
		$this->db->where('gl_charts_tax.account_tax_id',66);
		$this->db->from('gl_trans');
		$this->db->join('taxation_type_of_account', 'taxation_type_of_account.account_code = gl_trans.account_code');
		$this->db->join('gl_charts_tax', 'gl_charts_tax.account_tax_id = taxation_type_of_account.account_tax_code');
		
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q;
        }
        return FALSE;
	}
	function sum_input($date_time) {
    $this->db->select('SUM(amount_tax) as sum_input');
    $this->db->where('issuedate<',$date_time);
	$this->db->from('purchase_tax');
	
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	function sum_output($date_time) {
    $this->db->select('SUM(amount_tax_declare) as sum_output');
    $this->db->where('issuedate<',$date_time);
	$this->db->from('sale_tax');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	/* Employee Salary Tax */
	
	public function getEmployeeSalaryTaxes($emp_id, $sub_where=''){
		
		if($sub_where!=''){
			$sub_where = str_replace('"', "'", $sub_where);
		}
		$sql = "SELECT
					
					emp_tax.employee_id,
					CONCAT(
						u.first_name,
						' ',
						u.last_name
					) AS fullname,
					u.nationality,
					u.gender,
					emp_tax.position,
					u.employeed_date,
					emp_tax.date_insert AS date,
					emp_tax.basic_salary AS basic_salary,
					emp_tax.amount_usd AS salary_tax,
					(emp_tax.amount_usd * tx.salary_khm) AS salary_tax_to_be_paid,
					emp_tax.spouse,
					emp_tax.minor_children,
					emp_tax.trigger_id,
					emp_trigger.isCompany AS isCompany,
					COALESCE(tx.salary_khm, 0) AS khm_rate,
					emp_tax.remark,
					emp_tax.allowance,
					emp_tax.allowance_tax
				FROM
					erp_employee_salary_tax emp_tax
				LEFT JOIN erp_users u ON u.id = emp_tax.employee_id
				LEFT JOIN erp_employee_salary_tax_trigger emp_trigger ON emp_trigger.id = emp_tax.trigger_id
				LEFT JOIN erp_tax_exchange_rate tx ON DATE_FORMAT(
					CONCAT(tx. YEAR, '-', tx. MONTH, '-01'),
					'%Y-%m'
				) = DATE_FORMAT(
					emp_tax.date_insert,
					'%Y-%m'
				)
				WHERE emp_tax.employee_id = '".$emp_id."' ".$sub_where." ";
			
				//echo $sql;
		$query = $this->db->query($sql);
		foreach($query->result() AS $row){
			$data[] = $row;
		}
		return $data;
	}
	
	//new emp header//
	public function getEmployeeSalaryTaxesHeader($employee_id = NULL,$year,$month){
		$where = '';
		$sub_where = '';
		if($employee_id){
			$where .= " AND employee_id = '".$employee_id."'";
		}
		if($month){
			if($month!='all'){
				$where .= " AND MONTH(date_insert) = '".$month."'";
				$sub_where .= ' AND MONTH(date_insert) = "'.$month.'"';
			}
		}
		if($year){
			$where .= " AND YEAR(date_insert)='".$year."'";
			$sub_where .= ' AND YEAR(date_insert)="'.$year.'"';
		}
		
		
		$where.=' AND emp_tax.declare_tax=1 ';
		
		$sql = "SELECT
					'".$sub_where."' AS sub_where,
					emp_tax.employee_id,
					CONCAT(
						u.first_name,
						' ',
						u.last_name
					) AS fullname,
					u.nationality,
					u.gender,
					emp_tax.position,
					u.employeed_date,
					emp_tax.date_insert AS date,
					emp_tax.basic_salary AS basic_salary,
					emp_tax.amount_usd AS salary_tax,
					(emp_tax.amount_usd * tx.salary_khm) AS salary_tax_to_be_paid,
					emp_tax.spouse,
					emp_tax.minor_children,
					emp_tax.trigger_id,
					emp_trigger.isCompany AS isCompany,
					COALESCE(tx.salary_khm, 0) AS khm_rate,
					emp_tax.remark
				FROM
					erp_employee_salary_tax emp_tax
				LEFT JOIN erp_users u ON u.id = emp_tax.employee_id
				LEFT JOIN erp_employee_salary_tax_trigger emp_trigger ON emp_trigger.id = emp_tax.trigger_id
				LEFT JOIN erp_tax_exchange_rate tx ON DATE_FORMAT(
					CONCAT(tx. YEAR, '-', tx. MONTH, '-01'),
					'%Y-%m'
				) = DATE_FORMAT(
					emp_tax.date_insert,
					'%Y-%m'
				)
				WHERE 1=1 
				".$where."
				GROUP BY emp_tax.employee_id ";
				
				//echo $sql."<br>";
		$query = $this->db->query($sql);
		foreach($query->result() AS $row){
			$data[] = $row;
		}
		return $data;
	}
	
	//get employee tax salary list header//
	public function getEmployeeSalaryTaxListHeader($month, $year){
		$data=null;
		$where = '';
		$sub_where = '';
		if($month){
			if($month!='all'){
				$where .= " AND MONTH(date_insert) = '".$month."'";
				$sub_where .= ' AND MONTH(date_insert) = "'.$month.'"';
			}
		}
		if($year){
			$where .= " AND YEAR(date_insert)='".$year."'";
			$sub_where .= ' AND YEAR(date_insert)="'.$year.'"';
		}
		
		$sql = "SELECT
					MONTH(date_insert) as month_loop,
					emp_tax.employee_id,
					emp_tax.date_print,
					emp_tax.date_insert AS date,
					emp_tax.amount_usd AS salary_tax,
					(
						emp_tax.amount_usd * tx.salary_khm
					) AS salary_tax_to_be_paid,
					emp_tax.spouse,
					emp_tax.minor_children,
					COALESCE (tx.salary_khm, 0) AS khm_rate,
					emp_tax.remark,
					emp_tax.location,
					emp_tax.date_print
				FROM
					erp_employee_salary_tax emp_tax
				LEFT JOIN erp_users u ON u.id = emp_tax.employee_id
				LEFT JOIN erp_employee_salary_tax_trigger emp_trigger ON emp_trigger.id = emp_tax.trigger_id
				LEFT JOIN erp_tax_exchange_rate tx ON DATE_FORMAT(
					CONCAT(tx. YEAR, '-', tx. MONTH, '-01'),
					'%Y-%m'
				) = DATE_FORMAT(
					emp_tax.date_insert,
					'%Y-%m'
				)
				WHERE 1=1 ".$where."
				GROUP BY MONTH (date_insert) ";
				 //echo $sql;
		$query = $this->db->query($sql);
		foreach($query->result() AS $row){
			$data[] = $row;
		}
		return $data;
	}
	//list detail//
	public function getEmployeeSalaryTaxListDetails($month, $year){
		$where = '';
		$sub_where = '';
		if($month){
			if($month!='all'){
				$where .= " AND MONTH(date_insert) = '".$month."'";
				$sub_where .= ' AND MONTH(date_insert) = "'.$month.'"';
			}
		}
		if($year){
			$where .= " AND YEAR(date_insert)='".$year."'";
			$sub_where .= ' AND YEAR(date_insert)="'.$year.'"';
		}
		
		$sql = "SELECT
					CONCAT(
						u.first_name,
						' ',
						u.last_name
					) AS fullname,
					u.nationality,
					u.gender,
					emp_tax.position,
					u.employeed_date,
					emp_tax.basic_salary AS basic_salary,
					emp_tax.trigger_id,
					emp_trigger.isCompany AS isCompany,
					emp_tax.employee_id,
					emp_tax.date_insert AS date,
					emp_tax.amount_usd AS salary_tax,
					(
						emp_tax.amount_usd * tx.salary_khm
					) AS salary_tax_to_be_paid,
					emp_tax.spouse,
					emp_tax.minor_children,
					COALESCE (tx.salary_khm, 0) AS khm_rate,
					emp_tax.remark,
					emp_tax.location,
					emp_tax.date_print
				FROM
					erp_employee_salary_tax emp_tax
				LEFT JOIN erp_users u ON u.id = emp_tax.employee_id
				LEFT JOIN erp_employee_salary_tax_trigger emp_trigger ON emp_trigger.id = emp_tax.trigger_id
				LEFT JOIN erp_tax_exchange_rate tx ON DATE_FORMAT(
					CONCAT(tx. YEAR, '-', tx. MONTH, '-01'),
					'%Y-%m'
				) = DATE_FORMAT(
					emp_tax.date_insert,
					'%Y-%m'
				)
				WHERE 1=1 ".$where." ";
				//echo $sql."<br>";
		$query = $this->db->query($sql);
		foreach($query->result() AS $row){
			$data[] = $row;
		}
		return $data;
	}
	
	//update location tax salary list//
	function updateLocation($month=null, $year=null, $curent_location=null, $date_print=null){
		if($month && $year){
			$data = array(
						   'location' => $curent_location,
						   'date_print' => $date_print
						);
			$this->db->where('MONTH(date_insert)=', $month);
			$this->db->where('YEAR(date_insert)=', $year);
			$this->db->update('erp_employee_salary_tax', $data);
			return true;
		}
	}
	
	public function getEmployeeSalaryTaxExport($date = NULL, $isCompany = NULL, $employee_id = NULL){
		$this->db->select("
					emp_tax.employee_id,
					CONCAT(
						u.first_name,
						' ',
						u.last_name
					) AS fullname,
					u.nationality,
					u.gender,
					emp_tax.position,
					u.employeed_date,
					emp_tax.date_insert AS date_insert,
					emp_tax.basic_salary AS basic_salary,
					emp_tax.amount_usd AS salary_tax,
					(emp_tax.amount_usd * tx.salary_khm) AS salary_tax_to_be_paid,
					emp_tax.spouse,
					emp_tax.minor_children,
					emp_tax.trigger_id,
					emp_trigger.isCompany AS isCompany,
					COALESCE(tx.salary_khm, 0) AS khm_rate");
		$this->db->from("employee_salary_tax as emp_tax");
		$this->db->join('users as u', 'u.id = emp_tax.employee_id', 'left');
		$this->db->join('employee_salary_tax_trigger as emp_trigger', 'emp_trigger.id = emp_tax.trigger_id', 'left');
		$this->db->join("tax_exchange_rate as tx", "DATE_FORMAT(CONCAT(tx. YEAR, '-', tx. MONTH, '-01'),'%Y-%m') = DATE_FORMAT(emp_tax.date_insert,'%Y-%m')", "left");
		
		if($date){
			$this->db->where('DATE_FORMAT(emp_tax.date_insert, "%Y-%m") = ', $date);
		}
		
		if($isCompany){
			$this->db->where('emp_trigger.isCompany', $isCompany);
		}
		
		if($employee_id){
			$this->db->where('emp_tax.employee_id', $employee_id);
		}
		
		$query = $this->db->get();
		foreach($query->result() AS $row){
			$data[] = $row;
		}
		return $data;
	}
	
	public function getTaxExchangeRateByMY($month = NULL, $year = NULL){
		$this->db->where('month', $month)
					->where('year', $year);
		$this->db->limit(0,1);
		$query = $this->db->get('tax_exchange_rate');
		if($query->num_rows() > 0){
			return $query->row();
		}
		return false;
	}
	
	#############salary_tax#############
	
	public function getSalaryTax_Small(){
		$data=null;
		$sql = "SELECT
				id,
				MONTH (`year_month`) AS monthly,
				YEAR (`year_month`) AS yearly,
				COALESCE (total_salary_usd, 0) AS salary_usd,
				COALESCE (total_salary_tax_usd, 0) AS salary_tax,
				COALESCE (
					total_salary_tax_cal_base_riel,
					0
				) AS salary_tax_cal_base,
				COALESCE (total_salary_tax_riel, 0) AS salary_tax_riel,
				location,
				date_print
			FROM
				erp_employee_salary_tax_small_taxpayers_trigger
			GROUP BY 
				MONTH (`year_month`) ,
				YEAR (`year_month`) ";
		$query = $this->db->query($sql);
		foreach($query->result() AS $row){
			$data[] = $row;
		}
		return $data;
	}
	
	
	function update_salary_list_date($data= array()){
	
	 $update=$this->db->update('erp_employee_salary_tax_small_taxpayers_trigger', array('date_print' => $data['date']),array('MONTH(`year_month`)' => $data['month'],'YEAR(`year_month`)' => $data['year']));
	 if ($update) {
            return true;
        }
        return false;	
	}
	function update_salary_list_date_m($data= array()){
	
	 $update=$this->db->update('erp_employee_salary_tax_trigger', array('date_print' => $data['date']),array('year_month' => $data['year_month'],'tab'=>1));
	 if ($update) {
            return true;
        }
        return false;	
	}
	function update_salary_list_loc($data= array()){
	 $update=$this->db->update('erp_employee_salary_tax_trigger', array('location' => $data['location']),array('year_month' => $data['year_month'],'tab'=>1));
	 if ($update) {
            return true;
        }
        return false;	
	}
	
	function salary_tax_list_small($month=NULL,$year=NULL,$group_id=NULL)
	{
		$this->db->select("*")
				 ->from('erp_employee_salary_tax_small_taxpayers')
				 ->join('erp_users','erp_users.id = erp_employee_salary_tax_small_taxpayers.employee_id','left')
				 ->where(array('MONTH (erp_employee_salary_tax_small_taxpayers.date_insert)='=>$month,'YEAR (erp_employee_salary_tax_small_taxpayers.date_insert)='=>$year))
				->where('declare_tax',1);
			$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;				
	}
	
	function salary_location($month=NULL,$year=NULL){
		$this->db->select("date_print,location")
		 ->from('erp_employee_salary_tax_small_taxpayers_trigger')
		 ->where(array('MONTH (erp_employee_salary_tax_small_taxpayers_trigger.date_print)='=>$month,'YEAR (erp_employee_salary_tax_small_taxpayers_trigger.date_print)='=>$year));
	$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	function salary_location_m($month=NULL,$year=NULL){
		$this->db->select("date_print,location")
		 ->from('erp_employee_salary_tax_trigger')
		 ->where(array('MONTH (erp_employee_salary_tax_trigger.date_print)='=>$month,'YEAR (erp_employee_salary_tax_trigger.date_print)='=>$year));
	$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	function getGLChartsByCode($code){
		$this->db->where('accountcode',$code);
		$q=$this->db->get('gl_charts');
		if($q->num_rows() > 0){
			 return $q->row();
		}
	}
	function insertGLTrans($data){
		$this->db->insert('gl_trans', $data);
	}
	function getMaxTranNo(){
		$this->db->select('MAX(tran_no) as tran_no');
		$q=$this->db->get('gl_trans');
		if($q->num_rows() > 0){
			 return $q->row();
		}
	}
	
	function update_ref_tr($data){
		if ($this->site->getReference('tr') == $data['reference_no']) {
					$this->site->updateReference('tr');
				}
	}
	function getMrevenues(){
		$this->db->where('tax_type','medium_taxpayers');
		$q=$this->db->get('revenues');
		if($q->num_rows() > 0){
			 return $q->result();
		}
	}
	function getLrevenues(){
		$this->db->where('tax_type','large_taxpayers');
		$q=$this->db->get('revenues');
		if($q->num_rows() > 0){
			 return $q->result();
		}
	}
	function getSrevenues(){
		$this->db->where('tax_type','small_taxpayers');
		$q=$this->db->get('revenues');
		if($q->num_rows() > 0){
			 return $q->result();
		}
	}
	function getPurByRef($ref){
		$this->db->where('reference_no',$ref);
		$r=$this->db->get('purchases');
		if($r){
			return $r->row;
		}else{return false;}
	}
	
	public function getSalaryTax_List(){
		$data=null;
		$sql = "SELECT
				id,
				MONTH (`year_month`) AS monthly,
				YEAR (`year_month`) AS yearly,
				`year_month`,
				COALESCE (total_salary_usd, 0) AS salary_usd,
				COALESCE (total_salary_tax_usd, 0) AS salary_tax,
				COALESCE (
					total_salary_tax_cal_base_riel,
					0
				) AS salary_tax_cal_base,
				COALESCE (total_salary_tax_riel, 0) AS salary_tax_riel,
				location,
				date_print,
				(select total_salary_usd from erp_employee_salary_tax_trigger as tab2
				
				where tab2.year_month=erp_employee_salary_tax_trigger.year_month and tab2.tab=2) as amount_usd_t2
			FROM
				erp_employee_salary_tax_trigger
			WHERE tab=1		
			GROUP BY 
				MONTH (`year_month`) ,
				YEAR (`year_month`) 
			
				";
		$query = $this->db->query($sql);
		foreach($query->result() AS $row){
			$data[] = $row;
		}
		return $data;
	}
	public function get_salary_list_view_form($date){
		return null;
	}
}