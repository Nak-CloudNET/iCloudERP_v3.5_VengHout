<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Purchases_request_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->default_biller_id = $this->site->default_biller_id();
    }
	
	public function getVariantQtyById($id) {
		$q = $this->db->get_where('product_variants', array('id' => $id), 1);
		if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function getPurchaseRequestStatus($id){
		$this->db->select('erp_purchases_request.id,erp_purchases_request.status');
		$q=$this->db->get_where('erp_purchases_request',array('id' => $id),1);
			if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	public function addPurchaseRequest($data, $items)
    {
		if ($this->db->insert('purchases_request', $data)) {
            $purchase_id = $this->db->insert_id();
			if ($this->site->getReference('pq',$data['biller_id']) == $data['reference_no']) {
				$this->site->updateReference('pq',$data['biller_id']);
			}
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
                $this->db->insert('purchase_request_items', $item);
			}
            return true;
        }
        return false;
    }

	public function getAllPurchaseRequestItems($purchase_id)
    {
        $this->db->select('purchase_request_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant,companies.name, units.name AS pro_unit')
            ->join('products', 'products.id=purchase_request_items.product_id', 'left')
            ->join('units', 'products.unit=units.id', 'left')
			->join('companies', 'companies.id=purchase_request_items.supplier_id', 'left')
            ->join('product_variants', 'product_variants.id=purchase_request_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=purchase_request_items.tax_rate_id', 'left')
            ->group_by('purchase_request_items.id')
            ->order_by('id', 'desc');
        $q = $this->db->get_where('purchase_request_items', array('purchase_id' => $purchase_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getAllBiller($purchase_id)
    {
        $this->db->select('companies.*')
            ->join('companies', 'companies.id=purchases_request.biller_id', 'left');
        $q = $this->db->get_where('purchases_request', array('purchases_request.id' => $purchase_id));
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getCompaniesByID($supplier_id)
    {
        $this->db->select('companies.company,companies.address,companies.phone,companies.email')
            ->join('companies', 'companies.id=purchases_request.biller_id', 'left');
        $q = $this->db->get_where('purchases_request', array('purchases_request.id' => $supplier_id));
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    public function getWarehouseByID($warehouse_id)
    {
        $this->db->select('purchases_request.reference_no,
date,warehouses.`name`')
            ->join('warehouses', 'warehouses.id=purchases_request.warehouse_id', 'left');
        $q = $this->db->get_where('purchases_request', array('purchases_request.warehouse_id' => $warehouse_id));
         if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
	
	public function getAllPurchaseRequestItems_create($purchase_id)
    {
        $this->db->select('purchase_request_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, products.unit, products.details as details, product_variants.name as variant,companies.name')
            ->join('products', 'products.id=purchase_request_items.product_id', 'left')
			->join('companies', 'companies.id=purchase_request_items.supplier_id', 'left')
            ->join('product_variants', 'product_variants.id=purchase_request_items.option_id', 'left')
            ->join('tax_rates', 'tax_rates.id=purchase_request_items.tax_rate_id', 'left')
            ->group_by('purchase_request_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('purchase_request_items', array('purchase_id' => $purchase_id,'create_status'=>'0'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
	
	public function getPurchaseRequestByID($id)
    {
        // $q = $this->db->get_where('erp_purchases_request', array('id' => $id), 1);
        // if ($q->num_rows() > 0) {
        //     return $q->row();
        // }
        // return FALSE;
        $this->db->select('erp_purchases_request.*, erp_companies.company, erp_companies.name AS username,erp_warehouses.name AS ware_name')
                ->from('erp_purchases_request')
                ->join('erp_warehouses', 'erp_purchases_request.warehouse_id = erp_warehouses.id', 'left')
                ->join('erp_companies', 'erp_purchases_request.biller_id = erp_companies.id', 'left')
                ->join('erp_users', 'erp_purchases_request.created_by = erp_users.id', 'left')
                ->where('erp_purchases_request.id',$id);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }
    
	public function UpdatePurchaseRequest($id,$data, $items)
    {
		//$this->erp->print_arrays($data, $items);
		if ($this->db->update('purchases_request', $data,array('id' => $id)) && $this->db->delete('purchase_request_items', array('purchase_id' => $id))) {
            $purchase_id = $this->db->insert_id();
            if ($this->session->userdata('biller_id')) {
                $biller_id = $this->session->userdata('biller_id');
            } else {
                $biller_id = $Settings->default_biller_id;
            }
            if ($this->site->getReference('pq', $biller_id) == $data['reference_no']) {
                $this->site->updateReference('pq');
            }

            foreach ($items as $item) {
				
				$price = $item['price'];
				//unset($item['price']);
				$item['purchase_id'] = $id;
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
                $this->db->insert('purchase_request_items', $item);
			}
            return true;
        }
        return false;
    }
	
	 public function deletePurchaseRequest($id)
    {
        if ($this->db->delete('purchase_request_items', array('purchase_id' => $id)) && $this->db->delete('purchases_request', array('id' => $id))) {
            return true;
        }
        return FALSE;
    }
	
	public function getPurchaseRequestId($id){
		$this->db->select('purchases_request.id, date, reference_no,companies.company as project, purchases_request.supplier, purchases_request.status, grand_total,order_status ');
		$this->db->from('purchases_request');
		$this->db->join('companies', 'purchases_request.biller_id = companies.id', 'left');
		$this->db->where('purchases_request.id' , $id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}

    public function getAllCompaniesByID($biller_id) {
        $biller_id=json_decode($biller_id);
        $this->db->select('companies.*')
                 ->from('companies')
                 ->where_in("id",$biller_id);
        $q = $this->db->get();
        //$this->erp->print_arrays($biller_id,$q->row());
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
    }

}
