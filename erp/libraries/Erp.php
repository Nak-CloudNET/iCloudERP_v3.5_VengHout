<?php defined('BASEPATH') or exit('No direct script access allowed');


class Erp
{
	//********* Kindly to inform for beautiful code first before coding , invoid from messy coding ******/
	
    public function __get($var)
    {
        return get_instance()->$var;
    }

    private function _rglobRead($source, &$array = array())
    {
        if (!$source || trim($source) == "") {
            $source = ".";
        }
        foreach ((array) glob($source . "/*/") as $key => $value) {
            $this->_rglobRead(str_replace("//", "/", $value), $array);
        }
        $hidden_files = glob($source . ".*") and $htaccess = preg_grep('/\.htaccess$/', $hidden_files);
        $files = array_merge(glob($source . "*.*"), $htaccess);
        foreach ($files as $key => $value) {
            $array[] = str_replace("//", "/", $value);
        }
    }
	
	public function convertImageSpecialChar($str)
    {
        $name = explode('.', $str);
        $name_last = array_pop($name);
        $names = array(implode('-', $name), $name_last);

        $string = str_replace(' ', '-', $names[0]); 
        $strings = preg_replace('/[^A-Za-z0-9\-]/', '', $string);

        $img = $strings.'.'.$name_last;
        return $img;
    }
	
    private function _zip($array, $part, $destination, $output_name = 'erp')
    {
        $zip = new ZipArchive;
        @mkdir($destination, 0777, true);

        if ($zip->open(str_replace("//", "/", "{$destination}/{$output_name}" . ($part ? '_p' . $part : '') . ".zip"), ZipArchive::CREATE)) {
            foreach ((array) $array as $key => $value) {
                $zip->addFile($value, str_replace(array("../", "./"), null, $value));
            }
            $zip->close();
        }
    }
	
	public function formatMoneyPurchase($number)
    {
        if ($this->Settings->sac) {
            return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
            $this->formatSAC($this->formatDecimal($number)) .
            ($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
        }
        $decimals = $this->Settings->purchase_decimals;
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
        number_format($number, $decimals, $ds, $ts) .
        ($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
    }
	
	function KhmerMonth($m)
	{
		if($m==1){
			return "មករា";
		}else if($m==2){
			return "កុម្ភៈ";
		}else if($m==3){
			return "មិនា";
		}else if($m==4){
			return "មេសា";
		}else if($m==5){
			return "ឧសភា";
		}else if($m==6){
			return "មិថុនា";
		}else if($m==7){
			return "កក្កដា";
		}else if($m==8){
			return "សីហា";
		}else if($m==9){
			return "កញ្ញា";
		}else if($m==10){
			return "តុលា";
		}else if($m==11){
			return "វិច្ឆិកា";
		}else if($m==12){
			return "ធ្នូ";
		}
	}

	function KhmerNumDate ($numDate)
	{
		$numDate = str_replace('1', '១', $numDate);
		$numDate = str_replace('2', '២', $numDate);
		$numDate = str_replace('3', '៣', $numDate);
		$numDate = str_replace('4', '៤', $numDate);
		$numDate = str_replace('5', '៥', $numDate);
		$numDate = str_replace('6', '៦', $numDate);
		$numDate = str_replace('7', '៧', $numDate);
		$numDate = str_replace('8', '៨', $numDate);
		$numDate = str_replace('9', '៩', $numDate);
		$numDate = str_replace('0', '០', $numDate); 
		return $numDate;
	}
	
    public function formatMoney($number)
    {
        if ($this->Settings->sac) {
            return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
            $this->formatSAC($this->formatDecimal($number)) .
            ($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
        }
        $decimals = $this->Settings->decimals;
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return ($this->Settings->display_symbol == 1 ? $this->Settings->symbol : '') .
        number_format($number, $decimals, $ds, $ts) .
        ($this->Settings->display_symbol == 2 ? $this->Settings->symbol : '');
    }

    public function formatQuantity($number, $decimals = null)
    {
        if (!$decimals) {
            $decimals = $this->Settings->qty_decimals;
        }
        if ($this->Settings->sac) {
            return $this->formatSAC($this->formatDecimal($number, $decimals));
        }
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return number_format($number, $decimals, $ds, $ts);
    }

    public function formatNumber($number, $decimals = null)
    {
        if (!$decimals) {
            $decimals = $this->Settings->decimals;
        }
        if ($this->Settings->sac) {
            return $this->formatSAC($this->formatDecimal($number, $decimals));
        }
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return number_format($number, $decimals, $ds, $ts);
    }

    public function formatDecimal($number, $decimals = null)
    {
        if (!is_numeric($number)) {
            return null;
        }
        if (!$decimals) {
            $decimals = $this->Settings->decimals;
        }
        return number_format($number, $decimals, '.', '');
    }
	
	public function formatPurDecimal($number, $decimals = null)
    {
        if (!is_numeric($number)) {
            return null;
        }
        if (!$decimals) {
            $decimals = $this->Settings->purchase_decimals;
        }
        return number_format($number, $decimals, '.', '');
    }
    
    public function clear_tags($str)
    {
        return htmlentities(
            strip_tags($str,
                '<span><div><a><br><p><b><i><u><img><blockquote><small><ul><ol><li><hr><big><pre><code><strong><em><table><tr><td><th><tbody><thead><tfoot><h3><h4><h5><h6>'
            ),
            ENT_QUOTES | ENT_XHTML | ENT_HTML5,
            'UTF-8'
        );
    }

    public function decode_html($str)
    {
        return html_entity_decode($str, ENT_QUOTES | ENT_XHTML | ENT_HTML5, 'UTF-8');
    }

    public function roundMoney($num, $nearest = 0.05)
    {
        return round($num * (1 / $nearest)) * $nearest;
    }

    public function roundNumber($number, $toref = null)
    {
        switch ($toref) {
            case 1:
                $rn = round($number * 20) / 20;
                break;
            case 2:
                $rn = round($number * 2) / 2;
                break;
            case 3:
                $rn = round($number);
                break;
            case 4:
                $rn = ceil($number);
                break;
            default:
                $rn = $number;
        }
        return $rn;
    }

    public function unset_data($ud)
    {
        if ($this->session->userdata($ud)) {
            $this->session->unset_userdata($ud);
            return true;
        }
        return false;
    }

    public function hrsd($sdate)
    {
        if ($sdate) {
            return date($this->dateFormats['php_sdate'], strtotime($sdate));
        } else {
            return '0000-00-00';
        }
    }

    public function hrld($ldate)
    {
        if ($ldate) {
            return date($this->dateFormats['php_ldate'], strtotime($ldate));
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function fsd($inv_date)
    {
        if ($inv_date) {
            $jsd = $this->dateFormats['js_sdate'];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2);
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2);
            } else {
                $date = $inv_date;
            }
            return $date;
        } else {
            return '0000-00-00';
        }
    }

    public function fld($ldate)
    {
		
        if ($ldate) {
            $date = explode(' ', $ldate);
            $jsd = $this->dateFormats['js_sdate'];
			$inv_date = $date[0];
			$time = "";
			if(isset($date[1])){
				$time = $date[1];
			}
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2) . " " . $time;
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2) . " " . $time;
			}elseif($jsd == 'yyyy-mm-dd'){
				$date = $inv_date . ' ' . $time;
			} else {
                $date = $inv_date;
            }
			
			/* Error date 0000-00-00 00:00:00 */
            //return $date." ".$time;
			return $date;
            
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)
    {
        $this->load->library('email');
        $config['useragent'] = "Stock Manager Advance";
        $config['protocol'] = $this->Settings->smtp_crypto;
        $config['mailtype'] = "html";
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        if ($this->Settings->protocol == 'sendmail') {
            $config['mailpath'] = $this->Settings->mailpath;
        } elseif ($this->Settings->protocol == 'smtp') {
            $this->load->library('encrypt');
            $config['smtp_host'] = $this->Settings->smtp_host;
            $config['smtp_user'] = $this->Settings->smtp_user;
            $config['smtp_pass'] = $this->encrypt->decode($this->Settings->smtp_pass);
            $config['smtp_port'] = $this->Settings->smtp_port;
            if (!empty($this->Settings->smtp_crypto)) {
                $config['smtp_crypto'] = $this->Settings->smtp_crypto;
            }
        }

        $this->email->initialize($config);

        if ($from && $from_name) {
            $this->email->from($from, $from_name);
        } elseif ($from) {
            $this->email->from($from, $this->Settings->site_name);
        } else {
            $this->email->from($this->Settings->default_email, $this->Settings->site_name);
        }

        $this->email->to($to);
        if ($cc) {
            $this->email->cc($cc);
        }
        if ($bcc) {
            $this->email->bcc($bcc);
        }
        $this->email->subject($subject);
        $this->email->message($message);
        if ($attachment) {
            if (is_array($attachment)) {
                foreach ($attachment as $file) {
                    $this->email->attach($file);
                }
            } else {
                $this->email->attach($attachment);
            }
        }

        if ($this->email->send()) {
            //echo $this->email->print_debugger(); die();
            return true;
        } else {
            //echo $this->email->print_debugger(); die();
            return false;
        }
    }

    public function checkPermissions($action = null, $js = null, $module = null)
    {
        if (!$this->actionPermissions($action, $module)) {
            $this->session->set_flashdata('error', lang("access_denied"));
            if ($js) {
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
            } else {
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
            }
        }
    }

    public function actionPermissions($action = null, $module = null)
    {
        if ($this->Owner || $this->Admin) {
            if ($this->Admin && stripos($action, 'delete') !== false) {
                return false;
            }
            return true;
        } elseif ($this->Customer || $this->Supplier) {
			return false;
        } else {
            if (!$module) {
                $module = $this->m;
            }
            if (!$action) {
                $action = $this->v;
            }
            
            if ($this->GP[$module . '-' . $action] == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function save_barcode($text = null, $bcs = 'code128', $height = 56, $stext = 1, $sq = null)
    {
        $file_name = 'assets/uploads/barcode' . $this->session->userdata('user_id') . ($sq ? $sq : '') . '.png';
        $drawText = ($stext != 1) ? false : true;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $text, 'barHeight' => $height, 'drawText' => $drawText, 'factor' => 1);
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        $image = Zend_Barcode::draw($bcs, 'image', $barcodeOptions, $rendererOptions);
        if (imagepng($image, $file_name)) {
            imagedestroy($image);
            $bc = file_get_contents($file_name);
            $bcimage = base64_encode($bc);
            return $bcimage;
        }
        return false;
    }

    public function qrcode($type = 'text', $text = 'PHP QR Code', $size = 2, $level = 'H', $sq = null)
    {
        $file_name = 'assets/uploads/qrcode' . $this->session->userdata('user_id') . ($sq ? $sq : '') . '.png';
        if ($type == 'link') {
            $text = urldecode($text);
        }
        $this->load->library('phpqrcode');
        $config = array('data' => $text, 'size' => $size, 'level' => $level, 'savename' => $file_name);
		$this->phpqrcode->generate($config);
        $qr = file_get_contents($file_name);
        $qrimage = base64_encode($qr);
        return $qrimage;
    }

    public function generate_pdf($content, $name = 'download.pdf', $output_type = null, $footer = null, $margin_bottom = null, $header = null, $margin_top = null, $orientation = 'P')
    {
        if (!$output_type) {
            $output_type = 'D';
        }
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 20;
        }
        $this->load->library('pdf');
        $pdf = new mPDF('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 9, 9);
        $pdf->debug = false;
        $pdf->autoScriptToLang = true;
        $pdf->autoLangToFont = true;
        $pdf->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$pdf->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $pdf->SetTitle($this->Settings->site_name);
        $pdf->SetAuthor($this->Settings->site_name);
        $pdf->SetCreator($this->Settings->site_name);
        $pdf->SetDisplayMode('fullpage');
        $stylesheet = file_get_contents('assets/bs/bootstrap.min.css');
        $pdf->WriteHTML($stylesheet, 1);
        // $pdf->SetFooter($this->Settings->site_name.'||{PAGENO}/{nbpg}', '', TRUE); // For simple text footer

        if (is_array($content)) {
            $pdf->SetHeader($this->Settings->site_name.'||{PAGENO}/{nbpg}', '', TRUE); // For simple text header
            $as = sizeof($content);
            $r = 1;
            foreach ($content as $page) {
                $pdf->WriteHTML($page['content']);
                if (!empty($page['footer'])) {
                    $pdf->SetHTMLFooter('<p class="text-center">' . $page['footer'] . '</p>', '', true);
                }
                if ($as != $r) {
                    $pdf->AddPage();
                }
                $r++;
            }

        } else {

            $pdf->WriteHTML($content);
            if ($header != '') {
                $pdf->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', true);
            }
            if ($footer != '') {
                $pdf->SetHTMLFooter('<p class="text-center">' . $footer . '</p>', '', true);
            }

        }

        if ($output_type == 'S') {
            $file_content = $pdf->Output('', 'S');
            write_file('assets/uploads/' . $name, $file_content);
            return 'assets/uploads/' . $name;
        } else {
            $pdf->Output($name, $output_type);
        }
    }

    public function print_arrays()
    {
        $args = func_get_args();
        echo "<pre>";
        foreach ($args as $arg) {
            print_r($arg);
        }
        echo "</pre>";
        die();
    }

    public function logged_in()
    {
        return (bool) $this->session->userdata('identity');
    }

    public function in_group($check_group, $id = false)
    {
        if ( ! $this->logged_in()) {
            return false;
        }
        $id || $id = $this->session->userdata('user_id');
        $group = $this->site->getUserGroup($id);
        if ($group->name === $check_group) {
            return true;
        }
        return false;
    }

    public function log_payment($msg, $val = null)
    {
        $this->load->library('logs');
        return (bool) $this->logs->write('payments', $msg, $val);
    }

    public function update_award_points($total, $customer, $user, $scope = null, $saleman = NULL)
    {
        if (!empty($this->Settings->each_spent) && $total >= $this->Settings->each_spent) {
            $company = $this->site->getCompanyByID($customer);
            $points = floor(($total / $this->Settings->each_spent) * $this->Settings->ca_point);
            $total_points = $scope ? $company->award_points - $points : $company->award_points + $points;
            $this->db->update('companies', array('award_points' => $total_points), array('id' => $customer));
        }
        if($saleman){
            if (!empty($this->Settings->each_sale) && !$this->Customer && $total >= $this->Settings->each_sale) {
                $staff = $this->site->getUser($saleman);
                $points = floor(($total / $this->Settings->each_sale) * $this->Settings->sa_point);
                $total_points = $scope ? $staff->award_points - $points : $staff->award_points + $points;
                $this->db->update('users', array('award_points' => $total_points), array('id' => $saleman));
            }
        }else{
            if (!empty($this->Settings->each_sale) && !$this->Customer && $total >= $this->Settings->each_sale) {
                $staff = $this->site->getUser($user);
                $points = floor(($total / $this->Settings->each_sale) * $this->Settings->sa_point);
                $total_points = $scope ? $staff->award_points - $points : $staff->award_points + $points;
                $this->db->update('users', array('award_points' => $total_points), array('id' => $user));
            }
        }
        return true;
    }

    public function zip($source = null, $destination = "./", $output_name = 'erp', $limit = 5000)
    {
        if (!$destination || trim($destination) == "") {
            $destination = "./";
        }

        $this->_rglobRead($source, $input);
        $maxinput = count($input);
        $splitinto = (($maxinput / $limit) > round($maxinput / $limit, 0)) ? round($maxinput / $limit, 0) + 1 : round($maxinput / $limit, 0);

        for ($i = 0; $i < $splitinto; $i++) {
            $this->_zip(array_slice($input, ($i * $limit), $limit, true), $i, $destination, $output_name);
        }

        unset($input);
        return;
    }

    public function unzip($source, $destination = './')
    {

        // @chmod($destination, 0777);
        $zip = new ZipArchive;
        if ($zip->open(str_replace("//", "/", $source)) === true) {
            $zip->extractTo($destination);
            $zip->close();
        }
        // @chmod($destination,0755);

        return true;
    }

    public function view_rights($check_id, $js = null)
    {
        if (!$this->Owner && !$this->Admin) {
            if ($check_id != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('warning', $this->data['access_denied']);
                if ($js) {
                    die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome') . "'; }, 10);</script>");
                } else {
                    redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
                }
            }
        }
        return true;
    }

    public function makecomma($input)
    {
        if (strlen($input) <= 2) {return $input;}
        $length = substr($input, 0, strlen($input) - 2);
        $formatted_input = $this->makecomma($length) . "," . substr($input, -2);
        return $formatted_input;
    }

    public function formatSAC($num)
    {
        $pos = strpos((string) $num, ".");
        if ($pos === false) {$decimalpart = "00";} else {
            $decimalpart = substr($num, $pos + 1, 2);
            $num = substr($num, 0, $pos);}

        if (strlen($num) > 3 & strlen($num) <= 12) {
            $last3digits = substr($num, -3);
            $numexceptlastdigits = substr($num, 0, -3);
            $formatted = $this->makecomma($numexceptlastdigits);
            $stringtoreturn = $formatted . "," . $last3digits . "." . $decimalpart;
        } elseif (strlen($num) <= 3) {
            $stringtoreturn = $num . "." . $decimalpart;
        } elseif (strlen($num) > 12) {
            $stringtoreturn = number_format($num, 2);
        }

        if (substr($stringtoreturn, 0, 2) == "-,") {$stringtoreturn = "-" . substr($stringtoreturn, 2);}

        return $stringtoreturn;
    }
	
	public function md($page = FALSE)
    {
        die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . ($page ? site_url($page) : (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome')) . "'; }, 10);</script>");
    }

    public function analyze_term($term)
    {
        $spos = strpos($term, $this->Settings->barcode_separator);
        if ($spos !== false) {
            $st = explode($this->Settings->barcode_separator, $term);
            $sr = trim($st[0]);
            $option_id = trim($st[1]);
        } else {
            $sr = $term;
            $option_id = false;
        }
        return array('term' => $sr, 'option_id' => $option_id);
    }

    public function paid_opts($paid_by = null, $purchase = false)
    {
        $opts = '
        <option value="cash"'.($paid_by && $paid_by == 'cash' ? ' selected="selected"' : '').'>'.lang("cash").'</option>
        <option value="gift_card"'.($paid_by && $paid_by == 'gift_card' ? ' selected="selected"' : '').'>'.lang("gift_card").'</option>
        <option value="CC"'.($paid_by && $paid_by == 'CC' ? ' selected="selected"' : '').'>'.lang("CC").'</option>
        <option value="Cheque"'.($paid_by && $paid_by == 'Cheque' ? ' selected="selected"' : '').'>'.lang("cheque").'</option>
        <option value="other"'.($paid_by && $paid_by == 'other' ? ' selected="selected"' : '').'>'.lang("other").'</option>';
        if (!$purchase) {
            $opts .= '<option value="deposit"'.($paid_by && $paid_by == 'deposit' ? ' selected="selected"' : '').'>'.lang("deposit").'</option>';
        }
        return $opts;
    }

    public function send_json($data)
    {
        header('Content-Type: application/json');
        die(json_encode($data));
        exit;
    }
    
	public function fraction($num)
	{
		$intpart = floor( $num );
		$fraction = $num - $intpart;
		return $this->formatDecimal($fraction);
	}
    
    public function floorFigure($figure, $decimals)
	{
        if(!$decimals){
            $decimals = 2;
        }
        return number_format((floor($figure*100)/100), $decimals);
    }
	
	function numberOfDecimals($value)
	{
		if ((int)$value == $value)
		{
			return 0;
		}
		else if (! is_numeric($value))
		{
			// throw new Exception('numberOfDecimals: ' . $value . ' is not a number!');
			return false;
		}

		return strlen($value) - strrpos($value, '.') - 1;
	}
    
    public function removeComma($str)
	{
        return number_format(preg_replace("/[^0-9,.]/", "", $str));
    }

	function convert_number_to_words($number) 
	{
		
		$number = str_replace(',','',$number)-0;
   
		$hyphen      = '-';
		$conjunction = ' and ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' point ';
		$dictionary  = array(
			0                   => 'zero',
			1                   => 'one',
			2                   => 'two',
			3                   => 'three',
			4                   => 'four',
			5                   => 'five',
			6                   => 'six',
			7                   => 'seven',
			8                   => 'eight',
			9                   => 'nine',
			10                  => 'ten',
			11                  => 'eleven',
			12                  => 'twelve',
			13                  => 'thirteen',
			14                  => 'fourteen',
			15                  => 'fifteen',
			16                  => 'sixteen',
			17                  => 'seventeen',
			18                  => 'eighteen',
			19                  => 'nineteen',
			20                  => 'twenty',
			30                  => 'thirty',
			40                  => 'forty',
			50                  => 'fifty',
			60                  => 'sixty',
			70                  => 'seventy',
			80                  => 'eighty',
			90                  => 'ninety',
			100                 => 'hundred',
			1000                => 'thousand',
			1000000             => 'million',
			1000000000          => 'billion',
			1000000000000       => 'trillion',
			1000000000000000    => 'quadrillion',
			1000000000000000000 => 'quintillion'
		);
	   
		if (!is_numeric($number)) {
			return false;
		}
	   
		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
				'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
				E_USER_WARNING
			);
			return false;
		}

		if ($number < 0) {
			return $negative . $this->convert_number_to_words(abs($number));
		}
	   
		$string = $fraction = null;
	   
		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}
	   
		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . $this->convert_number_to_words($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= $this->convert_number_to_words($remainder);
				}
				break;
		}
	   
		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			$words = array();
			foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}
			$string .= implode(' ', $words);
		}
	   
		return $string;
	}

	function limit_words($input, $length, $delimiter = "...")
	{
		//only truncate if input is actually longer than $length
		if(strlen($input) > $length)
		{
			//check if there are any spaces at all and if the last one is within the given length if so truncate at space else truncate at length.
			if(strrchr($input, " ") && strrchr($input, " ") < $length)
			{
				return substr( $input, 0, strrpos( substr( $input, 0, $length), ' ' ) ) . $delimiter;
			}
			else
			{
				return substr( $input, 0, $length ) . $delimiter;
			}
		}
		else
		{
			return $input;
		}
	}
	
	function unit_measure($unit_qty, $quantity, $cal_quantity)
	{
		
	}
	
	function convert_unit_2_string ($_item_code = NULL, $_qty = NULL)
	{
		# $_is_mulit_unit = $_SESSION["multi_unit"];
		$_is_mulit_unit = 1;
		$_if_under_0 = "";
		if ($_is_mulit_unit == 0)
		{
			return $qty;

			exit ();
		}
		$nu = 0;
		if($_qty){
			
			if ($_qty < 0)
			{
				$nu = $_qty;
				//$_if_under_0 = "-";
				$_qty = abs ($_qty);
			}
		}

		if ($_qty == 0) $_qty = "zero";

		if ($_item_code == "" || $_qty === "")
		{
			//exit ("Warning! cannot call convert_unit_2_string($_item_code, $_qty) function.. missing argument, Error: bv00100");
		}
		else
		{
			if ($_qty == "zero") $_qty = 0;


			$_item_code = trim ($_item_code);

			$_units = array ();

			$_select_all_units = $this->site->getUnitUOM($_item_code);
			
			$_max_unit = count($_select_all_units);

			$_i = 0;
			if (is_array($_select_all_units)){
				foreach ($_select_all_units as $_get_unit)
				{
					$_unit_description 	= $_get_unit->name;
					$_unit_qty 			= $_get_unit->qty_unit;

					/*

						Syntax:

						A							B								C							D
						10							5								1							568
						D / A = AX					XA / B = BX						XB / C = CX
						D - (AX * A) = XA			XA - (BX * B) = XB				XB - (CX * C) = XC

						568 / 10 = 56 (8)			8 / 5 = 1 (4)					4 / 1 = 4 (0)
						568 - (56 * 10) = 8			8 - (1 * 5) = 4					4 - (4 * 1) = 0

																												7834663
						7834663 / 50 = 156693
						7834663 - (156693 * 50) = 13

						13 / 10 = 1
						13 - (10 * 1) = 3

						3



						10000 g = 10 kg
						- unit = Ton = 1 000 000 g

						- 10 000 / 1 000 000

						if 10 000 < 1 000 000


					*/

					if ($_qty <= 0) break;

					if ((($_qty) < $_unit_qty) || $_i == $_max_unit)
					{
						if ($_qty < $_unit_qty) continue;

						$_units[] = "$_qty <span style='color: #178228;'>$_unit_description x</span>";

						# break;
					}
					else
					{
						# D / A = AX
						$_qtyx = (int) ($_qty / $_unit_qty);
						$_units[] = "$_qtyx <span style='color: #178228;'>$_unit_description</span>";

						# D - (AX * A) = XA
						$_xqty = $_qty - ($_qtyx * $_unit_qty);

						#
						$_qty = $_xqty;
					}
				}
			}
			$_string_unit = $this->array_2_string(", ", $_units);

			if(empty($_select_all_units) and $_qty > 0){
				$_string_unit = '1 <span style="color: #178228;">' . $this->site->getUnitNameByProId($_item_code) .'</span>';
			}
			
			$en = "";
			if($_string_unit){
				if ($nu < 0)
				{
					$_if_under_0 = "- (";
					$en = ")";
				}else{
					$_if_under_0 = "(";
					$en = ")";
				}
			}
			
			return "$_if_under_0 $_string_unit $en";
		}

		# how to use:
		# echo convert_unit_2_string ("CAT4TST-00001", 7834663);
	}
    
	function convert_unit_2_string1 ($_item_code = NULL, $_qty = NULL)
    {
        # $_is_mulit_unit = $_SESSION["multi_unit"];
        $_is_mulit_unit = 1;
        $_if_under_0 = "";
        if ($_is_mulit_unit == 0)
        {
            return $qty;

            exit ();
        }
        $nu = 0;
        if($_qty){
            
            if ($_qty < 0)
            {
                $nu = $_qty;
                //$_if_under_0 = "-";
                $_qty = abs ($_qty);
            }
        }

        if ($_qty == 0) $_qty = "zero";

        if ($_item_code == "" || $_qty === "")
        {
            //exit ("Warning! cannot call convert_unit_2_string($_item_code, $_qty) function.. missing argument, Error: bv00100");
        }
        else
        {
            if ($_qty == "zero") $_qty = 0;


            $_item_code = trim ($_item_code);

            $_units = array ();

            $_select_all_units = $this->site->getUnitUOM($_item_code);
            
            
            
            $_max_unit = count($_select_all_units);

            $_i = 0;
            if (is_array($_select_all_units)){
                foreach ($_select_all_units as $_get_unit)
                {
                    $_unit_description = $_get_unit->name;
                    $_unit_qty = $_get_unit->qty_unit;
                    if ($_qty <= 0) break;

                    if ((($_qty) < $_unit_qty) || $_i == $_max_unit)
                    {
                        if ($_qty < $_unit_qty) continue;

                        $_units[] = "$_qty $_unit_description x";

                        # break;
                    }
                    else
                    {
                        # D / A = AX
                        $_qtyx = (int) ($_qty / $_unit_qty);
                        $_units[] = "$_qtyx $_unit_description";

                        # D - (AX * A) = XA
                        $_xqty = $_xqty = $this->erp->formatPurDecimal($_qty) - $this->erp->formatPurDecimal($_qtyx * $_unit_qty);

                        #
                        $_qty = $_xqty;
                    }
                }
            }
            $_string_unit = $this->array_2_string (", ", $_units);
            $en = "";
            if($_string_unit){
                if ($nu < 0)
                {
                    $_if_under_0 = "- (";
                    $en = ")";
                }else{
                    $_if_under_0 = "(";
                    $en = ")";
                }
            }
            
            return "$_if_under_0 $_string_unit $en";
        }

        # how to use:
        # echo convert_unit_2_string ("CAT4TST-00001", 7834663);
    }
	
	function convert_unit_by_variant ($_item_code = NULL, $_qty = NULL)
    {
        $_is_mulit_unit = 1;
        $_if_under_0 = "";
        if ($_is_mulit_unit == 0)
        {
            return $qty;

            exit ();
        }
        $nu = 0;
        if($_qty){
            
            if ($_qty < 0)
            {
                $nu = $_qty;
                $_qty = abs ($_qty);
            }
        }

        if ($_qty == 0) $_qty = "zero";

        if ($_item_code == "" || $_qty === "")
        {
            //exit ("Warning! cannot call convert_unit_2_string($_item_code, $_qty) function.. missing argument, Error: bv00100");
        }
        else
        {
            if ($_qty == "zero") 
				
			$_qty = 0;
            $_item_code = trim ($_item_code);
            $_units = array ();
            $_select_all_units = $this->site->getUnitUOM($_item_code);
            $_max_unit = count($_select_all_units);
            $_i = 0;
            if (is_array($_select_all_units)){
                foreach ($_select_all_units as $_get_unit)
				{
                    $_unit_description = $_get_unit->name;
                    $_unit_qty = $_get_unit->qty_unit;
                    if ($_qty <= 0) break;

                    if ((($_qty) < $_unit_qty) || $_i == $_max_unit)
                    {
                        if ($_qty < $_unit_qty) continue;
                        $_units[$_unit_description] = "$_qty";
                    }
                    else
                    {
                        $_qtyx = (int) ($_qty / $_unit_qty);
                        $_units[$_unit_description] = "$_qtyx";
                        $_xqty = $_qty - ($_qtyx * $_unit_qty);
                        $_qty = $_xqty;
                    }
                }
            }
            $_units;
            return $_units;
        }
    }
	
	function array_2_string($sep = "-", $_data, $_prefix = "", $_suffix = "")
	{
		if ($_prefix != "" AND $_suffix != "")
		{
			$_string = array ();

			foreach ($_data AS $_value)
			{
				$_string[] = $_prefix . $_value . $_suffix;
			}
		}

		else $_string = $_data;

		return implode ("$sep", array_filter ($_string));
	}
	
	function convert_unit_2_string_by_unit ($_item_code = NULL, $_qty = NULL)
	{
		# $_is_mulit_unit = $_SESSION["multi_unit"];
		$_is_mulit_unit = 1;

		if ($_is_mulit_unit == 0)
		{
			return $qty;

			exit ();
		}

		/*if ($_qty < 0)
		{
			$_if_under_0 = "-";
			$_qty = abs ($_qty);
		}*/

		if ($_qty == 0) $_qty = "zero";

		if ($_item_code == "" || $_qty === "")
		{
			//exit ("Warning! cannot call convert_unit_2_string($_item_code, $_qty) function.. missing argument, Error: bv00100");
		}
		else
		{
			if ($_qty == "zero") $_qty = 0;


			$_item_code = trim ($_item_code);

			$_units = array ();

			$_select_all_units = $this->site->getUnitUOM($_item_code);
			
			
			$_max_unit = count($_select_all_units);

			$_i = 0;

			foreach ($_select_all_units as $_get_unit)
			{
				$_unit_description = $_get_unit->name;
				$_unit_qty         = $_get_unit->qty_unit;
				$_cost	           =  ($_get_unit->qty_unit * $_get_unit->pcost );
				$_price			   = $_get_unit->price;

				/*

					Syntax:

					A							B								C							D
					10							5								1							568
					D / A = AX					XA / B = BX						XB / C = CX
					D - (AX * A) = XA			XA - (BX * B) = XB				XB - (CX * C) = XC

					568 / 10 = 56 (8)			8 / 5 = 1 (4)					4 / 1 = 4 (0)
					568 - (56 * 10) = 8			8 - (1 * 5) = 4					4 - (4 * 1) = 0

																											7834663
					7834663 / 50 = 156693
					7834663 - (156693 * 50) = 13

					13 / 10 = 1
					13 - (10 * 1) = 3

					3



					10000 g = 10 kg
					- unit = Ton = 1 000 000 g

					- 10 000 / 1 000 000

					if 10 000 < 1 000 000


				*/

                if (!$this->Owner && !$this->Admin) {
                    $gp = $this->site->checkPermissions();
                    $this->GP = $gp[0];
                    $GP = $gp[0];
                } else {
                    $GP = NULL;
                }


                //if ($_qty <= 0) break;

				if ((abs($_qty) < $_unit_qty) || $_i == $_max_unit)
				{
					
					if (abs($_qty) < $_unit_qty) continue; 

					$_units[] = "$_qty <span style='color: #178228;'>$_unit_description x</span>";

					# break;
				}
				else
				{
					# D / A = AX
					
					$_qtyx = (int) ($_qty / $_unit_qty);
					
                    $_units[] = "<tr>
                                    <td>$_unit_description</td>
                                    <td >" . $this->formatQuantity($_qtyx) . "</td>"
                        . ($this->Owner || $this->Admin || $GP['products-cost'] ? "
                                    <td>" . $this->formatMoney($_cost) . "</td>" : "")
                        . ($this->Owner || $this->Admin || $GP['products-price'] ? "
                                    <td>" . $this->formatMoney($_price) . "</td>" : "") . "</tr>";

					# D - (AX * A) = XA
					$_xqty = $_qty - ($_qtyx * $_unit_qty);

					#
					$_qty = $_xqty;
				}
			}

			$_string_unit = $this->array_2_string ("", $_units);

            /*if ($_qty < 0) {
                return "$_if_under_0";
            }*/
			return "$_string_unit";
		}

		# how to use:
		# echo convert_unit_2_string ("CAT4TST-00001", 7834663);
	}
	
	public function formatPercentage($percent)
	{
		$per 		= explode('.', $percent);

		$percentage = 0;
        if ($per[1] > 0) {
            $percentage = $this->erp->formatDecimal($per[0] . '.' . $per[1]);
		}else{
			$percentage = $per[0];
		}
		return $percentage;
	}
	
	public function multiCurrFormular($curr_code, $amount)
	{
		# Query Curency Detail by Code
		$currency 		= $this->site->getCurrencyByCode($curr_code);
		
		# Get Setting Rate
		$setting_code 	= $this->Settings->default_currency;
		$setting_curr	= $this->site->getCurrencyByCode($setting_code);
		
		# Calculate Formular
		$result = ($amount/$currency->rate)*$setting_curr->rate;
		
		# Return Result
		return $result;
	}
	
	function numberToWords ($number,$kh=''){
		if (($number < 0) || ($number > 999999999))
		{
			//throw new Exception("Number is out of range");
			return  "Number is out of range";
		}

		$Gn = floor($number / 1000000);  /* Millions (giga) */
		$number -= $Gn * 1000000;
		$kn = floor($number / 1000);     /* Thousands (kilo) */
		$number -= $kn * 1000;
		$Hn = floor($number / 100);      /* Hundreds (hecto) */
		$number -= $Hn * 100;
		$Dn = floor($number / 10);       /* Tens (deca) */
		$n = $number % 10;               /* Ones */

		$res = "";

		if ($Gn)
		{
			$res .= $this->numberToWords ($Gn,$kh) . ($kh==""?" Million":"លាន");
		}

		if ($kn)
		{
			$res .= (empty($res) ? "" : " ") .
				$this->numberToWords ($kn,$kh) . ($kh==""?" Thousand":"ពាន់");
		}

		if ($Hn)
		{
			$res .= (empty($res) ? "" : " ") .
				$this->numberToWords ($Hn,$kh) . ($kh==""?" Hundred":"រយ");
		}

		$ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
			"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
			"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
			"Nineteen");
		$tens = array("", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty",
			"Seventy", "Eigty", "Ninety");

		$oneskh = array("", "មួយ", "ពីរ", "បី", "បួន", "ប្រាំ", "ប្រាំមួយ",
			"ប្រាំពីរ", "ប្រាំបី", "ប្រាំបួន", "ដប់", "ដប់មួយ", "ដប់ពីរ", "ដប់បី",
			"ដប់បួន", "ដប់ប្រាំ", "ដប់ប្រាំមួយ", "ដប់ប្រាពីរ", "ដប់ប្រាំបី",
			"ដប់ប្រាំបួន");
		$tenskh = array("", "", "ម្ភៃ", "សាមសិប", "សែសិប", "ហាសិប", "ហុកសិប",
			"ចិតសិប", "ប៉ែតសិប", "កៅសិប");

		if ($Dn || $n)
		{
			if (!empty($res))
			{

				$res .= ($fpont>0?" ":($kh==""?" ":""));
			}

			if ($Dn < 2)
			{
				$res .= ($kh==""?$ones[$Dn * 10 + $n]:$oneskh[$Dn * 10 + $n]);
			}
			else
			{
				$res .= ($kh==""?$tens[$Dn]:$tenskh[$Dn]);

				if ($n)
				{
					$res .= ($kh==""?"-".$ones[$n]:$oneskh[$n]);
				}
			}
		}

		if (empty($res))
		{
			$res = ($kh==""?"zero":"សូន្យ");
		}

		return $res;
	}

	function numberToWordsCur ($numberf,$kh='',$cur="US Dollars",$cur_h = " សេន") {
		$numberf = round($numberf,2);
		$arr = explode('.',$numberf);
		$number = $arr[0]-0;
		$fpont =  (($arr[1]-0) ? $arr[1]-0 : 0);

		$f = '';
		if($fpont>0){
			$fpont = str_pad($fpont,2,'0',STR_PAD_RIGHT)-0;
			$f = ($kh==""?" and ":"​ និង ").$this->numberToWords($fpont,$kh).$cur_h;
		}
		$res = $this->numberToWords($number,$kh).' '.$cur;

		return $res.$f;
	}
	

}
