<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Transfers_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProductNames($term, $warehouse_id, $limit = 10)
    {
        $this->db->select('products.id, image, code, name, product_details, warehouses_products.quantity, cost, tax_rate, type, tax_method')
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
		//$this->erp->print_arrays($items);
        $status = $data['status'];
        if ($this->db->insert('transfers', $data)) {
            $transfer_id = $this->db->insert_id();
            if ($this->site->getReference('to') == $data['transfer_no']) {
                $this->site->updateReference('to');
            }
            foreach ($items as $item) {
                $item['transfer_id'] = $transfer_id;
				$option_id = $item['option_id'];
				
				if($option_id){
					$option = $this->transfers_model->getProductOptionByID($option_id);
					$item['quantity_balance'] = $item['quantity'] * $option->qty_unit;
				}
				
                if ($status == 'completed') {                    
					$item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['to_warehouse_id'];
					$item['status'] = 'received';
					$item['quantity'] = $item['quantity'] * (-1) ;
					$item['quantity_balance'] = $item['quantity_balance'] * (-1) ;
                    //$this->db->insert('purchase_items', $item);
					
					$item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['from_warehouse_id'];
                    $item['status'] = 'received';
					$item['quantity'] = $item['quantity'] * (-1) ;
					$item['quantity_balance'] = $item['quantity_balance']  * (-1);
                    $this->db->insert('purchase_items', $item);
                
				}
				if ($status == 'sent') {
                    
					$item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['to_warehouse_id'];
					$item['quantity'] = $item['quantity'] * (-1) ;
					$item['quantity_balance'] = $item['quantity_balance'] * (-1) ;
                    $item['status'] = 'pending';					
                    $this->db->insert('purchase_items', $item);
					
					$item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['from_warehouse_id'];
					$item['quantity'] = $item['quantity'] * (-1) ;
					$item['quantity_balance'] = $item['quantity_balance'] ;
                    unset($item['status']);					
                    $this->db->insert('transfer_items', $item);
                
				} 
				if ($status == 'pending'){
                    $item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['to_warehouse_id'];
					$this->db->insert('transfer_items', $item);
					
					/*$item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['from_warehouse_id'];
					$this->db->insert('transfer_items', $item);*/
                }
            /*   if ($status == 'sent') {
                    $this->syncTransderdItem($item['product_id'], $data['from_warehouse_id'], $item['quantity'], $item['option_id']);
					
					//from warehouse
					$qty = $this->getWareQty($item['product_id'], $data['from_warehouse_id']);
					if($qty){
						$final_qty = $qty->quantity - $item['quantity'];
						$this->db->insert('warehouses_products', array('quantity' => $final_qty, 'product_id' => $item['product_id'], 'warehouse_id' => $data['from_warehouse_id']));
					}
					
					//to warehouse
					$to_qty = $this->getWareQty($item['product_id'], $data['to_warehouse_id']);
					if($to_qty){
						$tof_qty = $to_qty->quantity + $item['quantity'];
						$this->db->insert('warehouses_products', array('quantity' => $tof_qty, 'product_id' => $item['product_id'], 'warehouse_id' => $data['to_warehouse_id']));
					}
				} */
					
			   if ($status == 'completed') {
                   
				   $this->syncTransderdItem($item['product_id'], $data['from_warehouse_id'], $item['quantity'], $item['option_id']);
					
					//from warehouse
					$qty = $this->getWareQty($item['product_id'], $data['from_warehouse_id']);
					if($qty){
						$final_qty = $qty->quantity - $item['quantity'];
						$this->db->update('warehouses_products', array('quantity' => $final_qty), array('product_id' => $item['product_id'], 'warehouse_id' => $data['from_warehouse_id']));
					}else{
						$final_qty = $qty->quantity - $item['quantity'];
						$this->db->insert('warehouses_products', array('quantity' => $final_qty, 'product_id' => $item['product_id'], 'warehouse_id' => $data['from_warehouse_id']));
					}
					
					//to warehouse
					$to_qty = $this->getWareQty($item['product_id'], $data['to_warehouse_id']);
					if($to_qty){
						$tof_qty = $to_qty->quantity + $item['quantity'];
						$this->db->update('warehouses_products', array('quantity' => $tof_qty), array('product_id' => $item['product_id'], 'warehouse_id' => $data['to_warehouse_id']));
					}else{
						$tof_qty = $to_qty->quantity + $item['quantity'];
						$this->db->insert('warehouses_products', array('quantity' => $tof_qty, 'product_id' => $item['product_id'], 'warehouse_id' => $data['to_warehouse_id']));
					}				
					
					//update products
					$PQty = $this->getQty($item['product_id']);
					$this->db->update('products', array('quantity' => $PQty->qty), array('id' => $item['product_id']));
			   }
            }
            return true;
        }
        return false;
    }

    public function updateTransfer($id, $data = array(), $items = array())
    {
		
        /*$ostatus = $this->resetTransferActions($id);
        $status = $data['status'];
        if ($this->db->update('transfers', $data, array('id' => $id))) {
            $tbl = $ostatus == 'completed' ? 'purchase_items' : 'transfer_items';
            $this->db->delete($tbl, array('transfer_id' => $id));

            foreach ($items as $item) {
                $item['transfer_id'] = $id;
				$option_id = $item['option_id'];
				if($option_id){
					$option = $this->transfers_model->getProductOptionByID($option_id);
					$item['quantity_balance'] = $item['quantity'] * $option->qty_unit;
				}
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
        return false; */
		
		$otransfer = $this->transfers_model->getTransferByID($id);
        $oitems = $this->transfers_model->getAllTransferItems($id, $otransfer->status);
        $ostatus = $otransfer->status;
		$status = $data['status'];
		if ($this->db->update('transfers', $data, array('id' => $id))) {
		//$this->erp->print_arrays($items);
		foreach ($items as $item) {
            $item['transfer_id'] = $id;
			//$items_id = $this->db->get_where('transfer_items', array('transfer_id'=>$id, 'product_id'=>$item['product_id']));
			$items_id = $this->db->select('id')
                  ->get_where('transfer_items', array('transfer_id'=>$id, 'product_id'=>$item['product_id']))
                  ->row()
                  ->id;

			$this->db->update('transfer_items', $item, array('transfer_id' => $id, 'id'=>$items_id));
			$option_id = $item['option_id'];
			if($option_id){
				$option = $this->transfers_model->getProductOptionByID($option_id);
				$item['quantity_balance'] = $item['quantity'] * $option->qty_unit;
			}
				/*if($status == 'completed'){
					
					if($ostatus == 'sent'){
						$item['date'] = date('Y-m-d');
						$item['warehouse_id'] = $data['to_warehouse_id'];						
						$item['status'] = 'received';
						$this->db->insert('purchase_items', $item);
					}
					
					$this->syncTransderdItem($item['product_id'], $data['from_warehouse_id'], $item['quantity'], $item['option_id']);
					
					//from warehouse
					$qty = $this->getWareQty($item['product_id'], $data['from_warehouse_id']);
					if($qty){
						$final_qty = $qty->quantity - $item['quantity'];
						$this->db->update('warehouses_products', array('quantity' => $final_qty), array('product_id' => $item['product_id'], 'warehouse_id' => $data['from_warehouse_id']));
					}else{
						$final_qty = $qty->quantity - $item['quantity'];
						$this->db->insert('warehouses_products', array('quantity' => $final_qty, 'product_id' => $item['product_id'], 'warehouse_id' => $data['from_warehouse_id']));
					}
					
					//to warehouse
					$to_qty = $this->getWareQty($item['product_id'], $data['to_warehouse_id']);
					if($to_qty){
						$tof_qty = $to_qty->quantity + $item['quantity'];
						$this->db->update('warehouses_products', array('quantity' => $tof_qty), array('product_id' => $item['product_id'], 'warehouse_id' => $data['to_warehouse_id']));
					}else{
						$tof_qty = $to_qty->quantity + $item['quantity'];
						$this->db->insert('warehouses_products', array('quantity' => $tof_qty, 'product_id' => $item['product_id'], 'warehouse_id' => $data['to_warehouse_id']));
					}					
					
					//update products
					$PQty = $this->getQty($item['product_id']);
					$this->db->update('products', array('quantity' => $PQty->qty), array('id' => $item['product_id']));
					
				}*/
				
				/*if($status == 'pending'){
					
					if($ostatus == 'sent'){
						$item['date'] = date('Y-m-d');
						$item['warehouse_id'] = $data['to_warehouse_id'];						
						$item['status'] = 'received';
						$this->db->insert('purchase_items', $item);
					}
					
					$this->syncTransderdItem($item['product_id'], $data['from_warehouse_id'], $item['quantity'], $item['option_id']);
					
					//from warehouse
					$qty = $this->getWareQty($item['product_id'], $data['from_warehouse_id']);
					if($qty){
						$final_qty = $qty->quantity - $item['quantity'];
						$this->db->update('warehouses_products', array('quantity' => $final_qty), array('product_id' => $item['product_id'], 'warehouse_id' => $data['from_warehouse_id']));
					}else{
						$final_qty = $qty->quantity - $item['quantity'];
						$this->db->insert('warehouses_products', array('quantity' => $final_qty, 'product_id' => $item['product_id'], 'warehouse_id' => $data['from_warehouse_id']));
					}
					
					//to warehouse
					$to_qty = $this->getWareQty($item['product_id'], $data['to_warehouse_id']);
					if($to_qty){
						$tof_qty = $to_qty->quantity + $item['quantity'];
						$this->db->update('warehouses_products', array('quantity' => $tof_qty), array('product_id' => $item['product_id'], 'warehouse_id' => $data['to_warehouse_id']));
					}else{
						$tof_qty = $to_qty->quantity + $item['quantity'];
						$this->db->insert('warehouses_products', array('quantity' => $tof_qty, 'product_id' => $item['product_id'], 'warehouse_id' => $data['to_warehouse_id']));
					}					
					
					//update products
					$PQty = $this->getQty($item['product_id']);
					$this->db->update('products', array('quantity' => $PQty->qty), array('id' => $item['product_id']));
					
				}*/
			}
			return true;
		}
		return false;
	}
	
	#KHR
    public function receivedTransfer($id, $data = array(), $items = array())
    {
		//$this->erp->print_arrays($items);
		$status = $data['status'];
		$product_id = $data['product_id'];
        if ($this->db->insert('recieved_transfers', $data)) {
            $received_id = $this->db->insert_id();
			
            if ($this->site->getReference('to') == $data['transfer_no']) {
                $this->site->updateReference('to');
            }
			
			
			
            foreach ($items as $item) {
                $item['received_id'] = $received_id;
				$option_id = $item['option_id'];
				
				if($option_id){
					$option = $this->transfers_model->getProductOptionByID($option_id);
					$item['quantity_balance'] = $item['quantity'] * $option->qty_unit;
				}
				
                if ($status == 'completed') {
					//$item['date'] = date('Y-m-d');
                    //$item['warehouse_id'] = $data['to_warehouse_id'];
					//$item['status'] = 'received';
					//$item['quantity'] = $item['quantity'] * (-1) ;
					//$item['quantity_balance'] = $item['quantity_balance'] * (-1) ;
                    //$this->db->insert('purchase_items', $item);
					
					$item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['from_warehouse_id'];
                    $item['status'] = 'received';
					$item['quantity'] = $item['quantity'] * (-1) ;
					$item['quantity_balance'] = $item['quantity_balance']  * (-1);
                    $this->db->insert('purchase_items', $item);
                
				}
				
				if ($status == 'sent') {
                    
					$item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['to_warehouse_id'];
					$item['quantity'] = $item['quantity'] * (-1) ;
					$item['quantity_balance'] = $item['quantity_balance'] * (-1) ;
                    $item['status'] = 'pending';					
                    $this->db->insert('purchase_items', $item);
					
					$item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['from_warehouse_id'];
					$item['quantity'] = $item['quantity'] * (-1) ;
					$item['quantity_balance'] = $item['quantity_balance'] ;
                    unset($item['status']);					
                    $this->db->insert('recieved_transfer_items', $item);
                
				}
				if ($status == 'pending'){
                    $item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['to_warehouse_id'];
					$this->db->insert('recieved_transfer_items', $item);
					
					#KHR
					//$this->db->update('warehouses_products', $data, array('id' => $id));
					$this->syncTransderdItem($item['product_id'], $data['from_warehouse_id'], $item['quantity'], $item['option_id']);
					
					//from warehouse
					$qty = $this->getWareQty($item['product_id'], $data['from_warehouse_id']);
					if($qty){
						$final_qty = $qty->quantity - $item['quantity'];
						$this->db->update('warehouses_products', array('quantity' => $final_qty), array('product_id' => $item['product_id'], 'warehouse_id' => $data['from_warehouse_id']));
					}else{
						$final_qty = $qty->quantity - $item['quantity'];
						$this->db->insert('warehouses_products', array('quantity' => $final_qty, 'product_id' => $item['product_id'], 'warehouse_id' => $data['from_warehouse_id']));
					}
					
					//to warehouse
					$to_qty = $this->getWareQty($item['product_id'], $data['to_warehouse_id']);
					if($to_qty){
						$tof_qty = $to_qty->quantity + $item['quantity'];
						$this->db->update('warehouses_products', array('quantity' => $tof_qty), array('product_id' => $item['product_id'], 'warehouse_id' => $data['to_warehouse_id']));
					}else{
						$tof_qty = $to_qty->quantity + $item['quantity'];
						$this->db->insert('warehouses_products', array('quantity' => $tof_qty, 'product_id' => $item['product_id'], 'warehouse_id' => $data['to_warehouse_id']));
					}
					
					//update products
					$PQty = $this->getQty($item['product_id']);
					$this->db->update('products', array('quantity' => $PQty->qty), array('id' => $item['product_id']));
					
					
					/*$item['date'] = date('Y-m-d');
                    $item['warehouse_id'] = $data['from_warehouse_id'];
					$this->db->insert('recieved_transfer_items', $item);*/
					
					#KHR
					//$this->db->update('warehouses_products', $data, array('id' => $id));
                }
            
				if ($status == 'completed') {
                   
				   $this->syncTransderdItem($item['product_id'], $data['from_warehouse_id'], $item['quantity'], $item['option_id']);
					
					//from warehouse
					$qty = $this->getWareQty($item['product_id'], $data['from_warehouse_id']);
					if($qty){
						$final_qty = $qty->quantity - $item['quantity'];
						$this->db->update('warehouses_products', array('quantity' => $final_qty), array('product_id' => $item['product_id'], 'warehouse_id' => $data['from_warehouse_id']));
					}else{
						$final_qty = $qty->quantity - $item['quantity'];
						$this->db->insert('warehouses_products', array('quantity' => $final_qty, 'product_id' => $item['product_id'], 'warehouse_id' => $data['from_warehouse_id']));
					}
					
					//to warehouse
					$to_qty = $this->getWareQty($item['product_id'], $data['to_warehouse_id']);
					if($to_qty){
						$tof_qty = $to_qty->quantity + $item['quantity'];
						$this->db->update('warehouses_products', array('quantity' => $tof_qty), array('product_id' => $item['product_id'], 'warehouse_id' => $data['to_warehouse_id']));
					}else{
						$tof_qty = $to_qty->quantity + $item['quantity'];
						$this->db->insert('warehouses_products', array('quantity' => $tof_qty, 'product_id' => $item['product_id'], 'warehouse_id' => $data['to_warehouse_id']));
					}				
					
					//update products
					$PQty = $this->getQty($item['product_id']);
					$this->db->update('products', array('quantity' => $PQty->qty), array('id' => $item['product_id']));
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
            $this->db->select('purchase_items.*, product_variants.name as variant, products.unit')
                ->from('purchase_items')
                ->join('products', 'products.id=purchase_items.product_id', 'left')
                ->join('product_variants', 'product_variants.id=purchase_items.option_id', 'left')	               
				->group_by('purchase_items.transfer_id')
                ->where('transfer_id', $transfer_id)
				->where('purchase_items.quantity >', 0);
        } else {
            $this->db->select('transfer_items.*, product_variants.name as variant, products.unit')
                ->from('transfer_items')
                ->join('products', 'products.id=transfer_items.product_id', 'left')
                ->join('product_variants', 'product_variants.id=transfer_items.option_id', 'left')
                ->group_by('transfer_items.transfer_id')
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
	
	public function getAllTransferItems2($transfer_id)
    {
        $this->db->select('recieved_transfers.id as id, recieved_transfers.transfer_no, recieved_transfers.date, CONCAT(erp_recieved_transfer_items.product_name, " (", erp_recieved_transfer_items.product_code, ")") as description, recieved_transfer_items.quantity, recieved_transfer_items.unit_cost, recieved_transfer_items.tax, recieved_transfer_items.subtotal')
                ->from('recieved_transfers')
				->join('recieved_transfer_items', 'recieved_transfers.id = recieved_transfer_items.received_id', 'left')				
				->join('transfer_items', 'recieved_transfer_items.product_id = transfer_items.product_id')
                ->where('transfer_items.transfer_id', $transfer_id);
				
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	
	/*public function getAllTransferItems2($transfer_id) {
		$this->db->select('transfer_items.transfer_id as id, transfer_items.product_code, transfer_items.product_name, CONCAT(erp_transfer_items.product_name," (",erp_transfer_items.product_code, ")") as pnamepcode, erp_transfer_items.quantity, erp_products.quantity as pquantity')
                ->from('transfer_items')
				->join('products', 'transfer_items.product_id = products.id', 'left')
				->join('transfers', 'transfer_items.transfer_id = transfers.id', 'left')
                ->where('transfer_items.transfer_id', $transfer_id);
				//->group_by('transfer_items.transfer_id');
				
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}*/
	
	public function getAllTransferItemsAndRecieved($transfer_id, $status)
    {
        if ($status == 'completed') {
            $this->db->select('purchase_items.*, product_variants.name as variant, products.unit')
                ->from('purchase_items')
                ->join('products', 'products.id=purchase_items.product_id', 'left')
                ->join('product_variants', 'product_variants.id=purchase_items.option_id', 'left')	               
				->group_by('purchase_items.transfer_id')
                ->where('transfer_id', $transfer_id)
				->where('purchase_items.quantity >', 0);
        } else {
            $this->db->select('transfer_items.*, SUM(erp_recieved_transfer_items.quantity) as qty_received, product_variants.name as variant, products.unit')
                ->from('transfer_items')
                ->join('products', 'products.id=transfer_items.product_id', 'left')
                ->join('product_variants', 'product_variants.id=transfer_items.option_id', 'left')
                ->join('transfers', 'transfers.id=transfer_items.transfer_id')
                ->join('recieved_transfers', 'recieved_transfers.transfer_no=transfers.transfer_no', 'left')
                ->join('recieved_transfer_items', 'recieved_transfer_items.received_id=recieved_transfers.id', 'left')
                ->group_by('transfer_items.transfer_id')
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

    public function resetTransferActions($id)
    {
        $otransfer = $this->transfers_model->getTransferByID($id);
        $oitems = $this->transfers_model->getAllTransferItems($id, $otransfer->status);
        $ostatus = $otransfer->status;
        if ($ostatus == 'sent' ||$ostatus == 'completed') {
            // $this->db->update('purchase_items', array('warehouse_id' => $otransfer->from_warehouse_id, 'transfer_id' => NULL), array('transfer_id' => $otransfer->id));
            foreach ($oitems as $item) {
                $option_id = (isset($item->option_id) && ! empty($item->option_id)) ? $item->option_id : NULL;
				$qty_balance = 0;
				if($option_id){
					$option = $this->getProductOptionByID($option_id);
					$qty_balance = $item->quantity * $option->qty_unit;
				}
				
                $clause = array('purchase_id' => NULL, 'transfer_id' => NULL, 'product_id' => $item->product_id, 'warehouse_id' => $otransfer->from_warehouse_id, 'option_id' => $option_id);
                $pi = $this->site->getPurchasedItem(array('id' => $item->id));
                if ($ppi = $this->site->getPurchasedItem($clause)) {
					if($option_id){
						$quantity_balance = $ppi->quantity_balance + $qty_balance;
					}else{
						$quantity_balance = $ppi->quantity_balance + $item->quantity;
					}                    
                    $this->db->update('purchase_items', array('quantity_balance' => $quantity_balance), $clause);
                } else {
                    $clause['quantity'] = $item->quantity;
                    $clause['item_tax'] = 0;
					if($option_id){
						$clause['quantity_balance'] = $qty_balance;
					}else{
						$clause['quantity_balance'] = $item->quantity;
					}
                    
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
	
	public function syncTransderdItem($product_id, $warehouse_id, $quantity, $option_id = NULL)
    {		
		if($option_id){
			$option = $this->getProductOptionByID($option_id);
			$quantity = $quantity * $option->qty_unit;
		}
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
		//Please don't delete this commend i keep to know the flow
		//Return worng when transfer many time 
        //$this->site->syncQuantity(NULL, NULL, NULL, $product_id);
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
	
}
