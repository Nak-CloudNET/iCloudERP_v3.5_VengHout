<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function getPaymentReferenceBySaleRef($sale_ref)
	{
		$q = $this->db->select('payments.reference_no as paymentRef')
					->from('sales')
					->join('payments', 'sales.id = payments.sale_id')
					->where('sales.reference_no', $sale_ref)
					->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function deleteChartAccount($id)
	{
		$q = $this->db->delete('gl_charts', array('accountcode' => $id));
		if($q){
			return true;
		} else{
			return false;
		}
	}

    public function getProductNames($term, $warehouse_id, $limit = 5)
    {
        $this->db->select('products.id, code, name, warehouses_products.quantity, cost, tax_rate, type, tax_method')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        if ($this->Settings->overselling) {
            $this->db->where("type = 'standard' AND (name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        } else {
            $this->db->where("type = 'standard' AND warehouses_products.warehouse_id = '" . $warehouse_id . "' AND warehouses_products.quantity > 0 AND "
                . "(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
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
	
	public function getAlltypes()
	{
		$q = $this->db->query("SELECT * from erp_groups WHERE erp_groups.id IN (3,4)");
		
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getAllcharts() 
	{
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
	public function getWHProduct($id)
    {
        $this->db->select('products.id, code, name, warehouses_products.quantity, cost, tax_rate')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        $q = $this->db->get_where('products', array('warehouses_products.product_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function addTransfer($data = array(), $items = array())
    {
        $status = $data['status'];
        if ($this->db->insert('transfers', $data)) {
            $transfer_id = $this->db->insert_id();
            if ($this->site->getReference('to') == $data['transfer_no']) {
                $this->site->updateReference('to');
            }
            foreach ($items as $item) {
                $item['transfer_id'] = $transfer_id;
                if ($status == 'completed') {
                    $item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['to_warehouse_id'];
                    $item['status'] = 'received';
                    $this->db->insert('purchase_items', $item);
                } else {
                    $this->db->insert('transfer_items', $item);
                }

                if ($status == 'sent' || $status == 'completed') {
                    $this->syncTransderdItem($item['product_id'], $data['from_warehouse_id'], $item['quantity'], $item['option_id']);
                }
            }

            return true;
        }
        return false;
    }

    public function updateTransfer($id, $data = array(), $items = array())
    {
        $ostatus = $this->resetTransferActions($id);
        $status = $data['status'];
        if ($this->db->update('transfers', $data, array('id' => $id))) {
            $tbl = $ostatus == 'completed' ? 'purchase_items' : 'transfer_items';
            $this->db->delete($tbl, array('transfer_id' => $id));

            foreach ($items as $item) {
                $item['transfer_id'] = $id;
                if ($status == 'completed') {
                    $item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['to_warehouse_id'];
                    $item['status'] = 'received';
                    $this->db->insert('purchase_items', $item);
                } else {
                    $this->db->insert('transfer_items', $item);
                }

                $status = $data['status'];
                if ($status == 'sent' || $status == 'completed') {
                    $this->syncTransderdItem($item['product_id'], $data['from_warehouse_id'], $item['quantity'], $item['option_id']);
                }
            }
            return true;
        }
        return false;
    }

    public function getProductWarehouseOptionQty($option_id, $warehouse_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductByCategoryID($id)
    {

        $q = $this->db->get_where('products', array('category_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return true;
        }

        return FALSE;
    }
	
	public function getAccountSections()
	{
		$this->db->select("sectionid,sectionname");
		$section = $this->db->get("gl_sections");
		if($section->num_rows() > 0){
			return $section->result_array();	
		}
		return false;
	}
	
	public function getSubAccounts($section_code)
	{
		$this->db->select('accountcode as id, accountname as text');
        $q = $this->db->get_where("gl_charts", array('sectionid' => $section_code));
        if ($q->num_rows() > 0) {
			$data[] = array('id' => '0', 'text' => 'None');
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
	}
	
	
	public function getpeoplebytype($company)
	{
		if($company == 'emp'){
			$this->db->select("name as id, name as text");
			$q = $this->db->get_where("companies", array('group_name' => 'employee'));
		}else{
			$this->db->select("name as id,CONCAT(code,'-',name) as text");
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
	
	
	public function addChartAccount($data)
	{
		//$this->erp->print_arrays($data);
		if ($this->db->insert('gl_charts', $data)) {
            return true;
        }
        return false;
	}
	
	public function updateChartAccount($id,$data)
	{
		//$this->erp->print_arrays($data);
		$this->db->where('accountcode', $id);
		$q=$this->db->update('gl_charts', $data);
        if ($q) {
            return true;
        }
        return false;
	}

    public function getProductQuantity($product_id, $warehouse = DEFAULT_WAREHOUSE)
    {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }
        return FALSE;
    }

    public function insertQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity))) {
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
	
	public function updateSetting($data)
	{
		if ($this->db->update('account_settings', $data)) {
            return true;
        }
        return false;
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
	
	public function getChartAccountByID($id)
	{
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
	
	public function getAllChartAccount()
	{
		$this->db->select('gl_charts.accountcode,gl_charts.accountname,gl_charts.parent_acc,gl_charts.sectionid');
		$this->db->from('gl_charts');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return FALSE;
	}
	
	public function getAllChartAccountIn($section_id)
	{
		$q = $this->db->query("SELECT
									accountcode,
									accountname,
									parent_acc,
									sectionid
								FROM
									erp_gl_charts
								WHERE
									sectionid IN ($section_id)");
		
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function getCustomers()
    {
        $q = $this->db->query("SELECT
									id, company
								FROM
									erp_companies
								WHERE
									group_name = 'biller'
								");
		
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
	public function getAllChartAccounts()
	{
		$q = $this->db->query("SELECT
									accountcode,
									accountname,
									parent_acc,
									sectionid
								FROM
									erp_gl_charts
								");
		
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function getBillers()
    {
		$this->db->select('company');
		$this->db->from('companies');
		$this->db->join('account_settings', 'account_settings.biller_id=companies.id');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getBillersArray($id)
    {
		$this->db->where_in('id', $id);
		$q = $this->db->get_where('companiess', array('group_name' => 'biller'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getSalename()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_sale=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getsalediscount()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_sale_discount=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getsale_tax()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_sale_tax=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getreceivable()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_receivable=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getpurchases()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_purchase=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getGLYearMonth()
	{
		$query = $this->db->select("MIN(YEAR(tran_date)) AS min_year, MIN(MONTH(tran_date)) AS min_month")
				->get('gl_trans');
		if($query->num_rows() > 0){
			return $query->row();
		}
		return false;
	}
	
	
	public function getpurchase_tax()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_purchase_tax=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	
	public function getpurchasediscount()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_purchase_discount=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getpayable()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_payable=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_sale_freights()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_sale_freight=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_purchase_freights()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_purchase_freight=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
	public function getstocks()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_stock=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getstock_adjust()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_stock_adjust=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_cost()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_cost=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getpayrolls()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_payroll=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_cash()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_cash=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getcredit_card()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_credit_card=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_sale_deposit()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_sale_deposit=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_purchase_deposit()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_purchase_deposit=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getcheque()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_cheque=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_loan()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_loan=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_retained_earning()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_retained_earnings=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_cost_of_variance()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_cost_variant=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getInterestIncome()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_interest_income=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getTransferOwner()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_transfer_owner=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getgift_card()
    {
		$this->db->select('accountname');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_gift_card=gl_charts.accountcode');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllChartAccountBank(){
		$this->db->select('gl_charts.accountcode,gl_charts.accountname,gl_charts.parent_acc,gl_charts.sectionid');
		$this->db->from('gl_charts');
		$this->db->where('bank', 1);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }

        return FALSE;
	}
	
	public function updateJournal($rows, $old_reference_no = NULL) {
		//$ids = '';
		//$ref = '';
		//$this->erp->print_arrays($rows);
		foreach($rows as $data){
			$gl_chart = $this->getChartAccountByID($data['account_code']);	
			if($gl_chart > 0){
				$data['sectionid'] = $gl_chart->sectionid;
				$data['narrative'] = $gl_chart->accountname;
			}
			//$ref = $data['reference_no'];
			
			if($data['tran_id'] != 0){
				$this->db->where('tran_id' , $data['tran_id']);
				$q = $this->db->update('gl_trans', $data);
				if ($q) {
					if($gl_chart->bank == 1){
						$payment = array(
							'date' => $data['tran_date'],
							'biller_id' => $data['biller_id'],
							'transaction_id' => $data['tran_id'],
							'amount' => $data['amount'],
							'reference_no' => $data['reference_no'],
							'paid_by' => $data['narrative'],
							'note' => $data['description'],
							'bank_account' => $data['bank_account'],
							'type' => 'received',
							'created_by' => $this->session->userdata('user_id')
						);
						$this->db->update('payments', $payment, array('transaction_id' => $data['tran_id']));
					}
					//$ids .= $data['tran_id'] . ',';
				}
			}else{
				if($this->db->insert('gl_trans', $data)) {
					$tran_id = $this->db->insert_id();
					if($gl_chart->bank == 1){
						$payment = array(
							'date' => $data['tran_date'],
							'biller_id' => $data['biller_id'],
							'transaction_id' => $tran_id,
							'amount' => $data['amount'],
							'reference_no' => $data['reference_no'],
							'paid_by' => $data['narrative'],
							'note' => $data['description'],
							'type' => 'received',
							'bank_account' => $data['account_code'],
							'created_by' => $this->session->userdata('user_id')
						);
						$this->db->insert('payments', $payment);
					}
					//$ids .= $tran_id . ',';
				}
			}
		}
	//	$ids = rtrim($ids, ',');
	//	$ids_arr = explode(',', $ids);
	//	$this->db->where_not_in('tran_id', $ids_arr);
	//	$this->db->where('reference_no', $ref);
	//	$this->db->delete('gl_trans'); 
	}
	
	public function addJournal($rows){
		foreach($rows as $data){
			$gl_chart = $this->getChartAccountByID($data['account_code']);
			if($gl_chart > 0){
				$data['sectionid'] = $gl_chart->sectionid;
				$data['narrative'] = $gl_chart->accountname;
			}
			
			if ($this->db->insert('gl_trans', $data)) {
				$tran_id = $this->db->insert_id();
				
				if ($gl_chart->bank == 1) {
					$payment = array(
						'date' 			=> $data['tran_date'],
						'biller_id' 	=> $data['biller_id'],
						'transaction_id'=> $tran_id,
						'amount' 		=> $data['amount'],
						'reference_no'	=> $data['reference_no'],
						'paid_by' 		=> $data['narrative'],
						'note' 			=> $data['description'],
						'bank_account' 	=> $data['account_code'],
						'type' 			=> 'received',
						'created_by' 	=> $this->session->userdata('user_id')
					);

					$this->db->insert('payments', $payment);
				}
				
				if ($this->site->getReference('jr',$data['biller_id']) == $data['reference_no']) {
					$this->site->updateReference('jr',$data['biller_id']);
				}
				
			}
		}
	}
	
	public function getJournalByTranNoTranID($tran_id, $tran_no){
		$q = $this->db->get_where('gl_trans', array('tran_id' => $tran_id, 'tran_no' => $tran_no), 1);
		if($q->num_rows() > 0){
			$row = $q->row();
			return $row->tr;
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
	
	public function getTranNoByRef($ref){
		$this->db->select('tran_no');
		$this->db->where('reference_no', $ref);
		$q = $this->db->get('gl_trans');
		if($q->num_rows() > 0){
			$row = $q->row();
			return $row->tran_no;
		}
		return FALSE;
	}
	
	public function getTranTypeByRef($ref){
		$this->db->select('tran_type');
		$this->db->where('reference_no', $ref);
		$q = $this->db->get('gl_trans');
		if($q->num_rows() > 0){
			$row = $q->row();
			return $row->tran_type;
		}
		return FALSE;
	}
	
	public function deleteJournalByRef($ref){
		$q = $this->db->delete('gl_trans', array('reference_no' => $ref));
		if($q){
			return true;
		}
		return false;
	}
	
	public function getJournalByRef($ref){
		$this->db->select('gl_trans.*, (IF(erp_gl_trans.amount > 0, erp_gl_trans.amount, null)) as debit, 
							(IF(erp_gl_trans.amount < 0, abs(erp_gl_trans.amount), null)) as credit');
		$q = $this->db->get_where('gl_trans', array('reference_no' => $ref));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
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
	
    public function getTransferByID($id)
    {

        $q = $this->db->get_where('transfers', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllTransferItems($transfer_id, $status)
    {
        if ($status == 'completed') {
            $this->db->select('purchase_items.*, product_variants.name as variant')
                ->from('purchase_items')
                ->join('product_variants', 'product_variants.id=purchase_items.option_id', 'left')
                ->group_by('purchase_items.id')
                ->where('transfer_id', $transfer_id);
        } else {
            $this->db->select('transfer_items.*, product_variants.name as variant')
                ->from('transfer_items')
                ->join('product_variants', 'product_variants.id=transfer_items.option_id', 'left')
                ->group_by('transfer_items.id')
                ->where('transfer_id', $transfer_id);
        }
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getWarehouseProduct($warehouse_id, $product_id, $variant_id)
    {
        if ($variant_id) {
            $data = $this->getProductWarehouseOptionQty($variant_id, $warehouse_id);
            return $data;
        } else {
            $data = $this->getWarehouseProductQuantity($warehouse_id, $product_id);
            return $data;
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

    public function resetTransferActions($id)
    {
        $otransfer = $this->transfers_model->getTransferByID($id);
        $oitems = $this->transfers_model->getAllTransferItems($id, $otransfer->status);
        $ostatus = $otransfer->status;
        if ($ostatus == 'sent' ||$ostatus == 'completed') {
            // $this->db->update('purchase_items', array('warehouse_id' => $otransfer->from_warehouse_id, 'transfer_id' => NULL), array('transfer_id' => $otransfer->id));
            foreach ($oitems as $item) {
                $option_id = (isset($item->option_id) && ! empty($item->option_id)) ? $item->option_id : NULL;
                $clause = array('purchase_id' => NULL, 'transfer_id' => NULL, 'product_id' => $item->product_id, 'warehouse_id' => $otransfer->from_warehouse_id, 'option_id' => $option_id);
                $pi = $this->site->getPurchasedItem(array('id' => $item->id));
                if ($ppi = $this->site->getPurchasedItem($clause)) {
                    $quantity_balance = $ppi->quantity_balance + $item->quantity;
                    $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), $clause);
                } else {
                    $clause['quantity'] = $item->quantity;
                    $clause['item_tax'] = 0;
                    $clause['quantity_balance'] = $item->quantity;
                    $this->db->insert('purchase_items', $clause);
                }
            }
        }
        return $ostatus;
    }

    public function deleteTransfer($id)
    {
        $ostatus = $this->resetTransferActions($id);
        $oitems = $this->transfers_model->getAllTransferItems($id, $ostatus);
        $tbl = $ostatus == 'completed' ? 'purchase_items' : 'transfer_items';
        if ($this->db->delete('transfers', array('id' => $id)) && $this->db->delete($tbl, array('transfer_id' => $id))) {
            foreach ($oitems as $item) {
                $this->site->syncQuantity(NULL, NULL, NULL, $item->product_id);
            }
            return true;
        }
        return FALSE;
    }

    public function getProductOptions($product_id, $warehouse_id, $zero_check = TRUE)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.cost as cost, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            ->where('product_variants.product_id', $product_id)
            ->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            ->group_by('product_variants.id');
        if ($zero_check) {
            $this->db->where('warehouses_products_variants.quantity >', 0);
        }
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductComboItems($pid, $warehouse_id)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name, warehouses_products.quantity as quantity')
            ->join('products', 'products.code=combo_items.item_code', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->where('warehouses_products.warehouse_id', $warehouse_id)
            ->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('combo_items.product_id' => $pid));
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

    public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL) 
	{
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, unit_cost, item_tax');
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

    public function syncTransderdItem($product_id, $warehouse_id, $quantity, $option_id = NULL)
    {
        if ($pis = $this->getPurchasedItems($product_id, $warehouse_id, $option_id)) {
            $balance_qty = $quantity;
            foreach ($pis as $pi) {
                if ($balance_qty <= $quantity && $quantity > 0) {
                    if ($pi->quantity_balance >= $quantity) {
                        $balance_qty = $pi->quantity_balance - $quantity;
                        $this->db->update('purchase_items', array('quantity_balance' => $balance_qty), array('id' => $pi->id));
                        $quantity = 0;
                    } elseif ($quantity > 0) {
                        $quantity = $quantity - $pi->quantity_balance;
                        $balance_qty = $quantity;
                        $this->db->update('purchase_items', array('quantity_balance' => 0), array('id' => $pi->id));
                    }
                }
                if ($quantity == 0) { break; }
            }
        } else {
            $clause = array('purchase_id' => NULL, 'transfer_id' => NULL, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'option_id' => $option_id);
            if ($pi = $this->site->getPurchasedItem($clause)) {
                $quantity_balance = $pi->quantity_balance - $quantity;
                $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), $clause);
            } else {
                $clause['quantity'] = 0;
                $clause['item_tax'] = 0;
                $clause['quantity_balance'] = (0 - $quantity);
                $this->db->insert('purchase_items', $clause);
            }
        }
        $this->site->syncQuantity(NULL, NULL, NULL, $product_id);
    }
	
	function getBalanceSheetDetailByAccCode($code = NULL, $section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL) {
        $where_biller = '';
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN ($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND date(erp_gl_trans.tran_date) BETWEEN '$from_date'
			AND '$to_date' ";
		}
		$query = $this->db->query("SELECT
			erp_gl_trans.tran_type,
			erp_gl_trans.tran_date,
			erp_gl_trans.reference_no,
			(
				CASE
				WHEN erp_gl_trans.tran_type = 'SALES' THEN
					IF(erp_gl_trans.bank = '1', (
						SELECT
							erp_companies.company
						FROM
							erp_payments
						INNER JOIN erp_sales ON erp_sales.id = erp_payments.sale_id
						INNER JOIN erp_companies ON erp_companies.id = erp_sales.customer_id
						WHERE
							erp_payments.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					), (
						SELECT
							erp_companies.company
						FROM
							erp_sales
						INNER JOIN erp_companies ON erp_companies.id = erp_sales.customer_id
						WHERE
							erp_sales.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					))
				WHEN erp_gl_trans.tran_type = 'PURCHASES' OR erp_gl_trans.tran_type = 'PURCHASE EXPENSE' THEN
					IF(erp_gl_trans.bank = 1, (
						SELECT
							erp_companies.company
						FROM
							erp_payments
						INNER JOIN erp_purchases ON erp_purchases.id = erp_payments.purchase_id
						INNER JOIN erp_companies ON erp_companies.id = erp_purchases.supplier_id
						WHERE
							erp_payments.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					), (
						SELECT
							erp_companies.company
						FROM
							erp_purchases
						INNER JOIN erp_companies ON erp_companies.id = erp_purchases.supplier_id
						WHERE
							erp_purchases.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					))
				WHEN erp_gl_trans.tran_type = 'SALES-RETURN' THEN
					(
						SELECT
							erp_return_sales.customer
						FROM
							erp_return_sales
						WHERE
							erp_return_sales.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES-RETURN' THEN
					(
						SELECT
							erp_return_purchases.supplier
						FROM
							erp_return_purchases
						WHERE
							erp_return_purchases.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'DELIVERY' THEN
					(
						SELECT
							erp_companies.company as customer
						FROM
							erp_deliveries
						INNER JOIN erp_companies ON erp_companies.id = erp_deliveries.customer_id
						WHERE
							erp_deliveries.do_reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PRINCIPLE' THEN
					(
						SELECT
							erp_companies.company
						FROM
							erp_payments
						LEFT JOIN erp_loans ON erp_loans.id = erp_payments.loan_id
						INNER JOIN erp_sales ON erp_loans.sale_id = erp_sales.id
						INNER JOIN erp_companies ON erp_companies.id = erp_sales.customer_id
						WHERE
							erp_payments.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				ELSE
					created_name
				END
			) AS customer,
			erp_gl_trans.description AS note,
			erp_gl_trans.account_code,
			erp_gl_charts.accountname,
			erp_gl_trans.amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE
			erp_gl_trans.account_code = '$code'
			AND	erp_gl_trans.sectionid IN ($section)
			$where_biller 
			$where_date
		HAVING amount <> 0
		");
		return $query;
	}
	
	function getBalanceSheetDetailPurByAccCode($code = NULL, $section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL) {
        $where_biller = '';
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND date(erp_gl_trans.tran_date) BETWEEN '$from_date'
			AND '$to_date' ";
		}
		$query = $this->db->query("SELECT
			erp_gl_trans.tran_type,
			erp_gl_trans.tran_date,
			erp_gl_trans.reference_no,
			(
				CASE
				WHEN erp_gl_trans.tran_type = 'SALES' THEN
					(
						SELECT
							erp_sales.customer
						FROM
							erp_sales
						WHERE
							erp_gl_trans.reference_no = erp_sales.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES' OR erp_gl_trans.tran_type = 'PURCHASE EXPENSE' THEN
					(
						SELECT
							erp_purchases.supplier
						FROM
							erp_purchases
						WHERE
							erp_gl_trans.reference_no = erp_purchases.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'SALES-RETURN' THEN
					(
						SELECT
							erp_return_sales.customer
						FROM
							erp_return_sales
						WHERE
							erp_return_sales.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES-RETURN' THEN
					(
						SELECT
							erp_return_purchases.supplier
						FROM
							erp_return_purchases
						WHERE
							erp_return_purchases.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'DELIVERY' THEN
					(
						SELECT
							erp_deliveries.customer
						FROM
							erp_deliveries
						WHERE
							erp_deliveries.do_reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'USING STOCK' THEN
					(
						SELECT
							erp_companies.name
						FROM
							erp_enter_using_stock
						INNER JOIN erp_companies ON erp_companies.id = erp_enter_using_stock.employee_id
						WHERE
							erp_enter_using_stock.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'STOCK_ADJUST' THEN
					(
						SELECT
							'' AS customer
						FROM
							erp_adjustments
						WHERE
							erp_adjustments.id = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				ELSE
					''
				END
			) AS customer,
			(
				CASE
				WHEN erp_gl_trans.tran_type = 'SALES' THEN
					(
						SELECT
							erp_sales.note
						FROM
							erp_sales
						WHERE
							erp_gl_trans.reference_no = erp_sales.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES' OR erp_gl_trans.tran_type = 'PURCHASE EXPENSE' THEN
					(
						SELECT
							erp_purchases.note
						FROM
							erp_purchases
						WHERE
							erp_gl_trans.reference_no = erp_purchases.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'SALES-RETURN' THEN
					(
						SELECT
							erp_return_sales.note
						FROM
							erp_return_sales
						WHERE
							erp_return_sales.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'PURCHASES-RETURN' THEN
					(
						SELECT
							erp_return_purchases.note
						FROM
							erp_return_purchases
						WHERE
							erp_return_purchases.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'DELIVERY' THEN
					(
						SELECT
							erp_deliveries.note
						FROM
							erp_deliveries
						WHERE
							erp_deliveries.do_reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'USING STOCK' THEN
					(
						SELECT
							erp_enter_using_stock.note
						FROM
							erp_enter_using_stock
						WHERE
							erp_enter_using_stock.reference_no = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				WHEN erp_gl_trans.tran_type = 'STOCK_ADJUST' THEN
					(
						SELECT
							erp_adjustments.note
						FROM
							erp_adjustments
						WHERE
							erp_adjustments.id = erp_gl_trans.reference_no
						LIMIT 0,1
					)
				ELSE
					''
				END
			) AS note,
			erp_gl_trans.account_code,
			erp_gl_charts.accountname,
			erp_gl_trans.amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE
			erp_gl_trans.account_code = '$code'
			AND	erp_gl_trans.sectionid IN ($section)
			$where_biller 
			$where_date
		HAVING amount <> 0
		");
		return $query;
	}

    public function getStatementByBalaneSheetDate($section = NULL, $from_date = NULL, $to_date = NULL, $biller_id = NULL)
    {
		$where_biller = '';
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) ";
		}
		$where_date = '';
		if($from_date && $to_date){
            $where_date = " AND date(erp_gl_trans.tran_date) BETWEEN '$from_date 00:00:00'
			AND '$to_date 11:59:00' ";
		}
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
			erp_gl_trans.account_code,
			erp_gl_trans.sectionid,
			erp_gl_charts.accountname,
			erp_gl_charts.parent_acc,
			sum(erp_gl_trans.amount) AS amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE 
			erp_gl_trans.sectionid IN ($section)
			$where_date
		GROUP BY
			erp_gl_trans.account_code
		");

		return $query;
	}
	public function getStatementByBalaneSheetDateByCustomer($section = NULL,$from_date= NULL,$to_date = NULL,$customer_id = NULL){
		$where_customer = '';
		if($customer_id != NULL){
			$where_customer = " AND erp_gl_trans.customer_id IN($customer_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND date(erp_gl_trans.tran_date) BETWEEN '$from_date'
			AND '$to_date' "; 
		}
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
			erp_gl_trans.account_code,
			erp_gl_trans.sectionid,
			erp_gl_charts.accountname,
			erp_gl_charts.parent_acc,
			sum(erp_gl_trans.amount) AS amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE 
			erp_gl_trans.sectionid IN ($section)
			$where_customer
			$where_date
		GROUP BY
			erp_gl_trans.account_code
		");

		return $query;
	}
	public function getStatementByDate($section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL){
		$where_biller = '';
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND date(erp_gl_trans.tran_date) BETWEEN '$from_date'
			AND '$to_date' "; 
		}
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
			erp_gl_trans.account_code,
			erp_gl_trans.sectionid,
			erp_gl_charts.accountname,
			erp_gl_charts.parent_acc,
			sum(erp_gl_trans.amount) AS amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE
			erp_gl_trans.sectionid IN ($section)
			$where_biller
			$where_date
		GROUP BY
			erp_gl_trans.account_code
		");

		return $query;
	}
	
	public function getStatementBalaneSheetByDateBill($section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL){
		$where_biller = '';
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND date(erp_gl_trans.tran_date) BETWEEN '$from_date'
			AND '$to_date' "; 
		}
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
			erp_gl_trans.account_code,
			erp_gl_trans.sectionid,
			erp_gl_charts.accountname,
			erp_gl_charts.parent_acc,
			sum(erp_gl_trans.amount) AS amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE
			erp_gl_trans.sectionid IN ($section)
			$where_biller
			$where_date
		GROUP BY
			erp_gl_trans.account_code,
			biller_id
		");

		return $query;
	}
	
	public function getStatementByDateBill($section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL){
		$where_biller = '';
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND erp_gl_trans.tran_date BETWEEN '$from_date'
			AND '$to_date' "; 
		}
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
			erp_gl_trans.account_code,
			erp_gl_trans.sectionid,
			erp_gl_charts.accountname,
			erp_gl_charts.parent_acc,
			sum(erp_gl_trans.amount) AS amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE
			erp_gl_trans.sectionid IN ($section)
			$where_biller
			$where_date
		GROUP BY
			erp_gl_trans.account_code,
			biller_id
		");

		return $query;
	}

	function getStatementDetailByAccCode($code = NULL, $section = NULL,$from_date= NULL,$to_date = NULL,$biller_id = NULL) {
        $where_biller = '';
		if($biller_id != NULL){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		$where_date = '';
		if($from_date && $to_date){
			$where_date = " AND erp_gl_trans.tran_date BETWEEN '$from_date'
			AND '$to_date' ";
		}
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
			erp_gl_trans.tran_type,
			erp_gl_trans.tran_date,
			erp_gl_trans.reference_no,
			(CASE WHEN erp_sales.customer THEN erp_sales.customer ELSE erp_purchases.supplier END) AS customer,
			(CASE WHEN erp_sales.note THEN erp_sales.note ELSE erp_purchases.note END) AS note,
			erp_companies.company,
			erp_gl_trans.account_code,
			erp_gl_charts.accountname,
			erp_gl_trans.amount,
			erp_gl_trans.biller_id
		FROM
			erp_gl_trans
		LEFT JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		LEFT JOIN erp_companies ON erp_gl_trans.biller_id = erp_companies.id
		LEFT JOIN erp_sales ON erp_sales.reference_no = erp_gl_trans.reference_no
		LEFT JOIN erp_purchases ON erp_purchases.reference_no = erp_gl_trans.reference_no
		WHERE
			erp_gl_trans.account_code = '$code'
			AND	erp_gl_trans.sectionid IN ($section)
			$where_biller 
			$where_date
		GROUP BY
			erp_sales.reference_no,
			erp_gl_trans.account_code
		HAVING amount <> 0
		");
		return $query;
	}
	
	public function getMonthlyIncomes($excep_acccode = NULL, $section = NULL,$from_date, $to_date, $biller_id = NULL)
	{
		$where_biller = '';
		$where_year = '';
		$where_date = '';
		$where_except_code = '';
		if($biller_id){
			$where_biller = " AND erp_gl_trans.biller_id IN($biller_id) "; 
		}
		if(!$year){
			$year = date('Y');
		}
		if($from_date && $to_date){
			$where_date = " AND gl.tran_date BETWEEN '$from_date'
			AND '$to_date' "; 
		}
		if($excep_acccode){
			$where_except_code = " AND gl.account_code NOT IN($excep_acccode) ";
		}
		$this->db->query('SET SQL_BIG_SELECTS=1');
		$query = $this->db->query("SELECT
									DATE_FORMAT('$from_date','%Y') AS year,
									erp_gl_trans.biller_id,
									erp_companies.code,
									erp_companies.company,
									erp_companies.name,
									COALESCE(erp_companies.amount, 0) AS total_amount,
									erp_companies.period,
									erp_companies.start_date,
									erp_companies.end_date,
                                    erp_companies.begining_balance,
									erp_gl_trans.account_code,
									erp_gl_trans.sectionid,
									erp_gl_charts.accountname,
									erp_gl_charts.parent_acc,
									COALESCE(january.amount, 0) AS jan,
									COALESCE(febuary.amount, 0) AS feb,
									COALESCE(march.amount, 0) AS mar,
									COALESCE(april.amount, 0) AS apr,
									COALESCE(may.amount, 0) AS may,
									COALESCE(june.amount, 0) AS jun,
									COALESCE(july.amount, 0) AS jul,
									COALESCE(august.amount, 0) AS aug,
									COALESCE(september.amount, 0) AS sep,
									COALESCE(october.amount, 0) AS oct,
									COALESCE(november.amount, 0) AS nov,
									COALESCE(december.amount, 0) AS dece,
									(
										COALESCE(january.amount,0) + COALESCE(febuary.amount,0) + COALESCE(march.amount,0) + COALESCE(april.amount,0) + COALESCE(may.amount,0) + COALESCE(june.amount,0) + COALESCE(july.amount,0) + COALESCE(august.amount,0) + COALESCE(september.amount,0) + COALESCE(october.amount,0) + COALESCE(november.amount,0) + COALESCE(december.amount,0)
									) AS total
								FROM
									erp_companies
								LEFT JOIN erp_gl_trans ON erp_companies.id = erp_gl_trans.biller_id
								LEFT JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '01'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS january ON january.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '02'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS febuary ON febuary.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '03'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS march ON march.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '04'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS april ON april.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '05'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS may ON may.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '06'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS june ON june.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '07'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS july ON july.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '08'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS august ON august.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '09'
									AND gl.sectionid IN (40, 70)
									AND gl.account_code = '$acc_code' 
									$where_date
									GROUP BY
										gl.biller_id
								) AS september ON september.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '10'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS october ON october.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '11'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_date
									GROUP BY
										gl.biller_id
								) AS november ON november.biller_id = erp_companies.id
								LEFT JOIN (
									SELECT
										COALESCE(SUM(gl.amount),0) AS amount,
										gl.biller_id
									FROM
										erp_gl_trans gl
									WHERE
										MONTH (gl.tran_date) = '12'
									AND	gl.sectionid IN ($section)
									$where_except_code
									$where_dates
									GROUP BY
										gl.biller_id
								) AS december ON december.biller_id = erp_companies.id
								WHERE
									1 = 1
								AND erp_companies.group_name = 'biller'
								$where_biller
								GROUP BY
									erp_companies.id
								ORDER BY erp_companies.id
		");
		return $query;
	}
	
	public function addJournals($rows)
	{		
		 if (!empty($rows)) {
			foreach($rows as $row){
					$this->db->insert('gl_trans', $row);
			}
		  return true;
		}
        return false;
    }
	
	public function addCharts($data = array())
	{
        if ($this->db->insert_batch('gl_charts', $data)) {
            return true;
        }
        return false;
    }
	
	public function getSectionIdByCode($code)
	{
        $q = $this->db->get_where('gl_charts', array('accountcode' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row()->sectionid;
        }
        return FALSE;
    }
	
	public function getAccountCode($accountcode)
	{
		$this->db->select('accountcode');
		$q = $this->db->get_where('gl_charts', array('accountcode' => $accountcode), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
	}
	
	public function getConditionTax()
	{
		$this->db->where('id','1');
		$q=$this->db->get('condition_tax');
		return $q->result();
	}
	
	public function getConditionTaxById($id)
	{
		$this->db->where('id',$id);
		$q=$this->db->get('condition_tax');
		return $q->row();
	}
	
	public function update_exchange_tax_rate($id,$data)
	{
		$this->db->where('id',$id);
		$update=$this->db->update('condition_tax',$data);
		if($update){
			return true;
		}
	} 
	
	public function getKHM()
	{
		$q = $this->db->get_where('currencies', array('code'=> 'KHM'), 1);
		if($q->num_rows() > 0){
			$q = $q->row();
            return $q->rate;
		}
	}
	
	public function addConditionTax($data)
	{
		if ($this->db->insert('condition_tax', $data)) 
		{
            return true;
        }
        return false;
	}
	
	public function deleteConditionTax($id)
	{
		$q = $this->db->delete('condition_tax', array('id' => $id));
		if($q){
			return true;
		} else{
			return false;
		}
	}
	
	public function getCustomersDepositByCustomerID($customer_id)
	{
		$q = $this->db
    		->select("deposits.id as dep_id, companies.id AS id , date,companies.name, companies.deposit_amount AS amount, paid_by, CONCAT(erp_users.first_name, ' ', erp_users.last_name) as created_by", false)
    		->from("deposits")
    		->join('users', 'users.id=deposits.created_by', 'inner')
    		->join('companies', 'deposits.company_id = companies.id', 'inner')
    		->where('deposits.amount <>', 0)
			->where('companies.id', $customer_id)
			->get();
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	
	public function ar_by_customer($start_date=null, $end_date=null, $customer2=null, $balance2=null, $condition=null, $sale_id=null)
    {
        $w = '';
        if($start_date){
            $w .= " AND (erp_sales.date) >= '".$start_date." 00:00:00'";
        }
        
        if($end_date){
            $w .= " AND (erp_sales.date) <= '".$end_date."23:59:00' ";
        }
        
        if($customer2){
            $w .= " AND erp_sales.customer_id = '".$customer2."' ";
        }       
        
        if(!$balance2){
            $balance2 = "all";
        }
        if($balance2 == "balance0"){
            $w .= " AND erp_sales.grand_total <= 0 ";
        }
        if($balance2 == "owe"){
            $w .= " AND erp_sales.grand_total > 0 ";
        }
        
        if($condition=='customer'){
            $q = $this->db
            ->select("sales.customer_id,
                        sales.customer,
                        '".$start_date."' AS start_date,
                        '".$end_date."' AS end_date,
                        '".$balance2."' AS balance ", false)
            ->from("sales")
            ->where("1 = 1 ".$w."")
            ->group_by("customer_id")
            ->order_by("customer", "asc")
            ->get();
            if($q->num_rows() > 0){
                return $q->result();
            }
            return false;
        }elseif($condition=='detail'){
            
            $q = $this->db
            ->select("sales.customer_id,
                        sales.id,
                        sales.customer,
                        sales.reference_no,
                        sales.date,
                        sales.grand_total,
                        0 as order_discount,
                        erp_returns.amount as amount_return,
                        erp_deposits.amount as amount_deposit
                    ", false)
            ->from("sales")
            ->join("payments erp_returns","erp_returns.sale_id = sales.id AND erp_returns.return_id<>''","left")
            ->join("payments erp_deposits","erp_deposits.sale_id = sales.id AND erp_deposits.deposit_id<>''","left")
            ->where("1 = 1 ".$w."")
            ->group_by("sales.reference_no")
            ->order_by("sales.reference_no", "desc")
            ->get();                
            if($q->num_rows() > 0){
                return $q->result();
            }
            return false;
            
        }elseif($condition=='payment'){
            $q = $this->db
            ->select("payments.amount,
                      payments.reference_no,
                      payments.date
                    ", false)
            ->from("payments")
            ->where("payments.sale_id<>", "")
            ->where("payments.sale_id=", $sale_id)
            ->get();
            if($q->num_rows() > 0){
                return $q->result();
            }
            return false;
        }
    }
	
	public function ap_by_supplier($start_date=null, $end_date=null, $supplier2=null, $balance2=null, $condition=null, $purchase_id=null)
    {
        $w = '';
        if($start_date){
            $w .= " AND (erp_purchases.date) >= '".$start_date." 00:00:00'";
        }
        
        if($end_date){
            $w .= " AND (erp_purchases.date) <= '".$end_date."23:59:00' ";
        }
        
        if($supplier2){
            $w .= " AND erp_purchases.supplier_id = '".$supplier2."' ";
        }
        
        if(!$balance2){
            $balance = "all";
        }
        if($balance2 == "balance0"){
            $w .= " AND erp_purchases.grand_total <= 0 ";
        }
        if($balance2 == "owe"){
            $w .= " AND erp_purchases.grand_total > 0 ";
        }
        
        if($condition=='supplier'){
            
            $q = $this->db->select("purchases.supplier_id,
                        purchases.supplier,
                        '".$start_date."' AS start_date,
                        '".$end_date."' AS end_date,
                        '".$balance2."' AS balance ", false)
            ->from("purchases")
            ->where("1 = 1 ".$w."")
            ->group_by("supplier_id")
            ->order_by("supplier", "asc")
            ->get();
            if($q->num_rows() > 0){
                return $q->result();
            }
            return false;
            
        }elseif($condition=='detail'){
        
            $q = $this->db->select("purchases.supplier_id,
                        purchases.id,
                        purchases.supplier,
                        purchases.reference_no,
                        purchases.date,
                        purchases.grand_total,
                        0 as order_discount,
                        erp_returns.amount as amount_return,
                        erp_deposits.amount as amount_deposit", false)
            ->from("purchases")
            ->join("payments erp_returns","erp_returns.purchase_id = purchases.id AND erp_returns.purchase_return_id<>''","left")
            ->join("payments erp_deposits","erp_deposits.purchase_id = purchases.id AND erp_deposits.purchase_deposit_id<>''","left")
            ->where("1 = 1 ".$w."")
            ->group_by("purchases.reference_no")
            ->order_by("purchases.reference_no", "desc")
            ->get();
            if($q->num_rows() > 0){
                return $q->result();
            }
            return false;
            
        }elseif($condition=='payment'){
            
            $q = $this->db->select("payments.amount,
                      payments.reference_no,
                      payments.date
                    ", false)
            ->from("payments")
            ->where("payments.purchase_id<>", "")
            ->where("payments.purchase_id=", $purchase_id)
            ->get();
            if($q->num_rows() > 0){
                return $q->result();
            }
            return false;
        }
    }
	
	public function increaseTranNo()
	{
		$q = $this->db->get_where('erp_order_ref',array("DATE_FORMAT(date,'%Y-%m')"=>date('Y-m')),1);
		if($q->num_rows() > 0){
				return $q->row()->tr;
			}
			return false;
	}
	
	public function UpdateincreaseTranNo($tr)
	{
		$q = $this->db->update('erp_order_ref',array('tr'=>$tr),array("DATE_FORMAT(date,'%Y-%m')"=>date('Y-m')));
		if($q){
				return true;
		}
		return false;
	}

    public function checkrefer($id)
	{
        $q = $this->db->get_where('erp_sales',array('id'=>$id),1);
        if($q->num_rows() > 0){
            return $q->row();
        }
        return false;
    }
	
	public function checkreferPur($id)
	{
        $q = $this->db->get_where('erp_purchases',array('id'=>$id),1);
        if($q->num_rows() > 0){
            return $q->row();
        }
        return false;
    }

	public function deleteGltranByAccount($ids = false){
		if($ids){
			$result = $this->db->where_in("tran_id",$ids)->delete("gl_trans");
			if($result) {
				$this->db->where_in("transaction_id",$ids)->delete("payments");
			}
			return $result;
		}
		return false;
	}
	public function ar_by_customerV2($start_date=null, $end_date=null, $customer=null, $balance=null){
		$this->db->select("sales.customer_id, sales.customer", false)
            ->from("sales");
           
		   if($start_date && $end_date){
			   $this->db->where('date_format(erp_sales.date,"%Y-%m-%d") BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		   }
		   if($balance == "balance0"){
			    $this->db->where('erp_sales.grand_total <= 0');
		   }
		   if($balance == "owe"){
			    $this->db->where('erp_sales.grand_total > 0');
		   }
		    if($customer){
			    $this->db->where('customer_id',$customer);
		   }
            $this->db->group_by("customer_id");
            $this->db->order_by("customer", "asc");
            $q = $this->db->get();
            if($q->num_rows() > 0){
                return $q->result();
            }
            return false;
	}
    
	public function getSaleByCustomerV2($cus_id){
		$this->db->select("sales.id,CONCAT(erp_users.first_name,' ',erp_users.last_name) as fullname", false)
            ->from("sales")->join("users","users.id=sales.saleman_by","LEFT");
			
			 $this->db->where('customer_id',$cus_id);
            $this->db->order_by("date", "asc");
            $q = $this->db->get();
            if($q->num_rows() > 0){
                return $q->result();
            }
            return false;
	}
	public function getSaleBySID($id){
            $q = $this->db->get_where("sales",array('id'=>$id),1);
            if($q->num_rows() > 0){
                return $q->row();
            }
            return false;
	}
	public function getPaymentBySID($id){
		$this->db->select("erp_payments.*,erp_companies.company as biller", false);
			$this->db->join("erp_companies","erp_companies.id=erp_payments.biller_id","LEFT");
          $this->db->where('sale_id',$id);
			$q = $this->db->get('erp_payments');
           if($q->num_rows() > 0){
                return $q->result();
            }
            return false;
	}
	public function getReturnBySID($id){
		$this->db->select("erp_return_sales.*", false);
          $this->db->where('sale_id',$id);
			$q = $this->db->get('erp_return_sales');
           if($q->num_rows() > 0){
                return $q->result();
            }
            return false;
	}
}
