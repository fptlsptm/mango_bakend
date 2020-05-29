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
class App_com extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
protected $models = array('Common',
													'shop/Shop_com',
													'shop/Shop_gumi',
													'shop/Shop_item',
													'shop/Shop_com_singo',
													'shop/Order_item',
													'shop/Shop_img',
													'shop/Json_log',
													'shop/Shop_tail',
													'shop/Country',
													'shop/Shop_tail',
													'shop/App_banner',
													'Member',
													'Point',
													'Member_userid',
													'shop/Shop_com_like',
													'shop/Shop_com_zzim',
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

	/////////////-------------------- 후기 리스트 로드 ------------------////////////////////

	public function com_main_list(){
		$method = $this->input->post("method");
    $btn['co_tall'] = $this->input->post("mem_tall");
		$btn['co_kg'] = $this->input->post("mem_kg");
		$btn['co_age'] = $this->input->post("mem_age");
		$mem_sex = $this->input->post("mem_sex");
		$page = $this->input->post("page");

		if($page == ""){$page == 0;}

		$price_max = $this->input->post("price_max");
		$price_min = $this->input->post("price_min");
		$color = $this->input->post("color");

		$color = array_filter(array_map('trim',$color));

		if($price_max != "" && $price_min != ""){
			$where['price_max'] = $price_max;
			$where['price_min'] = $price_min;
		}

		if(count($color) > 0){
			$where['color'] = $color;
		}

		$com['basic'] = $this->Shop_com_model->main_list_load($btn,$mem_sex,$where,"c.co_idx",$page);
		$com['order'] = $this->Shop_com_model->main_list_load($btn,$mem_sex,$where,"c.co_order_count",$page);
		$com['mem'] = $this->Shop_com_model->main_list_load($btn,$mem_sex,$where,"m.mem_id",$page);

		$ba = $this->App_banner_model->get_one(100);
		$ba_ck = count($com['basic'])+count($com['basic'])+count($com['basic']);
		/// 디바이스 체크해서 데이터를 다르게 뿌려줌


		if($page == 0){
			$datas['co_count'] = "5C";
			$datas['co_idx'] = "836";
			$datas['mem_nickname'] = $ba['ba_name'];
			$datas['ba_url'] = $ba['ba_url'];
			$datas['im_img'] = $ba['ba_img'];
			$datas['ev_idx'] = "0";
			$com_list[] = $datas;
			$ick = 15;
		}else {
			$ick = 15;
		}


		/////// ----- 여러가지 종류로 데이터를 받다보니... 추후 데이터 많아지면 수정 ---- ///////
		for ($i=0; $i < $ick ; $i++){
			if(count($com['basic'][$i]) > 0){$com_list[] = $com['basic'][$i];$list_c++;}
			if(count($com['order'][$i]) > 0){$com_list[] = $com['order'][$i];$list_c++;}
			if(count($com['mem'][$i]) > 0){$com_list[] = $com['mem'][$i];$list_c++;}
		}

		//쿼리문 디버깅하는부분 -- >> 지우지말자
		// $qry = $this->db->last_query();
		// $json['qry']= $qry;
		//카운터 디버깅하는부분
		// $count['basic'] = count($com['basic']);
		// $count['order'] = count($com['order']);
		// $count['mem'] = count($com['mem']);
		// $json['count']= $count;

		$json['com_list']= $com_list;
		$json['resultItem'] = json_date_success($method,"후기 리스트 입니다");

		echo json_encode($json);
  }
/////////////-------------------- 후기의 상세 정보를 가져옵니다------------------////////////////////
	public function com_info(){

		$method = $this->input->post("method");
    $co_idx = $this->input->post("co_idx");
		$mem_userid = $this->input->post("mem_userid");

		$com_info = $this->Shop_com_model->get_one($co_idx);
		$im_where['co_idx'] = $com_info['co_idx'];
		$img_info = $this->Shop_img_model->get_one("","",$im_where);

		$select = "it_name,it_price,it_get_count,it_idx,it_get_count,it_img,mem_userid,ev_idx";
		$select_mem = "mem_nickname,mem_icon";
		if($com_info['it_idx_sub'] == 0){$com_info['it_idx_sub'] = "null";}

		$it_info = $this->Shop_item_model->get_one($com_info['it_idx'],$select);
		$it_info_sub = $this->Shop_item_model->get_one($com_info['it_idx_sub'],$select);

		if($it_info['it_get_count'] <= 0){
			json_date_fail($method,"제고가 부족한상품입니다");
		}

		$sc_mem['mem_userid'] = $com_info['mem_userid'];
		$mem_info = $this->Member_model->get_one("",$select_mem,$sc_mem);

		$mem_info['mem_icon'] = app_member_icon_url($mem_info['mem_icon']);

		if(!$mem_info['mem_icon']){$mem_info['mem_icon'] = "";} // app_member_icon_url 면 null 처리

		$ta_w['co_idx'] = $co_idx;
		$ta_w['ta_con'] = "view";
		$ta_count = $this->Shop_tail_model->count_by($ta_w); //후기의 댓글 개수

		$si_sc_arr['mem_userid'] = $it_info['mem_userid'];
		$sa_sc_arr['it_idx'] = $it_info['it_idx'];

		$similar_list = $this->Shop_com_model->main_list_load("",$com_info['co_sex'],$si_sc_arr); /// 유사상품 후기들
		$same_list = $this->Shop_com_model->main_list_load("",$com_info['co_sex'],$sa_sc_arr); /// 같은 상품 후기들

		$count_list['ta_count'] = $ta_count;
		$count_list['similar_count'] = count($similar_list);
		$count_list['same_count'] = count($same_list);

		$com_info['ev_idx'] = $it_info['ev_idx'];


		//$com_info['ev_idx'] = $it_info_sub['ev_idx'];

		// 찜 좋아요 체크하는부분
		$ck_data['co_idx'] = $co_idx;
    $ck_data['mem_userid'] = $mem_userid;

    $ck_like = $this->Shop_com_like_model->count_by($ck_data);
		$ck_zzim = $this->Shop_com_zzim_model->count_by($ck_data);

		$like_zzim['ck_like'] = "N";
		$like_zzim['ck_zzim'] = "N";

    if($ck_like > 0){$like_zzim['ck_like'] = "Y";}
		if($ck_zzim > 0){$like_zzim['ck_zzim'] = "Y";}

		//// ---json 넣는 부분-----

		$json['it_info']= $it_info;
		$json['it_info_sub']= $it_info_sub;

		$json['com_info']= $com_info;
		$json['mem_info']= $mem_info;
		$json['like_zzim']= $like_zzim;
		$json['img_info']= $img_info;
		$json['similar_list'] = $similar_list;
		$json['same_list'] = $same_list;

		$json['count_list']= $count_list;


		$json['resultItem'] = json_date_success($method,"후기 상세정보 입니다");
		echo json_encode($json,JSON_UNESCAPED_UNICODE);
	}
  /////////////-------------------- 유사상품 후기들------------------////////////////////
	public function similar_list(){

		$method = $this->input->post("method");
		$co_idx = $this->input->post("co_idx");

		$co = $this->Shop_com_model->get_one($co_idx);
		$it = $this->Shop_item_model->get_one($co['it_idx']);

		$sc_arr['mem_userid'] = $it['mem_userid'];
		$similar_list = $this->Shop_com_model->main_list_load("",$com_info['co_sex'],$sc_arr); /// 유사상품 후기들


		$json['similar_count'] = count($similar_list);
		$json['similar_list']= $similar_list;
		$json['resultItem'] = json_date_success($method,"유사상품 후기들 입니다");
		echo json_encode($json);

	}
	/////////////-------------------- 같은상품 다른 후기들------------------////////////////////
	public function same_list(){

		$method = $this->input->post("method");
		$co_idx = $this->input->post("co_idx");

		$co = $this->Shop_com_model->get_one($co_idx);

		$sc_arr['it_idx'] = $co['it_idx'];
		$same_list = $this->Shop_com_model->main_list_load("",$co['co_sex'],$sc_arr); /// 같은 상품 후기들

		$json['same_count'] = count($same_list);
		$json['same_list']= $same_list;
		$json['resultItem'] = json_date_success($method,"같은상품 다른 후기들 입니다");
		echo json_encode($json);
	}



 /////////////-------------------- 제품의 상세 정보를 가져옵니다------------------////////////////////
	public function item_info(){
		$method = $this->input->post("method");
    $it_idx = $this->input->post("it_idx");

		$item_info = $this->Shop_item_model->get_one($it_idx);
		$where['it_idx'] = $it_idx;
		$gumi_info = $this->Shop_gumi_model->get("","",$where);

		$it_con = $item_info['it_con'];
		if($it_con == "판매상태"){
			$json['item_info'] = $item_info;
			$json['gumi_info'] = $gumi_info;
			$json['resultItem'] = json_date_success($method,"후기 상세정보 입니다");
		}else {
			json_date_fail($method,"상품이 판매상태가 아닙니다");
		}

		echo json_encode($json,JSON_UNESCAPED_UNICODE);
	}

	/////////////-------------------- 후기정보를 저장합니다 ------------------////////////////////
	///10637 테스트
	public function com_add(){

		$method = $this->input->post("method");
		$mem_userid = $this->input->post("mem_userid");
		$it_idx = $this->input->post("it_idx");
		$it_idx_sub = $this->input->post("it_idx_sub");

		$oi_price1 = $this->input->post("oi_price1");
		$oi_price2 = $this->input->post("oi_price2");
		$dir = './uploads/img/';




		$m_whe['mem_userid'] = $mem_userid;
		$mem = $this->Member_model->get_one("","mem_id,mem_point,mem_nickname",$m_whe);
		$its = $this->Shop_item_model->get_one($it_idx,"it_name");

	  //// ----- 후기 데이터를 저장합니다---- ///
		$co = $this->Common_model->make_arr_data("co_");

		for ($i=0; $i <count($_FILES['co_img']['name']) ; $i++) {
			$co_img = $this->Common_model->CIUpload_arr($_FILES['co_img'],$dir,$i);
			$co_img_name[$i] = $co_img['name'];
		}

		$co['mem_userid'] = $mem_userid;
		$mem_userid = trim($mem_userid);
		$co['it_idx'] = $it_idx;
		$co['it_idx_sub'] = $it_idx_sub;
		$co['co_img'] = implode(",",$co_img_name);
		$co['co_tag'] = "#{$mem['mem_nickname']}#{$its['it_name']}".$co['co_tag'];


		$co_idx = $this->Shop_com_model->insert($co);

		///--- 이미지 테이블 정보 저장------////

		$im = $this->Common_model->make_arr_data("im_");

		$im_img = $this->Common_model->CIUpload($_FILES['im_img'],$dir);
		img_resize(800,800,$dir,$im_img['name'],"",$im_img['type']);

		$this->load->library('file_proc'); // 워터마크 라이브러리
		$path = "./uploads/img/_800x800_{$im_img['name']}";//원본파일
		$mark ="./uploads/point/point.png";//워터마크에 사용할 파일
		$save = "./uploads/img/0000w_{$im_img['name']}";//워터마크 처리한 것을 저장할 파일
		$this->file_proc->img_merge($path,$mark,$save,$im['im_left'],$im['im_top']);
		$this->file_proc->img_merge($save,$mark,$save,$im['im_left_sub'],$im['im_top_sub']);

		$this->Common_model->ftp_uploads("_800x800_{$im_img['name']}");
		$this->Common_model->ftp_uploads("0000w_{$im_img['name']}");

		$im['im_img'] = $im_img['name'];
	 	$im['co_idx'] = $co_idx;
		$im['mem_userid'] = $mem_userid;
		$im['it_idx'] = $it_idx;

		$oi_idx = $this->input->post("oi_idx");
		if($oi_idx != "" && $oi_idx != 0){
			$oi['oi_com_ck'] = "Y";
			$this->Order_item_model->update($oi_idx,$oi);
		}

		$oi_idx_sub = $this->input->post("oi_idx_sub");
		if($oi_idx_sub != "" && $oi_idx_sub != 0){
			$oi['oi_com_ck'] = "Y";
			$this->Order_item_model->update($oi_idx_sub,$oi);
		}


		$im_idx = $this->Shop_img_model->insert($im);

		$up['im_idx'] = $im_idx;
		$this->Shop_com_model->update($co_idx,$up);

		////------- 포인트를 추가함 -------- /////

		$po['poi_point'] = ceil(($oi_price1 + $oi_price2)/100) * 3;
		$po['poi_type'] = "com";
		$po['poi_content'] = "사진후기 작성";
		$po['mem_id'] = $mem['mem_id'];
		$po['poi_related_id'] = $mem['mem_id'];
		$po['poi_action'] = "후기작성";
		$po['mem_userid'] = $mem_userid;

		$this->Point_model->insert($po);
		$po_plus['mem_point'] = $po['poi_point'] + $mem['mem_point'];

		$up_mem['mem_userid'] = $mem_userid;
		if($up_mem['mem_userid'] != ""){
			$this->Member_model->update("",$po_plus,$up_mem);
		}

		////------- 후기를 작성하면 판매자 후기가 내려감 -------- /////

		$it_idx = trim($it_idx);
		$it_idx_sub = trim($it_idx_sub);
		$this->Shop_com_model->ck_seller_item($it_idx,"N");
		$this->Shop_com_model->ck_seller_item($it_idx_sub,"N");


		$json['resultItem'] = json_date_success($method,"후기가 입력되었습니다");




		echo json_encode($json);
	}

	/////////////-------------------- 글자후기등록------------------////////////////////
	public function co_tail_add(){
		$method = $this->input->post("method");

		$oi_price1 = $this->input->post("oi_price1");
		$oi_price2 = $this->input->post("oi_price2");

		if(!$oi_price1){
			$oi_price1 = 0;
		}
		if(!$oi_price2){
			$oi_price2 = 0;
		}

		$ta = $this->Common_model->make_arr_data("ta_");
		$ta['co_idx'] = $this->input->post("co_idx");
		$ta['mem_userid'] = $this->input->post("mem_userid");
		$ta['ta_type'] = "co";

		// 포인트 받는 부분
		$mem_sc['mem_userid'] =  $ta['mem_userid'];
		$mem = $this->Member_model->get_one("","mem_id,mem_point,mem_userid",$mem_sc);

		$po['poi_point'] = ceil(($oi_price1 + $oi_price2)/100);
		$po['poi_type'] = "com";
		$po['poi_content'] = "글자후기 작성";
		$po['mem_id'] = $mem['mem_id'];
		$po['poi_related_id'] = $mem['mem_id'];
		$po['poi_action'] = "후기작성";
		$po['mem_userid'] = $mem['mem_userid'];

		// 포인트 기록 저장
		$this->Point_model->insert($po);

		// 기존 포인트에 포인트 더함
		$po_plus['mem_point'] = $po['poi_point'] + $mem['mem_point'];
		$this->Member_model->update($mem['mem_id'],$po_plus);

		$oi_idx = $this->input->post("oi_idx");
		if($oi_idx != "" && $oi_idx != 0){
			$oi['oi_com_ck'] = "Y";
			$this->Order_item_model->update($oi_idx,$oi);
		}

		/// 댓글 저장
		$this->Shop_tail_model->insert($ta);
		$json['resultItem'] = json_date_success($method,"후기를 등록했습니다");
		echo json_encode($json);

	}



	/////////////-------------------- 해시태그 카운터를 계산합니다 ------------------////////////////////

	public function hash_tag_count(){
		$method = $this->input->post("method");
		$tag = $this->input->post("tag");
		$sex = $this->input->post("sex");
		$co = $this->Common_model->make_arr_data("co_");

		//해시태그 검색을위해 100조건에 맞는 태그를100개 검색

		$com_list = $this->Shop_com_model->hash_tag_count($co,$sex,$tag);
		$com_list[] = $tag;
		if(count($com_list) == 0){json_date_fail($method,"검색결과에 맞는태그가 없습니다"); }



		for ($i=0; $i <count($com_list); $i++) {
			$tag_arr = explode($tag,$com_list[$i]['co_tag']);
			if(substr($tag_arr[1],0,1) != "#"){

				$tag_temp = $tag.$tag_arr[1];
				$tag_rs = explode("#",$tag_temp);
				$tag_rs[1] = str_replace(" ","",$tag_rs[1]);

				/// 중복태그를 제거
				foreach ($tag_list as $key => $val){
					if($val['co_tag'] != "#".$tag_rs[1]){
						$put_ck = true;

					}else {
						$put_ck = false;

						break;
					}
				}

				if($put_ck || count($tag_list) == 0){

					$sc_data['co_tag'] = "#".$tag_rs[1];
					$tc = $this->Shop_com_model->hash_tag_count($co,$sex,$sc_data['co_tag']);

					$tc_data['co_tag'] = "#".$tag_rs[1];
					$tc_data['co_count'] = count($tc);
					$tag_list[] = $tc_data;
				}
			}
		}

		$json['tag_list'] = $tag_list;
		//$json['com_list'] = $com_list;
		$json['resultItem'] = json_date_success($method,"후기검색카운트 입니다");
		echo json_encode($json);
	}

	/////////////-------------------- 해시태그로 리스트를 불러옵니다------------------////////////////////

	public function hash_tag_list(){
		$method = $this->input->post("method");
		$tag = $this->input->post("tag");
		$sex = $this->input->post("sex");
		$co = $this->Common_model->make_arr_data("co_");

		$where['tag'] = $tag;

		$com = $this->Shop_com_model->hash_tag_list($co,$sex,$where);
		$json['com_list'] = $com;
		$json['resultItem'] = json_date_success($method,"후기검색카운트 입니다");
		echo json_encode($json);

	}

	/////////////-------------------- 내가작성한 후기목록을 가져옵니다 .-----------------////////////////////

	public function my_com_list(){
		$method = $this->input->post("method");
		$where['mem_userid'] = $this->input->post("mem_userid");

		$my_list = $this->Shop_com_model->my_com_list("","",$where);

		$json['my_list'] = $my_list;
		$json['resultItem'] = json_date_success($method,"나의 후기목록 입니다");
		echo json_encode($json);

	}

	/////////////-- 후기삭제라 하고 후기상태값을 N 으로 변경해서 리스트에서 지워버립니다. .-----------------////////////////////

	public function com_remove(){
		$method = $this->input->post("method");
		$co_idx = $this->input->post("co_idx");
		$up_data['co_con'] = "N";
		$result = $this->Shop_com_model->update($co_idx,$up_data,"");

		$json['resultItem'] = json_date_success($method,"후기가 삭제되었습니다");
		echo json_encode($json);
	}

	/////////////------------- 후기정보 업데이트  .-----------------////////////////////

	public function com_update(){


		$method = $this->input->post("method");
		$co_idx = $this->input->post("co_idx");
		$co = $this->Common_model->make_arr_data("co_");

		json_null_ck($method,$co);


		$this->Shop_com_model->update($co_idx,$co,"");

		$json['resultItem'] = json_date_success($method,"후기정보가 업데이트 되었습니다");
		echo json_encode($json);
	}


	/////////////--------------후기신고 리스트 .-----------------////////////////////

	public function singo_content_list(){
		$method = $this->input->post("method");

		$json['content_list'] = ["주제와 맞지 않아요",
														 "정보가 부정확해요",
														 "지나치게 광고를 해요",
														 "이 글로 도배가 되어있어요",
														 "저작권을 침해해요",
														 "외설적인 내용이 포함되어있어요",
														 "화질이 너무 안 좋아요"];

		$json['resultItem'] = json_date_success($method,"신고하기 목록입니다");
		echo json_encode($json);

	}

	/////////////--------------후기신고 하기 .-----------------////////////////////

	public function singo_add(){

		$method = $this->input->post("method");
		$si['co_idx'] = $this->input->post("co_idx");
		$si['mem_userid'] = $this->input->post("mem_userid");


		$ck = $this->Shop_com_singo_model->count_by($si);

		if($ck == 0){
			$si['si_content'] = $this->input->post("si_content");
			$this->Shop_com_singo_model->insert($si);
		}else {
			json_date_fail($method,"이미 신고를 접수하신 후기입니다");
		}

		$json['resultItem'] = json_date_success($method,"신고가 접수되었습니다");
		echo json_encode($json);

	}

}


// /$this->Json_log_model->add_log();
// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
