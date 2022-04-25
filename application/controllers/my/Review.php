<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review extends CD_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('pagination');	
		$this->load->library('object_storage');
		$this->load->library('common');
		$this->load->model('review_m');
	}
	

}
