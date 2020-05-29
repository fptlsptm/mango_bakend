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
class App_zzim extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
protected $models = array('Common','shop/Shop_com_like','shop/Shop_com_zzim');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'string','json');

	function __construct()
	{
		parent::__construct();


	}
  public function index($value=''){
    echo "잘못된 접근입니다.";
  }
  /////////////-------------------- 찜하기 추가합니다------------------////////////////////
  public function zzim_add(){
    $method = $this->input->post("method");
    $zi['co_idx'] = $this->input->post("co_idx");
    $zi['mem_userid'] = $this->input->post("mem_userid");

    $ck = $this->Shop_com_zzim_model->count_by($zi);

    if($ck == 0){
      $this->Shop_com_zzim_model->insert($zi);
    }else{
      json_date_fail($method,"이미 추가하신 목록입니다");
    }

    $json['resultItem'] = json_date_success($method,"찜하기목록에 추가되었습니다");
    echo json_encode($json);
  }

  /////////////-------------------- 찜하기 취소------------------////////////////////

  public function zzim_remove(){
    $method = $this->input->post("method");
    $zi['co_idx'] = $this->input->post("co_idx");
    $zi['mem_userid'] = $this->input->post("mem_userid");

    $this->Shop_com_zzim_model->delete("",$zi);

    $json['resultItem'] = json_date_success($method,"찜목록에서 제거되었습니다");
    echo json_encode($json);
  }

  /////////////-------------------- 좋아요 & 취소------------------////////////////////
  public function zzim_auto(){
    $method = $this->input->post("method");
    $zi['co_idx'] = $this->input->post("co_idx");
    $zi['mem_userid'] = $this->input->post("mem_userid");

    $ck = $this->Shop_com_zzim_model->count_by($zi);
    if($ck == 0){
      $this->Shop_com_zzim_model->insert($zi);
      $json['resultItem'] = json_date_success($method,"찜목록에서 추가되었습니다");
      echo json_encode($json);
    }else{
      $this->Shop_com_zzim_model->delete("",$zi);
      $json['resultItem'] = json_date_success($method,"찜목록에서 제거되었습니다");
      echo json_encode($json);
    }
  }
  /////////////-------------------- 좋아요 목록보기------------------////////////////////
  public function zzim_list(){
    $method = $this->input->post("method");
    $mem_userid = $this->input->post("mem_userid");
		$order = $this->input->post("order");

    $zi_list = $this->Shop_com_zzim_model->zzim_list($mem_userid,$order);

    $json['zi_list'] = $zi_list;
    $json['resultItem'] = json_date_success($method,"찜한목록 입니다");
    echo json_encode($json);
  }


}



// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
