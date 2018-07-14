<?php defined('BASEPATH') OR exit('No direct script access allowed');
/* 
 *  ============================================================================== 
 *  Author	: CloudNET Team
 *  Email	: icloud.erp@gmail.com 
 *  For		: PHPExcel
 *  Web		: https://mpdf1.com
 *  License	: GPL
 *		: http://www.opensource.org/licenses/gpl-license.php
 *  ============================================================================== 
 */
require_once APPPATH . "/third_party/MPDF/mpdf.php";

class Pdf extends mPDF
{
    public function __construct()
    {
        parent::__construct();
    }
}
