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
class App_bank extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
protected $models = array('Common','shop/Bank');

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

	/////////////-------------------- 환불 계좌 목록------------------////////////////////

  public function ba_list(){
    $method = $this->input->post("method");
    $me['mem_userid'] = $this->input->post("mem_userid");

    $this->db->order_by("ba_idx", "desc");
    $ba_list = $this->Bank_model->get_one("","",$me);

    $json['ba_list']= $ba_list;
		$json['resultItem'] = json_date_success($method,"환불계좌목록 입니다");
    echo json_encode($json);
  }
  //////////// -----------------  환불 계좌를 추가합니다.---------///////////////

  public function ba_add(){

    $method = $this->input->post("method");
    $ba['ba_bank'] = $this->input->post("ba_bank");
		$ba['ba_name'] = $this->input->post("ba_name");
    $ba['ba_bank_num'] = $this->input->post("ba_bank_num");
    $ba['mem_userid'] = $this->input->post("mem_userid");

    $this->Bank_model->insert($ba);
    $json['resultItem'] = json_date_success($method,"환불계좌를 추가했습니다");
    echo json_encode($json);

  }
  /////////////-------------------- 계좌수정------------------////////////////////
  public function ba_update(){
    $method = $this->input->post("method");
    $ba['ba_bank'] = $this->input->post("ba_bank");
		$ba['ba_name'] = $this->input->post("ba_name");
    $ba['ba_bank_num'] = $this->input->post("ba_bank_num");

    $ba_idx = $this->input->post("ba_idx");

    $this->Bank_model->update($ba_idx,$ba);

    $json['resultItem'] = json_date_success($method,"환불계좌를 수정했습니다");
    echo json_encode($json);
  }

	////// ----------- 은행목록-----------/////////////
	public function bank_list(){
		$method = $this->input->post("method");
    $json['bank_list'] = ["기업은행","국민은행","수협은행","농협은행","지역농․축협","우리은행","SC제일은행","씨티은행","대구은행",
                          "부산은행","광주은행","제주은행","전북은행","경남은행","우리카드","외환카드","새마을금고중앙회","신협","우체국",
                          "KEB하나은행","신한은행","카카오뱅크"];
	  $json['resultItem'] = json_date_success($method,"은행목록 입니다");
    echo json_encode($json);

	}

}



// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
