<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * json helper
 *
 * Copyright 디몬스터 작성자 김영준
 *
 * ver_1.1
 */
// 성공적으로 로드했을때
if ( ! function_exists('json_date_success')) {
   function json_date_success($method,$msg,$arr){
     $rs_arr['method'] = $method;
     $rs_arr['msg'] = $msg;
     $rs_arr['result'] = "Y";
     $json['resultItem'] = $rs_arr;
     $json['item'] = $arr;
     echo json_encode($json,JSON_UNESCAPED_UNICODE);
     exit();
  }
}
// 로드를 실패 했을때
if ( ! function_exists('json_date_fail')) {
   function json_date_fail($method,$msg){
     $rs_arr['method'] = $method;
     $rs_arr['msg'] = $msg;
     $rs_arr['result'] = "N";
     $json['resultItem'] = $rs_arr;
     echo json_encode($json,JSON_UNESCAPED_UNICODE);
     exit();
  }
}

// null 체크
if ( ! function_exists('json_null_ck')) {
   function json_null_ck($method,$data_arr){

    foreach ($data_arr as $key => $value) {

      if($value == ""){
        $rs_arr['method'] = $method;
        $rs_arr['msg'] = " '{$key}' 값이 누락되었습니다";
        $rs_arr['result'] = "N";
        $json['resultItem'] = $rs_arr;
        echo json_encode($json,JSON_UNESCAPED_UNICODE);
        exit();
      }
    }
  }
}


//포스트로 들어온 배열키를 변수 이름으로 바꿈니다.
if ( ! function_exists('post_is')) {
   function post_is(){
    if (count($_POST) > 0){
      foreach ($_POST as $key => $value) {
        $$key = $value;
      }
    }
  }
}

?>
