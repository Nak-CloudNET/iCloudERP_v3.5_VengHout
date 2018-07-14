<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Companies_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllBillerCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'biller'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllCustomerCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'customer'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllSupplierCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'supplier'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllCustomerGroups()
    {
        $q = $this->db->get('customer_groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getCompanyAttachment($company_id)
    {   $this->db->select('attachment');
        $q = $this->db->get_where('companies', array('id' => $company_id));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }

    public function getCompanyUsers($company_id)
    {
        $q = $this->db->get_where('users', array('company_id' => $company_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getGroupAreas(){
		$this->db->select('areas_g_code,areas_group');
		return $this->db->get('erp_group_areas')->result();
	}
    public function getCompanyByID($id)
    {
        $this->db->select('companies.*, group_areas.areas_group, customer_groups.order_discount');
		$this->db->join('group_areas','group_areas.areas_g_code = companies.group_areas_id', 'left');
        $this->db->join('customer_groups', 'companies.customer_group_id = customer_groups.id', 'left');
        $q = $this->db->get_where('companies', array('companies.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	//socheat

	public function getCustomer_history($id)
    {
		$this->db->select('erp_sales.date,product_code,product_name,unit_price,quantity')
		->where('erp_sales.customer_id',$id)
		->from('erp_sale_items')
		->join('erp_sales','erp_sales.id=erp_sale_items.sale_id','left');
		//->join('erp_companies','erp_companies.id=erp_sales.customer_id','left');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

	public function getDefaults()
    {
		$this->db->select('*');
		$this->db->from('account_settings');
		$this->db->join('gl_charts', 'account_settings.default_open_balance=gl_charts.accountcode', 'left');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCompanyByEmail($email)
    {
        $q = $this->db->get_where('companies', array('email' => $email), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function getCompanyByCode($code)
    {
        $q = $this->db->get_where('companies', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function GetPublicChargeDetail($id)
	{
		$this->db->select('*');
		$this->db->from('public_charge_detail');
		$this->db->where_in('pub_id',$id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}

	public function addCustPublicCharge($data = array())
    {
        if ($this->db->insert_batch('customer_public_charge', $data)) {
            return true;
        }
        return false;
    }

    public function addCompany($data = array())
    {
        if (!empty($this->site->get_setting()->project_code_prefix)) {
            $project_code = $this->site->getReference('pro');
        } else {
            $project_code = substr($this->site->getReference('pro'), 5);
        }

        if (!empty($this->site->get_setting()->customer_code_prefix)) {
            $customer_code = $this->site->getReference('cus');
        } else {
            $customer_code = substr($this->site->getReference('cus'), 5);
        }

        if (!empty($this->site->get_setting()->supplier_code_prefix)) {
            $supplier_code = $this->site->getReference('sup');
        } else {
            $supplier_code = substr($this->site->getReference('sup'), 5);
        }

        if (!empty($this->site->get_setting()->employee_code_prefix)) {
            $employee_code = $this->site->getReference('emp');
        } else {
            $employee_code = substr($this->site->getReference('emp'), 5);
        }


        if ($this->db->insert('companies', $data)) {
            $cid = $this->db->insert_id();

            if ($project_code == $data['code']) {
                $this->site->updateReference('pro');
            }
            if ($customer_code == $data['code']) {
                $this->site->updateReference('cus');
            }
            if ($supplier_code == $data['code']) {
                $this->site->updateReference('sup');
            }
            if ($employee_code == $data['code']) {
                $this->site->updateReference('emp');
            }

			if($data['public_charge_id']){
				$pub_data = $this->GetPublicChargeDetail($data['public_charge_id']);

				for($i=0;$i<sizeof($pub_data);$i++)
				{
					$p_data[]=array('customer_id'=>$cid,
								    'pub_id'=>$pub_data[$i]->pub_id,
								    'description'=>$pub_data[$i]->description,
								    'date'=>$pub_data[$i]->date,
								    'amount'=>$pub_data[$i]->amount,
								    'period'=>$pub_data[$i]->period);
				}

				$this->addCustPublicCharge($p_data);

			}

            return $cid;
        }
        return false;
    }

	public function delete_public_charge($id){
		$this->db->where_in('pub_id', $id);
		$this->db->delete('customer_public_charge');
		if($this->db->affected_rows()){
			return true;
		}
	}

    public function updateCompany($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('companies', $data)) {

			$this->delete_public_charge($data['public_charge_id']);
			if($data['public_charge_id']){

				$pub_data = $this->GetPublicChargeDetail($data['public_charge_id']);

				for($i=0;$i<sizeof($pub_data);$i++)
				{
					$p_data[]=array('customer_id'=>$id,
								    'pub_id'=>$pub_data[$i]->pub_id,
								    'description'=>$pub_data[$i]->description,
								    'date'=>$pub_data[$i]->date,
								    'amount'=>$pub_data[$i]->amount,
								    'period'=>$pub_data[$i]->period);
				}

				$this->addCustPublicCharge($p_data);

			}

            return true;
        }
        return false;
    }

    public function addCompanies($data = array())
    {
        if ($this->db->insert_batch('companies', $data)) {
            return true;
        }
        return false;
    }

    public function deleteCustomer($id)
    {
        if ($this->getCustomerSales($id)) {
            return false;
        }
        if ($this->db->delete('companies', array('id' => $id, 'group_name' => 'customer')) && $this->db->delete('users', array('company_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteSupplier($id)
    {
        if ($this->getSupplierPurchases($id)) {
            return false;
        }
        if ($this->db->delete('companies', array('id' => $id, 'group_name' => 'supplier')) && $this->db->delete('users', array('company_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function deleteBiller($id)
    {
        if ($this->db->delete('companies', array('id' => $id, 'group_name' => 'biller'))) {
            return true;
        }
        return FALSE;
    }

	public function deleteOrderReByBillerID($id){
		if ($this->db->delete('erp_order_ref', array('biller_id' => $id))) {

            return true;
        }
        return FALSE;
	}

    public function getBillerSuggestions($term, $limit = 10)
    {
        $this->db->select("id,(CONCAT(code,' - ', name, ' (', company, ')')) as text");
        $this->db->where(" (id LIKE '%" . $term . "%' OR name LIKE '%" . $term . "%' OR company LIKE '%" . $term . "%') ");
        $q = $this->db->get_where('companies', array('group_name' => 'biller'), $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getCustomerSuggestions($term, $limit = 10)
    {
        $this->db->select("companies.id, CONCAT(erp_companies.code,' - ', (
			IF((ISNULL(erp_companies.company) OR erp_companies.company = ''), erp_companies.name, erp_companies.company)
		)) as text, customer_groups.order_discount", FALSE);
		$this->db->join('gift_cards', 'gift_cards.customer_id = companies.id', 'left');
        $this->db->join('customer_groups', 'companies.customer_group_id = customer_groups.id', 'left');
        $this->db->where(" (erp_companies.id LIKE '%" . $term . "%' OR erp_companies.name LIKE '%" . $term . "%' OR erp_companies.company LIKE '%" . $term . "%' OR email LIKE '%" . $term . "%' OR phone LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR erp_gift_cards.card_no LIKE '%" . $term . "%' ) ");
		$this->db->group_by('companies.id');
        $q = $this->db->get_where('companies', array('group_name' => 'customer'), $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

	public function getGiftCardByCardNUM($card)
	{
		$q = $this->db->get_where('gift_cards', array('card_no' => $card));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
	}

	public function getBalanceSuggestions($term, $limit = 10)
    {
        $this->db->select("*");
        $this->db->where(" (accountcode LIKE '%" . $term . "%' OR accountname LIKE '%" . $term . "%') ");
        $q = $this->db->get_where('gl_charts', $limit);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getCustomerGroupSuggestions($term, $limit = 10)
    {
        $this->db->select("id, CONCAT(name, ' (', name, ')') as text", FALSE);
        $this->db->where(" (id LIKE '%" . $term . "%' OR name LIKE '%" . $term . "%' OR name LIKE '%" . $term . "%' OR name LIKE '%" . $term . "%' OR name LIKE '%" . $term . "%') ");
        $q = $this->db->get('erp_customer_groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
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

    public function getCustomerSales($id)
    {
        $this->db->where('customer_id', $id)->from('sales');
        return $this->db->count_all_results();
    }

    public function getBillerSales($id)
    {
        $this->db->where('biller_id', $id)->from('sales');
        return $this->db->count_all_results();
    }

    public function getSupplierPurchases($id)
    {
        $this->db->where('supplier_id', $id)->from('purchases');
        return $this->db->count_all_results();
    }

	public function addDeposit($data, $cdata, $payment = array())
    {
        $biller_id = $payment['biller_id'];
        if ($this->db->insert('deposits', $data)) {
                $deposit_id = $this->db->insert_id();
                $this->db->update('companies', $cdata, array('id' => $data['company_id']));
                if($payment){
                    $payment['deposit_id'] = $deposit_id;
                    if ($this->db->insert('payments', $payment)) {
                        if ($this->site->getReference('sp', $biller_id) == $payment['reference_no']) {
							$this->site->updateReference('sp', $biller_id);
						}
						if ($payment['paid_by'] == 'gift_card') {
							$gc = $this->site->getGiftCardByNO($payment['cc_no']);
							$this->db->update('gift_cards', array('balance' => ($gc->balance - $payment['amount'])), array('card_no' => $payment['cc_no']));
						}
						return true;
					}
				}
            return true;
        }
        return false;
    }

    public function updateDeposit($id, $data, $cdata, $payment)
    {
        if ($this->db->update('deposits', $data, array('id' => $id)) && $this->db->update('companies', $cdata, array('id' => $data['company_id']))) {
			if($data['status'] == 'returned') {
				$this->db->update('payments', $payment , array('return_deposit_id' => $id));
			}else {
				$this->db->update('payments', $payment , array('deposit_id' => $id));
			}
            return true;
        }
        return false;
    }

	/*==============================chin local updated==============================*/
	public function ReturnDeposit($data, $cdata, $payment)
    {
        if ($this->db->insert('deposits', $data)) {
				$deposit_id = $this->db->insert_id();
				$this->db->update('companies', $cdata, array('id' => $data['company_id']));
				if($payment){
					$payment['return_deposit_id'] = $deposit_id;
					if ($this->db->insert('payments', $payment)) {
						if ($this->site->getReference('pp') == $payment['reference_no']) {
							$this->site->updateReference('pp');
						}
						if ($payment['paid_by'] == 'gift_card') {
							$gc = $this->site->getGiftCardByNO($payment['cc_no']);
							$this->db->update('gift_cards', array('balance' => ($gc->balance - $payment['amount'])), array('card_no' => $payment['cc_no']));
						}
						return true;
					}
				}
            return true;
        }
        return false;
    }
	/*========================end local updated===================================*/

    public function getDepositByID($id)
    {
        $q = $this->db->get_where('deposits', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getDepositItems($id)
    {
        $q = $this->db->get_where('deposits', array('company_id' => $id, 'paid_by' => 'deposit'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    public function deleteDeposit($id)
    {
        $deposit = $this->getDepositByID($id);
        $company = $this->getCompanyByID($deposit->company_id);
        $cdata = array(
			'deposit_amount' => ($company->deposit_amount - $deposit->amount)
		);
        if ($this->db->update('companies', $cdata, array('id' => $deposit->company_id)) &&
            $this->db->delete('deposits', array('id' => $id))) {
            return true;
        }
        return false;
    }
	public function getPOReference(){
		$this->db->where('payment_status',NULL);
		$this->db->select('reference_no');
		$this->db->from('purchases_order');
		$q=$this->db->get();
			if($q){
				return $q->result();
			}else{
				return false;
			}
	}
	public function addSupplierDeposit($data, $cdata, $payment = array(),$po,$reference_no)
    {
		//$this->erp->print_arrays($data, $cdata, $payment);
        if ($this->db->insert('deposits', $data)) {
			$deposit_id = $this->db->insert_id();

			if ($this->site->getReference('pp') == $data['reference']) {
				$this->site->updateReference('pp');
			}else{}

			//$this->db->update('purchases_order', $po, array('reference_no' => $reference_no));

			$this->db->update('companies', $cdata, array('id' => $data['company_id']));
			if($payment){
				$payment['purchase_deposit_id'] = $deposit_id;
				if ($this->db->insert('payments', $payment)) {
					if ($this->site->getReference('pp') == $payment['reference_no']) {
						$this->site->updateReference('pp');
					}
					if ($payment['paid_by'] == 'gift_card') {
						$gc = $this->site->getGiftCardByNO($payment['cc_no']);
						$this->db->update('gift_cards', array('balance' => ($gc->balance - $payment['amount'])), array('card_no' => $payment['cc_no']));
					}
					return true;
				}
			}
            return true;
        }
        return false;
    }
	public function deleteSupplierDeposit($id){
		$deposit = $this->getDepositByID($id);

		if($this->db->delete('deposits',array('id'=>$id))){
			$this->db->update('companies', array('deposit_amount' => 0), array('id' => $deposit->company_id));
			return true;
		}
			return false;
	}
	public function getPaymentBySupplierDeposit($purchase_deposit_id)
    {
        $q = $this->db->get_where('payments', array('purchase_deposit_id' => $purchase_deposit_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getPaymentByDepositID($deposit_id)
    {
        $q = $this->db->get_where('payments', array('deposit_id' => $deposit_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function updateSupplierDeposit($id, $data, $cdata, $payment)
    {
		// $this->erp->print_arrays($data, $cdata, $payment);
        if ($this->db->update('deposits', $data, array('id' => $id)) && $this->db->update('companies', $cdata, array('id' => $data['company_id']))) {
			$this->db->update('payments', $payment , array('purchase_deposit_id' => $id));
            return true;
        }
        return false;
    }
	public function createDriver($data = array()) {
		if($data) {
			if($this->db->insert('companies', $data)) {
				return true;
			}
		}
		return false;
	}
	public function delete_driver($id=null){
		if($this->db->delete('companies',array('id'=>$id))){
			return true;
		}
			return false;
	}

	public function saveDriver($id=null,$data = array()) {

		if($data) {
			if($this->db->update('companies', $data,array('id'=>$id)))  {
				return true;
			}
		}
		return false;
	}
	public function delete_employee($id){
		$this->db->where('erp_companies.id', $id);
		$this->db->delete('erp_companies');
		if($this->db->affected_rows()){
			return true;
		}
	}

	public function edit_employee($id,$data){
		$this->db->where('erp_companies.id',$id);
		$this->db->update('erp_companies',$data);
		if($this->db->affected_rows()){
			return true;
		}
		return false;

	}
	public function getEmployeeById($id){
		$this->db->select('*');
		$this->db->from('erp_companies');
		$this->db->where('id', $id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}

	public function getOrderRef(){
		$this->db->select("*");
		$this->db->from("erp_order_ref");
		$this->db->limit("32");
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

    public function getProOrderRef(){
        $this->db->select("pro");
        $this->db->select("cus");
        $this->db->select("sup");
        $this->db->select("emp");
        $this->db->from("erp_order_ref");
        $this->db->order_by("ref_id", "DESC");
        $this->db->limit(1);
        $q = $this->db->get();
        if($q->num_rows()>0){
            return $q->result();
        }
        return false;
    }

	public function addRefernce($refs){
		if($this->db->insert_batch('erp_order_ref',$refs)){
			return true;
		}
		return false;
	}

    public function updateProRef($data)
    {
        $pro = array(
            'pro'=>$data
        );
        if ($this->db->update('erp_order_ref',$pro)) {
            return true;
        };
        return false;
    }

    public function updateSaleOrderDeposit($deposit=null,$sale_order_id=null){
        $sale_order = $this->db->get_where('erp_sale_order', array('id' => $sale_order_id))->row();
        $payment_status = "";

        if($sale_order->grand_total - $deposit > 0){
            $payment_status = "partial";
        }else{
            $payment_status = "paid";
        }
        $deposit = $sale_order->paid + $deposit;

        $data = array(
               'paid' => $deposit,
               'payment_status' => $payment_status
        );
        $this->db->where('id', $sale_order_id);
        $this->db->update('erp_sale_order', $data);
    }

    public function getDeposit($id=null){
        $this->db->select('*')
                ->from('erp_deposits')
                ->where('erp_deposits.id',$id);
        $q = $this->db->get();
        if($q->num_rows()>0){
            return $q->row();
        }
        return false;
    }

    public function getSale_Order($id=null){
        $this->db->select('erp_sale_order.*, CONCAT(erp_users.first_name," ",erp_users.last_name) AS name,erp_companies.address,erp_companies.email,erp_companies.phone')
                ->from('erp_sale_order')
                ->join('erp_users','erp_sale_order.saleman_by = erp_users.id','left')
                ->join('erp_companies','erp_sale_order.biller_id = erp_companies.id','left')
                ->where('erp_sale_order.id',$id);
        $q = $this->db->get();
        if($q->num_rows()>0){
            return $q->row();
        }
        return false;
    }

    public function getSale_Order_Items($id=null){
        $this->db->select('erp_sale_order_items.*, CONCAT(erp_products.name,"<br/>",erp_products.details) AS pro_name,erp_units.name AS unit_name,erp_product_variants.name AS variant_name, erp_product_variants.qty_unit,, erp_sale_order.order_discount, erp_sale_order.order_tax, erp_sale_order.shipping')
                ->from('erp_sale_order_items')
                ->join('erp_sale_order','erp_sale_order_items.sale_order_id = erp_sale_order.id','left')
                ->join('erp_products','erp_sale_order_items.product_id = erp_products.id','left')
                ->join('erp_units','erp_products.unit = erp_units.id','left')
                ->join('erp_product_variants','erp_sale_order_items.option_id = erp_product_variants.id','left')
                ->where('erp_sale_order.id',$id);
        $q = $this->db->get();
        if($q->num_rows()>0){
            return $q->result();
        }
        return false;
    }

    public function getheader($id=null){
        $this->db->select('*')
                ->from('erp_sale_order')
                ->where('erp_sale_order.id',$id);
        $q = $this->db->get();
        if($q->num_rows()>0){
            return $q->row();
        }
        return false;
    }

	public function getPublicCharge()
	{
		$this->db->select('*')
                ->from('define_public_charge');
        $q = $this->db->get();
        if($q->num_rows()>0){
             return $q->result();
        }
        return false;
	}
    function addCashAdvance($employee_advance){
        //$this->erp->print_arrays($employee_advance);
        if($this->db->insert('erp_cash_advances', $employee_advance)){
            $advance_id = $this->db->insert_id();
            return $advance_id;
        }
        return false;

    }
    function addAdvancePayment($payment){
        if($this->db->insert('erp_payments', $payment)){
            if ($this->site->getReference('pp',$payment['biller_id']) == $payment['reference_no']) {
                $this->site->updateReference('pp',$payment['biller_id']);
            }
            return true;
        }
        return false;
    }


}
