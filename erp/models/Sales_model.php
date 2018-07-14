<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProductNames($term, $warehouse_id, $standard, $combo, $digital, $service, $user_category, $category_id, $limit = 20)
    {
        $this->db->select('products.id, start_date, end_date, erp_products.code, erp_products.name, erp_products.type, cost, warehouses_products.product_id, warehouses_products.quantity AS qoh, warehouses_products.quantity, price, tax_rate, tax_method, erp_products.image, promotion, promo_price, product_details, details, categories.type AS cate_type, subcategory_id, cf1, COALESCE((SELECT GROUP_CONCAT(sp.`serial_number`) FROM erp_serial as sp WHERE sp.product_id='.$this->db->dbprefix('products').'.id), "") as sep')
                 ->join('categories', 'categories.id=products.category_id', 'left')
				 ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
				 ->group_by('products.id');
        if ($this->Settings->overselling) {
            $this->db->where("(erp_products.name LIKE '%" . $term . "%' OR erp_products.code LIKE '%" . $term . "%' OR  concat(erp_products.name, ' (', erp_products.code, ')') LIKE '%" . $term . "%') AND inactived <> 1");
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
                if ($category_id != "") {
                    $this->db->where("products.category_id", $category_id);
                }
                if ($user_category != "") {
                    $this->db->where("products.category_id NOT IN (" . $user_category . ") ");
				}
				if($warehouse_id != ""){
					$this->db->where("warehouses_products.warehouse_id",$warehouse_id);
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
                if ($category_id != "") {
                    $this->db->where("products.category_id", $category_id);
                }
				if($service != ""){
					$this->db->where("products.type <> 'service' ");
				}
                if ($user_category != "") {
                    $this->db->where("products.category_id NOT IN (" . $user_category . ") ");
				}
				if($warehouse_id != ""){
					$this->db->where("warehouses_products.warehouse_id",$warehouse_id);
				}
			}
        } else {
            $this->db->where("(products.track_quantity = 0 OR warehouses_products.quantity > 0) AND warehouses_products.warehouse_id = '" . $warehouse_id . "' AND "
                . "(erp_products.name LIKE '%" . $term . "%' OR erp_products.code LIKE '%" . $term . "%' OR  concat(erp_products.name, ' (', erp_products.code, ')') LIKE '%" . $term . "%') AND inactived <> 1");
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
                if ($category_id != "") {
                    $this->db->where("products.category_id", $category_id);
                }
                if ($user_category != "") {
                    $this->db->where("products.category_id NOT IN (" . $user_category . ") ");
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
                if ($category_id != "") {
                    $this->db->where("products.category_id", $category_id);
                }
                if ($user_category != "") {
                    $this->db->where("products.category_id NOT IN (" . $user_category . ") ");
				}
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

    public function getProductReturns($term, $warehouse_id, $standard, $combo, $digital, $service, $user_category, $category_id, $limit = 20)
    {
        $this->db->select('products.id, start_date, end_date, erp_products.code, erp_products.name, erp_products.type, cost, warehouses_products.product_id, warehouses_products.quantity AS qoh, warehouses_products.quantity, price, tax_rate, tax_method, erp_products.image, promotion, promo_price, product_details, details, categories.type AS cate_type, subcategory_id, cf1, COALESCE((SELECT GROUP_CONCAT(sp.`serial_number`) FROM erp_serial as sp WHERE sp.product_id='.$this->db->dbprefix('products').'.id), "") as sep')
            ->join('categories', 'categories.id=products.category_id', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
            $this->db->where("(erp_products.name LIKE '%" . $term . "%' 
            OR erp_products.code LIKE '%" . $term . "%' 
            OR  concat(erp_products.name, ' (', erp_products.code, ')') LIKE '%" . $term . "%') 
            AND inactived <> 1");
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
            if ($category_id != "") {
                $this->db->where("products.category_id", $category_id);
            }
            if ($user_category != "") {
                $this->db->where("products.category_id NOT IN (" . $user_category . ") ");
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
            if ($category_id != "") {
                $this->db->where("products.category_id", $category_id);
            }
            if ($user_category != "") {
                $this->db->where("products.category_id NOT IN (" . $user_category . ") ");
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

    public function getQtyOrder($product_id){
		$this->db->select('COALESCE(erp_sale_order_items.quantity,0) as quantity')
		         ->join('erp_sale_order_items','erp_sale_order.id = erp_sale_order_items.sale_order_id','left')
		         ->where('erp_sale_order.order_status <> "completed" AND erp_sale_order_items.product_id = "'.$product_id.'"')
				 ->from('erp_sale_order');
		$q=$this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
				 
	}
	public function getSaleExportByID($id=null, $wh=null)
    {
		$this->db
			->select("sales.id, sales.date as date,erp_quotes.reference_no as q_no, sale_order.reference_no as so_no, 
			sales.reference_no as sale_no, sales.biller, group_areas.areas_group, sales.customer, 
			users.username AS saleman, sales.sale_status, COALESCE(erp_sales.grand_total,0) as amount,
			COALESCE((SELECT SUM(erp_return_sales.grand_total) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id), 0) as return_sale,
			COALESCE( (SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0) as paid, 
			(SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id  ) as deposit,
			SUM(COALESCE(erp_payments.discount,0)) as discount, 
			(COALESCE(erp_sales.grand_total,0)- COALESCE((SELECT SUM(erp_return_sales.grand_total) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id), 0)-COALESCE( (SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0)- COALESCE((SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id  ),0)-SUM(COALESCE(erp_payments.discount,0)) ) as balance, 
			sales.payment_status,sales.join_lease_id")
		->join('users', 'users.id = sales.saleman_by', 'left')
		->join('sale_order', 'sale_order.id = sales.so_id', 'left')
		->join('payments', 'payments.sale_id = sales.id', 'left')
		->join('group_areas', 'group_areas.areas_g_code = sales.group_areas_id', 'left')
		->join('quotes', 'quotes.id = sales.quote_id', 'left')
		->join('companies', 'companies.id = sales.customer_id', 'left');
		if($wh){
			$this->db->where_in('erp_sales.warehouse_id',$wh);
		}
        $q = $this->db->get_where('sales', array('sales.id' => $id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getExportCustomerBalance($id=null, $wh=null)
    {
		$this->db
			->select("sales.id, sales.date as date, sales.due_date as due_date, sales.reference_no as reference_no, sales.biller, sales.customer, 
			users.username AS saleman, sales.sale_status,sales.product_tax, 
			COALESCE(erp_sales.grand_total,0) as amount,
			COALESCE((SELECT SUM(erp_return_sales.grand_total) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id), 0) as return_sale,
			COALESCE( (SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0) as paid, 
			(SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id  ) as deposit,
			SUM(COALESCE(erp_payments.discount,0)) as discount, 
			(COALESCE(erp_sales.grand_total,0)- COALESCE((SELECT SUM(erp_return_sales.grand_total) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id), 0)-COALESCE((SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0)- COALESCE((SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id  ),0)-SUM(COALESCE(erp_payments.discount,0)) ) as balance, 
			sales.payment_status,sales.join_lease_id")
		->join('users', 'users.id = sales.saleman_by', 'left')
		->join('sale_order', 'sale_order.id = sales.so_id', 'left')
		->join('payments', 'payments.sale_id = sales.id', 'left')
		->join('group_areas', 'group_areas.areas_g_code = sales.group_areas_id', 'left')
		->join('quotes', 'quotes.id = sales.quote_id', 'left')
		->join('companies', 'companies.id = sales.customer_id', 'left');
		if($wh){
			$this->db->where_in('erp_sales.warehouse_id',$wh);
		}
        $q = $this->db->get_where('sales', array('sales.id' => $id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getProductNumber($term, $warehouse_id, $standard, $combo, $digital, $service, $user_category, $category_id, $limit = 100)
    {
		if(preg_match('/\s/', $term))
		{
			$name = explode(" ", $term);
			$first = $name[0];
            $this->db->select('products.id, products.code, products.name, products.type, cost,warehouses_products.quantity, warehouses_products.quantity as qoh, price, tax_rate, tax_method, cf1, product_details,subcategory_id, categories.type AS cate_type, details,COALESCE((SELECT GROUP_CONCAT(sp.`serial_number`) 
					FROM erp_serial as sp
				 WHERE sp.product_id='.$this->db->dbprefix('products').'.id
				), "") as sep')
                     ->join('categories', 'categories.id=products.category_id', 'left')
                     ->group_by('products.id');
			if($this->Owner || $this->admin){
				if ($category_id != "") {
                    $this->db->where("products.category_id", $category_id);
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
				if ($category_id != "") {
                    $this->db->where("products.category_id", $category_id);
                }
				if($user_category != ""){
					$this->db->where("products.category_id NOT IN (".$user_category.") ");
				}
			}
			$this->db->where('products.code', $first);
			$this->db->limit($limit);
			$q = $this->db->get('products');
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			}
		}else {

            $this->db->select('products.id, products.code, products.name, products.type, categories.type AS cate_type, cost, warehouses_products.quantity, warehouses_products.quantity as qoh, price, tax_rate, tax_method, product_details, details, COALESCE((SELECT GROUP_CONCAT(sp.`serial_number`) FROM erp_serial as sp WHERE sp.product_id=' . $this->db->dbprefix('products') . '.id ), "") as sep, cf1, subcategory_id')
                ->join('categories', 'categories.id=products.category_id', 'left')
                ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
                ->group_by('products.id');
			if($this->Owner || $this->admin){
				if ($category_id != "") {
                    $this->db->where("products.category_id", $category_id);
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
				if ($category_id != "") {
                    $this->db->where("products.category_id", $category_id);
                }
				if($user_category != ""){
					$this->db->where("products.category_id NOT IN (".$user_category.") ");
				}
			}
			$this->db->where("(erp_products.code LIKE '%" . $term . "%' OR erp_products.name LIKE '%" . $term . "%')");
			
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
	public function getCustomersByArea($area = null){		
		$this->db->select('id as id, CONCAT(name ," (",company, ")" ) as text');
		if($area != null) {
			$q = $this->db->get_where('companies', array('group_name' => 'customer','group_areas_id' => $area));
		}else {
			//$q = $this->db->get('companies');
			$q = $this->db->get_where('companies', array('group_name' => 'customer'));
		}
        if($q->num_rows() > 0) {
			return $q->result();
		}
		return false;
	}
    public function getCustomersCodeByArea($area = null){
        $this->db->select('id as id, CONCAT(code ," - ",IF(company!="",company,name)) as text');
        if($area != null) {
            $q = $this->db->get_where('companies', array('group_name' => 'customer','group_areas_id' => $area));
        }else {
            //$q = $this->db->get('companies');
            $q = $this->db->get_where('companies', array('group_name' => 'customer'));
        }
        if($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
	public function getCusDetail($customer_id){		
		$this->db->select('companies.credit_limited,IFNULL(sum(erp_sales.grand_total - erp_sales.paid), 0) AS balance');
		$this->db->join('sales', 'sales.customer_id = companies.id', 'INNER');
		$this->db->where('companies.id = '.$customer_id.' and (sales.payment_status = "due" or sales.payment_status = "partial" ) ');
		$result = $this->db->get('companies')->row();
		return $result;
	}
	
	public function getProductCodes($term, $warehouse_id, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('products').'.id,
				'.$this->db->dbprefix('products').'.code,
				'.$this->db->dbprefix('products').'.name, 
				details, category_id, price,
				'.$this->db->dbprefix('products').'.image,
				'. $this->db->dbprefix('categories').'.name as cate_name, 
				COALESCE((SELECT GROUP_CONCAT(related_pro.`name`) 
					FROM erp_related_products as related
					LEFT JOIN erp_products as related_pro on related_pro.`code` = related.`related_product_code`
				 WHERE related.product_code='.$this->db->dbprefix('products').'.code
				), "") as strap')
		->join('categories', 'categories.id=products.category_id', 'left')
		->group_by('products.id');
		$this->db->where("(".$this->db->dbprefix('products').".code LIKE '%" . $term . "%' )");
		//$this->db->limit($limit);
		$q = $this->db->get('products');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }

	public function getPname($term, $warehouse_id, $code, $category, $price, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('products').'.id,
				'.$this->db->dbprefix('products').'.code,
				'.$this->db->dbprefix('products').'.name, 
				details, category_id, price, 
				'.$this->db->dbprefix('products').'.image,
				'. $this->db->dbprefix('categories').'.name as cate_name, 
				COALESCE((SELECT GROUP_CONCAT(related_pro.`name`) 
					FROM erp_related_products as related
					LEFT JOIN erp_products as related_pro on related_pro.`code` = related.`related_product_code`
				 WHERE related.product_code='.$this->db->dbprefix('products').'.code
				), "") as strap')
		->join('categories', 'categories.id=products.category_id', 'left')
		->group_by('products.id');
		if($code == NULL){
			$this->db->where("(".$this->db->dbprefix('products').".name LIKE '%" . $term . "%' )");
		}else{
			$this->db->where("(".$this->db->dbprefix('products').".name LIKE '%" . $term . "%' and ".$this->db->dbprefix('products').".code LIKE '%" . $code . "%' and ".$this->db->dbprefix('categories').".name LIKE '%" . $category . "%' and ".$this->db->dbprefix('products').".price LIKE '%" . $price . "%' )");
		}
		//$this->db->limit($limit);
		$q = $this->db->get('products');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
	
	public function getPdescription($term, $warehouse_id, $name, $code, $price, $category, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('products').'.id,
				'.$this->db->dbprefix('products').'.code,
				'.$this->db->dbprefix('products').'.name, 
				details, category_id, price, 
				'.$this->db->dbprefix('products').'.image,
				'. $this->db->dbprefix('categories').'.name as cate_name,
				COALESCE((SELECT GROUP_CONCAT(related_pro.`name`) 
					FROM erp_related_products as related
					LEFT JOIN erp_products as related_pro on related_pro.`code` = related.`related_product_code`
				 WHERE related.product_code='.$this->db->dbprefix('products').'.code
				), "") as strap')
		->join('categories', 'categories.id=products.category_id', 'left')
		->group_by('products.id');
		if($name == null and $code == null and $price == null and $category == null){
			$this->db->where("(".$this->db->dbprefix('products').".details LIKE '%" . $term . "%' )");
		}else{
			$this->db->where("(".$this->db->dbprefix('products').".details LIKE '%" . $term . "%' and ".$this->db->dbprefix('products').".name LIKE '%" . $name . "%' and ".$this->db->dbprefix('products').".code LIKE '%" . $code . "%' and ".$this->db->dbprefix('products').".price LIKE '%" . $price . "%' and ".$this->db->dbprefix('category').".name LIKE '%" . $category . "%' )");
		}
		//$this->db->limit($limit);
		$q = $this->db->get('products');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
	
	public function getPcategory($term, $warehouse_id, $code, $name, $price, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('products').'.id,
				'.$this->db->dbprefix('products').'.code,
				'.$this->db->dbprefix('products').'.name, 
				details, category_id, price, 
				'.$this->db->dbprefix('products').'.image,
				'. $this->db->dbprefix('categories').'.name as cate_name,
				COALESCE((SELECT GROUP_CONCAT(related_pro.`name`) 
					FROM erp_related_products as related
					LEFT JOIN erp_products as related_pro on related_pro.`code` = related.`related_product_code`
				 WHERE related.product_code='.$this->db->dbprefix('products').'.code
				), "") as strap')
		->join('categories', 'categories.id=products.category_id', 'left')
		->group_by('products.id');
		if($code == null and $name == null and $price == null){
			$this->db->where("(".$this->db->dbprefix('categories').".name LIKE '%" . $term . "%' )");
		}else{
			$this->db->where("(".$this->db->dbprefix('categories').".name LIKE '%" . $term . "%' and ".$this->db->dbprefix('products').".code LIKE '%" . $code . "%' and ".$this->db->dbprefix('products').".name LIKE '%" . $name . "%' and ".$this->db->dbprefix('products').".price LIKE '%" . $price . "%' )");
		}
		
		//$this->db->limit($limit);
		$q = $this->db->get('products');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
	
	public function getPprice($term, $warehouse_id, $code, $name, $category, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('products').'.id,
				'.$this->db->dbprefix('products').'.code,
				'.$this->db->dbprefix('products').'.name, 
				details, category_id, price, 
				'.$this->db->dbprefix('products').'.image,
				'. $this->db->dbprefix('categories').'.name as cate_name,
				COALESCE((SELECT GROUP_CONCAT(related_pro.`name`) 
					FROM erp_related_products as related
					LEFT JOIN erp_products as related_pro on related_pro.`code` = related.`related_product_code`
				 WHERE related.product_code='.$this->db->dbprefix('products').'.code
				), "") as strap')
		->join('categories', 'categories.id=products.category_id', 'left')
		->group_by('products.id');
		if($code == null and $name == null and $category == null){
			$this->db->where("(".$this->db->dbprefix('products').".price LIKE '%" . $term . "%' )");
		}else{
			$this->db->where("(".$this->db->dbprefix('products').".price LIKE '%" . $term . "%' and ".$this->db->dbprefix('products').".code LIKE '%" . $code . "%' and ".$this->db->dbprefix('products').".name LIKE '%" . $name . "%' and ".$this->db->dbprefix('categories').".name LIKE '%" . $category . "%' )");
		}
		
		//$this->db->limit($limit);
		$q = $this->db->get('products');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
	
	public function getPstrap($term, $warehouse_id, $limit = 5)
    {
		$sub_string = "(
							SELECT
								erp_products.*
							FROM
								erp_products
							LEFT JOIN erp_related_products ON erp_products. CODE = erp_related_products.product_code
							WHERE
								erp_related_products.product_name LIKE '%" . $term . "%'
						) AS erp_products";
		$this->db->select('erp_products.id, erp_products.code, erp_products.name, details, category_id, price, 
				'.$this->db->dbprefix('products').'.image,
				'. $this->db->dbprefix('categories').'.name as cate_name,
				COALESCE((SELECT GROUP_CONCAT(related_pro.`name`) 
					FROM erp_related_products as related
					LEFT JOIN erp_products as related_pro on related_pro.`code` = related.`related_product_code`
				 WHERE related.product_code=erp_products.code
				), "") as strap')
		->join('categories', 'categories.id = erp_products.category_id', 'left')
		->group_by('erp_products.id');
		//$this->db->limit($limit);
		$q = $this->db->get($sub_string);
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
    
	public function getfcode($term, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('suspended').'.id,'.$this->db->dbprefix('suspended').'.name, description, floor, status');
		$this->db->where("(".$this->db->dbprefix('suspended').".name LIKE '%" . $term . "%' )");
		//$this->db->limit($limit);
		$q = $this->db->get('suspended');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
	
	public function getfdescription($term, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('suspended').'.id,'.$this->db->dbprefix('suspended').'.name, description, floor, status');
		$this->db->where("(".$this->db->dbprefix('suspended').".description LIKE '%" . $term . "%' )");
		//$this->db->limit($limit);
		$q = $this->db->get('suspended');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }
	
	public function getffloor($term, $limit = 5)
    {
		$this->db->select($this->db->dbprefix('suspended').'.id,'.$this->db->dbprefix('suspended').'.name, description, floor, status');
		$this->db->where("(".$this->db->dbprefix('suspended').".floor LIKE '%" . $term . "%' )");
		//$this->db->limit($limit);
		$q = $this->db->get('suspended');
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
    }    
	
    public function getProductComboItems($pid, $warehouse_id = NULL)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name,products.type as type, warehouses_products.quantity as quantity')
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

    public function getProductByCode($code)
    {
        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function syncQuantity($sale_id)
    {
        if ($sale_items = $this->getAllInvoiceItems($sale_id)) {
            foreach ($sale_items as $item) {
                $this->site->syncProductQty($item->product_id, $item->warehouse_id);
                if (isset($item->option_id) && !empty($item->option_id)) {
                    $this->site->syncVariantQty($item->option_id, $item->warehouse_id);
                }
            }
        }
    }

    public function getProductQuantity($product_id, $warehouse)
    {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }
        return FALSE;
    }
	/* POS Option */
    public function getProductOptions($product_id, $warehouse_id, $all = NULL)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.price as price, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity,product_variants.qty_unit as qty_unit,
		1 AS rate,
		(
			SELECT
				rate
			FROM
				erp_currencies curr
			WHERE
				curr. CODE = "USD"
		) AS setting_curr')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
			->join('currencies', 'currencies.code = product_variants.currentcy_code', 'left')
            //->join('warehouses', 'warehouses.id=product_variants.warehouse_id', 'left')
            ->where('product_variants.product_id', $product_id)
			->where('product_variants.product_id !=', 0)
            //->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            ->group_by('product_variants.id');
            if( ! $this->Settings->overselling && ! $all) {
                $this->db->where('warehouses_products_variants.quantity >', 0);
            }
		$this->db->order_by('product_variants.qty_unit', 'DESC');
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductVariants($product_id)
    {
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

        $q = $this->db->get_where('sale_items', array('id' => $id), 1);
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
	
	function getCustomerByID($cus_id = null)
	{
		$this->db->where(array('id' => $cus_id));
        $q = $this->db->get('companies');
        if ($q->num_rows() > 0) {
            return $q->row();
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
    
    public function getSalesReferences($term, $limit = 10)
    {
        $this->db->select('reference_no');
        $this->db->where("(reference_no LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getAllInvoiceItems($sale_id)
    {
        $this->db->select("sale_items.*,
        					tax_rates.code as tax_code,
        					tax_rates.name as tax_name,
        					tax_rates.rate as tax_rate,
        					(CASE WHEN erp_products.unit = 0 THEN erp_products.unit ELSE erp_units.name END) as uname,
        					products.details as details,
        					product_variants.name as variant,
        					product_variants.qty_unit,
        					units.name as unit,
        					products.promotion,
        					products.promo_price,
        					categories.name AS category_name,
							categories.image,
        					products.start_date,
        					products.end_date,
							sale_items.product_noted, products.name_kh,
							products.currentcy_code
        				")
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->join('units', 'units.id = products.unit', 'left')
            ->group_by('sale_items.id');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllSaleItemsBySaleId($sale_id)
    {
        $this->db->select("sale_items.*,
        					tax_rates.code as tax_code,
        					tax_rates.name as tax_name,
        					tax_rates.rate as tax_rate,
        					(CASE WHEN erp_products.unit = 0 THEN erp_products.unit ELSE erp_units.name END) as uname,
        					products.details as details,
        					product_variants.name as variant,
        					product_variants.qty_unit,
        					units.name as unit,
        					products.promotion,
        					products.promo_price,
        					categories.name AS category_name,
							categories.image,
        					products.start_date,
        					products.end_date,
							sale_items.product_noted, products.name_kh,
							products.currentcy_code
        				")
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->join('units', 'units.id = products.unit', 'left')
            ->group_by('sale_items.id')
            ->order_by('sale_items.product_id', 'ASC');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllInvoiceItemsByID($sale_id)
    {
        $this->db->select("sale_items.*,
							products.cf1,
							products.cf2,
							products.cf3,
							products.cf4,
							products.cf5,
							products.cf6,
        					tax_rates.code as tax_code,
        					tax_rates.name as tax_name,
        					tax_rates.rate as tax_rate,
        					(CASE WHEN erp_products.unit = 0 THEN erp_products.unit ELSE erp_units.name END) as uname,
        					products.details as details,
        					product_variants.name as variant,
        					product_variants.qty_unit,
        					units.name as unit,
        					products.promotion,
        					products.promo_price,
        					categories.name AS category_name,
        					products.start_date,
        					products.end_date,
							sale_items.product_noted, products.name_kh
        				")
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->join('units', 'units.id = products.unit', 'left')
            ->group_by('sale_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
	function getCustomerStatementByID($id)
	{	
		$this->db->select('sale_items.unit_price,sales.order_discount,terms.description')
			 ->join('sale_items', 'sale_items.sale_id = sales.id', 'left')
			 ->join('terms', 'terms.id = sales.term_id', 'left');
		$this->db->where(array('sales.id' => $id));
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
    public function getAllSOInvoiceItemsByID($sale_id)
    {
        $this->db->select("sale_order_items.*,
							products.cf1,
							products.cf2,
							products.cf3,
							products.cf4,
							products.cf5,
							products.cf6,
        					tax_rates.code as tax_code,
        					tax_rates.name as tax_name,
        					tax_rates.rate as tax_rate,
        					(CASE WHEN erp_products.unit = 0 THEN erp_products.unit ELSE erp_units.name END) as uname,
        					products.details as details,
        					product_variants.name as variant,
        					product_variants.qty_unit,
        					units.name as unit,
        					products.promotion,
        					products.promo_price,
        					categories.name AS category_name,
        					products.start_date,
        					products.end_date,
							sale_order_items.product_noted, products.name_kh
        				")
            ->join('products', 'products.id=sale_order_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_order_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_order_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->join('units', 'units.id = products.unit', 'left')
            ->group_by('sale_order_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_order_items', array('sale_order_id' => $sale_id));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }

    public function getCond($sale_id)
    {
        $this->db->select("sale_items.*,
        					tax_rates.code as tax_code,
        					tax_rates.name as tax_name,
        					tax_rates.rate as tax_rate,
        					(CASE WHEN erp_products.unit = 0 THEN erp_products.unit ELSE erp_units.name END) as uname,
        					products.details as details,
        					product_variants.name as variant,
        					product_variants.qty_unit,
        					units.name as unit,
        					products.promotion,
        					products.promo_price,
        					categories.name AS category_name,
        					products.start_date,
        					products.end_date
        				")
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->join('units', 'units.id = products.unit', 'left')
            ->group_by('sale_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPaid($ref=null){
    	$this->db->select("SUM(erp_payments.amount) as amount")
    			->from("erp_payments")
    			->where("erp_payments.reference_no",$ref);
    	$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function getAllProductVarain($id){
        $this->db->select('*');
        $q = $this->db->get_where('product_variants', array('product_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getMaxqty($product_id){
        $this->db->select('sale_items.option_id,MAX(qty_unit) as maxqty')
        ->join('sale_items', 'sale_items.product_id = product_variants.product_id', 'left');
        $q = $this->db->get_where('product_variants', array('product_variants.product_id' => $product_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getMinqty($product_id){
        $this->db->select('price,MIN(qty_unit) as minqty');
        $q = $this->db->get_where('product_variants', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getMaxunit($Max_unitqty,$product_id){
        $this->db->select('name');
        $q = $this->db->get_where('product_variants', array('qty_unit' => $Max_unitqty,'product_id' => $product_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getMinunit($minqty,$product_id){
        $this->db->select('name');
        $q = $this->db->get_where('product_variants', array('qty_unit' => $minqty,'product_id' => $product_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getMaxqtyByProID($pid){
        $this->db->select('MAX(qty_unit) as mva, product_id');
        $q = $this->db->get_where('product_variants', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getVarain($pid){
        $this->db->select('*');
        $q = $this->db->get_where('product_variants', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	 public function getAllReturnsItem($sale_id)
    {
        $this->db->select('return_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, (CASE WHEN erp_products.unit = 0 THEN erp_products.unit ELSE erp_units.name END) as uname, products.details as details, product_variants.name as variant, products.unit, products.promotion, categories.name AS category_name')
            ->join('products', 'products.id=return_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=return_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=return_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->join('units', 'units.id = products.unit', 'left')
            ->group_by('return_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('return_items', array('return_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getAllInvoicesItem($sale_id)
    {
        $this->db->select('sale_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, (CASE WHEN erp_products.unit = 0 THEN erp_products.unit ELSE erp_units.name END) as uname, products.details as details, product_variants.name as variant, products.unit, products.promotion, categories.name AS category_name')
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->join('units', 'units.id = products.unit', 'left')
            ->group_by('sale_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getAllInvoicesItems($sale_id)
    {
        $this->db->select('sale_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, (CASE WHEN erp_products.unit = 0 THEN erp_products.unit ELSE erp_units.name END) as uname, products.details as details, product_variants.name as variant, products.unit, products.promotion, categories.name AS category_name')
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->join('units', 'units.id = products.unit', 'left')
            ->group_by('sale_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach ($q->result_array() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


	function getPaymentBySaleID($sale_id){
		$q = $this->db->get_where('payments', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
    
	function getPaymentByReturnID($return_id, $sale_id = null){
		$q = $this->db->get_where('payments', array('return_id' => $return_id, 'sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
    
    public function getAllsuspendItem($sale_id)
    {
        $this->db->select('suspended_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant')
            ->join('products', 'products.id=suspended_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=suspended_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=suspended_items.tax_rate_id', 'left')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('suspended_items', array('suspend_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getAllSuspendDetail($id){
    	
    	$q = $this->db->get_where('suspended_bills', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;        
    }
	
	public function getAllSuspendbySupendID($id){
    	
    	$q = $this->db->get_where('suspended_bills', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;        
    }


    public function getAllRoomDetail($id){
    	
    	$q = $this->db->get_where('suspended', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;        
    }

    public function getAllReturnItems($return_id)
    {
        $this->db->select('return_items.*, products.details as details, 
									product_variants.name as variant,units.name as product_unit')
            ->join('products', 'products.id=return_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=return_items.option_id', 'left')
			->join('units', 'units.id=products.unit', 'left')
            ->group_by('return_items.id')
            ->order_by('id', 'desc');
        $q = $this->db->get_where('return_items', array('return_id' => $return_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    public function getAllInvoiceOrderItemsWithDetails($sale_id)
    {
        $this->db->select('erp_sale_order_items.id, erp_sale_order_items.product_name, erp_sale_order_items.product_code,products.price, erp_sale_order_items.quantity, erp_sale_order_items.serial_no, erp_sale_order_items.tax, erp_sale_order_items.net_unit_price, erp_sale_order_items.item_tax, erp_sale_order_items.item_discount, erp_sale_order_items.subtotal, products.details');
        $this->db->join('products', 'products.id=erp_sale_order_items.product_id', 'left');
        $this->db->order_by('id', 'asc');
        if(is_array($sale_id)){
            $this->db->or_where_in('sale_order_id', $sale_id);
            $q = $this->db->get('erp_sale_order_items');
        }else{
            $q = $this->db->get_where('sale_order_items', array('sale_order_id' => $sale_id));
        }
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getAllInvoiceOrderItems($delivery_id)
    {
        $this->db->select('erp_sale_order_items.id, erp_sale_order_items.product_name, erp_sale_order_items.product_code,products.price, erp_sale_order_items.quantity, erp_sale_order_items.serial_no, erp_sale_order_items.tax, erp_sale_order_items.net_unit_price, erp_sale_order_items.item_tax, erp_sale_order_items.item_discount, erp_sale_order_items.subtotal, products.details');
        $this->db->join('products', 'products.id=erp_sale_order_items.product_id', 'left');
        $this->db->order_by('id', 'asc');
        if(is_array($sale_id)){
            $this->db->or_where_in('sale_order_id', $sale_id);
            $q = $this->db->get('erp_sale_order_items');
        }else{
            $q = $this->db->get_where('sale_order_items', array('sale_order_id' => $sale_id));
        }
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getAllDeliveryInvoiceItems($delivery_id)
    {
        $this->db->select('erp_deliveries.*,
        					erp_delivery_items.product_name, erp_products.id as product_id, erp_products.code,erp_delivery_items.piece,erp_delivery_items.wpiece,delivery_items.option_id,
        					COALESCE(SUM(erp_delivery_items.quantity_received)) as quantity_received,
        					erp_companies.name,
							product_variants.name as variant,
        					units.name as unit,
        					product_variants.name as variant
        				');
		$this->db->from('deliveries');
		$this->db->join('erp_companies','deliveries.delivery_by = erp_companies.id','left');
		$this->db->join('delivery_items','delivery_items.delivery_id = deliveries.id', 'left');
		$this->db->join('erp_products','delivery_items.product_id = erp_products.id', 'left');
		$this->db->join('erp_product_variants','delivery_items.option_id = erp_product_variants.id', 'left');
		$this->db->join('units','erp_products.unit = units.id', 'left');
		$this->db->where('erp_deliveries.id',$delivery_id);
		$this->db->group_by('delivery_items.id');
		
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $result){
				$data[] = $result;
			}
			return $data;
		}
		return NULL;
		
    }
	public function getAllInvoiceItem($product_id)
    {
        $this->db->select('sale_items.*,erp_sales.customer,erp_sales.reference_no,erp_users.username as name') 
		    ->join('erp_sales','erp_sales.id=erp_sale_items.sale_id','LEFT')
			->join('erp_users','erp_users.id=erp_sales.created_by','LEFT')
            ->order_by('quantity', 'DESC');
        $q = $this->db->get_where('sale_items', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getAllInvoiceItemsWithDetails($sale_id)
    {
        $this->db->select('sale_items.id, sale_items.product_name,products.code as product_code,products.price, sale_items.quantity, sale_items.serial_no, sale_items.tax, sale_items.net_unit_price, sale_items.item_tax, sale_items.item_discount, sale_items.subtotal, products.details,sale_items.piece,sale_items.wpiece');
        $this->db->join('products', 'products.id=sale_items.product_id', 'left');
        $this->db->order_by('id', 'asc');
        if(is_array($sale_id)){
            $this->db->or_where_in('sale_id', $sale_id);
            $q = $this->db->get('sale_items');
        }else{
            $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        }
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getAllDeliInvoiceItems($delivery_id)
    {
        $this->db->select('delivery_items.id,delivery_items.product_name, products.code AS product_code , delivery_items.quantity_received as quantity');
        $this->db->join('products', 'products.id = delivery_items.product_id', 'left');
        $this->db->order_by('id', 'asc');
        if(is_array($delivery_id)){
            $this->db->or_where_in('delivery_id', $delivery_id);
            $q = $this->db->get('delivery_itemss');
        }else{
            $q = $this->db->get_where('delivery_items', array('delivery_id' => $delivery_id));
        }
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }


	public function getProductComboItemsCode($sale_id){
		$this->db->select('sale_items.id, combo_items.item_code, combo_items.quantity, sale_items.product_code ');
        $this->db->join('products', 'products.id=sale_items.product_id', 'left');
		$this->db->join('combo_items', 'combo_items.product_id=products.id', 'left');
		$this->db->group_by('combo_items.item_code');
        $this->db->order_by('id', 'asc');
        if(is_array($sale_id)){
            $this->db->or_where_in('sale_id', $sale_id);
            $q = $this->db->get('sale_items');
        }else{
            $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        }
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	
    /*public function getInvoiceByID($id)
    {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }*/
	public function getJoinlease($id=null)
	{
		$this->db
			 ->select("loans.period,loans.principle,loans.interest,erp_sales.order_discount,erp_sales.term_id,erp_sales.principle_type,erp_sales.down_date,companies.name,companies.phone,companies.gender,companies.date_of_birth,companies.cf1 as identify_card,terms.description,term_types.name as term_name")
			 ->join('companies', 'companies.id = erp_sales.join_lease_id', 'left')
			 ->join('terms','terms.id = erp_sales.term_id','left')
			 ->join('loans','loans.sale_id = erp_sales.id','left')
			 ->join('term_types','term_types.id = erp_sales.principle_type','left');
			
        $q = $this->db->get_where('erp_sales', array('erp_sales.id' => $id,'companies.group_name'=>'join_lease'));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
	}
	
	public function getProductPaymentsForSaleFlora($id=null)
	{
		$q = $this->db->get('erp_products');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getInvoiceByID($id=null,$wh=null)
    {
		$this->db
			 ->select("sales.*, companies.name,companies.company,companies.logo,companies.cf4,companies.phone, companies.email,
			  quotes.reference_no as quote_no, users.username as saleman,
			  (SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) 
			  FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id  ) as deposit,
			   (erp_sales.paid - (SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) 
			   FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id)) as real_paid, 
			   sale_order.reference_no as so_no, erp_companies.address, erp_sales.sale_status, 
			   companies.invoice_footer as invoice_footer, group_areas.areas_group, 
			   tax_rates.name as vat, payment_term.description as payment_term, sales.due_date")
			 ->join('companies', 'sales.biller_id = companies.id', 'left')
			 ->join('quotes', 'sales.quote_id = quotes.id', 'left')
			 ->join('payments', 'payments.sale_id = sales.id', 'left')
			 ->join('group_areas', 'group_areas.areas_g_code = sales.group_areas_id', 'left')
			 ->join('sale_order', 'sale_order.id = sales.so_id', 'left')
			 ->join('users', 'sales.saleman_by = users.id', 'left')
			 ->join('tax_rates', 'sales.order_tax_id = tax_rates.id', 'left')
			 ->join('payment_term', 'sales.payment_term = payment_term.id', 'left');

			 if($wh){
			 	$this->db->where_in('erp_sales.warehouse_id',$wh);
			 }
        $q = $this->db->get_where('sales', array('sales.id' => $id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getArInvoiceByID($id=null,$wh=null)
    {
        $this->db
            ->select("sales.*, companies.name,companies.company,companies.logo,companies.cf4,companies.phone, companies.email,
			  quotes.reference_no as quote_no, users.username as saleman,
			  (SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) 
			  FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id  ) as deposit,
			   (erp_sales.paid - (SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) 
			   FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id)) as real_paid, 
			   sale_order.reference_no as so_no, erp_companies.address, erp_sales.sale_status, 
			   companies.invoice_footer as invoice_footer, group_areas.areas_group, 
			   tax_rates.name as vat, payment_term.description as payment_term, sales.due_date")
            ->join('companies', 'sales.biller_id = companies.id', 'left')
            ->join('quotes', 'sales.quote_id = quotes.id', 'left')
            ->join('payments', 'payments.sale_id = sales.id', 'left')
            ->join('group_areas', 'group_areas.areas_g_code = sales.group_areas_id', 'left')
            ->join('sale_order', 'sale_order.id = sales.so_id', 'left')
            ->join('users', 'sales.saleman_by = users.id', 'left')
            ->join('tax_rates', 'sales.order_tax_id = tax_rates.id', 'left')
            ->join('payment_term', 'sales.payment_term = payment_term.id', 'left');

        if($wh){
            $this->db->where_in('erp_sales.warehouse_id',$wh);
        }
        $q = $this->db->get_where('sales', array('sales.customer_id' => $id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
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
	
	public function getLoansByPaymentID($id)
    {
		$this->db
			 ->select("loans.period,loans.principle,loans.interest,payments.*")
			 ->join('loans', 'loans.id = payments.loan_id', 'left');
        $q = $this->db->get_where('payments', array('payments.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getProductNewByID($id = null)
    {
		$this->db
			 ->select("sales.*,products.cf1,products.cf3,products.cf4")
			 ->join('sale_items', 'sale_items.sale_id = sales.id', 'left')
			 ->join('products', 'products.id = sale_items.product_id', 'left');
        $q = $this->db->get_where('sales', array('sales.id' => $id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getProductNew($id=null)
    {
		$this->db
			 ->select("sales.*,products.cf1,products.cf3,products.cf4")
			 ->join('sale_items', 'sale_items.sale_id = sales.id', 'left')
			 ->join('products', 'products.id = sale_items.product_id', 'left');
        $q = $this->db->get_where('sales', array('sales.id' => $id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getSOByID($id=null,$wh=null)
    {
		$this->db
			 ->select("sale_order.*, companies.phone, companies.email, quotes.reference_no as quote_no, users.username as saleman, sale_order.reference_no as so_no, erp_companies.address, erp_sale_order.order_status as sale_status, companies.invoice_footer as invoice_footer, group_areas.areas_group")
			 ->join('companies', 'sale_order.biller_id = companies.id', 'left')
			 ->join('quotes', 'sale_order.quote_id = quotes.id', 'left')
			 ->join('group_areas', 'group_areas.areas_g_code = sale_order.group_areas_id', 'left')
			 ->join('users', 'sale_order.saleman_by = users.id', 'left');
			 if($wh){
			 	$this->db->where_in('erp_sale_order.warehouse_id',$wh);
			 }
        $q = $this->db->get_where('sale_order', array('sale_order.id' => $id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getDeliveryID()
    {
        $q = $this->db->get('delivery_items');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSaleReturnByID($id = null,$wh = null) {
    	$this->db
            ->select("return_sales.*, payments.amount as paid")
            ->join('payments', 'return_sales.id = payments.return_id', 'left');
			 if($wh){
			 	$this->db->where_in('erp_sales.warehouse_id',$wh);
			 }
        $q = $this->db->get_where('return_sales', array('return_sales.id' => $id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function getReturnItemByID($id) {
        $this->db->select("return_items.*,
        					tax_rates.code as tax_code,
        					tax_rates.name as tax_name,
        					tax_rates.rate as tax_rate,
        					(CASE WHEN erp_products.unit = 0 THEN erp_products.unit ELSE erp_units.name END) as uname,
        					products.details as details,
        					product_variants.name as variant,
        					product_variants.qty_unit,
        					units.name as unit,
        					products.promotion,
        					products.promo_price,
        					categories.name AS category_name,
        					products.start_date,
        					products.end_date,
							products.name_kh,
							products.currentcy_code,
							sale_items.quantity as sqty
        				")
            ->join('products', 'products.id=return_items.product_id', 'left')
			->join('sale_items', 'sale_items.id = return_items.sale_item_id', 'left')
            ->join('product_variants', 'product_variants.id=return_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=return_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->join('units', 'units.id = products.unit', 'left')
            ->group_by('return_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('return_items', array('return_items.return_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getReturnedQty($sale_id, $product_id, $id) {
		$this->db->select("SUM(quantity) as returned_qty");
		$this->db->where("id <>", $id);
		$q = $this->db->get_where('return_items', array('sale_id' => $sale_id, 'product_id' => $product_id));
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getInvoiceByID1($id)
    {
		$this->db
			 ->select("sales.*, companies.phone, companies.email, quotes.reference_no as quote_no, users.username as saleman,(SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id  ) as deposit, (erp_sales.paid - (SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id)) as paid, sale_order.reference_no as so_no")
			 ->join('companies', 'sales.biller_id = companies.id', 'left')
			 ->join('quotes', 'sales.quote_id = quotes.id', 'left')
			 ->join('payments', 'payments.sale_id = sales.id', 'left')
			 ->join('sale_order', 'sale_order.id = sales.so_id', 'left')
			 ->join('users', 'sales.saleman_by = users.id', 'left');
        $q = $this->db->get_where('sales', array('sales.id' => $id),1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getSOInvoiceByID($id)
    {
		$this->db
			 ->select('sale_order.*, companies.phone, companies.email')
			 ->join('companies', 'sale_order.biller_id = companies.id', 'left');
        $q = $this->db->get_where('sale_order', array('sale_order.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getSaleInvoiceByID($id)
    {
        $q = $this->db->get_where('erp_sale_order', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getmulti_InvoiceByID($id)
    {
        $q = $this->db->get_where('sales', array('id' => $id), 1);
         if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	public function getInvoiceByRef($ref)
    {
        $q = $this->db->get_where('sales', array('reference_no' => $ref), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getInvoiceByIDs($id)
    {
       $this->db->select($this->db->dbprefix('suspended_bills').".id, date, (select name from ".$this->db->dbprefix('suspended')." where id= ".$this->db->dbprefix('suspended_bills').".suspend_id) as suspend, (select company from ".$this->db->dbprefix('companies')." where id= ".$this->db->dbprefix('suspended_bills').".biller_id) as biller, customer, 
            	case when DATE(date)+ INTERVAL (SELECT show_suspend_bar-1 from ".$this->db->dbprefix('pos_settings')." where ".$this->db->dbprefix('pos_settings').".default_biller=biller_id) DAY <= DATE(SYSDATE()) then 'completed' else 'pending' end AS sale_status,
            	total as grand_total, '' as paid, '' as balance, 'pending' as payment_status");
        $q = $this->db->get_where('suspended_bills', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
			//$this->erp->print_arrays($data);
            return $data;
        }
		return FALSE;
    }
	
	public function getInvoiceBySuspendIDs($id)
    {
       $this->db->select($this->db->dbprefix('suspended_bills').".id, date, (select name from ".$this->db->dbprefix('suspended')." where id= ".$this->db->dbprefix('suspended_bills').".suspend_id) as suspend, (select company from ".$this->db->dbprefix('companies')." where id= ".$this->db->dbprefix('suspended_bills').".biller_id) as biller, customer, 
            	case when DATE(date)+ INTERVAL (SELECT show_suspend_bar-1 from ".$this->db->dbprefix('pos_settings')." where ".$this->db->dbprefix('pos_settings').".default_biller=biller_id) DAY <= DATE(SYSDATE()) then 'completed' else 'pending' end AS sale_status,
            	total as grand_total, '' as paid, '' as balance, 'pending' as payment_status");
        $q = $this->db->get_where('suspended_bills', array('suspend_id' => $id), 1);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
			//$this->erp->print_arrays($data);
            return $data;
        }
		return FALSE;
    }
	
	public function getSuspendbyID($id){
		// $this->db->select($this->db->dbprefix('sales').".id,".$this->db->dbprefix('sales').".date, ".$this->db->dbprefix('sales').".suspend_note as suspend, (select company from ".$this->db->dbprefix('companies')." where id= ".$this->db->dbprefix('sales').".biller_id) as biller,".$this->db->dbprefix('sales').".customer, case when DATE(".$this->db->dbprefix('suspended_bills').".date)+ INTERVAL (SELECT show_suspend_bar-1 from ".$this->db->dbprefix('pos_settings')." where ".$this->db->dbprefix('pos_settings').".default_biller=".$this->db->dbprefix('suspended_bills').".biller_id) DAY <= DATE(SYSDATE()) then 'completed' else 'pending' end AS sale_status, ".$this->db->dbprefix('sales').".grand_total as grand_total, ".$this->db->dbprefix('sales').".paid as paid, (CASE WHEN ".$this->db->dbprefix('sales').".paid IS NULL THEN ".$this->db->dbprefix('sales').".grand_total ELSE ".$this->db->dbprefix('sales').".grand_total - ".$this->db->dbprefix('sales').".paid END) as balance, CASE WHEN ".$this->db->dbprefix('sales').".paid = 0 THEN 'pending' WHEN ".$this->db->dbprefix('sales').".grand_total = ".$this->db->dbprefix('sales').".paid THEN 'completed' WHEN ".$this->db->dbprefix('sales').".grand_total > ".$this->db->dbprefix('sales').".paid THEN 'partial' ELSE 'pending' END as payment_status")
		// ->join($this->db->dbprefix('sales'), $this->db->dbprefix('sales').'.suspend_note = '.$this->db->dbprefix('suspended_bills').'.suspend_name', 'right')
		// ->from('suspended_bills')
		// 
		$this->db->select('erp_sales.id AS id,erp_sales.date,erp_sale_items.product_name AS suspend,erp_sales.biller,erp_sales.customer,erp_sales.sale_status AS sale_status,erp_sales.grand_total AS grand_total,	erp_sales.paid AS paid,(CASE WHEN erp_sales.paid IS NULL THEN erp_sales.grand_total ELSE erp_sales.grand_total - erp_sales.paid END ) AS balance,	erp_sales.payment_status AS payment_status')
		->from('erp_sales')
		->join('erp_loans','erp_sales.id = erp_loans.sale_id','right')
		->join('erp_sale_items','erp_sales.id = erp_sale_items.sale_id','right')
		->where('sales.id', $id )
		->group_by('erp_sales.id')
		->order_by('erp_sales.date','desc');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getLoansByID($id)
    {
        $this->db->select('loans.*,sales.reference_no,
							sales.customer_id,sales.customer,sales.biller_id,sales.biller,
							sales.total,sales.paid
						');
        $this->db->join('sales', 'loans.sale_id=sales.id', 'INNER');
        $q = $this->db->get_where('loans', array('loans.sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
			//$this->erp->print_arrays($data);
            return $data;
        }
		return FALSE;
    }
	
	public function getExportLoans($id){
		$this->db->select($this->db->dbprefix('loans').".sale_id, sales.date, 
					 sales.reference_no as ref_no, sales.biller, sales.customer, 
					 sales.sale_status, ".$this->db->dbprefix('sales').".grand_total, 
					 IF(".$this->db->dbprefix('loans').".type <> 0,(".$this->db->dbprefix('sales').".paid + (COALESCE(".$this->db->dbprefix('sales').".other_cur_paid / ".$this->db->dbprefix('sales').".other_cur_paid_rate,0))),SUM(IF(".$this->db->dbprefix('loans').".paid_amount > 0,".$this->db->dbprefix('loans').".principle,0))) as paid,
					 IF(".$this->db->dbprefix('loans').".type <> 0,ROUND((".$this->db->dbprefix('sales').".grand_total- ((IF(".$this->db->dbprefix('loans').".type <> 0,".$this->db->dbprefix('sales').".paid, 0) + (COALESCE(".$this->db->dbprefix('sales').".other_cur_paid / ".$this->db->dbprefix('sales').".other_cur_paid_rate,0))))),3),ROUND((".$this->db->dbprefix('sales').".grand_total- SUM(IF(".$this->db->dbprefix('loans').".paid_amount > 0,".$this->db->dbprefix('loans').".principle,0)))))  as balance, 
					 IF(".$this->db->dbprefix('loans').".type = 0 AND ".$this->db->dbprefix('loans').".paid_amount < 0,'due',".$this->db->dbprefix('sales').".payment_status) as payment_status")
				 ->from('sales')
				 ->join('loans','sales.id=loans.sale_id','INNER')
				 ->where('sales.id', $id)
				 ->group_by('loans.sale_id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
				if ($q->num_rows() > 0) {
				return $q->row();
			}
        }
		return FALSE;
	}
	
	public function getSingleLoanById($id=NULL){

		$this->db->select('loans.*,sales.reference_no,
							sales.customer_id,sales.customer,sales.biller_id,sales.biller,
							sales.total,sales.paid
						');
        $this->db->join('sales', 'loans.sale_id=sales.id', 'INNER');
        $q = $this->db->get_where('loans', array('loans.id' => $id));
        if ($q->num_rows() > 0) {
           return $q->row();
        }
		return FALSE;
	}
	
	public function getMultiPayment($id=array())
	{
		$this->db->select('loans.*,
							sales.reference_no,
							sales.customer_id,
							sales.customer,
							sales.biller_id,sales.biller,
							sales.total,sales.paid');
		$this->db->from('loans');
		$this->db->join('sales', 'loans.sale_id=sales.id', 'INNER');
		$this->db->where_in('loans.id',$id);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;	
	}
	
	public function getMMultiPayment($id=NULL)
	{
		$this->db->select('loans.*,
							sales.reference_no,
							sales.customer_id,
							sales.customer,
							sales.biller_id,sales.biller,
							sales.total,sales.paid');
		$this->db->from('loans');
		$this->db->join('sales', 'loans.sale_id=sales.id', 'INNER');
		$this->db->where('loans.id',$id);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			return $q->row();
		}
		return FALSE;	
	}
	
	public function getMMMultiPayment($id=NULL)
	{
		$this->db->select('loans.*,
							sales.reference_no,
							sales.customer_id,
							sales.customer,
							sales.biller_id,sales.biller,
							sales.total,sales.paid');
		$this->db->from('loans');
		$this->db->join('sales', 'loans.sale_id=sales.id', 'INNER');
		$this->db->where('loans.id',$id);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
                $data[] = $row;
            }
			//$this->erp->print_arrays($data);
            return $data;
		}
		return FALSE;	
	}
	
	public function getLoanByID($id){

		$this->db->select('loans.*,sales.reference_no,
							sales.customer_id,sales.customer,sales.biller_id,sales.biller,
							sales.total,sales.paid
						');
        $this->db->join('sales', 'loans.sale_id=sales.id', 'INNER');
        $q = $this->db->get_where('loans', array('loans.id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
		return FALSE;
	}
	
	public function getItemsByID($id)
    {
        $this->db->select('sale_items.product_code,sale_items.product_name,sale_items.unit_price,
							sale_items.quantity
						');
        $q = $this->db->get_where('sale_items', array('sale_items.sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
			//$this->erp->print_arrays($data);
            return $data;
        }
		return FALSE;
    }
	
	public function getSaleInfoByID($id){
		$this->db->select('sales.id,sales.reference_no,sales.paid,sales.other_cur_paid,sales.other_cur_paid_rate,customer_id
						');
        $q = $this->db->get_where('sales', array('sales.id' => $id));
        if ($q->num_rows() > 0) {
            
			//$this->erp->print_arrays($data);
           return $q->row();
        }
		return FALSE;
	}
	
    public function getReturnByID($id=null)
    {
		$q = $this->db->get_where('return_sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getReturnByIDs($id=null)
    {
		$this->db->select('return_sales.* ,sale_order.reference_no as so_no,users.username as saleman,sales.reference_no as s_no')
					   ->from('return_sales')
					   ->join('sales', 'return_sales.sale_id = sales.id', 'left')
					   ->join('sale_order','sale_order.id = sales.so_id', 'left')
					   ->join('users','users.id = sales.saleman_by', 'left')
					   ->where(array('return_sales.id' => $id));
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getReturnBySID($sale_id)
    {
        $q = $this->db->get_where('return_sales', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getReturnSaleBySaleID($sale_id)
    {
        $this->db->select('sale_id');
        $q = $this->db->get_where('return_sales', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return true;
        }
        return FALSE;
    }
    
    public function getReturnItemByReturnID($return_id){
        $q = $this->db->get_where('return_sale_item', array('sale_item_id' => $return_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
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
	public function getWP2($product_id, $warehouse_id){
		  $q = $this->db->get_where('erp_warehouses_products',array('product_id'=>$product_id,'warehouse_id'=>$warehouse_id),1);
        if ($q->num_rows() > 0) {
           return $q->row();
        }
        return FALSE;
	}
    public function updateOptionQuantity($option_id, $quantity)
    {
        if ($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('product_variants', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addOptionQuantity($option_id, $quantity)
    {
        if ($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity + $quantity;
            if ($this->db->update('product_variants', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
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

    public function updateProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('warehouses_products_variants', array('quantity' => $nq), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        } else {
            $nq = 0 - $quantity;
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $nq))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addSale($data = array(), $items = array(), $payment = array(), $loans = array(),$deliver_id_muti=NULL)
    {

        if ($data['sale_status'] == 'completed') {
            $cost = $this->site->costing($items);
        }
        $deposit_customer_id    = $data['deposit_customer_id'];
        unset($data['deposit_customer_id']);
        $data['total_cost']     = 0;

        foreach ($items as $g) {
            $totalCostProducts  = $this->getTotalCostProducts($g['product_id'], $g['quantity']);
            $product_variants   = $this->site->getProductVariant($g['option_id'], $g['product_id']);
            if ($product_variants) {
                $data['total_cost'] += $totalCostProducts->total_cost * $product_variants->qty_unit;
            } else {
                $data['total_cost'] += $totalCostProducts->total_cost;
            }
        }

        if($loans) {
            $data['grand_total'] = $data['paid'];
            foreach ($loans as $loan) {
                $data['grand_total'] += $loan['payment'];
            }
        }

        if ($this->db->insert('sales', $data)) {

            $sale_id = $this->db->insert_id();
            if($deliver_id_muti){
                $this->UpdateDeliveryMulti($deliver_id_muti,$sale_id);
            }

            if ($this->site->getReference('so',$data['biller_id']) == $data['reference_no']) {
                $this->site->updateReference('so',$data['biller_id']);
            }
            $i = 0;

            foreach ($items as $item) {
                $product            = $this->site->getProductByID($item['product_id']);
                $item['unit_cost'] 	= $product->cost;
                $item['sale_id'] 	= $sale_id;
                $this->db->insert('sale_items', $item);
                $sale_item_id = $this->db->insert_id();

                if($data['so_id'] > 0 && $data['type'] = 'sale_order' && $data['type_id'] > 0) {
                    $this->db->update('sale_order', array('sale_status' => 'sale'), array('id' => $data['so_id']));
                }

                if($this->Settings->product_serial == 1){
                    $this->db->update('serial', array('serial_status'=>0), array('product_id'=>$item['product_id'], 'serial_number'=>$item['serial_no']));
                }

                if ($data['sale_status'] == 'completed' &&  $this->site->getProductByID($item['product_id'])) {
                    $item_costs = $this->site->item_costing($item);

                    foreach ($item_costs as $item_cost) {
                        $item_cost['sale_item_id'] 	= $sale_item_id;
                        $item_cost['sale_id'] 		= $sale_id;
                        if(isset($data['date'])){
                            $item_cost['date'] 		= $data['date'];
                        }
                        unset($item_cost['product_name']);
                        unset($item_cost['product_type']);
                        //$option_id = $item_cost['option_id'];

                        if(! isset($item_cost['pi_overselling'])) {
                            $this->db->insert('costing', $item_cost);
                        }
                    }

                    $purchase_items = array(
                        'product_id' 		=> $item['product_id'],
                        'product_code' 		=> $item['product_code'],
                        'product_name' 		=> $item['product_name'],
                        'option_id' 		=> $item['option_id'],
                        'quantity' 			=> (-1) * $item['quantity'],
                        'quantity_balance' 	=> (-1) * $item['quantity_balance'],
                        'warehouse_id' 		=> $item['warehouse_id'],
                        'expiry' 			=> $item['expiry'],
                        'date' 				=> date('Y-m-d', strtotime($data['date'])),
                        'status' 			=> ($data['sale_status'] == 'completed' ? 'received' : ''),
                        'transaction_type' 	=> 'SALE',
                        'transaction_id' 	=> $sale_item_id,
                        'product_type' 		=> $item['product_type'],
                        'sale_id'           => $sale_id
                    );
                    $this->site->syncSalePurchaseItems($purchase_items);


                }

            }


            if($loans){
                foreach($loans as $loan){
                    $loan['sale_id'] = $sale_id;
                    $this->db->insert('loans', $loan);
                }
            }

            if(strpos($data['paid'], '-') !== true){
                if ($data['payment_status'] == 'partial' || $data['payment_status'] == 'paid' && !empty($payment)) {
                    $payment['sale_id'] = $sale_id;
                    if ($payment['paid_by'] == 'gift_card') {
                        $this->db->update('gift_cards', array('balance' => $payment['gc_balance']), array('card_no' => $payment['cc_no']));
                        unset($payment['gc_balance']);
                        $this->db->insert('payments', $payment);
                    } else {
                        $this->db->insert('payments', $payment);
                    }
                    if ($this->site->getReference('sp') == $payment['reference_no']) {
                        $this->site->updateReference('sp');
                    }

                    if($payment['paid_by'] == 'deposit'){

                        $deposit = $this->site->getDepositByCompanyID($deposit_customer_id);
                        $deposit_balance = $deposit->deposit_amount;
                        $deposit_balance = $deposit_balance - abs($payment['amount']);
                        unset($payment['gc_balance']);

                        if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $deposit_customer_id))){
                            $this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $deposit_customer_id));
                        }
                    }
                    $this->site->syncSalePayments($sale_id);
                }
            }else{

                $sale_items = $this->site->getAllSaleItems($sale_id);
                $returns = array(
                    'date' => $data['date'],
                    'sale_id' => $sale_id,
                    'reference_no' => $this->site->getReference('re'),
                    'customer_id' => $data['customer_id'],
                    'customer' => $data['customer'],
                    'biller_id' => $data['biller_id'],
                    'biller' => $data['biller'],
                    'warehouse_id' => $data['warehouse_id'],
                    'note' => $data['note'],
                    'total' => $data['paid'],
                    'product_discount' => $data['product_discount'],
                    'order_discount_id' => $data['order_discount_id'],
                    'order_discount' => $data['order_discount'],
                    'total_discount' => $data['total_discount'],
                    'product_tax' => $data['product_tax'],
                    'order_tax_id' => $data['order_tax_id'],
                    'order_tax' => $data['order_tax'],
                    'total_tax' => $data['total_tax'],
                    'grand_total' => $data['grand_total'],
                    'created_by' => $this->session->userdata('user_id'),
                );
                if ($this->db->insert('return_sales', $returns)) {
                    $return_id = $this->db->insert_id();
                    if ($this->site->getReference('re') == $returns['reference_no']){
                        $this->site->updateReference('re');
                    }

                    foreach ($items as &$return_item){
                        unset($return_item['unit_price']);
                        $return_item['return_id'] = $return_id;
                        $sale_item_id = $this->db->insert('return_items', $return_item);

                        if ($sale_item = $this->sales_model->getSaleItemByID($sale_item_id)) {
                            //$this->db->delete('sale_items', array('id' => $item['sale_item_id']));
                            if ($sale_item->quantity == $return_item['quantity']) {
                            } else {
                                $nqty = $sale_item->quantity - $item['quantity'];
                                $tax = $sale_item->unit_price - $sale_item->net_unit_price;
                                $discount = $sale_item->item_discount / $sale_item->quantity;
                                $item_tax = $tax * $nqty;
                                $item_discount = $discount * $nqty;
                                $subtotal = $sale_item->unit_price * $nqty;
                                $this->db->update('sale_items', array('quantity' => $nqty, 'item_tax' => $item_tax, 'item_discount' => $item_discount, 'subtotal' => $subtotal), array('id' => $item['sale_item_id']));
                            }
                        }
                    }
                    //$this->calculateSaleTotals($returns['sale_id'], $return_id);

                    if ($data['payment_status'] == 'partial' || $data['payment_status'] == 'paid' && !empty($payment)) {
                        $payment['sale_id'] = $sale_id;
                        //if($payment['amount'] == $payment['amount'])
                        if ($payment['paid_by'] == 'gift_card') {
                            $this->db->update('gift_cards', array('balance' => $payment['gc_balance']), array('card_no' => $payment['cc_no']));
                            unset($payment['gc_balance']);
                            $payment['reference_no'] = $this->site->getReference('re');
                            $payment['type'] = 'returned';
                            $payment['return_id'] = $return_id;
                            $this->db->insert('payments', $payment);
                        } else {
                            $payment['reference_no'] = $this->site->getReference('re');
                            $payment['type'] = 'returned';
                            $payment['return_id'] = $return_id;
                            $this->db->insert('payments', $payment);
                        }
                        if ($this->site->getReference('sp') == $payment['reference_no']) {
                            $this->site->updateReference('sp');
                        }
                        //$this->site->syncSalePayments($sale_id);

                        $sale = $this->site->getSaleByID($sale_id);
                        $payments = $this->site->getSalePayments($sale_id);
                        $paid = 0;
                        foreach ($payments as $payment) {
                            if ($payment->type == 'returned') {
                                $paid -= $payment->amount;
                                //$paid -= $sale->paid;
                            } else {
                                $paid += $payment->amount;
                                //$paid += $sale->paid;
                            }
                        }

                        $payment_status = $paid <= 0 ? 'pending' : $sale->payment_status;
                        if ($paid <= 0 && $sale->due_date <= date('Y-m-d')) {
                            if ($payment->type == 'returned') {
                                $payment_status = 'returned';
                                $payment_term = 0;
                                $paid = -1 * abs($paid);
                            }else{
                                $payment_status = 'due';
                            }
                        } elseif ($this->erp->formatDecimal($sale->grand_total) > $this->erp->formatDecimal($paid) && $paid > 0) {
                            $payment_status = 'partial';
                        } elseif ($this->erp->formatDecimal($sale->grand_total) <= $this->erp->formatDecimal($paid)) {
                            if ($payment->type == 'returned') {
                                $payment_status = 'returned';
                                $paid = -1 * abs($paid);
                            }else{
                                $payment_status = 'paid';
                            }
                            $payment_term = 0;
                        }

                        if($payment['paid_by'] == 'deposit'){
                            $deposit = $this->site->getDepositByCompanyID($data['customer_id']);
                            $deposit_balance = $deposit->deposit_amount;
                            $deposit_balance = $deposit_balance + abs($payment['amount']);
                            if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $deposit_customer_id))){
                                $this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $deposit_customer_id));
                            }
                        }
                        $this->calculateSaleTotals($sale_id, $return_id, NULL, $payment_status);
                    }

                }
            }
            $this->erp->update_award_points($data['grand_total'], $data['customer_id'], $data['created_by'], NULL ,$data['saleman_by']);
            return $sale_id;
        }
        return false;
    }

    public function saleEdit($id, $qty, $sale_id, $ware){
		$Proqty = $this->getProductQty($id);
		$WareQty = $this->getWarehouseQty($id, $ware);
		$payprice = $this->getPaymentBySaleID($sale_id);
		if($Proqty){
			$quantity = $Proqty->quantity + $qty;
			$price = $payprice->amount - $Proqty->price;
			$this->db->update('products', array('quantity' => $quantity), array('id' => $id));
			$this->db->update('payments', array('amount'=>$price), array('sale_id'=>$sale_id));
		}
		if($WareQty){
			$warehouse = $WareQty->quantity + $qty;
			$this->db->update('warehouses_products', array('quantity' => $warehouse), array('product_id' => $id, 'warehouse_id' => $ware));
		}
		$this->db->delete('sale_items', array('sale_id' => $sale_id, 'product_id' => $id));
		$this->db->delete('costing', array('sale_id' => $sale_id, 'product_id' => $id));
		return false;
	}
	
	public function getProductQty($id){
		$this->db->select('quantity, price');
        $q = $this->db->get_where('products', array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getWarehouseQty($id, $warehouse){
		$this->db->select('quantity');
        $q = $this->db->get_where('warehouses_products', array('product_id' => $id, 'warehouse_id'=>$warehouse));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function addSaleImport($data = array(), $items = array())
	{
		$costing = $this->site->costing($items);
		foreach($items as $g) {
			$totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity']);
			$data['total_cost'] += $totalCostProducts->total_cost;
		}
		if ($this->db->insert('sales', $data)) {
			$sale_id = $this->db->insert_id();
			if ($this->site->getReference('so') == $data['reference_no']) {
				$this->site->updateReference('so');
			}
			$i = 0;
			foreach ($items as $item) {
				$item['sale_id'] = $sale_id;
				$this->db->insert('sale_items', $item);
				$sale_item_id = $this->db->insert_id();
				if ($data['sale_status'] == 'completed' && $this->site->getProductByID($item['product_id'])) {
					$item_costs = $this->site->item_costing($item);
					foreach ($item_costs as $item_cost) {
						$item_cost['sale_item_id'] = $sale_item_id;
						$item_cost['sale_id'] = $sale_id;
						if(isset($data['date'])){
							$item_cost['date'] = $data['date'];
						}
						//$option_id = $item_cost['option_id'];
						if(!isset($item_cost['pi_overselling'])) {
							$this->db->insert('costing', $item_cost);
						}
					}
				}
				foreach($costing[$i] as $costs){
					$tran_type = array('transaction_type' => 'SALE', 'transaction_id' => $sale_item_id);
					$cost[][]  = array_merge($costs, $tran_type);
				}
				$i++;
			}
			
			if ($data['sale_status'] == 'completed') {
				$this->site->syncPurchaseItems($cost);
			}
			$this->site->syncQuantity($sale_id);
			return $sale_id;
		}
		return false;
	}
	
	public function addSaleItemImport($items = array(), $old_ref)
	{
		$sale = $this->getSaleItemByRef($old_ref);
		$cost = $this->site->costing($items);
		foreach($items as $g){
			$totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity']);
			$sale->total_cost += $totalCostProducts->total_cost;
		}

		$sale_id = $sale->sale_id;
		if ($this->site->getReference('so') == $sale->reference_no) {
			$this->site->updateReference('so');
		}
		foreach ($items as $item) {
			if($item['product_id'] != $sale->product_id){
				$item['sale_id'] = $sale_id;
				$this->db->insert('sale_items', $item);
				$sale_item_id = $this->db->insert_id();
				
				$sale_update = array(
					'total' => $item['subtotal'] + $sale->total,
					'grand_total' => $item['subtotal'] + $sale->grand_total
				);
				$this->db->update('sales', $sale_update, array('id' => $item['sale_id']));
				
				/* 
				if ($sale->sale_status == 'completed' && $this->site->getProductByID($item['product_id'])) {

					$item_costs = $this->site->item_costing($item);
					foreach ($item_costs as $item_cost) {
						$item_cost['sale_item_id'] = $sale_item_id;
						$item_cost['sale_id'] = $sale_id;
						if(isset($sale->date)){
							$item_cost['date'] = $sale->date;
						}
						//$option_id = $item_cost['option_id'];

						if(! isset($item_cost['pi_overselling'])) {
							$this->db->insert('costing', $item_cost);
						}
					}
				}
				*/
			}
		}
		
		/*
		if ($sale->sale_status == 'completed') {
			$this->site->syncPurchaseItems($cost);
		}
		*/
		
	   $this->site->syncQuantity($sale_id);
		//$this->erp->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, NULL ,$sale->saleman_by);
		return false;
	}
	
	public function getSaleItemByRef($sale_ref)
    {
        $this->db->select('sale_items.id AS sale_item_id, sale_items.product_id ,sales.id AS sale_id, sales.reference_no AS sale_reference, sales.total, sales.grand_total');
        $this->db->join('sale_items', 'sale_items.sale_id = sales.id', 'inner');
        $q = $this->db->get_where('sales', array('sales.reference_no' => $sale_ref));
        
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }



	public function getSale_Del_ByID($id=null, $wh=null)
	{
		$ltrans = "(SELECT
						erp_transfer_items.product_id,
						erp_transfer_items.transfer_id,
						
						IFNULL(
							SUM(
								erp_transfer_items.quantity * erp_product_variants.qty_unit
							),
							erp_transfer_items.quantity
						) AS qty
					FROM
						erp_transfer_items
					LEFT JOIN erp_product_variants ON erp_product_variants.id = erp_transfer_items.option_id
					GROUP BY
						
						erp_transfer_items.transfer_id
					) AS erp_tran";

		
		$this->db->select("transfers.id as id,transfers.from_warehouse_id,erp_transfers.to_warehouse_id,transfers.date, transfer_no, from_warehouse_name as fname, from_warehouse_code as fcode, to_warehouse_name as tname,to_warehouse_code as tcode, erp_tran.qty, transfers.status, transfers.created_by, transfers.shipping, transfers.total_tax, transfers.note, transfers.attachment, transfers.total, transfers.grand_total, transfers.from_warehouse_name")
			->join('transfer_items', 'transfers.id = transfer_items.transfer_id', 'left')
			->join($ltrans,'erp_tran.product_id = transfer_items.product_id AND erp_tran.transfer_id = transfer_items.transfer_id','left')
			->group_by('transfers.transfer_no');
			if($wh){
				$this->db->where_in('erp_transfers.from_warehouse_id',$wh);
			}

		// $this->db->select($this->db->dbprefix('transfers') . '.id, ' . $this->db->dbprefix('transfers') . '.date, ' . $this->db->dbprefix('transfers') . '.transfer_no, ' . $this->db->dbprefix('transfers') . '.from_warehouse_name as fname, ' . $this->db->dbprefix('transfers') . '.from_warehouse_code as fcode, '.$this->db->dbprefix('transfers') . '.to_warehouse_name as tname,'.$this->db->dbprefix('transfers') . '.to_warehouse_code as tcode,'.$this->db->dbprefix('transfer_items') . '.quantity, '.$this->db->dbprefix('transfers') . '.status, from_warehouse_id, to_warehouse_id')  
			// ->join('transfer_items', 'transfers.id=transfer_items.transfer_id', 'left');
			$q = $this->db->get_where('transfers', array('transfers.id' => $id), 1);
		if ($q->num_rows() > 0) {
			return $q->row();
		}

		return FALSE;
	}


	public function getAllSaleDelItems($transfer_id, $status)
	{
		if ($status == 'completed') {
			$this->db->select('transfer_items.*, product_variants.name as variant, products.unit, IFNULL(SUM(erp_transfer_items.quantity * erp_product_variants.qty_unit),erp_transfer_items.quantity) as TQty,units.name')
				->from('transfer_items')
				->join('products', 'products.id = transfer_items.product_id', 'left')
				->join('product_variants', 'product_variants.id = transfer_items.option_id', 'left')
				->join('units', 'products.unit = units.id', 'left')
				->group_by('transfer_items.id')
				->where('transfer_id', $transfer_id);
		} else {
			$this->db->select('transfer_items.*, product_variants.name as variant, products.unit, IFNULL(SUM(erp_transfer_items.quantity * erp_product_variants.qty_unit),erp_transfer_items.quantity) as TQty')
				->from('transfer_items')
				->join('products', 'products.id = transfer_items.product_id', 'left')
				->join('product_variants', 'product_variants.id = transfer_items.option_id', 'left')
				->group_by('transfer_items.id')
				->where('transfer_id', $transfer_id);
		}
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}
	
	public function resetTransferActionsSync($id)
	{
		$osale = $this->sales_model->getSale_Del_ByID($id);
		$ostatus = $osale->status;
		
		if ($ostatus == 'sent' ||$ostatus == 'completed') {
			$this->db->delete('purchase_items', array('transfer_id' => $id));
		}

		$oitems = $this->sales_model->getAllSaleDelItems($id, $ostatus);
		if($oitems) {
			foreach ($oitems as $item) {
				$this->site->syncQuantitys(NULL, NULL, NULL, $item->product_id);
			}
			return true;
		}
		
		return FALSE;
	}
	
    public function updateSale($id, $data, $items = array(), $sale_data)
    {

		if ($data['sale_status'] == 'completed') {
			$this->site->costing($items);
		}

		$deposit_customer_id = $data['deposit_customer_id'];
		unset($data['deposit_customer_id']);
        $this->resetSaleActions($id);

        foreach($items as $g){
			$totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity']);
			$product_variants = $this->site->getProductVariant($g['option_id'], $g['product_id']);
			if($product_variants) {
				$data['total_cost'] += $totalCostProducts->total_cost * $product_variants->qty_unit;
			}else {
				$data['total_cost'] += $totalCostProducts->total_cost;
			}
		}

        if ($this->db->update('sales', $data, array('id' => $id))) {
			foreach($sale_data as $sa){
				$this->db->delete("inventory_valuation_details",array("field_id"=>$sa['slaeid'], "type"=>"SALE"));
				$this->db->delete("purchase_items",array("transaction_id"=>$sa['slaeid'], "transaction_type"=>"SALE"));
			}
			//=============== Delete Purchase Item =================//
			$sale_items = $this->site->getSaleItemBySaleID($id);
			foreach($sale_items as $sItem){
				$purchase_item = $this->site->getPurchaseItemBySaleItem($sItem->id, 'SALE');
				if($purchase_item){
					//$this->db->delete('purchase_items', array('id' => $purchase_item->id));
					
				}
			}

			//======================== End ========================//
			$this->db->delete('sale_items', array('sale_id' => $id));
			
			$i = 0;
            foreach ($items as $item) {
				$product = $this->site->getProductByID($item['product_id']);
				$item['unit_cost'] = $product->cost;
                $item['sale_id'] = $id;
				$old_sqty = $item['old_sqty'];
				unset($item['old_sqty']);

                $this->db->insert('sale_items', $item);
                $sale_item_id = $this->db->insert_id();

				$item['old_sqty'] = $old_sqty;
				
                if ($data['sale_status'] == 'completed' && $this->site->getProductByID($item['product_id'])) {
                    $item_costs = $this->site->item_costing($item);
					
                    foreach ($item_costs as $item_cost) {
                        $item_cost['sale_item_id'] = $sale_item_id;
                        $item_cost['sale_id'] = $id;

						unset($item_cost['product_name']);
						unset($item_cost['product_type']);
						
                        if(! isset($item_cost['pi_overselling'])) {
                            $this->db->insert('costing', $item_cost);
                        }
                    }

                    $purchase_items = array(
                        'product_id' 		=> $item['product_id'],
                        'product_code' 		=> $item['product_code'],
                        'product_name' 		=> $item['product_name'],
                        'option_id' 		=> $item['option_id'],
                        'quantity' 			=> (-1) * $item['quantity'],
                        'quantity_balance' 	=> (-1) * $item['quantity_balance'],
                        'warehouse_id' 		=> $item['warehouse_id'],
                        'expiry' 			=> $item['expiry'],
                        'date' 				=> date('Y-m-d', strtotime($data['date'])),
                        'status' 			=> ($data['sale_status'] == 'completed'?'received':''),
                        'transaction_type' 	=> 'SALE',
                        'transaction_id' 	=> $sale_item_id,
                        'product_type' 		=> $item['product_type'],
                        'sale_id'           => $id
                    );

                    $this->site->syncSalePurchaseItems($purchase_items);
                }
				$i++;
            }
			
			if($data['payment_status'] == 'paid' || $data['payment_status'] == 'partial'){
				$this->db->update('payments', array('amount' => $data['paid']), array('sale_id' => $id));
				$total_balance = $data['grand_total'] - $data['paid'];
				if($total_balance != 0){
					$this->db->update('sales', array('payment_status' => 'partial'), array('id' => $id));
				}else{
					$this->db->update('sales', array('payment_status' => 'paid'), array('id' => $id));
				}
				
			}
            if ($data['sale_status'] == 'completed') {
				$this->site->syncSalePayments($id);
            }

            $this->erp->update_award_points($data['grand_total'], $data['customer_id'], $data['created_by'], null, $data['saleman_by']);
            return true;
        }
        return false;
    }
	
	public function updateSaleOrder($id, $data, $items = array())
    {
        if ($this->db->update('erp_sale_order', $data, array('id' => $id)) && $this->db->delete('erp_sale_order_items', array('sale_order_id' => $id))) {
            
			foreach ($items as $item) {
                $item['sale_order_id'] = $id;
                $this->db->insert('erp_sale_order_items', $item);
            }
            return true;
        }
        return false;
    }
	
	
    public function deleteSale($id)
    {
        $sale_items = $this->resetSaleActions($id);
        if ($this->db->delete('payments', array('sale_id' => $id)) &&
        $this->db->delete('sale_items', array('sale_id' => $id)) &&
        $this->db->delete('sales', array('id' => $id))) {
            if ($return = $this->getReturnBySID($id)) {
                $this->deleteReturn($return->id);
            }
            $this->site->syncQuantity(NULL, NULL, $sale_items);
            return true;
        }
        return FALSE;
    }
	
	public function deleteSuspend($id)
    {
        if ($this->db->delete('suspended_bills', array('id' => $id)) &&
        $this->db->delete('suspended_items', array('suspend_id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function resetSaleActions($id)
    {
        $sale = $this->getInvoiceByID($id);
        $items = $this->getAllInvoiceItems($id);
        foreach ($items as $item) {

            if ($sale->sale_status == 'completed') {
                if ($costings = $this->getCostingLines($item->id, $item->product_id)) {
                    $quantity = $item->quantity;
                    foreach ($costings as $cost) {
                        if ($cost->quantity >= $quantity) {
                            $qty = $cost->quantity - $quantity;
                            $bln = $cost->quantity_balance ? $cost->quantity_balance + $quantity : $quantity;
                            $this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
                            $quantity = 0;
                        } elseif ($cost->quantity < $quantity) {
                            $qty = $quantity - $cost->quantity;
                            $this->db->delete('costing', array('id' => $cost->id));
                            $quantity -= $qty;
                        }
                        if ($quantity == 0) {
                            break;
                        }
                    }
                }
				/*
					if ($item->product_type == 'combo') {
						$combo_items = $this->site->getProductComboItems($item->product_id, $item->warehouse_id);
						foreach ($combo_items as $combo_item) {
							if($combo_item->type == 'standard') {
								$qty = ($item->quantity*$combo_item->qty);
								$this->updatePurchaseItem(NULL, $qty, NULL, $combo_item->id, $item->warehouse_id);
							}
						}
					} else {
						$option_id = isset($item->option_id) && !empty($item->option_id) ? $item->option_id : NULL;
						$this->updatePurchaseItem(NULL, $item->quantity, $item->id, $item->product_id, $item->warehouse_id, $option_id);
					}
				*/
            }

        }
        $this->erp->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, TRUE);
        return $items;
    }

    public function deleteReturn($id)
    {
        if ($this->db->delete('return_items', array('return_id' => $id)) && $this->db->delete('return_sales', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function updatePurchaseItem($id, $qty, $sale_item_id, $product_id = NULL, $warehouse_id = NULL, $option_id = NULL, $return_item_id = NULL)
    {
        if ($id) {
            if($pi = $this->getPurchaseItemByID($id)) {
                $pr = $this->site->getProductByID($pi->product_id);
                if ($pr->type == 'combo') {
                    $combo_items = $this->site->getProductComboItems($pr->id, $pi->warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        if($combo_item->type == 'standard') {
                            $cpi = $this->site->getPurchasedItem(array('product_id' => $combo_item->id, 'warehouse_id' => $pi->warehouse_id, 'option_id' => NULL));
                            $bln = $pi->quantity_balance + ($qty * $combo_item->qty);
							
							$combo_data = array(
								'product_id' 		=> $combo_items->id,
								'product_code' 		=> $combo_items->code,
								'product_name' 		=> $combo_items->name,
								'product_type' 		=> $combo_items->type,
								'net_unit_cost' 	=> 0,
								'quantity' 			=> 0,
								'item_tax' 			=> 0,
								'status' 			=>'received',
								'warehouse_id' 		=> $warehouse_id,
								'subtotal' 			=> 0,
								'date' 				=> date('Y-m-d'),
								'transaction_type'	=> 'SALE RETURN',
								'transaction_id'	=> $return_item_id,
								'quantity_balance' 	=> abs($qty * $combo_item->qty)
							);
							
							$this->db->insert('purchase_items', $combo_data);
							
                        }
                    }
                } else {
					
                    $bln 		 = $pi->quantity_balance + $qty;
					$new_arr_data = array(
						'product_id' 		=> $pr->product_id,
						'product_code' 		=> $pr->product_code,
						'product_name' 		=> $pr->product_name,
						'net_unit_cost' 	=> $pr->cost?$pr->cost:0,
						'quantity' 			=> 0,
						'item_tax' 			=> 0,
						'warehouse_id' 		=> $warehouse_id,
						'status' 			=>'received',
						'subtotal' 			=> 0,
						'transaction_type'	=> 'SALE RETURN',
						'transaction_id'	=> $return_item_id,
						'date' 				=> date('Y-m-d'),
						'quantity_balance' 	=> abs($qty)
					);
					
					$this->db->insert('purchase_items', $new_arr_data);
                }
            }
        } else {
			$expiry_date = $this->getReturnItems($return_item_id);
            if ($sale_item = $this->getSaleItemByID($sale_item_id)) {				
                $option_id = isset($sale_item->option_id) && !empty($sale_item->option_id) ? $sale_item->option_id : NULL;
				if($option_id){
					$clause = array('product_id' => $sale_item->product_id, 'warehouse_id' => $sale_item->warehouse_id, 'option_id' => $option_id);
				}else{
					$clause = array('product_id' => $sale_item->product_id, 'warehouse_id' => $sale_item->warehouse_id);
				}
				
                if ($pi = $this->site->getPurchasedItem($clause)) {
                    $quantity_balance = $pi->quantity_balance+$qty;
					
					$qty_balance = abs($qty);
					if($option_id){
						$option = $this->site->getProductVariantOptionIDPID($option_id, $sale_item->product_id);
						$qty_balance = $qty_balance * $option->qty_unit;
					}
					
					$new_arr_data = array(
						'product_id' 		=> $sale_item->product_id,
						'product_code' 		=> $sale_item->product_code,
						'product_name' 		=> $sale_item->product_name,
						'product_type' 		=> $sale_item->product_type,
						'option_id' 		=> $sale_item->option_id,
						'net_unit_cost' 	=> 0,
						'quantity' 			=> 0,
						'item_tax' 			=> 0,
						'warehouse_id' 		=> $sale_item->warehouse_id,
						'subtotal' 			=> 0,
						'date' 				=> date('Y-m-d'),
						'expiry' 			=> $expiry_date->expiry ? $expiry_date->expiry : NULL,
						'status' 			=>'received',
						'transaction_type'	=> 'SALE RETURN',
						'transaction_id'	=> $return_item_id,
						'quantity_balance' 	=> $qty_balance
					);
					
					$this->db->insert('purchase_items', $new_arr_data);

                } else {
					
					$qty_balance = $qty;
					if($option_id){
						$option = $this->site->getProductVariantOptionIDPID($option_id, $sale_item->product_id);
						$qty_balance = $qty_balance * $option->qty_unit;
					}
					
                    $clause['purchase_id'] 		= NULL;
                    $clause['transfer_id'] 		= NULL;
                    $clause['quantity'] 		= 0;
					$clause['expiry'] 			= $expiry_date->expiry ? $expiry_date->expiry : NULL;
                    $clause['quantity_balance'] = $qty_balance;
                    $clause['transaction_type'] = 'SALE RETURN';
                    $clause['transaction_id'] 	= $return_item_id;
					
                    $this->db->insert('purchase_items', $clause);
                }
            }
            if (! $sale_item && $product_id) {
                $pr = $this->site->getProductByIDWh($product_id,$warehouse_id);
                $clause = array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'option_id' => $option_id);
                if ($pr->type == 'standard') {
                    if ($pi = $this->site->getPurchasedItem($clause)) {
                        $quantity_balance = $pi->quantity_balance + $qty;
						$qty_balance = abs($qty);
						$p_var = $this->site->getProductSmallVariant($product_id);
						
						$new_arr_data = array(
							'product_id' 		=> $pr->id,
							'product_code' 		=> $pr->code,
							'product_name' 		=> $pr->name,
							'product_type' 		=> $pr->type,
							'option_id' 		=> $p_var ? $p_var->id : '',
							'net_unit_cost' 	=> $pr->cost ? $pr->cost : 0,
							'quantity' 			=> 0,
							'item_tax' 			=> 0,
							'warehouse_id' 		=> $warehouse_id,
							'subtotal' 			=> 0,
							'date' 				=> date('Y-m-d'),
							'expiry' 			=> $expiry_date->expiry ? $expiry_date->expiry : NULL,
							'status' 			=>'received',
							'transaction_type'	=> 'SALE RETURN',
							'transaction_id'	=> $return_item_id,
							'quantity_balance' 	=> $qty_balance
						);
						
						$this->db->insert('purchase_items', $new_arr_data);

                    } else {
						
						$qty_balance = $qty;
						if($option_id){
							$option = $this->site->getProductVariantOptionIDPID($option_id, $product_id);
							$qty_balance = $qty_balance * $option->qty_unit;
						}
						
                        $clause['purchase_id'] 		= NULL;
                        $clause['transfer_id'] 		= NULL;
                        $clause['quantity'] 		= 0;
                        $clause['expiry'] 			= $expiry_date->expiry ? $expiry_date->expiry : NULL;
                        $clause['quantity_balance'] = $qty_balance;
						$clause['transaction_type'] = 'SALE RETURN';
						$clause['transaction_id'] 	= $return_item_id;						
                        $this->db->insert('purchase_items', $clause);
                    }
                } elseif ($pr->type == 'combo') {
                    $combo_items = $this->site->getProductComboItems($pr->id, $warehouse_id);
                    foreach ($combo_items as $combo_item) {
                        $clause = array('product_id' => $combo_item->id, 'warehouse_id' => $warehouse_id, 'option_id' => NULL);
                        if($combo_item->type == 'standard') {
                            if ($pi = $this->site->getPurchasedItem($clause)) {
                                $quantity_balance = $pi->quantity_balance+($qty*$combo_item->qty);
								
								$combo_data = array(
									'product_id' 		=> $combo_items->id,
									'product_code' 		=> $combo_items->code,
									'product_name' 		=> $combo_items->name,
									'product_type' 		=> $combo_items->type,
									'option_id' 		=> $pi->option_id,
									'net_unit_cost' 	=> 0,
									'quantity' 			=> 0,
									'item_tax' 			=> 0,
									'warehouse_id' 		=> $warehouse_id,
									'subtotal' 			=> 0,
									'date' 				=> date('Y-m-d'),
									'status' 			=>'received',
									'transaction_type'	=> 'SALE RETURN',
									'transaction_id'	=> $return_item_id,
									'quantity_balance' 	=> abs($qty*$combo_item->qty)
								);
								
								$this->db->insert('purchase_items', $combo_data);
	
                            } else {
								
                                $clause['transfer_id'] 		= NULL;
                                $clause['purchase_id'] 		= NULL;
                                $clause['quantity'] 		= 0;
                                $clause['quantity_balance'] = $qty;
								$clause['transaction_type'] = 'SALE RETURN';
								$clause['transaction_id'] 	= $return_item_id;
                                $this->db->insert('purchase_items', $clause);
                            }
                        }
                    }
                }
            }
        }
    }
	
	public function getTotalCostProducts($product_id, $quantity){
		$this->db->select("SUM(cost* CASE WHEN $quantity <> 0 THEN $quantity ELSE 0 END ) AS total_cost ");
		$q = $this->db->get_where('products', array('id' => $product_id));
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function getPurchaseItemByID($id)
    {
        $q = $this->db->get_where('purchase_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function returnSale($data = array(), $items = array(), $payment = array())
    {
		
		$sale_items = $this->site->getAllSaleItems($data['sale_id']);
		
		foreach($items as $g){
			$product_variants = $this->site->getProductVariant($g['option_id'], $g['product_id']);
			if($product_variants) {
				$data['total_cost'] += ($g['unit_cost'] * ($g['quantity'] * $product_variants->qty_unit));
			}else {
				$data['total_cost'] += ($g['unit_cost'] * $g['quantity']);
			}
		}
		
        if ($this->db->insert('return_sales', $data)) {
            $return_id = $this->db->insert_id();
			if ($this->site->getReference('re',$data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('re',$data['biller_id']);
			}
            
			$total_amount_to_dep = 0;
            foreach ($items as $item) {
				$item['sale_id'] = $data['sale_id'];
                $item['return_id'] = $return_id;
                $this->db->insert('return_items', $item);
				$return_item_id = $this->db->insert_id();

                if ($sale_item = $this->getSaleItemByID($item['sale_item_id'])) {
                    if ($sale_item->quantity == $item['quantity']) {
                        
                    } else {
                        $nqty = $sale_item->quantity - $item['quantity'];
                        $tax = $sale_item->unit_price - $sale_item->net_unit_price;
                        $discount = $sale_item->item_discount / $sale_item->quantity;
                        $item_tax = $tax * $nqty;
                        $item_discount = $discount * $nqty;
                        $subtotal = $sale_item->unit_price * $nqty;
                    }
                }
				
				if ($item['product_type'] == 'combo') {
					$combo_items = $this->site->getProductComboItems($item['product_id'], $item['warehouse_id']);
					foreach ($combo_items as $combo_item) {
						$p_var = $this->site->getProductSmallVariant($combo_item->id);
						$option = $p_var->id ? $p_var->id : 0;
						if ($costings = $this->getCostingLines($item['sale_item_id'], $combo_item->id)) {
							$quantity = $item['quantity']*$combo_item->qty;
							foreach ($costings as $cost) {
								if ($cost->quantity >= $quantity) {
									$qty = $cost->quantity - $quantity;
									$bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
									$this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
									$quantity = 0;
								} elseif ($cost->quantity < $quantity) {
									$qty = $quantity - $cost->quantity;
									$this->db->delete('costing', array('id' => $cost->id));
									$quantity = $qty;
								}
							}
						}
						$this->updatePurchaseItem(NULL,($item['quantity']*$combo_item->qty), NULL, $combo_item->id, $item['warehouse_id'], $option, $return_item_id);
					}
				} else {
					
					if ($costings = $this->getCostingLines($item['sale_item_id'], $item['product_id'])) {
						$quantity = $item['quantity'];
						foreach ($costings as $cost) {
							if($cost->option_id != 0 || $cost->option_id != NULL){
								$quantity = $quantity * $cost->qty_unit;
								if (($cost->quantity* $cost->qty_unit) > $quantity) {
									$qty = ($cost->quantity * $cost->qty_unit) - $quantity;
									$bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
									$this->db->set('quantity',$qty/$cost->qty_unit);
									$this->db->update('costing', array('quantity_balance' => $bln), array('id' => $cost->id));
									$quantity = 0;
								} elseif (($cost->quantity*$cost->qty_unit) <= $quantity) {
									$qty = $quantity - ($cost->quantity*$cost->qty_unit);
									$this->db->delete('costing', array('id' => $cost->id));
									$quantity = $qty;
								}
							}else{
								if ($cost->quantity >= $quantity) {
									$qty = $cost->quantity - $quantity;
									$bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
									$this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
									$quantity = 0;
								} elseif ($cost->quantity < $quantity) {
									$qty = $quantity - $cost->quantity;
									$this->db->delete('costing', array('id' => $cost->id));
									$quantity = $qty;
								}
							}
						}
					}
					
					$this->updatePurchaseItem(NULL, $item['quantity']*($cost->qty_unit?$cost->qty_unit:1), $item['sale_item_id'], $item['product_id'], $item['warehouse_id'], $item['option_id'], $return_item_id);
				}
				
				$total_amount_to_dep += $item['subtotal'];
                $this->site->syncQuantity(NULL, NULL, NULL, $item['product_id']);
            }
            if (!empty($payment)) {
                $payment['sale_id']     = $data['sale_id'];
                $payment['return_id']   = $return_id;
                $payment['pos_paid']    = $payment['amount'];
                $this->db->insert('payments', $payment);
                $payment_id = $this->db->insert_id();
                if ($this->site->getReference('pp', $payment['biller_id']) == $payment['reference_no']) {
                    $this->site->updateReference('pp', $payment['biller_id']);
                }

                if ($this->site->getReference('sp', $payment['biller_id']) == $payment['reference_no']) {
                    $this->site->updateReference('sp', $payment['biller_id']);
                }

                if ($payment['paid_by'] == 'deposit') {
                    $deposit = array(
                        'date'          => $payment['date'],
                        'reference'     => $payment['reference_no'],
                        'amount'        => $payment['amount'],
                        'paid_by'       => $payment['paid_by'],
                        'created_by'    => $this->session->userdata('user_id'),
                        'status'        => 'deposit',
                        'biller_id'     => $payment['biller_id'],
                        'payment_id'    => $payment_id,
                        'company_id'    => $data['customer_id'],
                        'note'          => $data['customer']
                    );
                    $this->db->insert('deposits', $deposit);
                }

            }
			$this->calculateSaleTotalsReturn($data['sale_id'], $return_id, $data['surcharge']);
            $this->site->syncQuantity(NULL, NULL, $sale_items);
            $this->erp->update_award_points($data['grand_total'], $data['customer_id'], $data['created_by'], 'return_sale', $data['saleman_by']);
            return $return_id;
        }
        return false;
    }
	
	public function updateReturn($id, $data = array(), $items = array(), $payment = array())
    {
		
		//$this->erp->print_arrays($items);
        $sale_items = $this->site->getAllSaleItems($data['sale_id']);
		$return_items = $this->getAllReturnItems($id);
		$payment_id = $data['payment_id'];
		unset($data['payment_id']);
		
		foreach($items as $g){
			$product_variants = $this->site->getProductVariant($g['option_id'], $g['product_id']);
			if($product_variants) {
				$data['total_cost'] += ($g['unit_cost'] * ($g['quantity'] * $product_variants->qty_unit));
			}else {
				$data['total_cost'] += ($g['unit_cost'] * $g['quantity']);
			}
		}
		
        if ($this->db->update('return_sales', $data, array('id' => $id)) && $this->db->delete('return_items', array('return_id' => $id))) {
            $return_id = $id;
			$total_amount_to_dep = 0;
            foreach ($items as $item) {
				
				if($return_items) {
					foreach($return_items as $ritem) {
						$this->db->delete('purchase_items', array('transaction_id' => $ritem->id, 'transaction_type' => 'SALE RETURN'));
					}
				}
				
				$item['sale_id'] = $data['sale_id'];
                $item['return_id'] = $return_id;
                $this->db->insert('return_items', $item);
				$return_item_id = $this->db->insert_id();
				
				if ($item['product_type'] == 'combo') {
					$combo_items = $this->site->getProductComboItems($item['product_id'], $item['warehouse_id']);
					foreach ($combo_items as $combo_item) {
						if ($costings = $this->getCostingLines($item['sale_item_id'], $combo_item->id)) {
							$quantity = $item['quantity']*$combo_item->qty;
							foreach ($costings as $cost) {
								if ($cost->quantity >= $quantity) {
									$qty = $cost->quantity - $quantity;
									$bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
									$this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
									$quantity = 0;
								} elseif ($cost->quantity < $quantity) {
									$qty = $quantity - $cost->quantity;
									$this->db->delete('costing', array('id' => $cost->id));
									$quantity = $qty;
								}
							}
						}
						$this->updatePurchaseItem(NULL,($item['quantity']*$combo_item->qty), NULL, $combo_item->id, $item['warehouse_id'], NULL, $return_item_id);
					}
				} else {
					
					if ($costings = $this->getCostingLines($item['sale_item_id'], $item['product_id'])) {
						$quantity = $item['quantity'];
						foreach ($costings as $cost) {
							if($cost->option_id != 0 || $cost->option_id != NULL){
								$quantity = $quantity * $cost->qty_unit;
								if (($cost->quantity* $cost->qty_unit) > $quantity) {
									$qty = ($cost->quantity * $cost->qty_unit) - $quantity;
									$bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
									$this->db->set('quantity',$qty/$cost->qty_unit);
									$this->db->update('costing', array('quantity_balance' => $bln), array('id' => $cost->id));
									$quantity = 0;
								} elseif (($cost->quantity*$cost->qty_unit) <= $quantity) {
									$qty = $quantity - ($cost->quantity*$cost->qty_unit);
									$this->db->delete('costing', array('id' => $cost->id));
									$quantity = $qty;
								}
							}else{
								if ($cost->quantity >= $quantity) {
									$qty = $cost->quantity - $quantity;
									$bln = $cost->quantity_balance && $cost->quantity_balance >= $quantity ? $cost->quantity_balance - $quantity : 0;
									$this->db->update('costing', array('quantity' => $qty, 'quantity_balance' => $bln), array('id' => $cost->id));
									$quantity = 0;
								} elseif ($cost->quantity < $quantity) {
									$qty = $quantity - $cost->quantity;
									$this->db->delete('costing', array('id' => $cost->id));
									$quantity = $qty;
								}
							}
						}
					}
					
					$this->updatePurchaseItem(NULL, $item['quantity']*($cost->qty_unit?$cost->qty_unit:1), $item['sale_item_id'], $item['product_id'], $item['warehouse_id'], $item['option_id'], $return_item_id);
				}
				
				$total_amount_to_dep += $item['subtotal'];
                $this->site->syncQuantity(NULL, NULL, NULL, $item['product_id']);
            }
            if (!empty($payment)) {
                $payment['sale_id'] = $data['sale_id'];
                $payment['return_id'] = $return_id;
                $payment['pos_paid'] = $payment['amount'];
				if($payment_id) {
					$this->db->update('payments', $payment, array('id' => $payment_id));
				}else {
					$this->db->insert('payments', $payment);
					if ($this->site->getReference('pp', $payment['biller_id']) == $payment['reference_no']) {
						$this->site->updateReference('pp', $payment['biller_id']);
					}			
				}
            }
			$this->calculateSaleTotalsReturn($data['sale_id'], $return_id, $data['surcharge']);
            $this->site->syncQuantity(NULL, NULL, $sale_items);
            return $return_id;
        }
        return false;
    }
	
	/* Return Sales */
	public function returnSales($data = array(), $items = array(), $payment = array())
    {
		foreach($items as $g){
			$totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity']);
			$data['total_cost'] += $totalCostProducts->total_cost;
		}

        if ($this->db->insert('return_sales', $data)) {
            $return_id = $this->db->insert_id();
            if ($this->site->getReference('re', $data['biller_id']) == $data['reference_no']) {
                $this->site->updateReference('re', $data['biller_id']);
            }

            foreach ($items as $item) {

                $item['return_id']              = $return_id;
                $qty_balance                    = $item['quantity_balance'];
                unset($item['quantity_balance']);
                $this->db->insert('return_items', $item);
                $return_item_id                 = $this->db->insert_id();

                $clause = array(
                    'transaction_type' 	=> 'PRODUCT RETURN',
                    'transaction_id' 	=> $return_item_id,
                    'status' 			=> 'received',
                    'product_id' 		=> $item['product_id'],
                    'warehouse_id' 		=> $item['warehouse_id'],
                    'option_id' 		=> $item['option_id'],
                    'date' 		        => $data['date'],
                    'quantity_balance'  => $qty_balance,
                    'product_code'      => $item['product_code'],
                    'product_name'      => $item['product_name'],
                    'product_type'      => $item['product_type'],
                );

                $this->db->insert('purchase_items', $clause);

            }

            $this->site->syncQuantity(NULL, NULL, NULL, NULL, NULL, $return_id);

            if (!empty($payment)) {
                $payment['return_id'] = $return_id;
                $this->db->insert('payments', $payment);
                $payment_id = $this->db->insert_id();
                if ($this->site->getReference('pp', $payment['biller_id']) == $payment['reference_no']) {
                    $this->site->updateReference('pp', $payment['biller_id']);
                }

                if ($payment['paid_by'] == 'deposit') {
                    $deposit = array(
                        'date'          => $payment['date'],
                        'reference'     => $payment['reference_no'],
                        'amount'        => $payment['amount'],
                        'paid_by'       => $payment['paid_by'],
                        'created_by'    => $this->session->userdata('user_id'),
                        'status'        => 'deposit',
                        'biller_id'     => $payment['biller_id'],
                        'payment_id'    => $payment_id,
                        'company_id'    => $data['customer_id'],
                        'note'          => $data['customer']
                    );
                    $this->db->insert('deposits', $deposit);
                }

                $this->calculateSaleTotalsReturn($data['sale_id'], $return_id, $data['surcharge']);
            }
            return true;
        }
        return false;
    }

    public function getCostingLines($sale_item_id, $product_id)
    {
		$this->db->select('costing.*, product_variants.qty_unit');
		$this->db->join('product_variants', 'product_variants.id=costing.option_id','left');
        $this->db->order_by('costing.id', 'asc');
        $q = $this->db->get_where('costing', array('costing.sale_item_id' => $sale_item_id, 'costing.product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSaleItemByRefPID($sale_ref, $product_id)
    {
        $this->db->select('sale_items.id AS sale_item_id, sales.id AS sale_id');
        $this->db->join('sale_items', 'sale_items.sale_id = sales.id', 'inner');
        $q = $this->db->get_where('sales', array('sales.reference_no' => $sale_ref, 'sale_items.product_id' => $product_id));

        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSaleItemByRefPIDReturn($sale_ref, $product_id)
    {
        $this->db->select('sale_items.quantity');
        $this->db->join('sale_items', 'sale_items.sale_id = sales.id', 'inner');
        $q = $this->db->get_where('sales', array('sales.reference_no' => $sale_ref, 'sale_items.product_id' => $product_id));

        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSaleItemByID($id)
    {
        $q = $this->db->get_where('sale_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function getSaleItemByProductID($product_id)
    {
        $q = $this->db->get_where('sale_items', array('product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	function getSalesById($id){
		$q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function calculateSaleTotals($id, $return_id, $surcharge,$payment_status =NULL)
    {
        $sale = $this->getInvoiceByID($id);
        $items = $this->getAllInvoiceItems($id);

        if (!empty($items)) {
            $this->erp->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, TRUE);
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $total_items = 0;
            foreach ($items as $item) {
                $total_items += $item->quantity;
                $product_tax += $item->item_tax;
                $product_discount += $item->item_discount;
                $total += $item->net_unit_price * $item->quantity;
            }
            if ($sale->order_discount_id) {
                $percentage = '%';
                $order_discount_id = $sale->order_discount_id;
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total + $product_tax) * (Float)($ods[0])) / 100;
                } else {
                    $order_discount = $order_discount_id;
                }
            }
            if ($sale->order_tax_id) {
                $order_tax_id = $sale->order_tax_id;
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
            $grand_total = $total + $total_tax + $sale->shipping - $order_discount + $surcharge;
			if($payment_status){
				$data = array(
					'total' => $total,
					'product_discount' => $product_discount,
					'order_discount' => $order_discount,
					'total_discount' => $total_discount,
					'product_tax' => $product_tax,
					'order_tax' => $order_tax,
					'total_tax' => $total_tax,
					'grand_total' => $grand_total,
					'total_items' => $total_items,
					'return_id' => $return_id,
					'surcharge' => $surcharge,
					'payment_status' => $payment_status
				);
			}else{
				$data = array(
					'total' => $total,
					'product_discount' => $product_discount,
					'order_discount' => $order_discount,
					'total_discount' => $total_discount,
					'product_tax' => $product_tax,
					'order_tax' => $order_tax,
					'total_tax' => $total_tax,
					'grand_total' => $grand_total,
					'total_items' => $total_items,
					'return_id' => $return_id,
					'surcharge' => $surcharge
				);
			}

            if ($this->db->update('sales', $data, array('id' => $id))) {
                $this->erp->update_award_points($data['grand_total'], $sale->customer_id, $sale->created_by);
                return true;
            }
        } else {
            //$this->db->delete('sales', array('id' => $id));
            //$this->db->delete('payments', array('sale_id' => $id, 'return_id !=' => $return_id));
        }
        return FALSE;
    }

	public function calculateSaleTotalsReturn($id, $return_id, $surcharge = NULL,$payment_status =NULL)
    {
        $sale = $this->getInvoiceByID($id);
        $items = $this->getAllInvoiceItems($id);

        if (!empty($items)) {
            //$this->erp->update_award_points($sale->grand_total, $sale->customer_id, $sale->created_by, TRUE);
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $total_items = 0;
            foreach ($items as $item) {
                $total_items += $item->quantity;
                $product_tax += $item->item_tax;
                $product_discount += $item->item_discount;
                $total += $item->net_unit_price * $item->quantity;
            }
            if ($sale->order_discount_id) {
                $percentage = '%';
                $order_discount_id = $sale->order_discount_id;
                $opos = strpos($order_discount_id, $percentage);
                if ($opos !== false) {
                    $ods = explode("%", $order_discount_id);
                    $order_discount = (($total + $product_tax) * (Float)($ods[0])) / 100;
                } else {
                    $order_discount = $order_discount_id;
                }
            }
            if ($sale->order_tax_id) {
                $order_tax_id = $sale->order_tax_id;
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
            $grand_total = $total + $total_tax + $sale->shipping - $order_discount + $surcharge;
			if($payment_status){
				$data = array(
					//'total' => $total,
					//'product_discount' => $product_discount,
					//'order_discount' => $order_discount,
					//'total_discount' => $total_discount,
					//'product_tax' => $product_tax,
					//'order_tax' => $order_tax,
					//'total_tax' => $total_tax,
					//'grand_total' => $grand_total,
					//'total_items' => $total_items,
					'return_id' => $return_id,
					//'surcharge' => $surcharge,
					'payment_status' => $payment_status
				);
			}else{
				$data = array(
					//'total' => $total,
					//'product_discount' => $product_discount,
					//'order_discount' => $order_discount,
					//'total_discount' => $total_discount,
					//'product_tax' => $product_tax,
					//'order_tax' => $order_tax,
					//'total_tax' => $total_tax,
					//'grand_total' => $grand_total,
					//'total_items' => $total_items,
					'return_id' => $return_id,
					'sale_status' => 'returned',
					//'surcharge' => $surcharge
				);
			}

            if ($this->db->update('sales', $data, array('id' => $id))) {
                //$this->erp->update_award_points($data['grand_total'], $sale->customer_id, $sale->created_by);
                return true;
            }
        } else {
            //$this->db->delete('sales', array('id' => $id));
            //$this->db->delete('payments', array('sale_id' => $id, 'return_id !=' => $return_id));
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

    public function addDelivery($data = array())
    {
        if ($this->db->insert('deliveries', $data)) {
            if ($this->site->getReference('do') == $data['do_reference_no']) {
                $this->site->updateReference('do');
            }
            return true;
        }
        return false;
    }

    public function updateDelivery($id, $data = array())
    {
        if ($this->db->update('deliveries', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }

	public function completedDeliveries($id)
    {
        if ($this->db->update('deliveries', array('delivery_status' => 'completed'), array('id' => $id))) {
            return true;
        }
        return false;
    }

     public function getDeliveryByID($delivery_id=Null, $wh=null)
    {
    	$this->db->select("deliveries.customer_id,deliveries.id as id, deliveries.date, deliveries.do_reference_no, deliveries.sale_reference_no,deliveries.sale_reference_no AS reference_no ,cust.name as customer_name,cust.address,qty_order.qty AS qty_order,COALESCE(SUM(erp_delivery_items.quantity_received),0) as qty, deliveries.delivery_status as de_sale_status,deliveries.sale_id,deliveries.biller_id")
        ->from('deliveries')
        ->join('(SELECT erp_sales.id AS id,SUM(erp_sale_items.quantity) as qty FROM
                    erp_sales LEFT JOIN erp_sale_items ON erp_sale_items.sale_id = erp_sales.id GROUP BY erp_sales.id) AS qty_order','erp_deliveries.sale_id = qty_order.id','left')
        ->join('delivery_items', 'delivery_items.delivery_id = deliveries.id', 'inner')
        ->join('companies as erp_cust', 'cust.id = deliveries.customer_id', 'inner')
        ->where('type','invoice')
        ->where('erp_deliveries.id',$delivery_id)
        ->group_by('deliveries.id');
        if($wh){
        	$this->db->where_in('delivery_items.warehouse_id',$wh);
        }
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
    }

    public function getOrderDeliveryByID($id=null,$wh=null){
    	$this->db->select("deliveries.customer_id,deliveries.id as id, deliveries.date, deliveries.do_reference_no, deliveries.sale_reference_no,deliveries.sale_reference_no AS reference_no ,cust.name as customer_name,cust.address,qty_order.qty AS qty_order,COALESCE(SUM(erp_delivery_items.quantity_received),0) as qty, deliveries.sale_status,deliveries.sale_id,deliveries.biller_id")
        ->from('deliveries')
        ->join('(SELECT erp_sales.id AS id,SUM(erp_sale_items.quantity) as qty FROM
                    erp_sales LEFT JOIN erp_sale_items ON erp_sale_items.sale_id = erp_sales.id GROUP BY erp_sales.id) AS qty_order','erp_deliveries.sale_id = qty_order.id','left')
        ->join('delivery_items', 'delivery_items.delivery_id = deliveries.id', 'inner')
        ->join('companies as erp_cust', 'cust.id = deliveries.customer_id', 'inner')
        ->where('type','sale_order')
        ->where('erp_deliveries.id',$id);
        if($wh){
        	$this->db->where_in('delivery_items.warehouse_id',$wh);
        }
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;

    }

	public function getSaleDeliveryByID($delivery_id=Null)
    {
        $this->db->select('erp_deliveries.*,erp_sales.shipping,erp_sales.order_discount,erp_sales.order_tax,erp_sales.customer_id as customer_id,erp_sales.payment_status,erp_sales.saleman_by,companies.name as company_name,users.username as saleman,erp_sales.order_tax_id,erp_sales.sale_status,erp_sales.biller_id,erp_sales.delivery_by,erp_sales.payment_term,erp_sales.order_discount_id,erp_sales.po as po_no,erp_sale_order.po as po_no_so, erp_categories.categories_note_id as cnote_id');
		$this->db->where('erp_deliveries.issued_sale_id',$delivery_id);
		$this->db->join('erp_sales','erp_deliveries.sale_id=erp_sales.id', 'left');
		$this->db->join('erp_sale_order','erp_deliveries.sale_reference_no = erp_sale_order.reference_no', 'left');
		$this->db->join('users','deliveries.created_by = users.id', 'left');
		$this->db->join('delivery_items','deliveries.id = delivery_items.delivery_id', 'left');
		$this->db->join('erp_companies','erp_sales.customer_id = erp_companies.id', 'left');

		$this->db->join('erp_products','erp_delivery_items.product_id = erp_products.id', 'left');
		$this->db->join('erp_categories','erp_products.category_id = erp_categories.id', 'left');

		$this->db->from('deliveries');
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
    }

    public function getSaleDeliveryByIDForAC($delivery_id=Null)
    {
        $this->db->select('erp_deliveries.*,erp_sales.shipping,erp_sales.order_discount,erp_sales.order_tax,erp_sales.customer_id as customer_id,erp_sales.payment_status,erp_sales.saleman_by,companies.name as company_name,users.username as saleman,erp_sales.order_tax_id,erp_sales.sale_status,erp_sales.biller_id,erp_sales.delivery_by,erp_sales.payment_term,erp_sales.order_discount_id,erp_sales.po as po_no,erp_sale_order.po as po_no_so, erp_categories.categories_note_id as cnote_id');
        $this->db->where('erp_deliveries.id',$delivery_id);
        $this->db->join('erp_sales','erp_deliveries.sale_id=erp_sales.id', 'left');
        $this->db->join('erp_sale_order','erp_deliveries.sale_reference_no = erp_sale_order.reference_no', 'left');
        $this->db->join('users','deliveries.created_by = users.id', 'left');
        $this->db->join('delivery_items','deliveries.id = delivery_items.delivery_id', 'left');
        $this->db->join('erp_companies','erp_sales.customer_id = erp_companies.id', 'left');

        $this->db->join('erp_products','erp_delivery_items.product_id = erp_products.id', 'left');
        $this->db->join('erp_categories','erp_products.category_id = erp_categories.id', 'left');

        $this->db->from('deliveries');
        $q = $this->db->get();
        if($q->num_rows()>0){
            return $q->row();
        }
        return false;
    }

    public function getCombineSaleDeliveryByID($delivery_id)
    {
        $this->db->select('erp_deliveries.*,erp_sales.customer_id as customer_id,
        erp_sales.saleman_by,
        companies.name as company_name,
        erp_sales.biller_id,erp_sales.delivery_by,
        COALESCE(SUM(erp_delivery_items.quantity_received)) as quantity_received');
        $this->db->join('erp_sales','erp_deliveries.sale_id=erp_sales.id', 'left');
        $this->db->join('delivery_items','deliveries.id = delivery_items.delivery_id', 'left');
        $this->db->join('erp_companies','erp_sales.customer_id = erp_companies.id', 'left')
        //$this->db->where('erp_deliveries.issued_sale_id',$delivery_id)
        ->group_by('deliveries.id');
        $q = $this->db->get_where('erp_deliveries', array('issued_sale_id' => $delivery_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getSaleOrderDeliveryByID($delivery_id=Null)
    {
        $this->db->select('erp_deliveries.*,erp_sale_order.shipping,erp_sale_order.order_discount,erp_sale_order.order_tax,erp_sale_order.customer_id as customer_id,erp_sale_order.payment_status,erp_sale_order.saleman_by,companies.name as company_name,erp_sale_order.order_tax_id,erp_sale_order.sale_status,erp_sale_order.biller_id,erp_sale_order.delivery_by,erp_sale_order.payment_term,erp_sale_order.order_discount_id,sale_order.po as po_no');
		$this->db->where('erp_deliveries.id',$delivery_id);
		$this->db->join('erp_sale_order','erp_deliveries.sale_id=erp_sale_order.id');
		$this->db->join('erp_companies','erp_sale_order.customer_id = erp_companies.id');
		$this->db->from('deliveries');
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
    }


	public function getDelivery($id= Null){
		$this->db->select('deliveries.*, companies.name, companies.company');
		$this->db->join('companies', 'deliveries.customer_id = companies.id', 'inner');
		$q = $this->db->get_where('deliveries',array('deliveries.id'=>$id));
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}

    public function getDeliveryByIssueInvoice($id = Null)
    {
        $this->db->select('id');
        $q = $this->db->get_where('deliveries', array('issued_sale_id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getDeliveryBySaleID($sale_id)
    {
        $q = $this->db->get_where('deliveries', array('sale_id' => $sale_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function updateStock($products=null){
		$status=false;
		foreach($products as $del){
			$this->db->insert('erp_purchase_items',$del);
			if($this->db->affected_rows()>0){
				$status = true;
			}
		}
		if($status == true){
			return true;
		}
		return false;
	}
	
	public function deleteDelivery($id)
    {
		$stock_info = $this->resetDeliveryActions($id);
        if ($this->db->delete('deliveries', array('sale_id' => $id)) && $this->db->delete('delivery_items', array('delivery_id' => $id))) {
			$this->site->syncQuantity(NULL, $stock_info);
            return true;
        }
        return FALSE;
    }
	
	public function deleteDelivery_($id)
    {
		$stock_info = $this->resetDeliveryActions($id);
        if ($this->db->delete('deliveries', array('id' => $id)) && $this->db->delete('delivery_items', array('delivery_id' => $id))) {
			$this->site->syncQuantity(NULL, $stock_info);
            return true;
        }
        return FALSE;
    }

    public function getInvoicePayments($id)
    {	
		$this->db->select('payments.*,gl_charts.accountname')
				 ->join('gl_charts','gl_charts.accountcode = payments.bank_account','left');
		$this->db->order_by('id', 'asc');
        $q = $this->db->get_where('payments', array('sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getInvoicePaymentsLoan($id)
    {	
		$this->db->select('payments.*,gl_charts.accountname')
				 ->join('gl_charts','gl_charts.accountcode = payments.bank_account','left');
		$this->db->order_by('id', 'asc');
        $q = $this->db->get_where('payments', array('loan_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getPayments($fre)
    {
        $this->db->select('*,sales.reference_no as reslae, CONCAT(erp_users.first_name," ",erp_users.last_name) AS name, payments.id AS payment_id')
        ->join('sales','sales.id=payments.sale_id','left')
        ->join('users','sales.saleman_by = users.id','left');

		$this->db->where(array('payments.reference_no' => $fre));
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
           foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


	public function getCurrentBalance($sale_id)
	{
		$this->db->select('id, amount, extra_paid')
				 ->order_by('id', 'asc');
		$q = $this->db->get_where('payments', array('sale_id' => $sale_id));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
	
	public function getPurchaseByID($id)
	{
		$this->db->select('purchases.date,purchases.reference_no,payments.amount,purchases.biller_id,purchases.supplier_id,payments.paid_by')
            ->join('payments','purchases.id=payments.purchase_id','left');
        $q = $this->db->get_where('purchases', array('payments.id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function getPaymentsForSale($sale_id)
    {
        $this->db->select('payments.date, payments.paid_by, payments.amount,payments.pos_paid, payments.cc_no, payments.cheque_no, payments.reference_no, users.first_name, users.last_name, type')
            ->join('users', 'users.id=payments.created_by', 'left');
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getPaymentsForSaleFlora($sale_id)
    {
        $this->db->select('payments.date, payments.paid_by, payments.amount,payments.pos_paid, payments.cc_no, payments.cheque_no, payments.reference_no, users.first_name, users.last_name, type')
            ->join('users', 'users.id=payments.created_by', 'left');
        $q = $this->db->get_where('payments', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getPaymentsForSaleOrderFlora($so_id)
    {
        $this->db->select('deposits.date, deposits.paid_by, deposits.amount, deposits.reference, users.first_name, users.last_name')
            ->join('users', 'users.id=deposits.created_by', 'left');
        $q = $this->db->get_where('deposits', array('so_id' => $so_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getProjectManager($sale_ref)
    {
         $this->db->select('users.first_name,last_name')
            ->join('users', 'users.id=sales.assign_to_id', 'left');
        $q = $this->db->get_where('sales', array('reference_no' => $sale_ref));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function addEventToDo($data = array())
    {
        if ($this->db->insert('calendar', $data)) {
            return true;
        }
        return false;
    }
	
	public function addClearSaleman($data = array())
    {
		$deposit_customer_id = $data['deposit_customer_id'];
		unset($data['old_payment_id']);
		unset($data['deposit_customer_id']);
        if ($this->db->insert('salesman_clear_money', $data)) {
			$payment_id = $this->db->insert_id();
			if ($this->site->getReference('sp',$data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('sp',$data['biller_id']);
			}
            
            $this->site->syncSalePayments($data['sale_id']);
            if ($data['paid_by'] == 'gift_card') {
                $gc = $this->site->getGiftCardbyNO($data['cc_no']);
                $this->db->update('gift_cards', array('balance' => ($gc->balance - $data['amount'])), array('card_no' => $data['cc_no']));
            }
			
			if($data['paid_by'] == 'deposit'){
				$deposit = $this->site->getDepositByCompanyID($deposit_customer_id);
				$deposit_balance = $deposit->deposit_amount;
				$deposit_balance = $deposit_balance - abs($data['amount']);
				if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $deposit_customer_id))){
					//$this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $deposit_customer_id));
				}
			}
            return $payment_id;
        }
        return false;
    }

    public function addPayment($data = array())
    {
		if($data['old_payment_id'])
		{
			$this->db->delete('payments', array('id'=>$data['old_payment_id'],'sale_id' => $data['sale_id'], 'is_down_payment' => 1));
			$this->db->delete('gl_trans',array('payment_id'=>$data['old_payment_id'],'reference_no'=>$data['reference_no']));
			$this->site->syncSalePayments($data['sale_id']);
		}
		$deposit_customer_id = $data['deposit_customer_id'];
		unset($data['old_payment_id']);
		unset($data['deposit_customer_id']);
        if ($this->db->insert('payments', $data)) {
			$payment_id = $this->db->insert_id();
			if ($this->site->getReference('sp',$data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('sp',$data['biller_id']);
			}
            
            $this->site->syncSalePayments($data['sale_id']);
            if ($data['paid_by'] == 'gift_card') {
                $gc = $this->site->getGiftCardbyNO($data['cc_no']);
                $this->db->update('gift_cards', array('balance' => ($gc->balance - $data['amount'])), array('card_no' => $data['cc_no']));
            }
			
			if($data['paid_by'] == 'deposit'){
				$deposit = $this->site->getDepositByCompanyID($deposit_customer_id);
				$deposit_balance = $deposit->deposit_amount;
				$deposit_balance = $deposit_balance - abs($data['amount']);
				if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $deposit_customer_id))){
					//$this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $deposit_customer_id));
				}
			}
            return $payment_id;
        }
        return false;
    }
	
	public function addPaymentMulti($data = array())
    {
        if ($this->db->insert('payments', $data)) {
			$payment_id = $this->db->insert_id();
			if ($this->site->getReference('pp',$data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('pp',$data['biller_id']);
			}
            $this->site->syncPurchasePayments($data['purchase_id']);
            if ($data['paid_by'] == 'gift_card') {
                $gc = $this->site->getGiftCardbyNO($data['cc_no']);
                $this->db->update('gift_cards', array('balance' => ($gc->balance - $data['amount'])), array('card_no' => $data['cc_no']));
            }
			 if($data['paid_by'] == 'deposit'){
				$deposit = $this->site->getDepositByCompanyID($deposit_customer_id);
				$deposit_balance = $deposit->deposit_amount;
				$deposit_balance = $deposit_balance - abs($data['amount']);
				if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $deposit_customer_id))){
					//$this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $deposit_customer_id));
				}
			}
            return $payment_id;
        }
        return false;
    }
	
	public function addSalePaymentMulti($data = array())
    {
		//$this->erp->print_arrays($data);
		
        if ($this->db->insert('payments', $data)) {
			$payment_id = $this->db->insert_id();
			if ($this->site->getReference('pp',$data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('pp',$data['biller_id']);
			}
            $this->site->syncSalePayments($data['sale_id']);
            if ($data['paid_by'] == 'gift_card') {
                $gc = $this->site->getGiftCardbyNO($data['cc_no']);
                $this->db->update('gift_cards', array('balance' => ($gc->balance - $data['amount'])), array('card_no' => $data['cc_no']));
            }
			 if($data['paid_by'] == 'deposit'){
				$deposit = $this->site->getDepositByCompanyID($deposit_customer_id);
				$deposit_balance = $deposit->deposit_amount;
				$deposit_balance = $deposit_balance - abs($data['amount']);
				if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $deposit_customer_id))){
					//$this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $deposit_customer_id));
				}
			}
            return $payment_id;
        }
        return false;
    }
	
	public function addPurchasePaymentMulti($data = array())
    {
		
        if ($this->db->insert('payments', $data)) {
			$payment_id = $this->db->insert_id();
			if ($this->site->getReference('pp',$data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('pp',$data['biller_id']);
			}
			
            $this->site->syncPurchasePayments($data['purchase_id']);
            if ($data['paid_by'] == 'gift_card') {
                $gc = $this->site->getGiftCardbyNO($data['cc_no']);
                $this->db->update('gift_cards', array('balance' => ($gc->balance - $data['amount'])), array('card_no' => $data['cc_no']));
            }
			 if($data['paid_by'] == 'deposit'){
				$deposit = $this->site->getDepositByCompanyID($deposit_customer_id);
				$deposit_balance = $deposit->deposit_amount;
				$deposit_balance = $deposit_balance - abs($data['amount']);
				if($this->db->update('companies', array('deposit_amount' => $deposit_balance), array('id' => $deposit_customer_id))){
					//$this->db->update('deposits', array('amount' => $deposit_balance), array('company_id' => $deposit_customer_id));
				}
			}
            return $payment_id;
        }
        return false;
    }


	public function addSalePaymentLoan($data = array())
	{
		$id = $data['id'];

        if ($this->db->update('sales', $data, array('id' => $id))) {
            return true;
        }
        return false;
	}

	public function getTotalInterestSale($id)
	{
		$q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function addPaymentLoan($data = array(),$loan_id = NULL,$sale_id = NULL)
    {
		
		//$this->erp->print_arrays($loan_id);
		$old_loan  = $this->getSingleLoanById($loan_id);
		$data['paid_amount'] = ($data['paid_amount']+$old_loan->paid_amount);
		$data['discount']    = ($data['discount']+$old_loan->discount);
		
        if ($this->db->update('loans', $data, array('id' => $loan_id,'sale_id'=>$sale_id))) 
		{
			$this->syncLoanPayments($loan_id);
        }
		return true;
    }
	
	public function addLoanPayment($payments = array(),$interest=0)
	{
		$Interest       = $this->getTotalInterestSale($payments['sale_id']);
		$updateInterest = ($Interest->total_interest+$interest);
		$sale_id 		= $payments['sale_id'];
		//unset($payments['sale_id']);
		
		$this->db->update('sales', array('total_interest'=>$updateInterest), array('id' => $sale_id));
		
		if ($this->db->insert('payments', $payments)) {
				if ($this->site->getReference('sp', $payments['biller_id']) == $payments['reference_no']) {
					$this->site->updateReference('sp', $payments['biller_id']);
				}
				$this->site->syncSalePayments($sale_id);
				if ($payments['paid_by'] == 'gift_card') {
					$gc = $this->site->getGiftCardbyNO($payments['cc_no']);
					$this->db->update('gift_cards', array('balance' => ($gc->balance - $payments['amount'])), array('card_no' => $payments['cc_no']));
				}
				return true;
		}
	}
	
	public function getLoanAmountId($id=null)
	{
		$this->db->select('payment,paid_amount,payment_status');
		$this->db->from('loans');
		$this->db->where('id', $id);
        $q = $this->db->get();
         if ($q->num_rows() > 0) {
            return $q->row();
        }
	}


	public function syncLoanPayments($id=null)
	{
		$loan_id        = $this->getLoanAmountId($id);
        $payment_status = $loan_id->payment == $loan_id->paid_amount? 'paid' : 'partial';
		$this->db->where('id', $id);
        $this->db->update('loans', array('payment_status'=>$payment_status));
		
    }

    public function updatePayment($id, $data = array())
    {
		if($data['to_deposit'] == "1"){
			unset($data['to_deposit']);
			if ($this->db->insert('payments', $data)) {
				$this->site->syncSalePayments($data['sale_id']);
				$data['amount'] = 0;
				$data['pos_paid'] = 0;
				unset($data['paid_by']);
			}
		}
		
		if ($this->db->update('payments', $data, array('id' => $id))) {
            $this->site->syncSalePayments($data['sale_id']);
            return $id;
        }
        return false;
    }
	
	public function getSaleId($id)
	{
		$q = $this->db->get_where('loans', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getSaleById($id)
	{
		$q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}


    public function getSaleByRef($ref)
	{
		$q = $this->db->get_where('sales', array('reference_no' => $ref), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	/*public function getLoanView($id)
	{
		$this->db->order_by('period','DESC');
		$q = $this->db->get_where('loans', array('sale_id' => $id, 'period' => '1'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}*/
	
	public function getMonths($id)
	{
		$this->db->order_by('period','DESC');
		$q = $this->db->get_where('loans', array('sale_id' => $id), 1);
		
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function deletePayment($id)
    {
        $opay = $this->getPaymentByID($id);
        if ($this->db->delete('payments', array('id' => $id))) {
            $this->site->syncSalePayments($opay->sale_id);
            return true;
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

    /* ----------------- Gift Cards --------------------- */

    public function addGiftCard($data = array(), $ca_data = array(), $sa_data = array())
    {
        if ($this->db->insert('gift_cards', $data)) {
            if (!empty($ca_data)) {
                $this->db->update('companies', array('award_points' => $ca_data['points']), array('id' => $ca_data['customer']));
            } elseif (!empty($sa_data)) {
                $this->db->update('users', array('award_points' => $sa_data['points']), array('id' => $sa_data['user']));
            }
            return true;
        }
        return false;
    }
	
	public function importGiftCard($data)
	{
		if ($this->db->insert_batch('gift_cards', $data)) {
			return true;
        }
        return false;
	}

    public function updateGiftCard($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('gift_cards', $data)) {
            return true;
        }
        return false;
    }

    public function deleteGiftCard($id)
    {
        if ($this->db->delete('gift_cards', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }

    public function getPaypalSettings()
    {
        $q = $this->db->get_where('paypal', array('id' => 1));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSkrillSettings()
    {
        $q = $this->db->get_where('skrill', array('id' => 1));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getQuoteByID($id)
    {
        $this->db->select('quotes.*,companies.group_areas_id AS group_area');
		$this->db->join('users','quotes.created_by = users.id', 'left');
		$this->db->join('companies','quotes.customer_id = companies.id', 'left');
		$this->db->where('quotes.id',$id);
		$this->db->from('quotes');		
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
    }
	
	public function getAssignSaleById($id)
	{
		$this->db->select('sales.*,CONCAT(erp_users.last_name," ",erp_users.first_name) AS saleman');
		$this->db->join('users','users.id = sales.saleman_by', 'left');
		$this->db->where_in('sales.id',$id);
		$this->db->from('sales');		
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
    public function getAllQuoteItems($quote_id)
    {
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
        if (!$this->Owner) {
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

    public function getProductVariantByName($name, $product_id)
    {
        $q = $this->db->get_where('product_variants', array('name' => $name, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getProductVariantByid($product_id)
    {
        $q = $this->db->get_where('product_variants', array('product_id' => $product_id));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return Null;
    }
	
	public function getTaxRateByCode($code)
    {
        $q = $this->db->get_where('tax_rates', array('code' => $code), 1);
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
	public function getCombinePaymentById($id)
    {
		$this->db->select('id, date, reference_no, biller, customer, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status');
		$this->db->from('sales');
		$this->db->where_in('id', $id);
		$this->db->where('paid < grand_total');
        $q = $this->db->get();
         if ($q->num_rows() > 0) {
            return $q->result();
        }
		return FALSE;
    }
	
	public function getCombinePaymentBySaleId($id)
    {
		$this->db->select('sales.id, sales.date, sales.reference_no, sales.biller, sales.customer, sales.sale_status, sales.grand_total, sales.paid, (erp_sales.grand_total-erp_sales.paid-COALESCE(erp_return_sales.grand_total,0)) as balance, sales.payment_status');
		$this->db->from('sales');
		$this->db->join('return_sales', 'return_sales.id = sales.return_id', 'left');
		$this->db->where_in('sales.id', $id);
		$this->db->where('sales.paid < sales.grand_total');
		//$this->db->where('sale_status !=', 'returned');
        $q = $this->db->get();
         if ($q->num_rows() > 0) {
            return $q->result();
        }
		return FALSE;
    }


	public function getCombinePaymentPurById($id)
    {
		$this->db->select('id, date, reference_no, supplier,status, grand_total, paid, (grand_total-paid) as balance, payment_status');
		$this->db->from('erp_purchases');
		$this->db->where_in('id', $id);
		$this->db->where('paid<grand_total');
        $q = $this->db->get();
         if ($q->num_rows() > 0) {
            return $q->result();
        }
		return FALSE;
    }
	public function getSampleSaleRefByProductID($product_id){
		$q = $this->db->select('MAX(reference_no) AS reference_no')
					->join('sale_items', 'sale_items.sale_id = sales.id', 'left')
					->where('sale_items.product_id', $product_id)
					->get('sales');
		if($q->num_rows() > 0){
			return $q->row()->reference_no;
		}
	}
	
	function getSetting()
    {
        $q = $this->db->get('pos_settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	function getSettings()
    {
        $q = $this->db->get('settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	function add_booking($id, $data){
		$this->db->where('id', $id);
		$this->db->update('erp_suspended',$data);
		return $this->db->affected_rows();
	}
	
	public function getDocumentByID($id){
		$this->db->select('attachment, attachment1, attachment2')
				 ->from('sales')
				 ->where('id',$id);
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}
	
	public function getUserBySaleID($sale_id=null){
		$this->db->select('users.username,companies.name,companies.company');
        $this->db->from('deliveries');
		$this->db->join('sales','deliveries.sale_id=sales.id');
		$this->db->join('users','sales.saleman_by=users.id');
		$this->db->join('companies','sales.delivery_by=companies.id');
        $q = $this->db->get();
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	public function getUserFromSaleBySaleID($id){
		$this->db->select('users.*');
		$this->db->where('sales.id',$id);
		$this->db->join('users','sales.saleman_by = users.id');
		$q = $this->db->get('sales');
		if($q->num_rows()>0){
			return $q->row();
		}
		return Null;
	}
	public function getCustomerFullAddress($customer){
		$response = $this->db
                 ->select('address,city,state,postal_code,country,phone,email')
                 ->where('company', $customer)
                 ->from('companies')
                 ->get()
                 ->row_array();
		if($response){
			return $response;
		}
		return false;
		
	}
	
	public function getSItemsBySaleID($saleId=null, $product_id = array()){
		$response = $this->db
                 ->select('*')
				 ->where_in('product_id', $product_id)
                 ->where('sale_id', $saleId)
                 ->from('sale_items')
                 ->get()
                 ->result();
		return $response;	
	}
	
	public function updateSaleItemQtyReceived($qty_received,$condition){
		$this->db->where($condition);
		$result = $this->db->update('sale_items', $qty_received);
		if($result > 0){
			return true;
		}
	}
	
	public function updateSaleOrderQtyReceived($qty_received,$condition){
		$this->db->where($condition);
		$result = $this->db->update('sale_order_items', $qty_received);
		if($result > 0){
			return true;
		}
	}
	
	public function updatePOSSaleOrderQtyReceived($qty_received,$condition){
		$this->db->where($condition);
		$result = $this->db->update('sale_items', $qty_received);
		if($result > 0){
			return true;
		}
	}
	
	public function add_assign_sale_man($data)
	{
		foreach($data as $data_assign){
			$this->db->insert('salesman_assign', $data_assign);
		}
		$assign_to_id = $this->db->insert_id();
	}
	
	public function add_delivery($delivery, $delivery_items)
	{
		$pos = $delivery['pos'];
		unset($delivery['pos']);
		
		if(isset($delivery) && !empty($delivery) && isset($delivery_items) && !empty($delivery_items)){
			foreach($delivery_items as $g){
				$totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity_received']);
				$product_variants = $this->site->getProductVariant($g['option_id'], $g['product_id']);
				if($product_variants) {
					$delivery['total_cost'] += $totalCostProducts->total_cost * $product_variants->qty_unit;
				}else {
					$delivery['total_cost'] += $totalCostProducts->total_cost;
				}
			}
			
			$this->db->insert('deliveries', $delivery);
			$delivery_id = $this->db->insert_id();
			
			if($delivery_id > 0){
				//$this->db->update("erp_sales",array('sale_status'=>'completed'),array('id'=>$delivery['sale_id']));
				//$this->erp->print_arrays($this->site->getReference('do',$delivery['biller_id']),$delivery['do_reference_no']);
				if ($this->site->getReference('do',$delivery['biller_id']) == $delivery['do_reference_no']) {
					$this->site->updateReference('do',$delivery['biller_id']);
				}
				
				foreach($delivery_items as $delivery_item){
					$delivery_item['delivery_id'] = $delivery_id;
					if($delivery_item['option_id'] == '' || $delivery_item['option_id'] == null) {
						unset($delivery_item['option_id']);
					}

                    $this->db->update('sales', array('sale_status' => 'completed'), array('id' => $delivery['sale_id']));

					/*if ($delivery_item['sale_id']) {
						$abc = $this->db->update('sales', array('so_id' => $delivery_item['sale_id']), array('sale_id' => $delivery_item['sale_id']));
					}*/
					
					$this->db->insert('delivery_items',$delivery_item);
					$delivery_item_id = $this->db->insert_id();
					
					if ($delivery['delivery_status'] == 'completed' && $getproduct = $this->site->getProductByID($delivery_item['product_id'])) {
						
						if($delivery['type'] == 'sale_order') {
							$getitem = $this->getSaleOrderItemByID($delivery_item['item_id']);
							if($pos == 1){
								$getitem = $this->getSaleItemByID($delivery_item['item_id']);
							}
						}else {
							$getitem = $this->getSaleItemByID($delivery_item['item_id']);
							
						}
						
						$item = array(
							'product_id' 		=> $delivery_item['product_id'],
							'product_name' 		=> $delivery_item['product_name'],
							'product_type' 		=> $getproduct->type,
							'option_id' 		=> $delivery_item['option_id'],
							'warehouse_id' 		=> $delivery_item['warehouse_id'],
							'quantity' 			=> $delivery_item['quantity_received'],
							'net_unit_price' 	=> $getitem->net_unit_price,
							'unit_price' 		=> $getitem->unit_price
						);
						$item_costs = $this->site->item_costing($item);
						
						foreach ($item_costs as $item_cost) {
							$item_cost['delivery_item_id'] = $delivery_item_id;
							$item_cost['delivery_id'] = $delivery_id;
							if(isset($data['date'])){
								$item_cost['date'] = $delivery['date'];
							}
							unset($item_cost['transaction_type']);
							unset($item_cost['transaction_id']);
							unset($item_cost['status']);
							//$option_id = $item_cost['option_id'];
							
							if(! isset($item_cost['pi_overselling'])) {
								$this->db->insert('costing', $item_cost);
							}
						}
					}
				}
				
				return $delivery_id;
			}
			
		}
		return false;
	}
	
	public function add_delivery_old($delivery, $delivery_items){
		
		if(isset($delivery) && !empty($delivery) && isset($delivery_items) && !empty($delivery_items)){
			
			foreach($delivery_items as $g){
				$totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity_received']);
				
				$product_variants = $this->site->getProductVariant($g['option_id'], $g['product_id']);
				if($product_variants) {
					$delivery['total_cost'] += $totalCostProducts->total_cost * $product_variants->qty_unit;
				}else {
					$delivery['total_cost'] += $totalCostProducts->total_cost;
				}
			}
			
			$this->db->insert('deliveries', $delivery);
			$delivery_id = $this->db->insert_id();
			
			if($delivery_id > 0){
				//$this->db->update("erp_sales",array('sale_status'=>'completed'),array('id'=>$delivery['sale_id']));
				//$this->erp->print_arrays($this->site->getReference('do',$delivery['biller_id']),$delivery['do_reference_no']);
				if ($this->site->getReference('do',$delivery['biller_id']) == $delivery['do_reference_no']) {
					$this->site->updateReference('do',$delivery['biller_id']);
				}
				
				foreach($delivery_items as $delivery_item){
					$delivery_item['delivery_id'] = $delivery_id;
					if($delivery_item['option_id'] == '' || $delivery_item['option_id'] == null) {
						unset($delivery_item['option_id']);
					}

					/*if ($delivery_item['sale_id']) {
						$abc = $this->db->update('sales', array('so_id' => $delivery_item['sale_id']), array('sale_id' => $delivery_item['sale_id']));
					}*/
					
					$this->db->insert('delivery_items',$delivery_item);
					$delivery_item_id = $this->db->insert_id();
					
					if ($delivery['delivery_status'] == 'completed' && $getproduct = $this->site->getProductByID($delivery_item['product_id'])) {
						
						if($delivery['type'] == 'sale_order') {
							$getitem = $this->getSaleOrderItemByID($delivery_item['item_id']);
						}else {
							$getitem = $this->getSaleItemByID($delivery_item['item_id']);
							
						}
						$item = array(
							'product_id' 		=> $delivery_item['product_id'],
							'product_name' 		=> $delivery_item['product_name'],
							'product_type' 		=> $getproduct->type,
							'option_id' 		=> $delivery_item['option_id'],
							'warehouse_id' 		=> $delivery_item['warehouse_id'],
							'quantity' 			=> $delivery_item['quantity_received'],
							'net_unit_price' 	=> $getitem->net_unit_price,
							'unit_price' 		=> $getitem->unit_price
						);
						$item_costs = $this->site->item_costing($item);
						foreach ($item_costs as $item_cost) {
							$item_cost['delivery_item_id'] = $delivery_item_id;
							$item_cost['delivery_id'] = $delivery_id;
							if(isset($data['date'])){
								$item_cost['date'] = $delivery['date'];
							}
							unset($item_cost['transaction_type']);
							unset($item_cost['transaction_id']);
							unset($item_cost['status']);
							//$option_id = $item_cost['option_id'];
							
							if(! isset($item_cost['pi_overselling'])) {
								$this->db->insert('costing', $item_cost);
							}
						}
					}
				}
				return $delivery_id;
			}
			
		}
		return false;
	}
	
	public function getAllSaleItemQty($sale_id){
		$q = $this->db
				->select("COALESCE(SUM(erp_sale_items.quantity),0) as qty,COALESCE(SUM(erp_sale_items.quantity_received),0) as qty_received,COALESCE(SUM(erp_sale_items.quantity),0) - COALESCE(SUM(erp_sale_items.quantity_received),0) as balance")
				->from('sale_items')
				->where('sale_id', $sale_id)
				->get();
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}
	
	
	
	public function getAllSaleOrderItemQty($sale_id){
		$q = $this->db
				->select("COALESCE(SUM(erp_sale_order_items.quantity),0) as qty,COALESCE(SUM(erp_sale_order_items.quantity_received),0) as qty_received,COALESCE(SUM(erp_sale_order_items.quantity),0) - COALESCE(SUM(erp_sale_order_items.quantity_received),0) as balance")
				->from('erp_sale_order_items')
				->where('sale_order_id', $sale_id)
				->get();
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}
	public function getPOSorderItem_Dev($reference_no,$product_id){
		
		$this->db->select('sale_items.*');
		$this->db->from('sales');
		$this->db->join('sale_items','sale_items.sale_id = sales.id','inner');
		$this->db->where('sales.reference_no', $reference_no);
		$this->db->where('sale_items.product_id', $product_id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
	}
	public function getAllPOSSaleOrderItemQty($sale_id){
		$q = $this->db
				->select("COALESCE(SUM(erp_sale_items.quantity),0) as qty,COALESCE(SUM(erp_sale_items.quantity_received),0) as qty_received,COALESCE(SUM(erp_sale_items.quantity),0) - COALESCE(SUM(erp_sale_items.quantity_received),0) as balance")
				->from('erp_sale_items')
				->where('sale_id', $sale_id)
				->get();
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}
	
	public function getPOSDeliveryItemsByID($id, $type){
		
		$response = $this->db->select('delivery_items.*, products.code, sale_items.quantity as ord_qty, sale_items.quantity_received as ord_qty_rec')
							 ->join('sale_items', 'sale_items.id = delivery_items.item_id', 'inner')
							 ->join('products', 'products.id = delivery_items.product_id', 'inner');
		$q = $this->db->get_where('delivery_items', array('delivery_items.delivery_id' => $id));
		
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getSaleorderItemDev($item_id){
		$q = $this->db->get_where('sale_order_items', array('id' => $item_id));
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	public function getSaleItemDev($item_id){
		$q = $this->db->get_where('sale_items', array('id' => $item_id));
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	
	public function getSaleorderItem_Dev($reference_no,$product_id){
		
		$this->db->select('sale_order_items.*');
		$this->db->from('sale_order');
		$this->db->join('sale_order_items','sale_order_items.sale_order_id = sale_order.id','inner');
		$this->db->where('sale_order.reference_no', $reference_no);
		$this->db->where('sale_order_items.product_id', $product_id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
	}
	
	public function getUserFromSaleOrderByID($id){
		$this->db->select('users.*');
		$this->db->where('sale_order.id',$id);
		$this->db->join('users','sale_order.saleman_by = users.id');
		$q = $this->db->get('sale_order');
		if($q->num_rows()>0){
			return $q->row();
		}
		return Null;
	}
	
	public function getPaymentByQuoteID($quote_id){
		$q = $this->db->get_where('payments', array('deposit_quote_id' => $quote_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getSaleRecordbyID($id){
		$this->db->select('sales.*, companies.name, companies.company');
		$this->db->join('companies', 'sales.customer_id = companies.id', 'inner');
		$q = $this->db->get_where('sales', array('sales.id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function getSaleInfo($sale_id=null){
		$this->db->select('sales.*, users.username'); 
		$this->db->join('users','sales.saleman_by=users.id');
		$q = $this->db->get_where('sales', array('sales.id' => $sale_id));
		
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
		
	}
	
	public function getSaleItemBySaleID($saleId=null){
		$response = $this->db
                 ->select('*')
                 ->where('sale_id', $saleId)
                 ->from('sale_items')
                 ->get()
                 ->result_array();
		return $response;	
	}
	
	public function getSaleItemFreeBySaleID($saleId=null){
		$this->db->select("*");
		$this->db->from("erp_sale_items");
		$this->db->where("sale_id",$saleId);
		$this->db->where("digital_id",0);
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
				 
	}
	
	public function getSaleStandardItemBySaleID($saleId=null){
		$this->db->select("*");
		$this->db->from("erp_sale_items");
		$this->db->where("sale_id",$saleId);
		$this->db->where("digital_id",0);
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
				 
	}
	
	
	public function getDigitalItemsBySaleID($saleId=null){
		
		$this->db->select("digital_id,erp_products.name,SUM(erp_sale_items.quantity) as quantity,erp_sale_items.unit_price as price");
		$this->db->from("erp_sale_items");
		$this->db->where("sale_id",$saleId);
		$this->db->where("unit_price <> ",0);
		$this->db->join("erp_products","erp_sale_items.digital_id = erp_products.id");
		
		$this->db->group_by("digital_id");
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
				 
	}
	
	public function getSubDigitalItemsByDigitalId($sale_id,$digital_id){
		$this->db->select("*");
		$this->db->from("erp_sale_items");
		$this->db->where("sale_id",$sale_id);
		$this->db->where("digital_id",$digital_id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getSole_itemsBySaleID($sale_id){
			$this->db->select("*");
			$this->db->from("erp_sale_items");
			$this->db->where("sale_id",$sale_id);
			$this->db->where("unit_price <> ",0);
			$this->db->where("digital_id",0);
			
			$q = $this->db->get();
			if($q->num_rows()>0){
				foreach($q->result() as $row){
					$data[] = $row;
				}
				return $data;
			}
			return false;
	}
	public function getAllDigitalItem($id){
		$this->db->select("*");
		$this->db->from("erp_sale_items");
		$this->db->where("sale_id",$id);
		$this->db->where("digital_id<>",0);
		$q=$this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getDeliveryItemsByID($id, $type){
		if($type == 'sale_order') {
			$response = $this->db->select('delivery_items.*, products.code, sale_order_items.quantity as ord_qty, sale_order_items.quantity_received as ord_qty_rec')
								 ->join('sale_order_items', 'sale_order_items.id = delivery_items.item_id', 'left')
								 ->join('products', 'products.id = delivery_items.product_id', 'left');
			$q = $this->db->get_where('delivery_items', array('delivery_items.delivery_id' => $id));
		}else {
			$response = $this->db->select('delivery_items.*, products.code, sale_items.quantity as ord_qty, sale_items.quantity_received as ord_qty_rec')
								 ->join('sale_items', 'sale_items.id = delivery_items.item_id', 'left')
								 ->join('products', 'products.id = delivery_items.product_id', 'left');
			$q = $this->db->get_where('delivery_items', array('delivery_items.delivery_id' => $id));
		}
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getDeliveryItemsByItemId($delivery_id = NULL){
		$response = $this->db
                 ->select('erp_sale_order_items.*, erp_delivery_items.quantity_received AS dqty_received, erp_delivery_items.piece as dpiece, erp_delivery_items.wpiece as dwpiece, CONCAT(erp_delivery_items.piece, " x ", erp_delivery_items.wpiece) as dnote')
                 ->where('erp_delivery_items.delivery_id',$delivery_id)
				 ->join('erp_sale_order_items','erp_delivery_items.item_id = erp_sale_order_items.id')
                 ->from('erp_delivery_items')
                 ->get()
                 ->result();
		if(sizeof($response)>0){
			
			return $response;
		}else{
			return false;
		}
		
	}
	
	public function getDelivItemsByID($id){
		$response = $this->db
                 ->select('*')
                 ->where('delivery_id', $id)
                 ->from('delivery_items')
                 ->get()
                 ->result();
		 
		if(sizeof($response)>0){
			
			return $response;
		}else{
			return false;
		}
		
	}
	public function save_edit_delivery($id, $delivery, $delivery_items) {
		
		if($id && $delivery && $delivery_items){
			$this->resetDeliveryActions($id);	
			foreach($delivery_items as $g){
				$totalCostProducts 	= $this->getTotalCostProducts($g['product_id'], $g['quantity_received']);
				$product_variants 	= $this->site->getProductVariant($g['option_id'], $g['product_id']);
				if($product_variants) {
					$delivery['total_cost'] += $totalCostProducts->total_cost * $product_variants->qty_unit;
				}else {
					$delivery['total_cost'] += $totalCostProducts->total_cost;
				}
			}
			
			if($this->db->update('deliveries', $delivery, array('id' => $id))) {
				
				$this->db->delete('delivery_items', array('delivery_id' => $id));
				$this->db->delete('purchase_items',array('delivery_id' => $id));
				foreach($delivery_items as $delivery_item){
					$delivery_item['delivery_id'] = $id;
					if($delivery_item['option_id'] == '' || $delivery_item['option_id'] == null) {
						unset($delivery_item['option_id']);
					}
					
					$old_sqty = $delivery_item['old_sqty'];
					unset($delivery_item['old_sqty']);
					
					$this->db->insert('delivery_items',$delivery_item);
					$delivery_item_id = $this->db->insert_id();
					
					if ($delivery['delivery_status'] == 'completed' && $getproduct = $this->site->getProductByID($delivery_item['product_id'])) {
						if($delivery['type'] == 'sale_order') {
							$getitem = $this->getSaleOrderItemByID($delivery_item['item_id']);
						}else {
							$getitem = $this->getSaleItemByID($delivery_item['item_id']);
						}
						//$this->erp->print_arrays($getitem);
						$item = array(
							'product_id' 		=> $delivery_item['product_id'],
							'product_name' 		=> $delivery_item['product_name'],
							'product_type' 		=> $getproduct->type,
							'option_id' 		=> $delivery_item['option_id'],
							'warehouse_id' 		=> $delivery_item['warehouse_id'],
							'quantity' 			=> $delivery_item['quantity_received'],
							'net_unit_price' 	=> $getitem->net_unit_price,
							'unit_price' 		=> $getitem->unit_price,
							'old_sqty' 			=> $old_sqty
						);
						$item_costs = $this->site->item_costing($item);
						foreach ($item_costs as $item_cost) {
							$item_cost['delivery_item_id'] = $delivery_item_id;
							$item_cost['delivery_id'] = $id;
							if(isset($data['date'])){
								$item_cost['date'] = $delivery['date'];
							}
							//$option_id = $item_cost['option_id'];
							
							if(! isset($item_cost['pi_overselling'])) {
								$this->db->insert('costing', $item_cost);
							}
						}
					}
				}
				return true;
			}
		}
		return false;
	}
	
	public function save_edit_delivery_old($id, $delivery, $delivery_items) {
		if($id && $delivery && $delivery_items){
			$this->resetDeliveryActions($id);
			
			foreach($delivery_items as $g){
				$totalCostProducts = $this->getTotalCostProducts($g['product_id'], $g['quantity_received']);
				$product_variants = $this->site->getProductVariant($g['option_id'], $g['product_id']);
				if($product_variants) {
					$delivery['total_cost'] += $totalCostProducts->total_cost * $product_variants->qty_unit;
				}else {
					$delivery['total_cost'] += $totalCostProducts->total_cost;
				}
			}
			
			if($this->db->update('deliveries', $delivery, array('id' => $id))) {
				
				$this->db->delete('delivery_items', array('delivery_id' => $id));
				$this->db->delete('purchase_items',array('delivery_id' => $id));
				foreach($delivery_items as $delivery_item){
					$delivery_item['delivery_id'] = $id;
					if($delivery_item['option_id'] == '' || $delivery_item['option_id'] == null) {
						unset($delivery_item['option_id']);
					}
					
					$this->db->insert('delivery_items',$delivery_item);
					$delivery_item_id = $this->db->insert_id();
					
					if ($delivery['delivery_status'] == 'completed' && $getproduct = $this->site->getProductByID($delivery_item['product_id'])) {
						if($delivery['type'] == 'sale_order') {
							$getitem = $this->getSaleOrderItemByID($delivery_item['item_id']);
						}else {
							$getitem = $this->getSaleItemByID($delivery_item['item_id']);
						}
						//$this->erp->print_arrays($getitem);
						$item = array(
							'product_id' => $delivery_item['product_id'],
							'product_name' => $delivery_item['product_name'],
							'product_type' => $getproduct->type,
							'option_id' => $delivery_item['option_id'],
							'warehouse_id' => $delivery_item['warehouse_id'],
							'quantity' => $delivery_item['quantity_received'],
							'net_unit_price' => $getitem->net_unit_price,
							'unit_price' => $getitem->unit_price
						);
						$item_costs = $this->site->item_costing($item);
						foreach ($item_costs as $item_cost) {
							$item_cost['delivery_item_id'] = $delivery_item_id;
							$item_cost['delivery_id'] = $id;
							if(isset($data['date'])){
								$item_cost['date'] = $delivery['date'];
							}
							//$option_id = $item_cost['option_id'];
							
							if(! isset($item_cost['pi_overselling'])) {
								$this->db->insert('costing', $item_cost);
							}
						}
					}
				}
				return true;
			}
		}
		return false;
	}	
	
	public function getQuantities($deliver_id){
		
		$this->db->select('delivery_items.id as ditem,deliveries.id,deliveries.sale_id,delivery_items.quantity_received');
		$this->db->where('deliveries.id=',$deliver_id);
		$this->db->from('deliveries');
		$this->db->join('delivery_items','deliveries.id=delivery_items.delivery_id');
		
		$result = $this->db->get()->result_array();
		if(sizeof($result)>0){
			return $result;
		}
		return false; 
	}
	public function getSaleItemQty($sale_id=null){
		
		$this->db->select('sale_items.product_id,sale_items.product_code,sale_items.product_name,sale_items.quantity_received,sale_items.quantity,(erp_sale_items.quantity - erp_sale_items.quantity_received) as balance,erp_sale_items.option_id');
		
		$this->db->where('sale_id=',$sale_id);
		$this->db->from('sale_items');
		$result = $this->db->get()->result_array();
		if(sizeof($result)>0){
			return $result;
		}
		return false; 
		
	}
	
	public function getSaleOrder($sale_order_id){
		$this->db->select("sale_order.*, companies.name, companies.company,
			CASE erp_sale_order.order_status
			WHEN 'completed' THEN
				'Approved'
			WHEN 'rejected' THEN
				'Rejected'
			WHEN 'pending' THEN
				'Order'
			END AS status, tax_rates.rate AS tax,tax_rates.name as tax_name");
		$this->db->join('companies', 'sale_order.customer_id = companies.id', 'inner');
		$this->db->join('tax_rates', 'sale_order.order_tax_id = tax_rates.id', 'left');
		$q = $this->db->get_where('sale_order', array('sale_order.id' => $sale_order_id));
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
		
	}
	public function getDepositByID($sale_order_id){
		$this->db->select("sale_order.id,
                            sale_order.customer_id,
                            sale_order.date,
                            quotes.reference_no as qref,
                            sale_order.reference_no,
                            sale_order.biller,
                            companies.name as customer,
                            users.username as saleman,
                            sale_order.sale_status,
                            sale_order.grand_total,
                            COALESCE(SUM(erp_deposits.amount), 0) as deposit,
                            erp_sale_order.grand_total-COALESCE(SUM(erp_deposits.amount), 0) as balance,
                            sale_order.order_status")
				->join('companies', 'companies.id = sale_order.customer_id', 'left')
				->join('users', 'users.id = sale_order.saleman_by', 'left')
				->join('companies as delivery', 'delivery.id = sale_order.delivery_by', 'left')
				->join('deliveries', 'deliveries.sale_id = sale_order.id', 'left')
				->join('quotes', 'quotes.id = sale_order.quote_id', 'left')
                ->join('erp_deposits', 'erp_deposits.so_id = erp_sale_order.id', 'left')
				->group_by('sale_order.id');
		$q = $this->db->get_where('sale_order', array('sale_order.id' => $sale_order_id));
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function getSalePOS($sale_order_id){
		$this->db->select('sales.*, companies.name, companies.company');
		$this->db->join('companies', 'sales.customer_id = companies.id', 'inner');
		$q = $this->db->get_where('sales', array('sales.id' => $sale_order_id));
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
		
	}
	
	
	public function getSaleOrderItems($sale_order_id){
		
		$this->db->select('sale_order_items.*,product_variants.name,product_variants.qty_unit');
		$this->db->join('product_variants', 'product_variants.product_id = sale_order_items.product_id AND sale_order_items.option_id = product_variants.id', 'left');
		$this->db->from('sale_order_items');
		$this->db->where('erp_sale_order_items.quantity > erp_sale_order_items.quantity_received');
		$this->db->where('sale_order_items.sale_order_id',$sale_order_id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->result_array();
		}
		return false;
		
	}
	
	function getSaleOrderInvoice($sale_order_id = Null){

		$this->db->select("
							erp_sale_order.*,
							b.name,
							c.email,
							c.phone,
							c.street,
							c.village,
							c.sangkat,
							c.district,
							c.city,
							c.country,
							c.name as customer_name,
							c.phone as customer_phone,
							CASE erp_sale_order.order_status
							WHEN 'completed' THEN
								'Approved'
							WHEN 'rejected' THEN
								'Rejected'
							WHEN 'pending' THEN
								'Order'
							END AS status");
		$this->db->where('erp_sale_order.id',$sale_order_id);
		$this->db->where('c.group_name','customer');
		$this->db->where('b.group_name','biller');
		$this->db->join('erp_companies as c','erp_sale_order.customer_id = c.id');
		$this->db->join('erp_companies as b','erp_sale_order.biller_id = b.id');
		$this->db->from('erp_sale_order');
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	public function getSaleOrdItems($sale_order_id){
		$q = $this->db->get_where('sale_order_items', array('sale_order_id' => $sale_order_id));
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
		
	}
	
	public function getPOSOrdItems($sale_order_id){
		$q = $this->db->get_where('sale_items', array('sale_id' => $sale_order_id));
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
		
	}
	
	public function getProductPriceGroup($id, $group_price_id=null) {
		$this->db->select('product_prices.*, price_groups.name AS group_name, products.price as default_price, currencies.rate,product_variants.name AS variant_name, (
			SELECT
				rate
			FROM
				erp_currencies curr
			WHERE
				curr.code = "'.$this->site->get_setting()->default_currency.'"
		) AS setting_curr');
		$this->db->join('price_groups', 'price_groups.id = product_prices.price_group_id', 'left');
		$this->db->join('products', 'products.id = product_prices.product_id', 'left');
		$this->db->join('currencies', 'currencies.code = product_prices.currency_code', 'left');
		$this->db->join('product_variants', 'product_variants.id = product_prices.unit_id AND product_variants.product_id = product_prices.product_id', 'left');
		$this->db->where('product_prices.product_id', $id);
		if($group_price_id) {
			$this->db->where('product_prices.price_group_id', $group_price_id);
		}
		$q = $this->db->get('product_prices');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getOptPriceGroups($id) {
		$this->db->select('product_prices.*, price_groups.name AS group_name, products.price as default_price, currencies.rate, (
			SELECT
				rate
			FROM
				erp_currencies curr
			WHERE
				curr.code = "'.$this->site->get_setting()->default_currency.'"
		) AS setting_curr');
		$this->db->join('price_groups', 'price_groups.id = product_prices.price_group_id', 'left');
		$this->db->join('products', 'products.id = product_prices.product_id', 'left');
		$this->db->join('currencies', 'currencies.code = product_prices.currency_code', 'left');
		$this->db->where('product_id', $id);
		$this->db->group_by('product_prices.price_group_id');
		$q = $this->db->get('product_prices');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getProductPriceGroupId($id, $group_price_id){
		$this->db->select('product_prices.*, price_groups.name AS group_name, products.price as default_price, currencies.rate, (
			SELECT
				rate
			FROM
				erp_currencies curr
			WHERE
				curr.code = "'.$this->site->get_setting()->default_currency.'"
		) AS setting_curr');
		$this->db->join('price_groups', 'price_groups.id = product_prices.price_group_id', 'left');
		$this->db->join('products', 'products.id = product_prices.product_id', 'left');
		$this->db->join('currencies', 'currencies.code = product_prices.currency_code', 'left');
		$this->db->where('product_id', $id);
		$this->db->where('product_prices.price_group_id', $group_price_id);
		$q = $this->db->get('product_prices');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getSaleOrderItem($sale_order_id, $product_id = array()){
		$this->db->where_in('product_id', $product_id);
		$q = $this->db->get_where('sale_order_items', array('sale_order_id' => $sale_order_id));
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
		
	}
	
	public function getPOSSaleOrderItem($sale_id){
		$q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
		
	}
	
	
	public function updateSales($id, $data)
    {
        if ($this->db->update('sales', $data, array('id' => $id))) {
            return true;
        }
        return false;
    }
	
	public function getSale_Id($id){
		$q = $this->db->select('*')
					  ->get_where('loans', array('id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return FALSE;	 
	}
	public function getIndividualVariant($product_id,$product_option){
		$q = $this->db->get_where('erp_product_variants',array('id'=>$product_option,'product_id'=>$product_id));
		if($q->num_rows()>0){
			return $q->row();
		}
		return Null;
	}
	
	public function add_deposit($deposit){
		if($deposit) {
			$this->db->insert('deposits',$deposit); 
			if($this->db->affected_rows()>0){
				return true;
			}
		}
		return false; 
	}
	public function get_partialAmount($sale_order_id){
		$this->db->select('COALESCE(SUM(paid)) as partial_amount');
		$this->db->where('erp_sales.type_id',$sale_order_id);
		$this->db->from('erp_sales');
		$q = $this ->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	public function get_paidAmount($sale_order_id = Null){
		$this->db->select('COALESCE(SUM(paid)) as partial_amount');
		$this->db->where('erp_sale_order.id',$sale_order_id);
		$this->db->from('erp_sale_order');
		$q = $this ->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	public function updateOrderStatus($sale_order_id = Null){
		
		$data = array('sale_status' => 'completed');
		$this->db->where('id', $sale_order_id);
		$this->db->update('erp_sale_order', $data);
		if($this->db->affected_rows() > 0){
			return true;
		}
		return false;
	}
	
	public function getCurrentInterestByMonth() {
		$q = $this->db->select('interest')
						->get_where('loans', array('DATE_FORMAT(dateline,"%Y-%m-%d") <=' => date('Y-m-d')), 1);
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	/*=====================================chin local add=======================================*/
	public function getInvoiceDepositBySaleID($id = NULL) {
		$q = $this->db->get_where('deposits', array('sale_id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	public function updateDeposit($id, $data) {
		if($id && $data) {
			$this->db->update('deposits', $data, array('id' => $id));
			return true;
		}
		return false;
	}
	public function getProductByID($id = NULL, $warehouse_id = NULL) {
        $this->db->select('products.*, units.name as unit,warehouses_products.quantity AS qoh, products.unit as unit_id, warehouses_products.quantity as wh_qty');
        $this->db->join('units', 'units.id = products.unit', 'left');
		$this->db->join('warehouses_products', 'products.id = warehouses_products.product_id', 'left');
        $q = $this->db->get_where('products', array('products.id' => $id, 'warehouses_products.warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getSaleOrderInfo($sale_order_id=null){
		$this->db->select('sale_order.*, users.username'); 
		$this->db->join('users','sale_order.saleman_by = users.id');
		$q = $this->db->get_where('sale_order', array('sale_order.id' => $sale_order_id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
		
	}
	public function getDepositByPaymentID($id = null) {
		$q = $this->db->get_where('deposits', array('deposits.payment_id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function getDepositBySaleID($id)
    {
        $q = $this->db->get_where('deposits', array('sale_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return false;
    }
	
	public function deleteDeposit($id)
    {
        if ($id) {
            $this->db->delete('deposits', array('id' => $id));
            return true;
        }
        return FALSE;
    }
	
	public function getSaleOrderItemByID($id = NULL) {
		$q = $this->db->get_where('sale_order_items', array('id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	
	
	
	public function getDeliveriesByID($id = NULL) {
		$q = $this->db->get_where('deliveries', array('id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	public function getDeliveryItemsByDeliveryID($id = NULL) {
		$q = $this->db->get_where('delivery_items', array('delivery_items.delivery_id' => $id));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		return false;
	}
	public function getDelCostingLines($del_item_id, $product_id)
    {
		$this->db->select('costing.*, product_variants.qty_unit');
		$this->db->join('product_variants', 'product_variants.id=costing.option_id','left');
        $this->db->order_by('costing.id', 'asc');
        $q = $this->db->get_where('costing', array('costing.delivery_item_id' => $del_item_id, 'costing.product_id' => $product_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getDeliveryItemByID($id = NULL) {
		$q = $this->db->get_where('delivery_items', array('delivery_items.id' => $id));
		if ($q->num_rows() > 0) {
            return $q->row();
        }
		return false;
	}
	
	public function updateDelPurchaseItem($id, $qty, $del_item_id, $product_id = NULL, $warehouse_id = NULL, $option_id = NULL)
    {
		if ($delivery_item = $this->getDeliveryItemByID($del_item_id)) {
			
			$option_id = isset($delivery_item->option_id) && !empty($delivery_item->option_id) ? $delivery_item->option_id : NULL;
			if($option_id){
				$clause = array('product_id' => $delivery_item->product_id, 'warehouse_id' => $delivery_item->warehouse_id, 'option_id' => $option_id);
			}else{
				$clause = array('product_id' => $delivery_item->product_id, 'warehouse_id' => $delivery_item->warehouse_id);
			}
			
			if ($pi = $this->site->getPurchasedItem($clause)) {
				$quantity_balance = $pi->quantity_balance+$qty;
				$getproduct = $this->site->getProductByID($product_id);
				
				$new_arr_data = array(
					'product_id' 		=> $delivery_item->product_id,
					'product_code' 		=> $getproduct->code,
					'product_name' 		=> $delivery_item->product_name,
					'net_unit_cost' 	=> 0,
					'quantity' 			=> 0,
					'item_tax' 			=> 0,
					'warehouse_id' 		=> $delivery_item->warehouse_id,
					'subtotal' 			=> 0,
					'date' 				=> date('Y-m-d'),
					'status' 			=> '',
					'quantity_balance' 	=> abs($qty)
				);
				$this->db->insert('purchase_items', $new_arr_data);
			} else {
				$clause['purchase_id'] = NULL;
				$clause['transfer_id'] = NULL;
				$clause['quantity'] = 0;
				$clause['quantity_balance'] = $qty;
				$this->db->insert('purchase_items', $clause);
			}
		}
			
		if (! $delivery_item && $product_id) {
			$pr = $this->site->getProductByID($product_id);
			$clause = array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'option_id' => $option_id);
			if ($pr->type == 'standard') {
				if ($pi = $this->site->getPurchasedItem($clause)) {
					$quantity_balance = $pi->quantity_balance+$qty;
					
					$new_arr_data = array(
						'product_id' 		=> $pr->id,
						'product_code' 		=> $pr->code,
						'product_name' 		=> $pr->name,
						'net_unit_cost' 	=> $pr->cost?$pr->cost:0,
						'quantity' 			=> 0,
						'item_tax' 			=> 0,
						'warehouse_id' 		=> $warehouse_id,
						'subtotal' 			=> 0,
						'date' 				=> date('Y-m-d'),
						'status' 			=> '',
						'quantity_balance' 	=> abs($qty)
					);

					$this->db->insert('purchase_items', $new_arr_data);
					
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
							
							$combo_data = array(
								'product_id' => $combo_items->id,
								'product_code' => $combo_items->code,
								'product_name' => $combo_items->name,
								'net_unit_cost' => 0,
								'quantity' => 0,
								'item_tax' => 0,
								'warehouse_id' => $warehouse_id,
								'subtotal' => 0,
								'date' => date('Y-m-d'),
								'status' => '',
								'quantity_balance' => abs($qty*$combo_item->qty)
							);
							$this->db->insert('purchase_items', $combo_data);
							
							// $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), $clause);
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
	
	public function resetDeliveryActions($id)
    {
        $delivery 		= $this->getDeliveriesByID($id);
        $delivery_items = $this->getDeliveryItemsByDeliveryID($id);
        $this->db->delete('delivery_items', array('delivery_id' => $id));
        $this->db->delete('purchase_items', array('delivery_id' => $id));
        foreach ($delivery_items as $item) {
            $this->db->delete('inventory_valuation_details', array('field_id' => $item->id, 'type' => 'DELIVERY'));
        }
        return $delivery_items;
    }
	
	/*=====================================end local add========================================*/
	
	public function getAmountPaidbyCustomer($customer_id = NULL){
		$result = $this->db->select('(sum(erp_sales.grand_total) - sum(paid)) as amount, credit_limited')
						 ->from('erp_sales')
						 ->join('erp_companies','erp_companies.id = erp_sales.customer_id','left')
						 ->where('customer_id',$customer_id)
						 ->get()->row();
		return $result;
	}
	
	public function getTransferOwner($id = NULL) {
		
		$this->db->select('sales.grand_total as grand_total, sales.customer');
		$this->db->where('sales.id', $id);
		$this->db->from('sales');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
		
	}
	
	public function getCustomerPaid($sale_id = null, $customer_id = null) {
		if($sale_id && $customer_id) {
			$q = $this->db->select('SUM(paid_amount) as paid')
						->get_where('loans', array('sale_id' => $sale_id, 'customer_id' => $customer_id));
			if($q->num_rows() > 0) {
				return $q->row();
			}
		}
		return false;
	}
	
	public function addCustomerTransfer($data = array(), $payment = array()) {
		if($data) {
			if($this->db->insert('transfer_customers', $data)) {
				$transfer_owner_id = $this->db->insert_id();
				if($payment) {
					$payment['transfer_owner_id'] = $transfer_owner_id;
					if($this->db->insert('payments', $payment)) {
						if ($this->site->getReference('sp', $payment['biller_id']) == $payment['reference_no']) {
							$this->site->updateReference('sp', $payment['biller_id']);
						}
					}
				}
				return true;
			}			
		}
		return false;
	}
	
	public function getCustomerTransfersBySaleCustomerID($sale_id = NULL, $customer_id = NULL)
    {
		$this->db
			->select("loans.id, loans.period, 
					 loans.interest, loans.principle, loans.payment, 
					 loans.balance, loans.dateline, IF(erp_loans.old_date, erp_loans.old_date, NULL) AS old_date, loans.note,users.username,paid_date
					 ")
			->from('loans')
			->join('users','users.id=loans.created_by','LEFT')
			->where('sale_id', $sale_id)
			->where('customer_id', $customer_id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
	public function getTransferByID($id)
    {
        $q = $this->db->get_where('transfer_customers', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	########Combine Delivery##########
	
	public function getDeliveryItemsByItemIds($delivery_id = NULL)
	{
		
		$ids = array();
		$iids = '';
		$i=0;
		for($i=0;$i<count($delivery_id);$i++)
		{
			$ids[] = $delivery_id[$i];
			if($i == 0) {
				$iids = "'".$delivery_id[$i]."'";
			}else {
				$iids .= ",'".$delivery_id[$i]."'";
			}			
		}
		
		
		$response = $this->db
                 ->select('erp_sale_order_items.id,
						   erp_sale_order_items.sale_order_id,
						   erp_sale_order_items.product_id,
						   erp_sale_order_items.product_code,
						   erp_sale_order_items.product_name,
						   erp_sale_order_items.product_type,
						   erp_sale_order_items.wpiece,
						   erp_sale_order_items.option_id,
					       erp_sale_order_items.net_unit_price,
					       erp_sale_order_items.unit_price,
						   erp_sale_order_items.quantity_received,
						   erp_sale_order_items.quantity,
					       erp_sale_order_items.warehouse_id,
						   erp_sale_order_items.item_tax,
					       erp_sale_order_items.tax_rate_id,
					       erp_sale_order_items.tax,
					       erp_sale_order_items.discount,
					       erp_sale_order_items.item_discount,
						   erp_sale_order_items.subtotal,
						   erp_sale_order_items.serial_no,
						   erp_sale_order_items.real_unit_price,
					       erp_sale_order_items.product_noted,
					       erp_sale_order_items.group_price_id,
					       erp_sale_order_items.digital_id,
						   COALESCE((
								SELECT
									SUM(
										erp_delivery_items.quantity_received
									) AS qty_received
								FROM
									erp_delivery_items
								WHERE
									erp_delivery_items.product_id = erp_sale_order_items.product_id
								AND
									erp_delivery_items.wpiece = erp_sale_order_items.wpiece
								AND
									erp_delivery_items.delivery_id IN ('.$iids.')
							), 0) AS dqty_received,
						   COALESCE((
								SELECT
									SUM(
										erp_delivery_items.piece
									) AS qty_received
								FROM
									erp_delivery_items
								WHERE
									erp_delivery_items.product_id = erp_sale_order_items.product_id
								AND
									erp_delivery_items.wpiece = erp_sale_order_items.wpiece
								AND
									erp_delivery_items.delivery_id IN ('.$iids.')
							), 0) AS piece')
                 ->where_in('erp_delivery_items.delivery_id',$ids)
				 ->join('erp_delivery_items','erp_delivery_items.sale_id = erp_sale_order_items.sale_order_id AND erp_delivery_items.product_id = erp_sale_order_items.product_id', 'left')
                 ->from('erp_sale_order_items')
				 ->group_by('erp_sale_order_items.product_id, erp_sale_order_items.wpiece')
				 ->order_by('erp_delivery_items.delivery_id')
                 ->get()
                 ->result();
		
		if(sizeof($response)>0){
			return $response;
		}else{
			return false;
		}
	}
	
	
	public function getDeliveriesByIDs($delivery_ids = NULL)
    {
		
		$gp_id = array();
		for($i=0;$i<count($delivery_ids);$i++)
		{
			$gp_id[] = $delivery_ids[$i];
			
		}
		
		$this->db->select('erp_deliveries.*,erp_group_areas.areas_g_code as group_areas_id, SUM(erp_delivery_items.quantity_received) AS quantity, delivery_items.delivery_id as delivery_id,erp_sale_order.warehouse_id,erp_sale_order.shipping,erp_sale_order.order_discount,erp_sale_order.order_tax,erp_sale_order.customer_id as customer_id,erp_sale_order.payment_status,erp_sale_order.saleman_by,companies.name as company_name,erp_sale_order.order_tax_id,erp_sale_order.sale_status AS sale_order_status, erp_sale_order.biller_id,erp_sale_order.delivery_by,erp_sale_order.payment_term,erp_sale_order.order_discount_id');
		$this->db->from('deliveries');
		$this->db->join('delivery_items','erp_deliveries.id =delivery_items.delivery_id', 'left');
		$this->db->join('erp_sale_order','erp_deliveries.sale_id=erp_sale_order.id', 'left');
		$this->db->join('erp_companies','erp_sale_order.customer_id = erp_companies.id', 'left');
		$this->db->join('erp_group_areas','erp_group_areas.areas_g_code= erp_companies.group_areas_id', 'left');
		$this->db->where_in('erp_deliveries.id',$gp_id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
    }
	
	
	public function UpdateDeliveryMulti($deliver_ids_m = NULL,$sale_id=NULL){
		
	    $deli_num = explode(",",$deliver_ids_m);
		$up_deli = array();
		
		for($i=0;$i<count($deli_num);$i++)
		{
			$up_deli[] = $deli_num[$i];
			
		}
		$data = array('sale_status'=>'completed','issued_sale_id' => $sale_id);
		$this->db->where_in('id', $up_deli);
		$this->db->update('erp_deliveries', $data);
		if($this->db->affected_rows() > 0){
			return true;
		}
		return false;
		
	}

	public function getSaleOrdItemsDetail($sale_order_id = NULL) {
		$this->db->select('erp_sale_order_items.*,erp_product_variants.name as variant,erp_units.name as product_unit, products.details as details');
		$this->db->where('erp_sale_order_items.sale_order_id',$sale_order_id);
		$this->db->join('erp_product_variants','erp_sale_order_items.option_id = erp_product_variants.id','left');
		$this->db->join('erp_products','erp_sale_order_items.product_id = erp_products.id','left');
		$this->db->join('erp_units','erp_products.unit = erp_units.id', 'left');
		$this->db->from('erp_sale_order_items');
		$q = $this->db->get();
        if($q->num_rows()>0){
            foreach($q->result() as $result){
                $data[] = $result;
            }
            return $data;
        }
        return NULL;
	}
	public function getSaleOrdItemsDetailByID($sale_order_id = NULL) {
		$this->db->select('erp_sale_order_items.*,erp_products.*,erp_product_variants.name as variant,erp_units.name as product_unit');
		$this->db->where('erp_sale_order_items.sale_order_id',$sale_order_id);
		$this->db->join('erp_product_variants','erp_sale_order_items.option_id = erp_product_variants.id','left');
		$this->db->join('erp_products','erp_sale_order_items.product_id = erp_products.id','left');
		$this->db->join('erp_units','erp_products.unit = erp_units.id', 'left');
		$this->db->from('erp_sale_order_items');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
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
	
	public function getPaymentermID($id)
	{
		$q = $this->db->get_where('payment_term', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getSaleOrderById($sale_order_id){
		$this->db->select('sale_order.*');
		$q = $this->db->get_where('sale_order', array('sale_order.id' => $sale_order_id));
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function getSale_Order($id=null){
        $this->db->select('erp_sale_order.*, CONCAT(erp_users.first_name," ",erp_users.last_name) AS name,erp_companies.address,erp_companies.email,erp_companies.phone,erp_companies.company')
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
	public function getSaleItemsBySaleId($id){
        $this->db->select('erp_sale_items.*, CONCAT(erp_products.name,"<br/>",erp_products.details) AS pro_name,erp_units.name AS unit_name,erp_product_variants.name AS variant_name, erp_product_variants.qty_unit,, erp_sales.order_discount,erp_sales.grand_total, erp_sales.order_tax,erp_sales.paid, erp_sales.shipping')
                ->from('erp_sale_items')
                ->join('erp_sales','erp_sale_items.sale_id = erp_sales.id','left')
                ->join('erp_products','erp_sale_items.product_id = erp_products.id','left')
                ->join('erp_units','erp_products.unit = erp_units.id','left')
                ->join('erp_product_variants','erp_sale_items.option_id = erp_product_variants.id','left')
                ->where('erp_sales.id',$id);
        $q = $this->db->get();
        if($q->num_rows()>0){
            return $q->result();
        }
        return false;
    }
	
	public function checkrefer($id){
		$q = $this->db->get_where('erp_deliveries',array('id'=>$id),1);
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function getSaleByDeliveryID($id){
		$this->db->select('erp_sales.*,erp_companies.company,erp_companies.phone,erp_companies.address,erp_companies.email,CONCAT(erp_users.first_name," ",erp_users.last_name) as uname, erp_group_areas.areas_group as group_area,erp_sales.sale_status as status')
		->join('erp_companies','erp_companies.id=erp_sales.customer_id ','left')
		->join('erp_users','erp_users.id=erp_sales.saleman_by ','left')
		->join('erp_group_areas', 'erp_companies.group_areas_id = erp_group_areas.areas_g_code', 'left')
		->where('erp_sales.id',$id);
		$q = $this->db->get('erp_sales');
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function getDepositDeliveryByID($id){
		$this->db
				->select("sales.id, sales.date as date,erp_quotes.reference_no as q_no, sale_order.reference_no as so_no, sales.reference_no as sale_no, sales.biller, sales.customer, users.username AS saleman, sales.sale_status, sales.grand_total, (SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id  ) as deposit, COALESCE((erp_sales.paid - (SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id)),0) as paid, (ROUND(erp_sales.grand_total, 2)-erp_sales.paid) as balance, sales.payment_status")
				->join('users', 'users.id = sales.saleman_by', 'left')
				->join('sale_order', 'sale_order.id = sales.so_id', 'left')
				->join('payments', 'payments.sale_id = sales.id', 'left')
				->join('erp_quotes', 'erp_quotes.id = sales.quote_id', 'left')
				->join('companies', 'companies.id = sales.customer_id', 'left')
		->where('erp_sales.id',$id);
		$q = $this->db->get('erp_sales');
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}			
	public function getSaleByDeliveryIdbill($id){
		$this->db->select('erp_sales.*,erp_companies.company,erp_companies.phone,erp_companies.address,erp_companies.vat_no, erp_companies.invoice_footer as invoice')
		->join('erp_companies','erp_companies.id=erp_sales.biller_id ','left')
		->where('erp_sales.id',$id);
		$q = $this->db->get('erp_sales');
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function getDeliveryRefIdbill($id){
		$q = $this->db->get_where('erp_deliveries',array('issued_sale_id'=>$id),1);
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	public function getAllSaleItemID($id){

		$this->db->select("
							CONCAT(erp_products.code, ' - ', erp_products.name) as description,
							erp_products.product_details,
							erp_sale_items.quantity,
							erp_sale_items.unit_price,
							erp_sale_items.option_id,
							erp_product_variants.qty_unit,
							erp_product_variants.name as vname,
							erp_units.name as units
						")
		->join('erp_product_variants','erp_product_variants.id=erp_sale_items.option_id','left')
		->join('erp_products','erp_products.id=erp_sale_items.product_id','left')
		->join('erp_units','erp_units.id=erp_products.unit','left')
		->where('sale_id',$id);

		$q = $this->db->get('erp_sale_items');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function getAllSaleByDeliveryID($id){
		$this->db->select('erp_deliveries.*,erp_delivery_items.product_id,erp_delivery_items.delivery_id,erp_delivery_items.product_name,erp_delivery_items.quantity_received')
		->join('erp_deliveries','erp_deliveries.id=erp_delivery_items.delivery_id ','left')
		->where(array('erp_deliveries.issued_sale_id'=>$id));
		$q = $this->db->get('erp_delivery_items');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
				
			}
			return $data;
		}
		return false;
	}
	public function getAllSaleByDeliveryStateID($id){
		$this->db->select('erp_deliveries.*,erp_delivery_items.product_id,erp_delivery_items.delivery_id,erp_delivery_items.product_name,SUM(`erp_delivery_items`.`quantity_received`) as quantity_received')
		->join('erp_deliveries','erp_deliveries.id=erp_delivery_items.delivery_id ','left')
		->where(array('erp_deliveries.issued_sale_id'=>$id))
		->group_by('erp_delivery_items.product_name,DATE_FORMAT(date,"%Y-%m-%d")');
		$q = $this->db->get('erp_delivery_items');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getSaleByDeliveryID2($idd,$pid){
		$q = $this->db->get_where("erp_sale_items",array("sale_id"=>$idd,"product_id"=>$pid),1);
		if($q->num_rows() > 0){
			
			return $q->row();
		}
		return false;
	}
	
	public function assign_to_user($user_id=NULL,$so_id=NULL)
	{

		if($this->db->update('sales', array('assign_to_id' => $user_id), array('reference_no' => $so_id))){
			return true;
		}
		return false;
	}
	
	public function getQustatusByID($id) {
		$q = $this->db->select('SUM(quantity - quantity_received) as balance,quantity')
				 ->get_where('erp_quote_items', array('quote_id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	
	
	public function getCurCost($id) {
		$q = $this->db->select('cost')
		->get_where('erp_products', array('id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	##################################

	public function pos_sale($id=null, $wh = null){
        $this->db->select($this->db->dbprefix('sales').".id as id, 
			".$this->db->dbprefix('sales').".date,
			".$this->db->dbprefix('payments').".date as pdate,
			".$this->db->dbprefix('sales').".reference_no, biller.company, sales.customer, sales.sale_status , sales.grand_total, COALESCE((SELECT SUM(erp_return_sales.grand_total) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id), 0) as return_sale,	COALESCE((SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0) as paid, COALESCE((SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id), 0) as deposit, COALESCE((SELECT SUM(erp_payments.discount) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id), 0) as discount, (".$this->db->dbprefix('sales').".grand_total - COALESCE((SELECT SUM(erp_return_sales.grand_total) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id), 0) - COALESCE((SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0) - COALESCE((SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id), 0) - COALESCE((SELECT SUM(erp_payments.discount) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id), 0)) as balance, sales.payment_status")
            ->from('sales')
			->join('payments', 'payments.sale_id=sales.id', 'left')
			->join('erp_return_sales', 'erp_return_sales.sale_id = sales.id', 'left')
			->join('companies', 'companies.id=sales.customer_id', 'left')
			->join('companies as erp_biller', 'biller.id = sales.biller_id', 'inner')
			->where('erp_sales.id',$id);
			if($wh){
				$this->db->where_in('erp_sales.warehouse_id',$wh);
			}
        $q = $this->db->get();        
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
    }
	public function getCurrency(){
		$default_currency = $this->site->get_setting()->default_currency;
		$this->db->select("erp_currencies.*"); 
		$this->db->from("erp_currencies");
		$this->db->where(array("in_out"=>1,"code"=>$default_currency)); 
		$q = $this->db->get(); 
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	public function getFrequency()
	{
		$this->db->select("erp_frequency.*"); 
		$q = $this->db->get('erp_frequency');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false; 
	}
	
	public function getTerms()
	{
		$this->db->select("erp_terms.*"); 
		$q = $this->db->get('erp_terms');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false; 
	}
	
	public function getPrinciple()
	{
		$this->db->select("erp_term_types.*"); 
		$q = $this->db->get('erp_term_types');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false; 
	}
	
	public function getUSCurrency(){
		$this->db->select("erp_currencies.*"); 
		$this->db->from("erp_currencies");
		$this->db->where(array("in_out"=>1,"code"=>"USD")); 
		$q = $this->db->get(); 
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
public function getRielCurrency(){
		$this->db->select("erp_currencies.*"); 
		$this->db->from("erp_currencies");
		$this->db->where(array("in_out"=>1,"code"=>"KHM")); 
		$q = $this->db->get(); 
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}

	public function getAllDeliveriesAlerts($so_id)
    {
        $this->db
        	 ->select('product_name, product_code, quantity')
			 ->from('sale_order')
			 ->join('sale_order_items', 'sale_order_items.sale_order_id = sale_order.id', 'left')
			 ->group_by('sale_order_items.id');
		
		$this->db->where('sale_order.id',$so_id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $result){
				$data[] = $result;
			}
			return $data;
		}
		return NULL;
		
    }
	
	public function getPrinciple_id($id=null)
	{
		$this->db->select("principles.*"); 
		$this->db->from("principles");
		$this->db->where("principles.term_type_id",$id);
		$q = $this->db->get();
		if($q->num_rows() > 0){
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
	
	public function getDepositBySo($so_id=null,$company_id=null)
	{
		$this->db->select("SUM(amount) AS deposit_amt, GROUP_CONCAT(note) AS note"); 
		$this->db->from("deposits");
		$this->db->where(array("so_id"=>$so_id,"company_id"=>$company_id)); 
		$q = $this->db->get(); 
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	public function Addloans($loans=array(),$sale_id=null,$loan_info=array(),$update=null)
	{
		
	  if($loan_info['frequency']!=null){
		  
		if($loans){
				
			if($update)
			{
			   $this->db->delete('loans', array('sale_id' => $sale_id));
			}
			
			foreach($loans as $loan){
				$this->db->insert('loans', $loan);
			}
			$term_id = $this->term_id($loan_info['term']);
			$loan_info['term_id'] = $term_id->id;
			$this->db->update('sales',$loan_info, array('id' => $sale_id));
			return true;
		}
	  }
	}
	
	public function term_id($day)
	{
		 $this->db->select('id')
                 ->from('terms')
                 ->where_in("day", $day);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getCustomerMakup($customer_group_id=null,$pro_id=null,$sub_cate=null)
	{
		$this->db->select("categories_group.percent");
		$this->db->from("categories_group");
		if($sub_cate==0)
		{
			$this->db->join('products','products.category_id = categories_group.cate_id','left');	
		}else{
			$this->db->join('products','products.subcategory_id = categories_group.sub_cate','left');	
		}
		$this->db->join('customer_groups','customer_groups.id = categories_group.customer_group_id','left');
		$this->db->where("categories_group.customer_group_id",$customer_group_id); 
		$this->db->where("products.id",$pro_id);
		if($sub_cate==1)
		{
			$this->db->where("categories_group.sub_cate !=",""); 	
		}
		 
		$q = $this->db->get(); 
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	
	public function getLoanBySaleId($id = NULL) {
		$q = $this->db->get_where('loans', array('sale_id' => $id));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
	public function getLoanBySaleOrderId($id = NULL) {
		$q = $this->db->get_where('order_loans', array('sale_id' => $id));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
	public function getSalesBySaleId($id = NULL) {
		$this->db
        	 ->select('terms.description, sales.*')
			 ->from('sales')
			 ->join('terms', 'terms.id = sales.term_id', 'left');
		$this->db->where('sales.id',$id);
		$q = $this->db->get();
			if($q->num_rows()>0){
			return $q->row();
		}
	
    }

    public function getSaleOrderBySaleOrderId($id = NULL) {
		$this->db
        	 ->select('terms.description, sale_order.*')
			 ->from('sale_order')
			 ->join('terms', 'terms.id = sale_order.term_id', 'left');
		$this->db->where('sale_order.id',$id);
		$q = $this->db->get();
			if($q->num_rows()>0){
			return $q->row();
		}
	
    }
	
	public function getLoanByDataSaleId($id = NULL,$period_id= NULL) {
		
		$this->db->select("*"); 
		$this->db->from("loans");
		$this->db->where("loans.sale_id",$id); 
		$this->db->where_in("loans.period",$pro_id); 
		
		$q = $this->db->get();//_where('loans', array('sale_id' => $id,'period' => $period_id));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
	

	public function getProductVariantByOptionID($id = NULL) {
		$this->db
			 ->select('qty_unit, name')
			 ->from('product_variants')
			 ->where('product_id', $id)
			 ->order_by('qty_unit', 'desc');
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
	}
	
	public function getTotalInterest($sale_id)
	{
		$q = $this->db->get_where('sales', array('id' => $sale_id));
		if($q->num_rows() > 0) {
			
			return $q->row();
		}
		return FALSE;
	}
	
	public function UpdateFunctionLoan($data=null,$interest=null,$sale_id=null,$period=null)
	{
		if($this->db->update('loans', $data, array('sale_id' => $sale_id,'period'=>$period)))
		{
			$old_interest = $this->getTotalInterest($sale_id);
			$sum_interest = ($old_interest->interest+$interest);
			
			$this->db->update('sales', array('total_interest'=>$sum_interest), array('id' => $sale_id));
			return TRUE;
		}
	  return FALSE;
	}
	
	public function getAllSORef()
	{
		$q = $this->db->get('sale_order');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
	public function getDeposit($id){
		$this->db->select('erp_sales.deposit_so_id as id,erp_sales.reference_no')
				->where('erp_sales.id',$id);
		$q = $this->db->get('erp_sales');
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;	
	}
	public function getSaleinform($id = null){
		// $this->erp->print_arrays($id);
		$this->db->select('erp_sale_order.*, erp_companies.address,erp_companies.phone,erp_companies.email,erp_companies.invoice_footer,CONCAT(erp_users.first_name," ",erp_users.last_name) as att,erp_sales.grand_total as total_amount,erp_sales.note as sale_note,erp_tax_rates.name, erp_tax_rates.rate')
			->join('erp_companies','erp_sale_order.biller_id = erp_companies.id','left')
			->join('erp_users','erp_sale_order.saleman_by = erp_users.id','left')
			->join('erp_sales','erp_sales.deposit_so_id = erp_sale_order.id','left')
			->join('erp_tax_rates','erp_sale_order.order_tax_id = erp_tax_rates.id','left')
			->where('erp_sale_order.id',$id);
		$q = $this->db->get('erp_sale_order');
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;	
	}
	

	public function getItem($id){
		$this->db->select("
							CONCAT(erp_products.code, ' - ', erp_products.name) as description,
							erp_products.product_details,
							erp_sale_order_items.quantity,
							erp_sale_order_items.unit_price,
							erp_sale_order_items.option_id,
							erp_product_variants.qty_unit,
							erp_product_variants.name as vname,
							erp_units.name as units
						")
		->join('erp_product_variants','erp_product_variants.id=erp_sale_order_items.option_id','left')
		->join('erp_products','erp_products.id=erp_sale_order_items.product_id','left')
		->join('erp_units','erp_units.id=erp_products.unit','left')
		->where('erp_sale_order_items.sale_order_id',$id);

		$q = $this->db->get('erp_sale_order_items');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getLoansByIDs($ids = array()) {
		$this->db->where_in('id', $ids);
		$q = $this->db->get('loans');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function updatePaymentDate($data = array()) {
		if($data) {
			foreach($data as $dd) {
				$this->db->update('loans', array('dateline' => $dd["dateline"]), array('id' => $dd["id"]));
			}
			return true;
		}
		return false;
	}
	
	public function getLastPaidPeriodBySaleID($id = NULL) {
		$this->db->select('MAX(period) as period, SUM(payment) as total_payment, SUM(interest) as total_interest');
		$q = $this->db->get_where('loans', array('sale_id' => $id, 'paid_amount > ' => 0));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function updateLoanTerm($id = null, $data = array()) {
		if($id && $data) {
			if($this->db->delete('loans', array('sale_id' => $id, 'paid_amount <= ' => 0))) {
				$sales = $this->site->getSaleByID($id);
				$grand_total = $sales->paid;
				$total_interest = $sales->total_interest;
				foreach($data as $dt){
					$grand_total += $dt['payment'];
					$total_interest += $loan['interest'];
					$this->db->insert('loans', $dt);
				}
				$this->db->update('sales', array('grand_total' => $grand_total, 'total_interest' => $total_interest), array('id' => $id));
				return true;
			}
		}
		return false;
	}
	
	public function getOwnedLoanBySaleID($id) {
		$this->db->select('COUNT(erp_loans.id) as loan_num, SUM(erp_loans.principle) as total_principle');
		$q = $this->db->get_where('loans', array('sale_id' => $id, 'paid_amount' => '0'));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function leftTerm($id = NULL) {
		$this->db->where(array('sale_id' => $id, 'paid_amount <=' => 0));
		$q = $this->db->get('loans');
		if ($q->num_rows() > 0) {
            return $q->num_rows();
        }
        return FALSE;
	}
	
	public function getLoanRate($id = NULL)
	{
		$this->db->select('rated');
		$q = $this->db->get_where('loans', array('sale_id' => $id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
	}

	public function getProductSale($id = null){
		$this->db->select('erp_sales.*,
					erp_sale_items.subtotal,
							products.cf1,
							products.cf2,
							products.cf3,
							products.cf4,
							products.cf5,
							products.cf6,')
				->from('erp_sales')
				->join('erp_sale_items','erp_sale_items.sale_id = erp_sales.id','left')
				->join('erp_products','erp_sale_items.product_id = erp_products.id','left')
				->where('erp_sales.id',$id);
		$q = $this->db->get();
		if($q->num_rows() > 0) {
			return $q->row();
		}
	}
	// public function getProductSaleOrder($id = null){
	// 	$this->db->select('*')
	// 			->from('erp_sale_order')
	// 			->join('erp_sale_order_items','erp_sale_order_items.sale_order_id = erp_sale_order.id','left')
	// 			->join('erp_products','erp_sale_order_items.product_id = erp_products.id','left')
	// 			->where('erp_sale_order.id',$id);
	// 	$q = $this->db->get();
	// 	if($q->num_rows() > 0) {
	// 		return $q->row();
	// 	}
	// }
	
	public function getPaidBySale_id($sale_id=null, $payment_id=null){
    	$this->db->select("SUM(erp_payments.amount) as amount")
    			->from("erp_payments")
    			->where("erp_payments.sale_id",$sale_id);
		if($payment_id) {
			$this->db->where("erp_payments.id", $payment_id);
		}
    	$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getPurchaseItemExDateByID($id){
		$this->db->select('erp_purchase_items.expiry');
		$this->db->from('erp_purchase_items');
		$this->db->where('erp_purchase_items.id',$id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	public function getProductExpireDate($product_id, $warehouse_id){
		$currDate = date("Y/m/d");
		$condition = array('product_id'=>$product_id,'warehouse_id'=>$warehouse_id);
		$this->db->select('erp_purchase_items.id,erp_purchase_items.product_id,erp_purchase_items.expiry,
		erp_purchase_items.warehouse_id,
		SUM(quantity_balance) as quantity_balance');
		$this->db->from('erp_purchase_items');
		$this->db->where($condition);
		$this->db->where('erp_purchase_items.expiry >= ', $currDate);
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
	
	public function getAllProductExpireDate($product_id, $warehouse_id){
		$currDate = date("Y/m/d");
		$condition = array('product_id'=>$product_id,'warehouse_id'=>$warehouse_id);
		$this->db->select('erp_purchase_items.id,erp_purchase_items.product_id,erp_purchase_items.expiry,
		erp_purchase_items.warehouse_id,
		SUM(quantity_balance) as quantity_balance');
		$this->db->from('erp_purchase_items');
		$this->db->where($condition);
		$this->db->where('erp_purchase_items.expiry >= ', $currDate);
		$this->db->group_by('erp_purchase_items.expiry');
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	
	public function getProductExpireDate_old($product_id, $warehouse_id){
		$currDate = date("Y/m/d");
		$condition = array('product_id'=>$product_id,'warehouse_id'=>$warehouse_id);
		$this->db->select('erp_purchase_items.id,erp_purchase_items.product_id,erp_purchase_items.expiry');
		$this->db->from('erp_purchase_items');
		$this->db->where($condition);
		$this->db->where('erp_purchase_items.expiry >= ', $currDate);
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getReqestQtyByCode($option_id,$pquantity){
		$this->db->select('erp_product_variants.qty_unit')
				->from('erp_product_variants')
				->where('erp_product_variants.id',$option_id);
		$q = $this->db->get();
		if($q->num_rows()>0){
			$qty_unit = $q->row()->qty_unit;
			$request_quantity = $pquantity * $qty_unit;
			return $request_quantity;
		}
		return $pquantity;
	}
	
	public function getCurrentStockQuantityByDate($expiry,$product_code,$warehouse_id){
		$condition = array(
			'product_code'=>$product_code,
			'warehouse_id'=>$warehouse_id,
			'expiry' => $expiry
		);
		
		$this->db->select('erp_purchase_items.id,erp_purchase_items.product_id,erp_purchase_items.expiry,
		erp_purchase_items.warehouse_id,
		SUM(quantity_balance) as quantity_balance');
		$this->db->from('erp_purchase_items');
		$this->db->where($condition);
		
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	public function getCurrentStockQuantityByExpDate($expiry,$product_id,$warehouse_id){
		$condition = array(
			'product_id'=>$product_id,
			'warehouse_id'=>$warehouse_id,
			'expiry' => $expiry
		);
		
		$this->db->select('erp_purchase_items.id,erp_purchase_items.product_id,erp_purchase_items.expiry,
		erp_purchase_items.warehouse_id,
		SUM(quantity_balance) as quantity_balance');
		$this->db->from('erp_purchase_items');
		$this->db->where($condition);
		
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->row();
		}
		return false;
	}
	
	public function getAllCurrentStockQuantityByDate($product_id,$warehouse_id){
		$condition = array(
			'product_id'=>$product_id,
			'warehouse_id'=>$warehouse_id
		);
		$this->db->select('erp_purchase_items.id,erp_purchase_items.product_id,erp_purchase_items.expiry,
		erp_purchase_items.warehouse_id,
		SUM(quantity_balance) as quantity_balance');
		$this->db->from('erp_purchase_items');
		$this->db->where($condition);
		$this->db->group_by('erp_purchase_items.expiry');
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
			
	}
	
	public function getExpiryDateByID($exp_id){
		$this->db->select('erp_purchase_items.expiry')
			->from('erp_purchase_items')
			->where('erp_purchase_items.id',$exp_id);
		$expiry = $this->db->get();
		if($expiry->num_rows()>0){
			return $expiry->row();
		}
		return false;
	}
	

	
	public function getCurrentStockAndRequestQty($exp_id,$option_id,$pquantity,$product_code,$warehouse_id){
		$this->db->select('erp_purchase_items.expiry')
			->from('erp_purchase_items')
			->where('erp_purchase_items.id',$exp_id);
		$expiry = $this->db->get()->row()->expiry;
		$request_quantity = $this->getReqestQtyByCode($option_id,$pquantity);
		$currentStockQuantity = $this->getCurrentStockQuantityByDate($expiry,$product_code,$warehouse_id)->quantity_balance;
		$curStockAndRequestQty = array(
			'request_quantity' => $request_quantity,
			'currentStockQuantity' => $currentStockQuantity
		);
		
		if($curStockAndRequestQty != NULL){
			return $curStockAndRequestQty;
		}
		return false;
		
	}
	public function getQuantityBalanceBySaleID($sale_id = NULL) {
		$this->db->select('SUM(erp_sale_items.quantity - erp_return_items.quantity) as quantity');
		$this->db->join('erp_return_items', 'erp_sale_items.sale_id = erp_return_items.sale_id', 'LEFT');
		$q = $this->db->get_where('erp_sale_items', array('erp_sale_items.sale_id' => $sale_id));
		
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	public function getAllInvoiceReItems($sale_id)
    {
        $this->db->select("sale_items.*,
        					tax_rates.code as tax_code,
        					tax_rates.name as tax_name,
        					tax_rates.rate as tax_rate,
        					(CASE WHEN erp_products.unit = 0 THEN erp_products.unit ELSE erp_units.name END) as uname,
        					products.details as details,
        					product_variants.name as variant,
        					product_variants.qty_unit,
        					units.name as unit,
        					products.promotion,
        					products.promo_price,
        					categories.name AS category_name,
        					products.start_date,
        					products.end_date,
							sale_items.product_noted, products.name_kh,
							products.currentcy_code,
							(erp_sale_items.quantity - SUM(COALESCE(erp_return_items.quantity, 0))) AS bqty
        				")
            ->join('return_items', 'sale_items.id = return_items.sale_item_id', 'left')
            ->join('products', 'products.id=sale_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->join('units', 'units.id = products.unit', 'left')
            ->group_by('sale_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_items.sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getVariantName($pro_id=null,$pro_var_id=null)
	{
		$this->db->select('name');
        $q = $this->db->get_where('product_variants', array('product_id' => $pro_id,'id'=>$pro_var_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function AddJoinLease($jl_data=array(),$sale_id=null,$jl_id=null)
	{
		if($jl_data!=null){
			if($jl_id){	
					$this->db->update('companies', $jl_data,array('id',$jl_id));
			}else{
				if($this->db->insert('companies', $jl_data))
				{
					$jl_id = $this->db->insert_id();
				}
			}
			
			$this->db->update('sales', array('join_lease_id' => $jl_id), array('id' => $sale_id));
			return true;
		}
		
	}
	
	function getOrderLoan($so_id=null)
	{
		$this->db->select('term,interest_rate,frequency,depreciation_type,principle_type,join_lease_id,term_id,down_amount,installment_date,principle_term,principle_amount');
		$q = $this->db->get_where('erp_sale_order', array('erp_sale_order.id' => $so_id));
		
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	
	public function jl_data($jl_id=null)
	{
		$this->db->select("companies.id,companies.name,companies.phone,companies.gender,companies.date_of_birth,companies.cf1 as identify_card,address");
		$q = $this->db->get_where('companies', array('companies.id' => $jl_id));
		
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function LoanRated($so_id=null)
	{
		$this->db->select("rated");
		$q = $this->db->get_where('order_loans', array('order_loans.sale_id' => $so_id));
		
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function getDigitalProducts($id)
	{
		$this->db->select('products.id, start_date, end_date, code, name, type, cost, warehouses_products.product_id, warehouses_products.quantity AS qoh, warehouses_products.quantity, price, tax_rate, tax_method, image, promotion, promo_price, product_details, details, subcategory_id, cf1, COALESCE((SELECT GROUP_CONCAT(sp.`serial_number`) FROM erp_serial as sp WHERE sp.product_id='.$this->db->dbprefix('products').'.id), "") as sep')
				 ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
				 ->join('digital_items', 'digital_items.product_id = products.id', 'left')
				 ->where('digital_items.digital_pro_id', $id)
				 ->group_by('products.id');
		$q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	
	public function getSaleDeposit($id) {
		$this->db->select("SUM(erp_payments.amount) as deposit");
		$q = $this->db->get_where('payments', array('sale_id' => $id, 'paid_by' => 'deposit'));
		if($q->num_rows() > 0) {
			return $q->row()->deposit;
		}
		return false;
	}
	
	public function getDownPayment($id) {
		$this->db->select("SUM(erp_payments.amount) as down");
		$q = $this->db->get_where('payments', array('sale_id' => $id,'is_down_payment'=>'1'));
		if($q->num_rows() > 0) {
			return $q->row()->down;
		}
		return false;
	}
	
	public function getSaleLoan($sale_id)
	{
		$this->db->select('term,interest_rate,frequency,depreciation_type,principle_type,join_lease_id,term_id,down_amount,installment_date,principle_term,principle_amount');
		$q = $this->db->get_where('erp_sales', array('erp_sales.id' => $sale_id));
		
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
	
	public function getInvoiceItemByID($id,$digital_id){
		$this->db->select("erp_sale_items.*,units.name as uname,erp_product_variants.name as vname");
		$this->db->join("erp_products","erp_products.id=erp_sale_items.product_id","LEFT");
		$this->db->join("units","units.id=erp_products.unit","LEFT");
		$this->db->join("erp_product_variants","erp_product_variants.id=erp_sale_items.option_id","LEFT");
		$this->db->where("sale_id",$id);
		$this->db->where("digital_id",$digital_id);
		$q = $this->db->get('erp_sale_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		
	}
	
	public function getInvoiceItemBydigital_id($id){
		$this->db->select("digital_id");
		$this->db->where("sale_id",$id);
		$this->db->group_by("digital_id");
		$q = $this->db->get('erp_sale_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
		
	}
	
	
	public function getOwedAmountById($id=array())
	{
		$this->db->select("SUM(owed) as owed,paid_interest_status");
		$this->db->where_in("id",$id);
		$q = $this->db->get('loans');
		if ($q->num_rows() > 0) {  
            return $q->row();
        }
	}
	
	public function getPaidAmount($sale_id=null,$loan_id=array())
	{
		$this->db->select("payment,interest");
		$this->db->where("sale_id",$sale_id);
		$this->db->where_in("id",$loan_id);
		$q = $this->db->get('loans');
		if ($q->num_rows() > 0) {  
            return $q->result();
        }
	}
	public function getReturnSaleBySID($sale_id = NULL) {
		$this->db->select("SUM(COALESCE(grand_total, 0)) as returned, SUM(COALESCE(paid, 0)) as refunded");
		$q = $this->db->get_where('return_sales', array('sale_id' => $sale_id));
		if ($q->num_rows() > 0) {  
            return $q->row();
        }
		return false;
	}
	public function getSaleDiscounts($sale_id = NULL) {
		$this->db->select("SUM(COALESCE(discount, 0)) as discounted");
		$q = $this->db->get_where('payments', array('sale_id' => $sale_id));
		if ($q->num_rows() > 0) {  
            return $q->row()->discounted;
        }
		return false;
	}
	
	public function getPendingSOQTYByProductID($product_id)
    {
        $this->db->select("SUM((COALESCE(erp_sale_order_items.quantity, 0) * COALESCE(erp_product_variants.qty_unit, 1)) - (COALESCE(erp_sale_order_items.quantity_received, 0) * COALESCE(erp_product_variants.qty_unit, 1))) AS psoqty")
            ->join('sale_order', 'sale_order_items.sale_order_id = sale_order.id', 'inner')
			->join('product_variants', 'sale_order_items.option_id = product_variants.id', 'left')
            ->where('sale_order_items.product_id', $product_id)
            ->where('sale_order.order_status', 'completed')
			->where("(erp_sale_order.sale_status ='order' OR erp_sale_order.delivery_status <> 'completed')");
        $q = $this->db->get('sale_order_items');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getRe_Loan($sale_id = NULL)
    {
        $this->db->select("SUM(principle) AS loan_amount,SUM(paid_amount - (IF (paid_amount > 0, interest, 0))) AS paid_amount")
				 ->where('loans.sale_id', $sale_id);
        $q = $this->db->get('loans');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getDownPaymentById($sale_id = NULL)
    {
        $this->db->select("id,paid_by,bank_account,reference_no")
				 ->where('payments.sale_id', $sale_id)
				 ->where('payments.is_down_payment',1);
        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getReturnItems($id){
		$q = $this->db->get_where('return_items', array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	function getLastQueue($date, $biller_id){
		$this->db->select("DATE_FORMAT(date,'%d') as date, biller_id, queue")
				 ->from('sales')
				 ->where( array("DATE_FORMAT(date,'%Y-%m-%d')" => $date, "biller_id" => $biller_id) )
				 ->order_by('id','DESC'); 
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getDeliveriesItemByID($id = NULL, $product_id = NULL) {
		$q = $this->db->get_where('delivery_items', array('delivery_id' => $id, 'product_id' => $product_id));
		if($q->num_rows() > 0) {
			return $q->row();
		}
		return false;
	}
}
