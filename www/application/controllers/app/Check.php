<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Check extends CB_Controller
{

protected $models = array('Common','Member','Check',"Sang_date");

	protected $helpers = array('form', 'array', 'string','json','basic');

	function __construct(){
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
 		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        exit(0);
    }
    $mem_userid = $this->input->post("mem_userid");
    //if($mem_userid == "undefined" || $mem_userid == ""){json_date_fail($method,"권한이없습니다");}
	}
  public function index($mem_userid){
    echo "잘못된 접근입니다";
  }
  public function update_check($mem_userid){
    $ck = $this->Common_model->make_arr_data_key("ck_");
		$wh['mem_userid'] = $mem_userid;
		if($ck['ch_com'] == "Y"){
			$data['mem_point'] = 200;
			$this->Member_model->update("",$data,$wh);
		}
    $rs = $this->Check_model->get_one("","ch_idx",$wh);
    if($rs['ch_idx'] != ""){
      $this->Check_model->update($rs['ch_idx'],$ck);
    }
  }
	public function update_q3($mem_userid){
		$where['mem_userid'] = $mem_userid;
		$data['ch_sang'] = $this->input->post("ch_sang");
		if($data['ch_sang'] == "Y"){
				$data['q3'] = date("Y-m-d");
		}
		if($data['ch_sang'] == "N"){
				$data['ch_last_day'] = date("Y-m-d");
				$ch = $this->Check_model->get_one("","q3",$where);
				$sa['mem_userid'] = $mem_userid;
				$sa['sa_start'] = $ch['q3'];
				$sa['sa_end'] = date("Y-m-d");
				$this->Sang_date_model->insert($sa);
		}
		if($mem_userid != ""){
				$this->Check_model->update("",$data,$where);
		}
  }
	public function check_info($mem_userid){
		if($mem_userid != "" && $mem_userid != undefined && $mem_userid != null){
			$where['mem_userid'] = $mem_userid;
			$ch = $this->Check_model->get_one("","",$where);
			echo json_encode($ch,JSON_UNESCAPED_UNICODE);
		}
	}
	public function ip_test(){
		echo $_SERVER['X_REAL_IP'];
		echo "<br>";
		echo $_SERVER['REMOTE_ADDR'];
		echo "<br>";
		echo $_SERVER['HTTP_X_FORWARDED_FOR'];
	}

  public function get_check($mem_userid){
    $wh['mem_userid'] = $mem_userid;
    $rs = $this->Check_model->get_one("","ch_idx,ch_com",$wh);
    echo json_encode($rs,JSON_UNESCAPED_UNICODE);
  }
	public function get_data($mem_userid){
    $wh['mem_userid'] = $mem_userid;
    $rs = $this->Check_model->get_one("","",$wh);

    echo json_encode($rs,JSON_UNESCAPED_UNICODE);
  }

	public function cal_data($mem_userid){
    $wh['mem_userid'] = $mem_userid;
    $ch = $this->Check_model->get_one("","q3,q4,q5,ch_com,ch_sang",$wh);
		$ba_sub = $ch['q5']-14;

		// 생리 일자 디데이 계산
		$sang_time = strtotime("{$ch['q3']} +{$ch['q5']} days");
		$nDate = date("Y-m-d");
		$sang_day = date('Y-m-d',$sang_time); // 생리일
		$date1 = new DateTime($sang_day);
		$date2 = new DateTime($nDate); // 오늘
		$interval = date_diff($date1,$date2);

		if($date1 > $date2){
				$dday = -1*($interval->days);
				$rs['msg'] = "생리예정일이 {$interval->days}일 남았습니다.";
		}else {
				$dday = "+ ".($interval->days);
				$rs['msg'] = "생리예정일이 {$interval->days}일 지났습니다.";
		}
		$rs['dday'] = $dday;
		if($interval->days === 0){
			$rs['dday'] = "+ 0";
		}
		$rs['sang_day'] = $sang_day;
		//가임기
		for ($i=$ba_sub-5; $i < $ba_sub+3 ; $i++) {
			$ga_date = strtotime("{$ch['q3']} +{$i} days");
			$gaArr[] = date('Y-m-d',$ga_date);
		}
		$ba_date = strtotime("{$ch['q3']} +{$ba_sub} days");
		$ba = date('Y-m-d',$ba_date); // 배란일

		//생리예측주기
		for ($i=$ch['q5']-3; $i < 3+$ch['q5']+$ch['q4'] ; $i++) {
			$date = strtotime("{$ch['q3']} +{$i} days");
			$sangArr[] = date('Y-m-d',$date);
		}

		//생리가능주기
		for ($i=$ch['q5']; $i < $ch['q5']+$ch['q4'] ; $i++) {
			$date = strtotime("{$ch['q3']} +{$i} days");
			$canSangArr[] = date('Y-m-d',$date);
		}

		// for ($i=0; $i < $ch['q4'] ; $i++) {
		// 	$date = strtotime("{$ch['q3']} +{$i} days");
		// 	$sangArr[] = date('Y-m-d',$date);
		// }

		if($ch['q3'] == ""){
			$q3 = date('Y-m-d');
		}else {
			$q3 = $ch['q3'];
		}

		$q3_date = strtotime("{$ch['q3']} +1 month");
		$q3_1 = date('Y-m-d',$q3_date); // 배란일

		$q3_date2 = strtotime("{$ch['q3']} +2 month");
		$q3_2 = date('Y-m-d',$q3_date2); // 배란일

		$q3arr[] = explode("-",$q3);
		$q3arr[] = explode("-",$q3_1);
		$q3arr[] = explode("-",$q3_2);

		if(in_array($nDate, $sangArr)){
			$rs['msg'] = "생리 가능성이 있는날 입니다";
		}
		if(in_array($nDate, $gaArr)){
			$rs['msg'] = "임신 가능성이 있습니다";
		}
		if($nDate == $ba){
			$rs['msg'] = "오늘은 배란일 입니다";
		}
		if($nDate == $ba){
			$rs['msg'] = "오늘은 배란일 입니다";
		}
		if($ch['ch_sang'] == "Y"){
			$st = new DateTime($ch['q3']);
			$en = new DateTime(date("Y-m-d"));
			$df = date_diff($st,$en);
			$df_days = $df->days+1;
			$rs['smsg'] = "생리 {$df_days}일째";
			//$rs['smsg'] = "";
		}
		$rs['ch_sang'] = $ch['ch_sang'];
		$rs['ch_com'] = $ch['ch_com'];
		$rs['q3arr'] = $q3arr;
		$rs['q3'] = $ch['q3'];
		if($ch['q3'] != ""){
			$rs['gaArr'] = $gaArr;
			$rs['sangArr'] = $sangArr;
			$rs['canSangArr'] = $canSangArr;
			$rs['ba'] = $ba;
		}else {
			$rs['dday'] = "- NO";
			$rs['msg'] = "최근 생리일자가 없습니다";
			$rs['gaArr'] = [];
			$rs['sangArr'] = [];
			$rs['canSangArr'] = [];
		}
		echo json_encode($rs,JSON_UNESCAPED_UNICODE);
  }
}

// /$this->Json_log_model->add_log();
// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
