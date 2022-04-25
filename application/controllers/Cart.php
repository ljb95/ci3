<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CD_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('product_m');
		$this->load->model('member_m');
		$this->load->model('cart_m');
		$this->load->model('coupon_m');
	}

	public function cart_list()
	{
		$req = $this->input->get();
		
		if(empty($req['type'])) {
			$this->data['msg'] = '잘못된 접근입니다.';
			$this->load->view('header_v', $this->data);
			$this->load->view('errors/invalid_seq');
			$this->load->view('footer_v');
		}
		else {		
			$cart = array();
			$coupon = array();
			$couponcnt = 0;
			if(empty($this->data['user'])) {
				$tmp = $this->session->userdata('cart');
				if(!empty($tmp)) {
					foreach($tmp as $row) {
						if($row['cart_type'] === $req['type']) {
							if(empty($row['is_sale'])) $row['is_sale'] = 'y';
							$cart[] = $row;
						}
					}
				}
			}
			else {
				$cart = $this->cart_m->cart_list($this->data['user']['mem_id'], $req['type'])->result_array();
				
				
				if($req['type'] === 'item') {
					$product = array();
					$sum = 0;
					foreach($cart as $row) {
						$product[] = $row['cit_id'];
						if($row['is_sale'] === 'y') $sum += $row['cit_sale_price'] * $row['qty'];
						else $sum += $row['cit_price'] * $row['qty'];
					}
					$coupon = $this->coupon_m->coupon_list_for_cart($this->data['user']['mem_id'], $product, $sum)->result_array();
					$couponcnt = $this->coupon_m->coupon_cnt_for_cart($this->data['user']['mem_id']);
				}
			}
			$hour = date('H');
			$start_date = date('Y-m-d');
			if($hour >= 15) {
				$start_date = date('Y-m-d', strtotime('+1 days'));
			}
			if(!empty($this->data['user'])) {
				$this->data['delivery'] = $this->cart_m->delivery_address_default($this->data['user']['mem_id'])->row_array();
				$this->data['point'] = $this->member_m->member_info($this->data['user']['mem_id'])->row_array();
			}
			$shop_info = $this->common_m->shop_info()->row_array();
		 	$delivery_price = $shop_info['delivery_price'];
			if(!empty($this->data['delivery'])) {
				$tmp = $this->common_m->check_delivery_price($this->data['delivery']['zipcode'])->row_array();
				if(!empty($tmp)) {
					$delivery_price = $tmp['delivery_price'];	
				}
			}
			$this->data['cart'] = $cart;
			$this->data['start_date'] = $start_date;
			$this->data['cart_type'] = $req['type'];
			$this->data['coupon'] = $coupon;
			$this->data['couponcnt'] = $couponcnt;
			$this->data['delivery_price'] = $delivery_price;
			$this->load->view('header_v', $this->data);
			$this->load->view('cart/cart_v');
			$this->load->view('footer_v');
		}
	}
	
	public function change_cart()
	{
		if(empty($this->data['user'])) {
			$tmp = $this->session->userdata('cart');
			if(!empty($tmp)) {
				for($i = 0; $i < count($tmp); $i++) {
					if($tmp[$i]['is_subscribe'] == 'n' || $tmp[$i]['cart_type'] == 'subscribe') continue;
					
					if($tmp[$i]['cart_type'] == 'item') {
						$bExists = false;
						for($j = 0; $j < count($tmp); $j++) {
							if($tmp[$i]['cit_id'] === $tmp[$j]['cit_id'] && $tmp[$i]['cde_id'] === $tmp[$j]['cde_id'] && $tmp[$j]['cart_type'] === 'subscribe') {
								$tmp[$j]['qty'] += $tmp[$i]['qty'];
								$tmp[$i] = '';
								$bExists = true;
							}
						}
						
						if(!$bExists) $tmp[$i]['cart_type'] = 'subscribe';
					}
				}
				$idx = 0;
				$cart = array();
				foreach($tmp as $row) {
					if(empty($row)) continue;
	
					$cart[] = $row;
					$cart[count($cart) - 1]['cct_id'] = $idx;
					$idx++;
				}
				$this->session->set_userdata('cart', $cart);
			}
		}
		else {
			$res = $this->cart_m->change_cart($this->data['user']['mem_id']);	
		}
		header('Location: /cart/cart_list?type=subscribe');
	}
	public function popupclose(){
            header("Refresh:0; url=/cart/cart_list?type=item");
        }
	public function ajaxCheckSubscribe()
	{
		$req = $this->input->post();
		
		$cart = array();
		if(empty($this->data['user'])) {
			$tmp = $this->session->userdata('cart');
			if(!empty($tmp)) {
				foreach($tmp as $row) {
					if($row['cart_type'] === $req['type']) {
						$cart[] = $row;
					}
				}
			}
		}
		else {
			$cart = $this->cart_m->cart_list($this->data['user']['mem_id'], $req['type'])->result_array();	
		}
		
		$sum = 0;
		foreach($cart as $row) {
			$sum += $row['cit_subscribe_price'] * $row['qty'];
		}
		$result = array();
		if($sum >= 50) {
			$result['status'] = 'succ';	
		}
		else {
			$result['status'] = 'fail';
			$result['msg'] = '15,000원부터 구독가능합니다.';	
		}
		echo json_encode($result);
	}
	
	public function ajaxAddCart()
	{
		$req = $this->input->post();
		
		$val = array();
		$val['cart_type'] = $req['cart_type'];
		$val['is_subscribe'] = $req['is_subscribe'];
		$val['cit_id'] = $req['cit_id'];
		$val['cde_id'] = $req['cde_id'];
		$val['cit_name'] = $req['cit_name'];
		$val['cit_price'] = $req['cit_price'];
		$val['cit_sale_price'] = $req['cit_sale_price'];
		$val['cit_subscribe_price'] = $req['cit_subscribe_price'];
		$val['qty'] = $req['qty'];
		$val['cde_title'] = $req['cde_title'];
		$val['product_code'] = $req['product_code'];
		$val['barcode_no'] = $req['barcode_no'];
		$val['cit_file_1'] = $req['cit_file_1'];
		$val['is_sale'] = $req['is_sale'];
		
		$res = true;
		if(empty($this->data['user'])) {
			$cart = $this->session->userdata('cart');
			if(empty($cart)) {
				$val['cct_id'] = '0';
				$cart = array();
				$cart[] = $val;
			}
			else {
				$bExists = false;
				for($i = 0; $i < count($cart); $i++) {
					if($cart[$i]['cit_id'] === $val['cit_id'] && $cart[$i]['cde_id'] === $val['cde_id'] && $cart[$i]['cart_type'] === $val['cart_type']) {
						$cart[$i]['qty'] += $val['qty'];
						$bExists = true;
					}
				}
				if(!$bExists) {
					$val['cct_id'] = count($cart);
					$cart[] = $val;	
				}
			}
			$this->session->set_userdata('cart', $cart);
		}
		else {
			$val['mem_id'] = $this->data['user']['mem_id'];
			$res = $this->cart_m->insert_cart($val);	
		}
		
		$result = array();
		if($res) {
			$result['status'] = 'succ';	
		}
		else {
			$result['status'] = 'fail';
			$result['msg'] = '장바구니 담기에 실패했습니다.';	
		}
		echo json_encode($result);
	}
	
	public function ajaxChangeQty()
	{
		$req = $this->input->post();
		$res = true;
		if(empty($this->data['user'])) {
			$cart = $this->session->userdata('cart');
			for($i = 0; $i < count($cart); $i++) {
				if($cart[$i]['cct_id'] == $req['seq']) {
					$cart[$i]['qty'] += $req['qty'];
				}
			}
			$this->session->set_userdata('cart', $cart);
		}
		else {
			$res = $this->cart_m->update_cart_qty($req['seq'], $req['qty']);	
		}
		
		$result = array();
		if($res) {
			$result['status'] = 'succ';	
		}
		else {
			$result['status'] = 'fail';
			$result['msg'] = '수정에 실패했습니다.';	
		}
		echo json_encode($result);
	}
	
	public function ajaxDeleteCartItem()
	{
		$req = $this->input->post();
		$res = true;
		if(empty($this->data['user'])) {
			$tmp = $this->session->userdata('cart');
			$idx = 0;
			$cart = array();
			foreach($tmp as $row) {
				if($row['cct_id'] != $req['seq']) {
					$cart[] = $row;
					$cart[count($cart) - 1]['cct_id'] = $idx;
					$idx++;
				}
			}
			$this->session->set_userdata('cart', $cart);
		}
		else {
			$res = $this->cart_m->delete_cart($req['seq']);
		}
		
		$result = array();
		if($res) {
			$result['status'] = 'succ';	
		}
		else {
			$result['status'] = 'fail';
			$result['msg'] = '수정에 실패했습니다.';	
		}
		echo json_encode($result);
	}
	
	public function ajaxAddAddress()
	{
		$req = $this->input->post();
		
		$result = array();
		if(empty($req['recipient_name'])) {
			$result['status'] = 'fail';
			$result['msg'] = '수령인 이름을 입력해주세요.';
		}
		else if(empty($req['recipient_phone'])) {
			$result['status'] = 'fail';
			$result['msg'] = '수령인 연락처을 입력해주세요.';
		}
		else if(empty($req['zipcode'])) {
			$result['status'] = 'fail';
			$result['msg'] = '우편번호를 검색해 입력해주세요.';
		}
		else if(empty($req['detail_addr'])) {
			$result['status'] = 'fail';
			$result['msg'] = '상세주소를 입력해주세요.';
		}
		else {
			if(empty($req['mde_title'])) $req['mde_title'] = $req['recipient_name'];
			$res = $this->cart_m->insert_delivery_address($req);
			
			if($res) {
				$result['status'] = 'succ';	
				$result['msg'] = '저장 되었습니다.';	
			}
			else {
				$result['status'] = 'fail';
				$result['msg'] = '저장에 실패했습니다.';	
			}
		}
		echo json_encode($result);
	}

	public function ajaxUpdateAddress()
	{
		$req = $this->input->post();
		
		$result = array();
		if(empty($req['mde_title'])) {
			$result['status'] = 'fail';
			$result['msg'] = '설명을 입력해주세요.';
		}
		else if(empty($req['recipient_name'])) {
			$result['status'] = 'fail';
			$result['msg'] = '수령인 이름을 입력해주세요.';
		}
		else if(empty($req['recipient_phone'])) {
			$result['status'] = 'fail';
			$result['msg'] = '수령인 연락처을 입력해주세요.';
		}
		else if(empty($req['zipcode'])) {
			$result['status'] = 'fail';
			$result['msg'] = '우편번호를 검색해 입력해주세요.';
		}
		else if(empty($req['detail_addr'])) {
			$result['status'] = 'fail';
			$result['msg'] = '상세주소를 입력해주세요.';
		}
		else {
			$res = $this->cart_m->update_delivery($req);
			
			if($res) {
				$result['status'] = 'succ';	
				$result['msg'] = '수정 되었습니다.';	
			}
			else {
				$result['status'] = 'fail';
				$result['msg'] = '수정에 실패했습니다.';	
			}
		}
		echo json_encode($result);
	}

	public function ajaxUpdateAddress2()
	{
		$req = $this->input->post();
		
		$result = array();
		if(empty($req['mde_title'])) {
			$result['status'] = 'fail';
			$result['msg'] = '설명을 입력해주세요.';
		}
		else if(empty($req['recipient_name'])) {
			$result['status'] = 'fail';
			$result['msg'] = '수령인 이름을 입력해주세요.';
		}
		else if(empty($req['recipient_phone'])) {
			$result['status'] = 'fail';
			$result['msg'] = '수령인 연락처을 입력해주세요.';
		}
		else if(empty($req['zipcode'])) {
			$result['status'] = 'fail';
			$result['msg'] = '우편번호를 검색해주세요.';
		}
		else if(empty($req['detail_addr'])) {
			$result['status'] = 'fail';
			$result['msg'] = '상세주소를 입력해주세요.';
		}
		else {
			$res = $this->cart_m->update_delivery2($req);
			
			if($res) {
				$result['status'] = 'succ';	
				$result['msg'] = '수정 되었습니다.';	
			}
			else {
				$result['status'] = 'fail';
				$result['msg'] = '수정에 실패했습니다.';	
			}
		}
		echo json_encode($result);
	}

	public function ajaxDeleteAddr()
	{
		$req = $this->input->post();
		$res = $this->card_m->delete_addr();	
		if($res) {
			$result['status'] = 'succ';	
			$result['msg'] = '삭제 되었습니다.';
		}
		else {
			$result['status'] = 'fail';
			$result['msg'] = '삭제에 실패했습니다.';	
		}
	}
	
	public function ajaxUpdateAddressDefault()
	{
		$req = $this->input->post();
	
		$res = $this->cart_m->update_delivery_default($req);
			
		$result['status'] = 'succ';	
		echo json_encode($result);
	}

	public function ajaxAddressList()
	{
		$req = $this->input->post();
		
		$list = $this->cart_m->delivery_address_list($req['mem_id'])->result_array();
		
		echo json_encode($list);
	}
        
        function recommend_guest_subscribe()
	{	
                $this->load->view('header_v', $this->data);
		$this->load->view('/cart/recommend_guest_subscribe'); 
		$this->load->view('footer_v');
	}
        
        function recommend_member_subscribe()
	{
                $this->load->view('header_v', $this->data);
		$this->load->view('/cart/recommend_member_subscribe'); 
		$this->load->view('footer_v');
	}

}
