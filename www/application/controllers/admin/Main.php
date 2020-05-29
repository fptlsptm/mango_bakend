<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Main class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자 메인 controller 입니다.
 */
class Main extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = '';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Member_meta', 'Member_group', 'Member_group_member', 'Member_nickname', 'Member_extra_vars', 'Member_userid', 'Social_meta',"Check","Mem_check");

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Member_model';

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array','url');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('pagination', 'querystring'));
	}

	/**
	 * 관리자 메인 페이지입니다
	 */
	public function index(){
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_members_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$view['view']['sort'] = array(
			'mem_id' => $param->sort('mem_id', 'asc'),
			'mem_userid' => $param->sort('mem_userid', 'asc'),
			'mem_username' => $param->sort('mem_username', 'asc'),
			'mem_nickname' => $param->sort('mem_nickname', 'asc'),
			'mem_email' => $param->sort('mem_email', 'asc'),
			'mem_point' => $param->sort('mem_point', 'asc'),
			'mem_register_datetime' => $param->sort('mem_register_datetime', 'asc'),
			'mem_lastlogin_datetime' => $param->sort('mem_lastlogin_datetime', 'asc'),
			'mem_level' => $param->sort('mem_level', 'asc'),
		);
		$findex = $this->input->get('findex', null, 'member.mem_id');
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_search_field = array('mem_id', 'mem_userid', 'mem_email', 'mem_username', 'mem_nickname', 'mem_level', 'mem_homepage', 'mem_register_datetime', 'mem_register_ip', 'mem_lastlogin_datetime', 'mem_lastlogin_ip', 'mem_is_admin'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('mem_id', 'mem_level', 'mem_is_admin'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('member.mem_id', 'mem_userid', 'mem_username', 'mem_nickname', 'mem_email', 'mem_point', 'mem_register_datetime', 'mem_lastlogin_datetime', 'mem_level'); // 정렬이 가능한 필드

		$where = array();
		if ($this->input->get('mem_is_admin')) {
			$where['mem_is_admin'] = 1;
		}
		if ($this->input->get('mem_denied')) {
			$where['mem_denied'] = 1;
		}
		if ($mgr_id = (int) $this->input->get('mgr_id')) {
			if ($mgr_id > 0) {
				$where['mgr_id'] = $mgr_id;
			}
		}
		$result = $this->{$this->modelname}
			->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;

		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {

				$where = array(
					'mem_id' => element('mem_id', $val),
				);
				$result['list'][$key]['member_group_member'] = $this->Member_group_member_model->get('', '', $where, '', 0, 'mgm_id', 'ASC');
				$mgroup = array();
				if ($result['list'][$key]['member_group_member']) {
					foreach ($result['list'][$key]['member_group_member'] as $mk => $mv) {
						if (element('mgr_id', $mv)) {
							$mgroup[] = $this->Member_group_model->item(element('mgr_id', $mv));
						}
					}
				}
				$result['list'][$key]['member_group'] = '';
				if ($mgroup) {
					foreach ($mgroup as $mk => $mv) {
						if ($result['list'][$key]['member_group']) {
							$result['list'][$key]['member_group'] .= ', ';
						}
						$result['list'][$key]['member_group'] .= element('mgr_title', $mv);
					}
				}
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
				$result['list'][$key]['meta'] = $this->Member_meta_model->get_all_meta(element('mem_id', $val));
				$result['list'][$key]['social'] = $this->Social_meta_model->get_all_meta(element('mem_id', $val));

				$result['list'][$key]['num'] = $list_num--;
			}
		}

		$view['view']['data'] = $result;
		$view['view']['all_group'] = $this->Member_group_model->get_all_group();

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = admin_url($this->pagedir) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		/**
		 * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
		 */
		$search_option = array('mem_userid' => '회원아이디', 'mem_email' => '이메일');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir);
		$view['view']['write_url'] = admin_url($this->pagedir . '/write');
		$view['view']['list_delete_url'] = admin_url($this->pagedir . '/listdelete/?' . $param->output());

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		// $layoutconfig = array('layout' => 'layout', 'skin' => 'main');
		// $view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		// $this->data = $view;
		// $this->layout = element('layout_skin_file', element('layout', $view));
		// $this->view = element('view_skin_file', element('layout', $view));

		$this->da_admin_load_view("main",$view);
	}
	public function gomain(){
		$this->index();
	}
	public function excelpage(){
		$this->da_admin_load_view("excelpage");

	}
	public function excelprint(){
		$date1 = $this->input->post("date1");
		$date2 = $this->input->post("date2");
		$where['ch_com'] = "Y";
		$this->db->order_by('ch_idx', 'DESC');
		$this->db->where("ch_date BETWEEN '{$date1}' AND '{$date2}'");
		$data['list'] = $this->Check_model->get("","",$where);
		$this->load->view('/admin/basic/main/excelpage/excelprint',$data);

	}
	public function info($mem_userid){
		$where['mem_userid'] = $mem_userid;
		$data['mem'] = $this->Member_model->get_one("","",$where);
		$data['ch'] = $this->Check_model->get_one("","",$where);
		$data['mc'] = $this->Mem_check_model->get("","",$where);
		$this->da_admin_load_view("info",$data);
	}

}
