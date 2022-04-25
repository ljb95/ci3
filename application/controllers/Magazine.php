<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Magazine extends CD_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('magazine_m');
	}

	public function index()
	{
		$this->load->view('header_v', $this->data);
		$this->load->view('magazine/magazine_v');
		$this->load->view('footer_v');
	}
	
	public function ajaxMagazineList()
	{
		$req = $this->input->post();
		$perpage = 6;

		$offset = (int)$req['offset'];
		$list = $this->magazine_m->magazine_list($req, $offset, $perpage)->result_array();
		$offset += $perpage;
		$this->data['perpage'] = $perpage;
		$this->data['offset'] = $offset;
		$this->data['list'] = $list;
			
		echo json_encode($this->data);
	}
	
	public function detail()
	{
		$req = $this->input->get();
		
		$this->data['info'] = $this->magazine_m->magazine_detail($req['seq']);
		if(empty($this->data['info'])) {
			header('Location: /magazine');
		}
		else {
			$this->load->view('header_v', $this->data);
			$this->load->view('magazine/magazine_detail_v');
			$this->load->view('footer_v');
		}
	}
}
