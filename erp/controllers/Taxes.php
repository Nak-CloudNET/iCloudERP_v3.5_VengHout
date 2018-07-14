<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Taxes extends MY_Controller
{
    
    function __construct()
    {
        parent::__construct();
        
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if ($this->Customer || $this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->load->helper('security');
        $this->lang->load('taxes', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
        $this->load->model('taxes_model');
        $this->load->model('reports_model');
        $this->load->model('taxes_reports_model');
        
        if (!$this->Owner && !$this->Admin) {
            $gp                 = $this->site->checkPermissions();
            $this->permission   = $gp[0];
            $this->permission[] = $gp[0];
        } else {
            $this->permission[] = NULL;
        }
    }
    
    function index($action = NULL)
    {
        $this->erp->checkPermissions('index', true, 'taxes');
        
        $this->data['error']  = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc                   = array(
            array(
                'link' => base_url(),
                'page' => lang('home')
            ),
            array(
                'link' => '#',
                'page' => lang('taxes')
            )
        );
        $meta                 = array(
            'page_title' => lang('taxes'),
            'bc' => $bc
        );
        $this->page_construct('taxes/index', $meta, $this->data);
    }
    
    function withholding_tax()
    {
        
        $this->erp->checkPermissions();
        $this->form_validation->set_rules('enterprise', lang("enterprise"), 'required');
        if ($this->form_validation->run() == true) {
            #########Save Header Page########	
            $enterpriseID = $this->input->post('enterprise');
            $startdate    = $this->input->post('st_yy') . "-" . $this->input->post('st_mm') . "-" . $this->input->post('st_dd');
            $enddate      = $this->input->post('en_yy') . "-" . $this->input->post('en_mm') . "-" . $this->input->post('en_dd');
            $createddate  = $this->input->post('cr_yy') . "-" . $this->input->post('cr_mm') . "-" . $this->input->post('cr_dd');
            $month        = $this->input->post('cr_mm');
            $year         = $this->input->post('cr_yy');
            $st_year      = $this->input->post('st_yy');
            $field_in_kh  = $this->input->post('kh_made_at');
            $field_in_en  = $this->input->post('en_made_at');
            ###RE###
            $salarypaid   = $this->input->post('salarypaid');
            $persent      = $this->input->post('persent');
            $wht_tax      = $this->input->post('withholding_box');
            $remark       = $this->input->post('remark');
            $type_of_oop  = $this->input->post('type_of_oop');
            ###NRE##
            $salary_nre   = $this->input->post('salary_nre');
            $persent_nre  = $this->input->post('persent_nre');
            $wht_tax_nre  = $this->input->post('withholding_box_nre');
            $remark_nre   = $this->input->post('remark_nre');
            $type_of_oop  = $this->input->post('type_of_oop');
            ###Detail-WTRT##
            $nor          = $this->input->post('nor');
            $oop          = $this->input->post('oop');
            $ipn          = $this->input->post('ipn');
            $abtw         = $this->input->post('abtw');
            $tax_r        = $this->input->post('tax_r');
            $wdt_tax      = $this->input->post('wdt_tax');
            ###Detail-WTNRT####
            $nor_nre      = $this->input->post('nor_nre');
            $oop_nre      = $this->input->post('oop_nre');
            $ipn_nre      = $this->input->post('ipn_nre');
            $abtw_nre     = $this->input->post('abtw_nre');
            $tax_r_nre    = $this->input->post('tax_r_nre');
            $wdt_tax_nre  = $this->input->post('wdt_tax_nre');
            
            $st_month = $this->input->post('st_mm');
            $get_ref  = $this->site->getReference('tr');
            $saveWHT  = array(
                'group_id' => $enterpriseID,
                'covreturn_start' => $startdate,
                'covreturn_end' => $enddate,
                'created_date' => $createddate,
                'year' => $st_year,
                'month' => $st_month,
                'reference_no' => $get_ref,
                'created_by' => $this->session->userdata('user_id'),
                'total_non_resident_tax' => $this->input->post('total_nrt_box'),
                'total_resident_tax' => $this->input->post('total_tr_box'),
                'field_in_en' => $field_in_en,
                'field_in_kh' => $field_in_kh
            );
            $cRE      = sizeof($salarypaid);
            //TOR-25
            for ($i = 0; $i < $cRE; $i++) {
                $TER[] = array(
                    'amount_paid' => $salarypaid[$i],
                    'tax_rate' => ($persent[$i] / 100),
                    'withholding_tax' => $wht_tax[$i],
                    'remarks' => $remark[$i],
                    'type' => 'TOR25',
                    'type_of_oop' => addslashes($type_of_oop[$i])
                );
            }
            //TOR-26
            $cNRE = sizeof($salary_nre);
            for ($j = 0; $j < $cNRE; $j++) {
                $TENR[] = array(
                    'amount_paid' => $salary_nre[$j],
                    'tax_rate' => ($persent_nre[$j] / 100),
                    'withholding_tax' => $wht_tax_nre[$j],
                    'remarks' => $remark_nre[$j],
                    'type' => 'TOR26',
                    'type_of_oop' => addslashes($type_of_oop[$j])
                );
            }
            //D-WTRT
            $cWTRT = sizeof($nor);
            for ($m = 0; $m < $cWTRT; $m++) {
                if ($nor[$m] != "") {
                    $DWTRT[] = array(
                        'emp_code' => $nor[$m],
                        'object_of_payment' => $oop[$m],
                        'invoice_paynote' => $ipn[$m],
                        'amount_paid' => $abtw[$m],
                        'tax_rate' => $tax_r[$m],
                        'withholding_tax' => $wdt_tax[$m],
                        'type' => 'DWTRT'
                    );
                }
            }
            
            
            //D-WTRNT
            $cWTRNT = sizeof($nor_nre);
            for ($y = 0; $y < $cWTRNT; $y++) {
                if ($nor_nre[$y] != "") {
                    $DWTRNT[] = array(
                        'emp_code' => $nor_nre[$y],
                        'object_of_payment' => $oop_nre[$y],
                        'invoice_paynote' => $ipn_nre[$y],
                        'amount_paid' => $abtw_nre[$y],
                        'tax_rate' => $tax_r_nre[$y],
                        'withholding_tax' => $wdt_tax_nre[$y],
                        'type' => 'DWTRNT'
                    );
                }
            }
            
            if ($this->taxes_model->saveWithholdingdTax($saveWHT, $TER, $TENR, $DWTRT, $DWTRNT, $st_month)) {
                $this->session->set_flashdata('message', lang("return_tax_declared"));
                redirect("taxes/withholding_tax");
            } else {
                $this->session->set_flashdata('error', lang("enterprice_not_selected"));
                redirect("taxes/withholding_tax");
            }
        } else {
            $this->data['users']      = $this->reports_model->getStaff();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers']    = $this->site->getAllCompanies('biller');
            $this->data['enterprise'] = $this->taxes_model->SelectEnterprise();
            $this->data['employees']  = $this->taxes_model->getAllUsers();
            
            $bc   = array(
                array(
                    'link' => base_url(),
                    'page' => lang('home')
                ),
                array(
                    'link' => '#',
                    'page' => lang('withholding_tax')
                )
            );
            $meta = array(
                'page_title' => lang('withholding_tax'),
                'bc' => $bc
            );
            
            $this->page_construct('taxes/withholding_tax', $meta, $this->data);
        }
    }
    
    function salary_tax()
    {
        $this->erp->checkPermissions();
        
        $this->form_validation->set_rules('enterprise', lang("enterprise"), 'required');
        if ($this->form_validation->run() == true) {
            //Salary_Tax
            $enterprise    = $this->input->post('enterprise');
            $start_date    = $this->input->post('stY') . '-' . $this->input->post('stM') . '-' . $this->input->post('stD');
            $end_date      = $this->input->post('etY') . '-' . $this->input->post('etM') . '-' . $this->input->post('etD');
            $createMonth   = $this->input->post('createM');
            $createYear    = $this->input->post('createY');
            $create_date   = $createYear . '-' . $createMonth . '-' . $this->input->post('createD');
            $kh_made_at    = $this->input->post('kh_made_at');
            $en_made_at    = $this->input->post('en_made_at');
            //RE
            $emp_04        = $this->input->post('emp_o4');
            $sal_04_paid   = $this->input->post('sal_04_paid');
            $spouse        = $this->input->post('spouse');
            $no_of_child   = $this->input->post('no_of_child');
            $sal_cal_04    = $this->input->post('sal_cal_04');
            $tax_rate_04   = $this->input->post('tax_rate_04');
            $tax_on_sal_04 = $this->input->post('tax_on_sal_04');
            //NRE
            $emp_05        = $this->input->post('emp_05');
            $sal_05_paid   = $this->input->post('sal_05_paid');
            $tax_rate_05   = $this->input->post('tax_rate_05');
            $tax_on_sal_05 = $this->input->post('tax_on_sal_05');
            //FB
            $emp_06        = $this->input->post('emp_06');
            $sal_06_paid   = $this->input->post('sal_06_paid');
            $tax_rate_06   = $this->input->post('tax_rate_06');
            $tax_on_sal_06 = $this->input->post('tax_on_sal_06');
            
            //RE Back
            $emp_back_01        = $this->input->post('emp_back_01');
            $national_back_01   = $this->input->post('national_back_01');
            $function_back_01   = $this->input->post('function_back_01');
            $sal_01_paid        = $this->input->post('sal_01_paid');
            $spouse_back_01     = $this->input->post('spouse_back_01');
            $child_back_01      = $this->input->post('child_back_01');
            $tax_rate_back_01   = $this->input->post('tax_rate_back_01');
            $tax_on_sal_back_01 = $this->input->post('tax_on_sal_back_01');
            $remark_back_01     = $this->input->post('remark_back_01');
            //FB Back
            $emp_back_02        = $this->input->post('emp_back_02');
            $national_back_02   = $this->input->post('national_back_02');
            $function_back_02   = $this->input->post('function_back_02');
            $sal_02_benefit     = $this->input->post('sal_02_benefit');
            $tax_rate_back_02   = $this->input->post('tax_rate_back_02');
            $tax_on_sal_back_02 = $this->input->post('tax_on_sal_back_02');
            $remark_back_02     = $this->input->post('remark_back_02');
            
            //Salary_Tax
            if ($emp_back_01 != '') {
                $salary_tax = array(
                    'group_id' => $enterprise,
                    'covreturn_start' => $start_date,
                    'covreturn_end' => $end_date,
                    'created_date' => $create_date,
                    'year' => $createYear,
                    'month' => $createMonth,
                    'filed_in_kh' => $kh_made_at,
                    'filed_in_en' => $en_made_at
                );
            }
            //RE
            $RE = '';
            $no = sizeof($emp_04);
            for ($m = 0; $m < $no; $m++) {
                if ($emp_04[$m] != '' && $sal_04_paid[$m]) {
                    $RE[] = array(
                        'emp_num' => $emp_04[$m],
                        'salary_paid' => $sal_04_paid[$m],
                        'spouse_num' => $spouse[$m],
                        'children_num' => $no_of_child[$m],
                        'tax_salcalbase' => $sal_cal_04[$m],
                        'tax_rate' => $tax_rate_04[$m],
                        'tax_salary' => $tax_on_sal_04[$m],
                        'tax_type' => 'RE'
                        
                    );
                }
            }
            //NRE
            $NRE = '';
            if ($emp_05 != '' && $sal_05_paid) {
                $NRE = array(
                    'emp_num' => $emp_05,
                    'salary_paid' => $sal_05_paid,
                    'tax_rate' => $tax_rate_05,
                    'tax_salary' => $tax_on_sal_05,
                    'tax_type' => 'NRE'
                );
            }
            //FB
            $FB = '';
            if ($emp_06 != '' && $sal_06_paid) {
                $FB = array(
                    'emp_num' => $emp_06,
                    'salary_paid' => $sal_06_paid,
                    'tax_rate' => $tax_rate_06,
                    'tax_salary' => $tax_on_sal_06,
                    'tax_type' => 'FB'
                );
            }
            
            //REB
            $REB = '';
            $num = sizeof($emp_back_01);
            for ($m = 0; $m < $num; $m++) {
                if ($emp_back_01[$m] != '') {
                    $REB[] = array(
                        'empcode' => $emp_back_01[$m],
                        'salary_paid' => $sal_01_paid[$m],
                        'spouse' => $spouse_back_01[$m],
                        'minor_children' => $child_back_01[$m],
                        'nationality' => $national_back_01[$m],
                        'position' => $function_back_01[$m],
                        'date_insert' => date('Y-m-d'),
                        'tax_type' => 'REB',
                        'tax_rate' => $tax_rate_back_01[$m],
                        'tax_salary' => $tax_on_sal_back_01[$m],
                        'remark' => $remark_back_01[$m]
                    );
                }
            }
            //FBB
            $FBB = '';
            $num = sizeof($emp_back_01);
            for ($m = 0; $m < $num; $m++) {
                if ($emp_back_02[$m] != '') {
                    $FBB[] = array(
                        'empcode' => $emp_back_02[$m],
                        'salary_paid' => $sal_02_benefit[$m],
                        'nationality' => $national_back_02[$m],
                        'position' => $function_back_02[$m],
                        'date_insert' => date('Y-m-d'),
                        'tax_type' => 'FBB',
                        'tax_rate' => $tax_rate_back_02[$m],
                        'tax_salary' => $tax_on_sal_back_02[$m],
                        'remark' => $remark_back_02[$m]
                    );
                }
            }
            if ($this->taxes_model->addSalaryTax($salary_tax, $RE, $NRE, $FB, $REB, $FBB)) {
                $this->session->set_flashdata('message', lang("salary_tax_declared"));
                redirect("taxes/salary_tax");
            } else {
                $this->session->set_flashdata('error', lang("enterprice_not_selected"));
                redirect("taxes/salary_tax");
            }
        } else {
            $this->data['users']      = $this->reports_model->getStaff();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers']    = $this->site->getAllCompanies('biller');
            $this->data['enterprise'] = $this->taxes_model->SelectEnterprise();
            $this->data['employees']  = $this->taxes_model->getAllUsers();
            
            $bc   = array(
                array(
                    'link' => base_url(),
                    'page' => lang('home')
                ),
                array(
                    'link' => '#',
                    'page' => lang('salary_tax')
                )
            );
            $meta = array(
                'page_title' => lang('salary_tax'),
                'bc' => $bc
            );
            
            $this->page_construct('taxes/salary_tax', $meta, $this->data);
        }
    }
    
    function salary_tax_edit($id = NULL)
    {
        $this->erp->checkPermissions();
        
        $this->form_validation->set_rules('enterprise', lang("enterprise"), 'required');
        if ($this->form_validation->run() == true) {
            //Salary_Tax
            $sal_tax_id    = $this->input->post('sal_tax_id');
            $enterprise    = $this->input->post('enterprise');
            $start_date    = $this->input->post('stY') . '-' . $this->input->post('stM') . '-' . $this->input->post('stD');
            $end_date      = $this->input->post('etY') . '-' . $this->input->post('etM') . '-' . $this->input->post('etD');
            $createMonth   = $this->input->post('createM');
            $createYear    = $this->input->post('createY');
            $create_date   = $createYear . '-' . $createMonth . '-' . $this->input->post('createD');
            $kh_made_at    = $this->input->post('kh_made_at');
            $en_made_at    = $this->input->post('en_made_at');
            //RE
            $emp_04        = $this->input->post('emp_o4');
            $sal_04_paid   = $this->input->post('sal_04_paid');
            $spouse        = $this->input->post('spouse');
            $no_of_child   = $this->input->post('no_of_child');
            $sal_cal_04    = $this->input->post('sal_cal_04');
            $tax_rate_04   = $this->input->post('tax_rate_04');
            $tax_on_sal_04 = $this->input->post('tax_on_sal_04');
            //NRE
            $emp_05        = $this->input->post('emp_05');
            $sal_05_paid   = $this->input->post('sal_05_paid');
            $tax_rate_05   = $this->input->post('tax_rate_05');
            $tax_on_sal_05 = $this->input->post('tax_on_sal_05');
            //FB
            $emp_06        = $this->input->post('emp_06');
            $sal_06_paid   = $this->input->post('sal_06_paid');
            $tax_rate_06   = $this->input->post('tax_rate_06');
            $tax_on_sal_06 = $this->input->post('tax_on_sal_06');
            
            //RE Back
            $emp_back_01        = $this->input->post('emp_back_01');
            $national_back_01   = $this->input->post('national_back_01');
            $function_back_01   = $this->input->post('function_back_01');
            $sal_01_paid        = $this->input->post('sal_01_paid');
            $spouse_back_01     = $this->input->post('spouse_back_01');
            $child_back_01      = $this->input->post('child_back_01');
            $tax_rate_back_01   = $this->input->post('tax_rate_back_01');
            $tax_on_sal_back_01 = $this->input->post('tax_on_sal_back_01');
            $remark_back_01     = $this->input->post('remark_back_01');
            //FB Back
            $emp_back_02        = $this->input->post('emp_back_02');
            $national_back_02   = $this->input->post('national_back_02');
            $function_back_02   = $this->input->post('function_back_02');
            $sal_02_benefit     = $this->input->post('sal_02_benefit');
            $tax_rate_back_02   = $this->input->post('tax_rate_back_02');
            $tax_on_sal_back_02 = $this->input->post('tax_on_sal_back_02');
            $remark_back_02     = $this->input->post('remark_back_02');
            
            //Salary_Tax
            if ($emp_back_01 != '') {
                $salary_tax = array(
                    'group_id' => $enterprise,
                    'covreturn_start' => $start_date,
                    'covreturn_end' => $end_date,
                    'created_date' => $create_date,
                    'year' => $createYear,
                    'month' => $createMonth,
                    'filed_in_kh' => $kh_made_at,
                    'filed_in_en' => $en_made_at
                );
            }
            //RE
            $RE = '';
            $no = sizeof($emp_04);
            for ($m = 0; $m < $no; $m++) {
                if ($emp_04[$m] != '' && $sal_04_paid[$m]) {
                    $RE[] = array(
                        'emp_num' => $emp_04[$m],
                        'salary_paid' => $sal_04_paid[$m],
                        'spouse_num' => $spouse[$m],
                        'children_num' => $no_of_child[$m],
                        'tax_salcalbase' => $sal_cal_04[$m],
                        'tax_rate' => $tax_rate_04[$m],
                        'tax_salary' => $tax_on_sal_04[$m],
                        'tax_type' => 'RE'
                        
                    );
                }
            }
            //NRE
            $NRE = '';
            if ($emp_05 != '' && $sal_05_paid) {
                $NRE = array(
                    'emp_num' => $emp_05,
                    'salary_paid' => $sal_05_paid,
                    'tax_rate' => $tax_rate_05,
                    'tax_salary' => $tax_on_sal_05,
                    'tax_type' => 'NRE'
                );
            }
            //FB
            $FB = '';
            if ($emp_06 != '' && $sal_06_paid) {
                $FB = array(
                    'emp_num' => $emp_06,
                    'salary_paid' => $sal_06_paid,
                    'tax_rate' => $tax_rate_06,
                    'tax_salary' => $tax_on_sal_06,
                    'tax_type' => 'FB'
                );
            }
            
            //REB
            $REB = '';
            $num = sizeof($emp_back_01);
            for ($m = 0; $m < $num; $m++) {
                if ($emp_back_01[$m] != '') {
                    $REB[] = array(
                        'empcode' => $emp_back_01[$m],
                        'salary_paid' => $sal_01_paid[$m],
                        'spouse' => $spouse_back_01[$m],
                        'minor_children' => $child_back_01[$m],
                        'nationality' => $national_back_01[$m],
                        'position' => $function_back_01[$m],
                        'date_insert' => date('Y-m-d'),
                        'tax_type' => 'REB',
                        'tax_rate' => $tax_rate_back_01[$m],
                        'tax_salary' => $tax_on_sal_back_01[$m],
                        'remark' => $remark_back_01[$m]
                    );
                }
            }
            //FBB
            $FBB = '';
            $num = sizeof($emp_back_01);
            for ($m = 0; $m < $num; $m++) {
                if ($emp_back_02[$m] != '') {
                    $FBB[] = array(
                        'empcode' => $emp_back_02[$m],
                        'salary_paid' => $sal_02_benefit[$m],
                        'nationality' => $national_back_02[$m],
                        'position' => $function_back_02[$m],
                        'date_insert' => date('Y-m-d'),
                        'tax_type' => 'FBB',
                        'tax_rate' => $tax_rate_back_02[$m],
                        'tax_salary' => $tax_on_sal_back_02[$m],
                        'remark' => $remark_back_02[$m]
                    );
                }
            }
            if ($this->taxes_model->editSalaryTax($sal_tax_id, $salary_tax, $RE, $NRE, $FB, $REB, $FBB)) {
                $this->session->set_flashdata('message', lang("salary_tax_declared"));
                redirect("taxes_reports/tax_salary_list");
            } else {
                $this->session->set_flashdata('error', lang("enterprice_not_selected"));
                redirect("taxes/salary_tax");
            }
        } else {
            $this->data['users']      = $this->reports_model->getStaff();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers']    = $this->site->getAllCompanies('biller');
            $this->data['enterprise'] = $this->taxes_model->SelectEnterprise();
            $this->data['employees']  = $this->taxes_model->getAllUsers();
            
            $this->data['salary_tax'] = $this->taxes_reports_model->getSalaryTaxByID($id);
            $this->data['RES']        = $this->taxes_reports_model->getSalaryTaxFrontByID($id, 'RE');
            $this->data['NRES']       = $this->taxes_reports_model->getSalaryTaxFrontByID($id, 'NRE');
            $this->data['FBS']        = $this->taxes_reports_model->getSalaryTaxFrontByID($id, 'FB');
            $this->data['REBS']       = $this->taxes_reports_model->getSalaryTaxBackByID($id, 'REB');
            $this->data['FBBS']       = $this->taxes_reports_model->getSalaryTaxBackByID($id, 'FBB');
            //$this->erp->print_arrays($this->taxes_reports_model->getSalaryTaxBackByID($id,'REB'));
            $bc                       = array(
                array(
                    'link' => base_url(),
                    'page' => lang('home')
                ),
                array(
                    'link' => '#',
                    'page' => lang('salary_tax')
                )
            );
            $meta                     = array(
                'page_title' => lang('salary_tax'),
                'bc' => $bc
            );
            
            $this->page_construct('taxes/salary_tax_edit', $meta, $this->data);
        }
    }
    
    function getEmployeeInfo()
    {
        $emp_id = $this->input->get('emp_id', TRUE);
        
        $employee = $this->taxes_model->getEmployeeByID($emp_id);
        
        echo json_encode($employee);
    }
    
    function value_added_tax()
    {
        $this->erp->checkPermissions();
        $this->form_validation->set_rules('enterprise', lang('enterprise'), 'required');
        if ($this->form_validation->run() == true) {
            /********Save front Page of Form*********/
            $user_id               = $this->session->userdata('user_id');
            $enterpriseID          = $this->input->post('enterprise');
            $pusa_act04            = $this->input->post('pusa_act04');
            $tax_credit_premonth05 = $this->input->post('tax_credit_premonth05');
            $ncredit_purch06       = $this->input->post('ncredit_purch06');
            $strate_purch07        = $this->input->post('strate_purch07');
            $strate_purch08        = $this->input->post('strate_purch08');
            $strate_imports09      = $this->input->post('strate_imports09');
            $strate_imports10      = $this->input->post('strate_imports10');
            $total_intax11         = $this->input->post('total_intax11');
            $ntaxa_sales12         = $this->input->post('ntaxa_sales12');
            $exports13             = $this->input->post('exports13');
            $strate_sales14        = $this->input->post('strate_sales14');
            $strate_sales15        = $this->input->post('strate_sales15');
            $pay_difference16      = $this->input->post('pay_difference16');
            $refund17              = $this->input->post('refund17');
            $credit_forward18      = $this->input->post('credit_forward18');
            $startdate             = $this->input->post('st_yy') . "-" . $this->input->post('st_mm') . "-" . $this->input->post('st_dd');
            $enddate               = $this->input->post('en_yy') . "-" . $this->input->post('en_mm') . "-" . $this->input->post('en_dd');
            $createddate           = $this->input->post('cr_yy') . "-" . $this->input->post('cr_mm') . "-" . $this->input->post('cr_dd');
            $month                 = $this->input->post('cr_mm');
            $year                  = $this->input->post('cr_yy');
            
            $st_mm = $this->input->post('st_mm');
            $st_yy = $this->input->post('st_yy');
            
            $field_in_kh  = $this->input->post('kh_made_at');
            $field_in_en  = $this->input->post('en_made_at');
            $state_change = $this->input->post('state_change');
            $get_ref      = $this->site->getReference('tr');
            $saveValue    = array(
                'group_id' => $enterpriseID,
                'pusa_act04' => $pusa_act04,
                'tax_credit_premonth05' => $tax_credit_premonth05,
                'ncredit_purch06' => $ncredit_purch06,
                'strate_purch07' => $strate_purch07,
                'strate_purch08' => $strate_purch08,
                'strate_imports09' => $strate_imports09,
                'strate_imports10' => $strate_imports10,
                'total_intax11' => $total_intax11,
                'ntaxa_sales12' => $ntaxa_sales12,
                'exports13' => $exports13,
                'strate_sales14' => $strate_sales14,
                'strate_sales15' => $strate_sales15,
                'pay_difference16' => $pay_difference16,
                'refund17' => $refund17,
                'credit_forward18' => $credit_forward18,
                'covreturn_start' => $startdate,
                'covreturn_end' => $enddate,
                'created_date' => $createddate,
                'year' => $st_yy,
                'month' => $st_mm,
                'state_change' => $state_change,
                'reference_no' => $get_ref,
                'created_by' => $user_id,
                'field_in_kh' => $field_in_kh,
                'field_in_en' => $field_in_en
            );
            
            
            /********End Save front Page of Form*********/
            
            /********Save front Back Page of Form*********/
            
            //20
            $Product_1     = $this->input->post('product_1');
            $qty_1         = $this->input->post('qty_1');
            $date_1        = $this->input->post('date_1');
            $inv_declare_1 = $this->input->post('inv_declare_1');
            $suppid_1      = $this->input->post('suppid_1');
            $VAT_1         = $this->input->post('VAT_1');
            $a             = sizeof($Product_1);
            for ($i = 0; $i < $a; $i++) {
                if ($Product_1[$i] != "") {
                    $save20[] = array(
                        'productid' => $Product_1[$i],
                        'qty' => $qty_1[$i],
                        'date' => $this->erp->fld($date_1[$i]),
                        'inv_cust_desc' => $inv_declare_1[$i],
                        'supp_exp_inn' => $suppid_1[$i],
                        'val_vat' => $VAT_1[$i],
                        'type' => '20'
                    );
                    
                }
            }
            //End 20
            //21
            $Product_2     = $this->input->post('product_2');
            $qty_2         = $this->input->post('qty_2');
            $date_2        = $this->input->post('date_2');
            $inv_declare_2 = $this->input->post('inv_declare_2');
            $exp_2         = $this->input->post('exp_2');
            $exv_2         = $this->input->post('exv_2');
            $j             = sizeof($Product_2);
            for ($m = 0; $m < $j; $m++) {
                if ($Product_1[$m] != "") {
                    $save21[] = array(
                        'productid' => $Product_2[$m],
                        'qty' => $qty_2[$m],
                        'date' => $this->erp->fld($date_2[$m]),
                        'inv_cust_desc' => $inv_declare_2[$m],
                        'supp_exp_inn' => $exp_2[$m],
                        'val_vat' => $exv_2[$m],
                        'type' => '21'
                    );
                    
                }
            }
            //End 21
            //22
            $Product_3 = $this->input->post('product_3');
            $qty_3     = $this->input->post('qty_3');
            $VAT_3     = $this->input->post('VAT_3');
            $DESC_3    = $this->input->post('DESC_3');
            $INV_3     = $this->input->post('INV_3');
            $VAT2_3    = $this->input->post('VAT2_3');
            $b         = sizeof($Product_3);
            for ($u = 0; $u < $b; $u++) {
                if ($Product_3[$u] != "") {
                    $save22[] = array(
                        'productid' => $Product_3[$u],
                        'val_vat_g' => $VAT_3[$u],
                        'qty' => $qty_3[$u],
                        'inv_cust_desc' => $DESC_3[$u],
                        'supp_exp_inn' => $INV_3[$u],
                        'val_vat' => $VAT2_3[$u],
                        'type' => '22'
                    );
                    
                }
            }
            
            if ($this->taxes_model->saveValueAddedTax($saveValue, $save20, $save21, $save22, $st_mm)) {
                $this->session->set_flashdata('message', lang("return_tax_declared"));
                redirect("taxes/value_added_tax");
            } else {
                $this->session->set_flashdata('error', lang("enterprice_not_selected"));
                redirect("taxes/value_added_tax");
            }
        } else {
            
            
            $this->data['users']      = $this->reports_model->getStaff();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers']    = $this->site->getAllCompanies('biller');
            $this->data['suppid']     = $this->taxes_model->SupplierList();
            $this->data['Product']    = $this->taxes_model->getAllProducts();
            $this->data['enterprise'] = $this->taxes_model->SelectEnterprise();
            
            
            $bc   = array(
                array(
                    'link' => base_url(),
                    'page' => lang('home')
                ),
                array(
                    'link' => '#',
                    'page' => lang('value_added_tax')
                )
            );
            $meta = array(
                'page_title' => lang('value_added_tax'),
                'bc' => $bc
            );
            
            $this->page_construct('taxes/value_added_tax', $meta, $this->data);
        }
    }
    
    function value_added_tax_edit($id = NULL)
    {
        if ($this->form_validation->run() == true) {
            /********Save front Page of Form*********/
            $enterpriseID          = $this->input->post('enterprise');
            $pusa_act04            = $this->input->post('pusa_act04');
            $tax_credit_premonth05 = $this->input->post('tax_credit_premonth05');
            $ncredit_purch06       = $this->input->post('ncredit_purch06');
            $strate_purch07        = $this->input->post('strate_purch07');
            $strate_purch08        = $this->input->post('strate_purch08');
            $strate_imports09      = $this->input->post('strate_imports09');
            $strate_imports10      = $this->input->post('strate_imports10');
            $total_intax11         = $this->input->post('total_intax11');
            $ntaxa_sales12         = $this->input->post('ntaxa_sales12');
            $exports13             = $this->input->post('exports13');
            $strate_sales14        = $this->input->post('strate_sales14');
            $strate_sales15        = $this->input->post('strate_sales15');
            $pay_difference16      = $this->input->post('pay_difference16');
            $refund17              = $this->input->post('refund17');
            $credit_forward18      = $this->input->post('credit_forward18');
            $startdate             = $this->input->post('st_yy') . "-" . $this->input->post('st_mm') . "-" . $this->input->post('st_dd');
            $enddate               = $this->input->post('en_yy') . "-" . $this->input->post('en_mm') . "-" . $this->input->post('en_dd');
            $createddate           = $this->input->post('cr_yy') . "-" . $this->input->post('cr_mm') . "-" . $this->input->post('cr_dd');
            $month                 = $this->input->post('cr_mm');
            $year                  = $this->input->post('cr_yy');
            $field_in_kh           = $this->input->post('kh_made_at');
            $field_in_en           = $this->input->post('en_made_at');
            $saveValue             = array(
                'group_id' => $enterpriseID,
                'pusa_act04' => $pusa_act04,
                'tax_credit_premonth05' => $tax_credit_premonth05,
                'ncredit_purch06' => $ncredit_purch06,
                'strate_purch07' => $strate_purch07,
                'strate_purch08' => $strate_purch08,
                'strate_imports09' => $strate_imports09,
                'strate_imports10' => $strate_imports10,
                'total_intax11' => $total_intax11,
                'ntaxa_sales12' => $ntaxa_sales12,
                'exports13' => $exports13,
                'strate_sales14' => $strate_sales14,
                'strate_sales15' => $strate_sales15,
                'pay_difference16' => $pay_difference16,
                'refund17' => $refund17,
                'credit_forward18' => $credit_forward18,
                'covreturn_start' => $startdate,
                'covreturn_end' => $enddate,
                'created_date' => $createddate,
                'year' => $year,
                'month' => $month,
                'field_in_kh' => $field_in_kh,
                'field_in_en' => $field_in_en
            );
            
            
            /********End Save front Page of Form*********/
            
            /********Save front Back Page of Form*********/
            
            //20
            $Product_1     = $this->input->post('product_1');
            $qty_1         = $this->input->post('qty_1');
            $date_1        = $this->input->post('date_1');
            $inv_declare_1 = $this->input->post('inv_declare_1');
            $suppid_1      = $this->input->post('suppid_1');
            $VAT_1         = $this->input->post('VAT_1');
            $a             = sizeof($Product_1);
            for ($i = 0; $i < $a; $i++) {
                if ($Product_1[$i] != "") {
                    $save20[] = array(
                        'productid' => $Product_1[$i],
                        'qty' => $qty_1[$i],
                        'date' => date("Y-m-d", strtotime($date_1[$i])),
                        'inv_cust_desc' => $inv_declare_1[$i],
                        'supp_exp_inn' => $suppid_1[$i],
                        'val_vat' => $VAT_1[$i],
                        'type' => '20'
                    );
                    
                }
            }
            //End 20
            //21
            $Product_2     = $this->input->post('product_2');
            $qty_2         = $this->input->post('qty_2');
            $date_2        = $this->input->post('date_2');
            $inv_declare_2 = $this->input->post('inv_declare_2');
            $exp_2         = $this->input->post('exp_2');
            $exv_2         = $this->input->post('exv_2');
            $j             = sizeof($Product_2);
            for ($m = 0; $m < $j; $m++) {
                if ($Product_1[$m] != "") {
                    $save21[] = array(
                        'productid' => $Product_2[$m],
                        'qty' => $qty_2[$m],
                        'date' => date("Y-m-d", strtotime($date_2[$m])),
                        'inv_cust_desc' => $inv_declare_2[$m],
                        'supp_exp_inn' => $exp_2[$m],
                        'val_vat' => $exv_2[$m],
                        'type' => '21'
                    );
                    
                }
            }
            //End 21
            //22
            $Product_3 = $this->input->post('product_3');
            $qty_3     = $this->input->post('qty_3');
            $VAT_3     = $this->input->post('VAT_3');
            $DESC_3    = $this->input->post('DESC_3');
            $INV_3     = $this->input->post('INV_3');
            $VAT2_3    = $this->input->post('VAT2_3');
            $b         = sizeof($Product_3);
            for ($u = 0; $u < $b; $u++) {
                if ($Product_3[$u] != "") {
                    $save22[] = array(
                        'productid' => $Product_3[$u],
                        'val_vat_g' => $VAT_3[$u],
                        'qty' => $qty_3[$u],
                        'inv_cust_desc' => $DESC_3[$u],
                        'supp_exp_inn' => $INV_3[$u],
                        'val_vat' => $VAT2_3[$u],
                        'type' => '22'
                    );
                    
                }
            }
            
            if ($this->taxes_model->saveValueAddedTax($saveValue, $save20, $save21, $save22)) {
                $this->session->set_flashdata('message', lang("return_tax_declared"));
                redirect("taxes/value_added_tax");
            } else {
                $this->session->set_flashdata('error', lang("enterprice_not_selected"));
                redirect("taxes/value_added_tax");
            }
        } else {
            $this->data['users']      = $this->reports_model->getStaff();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers']    = $this->site->getAllCompanies('biller');
            $this->data['suppid']     = $this->taxes_model->SupplierList();
            $this->data['Product']    = $this->taxes_model->getAllProducts();
            $this->data['enterprise'] = $this->taxes_model->SelectEnterprise();
            
            $this->data['front']   = $this->taxes_reports_model->getInfoFrontPage($id);
            $this->data['back_20'] = $this->taxes_reports_model->getInfoBackPage($id, '20');
            $this->data['back_21'] = $this->taxes_reports_model->getInfoBackPage($id, '21');
            $this->data['back_22'] = $this->taxes_reports_model->getInfoBackPage($id, '22');
            
            $bc   = array(
                array(
                    'link' => base_url(),
                    'page' => lang('home')
                ),
                array(
                    'link' => '#',
                    'page' => lang('value_added_tax')
                )
            );
            $meta = array(
                'page_title' => lang('value_added_tax'),
                'bc' => $bc
            );
            
            $this->page_construct('taxes/value_added_tax_edit', $meta, $this->data);
        }
    }
    
    function getEnterpriceInfo()
    {
        $enterprice_info = array();
        $ent_id          = $this->input->get('ent_id', TRUE);
        $enterprice_info = $this->taxes_model->getEnterpriceByID($ent_id);
        
        
        $this->load->model('taxes_reports_model');
        $month = $this->input->get('month', TRUE);
        $year  = $this->input->get('year', TRUE);
        
        $tax_type = $this->input->get('tax_type', TRUE);
        
        $exchange_rate = $this->taxes_model->getExchangeTaxRate($month, $year);
        if ($month != '' || $year != '') {
            $date_time_for_gl_tran = $year . '-' . $month . '-31 00:00:00';
            $this->erp->checkPermissions();
            
            //$getF=$this->taxes_reports_model->get_set_forward($date_time_for_gl_tran);
            $getF               = $this->taxes_model->get_set_forward($date_time_for_gl_tran);
            $opening_balance    = 0;
            if ($getF) {
                foreach ($getF->result() as $get) {
                    $opening_balance += $get->amount;
                }
            }
            $date_time    = $year . '-' . $month . '-01 00:00:00';
            $total_input  = $this->erp->taxes_model->sum_input($date_time);
            $total_output = $this->erp->taxes_model->sum_output($date_time);
            foreach ($total_input as $t_i) {
                $get_input = $t_i->sum_amount_tax;
            }
            foreach ($total_output as $t_o) {
                $get_output = $t_o->sum_amount_tax_declare;
            }
            
            $opening_balance = abs($opening_balance);
            if (($get_input + $opening_balance) < $get_output) {
                $previous_vat = 0;
            } else {
                $previous_vat = ($get_input + $opening_balance) - $get_output;
            }
            $group_id           = NULL;
            $purc_list          = $this->taxes_reports_model->v_purch_journal_list($month, $year, $group_id, $tax_type);
            $sale_list          = $this->taxes_reports_model->v_sale_journal_list($month, $year, $group_id, $tax_type);
            $amount_for_taxable = 0;
            
            $non_tax_sale       = 0;
            $value_export       = 0;
            $amount_for_taxable = 0;
            $amount_tax_declare = 0;
            if ($sale_list) {
                foreach ($sale_list->result() as $row_sale) {
                    
                    if ($row_sale->sale_type == 2) {
                        $non_tax_sale += $row_sale->amound_declare;
                    }
                    if ($row_sale->sale_type == 1) {
                        $amount_for_taxable += $row_sale->amound_declare;
                    }
                    if ($row_sale->sale_type == 3) {
                        $value_export += $row_sale->amound_declare;
                    }
                }
            }
            $enterprice_info->ntaxa_sales12  = round($non_tax_sale * $exchange_rate->rate);
            $enterprice_info->exports13      = round($value_export * $exchange_rate->rate);
            $enterprice_info->strate_sales14 = round($amount_for_taxable * $exchange_rate->rate);
            $enterprice_info->strate_sales15 = round($amount_tax_declare * $exchange_rate->rate);
            
            
            /*foreach($purc_list->result() as $purc){
            $box6+=$purc->non_tax_pur;
            $box7+=$purc->amount_tax;
            }*/
            $PrepaymentCalculationBasefortheMonth = ($non_tax_sale + $amount_for_taxable) * $exchange_rate->rate;
            $enterprice_info->prepayment_05       = round($PrepaymentCalculationBasefortheMonth);
            $enterprice_info->prepayment_06       = round($PrepaymentCalculationBasefortheMonth * 0.01);
            if ($purc_list) {
                $S7  = 0;
                $S8  = 0;
                $S9  = 0;
                $S10 = 0;
                $S11 = 0;
                $S13 = 0;
                
                $G13 = 0;
                foreach ($purc_list->result() as $row) {
                    $P7  = 0;
                    $P8  = 0;
                    $P9  = 0;
                    $P10 = 0;
                    $P11 = 0;
                    $P13 = 0;
                    $S13 = 0;
                    if ($row->purchase_type == 1) {
                        $P10 = $row->amount_declear * $exchange_rate->rate;
                        $P11 = $row->amount_tax_declare * $exchange_rate->rate;
                        $S10 += $P10;
                        $S11 += $P11;
                        $S13 = ($P10) + ($P10 * 0.1);
                    }
                    if ($row->purchase_type == 2) {
                        $P7 = $row->amount_declear * $exchange_rate->rate;
                        $S7 += $row->amount_declear * $exchange_rate->rate;
                        $S13 = $P7;
                    }
                    if ($row->purchase_type == 3) {
                        $P8 = ($row->amount_declear * $exchange_rate->rate);
                        $P9 = ($row->amount_tax_declare * $exchange_rate->rate);
                        $S8 += ($row->amount_declear * $exchange_rate->rate);
                        $S9 += ($row->amount_tax_declare * $exchange_rate->rate);
                        $S13 = ($P8) + ($P8 * 0.1);
                    }
                    
                    
                    
                    $G13 += $S13;
                }
            }
            $box6  = $S7;
            $box7  = $S10;
            $box8  = $S11;
            $box9  = $S8;
            $box10 = $S9;
            
            
            
            $enterprice_info->previous_vat = round($previous_vat);
            $enterprice_info->in_box6      = round($box6);
            $enterprice_info->in_box7      = round($box7);
            $enterprice_info->in_box8      = round($box8);
            $enterprice_info->in_box9      = round($box9);
            $enterprice_info->in_box10     = round($box10);
            $enterprice_info->in_box11     = round(($previous_vat) + $box8 + $box10, 0);
            
            
            
            
            
            $previous_vat_plus_rate = $previous_vat;
            $total_vat_input        = $box8 + $box10;
            $total_vat_output       = $amount_tax_declare * $exchange_rate->rate;
            
            $vat_box_16_18 = abs($previous_vat_plus_rate + $total_vat_input - $total_vat_output);
            if ($previous_vat_plus_rate + $total_vat_input < $total_vat_output) {
                //echo lang('vat_payable_is'); 
                $enterprice_info->box16 = round($vat_box_16_18);
            } else {
                //echo lang('vat_credit_carried_forward_is'); 
                $enterprice_info->box18 = round($vat_box_16_18);
            }
            
            
            
            $specifictax3                    = 0;
            $specifictax10                   = 0;
            $specifictax15                   = 0;
            $specifictax20                   = 0;
            $total_accommodation_tax         = 0;
            $total_public_lighting_tax       = 0;
            $withholding_tax_on_non_resident = 0;
            $withholding_tax_on_resident1    = 0;
            $withholding_tax_on_resident2    = 0;
            $withholding_tax_on_resident3    = 0;
            $withholding_tax_on_resident4    = 0;
            $withholding_tax_on_resident5    = 0;
            
            $withholding_tax_on_non_resident1 = 0;
            $withholding_tax_on_non_resident2 = 0;
            $withholding_tax_on_non_resident3 = 0;
            $withholding_tax_on_non_resident4 = 0;
            
            $product_taxation = $this->taxes_model->getProductsMTaxation($month, $year, $tax_type);
            //$this->erp->print_arrays($product_taxation);
            foreach ($product_taxation as $ptaxation) {
                if ($ptaxation->payment_rental_immovable_property == 5 && $ptaxation->product_type == "service") {
                    $withholding_tax_on_resident5 += ($ptaxation->unit_price) * ($ptaxation->sale_item_quantity);
                }
                if ($ptaxation->specific_tax_on_certain_merchandise_and_services == 3) {
                    if ($ptaxation->sale_type == 1 && $ptaxation->tax_id == 1) {
                        $specifictax3 += ($ptaxation->unit_price / 1.1) * ($ptaxation->sale_item_quantity);
                    } else {
                        $specifictax3 += ($ptaxation->unit_price) * ($ptaxation->sale_item_quantity);
                    }
                }
                if ($ptaxation->specific_tax_on_certain_merchandise_and_services == 10) {
                    if ($ptaxation->sale_type == 1 && $ptaxation->tax_id == 1) {
                        $specifictax10 += ($ptaxation->unit_price / 1.1) * ($ptaxation->sale_item_quantity);
                    } else {
                        $specifictax10 += ($ptaxation->unit_price) * ($ptaxation->sale_item_quantity);
                    }
                }
                if ($ptaxation->specific_tax_on_certain_merchandise_and_services == 15) {
                    if ($ptaxation->sale_type == 1 && $ptaxation->tax_id == 1) {
                        $specifictax15 += ($ptaxation->unit_price / 1.1) * ($ptaxation->sale_item_quantity);
                    } else {
                        $specifictax15 += ($ptaxation->unit_price) * ($ptaxation->sale_item_quantity);
                    }
                }
                if ($ptaxation->specific_tax_on_certain_merchandise_and_services == 20) {
                    if ($ptaxation->sale_type == 1 && $ptaxation->tax_id == 1) {
                        $specifictax20 += ($ptaxation->unit_price / 1.1) * ($ptaxation->sale_item_quantity);
                    } else {
                        $specifictax20 += ($ptaxation->unit_price) * ($ptaxation->sale_item_quantity);
                    }
                }
                if ($ptaxation->accommodation_tax > 0) {
                    if ($ptaxation->sale_type == 1 && $ptaxation->tax_id == 1) {
                        $total_accommodation_tax += (($ptaxation->unit_price) / 1.1) * ($ptaxation->sale_item_quantity);
                    } else {
                        $total_accommodation_tax += ($ptaxation->unit_price) * ($ptaxation->sale_item_quantity);
                    }
                }
                if ($ptaxation->public_lighting_tax > 0) {
                    if ($ptaxation->sale_type == 1 && $ptaxation->tax_id == 1) {
                        $total_public_lighting_tax += (($ptaxation->unit_price / 1.1)) * ($ptaxation->sale_item_quantity);
                    } else {
                        $total_public_lighting_tax += ($ptaxation->unit_price) * ($ptaxation->sale_item_quantity);
                    }
                }
            }
            //$enterprice_info->total_specific_tax_on_certain_merchandise_and_services= $specifictax*$exchange_rate->rate;
            $enterprice_info->total_accommodation_tax   = ($total_accommodation_tax * $exchange_rate->rate) * ($ptaxation->sale_item_quantity);
            $enterprice_info->total_public_lighting_tax = ($total_public_lighting_tax * $exchange_rate->rate) * ($ptaxation->sale_item_quantity);
            
            $enterprice_info->specifictax3  = $specifictax3 * $exchange_rate->rate;
            $enterprice_info->specifictax10 = $specifictax10 * $exchange_rate->rate;
            $enterprice_info->specifictax15 = $specifictax15 * $exchange_rate->rate;
            $enterprice_info->specifictax20 = $specifictax20 * $exchange_rate->rate;
            
            $enterprice_info->p10 = 3;
            $enterprice_info->p12 = 10;
            $enterprice_info->p18 = 0;
            
            
            
            $purc_tax = $this->taxes_model->getPurchase_tax($month, $year, $tax_type);
            foreach ($purc_tax as $p_tax) {
                
                if ($p_tax->performance_royalty_intangible == 1) {
                    $withholding_tax_on_resident1 += ($p_tax->unit_price) * ($p_tax->qty_item);
                }
                if ($p_tax->payment_interest_non_bank == 2) {
                    $withholding_tax_on_resident2 += ($p_tax->unit_price) * ($p_tax->qty_item);
                }
                if ($p_tax->payment_interest_taxpayer_fixed == 3) {
                    $withholding_tax_on_resident3 += ($p_tax->unit_price) * ($p_tax->qty_item);
                }
                if ($p_tax->payment_interest_taxpayer_non_fixed == 4) {
                    $withholding_tax_on_resident4 += ($p_tax->unit_price) * ($p_tax->qty_item);
                }
                if ($p_tax->payment_rental_immovable_property == 5) {
                    $withholding_tax_on_resident5 += ($p_tax->unit_price) * ($p_tax->qty_item);
                }
                
                if ($p_tax->payment_of_interest == 1) {
                    $withholding_tax_on_non_resident1 += ($p_tax->unit_price) * ($p_tax->qty_item);
                }
                if ($p_tax->payment_royalty_rental_income_related == 2) {
                    $withholding_tax_on_non_resident2 += ($p_tax->unit_price) * ($p_tax->qty_item);
                }
                if ($p_tax->payment_management_technical == 3) {
                    $withholding_tax_on_non_resident3 += ($p_tax->unit_price) * ($p_tax->qty_item);
                }
                if ($p_tax->payment_dividend == 4) {
                    $withholding_tax_on_non_resident4 += ($p_tax->unit_price) * ($p_tax->qty_item);
                }
            }
            
            $purchasing_tax_journal_ref = $this->taxes_model->getPurchasing_tax_journal_ref($month, $year, $tax_type);
            
            foreach ($purchasing_tax_journal_ref as $journal_ref) {
                $j_ref              = $journal_ref->reference_no;
                $get_gl_tran_by_ref = $this->taxes_model->getGlTranByRef($j_ref);
                foreach ($get_gl_tran_by_ref as $journal_wh_tax) {
                    if ($journal_wh_tax->performance_royalty_intangible == 1) {
                        $withholding_tax_on_resident1 += ($journal_wh_tax->unit_price);
                    }
                    if ($journal_wh_tax->payment_interest_non_bank == 2) {
                        $withholding_tax_on_resident2 += ($journal_wh_tax->unit_price);
                    }
                    if ($journal_wh_tax->payment_interest_taxpayer_fixed == 3) {
                        $withholding_tax_on_resident3 += ($journal_wh_tax->unit_price);
                    }
                    if ($journal_wh_tax->payment_interest_taxpayer_non_fixed == 4) {
                        $withholding_tax_on_resident4 += ($journal_wh_tax->unit_price);
                    }
                    if ($journal_wh_tax->payment_rental_immovable_property == 5) {
                        $withholding_tax_on_resident5 += ($journal_wh_tax->unit_price);
                    }
                    
                    if ($journal_wh_tax->payment_of_interest == 1) {
                        $withholding_tax_on_non_resident1 += ($journal_wh_tax->unit_price);
                    }
                    if ($journal_wh_tax->payment_royalty_rental_income_related == 2) {
                        $withholding_tax_on_non_resident2 += ($journal_wh_tax->unit_price);
                    }
                    if ($journal_wh_tax->payment_management_technical == 3) {
                        $withholding_tax_on_non_resident3 += ($journal_wh_tax->unit_price);
                    }
                    if ($journal_wh_tax->payment_dividend == 4) {
                        $withholding_tax_on_non_resident4 += ($journal_wh_tax->unit_price);
                    }
                }
            }
            
            
            
            
            
            $enterprice_info->wr1 = $withholding_tax_on_resident1 * $exchange_rate->rate;
            $enterprice_info->wr2 = $withholding_tax_on_resident2 * $exchange_rate->rate;
            $enterprice_info->wr3 = $withholding_tax_on_resident3 * $exchange_rate->rate;
            $enterprice_info->wr4 = $withholding_tax_on_resident4 * $exchange_rate->rate;
            $enterprice_info->wr5 = $withholding_tax_on_resident5 * $exchange_rate->rate;
            
            $enterprice_info->wnr1 = $withholding_tax_on_non_resident1 * $exchange_rate->rate;
            $enterprice_info->wnr2 = $withholding_tax_on_non_resident2 * $exchange_rate->rate;
            $enterprice_info->wnr3 = $withholding_tax_on_non_resident3 * $exchange_rate->rate;
            $enterprice_info->wnr4 = $withholding_tax_on_non_resident4 * $exchange_rate->rate;
            
            
            
            
        }
        echo json_encode($enterprice_info);
    }
    
    //get salary tax data into list//
    function getDataIntoListByID()
    {
        //$dataList=array();
        $ent_id = $this->input->get('ent_id', TRUE);
        //$dataList = $this->taxes_model->getEnterpriceByID($ent_id);
        
        $this->load->model('taxes_reports_model');
        $month    = $this->input->get('month', TRUE);
        $year     = $this->input->get('year', TRUE);
        $dataList = $this->taxes_model->getDataIntoListByID($ent_id, $month, $year);
        //$this->erp->print_arrays($dataList);
        echo json_encode($dataList);
        //echo ($dataList);
    }
    
    
    /* purchasing tax */
    function purchasing_tax($warehouse_id=NULL)
    {
        $this->erp->checkPermissions();
        $this->data['purchasing_tax'] = $this->taxes_model->getPursing_tax();
        $this->data['getJournal_tax'] = $this->taxes_model->getJournal_tax();
        $this->data['modal_js']       = $this->site->modal_js();
        if (isset($_GET['d']) != "") {
            $date               = $_GET['d'];
            $this->data['date'] = $date;
        }
        $this->data['users'] = $this->reports_model->getStaff();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses']   = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse']    = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : null;
        } else {
            $this->data['warehouses']   = null;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse']    = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : null;
        }
        $bc   = array(
            array(
                'link' => base_url(),
                'page' => lang('home')
            ),
            array(
                'link' => '#',
                'page' => lang('purchasing_tax')
            )
        );
        $meta = array(
            'page_title' => lang('purchasing_tax'),
            'bc' => $bc
        );
        $this->page_construct('taxes/purchasing_tax', $meta, $this->data);
    }
    
    public function getPurchases($warehouse_id = null)
    {
        $this->erp->checkPermissions('index');
        if ($this->input->get('product')) {
            $product = $this->input->get('product');
        } else {
            $product = NULL;
        }
        if ($this->input->get('hide')) {
            $hide = $this->input->get('hide');
        } else {
            $product = 0;
        }
        if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('supplier')) {
            $supplier = $this->input->get('supplier');
        } else {
            $supplier = NULL;
        }
        if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
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
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date   = $this->erp->fld($end_date);
        }
        if ($this->input->get('note')) {
            $note = $this->input->get('note');
        } else {
            $note = NULL;
        }
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user         = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        $this->load->library('datatables');
		
        if ($warehouse_id) 
		{
            $this->datatables->select("
				erp_purchases.id,
				erp_purchases.date,
				erp_purchases.reference_no,
				supplier,
				erp_purchases.note,
				erp_purchases.`status`,
				(total-order_discount+shipping) as amount,
				order_tax,
				grand_total,
				erp_purchase_tax.amount_declear,
				erp_purchase_tax.amount_tax_declare,
				(erp_purchase_tax.amount_declear+erp_purchase_tax.amount_tax_declare) as total_declare,
				IF(erp_purchases.purchase_type = '1', 
				'Taxable Sales', 
				IF(erp_purchases.purchase_type = '2', 'Non Taxable Sales','Export')) as remark,
				tax_status")
				->from('erp_purchases') 
                ->join('erp_purchase_tax', 'erp_purchases.id = erp_purchase_tax.purchase_id', 'left')
				->where('warehouse_id', $warehouse_id);
        } 
		else 
		{
            $this->datatables->select("
					erp_purchases.id as id,
					erp_purchases.date,
					erp_purchases.reference_no,
					supplier,
					erp_purchases.note,
					erp_purchases.`status`,
					(total-order_discount+shipping) as amount,
					order_tax,
					grand_total,
					erp_purchase_tax.amount_declear,
					erp_purchase_tax.amount_tax_declare,
					(erp_purchase_tax.amount_declear+erp_purchase_tax.amount_tax_declare) as total_declare,
					IF(erp_purchases.purchase_type = '1', 
					'Taxable Sales', 
					IF(erp_purchases.purchase_type = '2', 'Non Taxable Sales','Export')) as remark,
					tax_status")
				->from('erp_purchases')
                ->join('erp_purchase_tax', 'erp_purchases.id = erp_purchase_tax.purchase_id', 'left');
				
            if (isset($_REQUEST['d'])) 
			{
                $date_c = date('Y-m-d', strtotime('+3 months'));
                $date   = $_GET['d'];
                $date1  = str_replace("/", "-", $date);
                $date   = date('Y-m-d', strtotime($date1));                
                $this->datatables->where("date >=", $date)->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()');
            }
        }
        $this->datatables->edit_column('id', '$1__$2', 'id, purchase_tax.type');
        $this->datatables->unset_column('purchase_tax.type');
        //$this->datatables->where("(erp_purchase_tax.tax_type='0' OR erp_purchase_tax.tax_type= '2')", NULL, FALSE);
		
        if ($user_query) {
            $this->datatables->where('purchases.created_by', $user_query);
        }
        if ($product) {
            $this->datatables->like('purchase_items.product_id', $product);
        }
        if ($supplier) {
            $this->datatables->where('purchases.supplier_id', $supplier);
        }
        if ($warehouse) {
            $this->datatables->where('purchases.warehouse_id', $warehouse);
        }
        if ($reference_no) {
            $this->datatables->like('purchases.reference_no', $reference_no, 'both');
        }
        if ($start_date) {
            $this->datatables->where($this->db->dbprefix('purchases') . '.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }
        if ($note) {
            $this->datatables->like('purchases.note', $note, 'both');
        }
        $action     = NULL;
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
    
    public function add_purchasing_tax_form()
    {
        $this->erp->checkPermissions();
        $this->form_validation->set_rules('date', lang('date'), 'required');
        $setting = $this->site->get_setting();
        if ($this->form_validation->run() == true) {
            
            $tax_ref            = $this->input->post('tax_ref');
            $pur_id             = $this->input->post('pur_id');
            $purchase_id        = $this->input->post('purchase_id');
            $type               = $this->input->post('ptype');
            $purchase_ref       = $this->input->post('purchase_ref');
            $supplier_id        = $this->input->post('supplier_id');
            $warehouse_id       = $this->input->post('warehouse_id');
            $issuedate          = $this->input->post('date');
            $amount             = $this->input->post('amount');
            $non_tax_pur        = $this->input->post('non_tax_pur');
            $value_import       = $this->input->post('value_import');
            $amount_tax         = $this->input->post('amount_tax');
            $amount_declare     = $this->input->post('amount_decleared');
            $amount_tax_declare = $this->input->post('amount_tax_declare');
            $purchase_type      = $this->input->post('purchase_type');
            $tax_id             = $this->input->post('tax_id');
            $c                  = count($purchase_id);
            $journal_taxes      = 0;
			
            $this->load->model('purchases_model');
			
            for ($i = 0; $i < $c; $i++) {
                $qty = 0;
                if ($purchase_id[$i] != "") {
                    $sale_items = $this->purchases_model->getItemsByPurchaseId($purchase_id[$i]);
                    foreach ($sale_items as $item) {
                        $qty += $item->quantity;
                    }
                }
                
                if ($supplier_id[$i] != "") {
                    $getSupplier = $this->purchases_model->getSupplierById($supplier_id[$i]);
                } else {
                    $getSupplier = $this->purchases_model->getSupplierById($setting->default_biller);
                }
                $vatin        = $getSupplier->vat_no;
                $purchase_tax = array(
                    'reference_no' => $purchase_ref[$i],
                    'purchase_id' => $purchase_id[$i],
                    'type' => $type[$i],
                    'purchase_ref' => $purchase_ref[$i],
                    'supplier_id' => $supplier_id[$i],
                    'group_id' => $warehouse_id[$i],
                    'issuedate' => $issuedate,
                    'amount' => $amount[$i],
                    'value_import' => $value_import[$i],
                    'non_tax_pur' => $non_tax_pur[$i],
                    'amount_tax' => $amount_tax[$i],
                    'amount_declear' => $amount_declare[$i],
                    'vatin' => $vatin,
                    'qty' => $qty,
                    'amount_tax_declare' => $amount_tax_declare[$i],
                    'purchase_type' => $purchase_type[$i],
                    'tax_type' => $purchase_type[$i],
                    'tax_id' => $tax_id[$i],
                    'status_tax' => 'confirmed'
                );
                
                $check_update = $this->taxes_model->check_update_purchase_tax($purchase_id[$i]);
                
                if ($check_update == 1) {
                    $this->taxes_model->updatePurchaseTax($purchase_id[$i], $purchase_tax);
                } else {
                    $this->taxes_model->addPurchasingTax($purchase_tax, $purchase_type[$i]);
                }
            }
            
        }
        
        if ($this->form_validation->run() == true) {
            
            $this->session->set_userdata('remove_pols', 1);
            $this->session->set_flashdata('message', $this->lang->line("purchasing_tax_added"));
            redirect('taxes/purchasing_tax');
        } else {
            $arr = array();
            if ($this->input->get('data')) {
                $arr = explode(',', $this->input->get('data'));
            }
            $purchase_taxes               = $this->taxes_model->getPurchaseTaxes($arr);
            $this->data['modal_js']       = $this->site->modal_js();
            $this->data['purchase_taxes'] = $purchase_taxes;
            $this->data['journal_taxes']  = $journal_taxes;
            $this->data['exchange_rate']  = $this->taxes_model->getExchangeRate('KHM');
            $this->load->view($this->theme . 'taxes/add_purchasing_tax_form', $this->data);
        }
    }
    
    /*selling tax*/
    function selling_tax($warehouse_id=NULL)
    {
        $this->erp->checkPermissions();
        $this->data['users']      = $this->reports_model->getStaff();
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['billers']    = $this->site->getAllCompanies('biller');
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin) {
            $this->data['warehouses']   = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse']    = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
        } else {
            $this->data['warehouses']   = NULL;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse']    = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;
        }
        
        
        $bc   = array(
            array(
                'link' => base_url(),
                'page' => lang('home')
            ),
            array(
                'link' => '#',
                'page' => lang('selling_tax')
            )
        );
        $meta = array(
            'page_title' => lang('selling_tax'),
            'bc' => $bc
        );
        
        $this->page_construct('taxes/selling_tax', $meta, $this->data);
    }
       
    public function combine_tax(){
        $this->erp->checkPermissions();
		
        $arr = array();
        if ($this->input->get('data'))
        {
            $arr = explode(',', $this->input->get('data'));
        }
		
		$this->form_validation->set_rules('date', lang("date"), 'required');
        if ($this->form_validation->run() == true) {
			
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
			
			$sale_id_arr      	 = $this->input->post('sale_id');
			$warehouse_id_arr 	 = $this->input->post('warehouse_id');
			$referen_line_arr 	 = $this->input->post('referent_line');
			$customer_id_arr  	 = $this->input->post('customer');
			$order_tax_id_arr 	 = $this->input->post('ordertax_id');
			$amount_arr          = $this->input->post('amount');
			$amount_tax_arr      = $this->input->post('amount_tax');
			$amount_declared_arr = $this->input->post('amount_decleared');
			$purchase_type 		 = $this->input->post('purchase_type');
			
			$i = 0;
			
			foreach($sale_id_arr as $sale_id){
				if($referen_line_arr[$i]!=""){
					$sale_tax = array(
								'issuedate' => $date,
								'sale_id' => $sale_id,
								'customer_id' => $customer_id_arr[$i],
								'group_id' => $warehouse_id_arr[$i],
								'tax_id' =>$order_tax_id_arr[$i],
								'amound' =>$amount_arr[$i],
								'amound_tax' => $amount_tax_arr[$i],
								'amound_declare'=>$amount_declared_arr[$i],
								'referent_no' => $referen_line_arr[$i],
								'sale_type' => $purchase_type[$i]
							);
						$this->db->where('id',$sale_id)->update('sales', array('tax_type'=>$purchase_type[$i], 'sale_type'=>$purchase_type[$i]));
						$i++;
						$this->taxes_model->addTax($sale_tax);
				}
			}
			
            
			
			$this->session->set_flashdata('message', lang("tax_added"));
            redirect('taxes/selling_tax');
		}else{
		
		$this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
		$this->data['billers'] = $this->site->getAllCompanies('biller');
        $combine_tax = $this->taxes_model->getCombineTaxById($arr);
        $this->data['combine_tax'] = $combine_tax;
        $this->data['payment_ref'] = ''; 
        $this->data['modal_js'] = $this->site->modal_js();
		$this->data['exchange_rate'] = $this->taxes_model->getExchangeRate('KHM');
		
		$this->load->view($this->theme . 'taxes/add_selling_tax', $this->data);
		
		}
	}
    
    /*get All Sales*/
    
    function getSalesReport($pdf = NULL, $xls = NULL)
    {
        $this->erp->checkPermissions('sales', TRUE);
        if ($this->input->get('product')) {
            $product = $this->input->get('product');
        } else {
            $product = NULL;
        }
        if ($this->input->get('hide')) {
            $hide = $this->input->get(hide);
        } else {
            $hide = 0;
        }
        
        if ($this->input->get('user')) {
            $user = $this->input->get('user');
        } else {
            $user = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
        if ($this->input->get('biller')) {
            $biller = $this->input->get('biller');
        } else {
            $biller = NULL;
        }
        if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get("customer_group")) {
            $customer_group = $this->input->get("customer_group");
        } else {
            $customer_group = NULL;
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
        if ($this->input->get('serial')) {
            $serial = $this->input->get('serial');
        } else {
            $serial = NULL;
        }
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date   = $this->erp->fld($end_date);
        }
        if (!$this->Owner && !$this->Admin) {
            $user = $this->session->userdata('user_id');
        }
        
        if ($pdf || $xls) {
            
            $this->db->select("
				erp_sales.id,
				date, 
				reference_no, 
				biller, 
				customer,
				payment_status,
				(grand_total-total_tax) as balance,
				total_tax,
				grand_total,
				reference_no_tax,
				tax_status", FALSE)
			->from('sales')
			->join('sale_items', 'sale_items.sale_id=sales.id', 'left')
			->join('warehouses', 'warehouses.id=sales.warehouse_id', 'left')
			->join('companies', 'companies.id=sales.customer_id', 'left')
			->join('customer_groups', 'customer_groups.id=companies.customer_group_id', 'left')
			->group_by('sales.id');
            
            
            if ($user) {
                $this->db->where('sales.created_by', $user);
            }
            if ($product) {
                $this->db->like('sale_items.product_id', $product);
            }
            if ($serial) {
                $this->db->like('sale_items.serial_no', $serial);
            }
            if ($biller) {
                $this->db->where('sales.biller_id', $biller);
            }
            if ($customer) {
                $this->db->where('sales.customer_id', $customer);
            }
            if ($customer_group) {
                $this->db->where('companies.customer_group_id', $customer_group);
            }
            if ($warehouse) {
                $this->db->where('sales.warehouse_id', $warehouse);
            }
            if ($reference_no) {
                $this->db->like('sales.reference_no', $reference_no, 'both');
            }
            if ($start_date) {
                $this->db->where($this->db->dbprefix('sales') . '.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
            
            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
            } else {
                $data = NULL;
            }
            
            if (!empty($data)) {
                
                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle(lang('tax_report'));
                $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                $this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));
                $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                $this->excel->getActiveSheet()->SetCellValue('E1', lang('payment_status'));
                $this->excel->getActiveSheet()->SetCellValue('F1', lang('amount'));
                $this->excel->getActiveSheet()->SetCellValue('G1', lang('amount_tax'));
                $this->excel->getActiveSheet()->SetCellValue('H1', lang('total_amount'));
                $this->excel->getActiveSheet()->SetCellValue('I1', lang('tax_ref_no'));
                $this->excel->getActiveSheet()->SetCellValue('J1', lang('action'));
                
                $row     = 2;
                $total   = 0;
                $paid    = 0;
                $balance = 0;
                foreach ($data as $data_row) {
                    $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($data_row->date));
                    $this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->reference_no);
                    $this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->biller);
                    $this->excel->getActiveSheet()->SetCellValue('D' . $row, $data_row->customer);
                    $this->excel->getActiveSheet()->SetCellValue('E' . $row, $data_row->payment_status);
                    $this->excel->getActiveSheet()->SetCellValue('F' . $row, $data_row->balance);
                    $this->excel->getActiveSheet()->SetCellValue('G' . $row, $data_row->total_tax);
                    $this->excel->getActiveSheet()->SetCellValue('H' . $row, $data_row->grand_total);
                    $this->excel->getActiveSheet()->SetCellValue('I' . $row, ($data_row->reference_no_tax));
                    $this->excel->getActiveSheet()->SetCellValue('J' . $row, $data_row->tax_status);
                    $total += $data_row->balance;
                    $paid += $data_row->total_tax;
                    $balance += $data_row->grand_total;
                    $row++;
                }
                $this->excel->getActiveSheet()->getStyle("G" . $row . ":I" . $row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
                $this->excel->getActiveSheet()->SetCellValue('F' . $row, $total);
                $this->excel->getActiveSheet()->SetCellValue('G' . $row, $paid);
                $this->excel->getActiveSheet()->SetCellValue('H' . $row, $balance);
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                $filename = 'sales_report';
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                if ($pdf) {
                    $styleArray = array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    );
                    $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                    $this->excel->getActiveSheet()->getStyle('F2:F' . $row)->getAlignment()->applyFromArray(array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
                    ));
                    $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                    $rendererName        = PHPExcel_Settings::PDF_RENDERER_MPDF;
                    $rendererLibrary     = 'MPDF';
                    $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                    if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                        die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' . PHP_EOL . ' as appropriate for your directory structure');
                    }
                    
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                    header('Cache-Control: max-age=0');
                    
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                    $objWriter->save('php://output');
                    exit();
                }
                if ($xls) {
                    $this->excel->getActiveSheet()->getStyle('E2:E' . $row)->getAlignment()->setWrapText(true);
                    $this->excel->getActiveSheet()->getStyle('F2:F' . $row)->getAlignment()->applyFromArray(array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                        'wrap' => true
                    ));
                    ob_clean();
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                    header('Cache-Control: max-age=0');
                    ob_clean();
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save('php://output');
                    exit();
                }
                
            }
            $this->session->set_flashdata('error', lang('nothing_found'));
            redirect($_SERVER["HTTP_REFERER"]);
            
        } else {
            $this->load->library('datatables');
            
            $this->datatables->select("
						erp_sales.id,
						date, 
						reference_no, 
						biller, 
						customer,
						(total-order_discount+shipping) as balance,
						order_tax,
						grand_total,
						sale_tax.amound_declare,
						sale_tax.amount_tax_declare,
						(erp_sale_tax.amound_declare+erp_sale_tax.amount_tax_declare) as total_declare,
						IF(erp_sales.sale_type = '1', 
						'Taxable Sales', 
						IF(erp_sales.sale_type = '2', 'Non Taxable Sales','Export')) as remark,
						tax_status", FALSE)
			->from('sales')
			->join('sale_tax', 'sale_tax.sale_id=sales.id', 'left')
			->group_by('sales.id')->order_by('date DESC');
			
            if ($hide == 0) {
                $this->datatables->where('sales.hide_tax<>', 1);
            }
            if ($user) {
                $this->datatables->where('sales.created_by', $user);
            }
            if ($product) {
                $this->datatables->like('sale_items.product_id', $product);
            }
            if ($serial) {
                $this->datatables->like('sale_items.serial_no', $serial);
            }
            if ($biller) {
                $this->datatables->where('sales.biller_id', $biller);
            }
            if ($customer) {
                $this->datatables->where('sales.customer_id', $customer);
            }
            if ($customer_group) {
                $this->datatables->where('companies.customer_group_id', $customer_group);
            }
            if ($warehouse) {
                $this->datatables->where('sales.warehouse_id', $warehouse);
            }
            if ($reference_no) {
                $this->datatables->like('sales.reference_no', $reference_no, 'both');
            }
            if ($start_date) {
                $this->datatables->where($this->db->dbprefix('sales') . '.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
            echo $this->datatables->generate();
        }
        
    }
    
    /*End Modify*/
    public function exchange_rate_tax()
    {
        $this->erp->checkPermissions('index', true, 'taxes');
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
        $this->data['condition_tax'] = $this->taxes_model->getConditionTax();
        $bc                          = array(
            array(
                'link' => base_url(),
                'page' => lang('home')
            ),
            array(
                'link' => '#',
                'page' => lang('taxes')
            )
        );
        $meta                        = array(
            'page_title' => lang('exchange_rate_tax'),
            'bc' => $bc
        );
        $this->page_construct('taxes/exchange_rate_tax', $meta, $this->data);
    }
    
	public function edit_condition_tax($id)
    {
        $this->erp->checkPermissions(false, true);
        
        $this->data['condition_tax'] = $this->taxes_model->getConditionTaxById($id);
        $this->data['error']         = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js']      = $this->site->modal_js();
        $this->load->view($this->theme . 'taxes/edit_condition_tax', $this->data);
    }
    
	function update_exchange_tax_rate($id)
    {
        $data   = array(
            'rate' => $this->input->post('rate')
        );
        $update = $this->taxes_model->update_exchange_tax_rate($id, $data);
        if ($update) {
            redirect('taxes/exchange_rate_tax');
        }
    }
    
	function prepayment_profit_tax()
    {
        $this->form_validation->set_rules('enterprise', lang("enterprise"), 'required');
        if ($this->form_validation->run() == true) {
            //front
            $covreturn_start  = $this->input->post('stY') . '-' . $this->input->post('stM') . '-' . $this->input->post('stD');
            $covreturn_end    = $this->input->post('etY') . '-' . $this->input->post('etM') . '-' . $this->input->post('etD');
            $enterprise       = $this->input->post('enterprise');
            $credit_04        = $this->input->post('credit_04');
            $prepayment_05    = $this->input->post('prepayment_05');
            $prepayment_06    = $this->input->post('prepayment_06');
            $credit_07        = $this->input->post('credit_07');
            $prepayment_08    = $this->input->post('prepayment_08');
            $specific_09      = $this->input->post('specific_09');
            $specific_10      = $this->input->post('specific_10');
            $specific_11      = $this->input->post('specific_11');
            $specific_12      = $this->input->post('specific_12');
            $accommodation_13 = $this->input->post('accommodation_13');
            $accommodation_14 = $this->input->post('accommodation_14');
            $lighting_15      = $this->input->post('lighting_15');
            $lighting_16      = $this->input->post('lighting_16');
            $other_taxes      = $this->input->post('other_taxes');
            $tax_17           = $this->input->post('tax_17');
            $tax_due_18       = $this->input->post('tax_due_18');
            $total_tax_19     = $this->input->post('total_tax_19');
            $month            = $this->input->post('createM');
            $year             = $this->input->post('createY');
            $created_date     = $year . '-' . $month . '-' . $this->input->post('createD');
            $filed_in_kh      = $this->input->post('kh_made_at');
            $filed_in_en      = $this->input->post('en_made_at');
            //SGP
            $goods_01         = $this->input->post('goods_01');
            $quantity_01      = $this->input->post('quantity_01');
            $specific_tax_01  = $this->input->post('specific_tax_01');
            $amount_tax_01    = $this->input->post('amount_tax_01');
            $invoice_01       = $this->input->post('invoice_01');
            //SS
            $goods_02         = $this->input->post('goods_02');
            $quantity_02      = $this->input->post('quantity_02');
            $specific_tax_02  = $this->input->post('specific_tax_02');
            $amount_tax_02    = $this->input->post('amount_tax_02');
            $invoice_02       = $this->input->post('invoice_02');
            $get_ref          = $this->site->getReference('tr');
            //front
            
            $stM = $this->input->post('stM');
            $stY = $this->input->post('stY');
            
            $return_tax  = array(
                'group_id' => $enterprise,
                'credit_lmonth04' => $credit_04,
                'precaba_month05' => $prepayment_05,
                'premonth_rate06' => $prepayment_06,
                'crecarry_forward07' => $credit_07,
                'preprofit_taxdue08' => $prepayment_08,
                'sptax_calbase09' => $specific_09,
                'sptax_duerate10' => $specific_10,
                'sptax_calbase11' => $specific_11,
                'sptax_duerate12' => $specific_12,
                'taxacc_calbase13' => $accommodation_13,
                'taxacc_duerate14' => $accommodation_14,
                'taxpuli_calbase15' => $lighting_15,
                'specify' => $other_taxes,
                'taxpuli_duerate16' => $lighting_16,
                'tax_calbase17' => $tax_17,
                'tax_duerate18' => $tax_due_18,
                'total_taxdue19' => $total_tax_19,
                'covreturn_start' => $covreturn_start,
                'covreturn_end' => $covreturn_end,
                'created_date' => $created_date,
                'created_by' => $this->session->userdata('user_id'),
                'reference_no' => $get_ref,
                'year' => $stY,
                'month' => $stM,
                'filed_in_kh' => $filed_in_kh,
                'filed_in_en' => $filed_in_en,
                'tax_type' => 2
            );
            //SGP
            $counter_SGP = sizeof($goods_01);
            for ($i = 0; $i < $counter_SGP; $i++) {
                if ($goods_01[$i] != '') {
                    $SGP[] = array(
                        'orderlineno' => $i,
                        'itemcode' => $goods_01[$i],
                        'quantity' => $quantity_01[$i],
                        'specific_tax' => $specific_tax_01[$i],
                        'amount_tax' => $amount_tax_01[$i],
                        'inv_num' => $invoice_01[$i],
                        'type' => 'SGP'
                    );
                }
            }
            //SS
            $counter_SS = sizeof($goods_02);
            for ($j = 0; $j < $counter_SS; $j++) {
                if ($goods_02[$j] != '') {
                    $SS[] = array(
                        'orderlineno' => $j,
                        'itemcode' => $goods_02[$j],
                        'quantity' => $quantity_02[$j],
                        'specific_tax' => $specific_tax_02[$j],
                        'amount_tax' => $amount_tax_02[$j],
                        'inv_num' => $invoice_02[$j],
                        'type' => 'SS'
                    );
                }
            }
            if ($this->taxes_model->addReturnTax($return_tax, $SGP, $SS)) {
                $this->session->set_flashdata('message', lang("return_tax_added"));
                redirect("taxes/prepayment_profit_tax");
            } else {
                $this->session->set_flashdata('error', lang("enterprice_cannot_add"));
                redirect("taxes/prepayment_profit_tax");
            }
        } else {
            //$this->erp->print_arrays($this->taxes_model->getProductsMTaxation());
            $this->data['enterprise'] = $this->taxes_model->SelectEnterprise();
            $this->data['products']   = $this->taxes_model->getAllProducts();
            $bc                       = array(
                array(
                    'link' => base_url(),
                    'page' => lang('home')
                ),
                array(
                    'link' => '#',
                    'page' => lang('prepayment_profit_tax')
                )
            );
            $meta                     = array(
                'page_title' => lang('prepayment_profit_tax'),
                'bc' => $bc
            );
            
            $this->page_construct('taxes/prepayment_profit_tax', $meta, $this->data);
        }
    }
    
    function prepayment_profit_tax_state_charge()
    {
        $this->form_validation->set_rules('enterprise', lang("enterprise"), 'required');
        if ($this->form_validation->run() == true) {
            //front
            $covreturn_start  = $this->input->post('stY') . '-' . $this->input->post('stM') . '-' . $this->input->post('stD');
            $covreturn_end    = $this->input->post('etY') . '-' . $this->input->post('etM') . '-' . $this->input->post('etD');
            $enterprise       = $this->input->post('enterprise');
            $credit_04        = $this->input->post('credit_04');
            $prepayment_05    = $this->input->post('prepayment_05');
            $prepayment_06    = $this->input->post('prepayment_06');
            $credit_07        = $this->input->post('credit_07');
            $prepayment_08    = $this->input->post('prepayment_08');
            $specific_09      = $this->input->post('specific_09');
            $specific_10      = $this->input->post('specific_10');
            $specific_11      = $this->input->post('specific_11');
            $specific_12      = $this->input->post('specific_12');
            $accommodation_13 = $this->input->post('accommodation_13');
            $accommodation_14 = $this->input->post('accommodation_14');
            $lighting_15      = $this->input->post('lighting_15');
            $lighting_16      = $this->input->post('lighting_16');
            $other_taxes      = $this->input->post('other_taxes');
            $tax_17           = $this->input->post('tax_17');
            $tax_due_18       = $this->input->post('tax_due_18');
            $total_tax_19     = $this->input->post('total_tax_19');
            $month            = $this->input->post('createM');
            $year             = $this->input->post('createY');
            $created_date     = $year . '-' . $month . '-' . $this->input->post('createD');
            $filed_in_kh      = $this->input->post('kh_made_at');
            $filed_in_en      = $this->input->post('en_made_at');
            //SGP
            $goods_01         = $this->input->post('goods_01');
            $quantity_01      = $this->input->post('quantity_01');
            $specific_tax_01  = $this->input->post('specific_tax_01');
            $amount_tax_01    = $this->input->post('amount_tax_01');
            $invoice_01       = $this->input->post('invoice_01');
            //SS
            $goods_02         = $this->input->post('goods_02');
            $quantity_02      = $this->input->post('quantity_02');
            $specific_tax_02  = $this->input->post('specific_tax_02');
            $amount_tax_02    = $this->input->post('amount_tax_02');
            $invoice_02       = $this->input->post('invoice_02');
            $get_ref          = $this->site->getReference('tr');
            //front
            $return_tax       = array(
                'group_id' => $enterprise,
                'credit_lmonth04' => $credit_04,
                'precaba_month05' => $prepayment_05,
                'premonth_rate06' => $prepayment_06,
                'crecarry_forward07' => $credit_07,
                'preprofit_taxdue08' => $prepayment_08,
                'sptax_calbase09' => $specific_09,
                'sptax_duerate10' => $specific_10,
                'sptax_calbase11' => $specific_11,
                'sptax_duerate12' => $specific_12,
                'taxacc_calbase13' => $accommodation_13,
                'taxacc_duerate14' => $accommodation_14,
                'taxpuli_calbase15' => $lighting_15,
                'specify' => $other_taxes,
                'taxpuli_duerate16' => $lighting_16,
                'tax_calbase17' => $tax_17,
                'tax_duerate18' => $tax_due_18,
                'total_taxdue19' => $total_tax_19,
                'covreturn_start' => $covreturn_start,
                'covreturn_end' => $covreturn_end,
                'created_date' => $created_date,
                'created_by' => $this->session->userdata('user_id'),
                'reference_no' => $get_ref,
                'year' => $year,
                'month' => $month,
                'filed_in_kh' => $filed_in_kh,
                'filed_in_en' => $filed_in_en,
                'tax_type' => 3
            );
            //SGP
            $counter_SGP      = sizeof($goods_01);
            for ($i = 0; $i < $counter_SGP; $i++) {
                if ($goods_01[$i] != '') {
                    $SGP[] = array(
                        'orderlineno' => $i,
                        'itemcode' => $goods_01[$i],
                        'quantity' => $quantity_01[$i],
                        'specific_tax' => $specific_tax_01[$i],
                        'amount_tax' => $amount_tax_01[$i],
                        'inv_num' => $invoice_01[$i],
                        'type' => 'SGP'
                    );
                }
            }
            //SS
            $counter_SS = sizeof($goods_02);
            for ($j = 0; $j < $counter_SS; $j++) {
                if ($goods_02[$j] != '') {
                    $SS[] = array(
                        'orderlineno' => $j,
                        'itemcode' => $goods_02[$j],
                        'quantity' => $quantity_02[$j],
                        'specific_tax' => $specific_tax_02[$j],
                        'amount_tax' => $amount_tax_02[$j],
                        'inv_num' => $invoice_02[$j],
                        'type' => 'SS'
                    );
                }
            }
            if ($this->taxes_model->addReturnTax($return_tax, $SGP, $SS)) {
                $this->session->set_flashdata('message', lang("return_tax_added"));
                redirect("taxes/prepayment_profit_tax_state_charge");
            } else {
                $this->session->set_flashdata('error', lang("enterprice_cannot_add"));
                redirect("taxes/prepayment_profit_tax_state_charge");
            }
        } else {
            $this->data['enterprise'] = $this->taxes_model->SelectEnterprise();
            $this->data['products']   = $this->taxes_model->getAllProducts();
            
            $bc   = array(
                array(
                    'link' => base_url(),
                    'page' => lang('home')
                ),
                array(
                    'link' => '#',
                    'page' => lang('prepayment_profit_tax_state_charge')
                )
            );
            $meta = array(
                'page_title' => lang('prepayment_profit_tax_state_charge'),
                'bc' => $bc
            );
            
            $this->page_construct('taxes/prepayment_profit_tax_state_charge', $meta, $this->data);
        }
    }
    
    function selling_tax_manual()
    {
        $this->erp->checkPermissions();
        
        $this->form_validation->set_rules('enterprise', lang("enterprise"), 'required');
        if ($this->form_validation->run() == true) {
            $enterprise    = $this->input->post('enterprise');
            $exchange_rate = $this->input->post('exc_rate');
            $date          = $this->input->post('s1');
            $invoice       = $this->input->post('s2');
            $client        = $this->input->post('s3');
            $vatin         = $this->input->post('s4');
            $description   = $this->input->post('s5');
            $qty           = $this->input->post('s6');
            $non_tax_sal   = $this->input->post('s7');
            $val_exp       = $this->input->post('s8');
            $tax_val_9     = $this->input->post('s9');
            $vat_10        = $this->input->post('s10');
            $tax_val_11    = $this->input->post('s11');
            $tax_12        = $this->input->post('s12');
            
            $invoices_num = sizeof($invoice);
            for ($j = 0; $j < $invoices_num; $j++) {
                if ($invoice[$j] != '') {
                    $invoices = array(
                        'group_id' => $enterprise,
                        'sale_id' => '',
                        'customer_id' => $client[$j],
                        'issuedate' => $date[$j],
                        'vatin' => $vatin[$j],
                        'description' => $description[$j],
                        'qty' => $qty[$j],
                        'non_tax_sale' => $non_tax_sal[$j],
                        'value_export' => $val_exp[$j],
                        'amound' => ($tax_val_9[$j] / $exchange_rate),
                        'amound_tax' => ($vat_10[$j] / $exchange_rate),
                        'amound_declare' => $vat_10[$j],
                        'tax_value' => $tax_val_11[$j],
                        'vat' => $tax_12[$j],
                        'tax_id' => '',
                        'referent_no' => $invoice[$j]
                    );
                    $this->taxes_model->addTax($invoices);
                }
            }
            $this->session->set_flashdata('message', lang("tax_added"));
            redirect('taxes_reports/sales_journal_list');
        } else {
            $this->data['enterprise']    = $this->taxes_model->SelectEnterprise();
            $this->data['exchange_rate'] = $this->taxes_model->getExchangeRate('KHM');
            $this->data['modal_js']      = $this->site->modal_js();
            
            $this->load->view($this->theme . 'taxes/selling_tax_manual', $this->data);
        }
    }
    
    function getChartAccountTaxes()
    {
        $this->erp->checkPermissions('index', true, 'taxes');
        
        $this->load->library('datatables');
        $this->datatables->select("(erp_gl_charts_tax.accountcode) as id,erp_gl_charts_tax.accountcode, erp_gl_charts_tax.accountname, erp_gl_charts_tax.accountname_kh, erp_gl_sections.sectionname")->from("erp_gl_charts_tax")->join("erp_gl_sections", "erp_gl_charts_tax.sectionid=erp_gl_sections.sectionid", "INNER")->add_column("Actions", "<center><a class=\"tip\" title='" . $this->lang->line("edit_taxes") . "' href='" . site_url('taxes/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a>  <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_taxes") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('taxes/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "erp_gl_charts_tax.accountcode");
        //->unset_column('id');
        echo $this->datatables->generate();
    }
    
    function add()
    {
        $this->erp->checkPermissions(false, true);
        
        $this->form_validation->set_rules('accountcode', $this->lang->line("accountcode"), 'is_unique[charts_tax.accountcode]');
        
        if ($this->form_validation->run('taxes/add') == true) {
            
            $data = array(
                'accountcode' => $this->input->post('account_code'),
                'accountname' => $this->input->post('account_name'),
                'accountname_kh' => $this->input->post('account_name_kh'),
                'sectionid' => $this->input->post('account_section')
            );
            //$this->erp->print_arrays($data);
        }
        
        if ($this->form_validation->run() == true && $this->taxes_model->addChartAccount($data)) {
            $this->session->set_flashdata('message', $this->lang->line("tax_added"));
            redirect('taxes');
        } else {
            $this->data['sectionacc'] = $this->taxes_model->getAccountSections();
            $this->data['error']      = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js']   = $this->site->modal_js();
            $this->load->view($this->theme . 'taxes/add', $this->data);
        }
    }
    
    function edit($id = NULL)
    {
        $this->erp->checkPermissions(false, true);
        
        $this->form_validation->set_rules('accountcode', $this->lang->line("accountcode"), 'is_unique[charts_tax.accountcode]');
        
        if ($this->form_validation->run('taxes/edit') == true) {
            
            $data = array(
                'accountcode' => $this->input->post('account_code'),
                'accountname' => $this->input->post('account_name'),
                'accountname_kh' => $this->input->post('account_name_kh'),
                'sectionid' => $this->input->post('account_section')
            );
            //$this->erp->print_arrays($data);
            $idd  = $this->input->post('id');
        }
        
        if ($this->form_validation->run() == true && $this->taxes_model->updateChartAccount($idd, $data)) {
            $this->session->set_flashdata('message', $this->lang->line("tax_updated"));
            redirect('taxes');
        } else {
            $chart_acc_details = $this->taxes_model->getChartAccountByID($id);
            $section_id        = $chart_acc_details->sectionid;
            
            $this->data['supplier']   = $chart_acc_details;
            $this->data['sectionacc'] = $this->taxes_model->getAccountSections();
            $this->data['id']         = $id;
            $this->data['error']      = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['modal_js']   = $this->site->modal_js();
            $this->load->view($this->theme . 'taxes/edit', $this->data);
        }
    }
    
    function delete($id = NULL)
    {
        $this->erp->checkPermissions(NULL, TRUE);
        
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        
        if ($this->taxes_model->deleteChartAccount($id)) {
            echo $this->lang->line("deleted_chart_account_tax");
        } else {
            $this->session->set_flashdata('warning', lang('chart_account_x_deleted_have_account'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }
    
    function taxes_actions()
    {
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        
        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
        
        if ($this->form_validation->run() == true) {
            
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    
                    $error = false;
                    foreach ($_POST['val'] as $id) {
                        if (!$this->taxes_model->deleteChartAccount($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('suppliers_x_deleted_have_purchases'));
                    } else {
                        $this->session->set_flashdata('message', $this->lang->line("tax_deleted_successfully"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                
                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
                    
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('tax'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('account_code'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('account_name'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('account_name_kh'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('account_section'));
                    
                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $tax = $this->site->getTaxByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $tax->accountcode);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $tax->accountname);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $tax->accountname_kh);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $tax->sectionname);
                        $row++;
                    }
                    
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'Account_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array(
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        );
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName        = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary     = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' . PHP_EOL . ' as appropriate for your directory structure');
                        }
                        
                        header('Content-Type: application/pdf');
                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                        header('Cache-Control: max-age=0');
                        
                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');
                        
                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }
                    
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_supplier_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    
    function purchasing_tax_manual()
    {
        $this->erp->checkPermissions();
        
        $this->form_validation->set_rules('enterprise', lang("enterprise"), 'required');
        if ($this->form_validation->run() == true) {
            $enterprise    = $this->input->post('enterprise');
            $exchange_rate = $this->input->post('exc_rate');
            $date          = $this->input->post('s1');
            $invoice       = $this->input->post('s2');
            $client        = $this->input->post('s3');
            $vatin         = $this->input->post('s4');
            $description   = $this->input->post('s5');
            $qty           = $this->input->post('s6');
            $non_tax_pur   = $this->input->post('s7');
            $tax_val_9     = $this->input->post('s9');
            $vat_10        = $this->input->post('s10');
            $tax_val_11    = $this->input->post('s11');
            $tax_12        = $this->input->post('s12');
            
            $invoices_num = sizeof($invoice);
            for ($i = 0; $i < $invoices_num; $i++) {
                if ($invoice[$i] != '') {
                    $purchase_tax = array(
                        'group_id' => $enterprise,
                        'reference_no' => $invoice[$i],
                        'purchase_id' => '',
                        'purchase_ref' => '',
                        'supplier_id' => $client[$i],
                        'issuedate' => $date[$i],
                        'description' => $description[$i],
                        'qty' => $qty[$i],
                        'vatin' => $vatin[$i],
                        'non_tax_pur' => $non_tax_pur[$i],
                        'amount' => ($tax_val_9[$i] / $exchange_rate),
                        'amount_tax' => ($vat_10[$i] / $exchange_rate),
                        'amount_declear' => $vat_10[$i],
                        'tax_value' => $tax_val_11[$i],
                        'vat' => $tax_12[$i],
                        'tax_id' => ''
                    );
                    $this->taxes_model->addPurchasingTax($purchase_tax);
                }
            }
            $this->session->set_flashdata('message', lang("tax_added"));
            redirect('taxes_reports/purchase_journal_list');
        } else {
            $this->data['enterprise']    = $this->taxes_model->SelectEnterprise();
            $this->data['billers']       = $this->site->getAllCompanies('biller');
            $this->data['exchange_rate'] = $this->taxes_model->getExchangeRate('KHM');
            $this->data['modal_js']      = $this->site->modal_js();
            
            $this->load->view($this->theme . 'taxes/purchasing_tax_manual', $this->data);
        }
    }
    
    function getExchangeRateTax()
    {
        $this->erp->checkPermissions();
        $this->load->library('datatables');
        $this->datatables->select("id, usd, salary_khm, average_khm, month, year")->from("tax_exchange_rate")->add_column("Actions", "<center><a class=\"tip\" title='" . $this->lang->line("edit_exhangerate_tax") . "' href='" . site_url('taxes/edit_exhangerate_tax/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a>  <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_condition_tax") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('taxes/delete_exchangerate_tax/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></center>", "id");
        echo $this->datatables->generate();
    }
    
    function add_exchangerate_tax()
    {
        $this->erp->checkPermissions();
        $this->data['error']    = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $this->load->view($this->theme . 'taxes/add_exchangerate_tax', $this->data);
    }
    
    function tax_return_for_small_tax_payers()
    {
        
        $this->erp->checkPermissions();
        $this->data['error']    = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['modal_js'] = $this->site->modal_js();
        $bc                     = array(
            array(
                'link' => base_url(),
                'page' => lang('home')
            ),
            array(
                'link' => '#',
                'page' => lang('tax_return_for_small_tax_payers')
            )
        );
        $meta                   = array(
            'page_title' => lang('tax_return_for_small_tax_payers'),
            'bc' => $bc
        );
        $this->page_construct('taxes/tax_return_for_small_tax_payers', $meta, $this->data);
    }
    
    public function edit_selling_tax()
    {
        $this->erp->checkPermissions();
        
        $arr = array();
        if ($this->input->get('data')) {
            $arr = explode(',', $this->input->get('data'));
        }
        $this->data['error']         = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $this->data['billers']       = $this->site->getAllCompanies('biller');
        $combine_tax                 = $this->taxes_model->getCombineTaxByIdForEdit($arr);
        $this->data['combine_tax']   = $combine_tax;
        $this->data['payment_ref']   = '';
        $this->data['modal_js']      = $this->site->modal_js();
        $this->data['exchange_rate'] = $this->taxes_model->getExchangeRate('KHM');
        $this->load->view($this->theme . 'taxes/edit_selling_tax', $this->data);
    }
    
	public function update_selling_tax($id)
    {
        $this->erp->checkPermissions();
        $this->taxes_model->updateSellingTaxStatus($id);
    }
    
    public function updateSaleTax()
    {
        if ($this->Owner || $this->Admin) {
            $date = $this->erp->fld(trim($this->input->post('date')));
        } else {
            $date = date('Y-m-d H:i:s');
        }
        
        $sale_id_arr            = $this->input->post('sale_id');
        $warehouse_id_arr       = $this->input->post('warehouse_id');
        $referen_line_arr       = $this->input->post('referent_line');
        $customer_id_arr        = $this->input->post('customer');
        $order_tax_id_arr       = $this->input->post('ordertax_id');
        $amount_arr             = $this->input->post('amount');
        $amount_tax_arr         = $this->input->post('vatbox');
        $non_taxable_sale_arr   = $this->input->post('non_taxable');
        $export_arr             = $this->input->post('export');
        $amount_tax_declare_arr = $this->input->post('vatbox_declare');
        $amount_declared_arr    = $this->input->post('amount_declare');
        $i                      = 0;
        
        foreach ($sale_id_arr as $sale_id) {
            //if($referen_line_arr[$i]!=""){
            $sale_tax = array(
                'issuedate' => $date,
                'sale_id' => $sale_id,
                'customer_id' => $customer_id_arr[$i],
                'group_id' => $warehouse_id_arr[$i],
                'tax_id' => $order_tax_id_arr[$i],
                'amound' => $amount_arr[$i],
                'amound_tax' => $amount_tax_arr[$i],
                'amound_declare' => $amount_declared_arr[$i],
                'amount_tax_declare' => $amount_tax_declare_arr[$i],
                'referent_no' => $referen_line_arr[$i],
                'non_tax_sale' => $non_taxable_sale_arr[$i],
                'value_export' => $export_arr[$i]
            );
            //print_r($sale_tax);exit;
            $i++;
            $this->taxes_model->updateSaleTax($sale_id, $sale_tax);
            
        }
        
        
        
        $this->session->set_flashdata('message', lang("tax_updated"));
        redirect('taxes/selling_tax', 'refresh');
    }
    
    public function remove_selling_tax()
    {
        $this->erp->checkPermissions();
        
        $arr = array();
        
        if ($this->input->get('data')) {
            $arr = explode(',', $this->input->get('data'));
            
        }
        
        $remove_combine_tax = $this->taxes_model->updateSellingTaxStatus($arr);
        
        if ($remove_combine_tax) {
            redirect('welcome', 'refresh');
        }
    }
    
	function selling_tax_action()
    {
        $ids         = $this->input->post('val');
        $form_adtion = $this->input->post('form_action');
        
        
        
        if ($form_adtion == 'hide_selling_tax') {
            foreach ($ids as $id) {
                $this->taxes_model->hdie_selling_tax($id);
            }
            $this->session->set_flashdata('message', lang("selling_tax_hiddden"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if ($form_adtion == 'delete') {
            foreach ($ids as $id) {
                $this->taxes_model->updateSellingTaxStatusById($id);
            }
            $this->session->set_flashdata('message', lang("selling_tax_undeclared"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        
        if ($form_adtion == 'declare') {
            foreach ($ids as $id) {
                $combine_tax = $this->taxes_model->getCombineTaxById($id);
                foreach ($combine_tax->result() as $combine_taxes) {
                    $sale_type    = $combine_taxes->sale_type;
                    $order_tax    = $combine_taxes->order_tax;
                    $amount       = $combine_taxes->balance;
                    $vat          = $combine_taxes->total_tax;
                    $sale_id      = $combine_taxes->id;
                    $warehouse_id = $combine_taxes->warehouse_id;
                    $customer     = $combine_taxes->customer;
                    $reference_no = $combine_taxes->reference_no;
                    if ($sale_type == 1) {
                        if ($vat <= 0) {
                            $vat_declare    = ($amount / 1.1) * (0.1);
                            $amount_declare = $amount - $vat_declare;
                            $value_export   = 0;
                            $non_tax_sale   = 0;
                        } else {
                            $vat_declare    = $vat;
                            $amount_declare = $amount;
                            $value_export   = 0;
                            $non_tax_sale   = 0;
                        }
                    }
                    if ($sale_type == 2) {
                        $non_tax_sale   = $amount;
                        $amount         = 0;
                        $value_export   = 0;
                        $vat            = 0;
                        $amount_declare = 0;
                        $vat_declare    = 0;
                    }
                    if ($sale_type == 3) {
                        $value_export   = $amount;
                        $amount         = 0;
                        $non_tax_sale   = 0;
                        $vat            = 0;
                        $amount_declare = 0;
                        $vat_declare    = 0;
                    }
                    $customer_id = $combine_taxes->customer_id;
                    $getCustomer = $this->taxes_model->getCustomerById($customer_id);
                    $vatin       = $getCustomer->vat_no;
                    //echo $vat_declare.'-'.$amount_declare.' / '.$reference_no;
                    //echo ' ||<br/>';
                    $sale_tax    = array(
                        'issuedate' => date("Y-m-d h:i:s"),
                        'sale_id' => $sale_id,
                        'customer_id' => $combine_taxes->customer,
                        'group_id' => $combine_taxes->warehouse_id,
                        'tax_id' => $combine_taxes->order_tax_id,
                        'amound' => $amount,
                        'amound_tax' => $vat,
                        'amound_declare' => $amount_declare,
                        'amount_tax_declare' => $vat_declare,
                        'referent_no' => $reference_no,
                        'non_tax_sale' => $non_tax_sale,
                        'vatin' => $vatin,
                        'value_export' => $value_export
                    );
                    $this->taxes_model->declare_tax($sale_tax);
                    
                    
                    
                }
            }
            $this->session->set_flashdata('message', lang("tax_added"));
            redirect('taxes/selling_tax', 'refresh');
        }
        
        redirect('taxes/selling_tax');
    }
    
    function value_added_tax_state_change()
    {
        $this->erp->checkPermissions();
        
        $this->form_validation->set_rules('enterprise', lang('enterprise'), 'required');
        
        if ($this->form_validation->run() == true) {
            /********Save front Page of Form*********/
            $enterpriseID          = $this->input->post('enterprise');
            $pusa_act04            = $this->input->post('pusa_act04');
            $tax_credit_premonth05 = $this->input->post('tax_credit_premonth05');
            $ncredit_purch06       = $this->input->post('ncredit_purch06');
            $strate_purch07        = $this->input->post('strate_purch07');
            $strate_purch08        = $this->input->post('strate_purch08');
            $strate_imports09      = $this->input->post('strate_imports09');
            $strate_imports10      = $this->input->post('strate_imports10');
            $total_intax11         = $this->input->post('total_intax11');
            $ntaxa_sales12         = $this->input->post('ntaxa_sales12');
            $exports13             = $this->input->post('exports13');
            $strate_sales14        = $this->input->post('strate_sales14');
            $strate_sales15        = $this->input->post('strate_sales15');
            $pay_difference16      = $this->input->post('pay_difference16');
            $refund17              = $this->input->post('refund17');
            $credit_forward18      = $this->input->post('credit_forward18');
            $startdate             = $this->input->post('st_yy') . "-" . $this->input->post('st_mm') . "-" . $this->input->post('st_dd');
            $enddate               = $this->input->post('en_yy') . "-" . $this->input->post('en_mm') . "-" . $this->input->post('en_dd');
            $createddate           = $this->input->post('cr_yy') . "-" . $this->input->post('cr_mm') . "-" . $this->input->post('cr_dd');
            $month                 = $this->input->post('cr_mm');
            $year                  = $this->input->post('cr_yy');
            $field_in_kh           = $this->input->post('kh_made_at');
            $field_in_en           = $this->input->post('en_made_at');
            $state_change          = $this->input->post('state_change');
            $saveValue             = array(
                'group_id' => $enterpriseID,
                'pusa_act04' => $pusa_act04,
                'tax_credit_premonth05' => $tax_credit_premonth05,
                'ncredit_purch06' => $ncredit_purch06,
                'strate_purch07' => $strate_purch07,
                'strate_purch08' => $strate_purch08,
                'strate_imports09' => $strate_imports09,
                'strate_imports10' => $strate_imports10,
                'total_intax11' => $total_intax11,
                'ntaxa_sales12' => $ntaxa_sales12,
                'exports13' => $exports13,
                'strate_sales14' => $strate_sales14,
                'strate_sales15' => $strate_sales15,
                'pay_difference16' => $pay_difference16,
                'refund17' => $refund17,
                'credit_forward18' => $credit_forward18,
                'covreturn_start' => $startdate,
                'covreturn_end' => $enddate,
                'created_date' => $createddate,
                'year' => $year,
                'month' => $month,
                'state_change' => $state_change,
                'field_in_kh' => $field_in_kh,
                'field_in_en' => $field_in_en
            );
            
            
            /********End Save front Page of Form*********/
            
            /********Save front Back Page of Form*********/
            
            //20
            $Product_1     = $this->input->post('product_1');
            $qty_1         = $this->input->post('qty_1');
            $date_1        = $this->input->post('date_1');
            $inv_declare_1 = $this->input->post('inv_declare_1');
            $suppid_1      = $this->input->post('suppid_1');
            $VAT_1         = $this->input->post('VAT_1');
            $a             = sizeof($Product_1);
            for ($i = 0; $i < $a; $i++) {
                if ($Product_1[$i] != "") {
                    $save20[] = array(
                        'productid' => $Product_1[$i],
                        'qty' => $qty_1[$i],
                        'date' => date("Y-m-d", strtotime($date_1[$i])),
                        'inv_cust_desc' => $inv_declare_1[$i],
                        'supp_exp_inn' => $suppid_1[$i],
                        'val_vat' => $VAT_1[$i],
                        'type' => '20'
                    );
                    
                }
            }
            //End 20
            //21
            $Product_2     = $this->input->post('product_2');
            $qty_2         = $this->input->post('qty_2');
            $date_2        = $this->input->post('date_2');
            $inv_declare_2 = $this->input->post('inv_declare_2');
            $exp_2         = $this->input->post('exp_2');
            $exv_2         = $this->input->post('exv_2');
            $j             = sizeof($Product_2);
            for ($m = 0; $m < $j; $m++) {
                if ($Product_1[$m] != "") {
                    $save21[] = array(
                        'productid' => $Product_2[$m],
                        'qty' => $qty_2[$m],
                        'date' => date("Y-m-d", strtotime($date_2[$m])),
                        'inv_cust_desc' => $inv_declare_2[$m],
                        'supp_exp_inn' => $exp_2[$m],
                        'val_vat' => $exv_2[$m],
                        'type' => '21'
                    );
                    
                }
            }
            //End 21
            //22
            $Product_3 = $this->input->post('product_3');
            $qty_3     = $this->input->post('qty_3');
            $VAT_3     = $this->input->post('VAT_3');
            $DESC_3    = $this->input->post('DESC_3');
            $INV_3     = $this->input->post('INV_3');
            $VAT2_3    = $this->input->post('VAT2_3');
            $b         = sizeof($Product_3);
            for ($u = 0; $u < $b; $u++) {
                if ($Product_3[$u] != "") {
                    $save22[] = array(
                        'productid' => $Product_3[$u],
                        'val_vat_g' => $VAT_3[$u],
                        'qty' => $qty_3[$u],
                        'inv_cust_desc' => $DESC_3[$u],
                        'supp_exp_inn' => $INV_3[$u],
                        'val_vat' => $VAT2_3[$u],
                        'type' => '22'
                    );
                    
                }
            }
            
            if ($this->taxes_model->saveValueAddedTax($saveValue, $save20, $save21, $save22)) {
                $this->session->set_flashdata('message', lang("return_tax_declared"));
                redirect("taxes/value_added_tax_state_change");
            } else {
                $this->session->set_flashdata('error', lang("enterprice_not_selected"));
                redirect("taxes/value_added_tax_state_change");
            }
        } else {
            
            
            $this->data['users']      = $this->reports_model->getStaff();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers']    = $this->site->getAllCompanies('biller');
            $this->data['suppid']     = $this->taxes_model->SupplierList();
            $this->data['Product']    = $this->taxes_model->getAllProducts();
            $this->data['enterprise'] = $this->taxes_model->SelectEnterprise();
            
            
            $bc   = array(
                array(
                    'link' => base_url(),
                    'page' => lang('home')
                ),
                array(
                    'link' => '#',
                    'page' => lang('value_added_tax_state_change')
                )
            );
            $meta = array(
                'page_title' => lang('value_added_tax_state_change'),
                'bc' => $bc
            );
            
            $this->page_construct('taxes/value_added_tax_state_change', $meta, $this->data);
        }
    }
    
    function value_added_tax_state_change_edit($id = NULL)
    {
        if ($this->form_validation->run() == true) {
            /********Save front Page of Form*********/
            $enterpriseID          = $this->input->post('enterprise');
            $pusa_act04            = $this->input->post('pusa_act04');
            $tax_credit_premonth05 = $this->input->post('tax_credit_premonth05');
            $ncredit_purch06       = $this->input->post('ncredit_purch06');
            $strate_purch07        = $this->input->post('strate_purch07');
            $strate_purch08        = $this->input->post('strate_purch08');
            $strate_imports09      = $this->input->post('strate_imports09');
            $strate_imports10      = $this->input->post('strate_imports10');
            $total_intax11         = $this->input->post('total_intax11');
            $ntaxa_sales12         = $this->input->post('ntaxa_sales12');
            $exports13             = $this->input->post('exports13');
            $strate_sales14        = $this->input->post('strate_sales14');
            $strate_sales15        = $this->input->post('strate_sales15');
            $pay_difference16      = $this->input->post('pay_difference16');
            $refund17              = $this->input->post('refund17');
            $credit_forward18      = $this->input->post('credit_forward18');
            $startdate             = $this->input->post('st_yy') . "-" . $this->input->post('st_mm') . "-" . $this->input->post('st_dd');
            $enddate               = $this->input->post('en_yy') . "-" . $this->input->post('en_mm') . "-" . $this->input->post('en_dd');
            $createddate           = $this->input->post('cr_yy') . "-" . $this->input->post('cr_mm') . "-" . $this->input->post('cr_dd');
            $month                 = $this->input->post('cr_mm');
            $year                  = $this->input->post('cr_yy');
            $field_in_kh           = $this->input->post('kh_made_at');
            $field_in_en           = $this->input->post('en_made_at');
            $saveValue             = array(
                'group_id' => $enterpriseID,
                'pusa_act04' => $pusa_act04,
                'tax_credit_premonth05' => $tax_credit_premonth05,
                'ncredit_purch06' => $ncredit_purch06,
                'strate_purch07' => $strate_purch07,
                'strate_purch08' => $strate_purch08,
                'strate_imports09' => $strate_imports09,
                'strate_imports10' => $strate_imports10,
                'total_intax11' => $total_intax11,
                'ntaxa_sales12' => $ntaxa_sales12,
                'exports13' => $exports13,
                'strate_sales14' => $strate_sales14,
                'strate_sales15' => $strate_sales15,
                'pay_difference16' => $pay_difference16,
                'refund17' => $refund17,
                'credit_forward18' => $credit_forward18,
                'covreturn_start' => $startdate,
                'covreturn_end' => $enddate,
                'created_date' => $createddate,
                'year' => $year,
                'month' => $month,
                'field_in_kh' => $field_in_kh,
                'field_in_en' => $field_in_en
            );
            
            
            /********End Save front Page of Form*********/
            
            /********Save front Back Page of Form*********/
            
            //20
            $Product_1     = $this->input->post('product_1');
            $qty_1         = $this->input->post('qty_1');
            $date_1        = $this->input->post('date_1');
            $inv_declare_1 = $this->input->post('inv_declare_1');
            $suppid_1      = $this->input->post('suppid_1');
            $VAT_1         = $this->input->post('VAT_1');
            $a             = sizeof($Product_1);
            
            for ($i = 0; $i < $a; $i++) {
                if ($Product_1[$i] != "") {
                    $save20[] = array(
                        'productid' => $Product_1[$i],
                        'qty' => $qty_1[$i],
                        'date' => date("Y-m-d", strtotime($date_1[$i])),
                        'inv_cust_desc' => $inv_declare_1[$i],
                        'supp_exp_inn' => $suppid_1[$i],
                        'val_vat' => $VAT_1[$i],
                        'type' => '20'
                    );
                    
                }
            }
            //End 20
            //21
            $Product_2     = $this->input->post('product_2');
            $qty_2         = $this->input->post('qty_2');
            $date_2        = $this->input->post('date_2');
            $inv_declare_2 = $this->input->post('inv_declare_2');
            $exp_2         = $this->input->post('exp_2');
            $exv_2         = $this->input->post('exv_2');
            $j             = sizeof($Product_2);
            for ($m = 0; $m < $j; $m++) {
                if ($Product_1[$m] != "") {
                    $save21[] = array(
                        'productid' => $Product_2[$m],
                        'qty' => $qty_2[$m],
                        'date' => date("Y-m-d", strtotime($date_2[$m])),
                        'inv_cust_desc' => $inv_declare_2[$m],
                        'supp_exp_inn' => $exp_2[$m],
                        'val_vat' => $exv_2[$m],
                        'type' => '21'
                    );
                    
                }
            }
            //End 21
            //22
            $Product_3 = $this->input->post('product_3');
            $qty_3     = $this->input->post('qty_3');
            $VAT_3     = $this->input->post('VAT_3');
            $DESC_3    = $this->input->post('DESC_3');
            $INV_3     = $this->input->post('INV_3');
            $VAT2_3    = $this->input->post('VAT2_3');
            $b         = sizeof($Product_3);
            for ($u = 0; $u < $b; $u++) {
                if ($Product_3[$u] != "") {
                    $save22[] = array(
                        'productid' => $Product_3[$u],
                        'val_vat_g' => $VAT_3[$u],
                        'qty' => $qty_3[$u],
                        'inv_cust_desc' => $DESC_3[$u],
                        'supp_exp_inn' => $INV_3[$u],
                        'val_vat' => $VAT2_3[$u],
                        'type' => '22'
                    );
                    
                }
            }
            
            if ($this->taxes_model->saveValueAddedTax($saveValue, $save20, $save21, $save22)) {
                $this->session->set_flashdata('message', lang("return_tax_declared"));
                redirect("taxes/value_added_tax");
            } else {
                $this->session->set_flashdata('error', lang("enterprice_not_selected"));
                redirect("taxes/value_added_tax");
            }
        } else {
            $this->data['users']      = $this->reports_model->getStaff();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['billers']    = $this->site->getAllCompanies('biller');
            $this->data['suppid']     = $this->taxes_model->SupplierList();
            $this->data['Product']    = $this->taxes_model->getAllProducts();
            $this->data['enterprise'] = $this->taxes_model->SelectEnterprise();
            
            $this->data['front']   = $this->taxes_reports_model->getInfoFrontPage($id);
            $this->data['back_20'] = $this->taxes_reports_model->getInfoBackPage($id, '20');
            $this->data['back_21'] = $this->taxes_reports_model->getInfoBackPage($id, '21');
            $this->data['back_22'] = $this->taxes_reports_model->getInfoBackPage($id, '22');
            
            $bc   = array(
                array(
                    'link' => base_url(),
                    'page' => lang('home')
                ),
                array(
                    'link' => '#',
                    'page' => lang('value_added_tax_state_change')
                )
            );
            $meta = array(
                'page_title' => lang('value_added_tax_state_change'),
                'bc' => $bc
            );
            
            $this->page_construct('taxes/value_added_tax_state_change_edit', $meta, $this->data);
        }
    }
    
	public function small_selling_tax()
    {
        
        $this->erp->checkPermissions($warehouse_id=NULL);
        $this->data['users']      = $this->reports_model->getStaff();
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['billers']    = $this->site->getAllCompanies('biller');
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin) {
            $this->data['warehouses']   = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse']    = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
        } else {
            $this->data['warehouses']   = NULL;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse']    = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;
        }
        
        
        $bc   = array(
            array(
                'link' => base_url(),
                'page' => lang('home')
            ),
            array(
                'link' => '#',
                'page' => lang('selling_tax_for_small_taxpayers')
            )
        );
        $meta = array(
            'page_title' => lang('selling_tax'),
            'bc' => $bc
        );
        
        $this->page_construct('taxes/small_selling_tax', $meta, $this->data);
    }
    
	function getSalesReportForSmallTaxpayers($pdf = NULL, $xls = NULL)
    {
        $this->erp->checkPermissions('sales', TRUE);
        if ($this->input->get('product')) {
            $product = $this->input->get('product');
        } else {
            $product = NULL;
        }
        if ($this->input->get('user')) {
            $user = $this->input->get('user');
        } else {
            $user = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
        if ($this->input->get('biller')) {
            $biller = $this->input->get('biller');
        } else {
            $biller = NULL;
        }
        if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get("customer_group")) {
            $customer_group = $this->input->get("customer_group");
        } else {
            $customer_group = NULL;
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
        if ($this->input->get('serial')) {
            $serial = $this->input->get('serial');
        } else {
            $serial = NULL;
        }
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date   = $this->erp->fld($end_date);
        }
        if (!$this->Owner && !$this->Admin) {
            $user = $this->session->userdata('user_id');
        }
        
        if ($pdf || $xls) {
            
            $this->db->select("erp_sales.id,date, reference_no, biller, customer,payment_status,(grand_total-total_tax) as balance,total_tax,grand_total,reference_no_tax,tax_status", FALSE)->from('sales')->join('sale_items', 'sale_items.sale_id=sales.id', 'left')->join('warehouses', 'warehouses.id=sales.warehouse_id', 'left')->join('companies', 'companies.id=sales.customer_id', 'left')->join('customer_groups', 'customer_groups.id=companies.customer_group_id', 'left')->group_by('sales.id');
            
            
            if ($user) {
                $this->db->where('sales.created_by', $user);
            }
            if ($product) {
                $this->db->like('sale_items.product_id', $product);
            }
            if ($serial) {
                $this->db->like('sale_items.serial_no', $serial);
            }
            if ($biller) {
                $this->db->where('sales.biller_id', $biller);
            }
            if ($customer) {
                $this->db->where('sales.customer_id', $customer);
            }
            if ($customer_group) {
                $this->db->where('companies.customer_group_id', $customer_group);
            }
            if ($warehouse) {
                $this->db->where('sales.warehouse_id', $warehouse);
            }
            if ($reference_no) {
                $this->db->like('sales.reference_no', $reference_no, 'both');
            }
            if ($start_date) {
                $this->db->where($this->db->dbprefix('sales') . '.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
            
            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
            } else {
                $data = NULL;
            }
            
            if (!empty($data)) {
                
                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle(lang('tax_report'));
                $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                $this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));
                $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                $this->excel->getActiveSheet()->SetCellValue('E1', lang('payment_status'));
                $this->excel->getActiveSheet()->SetCellValue('F1', lang('amount'));
                $this->excel->getActiveSheet()->SetCellValue('G1', lang('amount_tax'));
                $this->excel->getActiveSheet()->SetCellValue('H1', lang('total_amount'));
                $this->excel->getActiveSheet()->SetCellValue('I1', lang('tax_ref_no'));
                $this->excel->getActiveSheet()->SetCellValue('J1', lang('action'));
                
                $row     = 2;
                $total   = 0;
                $paid    = 0;
                $balance = 0;
                foreach ($data as $data_row) {
                    $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($data_row->date));
                    $this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->reference_no);
                    $this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->biller);
                    $this->excel->getActiveSheet()->SetCellValue('D' . $row, $data_row->customer);
                    $this->excel->getActiveSheet()->SetCellValue('E' . $row, $data_row->payment_status);
                    $this->excel->getActiveSheet()->SetCellValue('F' . $row, $data_row->balance);
                    $this->excel->getActiveSheet()->SetCellValue('G' . $row, $data_row->total_tax);
                    $this->excel->getActiveSheet()->SetCellValue('H' . $row, $data_row->grand_total);
                    $this->excel->getActiveSheet()->SetCellValue('I' . $row, ($data_row->reference_no_tax));
                    $this->excel->getActiveSheet()->SetCellValue('J' . $row, $data_row->tax_status);
                    $total += $data_row->balance;
                    $paid += $data_row->total_tax;
                    $balance += $data_row->grand_total;
                    $row++;
                }
                $this->excel->getActiveSheet()->getStyle("G" . $row . ":I" . $row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
                $this->excel->getActiveSheet()->SetCellValue('F' . $row, $total);
                $this->excel->getActiveSheet()->SetCellValue('G' . $row, $paid);
                $this->excel->getActiveSheet()->SetCellValue('H' . $row, $balance);
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                $filename = 'sales_report';
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                if ($pdf) {
                    $styleArray = array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    );
                    $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                    $this->excel->getActiveSheet()->getStyle('F2:F' . $row)->getAlignment()->applyFromArray(array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
                    ));
                    $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                    $rendererName        = PHPExcel_Settings::PDF_RENDERER_MPDF;
                    $rendererLibrary     = 'MPDF';
                    $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                    if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                        die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' . PHP_EOL . ' as appropriate for your directory structure');
                    }
                    
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                    header('Cache-Control: max-age=0');
                    
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                    $objWriter->save('php://output');
                    exit();
                }
                if ($xls) {
                    $this->excel->getActiveSheet()->getStyle('E2:E' . $row)->getAlignment()->setWrapText(true);
                    $this->excel->getActiveSheet()->getStyle('F2:F' . $row)->getAlignment()->applyFromArray(array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                        'wrap' => true
                    ));
                    ob_clean();
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                    header('Cache-Control: max-age=0');
                    ob_clean();
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save('php://output');
                    exit();
                }
                
            }
            $this->session->set_flashdata('error', lang('nothing_found'));
            redirect($_SERVER["HTTP_REFERER"]);
            
        } else {
            $this->load->library('datatables');
            
            $this->datatables->select("erp_sales.id,date, reference_no, biller, customer,note,total as balance,
					IF(erp_sales.sale_type = '1', 'Taxable Sales', IF(erp_sales.sale_type = '2', 'Non Taxable Sales','Export')) as remark,
					tax_status", FALSE)->from('sales')->join('sale_tax', 'sale_tax.sale_id=sales.id', 'left')->group_by('sales.id')->order_by('date DESC');
            $this->datatables->where('sales.sale_type<>', 0);
            $this->datatables->where('sales.tax_type', 1);
            if ($user) {
                $this->datatables->where('sales.created_by', $user);
            }
            if ($product) {
                $this->datatables->like('sale_items.product_id', $product);
            }
            if ($serial) {
                $this->datatables->like('sale_items.serial_no', $serial);
            }
            if ($biller) {
                $this->datatables->where('sales.biller_id', $biller);
            }
            if ($customer) {
                $this->datatables->where('sales.customer_id', $customer);
            }
            if ($customer_group) {
                $this->datatables->where('companies.customer_group_id', $customer_group);
            }
            if ($warehouse) {
                $this->datatables->where('sales.warehouse_id', $warehouse);
            }
            if ($reference_no) {
                $this->datatables->like('sales.reference_no', $reference_no, 'both');
            }
            if ($start_date) {
                $this->datatables->where($this->db->dbprefix('sales') . '.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
            echo $this->datatables->generate();
        }
        
    }
	
    public function small_combine_tax()
    {
        $this->erp->checkPermissions();
        
        $arr = array();
        if ($this->input->get('data')) {
            $arr = explode(',', $this->input->get('data'));
        }
        
        $this->form_validation->set_rules('date', lang("date"), 'required');
        if ($this->form_validation->run() == true) {
            
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            
            $sale_id_arr            = $this->input->post('sale_id');
            $warehouse_id_arr       = $this->input->post('warehouse_id');
            $referen_line_arr       = $this->input->post('referent_line');
            $customer_id_arr        = $this->input->post('customer_id');
            $order_tax_id_arr       = $this->input->post('ordertax_id');
            $amount_arr             = $this->input->post('amount');
            $amount_tax_arr         = $this->input->post('vatbox');
            $note                   = $this->input->post('note');
            $non_taxable_sale_arr   = $this->input->post('non_taxable');
            $export_arr             = $this->input->post('export');
            $amount_tax_declare_arr = $this->input->post('vatbox_declare');
            $amount_declared_arr    = $this->input->post('amount_declare');
            $pns                    = $this->input->post('pns');
            $i                      = 0;
            
            foreach ($sale_id_arr as $sale_id) {
                
                $customer_id = $customer_id_arr[$i];
                $getCustomer = $this->taxes_model->getCustomerById($customer_id);
                $vatin       = $getCustomer->vat_no;
                
                //ZZif($referen_line_arr[$i]!=""){
                $sale_tax = array(
                    'issuedate' => $date,
                    'sale_id' => $sale_id,
                    'customer_id' => $customer_id_arr[$i],
                    'group_id' => $warehouse_id_arr[$i],
                    'tax_id' => $order_tax_id_arr[$i],
                    'amound' => $amount_arr[$i],
                    'amound_tax' => $amount_tax_arr[$i],
                    'description' => $note[$i],
                    'amound_declare' => $amount_declared_arr[$i],
                    'amount_tax_declare' => $amount_tax_declare_arr[$i],
                    'referent_no' => $referen_line_arr[$i],
                    'vatin' => $vatin,
                    'non_tax_sale' => $non_taxable_sale_arr[$i],
                    'tax_type' => 1,
                    'pns' => $pns[$i],
                    'value_export' => $export_arr[$i]
                );
                //	print_r($sale_tax); exit;
                $i++;
                $this->taxes_model->addTax($sale_tax);
                
            }
            
            
            
            $this->session->set_flashdata('message', lang("tax_added"));
            redirect('taxes/small_selling_tax', 'refresh');
        } else {
            
            $this->data['error']         = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['billers']       = $this->site->getAllCompanies('biller');
            $combine_tax                 = $this->taxes_model->getCombineTaxById($arr);
            $this->data['combine_tax']   = $combine_tax;
            $this->data['payment_ref']   = '';
            $this->data['modal_js']      = $this->site->modal_js();
            $this->data['exchange_rate'] = $this->taxes_model->getExchangeRate('KHM');
            
            $this->load->view($this->theme . 'taxes/add_small_selling_tax', $this->data);
            
        }
    }
	
    function small_purchasing_tax($warehouse_id=NULL)
    {
        $this->erp->checkPermissions();
        
        if (isset($_GET['d']) != "") {
            $date               = $_GET['d'];
            $this->data['date'] = $date;
        }
        
        $this->data['users'] = $this->reports_model->getStaff();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses']   = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse']    = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : null;
        } else {
            $this->data['warehouses']   = null;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse']    = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : null;
        }
        
        $bc   = array(
            array(
                'link' => base_url(),
                'page' => lang('home')
            ),
            array(
                'link' => '#',
                'page' => lang('purchasing_tax')
            )
        );
        $meta = array(
            'page_title' => lang('purchasing_tax'),
            'bc' => $bc
        );
        $this->page_construct('taxes/small_purchasing_tax', $meta, $this->data);
    }
    
	public function getSmallPurchases($warehouse_id = null)
    {
        $this->erp->checkPermissions('index');
        if ($this->input->get('product')) {
            $product = $this->input->get('product');
        } else {
            $product = NULL;
        }
        if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('supplier')) {
            $supplier = $this->input->get('supplier');
        } else {
            $supplier = NULL;
        }
        if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
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
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date   = $this->erp->fld($end_date);
        }
        if ($this->input->get('note')) {
            $note = $this->input->get('note');
        } else {
            $note = NULL;
        }
        
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user         = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        
        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables->select("insert_id as id, date, " . $this->db->dbprefix("purchasing_taxes") . ".reference_no,  " . $this->db->dbprefix("purchasing_taxes") . ".invoice_no,
				supplier, note, status, " . $this->db->dbprefix("purchasing_taxes") . ".amount,  " . $this->db->dbprefix("purchasing_taxes") . ".vat, balance,   purchase_tax.status_tax, purchasing_taxes.type")->from('purchasing_taxes') // zz1
                ->join('purchase_tax', 'purchase_tax.purchase_id = purchasing_taxes.insert_id', 'left')->where('warehouse_id', $warehouse_id);
        } else {
            $this->datatables->select("insert_id as id, date, " . $this->db->dbprefix("purchasing_taxes") . ".reference_no,  " . $this->db->dbprefix("purchasing_taxes") . ".invoice_no,
				supplier, note, status, " . $this->db->dbprefix("purchasing_taxes") . ".amount,  " . $this->db->dbprefix("purchasing_taxes") . ".vat, balance,   purchase_tax.status_tax, purchasing_taxes.type")->from('purchasing_taxes') // zz1
                ->join('purchase_tax', 'purchase_tax.purchase_id = purchasing_taxes.insert_id', 'left');
            if (isset($_REQUEST['d'])) {
                $date_c = date('Y-m-d', strtotime('+3 months'));
                $date   = $_GET['d'];
                $date1  = str_replace("/", "-", $date);
                $date   = date('Y-m-d', strtotime($date1));
                
                $this->datatables->where("date >=", $date)->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()');
            }
        }
        $this->datatables->edit_column('id', '$1__$2', 'id, purchasing_taxes.type');
        $this->datatables->unset_column('purchasing_taxes.type');
        //$this->datatables->where('purchases.tax_type', 2);
        // search options
        $this->datatables->where('erp_purchasing_taxes.tax_type', 1);
        if ($user_query) {
            $this->datatables->where('purchases.created_by', $user_query);
        }
        if ($product) {
            $this->datatables->like('purchase_items.product_id', $product);
        }
        if ($supplier) {
            $this->datatables->where('purchases.supplier_id', $supplier);
        }
        if ($warehouse) {
            $this->datatables->where('purchases.warehouse_id', $warehouse);
        }
        if ($reference_no) {
            $this->datatables->like('purchases.reference_no', $reference_no, 'both');
        }
        if ($start_date) {
            $this->datatables->where($this->db->dbprefix('purchases') . '.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }
        if ($note) {
            $this->datatables->like('purchases.note', $note, 'both');
        }
        $action     = NULL;
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
    
    function employee_tax_salary_list($month = NULL, $year = NULL)
    {
        
        $this->data['khMonth'] = $this->erp->KhmerMonth($month);
        $dateObj               = DateTime::createFromFormat('!m', $month);
        $this->data['enMonth'] = $dateObj->format('F');
        $this->data['enYear']  = $year;
        $this->data['khYear']  = $this->erp->KhmerNumDate($year);
        
        $biller_id            = $this->site->default_biller_id();
        $biller               = $this->site->getCompanyByID($biller_id);
        $this->data['biller'] = $biller;
        $this->data['datas']  = $this->taxes_reports_model->getEmployeeSalaryTaxListDetails($month, $year);
        
        $this->load->view($this->theme . 'taxes/employee_tax_salary_list', $this->data);
        
        
    }
    // Large Tax
    function large_selling_tax($warehouse_id=NULL)
    {
        $this->erp->checkPermissions();
        $this->data['users']      = $this->reports_model->getStaff();
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['billers']    = $this->site->getAllCompanies('biller');
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin) {
            $this->data['warehouses']   = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse']    = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
        } else {
            $this->data['warehouses']   = NULL;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse']    = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;
        }
        
        
        $bc   = array(
            array(
                'link' => base_url(),
                'page' => lang('home')
            ),
            array(
                'link' => '#',
                'page' => lang('selling_tax')
            )
        );
        $meta = array(
            'page_title' => lang('selling_tax'),
            'bc' => $bc
        );
        
        $this->page_construct('taxes/large_selling_tax', $meta, $this->data);
    }
    
	function getLargeSalesReport($pdf = NULL, $xls = NULL)
    {
        $this->erp->checkPermissions('sales', TRUE);
        if ($this->input->get('product')) {
            $product = $this->input->get('product');
        } else {
            $product = NULL;
        }
        if ($this->input->get('user')) {
            $user = $this->input->get('user');
        } else {
            $user = NULL;
        }
        if ($this->input->get('customer')) {
            $customer = $this->input->get('customer');
        } else {
            $customer = NULL;
        }
        if ($this->input->get('biller')) {
            $biller = $this->input->get('biller');
        } else {
            $biller = NULL;
        }
        if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
        }
        if ($this->input->get("customer_group")) {
            $customer_group = $this->input->get("customer_group");
        } else {
            $customer_group = NULL;
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
        if ($this->input->get('serial')) {
            $serial = $this->input->get('serial');
        } else {
            $serial = NULL;
        }
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date   = $this->erp->fld($end_date);
        }
        if (!$this->Owner && !$this->Admin) {
            $user = $this->session->userdata('user_id');
        }
        
        if ($pdf || $xls) {
            
            $this->db->select("erp_sales.id,date, reference_no, biller, customer,payment_status,(grand_total-total_tax) as balance,total_tax,grand_total,reference_no_tax,tax_status", FALSE)->from('sales')->join('sale_items', 'sale_items.sale_id=sales.id', 'left')->join('warehouses', 'warehouses.id=sales.warehouse_id', 'left')->join('companies', 'companies.id=sales.customer_id', 'left')->join('customer_groups', 'customer_groups.id=companies.customer_group_id', 'left')->group_by('sales.id');
            
            
            if ($user) {
                $this->db->where('sales.created_by', $user);
            }
            if ($product) {
                $this->db->like('sale_items.product_id', $product);
            }
            if ($serial) {
                $this->db->like('sale_items.serial_no', $serial);
            }
            if ($biller) {
                $this->db->where('sales.biller_id', $biller);
            }
            if ($customer) {
                $this->db->where('sales.customer_id', $customer);
            }
            if ($customer_group) {
                $this->db->where('companies.customer_group_id', $customer_group);
            }
            if ($warehouse) {
                $this->db->where('sales.warehouse_id', $warehouse);
            }
            if ($reference_no) {
                $this->db->like('sales.reference_no', $reference_no, 'both');
            }
            if ($start_date) {
                $this->db->where($this->db->dbprefix('sales') . '.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
            
            $q = $this->db->get();
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $data[] = $row;
                }
            } else {
                $data = NULL;
            }
            
            if (!empty($data)) {
                
                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle(lang('tax_report'));
                $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                $this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));
                $this->excel->getActiveSheet()->SetCellValue('D1', lang('customer'));
                $this->excel->getActiveSheet()->SetCellValue('E1', lang('payment_status'));
                $this->excel->getActiveSheet()->SetCellValue('F1', lang('amount'));
                $this->excel->getActiveSheet()->SetCellValue('G1', lang('amount_tax'));
                $this->excel->getActiveSheet()->SetCellValue('H1', lang('total_amount'));
                $this->excel->getActiveSheet()->SetCellValue('I1', lang('tax_ref_no'));
                $this->excel->getActiveSheet()->SetCellValue('J1', lang('action'));
                
                $row     = 2;
                $total   = 0;
                $paid    = 0;
                $balance = 0;
                foreach ($data as $data_row) {
                    $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($data_row->date));
                    $this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->reference_no);
                    $this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->biller);
                    $this->excel->getActiveSheet()->SetCellValue('D' . $row, $data_row->customer);
                    $this->excel->getActiveSheet()->SetCellValue('E' . $row, $data_row->payment_status);
                    $this->excel->getActiveSheet()->SetCellValue('F' . $row, $data_row->balance);
                    $this->excel->getActiveSheet()->SetCellValue('G' . $row, $data_row->total_tax);
                    $this->excel->getActiveSheet()->SetCellValue('H' . $row, $data_row->grand_total);
                    $this->excel->getActiveSheet()->SetCellValue('I' . $row, ($data_row->reference_no_tax));
                    $this->excel->getActiveSheet()->SetCellValue('J' . $row, $data_row->tax_status);
                    $total += $data_row->balance;
                    $paid += $data_row->total_tax;
                    $balance += $data_row->grand_total;
                    $row++;
                }
                $this->excel->getActiveSheet()->getStyle("G" . $row . ":I" . $row)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
                $this->excel->getActiveSheet()->SetCellValue('F' . $row, $total);
                $this->excel->getActiveSheet()->SetCellValue('G' . $row, $paid);
                $this->excel->getActiveSheet()->SetCellValue('H' . $row, $balance);
                
                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
                $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                $filename = 'sales_report';
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                if ($pdf) {
                    $styleArray = array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    );
                    $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                    $this->excel->getActiveSheet()->getStyle('F2:F' . $row)->getAlignment()->applyFromArray(array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
                    ));
                    $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                    require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                    $rendererName        = PHPExcel_Settings::PDF_RENDERER_MPDF;
                    $rendererLibrary     = 'MPDF';
                    $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                    if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                        die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' . PHP_EOL . ' as appropriate for your directory structure');
                    }
                    
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
                    header('Cache-Control: max-age=0');
                    
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                    $objWriter->save('php://output');
                    exit();
                }
                if ($xls) {
                    $this->excel->getActiveSheet()->getStyle('E2:E' . $row)->getAlignment()->setWrapText(true);
                    $this->excel->getActiveSheet()->getStyle('F2:F' . $row)->getAlignment()->applyFromArray(array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                        'wrap' => true
                    ));
                    ob_clean();
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                    header('Cache-Control: max-age=0');
                    ob_clean();
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    $objWriter->save('php://output');
                    exit();
                }
                
            }
            $this->session->set_flashdata('error', lang('nothing_found'));
            redirect($_SERVER["HTTP_REFERER"]);
            
        } else {
            $this->load->library('datatables');
            
            $this->datatables->select("erp_sales.id,date, reference_no, biller, customer,note,(total-order_discount+shipping) as balance,order_tax,grand_total,sale_tax.amound_declare,sale_tax.amount_tax_declare,(erp_sale_tax.amound_declare+erp_sale_tax.amount_tax_declare) as total_declare,
					IF(erp_sales.sale_type = '1', 'Taxable Sales', IF(erp_sales.sale_type = '2', 'Non Taxable Sales','Export')) as remark,tax_status", FALSE)->from('sales')->join('sale_tax', 'sale_tax.sale_id=sales.id', 'left')->group_by('sales.id')->order_by('date DESC');
            $this->datatables->where('sales.sale_type<>', 0);
            $this->datatables->where('sales.tax_type', 3);
            if ($user) {
                $this->datatables->where('sales.created_by', $user);
            }
            if ($product) {
                $this->datatables->like('sale_items.product_id', $product);
            }
            if ($serial) {
                $this->datatables->like('sale_items.serial_no', $serial);
            }
            if ($biller) {
                $this->datatables->where('sales.biller_id', $biller);
            }
            if ($customer) {
                $this->datatables->where('sales.customer_id', $customer);
            }
            if ($customer_group) {
                $this->datatables->where('companies.customer_group_id', $customer_group);
            }
            if ($warehouse) {
                $this->datatables->where('sales.warehouse_id', $warehouse);
            }
            if ($reference_no) {
                $this->datatables->like('sales.reference_no', $reference_no, 'both');
            }
            if ($start_date) {
                $this->datatables->where($this->db->dbprefix('sales') . '.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
            echo $this->datatables->generate();
        }
        
    }
    
	public function large_combine_tax()
    {
        $this->erp->checkPermissions();
        
        $arr = array();
        if ($this->input->get('data')) {
            $arr = explode(',', $this->input->get('data'));
        }
        
        $this->form_validation->set_rules('date', lang("date"), 'required');
        if ($this->form_validation->run() == true) {
            
            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $sale_id_arr            = $this->input->post('sale_id');
            $warehouse_id_arr       = $this->input->post('warehouse_id');
            $referen_line_arr       = $this->input->post('referent_line');
            $customer_id_arr        = $this->input->post('customer_id');
            $order_tax_id_arr       = $this->input->post('ordertax_id');
            $amount_arr             = $this->input->post('amount');
            $amount_tax_arr         = $this->input->post('vatbox');
            $note                   = $this->input->post('note');
            $non_taxable_sale_arr   = $this->input->post('non_taxable');
            $export_arr             = $this->input->post('export');
            $amount_tax_declare_arr = $this->input->post('vatbox_declare');
            $amount_declared_arr    = $this->input->post('amount_declare');
            $sale_type_arr          = $this->input->post('tax_type');
            $i                      = 0;
            foreach ($sale_id_arr as $sale_id) {
                $customer_id = $customer_id_arr[$i];
                $getCustomer = $this->taxes_model->getCustomerById($customer_id);
                $vatin       = $getCustomer->vat_no;
                //ZZif($referen_line_arr[$i]!=""){
                $sale_tax    = array(
                    'issuedate' => $date,
                    'sale_id' => $sale_id,
                    'customer_id' => $customer_id_arr[$i],
                    'group_id' => $warehouse_id_arr[$i],
                    'tax_id' => $order_tax_id_arr[$i],
                    'amound' => $amount_arr[$i],
                    'amound_tax' => $amount_tax_arr[$i],
                    'description' => $note[$i],
                    'amound_declare' => $amount_declared_arr[$i],
                    'amount_tax_declare' => $amount_tax_declare_arr[$i],
                    'referent_no' => $referen_line_arr[$i],
                    'vatin' => $vatin,
                    'non_tax_sale' => $non_taxable_sale_arr[$i],
                    'tax_type' => 3,
                    'sale_type' => $sale_type_arr[$i],
                    'value_export' => $export_arr[$i]
                );
                //	print_r($sale_tax); exit;
                $i++;
                $check_update = $this->taxes_model->check_update_sale_tax($sale_id);
                
                if ($check_update == 1) {
                    $this->taxes_model->updateTax($sale_id, $sale_tax);
                } else {
                    $this->taxes_model->addTax($sale_tax);
                }
            }
            
            
            
            $this->session->set_flashdata('message', lang("tax_added"));
            redirect('taxes/large_selling_tax', 'refresh');
        } else {
            $this->data['error']   = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $combine_tax           = $this->taxes_model->getCombineTaxById($arr);
            
            //print_r($combine_tax);exit;
            $this->data['combine_tax']   = $combine_tax;
            $this->data['payment_ref']   = '';
            $this->data['modal_js']      = $this->site->modal_js();
            $this->data['exchange_rate'] = $this->taxes_model->getExchangeRate('KHM');
            
            $this->load->view($this->theme . 'taxes/add_large_selling_tax', $this->data);
            
        }
    }
    // Purchasing tax large taxpayer //
    function large_purchasing_tax($warehouse_id=NULL)
    {
        $this->erp->checkPermissions();
        $this->data['purchasing_tax'] = $this->taxes_model->getPursing_tax();
        $this->data['getJournal_tax'] = $this->taxes_model->getJournal_tax();
        $this->data['modal_js']       = $this->site->modal_js();
        if (isset($_GET['d']) != "") {
            $date               = $_GET['d'];
            $this->data['date'] = $date;
        }
        $this->data['users'] = $this->reports_model->getStaff();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses']   = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse']    = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : null;
        } else {
            $this->data['warehouses']   = null;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse']    = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : null;
        }
        $bc   = array(
            array(
                'link' => base_url(),
                'page' => lang('home')
            ),
            array(
                'link' => '#',
                'page' => lang('purchasing_tax')
            )
        );
        $meta = array(
            'page_title' => lang('purchasing_tax'),
            'bc' => $bc
        );
        $this->page_construct('taxes/large_purchasing_tax', $meta, $this->data);
    }
    
    public function getLargePurchases($warehouse_id = null)
    {
        $this->erp->checkPermissions('index');
        
        if ($this->input->get('product')) {
            $product = $this->input->get('product');
        } else {
            $product = NULL;
        }
        if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('supplier')) {
            $supplier = $this->input->get('supplier');
        } else {
            $supplier = NULL;
        }
        if ($this->input->get('warehouse')) {
            $warehouse = $this->input->get('warehouse');
        } else {
            $warehouse = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
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
        if ($start_date) {
            $start_date = $this->erp->fld($start_date);
            $end_date   = $this->erp->fld($end_date);
        }
        if ($this->input->get('note')) {
            $note = $this->input->get('note');
        } else {
            $note = NULL;
        }
        if ((!$this->Owner || !$this->Admin) && !$warehouse_id) {
            $user         = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables->select("insert_id as id, date, " . $this->db->dbprefix("purchasing_taxes") . ".reference_no, " . $this->db->dbprefix("purchasing_taxes") . ".invoice_no, 
				 supplier, note, status, " . $this->db->dbprefix("purchasing_taxes") . ".amount,  " . $this->db->dbprefix("purchasing_taxes") . ".vat, balance, purchase_tax.amount_declear, purchase_tax.amount_tax_declare, (erp_purchase_tax.amount_declear+erp_purchase_tax.amount_tax_declare) as total_amount_declare, remark, purchase_tax.status_tax, purchasing_taxes.type")->from('purchasing_taxes') // zz1
                ->join('purchase_tax', 'purchase_tax.purchase_id = purchasing_taxes.insert_id', 'left')->where('warehouse_id', $warehouse_id);
        } else {
            $this->datatables->select("insert_id as id, date, " . $this->db->dbprefix("purchasing_taxes") . ".reference_no,  " . $this->db->dbprefix("purchasing_taxes") . ".invoice_no,
				supplier, note, status, " . $this->db->dbprefix("purchasing_taxes") . ".amount,  " . $this->db->dbprefix("purchasing_taxes") . ".vat, balance, purchase_tax.amount_declear, purchase_tax.amount_tax_declare, (erp_purchase_tax.amount_declear+erp_purchase_tax.amount_tax_declare) as total_amount_declare, remark, purchase_tax.status_tax, purchasing_taxes.type")->from('purchasing_taxes') // zz1
                ->join('purchase_tax', 'purchase_tax.purchase_id = purchasing_taxes.insert_id', 'left');
            if (isset($_REQUEST['d'])) {
                $date_c = date('Y-m-d', strtotime('+3 months'));
                $date   = $_GET['d'];
                $date1  = str_replace("/", "-", $date);
                $date   = date('Y-m-d', strtotime($date1));
                
                $this->datatables->where("date >=", $date)->where('DATE_SUB(date, INTERVAL 1 DAY) <= CURDATE()');
            }
        }
        $this->datatables->edit_column('id', '$1__$2', 'id, purchasing_taxes.type');
        $this->datatables->unset_column('purchasing_taxes.type');
        //$this->datatables->where('purchases.tax_type', 2);
        // search options
        $this->datatables->where("(erp_purchasing_taxes.tax_type='0' OR erp_purchasing_taxes.tax_type= '3')", NULL, FALSE);
        if ($user_query) {
            $this->datatables->where('purchases.created_by', $user_query);
        }
        if ($product) {
            $this->datatables->like('purchase_items.product_id', $product);
        }
        if ($supplier) {
            $this->datatables->where('purchases.supplier_id', $supplier);
        }
        if ($warehouse) {
            $this->datatables->where('purchases.warehouse_id', $warehouse);
        }
        if ($reference_no) {
            $this->datatables->like('purchases.reference_no', $reference_no, 'both');
        }
        if ($start_date) {
            $this->datatables->where($this->db->dbprefix('purchases') . '.date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }
        if ($note) {
            $this->datatables->like('purchases.note', $note, 'both');
        }
        $action     = NULL;
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
    
    public function add_large_purchasing_tax_form()
    {
        
        $this->erp->checkPermissions();
        $this->form_validation->set_rules('date', lang('date'), 'required');
        
        $setting = $this->site->get_setting();
        if ($this->form_validation->run() == true) {
            
            $tax_ref            = $this->input->post('tax_ref');
            $pur_id             = $this->input->post('pur_id');
            $purchase_id        = $this->input->post('purchase_id');
            $type               = $this->input->post('ptype');
            $purchase_ref       = $this->input->post('purchase_ref');
            $supplier_id        = $this->input->post('supplier_id');
            $warehouse_id       = $this->input->post('warehouse_id');
            $issuedate          = $this->input->post('date');
            $amount             = $this->input->post('amount');
            $non_tax_pur        = $this->input->post('non_tax_pur');
            $value_import       = $this->input->post('value_import');
            $amount_tax         = $this->input->post('amount_tax');
            $amount_declare     = $this->input->post('amount_decleared');
            $amount_tax_declare = $this->input->post('amount_tax_declare');
            $purchase_type      = $this->input->post('purchase_type');
            $tax_id             = $this->input->post('tax_id');
            $c                  = count($purchase_id);
            $journal_taxes      = 0;
            
            $this->load->model('purchases_model');
            for ($i = 0; $i < $c; $i++) {
                $qty = 0;
                if ($purchase_id[$i] != "") {
                    $sale_items = $this->purchases_model->getItemsByPurchaseId($purchase_id[$i]);
                    foreach ($sale_items as $item) {
                        $qty += $item->quantity;
                    }
                }
                
                if ($supplier_id[$i] != "") {
                    $getSupplier = $this->purchases_model->getSupplierById($supplier_id[$i]);
                } else {
                    $getSupplier = $this->purchases_model->getSupplierById($setting->default_biller);
                }
                $vatin        = $getSupplier->vat_no;
                $purchase_tax = array(
                    'reference_no' => $purchase_ref[$i],
                    'purchase_id' => $purchase_id[$i],
                    'type' => $type[$i],
                    'purchase_ref' => $purchase_ref[$i],
                    'supplier_id' => $supplier_id[$i],
                    'group_id' => $warehouse_id[$i],
                    'issuedate' => $issuedate,
                    'amount' => $amount[$i],
                    'value_import' => $value_import[$i],
                    'non_tax_pur' => $non_tax_pur[$i],
                    'amount_tax' => $amount_tax[$i],
                    'amount_declear' => $amount_declare[$i],
                    'vatin' => $vatin,
                    'qty' => $qty,
                    'amount_tax_declare' => $amount_tax_declare[$i],
                    'purchase_type' => $purchase_type[$i],
                    'tax_type' => 3,
                    'tax_id' => $tax_id[$i],
                    'status_tax' => 'confirmed'
                );
                
                
                $check_update = $this->taxes_model->check_update_purchase_tax($purchase_id[$i]);
                
                if ($check_update == 1) {
                    $this->taxes_model->updatePurchaseTax($purchase_id[$i], $purchase_tax);
                } else {
                    $this->taxes_model->addPurchasingTax($purchase_tax);
                }
            }
            
        }
        
        if ($this->form_validation->run() == true) {
            
            $this->session->set_userdata('remove_pols', 1);
            $this->session->set_flashdata('message', $this->lang->line("purchasing_tax_added"));
            redirect('taxes/large_purchasing_tax');
        } else {
            $arr = array();
            if ($this->input->get('data')) {
                $arr = explode(',', $this->input->get('data'));
            }
            $purchase_taxes               = $this->taxes_model->getPurchaseTaxes($arr);
            $this->data['modal_js']       = $this->site->modal_js();
            $this->data['purchase_taxes'] = $purchase_taxes;
            //print_r($purchase_taxes);
            $this->data['journal_taxes']  = $journal_taxes;
            $this->data['exchange_rate']  = $this->taxes_model->getExchangeRate('KHM');
            //print_r($journal_taxes);
            
            $this->load->view($this->theme . 'taxes/add_large_purchasing_tax_form', $this->data);
        }
    }
    

    public function add_small_purchasing_tax_form()
    {
        
        $this->erp->checkPermissions();
        $this->form_validation->set_rules('date', lang('date'), 'required');
        
        $setting = $this->site->get_setting();
        if ($this->form_validation->run() == true) {
            
            $tax_ref            = $this->input->post('tax_ref');
            $pur_id             = $this->input->post('pur_id');
            $purchase_id        = $this->input->post('purchase_id');
            $type               = $this->input->post('ptype');
            $purchase_ref       = $this->input->post('purchase_ref');
            $supplier_id        = $this->input->post('supplier_id');
            $warehouse_id       = $this->input->post('warehouse_id');
            $issuedate          = $this->input->post('date');
            $amount             = $this->input->post('amount');
            $non_tax_pur        = $this->input->post('non_tax_pur');
            $value_import       = $this->input->post('value_import');
            $amount_tax         = $this->input->post('amount_tax');
            $amount_declare     = $this->input->post('amount_decleared');
            $amount_tax_declare = $this->input->post('amount_tax_declare');
            $purchase_type      = $this->input->post('purchase_type');
            $tax_id             = $this->input->post('tax_id');
            $c                  = count($purchase_id);
            $journal_taxes      = 0;
            
            $this->load->model('purchases_model');
            for ($i = 0; $i < $c; $i++) {
                $qty = 0;
                if ($purchase_id[$i] != "") {
                    $sale_items = $this->purchases_model->getItemsByPurchaseId($purchase_id[$i]);
                    foreach ($sale_items as $item) {
                        $qty += $item->quantity;
                    }
                }
                
                if ($supplier_id[$i] != "") {
                    $getSupplier = $this->purchases_model->getSupplierById($supplier_id[$i]);
                } else {
                    $getSupplier = $this->purchases_model->getSupplierById($setting->default_biller);
                }
                $vatin        = $getSupplier->vat_no;
                $purchase_tax = array(
                    'reference_no' => $purchase_ref[$i],
                    'purchase_id' => $purchase_id[$i],
                    'type' => $type[$i],
                    'purchase_ref' => $purchase_ref[$i],
                    'supplier_id' => $supplier_id[$i],
                    'group_id' => $warehouse_id[$i],
                    'issuedate' => $issuedate,
                    'amount' => $amount[$i],
                    'value_import' => $value_import[$i],
                    'non_tax_pur' => $non_tax_pur[$i],
                    'amount_tax' => $amount_tax[$i],
                    'amount_declear' => $amount_declare[$i],
                    'vatin' => $vatin,
                    'qty' => $qty,
                    'amount_tax_declare' => $amount_tax_declare[$i],
                    'purchase_type' => $purchase_type[$i],
                    'tax_type' => 1,
                    'tax_id' => $tax_id[$i],
                    'status_tax' => 'confirmed'
                );
                
                
                $check_update = $this->taxes_model->check_update_purchase_tax($purchase_id[$i]);
                
                //$this->erp->print_arrays($purchase_tax);
                
                
                if ($check_update == 1) {
                    $this->taxes_model->updatePurchaseTax($purchase_id[$i], $purchase_tax);
                } else {
                    $this->taxes_model->addPurchasingTax($purchase_tax);
                }
            }
            
        }
        
        if ($this->form_validation->run() == true) {
            
            $this->session->set_userdata('remove_pols', 1);
            $this->session->set_flashdata('message', $this->lang->line("purchasing_tax_added"));
            redirect('taxes/small_purchasing_tax');
        } else {
            $arr = array();
            if ($this->input->get('data')) {
                $arr = explode(',', $this->input->get('data'));
            }
            $purchase_taxes               = $this->taxes_model->getPurchaseTaxes($arr);
            $this->data['modal_js']       = $this->site->modal_js();
            $this->data['purchase_taxes'] = $purchase_taxes;
            //print_r($purchase_taxes);
            $this->data['journal_taxes']  = $journal_taxes;
            $this->data['exchange_rate']  = $this->taxes_model->getExchangeRate('KHM');
            //print_r($journal_taxes);
            
            $this->load->view($this->theme . 'taxes/add_small_purchasing_tax_form', $this->data);
        }
    }    
    
	public function insert_exchange_tax_rate()
    {
        $tax_type_arr    = $this->input->post('tax_type');
        $average_khr_arr = $this->input->post('average_khr');
        $salary_khr      = $this->input->post('salary_khr');
        $month_arr       = $this->input->post('month');
        $year_arr        = $this->input->post('year');
        
        $i = 0;
        foreach ($month_arr as $month) {
            $data   = array(
                'average_khm' => $average_khr_arr[$i],
                'usd' => 1,
                'usd_curency' => 'USD',
                'kh_curency' => 'KHR',
                'salary_khm' => $salary_khr[$i],
                'month' => $month_arr[$i],
                'year' => $year_arr[$i]
            );
            $insert = $this->taxes_model->insertExchangeTaxRate($data);
        }
        
        if ($insert) {
            redirect('taxes/exchange_rate_tax');
        }
        
    }
    
	public function delete_exchangerate_tax($id)
    {
        $del = $this->taxes_model->delete_exchangerate_tax($id);
        if ($del) {
            redirect('taxes/exchange_rate_tax', 'refresh');
        }
    }
   
    public function edit_exhangerate_tax($id)
    {
        $this->data['tax_rate'] = $this->taxes_model->get_exchangerate_tax_by_id($id);
        $this->load->view($this->theme . 'taxes/edit_exchangerate_tax', $this->data);
    }
    
	public function update_exchange_tax_rate_by_id()
    {
        $id          = $this->input->post('id');
        $salary_khr  = $this->input->post('salary_khr');
        $average_khr = $this->input->post('average_khr');
        $month       = $this->input->post('month');
        $year        = $this->input->post('year');
        
        $data = array(
            'salary_khm' => $salary_khr,
            'average_khm' => $average_khr,
            'month' => $month,
            'year' => $year
        );
        $d    = $this->taxes_model->update_exchangerate_tax($id, $data);
        if ($d) {
            redirect('taxes/exchange_rate_tax', 'refresh');
        }
    }
    
    
    
    
    
}
