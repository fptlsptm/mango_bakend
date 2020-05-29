<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Common_model extends CB_Model{
  public $cf;
  protected $CI;

	/**
	 * 테이블명
	 */
	public $_table = 'member';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'mem_id'; // 사용되는 테이블의 프라이머리키

	public $search_sfield = '';

	function __construct(){
    $cf = array();
    $cf['file_upload_size_member'] = 20000000;//6mb 1mb = 1,048,576
    $this->CI =& get_instance();
    parent::__construct();
	}
  ////컴온 로드확인------------
  function hello_common(){echo "hello_common";}
  /// 옷사진을 이미서버로 던짐 경로설정 가능
  function ftp_uploads_dir($ori_img,$dir,$ftp_dir){

       $source_file    = $dir.$ori_img;  // 원래 저장된 폴더의 경로와 파일명(상대 경로)
       $target_file    = $ori_img;      // ftp에 업로드할 파일명
       $ftp_server    = "172.27.0.31";
       $user          = "original";
       $pass          = "dhflwlskf";
       $port          = "21";
       $chdir         = $ftp_dir;
       $mode          = FTP_BINARY;              // mode

       $conn_id = ftp_connect($ftp_server,$port);

       if($conn_id)
       {
           $login_result = ftp_login($conn_id,$user,$pass);
       }
       if((!$conn_id) || (!$login_result))
       {
           echo "Ftp connect has Failed !<br>";
           echo "Try again!";
           return;
       } else {
           $dir = ftp_chdir($conn_id,$chdir) or die ('디렉토리 변경 실패');
           $up  = ftp_put($conn_id, $target_file ,$source_file ,$mode);
           if(!$up){die ('upload 실패');}
            // 접속  ,  업로드될파일,원본 파일명  ,모드
       }
       $ftp['img'] = $ftp_dir.$target_file;
       $ftp['server'] = $ftp_server;
       return $ftp;

   }


  /// 옷사진을 이미서버로 던짐 경로가 옷사진임
  function ftp_uploads($ori_img){

       $dir = './uploads/img/';
       $ftp_dir = "/close_img";
       $source_file    = $dir.$ori_img;    // 원래 저장된 폴더의 경로와 파일명(상대 경로)
       $target_file    = $ori_img;      // ftp에 업로드할 파일명
       $ftp_server    = "172.27.0.31";
       $user          = "original";
       $pass          = "dhflwlskf";
       $port          = "21";
       $chdir         = $ftp_dir;
       $mode          = FTP_BINARY;              // mode

       $conn_id = ftp_connect($ftp_server,$port);

       if($conn_id)
       {
           $login_result = ftp_login($conn_id,$user,$pass);
       }
       if((!$conn_id) || (!$login_result))
       {
           echo "Ftp connect has Failed !<br>";
           echo "Try again!";
           return;
       } else {
           $dir = ftp_chdir($conn_id,$chdir) or die ('디렉토리 변경 실패');
           $up  = ftp_put($conn_id, $target_file ,$source_file ,$mode);
           if(!$up){die ('upload 실패');}
            // 접속  ,  업로드될파일,원본 파일명  ,모드
       }
       $ftp['img'] = $ftp_dir.$target_file;
       $ftp['server'] = $ftp_server;
       return $ftp;

   }

	/*파일업로드(ciborad)*/
  /*2019 03 06 김영준*/
	function CIUpload($files, $upload_path){

		if(!class_exists("upload")) $this->CI->load->library('upload');

		$ref = array('error'=>'true', 'msg'=>'파일값이없습니다.', 'name'=>'', 'ori'=>'');
		if (isset($files) && isset($files['name']) && $files['name']) {
			$uploadconfig = '';
			$uploadconfig['upload_path'] = $upload_path;
			if($allowed_types!='') $uploadconfig['allowed_types'] = $allowed_types;
			else $uploadconfig['allowed_types'] = 'jpg|jpeg|png|gif|pdf|avi|flv|mov|mkv|csv|mp3|mp4|zip|tar|rar|doc|ppt|pptx|docx|docm|dotx|dotm|xls|xla|xlt|xlw|odt|odp|ods|odc|odf|wp';

			$uploadconfig['max_size'] = $this->cf['file_upload_size_member'];
			$uploadconfig['max_width'] = '9999999';
			$uploadconfig['max_height'] = '99999999';
			$uploadconfig['encrypt_name'] = true;
			$this->CI->upload->initialize($uploadconfig);
			$_FILES['userfile']['name'] = $files['name'];
			$_FILES['userfile']['type'] = $files['type'];
			$_FILES['userfile']['tmp_name'] = $files['tmp_name'];
			$_FILES['userfile']['error'] = $files['error'];
			$_FILES['userfile']['size'] = $files['size'];

			if ($this->CI->upload->do_upload()) {
				$filedata = $this->CI->upload->data();
				$uploadfiledata['nte_filename'] = element('file_name', $filedata);
				$uploadfiledata['nte_originname'] = element('orig_name', $filedata);
				$ref['error']  = 'false';
				$ref['msg'] = '성공';
        $ref['type'] =  $files['type'];
				$ref['name'] = $uploadfiledata['nte_filename'];
				$ref['ori'] = $uploadfiledata['nte_originname'];
			} else {
				$file_error = $this->CI->upload->display_errors("","");
				$ref['error']  = 'true';
				$ref['msg'] = $file_error;
				$ref['name'] = '';
				$ref['ori'] = '';
			}
		}

		return $ref;
	}


  /*파일업로드(ciborad) 배열로*/
  function CIUpload_arr($files, $upload_path, $i){

		if(!class_exists("upload")) $this->CI->load->library('upload');

		$ref = array('error'=>'true', 'msg'=>'파일값이없습니다.', 'name'=>'', 'ori'=>'');
		if (isset($files) && isset($files['name'][$i]) && $files['name'][$i]) {
			$uploadconfig = '';
			$uploadconfig['upload_path'] = $upload_path;
			if($allowed_types!='') $uploadconfig['allowed_types'] = $allowed_types;
			else $uploadconfig['allowed_types'] = 'jpg|jpeg|png|gif|pdf|avi|flv|mov|mkv|csv|mp3|mp4|zip|tar|rar|doc|ppt|pptx|docx|docm|dotx|dotm|xls|xla|xlt|xlw|odt|odp|ods|odc|odf|wp';

			$uploadconfig['max_size'] = $this->cf['file_upload_size_member'];
			$uploadconfig['max_width'] = '9999999';
			$uploadconfig['max_height'] = '99999999';
			$uploadconfig['encrypt_name'] = true;
			$this->CI->upload->initialize($uploadconfig);
			$_FILES['userfile']['name'] = $files['name'][$i];
			$_FILES['userfile']['type'] = $files['type'][$i];
			$_FILES['userfile']['tmp_name'] = $files['tmp_name'][$i];
			$_FILES['userfile']['error'] = $files['error'][$i];
			$_FILES['userfile']['size'] = $files['size'][$i];

			if ($this->CI->upload->do_upload()) {
				$filedata = $this->CI->upload->data();
				$uploadfiledata['nte_filename'] = element('file_name', $filedata);
				$uploadfiledata['nte_originname'] = element('orig_name', $filedata);
				$ref['error']  = 'false';
				$ref['msg'] = '성공';
        $ref['type'] =  $files['type'][$i];
				$ref['name'] = $uploadfiledata['nte_filename'];
				$ref['ori'] = $uploadfiledata['nte_originname'];
        $this->ftp_uploads($ref['name']);
			} else {
				$file_error = $this->CI->upload->display_errors("","");
				$ref['error']  = 'true';
				$ref['msg'] = $file_error;
				$ref['name'] = '';
				$ref['ori'] = '';
			}

		}

		return $ref;
	}


  ///-------------------------------------------------------------------------------------------------------------//
  function kakao_location_call($keys,$params){
     $headers = array(
           'Authorization: KakaoAK '. $keys,
           'Content-Type: application/x-www-form-urlencoded;charset=utf-8'
     );


     $ch = curl_init();
     curl_setopt( $ch,CURLOPT_URL, 'https://dapi.kakao.com/v2/local/search/address.json' );
     curl_setopt( $ch,CURLOPT_POST, true );
     curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
     curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
     curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, true );
     curl_setopt( $ch,CURLOPT_SAFE_UPLOAD, true );
     curl_setopt( $ch,CURLOPT_POSTFIELDS, $params );

     $result = curl_exec($ch );
     curl_close( $ch );

     $res = json_decode($result, true);


     return $res;
   }

  // 주소를 넣으면 좌표로 변환함
  function load_latlng($address){
    $address_url = urlencode($address);
    $keys = "4b17c8097e33c5760b4bc073a7d46b94";
    $params = "query=".$address_url;
    $locations = $this->kakao_location_call($keys,$params);
    return $locations;

  }
  // post를 받을떄 키로 값을 받음
  function make_arr_data($key_name){
    foreach ($_REQUEST as $key => $value) {
      if(strpos($key, $key_name) !== false){
        $rs[$key] = $value;
      }
    }
    return $rs;
  }
  // post를 받을떄 배열로된키를 $key_name 가 포함된 값을 뺴고 받아옴
  function make_arr_data_key($key_name){
    foreach ($_REQUEST as $key => $value) {
      if(strpos($key, $key_name) !== false){
        $key = str_replace($key_name,"",$key);
        $rs[$key] = $value;
      }
    }
    return $rs;
  }
  // post를 받을떄 배열로된키를 받을때 사용
  function make_arr_data_arr($key_name,$count_key){
    //$count_key 배열 위치를 바꿔 줄때 필요한 $key_name 중의 기준이 되는키
    foreach ($_REQUEST as $key => $value) {
      if(strpos($key, $key_name) !== false && is_array($value)){
        $rs[$key] = $value;
      }
    }
    for ($i=0; $i <count($rs[$count_key]); $i++) {
      foreach ($rs as $key => $val) {
        $data[$i][$key] =  $rs[$key][$i];
      }
    }
    return $data;
  }
  //$pre_page 현재 페이지
  function show_page($limit,$all_count,$addr,$pre_page){
    $ck_pre = $pre_page;
    if(count($_GET) > 0){
      $get_data = "?";
      foreach ($_GET as $key => $value) {
        $get_data .= "{$key}={$value}&";
      }
    }

    $all_page_count = ceil($all_count/$limit);

    if($pre_page <= 0){$pre_page = 1;}
    $page_ck = floor($pre_page/5)+1;

    $page_count = $page_ck * 5;

    $next = $page_count;

    if($next > $all_page_count){$next = $all_page_count;}

    $back = $page_count -5;
    $page_back = $back -1;
    if($back < 0){$back = 0;}
    if($page_back < 0){$page_back = 0;}

    $data .= "<div class='page_box'>";

    $data .= "<a href ='{$addr}{$page_back}{$get_data}'>이전</a>";
    for ($i= $back; $i <$next ; $i++) {
      $ic = $i+1;

      if($ck_pre == $i){$on = "ck_on";}else{$on = "";}
      $data .= "<a class='{$on}' href ='{$addr}{$i}{$get_data}'>{$ic}</a>";
    }
    $data .= "<a href ='{$addr}{$next}{$get_data}'>다음</a>";
    $data .= "</div>";

    return $data;
  }
  //// ----------------  상품의 최대값과 최솟값을 가져옵니다 -----------------//////
  function it_price_maxmin(){
    $sql_max = "SELECT MAX(it_price) as max FROM cb_shop_item ";
    $rs_max = $this->db->query($sql_max)->result_array();

    $sql_min = "SELECT MIN(it_price) as min FROM cb_shop_item ";
    $rs_min = $this->db->query($sql_min)->result_array();

    $rs['min'] = $rs_min[0]['min'];
    $rs['max'] = $rs_max[0]['max'];

    return $rs;
  }

}
