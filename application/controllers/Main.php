<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CD_Controller 
{
	public function __construct() 
	{	
		parent::__construct();
//		$this->load->library('common_l');
//		$this->load->library('form_validation');
//		$this->load->library('session');
		$this->load->model('popup_m');
		$this->load->model('review_m');
		$this->load->model('subscribe_m');
	}
	
	public function index()
	{
		$perpage = 3;
		$offset = 0;
		$req['only_photo'] = 'y';
		
		$list = $this->review_m->review_list_all($req['only_photo'], $offset, $perpage)->result_array();
		$subscribe_cnt = $this->subscribe_m->subscribe_total_cnt();
		$popup = $this->popup_m->popup_list()->result_array();
		$main = $this->common_m->shop_main()->row_array();

		$this->data['reviews'] = $list;
		$this->data['subscribe_cnt'] = $subscribe_cnt;
		$this->data['popup'] = $popup;
		$this->data['main'] = $main;
		$this->load->view('header_v', $this->data);
		$this->load->view('main/index_v');
		$this->load->view('footer_v');
	}
	
	public function introduce()
	{
		$this->load->view('header_v', $this->data);
		$this->load->view('main/introduce_v');
		$this->load->view('footer_v');
	}
	
	public function why()
	{
		$this->load->view('header_v', $this->data);
		$this->load->view('main/why_v');
		$this->load->view('footer_v');
	}
	
	public function characteristic()
	{
		$this->load->view('header_v', $this->data);
		$this->load->view('main/characteristic_v');
		$this->load->view('footer_v');
	}
}
