<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CD_Controller 
{	
	public function __construct() 
	{	
		parent::__construct();
//		$this->load->library('common_l');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->library('email_l');
		$this->load->helper('string');
		$this->load->model('member_m');
		$this->load->model('diagnosis_m');
		$this->load->model('email_m');
	}
	
//	public function index()
//	{
//	}
	
	public function join()
	{
		$this->load->view('header_v', $this->data);
		$this->load->view('member/join_v');
		$this->load->view('footer_v');
	}
	
	public function join_p()
	{
		$res = [];
		$res['result'] = true;
		$res['result_msg'] = '';
		$res['result_data'] = [];
		
		$this->form_validation->set_rules('mem_phone', '', 'required');
		$this->form_validation->set_rules('mem_username', '', 'required');
		$this->form_validation->set_rules('mem_email', '', 'required');
		$this->form_validation->set_rules('mem_password', '', 'required');
		$this->form_validation->set_rules('mem_receive_sms', '', 'required');
		$this->form_validation->set_error_delimiters('', '');

		if ( $this->form_validation->run() ) 
		{	
			$req = $this->input->post();
			
//			$res['result_data'] = $req;
			
			if ( $res['result'] ) 
			{
				$where = [];
				$where['mem_email'] = $req['mem_email'];
				$member_r = $this->member_m->member_r($where);
				
				if ( $member_r->num_rows() > 0 ) 
				{
					$res['result'] = false;
					$res['result_msg'] = '이미 가입된 이메일 주소입니다.';
				}
			}
			
			if ( $res['result'] ) 
			{
				$values = [];
				$values['mem_userid'] = $req['mem_email'];
				$values['mem_email'] = $req['mem_email'];
				$values['mem_password'] = $req['mem_password'];
				$values['mem_username'] = $req['mem_username'];
				$values['mem_phone'] = $req['mem_phone'];
				$values['mem_sns_type'] = 'email';
				$values['is_rcv_sms'] = ($req['mem_receive_sms'] === 'true' ? 'y' : 'n');
				$values['is_rcv_email'] = ($req['mem_receive_sms'] === 'true' ? 'y' : 'n');
				$values['is_rcv_kakao'] = ($req['mem_receive_sms'] === 'true' ? 'y' : 'n');
				$values['mem_register_datetime'] = date('Y-m-d H:i:s');
				$values['mem_register_ip'] = $_SERVER["REMOTE_ADDR"];
				$this->member_m->member_c($values);

				$email = $this->email_m->email_detail('join')->row_array();

				if(!empty($email)) {
					$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
					$base_url .= "://" . $_SERVER['HTTP_HOST'];
					
					$html = $email['mail_content'];
					$html = str_replace('[=NAME]', $req['mem_username'], $html);
					$html = str_replace('[=BASE_URL]', $base_url, $html);
					$this->email_m->email_insert($req['mem_email'], $email['mail_title'], $html);
				}
			}
		}
		else
		{
			$res['result'] = false;
			$res['result_msg'] = validation_errors();
		}
		
		header('Content-type: application/json');
		echo json_encode($res);	
	}
	
	public function join_sns_p()
	{
		$res = [];
		$res['result'] = true;
		$res['result_msg'] = '';
		$res['result_data'] = [];
		
		$this->form_validation->set_rules('mem_userid', '', 'required');
		$this->form_validation->set_rules('mem_email', '', 'required');
		$this->form_validation->set_rules('mem_username', '', 'required');
		$this->form_validation->set_rules('mem_sns_type', '', 'required');
		$this->form_validation->set_error_delimiters('', '');

		if ( $this->form_validation->run() ) 
		{	
			$req = $this->input->post();
			
//			$res['result_data'] = $req;
			
			if ( $res['result'] ) 
			{
				$where = [];
				$where['mem_userid'] = $req['mem_userid'];
				$where['mem_sns_type'] = $req['mem_sns_type'];
				$member_r3 = $this->member_m->member_r3($where);
				
				if ( $member_r3->num_rows() > 0 ) 
				{
					$res['result'] = false;
					$res['result_msg'] = '이미 가입된 아이디입니다.';
				}
			}
			
			if ( $res['result'] ) 
			{
				$values = [];
				$values['mem_userid'] = $req['mem_userid'];
				$values['mem_email'] = $req['mem_email'];
				$values['mem_username'] = $req['mem_username'];
				$values['mem_sns_type'] = $req['mem_sns_type'];
				$values['mem_register_datetime'] = date('Y-m-d H:i:s');
				$values['mem_register_ip'] = $_SERVER["REMOTE_ADDR"];
				$this->member_m->member_c2($values);
			}
		}
		else
		{
			$res['result'] = false;
			$res['result_msg'] = validation_errors();
		}
		
		header('Content-type: application/json');
		echo json_encode($res);	
	}
	
	public function join_more()
	{
		$this->load->view('header_v', $this->data);
		$this->load->view('member/join_more_v');
		$this->load->view('footer_v');	
	}
	
	public function join_more_p()
	{
		$res = [];
		$res['result'] = true;
		$res['result_msg'] = '';
		$res['result_data'] = [];
		
		$this->form_validation->set_rules('mem_id', '', 'required');
		$this->form_validation->set_rules('mem_phone', '', 'required');
		$this->form_validation->set_rules('mem_username', '', 'required');
		$this->form_validation->set_rules('mem_email', '', 'required');
		$this->form_validation->set_rules('mem_receive_sms', '', 'required');
		$this->form_validation->set_error_delimiters('', '');

		if ( $this->form_validation->run() ) 
		{	
			$req = $this->input->post();
			
//			$res['result_data'] = $req;
			
			if ( $res['result'] ) 
			{
				$where = [];
				$where['mem_id'] = $req['mem_id'];
				$member_r4 = $this->member_m->member_r4($where);
				
				if ( $member_r4->num_rows() < 1 ) 
				{
					$res['result'] = false;
					$res['result_msg'] = '회원 정보가 존재하지 않습니다.';
				}
			}
			
			if ( $res['result'] ) 
			{
				$set = [];
				$set['mem_email'] = $req['mem_email'];
				$set['mem_username'] = $req['mem_username'];
				$set['mem_phone'] = $req['mem_phone'];
				$set['is_rcv_sms'] = ($req['mem_receive_sms'] === 'true' ? 'y' : 'n');
				$set['is_rcv_email'] = ($req['mem_receive_sms'] === 'true' ? 'y' : 'n');
				$set['is_rcv_kakao'] = ($req['mem_receive_sms'] === 'true' ? 'y' : 'n');
				$where = [];
				$where['mem_id'] = $req['mem_id'];
				$this->member_m->member_u($set, $where);


				$email = $this->email_m->email_detail('join')->row_array();
				
				if(!empty($email)) {
					$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
					$base_url .= "://" . $_SERVER['HTTP_HOST'];
					
					$html = $email['mail_content'];
					$html = str_replace('[=NAME]', $req['mem_username'], $html);
					$html = str_replace('[=BASE_URL]', $base_url, $html);
					$this->email_m->email_insert($req['mem_email'], $email['mail_title'], $html);
				}
			}
			
			if ( $res['result'] ) 
			{
				$this->_session_c($req['mem_id']);
			}
		}
		else
		{
			$res['result'] = false;
			$res['result_msg'] = validation_errors();
		}
		
		header('Content-type: application/json');
		echo json_encode($res);	
	}
	
	private function _session_c($mem_id)
	{
		$member_info = $this->member_m->member_info($mem_id)->row_array();
//		$this->session->set_userdata('user', $member_info);
//		$this->session->set_userdata('mem_userid', $member_info['mem_userid']);
		$_SESSION['user'] =  $member_info;
		$_SESSION['mem_userid'] = $member_info['mem_userid'];
	}
	
	public function login()
	{
		$_data = [];
		
		if (!empty($_SERVER['HTTP_REFERER']) && strlen($_SERVER['HTTP_REFERER']) > 0 )	{
			if(strpos($_SERVER['HTTP_REFERER'], '/member') !== false) $_data['login_referer'] = '/';
			else $_data['login_referer'] = $_SERVER['HTTP_REFERER'];
		}
		else										$_data['login_referer'] = '/';
		
		$this->load->view('header_v', $this->data);
		$this->load->view('member/login_v', $_data);
		$this->load->view('footer_v');
	}
	
	public function login_p()
	{
		$res = [];
		$res['result'] = true;
		$res['result_msg'] = '';
		$res['result_data'] = [];
		
		$this->form_validation->set_rules('mem_userid', '', 'required');
		$this->form_validation->set_rules('mem_password', '', 'required');
		$this->form_validation->set_error_delimiters('', '');

		if ( $this->form_validation->run() ) 
		{	
			$req = $this->input->post();
			
			$member_r2 = [];
			
			if ( $res['result'] ) 
			{
				$where = [];
				$where['mem_userid'] = $req['mem_userid'];
				$where['mem_password'] = $req['mem_password'];
				$member_r2 = $this->member_m->member_r2($where);
				
				if ( $member_r2->num_rows() === 1 )
				{
					$member_r2 = $member_r2->row_array();
				}
				else
				{
					$res['result'] = false;
					$res['result_msg'] = '아이디 혹은 비밀번호가 일치하지 않습니다.';
				}
			}
			
			if ( $res['result'] ) 
			{
				$this->_session_c($member_r2['mem_id']);
			}	
			
			if ( $res['result'] ) 
			{
				$values = [];
				$values['mll_success'] = $res['result'];
				$values['mem_id'] = $member_r2['mem_id'];
				$values['mll_userid'] = $member_r2['mem_userid'];
				$values['mll_datetime'] = date('Y-m-d H:i:s');
				$values['mll_ip'] = $_SERVER["REMOTE_ADDR"];
				$values['mll_reason'] = '로그인 성공';
				$values['mll_useragent'] = $_SERVER['HTTP_USER_AGENT'];
				$values['mll_url'] = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				$values['mll_referer'] = $_SERVER['HTTP_REFERER'];
				$this->member_m->member_login_log_c($values);	
				
				$val = array();
				$val['mem_lastlogin_datetime'] = $values['mll_datetime'];
				$val['mem_lastlogin_ip'] = $values['mll_ip'];
				$this->member_m->member_u3($val, $values['mem_id']);
				
				$cart = $this->session->userdata('cart');
				if(!empty($cart)) {
					foreach($cart as $row) {
						$val = array();
						$val['cart_type'] = $row['cart_type'];
						$val['is_subscribe'] = $row['is_subscribe'];
						$val['cit_id'] = $row['cit_id'];
						$val['cde_id'] = $row['cde_id'];
						$val['cit_name'] = $row['cit_name'];
						$val['cit_price'] = $row['cit_price'];
						$val['cit_sale_price'] = $row['cit_sale_price'];
						$val['cit_subscribe_price'] = $row['cit_subscribe_price'];
						$val['qty'] = $row['qty'];
						$val['cde_title'] = $row['cde_title'];
						$val['product_code'] = $row['product_code'];
						$val['barcode_no'] = $row['barcode_no'];
						$val['cit_file_1'] = $row['cit_file_1'];
						$val['mem_id'] = $member_r2['mem_id'];
						$this->cart_m->insert_cart($val);	
					}
				}
				$this->session->set_userdata('cart', '');

				$diagnosis = $this->session->userdata('diagnosis');
				if(!empty($diagnosis)) {
					foreach($diagnosis as $row) {
						$val = array();
						$val['cdg_id'] = $row;
						$val['mem_id'] = $member_r2['mem_id'];
						$this->diagnosis_m->update_diagnosis($val);	
					}
				}
				$this->session->set_userdata('diagnosis', '');
			}	
			else
			{
				$values = [];
				$values['mll_success'] = $res['result'];
				$values['mll_userid'] = $req['mem_userid'];
				$values['mll_datetime'] = date('Y-m-d H:i:s');
				$values['mll_ip'] = $_SERVER["REMOTE_ADDR"];
				$values['mll_reason'] = $res['result_msg'];
				$values['mll_useragent'] = $_SERVER['HTTP_USER_AGENT'];
				$values['mll_url'] = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				$values['mll_referer'] = $_SERVER['HTTP_REFERER'];
				$this->member_m->member_login_log_c($values);	
			}
		}
		else
		{
			$res['result'] = false;
			$res['result_msg'] = validation_errors();
		}
		
		header('Content-type: application/json');
		echo json_encode($res);	
	}
	
	public function login_sns_p()
	{
		$res = [];
		$res['result'] = true;
		$res['result_msg'] = '';
		$res['result_data'] = [];
		
		$this->form_validation->set_rules('mem_userid', '', 'required');
		$this->form_validation->set_rules('mem_sns_type', '', 'required');
		$this->form_validation->set_error_delimiters('', '');

		if ( $this->form_validation->run() ) 
		{	
			$req = $this->input->post();
			
			$member_r3 = [];
			
			if ( $res['result'] ) 
			{
				$where = [];
				$where['mem_userid'] = $req['mem_userid'];
				$where['mem_sns_type'] = $req['mem_sns_type'];
				$member_r3 = $this->member_m->member_r3($where);
				
				if ( $member_r3->num_rows() === 1 )
				{
					$member_r3 = $member_r3->row_array();
				}
				else
				{
					$res['result'] = false;
					$res['result_msg'] = '아이디가 일치하지 않습니다.';
				}
			}
			
			if ( $res['result'] ) 
			{
				$this->_session_c($member_r3['mem_id']);
			}
			
			if ( $res['result'] ) 
			{
				$values = [];
				$values['mll_success'] = $res['result'];
				$values['mem_id'] = $member_r3['mem_id'];
				$values['mll_userid'] = $member_r3['mem_userid'];
				$values['mll_datetime'] = date('Y-m-d H:i:s');
				$values['mll_ip'] = $_SERVER["REMOTE_ADDR"];
				$values['mll_reason'] = '로그인 성공';
				$values['mll_useragent'] = $_SERVER['HTTP_USER_AGENT'];
				$values['mll_url'] = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				$values['mll_referer'] = $_SERVER['HTTP_REFERER'];
				$this->member_m->member_login_log_c($values);	

				$val = array();
				$val['mem_lastlogin_datetime'] = $values['mll_datetime'];
				$val['mem_lastlogin_ip'] = $values['mll_ip'];
				$this->member_m->member_u3($val, $values['mem_id']);

				$cart = $this->session->userdata('cart');
				if(!empty($cart)) {
					foreach($cart as $row) {
						$val = array();
						$val['cart_type'] = $row['cart_type'];
						$val['is_subscribe'] = $row['is_subscribe'];
						$val['cit_id'] = $row['cit_id'];
						$val['cde_id'] = $row['cde_id'];
						$val['cit_name'] = $row['cit_name'];
						$val['cit_price'] = $row['cit_price'];
						$val['cit_sale_price'] = $row['cit_sale_price'];
						$val['cit_subscribe_price'] = $row['cit_subscribe_price'];
						$val['qty'] = $row['qty'];
						$val['cde_title'] = $row['cde_title'];
						$val['product_code'] = $row['product_code'];
						$val['barcode_no'] = $row['barcode_no'];
						$val['cit_file_1'] = $row['cit_file_1'];
						$val['mem_id'] = $member_r3['mem_id'];
						$this->cart_m->insert_cart($val);	
					}
				}
				$this->session->set_userdata('cart', '');

				$diagnosis = $this->session->userdata('diagnosis');
				if(!empty($diagnosis)) {
					foreach($diagnosis as $row) {
						$val = array();
						$val['cdg_id'] = $row;
						$val['mem_id'] = $member_r3['mem_id'];
						$this->diagnosis_m->update_diagnosis($val);	
					}
				}
				$this->session->set_userdata('diagnosis', '');
			}	
			else
			{
				$values = [];
				$values['mll_success'] = $res['result'];
				$values['mll_userid'] = $req['mem_userid'];
				$values['mll_datetime'] = date('Y-m-d H:i:s');
				$values['mll_ip'] = $_SERVER["REMOTE_ADDR"];
				$values['mll_reason'] = $res['result_msg'];
				$values['mll_useragent'] = $_SERVER['HTTP_USER_AGENT'];
				$values['mll_url'] = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				$values['mll_referer'] = $_SERVER['HTTP_REFERER'];
				$this->member_m->member_login_log_c($values);	
			}
		}
		else
		{
			$res['result'] = false;
			$res['result_msg'] = validation_errors();
		}
		
		header('Content-type: application/json');
		echo json_encode($res);	
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		header('Location: /');
	}
	
	public function find_id()
	{
		$this->load->view('header_v', $this->data);
		$this->load->view('member/find_id_v');
		$this->load->view('footer_v');
	}
	
	public function find_id_p()
	{
		$res = [];
		$res['result'] = true;
		$res['result_msg'] = '';
		$res['result_data'] = [];
		
		$this->form_validation->set_rules('mem_username', '', 'required');
		$this->form_validation->set_rules('mem_phone', '', 'required');
		$this->form_validation->set_error_delimiters('', '');

		if ( $this->form_validation->run() ) 
		{	
			$req = $this->input->post();
			
			$member_r5 = [];
			
			if ( $res['result'] ) 
			{
				$where = [];
				$where['mem_username'] = $req['mem_username'];
				$where['mem_phone'] = $req['mem_phone'];
				$member_r5 = $this->member_m->member_r5($where);
				
				if ( $member_r5->num_rows() === 1 )
				{
					$member_r5 = $member_r5->row_array();
					
					if ( $member_r5['mem_sns_type'] === 'email' )
					{
						$this->session->set_flashdata('f_mem_id', $member_r5['mem_id']);
						$this->session->set_flashdata('f_mem_userid', $member_r5['mem_userid']);
						$this->session->set_flashdata('f_mem_username', $member_r5['mem_username']);
						$this->session->set_flashdata('f_mem_phone', $member_r5['mem_phone']);
						$this->session->set_flashdata('f_mem_sns_type', $member_r5['mem_sns_type']);
						$this->session->set_flashdata('f_mem_register_datetime', $member_r5['mem_register_datetime']);
					}
					else
					{
						$res['result'] = false;
						if($member_r5['mem_sns_type'] === 'kakao') {
							$res['result_msg'] = '고객님은 카카오 아이디로 회원가입하셨습니다. (이메일로 가입한 회원만 아이디 또는 비밀번호 찾기가 가능합니다.)';
						}
						else {
							$res['result_msg'] = '고객님은 네이버 아이디로 회원가입하셨습니다. (이메일로 가입한 회원만 아이디 또는 비밀번호 찾기가 가능합니다.)';
						}
						$res['result_data']['mem_sns_type'] = $member_r5['mem_sns_type'];
					}
				}
				else
				{
					$res['result'] = false;
					$res['result_msg'] = '회원 정보를 찾을 수 없습니다.';
				}
			}
		}
		else
		{
			$res['result'] = false;
			$res['result_msg'] = validation_errors();
		}
		
		header('Content-type: application/json');
		echo json_encode($res);	
	}
	
	public function find_id_result_t()
	{
		if ( $this->session->has_userdata('f_mem_userid') )
		{
			$this->load->view('header_v', $this->data);
			$this->load->view('member/find_id_result_t_v');
			$this->load->view('footer_v');
		}
		else
		{
			header('Location: /member/find_id');
		}
	}
	
	public function find_id_result_f()
	{
		$this->load->view('header_v', $this->data);
		$this->load->view('member/find_id_result_f_v');
		$this->load->view('footer_v');
	}
	
	public function find_pw()
	{
		$this->load->view('header_v', $this->data);
		$this->load->view('member/find_pw_v');
		$this->load->view('footer_v');
	}
	
	public function find_pw_hp_step1()
	{
		$this->load->view('header_v', $this->data);
		$this->load->view('member/find_pw_hp_step1_v');
		$this->load->view('footer_v');
	}
	
	public function find_pw_hp_step2()
	{
		if ( $this->session->has_userdata('f_mem_userid') )
		{
			$this->load->view('header_v', $this->data);
			$this->load->view('member/find_pw_hp_step2_v');
			$this->load->view('footer_v');
		}
		else
		{
			header('Location: /member/find_pw_hp_step1');
		}
	}
	
	public function find_pw_hp_step2_p()
	{
		$res = [];
		$res['result'] = true;
		$res['result_msg'] = '';
		$res['result_data'] = [];
		
		$this->form_validation->set_rules('mem_id', '', 'required');
		$this->form_validation->set_rules('mem_password', '', 'required');
		$this->form_validation->set_error_delimiters('', '');

		if ( $this->form_validation->run() ) 
		{	
			$req = $this->input->post();
			
			if ( $res['result'] ) 
			{
				$where = [];
				$where['mem_id'] = $req['mem_id'];
				$member_r4 = $this->member_m->member_r4($where);
				
				if ( $member_r4->num_rows() < 1 ) 
				{
					$res['result'] = false;
					$res['result_msg'] = '회원 정보가 존재하지 않습니다.';
				}
			}
			
			if ( $res['result'] ) 
			{
				$set = [];
				$set['mem_password'] = $req['mem_password'];
				$where = [];
				$where['mem_id'] = $req['mem_id'];
				$this->member_m->member_u2($set, $where);
			}
		}
		else
		{
			$res['result'] = false;
			$res['result_msg'] = validation_errors();
		}
		
		header('Content-type: application/json');
		echo json_encode($res);	
	}
	
	public function find_pw_hp_result()
	{
		$this->load->view('header_v', $this->data);
		$this->load->view('member/find_pw_hp_result_v');
		$this->load->view('footer_v');
	}
	
	public function find_pw_email()
	{
		$this->load->view('header_v', $this->data);
		$this->load->view('member/find_pw_email_v');
		$this->load->view('footer_v');
	}
	
	public function find_pw_email_p()
	{
		$res = [];
		$res['result'] = true;
		$res['result_msg'] = '';
		$res['result_data'] = [];
		
		$this->form_validation->set_rules('mem_username', '', 'required');
		$this->form_validation->set_rules('mem_email', '', 'required');
		$this->form_validation->set_error_delimiters('', '');

		if ( $this->form_validation->run() ) 
		{	
			$req = $this->input->post();
			
			$member_r7 = [];
			
			if ( $res['result'] ) 
			{
				$where = [];
				$where['mem_username'] = $req['mem_username'];
				$where['mem_email'] = $req['mem_email'];
				$member_r7 = $this->member_m->member_r7($where);
				
				if ( $member_r7->num_rows() === 1 )
				{
					$member_r7 = $member_r7->row_array();
					
					if ( $member_r7['mem_sns_type'] !== 'email' )
					{
						$res['result'] = false;
						if($member_r7['mem_sns_type'] === 'kakao') {
							$res['result_msg'] = '고객님은 카카오 아이디로 회원가입하셨습니다. (이메일로 가입한 회원만 아이디 또는 비밀번호 찾기가 가능합니다.)';
						}
						else {
							$res['result_msg'] = '고객님은 네이버 아이디로 회원가입하셨습니다. (이메일로 가입한 회원만 아이디 또는 비밀번호 찾기가 가능합니다.)';
						}
//						$res['result_msg'] = '이메일로 가입한 회원만 비밀번호 찾기가 가능합니다.';
						$res['result_data']['mem_sns_type'] = $member_r7['mem_sns_type'];
					}
				}
				else
				{
					$res['result'] = false;
					$res['result_msg'] = '회원 정보가 존재하지 않습니다.';
				}	
			}
			
			if ( $res['result'] ) 
			{
				$otp = random_string('alnum', 16);	
				
				$val = array();
				$val['otp'] = $otp;
				$val['otp_limit_dt'] = date("Y-m-d H:i:s", strtotime("+30 minutes"));
				$this->member_m->member_u3($val, $member_r7['mem_id']);
				
				$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
				$base_url .= "://" . $_SERVER['HTTP_HOST'];
				
				$html = '';
				$html .= '<table cellpadding="0" cellspacing="0" width="800" style="text-align: center;" align="center"><tr><td><table cellpadding="0" cellspacing="0" width="100%" style="text-align: left;">';
				$html .= '<tr><td style="font-size: 0;"><img src="' . $base_url . '/res/img/email/head.jpg"></td></tr>';
				$html .= '<tr><td style="font-size: 18px; line-height: 30px; color: #333; padding:40px 0 90px;">직접 비밀번호 찾기를 하지 않았는데 이 메일을 받았다면, 누군가 회원님의 계정을 도용하려는 것일 수 있습니다. 지금 비밀번호를 변경하여 계정을 안전하게 보호해주세요.</td></tr>';
				$html .= '<tr><td style="text-align: center; padding: 0 0 70px;"><a href="' . $base_url . '/member/reset_pw/' . $otp . '" target="_blank"><img src="' . $base_url . '/res/img/email/btn.jpg" alt="비밀번호 재설정"></a></td></tr>';
				$html .= '<tr><td style="font-size: 14px; color: #cccccc; line-height: 24px; padding:50px 0; border-top:1px solid #ccc;">COPYRIGHT 2021 MYUNGSUNG CORP. ALL RIGHTS RESERVED.</td></tr>';
				$html .= '</table></td></tr></table>';
				$this->email_l->email_send('help@cleand.kr', '클린디', $req['mem_email'], '[클린디] 비밀번호 찾기', $html);
				
				$this->session->set_flashdata('f_mem_email', $req['mem_email']);
//				$this->session->set_flashdata('f_mem_email', $req['mem_email'] . '/' . $member_r7['mem_id']);
			}
		}
		else
		{
			$res['result'] = false;
			$res['result_msg'] = validation_errors();
		}
		
		header('Content-type: application/json');
		echo json_encode($res);	
	}
	
	public function find_pw_email_result()
	{
		if ( $this->session->has_userdata('f_mem_email') )
		{
			$this->load->view('header_v', $this->data);
			$this->load->view('member/find_pw_email_result_v');
			$this->load->view('footer_v');
		}
		else
		{
			header('Location: /member/find_pw_email');
		}
	}
	
	public function reset_pw()
	{
		$otp = $this->uri->segment(3, '');
		
		if ( strlen($otp) > 0 )
		{
				$where = [];
				$where['otp'] = $otp;
				$member_r8 = $this->member_m->member_r8($where);
				
				if ( $member_r8->num_rows() === 1 )
				{
					$member_r8 = $member_r8->row_array();
					
					if ( strtotime($member_r8['otp_limit_dt']) > time() )
					{
	//					$val = array();
	//					$val['otp'] = '';
	//					$this->member_m->member_u3($val, $member_r8['mem_id']);

						$data = [];
						$data['mem_id'] = $member_r8['mem_id'];

						$this->load->view('header_v', $this->data);
						$this->load->view('member/reset_pw_v', $data);
						$this->load->view('footer_v');
					}
					else
					{
						header('Location: /member/login');
					}
				}
				else
				{
					header('Location: /member/login');
				}
		}
		else
		{
			header('Location: /member/login');
		}
	}
	
	public function reset_pw_p()
	{
		$res = [];
		$res['result'] = true;
		$res['result_msg'] = '';
		$res['result_data'] = [];
		
		$this->form_validation->set_rules('mem_id', '', 'required');
		$this->form_validation->set_rules('mem_password', '', 'required');
		$this->form_validation->set_error_delimiters('', '');

		if ( $this->form_validation->run() ) 
		{	
			$req = $this->input->post();
			
			$member_r4 = [];
			
			if ( $res['result'] ) 
			{
				$where = [];
				$where['mem_id'] = $req['mem_id'];
				$member_r4 = $this->member_m->member_r4($where);
				
				if ( $member_r4->num_rows() === 1 )
				{
					$member_r4 = $member_r4->row_array();
				}
				else
				{
					$res['result'] = false;
					$res['result_msg'] = '회원 정보가 존재하지 않습니다.';
				}
			}
			
			if ( $res['result'] ) 
			{
				$set = [];
				$set['mem_password'] = $req['mem_password'];
				$set['otp'] = '';
				$set['otp_limit_dt'] = '';
				$where = [];
				$where['mem_id'] = $member_r4['mem_id'];
				$this->member_m->member_u6($set, $where);
			}
		}
		else
		{
			$res['result'] = false;
			$res['result_msg'] = validation_errors();
		}
		
		header('Content-type: application/json');
		echo json_encode($res);	
	}	
}
