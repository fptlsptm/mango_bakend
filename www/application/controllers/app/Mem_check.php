<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mem_check extends CB_Controller
{

protected $models = array('Common','Member','Check','Mem_check');

	protected $helpers = array('form', 'array', 'string','json','basic');

	function __construct(){
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
 		header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        exit(0);
    }
    //$mem_userid = $this->input->post("mem_userid");
    //if($mem_userid == "undefined" || $mem_userid == ""){json_date_fail($method,"권한이없습니다");}
	}
  public function index($mem_userid){
    echo "잘못된 접근입니다";
  }

  public function add($mem_userid){
		//echo "string";
		$wh['mc_date'] = date("Y-m-d");
		$wh['mem_userid'] = $mem_userid;
		$rs = $this->Mem_check_model->count_by("",$wh);
		if($rs == 0){
			$mc = $this->Common_model->make_arr_data_key("ck_");
			$mc['mc_date'] = date("Y-m-d");
			$mc['mem_userid'] = $mem_userid;
			$this->Mem_check_model->insert($mc);
		}else {
			$mc = $this->Common_model->make_arr_data_key("ck_");
			foreach ($mc as $key => $val) {
				if($val != "" && $val != "4"){
					$mc_data[$key] = $val;
				}
				if($val == "0"){
					$mc_data[$key] = $val;
				}
			}
			// for ($i=0; $i <count($mc); $i++){
			// 	$mc_data[''] =
			// }
			$this->Mem_check_model->update("",$mc_data,$wh);
		}
		// $mc = $this->Common_model->make_arr_data_key("ck_");
		// $this->Mem_check_model->insert($mc);
		//echo json_encode($mc,JSON_UNESCAPED_UNICODE);
    //var_dump($mc);
  }
	public function info($mem_userid){
		$wh['mem_userid'] = $mem_userid;
		$wh['mc_date'] = date("Y-m-d");
		$rs = $this->Mem_check_model->get_one("","*",$wh);
		echo json_encode($rs,JSON_UNESCAPED_UNICODE);
	}

}

// /$this->Json_log_model->add_log();
// 개발 끝날때 까지 주석 지우지 말자
//$qry = $this->db->last_query();
//$json['qry'] = $qry;
