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
class App_addr extends CB_Controller{

	/**
	 * 모델을 로딩합니다
	 */
protected $models = array('Common','Member');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'string','json');

	function __construct()
	{
		parent::__construct();
		header('Access-Control-Allow-Origin: http://1.234.44.171:3000');
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
	// 구글지도로 위치값을 찾음
	public function search_locations($place_id){
		$ch = curl_init();
		$url = "https://maps.googleapis.com/maps/api/place/details/json?";
		$data['place_id']=$place_id;
		//$data['inputtype']="textquery";
		//$data['fields']="photos,formatted_address,name,rating,opening_hours,geometry";
		$data['key']="AIzaSyCKuiFCF26I-4dSO02YctV_BUA85QazyuY";
		//$data['language']="en";

		foreach ($data as $key => $value) {$parm .="{$key}={$value}&";}
		$url = substr($url.$parm,0,-1);

	  curl_setopt( $ch,CURLOPT_URL,$url);
	  curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	  curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	  $result = curl_exec($ch);
	  curl_close( $ch );
		//echo $result;
		$rs = json_decode($result,true);

		json_date_success("search_locations","success",$rs['result']['geometry']['location']);

	}
	// 구글지도로 위치값을 찾음
	public function search_latlng($val){
		$ch = curl_init();
		$url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?";
		$data['input']=$val;
		$data['inputtype']="textquery";
		$data['fields']="photos,formatted_address,name,rating,opening_hours,geometry";
		$data['key']="AIzaSyCKuiFCF26I-4dSO02YctV_BUA85QazyuY";
		$data['language']="en";

		foreach ($data as $key => $value) {$parm .="{$key}={$value}&";}
		$url = substr($url.$parm,0,-1);

	  curl_setopt( $ch,CURLOPT_URL,$url);
	  curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	  curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	  $result = curl_exec($ch);
	  curl_close( $ch );
		//echo $result;
		$rs = json_decode($result,true);
		//var_dump($rs);
		//echo $rs['candidates'];
		//echo json_encode($rs['candidates'][0]);
		foreach ($rs['predictions'] as $key => $value){
			$arr['place_id'] = $value['place_id'];
			$arr['description'] = $value['description'];
			$json[] = $arr;
		}

		if($val == ""){json_date_fail("search_locations","Please enter your search term");}
		if(count($json)==0){json_date_fail("search_locations","Please enter a valid address");}
		json_date_success("search_locations","success",$json);
	}




}



// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
