<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscribe extends CD_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('pagination');	
		$this->load->library('object_storage');
		$this->load->library('common');
		$this->load->model('subscribe_m');
		$this->load->model('member_m');
		$this->load->model('cart_m');
		$this->load->model('order_m');
		$this->load->model('review_m');
		$this->load->model('email_m');
	}
	
	public function subscribe_list()
	{
		if(empty($this->data['user'])) {
			$this->data['msg'] = '로그인이  필요합니다.';
			$this->load->view('header_v', $this->data);
			$this->load->view('errors/invalid_seq');
			$this->load->view('footer_v');
		}
		else {
 			$req = $this->input->get();
			$req['mem_id'] = $this->data['user']['mem_id'];
			$perpage = (isset($req['perpage']) ? (int)$req['perpage'] : 10);
			$offset = (int)$this->uri->segment(4, 0);
			$total_rows = $this->subscribe_m->subscribe_list_cnt($req);;
			$num = $total_rows - $offset;

			$config = array();
			$config['base_url'] = '/my/subscribe/subscribe_list/';
			$config['total_rows'] = $total_rows;
			$config['perpage'] = $perpage;
			$config['offset'] = $offset;
			$config['num_links'] = 5;
			$pagination = $this->common->pagination($config);
	
			$list = $this->subscribe_m->subscribe_list($req, $offset, $perpage)->result_array();

			$this->data['list'] = $list;
			$this->data['total'] = $total_rows;
			$this->data['offset'] = $offset;
			$this->data['perpage'] = $perpage;
			$this->data['pagination'] = $pagination;
			$this->load->view('header_v', $this->data);
			$this->load->view('my/subscribe/subscribe_v');
			$this->load->view('footer_v');
		}
	}
	
	public function subscribe_order_list()
	{
		$req = $this->input->get();
		if(empty($this->data['user'])) {
			$this->data['msg'] = '로그인이  필요합니다.';
			$this->load->view('header_v', $this->data);
			$this->load->view('errors/invalid_seq');
			$this->load->view('footer_v');
		}
		else if(empty($req['seq'])) {
			$this->data['msg'] = '잘못된 접근입니다.';
			$this->data['move'] = '/my/subscribe/subscribe_list';
			$this->load->view('header_v', $this->data);
			$this->load->view('errors/invalid_seq');
			$this->load->view('footer_v');
		}
		else {
			$req['mem_id'] = $this->data['user']['mem_id'];
			$perpage = (isset($req['perpage']) ? (int)$req['perpage'] : 10);
			$offset = (int)$this->uri->segment(4, 0);
			$total_rows = $this->subscribe_m->order_list_cnt($req);;
			$num = $total_rows - $offset;

			$config = array();
			$config['base_url'] = '/my/subscribe/subscribe_order_list/';
			$config['total_rows'] = $total_rows;
			$config['perpage'] = $perpage;
			$config['offset'] = $offset;
			$config['num_links'] = 5;
			$pagination = $this->common->pagination($config);
	
			$list = $this->subscribe_m->order_list($req, $offset, $perpage)->result_array();

			$this->data['list'] = $list;
			$this->data['seq'] = $req['seq'];
			$this->data['total'] = $total_rows;
			$this->data['offset'] = $offset;
			$this->data['perpage'] = $perpage;
			$this->data['pagination'] = $pagination;
			$this->load->view('header_v', $this->data);
			$this->load->view('my/subscribe/subscribe_order_list_v');
			$this->load->view('footer_v');
		}
	} 

	public function list_detail()
	{
		if(empty($this->data['user'])) {
			$this->data['msg'] = '로그인이  필요합니다.';
			$this->load->view('header_v', $this->data);
			$this->load->view('errors/invalid_seq');
			$this->load->view('footer_v');
		}
		else {
			$req = $this->input->post();
			$req['mem_id'] = $this->data['user']['mem_id'];
			$perpage = (isset($req['perpage']) ? (int)$req['perpage'] : 10);
			$offset = (isset($req['offset']) ? (int)$req['offset'] : 0);
			$total_rows = $this->subscribe_m->order_list_cnt($req);

			$config = array();
			$config['base_url'] = '';
			$config['total_rows'] = $total_rows;
			$config['perpage'] = $perpage;
			$config['offset'] = $offset;
			$config['num_links'] = 5;
			$pagination = $this->common->pagination($config);
	
			$list = $this->subscribe_m->order_list($req, $offset, $perpage)->result_array();

			$this->data['list'] = $list;
			$this->data['csu_id'] = $req['csu_id'];
			$this->data['total'] = $total_rows;
			$this->data['offset'] = $offset;
			$this->data['perpage'] = $perpage;
			$this->data['pagination'] = $pagination;
			$this->load->view('my/subscribe/subscribe_list_detail_v', $this->data);
		}
	}
	
	public function detail()
	{
		$req = $this->input->get();
		
		if(empty($this->data['user'])) {
			$this->data['msg'] = '로그인이  필요합니다.';
			$this->load->view('header_v', $this->data);
			$this->load->view('errors/invalid_seq');
			$this->load->view('footer_v');
		}
		else if(empty($req['seq'])) {
			$this->data['msg'] = '잘못된 접근입니다.';
			$this->data['move'] = '/my/subscribe/subscribe_list';
			$this->load->view('header_v', $this->data);
			$this->load->view('errors/invalid_seq');
			$this->load->view('footer_v');
		}
		else {
			$offset = 0;
			$perpage = 2;
			$req['mem_id'] = $this->data['user']['mem_id'];
			$info = $this->subscribe_m->subscribe_info($req['seq'], $req['mem_id'])->row_array();
			if(empty($info)) {
				$this->data['msg'] = '잘못된 접근입니다.';
				$this->data['move'] = '/my/subscribe/subscribe_list';
				$this->load->view('header_v', $this->data);
				$this->load->view('errors/invalid_seq');
				$this->load->view('footer_v');
			}
			else {
				$info['list'] = $this->subscribe_m->subscribe_detail_list($req['seq'])->result_array();
				$info['order'] = $this->subscribe_m->order_list($req, $offset, $perpage)->result_array();
		
				$this->data['info'] = $info;
				$this->load->view('header_v', $this->data);
				$this->load->view('my/subscribe/subscribe_detail_v');
				$this->load->view('footer_v');
			}
		}
	}
	
	public function order_detail()
	{
		$req = $this->input->get();
		if(empty($this->data['user'])) {
			$this->data['msg'] = '로그인이  필요합니다.';
			$this->load->view('header_v', $this->data);
			$this->load->view('errors/invalid_seq');
			$this->load->view('footer_v');
		}
		else if(empty($req['seq'])) {
			$this->data['msg'] = '잘못된 접근입니다.';
			$this->data['move'] = '/my/order/order_list';
			$this->load->view('header_v', $this->data);
			$this->load->view('errors/invalid_seq');
			$this->load->view('footer_v');
		}
		else {
			if(!isset($_SERVER['HTTP_REFERER'])) {
				$refer = '/my/subscribe';
			}
			else {
				$refer = $_SERVER['HTTP_REFERER'];
			}
			$this->data['move'] = $refer;
			
			$info = $this->order_m->order_detail($req['seq'])->row_array();
			$offset = (int)$this->uri->segment(4, 0);
			if(empty($info)) {
				$this->data['msg'] = '해당주문건이 없습니다.';
				$this->data['move'] = '/my/order/order_list/' . $offset;
				$this->load->view('header_v', $this->data);
				$this->load->view('errors/invalid_seq');
				$this->load->view('footer_v');
			}
			else {
				$info = $this->order_m->order_detail($req['seq'])->row_array();
				$info['list'] = $this->order_m->order_detail_list($req['seq'])->result_array();

				$this->data['info'] = $info;
				$this->data['orders'] = $this->review_m->order_list2($req['seq'])->result_array();
				$this->data['offset'] = $offset;
				$this->load->view('header_v', $this->data);
				$this->load->view('my/subscribe/order_detail_v');
				$this->load->view('footer_v');
			}
		}
	}
	
	public function ajaxUpdateSubscribe()
	{
		$req = $this->input->post();
		$result = array();
		if(empty($this->data['user'])) {
			$result['status'] = 'login';
			$result['msg'] = '로그인이 필요합니다.';
		}
		else if((empty($req['new_period']) && empty($req['new_date'])) || ($req['new_period'] == $req['org_period'] && $req['new_date'] == $req['org_date'])) {
			$result['status'] = 'fail';
			$result['msg'] = '수정내역이 없습니다.';
		}
		else {
			$res = $this->subscribe_m->update_subscribe($req);
			if($res) {
				$result['status'] = 'succ';
				$result['msg'] = '수정하였습니다.';
			}
			else {
				$result['status'] = 'fail';
				$result['msg'] = '수정에 실패했습니다.';
			}
		}
		echo json_encode($result);
	}
        private function cancelKakaoPay($billing_key){
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, KAKAO_BASE_URL.'/v1/payment/manage/subscription/inactive');
            curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            $aPostData = array();
            $aPostData['cid'] = KAKAO_CID_SUBSCRIP;
            $aPostData['sid'] = $billing_key;
            
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
            
            $header = Array(
                'POST /v1/payment/manage/subscription/inactive HTTP/1.1',
                'Host: kapi.kakao.com',
                'Authorization: KakaoAK 8c01888f64dffa0104a05ef170b7ba2b',
                'Content-type: application/x-www-form-urlencoded;charset=utf-8'
            );

            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

            $gData = curl_exec($curl);
            
            curl_close($curl);
        }
        private function cancelNaverPay($billing_key){
            
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://dev.apis.naver.com/naverpay-partner/naverpay/payments/recurrent/expire/v1/request');
            $header = Array(
                'X-Naver-Client-Id:f07MEDKDQ478StuME1ea',
                'X-Naver-Client-Secret:HkH1m3sQQg',
                'X-NaverPay-Chain-Id:dkVsWi9VSEF4N0x'
             );
            curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            
            $aPostData = array();
            $aPostData['recurrentId'] = $billing_key;
            $aPostData['expireRequester'] = 1;
            $aPostData['expireReason'] = '사용자의 구독해지';
            
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));

            $gData = curl_exec($curl);
            
            curl_close($curl);
        }
        
        public function ajaxCancelSubscribe()
	{
		$req = $this->input->post();
		$result = array();
		if(empty($this->data['user'])) {
			$result['status'] = 'login';
			$result['msg'] = '로그인이 필요합니다.';
		}
		else {
                   
			$res = $this->subscribe_m->cancel_subscribe($req);
                        
			if($res) {
				$info = $this->subscribe_m->subscribe_info2($req['csu_id'])->row_array();
				$list = $this->subscribe_m->subscribe_detail_list($req['csu_id'])->result_array();


                                if($info['payMethod'] == 'KakaoPay'){
                                    $this->cancelKakaoPay($info['billing_key']);
                                } else if ($info['payMethod'] == 'NaverPay'){
                                    $this->cancelNaverPay($info['billing_key']);
                                }
          				$product = '';                      
foreach($list as $row) {
	$product .= $row['cit_name'] . (!empty($row['cde_title']) ? '(' . $row['cde_title'] . ')' : '') . '/' . $row['qty'] . '
';	
}
				
				$msg = '[[클린디]]
' . $this->data['user']['mem_username'] . '님, 슬기로운 양치생활 클린디 정기배송이 해지되었습니다.
해지와 함께 매달 드리던 ' . number_format($info['org_price'] - $info['total_price']) . '원 상당의 혜택도 중지되었습니다.
- 구독번호 : ' . $info['csu_title'] . '
- 클린디 제품 : 
' . $product . '

클린디는 고객님이 원하실 때 언제든지 다시 이용하실 수 있습니다.
클린디 설문을 통해 구강건강 진단을 받아보세요

궁금하시거나 도움이 필요하시면 언제든 고객센터에 문의해 주세요

클린디 드림';
				$id = '50077';
				list($microtime,$timestamp) = explode(' ',microtime());
				$time = $timestamp.substr($microtime, 2, 3);
		
				$messages = array();
				
				$message = array();
				$message['no'] = '0';
				$message['tel_num'] = $this->data['user']['mem_phone'];
				$message['custom_key'] = $time . '000';
				$message['msg_content'] = $msg;
				$message['sms_content'] = $msg;
				$message['use_sms'] = '1';
				$message['btn_url'] = array();
				$button = array();
				$button['url_pc'] = 'https://www.cleand.kr/diagnosis';
				$button['url_mobile'] = 'https://www.cleand.kr/diagnosis';
				$message['btn_url'][] = $button; 
				$messages[] = $message;
		
				$this->sendBizMessage($id, $messages);	

				$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
				$base_url .= "://" . $_SERVER['HTTP_HOST'];

				$email = $this->email_m->email_detail('subcancel')->row_array();
					
				if(!empty($email)) {
					$html = $email['mail_content'];
					$html = str_replace('[=BASE_URL]', $base_url, $html);
					$html = str_replace('[=NAME]', $this->data['user']['mem_username'], $html);
					$html = str_replace('[=PRODUCT]', $product, $html);
					$html = str_replace('[=TITLE]', $info['csu_title'], $html);
					$html = str_replace('[=DATE]', date('Y-m-d'), $html);
					$this->email_m->email_insert($this->data['user']['mem_email'], $email['mail_title'], $html);
				}
				
				$order_info = $this->subscribe_m->subscribe_order_info($req['csu_id'])->row_array();
				if(!empty($order_info)) {
					$msg = '[클린디]
정기구독 해지 전에 결제된 미배송 주문이 있습니다.

해당 주문에 대해 배송을 원하지 안으면
배송 시작 전 클린디 홈페이지에서
로그인 후 주문관리 → 주문내역에서
결제를 취소해주세요.

- 미배송 주문건 : ' . $order_info['order_id'] . '
- 결제금액 : ' . number_format($order_info['total_price']) . '원

▶ 주문관리
https://cleand.kr/my/order/order_list

※ 참고
- 클린디 배송시간 : 평일 매일 오후 3시';

					$id = '50081';
					list($microtime,$timestamp) = explode(' ',microtime());
					$time = $timestamp.substr($microtime, 2, 3);
			
					$messages = array();
					
					$message = array();
					$message['no'] = '0';
					$message['tel_num'] = $this->data['user']['mem_phone'];
					$message['custom_key'] = $time . '000';
					$message['msg_content'] = $msg;
					$message['sms_content'] = $msg;
					$message['use_sms'] = '1';
					$message['btn_url'] = array();
					$button = array();
					$button['url_pc'] = 'https://cleand.kr/my/order/order_list';
					$button['url_mobile'] = 'https://cleand.kr/my/order/order_list';
					$message['btn_url'][] = $button; 
					$messages[] = $message;
			
					$this->sendBizMessage($id, $messages);	
				}
				$result['status'] = 'succ';
				$result['msg'] = '구독해지 되었습니다.';
			}
			else {
				$result['status'] = 'fail';
				$result['msg'] = '구독해지에 실패했습니다.';
			}
		}
		echo json_encode($result);
	}
	
	public function ajaxUpdateSubscribeAddr()
	{
		$req = $this->input->post();
		$result = array();
		if(empty($this->data['user'])) {
			$result['status'] = 'login';
			$result['msg'] = '로그인이 필요합니다.';
		}
		else if(empty($req['recipient_name'])) {
			$result['status'] = 'fail';
			$result['msg'] = '받는분 성함을 입력해 주세요.';
		}
		else if(empty($req['recipient_phone'])) {
			$result['status'] = 'fail';
			$result['msg'] = '받는분 연락처를 입력해 주세요.';
		}
		else if(empty($req['recipient_zip'])) {
			$result['status'] = 'fail';
			$result['msg'] = '우편번호를 입력해 주세요.';
		}
		else if(empty($req['recipient_addr2'])) {
			$result['status'] = 'fail';
			$result['msg'] = '상세주소를 입력해 주세요.';
		}
		else {
			$res = $this->subscribe_m->update_subscribe_addr($req);
			if($res) {
				$result['status'] = 'succ';
				$result['msg'] = '수정되었습니다.';
			}
			else {
				$result['status'] = 'fail';
				$result['msg'] = '수정에 실패했습니다.';
			}
		}
		echo json_encode($result);
	}

	public function ajaxUpdateSubscribeTitle()
	{
		$req = $this->input->post();
		$result = array();
		if(empty($this->data['user'])) {
			$result['status'] = 'login';
			$result['msg'] = '로그인이 필요합니다.';
		}
		else if(empty($req['csu_id'])) {
			$result['status'] = 'fail';
			$result['msg'] = '수정할 구독을 선택해 주세요.';
		}
		else if(empty($req['csu_title'])) {
			$result['status'] = 'fail';
			$result['msg'] = '구독명을 입력해 주세요.';
		}
		else {
			$res = $this->subscribe_m->update_subscribe_title($req);
			if($res) {
				$result['status'] = 'succ';
				$result['msg'] = '수정되었습니다.';
			}
			else {
				$result['status'] = 'fail';
				$result['msg'] = '수정에 실패했습니다.';
			}
		}
		echo json_encode($result);
	}
}
