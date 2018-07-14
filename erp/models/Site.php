<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    public function get_total_qty_alerts() 
	{
        $this->db->where('alert_quantity > erp_warehouses_products.quantity', NULL, FALSE)->where('track_quantity', 1);
        $this->db->join('erp_warehouses_products', 'erp_products.id = erp_warehouses_products.product_id', 'left');
        return $this->db->count_all_results('products');
    }
	
	public function getEmployeeByID($id) {
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
    public function get_expiring_qty_alerts() 
	{
        //$date = date('Y-m-d', strtotime('+3 months'));
        $this->db->select('SUM(erp_purchase_items.quantity_balance) as alert_num')
        ->where('expiry !=', NULL)->where('expiry !=', '0000-00-00')
        ->where('DATE_ADD(expiry , INTERVAL -(SELECT alert_day FROM erp_settings) DAY) <= CURDATE() AND CURDATE()< expiry')
		->group_by('erp_purchase_items.product_code')
		->group_by('erp_purchase_items.expiry')
		->having('SUM(erp_purchase_items.quantity_balance) > 0');
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_public_charge_alerts() 
	{

        $this->db->select('erp_companies.id,
						   erp_companies.name,
						   erp_define_public_charge.description,
							SUM(
								erp_customer_public_charge.amount
							) AS total_amount,
							SUM(
								erp_customer_public_charge.paid
							) AS paid')
        ->join('companies','companies.id = customer_public_charge.customer_id','left')
		->join('define_public_charge','define_public_charge.id = customer_public_charge.pub_id','left')
		->group_by('customer_public_charge.customer_id')
		->group_by('customer_public_charge.pub_id');
        $q = $this->db->get('customer_public_charge');
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
	
	public function getemployeetyp($id)
	{
		$q=$this->db->get('erp_employee_type');
		 if ($q->num_rows() > 0) {
            foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
        }
        return FALSE;
	}
	
	public function get_sale_suspend_alerts()
	{
        $q = $this->db->query('
				SELECT COUNT(n.date) AS alert_num, MIN(n.date) AS date
				FROM 
				(
					SELECT date
					FROM erp_suspended_bills 
				) AS n
				WHERE
				DATE_SUB(n.date, INTERVAL 1 DAY) <= CURDATE()
		');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getDriverByGroupId()
	{
		$this->db->select('id,name');
		$this->db->where(array('group_name' => 'driver'));
		$q = $this->db->get('companies');
		if($q->num_rows() > 0) {
			foreach($q->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
	
	public function get_customer_payments_alerts()
	{

		$this->db->select('COUNT(*) AS count, GROUP_CONCAT(CONCAT(erp_sales.id) SEPARATOR "-") as id');
		$this->db->where('due_date !=', NULL)->where('due_date !=', '0000-00-00');
		$this->db->where('DATE_SUB(due_date , INTERVAL (SELECT alert_day FROM erp_settings) DAY) < CURDATE()');
		$this->db->where(array('payment_status !=' => 'paid', 'sale_status !=' => 'returned'));
		$q = $this->db->get('sales');
		if($q->num_rows() > 0 ){
			$q = $q->row();
			return $q;
		}
		return false;
        /*$q = $this->db->query('
				SELECT COUNT(n.date) AS alert_num, MIN(n.date) AS date
				FROM 
				(
					SELECT payment_term , date
					FROM erp_sales
					WHERE
					`payment_term` <> 0
					ORDER BY date DESC
				) AS n
				WHERE
				DATE_SUB(n.date, INTERVAL 7 DAY) < CURDATE()
		');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;*/
	}
	
	public function get_purchase_payments_alerts()
	{

		$this->db->select('COUNT(*) AS count, GROUP_CONCAT(CONCAT(erp_purchases.id) SEPARATOR "-") as id');
		$this->db->where('due_date !=', NULL)->where('due_date !=', '0000-00-00');
		$this->db->where('DATE_SUB(due_date , INTERVAL (SELECT alert_day FROM erp_settings) DAY) < CURDATE()');
		$this->db->where(array('purchases.payment_status !=' => 'paid', 'purchases.status !=' => 'returned', 'purchases.total !=' => 0));
		$q = $this->db->get('purchases');
		if($q->num_rows() > 0 ){
			$q = $q->row();
			return $q;
		}
		return false;

        /*$q = $this->db->query('
			SELECT COUNT(n.date) AS alert_num, MIN(n.date) AS date
				FROM 
				(
					SELECT payment_term , date
					FROM erp_purchases
					WHERE
					`payment_term` <> 0
					ORDER BY date DESC
				) AS n
				WHERE
				DATE_SUB(n.date, INTERVAL 1 DAY) <= CURDATE()
		');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;*/
	}
	
	function getArea()
	{
        $q = $this->db->get('group_areas');        
		if ($q->num_rows() > 0 ){           
			
			return $q->result();
        }        
		return FALSE;
	}
	
	public function getProducts()
    {
		$this->db->select('id, code, name');
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function get_customer_alerts()
	{
		$this->db->select('COUNT(*) AS count');
		$this->db->where('CURDATE() >= DATE_SUB(end_date , INTERVAL (SELECT alert_day FROM erp_settings) DAY)');
		$q = $this->db->get('companies');
		if($q->num_rows() > 0 ){
			$q = $q->row();
			return $q->count;
		}
		return false;
	}

	public function get_delivery_alerts()
	{
		$this->db->select('COUNT(*) AS count');
		$this->db->where('DATE_SUB(delivery_date , INTERVAL (SELECT alert_day FROM erp_settings) DAY) < CURDATE()');
		$this->db->where('sale_order.order_status', 'completed');
		$this->db->where('sale_order.sale_status <>', 'sale');
		$q = $this->db->get('sale_order');
		if($q->num_rows() > 0 ){
			$q = $q->row();
			return $q->count;
		}
		return false;
	}
	public function get_quote_alerts()
	{
		$this->db->select('COUNT(*) AS count');
		$this->db->where('quotes.status', 'pending');
		$q = $this->db->get('quotes');
		if($q->num_rows() > 0 ){
			$q = $q->row();
			return $q->count;
		}
		return false;
	}
	public function get_purchases_request_alerts()
	{
		$this->db->select('COUNT(*) AS count');
		$this->db->where('purchases_request.status', 'requested');
		$q = $this->db->get('purchases_request');
		if($q->num_rows() > 0 ){
			$q = $q->row();
			return $q->count;
		}
		return false;
	}
	public function get_purchases_order_alerts()
	{
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
		$this->db->select('COUNT(*) AS count');
		$this->db->join($v1, 'purchase_order_items.purchase_id = erp_purchases_order.id');
		$this->db->where('erp_purchases_order.status', 'pending');
		$q = $this->db->get('erp_purchases_order');
		if($q->num_rows() > 0 ){
			$q = $q->row();
			return $q->count;
		}
		return false;
	}
	public function get_sale_order_order_alerts()
	{
		$this->db->select('COUNT(*) AS count');
		$this->db->where('sale_order.order_status','pending');
		$q = $this->db->get('sale_order');
		if($q->num_rows() > 0 ){
			$q = $q->row();
			return $q->count;
		}
		return false;
	}
	

    public function get_setting() 
	{
        $q = $this->db->get('settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function get_Acc_setting() 
	{
        $q = $this->db->get('account_settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getDateFormat($id) 
	{
        $q = $this->db->get_where('date_format', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function get_customer_groups($id) 
	{
        $q = $this->db->get_where('customer_groups', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }


    public function getAllCompanies($group_name, $biller_id = NULL, $xls = NULL)
	{
		$this->db->select("*,IF(erp_companies.company = '',erp_companies.name,erp_companies.company) as username")
			->where('erp_companies.group_name',$group_name);

        if ($biller_id) {
            if($xls) {
                $arr_biller = explode('_',$biller_id);
            } else {
                //$arr_biller = explode(',',$biller_id);
                $arr_biller = $biller_id;
            }
            $this->db->where_in('erp_companies.id', $arr_biller);
        }

        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getSupplierByArray($array)
	{
		$this->db->select("id, CONCAT(company, ' (', name, ')') as text", FALSE)
				->from("erp_companies")
				->where_in('id', $array);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getProductSupplier($group_name) 
	{
		$q = $this->db->get_where('products', array('code' => $group_name));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function getCompanyByID($id) 
	{
		$this->db->select(" companies.id,
							companies.gender,
							companies.date_of_birth,
							companies.id as cid, 
							companies.group_name,
							companies.deposit_amount,
							companies.identify_date,
							COALESCE(code, '') AS code, 
							company, company_kh, companies.name ,name_kh, email, phone, 
							group_areas.areas_group, 
							customer_group_name, customer_group_id, vat_no,
							price_group_id,erp_companies.address, erp_companies.address_1, erp_companies.address_2, erp_companies.address_3, erp_companies.address_4, erp_companies.address_5, erp_companies.city, erp_companies.postal_code,erp_companies.street,erp_companies.village,erp_companies.sangkat,erp_companies.district, erp_companies.state, erp_companies.country,
							erp_companies.cf1, erp_companies.cf2, erp_companies.cf3, erp_companies.cf4, erp_companies.cf5, erp_companies.cf6, erp_companies.invoice_footer,
							(SELECT SUM(erp_deposits.amount) FROM erp_deposits WHERE erp_companies.id = erp_deposits.company_id) as deposit_amount2, award_points, attachment,erp_companies.logo")
        ->join('group_areas', 'group_areas.areas_g_code = companies.group_areas_id', 'left')
        ->group_by("companies.id");
        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
	public function getCompanyByIDorder($id) 
	{
        $this->db->select("erp_companies.*")
        ->join('erp_companies', 'erp_sale_order.biller_id = erp_companies.id', 'left')
        ->where('erp_companies.id',$id)
        ->group_by("erp_companies.id");
        $q = $this->db->get('erp_sale_order');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
	public function getQuoteByID($id) 
	{
        $this->db->select("erp_sale_order.*,erp_quotes.reference_no")
        ->join('erp_quotes', 'erp_sale_order.quote_id = erp_quotes.id', 'left')
        ->where('erp_sale_order.id',$id);
        // ->group_by("erp_companies.id");
        $q = $this->db->get('erp_sale_order');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCompanyByIDorderCus($id) 
	{
        $this->db->select("erp_companies.*,erp_sale_order.*")
        ->join('erp_companies', 'erp_sale_order.customer_id = erp_companies.id', 'left')
        ->where('erp_sale_order.customer_id',$id);
        $q = $this->db->get('erp_sale_order');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCompanyByIDCustomer($id) 
	{
    	$this->db->select("erp_companies.*")
        ->join('erp_companies', 'erp_quotes.customer_id = erp_companies.id', 'left')
        ->where('erp_quotes.customer_id',$id)
        ->group_by("erp_quotes.customer_id");
        $q = $this->db->get('erp_quotes');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCompanyNameByCustomerID($user_id) 
	{
    	$this->db->select("erp_companies.name,erp_companies.phone, erp_companies.company")
        ->where('erp_companies.id',$user_id);
        $q = $this->db->get('erp_companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function getDriverByID($id) 
	{
       $this->db->select('companies.id,' . $this->db->dbprefix('companies') . '.name,' . $this->db->dbprefix('companies') . '.email,' . $this->db->dbprefix('companies') . '.phone');
     
        $q = $this->db->get_where('companies', array('id' => $id, 'companies.group_name' => 'driver'));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getSupplierByID($id) 
	{
      $this->db->select("companies.id, companies.id as cid, COALESCE(code, '') AS code, company, name, phone, city, country, vat_no,deposit_amount");
      $q = $this->db->get_where('companies', array('id' => $id, 'group_name' => 'supplier'), 1);
      if ($q->num_rows() > 0) {
          return $q->row();
      }
      return FALSE;
    }
	
	public function getBillerId($id=null) 
	{
        $this->db->select("biller_id");
		$this->db->where('id', $id);
		$q = $this->db->get('transfers');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getRefTransferNo($id=null) 
	{
        $this->db->select("transfer_no");
		$this->db->where('id', $id);
		$q = $this->db->get('transfers');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function getDepositsByID($id) 
	{
		$this->db->select('SUM(amount) as deposit');
        $q = $this->db->get_where('deposits', array('company_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getSuppliers()
	{
		$this->db->select("id,CONCAT(code,'-',name) as text");
		$this->db->where('group_name', 'supplier');
		$q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getSuppliersByID($id) 
	{
        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getCustomers()
	{
		$this->db->select("id,CONCAT(code,'-',name) as text");
		$this->db->where('group_name', 'customer');
		$q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getCustomerSale()
	{
		$this->db->select("customer_id as id, customer as name");
		$this->db->where('sales.payment_status <>', 'paid');
		$q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
    
    function getSupplierNameByID($sup_id = null)
	{
        $this->db->select('name, company');
		$this->db->where(array('id' => $sup_id));
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	function getCompanyByCode($code = null, $type)
	{
		$q = $this->db->get_where('companies',array('code'=>$code, 'group_name' => $type),1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	function getCompanyByName($name = null, $type)
	{
		$q = $this->db->get_where('companies',array('name'=>$name, 'group_id' => $type),1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
    
    function getBillerNameByID($biller_id = null)
	{
		$this->db->select('company, name');
		$this->db->where(array('id' => $biller_id));
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getCompanyByArray($id) 
	{
		$this->db->select('*');
		$this->db->where_in('id',json_decode($id));
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
	public function getAccountByID($id) 
	{
		$this->db->select("erp_gl_charts.accountcode, erp_gl_charts.accountname, erp_gl_charts.parent_acc, erp_gl_sections.sectionname")
				->from("erp_gl_charts")
				->join("erp_gl_sections","erp_gl_charts.sectionid=erp_gl_sections.sectionid","INNER")
				->where(array('erp_gl_charts.accountcode' => $id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getTaxByID($id) 
	{
		$this->db->select("gl_charts_tax.accountcode, gl_charts_tax.accountname, gl_charts_tax.accountname_kh, erp_gl_sections.sectionname")
				->from("gl_charts_tax")
				->join("erp_gl_sections","gl_charts_tax.sectionid=erp_gl_sections.sectionid","INNER")
				->where(array('gl_charts_tax.accountcode' => $id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getJournalByID($id=null) 
	{
		// echo $biller_id;exit();
		// echo 'hello'; exit();
		$this->db->select("gt.tran_no,gt.tran_no AS g_tran_no, 
						erp_companies.company,
						gt.tran_type, gt.tran_date, 
						gt.reference_no, gt.account_code, 
						gt.narrative, gt.description,(
						CASE
							WHEN gt.tran_type = 'SALES' THEN
								(
									SELECT
										erp_sales.customer
									FROM
										erp_sales
									WHERE
										gt.reference_no = erp_sales.reference_no
									LIMIT 0,
									1
								)
							WHEN gt.tran_type = 'PURCHASES' THEN
								(
									SELECT
										erp_purchases.supplier
									FROM
										erp_purchases
									WHERE
										gt.reference_no = erp_purchases.reference_no
									LIMIT 0,
									1
								)
							WHEN gt.tran_type = 'SALES-RETURN' THEN
								(
									SELECT
										erp_return_sales.customer
									FROM
										erp_return_sales
									WHERE
										erp_return_sales.reference_no = gt.reference_no
									LIMIT 0,
									1
								)
							WHEN gt.tran_type = 'PURCHASES-RETURN' THEN
								(
									SELECT
										erp_return_purchases.supplier
									FROM
										erp_return_purchases
									WHERE
										erp_return_purchases.reference_no = gt.reference_no
									LIMIT 0,
									1
								)
							WHEN gt.tran_type = 'DELIVERY' THEN
								(
									SELECT
										erp_deliveries.customer
									FROM
										erp_deliveries
									WHERE
										erp_deliveries.do_reference_no = gt.reference_no
									LIMIT 0,
									1
								)
							ELSE
								''
							END
						) AS NAME, 
						(IF(gt.amount > 0, gt.amount, IF(gt.amount = 0, 0, null))) as debit, 
						(IF(gt.amount < 0, abs(gt.amount), null)) as credit")
				->from("erp_gl_trans gt")
				->join("erp_companies","gt.biller_id=erp_companies.id","left" )
				->where('gt.tran_id', $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getReceivableByID($id=null, $wh=null)
	{
		$this->db
				->select("sales.id, sales.date, sales.reference_no, sales.biller, companies.company as customer, sales.sale_status, sales.grand_total, sales.paid, (grand_total-paid) as balance, sales.payment_status")
				->from('sales')
				->join('companies', 'sales.customer_id = companies.id', 'left')
				->where(array('payment_status !=' => 'Returned', 'payment_status !='=>'paid', '(grand_total-paid) <>' =>0, 'sales.id' =>$id));
				if($wh){
					$this->db->where_in('sales.warehouse_id',$wh);
				}
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getReceivable_DescriptionByID($id=null, $wh=null)
	{
		$this->db
				->select("sales.id,sale_items.product_name,sale_items.product_noted")
				->from('sales')
				->join('sale_items','sales.id = sale_items.sale_id')
				->where(array('payment_status !=' => 'Returned', 'payment_status !='=>'paid', '(grand_total-paid) <>' =>0, 'sales.id' =>$id));
				if($wh){
					$this->db->where_in('sales.warehouse_id',$wh);
				}
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
        }
        return FALSE;
	}
	
	
	public function getRecieptByID($id)
	{
		$this->db
				->select($this->db->dbprefix('payments') . ".id,
				" . $this->db->dbprefix('payments') . ".date AS date,
				" . $this->db->dbprefix('sales') . ".date AS inv_date,
				" . $this->db->dbprefix('payments') . ".reference_no as payment_ref, 
				" . $this->db->dbprefix('sales') . ".reference_no as sale_ref, customer,
				(
				CASE 
				WHEN " . $this->db->dbprefix('payments') . ".note = ' ' THEN 
				".$this->db->dbprefix('sales') . ".suspend_note 
				WHEN " . $this->db->dbprefix('sales') . ".suspend_note != ''  THEN 
				CONCAT(".$this->db->dbprefix('sales') . ".suspend_note, ' - ',  " . $this->db->dbprefix('payments') . ".note) 
				ELSE " . $this->db->dbprefix('payments') . ".note END
				) AS noted, 
				" . $this->db->dbprefix('payments') . ".paid_by, IF(erp_payments.type = 'returned', CONCAT('-', erp_payments.amount), erp_payments.amount) as amount, " . $this->db->dbprefix('payments') . ".type, erp_sales.sale_status")
                ->from('payments')
                ->join('sales', 'payments.sale_id=sales.id', 'left')
                ->join('purchases', 'payments.purchase_id=purchases.id', 'left')
                ->group_by('payments.id')
				->order_by('payments.date desc')
				->where(array('payments.type !='=>"sent", 'sales.customer !='=>'', 'payments.id'=>$id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getPayableByID($id=null, $wh=null)
	{
		$this->db
				->select("id,date,reference_no,order_ref,request_ref,supplier,status,grand_total,
					paid, (grand_total - paid) AS balance,payment_status")
                ->from('purchases')
				->where(array('payment_status !='=>'paid', 'id'=>$id));
				if($wh){
					$this->db->where_in('purchases.warehouse_id',$warehouse_id);
				}
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function getCustomerGroupByID($id) 
	{
        $q = $this->db->get_where('customer_groups', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getCustomerGroup() 
	{
        $q = $this->db->get('customer_groups');
        if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
    }
	public function getCompanyWarehouseByID($id) 
	{
        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
			$rs = $q->row();
			$warehouses = $rs->cf5;
	
			$query = $this->db->query('
				SELECT
					erp_companies.id,
					erp_companies.cf5,
					erp_users.warehouse_id,
					wh.`name`
				FROM
					erp_companiess

				INNER JOIN erp_users ON erp_users.biller_id = erp_companies.id
				INNER JOIN 
				(
					SELECT w.`name`,w.id
					FROM erp_warehouses w
				) AS wh
				WHERE
					wh.id IN ('.$warehouses.')
					AND erp_companies.id = '.$id.'
				GROUP BY wh.`name`
			');
			if ($query->num_rows() > 0) {
				foreach($query->result() as $row){
					$data[] = $row;
				}
				return $data;
			}
        }
		return FALSE;
    } 
	
	public function getUserBankAccountByEmail($id){
		$this->db->select('erp_users.id,erp_users_bank_account.id as bank_id,erp_users_bank_account.bankaccount_code');
		$this->db->from('erp_users');
		
		$this->db->join('erp_users_bank_account','erp_users.id = erp_users_bank_account.user_id','left');
		$this->db->where('erp_users.id',$id);
		$q = $this->db->get();
		
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
		
	}
	
	public function getWarehouseCompanyByID($id) 
	{
	    $id = explode('__', $id);
	    $this->db->where_in('id', $id);
        $q = $this->db->get('companies');
        $user_warehouses = $this->session->userdata('warehouse_id');

        if ($q->num_rows() > 0) {
            $warehouses = array();
            foreach ($q->result() as $rs) {
                //$rs = $q->row();
                $warehouses[] = $rs->cf5;
            }
            $_userwareho    = explode(',', $user_warehouses);
            $_warehouses    = implode(",", $warehouses);
            $user_ware      = array_intersect($_userwareho, explode(',',$_warehouses));
			$ware = "";
			if($warehouses){
			    if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
                    $ware = ' WHERE w.id IN ('.$_warehouses.') ';
                } else {
			        if (count($user_ware) > 0) {
                        $_ware = implode(",", $user_ware);
                        $ware = ' WHERE w.id IN ('.$_ware.') ';
                    }
                }
			
				$query = $this->db->query('
					SELECT
						erp_companies.id AS company_id,
						erp_companies.cf5,
						wh.id,
						wh.code,
						wh.name
					FROM
						erp_companies
					LEFT JOIN erp_users
					ON erp_users.id = erp_companies.id
					INNER JOIN 
					(
						SELECT w.name, w.code,w.id
						FROM erp_warehouses w
						'.$ware.'
					) AS wh
					
					GROUP BY wh.`name`
					ORDER BY wh.id ASC
				');
				if ($query->num_rows() > 0) {
					foreach($query->result() as $row){
						$data[] = $row;
					}
					return $data;
				}
			}
			return 0;
        }
		return 0;
    } 
	
	public function getSuspendByID($id)
	{
		$this->db
			->select("products.id as id, products.name, sales.customer as customer_name, 
					COALESCE(erp_sales.grand_total, 0) as price, 
					COALESCE((SELECT amount FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id AND erp_payments.paid_by = 'depreciation'), 0) as deposite, 
					note, (SELECT MIN(dateline) FROM erp_loans WHERE sale_id = erp_sales.id) as start_date, 
					(SELECT MAX(dateline) FROM erp_loans WHERE sale_id = erp_sales.id) as end_date, 
					CONCAT(erp_sales.term, ' Months') as term, 
					CASE WHEN erp_products.id = erp_sale_items.product_id THEN 'sold' ELSE 'aval' END AS status, sales.attachment as attachment")
            ->join('sale_items', 'sale_items.product_id = products.id', 'left')
            ->join('sales', 'sales.id = sale_items.sale_id', 'left')
            ->join('companies', 'companies.id = sales.customer_id', 'left')
            ->from("products")
            ->where('erp_products.id',$id);
		// $this->db->select("floor,erp_suspended.name as room_name, erp_suspended_bills.total as price, (SELECT deposit_amount FROM erp_companies WHERE erp_companies.id = erp_suspended_bills.customer_id) as deposite ,description, (SELECT MAX(customer) FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) as customer_name, (SELECT MAX(date) FROM erp_suspended_bills sb WHERE sb.suspend_id = erp_suspended.id ) as start_date, erp_companies.end_date as end_date, (12 * (YEAR (erp_companies.end_date) - YEAR (erp_suspended_bills.date)) + (MONTH (erp_companies.end_date) - MONTH (erp_suspended_bills.date))) as term_year, CASE WHEN erp_suspended.status = 0 THEN 'free' WHEN erp_suspended.status = 1 THEN 'busy' ELSE 'busy' END AS status")
		// ->join('erp_suspended_bills', 'erp_suspended.id = erp_suspended_bills.suspend_id', 'left')
		// ->join('erp_companies', 'erp_companies.id = erp_suspended_bills.customer_id', 'left')
		// ->from("erp_suspended")
		// ->where('erp_suspended.id',$id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
    public function getUser($id = NULL) 
	{
        if (!$id) {
            $id = $this->session->userdata('user_id');
        }
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getUsers($id)
	{
		$this->db
			->select($this->db->dbprefix('users').".id as id, first_name, last_name, email, company, award_points, " . $this->db->dbprefix('groups') . ".name, (CASE WHEN active = 0 THEN 'Inactive' ELSE 'Active' END) as astatus")
            ->from("users")
            ->join('groups', 'users.group_id=groups.id', 'left')
            ->group_by('users.id')
            ->where(array('company_id'=> NULL, 'users.id'=>$id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getEmployees($id)
	{
            // $this->db->select($this->db->dbprefix('companies').".id, name, name_kh, company, company_kh, gender, phone, email, position,address ");
            $this->db->select($this->db->dbprefix('companies').".*");
            $q = $this->db->get_where('companies', array('companies.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getProductVariantByID($id, $uom = null) 
	{
        if($uom) {
            $q = $this->db->get_where('product_variants', array('product_id' => $id, 'name' => $uom), 1);
        }else{
            $q = $this->db->get_where('product_variants', array('product_id' => $id), 1);
        }
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductSmallVariant($product_id){
        $this->db->select('*')
                 ->where('product_id', $product_id)
                 ->order_by('qty_unit', 'ASC')
                 ->limit(1);
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getProductVariantByOptionID($option_id){
		$q = $this->db->get_where('product_variants', array('id' => $option_id), 1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
    public function getProductByID($id) {
        $warehouse_id = $this->session->userdata('warehouse_id');
        if ($warehouse_id) {
            $this->db->select('products.*,products.quantity AS qoh, units.name as unit, products.unit as unit_id,categories.type as cate_type, product_variants.qty_unit, erp_warehouses_products.quantity as qty');
            $this->db->join('categories','categories.id=products.category_id','left');
            $this->db->join('units', 'units.id = products.unit', 'left');
            $this->db->join('product_variants', 'products.id = product_variants.product_id', 'left');
            $this->db->join('purchase_request_items', 'products.id = purchase_request_items.product_id', 'left');
            $this->db->join('warehouses_products', 'products.id = warehouses_products.product_id', 'left');
            $this->db->where('erp_warehouses_products.warehouse_id', $warehouse_id);
            $q = $this->db->get_where('products', array('products.id' => $id), 1);
            if ($q->num_rows() > 0) {
                return $q->row();
            }
            return FALSE;
        } else {
            $this->db->select('products.*,products.quantity AS qty, units.name as unit, products.unit as unit_id,categories.type as cate_type, product_variants.qty_unit, erp_warehouses_products.quantity as wqty');
            $this->db->join('categories','categories.id=products.category_id','left');
            $this->db->join('units', 'units.id = products.unit', 'left');
            $this->db->join('product_variants', 'products.id = product_variants.product_id', 'left');
            $this->db->join('purchase_request_items', 'products.id = purchase_request_items.product_id', 'left');
            $this->db->join('warehouses_products', 'products.id = warehouses_products.product_id', 'left');
            $q = $this->db->get_where('products', array('products.id' => $id), 1);
            if ($q->num_rows() > 0) {
                return $q->row();
            }
            return FALSE;
        }
    }

    public function getProductByIDForModalView($id) {
        $warehouse_id = $this->session->userdata('warehouse_id');
        $warehouses_id = explode(',', $warehouse_id);
        if ($warehouse_id) {
            $this->db->select('products.*,products.quantity AS qoh, units.name as unit, products.unit as unit_id,categories.type as cate_type, product_variants.qty_unit, (SUM(erp_warehouses_products.quantity) / 2) as qty');
            $this->db->join('products', 'warehouses_products.product_id = products.id', 'left');
            $this->db->join('categories','categories.id=products.category_id','left');
            $this->db->join('units', 'units.id = products.unit', 'left');
            $this->db->join('product_variants', 'products.id = product_variants.product_id', 'left');

            if(count($warehouses_id) > 1) {
                $this->db->where_in('erp_warehouses_products.warehouse_id', $warehouses_id);
            }else {
                $this->db->where('erp_warehouses_products.warehouse_id', $warehouse_id);
            }
            $q = $this->db->get_where('warehouses_products', array('warehouses_products.product_id' => $id), 1);
            if ($q->num_rows() > 0) {
                return $q->row();
            }
            return FALSE;
        } else {
            $this->db->select('products.*,products.quantity AS qty, units.name as unit, products.unit as unit_id,categories.type as cate_type, product_variants.qty_unit, erp_warehouses_products.quantity as wqty');
            $this->db->join('categories','categories.id=products.category_id','left');
            $this->db->join('units', 'units.id = products.unit', 'left');
            $this->db->join('product_variants', 'products.id = product_variants.product_id', 'left');
            $this->db->join('purchase_request_items', 'products.id = purchase_request_items.product_id', 'left');
            $this->db->join('warehouses_products', 'products.id = warehouses_products.product_id', 'left');
            $q = $this->db->get_where('products', array('products.id' => $id), 1);
            if ($q->num_rows() > 0) {
                return $q->row();
            }
            return FALSE;
        }
    }
	
	public function getProductByIDWh($id,$warehouse_id) {
        $this->db->select('products.*,warehouses_products.quantity ,warehouses_products.quantity AS qoh, units.name as unit, products.unit as unit_id, sale_order_items.quantity_received as qty_received');
		$this->db->from('products');
        $this->db->join('units', 'units.id = products.unit', 'left');
		$this->db->join('warehouses_products','warehouses_products.product_id=products.id','left');
		$this->db->join('sale_order_items','products.id = sale_order_items.product_id','left');
		$this->db->where('warehouses_products.warehouse_id',  $warehouse_id);
		$this->db->where('products.id' , $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getProductAllByID($id) {
        $this->db->select('products.*');        
        $q = $this->db->get_where('products', array('products.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllCurrencies() {
        $q = $this->db->get('currencies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCurrencyByCode($code) {
        $q = $this->db->get_where('currencies', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllTaxRates() {
		$this->db->select('tax_rates.*');
        $q = $this->db->get('tax_rates');
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

    public function getTaxRateByID($id) {
        $q = $this->db->get_where('tax_rates', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getTaxRateByCode($code) {
        $q = $this->db->get_where('tax_rates', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllWarehouses() {
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllCategoriesMakeup() 
	{
        $q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllCategoriesMakeupSub() 
	{
        $q = $this->db->get('subcategories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getWarehouseByID($id) {
        $q = $this->db->get_where('warehouses', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getWarehouseByArrayID($id) {
        $this->db->where_in('id', JSON_decode($id));
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getWarehouseByCode($code) {
        $q = $this->db->get_where('warehouses', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getChartByID($id) {
        $q = $this->db->get_where('gl_charts', array('accountcode' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
	public function getAllCategories() {
        $q = $this->db->order_by('name')->get('categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	
	public function getAllSuppliers() {
        $q = $this->db->get_where('companies', array('group_name' => 'supplier'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getCategoryByID($id) {
        $q = $this->db->get_where('categories', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getGiftCardByID($id) {
    	$this->db->select("*,erp_users.username");
    	$this->db->where('erp_gift_cards.id',$id);
		$this->db->join('erp_users','erp_gift_cards.created_by = erp_users.id', 'left');
		$this->db->from('gift_cards');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getGiftCardByNO($no) {
        $q = $this->db->get_where('gift_cards', array('card_no' => $no), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getCategory()
    {
        $q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getGiftCardHistoryByNo($no) {
        $q = $this->db->get_where('gift_cards', array('card_no' => $no), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function getDepositByCompanyID($comapny_id) {
        $this->db->select('erp_companies.*,COALESCE(SUM(erp_deposits.amount), 0) as balance, 
							COALESCE(SUM(IF(erp_deposits.amount > 0, erp_deposits.amount, 0)), 0) as dep_amount,
							(SELECT COALESCE(SUM(IF(erp_sale_order.paid > 0, erp_sale_order.paid, 0)), 0) as tmp_deposit FROM erp_sale_order WHERE erp_sale_order.customer_id = erp_companies.id) as tmp_deposit');
		$this->db->where('erp_companies.id',$comapny_id);
		$this->db->join('erp_deposits','erp_companies.id = erp_deposits.company_id', 'left');
		$this->db->group_by('erp_companies.id');
		$this->db->from('erp_companies');
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
    }
	
	public function getDepositByPaymentID($id,$purchase_id){
        $q = $this->db->get_where('erp_deposits', array('payment_id' => $id,'po_id'=>$purchase_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
    public function updateInvoiceStatus() {
        $date = date('Y-m-d');
        $q = $this->db->get_where('invoices', array('status' => 'unpaid'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                if ($row->due_date < $date) {
                    $this->db->update('invoices', array('status' => 'due'), array('id' => $row->id));
                }
            }
            $this->db->update('settings', array('update' => $date), array('setting_id' => '1'));
            return true;
        }
    }

    public function modal_js() {
        return '<script type="text/javascript">' . file_get_contents($this->data['assets'] . 'js/modal.js') . '</script>';
    }

    public function getReference($field, $biller = null)
    {
		$biller_prefix = false;
		if($this->get_setting()->separate_ref == 1){
			$biller_prefix = true;
			if($biller){
				$condition = array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m'),'biller_id'=>json_decode($biller));
				if ($this->Settings->reference_format == 4 || $this->Settings->reference_format == 5) {
					$q = $this->db->get_where('order_ref', array('biller_id'=> JSON_decode($biller)), 1);
				} elseif($this->Settings->reference_format == 2) {
					$this->db
						 ->select('order_ref.*')
						 ->from('order_ref')
						 ->where('DATE_FORMAT(date,"%Y")', date('Y'))
						->where_in('biller_id', JSON_decode($biller))
						 ->limit(1);
					$q = $this->db->get();
				} else {
					$q = $this->db->get_where('order_ref',$condition, 1);
				}
			}else{
				$q = $this->db->get('order_ref', 1);
			}
		}else{
			if ($this->Settings->reference_format == 4 || $this->Settings->reference_format == 5) {
				$q = $this->db->get('order_ref', 1);
			} elseif($this->Settings->reference_format == 2) {
                //$q = $this->db->get_where('order_ref', array('DATE_FORMAT(date,"%Y")' => date('Y')), 1);
                $this->db
                    ->select('order_ref.*')
                    ->from('order_ref')
                    ->where('DATE_FORMAT(date,"%Y")', date('Y'))
                    ->where_in('biller_id', JSON_decode($biller))
                    ->limit(1);
                $q = $this->db->get();
			} else {
				$q = $this->db->get_where('order_ref', array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m')), 1);
			}
		}
        
        if ($q->num_rows() > 0) {
            $ref = $q->row();
            switch ($field) {
                case 'so':
					if($biller_prefix){
                        $so_prfix = $this->Settings->sales_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$so_prfix;
                        break;
					}else{
                        $prefix = $this->Settings->sales_prefix;
                        break;
					}

                case 'qu':
					if($biller_prefix){
                        $qu_prefix = $this->Settings->quote_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$qu_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->quote_prefix;
                        break;
					}

                case 'po':
					if($biller_prefix){
                        $po_prefix = $this->Settings->purchase_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$po_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->purchase_prefix;
                        break;
					}

                case 'to':
					if($biller_prefix){
                        $to_prefix = $this->Settings->transfer_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$to_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->transfer_prefix;
                        break;
					}

                case 'do':					
					if($biller_prefix){
                        $do_prfix = $this->Settings->delivery_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$do_prfix;
                        break;
					}else{
                        $prefix = $this->Settings->delivery_prefix;
                        break;
					}

                case 'pay':
					if($biller_prefix){
                        $pay_prefix = $this->Settings->payment_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$pay_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->payment_prefix;
                        break;
					}

                case 'pos':	
					if($biller_prefix){
                        $pos_prfix = 'POS';
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix . '/' . $pos_prfix;
                        break;
					}else{
                        $prefix = isset($this->Settings->sales_prefix) ? $this->Settings->sales_prefix . '/POS' : '';
                        break;
					}

                case 're':
                    if($biller_prefix){
                        $re_prefix = $this->Settings->return_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$re_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->return_prefix;
                        break;
					}

                case 'ex':
					if($biller_prefix){
                        $ex_prefix = $this->Settings->expense_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$ex_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->expense_prefix;
                        break;
					}
					
				case 'sp':
					if($biller_prefix){
                        $sp_prefix = $this->Settings->sale_payment_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$sp_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->sale_payment_prefix;
                        break;
					}

				case 'pp':
					if($biller_prefix){
                        $pp_prefix = $this->Settings->purchase_payment_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$pp_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->purchase_payment_prefix;
                        break;
					}

				case 'sl':
					if($biller_prefix){
                        $sl_prefix = $this->Settings->sale_loan_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$sl_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->sale_loan_prefix;
                        break;
					}

				case 'tr':
					if($biller_prefix){
                        $tr_prefix = $this->Settings->transaction_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$tr_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->transaction_prefix;
                        break;
					}

				case 'con':
					if($biller_prefix){
						$con_prefix = $this->Settings->convert_prefix;
						if($biller){
							$biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
							$prefix = $biller_prefix.'/'.$con_prefix;
						}else{
							$biller_prefix = null;
							$prefix = null;
						}
						break;
					}else{
						$prefix = $this->Settings->convert_prefix;
						break;
					}

                case 'rep':
					if($biller_prefix){
                        $rep_prefix = $this->Settings->returnp_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$rep_prefix;
                        break;

					}else{
                        $prefix = $this->Settings->returnp_prefix;
                        break;
					}

				case 'es':
					if($biller_prefix){
                        $es_prefix = $this->Settings->enter_using_stock_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$es_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->enter_using_stock_prefix;
                        break;
					}

				case 'esr':
					if($biller_prefix){
                        $esr_prefix = $this->Settings->enter_using_stock_return_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$esr_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->enter_using_stock_return_prefix;
                        break;
					}

				case 'sd':
					if($biller_prefix){
                        $sd_prefix = $this->Settings->supplier_deposit_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$sd_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->supplier_deposit_prefix;
                        break;
					}

				case 'sao':
					if($biller_prefix){
                        $sao_prefix = $this->Settings->sale_order_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$sao_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->sale_order_prefix;
                        break;
					}
					
				case 'poa':
					if($biller_prefix){
                        $poa_prefix = $this->Settings->purchase_order_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$poa_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->purchase_order_prefix;
                        break;
					}

				case 'pq':
					if($biller_prefix){
                        $pq_prefix = $this->Settings->purchase_request_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$pq_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->purchase_request_prefix;
                        break;
					}

				case 'jr':
					if($biller_prefix){
                        $jr_prefix = $this->Settings->journal_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$jr_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->journal_prefix;
                        break;
					}

				case 'qa':
					if($biller_prefix){
                        $qa_prefix = $this->Settings->adjustment_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$qa_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->adjustment_prefix;
                        break;
					}

				case 'st':
					if($biller_prefix){
                        $st_prefix = $this->Settings->stock_count_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$st_prefix;
                        break;

					}else{
                        $prefix = $this->Settings->stock_count_prefix;
                        break;
					}

				case 'adc':
					if($biller_prefix){
                        $adc_prefix = $this->Settings->adjust_cost_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$adc_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->adjust_cost_prefix;
                        break;
					}

				case 'pn':
					if($biller_prefix){
                        $adc_prefix = $this->Settings->project_plan_prefix;
                        $biller_prefix = $this->getBillerPrefix($biller)->biller_prefix;
                        $prefix = $biller_prefix.'/'.$adc_prefix;
                        break;
					}else{
                        $prefix = $this->Settings->project_plan_prefix;
                        break;
					}

				case 'pro':
						$prefix = $this->Settings->project_code_prefix;
						break;
				case 'cus':
						$prefix = $this->Settings->customer_code_prefix;

						break;
				case 'sup':
						$prefix = $this->Settings->supplier_code_prefix;
						break;
				case 'emp':
						$prefix = $this->Settings->employee_code_prefix;
						break;
                default:
                    $prefix = '';
            }

            $ref_no = (!empty($prefix)) ? $prefix . '/' : '';

            if ($prefix == $this->get_setting()->project_code_prefix || $prefix == $this->get_setting()->customer_code_prefix || $prefix == $this->get_setting()->supplier_code_prefix || $prefix == $this->get_setting()->employee_code_prefix) {
                $ref_no .= sprintf("%05s", $ref->{$field});
			} else {
				if ($this->Settings->reference_format == 1) {
	                $ref_no .= date('ym') . "/" . sprintf("%05s", $ref->{$field});
	            }elseif ($this->Settings->reference_format == 2) {
	                $ref_no .= date('Y') . "/" . sprintf("%05s", $ref->{$field});
	            } elseif ($this->Settings->reference_format == 3) {
	                $ref_no .= date('Y/m') . "/" . sprintf("%05s", $ref->{$field});
	            } elseif ($this->Settings->reference_format == 4) {
	                $ref_no .= sprintf("%05s", $ref->{$field});
				} elseif ($this->Settings->reference_format == 5) {
					$ref_no = sprintf("%05s", $ref->{$field});
	            } else {
	                $ref_no .= $this->getRandomReference();
	            }
	        }

            return $ref_no;
        }
        return FALSE;
    }

    public function getRandomReference($len = 12) {
        $result = '';
        for ($i = 0; $i < $len; $i++) {
            $result .= mt_rand(0, 9);
        }
		
        if ($this->getSaleByReference($result)) {
            $this->getRandomReference();
        }

        return $result;
    }

    public function getSaleByReference($ref) {
        $this->db->like('reference_no', $ref, 'before');
        $q = $this->db->get('sales', 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateReference($field, $biller = NULL)
    {
		if($this->session->userdata('biller_id')){
            $default_biller = JSON_decode($this->session->userdata('biller_id'));
		}else{
			$default_biller = $this->get_setting()->default_biller;
		}
		
		if($this->get_setting()->separate_ref == 1){
			if($biller){
				
				if ($this->get_setting()->reference_format == 4 || $this->get_setting()->reference_format == 5) {
					$q = $this->db->get_where('order_refs',array('biller_id'=>$biller), 1);
					if ($q->num_rows() > 0) {
						$ref = $q->row();
						$this->db->update('order_ref', array($field => $ref->{$field} + 1), array('biller_id'=>$biller));
						return TRUE;
					}
					return FALSE;
					
                } elseif ($this->get_setting()->reference_format == 2) {
                    $this->db
                        ->select('order_ref.*')
                        ->from('order_ref')
                        ->where('DATE_FORMAT(date,"%Y")', date('Y'))
                        ->where_in('biller_id', JSON_decode($biller))
                        ->limit(1);
                    $q = $this->db->get();
                    if ($q->num_rows() > 0) {
                        $ref = $q->row();
                        $this->db->update('order_ref', array($field => $ref->{$field} + 1), array('DATE_FORMAT(date,"%Y")' => date('Y'), 'biller_id' => $biller));
                        return TRUE;
                    }
                    return FALSE;


                } else {
					
					$q = $this->db->get_where('order_ref', array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m'), 'biller_id'=>$biller), 1);
					if ($q->num_rows() > 0) {
						$ref = $q->row();
						$this->db->update('order_ref', array($field => $ref->{$field} + 1), array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m'),'biller_id'=>$biller));
						return TRUE;
					}
					return FALSE;
					
				}

            } else { // End no $biller
				
				if($field == 'cus'){
                    $q = $this->db->get('order_ref', 1);
					if ($q->num_rows() > 0) {
						$ref = $q->row();
						$this->db->update('order_ref', array($field => $ref->{$field} + 1));
						return TRUE;
					}
					return FALSE;
				}
				if($field == 'sup'){
					$q = $this->db->get('order_ref', 1);
					if ($q->num_rows() > 0) {
						$ref = $q->row();
						$this->db->update('order_ref', array($field => $ref->{$field} + 1));
						return TRUE;
					}
					return FALSE;
				}
				$q = $this->db->get_where('order_ref', array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m'), 'biller_id'=>$default_biller), 1);
				if ($q->num_rows() > 0) {
					$ref = $q->row();
					$this->db->update('order_ref', array($field => $ref->{$field} + 1), array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m'),'biller_id'=>$default_biller));
					return TRUE;
				}
				return FALSE;
			}

        } else { // End get_setting->separate_ref
			
			if ($this->get_setting()->reference_format == 4 || $this->get_setting()->reference_format == 5) {
				
				$q = $this->db->get_where('order_ref', array(''), 1);
				if ($q->num_rows() > 0) {
					$ref = $q->row();
					$this->db->update('order_ref', array($field => $ref->{$field} + 1));
					return TRUE;
				}
				return FALSE;

            } elseif ($this->get_setting()->reference_format == 2) {
                $this->db
                    ->select('order_ref.*')
                    ->from('order_ref')
                    ->where('DATE_FORMAT(date,"%Y")', date('Y'))
                    ->where_in('biller_id', JSON_decode($biller))
                    ->limit(1);
                $q = $this->db->get();
                if ($q->num_rows() > 0) {
                    $ref = $q->row();
                    $this->db->update('order_ref', array($field => $ref->{$field} + 1), array('DATE_FORMAT(date,"%Y")' => date('Y'), 'biller_id' => $biller));
                    return TRUE;
                }
                return FALSE;

            } else {
				
				$q = $this->db->get_where('order_ref', array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m')), 1);
				if ($q->num_rows() > 0) {
					$ref = $q->row();
					$this->db->update('order_ref', array($field => $ref->{$field} + 1), array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m')));
					return TRUE;
				}
				return FALSE;
			}
		}
    }
	
	public function updateReference_old($field, $biller=NULL) {
		$default_biller = $this->get_setting()->default_biller;
		if($this->get_setting()->separate_ref == 1){
			if($biller){
				
				if ($this->get_setting()->reference_format == 4 || $this->get_setting()->reference_format == 5) {
					$q = $this->db->get_where('order_ref',array('biller_id'=>$biller), 1);
					if ($q->num_rows() > 0) {
						$ref = $q->row();
						$this->db->update('order_ref', array($field => $ref->{$field} + 1), array('biller_id'=>$biller));
						return TRUE;
					}
					return FALSE;
					
				} else {
					
					$q = $this->db->get_where('order_ref', array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m'), 'biller_id'=>$biller), 1);
					if ($q->num_rows() > 0) {
						$ref = $q->row();
						$this->db->update('order_ref', array($field => $ref->{$field} + 1), array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m'),'biller_id'=>$biller));
						return TRUE;
					}
					return FALSE;
					
				}
			} else {
				
				if ($this->get_setting()->reference_format == 4 || $this->get_setting()->reference_format == 5) {
					$q = $this->db->get('order_ref', 1);
					if ($q->num_rows() > 0) {
						$ref = $q->row();
						$this->db->update('order_ref', array($field => $ref->{$field} + 1), array('ref_id' => 1));
						return TRUE;
					}
					return FALSE;
				} else {
					$q = $this->db->get_where('order_ref', array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m')), 1);
					if ($q->num_rows() > 0) {
						$ref = $q->row();
						$this->db->update('order_ref', array($field => $ref->{$field} + 1), array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m')));
						return TRUE;
					}
					return FALSE;
				}
			}
		} else{
			
			if($biller){
				if ($this->get_setting()->reference_format == 4 || $this->get_setting()->reference_format == 5) {
					$q = $this->db->get('order_ref', 1);
					if ($q->num_rows() > 0) {
						$ref = $q->row();
						$this->db->update('order_ref', array($field => $ref->{$field} + 1));
						return TRUE;
					}
					return FALSE;
				} else {
					$q = $this->db->get_where('order_ref', array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m')), 1);
					if ($q->num_rows() > 0) {
						$ref = $q->row();
						$this->db->update('order_ref', array($field => $ref->{$field} + 1), array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m')));
						return TRUE;
					}
					return FALSE;
				}
				
			} else {
				
				if ($this->get_setting()->reference_format == 4 || $this->get_setting()->reference_format == 5) {
					$q = $this->db->get('order_ref', 1);
					if ($q->num_rows() > 0) {
						$ref = $q->row();
						$this->db->update('order_ref', array($field => $ref->{$field} + 1));
						return TRUE;
					}
					return FALSE;
				} else {
					$q = $this->db->get_where('order_ref', array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m')), 1);
					if ($q->num_rows() > 0) {
						$ref = $q->row();
						$this->db->update('order_ref', array($field => $ref->{$field} + 1), array('DATE_FORMAT(date,"%Y-%m")' => date('Y-m')));
						return TRUE;
					}
					return FALSE;
				}
			}
		}
		
		
    }
	

    public function checkPermissions() {

        $q = $this->db->get_where('permissions', array('group_id' => $this->session->userdata('group_id')), 1);
        if ($q->num_rows() > 0) {
            return $q->result_array();
        }
        return FALSE;
    }
    
    public function getPermission() {
        $q = $this->db->get_where('permissions', array('group_id' => $this->session->userdata('group_id')), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getNotifications() {
        $date = date('Y-m-d H:i:s', time());
        $this->db->where("from_date <=", $date);
        $this->db->where("till_date >=", $date);

        if ($this->Supplier) {
            $this->db->where('scope', 4);
        } elseif ($this->Customer) {
            $this->db->where('scope', 1)->or_where('scope', 3);
        } elseif (!$this->Customer && !$this->Supplier) {
            $this->db->where('scope', 2)->or_where('scope', 3);
        }
        $this->db->where("till_date >=", $date);

        $q = $this->db->get("notifications");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getUpcomingEvents() {
        $dt = date('Y-m-d');
        $this->db->where('start >=', $dt)->order_by('start')->limit(5);
        if ($this->Settings->restrict_calendar) {
            $this->db->where('user_id', $this->session->userdata('user_id'));
        }

        $q = $this->db->get('calendar');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getUserGroup($user_id = false) {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $group_id = $this->getUserGroupID($user_id);
        $q = $this->db->get_where('groups', array('id' => $group_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getUserGroupID($user_id = false) {
        $user = $this->getUser($user_id);
        return $user->group_id;
    }

    public function getWarehouseProductsVariants($option_id, $warehouse_id = NULL) {
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPurchasedItem($where_clause) {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get_where('purchase_items', $where_clause);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function syncVariantQty($variant_id, $warehouse_id, $product_id = NULL) {

        $balance_qty 	= $this->getBalanceQuantity($product_id);
        $wh_balance_qty = $this->getBalanceQuantity($product_id, $warehouse_id);		
		$unit_qty 		= $this->getUnitQuantity($variant_id, $product_id);

        if ($this->db->update('product_variants', array('quantity' => $balance_qty), array('id' => $variant_id))) {
            if ($this->getWarehouseProductsVariants($variant_id, $warehouse_id)) {
                $this->db->update('warehouses_products_variants', array('quantity' => ($wh_balance_qty / $unit_qty->qty_unit)), array('option_id' => $variant_id, 'warehouse_id' => $warehouse_id));
            } else {
                if($wh_balance_qty) {
                    $this->db->insert('warehouses_products_variants', array('quantity' => ($wh_balance_qty / $unit_qty->qty_unit), 'option_id' => $variant_id, 'warehouse_id' => $warehouse_id, 'product_id' => $product_id));
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    public function getWarehouseProducts($product_id, $warehouse_id = NULL) {
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
    public function getPurchaseBalanceQuantity($product_id, $warehouse_id = NULL) {
        $this->db->select('SUM(COALESCE(quantity_balance, 0)) as stock', False);
        $this->db->where('product_id', $product_id)->where('quantity_balance !=', 0);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->stock;
        }
        return 0;
    }
	
	public function getProudctBalanceQuantity($product_id, $warehouse_id = NULL) {
        $this->db->select('SUM(COALESCE('.$this->db->dbprefix('product_variants').'.quantity, 0)) as stock', False);
		$this->db->join('warehouses_products_variant', 'warehouses_products_variants.product_id = product_variants.product_id');
        $this->db->where($this->db->dbprefix('product_variants').'.product_id', $product_id)->where($this->db->dbprefix('product_variants').'.quantity !=', 0);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->stock;
        }
        return 0;
    }
	
	public function getProductQty($product_id){
		$this->db->select('SUM(COALESCE(quantity, 0)) as stock', False);
		$this->db->where('id',$product_id);
		$q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->stock;
        }
        return 0;
	}
	
    public function syncProductQty($product_id, $warehouse_id) {
        $balance_qty = $this->getBalanceQuantity($product_id);
        $wh_balance_qty = $this->getBalanceQuantity($product_id, $warehouse_id);

        if ($this->db->update('products', array('quantity' => $balance_qty), array('id' => $product_id))) {
            if ($this->getWarehouseProducts($product_id, $warehouse_id)) {
                $this->db->update('warehouses_products', array('quantity' => $wh_balance_qty), array('product_id' => $product_id, 'warehouse_id' => $warehouse_id));
            } else {
                if( ! $wh_balance_qty) { $wh_balance_qty = 0; }
                $this->db->insert('warehouses_products', array('quantity' => $wh_balance_qty, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id));
            }
            return TRUE;
        }
        return FALSE;
    }
    
    function getCustomerNameByID($cus_id = null)
	{
        $this->db->select('name, company');
		$this->db->where(array('id' => $cus_id));
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function getSaleByID($id) {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	function getSellingByID($cus_id = null)
	{
        $this->db->select("id, date, reference_no, biller, customer, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status");
		$this->db->where(array('id' => $cus_id));
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function getSalePayments($sale_id) {
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id, 'return_id' => NULL));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
    public function syncSalePaymentsCur($id) {
		
        $sale = $this->getSaleByID($id);
        $payments = $this->getSalePayments($id);
        $paid = 0;
        foreach ($payments as $payment) {
            if ($payment->type == 'returned') {
				$paid -= $sale->paid;
            } else {
				$paid += $payment->amount;
            }
        }
		
		$sale_status = $sale->sale_status;
        $payment_status = $paid <= 0 ? 'pending' : $sale->payment_status;
        if ($paid <= 0 && $sale->due_date <= date('Y-m-d')) {
            if ($payment->type == 'returned') {
				$payment_status = 'returned';
				$payment_term = 0;
				$paid = -1 * abs($paid);
			} else {
				if($sale->paid == 0 && $sale->grand_total == 0 || $sale->payment_status == 'paid'){
					$payment_status = 'paid';
					$sale_status = 'completed';
				} else {
					$payment_status = 'due';
				}
			}
        } elseif ($this->erp->formatDecimal($sale->grand_total) > $this->erp->formatDecimal($paid) && $paid > 0) {
            $payment_status = 'partial';
        } elseif ($this->erp->formatDecimal($sale->grand_total) <= $this->erp->formatDecimal($paid)) {
			if ($payment->type == 'returned') {
				$payment_status = 'returned';
				$paid = -1 * abs($paid);
			}else{
				$payment_status = 'paid';
				$sale_status = 'completed';
			}
			$payment_term = 0;
        }

        if ($this->db->update('sales', array('paid' => $paid, 'sale_status' => $sale_status ,'payment_status' => $payment_status,'payment_term'=>$payment_term), array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function getReturnSaleBySID($sale_id = NULL) {
		$this->db->select("SUM(COALESCE(grand_total, 0)) as returned, SUM(COALESCE(paid, 0)) as refunded");
		$q = $this->db->get_where('return_sales', array('sale_id' => $sale_id));
		if ($q->num_rows() > 0) {  
            return $q->row();
        }
		return false;
	}
	
	public function syncSalePayments($id){
        $sale = $this->getSaleByID($id);
		$return = $this->getReturnSaleBySID($id);
        $payments = $this->getSalePayments($id);
        $paid = 0;
		
		
		if($payments)
		{
			foreach ($payments as $payment) {
				if ($payment->type == 'returned') {
					$paid -= (($payment->amount + $payment->discount) -$payment->extra_paid);
					//$paid -= $sale->paid;
				} else {
					$paid += (($payment->amount + $payment->discount)- $payment->interest_paid - $payment->extra_paid);
					//$paid += $sale->paid;
				}
			}
		}
		$real_paid = $paid;
		$paid += $return->returned - $return->refunded;
		$sale_status = $sale->sale_status;
        $payment_status = $paid <= 0 ? 'due' : $sale->payment_status;
       
        if ($paid <= 0 && $sale->due_date <= date('Y-m-d')) {
            if ($payment->type == 'returned') {
				$payment_status = 'returned';
				
				$paid = -1 * abs($paid);
			}else{
				if($sale->paid == 0 && $sale->grand_total == 0){
					$payment_status = 'paid';
					$sale_status = 'completed';
				}else{
					$payment_status = 'due';
				}
			}
			
        } elseif ($this->erp->formatDecimal($sale->grand_total) > $this->erp->formatDecimal($paid) && $paid > 0) {
            $payment_status = 'partial';
        } elseif ($this->erp->formatDecimal($sale->grand_total) <= $this->erp->formatDecimal($paid)) {
			if ($payment->type == 'returned') {
				$payment_status = 'returned';
				$paid = -1 * abs($paid);
			}else{
				$payment_status = 'paid';
				$sale_status = 'completed';
			}
			
        }
		
		if($this->get_setting()->stock_deduction=='delivery'){
			if ($this->db->update('sales', array('paid' => $real_paid,'payment_status' => $payment_status), array('id' => $id))) {
				return true;
			}
		}else{
			if ($this->db->update('sales', array('paid' => $real_paid, 'payment_status' => $payment_status), array('id' => $id))) {
				return true;
			}
		}
        return FALSE;
    }

    public function getPurchaseByID($id) {
        $q = $this->db->get_where('purchases', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPurchasePayments($purchase_id) {
        $q = $this->db->get_where('payments', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function syncPurchasePayments($id) {
        $purchase = $this->getPurchaseByID($id);
        $payments = $this->getPurchasePayments($id);
        $paid = 0;
        foreach ($payments as $payment) {
            $paid += ($payment->amount + $payment->discount);
        }
		
        $payment_status = $paid <= 0 ? 'due' : $purchase->payment_status;
		$payment_term = $purchase->payment_term;
        if ($this->erp->formatDecimal($purchase->grand_total) > $this->erp->formatDecimal($paid) && $paid > 0) {
            $payment_status = 'partial';
        } elseif ($this->erp->formatDecimal($purchase->grand_total) == $this->erp->formatDecimal($paid)) {
            $payment_status = 'paid';
			$payment_term = 0;
        }

        if ($this->db->update('purchases', array('paid' => $paid, 'payment_status' => $payment_status, 'payment_term' => $payment_term), array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    private function getBalanceQuantity($product_id, $warehouse_id = NULL) {
        $this->db->select("SUM(COALESCE(quantity_balance, 0)) as stock", False);
        $this->db->where('product_id', $product_id);
		$this->db->where('status', 'received');
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->stock;
        }
        return 0;
    }
    
    public function getProductType($product_id){
        $this->db->select('type');
        $this->db->where('id', $product_id);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->type;
        }
        return FALSE;
    }

    private function getBalanceVariantQuantity($variant_id, $warehouse_id = NULL) {
        $this->db->select('SUM(COALESCE(quantity_balance, 0)) as stock', False);
        $this->db->where('option_id', $variant_id)->where('quantity_balance !=', 0);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            $data = $q->row();
            return $data->stock;
        }
        return 0;
    }
	
	public function getPurchaseItemBySaleItem($id, $type){
		$q = $this->db->get_where('purchase_items', array('transaction_id' => $id, 'transaction_type'=>$type), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    /*************/
	public function getWarehouseQty($product_id, $warehouse_id){
		$this->db->select("warehouses_products.*");
		$this->db->from("warehouses_products");
		$this->db->where(array("product_id" => $product_id,"warehouse_id" => $warehouse_id));
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	public function getUsingStockById($id){
		$q = $this->db->get_where('enter_using_stock', array('id' => $id) );
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	public function getUsingStockByRef($ref){
		$q = $this->db->get_where('enter_using_stock_items', array('reference_no' => $ref) );
		if($q->num_rows()>0){
			return $q->result();
		}
		return false;
	}

    public function calculateAVCost($product_id, $warehouse_id, $net_unit_price, $unit_price, $quantity, $product_name, $option_id, $item_quantity, $transaction_type, $transaction_id, $status, $expiry, $old_sqty = 0, $date,$product_type)
    {
        $warehouse_qty = $this->getWarehouseQty($product_id, $warehouse_id)->quantity;
        $warehouse_qty += $old_sqty;

        if (($quantity > $warehouse_qty && !$this->Settings->overselling) || ($warehouse_qty <= 0 && !$this->Settings->overselling)) {
            $pi = $this->site->getProductByID($product_id);

            $this->session->set_flashdata('error', sprintf(lang("quantity_out_of_stock_for_%s"), ($pi->product_name ? $pi->product_name : $product_name)));
            redirect($_SERVER["HTTP_REFERER"]);
        } elseif ($quantity > 0) {
			$getProduct = $this->site->getProductByID($product_id);
			
			$cost[] = array(
                'date' => $date,
				'pi_overselling' 	=> 1, 
				'product_id' 		=> $product_id, 
				'product_code'		=> $getProduct->code,
				'product_name'		=> $getProduct->name,
				'quantity_balance' 	=> $transaction_type == 'PRODUCT RETURN'?$quantity:(0 - $quantity),
				'warehouse_id' 		=> $warehouse_id, 
				'option_id' 		=> $option_id,
				'transaction_type'	=> $transaction_type,
				'transaction_id'	=> $transaction_id,
				'status'			=> $status,
				'expiry'			=> $expiry,
                'product_type'      => $product_type
			);
        }

        return $cost;
    }
	
	public function calculateAVCosts($product_id, $warehouse_id, $net_unit_price, $unit_price, $quantity, $product_name, $option_id, $item_quantity, $shipping) {
        $real_item_qty = $quantity;
		$average_cost = 0;
        if ($pis = $this->getPurchasedItems($product_id, $warehouse_id, $option_id)) {
            $cost_row = array();
            $quantity = $item_quantity;
            $balance_qty = $quantity;
            $total_net_unit_cost = 0;
            $total_unit_cost = 0;
			$total_unit_costs = 0;
			$total_shipping = 0;

            foreach ($pis as $pi) {
				
				$oldcost = $this->getoldcost($product_id);
				$getoldcost = $oldcost->cost;
				$old_qty = $oldcost->quantity;

				if($getoldcost == 0 || $getoldcost == ''){
					if ($pi->item_discount || $shipping) {
						$percentage = '%';
						$purchase_discount = $pi->discount;
						$opos = strpos($purchase_discount, $percentage);
						if ($opos !== false) {
							$ods = explode("%", $purchase_discount);
							//$total_new_cost = ($unit_price * $quantity)-(($unit_price * $quantity)*($pi->discount/100));
							$total_new_cost = (($unit_price * $quantity) * (Float)($ods[0])) / 100;
						} else {
							$total_new_cost = (($unit_price * $quantity)) - $pi->item_discount;
						}
						$average_cost = ($total_new_cost/$quantity);
					} else {
						$average_cost = $unit_price;
					}
				}else{
					$total_old_cost = $old_qty * $getoldcost;
					$total_new_cost = ($unit_price * $quantity);
					
					if ($pi->item_discount) {
						$percentage = '%';
						$purchase_discount = $pi->discount;
						$opos = strpos($purchase_discount, $percentage);
						if ($opos !== false) {
							$ods = explode("%", $purchase_discount);
							//$total_new_cost = ($unit_price * $quantity)-(($unit_price * $quantity)*($pi->discount/100));
							$total_new_cost = (($unit_price * $quantity) * (Float)($ods[0])) / 100;
						} else {
							$total_new_cost = ($unit_price * $quantity) - $pi->item_discount;
						}
					}
					
					$total_qty = $quantity + $old_qty;
					$total_cost = $total_new_cost + $total_old_cost;
					
					$average_cost = ($total_cost/$total_qty);
				}
			}
		}
        return $average_cost;
    }
	
	public function AverageCost($new_cost, $new_qty, $old_cost = NULL, $old_qty = NULL)
	{
		$total_old_cost = $old_cost * $old_qty;
		$total_qty 		= $new_qty + $old_qty;
		$avgcost 		= ($new_cost + $total_old_cost) / $total_qty;
		return $avgcost;
	}
	
	public function getoldcost($product_id){
		$this->db->select('cost, quantity, price, type');
        $q = $this->db->get_where('products', array('id'=>$product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function calculateAverageCostShipping($product_id, $warehouse_id, $net_unit_cost, $quantity,$option_id, $shipping, $subtotal, $t_po_item_amount){	
		
		$costunit = 0;
		//if ($pis = $this->getPurchasedItems($product_id, $warehouse_id, $option_id)) {
		$freight_net = $shipping;
		$unit_cost = $net_unit_cost;
		$total_cost_line = $subtotal;
		$qty_new_receive = $quantity;
		
		$f_percents = ($total_cost_line / $t_po_item_amount) * 100;
		
		$f_atm = $freight_net * ($f_percents / 100);
		
		$f_cost = $f_atm / $qty_new_receive;
		
		$f_total_cost = $total_cost_line + $f_atm;
		
		$average_cost = $f_total_cost/$qty_new_receive;
		
		if ($pis = $this->getPurchasedItems_order($product_id, $warehouse_id, $option_id)) {

			$oldcost = $this->getoldcost($product_id);
			$old_cost = $oldcost->cost;
			$old_qty = $oldcost->quantity;

			if($option_id){
				$option = $this->getProductVariantByOptionID($option_id);
				$new_cost = ($unit_cost + $f_cost) / $option->qty_unit;
				
			} else {
				$new_cost = ($unit_cost + $f_cost);
			}

			$new_qty = $qty_new_receive;
			$total_old_cost = $old_qty * $old_cost;
			$total_new_cost = $new_cost * $new_qty;
			$total_qty = $new_qty + $old_qty;
			$total_cost = $total_new_cost + $total_old_cost;
			if($old_cost == 0 && $old_qty > 0 || $old_cost == ''){
				$average_cost = $total_new_cost/$total_qty;
			}else{
				$average_cost = $total_cost/$total_qty;
			}
		}
		return $average_cost;
		
	}
	
	public function calculateAverageCost($product_id, $unit_cost, $quantity, $product_amount, $item_discount, $order_discount, $shipping, $subtotal, $item_option){
		$average_cost = 0;
		$discount = 0;
		$ship = 0;
		//New Product which have no cost products and purchase_item;
		$average_cost = $unit_cost;
		
		//Get old cost from products
		$oldcost = $this->getoldcost($product_id);
		$old_cost = $oldcost->cost;
		$old_qty = $oldcost->quantity;
		if($option = $this->getProductVariantByOptionID($item_option)){
			$unit_cost = $unit_cost / $option->qty_unit;
		}
		//Have Discounts and Shipping
		if ($item_discount || $order_discount || $shipping) {
			if ($order_discount) {
				$percentage = '%';
				$opos = strpos($order_discount, $percentage);
				if ($opos !== false) {
					$ods = explode("%", $order_discount);
					$discount = (($unit_cost * $quantity) * (Float)($ods[0])) /100 ;
				} else {
					$discount = $this->erp->formatPurDecimal($order_discount/$product_amount);
				}
			}
			if($shipping){
				if($product_amount > 1){
					$ship = ($shipping * $unit_cost)/$subtotal; 
				}else{
					$ship = $shipping;
				}
			}
			$total_new_cost = ($unit_cost * $quantity)-($item_discount + $discount) + $ship;
		} else {
			$total_new_cost = $unit_cost * $quantity;
		}
		
		$total_old_cost = $old_cost * $old_qty;
		$total_cost     = $total_new_cost + $total_old_cost;
		$total_qty      = $quantity + $old_qty;
		if($old_qty < 0){
			if($total_qty <= 0){
				return $old_cost;
			}else{
				return ($total_new_cost/$quantity);
			}
		}else{
			$average_cost   = $total_cost/$total_qty;
		}
		return $average_cost;
	}
	
	/******** Nak
	********* Add Function calculateAVGCost2017
	********* 04/05/2017
	*********/
	
	public function calculateAVGCost2017($product_id, $shipping, $quantity, $price = null, $total_price = null, $cost = null, $item_discount = null, $order_discount = null, $variant_id = null, $subtotal, $total)
	{
		$oldcost   				= $this->getoldcost($product_id);
		$old_cost  				= $oldcost->cost;
		$old_qty   				= $oldcost->quantity;
		$percent_price 			= 0;
		$shipping_cost 			= 0;
		
		if($old_qty < 0){
			$old_qty  			= 0;
		}
		/*
		$total_unit_price 		= $cost * $quantity;
		$percent_price 			= ($total_unit_price / $total_price);
		$shipping_cost 			= ($shipping * $percent_price);
		$last_cost 				= ($shipping_cost / $quantity) + $cost;
		*/
		//Total New Cost = (Subtotal * (Total + Shipping))/Total
		$totalNewCost  			= ($subtotal * ($total + $shipping))/$total;
		$totalQuantity 			= $old_qty + $quantity;
		$totalOldCost  			= $old_cost * $old_qty;
		//$totalNewCost  		= $quantity * $last_cost;
		//echo $subtotal .'=='.$total.'<br>';
		//echo 'TQ '. $totalQuantity .' TOC ' . $totalOldCost .' TNC '. $totalNewCost .' QTY '.$quantity.'<br>';
		if ($oldcost->type != 'service') {
			$avgcost 			= ($totalNewCost + $totalOldCost) / $totalQuantity;
		} else {
			$avgcost 			= $cost;
		}
		
		return array('avgcost' 	=> $avgcost, 'shipping_cost' => $shipping_cost);
	}
	
	/******** Nak
	********* Add Function editcalculateAVGCost2017
	********* 04/05/2017
	*********/
	
	public function editCalculateAVGCost2017($product_id, $shipping, $quantity, $price, $total_price, $cost, $item_discount, $order_discount, $variant_id, $purcahse_id, $subtotal, $total){
		$pur_cost  			= $this->purchases_model->getPurcahseItemByPurIDProID($purcahse_id, $product_id);
		$pro_cost			= $this->getProductAllByID($product_id);
		$purchase			= $this->getPurchaseByID($purcahse_id);
		
		//================== Find Old Total Cost ==================//
		$oldcost 			= ($pur_cost->subtotal * ($purchase->stotal + $purchase->shipping))/$purchase->stotal;
		//========================== End ==========================//

		$old_cost  			= ($pro_cost->cost * $pro_cost->quantity) - $oldcost;
		$old_qty   			= $pro_cost->quantity - $pur_cost->quantity_balance;
		$percent_price 		= 0;
	
		if($old_qty < 0){
			$old_qty  		= 0;
		}
		if($old_cost < 0){
			$old_cost 		= 0; 
		}
		/*
		$total_unit_price 	= $cost * $quantity;
		$percent_price 		= ($total_unit_price / $total_price);
		$shipping_cost 		= ($shipping * $percent_price);
		$last_cost 			= ($shipping_cost / $quantity) + $cost;
		$totalNewCost  		= $quantity * $last_cost;
		*/
		
		$totalNewCost  		= ($subtotal * ($total + $shipping))/$total;
		$totalQuantity 		= $old_qty + $quantity;
		$totalOldCost  		= $old_cost;
		
		if ($pro_cost->type != 'service') {
			$avgcost 			= ($totalNewCost + $totalOldCost) / $totalQuantity;
		} else {
			$avgcost 			= $cost;
		}
		//echo 'Qty '. $quantity. 'Cost '. $cost.' TNC '.$totalNewCost.' TOC '.$totalOldCost.' TQ '.$totalQuantity.'<br/>';
		return array('avgcost' => $avgcost, 'shipping_cost' => $shipping_cost);
	}
	
	public function updatePurItem($id){
		$SQLdata = array(
			'transaction_id' => $id
		);
		$this->db->where('id',$id);
		$this->db->update('purchase_items', $SQLdata);
		return $this->db->affected_rows();
	}
	
	public function updateQualityPro($SQLdata, $id){
		$this->db->where('code',$id);
		$this->db->update('products',$SQLdata);
		return $this->db->affected_rows();
	}
	
	public function updateCostPro($SQLdata, $id){
		$this->db->where_in('id',$id);
		$this->db->update('products',$SQLdata);
		return $this->db->affected_rows();
	}
	
	public function calculateCONAVCost($product_id, $total_raw_cost, $total_fin_qty, $unit_qty) {
		$percent 		= 0;
		$qty 			= 0;
		$total_new_cost = 0;
		$total_qty		= 0;
		$total_old_cost = 0;
		$old_product	= $this->getProductAllByID($product_id);
		
		$total_qty		= $unit_qty;
		/*
		if($finish_cost){
			$percent 	= $each_cost/$finish_cost;
		}else{
			$percent	= 1;
		}
		$qty 			= $quantity;
		if(!$qty_unit){
			$qty_unit 	= 1;
		}
		*/
		//========================== AVG Cost ============================//

		if($old_product->cost > 0){
			$total_qty		= $unit_qty + $old_product->quantity;
			$total_old_cost = $old_product->quantity * $old_product->cost;
		}
		
		$total_new_cost = ($total_raw_cost * $unit_qty)/$total_fin_qty;
		//echo 'TRC '. $total_raw_cost .' UQTY '. $unit_qty .' TFQ '. $total_fin_qty .' TNC '. $total_new_cost .' TOC '. $total_old_cost .' TQTY '. $total_qty .'<br/>';
		$average_cost 	= ($total_new_cost + $total_old_cost) / $total_qty;
		
		//============================ End ===============================//

        return array('avg'=>$average_cost, 'cost' => $total_new_cost);
    }
	
	public function editcalculateCONAVCost($product_id, $total_raw_cost, $total_fin_qty, $unit_qty, $combo_item) {
		$percent 		= 0;
		$qty 			= 0;
		$total_new_cost = 0;
		$total_qty		= 0;
		$total_old_cost = 0;
		$old_product	= $this->getPurchaseItemByTranId($combo_item);
		$total_qty		= $unit_qty;
		/*
		if($finish_cost){
			$percent 	= $each_cost/$finish_cost;
		}else{
			$percent	= 1;
		}
		$qty 			= $quantity;
		if(!$qty_unit){
			$qty_unit 	= 1;
		}
		*/
		//========================== AVG Cost ============================//

		if($old_product->unit_cost > 0){
			$total_qty		= $unit_qty + $old_product->cb_qty;
			$total_old_cost = $old_product->cb_qty * isset($old_product->cb_cost);
		}
		
		$total_new_cost = ($total_raw_cost * $unit_qty)/$total_fin_qty;
		//echo 'TRC '. $total_raw_cost .' UQTY '. $unit_qty .' TFQ '. $total_fin_qty .' TNC '. $total_new_cost .' TOC '. $total_old_cost .' TQTY '. $total_qty .'<br/>';		
		$average_cost 	= ($total_new_cost + $total_old_cost) / $total_qty;
		
		//============================ End ===============================//
		
        return array('avg'=>$average_cost, 'cost' => $total_new_cost);
    }

	public function getPurchaseItemByTranId($combo_item){
		$q = $this->db->get_where('purchase_items', array('transaction_id' => $combo_item));
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getConvertItemsById($convert_id){
		$this->db->select('convert_items.status,convert_items.convert_id,products.quantity AS c_quantity ,products.cost AS pcost');
		$this->db->join('products', 'products.id = convert_items.product_id', 'INNER');
		$this->db->where(array('convert_items.convert_id'=> $convert_id));
		$query = $this->db->get('convert_items');
		
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
	public function calculateCosts($unit_price, $item_quantity, $shipping){
		$new_unit_cost = ($unit_price*$item_quantity)+$shipping;
		$final_cost    = $new_unit_cost / $item_quantity;
		return $final_cost;
	}
	
	public function calculateCost($unit_price, $item_quantity, $shipping){
		$new_unit_cost = ($unit_price*$item_quantity);
		$final_cost    = $new_unit_cost / $item_quantity;
		return $final_cost;
	}
	
	public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL) 
	{
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, SUM(quantity) as quantity, SUM(quantity_balance) as quantity_balance, net_unit_cost, unit_cost, item_tax,item_discount,discount');
        $this->db->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->where('quantity_balance !=', 0);
        if ($option_id) {
            $this->db->where('option_id', $option_id);
        }
        $this->db->group_by('product_id');
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
	
	public function getPurchasedItems_order($product_id, $warehouse_id, $option_id = NULL)
	{
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, unit_cost, item_tax,item_discount,discount');
        $this->db->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->where('quantity_balance !=', 0);
        if ($option_id) {
            $this->db->where('option_id', $option_id);
        }
        $this->db->group_by('id');
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get('erp_purchase_order_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getShippingItems($id) 
	{
        $this->db->select('shipping');
        $this->db->where('id', $id);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductComboItems($pid, $warehouse_id = NULL)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name, products.type as type, combo_items.unit_price as unit_price, warehouses_products.quantity as quantity')
            ->join('products', 'products.code=combo_items.item_code', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('combo_items.id');
        if($warehouse_id) {
            $this->db->where('warehouses_products.warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('combo_items', array('combo_items.product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }

    public function item_costing($item, $pi = NULL)
    {
        $item_quantity = $pi ? $item['aquantity'] : $item['quantity'];
        if (!isset($item['option_id']) || $item['option_id'] == 'null') {
            $item['option_id'] = NULL;
        }


        if ($this->Settings->accounting_method != 2 && !$this->Settings->overselling) {

            if ($this->site->getProductByID($item['product_id'])) {
                if ($item['product_type'] == 'standard') {

                    $cost = $this->site->calculateAVCost($item['product_id'], $item['warehouse_id'], $item['net_unit_price'], $item['unit_price'], $item['quantity'], $item['product_name'], $item['option_id'], $item_quantity, $item['transaction_type'], $item['transaction_id'], $item['status']);
                } elseif ($item['product_type'] == 'combo') {
                    $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                    foreach ($combo_items as $combo_item) {
                        $pr = $this->getProductByCode($combo_item->code);
                        if ($pr->tax_rate) {
                            $pr_tax = $this->site->getTaxRateByID($pr->tax_rate);
                            if ($pr->tax_method) {
                                $item_tax 		= $this->erp->formatDecimal((($combo_item->unit_price) * $pr_tax->rate) / (100 + $pr_tax->rate));
                                $net_unit_price = $combo_item->unit_price - $item_tax;
                                $unit_price 	= $combo_item->unit_price;
                            } else {
                                $item_tax 		= $this->erp->formatDecimal((($combo_item->unit_price) * $pr_tax->rate) / 100);
                                $net_unit_price = $combo_item->unit_price;
                                $unit_price 	= $combo_item->unit_price + $item_tax;
                            }
                        } else {
                            $net_unit_price 	= $combo_item->unit_price;
                            $unit_price 		= $combo_item->unit_price;
                        }
                        if ($pr->type == 'standard') {
                            $cost = $this->site->calculateAVCost($pr->id, $item['warehouse_id'], $net_unit_price, $unit_price, ($combo_item->qty * $item['quantity']), $pr->name, NULL, $item_quantity, $item['transaction_type'], $item['transaction_id'], $item['status']);
                        } else {
                            $cost = array(
                                array(
                                    'date' 					 => date('Y-m-d'),
                                    'product_id' 			 => $pr->id,
                                    'product_code' 			 => $pr->code,
                                    'product_name' 			 => $pr->name,
                                    'product_name' 			 => $pr->type,
                                    'sale_item_id' 			 => 'sale_items.id',
                                    'purchase_item_id' 		 => NULL,
                                    'quantity' 				 => ($combo_item->qty * $item['quantity']),
                                    'purchase_net_unit_cost' => 0,
                                    'purchase_unit_cost' 	 => 0,
                                    'sale_net_unit_price' 	 => $combo_item->unit_price,
                                    'sale_unit_price' 		 => $combo_item->unit_price,
                                    'quantity_balance' 		 => NULL,
                                    'inventory' 			 => NULL,
                                    'transaction_type' 		 => $item['transaction_type'],
                                    'transaction_type' 		 => $item['transaction_id'],
                                    'status' 				 => $item['status']
                                )
                            );
                        }
                    }
                } else {
                    $cost = array(
                        array(
                            'date' 						=> date('Y-m-d'),
                            'product_id' 				=> $item['product_id'],
                            'product_code' 				=> $item['product_code'],
                            'product_name' 				=> $item['product_name'],
                            'product_type' 				=> $item['product_type'],
                            'sale_item_id' 				=> 'sale_items.id',
                            'purchase_item_id' 			=> NULL,
                            'quantity' 					=> $item['quantity'],
                            'purchase_net_unit_cost' 	=> 0,
                            'purchase_unit_cost' 		=> 0,
                            'sale_net_unit_price' 		=> $item['net_unit_price'],
                            'sale_unit_price' 			=> $item['unit_price'],
                            'quantity_balance' 			=> NULL,
                            'inventory' 				=> NULL,
                            'transaction_type' 			=> $item['transaction_type'],
                            'transaction_type' 			=> $item['transaction_id'],
                            'status' 					=> $item['status']
                        )
                    );
                }
            } elseif ($item['product_type'] == 'manual') {
                $cost = array(
                    array(
                        'date' 						=> date('Y-m-d'),
                        'product_id' 				=> $item['product_id'],
                        'sale_item_id' 				=> 'sale_items.id',
                        'purchase_item_id' 			=> NULL,
                        'quantity' 					=> $item['quantity'],
                        'purchase_net_unit_cost' 	=> 0,
                        'purchase_unit_cost' 		=> 0,
                        'sale_net_unit_price' 		=> $item['net_unit_price'],
                        'sale_unit_price' 			=> $item['unit_price'],
                        'quantity_balance' 			=> NULL,
                        'inventory' 				=> NULL,
                        'transaction_type' 			=> $item['transaction_type'],
                        'transaction_type' 			=> $item['transaction_id'],
                        'status' 					=> $item['status']
                    )
                );
            }
        } else {

            if ($this->site->getProductByID($item['product_id'])) {
                if ($item['product_type'] == 'standard') {
                    $cost = $this->site->calculateAVCost($item['product_id'], $item['warehouse_id'], $item['net_unit_price'], $item['unit_price'], $item['quantity'], $item['product_name'], $item['option_id'], $item_quantity, (isset($item['transaction_type'])?$item['transaction_type']:''), (isset($item['transaction_id'])?$item['transaction_id']:''),(isset($item['status'])?$item['status']:''),$item['expiry']?$item['expiry']:NULL, $item['old_sqty'],null,$item['product_type']);
                } elseif ($item['product_type'] == 'combo') {
                    $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                    foreach ($combo_items as $combo_item) {
                        $cost = $this->site->calculateAVCost($combo_item->id, $item['warehouse_id'], ($combo_item->qty * $item['quantity']), $item['unit_price'], $item['quantity'], $item['product_name'], $item['option_id'], $item_quantity, (isset($item['transaction_type'])?$item['transaction_type']:''), (isset($item['transaction_id'])?$item['transaction_id']:''),(isset($item['status'])?$item['status']:''),$item['expiry']?$item['expiry']:NULL);
                    }
                } else {
                    $cost = array(
                        array(
                            'date' 						=> date('Y-m-d'),
                            'product_id' 				=> $item['product_id'],
                            'product_code' 				=> $item['product_code'],
                            'product_name' 				=> $item['product_name'],
                            'product_type' 				=> $item['product_type'],
                            'sale_item_id' 				=> 'sale_items.id',
                            'purchase_item_id' 			=> NULL,
                            'quantity' 					=> $item['quantity'],
                            'purchase_net_unit_cost' 	=> 0,
                            'purchase_unit_cost' 		=> 0,
                            'sale_net_unit_price' 		=> $item['net_unit_price'],
                            'sale_unit_price' 			=> $item['unit_price'],
                            'transaction_type' 			=> $item['transaction_type'],
                            'transaction_id' 			=> $item['transaction_id'],
                            'quantity_balance' 			=> NULL,
                            'inventory' 				=> NULL,
                            'transaction_type' 			=> $item['transaction_type'],
                            'transaction_type' 			=> $item['transaction_id'],
                            'status' 					=> $item['status']
                        )
                    );
                }
            } elseif ($item['product_type'] == 'manual') {
                $cost = array(
                    array(
                        'date' 						=> date('Y-m-d'),
                        'product_id' 				=> $item['product_id'],
                        'sale_item_id' 				=> 'sale_items.id',
                        'purchase_item_id' 			=> NULL,
                        'quantity' 					=> $item['quantity'],
                        'purchase_net_unit_cost' 	=> 0,
                        'purchase_unit_cost' 		=> 0,
                        'sale_net_unit_price' 		=> $item['net_unit_price'],
                        'sale_unit_price' 			=> $item['unit_price'],
                        'quantity_balance' 			=> NULL,
                        'inventory' 				=> NULL,
                        'transaction_type' 			=> $item['transaction_type'],
                        'transaction_type' 			=> $item['transaction_id'],
                        'status' 					=> $item['status']
                    )
                );
            }
        }

        return $cost;
    }

    public function item_costing_old($item, $pi = NULL)
	{

		$item_quantity = $pi ? $item['aquantity'] : $item['quantity'];
        if (!isset($item['option_id']) || $item['option_id'] == 'null') {
            $item['option_id'] = NULL;
        }
		//$this->erp->print_arrays($item);
        if ($this->Settings->accounting_method != 2 && !$this->Settings->overselling) {

			if ($this->site->getProductByID($item['product_id'])) {
                if ($item['product_type'] == 'standard') {
                    $cost = $this->site->calculateAVCost($item['product_id'], $item['warehouse_id'], $item['net_unit_price'], $item['unit_price'], $item['quantity'], $item['product_name'], $item['option_id'], $item_quantity, $item['transaction_type'], $item['transaction_id'], $item['status'], $item['expiry'], $item['old_sqty']);
                } elseif ($item['product_type'] == 'combo') {
                    $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                    foreach ($combo_items as $combo_item) {
                        $pr = $this->getProductByCode($combo_item->code);
                        if ($pr->tax_rate) {
                            $pr_tax = $this->site->getTaxRateByID($pr->tax_rate);
                            if ($pr->tax_method) {
                                $item_tax 		= $this->erp->formatDecimal((($combo_item->unit_price) * $pr_tax->rate) / (100 + $pr_tax->rate));
                                $net_unit_price = $combo_item->unit_price - $item_tax;
                                $unit_price 	= $combo_item->unit_price;
                            } else {
                                $item_tax 		= $this->erp->formatDecimal((($combo_item->unit_price) * $pr_tax->rate) / 100);
                                $net_unit_price = $combo_item->unit_price;
                                $unit_price 	= $combo_item->unit_price + $item_tax;
                            }
                        } else {
                            $net_unit_price 	= $combo_item->unit_price;
                            $unit_price 		= $combo_item->unit_price;
                        }
                        if ($pr->type == 'standard') {
                            $cost = $this->site->calculateAVCost($pr->id, $item['warehouse_id'], $net_unit_price, $unit_price, ($combo_item->qty * $item['quantity']), $pr->name, NULL, $item_quantity, $item['transaction_type'], $item['transaction_id'], $item['status'], $item['expiry'], $item['old_sqty']);
                        } else {
                            $cost = array(
								array(
									'date' 					 => date('Y-m-d'), 
									'product_id' 			 => $pr->id, 
									'product_code' 			 => $pr->code, 
									'product_name' 			 => $pr->name, 
									'product_name' 			 => $pr->type, 
									'sale_item_id' 			 => 'sale_items.id', 
									'purchase_item_id' 		 => NULL, 
									'quantity' 				 => ($combo_item->qty * $item['quantity']), 
									'purchase_net_unit_cost' => 0, 
									'purchase_unit_cost' 	 => 0, 
									'sale_net_unit_price' 	 => $combo_item->unit_price, 
									'sale_unit_price' 		 => $combo_item->unit_price, 
									'quantity_balance' 		 => NULL, 
									'inventory' 			 => NULL,
									'transaction_type' 		 => $item['transaction_type'], 
									'transaction_id' 		 => $item['transaction_id'],
									'status' 				 => $item['status']
								)
							);
                        }
                    }
                } else {
                    $cost = array(
						array(
							'date' 						=> date('Y-m-d'), 
							'product_id' 				=> $item['product_id'], 
							'product_code' 				=> $item['product_code'], 
							'product_name' 				=> $item['product_name'], 
							'product_type' 				=> $item['product_type'], 
							'sale_item_id' 				=> 'sale_items.id', 
							'purchase_item_id' 			=> NULL, 
							'quantity' 					=> $item['quantity'], 
							'purchase_net_unit_cost' 	=> 0, 
							'purchase_unit_cost' 		=> 0, 
							'sale_net_unit_price' 		=> $item['net_unit_price'], 
							'sale_unit_price' 			=> $item['unit_price'], 
							'quantity_balance' 			=> NULL, 
							'inventory' 				=> NULL,
							'transaction_type' 			=> $item['transaction_type'], 
							'transaction_id' 			=> $item['transaction_id'],
							'status' 					=> $item['status']
						)
					);
                }
            } elseif ($item['product_type'] == 'manual') {
                $cost = array(
					array(
						'date' 						=> date('Y-m-d'), 
						'product_id' 				=> $item['product_id'], 
						'sale_item_id' 				=> 'sale_items.id', 
						'purchase_item_id' 			=> NULL, 
						'quantity' 					=> $item['quantity'], 
						'purchase_net_unit_cost' 	=> 0, 
						'purchase_unit_cost' 		=> 0, 
						'sale_net_unit_price' 		=> $item['net_unit_price'], 
						'sale_unit_price' 			=> $item['unit_price'], 
						'quantity_balance' 			=> NULL, 
						'inventory' 				=> NULL,
						'transaction_type' 			=> $item['transaction_type'], 
						'transaction_id' 			=> $item['transaction_id'],
						'status' 					=> $item['status']
					)
				);
            }
        } else {

            if ($this->site->getProductByID($item['product_id'])) {
                if ($item['product_type'] == 'standard') {
                    $cost = $this->site->calculateAVCost($item['product_id'], $item['warehouse_id'], $item['net_unit_price'], $item['unit_price'], $item['quantity_balance'], $item['product_name'], $item['option_id'], $item_quantity, (isset($item['transaction_type']) ? $item['transaction_type'] : ''), (isset($item['transaction_id']) ? $item['transaction_id'] : ''), (isset($item['status']) ? $item['status'] : ''), $item['expiry'] ? $item['expiry'] : NULL, $item['old_sqty'], $item['date']);

                } elseif ($item['product_type'] == 'combo') {
                    $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                    foreach ($combo_items as $combo_item) {
                        $cost = $this->site->calculateAVCost($combo_item->id, $item['warehouse_id'], ($combo_item->qty * $item['quantity']), $item['unit_price'], $item['quantity_balance'], $item['product_name'], $item['option_id'], $item_quantity, (isset($item['transaction_type']) ? $item['transaction_type'] : ''), (isset($item['transaction_id']) ? $item['transaction_id'] : ''), (isset($item['status']) ? $item['status'] : ''), $item['expiry'] ? $item['expiry'] : NULL, $item['old_sqty'], $item['date']);
                    }
                } else {
                    $cost = array(
						array(
                            'date' => $item['date'],
							'product_id' 				=> $item['product_id'], 
							'product_code' 				=> $item['product_code'], 
							'product_name' 				=> $item['product_name'], 
							'product_type' 				=> $item['product_type'], 
							'sale_item_id' 				=> 'sale_items.id', 
							'purchase_item_id' 			=> NULL, 
							'quantity' 					=> $item['quantity'], 
							'purchase_net_unit_cost' 	=> 0, 
							'purchase_unit_cost' 		=> 0, 
							'sale_net_unit_price' 		=> $item['net_unit_price'], 
							'sale_unit_price' 			=> $item['unit_price'],
							'transaction_type' 			=> $item['transaction_type'],
							'transaction_id' 			=> $item['transaction_id'],
							'quantity_balance' 			=> NULL, 
							'inventory' 				=> NULL,
							'transaction_type' 			=> $item['transaction_type'], 
							'transaction_id' 			=> $item['transaction_id'],
							'status' 					=> $item['status']
						)
					);
                }
            } elseif ($item['product_type'] == 'manual') {
                $cost = array(
					array(
                        'date' => $item['date'],
						'product_id' 				=> $item['product_id'], 
						'sale_item_id' 				=> 'sale_items.id', 
						'purchase_item_id' 			=> NULL, 
						'quantity' 					=> $item['quantity'], 
						'purchase_net_unit_cost' 	=> 0, 
						'purchase_unit_cost' 		=> 0, 
						'sale_net_unit_price' 		=> $item['net_unit_price'], 
						'sale_unit_price' 			=> $item['unit_price'], 
						'quantity_balance' 			=> NULL, 
						'inventory' 				=> NULL,
						'transaction_type' 			=> $item['transaction_type'], 
						'transaction_id' 			=> $item['transaction_id'],
						'status' 					=> $item['status']
					)
				);
            }
		}
		
        return $cost;
    }

    public function costing($items)
	{
	    $citems = array();
        $cost = array();
        foreach ($items as $item) {
            $pr = $this->getProductByID($item['product_id']);
            if ($pr->type == 'standard') {
				
                if (isset($citems['p' . $item['product_id'] . 'o' . $item['option_id']. 'e' . $item['expiry']])) {
                    $citems['p' . $item['product_id'] . 'o' . $item['option_id']. 'e' . $item['expiry']]['aquantity'] += $item['quantity'];
					$citems['p' . $item['product_id'] . 'o' . $item['option_id']. 'e' . $item['expiry']]['quantity'] += $item['quantity'];
					$citems['p' . $item['product_id'] . 'o' . $item['option_id']. 'e' . $item['expiry']]['quantity_balance'] += $item['quantity_balance'];
					$citems['p' . $item['product_id'] . 'o' . $item['option_id']. 'e' . $item['expiry']]['old_sqty'] = $item['old_sqty'];
                } else {
                    $citems['p' . $item['product_id'] . 'o' . $item['option_id']. 'e' . $item['expiry']] = $item;
                    $citems['p' . $item['product_id'] . 'o' . $item['option_id']. 'e' . $item['expiry']]['aquantity'] = $item['quantity'];
					$citems['p' . $item['product_id'] . 'o' . $item['option_id']. 'e' . $item['expiry']]['old_sqty'] = $item['old_sqty'];
                }
				
            } elseif ($pr->type == 'combo') {
                $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                foreach ($combo_items as $combo_item) {
                    if ($combo_item->type == 'standard') {
                        if (isset($citems['p' . $combo_item->id . 'o' . $item['option_id']. 'e' . $item['expiry']])) {
                            $citems['p' . $combo_item->id . 'o' . $item['option_id']. 'e' . $item['expiry']]['aquantity'] += ($combo_item->qty*$item['quantity']);
                        } else {
                            $cpr = $this->getProductByID($combo_item->id);
                            if ($cpr->tax_rate) {
                                $cpr_tax = $this->site->getTaxRateByID($cpr->tax_rate);
                                if ($cpr->tax_method) {
                                    $item_tax = $this->erp->formatDecimal((($combo_item->unit_price) * $cpr_tax->rate) / (100 + $cpr_tax->rate));
                                    $net_unit_price = $combo_item->unit_price - $item_tax;
                                    $unit_price = $combo_item->unit_price;
                                } else {
                                    $item_tax = $this->erp->formatDecimal((($combo_item->unit_price) * $cpr_tax->rate) / 100);
                                    $net_unit_price = $combo_item->unit_price;
                                    $unit_price = $combo_item->unit_price + $item_tax;
                                }
                            } else {
                                $net_unit_price = $combo_item->unit_price;
                                $unit_price = $combo_item->unit_price;
                            }
                            $cproduct = array('product_id' => $combo_item->id, 'product_name' => $cpr->name, 'product_type' => $combo_item->type, 'quantity' => ($combo_item->qty*$item['quantity']), 'net_unit_price' => $net_unit_price, 'unit_price' => $unit_price, 'warehouse_id' => $item['warehouse_id'], 'item_tax' => $item_tax, 'tax_rate_id' => $cpr->tax_rate, 'tax' => ($cpr_tax->type == 1 ? $cpr_tax->rate.'%' : $cpr_tax->rate), 'option_id' => NULL);
                            $citems['p' . $combo_item->id . 'o' . $item['option_id']. 'e' . $item['expiry']] = $cproduct;
                            $citems['p' . $combo_item->id . 'o' . $item['option_id']. 'e' . $item['expiry']]['aquantity'] = ($combo_item->qty*$item['quantity']);
                        }
						$citems['p' . $combo_item->id . 'o' . $item['option_id']. 'e' . $item['expiry']]['transaction_type'] = $item['transaction_type'];
						$citems['p' . $combo_item->id . 'o' . $item['option_id']. 'e' . $item['expiry']]['transaction_id'] = $item['transaction_id'];
						$citems['p' . $combo_item->id . 'o' . $item['option_id']. 'e' . $item['expiry']]['status'] = $item['status'];
                    }
                }
            }
        }

        foreach ($citems as $item) {
            $item['aquantity'] = $citems['p' . $item['product_id'] . 'o' . $item['option_id']]['aquantity'];
            $cost[] = $this->item_costing($item, TRUE);

        }



        return $cost;
    }
	
	public function getUnitQuantity($option_id=null,$prod_id=null)
	{
		$q = $this->db->get_where("product_variants", array('id' => $option_id,'product_id'=>$prod_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function syncQuantitys($sale_id = NULL, $purchase_id = NULL, $oitems = NULL, $product_id = NULL) 
	{
        
		if ($sale_id) {
            $sale_items = $this->getAllSaleItems($sale_id);
            foreach ($sale_items as $item) {
                if ($item->product_type == 'standard') {
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    if (isset($item->option_id) && !empty($item->option_id)) {
                        $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                    }
                } elseif ($item->product_type == 'combo') {
                    $combo_items = $this->getProductComboItems($item->product_id, $item->warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        if($combo_item->type == 'standard') {
                            $this->syncProductQty($combo_item->id, $item->warehouse_id);
                        }
                    }
                }
            }
        } elseif ($purchase_id) {
            $purchase_items = $this->getAllPurchaseItems($purchase_id);
         
			$var_option = 0;
            foreach ($purchase_items as $item) {
				
				if($item->option_id != 0) {
					$var_option = $item->option_id;
				}
                $type = $this->getProductType($item->product_id);
                if($type != 'service'){
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    if (isset($item->option_id) && !empty($item->option_id)) {
                        $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                    }
                }
            }

        } elseif ($oitems) {
			
            foreach ($oitems as $item) {
				
                if (isset($item->product_type)) {
                    if ($item->product_type == 'standard') {
                        $this->syncProductQty($item->product_id, $item->warehouse_id);
                        if (isset($item->option_id) && !empty($item->option_id)) {
                            $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                        }
                    } elseif ($item->product_type == 'combo') {
                        $combo_items = $this->getProductComboItems($item->product_id, $item->warehouse_id);
                        foreach ($combo_items as $combo_item) {
                            if($combo_item->type == 'standard') {
                                $this->syncProductQty($combo_item->id, $item->warehouse_id);
                            }
                        }
                    }
                } else {
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    if (isset($item->option_id) && !empty($item->option_id)) {
                        $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                    }
                }
            }

        } elseif ($product_id) {
            $warehouses = $this->getAllWarehouses();
            foreach ($warehouses as $warehouse) {
                $type = $this->getProductType($product_id);
                if($type != 'service'){
                    $this->syncProductQty($product_id, $warehouse->id);
                    if ($product_variants = $this->getProductVariants($product_id)) {
                        foreach ($product_variants as $pv) {
                            
							$this->syncVariantQty($pv->id, $warehouse->id, $product_id);
							
                        }
                    }
					//$this->erp->print_arrays($product_variants);
                }else{
					if($this->getBalanceQuantity($product_id)){
						$this->db->update('products', array('quantity' => 1), array('id' => $product_id));
					}else{
						$this->db->update('products', array('quantity' => 1), array('id' => $product_id));
					}
				}
				//print_r($product_variants);
				
            }
        }
    }
	
	public function syncQuantity($sale_id = NULL, $purchase_id = NULL, $oitems = NULL, $product_id = NULL, $delivery_id=NULL, $sale_return_id = NULL, $oitemArray=NULL) {
        if ($sale_id) {
            $sale_items 	= $this->getAllSaleItems($sale_id);
            foreach ($sale_items as $item) {
                if ($item->product_type == 'standard') {
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    if (isset($item->option_id) && !empty($item->option_id)) {
                        $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                    }
                } elseif ($item->product_type == 'combo') {
                    $combo_items = $this->getProductComboItems($item->product_id, $item->warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        if($combo_item->type == 'standard') {
                            $this->syncProductQty($combo_item->id, $item->warehouse_id);
                        }
                    }
                }
            }
        } elseif ($purchase_id) {
            $purchase_items = $this->getAllPurchaseItems($purchase_id);
			$var_option = 0;
            foreach ($purchase_items as $item) {
				if($item->option_id != 0) {
					$var_option = $item->option_id;
				}
                $type = $this->getProductType($item->product_id);
                if($type != 'service'){
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    // if (isset($item->option_id) && !empty($item->option_id)) {
						if ($product_variants = $this->getProductVariants($item->product_id)) {
							foreach ($product_variants as $pv) {
								$this->syncVariantQty($pv->id, $item->warehouse_id, $item->product_id);
							}
						}
                    // }
                }
            }

        } elseif ($oitems) {

            foreach ($oitems as $item) {
                if (isset($item->product_type)) {
                    if ($item->product_type == 'standard') {
                        $this->syncProductQty($item->product_id, $item->warehouse_id);
                        if (isset($item->option_id) && !empty($item->option_id)) {
                            $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                        }
                    } elseif ($item->product_type == 'combo') {
                        $combo_items = $this->getProductComboItems($item->product_id, $item->warehouse_id);
                        foreach ($combo_items as $combo_item) {
                            if($combo_item->type == 'standard') {
                                $this->syncProductQty($combo_item->id, $item->warehouse_id);
                            }
                        }
                    }
                } else {
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    if (isset($item->option_id) && !empty($item->option_id)) {
                        $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                    }
                }
            }

        }elseif ($product_id) {
            $warehouses = $this->getAllWarehouses();
            foreach ($warehouses as $warehouse) {
                $pro = $this->getProductByID($product_id);
                if($pro->type != 'service'){
                    $this->syncProductQty($product_id, $warehouse->id);
                    if ($product_variants = $this->getProductVariants($product_id)) {
						$this->syncVariantsQty($warehouse->id, $product_id);
                        /* foreach ($product_variants as $pv) {
                            $this->syncVariantQty($pv->id, $warehouse->id, $product_id);
                        } */
                    }
                } else {
					if ($this->getBalanceQuantity($product_id)) {
//						echo 'hello'; exit();
						$ware_stock = $this->getWarehousehaveStock($product_id);
						$this->db->update('products', array('quantity' => 1), array('id' => $product_id));
						$warehouses = $this->site->getAllWarehouses();
						$stock = 0;
						if($ware_stock->warehouse_id == $warehouse->id){
							$stock = 1;
						}
						
						if ($ware_stock) {
							$this->db->update('warehouses_products', array('quantity' => $stock), array('product_id' => $product_id, 'warehouse_id' => $warehouse->id));
						} else {
							$this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse->id, 'quantity' => $stock));
						}
							
					} else {
						$this->db->update('products', array('quantity' => 1), array('id' => $product_id));
						/*$warehouses = $this->site->getAllWarehouses();
						foreach ($warehouses as $warehouse) {
							$this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse->id, 'quantity' => 0));
						}*/
					}
				}
            }
			
        } elseif ($delivery_id){
			$delivery_items = $this->getAllDeliveryItems($delivery_id);
			foreach ($delivery_items as $item) {
                if ($item->product_type == 'standard') {
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    if (isset($item->option_id) && !empty($item->option_id)) {
                        $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                    }
                } elseif ($item->product_type == 'combo') {
                    $combo_items = $this->getProductComboItems($item->product_id, $item->warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        if($combo_item->type == 'standard') {
                            $this->syncProductQty($combo_item->id, $item->warehouse_id);
                        }
                    }
                }
            }
		}elseif($sale_return_id){
			$sale_return = $this->getAllSaleReturnItems($sale_return_id);
            foreach ($sale_return as $item) {
                if ($item->product_type == 'standard') {
                    $this->syncProductQty($item->product_id, $item->warehouse_id);
                    if (isset($item->option_id) && !empty($item->option_id)) {
                        $this->syncVariantQty($item->option_id, $item->warehouse_id, $item->product_id);
                    }
                } elseif ($item->product_type == 'combo') {
                    $combo_items = $this->getProductComboItems($item->product_id, $item->warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        if($combo_item->type == 'standard') {
                            $this->syncProductQty($combo_item->id, $item->warehouse_id);
                        }
                    }
                }
            }
		}elseif($oitemArray){
			foreach ($oitemArray as $item) {
                if (isset($item['product_type'])) {
                    if ($item['product_type'] == 'standard') {
                        $this->syncProductQty($item['product_id'], $item['warehouse_id']);
                        if (isset($item['option_id']) && !empty($item['option_id'])) {
                            $this->syncVariantQty($item['option_id'], $item['warehouse_id'], $item['product_id']);
                        }
                    } elseif ($item['product_type'] == 'combo') {
                        $combo_items = $this->getProductComboItems($item['product_id'], $item['warehouse_id']);
                        foreach ($combo_items as $combo_item) {
                            if($combo_item->type == 'standard') {
                                $this->syncProductQty($combo_item->id, $item['warehouse_id']);
                            }
                        }
                    }
                } else {
                    $this->syncProductQty($item['product_id'], $item['warehouse_id']);
                    if (isset($item['option_id']) && !empty($item['option_id'])) {
                        $this->syncVariantQty($item['option_id'], $item['warehouse_id'], $item['product_id']);
                    }
                }
            }
		}
        getUserIdPermission();
    }

    public function getAllSaleReturnItems($id){
	    $q = $this->db->get_where('return_items', array('return_id' => $id));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }

	public function getWarehousehaveStock($id)
	{
		$this->db->select('SUM(quantity_balance) as qty, warehouse_id')
				 ->from('purchase_items')
				 ->where(array('product_id' => $id));
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function syncVariantsQty($warehouse_id, $product_id) {
        $balance_qty 	= $this->getBalanceQuantity($product_id);
        $wh_balance_qty = $this->getBalanceQuantity($product_id, $warehouse_id);		
        $variants 		= $this->getProductVariants($product_id);	
		foreach($variants as $variant){
			$pvar_qty 	= $balance_qty/$variant->qty_unit;
			$wvar_qty 	= $wh_balance_qty/$variant->qty_unit;
			if($pvar_qty){
				if ($this->db->update('product_variants', array('quantity' => $pvar_qty), array('id' => $variant->id))) {
					if ($this->getWarehouseProductsVariants($variant->id, $warehouse_id)) {
						$this->db->update('warehouses_products_variants', array('quantity' => ($wvar_qty)), array('option_id' => $variant->id, 'warehouse_id' => $warehouse_id));
					} else {
						if($wvar_qty) {
							$this->db->insert('warehouses_products_variants', array('quantity' => ($wvar_qty), 'option_id' => $variant->id, 'warehouse_id' => $warehouse_id, 'product_id' => $product_id));
						}
					}
				}
			}
			$balance_qty 	= $balance_qty%$variant->qty_unit;
			$wh_balance_qty = $wh_balance_qty%$variant->qty_unit;
		}
		return FALSE;
    }
	
    public function getProductVariants($product_id)
    {
		$this->db->order_by('qty_unit', 'DESC');
        $q = $this->db->get_where('product_variants', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getVariantsById($id)
	{
		$q = $this->db->get_where('variants', array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getProductVariantOptionIDPID($option_id, $product_id)
    {
        $q = $this->db->get_where('product_variants', array('id' => $option_id, 'product_id' => $product_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllSaleItems($sale_id) {
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllPurchaseItems($purchase_id) {
        $q = $this->db->get_where('purchase_items', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function deleteStrapByProductCode($code = NULL) 
	{
        if ( $this->db->delete('related_products', array('product_code' => $code))) {
            return true;
        }
        return false;
	}

	public function getSaleItemBySaleID($saleId=null){
		$response = $this->db->select('*')->where('sale_id', $saleId)->from('sale_items')->get()
                 ->result();
		return $response;	
	}
	
    public function syncPurchaseItems($data = array()) 
	{
        if (!empty($data)) {
            foreach ($data as $items) {
                foreach ($items as $item) { 
					
					$product = $this->getProductByID($item['product_id']);
                    if($product->type != 'service'){						
						$qty = 1;
						if (isset($item['pi_overselling'])) {
							
							unset($item['pi_overselling']);
							$option_id = (isset($item['option_id']) && !empty($item['option_id'])) ? $item['option_id'] : NULL;
							$clause = array(
								'purchase_id' 		=> NULL, 
								'transfer_id' 		=> NULL, 
								'transaction_type' 	=> $item['transaction_type'],
								'transaction_id' 	=> $item['transaction_id'],
								'status' 			=> $item['status'], 
								'product_id' 		=> $item['product_id'], 
								'warehouse_id' 		=> $item['warehouse_id'], 
								'option_id' 		=> $option_id,
								'expiry'			=> $item['expiry']
							);

							if ($pi = $this->getPurchasedItem($clause)) {
								
								$clause['quantity'] = 0;
								$clause['item_tax'] = 0;
								$clause['date']     = 0;
								if($option_id){
									$option = $this->getProductVariantOptionIDPID($option_id, $item['product_id']);
									if($option->qty_unit > 0){
										$qty = $option->qty_unit;
									}
									$clause['quantity_balance'] = $item['quantity_balance'] * $qty;
								}else{
									$clause['quantity_balance'] = $item['quantity_balance'];
								}
								$clause['product_code'] 		= $product->code;
								$clause['product_name'] 		= $product->name;
								$clause['product_type'] 		= $product->type;
								$clause['date'] 				= $item['date']?$item['date']:date('Y-m-d');
								$clause['status']               = 'received';
								$this->db->insert('purchase_items', $clause);

							} else {
								
								$clause['quantity'] = 0;
								$clause['item_tax'] = 0;
								if($option_id){
									$option = $this->getProductVariantOptionIDPID($option_id, $item['product_id']);
									if($option->qty_unit > 0){
										$qty = $option->qty_unit;
									}
									$clause['quantity_balance'] = $item['quantity_balance'] * $qty;
								}else{
									$clause['quantity_balance'] = $item['quantity_balance'];
								}
								$clause['product_code'] 		= $product->code;
								$clause['product_name'] 		= $product->name;
								$clause['product_type'] 		= $product->type;
								$clause['date'] = $item['date']?$item['date']:date('Y-m-d');
								$clause['status']               = 'received';
								$this->db->insert('purchase_items', $clause);
							}
						} else {
							/* Add New */
							if ($item['inventory']) {
								$pr_item = $this->getPurchaseItemByID($item['purchase_item_id']);
								if($pr_item){
									$qty_balance 			= -1 * abs($item['quantity']);
									$option_id 				= (isset($item['option_id']) && !empty($item['option_id'])) ? $item['option_id'] : NULL;
									if($option_id){
										$option 			= $this->getProductVariantOptionIDPID($option_id, $item['product_id']);
										if($option->qty_unit > 0){
											$qty = $option->qty_unit;
										}
										$qty_balance  		= $qty_balance * $qty;
									}
									$new_arr_data = array(
										'product_id' 		=> $item['product_id'],
										'product_code' 		=> $product->code,
										'product_name' 		=> $product->name,
										'net_unit_cost' 	=> $pr_item->net_unit_cost?$pr_item->net_unit_cost:$product->cost,
										'quantity' 			=> 0,
										'item_tax' 			=> 0,
										'warehouse_id' 		=> $pr_item->warehouse_id?$pr_item->warehouse_id:'',
										'subtotal' 			=> $pr_item->subtotal?$pr_item->subtotal:0,
										'date' 				=> date('Y-m-d'),
										'status' 			=> $pr_item->status?$pr_item->status:'',
										'quantity_balance' 	=> $qty_balance,
										'option_id' 		=> $option_id,
										'transaction_type' 	=> $item['transaction_type'],
										'transaction_id' 	=> $item['transaction_id']
									);
									//echo '<pre>';print_r($new_arr_data);echo '</pre>';
									$this->db->insert('purchase_items', $new_arr_data);
								}
							}
							/* Checking...
							if ($item['inventory']) {
								$this->db->update('purchase_items', array('quantity_balance' => $item['quantity_balance']), array('id' => $item['purchase_item_id']));
							}
                			*/
						}
                    }
                    $this->site->syncQuantity(NULL, NULL, NULL, $item['product_id']);
					$this->db->update("inventory_valuation_details",array('cost'=>$product->cost,'avg_cost'=>$product->cost),array('field_id'=>$item['transaction_id']));
                }
            }
            return TRUE;
        }
        return FALSE;
    }
	
	public function syncPurchaseItems_delivery($data = array(), $delivery_id = NULL)
	{

		if (!empty($data)) {
            foreach ($data as $items) {
                foreach ($items as $item) {

					$product = $this->getProductByID($item['product_id']);
                    if($product->type != 'service'){
						$qty = 0;
						if (isset($item['pi_overselling'])) {
							unset($item['pi_overselling']);
							$option_id = (isset($item['option_id']) && !empty($item['option_id'])) ? $item['option_id'] : NULL;
							$clause = array(
								'purchase_id' 		=> NULL, 
								'transfer_id' 		=> NULL,
								'delivery_id'		=> $delivery_id,
								'transaction_type' 	=> $item['transaction_type'],
								'transaction_id' 	=> $item['transaction_id'],
								'status' 			=> $item['status'], 
								'product_id' 		=> $item['product_id'], 
								'product_code' 		=> $item['product_code'], 
								'product_type' 		=> $item['product_type'], 
								'product_name' 		=> $item['product_name'], 
								'warehouse_id' 		=> $item['warehouse_id'], 
								'option_id' 		=> $option_id,
								'expiry'			=> $item['expiry']?$item['expiry']:NULL
							);
                            $clause['quantity'] = 0;
                            $clause['item_tax'] = 0;
                            $clause['quantity_balance'] 	= $item['quantity_balance'];
                            $clause['date'] 				= $item['date']?$item['date']:date('Y-m-d');
                            $clause['status']               = 'received';
                            $this->db->insert('purchase_items', $clause);

						} else {
							
							/* Add New */
							if ($item['inventory']) {
								$pr_item = $this->getPurchaseItemByID($item['purchase_item_id']);
								if($pr_item){
									$qty_balance 			= -1 * abs($item['quantity']);
									$option_id 				= (isset($item['option_id']) && !empty($item['option_id'])) ? $item['option_id'] : NULL;
									if($option_id){
										$option 			= $this->getProductVariantOptionIDPID($option_id, $item['product_id']);
										if($option->qty_unit > 0){
											$qty = $option->qty_unit;
										}else{
											$qty = 1;
										}
										$qty_balance  		= $qty_balance * $qty;
									}
									$new_arr_data = array(
										'product_id' 		=> $item['product_id'],
										'delivery_id'		=> $delivery_id,
										'product_code' 		=> $product->code,
										'product_name' 		=> $product->name,
										'net_unit_cost' 	=> $pr_item->net_unit_cost?$pr_item->net_unit_cost:$product->cost,
										'quantity' 			=> 0,
										'item_tax' 			=> 0,
										'warehouse_id' 		=> $pr_item->warehouse_id?$pr_item->warehouse_id:'',
										'subtotal' 			=> $pr_item->subtotal?$pr_item->subtotal:0,
										'date' 				=> date('Y-m-d'),
										'status' 			=> $pr_item->status?$pr_item->status:'',
										'quantity_balance' 	=> $qty_balance,
										'option_id' 		=> $option_id,
										'transaction_type' 	=> $item['transaction_type'],
										'transaction_id' 	=> $item['transaction_id']
									);
									$this->db->insert('purchase_items', $new_arr_data);
									
									
								}
							}
							/* Checking...
							if ($item['inventory']) {
								$this->db->update('purchase_items', array('quantity_balance' => $item['quantity_balance']), array('id' => $item['purchase_item_id']));
							}
                			*/
						}
                    }
                    $this->site->syncQuantity(NULL, NULL, NULL, $item['product_id']);
					$this->db->update("inventory_valuation_details",array('cost'=>$product->cost,'avg_cost'=>$product->cost),array('field_id'=>$item['transaction_id']));
                }
            }
            return TRUE;
        }
        return FALSE;
    }
	
	public function syncQuantities($data)
	{
		if (!empty($data)) {
            foreach ($data as $items) {
                $product = $this->getProductByID($item['product_id']);
				if($product->type != 'service'){
					if($product->type == 'combo'){
						
					}else{
						
					}
				}
            }
            return TRUE;
        }
        return FALSE;
	}

	public function getPurchaseItemByID($purchase_item_id)
	{
		$q = $this->db->get_where('purchase_items', array('id' => $purchase_item_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getMakeupCostByCompanyID($customer_id)
	{
		$this->db->select('percent, makeup_cost')
						->join('customer_groups', 'customer_groups.id = companies.customer_group_id')
						->where('companies.id', $customer_id);
		$q = $this->db->get('companies');
		if($q->num_rows() > 0){
			return $q->row();
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
	
	/*==============================local chin add===============================================*/
	public function getInvoicePaymentBySaleID($sale_id)
    {
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id, 'add_payment <> ' => '1'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	/*=============================end local add================================================*/
	
	public function getPaymentBySaleID($sale_id)
    {
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getPaymentByPurchaseID($purchase_id)
    {
        $q = $this->db->get_where('payments', array('purchase_id' => $purchase_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getAllBom($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $q = $this->db->get('bom');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getBom_itemsTop($id)
    {
        $this->db->select('*');
        $this->db->where(array('bom_id'=> $id, 'status'=> 'deduct'));
        $q = $this->db->get('bom_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

	public function getBom_itemsBottom($id)
    {
        $this->db->select('*');
        $this->db->where(array('bom_id'=> $id, 'status'=> 'add'));
        $q = $this->db->get('bom_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function default_biller_id() 
	{
        $this->db->select('default_biller');
        $q = $this->db->get('settings');
        if($q->num_rows() > 0){
            $q = $q->row();
            return $q->default_biller;
        }
        return false;
    }
	
	public function suspend_room(){
		$q = $this->db->get_where('suspended');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function month($month, $id){
		$start = '';
		$end   = '';
		if($month == 01){
			$date = date('Y');
			$dates = $date - 1;
			$years = $dates.'-'.$month.'-23';	
			$y = new DateTime( $years ); 
			$end  = $y->format( 'Y-m-t' );	
			$start = $dates.'-'.$month.'-01';	
		}elseif($month == '0-1'){
			$date = date('Y');
			$years = $date.'-01-23';	
			$y = new DateTime( $years ); 
			$end  = $y->format( 'Y-m-t' );	
			$start = $date.'-01-01';	
		}else{
			$date = date('Y');
			$years = $date.'-'.$month.'-23';	
			$y = new DateTime( $years ); 
			$end  = $y->format( 'Y-m-t' );	
			$start = $date.'-'.$month.'-01';	
		}
		
		$this->db->select('date')
					  ->from('purchase_items')
					  ->where('date >= "'.$start.'" and date <= "'.$end.'" and product_code = '.$id.' ')
					  ->order_by('date', 'desc')
					  ->limit(1);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
           $result = $q->row();
		   return $result->date;
        }
        return FALSE;	
	}
	
	public function months($year,$month)
	{
		$start = '';
		$end   = '';
		if($month == 01){
			$dates = $year - 1;
			$years = $dates.'-12-23';	
			$y = new DateTime( $years ); 
			$end  = $y->format( 'Y-m-t' );	
			$start = $dates.'-12-01';	
		}else{
			$months = $month - 1;
			$years = $year.'-'.$months.'-23';	
			$y = new DateTime( $years ); 
			$end  = $y->format( 'Y-m-t' );	
			$start = $date.'-'.$months.'-01';	
		}
		
		$this->db->select('date')
					  ->from('purchase_items')
					  ->where('date >= "'.$start.'" and date <= "'.$end.'" ')
					  ->order_by('date', 'desc')
					  ->limit(1);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;	
	}
	
	public function getCurrency()
	{
		$this->db->select()
				 ->from('currencies')
				 ->order_by('id', 'ASC');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	/* New Function */
	public function getAllBaseUnits()
    {
        $q = $this->db->get_where("units", array('base_unit' => NULL));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getUnitsByBUID($base_unit)
    {
        $this->db->where('id', $base_unit)->or_where('base_unit', $base_unit);
        $q = $this->db->get("units");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getUnitByID($id)
    {
        $q = $this->db->get_where("units", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPriceGroupByID($id)
    {
        $q = $this->db->get_where('price_groups', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductGroupPrice($product_id, $group_id)
    {
        $q = $this->db->get_where('product_prices', array('price_group_id' => $group_id, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllBrands()
    {
        $q = $this->db->get("brands");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getBrandByID($id)
    {
        $q = $this->db->get_where('brands', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getAllProducts()
	{
		$q = $this->db->get("products");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getAllProductsInPurchaseItems($id) 
	{
        $this->db->select('purchase_items.*');        
        $q = $this->db->get_where('purchase_items', array('purchase_items.product_id' => $id, 'transaction_type !=', 'OPENING QUANTITY'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getUserSetting($id){
		$q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getAllBiller() {
        $this->db->where('group_name','biller');
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllEmployee() {
        $this->db->select($this->db->dbprefix('users').".id as id, " . $this->db->dbprefix('users') . ".emp_code, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ' ," . $this->db->dbprefix('users') . ".last_name) AS fullname, " . $this->db->dbprefix('users') . ".gender, nationality, position, employeed_date, phone, company, active");
        $this->db->from("users");
        $this->db->join('groups', 'users.group_id=groups.id', 'left');
        $this->db->group_by('users.id');
        $this->db->where('company_id', NULL);
		$this->db->order_by('id', 'DESC');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getUnits()
    {
         $q = $this->db->get("units");
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
	public function getGLChart(){
		$this->db->select()
				 ->from('gl_charts');
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}
	
	public function getTwoCurrencies(){
		$this->db->where('in_out', '1');
		/* $this->db->or_where('code', 'USD'); */
		$q = $this->db->get('currencies');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getPriceGroups()
    {
        $q = $this->db->get('price_groups');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getDrivers()
	{
		$this->db->select('id,name');
		$this->db->where(array('group_id' => '5', 'group_name' => 'driver'));
		$q = $this->db->get('companies');
		if($q->num_rows() > 0) {
			foreach($q->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}

	public function getDriversForDelivery()
	{
		$this->db->select('id,name');
		$this->db->where('group_id', '5');
		$this->db->where('group_name', 'driver');
		
		$q = $this->db->get('companies');
		if ($q->num_rows() > 0) {
            return $q->result();
        }
		return FALSE;
	}

	public function getAllPaymentTerm() 
	{
        $q = $this->db->get('payment_term');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllPaymentTermByID($pt_id) 
	{
        $q = $this->db->get_where('payment_term', array('id' => $pt_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function syncDeposits($company_id)
	{
		$da = 0;
		$total_amount = 0;
		if(is_array($company_id)){
			foreach($company_id as $id){
				$this->db->select('SUM(amount) as deposit')
						 ->from('deposits')
						 ->where(array('company_id'=>$id));	 
				$q = $this->db->get();
				if($q->num_rows() > 0){
					$da = $q->row()->deposit;
				}
				$this->db->select('deposit_amount as real_deposit_amount, deposit_amount as pur_deposit_amount')
						 ->from('erp_companies')
						 ->where(array('id'=>$id));	 
				$q2 = $this->db->get();
				if($q2->num_rows() > 0){
					$d_amount = $q2->row();
				}
				$total_amount = $da - $d_amount->pur_deposit_amount;
				$this->db->update('companies', array('deposit_amount'=>($d_amount->real_deposit_amount+$total_amount),'deposit_amount'=>$da), array('id' => $id));
			}
		}else{
			$this->db->select('SUM(amount) as deposit')
					 ->from('deposits')
					 ->where(array('company_id'=>$company_id));
			$q = $this->db->get();
			if($q->num_rows() > 0){
				$da = $q->row()->deposit;
			}
			$this->db->select('deposit_amount as real_deposit_amount, deposit_amount as pur_deposit_amount')
						 ->from('erp_companies')
						 ->where('id',$company_id);	 
			$q2 = $this->db->get();
			if($q2->num_rows() > 0){
				$d_amount = $q2->row();
			}
			$total_amount = $da - $d_amount->pur_deposit_amount;
			$this->db->update('companies', array('deposit_amount'=>($d_amount->real_deposit_amount+$total_amount),'deposit_amount'=>$da), array('id' => $company_id));
		}
		
		return false;
	}
	
	/* Bank Accounts */
	public function getAllBankAccounts() 
	{
        $q = $this->db->get_where('gl_charts', array('bank' => 1));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAssetsBankAccounts()
    {
        $this->db->select('*');
        $this->db->from('erp_gl_charts');
        $this->db->like('accountcode','100','after');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


    // Bank Accounts For User
    public function getAllBankAccountsByUserID() 
	{
        $this->db
        	 ->select('gl_charts.accountcode, gl_charts.accountname')
        	 ->from('gl_charts')
        	 ->join('erp_users_bank_account', 'gl_charts.accountcode = erp_users_bank_account.bankaccount_code', 'left')
        	 ->where('erp_users_bank_account.user_id', $this->session->userdata('user_id'));
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllBankAccountsByUserID2() 
	{
        $this->db
        	 ->select('gl_charts.accountcode, gl_charts.accountname')
        	 ->from('gl_charts')
        	 ->join('erp_users_bank_account', 'gl_charts.accountcode = erp_users_bank_account.bankaccount_code', 'left')
        	 ->where('erp_users_bank_account.id', $this->session->userdata('user_id'));
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function checkReference($ref) 
	{
        $q = $this->db->get_where('purchases', array('reference_no' => $ref));
        if ($q->num_rows() > 0) {
            return true;
        }
        return FALSE;
    }
	
	public function getUnitUOM($product_id=NULL)
	{
		$this->db->select("product_variants.*,products.cost as pcost");
		$this->db->from("product_variants");
		$this->db->join("products","products.id=product_variants.product_id","left");
        $this->db->where('product_id', $product_id);
		$this->db->order_by('qty_unit', 'DESC');
        $q = $this->db->get();
		
		if ($q->num_rows() > 0) {
		foreach (($q->result()) as $row) {
			$data[] = $row;
		}
			return $data;
        }
		

        return FALSE;
	}
	
	public function getUnitNameByProId($product_id = NULL)
	{
		$this->db->select("units.name as unit_name");
		$this->db->from("units");
		$this->db->join("products","products.unit = units.id","left");
        $this->db->where('products.id', $product_id);
        $q = $this->db->get();
		
		if ($q->num_rows() > 0) {
			return $q->row()->unit_name;
        }
		
		return FALSE;
	}
	
	public function getWarehouseProduct($product_id, $warehouse_id) 
	{
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getUserWarehouseProduct($product_id)
    {
        $warehouse_id = $this->session->userdata('warehouse_id');
        $warehouses_id = explode(',', $warehouse_id);

        $this->db->select('erp_warehouses_products.product_id as id, SUM(erp_warehouses_products.quantity) as qty');

        if (count($warehouses_id) > 1) {
            $this->db->where_in('erp_warehouses_products.warehouse_id', $warehouses_id);
        } else {
            $this->db->where('erp_warehouses_products.warehouse_id', $warehouse_id);
        }
        $q = $this->db->get_where('warehouses_products', array('warehouses_products.product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getCustomerInvoices($customer = NULL)
	{
		
		if($customer){
			$this->db->select("id as id, reference_no as text");
			$q = $this->db->get_where("sales", array('customer_id' => $customer));
		}else{
			$this->db->select("id as id, reference_no as text");
			$q = $this->db->get("sales");
		}
		
		return $q->result();

        return FALSE;
	}
	
	public function getProductOptions($pid)
    {
		$this->db->order_by('qty_unit', 'desc');
        $q = $this->db->get_where('product_variants', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
	public function getBillPayableByID($id)
	{
    	$this->db
				->select($this->db->dbprefix('payments') . ".id as pid, 
				" . $this->db->dbprefix('payments') . ".date,
				" . $this->db->dbprefix('purchases') . ".date as purchase_date,
				" . $this->db->dbprefix('purchases') . ".reference_no as purchases_ref,
				" . $this->db->dbprefix('purchases') . ".supplier,
				" . $this->db->dbprefix('payments') . ".paid_by,
				" . $this->db->dbprefix('payments') . ".note,
				" . $this->db->dbprefix('payments') . ".amount, 
				'paid' as payment_status")
			->from('purchases')
			->JOIN('payments','purchases.id=payments.purchase_id','left')
			->where('payments.id', $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getProductVariant($option_id = NULL, $product_id = NULL) 
	{
		if($option_id && $product_id) {
			$q = $this->db->get_where('product_variants', array('id' => $option_id, 'product_id' => $product_id));
			if ($q->num_rows() > 0) {
				return $q->row();
			}
			return FALSE;
		}
		return FALSE;
	}
	
	public function getProductVariantByName($option_name = NULL, $product_id = NULL) 
	{
		if($option_name && $product_id) {
			$q = $this->db->get_where('product_variants', array('name' => $option_name, 'product_id' => $product_id));
			if ($q->num_rows() > 0) {
				return $q->row();
			}
			return FALSE;
		}
		return FALSE;
	}
	
	public function getProVariant($variant = NULL, $product_id = NULL)
	{
		if($variant && $product_id) {
			$q = $this->db->get_where('product_variants', array('name' => $variant, 'product_id' => $product_id));
			if ($q->num_rows() > 0) {
				return $q->row();
			}
			return FALSE;
		}
		return FALSE;
	}

	public function getARaging($id=null,$ware=null,$created=null,$biller=null,$Sdate=null,$Edate=null,$wh=null)
	{
    	// echo $Sdate;exit();
    	// $this->erp->print_arrays($wh);
    	// echo $id;exit();

    	$this->db->select("customer_id,companies.name as customer,SUM(IFNULL(grand_total, 0)) AS grand_total,	SUM(IFNULL(paid, 0)) AS paid,SUM(IFNULL(grand_total - paid, 0)) AS balance,	COUNT(erp_sales.id) AS ar_number")
			->from('sales')
			->join('companies','sales.customer_id = companies.id', 'left')
			->where('payment_status !=', 'Returned')
			->where('payment_status !=', 'paid')
			->where('customer_id =',$id)		
			->where('DATE_SUB('. $this->db->dbprefix('sales')  .'.date, INTERVAL 1 DAY) <= CURDATE()')
			->where('(grand_total-paid) <> ', 0);
			if($ware){
				$this->db->where('erp_sales.warehouse_id',$ware);
			}
			if($created){
				$this->db->where('erp_sales.created_by',$created);
			}
			if($biller){
				$this->db->where('erp_sales.biller_id',$biller);
			}
			if ($Sdate) {
				$this->db->where($this->db->dbprefix('sales').'.date BETWEEN "' . $Sdate . '" and "' . $Edate . '"');
			}
			if($wh){
				$this->db->where_in('sales.warehouse_id',$wh);
			}
			$q = $this->db->get();
	        if ($q->num_rows() > 0) {
	            return $q->row();
	        }
	        return FALSE;
    }

    public function getARaging2($id)
	{
    	// echo $id;exit();
    	$this->db->select("companies.id, customer, 
			SUM(
				IFNULL(grand_total, 0)
			) as grand_total, 
			SUM(
				IFNULL(paid, 0)
			) as paid, 
			SUM(
				IFNULL(grand_total-paid, 0)
			) as balance,
			COUNT(
				erp_sales.id
			) as ar_number
			")
		->from('sales')
		->join ('companies', 'sales.customer_id = companies.id', 'left')
		->where('payment_status !=', 'Returned')
		->where('payment_status !=', 'paid')
		->where('DATE(erp_sales.date) BETWEEN curdate() - INTERVAL 30 DAY AND curdate() - INTERVAL 0 DAY')
		->where('(grand_total-paid) <> ', 0)
		->where('companies.id =',$id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getBillerPrefix($id)
	{
        $id = JSON_decode($id);
		$this->db->select('*');
		$this->db->from("erp_companies");
		$this->db->where_in('id',$id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
	}
	
	public function getPamentTermbyID($id)
    {
        $q = $this->db->get_where('payment_term', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
	public function getSaller($id)
	{
    	$this->db->select("erp_sales.*,CONCAT(erp_users.first_name,' ',erp_users.last_name) AS saller,CONCAT(erp_users.first_name_kh,' ',erp_users.last_name_kh) AS saller_kh,erp_users.gender,erp_users.date_of_birth,erp_users.nationality,erp_users.nationality_kh,erp_users.identify,erp_companies.company,erp_companies.company_kh,erp_companies.address,erp_companies.street,erp_companies.village,erp_companies.sangkat,erp_companies.district,erp_companies.state,erp_companies.city,erp_companies.country")
		->from('erp_sales')
		->join ('erp_users', 'erp_sales.saleman_by = erp_users.id', 'left')
		->join ('erp_companies', 'erp_sales.biller_id = erp_companies.id', 'left')
		->where('erp_sales.id', $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
	public function getTheCustomers($id)
	{
    	$this->db->select("erp_companies.*")
		->from('erp_sales')
		->join ('erp_companies', 'erp_sales.customer_id = erp_companies.id', 'left')
		->join ('erp_users', 'erp_sales.saleman_by = erp_users.id', 'left')
		->where('erp_sales.id', $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getAmount($id)
	{
    	$this->db->select("SUM(erp_loans.principle) AS amount")
		->from('erp_loans')
		->join ('erp_sales', 'erp_loans.sale_id = erp_sales.id', 'left')
		->where('erp_sales.id', $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
	public function getDuration($id)
	{
    	$this->db->select("(erp_sales.term/erp_sales.frequency) AS duration,terms.description,loans.payment")
		->from('erp_sales')
		->join ('erp_terms', 'erp_sales.term = terms.day', 'left')
		->join ('erp_loans', 'erp_sales.id = loans.sale_id', 'left')
		->where('erp_sales.id', $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getComboId($code)
	{
		$q = $this->db->get_where('combo_items', array('item_code' => $code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getBillerByID($id)
	{
		$this->db->where_in('id', $id);
		$q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getComboCost($id)
	{
		$this->db->select("SUM(erp_products.cost * erp_combo_items.quantity) AS p_cost")
				 ->from('combo_items')
				 ->join('products', 'products.code = combo_items.item_code', 'left')
				 ->where('product_id', $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function updateComboCost($code)
	{
		$combo_id = $this->getComboId($code);
		if($combo_id){
            foreach($combo_id as $combo){
                $comcost = $this->getComboCost($combo->product_id);
                $this->db->update('products', array('cost' => $comcost->p_cost), array('id' => $combo->product_id));
            }
        }

	}
	
	public function getUserWarehouses()
    {
		$query = $this->db->query('
			SELECT
				*
			FROM
				erp_warehouses
			WHERE
				id IN ('.$this->session->userdata('warehouse_id').')
			GROUP BY id
		');
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
    }
	
	public function getSaleOrderByID($id) {
        $q = $this->db->get_where('sale_order', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function checkExpiryDate($product_id, $expiry, $warehouse_id)
	{
		$this->db->select('sum(quantity_balance) as expiry_qty')
				 ->from('purchase_items')
				 ->where(array('product_id' => $product_id, 'expiry' => $expiry, 'warehouse_id' => $warehouse_id));
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getProductExpireDate($product_id, $warehouse_id){
		
		$condition = array('product_id'=>$product_id,'warehouse_id'=>$warehouse_id, 'expiry !=' => NULL );
		$this->db->select('
			erp_purchase_items.id, 
			erp_purchase_items.product_id, 
			erp_purchase_items.expiry,
			erp_purchase_items.warehouse_id,
			SUM(quantity_balance) as quantity_balance'
		);
		$this->db->from('erp_purchase_items');
		$this->db->where($condition);
		
		$this->db->group_by('erp_purchase_items.expiry');
		$this->db->having('quantity_balance > ', 0);
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

	public function getMakeupCost($id){
        $this->db->select('makeup_cost')
            ->from('customer_groups')
            ->where(array('id' => $id));
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach($q->result() as $row){
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function syncSalePurchaseItems($purchase_item)
    {
        if ($purchase_item['product_type'] == 'combo') {

            $combo = $this->getProductComboItems($purchase_item['product_id']);

            foreach ($combo as $combo_item) {
				$p_var = $this->getProductSmallVariant($combo_item->id);
                $pur_items = array(
                    'product_id' 		=> $combo_item->id,
                    'product_code' 		=> $combo_item->code,
                    'product_name' 		=> $combo_item->name,
                    'product_type' 		=> $combo_item->type,
                    'option_id' 		=> $p_var ? $p_var->id : '',
                    'quantity' 			=> $purchase_item['quantity'] * $combo_item->qty,
                    'quantity_balance' 	=> $purchase_item['quantity_balance'] * $combo_item->qty,
                    'warehouse_id' 		=> $purchase_item['warehouse_id'],
                    'expiry' 			=> $purchase_item['expiry'],
                    'date' 				=> date('Y-m-d', strtotime($purchase_item['date'])),
                    'status' 			=> "received",
                    'transaction_type' 	=> 'SALE',
                    'transaction_id' 	=> $purchase_item['transaction_id'],
                    'sale_id'           => $purchase_item['sale_id']
                );
                $this->db->insert('purchase_items', $pur_items);
            }
        } else {
            $this->db->insert('purchase_items', $purchase_item);
        }
        return FALSE;
    }
	
}
