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
class Chat extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
protected $models = array('Common','Member','Config','Accompany','Room','Room_member','Chat');

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
  public function index($mem_userid){
    echo "잘못된 접근입니다";
  }
  public function list($mem_userid){
    $where['ro_type'] = "001"; // acom
    $where['rm.mem_userid'] = $mem_userid;
    $rs = $this->Room_member_model->room_member_acom($where);
    // 1:1 채팅시 사용 지우지 말자
    // $where['my.mem_userid'] = $mem_userid;
    // $rs = $this->Room_member_model->room_member_around($where);

    echo json_encode($rs);
  }
  public function add_chat(){
    $data['ro_idx'] = $this->input->post('ro_idx');
    $data['ch_msg'] = $this->input->post('ch_msg');
    $data['mem_userid'] = $this->input->post('mem_userid');
    if($data['ch_msg'] == ""||$data['ro_idx'] == "" ||$data['mem_userid'] == ""){echo "실패"; exit;}
    $this->Chat_model->insert($data);
  }
  public function chat_room($ro_idx){
    $where['ro_idx'] = $ro_idx;
    $method = $this->input->post("method");
    $arr = $this->Chat_model->chat_room($where);
    $arr = array_reverse($arr);
    if(count($arr) > 0){
      json_date_success($method,"ok chat",$arr);
    }else {
      json_date_fail($method,"no chat");
    }
  }
	public function chat_info($ro_idx){
		$arr['mem_list'] = $this->Chat_model->chat_info($ro_idx);
		$ro = $this->Room_model->get_one($ro_idx);
		$arr['accompany'] = $this->Accompany_model->get_one($ro['ac_idx']);


		json_date_success($method,"ok chat",$arr);
	}

}

// /$this->Json_log_model->add_log();
// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
