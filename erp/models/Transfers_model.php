<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transfers_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProductNames($term, $warehouse_id, $limit = 20)
    {
        $this->db->select('products.id, image, products.code, products.name, product_details, warehouses_products.quantity, products.cost, tax_rate, type, tax_method, unit,warehouses_products_variants.option_id')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->join('warehouses_products_variants', 'warehouses_products_variants.product_id=products.id', 'left')
            ->group_by('products.id');
        if ($this->Settings->overselling) {
            $this->db->where("type = 'standard' AND (erp_products.name LIKE '%" . $term . "%' OR erp_products.code LIKE '%" . $term . "%' OR  concat(erp_products.name, ' (', erp_products.code, ')') LIKE '%" . $term . "%')");
        } else {
            $this->db->where("type = 'standard' AND warehouses_products.warehouse_id = '" . $warehouse_id . "' AND warehouses_products.quantity > 0 AND "
                . "(erp_products.name LIKE '%" . $term . "%' OR erp_products.code LIKE '%" . $term . "%' OR  concat(name, ' (', erp_products.code, ')') LIKE '%" . $term . "%')");
        }
        $this->db->limit($limit);
        $q = $this->db->get('erp_products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
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
            if ($this->site->getReference('to',$data['biller_id']) == $data['transfer_no']) {
                $this->site->updateReference('to',$data['biller_id']);
            }

            foreach ($items as $item) {
                $item['transfer_id'] 		= $transfer_id;
				$option_id 					= $item['option_id'];
				$this->db->insert('transfer_items', $item);
				$transfer_item_id = $this->db->insert_id();

                if ($status == 'completed') {
                    $item['status'] 			= 'received';
                    $item['transaction_type'] 	= 'TRANSFER';
                    $item['transaction_id'] 	= $transfer_item_id;

                    $this->syncTransderdItem($data['to_warehouse_id'], $data['from_warehouse_id'], $item, $transfer_item_id);

                }
            }
            optimizeTransferStock($data['date']);

            return true;
        }
        return false;
    }

    public function updateTransfer($id, $data = array(), $items = array(), $tran_items_id)
    {
		$this->resetTransferActionsSync($id);
        $status = $data['status'];
        if ($this->db->update('transfers', $data, array('id' => $id))) {
            $this->db->delete('transfer_items', array('transfer_id' => $id));
			
			foreach ($items as $item) {
                $item['transfer_id'] = $id;

				$this->db->insert('transfer_items', $item);
				$transfer_item_id = $this->db->insert_id();
				
                if ($status == 'completed') {
                    $item['status'] 			= 'received';
					$item['transaction_type'] 	= 'TRANSFER';
					$item['transaction_id'] 	= $transfer_item_id;
                    $this->syncTransderdItem($data['to_warehouse_id'], $data['from_warehouse_id'], $item);
                }
            }
            return true;
        }
        return false;
    }
	
	public function getProductWarehouseOptionQtyByUnitOne($id,$warehouse_id)
	{
        $this->db->select('COALESCE(SUM(quantity),0) AS qty');
			$this->db->from('erp_warehouses_products');
			$this->db->where('erp_warehouses_products.warehouse_id',$warehouse_id);
	        $this->db->where('erp_warehouses_products.product_id',$id);
	    $q = $this->db->get();
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	
	public function getProductWarehouseOptionQtyByUnit($id,$warehouse_id,$option_id)
	{
		$this->db->select('quantity AS qty');
		$this->db->from('erp_warehouses_products_variants');
		$this->db->where('erp_warehouses_products_variants.warehouse_id',$warehouse_id);
		$this->db->where('erp_warehouses_products_variants.product_id',$id);
		$this->db->where('erp_warehouses_products_variants.option_id',$option_id);
	    $q = $this->db->get();
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

    public function getProductByCategoryID($id)
    {

        $q = $this->db->get_where('products', array('category_id' => $id), 1);
        if ($q->num_rows() > 0) {
            return true;
        }

        return FALSE;
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

	public function getTransferByID($id=null, $wh=null)
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

    public function getAllTransferItems($transfer_id, $status)
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
            $this->db->select('transfer_items.*, product_variants.name as variant, products.unit, IFNULL(SUM(erp_transfer_items.quantity * erp_product_variants.qty_unit),erp_transfer_items.quantity) as TQty,units.name')
                ->from('transfer_items')
                ->join('products', 'products.id = transfer_items.product_id', 'left')
                ->join('product_variants', 'product_variants.id = transfer_items.option_id', 'left')
				->join('units', 'products.unit = units.id', 'left')
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
	
	
	public function resetTransferActionsSync($id)
	{
		$otransfer = $this->transfers_model->getTransferByID($id);
        $ostatus = $otransfer->status;
		
        if ($ostatus == 'sent' ||$ostatus == 'completed') {
            $this->db->delete('purchase_items', array('transfer_id' => $id));
        }
	
        $oitems = $this->transfers_model->getAllTransferItems($id, $ostatus);
		if($oitems) {
			foreach ($oitems as $item) {
				$this->db->delete("inventory_valuation_details", array('field_id'=>$item->id, 'type' => 'TRANSFER'));
				
                $this->site->syncQuantitys(NULL, NULL, NULL, $item->product_id);
            }
			return true;
		}
		
        return FALSE;
	}

    public function resetTransferActions($id)
    {
		
        $otransfer = $this->transfers_model->getTransferByID($id);
        $ostatus = $otransfer->status;
		
		
        if ($ostatus == 'sent' ||$ostatus == 'completed') {
            $this->db->delete('purchase_items', array('transfer_id' => $id));
			
        }
		
		$this->site->syncQuantitys(NULL, NULL, NULL, $item['product_id']);
		
		
        return $ostatus;
    }

    public function deleteTransfer($id)
    {
        $ostatus = $this->resetTransferActions($id);
        $oitems = $this->transfers_model->getAllTransferItems($id, $ostatus);
        if ($this->db->delete('transfers', array('id' => $id)) && $this->db->delete('transfer_items', array('transfer_id' => $id))) {
			foreach ($oitems as $item) {
                $this->site->syncQuantitys(NULL, NULL, NULL, $item->product_id);
            }
            return true;
        }
        return FALSE;
    }

    public function getProductOptions($product_id, $warehouse_id, $zero_check = TRUE)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, products.cost as cost, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity,product_variants.qty_unit')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
			->join('products','products.id = product_variants.product_id','left')
            ->where('product_variants.product_id', $product_id)
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
	public function getUnitById($product_id,$warehouse_id)
    {
		$this->db->select('product_variants.id as id, product_variants.name as name, product_variants.cost as cost, product_variants.quantity as total_quantity, warehouses_products.quantity as quantity')
            ->join('erp_warehouses_products', 'erp_warehouses_products.product_id=product_variants.product_id', 'left');
            if ($this->Settings->overselling) {
				$this->db->where('product_variants.product_id', $product_id);
			} else {
				$this->db->where('warehouses_products.warehouse_id', $warehouse_id);
				$this->db->where('erp_warehouses_products.quantity >', 0);
			}
			$this->db->group_by('product_variants.id');
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	public function  getTransferItemsByTransferId($transfer_id)
    {
		$this->db->select('transfer_items.id');
		$q = $this->db->get_where('transfer_items', array('transfer_id' => $transfer_id));
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

    public function getProductVariantByName($variant_name, $product_id)
    {
        $q = $this->db->get_where('product_variants', array('name' => $variant_name, 'product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getVariantName($variant){
        $q = $this->db->get_where('variants', array('id' => $variant), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

	public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL) {
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
	
	public function syncTransderdItem($to_warehouse_id = NULL, $from_warehouse_id = NULL, $item )
    {

		$item['warehouse_id'] 		= $to_warehouse_id;
		$this->db->insert('purchase_items', $item);
		$item['warehouse_id'] 		= $from_warehouse_id;
		$item['quantity'] 			= (-1) * $item['quantity'];
		$item['quantity_balance'] 	= (-1) * $item['quantity_balance'];
		$this->db->insert('purchase_items', $item);
        $this->site->syncQuantitys(NULL, NULL, NULL, $item['product_id']);
    }

    public function getProductOptionByIDUnits($id)
    {
		$this->db->select('SUM(unit) as qty_unit')
				 ->from('products')
				 ->where('products.id', $id);
		$q = $this->db->get();
     
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

	public function getWareQty($product_id, $warehouse_id){
		$this->db->select('quantity')
				 ->from('warehouses_products')
				 ->where(array('product_id'=>$product_id, 'warehouse_id'=>$warehouse_id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
		
	}
	
	public function getQty($product_id){
		$this->db->select('SUM(quantity) AS qty')
				 ->from('warehouses_products')
				 ->where(array('product_id'=>$product_id));
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
		
	}
	
	public function getDocumentByID($id){
		$this->db->select('attachment, attachment1, attachment2')
				 ->from('transfers')
				 ->where('id',$id);
		$q = $this->db->get();
		if($q->num_rows() > 0){
			return $q->result();
		}
		return false;
	}

   public function getTransfersInvoiceByID($id)
    {
        $this->db->select('erp_transfers.*, erp_users.username')
                 ->from('transfers')
                 ->join('erp_users','erp_transfers.created_by = erp_users.id','left')
                 ->where('erp_transfers.id',$id);        
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

   public function getAllTransfersInvoice($transfer_id)
    {
        $this->db->select('erp_transfer_items.option_id,erp_transfers.*,erp_transfer_items.product_name,erp_transfer_items.product_code, erp_transfer_items.quantity, erp_units.name as unit');
        $this->db->from('erp_transfers');
        //$this->db->join('erp_companies','erp_transfers.delivery_by = erp_companies.id','left');
        $this->db->join('erp_transfer_items','erp_transfer_items.transfer_id = erp_transfers.id', 'left');
        $this->db->join('erp_products','erp_transfer_items.product_id = erp_products.id', 'left');
        $this->db->join('erp_units','erp_products.unit = erp_units.id', 'left');
        $this->db->group_by('erp_transfer_items.id');
        
        $this->db->where('erp_transfers.id',$transfer_id);
        $q = $this->db->get();
        if($q->num_rows()>0){
            foreach($q->result() as $result){
                $data[] = $result;
            }
            return $data;
        }
        return NULL;
        
    }

    public function getVar($id){
        $q = $this->db->get_where('erp_product_variants', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getUsingStockById($id)
    {
        $this->db->where('id',$id);
        $q=$this->db->get('transfers');
        if($q){
            return $q->row();
        }else{return false;}
    }

}
