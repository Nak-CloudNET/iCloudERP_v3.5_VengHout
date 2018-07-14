<?php defined('BASEPATH') OR exit('No direct script access allowed');

FUNCTION kpTime($dtime,$atime){
	$nextDay=$dtime>$atime?1:0;
	$dep=EXPLODE(':',$dtime);
	$arr=EXPLODE(':',$atime);
	$diff=ABS(MKTIME($dep[0],$dep[1],0,DATE('n'),DATE('j'),DATE('y'))-MKTIME($arr[0],$arr[1],0,DATE('n'),DATE('j')+$nextDay,DATE('y')));
	$hours=FLOOR($diff/(60*60));
	$mins=FLOOR(($diff-($hours*60*60))/(60));
	$secs=FLOOR(($diff-(($hours*60*60)+($mins*60))));
	IF(STRLEN($hours)<2){$hours="0".$hours;}
	IF(STRLEN($mins)<2){$mins="0".$mins;}
	IF(STRLEN($secs)<2){$secs="0".$secs;}
	RETURN $hours.':'.$mins;
}
if(! function_exists('supspend')) {
    function supspend() {
        $ci =& get_instance();
		return 'hi';
		/*
		$arrSuspend = array();
        $this->db->select('*');
        $this->db->from('erp_suspended_bills');
        //$this->db->where('created_by', $this->session->userdata('user_id'));
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $arrSuspend[$row->suspend_note]['suspend'] = "suspend";
                $arrSuspend[$row->suspend_note]['cust'] = $row->customer;
                $arrSuspend[$row->suspend_note]['date'] = $row->date;
                $arrSuspend[$row->suspend_note]['count'] = $row->count;
                $arrSuspend[$row->suspend_note]['total'] = $row->total;
                $arrSuspend[$row->suspend_note]['id'] = $row->id;
            }
        }
        for($i=1; $i < count(26; $i++){
            echo "<button type=\"button\" value='" . $i . "' ".($arrSuspend[$i]['suspend'] === "suspend" ? 'id="'.$arrSuspend[$i]['id'].'"' : '' )." class='".($arrSuspend[$i]['suspend'] === "suspend" ? 'btn-prni btn btn-info sus_sale' : 'btn-prni btn suspend-button' )."' >
                    <span>" . ($arrSuspend[$i]['suspend'] === "suspend" ? "<p>Number " . $i . "</p>(" . $arrSuspend[$i]['count'] . ")<br/>" . $arrSuspend[$i]['total'] : "Number " . $i ) . "</span>
                </button>";
        }
		*/
    }
}
