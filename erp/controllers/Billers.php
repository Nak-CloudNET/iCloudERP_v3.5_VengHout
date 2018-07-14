<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Billers extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->lang->load('billers', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('companies_model');
    }

    function index($action = NULL)
    {
        $this->erp->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['action'] = $action;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('billers')));
        $meta = array('page_title' => lang('billers'), 'bc' => $bc);
        $this->page_construct('billers/index', $meta, $this->data);
    }

    function getBillers()
    {
        $this->erp->checkPermissions('index');

        $this->load->library('datatables');
        $this->datatables
            ->select("id,id as no, COALESCE(code, '') AS code, company, name, vat_no, phone, email, city, country")
            ->from("companies")
            ->where('group_name', 'biller')
            ->add_column("Actions", "<div class=\"text-center\"><a class=\"tip\" title='" . $this->lang->line("edit_billers") . "' href='" . site_url('billers/edit/$1') . "' data-toggle='modal' data-target='#myModal'><i class=\"fa fa-edit\"></i></a> <a href='#' class='tip po' title='<b>" . $this->lang->line("delete_biller") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('billers/deleteByID/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div>", "id");
        //->unset_column('id');
        echo $this->datatables->generate();
    }

    function add()
    {
        $this->erp->checkPermissions(false, true);
        $this->form_validation->set_rules('biller_prefix', lang("biller_prefix"), 'trim|is_unique[companies.biller_prefix]');
        $this->form_validation->set_rules('code',lang('code'),'required');
        $this->form_validation->set_rules('name',lang('name'),'required');
        $this->form_validation->set_rules('phone',lang('phone'),'required');
        $pro=$this->companies_model->getProOrderRef();
        $old_pro_ref    = null;
        $old_cus_ref    = null;
        $old_sup_ref    = null;
        $old_emp_ref    = null;
        foreach ($pro as $p)
        {
            $old_pro_ref    = $p->pro;
            $old_cus_ref    = $p->cus;
            $old_sup_ref    = $p->sup;
            $old_emp_ref    = $p->emp;
        }
        if ($this->form_validation->run('companies/add') == true)
        {
            $whs 		= $this->input->post('cf5');
            $warehouses = '';
            $i 			= 1;
            foreach($whs as $wh)
            {
                if(count($whs)==$i)
                {
                    $warehouses .= $wh;
                }else{
                    $warehouses .= $wh.',';
                }
                $i++;
            }

            $data = array(
                'code' 				=> $this->input->post('code'),
                'name' 				=> $this->input->post('name'),
                'email' 			=> $this->input->post('email'),
                'group_id' 			=> NULL,
                'group_name' 		=> 'biller',
                'company' 			=> $this->input->post('company'),
                'company_kh' 		=> $this->input->post('cf1'),
                'address' 			=> $this->input->post('address'),
                'vat_no' 			=> $this->input->post('vat_no'),
                'city' 				=> $this->input->post('city'),
                'state' 			=> $this->input->post('state'),
                'postal_code' 		=> $this->input->post('postal_code'),
                'wifi_code' 		=> $this->input->post('wifi_code'),
                'country' 			=> $this->input->post('country'),
                'contact_person' 	=> $this->input->post('contact_person'),
                'phone' 			=> $this->input->post('phone'),
                'logo' 				=> $this->input->post('logo'),
                'business_activity'	=> $this->input->post('business'),
                'group'				=> $this->input->post('group'),
                'village'			=> $this->input->post('village'),
                'street'			=> $this->input->post('Street'),
                'sangkat'           => $this->input->post('Commune'),
                'district' 			=> $this->input->post('District'),
                'cf1' 				=> $this->input->post('cf1'),
                'cf2' 				=> $this->input->post('cf2'),
                'cf3' 				=> $this->input->post('cf3'),
                'cf4' 				=> $this->input->post('cf4'),
                'cf5' 				=> $warehouses,
                'cf6' 				=> $this->input->post('cf6'),
                'invoice_footer' 	=> $this->input->post('invoice_footer'),
                'start_date' 		=> $this->erp->fsd($this->input->post('start_date')),
                'end_date' 			=> $this->erp->fsd($this->input->post('end_date')),
                'period' 			=> $this->input->post('period'),
                'amount' 			=> $this->input->post('amount'),
                'begining_balance' 	=> $this->input->post('beginning_balance'),
                'biller_prefix' 	=> $this->input->post('biller_prefix'),
                'public_charge_id' 	=> ''

            );

        } elseif ($this->input->post('add_biller')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('billers');
        }
        if($this->companies_model->getCompanyByCode($this->input->post('code')))
        {
            $this->session->set_flashdata('error', $this->lang->line("Project code already exist"));
            redirect("billers");
        }else{
            if ($this->form_validation->run() == true && $biller_id = $this->companies_model->addCompany($data)) {
                if($biller_id > 0){
                    $date = date('Y-m') . "-01";
                    for ($i = 0; $i <= 24; $i++) {
                        $refs[] = array(
                            'biller_id' => $biller_id,
                            'date'      => $date,
                            'so'        => 1,
                            'qu'        => 1,
                            'po'        => 1,
                            'to'        => 1,
                            'pos'       => 1,
                            'do'        => 1,
                            'pay'       => 1,
                            're'        => 1,
                            'ex'        => 1,
                            'sp'        => 1,
                            'pp'        => 1,
                            'sl'        => 1,
                            'tr'        => 1,
                            'rep'       => 1,
                            'con'       => 1,
                            'pj'        => 1,
                            'sd'        => 1,
                            'es'        => 1,
                            'esr'       => 1,
                            'sao'       => 1,
                            'poa'       => 1,
                            'pq'        => 1,
                            'jr'        => 1,
                            'pro'       => 1,
                            'cus'       => $old_cus_ref?$old_cus_ref:1,
                            'sup'       => $old_sup_ref?$old_sup_ref:1,
                            'emp'       => $old_emp_ref?$old_emp_ref:1
                        );
                        $date = date('Y-m-d', strtotime('+1 month', strtotime($date)));
                    }

                    $this->companies_model->addRefernce($refs);
                }
                if ($this->site->getReference('pro') == $this->input->post('code')) {
                    $this->companies_model->updateProRef($old_pro_ref + 1);
                }
                $this->session->set_flashdata('message', $this->lang->line("biller_added"));
                redirect("billers");
            } else {
                $this->data['logos']        = $this->getLogoList();
                $this->data['error']        = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
                $this->data['modal_js']     = $this->site->modal_js();
                $this->data['warehouses']   = $this->site->getAllWarehouses();
                $this->data['reference']    = $this->site->getReference('pro');
                $this->load->view($this->theme . 'billers/add', $this->data);
            }
        }
    }

    function edit($id = NULL)
    {
        $this->erp->checkPermissions(false, true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        //$this->form_validation->set_rules('company', lang("company"), 'required');
        $company_details = $this->companies_model->getCompanyByID($id);
        $this->form_validation->set_rules('code',lang('code'),'required');
        $this->form_validation->set_rules('name',lang('name'),'required');
        $this->form_validation->set_rules('phone',lang('phone'),'required');

        if ($this->form_validation->run() == true) {

            $whs = $this->input->post('cf5');
            $warehouses = '';
            $i = 1;
            foreach($whs as $wh){
                if(count($whs)==$i){
                    $warehouses .= $wh;
                }else{
                    $warehouses .= $wh.',';
                }
                $i++;
            }

            $data = array('code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'group_id' => NULL,
                'group_name' => 'biller',
                'company' => $this->input->post('company'),
                'company_kh' => $this->input->post('cf1'),
                'address' => $this->input->post('address'),
                'vat_no' => $this->input->post('vat_no'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'wifi_code' => $this->input->post('wifi_code'),
                'country' => $this->input->post('country'),
                'business_activity'=>$this->input->post('business'),
                'group'=>$this->input->post('group'),
                'village'=>$this->input->post('village'),
                'street'=>$this->input->post('Street'),
                'sangkat'=>$this->input->post('Commune'),
                'district'=>$this->input->post('District'),
                'contact_person' => $this->input->post('contact_person'),
                'phone' => $this->input->post('phone'),
                'logo' => $this->input->post('logo'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $warehouses,
                'cf6' => $this->input->post('cf6'),
                'invoice_footer' => $this->input->post('invoice_footer'),
                'start_date' => $this->erp->fsd($this->input->post('start_date')),
                'end_date' => $this->erp->fsd($this->input->post('end_date')),
                'period' => $this->input->post('period'),
                'amount' => $this->input->post('amount'),
                'begining_balance' => $this->input->post('beginning_balance'),
                'biller_prefix' => $this->input->post('biller_prefix'),
                'public_charge_id' => ''
            );
            //$this->erp->print_arrays($data);
        } elseif ($this->input->post('edit_biller')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('billers');
        }
        if ($this->form_validation->run() == true && $this->companies_model->updateCompany($id, $data)) {
            $this->session->set_flashdata('message', $this->lang->line("biller_updated"));
            redirect("billers");
        } else {
            $this->data['biller'] = $company_details;
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['logos'] = $this->getLogoList();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->load->view($this->theme . 'billers/edit', $this->data);
        }
    }

    function deleteByID($id = NULL)
    {
        $this->erp->checkPermissions(NULL, TRUE);
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->companies_model->deleteBiller($id)) {
            $this->companies_model->deleteOrderReByBillerID($id);
            echo $this->lang->line("biller_deleted");
        } else {
            $this->session->set_flashdata('warning', lang('biller_x_deleted_have_sales'));
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 0);</script>");
        }
    }

    function suggestions($term = NULL, $limit = NULL)
    {
        $this->erp->checkPermissions('index');

        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['results'] = $this->companies_model->getBillerSuggestions($term, $limit);
        echo json_encode($rows);
    }

    function getBiller($id = NULL)
    {
        $this->erp->checkPermissions('index');

        $row = $this->companies_model->getCompanyByID($id);
        echo json_encode(array(array('id' => $row->id, 'text' => $row->company)));
    }

    public function getLogoList()
    {
        $this->load->helper('directory');
        $dirname = "assets/uploads/logos";
        $ext = array("jpg", "png", "jpeg", "gif");
        $files = array();
        if ($handle = opendir($dirname)) {
            while (false !== ($file = readdir($handle)))
                for ($i = 0; $i < sizeof($ext); $i++)
                    if (stristr($file, "." . $ext[$i])) //NOT case sensitive: OK with JpeG, JPG, ecc.
                        $files[] = $file;
            closedir($handle);
        }
        sort($files);
        return $files;
    }

    function biller_actions()
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
                        if (!$this->companies_model->deleteBiller($id)) {
                            $error = true;
                        }
                    }
                    if ($error) {
                        $this->session->set_flashdata('warning', lang('billers_x_deleted_have_sales'));
                    } else {
                        $this->session->set_flashdata('message', $this->lang->line("billers_deleted"));
                    }
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('billers'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('no'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('code'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('company'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('name'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('vat_number'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('phone'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('email_address'));
                    $this->excel->getActiveSheet()->SetCellValue('H1', lang('city'));
                    $this->excel->getActiveSheet()->SetCellValue('I1', lang('country'));
                    // $BStyle = array(
                    // 'borders' => array(
                    // 'allborders' => array(
                    // )
                    // )
                    // );
                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                        $customer = $this->site->getCompanyByID($id);
                        $customer = $this->site->getCompanyByID($id);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $customer->id);
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $customer->code." ");
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $customer->company);
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $customer->name);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $customer->vat_no." ");
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $customer->phone." ");
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $customer->email);
                        $this->excel->getActiveSheet()->SetCellValue('H' . $row, $customer->city);
                        $this->excel->getActiveSheet()->SetCellValue('I' . $row, $customer->country);

                        // $this->excel->getActiveSheet()->getStyle('A'.$row.':K'.$row)->applyFromArray($BStyle);
                        $row++;

                    }
                    //$this->excel->getActiveSheet()->getStyle('A1:K1')->applyFromArray($BStyle);
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
                    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'billers_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
                                PHP_EOL . ' as appropriate for your directory structure');
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
                $this->session->set_flashdata('error', $this->lang->line("no_biller_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    public function getWarehouses(){
        $warehouses = $this->site->getAllWarehouses();

        echo json_encode($warehouses);
    }

}
