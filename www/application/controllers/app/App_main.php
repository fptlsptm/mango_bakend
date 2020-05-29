<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class App_main extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
protected $models = array('Common','Config',
													'shop/Shop_com',
													'shop/Shop_gumi',
													'shop/Shop_item',
													'shop/Shop_img',
													'shop/Shop_event',
													'shop/Tag_cate',
												  'shop/Terms',
													'shop/Country',
													'Member',
													'Point',
													'Member_userid',
													'Member_extra_vars',
                          'shop/Jang');

	protected $helpers = array('form', 'array', 'string','json');

	function __construct()
	{
		parent::__construct();


	}
  public function index($value=''){
    echo "잘못된 접근입니다.";
  }

	/////////////-------------------- 상단 목록및 기타 데이터를 로드합니다 ------------------////////////////////
	public function main_data(){
    $method = $this->input->post("method");


    $it_price =  $this->Common_model->it_price_maxmin();

    $name_arr = ['기획전','TOP50'];
    $url_arr = ['http://original-c.com/app/app_main/event_list','/'];
    $type_arr = ['web_view','json'];

    for ($i=0; $i <count($type_arr) ; $i++) {
      $menu_data['name'] = $name_arr[$i];
      $menu_data['url'] = $url_arr[$i];
      $menu_data['type'] = $type_arr[$i];
      $main_menu[] = $menu_data;
    }

		$co_name = ["블랙","베이지","화이트","핑크","회색","파랑","갈색","남색","초록","노랑","빨강","보라","주황","그레이","골드"];
		$co_code = ["#000000","#EABF00","#FFFFFF","#FF84E5","#DDDDDD","#0014DC","#92001A","#002192","#1DAF00","#F8E900","#F8001A","#AD00F8","#F87100","#FAFAFA","#FFF000"];

		for ($i=0; $i <count($co_code) ; $i++) {
			$it_color[$i]['co_name'] = $co_name[$i];
			$it_color[$i]['co_code'] = $co_code[$i];
		}

		$json['Tag_cate'] = $this->Tag_cate_model->get();

		$json['item_icon'] = "http://img.original-c.com/app_img/o_point.png";
		$json['it_color'] = $it_color;
    $json['it_price'] = $it_price;
    $json['main_menu'] = $main_menu;
    $json['resultItem'] = json_date_success($method,"기타 데이터 입니다");
		echo json_encode($json);
  }

	/////////////-------------------- 상세페이지 웹뷰를 로드합니다. ------------------////////////////////
	public function item_info($it_idx){

		$it_info = $this->Shop_item_model->get_one($it_idx);

		$wh['it_idx'] = $it_info['it_idx'];
		$wh['im_type'] = "it";
		$im = $this->Shop_img_model->get_one("","im_img",$wh);



		$view['im'] = $im;
		$view['it'] = $it_info;
		$this->load->view('head');
    $this->load->view('shop/bootstrap/app_item_info',$view);

	}

	/////////////-------------------- 기획전 웹뷰 ------------------////////////////////
	public function event_list(){
		$where['ev_con'] = "기획진행중";
		$ev_list = $this->Shop_event_model->get("","",$where);

		$view['ev_list'] = $ev_list;
		$this->load->view('head');
    $this->load->view('shop/bootstrap/app_event_list',$view);
	}

	/////////////-------------------- 다음주소 웹뷰 ------------------////////////////////
	public function daum_zip_api(){

    $this->load->view('shop/bootstrap/daum_zip_api',$view);
	}
	/////////////-------------------- 이용약관------------------////////////////////
	public function terms(){
		$method = $this->input->post("method");
		$gd = $this->Config_model->get_all_meta();

		$te = $this->Terms_model->get();
		//nl2br
		$te[0]['te_text'] = $gd['member_register_policy1'];
		$te[1]['te_text'] = $gd['member_register_policy2'];

		$json['te_list'] = $te;

    $json['resultItem'] = json_date_success($method,"이용약관입니다");
		echo json_encode($json);
	}

	/////////////--------------top50 후기정보 가져오기 .-----------------////////////////////

	public function top_50_list(){
		$method = $this->input->post("method");
		$btn['co_tall'] = $this->input->post("mem_tall");
		$btn['co_kg'] = $this->input->post("mem_kg");
		$btn['co_age'] = $this->input->post("mem_age");
		$mem_sex = $this->input->post("mem_sex");

		$price_max = $this->input->post("price_max");
		$price_min = $this->input->post("price_min");
		$color = $this->input->post("color");

		$com_list = $this->Shop_com_model->top_50_list($btn,$mem_sex,$where);


		$json['com_list']= $com_list;
		$json['resultItem'] = json_date_success($method,"top50 리스트입니다");

		echo json_encode($json);
	}


	/////////////-------------- 기획전 목록-----------------////////////////////

	public function event_view_list(){

		$method = $this->input->post("method");
		$btn['co_tall'] = $this->input->post("mem_tall");
		$btn['co_kg'] = $this->input->post("mem_kg");
		$btn['co_age'] = $this->input->post("mem_age");
		$mem_sex = $this->input->post("mem_sex");
		$ev_idx = $this->input->post("ev_idx");

		$com_list = $this->Shop_com_model->event_list($btn,$mem_sex,$ev_idx);

		if(count($com_list) ==  0 || $ev_idx == ""){
			json_date_fail($method,"기획전 목록이 없습니다");
		}

		$json['com_list']= $com_list;
		$json['resultItem'] = json_date_success($method,"기획전 목록입니다");
		echo json_encode($json);

	}

	/////////////-------------- 상단 목록-----------------////////////////////

	public function head_view_list(){

		$method = $this->input->post("method");
		$btn['co_tall'] = $this->input->post("mem_tall");
		$btn['co_kg'] = $this->input->post("mem_kg");
		$btn['co_age'] = $this->input->post("mem_age");
		$mem_sex = $this->input->post("mem_sex");
		$type = $this->input->post("type");

		if($type == ""){
			$type_arr = ['event','top'];
			$ran = mt_rand(0,1);
			$data_info['type'] = $type_arr[$ran];
		}else {
			$data_info['type'] = $type;
		}

		$data_info['con'] = "정상";
		switch ($data_info['type']) {
			////////----------- 기획전  --------------------////
			case 'event':

				$data_info['name'] = "님의 맞춤 기획전";

				$where['ev_con'] = "기획진행중";
				$ev_list = $this->Shop_event_model->get("","",$where);
				$ev_count_ran = count($ev_list) -1;
				$ev_count = count($ev_list);
				if($ev_count_ran < 0){$ev_count_ran = 0;}
				$ev_ran = mt_rand(0,$ev_count_ran);


				if($ev_count > 0){
					$ev_idx = $ev_list[$ev_ran]['ev_idx'];
					$com_list = $this->Shop_com_model->event_list($btn,$mem_sex,$ev_idx);

					if(count($com_list) == 0){
						$com_list = $this->Shop_com_model->main_list_load($btn,$mem_sex,"","t.it_idx");
						$data_info['con'] = "데이터 부족으로 신상품목록로드";
					}

				}else {
					$com_list = $this->Shop_com_model->main_list_load($btn,$mem_sex,"","t.it_idx");
					$data_info['con'] = "데이터 부족으로 신상품목록로드";
				}
			break;
			////////----------- top 50 리스트 --------------------////
			case 'top':

				$data_info['name'] = "님의 맞춤 top50";
				$com_list = $this->Shop_com_model->top_50_list($btn,$mem_sex,$where);
			break;

		}


		$json['data_info'] = $data_info;
		$json['com_list'] = $com_list;
		$json['resultItem'] = json_date_success($method,"상단 목록입니다");
		echo json_encode($json);

	}

	/////////////-------------- 상단 목록-----------------////////////////////




}



// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
