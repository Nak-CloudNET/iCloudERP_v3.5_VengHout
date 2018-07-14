<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sale_order_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
		$this->load->model('quotes_model');
    }

    /*==============================Chin local updated====================================*/
    public function addSaleOrder($data, $products)
    {
		//$this->erp->print_arrays($data, $products);
		if(isset($data) AND !empty($data) and isset($products) AND !empty($products)){
			$this->db->insert('sale_order',$data);
			$sale_order_id = $this->db->insert_id();
			
			if ($this->site->getReference('sao',$data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('sao',$data['biller_id']);
			}
            $data['id'] = $data['id'] ? $data['id'] : 0;
			if ($data['id']) {
				$this->db->update('quotes', array('issue_invoice' => 'completed'), array('id' => $data['id']));
			}

			if($sale_order_id>0){
				$status = false;
				foreach($products as $product){
                    $product['serial_no'] = $product['serial_no'] ? $product['serial_no'] : 0;
                    $product['expiry'] = $product['expiry'] ? $product['expiry'] : null;
                    $product['expiry_id'] = $product['expiry_id'] ? $product['expiry_id'] : 0;
					$prod = array(
						'sale_order_id' => $sale_order_id,
						'product_id' => $product['product_id'],
						'product_code' => $product['product_code'],
						'product_name' => $product['product_name'],
						'product_type' => $product['product_type'],
						'option_id' => $product['option_id'],
						'net_unit_price' => $product['net_unit_price'],
						'unit_price' => $product['unit_price'],
						'quantity' => $product['quantity'],
						'warehouse_id' => $product['warehouse_id'],
						'item_tax' => $product['item_tax'],
						'group_price_id'=>$product['group_price_id'],
						'tax_rate_id' => $product['tax_rate_id'],
						'tax' => $product['tax'],
						'piece' => $product['piece'],
						'wpiece' => $product['wpiece'],
						'discount' => $product['discount'],
						'item_discount' => $product['item_discount'],
						'subtotal' => $product['subtotal'],
						'serial_no' => $product['serial_no'],
						'real_unit_price' => $product['real_unit_price'],
						'product_noted' => $product['product_noted'],
						'expiry' => $product['expiry'],
						'expiry_id' => $product['expiry_id'],
						'digital_id' => $product['digital_id'],
						'price_id' => $product['price_id']

					);
					
					if($this->db->insert('sale_order_items',$prod)){
						$insert_id = $this->db->insert_id();
					}
				}
				if($insert_id == true){
					return $sale_order_id;
				}
				
			}
			return false;
		
		}
	}
	/*==================================end local updated===============================*/
	
	public function add_deposit($deposit){
		$this->db->insert('deposits',$deposit); 
		if($this->db->affected_rows()>0){
			return true;
		}
		return false; 
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


    public function getInvoiceByIDs($id=null,$wh=null)
    {
        $this->db->select("sale_order.id, sale_order.date, sale_order.reference_no, sale_order.biller, companies.name AS customer, users.username AS saleman,delivery.name as delivery_man,grand_total, paid,(grand_total-paid) as balance")
				->from('sale_order')
				->join('companies', 'companies.id = sale_order.customer_id', 'left')
				->join('users', 'users.id = sale_order.saleman_by', 'left')
				->join('companies as delivery', 'delivery.id = sale_order.delivery_by', 'left')
				->join('deliveries', 'deliveries.sale_id = sale_order.id', 'left')		
                ->where('sale_order.opening_ar!=','2')
				->where("sale_order.id",$id)
				->group_by('sale_order.id');
				if($wh){
					$this->db->where_in('erp_sale_order.warehouse_id',$wh);
				}
				
				$q = $this->db->get();
         if ($q) {
           return $q->row();
        }
        return FALSE;
    }
	 public function getInvoice()
    {
	
		$this->db->select("sale_order.id, sale_order.date, sale_order.reference_no, sale_order.biller, companies.name AS customer, users.username AS saleman,delivery.name as delivery_man,grand_total, paid,(grand_total-paid) as balance")
				 ->from('sale_order')
				 ->join('companies', 'companies.id = sale_order.customer_id', 'left')
				 ->join('users', 'users.id = sale_order.saleman_by', 'left')
				 ->join('companies as delivery', 'delivery.id = sale_order.delivery_by', 'left')
				 ->join('deliveries', 'deliveries.sale_id = sale_order.id', 'left')
				 ->group_by('sale_order.id');
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			return $q->result();
		}
		return FALSE;
	}
	
	public function deleteSaleOrderByID($sale_order_id = null){
		$this->db->delete('erp_sale_order', array('id' => $sale_order_id));
		if($this->db->affected_rows()>0){
			$this->db->delete('erp_sale_order_items', array('sale_order_id' => $sale_order_id));
			if($this->db->affected_rows()>0){
				return true;
			}
		}
		return false;
	}
	
	public function getSaleOrder($sale_order_id=null){
		$q = $this->db->get_where('erp_sale_order',array('id'=>$sale_order_id));
		if($q->num_rows()>0){
			return $q->row();
		}
		return null;
	}
	public function getCompanyByID($id){
		$this->db->select("erp_companies.*");
		$this->db->join("erp_companies","erp_companies.id = erp_sale_order.customer_id","left");
		$this->db->where('erp_sale_order.id', $id);
		$q = $this->db->get('erp_sale_order');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getCustomersByArea($area){		
		$this->db->select('id as id, CONCAT(name ," (",company, ")" ) as text');
		$q = $this->db->get_where('companies', array('group_name' => 'customer','group_areas_id' => $area));
        if($q->num_rows() > 0) {
			return $q->result();
		}
		return false;
	}
	public function getSaleOrderItems($sale_order_id=null){
		$q = $this->db->get_where('erp_sale_order_items',array('sale_order_id'=>$sale_order_id));
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return null;
	}
	
	/*==================================chin local add==============================*/
	public function getAuthorizeSaleOrder($id) {
		if($id) {
			$this->db->update('sale_order', array('order_status' => 'completed'), array('id' => $id));
			return true;
		}
		return false;
	}
	public function getunapproved($id) {
		if($id) {
			$this->db->update('sale_order', array('order_status' => 'pending'), array('id' => $id));
			return true;
		}
		return false;
	}
	public function getrejected($id) {
		if($id) {
			$this->db->update('sale_order', array('order_status' => 'rejected'), array('id' => $id));
			return true;
		}
		return false;
	}
	
	public function getProductByID($id = NULL, $warehouse_id = NULL) {
        $this->db->select('products.*, units.name as unit, products.unit as unit_id, warehouses_products.quantity as wh_qty');
        $this->db->join('units', 'units.id = products.unit', 'left');
		$this->db->join('warehouses_products', 'products.id = warehouses_products.product_id', 'left');
        $q = $this->db->get_where('products', array('products.id' => $id, 'warehouses_products.warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	/*==================================end local add==============================*/
	
	public function getInvoiceByID($id)
    {
		$this->db->select("sale_order.*, companies.name AS customer, users.username AS saleman,delivery.name as delivery_man, sale_order.grand_total, paid,(erp_sale_order.grand_total-paid) as balance, quotes.reference_no AS quotation_no, CASE erp_sale_order.order_status
			WHEN 'completed' THEN
				'Approved'
			WHEN 'rejected' THEN
				'Rejected'
			WHEN 'pending' THEN
				'Order'
			END AS status,COALESCE (SUM(erp_deposits.amount), 0) AS deposit,
			erp_sale_order.grand_total - COALESCE (SUM(erp_deposits.amount), 0) AS balance")
				 ->join('companies', 'companies.id = sale_order.customer_id', 'left')
				 ->join('users', 'users.id = sale_order.saleman_by', 'left')
				 ->join('companies as delivery', 'delivery.id = sale_order.delivery_by', 'left')
				 ->join('deliveries', 'deliveries.sale_id = sale_order.id', 'left')
				 ->join('quotes', 'sale_order.quote_id = quotes.id', 'left')
				 ->join('deposits', 'erp_deposits.so_id = erp_sale_order.id', 'left')
				 ->group_by('sale_order.id');
		$q = $this->db->get_where('sale_order', array('sale_order.id' => $id));
		if ($q->num_rows() > 0) {
			return $q->row();
		}
		return FALSE;
		
    }
	
	public function getAllInvoiceItems($sale_id)
    {
        $this->db->select('sale_order_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, (CASE WHEN erp_products.unit = 0 THEN erp_products.unit ELSE erp_units.name END) as uname, products.details as details, product_variants.name as variant, products.unit, products.promotion, categories.name AS category_name')
            ->join('products', 'products.id=sale_order_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_order_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_order_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->join('units', 'units.id = products.unit', 'left')
            ->group_by('sale_order_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_order_items', array('sale_order_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getAllInvoiceItemsById($sale_id){
		
		$this->db->select('sale_order_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, (CASE WHEN erp_products.unit = 0 THEN erp_products.unit ELSE erp_units.name END) as uname, products.details as details, product_variants.name as variant, products.unit, products.promotion, categories.name AS category_name')
            ->join('products', 'products.id=sale_order_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_order_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=sale_order_items.tax_rate_id', 'left')
			->join('categories', 'categories.id = products.category_id', 'left')
            ->join('units', 'units.id = products.unit', 'left')
            ->group_by('sale_order_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_order_items', array('sale_order_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
		
	}
	
	public function getSaleOrderItemsDetail($sale_order_id = NULL){
		$this->db
		->select('erp_sale_order_items.*,erp_product_variants.name as package_name, units.name as unit')
		->where('erp_sale_order_items.sale_order_id',$sale_order_id)
		->join('products', 'products.id = sale_order_items.product_id', 'left')
		->join('erp_product_variants','erp_sale_order_items.option_id = erp_product_variants.id','left')
		->join('units', 'units.id = products.unit', 'left')
		->from('erp_sale_order_items');
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->result();
		}
		return false;
	}

	public function getDeliveriesInvoiceByID($id)
    {
    	$this->db->select('deliveries.*, delivery_items.warehouse_id, users.username as saleman')
    			 ->join('delivery_items', 'deliveries.id = delivery_items.delivery_id', 'left')
    			 ->join('users', 'deliveries.created_by = users.id', 'left');
        $q = $this->db->get_where('deliveries', array('deliveries.id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	public function getDeliveryQuantity($id){
		$this->db->select('erp_delivery_items.quantity_received')
    			 ->join('delivery_items', 'deliveries.id = delivery_items.delivery_id', 'left')
    			 ->join('users', 'deliveries.created_by = users.id', 'left');
        $q = $this->db->get_where('deliveries', array('deliveries.id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getTotalDeliveryQuantity($sale_reference_no, $id) {
		$this->db->select('COUNT(erp_deliveries.sale_reference_no) as num_truck')
    			 ->join('delivery_items', 'deliveries.id = delivery_items.delivery_id', 'left')
    			 ->join('users', 'deliveries.created_by = users.id', 'left')
				 ->where('deliveries.id <=', $id);
        $q = $this->db->get_where('deliveries', array('deliveries.sale_reference_no' => $sale_reference_no));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getDeliveryTigerByID($sale_id){

		
		 $this->db
			->select("deliveries.id as id, deliveries.date, deliveries.do_reference_no, deliveries.sale_reference_no, companies.name as customer_name, deliveries.address,qty_order.qty AS qty_order,COALESCE(SUM(erp_delivery_items.quantity_received),0) as qty, deliveries.sale_status, drivers.name as driver_name")
			->from('deliveries')
			->join('(SELECT erp_sale_order.id AS id,SUM(erp_sale_order_items.quantity) as qty FROM erp_sale_order LEFT JOIN erp_sale_order_items ON erp_sale_order_items.sale_order_id = erp_sale_order.id GROUP BY erp_sale_order.id) AS qty_order','erp_deliveries.sale_id = qty_order.id','left')
			->where('type','sale_order')
			->join('delivery_items', 'delivery_items.delivery_id = deliveries.id', 'left')
			->join('companies', 'companies.id = deliveries.customer_id', 'inner')
			->join('companies as erp_drivers', 'drivers.id = deliveries.delivery_by', 'inner');
		$this->db->where('erp_deliveries.sale_id',$sale_id);
		$q = $this->db->get();
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
    public function getAllDeliveryInvoiceItems($delivery_id)
    {
        $this->db->select('erp_products.code, erp_categories.categories_note_id, erp_deliveries.*, erp_products.name as description, delivery_items.quantity_received as qty, erp_companies.name, erp_units.name as unit, delivery_items.category_name as brand, delivery_items.option_id, product_variants.name as variant,product_variants.qty_unit, (erp_delivery_items.quantity_received * erp_product_variants.qty_unit) as variant_qty,sale_order_items.piece,sale_order_items.wpiece,sale_order_items.expiry,sale_items.expiry as sale_expiry');
		$this->db->from('deliveries');
		$this->db->join('erp_companies','deliveries.delivery_by = erp_companies.id','left');
		$this->db->join('delivery_items','deliveries.id = delivery_items.delivery_id', 'left');
		$this->db->join('product_variants','delivery_items.option_id = product_variants.id', 'left');
		$this->db->join('erp_products','delivery_items.product_id = erp_products.id', 'left');
		$this->db->join('erp_units','erp_products.unit = erp_units.id', 'left');
		$this->db->join('erp_categories','erp_products.category_id = erp_categories.id', 'left');
		$this->db->join('erp_sale_order','erp_deliveries.sale_reference_no = erp_sale_order.reference_no', 'left');
		$this->db->join('erp_sale_order_items','erp_sale_order.id = erp_sale_order_items.sale_order_id', 'left');
		$this->db->join('erp_sales','erp_deliveries.sale_reference_no = erp_sales.reference_no', 'left');
		$this->db->join('erp_sale_items','erp_sales.id = erp_sale_items.sale_id', 'left');
		$this->db->group_by('delivery_items.id');
		
		$this->db->where('erp_deliveries.id',$delivery_id);
		$q = $this->db->get();
		 if ($q->num_rows() > 0) {
            return $q->result();
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
	public function getCategoriesNoteById($id){
		$this->db->where_in('id', $id);
        $q = $this->db->get('erp_categories_note');
        if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
    }
	public function assign_to_user($user_id=NULL,$so_id=NULL)
	{

		if($this->db->update('sale_order', array('assign_to_id' => $user_id), array('reference_no' => $so_id))){
			return true;
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

   	public function getProductSaleOrder($id = null){
		$this->db->select('*')
				->from('erp_sale_order')
				->join('erp_sale_order_items','erp_sale_order_items.sale_order_id = erp_sale_order.id','left')
				->join('erp_products','erp_sale_order_items.product_id = erp_products.id','left')
				->where('erp_sale_order.id',$id);
		$q = $this->db->get();
		if($q->num_rows() > 0) {
			return $q->row();
		}
	}
	
	public function getJoinlease($id=null)
	{
		$this->db
			 ->select("erp_sale_order.order_discount,erp_sale_order.term_id,erp_sale_order.principle_type,erp_sale_order.installment_date,companies.name,companies.phone,companies.gender,companies.date_of_birth,companies.cf1 as identify_card,terms.description,term_types.name as term_name,sale_order.principle_term")
			 ->join('companies', 'companies.id = erp_sale_order.join_lease_id', 'left')
			 ->join('terms','terms.id = erp_sale_order.term_id','left')
			 ->join('term_types','term_types.id = erp_sale_order.principle_type','left');
			
        $q = $this->db->get_where('erp_sale_order', array('erp_sale_order.id' => $id,'companies.group_name'=>'join_lease'));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
	}
	public function getLoanBySaleId($id = NULL) {

		$q = $this->db->get_where('order_loans', array('sale_id' => $id));
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return FALSE;
	}
	
	public function AddJoinLease($jl_data=array(),$sale_id=null,$jl_id=null)
	{
	  if($jl_data!=null){
						
		if($jl_id){	
				 $this->db->update('companies', $jl_data,array('id',$jl_id));
				 return true;
		}else{
			if($this->db->insert('companies', $jl_data))
			{
				 $jl_id = $this->db->insert_id();
				 $this->db->update('sale_order', array('join_lease_id' => $jl_id), array('id' => $sale_id));
				 return true;
			}
		}
	  }
	}
	
	public function Addloans($loans=null,$sale_id=null,$update_loan=array(),$update=null)
	{
		
	  if($update_loan['frequency']!=null){
		if($loans){
			
			if($update)
			{
			   $this->db->delete('order_loans', array('sale_id' => $sale_id));
			}

			foreach($loans as $loan)
			{
				$this->db->insert('order_loans', $loan);
			}
			
			$term_id = $this->term_id($update_loan['term']);
			$update_loan['term_id'] = $term_id->id;
			$this->db->update('sale_order', $update_loan, array('id' => $sale_id));
			
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

    public function getProductNames($term, $warehouse_id, $standard, $combo, $digital, $service, $category, $limit = 20)
    {
        $this->db->select('products.id, start_date, end_date, code, name, type, cost, warehouses_products.product_id, warehouses_products.quantity AS qoh, warehouses_products.quantity, price, tax_rate, tax_method, image, promotion, promo_price, product_details, details, subcategory_id, cf1, COALESCE((SELECT GROUP_CONCAT(sp.`serial_number`) FROM erp_serial as sp WHERE sp.product_id='.$this->db->dbprefix('products').'.id), "") as sep')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        if (1) {
            $this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%') AND inactived <> 1");
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
                if($service != ""){
                    $this->db->where("products.type <> 'service' ");
                }
                if($category != ""){
                    $this->db->where("products.category_id NOT IN (".$category.") ");
                }
                if($warehouse_id != ""){
                    $this->db->where("warehouses_products.warehouse_id",$warehouse_id);
                }
            }
        } else {
            $this->db->where("(products.track_quantity = 0 OR warehouses_products.quantity > 0) AND warehouses_products.warehouse_id = '" . $warehouse_id . "' AND "
                . "(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%') AND inactived <> 1");
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
