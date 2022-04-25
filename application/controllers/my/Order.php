<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CD_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('common');
        $this->load->model('order_m');
        $this->load->model('common_m');
        $this->load->model('review_m');
    }

    public function index() {
        if (empty($this->data['user'])) {
            $this->data['msg'] = '로그인이  필요합니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $req['offset'] = 0;
            $req['perpage'] = 2;
            $req['mem_id'] = $this->data['user']['mem_id'];

            $req['order_status'] = 'order';
            $this->data['order'] = $this->order_m->order_list($req, (int) $req['offset'], (int) $req['perpage'])->result_array();
            $this->data['totalCnt'] = $this->order_m->order_list_cnt($req);

            $req['order_status'] = 'cancel';
            $this->data['cancel'] = $this->order_m->order_list($req, (int) $req['offset'], (int) $req['perpage'])->result_array();
            $this->data['cancelCnt'] = $this->order_m->order_list_cnt($req, (int) $req['offset'], (int) $req['perpage']);

            $req['order_status'] = array('CHANGE_REQUEST', 'CHANGE_ING', 'CHANGE_CANCEL', 'CHANGE_DENY', 'CHANGE_COMPLETE', 'RETURN_REQUEST', 'RETURN_PICKUP', 'RETURN_ENTER', 'RETURN_CHECK',
                'RETURN_COMPLETE', 'REFUND_REQUEST', 'REFUND_APPROVAL', 'REFUND_ACANCEL', 'REFUND_CONFIRM', 'REFUND_ERROR', 'REFUND_COMPLETE', 'RETURN_CANCEL', 'RETURN_DENY');
            $this->data['change'] = $this->order_m->order_list($req, (int) $req['offset'], (int) $req['perpage'])->result_array();

            $this->load->view('header_v', $this->data);
            $this->load->view('my/order/order_v');
            $this->load->view('footer_v');
        }
    }

    public function order_list() {
        if (empty($this->data['user'])) {
            $this->data['msg'] = '로그인이  필요합니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $req = $this->input->post();

            $req['mem_id'] = $this->data['user']['mem_id'];
            $req['order_status'] = 'order';
            $perpage = (isset($req['perpage']) ? (int) $req['perpage'] : 10);
            $offset = (int) $this->uri->segment(4, 0);
            $total_rows = $this->order_m->order_list_cnt($req);
            ;
            $num = $total_rows - $offset;

            $config = array();
            $config['base_url'] = '/my/order/order_list/';
            $config['total_rows'] = $total_rows;
            $config['perpage'] = $perpage;
            $config['offset'] = $offset;
            $config['num_links'] = 5;
            $pagination = $this->common->pagination($config);

            $list = $this->order_m->order_list($req, $offset, $perpage)->result_array();

            $this->data['list'] = $list;
            $this->data['total'] = $total_rows;
            $this->data['offset'] = $offset;
            $this->data['perpage'] = $perpage;
            $this->data['pagination'] = $pagination;
            $this->load->view('header_v', $this->data);
            $this->load->view('my/order/order_list_v');
            $this->load->view('footer_v');
        }
    }

    public function order_detail() {
        $req = $this->input->get();
        if (empty($this->data['user'])) {
            $this->data['msg'] = '로그인이  필요합니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else if (empty($req['seq'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->data['move'] = '/my/order/order_list';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $info = $this->order_m->order_detail($req['seq'])->row_array();
            $offset = (int) $this->uri->segment(4, 0);
            if (empty($info)) {
                $this->data['msg'] = '해당주문건이 없습니다.';
                $this->data['move'] = '/my/order/order_list/' . $offset;
                $this->load->view('header_v', $this->data);
                $this->load->view('errors/invalid_seq');
                $this->load->view('footer_v');
            } else {
                $info = $this->order_m->order_detail($req['seq'])->row_array();
                $info['list'] = $this->order_m->order_detail_list($req['seq'])->result_array();

                $this->data['info'] = $info;
                $this->data['orders'] = $this->review_m->order_list2($req['seq'])->result_array();
                $this->data['offset'] = $offset;
                $this->load->view('header_v', $this->data);
                $this->load->view('my/order/order_detail_v');
                $this->load->view('footer_v');
            }
        }
    }

    public function cancel_list() {
        if (empty($this->data['user'])) {
            $this->data['msg'] = '로그인이  필요합니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $req = $this->input->post();

            $req['order_status'] = 'cancel';
//			$req['order_status'] = array('CANCEL', 'REFUND_REQUEST', 'REFUND_COMPLETE', 'REFUND_ING');
            $req['mem_id'] = $this->data['user']['mem_id'];
            $perpage = (isset($req['perpage']) ? (int) $req['perpage'] : 10);
            $offset = (int) $this->uri->segment(4, 0);
            $total_rows = $this->order_m->order_list_cnt($req);
            ;
            $num = $total_rows - $offset;

            $config = array();
            $config['base_url'] = '/my/order/cancel_list/';
            $config['total_rows'] = $total_rows;
            $config['perpage'] = $perpage;
            $config['offset'] = $offset;
            $config['num_links'] = 5;
            $pagination = $this->common->pagination($config);

            $list = $this->order_m->order_list($req, $offset, $perpage)->result_array();

            $this->data['list'] = $list;
            $this->data['total'] = $total_rows;
            $this->data['offset'] = $offset;
            $this->data['perpage'] = $perpage;
            $this->data['pagination'] = $pagination;
            $this->load->view('header_v', $this->data);
            $this->load->view('my/order/cancel_list_v');
            $this->load->view('footer_v');
        }
    }

    public function cancel_detail() {
        $req = $this->input->get();

        if (empty($this->data['user'])) {
            $this->data['msg'] = '로그인이  필요합니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else if (empty($req['seq'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->data['move'] = '/my/order/order_list';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $info = $this->order_m->order_detail_cancel($req['seq'])->row_array();
            $offset = (int) $this->uri->segment(4, 0);
            if (empty($info)) {
                $this->data['msg'] = '해당주문건이 없습니다.';
                $this->data['move'] = '/my/order/order_cancel/' . $offset;
                $this->load->view('header_v', $this->data);
                $this->load->view('errors/invalid_seq');
                $this->load->view('footer_v');
            } else {
                $info['list'] = $this->order_m->order_detail_list($req['seq'])->result_array();
                $offset = (int) $this->uri->segment(4, 0);

                $this->data['info'] = $info;
                $this->data['offset'] = $offset;
                $this->data['bank'] = $this->common_m->bank_code()->result_array();
                $this->load->view('header_v', $this->data);
                $this->load->view('my/order/cancel_detail_v');
                $this->load->view('footer_v');
            }
        }
    }

    public function change_list() {
        if (empty($this->data['user'])) {
            $this->data['msg'] = '로그인이  필요합니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $req = $this->input->post();

            $req['order_status'] = array('CHANGE_REQUEST', 'CHANGE_ING', 'CHANGE_CANCEL', 'CHANGE_DENY', 'CHANGE_COMPLETE', 'RETURN_REQUEST', 'RETURN_PICKUP', 'RETURN_ENTER', 'RETURN_CHECK',
                'RETURN_COMPLETE', 'REFUND_REQUEST', 'REFUND_APPROVAL', 'REFUND_ACANCEL', 'REFUND_CONFIRM', 'REFUND_ERROR', 'REFUND_COMPLETE', 'RETURN_CANCEL', 'RETURN_DENY');
            $req['mem_id'] = $this->data['user']['mem_id'];
            $perpage = (isset($req['perpage']) ? (int) $req['perpage'] : 10);
            $offset = (int) $this->uri->segment(4, 0);
            $total_rows = $this->order_m->order_list_cnt($req);
            ;
            $num = $total_rows - $offset;

            $config = array();
            $config['base_url'] = '/my/order/change_list/';
            $config['total_rows'] = $total_rows;
            $config['perpage'] = $perpage;
            $config['offset'] = $offset;
            $config['num_links'] = 5;
            $pagination = $this->common->pagination($config);

            $list = $this->order_m->order_list($req, $offset, $perpage)->result_array();

            $this->data['list'] = $list;
            $this->data['total'] = $total_rows;
            $this->data['offset'] = $offset;
            $this->data['perpage'] = $perpage;
            $this->data['pagination'] = $pagination;
            $this->load->view('header_v', $this->data);
            $this->load->view('my/order/change_list_v');
            $this->load->view('footer_v');
        }
    }

    public function change_detail() {
        $req = $this->input->get();

        if (empty($this->data['user'])) {
            $this->data['msg'] = '로그인이  필요합니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else if (empty($req['seq'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->data['move'] = '/my/order/change_list';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $info = $this->order_m->order_detail_change($req['seq']);
            $offset = (int) $this->uri->segment(4, 0);
            if (empty($info)) {
                $this->data['msg'] = '해당주문건이 없습니다.';
                $this->data['move'] = '/my/order/change_list/' . $offset;
                $this->load->view('header_v', $this->data);
                $this->load->view('errors/invalid_seq');
                $this->load->view('footer_v');
            } else {
                $info['files'] = $this->common_m->file_list('change', $req['seq'])->result_array();
                $offset = (int) $this->uri->segment(4, 0);

                $this->data['info'] = $info;
                $this->data['offset'] = $offset;
                $this->data['move'] = '/my/order/change_list/' . $offset;
                $this->load->view('header_v', $this->data);
                $this->load->view('my/order/change_detail_v');
                $this->load->view('footer_v');
            }
        }
    }

    public function cancel_request() {
        $req = $this->input->get();

        if (empty($this->data['user'])) {
            $this->data['msg'] = '로그인이  필요합니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else if (empty($req['seq'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $info = $this->order_m->order_detail($req['seq'])->row_array();
            $offset = (int) $this->uri->segment(4, 0);
            if (empty($info)) {
                $this->data['msg'] = '해당주문건이 없습니다.';
                $this->data['move'] = '/my/order/order_list/' . $offset;
                $this->load->view('header_v', $this->data);
                $this->load->view('errors/invalid_seq');
                $this->load->view('footer_v');
            } else if ($info['status'] != 'REQUEST' && $info['status'] != 'PAYMENT') {
                $this->data['msg'] = '취소가 불가능한 주문건입니다.';
                $this->data['move'] = '/my/order/order_detail/' . $offset . '?seq=' . $req['seq'];
                $this->load->view('header_v', $this->data);
                $this->load->view('errors/invalid_seq');
                $this->load->view('footer_v');
            } else {
                $info['list'] = $this->order_m->order_detail_list($req['seq'])->result_array();

                $this->data['info'] = $info;
                $this->data['offset'] = $offset;
                $this->data['bank'] = $this->common_m->bank_code()->result_array();
                $this->load->view('header_v', $this->data);
                $this->load->view('my/order/cancel_request_v');
                $this->load->view('footer_v');
            }
        }
    }

    public function return_request() {
        $req = $this->input->get();

        if (empty($this->data['user'])) {
            $this->data['msg'] = '로그인이  필요합니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else if (empty($req['seq'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $info = $this->order_m->order_detail($req['seq'])->row_array();
            $offset = (int) $this->uri->segment(4, 0);
            if (empty($info)) {
                $this->data['msg'] = '해당주문건이 없습니다.';
                $this->data['move'] = '/my/order/order_list/' . $offset;
                $this->load->view('header_v', $this->data);
                $this->load->view('errors/invalid_seq');
                $this->load->view('footer_v');
            } else if ($info['status'] != 'DELIVERY_COMPLETE') {
                $this->data['msg'] = '반품이 불가한 주문건입니다.';
                $this->data['move'] = '/my/order/order_detail/' . $offset . '?seq=' . $req['seq'];
                $this->load->view('header_v', $this->data);
                $this->load->view('errors/invalid_seq');
                $this->load->view('footer_v');
            } else {
                $info['list'] = $this->order_m->order_detail_list($req['seq'])->result_array();

                $this->data['info'] = $info;
                $this->data['offset'] = $offset;
                $this->data['move'] = '/my/order/order_detail/' . $offset . '?seq=' . $req['seq'];
                $this->data['bank'] = $this->common_m->bank_code()->result_array();
                $this->load->view('header_v', $this->data);
                $this->load->view('my/order/return_request_v');
                $this->load->view('footer_v');
            }
        }
    }

    public function change_request() {
        $req = $this->input->get();

        if (empty($this->data['user'])) {
            $this->data['msg'] = '로그인이  필요합니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else if (empty($req['seq'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $info = $this->order_m->order_detail($req['seq'])->row_array();
            $offset = (int) $this->uri->segment(4, 0);
            if (empty($info)) {
                $this->data['msg'] = '해당주문건이 없습니다.';
                $this->data['move'] = '/my/order/order_list/' . $offset;
                $this->load->view('header_v', $this->data);
                $this->load->view('errors/invalid_seq');
                $this->load->view('footer_v');
            } else if ($info['status'] != 'DELIVERY_COMPLETE') {
                $this->data['msg'] = '교환요청이 불가한 주문건입니다.';
                $this->data['move'] = '/my/order/order_detail/' . $offset . '?seq=' . $req['seq'];
                $this->load->view('header_v', $this->data);
                $this->load->view('errors/invalid_seq');
                $this->load->view('footer_v');
            } else {
                $info['list'] = $this->order_m->order_detail_list($req['seq'])->result_array();

                $this->data['info'] = $info;
                $this->data['offset'] = $offset;
                $this->data['move'] = '/my/order/order_detail/' . $offset . '?seq=' . $req['seq'];
                $this->data['bank'] = $this->common_m->bank_code()->result_array();
                $this->load->view('header_v', $this->data);
                $this->load->view('my/order/change_request_v');
                $this->load->view('footer_v');
            }
        }
    }

    public function ajaxComplete() {
        $req = $this->input->post();

        if (empty($this->data['user'])) {
            $result['status'] = 'login';
            $result['msg'] = '로그인이 필요합니다.';
        } else if (empty($req['order_id'])) {
            $result['status'] = 'fail';
            $result['msg'] = '잘못된 접근입니다.';
        } else {
            $info = $this->order_m->order_detail($req['order_id'])->row_array();
            if (empty($info)) {
                $result['status'] = 'fail';
                $result['msg'] = '해당주문건이 없습니다.';
            } else if ($info['mem_id'] != $this->data['user']['mem_id']) {
                $result['status'] = 'fail';
                $result['msg'] = '잘못된 접근입니다.';
            } else if ($info['status'] != 'DELIVERY_COMPLETE' && $info['status'] != 'DELIVERY') {
                $result['status'] = 'fail';
                $result['msg'] = '이미 처리된 주문건입니다.';
            } else {
                $val = array();
                $val['order_id'] = $req['order_id'];
                $val['ins_user'] = $this->data['user']['mem_userid'];
                $val['old_status'] = $info['status'];
                $val['new_status'] = 'COMPLETE';
                $val['change_type'] = 'user';
                $val['mem_id'] = $this->data['user']['mem_id'];
                $val['use_coupon'] = $info['use_coupon'];
                $val['use_coupon_id'] = $info['use_coupon_id'];
                $val['use_coupon_type'] = $info['use_coupon_type'];
                $res = $this->order_m->order_complete($val);
                if ($res) {
                    $result['status'] = 'succ';
                    $result['msg'] = '구매완료 하였습니다. 감사합니다.';
                } else {
                    $result['status'] = 'fail';
                    $result['msg'] = '구매 완료에 실패했습니다.';
                }
            }
        }
        echo json_encode($result);
    }

    public function ajaxCancel() {
        $req = $this->input->post();

        if (empty($this->data['user'])) {
            $result['status'] = 'login';
            $result['msg'] = '로그인이 필요합니다.';
        } else if (empty($req['order_id'])) {
            $result['status'] = 'fail';
            $result['msg'] = '잘못된 접근입니다.';
        } else {
            $info = $this->order_m->order_detail($req['order_id'])->row_array();
            if (empty($info)) {
                $result['status'] = 'fail';
                $result['msg'] = '해당주문건이 없습니다.';
            } else if ($info['status'] == 'CANCEL' || strpos('REFUND', $info['status']) > 0) {
                $result['status'] = 'fail';
                $result['msg'] = '이미취소된 주문건입니다.';
            } else {
                $result_msg = '취소하였습니다.';
                if (empty(trim($req['reason_msg']))) {
                    $result_msg = '취소사유를 입력해 주세요.';
                    $res = false;
                } else if ($info['status'] == 'PAYMENT') {

                    if (($info['payMethod'] == 'VBank' || $info['payMethod'] == 'VBANK') && (empty($req['bank_code']) || empty(trim($req['bank_num'])) || empty(trim($req['bank_owner'])))) {
                        $result_msg = '환불계좌정보를 입력해 주세요.';
                        $res = false;
                    } else if (($info['payMethod'] == 'VBank' || $info['payMethod'] == 'VBANK') && (empty($req['bankCheck']) || $req['bankCheck'] == 'n')) {
                        $result_msg = '환불계좌 수집이용에 동의해 주세요.';
                        $res = false;
                    } else if (empty($req['cancelCheck']) || $req['cancelCheck'] == 'n') {
                        $result_msg = '주문취소에 동의해 주세요.';
                        $res = false;
                    } else if (empty($info['tid'])) {
                        $req['result_code'] = '00';
                        $req['result_msg'] = '';
                        $req['ins_user'] = $this->data['user']['mem_userid'];
                        $req['old_status'] = $info['status'];
                        $req['new_status'] = 'CANCEL';
                        $req['change_type'] = 'user';
                        $req['order_type'] = $info['order_type'];
                        $req['mem_id'] = $this->data['user']['mem_id'];
                        $res = $this->order_m->order_cancel($req);
                        if (!$res)
                            $result_msg = '취소결과 저장에 실패하였습니다.';
                    } else if ($info['payMethod'] == 'KakaoPay') {

                        $curl = null;
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, KAKAO_BASE_URL.'/v1/payment/cancel');
                        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_HEADER, 0);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_POST, 1);
                        $aPostData = array();
                        $aPostData['cid'] = KAKAO_CID_EASYPAY;
                        $aPostData['aid'] = $info['aid'];
                        $aPostData['tid'] = $info['tid'];
                        $aPostData['cancel_amount'] = $info['total_price'];
                        $aPostData['cancel_tax_free_amount'] = 0;
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
                        $header = Array(
                            'POST /v1/payment/cancel HTTP/1.1',
                            'Host: kapi.kakao.com',
                            'Authorization: KakaoAK 8c01888f64dffa0104a05ef170b7ba2b',
                            'Content-type: application/x-www-form-urlencoded;charset=utf-8'
                        );
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

                        $gData = curl_exec($curl);
                        $recv = json_decode($gData, true);

                        if (isset($recv['code'])) {
                            $result_msg = '결제취소에 실패했습니다.';
                            $res = false;
                        } else {
                            $req['result_code'] = 200;
                            $req['result_msg'] = '카카오페이 결제 취소';
                            $req['ins_user'] = $this->data['user']['mem_userid'];
                            $req['old_status'] = $info['status'];
                            $req['new_status'] = 'CANCEL';
                            $req['change_type'] = 'user';
                            $req['order_type'] = $info['order_type'];
                            $req['mem_id'] = $this->data['user']['mem_id'];
                            $res = $this->order_m->order_cancel($req);

                            if (!$res)
                                $result_msg = '취소결과 저장에 실패하였습니다.';

                            $this->sendCancelsms($info);
                        }
                    } else if ($info['payMethod'] == 'NaverPay') {

                        $curl = null;
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, 'https://dev.apis.naver.com/naverpay-partner/naverpay/payments/v1/cancel');
                        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_HEADER, 0);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_POST, 1);
                        $aPostData = array();
                        $aPostData['paymentId'] = $info['tid'];
                        $aPostData['cancelAmount'] = $info['total_price'];
                        $aPostData['taxScopeAmount'] = $info['total_price'];
                        $aPostData['taxExScopeAmount'] = 0;
                        $aPostData['cancelReason'] = '구매자 결제 취소 요청';
                        $aPostData['cancelRequester'] = 1;
                        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
                        $header = Array(
                            'X-Naver-Client-Id:f07MEDKDQ478StuME1ea',
                            'X-Naver-Client-Secret:HkH1m3sQQg',
                            'X-NaverPay-Chain-Id:dkVsWi9VSEF4N0x'
                        );
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

                        $gData = curl_exec($curl);
                        $recv = json_decode($gData, true);
                        
                        if ($recv['code'] != 'Success') {
                            $result_msg = '결제취소에 실패했습니다.';
                            $res = false;
                        } else {
                            $req['result_code'] = 200;
                            $req['result_msg'] = '네이버페이 결제 취소';
                            $req['ins_user'] = $this->data['user']['mem_userid'];
                            $req['old_status'] = $info['status'];
                            $req['new_status'] = 'CANCEL';
                            $req['change_type'] = 'user';
                            $req['order_type'] = $info['order_type'];
                            $req['mem_id'] = $this->data['user']['mem_id'];
                            $res = $this->order_m->order_cancel($req);

                            if (!$res)
                                $result_msg = '취소결과 저장에 실패하였습니다.';
                            
                            $this->sendCancelsms($info);
                            
                        }
                    } else {
                        $timestamp = date("YmdHis");
                        $ip = '27.96.130.218';
                        $type = 'Refund';
                        $paymethod = '';
                        $mid = '';
                        $hashData = '';
                        if ($info['payMethod'] == 'CARD' || $info['payMethod'] == 'Card' || $info['payMethod'] == 'VCard') {
                            $paymethod = 'Card';
//							if(substr($info['order_type'], 0, 3) == 'billing') {
                            if (substr($req['order_id'], 0, 3) == 'CDA') {
                                $mid = MIDB;
                                $hashData = BIL_INI_API_KEY . $type . $paymethod . $timestamp . $ip . $mid . $info['tid'];
                            } else {
                                $mid = MID0;
                                $hashData = WEB_INI_API_KEY . $type . $paymethod . $timestamp . $ip . $mid . $info['tid'];
                            }
                        } else if ($info['payMethod'] == 'DirectBank' || $info['payMethod'] == 'BANK') {
                            $paymethod = 'Acct';
                            $mid = MID0;
                            $hashData = WEB_INI_API_KEY . $type . $paymethod . $timestamp . $ip . $mid . $info['tid'];
                        } else if ($info['payMethod'] == 'VBank' || $info['payMethod'] == 'VBANK') {
                            $paymethod = 'Vacct';
                            $mid = MID0;
                            $bank_num = @openssl_encrypt($req['bank_num'], "aes-128-cbc", WEB_INI_API_KEY, true, WEB_INI_API_IV);
                            $bank_num = base64_encode($bank_num);
                            $req['bank_num'] = $bank_num;
                            $hashData = WEB_INI_API_KEY . $type . $paymethod . $timestamp . $ip . $mid . $info['tid'] . $bank_num;
                        }

                        $params = array(
                            'type' => $type,
                            'paymethod' => $paymethod,
                            'timestamp' => $timestamp,
                            'clientIp' => $ip,
                            'mid' => $mid,
                            'tid' => $info['tid'],
                            'msg' => '사용자요청',
                            'hashData' => hash('sha512', $hashData)
                        );

                        if ($info['payMethod'] == 'VBank' || $info['payMethod'] == 'VBANK') {
                            $params['refundAcctNum'] = $req['bank_num'];
                            $params['refundBankCode'] = $req['bank_code'];
                            $params['refundAcctName'] = $req['bank_owner'];
                        }

                        $ch = curl_init();                                 //curl 초기화
                        curl_setopt($ch, CURLOPT_URL, REFUND_URL);               //URL 지정하기
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환 
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초 
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함
                        curl_setopt($ch, CURLOPT_HEADER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));       //POST data
                        curl_setopt($ch, CURLOPT_POST, 1);              //true시 post 전송 

                        $response = curl_exec($ch);

                        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                        $header = substr($response, 0, $header_size);
                        $body = substr($response, $header_size);
                        $result = json_decode($body);
                        curl_close($ch);

                        if (!isset($result->resultCode)) {
                            $result_msg = '결제취소에 실패했습니다.';
                            $res = false;
                        } else if ($result->resultCode !== '00') {
                            $result_msg = $result->resultMsg;
                            $res = false;
                        } else {
                            $req['result_code'] = $result->resultCode;
                            $req['result_msg'] = $result->resultMsg;
                            $req['ins_user'] = $this->data['user']['mem_userid'];
                            $req['old_status'] = $info['status'];
                            $req['new_status'] = 'CANCEL';
                            $req['change_type'] = 'user';
                            $req['order_type'] = $info['order_type'];
                            $req['mem_id'] = $this->data['user']['mem_id'];
                            $res = $this->order_m->order_cancel($req);
                            if (!$res)
                                $result_msg = '취소결과 저장에 실패하였습니다.';


                            if ($info['order_type'] == 'billing') {
                                list($microtime, $timestamp) = explode(' ', microtime());
                                $time = $timestamp . substr($microtime, 2, 3);

                                $messages = array();

                                $message = array();
                                $msg = '[[클린디]]
' . $info['mem_username'] . '님, 슬기로운 양치생활 클린디 정기구독 결제가 취소되었습니다.

다음번 정기구독 결제는 ' . $info['check_date'] . ' 에 진행됩니다.
정기구독 결제 일정을 변경하시거나 정기구독을 해지하시려면 마이페이지-정기구독 관리에서 변경해주세요

▷ 정기구독 관리하기 : https://www.cleand.kr/my/subscribe/detail/0?seq=' . $info['csu_id'] . '

' . $info['mem_username'] . '님의 구강 건강을 위해 클린디는 최선을 다하겠습니다.';

                                $message['no'] = '0';
                                $message['tel_num'] = $info['mem_phone'];
                                $message['custom_key'] = $time . '000';
                                $message['msg_content'] = $msg;
                                $message['sms_content'] = $msg;
                                $message['use_sms'] = '1';
                                $message['btn_url'] = array();
                                $button = array();
                                $button['url_pc'] = 'https://www.cleand.kr/my/subscribe/detail/0?seq=' . $info['csu_id'];
                                $button['url_mobile'] = 'https://www.cleand.kr/my/subscribe/detail/0?seq=' . $info['csu_id'];
                                $message['btn_url'][] = $button;

                                $messages[] = $message;

                                $id = '50075';
                                $this->sendBizMessage($id, $messages);
                            } else {
                                $this->sendCancelsms($info);
                            }
                        }
                    }
                } else {
                    if (empty($req['cancelCheck']) || $req['cancelCheck'] == 'n') {
                        $result_msg = '주문취소에 동의해 주세요.';
                        $res = false;
                    } else {
                        $req['ins_user'] = $this->data['user']['mem_userid'];
                        $req['old_status'] = $info['status'];
                        $req['new_status'] = 'CANCEL';
                        $req['change_type'] = 'user';
                        $req['order_type'] = $info['order_type'];
                        $req['mem_id'] = $this->data['user']['mem_id'];
                        $res = $this->order_m->order_cancel($req);
                    }
                }

                $result = array();
                if ($res) {
                    $result['status'] = 'succ';
                    $result['msg'] = $result_msg;
                } else {
                    $result['status'] = 'fail';
                    $result['msg'] = $result_msg;
                }
            }
        }
        echo json_encode($result);
    }
    
    private function sendCancelsms($info) {
              list($microtime, $timestamp) = explode(' ', microtime());
                            $time = $timestamp . substr($microtime, 2, 3);
                            $messages = array();
                            $message = array();
                            $msg = $info['mem_username'] . '님의 주문이 정상적으로
취소되었습니다.

▶ 취소내역
- 상품명 : ' . $info['product_name'] . '
- 주문번호 : ' . $info['order_id'] . '
- 환불금액 : ' . number_format($info['total_price']) . '원

다음에는 더 좋은 상품으로 보답드리겠습니다.

※ 환불은 주문 시 사용한 결제수단에 따라
영업 1~3일이 소요될 수 있습니다.';

                            $message['no'] = '0';
                            $message['tel_num'] = $info['mem_phone'];
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

                            $id = '50082';
                            $this->sendBizMessage($id, $messages);
    }

    public function ajaxChange() {
        $req = $this->input->post();

        if (empty($this->data['user'])) {
            $result['status'] = 'login';
            $result['msg'] = '로그인이 필요합니다.';
        } else if (empty($req['order_id'])) {
            $result['status'] = 'fail';
            $result['msg'] = '잘못된 접근입니다.';
        } else {
            $result = $this->fnCheckReturn($req);
            if ($result['status'] == 'succ') {
                $info = $this->order_m->order_detail($req['order_id'])->row_array();
                if (empty($info)) {
                    $result['status'] = 'fail';
                    $result['msg'] = '해당주문건이 없습니다.';
                } else if ($info['mem_id'] != $this->data['user']['mem_id']) {
                    $result['status'] = 'fail';
                    $result['msg'] = '잘못된 요청입니다.';
                } else if ($info['status'] != 'DELIVERY_COMPLETE') {
                    $result['status'] = 'fail';
                    $result['msg'] = '이미 처리된 주문건입니다.';
                } else {
                    $req['order_type'] = $info['order_type'];
                    $req['ins_user'] = $this->data['user']['mem_userid'];
                    $req['old_status'] = $info['status'];
                    $req['new_status'] = $req['action_type'] == 'return' ? 'RETURN_REQUEST' : 'CHANGE_REQUEST';
                    $req['change_type'] = 'user';
                    $req['total_price'] = $info['total_price'];
                    $res = $this->order_m->change_request($req);
                    if ($res) {
                        list($microtime, $timestamp) = explode(' ', microtime());
                        $time = $timestamp . substr($microtime, 2, 3);

                        $messages = array();

                        $message = array();
                        $msg = '[[클린디]]
안녕하세요. ' . $info['mem_username'] . '님

요청하신 취소 및 반품/교환 접수가 완료되었습니다.

ID : ' . $info['mem_email'] . '
요청일 : ' . date('Y-m-d') . '


▷ [클린디] 바로가기
https://www.cleand.kr/';
                        $message['no'] = '0';
                        $message['tel_num'] = $info['mem_phone'];
                        $message['custom_key'] = $time . '000';
                        $message['msg_content'] = $msg;
                        $message['sms_content'] = $msg;
                        $message['use_sms'] = '1';
                        $message['btn_url'] = array();
                        $button = array();
                        $button['url_pc'] = 'https://www.cleand.kr/my/order';
                        $button['url_mobile'] = 'https://www.cleand.kr/my/order';
                        $message['btn_url'][] = $button;

                        $messages[] = $message;

                        $id = '50058';
                        $this->sendBizMessage($id, $messages);

                        $result['status'] = 'succ';
                        $result['msg'] = ($req['action_type'] == 'return' ? '반품' : '교환') . ' 신청이 완료되었습니다.';
                        $result['msg2'] = '해당 상품 상세페이지에 표기된<br>배송/반품/교환/반품 정책을 참조 바랍니다.';
                    } else {
                        $result['status'] = 'fail';
                        $result['msg'] = ($req['action_type'] == 'return' ? '반품' : '교환') . '요청에 실패했습니다.';
                    }
                }
            }
        }
        echo json_encode($result);
    }

    public function ajaxChangeBank() {
        $req = $this->input->post();

        $result = array();
        if (empty($this->data['user'])) {
            $result['status'] = 'login';
            $result['msg'] = '로그인이 필요합니다.';
        } else if (empty($req['crf_id'])) {
            $result['status'] = 'fail';
            $result['msg'] = '잘못된 접근입니다.';
        } else if (empty(trim($req['reason_msg']))) {
            $result['status'] = 'fail';
            $result['msg'] = '취소사유를 입력해 주세요.';
        } else if (empty($req['bank_code']) || empty(trim($req['bank_num'])) || empty(trim($req['bank_owner']))) {
            $result['status'] = 'fail';
            $result['msg'] = '환불계좌정보를 입력해 주세요.';
        } else {
            $val = array();
            $val['bank_code'] = $req['bank_code'];
            $val['bank_name'] = $req['bank_name'];
            $val['bank_owner'] = $req['bank_owner'];
            $val['bank_num'] = $req['bank_num'];
            $val['refund_memo'] = $req['reason_msg'];

            $this->order_m->update_refund($req['crf_id'], $val);
            $result['status'] = 'succ';
            $result['msg'] = '수정되었습니다.';
        }
        echo json_encode($result);
    }

    private function fnCheckReturn($req) {
        $action = $req['action_type'] == 'return' ? '반품' : '교환';
        $item = array();
        $result = array();
        $result['status'] = 'succ';

        if (!empty($req['chk'])) {
            for ($i = 0; $i < count($req['chk']); $i++) {
                if (!empty($req['cod_id'])) {
                    for ($j = 0; $j < count($req['cod_id']); $j++) {
                        if ($req['chk'][$i] == $req['cod_id'][$j]) {
                            if ($req['req_qty'][$j] > 0) {
                                $tmp = array();
                                $tmp['cod_id'] = $req['cod_id'][$j];
                                $tmp['unit_price'] = $req['unit_price'][$j];
                                $tmp['qty'] = $req['req_qty'][$j];
                                $item[] = $tmp;
                                break;
                            } else {
                                $result['status'] = 'fail';
                                $result['msg'] = $action . '상품 \'' . $req['cit_name'][$i] . '\' ' . '의 수량을 입력해 주세요.';
                                return $result;
                            }
                        }
                    }
                } else {
                    if ($req['req_qty'][0] > 0) {
                        $tmp = array();
                        $tmp['order_id'] = $req['order_id'];
                        $item[] = $tmp;
                    } else {
                        $result['status'] = 'fail';
                        $result['msg'] = '수량을 입력해 주세요.';
                        return $result;
                    }
                }
            }
        }

        if (empty($item)) {
            $result['status'] = 'fail';
            $result['msg'] = $action . '신청할 상품을 선택해주시고 수량을 입력해 주세요.';
        } else if (empty($req['reason_code'])) {
            $result['status'] = 'fail';
            $result['msg'] = $action . ' 사유를 선택해 주세요.';
        } else if ($req['reason_code'] != '1' && empty($req['newname'])) {
            $result['status'] = 'fail';
            $result['msg'] = '사진을 첨부해 주세요.';
        } else if (empty(trim($req['recipient_phone']))) {
            $result['status'] = 'fail';
            $result['msg'] = '연락처를 입력해 주세요.';
        } else if (empty(trim($req['recipient_zip'])) || empty(trim($req['recipient_addr2']))) {
            $result['status'] = 'fail';
            $result['msg'] = '주소를 입력해 주세요.';
        } else if (isset($req['delivery_way']) && $req['reason_code'] == '1' && empty($req['delivery_way'])) {
            $result['status'] = 'fail';
            $result['msg'] = '배송비 결제방법을 선택해 주세요.';
        } else if ($req['action_type'] == 'return') {
            if (empty($req['bank_code'])) {
                $result['status'] = 'fail';
                $result['msg'] = '환불받으실 은행을 선택해 주세요.';
            } else if (empty($req['bank_owner'])) {
                $result['status'] = 'fail';
                $result['msg'] = '환불받으실 예금주를 입력해 주세요.';
            } else if (empty($req['bank_num'])) {
                $result['status'] = 'fail';
                $result['msg'] = '환불받으실 계좌번호를 입력해 주세요.';
            } else if (empty($req['agree'])) {
                $result['status'] = 'fail';
                $result['msg'] = '반품신청에 동의해 주세요';
            } else if (empty($req['bankCheck'])) {
                $result['status'] = 'fail';
                $result['msg'] = '환불계좌 수집이용에 동의해 주세요';
            }
        }

        return $result;
    }

    public function ajaxChangeDelivery() {
        $req = $this->input->post();

        $result = array();
        if (empty($this->data['user'])) {
            $result['status'] = 'login';
            $result['msg'] = '로그인이 필요합니다.';
        } else if (empty($req['order_id'])) {
            $result['status'] = 'fail';
            $result['msg'] = '잘못된 접근입니다.';
        } else if (empty(trim($req['recipient_name']))) {
            $result['status'] = 'fail';
            $result['msg'] = '수령인명을 입력해 주세요.';
        } else if (empty(trim($req['recipient_phone']))) {
            $result['status'] = 'fail';
            $result['msg'] = '수령인 연락처를 입력해 주세요.';
        } else if (empty(trim($req['zipcode']))) {
            $result['status'] = 'fail';
            $result['msg'] = '주소를 입력해 주세요.';
        } else if (empty(trim($req['detail_addr']))) {
            $result['status'] = 'fail';
            $result['msg'] = '상세주소를 입력해 주세요.';
        } else {
            $res = $this->order_m->order_detail2($req['order_id'], $req['mem_id'])->row_array();
            if (empty($res)) {
                $result['status'] = 'fail';
                $result['msg'] = '잘못된 접근입니다.';
            } else if ($res['status'] !== 'REQUEST' && $res['status'] !== 'PAYMENT') {
                $result['status'] = 'fail';
                $result['msg'] = '상품을 발송하여 수령주소를 수정할 수 없습니다.';
            } else {
                $this->order_m->delivery_change($req);
                $result['status'] = 'succ';
                $result['msg'] = '수정되었습니다.';
            }
        }
        echo json_encode($result);
    }

}
