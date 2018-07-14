<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model
{
	//********* Kindly to inform for beautiful code first before coding , invoid from messy coding ******/
	
    public function __construct()
    {
        parent::__construct();
    }

    public function insertConvert($data)
    {
        if ($this->db->insert('convert', $data)) {
            $convert_id = $this->db->insert_id();
			
			if ($this->site->getReference('con', $data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('con', $data['biller_id']);
			}
            return $convert_id;
        }
    }
	
	public function updateConvert($id, $data) 
	{
        if ($this->db->update('convert', $data, array('id' => $id))) {
            return true;
        }
        return false;
	}
	
	public function getConvertByID($id) 
	{
		$l_qty = "( SELECT
                        con_item.convert_id,
                        SUM(con_item.cost) as cost,
                        SUM(con_item.quantity) as qty
                    FROM
                        erp_convert_items con_item
                    WHERE
                        con_item.`status` = 'add'
                    GROUP BY
                        con_item.convert_id
                    ) Quantity";
        $this->db
            ->select($this->db->dbprefix('convert') . ".id as id,
                    ".$this->db->dbprefix('convert').".date as Date,
                    ".$this->db->dbprefix('convert').".reference_no as Reference, Quantity.cost, Quantity.qty,
                    ".$this->db->dbprefix('convert').".noted as Note,
                    ".$this->db->dbprefix('warehouses').".name as na,
					".$this->db->dbprefix('convert').".warehouse_id as warehouse_id,
					".$this->db->dbprefix('convert').".bom_id as bom_id,
					".$this->db->dbprefix('convert').".biller_id as biller_id,
                    ".$this->db->dbprefix('users') . ".username ", false)
            ->join('users', 'users.id               = convert.created_by', 'left')
            ->join('warehouses', 'warehouses.id     = convert.warehouse_id', 'left')
            ->join($l_qty, ' Quantity.convert_id    = erp_convert.id', 'left')
            ->group_by('convert.id');
        $q = $this->db->get_where('convert', array('convert.id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function ConvertDeduct($id)
    {
        $this->db->select('products.id as pid, product_name, product_code,'.$this->db->dbprefix('convert_items').'.quantity AS Cquantity,'.$this->db->dbprefix('convert_items').'.cost AS Ccost,'.$this->db->dbprefix('products').'.cost AS Pcost, product_variants.name as variant, product_variants.qty_unit, convert_items.option_id')
				->join('products', 'products.id=convert_items.product_id', 'left')
				->join('product_variants', 'product_variants.id=convert_items.option_id', 'left');
		$q = $this->db->get_where('convert_items', array('convert_id' => $id, 'status' => 'deduct'));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
	public function ConvertAdd($id)
    {
       $this->db->select('products.id as pid, product_name, product_code,'.
			$this->db->dbprefix('convert_items').'.quantity AS Cquantity,'.
			$this->db->dbprefix('convert_items').'.cost AS Ccost,'.
			$this->db->dbprefix('products').'.cost AS Pcost, product_variants.name as variant, product_variants.qty_unit, convert.noted, units.name as unit, convert_items.option_id')
				->join('products', 'products.id=convert_items.product_id', 'left')
                ->join('product_variants', 'product_variants.id=convert_items.option_id', 'left')
				->join('convert', 'convert_items.convert_id = convert.id', 'left')
				->join('units', 'products.unit = units.id', 'left');
		$q = $this->db->get_where('convert_items', array('convert_id' => $id, 'status' => 'add'));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
	public function convertHeader($id)
	{
		$this->db->select('convert.*,users.username,warehouses.name')
			 ->join('users', 'users.id = convert.created_by', 'left')
             ->join('warehouses', 'warehouses.id = convert.warehouse_id', 'left');
		$q = $this->db->get_where("convert", array('convert.id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getConvert_ItemByID($id, $ware_id = NULL)
    {
		$this->db->select('convert_items.id, 
							convert_items.convert_id, 
							convert_items.product_id, 
							convert_items.product_code, 
							convert_items.product_name, 
							convert_items.quantity, 
							convert_items.cost, 
							convert_items.status,
							convert_items.option_id,
							(SELECT COALESCE(quantity , 0) as qoh FROM erp_warehouses_products WHERE warehouse_id = '.$ware_id.' AND erp_warehouses_products.product_id = erp_convert_items.product_id) as qoh, units.name as unit
							')
            ->join('products', 'convert_items.product_id = products.id', 'left')
            ->join('units', 'products.unit = units.id', 'left');
		$q = $this->db->get_where("convert_items", array('convert_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function deleteConvert($id)
    {
        if ($this->db->delete('convert', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function deleteConvert_items($id)
    {
        if ($this->db->delete('convert_items', array('convert_id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function deleteConvert_itemsByPID($id, $product_id)
    {
        if ($this->db->delete('convert_items', array('convert_id' => $id, 'product_id' => $product_id))) {
            return true;
        }
        return FALSE;
    }
	public function deleteConvert_itemsInventory_detail($convert_items_id)
    {
        if ($this->db->delete('inventory_valuation_details', array('type' => 'CONVERT', 'field_id' => $convert_items_id))) {
            return true;
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
	
	public function getCategoryProducts($category_id)
    {
        $q = $this->db->get_where('products', array('category_id' => $category_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSubCategoryProducts($subcategory_id)
    {
        $q = $this->db->get_where('products', array('subcategory_id' => $subcategory_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
    
	public function getProductWithCategory($id)
    {
        $this->db->select($this->db->dbprefix('products') . '.*, ' . $this->db->dbprefix('categories') . '.name as category')
        ->join('categories', 'categories.id=products.category_id', 'left');
        $q = $this->db->get_where('products', array('products.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductOptions($pid)
    {
		$this->db->order_by('qty_unit', 'ASC');
        $q = $this->db->get_where('product_variants', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductOptionsData($pid)
    {
        $this->db->order_by('qty_unit', 'ASC');
        $q = $this->db->get_where('product_variants', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductOptionsByProId($pid)
    {
        $this->db->order_by('qty_unit', 'ASC');
        $q = $this->db->get_where('product_variants', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getQASuggestions($term, $warehouse_id, $limit = 100)
    {
        $this->db->select($this->db->dbprefix('products') . '.id,' .
            $this->db->dbprefix('products') . '.code as code,' .
            $this->db->dbprefix('products') . '.name as name,' .
            $this->db->dbprefix('units') . '.name as vname,  
                                (SELECT COALESCE(quantity , 0) as qoh FROM erp_warehouses_products WHERE warehouse_id = ' . $warehouse_id . ' AND erp_warehouses_products.product_id = erp_products.id GROUP BY erp_warehouses_products.product_id) as qoh')
            ->join('units', 'units.id = products.unit', 'left')
            ->where("type != 'combo' AND " .
                "(" . $this->db->dbprefix('products') . ".name LIKE '%" . $term . "%' OR 
                     " . $this->db->dbprefix('products') . ".code LIKE '%" . $term . "%' OR concat(" . $this->db->dbprefix('products') . ".name, ' (', " . $this->db->dbprefix('products') . ".code, ')') LIKE '%" . $term . "%') AND inactived <> 1 AND type <> 'service' ")
                /*Check product cost if over 0 not show on suggestion list*/
                ->where('products.cost <>',0)
				->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getProductOptionsWithWH($pid)
    {
        $this->db->select($this->db->dbprefix('product_variants') . '.*, ' . $this->db->dbprefix('warehouses') . '.name as wh_name, ' . $this->db->dbprefix('warehouses') . '.id as warehouse_id, ' . $this->db->dbprefix('warehouses_products_variants') . '.quantity as wh_qty, ' . $this->db->dbprefix('product_variants') . '.qty_unit, ('.$this->db->dbprefix('product_variants').'.cost * '.$this->db->dbprefix('product_variants').'.qty_unit) AS variant_cost ')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            ->join('warehouses', 'warehouses.id=warehouses_products_variants.warehouse_id', 'left')
            ->group_by(array('' . $this->db->dbprefix('product_variants') . '.id', '' . $this->db->dbprefix('warehouses_products_variants') . '.warehouse_id'))
            ->order_by('product_variants.qty_unit DESC');
        $q = $this->db->get_where('product_variants', array('product_variants.product_id' => $pid, 'warehouses_products_variants.quantity !=' => NULL));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getProductComboItems($pid)
    {
        $this->db->select($this->db->dbprefix('products') . '.id as id, ' . $this->db->dbprefix('products') . '.code as code, ' . $this->db->dbprefix('combo_items') . '.quantity as qty, ' . $this->db->dbprefix('products') . '.name as name, ' . $this->db->dbprefix('combo_items') . '.unit_price as price, ' . $this->db->dbprefix('products') . '.cost as cost')->join('products', 'products.code=combo_items.item_code', 'left')->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }
	public function getProductDigitalItems($pid)
    {
        $this->db->select($this->db->dbprefix('products') . '.id as id, ' . $this->db->dbprefix('products') . '.code as code, ' . $this->db->dbprefix('products') . '.name as name')->join('products', 'products.id=erp_digital_items.product_id', 'left')->group_by('erp_digital_items.id');
        $q = $this->db->get_where('erp_digital_items', array('digital_pro_id' => $pid));
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
		$this->db->select('products.*,units.id as unit_id,units.name as unit');
        $this->db->where('products.id', $id)->join('units', 'products.unit=units.id', 'left');
		$q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function has_purchase($product_id, $warehouse_id = NULL)
    {
        if($warehouse_id) { $this->db->where('warehouse_id', $warehouse_id); }
        $q = $this->db->get_where('purchase_items', array('product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return TRUE;
        }
        return FALSE;
    }

    public function getProductDetails($id)
    {
        $this->db->select($this->db->dbprefix('products') . '.code, ' . $this->db->dbprefix('products') . '.name, ' . $this->db->dbprefix('categories') . '.code as category_code, cost, price, quantity, alert_quantity')
            ->join('categories', 'categories.id=products.category_id', 'left');
        $q = $this->db->get_where('products', array('products.id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductDetail($id) 
	{
	$this->db->select($this->db->dbprefix('products') . '.*, ' . $this->db->dbprefix('tax_rates') . '.code as tax_rate_code, ' . $this->db->dbprefix('categories') . '.name as category_name, ' . $this->db->dbprefix('subcategories') . '.code as subcategory_code, ' . $this->db->dbprefix('subcategories') . '.name as subcategory_name, '.$this->db->dbprefix('units') . '.name as p_unit' )
			->join('tax_rates', 'tax_rates.id=products.tax_rate', 'left')
			->join('categories', 'categories.id=products.category_id', 'left')
			->join('subcategories', 'subcategories.id=products.subcategory_id', 'left')
			->join('units', 'products.unit=units.id', 'left')
			->join('warehouses', 'warehouses.id=products.warehouse', 'left')
			->group_by("products.id")->order_by('products.id desc');
			$q = $this->db->get_where('products', array('products.id' => $id), 1);
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

    public function getAllWarehousesWithPQ($product_id)
    {
		
        $this->db->select('' . $this->db->dbprefix('warehouses') . '.*, ' . $this->db->dbprefix('warehouses_products') . '.quantity, ' . $this->db->dbprefix('warehouses_products') . '.rack')
            ->join('warehouses_products', 'warehouses_products.warehouse_id=warehouses.id', 'left')
            ->where('warehouses_products.product_id', $product_id)
            ->group_by('warehouses.id');
		
		if (!$this->Owner && !$this->Admin && $this->session->userdata('warehouse_id')) {
			$this->db->where_in('warehouses.id', explode(',',$this->session->userdata('warehouse_id')));
		}
		
        $q = $this->db->get('warehouses');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductPhotos($id)
    {
        $q = $this->db->get_where("product_photos", array('product_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }
	
	public function getOptionId($product_id, $name)
    {
		$q = $this->db->get_where('product_variants', array('product_id' => $product_id, 'name'=>$name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getProductVariantByOptionID($option_id)
    {
        $q = $this->db->get_where('product_variants', array('id' => $option_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }
	
    public function getProductByCode($code)
    {
        $code = explode('@', $code);
        $q = $this->db->get_where('products', array('code' => $code[0]), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function addProduct($data, $items, $product_attributes, $photos, $related_products, $items2)
    {
		if ($this->db->insert('products', $data)) {
            $product_id = $this->db->insert_id();

            if ($items) {
                foreach ($items as $item) {
                    $item['product_id'] = $product_id;
                    $this->db->insert('combo_items', $item);
                }
            }

            if ($items2) {
                foreach ($items2 as $item2) {
                    $item2['digital_pro_id'] = $product_id;
                    $this->db->insert('erp_digital_items', $item2);
                }
            }
			
			//================= Add To Warehouse Products ===================//

            $warehouses = $this->site->getAllWarehouses();
            foreach ($warehouses as $warehouse) {
                $this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse->id, 'quantity' => 0));
            }

            if ($product_attributes) {
                foreach ($product_attributes as $pr_attr) {
                    $pr_attr_details = $this->getPrductVariantByPIDandName($product_id, $pr_attr['name']);

                    $pr_attr['product_id'] = $product_id;
                    unset($pr_attr['warehouse_id']);
                    if ($pr_attr_details) {
                        $option_id = $pr_attr_details->id;
                    } else {
                        $this->db->insert('product_variants', $pr_attr);
                        $option_id = $this->db->insert_id();
                    }
                    $warehouses = $this->site->getAllWarehouses();
                    foreach ($warehouses as $warehouse) {
                        $this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse->id, 'quantity' => 0));
                    }
                }
            }

            if ($photos) {
                foreach ($photos as $photo) {
                    $this->db->insert('product_photos', array('product_id' => $product_id, 'photo' => $photo));
                }
            }
			
			if ($related_products) {
				foreach ($related_products as $related_product) {
                    $this->db->insert('related_products', $related_product);
                }
			}
            return $product_id;
        }

        return false;

    }
    public function addProductHistory($data, $items, $product_attributes, $photos, $related_products=NULL, $items2)
    {
        //$this->erp->print_arrays($data, $items, $warehouse_qty, $product_attributes, $photos);
        if ($this->db->insert('products_audit', $data,$items, $warehouse_qty, $product_attributes, $photos)) {
            return true;
        }

        return false;

    }
    public function getPrductVariantByPIDandName($product_id, $name)
    {
        $q = $this->db->get_where('product_variants', array('product_id' => $product_id, 'name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function addAjaxProduct($data)
    {

        if ($this->db->insert('products', $data)) {
            $product_id = $this->db->insert_id();
            return $this->getProductByID($product_id);
        }

        return false;

    }
    
	public function getProduct_variant($id)
	{
		$this->db->select("erp_product_variants.qty_unit")
		->join('erp_product_variants','erp_product_variants.name=erp_variants.name','left');
		$q = $this->db->get_where('erp_variants',array('erp_variants.id'=>$id));
		 if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
    
	public function add_products($products = array())
    {
        if (!empty($products)) {
            foreach ($products as $product) { 
                $variants = explode('|', $product['variants']);
				
                unset($product['variants']);
				$product['track_quantity'] = 0;
                if ($this->db->insert('products', $product)) {
					$product_id = $this->db->insert_id();

                    $warehouses = $this->site->getAllWarehouses();
                    foreach ($warehouses as $warehouse) {
                        $this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse->id, 'quantity' => 0));
                    }

                    foreach ($variants as $variant) {
						$va_seps = explode('=', $variant);
						$price   = explode('_',$variant);
						
						$va_name = $va_seps[0];
						$va_qty_unit = $va_seps[1] ? $va_seps[1] : 1;
						
                        if ($va_name && trim($va_name) != '') {
                            $vat = array('product_id' => $product_id, 'name' => trim($va_name), 'qty_unit' => $va_qty_unit, 'price' => $price[1]);
                            $this->db->insert('product_variants', $vat);
							$option_id = $this->db->insert_id();
							
							$warehouses = $this->site->getAllWarehouses();
							foreach ($warehouses as $warehouse) {
								$this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse->id, 'quantity' => 0));
							}
							
							if(!$this->getVariantName($va_name)){
								$this->db->insert('variants', array('name' => $va_name) );
							}
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }

	public function getVariantName($name)
	{
		$q = $this->db->get_where('variants', array('name' => $name));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
	}
	
	public function getVariantNameById($option_id)
	{
		$q = $this->db->get_where('variants', array('id' => $option_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
	}
    public function getVariantNameByArrayId($option_id)
    {
        $this->db->where_in('id',$option_id);
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
    }
	
    public function getProductNames($term, $warehouse_id, $limit = 100)
    {
		$this->db->select('products.*,  COALESCE(erp_warehouses_products.quantity, 0) as qoh, units.name as unit');
        $this->db->where("type = 'standard' AND (erp_products.name LIKE '%" . $term . "%' OR erp_products.code LIKE '%" . $term . "%' OR  concat(erp_products.name, ' (', erp_products.code, ')') LIKE '%" . $term . "%')");
		if($warehouse_id){
			$this->db->where("warehouses_products.warehouse_id", $warehouse_id);
		}
		$this->db->group_by('products.id');
        $this->db->limit($limit);
		$this->db->join('warehouses_products', 'warehouses_products.product_id = products.id', 'left');
		$this->db->join('units', 'products.unit = units.id', 'left');
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getUsingStockProducts($term, $warehouse_id, $plan = NULL, $address = NULL, $limit = 100)
    {
		$project_plan_qty = 0;
        $this->db->where("type = 'standard' AND (erp_products.name LIKE '%" . $term . "%' OR erp_products.code LIKE '%" . $term . "%' OR  concat(erp_products.name, ' (', erp_products.code, ')') LIKE '%" . $term . "%')");
		if ($plan) {
			$project_plan_qty = "(
				SELECT 
					product_id, 
					COALESCE(quantity_balance, 0) as project_qty 
				FROM 
					erp_project_plan_items 
				WHERE 
					erp_project_plan_items.project_plan_id = $plan
				AND	erp_project_plan_items.product_code LIKE '%$term%' 
			) project_plan";
			
			$using_qty = "(
				SELECT
					code,
					COALESCE(SUM(qty_use), 0) as using_qty
				FROM
					erp_enter_using_stock_items 
				LEFT JOIN erp_enter_using_stock ON erp_enter_using_stock_items.reference_no = erp_enter_using_stock.reference_no 
				WHERE 
					erp_enter_using_stock.plan_id = $plan 
				AND erp_enter_using_stock.address_id = $address 
				AND erp_enter_using_stock_items.code LIKE '%$term%' 
				AND erp_enter_using_stock.type = 'use'
			) using_stock";
			
			$return_using_qty = "(
				SELECT
					code,
					COALESCE(SUM(qty_use), 0) as using_qty
				FROM
					erp_enter_using_stock_items 
				LEFT JOIN erp_enter_using_stock ON erp_enter_using_stock_items.reference_no = erp_enter_using_stock.reference_no 
				WHERE 
					erp_enter_using_stock.plan_id = $plan 
				AND erp_enter_using_stock.address_id = $address 
				AND erp_enter_using_stock_items.code LIKE '%$term%' 
				AND erp_enter_using_stock.type = 'return'
			) return_using_stock";
		}
		$this->db->where("warehouses_products.warehouse_id", $warehouse_id);
        $this->db->limit($limit);
		if ($plan) {
			$this->db->select('products.*, warehouses_products.quantity as qoh, units.name as unit_name, (COALESCE(project_plan.project_qty,0) - COALESCE(using_stock.using_qty, 0) + COALESCE(return_using_stock.using_qty, 0) ) as project_qty, project_plan.product_id as in_plan');
		} else {
			$this->db->select('products.*, warehouses_products.quantity as qoh, units.name as unit_name');
		}
		
		$this->db->join('warehouses_products', 'warehouses_products.product_id = products.id', 'left');
		$this->db->join('units', 'units.id = products.unit', 'left');
		if ($plan) {
			$this->db->join($project_plan_qty, 'project_plan.product_id = products.id', 'left');
			$this->db->join($using_qty, 'using_stock.code = products.code', 'left');
			$this->db->join($return_using_qty, 'return_using_stock.code = products.code', 'left');
		}
        $this->db->group_by('products.id');
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getUnitAndVaraintByProductId($id)
	{
		$variant = $this->db->select("products.*, '1' as measure_qty, product_variants.name as unit_variant, product_variants.qty_unit as qty_unit ")
							->from("products")
							->where("products.id",$id)
							->join("product_variants","products.id=product_variants.product_id","left")
							->get();					
		$unit_of_measure = $this->getUnitOfMeasureByProductId($id);
		if($variant->num_rows() > 0 && $variant->row()->unit_variant != null){
			return $variant->result();
		}else{
			return $unit_of_measure;
		}			
	}
	
	public function getProjectPlanItem($plan_id, $product_id)
	{
		$q = $this->db->get_where('project_plan_items', array('project_plan_id' => $plan_id, 'product_id' => $product_id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
	}
	
	public function getPlanUsing($plan_id, $product_code, $address)
	{
		
		$using_qty = "(
				SELECT
					code,
					COALESCE(SUM(qty_use), 0) as using_qty
				FROM
					erp_enter_using_stock_items 
				LEFT JOIN erp_enter_using_stock ON erp_enter_using_stock_items.reference_no = erp_enter_using_stock.reference_no 
				WHERE 
					erp_enter_using_stock.plan_id = $plan_id 
				AND erp_enter_using_stock.address_id = $address  
				AND erp_enter_using_stock_items.code = '$product_code'
				AND erp_enter_using_stock.type = 'use'
			) using_stock";
			
		$return_using_qty = "(
				SELECT
					code,
					COALESCE(SUM(qty_use), 0) as reutn_using_qty
				FROM
					erp_enter_using_stock_items 
				LEFT JOIN erp_enter_using_stock ON erp_enter_using_stock_items.reference_no = erp_enter_using_stock.reference_no 
				WHERE 
					erp_enter_using_stock.plan_id = $plan_id 
				AND erp_enter_using_stock.address_id = $address  
				AND erp_enter_using_stock_items.code = '$product_code'
				AND erp_enter_using_stock.type = 'return'
			) return_using_stock";
			
		$this->db->select('project_plan_items.*, using_stock.using_qty, return_using_stock.reutn_using_qty')
				 ->from('project_plan_items')
				 ->join($using_qty, 'using_stock.code = project_plan_items.product_code', 'left')
				 ->join($return_using_qty, 'using_stock.code = project_plan_items.product_code', 'left')
				 ->where(array('project_plan_items.project_plan_id' => $plan_id, 'project_plan_items.product_code' => $product_code));
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getProductNumber($term, $warehouse_id, $limit = 100)
    {
		if(preg_match('/\s/', $term))
		{
			$name = explode(" ", $term);
			$first = $name[0];
			$this->db->select('products.*,  COALESCE(erp_warehouses_products.quantity, 0) as qoh')->group_by('products.id');
			$this->db->join('warehouses_products', 'warehouses_products.product_id = products.id', 'left');
			$this->db->where(array('code'=> $first, 'warehouses_products.warehouse_id'=>$warehouse_id));
			$this->db->limit($limit);
			$q = $this->db->get('products');
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			}
		}else
		{		
			$this->db->select('products.*,  COALESCE(erp_warehouses_products.quantity, 0) as qoh')->group_by('products.id');
			$this->db->where("type = 'standard' AND (code LIKE '%" . $term . "%')");
			$this->db->where("warehouses_products.warehouse_id", $warehouse_id);
			$this->db->limit($limit);
			$this->db->join('warehouses_products', 'warehouses_products.product_id = products.id', 'left');
			$q = $this->db->get('products');
			if ($q->num_rows() > 0) {
				foreach (($q->result()) as $row) {
					$data[] = $row;
				}
				return $data;
			}
		}
	}
	
	public function getProductCode($term)
    {
        $this->db->select('code');
		$q = $this->db->get_where('products', array('code' => $term), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
    }

    public function updateProduct($id, $data, $items, $product_attributes, $photos, $update_variants, $related_products=NULL,$items2)
    {
		if ($this->db->update('products', $data, array('id' => $id))) {

            if ($items) {
                $this->db->delete('combo_items', array('product_id' => $id));
                foreach ($items as $item) {
                    $item['product_id'] = $id;
                    $this->db->insert('combo_items', $item);
                }
            }
			if ($items2) {
                $this->db->delete('erp_digital_items', array('digital_pro_id' => $id));
                foreach ($items2 as $item2) {
                    $item2['digital_pro_id'] = $id;
                    $this->db->insert('erp_digital_items', $item2);
                }
            }
			//============== Add To Warehouse ================//
			$warehouses = $this->site->getAllWarehouses();
			foreach ($warehouses as $warehouse) {
				$this->db->update('warehouses_products', array('quantity' => 0), array('product_id' => $id, 'warehouse_id' => $warehouse->id));
			}

            if ($update_variants) {
                $this->db->update_batch('product_variants', $update_variants, 'id');
            }

            if ($photos) {
                foreach ($photos as $photo) {
                    $this->db->insert('product_photos', array('product_id' => $id, 'photo' => $photo));
                }
            }

            if ($product_attributes) {
                foreach ($product_attributes as $pr_attr) {
					$pr_attr['product_id'] 	= $id;
                    $this->db->insert('product_variants', $pr_attr);
                    $option_id = $this->db->insert_id();
					
					$warehouses = $this->site->getAllWarehouses();
					foreach ($warehouses as $warehouse) {
						$this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $id, 'warehouse_id' => $warehouse->id, 'quantity' => 0));
					}
                }
            }
			
			if ($related_products) {
				foreach ($related_products as $related_product) {
                    $this->db->insert('related_products', $related_product);
                }
			}

            $this->site->syncQuantity(NULL, NULL, NULL, $id);
            return true;
        } else {
            return false;
        }
    }

    public function updateProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            if ($this->db->update('warehouses_products_variants', array('quantity' => $quantity), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        } else {
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getPurchasedItemDetails($product_id, $warehouse_id, $option_id = NULL)
    {
        $q = $this->db->get_where('purchase_items', array('product_id' => $product_id, 'option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPurchasedItemDetailsWithOption($product_id, $warehouse_id, $option_id)
    {
        $q = $this->db->get_where('purchase_items', array('product_id' => $product_id, 'purchase_id' => NULL, 'transfer_id' => NULL, 'warehouse_id' => $warehouse_id, 'option_id' => $option_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getProductOptionsByVarName($id, $var_name = NULL)
    {
        $q = $this->db->get_where('product_variants', array('product_id' => $id, 'name' => $var_name));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
    public function updatePrice($data = array())
    {
		
		foreach($data as $item){
			$product = $this->products_model->getProductByCode(trim($item['code']));
			$update = array(
				'price' => $item['price'] >= 0 ? $item['price'] : $product->price,
				'cost'	=> $item['cost'] >= 0 ? $item['cost'] : $product->cost
			);
			
			if ($item['variant'] != "" || $item['variant'] != NULL ) {
				$variants = explode('|', $item['variant']);
				foreach ($variants as $variant) {
					$va_seps = explode('=', $variant);
					$varaint_item = $this->getProductOptionsByVarName($item['id'], $va_seps[0]);
					if($varaint_item){
						$this->db->update('product_variants', array('price' => $va_seps[1]), array('id' => $varaint_item->id));
					}					
				}
			}
			unset($item['variant']);
			$this->db->update('products', $update, array('id' => $item['id']));
			$variant = $this->getProductVariantByProId($item['id']);
			foreach($variant as $var){
				if($item['cost']){
					$var_cost = $item['cost'] * $var->qty_unit;
					$this->db->update('product_variants', array('cost' => $var_cost), array('id' => $var->id));
				}
			}
		}
    }
	
	public function getProductVariantByProId($id)
	{
		$q = $this->db->get_where('product_variants', array('product_id' => $id));
		if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function updateQuantityExcel($data = array())
    {
		foreach($data as $item){
			$product = $this->products_model->getProductByCode(trim($item['code']));
			$update = array(
				'cost'	=> $item['cost'] >= 0 ? $item['cost'] : $product->cost
			);
			$this->db->update('products', $update, array('code' => $item['code']));
		}
		return true;
    }
	
	public function updateCostVariant($data = array())
    {
		foreach($data as $item){
			$variant = $this->getProductVariantByOptionID(trim($item['option_id']));
			$update = array(
				'cost'	=> $item['cost'] >= 0 ? $item['cost'] : $variant->cost
			);
			$this->db->update('product_variants', $update, array('id' => $item['option_id']));
		}
		return true;
    }
	
	public function updateQuantityExcelWarehouse($data = array())
    {
		foreach($data as $value){
			$que_data = array('quantity' => $value['quantity']);
			$where = array(
				'product_id'=>$value['product_id'],
				'warehouse_id'=>$value['warehouse_id']
			);
			$this->db->update('warehouses_products', $que_data, $where);
		}
		return true;
    }
	
	public function updateQuantityExcelVar($data = array())
    {
		foreach($data as $value){
			$que_data = array(
				'quantity'  => $value['quantity']
			);
			$where = array(
				'product_id'	=>$value['product_id'],
				'warehouse_id'	=>$value['warehouse_id'],
				'option_id'		=>$value['option_id']
			);
			$this->db->update('warehouses_products_variants', $que_data, $where);
		}
		return true;
    }
	
	public function updateQuantityExcelPurchase($data = array())
    {
		foreach($data as $value){
			$this->db->select('*');
			$this->db->from('products');
			$this->db->where(array('id'=>$value['product_id']));
			$prod			= $this->db->get();
            $expiry_date 	= $this->erp->fsd($value['expiry']);//date('Y-m-d', strtotime($expiry));
			$pur_data = array(
				'product_id'		=> $value['product_id'],
				'product_code'		=> $prod->row()->code,
				'product_name'		=> $prod->row()->name,
				'warehouse_id'		=> $value['warehouse_id'],
				'option_id'			=> $value['option_id'],
				'quantity'			=> $value['quantity_balance'],
				'opening_stock'		=> $value['opening_stock'],
				'quantity_balance'	=> $value['quantity_balance'],
				'quantity_received'	=> $value['quantity_balance'],
				'product_type'		=> $value['product_type'],
				'net_unit_cost'	 	=> $value['cost'],
				'unit_cost' 		=> $value['cost'],
				'real_unit_cost' 	=> $value['cost'],
				'subtotal' 			=> $value['cost'] * $value['quantity_balance'],
				'status' 			=> 'received',
				'date' 				=> date('Y-m-d'),
				'expiry' 			=> $expiry_date,
				'serial_no'			=> $value['serial']
			);
			
			if(isset($value['transaction_type'])){
				$pur_data['transaction_type'] = $value['transaction_type'];
			}
			$this->db->insert('purchase_items',$pur_data);

            //$this->site->syncQuantity(NULL, NULL, NULL, $value['product_id']);
		}
		return true;
    }
	
	public function insertGlTran($total_cost)
	{
		$v_tran_no = $this->db->select('(COALESCE (MAX(tran_no), 0) + 1) as tran')->from('gl_trans')->get()->row()->tran;
		$v_reference = $this->db->select('COUNT(*) as trans')->from('purchase_items')->where('option_id', 3)->get()->row()->trans;
		$tran = $this->getTrans('default_purchase');
		$dob = $this->getTrans('default_open_balance');
		$data = array(
			array(
				'tran_type'    => 'JOURNAL',
				'tran_no'      => $v_tran_no,
				'tran_date'    => date('Y-m-d h:i:s'),
				'sectionid'    => $tran->sectionid,
				'account_code' => $tran->accountcode,
				'narrative'    => $tran->accountname,
				'amount'       => $total_cost,
				'reference_no' => '000'.$v_reference,
				'description'  => 'Import Quantity',
				'biller_id'    => $this->Settings->default_biller,
				'created_by'   => $this->session->userdata('user_id')
			),
			array(
				'tran_type'    => 'JOURNAL',
				'tran_no'      => $v_tran_no,
				'tran_date'    => date('Y-m-d h:i:s'),
				'sectionid'    => $dob->sectionid,
				'account_code' => $dob->accountcode,
				'narrative'    => $dob->accountname,
				'amount'       => (-1) * $total_cost,
				'reference_no' => '000'.$v_reference,
				'description'  => 'Import Quantity',
				'biller_id'    => $this->Settings->default_biller,
				'created_by'   => $this->session->userdata('user_id')
			)
		);
		$this->db->insert_batch('gl_trans',$data);
	}
	
	public function getTrans($type)
	{
		$this->db->select('erp_gl_sections.sectionid, erp_gl_charts.accountcode, erp_gl_charts.accountname')
				 ->from('erp_account_settings')
				 ->join('erp_gl_charts', 'erp_gl_charts.accountcode = erp_account_settings.'.$type , 'INNER')
				 ->join('erp_gl_sections', 'erp_gl_sections.sectionid = erp_gl_charts.sectionid' , 'INNER')
				 ->where('erp_gl_charts.accountcode = erp_account_settings.'.$type);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
	}
	
	public function getProductfordelete($id)
	{
		 $this->db->select('erp_products.id')
		          ->join('erp_sale_items', 'erp_sale_items.product_id=erp_products.id', 'left')
				  ->join('erp_purchase_items', 'erp_purchase_items.product_id=erp_products.id', 'left')
		          ->from('erp_products')
				  ->where('erp_sale_items.product_id = "'.$id.'" ')
				  ->or_where('erp_purchase_items.product_id ="'.$id.'"');
			$q=$this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
	}
    
	public function deleteProduct($id)
    {
        if ($this->db->delete('products', array('id' => $id)) && $this->db->delete('warehouses_products', array('product_id' => $id)) && $this->db->delete('warehouses_products_variants', array('product_id' => $id))) {
            return true;
        }
        return FALSE;
    }


    public function totalCategoryProducts($category_id)
    {
        $q = $this->db->get_where('products', array('category_id' => $category_id));

        return $q->num_rows();
    }

    public function getSubcategoryByID($id)
    {
        $q = $this->db->get_where('subcategories', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getCategoryByCode($code)
    {
        $q = $this->db->get_where('categories', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getSubcategoryByCode($code)
    {

        $q = $this->db->get_where('subcategories', array('code' => $code), 1);
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
	
	public function getCategoriesForBrandID($brand_id)
    {
        $this->db->select('id as id, name as text');
        $q = $this->db->get_where("categories", array('brand_id' => $brand_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getSubCategoriesForCategoryID($category_id)
    {
        $this->db->select('id as id, name as text');
        $q = $this->db->get_where("subcategories", array('category_id' => $category_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getSubCategoriesByCategoryID($category_id)
    {
        $q = $this->db->get_where("subcategories", array('category_id' => $category_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getAdjustmentByID($id)
    {
        $q = $this->db->get_where('adjustments', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function syncAdjustment($data = array())
    {
        if(! empty($data)) {
			$pr = $this->site->getProductByID($data['product_id']);
			$qty_balance = $data['type'] == 'subtraction' ? (0 - $data['quantity']) : $data['quantity'];
			if($data['option_id']){
				$option = $this->site->getProductVariantOptionIDPID($data['option_id'], $data['product_id']);
				$qty_balance = $qty_balance * $option->qty_unit;
			}
			
			$item = array(
				'product_id' 		=> $data['product_id'],
				'product_code' 		=> $pr->code,
				'product_name' 		=> $pr->name,
				'net_unit_cost' 	=> 0,
				'unit_cost' 		=> 0,
				'quantity' 			=> 0,
				'option_id' 		=> $data['option_id'],
				'quantity_balance' 	=> $qty_balance ,
				'item_tax' 			=> 0,
				'tax_rate_id' 		=> 0,
				'tax' 				=> '',
				'subtotal' 			=> 0,
				'warehouse_id' 		=> $data['warehouse_id'],
				'date' 				=> date('Y-m-d'),
				'status' 			=> 'received',
			);
			$this->db->insert('purchase_items', $item);

            $this->site->syncProductQty($data['product_id'], $data['warehouse_id']);
            if ($data['option_id']) {
                $this->site->syncVariantQty($data['option_id'], $data['warehouse_id'], $data['product_id']);
            }
        }
    }

    public function reverseAdjustment($id)
    {
        if ($adjustment = $this->getAdjustmentByID($id)) {

            if ($purchase_item = $this->getPurchasedItemDetails($adjustment->product_id, $adjustment->warehouse_id, $adjustment->option_id)) {
                $quantity_balance = $adjustment->type == 'subtraction' ? $purchase_item->quantity_balance + $adjustment->quantity : $purchase_item->quantity_balance - $adjustment->quantity;
                $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), array('id' => $purchase_item->id));
            }

            $this->site->syncProductQty($adjustment->product_id, $adjustment->warehouse_id);
            if ($adjustment->option_id) {
                $this->site->syncVariantQty($adjustment->option_id, $adjustment->warehouse_id, $adjustment->product_id);
            }
        }
    }
	
	public function getAdjustment($id)
	{
        $this->db
            ->select('adjustments.id as id,
                adjustments.date,
                adjustments.reference_no,
                warehouses.`name` as wh_name,users.first_name,
                users.last_name,
                adjustments.note,
                adjustments.attachment', FALSE)
            ->join('warehouses', 'warehouses.id = adjustments.warehouse_id', 'left')
            ->join('users', 'users.id = adjustments.created_by', 'left')
            ->where('adjustments.id', $id)
            ->group_by('adjustments.id');
        $q = $this->db->get('adjustments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getAdjustmentList($id)
	{
        $this->db
            ->select('adjustment_items.*, adjustments.note, products.code,products.name,product_variants.name as variants')
			->join('products', 'products.id = adjustment_items.product_id','left' )
			->join('product_variants', 'product_variants.product_id = products.id 
				AND product_variants.id = adjustment_items.option_id','left' )
            ->join('adjustments', 'adjustment_items.adjust_id = adjustments.id', 'left')
			->where('adjustment_items.adjust_id', $id)
            ->group_by('adjustment_items.id');
        $query = $this->db->get('adjustment_items');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
	
	public function addAdjustment($data, $dataPurchase = NULL)
    {
		$p 			= $this->getProductByID($data['product_id']);
		$cost 		= $p->cost;
		$total_cost = 0;
		if($data['option_id']){
			$option = $this->getProductVariantByOptionID($data['option_id']);
			$total_cost = $cost * ($data['quantity'] * $option->qty_unit);
		}else{
			$total_cost = $cost * $data['quantity'];
		}
		
		$data['cost'] 		= $cost;
		$data['total_cost'] = $total_cost;
        if ($this->db->insert('adjustments', $data)) {
			$insert_id = $this->db->insert_id();
			$dataPurchase['transaction_id'] = $insert_id;
			$this->db->insert('purchase_items', $dataPurchase);			
			
            $this->site->syncQuantitys(null, null, null, $data['product_id']);
            return true;
        }
        return false;
    }
	
	public function addMultiAdjustment($data, $dataPurchase = NULL)
    {
        
		if ($this->db->insert('adjustments', $data)) {
            $adjustment_id = $this->db->insert_id();
			$quantity_balance = 0;
			foreach($dataPurchase as $products){
				$products['adjust_id'] 	= $adjustment_id;
				$products['date'] 		= $data['date'];
				$quantity_balance 		= $products['quantity_balance'];
				$expiry 		  		= $products['expiry'];
				$product_code 		  	= $products['product_code'];
				$product_name 		  	= $products['product_name'];
				$product_type		  	= $products['product_type'];
				unset($products['quantity_balance']);
				unset($products['product_code']);
				unset($products['product_name']);
				unset($products['product_type']);
                $this->db->insert('adjustment_items', $products);
				$adjust_item_id 		= $this->db->insert_id();

				$product_cost = $products['cost'];
				unset($products['adjust_id']);
				unset($products['cost']);
				unset($products['total_cost']);
				unset($products['type']);
				unset($products['biller_id']);
				$products['quantity_balance'] 	= $quantity_balance;
				$products['expiry'] 			= $expiry;
				$products['transaction_id'] 	= $adjust_item_id;
				$products['product_code'] 		= $product_code;
				$products['product_name'] 		= $product_name;
				$products['product_type'] 		= $product_type;
				$products['real_unit_cost'] 	= $product_cost;
				$products['transaction_type'] 	= 'ADJUSTMENT';
				$products['status'] 			= 'received';
				$products['reference'] 			= $data['reference_no'];

				$this->db->insert('purchase_items', $products);
			}

			if ($this->site->getReference('qa',$data['biller_id']) == $data['reference_no']) {
                $this->site->updateReference('qa',$data['biller_id']);
            }

			foreach($dataPurchase as $products){
				$this->site->syncQuantitys(null, null, null, $products['product_id']);
			}
			return true;
        }
        return false;
    }
	
    public function updateAdjustment($id, $data)
    {
		$p = $this->getProductByID($data['product_id']);
		$cost = $p->cost;
		$total_cost = 0;
		if($data['option_id']){
			$option = $this->getProductVariantByOptionID($data['option_id']);
			$total_cost = $cost * ($data['quantity'] * $option->qty_unit);
		}else{
			$total_cost = $cost * $data['quantity'];
		}
		
		$data['cost'] = $cost;
		$data['total_cost'] = $total_cost;
        $this->reverseAdjustment($id);
        if ($this->db->update('adjustments', $data, array('id' => $id))) {
            $this->syncAdjustment($data);
            return true;
        }
        return false;
    }

    public function deleteAdjustment($id)
    {
        $this->reverseAdjustment($id);
        if ( $this->db->delete('adjustments', array('id' => $id))) {
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

    public function addQuantity($product_id, $warehouse_id, $quantity, $rack = NULL)
    {

        if ($this->getProductQuantity($product_id, $warehouse_id)) {
            if ($this->updateQuantity($product_id, $warehouse_id, $quantity, $rack)) {
                return TRUE;
            }
        } else {
            if ($this->insertQuantity($product_id, $warehouse_id, $quantity, $rack)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function insertQuantity($product_id, $warehouse_id, $quantity, $rack = NULL)
    {
        if ($this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity, 'rack' => $rack))) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function updateQuantity($product_id, $warehouse_id, $quantity, $rack = NULL)
    {
        $data = $rack ? array('quantity' => $quantity, 'rack' => $rack) : $data = array('quantity' => $quantity);
        if ($this->db->update('warehouses_products', $data, array('product_id' => $product_id, 'warehouse_id' => $warehouse_id))) {
            $this->site->syncProductQty($product_id, $warehouse_id);
            return true;
        }
        return false;
    }

    public function products_count($category_id, $subcategory_id = NULL)
    {
        if ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        $this->db->from('products');
        return $this->db->count_all_results();
    }

    public function fetch_products($category_id, $limit, $start, $subcategory_id = NULL)
    {

        $this->db->limit($limit, $start);
        if ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        $this->db->order_by("id", "asc");
        $query = $this->db->get("products");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
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
	
    public function syncVariantQty($option_id)
    {
        $wh_pr_vars = $this->getProductWarehouseOptions($option_id);
        $qty = 0;
        foreach ($wh_pr_vars as $row) {
            $qty += $row->quantity;
        }
        if ($this->db->update('product_variants', array('quantity' => $qty), array('id' => $option_id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function getProductWarehouseOptions($option_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function setRack($data)
    {
        if ($this->db->update('warehouses_products', array('rack' => $data['rack']), array('product_id' => $data['product_id'], 'warehouse_id' => $data['warehouse_id']))) {
            return TRUE;
        }
        return FALSE;
    }

    public function getSoldQty($id)
    {
        $this->db->select("date_format(" . $this->db->dbprefix('sales') . ".date, '%Y-%M') month, SUM( " . $this->db->dbprefix('sale_items') . ".quantity ) as sold, SUM( " . $this->db->dbprefix('sale_items') . ".subtotal ) as amount")
            ->from('sales')
            ->join('sale_items', 'sales.id=sale_items.sale_id', 'left')
            ->group_by("date_format(" . $this->db->dbprefix('sales') . ".date, '%Y-%m')")
            ->where($this->db->dbprefix('sale_items') . '.product_id', $id)
            //->where('DATE(NOW()) - INTERVAL 1 MONTH')
            ->where('DATE_ADD(curdate(), INTERVAL 1 MONTH)')
            ->order_by("date_format(" . $this->db->dbprefix('sales') . ".date, '%Y-%m') desc")->limit(3);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getPurchasedQty($id)
    {
        $this->db->select("date_format(" . $this->db->dbprefix('purchases') . ".date, '%Y-%M') month, SUM( " . $this->db->dbprefix('purchase_items') . ".quantity ) as purchased, SUM( " . $this->db->dbprefix('purchase_items') . ".subtotal ) as amount")
            ->from('purchases')
            ->join('purchase_items', 'purchases.id=purchase_items.purchase_id', 'left')
            ->group_by("date_format(" . $this->db->dbprefix('purchases') . ".date, '%Y-%m')")
            ->where($this->db->dbprefix('purchase_items') . '.product_id', $id)
            //->where('DATE(NOW()) - INTERVAL 1 MONTH')
            ->where('DATE_ADD(curdate(), INTERVAL 1 MONTH)')
            ->order_by("date_format(" . $this->db->dbprefix('purchases') . ".date, '%Y-%m') desc")->limit(3);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllVariants()
    {
        $q = $this->db->get('variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function getProjects($id = null)
	{
		$q = $this->db->get('variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	
    public function getProductsForPrinting($term, $limit = 100)
    {
        $this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	public function getConvertItemsById($convert_id)
	{
		$this->db->select('convert_items.product_id, convert_items.convert_id, convert_items.quantity AS c_quantity , (erp_products.cost * erp_convert_items.quantity) AS tcost, convert_items.status, products.cost AS p_cost, (erp_products.price * erp_convert_items.quantity) as tprice, convert_items.option_id');
		$this->db->join('products', 'products.id = convert_items.product_id', 'INNER');
		$this->db->where('convert_items.convert_id', $convert_id);
		$query = $this->db->get('convert_items');
		
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
	public function getConvertItemsByIDPID($convert_id, $product_id = NULL)
	{
		if($product_id){
			$this->db->where('product_id', $product_id);
		}
		$this->db->where('convert_id', $convert_id);
		$query = $this->db->get('convert_items');
		if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
	}
	
	public function getConvertItemsAdd($convert_id)
	{
		$this->db->select('convert_items.product_id, convert_items.convert_id, convert_items.quantity AS c_quantity , (erp_products.cost * erp_convert_items.quantity) AS tcost, convert_items.status, (erp_products.price * erp_convert_items.quantity) as tprice, product_variants.qty_unit, convert_items.option_id');
		$this->db->join('products', 'products.id = convert_items.product_id', 'INNER');
		$this->db->join('product_variants', 'product_variants.id = convert_items.option_id', 'left');
		$this->db->where('convert_items.convert_id', $convert_id);
		$this->db->where('convert_items.status', 'add');
		$query = $this->db->get('convert_items');
		
		if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
	}
	
	public function getConvertItemsDeduct($convert_id)
	{
		$this->db->select('SUM(erp_products.cost * erp_convert_items.quantity) AS tcost, convert_items.status');
		$this->db->join('products', 'products.id = convert_items.product_id', 'INNER');
		$this->db->where('convert_items.convert_id', $convert_id);
		$this->db->where('convert_items.status', 'deduct');
		$query = $this->db->get('convert_items');
		
		if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
	}
	
	public function getConvertItemsId($convert_id)
	{
		$q = $this->db->get_where('convert_items', array('convert_id' => $convert_id) );
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getAllBoms()
	{
		$q = $this->db->get('bom');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function getAllBom_id($id, $ware_id)
	{
		$this->db->select('bom.*, bom_items.*, (SELECT COALESCE(quantity , 0) as qoh FROM erp_warehouses_products WHERE warehouse_id = '.$ware_id.' AND erp_warehouses_products.product_id = erp_bom_items.product_id) as qoh, units.name as unit')
				 ->join('bom_items', 'bom.id = bom_items.bom_id', 'left')
				 ->join('products', 'bom_items.product_id = products.id', 'left')
				 ->join('units', 'products.unit = units.id', 'left')
				 ->where(array('bom.id'=>$id));
		$q = $this->db->get('bom');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}

	public function getReference(){
		$this->db->select('reference_no')
				 ->order_by('date', 'desc')
				 ->limit(1);
		$q = $this->db->get('convert');
        if ($q->num_rows() > 0) {
			foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getAllCurrencies()
    {
        $q = $this->db->get('currencies');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
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
	
	public function getWarehouseQty($product_id, $warehouse_id)
	{
        $this->db->select('SUM(quantity) as quantity')
                 ->from('warehouses_products')
                 ->where(array('product_id'=>$product_id, 'warehouse_id'=>$warehouse_id));
        $q = $this->db->get();
        if($q->num_rows() > 0){
            return $q->row();
        }
        return false;
    }
	
	public function getUnits()
	{
		$this->db->select()
				 ->from('units');
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}
	
	public function getStrapByProductID($code = NULL) 
	{
		$q = $this->db->get_where('related_products', array('product_code' => $code));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
	}
	
	public function getProductName_code($w_id=null)
    {	
		
		$this->db->where('warehouses_products.warehouse_id',$w_id);
		$this->db->select('concat(name," ( ",code," ) ") as label,code as value,erp_warehouses_products.quantity as quantity,products.cost as cost,erp_warehouses_products.quantity as qqh');
        $this->db->from('products');
		$this->db->join('warehouses_products' ,'warehouses_products.product_id=products.id', 'left');
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
	
	public function getGLChart()
	{
		$this->db->select()
				 ->from('gl_charts');
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}
	
	public function getUnitOfMeasureByProductCode($code,$unit_desc=null)
	{
		if($unit_desc!=null){
			$this->db->where('units.name',$unit_desc);
		}
		$this->db->where('products.code',$code);
		$this->db->select('products.*,units.name as description, "1" as measure_qty');
		$this->db->from('products');
		$this->db->join('units','products.unit=units.id','left');
		$q=$this->db->get();
		if($q){
			if($unit_desc!=null){
				return $q->row();
			}else{
				return $q->result();
			}
			
		}
		return false;
	}
	
	public function getUnitOfMeasureByProductId($id,$unit_desc=null)
	{
		if($unit_desc!=null){
			$this->db->where('units.name',$unit_desc);
		}
		$this->db->where('products.id',$id);
		$this->db->select('products.*,units.name as unit_variant, "1" as measure_qty');
		$this->db->from('products');
		$this->db->join('units','products.unit=units.id','left');
		$q=$this->db->get();
		if($q){
			if($unit_desc!=null){
				return $q->row();
			}else{
				return $q->result();
			}
			
		}
		return false;
	}
	
	public function insert_enter_using_stock($data, $items)
	{
		if($this->db->insert('enter_using_stock', $data)){
					
            if ($this->site->getReference('es', $data['shop']) == $data['reference_no']) {
                $this->site->updateReference('es', $data['shop']);
            }
			foreach($items as $item){
				$product_id 	= $item['product_id'];
				$product_name 	= $item['product_name'];
				unset($item['product_name']);
				$this->db->insert('enter_using_stock_items', $item);
				$using_stock_item = $this->db->insert_id();	
				
				$pur_data = array(
					'product_id' 		=> $product_id,
					'product_code' 		=> $item['code'],
					'product_name' 		=> $product_name,
					'net_unit_cost' 	=> $item['cost'],
					'option_id' 		=> $item['option_id'],
					'quantity' 			=> -1 * abs($item['qty_use']),
					'reference'			=> $item['reference_no'],
					'warehouse_id' 		=> $item['warehouse_id'],
					'expiry' 			=> $item['expiry'],
					'date' 				=> $data['date'],
					'status' 			=> 'received',
					'quantity_balance' 	=> -1 * abs($item['qty_use']),
					'transaction_type' 	=> 'USING STOCK',
					'transaction_id' 	=> $using_stock_item,
				);
				$qty_use = $item['qty_use'];
			   
				$this->db->insert('purchase_items', $pur_data);
				
				$pro_item = $this->getProjectPlanItem($data['plan_id'], $product_id);
				$new_qty_use = $pro_item->quantity_used + $qty_use;
				
				$this->db->update("project_plan_items", array("project_plan_items.quantity_used" => $new_qty_use), array("project_plan_id" => $data['plan_id'], "product_id" => $product_id ));

				$this->site->syncProductQty($product_id, $item['warehouse_id']);
				
			}
			
			return true;
		}else{
			return false;
		}
	}
	
	public function insert_enter_using_stock_item($data)
	{
		if($data) {
			$i=$this->db->insert('enter_using_stock_items', $data);
			if($i){
				return $this->db->insert_id();
			}
		}
		return false;
	}
	
	public function getProductQtyByCode($product_code)
    {	
		$this->db->where('code',$product_code);
		$this->db->select('*');
        $this->db->from('products');
		 $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function get_enter_using_stock_by_ref($r_r)
	{
        $ref = str_replace('_', '&', $r_r);
		$this->db->select('enter_using_stock.*,warehouses.name,users.first_name,users.last_name, project_plan.plan, concat(erp_products.cf3, " ", erp_products.cf4) as address');
		$this->db->from('enter_using_stock');
		$this->db->join('warehouses','warehouses.id=enter_using_stock.warehouse_id','left');
		$this->db->join('project_plan','project_plan.id=enter_using_stock.plan_id','left');
		$this->db->join('products','products.id = enter_using_stock.address_id','left');
		$this->db->join('users','users.id=enter_using_stock.employee_id','left');
		$this->db->where('enter_using_stock.reference_no',$ref);
		$q=$this->db->get();
		if($q){
			return $q->row();
		}else{
			return false;
		}
	}	
	
	public function get_enter_using_stock_item_by_ref($r_r)
	{
        $ref = str_replace('_', '&', $r_r);
        $this->db->select('
		    enter_using_stock_items.*,
		    products.name as product_name,
		    expense_categories.name as exp_cate_name,
		    IF(erp_enter_using_stock_items.option_id, erp_product_variants.name, erp_enter_using_stock_items.unit) as unit_name,
		    products.cost,
		    position.name as pname,
		    reasons.description as rdescription,
		    product_variants.qty_unit as variant_qty,
		    product_variants.name as variant,
		    units.name as unit
		    ');
		$this->db->from('enter_using_stock_items');
		$this->db->join('products','products.code=enter_using_stock_items.code','left');
		$this->db->join('position','enter_using_stock_items.description = position.id','left');
		$this->db->join('reasons','enter_using_stock_items.reason = reasons.id','left');
		$this->db->join('product_variants','enter_using_stock_items.option_id = product_variants.id','left');
        $this->db->join('units', 'units.id = products.unit', 'left');
		$this->db->join('expense_categories','enter_using_stock_items.exp_cate_id = expense_categories.id','left');
		$this->db->where('enter_using_stock_items.reference_no',$ref);
		
		$q=$this->db->get();
		if($q){
			return $q->result();
		}else{
			return false;
		}
	}
	
	function getReferno()
	{
		$q=$this->db->get('enter_using_stock');
		return $q->result();
	}
	
	function getEmpno()
	{
		$q=$this->db->get('erp_users');
		return $q->result();
	}
	
	function datatable_using_stock()
    {
        $this->load->library('datatables');
		
		$fdate=$this->input->get('fdate');
		$tdate=$this->input->get('tdate');
		
		$referno=$this->input->get('referno');
		$empno=$this->input->get('empno');
		//if($fdate=='' && $tdate==''){
		$start_date = $this->erp->fld($fdate);
        $end_date = $this->erp->fld($tdate);
		
        $this->datatables
            ->select("erp_enter_using_stock.id as id,erp_enter_using_stock.reference_no as refno,
			erp_companies.company,erp_warehouses.name as warehouse_name,erp_users.username,erp_enter_using_stock.note,
			erp_enter_using_stock.type as type,erp_enter_using_stock.date,erp_enter_using_stock.total_cost", FALSE)
            ->from("erp_enter_using_stock")
			
		    ->join('erp_companies', 'erp_companies.id=erp_enter_using_stock.shop', 'inner')
		    ->join('erp_warehouses', 'erp_enter_using_stock.warehouse_id=erp_warehouses.id', 'left')
			
			->join('erp_users', 'erp_users.id=erp_enter_using_stock.employee_id', 'inner');
			if($fdate!='' && $tdate!=''){
				$this->datatables->where('erp_enter_using_stock.date>=',$start_date);
				$this->datatables->where('erp_enter_using_stock.date<=',$end_date);
			}
			if($referno!=''){
				$this->datatables->where('erp_enter_using_stock.reference_no',$referno);
			}
			if($empno!=''){
				$this->datatables->where('erp_users.username',$empno);
			}
             $this->datatables->add_column("Actions", "<div class=\"text-center\">
			 <a class='edit_using' href='" . site_url('products/edit_enter_using_stock_by_id/$1/$2') . "'  class='tip' title='Edit'>
			 <i class=\"fa fa-edit\"></i>
			 </a>  
			 <a class='edit_return' href='" . site_url('products/edit_enter_using_stock_return_by_id/$1/$2') . "'  class='tip' title='Edit'>
			 <i class=\"fa fa-edit\"></i>
			 </a> 
			 <a href='" . site_url('products/print_enter_using_stock_by_id/$1/$2') . "'  class='tip' title='Print'>
			 <i class=\"fa fa-file-text-o\"></i>
			 </a> 
			 <a class='add_return' href='".site_url('products/return_enter_using_stock_by_id/$1') . "'  class='tip' title='Return'><i class=\"fa fa-reply\"></i></a>
			 ", "id,type");
			/* <a href='" . site_url('system_settings/edit_sale_type/$1') . "' data-toggle='modal' data-target='#myModal' class='tip' title='Edit'>
			 <i class=\"fa fa-edit\"></i>
			 </a> 
			 <a href='#' class='tip po' title='<b>" . lang("delete") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p>
			 <a class='btn btn-danger po-delete' href='" . site_url('system_settings/delete_unit_of_saletype/$1') . "'>" . lang('i_m_sure') . "
			 </a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i>
			 </a>
			 </div>", "id,type");
			 */
        echo $this->datatables->generate();
    }

	public function getUsingStockById($id)
	{
		$this->db->where('id',$id);
		$q=$this->db->get('enter_using_stock');
		if($q){
			return $q->row();
		}else{return false;}
	}

	public function getReturnReference($ref)
	{
		$this->db->where('using_reference_no',$ref);
		$q=$this->db->get('enter_using_stock');
		if($q){
			return $q->row()->reference_no;
		}else{return false;}
	}

    public function getUsingStockProject($id)
	{
        $this->db->select('erp_companies.company, erp_companies.logo, erp_companies.address, erp_companies.phone, erp_companies.email');
        $this->db->from('erp_enter_using_stock');
        $this->db->join('erp_companies','erp_enter_using_stock.shop = erp_companies.id','left');
        $this->db->where('erp_enter_using_stock.id', $id);
        $q=$this->db->get();
        if($q){
            return $q->row();
        }else{return false;}
    }
	
	public function getUsingStockByCustomerID($id)
	{
        $this->db->select('erp_companies.*');
        $this->db->from('erp_enter_using_stock');
        $this->db->join('erp_companies','erp_enter_using_stock.customer_id = erp_companies.id','left');
        $this->db->where('erp_enter_using_stock.id', $id);
        $q=$this->db->get();
        if($q){
            return $q->row();
        }else{return false;}
    }

    public function getUsingStockProjectByRef($r_r)
	{
        $ref = str_replace('_', '&', $r_r);
        $this->db->select('erp_companies.*, erp_enter_using_stock.*,warehouses.name as warehouse_name, authorize.username as authorize_name, employee.username as employee_name');
        $this->db->from('erp_enter_using_stock');
        $this->db->join('erp_companies','erp_enter_using_stock.shop = erp_companies.id','left');
        $this->db->join('erp_warehouses', 'erp_enter_using_stock.warehouse_id = erp_warehouses.id', 'left');
        $this->db->join('erp_users as authorize', 'erp_enter_using_stock.authorize_id = authorize.id', 'left');
        $this->db->join('erp_users as employee', 'erp_enter_using_stock.employee_id = employee.id', 'left');
        $this->db->where('erp_enter_using_stock.reference_no', $ref);
        $q=$this->db->get();
        if($q){
            return $q->row();
        }else{return false;}
    }
	
	public function getUsingStockItemByRef($ref,$wh_id=NULL)
	{
		$this->db->select('enter_using_stock_items.id as e_id,
							enter_using_stock_items.code as product_code,
							enter_using_stock_items.description,
							enter_using_stock_items.reason,
							enter_using_stock_items.exp_cate_id,
							enter_using_stock_items.qty_use,
							enter_using_stock_items.qty_by_unit,
							enter_using_stock_items.unit,
							enter_using_stock_items.warehouse_id as wh_id,
							products.name,
							products.cost,
							products.code as product_code,
							products.id as product_id,
							sum(erp_warehouses_products.quantity) as quantity,
							products.unit as unit_type
						');
		$this->db->from('enter_using_stock_items');
		$this->db->join('products','enter_using_stock_items.code=products.code');
		$this->db->join('warehouses_products','products.id = warehouses_products.product_id');
		$this->db->where('enter_using_stock_items.reference_no', $ref);
			
		$this->db->group_by('e_id');
		$q=$this->db->get();
		if($q){
			return $q->result();
		}else{return false;}
	}
	
	public function getUsingStockItemsByRef($ref)
	{
		$this->db->select('enter_using_stock_items.id as e_id,
							enter_using_stock_items.code as code,
							enter_using_stock_items.code as code,
							enter_using_stock_items.description,
							enter_using_stock_items.qty_use,
							enter_using_stock_items.qty_by_unit,
							enter_using_stock_items.unit,
							enter_using_stock_items.expiry,
							enter_using_stock_items.warehouse_id as wh_id,
							enter_using_stock_items.option_id as option_id,
							products.name,
							products.cost,
							products.quantity,
							products.code as product_code,
							products.id as id,
							warehouses_products.quantity as qoh,
							products.unit as unit_type,
							units.name as unit_name
						');
		$this->db->from('enter_using_stock_items');
		$this->db->join('products', 'enter_using_stock_items.code = products.code', 'left');
		$this->db->join('units', 'units.id = products.unit', 'left');
		$this->db->join('warehouses_products', 'enter_using_stock_items.warehouse_id = warehouses_products.warehouse_id and products.id = warehouses_products.product_id', 'left');
		$this->db->where('enter_using_stock_items.reference_no', $ref);
			
		$this->db->group_by('e_id');
		$q=$this->db->get();
		if($q){
			return $q->result();
		}else{
			return false;
		}
	}
	
	public function getUsingStockItems($ref)
	{
		$this->db->select('enter_using_stock_items.id as e_id,
							enter_using_stock_items.code as product_code,
							enter_using_stock_items.description,
							enter_using_stock_items.reason,
							enter_using_stock_items.qty_use,
							enter_using_stock_items.qty_by_unit,
							enter_using_stock_items.unit,
							enter_using_stock_items.warehouse_id as wh_id,
							products.name,
							products.cost,
							products.quantity,
							products.code as product_code,
							products.id as product_id,
							warehouses_products.quantity as qoh,
							products.unit as unit_type,
							units.name as unit_name
						');
		$this->db->from('enter_using_stock_items');
		$this->db->join('products','enter_using_stock_items.code = products.code', 'left');
		$this->db->join('units','units.id = products.unit', 'left');
		$this->db->join('warehouses_products','enter_using_stock_items.warehouse_id = warehouses_products.warehouse_id and products.id = warehouses_products.product_id', 'left');
		$this->db->where('enter_using_stock_items.reference_no', $ref);
			
		$this->db->group_by('e_id');
		$q=$this->db->get();
		if($q){
			return $q->result();
		}else{return false;}
	}
	
	public function getReturnStockItem($ref, $code)
	{
		$q = $this->db->select('qty_by_unit as return_qty')->from('enter_using_stock_items')->where(array('reference_no'=>$ref, 'code'=>$code))->get()->row()->return_qty;
		if($q){
			return $q;
		}
		return false;
		
	}
	
	public function getQtyOnHandGroupByWhID()
	{
		$this->db->select('warehouses_products.id,warehouses_products.product_id,warehouses_products.warehouse_id,sum(erp_warehouses_products.quantity) as qqh,products.code as product_code');
		$this->db->from('warehouses_products');
		$this->db->Group_by('warehouse_id');
		$this->db->Group_by('product_id');
		$this->db->join('products','warehouses_products.product_id=products.id');
		$q=$this->db->get();
		if($q){
			return $q->result();
		}else{return false;}
	}
	
	public function update_enter_using_stock($stock_id, $data, $items)
	{
		
        $this->syncUsingStock($data['reference_no'], $stock_id, $data['plan_id']);
		$this->db->where('id',$stock_id);
		if($this->db->update('enter_using_stock', $data)){
			foreach($items as $item){
				$product_id 	= $item['product_id'];
				$product_name 	= $item['product_name'];

				unset($item['product_name']);
				$this->db->insert('enter_using_stock_items', $item);
				$using_stock_item = $this->db->insert_id();	
				
				$pur_data = array(
					'product_id' 		=> $product_id,
					'product_code' 		=> $item['code'],
					'product_name' 		=> $product_name,
					'net_unit_cost' 	=> $item['cost'],
					'option_id' 		=> $item['option_id'],
					'quantity' 			=> -1 * abs($item['qty_use']),
					'reference'			=> $item['reference_no'],
					'warehouse_id' 		=> $item['warehouse_id'],
					'date' 				=> $data['date'],
					'expiry' 			=> $item['expiry'],
					'status' 			=> 'received',
					'quantity_balance' 	=> -1 * abs($item['qty_use']),
					'transaction_type' 	=> 'USING STOCK',
					'transaction_id' 	=> $using_stock_item,
				);	

				$this->db->insert('purchase_items', $pur_data);
				$product_cost = $this->site->getProductByID($product_id);
				
				$pro_item 	 = $this->getProjectPlanItem($data['plan_id'], $product_id);
				$new_qty_use = $pro_item->quantity_used + $item['qty_use'];
				
				$this->db->update("project_plan_items", array("project_plan_items.quantity_used" => $new_qty_use), array("project_plan_id" => $data['plan_id'], "product_id" => $product_id ));
				
				$this->db->update("inventory_valuation_details",array('cost'=>$product_cost->cost,'avg_cost'=>$product_cost->cost),array('field_id'=>$using_stock_item));
				$this->site->syncProductQty($product_id, $item['warehouse_id']);
				
			}
			return true;
		}else{
			return false;
		}
	}
	
	private function syncUsingStock($ref, $stock_id, $plan_id){
		$using_stock = $this->site->getUsingStockById($stock_id);
		$using_item  = $this->site->getUsingStockByRef($ref);
		$del_pu_item = $this->delete_purchase_items_by_ref($ref);
		$del_en_item = $this->delete_enter_items_by_ref($ref);
		foreach($using_item as $item){
			$product 	 = $this->site->getProductByCode($item->code);
			$pro_item 	 = $this->getProjectPlanItem($plan_id, $product->id);
			$new_qty_use = $pro_item->quantity_used - $item->qty_use;
			
			$this->db->update("project_plan_items", array("project_plan_items.quantity_used" => $new_qty_use), array("project_plan_id" => $plan_id, "product_id" => $product->id ));
			
			$this->db->delete("inventory_valuation_details",array('field_id'=>$item->id, 'type' => 'USING STOCK'));
			$this->site->syncQuantitys(NULL,  NULL, NULL, $product->id);
		}
		
	}
	
	public function delete_purchase_items_by_ref($reference_no)
	{
		$this->db->where('reference', $reference_no);
		$d=$this->db->delete('purchase_items');
		if($d){
			return true;
		}return false;
	}
	
	public function delete_purchase_items_by_conId($id)
	{
		$this->db->where(array('transaction_type' => 'CONVERT', 'transaction_id' => $id));
		$d=$this->db->delete('purchase_items');
		if($d){
			return true;
		}return false;
	}
	
	public function get_purchase_items_by_conId($id)
	{
		$q = $this->db->get_where('purchase_items', array('transaction_id' => $id, 'transaction_type' => 'CONVERT') );
		if ($q->num_rows() > 0) {
			return $q->row();
		}
		return FALSE;
	}
	
	public function delete_inventory_valuation_details($stock_item_id_arr)
	{
		foreach($stock_item_id_arr as $id){
			$this->db->delete("inventory_valuation_details",array('field_id'=>$id));
		}
		return true;
	}
	
	public function delete_enter_items_by_ref($reference_no)
	{
		$this->db->where('reference_no', $reference_no);
		$d=$this->db->delete('enter_using_stock_items');
		if($d){
			return true;
		}return false;
	}
	
	public function update_enter_using_stock_item($data,$item_id)
	{
		$this->db->where('id',$item_id);
		$i = $this->db->update('enter_using_stock_items', $data);
		if($i){
			return true;
		}else{return false;}
	}
	
	public function delete_update_stock_item($id)
	{
		$d = $this->db->delete('enter_using_stock_items', array('id' => $id));
	}
	
	public function get_enter_using_stock_by_id($id)
	{
		$this->db->select('enter_using_stock.*, warehouses.name, users.first_name, users.last_name, project_plan.plan, CONCAT(erp_products.cf4, "", erp_products.cf3) as address');
		$this->db->from('enter_using_stock');
		$this->db->join('project_plan','enter_using_stock.plan_id=project_plan.id', 'left');
		$this->db->join('warehouses','warehouses.id=enter_using_stock.warehouse_id', 'left');
		$this->db->join('products','products.id=enter_using_stock.address_id', 'left');
        $this->db->join('users','users.id=enter_using_stock.employee_id', 'left');
		$this->db->where('enter_using_stock.id',$id);
		$q=$this->db->get();
		if($q){
			return $q->row();
		}else{
			return false;
		}
	}
	
	public function getUsingStockItem($item_code,$reference_no)
	{
		$this->db->where('code',$item_code);
		$this->db->where('reference_no',$reference_no);
		$q=$this->db->get('enter_using_stock_items');
		if($q){
			return $q->row();
		}return false;
	}
	
	public function getUsingStockReturnItemByRef($ref,$wh_id=NULL)
	{
		$this->db->select('enter_using_stock_items.id as e_id,
									enter_using_stock_items.code as product_code,
									enter_using_stock_items.description,
									enter_using_stock_items.reason,
									enter_using_stock_items.qty_use,
									enter_using_stock_items.qty_by_unit,
									enter_using_stock_items.unit,
									enter_using_stock_items.warehouse_id as wh_id,
									products.name,
									products.cost,
									products.code as product_code,
									products.id as product_id,
									sum(erp_warehouses_products.quantity) as quantity,
									products.unit as unit_type,
									,erp_enter_using_stock_items.qty_use as qty_use_from_using_stock
									
						');
		$this->db->from('enter_using_stock_items');
		$this->db->join('products','enter_using_stock_items.code=products.code');
		$this->db->join('warehouses_products','products.id=warehouses_products.product_id');
		$this->db->join('enter_using_stock','enter_using_stock_items.reference_no=enter_using_stock.reference_no');
		$this->db->where('enter_using_stock_items.reference_no',$ref);
		
		
		$this->db->group_by('e_id');
		$q=$this->db->get();
		if($q){
			return $q->result();
		}else{return false;}
	}
	
	public function get_all_enter_using_stock($id) 
	{
		$this->db->select('enter_using_stock.*,users.username,companies.company,warehouses.name as warehouse_name,users.first_name,users.last_name');
    //  $this->db->from('enter_using_stock');
        $this->db->join('warehouses', 'warehouses.id=enter_using_stock.warehouse_id',left);
        $this->db->join('users', 'users.id=enter_using_stock.employee_id',inner);
        $this->db->join('companies', 'companies.id = enter_using_stock.shop',inner);
        $q = $this->db->get_where('enter_using_stock', array('enter_using_stock.id' => $id), 1);
    if ($q->num_rows() > 0) {
        return $q->row();
    }
    return FALSE;
    }
	
	public function getPurcahseItemByPurchaseID($id)
    {
		$this->db->select('products.code, products.name, products.cost, products.quantity, (erp_products.cost * erp_products.quantity) AS total_cost, products.unit, units.name as uname');
		$this->db->from('purchase_items');
		$this->db->join('products','products.id = purchase_items.product_id', 'left');
		$this->db->join('units','units.id = products.unit', 'left');
		$this->db->where('purchase_id', $id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }
	
	public function getPurchaseByID($id)
    {
        $q = $this->db->get_where('purchases', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }	
	
	public function getProduct()
	{
		
		$this->db->select("products.id,	CONCAT(erp_products.code,' - ',erp_products.name) AS name,
							COALESCE(SUM(CASE WHEN erp_purchase_items.purchase_id <> 0 THEN (erp_purchase_items.quantity*(CASE WHEN erp_product_variants.qty_unit <> 0 THEN erp_product_variants.qty_unit ELSE 1 END)) ELSE 0 END),0) as purchasedQty,
							SUM( erp_sale_items.quantity*(CASE WHEN erp_product_variants.qty_unit <> 0 THEN erp_product_variants.qty_unit ELSE 1 END)) soldQty,
							
							");					
				
					$this->db->from('products');					
					$this->db->group_by("products.id");	
					$this->db->join('sale_items','sale_items.product_id = products.id', 'left');
					$this->db->join('product_variants','product_variants.product_id = products.id', 'left');
					$this->db->join('purchase_items', 'purchase_items.product_id = products.id', 'left');				
					$this->db->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left');
					$this->db->join('categories', 'products.category_id=categories.id', 'left');
					$q = $this->db->get();
					if ($q->num_rows() > 0) {
						foreach (($q->result()) as $row) {
							$data[] = $row;
						}
						return $data;
					}
					return FALSE;
	}
	
	public function getProductsReportTable($biller_id = NULL,$param = NULL)
	{
			
		if ($param['product']) {
            $product = $param['product'];
        } else {
            $product = NULL;
        }
		
		if ($param['biller_id']) {
            $biller_id = $param['biller_id'];
        } else {
            $biller_id = NULL;
        }

        if ($param['category']) {
            $category = $param['category'];
        } else {
            $category = NULL;
        }
		if ($param['supplier']) {
            $supplier = $param['supplier'];
        } else {
            $supplier = NULL;
        }
		
		if ($param['start_date']) {
            $start_date = $param['start_date'];
        } else {
            $start_date = NULL;
        }
		
		if ($param['end_date']) {
            $end_date = $param['end_date'];
        } else {
            $end_date = NULL;
        }
		if ($param['warehouse']) {
            $warehouse = $param['warehouse'];
			$where_sale=$param['where_sale'];
			$where_purchase=$param['where_purchase'];
        } else {
            $warehouse = NULL;
			$where_purchase = '';
			$where_sale='';
        }
		
		if($biller_id){
			$where_p_biller = "AND p.biller_id = {$biller_id} ";
			$where_s_biller = "AND s.biller_id = {$biller_id} ";
		}else{
			$where_p_biller = 'AND 1=1 ';
			$where_s_biller = 'AND 1=1 ';
		}
		
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
			//echo $start_date; die();
            $end_date = $end_date ? $this->erp->fld($end_date) : date('Y-m-d');
			
			
			
			$pp = "( SELECT 
				pi.date as date, pi.product_id, 
				pi.purchase_id, 
				COALESCE(SUM( CASE WHEN pi.purchase_id <> 0 THEN (pi.quantity*(CASE WHEN ppv.qty_unit <> 0 THEN ppv.qty_unit ELSE 1 END)) ELSE 0 END),0) as purchasedQty, 
				SUM(pi.quantity_balance) as balacneQty, 
				SUM((CASE WHEN pi.option_id <> 0 THEN ppv.cost ELSE pi.net_unit_cost END) * pi.quantity_balance ) balacneValue, 
				SUM( pi.unit_cost * (CASE WHEN pi.purchase_id <> 0 THEN pi.quantity ELSE 0 END) ) totalPurchase 
				FROM {$this->db->dbprefix('purchase_items')} pi 
				LEFT JOIN {$this->db->dbprefix('purchases')} p 
				on p.id = pi.purchase_id 
				LEFT JOIN " . $this->db->dbprefix('product_variants') . " ppv 
				ON ppv.id=pi.option_id 
				WHERE p.date >= '{$start_date}' and p.date < '{$end_date}' 
				AND pi.status <> 'ordered'
				". $where_p_biller ."
				GROUP BY pi.product_id ) PCosts";
				
			$sp = "( SELECT si.product_id, 
				SUM( si.quantity*(CASE WHEN pv.qty_unit <> 0 THEN pv.qty_unit ELSE 1 END)) soldQty, 
				SUM( si.subtotal ) totalSale, 
				s.date as sdate 
				FROM " . $this->db->dbprefix('sales') . " s 
				INNER JOIN " . $this->db->dbprefix('sale_items') . " si 
				ON s.id = si.sale_id 
				LEFT JOIN " . $this->db->dbprefix('product_variants') . " pv 
				ON pv.id=si.option_id 
				WHERE s.date >= '{$start_date}' 
				AND s.date < '{$end_date}' 
				". $where_s_biller ."
				GROUP BY si.product_id ) PSales";
			
        } else {
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
						". $where_p_biller ."
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
						". $where_s_biller ."
						GROUP BY si.product_id ) PSales";
        }
		return array($param,$pp,$sp);
        
	}
	
    public function getStockCountProducts($warehouse_id, $type, $categories = NULL, $brands = NULL)
    {
        $this->db->select("{$this->db->dbprefix('products')}.id as id, {$this->db->dbprefix('products')}.code as code, {$this->db->dbprefix('products')}.name as name, {$this->db->dbprefix('warehouses_products')}.quantity as quantity")
        ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
        ->where('warehouses_products.warehouse_id', $warehouse_id)
        ->where(array('products.type'=>'standard', 'products.inactived != ' => '1'))
        ->order_by('products.code', 'asc');
        if ($categories) {
            $r = 1;
            $this->db->group_start();
            foreach ($categories as $category) {
                if ($r == 1) {
                    $this->db->where('products.category_id', $category);
                } else {
                    $this->db->or_where('products.category_id', $category);
                }
                $r++;
            }
            $this->db->group_end();
        }
        if ($brands) {
            $r = 1;
            $this->db->group_start();
            foreach ($brands as $brand) {
                if ($r == 1) {
                    $this->db->where('products.brand', $brand);
                } else {
                    $this->db->or_where('products.brand', $brand);
                }
                $r++;
            }
            $this->db->group_end();
        }

        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getStockCountProductVariants($warehouse_id, $product_id)
    {
        $this->db->select("
				{$this->db->dbprefix('product_variants')}.name,				
				{$this->db->dbprefix('warehouses_products_variants')}.quantity as quantity")
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left');
        $q = $this->db->get_where('product_variants', array('product_variants.product_id' => $product_id, 'warehouses_products_variants.warehouse_id' => $warehouse_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row){
                $data[] = $row;
            }
            return $data;
        }
    }

    public function addStockCount($data)
    {
        if ($data) {
            if ($this->site->getReference('st', $data['biller_id']) == $data['reference_no']) {
                $this->site->updateReference('st', $data['biller_id']);
            }
            unset($data['biller_id']);
            $this->db->insert('stock_counts', $data);
            return TRUE;
        }
        return FALSE;
    }

    public function finalizeStockCount($id, $data, $products)
    {
        if ($this->db->update('stock_counts', $data, array('id' => $id))) {
            foreach ($products as $product) {
                $this->db->insert('stock_count_items', $product);
            }
            return TRUE;
        }
        return FALSE;
    }

    public function getStouckCountByID($id)
    {
        $q = $this->db->get_where("stock_counts", array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getStockCountItems($stock_count_id)
    {
        $q = $this->db->get_where("stock_count_items", array('stock_count_id' => $stock_count_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return NULL;
    }
	
	public function getAdjustmentByCountID($count_id)
    {
        $q = $this->db->get_where('adjustments', array('count_id' => $count_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductVariantID($product_id, $name)
    {
        $q = $this->db->get_where("product_variants", array('product_id' => $product_id, 'name' => $name), 1);
        if ($q->num_rows() > 0) {
            $variant = $q->row();
            return $variant->id;
        }
        return NULL;
    }
	
	public function deleteProductPhoto($id)
    {
        if ($this->db->delete('product_photos', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function addStock($data, $product)
	{
		
		$this->db->insert_batch('purchase_items', $data);
		foreach($product as $product_id){
			$this->site->syncQuantitys(NULL,  NULL, NULL, $product_id);
		}
	}
	
	public function getProductByWareId($w_id=null, $c_id = null)
    {	
		if($c_id){
			$this->db->where('products.category_id',$c_id);
		}
		$this->db->where(array('warehouses_products.warehouse_id'=>$w_id, 'products.inactived !='=>'1'));
		$this->db->select('products.id as pid, products.code, products.name as label, COALESCE(erp_product_variants.name, "") as variant, warehouses_products.quantity as quantity, 0 as qty');
        $this->db->from('products');
		$this->db->join('warehouses_products' ,'warehouses_products.product_id=products.id', 'left');
		$this->db->join('product_variants' ,'product_variants.product_id=products.id', 'left');
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
		return FALSE;
    }

    public function getProductByArrProId($w_id=null, $c_id = null, $p_id = null)
    {
		if($c_id){
			$this->db->where('products.category_id',$c_id);
		}
        $this->db->select('products.id as pid, products.code, products.name as label, COALESCE(erp_product_variants.name, "") as variant, warehouses_products.quantity as quantity, 0 as qty');
        $this->db->from('products');
        $this->db->join('warehouses_products' ,'warehouses_products.product_id=products.id', 'left');
        $this->db->join('product_variants' ,'product_variants.product_id=products.id', 'left');
        $this->db->where(array('warehouses_products.warehouse_id'=>$w_id, 'products.inactived !='=>'1'));
        $this->db->where_in('warehouses_products.product_id', $p_id);

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
		return FALSE;
    }
	
	public function getReasonsForPositionID($position_id)
    {
        $this->db->select('id as id, description as text');
        $q = $this->db->get_where("reasons", array('position_id' => $position_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }
	
	public function getAllExpenseCategory()
    {
        $q = $this->db->get('expense_categories');
        if ($q->num_rows() > 0) {
			return $q->result();
        }
        return FALSE;
    }
	
	public function getAllreasons()
    {
        $q = $this->db->get('reasons');
        if ($q->num_rows() > 0) {
			return $q->result();
        }
        return FALSE;
    }
	
	public function getAllPositionData() 
	{
		$q = $this->db->get('position');
		if ($q->num_rows() > 0 ) {
			$data = $q->result();
			return $data;
		}
		return FALSE;
	}
	
	public function getAdjustmentItems($adjustment_id)
    {
        $this->db->select('adjustment_items.*, products.code as product_code, products.name as product_name, products.image, products.details as details, product_variants.name as variant')
            ->join('products', 'products.id=adjustment_items.product_id', 'left')
            ->join('product_variants', 'product_variants.id=adjustment_items.option_id', 'left')
            ->group_by('adjustment_items.id')
            ->order_by('id', 'asc');

        $this->db->where('adjust_id', $adjustment_id);

        $q = $this->db->get('adjustment_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function updateMultiAdjustment($id, $data, $dataPurchase = NULL,$itemid)
    {
		if ($this->db->update('adjustments', $data, array('id' => $id))) {
			$quantity_balance = 0;
			$pro_db_id  = array();
			$pro_in_id  = array();
			$pro_merge  = array();
			$pro_unique = array();
			
			$adjust_item = $this->getAdjustmentItems($id);
			
			foreach($adjust_item as $items){
				$pro_db_id[] = $items->product_id;
			}
			
			foreach($dataPurchase as $items){
				$pro_in_id[] = $items['product_id'];
			}
			
			$pro_merge = array_merge($pro_db_id, $pro_in_id);
			$pro_unique = array_unique($pro_merge);
			
			$this->db->delete('adjustment_items', array('adjust_id'=>$id));
			
			foreach($itemid as $idd){
				$this->db->delete("inventory_valuation_details",array('field_id'=>$idd['itemidd'], 'type' => 'ADJUSTMENT') );
			}
			foreach($adjust_item as $adj_items){
				$this->db->delete('purchase_items', array('transaction_id'=>$adj_items->id, 'transaction_type' => 'ADJUSTMENT') );
			}
			
			foreach($dataPurchase as $products){
				$products['adjust_id'] 	= $id;
				$products['date'] 		= $data['date'];
				$quantity_balance 		= $products['quantity_balance'];
				$expiry 				= $products['expiry'];
				$product_code 			= $products['product_code'];
				$product_name 			= $products['product_name'];
				$product_type 			= $products['product_type'];
				unset($products['quantity_balance']);
				unset($products['expiry']);
                unset($products['product_code']);
                unset($products['product_name']);
                unset($products['product_type']);
                $this->db->insert('adjustment_items', $products);
				$adjust_item_id 		= $this->db->insert_id();
				$product_cost = $products['cost'];
				unset($products['adjust_id']);
				unset($products['cost']);
				unset($products['total_cost']);
				unset($products['type']);
				unset($products['biller_id']);
				$products['quantity_balance'] 	= $quantity_balance;
				$products['expiry'] 			= $expiry;
				$products['transaction_id'] 	= $adjust_item_id;
				$products['product_code'] 		= $product_code;
				$products['product_name'] 		= $product_name;
				$products['product_type'] 		= $product_type;
				$products['real_unit_cost'] 	= $product_cost;
				$products['transaction_type'] 	= 'ADJUSTMENT';
				$products['status'] 			= 'received';
				$products['reference'] 			= $data['reference_no'];	
				$this->db->insert('purchase_items', $products);	
			}
			
			foreach($pro_unique as $product_id){
				$this->site->syncQuantitys(null, null, null, $product_id);
			}			
			return true;
        }
        return false;
    }
	
	public function getAdjustQtyFromWare($adjust_id, $product_id)
	{
		$this->db->select('warehouses_products.quantity')
				 ->from('adjustments')
				 ->join('warehouses_products', 'warehouses_products.warehouse_id = adjustments.warehouse_id')
				 ->where(array('adjustments.id' => $adjust_id,'warehouses_products.product_id'=>$product_id));
		$q = $this->db->get();
		if ($q->num_rows() > 0 ) {
			return $q->row();
		}
		return FALSE;
	}
	
	public function getAdjustExpiryDate($adjust_item_id, $product_id)
	{
		$this->db->select('expiry')
				 ->from('purchase_items')
				 ->where(array('transaction_id' => $adjust_item_id, 'transaction_type' => 'ADJUSTMENT', 'product_id' => $product_id));
		$q = $this->db->get();
		if ($q->num_rows() > 0 ) {
			return $q->row();
		}
		return FALSE;
	}
	
    public function get_enter_using_stock_info()
	{
        $this->db->select('erp_companies.*')
                 ->from('erp_settings')
                 ->join('erp_companies', 'erp_settings.default_biller = erp_companies.id','left');
        $q = $this->db->get();
        if ($q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }
    
	public function getAuInfo($id)
	{
        $this->db->select('erp_users.username')
                 ->from('erp_enter_using_stock')
                 ->join('erp_users', 'erp_enter_using_stock.authorize_id = erp_users.id','left')
                 ->where('erp_enter_using_stock.id',$id);
        $q = $this->db->get();
        if ($q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }
    
	public function getAuInfoByref($r_r)
	{
        $ref = str_replace('_', '&', $r_r);
        $this->db->select('erp_users.username')
                 ->from('erp_enter_using_stock')
                 ->join('erp_users', 'erp_enter_using_stock.authorize_id = erp_users.id','left')
                 ->where('erp_enter_using_stock.reference_no',$ref);
        $q = $this->db->get();
        if ($q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllWarehousesByUser($warehouse_id) 
	{
        $wid = explode(',', $warehouse_id);
        $this->db->select('warehouses.*')
                 ->from('warehouses')
                 ->where_in("id", $wid);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }

    public function getAllOrderProductsQty($product_id)
    {
        $this->db->select("warehouses.id as id, sale_order.reference_no, warehouses.name, erp_sale_order_items.piece as piece,((erp_sale_order_items.quantity * COALESCE(erp_product_variants.qty_unit, 1)) - (erp_sale_order_items.quantity_received * COALESCE(erp_product_variants.qty_unit, 1))) as qty, sale_order.sale_status")
            ->from('sale_order_items')
            ->join('sale_order', 'sale_order_items.sale_order_id = sale_order.id', 'left')
            ->join('warehouses', 'warehouses.id = sale_order_items.warehouse_id', 'left')
			->join('product_variants', 'sale_order_items.option_id = product_variants.id', 'left')
            ->where('sale_order_items.product_id', $product_id)
            ->where('sale_order.order_status =', 'completed')
			->where('((erp_sale_order_items.quantity * COALESCE(erp_product_variants.qty_unit, 1)) - (erp_sale_order_items.quantity_received * COALESCE(erp_product_variants.qty_unit, 1))) > 0')
            ->where("(erp_sale_order.sale_status ='order' OR (erp_sale_order.delivery_status <> 'completed' AND erp_sale_order.sale_status <>'sale'))", NULL, FALSE);
        
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getRate() {
        $this->db->select('*')
                ->from('erp_currencies')
                ->where('erp_currencies.name','Riel In');
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function getPlan()
	{
		$this->db->select('*');
        $q = $this->db->get("project_plan");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
	}
    
	public function getAddressById($plan = null)
    {
        $this->db->select('id, CONCAT(cf4, " ", cf3) AS text')
				 ->where('cf1', $plan);
        $q = $this->db->get("products");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }
	
	public function getPasswordOA()
	{
		$group_ids = array('1', '2');
		$this->db->where_in('group_id', $group_ids);
		$q = $this->db->get("users");
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
	}
	
	public function returnUsingStock($data, $items)
	{
        $this->db->update('enter_using_stock', array('is_return' => '1'), array('id' => $data['id']));
        unset($data['id']);
        if($this->db->insert('enter_using_stock', $data)){
            $return_id = $this->db->insert_id();
            if ($this->site->getReference('esr', $data['shop']) == $data['reference_no']) {
                $this->site->updateReference('esr', $data['shop']);
            }


            foreach($items as $item){
				$product_id 	= $item['product_id'];
				$product_name 	= $item['product_name'];
				
				unset($item['product_id']);
				unset($item['product_name']);
				$this->db->insert('enter_using_stock_items', $item);
				$using_stock_item = $this->db->insert_id();	
				
				$pur_data = array(
					'product_id' 		=> $product_id,
					'product_code' 		=> $item['code'],
					'product_name' 		=> $product_name,
					'net_unit_cost' 	=> $item['cost'],
					'option_id' 		=> $item['option_id'],
					'quantity' 			=> abs($item['qty_use']),
					'reference'			=> isset($item['using_reference_no']),
					'warehouse_id' 		=> $item['warehouse_id'],
					'date' 				=> $data['date'],
					'expiry' 			=> $item['expiry'],
					'status' 			=> 'received',
					'quantity_balance' 	=> abs($item['qty_use']),
					'transaction_type' 	=> 'RETURN USING STOCK',
					'transaction_id' 	=> $using_stock_item,
				);
				$qty_use = $item['qty_use'];
			   
				$this->db->insert('purchase_items', $pur_data);

				$this->site->syncProductQty($product_id, $item['warehouse_id']);
			}
			
			return $return_id;
		}else{
			return false;
		}
	}
	
	public function gethomeType()
	{
		$q = $this->db->get('project_plan');
		if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
	}
	public function getSuppliers()
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

    public function getProductVariant($product_id, $id)
    {
        $this->db->select("erp_product_variants.qty_unit")
            ->join('erp_product_variants', 'erp_product_variants.name=erp_variants.name', 'left')
            ->where('product_variants.product_id', $product_id);
        $q = $this->db->get_where('erp_variants', array('erp_variants.id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
}
