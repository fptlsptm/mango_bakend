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
class App_like extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
protected $models = array('Common','shop/Shop_com_like');

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
  /////////////-------------------- 좋아요를 눌음------------------////////////////////
  public function co_like(){
    $method = $this->input->post("method");
    $li['co_idx'] = $this->input->post("co_idx");
    $li['mem_userid'] = $this->input->post("mem_userid");

    $ck = $this->Shop_com_like_model->count_by($li);
    if($ck == 0){
      $this->Shop_com_like_model->insert($li);
      $this->Shop_com_like_model->co_like($li['co_idx']);
    }else{
      json_date_fail($method,"이미 좋아요를 하셨습니다");
    }

    $json['resultItem'] = json_date_success($method,"좋아요!");
    echo json_encode($json);
  }

  /////////////-------------------- 좋아요 취소------------------////////////////////

  public function co_like_remove(){
    $method = $this->input->post("method");
    $li['co_idx'] = $this->input->post("co_idx");
    $li['mem_userid'] = $this->input->post("mem_userid");

    $this->Shop_com_like_model->delete("",$li);
    $this->Shop_com_like_model->co_like_remove($li['co_idx']);

    $json['resultItem'] = json_date_success($method,"좋아요취소");
    echo json_encode($json);
  }

  /////////////-------------------- 좋아요 & 취소------------------////////////////////
  public function co_auto_like(){
    $method = $this->input->post("method");
    $li['co_idx'] = $this->input->post("co_idx");
    $li['mem_userid'] = $this->input->post("mem_userid");

    $ck = $this->Shop_com_like_model->count_by($li);
    if($ck == 0){
      $this->Shop_com_like_model->insert($li);
      $this->Shop_com_like_model->co_like($li['co_idx']);
      $json['resultItem'] = json_date_success($method,"좋아요!");
      echo json_encode($json);
    }else{
      $this->Shop_com_like_model->delete("",$li);
      $this->Shop_com_like_model->co_like_remove($li['co_idx']);
      $json['resultItem'] = json_date_success($method,"좋아요취소");
      echo json_encode($json);
    }
  }
  /////////////-------------------- 좋아요 목록보기------------------////////////////////
  public function co_like_list(){
    $method = $this->input->post("method");
    $mem_userid = $this->input->post("mem_userid");

    $li_list = $this->Shop_com_like_model->co_like_list($mem_userid);
    $json['li_list'] = $li_list;
    $json['resultItem'] = json_date_success($method,"좋아요 목록 ");
    echo json_encode($json);
  }


}



// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
