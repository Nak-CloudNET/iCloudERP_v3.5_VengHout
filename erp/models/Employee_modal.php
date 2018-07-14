<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_modal extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
	
	
    public function delete_employee($id)
    {
        $this->trigger_events('pre_delete_user');

        $this->db->trans_begin();

        // remove user from groups
        //$this->remove_from_group(NULL, $id);

        // delete user from users table should be placed after remove from group
        $this->db->delete($this->tables['users'], array('id' => $id));

        // if user does not exist in database then it returns FALSE else removes the user from groups
        if ($this->db->affected_rows() == 0) {
            return FALSE;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->trigger_events(array('post_delete_user', 'post_delete_user_unsuccessful'));
            $this->set_error('delete_unsuccessful');
            return FALSE;
        }

        $this->db->trans_commit();

        $this->trigger_events(array('post_delete_user', 'post_delete_user_successful'));
        $this->set_message('delete_successful');
        return TRUE;
    }

	public function getEmployees(){
		$this->db->where('user_type', 'employee');
		$query = $this->db->get('users');
		foreach($query->result() as $row){
			$data[] = $row;
		}
		return $data;
	}

	public function getEmployee(){
		$query = $this->db->get('users');
		foreach($query->result() as $row){
			$data = $row;
		}
		return $data;
	}
    
	public function getTaxExchangeRateByMY($month = NULL, $year = NULL){
		$this->db->where('month', $month)
				 ->where('year', $year);
		$this->db->limit(0,1);
		$query = $this->db->get('tax_exchange_rate');
		if($query->num_rows() > 0){
			return $query->row();
		}
		return false;
	}
	
	public function getSalaryTaxTriggerByDate($year_month){
		$this->db->where('year_month', $year_month);
		$this->db->limit(0,1);
		$query = $this->db->get('employee_salary_tax_trigger');
		if($query->num_rows() > 0){
			return $query->row();
		}
		return false;
	}
	public function getSalaryTaxReference(){
		$this->db->select('CONCAT("SL00000", MAX(SUBSTR(reference_no, 3))+1) AS reference_no');
		$query = $this->db->get('employee_salary_tax_trigger');
		$reference_no = '';
		if($query->num_rows() > 0){
			$reference_no = $query->row()->reference_no;
		}
		if(!$reference_no){
			$reference_no = "SL000001";
		}
		return $reference_no;
	}
	public function getSalaryTaxTriggerByDate_Tab($year_month,$tab){
		$this->db->where('year_month', $year_month);
		$this->db->where('tab', $tab);
		$this->db->limit(0,1);
		$query = $this->db->get('employee_salary_tax_trigger');
		if($query->num_rows() > 0){
			return $query->row();
		}
		return false;
	}
	public function insert_employee_salary($data, $items, $cond=null){
	
		$data['tab'] = $cond;
		if($data){
			
			
			//fringe benefit only//
			if($cond==1){
				$salary_tax_trigger = $this->getSalaryTaxTriggerByDate_Tab($data['year_month'],1);
				if($salary_tax_trigger){
					$data['tab'] = 1;
					$data['reference_no'] = $salary_tax_trigger->reference_no;
					$data['updated_at'] = date('Y-m-d H:m:i');
					$data['updated_by'] = $this->session->userdata('user_id');
					$this->db->update('employee_salary_tax_trigger', $data, array('id' => $salary_tax_trigger->id));
					$trigger_id = $salary_tax_trigger->id;
				}else{
					$this->db->insert('employee_salary_tax_trigger', $data);
					$trigger_id = $this->db->insert_id();
				}
			}
			if($cond==2){
				$salary_tax_trigger = $this->getSalaryTaxTriggerByDate_Tab($data['year_month'],2);
				if($salary_tax_trigger){
					$data['tab'] = 2;
					$data['reference_no'] = $salary_tax_trigger->reference_no;
					$data['updated_at'] = date('Y-m-d H:m:i');
					$data['updated_by'] = $this->session->userdata('user_id');
					$this->db->update('employee_salary_tax_trigger', $data, array('id' => $salary_tax_trigger->id));
					$trigger_id = $salary_tax_trigger->id;
				}else{
					$this->db->insert('employee_salary_tax_trigger', $data);
					$trigger_id = $this->db->insert_id();
				}
			}
			if($cond==3){
				$salary_tax_trigger = $this->getSalaryTaxTriggerByDate_Tab($data['year_month'],3);
				$get_ref =$this->site->getReference('tr');
				
				if($salary_tax_trigger){
					$data['tab'] = 3;
					$data['reference_no'] = $salary_tax_trigger->reference_no;
					$data['updated_at'] = date('Y-m-d H:m:i');
					$data['updated_by'] = $this->session->userdata('user_id');
					$this->db->update('employee_salary_tax_trigger', $data, array('id' => $salary_tax_trigger->id));
					$trigger_id = $salary_tax_trigger->id;
				}else{
					$data['reference_no_j']=$get_ref;
					$this->db->insert('employee_salary_tax_trigger', $data);
					$this->update_ref_tr($data);
					$trigger_id = $this->db->insert_id();
				}
			}
			foreach($items as $item){
				$item['tab'] = $cond;
				if($item['employee_id']){
					$item['trigger_id'] = $trigger_id;
					// if($this->db->update('users', array('salary' => $item['basic_salary'])))
					$basic_salary = $item['basic_salary'];
					
					$date_month = date('Y-m', strtotime($item['date_insert']));
					
					$employee_salary = $this->getCurrentEmployeeSalary($item['employee_id'], $item['date_insert']);
					
					$date_db = date('Y-m', strtotime($employee_salary->date_insert));
					
					if($date_month == $date_db){
						$employee_salary = $this->getCurrentEmployeeSalary($item['employee_id'], $item['date_insert']);
						if($employee_salary){
							$item['updated_by'] = $this->session->userdata('user_id');
							$item['updated_at'] = date('Y-m-d H:m:i');
							$this->db->update('employee_salary_tax', $item, array('id' => $employee_salary->id));
						}else{
							if($this->db->insert('employee_salary_tax', $item)){
								$this->db->update('users', array('salary' => $basic_salary), array('id'  => $item['employee_id']));
							}
						}
					}else{
						if($this->db->insert('employee_salary_tax', $item)){
							$this->db->update('users', array('salary' => $basic_salary), array('id'  => $item['employee_id']));
						}
					}
				}	
			}
			
			return true;
		}
	}
	public function getCurrentEmployeeSalary($employee_id, $date){
		if($date){
			$date = date('Y-m', strtotime($date));
		}else{
			$date = date('Y-m');
		}
		
		$this->db->where('employee_id', $employee_id);
		$this->db->where('DATE_FORMAT(date_insert, "%Y-%m")= "'. $date . '"');
		$this->db->limit(0,1);
		$query = $this->db->get('employee_salary_tax');
		if($query->num_rows() > 0){
			return $query->row();
		}
		return false;
	}
	 public function addEmployee($data = array())
    {
        if ($this->db->insert('users', $data)) {
            return true;
        }
        return false;
	}
}
?>