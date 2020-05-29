<?php
defined('BASEPATH') OR exit('No direct script access allowed');



/**
 * Login class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 로그인 페이지와 관련된 controller 입니다.
 */
class App_accompany extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
protected $models = array('Common','Member','Config','Accompany','Room','Room_member');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'string','json','basic');

	function __construct(){
		parent::__construct();
		header('Access-Control-Allow-Origin: http://1.234.44.171:3000');
 		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        exit(0);
    }
    $mem_userid = $this->input->post("mem_userid");
    //if($mem_userid == "undefined" || $mem_userid == ""){json_date_fail($method,"권한이없습니다");}
	}
  public function index($value=''){
    echo "잘못된 접근입니다.";
  }
	/////////////-------------------- 동행글작성  -----------------------//////////////////////////////

	public function add_accom(){

    $ref = $this->Common_model->CIUpload($_FILES['ac_img'],"./uploads/accs");
    $ac = $this->Common_model->make_arr_data("ac_");
		$mem_userid = $this->input->post("mem_userid");
    $ac['ac_img'] = $ref['name'];
    $ac['mem_userid'] = $mem_userid;
    $ac_idx = $this->Accompany_model->insert($ac);
		// 채팅룸을 생성합니다
		$ro['ac_idx'] = $ac_idx;
		$ro['ro_type'] = '001';
		$ro['mem_userid'] = $mem_userid;
		$ro_idx = $this->Room_model->insert($ro);

		// 채팅룸을 생성합니다
		$rm['ro_idx'] = $ro_idx;
		$rm['mem_userid'] = $mem_userid;
		$this->Room_member_model->insert($rm);
    $json['resultItem'] = json_date_success($method,"Companion has been registered");
		echo json_encode($json,JSON_UNESCAPED_UNICODE);
  }

	/////////////-------------------- 동행글업데이트  -----------------------//////////////////////////////

	public function update_accom(){

		$ac = $this->Common_model->make_arr_data("ac_");

    $ref = $this->Common_model->CIUpload($_FILES['ac_img'],"./uploads/accs");
		if($ref['name'] == ""){
			unset($ac['ac_img']);
		}else {
			$ac['ac_img'] = $ref['name'];
		}
    $this->Accompany_model->update($ac['ac_idx'],$ac);

    $json['resultItem'] = json_date_success($method,"Companion has been fixed");
		echo json_encode($json,JSON_UNESCAPED_UNICODE);
  }


  /////////////-------------------- 동행글출력  -----------------------//////////////////////////////
  public function accom_list($mem_userid){
		$sctext_key =['ac_title','ac_loc','startDate','endDate'];
		$sctext = $this->input->get('sctext');
		$sctext_arr = explode("@",$sctext);
		for ($i=0; $i < count($sctext_key); $i++) {
			$where[$sctext_key[$i]] = $sctext_arr[$i];
		}
		$order = $this->input->get('order');
		if($mem_userid != ""){
			$where['ac.mem_userid'] = $mem_userid;
		}
    $list = $this->Accompany_model->accom_list($where,$order);
    foreach ($list as $key => $val) {
			$val['mem_photo'] = "http://1.234.44.171/uploads/member_photo/{$val['mem_photo']}";
      $json[] = $val;
    }

		$qry = $this->db->last_query();

    echo json_encode($json,JSON_UNESCAPED_UNICODE);
  }

	public function accom_info($ac_idx){

		$info = $this->Accompany_model->accom_info($ac_idx);
		$info['ac_img'] = "http://1.234.44.171/uploads/accs/{$info['ac_img']}";
		$info['mem_photo'] = "http://1.234.44.171/uploads/member_photo/{$info['mem_photo']}";
		echo json_encode($info,JSON_UNESCAPED_UNICODE);
	}
	public function accom_join(){
		$mem_userid = $this->input->post("mem_userid");
		$ac_idx = $this->input->post("ac_idx");
		$wh['ac_idx'] = $ac_idx;
		$ro = $this->Room_model->get_one("","",$wh);

		$sc['mem_userid'] = $mem_userid;
		$sc['ro_idx'] = $ro['ro_idx'];
		$count = $this->Room_member_model->count_by($sc);
		if($count == 0){
			$this->Room_member_model->insert($sc);
			$json['rs'] = "Y";
			$json['msg'] = "The travel application has been completed";
		}else {
			$json['rs'] = "N";
			$json['msg'] = "It has already been applied";
		}
		echo json_encode($json,JSON_UNESCAPED_UNICODE);
	}


}

// /$this->Json_log_model->add_log();
// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
