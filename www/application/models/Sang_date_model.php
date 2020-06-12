<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Autologin model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Sang_date_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'sang_date';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'sa_idx'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}
}
