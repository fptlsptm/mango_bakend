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
class App_mypage extends CB_Controller
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
													'shop/Order',
													'shop/Json_log',
													'Point',
													'shop/Order_item',
													'shop/Order_cart',
													'Member',
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

	/////////////-------------------- 주문내역 조회 ------------------////////////////////

	public function order_list(){

		$method = $this->input->post("method");
    $where['mem_userid'] = $this->input->post("mem_userid");
		$or_con = $this->input->post("or_con");

		if($or_con != ""){$where['or_con'] = $or_con;}

		$select = "or_idx,it_name,or_price,or_con,or_date_num,or_date";

		$select .=",(SELECT im_img FROM cb_order_item AS oi WHERE cb_order.or_idx = oi.or_idx ORDER BY oi_idx DESC LIMIT 1) AS im_img";
		$this->db->order_by("or_idx","DESC");
		$order_list = $this->Order_model->get("",$select,$where);

		if(count($order_list) > 0){
			$json['order_list']= $order_list;
			$json['resultItem'] = json_date_success($method,"주문목록 입니다");
			echo json_encode($json);
		}else{
			json_date_fail($method,"주문내역이 없습니다.");
		}

  }

	/////////////-------------------- 반품 취소 내역 조회 ------------------////////////////////

	public function order_list_can(){

		$method = $this->input->post("method");
    $mem_userid = $this->input->post("mem_userid");
		$this->db->where("(mem_userid = '{$mem_userid}') AND (or_con = '반품요청' OR or_con = '반품완료' OR or_con = '취소요청' OR or_con = '취소완료')");
		$select = "or_idx,it_name,or_price,or_con,or_date_num,or_date,mem_userid";
		$select .=",(SELECT im_img FROM cb_order_item AS oi WHERE cb_order.or_idx = oi.or_idx ORDER BY oi_idx DESC LIMIT 1) AS im_img";
		$this->db->order_by("or_idx","DESC");
		$order_list = $this->Order_model->get("",$select);



		if(count($order_list) > 0){
			$json['order_list']= $order_list;
			$json['resultItem'] = json_date_success($method,"반품 취소 내역 조회 목록입니다");
			echo json_encode($json,JSON_UNESCAPED_UNICODE);
		}else{

			json_date_fail($method,"주문내역이 없습니다.");
		}
  }


	/////////////-------------------- 주문내역 상세조회 ------------------////////////////////

	public function order_item_list(){

		$method = $this->input->post("method");
		$where['or_idx'] = $this->input->post("or_idx");
		$select_oi = "oi_name,oi_size,oi_color,oi_order_num,oi_price,im_img";
		$select_or =  "or_price,or_use_point,or_price_type,or_date,or_date_num";
		$order_item_list = $this->Order_item_model->get("",$select_oi,$where);

		// 제품 총 가격 계산
		foreach ($order_item_list as $key => $val){
			$oi_sum += $val['oi_price'];
		}

		$order_data = $this->Order_model->get_one($where['or_idx'],$select_or);
		$order_data['all_price'] = $order_data['or_use_point'] + $order_data['or_price'];

		$order_data['song_price'] = $order_data['all_price'] - $oi_sum;
		$order_data['oi_price'] = $oi_sum;


		if(count($order_item_list) > 0){

			$json['order_data']= $order_data;
			$json['order_item_list']= $order_item_list;
			$json['resultItem'] = json_date_success($method," 주문상세내용 입니다");
			echo json_encode($json,JSON_UNESCAPED_UNICODE);

		}else{
			json_date_fail($method,"주문상세내용이 없습니다");
		}
	}
	/////////////-------------------- 주문을 취소합니다------------------////////////////////
	public function order_cancel(){
		$method = $this->input->post("method");
		$or_idx = $this->input->post("or_idx");

		$or = $this->Order_model->get_one($or_idx);

		if($or['or_con'] == "결제완료"){
			$update_data['or_con'] = "취소요청";
			$this->Order_model->update($or_idx,$update_data);
		}else{
			json_date_fail($method,"주문을 취소할 수 있는 상태가 아닙니다");
		}

		$json['resultItem'] = json_date_success($method,"주문이 취소되었습니다");
		echo json_encode($json,JSON_UNESCAPED_UNICODE);
	}
	/////////////-------------------- 반품을 신청합니다------------------////////////////////
	public function order_return(){

		$method = $this->input->post("method");
		$or_idx = $this->input->post("or_idx");
		$or_re_memo = $this->input->post("or_re_memo");

		$or = $this->Order_model->get_one($or_idx);

		if($or['or_con'] == "결제완료" || $or['or_con'] == "결제대기"){

			json_date_fail($method,"반품을 할 수 있는 상태가 아닙니다");

		}elseif($or['or_con'] == "구매확정") {

			json_date_fail($method,"구매확정 상태이기 때문에 반품이 안됩니다");

		}else{

			$update_data['or_con'] = "반품요청";
			$update_data['or_re_memo'] = $or_re_memo;
			$this->Order_model->update($or_idx,$update_data);

		}
		$json['resultItem'] = json_date_success($method,"반품신청이 되었습니다");
		echo json_encode($json);

	}
	/////////////-------------------- 구매확정합니다. 신청합니다------------------////////////////////
	public function order_ok(){

		$method = $this->input->post("method");
		$or_idx = $this->input->post("or_idx");

		if($or_idx == 0 || $or_idx == ""){
			json_date_fail($method,"0r_idx에러 잠시후에 시도해주세요");
		}

		$oi_wh['or_idx'] = $or_idx;
		$or_data = $this->Order_model->get_one($or_idx);
		$oi = $this->Order_item_model->get("","*",$oi_wh);

		if($or_data['or_con'] == "결제대기"){
			json_date_fail($method,"결제대기는 구매확정이 안됩니다");
		}

		foreach ($oi as $key => $val){
			$it_price = $val['oi_price'] * $val['oi_order_num'];

			$co = $this->Shop_com_model->get_one($val['co_idx']);

			$poi_content = "후기에대한 구매확정";
			$this->plus_mem_point($co['mem_userid'],$it_price,4,$poi_content);

		}

		$or['or_con'] = "구매확정";
		$this->Order_model->update($or_idx,$or);

		$json['resultItem'] = json_date_success($method,"구매확정이 되었습니다");
		echo json_encode($json);

	}

	/////////////-------------------- 장바구니 리스트 ------------------////////////////////

	public function cart_list(){

		$method = $this->input->post("method");
		$where['mem_userid'] = $this->input->post("mem_userid");

		$select = "*";
		$select .= ",(SELECT it_price FROM cb_shop_item AS it WHERE it.it_idx = cb_order_cart.it_idx) AS it_price";
		$select .= ",(SELECT gu_price FROM cb_shop_gumi AS gu WHERE gu.gu_idx = cb_order_cart.gu_idx) AS gu_price";

		$this->db->select($select);
		$ca_list = $this->Order_cart_model->get("","",$where);
		$cart_list = $this->Order_cart_model->cart_list($where['mem_userid']);

		$json['ca_list'] = $ca_list;


		foreach ($ca_list as $key => $val) {
			$ca_price_sum += $val['ca_price'];
		}
		$json['ca_price_sum'] = $ca_price_sum;
		$json['resultItem'] = json_date_success($method,"장바구니 리스트입니다");

		echo json_encode($json,JSON_UNESCAPED_UNICODE);
	}

	/////////////-------------------- 장바구니 정보입력 ------------------////////////////////
	public function cart_add(){
		$method = $this->input->post("method");

		$data_arr = ["mem_userid","gu_idx","co_idx","ca_order_num"];
		for ($i=0; $i <count($data_arr) ; $i++) {
			$ca_data[$data_arr[$i]] = $this->input->post($data_arr[$i]);
		}
		$gu = $this->Shop_gumi_model->get_one($ca_data['gu_idx']);
		$co = $this->Shop_com_model->get_one($ca_data['co_idx']);
		$it = $this->Shop_item_model->get_one($co['it_idx']);
		$im = $this->Shop_img_model->get_one($co['im_idx']);

		$ca_price = ($it['it_price'] + $gu['gu_price'])*$ca_data['ca_order_num'];

		$ca_data['im_idx'] = $im['im_idx'];
		$ca_data['it_idx'] = $it['it_idx'];
		$ca_data['ca_size'] = $gu['gu_size'];
		$ca_data['ca_color'] = $gu['gu_color'];
		$ca_data['ca_price'] = $ca_price;
		$ca_data['ca_name'] = $it['it_name'];
		$ca_data['im_img'] = $im['im_img'];


		if($gu['gu_sell_con'] != 'Y'){
			json_date_fail($method,"제품이 품절상태입니다");
		}
		json_null_ck($method,$co_data);

		$this->Order_cart_model->insert($ca_data);
		$qry = $this->db->last_query();

		$json['resultItem'] = json_date_success($method,"상품이 장바구니에 추가되었습니다");
		echo json_encode($json);

	}

	/////////////-------------------- 장바구니 다중넣기 ------------------////////////////////
	public function cart_add_arr(){
		$method = $this->input->post("method");

		$mem_userid = $this->input->post("mem_userid");
		$gu_idx = $this->input->post("gu_idx");
		$co_idx = $this->input->post("co_idx");
		$ca_order_num = $this->input->post("ca_order_num");

		$ca_data['mem_userid'] = $mem_userid;

		for ($i=0; $i <count($gu_idx) ; $i++) {

			$gu = $this->Shop_gumi_model->get_one($gu_idx[$i]);
			$co = $this->Shop_com_model->get_one($co_idx[$i]);
			$it = $this->Shop_item_model->get_one($co['it_idx']);
			$im = $this->Shop_img_model->get_one($co['im_idx']);


			$ca_data['gu_idx'] = $gu_idx[$i];
			$ca_data['co_idx'] = $co_idx[$i];
			$ca_data['ca_order_num'] = $ca_order_num[$i];
			$ca_price = ($it['it_price'] + $gu['gu_price'])*$ca_data['ca_order_num'];
			$ca_data['im_idx'] = $im['im_idx'];
			$ca_data['it_idx'] = $it['it_idx'];
			$ca_data['ca_size'] = $gu['gu_size'];
			$ca_data['ca_color'] = $gu['gu_color'];
			$ca_data['ca_price'] = $ca_price;
			$ca_data['ca_name'] = $it['it_name'];
			$ca_data['im_img'] = $im['im_img'];

			if($gu['gu_sell_con'] != 'Y'){
				json_date_fail($method,"제품이 품절상태입니다");
			}
			json_null_ck($method,$co_data);
			$this->Order_cart_model->insert($ca_data);

		}


		$json['resultItem'] = json_date_success($method,"상품이 장바구니에 추가되었습니다");
		echo json_encode($json);

	}

	/////////////-------------------- 장바구니 정보삭제------------------////////////////////
	public function cart_remove(){
		$method = $this->input->post("method");
		$ca_idx = $this->input->post("ca_idx");

		$this->Order_cart_model->delete($ca_idx);
		$json['resultItem'] = json_date_success($method,"장바구니 목록이 삭제되었습니다");
		echo json_encode($json);
	}


	/////////////-------------------- 장바구니 정보삭제------------------////////////////////
	public function cart_remove_arr(){
		$method = $this->input->post("method");
		$ca_idx = $this->input->post("ca_idx");

		for ($i=0; $i <count($ca_idx); $i++) {
			$this->Order_cart_model->delete($ca_idx[$i]);
		}

		$json['resultItem'] = json_date_success($method,"장바구니 목록이 삭제되었습니다");
		echo json_encode($json);
	}


	/////////////-------------------- 배송완료,구매확정 리스트------------------////////////////////

	public function com_order_item_list(){

		$method = $this->input->post("method");
		$mem_userid = $this->input->post("mem_userid");
		$page = $this->input->post("page");

		$order_item_list = $this->Order_model->com_order_item_list($mem_userid,$page);

		if(count($order_item_list) > 0){

			$json['order_item_list']= $order_item_list;
			$json['resultItem'] = json_date_success($method," 주문상세내용 입니다");
			echo json_encode($json);

		}else{
			json_date_fail($method,"주문상세내용이 없습니다");
		}
	}
	/////////////-------------------- 포인트 리스트------------------////////////////////

	public function point_list(){

		$method = $this->input->post("method");
		$sc_date = $this->input->post("sc_date");
		$sc_mode = $this->input->post("sc_mode");

		$where['mem_userid'] = $this->input->post("mem_userid");

		$timestamp = strtotime("-{$sc_date} months");
		$p_days = strtotime("+1 days");
		$st_date = date("Y-m-d", $timestamp);
		$la_date = date("Y-m-d", $p_days);

		if($sc_mode == "001"){$this->db->where("poi_point > 0");}
		if($sc_mode == "002"){$this->db->where("poi_point < 0");}

		$this->db->where("poi_datetime BETWEEN '{$st_date}' AND '{$la_date}'");
		$this->db->order_by("poi_id","DESC");
		$point_list = $this->Point_model->get("","",$where);


		$qry = $this->db->last_query();
		$json['qry'] = $qry;
		if(count($point_list) > 0){

			$json['point_list']= $point_list;
			$json['resultItem'] = json_date_success($method," 포인트내역 입니다");
			echo json_encode($json);

		}else{
			json_date_fail($method,"포인트내역이 없습니다");
		}
	}


	public function plus_mem_point($mem_userid,$it_price,$pur_num,$poi_content){

		$m_whe['mem_userid'] = $mem_userid;
		$mem = $this->Member_model->get_one("","mem_id,mem_point",$m_whe);

		$po['poi_point'] = ceil($it_price/100)*$pur_num;
		$po['poi_type'] = "com";
		$po['poi_content'] = $poi_content;
		$po['mem_id'] = $mem['mem_id'];
		$po['poi_related_id'] = $mem['mem_id'];
		$po['poi_action'] = "후기에대한 구매확정";
		$po['mem_userid'] = $mem_userid;

		$this->Point_model->insert($po);
		$po_plus['mem_point'] = $po['poi_point'] + $mem['mem_point'];
		$this->Member_model->update($mem['mem_id'],$po_plus);

	}


}

//$this->Json_log_model->add_log();

// /$this->Json_log_model->add_log();
// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
