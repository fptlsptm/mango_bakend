<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Phpinfo extends CB_Controller
{

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('pagination', 'querystring', 'accesslevel', 'videoplayer', 'point'));
	}
  public function index(){
    phpinfo();
  }
}
