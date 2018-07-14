<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Project_plan_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

	public function getProductNames($term, $standard, $combo, $digital, $service, $category, $limit = 10)
    {
        
		$this->db->where("(type = 'standard' OR type = 'service') AND (name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%') AND inactived <> 1");
		if(!$this->Owner || !$this->Admin){
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
		$this->db->order_by('code', 'ASC');
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
	
	public function getProductOptions($product_id)
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
	
	public function addProjectPlan($data, $items){
		if ($this->db->insert('project_plan', $data)) {
			$plan_id = $this->db->insert_id();
			if ($this->session->userdata('biller_id')) {
                $biller_id = $this->session->userdata('biller_id');
            } else {
                $biller_id = $this->Settings->default_biller;
            }
			if ($this->site->getReference('pn', $biller_id) == $data['reference_no']) {
				$this->site->updateReference('pn', $biller_id);
			}
			foreach ($items as $item) {
				$item['project_plan_id'] = $plan_id;
				$this->db->insert('project_plan_items', $item);
			}
			return $plan_id;
		} else {
			return false;
		}
	}
	
	public function updateProjectPlan($id, $data, $items){
		if ($this->db->update('project_plan', $data, array('id' => $id) )) {
			$this->db->delete('project_plan_items', array('project_plan_id' => $id));
			foreach ($items as $item) {
				$item['project_plan_id'] = $id;
				$this->db->insert('project_plan_items', $item);
			}
			return true;
		} else {
			return false;
		}
	}
	
	public function getProductVariant($id){
		$q = $this->db->get_where('product_variants', array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getAllProjectPlan($id){
		$q = $this->db->get_where('project_plan', array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getAllProjectPlanItem($id){
		$this->db->select('products.id as id, products.type, project_plan_items.product_code as code, project_plan_items.product_name as name, project_plan_items.option_id as option, project_plan_items.quantity as qty, products.quantity as quantity')
				 ->join('products', 'products.id = project_plan_items.product_id', 'left');
		$q = $this->db->get_where('project_plan_items', array('project_plan_id' => $id));
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function getPlan(){
		$q = $this->db->get('project_plan');
        if ($q->num_rows() > 0) {
            return $q->result();
        }
        return FALSE;
	}
	
	public function addProjectAddress($data){
		if ($this->db->insert('plan_address', $data)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function updateProjectAddress($data){
		if ($this->db->update('plan_address', $data)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getAddress($id){
		$q = $this->db->get_where('plan_address', array('id' => $id));
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	public function getProjectPlan($id){
		$this->db->select('erp_project_plan.*')
						 ->from('erp_project_plan')
						->where('erp_project_plan.id',$id);
		$q = $this->db->get();
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
	}
	
	
}
