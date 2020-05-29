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
class App_tail extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
protected $models = array('Common',
													'shop/Shop_com',
													'shop/Shop_gumi',
													'shop/Shop_item',
													'shop/Shop_img',
													'shop/Country',
                          'shop/Shop_tail',
													'Member',
													'Point',
													'Member_userid',
													'Member_extra_vars',
                          'shop/Jang');

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

	/////////////-------------------- 댓글리스트------------------////////////////////

  public function tail_list(){
    $method = $this->input->post("method");
    $co['co_idx'] = $this->input->post("co_idx");
		$co['ta_con'] = "view";
    $this->db->order_by("ta_idx", "asc");

		$select = "*";
		$select .= ",(SELECT mem_nickname FROM cb_member AS me WHERE cb_shop_tail.mem_userid = me.mem_userid) AS mem_nickname";
		$select .= ",(SELECT mem_nickname FROM cb_member AS mt WHERE cb_shop_tail.mem_userid_to = mt.mem_userid) AS mem_nickname_to";
		$select .= ",(SELECT mem_icon FROM cb_member AS mi WHERE cb_shop_tail.mem_userid = mi.mem_userid) AS mem_icon";

    $ta_list = $this->Shop_tail_model->get("",$select,$co);

		foreach ($ta_list as $key => $val) {
			$mem_icon = app_member_icon_url($val['mem_icon']);
			if(!$mem_icon){$mem_icon = "";} // app_member_icon_url 면 null 처리
			$ta_list[$key]['mem_icon'] = $mem_icon;

		}

    $json['ta_list']= $ta_list;
		$json['resultItem'] = json_date_success($method,"댓글 리스트 입니다");
    echo json_encode($json);
  }
	/////////////-------------------- 댓글등록------------------////////////////////
  public function tail_add(){
    $method = $this->input->post("method");
    $ta = $this->Common_model->make_arr_data("ta_");
    $ta['co_idx'] = $this->input->post("co_idx");
    $ta['mem_userid'] = $this->input->post("mem_userid");
    $ta['mem_userid_to'] = $this->input->post("mem_userid_to");

    $this->Shop_tail_model->insert($ta);
    $json['resultItem'] = json_date_success($method,"댓글을 등록했습니다");
    echo json_encode($json);

  }



  /////////////-------------------- 댓글수정------------------////////////////////

  public function tail_update(){
    $method = $this->input->post("method");
    $ta['ta_content'] = $this->input->post("ta_content");
    $ta_idx = $this->input->post("ta_idx");

    $this->Shop_tail_model->update($ta_idx,$ta);
    $json['resultItem'] = json_date_success($method,"댓글을 수정했습니다");
    echo json_encode($json);
  }

  /////////////-------------------- 댓글삭제------------------////////////////////
  public function tail_remove(){
    $method = $this->input->post("method");
    $ta_idx = $this->input->post("ta_idx");
    $this->Shop_tail_model->delete($ta_idx);
    $json['resultItem'] = json_date_success($method,"댓글을 삭제 했습니다");
    echo json_encode($json);
  }
}



// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
