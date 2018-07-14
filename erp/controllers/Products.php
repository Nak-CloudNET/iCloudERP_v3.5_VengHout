<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends MY_Controller
{
    //********* Kindly to inform for beautiful code first before coding , invoid from messy coding ******/
    
    function __construct()
    {
        parent::__construct();
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->lang->load('settings', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('settings_model');
        $this->load->model('sales_model');
        $this->load->model('purchases_model');
        
        $this->lang->load('products', $this->Settings->language);
        $this->load->library('form_validation');
        $this->load->model('products_model');
        $this->digital_upload_path = 'files/';
        $this->upload_path = 'assets/uploads/';
        $this->thumbs_path = 'assets/uploads/thumbs/';
        $this->image_types = 'gif|jpg|jpeg|png|tif';
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
        $this->allowed_file_size = '1024';
        $this->popup_attributes = array('width' => '900', 'height' => '600', 'window_name' => 'erp_popup', 'menubar' => 'yes', 'scrollbars' => 'yes', 'status' => 'no', 'resizable' => 'yes', 'screenx' => '0', 'screeny' => '0');
        if(!$this->Owner && !$this->Admin) {
            $gp = $this->site->checkPermissions();
            $this->permission = $gp[0];
            $this->permission[] = $gp[0];
        } else {
            $this->permission[] = NULL;
        }
        $this->default_biller_id = $this->site->default_biller_id();
    }
    
    function index($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('index',null,'products');
        $this->data['products'] = $this->site->getProducts();
        $this->data['categories'] = $this->site->getAllCategories();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
            
        } else {
            $this->data['warehouses'] = $this->products_model->getUserWarehouses();
            if($warehouse_id){
                $this->data['warehouse_id'] = $warehouse_id;
                $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
            }else{
                //$this->data['warehouse_id'] = str_replace(',', '-',$this->session->userdata('warehouse_id'));
                $this->data['warehouse_id'] = NULL;
                $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->products_model->getUserWarehouses() : NULL;
            }
        }
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('products')));
        $meta = array('page_title' => lang('products'), 'bc' => $bc);
        $this->page_construct('products/index', $meta, $this->data);
    }
    
    function adjustment_actions()
    {
        if (!empty($_POST['val'])) {
            if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
                $row = 2;
                $this->load->library('excel');
                $this->excel->setActiveSheetIndex(0);
                $this->excel->getActiveSheet()->setTitle('adjustments');
                $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
                $this->excel->getActiveSheet()->SetCellValue('C1', lang('warehouse'));
                $this->excel->getActiveSheet()->SetCellValue('D1', lang('created_by'));
                $this->excel->getActiveSheet()->SetCellValue('E1', lang('note'));
               
                foreach ($_POST['val'] as $id) { 
                    $adjust = $this->products_model->getAdjustment($id);
                    $this->excel->getActiveSheet()->SetCellValue('A' . $row, $adjust->date);
                    $this->excel->getActiveSheet()->SetCellValue('B' . $row,  $adjust->reference_no);
                    $this->excel->getActiveSheet()->SetCellValue('C' . $row, $adjust->wh_name);
                    $this->excel->getActiveSheet()->SetCellValue('D' . $row, $adjust->first_name." ".$adjust->last_name);
                    $this->excel->getActiveSheet()->SetCellValue('E' . $row, $this->erp->decode_html(strip_tags($adjust->note)));
                    $row++; 
                }
                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $filename = 'adjustments_reports' . date('Y_m_d_H_i_s');
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
                    
                    $this->excel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
                    $this->excel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                    return $objWriter->save('php://output');
                }
                if ($this->input->post('form_action') == 'export_excel') {
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                    header('Cache-Control: max-age=0');
                    
                    $this->excel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
                    
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    return $objWriter->save('php://output');
                }
            }
        }else {
            $this->session->set_flashdata('error', $this->lang->line("no_adjustment_selected"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    
    function add_procategory()
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('code', lang("category_code"), 'trim|is_unique[categories.code]|required');
        $this->form_validation->set_rules('name', lang("name"), 'required|min_length[3]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name');
            $code = $this->input->post('code');

            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            } else {
                $photo = NULL;
            }
        } elseif ($this->input->post('add_category')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("products/add");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addCategory($name, $code, $photo)) {
            $this->session->set_flashdata('message', lang("category_added"));
        //  redirect("products/add");
            if (strpos($_SERVER['HTTP_REFERER'], 'products/add_procategory') !== false) {
                 redirect("products/add");
            }else{
                 redirect("products/add");
            }
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code'),
            );
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'settings/add_category', $this->data);
        }
    }
    
    function add_subcategory($parent_id = NULL)
    {
        $this->load->helper('security');
        $this->form_validation->set_rules('category', lang("main_category"), 'required');
        $this->form_validation->set_rules('code', lang("subcategory_code"), 'trim|is_unique[categories.code]|is_unique[subcategories.code]|required');
        $this->form_validation->set_rules('name', lang("subcategory_name"), 'required|min_length[2]');
        $this->form_validation->set_rules('userfile', lang("category_image"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $name = $this->input->post('name');
            $code = $this->input->post('code');
            $category = $this->input->post('category');
            if ($_FILES['userfile']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['max_width'] = $this->Settings->iwidth;
                $config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                //$data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->twidth;
                $config['height'] = $this->Settings->theight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            } else {
                $photo = NULL;
            }
        } elseif ($this->input->post('add_subcategory')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("products/add");
        }

        if ($this->form_validation->run() == true && $this->settings_model->addSubCategory($category, $name, $code, $photo)) {
            $this->session->set_flashdata('message', lang("subcategory_added"));
            redirect("products/add", 'refresh');
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text', 'class' => 'form-control',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['code'] = array('name' => 'code',
                'id' => 'code',
                'type' => 'text',
                'class' => 'form-control',
                'required' => 'required',
                'value' => $this->form_validation->set_value('code'),
            );
            $this->data['parent_id'] = $parent_id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['categories'] = $this->settings_model->getAllCategories();
            $this->load->view($this->theme . 'settings/add_subcategory', $this->data);
        }
    }

    function getProducts($warehouse_id = NULL)
    {
        if (!$this->Owner && !$this->Admin) {
            $gp = $this->site->checkPermissions();
            $this->permission = $gp[0];
            $this->permission[] = $gp[0];
        } else {
            $this->permission[] = NULL;
        }

        $this->erp->checkPermissions('index',null,'products');
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
        if ($this->input->get('product_type')) {
            $product_type = $this->input->get('product_type');
        } else {
            $product_type = NULL;
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
        
        $detail_link = anchor('products/view/$1', '<i class="fa fa-file-text-o"></i> ' . lang('product_details'));
        $delete_link = "<a href='products/delete/$1' class='tip po' title='<b>" . $this->lang->line("delete_product") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger' id='a__$1' href='" . site_url('products/delete/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "
            . lang('delete_product') . "</a>"; 
        $edit_link = anchor('products/edit/$1', '<i class="fa fa-edit"></i> ' . lang('edit_product'), 'class="sledit"');

        $single_barcode = anchor_popup('products/single_barcode/$1/' . ($warehouse_id ? $warehouse_id : ''), '<i class="fa fa-print"></i> ' . lang('print_barcode'), $this->popup_attributes);

        $single_label = anchor_popup('products/single_label/$1/' . ($warehouse_id ? $warehouse_id : ''), '<i class="fa fa-print"></i> ' . lang('print_label'), $this->popup_attributes);
        $action = '<div class="text-center"><div class="btn-group text-left">'
            . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
            . lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>' . $detail_link . '</li>'
            .(($this->Owner || $this->Admin || $this->GP['products-add']) ? '<li><a href="' . site_url('products/add/$1') . '"><i class="fa fa-plus-square"></i> ' . lang('duplicate_product') . '</a></li>' : '');

        $action .= '<li><a href="' . site_url() . 'assets/uploads/$2" data-type="image" data-toggle="lightbox"><i class="fa fa-file-photo-o"></i> '
            . lang('view_image') . '</a></li>'
            .(($this->Owner || $this->Admin || $this->GP['products-print_barcodes']) ? '<li>' . $single_barcode . '</li>' : '') .''
             .(($this->Owner || $this->Admin || $this->GP['products-print_barcodes']) ? '<li>' . $single_label . '</li>' : '')
            .(($this->Owner || $this->Admin) ? '<li>'.$edit_link.'</li>' : ($this->GP['products-edit'] ? '<li>'.$edit_link.'</li>' : '')).
            (($this->Owner || $this->Admin) ? '<li>'.$delete_link.'</li>' : ($this->GP['products-delete'] ? '<li>'.$delete_link.'</li>' : '')).
            
            '</ul>
        </div></div>';

        if($warehouse_id) {
            $warehouse_id = explode('-', $warehouse_id);
        }

        $this->load->library('datatables');
            if ($this->session->userdata('warehouse_id')) {
                $this->datatables
                ->select($this->db->dbprefix('products') . ".id as productid, " . 
                $this->db->dbprefix('products') . ".image as image, " . 
                $this->db->dbprefix('products') . ".code as code, " . 
                $this->db->dbprefix('products') . ".name as name, " . 
                $this->db->dbprefix('products') . ".name_kh as kname, " .
                $this->db->dbprefix('categories') . ".name as cname,
                subcategories.name as sub_name, 
                cost as cost, 
                price as price, 
                IF(erp_products.type = 'service', 
                        CONCAT(COALESCE(erp_products.quantity, 0), '=', 
                        erp_products.id),
                        CONCAT(COALESCE(SUM(wp.quantity), 0), '=', 
                        erp_products.id)
                    ) as quantity, " .
                $this->db->dbprefix("units").".name as unit, 
                alert_quantity", FALSE)        
                ->from('products');

                if ($this->Settings->display_all_products) {
                    $this->datatables->join("( SELECT * from {$this->db->dbprefix('warehouses_products')} WHERE warehouse_id = {$warehouse_id}) wp", 'products.id=wp.product_id', 'left');
                } else {
                    if($warehouse_id){
                        $this->datatables->join('warehouses_products wp', 'products.id = wp.product_id', 'left')
                        ->where_in('wp.warehouse_id', $warehouse_id)
                        ->where("wp.rack LIKE '%##" . $this->session->userdata('user_id') . "##%'");
                    }else{
                        $this->datatables->join('warehouses_products wp', 'products.id = wp.product_id', 'left')
                        ->where("wp.rack LIKE '%##" . $this->session->userdata('user_id') . "##%'");
                    }
                }

                $this->datatables->join('categories', 'products.category_id=categories.id', 'left')
                ->join('units', 'products.unit=units.id', 'left')
                ->join('subcategories', 'subcategories.id=products.subcategory_id', 'left')
                ->group_by("products.id");
              
            } else {
                $this->datatables
                ->select(
                    $this->db->dbprefix('products') . ".id as productid, " . 
                    $this->db->dbprefix('products') . ".image as image, " . 
                    $this->db->dbprefix('products') . ".code as code, " . 
                    $this->db->dbprefix('products') . ".name as name, " . 
                    $this->db->dbprefix('products') . ".name_kh as kname, " . 
                    $this->db->dbprefix('categories') . ".name as cname, subcategories.name as sub_name, 
                    cost as cost,
                    price as price,
                    IF(erp_products.type = 'service', 
                        CONCAT(COALESCE(erp_products.quantity, 0), '=', 
                        erp_products.id),
                        CONCAT(COALESCE(SUM(wp.quantity), 0), '=', 
                        erp_products.id)
                    ) as quantity, " .
                    $this->db->dbprefix('units').".name as unit, 
                    alert_quantity", FALSE
                    )
                ->from('products')
                ->join('warehouses_products wp', 'products.id = wp.product_id', 'left')
                ->join('categories', 'products.category_id=categories.id', 'left')
                ->join('subcategories', 'subcategories.id=products.subcategory_id', 'left')
                ->join('units', 'products.unit=units.id', 'left');

                if($warehouse_id){
                    $this->datatables->where_in('wp.warehouse_id', $warehouse_id);
                    $this->datatables->where('wp.quantity <>', NULL);
                }
                $this->datatables->group_by("products.id");
            }

        if (!$this->Owner && !$this->Admin) {
            if (!$gp[0]['products-cost']) {
                $this->datatables->unset_column("cost");
            }
            if (!$gp[0]['products-price']) {
                $this->datatables->unset_column("price");
            }
        }
        if ($product) {
            $this->datatables->where($this->db->dbprefix('products') . ".id", $product);
        }
        if ($category) {
            $this->datatables->where($this->db->dbprefix('products') . ".category_id", $category);
        }
        if ($product_type) {
            $this->datatables->where($this->db->dbprefix('products') . ".inactived", $product_type);
        }else{
            $this->datatables->where($this->db->dbprefix('products') . ".inactived !=", '1');
        }   
        $this->datatables->add_column("Actions", $action, "productid, image, code, name");
        echo $this->datatables->generate();
    }

    function set_rack($product_id = NULL, $warehouse_id = NULL)
    {
        $this->form_validation->set_rules('rack', lang("rack_location"), 'trim|required');

        if ($this->form_validation->run() == true) {
            $data = array('rack' => $this->input->post('rack'),
                'product_id' => $product_id,
                'warehouse_id' => $warehouse_id,
            );
        } elseif ($this->input->post('set_rack')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect("products");
        }

        if ($this->form_validation->run() == true && $this->products_model->setRack($data)) {
            $this->session->set_flashdata('message', lang("rack_set"));
            redirect("products/" . $warehouse_id);
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['product'] = $this->site->getProductByID($product_id);
            $wh_pr = $this->products_model->getProductQuantity($product_id, $warehouse_id);
            $this->data['rack'] = $wh_pr['rack'];
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'products/set_rack', $this->data);

        }
    }

    function product_barcode($product_code = NULL, $bcs = 'code128', $height = 60)
    {
        return "<img src='" . site_url('products/gen_barcode/' . $product_code . '/' . $bcs . '/' . $height) . "' alt='{$product_code}' class='bcimg' />";
    }

    function barcode($product_code = NULL, $bcs = 'code128', $height = 60)
    {
        return site_url('products/gen_barcode/' . $product_code . '/' . $bcs . '/' . $height);
    }

    function gen_barcode($product_code = NULL, $bcs = 'code128', $height = 60, $text = 1)
    {
        $drawText           = ($text != 1) ? FALSE : TRUE;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions     = array('text' => $product_code, 'barHeight' => $height, 'drawText' => $drawText, 'factor' => 1);
        $rendererOptions    = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        $imageResource      = Zend_Barcode::render($bcs, 'image', $barcodeOptions, $rendererOptions);
        return $imageResource;

    }

    function single_barcode($product_id = NULL, $warehouse_id = NULL)
    {
        $this->erp->checkPermissions('print_barcodes', NULL, 'products');
        $product = $this->products_model->getProductByID($product_id);
        $currencies = $this->site->getAllCurrencies();
        $this->data['product'] = $product;
        $options = $this->products_model->getProductOptionsWithWH($product_id);
        if( ! $options) {
            $options = $this->products_model->getProductOptions($product_id);
        }
        $table = '';
        if (!empty($options)) {
            $r = 1;
            foreach ($options as $option) {
                $quantity = $option->wh_qty;
                $warehouse = $this->site->getWarehouseByID(($option->quantity <= 0) ? $this->Settings->default_warehouse :$option->warehouse_id);
                $table .= '<h3 class="'.($option->quantity ? '' : 'text-danger').'">'.$warehouse->name.' ('.$warehouse->code.') - '.$product->name.' - '.$option->name.' ('.lang('quantity').': '.$quantity.')</h3>';
                $table .= '<table class="table table-bordered barcodes"><tbody><tr>';
                for($i=0; $i < $quantity; $i++) {

                    $table .= '<td style="width: 20px;"><table class="table-barcode"><tbody><tr><td colspan="2" class="bold">' . $this->Settings->site_name . '</td></tr><tr><td colspan="2">' . $product->name . ' - '.$option->name.'</td></tr><tr><td colspan="2" class="text-center bc">' . $this->product_barcode($product->code . $this->Settings->barcode_separator . $option->id, 'code128', 60) . '</td></tr>';
                    foreach ($currencies as $currency) {
                        $table .= '<tr><td class="text-left">' . $currency->code . '</td><td class="text-right">' . $this->erp->formatMoney($product->price * $currency->rate) . '</td></tr>';
                    }
                    $table .= '</tbody></table>';
                    $table .= '</td>';
                    $table .= ((bool)($i & 1)) ? '</tr><tr>' : '';

                }
                $r++;
                $table .= '</tr></tbody></table><hr>';
            }
        } else {
            $table .= '<table class="table table-bordered barcodes"><tbody><tr>';
            $num = $product->quantity;
            for ($r = 1; $r <= $num; $r++) {
                if ($r != 1) {
                    $rw = (bool)($r & 1);
                    $table .= $rw ? '</tr><tr>' : '';
                }
                $table .= '<td style="width: 20px;"><table class="table-barcode"><tbody><tr><td colspan="2" class="bold">' . $this->Settings->site_name . '</td></tr><tr><td colspan="2">' . $product->name . '</td></tr><tr><td colspan="2" class="text-center bc">' . $this->product_barcode($product->code, $product->barcode_symbology, 60) . '</td></tr>';
                foreach ($currencies as $currency) {
                    $table .= '<tr><td class="text-left">' . $currency->code . '</td><td class="text-right">' . $this->erp->formatMoney($product->price * $currency->rate) . '</td></tr>';
                }
                $table .= '</tbody></table>';
                $table .= '</td>';
            }
            $table .= '</tr></tbody></table>';
        }

        $this->data['table'] = $table;

        $this->data['page_title'] = lang("print_barcodes");
        $this->load->view($this->theme . 'products/single_barcode', $this->data);
    }

    function single_label($product_id = NULL, $warehouse_id = NULL)
    {
        $this->erp->checkPermissions('print_barcodes', NULL, 'products');

        $product = $this->products_model->getProductByID($product_id);
        $currencies = $this->site->getAllCurrencies();

        $this->data['product'] = $product;
        $options = $this->products_model->getProductOptionsWithWH($product_id);

        $table = '';
        if (!empty($options)) {
            $r = 1;
            foreach ($options as $option) {
                $quantity = $option->wh_qty;
                $warehouse = $this->site->getWarehouseByID($option->warehouse_id);
                $table .= '<h3 class="'.($option->quantity ? '' : 'text-danger').'">'.$warehouse->name.' ('.$warehouse->code.') - '.$product->name.' - '.$option->name.' ('.lang('quantity').': '.$quantity.')</h3>';
                $table .= '<table class="table table-bordered barcodes"><tbody><tr>';
                for($i=0; $i < $quantity; $i++) {
                    if ($i % 4 == 0 && $i > 3) {
                        $table .= '</tr><tr>';
                    }
                    $table .= '<td style="width: 20px;"><table class="table-barcode"><tbody><tr><td colspan="2" class="bold">' . $this->Settings->site_name . '</td></tr><tr><td colspan="2">' . $product->name . ' - '.$option->name.'</td></tr><tr><td colspan="2" class="text-center bc">' . $this->product_barcode($product->code . $this->Settings->barcode_separator . $option->id, 'code128', 30) . '</td></tr>';
                    foreach ($currencies as $currency) {
                        $table .= '<tr><td class="text-left">' . $currency->code . '</td><td class="text-right">' . $this->erp->formatMoney($product->price * $currency->rate) . '</td></tr>';
                    }
                    $table .= '</tbody></table>';
                    $table .= '</td>';
                }
                $r++;
                $table .= '</tr></tbody></table><hr>';
            }
        } else {
            $table .= '<table class="table table-bordered barcodes"><tbody><tr>';
            $num = $product->quantity;
            for ($r = 1; $r <= $num; $r++) {
                $table .= '<td style="width: 20px;"><table class="table-barcode"><tbody><tr><td colspan="2" class="bold">' . $this->Settings->site_name . '</td></tr><tr><td colspan="2">' . $product->name . '</td></tr><tr><td colspan="2" class="text-center bc">' . $this->product_barcode($product->code, $product->barcode_symbology, 30) . '</td></tr>';
                foreach ($currencies as $currency) {
                    $table .= '<tr><td class="text-left">' . $currency->code . '</td><td class="text-right">' . $this->erp->formatMoney($product->price * $currency->rate) . '</td></tr>';
                }
                $table .= '</tbody></table>';
                $table .= '</td>';
                if ($r % 4 == 0 && $r > 3) {
                    $table .= '</tr><tr>';
                }
            }
            $table .= '</tr></tbody></table>';
        }

        $this->data['table'] = $table;
        $this->data['page_title'] = lang("barcode_label");
        $this->load->view($this->theme . 'products/single_label', $this->data);
    }

    function single_label2($product_id = NULL, $warehouse_id = NULL)
    {
        $this->erp->checkPermissions('print_barcodes', NULL, 'products');

        $pr = $this->products_model->getProductByID($product_id);
        $currencies = $this->site->getAllCurrencies();

        $this->data['product'] = $pr;
        $options = $this->products_model->getProductOptionsWithWH($product_id);
        $html = "";

        if (!empty($options)) {
            foreach ($options as $option) {
                for ($r = 1; $r <= $option->wh_qty; $r++) {
                    $html .= '<div class="labels"><strong>' . $pr->name . ' - '.$option->name.'</strong><br>' . $this->product_barcode($pr->code . $this->Settings->barcode_separator . $option->id, 'code128', 25) . '<br><span class="price">'.lang('price') .': ' .$this->Settings->default_currency. ' ' . $this->erp->formatMoney($pr->price) . '</span></div>';
                }
            }
        } else {
            for ($r = 1; $r <= $pr->quantity; $r++) {
                $html .= '<div class="labels"><strong>' . $pr->name . '</strong><br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 25) . '<br><span class="price">'.lang('price') .': ' .$this->Settings->default_currency. ' ' . $this->erp->formatMoney($pr->price) . '</span></div>';
            }
        }

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("barcode_label");
        $this->load->view($this->theme . 'products/single_label2', $this->data);
    }

    function print_barcodes($product_id = NULL)
    {
        $this->erp->checkPermissions('print_barcodes', NULL, 'products');
        $this->form_validation->set_rules('style', lang("style"), 'required');
        if ($this->form_validation->run() == true) {

            $style = $this->input->post('style');
            $bci_size = ($style == 111 || $style == 10 || $style == 12 || $style == 90 || $style == 6 ? 50 : ($style == 14 || $style == 16 || $style == 18 ? 30 : 20));
            
            $currencies = $this->site->getAllCurrencies();
            $s = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;

            if ($s < 1) {
                $this->session->set_flashdata('error', lang('no_product_selected'));
                redirect("products/print_barcodes");
            }
            
            for ($m = 0; $m < $s; $m++) {
                $pid = $_POST['product_id'][$m];
                $quantity = $_POST['quantity'][$m];
                $product = $this->products_model->getProductWithCategory($pid);
                if ($variants = $this->products_model->getVariantNameByArrayId($this->input->post('vars'))) {
                    foreach ($variants as $option) {
                        //if ($this->input->post('vt_'.$product->id.'_'.$option->id)) {
                            $barcodes[] = array(
                                'biller' => $this->input->post('biller'),
                                'site' => $this->input->post('site_name') ? $this->Settings->site_name : FALSE,
                                'code' => $product->code,
                                'name' => $this->input->post('product_name') ? $product->name . ' - ' . $option->name : FALSE,
                                'image' => $this->input->post('product_image') ? $product->image : FALSE,
                                'barcode' => $this->product_barcode($product->code . $this->Settings->barcode_separator . $option->id, 'code128', $bci_size),
                                'price' => $this->input->post('price') ? $this->erp->formatMoney($option->price != 0 ? $option->price : $product->price) : FALSE,
                                'unit' => $this->input->post('unit') ? $product->unit : FALSE,
                                'category' => $this->input->post('category') ? $product->category : FALSE,
                                'currencies' => $this->input->post('currencies'),
                                'variants' => $this->input->post('variants') ? $variants : FALSE,
                                'quantity' => $quantity
                                );


                        // }
                    }
                } else {
                    $barcodes[] = array(
                        'biller' => $this->input->post('biller'),
                        'site'          => $this->input->post('site_name') ? $this->Settings->site_name : FALSE,
                        'code' => $product->code,
                        'name'          => $this->input->post('product_name') ? $product->name : FALSE,
                        'image'         => $this->input->post('product_image') ? $product->image : FALSE,
                        'barcode'       => $this->product_barcode($product->code, $product->barcode_symbology, $bci_size),
                        'price'         => $this->input->post('price') ?  $this->erp->formatMoney($product->price) : FALSE,
                        'unit'          => $this->input->post('unit') ? $product->unit : FALSE,
                        'category'      => $this->input->post('category') ? $product->category : FALSE,
                        'currencies'    => $this->input->post('currencies'),
                        'variants'      => FALSE,
                        'quantity'      => $quantity
                    );
                }
            }

            $this->data['barcodes'] = $barcodes;
            $this->data['currencies'] = $currencies;
            $this->data['style'] = $style;
            $this->data['items'] = false;
            $this->data['cur'] = $this->products_model->getRate();
            $this->data['billers'] = $this->site->getAllCompanies('biller');
        
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('print_barcodes')));
            $meta = array('page_title' => lang('print_barcodes'), 'bc' => $bc);
            $this->page_construct('products/print_barcodes', $meta, $this->data);

        } else {

            if ($this->input->get('purchase') || $this->input->get('transfer')) {
                if ($this->input->get('purchase')) {
                    $purchase_id = $this->input->get('purchase', TRUE);
                    $items = $this->purchases_model->getAllPurchaseItems($purchase_id);
                } elseif ($this->input->get('transfer')) {
                    $transfer_id = $this->input->get('transfer', TRUE);
                    $items = $this->products_model->getTransferItems($transfer_id);
                }
                if ($items) {
                    foreach ($items as $item) {
                        if ($row = $this->products_model->getProductByID($item->product_id)) {
                            $selected_variants = false;
                            if ($variants = $this->products_model->getProductOptions($row->id)) {
                                foreach ($variants as $variant) {
                                    $selected_variants[$variant->id] = isset($pr[$row->id]['selected_variants'][$variant->id]) && !empty($pr[$row->id]['selected_variants'][$variant->id]) ? 1 : ($variant->id == $item->option_id ? 1 : 0);
                                }
                            }
                            $pr[$row->id] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'code' => $row->code, 'name' => $row->name, 'price' => $row->price, 'qty' => $row->quantity == NULL ? $row->quantity = 1 : $row->quantity, 'variants' => $variants, 'selected_variants' => $selected_variants);
                        }
                    }
                    $this->data['message'] = lang('products_added_to_list');
                }
            }

            if ($this->input->get('category')) {
                if ($products = $this->products_model->getCategoryProducts($this->input->get('category'))) {
                    foreach ($products as $row) {
                        $selected_variants = false;
                        if ($variants = $this->products_model->getProductOptions($row->id)) {
                            foreach ($variants as $variant) {
                                $selected_variants[$variant->id] = $variant->quantity > 0 ? 1 : 0;
                            }
                        }
                        $pr[$row->id] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'code' => $row->code, 'name' => $row->name, 'price' => $row->price, 'qty' => $row->quantity == NULL ? $row->quantity = 1 : $row->quantity, 'variants' => $variants, 'selected_variants' => $selected_variants);
                    }
                    $this->data['message'] = lang('products_added_to_list');
                } else {
                    $pr = array();
                    $this->session->set_flashdata('error', lang('no_product_found'));
                }
            }

            if ($this->input->get('subcategory')) {
                if ($products = $this->products_model->getSubCategoryProducts($this->input->get('subcategory'))) {
                    foreach ($products as $row) {
                        $selected_variants = false;
                        if ($variants = $this->products_model->getProductOptions($row->id)) {
                            foreach ($variants as $variant) {
                                $selected_variants[$variant->id] = $variant->quantity > 0 ? 1 : 0;
                            }
                        }
                        $pr[$row->id] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'code' => $row->code, 'name' => $row->name, 'price' => $row->price, 'qty' => $row->quantity == NULL ? $row->quantity = 1 : $row->quantity, 'variants' => $variants, 'selected_variants' => $selected_variants);
                    }
                    $this->data['message'] = lang('products_added_to_list');
                } else {
                    $pr = array();
                    $this->session->set_flashdata('error', lang('no_product_found'));
                }
            }

            $this->data['items'] = isset($pr) ? json_encode($pr) : false;
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('print_barcodes')));
            $meta = array('page_title' => lang('print_barcodes'), 'bc' => $bc);
            $this->page_construct('products/print_barcodes', $meta, $this->data);
        }
    }

    function add($id = NULL, $param = null)
    {
        $this->erp->checkPermissions('add',null,'products');
        $this->load->helper('security');
        $warehouses = $this->site->getAllWarehouses();
        if ($this->input->post('type') == 'standard') {
            $this->form_validation->set_rules('cost', lang("product_cost"), 'required');
        }
        if ($this->input->post('barcode_symbology') == 'ean13') {
            $this->form_validation->set_rules('code', lang("product_code"), 'min_length[13]|max_length[13]');
        }
        $this->form_validation->set_rules('code', lang("product_code"), 'is_unique[products.code]');
        $this->form_validation->set_rules('product_image', lang("product_image"), 'xss_clean');
        $this->form_validation->set_rules('digital_file', lang("digital_file"), 'xss_clean');
        $this->form_validation->set_rules('userfile', lang("product_gallery_images"), 'xss_clean');
        $this->form_validation->set_rules('category',lang('category'),'required');
        $this->form_validation->set_rules('unit',lang('product_unit'),'required');
        $warehouse_qty = array();
        if ($this->form_validation->run() == true) {
            $tax_rate = $this->input->post('tax_rate') ? $this->site->getTaxRateByID($this->input->post('tax_rate')) : NULL;
            if($this->input->post('inactive')) {
                $inactived = $this->input->post('inactive');
            } else {
                $inactived = 0;
            }
            $product_type           = $this->input->post('type');
            $cost_combo_item        = $this->input->post('cost_combo_item');
            $prodcut_cost           = $this->erp->formatDecimal($this->input->post('cost'));
            $data = array(
                'code'              => $this->input->post('code'),
                'barcode_symbology' => $this->input->post('barcode_symbology'),
                'name'              => $this->input->post('name'),
                'name_kh'           => $this->input->post('name_kh'),
                'type'              => $product_type,
                'category_id'       => $this->input->post('category'),
                'subcategory_id'    => $this->input->post('subcategory') ? $this->input->post('subcategory') : NULL,
                'cost'              => $product_type == 'combo' ? $cost_combo_item : $prodcut_cost,
                'price'             => $this->erp->formatDecimal($this->input->post('price')),
                'unit'              => $this->input->post('unit'),
                'tax_rate'          => $this->input->post('tax_rate'),
                'tax_method'        => $this->input->post('tax_method'),
                'alert_quantity'    => $this->input->post('alert_quantity'),
                'track_quantity'    => $this->input->post('track_quantity') ? $this->input->post('track_quantity') : '0',
                'details'           => $this->input->post('details'),
                'product_details'   => $this->input->post('product_details'),
                'supplier1'         => $this->input->post('supplier'),
                'supplier1price'    => $this->erp->formatDecimal($this->input->post('supplier_price')),
                'supplier2'         => $this->input->post('supplier_2'),
                'supplier2price'    => $this->erp->formatDecimal($this->input->post('supplier_2_price')),
                'supplier3'         => $this->input->post('supplier_3'),
                'supplier3price'    => $this->erp->formatDecimal($this->input->post('supplier_3_price')),
                'supplier4'         => $this->input->post('supplier_4'),
                'supplier4price'    => $this->erp->formatDecimal($this->input->post('supplier_4_price')),
                'supplier5'         => $this->input->post('supplier_product'),
                'supplier5price'    => $this->input->post('supplier_product_price'),
                'cf1'               => $this->input->post('cf1'),
                'cf2'               => $this->input->post('cf2'),
                'cf3'               => $this->input->post('cf3'),
                'cf4'               => $this->input->post('cf4'),
                'cf5'               => $this->input->post('cf5'),
                'cf6'               => $this->input->post('cf6'),
                'contruction_status'=> $this->input->post('contruction_status'),
                'promotion'         => $this->input->post('promotion'),
                'promo_price'       => trim($this->input->post('promo_price')),
                'start_date'        => $this->erp->fsd($this->input->post('start_date')),
                'end_date'          => $this->erp->fsd($this->input->post('end_date')),
                'supplier1_part_no' => $this->input->post('supplier_part_no'),
                'supplier2_part_no' => $this->input->post('supplier_2_part_no'),
                'supplier3_part_no' => $this->input->post('supplier_3_part_no'),
                'supplier4_part_no' => $this->input->post('supplier_4_part_no'),
                'supplier5_part_no' => $this->input->post('supplier_5_part_no'),           
                'currentcy_code'    => $this->input->post('currency'),
                'inactived'         => $inactived,
                'service_type'      => $this->input->post('include_cost'),
                'brand_id'          => $this->input->post('brand')
            );
            
            $related_straps = $this->input->post('related_strap');
            for($i=0; $i<sizeof($related_straps); $i++) {
                $product_name = $this->site->getProductByCode($related_straps[$i]);
                $related_products[] = array(
                                            'product_code' => $this->input->post('code'),
                                            'related_product_code' => $related_straps[$i],
                                            'product_name' => $product_name->name,
                                            );
            }
            
            $this->load->library('upload');
            if ($this->input->post('type') == 'standard') {
                $wh_total_quantity = 0;
                $pv_total_quantity = 0;
                for ($s = 2; $s > 5; $s++) {
                    $data['suppliers' . $s] = $this->input->post('supplier_' . $s);
                    $data['suppliers' . $s . 'price'] = $this->input->post('supplier_' . $s . '_price');
                }

                if ($this->input->post('attributes')) {
                    $a = sizeof($_POST['attr_name']);
                    for ($r = 0; $r <= $a; $r++) {
                        if (isset($_POST['attr_name'][$r])) {
                            if(isset($_POST['attr_warehouse'][$r]) == NULL){
                                $_POST['attr_warehouse'][$r] = '';
                            }
                            if(isset($_POST['attr_quantity_unit'][$r]) == NULL){
                                $_POST['attr_quantity_unit'][$r] = '';
                            }
                            if(isset($_POST['attr_quantity'][$r]) == NULL){
                                $_POST['attr_quantity'][$r] = '';
                            }
                            if(isset($_POST['attr_cost'][$r]) == NULL){
                                $_POST['attr_cost'][$r] = '';
                            }
                            if(isset($_POST['attr_price'][$r]) == NULL){
                                $_POST['attr_price'][$r] = '';
                            }
                            $product_attributes[] = array(
                                'name' => $_POST['attr_name'][$r],
                                'warehouse_id' => $_POST['attr_warehouse'][$r],
                                'qty_unit' => $_POST['attr_quantity_unit'][$r],
                                'quantity' => $_POST['attr_quantity'][$r],
                                'cost' => $_POST['attr_cost'][$r],
                                'price' => $_POST['attr_price'][$r],
                            );
                            $pv_total_quantity += $_POST['attr_quantity'][$r];
                        }
                    }
                } else {
                    $product_attributes = NULL;
                }
                
            } else {
                $warehouse_qty = NULL;
                $product_attributes = NULL;
            }
            if ($this->input->post('type') == 'service') {
                $data['track_quantity'] = 0;
                $wh_total_quantity  = 1;
                /*if($this->input->post('service_type') == 1){
                    $wh_total_quantity  = 1;
                }*/
            } elseif ($this->input->post('type') == 'combo') {
                $total_price = 0;
                $c = sizeof($_POST['combo_item_code']) - 1;
                for ($r = 0; $r <= $c; $r++) {
                    if (isset($_POST['combo_item_code'][$r]) && isset($_POST['combo_item_quantity_unit'][$r]) && isset($_POST['combo_item_price'][$r])) {
                        $items[] = array(
                            'item_code'     => $_POST['combo_item_code'][$r],
                            'quantity'      => $_POST['combo_item_quantity_unit'][$r],
                            'unit_price'    => $_POST['combo_item_price'][$r],
                        );
                    }
                    $total_price += $_POST['combo_item_price'][$r] * $_POST['combo_item_quantity_unit'][$r];
                }
                
                $data['track_quantity'] = 0;
            } elseif ($this->input->post('type') == 'digital') {
                $c = sizeof($_POST['combo_item_code']) - 1;
                for ($r = 0; $r <= $c; $r++) {
                    if (isset($_POST['combo_item_code'][$r])) {
                        $items2[] = array(
                            'product_id' => $_POST['combo_item_id'][$r]
                        );
                    }
                }
                $data['track_quantity'] = 0;
            }
            if (!isset($items)) {
                $items = NULL;
            }
            if (!isset($items2)) {
                $items2 = NULL;
            }
            if ($_FILES['product_image']['size'] > 0) {

                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['max_filename'] = 25;
                $config['encrypt_name'] = TRUE;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('product_image')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/add");
                }
                $photo = $this->upload->file_name;
                $data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;

                $config['maintain_ratio'] = TRUE;
                $config['width'] = $this->Settings->iwidth;
                $config['height'] = $this->Settings->iheight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                copy($config['new_image'] , $config['source_image']);
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            }
            if ($_FILES['userfile']['name'][0] != "") {

                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $files = $_FILES;
                $cpt = count($_FILES['userfile']['name']);
                for ($i = 0; $i < $cpt; $i++) {

                    $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
                    $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
                    $_FILES['userfile']['size'] = $files['userfile']['size'][$i];

                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                        redirect("products/add");
                    } else {
                        $pho = $this->upload->file_name;

                        $photos[] = $pho;

                        $this->load->library('image_lib');
                        $config['image_library'] = 'gd2';
                        $config['source_image'] = $this->upload_path . $pho;
                        $config['new_image'] = $this->thumbs_path . $pho;
                        $config['maintain_ratio'] = TRUE;
                        //$config['width'] = $this->Settings->twidth;
                        //$config['height'] = $this->Settings->theight;
                        $config['width'] = $this->Settings->iwidth;
                        $config['height'] = $this->Settings->iheight;

                        $this->image_lib->initialize($config);
                        copy($config['new_image'] , $config['source_image']);
                        if (!$this->image_lib->resize()) {
                            echo $this->image_lib->display_errors();
                        }

                        if ($this->Settings->watermark) {
                            $this->image_lib->clear();
                            $wm['source_image'] = $this->upload_path . $pho;
                            $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                            $wm['wm_type'] = 'text';
                            $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                            $wm['quality'] = '100';
                            $wm['wm_font_size'] = '16';
                            $wm['wm_font_color'] = '999999';
                            $wm['wm_shadow_color'] = 'CCCCCC';
                            $wm['wm_vrt_alignment'] = 'top';
                            $wm['wm_hor_alignment'] = 'right';
                            $wm['wm_padding'] = '10';
                            $this->image_lib->initialize($wm);
                            $this->image_lib->watermark();
                        }
                        $this->image_lib->clear();
                    }
                }
                $config = NULL;
            } else {
                $photos = NULL;
            }
            $data['quantity'] = isset($wh_total_quantity) ? $wh_total_quantity : 0;
        }

        if ($this->form_validation->run() == true && $pid = $this->products_model->addProduct($data, $items, $product_attributes, $photos, isset($related_products) ? $related_products : NULL, $items2)) {
            getUserIdPermission();
            $data['product_id']=$pid;
            $data['action']='Add';
            $data['created_at']=date('Y-m-d H:i:s');
            $data['updated_by']=$this->session->userdata('user_id');
            $this->products_model->addProductHistory($data, $items, $product_attributes, $photos, $related_products, $items2);
            $this->session->set_flashdata('message', lang("product_added"));
            if (strpos($_SERVER['HTTP_REFERER'], 'products/add') !== false) {
                redirect('products');
            }else if($this->input->get('salep')) {
                redirect('sales/add?addsales='.$pid);
            }else if($this->input->get('salee')) {
                $sale_id = $this->input->get('salee');
                redirect('sales/edit/'.$sale_id.'?editsales='.$pid);
            }
            else if($this->input->get('saleo')) {
                redirect('sale_order/add_sale_order?addsaleorder='.$pid);
            }
            else if($this->input->get('saleoe')) {
                $editorder = $this->input->get('saleoe');
                redirect('sale_order/edit_sale_order/'.$editorder.'?editsaleorder='.$pid);
            }
            else if($this->input->get('quote')) {
                redirect('quotes/add?addquote='.$pid);
            }
            else if($this->input->get('equote')) {
                $equote = $this->input->get('equote');
                redirect('quotes/edit/'.$equote.'?editquote='.$pid);
            }else if($this->input->get('addprquest')) {
                redirect('purchases_request/add?addpurrquest='.$pid);
            }
            else if($this->input->get('editprquest')) {
                $pureid = $this->input->get('editprquest');
                redirect('purchases_request/edit/'.$pureid.'?editpurrquest='.$pid);
            }else if($this->input->get('addprquestorder')) {
                redirect('purchases/add_purchase_order?addpurrquestorder='.$pid);
            }else if($this->input->get('editprquestorder')) {
                $pureorerid = $this->input->get('editprquestorder');
                redirect('purchases/edit_purchase_order/'.$pureorerid.'?editpurrquestorder='.$pid);
            }else if($this->input->get('addpr')) {
                redirect('purchases/add?addpur='.$pid);
            }else if($this->input->get('editpr')) {
                $epur = $this->input->get('editpr');
                redirect('purchases/edit/'.$epur.'?editpur='.$pid);
            }
            else{
                redirect('products');
            }
            
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
            $this->data['currencies'] = $this->products_model->getAllCurrencies();
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['brands'] = $this->site->getAllBrands();
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['warehouses'] = $warehouses;
            
            $this->data['warehouses_products'] = $id ? $this->products_model->getAllWarehousesWithPQ($id) : NULL;
            $this->data['product'] = $id ? $this->products_model->getProductByID($id) : NULL;
            $this->data['products'] = $this->site->getAllProducts();
            $this->data['suppliers'] = $this->products_model->getSuppliers();
            $this->data['variants'] = $this->products_model->getAllVariants();          
            $this->data['home_type'] = $this->products_model->gethomeType();            
            
            /** Project **/
            
            $this->data['shops'] = $this->products_model->getProjects();
            $this->data['unit'] = $this->products_model->getUnits();
            $this->data['combo_items'] = ($id && $this->data['product']->type == 'combo') ? $this->products_model->getProductComboItems($id) : NULL;
             $this->data['digital_items'] = ($id && $this->data['product']->type == 'digital') ? $this->products_model->getProductDigitalItems($id) : NULL;
            $this->data['product_options'] = $id ? $this->products_model->getProductOptions($id) : NULL;
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('add_product')));
            $meta = array('page_title' => lang(''), 'bc' => $bc);
            $this->page_construct('products/add', $meta, $this->data);
        }
    }

    function suggestions()
    {
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', true);
        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $rows = $this->products_model->getProductNames($term, $warehouse_id);
        if ($rows) {
            $uom = "";
            foreach ($rows as $row) {
                $options = $this->products_model->getProductOptions($row->id);

                $pr[] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")(". $row->unit .")", 'uom' => $options, 'code' => $row->code, 'name' => $row->name, 'price' => $row->price, 'qty' => 1, 'unit' => $row->unit, 'cost' => $row->cost, 'qoh' => $row->qoh);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }
    
    public function suggestionsStock()
    {
        $term           = $this->input->get('term', TRUE);
        $warehouse_id   = $this->input->get('warehouse_id', TRUE);
        $plan           = $this->input->get('plan',TRUE);
        $address        = $this->input->get('address',TRUE);
        $rows           = $this->products_model->getUsingStockProducts($term, $warehouse_id, $plan, $address);
        if($rows){
            $c = str_replace(".", "", microtime(true));
            $r = 0;
            $project_qty = 0;
            foreach ($rows as $row) {
                if($plan){
                    $project_qty      = $row->project_qty;
                } else {
                    $row->project_qty = 0;
                }
                $row->have_plan = 0;
                if($row->project_qty && $row->in_plan){
                    $row->have_plan = 1;
                }
                $row->qty_use   = 0;
                $row->qty_old   = 0;
                $option_unit    = $this->products_model->getUnitAndVaraintByProductId($row->id);
                $expiry_date    = $this->site->getProductExpireDate($row->id, $warehouse_id);
                $pr[] = array('id' => ($c + $r), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")",'row' => $row, 'option_unit' => $option_unit, 'project_qty' => $project_qty, 'expiry_date' => $expiry_date);
                $r++;
            }
            echo json_encode($pr);
        }else{
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }
    
    public function suggests()
    {
        $term = $this->input->get('term', true);
        $warehouse_id = $this->input->get('warehouse_id', true);

        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }
        
        $spos = strpos($term, '%');
        if ($spos !== false) {
            $st = explode("%", $term);
            $sr = trim($st[0]);
            $option = trim($st[1]);
        } else {
            $sr = $term;
            $option = '';
        }

        $rows = $this->products_model->getProductNumber($term, $warehouse_id);

        if ($rows) {
            $uom = "";
            foreach ($rows as $row) {
                $options = $this->products_model->getProductOptions($row->id);
                
                $pr[] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'uom' => $options, 'code' => $row->code, 'name' => $row->name, 'price' => $row->price, 'cost' => $row->cost, 'qty' => 1, 'qoh' => $row->qoh);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }
    
    function check_product_available($term = NULL)
    {
        $term = $this->input->get('term', TRUE);
        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $row = $this->products_model->getProductCode($term);
        if ($row) {
            echo 1;
        } else {
            echo 0;
        }
    }

    function get_suggestions()
    {
        $term = $this->input->get('term', TRUE);
        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $rows = $this->products_model->getProductsForPrinting($term);
        if ($rows) {
            foreach ($rows as $row) {
                $variants = $this->products_model->getProductOptions($row->id);
                $pr[] = array('id' => str_replace(".", "", microtime(true)), 'pro_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'code' => $row->code, 'name' => $row->name, 'price' => $row->price, 'qty' => 1, 'variants' => $variants);
            }           
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }

    function addByAjax()
    {
        if (!$this->mPermissions('add')) {
            exit(json_encode(array('msg' => lang('access_denied'))));
        }
        if ($this->input->get('token') && $this->input->get('token') == $this->session->userdata('user_csrf') && $this->input->is_ajax_request()) {
            $product = $this->input->get('product');
            if(!isset($product['type']) || empty($prodcut['type'])){
                exit(json_encode(array('msg' => lang('product_type_is_required'))));
            }
            if (!isset($product['code']) || empty($product['code'])) {
                exit(json_encode(array('msg' => lang('product_code_is_required'))));
            }
            if (!isset($product['name']) || empty($product['name'])) {
                exit(json_encode(array('msg' => lang('product_name_is_required'))));
            }
            if (!isset($product['barcode_symbology']) || empty($product['barcode_symbology'])) {
                exit(json_encode(array('msg' => lang('barcode_symbology_is_required'))));
            }
            if (!isset($product['category_id']) || empty($product['category_id'])) {
                exit(json_encode(array('msg' => lang('product_category_is_required'))));
            }
            if (!isset($product['unit']) || empty($product['unit'])) {
                exit(json_encode(array('msg' => lang('product_unit_is_required'))));
            }
            if (!isset($product['price']) || empty($product['price'])) {
                exit(json_encode(array('msg' => lang('product_price_is_required'))));
            }
            if (!isset($product['cost']) || empty($product['cost'])) {
                exit(json_encode(array('msg' => lang('product_cost_is_required'))));
            }
            if ($this->products_model->getProductByCode($product['code'])) {
                exit(json_encode(array('msg' => lang('product_code_already_exist'))));
            }
            if ($row = $this->products_model->addAjaxProduct($product)) {
                $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
                $pr = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'code' => $row->code, 'qty' => 1, 'cost' => $row->cost, 'name' => $row->name, 'tax_method' => $row->tax_method, 'tax_rate' => $tax_rate, 'discount' => '0');
                echo json_encode(array('msg' => 'success', 'result' => $pr));
            } else {
                exit(json_encode(array('msg' => lang('failed_to_add_product'))));
            }
        } else {
            json_encode(array('msg' => 'Invalid token'));
        }

    }
    function deleteProductVariant($product_id=null,$variants_id=null){
        $this->erp->checkPermissions('edit',null,'products');
        $row=$this->db->get_where('erp_product_variants',array('id'=>$variants_id));
        if($variants_id!=null){
            $exist=$this->db->get_where('erp_sale_items',array('product_id'=>$product_id));
            if($exist->num_rows() > 0){
                echo false;
            }else{
                if($row->num_rows() >0){
                    $this->db->delete('product_variants',array('id'=>$variants_id));
                    echo true;
                }
                
            }
        }
       
    }
    function edit($id = NULL)
    {
        $this->erp->checkPermissions('edit',null,'products');
        $this->load->helper('security');
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        $warehouses = $this->site->getAllWarehouses();
        $warehouses_products = $this->products_model->getAllWarehousesWithPQ($id);
        $product = $this->site->getProductAllByID($id);

        if (!$id || !$product) {
            $this->session->set_flashdata('error', lang('prduct_not_found'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        if ($this->input->post('type') == 'standard') {
           
        }
        if ($this->input->post('code') !== $product->code) {
            $this->form_validation->set_rules('code', lang("product_code"), 'is_unique[products.code]');
        }
        if ($this->input->post('barcode_symbology') == 'ean13') {
            $this->form_validation->set_rules('code', lang("product_code"), 'min_length[13]|max_length[13]');
        }
        $this->form_validation->set_rules('product_image', lang("product_image"), 'xss_clean');
        $this->form_validation->set_rules('digital_file', lang("digital_file"), 'xss_clean');
        $this->form_validation->set_rules('userfile', lang("product_gallery_images"), 'xss_clean');

        if ($this->form_validation->run('products/add') == true) {
            if($this->input->post('inactive')) {
                $inactived = $this->input->post('inactive');
            } else {
                $inactived = 0;
            }
            $product_type = $this->input->post('type');
            $cost_combo_item = $this->input->post('cost_combo_item');
            $prodcut_cost = $this->erp->formatDecimal($this->input->post('cost'));
            $data = array(
                'code'              => $this->input->post('code'),
                'barcode_symbology' => $this->input->post('barcode_symbology'),
                'name'              => $this->input->post('name'),
                'name_kh'           => $this->input->post('name_kh'),
                'type'              => $product_type,
                'category_id'       => $this->input->post('category'),
                'subcategory_id'    => $this->input->post('subcategory') ? $this->input->post('subcategory') : NULL,
                'cost'              => $product_type == 'combo' ? $cost_combo_item : $prodcut_cost,
                'price'             => $this->erp->formatDecimal($this->input->post('price')),
                'unit'              => $this->input->post('unit'),
                'tax_rate'          => $this->input->post('tax_rate'),
                'tax_method'        => $this->input->post('tax_method'),
                'alert_quantity'    => $this->input->post('alert_quantity'),
                'track_quantity'    => $this->input->post('track_quantity') ? $this->input->post('track_quantity') : '0',
                'details'           => $this->input->post('details'),
                'product_details'   => $this->input->post('product_details'),
                'supplier1'         => $this->input->post('supplier'),
                'supplier1price'    => $this->erp->formatDecimal($this->input->post('supplier_price')),
                'supplier2'         => $this->input->post('supplier_2'),
                'supplier2price'    => $this->erp->formatDecimal($this->input->post('supplier_2_price')),
                'supplier3'         => $this->input->post('supplier_3'),
                'supplier3price'    => $this->erp->formatDecimal($this->input->post('supplier_3_price')),
                'supplier4'         => $this->input->post('supplier_4'),
                'supplier4price'    => $this->erp->formatDecimal($this->input->post('supplier_4_price')),
                'supplier5'         => $this->input->post('supplier_product'),
                'supplier5price'    => $this->input->post('supplier_product_price'),
                'service_type'      => $this->input->post('include_cost'),
                'cf1'               => $this->input->post('cf1'),
                'cf2'               => $this->input->post('cf2'),
                'cf3'               => $this->input->post('cf3'),
                'cf4'               => $this->input->post('cf4'),
                'cf5'               => $this->input->post('cf5'),
                'cf6'               => $this->input->post('cf6'),
                'contruction_status'=> $this->input->post('contruction_status'),
                'promotion'         => $this->input->post('promotion'),
                'promo_price'       => trim($this->input->post('promo_price')),
                'start_date'        => $this->erp->fsd($this->input->post('start_date')),
                'end_date'          => $this->erp->fsd($this->input->post('end_date')),
                'supplier1_part_no' => $this->input->post('supplier_part_no'),
                'supplier2_part_no' => $this->input->post('supplier_2_part_no'),
                'supplier3_part_no' => $this->input->post('supplier_3_part_no'),
                'supplier4_part_no' => $this->input->post('supplier_4_part_no'),
                'supplier5_part_no' => $this->input->post('supplier_5_part_no'), 
                'currentcy_code'    => $this->input->post('currency'),
                'inactived'         => $inactived,
                'brand_id'          => $this->input->post('brand')
            );
            
            $related_straps = $this->input->post('related_strap');
            if($this->site->deleteStrapByProductCode($this->input->post('code'))) {
                for($i=0; $i<sizeof($related_straps); $i++) {
                    $product_name = $this->site->getProductByCode($related_straps[$i]);
                    $related_products[] = array(
                                                'product_code' => $this->input->post('code'),
                                                'related_product_code' => $related_straps[$i],
                                                'product_name' => $product_name->name
                                                );
                }
            }
            $this->load->library('upload');
            if ($this->input->post('type') == 'standard') {
                if ($product_variants = $this->products_model->getProductOptions($id)) {
                    foreach ($product_variants as $pv) {
                        $update_variants[] = array(
                            'id' => $this->input->post('variant_id_'.$pv->id),
                            'name' => $this->input->post('variant_name_'.$pv->id),
                            'qty_unit' => $this->input->post('variant_qty_unit_'.$pv->id),
                            //'cost' => $this->input->post('variant_cost_'.$pv->id),
                            'price' => $this->input->post('variant_price_'.$pv->id),
                        );
                    }
                } else {
                    $update_variants = NULL;
                }
                for ($s = 2; $s > 5; $s++) {
                    $data['suppliers' . $s] = $this->input->post('supplier_' . $s);
                    $data['suppliers' . $s . 'price'] = $this->input->post('supplier_' . $s . '_price');
                }
                if ($this->input->post('attributes')) {
                    $a = sizeof($_POST['attr_name']);
                    for ($r = 0; $r <= $a; $r++) {
                        if (isset($_POST['attr_name'][$r])) {
                            if ($product_variatnt = $this->products_model->getPrductVariantByPIDandName($id, trim($_POST['attr_name'][$r]))) {
                                $this->form_validation->set_message('required', lang("product_already_has_variant").' ('.$_POST['attr_name'][$r].')');
                                $this->form_validation->set_rules('new_product_variant', lang("new_product_variant"), 'required');
                            } else {
                                $product_attributes[] = array(
                                    'name' => $_POST['attr_name'][$r],
                                    'qty_unit' => $_POST['attr_quantity_unit'][$r],
                                    'price' => $_POST['attr_price'][$r],
                                );
                            }
                        }
                    }
                } else {
                    $product_attributes = NULL;
                }

            } else {
                $warehouse_qty = NULL;
                $product_attributes = NULL;
            }

            if ($this->input->post('type') == 'service') {
                $data['track_quantity']  = 0;
                $wh_total_quantity = 1;
                $data['service_type']    = $this->input->post('service_type');
            } elseif ($this->input->post('type') == 'combo') {
                $total_price = 0;
                $c = sizeof($_POST['combo_item_code']) - 1;
                for ($r = 0; $r <= $c; $r++) {
                    if (isset($_POST['combo_item_code'][$r]) && isset($_POST['combo_item_quantity_unit'][$r]) && isset($_POST['combo_item_price'][$r])) {
                        $items[] = array(
                            'item_code' => $_POST['combo_item_code'][$r],
                            'quantity' => $_POST['combo_item_quantity_unit'][$r],
                            //'quantity' => $_POST['combo_item_quantity'][$r],
                            'unit_price' => $_POST['combo_item_price'][$r],
                        );
                    }
                    $total_price += $_POST['combo_item_price'][$r] * $_POST['combo_item_quantity_unit'][$r];
                }
                
                $data['track_quantity'] = 0;
            } elseif ($this->input->post('type') == 'digital') {
              
                 $c = sizeof($_POST['combo_item_code']) - 1;
                for ($r = 0; $r <= $c; $r++) {
                    if (isset($_POST['combo_item_code'][$r])) {
                        $items2[] = array(
                            'product_id' => $_POST['combo_item_id'][$r]
                        );
                    }
                }
                $data['track_quantity'] = 0;
            }
            if (!isset($items)) {
                $items = NULL;
            }
             if (!isset($items2)) {
                $items2 = NULL;
            }
            if ($_FILES['product_image']['size'] > 0) {

                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                //$config['max_width'] = $this->Settings->iwidth;
                //$config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('product_image')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/edit/" . $id);
                }
                $photo = $this->upload->file_name;
                $data['image'] = $photo;
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $this->upload_path . $photo;
                $config['new_image'] = $this->thumbs_path . $photo;
                $config['maintain_ratio'] = TRUE;
                //$config['width'] = $this->Settings->twidth;
                //$config['height'] = $this->Settings->theight;
                $config['width'] = $this->Settings->iwidth;
                $config['height'] = $this->Settings->iheight;
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                copy($config['new_image'] , $config['source_image']);
                if (!$this->image_lib->resize()) {
                    echo $this->image_lib->display_errors();
                }
                if ($this->Settings->watermark) {
                    $this->image_lib->clear();
                    $wm['source_image'] = $this->upload_path . $photo;
                    $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                    $wm['wm_type'] = 'text';
                    $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                    $wm['quality'] = '100';
                    $wm['wm_font_size'] = '16';
                    $wm['wm_font_color'] = '999999';
                    $wm['wm_shadow_color'] = 'CCCCCC';
                    $wm['wm_vrt_alignment'] = 'top';
                    $wm['wm_hor_alignment'] = 'right';
                    $wm['wm_padding'] = '10';
                    $this->image_lib->initialize($wm);
                    $this->image_lib->watermark();
                }
                $this->image_lib->clear();
                $config = NULL;
            }

            if ($_FILES['userfile']['name'][0] != "") {

                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                //$config['max_width'] = $this->Settings->iwidth;
                //$config['max_height'] = $this->Settings->iheight;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                $config['max_filename'] = 25;
                $files = $_FILES;
                $cpt = count($_FILES['userfile']['name']);
                for ($i = 0; $i < $cpt; $i++) {

                    $_FILES['userfile']['name'] = $files['userfile']['name'][$i];
                    $_FILES['userfile']['type'] = $files['userfile']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                    $_FILES['userfile']['error'] = $files['userfile']['error'][$i];
                    $_FILES['userfile']['size'] = $files['userfile']['size'][$i];

                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                        redirect("products/edit/" . $id);
                    } else {

                        $pho = $this->upload->file_name;

                        $photos[] = $pho;

                        $this->load->library('image_lib');
                        $config['image_library'] = 'gd2';
                        $config['source_image'] = $this->upload_path . $pho;
                        $config['new_image'] = $this->thumbs_path . $pho;
                        $config['maintain_ratio'] = TRUE;
                        //$config['width'] = $this->Settings->twidth;
                        //$config['height'] = $this->Settings->theight;
                        $config['width'] = $this->Settings->iwidth;
                        $config['height'] = $this->Settings->iheight;

                        $this->image_lib->initialize($config);
                        copy($config['new_image'] , $config['source_image']);
                        if (!$this->image_lib->resize()) {
                            echo $this->image_lib->display_errors();
                        }

                        if ($this->Settings->watermark) {
                            $this->image_lib->clear();
                            $wm['source_image'] = $this->upload_path . $pho;
                            $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                            $wm['wm_type'] = 'text';
                            $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                            $wm['quality'] = '100';
                            $wm['wm_font_size'] = '16';
                            $wm['wm_font_color'] = '999999';
                            $wm['wm_shadow_color'] = 'CCCCCC';
                            $wm['wm_vrt_alignment'] = 'top';
                            $wm['wm_hor_alignment'] = 'right';
                            $wm['wm_padding'] = '10';
                            $this->image_lib->initialize($wm);
                            $this->image_lib->watermark();
                        }

                        $this->image_lib->clear();
                    }
                }
                $config = NULL;
            } else {
                $photos = NULL;
            }
            $data['quantity'] = isset($wh_total_quantity) ? $wh_total_quantity : 0;
        }

        if ($this->form_validation->run() == true && $this->products_model->updateProduct($id, $data, $items, $product_attributes, $photos, $update_variants, $related_products,$items2)) {
            getUserIdPermission();
            $data['created_at']=date('Y-m-d H:i:s');
            $data['updated_by']=$this->session->userdata('user_id');
            $data['product_id']=$id;
            $data['action']='Edit';
            $this->products_model->addProductHistory($data, $items, $product_attributes, $photos, $related_products, $items2);
            $this->session->set_flashdata('message', lang("product_updated"));
            redirect('products');
        } else {
            $this->data['error']                = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['currencies']           = $this->products_model->getAllCurrencies();
            $this->data['categories']           = $this->site->getAllCategories();
            $this->data['brands']               = $this->site->getAllBrands();
            $this->data['tax_rates']            = $this->site->getAllTaxRates();
            $this->data['warehouses']           = $warehouses;
            $this->data['warehouses_products']  = $warehouses_products;
            $this->data['product']              = $product;
            $this->data['products']             = $this->site->getAllProducts();
            $this->data['straps']               = $this->products_model->getStrapByProductID($product->code);
            $this->data['suppliers']            = $this->products_model->getSuppliers();
            $this->data['variants']             = $this->products_model->getAllVariants();
            $this->data['product_item']         = $this->site->getAllProductsInPurchaseItems($id);
            $this->data['unit']                 = $this->products_model->getUnits();
            $this->data['home_type']            = $this->products_model->gethomeType();
            $this->data['product_variants'] = $this->products_model->getProductOptionsData($id);
            $this->data['combo_items']          = $product->type == 'combo' ? $this->products_model->getProductComboItems($product->id) : NULL;
            $this->data['digital_items']        = $product->type == 'digital' ? $this->products_model->getProductDigitalItems($product->id) : NULL;
            $this->data['product_options']      = $id ? $this->products_model->getProductOptionsWithWH($id) : NULL;
            $this->data['beforeDelPro']         = $this->products_model->getProductfordelete($id);
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('edit_product')));
            $meta = array('page_title' => lang('edit_product'), 'bc' => $bc);
            $this->page_construct('products/edit', $meta, $this->data);
        }
    }

    function import_csv()
    {
        $this->erp->checkPermissions('import', NULL, 'products');
        
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/import_csv");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen($this->digital_upload_path . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('code', 'name', 'name_kh', 'category_code', 'unit_id', 'cost', 'price', 'alert_quantity', 'tax_rate', 'tax_method', 'subcategory_code', 'variants','supplier1','supplier2','supplier3', 'supplier4', 'supplier5', 'cf1', 'cf2', 'cf3', 'cf4', 'cf5', 'cf6', 'image', 'type');

                $final = array();

                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv_pr) { 
                    if ($this->products_model->getProductByCode(trim($csv_pr['code']))) {
                        $this->session->set_flashdata('error', lang("check_product_code") . " (" . $csv_pr['code'] . "). " . lang("code_already_exist") . " " . lang("line_no") . " " . $rw);
                        redirect("products/import_csv");
                    }
                    if ($catd = $this->products_model->getCategoryByCode(trim($csv_pr['category_code']))) {
                        $pr_code[] = trim($csv_pr['code']);
                        $pr_name[] = trim($csv_pr['name']);
                        $pr_name_kh[] = trim($csv_pr['name_kh']);
                        $pr_cat[] = $catd->id;
                        $pr_variants[] = trim($csv_pr['variants']); 
                        $pr_unit[] = trim($csv_pr['unit_id']);
                        $currency_code[] = $this->site->get_setting()->default_currency;
                        $tax_method[] = $csv_pr['tax_method'] == 'exclusive' ? 0: 1;
                        $prsubcat = $this->products_model->getSubcategoryByCode(trim($csv_pr['subcategory_code']));
                        $pr_subcat[] = $prsubcat ? $prsubcat->id : NULL;
                        $pr_cost[] = trim($csv_pr['cost']);
                        $pr_price[] = trim($csv_pr['price']);
                        $pr_aq[] = trim($csv_pr['alert_quantity']);
                        $tax_details = $this->products_model->getTaxRateByName(trim($csv_pr['tax_rate']));
                        $pr_tax[] = $tax_details ? $tax_details->id : NULL;
                        
                        $supplier1[] = trim($csv_pr['supplier1']);
                        $supplier2[] = trim($csv_pr['supplier2']);
                        $supplier3[] = trim($csv_pr['supplier3']);
                        $supplier4[] = trim($csv_pr['supplier4']);
                        $supplier5[] = trim($csv_pr['supplier5']);
                        
                        $cf1[] = trim($csv_pr['cf1']);
                        $cf2[] = trim($csv_pr['cf2']);
                        $cf3[] = trim($csv_pr['cf3']);
                        $cf4[] = trim($csv_pr['cf4']);
                        $cf5[] = trim($csv_pr['cf5']);
                        $cf6[] = trim($csv_pr['cf6']);
                        $image[] = $this->erp->convertImageSpecialChar(trim($csv_pr['image']));
                        $type[] = trim($csv_pr['type']);
                        
                    } else {
                        $this->session->set_flashdata('error', lang("check_category_code") . " (" . $csv_pr['category_code'] . "). " . lang("category_code_x_exist") . " " . lang("line_no") . " " . $rw);
                        redirect("products/import_csv");
                    }
                    $rw++;
                }
            }

            $ikeys = array('code', 'name', 'name_kh', 'category_id', 'unit', 'cost', 'price', 'alert_quantity', 'tax_rate', 'tax_method', 'subcategory_id', 'variants', 'supplier1', 'supplier2', 'supplier3', 'supplier4', 'supplier5', 'cf1', 'cf2', 'cf3', 'cf4', 'cf5', 'cf6', 'currentcy_code', 'image', 'type');

            $items = array();
            foreach (array_map(null, $pr_code, $pr_name, $pr_name_kh, $pr_cat, $pr_unit, $pr_cost, $pr_price, $pr_aq, $pr_tax, $tax_method, $pr_subcat, $pr_variants, $supplier1, $supplier2, $supplier3, $supplier4, $supplier5, $cf1, $cf2, $cf3, $cf4, $cf5, $cf6, $currency_code, $image, $type) as $ikey => $value) {
                $items[] = array_combine($ikeys, $value); 
            }
        }

        if ($this->form_validation->run() == true && $this->products_model->add_products($items)) {
			getUserIdPermission();			
            $this->session->set_flashdata('message', lang("products_added"));
            redirect('products');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('import_products_by_csv')));
            $meta = array('page_title' => lang('import_products_by_csv'), 'bc' => $bc);
            $this->page_construct('products/import_csv', $meta, $this->data);

        }
    }

    function update_price()
    {
        $this->erp->checkPermissions('import_price_cost', NULL, 'products');
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('message', lang("disabled_in_demo"));
                redirect('welcome');
            }

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/update_price");
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen($this->digital_upload_path . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                
                $titles = array_shift($arrResult);

                $keys = array('code', 'price','cost', 'variant');

                $final = array();
                
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                
                $rw = 2;
                $i  = 0;
                
                foreach ($final as $csv_pr) {
                    $item = $this->products_model->getProductByCode(trim($csv_pr['code']));                 
                    if (!$item) {
                        $this->session->set_flashdata('error', lang("check_product_code") . " (" . $csv_pr['code'] . "). " . lang("code_x_exist") . " " . lang("line_no") . " " . $rw);
                        redirect("products/update_price");
                    }
                    if($csv_pr['cost'] != "" && $csv_pr['price'] != ""){
                        if($csv_pr['cost'] > $csv_pr['price']){
                            $this->session->set_flashdata('error', lang("check_product_code") . " (" . $csv_pr['code'] . "). " . lang("cost_x_price") . " " . lang("line_no") . " " . $rw);
                            redirect("products/update_price");
                        }                       
                    }else if($csv_pr['cost'] != ""){
                        if($csv_pr['cost'] > $item->price){
                            $this->session->set_flashdata('error', lang("check_product_code") . " (" . $csv_pr['code'] . "). " . lang("cost_x_price") . " " . lang("line_no") . " " . $rw);
                            redirect("products/update_price");
                        }
                    }else if($csv_pr['price'] != ""){
                        if($csv_pr['price'] < $item->cost){
                            $this->session->set_flashdata('error', lang("check_product_code") . " (" . $csv_pr['code'] . "). " . lang("price_less_cost") . " " . lang("line_no") . " " . $rw);
                            redirect("products/update_price");
                        }
                    }
                    $final[$i]['id'] = $item->id;
                    $i++;
                    $rw++;
                }
            }
            
        }

        if ($this->form_validation->run() == true && !empty($final)) {
            $this->products_model->updatePrice($final);
            $this->session->set_flashdata('message', lang("price_updated"));
            redirect('products');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('update_price_csv')));
            $meta = array('page_title' => lang('update_price_csv'), 'bc' => $bc);
            $this->page_construct('products/update_price', $meta, $this->data);

        }
    }
    
    function update_quantity()
    {
        $this->erp->checkPermissions('import_quantity', NULL, 'products');
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            isCloseDate(date('Y-m-d'));
            if (DEMO) {
                $this->session->set_flashdata('message', lang("disabled_in_demo"));
                redirect('welcome');
            }

            if (isset($_FILES["userfile"])) {

                $this->load->library('upload');

                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);
                
                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("products/update_quantity");
                }

                $csv = $this->upload->file_name;
                
                $arrResult = array();
                $handle = fopen($this->digital_upload_path . $csv, "r");
                
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);
                
                $keys           = array('code', 'quantity', 'opening_stock', 'cost');
                $keys_warehouse = array('quantity', 'warehouse_id');
                $keys_var_value = array('code', 'option_id', 'cost');
                $keys_var       = array('quantity', 'option_id','warehouse_id');
                $keys_purchase  = array('product_code', 'quantity_balance','option_id','warehouse_id','opening_stock', 'expiry', 'serial');
                
                $final                  = array();
                $final_ware_product     = array();
                $final_var              = array();
                $final_purchase_item    = array();
                foreach ($arrResult as $key => $value) {

                    $query_product = $this->products_model->getProductByCode(trim($value[0]));
                    $qty_unit       = 0;
                    $qty_variant = $this->products_model->getProductVariant($query_product->id, $value[2]);
                    $quantity       = isset($qty_variant->qty_unit) ? $value[1] * $qty_variant->qty_unit : $value[1];
                    $cost           = isset($qty_variant->qty_unit) ? $value[5] / $qty_variant->qty_unit : $value[5];
                    $value[1]       = $quantity;
                    $value[5]       = $cost;
                    $temp_product   = $value;
                    $temp_warehouse = $value;
                    $temp_var       = $value;
                    $var_value      = $value;
                    unset($var_value[1]);
                    unset($var_value[3]);
                    unset($var_value[4]);
                    unset($var_value[6]);
                    unset($var_value[7]);
                    
                    $final_var_value[] = array_combine($keys_var_value, $var_value);
                    
                    unset($temp_product[2]);
                    unset($temp_product[3]);
                    unset($temp_product[6]);
                    unset($temp_product[7]);
                    

                    unset($temp_warehouse[0]);
                    unset($temp_warehouse[2]);
                    unset($temp_warehouse[4]);
                    unset($temp_warehouse[5]);
                    unset($temp_warehouse[6]);
                    unset($temp_warehouse[7]);
                    
                    unset($temp_var[0]);
                    unset($temp_var[4]);
                    unset($temp_var[5]);
                    unset($temp_var[6]);
                    unset($temp_var[7]);
                    unset($value[5]);

                    $final[]                = array_combine($keys, $temp_product);
                    $final_ware_product[]   = array_combine($keys_warehouse, $temp_warehouse);
                    $final_var[]            = array_combine($keys_var, $temp_var);
                    $final_purchase_item[]  = array_combine($keys_purchase, $value);
                    $implode[]              = implode(',', $value);
                }
                
                $rw = 2;
                $i =0;

                foreach ($final as $csv_pr)
                {
                    if (trim($csv_pr['code'])) {
                        $query_product = $this->products_model->getProductByCode(trim($csv_pr['code']));
                        if ($query_product->type == 'service') {
                            $this->session->set_flashdata('error', lang("check_product") . " (" . $csv_pr['code'] . "). " . lang("product_service_cannot_import") . " " . lang("line_no") . " " . $rw);
                            redirect("products/update_quantity");
                        }

                        $final_purchase_item[$i]['transaction_type'] = "OPENING QUANTITY";
                        $final_purchase_item[$i]['product_id'] = $query_product->id;
                        $final_purchase_item[$i]['product_type'] = $query_product->type;
                        $final_ware_product[$i]['product_id'] = $query_product->id;
                        $final_var[$i]['product_id'] = $query_product->id;
                        if($final_var[$i]['option_id']) {
                            $option = $this->products_model->getVariantNameById($final_var[$i]['option_id']);
                            $query_product_var = $this->products_model->getOptionId($query_product->id, $option->name);
                            $final_var[$i]['option_id'] = $query_product_var->id;
                            $final_var_value[$i]['option_id'] = $query_product_var->id;
                            $final_purchase_item[$i]['option_id'] = $query_product_var->id;
                        }
                        if ($csv_pr['cost'] > 0) {
                            $final_purchase_item[$i]['cost'] = $csv_pr['cost'];
                        } else {
                            $final_purchase_item[$i]['cost'] = $query_product->cost;
                        }
                        if (!$query_product) {
                            $this->session->set_flashdata('message', lang("check_product_code") . " (" . $csv_pr['code'] . "). " . lang("code_x_exist") . " " . lang("line_no") . " " . $rw);
                            redirect("products/update_quantity");
                        }

                        $rw++;
                        $i++;
                    }
                }

                $total_cost = 0;
                
                foreach ($final as $csvpr) {
                    $cost = $csvpr['quantity'] * $csvpr['cost'];
                    $total_cost += $cost;
                }
            }

            //$this->erp->print_arrays($final_purchase_item);

        }

        if ($this->form_validation->run() == true && !empty($final)) {
            $this->products_model->updateQuantityExcel($final);
            $this->products_model->updateCostVariant($final_var_value);
            $this->products_model->updateQuantityExcelWarehouse($final_ware_product);
            $this->products_model->updateQuantityExcelVar($final_var);
            $this->products_model->insertGlTran($total_cost);
            $check  = $this->products_model->updateQuantityExcelPurchase($final_purchase_item);
            optimizeOpeningQuantity(date('Y-m-d'));
            getUserIdPermission();
            if($check)
            {
               $this->session->set_flashdata('message', lang("quantity_updated"));
                redirect('products'); 
            }
            
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('update_price_csv')));
            $meta = array('page_title' => lang('update_quantity_csv'), 'bc' => $bc);
            $this->page_construct('products/update_quantity', $meta, $this->data);

        }
    }

    function delete($id = NULL)
    {
        $this->erp->checkPermissions('delete',null,'products');
        if($this->products_model->getProductfordelete($id)){
            $this->session->set_flashdata('error', lang('product_with_transaction_can_not_delete'));
            redirect($_SERVER["HTTP_REFERER"]);
        }else{
            if ($this->products_model->deleteProduct($id)) {
                getUserIdPermission();
                if($this->input->is_ajax_request()) {
                    echo lang("product_deleted"); die();
                }
                $this->session->set_flashdata('message', lang('product_deleted'));
                redirect('products');
            }
        }

    }

    function quantity_adjustments($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('adjustments');
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $this->data['warehouses'] = $this->site->getAllWarehouses();
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
            
        } else {
            
            $this->data['warehouses'] = $this->products_model->getUserWarehouses();

            if($warehouse_id){
                $this->data['warehouse_id'] = $warehouse_id;
                $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
            }else{
                $this->data['warehouse_id'] = str_replace(',', '-',$this->session->userdata('warehouse_id'));
                $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->products_model->getUserWarehouses() : NULL;
            }
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('product_adjustment_list')));
        $meta = array('page_title' => lang('product_adjustment'), 'bc' => $bc);
        $this->page_construct('products/quantity_adjustments', $meta, $this->data);
    }
    
    function adjustment_view_list($id)
    {
        $this->erp->checkPermissions('adjustments');
        $this->data['header'] = $this->products_model->getAdjustment($id); 
        $this->data['items'] = $this->products_model->getAdjustmentList($id); 
        $this->data['page_title'] = lang("adjustments");
        $this->load->view($this->theme . 'products/adjustment_view_list', $this->data);
    }
    
    function getadjustments($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('adjustments');
        $warehouse_ids = explode('-', $warehouse_id);

        $delete_link = "<a href='#' class='tip po' title='<b>" . $this->lang->line("delete_adjustment") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('products/delete_adjustment/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a>";

        $this->load->library('datatables');

        if ($warehouse_id) {
            $this->datatables
                ->select("adjustments.id as id, adjustments.date, reference_no, warehouses.name as wh_name, SUM(COALESCE(abs(erp_adjustment_items.quantity),0)) as quantity, 
                CONCAT({$this->db->dbprefix('users')}.first_name, ' ', 
                {$this->db->dbprefix('users')}.last_name) as created_by, 
                {$this->db->dbprefix('adjustments')}.note, 
                {$this->db->dbprefix('adjustments')}.attachment")
                ->from('adjustments')
                ->join('warehouses', 'warehouses.id=adjustments.warehouse_id', 'left')
                ->join('users', 'users.id=adjustments.created_by', 'left')
                ->join('adjustment_items', 'adjustment_items.adjust_id = adjustments.id', 'left')
                ->group_by("adjustments.id");

                if (count($warehouse_ids) > 1) {
                    $this->datatables->where_in('adjustments.warehouse_id', $warehouse_ids);
                } else {
                    $this->datatables->where('adjustments.warehouse_id', $warehouse_id);
                }

        } else {
            $this->datatables
                ->select("adjustments.id as id, adjustments.date, adjustments.reference_no, warehouses.name as wh_name, SUM(COALESCE(abs(erp_adjustment_items.quantity),0)) as quantity,
                CONCAT({$this->db->dbprefix('users')}.first_name, ' ', 
                {$this->db->dbprefix('users')}.last_name) as created_by, 
                {$this->db->dbprefix('adjustments')}.note, 
                {$this->db->dbprefix('adjustments')}.attachment")
                ->from('adjustments')
                ->join('warehouses', 'warehouses.id=adjustments.warehouse_id', 'left')
                ->join('users', 'users.id=adjustments.created_by', 'left')
                ->join('adjustment_items', 'adjustment_items.adjust_id = adjustments.id', 'left')
                ->group_by("adjustments.id");
        }

        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('adjustments.created_by', $this->session->userdata('user_id'));
        }

        $this->datatables->add_column("Actions", "<div class='text-center'><a href='" . site_url('products/edit_multi_adjustment/$1') . "' class='tip' title='" . lang("edit_adjustment") . "'><i class='fa fa-edit'></i></a></div>", "id");

        echo $this->datatables->generate();

    }

    function getadjustmentsDetail($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('adjustments');
        $warehouse_ids = explode('-', $warehouse_id);

        $delete_link = "<a href='#' class='tip po' title='<b>" . $this->lang->line("delete_adjustment") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('products/delete_adjustment/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a>";

        $this->load->library('datatables');
            $this->datatables
                ->select("adjustments.date, reference_no, products.code as pcode, products.name as pname, product_variants.name as pvaraint, adjustment_items.quantity, adjustment_items.type, warehouses.name")
                ->from('adjustments')
                ->join('adjustment_items', 'adjustments.id = adjustment_items.adjust_id', 'left')
                ->join('product_variants', 'adjustment_items.option_id = product_variants.id', 'left')
                ->join('products', 'adjustment_items.product_id = products.id', 'left')
                ->join('warehouses', 'warehouses.id=adjustments.warehouse_id', 'left')
                ->join('users', 'users.id=adjustments.created_by', 'left')
                ->where('adjustment_items.product_id', $this->input->get('product'))
                ->group_by("adjustments.id");

        echo $this->datatables->generate();

    }

    function getadjustmentsDetailByProID($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('adjustments');
        $warehouse_ids = explode('-', $warehouse_id);

        $delete_link = "<a href='#' class='tip po' title='<b>" . $this->lang->line("delete_adjustment") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('products/delete_adjustment/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a>";

        $this->load->library('datatables');
        $this->datatables
            ->select("adjustments.date, reference_no, products.code as pcode, products.name as pname, product_variants.name as pvaraint, adjustment_items.quantity, adjustment_items.type, warehouses.name")
            ->from('adjustments')
            ->join('adjustment_items', 'adjustments.id = adjustment_items.adjust_id', 'left')
            ->join('product_variants', 'adjustment_items.option_id = product_variants.id', 'left')
            ->join('products', 'adjustment_items.product_id = products.id', 'left')
            ->join('warehouses', 'warehouses.id=adjustments.warehouse_id', 'left')
            ->join('users', 'users.id=adjustments.created_by', 'left')
            ->where('adjustment_items.product_id', $this->input->get('product'))
            ->group_by("adjustments.id");

        echo $this->datatables->generate();

    }
        
    function getProductAll($pdf = NULL, $excel = NULL)
    {
        $this->erp->checkPermissions('export', NULL, 'products');

        $product = $this->input->get('product') ? $this->input->get('product') : NULL;

        if ($pdf || $excel) {

            $this->db
                ->select($this->db->dbprefix('products') . ".code as codes, " . $this->db->dbprefix('products') . ".name as names,". $this->db->dbprefix('products') .".unit as units,
                " . $this->db->dbprefix('categories') . ".name as cname, " . $this->db->dbprefix('products') . ".cost as costes, 
                " . $this->db->dbprefix('products') . ".price as prices, " . $this->db->dbprefix('products') . ".quantity as quantities,
                " . $this->db->dbprefix('products') . ".alert_quantity as alert_quantities,
                " . $this->db->dbprefix('warehouses') . ".name as wname");
            $this->db->from('products');
            $this->db->join('categories', 'categories.id=products.category_id', 'left');
            $this->db->join('warehouses', 'warehouses.id=products.warehouse', 'left');
            $this->db->group_by("products.id")->order_by('products.id desc');
            if ($product) {
                $this->db->where('product.id', $product);
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
                $this->excel->getActiveSheet()->setTitle(lang('products'));
                $this->excel->getActiveSheet()->SetCellValue('A1', lang('product_code'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('product_name'));
                $this->excel->getActiveSheet()->SetCellValue('C1', lang('category'));
                $this->excel->getActiveSheet()->SetCellValue('D1', lang('product_cost'));
                $this->excel->getActiveSheet()->SetCellValue('E1', lang('product_price'));
                $this->excel->getActiveSheet()->SetCellValue('F1', lang('quantity'));
                $this->excel->getActiveSheet()->SetCellValue('G1', lang('product_unit'));
                $this->excel->getActiveSheet()->SetCellValue('H1', lang('alert_quantity'));

                $row = 2;
                foreach ($data as $data_row) {
                    //$this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($data_row->id));
                    $this->excel->getActiveSheet()->SetCellValue('A' . $row, $data_row->codes);
                    $this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->names);
                    $this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->cname);
                    $this->excel->getActiveSheet()->SetCellValue('D' . $row, $data_row->costes);
                    $this->excel->getActiveSheet()->SetCellValue('E' . $row, $data_row->prices);
                    $this->excel->getActiveSheet()->SetCellValue('F' . $row, lang($data_row->quantities));
                    $this->excel->getActiveSheet()->SetCellValue('G' . $row, lang($data_row->units));
                    $this->excel->getActiveSheet()->SetCellValue('H' . $row, lang($data_row->alert_quantities));
                    //$this->excel->getActiveSheet()->SetCellValue('G' . $row, $data_row->wh);
                    $row++;
                }

                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $filename = 'Product_' . date('Y_m_d_H_i_s');
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                if ($pdf) {
                    $styleArray = array(
                        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                    );
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
                    $objWriter->save('php://output');
                    exit();
                }
                if ($excel) {
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

        }
    }
    
    public function qa_suggestions()
    {
        $term = $this->input->get('term', true);
        $ware = $this->input->get('warehouse_id', true);
        
        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $analyzed   = $this->erp->analyze_term($term);
        $sr         = $analyzed['term'];
        $option_id  = $analyzed['option_id'];   
        $rows       = $this->products_model->getQASuggestions($sr, $ware);

        if ($rows) {
            foreach ($rows as $row) {
                $row->qty = 1;
                $options = $this->products_model->getProductOptions($row->id);
                $row->option = $option_id;
                $row->serial = '';

                $pr[] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'options' => $options);

            }
            $this->erp->send_json($pr);
        } else {
            $this->erp->send_json(array(array('id' => 0, 'label' => lang('item_no_cost'), 'value' => $term)));
        }
    }
    
    /************Also use with stock count import with adjustment***************/
    
    function add_adjustment_multiple($count_id = NULL)
    {
        $this->erp->checkPermissions('adjustments', true);
        $this->form_validation->set_rules('warehouse', lang("warehouse"), 'required');
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required|is_unique[adjustments.reference_no]');        
        
        if ($this->form_validation->run() == true)
        {
            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld($this->input->post('date'));
            } else {
                $date = date('Y-m-d H:s:i');
            }

            isCloseDate(date('Y-m-d', strtotime($date)));

            $reference_no   = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('qa', $this->default_biller_id);
            $warehouse_id   = $this->input->post('warehouse');
            $customer       = $this->input->post('customer');
            $note = $this->input->post('note');
            $biller = $this->input->post('biller');
            $i              = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
            $totalCost      = 0;
            
            for ($r = 0; $r < $i; $r++) {
                $product_id     = $_POST['product_id'][$r];
                $type           = $_POST['type'][$r];
                $quantity       = $_POST['quantity'][$r];
                $serial         = isset($_POST['serial'][$r]) ? $_POST['serial'][$r] : '';
                $item_expiry    = isset($_POST['expiry'][$r]) ? $this->erp->fsd($_POST['expiry'][$r]) : NULL;
                $variant        = isset($_POST['variant'][$r]) && !empty($_POST['variant'][$r]) ? $_POST['variant'][$r] : NULL;

                if ($type == 'subtraction') {
                    $unit_qty   = $this->products_model->getProductVariantByOptionID($variant);
                    $wh_qty     = $this->products_model->getProductQuantity($product_id, $warehouse_id);
                    $var_qty    = $unit_qty->qty_unit * $quantity;
                    
                    if($wh_qty['quantity'] < 0 or $wh_qty['quantity'] < $var_qty){
                        $this->session->set_flashdata('error', lang('warehouse_qty_is_less_than_damage'));
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                }
                
                $qty                = $type == 'addition'? $quantity : ((-1) * $quantity);
                $p                  = $this->products_model->getProductByID($product_id);
                $cost               = $p->cost;
                $quantity_balance   = 0;
                
                //================ Check Cost ================//
                if (!$cost) {
                    $this->session->set_flashdata('error', lang('product_cost_zero') . ' ( ' . $p->name . ' )');
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                //==================== End ===================//
                
                if($variant){
                    $option             = $this->products_model->getProductVariantByOptionID($variant);
                    $total_cost         = $cost * ($qty * $option->qty_unit);
                    $quantity_balance   = $qty * $option->qty_unit;
                }else{
                    $total_cost         = $cost * $qty;
                    $quantity_balance   = $qty;
                }
                if ($item_expiry) {
                    $today = date('Y-m-d');
                    if ($item_expiry <= $today) {
                        $this->session->set_flashdata('error', lang('product_expiry_date_issue') . ' ( ' . $p->name . ' )');
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                }
                $products[] = array(
                    'product_id'        => $product_id,
                    'product_code'      => $p->code,
                    'product_name'      => $p->name,
                    'product_type'      => $p->type,
                    'type'              => $type,
                    'quantity'          => $qty,
                    'warehouse_id'      => $warehouse_id,
                    'option_id'         => $variant,
                    'serial_no'         => $serial,
                    'cost'              => $cost,
                    'total_cost'        => $total_cost,
                    'quantity_balance'  => $quantity_balance,
                    'expiry'            => $item_expiry
                );
                
                $totalCost += $total_cost;
            }
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("products"), 'required');
            } else {
                krsort($products);
            }
            
            $data = array(
                'date'          => $date,
                'warehouse_id'  => $warehouse_id,
                'note'          => $note,
                'reference_no'  => $reference_no,
                'created_by'    => $this->session->userdata('user_id'),
                'biller_id'     => $biller ? $biller : $this->default_biller_id,
                'customer_id'   => $customer,
                'total_cost'    => $totalCost
            );
            
            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }
            //$this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->products_model->addMultiAdjustment($data, $products)) {
            optimizeStockAdjustment(date('Y-m-d', strtotime($data['date'])));
            $this->session->set_userdata('remove_adjustments', 1);
            $this->session->set_flashdata('message', $this->lang->line("quantity_adjusted"));
            redirect('products/quantity_adjustments');
        } else {

            if ($count_id) {
                $stock_count = $this->products_model->getStouckCountByID($count_id);
                $items = $this->products_model->getStockCountItems($count_id);
                $c = rand(100000, 9999999);
                foreach ($items as $item) {
                    if ($item->counted != $item->expected) {
                        $product = $this->site->getProductByID($item->product_id);
                        $row = json_decode('{}');
                        $row->id = $item->product_id;
                        $row->code = $product->code;
                        $row->name = $product->name;
                        $row->qty = $item->counted-$item->expected;
                        $row->type = $row->qty > 0 ? 'addition' : 'subtraction';
                        $row->qty = $row->qty > 0 ? $row->qty : (0-$row->qty);
                        $options = $this->products_model->getProductOptions($product->id);
                        $row->option = $item->product_variant_id ? $item->product_variant_id : 0;
                        $row->serial = '';
                        $ri = $this->Settings->item_addition ? $product->id : $c;

                        $pr[$ri] = array('id' => str_replace(".", "", microtime(true)), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")",
                            'row' => $row, 'options' => $options);
                        $c++;
                    }
                }
            }
            $warehouse_id = $this->session->userdata('warehouse_id');
            $this->data['adjustment_items'] = $count_id ? json_encode($pr) : FALSE;
            $this->data['warehouse_id']     = $count_id ? $stock_count->warehouse_id : FALSE;
            $this->data['count_id']         = $count_id;
            $this->data['error']            = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['warehouses']       = $this->site->getAllWarehouses();
            $this->data['customers']            = $this->site->getCustomers();
            $this->data['warehouses_by_user'] = $this->products_model->getAllWarehousesByUser($warehouse_id);
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')){
                $biller_id = $this->site->get_setting()->default_biller;
                $this->data['reference'] = $this->site->getReference('qa',$biller_id);
            }else{
                $biller_id = $this->session->userdata('biller_id');
                $this->data['reference'] = $this->site->getReference('qa',$biller_id);
            }
            
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('add_adjustment_multiple')));
            $meta = array('page_title' => lang('add_adjustment_multiple'), 'bc' => $bc);
            $this->page_construct('products/add_adjustment_multiple', $meta, $this->data);

        }
    }
    
    /************Please check add adjustment use or not****************/
    
    function add_adjustment($product_id = NULL, $warehouse_id = NULL)
    {
        $this->erp->checkPermissions('adjustments', true);
        $this->form_validation->set_rules('type', lang("type"), 'required');
        $this->form_validation->set_rules('quantity', lang("quantity"), 'required');
        $this->form_validation->set_rules('warehouse', lang("warehouse"), 'required');      
        if ($this->form_validation->run() == true) {

            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld($this->input->post('date'));
            } else {
                $date = date('Y-m-d H:s:i');
            }
            
            $data = array(
                'date'          => $date,
                'product_id'    => $product_id,
                'type'          => $this->input->post('type'),
                'quantity'      => $this->input->post('quantity'),
                'warehouse_id'  => $this->input->post('warehouse'),
                'option_id'     => $this->input->post('option') ? $this->input->post('option') : NULL,
                'note'          => $this->input->post('note'),
                'created_by'    => $this->session->userdata('user_id'),
                'biller_id'     => $this->default_biller_id ? $this->default_biller_id:''
                );
            
            $qty = $this->input->post('type') == 'addition' ? $this->input->post('quantity') : ((-1) * $this->input->post('quantity')); 
            
            $product = $this->db->where("id",$product_id)->get("products")->row();
            $dataPurchase = array(
                'product_id'        => $product_id,
                'product_code'      => $product->code,
                'product_name'      => $product->name,
                'option_id'         => $this->input->post('option') ? $this->input->post('option') : NULL,
                'note'              => $this->input->post('note'),
                'warehouse_id'      => $this->input->post('warehouse'),
                'quantity'          => $qty,
                'quantity_balance'  => $qty,
                'type'              => $this->input->post('type'),
                'date'              => $date,
                'transaction_type'  => 'ADJUSTMENT',
                'status'            => 'received'
            );
            
            if (!$this->Settings->overselling && $this->input->post('type') == 'subtraction') {
                if ($this->input->post('option')) {
                    if($op_wh_qty = $this->products_model->getProductWarehouseOptionQty($this->input->post('option'), $this->input->post('warehouse'))) {
                        if ($op_wh_qty->quantity < $data['quantity']) {
                            $this->session->set_flashdata('error', lang('warehouse_option_qty_is_less_than_damage'));
                            redirect($_SERVER["HTTP_REFERER"]);
                        }
                    } else {
                        $this->session->set_flashdata('error', lang('warehouse_option_qty_is_less_than_damage'));
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                }
                if($wh_qty = $this->products_model->getProductQuantity($product_id, $this->input->post('warehouse'))) {
                    if ($wh_qty['quantity'] < $data['quantity']) {
                        $this->session->set_flashdata('error', lang('warehouse_qty_is_less_than_damage'));
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                } else {
                    $this->session->set_flashdata('error', lang('warehouse_qty_is_less_than_damage'));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            }

        } elseif ($this->input->post('adjust_quantity')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('products');
        }
            
        if ($this->form_validation->run() == true && $this->products_model->addAdjustment($data,$dataPurchase)) {
            
            $this->session->set_flashdata('message', lang("quantity_adjusted"));
            redirect('products/quantity_adjustments');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $product = $this->site->getProductByID($product_id);
            if($product->type != 'standard') {
                $this->session->set_flashdata('error', lang('quantity_x_adjuste').' ('.lang('product_type').': '.lang($product->type).')');
                die('<script>window.location.replace("'.$_SERVER["HTTP_REFERER"].'");</script>');
            }
            $this->data['product'] = $product;
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['modal_js'] = $this->site->modal_js();
            $this->data['options'] = $this->products_model->getProductOptions($product_id);
            $this->data['product_id'] = $product_id;
            $this->data['warehouse_id'] = $warehouse_id;
            $this->load->view($this->theme . 'products/add_adjustment', $this->data);

        }
    }
    
    function multi_adjustment()
    {
        $this->load->view($this->theme . 'products/edit_adjustment', $this->data);
    }

    function edit_adjustment($product_id = NULL, $id = NULL)
    {
        $this->erp->checkPermissions('adjustments', true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        if ($this->input->get('product_id')) {
            $product_id = $this->input->get('product_id');
        }
        $this->form_validation->set_rules('type', lang("type"), 'required');
        $this->form_validation->set_rules('quantity', lang("quantity"), 'required');
        $this->form_validation->set_rules('warehouse', lang("warehouse"), 'required');

        if ($this->form_validation->run() == true) {

            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld($this->input->post('date'));
            } else {
                $date = NULL;
            }

            $data = array(
                'product_id' => $product_id,
                'type' => $this->input->post('type'),
                'quantity' => $this->input->post('quantity'),
                'warehouse_id' => $this->input->post('warehouse'),
                'option_id' => $this->input->post('option') ? $this->input->post('option') : NULL,
                'note' => $this->input->post('note'),
                'updated_by' => $this->session->userdata('user_id')
                );
            if ($date) {
                $data['date'] = $date;
            }

            if (!$this->Settings->overselling && $this->input->post('type') == 'subtraction') {
                $dp_details = $this->products_model->getAdjustmentByID($id);
                if ($this->input->post('option')) {
                    $op_wh_qty = $this->products_model->getProductWarehouseOptionQty($this->input->post('option'), $this->input->post('warehouse'));
                    $old_op_qty = $op_wh_qty->quantity + $dp_details->quantity;
                    if ($old_op_qty < $data['quantity']) {
                        $this->session->set_flashdata('error', lang('warehouse_option_qty_is_less_than_damage'));
                        redirect('products');
                    }
                }
                $wh_qty = $this->products_model->getProductQuantity($product_id, $this->input->post('warehouse'));
                $old_quantity = $wh_qty['quantity'] + $dp_details->quantity;
                if ($old_quantity < $data['quantity']) {
                    $this->session->set_flashdata('error', lang('warehouse_qty_is_less_than_damage'));
                    redirect('products/quantity_adjustments');
                }
            }

        } elseif ($this->input->post('edit_adjustment')) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('products/quantity_adjustments');
        }

        if ($this->form_validation->run() == true && $this->products_model->updateAdjustment($id, $data)) {
            $this->session->set_flashdata('message', lang("quantity_adjusted"));
            redirect('products/quantity_adjustments');
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $this->data['product'] = $this->site->getProductByID($product_id);
            $this->data['options'] = $this->products_model->getProductOptions($product_id);
            $this->data['damage'] = $this->products_model->getAdjustmentByID($id);
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['id'] = $id;
            $this->data['product_id'] = $product_id;
            $this->data['modal_js'] = $this->site->modal_js();
            $this->load->view($this->theme . 'products/edit_adjustment', $this->data);
        }
    }
    
    function edit_multi_adjustment($id)
    {
        $this->erp->checkPermissions('adjustments', true);
        $adjustment = $this->products_model->getAdjustmentByID($id);
        if (!$id || !$adjustment) {
            $this->session->set_flashdata('error', lang('adjustment_not_found'));
            $this->erp->md();
        }
        $this->form_validation->set_rules('warehouse', lang("warehouse"), 'required');

        if ($this->form_validation->run() == true) {

            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld($this->input->post('date'));
            } else {
                $date = $adjustment->date;
            }
            isCloseDate(date('Y-m-d', strtotime($date)));
            $reference_no   = $this->input->post('reference_no');
            $warehouse_id   = $this->input->post('warehouse');
            $note = $this->input->post('note');
            $biller = $this->input->post('biller');
            $customer_id    = $this->input->post('customer');
            $serial         = '';
            $totalCost      = 0;
            
            $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
            for ($r = 0; $r < $i; $r++) {

                $product_id = $_POST['product_id'][$r];
                $type       = $_POST['type'][$r];
                $itemid     = $_POST['itemid'][$r];
                $quantity   = $_POST['quantity'][$r];
                $item_expiry = isset($_POST['expiry'][$r]) ? $this->erp->fsd($_POST['expiry'][$r]) : NULL;
                $variant    = isset($_POST['variant'][$r]) && !empty($_POST['variant'][$r]) ? $_POST['variant'][$r] : null;

                if (!$this->Settings->overselling && $type == 'subtraction') {
                    $unit_qty   = $this->products_model->getProductVariantByOptionID($variant);
                    $wh_qty     = $this->products_model->getProductQuantity($product_id, $warehouse_id);
                    $var_qty    = $unit_qty->qty_unit * $quantity;
                    
                    if($wh_qty['quantity'] < 0 or $wh_qty['quantity'] < $var_qty){
                        $this->session->set_flashdata('error', lang('warehouse_qty_is_less_than_damage'));
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                }

                $qty = $type == 'addition'? $quantity : ((-1) * $quantity);
                $p                  = $this->products_model->getProductByID($product_id);
                $cost               = $p->cost;
                $quantity_balance   = 0;
                if($variant){
                    $option = $this->products_model->getProductVariantByOptionID($variant);
                    $total_cost         = $cost * ($qty * $option->qty_unit);
                    $quantity_balance   = $qty * $option->qty_unit;
                }else{
                    $total_cost         = $cost * $qty;
                    $quantity_balance   = $qty;
                }
                
                $dataid[] = array(
                    'itemidd' => $itemid
                );
                
                if ($item_expiry) {
                    $today = date('Y-m-d');
                    if ($item_expiry <= $today) {
                        $this->session->set_flashdata('error', lang('product_expiry_date_issue') . ' ( ' . $p->name . ' )');
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                }
                
                $products[] = array(
                    'product_id'        => $product_id,
                    'product_code'      => $p->code,
                    'product_name'      => $p->name,
                    'product_type'      => $p->type,
                    'type'              => $type,
                    'quantity'          => $qty,
                    'warehouse_id'      => $warehouse_id,
                    'option_id'         => $variant,
                    'serial_no'         => $serial,
                    'cost'              => $cost,
                    'total_cost'        => $total_cost,
                    'quantity_balance'  => $quantity_balance,
                    'expiry'            => $item_expiry
                );

                $totalCost += $total_cost;

            }
            
            if (empty($products)) {
                $this->form_validation->set_rules('product', lang("products"), 'required');
            } else {
                krsort($products);
            }

            $data = array(
                'date'          => $date,
                'warehouse_id'  => $warehouse_id,
                'note'          => $note,
                'reference_no'  => $reference_no,
                'updated_by'    => $this->session->userdata('user_id'),
                'biller_id'     => $biller ? $biller:$this->default_biller_id,
                'customer_id'   => $customer_id,
                'total_cost'    => $totalCost
            );

            if ($_FILES['document']['size'] > 0) {
                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = $this->digital_file_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('document')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                $photo = $this->upload->file_name;
                $data['attachment'] = $photo;
            }       
            //$this->erp->print_arrays($data, $products);
        }

        if ($this->form_validation->run() == true && $this->products_model->updateMultiAdjustment($id, $data, $products, $dataid)) {
            optimizeStockAdjustment(date('Y-m-d', strtotime($data['date'])));
            $this->session->set_userdata('remove_qals', 1);
            $this->session->set_flashdata('message', lang("quantity_adjusted"));
            redirect('products/quantity_adjustments');
        } else {

            $inv_items = $this->products_model->getAdjustmentItems($id);
            krsort($inv_items);
            $c = rand(100000, 9999999);
            foreach ($inv_items as $item) {
                $product        = $this->site->getProductByID($item->product_id);
                $qoh            = $this->products_model->getAdjustQtyFromWare($item->adjust_id, $item->product_id);
                $pur_item_exp   = $this->products_model->getAdjustExpiryDate($item->id, $item->product_id);
                $row            = json_decode('{}');
                $row->id        = $item->product_id;
                $row->code      = $product->code;
                $row->name      = $product->name;
                $row->qty       = abs($item->quantity);
                $row->type      = $item->type;
                $row->expiry    = ((isset($pur_item_exp->expiry) && $pur_item_exp->expiry != '0000-00-00') ? $this->erp->hrsd($pur_item_exp->expiry) : '');
                $row->qoh       = $qoh->quantity;
                $options        = $this->products_model->getProductOptions($product->id);
                $row->option    = $item->option_id ? $item->option_id : 0;
                $row->serial    = $item->serial_no ? $item->serial_no : '';
                $ri             = $this->Settings->item_addition ? $product->id : $c;

                $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row, 'options' => $options,'item_ids'=>$item->id);
                $c++;
            }
            $warehouse_id = $this->session->userdata('warehouse_id');
            $this->data['adjustment'] = $adjustment;            
            $this->data['adjustment_items'] = json_encode($pr);
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['products']         = $this->products_model->getProductByID($id);
            $this->data['customers']            = $this->site->getCustomers();
            $this->data['billers']    = $this->site->getAllCompanies('biller');
            $this->data['warehouses_by_user'] = $this->products_model->getAllWarehousesByUser($warehouse_id);

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('edit_adjustment')));
            $meta = array('page_title' => lang('edit_adjustment'), 'bc' => $bc);
            $this->page_construct('products/edit_multi_adjustment', $meta, $this->data);
        }
    }

    function upload_image(){
        
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $this->load->library('upload');
            if ($_FILES['userfile']['name'][0] != "") {

                $config['upload_path'] = $this->upload_path;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['max_filename'] = 30;
                $files = $_FILES;
                $cpt = count($_FILES['userfile']['name']);

                for ($i = 0; $i < $cpt; $i++) {
                    $_FILES['userfile']['name']     = $this->erp->convertImageSpecialChar($files['userfile']['name'][$i]);
                    $_FILES['userfile']['type']     = $files['userfile']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $files['userfile']['tmp_name'][$i];
                    $_FILES['userfile']['error']    = $files['userfile']['error'][$i];
                    $_FILES['userfile']['size']     = $files['userfile']['size'][$i];

                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload()) {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('error', $error);
                        redirect("products/upload_image");
                    } else {

                        $pho = $this->upload->file_name;
                        $photos[] = $pho;

                        $this->load->library('image_lib');
                        $config['image_library'] = 'gd2';
                        $config['source_image'] = $this->upload_path . $pho;
                        $config['new_image'] = $this->thumbs_path . $pho;
                        $config['maintain_ratio'] = TRUE;
                        $config['width'] = $this->Settings->iwidth;
                        $config['height'] = $this->Settings->iheight;

                        $this->image_lib->initialize($config);
                        copy($config['new_image'] , $config['source_image']);
                        if (!$this->image_lib->resize()) {
                            echo $this->image_lib->display_errors();
                        }

                        if ($this->Settings->watermark) {
                            $this->image_lib->clear();
                            $wm['source_image'] = $this->upload_path . $pho;
                            $wm['wm_text'] = 'Copyright ' . date('Y') . ' - ' . $this->Settings->site_name;
                            $wm['wm_type'] = 'text';
                            $wm['wm_font_path'] = 'system/fonts/texb.ttf';
                            $wm['quality'] = '100';
                            $wm['wm_font_size'] = '16';
                            $wm['wm_font_color'] = '999999';
                            $wm['wm_shadow_color'] = 'CCCCCC';
                            $wm['wm_vrt_alignment'] = 'top';
                            $wm['wm_hor_alignment'] = 'right';
                            $wm['wm_padding'] = '10';
                            $this->image_lib->initialize($wm);
                            $this->image_lib->watermark();
                        }
                        $this->image_lib->clear();
                    }
                }
                $config = NULL;
            } else {
                $photos = NULL;
            }
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('upload_image')));
            $meta = array('page_title' => lang('upload_image'), 'bc' => $bc);
            
        $this->page_construct('products/upload_image', $meta, $this->data);
    }
    
    function delete_adjustment($id = NULL)
    {
        $this->erp->checkPermissions(NULL, TRUE);

        if ($this->products_model->deleteAdjustment($id)) {
            echo lang("adjustment_deleted");
        }

    }

    function modal_view($id = NULL)
    {
        $this->erp->checkPermissions('index', TRUE);

        $pr_details = $this->site->getProductByIDForModalView($id);
        $pr_details_user = $this->site->getUserWarehouseProduct($id);

        if (!$id || !$pr_details) {
            $this->session->set_flashdata('error', lang('prduct_not_found'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $pr_details->code . '/' . $pr_details->barcode_symbology . '/40/0') . "' alt='" . $pr_details->code . "' class='pull-left' />";
        if ($pr_details->type == 'combo') {
            $this->data['combo_items'] = $this->products_model->getProductComboItems($id);
        }
        $this->db->select('*');
        $this->db->from('companies');
        $this->db->where('id',  $pr_details->supplier1);
        $q = $this->db->get()->result();

        $this->data['supplier'] = $q;
        $this->data['product'] = $pr_details;
        $this->data['product_user'] = $pr_details_user;
        $this->data['images'] = $this->products_model->getProductPhotos($id);
        $this->data['category'] = $this->site->getCategoryByID($pr_details->category_id);
        $this->data['subcategory'] = $pr_details->subcategory_id ? $this->products_model->getSubCategoryByID($pr_details->subcategory_id) : NULL;
        $this->data['tax_rate'] = $pr_details->tax_rate ? $this->site->getTaxRateByID($pr_details->tax_rate) : NULL;
        $this->data['warehouses'] = $this->products_model->getAllWarehousesWithPQ($id);
        $this->data['options'] = $this->products_model->getProductOptionsWithWH($id);
        $this->data['variants'] = $this->products_model->getProductOptionsData($id);
        $this->data['ordered_products_qty'] = $this->products_model->getAllOrderProductsQty($id);
        
        $this->load->view($this->theme.'products/modal_view', $this->data);
    }

    function view($id = NULL)
    {
        $this->erp->checkPermissions('index');

        $pr_details = $this->products_model->getProductByID($id);
        if (!$id || !$pr_details) {
            $this->session->set_flashdata('error', lang('prduct_not_found'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $pr_details->code . '/' . $pr_details->barcode_symbology . '/40/0') . "' alt='" . $pr_details->code . "' class='pull-left' />";
        if ($pr_details->type == 'combo') {
            $this->data['combo_items'] = $this->products_model->getProductComboItems($id);
        }
        $this->data['product'] = $pr_details;
        $this->data['images'] = $this->products_model->getProductPhotos($id);
        $this->data['category'] = $this->site->getCategoryByID($pr_details->category_id);
        $this->data['subcategory'] = $pr_details->subcategory_id ? $this->products_model->getSubCategoryByID($pr_details->subcategory_id) : NULL;
        $this->data['tax_rate'] = $pr_details->tax_rate ? $this->site->getTaxRateByID($pr_details->tax_rate) : NULL;
        $this->data['popup_attributes'] = $this->popup_attributes;
        $this->data['warehouses'] = $this->products_model->getAllWarehousesWithPQ($id);
        $this->data['options'] = $this->products_model->getProductOptionsWithWH($id);
        $this->data['variants'] = $this->products_model->getProductOptions($id);
        $this->data['sold'] = $this->products_model->getSoldQty($id);
        $this->data['purchased'] = $this->products_model->getPurchasedQty($id);

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => $pr_details->name));
        $meta = array('page_title' => $pr_details->name, 'bc' => $bc);
        $this->page_construct('products/view', $meta, $this->data);
    }

    function pdf($id = NULL, $view = NULL)
    {
        $this->erp->checkPermissions('index');

        $pr_details = $this->products_model->getProductByID($id);
        if (!$id || !$pr_details) {
            $this->session->set_flashdata('error', lang('prduct_not_found'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->data['barcode'] = "<img src='" . site_url('products/gen_barcode/' . $pr_details->code . '/' . $pr_details->barcode_symbology . '/40/0') . "' alt='" . $pr_details->code . "' class='pull-left' />";
        if ($pr_details->type == 'combo') {
            $this->data['combo_items'] = $this->products_model->getProductComboItems($id);
        }
        $this->data['product'] = $pr_details;
        $this->data['images'] = $this->products_model->getProductPhotos($id);
        $this->data['category'] = $this->site->getCategoryByID($pr_details->category_id);
        $this->data['subcategory'] = $pr_details->subcategory_id ? $this->products_model->getSubCategoryByID($pr_details->subcategory_id) : NULL;
        $this->data['tax_rate'] = $pr_details->tax_rate ? $this->site->getTaxRateByID($pr_details->tax_rate) : NULL;
        $this->data['popup_attributes'] = $this->popup_attributes;
        $this->data['warehouses'] = $this->products_model->getAllWarehousesWithPQ($id);
        $this->data['options'] = $this->products_model->getProductOptionsWithWH($id);
        $this->data['variants'] = $this->products_model->getProductOptions($id);

        $name = $pr_details->code . '_' . str_replace('/', '_', $pr_details->name) . ".pdf";
        if ($view) {
            $this->load->view($this->theme . 'products/pdf', $this->data);
        } else {
            $html = $this->load->view($this->theme . 'products/pdf', $this->data, TRUE);
            $this->erp->generate_pdf($html, $name);
        }
    }
    
    function getCategories($band_id = NULL)
    {
        if ($rows = $this->products_model->getCategoriesForBrandID($band_id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
    }

    function getSubCategories($category_id = NULL)
    {
        if ($rows = $this->products_model->getSubCategoriesForCategoryID($category_id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
    }

    function product_actions($wh = NULL)
    {
        /*if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }*/
        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'sync_quantity') {
                    foreach ($_POST['val'] as $id) {
                        $this->site->syncQuantity(NULL, NULL, NULL, $id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("products_quantity_sync"));
                    redirect($_SERVER["HTTP_REFERER"]);
                
                }else if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->products_model->deleteProduct($id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("products_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                    
                }else if ($this->input->post('form_action') == 'labels') {
                    $currencies = $this->site->getAllCurrencies();
                    $r = 1;
                    $inputs = '';
                    $html = "";
                    $html .= '<table class="table table-bordered table-condensed bartable"><tbody><tr>';
                    foreach ($_POST['val'] as $id) {
                        $inputs .= form_hidden('val[]', $id);
                        $pr = $this->products_model->getProductByID($id);

                        $html .= '<td class="text-center"><h4>' . $this->Settings->site_name . '</h4>' . $pr->name . '<br>' . $this->product_barcode($pr->code, $pr->barcode_symbology, 30);
                        $html .= '<table class="table table-bordered">';
                        foreach ($currencies as $currency) {
                            $html .= '<tr><td class="text-left">' . $currency->code . '</td><td class="text-right">' . $this->erp->formatMoney($pr->price * $currency->rate) . '</td></tr>';
                        }
                        $html .= '</table>';
                        $html .= '</td>';

                        if ($r % 4 == 0) {
                            $html .= '</tr><tr>';
                        }
                        $r++;
                    }
                    if ($r < 4) {
                        for ($i = $r; $i <= 4; $i++) {
                            $html .= '<td></td>';
                        }
                    }
                    $html .= '</tr></tbody></table>';

                    $this->data['r'] = $r;
                    $this->data['html'] = $html;
                    $this->data['inputs'] = $inputs;
                    $this->data['page_title'] = lang("print_labels");
                    $this->data['categories'] = $this->site->getAllCategories();
                    $this->data['category_id'] = '';
                    //$this->load->view($this->theme . 'products/print_labels', $this->data);
                    $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('print_labels')));
                    $meta = array('page_title' => lang('print_labels'), 'bc' => $bc);
                    $this->page_construct('products/print_labels', $meta, $this->data);
                }else if ($this->input->post('form_action') == 'barcodes') {
                    foreach ($_POST['val'] as $id) {
                        $row = $this->products_model->getProductByID($id);
                        $selected_variants = false;
                        if ($variants = $this->products_model->getProductOptions($row->id)) {
                            foreach ($variants as $variant) {
                                $selected_variants[$variant->id] = $variant->quantity > 0 ? 1 : 0;
                            }
                        }
                        $pr[$row->id] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'code' => $row->code, 'name' => $row->name, 'price' => $row->price, 'qty' => $row->quantity, 'variants' => $variants, 'selected_variants' => $selected_variants);
                    }

                    $this->data['items'] = isset($pr) ? json_encode($pr) : false;
                    $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
                    $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('print_barcodes')));
                    $meta = array('page_title' => lang('print_barcodes'), 'bc' => $bc);
                    $this->page_construct('products/print_barcodes', $meta, $this->data);
                    
                }else if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
                    $row = 2;
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    
                    if($this->Owner || $this->Admin){
                        
                        $this->excel->getActiveSheet()->setTitle('Products');
                        $this->excel->getActiveSheet()->SetCellValue('A1', lang('product_code'));
                        $this->excel->getActiveSheet()->SetCellValue('B1', lang('product_name'));
                        $this->excel->getActiveSheet()->SetCellValue('C1', lang('product_name_kh'));
                        $this->excel->getActiveSheet()->SetCellValue('D1', lang('category'));
                        $this->excel->getActiveSheet()->SetCellValue('E1', lang('sub_category'));
                        $this->excel->getActiveSheet()->SetCellValue('F1', lang('product_cost'));
                        $this->excel->getActiveSheet()->SetCellValue('G1', lang('product_price'));
                        $this->excel->getActiveSheet()->SetCellValue('H1', lang('quantity'));
                        $this->excel->getActiveSheet()->SetCellValue('I1', lang('product_unit'));
                        $this->excel->getActiveSheet()->SetCellValue('J1', lang('alert_quantity'));
                                           
                        $sum_cost = 0;
                        $sum_price = 0;
                        $sum_quantity = 0;
                        foreach ($_POST['val'] as $id) {
                                $product = $this->products_model->getProductDetail($id);
                                $variants = $this->products_model->getProductOptions($id);
                                $product_variants = '';
                                if ($variants) {
                                        foreach ($variants as $variant) {
                                                $product_variants .= trim($variant->name) . '|';
                                        }
                                }
                                $quantity = $product->quantity;
                                if ($wh) {
                                        if($wh_qty = $this->products_model->getProductQuantity($id, $wh)) {
                                                $quantity = $wh_qty['quantity'];
                                        }else {
                                                $quantity = 0;
                                        }
                                }
                                //total some each value
                                
                                $sum_cost += $product->cost;
                                $sum_price += $product->price;
                                $sum_quantity += $quantity;

                                $this->excel->getActiveSheet()->SetCellValue('A' . $row, $product->code . " ");
                                $this->excel->getActiveSheet()->SetCellValue('B' . $row, $product->name);
                                $this->excel->getActiveSheet()->SetCellValue('C' . $row, $product->name_kh);
                                $this->excel->getActiveSheet()->SetCellValue('D' . $row, $product->category_name);
                                $this->excel->getActiveSheet()->SetCellValue('E' . $row, $product->subcategory_name);
                                $this->excel->getActiveSheet()->SetCellValue('F' . $row, $this->erp->formatMoney($product->cost));
                                $this->excel->getActiveSheet()->SetCellValue('G' . $row, $this->erp->formatMoney($product->price));
                                $this->excel->getActiveSheet()->SetCellValue('H' . $row, $quantity);
                                $this->excel->getActiveSheet()->SetCellValue('I' . $row, "    ".$product->p_unit);
                                $this->excel->getActiveSheet()->SetCellValue('J' . $row, $product->alert_quantity);

                                //to display total sum
                                $i = $row+1;
                                $this->excel->getActiveSheet()->SetCellValue('F' . $i, $this->erp->formatMoney($sum_cost));
                                $this->excel->getActiveSheet()->SetCellValue('G' . $i, $this->erp->formatMoney($sum_price));
                                $this->excel->getActiveSheet()->SetCellValue('H' . $i, $sum_quantity);
                                $row++;
                                
                        }
                        
                    }else{
                            
                            if($wh) {
                                $wh = explode('-', $wh);
                            }
                            if($this->session->userdata('show_cost') && $this->session->userdata('show_price')){
                                $this->excel->getActiveSheet()->setTitle('Products');
                                $this->excel->getActiveSheet()->SetCellValue('A1', lang('product_code'));
                                $this->excel->getActiveSheet()->SetCellValue('B1', lang('product_name'));
                                $this->excel->getActiveSheet()->SetCellValue('C1', lang('product_name_kh'));
                                $this->excel->getActiveSheet()->SetCellValue('D1', lang('category'));
                                $this->excel->getActiveSheet()->SetCellValue('E1', lang('sub_category'));
                                $this->excel->getActiveSheet()->SetCellValue('F1', lang('product_cost'));
                                $this->excel->getActiveSheet()->SetCellValue('G1', lang('product_price'));
                                $this->excel->getActiveSheet()->SetCellValue('H1', lang('quantity'));
                                $this->excel->getActiveSheet()->SetCellValue('I1', lang('product_unit'));
                                $this->excel->getActiveSheet()->SetCellValue('J1', lang('alert_quantity'));
                            }else if($this->session->userdata('show_cost')){
                                $this->excel->getActiveSheet()->setTitle('Products');
                                $this->excel->getActiveSheet()->SetCellValue('A1', lang('product_code'));
                                $this->excel->getActiveSheet()->SetCellValue('B1', lang('product_name'));
                                $this->excel->getActiveSheet()->SetCellValue('C1', lang('product_name_kh'));
                                $this->excel->getActiveSheet()->SetCellValue('D1', lang('category'));
                                $this->excel->getActiveSheet()->SetCellValue('E1', lang('sub_category'));
                                $this->excel->getActiveSheet()->SetCellValue('F1', lang('product_cost'));
                                $this->excel->getActiveSheet()->SetCellValue('G1', lang('quantity'));
                                $this->excel->getActiveSheet()->SetCellValue('H1', lang('product_unit'));
                                $this->excel->getActiveSheet()->SetCellValue('I1', lang('alert_quantity'));
                            }else{
                                $this->excel->getActiveSheet()->setTitle('Products');
                                $this->excel->getActiveSheet()->SetCellValue('A1', lang('product_code'));
                                $this->excel->getActiveSheet()->SetCellValue('B1', lang('product_name'));
                                $this->excel->getActiveSheet()->SetCellValue('C1', lang('product_name_kh'));
                                $this->excel->getActiveSheet()->SetCellValue('D1', lang('category'));
                                $this->excel->getActiveSheet()->SetCellValue('E1', lang('sub_category'));
                                $this->excel->getActiveSheet()->SetCellValue('F1', lang('product_price'));
                                $this->excel->getActiveSheet()->SetCellValue('G1', lang('quantity'));
                                $this->excel->getActiveSheet()->SetCellValue('H1', lang('product_unit'));
                                $this->excel->getActiveSheet()->SetCellValue('I1', lang('alert_quantity'));
                            }
                            $a = 2;
                            foreach ($_POST['val'] as $id) {
                                $this->db->select(
                                    $this->db->dbprefix('products') . ".code as code, " . 
                                    $this->db->dbprefix('products') . ".name as name, " . 
                                    $this->db->dbprefix('products') . ".name_kh as kname, " .
                                    $this->db->dbprefix('categories') . ".name as cname,subcategories.name as sub_name,cost as cost,price as price, COALESCE(sum(wp.quantity), 0) as quantity, ".
                                    $this->db->dbprefix("units").".name as unit, alert_quantity"
                                );
                                $this->db->from('products');
                                if ($this->Settings->display_all_products) {
                                $this->db->join("( SELECT * from {$this->db->dbprefix('warehouses_products')} WHERE warehouse_id = {$wh}) wp", 'products.id=wp.product_id', 'left');
                                } else {
                                    $this->db->join('warehouses_products wp', 'products.id=wp.product_id', 'left')
                                    ->where_in('wp.warehouse_id', $wh);
                                }
                                $this->db->where($this->db->dbprefix('products') . ".inactived !=", '1');
                                $this->db->where('wp.product_id', $id);
                                $this->db->join('categories', 'products.category_id=categories.id', 'left')
                                ->join('units', 'products.unit=units.id', 'left')
                                ->join('subcategories', 'subcategories.id=products.subcategory_id', 'left')
                                ->group_by("products.id");
                                $q = $this->db->get();
                                if ($q->num_rows() > 0) {
                                    foreach (($q->result()) as $row) {
                                        $data[] = $row;
                                    }
                                } else {
                                    $data = NULL;
                                }
                                $sum_cost = 0;
                                $sum_price = 0;
                                $sum_quantity = 0;
                                
                                foreach ($data as $product) {
                                    //total some each value
                                    $quantity = $product->quantity;
                                    $sum_cost += $product->cost;
                                    $sum_price += $product->price;
                                    $sum_quantity += $quantity;
                                    if($this->session->userdata('show_cost') && $this->session->userdata('show_price')){
                                        $this->excel->getActiveSheet()->SetCellValue('A' . $a, $product->code . " ");
                                        $this->excel->getActiveSheet()->SetCellValue('B' . $a, $product->name);
                                        $this->excel->getActiveSheet()->SetCellValue('C' . $a, $product->kname);
                                        $this->excel->getActiveSheet()->SetCellValue('D' . $a, $product->cname);
                                        $this->excel->getActiveSheet()->SetCellValue('E' . $a, $product->sub_name);
                                        $this->excel->getActiveSheet()->SetCellValue('F' . $a, $this->erp->formatMoney($product->cost));
                                        $this->excel->getActiveSheet()->SetCellValue('G' . $a, $this->erp->formatMoney($product->price));
                                        $this->excel->getActiveSheet()->SetCellValue('H' . $a, $quantity);
                                        $this->excel->getActiveSheet()->SetCellValue('I' . $a, "    ".$product->unit);
                                        $this->excel->getActiveSheet()->SetCellValue('J' . $a, $product->alert_quantity);   
                                        //to display total sum
                                        $i = $a+1;
                                        $this->excel->getActiveSheet()->SetCellValue('F' . $i, $this->erp->formatMoney($sum_cost));
                                        $this->excel->getActiveSheet()->SetCellValue('G' . $i, $this->erp->formatMoney($sum_price));
                                        $this->excel->getActiveSheet()->SetCellValue('H' . $i, $sum_quantity);
                                     }else if($this->session->userdata('show_cost')){
                                        $this->excel->getActiveSheet()->SetCellValue('A' . $a, $product->code . " ");
                                        $this->excel->getActiveSheet()->SetCellValue('B' . $a, $product->name);
                                        $this->excel->getActiveSheet()->SetCellValue('C' . $a, $product->kname);
                                        $this->excel->getActiveSheet()->SetCellValue('D' . $a, $product->cname);
                                        $this->excel->getActiveSheet()->SetCellValue('E' . $a, $product->sub_name);
                                        $this->excel->getActiveSheet()->SetCellValue('F' . $a, $this->erp->formatMoney($product->cost));
                                        $this->excel->getActiveSheet()->SetCellValue('G' . $a, $quantity);
                                        $this->excel->getActiveSheet()->SetCellValue('H' . $a, "    ".$product->unit);
                                        $this->excel->getActiveSheet()->SetCellValue('I' . $a, $product->alert_quantity);   
                                        //to display total sum
                                        $i = $a+1;
                                        $this->excel->getActiveSheet()->SetCellValue('F' . $i, $this->erp->formatMoney($sum_cost));
                                        $this->excel->getActiveSheet()->SetCellValue('G' . $i, $sum_quantity);
                                     }else{
                                        $this->excel->getActiveSheet()->SetCellValue('A' . $a, $product->code . " ");
                                        $this->excel->getActiveSheet()->SetCellValue('B' . $a, $product->name);
                                        $this->excel->getActiveSheet()->SetCellValue('C' . $a, $product->kname);
                                        $this->excel->getActiveSheet()->SetCellValue('D' . $a, $product->cname);
                                        $this->excel->getActiveSheet()->SetCellValue('E' . $a, $product->sub_name);
                                        $this->excel->getActiveSheet()->SetCellValue('F' . $a, $this->erp->formatMoney($product->price));
                                        $this->excel->getActiveSheet()->SetCellValue('G' . $a, $quantity);
                                        $this->excel->getActiveSheet()->SetCellValue('H' . $a, "    ".$product->unit);
                                        $this->excel->getActiveSheet()->SetCellValue('I' . $a, $product->alert_quantity);   
                                        //to display total sum
                                        $i = $a+1;
                                        $this->excel->getActiveSheet()->SetCellValue('F' . $i, $this->erp->formatMoney($sum_price));
                                        $this->excel->getActiveSheet()->SetCellValue('G' . $i, $sum_quantity);
                                     }
                                }
                                $a++;
                            }
                        
                    }          

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'products_' . date('Y_m_d_H_i_s');
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
                        if($this->Owner || $this->Admin){
                            //Set style to header in pdf
                            $this->excel->getActiveSheet()->getStyle('A1'.':J1')->getFont()->setBold(true);
                            $this->excel->getActiveSheet()->getStyle('A1'.':J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            //apply style border top and bold text in case excel
                            $this->excel->getActiveSheet()->getStyle('F'.$i. ':H'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                            $this->excel->getActiveSheet()->getStyle('F'.$i. ':H'.$i)->getFont()->setBold(true);
                        }else{
                            //Set style to header in pdf
                            $this->excel->getActiveSheet()->getStyle('A1'.':H1')->getFont()->setBold(true);
                            $this->excel->getActiveSheet()->getStyle('A1'.':H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            //apply style border top and bold text in case excel

                            if($this->session->userdata('show_cost') && $this->session->userdata('show_price')){
                                $this->excel->getActiveSheet()->getStyle('F'.$i. ':H'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                $this->excel->getActiveSheet()->getStyle('F'.$i. ':H'.$i)->getFont()->setBold(true);
                            }else{
                                $this->excel->getActiveSheet()->getStyle('F'.$i. ':G'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                $this->excel->getActiveSheet()->getStyle('F'.$i. ':G'.$i)->getFont()->setBold(true);
                            }
                            
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

                        if($this->Owner || $this->Admin){
                            //Set style to header in pdf
                            $this->excel->getActiveSheet()->getStyle('A1'.':J1')->getFont()->setBold(true);
                            $this->excel->getActiveSheet()->getStyle('A1'.':J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            //apply style border top and bold text in case excel
                            $this->excel->getActiveSheet()->getStyle('F'.$i. ':H'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                            $this->excel->getActiveSheet()->getStyle('F'.$i. ':H'.$i)->getFont()->setBold(true);
                        }else{
                            //Set style to header in pdf
                            $this->excel->getActiveSheet()->getStyle('A1'.':H1')->getFont()->setBold(true);
                            $this->excel->getActiveSheet()->getStyle('A1'.':H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            //apply style border top and bold text in case excel

                            if($this->session->userdata('show_cost') && $this->session->userdata('show_price')){
                                $this->excel->getActiveSheet()->getStyle('F'.$i. ':H'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                $this->excel->getActiveSheet()->getStyle('F'.$i. ':H'.$i)->getFont()->setBold(true);
                            }else{
                                $this->excel->getActiveSheet()->getStyle('F'.$i. ':G'.$i)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                                $this->excel->getActiveSheet()->getStyle('F'.$i. ':G'.$i)->getFont()->setBold(true);
                            }
                            
                        }
                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_product_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    
    public function delete_image($id = NULL)
    {
        /*$this->erp->checkPermissions('edit', true);
        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            $id || die(json_encode(array('error' => 1, 'msg' => lang('no_image_selected'))));
            $this->db->delete('product_photos', array('id' => $id));
            die(json_encode(array('error' => 0, 'msg' => lang('image_deleted'))));
        }
        die(json_encode(array('error' => 1, 'msg' => lang('ajax_error'))));
        */
        $this->erp->checkPermissions('edit', true);
         
        if ($this->products_model->deleteProductPhoto($id)) {
            if($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                $id || die(json_encode(array('error' => 1, 'msg' => lang('no_image_selected'))));
                die(json_encode(array('error' => 0, 'msg' => lang('image_deleted'))));
            }
        }
    }
    
    public function list_convert($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('items_convert', NULL, 'products');
        
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
            
        } else {

            $this->data['warehouses'] = $this->products_model->getUserWarehouses();
            if($warehouse_id){
                $this->data['warehouse_id'] = $warehouse_id;
                $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
            }else{
                $this->data['warehouse_id'] = str_replace(',', '-',$this->session->userdata('warehouse_id'));
                $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->products_model->getUserWarehouses() : NULL;
            }
        }

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('products')));
        $meta = array('page_title' => lang('list_convert'), 'bc' => $bc);
        $this->page_construct('products/list_convert', $meta, $this->data);
    }
    
    public function delete_convert($id = null)
    {
        $this->erp->checkPermissions('delete', true);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }
        if ($this->products_model->deleteConvert($id) && $this->products_model->deleteConvert_items($id)) {
            echo lang("convert_deleted");
        }
    }
    
    public function product_analysis($id = null)
    {
        //$convert = $this->products_model->getConvertByID($id);
        
        $header = $this->products_model->convertHeader($id);
        $deduct = $this->products_model->ConvertDeduct($id);
        $add    = $this->products_model->ConvertAdd($id);
        //$this->data['user'] = $this->site->getUser($convert->created_by);
        $this->data['header'] =$header;
        $this->data['deduct'] = $deduct;
        $this->data['add'] = $add;
        $this->data['logo'] = true;
        $this->data['page_title'] = $this->lang->line("product_analysis");
        $this->load->view($this->theme . 'products/product_anlysis', $this->data);
    }
    
    public function getListConvert($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('index', true, 'products');

        $add_link = '<a href="' . site_url('products/items_convert') . '"><i class="fa fa-plus-circle"></i> ' . lang('add_convert') . '</a>';
        $analysis_link = anchor('products/product_analysis/$1', '<i class="fa fa-file-text-o"></i> ' . lang('product_analysis'), 'data-toggle="modal" data-target="#myModal2"');
        $edit_link = '<a href="' . site_url('products/edit_convert/$1') . '"><i class="fa fa-edit"></i> ' . lang('edit_convert') . '</a>';
        $action = '<div class="text-center"><div class="btn-group text-left">'
        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'
        . lang('actions') . ' <span class="caret"></span></button>
            <ul class="dropdown-menu pull-right" role="menu">
                <li>' . $edit_link . '</li>
                <li>' . $analysis_link . '</li>
            </ul></div></div>';
        
        $this->load->library('datatables');

        $l_qty = "( SELECT
                        con_item.convert_id,
                        SUM(con_item.cost) as cost,
                        SUM(con_item.quantity) AS qty
                    FROM
                        erp_convert_items con_item
                    WHERE
                        con_item.`status` = 'add'
                    GROUP BY
                        con_item.convert_id
                    ) Quantity";

        if ($warehouse_id) {
            $warehouse_ids = explode('-', $warehouse_id);
            $this->datatables
            ->select($this->db->dbprefix('convert') . ".id as id,
                    ".$this->db->dbprefix('convert').".date AS Date,
                    ".$this->db->dbprefix('convert').".reference_no AS Reference, Quantity.cost, Quantity.qty,
                    ".$this->db->dbprefix('convert').".noted AS Note,
                    ".$this->db->dbprefix('warehouses').".name as na,
                    " . $this->db->dbprefix('users') . ".username ", false)
            ->from('convert')
            ->join('users', 'users.id               = convert.created_by', 'left')
            ->join('warehouses', 'warehouses.id     = convert.warehouse_id', 'left')
            ->join($l_qty, ' Quantity.convert_id    = erp_convert.id', 'left')
            ->group_by('convert.id');

            if (count($warehouse_ids) > 1) {
                $this->datatables->where_in('convert.warehouse_id', $warehouse_ids);
            } else {
                $this->datatables->where('convert.warehouse_id', $warehouse_id);
            }

        } else {
            $this->datatables
            ->select($this->db->dbprefix('convert') . ".id as id,
                    ".$this->db->dbprefix('convert').".date AS Date,
                    ".$this->db->dbprefix('convert').".reference_no AS Reference, Quantity.cost, Quantity.qty,
                    ".$this->db->dbprefix('convert').".noted AS Note,
                    ".$this->db->dbprefix('warehouses').".name as na,
                    " . $this->db->dbprefix('users') . ".username ", false)
            ->from('convert')
            ->join('users', 'users.id               = convert.created_by', 'left')
            ->join('warehouses', 'warehouses.id     = convert.warehouse_id', 'left')
            ->join($l_qty, ' Quantity.convert_id    = erp_convert.id', 'left')
            ->group_by('convert.id');
        }

         if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {
            $this->datatables->where('convert.created_by', $this->session->userdata('user_id'));
        }
            
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
    
    public function edit_convert($id = null)
    {
        $this->load->helper('security');
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        }
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $id_convert_item = 0;
        if ($this->form_validation->run() == true) {
            
            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld(trim($this->input->post('cdate')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            isCloseDate(date('Y-m-d', strtotime($date)));
            $convert_id         = $_POST['convert_id'];
            
            $warehouse_id       = $_POST['warehouse'];
            // list convert item from
            $cIterm_from_id     = $_POST['convert_from_items_id'];
            $cIterm_from_code   = $_POST['convert_from_items_code'];
            $cIterm_from_name   = $_POST['convert_from_items_name'];
            //$cIterm_from_uom    = $_POST['convert_to_items_uom'];
            $cIterm_from_uom    = $_POST['convert_from_items_uom'];
            $cIterm_from_qty    = $_POST['convert_from_items_qty'];
            // list convert item to
            $iterm_to_id        = $_POST['convert_to_items_id'];
            $iterm_to_code      = $_POST['convert_to_items_code'];
            $iterm_to_name      = $_POST['convert_to_items_name'];
            $iterm_to_uom = $_POST['convert_to_items_uom'];
            $iterm_to_qty       = $_POST['convert_to_items_qty'];

            $data               = array(
                                    'reference_no'  => $_POST['reference_no'],
                                    'date'          => $date,
                                    'warehouse_id'  => $_POST['warehouse'],
                                    'updated_by'    => $this->session->userdata('user_id'),
                                    'noted'         => $_POST['note'],
                                    'biller_id'     => $_POST['biller']
                                );  
                                
            $i                  = isset($_POST['convert_from_items_code']) ? sizeof($_POST['convert_from_items_code']) : 0;
            for($r = 0; $r < $i; $r++){
                if(!empty($cIterm_from_uom[$r])){
                    $product_variant= $this->site->getProductVariantByOptionID($cIterm_from_uom[$r]);
                }
                $unit_qty  = ( $product_variant ? $product_variant->qty_unit : 1 );
                $ware_qty  = $this->products_model->getProductQuantity($cIterm_from_id[$r], $warehouse_id);
                $con_item  = $this->products_model->getConvertItemsId($convert_id);
                
                $qty_input = 1;
                $real_qty  = 0;
                $con_qty   = 1;
                if($cIterm_from_qty[$r]){
                    $qty_input = $cIterm_from_qty[$r];
                }
                if ($con_item) {
                    if($con_item->quantity){
                        $con_qty = $con_item->quantity; 
                    }
                    $real_qty = $ware_qty['quantity'] + ($unit_qty * $con_qty);
                } else {
                    $real_qty = $ware_qty['quantity'];
                }
                
                //echo $real_qty .'='. $ware_qty['quantity'] .'<br/>';
                //====================== Check Quantity ===================//
                if($real_qty < ($unit_qty  * $qty_input) ){
                    $this->session->set_flashdata('error', $this->lang->line("quantity_is_valid"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                //=========================== End =========================//
            }
            
            $idConvert          = $this->products_model->updateConvert($convert_id, $data);
            $id_convert_item    = $idConvert;
            
            $items              = array();
            
            $cost_variant       = 0;
            $total_raw_cost     = 0;
            $total_fin_qty      = 0;
            $each_cost          = 0;
            
            for ($r = 0; $r < $i; $r++) {
                $products     = $this->site->getProductByID($cIterm_from_id[$r]);
                $convert_from = $this->products_model->getConvertItemsByIDPID($convert_id, $cIterm_from_id[$r]);
                $puchase_item = $this->products_model->get_purchase_items_by_conId($convert_from->id);
                $this->products_model->delete_purchase_items_by_conId($convert_from->id);
                $this->products_model->deleteConvert_itemsByPID($convert_id, $cIterm_from_id[$r]);
                $this->products_model->deleteConvert_itemsInventory_detail($convert_from->id);
                //======================= Check Variant ===================//
    
                if(!empty($cIterm_from_uom[$r])){
                    $product_variant= $this->site->getProductVariantByOptionID($cIterm_from_uom[$r]);
                }
                
                $unit_qty = ( $product_variant ? $product_variant->qty_unit : 1 );
                if($product_variant){
                    $cost_variant    = $products->cost * $unit_qty;
                    $total_raw_cost += $cost_variant * $cIterm_from_qty[$r];
                } else {
                    $cost_variant    = $products->cost;
                    $total_raw_cost += $cost_variant * $cIterm_from_qty[$r];
                }
                
                //=========================== End ========================//
                
                $qtytransfer = (-1) * ($unit_qty  * $cIterm_from_qty[$r]);

                $clause = array(
                    'purchase_id'   => NULL, 
                    'product_code'  => $cIterm_from_code[$r], 
                    'product_id'    => $cIterm_from_id[$r], 
                    'date'          => $date,
                    'warehouse_id'  => $warehouse_id
                );
                
                $conItem = array(
                    'convert_id'    => $convert_id,
                    'product_id'    => $cIterm_from_id[$r],
                    'product_code'  => $cIterm_from_code[$r],
                    'product_name'  => $cIterm_from_name[$r],
                    'quantity'      => $cIterm_from_qty[$r],
                    'option_id'     => $cIterm_from_uom[$r],
                    'cost'          => $cost_variant,
                    'status'        => 'deduct'
                );

                $this->db->insert('erp_convert_items', $conItem);
                $convert_item_id = $this->db->insert_id();
                
                //================= Add Value For Stock =====================//
                
                $clause['quantity']         = $qtytransfer;
                $clause['item_tax']         = 0;
                $clause['option_id']        = $cIterm_from_uom[$r];
                $clause['convert_id']       = $convert_id;
                $clause['product_name']     = $cIterm_from_name[$r];
                $clause['quantity_balance'] = $qtytransfer;
                $clause['transaction_type'] = 'CONVERT';
                $clause['cb_avg']           = $puchase_item->cb_avg;
                $clause['cb_qty']           = $puchase_item->cb_qty;
                $clause['transaction_id']   = $convert_item_id;
                $clause['status']           = 'received';
                
                $this->db->insert('purchase_items', $clause);
                
                //========================= End ============================//
                                                        
                $this->site->syncQuantity(NULL, NULL, NULL, $cIterm_from_id[$r]);
            }
            
            $j = isset($_POST['convert_to_items_code']) ? sizeof($_POST['convert_to_items_code']) : 0;
            
            //========================= Get Finish Qty ======================//
            for ($r = 0; $r < $j; $r++) {
                $option     = $this->site->getProductVariantByOptionID($iterm_to_uom[$r]);
                if($option){
                    $total_fin_qty  += $iterm_to_qty[$r] * $option->qty_unit;
                }else{
                    $total_fin_qty  += $iterm_to_qty[$r];
                }
            }
            //=============================== End ===========================//
            
            for ($r = 0; $r < $j; $r++) {
                
                $convert_from = $this->products_model->getConvertItemsByIDPID($convert_id, $iterm_to_id[$r]);
                $puchase_item = $this->products_model->get_purchase_items_by_conId($convert_from->id);
                
                //======================== Check Variant ========================//
                
                if(!empty($cIterm_from_uom[$r])){
                    $product_variant= $this->site->getProductVariantByOptionID($iterm_to_uom[$r]);
                }
                
                $unit_qty = ( $product_variant ? $product_variant->qty_unit : 1 );
    
                //============================ End ==============================//
                
                //========================== AVG Cost ===========================//
                if($product_variant){
                    $qty_items  = $iterm_to_qty[$r] * $product_variant->qty_unit;
                }else{
                    $qty_items  = $iterm_to_qty[$r];
                }
                
                $each_cost      = $this->site->editcalculateCONAVCost($iterm_to_id[$r], $total_raw_cost, $total_fin_qty, $qty_items, $convert_from->id);
                //============================ End ==============================//
                
                $this->products_model->delete_purchase_items_by_conId($convert_from->id);
                $this->products_model->deleteConvert_itemsByPID($convert_id, $iterm_to_id[$r]);
                $this->products_model->deleteConvert_itemsInventory_detail($convert_from->id);
                $qtytransfer = ($unit_qty  * $iterm_to_qty[$r]);
                
                $clause = array(
                    'purchase_id'   => NULL, 
                    'product_code'  => $iterm_to_code[$r], 
                    'product_id'    => $iterm_to_id[$r],
                    'date'          => $date,
                    'warehouse_id'  => $warehouse_id
                );
                
                $conItem        = array(
                    'convert_id'    => $convert_id,
                    'product_id'    => $iterm_to_id[$r],
                    'product_code'  => $iterm_to_code[$r],
                    'product_name'  => $iterm_to_name[$r],
                    'quantity'      => $iterm_to_qty[$r],
                    'option_id'     => $iterm_to_uom[$r],
                    'cost'          => $each_cost['cost'] / $iterm_to_qty[$r],
                    'status'        => 'add'
                );
                
                $this->db->insert('erp_convert_items', $conItem);
                $convert_item_id = $this->db->insert_id();
                            
                $clause['quantity']         = $qtytransfer;
                $clause['item_tax']         = 0;
                $clause['option_id']        = $iterm_to_uom[$r];
                $clause['convert_id']       = $convert_id;
                $clause['product_name']     = $iterm_to_name[$r];
                $clause['quantity_balance'] = $qtytransfer;
                $clause['transaction_type'] = 'CONVERT';
                $clause['transaction_id']   = $convert_item_id;
                $clause['status']           = 'received';
                $clause['cb_avg']           = $puchase_item->cb_avg;
                $clause['cb_qty']           = $puchase_item->cb_qty;
                
                $this->db->insert('purchase_items', $clause);
                
                $this->db->update('products', array('cost' => $each_cost['avg']), array('id' => $iterm_to_id[$r]));
                
                $this->site->syncQuantity(NULL, NULL, NULL, $iterm_to_id[$r]);
            }
            optimizeConvert(date('Y-m-d', strtotime($date)));
            $this->session->set_flashdata('message', lang("convert_updated"));
            redirect('products/list_convert');
        }
        
        $warehouse_id                       = $this->session->userdata('warehouse_id');
        $convert                            = $this->products_model->getConvertByID($id);
        $this->data['tax_rates']        = $this->site->getAllTaxRates();
        $this->data['warehouses']           = $this->site->getAllWarehouses();
        $this->data['warehouses_by_user']   = $this->products_model->getAllWarehousesByUser($warehouse_id);
        $this->data['convert']              = $convert;
        $this->data['convert_items']        = $this->products_model->getConvert_ItemByID($id, $convert->warehouse_id);
        $this->data['bom']                  = $this->products_model->getAllBoms();
        $this->data['billers']              = $this->site->getAllBiller();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('edit_convert')));
        $meta = array('page_title' => lang('edit_convert'), 'bc' => $bc);
        $this->page_construct('products/edit_convert', $meta, $this->data);
    }
    
    public function convert_actions()
    {
        // if (!$this->Owner || !$this->admin || !$this->GP['products-items_convert']) {
            // $this->session->set_flashdata('warning', lang('access_denied'));
            // redirect($_SERVER["HTTP_REFERER"]);
        // }

        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {
        
            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        $this->products_model->deleteConvert($id);
                        $this->products_model->deleteConvert_items($id);
                    }
                    $this->session->set_flashdata('message', $this->lang->line("convert_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {
                    $row = 2;
                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('convert'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('cost'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('quantity_convert'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('note'));
                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('warehouse'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('created_by'));
                    
                    $sum_cost = 0;
                    foreach ($_POST['val'] as $id) {
                        $converts = $this->products_model->getConvertByID($id);
                         
                        //Total sum of each value
                        $sum_cost += $converts->cost;
                        $user = $this->site->getUser($converts->created_by);
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($converts->Date));
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $converts->Reference." ");
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $this->erp->formatMoney($converts->cost));
                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $this->erp->formatMoneyPurchase($converts->qty));
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, strip_tags($converts->Note));
                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $converts->na);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $converts->username);
                        
                        $i = $row+1;
                        $this->excel->getActiveSheet()->SetCellValue('C' . $i, $this->erp->formatMoney($sum_cost));
                       
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
                    $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'convert_' . date('Y_m_d_H_i_s');
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php";
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
                        
                        //Add style bold text in case PDF
                        $this->excel->getActiveSheet()->getStyle('C'. $i. '')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('A1'.':G1')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('A1'.':G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
                        return $objWriter->save('php://output');
                    }
                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');
                        
                        //apply style border top and bold text in case excel
                        $this->excel->getActiveSheet()->getStyle('C'.$i. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('C'. $i. '')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('A1'.':G1')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('A1'.':G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
            } else {
                $this->session->set_flashdata('error', $this->lang->line("no_convert_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    
    function items_convert()
    {
        $this->erp->checkPermissions('items_convert', NULL, 'products');
        $this->form_validation->set_rules('biller', lang("biller"), 'required');
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required|is_unique[convert.reference_no]');

        $id_convert_item = 0;
        if ($this->form_validation->run() == true)
        {
            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld($_POST['sldate']);
            } else {
                $date = date('Y-m-d H:i:s');
            }
            isCloseDate(date('Y-m-d', strtotime($date)));
            $warehouse_id        = $_POST['warehouse'];
            // list convert item from
            $cIterm_from_code   = $_POST['convert_from_items_code'];
            $cIterm_from_name   = $_POST['convert_from_items_name'];
            $cIterm_from_uom    = $_POST['convert_from_items_uom'];
            $cIterm_from_qty    = $_POST['convert_from_items_qty'];
            $cIterm_from_id     = $_POST['convert_from_items_id'];

            // list convert item to
            $iterm_to_id        = $_POST['convert_to_items_id'];
            $iterm_to_code      = $_POST['convert_to_items_code'];
            $iterm_to_name      = $_POST['convert_to_items_name'];
            $iterm_to_uom       = $_POST['convert_to_items_uom'];
            $iterm_to_qty       = $_POST['convert_to_items_qty'];
            $reference_no       = $_POST['reference_no']?$_POST['reference_no']:$this->site->getReference('con', $_POST['biller']);
            $i                  = isset($_POST['convert_from_items_code']) ? sizeof($_POST['convert_from_items_code']) : 0;

            /**************************************/
            /* Check if qty on hand < qty convert
            ***************************************/
            for ($r = 0; $r < $i; $r++) {
                $product_variant= $this->site->getProductVariantByOptionID($cIterm_from_uom[$r]);
                $unit_qty       = ( !empty($product_variant->qty_unit) && $product_variant->qty_unit > 0 ? $product_variant->qty_unit : 1 );
                $ware_qty       = $this->products_model->getProductQuantity($cIterm_from_id[$r], $warehouse_id);
                if($ware_qty['quantity'] < ($unit_qty  * $cIterm_from_qty[$r]) ){
                    $this->session->set_flashdata('error', $this->lang->line("quantity_is_valid"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            }

            $data               = array(
                'reference_no'  => $reference_no,
                'date'          => $date,
                'warehouse_id'  => $_POST['warehouse'],
                'created_by'    => $this->session->userdata('user_id'),
                'noted'         => $_POST['note'],
                'bom_id'        => $_POST['bom_id'],
                'biller_id'     => $_POST['biller']
            );

            $idConvert          = $this->products_model->insertConvert($data);
            $id_convert_item    = $idConvert;
            
            $items              = array();
            $i                  = isset($_POST['convert_from_items_code']) ? sizeof($_POST['convert_from_items_code']) : 0;
            $qty_from           = '';
            $total_cost         = '';
            $cost_variant       = 0;
            $total_raw_cost     = 0;
            $total_fin_qty      = 0;
            $each_cost          = 0;
            
            for ($r = 0; $r < $i; $r++) {
                $qty_from       += $cIterm_from_qty[$r];
                $product_fr      = $this->site->getProductByID($cIterm_from_id[$r]);
                $total_cost     += ($cIterm_from_qty[$r] * $product_fr->cost);
                
                $ware_qty        = $this->products_model->getProductQuantity($cIterm_from_id[$r], $warehouse_id);
                
                //======================= Check Variant ===================//
                
//                if(!empty($cIterm_from_uom[$r])){
                    $product_variant= $this->site->getProductVariantByOptionID($cIterm_from_uom[$r]);
//                }
                
                $unit_qty     = ( !empty($product_variant->qty_unit) && $product_variant->qty_unit > 0 ? $product_variant->qty_unit : 1 );
                if($product_variant){
                    $cost_variant    = $product_fr->cost * $unit_qty;
                    $total_raw_cost += $cost_variant * $cIterm_from_qty[$r];
                } else {
                    $cost_variant    = $product_fr->cost;
                    $total_raw_cost += $cost_variant * $cIterm_from_qty[$r];
                }
                $qtytransfer = (-1) * ($unit_qty  * $cIterm_from_qty[$r]);

                $clause = array(
                    'purchase_id'   => NULL, 
                    'product_code'  => $cIterm_from_code[$r], 
                    'product_id'    => $cIterm_from_id[$r], 
                    'warehouse_id'  => $warehouse_id
                );
                
                $conItem = array(
                                'convert_id'    => $idConvert,
                                'product_id'    => $cIterm_from_id[$r],
                                'product_code'  => $cIterm_from_code[$r],
                                'product_name'  => $cIterm_from_name[$r],
                                'quantity'      => $cIterm_from_qty[$r],
                                'option_id'     => $cIterm_from_uom[$r],
                                'cost'          => $cost_variant,
                                'status'        => 'deduct'
                            );
                
                $this->db->insert('erp_convert_items', $conItem);
                $convert_item_id = $this->db->insert_id();
                
                //================= Add Value For Stock =====================//
                
                $clause['quantity']         = $qtytransfer;
                $clause['item_tax']         = 0;
                $clause['date']             = date('Y-m-d');
                $clause['option_id']        = $cIterm_from_uom[$r];
                $clause['convert_id']       = $id_convert_item;
                $clause['product_name']     = $cIterm_from_name[$r];
                $clause['quantity_balance'] = $qtytransfer;
                $clause['cb_avg']           = $product_fr->cost;
                $clause['cb_qty']           = $product_fr->quantity;
                $clause['transaction_type'] = 'CONVERT';
                $clause['transaction_id']   = $convert_item_id;
                $clause['status']           = 'received';
                
                $this->db->insert('purchase_items', $clause);
                
                //========================= End ============================//
                            
                $this->site->syncQuantity(NULL, NULL, NULL, $cIterm_from_id[$r]);
            }
            
            $j = isset($_POST['convert_to_items_code']) ? sizeof($_POST['convert_to_items_code']) : 0;
            
            //========================= Get Finish Qty ======================//
            for ($r = 0; $r < $j; $r++) {
                $option     = $this->site->getProductVariantByOptionID($iterm_to_uom[$r]);
                if($option){
                    $total_fin_qty  += $iterm_to_qty[$r] * $option->qty_unit;
                }else{
                    $total_fin_qty  += $iterm_to_qty[$r];
                }
            }
            //=============================== End ===========================//
            
            for ($r = 0; $r < $j; $r++) {
                $products = $this->site->getProductByID($iterm_to_id[$r]);
                //======================== Check Variant ========================//
                if(!empty($iterm_to_uom[$r])){
                    $product_variant   = $this->site->getProductVariantByOptionID($iterm_to_uom[$r]);
                }
                
                $unit_qty = ( !empty($iterm_to_uom[$r]) ? $product_variant->qty_unit : 1 );
                
                //============================ End ==============================//
                
                //========================== AVG Cost ===========================//
                if(!empty($iterm_to_uom[$r])){
                    $qty_items  = $iterm_to_qty[$r] * $product_variant->qty_unit;
                }else{
                    $qty_items  = $iterm_to_qty[$r];
                }
                
                $each_cost      = $this->site->calculateCONAVCost($iterm_to_id[$r], $total_raw_cost, $total_fin_qty, $qty_items);
                //============================= End =============================//
                $qtytransfer    = ($unit_qty  * $iterm_to_qty[$r]);

                $clause         = array(
                    'purchase_id'   => NULL, 
                    'product_code'  => $iterm_to_code[$r], 
                    'product_id'    => $iterm_to_id[$r], 
                    'warehouse_id'  => $warehouse_id
                );
                
                $conItem        = array(
                    'convert_id'    => $idConvert,
                    'product_id'    => $iterm_to_id[$r],
                    'product_code'  => $iterm_to_code[$r],
                    'product_name'  => $iterm_to_name[$r],
                    'quantity'      => $iterm_to_qty[$r],
                    'option_id'     => $iterm_to_uom[$r],
                    'cost'          => $each_cost['cost'] / $iterm_to_qty[$r],
                    'status'        => 'add'
                );
                
                $this->db->insert('erp_convert_items', $conItem);
                $convertitem_id = $this->db->insert_id();
                
                $clause['quantity']         = $qtytransfer;
                $clause['item_tax']         = 0;
                $clause['date']             = date('Y-m-d');
                $clause['option_id']        = $iterm_to_uom[$r];
                $clause['convert_id']       = $id_convert_item;
                $clause['product_name']     = $iterm_to_name[$r];
                $clause['quantity_balance'] = $qtytransfer;
                $clause['transaction_type'] = 'CONVERT';
                $clause['transaction_id']   = $convertitem_id;
                $clause['status']           = 'received';
                $clause['cb_avg']           = $products->cost;
                $clause['cb_qty']           = $products->quantity;
                $this->db->insert('purchase_items', $clause);

                $this->db->update('products', array('cost' => $each_cost['avg']), array('id' => $iterm_to_id[$r]));
                
                $this->site->syncQuantity(NULL, NULL, NULL, $iterm_to_id[$r]);
            }
            optimizeConvert(date('Y-m-d', strtotime($date)));

            $this->session->set_flashdata('message', lang("convert_success"));
            redirect('products/items_convert');
        }else{
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            
            $reference = $this->products_model->getReference();
            if ($reference != NULL) {
                foreach ($reference as $reference_no) {
                    if ($this->site->getReference('con') == $reference_no->reference_no) {
                        $this->site->updateReference('con');
                    }
                }
            }
            
            if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) {
                $biller_id = $this->site->get_setting()->default_biller;
                $this->data['biller_id'] = $biller_id;
                $this->data['conumber'] = $this->site->getReference('con',$biller_id);
            } else {
                $biller_id = $this->session->userdata('biller_id');
                $this->data['biller_id'] = $biller_id;
                $this->data['conumber'] = $this->site->getReference('con',$biller_id);
            }           
            
            $warehouse_id = $this->session->userdata('warehouse_id');
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouses_by_user'] = $this->products_model->getAllWarehousesByUser($warehouse_id);
            $this->data['tax_rates'] = $this->site->getAllTaxRates();
            $this->data['bom'] = $this->products_model->getAllBoms();
            $this->data['billers'] = $this->site->getAllBiller();

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('products')));
            $meta = array('page_title' => lang('convert_product'), 'bc' => $bc);
            $this->page_construct('products/items_convert', $meta, $this->data);
        }
    }
    
    public function testConvert($convert_id, $qty_to, $qty_from)
    {
        $r = $this->site->calculateCONAVCost($convert_id, $qty_to, $qty_from);
        echo 'Average Cost Convert' . $r;
    }
    
    public function return_products($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('return_list', true, 'products');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
        } else {
            $user = $this->site->getUser();
            $this->data['warehouses'] = NULL;
            $this->data['warehouse_id'] = $user->warehouse_id;
            $this->data['warehouse'] = $user->warehouse_id ? $this->site->getWarehouseByID($user->warehouse_id) : NULL;
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('return_products')));
        $meta = array('page_title' => lang('return_products'), 'bc' => $bc);
        $this->page_construct('products/return_products', $meta, $this->data);
    }

    public function getReturns($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('return_products');
        
        if ($this->input->get('user')) {
            $user_query = $this->input->get('user');
        } else {
            $user_query = NULL;
        }
        if ($this->input->get('reference_no')) {
            $reference_no = $this->input->get('reference_no');
        } else {
            $reference_no = NULL;
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
            $end_date = $this->erp->fld($end_date);
        }

        if ((! $this->Owner || ! $this->Admin) && ! $warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        
        
        if (!$this->Owner && !$warehouse_id) {
            $user = $this->site->getUser();
            $warehouse_id = $user->warehouse_id;
        }
        $detail_link = anchor('sales/view/$1', '<i class="fa fa-file-text-o"></i>');
        $edit_link = ''; //anchor('sales/edit/$1', '<i class="fa fa-edit"></i>', 'class="reedit"');
        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_return_sale") . "</b>' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('sales/delete_return/$1') . "'>"
            . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a>";
        $action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $delete_link . '</div>';
        //$action = '<div class="text-center">' . $detail_link . ' ' . $edit_link . ' ' . $email_link . ' ' . $delete_link . '</div>';

        $this->load->library('datatables');
        if ($warehouse_id) {
            $this->datatables
                ->select($this->db->dbprefix('return_sales') . ".date as date, " . $this->db->dbprefix('sales') . ".reference_no as ref, ABS(" . $this->db->dbprefix('return_items') . ".quantity) as qty, " . $this->db->dbprefix('return_sales') . ".biller, " . $this->db->dbprefix('return_sales') . ".customer, " . $this->db->dbprefix('users') . ".username, " . $this->db->dbprefix('return_sales') . ".id as id")
                ->join('sales', 'sales.id=return_sales.sale_id', 'left')
                ->join('return_items', 'return_items.return_id = return_sales.id', 'left')
                ->join('users', 'users.id = return_sales.created_by', 'left')
                ->from('return_sales')
                ->group_by('return_sales.id')
                ->where('return_sales.warehouse_id', $warehouse_id);
        } else {
            $this->datatables
                ->select($this->db->dbprefix('return_sales') . ".date as date, " . $this->db->dbprefix('sales') . ".reference_no as ref, ABS(" . $this->db->dbprefix('return_items') . ".quantity) as qty, " . $this->db->dbprefix('return_sales') . ".biller, " . $this->db->dbprefix('return_sales') . ".customer, " . $this->db->dbprefix('users') . ".username, " . $this->db->dbprefix('return_sales') . ".id as id")
                ->join('sales', 'sales.id=return_sales.sale_id', 'left')
                ->join('return_items', 'return_items.return_id = return_sales.id','left')
                ->join('users', 'users.id = return_sales.created_by', 'left')
                ->from('return_sales')
                ->group_by('return_sales.id');
        }
        if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin) {
            $this->datatables->where('return_sales.created_by', $this->session->userdata('user_id'));
        } elseif ($this->Customer) {
            $this->datatables->where('return_sales.customer_id', $this->session->userdata('customer_id'));
        }
        
        if ($user_query) {
            $this->datatables->where('sales.created_by', $user_query);
        }/*
        if ($customer) {
            $this->datatables->where('sales.id', $customer);
        }*/
        if ($reference_no) {
            $this->datatables->where('sales.reference_no', $reference_no);
        }
        if ($biller) {
            $this->datatables->where('sales.biller_id', $biller);
        }
        if ($customer) {
            $this->datatables->where('sales.customer_id', $customer);
        }
        if ($warehouse) {
            $this->datatables->where('sales.warehouse_id', $warehouse);
        }

        if ($start_date) {
            $this->datatables->where($this->db->dbprefix('return_sales').'.date BETWEEN "' . $start_date . '" AND "' . $end_date . '"');
        }
        
        $this->datatables->add_column("Actions", $action, "id");
        echo $this->datatables->generate();
    }
    
    public function getDatabyBom_id()
    {
        $id             = $this->input->get('term', TRUE);
        $warehouse_id   = $this->input->get('warehouse_id', TRUE);
        $result = $this->products_model->getAllBom_id($id, $warehouse_id);
        if ($result) {
            $uom = array();
            foreach ($result as $row) {
                $options = $this->products_model->getProductOptions($row->product_id);
                
                $pr[] = array('row' => $row, 'variant' => $options );
            }
            //echo '<pre>';print_r($pr);echo '</pre>';
            echo json_encode($pr);
        };
        //echo json_encode($result);
    }
    
    public function product_serial()
    {
        $this->erp->checkPermissions('adjustments');

        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $data['warehouses'] = $this->site->getAllWarehouses();

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('product_serial')));
        $meta = array('page_title' => lang('product_serial'), 'bc' => $bc);
        $this->page_construct('products/product_serial', $meta, $this->data);
    }

    public function getProductSerial($pdf = NULL, $xls = NULL)
    {
        $this->erp->checkPermissions('adjustments');

        $product = $this->input->get('product') ? $this->input->get('product') : NULL;

        if ($pdf || $xls) {

            $this->db
                ->select($this->db->dbprefix('adjustments') . ".id as did, " . $this->db->dbprefix('adjustments') . ".product_id as productid, " . $this->db->dbprefix('adjustments') . ".date as date, " . $this->db->dbprefix('products') . ".image as image, " . $this->db->dbprefix('products') . ".code as code, " . $this->db->dbprefix('products') . ".name as pname, " . $this->db->dbprefix('product_variants') . ".name as vname, " . $this->db->dbprefix('adjustments') . ".quantity as quantity, ".$this->db->dbprefix('adjustments') . ".type, " . $this->db->dbprefix('warehouses') . ".name as wh");
            $this->db->from('adjustments');
            $this->db->join('products', 'products.id=adjustments.product_id', 'left');
            $this->db->join('product_variants', 'product_variants.id=adjustments.option_id', 'left');
            $this->db->join('warehouses', 'warehouses.id=adjustments.warehouse_id', 'left');
            $this->db->group_by("adjustments.id")->order_by('adjustments.date desc');
            if ($product) {
                $this->db->where('adjustments.product_id', $product);
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
                $this->excel->getActiveSheet()->setTitle(lang('quantity_adjustments'));
                $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
                $this->excel->getActiveSheet()->SetCellValue('B1', lang('product_code'));
                $this->excel->getActiveSheet()->SetCellValue('C1', lang('product_name'));
                $this->excel->getActiveSheet()->SetCellValue('D1', lang('product_variant'));
                $this->excel->getActiveSheet()->SetCellValue('E1', lang('quantity'));
                $this->excel->getActiveSheet()->SetCellValue('F1', lang('type'));
                $this->excel->getActiveSheet()->SetCellValue('G1', lang('warehouse'));

                $row = 2;
                foreach ($data as $data_row) {
                    $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($data_row->date));
                    $this->excel->getActiveSheet()->SetCellValue('B' . $row, $data_row->code);
                    $this->excel->getActiveSheet()->SetCellValue('C' . $row, $data_row->pname);
                    $this->excel->getActiveSheet()->SetCellValue('D' . $row, $data_row->vname);
                    $this->excel->getActiveSheet()->SetCellValue('E' . $row, $data_row->quantity);
                    $this->excel->getActiveSheet()->SetCellValue('F' . $row, lang($data_row->type));
                    $this->excel->getActiveSheet()->SetCellValue('G' . $row, $data_row->wh);
                    $row++;
                }

                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $filename = lang('quantity_adjustments');
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
                    $objWriter->save('php://output');
                    exit();
                }
                if ($xls) {
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

            $delete_link = "<a href='#' class='tip po' title='<b>" . $this->lang->line("delete_adjustment") . "</b>' data-content=\"<p>"
                . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' id='a__$1' href='" . site_url('products/delete_adjustment/$2') . "'>"
                . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a>";

            $this->load->library('datatables');
            $this->datatables
                ->select($this->db->dbprefix('products') . ".code as code, " . $this->db->dbprefix('products') . ".name as name, " . $this->db->dbprefix('companies') . ".company as company, " . $this->db->dbprefix('warehouses') . ".name as warehouse, " . $this->db->dbprefix('serial') . ".serial_number as number, ".$this->db->dbprefix('serial') . ".serial_status as sstatus");
            $this->datatables->from('serial');
            $this->datatables->join('products', 'products.id=serial.product_id', 'left');
            $this->datatables->join('companies', 'companies.id=serial.biller_id', 'left');
            $this->datatables->join('warehouses', 'warehouses.id=serial.warehouse', 'left');
            $this->datatables->add_column("Actions", "<div class='text-center'><a href='" . site_url('products/edit_adjustment/$1/$2') . "' class='tip' title='" . lang("edit_adjustment") . "' data-toggle='modal' data-target='#myModal'><i class='fa fa-edit'></i></a> " . $delete_link . "</div>", "did");
            if ($product) {
                $this->datatables->where('serial.product_id', $product);
            }

            echo $this->datatables->generate();

        }

    }
    
    public function getReasons($position_id = NULL)
    {
        if ($rows = $this->products_model->getReasonsForPositionID($position_id)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        
        echo $data;
    }
    
    public function enter_using_stock($purchase_id = null, $id = NULL)
    {
        $this->erp->checkPermissions('adjustments');
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $AllUsers    = $this->site->getAllUsers();
        $CurrentUser = $this->site->getUser();
        $setting     = $this->site->get_setting();
        $biller      = $this->site->getAllBiller();
        $employee    = $this->site->getAllEmployee();
        $all_unit    = $this->site->getUnits();
        $product     = $this->products_model->getProductName_code();
        $getExpense  = $this->products_model->getAllExpenseCategory();
        $getGLChart  = $this->products_model->getGLChart();
        $this->data['getExpense'] = $getExpense;
        $this->data['getGLChart'] = $getGLChart; 
        $this->data['AllUsers'] = $AllUsers; 
        $this->data['CurrentUser'] = $CurrentUser; 
        $this->data['setting'] = $setting; 
        $this->data['biller'] = $biller; 
        $this->data['all_unit'] = $all_unit; 
        $this->data['employees'] = $employee; 
        $this->data['product'] = $product; 
        $this->data['productJSON'] = json_encode($product); 
        //$this->data['reference'] = $this->site->getReference('es');
        
        if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) {
            $biller_id = $this->site->get_setting()->default_biller;
            $this->data['biller_id'] = $biller_id;
            $this->data['reference'] = $this->site->getReference('es',$biller_id);
        } else {
            $biller_id = $this->session->userdata('biller_id');
            $this->data['biller_id'] = $biller_id;
            $this->data['reference'] = $this->site->getReference('es',$biller_id);
        }
        
        if($purchase_id){
            $this->data['items'] = $this->products_model->getPurcahseItemByPurchaseID($purchase_id);
            $this->data['purchase']       = $this->products_model->getPurchaseByID($purchase_id);
        }
        $this->data['modal_js'] = $this->site->modal_js();
        $this->data['positions'] = $this->products_model->getAllPositionData();
        
        //$this->data['reason'] = $id ? $this->products_model->getReasonByPosID($id) : NULL;
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('enter_using_stock')));
        $meta = array('page_title' => lang('enter_using_stock'), 'bc' => $bc);
        $this->page_construct('products/enter_using_stock', $meta, $this->data);
    }
    
    public function using_stock($purchase_id = null, $id = NULL)
    {
        $this->erp->checkPermissions('adjustments');
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $AllUsers    = $this->site->getAllUsers();
        $CurrentUser = $this->site->getUser();
        $setting     = $this->site->get_setting();
        $biller      = $this->site->getAllBiller();
        $employee    = $this->site->getAllEmployee();
        $all_unit    = $this->site->getUnits();
        $product     = $this->products_model->getProductName_code();
        $getExpense  = $this->products_model->getAllExpenseCategory();
        //$getGLChart  = $this->products_model->getAllChartAccountIn('11,50');
        $getGLChart  = $this->products_model->getGLChart();
        
        $this->data['getExpense']   = $getExpense;
        $this->data['getGLChart']   = $getGLChart; 
        $this->data['AllUsers']     = $AllUsers; 
        $this->data['CurrentUser']  = $CurrentUser; 
        $this->data['setting']      = $setting; 
        $this->data['biller']       = $biller; 
        $this->data['all_unit']     = $all_unit; 
        $this->data['employees']    = $employee; 
        $this->data['product']      = $product; 
        $this->data['productJSON']  = json_encode($product);

        $this->data['setting'] = $this->site->get_setting();
        if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')){
            $biller_id = $this->site->get_setting()->default_biller;
            $this->data['reference'] = $this->site->getReference('es',$biller_id);

        }else{
            $biller_id = $this->session->userdata('biller_id');
            $this->data['reference'] = $this->site->getReference('es',$biller_id);
        }
        
        if($purchase_id){
            $this->data['items']    = $this->products_model->getPurcahseItemByPurchaseID($purchase_id);
            $this->data['purchase'] = $this->products_model->getPurchaseByID($purchase_id);
        }
        $warehouse_id = $this->session->userdata('warehouse_id');
        $this->data['plan']         = $this->products_model->getPlan();
        $this->data['modal_js']     = $this->site->modal_js();
        $this->data['positions']    = $this->products_model->getAllPositionData();
        $this->data['warehouses_by_user'] = $this->products_model->getAllWarehousesByUser($warehouse_id);

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('add_product_using')));
        $meta = array('page_title' => lang('add_product_using'), 'bc' => $bc);
        $this->page_construct('products/using_stock', $meta, $this->data);
    }
    
    public function checkPasswords()
    {
        $password = $_REQUEST['password'];
        $oapass = $this->products_model->getPasswordOA();
        $this->load->model('auth_model');
        $this->load->library('ion_auth');
        $result = array();
        foreach($oapass as $user){
            if( $this->ion_auth->hash_password_db($user->id, $password) ){
                $result[] = 1;
            } else {
                $result[] = 0;
            }
        }
        echo json_encode($result);
    }
    
    public function getProductByWarehouses()
    {
        $product=array();
        $w_id = $this->input->get('w_id');
        $product = $this->products_model->getProductName_code($w_id);
        echo json_encode($product);
    }
    
    public function add_enter_using_stock()
    {
        $date            = $this->erp->fld(trim($this->input->post('date')));
        $reference_no    = $this->input->post('reference_no');
        $warehouse_id    = $this->input->post('from_location');
        $authorize_id    = $this->input->post('authorize_id');
        $employee_id     = $this->input->post('employee_id');
        $shop            = $this->input->post('shop');
        $account         = $this->input->post('account');
        $note            = $this->input->post('note');
        $cost            = $this->input->post('cost');
        $ref_prefix      = $this->input->post('ref_prefix');
        $item_code_arr   = $this->input->post('item_code');
        $description_arr = $this->input->post('description');
        $reason_arr      = $this->input->post('reason');
        $qty_use_arr     = $this->input->post('qty_use');
        $unit_arr        = $this->input->post('unit');
        $wh_id_arr       = $this->input->post('from_location');
        $qty_arr         = $this->input->post('qty_use');
        $exp_cate_id     = $this->input->post('exp_catid');
        $total_item_cost = 0;
        $i               = 0;
        
        foreach($item_code_arr as $item_code){
            
            $unit_of_measure = $this->products_model->getUnitOfMeasureByProductCode($item_code_arr[$i],$unit_arr[$i]);          
            $variant = $this->db->select("products.*,
                                    qty_unit as measure_qty, 
                                    product_variants.name as description")
                                    ->from("products")
                                    ->where( 
                                        array(
                                            "products.code"=>$item_code_arr[$i],
                                            "product_variants.name" =>$unit_arr[$i]
                                            )
                                        )
                                    ->join("product_variants","products.id = product_variants.product_id","left")
                                    ->get();
            if($variant->num_rows() > 0 && $variant->row()->description != null){
                $unit_of_measure = $variant->row();
            }
            
            $convert_qty = $qty_use_arr[$i] * $unit_of_measure->measure_qty;
            $total_cost  = ($cost[$i] * $convert_qty);
            $total_item_cost += $this->erp->formatDecimal($total_cost);         
            $i++;
        }

        $CurrentUser=$this->site->getUser();
        $data = array(
            'date'          => $date,
            'reference_no'  => $reference_no,
            'warehouse_id'  => $warehouse_id,
            'authorize_id'  => $authorize_id,
            'employee_id'   => $employee_id,
            'shop'          => $shop,
            'account'       => $account,
            'note'          => $note,
            'create_by'     => $CurrentUser->id,
            'type'          => 'use',
            'total_cost'    => $total_item_cost,
        );
        
        $insert_enter_using_stock = $this->products_model->insert_enter_using_stock($data,$ref_prefix);
        $i = 0;
        foreach($item_code_arr as $item_code){
            
            $unit_of_measure = $this->products_model->getUnitOfMeasureByProductCode($item_code_arr[$i],$unit_arr[$i]);
            
            $variant = $this->db->select("products.*,
                                    qty_unit as measure_qty, 
                                    product_variants.id as option_id,
                                    product_variants.name as description")
                                    ->from("products")
                                    ->where( 
                                        array(
                                            "products.code"=>$item_code_arr[$i],
                                            "product_variants.name" =>$unit_arr[$i]
                                            )
                                        )
                                    ->join("product_variants","products.id=product_variants.product_id","left")
                                    ->get();
                                    
            if($variant->num_rows() > 0 && $variant->row()->description != null){
                $unit_of_measure = $variant->row();
            }
            $option_id = $unit_of_measure->option_id;
            $convert_qty = $qty_use_arr[$i] * $unit_of_measure->measure_qty;
            $item_data = array(
                'code'          => $item_code_arr[$i],
                'description'   => $description_arr[$i],
                'reason'        => $reason_arr[$i],
                'qty_use'       => $convert_qty,
                'qty_by_unit'   => $qty_use_arr[$i],
                'unit'          => $unit_arr[$i],
                'warehouse_id'  => $wh_id_arr,
                'cost'          => $cost[$i],
                'reference_no'  => $reference_no,
                'exp_cate_id'   => $exp_cate_id[$i],
                'option_id'     => $option_id
            );
                
            $insert_enter_using_stock_item = $this->products_model->insert_enter_using_stock_item($item_data);

            if($insert_enter_using_stock_item){
                
                $product        = $this->products_model->getProductQtyByCode($item_code_arr[$i]);
                $product_id     = $product->id;
                $product_code   = $product->code;
                $product_name   = $product->name;
                $net_unit_cost  = $product->price;
                $pr_item=null;
                $pur_data = array(
                    'product_id'        => $product_id,
                    'product_code'      => $product_code,
                    'product_name'      => $product_name,
                    'net_unit_cost'     => $product->cost,
                    'option_id'         => $unit_of_measure->id,
                    'quantity'          => -1 * abs($convert_qty),
                    'reference'         => $reference_no,
                    'warehouse_id'      => $wh_id_arr,
                    'subtotal'          => $pr_item->subtotal?$pr_item->subtotal:0,
                    'date'              => $date,
                    'status'            => 'received',
                    'net_unit_cost'     => $net_unit_cost,
                    'quantity_balance'  => -1 * abs($convert_qty),
                    'transaction_type'  => 'USING STOCK',
                    'transaction_id'    => $insert_enter_using_stock_item,
                );  
                $this->db->insert('purchase_items', $pur_data);
                $product_cost = $this->site->getProductByID($product_id);
                $this->db->update("inventory_valuation_details",array('cost'=>$product_cost->cost,'avg_cost'=>$product_cost->cost),array('field_id'=>$insert_enter_using_stock_item));
                $this->site->syncQuantitys(null,null,null,$product_id);
            }
            $i++;   
        }       
        if($insert_enter_using_stock_item && $insert_enter_using_stock){
            $this->session->set_flashdata(lang('enter_using_stock_added.'));
                $r_r=str_replace("/","-",$reference_no);
            redirect('products/print_enter_using_stock/'.$r_r);
        }else{
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', $error);
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    
    public function add_using_stock()
    {
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required|is_unique[enter_using_stock.reference_no]');
        $this->form_validation->set_rules('account', lang("account"), 'required');
        $this->form_validation->set_rules('from_location', lang("from_location"), 'required');
        if ($this->form_validation->run() == true){
            if($this->Owner || $this->Admin){
                $date       = $this->erp->fld($this->input->post('date'));
            } else {
                $date       = date('Y-m-d');
            }
            isCloseDate(date('Y-m-d', strtotime($date)));
            $authorize      = $this->input->post('authorize_id');
            $account        = $this->input->post('account');
            $reference_no   = $this->input->post('reference_no');
            $employee_id    = $this->input->post('employee_id');
            $customer_id    = $this->input->post('customer');
            $plan           = $this->input->post('plan');
            $address        = $this->input->post('address');
            $warehouse_id   = $this->input->post('from_location');
            $shop           = $this->input->post('shop');
            $note           = $this->input->post('note');
            $total_item_cost= 0;

            $i              = sizeof($_POST['product_id']);
            for ($r = 0; $r < $i; $r++) {
                $product_id     = $_POST['product_id'][$r];
                $product_code   = $_POST['item_code'][$r];
                $product_name   = $_POST['name'][$r];
                $product_cost   = $_POST['cost'][$r];
                $description    = $_POST['description'][$r];
                $qty_use        = $_POST['qty_use'][$r];
                $unit           = $_POST['unit'][$r];
                $exp            = $_POST['exp'][$r];
                $qty_balance    = $qty_use;
                $option_id      = '';
                $total_cost     = $product_cost * $qty_balance; 
                
                $variant        = $this->site->getProductVariantByID($product_id, $unit);
                if ($variant) {
                    $qty_balance    = $qty_use * $variant->qty_unit;
                    $option_id      = $variant->id;
                    $total_cost     = $product_cost * $qty_balance;
                        
                }
                
                //======================= Check Stock ========================//
                $warehouse      = $this->site->getWarehouseQty($product_id, $warehouse_id);
                if($warehouse->quantity < $qty_balance OR $qty_balance == 0){
                    $this->session->set_flashdata('error', $this->lang->line("quantity_bigger") );
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                //=========================== End ============================//
                
                //================== Check Stock With Expiry =================//
                if ($this->Settings->product_expiry) {
                    $stock_expiry = $this->site->checkExpiryDate($product_id, $exp, $warehouse_id);
                    if($stock_expiry->expiry_qty < $qty_balance){
                        $this->session->set_flashdata('error', $this->lang->line("expiry_date_bigger") );
                        redirect($_SERVER["HTTP_REFERER"]);
                    }
                }
                //============================= End ==========================//

                $item_data[] = array(
                    'product_id'    => $product_id,
                    'code'          => $product_code,
                    'product_name'  => $product_name,
                    'description'   => $description,
                    'qty_use'       => $qty_balance,
                    'qty_by_unit'   => $qty_use,
                    'unit'          => $unit,
                    'expiry'        => $exp,
                    'warehouse_id'  => $warehouse_id,
                    'cost'          => $product_cost,
                    'reference_no'  => $reference_no,
                    'option_id'     => $option_id
                );
                
                $total_item_cost+= $total_cost;
            }
            
            if (empty($item_data)) {
                $this->session->set_flashdata('error', $this->lang->line("no_data_select") );
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                krsort($item_data);
            }
            
            $data = array(
                'date'          => $date,
                'reference_no'  => $reference_no,
                'warehouse_id'  => $warehouse_id,
                'authorize_id'  => $authorize,
                'employee_id'   => $employee_id,
                'customer_id'   => $customer_id,
                'shop'          => $shop,
                'account'       => $account,
                'note'          => $note,
                'create_by'     => $this->session->userdata('user_id'),
                'type'          => 'use',
                'total_cost'    => $total_item_cost,
                'plan_id'       => $plan,
                'address_id'    => $address,
            );
            
            //$this->erp->print_arrays($data, $item_data);
            $using_stock = $this->products_model->insert_enter_using_stock($data, $item_data);
            optimizeUsing(date('Y-m-d', strtotime($date)));
            if($using_stock){
                $this->session->set_flashdata(lang('enter_using_stock_added.'));

                $r_r = str_replace("/","-",$reference_no);
                $ref = str_replace("&","_",$r_r);

                $this->session->set_userdata('remove_usitem', '1');
                redirect('products/print_using_stock/' . $ref);
            }
            
        } else {
            $this->session->set_flashdata('error', validation_errors());
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    
    public function getUnitOfMeasureByProductCode()
    {
        $code = $this->input->get('product_code', TRUE);        
        $variant = $this->db->select("products.*, 
                                    '1' as measure_qty, 
                                    product_variants.name as description")
                                    ->from("products")
                                    ->where("products.code",$code)                                                                      
                                    ->join("product_variants","products.id=product_variants.product_id","left")
                                    ->get();                    
        $unit_of_measure = $this->products_model->getUnitOfMeasureByProductCode($code);
        if($variant->num_rows() > 0 && $variant->row()->description != null){
            echo json_encode($variant->result());
        }else{
            echo json_encode($unit_of_measure);
        }           
    }
    
    public function print_enter_using_stock($ref)
    {
        $r_r            = str_replace("-","/",$ref);
        $using_stock    = $this->products_model->get_enter_using_stock_by_ref($r_r);
        $stock_item     = $this->products_model->get_enter_using_stock_item_by_ref($r_r);
        $biller         = $this->site->getCompanyByID($using_stock->shop);
        $this->data['using_stock'] = $using_stock; 
        $this->data['stock_item']  = $stock_item; 
        $this->data['biller']      = $biller;
        $this->load->view($this->theme.'products/print_enter_using_stock',$this->data);
    }
    
    public function print_using_stock($r_r)
    {
        $r_r = str_replace('-', '/', $r_r);

        $using_stock    =   $this->products_model->get_enter_using_stock_by_ref($r_r);
        $stock_item     =   $this->products_model->get_enter_using_stock_item_by_ref($r_r);
        $this->data['info'] = $this->products_model->get_enter_using_stock_info();
        $this->data['using_stock'] = $using_stock; 
        $this->data['stock_item'] = $stock_item;
        $this->data['biller'] = $this->products_model->getUsingStockProjectByRef($r_r);
        $this->data['invs'] = $this->products_model->getUsingStockProjectByRef($r_r);
        $this->data['au_info'] =$this->products_model->getAuInfoByref($r_r);

        $this->load->view($this->theme.'products/print_using_stock',$this->data);
    }
    
    public function view_using_stock()
    {
        $this->erp->checkPermissions('list_using_stock', true, 'products');
        
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $AllUsers=$this->site->getAllUsers();
        $CurrentUser=$this->site->getUser();
        $setting=$this->site->get_setting();
        $biller=$this->site->getAllBiller();
        $employee=$this->site->getAllEmployee();
        $all_unit=$this->site->getUnits();
        $product=$this->products_model->getProductName_code();
        $getGLChart=$this->products_model->getGLChart();
        $this->data['getGLChart'] = $getGLChart; 
        $this->data['AllUsers'] = $AllUsers; 
        $this->data['CurrentUser'] = $CurrentUser; 
        $this->data['setting'] = $setting; 
        $this->data['biller'] = $biller; 
        $this->data['all_unit'] = $all_unit; 
        $this->data['employees'] = $employee; 
        $this->data['product'] = $product; 
        $this->data['productJSON'] = json_encode($product); 
        $this->data['reference'] = $this->site->getReference('es');
        
        $this->data['modal_js'] = $this->site->modal_js();
        
        $this->data['enter_using_stock']=$this->products_model->getReferno();
        
        $this->data['empno']=$this->products_model->getEmpno();
        
         $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('enter_using_stock')));
        $meta = array('page_title' => lang('enter_using_stock'), 'bc' => $bc);
        $this->page_construct('products/view_using_stock', $meta,$this->data);
        
    }
    
    public function view_enter_using_stock()
    {
        $this->erp->checkPermissions('list_using_stock', true, 'products');
        
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $AllUsers=$this->site->getAllUsers();
        $CurrentUser=$this->site->getUser();
        $setting=$this->site->get_setting();
        $biller=$this->site->getAllBiller();
        $employee=$this->site->getAllEmployee();
        $all_unit=$this->site->getUnits();
        $product=$this->products_model->getProductName_code();
        $getGLChart=$this->products_model->getGLChart();
        $this->data['getGLChart'] = $getGLChart; 
        $this->data['AllUsers'] = $AllUsers; 
        $this->data['CurrentUser'] = $CurrentUser; 
        $this->data['setting'] = $setting; 
        $this->data['biller'] = $biller; 
        $this->data['all_unit'] = $all_unit; 
        $this->data['employees'] = $employee; 
        $this->data['product'] = $product; 
        $this->data['productJSON'] = json_encode($product); 
        $this->data['reference'] = $this->site->getReference('es');
        
        $this->data['modal_js'] = $this->site->modal_js();
        
        $this->data['enter_using_stock']=$this->products_model->getReferno();
        
        $this->data['empno']=$this->products_model->getEmpno();
        $this->data['plans']=$this->products_model->getPlan();

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('product_using_list')));
        $meta = array('page_title' => lang('product_using'), 'bc' => $bc);
        $this->page_construct('products/view_enter_using_stock', $meta,$this->data);
        
    }
    
    public function datatable_using_stock()
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
            ->select("erp_enter_using_stock.id as id,erp_enter_using_stock.date,erp_enter_using_stock.reference_no as refno,
            erp_companies.company,erp_warehouses.name as warehouse_name,erp_users.username,erp_enter_using_stock.note,
            erp_enter_using_stock.type as type,erp_enter_using_stock.total_cost", FALSE)
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
             <!--<a class='add_return' href='".site_url('products/return_enter_using_stock_by_id/$1') . "'  class='tip' title='Return'><i class=\"fa fa-reply\"></i></a>-->
             ", "id,type");
            
        echo $this->datatables->generate();
    }
    
    public function get_using_stock()
    {
        $this->load->library('datatables');
        $fdate=$this->input->get('start_date');
        $tdate=$this->input->get('end_date');
        $referno=$this->input->get('referno');
        $empno=$this->input->get('empno');
        $plan=$this->input->get('plan');
        
        $start_date = $this->erp->fsd($fdate);
        $end_date = $this->erp->fsd($tdate);

        $action_link = '<div class="btn-group text-left"><button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'.lang("actions").'<span class="caret"></span></button>
            <ul class="dropdown-menu pull-right" role="menu">                                                               
                <li class="edit_using"><a href="'.site_url('products/edit_using_stock_by_id/$1/$2').'" ><i class="fa fa-edit"></i>'.lang('edit_using_stock').'</a></li> 
                <li class="add_return" ><a href="'.site_url('products/return_using_stock/$1/$2').'" ><i class="fa fa-reply"></i>'.lang('return_using_stock').'</a></li> 
                <li><a href="' . site_url('products/print_using_stock_by_id/$1/$2') . '" ><i class="fa fa-print"></i>' . lang('print_using_stock') . '</a></li>
                <!--<li><a href="' . site_url('products/print_sample_form_ppcp/$1/$2') . '" ><i class="fa fa-newspaper-o"></i>' . lang('print_sample_form_ppcp') . '</a></li>-->
            </ul>
        </div>';
                        
        $this->datatables
            ->select("erp_enter_using_stock.id as id,erp_enter_using_stock.date, erp_enter_using_stock.reference_no as refno,
            erp_companies.company, erp_warehouses.name as warehouse_name, erp_users.username, erp_enter_using_stock.note, erp_enter_using_stock.type as type, erp_enter_using_stock.is_return", FALSE)
            ->from("erp_enter_using_stock")
            ->join('erp_companies', 'erp_companies.id=erp_enter_using_stock.shop', 'inner')
            ->join('erp_warehouses', 'erp_enter_using_stock.warehouse_id=erp_warehouses.id', 'left')
            ->join('erp_project_plan', 'erp_enter_using_stock.plan_id = erp_project_plan.id', 'left')
            ->join('erp_products', 'erp_enter_using_stock.address_id = erp_products.id', 'left')
            ->join('erp_users', 'erp_users.id=erp_enter_using_stock.employee_id', 'inner')
            ->order_by('enter_using_stock.id', 'desc');
            if($fdate && $tdate){
                $this->datatables->where('erp_enter_using_stock.date>=',$start_date);
                $this->datatables->where('erp_enter_using_stock.date<=',$end_date);
            }
            if($referno!=''){
                $this->datatables->where('erp_enter_using_stock.reference_no',$referno);
            }
            if($empno!=''){
                $this->datatables->where('erp_users.username',$empno);
            }
            if($plan!=''){
                $this->datatables->where('erp_project_plan.id',$plan);
            }
             $this->datatables->add_column("Actions", $action_link, "id,type");
        echo $this->datatables->generate();
    }
    
    public function edit_enter_using_stock_by_id($id=NULL,$type=NULL)
    {
        $this->erp->checkPermissions('adjustments');
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $AllUsers=$this->site->getAllUsers();
        $CurrentUser=$this->site->getUser();
        $setting=$this->site->get_setting();
        $biller=$this->site->getAllBiller();
        $employee=$this->site->getAllEmployee();
        $all_unit=$this->site->getUnits();
        $product=$this->products_model->getProductName_code();
        $getGLChart=$this->products_model->getGLChart();
        $this->data['getGLChart'] = $getGLChart; 
        $this->data['AllUsers'] = $AllUsers; 
        $this->data['CurrentUser'] = $CurrentUser; 
        $this->data['setting'] = $setting; 
        $this->data['biller'] = $biller; 
        $this->data['all_unit'] = $all_unit; 
        $this->data['employees'] = $employee; 
        $this->data['product'] = $product; 
        $this->data['productJSON'] = json_encode($product); 
        $this->data['reference'] = $this->site->getReference('es');
        $getUsingStock=$this->products_model->getUsingStockById($id);
        $reference_no= $getUsingStock->reference_no;
        $wh_id=$getUsingStock->warehouse_id;
        $getUsingStockItem=$this->products_model->getUsingStockItemByRef($reference_no,$wh_id);
        $getQtyOnHandGroupByWh_ID=$this->products_model->getQtyOnHandGroupByWhID();
        $unit_of_measure_by_code=array();
        $i=0;
        foreach($getUsingStockItem as $Stock_I){
            $get_unit_of_measure = $this->products_model->getUnitOfMeasureByProductCode($Stock_I->product_code);
            
            $variant = $this->db->select("products.*, 
                                    '1' as measure_qty, 
                                    product_variants.name as description")
                                    ->from("products")
                                    ->where("products.code",$Stock_I->product_code)                                                                     
                                    ->join("product_variants","products.id=product_variants.product_id","left")
                                    ->get();
                                    
            if($variant->num_rows() > 0 && $variant->row()->description != null){
                $get_unit_of_measure = $variant->result();
            }
        
            foreach($get_unit_of_measure as $um)
            {
                $product_code = $Stock_I->product_code;
                $u_description = $um->description;
                $u_measure_qty = $um->measure_qty;
                $unit_of_measure_by_code[$i]=array(
                                                    'product_code'=>$product_code,
                                                    'description'=>$u_description,
                                                    'measure_qty'=>$u_measure_qty
                                                );
                $i++;
            }
        }
        $this->data['getExpenses'] = $this->products_model->getAllExpenseCategory();
        $this->data['positions'] = $this->products_model->getAllPositionData();
        $this->data['reasons'] = $this->products_model->getAllreasons();
        $this->data['unit_of_measure_by_code'] =$unit_of_measure_by_code;
        $this->data['qqh'] =$getQtyOnHandGroupByWh_ID;
        $this->data['stock'] =$getUsingStock;
        $this->data['stock_item'] =$getUsingStockItem;
        $this->data['modal_js'] = $this->site->modal_js();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('edit_enter_using_stock')));
        $meta = array('page_title' => lang('edit_enter_using_stock'), 'bc' => $bc);
        $this->page_construct('products/edit_enter_using_stock', $meta, $this->data);
    }
    
    public function edit_using_stock_by_id($id=NULL, $type=NULL)
    {
        $this->erp->checkPermissions('adjustments');
        $data['error']              = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['warehouses']   = $this->site->getAllWarehouses();
        $AllUsers                   = $this->site->getAllUsers();
        $CurrentUser                = $this->site->getUser();
        $setting                    = $this->site->get_setting();
        $biller                     = $this->site->getAllBiller();
        $employee                   = $this->site->getAllEmployee();
        $all_unit                   = $this->site->getUnits();
        $product                    = $this->products_model->getProductName_code();
        $getGLChart                 = $this->products_model->getGLChart();
        $this->data['getGLChart']   = $getGLChart; 
        $this->data['AllUsers']     = $AllUsers; 
        $this->data['CurrentUser']  = $CurrentUser; 
        $this->data['setting']      = $setting; 
        $this->data['biller']       = $biller; 
        $this->data['all_unit']     = $all_unit; 
        $this->data['employees']    = $employee; 
        $this->data['product']      = $product; 
        $this->data['productJSON']  = json_encode($product); 
        //$this->data['reference']  = $this->site->getReference('es');
        $getUsingStock              = $this->products_model->getUsingStockById($id);
        $reference_no               = $getUsingStock->reference_no;
        $date                       = $getUsingStock->date;
        $wh_id                      = $getUsingStock->warehouse_id;
        $getUsingStockItem          = $this->products_model->getUsingStockItemsByRef($reference_no);
        
        $c = rand(100000, 9999999);
        foreach ($getUsingStockItem as $row) {
            $option_unit        = $this->products_model->getUnitAndVaraintByProductId($row->id);
            $opt_pro            = $this->products_model->getProductVariantByOptionID($row->option_id);
            $row->project_qty   = 0;
            if($getUsingStock->plan_id){
                $project_item       = $this->products_model->getPlanUsing($getUsingStock->plan_id, $row->product_code, $getUsingStock->address_id);
                $row->project_qty   = ($project_item->quantity_balance - $project_item->using_qty + $project_item->reutn_using_qty);

                if ($project_item) {
                    $row->have_plan   = 1;
                }
            }
            
            $expiry_date        = $this->site->getProductExpireDate($row->id, $wh_id);
            
            $row->have_plan   = 0;
            $row->qty_old = $row->qty_use;
            if ($opt_pro) {
                $row->qty_use = $row->qty_use / $opt_pro->qty_unit; 
            } else {
                $row->qty_use = $row->qty_use;  
            }
            
            $ri = $this->Settings->item_addition ? $row->id : $c;
            $pr[$ri] = array('id' => $c, 'item_id' => $row->id, 'label' => $row->name . " (" . $row->product_code . ")", 'row' => $row, 'option_unit' => $option_unit, 'project_qty' => $row->project_qty,'stock_item' => $row->e_id, 'expiry_date' => $expiry_date);
            $c++;
        
        }
    
        $this->data['items']        = json_encode($pr);
        $this->data['refer']        = $reference_no;
        $this->data['date']         = $this->erp->hrsd($date);
        $this->data['where']        = $wh_id;
        $this->data['using_stock']  = $getUsingStock;
        $this->data['plan']         = $this->products_model->getPlan();
        
        $this->data['modal_js'] = $this->site->modal_js();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('edit_enter_using_stock')));
        $meta = array('page_title' => lang('edit_enter_using_stock'), 'bc' => $bc);
        $this->page_construct('products/edit_using_stock', $meta, $this->data);
    }
    
    public function update_enter_using_stock()
    {
            if ($this->Owner || $this->Admin || $this->Settings->allow_change_date == 1) {
                $date = $this->erp->fld(trim($this->input->post('date')));
            } else {
                $date = date('Y-m-d H:i:s');
            }
            
            $stock_id            = $this->input->post('stock_id');
            $date                = $date;
            $warehouse_id        = $this->input->post('from_location');
            $authorize_id        = $this->input->post('authorize_id');
            $employee_id         = $this->input->post('employee_id');
            $shop                = $this->input->post('shop');
            $account             = $this->input->post('account');
            $note                = $this->input->post('note');
            $cost                = $this->input->post('cost');
            $ref_prefix          = $this->input->post('ref_prefix');
            $stock_item_id_arr   = $this->input->post('stock_item_id');
            $item_code_arr       = $this->input->post('item_code');
            $description_arr     = $this->input->post('description');
            $reason_arr          = $this->input->post('reason');
            $qty_use_arr         = $this->input->post('qty_use');
            $last_qty_use_arr    = $this->input->post('last_qty_use');
            $unit_arr            = $this->input->post('unit');
            $qty_arr             = $this->input->post('qty_use');
            $reference_no        = $this->input->post('reference_no');
            $sotre_delete_id     = $this->input->post('sotre_delete_id');
            $product_id          = $this->input->post('product_id');
            $exp_cate_id         = $this->input->post('exp_catid');
            $delete_item         = (explode("-",$sotre_delete_id));
            $delete_product_id   = (explode("-",$product_id));
            $total_item_cost     = 0;
            $i=0;
            
            foreach($item_code_arr as $item_code)
            {
                $unit_of_measure=$this->products_model->getUnitOfMeasureByProductCode($item_code_arr[$i],$unit_arr[$i]);
                $variant = $this->db->select("products.*,
                                    qty_unit as measure_qty, 
                                    product_variants.name as description")
                                    ->from("products")
                                    ->where( 
                                        array(
                                            "products.code"=>$item_code_arr[$i],
                                            "product_variants.name" =>$unit_arr[$i]
                                            )
                                        )
                                    ->join("product_variants","products.id=product_variants.product_id","left")
                                    ->get();
                if($variant->num_rows() > 0 && $variant->row()->description != null){
                    $unit_of_measure = $variant->row();
                }
                
                $convert_qty = $qty_use_arr[$i]*$unit_of_measure->measure_qty;
                $total_cost  = $cost[$i] * $convert_qty;
                $total_item_cost+= $this->erp->formatDecimal($total_cost);
                $i++;
            }
            $CurrentUser=$this->site->getUser();
            $data = array(
                'date'          => $date,
                'warehouse_id'  => $warehouse_id,
                'authorize_id'  => $authorize_id,
                'employee_id'   => $employee_id,
                'shop'          => $shop,
                'account'       => $account,
                'note'          => $note,
                'create_by'     => $CurrentUser->id,
                'type'          => 'use',
                'total_cost'    => $total_item_cost,
            );
            
            $insert_enter_using_stock = $this->products_model->update_enter_using_stock($data,$ref_prefix,$stock_id);
            
            $i = 0;
            $del_en_item              = $this->products_model->delete_enter_items_by_ref($reference_no);
            $del_pu_item              = $this->products_model->delete_purchase_items_by_ref($reference_no);
            $this->products_model->delete_inventory_valuation_details($stock_item_id_arr);
            
            foreach($item_code_arr as $item_code){
                $unit_of_measure = $this->products_model->getUnitOfMeasureByProductCode($item_code_arr[$i],$unit_arr[$i]);
                
                $variant = $this->db->select("products.*,
                                    qty_unit as measure_qty,
                                    product_variants.id as option_id,
                                    product_variants.name as description")
                                    ->from("products")
                                    ->where( 
                                        array(
                                            "products.code"=>$item_code_arr[$i],
                                            "product_variants.name" =>$unit_arr[$i]
                                            )
                                        )
                                    ->join("product_variants","products.id=product_variants.product_id","left")
                                    ->get();
                if($variant->num_rows() > 0 && $variant->row()->description != null){
                    $unit_of_measure = $variant->row();
                }   
                $option_id = $unit_of_measure->option_id;
                $convert_qty = $qty_use_arr[$i] * $unit_of_measure->measure_qty;
                $item_data = array(
                    'code'          => $item_code_arr[$i],
                    'description'   => $description_arr[$i],
                    'reason'        => $reason_arr[$i],
                    'qty_use'       => $convert_qty,
                    'unit'          => $unit_arr[$i],
                    'qty_by_unit'   => $qty_use_arr[$i],
                    'warehouse_id'  => $warehouse_id,
                    'cost'          => $cost[$i],
                    'reference_no'  => $reference_no,
                    'exp_cate_id'   => $exp_cate_id[$i],
                    'option_id'     => $option_id
                );
                $insert_enter_using_stock_item = $this->products_model->insert_enter_using_stock_item($item_data);
                if($insert_enter_using_stock_item){
                    $product        = $this->products_model->getProductQtyByCode($item_code_arr[$i]);
                    $product_id     = $product->id;
                    $product_code   = $product->code;
                    $product_name   = $product->name;
                    $net_unit_cost  = $product->price;
                    $pr_item=null;
                    $pur_data = array(
                        'product_id'        => $product_id,
                        'product_code'      => $product_code,
                        'product_name'      => $product_name,
                        'net_unit_cost'     => $product->cost,
                        'option_id'         => $unit_of_measure->id,
                        'quantity'          => -1 * abs($convert_qty),
                        'reference'         => $reference_no,
                        'warehouse_id'      => $warehouse_id,
                        'subtotal'          => $pr_item->subtotal ? $pr_item->subtotal : 0,
                        'date'              => $date,
                        'status'            => 'received',
                        'net_unit_cost'     => $net_unit_cost,
                        'quantity_balance'  => -1 * abs($convert_qty),
                        'transaction_type'  => 'USING STOCK',
                        'transaction_id'    => $insert_enter_using_stock_item,
                    );
                    $this->db->insert('purchase_items', $pur_data);
                    $product_cost = $this->site->getProductByID($product_id);
                $this->db->update("inventory_valuation_details",array('cost'=>$product_cost->cost,'avg_cost'=>$product_cost->cost),array('field_id'=>$insert_enter_using_stock_item));
                        //$this->site->syncProductQty($product_id, $warehouse_id);
                    $this->site->syncQuantitys(null,null,null,$product_id);
                }
                $i++;
            }
            foreach($delete_item as $d_i){
                
                //$del = $this->products_model->delete_update_stock_item($d_i);
                
                if($delete_product_id){
                    foreach($delete_product_id as $product_id){
                        $this->site->syncQuantitys(null,null,null,$product_id);
                    }
                }
            }
            if($insert_enter_using_stock_item && $insert_enter_using_stock){
                $this->session->set_flashdata(lang('enter_using_stock_added.'));
                    $r_r=str_replace("/","-",$this->input->post('reference_no'));
                    
                    redirect('products/print_enter_using_stock/'.$r_r);
            }else{
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
            }
        
    }
    
    public function update_using_stock()
    {
        $this->form_validation->set_rules('account', lang("account"), 'required');
        $this->form_validation->set_rules('from_location', lang("from_location"), 'required');
        if ($this->form_validation->run() == true){
            $stock_id   = $this->input->post('stock_id');
            if($this->Owner || $this->Admin){
                $date       = $this->erp->fld($this->input->post('date'));
            } else {
                $date       = date('Y-m-d');
            }
            isCloseDate(date('Y-m-d', strtotime($date)));
            $authorize      = $this->input->post('authorize_id');
            $account        = $this->input->post('account');
            $reference_no   = $this->input->post('reference_no');
            $employee_id    = $this->input->post('employee_id');
            $customer_id    = $this->input->post('customer');
            $plan           = $this->input->post('plan');
            $address        = $this->input->post('address');
            $warehouse_id   = $this->input->post('from_location');
            $shop           = $this->input->post('shop');
            $note           = $this->input->post('note');
            $total_item_cost= 0;
            
            $i              = sizeof($_POST['product_id']);
            for ($r = 0; $r < $i; $r++) {
                $product_id     = $_POST['product_id'][$r];
                $product_code   = $_POST['item_code'][$r];
                $product_name   = $_POST['name'][$r];
                $product_cost   = $_POST['cost'][$r];
                $description    = $_POST['description'][$r];
                $qty_use        = $_POST['qty_use'][$r];
                $qty_old        = $_POST['qty_old'][$r];
                $unit           = $_POST['unit'][$r];
                $expiry         = isset($_POST['exp'][$r]);
                $qty_balance    = $qty_use;
                $option_id      = '';
                $total_cost     = $product_cost * $qty_balance; 
                
                $variant        = $this->site->getProductVariantByID($product_id, $unit);
                if ($variant) {
                    $qty_balance    = $qty_use * $variant->qty_unit;
                    $option_id      = $variant->id;
                    $total_cost     = $product_cost * $qty_balance;
                        
                }
                
                $warehouse      = $this->site->getWarehouseQty($product_id, $warehouse_id);

                if(($warehouse->quantity + $qty_old) < $qty_balance){
                    $this->session->set_flashdata('error', $this->lang->line("quantity_bigger") );
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                $item_data[] = array(
                    'product_id'    => $product_id,
                    'code'          => $product_code,
                    'product_name'  => $product_name,
                    'description'   => $description,
                    'qty_use'       => $qty_balance,
                    'qty_by_unit'   => $qty_use,
                    'unit'          => $unit,
                    'expiry'        => $expiry,
                    'warehouse_id'  => $warehouse_id,
                    'cost'          => $product_cost,
                    'reference_no'  => $reference_no,
                    'option_id'     => $option_id
                );
                
                $total_item_cost+= $total_cost;
            }
            
            if (empty($item_data)) {
                $this->session->set_flashdata('error', $this->lang->line("no_data_select") );
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                krsort($item_data);
            }
            
            $data = array(
                'date'          => $date,
                'reference_no'  => $reference_no,
                'warehouse_id'  => $warehouse_id,
                'authorize_id'  => $authorize,
                'employee_id'   => $employee_id,
                'customer_id'   => $customer_id,
                'shop'          => $shop,
                'account'       => $account,
                'note'          => $note,
                'create_by'     => $this->session->userdata('user_id'),
                'type'          => 'use',
                'total_cost'    => $total_item_cost,
                'plan_id'       => $plan,
                'address_id'    => $address,
                
            );
            //$this->erp->print_arrays($data, $item_data);
            $using_stock = $this->products_model->update_enter_using_stock($stock_id, $data, $item_data);
            optimizeUsing(date('Y-m-d', strtotime($date)));
            if($using_stock){
                $this->session->set_flashdata(lang('enter_using_stock_added.'));
                $r_r = str_replace("/","-",$reference_no);
                redirect('products/print_using_stock/' . $r_r);
            }
            
        } else {
            $this->session->set_flashdata('error', validation_errors());
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        
    }
    
    public function print_enter_using_stock_by_id($id, $type)
    {
        $this->erp->checkPermissions('using_stock');
        if($type=="use"){
            $using_stock=$this->products_model->get_enter_using_stock_by_id($id);
            $stock_item = $this->products_model->get_enter_using_stock_item_by_ref($id);
            $this->data['using_stock'] = $using_stock;
            $this->data['stock_item'] = $stock_item;
            $this->load->view($this->theme.'products/print_enter_using_stock',$this->data);
        }
        if($type=="return"){
            $using_stock=$this->products_model->get_enter_using_stock_by_id($id);
            $stock_item=$this->products_model->get_enter_using_stock_item_by_ref($id);
            $this->data['using_stock'] = $using_stock;
            $this->data['stock_item'] = $stock_item;
            $this->load->view($this->theme.'products/print_enter_using_stock_return',$this->data);
        }
    }
    
    public function print_using_stock_by_id($id, $type)
    {
        $this->erp->checkPermissions('using_stock');
        if($type=="use"){
            $using_stock = $this->products_model->get_enter_using_stock_by_id($id);
            $ref_no      = $using_stock->reference_no;
            $stock_item  = $this->products_model->get_enter_using_stock_item_by_ref($ref_no);

            $this->data['using_stock']  = $using_stock;
            $this->data['stock_item']   = $stock_item; 
            $this->data['info']         = $this->products_model->get_enter_using_stock_info(); 
            $this->data['biller']       = $this->products_model->getUsingStockProject($id);
            $this->data['invs'] = $this->products_model->getUsingStockProjectByRef($ref_no);
            $this->data['au_info']      = $this->products_model->getAuInfo($id);
            $this->load->view($this->theme.'products/print_using_stock',$this->data);
        }
        if($type=="return"){
            $using_stock = $this->products_model->get_enter_using_stock_by_id($id);
            $ref_no=$using_stock->reference_no;
            $stock_item=$this->products_model->get_enter_using_stock_item_by_ref($id);
             $this->data['using_stock'] = $using_stock; 
             $this->data['stock_item'] = $stock_item;
            $this->data['au_info']      = $this->products_model->getAuInfo($id);
            $this->data['biller']       = $this->products_model->getUsingStockProject($id);
            $this->data['invs'] = $this->products_model->getUsingStockProjectByRef($ref_no);
            $this->load->view($this->theme.'products/print_enter_using_stock_return',$this->data);
        }
    }
    public function print_sample_form_ppcp($id, $type)
    {
        $this->erp->checkPermissions('using_stock');
        if($type=="use"){
            $using_stock = $this->products_model->get_enter_using_stock_by_id($id);
            $ref_no      = $using_stock->reference_no;
            $stock_item  = $this->products_model->get_enter_using_stock_item_by_ref($ref_no);
            
            $this->data['using_stock']  = $using_stock;
            $this->data['stock_item']   = $stock_item; 
            $this->data['info']         = $this->products_model->get_enter_using_stock_info();
            $this->data['biller']       = $this->products_model->getUsingStockProject($id);
            $this->data['customer']             = $this->products_model->getUsingStockByCustomerID($id);
            $this->data['au_info']      = $this->products_model->getAuInfo($id);
            $this->load->view($this->theme.'products/print_sample_form_ppcp',$this->data);
        }
        if($type=="return"){
            $using_stock = $this->products_model->get_enter_using_stock_by_id($id);
            $ref_no=$using_stock->reference_no;
            $stock_item=$this->products_model->get_enter_using_stock_item_by_ref($ref_no);
             $this->data['using_stock'] = $using_stock; 
             $this->data['stock_item'] = $stock_item; 
            $this->load->view($this->theme.'products/print_sample_form_ppcp',$this->data);
        }
    }
    
    public function return_enter_using_stock_by_id($id=NULL, $type=NULL)
    {
        $this->erp->checkPermissions('adjustments');
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $using_stock = $this->products_model->get_enter_using_stock_by_id($id);

        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $AllUsers=$this->site->getAllUsers();
        $CurrentUser=$this->site->getUser();
        $setting=$this->site->get_setting();
        $biller=$this->site->getAllBiller();
        $employee=$this->site->getAllEmployee();
        $all_unit=$this->site->getUnits();
        $product=$this->products_model->getProductName_code();
        $getGLChart=$this->products_model->getGLChart();
        $this->data['getGLChart'] = $getGLChart; 
        $this->data['AllUsers'] = $AllUsers; 
        $this->data['CurrentUser'] = $CurrentUser; 
        $this->data['setting'] = $setting; 
        $this->data['biller'] = $biller; 
        $this->data['all_unit'] = $all_unit; 
        $this->data['employees'] = $employee; 
        $this->data['product'] = $product; 
        $this->data['productJSON'] = json_encode($product); 
        $this->data['reference'] = $this->site->getReference('es');

        $getUsingStock=$this->products_model->getUsingStockById($id);
        $reference_no= $getUsingStock->reference_no;
        $wh_id=$getUsingStock->warehouse_id;
        $getUsingStockItem=$this->products_model->getUsingStockItemByRef($reference_no,$wh_id);
        $getQtyOnHandGroupByWh_ID=$this->products_model->getQtyOnHandGroupByWhID();
        
        $unit_of_measure_by_code=array();
        $i=0;
        foreach($getUsingStockItem as $Stock_I){
            $get_unit_of_measure=$this->products_model->getUnitOfMeasureByProductCode($Stock_I->product_code);
            foreach($get_unit_of_measure as $um){
                $product_code=$Stock_I->product_code;
                $u_description=$um->description;
                $u_measure_qty=$um->measure_qty;
                $unit_of_measure_by_code[$i]=array(
                            'product_code'=>$product_code,
                            'description'=>$u_description,
                            'measure_qty'=>$u_measure_qty
                );
                $i++;
            }
        }
        $this->data['unit_of_measure_by_code'] =$unit_of_measure_by_code;
        $return_ref = $this->products_model->getReturnReference($reference_no);
        $this->data['reference_no'] = $return_ref;
        $this->data['qqh'] = $getQtyOnHandGroupByWh_ID;
        $this->data['stock'] = $getUsingStock;
        $this->data['stock_item'] =$getUsingStockItem;
        
        $this->data['reference_return'] = $this->site->getReference('esr');
        $this->data['modal_js'] = $this->site->modal_js();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('return_using_stock')));
        $meta = array('page_title' => lang('return_using_stock'), 'bc' => $bc);
        $this->page_construct('products/return_enter_using_stock', $meta, $this->data);
    }
    
    public function add_enter_using_stock_return()
    {

            $date = $this->erp->fld(trim($this->input->post('date')));
            $reference_no        = $this->input->post('reference_no');
            $return_reference_no = $this->input->post('return_reference_no');
            $warehouse_id        = $this->input->post('from_location');
            $authorize_id        = $this->input->post('authorize_id');
            $employee_id         = $this->input->post('employee_id');
            $shop                = $this->input->post('shop');
            $account             = $this->input->post('account');
            $note                = $this->input->post('note');
            $cost                = $this->input->post('cost');
            $total_cost_by_row   = $this->input->post('total_cost');
            $ref_prefix          = $this->input->post('ref_prefix');
            $item_code_arr       = $this->input->post('item_code');
            $description_arr     = $this->input->post('description');
            $reason_arr          = $this->input->post('reason');
            $qty_use_arr         = $this->input->post('qty_use');
            $qty_return_arr      = $this->input->post('qty_return');
            $unit_arr            = $this->input->post('unit');
            $qty_arr             = $this->input->post('qty_use');
            
            
            $total_item_cost     = 0;
            //print_r($total_cost_by_row);
            //echo '///<br/>';
            //echo 'New _ cost __________________<br/>';
            $i=0;
            foreach($item_code_arr as $item_code){
                $unit_of_measure=$this->products_model->getUnitOfMeasureByProductCode($item_code_arr[$i],$unit_arr[$i]);
                $convert_cost=($cost[$i]*$unit_of_measure->measure_qty)*$qty_return_arr[$i];
                $total_cost=$convert_cost*$qty_return_arr[$i];
                $total_item_cost+=$convert_cost;
                //echo $total_item_cost.'<br/>';
                $i++;
            }
            //echo 'Old _ cost __________________<br/>';
            $i=0;$total_old_item_cost=0;
            foreach($item_code_arr as $item_code){
                $unit_of_measure=$this->products_model->getUnitOfMeasureByProductCode($item_code_arr[$i],$unit_arr[$i]);
                $old_product_cost=$this->products_model->getUsingStockItem($item_code_arr[$i],$reference_no);
                $total_old_cost=$old_product_cost->cost * $qty_return_arr[$i];
                $convert_cost=$total_old_cost*$unit_of_measure->measure_qty;
                $total_cost=$convert_cost*$qty_return_arr[$i];
                $total_old_item_cost+=$convert_cost;
                //echo $total_old_item_cost.'<br/>';
                $i++;
            }//exit;
            $CurrentUser=$this->site->getUser();
            $data = array(
                'date' => $date,
                'using_reference_no' => $reference_no,
                'reference_no' => $return_reference_no,
                'warehouse_id' => $warehouse_id,
                'authorize_id' => $authorize_id,
                'employee_id' => $employee_id,
                'shop' => $shop,
                'account' => $account,
                'note' => $note,
                'create_by' => $CurrentUser->id,
                'type' => 'return',
                'total_cost' => $total_item_cost,
                'total_using_cost' =>   $total_old_item_cost,
            );
            //print_r($data);exit;
            $insert_enter_using_stock=$this->products_model->insert_enter_using_stock($data,$ref_prefix);
            
            
            $i = 0;
            foreach($item_code_arr as $item_code){
                    $unit_of_measure=$this->products_model->getUnitOfMeasureByProductCode($item_code_arr[$i],$unit_arr[$i]);
                    $getProduct = $this->products_model->getProductByCode($item_code_arr[$i]);
                    $convert_qty=$qty_return_arr[$i]*$unit_of_measure->measure_qty;
                    $cost=$getProduct->cost;
                    $item_data = array(
                        'code' => $item_code_arr[$i],
                        'description' => $description_arr[$i],
                        'reason' => $reason_arr[$i],
                        'qty_use' => $convert_qty,
                        'qty_by_unit' => $qty_return_arr[$i],
                        'unit' => $unit_arr[$i],
                        'cost' => $cost,
                        'warehouse_id' => $warehouse_id,
                        'reference_no' => $return_reference_no,
                    );
                    $insert_enter_using_stock_item = $this->products_model->insert_enter_using_stock_item($item_data);
                    if($insert_enter_using_stock_item){
                        $product=$this->products_model->getProductQtyByCode($item_code_arr[$i]);
                        $product_id=$product->id;
                        $product_code=$product->code;
                        $product_name=$product->name;
                        $net_unit_cost=$product->price;
                        $pr_item=null;
                        $pur_data = array(
                            'product_id'        => $product_id,
                            'product_code'      => $product_code,
                            'product_name'      => $product_name,
                            'net_unit_cost'     => $product->cost,
                            'option_id'         => $unit_of_measure->id,
                            'quantity'          => abs($convert_qty),
                            'net_unit_cost'     => $net_unit_cost,
                            'warehouse_id'      => $warehouse_id,
                            'subtotal'          => $pr_item->subtotal?$pr_item->subtotal:0,
                            'date'              => $date,
                            'reference'         => $return_reference_no,
                            'status'            => 'received',
                            'quantity_balance'  => abs($convert_qty),
                            'transaction_id'    => $insert_enter_using_stock_item,
                            'transaction_type'  => 'RETURN USING STOCK'
                        );
                        $this->db->insert('purchase_items', $pur_data);
                        $this->site->syncProductQty($product_id, $warehouse_id);
                    }   
                        $i++;   
            }       
            if($insert_enter_using_stock_item && $insert_enter_using_stock){
                $this->session->set_flashdata(lang('enter_using_stock_return_added.'));
                    $r_r=str_replace("/","-",$return_reference_no);
                    
                    redirect('products/print_enter_using_stock_return/'.$r_r);
            }else{
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
            }
        
    }
    
    public function print_enter_using_stock_return($id)
    {
        $this->db->select('reference_no');
        $us_ref = $this->db->get_where('erp_enter_using_stock', array('id'=> $id), 1);
        $row = $us_ref->row();

        $using_stock                = $this->products_model->get_enter_using_stock_by_ref($row->reference_no);
        $stock_item                 = $this->products_model->get_enter_using_stock_item_by_ref($row->reference_no);
        $this->data['biller']       = $this->site->getCompanyByID($using_stock->shop);
        $this->data['invs'] = $this->products_model->getUsingStockProjectByRef($using_stock->reference_no);

        //$this->data['biller']       = $this->products_model->getUsingStockProjectByRef($ref);
        $this->data['au_info']      = $this->site->getUser($using_stock->authorize_id);
        $this->data['using_stock']  = $using_stock; 
        $this->data['stock_item']   = $stock_item; 
        $this->load->view($this->theme.'products/print_enter_using_stock_return',$this->data);
    }
    
    public function edit_enter_using_stock_return_by_id($id,$type)
    {
        $this->erp->checkPermissions('adjustments');
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $AllUsers=$this->site->getAllUsers();
        $CurrentUser=$this->site->getUser();
        $setting=$this->site->get_setting();
        $biller=$this->site->getAllBiller();
        $employee=$this->site->getAllEmployee();
        $all_unit=$this->site->getUnits();
        $product=$this->products_model->getProductName_code();
        $getGLChart=$this->products_model->getGLChart();
        $this->data['getGLChart'] = $getGLChart; 
        $this->data['AllUsers'] = $AllUsers; 
        $this->data['CurrentUser'] = $CurrentUser; 
        $this->data['setting'] = $setting; 
        $this->data['biller'] = $biller; 
        $this->data['all_unit'] = $all_unit; 
        $this->data['employees'] = $employee; 
        $this->data['product'] = $product; 
        $this->data['productJSON'] = json_encode($product); 
        $this->data['reference'] = $this->site->getReference('es');
        

        $getUsingStock=$this->products_model->getUsingStockById($id);
        $reference_no= $getUsingStock->reference_no;
        
        $wh_id=$getUsingStock->warehouse_id;
        $getUsingStockItem=$this->products_model->getUsingStockReturnItemByRef($reference_no,$wh_id);
        
        $getQtyOnHandGroupByWh_ID=$this->products_model->getQtyOnHandGroupByWhID();
        
        $unit_of_measure_by_code=array();
        $i=0;
        foreach($getUsingStockItem as $Stock_I){
            $get_unit_of_measure=$this->products_model->getUnitOfMeasureByProductCode($Stock_I->product_code);
            foreach($get_unit_of_measure as $um){
                $product_code=$Stock_I->product_code;
                $u_description=$um->description;
                $u_measure_qty=$um->measure_qty;
                $unit_of_measure_by_code[$i]=array(
                            'product_code'=>$product_code,
                            'description'=>$u_description,
                            'measure_qty'=>$u_measure_qty
                );
                $i++;
            }
        }
        $this->data['unit_of_measure_by_code'] =$unit_of_measure_by_code;
        
        $this->data['qqh'] =$getQtyOnHandGroupByWh_ID;
        $this->data['stock'] =$getUsingStock;
        $this->data['stock_item'] =$getUsingStockItem;
        
        $this->data['modal_js'] = $this->site->modal_js();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('edit_enter_using_stock_return')));
        $meta = array('page_title' => lang('edit_enter_using_stock_return'), 'bc' => $bc);
        $this->page_construct('products/edit_enter_using_stock_return', $meta, $this->data);
    }
    
    public function update_enter_using_stock_return_by_id()
    {
            $stock_id            =  $this->input->post('stock_id');
            $date                =  $this->erp->fld(trim($this->input->post('date')));
            $warehouse_id        = $this->input->post('from_location');
            $authorize_id        = $this->input->post('authorize_id');
            $employee_id         = $this->input->post('employee_id');
            $shop                = $this->input->post('shop');
            $account             = $this->input->post('account');
            $note                = $this->input->post('note');
            $cost                = $this->input->post('cost');
            
            $ref_prefix          = $this->input->post('ref_prefix');

            $stock_item_id_arr   = $this->input->post('stock_item_id');
            $item_code_arr       = $this->input->post('item_code');
            $description_arr     = $this->input->post('description');
            $reason_arr          = $this->input->post('reason');
            $qty_use_arr         = $this->input->post('qty_return');
            $last_qty_use_arr    = $this->input->post('last_qty_return');
            $unit_arr            = $this->input->post('unit');
            $qty_arr             = $this->input->post('qty_use');
            $reference_no        = $this->input->post('reference_no');
            
            $sotre_delete_id     = $this->input->post('sotre_delete_id');
            
            $delete_item         = (explode("-",$sotre_delete_id));

            $total_item_cost     = 0;
            $using_reference_no  = $this->input->post('using_reference_no');
            $i                   = 0;
            foreach($item_code_arr as $item_code){
                $unit_of_measure=$this->products_model->getUnitOfMeasureByProductCode($item_code_arr[$i],$unit_arr[$i]);
                $convert_qty=$qty_use_arr[$i]*$unit_of_measure->measure_qty;
                $total_cost=$cost[$i]*$convert_qty;
                $total_item_cost+=$total_cost;
                
                $i++;
            }
            //echo $total_item_cost.'<br/>';
            $i                   = 0;
            $total_old_item_cost = 0;
            foreach($item_code_arr as $item_code){
                $unit_of_measure=$this->products_model->getUnitOfMeasureByProductCode($item_code_arr[$i],$unit_arr[$i]);
                $old_product_cost=$this->products_model->getUsingStockItem($item_code_arr[$i],$using_reference_no);
                $convert_qty=$qty_use_arr[$i]*$unit_of_measure->measure_qty;
                $total_cost=$old_product_cost->cost*$convert_qty;
                $total_old_item_cost+=$total_cost;
                
                $i++;
            }
            //echo $total_old_item_cost.'<br/>';
            //exit;
            $CurrentUser=$this->site->getUser();
            $data = array(
                'date'              => $date,
                'warehouse_id'      => $warehouse_id,
                'authorize_id'      => $authorize_id,
                'employee_id'       => $employee_id,
                'shop'              => $shop,
                'account'           => $account,
                'note'              => $note,
                'create_by'         => $CurrentUser->id,
                'type'              => 'return',
                'total_cost'        =>  $total_item_cost,
                'total_using_cost'  =>  $total_old_item_cost,
            );
            $insert_enter_using_stock=$this->products_model->update_enter_using_stock($data,$ref_prefix,$stock_id);
            
            $i = 0;
            
            
            $del_pu_item= $this->products_model->delete_purchase_items_by_ref($reference_no);
            foreach($item_code_arr as $item_code){
                $unit_of_measure=$this->products_model->getUnitOfMeasureByProductCode($item_code_arr[$i],$unit_arr[$i]);
                $convert_qty=$qty_use_arr[$i]*$unit_of_measure->measure_qty;
                
                if($stock_item_id_arr[$i]!="NULL"){
                    $item_data = array(
                        'code'          => $item_code_arr[$i],
                        'description'   => $description_arr[$i],
                        'reason'        => $reason_arr[$i],
                        'qty_use'       => $convert_qty,
                        'qty_by_unit'   => $qty_use_arr[$i],
                        'unit'          => $unit_arr[$i],
                        'warehouse_id'  => $warehouse_id,
                    );
                    $insert_enter_using_stock_item=$this->products_model->update_enter_using_stock_item($item_data,$stock_item_id_arr[$i]);
                    if($insert_enter_using_stock_item){
                        $product=$this->products_model->getProductQtyByCode($item_code_arr[$i]);
                        $product_id=$product->id;
                        $product_code=$product->code;
                        $product_name=$product->name;
                        $net_unit_cost=$product->price;
                        $pr_item=null;
                        $pur_data = array(
                            'product_id'        => $product_id,
                            'product_code'      => $product_code,
                            'product_name'      => $product_name,
                            'net_unit_cost'     => $product->cost,
                            'option_id'         => $unit_of_measure->id,
                            'quantity'          => $convert_qty,
                            'warehouse_id'      => $warehouse_id,
                            'subtotal'          => $pr_item->subtotal?$pr_item->subtotal:0,
                            'date'              => $date,
                            'status'            => 'received',
                            'reference'         => $reference_no,
                            'net_unit_cost'     => $net_unit_cost,
                            'quantity_balance'  => $convert_qty,
                            'transaction_type'  => 'USING STOCK',
                            'transaction_id'    => $stock_item_id_arr[$i]
                        );
                        $this->db->insert('purchase_items', $pur_data);
                        $this->site->syncProductQty($product_id, $warehouse_id);
                    }
                }else{
                //echo $stock_item_id_arr[$i];
                    $item_data = array(
                        'code'          => $item_code_arr[$i],
                        'description'   => $description_arr[$i],
                        'reason'        => $reason_arr[$i],
                        'qty_use'       => $convert_qty,
                        'unit'          => $unit_arr[$i],
                        'warehouse_id'  => $warehouse_id,
                    );
                    //print_r($item_data);      
                    //echo '||||| <br/>';
                    $insert_enter_using_stock_item=$this->products_model->insert_enter_using_stock_item($item_data);
                    if($insert_enter_using_stock_item){
                        $product        = $this->products_model->getProductQtyByCode($item_code_arr[$i]);
                        $product_id     = $product->id;
                        $product_code   = $product->code;
                        $product_name   = $product->name;
                        $net_unit_cost  = $product->price;
                        $this->site->syncProductQty($product_id, $warehouse_id);
                    }
                    
                }
                $i++;   
            }
            foreach($delete_item as $d_i){
                $del=$this->products_model->delete_update_stock_item($d_i);
            }
            if($insert_enter_using_stock_item && $insert_enter_using_stock){
                $this->session->set_flashdata(lang('enter_using_stock_added.'));
                    $r_r=str_replace("/","-",$this->input->post('reference_no'));
                    
                    redirect('products/print_enter_using_stock/'.$r_r);
            }else{
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
            }
        
    }
    
    public function using_stock_action()
    {
        if(!empty($_POST['val'])){
            $this->load->library('excel');
            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle(lang('list_using_stock'));
            $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));
            $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));
            $this->excel->getActiveSheet()->SetCellValue('C1', lang('project'));
            $this->excel->getActiveSheet()->SetCellValue('D1', lang('warehouse'));
            $this->excel->getActiveSheet()->SetCellValue('E1', lang('employee'));
            $this->excel->getActiveSheet()->SetCellValue('F1', lang('description'));
            $this->excel->getActiveSheet()->SetCellValue('G1', lang('status'));
            // $this->excel->getActiveSheet()->SetCellValue('H1', lang('cost'));
            $this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
            $i = 2;$sum_totalCost=0;
            foreach ($_POST['val'] as $id) {
            $row = $this->products_model->get_all_enter_using_stock($id);
                //sum total cost
                $sum_totalCost += $row->total_cost;
                //$ref_no=$row->reference_no;
                //$stock_item=$this->products_model->get_enter_using_stock_item_by_ref($ref_no);
                $this->excel->getActiveSheet()->SetCellValue('A'.$i, $row->date);
                $this->excel->getActiveSheet()->SetCellValue('B'.$i, $row->reference_no);
                $this->excel->getActiveSheet()->SetCellValue('C'.$i, $row->company);
                $this->excel->getActiveSheet()->SetCellValue('D'.$i, $row->warehouse_name);
                $this->excel->getActiveSheet()->SetCellValue('E'.$i, $row->username);
                $this->excel->getActiveSheet()->SetCellValue('F'.$i, $this->erp->decode_html(strip_tags($row->note)));
                $this->excel->getActiveSheet()->SetCellValue('G'.$i, $row->type);
                // $this->excel->getActiveSheet()->SetCellValue('H'.$i, $this->erp->formatMoney($row->total_cost));
                // $this->excel->getActiveSheet()->getStyle('H'. $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $this->excel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //To display sum total cost
                // $a = $i+1;
                // $this->excel->getActiveSheet()->SetCellValue('H' . $a,$this->erp->formatMoney( $sum_totalCost));
                // $this->excel->getActiveSheet()->getStyle('H'. $a)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $i++;       
            }
                //set font bold,font color,font size,font name and background color to excel
                // $styleArray = array(
                //     'font'  => array(
                //         'bold'  => true,
                //         'color' => array('rgb' => 'FFFFFF'),
                //         'size'  => 10,
                //         'name'  => 'Verdana'
                //     ),
                //     'fill' => array(
                //         'type' => PHPExcel_Style_Fill::FILL_SOLID,
                //         'color' => array('rgb' => '428BCA')
                //     )
                // );
                // $this->excel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);

                $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                $filename = lang('list_using_stock');
                $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                 //Add style bold text and border top in case PDF
                // $this->excel->getActiveSheet()->getStyle('H'.$a. '')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                // $this->excel->getActiveSheet()->getStyle('H'. $a. '')->getFont()->setBold(true);
                if ($this->input->post('form_action') == 'export_excel') {
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                return $objWriter->save('php://output');
            }
            redirect($_SERVER["HTTP_REFERER"]);
        }else {
            $this->session->set_flashdata('error', $this->lang->line("No_selected. Please select at least one!"));
            redirect($_SERVER["HTTP_REFERER"]);
        }    
    }
    
    /****************STOCK COUNT******************/
    
    public function stock_count()
    {
    
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('sales'), 'page' => lang('sales')), array('link' => '#', 'page' => lang('stock_count')));
        $meta = array('page_title' => lang('stock_count'), 'bc' => $bc);
        $this->page_construct('products/stock_count', $meta, $this->data);    
        
    }
    
    public function stock_count_excel()
    {
        $t = $this->input->get('excel');
        echo json_encode($t);
    }
    
    public function list_count_stock($warehouse_id = NULL)
    {
        $this->erp->checkPermissions('count_stocks', true, 'products');

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        if ($this->Owner || $this->Admin || !$this->session->userdata('warehouse_id')) {
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['warehouse_id'] = $warehouse_id;
            $this->data['warehouse'] = $warehouse_id ? $this->site->getWarehouseByID($warehouse_id) : NULL;
        } else {
            $this->data['warehouses'] = NULL;
            $this->data['warehouse_id'] = $this->session->userdata('warehouse_id');
            $this->data['warehouse'] = $this->session->userdata('warehouse_id') ? $this->site->getWarehouseByID($this->session->userdata('warehouse_id')) : NULL;
        }

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('product_count_list')));
        $meta = array('page_title' => lang('product_count_list'), 'bc' => $bc);
        $this->page_construct('products/list_count_stock', $meta, $this->data);
    }
    
    public function getCounts()
    {
        $this->erp->checkPermissions('count_stocks', true, 'products');
        $warehouse_id = [];
        if (! $this->Owner && ! $this->Admin) {
            $user = $this->site->getUser();
            $warehouse_id = explode(',', $user->warehouse_id);
        }

        $delete_link = "<a href='#' class='delete po' title='" . lang("delete_count") . "' data-content=\"<p>"
            . lang('r_u_sure') . "</p><a class='btn btn-danger' href='" . site_url('products/delete_count/$1') . "'>"
            . lang('i_m_sure') . "</a><button class='btn'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i>"
            . lang('delete_count') . "</a>";
            
        $action_link = '<div class="btn-group text-left"><button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'.lang("actions").'<span class="caret"></span></button>
                            <ul class="dropdown-menu pull-right" role="menu">                                                               
                                <li><a class="add-sm" href="'.site_url('products/view_count/$1').'" data-toggle="modal" data-target="#myModal"><i class="fa fa-newspaper-o"></i>'.lang('view_count').'</a></li>                                                         
                                <li>'.$delete_link.'</li>
                            </ul>
                        </div>';
                        
        $this->load->library('datatables');
        $this->datatables
            ->select("{$this->db->dbprefix('stock_counts')}.id as id, 
                        date, 
                        reference_no, 
                        {$this->db->dbprefix('warehouses')}.name as wh_name, 
                        type, 
                        brand_names, 
                        category_names, 
                        initial_file, 
                        final_file")
            ->from('stock_counts')
            ->join('warehouses', 'warehouses.id=stock_counts.warehouse_id', 'left');
            if (count($warehouse_id) > 0) {
                $this->datatables->where_in('warehouse_id', $warehouse_id);
            }

        $this->datatables->add_column('Actions', $action_link, "id");
        echo $this->datatables->generate();
    }
    
    public function view_count($id)
    {
        $this->erp->checkPermissions('count_stocks', true, 'products');
        $stock_count = $this->products_model->getStouckCountByID($id);
        
        if ( ! $stock_count->finalized ) {
            $this->erp->md('products/finalize_count/'.$id);
        }

        $this->data['stock_count'] = $stock_count;
        $this->data['stock_count_items'] = $this->products_model->getStockCountItems($id);
        $this->data['warehouse'] = $this->site->getWarehouseByID($stock_count->warehouse_id);
        $this->data['adjustment'] = $this->products_model->getAdjustmentByCountID($id);
        
        $this->load->view($this->theme.'products/view_count', $this->data);
    }
    
    public function delete_count($id = NULL)
    {           
        if($id){            
            $result = $this->db->where("id", $id)->delete("stock_counts");
            if($result){
                $this->session->set_flashdata('message', lang("delete_count"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }else{
            $this->session->set_flashdata('error', lang("ajax_error"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }
    
    public function exportStock()
    {
        $arrPro = $_REQUEST['pro_id'];
        $arrQty = $_REQUEST['pro_qty'];
        $wareId = $_REQUEST['warehouse_id'];
        $cateId = $_REQUEST['category_id'];
        $final = array_combine($arrPro, $arrQty);
        $all_product = $this->products_model->getProductByArrProId($wareId, $cateId, $arrPro);
        foreach($all_product as $pro_all){
            $pro_all->qty = $final[$pro_all->pid]?$final[$pro_all->pid]:0;
            $pro_count[] = $pro_all;
        }
        echo json_encode($pro_count);
    }
    
    public function barcode_count_stock()
    {
        $this->erp->checkPermissions('count_stocks', true, 'products');
        $this->data['warehouses'] = $this->site->getAllWarehouses();
        $this->data['category']   = $this->site->getAllCategories();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('barcode_count_stock')));
        $meta = array('page_title' => lang('barcode_count_stock'), 'bc' => $bc);
        $this->page_construct('products/barcode_count_stock', $meta, $this->data);
    }

    function barcode_count_stock_actions()
    {
        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
        if ($this->form_validation->run() == true) {

                if ($this->input->post('form_action') == 'export_excel') {
                    if($this->Owner || $this->Admin){
                        $this->load->library('excel');
                        $this->excel->setActiveSheetIndex(0);
                        $this->excel->getActiveSheet()->setTitle(lang('products'));
                        $this->excel->getActiveSheet()->SetCellValue('A1', lang('barcode_count_stock'));
                        $this->excel->setActiveSheetIndex(0)->mergeCells('A1:F1');
                        $herder = array(
                            'font'  => array(
                                'bold'  => true,
                                'size'  => 12,
                                'name'  => 'Verdana'
                            )
                        );
                        $this->excel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($herder);
                        $this->excel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->SetCellValue('A2', lang('no'));
                        $this->excel->getActiveSheet()->SetCellValue('B2', lang('product_code'));
                        $this->excel->getActiveSheet()->SetCellValue('C2', lang('product_name'));
                        $this->excel->getActiveSheet()->SetCellValue('D2', lang('variant'));
                        $this->excel->getActiveSheet()->SetCellValue('E2', lang('expected'));
                        $this->excel->getActiveSheet()->SetCellValue('F2', lang('counted'));


                        $row = 3;
                        $sum_grand = $balance = $sum_banlance = $sum_deposit =$sum_amount=$sum_return_sale=$sum_discount =$sum_paid = 0;
                        foreach ($_POST['val'] as $id) {
                            $sale = $this->sales_model->getSaleExportByID($id);
                            $sum_amount += $sale->amount;
                            $sum_return_sale += $sale->return_sale;
                            $sum_paid += $sale->paid;
                            $sum_deposit += $sale->deposit;
                            $sum_discount += $sale->discount;
                            $sum_banlance += $sale->balance;
                            $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->erp->hrld($sale->date));
                            $this->excel->getActiveSheet()->SetCellValue('B' . $row, $sale->quote_no." ");
                            $this->excel->getActiveSheet()->SetCellValue('C' . $row, $sale->so_no." ");
                            $this->excel->getActiveSheet()->SetCellValue('D' . $row, $sale->sale_no." ");
                            $this->excel->getActiveSheet()->SetCellValue('E' . $row, $sale->biller);
                            $this->excel->getActiveSheet()->SetCellValue('F' . $row, $sale->areas_group);
                            $new_row = $row+1;
                            $styleArray = array(
                                'alignment' => array(
                                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
                                )
                            );
                            $this->excel->getActiveSheet()->getStyle('J'.$new_row.':O'.$new_row)->applyFromArray($styleArray);
                            $this->excel->getActiveSheet()->SetCellValue('J' . $new_row, $sum_amount);
                            $this->excel->getActiveSheet()->SetCellValue('K' . $new_row, $sum_return_sale);
                            $this->excel->getActiveSheet()->SetCellValue('L' . $new_row, $sum_paid);
                            $this->excel->getActiveSheet()->SetCellValue('M' . $new_row, $sum_deposit);
                            $this->excel->getActiveSheet()->SetCellValue('N' . $new_row, $sum_discount);
                            $this->excel->getActiveSheet()->SetCellValue('O' . $new_row, $sum_banlance);
                            $row++;
                        }
                    }
                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(17);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                    $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'sales_' . date('Y_m_d_H_i_s');

                    if ($this->input->post('form_action') == 'export_excel') {
                        header('Content-Type: application/vnd.ms-excel');
                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
                        header('Cache-Control: max-age=0');
                        $styleArray = array(
                            'font'  => array(
                                'bold'  => true,
                            )
                        );

                        $this->excel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $this->excel->getActiveSheet()->getStyle('J' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('J' . $new_row.'')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('K' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('K' . $new_row.'')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('L' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('L' . $new_row.'')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('M' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('M' . $new_row.'')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('N' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('N' . $new_row.'')->getFont()->setBold(true);
                        $this->excel->getActiveSheet()->getStyle('O' . $new_row.'')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border:: BORDER_THIN);
                        $this->excel->getActiveSheet()->getStyle('O' . $new_row.'')->getFont()->setBold(true);

                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                        return $objWriter->save('php://output');
                    }

                    redirect($_SERVER["HTTP_REFERER"]);
                }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

    public function count_stock($page = NULL)
    {
        $this->erp->checkPermissions('count_stocks', true, 'products');
        $this->form_validation->set_rules('warehouse', lang("warehouse"), 'required');
        $this->form_validation->set_rules('type', lang("type"), 'required');

        if ($this->form_validation->run() == true) {

            $warehouse_id = $this->input->post('warehouse');
            $type = $this->input->post('type');
            $categories = $this->input->post('category') ? $this->input->post('category') : NULL;
            $brands = $this->input->post('brand') ? $this->input->post('brand') : NULL;
            $this->load->helper('string');
            $name = random_string('md5').'.csv';
            $products = $this->products_model->getStockCountProducts($warehouse_id, $type, $categories, $brands);
            $pr = 0; $rw = 0;
            foreach ($products as $product) {
                if ($variants = $this->products_model->getStockCountProductVariants($warehouse_id, $product->id)) {
                    foreach ($variants as $variant) {
                        $items[] = array(
                            'product_code' => $product->code,
                            'product_name' => $product->name,
                            'variant' => $variant->name,
                            'expected' => $variant->quantity,
                            'counted' => ''
                            );
                        $rw++;
                    }
                } else {
                    $items[] = array(
                        'product_code' => $product->code,
                        'product_name' => $product->name,
                        'variant' => '',
                        'expected' => $product->quantity,
                        'counted' => ''
                        );
                    $rw++;
                }
                $pr++;
            }
            if ( ! empty($items)) {
                $csv_file = fopen('./files/'.$name, 'w');
                fputcsv($csv_file, array(lang('product_code'), lang('product_name'), lang('variant'), lang('expected'), lang('counted')));
                foreach ($items as $item) {
                    fputcsv($csv_file, $item);
                }
                // file_put_contents('./files/'.$name, $csv_file);
                // fwrite($csv_file, $txt);
                fclose($csv_file);
            } else {
                $this->session->set_flashdata('error', lang('no_product_found'));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld($this->input->post('date'));
            } else {
                $date = date('Y-m-d H:s:i');
            }
            $category_ids = '';
            $brand_ids = '';
            $category_names = '';
            $brand_names = '';
            if ($categories) {
                $r = 1; $s = sizeof($categories);
                foreach ($categories as $category_id) {
                    $category = $this->site->getCategoryByID($category_id);
                    if ($r == $s) {
                        $category_names .= $category->name;
                        $category_ids .= $category->id;
                    } else {
                        $category_names .= $category->name.', ';
                        $category_ids .= $category->id.', ';
                    }
                    $r++;
                }
            }
            if ($brands) {
                $r = 1; $s = sizeof($brands);
                foreach ($brands as $brand_id) {
                    $brand = $this->site->getBrandByID($brand_id);
                    if ($r == $s) {
                        $brand_names .= $brand->name;
                        $brand_ids .= $brand->id;
                    } else {
                        $brand_names .= $brand->name.', ';
                        $brand_ids .= $brand->id.', ';
                    }
                    $r++;
                }
            }
            $data = array(
                'date' => $date,
                'warehouse_id' => $warehouse_id,
                'reference_no' => $this->input->post('reference_no'),
                'type' => $type,
                'categories' => $category_ids,
                'category_names' => $category_names,
                'brands' => $brand_ids,
                'brand_names' => $brand_names,
                'initial_file' => $name,
                'products' => $pr,
                'rows' => $rw,
                'created_by' => $this->session->userdata('user_id')
            );

        }
        
        if ($this->form_validation->run() == true && $this->products_model->addStockCount($data)) {
            $this->session->set_flashdata('message', lang("stock_count_intiated"));
            redirect('products/list_count_stock');

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['warehouses'] = $this->site->getAllWarehouses();
            $this->data['categories'] = $this->site->getAllCategories();
            $this->data['brands'] = $this->site->getAllBrands();
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('count_stock')));
            $meta = array('page_title' => lang('count_stock'), 'bc' => $bc);
            $this->page_construct('products/count_stock', $meta, $this->data);

        }

    }
    
    public function add_count_stock($page = NULL)
    {
        $this->erp->checkPermissions('count_stocks', true, 'products');
        $this->form_validation->set_rules('warehouse', lang("warehouse"), 'required');
        $this->form_validation->set_rules('type', lang("type"), 'required');
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required|is_unique[stock_counts.reference_no]');

        $this->data['setting'] = $this->site->get_setting();
        if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')){
            $biller_id = $this->site->get_setting()->default_biller;
            $this->data['reference'] = $this->site->getReference('st',$biller_id);
        }else{
            $this->data['reference'] = $this->site->getReference('st',$biller_id);
            $biller_id = $this->session->userdata('biller_id');
        }

        if ($this->form_validation->run() == true)
        {
            $this->load->helper('string');
            $warehouse_id   = $this->input->post('warehouse');
            $type           = $this->input->post('type');
            $categories     = $this->input->post('category') ? $this->input->post('category') : NULL;
            $brands         = $this->input->post('brand') ? $this->input->post('brand') : NULL;
            $name           = random_string('md5').'.csv';
            $products       = $this->products_model->getStockCountProducts($warehouse_id, $type, $categories, $brands);

            $pr = 0; 
            $rw = 0;
            foreach ($products as $product) {
                if ($variants = $this->products_model->getStockCountProductVariants($warehouse_id, $product->id)) {
                    foreach ($variants as $variant) {
                        $variant_qty = $this->erp->convert_unit_by_variant($product->id, $product->quantity)[$variant->name];
                        $items[] = array(
                            'product_code' => $product->code."@",
                            'product_name' => $product->name,
                            'variant'      => $variant->name,
                            'expected'     => $variant_qty,
                            'counted'      => ''
                            );
                        $rw++;
                    }
                } else {
                    $items[] = array(
                        'product_code' => $product->code."@",
                        'product_name' => $product->name,
                        'variant'      => '',
                        'expected'     => $product->quantity,
                        'counted'      => ''
                        );
                    $rw++;
                }
                $pr++;
            }
            
            if ( ! empty($items)) {
                $csv_file = fopen('./files/'.$name, 'w');
                fprintf($csv_file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($csv_file, array(lang('product_code'), lang('product_name'), lang('variant'), lang('expected'), lang('counted')));
                foreach ($items as $item) {                 
                    fputcsv($csv_file, $item);
                }
                fclose($csv_file);
            } else {
                $this->session->set_flashdata('error', lang('no_product_found'));
                redirect($_SERVER["HTTP_REFERER"]);
            }

            if ($this->Owner || $this->Admin) {
                $date = $this->erp->fld($this->input->post('date'));
            } else {
                $date = date('Y-m-d H:s:i');
            }
            $category_ids = '';
            $brand_ids = '';
            $category_names = '';
            $brand_names = '';
            if ($categories) {
                $r = 1; $s = sizeof($categories);
                foreach ($categories as $category_id) {
                    $category = $this->site->getCategoryByID($category_id);
                    if ($r == $s) {
                        $category_names .= $category->name;
                        $category_ids .= $category->id;
                    } else {
                        $category_names .= $category->name.', ';
                        $category_ids .= $category->id.', ';
                    }
                    $r++;
                }
            }
            if ($brands) {
                $r = 1; $s = sizeof($brands);
                foreach ($brands as $brand_id) {
                    $brand = $this->site->getBrandByID($brand_id);
                    if ($r == $s) {
                        $brand_names .= $brand->name;
                        $brand_ids .= $brand->id;
                    } else {
                        $brand_names .= $brand->name.', ';
                        $brand_ids .= $brand->id.', ';
                    }
                    $r++;
                }
            }
            $bill_id = JSON_decode($biller_id);
            $data = array(
                'date'              => $date,
                'biller_id'         => $this->Owner || $this->Admin ? $biller_id : $bill_id[0],
                'warehouse_id'      => $warehouse_id,
                'reference_no'      => $this->input->post('reference_no'),
                'type'              => $type,
                'categories'        => $category_ids,
                'category_names'    => $category_names,
                'brands'            => $brand_ids,
                'brand_names'       => $brand_names,
                'initial_file'      => $name,
                'products'          => $pr,
                'rows'              => $rw,
                'created_by'        => $this->session->userdata('user_id')
            );
        }
        if ($this->form_validation->run() == true && $this->products_model->addStockCount($data)) {
            $this->session->set_flashdata('message', lang("stock_count_intiated"));
            redirect('products/list_count_stock');

        } else {
            $this->data['error']        = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            if (!$this->Owner && !$this->Admin) {
                $warehouses             = $this->site->getUserWarehouses();
            } else {
                $warehouses             = $this->site->getAllWarehouses();
            }
            $this->data['warehouses']   = $warehouses;
            $this->data['categories']   = $this->site->getAllCategories();
            $this->data['brands']       = $this->site->getAllBrands();

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('add_product_count')));
            $meta = array('page_title' => lang('add_product_count'), 'bc' => $bc);
            $this->page_construct('products/add_count_stock', $meta, $this->data);
        }
    }
    
    public function finalize_count($id)
    {
        $this->erp->checkPermissions('count_stocks', true, 'products');
        $stock_count = $this->products_model->getStouckCountByID($id);
        if ( ! $stock_count || $stock_count->finalized) {
            $this->session->set_flashdata('error', lang("stock_count_finalized"));
            redirect('products/list_count_stock');
        }

        $this->form_validation->set_rules('count_id', lang("count_stock"), 'required');

        if ($this->form_validation->run() == true) {

            if ($_FILES['csv_file']['size'] > 0) {
                $note = $this->erp->clear_tags($this->input->post('note'));
                $data = array(
                    'updated_by' => $this->session->userdata('user_id'),
                    'updated_at' => date('Y-m-d H:s:i'),
                    'note' => $note
                );

                $this->load->library('upload');
                $config['upload_path'] = $this->digital_upload_path;
                $config['allowed_types'] = 'csv';
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = false;
                $config['encrypt_name'] = true;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('csv_file')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen($this->digital_upload_path . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 5000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }

                $titles = array_shift($arrResult);
                $keys = array('product_code', 'product_name', 'product_variant', 'expected', 'counted');
                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2; $differences = 0; $matches = 0;
                foreach ($final as $pr) {
                    
                    if ($product = $this->products_model->getProductByCode(trim($pr['product_code']))) {
                        $pr['counted'] = !empty($pr['counted']) ? $pr['counted'] : 0;
                        if ($pr['expected'] == $pr['counted']) {
                            $matches++;
                        } else {
                            $pr['stock_count_id'] = $id;
                            $pr['product_id'] = $product->id;
                            $pr['cost'] = $product->cost;
                            $pr['product_variant_id'] = empty($pr['product_variant']) ? NULL : $this->products_model->getProductVariantID($pr['product_id'], $pr['product_variant']);
                            $products[] = $pr;
                            $differences++;
                        }
                    } else {
                        $this->session->set_flashdata('error', lang('check_product_code') . ' (' . $pr['product_code'] . '). ' . lang('product_code_x_exist') . ' ' . lang('line_no') . ' ' . $rw);
                        redirect('products/finalize_count/'.$id);
                    }
                    $rw++;
                }
                $data['final_file'] = $csv;
                $data['differences'] = $differences;
                $data['matches'] = $matches;
                $data['missing'] = $stock_count->rows-($rw-2);
                $data['finalized'] = 1;
            }
        }
        if ($this->form_validation->run() == true && $this->products_model->finalizeStockCount($id, $data, $products)) {
            $this->session->set_flashdata('message', lang("stock_count_finalized"));
            redirect('products/list_count_stock');
        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['stock_count'] = $stock_count;
            $this->data['warehouse'] = $this->site->getWarehouseByID($stock_count->warehouse_id);
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => site_url('products/stock_counts'), 'page' => lang('stock_counts')), array('link' => '#', 'page' => lang('finalize_count')));
            $meta = array('page_title' => lang('finalize_count'), 'bc' => $bc);
            $this->page_construct('products/finalize_count', $meta, $this->data);
        }

    }
    
    public function adjust_cost()
    {
        $this->erp->checkPermissions();
        $this->form_validation->set_rules('warehouse', lang("warehouse"), 'required');
        $this->form_validation->set_rules('reference_no', lang("reference_no"), 'required');
        $this->form_validation->set_rules('start_date', lang("start_date"), 'required');
        $this->form_validation->set_rules('end_date', lang("end_date"), 'required');
        $this->form_validation->set_rules('project', lang("project"), 'required');
        $this->form_validation->set_rules('adjust_by', lang("adjust_by"), 'required');
        
        if ($this->form_validation->run() == true) {
            
        }else{
            $this->data['setting'] = $this->site->get_setting();
            if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')){
                $biller_id = $this->site->get_setting()->default_biller;
                $this->data['reference'] = $this->site->getReference('adc',$biller_id);
            }else{
                $biller_id = $this->session->userdata('biller_id');
                $this->data['reference'] = $this->site->getReference('adc',$biller_id);
                
            }
            
            $this->data['allusers']    = $this->site->getAllUsers();
            $this->data['biller']      = $this->site->getAllBiller();
            $this->data['warehouses']  = $this->site->getAllWarehouses();
            
            $bc     = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('adjust_cost')));
            $meta   = array('page_title' => lang('adjust_cost'), 'bc' => $bc);
            $this->page_construct('products/adjust_cost', $meta, $this->data);
        }
    }
    
    public function adjust_suggestions()
    {
        $term = $this->input->get('term', TRUE);
        $warehouse_id = $this->input->get('warehouse_id', true);
        if (strlen($term) < 1 || !$term) {
            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");
        }

        $rows = $this->products_model->getProductNames($term, $warehouse_id);
        if ($rows) {
            foreach ($rows as $row) {
                $pr[] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'code' => $row->code, 'name' => $row->name, 'price' => $row->price, 'qty' => 1, 'unit' => $row->unit, 'cost' => $row->cost);
            }
            echo json_encode($pr);
        } else {
            echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
        }
    }
    
    function getAddress($plan = NULL)
    {
        if ($rows = $this->products_model->getAddressById($plan)) {
            $data = json_encode($rows);
        } else {
            $data = false;
        }
        echo $data;
    }
    
    function getReferenceByProject($field,$biller_id)
    {
        $reference_no = $this->site->getReference($field,$biller_id);
        echo json_encode($reference_no);
    }
    
    public function return_using_stock($id)
    {
        $this->erp->checkPermissions('using_stock',null,'products');
        $this->form_validation->set_rules('from_location', lang("from_location"), 'required');
        if ($this->form_validation->run() == true) {
        
            if($this->Owner || $this->Admin){
                $date       = $this->erp->fld($this->input->post('date'));
            } else {
                $date       = date('Y-m-d');
            }
            isCloseDate(date('Y-m-d', strtotime($date)));
            $authorize      = $this->input->post('authorize_id');
            $account        = $this->input->post('account');
            $reference_no   = $this->input->post('reference_no');
            $return_ref     = $this->input->post('return_reference_no');
            $employee_id    = $this->input->post('employee_id');
            $customer_id    = $this->input->post('customer');
            $plan           = $this->input->post('plan');
            $address        = $this->input->post('address');
            $warehouse_id   = $this->input->post('from_location');
            $shop           = $this->input->post('shop');
            $note           = $this->input->post('note');
            $total_item_cost= 0;

            $i              = sizeof($_POST['product_id']);
            for ($r = 0; $r < $i; $r++) {
                $product_id     = $_POST['product_id'][$r];
                $product_code   = $_POST['item_code'][$r];
                $product_name   = $_POST['name'][$r];
                $product_cost   = $_POST['cost'][$r];
                $description    = $_POST['description'][$r];
                $qty_use        = $_POST['qty_use'][$r];
                $qty_old        = $_POST['qty_old'][$r];
                $unit           = $_POST['unit'][$r];
                $expiry         = isset($_POST['exp'][$r]);
                $qty_balance    = $qty_use;
                $option_id      = '';
                $total_cost     = $product_cost * $qty_balance; 
                
                $variant        = $this->site->getProductVariantByID($product_id, $unit);
                if ($variant) {
                    $qty_balance    = $qty_use * $variant->qty_unit;
                    $option_id      = $variant->id;
                    $total_cost     = $product_cost * $qty_balance;
                        
                }
                
                /*$warehouse        = $this->site->getWarehouseQty($product_id, $warehouse_id);
                if(($warehouse->quantity + $qty_old) < $qty_balance){
                    $this->session->set_flashdata('error', $this->lang->line("quantity_bigger") );
                    redirect($_SERVER["HTTP_REFERER"]);
                }*/

                $item_data[] = array(
                    'product_id'    => $product_id,
                    'code'          => $product_code,
                    'product_name'  => $product_name,
                    'description'   => $description,
                    'qty_use'       => $qty_balance,
                    'qty_by_unit'   => $qty_use,
                    'unit'          => $unit,
                    'expiry'        => $expiry,
                    'warehouse_id'  => $warehouse_id,
                    'cost'          => $product_cost,
                    'reference_no'  => $return_ref,
                    'option_id'     => $option_id
                );
                
                $total_item_cost+= $total_cost;
            }
            
            if (empty($item_data)) {
                $this->session->set_flashdata('error', $this->lang->line("no_data_select") );
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                krsort($item_data);
            }
            
            $data = array(
                'id'                    => $id,
                'date'                  => $date,
                'using_reference_no'    => $reference_no,
                'reference_no'          => $return_ref,
                'warehouse_id'          => $warehouse_id,
                'authorize_id'          => $authorize,
                'employee_id'           => $employee_id,
                'customer_id'           => $customer_id,
                'shop'                  => $shop,
                'account'               => $account,
                'note'                  => $note,
                'create_by'             => $this->session->userdata('user_id'),
                'type'                  => 'return',
                'total_cost'            => $total_item_cost,
                'plan_id'               => $plan,
                'address_id'            => $address
            );
        }
        
        if ($this->form_validation->run() == true) {
            $return_id = $this->products_model->returnUsingStock($data, $item_data);
            optimizeUsing(date('Y-m-d', strtotime($date)));
            $this->session->set_flashdata(lang('enter_using_stock_return_added.'));
            $r_r=str_replace("/","-",$return_id);
            redirect('products/print_enter_using_stock_return/'.$r_r);
        } else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $getUsingStock             = $this->products_model->getUsingStockById($id);
            $this->data['using_stock'] = $getUsingStock;
            if ($this->Owner || $this->Admin || !$this->session->userdata('biller_id')) {
                $biller_id = $this->site->get_setting()->default_biller;
                $this->data['ref_return']   = $this->site->getReference('esr', $biller_id);
            } else {
                $biller_id = $this->session->userdata('biller_id');
                $this->data['ref_return']   = $this->site->getReference('esr', $biller_id);
            }
            
            $this->data['authorize_by']= $this->site->getAllUsers();
            $this->data['accounting']  = $this->products_model->getGLChart();
            $this->data['warehouses']  = $this->site->getAllWarehouses();
            $this->data['plan']        = $this->products_model->getPlan();
            $this->data['employees']   = $this->site->getAllEmployee();
            $this->data['biller']      = $this->site->getAllBiller();
            $this->data['id']          = $id;
            
            //=============== Get Item ===============//
            $wh_id          = $getUsingStock->warehouse_id;
            $reference_no   = $getUsingStock->reference_no;
            $getUsingStockItem          = $this->products_model->getUsingStockItemsByRef($reference_no);
            $c = str_replace(".", "", microtime(true));
            $r = 0;
            foreach ($getUsingStockItem as $row) {
                $option_unit        = $this->products_model->getUnitAndVaraintByProductId($row->id);
                $opt_pro            = $this->products_model->getProductVariantByOptionID($row->option_id);
                
                if ($getUsingStock->plan_id) {
                    $project_item       = $this->products_model->getPlanUsing($getUsingStock->plan_id, $row->product_code, $getUsingStock->address_id);
                    $row->project_qty   = ($project_item->quantity_balance - $project_item->using_qty);

                    $row->have_plan = 0;
                    if($project_item){
                        $row->have_plan = 1;
                    }
                }
                
                $expiry_date        = $this->site->getProductExpireDate($row->id, $wh_id);
                
                if ($opt_pro) {
                    $row->qty_use = $row->qty_use / $opt_pro->qty_unit; 
                } else {
                    $row->qty_use = $row->qty_use;  
                }   

                $row->qty_old = $row->qty_use;          
                
                $pr[] = array('id' => ($c + $r), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->product_code . ")", 'row' => $row, 'option_unit' => $option_unit, 'project_qty' => isset($row->project_qty),'stock_item' => $row->e_id, 'expiry_date' => $expiry_date);
                $r++;
            
            }
            
            $this->data['items'] = json_encode($pr);
            
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('products'), 'page' => lang('products')), array('link' => '#', 'page' => lang('return_using_stock')));
            $meta = array('page_title' => lang('return_using_stock'), 'bc' => $bc);
            $this->page_construct('products/return_using_stock', $meta, $this->data);
        }
        
    }
    public function product_history($product=NULL){
        $this->load->library('datatables');
        if($this->input->get('product')){
            $product_id=$this->input->get('product');
        }
        if($product_id){
            $this->datatables
                ->select("products_audit.created_at,
                users.username,
                erp_products_audit.name,
                products_audit.name_kh,
                erp_categories.name as category,
                erp_subcategories.name as subcategory,
                erp_units.name as product_unit,
                tax_rates.name as tax_name,
                IF(erp_products_audit.tax_method=0,'INCLUSIVE','EXCLUSIVE'),
                products_audit.cost,
                products_audit.price,
                erp_products_audit.action")
                ->from("erp_products_audit")
                ->join("categories","categories.id=products_audit.category_id","LEFT")
                ->join("subcategories","subcategories.id=products_audit.subcategory_id","LEFT")
                ->join("users","products_audit.updated_by=users.id","LEFT")
                ->join("units","products_audit.unit=units.id","LEFT")
                ->join("tax_rates","products_audit.tax_rate=tax_rates.id","LEFT")
                ->where("products_audit.product_id",$product_id);
        }
        echo $this->datatables->generate();
    }
    
}