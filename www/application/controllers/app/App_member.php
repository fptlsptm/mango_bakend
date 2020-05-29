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
class App_member extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Common','Member','Config','Member_userid',"Check","Push");


	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'string','json','basic');

	function __construct(){
		parent::__construct();
		header('Access-Control-Allow-Origin: * ');
 		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        exit(0);
    }
	}
  public function index($value=''){
    echo "잘못된 접근입니다.";
  }
	public function get_push($mem_userid){
		$where['mem_userid'] = $mem_userid;
		$rs = $this->Push_model->get("","",$where);
		echo json_encode($rs,JSON_UNESCAPED_UNICODE);
	}
	/////////////-------------------- 로그인  -----------------------//////////////////////////////

	public function mem_login(){
		foreach ($_POST as $key => $value) {
			$$key = $this->input->post($key);
		}
    if($mem_userid == ""){json_date_fail($method,"아이디값이 없습니다");}
		$where['mem_userid'] = $mem_userid;
		$mem = $this->Member_model->get_one("","mem_id,mem_userid,mem_password",$where);

		if($mem['mem_userid'] == ""){json_date_fail($method,"아이디가 없습니다");}
		$sql = $this->db->last_query();
		$mt['mem_token'] = $mem_token;

		$hash = password_hash($mem_password, PASSWORD_BCRYPT);
		$rs = password_verify($mem_password,$mem['mem_password']);

		if($rs){
			$this->Member_model->update("",$mt,$where);
      $json['mem_id'] =$mem['mem_id'];
			json_date_success($method,"로그인성공",$json);
		}else{
			json_date_fail($method,"비밀번호가 일치하지 않습니다");
		}
  }
	public function mem_form_data(){

		$json = $this->cbconfig->item('registerform');
		$form = json_decode($json, true);

		foreach ($form as $key => $value) {
			if($value['use'] == 1){
				$arr[] = $value;
			}
		}

		echo json_encode($arr,JSON_UNESCAPED_UNICODE);
	}
	public function member_update_one($mem_userid){
		$md = $this->Common_model->make_arr_data("mem_");
		$where['mem_userid'] = $mem_userid;
		$rs = $this->Member_model->update("",$md,$where);

	}


	/////////////-------------------- 회원정보-----------------------//////////////////////////////

	public function mem_info($mem_userid){
		//Check

		$method = $this->input->post("method");
		$where['mem_userid'] = $mem_userid;
		$mem = $this->Member_model->get_one("",$sel,$where);
		$ch = $this->Check_model->get_one("",$sel,$where);
		if($mem['mem_push1'] == 1){$mem['mem_push1'] = true;}else{$mem['mem_push1'] = false;}
		if($mem['mem_push2'] == 1){$mem['mem_push2'] = true;}else{$mem['mem_push2'] = false;}

		if(count($mem) > 1){
			$json['mem_info'] = $mem;
			$json['ch_info'] = $ch;
			json_date_success("mem_info","success",$json);
		}else{
			json_date_fail($method,"회원정보가 없습니다");
		}

	}
	public function token_update($mem_userid){
		$where['mem_userid'] = $mem_userid;
		$data['mem_token'] = $this->input->post("mem_token");
		$data['mem_platform'] = $this->input->post("mem_platform");

		$this->Member_model->update("",$data,$where);
	}

	/////////////-------------------- 회원정보-----------------------//////////////////////////////
	public function mem_photo($mem_userid){
		$method = $this->input->post("method");
		$where['mem_userid'] = $mem_userid;
		$sel = "mem_photo";
		$mem = $this->Member_model->get_one("",$sel,$where);
		if($mem['mem_photo'] == ""){$mem['mem_photo'] = 'mypage.png';}
		$mem['mem_photo'] = "http://1.234.44.171/uploads/member_photo/{$mem['mem_photo']}";

		if(count($mem) > 0){
			$json['mem_info'] = $mem;
			json_date_success("mem_info","success",$mem);
		}else{
			json_date_fail($method,"회원정보가 없습니다");
		}
	}
	/////////////-------------------- 회원 아이디 조회-----------------------//////////////////////////////
	public function mem_userid_ck(){

		$method = $this->input->post("method");
		$mem_userid = $this->input->post("mem_userid");
		$where['mem_userid'] = $mem_userid;

		$ck_arr = $this->Member_userid_model->get_one("","",$where);

		if($mem_userid == ""){
			$json['resultItem'] = json_date_fail($method,"아이디값이 없습니다");
		}

		if(preg_match("/[\xE0-\xFF][\x80-\xFF][\x80-\xFF]/", $where['mem_userid'])){
			json_date_fail($method,"아이디가 한글입니다");
		}
		if(count($ck_arr) == 0){
			$json['resultItem'] = json_date_success($method,"가입가능한 아이디입니다");
		}else{
			json_date_fail($method,"이미 존재하는 아이디입니다");
		}
		echo json_encode($json);
	}
	/////////////-------------------- 회원 닉네임 조회-----------------------//////////////////////////////
	public function mem_nickname_ck(){

		$method = $this->input->post("method");
		$mem_nickname = $this->input->post("mem_nickname");
		$where['mem_nickname'] = $mem_nickname;

		$ck_arr = $this->Member_model->get_one("","",$where);

		if($mem_nickname == ""){
			$json['resultItem'] = json_date_fail($method,"닉네임값이 없습니다");
		}

		if(preg_match("/[\xE0-\xFF][\x80-\xFF][\x80-\xFF]/", $where['mem_nickname'])){
			json_date_fail($method,"닉네임이 한글입니다");
		}


		if(count($ck_arr) == 0){
			$json['resultItem'] = json_date_success($method,"가입가능한 닉네임입니다");
		}else{
			json_date_fail($method,"이미 존재하는 닉네임입니다");
		}

		echo json_encode($json);
	}
	/////////////-------------------- 회원정보 체크-----------------------//////////////////////////////
	public function member_join_ck(){
		$method = $this->input->post("method");
		$mem_arr = array('mem_userid' => '아이디',
										 'mem_nickname' => '닉네임',
										 'mem_phone' => '휴대폰');
		foreach ($mem_arr as $key => $value) {
			$mem[$key] = $this->input->post($key);
			if($mem[$key] == ""){
				json_date_fail($method,"{$value}값이 누락되었습니다");
			}
			$where = array();
			$where[$key] = $mem[$key];
			$ck_arr = $this->Member_model->get_one("",$key,$where);
			if(count($ck_arr) != 0){
				json_date_fail($method,"중복되는 {$value}값이 존재합니다");
			}
		}

		$json['resultItem'] = json_date_success($method,"입력한 값이 모두 유효합니다");
		echo json_encode($json);
	}


	/////////////-------------------- 회원 가입-----------------------//////////////////////////////

	public function member_join(){

		$password = $this->input->post("password");
		$password_ck = $this->input->post("password_ck");
		$method = $this->input->post("method");
		$md = $this->Common_model->make_arr_data("mem_");
		$kc = strlen($password);
		if(strlen($password) < 4){json_date_fail($method,"비밀번호는 최소4자리입니다");}
    $md['mem_password'] = password_hash($password, PASSWORD_BCRYPT);
		$md['mem_register_datetime'] = date("Y-m-d H:i:s");
		$md['mem_email'] = $md['mem_userid'];
		// 아이디 중복체크
		$id_wh['mem_userid'] = $md['mem_userid'];
		$id_ck = $this->Member_model->get_one("","mem_userid",$id_wh);

    if($md['mem_userid'] == ""){json_date_fail($method,"이메일 값이 없습니다");}
    if($md['mem_password'] == ""){json_date_fail($method,"비밀번호 값이 없습니다");}
    if($password_ck == ""){json_date_fail($method,"비밀번호확인 값이 없습니다");}

		$check_email=preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $md['mem_email']);
		if(!$check_email){json_date_fail($method,"올바른 이메일형식이 아닙니다");}


		//if(strlen($md['mem_birthday']) != 6){json_date_fail($method,"생년월일은6자리입니다.");}

		if(strlen($md['mem_birthday']) != 6){$md['mem_birthday'] = "notting";}
		if(count($id_ck) != 0){json_date_fail($method,"이미 사용중인 이메일입니다");}
		if($password_ck != $password){json_date_fail($method,"비밀번호를 확인해주세요");}


		$mem_id = $this->Member_model->insert($md);  // 회원기본정보를 담음
		$ck_data['mem_userid'] = $md['mem_userid'];
		$this->Check_model->insert($ck_data);
		$pu['pu_title'] = "가입을 축하드립니다";
		$pu['pu_body'] = "가입을 축하드립니다";
		$pu['mem_userid'] = $md['mem_userid'];
		$this->Push_model->insert($pu);
		$arr['mem_id'] = $mem_id;

		$qry = $this->db->last_query();
		$json['resultItem'] = json_date_success($method,"회원가입이 되었습니다",$arr);
	}

	/////////////-------------------- 회원정보 업데이트-----------------------////////////////////////

	public function member_update(){

		// 실제로 회원정보가 존재유무를 판단
		$method = $this->input->post("method");
		$mem_userid = $this->input->post("mem_userid");
		$where['mem_userid'] = $mem_userid;
		$ck_arr = $this->Member_userid_model->get_one("","",$where);
		if(count($ck_arr) != 3){
			json_date_fail($method,"There is no member information");
		}

		$md = $this->Common_model->make_arr_data("mem_");
		// if($md['mem_password'] != ""){
		// 	$md['mem_password'] = password_hash($md['mem_password'], PASSWORD_BCRYPT);
		// }

		$ref = $this->Common_model->CIUpload($_FILES['mem_photo'],"./uploads/member_photo");
		if($ref['name'] != ""){$md['mem_photo'] = $ref['name']; };
		$mem_id = $this->Member_model->update("",$md,$where); // 회원정보 업데이트

		$json['resultItem'] = json_date_success($method,"As member information is modified");
		echo json_encode($json);
	}





	/////////////-------------------- 비밀번호 변경 ----------------------///////////////////
	public function find_pass(){

		$method = $this->input->post("method");
		$sm['sm_content'] = $this->input->post("sm_content");
		$sm['sm_hp'] = $this->input->post("sm_hp");

		$mem['mem_phone'] = $this->input->post("sm_hp");
		$mem['mem_userid'] = $this->input->post("mem_userid");

		$mb_data = $this->Member_model->get_one("","",$mem);
		$sm_data = $this->Sms_data_model->get_one("","",$sm);

		if(count($mb_data) < 2){
		 	$json['resultItem'] = json_date_fail($method,"해당하는 회원아이디가 없습니다");
		}

		if(count($sm_data) < 2){
		 	$json['resultItem'] = json_date_fail($method,"인증번호가 일치하지 않습니다");
		}

		$pass = $this->input->post("mem_password");
		$pw['mem_password'] = password_hash($pass, PASSWORD_BCRYPT);
		$this->Member_model->update($mb_data['mem_id'],$pw);

		$this->Sms_data_model->delete("",$sm);
		$json['resultItem'] = json_date_success($method,"비밀번호가 변경되었습니다");
		echo json_encode($json);

	}

	//////////////-------------------- 아이디를 찾음-----------------------///////////////////
	public function find_id(){

		$method = $this->input->post("method");
		$sm['sm_content'] = $this->input->post("sm_content");
		$sm['sm_hp'] = $this->input->post("sm_hp");
		$mem['mem_phone'] = $this->input->post("sm_hp");
		$mb_data = $this->Member_model->get_one("","",$mem);
		$sm_data = $this->Sms_data_model->get_one("","",$sm);


		if(count($sm_data) < 2){
		 	$json['resultItem'] = json_date_fail($method,"인증번호가 일치하지 않습니다");
		}

		if(count($mb_data) < 2){
		 	$json['resultItem'] = json_date_fail($method,"해당하는 회원아이디가 없습니다");
		}

		$this->Sms_data_model->delete("",$sm);
		$json['mem_userid'] = $mb_data['mem_userid'];
		$json['resultItem'] = json_date_success($method,"회원아이디입니다");
		echo json_encode($json);

	}
	//////////-------------------- 회원가입시 문자인증 보냄-----------////////////
	public function join_sms_send(){
		$method = $this->input->post("method");
		$jo['jo_hp'] = $this->input->post("jo_hp");
		$this->Join_sms_model->delete("",$jo);
		$hp = $jo['jo_hp'];

		// if(preg_match("/^01[0-9]-([0-9]{4})-([0-9]{4})$/", $hp)){
		//
		// 	$jo['jo_content'] = mt_rand(1000,9999);
		// 	$this->Join_sms_model->insert($jo);
		// 	$json['jo_content'] = $jo['jo_content'];
		// 	$this->server_send_sms($jo['jo_content'],$hp);
		// 	$json['resultItem'] = json_date_success($method,"인증번호가 전송되었습니다");
		//
		// 	echo json_encode($json);
		// }else {
		// 	$json['resultItem'] = json_date_fail($method,"올바른휴대폰 번호를 입력 해 주세요");
		// }
	}
	//////////-------------------- 회원가입시 문자인증 받음------------////////////

	public function join_sms_find(){
		$method = $this->input->post("method");
		$jo['jo_hp'] = $this->input->post("jo_hp");
		$jo['jo_content'] = $this->input->post("jo_content");
		$rs = $this->Join_sms_model->count_by($jo);

		if($rs == 1){
			$this->Join_sms_model->delete("",$jo);
			$json['resultItem'] = json_date_success($method,"올바른 인증번호입니다");
			echo json_encode($json);
		}else{
			json_date_fail($method,"인증번호가 올바르지않습니다");
		}

	}




}

// /$this->Json_log_model->add_log();
// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
