<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Taxes_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function saveWithholdingdTax($WHT = array(), $WTR = array(), $WTNR = array(),$DWTR = array(),$DWTNR = array(),$st_month) {
		if($WHT) {
			$chkmodifyid = $this->chkMonthWithholding($WHT['year'],$st_month);
		if($chkmodifyid){
				$old_ref_no =	$chkmodifyid->reference_no;
				if($old_ref_no){
					$this->db->delete('gl_trans',array('reference_no'=>$old_ref_no));
				}
				
				$this->db->delete('return_withholding_tax',array('year'=>$WHT['year'],'month'=>$WHT['month']));
				$WHT['reference_no']=$old_ref_no;
		}		
			
			$i=$this->db->insert('return_withholding_tax', $WHT);
			$return_id = $this->db->insert_id();
			if ($i) {
				$this->update_ref_tr($WHT);
				foreach ($WTR as $WTRData) {
					$WTRData['withholding_id'] = $return_id;
					$this->db->insert('return_withholding_tax_front', $WTRData);
				}
				foreach ($WTNR as $WTNRData) {
					$WTNRData['withholding_id'] = $return_id;
					$this->db->insert('return_withholding_tax_front', $WTNRData);
				}
				foreach ($DWTR as $DWTRData) {
					$DWTRData['withholding_id'] = $return_id;
					$this->db->insert('return_withholding_tax_back', $DWTRData);
				}
				foreach ($DWTNR as $DWTNRData) {
					$DWTNRData['withholding_id'] = $return_id;
					$this->db->insert('return_withholding_tax_back', $DWTNRData);
				}
				return true;
			}
			return false;
		}
		return false;
	}
	
	
	public function saveValueAddedTax($VAT = array(), $SPNSM = array(), $GSE = array(),$GSLS = array(),$st_mm) {
		if($VAT) {
			$chkmodifyid = $this->chkMonthVAT($VAT['year'],$st_mm,$VAT['state_change']);
			if($chkmodifyid){	
					$reference_no=$chkmodifyid->reference_no;
					$this->db->where('reference_no',$reference_no);
					$this->db->delete('gl_trans');
					$this->db->delete('return_value_added_tax',array('year'=>$VAT['year'],'month'=>$VAT['month'],'state_change'=>$VAT['state_change']));
					$VAT['reference_no']=$reference_no;
			}
			if ($this->site->getReference('tr') == $VAT['reference_no']) {
				$this->site->updateReference('tr');
			}
				if ($this->db->insert('return_value_added_tax', $VAT)) {
					$return_id = $this->db->insert_id();
					foreach ($SPNSM as $spData) {
						$spData['value_id'] = $return_id;
						$this->db->insert('return_value_added_tax_back', $spData);
					}
					foreach ($GSE as $gsData) {
						$gsData['value_id'] = $return_id;
						$this->db->insert('return_value_added_tax_back', $gsData);
					}
					foreach ($GSLS as $gslData) {
						$gslData['value_id'] = $return_id;
						$this->db->insert('return_value_added_tax_back', $gslData);
					}
					return true;
				}
			
			return false;
		}
		return false;
	}
	

	public function SelectEnterprise()
	{
		$q = $this->db->get_where('companies', array('group_id' => NULL));
         if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getAllUsers() {
		$q = $this->db->get('users');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
	}
	
	public function getEmployeeByID($id = NULL)
	{
		$this->db->select('users.id, users.username, users.first_name_kh, users.last_name_kh, users.nationality_kh, pack_lists.description');
        $this->db->join('pack_lists', 'pack_lists.id = users.pack_id', 'inner');
        $q = $this->db->get_where('users', array('users.id' => $id));
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function addSalaryTax($salary_tax = array(),$RE = array(),$NRE = array(),$FB = array(),$REB = array(),$FBB = array())
	{
		$help = false;
		if($salary_tax) {
			if($this->db->insert('salary_tax', $salary_tax)) {
				$salary_tax_id = $this->db->insert_id();
				if($RE) {
					foreach($RE as $ore) {
						$ore['salary_tax_id'] = $salary_tax_id;
						if($this->db->insert('salary_tax_front', $ore)){
							$help = true;
						}
					}
				}
				if($NRE) {
					$NRE['salary_tax_id'] = $salary_tax_id;
					if($this->db->insert('salary_tax_front', $NRE)){
						$help = true;
					}
				}
				if($FB) {
					$FB['salary_tax_id'] = $salary_tax_id;
					if($this->db->insert('salary_tax_front', $FB)){
						$help = true;
					}
				}
				if($REB) {
					foreach($REB as $oreb) {
						$oreb['salary_tax_id'] = $salary_tax_id;
						if($this->db->insert('salary_tax_back', $oreb)){
							$help = true;
						}
					}
				}
				if($FBB) {
					foreach($FBB as $ofbb){
						$ofbb['salary_tax_id'] = $salary_tax_id;
						if($this->db->insert('salary_tax_back', $ofbb)){
							$help = true;
						}
					}
				}
				$help = true;
			}
		}
		if($help) {
			return true;
		}else{
			return false;
		}
	}
	
	public function editSalaryTax($id = NULL,$salary_tax = array(),$RE = array(),$NRE = array(),$FB = array(),$REB = array(),$FBB = array())
	{
		$help = false;
		if($salary_tax) {
			if($this->db->update('salary_tax', $salary_tax, array('id' => $id))) {
				$this->db->delete('salary_tax_front', array('salary_tax_id' => $id));
				$this->db->delete('salary_tax_back', array('salary_tax_id' => $id));
				if($RE) {
					foreach($RE as $ore) {
						$ore['salary_tax_id'] = $id;
						if($this->db->insert('salary_tax_front', $ore)){
							$help = true;
						}
					}
				}
				if($NRE) {
					$NRE['salary_tax_id'] = $id;
					if($this->db->insert('salary_tax_front', $NRE)){
						$help = true;
					}
				}
				if($FB) {
					$FB['salary_tax_id'] = $id;
					if($this->db->insert('salary_tax_front', $FB)){
						$help = true;
					}
				}
				if($REB) {
					foreach($REB as $oreb) {
						$oreb['salary_tax_id'] = $id;
						if($this->db->insert('salary_tax_back', $oreb)){
							$help = true;
						}
					}
				}
				if($FBB) {
					foreach($FBB as $ofbb){
						$ofbb['salary_tax_id'] = $id;
						if($this->db->insert('salary_tax_back', $ofbb)){
							$help = true;
						}
					}
				}
				$help = true;
			}
		}
		if($help) {
			return true;
		}else{
			return false;
		}
	}
		
	public function getEnterpriceByID($id = NULL)
	{
		$q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getAllProducts()
    {
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
	
	public function SupplierList(){
		$q = $this->db->get_where('companies', array('group_name' => 'supplier'));
         if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function SelectEnterpriseId($id)
	{
		$q = $this->db->get_where('companies', array('group_id' => NULL,'id'=>$id));
         if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	
	public function getPurchaseTaxes($arr_id)
    {	
		$type = '';
		$id = '';
		foreach($arr_id as $idd){
			$ids = explode('__', $idd);
			$type = $ids[1];
			$id[] = $ids[0];
		}
		
		$this->db->select("
			purchases.id as id, 
			purchases.date, 
			purchases.reference_no, 
			purchases.note, 
			(total-order_discount+shipping) as amount, 
			order_tax, 
			(total-order_discount+shipping) as balance, 
			purchases.supplier_id, 
			'' as remark,
			1 as remark_id, 
			purchase_tax.amount_tax_declare as vat_declare,  
			purchase_tax.amount_declear, 
			purchase_tax.type as ptype");
		$this->db->from('purchases');
		$this->db->join('purchase_tax', 'purchase_tax.purchase_id = purchases.id','left');
		$this->db->where_in('purchases.id', $id);
		$this->db->order_by('purchases.date','desc');
        $q = $this->db->get();
         if ($q->num_rows() > 0) {
            return $q->result();
        }
		return FALSE;
    }
	
	public function getExchangeTaxRate($month,$year) {
		$this->db->where('month', $month);
		$this->db->where('year', $year);
		$this->db->select('average_khm as rate, salary_khm as salary_rate');
		$this->db->from('tax_exchange_rate');
        $q = $this->db->get();
         if ($q->num_rows() > 0) {
            return $q->row();
        }
		return FALSE;
	}
	public function getExchangeRate($id) {
		$this->db->select('rate');
		$this->db->from('currencies');
		$this->db->where('code', $id);
        $q = $this->db->get();
         if ($q->num_rows() > 0) {
            return $q->row();
        }
		return FALSE;
	}
	public function addPurchasingTax($data = array(), $addPurchasingTax = NULL) {
		if($this->db->insert('purchase_tax', $data)) {
			$this->db->update('purchases', array(
												'tax_type' => $addPurchasingTax,
												'reference_no_tax' => $data['reference_no'], 
												'tax_status' => 'confirmed'
												),array('id' => $data['purchase_id']));
			return true;
		}
		return false;
	}
	
	/*Add Sale Tax Model*/
	public function getCombineTaxById($id)
    {
		$this->db->select('sales.id, sales.date,sales.note, sales.reference_no, sales.biller, sales.customer,
		sales.customer_id,sales.total,sales.order_discount,sales.order_tax,sales.shipping,sales.total_tax,
		sales.warehouse_id,sales.order_tax,sales.order_tax_id, sales.sale_status,sales.grand_total,
		(erp_sales.grand_total-erp_sales.total_tax) as balance,sales.sale_type,
		sale_tax.amound_declare as amount_declare,
		sale_tax.amount_tax_declare as vat_declare');
		$this->db->from('sales');
		$this->db->join('sale_tax', 'sale_tax.sale_id = sales.id','left');
		$this->db->where_in('id', $id);
        $q = $this->db->get();
         if ($q->num_rows() > 0) {
            return $q;
        }
		return FALSE;
    }
	
	 public function addTax($data = array())
    {
        if ($this->db->insert('sale_tax', $data)) {
			$this->db->update('sales', array('reference_no_tax' => $data['referent_no'],'tax_status'=>'confirmed'), array('id' => $data['sale_id']));
			
            return true;
        }
        return false;
    }
	/*End Add Sale Tax*/
	public function getConditionTax(){
		$this->db->where('id','1');
		$q=$this->db->get('condition_tax');
		return $q->result();
	}
	public function getConditionTaxById($id){
		$this->db->where('id',$id);
		$q=$this->db->get('condition_tax');
		return $q->row();
	}
	public function update_exchange_tax_rate($id,$data){
		$this->db->where('id',$id);
		$update=$this->db->update('condition_tax',$data);
		if($update){
			return true;
		}
	}
	public function chkMonthWithholding($year=NULL,$month=NULL){
		$this->db->select("reference_no");
		$this->db->from("return_withholding_tax");
		$this->db->where(array('`year`'=>$year,'`month`'=>$month));
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->row();	
		}
		return false;
	}
	public function chkMonthVAT($year=NULL,$month=NULL,$state_change=NULL){
		$this->db->select("id,reference_no");
		$this->db->from("erp_return_value_added_tax");
		$this->db->where(array('`year`'=>$year,'`month`'=>$month,'`state_change`'=>$state_change));
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->row();	
		}
		return false;
	}
	public function chkMonth($year=NULL,$month=NULL,$tax_type=NULL){
		$this->db->select("id,reference_no");
		$this->db->from("erp_return_tax_front");
		$this->db->where(array('`year`'=>$year,'`month`'=>$month,'`tax_type`'=>$tax_type));
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->row();	
		}
		return false;
	}
	function update_ref_tr($data){
		if ($this->site->getReference('tr') == $data['reference_no']) {
					$this->site->updateReference('tr');
				}
	}
	public function addReturnTax($return_tax = array(), $SGP = array(), $SS = array()) {
	  if($return_tax) {
		  
			$chkmodifyid = $this->chkMonth($return_tax['year'],$return_tax['month'],$return_tax['tax_type']);
			
			$old_ref=$chkmodifyid->reference_no;
		if($chkmodifyid){
				$this->db->where('reference_no',$old_ref);
				$this->db->delete('gl_trans');
				$return_tax['reference_no']=$old_ref;
				$this->update_ref_tr($return_tax);
				$this->db->delete('return_tax_front',array('year'=>$return_tax['year'],'month'=>$return_tax['month'],'tax_type'=>$return_tax['tax_type']));
				if ($this->db->insert('return_tax_front', $return_tax)) {
				$return_id = $this->db->insert_id();
					foreach ($SGP as $osgb) {
						$osgb['tax_return_id'] = $return_id;
						$this->db->delete('return_tax_back',array('tax_return_id'=>$chkmodifyid->id,'type'=>'SGP'));
						$this->db->insert('return_tax_back', $osgb);
					}
					foreach ($SS as $oss) {
						$oss['tax_return_id'] = $return_id;
						$this->db->delete('return_tax_back',array('tax_return_id'=>$chkmodifyid->id,'type'=>'SS'));
						$this->db->insert('return_tax_back', $oss);
					}
					return true;
				}
				return false;
		}else{
			$this->update_ref_tr($return_tax);
			if ($this->db->insert('return_tax_front', $return_tax)) {
				$return_id = $this->db->insert_id();
				foreach ($SGP as $osgb) {
					$osgb['tax_return_id'] = $return_id;
					$this->db->insert('return_tax_back', $osgb);
				}
				foreach ($SS as $oss) {
					$oss['tax_return_id'] = $return_id;
					$this->db->insert('return_tax_back', $oss);
				}
				return true;
			}
			return false;
		  }
		}
		return false;
	}	
	
	public function getAccountSections(){
		$this->db->select("sectionid,sectionname");
		$section = $this->db->get("gl_sections");
		if($section->num_rows() > 0){
			return $section->result_array();	
		}
		return false;
	}
	
	public function addChartAccount($data){
		if ($this->db->insert('gl_charts_tax', $data)) {
            return true;
        }
        return false;
	}
	
	public function updateChartAccount($id,$data){
		//$this->erp->print_arrays($data);
		$this->db->where('accountcode', $id);
		$q=$this->db->update('gl_charts_tax', $data);
        if ($q) {
            return true;
        }
        return false;
	}
	
	public function deleteChartAccount($id){
		$q = $this->db->delete('gl_charts_tax', array('accountcode' => $id));
		if($q){
			return true;
		} else{
			return false;
		}
	}
	
	public function getChartAccountByID($id){
		$this->db->select('gl_charts_tax.accountcode,gl_charts_tax.accountname,gl_charts_tax.accountname_kh,gl_charts_tax.sectionid,gl_sections.sectionname');
		$this->db->from('gl_charts_tax');
		$this->db->join('gl_sections', 'gl_sections.sectionid=gl_charts_tax.sectionid','INNER');
		$this->db->where('gl_charts_tax.accountcode' , $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getCombineTaxByIdForEdit($id)
    {
		$this->db->select('sales.id, sales.date, sales.reference_no, sales.biller, sales.customer,sales.total_tax,sales.warehouse_id,sales.order_tax,sales.order_tax_id, sales.sale_status,sales.grand_total,(erp_sales.grand_total-erp_sales.total_tax) as balance,sale_type,sale_tax.referent_no as tax_ref,
		sale_tax.amound_declare as amd,sale_tax.amount_tax_declare as amtd');
		$this->db->from('sales');
		$this->db->join('sale_tax', 'sale_tax.sale_id = sales.id');
		$this->db->where_in('id', $id);
        $q = $this->db->get();
         if ($q->num_rows() > 0) {
            return $q;
        }
		return FALSE;
    }
	public function updateSellingTaxStatus($ids){
		$data = array(
               'tax_status' => NULL
            );
			foreach($ids as $id){
				$this->db->where('id',$id);
				$u=$this->db->update('sales',$data);
				$this->db->where('sale_id',$id);
				$d=$this->db->delete('sale_tax');
			}
	}
	public function updateSaleTax($sale_id,$data){
		
		$this->db->where('sale_id',$sale_id);
		$u=$this->db->update('sale_tax',$data);
		if($u){
			return true;
		}else{return false;}
	}
	public function updateSellingTaxStatusById($id){
		$data = array(
               'tax_status' => NULL
            );
		
				$this->db->where('id',$id);
				$u=$this->db->update('sales',$data);
				$this->db->where('sale_id',$id);
				$d=$this->db->delete('sale_tax');
			
	}
	public function declare_tax($data)
    {
        if ($this->db->insert('sale_tax', $data)) {
			$this->db->update('sales', array('reference_no_tax' => $data['referent_no'],'tax_status'=>'confirmed'), array('id' => $data['sale_id']));
			
            return true;
        }
        return false;
    }
	public function getCustomerById($id)
    {
		$this->db->select('vat_no');
		$this->db->where('id', $id);
        $q = $this->db->get('companies');
         if ($q->num_rows() > 0) {
            return $q->row();
        }
		return FALSE;
    }
	function sum_input($date_time) {
    $this->db->select('SUM(amount_tax_declare * average_khm) as sum_amount_tax, 
	SUM(non_tax_pur * average_khm) as sum_non_tax_pur,SUM(erp_purchase_tax.amount * average_khm) as sum_amount');
    $this->db->where('issuedate<',$date_time);
    $this->db->where('purchase_type',1);
    $this->db->where('erp_purchase_tax.tax_type',2);
	$this->db->from('purchase_tax');
	$this->db->join('purchasing_taxes', 'purchase_tax.reference_no=purchasing_taxes.reference_no');
	$this->db->join('erp_tax_exchange_rate', 'erp_tax_exchange_rate.month=MONTH (erp_purchase_tax.issuedate) AND erp_tax_exchange_rate.year = YEAR (erp_purchase_tax.issuedate)');
	
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	function sum_output($date_time) {
    $this->db->select('SUM(amount_tax_declare * average_khm) as sum_amount_tax_declare');
    $this->db->where('issuedate<',$date_time);
    $this->db->where('erp_sales.sale_type',1);
    $this->db->where('erp_sale_tax.tax_type',2);
	$this->db->from('sale_tax');
	$this->db->JOIN('erp_sales','erp_sale_tax.sale_id = erp_sales.id','LEFT');
	$this->db->join('erp_tax_exchange_rate', 'erp_tax_exchange_rate.month=MONTH (erp_sale_tax.issuedate) AND erp_tax_exchange_rate.year = YEAR (erp_sale_tax.issuedate)');
	
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	function get_set_forward($date){
		$this->db->select('(erp_gl_trans.amount * average_khm) as amount, ');
		$this->db->where('gl_trans.account_code',100442);
		$this->db->where('gl_trans.tran_date<',$date);
		$this->db->where('gl_charts_tax.account_tax_id',66);
		$this->db->from('gl_trans');
		$this->db->join('taxation_type_of_account', 'taxation_type_of_account.account_code = gl_trans.account_code');
		$this->db->join('gl_charts_tax', 'gl_charts_tax.account_tax_id = taxation_type_of_account.account_tax_code');
		$this->db->join('erp_tax_exchange_rate', 'erp_tax_exchange_rate.month=MONTH (erp_gl_trans.tran_date) AND erp_tax_exchange_rate.year = YEAR (erp_gl_trans.tran_date)');
	
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q;
        }
        return FALSE;
	}
	function check_update_sale_tax($sale_id){
		$this->db->where('sale_id',$sale_id);
		$g=$this->db->get('sale_tax');
		 if ($g->num_rows() > 0) {
            return true;
        }
        return FALSE;
	}
	function check_update_purchase_tax($purchase_id){
		$this->db->where('purchase_id',$purchase_id);
		$g=$this->db->get('purchase_tax');
		 if ($g->num_rows() > 0) {
            return true;
        }
        return FALSE;
	}
	public function updateTax($sale_id,$data = array())
    {	
       $u= $this->db->update('sale_tax', $data, array('sale_id' => $sale_id));
	   if($u){
		  return true; 
	   }else{
		   return false;
	   }
    }
	public function updatePurchaseTax($id,$data = array())
    {	
       $u= $this->db->update('purchase_tax', $data, array('purchase_id' => $id));
	   if($u){
		  return true; 
	   }else{
		   return false;
	   }
    }
	public function getPursing_tax(){
		$this->db->select("purchases.id, date, ". $this->db->dbprefix("purchases").".reference_no, supplier,purchases.note, status, (grand_total - order_tax) as amount,  order_tax, (grand_total) as balance,
				purchase_tax.amount_declear,purchase_tax.amount_tax_declare,(erp_purchase_tax.amount_declear+erp_purchase_tax.amount_tax_declare) as total_amount_declare,
				IF(erp_purchases.purchase_type = '1', 'Taxable Purchase', IF(erp_purchases.purchase_type = '2', 'Non Taxable Purchase','Import')) as remark,
				(IF(tax_status<>'','confirmed','')) as status_tax")
                ->from('purchases')  
				->join('purchase_tax','purchase_tax.purchase_id = purchases.id', 'left');
		$q=$this->db->get();
		if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	public function getJournal_tax(){
		$this->db->select("tran_id,tran_date,reference_no,description,(amount*10) as amount,amount as order_tax,((amount*10)+amount) as balance,'Non Taxable Purchase' as remark,status_tax");
		$this->db->from('gl_trans');
		$this->db->where('account_code', 100441);
		$this->db->where('tran_type','JOURNAL');
		$q=$this->db->get();
		if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}	
	
	public function getJournal_tax_by_id($id){
		
		$this->db->select("tran_id,tran_date,reference_no,description,(amount*10) as amount,amount as order_tax,((amount*10)+amount) as balance,'Non Taxable Purchase' as remark,status_tax");
		$this->db->from('gl_trans');
		$this->db->where('account_code', 100441);
		$this->db->where('tran_type','JOURNAL');
		$this->db->where_in('tran_id', $id);
		$q=$this->db->get();
		if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	public function getProductsMTaxation($month,$year,$tax_type=2)
    {
		$this->db->where(array('MONTH ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('sale_tax') .'.issuedate)='=>$year));
		$this->db->where('tax_type',$tax_type);
		$this->db->where('sale_tax.sale_type!=',3);
		$this->db->where('sale_tax.sale_type!=',0);
		$this->db->select('sale_tax.sale_id,sale_items.product_name as,sale_items.net_unit_price,sale_items.unit_price,sale_items.subtotal,sale_items.quantity as sale_item_quantity,
						sale_items.specific_tax_on_certain_merchandise_and_services,
						sale_items.accommodation_tax,
						sale_items.public_lighting_tax,
						sale_items.other_tax,
						
						sale_items.performance_royalty_intangible,
						sale_items.payment_interest_non_bank,
						sale_items.payment_interest_taxpayer_fixed,
						sale_items.payment_interest_taxpayer_non_fixed,
						sale_items.payment_rental_immovable_property,
						sale_items.payment_of_interest,
						sale_items.payment_royalty_rental_income_related,
						sale_items.payment_management_technical,
						sale_items.payment_dividend,
						
						sale_items.payment_of_profit_tax,
						sale_tax.sale_type,
						sale_tax.tax_id,
						products.type as product_type,
		');
		$this->db->from('sale_tax');
		$this->db->join('sale_items','sale_tax.sale_id=sale_items.sale_id');
		$this->db->join('products','sale_items.product_id=products.id');
		
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
	
	public function getDataIntoListByID($ent_id, $month=null, $year=null)
	{
		$this->db->select('count(erp_employee_salary_tax.employee_id) as numOfEmp,
		SUM(erp_employee_salary_tax.amount_usd) AS amount_usd,
		SUM(erp_employee_salary_tax.spouse) as spouse,
		SUM(erp_employee_salary_tax.minor_children) as minor_children,
		erp_employee_salary_tax.tax_rate,
		SUM(erp_employee_salary_tax.salary_tax) AS salary_tax,
		SUM(erp_employee_salary_tax.salary_tobe_paid) AS salary_tobe_paid');
		$this->db->from('employee_salary_tax');
		$this->db->join('users', 'users.id = employee_salary_tax.employee_id');
		$this->db->join('groups', 'users.group_id = groups.id', 'left');
		$this->db->where('users.group_id', $ent_id);
		$this->db->where("DATE_FORMAT(erp_employee_salary_tax.date_insert, '%Y-%m')=", ($year . '-' . $month));
        $this->db->group_by('tax_rate');
		$q = $this->db->get();
         if ($q->num_rows() > 0) { 
            return $q->result();
        }
		return FALSE;
	}
	public function getPurchase_tax($month,$year,$tax_type){
		$this->db->where(array('MONTH ('. $this->db->dbprefix('purchase_tax') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('purchase_tax') .'.issuedate)='=>$year));
		$this->db->where('purchase_tax.tax_type',$tax_type);
		$this->db->where('products.type','service');
		$this->db->select('purchase_tax.*,
						purchase_items.specific_tax_on_certain_merchandise_and_services,
						purchase_items.accommodation_tax,
						purchase_items.public_lighting_tax,
						purchase_items.other_tax,
						
						purchase_items.performance_royalty_intangible,
						purchase_items.payment_interest_non_bank,
						purchase_items.payment_interest_taxpayer_fixed,
						purchase_items.payment_interest_taxpayer_non_fixed,
						purchase_items.payment_rental_immovable_property,
						purchase_items.payment_of_interest,
						purchase_items.payment_royalty_rental_income_related,
						purchase_items.payment_management_technical,
						purchase_items.payment_dividend,
						
						purchase_items.payment_of_profit_tax,
						purchase_items.quantity as qty_item,
						purchase_items.net_unit_cost as unit_price,
		');
		$this->db->from('purchase_tax');
		$this->db->join('purchases','purchase_tax.purchase_id=purchases.id');
		$this->db->join('purchase_items','purchase_tax.purchase_id=purchase_items.purchase_id');
		$this->db->join('taxation_type_of_product','purchase_items.product_id=taxation_type_of_product.product_id');
		$this->db->join('products','purchase_items.product_id=products.id');
		$q=$this->db->get();
		if($q){
			return $q->result();
		}else{return false;}
	}
	public function PurcJournalTax($ref){
		$this->db->where('gl_trans.reference_no',$ref);
		$this->db->select('taxation_type_of_account.*,gl_trans.tran_id,gl_trans.amount');
		$this->db->from('gl_trans');
		$this->db->join('taxation_type_of_account','gl_trans.account_code=taxation_type_of_account.account_code','right');
		$q=$this->db->get();
		if($q){
			return $q->result();
		}else{return false;}
	}
	public function insertExchangeTaxRate($data){
		$i=$this->db->insert('tax_exchange_rate',$data);
		if($i){
			return true;
		}return false;
	}
	public function delete_exchangerate_tax($id){
		$this->db->where('id',$id);
		$d=$this->db->delete('tax_exchange_rate');
		if($d){
			return true;
		}return false;
	}
	public function get_exchangerate_tax_by_id($id){
		$this->db->where('id',$id);
		$q=$this->db->get('tax_exchange_rate');
		if($q){
			return $q->row();
		}return false;
	}
	public function update_exchangerate_tax($id,$data){
		$this->db->where('id',$id);
		$u=$this->db->update('tax_exchange_rate',$data);
		if($u){
			return true;
		}return false;
	}
	public function hdie_selling_tax($id){
		$data=array('hide_tax'=>1);
		$this->db->where('id',$id);
		$u=$this->db->update('sales',$data);
		if($u){
			return true;
		}return false;
	}
	public function getPurchasing_tax_journal_ref($month,$year,$tax_type){
		$this->db->where(array('MONTH ('. $this->db->dbprefix('purchase_tax') .'.issuedate)='=>$month,'YEAR ('. $this->db->dbprefix('purchase_tax') .'.issuedate)='=>$year));
		$this->db->where('purchase_tax.tax_type',$tax_type);
		$this->db->where('purchase_tax.type','JOURNAL');
		$this->db->select('purchase_tax.reference_no');
		$this->db->from('purchasing_taxes');
		$this->db->join('purchase_tax','purchasing_taxes.reference_no=purchase_tax.reference_no');
		$q=$this->db->get();
		if($q){
			return $q->result();
		}return false;
	}
	public function getGlTranByRef($journal_ref){
		$this->db->where('gl_trans.reference_no',$journal_ref);
		$this->db->where('gl_trans.amount>',0);
		$this->db->select('
						taxation_type_of_account.performance_royalty_intangible,
						taxation_type_of_account.payment_interest_non_bank,
						taxation_type_of_account.payment_interest_taxpayer_fixed,
						taxation_type_of_account.payment_interest_taxpayer_non_fixed,
						taxation_type_of_account.payment_rental_immovable_property,
						taxation_type_of_account.payment_of_interest,
						taxation_type_of_account.payment_royalty_rental_income_related,
						taxation_type_of_account.payment_management_technical,
						taxation_type_of_account.payment_dividend,
						gl_trans.amount as unit_price,
		');
		$this->db->from('gl_trans');
		$this->db->join('taxation_type_of_account','gl_trans.account_code=taxation_type_of_account.account_code');
		
		$q=$this->db->get();
		if($q){
			return $q->result();
		}return false;
	}
	
}
