<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Autologin model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Mem_check_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'mem_check';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'mc_idx'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}
}
