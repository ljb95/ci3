<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CD_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('product_m');
	}

	public function product_list()
	{
		$req = $this->input->get();
		
		if(!isset($req['seq'])) {
			$req['seq'] = '';	
		}
		$list = $this->product_m->product_list($req['seq'])->result_array();
		
		$this->data['list'] = $list;
		$this->data['req'] = $req;
		
		$this->load->view('header_v', $this->data);
		$this->load->view('product/product_list_v');
		$this->load->view('footer_v');
	}
	
	public function product_detail()
	{
		$req = $this->input->get();
		if(!isset($req['seq'])) {
			$this->data['msg'] = '잘못된 접근입니다.';
			$this->load->view('header_v', $this->data);
			$this->load->view('errors/invalid_seq');
			$this->load->view('footer_v');
		}
		else {
			$info = $this->product_m->product_item($req['seq'])->row_array();
			if(empty($info)) {
				$this->data['msg'] = '존재하지 않는 상품입니다.';
				$this->load->view('header_v', $this->data);
				$this->load->view('errors/invalid_seq');
				$this->load->view('footer_v');
			}
			else {
				$category = $this->product_m->category_list()->result_array();
				$options = $this->product_m->product_option($req['seq'])->result_array();
				
				$this->data['info'] = $info;
				$this->data['category'] = $category;
				$this->data['options'] = $options;
				
				$this->load->view('header_v', $this->data);
				if($info['is_order'] == 'y') {
					$this->load->view('product/product_detail_v');
				}
				else {
					$this->load->view('product/product_detail2_v');
				}
				$this->load->view('footer_v');
			}
		}
	}
	
	public function ajaxOptionDetail()
	{
		$req = $this->input->post();
		
		$option = array();
		if(isset($req['option'])) {
			$option = explode(',', $req['option']);
		}
		
		$info = $this->product_m->product_item_detail($req['seq'], $option)->row_array();
		
		echo json_encode($info);
	}
        
        public function recommend()
	{
                $this->load->view('header_v', $this->data);
		$this->load->view('product/recommend'); 
		$this->load->view('footer_v');
	}
}
