<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Purchases_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->default_biller_id = $this->site->default_biller_id();
    }
	
	public function getReferenceno($id){
		 $this->db->select('reference_no');
		 $q = $this->db->get_where('erp_purchases',array('id'=>$id),1);
		  if ($q->num_rows() > 0) {
            return $q->row();
        }
		return FALSE;
	}
	
	public function getItemsByPurchaseId($id)
    {
		$this->db->select('quantity');
		$this->db->where('purchase_id',$id);
		$q = $this->db->get('purchase_items');
		if($q->num_rows() > 0){
             foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
		}
    }
	//Function for purchases receive items invoice
    public function getAllBillers($purchase_id)
    {
        $this->db->select('companies.*')
            ->join('companies', 'companies.id=purchases.biller_id', 'left');
        $q = $this->db->get_where('purchases', array('purchases.id' => $purchase_id));
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getCompanyByID($purchase_id)
    {
        $this->db->select('companies.address,phone,code,name_kh,name')
            ->join('companies', 'companies.id=purchases.supplier_id', 'left');
        $q = $this->db->get_where('purchases', array('purchases.id' => $purchase_id));
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getAllPurchasesOrder($purchase_id){
    	$this->db->select('purchases.reference_no as p_ref, purchases_order.reference_no as po_ref, purchases.date')
            ->join('purchases_order', 'purchases_order.id=purchases.order_id', 'left');
        $q = $this->db->get_where('purchases', array('purchases.id' => $purchase_id));
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    //End
	public function getPurchasesTotals($supplier_id)
    {
        $this->db->select('SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('supplier_id', $supplier_id);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getSupplierPurchases($supplier_id)
    {
        $this->db->from('purchases')->where('supplier_id', $supplier_id);
        return $this->db->count_all_results();
    }
	public function getSupplierById($id)
    {
		$this->db->select('vat_no');
		$this->db->where('id', $id);
        $q = $this->db->get('companies');
         if ($q->num_rows() > 0) {
            return $q->row();
        }
		return FALSE;
    }
	
	public function getProductStock($term, $standard, $combo, $digital, $service, $category, $warehouse_id, $category_id, $option_id = null, $limit = 15)
    {
        $this->db->select('
        					products.id,
        					products.code,
        					CONCAT(erp_products.name, " (", IF (erp_product_variants.name != "", erp_product_variants.name, erp_units.name), " )") as name,
        					warehouses_products.quantity,
        					COALESCE(erp_product_variants.name, "") as variant,
							COALESCE(erp_product_variants.id, "") as option_id
        				');
		$this->db->where("(type = 'standard') AND (erp_products.name LIKE '%" . $term . "%' OR erp_products.code LIKE '%" . $term . "%' OR  concat(erp_products.name, ' (', erp_products.code, ')') LIKE '%" . $term . "%') AND inactived <> 1");
		$this->db->where(array("warehouses_products.warehouse_id"=> $warehouse_id, "products.inactived !="=>"1"));
		if($category_id){
			$this->db->where("products.category_id", $category_id);
		}
		if($option_id){
			$this->db->where("product_variants.id", $option_id);
		}
		$this->db->join('warehouses_products', 'products.id = warehouses_products.product_id', 'left');
		$this->db->join('product_variants', 'products.id = product_variants.product_id', 'left');
		$this->db->join('units', 'products.unit = units.id', 'left');
		$this->db->order_by('code', 'DESC');
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getProductNames($term, $standard, $combo, $digital, $service, $category, $limit = 100)
    {
        
		$this->db->where("(type = 'standard' OR type = 'service') AND (name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%') AND inactived <> 1");
		//$this->db->where('MATCH (code) AGAINST ("'+ $term +'")', NULL, FALSE);
		if($this->Owner || $this->Admin){
            if($standard != ""){
                $this->db->where("products.type <> 'standard' ");
            }
            if($combo != ""){
                $this->db->where("products.type <> 'combo' ");
            }
            if($digital != ""){
                $this->db->where("products.type <> 'digital' ");
            }
            if($service != ""){
                $this->db->where("products.type <> 'service' ");
            }
            if($category != ""){
                $this->db->where("products.category_id NOT IN (".$category.") ");
            }   
        }else{
            if($standard != ""){
                $this->db->where("products.type <> 'standard' ");
            }
            if($combo != ""){
                $this->db->where("products.type <> 'combo' ");
            }
            if($digital != ""){
                $this->db->where("products.type <> 'digital' ");
            }
            if($service != ""){
                $this->db->where("products.type <> 'service' ");
            }
            if($category != ""){
                $this->db->where("products.category_id NOT IN (".$category.") ");
            }
		}
		$this->db->order_by('code', 'ASC');
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getTranNoExp($id){
		
		$this->db->select('erp_gl_trans.tran_no');
		$this->db->from('purchases');
		$this->db->join('gl_trans','erp_gl_trans.reference_no = erp_purchases.reference_no AND erp_purchases.biller_id = erp_gl_trans.biller_id','inner');
		$this->db->where('purchases.id', $id);
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return  $q->row();
		}
		return FALSE;
		
	}
	
	
	public function getJournalByTranNo($tran_no){
		$this->db->select('gl_trans.*, (IF(erp_gl_trans.amount > 0, erp_gl_trans.amount, null)) as debit, 
							(IF(erp_gl_trans.amount < 0, abs(erp_gl_trans.amount), null)) as credit');
		$q = $this->db->get_where('gl_trans', array('tran_no' => $tran_no));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	
	public function getSubAccounts($section_code){
		$this->db->select('accountcode as id, accountname as text');
        $q = $this->db->get_where("gl_charts", array('sectionid' => $section_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
	}
	
	/*Customize Expense*/
	
	public function getTran_id($reference_no)
	{
		$this->db->select('tran_id')
		->where('tran_type','PURCHASE EXPENSE')
		->where('reference_no',$reference_no)
		->where('amount<0');
		
		$q = $this->db->get('gl_trans');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	
	public function updateJournal($reference = NULL, $rows = array()) {
		$ids = '';
		$ref = '';
		
		$this->db->delete('gl_trans', array('reference_no' => $reference));
		
		foreach($rows as $data){
			$gl_chart = $this->getChartAccountByID($data['account_code']);	
			if($gl_chart > 0){
				$data['sectionid'] = $gl_chart->sectionid;
				$data['narrative'] = $gl_chart->accountname;
			}
			
			$this->db->insert('gl_trans', $data);
		}
	}
	
	
	public function getAlltypes(){
		$q = $this->db->query("SELECT * from erp_groups WHERE erp_groups.id IN (3,4)");
		
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	
	public function getAccountSections(){
		$this->db->select("sectionid,sectionname");
		$section = $this->db->get("gl_sections");
		if($section->num_rows() > 0){
			return $section->result_array();	
		}
		return false;
	}
	
	
	public function getAllChartAccount(){
		$this->db->select('gl_charts.accountcode, gl_charts.accountname, gl_charts.parent_acc, gl_charts.sectionid');
		$this->db->from('gl_charts');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return FALSE;
	}
	
	
	public function getpeoplebytype($company){
		
		if($company == 'emp'){
			$this->db->select('CONCAT(first_name,"  ",last_name) as id, CONCAT(first_name,"  ",last_name) as text');
			$q = $this->db->get("users");
		}else{
			$this->db->select('name as id, name as text');
			$q = $this->db->get_where("companies", array('group_id' => $company));
		}
		
		
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
	}
	
	
	public function getTranNo(){
		/*
		$this->db->query("UPDATE erp_order_ref
							SET tr = tr + 1
							WHERE
							DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')");
		*/
		/*
		$q = $this->db->query("SELECT tr FROM erp_order_ref
									WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')");
									*/

		$this->db->select('(COALESCE (MAX(tran_no), 0) + 1) AS tr');
		$q = $this->db->get('gl_trans');
		if($q->num_rows() > 0){
			$row = $q->row();
			return $row->tr;
		}
		return FALSE;
	}
	
	
	public function ACC_AP(){
		$this->db->select('gl_charts.accountcode');
		$this->db->from("account_settings");
		$this->db->join('gl_charts', 'gl_charts.accountcode = account_settings.default_payable','INNER');
		$this->db->join('gl_sections', 'gl_sections.sectionid = gl_charts.sectionid','INNER');
		$q = $this->db->get();
		if($q->num_rows() > 0){
			$row = $q->row();
			return $row->accountcode;
		}
		return FALSE;
	}
	
	public function ACC_Pur_Tax(){
		$this->db->select('gl_charts.accountcode');
		$this->db->from("account_settings");
		$this->db->join('gl_charts', 'gl_charts.accountcode = account_settings.default_purchase_tax','INNER');
		$this->db->join('gl_sections', 'gl_sections.sectionid = gl_charts.sectionid','INNER');
		$q = $this->db->get();
		if($q->num_rows() > 0){
			$row = $q->row();
			return $row->accountcode;
		}
		return FALSE;
	}
	
	
	public function addJournal($rows){
		foreach($rows as $data){
			$gl_chart = $this->getChartAccountByID($data['account_code']);
			if($gl_chart > 0){
				$data['sectionid'] = $gl_chart->sectionid;
				$data['narrative'] = $gl_chart->accountname;
			}
			$this->db->insert('gl_trans', $data);
		}
	}
	
	public function getChartAccountByID($id){
		$this->db->select('gl_charts.accountcode,gl_charts.accountname,gl_charts.parent_acc,gl_charts.sectionid,gl_sections.sectionname, bank ');
		$this->db->from('gl_charts');
		$this->db->join('gl_sections', 'gl_sections.sectionid=gl_charts.sectionid','INNER');
		$this->db->where('gl_charts.accountcode' , $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getOldQOH($id)
	{
		$this->db->select('quantity');
		$this->db->from('products');
		$this->db->where('products.id' , $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getAccPayable ($id=null){
		$this->db->select('accountcode');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'gl_sections.sectionid=gl_charts.sectionid','INNER');
		$this->db->where('gl_charts.accountcode' , $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	
	
	/*End Code*/
	

	public function getProductNumber($term, $standard, $combo, $digital, $service, $category, $limit = 100)
    {
		if(preg_match('/\s/', $term))
		{
			$name = explode(" ", $term);
			$first = $name[0];
			$this->db->select('*')
            ->group_by('products.id');
			$this->db->where('code', $first);
			$this->db->limit($limit);
			if($this->Owner || $this->admin){
                if($standard != ""){
                    $this->db->where("products.type <> 'standard' ");
                }
                if($combo != ""){
                    $this->db->where("products.type <> 'combo' ");
                }
                if($digital != ""){
                    $this->db->where("products.type <> 'digital' ");
                }
                if($service != ""){
                    $this->db->where("products.type <> 'service' ");
                }
                if($category != ""){
                    $this->db->where("products.category_id NOT IN (".$category.") ");
                }
            }else{
                if($standard != ""){
                    $this->db->where("products.type <> 'standard' ");
                }
                if($combo != ""){
                    $this->db->where("products.type <> 'combo' ");
                }
                if($digital != ""){
                    $this->db->where("products.type <> 'digital' ");
                }
                if($service != ""){
                    $this->db->where("products.type <> 'service' ");
                }
                if($category != ""){
                    $this->db->where("products.category_id NOT IN (".$category.") ");
                }
            }
			$q = $this->db->get('products');
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			}
		}else
		{
			/* --v_pos : View in Database
			$this->db->select();
			$this->db->from('v_pos');
			$this->db->where("(code LIKE '%" . $term . "%')");
			 ENd VIew */

            $this->db->select('products.*')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
			$this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%') AND inactived <> 1");
            if ($this->Owner || $this->Admin) {
                if($standard != ""){
                    $this->db->where("products.type <> 'standard' ");
                }
                if($combo != ""){
                    $this->db->where("products.type <> 'combo' ");
                }
                if($digital != ""){
                    $this->db->where("products.type <> 'digital' ");
                }
                if($service != ""){
                    $this->db->where("products.type <> 'service' ");
                }
                if($category != ""){
                    $this->db->where("products.category_id NOT IN (".$category.") ");
                }
            }else{
                if($standard != ""){
                    $this->db->where("products.type <> 'standard' ");
                }
                if($combo != ""){
                    $this->db->where("products.type <> 'combo' ");
                }
                if($digital != ""){
                    $this->db->where("products.type <> 'digital' ");
                }
                if($service != ""){
                    $this->db->where("products.type <> 'service' ");
                }
                if($category != ""){
                    $this->db->where("products.category_id NOT IN (".$category.") ");
                }
            }
			$this->db->limit($limit);
			$q = $this->db->get('products');
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			}
		}
	}
	
	public function getExpenseByCode($code)
    {
        $q = $this->db->get_where('expenses', array('account_code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
	
	function getExpenseByReference($ref){
		$q = $this->db->get_where('expenses', array('reference' => $ref), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
	}
	
	public function addExpenses($data = array())
    {
		if ($this->db->insert_batch('expenses', $data)) {
            return true;
        }
        return false;
    }
	
	public function addOpeningAP($purchases, $deposits, $da)
    {

		$this->db->trans_start();
		if ($this->db->insert_batch('purchases', $purchases)) {
			if($deposits){
				foreach($deposits as $deposit){
					if(!empty($deposit['amount']) && $deposit['amount'] > 0)
					{
						$this->db->insert('deposits', $deposit);
						$pur_deposit_id = $this->db->insert_id();						
						$payment = array(
							'date' => $deposit['date'],
							'reference_no' => $deposit['reference'],
							'amount' => $deposit['amount'],
							'paid_by' => 'cash',
							'created_by' => $deposit['created_by'],
							'type' => 'received',
							'biller_id'	=> $deposit['biller_id'],
							'purchase_deposit_id' => $pur_deposit_id,
							'opening' => $deposit['opening']
						);
						$this->db->insert('payments', $payment);	
					}
				}
				
				$this->site->syncDeposits($da);
			}
			$this->db->trans_complete();
            return true;
        }

        return false;
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
        return FALSE;
    }

    public function getProductByID($id)
    {
        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductsByCode($code)
    {
        $this->db->select('*')->from('products')->like('code', $code, 'both');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getProductByCode($code)
    {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductByName($name)
    {
        $q = $this->db->get_where('products', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateProductQuantity($product_id, $quantity, $warehouse_id, $product_cost)
    {
        if ($this->addQuantity($product_id, $warehouse_id, $quantity)) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function calculateAndUpdateQuantity($item_id, $product_id, $quantity, $warehouse_id, $product_cost)
    {
        if ($this->updatePrice($product_id, $product_cost) && $this->calculateAndAddQuantity($item_id, $product_id, $warehouse_id, $quantity)) {
            return true;
        }
        return false;
    }

    public function calculateAndAddQuantity($item_id, $product_id, $warehouse_id, $quantity)
    {

        if ($this->getProductQuantity($product_id, $warehouse_id)) {
            $quantity_details = $this->getProductQuantity($product_id, $warehouse_id);
            $product_quantity = $quantity_details['quantity'];
            $item_details = $this->getItemByID($item_id);
            $item_quantity = $item_details->quantity;
            $after_quantity = $product_quantity - $item_quantity;
            $new_quantity = $after_quantity + $quantity;
            if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                return TRUE;
            }
        } else {

            if ($this->insertQuantity($product_id, $warehouse_id, $quantity)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addQuantity($product_id, $warehouse_id, $quantity)
    {

        if ($this->getProductQuantity($product_id, $warehouse_id)) {
            $warehouse_quantity = $this->getProductQuantity($product_id, $warehouse_id);
            $old_quantity = $warehouse_quantity['quantity'];
            $new_quantity = $old_quantity + $quantity;

            if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                return TRUE;
            }
        } else {

            if ($this->insertQuantity($product_id, $warehouse_id, $quantity)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function insertQuantity($product_id, $warehouse_id, $quantity)
    {
        $productData = array(
            'product_id' => $product_id,
            'warehouse_id' => $warehouse_id,
            'quantity' => $quantity
        );
        if ($this->db->insert('warehouses_products', $productData)) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function updateQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($this->db->update('warehouses_products', array('quantity' => $quantity), array('product_id' => $product_id, 'warehouse_id' => $warehouse_id))) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function getProductQuantity($product_id, $warehouse)
    {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);

        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }
        return FALSE;
    }

    public function updatePrice($id, $unit_cost)
    {
        if ($this->db->update('products', array('cost' => $unit_cost), array('id' => $id))) {
            return true;
        }
        return false;
    }

    public function getAllPurchases()
    {
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getAllPurchaseItems($purchase_id)
    {
        $this->db->select('purchase_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, units.name as unit,erp_products.id as product_id, products.details as details,products.image,products.name as pname,purchase_items.piece,purchase_items.wpiece,purchase_items.option_id, product_variants.name as variant,IF(erp_companies.company = "", erp_companies.name, erp_companies.company) AS supplier')
            ->join('products', 'products.id=purchase_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=purchase_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=purchase_items.tax_rate_id', 'left')
            ->join('purchases', 'purchase_items.purchase_id=purchases.id','left')
			->join('companies', 'companies.id=purchases.supplier_id', 'left')
            ->join('units', 'products.unit = units.id', 'left')
            ->group_by('purchase_items.id')
            ->order_by('id','DESC');
        $q = $this->db->get_where('purchase_items', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getVar($id){
        $q = $this->db->get_where('erp_product_variants', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getPoquantiyById($id){
		$this->db->select('sum(erp_purchase_order_items.quantity_po) as quantity_po,sum(erp_purchase_order_items.quantity) as quantity');
		$q = $this->db->get_where('purchase_order_items',array('purchase_id' => $id),1);
		return $q->row();
			
	}
	
	public function getAllPurchaseOrderItems($purchase_id)
    {
        $this->db->select('	purchase_order_items.id,
							purchase_order_items.purchase_id,
							purchase_order_items.transfer_id,
							purchase_order_items.product_id,
							purchase_order_items.product_code,
							purchase_order_items.product_name,
							purchase_order_items.option_id,
							purchase_order_items.net_unit_cost,
							purchase_order_items.quantity as po_qty,
							(erp_purchase_order_items.quantity - erp_purchase_order_items.quantity_po) AS quantity,
							purchase_order_items.quantity_po,
							purchase_order_items.warehouse_id,
							purchase_order_items.item_tax,
							purchase_order_items.tax_rate_id,
							purchase_order_items.tax,
							purchase_order_items.discount,
							purchase_order_items.item_discount,
							purchase_order_items.expiry,
							purchase_order_items.subtotal,
							purchase_order_items.quantity_balance,
							purchase_order_items.date,
							purchase_order_items.`status`,
							purchase_order_items.unit_cost,
							purchase_order_items.real_unit_cost,
							purchase_order_items.quantity_received,
							purchase_order_items.supplier_part_no,
							purchase_order_items.supplier_id,
							purchase_order_items.tax_method,
							purchase_order_items.piece,
							purchase_order_items.wpiece,
							purchase_order_items.price,purchase_order_items.create_id, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, units.name as unit, products.details as details,products.image,products.name as pname, product_variants.name as variant,companies.name')
            ->join('products', 'products.id=purchase_order_items.product_id', 'left')
            ->join('units', 'products.unit = units.id', 'left')
			->join('companies', 'companies.id=purchase_order_items.supplier_id', 'left')
            ->join('product_variants', 'product_variants.id=purchase_order_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=purchase_order_items.tax_rate_id', 'left')
            ->group_by('purchase_order_items.id')
            ->order_by('id', 'desc');
        $q = $this->db->get_where('purchase_order_items', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllPurchaseOrderItems_order($purchase_id)
    {
        $this->db->select('	purchase_order_items.id,
							purchase_order_items.purchase_id,
							purchase_order_items.transfer_id,
							purchase_order_items.product_id,
							purchase_order_items.product_code,
							purchase_order_items.product_name,
							purchase_order_items.option_id,
							purchase_order_items.net_unit_cost,
							purchase_order_items.quantity as po_qty,
							erp_purchase_order_items.quantity,
							purchase_order_items.warehouse_id,
							purchase_order_items.item_tax,
							purchase_order_items.tax_rate_id,
							purchase_order_items.tax,
							purchase_order_items.discount,
							purchase_order_items.item_discount,
							purchase_order_items.expiry,
							purchase_order_items.subtotal,
							purchase_order_items.quantity_balance,
							purchase_order_items.date,
							purchase_order_items.`status`,
							purchase_order_items.unit_cost,
							purchase_order_items.real_unit_cost,
							purchase_order_items.quantity_received,
							purchase_order_items.supplier_part_no,
							purchase_order_items.supplier_id,
							purchase_order_items.price, 
							purchase_order_items.tax_method, 
							tax_rates.code as tax_code, 
							tax_rates.name as tax_name, 
							tax_rates.rate as tax_rate, 
							products.unit, 
							products.details as details,
							products.image,
							products.name as pname, 
							purchase_order_items.piece,
							purchase_order_items.wpiece,
							product_variants.name as variant,companies.name')
            ->join('products', 'products.id=purchase_order_items.product_id', 'left')
			->join('companies', 'companies.id=purchase_order_items.supplier_id', 'left')
            ->join('product_variants', 'product_variants.id=purchase_order_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=purchase_order_items.tax_rate_id', 'left')
            ->group_by('purchase_order_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('purchase_order_items', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getPurchasesOrderbyID($id){
		 $q= $this->db->get_where('erp_purchases_order',array('id'=>$id),1);
		  if ($q->num_rows() > 0) {
            return $q->row();
          }
          return FALSE;
	}
	
	public function getPurchasesReqestbyID($id){
		 $q= $this->db->get_where('erp_purchases_request',array('id'=>$id),1);
		  if ($q->num_rows() > 0) {
            return $q->row();
          }
          return FALSE;
	}
	
	public function getAllQuoteItemsData($quote_id)
    {
        $this->db->select('quote_items.*, quotes.biller, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details,products.image,products.name as pname, product_variants.name as variant,companies.name')
			->join('quotes', 'quote_items.quote_id = quotes.id', 'left')
            ->join('products', 'products.id=quote_items.product_id', 'left')
			->join('companies', 'companies.id=quote_items.supplier_id', 'left')
            ->join('product_variants', 'product_variants.id=quote_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=quote_items.tax_rate_id', 'left')
            ->group_by('quote_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('quote_items', array('quote_id' => $quote_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL)
    {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, item_tax');
        $this->db->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->where('quantity_balance !=', 0);
        if ($option_id) {
            $this->db->where('option_id', $option_id);
        }
        $this->db->group_by('id');
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getVariantQtyById($id) {
		$q = $this->db->get_where('product_variants', array('id' => $id), 1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getVariantQtyByProductId($product_id) {
		$q = $this->db->get_where('product_variants', array('product_id' => $product_id));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}

    public function getItemByID($id)
    {
        $q = $this->db->get_where('purchase_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTaxRateByName($name)
    {
        $q = $this->db->get_where('tax_rates', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function paymentByPurchaseID($purchase_id){
        $this->db->select('purchase_id');
        $q = $this->db->get_where('payments', array('purchase_id' => $purchase_id), 1);
        if($q->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
	
	// Return by invoice
	public function returnPurchase($data = array(), $items = array(), $payment = array())
    {
       
		$purchase_items = $this->site->getAllPurchaseItems($data['purchase_id']);
		
        if ($this->db->insert('return_purchases', $data)) {
            $return_id = $this->db->insert_id();
			
			if ($this->site->getReference('rep',$data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('rep',$data['biller_id']);
			}
            foreach ($items as $item) {
                $item['return_id'] = $return_id;
				$quantity_balance = $item['quantity_balance']; 
				unset($item['quantity_balance']);
                $this->db->insert('return_purchase_items', $item);
				$return_item_id = $this->db->insert_id();
				if($item['product_type'] == 'standard'){
					$new_arr_data = array(
						'product_id' 		=> $item['product_id'],
						'product_code' 		=> $item['product_code'],
						'product_name' 		=> $item['product_name'],
						'net_unit_cost' 	=> $item['net_unit_cost']?$item['net_unit_cost']:0,
						'quantity' 			=> $item['quantity'],
						'item_tax' 			=> 0,
						'warehouse_id' 		=> $item['warehouse_id'],
						'subtotal' 			=> $item['subtotal']?$item['subtotal']:0,
						'date' 				=> date('Y-m-d'),
						'status' 			=> 'received',
						'transaction_type'	=> 'PURCHASE RETURN',
						'transaction_id'	=> $return_item_id,
						'quantity_balance' 	=> -1 * abs($quantity_balance)
					);
					$this->db->insert('purchase_items', $new_arr_data);
				}
            }
            $is_payment = $this->paymentByPurchaseID($data['purchase_id']);
            if($payment){
                /*
				$payment = array(
                    'date' 					=> $data['date'],
                    'purchase_id' 			=> $data['purchase_id'],
                    'reference_no' 			=> $this->site->getReference('pp'),
                    'amount' 				=> $data['grand_total'],
                    'paid_by' 				=> 'cash',
                    'created_by' 			=> $this->session->userdata('user_id'),
                    'type' 					=> 'received',
                    'note' 					=> $data['note'] ? 'Returned: '. $data['note'] : 'Returned',
                    'purchase_return_id' 	=> $return_id,
                    'biller_id' 			=> $this->default_biller_id
                );
				*/
                $this->db->insert('payments', $payment);
                $this->site->updateReference('pp');
            }

            $this->calculatePurchaseTotalsReturn($data['purchase_id'], $return_id, $data['surcharge']);
            //$this->site->syncQuantity(NULL, NULL, $purchase_items);
            //$this->site->syncQuantity(NULL, $data['purchase_id']);
            $this->site->syncQuantitys(NULL, $data['purchase_id'], NULL, NULL);
            return true;
        }
        return false;
    }
	
	/* Purchases Return */
	public function returnPurchases($data = array(), $items = array())
    {
		//$this->erp->print_arrays($data, $items);
        if ($this->db->insert('return_purchases', $data)) {
            $return_id = $this->db->insert_id();
            if ($this->site->getReference('rep') == $data['reference_no']) {
                $this->site->updateReference('rep');
            }
            foreach ($items as $item) {
                $item['return_id'] = $return_id;
                $purchase_id = $item['purchase_item_id'];
                $item['purchase_id'] = $purchase_id;
                $purchase_item = $this->getPurcahseItemByPurchaseIDProductID($purchase_id, $item['product_id']);       
                $item['purchase_item_id'] = $purchase_item->id;       
                $this->db->insert('return_purchase_items', $item);
				$warehouse_id = $item['warehouse_id'];
                
                $purchase_items = $this->site->getAllPurchaseItems($purchase_id);
				
				if($item['product_type'] == 'standard'){
					$new_arr_data = array(
						'product_id' => $item['product_id'],
						'product_code' => $item['product_code'],
						'product_name' => $item['product_name'],
						'net_unit_cost' => $item['net_unit_cost']?$item['net_unit_cost']:0,
						'quantity' => 0,
						'item_tax' => 0,
						'warehouse_id' => $item['warehouse_id'],
						'subtotal' => $item['subtotal']?$item['subtotal']:0,
						'date' => date('Y-m-d'),
						'status' => '',
						'quantity_balance' => -1 * abs($item['quantity'])
					);
					$this->db->insert('purchase_items', $new_arr_data);
				}
				
				/*
                if ($purchase_item) {
                        $nqty = $purchase_item->quantity - $item['quantity'];
                        $bqty = $purchase_item->quantity_balance - $item['quantity'];
                        $rqty = $purchase_item->quantity_received - $item['quantity'];
                        $tax = $purchase_item->unit_cost - $purchase_item->net_unit_cost;
                        $discount = $purchase_item->item_discount / $purchase_item->quantity;
                        $item_tax = $tax * $nqty;
                        $item_discount = $discount * $nqty;
                        $subtotal = $purchase_item->unit_cost * $nqty;
                        //$this->db->update('purchase_items', array('quantity_balance' => $bqty, 'quantity_received' => $rqty, 'item_tax' => $item_tax, 'item_discount' => $item_discount, 'subtotal' => $subtotal), array('id' => $item['purchase_item_id']));
                        $this->db->update('purchase_items', array('quantity_balance' => $bqty, 'quantity_received' => $rqty), array('id' => $item['purchase_item_id']));
                }
				*/
                $is_payment = $this->paymentByPurchaseID($purchase_id);
                if($is_payment){
                    $payment = array(
                        'date' => $data['date'],
                        'purchase_id' => $purchase_id,
                        'reference_no' => $this->site->getReference('pp'),
                        'amount' => $data['grand_total'],
                        'paid_by' => 'cash',
                        'created_by' => $this->session->userdata('user_id'),
                        'type' => 'received',
                        'note' => $data['note'] ? 'Returned: '. $data['note'] : 'Returned',
                        'purchase_return_id' => $return_id,
                        'biller_id' => $this->default_biller_id
                    );
                    $this->db->insert('payments', $payment);
                    $this->site->updateReference('pp');
                }
                $this->calculatePurchaseTotalsReturn($purchase_id, $return_id, $data['surcharge']);
                //$this->site->syncQuantity(NULL, NULL, $purchase_items);
				if($purchase_id > 0){
					$this->site->syncQuantity(NULL, $purchase_id);
				}else{
					$pr = $this->site->getProductByID($item['product_id']);
					$pr_quantity = $pr->quantity - $item['quantity'];
					if ($this->db->update('products', array('quantity' => $pr_quantity), array('id' => $item['product_id']))) {
						if ($this->site->getWarehouseProducts($item['product_id'], $warehouse_id)) {
							$this->db->update('warehouses_products', array('quantity' => $pr_quantity), array('product_id' => $item['product_id'], 'warehouse_id' => $warehouse_id));
						} else {
							if( ! $pr_quantity) { $pr_quantity = 0; }
							$this->db->insert('warehouses_products', array('quantity' => $pr_quantity, 'product_id' => $item['product_id'], 'warehouse_id' => $warehouse_id));
						}
					}
				}
            }
            return true;
        }
        return false;
    }

    public function calculatePurchaseTotals($id, $return_id, $surcharge)
    {
        $purchase = $this->getPurchaseByID($id);
        $items = $this->getAllPurchaseItems($id);
        if (!empty($items)) {
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            foreach ($items as $item) {
                $product_tax += $item->item_tax;
                $product_discount += $item->item_discount;
                $total += $item->net_unit_cost * $item->quantity;
            }
            if ($purchase->order_discount_id) {
                $percentage = '%';
                $order_discount_id = $purchase->order_discount_id;
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total + $product_tax) * (Float)($ods[0])) / 100;
                } else {
                    $order_discount = $order_discount_id;
                }
            }
            if ($purchase->order_tax_id) {
                $order_tax_id = $purchase->order_tax_id;
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $order_tax_details->rate;
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = (($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100;
                    }
                }
            }
            $total_discount = $order_discount + $product_discount;
            $total_tax = $product_tax + $order_tax;
            $grand_total = $total + $total_tax + $purchase->shipping - $order_discount + $surcharge;
            $data = array(
                'total' => $total,
                'product_discount' => $product_discount,
                'order_discount' => $order_discount,
                'total_discount' => $total_discount,
                'product_tax' => $product_tax,
                'order_tax' => $order_tax,
                'total_tax' => $total_tax,
                'grand_total' => $grand_total,
                'return_id' => $return_id,
                'surcharge' => $surcharge
            );

            if ($this->db->update('purchases', $data, array('id' => $id))) {
                return true;
            }
        } else {
            //$this->db->delete('purchases', array('id' => $id));
        }
        return FALSE;
    }
	
	public function calculatePurchaseTotalsReturn($id, $return_id, $surcharge)
    {
        $purchase = $this->getPurchaseByID($id);
        $items = $this->getAllPurchaseItems($id);
        if (!empty($items)) {
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            foreach ($items as $item) {
                $product_tax += $item->item_tax;
                $product_discount += $item->item_discount;
                $total += $item->net_unit_cost * $item->quantity;
            }
            if ($purchase->order_discount_id) {
                $percentage = '%';
                $order_discount_id = $purchase->order_discount_id;
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total + $product_tax) * (Float)($ods[0])) / 100;
                } else {
                    $order_discount = $order_discount_id;
                }
            }
            if ($purchase->order_tax_id) {
                $order_tax_id = $purchase->order_tax_id;
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $order_tax_details->rate;
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = (($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100;
                    }
                }
            }
            $total_discount = $order_discount + $product_discount;
            $total_tax = $product_tax + $order_tax;
            $grand_total = $total + $total_tax + $purchase->shipping - $order_discount + $surcharge;
            $data = array(
                //'total' => $total,
                //'product_discount' => $product_discount,
                //'order_discount' => $order_discount,
                //'total_discount' => $total_discount,
                //'product_tax' => $product_tax,
                //'order_tax' => $order_tax,
                //'total_tax' => $total_tax,
                //'grand_total' => $grand_total,
                'return_id' => $return_id,
                'surcharge' => $surcharge,
				'status' => 'returned'
            );
            /*
            $data = array(
                'return_id' => $return_id,
                'surcharge' => $surcharge
            );
            */

            if ($this->db->update('purchases', $data, array('id' => $id))) {
                return true;
            }
        } else {
            //$this->db->delete('purchases', array('id' => $id));
        }
        return FALSE;
    }
	
	public function getPurcahseItemByID($id)
    {
        $q = $this->db->get_where('purchase_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getPurcahseItemByPurchaseID($id)
    {
        $q = $this->db->get_where('purchase_items', array('purchase_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getPurcahseOrderItemByPurchaseID($id)
    {
        $q = $this->db->get_where('purchase_order_items', array('purchase_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getPurcahseItemByPurchaseIDProductID($id, $product_id, $order_id = NULL)
    {
		if($order_id){
			$this->db->where('create_id', $order_id);
		}
		
        $q = $this->db->get_where('purchase_items', array('purchase_id' => $id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getPurcahseItemByPurIDProID($id, $product_id)
    {
		$this->db->select('SUM(subtotal) as subtotal, SUM(quantity_balance) as quantity_balance');
		$q = $this->db->get_where('purchase_items', array('purchase_id' => $id, 'product_id' => $product_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function updatePurchaseItem($id, $qty, $purchase_item_id, $product_id = NULL, $warehouse_id = NULL, $option_id = NULL)
    {
        if ($id) {
            if($pi = $this->getPurchaseItemByID($id)) {
                $pr = $this->site->getProductByID($pi->product_id);
                if ($pr->type == 'combo') {
                    $combo_items = $this->site->getProductComboItems($pr->id, $pi->warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        if($combo_item->type == 'standard') {
                            $cpi = $this->site->getPurchasedItem(array('product_id' => $combo_item->id, 'warehouse_id' => $pi->warehouse_id, 'option_id' => NULL));
                            $bln = $pi->quantity_balance + ($qty*$combo_item->qty);
                            $this->db->update('purchase_items', array('quantity_balance' => $bln), array('id' => $combo_item->id));
                        }
                    }
                } else {
                    $bln = $pi->quantity_balance + $qty;
                    $this->db->update('purchase_items', array('quantity_balance' => $bln), array('id' => $id));
                }
            }
        } else {
            if ($purchase_item = $this->getPurchaseItemByID($purchase_item_id)) {
                $option_id = isset($purchase_item->option_id) && !empty($purchase_item->option_id) ? $purchase_item->option_id : NULL;
                $clause = array('product_id' => $purchase_item->product_id, 'warehouse_id' => $purchase_item->warehouse_id, 'option_id' => $option_id);
                if ($pi = $this->site->getPurchasedItem($clause)) {
                    $quantity_balance = $pi->quantity_balance+$qty;
                    $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), array('id' => $pi->id));
                } else {
                    $clause['purchase_id'] = NULL;
                    $clause['transfer_id'] = NULL;
                    $clause['quantity'] = 0;
                    $clause['quantity_balance'] = $qty;
                    $this->db->insert('purchase_items', $clause);
                }
            }
            if (! $sale_item && $product_id) {
                $pr = $this->site->getProductByID($product_id);
                $clause = array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'option_id' => $option_id);
                if ($pr->type == 'standard') {
                    if ($pi = $this->site->getPurchasedItem($clause)) {
                        $quantity_balance = $pi->quantity_balance+$qty;
                        $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), array('id' => $pi->id));
                    } else {
                        $clause['purchase_id'] = NULL;
                        $clause['transfer_id'] = NULL;
                        $clause['quantity'] = 0;
                        $clause['quantity_balance'] = $qty;
                        $this->db->insert('purchase_items', $clause);
                    }
                } elseif ($pr->type == 'combo') {
                    $combo_items = $this->site->getProductComboItems($pr->id, $warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        $clause = array('product_id' => $combo_item->id, 'warehouse_id' => $warehouse_id, 'option_id' => NULL);
                        if($combo_item->type == 'standard') {
                            if ($pi = $this->site->getPurchasedItem($clause)) {
                                $quantity_balance = $pi->quantity_balance+($qty*$combo_item->qty);
                                $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), $clause);
                            } else {
                                $clause['transfer_id'] = NULL;
                                $clause['purchase_id'] = NULL;
                                $clause['quantity'] = 0;
                                $clause['quantity_balance'] = $qty;
                                $this->db->insert('purchase_items', $clause);
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function getPurchaseItemIdByPurchaseID($purchase_id, $product_id = null)
    {
        $this->db->select('id');
        $q = $this->db->get_where('purchase_items', array('purchase_id' => $purchase_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            $q = $q->row();
            return $q->id;
        }
        return FALSE;
    }

    public function getPurchaseByID($id=null, $wh=null)
    {
    	$this->db
                ->select("
                        purchases.id,
                        purchases.date,
                        purchases.due_date,
                        purchases_request.reference_no as request_ref,
                        order_ref,
                        purchases.reference_no,
						purchases.type_of_po,
                        companies.name,
                        purchases.status,
						purchases.biller_id,
						purchases.warehouse_id,
						purchases.supplier_id,
						purchases.supplier,
                        purchases.grand_total,
                        purchases.paid,
                        purchases.total,
                        purchases.created_by,
                        purchases.quote_id,
                        purchases.note,
                        purchases.order_discount_id,
                        purchases.order_tax_id,
                        purchases.payment_term,
                        purchases.order_discount,
                        purchases.customer_id,
                        purchases.sale_id,
                        purchases.order_tax,
                        purchases.order_id,
                        purchases.shipping,
                        (erp_purchases.grand_total - erp_purchases.paid) as balance,
                        purchases.payment_status,
						purchases.updated_by,
						purchases.updated_count,
						purchases.attachment,
						purchases.updated_at,
                        purchases.opening_ap, companies.company,companies.name as customer_name,users.username, tax_rates.name AS tax_name, warehouses.name AS ware_name")
                ->from('purchases')
				->join('companies', 'companies.id = purchases.biller_id', 'inner')
                ->join('purchases_order', 'purchases.order_id = purchases_order.id', 'left')
                ->join('purchases_request', 'purchases_order.request_id = purchases_request.id', 'left')
                ->join('users', 'purchases.created_by = users.id', 'left')
                ->join('tax_rates', 'purchases.order_tax_id = tax_rates.id', 'left')
                ->join('warehouses', 'purchases.warehouse_id = warehouses.id', 'left')
    			->where('purchases.id',$id);
    	if($wh){
    		$this->db->where_in('erp_purchases.warehouse_id',$wh);
    	}
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPaymentAmountByPurID($pid, $id)
    {
    	$this->db
    		->select('COALESCE(SUM(COALESCE(erp_payments.amount, 0)), 0) as paid')
    		->from('payments')
    		->where('payments.purchase_id', $pid)
    		->where('payments.id < ', $id);
    		
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	//To get value from purchase order to export excel/pdf
	public function getPurchaseOrderDetail($id) {
        $v1 = "(
			SELECT
				purchase_id,
				CASE
			WHEN sum(quantity) <= sum(quantity_po) THEN
				'received'
			ELSE
				CASE
			WHEN (
				sum(quantity_po) > 0 && sum(quantity_po) < sum(quantity)
			) THEN
				'partial'
			ELSE
				'ordered'
			END
			END AS `status`
			FROM
				erp_purchase_order_items
			GROUP BY
				purchase_id
		) AS erp_purchase_order_items ";
         $this->db
		->select("purchases_order.id as id,
						purchases_order.date,
						purchases_order.reference_no,
						purchases_request.reference_no as purchase_ref,
                        companies.company as project,
						purchases_order.supplier,
						erp_purchase_order_items.status,
						purchases_order.grand_total,						
						purchases_order.payment_status,
						purchases_order.order_status,
                        purchases_order.status as ordered")
				->join('purchases_request', 'purchases_order.request_id = purchases_request.id', 'left')
				->join($v1, 'purchase_order_items.purchase_id = erp_purchases_order.id')
                ->join('companies', 'purchases_order.biller_id = companies.id', 'left');
        $q = $this->db->get_where('purchases_order', array('purchases_order.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getPurchaseOrderDetailByWarehouse($id,$wh) {
	 	$v1 = "(
			SELECT
				purchase_id,
				CASE
			WHEN sum(quantity) <= sum(quantity_po) THEN
				'received'
			ELSE
				CASE
			WHEN (
				sum(quantity_po) > 0 && sum(quantity_po) < sum(quantity)
			) THEN
				'partial'
			ELSE
				'ordered'
			END
			END AS `status`
			FROM
				erp_purchase_order_items
			GROUP BY
				purchase_id
		) AS erp_purchase_order_items ";
        $this->db
			->select("purchases_order.id as id,
						purchases_order.date,
						purchases_order.reference_no,
						purchases_request.reference_no as purchase_ref,
                        companies.company as project,
						purchases_order.supplier,
						erp_purchase_order_items.status,
						purchases_order.grand_total,						
						purchases_order.payment_status,
						purchases_order.order_status,
                        purchases_order.status as ordered
					")
				->join('purchases_request', 'purchases_order.request_id = purchases_request.id', 'left')
				->join($v1, 'purchase_order_items.purchase_id = erp_purchases_order.id')
                ->join('companies', 'purchases_order.biller_id = companies.id', 'left')
            ->where_in('purchases_order.warehouse_id', $wh);
        $q = $this->db->get_where('purchases_order', array('purchases_order.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	// saram test purchases request
	
	public function getPurchaseOrderByID($id)
    {
    	$this->db->select('purchases_order.*, payment_term.description, warehouses.name AS Wname,companies.company,companies.name AS username, tax_rates.name AS tax_name, warehouses.name AS ware_name, purchases_request.reference_no as pr_referemce_no')
    			->join('payment_term','purchases_order.payment_term = payment_term.id','left')
    			->join('warehouses','purchases_order.warehouse_id= warehouses.id','left')
    			->join('companies','purchases_order.biller_id = companies.id','left')
    			->join('users','purchases_order.created_by = users.id','left')
    			->join('tax_rates','purchases_order.order_tax_id = tax_rates.id','left')
    			->join('purchases_request','purchases_order.request_id = purchases_request.id','left')
				
    			->where('purchases_order.id',$id);
        $q = $this->db->get('purchases_order');
        if ($q->num_rows() > 0) {
            //$this->erp->print_arrays($q->row());
            return $q->row();

        }
        return FALSE;
    }
	
	public function getPurchaseByRef($ref)
    {
        $q = $this->db->get_where('purchases', array('reference_no' => $ref), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getPurchaseIDByRef($ref)
    {
        $this->db->select('id', False);
        $q = $this->db->get_where('purchases', array('reference_no' => $ref), 1);
        if ($q->num_rows() > 0) {
            $q = $q->row();
            return $q->id;
        }
        return FALSE;
    }

    public function getProductOptionByID($id)
    {
        $q = $this->db->get_where('product_variants', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductWarehouseOptionQty($option_id, $warehouse_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            $nq = $option->quantity + $quantity;
            if ($this->db->update('warehouses_products_variants', array('quantity' => $nq), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                return TRUE;
            }
        } else {
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function resetProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('warehouses_products_variants', array('quantity' => $nq), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                return TRUE;
            }
        } else {
            $nq = 0 - $quantity;
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $nq))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getOverSoldCosting($product_id)
    {
        $q = $this->db->get_where('costing', array('overselling' => 1));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function countpurchase_request_items($id){
		$q = $this->db->get_where('erp_purchase_request_items',array('purchase_id'=>$id));
		if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function countpurchase_request_items_status($id){
		$q = $this->db->get_where('erp_purchase_request_items',array('purchase_id'=>$id,'create_status'=>'1'));
		if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function countpurchase_Order_items($id){
		$q = $this->db->get_where('erp_purchase_order_items',array('purchase_id'=>$id));
		if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function countpurchase_Order_items_status($id){
		$q = $this->db->get_where('erp_purchase_order_items',array('purchase_id'=>$id));
		if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function calQty_order($id){
		$q = $this->db->get_where('erp_purchase_request_items',array('id'=>$id),1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function calAddQty_order($id){
		$q = $this->db->get_where('erp_purchase_request_items',array('id'=>$id),1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function addPurchaseOrder($data, $items, $payment,$quote_id)
    {
		//$this->erp->print_arrays($data, $items, $quote_id);
		if ($this->db->insert('purchases_order', $data)) {
            $purchase_id = $this->db->insert_id();
			if ($this->site->getReference('poa',$data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('poa',$data['biller_id']);
			}
			
			$total_qty = 0;
            foreach ($items as $item) {
				$price = $item['price'];
				//unset($item['price']);
				$item['purchase_id'] = $purchase_id;
				if($item['option_id'] != 0) {
					$row = $this->getVariantQtyById($item['option_id']);
					$item['real_unit_cost'] = $item['real_unit_cost'] / $row->qty_unit;
				}
                
                if($item['type'] == 'service'){
                    unset($item['type']);
                    $item['quantity'] = 1;
                    $item['quantity_balance'] = 1;
                }
                unset($item['type']);
				
				/*$new_qty = $this->calAddQty_order($item['create_id']);
				if($new_qty->id == $item['create_id']){
					$total_qty = $item['quantity'];
					$total_qty = $total_qty + $new_qty->create_qty;
					$this->db->update('erp_purchase_request_items',array('create_qty'=>$total_qty),array('id'=>$item['create_id']));
					
					$qty_re = $this->calQty_order($item['create_id']);
					if($total_qty >= $qty_re->quantity){
					$this->db->update('erp_purchase_request_items',array('create_status'=>'1'),array('id'=>$item['create_id']));
					}
				}*/
				$this->db->update('erp_purchase_request_items',array('create_status'=>'1'),array('id'=> isset($item['create_id'])));
				
				$this->db->insert('purchase_order_items', $item);
				
            }
			if($quote_id){
				/*
				$q = $this->countpurchase_request_items($quote_id);
				$q_status = $this->countpurchase_request_items_status($quote_id);
				$count = count($q);
				$count_status = count($q_status);
				*/
				//if($count == $count_status){
					$this->db->update('erp_purchases_request',array('order_status'=>'completed'),array('id'=>$quote_id));
				//}
				/*else if($count != $count_status && $count_status>0){
					$this->db->update('erp_purchases_request',array('order_status'=>'partial'),array('id'=>$quote_id));
				}*/
			}
			
			/*if ($data['payment_status'] == 'partial' || $data['payment_status'] == 'paid' && !empty($payment)){
				$payment['purchase_id'] = $purchase_id;
				if ($payment['paid_by'] == 'gift_card') {
					$this->db->update('gift_cards', array('balance' => $payment['gc_balance']), array('card_no' => $payment['cc_no']));
					unset($payment['gc_balance']);
					$this->db->insert('payments', $payment);
				} else {
					$this->db->insert('payments', $payment);
				}
				if ($this->site->getReference('pp') == $payment['reference_no']) {
					$this->site->updateReference('pp');
				}
				
				if($payment['paid_by'] == 'deposit'){
					$deposit = $this->site->getDepositByCompanyID($data['supplier_id']);
					$deposit_balance = $deposit->deposit_amount;
					$deposit_balance = $deposit_balance - abs($payment['amount']);
					$this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $data['supplier_id']));
						/*$deposits = array(
							'date' => $data['date'],
							'company_id' => $data['supplier_id'],
							'amount' => -1 * $payment['amount'],
							'paid_by' => $payment['paid_by'],
							'note' => $data['note'],
							'created_by' => $this->session->userdata('user_id'),
							'biller_id' => $data['biller_id'],
							'po_id' => $purchase_id
							
						);
						$this->db->insert('deposits', $deposits);
					
					//$this->site->syncDeposits($data['supplier_id']);
				}
				//$this->site->syncSalePayments($sale_id);
			}*/
			
			return true;
        }else{
			return false;
		}
        
    }
	
	public function addPurchase($data, $items, $payment, $quote_id, $amount_o, $order_id = '')
    {
		unset($data['total_qty']);
		$stotal = $data['stotal'];
		//unset($data['stotal']);
 
		if ($this->db->insert('purchases', $data)) {
            $purchase_id = $this->db->insert_id();
			
			if ($this->site->getReference('po',$data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('po',$data['biller_id']);
			}
            
            foreach ($items as $item) {
				
				$price 			= $item['price'];
				$pur_order_id 	= $item['pur_order_id'];

				unset($item['price']);
				unset($item['pur_order_id']);

				$item['purchase_id'] = $purchase_id;
				
                if ($item['product_type'] == 'service') {
                    $item['quantity']			= 1;
                    $item['quantity_balance'] 	= 1;
					$item['net_shipping'] 		= 0;
                } else {
					$st_total 					= $item['subtotal'];
					$item['net_shipping'] 		= (($st_total * $data['shipping']) / $stotal);
				}
				
				if($data['status'] == 'received' || $data['status'] == 'partial' || $data['status'] == 'padding') {
					$this->db->update('products', array('cost' => $item['real_unit_cost'], 'price'=>$price), array('id' => $item['product_id']));
					$this->site->updateComboCost($item['product_code']);
				}
                if ($data['status'] == 'ordered' || $data['status'] == 'padding') {
					
                    $item['quantity_balance'] = 0;
					$this->db->insert('purchase_items', $item);
					$purchase_items_id = $this->db->insert_id();
					$this->site->updatePurItem($purchase_items_id);
					$pcost = $this->db->get_where('products',array('id'=>$item['product_id']),1)->row()->cost;
					//$this->db->update("inventory_valuation_details",array('cost'=>$pcost,'avg_cost'=>$pcost),array('field_id'=>$purchase_items_id));
					
				} else {
					
					$this->db->insert('purchase_items', $item);
					$purchase_items_id = $this->db->insert_id();
					$this->site->updatePurItem($purchase_items_id);
					$pcost = $this->db->get_where('products',array('id'=>$item['product_id']),1)->row()->cost;
					//$this->db->update("inventory_valuation_details",array('cost'=>$pcost,'avg_cost'=>$pcost),array('field_id'=>$purchase_items_id));
					
				}

				
				/* Prevent from ordered status */
				
                if($item['option_id'] != 0) {
					$pro_var = $this->site->getProductVariants($item['product_id']);
					foreach($pro_var as $vars){
						$this->db->set('quantity', ($item['quantity'] * $vars->qty_unit),false);
						$this->db->set('cost', ($item['real_unit_cost'] * $vars->qty_unit),false);
						$this->db->where(array('id' => $vars->id, 'product_id' => $item['product_id']));
						$this->db->update('product_variants');
					}
					
                }
				
				if($quote_id) {
					$this->db->set('quantity_received', $item['quantity'].' + quantity_received',false);
					$this->db->where(array('purchase_id' => $quote_id, 'product_id' => $item['product_id'], 'id' => $pur_order_id));
					$this->db->update('purchase_order_items');
				}
				
			}
			
			if($quote_id) {
				$qu_balance = $this->getPOstatusByID($quote_id);
				if($qu_balance->balance <= 0) {
					$status = array('order_status' => 'completed');
				}else if($qu_balance->balance > 0) {
					$status = array('order_status' => 'partial');
				}else {
					$status = array('order_status' => 'pending');
				}
				$this->db->update('purchases_order', $status, array('id' => $quote_id));
			}

			return true;
        }
        return false;
    }
	
	public function addSerial($serial){
		foreach ($serial as $item) {
			$sp = explode('|', $item['serial_number']);
			foreach($sp as $ser){
				if($ser != ""){
					$serials = array(
						'product_id'    => $item['product_id'],
						'serial_number' => $ser,
						'warehouse'     => $item['warehouse'],
						'biller_id'     => $item['biller_id'],
						'serial_status' => 1
					);
					$this->db->insert('serial', $serials);
				}
			}
		}
		return false;
	}
	
	public function addPuraddPurchaseImport($data, $items)
    {

		//$this->erp->print_arrays($data,$items);
		if ($this->db->insert('purchases', $data)) {
           
            $purchase_id = $this->db->insert_id();
            
            if ($this->site->getReference('po') == $data['reference_no']) {
                $this->site->updateReference('po');
            }
			
            foreach ($items as $item) {
				$item['purchase_id'] = $purchase_id;

                $this->db->insert('purchase_items', $item);
				
				/* Prevent from ordered status */
				/*
				if($data['status'] == 'received' || $data['status'] == 'pending'){
					$this->db->update('products', array('cost' => $item['net_unit_cost'], 'quantity' => $item['quantity']), array('id' => $item['product_id']));
				}
				*/

				/*
                if($item['option_id'] != 0) {
					$this->db->set('quantity', $item['quantity'].' * `qty_unit`');
					$this->db->set('cost', $item['real_unit_cost'].' / `qty_unit`');
                    //$this->db->update('product_variants', array('cost' => $item['real_unit_cost']), array('id' => $item['option_id'], 'product_id' => $item['product_id']));
					$this->db->where(array('id' => $item['option_id'], 'product_id' => $item['product_id']));
					$this->db->update('product_variants');
                }*/
				
            }

            if ($data['status'] == 'received') {
                $this->site->syncQuantity(NULL, $purchase_id);
            }
            return true;
        }
        return false;
    }
	
	public function addPurchaseImport($purchase)
    {
		foreach ($purchase as $pur) {
			$stotal = $pur['data']['stotal'];
			unset($pur['data']['stotal']);
			if ($this->db->insert('purchases', $pur['data'])) {
				$purchase_id = $this->db->insert_id();
				foreach($pur['item'] as $item){
					$avg_cost[] = array(
						'product_id' 	=> $item['product_id'],
						'quantity'   	=> $item['quantity_balance'],
						'subtotal'		=> $item['subtotal']
					);
				}
				
				$out  = array();
				foreach ($avg_cost as $key => $value){
					if (array_key_exists($value['product_id'], $out)){
						$out[$value['product_id']]['product_id'] = $value['product_id'];
						$out[$value['product_id']]['quantity'] 	+= $value['quantity'];
						$out[$value['product_id']]['subtotal'] 	+= $value['subtotal'];
					} else {
						$out[$value['product_id']] = array(
							'product_id' => $value['product_id'], 
							'quantity'   => $value['quantity'],
							'subtotal'   => $value['subtotal'],
						);
					}
				}
				
				$array_c = array_values($out);
				
				if($this->Settings->accounting_method == 2 || $pur['data']['shipping']){
					$c 	  = count($array_c);
					$avg  = array();
					$ship = array();
					
					for($i = 0; $i < $c; $i++){
						$costunit = $this->site->calculateAVGCost2017($array_c[$i]['product_id'], $pur['data']['shipping'], $array_c[$i]['quantity'], null, null, null, null, null, null, $array_c[$i]['subtotal'], $pur['data']['total']);
						$avg[$array_c[$i]['product_id']]  = $costunit['avgcost'];
						$ship[$array_c[$i]['product_id']] = $costunit['shipping_cost'];
					}
					$i = 0;
					foreach($pur['item'] as $p){
						$pur['item'][$i]['real_unit_cost'] = $avg[ $p['product_id'] ];
						$i++;
					}
				}
				//$this->erp->print_arrays($pur['item']);
				foreach ($pur['item'] as $item) {

					$item['purchase_id'] = $purchase_id;
					
					if ($item['type'] == 'service') {
						$item['quantity'] = 1;
						$item['quantity_balance'] = 1;
						$item['net_shipping'] = 0;
					} else {
						$st_total = $item['subtotal'];
						$item['net_shipping'] = (($st_total * $pur['data']['shipping']) / $stotal);
					}
					
					$this->db->update('products', array('cost' => $item['real_unit_cost']), array('id' => $item['product_id']));
					$this->site->updateComboCost($item['product_code']);
					
					if ($pur['data']['status'] == 'ordered' || $pur['data']['status'] == 'padding') {
						
						$item['quantity_balance'] = 0;
						$this->db->insert('purchase_items', $item);
						$purchase_items_id = $this->db->insert_id();
						$this->site->updatePurItem($purchase_items_id);
						
					} else {
						
						$this->db->insert('purchase_items', $item);
						$purchase_items_id = $this->db->insert_id();
						$this->site->updatePurItem($purchase_items_id);
						
					}
					
					/* Prevent from ordered status */
					
					if($item['option_id'] != 0) {
						$pro_var = $this->site->getProductVariants($item['product_id']);
						foreach($pro_var as $vars){
							$this->db->set('quantity', ($item['quantity'] * $vars->qty_unit),false);
							$this->db->set('cost', ($item['real_unit_cost'] * $vars->qty_unit),false);
							$this->db->where(array('id' => $vars->id, 'product_id' => $item['product_id']));
							$this->db->update('product_variants');
						}
						
					}
					
				}

				if ($pur['data']['status'] == 'received') {
					$this->site->syncQuantity(NULL, $purchase_id);
				}
				
			}
		}
		
		return false;
    }
	
	public function addPurchaseItemImport($items, $old_reference)
    {
        $purchase = $this->getPurchaseItemByRef($old_reference);
		if($items){
            foreach ($items as $item) {
				$item['purchase_id'] = $purchase->purchase_id;
                $this->db->insert('purchase_items', $item);
				
				$pur_update = array(
					'total' => $item['subtotal'] + $purchase->total,
					'grand_total' => $item['subtotal'] + $purchase->grand_total,
					'updated_by' => $this->session->userdata('user_id')
				);
				$this->db->update('purchases', $pur_update, array('id' => $item['purchase_id']));
				
				/* Prevent from ordered status */
				$this->db->update('products', array('cost' => $item['net_unit_cost'], 'quantity' => $item['quantity']), array('id' => $item['product_id']));

            }
			if ($data['status'] == 'received') {
                $this->site->syncQuantity(NULL, $purchase->purchase_id);
            }
            return true;
        }
        return false;
    }
	
	public function getRequestInBYID($id){	
		$q = $this->db->get_where('erp_purchases_request',array('id'=>$id),1);
		if ($q->num_rows() > 0) {
			if($this->db->update('erp_purchases_request',array('order_status'=>'pending'),array('id'=>$id))){
				$this->db->update('erp_purchase_request_items',array('create_status'=>'0'),array('purchase_id'=>$id));
			}	
		}
	}
	
	public function getRequestInBYIDUpdateQty($id){	
		$q = $this->db->get_where('erp_purchases_request',array('id'=>$id),1);
		if ($q->num_rows() > 0) {
			if($this->db->update('erp_purchases_request',array('order_status'=>'completed'),array('id'=>$id))){
				$this->db->update('erp_purchase_request_items',array('create_status'=>'1'),array('purchase_id'=>$id));
			}	
		}
	}
	
	public function getOrderIDInPurchaseRequest($id){
		$q = $this->db->get_where('erp_purchases_order',array('id'=>$id),1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function updatePurchaseOrder($id, $data, $items = array(), $payment,$amount_o)
    {
		//$this->erp->print_arrays($data, $items);
        $opurchase 	= $this->getPurchaseOrderByID($id);
        $oitems 	= $this->getAllPurchaseOrderItems($id);
		
		if($data['total'] <= 0){
			$ordd = $this->getOrderIDInPurchaseRequest($id);
			$this->getRequestInBYID($ordd->request_id);	
		}else{
			$ordd = $this->getOrderIDInPurchaseRequest($id);
			$this->getRequestInBYIDUpdateQty($ordd->request_id);	
		}
		$result_o = 0;
		unset($data['create_request']);
        if ($this->db->update('purchases_order', $data, array('id' => $id)) && $this->db->delete('purchase_order_items', array('purchase_id' => $id))) {
            $purchase_id = $id;
            foreach ($items as $item) {
                $item['purchase_id'] = $id;
				
				// Update price
				$price = $item['price'];
				//unset($item['price']);
				
				if($item['option_id'] != 0) {
					$row = $this->getVariantQtyById($item['option_id']);
					$item['real_unit_cost'] = $item['real_unit_cost'] / $row->qty_unit;
				}
                $product_detail = $this->getProductByID($item['product_id']);
                if($product_detail->type == 'service'){
                    $item['quantity'] = 1;
                    $item['quantity_balance'] = 1;
                }
				
                $this->db->insert('purchase_order_items', $item);
            }
			
			
			/*if ($data['payment_status'] == 'partial' || $data['payment_status'] == 'paid' && !empty($payment)) {
                $payment['purchase_id'] = $purchase_id;
                if ($payment['paid_by'] == 'gift_card') {
                    $this->db->update('gift_cards', array('balance' => $payment['gc_balance']), array('card_no' => $payment['cc_no']));
                    unset($payment['gc_balance']);
                    $this->db->insert('payments', $payment);
                } else {
                    $this->db->insert('payments', $payment);
                }
                if ($this->site->getReference('pp') == $payment['reference_no']) {
                    $this->site->updateReference('pp');
                }
                
                if($payment['paid_by'] == 'deposit'){
                    $deposit = $this->site->getDepositByCompanyID($data['supplier_id']);
                    $deposit_balance = $deposit->deposit_amount;
					if($amount_o > $data['paid']){
						$result_o = $amount_o - $data['paid'];
						$deposit_balance = $deposit_balance + $result_o;
					}else{
						$result_o = $data['paid'] - $amount_o;
						$deposit_balance = $deposit_balance - $result_o;
					}
                    
                    $this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $data['supplier_id']));
						//$old_deposite = $this->getDepositByPurchaseid($id);
						//if($old_deposite){
							//$this->db->update('deposits', array('amount' => (-1 * $payment['amount'])), array('po_id' => $id));
					/*	}else{
							
							$deposits = array(
								'date' => $data['date'],
								'company_id' => $data['supplier_id'],
								'amount' => -1 * $payment['amount'],
								'paid_by' => $payment['paid_by'],
								'note' => $data['note'],
								'created_by' => $this->session->userdata('user_id'),
								'biller_id' => $data['biller_id'],
								'po_id' => $id
							);
							$this->db->insert('deposits', $deposits);
						}
                    
                }
                //$this->site->syncSalePayments($sale_id);
            }*/
            return true;
        }else{
			 return false;
		}
       
    }
	
	public function getOrderBYID($id){	
		$q = $this->db->get_where('erp_purchases_order',array('id'=>$id),1);
		if ($q->num_rows() > 0) {
			if($this->db->update('erp_purchases_order',array('order_status'=>'pending'),array('id'=>$id))){
				$this->db->update('erp_purchase_order_items',array('create_order'=>'0'),array('purchase_id'=>$id));
			}	
		}
	}
	
	public function getOrderBYIDUpdateQty($id){	
		$q = $this->db->get_where('erp_purchases_order',array('id'=>$id),1);
		if ($q->num_rows() > 0) {
			if($this->db->update('erp_purchases_order',array('order_status'=>'completed'),array('id'=>$id))){
				$this->db->update('erp_purchase_order_items',array('create_order'=>'1'),array('purchase_id'=>$id));
			}	
		}
	}
	
	public function getOrderIDInPurchase($id){
		$q = $this->db->get_where('erp_purchases',array('id'=>$id),1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
    public function updatePurchase($id, $data, $items = array(), $payment,$purid,$amount_o,$paid_o)
    {
		$opurchase 	= $this->getPurchaseByID($id);
        $oitems 	= $this->getAllPurchaseItems($id);
		if($data['total'] <= 0){
			$ordd = $this->getOrderIDInPurchase($id);
			$this->getOrderBYID($ordd->order_id);	
		}else{
			$ordd = $this->getOrderIDInPurchase($id);
			$this->getOrderBYIDUpdateQty($ordd->order_id);	
		}
		
		$data['updated_count'] 	= $opurchase->updated_count + 1;
		
		$stotal = $data['stotal'];
		unset($data['stotal']);
		
		$result_o = 0;
        if ($this->db->update('purchases', $data, array('id' => $id)) && $this->db->delete('purchase_items', array('purchase_id' => $id))) {
            $purchase_id = $id;
			foreach($purid as $pu){
                $this->db->delete("inventory_valuation_details", array("field_id" => $pu['pur_id'], 'type' => 'PURCHASE'));
			}
            foreach ($items as $item) {
                $item['purchase_id'] = $id;
				
				$getproduct = $this->getProductByID($item['product_id']);
				
				if($getproduct->type == 'service'){
                    $item['quantity'] = 1;
                    $item['quantity_balance'] = 1;
					$item['net_shipping'] = 0;
                }else {
					$st_total = $item['subtotal'];
					$item['net_shipping'] = (($st_total * $data['shipping']) / $stotal);
				}
				
				// Update price
				$price 			= $item['price'];
				$old_quantity 	= $item['old_quantity'];
				unset($item['old_quantity']);
				unset($item['price']);
				
                $this->db->insert('purchase_items', $item);
				$purchase_items_id = $this->db->insert_id();
				$this->site->updatePurItem($purchase_items_id);

				if($data['status'] <= 'received' || $data['status'] == 'partial') {
					$this->db->update('products', array('cost' => $item['real_unit_cost'], 'price' => $price), array('id' => $item['product_id']));
					$this->site->updateComboCost($item['product_code']);
				}
				$pcost = $this->db->get_where('products',array('id'=>$item['product_id']),1)->row()->cost;
				//$va = $this->db->get_where('erp_product_variants',array('id'=>$item['option_id']),1)->row();
				//$this->db->update("inventory_valuation_details",array('cost'=>$pcost,'avg_cost'=>$pcost),array('field_id'=>$purchase_items_id));
				
				/*
					$update_stock = array ('quantity'=>$item['quantity_balance']);
					$this->db->where('code',$item_code)->update('products',$update_stock); // checking
				*/
				if($opurchase->order_id > 0) {
					$this->db->set('quantity_received', 'quantity_received - '. $old_quantity .' + '. $item['quantity'], false);
					$this->db->where(array('purchase_id' => $opurchase->order_id, 'product_id' => $item['product_id'], 'id' => $item['create_id'] ));
					$this->db->update('purchase_order_items');
				}
				
				if($item['option_id'] != 0) {
					$pro_var = $this->site->getProductVariants($item['product_id']);
					foreach($pro_var as $vars){
						$this->db->set('quantity', ($item['quantity'] * $vars->qty_unit),false);
						$this->db->set('cost', ($item['real_unit_cost'] * $vars->qty_unit),false);
						$this->db->where(array('id' => $vars->id, 'product_id' => $item['product_id']));
						$this->db->update('product_variants');
					}
					
                }
            }
			
			if($opurchase->order_id > 0) {
				$po_balance = $this->getPOstatusByID($opurchase->order_id);
				if($po_balance->balance == $po_balance->quantity) {
					$status = array('order_status' => 'pending');
				}else if($po_balance->balance > 0) {
					$status = array('order_status' => 'partial');
				}else {
					$status = array('order_status' => 'completed');
				}
				$this->db->update('purchases_order', $status, array('id' => $opurchase->order_id));
			}
			
            if ($opurchase->status == 'received' || $opurchase->status == 'partial') {
                $this->site->syncQuantity(NULL, NULL, $oitems);
            }
			
            if ($data['status'] == 'received' || $data['status'] == 'partial') {
                $this->site->syncQuantity(NULL, $id);
            }
			
			$this->update_quote_status($data['quote_id']);
			$this->update_quote_items($data['quote_id']);
            $this->site->syncPurchasePayments($id);
			
            return true;
        }
        return false;
    }
	
	public function update_quote_items($quote_id){
		$quantities = $this->getPIQuantiyByProducts($quote_id);
		
		foreach($quantities as $quantity){
			$qitems = array('quantity_received'=>$quantity->qty);
			$condition = array('quote_id'=>$quote_id,'product_id'=>$quantity->product_id);
			$this->db->update('erp_quote_items',$qitems,$condition);
		}
		
		
	}
	
	public function update_quote_status($quote_id){
		
		$quantity = $this->getPurchaseItemQuantiy($quote_id);
		$quote_quantity = $this->getQustatusByID($quote_id);
		if($quantity->qty >= $quote_quantity->quantity){
			$status = array('quote_status'=>'completed');
		}else if($quantity->qty !=0 && $quote_quantity->quantity - $quantity->qty > 0){
			$status = array('quote_status'=>'partial');
			
		}else{
			$status = array('quote_status'=>'pending');
		}
		$this->db->where('erp_quotes.id',$quote_id);
		$this->db->update('erp_quotes',$status);
		
	}

    public function updatePurchaseOpeningAP($id, $data)
    {
		$opurchase = $this->getPurchaseByID($id);
        $oitems = $this->getAllPurchaseItems($id);
		if($data['total'] <= 0){
			$ordd = $this->getOrderIDInPurchase($id);
			$this->getOrderBYID($ordd->order_id);	
		}else{
			$ordd = $this->getOrderIDInPurchase($id);
			$this->getOrderBYIDUpdateQty($ordd->order_id);	
		}

		
		$data['updated_count'] 	= $opurchase->updated_count + 1;
		
		$stotal = $data['stotal'];
		unset($data['stotal']);
		
		$result_o = 0;
        if ($this->db->update('purchases', $data, array('id' => $id)) && $this->db->delete('purchase_items', array('purchase_id' => $id))) {
            $purchase_id = $id;
            foreach ($items as $item) {
                $item['purchase_id'] = $id;
				
				$getproduct = $this->getProductByID($item['product_id']);
				
				if($getproduct->type == 'service'){
                    $item['quantity'] = 1;
                    $item['quantity_balance'] = 1;
					$item['net_shipping'] = 0;
                }else {
					$st_total = $item['subtotal'] - $item['item_tax'];
					$item['net_shipping'] = (($st_total * $data['shipping']) / $stotal);
				}
				
				// Update price
				$price = $item['price'];
				$old_quantity = $item['old_quantity'];
				unset($item['old_quantity']);
				unset($item['price']);
				
				//$item['net_shipping'] = $net_shipping;
				
                $this->db->insert('purchase_items', $item);
				$purchase_items_id = $this->db->insert_id();
				$this->site->updatePurItem($purchase_items_id);

				if($data['status'] <= 'received' || $data['status'] == 'partial') {
					$this->db->update('products', array('cost' => $item['real_unit_cost'], 'price' => $price), array('id' => $item['product_id']));
				}
				/*
					$update_stock = array ('quantity'=>$item['quantity_balance']);
					$this->db->where('code',$item_code)->update('products',$update_stock); // checking
				*/
				if($opurchase->order_id > 0) {
					$this->db->set('quantity_received', 'quantity_received - '. $old_quantity .' + '. $item['quantity'], false);
					$this->db->where(array('purchase_id' => $opurchase->order_id, 'product_id' => $item['product_id']));
					$this->db->update('purchase_order_items');
				}
            }
			
			if($opurchase->order_id > 0) {
				$po_balance = $this->getPOstatusByID($opurchase->order_id);
				if($po_balance->balance == $po_balance->quantity) {
					$status = array('order_status' => 'pending');
				}else if($po_balance->balance > 0) {
					$status = array('order_status' => 'partial');
				}else {
					$status = array('order_status' => 'completed');
				}
				$this->db->update('purchases_order', $status, array('id' => $opurchase->order_id));
			}
			
            if ($opurchase->status == 'received' || $opurchase->status == 'partial') {
                $this->site->syncQuantity(NULL, NULL, $oitems);
            }
            if ($data['status'] == 'received' || $data['status'] == 'partial') {
                $this->site->syncQuantity(NULL, $id);
            }
			
            $this->site->syncPurchasePayments($id);
			
            return true;
        }
        return false;
    }
	
	public function getDepositByPurchaseid($id)
	{
		$this->db->select('amount')
				 ->from('erp_deposits')
				 ->where('po_id', $id);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
    public function deletePurchase($id)
    {
        $purchase_items = $this->site->getAllPurchaseItems($id);

        if ($this->db->delete('purchase_items', array('purchase_id' => $id)) && $this->db->delete('purchases', array('id' => $id))) {
            $this->db->delete('payments', array('purchase_id' => $id));
            $this->site->syncQuantity(NULL, NULL, $purchase_items);
            return true;
        }
        return FALSE;
    }
	
	public function getPurchaseItemByRef($purchase_ref)
    {
        $this->db->select('purchase_items.id AS purchase_item_id, purchase_items.product_id ,purchases.id AS purchase_id, purchases.reference_no AS purchase_reference, purchases.total, purchases.grand_total');
        $this->db->join('purchase_items', 'purchase_items.purchase_id = purchases.id', 'inner');
        $q = $this->db->get_where('purchases', array('purchases.reference_no' => $purchase_ref));
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
    public function getWarehouseProductQuantity($warehouse_id, $product_id)
    {
        $q = $this->db->get_where('warehouses_products', array('warehouse_id' => $warehouse_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPurchasePayments($purchase_id)
    {
        $this->db->order_by('id', 'asc');
        $q = $this->db->get_where('payments', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getPaymentByPurchaseID($id)
    {
        $q = $this->db->get_where('payments', array('purchase_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
	
	public function getWarehouseIDByCode($code)
    {
		$this->db->select('id, code');
        $q = $this->db->get_where('warehouses', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            $q = $q->row();
			return $q->id;
        }

        return FALSE;
    }

    public function getPaymentByID($id)
    {
        $q = $this->db->get_where('payments', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
	
	public function getCurrentBalance($id, $pur_id)
	{
		$this->db->select('id, amount')
				 ->order_by('id', 'asc');
		$q = $this->db->get_where('payments', array('purchase_id' => $pur_id));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
	
    public function getPaymentsForPurchase($purchase_id)
    {
        $this->db->select('payments.date, payments.paid_by, payments.amount, payments.reference_no, users.first_name, users.last_name, type')
            ->join('users', 'users.id=payments.created_by', 'left');
        $q = $this->db->get_where('payments', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function addPayment($data = array(), $id, $suppliers_id, $reference_no_o)
    {
		$purchase_id = $data['purchase_id'];
		$payment = $this->site->getPaymentByPurchaseID($purchase_id);
		if ($this->db->insert('payments', $data)) {
			$payment_id = $this->db->insert_id();
			if($data['paid_by'] == "deposit"){
				if ($this->site->getReference('pay',$data['biller_id']) == $data['reference_no']) {
					$this->site->updateReference('pay',$data['biller_id']);
				}
			}else{
				if ($this->site->getReference('pp',$data['biller_id']) == $data['reference_no']) {
					$this->site->updateReference('pp',$data['biller_id']);
				}
			}
			
			if($data['paid_by'] == 'deposit'){
				$deposit = $this->site->getDepositByCompanyID($suppliers_id);
				$deposit_balance = $deposit->deposit_amount;
				$deposit_balance = $deposit_balance - abs($data['amount']);
						
				if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $suppliers_id))){
					$deposits = array(
						'date' 			=> $data['date'],
						'company_id' 	=> $suppliers_id,
						'reference' 	=> $reference_no_o,
						'amount' 		=> (-1*$data['amount']), //+ $amount_o) ,
						'paid_by' 		=> $data['paid_by'],
						'note' 			=> $data['note'],
						'created_by' 	=> $this->session->userdata('user_id'),
						'biller_id' 	=> $data['biller_id'],
						'po_id' 		=> $purchase_id,
						'payment_id'	=> $payment_id
						
					);
					$this->db->insert('deposits', $deposits);
				}
			}
			
			$this->site->syncPurchasePayments($data['purchase_id']);
			return true;
		}

        return false;
    }
	
	public function addPaymentMulti($data = array(), $id, $suppliers_id, $reference_no_o)
    {
		$purchase_id = $data['purchase_id'];
		$payment = $this->site->getPaymentByPurchaseID($purchase_id);
		if ($this->db->insert('payments', $data)) {
			$payment_id = $this->db->insert_id();
			if($data['paid_by'] == 'deposit'){
				$deposit = $this->site->getDepositByCompanyID($suppliers_id);
				$deposit_balance = $deposit->deposit_amount;
				$deposit_balance = $deposit_balance - abs($data['amount']);
						
				if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $suppliers_id))){
					$deposits = array(
						'date' => $data['date'],
						'company_id' => $suppliers_id,
						'reference' => $reference_no_o,
						'amount' => (-1*$data['amount']), //+ $amount_o) ,
						'paid_by' => $data['paid_by'],
						'note' => $data['note'],
						'created_by' => $this->session->userdata('user_id'),
						'biller_id' => $data['biller_id'],
						'po_id' => $purchase_id,
						'payment_id'=>$payment_id
						
					);
					$this->db->insert('deposits', $deposits);
				}
			}
			$this->site->syncPurchasePayments($data['purchase_id']);
			return true;
		}

        return false;
    }

    public function updatePayment($id, $data = array(),$paid_2,$amount_2,$suppliers_id,$reference_no_o)
    {
        if ($this->db->update('payments', $data, array('id' => $id))) {
			if ($this->site->getReference('po') == $data['reference_no']) {
                $this->site->updateReference('po');
            }
			if($paid_2 == "deposit" && $data['paid_by'] =="cash"){
				$deposit = $this->site->getDepositByCompanyID($suppliers_id);
				$deposit_balance = $deposit->deposit_amount;
				$deposit_balance = $deposit_balance + abs($amount_2);
				
				if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $suppliers_id))){
					$this->db->delete('deposits', array('payment_id' => $id,'po_id' => $data['purchase_id']));
				}
			}
			else if($paid_2 == "cash" && $data['paid_by'] =="deposit"){
				$result_o = 0;
				$deposit = $this->site->getDepositByCompanyID($suppliers_id);
				$deposit_balance = $deposit->deposit_amount;
				//$deposit_balance = $deposit_balance - abs($data['amount']);
				/*if($amount_2 > $data['amount']){
					$result_o = $amount_2 - $data['amount'];
					$deposit_balance = $deposit_balance + $result_o;
				}else{
					$result_o = $data['amount'] - $amount_2;
					$deposit_balance = $deposit_balance - $result_o;
				}*/
				$deposit_balance = $deposit_balance - $data['amount'];
				if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $suppliers_id))){
					$conf = $this->site->getDepositByPaymentID($id,$data['purchase_id']);
					if($conf){	
						$this->db->update('deposits', array('amount' => (-1 * $data['amount'])), array('payment_id' => $id,'po_id' => $data['purchase_id']));
					}else{
						$deposits = array(
						'date' => $data['date'],
						'company_id' => $suppliers_id,
						'reference' => $reference_no_o,
						'amount' => (-1*$data['amount']), //+ $amount_o) ,
						'paid_by' => $data['paid_by'],
						'note' => $data['note'],
						'created_by' => $this->session->userdata('user_id'),
						'biller_id' => $this->session->userdata('biller_id'),
						'po_id' => $data['purchase_id'],
						'payment_id'=>$id
						);
						$this->db->insert('deposits', $deposits);
					}
				}
			}else if($paid_2 == "deposit" && $data['paid_by'] =="deposit"){
				$result_o = 0;
				$deposit = $this->site->getDepositByCompanyID($suppliers_id);
				$deposit_balance = $deposit->deposit_amount;
				//$deposit_balance = $deposit_balance - abs($data['amount']);
				if($amount_2 > $data['amount']){
					$result_o = $amount_2 - $data['amount'];
					$deposit_balance = $deposit_balance + $result_o;
				}else{
					$result_o = $data['amount'] - $amount_2;
					$deposit_balance = $deposit_balance - $result_o;
				}
				if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $suppliers_id))){
					
					$this->db->update('deposits', array('amount' => (-1 * $data['amount'])), array('payment_id' => $id,'po_id' => $data['purchase_id']));
					
				}
			}
            $this->site->syncPurchasePayments($data['purchase_id']);
            return true;
        }
        return false;
    }

    public function deletePayment($id)
    {
        $opay = $this->getPaymentByID($id);
        if ($this->db->delete('payments', array('id' => $id))) {
			if($opay->paid_by =="deposit"){
				$this->db->delete('deposits', array('payment_id' => $id,'po_id' => $opay->purchase_id));
			}
            $this->site->syncPurchasePayments($opay->purchase_id);
            return true;
        }
        return FALSE;
    }

    public function getProductOptions($product_id)
    {
		$this->db->order_by('product_variants.qty_unit', 'DESC');
        $q = $this->db->get_where('product_variants', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductVariantByName($name, $product_id)
    {
        $q = $this->db->get_where('product_variants', array('name' => $name, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getExpenseByID($id)
    {
        $q = $this->db->get_where('expenses', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getExpenses($id)
    {
		$this->db
				->select($this->db->dbprefix('expenses') . ".id as id, date, reference, gl_trans.narrative ,expenses.amount, expenses.note, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name) as user, attachment", false)
				->from('expenses')
				->join('users', 'users.id=expenses.created_by', 'left')
				->join('gl_trans', 'gl_trans.account_code = expenses.account_code', 'left')
				->where('expenses.id', $id)
				->group_by('expenses.id');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addExpense($data = array(), $payment = array())
    {
		
        if ($this->db->insert('expenses', $data)) {
            $expense_id = $this->db->insert_id();
            if ($this->site->getReference('ex',$data['biller_id']) == $data['reference']) {
                $this->site->updateReference('ex',$data['biller_id']);
            }
            if($payment){
                $payment['expense_id'] = $expense_id;
                $payment['reference_no'] = $this->site->getReference('pay');
                $this->db->insert('payments', $payment);
                if ($this->site->getReference('pay') == $payment['reference_no']) {
                    $this->site->updateReference('pay');
                }
            }
            return true;
        }
        return false;
    }

    public function updateExpense($id, $data = array(), $data_payment = array())
    {
        if ($this->db->update('expenses', $data, array('id' => $id))) {
            $this->db->update('payments', $data_payment, array('expense_id' => $id));
            return true;
        }
        return false;
    }

    public function deleteExpense($id)
    {
        if ($this->db->delete('expenses', array('id' => $id))) {
            $this->db->update('payments', array('amount' => 0), array('expense_id' => $id));
            return true;
        }
        return FALSE;
    }
	
	public function check_expense_reference($ref){
		$this->db->where('reference', $ref);
		$query = $this->db->get('expenses');
		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

    public function getQuoteByID($id)
    {
        $q = $this->db->get_where('quotes', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllQuoteItems($quote_id)
    {
		
		$this->db->where('(erp_quote_items.quantity - erp_quote_items.quantity_received)>',0);
        $q = $this->db->get_where('quote_items', array('quote_id' => $quote_id));
		
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getStaff()
    {
        if ($this->Admin) {
            $this->db->where('group_id !=', 1);
        }
        $this->db->where('group_id !=', 3)->where('group_id !=', 4);
        $q = $this->db->get('users');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getPurchasesReferences($term, $limit = 10)
    {
        $this->db->select('reference_no');
        $this->db->where("(reference_no LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    
    public function getPurchaseItemByRefPID($purchase_ref, $product_id)
    {
        $this->db->select('purchase_items.quantity');
        $this->db->join('purchase_items', 'purchase_items.purchase_id = purchases.id', 'inner');
        $q = $this->db->get_where('purchases', array('purchases.reference_no' => $purchase_ref, 'purchase_items.product_id' => $product_id));
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getCombinePaymentById($id)
    {
		$this->db->select('id, date, reference_no, supplier, status, grand_total, paid, (grand_total-paid) as balance, payment_status');
		$this->db->from('purchases');
		$this->db->where_in('id', $id);
        $q = $this->db->get();
         if ($q->num_rows() > 0) {
            return $q;
        }
		return FALSE;
    }

	public function getSupplierSuggestions($term, $limit = 10)
    {
        $this->db->select("id, CONCAT(company, ' (', name, ')') as text", FALSE);
        $this->db->where(" (id LIKE '%" . $term . "%' OR name LIKE '%" . $term . "%' OR company LIKE '%" . $term . "%' OR email LIKE '%" . $term . "%' OR phone LIKE '%" . $term . "%') ");
        $q = $this->db->get_where('companies', array('group_name' => 'supplier'), $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    
    public function getReturnPurchaseByPurchaseID($purchase_id)
    {
        $this->db->select('SUM(quantity_balance) AS quantity_balance')
            ->join('purchase_items', 'purchase_items.purchase_id = purchases.id', 'left');
        $q = $this->db->get_where('purchases', array('purchases.id' => $purchase_id), 1);
        if ($q->num_rows() > 0) {
            $q = $q->row();
            echo $q->quantity_balance;
            exit();
        }
    }
	 public function getReturnPurchase($id,$ware_id,$returnp)
    {
        $this->db
        ->select($this->db->dbprefix('return_purchases') . ".date as date, " . $this->db->dbprefix('return_purchases') . ".reference_no as ref, " . $this->db->dbprefix('purchases') . ".reference_no as sal_ref, " . $this->db->dbprefix('return_purchases') . ".supplier, " . $this->db->dbprefix('return_purchases') . ".surcharge, " . $this->db->dbprefix('return_purchases') . ".grand_total, " . $this->db->dbprefix('return_purchases') . ".id as id,". $this->db->dbprefix('purchases') . ".paid")
        ->join('purchases', 'purchases.id=return_purchases.purchase_id', 'left')
        ->from('return_purchases')
        ->where('return_purchases.id = '.$id)
        ->group_by('return_purchases.id');
        if( $ware_id){
        $this->db->where('return_purchases.warehouse_id',$ware_id);
        }
        if ($returnp) {
            $this->db->where('sales.id', $sales);
        }

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getKHM(){
		$q = $this->db->get_where('currencies', array('code'=> 'KHM'), 1);
		if($q->num_rows() > 0){
			$q = $q->row();
            return $q->rate;
		}
	}
	public function getReturnByID($id)
    {
        $q = $this->db->get_where('return_purchases', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getAllPurchaseReturnItems($return_id)
    {
        $this->db->select('return_purchase_items.*, products.details as details, product_variants.name as variant,products.price as peroduct_net_unit_price')
            ->join('products', 'products.id=return_purchase_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=return_purchase_items.option_id', 'left')
            ->group_by('return_purchase_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('return_purchase_items', array('return_id' => $return_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getInvoiceByID($id)
    {
        $q = $this->db->get_where('purchases', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function add_deposit($deposit){
		$this->db->insert('deposits',$deposit); 
		if($this->db->affected_rows()>0){
			return true;
		}
		return false; 
	}
	
	public function getUnits(){
		$this->db->select()
				 ->from('units');
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}
	
	public function getSubCategories()
    {
        $this->db->select('id as id, name as text');
        $q = $this->db->get("subcategories");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }
	
	public function getPOstatusByID($id) {
		$q = $this->db->select('SUM(quantity - quantity_received) as balance, SUM(quantity) as quantity')
				 ->get_where('purchase_order_items', array('purchase_id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}

	public function getCustomerReferenceNo() {
		$this->db->select('id, reference_no as name');
		$q = $this->db->get('sales');
		if ($q->num_rows() > 0) {
			return $q->result();
		}
		return false;
	}
	
	public function getQustatusByID($id) {
		$q = $this->db->select('SUM(quantity - quantity_received) as balance,SUM(COALESCE(quantity,0)) as quantity')
				 ->get_where('erp_quote_items', array('quote_id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function getPurchaseItemQuantiy($quote_id){
		$this->db->select("SUM(COALESCE(erp_purchase_items.quantity,0)) as qty");
		$this->db->from("erp_purchases");
		$this->db->where("erp_purchases.quote_id",$quote_id);
		$this->db->join("erp_purchase_items","erp_purchases.id = erp_purchase_items.purchase_id");
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	public function getPIQuantiyByProducts($quote_id){
		$this->db->select("SUM(COALESCE(erp_purchase_items.quantity,0)) as qty,erp_purchase_items.product_id");
		$this->db->from("erp_purchases");
		$this->db->where("erp_purchases.quote_id",$quote_id);
		$this->db->group_by("erp_purchase_items.product_id");
		$this->db->join("erp_purchase_items","erp_purchases.id = erp_purchase_items.purchase_id");
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getAllPurchasePayment($id){
		$this->db->select("erp_purchases.*, erp_payments.reference_no as paymemt_no,erp_payments.amount,erp_payments.date as payment_date");
		$this->db->from("erp_purchases");
		$this->db->join("erp_payments","erp_payments.purchase_id = erp_purchases.id","left");
		$this->db->where("erp_purchases.id",$id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getAllPaymentByReference_no($reference_no){
		$this->db->select("erp_purchases.*, erp_payments.reference_no as paymemt_no,erp_payments.amount,erp_payments.date as payment_date");
		$this->db->from("erp_purchases");
		$this->db->join("erp_payments","erp_payments.purchase_id = erp_purchases.id","left");
		$this->db->where("erp_payments.reference_no",$reference_no);
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getAllCompaniesByID($biller_id) {
        $this->db->select('companies.*')
                 ->from('companies')
                 ->where_in("id", JSON_decode($biller_id));

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }

    public function getBiller($id=null) {
    	$this->db->select("*");
		$this->db->from("erp_companies");
		$this->db->where("erp_companies.id",$id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
    }

}
