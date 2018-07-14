<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProductNames($term, $limit = 5)
    {
        $this->db->select('id, code, name')
            ->like('name', $term, 'both')->or_like('code', $term, 'both');
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
	public function getsale_top_export($id){
		$this->db->select("erp_sale_items.id, 
			sale_items.product_code, 
			sale_items.product_name, 
			erp_categories.name as category,  
            SUM(COALESCE(erp_sale_items.quantity,0)) as quantity,			
			(erp_units.name)")
			->from('erp_sale_items') 
			->join('erp_sales','erp_sales.id=erp_sale_items.id','left')
			->join('erp_products','erp_products.id=erp_sale_items.product_id','left')
			->join('erp_categories','erp_categories.id=erp_products.category_id','left')
			->join('erp_units','erp_units.id=erp_products.unit','left')
			->where('erp_sale_items.product_id', $id)
			->group_by('erp_sale_items.product_id')
			->order_by('quantity','DESC'); 
			$q = $this->db->get();
			if ($q->num_rows() > 0) {
				return $q->row();
			}
			return false; 	
	}
	public function getTransfersReport($reference_no,$start_date,$end_date,$from_warehouse,$to_warehouse,$offset,$limit,$wid){ 
		if($reference_no){
			$this->db->where("erp_transfers.transfer_no",$reference_no);
		} 
		if($start_date){
			$this->db->where("date_format(erp_transfers.date,'%Y-%m-%d')  BETWEEN '$start_date' AND '$end_date'");
		}
		if($from_warehouse){
			$this->db->where("erp_transfers.from_warehouse_id",$from_warehouse);
		}
		if($to_warehouse){
			$this->db->where("erp_transfers.to_warehouse_id",$to_warehouse);
		}
		if($wid){
			//$this->db->where("erp_transfers.from_warehouse_id IN ($wid)");
			$this->db->where("erp_transfers.to_warehouse_id IN ($wid)");
		}
		$this->db->select("erp_transfers.*,username");
        $this->db->join("erp_users","erp_transfers.authorize_id=erp_users.id");
		$this->db->order_by("erp_transfers.id","DESC");
		$this->db->limit($limit,$offset);
		
		$q =$this->db->get('erp_transfers');
		if ($q->num_rows() > 0){
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getAlladjustment($reference_no,$warehouse,$wid,$created_by,$start_date,$end_date,$page,$offset){
		if($reference_no){
			$this->db->where("erp_adjustments.reference_no",$reference_no);
		}
		if($warehouse){
		   $this->db->where("erp_adjustments.warehouse_id",$warehouse);
		}else{
			if($wid){
				$this->db->where("erp_adjustments.warehouse_id IN ($wid)");
			}
		}
		if($created_by){
		   $this->db->where("erp_adjustments.created_by",$created_by);
		}
		if($start_date && $end_date){
		   $this->db->where("erp_adjustments.date BETWEEN '$start_date 00:00' AND '$end_date 23:59' ");
		}
		$this->db->select("erp_adjustments.*,erp_warehouses.name as warehouse,erp_users.username")
		->join('erp_warehouses','erp_warehouses.id=erp_adjustments.warehouse_id','left')
		->join('erp_users','erp_users.id=erp_adjustments.created_by','left');
		$this->db->limit($page,$offset);
		$q =$this->db->get_where('erp_adjustments');
		if ($q->num_rows() > 0){
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getAlladjustmentByID($id){
		$this->db->select("erp_adjustments.*,erp_warehouses.name as warehouse,erp_users.username")
		->join('erp_warehouses','erp_warehouses.id=erp_adjustments.warehouse_id','left')
		->join('erp_users','erp_users.id=erp_adjustments.created_by','left');
		$q =$this->db->get_where('erp_adjustments',array('erp_adjustments.id'=>$id));
		if ($q->num_rows() > 0){
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getTransfersReportDetail($id,$offset,$limit){ 
		$this->db->select("erp_transfers.*");
		$this->db->order_by("erp_transfers.id","DESC");
		$this->db->limit($limit,$offset);
		$this->db->where('erp_transfers.id', $id);
		$q =$this->db->get('erp_transfers');
		if ($q->num_rows() > 0){
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getconvert($product,$warehouse,$start_date,$end_date,$page,$offset){
		if($product){
			$this->db->where('erp_convert_items.product_id',$product);
		}
		if($warehouse){
			$this->db->where('erp_convert.warehouse_id',$warehouse);
		}
		if($start_date){
			$this->db->where("erp_convert.date BETWEEN '$start_date' AND '$end_date'");
		}
		 
		$this->db->select("erp_convert_items.id,erp_convert_items.product_id,erp_convert_items.product_code,erp_convert_items.product_name,SUM(erp_convert_items.quantity) as con_qty,erp_product_variants.name as var_name,erp_units.name as unit")
		->join('erp_product_variants','erp_product_variants.id = erp_convert_items.option_id','LEFT')
		->join('erp_products','erp_products.id=erp_convert_items.product_id','LEFT')
		->join('erp_units','erp_units.id=erp_products.unit','left') 
		->join('erp_convert','erp_convert.id=erp_convert_items.convert_id','left')
		->where("erp_convert_items.status","add")
		->group_by("erp_convert_items.product_id")
		->limit($page,$offset);
		$q = $this->db->get('erp_convert_items');
		if ($q->num_rows() > 0){
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	   
	}
	public function getConvertDetailByID($id,$start,$end){
		
		$this->db->select("erp_convert.reference_no,erp_convert.date,erp_convert.id,erp_convert_items.product_id,erp_warehouses.name as warehouse ,erp_users.username")
	    ->join('erp_convert_items','erp_convert_items.convert_id=erp_convert.id','LEFT')
		->join('erp_warehouses','erp_warehouses.id=erp_convert.warehouse_id','LEFT')
		->join('erp_users','erp_users.id=erp_convert.created_by','LEFT')
		->where('erp_convert_items.product_id',$id);
		if($start){ 			
			$this->db->where("date_format(erp_convert.date,'%Y-%m-%d')  BETWEEN '$start' AND '$end'");
		}
		$q = $this->db->get('erp_convert');
		if($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
		
	}
	public function getconvertDetail($id,$start,$end,$offset,$page,$reference_no,$warehouse,$created_by,$wid){
		if($start){
			$this->db->where("date_format(erp_convert.date,'%Y-%m-%d') BETWEEN '".$this->erp->fsd($start)."' AND '".$this->erp->fsd($end)."'");
		}
		if($reference_no){
			$this->db->where("erp_convert.reference_no",$reference_no);
		}
		if($warehouse){
			$this->db->where("erp_convert.warehouse_id",$warehouse);
		}else{
			if($wid){
				$this->db->where("erp_convert.warehouse_id IN ($wid) ");
			}
		}
		if($created_by){
			$this->db->where("erp_convert.created_by",$created_by);
		}
		$this->db->select("erp_convert.reference_no,erp_convert.date,erp_convert.id,erp_warehouses.name as warehouse ,erp_users.username,erp_convert.bom_id")
		->join('erp_warehouses','erp_warehouses.id=erp_convert.warehouse_id','LEFT')
		->join('erp_users','erp_users.id=erp_convert.created_by','LEFT')
		->where('erp_convert.bom_id',$id)
		->limit($page,$offset);
		$q = $this->db->get('erp_convert');
		if ($q->num_rows() > 0){
            foreach (($q->result()) as $row){
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
		
	}
	 
	public function getUsingStock($reference_no,$employee,$biller,$warehouse,$wid,$start_date,$end_date,$offset,$limit){
        //$this->erp->print_arrays($start_date,$end_date);
	    $this->db->select("erp_companies.name as biller,erp_enter_using_stock.id as id,    erp_enter_using_stock.reference_no as refno,
		erp_companies.company, erp_warehouses.name as warehouse_name, erp_users.username, erp_enter_using_stock.note, erp_enter_using_stock.type as type, erp_enter_using_stock.date, erp_enter_using_stock.total_cost", FALSE)
		->join('erp_companies', 'erp_companies.id=erp_enter_using_stock.shop', 'inner')
		->join('erp_warehouses', 'erp_enter_using_stock.warehouse_id=erp_warehouses.id', 'left')
	    ->join('erp_users', 'erp_users.id=erp_enter_using_stock.employee_id', 'inner');

		$this->db->limit($limit,$offset);
		if($reference_no){
			$this->db->where('erp_enter_using_stock.reference_no',$reference_no);
		}
		if($employee){
			$this->db->where('erp_users.id',$employee);
		}
		if($biller){
			$this->db->where('erp_companies.id',$biller);
		}
		if($warehouse){
			$this->db->where('erp_enter_using_stock.warehouse_id',$warehouse);
		}else{
			if($wid){
				$this->db->where("erp_enter_using_stock.warehouse_id IN ($wid)");
			}
		}
		/*Fixed date format*/
		if($start_date){
			$this->db->where("date_format(erp_enter_using_stock.date,'%d/%m/%Y') BETWEEN '{$start_date}' AND '{$end_date}'");
		}		$q =$this->db->get('erp_enter_using_stock');
		if ($q->num_rows() > 0){
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
        			 
	}
	public function getUsingStockDetails($id){
		$this->db->select("erp_enter_using_stock.*");
		$this->db->order_by("erp_enter_using_stock.id","DESC");
		//$this->db->limit($limit,$offset);
		$this->db->where("erp_enter_using_stock.id",$id);
		$q =$this->db->get('erp_enter_using_stock');
		if ($q->num_rows() > 0){
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getUsingStockReport($id,$offset,$limit){
	    $this->db->select("erp_enter_using_stock.id as id,    erp_enter_using_stock.reference_no as refno,
		erp_companies.company, erp_warehouses.name as warehouse_name, erp_users.username, erp_enter_using_stock.note, erp_enter_using_stock.type as type, erp_enter_using_stock.date, erp_enter_using_stock.total_cost", FALSE)
		->join('erp_companies', 'erp_companies.id=erp_enter_using_stock.shop', 'inner')
		->join('erp_warehouses', 'erp_enter_using_stock.warehouse_id=erp_warehouses.id', 'left')
	    ->join('erp_users', 'erp_users.id=erp_enter_using_stock.employee_id', 'inner')
	    ->where('erp_enter_using_stock.id', $id);
		$this->db->limit($limit,$offset);		
		$q =$this->db->get('erp_enter_using_stock');
		if ($q->num_rows() > 0){
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;  			 
	}
	public function getBillers(){
		$this->db->select('erp_companies.*');
		$this->db->where('group_name',"biller");
		$q=$this->db->get('erp_companies');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row){
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
		      
	}
	
	public function get_manager_project(){
		$this->db->select("erp_users.id as id, first_name, last_name, email, company,erp_groups.name, erp_users.active")
		->join('groups', 'users.group_id = groups.id','left')
		->join('sales','sales.assign_to_id=users.id','inner')
        ->group_by('users.id')
        ->where('company_id', NULL);
		$q=$this->db->get('erp_users');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row){
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getDeliveryExport($id){
		$this->db->select("erp_deliveries.*,erp_companies.name as driver,erp_warehouses.name as warehouse,erp_delivery_items.product_name,erp_delivery_items.quantity_received")
		->join('erp_deliveries','erp_delivery_items.delivery_id=erp_deliveries.id','LEFT')
	    ->join('erp_warehouses','erp_warehouses.id=erp_delivery_items.warehouse_id','LEFT')
	    ->join('erp_companies','erp_companies.id=erp_deliveries.id');
		 $q =$this->db->get_where('erp_delivery_items',array('erp_delivery_items.delivery_id'=>$id));
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
    public function getAlldelivery($reference_no,$customer,$driver,$start_date,$end_date,$warehouse,$offset,$limit){
		         
		         $this->db->select("erp_deliveries.*,erp_companies.name as driver")
		         ->join("erp_companies","erp_companies.id=erp_deliveries.delivery_by");
				 if($reference_no){
					 $this->db->where('erp_deliveries.do_reference_no',$reference_no);
				 }
				 if($customer){
					 $this->db->where('erp_deliveries.customer_id',$customer);
				 } 
				 if($driver){
					 $this->db->where('erp_deliveries.delivery_by',$driver);
				 }
				 if($start_date){
					 $this->db->where("date_format(erp_deliveries.date,'%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'"); 
				 }
				 if($warehouse){
					 $this->db->join("erp_delivery_items","erp_delivery_items.delivery_id=erp_deliveries.id","LEFT");
					 $this->db->where("erp_delivery_items.warehouse_id",$warehouse);
				 } 
				 $this->db->order_by('erp_deliveries.id','DESC');
				 $this->db->limit($limit, $offset); 
				
		$q = $this->db->get('erp_deliveries');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getUser(){
		$q =$this->db->get('erp_users');
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

    public function getSaleman($saleman=null)
    {
        $user_id = $this->session->userdata('user_id');
    	$this->db
    	        ->select("users.id, users.username", false)
                ->from("sales")
                ->join('users', 'sales.saleman_by = users.id', 'left')
                ->join('groups', 'users.group_id = groups.id', 'left');

        $this->db->group_by("users.id");
        $this->db->where('groups.type =', 'SALE');

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0) {
			if ($user_id) {
				$this->db->where('users.id', $user_id);
			}
		}


        if ($saleman) {
        	$this->db->where('sales.saleman_by', $saleman);
        }


        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSalemanReportDetail($saleman_id, $start_date2 = NULL, $end_date2 = NULL, $saleman2 = NULL, $sales_type2 = NULL, $issued_by2 = NULL, $start_date = NULL, $end_date = NULL, $sales_type = NULL, $issued_by = NULL){

        $user_biller_id = JSON_decode($this->session->userdata('biller_id'));
        $this->db
        ->select("sales.id, sales.date, sales.due_date, sales.reference_no, sales.biller, sales.note, companies.name as customer, 
                    sales.sale_status, COALESCE(erp_sales.grand_total, 0) as grand_total,  
                    (SELECT SUM(erp_return_sales.grand_total) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id) as return_sale, 
                    COALESCE( (SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0) as paid, 
                    (SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id) as deposit, 
                    (SELECT SUM(COALESCE(erp_payments.discount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id) as discount, 
                    (COALESCE(erp_sales.grand_total,0)-COALESCE((SELECT SUM(erp_return_sales.grand_total) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id), 0)-COALESCE( (SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0)- COALESCE((SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id  ),0)-SUM(COALESCE(erp_payments.discount,0)) ) as balance, 
                    payment_status")
        ->join('companies', 'sales.customer_id = companies.id', 'left')
        ->join('payments', 'payments.sale_id = sales.id', 'left')
        ->join('users', 'sales.saleman_by = users.id', 'left')
        ->join('groups', 'users.group_id = groups.id', 'left')
        ->where('erp_sales.saleman_by', $saleman_id)
        ->group_by('sales.id')
        ->order_by('sales.date', 'desc');

		if($user_biller_id != NULL){
			$this->db->where_in('sales.biller_id', $user_biller_id);
		}

        if($start_date2 && $end_date2){
		   $this->db->where('date_format(erp_sales.date,"%Y-%m-%d") BETWEEN "' . $start_date2 . '" and "' . $end_date2 . '"');
		   $this->db->where('groups.type =', 'SALE');
	    }

	    if($saleman2){
		    $this->db->where('sales.saleman_by',$saleman2);
	    }
	    
	    if ($sales_type2) {
		    if($sales_type2 == 'wholesale'){
		    	$sales_type2 = 0;
			    $this->db->where('sales.pos',$sales_type2);
		    } elseif ($sales_type2 == 'retail') {
		    	$sales_type2 = 1;
			    $this->db->where('sales.pos',$sales_type2);
		    }
		}

		if ($issued_by2) {
			if ($issued_by2 == 'hide') {
				$this->db->where('sales.note =', '');
			}
		}

		// For Saleman Report
		if($start_date && $end_date){
		   $this->db->where('date_format(erp_sales.date,"%Y-%m-%d") BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		   $this->db->where('groups.type =', 'SALE');
	    }
	    
	    if ($sales_type) {
		    if($sales_type == 'wholesale'){
		    	$sales_type = 0;
			    $this->db->where('sales.pos',$sales_type);
		    } elseif ($sales_type == 'retail') {
		    	$sales_type = 1;
			    $this->db->where('sales.pos',$sales_type);
		    }
		}

		if ($issued_by) {
			if ($issued_by == 'hide') {
				$this->db->where('sales.note =', '');
			}
		}

        $q = $this->db->get('sales');
        if($q->num_rows() > 0){
            return $q->result();
        }
        return false;
	}

	public function getSalemanReportDetailForEx($saleman_id, $start_date = NULL, $end_date = NULL, $sales_type = NULL, $issued_by = NULL){

        $this->db
        ->select("sales.id, sales.date, sales.due_date, sales.reference_no, sales.biller, sales.note, companies.name as customer, 
                    sales.sale_status, COALESCE(erp_sales.grand_total, 0) as grand_total,  
                    (SELECT SUM(erp_return_sales.grand_total) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id) as return_sale, 
                    COALESCE( (SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0) as paid, 
                    (SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id) as deposit, 
                    (SELECT SUM(COALESCE(erp_payments.discount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id) as discount, 
                    (COALESCE(erp_sales.grand_total,0)-COALESCE((SELECT SUM(erp_return_sales.grand_total) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id), 0)-COALESCE( (SELECT SUM(IF((erp_payments.paid_by != 'deposit' AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id),0)- COALESCE((SELECT SUM(IF(erp_payments.paid_by = 'deposit', erp_payments.amount, 0)) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id  ),0)-SUM(COALESCE(erp_payments.discount,0)) ) as balance, 
                    payment_status")
        ->join('companies', 'sales.customer_id = companies.id', 'left')
        ->join('payments', 'payments.sale_id = sales.id', 'left')
        ->join('users', 'sales.saleman_by = users.id', 'left')
        ->join('groups', 'users.group_id = groups.id', 'left')
        ->where('erp_sales.saleman_by', $saleman_id)
        ->group_by('sales.id')
        ->order_by('sales.date', 'desc');

		// For Saleman Report
		if($start_date && $end_date){
		   $this->db->where('date_format(erp_sales.date,"%Y-%m-%d") BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		   $this->db->where('groups.type =', 'SALE');
	    }
	    
	    if ($sales_type) {
		    if($sales_type == 'wholesale'){
		    	$sales_type = 0;
			    $this->db->where('sales.pos',$sales_type);
		    } elseif ($sales_type == 'retail') {
		    	$sales_type = 1;
			    $this->db->where('sales.pos',$sales_type);
		    }
		}

		if ($issued_by) {
			if ($issued_by == 'hide') {
				$this->db->where('sales.note =', '');
			}
		}

        $q = $this->db->get('sales');
        if($q->num_rows() > 0){
            return $q->result();
        }
        return false;
	}

    public function getSalesTotals($customer_id)
    {

        $this->db->select('SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('biller_id', $customer_id);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
    public function getSalesTotal($customer_id)
    {
        $this->db
                ->select('SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
                ->where('customer_id', $customer_id)
                ->where('sales.pos <>', '1')->where('sales.sale_status <>','returned');
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getSalesTotalCreatedBy($customer_id,$creator)
    {
        $this->db
            ->select('SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('customer_id', $customer_id)
            ->where('sales.pos <>', '1')
            ->where('sales.created_by',$creator);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getCustomerSales($customer_id)
    {
        $this->db->from('sales')->where('biller_id', $customer_id);
        return $this->db->count_all_results();
    }

    public function getCustomerSale($customer_id)
    {
        $this->db
                ->from('sales')
                ->where('customer_id', $customer_id)
                ->where('sales.pos <>', '1');
        return $this->db->count_all_results();
    }
    public function getCustomerSaleCreatedBy($customer_id,$creator)
    {
        $this->db
            ->from('sales')
            ->where('customer_id', $customer_id)
            ->where('sales.pos <>', '1')
            ->where('sales.created_by',$creator);
        return $this->db->count_all_results();
    }
    public function getCustomerQuotes($customer_id)
    {
        $this->db->from('quotes')->where('biller_id', $customer_id);
        return $this->db->count_all_results();
    }
    public function getCustomerQuote($customer_id)
    {
        $this->db->from('quotes')->where('customer_id', $customer_id);
        return $this->db->count_all_results();
    }
    public function getCustomerQuoteCreatedBy($customer_id,$creator)
    {
        $this->db->from('quotes')->where('customer_id', $customer_id)->where('quotes.created_by',$creator);
        return $this->db->count_all_results();
    }
    public function getCustomerReturns($customer_id)
    {
        $this->db->from('return_sales')->where('biller_id', $customer_id);
        return $this->db->count_all_results();
    }
	public function getCustomerReturn($customer_id)
    {
        $this->db->from('return_sales')
        ->join('sales','sales.id=return_sales.sale_id','left')
        ->where('return_sales.customer_id', $customer_id)
        ->where('sales.pos <>',1);
        return $this->db->count_all_results();
    }
    public function getCustomerReturnCreatedBy($customer_id,$creator)
    {
        $this->db->from('return_sales')->where('customer_id', $customer_id)->where('return_sales.created_by',$creator);
        return $this->db->count_all_results();
    }
	public function getCustomerDeposit($customer_id){
		$this->db
		->from('deposits')
		->where('company_id', $customer_id);
		// ->where('deposits.pos <>', '1');
        return $this->db->count_all_results();
	}
    public function getCustomerDepositCreatedBy($customer_id,$creator){
        $this->db
            ->from('deposits')
            ->where('company_id', $customer_id)->where('created_by',$creator);
        // ->where('deposits.pos <>', '1');
        return $this->db->count_all_results();
    }
	public function getCustomerDeposits($company_id)
    {
        $this->db
                ->from('deposits')
                ->join('users', 'users.id=deposits.created_by', 'left')
				->where($this->db->dbprefix('deposits') . ".company_id", $company_id);
        return $this->db->count_all_results();
    }

    public function getStockValue()
    {
        $q = $this->db->query("SELECT SUM(by_price) as stock_by_price, SUM(by_cost) as stock_by_cost FROM ( Select COALESCE(sum(" . $this->db->dbprefix('warehouses_products') . ".quantity), 0)*price as by_price, COALESCE(sum(" . $this->db->dbprefix('warehouses_products') . ".quantity), 0)*cost as by_cost FROM " . $this->db->dbprefix('products') . " JOIN " . $this->db->dbprefix('warehouses_products') . " ON " . $this->db->dbprefix('warehouses_products') . ".product_id=" . $this->db->dbprefix('products') . ".id GROUP BY " . $this->db->dbprefix('products') . ".id )a");
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	 public function getWarehouseStockValue($id)
    {
        $q = $this->db->query("SELECT SUM(by_price) as stock_by_price, SUM(by_cost) as stock_by_cost FROM ( Select sum(COALESCE(" . $this->db->dbprefix('warehouses_products') . ".quantity, 0))*price as by_price, sum(COALESCE(" . $this->db->dbprefix('warehouses_products') . ".quantity, 0))*cost as by_cost FROM " . $this->db->dbprefix('products') . " JOIN " . $this->db->dbprefix('warehouses_products') . " ON " . $this->db->dbprefix('warehouses_products') . ".product_id=" . $this->db->dbprefix('products') . ".id WHERE " . $this->db->dbprefix('warehouses_products') . ".warehouse_id = ? GROUP BY " . $this->db->dbprefix('products') . ".id )a", array($id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	//chivorn chart stock
	
		
	public function getCategoryStockValue($biller= NULL,$customer= NULL,$start_date= NULL,$end_date= NULL)
    {
		if($biller != NULL){
			$where_biller = " AND erp_sales.biller_id=".$biller;
		}else{
			$where_biller = "";
		}
		if($customer != NULL){
			$where_customer = " AND erp_sales.customer_id=".$customer;
		}else{
			$where_customer = "";
		}
		if($start_date != NULL && $end_date != NULL){
			$where_between_date = " AND erp_sales.date between '$start_date' AND '$end_date'";
		}else{
			$where_between_date = "";
		}
		
		$q = $this->db->query("
			SELECT
				COALESCE (
					sum(
						erp_sale_items.subtotal
					),
					0
				) AS by_price,
				erp_categories.name AS category_name
			FROM
				erp_products
			JOIN erp_warehouses_products ON erp_warehouses_products.product_id = erp_products.id
			JOIN erp_categories ON erp_categories.id = erp_products.category_id
			JOIN erp_sale_items ON erp_sale_items.product_id = erp_products.id
			JOIN erp_sales ON erp_sales.id = erp_sale_items.sale_id WHERE 1=1 $where_biller $where_customer $where_between_date
			GROUP BY
				erp_categories.id");
        
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	public function getChartValue()
    {
		$q = $this->db->query("
			SELECT
				accountcode,
				accountname,
				COALESCE (
					sum(
						amount
					),
					0
				) AS total_amount
			FROM
				erp_gl_charts
			LEFT JOIN erp_gl_trans ON erp_gl_trans.account_code = erp_gl_charts.accountcode
			WHERE
				erp_gl_charts.bank = 1
			GROUP BY
				accountcode;");
        
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	public function getCategoryStockValueById($id, $biller= NULL,$customer= NULL,$start_date= NULL,$end_date= NULL)
    {
		if($biller != NULL){
			$where_biller = " AND erp_sales.biller_id=".$biller;
		}else{
			$where_biller = "";
		}
		if($customer != NULL){
			$where_customer = " AND erp_sales.customer_id=".$customer;
		}else{
			$where_customer = "";
		}
		
		if($start_date != NULL && $end_date != NULL){
			$where_between_date = " AND erp_sales.date between '$start_date' AND '$end_date'";
		}else{
			$where_between_date = "";
		}
		
        $q = $this->db->query("
			SELECT
				COALESCE (
					sum(
						erp_sale_items.subtotal
					),
					0
				) AS by_price,
				erp_categories.name AS category_name
			FROM
				erp_products
			JOIN erp_warehouses_products ON erp_warehouses_products.product_id = erp_products.id
			JOIN erp_categories ON erp_categories.id = erp_products.category_id
			JOIN erp_sale_items ON erp_sale_items.product_id = erp_products.id
			JOIN erp_sales ON erp_sales.id = erp_sale_items.sale_id
			WHERE erp_sale_items.warehouse_id = $id $where_biller $where_customer $where_between_date
			GROUP BY
				erp_categories.id");
        
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	public function getChartValueById($id)
    {
        $q = $this->db->query("
SELECT
				accountcode,
				accountname,
				COALESCE (
					sum(
						amount
					),
					0
				) AS total_amount
			FROM
				erp_gl_charts
			LEFT JOIN erp_gl_trans ON erp_gl_trans.account_code = erp_gl_charts.accountcode
			WHERE
				erp_gl_charts.bank = 1 and erp_gl_trans.account_code= $id
			GROUP BY
				accountcode;");
        
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	public function getChartDataProfit($biller_id = null, $year = null)
    {
	if($biller_id != null){
		$where_biller_id = "AND erp_gl_trans.biller_id = ".$biller_id;
	}else{
		$where_biller_id = "";
	}
	if($year != null){
		$where_year = "AND YEAR(erp_gl_trans.tran_date) = ".$year;
	}else{
		$where_year = "";
	}
        $myQuery = "SELECT
	I. MONTH,
	COALESCE (I.income, 0) AS income,
	COALESCE (C.cost, 0) AS cost,
	COALESCE (O.operation, 0) AS operation
FROM
	(
		SELECT
			date_format(tran_date, '%Y-%m') MONTH,
			erp_gl_trans.account_code,
			erp_gl_trans.sectionid,
			erp_gl_charts.accountname,
			erp_gl_charts.parent_acc,
			sum(erp_gl_trans.amount) AS income
		FROM
			erp_gl_trans
		INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
		WHERE
			erp_gl_trans.tran_date >= date_sub(now(), INTERVAL 12 MONTH)
		AND erp_gl_trans.sectionid IN (40, 70) $where_biller_id $where_year
	
			GROUP BY date_format(tran_date, '%Y-%m'),
			erp_gl_trans.account_code
	) I
LEFT JOIN (
	SELECT
		date_format(tran_date, '%Y-%m') MONTH,
		erp_gl_trans.account_code,
		erp_gl_trans.sectionid,
		erp_gl_charts.accountname,
		erp_gl_charts.parent_acc,
		sum(erp_gl_trans.amount) AS cost
	FROM
		erp_gl_trans
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
	WHERE
		erp_gl_trans.tran_date >= date_sub(now(), INTERVAL 12 MONTH)
	AND erp_gl_trans.sectionid IN (50) $where_biller_id $where_year

		GROUP BY date_format(tran_date, '%Y-%m'),
		erp_gl_trans.account_code
) C ON I. MONTH = C. MONTH
LEFT JOIN (
	SELECT
		date_format(tran_date, '%Y-%m') MONTH,
		erp_gl_trans.account_code,
		erp_gl_trans.sectionid,
		erp_gl_charts.accountname,
		erp_gl_charts.parent_acc,
		sum(erp_gl_trans.amount) AS operation
	FROM
		erp_gl_trans
	INNER JOIN erp_gl_charts ON erp_gl_charts.accountcode = erp_gl_trans.account_code
	WHERE
		erp_gl_trans.tran_date >= date_sub(now(), INTERVAL 12 MONTH)
	AND erp_gl_trans.sectionid IN (60,80,90) $where_biller_id $where_year
		GROUP BY date_format(tran_date, '%Y-%m'),
		erp_gl_trans.account_code
) O ON O. MONTH = I. MONTH
GROUP BY
	I. MONTH
ORDER BY
	I. MONTH";
        $q = $this->db->query($myQuery);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	// end chivorn
	
    public function getChartData()
    {
        $myQuery = "SELECT S.month,
        COALESCE(S.sales, 0) as sales,
        COALESCE( P.purchases, 0 ) as purchases,
        COALESCE(S.tax1, 0) as tax1,
        COALESCE(S.tax2, 0) as tax2,
        COALESCE( P.ptax, 0 ) as ptax
        FROM (  SELECT  date_format(date, '%Y-%m') Month,
                SUM(total) Sales,
                SUM(product_tax) tax1,
                SUM(order_tax) tax2
                FROM " . $this->db->dbprefix('sales') . "
                WHERE date >= date_sub( now( ) , INTERVAL 12 MONTH )
                GROUP BY date_format(date, '%Y-%m')) S
            LEFT JOIN ( SELECT  date_format(date, '%Y-%m') Month,
                        SUM(product_tax) ptax,
                        SUM(order_tax) otax,
                        SUM(total) purchases
                        FROM " . $this->db->dbprefix('purchases') . "
                        GROUP BY date_format(date, '%Y-%m')) P
            ON S.Month = P.Month
            GROUP BY S.Month
            ORDER BY S.Month";
        $q = $this->db->query($myQuery);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllWarehouses()
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
	
	public function getAllSaleIemsWarehouses()
    {
        $this->db
			->select('warehouses.code')
            ->from('warehouses')
			->join('sale_items', 'warehouses.id = sale_items.warehouse_id')
			->group_by('sale_items.warehouse_id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	 public function getAllCharts()
    {
        $q = $this->db->get('gl_charts');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllCustomers()
    {
        $q = $this->db->get('customers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllBillers()
    {
        $q = $this->db->get_where('erp_companies',array("group_name"=>"biller"));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllSuppliers()
    {
        $q = $this->db->get('suppliers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
    public function getDailySales($year, $month)
    {
        $myQuery = "SELECT DATE_FORMAT( erp_sales.date,  '%e' ) AS date, 
		SUM( COALESCE( erp_sales.product_tax, 0 ) ) AS tax1, 
		SUM( COALESCE( erp_sales.order_tax, 0 ) ) AS tax2, 
		SUM( COALESCE( erp_sales.total, 0 ) ) AS total, 
		SUM( COALESCE( erp_sales.total_discount, 0 ) ) AS discount, 
		SUM( COALESCE( erp_sales.order_discount, 0 ) ) AS order_discount, 
		SUM( COALESCE( erp_sales.shipping, 0 ) ) AS shipping,SUM(COALESCE(erp_return_sales.grand_total,0)) as t_return
			FROM " . $this->db->dbprefix('sales') . " LEFT JOIN erp_return_sales ON erp_return_sales.sale_id=erp_sales.id
			WHERE DATE_FORMAT( erp_sales.date,  '%Y-%m' ) =  '{$year}-{$month}'
			GROUP BY DATE_FORMAT( erp_sales.date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getDailySalesByBiller($year, $month,$biller)
    {
        $user = $this->site->getUser();
        if(empty($biller))
        {
            if(!$this->Owner && !$this->Admin)
            {
                $biller=$user->biller_id;
            }else
            {
                $biller="";
            }
        }
        $biller=json_decode($biller);
        if(is_array($biller)){
            $b="";
            foreach ($biller as $key ) {
                $b.=$key.",";

            }
            $b=rtrim($b,",");
            //$biller='';
        }else{
            $b=$biller;
        }
        $biller=$biller?' AND erp_sales.biller_id IN ('.$b.')':'';
        $myQuery = "SELECT DATE_FORMAT( erp_sales.date,  '%e' ) AS date, 
		SUM( COALESCE( erp_sales.product_tax, 0 ) ) AS tax1, 
		SUM( COALESCE( erp_sales.order_tax, 0 ) ) AS tax2, 
		SUM( COALESCE( erp_sales.total, 0 ) ) AS total, 
		SUM( COALESCE( erp_sales.total_discount, 0 ) ) AS discount, 
		SUM( COALESCE( erp_sales.order_discount, 0 ) ) AS order_discount, 
		SUM( COALESCE( erp_sales.shipping, 0 ) ) AS shipping,SUM(COALESCE(erp_return_sales.grand_total,0)) as t_return
			FROM " . $this->db->dbprefix('sales') . " LEFT JOIN erp_return_sales ON erp_return_sales.sale_id=erp_sales.id
			WHERE DATE_FORMAT( erp_sales.date,  '%Y-%m' ) =  '{$year}-{$month}'".$biller."
			GROUP BY DATE_FORMAT( erp_sales.date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


    public function getMonthlySales($year)
    {
        $myQuery = "SELECT DATE_FORMAT( erp_sales.date,  '%c' ) AS date,
		SUM( COALESCE( erp_sales.product_tax, 0 ) ) AS tax1, 
		SUM( COALESCE( erp_sales.order_tax, 0 ) ) AS tax2, 
		SUM( COALESCE( erp_sales.total, 0 ) ) AS total,
		SUM( COALESCE( erp_sales.total_discount, 0 ) ) AS discount,
		SUM( COALESCE( erp_sales.order_discount, 0 ) ) AS order_discount, 
		SUM( COALESCE( erp_sales.shipping, 0 ) ) AS shipping,SUM(COALESCE(erp_return_sales.grand_total,0)) as t_return 
			FROM " . $this->db->dbprefix('sales') . " LEFT JOIN erp_return_sales ON erp_return_sales.sale_id = erp_sales.id
			WHERE DATE_FORMAT( erp_sales.date,  '%Y' ) =  '{$year}'
			GROUP BY date_format( erp_sales.date, '%c' ) ORDER BY date_format( erp_sales.date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getMonthlySalesByBiller($year,$biller)
    {
        $user = $this->site->getUser();
        if(empty($biller)){
            if(!$this->Owner && !$this->Admin)
            {
                $biller=$user->biller_id;
            }else
            {
                $biller="";
            }
        }
        $biller=json_decode($biller);
        if(is_array($biller)){
            $b="";
            foreach ($biller as $key ) {
                $b.=$key.",";

            }
            $b=rtrim($b,",");
            //$biller='';
        }else{
            $b=$biller;
        }
        $biller=$biller?' AND erp_sales.biller_id IN ('.$b.')':'';
        $myQuery = "SELECT DATE_FORMAT( erp_sales.date,  '%c' ) AS date,
		SUM( COALESCE( erp_sales.product_tax, 0 ) ) AS tax1, 
		SUM( COALESCE( erp_sales.order_tax, 0 ) ) AS tax2, 
		SUM( COALESCE( erp_sales.total, 0 ) ) AS total,
		SUM( COALESCE( erp_sales.total_discount, 0 ) ) AS discount,
		SUM( COALESCE( erp_sales.order_discount, 0 ) ) AS order_discount, 
		SUM( COALESCE( erp_sales.shipping, 0 ) ) AS shipping,SUM(COALESCE(erp_return_sales.grand_total,0)) as t_return 
			FROM " . $this->db->dbprefix('sales') . " LEFT JOIN erp_return_sales ON erp_return_sales.sale_id = erp_sales.id
			WHERE DATE_FORMAT( erp_sales.date,  '%Y' ) =  '{$year}'".$biller."
			GROUP BY date_format( erp_sales.date, '%c' ) ORDER BY date_format( erp_sales.date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getRoomDailySales($room_id, $year, $month)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            WHERE suspend_note = {$room_id} AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getStaffDailySaleman($user_id, $year, $month)
    {
    	$user_id = $this->session->userdata('user_id');
    	$user_biller_id = $this->session->userdata('biller_id');
    	if ($user_biller_id) {
    		$myQuery = "SELECT DATE_FORMAT( erp_sales.date,  '%e' ) AS date, SUM( COALESCE( erp_sales.product_tax, 0 ) ) AS tax1, SUM( COALESCE( erp_sales.order_tax, 0 ) ) AS tax2, SUM( COALESCE( erp_sales.grand_total, 0 ) ) AS total, SUM( COALESCE( erp_sales.total_discount, 0 ) ) AS discount, SUM( COALESCE( erp_sales.shipping, 0 ) ) AS shipping,SUM(COALESCE(erp_return_sales.grand_total,0)) as t_return
            FROM " . $this->db->dbprefix('sales') . " LEFT JOIN erp_return_sales ON erp_return_sales.sale_id = erp_sales.id
            WHERE erp_sales.biller_id = {$user_biller_id} AND (CASE WHEN saleman_by <> '' THEN saleman_by ELSE erp_sales.created_by END) = {$user_id} AND DATE_FORMAT( erp_sales.date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( erp_sales.date,  '%e' )";
    	} else {
	        $myQuery = "SELECT DATE_FORMAT( erp_sales.date,  '%e' ) AS date, SUM( COALESCE( erp_sales.product_tax, 0 ) ) AS tax1, SUM( COALESCE( erp_sales.order_tax, 0 ) ) AS tax2, SUM( COALESCE( erp_sales.grand_total, 0 ) ) AS total, SUM( COALESCE( erp_sales.total_discount, 0 ) ) AS discount, SUM( COALESCE( erp_sales.shipping, 0 ) ) AS shipping,SUM(COALESCE(erp_return_sales.grand_total,0)) as t_return
	            FROM " . $this->db->dbprefix('sales') . " LEFT JOIN erp_return_sales ON erp_return_sales.sale_id = erp_sales.id
	            WHERE (CASE WHEN saleman_by <> '' THEN saleman_by ELSE erp_sales.created_by END) = {$user_id} AND DATE_FORMAT( erp_sales.date,  '%Y-%m' ) =  '{$year}-{$month}'
	            GROUP BY DATE_FORMAT( erp_sales.date,  '%e' )";
	    }

        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getStaffDailySaleman1($user_id, $year, $month)
    {
    	$user_biller_id = $this->session->userdata('biller_id');
    	if ($user_biller_id) {
    		$myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . "
            WHERE erp_purchases.biller_id = {$user_biller_id} AND created_by = {$user_id} AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
    	} else {
    		$myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . "
            WHERE created_by = {$user_id} AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
    	}

        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getStaffDailySales($user_id, $year, $month)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, 
			SUM( COALESCE( order_tax, 0 ) ) AS tax2, 
			SUM( COALESCE( total, 0 ) ) AS total,
			SUM( COALESCE( total_discount, 0 ) ) AS discount,
			SUM( COALESCE( order_discount, 0 ) ) AS order_discount, 
			SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            WHERE created_by = {$user_id} AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getStaffDailySalesByBiller($user_id, $year, $month,$biller)
    {
        $user = $this->site->getUser();
        if(empty($biller))
        {
            if(!$this->Owner && !$this->Admin)
            {
                $biller=$user->biller_id;
            }else
            {
                $biller="";
            }
        }
        $biller=json_decode($biller);
        if(is_array($biller)){
            $b="";
            foreach ($biller as $key ) {
                $b.=$key.",";

            }
            $b=rtrim($b,",");
            //$biller='';
        }else{
            $b=$biller;
        }
        $biller=$biller?' AND erp_sales.biller_id IN ('.$b.')':'';
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, 
			SUM( COALESCE( order_tax, 0 ) ) AS tax2, 
			SUM( COALESCE( total, 0 ) ) AS total,
			SUM( COALESCE( total_discount, 0 ) ) AS discount,
			SUM( COALESCE( order_discount, 0 ) ) AS order_discount, 
			SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            WHERE created_by = {$user_id} AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'".$biller[0]."
            GROUP BY DATE_FORMAT( date,  '%e' )";
            //$this->db->where_in("erp_sales.biller_id",json_decode($biller));
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProjectManagerDailySales($user_id, $year, $month)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total,  SUM( COALESCE( subtotal, 0 ) ) AS subtotal, SUM( COALESCE( total_discount, 0 ) ) AS discount,SUM( COALESCE( order_discount, 0 ) ) AS order_discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            LEFT JOIN " . $this->db->dbprefix('sale_items') . " as sale_items ON sale_items.sale_id = " . $this->db->dbprefix('sales') . ".id
            LEFT JOIN " . $this->db->dbprefix('users') . " as users ON users.id = " . $this->db->dbprefix('sales') . ".assign_to_id
            WHERE users.id = {$user_id} AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getRoomMonthlySales($room_id, $year)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            WHERE suspend_note = {$room_id} AND DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getStaffMonthlySaleman($user_id, $year)
    {
    	$user_biller_id = $this->session->userdata('biller_id');
    	if ($user_biller_id != NULL) {
    		$myQuery = "SELECT DATE_FORMAT( erp_sales.date,  '%c' ) AS date, 
			SUM( COALESCE( erp_sales.product_tax, 0 ) ) AS tax1, 
			SUM( COALESCE( erp_sales.order_tax, 0 ) ) AS tax2, 
			SUM( COALESCE( erp_sales.total, 0 ) ) AS total,
			SUM( COALESCE( erp_sales.total_discount, 0 ) ) AS discount, 
			SUM( COALESCE( erp_sales.order_discount, 0 ) ) AS order_discount, 
			SUM( COALESCE( erp_sales.shipping, 0 ) ) AS shipping,SUM(COALESCE(erp_return_sales.grand_total,0))as t_return
	            FROM " . $this->db->dbprefix('sales') . " LEFT JOIN erp_return_sales ON erp_return_sales.sale_id=erp_sales.id
	            WHERE erp_sales.biller_id = {$user_biller_id} AND (CASE WHEN saleman_by <> '' THEN saleman_by ELSE erp_sales.created_by END) = {$user_id} AND DATE_FORMAT( erp_sales.date,  '%Y' ) =  '{$year}'
	            GROUP BY date_format( erp_sales.date, '%c' ) ORDER BY date_format( erp_sales.date, '%c' ) ASC";
    	} else {
    		$myQuery = "SELECT DATE_FORMAT( erp_sales.date,  '%c' ) AS date, 
			SUM( COALESCE( erp_sales.product_tax, 0 ) ) AS tax1, 
			SUM( COALESCE( erp_sales.order_tax, 0 ) ) AS tax2, 
			SUM( COALESCE( erp_sales.total, 0 ) ) AS total,
			SUM( COALESCE( erp_sales.total_discount, 0 ) ) AS discount, 
			SUM( COALESCE( erp_sales.order_discount, 0 ) ) AS order_discount, 
			SUM( COALESCE( erp_sales.shipping, 0 ) ) AS shipping,SUM(COALESCE(erp_return_sales.grand_total,0))as t_return
	            FROM " . $this->db->dbprefix('sales') . " LEFT JOIN erp_return_sales ON erp_return_sales.sale_id=erp_sales.id
	            WHERE (CASE WHEN saleman_by <> '' THEN saleman_by ELSE erp_sales.created_by END) = {$user_id} AND DATE_FORMAT( erp_sales.date,  '%Y' ) =  '{$year}'
	            GROUP BY date_format( erp_sales.date, '%c' ) ORDER BY date_format( erp_sales.date, '%c' ) ASC";
    	}

        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getStaffMonthlySalemanByBiller($user_id, $year,$biller)
    {
        $user = $this->site->getUser();
        if(empty($biller))
        {
            if(!$this->Owner && !$this->Admin)
            {
                $biller=$user->biller_id;
            }else
            {
                $biller="";
            }
        }
        $biller=json_decode($biller);
        if(is_array($biller)){
            $b="";
            foreach ($biller as $key ) {
                $b.=$key.",";

            }
            $b=rtrim($b,",");
            //$biller='';
        }else{
            $b=$biller;
        }
        $biller=$biller?' AND erp_sales.biller_id IN ('.$b.')':'';
        $user_biller_id = $this->session->userdata('biller_id');
        if ($user_biller_id != NULL) {
            $myQuery = "SELECT DATE_FORMAT( erp_sales.date,  '%c' ) AS date, 
			SUM( COALESCE( erp_sales.product_tax, 0 ) ) AS tax1, 
			SUM( COALESCE( erp_sales.order_tax, 0 ) ) AS tax2, 
			SUM( COALESCE( erp_sales.total, 0 ) ) AS total,
			SUM( COALESCE( erp_sales.total_discount, 0 ) ) AS discount, 
			SUM( COALESCE( erp_sales.order_discount, 0 ) ) AS order_discount, 
			SUM( COALESCE( erp_sales.shipping, 0 ) ) AS shipping,SUM(COALESCE(erp_return_sales.grand_total,0))as t_return
	            FROM " . $this->db->dbprefix('sales') . " LEFT JOIN erp_return_sales ON erp_return_sales.sale_id=erp_sales.id
	            WHERE erp_sales.biller_id = {$user_biller_id} AND (CASE WHEN saleman_by <> '' THEN saleman_by ELSE erp_sales.created_by END) = {$user_id} AND DATE_FORMAT( erp_sales.date,  '%Y' ) =  '{$year}'
	            GROUP BY date_format( erp_sales.date, '%c' ) ORDER BY date_format( erp_sales.date, '%c' ) ASC";
        } else {
            $myQuery = "SELECT DATE_FORMAT( erp_sales.date,  '%c' ) AS date, 
			SUM( COALESCE( erp_sales.product_tax, 0 ) ) AS tax1, 
			SUM( COALESCE( erp_sales.order_tax, 0 ) ) AS tax2, 
			SUM( COALESCE( erp_sales.total, 0 ) ) AS total,
			SUM( COALESCE( erp_sales.total_discount, 0 ) ) AS discount, 
			SUM( COALESCE( erp_sales.order_discount, 0 ) ) AS order_discount, 
			SUM( COALESCE( erp_sales.shipping, 0 ) ) AS shipping,SUM(COALESCE(erp_return_sales.grand_total,0))as t_return
	            FROM " . $this->db->dbprefix('sales') . " LEFT JOIN erp_return_sales ON erp_return_sales.sale_id=erp_sales.id
	            WHERE (CASE WHEN saleman_by <> '' THEN saleman_by ELSE erp_sales.created_by END) = {$user_id} AND DATE_FORMAT( erp_sales.date,  '%Y' ) =  '{$year}'".$biller."
	            GROUP BY date_format( erp_sales.date, '%c' ) ORDER BY date_format( erp_sales.date, '%c' ) ASC";
        }

        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getStaffMonthlySaleman1($user_id, $year)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, 
		SUM( COALESCE( product_tax, 0 ) ) AS tax1, 
		SUM( COALESCE( order_tax, 0 ) ) AS tax2, 
		SUM( COALESCE( total, 0 ) ) AS total,
		SUM( COALESCE( total_discount, 0 ) ) AS discount, 
		SUM( COALESCE( order_discount, 0 ) ) AS order_discount, 
		SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . "
            WHERE created_by  = {$user_id} AND DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    public function getMonthlyReportByPM($user_id, $year)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( subtotal, 0 ) ) AS subtotal, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( order_discount, 0 ) ) AS order_discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            LEFT JOIN " . $this->db->dbprefix('sale_items') . " as sale_items ON sale_items.sale_id = " . $this->db->dbprefix('sales') . ".id
            LEFT JOIN " . $this->db->dbprefix('users') . " as users ON users.id = " . $this->db->dbprefix('sales') . ".assign_to_id
            WHERE DATE_FORMAT( date,  '%Y' ) =  '{$year}' AND users.id = '{$user_id}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStaffMonthlySales($user_id, $year)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( product_tax, 0 ) ) AS tax1, SUM( COALESCE( order_tax, 0 ) ) AS tax2, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( total_discount, 0 ) ) AS discount, SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('sales') . "
            WHERE created_by = {$user_id} AND DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPurchasesTotals($supplier_id)
    {
        $this->db->select('SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where_in('supplier_id', json_decode($supplier_id));
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getSupplierPurchases($supplier_id)
    {
        $this->db->from('purchases')->where_in('supplier_id', json_decode($supplier_id));
        return $this->db->count_all_results();
    }


    public function getRoomPurchases($room_id)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('suspend_note', $room_id);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getStaffPurchases($user_id)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('created_by', $user_id);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getStaffSales($user_id)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('created_by', $user_id);
        $q = $this->db->get('saless');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getStaffSaleman($user_id)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('saleman_by', $user_id);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	
	public function getStaffSalesProject($user_id)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('assign_to_id', $user_id);
        $q = $this->db->get('saless');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getStaffSalemanProject($user_id)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('assign_to_id', $user_id);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getRoomSales($room_id)
    {
        $this->db->select('count(id) as total, SUM(COALESCE(grand_total, 0)) as total_amount, SUM(COALESCE(paid, 0)) as paid', FALSE)
            ->where('suspend_note', $room_id);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getTotalSales($start, $end)
    {
    	$user_id = $this->session->userdata('user_id');
        $user_biller_id = $this->session->userdata('biller_id');

        $this->db
             ->select('count(DISTINCT erp_sales.id) as total, 
             (select sum(grand_total) from erp_sales) as total_amount, 
             SUM(IF((erp_payments.paid_by != "deposit" AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) as paid,
             sum(erp_payments.discount) as discount,
             SUM(DISTINCT total_tax) as tax', FALSE)
            ->join('erp_payments','erp_payments.sale_id=erp_sales.id','left')
             ->where('sale_status !=', 'pending')
             ->where('erp_sales.date BETWEEN ' . $start . ' and ' . $end);

		if($user_biller_id != NULL){
			$this->db->where('sales.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('sales.created_by', $user_id);
			}
		}

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalPurchases($start, $end)
    {
    	$user_id = $this->session->userdata('user_id');
    	$user_biller_id = $this->session->userdata('biller_id');

        $this->db
             ->select('
             count(DISTINCT erp_purchases.id) as total,
              (select sum(grand_total) from erp_purchases) as total_amount,
              SUM(IF((erp_payments.paid_by != "deposit" AND ISNULL(erp_payments.return_id)), erp_payments.amount, IF(NOT ISNULL(erp_payments.return_id), ((-1)*erp_payments.amount), 0))) as paid, 
              SUM(COALESCE(total_tax, 0)) as tax', FALSE)
            ->join('erp_payments','erp_payments.purchase_id=erp_purchases.id','left')
                ->where('purchases.status', 'received')
			 ->where('purchases.date BETWEEN ' . $start . ' and ' . $end);

		if($user_biller_id != NULL){
			$this->db->where('purchases.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('purchases.created_by', $user_id);
			}
		}

        $q = $this->db->get('purchases');
		
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalPaidAmount($start, $end)
    {
    	$user_id = $this->session->userdata('user_id');
    	$user_biller_id = $this->session->userdata('biller_id');

        $this->db
            ->select('count(erp_payments.id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'sent')
            ->where('date BETWEEN ' . $start . ' and ' . $end);

		if($user_biller_id != NULL){
			$this->db->where('payments.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('payments.created_by', $user_id);
			}
		}

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalExpenses($start, $end)
    {
    	$user_id = $this->session->userdata('user_id');
    	$user_biller_id = $this->session->userdata('biller_id');

        $this->db
            ->select('count(erp_expenses.id) as total, sum(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('date BETWEEN ' . $start . ' and ' . $end);

		if($user_biller_id != NULL){
			$this->db->where('expenses.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('expenses.created_by', $user_id);
			}
		}

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReceivedAmount($start, $end)
    {
    	$user_id = $this->session->userdata('user_id');
		$user_biller_id = $this->session->userdata('biller_id');

        $this->db
             ->select('count(erp_payments.id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
             ->where('type', 'received')
             ->where('date BETWEEN ' . $start . ' and ' . $end);

		if($user_biller_id != NULL){
			$this->db->where('payments.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('payments.created_by', $user_id);
			}
		}

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReceivedCashAmount($start, $end)
    {
		$user_id = $this->session->userdata('user_id');
		$user_biller_id = $this->session->userdata('biller_id');

        $this->db
            ->select('count(erp_payments.id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'received')->where('paid_by', 'cash')
            ->where('date BETWEEN ' . $start . ' and ' . $end);

		if($user_biller_id != NULL){
			$this->db->where('payments.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('payments.created_by', $user_id);
			}
		}

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReceivedCCAmount($start, $end)
    {
    	$user_id = $this->session->userdata('user_id');
    	$user_biller_id = $this->session->userdata('biller_id');

        $this->db
            ->select('count(erp_payments.id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'received')->where('paid_by', 'CC')
            ->where('date BETWEEN ' . $start . ' and ' . $end);

		if($user_biller_id != NULL){
			$this->db->where('payments.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('payments.created_by', $user_id);
			}
		}

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReceivedChequeAmount($start, $end)
    {
    	$user_id = $this->session->userdata('user_id');
    	$user_biller_id = $this->session->userdata('biller_id');

        $this->db
            ->select('count(erp_payments.id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'received')->where('paid_by', 'Cheque')
            ->where('date BETWEEN ' . $start . ' and ' . $end);

		if($user_biller_id != NULL){
			$this->db->where('payments.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('payments.created_by', $user_id);
			}
		}

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReceivedPPPAmount($start, $end)
    {
    	$user_id = $this->session->userdata('user_id');
    	$user_biller_id = $this->session->userdata('biller_id');

        $this->db
            ->select('count(erp_payments.id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'received')->where('paid_by', 'ppp')
            ->where('date BETWEEN ' . $start . ' and ' . $end);

		if($user_biller_id != NULL){
			$this->db->where('payments.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('payments.created_by', $user_id);
			}
		}

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReceivedStripeAmount($start, $end)
    {
    	$user_id = $this->session->userdata('user_id');
    	$user_biller_id = $this->session->userdata('biller_id');

        $this->db
            ->select('count(erp_payments.id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'received')->where('paid_by', 'stripe')
            ->where('date BETWEEN ' . $start . ' and ' . $end);

		if($user_biller_id != NULL){
			$this->db->where('payments.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('payments.created_by', $user_id);
			}
		}

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getTotalReturnedAmount($start, $end)
    {
    	$user_id = $this->session->userdata('user_id');
    	$user_biller_id = $this->session->userdata('biller_id');

        $this->db
            ->select('count(erp_payments.id) as total, SUM(COALESCE(amount, 0)) as total_amount', FALSE)
            ->where('type', 'returned')
            ->where('date BETWEEN ' . $start . ' and ' . $end);

		if($user_biller_id != NULL){
			$this->db->where('payments.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('payments.created_by', $user_id);
			}
		}

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getWarehouseTotals($warehouse_id = NULL)
    {
        $this->db->select('sum(quantity) as total_quantity, count(id) as total_items', FALSE);
        $this->db->where('quantity !=', 0);
        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }
        $q = $this->db->get('warehouses_products');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
    public function getDailySaleRevenues($date, $start_date, $end_date)
    {
    	$this->db->select('
    						SUM(COALESCE(erp_sales.total_items, 0)) AS total_items,
	                        SUM(COALESCE(erp_sales.total, 0)) AS total, 
	                        SUM(COALESCE(erp_sales.total_discount, 0)) AS discount
    					', FALSE)
    		 	 ->from('sales');
		if ($start_date) {
			$this->db->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		} else {
			$this->db->where('erp_sales.date LIKE', "%$date%");
			$this->db->group_by('DATE_FORMAT(erp_sales.date, \'%e\')');
		}
			
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getDailySaleRevenuesByPM($date, $user_id)
    {
        $myQuery = "SELECT 
                        SUM(COALESCE(erp_sales.total_items, 0)) AS total_items,
                        SUM(COALESCE(erp_sales.total, 0)) AS total, 
                        SUM(COALESCE(erp_sales.total_discount, 0)) AS discount
            FROM " . $this->db->dbprefix('sales') . "
            LEFT JOIN erp_users ON erp_sales.assign_to_id = erp_users.id
            WHERE erp_sales.DATE LIKE  '%$date%'
            AND erp_users.id = $user_id
            GROUP BY DATE_FORMAT( date,  '%e' )";
            
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getCosting($date, $start_date, $end_date)
    {
        $this->db
            ->select('SUM( COALESCE( total_cost, 0 ) ) AS cost,SUM( COALESCE( total_items, 0 ) ) AS total_items, SUM( COALESCE( grand_total, 0 ) ) AS sales, SUM( total_tax + shipping + total_cost ) AS net_cost, SUM( total_tax + shipping + grand_total ) AS net_sales', FALSE)
			->where('pos', 1);

		if ($start_date) {
			$this->db->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		} else {
			$this->db->where("date >=", $date.' 00:00:00');
			$this->db->where("date <=", $date.' 23:55:00');
		}

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getCostingByPM($date, $user_id)
    {
        $this->db
            ->select('SUM( COALESCE( total_cost, 0 ) ) AS cost,SUM( COALESCE( total_items, 0 ) ) AS total_items, SUM( COALESCE( grand_total, 0 ) ) AS sales, SUM( total_tax + shipping + total_cost ) AS net_cost, SUM( total_tax + shipping + grand_total ) AS net_sales', FALSE)
            ->join('users', 'sales.assign_to_id = users.id', 'left')
            ->where("date >=", $date.' 00:00:00')
            ->where("date <=", $date.' 23:55:00')
            ->where('pos !=', 1)
            ->where('users.id', $user_id);

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getSaleDaily($date)
    {
		$this->db->select("
		date, 
		reference_no,
		customer, 
		total_discount,
		grand_total, 
		paid,			
		(grand_total-paid) as balance, 
		payment_status")
			->where("date >=", $date.' 00:00:00')
			->where("date <=", $date.' 23:55:00');
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
	}

    public function getSaleDailyByPM($date, $user_id)
    {
        $this->db
            ->select("
                date, 
                reference_no,
                customer, 
                total_discount,
                grand_total, 
                paid,           
                (grand_total-paid) as balance, 
                payment_status")
            ->join('users', 'sales.assign_to_id = users.id', 'left')
            ->where("date >=", $date.' 00:00:00')
            ->where("date <=", $date.' 23:55:00')
            ->where('users.id', $user_id);
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
	
	public function getSaleMonthly($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
		
		$this->db->select("
						date, 
						reference_no,
						customer, 
						total_discount,
						grand_total, 
						paid,			
						(grand_total-paid) as balance, 
						payment_status");

		if($date) {
            $this->db->where('purchases.date', $date);
        }elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('sales.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('sales.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }
		 $q = $this->db->get('sales');
		if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
		
	}

    public function getSaleMonthlyByPM($user_id, $date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $this->db->select("
                        date, 
                        reference_no,
                        customer, 
                        total_discount,
                        grand_total, 
                        paid,           
                        (grand_total-paid) as balance, 
                        payment_status");

        if($date) {
            $this->db->where('purchases.date', $date);
        }elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('sales.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('sales.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }
         $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
        
    }
	
	public function getPurchaseing($date)
    {
        if (!$this->Owner && !$this->Admin && $this->session->userdata('warehouse_id')) {
            $warehouse_ids = $this->session->userdata('warehouse_id');
            $warehouse_id = explode(',',$warehouse_ids);
        }
		$user_biller_id = json_decode($this->session->userdata('biller_id'));
        $this->db->select("date, reference_no, supplier, status, total_discount, grand_total, paid, (grand_total-paid) as balance, payment_status")
			->where("date >=", $date.' 00:00:00')
			->where("date <=", $date.' 23:55:00');

        if (!$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $user_id = $this->session->userdata('user_id');
            if($user_id != NULL){
                $this->db->where_in('purchases.created_by', $user_id);
            }
        }
        if($user_biller_id != NULL){
            $this->db->where_in('purchases.biller_id', $user_biller_id);
            $this->db->where_in('purchases.warehouse_id', $warehouse_id);

        }

        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
	public function getSaleDailies($date)
{
    $this->db->select("
            date, 
            reference_no, 
            customer, 
            sale_status, 
            total_discount,
            grand_total, 
         
            (grand_total-paid) as balance, 
            payment_status,
            COALESCE ( ( SELECT SUM( erp_return_sales.grand_total ) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id ), 0 ) AS return_sale,
                COALESCE (
                    (
                    SELECT
                        SUM(
                        IF
                            (
                                ( erp_payments.paid_by != 'deposit' AND ISNULL( erp_payments.return_id ) ),
                                erp_payments.amount,
                            IF
                                ( NOT ISNULL( erp_payments.return_id ), ( ( - 1 ) * erp_payments.amount ), 0 ) 
                            ) 
                        ) 
                    FROM
                        erp_payments 
                    WHERE
                        erp_payments.sale_id = erp_sales.id 
                    ),
                    0 
                ) AS paid,
                COALESCE (
                ( SELECT SUM( IF ( erp_payments.paid_by = 'deposit', erp_payments.amount, 0 ) ) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id ),
                0 
            ) AS deposit
        ")
        ->where("date >=", $date.' 00:00:00')
        ->where("date <=", $date.' 23:55:00');

    $q = $this->db->get('sales');
    if ($q->num_rows() > 0) {
        return $q->result();
    }
    return false;
}
    public function getSaleDailiesByBiller($date,$biller)
    {
        $this->db->select("
            date, 
            reference_no, 
            customer, 
            sale_status, 
            total_discount,
            grand_total, 
         
            (grand_total-paid) as balance, 
            payment_status,
            COALESCE ( ( SELECT SUM( erp_return_sales.grand_total ) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id ), 0 ) AS return_sale,
                COALESCE (
                    (
                    SELECT
                        SUM(
                        IF
                            (
                                ( erp_payments.paid_by != 'deposit' AND ISNULL( erp_payments.return_id ) ),
                                erp_payments.amount,
                            IF
                                ( NOT ISNULL( erp_payments.return_id ), ( ( - 1 ) * erp_payments.amount ), 0 ) 
                            ) 
                        ) 
                    FROM
                        erp_payments 
                    WHERE
                        erp_payments.sale_id = erp_sales.id 
                    ),
                    0 
                ) AS paid,
                COALESCE (
                ( SELECT SUM( IF ( erp_payments.paid_by = 'deposit', erp_payments.amount, 0 ) ) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id ),
                0 
            ) AS deposit
        ")
            ->where("date >=", $date.' 00:00:00')
            ->where("date <=", $date.' 23:55:00');
        if($biller){$this->db->where('erp_sales.biller_id',$biller);}

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
	public function getSaleDailieStaff($id,$date)
    {
		$user_id = $this->session->userdata('user_id');
		$user_biller_id = $this->session->userdata('biller_id');

        $this->db->select("date, reference_no, customer, sale_status, total_discount, grand_total, paid, (grand_total-paid) as balance, payment_status")
		    ->where('sales.saleman_by',$id)
			->where("date >=", $date.' 00:00:00')
			->where("date <=", $date.' 23:55:00');

		if($user_biller_id != NULL){
			$this->db->where('sales.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('sales.created_by', $user_id);
			}
		}


        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
	public function getPurchaseDailieStaff($id,$date)
    {
		$user_biller_id = $this->session->userdata('biller_id');
        $this->db->select("date, reference_no, supplier, status, total_discount, grand_total, paid, (grand_total-paid) as balance, payment_status")
		    ->where('purchases.created_by',$id)
			->where("date >=", $date.' 00:00:00')
			->where("date <=", $date.' 23:55:00');

		if($user_biller_id != NULL){
			$this->db->where('purchases.biller_id', $user_biller_id);
		}

        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
	public function getMonthCosting($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $this->db->select('SUM( COALESCE( total_cost, 0 ) ) AS cost, SUM( COALESCE( total, 0 ) ) AS sales, SUM( total_tax + shipping + total_cost ) AS net_cost, SUM( total_tax + shipping + grand_total ) AS net_sales', FALSE);
		
		if($date) {
            $this->db->where('sales.date', $date);
        }elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('sales.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('sales.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }

        if ($warehouse_id) {
            //$this->db->join('sales', 'sales.id=costing.sale_id')
            $this->db->where('sales.warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getMonthCostingByPM($user_id, $date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $this->db->select('SUM( COALESCE( total_cost, 0 ) ) AS cost, SUM( COALESCE( grand_total, 0 ) ) AS sales, SUM( total_tax + shipping + total_cost ) AS net_cost, SUM( total_tax + shipping + grand_total ) AS net_sales', FALSE);
        
        if($date) {
            $this->db->where('sales.date', $date);
        }elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('sales.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('sales.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }

        if ($warehouse_id) {
            //$this->db->join('sales', 'sales.id=costing.sale_id')
            $this->db->where('sales.warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getMonthPurchaseing($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $user_biller_id = json_decode($this->session->userdata('biller_id'));

        $this->db->select("date, reference_no, supplier, total_discount, status, grand_total, paid, (grand_total-paid) as balance, payment_status");
				
		if($date) {
            $this->db->where('purchases.date', $date);
        }elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('purchases.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('purchases.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
			
        }

		if($user_biller_id != NULL){
			$this->db->where_in('purchases.biller_id', $user_biller_id);
		}

		
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
	public function getMonthSales($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
//        $this->db->select("date, reference_no, customer, total_discount, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status");
//
        $this->db->select("
            date, 
            reference_no, 
            customer, 
            sale_status, 
            total_discount,
            grand_total, 
         
            (grand_total-paid) as balance, 
            payment_status,
            COALESCE ( ( SELECT SUM( erp_return_sales.grand_total ) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id ), 0 ) AS return_sale,
                COALESCE (
                    (
                    SELECT
                        SUM(
                        IF
                            (
                                ( erp_payments.paid_by != 'deposit' AND ISNULL( erp_payments.return_id ) ),
                                erp_payments.amount,
                            IF
                                ( NOT ISNULL( erp_payments.return_id ), ( ( - 1 ) * erp_payments.amount ), 0 ) 
                            ) 
                        ) 
                    FROM
                        erp_payments 
                    WHERE
                        erp_payments.sale_id = erp_sales.id 
                    ),
                    0 
                ) AS paid,
                COALESCE (
                ( SELECT SUM( IF ( erp_payments.paid_by = 'deposit', erp_payments.amount, 0 ) ) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id ),
                0 
            ) AS deposit
        ");
		if($date) {
            $this->db->where('sales.date', $date);
        }elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('sales.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('sales.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }
		
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
    public function getMonthSalesByBiller($date, $warehouse_id = NULL, $year = NULL, $month = NULL,$biller=NULL)
    {
//        $this->db->select("date, reference_no, customer, total_discount, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status");
//
        $this->db->select("
            date, 
            reference_no, 
            customer, 
            sale_status, 
            total_discount,
            grand_total, 
         
            (grand_total-paid) as balance, 
            payment_status,
            COALESCE ( ( SELECT SUM( erp_return_sales.grand_total ) FROM erp_return_sales WHERE erp_return_sales.sale_id = erp_sales.id ), 0 ) AS return_sale,
                COALESCE (
                    (
                    SELECT
                        SUM(
                        IF
                            (
                                ( erp_payments.paid_by != 'deposit' AND ISNULL( erp_payments.return_id ) ),
                                erp_payments.amount,
                            IF
                                ( NOT ISNULL( erp_payments.return_id ), ( ( - 1 ) * erp_payments.amount ), 0 ) 
                            ) 
                        ) 
                    FROM
                        erp_payments 
                    WHERE
                        erp_payments.sale_id = erp_sales.id 
                    ),
                    0 
                ) AS paid,
                COALESCE (
                ( SELECT SUM( IF ( erp_payments.paid_by = 'deposit', erp_payments.amount, 0 ) ) FROM erp_payments WHERE erp_payments.sale_id = erp_sales.id ),
                0 
            ) AS deposit
        ");
        if($date) {
            $this->db->where('sales.date', $date);
        }elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('sales.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('sales.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }
        if($biller){
            $this->db->where('sales.biller_id', $biller);
        }
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
	public function getMonthSale($id,$date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
		$user_id = $this->session->userdata('user_id');
		$user_biller_id = $this->session->userdata('biller_id');

        $this->db->select("date, reference_no, customer, total_discount, sale_status, grand_total, paid, (grand_total-paid) as balance, payment_status");
		
		if($date) {
            $this->db->where('sales.date', $date);
			
        }elseif ($month){
            
            $last_day = days_in_month($month, $year); 
            $this->db->where('sales.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('sales.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }
		$this->db->where('sales.saleman_by',$id);

		if($user_biller_id != NULL){
			$this->db->where('sales.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('sales.created_by', $user_id);
			}
		}


        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }
	public function getMonthPurchase($id,$date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $this->db->select("date, reference_no, supplier, total_discount,status, grand_total, paid, (grand_total-paid) as balance, payment_status");
		
		if($date) {
            $this->db->where('purchases.date', $date);
			
        }elseif ($month){
            
            $last_day = days_in_month($month, $year); 
            $this->db->where('purchases.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('purchases.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }
		$this->db->where('purchases.created_by',$id);
        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

    public function getMonthlyPurchase($id,$date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
		$user_id = $this->session->userdata('user_id');
		$user_biller_id = $this->session->userdata('biller_id');

        $this->db->select("date, reference_no, supplier, total_discount, status, grand_total, paid, (grand_total-paid) as balance, payment_status");
		
		if($date) {
            $this->db->where('purchases.date', $date);
			
        }elseif ($month){
            
            $last_day = days_in_month($month, $year); 
            $this->db->where('purchases.date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('purchases.date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }

		if($user_biller_id != NULL){
			$this->db->where('purchases.biller_id', $user_biller_id);
		}

		// View Rights
		if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
			if ($user_id) {
				$this->db->where('purchases.created_by', $user_id);
			}
		}


        $q = $this->db->get('purchases');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;
    }

	public function getShipping($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db->select('SUM( COALESCE( shipping, 0 ) ) AS shippings', FALSE);
        if ($date) {
            $this->db->where('date >=', $sdate)->where('date <=', $edate);
        } elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }

        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	public function getOrderTax($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db->select('SUM( COALESCE( order_tax, 0 ) ) AS order_taxs', FALSE);
        if ($date) {
            $this->db->where('date >=', $sdate)->where('date <=', $edate);
        } elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }

        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	public function getOrderDiscount($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db->select('SUM( COALESCE( order_discount, 0 ) ) AS order_discount', FALSE);
        if ($date) {
            $this->db->where('date >=', $sdate)->where('date <=', $edate);
        } elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }

        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getOrderDiscountByPM($user_id, $date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        // $this->erp->print_arrays($year);
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db->select('SUM( COALESCE( order_discount, 0 ) ) AS order_discount', FALSE);
        if ($date) {
            $this->db->where('date >=', $sdate)->where('date <=', $edate);
        } elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }

        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return false;
    }

    public function getExpenses($date, $start_date, $end_date)
    {
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db
            ->select('SUM( COALESCE( amount, 0 ) ) AS total,count( COALESCE( id, 0 ) ) AS count_ex', FALSE);
        
        if ($start_date) {
			$this->db->where($this->db->dbprefix('expenses').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		} else {
			$this->db->where('date >=', $sdate)->where('date <=', $edate);
		}

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getExpensesByPM($date, $user_id)
    {
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db
            ->select('SUM( COALESCE( amount, 0 ) ) AS total,count( COALESCE( erp_expenses.id, 0 ) ) AS count_ex', FALSE)
            ->join('users', 'expenses.created_by = users.id', 'left')
            ->where('date >=', $sdate)->where('date <=', $edate)
            ->where('users.id', $user_id);

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getExpense($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE);
        if ($date) {
            $this->db->where('date >=', $sdate)->where('date <=', $edate);
        } elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }
        

        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	
	public function getReturns($date, $warehouse_id = NULL, $year = NULL, $month = NULL)
    {
        $sdate = $date.' 00:00:00';
        $edate = $date.' 23:59:59';
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total', FALSE)
        ->where('sale_status', 'returned');
        if ($date) {
            $this->db->where('date >=', $sdate)->where('date <=', $edate);
        } elseif ($month) {
            $this->load->helper('date');
            $last_day = days_in_month($month, $year);
            $this->db->where('date >=', $year.'-'.$month.'-01 00:00:00');
            $this->db->where('date <=', $year.'-'.$month.'-'.$last_day.' 23:59:59');
        }

        if ($warehouse_id) {
            $this->db->where('warehouse_id', $warehouse_id);
        }

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
    
	public function getSaleDetail($product_code)
    {
        $this->db->order_by('sale_items.id', 'asc');
		$this->db->join('sales', 'sales.id = sale_items.sale_id', 'left');
        $q = $this->db->get_where('sale_items', array('product_code' => $product_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getPurchaseDetail($product_code)
    {
		$this->db->select('*');
		$this->db->from('purchase_items');
		$this->db->join('purchases', 'purchase_items.purchase_id = purchases.id');
		$this->db->where('purchase_items.product_code', $product_code);
		$this->db->where('purchase_items.status <>', 'ordered');
        //$this->db->order_by('id', 'asc');
		$q = $this->db->get();
        //$q = $this->db->get_where('purchase_items', array('product_code' => $product_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getPurchaseDetailSupplier($product_code, $supplier_id)
    {	
		$this->db->select('*');
		$this->db->from('purchase_items');
		$this->db->join('purchases', 'purchase_items.purchase_id = purchases.id');
		$this->db->where('purchase_items.product_code', $product_code);
		$this->db->where('purchases.supplier_id', $supplier_id);
		$this->db->where('purchase_items.status <>', 'ordered');
        //$this->db->order_by('id', 'asc');
		$q = $this->db->get();
        //$q = $this->db->get_where('purchase_items', array('product_code' => $product_code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	public function Count_Sale_discount($date, $start_date, $end_date)
	{
		$this->db
	        ->select("count( ( COALESCE(erp_sales.id, 0 ) ) ) AS count_id")
	        ->from('sales');

	    if ($start_date) {
	    	$this->db->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
	    } else {
	    	$this->db->where('date_format(erp_sales.date, "%Y-%m-%d") =', $date);
	    	$this->db->where('sales.order_discount <>', '');
		}

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}

    public function Count_Sale_shipping($date, $start_date, $end_date)
    {
    	$this->db
	        ->select("count( ( COALESCE(erp_sales.id, 0 ) ) ) AS count_id")
	        ->from('sales');

	    if ($start_date) {
	    	$this->db->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
	    } else {
	    	$this->db->where('date_format(erp_sales.date, "%Y-%m-%d") =', $date);
	    	$this->db->where('sales.shipping <>', '');
		}

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}

    public function countSaleDiscountByPM($date, $user_id){
        
        $myQuery = "SELECT count( ( COALESCE( erp_sales.id, 0 ) ) ) AS count_id
                FROM erp_sales
                LEFT JOIN erp_users ON erp_sales.assign_to_id = erp_users.id
                WHERE DATE_FORMAT( date,  '%Y-%m-%d' ) =  '{$date}' and order_discount!=''
                AND erp_users.id = $user_id";
            
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	

	public function getSalesReturnDate($date, $start_date, $end_date)
    {
		$this->db
	        ->select("
	        		SUM( COALESCE( ABS({$this->db->dbprefix('return_sales')}.grand_total), 0 ) ) AS paid,
	        		SUM( ( COALESCE( quantity, 0 ) ) ) AS quantity,
	        		SUM(( COALESCE( {$this->db->dbprefix('sales')}.order_discount, 0 ) ) ) AS order_discount,
	        		SUM(( COALESCE( {$this->db->dbprefix('sales')}.shipping, 0 ) ) ) AS shippings,
	        		SUM(( COALESCE( {$this->db->dbprefix('sales')}.order_tax, 0 ) ) ) AS order_taxs", FALSE)
		    ->join('return_sales', 'sales.return_id=return_sales.id', 'left')
		    ->join('return_items', 'return_items.return_id=return_sales.id', 'left');

		if ($start_date) {
			$this->db->where($this->db->dbprefix('sales').'.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
		} else {
			$this->db->where("DATE({$this->db->dbprefix('sales')}.date)", $date);
		}

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getSalesReturnDateByPM($date, $user_id)
    {
        $this->db
            ->select("SUM( COALESCE( ABS({$this->db->dbprefix('return_sales')}.grand_total), 0 ) ) AS paid, SUM( ( COALESCE( quantity, 0 ) ) ) AS quantity, SUM(( COALESCE( {$this->db->dbprefix('sales')}.order_discount, 0 ) ) ) AS order_discount", FALSE)
            ->join('return_sales', 'sales.return_id=return_sales.id', 'left')
            ->join('return_items', 'return_items.return_id=return_sales.id', 'left')
            ->join('users', 'sales.assign_to_id = users.id', 'left')
            ->where("DATE({$this->db->dbprefix('sales')}.date)", $date)
            ->where('users.id', $user_id);

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

	public function getTotalDiscountDate($date)
    {
		 $this->db->select('SUM( COALESCE( total_discount, 0 ) ) AS discount', FALSE)
        ->where('DATE(date)', $date);

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTotalDiscountDateByPM($date, $user_id)
    {
         $this->db
             ->select('SUM( COALESCE( total_discount, 0 ) ) AS discount', FALSE)
             ->join('users', 'sales.assign_to_id = users.id', 'left')
             ->where('DATE(date)', $date)
             ->where('users.id', $user_id);

        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

	public function getTotalCosts($start, $end)
    {
        $this->db
             ->select('SUM( COALESCE( purchase_unit_cost, 0 ) * quantity ) AS cost', FALSE)
             ->where('date BETWEEN ' . $start . ' and ' . $end);

        $q = $this->db->get('costing');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	public function getDailyPurchases($year, $month, $warehouse_id = NULL, $user_warehouse_id = NULL)
    {
        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, 
		SUM( COALESCE( product_tax, 0 ) ) AS tax1, 
		SUM( COALESCE( order_tax, 0 ) ) AS tax2, 
		SUM( COALESCE( total, 0 ) ) AS total, 
		SUM( COALESCE( order_discount, 0 ) ) AS discount, 
		SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . " WHERE ";
        if ($user_warehouse_id) {
            $myQuery .= " warehouse_id in ({$user_warehouse_id}) AND ";
        }
        if ($warehouse_id) {
            $myQuery .= " warehouse_id in ({$warehouse_id}) AND ";
        }
        $myQuery .= " DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getMonthlyPurchases($year, $warehouse_id = NULL, $user_warehouse_id = NULL)
    {

        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, 
		SUM( COALESCE( product_tax, 0 ) ) AS tax1, 
		SUM( COALESCE( order_tax, 0 ) ) AS tax2, 
		SUM( COALESCE( total, 0 ) ) AS total, 
		SUM( COALESCE( order_discount, 0 ) ) AS discount, 
		SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases') . " WHERE ";
        if ($user_warehouse_id) {
            $myQuery .= " warehouse_id in ({$user_warehouse_id}) AND ";
        }
        if ($warehouse_id) {
            $myQuery .= " warehouse_id in ({$warehouse_id}) AND ";
        }
        $myQuery .= " DATE_FORMAT( date,  '%Y' ) =  '{$year}'
            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStaffDailyPurchases($user_id, $year, $month, $warehouse_id = NULL)
    {
        $user_biller_id = json_decode($this->session->userdata('biller_id'));
        $usr_biller_id = implode(',',$user_biller_id);

        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, 
		SUM( COALESCE( product_tax, 0 ) ) AS tax1, 
		SUM( COALESCE( order_tax, 0 ) ) AS tax2, 
		SUM( COALESCE( total, 0 ) ) AS total, 
		SUM( COALESCE( order_discount, 0 ) ) AS discount, 
		SUM( COALESCE( shipping, 0 ) ) AS shipping
            FROM " . $this->db->dbprefix('purchases')." LEFT JOIN erp_users ON erp_purchases.created_by = erp_users.id WHERE ";
        if ($warehouse_id) {
            $myQuery .= " erp_purchases.warehouse_id in ({$warehouse_id}) AND ";
        }
        $myQuery .= " erp_purchases.biller_id in({$usr_biller_id}) AND created_by = {$user_id} AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
            GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStaffMonthlyPurchases($user_id, $year, $warehouse_id = NULL)
    {
    	$user_biller_id = json_decode($this->session->userdata('biller_id'));
    	$usr_biller_id = implode(',',$user_biller_id);

    	if ($user_id) {
    		$myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, 
			SUM( COALESCE( product_tax, 0 ) ) AS tax1, 
			SUM( COALESCE( order_tax, 0 ) ) AS tax2, 
			SUM( COALESCE( total, 0 ) ) AS total, 
			SUM( COALESCE( order_discount, 0 ) ) AS discount, 
			SUM( COALESCE( shipping, 0 ) ) AS shipping
	            FROM " . $this->db->dbprefix('purchases') . " LEFT JOIN erp_users ON erp_purchases.created_by = erp_users.id WHERE ";
            if ($warehouse_id) {
                $myQuery .= " erp_purchases.warehouse_id in({$warehouse_id}) AND ";
            }
	        $myQuery .= " erp_purchases.biller_id in ({$usr_biller_id}) AND created_by = {$user_id} AND DATE_FORMAT( date,  '%Y' ) =  '{$year}'
	            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
    	} else {
    		$myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, 
			SUM( COALESCE( product_tax, 0 ) ) AS tax1, 
			SUM( COALESCE( order_tax, 0 ) ) AS tax2, 
			SUM( COALESCE( total, 0 ) ) AS total, 
			SUM( COALESCE( order_discount, 0 ) ) AS discount, 
			SUM( COALESCE( shipping, 0 ) ) AS shipping
	            FROM " . $this->db->dbprefix('purchases') . " LEFT JOIN erp_users ON erp_purchases.created_by = erp_users.id WHERE ";
	        if ($warehouse_id) {
	            $myQuery .= " erp_purchases.warehouse_id in({$warehouse_id}) AND ";
	        }
	        $myQuery .= " created_by = {$user_id} AND DATE_FORMAT( date,  '%Y' ) =  '{$year}'
	            GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
    	}
        
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getReportW($product = NULL, $category = NULL, $supplier = NULL, $start_date = NULL, $end_date = NULL){
		$where_purchase = "where 1=1 AND {$this->db->dbprefix('purchase_items')}.status <> 'ordered' AND {$this->db->dbprefix('purchase_items')}.purchase_id != ''";
		$where_sale='where 1=1';
		if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date = $end_date ? $this->erp->fld($end_date) : date('Y-m-d');

            $pp = "( SELECT pi.product_id, 
						SUM( pi.quantity * (CASE WHEN pi.option_id <> 0 THEN pi.vqty_unit ELSE 1 END) ) purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM((CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) *  tpi.quantity_balance ) balacneValue, 
						SUM( pi.unit_cost * pi.quantity ) totalPurchase, 
                        SUM(pi.unit_cost) AS totalCost,
						SUM(pi.quantity) AS Pquantity,
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity 
									FROM erp_purchase_items 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									WHERE {$this->db->dbprefix('purchase_items')}.date >= '{$start_date}' AND {$this->db->dbprefix('purchase_items')}.date < '{$end_date}' 
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} 
										GROUP BY product_id ) tpi on tpi.product_id = pi.product_id 
						GROUP BY pi.product_id ) PCosts";

			$sp = "( SELECT si.product_id, 
						SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)) soldQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						WHERE s.date >= '{$start_date}' AND s.date < '{$end_date}' 
						GROUP BY si.product_id ) PSales";

			$ppb = "( SELECT pi.product_id, 
						SUM( pi.quantity ) purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM( (CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) *  tpi.quantity_balance ) balacneValue, 
						SUM( pi.unit_cost * pi.quantity ) totalPurchase, 
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity 
									FROM erp_purchase_items 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									WHERE {$this->db->dbprefix('purchase_items')}.date < '{$start_date}'
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} 
										GROUP BY product_id ) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCostsBegin";
            
			$spb = "( SELECT si.product_id, 
						SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)) saleQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						WHERE s.date < '{$start_date}'
						GROUP BY si.product_id ) PSalesBegin";
        } 
		else {
			$current_date = date('Y-m-d');
			$prevouse_date = date('Y').'-'.date('m').'-'.'01';
			$pp = "( SELECT pi.product_id, 
						SUM( pi.quantity * (CASE WHEN pi.option_id <> 0 THEN pi.vqty_unit ELSE 1 END) ) purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM( (CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) *  tpi.quantity_balance ) balacneValue, 
						SUM( pi.unit_cost * pi.quantity ) totalPurchase, 
                        SUM(pi.unit_cost) AS totalCost,
						SUM(pi.quantity) AS Pquantity,
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost ,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity
									FROM {$this->db->dbprefix('purchase_items')} 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									".$where_purchase." 
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 			
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} GROUP BY product_id 
									) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCosts";

			$sp = "( SELECT si.product_id, 
						COALESCE(SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)),0) soldQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						".$where_sale."
						GROUP BY si.product_id ) PSales";

			
			$ppb = "( SELECT pi.product_id, 
						SUM(pi.quantity) AS purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM( (CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) * tpi.quantity_balance ) balacneValue, 
						SUM(pi.unit_cost * pi.quantity) totalPurchase, 
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost ,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity
									FROM {$this->db->dbprefix('purchase_items')} 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									".$where_purchase." 
									AND {$this->db->dbprefix('purchase_items')}.date < '{$prevouse_date}' 
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 			
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} 
										GROUP BY product_id ) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCostsBegin";
			
            $spb = "( SELECT si.product_id, 
						COALESCE(SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)),0) saleQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						".$where_sale."
						AND s.date < '{$prevouse_date}'
						GROUP BY si.product_id ) PSalesBegin";
			
        }
						
		$this->db->select($this->db->dbprefix('products') . ".id as product_id, 
				" . $this->db->dbprefix('products') . ".code as product_code, 
				" . $this->db->dbprefix('products') . ".name,
				COALESCE( PCostsBegin.purchasedQty-PSalesBegin.saleQty, 0 ) as BeginPS,
				CONCAT(COALESCE (PCosts.Pquantity, 0)) AS purchased,
				COALESCE( PSales.Squantity, 0 ) + COALESCE (
                        (
                            SELECT
                                SUM(si.quantity * ci.quantity)
                            FROM
                                ".$this->db->dbprefix('combo_items') . " ci
                            INNER JOIN erp_sale_items si ON si.product_id = ci.product_id
                            WHERE
                                ci.item_code = ".$this->db->dbprefix('products') . ".code
                        ),
                        0
                    ) as sold,
				COALESCE (COALESCE (
						PCostsBegin.purchasedQty-PSalesBegin.saleQty,
						0
					)+COALESCE (PCosts.Pquantity, 0) - COALESCE( PSales.Squantity , 0 ) -  COALESCE (
                        (
                            SELECT
                                SUM(si.quantity * ci.quantity)
                            FROM
								".$this->db->dbprefix('combo_items') . " ci
                            INNER JOIN erp_sale_items si ON si.product_id = ci.product_id
                            WHERE
                                ci.item_code = ".$this->db->dbprefix('products') . ".code
                        ),
                        0
                    ) ) AS balance", 
				FALSE)
				 ->from('products')
				 ->join($sp, 'products.id = PSales.product_id', 'left')
				 ->join($pp, 'products.id = PCosts.product_id', 'left')
				 ->join($spb, 'products.id = PSalesBegin.product_id', 'left')
                 ->join($ppb, 'products.id = PCostsBegin.product_id', 'left')
				 ->join('warehouses_products wp', 'products.id=wp.product_id', 'left')
				 ->join('categories', 'products.category_id=categories.id', 'left')
				 ->group_by("products.id");
		if($product){
			$this->db->where($this->db->dbprefix('products') . ".id", $product);
		}
		if ($category) {
			$this->db->where($this->db->dbprefix('products') . ".category_id", $category);
		}
		
		if($start_date && $end_date)
		{
			$this->db->where("( date(PCostsBegin.pdate) BETWEEN '{$start_date}' AND  '{$end_date}' 
			OR date(PSalesBegin.sdate) BETWEEN '{$start_date}' AND  '{$end_date}'
			OR date(PCosts.pdate) BETWEEN '{$start_date}' AND  '{$end_date}'
			OR date(PSales.sdate) BETWEEN '{$start_date}' AND  '{$end_date}' )");
		}
			
		if ($supplier) {
			$this->db->where("products.supplier1 = '".$supplier."' or products.supplier2 = '".$supplier."' or products.supplier3 = '".$supplier."' or products.supplier4 = '".$supplier."' or products.supplier5 = '".$supplier."'");
		}
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return false;		
	}
	
	public function getInOutByID($id){
		if ($this->input->get('product')) {
            $product = $this->input->get('product');
        } else {
            $product = NULL;
        }
        if ($this->input->get('category')) {
            $category = $this->input->get('category');
        } else {
            $category = NULL;
        }
        if ($this->input->get('in_out')) {
            $in_out = $this->input->get('in_out');
        } else {
            $in_out = NULL;
        }
        if ($this->input->get('start_date')) {
            $start_date = $this->input->get('start_date');
        } else {
            $start_date = NULL;
        }
        if ($this->input->get('end_date')) {
            $end_date = $this->input->get('end_date');
        } else {
            $end_date = NULL;
        }
		if ($this->input->get('supplier')) {
            $supplier = $this->input->get('supplier');
        } else {
            $supplier = NULL;
        }
		if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
			$where_sale='where si.warehouse_id='.$warehouse;
			$where_purchase="where {$this->db->dbprefix('purchase_items')}.warehouse_id=".$warehouse . "AND {$this->db->dbprefix('purchase_items')}.status <> 'ordered'";
        } else {
            $warehouse = NULL;
			$where_purchase = "where 1=1 AND {$this->db->dbprefix('purchase_items')}.status <> 'ordered' AND {$this->db->dbprefix('purchase_items')}.purchase_id != ''";
			//$where_purchase = "where 1=1 AND {$this->db->dbprefix('purchase_items')}.status <> 'ordered'";
			$where_sale='where 1=1';
        }
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date = $end_date ? $this->erp->fld($end_date) : date('Y-m-d');

            $pp = "( SELECT pi.product_id, 
						SUM( pi.quantity * (CASE WHEN pi.option_id <> 0 THEN pi.vqty_unit ELSE 1 END) ) purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM((CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) *  tpi.quantity_balance ) balacneValue, 
						SUM( pi.unit_cost * pi.quantity ) totalPurchase, 
                        SUM(pi.unit_cost) AS totalCost,
						SUM(pi.quantity) AS Pquantity,
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity 
									FROM erp_purchase_items 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									WHERE {$this->db->dbprefix('purchase_items')}.date >= '{$start_date}' AND {$this->db->dbprefix('purchase_items')}.date < '{$end_date}' 
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} 
										GROUP BY product_id ) tpi on tpi.product_id = pi.product_id 
						GROUP BY pi.product_id ) PCosts";

			$sp = "( SELECT si.product_id, 
						SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)) soldQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						WHERE s.date >= '{$start_date}' AND s.date < '{$end_date}' 
						GROUP BY si.product_id ) PSales";

			$ppb = "( SELECT pi.product_id, 
						SUM( pi.quantity ) purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM( (CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) *  tpi.quantity_balance ) balacneValue, 
						SUM( pi.unit_cost * pi.quantity ) totalPurchase, 
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity 
									FROM erp_purchase_items 
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									WHERE {$this->db->dbprefix('purchase_items')}.date < '{$start_date}'
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} 
										GROUP BY product_id ) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCostsBegin";
            
			$spb = "( SELECT si.product_id, 
						SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)) saleQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						WHERE s.date < '{$start_date}'
						GROUP BY si.product_id ) PSalesBegin";
        } else {
			$current_date = date('Y-m-d');
			$prevouse_date = date('Y').'-'.date('m').'-'.'01';
            //$pp = "( SELECT pi.product_id, SUM( pi.quantity ) purchasedQty, SUM( tpi.quantity_balance ) balacneQty, SUM( pi.unit_cost * tpi.quantity_balance ) balacneValue, SUM( pi.unit_cost * pi.quantity ) totalPurchase, pi.date as pdate from ( SELECT p.date as date, product_id, purchase_id, SUM(quantity) as quantity, unit_cost from erp_purchase_items JOIN {$this->db->dbprefix('purchases')} p on p.id = {$this->db->dbprefix('purchase_items')}.purchase_id GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi LEFT JOIN ( SELECT product_id, SUM(quantity_balance) as quantity_balance from {$this->db->dbprefix('purchase_items')} GROUP BY product_id ) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCosts";
            //$sp = "( SELECT si.product_id, SUM( si.quantity ) soldQty, SUM( si.subtotal ) totalSale, s.date as sdate from " . $this->db->dbprefix('sales') . " s JOIN " . $this->db->dbprefix('sale_items') . " si on s.id = si.sale_id GROUP BY si.product_id ) PSales";
			$pp = "( SELECT pi.product_id, 
						SUM( pi.quantity * (CASE WHEN pi.option_id <> 0 THEN pi.vqty_unit ELSE 1 END) ) purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM( (CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) *  tpi.quantity_balance ) balacneValue, 
						SUM( pi.unit_cost * pi.quantity ) totalPurchase, 
                        SUM(pi.unit_cost) AS totalCost,
						SUM(pi.quantity) AS Pquantity,
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost ,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity
									FROM {$this->db->dbprefix('purchase_items')} 
									LEFT JOIN " . $this->db->dbprefix('purchases') . " pp 
									ON pp.id = {$this->db->dbprefix('purchase_items')}.purchase_id  
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									".$where_purchase." 
									".$where_p_biller."
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 			
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} GROUP BY product_id 
									) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCosts";

			$sp = "( SELECT si.product_id, 
						COALESCE(SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)),0) soldQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						".$where_sale."
						".$where_s_biller."
						GROUP BY si.product_id ) PSales";

			
			$ppb = "( SELECT pi.product_id, 
						SUM(pi.quantity) AS purchasedQty, 
						SUM( tpi.quantity_balance ) balacneQty, 
						SUM( (CASE WHEN pi.option_id <> 0 THEN pi.vcost ELSE pi.unit_cost END) * tpi.quantity_balance ) balacneValue, 
						SUM(pi.unit_cost * pi.quantity) totalPurchase, 
						pi.date as pdate 
						FROM ( SELECT {$this->db->dbprefix('purchase_items')}.date as date, 
									{$this->db->dbprefix('purchase_items')}.product_id, 
									purchase_id, 
									SUM({$this->db->dbprefix('purchase_items')}.quantity) as quantity, 
									unit_cost ,
									option_id,
									ppv.qty_unit AS vqty_unit,
									ppv.cost AS vcost,
									ppv.quantity AS vquantity
									FROM {$this->db->dbprefix('purchase_items')} 
									LEFT JOIN " . $this->db->dbprefix('purchases') . " pp 
									ON pp.id={$this->db->dbprefix('purchase_items')}.purchase_id  
									JOIN {$this->db->dbprefix('products')} p 
									ON p.id = {$this->db->dbprefix('purchase_items')}.product_id 
									LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
									ON ppv.id={$this->db->dbprefix('purchase_items')}.option_id  
									".$where_purchase." 
									".$where_p_biller."
									AND {$this->db->dbprefix('purchase_items')}.date < '{$prevouse_date}' 
									GROUP BY {$this->db->dbprefix('purchase_items')}.product_id ) pi 			
						LEFT JOIN ( SELECT product_id, 
										SUM(quantity_balance) as quantity_balance 
										FROM {$this->db->dbprefix('purchase_items')} 
										GROUP BY product_id ) tpi on tpi.product_id = pi.product_id GROUP BY pi.product_id ) PCostsBegin";
			
            $spb = "( SELECT si.product_id, 
						COALESCE(SUM( si.quantity*(CASE WHEN si.option_id <> 0 THEN spv.qty_unit ELSE 1 END)),0) saleQty, 
						SUM( si.subtotal ) totalSale, 
						SUM( si.quantity) AS Squantity,
						s.date as sdate
						FROM " . $this->db->dbprefix('sales') . " s 
						JOIN " . $this->db->dbprefix('sale_items') . " si 
						ON s.id = si.sale_id 
						LEFT JOIN " . $this->db->dbprefix('product_variants') . " spv 
						ON spv.id=si.option_id
						".$where_sale."
						".$where_s_biller."
						AND s.date < '{$prevouse_date}'
						GROUP BY si.product_id ) PSalesBegin";
        }
			$year = date('Y');
			$month = date('m');
			$YMD = $this->site->months($year, $month);
			if($YMD->date == ""){
				$LYMD = '0000-00-00';
			}else{
				$LYMD = $YMD->date;
			}
		$this->db->select($this->db->dbprefix('products') . ".id as product_id, 
				" . $this->db->dbprefix('products') . ".code as product_code, 
				" . $this->db->dbprefix('products') . ".name,
				COALESCE ((
					SELECT 
						SUM(
							" . $this->db->dbprefix('purchase_items') . ".quantity_balance
						) AS quantity
					FROM
						". $this->db->dbprefix('purchase_items') ."
					JOIN " . $this->db->dbprefix('products') . "  p ON p.id = " . $this->db->dbprefix('purchase_items') . ".product_id
					LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv ON ppv.id =" . $this->db->dbprefix('purchase_items') . ".option_id 
					WHERE DATE_FORMAT(" . $this->db->dbprefix('purchase_items') . ".date, '%Y-%m-%d') = '".$LYMD."'
					AND  (p.id) = ".$id."
					AND " . $this->db->dbprefix('purchase_items') . ".status <> 'ordered'
					GROUP BY
						DATE_FORMAT(" . $this->db->dbprefix('purchase_items') . ".date, '%Y-%m'),
						erp_products.id
				), 0 ) as BeginPS,
				COALESCE (" . $this->db->dbprefix('products') . ".quantity, 0) - COALESCE (PCosts.Pquantity, 0) + COALESCE( PSales.Squantity, 0 )
					+ COALESCE (PCosts.Pquantity, 0) AS purchased,
				COALESCE( PSales.Squantity, 0 ) + COALESCE (
                        (
                            SELECT
                                SUM(si.quantity * ci.quantity)
                            FROM
                                ".$this->db->dbprefix('combo_items') . " ci
                            INNER JOIN erp_sale_items si ON si.product_id = ci.product_id
                            WHERE
                                ci.item_code = ".$this->db->dbprefix('products') . ".code
                        ),
                        0
                    ) as sold,
					COALESCE((
						COALESCE (erp_products.quantity, 0) - COALESCE (PCosts.Pquantity, 0) + COALESCE (PSales.Squantity, 0) + COALESCE (PCosts.Pquantity, 0)
					) - COALESCE (PSales.Squantity, 0) + COALESCE (
					(
						SELECT
							SUM(si.quantity * ci.quantity)
						FROM
							erp_combo_items ci
						INNER JOIN erp_sale_items si ON si.product_id = ci.product_id
						WHERE
							ci.item_code = erp_products. CODE
					),
					0
					), 0)
					AS balance", 
				FALSE)
				 ->from('products')
				 ->join($sp, 'products.id = PSales.product_id', 'left')
				 ->join($pp, 'products.id = PCosts.product_id', 'left')
				 ->join($spb, 'products.id = PSalesBegin.product_id', 'left')
                 ->join($ppb, 'products.id = PCostsBegin.product_id', 'left')
				 ->join('warehouses_products wp', 'products.id=wp.product_id', 'left')
				 ->join('categories', 'products.category_id=categories.id', 'left')
				 ->where('products.id', $id)
				 ->group_by("products.id");
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;		
	}
	
	public function getRoomByID($id){
		$this->db
			->select("id,floor,name,ppl_number,description, CASE WHEN status = 0 THEN 'Active' ELSE 'Close' END AS status")
            ->from("erp_suspended")
			->where("id", $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getSalemanByID($id){
		$this->db
				->select('username, phone, sum(erp_sales.total) as sale_amount, sum(erp_sales.paid) as sale_paid, (sum(erp_sales.total) - sum(erp_sales.paid)) as balance')
				->from('users')
				->join('sales', 'sales.saleman_by = users.id')
				->where('users.id', $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	public function purchase_pro($id){
		$this->db->select("erp_purchase_items.id as idd, erp_purchase_items.date as date,
		reference_no,
		product_name,
		erp_purchase_items.quantity,
		erp_product_variants.name as unit,
		(
			(net_unit_cost + net_shipping) * erp_purchase_items.quantity
		) as amount
		
		", false)
		->from("erp_purchase_items")
		->join('erp_purchases', 'erp_purchase_items.purchase_id = erp_purchases.id', 'left')
		->join('erp_products', 'erp_products.id = erp_purchase_items.product_id', 'left')
		->join('erp_units','erp_units.id = erp_products.unit','inner')
		->join('erp_categories','erp_categories.id = erp_products.category_id','inner')
		->join('erp_product_variants','erp_purchase_items.option_id = erp_product_variants.id','inner')
		->where('erp_purchase_items.id',$id);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			return $q->row();
		} 
		return false;
	}
	
	public function getPurchasesByID($id){
		$this->db
				->select($this->db->dbprefix('purchases') . ".date, reference_no, " . $this->db->dbprefix('warehouses') . ".name as wname, supplier, GROUP_CONCAT(" . $this->db->dbprefix('purchase_items') . ".product_name SEPARATOR '___') as iname, GROUP_CONCAT(ROUND(" . $this->db->dbprefix('purchase_items') . ".quantity) SEPARATOR '___') as iqty, grand_total, paid, (grand_total-paid) as balance, " . $this->db->dbprefix('purchases') . ".status,CONCAT(erp_users.first_name,' ',erp_users.last_name) as create_by", FALSE)
				->from('purchases')
				->join('purchase_items', 'purchase_items.purchase_id=purchases.id', 'left')
				->join('warehouses', 'warehouses.id=purchases.warehouse_id', 'left')
				->join('users', 'users.id=purchases.created_by', 'left')
				->where('purchases.id', $id)
                ->group_by('purchases.id')
                ->order_by('purchases.date desc');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getPaymentsByID($id){
		$this->db
				->select($this->db->dbprefix('payments') . ".id as idd, ". $this->db->dbprefix('sales') . ".suspend_note as noted, ". $this->db->dbprefix('payments'). ".date, " . $this->db->dbprefix('payments') . ".reference_no as payment_ref, " . $this->db->dbprefix('sales') . ".reference_no as sale_ref, " . $this->db->dbprefix('purchases') . ".reference_no as purchase_ref, " . $this->db->dbprefix('payments') . ".note,payments.paid_by,amount, payments.type,CONCAT(erp_users.first_name,' ',erp_users.last_name) as create_by")
                ->from('payments')
                ->join('sales', 'payments.sale_id=sales.id', 'left')
				->join('users', 'users.id=payments.created_by', 'left')
                ->join('purchases', 'payments.purchase_id=purchases.id', 'left')
				->where('payments.id', $id)
                ->group_by('payments.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getSaleDiscountByID($id)
	{
		$this->db->select('sales.date, sale_items.product_code, sale_items.product_name, sales.customer, products.cost, sale_items.unit_price ,sale_items.quantity, sale_items.discount')
				 ->from('sale_items')
				 ->join('sales', 'sales.id = sale_items.sale_id', 'left')
				 ->join('products', 'products.id = sale_items.product_id', 'left')
				 ->where('sale_items.discount <> 0 and sales.id = '.$id.' ');
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}
	
	public function getProjectsByID($id){
		$this->db
				->select($this->db->dbprefix('companies') . ".id as idd, company, name, phone, email, count(" . $this->db->dbprefix('sales') . ".id) as total, COALESCE(sum(" . $this->db->dbprefix('sales') . ".grand_total), 0) as total_amount, (COALESCE(sum(" . $this->db->dbprefix('sales') . ".grand_total), 0) * (" . $this->db->dbprefix('companies') . ".cf6/100)) as total_earned, COALESCE(sum(paid), 0) as paid, ( COALESCE(sum(grand_total), 0) - COALESCE(sum(paid), 0)) as balance", FALSE)
                ->from("companies")
                ->join('sales', 'sales.biller_id=companies.id')
                ->where('companies.group_name', 'biller')
                ->group_by('companies.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getSupplierByID($id){
		if (!$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $user = $this->session->userdata('user_id');
        }

        $sp = "(
				SELECT
					erp_purchases.id,
					erp_purchases.supplier_id,
					SUM(
						COALESCE (erp_payments.discount, 0)
					) AS discount,
					SUM(

						IF (
							erp_payments.paid_by = 'deposit',
							COALESCE (erp_payments.amount, 0),
							0
						)
					) AS deposit,
					SUM(

						IF (
							(
								erp_payments.paid_by != 'deposit'
								AND ISNULL(erp_payments.return_id)
							),
							erp_payments.amount,

						IF (
							NOT ISNULL(erp_payments.return_id),
							((- 1) * erp_payments.amount),
							0
						)
						)
					) AS payment
				FROM
					erp_payments
				LEFT JOIN erp_purchases ON erp_purchases.id = erp_payments.purchase_id
				WHERE
					erp_purchases.payment_status <> 'paid'
				AND erp_purchases.status <> 'ordered'
				GROUP BY erp_purchases.supplier_id
				) AS erp_pmt";

        $return = "(
				SELECT
					erp_purchases.id,
					erp_purchases.supplier_id,
					SUM(
						erp_return_purchases.grand_total
					) AS return_amount
				FROM
					erp_return_purchases
				LEFT JOIN erp_purchases ON erp_purchases.id = erp_return_purchases.purchase_id
				WHERE
					erp_purchases.payment_status <> 'paid'
				AND (
						erp_purchases.return_id IS NULL
						OR erp_purchases.grand_total <> erp_return_purchases.grand_total
					)
				GROUP BY
					erp_return_purchases.supplier_id
				) AS erp_total_return_purchase";

		$this->db
            ->select($this->db->dbprefix('companies') . ".id as idd,
				        companies.company,
				        name,
				        companies.phone,
				        companies.email,
				        count(" . $this->db->dbprefix('purchases') . ".id) as total,
				        COALESCE(sum(grand_total), 0) as total_amount,
				        total_return_purchase.return_amount as return_sale,
				        COALESCE(sum(paid), 0) as paid,
				        COALESCE(erp_pmt.deposit, 0) AS total_deposit,
					    COALESCE(erp_pmt.discount, 0) AS total_discount,
				        ( COALESCE(sum(grand_total), 0) - COALESCE(sum(paid), 0)) as balance,
				        CONCAT(erp_users.first_name,' ',erp_users.last_name) as create_by", FALSE)
                ->from("companies")
                ->join('purchases', 'purchases.supplier_id=companies.id')
				->join('users', 'users.id=purchases.created_by','LEFT')
            ->join($sp, 'pmt.supplier_id = purchases.supplier_id', 'left')
            ->join($return, 'total_return_purchase.supplier_id = purchases.supplier_id', 'left')
                ->where('companies.group_name', 'supplier')
				->where(array('purchases.status' => 'received', 'purchases.payment_status <>' => 'paid'))
				->where('companies.id',$id);
            
				if(!$this->Owner && !$this->Admin && $this->session->userdata('view_right') == 0){
					if ($user) {
						$this->db->where('purchases.created_by', $user);
					}
				}
                $this->db->group_by('companies.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
   public function getCustomersByID($id=null, $wh=null) {
        // $this->db
        //         ->select('id, code, name, quantity, unit, cost')
        //         ->from('erp_products')
        //         ->where('products.id', $id);
   		// $this->erp->print_arrays($wh);
   		$this->db->select("erp_companies.id as idd, company, name, phone, email, count(" . $this->db->dbprefix('sales') . ".id) as total, COALESCE(sum(grand_total), 0) as total_amount, COALESCE(sum(paid), 0) as paid, ( COALESCE(sum(grand_total), 0) - COALESCE(sum(paid), 0)) as balance", FALSE)
                ->from("companies")
                ->join('sales', 'sales.customer_id = companies.id', 'left')
                ->where(array('companies.group_name' => 'customer', 'sales.payment_status !=' => 'paid'))
                ->where(array('sales.sale_status !=' => 'ordered'))
                ->where(array('sales.sale_status !=' => 'returned'))
				->group_by('companies.id');
				// if($wh){
				// 	$this->db->where_in('erp_sales.warehouse_id',$wh);
				// }
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	public function getCustomerByID($id=null,$wh=null) {
		
        // $this->db
        //         ->select('id, code, name, quantity, unit, cost')
        //         ->from('erp_products')
        //         ->where('products.id', $id);
   		// $this->erp->print_arrays($wh);
		$sp = "(
				SELECT
					SUM(COALESCE(erp_payments.discount, 0)) AS discount,
					SUM(IF(erp_payments.paid_by = 'deposit', COALESCE(erp_payments.amount, 0), 0)) AS deposit,
					SUM(IF(erp_payments.paid_by <> 'deposit', COALESCE(erp_payments.amount, 0), 0)) AS payment,
					erp_sales.customer_id AS cust_id
				FROM
					erp_payments
				INNER JOIN erp_sales ON erp_sales.id = erp_payments.sale_id
				WHERE
					erp_sales.payment_status <> 'paid' AND erp_sales.sale_status <> 'ordered'
				GROUP BY
					erp_sales.customer_id
				) AS erp_pmt";

		$return = "(
				SELECT
					erp_return_sales.sale_id as sale_id,
					SUM(COALESCE(erp_return_sales.grand_total, 0)) AS return_sale
				FROM
					erp_return_sales
				LEFT JOIN erp_sales ON erp_sales.id = erp_return_sales.sale_id
				GROUP BY
					erp_return_sales.sale_id
				) AS erp_total_return_sale";
		$this->load->library('datatables');
   		$this->db->select("erp_companies.id as idd, company, name, phone, email, count(" . $this->db->dbprefix('sales') . ".id) as total, COALESCE(sum(grand_total), 0) as total_amount,
					SUM(COALESCE(erp_total_return_sale.return_sale, 0)) AS return_sale,
					COALESCE(erp_pmt.payment, 0) AS total_payment,
					COALESCE(erp_pmt.deposit, 0) AS total_deposit,
					COALESCE(erp_pmt.discount, 0) AS total_discount,
					(COALESCE(SUM(erp_sales.grand_total), 0) - SUM(COALESCE(erp_total_return_sale.return_sale, 0)) - COALESCE(erp_pmt.payment, 0) - COALESCE(erp_pmt.deposit, 0) - COALESCE(erp_pmt.discount, 0)) AS balance
					", FALSE)
                ->from("sales")
                ->join('companies', 'companies.id = sales.customer_id', 'left')
				->join($sp, 'pmt.cust_id = sales.customer_id', 'left')
				->join($return, 'total_return_sale.sale_id = sales.id', 'left')
                ->where(array('companies.group_name' => 'customer', 'sales.payment_status !=' => 'paid'))
                ->where(array('sales.sale_status !=' => 'ordered'))
				->where('erp_companies.id', $id)
				->group_by('companies.id');
				// if($wh){
				// 	$this->db->where_in('erp_sales.warehouse_id',$wh);
				// }
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	public function getProfitByID($id){
		$p_cost = "COALESCE (
						(
							SELECT
								CASE
							WHEN type <> 'combo' THEN
								(
									SELECT
										SUM(
											cost * erp_sale_items.quantity
										)
									FROM
										erp_sale_items
									INNER JOIN erp_products ON erp_products.id = erp_sale_items.product_id
									WHERE
										erp_sale_items.sale_id = erp_sales.id
								)
							ELSE
								(
									SELECT
										SUM(
											erp_products.cost * erp_sale_items.quantity
										) AS cost
									FROM
										erp_combo_items
									INNER JOIN erp_products ON erp_products.`code` = erp_combo_items.item_code
									WHERE
										erp_combo_items.product_id = erp_sale_items.product_id
								)
							END
							FROM
								erp_products
							WHERE
								erp_products.id = erp_sale_items.product_id
						),
						0
					)";
			$gl_trant="COALESCE((SELECT sum(erp_gl_trans.amount) from erp_gl_trans where sectionid=50 AND erp_gl_trans.sale_id=erp_sales.id group by erp_gl_trans.sale_id),0)";
		$this->db
				->select("erp_sales.id, date, erp_sales.reference_no,suspend_note ,biller, customer, grand_total, paid, (grand_total-paid) as balance,
                    ".$p_cost." AS total_cost,(SELECT SUM(erp_gl_trans.amount) from erp_gl_trans where sectionid=50 AND erp_gl_trans.sale_id =erp_sales.id group by erp_gl_trans.sale_id)as amount,
                    COALESCE (
                        COALESCE (
                            (
                                grand_total
                            ),
                            0
                        ) - ".$p_cost." - ".$gl_trant."
                    ) AS profit, payment_status", FALSE)
					->from('sales')
					->join('sale_items', 'sale_items.sale_id=sales.id', 'inner')
					->join('warehouses', 'warehouses.id=sales.warehouse_id', 'left')
					->join('companies', 'companies.id=sales.customer_id','left')
					->join('erp_gl_trans','erp_gl_trans.sale_id=erp_sales.id','left')             
					->join('customer_groups','customer_groups.id=companies.customer_group_id','left')
				->where('erp_sales.id', $id)
				->group_by('sales.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getSalesByID($id){
		$this->db
				->select("erp_sales.id, date, reference_no, biller, customer,
									GROUP_CONCAT(" . $this->db->dbprefix('sale_items') . ".product_name SEPARATOR '\n') as iname, 
									GROUP_CONCAT(ROUND(".$this->db->dbprefix('sale_items') . ".quantity) SEPARATOR '\n') as iqty, 
									grand_total, 
									paid, 
									(grand_total-paid) as balance, 
									payment_status", FALSE)
				->from('sales')
				->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
				->join('warehouses', 'warehouses.id=sales.warehouse_id', 'left')
				->join('companies', 'companies.id=sales.customer_id','left')                
				->join('customer_groups','customer_groups.id=companies.customer_group_id','left')
				->where('erp_sales.id', $id)
				->group_by('sales.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getSalesExportByID($id){
		$this->db
                ->select("erp_sales.id, date, reference_no, biller, customer,
                            GROUP_CONCAT(CONCAT(" . $this->db->dbprefix('sale_items') . ".product_name, '(', " . $this->db->dbprefix('sale_items') . ".product_code , ')') SEPARATOR '\n') as iname, 
                            GROUP_CONCAT(CONCAT((ROUND(".$this->db->dbprefix('sale_items') . ".quantity)), '(', " . $this->db->dbprefix('sale_items') . ".unit_price , ')') SEPARATOR '\n') as iqty, 
                            GROUP_CONCAT(" . $this->db->dbprefix('products') . ".cost SEPARATOR '\n') as icost, 
                            grand_total, paid, 
                            (grand_total-paid) as balance, 
                            payment_status, 
                            SUM(".$this->db->dbprefix('sale_items') . ".quantity) as total_qty", FALSE)
                ->from('sales')
                ->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
                ->join('products', 'products.id = sale_items.product_id', 'left')
                ->join('warehouses', 'warehouses.id=sales.warehouse_id', 'left')
                ->join('companies', 'companies.id=sales.customer_id','left')                
                ->join('customer_groups','customer_groups.id=companies.customer_group_id','left')
                ->where('erp_sales.id', $id)
                ->group_by('sales.id');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	
	public function getCategoryByID($id){
		$pp = "( SELECT pp.category_id as category, pi.product_id, SUM( pi.quantity ) purchasedQty, SUM( pi.net_unit_cost * pi.quantity ) totalPurchase from " . $this->db->dbprefix('products') . " pp
                left JOIN " . $this->db->dbprefix('purchase_items') . " pi on pp.id = pi.product_id 
                group by pp.category_id
                ) PCosts";
            $sp = "( SELECT sp.category_id as category, si.product_id, SUM( si.quantity ) soldQty, SUM( si.subtotal ) totalSale from " . $this->db->dbprefix('products') . " sp
                left JOIN " . $this->db->dbprefix('sale_items') . " si on sp.id = si.product_id 
                group by sp.category_id 
                ) PSales";
				
		$this->db
                ->select($this->db->dbprefix('categories') . ".id as cidd, " .$this->db->dbprefix('categories') . ".code, " . $this->db->dbprefix('categories') . ".name,
                    SUM( COALESCE( PCosts.purchasedQty, 0 ) ) as PurchasedQty,
                    SUM( COALESCE( PSales.soldQty, 0 ) ) as SoldQty,
                    SUM( COALESCE( PCosts.totalPurchase, 0 ) ) as TotalPurchase,
                    SUM( COALESCE( PSales.totalSale, 0 ) ) as TotalSales,
                    (SUM( COALESCE( PSales.totalSale, 0 ) )- SUM( COALESCE( PCosts.totalPurchase, 0 ) ) ) as Profit", FALSE)
                ->from('categories')
                ->join($sp, 'categories.id = PSales.category', 'left')
                ->join($pp, 'categories.id = PCosts.category', 'left')
				->where('categories.id', $id)
				->group_by('categories.id');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
	}
	public function getCategoryValueByID($id) {
		$wid = $this->reports_model->getWareByUserID();
        $this->db
                ->select('
                        categories.id AS cid, 
                        categories.code, 
                        categories.name, 
                        COALESCE(SUM(erp_warehouses_products.quantity), 0) AS current_stock, 
                        COALESCE(SUM(erp_products.cost * erp_warehouses_products.quantity), 0) AS total_cost, 
                        COALESCE(SUM(erp_products.price * erp_warehouses_products.quantity), 0) AS total_price,				
                        COALESCE(SUM(erp_products.price * erp_warehouses_products.quantity) - SUM(erp_products.cost * erp_warehouses_products.quantity), 0) as balance')
                    ->from('categories')
                    ->join('products', 'products.category_id = categories.id', 'left')
					->join('erp_warehouses_products', 'erp_warehouses_products.product_id = products.id', 'left')
                    ->where('categories.id', $id);
					if($wid){
						$this->db->where("erp_warehouses_products.warehouse_id IN ($wid)");
					}
                     $this->db->group_by('categories.id');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
	public function getWarehouseByID($id){
		$this->db->select('id, code, name, quantity');
		$this->db->from('products');
		$this->db->where('id', $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;		
	}
	
	public function getProductByID($id){
		$pp = "( SELECT 
					pi.date as date, 
					pi.product_id, 
					pi.purchase_id, 
					COALESCE(SUM(CASE WHEN pi.purchase_id <> 0 THEN (pi.quantity*(CASE WHEN ppv.qty_unit <> 0 THEN ppv.qty_unit ELSE 1 END)) ELSE 0 END),0) as purchasedQty, 
					SUM(pi.quantity_balance) as balacneQty, 
					SUM((CASE WHEN pi.option_id <> 0 THEN ppv.cost ELSE pi.net_unit_cost END) * pi.quantity_balance ) balacneValue, 
					SUM( pi.unit_cost * (CASE WHEN pi.purchase_id <> 0 THEN pi.quantity ELSE 0 END) ) totalPurchase
					FROM {$this->db->dbprefix('purchase_items')} pi 
					LEFT JOIN {$this->db->dbprefix('purchases')} p 
					ON p.id = pi.purchase_id
					LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
					ON ppv.id=pi.option_id ".$where_purchase." 
					WHERE pi.status <> 'ordered'
					GROUP BY pi.product_id ) PCosts";
		$sp = "( SELECT 
					si.product_id, 
					SUM( si.quantity*(CASE WHEN pv.qty_unit <> 0 THEN pv.qty_unit ELSE 1 END)) soldQty, 
					SUM( si.subtotal ) totalSale, 
					s.date as sdate FROM " . $this->db->dbprefix('sales') . " s 
					INNER JOIN " . $this->db->dbprefix('sale_items') . " si 
					ON s.id = si.sale_id 
					LEFT JOIN " . $this->db->dbprefix('product_variants') . " pv 
					ON pv.id=si.option_id ".$where_sale." 
					GROUP BY si.product_id ) PSales";
		$this->db
                ->select($this->db->dbprefix('products') . ".id AS idd, " .$this->db->dbprefix('categories') .".name as catName, ". $this->db->dbprefix('products') . ".code, " . $this->db->dbprefix('products') . ".name,
				COALESCE( PCosts.purchasedQty, 0 ) AS qpurchase, COALESCE( PCosts.totalPurchase, 0 ) AS ppurchased,
				COALESCE (PSales.soldQty, 0) + COALESCE (
                        (
                            SELECT
                                SUM(si.quantity * ci.quantity)
                            FROM
                                erp_combo_items ci
                            INNER JOIN erp_sale_items si ON si.product_id = ci.product_id
                            WHERE
                                ci.item_code = ".$this->db->dbprefix('products') . ".code
                        ),
                        0
                ) AS qsale,
                COALESCE (PSales.totalSale, 0) AS psold,
                (COALESCE( PSales.totalSale, 0 ) - COALESCE( PCosts.totalPurchase, 0 )) as Profit,
				COALESCE( PCosts.balacneQty, 0 ) as qbalance, COALESCE( PCosts.balacneValue, 0 ) as pbalance", FALSE)
                ->from('products')
                ->join($sp, 'products.id = PSales.product_id', 'left')
                ->join($pp, 'products.id = PCosts.product_id', 'left')				
				->join('warehouses_products wp', 'products.id=wp.product_id', 'left')
				->join('categories', 'products.category_id=categories.id', 'left')
				->where('products.id', $id)
				->group_by("products.id");
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;	
	}

    function getQuantityByID($id,$wareid){
		if($wareid){
			 $this->db
                    ->select('warehouses_products.id, image, products.code, products.name, warehouses.name as wname, warehouses_products.quantity, alert_quantity')
                    ->from('products')
                    ->join("warehouses_products", 'products.id=warehouses_products.product_id', 'left')
                    ->join('warehouses', 'warehouses_products.warehouse_id = warehouses.id', 'left')
                    ->where('alert_quantity > erp_warehouses_products.quantity')
                    ->where('track_quantity', 1)
					->where(array("warehouses_products.warehouse_id"=>$wareid, 'warehouses_products.id' => $id))
                   ->group_by('products.id');
		}else{
			$this->db
             ->select('warehouses_products.id, image, products.code, products.name, warehouses.name as wname, warehouses_products.quantity, alert_quantity')
                ->from('products')
                ->join("warehouses_products", 'products.id=warehouses_products.product_id', 'left')
                ->join('warehouses', 'warehouses_products.warehouse_id = warehouses.id', 'left')
            ->where('alert_quantity > warehouses_products.quantity')
            ->where(array('track_quantity'=> 1, 'warehouses_products.id' => $id));
		}
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;    
    }

    function getWarehouseNameByWID($wid){
        $q = $this->db->get_where('warehouses', array('id' => $wid), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    function getRegisterByID($id){
        $this->db
             ->select("date, closed_at, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name, '<br>', " . $this->db->dbprefix('users') . ".email) as user, cash_in_hand, CONCAT(total_cc_slips, ' (', total_cc_slips_submitted, ')') as c_slips, CONCAT(total_cheques, ' (', total_cheques_submitted, ')') as cheques, CONCAT(total_cash, ' (', total_cash_submitted, ')') as cash, pos_register.note", FALSE)
             ->from("pos_register")
             ->where("pos_register.id", $id)
             ->join('users', 'users.id=pos_register.user_id', 'left');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false; 
    }
	
	function getDataReportDetail($id)
	{
	
		if($id)
		{
			
			$q = $this->db->query("
								SELECT
									`erp_sales`.`id`,
									erp_sale_items.product_id,
									`erp_categories`.`id` AS `categoryId`,
									`erp_categories`.`name` AS `categoryName`,
									CONCAT(
										erp_sale_items.product_name,
										' (',
										`erp_sale_items`.`product_code`,
										')'
									) AS productName,
									erp_sale_items.product_id as product_id,
									`erp_products`.`quantity` AS `stockInHand`,
									(SELECT SUM(quantity) from erp_return_items WHERE return_id=erp_return_sales.id) as total_return,
									SUM(erp_sale_items.quantity) AS saleQuantity,
									
									`erp_products`.`cost` AS `unitCost`,
									`erp_sale_items`.`unit_price` AS `unitPrice`,
									sum(
										(
											erp_sale_items.unit_price * erp_sale_items.quantity
										) - (
											CASE
											WHEN discount LIKE '%\%' THEN
												(
													erp_sale_items.unit_price * erp_sale_items.quantity
												) * SUBSTRING_INDEX(discount, '%', 1) / 100
											ELSE
												erp_sale_items.discount
											END
										)
									) AS revenue,
									(
										IFNULL(
											SUM(erp_sale_items.quantity) * erp_products.cost,
											0
										)
									) AS coms,
									sum(
										(
											(
												erp_sale_items.unit_price * erp_sale_items.quantity
											) - (
												CASE
												WHEN discount LIKE '%\%' THEN
													(
														erp_sale_items.unit_price * erp_sale_items.quantity
													) * SUBSTRING_INDEX(discount, '%', 1) / 100
												ELSE
													erp_sale_items.discount
												END
											)
										) - (
											IFNULL(
												erp_sale_items.quantity * erp_products.cost,
												0
											)
										)
									) AS profit,
									sum(
										CASE
										WHEN discount LIKE '%\%' THEN
											(
												erp_sale_items.unit_price * erp_sale_items.quantity
											) * SUBSTRING_INDEX(discount, '%', 1) / 100
										ELSE
											erp_sale_items.discount
										END
									) as total_discount
								FROM
									`erp_sales`
								JOIN `erp_sale_items` ON `erp_sales`.`id` = `erp_sale_items`.`sale_id`
								JOIN `erp_products` ON `erp_sale_items`.`product_id` = `erp_products`.`id`
								JOIN `erp_categories` ON `erp_products`.`category_id` = `erp_categories`.`id`
								LEFT JOIN erp_return_sales ON erp_return_sales.sale_id = erp_sales.id
								WHERE
									`erp_categories`.`id` = '".$id."'
								GROUP BY
									`erp_categories`.`id`,
									`erp_products`.`id`,
									erp_sale_items.unit_price,
									erp_sale_items.discount
			
							")->result();
			$row = '';
			$stockInHand = 0;
			$saleQuantity = 0;
			$unitCost = 0;
			$unitPrice = 0;
			$revenue = 0;
			$coms = 0;
			$profit = 0;
			$grand_discount = 0;
			$total_return = 0;
			foreach($q as $data_row){
				$stockInHand+=$data_row->stockInHand;
				$saleQuantity+=$data_row->saleQuantity;
				$unitCost+=$data_row->unitCost;
				$unitPrice+=$data_row->unitPrice;
				$revenue+=$data_row->revenue;
				$grand_discount+=$data_row->total_discount;
				$coms+=$data_row->coms;
				$profit+=$data_row->profit;
				$total_return += $data_row->total_return;
				$warehouses = $this->getAllWarehouses();				
				$row.='<tr>';					
					$row.='<td><input class="checkbox multi-select" name="val[]" value="'.$data_row->id.'" type="checkbox"/></td>';
					$row.='<td>'.$data_row->productName.'</td>';					
					$row.='<input type="hidden" value="'.$data_row->product_id.'">';
					foreach($warehouses as $warehouse){
						$warehouseQty = $this->getWHQty($data_row->product_id, $warehouse->id);
						$row.='<td>'.(($warehouseQty > 0)? $this->erp->formatQuantity($warehouseQty->wqty):$this->erp->formatQuantity(1)).'</td>';
					}
					$row.='<td>'.$this->erp->formatQuantity($data_row->saleQuantity) .'</td>';
					$row.='<td>'.$this->erp->formatMoney($data_row->unitCost).'</td>';
					$row.='<td>'.$this->erp->formatMoney($data_row->unitPrice) .'</td>';
					$row.='<td>'.$this->erp->formatMoney($data_row->total_discount) .'</td>';
					$row.='<td>'.$this->erp->formatMoney($data_row->total_return).'</td>';
					$row.='<td>'.$this->erp->formatMoney($data_row->revenue) .'</td>';										
				$row.='</tr>';			
			}
			
			$row.='<tr style="font-weight:bold;">';
					$row.='<td><input class="checkbox multi-select" name="val[]" value="'.$data_row->id.'" type="checkbox"/></td>';
					$row.='<td style="text-align:right;padding-right:10px;">'."Grand Total".'</td>';
					$row.='<input type="hidden" value="'.$data_row->product_id.'">';
					foreach($warehouses as $warehouse){
						$warehouseQty = $this->getWHQty($data_row->product_id, $warehouse->id);
						$row.='<td>'.(($warehouseQty > 0)? $this->erp->formatQuantity($warehouseQty):$this->erp->formatQuantity(1)).'</td>';
					}
					$row.='<td>'.$this->erp->formatQuantity($saleQuantity) .'</td>';
					$row.='<td>'.$this->erp->formatMoney($unitCost).'</td>';
					$row.='<td>'.$this->erp->formatMoney($unitPrice) .'</td>';
					$row.='<td>'.$this->erp->formatMoney($grand_discount) .'</td>';
					$row.='<td>'.$this->erp->formatMoney($total_return) .'</td>';
					$row.='<td>'.$this->erp->formatMoney($revenue) .'</td>';											
				$row.='</tr>';
			return $row;
		}else{
			return "Data Not Found";
		}
	}
	
	
	function getWarehouseQty($product_id){
		$this->db->select('erp_warehouses_products.quantity as wqty');
		$this->db->where('erp_warehouses_products.product_id',$product_id);
		$this->db->from('erp_warehouses_products');
		$result = $this->db->get();
		if($result->num_rows()>0){
			foreach($result->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
		
	}
	
	function getWHQty($product_id, $wh_id){
		$this->db->select('erp_warehouses_products.quantity as wqty');
		$this->db->where(array('erp_warehouses_products.product_id' => $product_id, 'erp_warehouses_products.warehouse_id' => $wh_id));
		$this->db->from('erp_warehouses_products');
		$result = $this->db->get();
		if($result->num_rows()>0){
			return $result->row();
		}
		return false;
		
	}
	
	function getCategoryName($category_id = NULL, $product_id = NULL, $start = NULL, $end = NULL, $biller_id = NULL){
		$this->db->select("categories.id,categories.name,sales.date");
		$this->db->from("sale_items");
		$this->db->join('products','products.id = sale_items.product_id');
		$this->db->join('categories','categories.id = products.category_id');
		$this->db->join('sales','sales.id = sale_items.sale_id');
		if($category_id) {
			$this->db->where('categories.id', $category_id);
		}
		if($product_id){
			$this->db->where('products.id', $product_id);
		}
		if($start && $end) {
			$this->db->where('sales.date >= ' . $start . ' AND sales.date <= ' . $end . ' ');
		}
		if($biller_id) {
			$this->db->where('sales.biller_id', $biller_id);
		}
		$this->db->group_by('categories.id');
		$q = $this->db->get();
		if($q->num_rows() > 0 ) {
			return $q->result();
		}
		return false;
	}
	
	function getCategory(){
		$q = $this->db->get('categories');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	function getProductName(){
		$q = $this->db->get('products');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	function getExportSaleDetail($id){
		$this->db->select("
		sales.id,
		categories.id as categoryId,
		categories.name as categoryName,
		CONCAT(erp_sale_items.product_name,' (',erp_sale_items.product_code,')') as productName,
		products.quantity as stockInHand,
		SUM(erp_sale_items.quantity) AS saleQuantity,
		products.cost as unitCost,
		products.price as unitPrice,
		sum(
			(
				erp_sale_items.unit_price * erp_sale_items.quantity
			) - (
				CASE
				WHEN discount LIKE '%\%' THEN
					(
						erp_sale_items.unit_price * erp_sale_items.quantity
					) * SUBSTRING_INDEX(discount, '%', 1) / 100
				ELSE
					erp_sale_items.discount
				END
			)
		) AS revenue,		
		(
			IFNULL(
				SUM(erp_sale_items.quantity) * erp_products.cost,
				0
			)
		) AS coms,
		sum(
			(
				(
					erp_sale_items.unit_price * erp_sale_items.quantity
				) - (
					CASE
					WHEN discount LIKE '%\%' THEN
						(
							erp_sale_items.unit_price * erp_sale_items.quantity
						) * SUBSTRING_INDEX(discount, '%', 1) / 100
					ELSE
						erp_sale_items.discount
					END
				)
			) - (
				IFNULL(
					erp_sale_items.quantity * erp_products.cost,
					0
				)
			)
		) AS profit");
        $this->db->from('sales');
        $this->db->join('sale_items', 'sales.id = sale_items.sale_id');
        $this->db->join('products', 'sale_items.product_id = products.id');        
        $this->db->join('categories', 'products.category_id = categories.id');
		$this->db->where('categories.id', $id);
        $this->db->group_by('categories.id');		
        $this->db->group_by('products.id');		
		$q = $this->db->get();
		if($q->num_rows() > 0 ) {
			return $q->result();
		}
		return false;
	}
	public function getAllCompanies($group_name) {
        $q = $this->db->get_where('companies', array('group_name' => $group_name));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	function getDataSaleDetailByInvoice($sale_id){
		if($start){
			$start_date = $start;
			$end_date = $end;
		}else{
			$start_date = date('Y-m-d').'00:00:00';
			$end_date = date('Y-m-d h:i:s');
		}
	if($sale_id){
		$this->db->select("sales.id, sales.date, sales.reference_no, sales.biller_id, CONCAT(erp_sale_items.product_code ,' - ', erp_sale_items.product_name) as product_name, erp_sale_items.quantity as qty, sale_items.unit_price as unit_price,sale_items.discount as dis, sale_items.subtotal as amount");
        $this->db->from('sales');
        $this->db->join('sale_items', 'sales.id = sale_items.sale_id');
        $this->db->join('products', 'sale_items.product_id = products.id');        
        $this->db->join('companies', 'companies.id = sales.biller_id');
       // $this->db->join('product_variants', 'sale_items.option_id = product_variants.id');
		$this->db->where('sales.id', $sale_id);		
		//$this->db->where('sales.date >= "'.$start_date.'" and sales.date <= "'.$end_date.'"');
        		
		$q = $this->db->get();		
        foreach($q->result() as $data_row){			
			$row.='<tr>';			
				$row.='<td colspan="2">'." ".'</td>';
				$row.='<td style="padding-left:20px;">'.$data_row->product_name .'</td>';
				$row.='<td>'.$this->erp->formatQuantity($data_row->qty).'</td>';
				$row.='<td>'.$this->erp->formatMoney($data_row->unit_price) .'</td>';
				$row.='<td>'.$data_row->dis .'</td>';
				$row.='<td>'.$this->erp->formatMoney($data_row->amount) .'</td>';															
			$row.='</tr>';			
		}		
		return $row;
	  }
	else		
		return "Data Not Found";
	}
	function searchByBiller($biller = NULL){
		$this->db->select("companies.id, companies.group_name, sales.reference_no, sales.date");
		$this->db->from("sales");	
		$this->db->join('companies','companies.id = sales.biller_id');
		if($biller) {
			$this->db->where(array('group_name' => 'biller','sales.biller_id' => $biller));
		}		
		$q = $this->db->get();
		if($q->num_rows() > 0 ) {
			return $q->result();
		}
		return false;
	}
	function getSearchInvoice($biller){
		$post = $this->input->post();
		$sql = "SELECT
					`erp_sales`.*, `product_name`,
					`product_code`,
					sum(erp_sale_items.quantity) AS quantity,
					sum(erp_sale_items.unit_price) AS unit_price,
					sum(item_discount) AS item_discount
				FROM
					`erp_sales`
				LEFT JOIN `erp_sale_items` ON `erp_sale_items`.`sale_id` = `erp_sales`.`id`
				WHERE
					`erp_sales`.`biller_id` = {$biller}
				GROUP BY
					`reference_no`,
					`biller_id`";
		$result = $this->db->query($sql);	
		$html = "";
		$total_quantity = 0; $total_price = 0; $total_discount_item = 0; $total_amount = 0;
        foreach($result->result() as $row){
        	$total_quantity += $row->quantity;
        	$total_price += $row->unit_price;
        	$total_discount_item += ($row->item_discount * $row->unit_price / 100);
        	$total_amount += $row->total;
			$html.='<tr>';
				$html.='<td><input class="checkbox checkth" type="checkbox" name="check"/></td>';				
				$html.='<td>'.$row->date.'</td>';
				$html.='<td>'.$row->reference_no.'</td>';
				$html.='<td>'.$row->total_items .'</td>';	
				$html.='<td class="right">'.$row->quantity .'</td>';
				$html.='<td class="right">'.$row->unit_price .'</td>';
				$html.='<td class="right">'.($row->item_discount * $row->unit_price / 100) .'</td>';
				$html.='<td class="right">'.$this->erp->formatMoney($row->total).'</td>';															
			$html.='</tr>';			
		}
		$result = array(
			'html' => $html, 
			'total_quantity' => $total_quantity,
			'total_price' => $total_price,
			'total_discount_item' => $total_discount_item,
			'total_amount' => $total_amount,
		);	
		return $result;
	}
	function getInvoice($start = NULL, $end = NULL){
		$start = $this->erp->fld(date('d/m/Y'));
		$end = $this->erp->fld(date('d/m/Y h:i:s'));
		if($start){
			$start_date = $start;
			$end_date = $end;
		}else{
			$start_date = $this->erp->fld(date('d/m/Y'));
			$end_date = $this->erp->fld(date('d/m/Y h:i:s'));
		}
		$this->db->select("sales.id, sales.reference_no, sales.biller_id, sales.total")->from("sales");		
		$this->db->where('sales.date >= "'.$start_date.'" and sales.date <= "'.$end_date.'"');
		$q = $this->db->get();
		if($q->num_rows() > 0 ) {
			return $q->result();
		}
		return false;
	}
	
	public function getSalesOrder($customer_id)
    {
        $this->db->from('sale_order')->where('customer_id', $customer_id);
        return $this->db->count_all_results();
    }
	
	public function getWarehouse(){
		$q = $this->db->get('erp_warehouses');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function getCategoryByProduct(){
		$this->db->select('erp_categories.id as cid,erp_categories.code as ccode,erp_categories.name as cname,erp_subcategories.id as subid,erp_subcategories.code as scode,erp_subcategories.name as sname')->from('erp_categories')->join('erp_subcategories','erp_subcategories.category_id=erp_categories.id','left');
		$q = $this->db->get();
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function getProductByCategoryAndWarehouse(){
		$this->db->select('erp_products.*,erp_warehouses_products.warehouse_id as wid,erp_categories.id as cid,erp_categories.code as ccode,erp_categories.name as cname,erp_subcategories.id as subid,erp_subcategories.code as scode,erp_subcategories.name as sname')->from('erp_products')
		->join('erp_warehouses_products','erp_warehouses_products.product_id=erp_products.id','left')
		->join('erp_categories','erp_categories.id=erp_products.category_id','left')
		->join('erp_subcategories','erp_subcategories.id=erp_products.subcategory_id','left');
		
		$q = $this->db->get();
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function getPurchaseByProductID(){
		$this->db->select('erp_purchase_items.*,erp_purchases.id as pur_id,erp_purchases.supplier,erp_purchases.reference_no,erp_companies.company,erp_purchases.warehouse_id,erp_products.cost as pcost,erp_products.quantity as pqty,erp_products.unit')->from('erp_purchase_items')
		->join('erp_purchases','erp_purchases.id=erp_purchase_items.purchase_id','left')
		->join('erp_products','erp_products.id=erp_purchase_items.product_id','left')
		->join('erp_companies','erp_companies.id=erp_purchases.biller_id','left');
		//->group_by('pur_id');
		$q = $this->db->get();
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getWarehousesInventoryValuation($wid, $warehouse_id, $category_id, $product_id,$stockType, $from_date, $to_date, $reference_no, $biller){
        $this->db->select('warehouses.code, warehouses.name AS warehouse, stock_trans.warehouse_id, stock_trans.expired_date');
        $this->db->join('products', 'stock_trans.product_id = products.id', 'left');
        $this->db->join('warehouses', 'warehouses.id = stock_trans.warehouse_id', 'left');
        $this->db->join('purchases', 'stock_trans.tran_id = purchases.id', 'left');

		if($warehouse_id){
            $this->db->where("stock_trans.warehouse_id", $warehouse_id);
		}else{
			if($wid){
                $this->db->where("stock_trans.warehouse_id  IN ($wid)");
			}
		}
		
		if($category_id){
            $this->db->where('products.category_id', $category_id);
		}
		
		if($biller){
            $this->db->where('stock_trans.biller_id', $biller);
		}
		
		if($product_id){
            $this->db->where('stock_trans.product_id', $product_id);
		}
		
		if($stockType){
            $this->db->where('stock_trans.tran_type', $stockType);
		}
		
		if($reference_no){
            $this->db->where('purchases.reference_no', $reference_no);
		}
        if ($from_date) {
            $this->db->where('date_format(erp_stock_trans.tran_date,"%Y-%m-%d") >="' . $from_date . '" AND date_format(erp_stock_trans.tran_date,"%Y-%m-%d") <="' . $to_date . '"');;
        }
        $this->db->group_by('stock_trans.warehouse_id');
        $q = $this->db->get('stock_trans');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
		
	public function getWarehousesProductProfit($wid,$warehouse_id,$category_id,$product_id,$from_date,$to_date,$reference_no,$biller){
		$this->db->select('warehouses.id as warehouse_id,warehouses.code, warehouses.name AS warehouse');
		$this->db->join('warehouses', 'sale_items.warehouse_id = warehouses.id');
		$this->db->join('sales', 'sales.id = sale_items.sale_id');
		$this->db->join('products','products.id = sale_items.product_id');
		
		if($warehouse_id){
			$this->db->where("sale_items.warehouse_id",$warehouse_id);
		}else{
			if($wid){
				$this->db->where("sale_items.warehouse_id  IN ($wid)");
			}
		}
		
		if($category_id){
			$this->db->where('products.category_id', $category_id);
		}
		if($biller){
			$this->db->where('biller_id', $biller);
		}
		if($product_id){
			$this->db->where('product_id', $product_id);
		}
		if($stockType){
			$this->db->where('type', $stockType);
		}
		
		if($reference_no){
			$this->db->where('reference_no', $reference_no);
		}
		if($from_date && $to_date){
			$this->db->where('date_format(date,"%Y-%m-%d") >="'.$from_date.'" AND date_format(date,"%Y-%m-%d") <="'.$to_date.'"');
		}
		$this->db->group_by('warehouses.id');
		$q = $this->db->get('sale_items');
		
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getCategoriesInventoryValuationByWarehouse($warehouse_id,$category_id,$product_id,$stockType,$from_date,$to_date,$reference_no, $biller){
        $this->db->select('category_id, categories.name AS category_name, purchases.reference_no');
        $this->db->join('products', 'stock_trans.product_id = products.id', 'left');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('purchases', 'stock_trans.tran_id = purchases.id', 'left');
		if($warehouse_id){
            $this->db->where('stock_trans.warehouse_id', $warehouse_id);
		}
		if($category_id){
            $this->db->where('products.category_id', $category_id);
		}
		if($product_id){
            $this->db->where('stock_trans.product_id', $product_id);
		}
		if($biller){
            $this->db->where('stock_trans.biller_id', $biller);
		}
		if($stockType){
            $this->db->where('stock_trans.tran_type', $stockType);
		}
		
		if($reference_no){
            $this->db->where('purchases.reference_no', $reference_no);
		}
		if($from_date && $to_date){
            $this->db->where('date_format(erp_stock_trans.tran_date,"%Y-%m-%d") >="' . $from_date . '" AND date_format(erp_stock_trans.tran_date,"%Y-%m-%d") <="' . $to_date . '"');
		}

        $this->db->group_by('category_id');
        $q = $this->db->get('stock_trans');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getCategoriesProductProfitByWarehouse($warehouse_id,$category_id,$product_id,$from_date,$to_date,$reference_no,$biller){
		
		$this->db->select('categories.id, categories.name AS category_name');
		$this->db->join('erp_products','erp_products.id = erp_sale_items.product_id');
		$this->db->join('erp_categories', 'erp_categories.id = erp_products.category_id');
		$this->db->join('sales','sales.id = sale_items.sale_id');
		if($warehouse_id){
			$this->db->where('sale_items.warehouse_id', $warehouse_id);
		}
		if($category_id){
			$this->db->where('erp_products.category_id', $category_id);
		}
		if($product_id){
			$this->db->where('product_id', $product_id);
		}
		if($biller){
			$this->db->where('biller_id', $biller);
		}
		if($reference_no){
			$this->db->where('reference_no', $reference_no);
		}
		if($from_date && $to_date){
			$this->db->where('date_format(date,"%Y-%m-%d") >="'.$from_date.'" AND date_format(date,"%Y-%m-%d") <="'.$to_date.'"');
		}
		$this->db->group_by('erp_products.category_id');
		$q = $this->db->get('erp_sale_items');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}


    public function getProductsInventoryValuationByWhCat($warehouse_id, $category_id, $product_id, $stockType, $from_date, $to_date, $reference_no, $biller, $plan_id)
    {

        $this->db->select('product_id, product_code,products.name as product_name,units.name as un');
        $this->db->join("products", "products.id=inventory_valuation_details.product_id", "LEFT");
        $this->db->join("units", "units.id=products.unit", "LEFT");
		if($warehouse_id){
            $this->db->where('inventory_valuation_details.warehouse_id', $warehouse_id);
		}
		if($category_id){
            $this->db->where('inventory_valuation_details.category_id', $category_id);
		}
		if($product_id){
            $this->db->where('inventory_valuation_details.product_id', $product_id);
		}
		if($stockType){
            $this->db->where('inventory_valuation_details.type', $stockType);
		}
		if($biller){
            $this->db->where('biller_id', $biller);
        }
        if ($plan_id) {
            $this->db->where('plan_id', $plan_id);
		}
		if($reference_no){
            $this->db->where('inventory_valuation_details.reference_no', $reference_no);
		}
		if($from_date && $to_date){

            //$from_date = date('Y-m-d',strtotime($from_date)).' 00:00:00';
            //$to_date   = date('Y-m-d',strtotime($to_date)).' 00:00:00';
            $this->db->where('date_format(erp_inventory_valuation_details.date,"%Y-%m-%d") >="' . $from_date . '" AND date_format(erp_inventory_valuation_details.date,"%Y-%m-%d") <="' . $to_date . '"');
        }
        $this->db->group_by('inventory_valuation_details.product_id');
        $this->db->order_by('inventory_valuation_details.id', 'desc');
        $q = $this->db->get('inventory_valuation_details');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getProductsProfitByWhCat($warehouse_id,$category_id,$product_id,$from_date,$to_date,$reference_no,$biller){
		$this->db->select('product_id, product_code,products.name as product_name,units.name as un');
		$this->db->join("products","products.id=erp_sale_items.product_id");
		$this->db->join("units","units.id=products.unit");
		$this->db->join('sales','sales.id = sale_items.sale_id');
		if($warehouse_id){
			$this->db->where('erp_sale_items.warehouse_id', $warehouse_id);
		}
		if($category_id){
			$this->db->where('products.category_id', $category_id);
		}
		if($product_id){
			$this->db->where('erp_sale_items.product_id', $product_id);
		}
		
		if($biller){
			$this->db->where('biller_id', $biller);
		}
		if($reference_no){
			$this->db->where('erp_sales.reference_no', $reference_no);
		}
		if($from_date && $to_date){
			
			$this->db->where('date_format(date,"%Y-%m-%d") >="'.$from_date.'" AND date_format(date,"%Y-%m-%d") <="'.$to_date.'"');
		}
		$this->db->group_by('erp_sale_items.product_id');
		$this->db->order_by('erp_sale_items.id', 'desc');
		$q = $this->db->get('erp_sale_items');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getProductsInventoryValuationByProduct($warehouse_id,$category_id,$product_id,$stockType,$from_date,$to_date,$reference_no, $biller){
        $this->db->select('
                            stock_trans.*,
                            products.name as product_name,
                            companies.company AS biller_company,
                            companies.name AS biller_name,
                            products.image,
                            CASE
                                WHEN erp_stock_trans.tran_type = \'PURCHASE\' THEN
                                    erp_purchases.reference_no
                                WHEN erp_stock_trans.tran_type = \'SALE\' THEN
                                    erp_sales.reference_no
                                WHEN erp_stock_trans.tran_type = \'ADJUSTMENT\' THEN
                                    erp_adjustments.reference_no
                                WHEN erp_stock_trans.tran_type = \'USING STOCK\' THEN
                                    erp_enter_using_stock.reference_no
                                WHEN erp_stock_trans.tran_type = \'CONVERT\' THEN
                                    erp_convert.reference_no
                                WHEN erp_stock_trans.tran_type = \'TRANSFER\' THEN
                                    erp_transfers.transfer_no
                                WHEN erp_stock_trans.tran_type = \'SALE RETURN\' THEN
                                    erp_return_sales.reference_no
                                WHEN erp_stock_trans.tran_type = \'DELIVERY\' THEN
                                    erp_deliveries.do_reference_no
                                
                            END as reference_no     
                            ');
        $this->db->join('companies', 'companies.id = stock_trans.biller_id', 'left');
        $this->db->join('products', 'products.id = stock_trans.product_id', 'left');
        $this->db->join('purchases', 'stock_trans.tran_id = purchases.id', 'left');
        $this->db->join('sales', 'stock_trans.tran_id = sales.id', 'left');
        $this->db->join('adjustments', 'stock_trans.tran_id = adjustments.id', 'left');
        $this->db->join('enter_using_stock', 'stock_trans.tran_id = enter_using_stock.id', 'left');
        $this->db->join('convert', 'stock_trans.tran_id = convert.id', 'left');
        $this->db->join('transfers', 'stock_trans.tran_id = transfers.id', 'left');
        $this->db->join('return_sales', 'stock_trans.tran_id = return_sales.id', 'left');
        $this->db->join('deliveries', 'stock_trans.tran_id = deliveries.id', 'left');

		if ($this->Settings->product_expiry == 1) {
            $this->db->select('SUM(COALESCE(erp_stock_trans.quantity,0)) as quantity');
            $this->db->group_by('stock_trans.expired_date');
            $this->db->group_by('stock_trans.tran_type');
		}

		if($category_id){
            $this->db->where('products.category_id', $category_id);
		}
		if($product_id){
            $this->db->where('stock_trans.product_id', $product_id);
		}
		
		if($warehouse_id){
            $this->db->where('stock_trans.warehouse_id', $warehouse_id);
		}
		
		if($stockType){
            $this->db->where('stock_trans.tran_type', $stockType);
		}
		if($biller){
            $this->db->where('stock_trans.biller_id', $biller);
		}
		if($reference_no){
            $this->db->where('purchases.reference_no', $reference_no);
		}
		if($from_date && $to_date){
            $this->db->where('date_format(erp_stock_trans.tran_date,"%Y-%m-%d") >="' . $from_date . '" AND date_format(erp_stock_trans.tran_date,"%Y-%m-%d") <="' . $to_date . '"');
        }
        $q = $this->db->get('stock_trans');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getProductsProfitByProduct($warehouse_id,$category_id,$product_id,$from_date,$to_date,$reference_no, $biller){
        $this->db->select('erp_sales.date,erp_sales.customer,erp_sales.reference_no,erp_sale_items.*, companies.name AS biller_name, product_variants.qty_unit as qty_variant, products.image');
		$this->db->join('erp_sales','erp_sales.id = erp_sale_items.sale_id');
		$this->db->join('companies', 'companies.id = erp_sales.biller_id', 'left');
		$this->db->join('product_variants', 'product_variants.id = erp_sale_items.option_id', 'left');
		$this->db->join('products','products.id = sale_items.product_id');
		if($warehouse_id){
			$this->db->where('erp_sale_items.warehouse_id', $warehouse_id);
		}
		if($category_id){
			$this->db->where('products.category_id', $category_id);
		}
		if($product_id){
			$this->db->where('erp_sale_items.product_id', $product_id);
		}
		
		if($biller){
			$this->db->where('biller_id', $biller);
		}
		if($reference_no){
			$this->db->where('reference_no', $reference_no);
		}
		if($from_date && $to_date){
			$this->db->where('date_format(date,"%Y-%m-%d") >="'.$from_date.'" AND date_format(date,"%Y-%m-%d") <="'.$to_date.'"');
		}
		$q = $this->db->get('erp_sale_items');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	

	public function getProductsGrossMarginData($warehouse_id,$category_id,$product_id,$stockType,$from_date,$to_date,$reference_no, $biller){
		$this->db->select('inventory_valuation_details.*, companies.name AS biller_name, products.code as pcode, products.name as pname, categories.name as cname, purchase_items.quantity as BBQty, purchase_items.net_unit_cost as BBCost, sale_items.unit_cost as OCost, sale_items.unit_price as OSPrice');
		$this->db->join('companies', 'companies.id = inventory_valuation_details.biller_id', 'left');
		$this->db->join('categories', 'categories.id = inventory_valuation_details.category_id', 'left');
		$this->db->join('products', 'products.id = inventory_valuation_details.product_id', 'left');
		$this->db->join('purchase_items', 'purchase_items.product_id = inventory_valuation_details.product_id', 'left');
		$this->db->join('sale_items', 'sale_items.product_id = inventory_valuation_details.product_id', 'left');
		$this->db->group_by('inventory_valuation_details.id');

		if($warehouse_id){
			$this->db->where('inventory_valuation_details.warehouse_id', $warehouse_id);
		}
		if($category_id){
			$this->db->where('inventory_valuation_details.category_id', $category_id);
		}
		if($product_id){
			$this->db->where('inventory_valuation_details.product_id', $product_id);
		}
		if($stockType){
			$this->db->where('type', $stockType);
		}
		if($biller){
			$this->db->where('biller_id', $biller);
		}
		if($reference_no){
			$this->db->where('reference_no', $reference_no);
		}
		if($from_date && $to_date){
			//$from_date = date('Y-m-d',strtotime($from_date)).' 00:00:00';
			//$to_date   = date('Y-m-d',strtotime($to_date)).' 00:00:00';
			$this->db->where('date_format(erp_inventory_valuation_details.date,"%Y-%m-%d") >="'.$from_date.'" AND date_format(erp_inventory_valuation_details.date,"%Y-%m-%d") <="'.$to_date.'"');
		}
		$q = $this->db->get('inventory_valuation_details');
		if($q->num_rows() > 0 ) {
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getAllCategories(){
		$q = $this->db->get_where('categories');
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getAllProducts(){
		$q = $this->db->get_where('products');
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getCustomerProducts() {
		$this->db
			->select('products.id as id, products.code, products.name, products.quantity, products.unit, products.cost')
			->from('products');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}			
	public function getConverts($reference,$from_date, $to_date,$per_page,$ob_set){
		$this->db->select("
				erp_convert.id,
				bom_id,
				erp_convert.reference_no,
				erp_convert.date,
				erp_bom.name,
				erp_warehouses.name as wname")
		->join("erp_bom","erp_bom.id=erp_convert.bom_id","LEFT")
		->join("erp_warehouses","erp_warehouses.id=erp_convert.warehouse_id","LEFT");
		if($reference){
			$this->db->where("erp_convert.reference_no",$reference);
		}
		if($from_date && $to_date){
			$this->db->where('erp_convert.date >="'.$this->erp->fld($from_date).'00:00:00" AND erp_convert.date<="'.$this->erp->fld($to_date).' 23:59:00"');
		}
		 $this->db->limit($per_page,$ob_set); 
		$converts = $this->db->get("erp_convert");
		if ($converts->num_rows() > 0) {
            foreach (($converts->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getConvertsProduct($id,$per_page,$ob_set){
		$this->db->select("
				erp_convert.id,
				bom_id,
				erp_convert.reference_no,
				erp_convert.date,
				erp_bom.name,
				erp_warehouses.name as wname")
		->join("erp_bom","erp_bom.id=erp_convert.bom_id","LEFT")
		->join("erp_warehouses","erp_warehouses.id=erp_convert.warehouse_id","LEFT");
		 $this->db->limit($per_page,$ob_set)
		 ->where('erp_convert.id', $id);
		$converts = $this->db->get("erp_convert");
		if ($converts->num_rows() > 0) {
            foreach (($converts->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getSaleCustomerReport($id,$customer){
		$this->db
            ->select("sale_items.id as id, sales.date, sales.reference_no, sale_items.product_name, sales.grand_total, sales.paid, (erp_sales.grand_total - erp_sales.paid) as balacne,erp_sales.note")
            ->from('sales')
            ->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
            ->join('products', 'products.id = sale_items.product_id', 'left')
            ->join('companies', 'companies.id=sales.customer_id','left')
            ->join('users', 'sales.saleman_by = users.id', 'left')
            ->where('sales.customer_id', $customer)
            ->where('sale_items.id', $id)
            ->where('sales.sale_status <> "returned"')
            ->group_by('sales.reference_no');
            $q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getConvertExportByID($id){
		$this->db->select("erp_convert_items.id,erp_convert_items.product_id,erp_convert_items.product_code,erp_convert_items.product_name,SUM(erp_convert_items.quantity) as con_qty,erp_units.name as unit")
		->join('erp_products','erp_products.id=erp_convert_items.product_id','LEFT')
		->join('erp_units','erp_units.id = erp_products.unit','LEFT')
		->where("erp_convert_items.status","add")
		->where("erp_convert_items.product_id",$id)
		->group_by("erp_convert_items.product_id")
		->limit($page,$offset);
		$q = $this->db->get('erp_convert_items');
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE; 
	}
	
	function getConvertExportDetails($id){
		$this->db->select("erp_convert.reference_no,erp_convert.date,erp_convert.id,erp_convert_items.product_id,erp_warehouses.name as warehouse ,erp_users.username")
	    ->join('erp_convert_items','erp_convert_items.convert_id=erp_convert.id','LEFT')
		->join('erp_warehouses','erp_warehouses.id=erp_convert.warehouse_id','LEFT')
		->join('erp_users','erp_users.id=erp_convert.created_by','LEFT')
		->where('erp_convert_items.convert_id',$id)
		->group_by('erp_convert_items.convert_id')
		->limit($page,$offset);
		$q = $this->db->get('erp_convert');
		if ($q->num_rows() > 0){
            foreach (($q->result()) as $row){
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	
	public function getWarePur($wid,$warehouse,$product,$category,$biller){
		$this->db->select("erp_warehouses.id,erp_warehouses.name")
				 ->join("erp_warehouses","erp_warehouses.id = stock_trans.warehouse_id","LEFT")
				 ->join("products","products.id = stock_trans.product_id","LEFT");
		if ($warehouse) {
			$this->db->where("stock_trans.warehouse_id",$warehouse);
		} else {
			if($wid){
				$this->db->where("stock_trans.warehouse_id IN ($wid)");
			}
		}
		if ($product) {
			$this->db->where("stock_trans.product_id",$product);
		}
		if($category){
			$this->db->where("products.category_id",$category);
		}
		$this->db->group_by("stock_trans.warehouse_id");
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getWareFull(){	
		$q = $this->db->get("erp_warehouses");
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getWareFullByUSER($id){
		if($id){
			$this->db->where("id IN ($id)");
		}
		$q = $this->db->get("erp_warehouses");
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getProPur($wid,$cid,$product2=null,$biller,$start=null,$end=null){
        $this->db->select("product_id,products.code,products.name,units.name as name_unit,products.category_id,stock_trans.warehouse_id, products.image")
            ->join("products","products.id = stock_trans.product_id","LEFT")
            ->join("units","units.id = products.unit","LEFT")
            //->join("erp_purchases","erp_purchases.id=stock_trans.tran_id","LEFT")
            ->where('stock_trans.tran_date >= "'.$start.'" AND stock_trans.tran_date <= "'.$end.'"')
		    ->where('stock_trans.quantity_balance_unit !=', 0);
		if($product2){
			$this->db->where(array("stock_trans.product_id"=>$product2));
		}
        /*if($biller){
            $this->db->where("erp_purchases.biller_id",$biller);
        }*/
		$this->db->where(array("stock_trans.warehouse_id"=>$wid,"products.category_id"=>$cid));
		$this->db->group_by("stock_trans.product_id");
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getQtyINALL($id,$wid,$tr,$start,$end,$biller){
		$this->db->select("SUM(COALESCE(quantity_balance_unit, 0)) as bqty");
		$this->db->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where("quantity_balance_unit >",0);
		$this->db->where('stock_trans.tran_date >="'.$start.'" AND stock_trans.tran_date<="'.$end.'"');
		$this->db->where(array("product_id"=>$id,"tran_type"=>$tr,"stock_trans.warehouse_id"=>$wid));
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getQtyOUTALL($id,$wid,$tr,$start,$end,$biller){
		$this->db->select("SUM(COALESCE((-1)*quantity_balance_unit,0)) as bqty");
		$this->db->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where("quantity_balance_unit <",0);
		$this->db->where('stock_trans.tran_date >="'.$start.'" AND stock_trans.tran_date<="'.$end.'"');
		$this->db->where(array("product_id"=>$id,"tran_type"=>$tr,"stock_trans.warehouse_id"=>$wid));
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getProCat($wid,$category2,$product2,$biller){
		$this->db->select("erp_categories.id,erp_categories.name")
				 ->join("erp_categories","erp_categories.id=products.category_id","LEFT")
				 ->join("stock_trans","stock_trans.product_id = products.id","LEFT")
                ->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		if($category2){
			$this->db->where(array("products.category_id"=>$category2));
		}
		if($product2){
			$this->db->where(array("products.id"=>$product2));
		}
        if($biller){
            $this->db->where("erp_purchases.biller_id",$biller);
        }
		$this->db->where(array("stock_trans.warehouse_id"=>$wid));
		$this->db->limit(2);
		$this->db->group_by("products.category_id");
		$q = $this->db->get("products");
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getTransuctionsPurIN($product2,$warehouse2,$start,$end,$biller){
		$this->db->select("tran_type");
		$this->db->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		$this->db->where("quantity_balance_unit > ",0);
		if($product2){
			$this->db->where("stock_trans.product_id",$product2);
		}
		if($warehouse2){
			$this->db->where("stock_trans.warehouse_id IN ($warehouse2)");
		}
		if($biller){
			$this->db->where_in("erp_purchases.biller_id",$biller);
		}
		$this->db->where('stock_trans.tran_date >= "'.$start.'" AND stock_trans.tran_date <= "'.$end.'"');
		$this->db->where("tran_type!=",null);
		$this->db->order_by("tran_type","ASC");
		$this->db->group_by("tran_type");
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getTransuctionsPurOUT($product2,$warehouse2,$start,$end,$biller){
		$this->db->select("tran_type");
		$this->db->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		$this->db->where("quantity_balance_unit <",0);
		if($product2){
			$this->db->where("stock_trans.product_id",$product2);
		}
		if($warehouse2){
			$this->db->where("stock_trans.warehouse_id IN ($warehouse2)");
		}
		if($biller){
			$this->db->where_in("erp_purchases.biller_id",$biller);
		}
		$this->db->where('stock_trans.tran_date >="'.$start.'" AND stock_trans.tran_date<="'.$end.'"');
		$this->db->where("tran_type!=",null);
		$this->db->order_by("tran_type","ASC");
		$this->db->group_by("tran_type");
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getAmountQtyINALL($id,$wid,$tr,$cid,$start,$end){
		$this->db->select("SUM(COALESCE(quantity_balance_unit,0)) as bqty")
		->join("products","products.id=stock_trans.product_id","LEFT");
		$this->db->where("quantity_balance_unit>",0);
		if($id){
			$this->db->where(array("product_id"=>$id));
		}
		$this->db->where('stock_trans.tran_date >="'.$start.'" AND stock_trans.tran_date<="'.$end.'"');
		$this->db->where(array("tran_type"=>$tr,"stock_trans.warehouse_id"=>$wid,"products.category_id"=>$cid));
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getAmountQtyOUTALL($id,$wid,$tr,$cid,$start,$end,$biller){
		$this->db->select("SUM(COALESCE((-1)*quantity_balance_unit,0)) as bqty")
		->join("products","products.id=stock_trans.product_id","LEFT");
		$this->db->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where("quantity_balance_unit <",0);
		if($id){
			$this->db->where(array("product_id"=>$id));
		}
		$this->db->where('stock_trans.tran_date >="'.$start.'" AND stock_trans.tran_date<="'.$end.'"');
		$this->db->where(array("tran_type"=>$tr,"stock_trans.warehouse_id"=>$wid,"products.category_id"=>$cid));
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getAmountQtyINALLCAT($id,$wid,$tr,$cid,$start,$end,$biller){
		$this->db->select("SUM(COALESCE(quantity_balance_unit,0)) as bqty")
		->join("products","products.id=stock_trans.product_id","LEFT");
		$this->db->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		$this->db->where("erp_purchases.status !=", "pending");
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where("quantity_balance_unit >",0);
		if($id){
			$this->db->where(array("product_id"=>$id));
		}
		if($cid){
			$this->db->where(array("products.category_id"=>$cid));
		}
		$this->db->where('stock_trans.tran_date >="'.$start.'" AND stock_trans.tran_date<="'.$end.'"');
		$this->db->where(array("tran_type"=>$tr,"stock_trans.warehouse_id"=>$wid));
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getAmountQtyOUTALLCAT($id,$wid,$tr,$cid,$start,$end,$biller){
		$this->db->select("SUM(COALESCE((-1)*quantity_balance_unit,0)) as bqty")
		->join("products","products.id=stock_trans.product_id","LEFT");
		$this->db->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where("quantity_balance_unit <",0);
		if($id){
			$this->db->where(array("product_id"=>$id));
		}
		if($cid){
			$this->db->where(array("products.category_id"=>$cid));
		}
		$this->db->where('stock_trans.tran_date >="'.$start.'" AND stock_trans.tran_date<="'.$end.'"');
		$this->db->where(array("tran_type"=>$tr,"stock_trans.warehouse_id"=>$wid));
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getBeginQtyINALL($id,$wid,$start,$end,$biller){
		$numMonth=1;
		$startDate=date('Y-m-01',strtotime($start . " - $numMonth month"));
		$endDate=date('Y-m-t',strtotime($start . " - $numMonth month"));
		$this->db->select("SUM(COALESCE(quantity_balance_unit, 0)) as bqty");
		$this->db->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		$this->db->where("quantity_balance_unit >",0);
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where('stock_trans.tran_date >="'.$startDate.'" AND stock_trans.tran_date <="'.$endDate.'"');
		$this->db->where(array("product_id"=>$id,"stock_trans.warehouse_id"=>$wid));
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

	public function getBeginQtyINALL2($id,$wid,$start,$end,$biller){
		$numMonth=0;
		$startDate=date('Y-m-01',strtotime($start . " - $numMonth month"));
		$endDate=date('Y-m-t',strtotime($start . " - $numMonth month"));
		$this->db->select("SUM(COALESCE(quantity_balance,0)) as bqty");
		$this->db->join("erp_purchases","erp_purchases.id=purchase_items.purchase_id","LEFT");
		$this->db->where("quantity_balance>",0);
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where('purchase_items.date >="'.$startDate.'" AND purchase_items.date<="'.$endDate.'"');
		$this->db->where(array("product_id"=>$id,"purchase_items.warehouse_id"=>$wid));
		$q = $this->db->get("purchase_items");
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getBeginQtyOUTALL($id,$wid,$start,$end,$biller){
		$numMonth=1;
		$startDate=date('Y-m-01',strtotime($start . " - $numMonth month"));
		$endDate=date('Y-m-t',strtotime($start . " - $numMonth month"));
		$this->db->select("SUM(COALESCE((-1)*quantity_balance_unit, 0)) as bqty");
		$this->db->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		$this->db->where("quantity_balance_unit <",0);
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where('stock_trans.tran_date >="'.$startDate.'" AND stock_trans.tran_date<="'.$endDate.'"');
		$this->db->where(array("product_id"=>$id,"stock_trans.warehouse_id"=>$wid));
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getBeginQtyOUTALL2($id,$wid,$start,$end,$biller){
		$numMonth=0;
		$startDate=date('Y-m-01',strtotime($start . " - $numMonth month"));
		$endDate=date('Y-m-t',strtotime($start . " - $numMonth month"));
		$this->db->select("SUM(COALESCE((-1)*quantity_balance,0)) as bqty");
		$this->db->join("erp_purchases","erp_purchases.id=purchase_items.purchase_id","LEFT");
		$this->db->where("quantity_balance<",0);
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where('purchase_items.date >="'.$startDate.'" AND purchase_items.date<="'.$endDate.'"');
		$this->db->where(array("product_id"=>$id,"purchase_items.warehouse_id"=>$wid));
		$q = $this->db->get("purchase_items");
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

	public function getAllProductsDetails($product_id, $cid, $per_page, $ob_set, $start_date, $end_date)
	{
		$user_warehouse = $this->session->userdata('warehouse_id');
		$user_warehouses = explode(',', $user_warehouse);
		if ($user_warehouse) {
			$this->db->select("products.*,units.name as uname,purchase_items.expiry");
			$this->db->join("units","units.id=products.unit","left");
			$this->db->join("purchase_items","products.id = purchase_items.product_id","left");
			$this->db->join('warehouses_products', 'purchase_items.product_id = warehouses_products.product_id', 'left');
			$this->db->order_by('purchase_items.id', 'desc');

			if ($this->Settings->product_expiry == 1) {
				$this->db->group_by('purchase_items.expiry');
			} else {
				$this->db->group_by('products.id');
				if (count($user_warehouses) >1) {
                    $this->db->where_in("warehouses_products.warehouse_id",$user_warehouses);
                } else {
                    $this->db->where("warehouses_products.warehouse_id",$user_warehouse);
                }
				$this->db->where("warehouses_products.quantity <>", 0);
			}
			if($cid){
				$this->db->where("category_id", $cid);
			}

			$this->db->where("products.type !=", "service");
			

			if($product_id){
				$this->db->where('purchase_items.product_id', $product_id);
			}
			if ($start_date) {
				$this->db->where($this->db->dbprefix('purchase_items').'.expiry BETWEEN "' . $start_date . '" AND "' . $end_date . '"');
			}
			
			$this->db->limit($per_page, $ob_set); 
			$q = $this->db->get('products');
			if($q->num_rows()>0){
				foreach($q->result() as $row){
					$data[] = $row;
				}
				return $data;
			}
			return false;

		} else {
			$this->db->select("products.*,units.name as uname,purchase_items.expiry");
			$this->db->join("units","units.id=products.unit","left");
			$this->db->join("purchase_items","products.id = purchase_items.product_id","left");
			$this->db->order_by('purchase_items.id', 'desc');

			if ($this->Settings->product_expiry == 1) {
				$this->db->group_by('purchase_items.expiry');
			} else {
				$this->db->group_by('products.id');
			}
			if($cid){
				$this->db->where("category_id", $cid);
			}
			$this->db->where("products.type !=", "service");

			if($product_id){
				$this->db->where('purchase_items.product_id', $product_id);
			}
			if ($start_date) {
				$this->db->where($this->db->dbprefix('purchase_items').'.expiry BETWEEN "' . $start_date . '" AND "' . $end_date . '"');
			}
			
			$this->db->limit($per_page, $ob_set); 
			$q = $this->db->get('products');
			if($q->num_rows()>0){
				foreach($q->result() as $row){
					$data[] = $row;
				}
				return $data;
			}
			return false;
		}
	}

	public function getQtyByWare($pid,$wid,$product2,$category2,$biller2, $expiry, $wid1, $start_date1, $end_date1, $warehouse2)
	{
		$user_warehouses = $this->session->userdata('warehouse_id');
		if ($user_warehouses) {

			if ($this->Settings->product_expiry == 1) {
				$this->db->select("SUM(COALESCE(erp_warehouses_products.quantity_balance,0)) as wqty");
				$this->db->join('products', 'purchase_items.product_id = products.id', 'left');
			} else {
				$this->db->select("erp_warehouses_products.quantity as wqty");
				$this->db->join('products', 'purchase_items.product_id = products.id', 'left');
			} 

			$this->db->where("purchase_items.status =", "received");
			$this->db->join('warehouses_products', 'purchase_items.product_id = warehouses_products.product_id', 'left');
			$this->db->join('purchases', 'purchase_items.purchase_id = purchases.id', 'left');

			if ($this->Settings->product_expiry == 1) {
				$this->db->group_by('purchase_items.expiry');
				$this->db->where("purchase_items.expiry",$expiry);
			} else {
				$this->db->group_by('purchase_items.product_id');
			}
			
			$this->db->where("warehouses_products.product_id",$pid);
			$this->db->where("warehouses_products.warehouse_id",$wid);
			$this->db->where("warehouses_products.quantity <>", 0);

			if($product2){
				$this->db->where('erp_warehouses_products.product_id', $product2);
			}
			if($category2){
				$this->db->where('products.category_id', $category2);
			}
			if($warehouse2){
				$this->db->where('erp_warehouses_products.warehouse_id', $warehouse2);
			}
			if ($start_date1) {
				$this->db->where($this->db->dbprefix('products').'.start_date BETWEEN "' . $start_date1 . '" AND "' . $end_date1 . '"');
			}

			$q = $this->db->get("purchase_items");
			if ($q->num_rows() > 0) {
	            return $q->row();
	        }
	        return FALSE;
		} else {
			$this->db->select("COALESCE(erp_warehouses_products.quantity,0) as wqty");
			$this->db->join('products', 'purchase_items.product_id = products.id', 'left');
			$this->db->join('purchases', 'purchase_items.purchase_id = purchases.id', 'left');
			$this->db->join('erp_warehouses_products', 'purchase_items.product_id = erp_warehouses_products.product_id', 'left');
			$this->db->where("purchase_items.status =", "received");

			if ($this->Settings->product_expiry == 1) {
				$this->db->group_by('purchase_items.expiry');
				$this->db->where("purchase_items.expiry",$expiry);
			} else {
				$this->db->group_by('purchase_items.product_id');
			}

			$this->db->where("erp_warehouses_products.product_id",$pid);
			$this->db->where("erp_warehouses_products.warehouse_id",$wid);

			if($product2){
				$this->db->where('erp_warehouses_products.product_id', $product2);
			}
			if($category2){
				$this->db->where('products.category_id', $category2);
			}
			if($warehouse2){
				$this->db->where('erp_warehouses_products.warehouse_id', $warehouse2);
			}
			if ($start_date1) {
				$this->db->where($this->db->dbprefix('purchase_items').'.expiry BETWEEN "' . $start_date1 . '" AND "' . $end_date1 . '"');
			}

			$q = $this->db->get("purchase_items");
			if ($q->num_rows() > 0) {
	            return $q->row();
	        }
	        return FALSE;
		}
	}
	public function getQtyByPro($pid,$product2,$category2){
		$this->db->select("SUM(COALESCE(erp_warehouses_products.quantity,0)) as wqty");
		$this->db->join("products","products.id=warehouses_products.product_id","LEFT");
		if($product2){
			$this->db->where("warehouses_products.product_id",$product2);
		}
		if($category2){
			$this->db->where("products.category_id",$category2);
		}
		$this->db->where(array("product_id"=>$pid));
		$q = $this->db->get("erp_warehouses_products");
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getQtyByWareID($wid,$product2,$category2){
		$this->db->select("SUM(COALESCE(erp_warehouses_products.quantity,0)) as wqty");
		$this->db->join("products","products.id=warehouses_products.product_id","LEFT");
		if($product2){
			$this->db->where("warehouses_products.product_id",$product2);
		}
		if($category2){
			$this->db->where("products.category_id",$category2);
		}
		$this->db->where("warehouse_id",$wid);
		$q = $this->db->get("erp_warehouses_products");
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

	public function getAllProductsDetail1($pid,$cid, $from_date, $to_date)
	{
		$user_warehouses = $this->session->userdata('warehouse_id');

		if ($user_warehouses) {
			$this->db->select("products.*,units.name as uname,purchase_items.expiry");
			$this->db->join("units","units.id=products.unit","left");
			$this->db->join("purchase_items","products.id = purchase_items.product_id","left");
			$this->db->join('warehouses_products', 'purchase_items.product_id = warehouses_products.product_id', 'left');

			if ($this->Settings->product_expiry == 1) {
				$this->db->group_by('purchase_items.expiry');
			} else {
				$this->db->group_by('products.id');
				$this->db->where("warehouses_products.warehouse_id",$user_warehouses);
				$this->db->where("warehouses_products.quantity <>", 0);
			}
			if($pid){
				$this->db->where("products.id", $pid);
			}
			if($cid){
				$this->db->where("category_id", $cid);
			}

			$this->db->where("products.type !=", "service");
			

			if ($from_date) {
				$this->db->where($this->db->dbprefix('purchase_items').'.date BETWEEN "' . $from_date . '" and "' . $to_date . '"');
			}
			
			$this->db->limit($per_page, $ob_set); 
			$q = $this->db->get('products');
			if($q->num_rows()>0){
				foreach($q->result() as $row){
					$data[] = $row;
				}
				return $data;
			}
			return false;

		} else {
			$this->db->select("products.*,units.name as uname, purchase_items.expiry");
			$this->db->join("units","units.id=products.unit","LEFT");
			$this->db->join("purchase_items","products.id = purchase_items.product_id","LEFT");

			if ($this->Settings->product_expiry == 1) {
				$this->db->group_by('purchase_items.expiry');
			} else {
				$this->db->group_by('products.id');
			}

			if($pid){
				$this->db->where("products.id",$pid);
			}

			if($cid){
				$this->db->where("category_id",$cid);
			}

			$this->db->where("products.type !=", "service");
			if ($from_date) {
				$this->db->where($this->db->dbprefix('purchase_items').'.date BETWEEN "' . $from_date . '" and "' . $to_date . '"');
			}

			$q = $this->db->get('products');
			if($q->num_rows()>0){
				foreach($q->result() as $row){
					$data[] = $row;
				}
				return $data;
			}
			return false;
		}
	}

	public function getAllProductsDetailsNUM($pid,$cid){
		$this->db->select("products.*,units.name as uname");
		$this->db->join("units","units.id=products.unit","LEFT");
		if($pid){
			$this->db->where("products.id",$pid);
		}
		if($cid){
			$this->db->where("category_id",$cid);
		}
		
		$q = $this->db->get('products');
		if($q->num_rows()>0){
			return $q->num_rows();
		}
		return false;
	}
	public function getQtyUnitINALL($id,$wid,$tr,$start,$end,$biller){
		$this->db->select("SUM(COALESCE(quantity_balance_unit, 0)) as bqty");
		$this->db->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}

		$this->db->where("quantity_balance_unit >",0);
		$this->db->where('stock_trans.tran_date >="'.$start.'" AND stock_trans.tran_date<="'.$end.'"');
		$this->db->where(array("stock_trans.product_id"=>$id,"tran_type"=>$tr,"stock_trans.warehouse_id"=>$wid));
		//$this->db->group_by("option_id");
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
			return $q->row();
        }
        return FALSE;
	}
	public function getQtyUnitOUTALL($id,$wid,$tr,$start,$end,$biller){
		$this->db->select("SUM(COALESCE((-1)*quantity_balance_unit,0)) as bqty");
		$this->db->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where("quantity_balance_unit <",0);
		$this->db->where('stock_trans.tran_date >="'.$start.'" AND stock_trans.tran_date<="'.$end.'"');
		$this->db->where(array("stock_trans.product_id"=>$id,"tran_type"=>$tr,"stock_trans.warehouse_id"=>$wid));
		
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
           return $q->row();
        }
        return FALSE;
	}
	
	public function getQtyUnitALL($id,$wid,$start,$end){
		$this->db->select("SUM(COALESCE(quantity_balance,0)) as bqty");
		
		$this->db->where('purchase_items.date >="'.$start.'" AND purchase_items.date<="'.$end.'"');
		$this->db->where(array("erp_purchase_items.product_id"=>$id,"warehouse_id"=>$wid));
		
		$q = $this->db->get("purchase_items");
		if ($q->num_rows() > 0) {
           return $q->row();
        }
        return FALSE;
	}
	public function getBeginQtyALL($id,$wid,$start,$end,$biller){
		$numMonth=1;
		$startDate=date('Y-m-01',strtotime($start . " - $numMonth month"));
		$endDate=date('Y-m-t',strtotime($start . " - $numMonth month"));
		$this->db->select("SUM(COALESCE(quantity_balance_unit, 0)) as bqty");
		$this->db->join("erp_purchases","erp_purchases.id = stock_trans.tran_id","LEFT");
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where('stock_trans.tran_date >="'.$startDate.'" AND stock_trans.tran_date<="'.$endDate.'"');
		$this->db->where(array("stock_trans.product_id"=>$id,"stock_trans.warehouse_id"=>$wid));
	
		$q = $this->db->get("stock_trans");
		if ($q->num_rows() > 0) {
           return $q->row();
        }
        return FALSE;
	}
	
	//-----------------------------stock in out
	public function getStockINOUT($product,$category,$warehouse,$wid,$in_out,$year,$month,$per_page,$ob_set,$biller){
		$datee = $year.'-'.$month;
		$this->db->select("purchase_items.product_id,products.code,products.image,products.name,purchase_items.date,DATE_FORMAT(erp_purchase_items.date, '%Y-%m') as dater,DATE_FORMAT(erp_purchase_items.date, '%d') as datday,units.name as name_unit")
		->join("products","products.id=purchase_items.product_id","LEFT")
		->join("units","units.id=products.unit","LEFT")
		->join("erp_purchases","erp_purchases.id=purchase_items.purchase_id","LEFT");
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where("DATE_FORMAT(erp_purchase_items.date, '%Y-%m')=",$datee);
		if($product){
			$this->db->where("purchase_items.product_id",$product);
		}
		if($category){
			$this->db->where("products.category_id",$category);
		}
		if($warehouse){
			$this->db->where("erp_purchase_items.warehouse_id",$warehouse);
		}else{
			if($wid){
				$this->db->where("erp_purchase_items.warehouse_id IN ($wid)");
			}
		}
		$this->db->group_by("purchase_items.product_id")
		->group_by("DATE_FORMAT('erp_purchase_items.date', '%Y-%m')");
		
		 $this->db->limit($per_page,$ob_set); 
		$q = $this->db->get("purchase_items");
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	//-------stock-in-out for report export
	public function getStockINOUTS($product,$category,$warehouse,$wid,$in_out,$year,$month,$biller){
		$datee = $year.'-'.$month;
		$this->db->select("purchase_items.product_id,products.code,products.name,purchase_items.date,DATE_FORMAT(erp_purchase_items.date, '%Y-%m') as dater,DATE_FORMAT(erp_purchase_items.date, '%d') as datday,units.name as name_unit")
		->join("products","products.id=purchase_items.product_id","LEFT")
		->join("units","units.id=products.unit","LEFT")
		->join("erp_purchases","erp_purchases.id=purchase_items.purchase_id","LEFT")
		->where("DATE_FORMAT(erp_purchase_items.date, '%Y-%m')=",$datee);
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		
		if($product){
			$this->db->where("purchase_items.product_id",$product);
		}
		if($category){
			$this->db->where("products.category_id",$category);
		}
		if($warehouse){
			$this->db->where("erp_purchase_items.warehouse_id",$warehouse);
		}else{
			if($wid){
				$this->db->where("erp_purchase_items.warehouse_id IN ($wid)");
			}
		}
		$this->db->group_by("purchase_items.product_id")
		->group_by("DATE_FORMAT('erp_purchase_items.date', '%Y-%m')");
		$q = $this->db->get("purchase_items");
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getStockINOUTNUM($product,$category,$in_out,$year,$month,$warehouse,$wid,$biller){
		
		$datee = $year.'-'.$month;
		$this->db->select("purchase_items.product_id,products.code,products.name,purchase_items.date,DATE_FORMAT(erp_purchase_items.date, '%Y-%m') as dater,DATE_FORMAT(erp_purchase_items.date, '%d') as datday")
		->join("products","products.id=purchase_items.product_id","LEFT")
		->join("erp_purchases","erp_purchases.id=purchase_items.purchase_id","LEFT");
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where("DATE_FORMAT(erp_purchase_items.date, '%Y-%m')=",$datee);
		if($product){
			$this->db->where("purchase_items.product_id",$product);
		}
		if($category){
			$this->db->where("products.category_id",$category);
		}
		
		if($warehouse){
			$this->db->where("erp_purchase_items.warehouse_id",$warehouse);
		}else{
			if($wid){
				$this->db->where("erp_purchase_items.warehouse_id IN ($wid)");
			}
		}
		$this->db->group_by("purchase_items.product_id")
		->group_by("DATE_FORMAT('erp_purchase_items.date', '%Y-%m')");
		$q = $this->db->get("purchase_items");
		if ($q->num_rows() > 0) {
            return $q->num_rows();
        }
        return FALSE;
	}
	
	public function getStockINOUTM($product,$category,$warehouse,$wid,$in_out,$year,$per_page,$ob_set,$biller){
		$this->db->select("purchase_items.product_id,products.code,products.name,products.image,purchase_items.date,DATE_FORMAT(erp_purchase_items.date, '%Y-%m') as dater,DATE_FORMAT(erp_purchase_items.date, '%m') as datm,units.name as name_unit")
		->join("products","products.id=purchase_items.product_id","LEFT")
		->join("units","units.id=products.unit","LEFT")
		->join("erp_purchases","erp_purchases.id=purchase_items.purchase_id","LEFT");
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where("DATE_FORMAT(erp_purchase_items.date, '%Y')=",$year);
		if($product){
			$this->db->where("purchase_items.product_id",$product);
		}
		if($category){
			$this->db->where("products.category_id",$category);
		}
		if($warehouse){
			$this->db->where("erp_purchase_items.warehouse_id",$warehouse);
		}else{		
			if($wid){
				$this->db->where("erp_purchase_items.warehouse_id IN ($wid)");
			}
		}
		$this->db->group_by("purchase_items.product_id");
		 $this->db->limit($per_page,$ob_set); 
		$q = $this->db->get("purchase_items");
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getStockINOUTMS($product,$category,$warehouse,$wid,$in_out,$year,$biller){
		$this->db->select("purchase_items.product_id,products.code,products.name,purchase_items.date,DATE_FORMAT(erp_purchase_items.date, '%Y-%m') as dater,DATE_FORMAT(erp_purchase_items.date, '%m') as datm,units.name as name_unit")
		->join("products","products.id=purchase_items.product_id","LEFT")
		->join("units","units.id=products.unit","LEFT")
		->join("erp_purchases","erp_purchases.id=purchase_items.purchase_id","LEFT")
		->where("DATE_FORMAT(erp_purchase_items.date, '%Y')=",$year);
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		if($product){
			$this->db->where("purchase_items.product_id",$product);
		}
		if($category){
			$this->db->where("products.category_id",$category);
		}
		if($warehouse){
			$this->db->where("erp_purchase_items.warehouse_id",$warehouse);
		}else{		
			if($wid){
				$this->db->where("erp_purchase_items.warehouse_id IN ($wid)");
			}
		}
		$this->db->group_by("purchase_items.product_id");
		$q = $this->db->get("purchase_items");
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	public function getStockINOUTNUMM($product,$category,$in_out,$year,$warehouse,$wid,$biller){
		$this->db->select("purchase_items.product_id,products.code,products.name,products.image,purchase_items.date,DATE_FORMAT(erp_purchase_items.date, '%Y-%m') as dater,DATE_FORMAT(erp_purchase_items.date, '%m') as datm")
		->join("products","products.id=purchase_items.product_id","LEFT")
		->join("erp_purchases","erp_purchases.id=purchase_items.purchase_id","LEFT");
		if($biller){
			$this->db->where("erp_purchases.biller_id",$biller);
		}
		$this->db->where("DATE_FORMAT(erp_purchase_items.date, '%Y')=",$year);
		if($product){
			$this->db->where("purchase_items.product_id",$product);
		}
		if($category){
			$this->db->where("products.category_id",$category);
		}
		if($warehouse){
			$this->db->where("erp_purchase_items.warehouse_id ",$warehouse);
		}else{		
			if($wid){
				$this->db->where("erp_purchase_items.warehouse_id IN ($wid)");
			}
		}
		$this->db->group_by("purchase_items.product_id");
		$q = $this->db->get("purchase_items");
		if ($q->num_rows() > 0) {
            return $q->num_rows();
        }
        return FALSE;
	}
	//---------------------------End stock in out
	public function getWareByUserID(){
		$q = $this->db->get_where("users",array("id"=>$this->session->userdata('user_id')),1);
		if ($q->num_rows() > 0) {
            return $q->row()->warehouse_id;
        }
        return FALSE;
	}
	public function getBiilerByUserID(){
		$q = $this->db->get_where("users",array("id"=>$this->session->userdata('user_id')),1);
		if ($q->num_rows() > 0) {
            return $q->row()->biller_id;
        }
        return FALSE;
	}
	public function getV($id){
		$q = $this->db->get_where('purchase_items',array('id'=> $id),1);
		if ($q->num_rows() > 0) {
            return $q->row()->option_id;
        }
        return FALSE;
	}
	public function getUn($id){
		$q = $this->db->get_where("erp_units",array('id'=>$id),1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	//Get expense for view expense report
	public function getCatExpenseReport($reference_no,$from_date,$to_date,$per_page,$ob_set){
		$this->db->select("gl_trans.narrative,expenses.account_code")
            ->from('expenses')
			->join('gl_trans', 'gl_trans.account_code = expenses.account_code', 'left');
			if($reference_no){
				$this->db->where('expenses.reference',$reference_no);
			}
			if($from_date && $to_date){
				$this->db->where('date_format(erp_expenses.date,"%Y-%m-%d") >="'.$from_date.'" AND date_format(erp_expenses.date,"%Y-%m-%d") <="'.$to_date.'"');
			}
            $this->db->group_by('expenses.account_code');
			$this->db->limit($per_page,$ob_set); 
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	//End get expense export of view expense report
	
	//Get expense for export
	public function getCatExpenseReports($code){
		$this->db->select("gl_trans.narrative,expenses.account_code")
            ->from('expenses')
			->join('gl_trans', 'gl_trans.account_code = expenses.account_code', 'left');
			$this->db->where('expenses.account_code',$code);
            $this->db->group_by('expenses.account_code');
		$q = $this->db->get();
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	//End expense export
	public function getCatExpenseReportNUM($reference_no,$from_date,$to_date){
		$this->db->select("gl_trans.narrative,expenses.account_code")
            ->from('expenses')
			->join('gl_trans', 'gl_trans.account_code = expenses.account_code', 'left');
			if($reference_no){
				$this->db->where('expenses.reference',$reference_no);
			}
			if($from_date && $to_date){
				$this->db->where('date_format(erp_expenses.date,"%Y-%m-%d") >="'.$from_date.'" AND date_format(erp_expenses.date,"%Y-%m-%d") <="'.$to_date.'"');
			}
			
            $this->db->group_by('expenses.account_code');
		$q = $this->db->get();
		if($q->num_rows()>0){
			return $q->num_rows();
		}
		return false;
	}
	
	public function getLastDate($table,$field){
		$this->db->select("MAX(date_format($field,'%Y-%m-%d')) as datt");
		$q = $this->db->get("$table");
		if($q->num_rows()>0){
			return $q->row()->datt;
		}
		return false;
	}

	public function getProjectPlanName($start_date=null, $end_date=null, $customer=null, $balance=null){
		$this->db
		     ->select("project_plan.*, CONCAT(erp_products.cf4, ' ', erp_products.cf3) as address, enter_using_stock.address_id", false)
             ->from("enter_using_stock")
             ->join('project_plan', 'enter_using_stock.plan_id = project_plan.id', 'left')
             ->join('products', 'enter_using_stock.address_id = products.id', 'left')
             ->group_by('enter_using_stock.plan_id')
             ->group_by('enter_using_stock.address_id')
             ->order_by('enter_using_stock.id', 'desc');
           
		    /*if($start_date && $end_date){
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
		    }*/

            $q = $this->db->get();
            if($q->num_rows() > 0){
                return $q->result();
            }
            return false;
	}

	public function getProjectPlanAndUsing($plan_id){
		$this->db
		     ->select("
		     			erp_project_plan_items.product_name,
		     			erp_project_plan_items.quantity as project_plan,
						SUM(IF(erp_enter_using_stock_items.code = erp_project_plan_items.product_code,
						erp_enter_using_stock_items.qty_use, 0)) as using_stock,

						(erp_project_plan_items.quantity - SUM(IF(erp_enter_using_stock_items.code = erp_project_plan_items.product_code,
						erp_enter_using_stock_items.qty_use, ''))) as balance
		     		", false)
             ->from("erp_enter_using_stock")
             ->join('erp_enter_using_stock_items', 'enter_using_stock.reference_no = erp_enter_using_stock_items.reference_no', 'left')
             ->join('erp_project_plan_items', 'erp_enter_using_stock.plan_id = erp_project_plan_items.project_plan_id', 'left')
             ->join('erp_products', 'erp_enter_using_stock_items.code = erp_products.code', 'left')
             ->where('erp_enter_using_stock.plan_id', $plan_id)
             ->where('erp_enter_using_stock_items.code', 'ISNULL')
             ->group_by('erp_project_plan_items.product_code');
             $query1 = $this->db->get_compiled_select(); 

        $this->db
		     ->select("
		     			erp_products.name,

						IF(eusi.code = erp_project_plan_items.product_code,
						erp_project_plan_items.quantity, ''),

					 	IF(eusi.code = erp_project_plan_items.product_code,
					 	eusi.qty_use, eusi.qty_use),

						(IF(eusi.code = erp_project_plan_items.product_code,
						erp_project_plan_items.quantity, '')- IF(eusi.code = erp_project_plan_items.product_code,
					 	eusi.qty_use, eusi.qty_use))
		     		")
             ->from("erp_enter_using_stock_items as eusi")
             ->join('erp_enter_using_stock', 'eusi.reference_no = erp_enter_using_stock.reference_no', 'left')
             ->join('erp_project_plan_items', 'erp_enter_using_stock.plan_id = erp_project_plan_items.project_plan_id', 'left')
             ->join('erp_products', 'eusi.code = erp_products.code', 'left')
             ->where('erp_enter_using_stock.plan_id', $plan_id)
             ->group_by('eusi.code');
             $query2 = $this->db->get_compiled_select(); 

            $q = $this->db->query($query1." UNION ".$query2);
            if($q->num_rows() > 0){
                return $q->result();
            }
            return false;
	}
	
	public function getSalePaidInvoice($start_date = NULL, $end_date = NULL)
	{
		$this->db->select('sales.id, sales.date, sales.reference_no , sales.biller, group_areas.areas_group, sales.customer, users.username AS saleman, sales.grand_total')
				 ->from('sales')
				 ->join('users', 'users.id = sales.saleman_by', 'left')
				 ->join('group_areas', 'group_areas.areas_g_code = sales.group_areas_id', 'left')
				 ->where('sales.paid >', 0);
		if ($start_date) {
			$this->db->where("date_format(erp_sales.date,'%Y-%m-%d')  BETWEEN '$start_date' AND '$end_date'");
		}
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}
	
	public function getSalePaidInvoiceById($id = NULL)
	{
		$this->db->select('sales.id, sales.date, sales.reference_no , sales.biller, group_areas.areas_group, sales.customer, users.username AS saleman, sales.grand_total')
				 ->from('sales')
				 ->join('users', 'users.id = sales.saleman_by', 'left')
				 ->join('group_areas', 'group_areas.areas_g_code = sales.group_areas_id', 'left')
				 ->where('sales.id', $id);
				 
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}

	public function getPaidInvoice($id)
	{
		$this->db->select('payments.date, payments.reference_no, companies.company, payments.amount, payments.paid_by, users.username, payments.type')
				 ->from('payments')
				 ->join('users', 'users.id = payments.created_by', 'left')
				 ->join('companies', 'companies.id = payments.biller_id', 'left')
				 ->where('payments.sale_id', $id);
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}

    public function getProductGrossmarginReport($id)
    {
        $this->db
            ->select('sale_items.id as sid, sales.date, sales.reference_no, sale_items.product_code, sale_items.product_name, sale_items.quantity, IF(erp_sale_items.option_id,  erp_product_variants.name, erp_units.name) as unit, IF(erp_sale_items.option_id,  (erp_sale_items.unit_cost * erp_product_variants.qty_unit) * erp_sale_items.quantity, erp_sale_items.unit_cost * erp_sale_items.quantity) as cost, erp_sale_items.subtotal as price, (erp_sale_items.subtotal - IF(erp_sale_items.option_id,  (erp_sale_items.unit_cost * erp_product_variants.qty_unit) * erp_sale_items.quantity, erp_sale_items.unit_cost * erp_sale_items.quantity)) as profit')
            ->from('sales')
            ->join("sale_items", 'sales.id = sale_items.sale_id', 'left')
            ->join("products", 'sale_items.product_id = products.id', 'left')
            ->join("units", 'products.unit = units.id', 'left')
            ->join("product_variants", 'sale_items.option_id = product_variants.id', 'left')
            ->where('sale_items.id', $id);

        $q = $this->db->get();
        if($q->num_rows() > 0){
            return $q->row();
        }
        return false;
    }

    public function getProductValueReport($id)
    {
        $this->db
            ->select('erp_warehouses_products.id
            , products.code as pcode, products.name as pname, warehouses.name as wname, warehouses_products.quantity, products.cost, (erp_warehouses_products.quantity * erp_products.cost) as total_cost')
            ->from('products')
            ->join("warehouses_products", 'products.id=warehouses_products.product_id', 'left')
            ->join('warehouses', 'warehouses_products.warehouse_id = warehouses.id', 'left')
            ->where('warehouses_products.quantity >', 0)
            ->where('warehouses_products.id', $id)
            ->order_by('products.id', 'asc');

        $q = $this->db->get();
        if($q->num_rows() > 0){
            return $q->row();
        }
        return false;
    }

    public function getExportTransferSummaryReport($id = null, $product = null, $from_warehouse = null, $to_warehouse = null, $start_date = null, $end_date = null)
    {
        $this->db
            ->select('
                transfer_items.product_id as pid,
                transfers.date,
                products.name,
                transfers.from_warehouse_name,
                transfers.to_warehouse_name,
                SUM(COALESCE(erp_transfer_items.quantity, 0)) as quantity,
                IF (erp_transfer_items.option_id, erp_product_variants.name, erp_units.name) as unit
                ', FALSE)
            ->join('transfer_items', 'products.id = transfer_items.product_id', 'left')
            ->join('transfers', 'transfer_items.transfer_id = transfers.id', 'left')
            ->join('product_variants', 'transfer_items.option_id = product_variants.id', 'left')
            ->join('units', 'products.unit = units.id', 'left')
            ->where_in('products.id', $id)
            ->group_by('transfers.from_warehouse_id, transfers.to_warehouse_id, transfer_items.option_id, transfer_items.product_id');

        if ($product) {
            $this->db->where('products.id', $product);
        }
        if ($from_warehouse) {
            $this->db->where('transfers.from_warehouse_id', $from_warehouse);
        }
        if ($to_warehouse) {
            $this->db->where('transfers.to_warehouse_id', $to_warehouse);
        }
        if ($start_date) {
            $this->db->where($this->db->dbprefix('transfers') . '.date BETWEEN "' . $start_date . ' 00:00:00" and "' . $end_date . ' 23:59:00"');
        }

        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
}
