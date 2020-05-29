<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class App_main extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
   protected $models = array('Common','Member','Order');
	protected $helpers = array('form', 'array', 'string','json');

	function __construct()
	{
		parent::__construct();
	}
  public function index($value=''){
    echo "잘못된 접근입니다.";
  }

}



// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
