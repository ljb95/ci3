<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnosis extends CD_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('common');
        $this->load->model('diagnosis_m');
        $this->load->model('subscribe_m');
        $this->load->model('common_m');
        $this->load->model('member_m');
        $this->load->model('cart_m');
        $this->load->model('payment_m');
        $this->load->model('email_m');
    }

    public function index() {
        $this->load->view('header_v', $this->data);
        $this->load->view('diagnosis/diagnosis_v');
        $this->load->view('footer_v');
    }

    public function detail() {
        if (!isset($_SERVER['HTTP_REFERER'])) {
            $this->data['move'] = '/';
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            if (strpos($_SERVER['HTTP_REFERER'], $this->data['base_url'] . '/diagnosis') >= 0) {
                $req = $this->input->get();
                $this->data['info'] = $this->diagnosis_m->diagnosis_info($req['seq'])->row_array();
                $this->data['item'] = $this->diagnosis_m->diagnosis_item()->result_array();
                if (!empty($this->data['user'])) {
                    $this->data['member'] = $this->member_m->member_info($this->data['user']['mem_id'])->row_array();
                    $this->data['delivery'] = $this->cart_m->delivery_address_default($this->data['user']['mem_id'])->row_array();
                } else {
                    $this->data['member'] = array();
                    $this->data['member']['is_starter'] = 'n';
                }
                $this->load->view('header_v', $this->data);
                $this->load->view('diagnosis/diagnosis_complete_v');
                $this->load->view('footer_v');
            } else if (!empty($this->data['user'])) {
                $req = $this->input->get();
                $info = $this->diagnosis_m->diagnosis_info2($req['seq'], $this->data['user']['mem_id'])->row_array();
                if (empty($info)) {
                    $this->data['move'] = '/';
                    $this->data['msg'] = '잘못된 접근입니다.';
                    $this->load->view('header_v', $this->data);
                    $this->load->view('errors/invalid_seq');
                    $this->load->view('footer_v');
                } else {
                    $this->data['info'] = $info;
                    $this->data['item'] = $this->diagnosis_m->diagnosis_item()->result_array();
                    $this->data['member'] = $this->member_m->member_info($this->data['user']['mem_id'])->row_array();
                    $this->data['delivery'] = $this->cart_m->delivery_address_default($this->data['user']['mem_id'])->row_array();
                    $this->load->view('header_v', $this->data);
                    $this->load->view('diagnosis/diagnosis_complete_v');
                    $this->load->view('footer_v');
                }
            } else {
                $this->data['move'] = '/';
                $this->data['msg'] = '잘못된 접근입니다.';
                $this->load->view('header_v', $this->data);
                $this->load->view('errors/invalid_seq');
                $this->load->view('footer_v');
            }
        }
    }

    public function ajaxSaveResult() {
        $req = $this->input->post();

        $result = array();

        $brush = array();
        $brush['line'] = 0;
        $brush['strong'] = 0;
        $brush['shape1'] = 0;
        $brush['shape2'] = 0;

        $score = array();
        $score['oral'] = 0;
        $score['gum'] = 0;
        $score['tooth'] = 0;

        if (empty($this->data['user'])) {
            $req['mem_id'] = 0;
        } else {
            $req['mem_id'] = $this->data['user']['mem_id'];
        }

        if (empty($req['user_name'])) {
            $result['status'] = 'fail';
            $result['msg'] = '고객님의 이름을 입력해 주세요.';
            echo json_encode($result);
            exit;
        }

        if (empty($req['user_sex'])) {
            $result['status'] = 'fail';
            $result['msg'] = '고객님의 성별을 입력해 주세요.';
            echo json_encode($result);
            exit;
        } else if ($req['user_sex'] == '1') {
            $brush['line'] += 7;
        } else if ($req['user_sex'] == '2') {
            $brush['line'] += 15;
        }

        if (empty($req['user_age'])) {
            $result['status'] = 'fail';
            $result['msg'] = '고객님의 나이을 입력해 주세요.';
            echo json_encode($result);
            exit;
        } else if ($req['user_age'] <= 13) {
            $brush['line'] += 10;
            $brush['strong'] += 5;
            $score['gum'] += 10;
            $score['tooth'] += 5;
        } else if ($req['user_age'] > 13 && $req['user_age'] <= 18) {
            $brush['line'] += 10;
            $brush['strong'] += 10;
            $score['gum'] += 10;
            $score['tooth'] += 5;
        } else if ($req['user_age'] > 18 && $req['user_age'] <= 50) {
            $brush['line'] += 25;
            $brush['strong'] += 10;
            $score['gum'] += 10;
            $score['tooth'] += 10;
        } else {
            $brush['line'] += 25;
            $brush['strong'] += 5;
            $brush['shape2'] += 1;
        }

        if (empty($req['user_height'])) {
            $result['status'] = 'fail';
            $result['msg'] = '고객님의 키를 입력해 주세요.';
            echo json_encode($result);
            exit;
        } else if ($req['user_height'] <= 160) {
            $brush['line'] += 5;
        } else if ($req['user_height'] >= 161 && $req['user_height'] <= 179) {
            $brush['line'] += 10;
        } else if ($req['user_height'] >= 180) {
            $brush['line'] += 20;
        }

        if (empty($req['user_weight'])) {
            $result['status'] = 'fail';
            $result['msg'] = '고객님의 몸무게를 입력해 주세요.';
            echo json_encode($result);
            exit;
        } else if ($req['user_weight'] <= 60) {
            $brush['line'] += 5;
        } else if ($req['user_weight'] >= 61 && $req['user_weight'] <= 79) {
            $brush['line'] += 10;
        } else if ($req['user_weight'] >= 80) {
            $brush['line'] += 15;
        }

        if (empty($req['structure'])) {
            $result['status'] = 'fail';
            $result['msg'] = '고객님의 구강구조를 선택해 주세요.';
            echo json_encode($result);
            exit;
        } else if ($req['structure'] == '1') {
            $brush['line'] += 15;
        } else if ($req['structure'] == '2') {
            $brush['line'] += 20;
        } else if ($req['structure'] == '3') {
            $brush['line'] += 25;
        }

        if (empty($req['brush_cnt'])) {
            $result['status'] = 'fail';
            $result['msg'] = '고객님의 하루 양치횟수를 선택해 주세요.';
            echo json_encode($result);
            exit;
        } else if ($req['brush_cnt'] == '1') {
            $brush['strong'] += 10;
        } else if ($req['brush_cnt'] == '2') {
            $brush['strong'] += 2;
            $score['oral'] += 15;
        } else if ($req['brush_cnt'] == '3') {
            $score['oral'] += 20;
        }

        if (empty($req['brush_time'])) {
            $result['status'] = 'fail';
            $result['msg'] = '고객님의 양치시간을 선택해 주세요.';
            echo json_encode($result);
            exit;
        } else if ($req['brush_time'] == '1') {
            $brush['strong'] += 15;
        } else if ($req['brush_time'] == '2') {
            $brush['strong'] += 12;
            $score['oral'] += 20;
        } else if ($req['brush_time'] == '3') {
            $brush['strong'] += 6;
            $score['oral'] += 25;
        }

        if (empty($req['life'])) {
            $result['status'] = 'fail';
            $result['msg'] = '고객님의 생활습관을 선택해 주세요.';
            echo json_encode($result);
            exit;
        } else {
            $score['tooth'] += 5;
            $brush['strong'] += 8;
            $score['oral'] += 10;
            $score['oral'] += 10;
            $score['oral'] += 5;
            $score['oral'] += 5;
            for ($i = 0; $i < count($req['life']); $i++) {
                if ($req['life'][$i] == '1') {
                    $brush['strong'] += 12;
                    $score['oral'] -= 10;
                } else if ($req['life'][$i] == '2') {
                    
                } else if ($req['life'][$i] == '3') {
                    
                } else if ($req['life'][$i] == '4') {
                    $score['oral'] -= 5;
                } else if ($req['life'][$i] == '5') {
                    $brush['strong'] -= 8;
                    $score['oral'] -= 10;
                    $score['tooth'] -= 5;
                } else if ($req['life'][$i] == '6') {
                    
                } else if ($req['life'][$i] == '7') {
                    $score['oral'] -= 5;
                }
            }
        }

        if (empty($req['health'])) {
            $result['status'] = 'fail';
            $result['msg'] = '고객님의 건강상태를 선택해 주세요.';
            echo json_encode($result);
            exit;
        } else {
            $score['oral'] += 5;
            for ($i = 0; $i < count($req['health']); $i++) {
                if ($req['health'][$i] == '1') {
                    
                } else if ($req['health'][$i] == '2') {
                    
                } else if ($req['health'][$i] == '3') {
                    
                } else if ($req['health'][$i] == '4') {
                    
                } else if ($req['health'][$i] == '5') {
                    
                } else if ($req['health'][$i] == '6') {
                    
                } else if ($req['health'][$i] == '7') {
                    
                } else if ($req['health'][$i] == '8') {
                    
                } else if ($req['health'][$i] == '9') {
                    $score['oral'] -= 5;
                }
            }
        }

        if (empty($req['blood'])) {
            $result['status'] = 'fail';
            $result['msg'] = '고객님의 구강상태를 선택해 주세요.';
            echo json_encode($result);
            exit;
        } else {
            $brush['strong'] += 5 + 5;
            $score['gum'] += 20 + 15 + 10;
            for ($i = 0; $i < count($req['blood']); $i++) {
                if ($req['blood'][$i] == '1') {
                    $score['gum'] -= 20;
                    $brush['strong'] -= 5;
                    $brush['shape2'] += 1;
                } else if ($req['blood'][$i] == '2') {
                    $score['gum'] -= 15;
                    $brush['strong'] -= 5;
                    $brush['shape2'] += 1;
                } else if ($req['blood'][$i] == '3') {
                    $score['gum'] -= 10;
                }
            }
        }

        if ($req['scaling'] == '1') {
            $score['oral'] += 20;
        } else {
            $brush['shape1'] += 1;
            $brush['strong'] += 10;
        }

        if (empty($req['tooth'])) {
            $result['status'] = 'fail';
            $result['msg'] = '고객님의 치아상태를 선택해 주세요.';
            echo json_encode($result);
            exit;
        } else {
            $score['tooth'] += 5 + 15 + 5 + 10 + 15 + 5;
            $brush['strong'] += 8 + 5;
            $score['gum'] += 20;
            for ($i = 0; $i < count($req['tooth']); $i++) {
                if ($req['tooth'][$i] == '1') {
                    $brush['shape1'] += 1;
                    $score['tooth'] -= 5;
                } else if ($req['tooth'][$i] == '2') {
                    $brush['shape2'] += 1;
                    $score['tooth'] -= 15;
                    $brush['strong'] -= 8;
                } else if ($req['tooth'][$i] == '3') {
                    $brush['shape1'] += 1;
                    $score['tooth'] -= 5;
                    $brush['strong'] += 12;
                } else if ($req['tooth'][$i] == '4') {
                    $brush['shape2'] += 1;
                    $score['gum'] -= 20;
                    $brush['strong'] -= 5;
                    $score['tooth'] -= 10;
                } else if ($req['tooth'][$i] == '5') {
                    $score['tooth'] -= 15;
                } else if ($req['tooth'][$i] == '6') {
                    $score['tooth'] -= 5;
                }
            }
        }

        $score['gum'] += round($score['oral'] * 0.25);
        $score['tooth'] += round($score['oral'] * 0.3);

        $req['brush_line_score'] = $brush['line'];
        $req['brush_strong_score'] = $brush['strong'];
        $req['brush_shape1_score'] = $brush['shape1'];
        $req['brush_shape2_score'] = $brush['shape2'];
        $req['score_oral'] = $score['oral'];
        $req['score_gum'] = $score['gum'];
        $req['score_tooth'] = $score['tooth'];
        $req['score_total'] = Round(($score['oral'] * 0.4) + ($score['gum'] * 0.3) + ($score['tooth'] * 0.3));
        $req['brush_line_name'] = '';
        if ($brush['line'] <= 65)
            $req['brush_line_name'] = '4줄';
        else if ($brush['line'] > 65 && $brush['line'] <= 85)
            $req['brush_line_name'] = '5줄';
        else if ($brush['line'] > 85)
            $req['brush_line_name'] = '6줄';
        $req['brush_strong_name'] = $brush['strong'] <= 57 ? '부드러운모' : '강한모';
        $req['brush_shape_name'] = ($brush['strong'] <= 57 ? ($brush['shape2'] < 1 ? '미세모' : '초극세모') : ($brush['shape1'] < 2 ? '기능모' : '탄력모'));

        $res = $this->diagnosis_m->insert_diagnosis($req);
        if ($res > 0) {
            $result['status'] = 'succ';
            $result['msg'] = $res;

            if ($req['mem_id'] == 0) {
                $diagnosis = array();
                $tmp = $this->session->userdata('diagnosis');
                if (!empty($tmp)) {
                    $diagnosis = $tmp;
                }
                $diagnosis[] = $res;
                $this->session->set_userdata('diagnosis', $diagnosis);
            }
        } else {
            $result['status'] = 'fail';
            $result['msg'] = '진단에 실패했습니다. 관리자에게 문의해 주세요.';
        }

        echo json_encode($result);
        exit;
    }

    public function kakao() {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, KAKAO_BASE_URL . '/v1/payment/ready');
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        $pid = $_POST['pay_id'];
        unset($_POST['pay_id']);

        $aPostData = array();
        foreach ($_POST as $key => $val) {
            $aPostData[$key] = $val;
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
        $header = Array(
            'POST /v1/payment/ready HTTP/1.1',
            'Host: kapi.kakao.com',
            'Authorization: KakaoAK 8c01888f64dffa0104a05ef170b7ba2b',
            'Content-type: application/x-www-form-urlencoded;charset=utf-8'
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $gData = curl_exec($curl);
        $data = array();
        $data['step'] = 'ready';
        $data['pid'] = $pid;
        $data['tid'] = json_decode($gData)->tid;
        $res = $this->payment_m->payment_kakao($data);

        curl_close($curl);

        echo $gData;
    }

    private function naver_payment($pay, $res, $data, $merchant) {
        if ($pay['status'] == 'OK') {
            header('Location: /my/order/order_list');
        }

        $res['status'] = (isset($data['resultCode']) && $data['resultCode'] == 'Success') ? 'OK' : 'fail';

        if ($res['status'] == 'OK') {
            $curl = curl_init();
            $url = ($pay['order_type'] == 'subscribe') ? 'https://dev.apis.naver.com/naverpay-partner/naverpay/payments/recurrent/regist/v1/approval' : 'https://dev.apis.naver.com/naverpay-partner/naverpay/payments/v2.2/apply/payment';
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            $aPostData = array();

            if ($pay['order_type'] == 'subscribe') {
                $aPostData['reserveId'] = $data['reserveId'];
                $aPostData['tempReceiptId'] = $data['tempReceiptId'];
            } else {
                $aPostData['paymentId'] = $data['paymentId'];
            }
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
            $header = Array(
                'X-Naver-Client-Id:f07MEDKDQ478StuME1ea',
                'X-Naver-Client-Secret:HkH1m3sQQg',
                'X-NaverPay-Chain-Id:dkVsWi9VSEF4N0x'
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

            $gData = curl_exec($curl);
            $recv = json_decode($gData, true);

            if ($recv['code'] == "Success") {

                if ($pay['order_mem_type'] === 'guest') {
                    $pay['mem_id'] = $this->payment_m->payment_adduser($pay);
                    $val = array();
                    $val['mem_id'] = $pay['mem_id'];
                    $val['cdg_id'] = $merchant[1];
                    $this->diagnosis_m->update_diagnosis($val);
                }

                $res['res_result'] = ($pay['order_type'] == 'subscribe') ? $recv['body']['reserveId'] : $recv['body']['paymentId'];
                $res['res_msg'] = '성공';
                $res['status'] = 'OK';
                $res['pay_id'] = $pay['pay_id'];
                $this->payment_m->payment_response($res);

                $pay['status'] = 'PAYMENT';

                $resultMap = array();
                $resultMap['goodsName'] = $pay['product_name'];
                $resultMap['tid'] = ($pay['order_type'] == 'subscribe') ? $recv['body']['reserveId'] : $recv['body']['paymentId'];
                $resultMap['payMethod'] = ($pay['order_type'] == 'subscribe') ? '' : $recv['body']['detail']['primaryPayMeans'];
                $resultMap['payDevice'] = 'PC';
                $resultMap['vactBankName'] = '';
                $resultMap['VACT_BankCode'] = ($pay['order_type'] == 'subscribe') ? '' : $recv['body']['detail']['bankCorpCode'];
                $resultMap['CARD_BillKey'] = ($pay['order_type'] == 'subscribe') ? $recv['body']['recurrentId'] : '';
                $resultMap['VACT_Name'] = '';
                $resultMap['VACT_Num'] = '';
                $resultMap['VACT_InputName'] = '';
                $resultMap['VACT_Date'] = '';
                $resultMap['ACCT_BankName'] = '';
                $resultMap['ACCT_BankCode'] = '';
                $resultMap['ACCT_Num'] = '';
                $resultMap['CSHR_ResultCode'] = '';
                $resultMap['CSHR_Type'] = '';
                $resultMap['aid'] = ($pay['order_type'] == 'subscribe') ? $recv['body']['tempReceiptId'] : $recv['body']['detail']['payHistId'];
                $resultMap['applDate'] = ($pay['order_type'] == 'subscribe') ? date('Ymd') : date('Ymd', strtotime($recv['body']['detail']['admissionYmdt']));
                $resultMap['applTime'] = ($pay['order_type'] == 'subscribe') ? date('his') : date('his', strtotime($recv['body']['detail']['admissionYmdt']));

                $naverpay_card_com = array(
                    'C0' => '신한', 'C1' => '비씨', 'C2' => '광주', 'C3' => 'KB국민', 'C4' => 'NH',
                    'C5' => '롯데', 'C6' => '산업', 'C7' => '삼성', 'C8' => '수협', 'C9' => '씨티', 'CA' => '외환',
                    'CB' => '우리', 'CC' => '전북', 'CD' => '제주', 'CF' => '하나-외환', 'CH' => '현대'
                );

                $resultMap['P_FN_NM'] = ($pay['order_type'] == 'subscribe') ? '' : $naverpay_card_com[$recv['body']['detail']['cardCorpCode']];
                $resultMap['CARD_Code'] = ($pay['order_type'] == 'subscribe') ? '' : $recv['body']['detail']['cardCorpCode'];
                $resultMap['CARD_Num'] = '';
                $resultMap['payMethod'] = 'NaverPay';

                $this->payment_m->diagnosis_payment_exec($res['res_result'], $pay, $resultMap);

                $order = $this->payment_m->select_order_info($pay['order_id']);
                $order['order_mem_type'] = $pay['order_mem_type'];
                
                /* sid update */
                if ($pay['order_type'] == 'subscribe') {
                    $sid = array();
                    $sid['order_id'] = $pay['order_id'];
                    $sid['sid'] = $recv['body']['recurrentId'];

                    $this->payment_m->payment_easypay_subscript($sid);
                    
                    if ($pay['start_date'] == date('Y-m-d')) {

                        $resultMap['payDate'] = $resultMap['applDate'];
                        $resultMap['payTime'] = $resultMap['applTime'];

                        $sub = $this->billing_payment_naver($pay, $resultMap);

                        $this->payment_m->insert_billing_order($sub, $pay, $resultMap);
                        $pay['billing_id'] = $sub['order_id'];
                    }
                }



                 $pay['csu_id'] = $order['billing_order_id'];

                if ($pay['status'] == 'PAYMENT') {
                    $this->order_sms($pay);
                }
            } else {
                $res['status'] = 'CANCEL';
                $this->payment_m->payment_response($res);
                $order = $pay;
            } 

            curl_close($curl);
        } else {
                $res['pay_id'] = '';
                $res['status'] = 'CANCEL';
                $res['res_result'] = 'CANCEL';
                $res['res_msg'] = '결제를 취소하였습니다.';
                $this->payment_m->payment_response($res);
                $order = $pay;
            }


        return array($order, $pay, $res);
    }

    private function inicis_payment($pay, $res, $data) {

        $timestamp = date("YmdHis");
        $ip = '118.67.134.168';
        $type = 'Billing';
        $paymethod = 'Card';

        print_r($pay);

        die();

        $params = array(
            'type' => 'Auth',
            'paymethod' => $paymethod,
            'timestamp' => $timestamp,
            'clientIp' => $ip,
            'mid' => MIDB,
            'url' => $this->data['base_url'],
            'moid' => $req['order_id'],
            'goodName' => $pay['product_name'],
            'buyerName' => $pay['mem_username'],
            'buyerEmail' => $pay['mem_email'],
            'buyerTel' => $pay['mem_phone'],
            'price' => $pay['price'],
            'cardNumber' => $pay['cardNumber'],
            'cardExpire' => $pay['cardExpire'],
            'regNo' => $pay['regNo'],
            'cardPw' => $pay['cardPw'],
            'hashData' => hash('sha512', BIL_INI_API_KEY . $type . $paymethod . $timestamp . $ip . MIDB . $pay['order_id'] . $pay['price'] . $pay['cardNumber'])
        );

        $ch = curl_init();                                 //curl 초기화
        curl_setopt($ch, CURLOPT_URL, BILL_URL);               //URL 지정하기
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));       //POST data
        curl_setopt($ch, CURLOPT_POST, 1);              //true시 post 전송 

        $response = curl_exec($ch);

        $recv = json_decode($response, true);

        curl_close($ch);
    }

    private function kakao_payment($pay, $res, $data) {
        if ($pay['status'] == 'OK') {
            header('Location: /my/order/order_list');
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, KAKAO_BASE_URL . '/v1/payment/approve');
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        $aPostData = array();

        $aPostData['cid'] = ($pay['order_type'] == 'subscribe') ? KAKAO_CID_SUBSCRIP : KAKAO_CID_EASYPAY;
        $aPostData['tid'] = $pay['tid'];
        $aPostData['partner_order_id'] = $pay['order_id'];
        $aPostData['partner_user_id'] = $pay['mem_username'];
        $aPostData['pg_token'] = $data['pg_token'];
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
        $header = Array(
            'POST /v1/payment/approve HTTP/1.1',
            'Host: kapi.kakao.com',
            'Authorization: KakaoAK 8c01888f64dffa0104a05ef170b7ba2b',
            'Content-type: application/x-www-form-urlencoded;charset=utf-8'
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $gData = curl_exec($curl);
        $recv = json_decode($gData, true);

        if (!isset($recv['code'])) {

            if ($pay['order_mem_type'] === 'guest') {
                $pay['mem_id'] = $this->payment_m->payment_adduser($pay);
                $val = array();
                $val['mem_id'] = $pay['mem_id'];
                $val['cdg_id'] = $merchant[1];
                $this->diagnosis_m->update_diagnosis($val);
            }

            curl_close($curl);
            $res['res_result'] = $recv['aid'];
            $res['res_msg'] = '성공';
            $res['status'] = 'OK';
            $res['pay_id'] = $pay['pay_id'];
            $this->payment_m->payment_response($res);

            $pay['status'] = 'PAYMENT';

            $resultMap = array();
            $resultMap['goodsName'] = $pay['product_name'];
            $resultMap['tid'] = $pay['tid'];
            $resultMap['payMethod'] = $recv['payment_method_type'];
            $resultMap['payDevice'] = 'PC';
            $resultMap['vactBankName'] = '';
            $resultMap['VACT_BankCode'] = '';
            $resultMap['VACT_Name'] = '';
            $resultMap['VACT_Num'] = '';
            $resultMap['VACT_InputName'] = '';
            $resultMap['CARD_BillKey'] = ($pay['order_type'] == 'subscribe') ? $recv['sid'] : '';
            $resultMap['VACT_Date'] = '';
            $resultMap['ACCT_BankName'] = '';
            $resultMap['ACCT_BankCode'] = '';
            $resultMap['ACCT_Num'] = '';
            $resultMap['CSHR_ResultCode'] = '';
            $resultMap['CSHR_Type'] = '';
            $resultMap['aid'] = $recv['aid'];
            $resultMap['applDate'] = date('Ymd', strtotime($recv['approved_at']));
            $resultMap['applTime'] = date('his', strtotime($recv['approved_at']));

            if (isset($recv['card_info'])) {
                $resultMap['P_FN_NM'] = $recv['card_info']['issuer_corp'];
                $resultMap['CARD_Code'] = $recv['card_info']['issuer_corp_code'];
            } else {
                $resultMap['P_FN_NM'] = '';
                $resultMap['CARD_Code'] = '';
            }
            $resultMap['CARD_Num'] = '';
            $resultMap['payMethod'] = 'KakaoPay';

            $this->payment_m->diagnosis_payment_exec($res['res_result'], $pay, $resultMap);

            $order = $this->payment_m->select_order_info($pay['order_id']);
            $order['order_mem_type'] = $pay['order_mem_type'];

            if ($pay['order_type'] == 'subscribe') {
                /* ToDo */
                $sid = array();
                $sid['order_id'] = $pay['order_id'];
                $sid['sid'] = $recv['sid'];
                $this->payment_m->payment_easypay_subscript($sid);
                
                $pay['csu_id'] = $order['billing_order_id'];

                if ($pay['start_date'] == date('Y-m-d')) {
                    $sub = $this->billing_payment_kakao($pay);
                    
                    $pay['csu_id'] = $sub['csu_id'];

                    $resultMap['payDate'] = date('Ymd', strtotime($recv['approved_at']));
                    $resultMap['payTime'] = date('his', strtotime($recv['approved_at']));

                    $this->payment_m->insert_billing_order($sub, $pay, $resultMap);
                    $pay['billing_id'] = $sub['order_id'];
                }
            }

            if ($pay['status'] == 'PAYMENT') {
                $this->order_sms($pay);
            }
        } else {
            $res['status'] = 'CANCEL';
            $this->payment_m->payment_response($res);
            $order = $pay;
        }

        return array($order, $pay, $res);
    }

    public function payment_request() {
        $req = $this->input->post();

        $result = array();
        if (empty($req)) {
            $result['status'] = 'fail';
            $result['msg'] = '잘못된 요청입니다.';
            $result['target'] = 'recipient_name';
        } else if (empty($req['recipient_name'])) {
            $result['status'] = 'fail';
            $result['msg'] = '수령인 이름을 입력해주세요.';
            $result['target'] = 'recipient_name';
        } else if (empty($req['recipient_phone'])) {
            $result['status'] = 'fail';
            $result['msg'] = '수령인 연락처를 입력해주세요.';
            $result['target'] = 'recipient_phone';
        } else if (empty($req['zipcode'])) {
            $result['status'] = 'fail';
            $result['msg'] = '우편번호를 검색해 주세요.';
            $result['target'] = 'zipcode';
        } else if (empty($req['addr2'])) {
            $result['status'] = 'fail';
            $result['msg'] = '상세주소를 입력해주세요.';
            $result['target'] = 'addr2';
        } else if (empty($req['pay_type'])) {
            $result['status'] = 'fail';
            $result['msg'] = '결제수단을 선택해주세요.';
            $result['target'] = '';
        } else {
            $val = array();

            require_once('application/third_party/inicis/INIStdPayUtil.php');
            $SignatureUtil = new INIStdPayUtil();
            $mid = ($req['order_type'] == 'starter' ? MID0 : MIDB);
            //인증
            $signKey = ($req['order_type'] == 'starter' ? WEB_KEY : BIL_KEY); // 가맹점에 제공된 웹 표준 사인키(가맹점 수정후 고정)
            $orderNumber = 'CD' . ($req['order_type'] == 'subscribe' ? 'B' : 'S') . $SignatureUtil->getTimestamp(); // 가맹점 주문번호(가맹점에서 직접 설정)


            $timestamp = $SignatureUtil->getTimestamp();   // util에 의해서 자동생성

            if ($req['order_type'] == 'subscribe') {
                $price = $req['subscribe_total_price'];        // 상품가격(특수기호 제외, 가맹점에서 직접 설정)
                $req['total_price'] = $price;
            } else {
                if ($req['starter_total_price'] > 0) {
                    $price = $req['starter_total_price'];        // 상품가격(특수기호 제외, 가맹점에서 직접 설정)
                } else {
                    $price = $req['subscribe_total_price'];        // 상품가격(특수기호 제외, 가맹점에서 직접 설정)
                }
                $req['total_price'] = $req['starter_total_price'];
            }
            $mKey = $SignatureUtil->makeHash($signKey, "sha256");

            $params = array(
                "oid" => $orderNumber,
                "price" => $price,
                "timestamp" => $timestamp
            );
            $sign = $SignatureUtil->makeSignature($params, "sha256");

            $val['mid'] = $mid;
            $val['goodname'] = '';
            $val['oid'] = $orderNumber;
            $val['price'] = $price;
            $val['buyername'] = '';
            $val['buyertel'] = '';
            $val['buyeremail'] = '';
            $val['timestamp'] = $timestamp;
            $val['signature'] = $sign;
            $val['mKey'] = $mKey;

            if ($req['order_type'] == 'starter') {
                if ($req['pay_type'] == 'card') {
                    $val['gopaymethod'] = $req['device_type'] == 'PC' ? 'Card' : 'CARD';
                    $val['acceptmethod'] = '';
                    $val['bank_dt'] = '';
                    $val['bank_tm'] = '';
                } else if ($req['pay_type'] == 'direct') {
                    $val['gopaymethod'] = $req['device_type'] == 'PC' ? 'DirectBank' : 'BANK';
                    $val['acceptmethod'] = '';
                    $val['bank_dt'] = '';
                    $val['bank_tm'] = '';
                } else if ($req['pay_type'] == 'bank') {
                    $limit_date = date('Ymd', strtotime('+7 days'));
                    $val['gopaymethod'] = $req['device_type'] == 'PC' ? 'VBank' : 'VBANK';
                    $val['acceptmethod'] = $req['device_type'] == 'PC' ? 'vbank(' . $limit_date . ')' : 'vbank_receipt=Y';
                    $val['bank_dt'] = $limit_date;
                    $val['bank_tm'] = '2359';
                } else if ($req['pay_type'] == 'kakao') {
                    $val['gopaymethod'] = 'kakao';
                    $val['acceptmethod'] = '';
                    $val['bank_dt'] = '';
                    $val['bank_tm'] = '';
                } else if ($req['pay_type'] == 'naver') {
                    $val['gopaymethod'] = 'naver';
                    $val['acceptmethod'] = '';
                    $val['bank_dt'] = '';
                    $val['bank_tm'] = '';
                }
            } else {
                if ($req['pay_type'] == 'card') {
                    $val['gopaymethod'] = 'CARD';
                    $val['acceptmethod'] = 'BILLAUTH(Card):FULLVERIFY';
                } else if ($req['pay_type'] == 'kakao') {
                    $val['gopaymethod'] = 'kakao';
                    $val['acceptmethod'] = '';
                } else if ($req['pay_type'] == 'naver') {
                    $val['gopaymethod'] = 'naver';
                    $val['acceptmethod'] = '';
                }
            }

            $res = $this->payment_m->diagnosis_payment_request($req, $val);
            $result['order_type'] = $req['order_type'];
            if ($res) {
                $val['timestamp2'] = date('YmdHis');
                $val['hashdata'] = hash('sha256', $mid . $val['oid'] . $val['timestamp2'] . INILITE_KEY);
                $result['status'] = 'succ';
                $result['quantity'] = ($req['order_type'] == 'starter') ? 6 : count($req['cit_name']);
                $result['data'] = $val;
            } else {
                $result['status'] = 'fail';
                $result['msg'] = '요청에 실패하였습니다.';
            }
        }
        echo json_encode($result);
    }

    public function payment_result_mo() {
        if (empty($_REQUEST['P_NOTI'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $res = array();
            $merchant = explode(',', $_REQUEST['P_NOTI']);
            $res['pay_id'] = $merchant[0];
            $res['res_result'] = $_REQUEST['P_STATUS'];
            $res['res_msg'] = iconv("EUC-KR", "UTF-8", $_REQUEST['P_RMESG1']);
            $pay = $this->payment_m->select_payment_temp($merchant[0]);
            if (empty($pay['res_result'])) {
                if (strcmp("01", $_REQUEST["P_STATUS"]) == 0) {
                    $res['status'] = 'CANCEL';
                    $this->payment_m->payment_response($res);
                    header('Location: /diagnosis/detail?seq=' . $merchant[1]);
                } else {
                    $res['status'] = 'OK';
                    if ($pay['order_mem_type'] === 'guest') {
                        $pay['mem_id'] = $this->payment_m->payment_adduser($pay);
                        $val = array();
                        $val['mem_id'] = $pay['mem_id'];
                        $val['cdg_id'] = $merchant[1];
                        $this->diagnosis_m->update_diagnosis($val);
                    }

                    if ($_REQUEST["P_STATUS"] === "00") {     // 인증이 P_STATUS===00 일 경우만 승인 요청
                        $id_merchant = substr($_REQUEST["P_TID"], '10', '10');     // P_TID 내 MID 구분
                        $data = array(
                            'P_MID' => $id_merchant, // P_MID
                            'P_TID' => $_REQUEST["P_TID"]                   // P_TID
                        );

                        // curl 통신 시작 

                        $ch = curl_init();                                                             //curl 초기화
                        curl_setopt($ch, CURLOPT_URL, $_REQUEST["P_REQ_URL"]);      //URL 지정하기
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환 
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);     //connection timeout 10초 
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);           //원격 서버의 인증서가 유효한지 검사 안함
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));    //POST 로 $data 를 보냄
                        curl_setopt($ch, CURLOPT_POST, 1);                                         //true시 post 전송 

                        $response = curl_exec($ch);
                        curl_close($ch);

                        // -------------------- 승인결과 수신 -------------------------------------------
                        $response = iconv("EUC-KR", "UTF-8", $response);
                        $map = array();
                        $map['goodsName'] = $pay['product_name'];
                        $map['CARD_BankCode'] = '0';
                        $map['payDevice'] = 'MO';
                        $resultMap = explode('&', $response);

                        $map['CARD_Code'] = '';
                        $map['CARD_Num'] = '';

                        $map['vactBankName'] = '';
                        $map['VACT_BankCode'] = '';
                        $map['VACT_Name'] = '';
                        $map['VACT_Num'] = '';
                        $map['VACT_InputName'] = '';
                        $map['VACT_Date'] = '';

                        $map['ACCT_BankName'] = '';
                        $map['ACCT_BankCode'] = '';
                        $map['ACCT_Num'] = '';
                        $map['CSHR_ResultCode'] = '';
                        $map['CSHR_Type'] = '';

                        foreach ($resultMap as $row) {
                            $data = explode('=', $row);
                            if (count($data) == 2) {
                                switch ($data[0]) {
                                    case 'P_TID' : $map['tid'] = $data[1];
                                        break;
                                    case 'P_TYPE' : $map['payMethod'] = $data[1];
                                        break;
                                    case 'P_FN_NM' :
                                        $map['vactBankName'] = $data[1];
                                        $map['ACCT_BankName'] = $data[1];
                                        $map['P_FN_NM'] = $data[1];
                                        break;
                                    case 'P_CARD_NUM' :$map['CARD_Num'] = $data[1];
                                        break;
                                    case 'P_CARD_PURCHASE_CODE' : $map['CARD_Code'] = $data[1];
                                        break;
                                    case 'P_AUTH_DT' :
                                        $map['applDate'] = substr($data[1], 0, 8);
                                        $map['applTime'] = substr($data[1], 8, 6);
                                        break;
                                    case 'P_VACT_NUM' : $map['VACT_Num'] = $data[1];
                                        break;
                                    case 'P_VACT_DATE' : $map['VACT_Date'] = $data[1];
                                        break;
                                    case 'P_VACT_BANK_CODE' : $map['VACT_BankCode'] = $data[1];
                                        break;
                                    case 'P_VACT_NAME' : $map['VACT_Name'] = $data[1];
                                        break;
                                    case 'P_UNAME' : $map['VACT_InputName'] = $data[1];
                                        break;
                                    case 'P_CSHR_AUTH_NO' : $map['CSHR_ResultCode'] = $data[1];
                                        break;
                                    case 'P_CSHR_TYPE' : $map['CSHR_Type'] = $data[1];
                                        break;
                                    case 'P_STATUS' : $map['resultStatus'] = $data[1];
                                        break;
                                    case 'P_RMESG1' : $map['resultMsg'] = $data[1];
                                        break;
                                }
                            }
                        }

                        if (!empty($map['resultStatus']) && $map['resultStatus'] == '00') {
                            if ($map['payMethod'] == 'VBANK') {
                                $pay['status'] = 'REQUEST';
                                $map['P_FN_NM'] = '';
                                $map['ACCT_BankName'] = '';
                            } else if ($map['payMethod'] == 'BANK') {
                                $pay['status'] = 'PAYMENT';
                                $map['P_FN_NM'] = '';
                                $map['vactBankName'] = '';
                            } else {
                                $pay['status'] = 'PAYMENT';
                                $map['vactBankName'] = '';
                                $map['ACCT_BankName'] = '';
                            }
                            $pay['card_name'] = $map['P_FN_NM'];
                            $this->payment_m->diagnosis_payment_exec($res['res_result'], $pay, $map);

                            if ($pay['status'] == 'PAYMENT') {
                                $tmp = $pay;
                                $tmp['order_type'] = 'item';
                                $this->order_sms($tmp);
                            }
                        } else {
                            $res['status'] = 'ERROR';
                            $res['res_msg'] = $map['resultMsg'];
                        }
                    } else {   //  인증이 P_STATUS===00 아닐경우 아래 인증 실패를 출력함
                        $res['status'] = 'ERROR';
                    }
                    $this->payment_m->payment_response($res);
                }
            } else {
                $res['res_result'] = $pay['res_result'];
                $res['res_msg'] = $pay['res_msg'];
                $res['status'] = $pay['status'];
            }
            if ($res['status'] == 'OK') {
                $order = $this->payment_m->select_order_info($pay['order_id']);
                $order['order_mem_type'] = $pay['order_mem_type'];
            } else {
                $order = $pay;
            }

            $this->data['res'] = $res;
            $this->data['order'] = $order;
            $this->data['cdg_id'] = $merchant[1];
            $this->load->view('header_v', $this->data);
            $this->load->view('diagnosis/order_complete_v');
            $this->load->view('footer_v');
        }
    }

    public function payment_result_mo_bill() {
        if (empty($_REQUEST['merchantreserved'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $res = array();
            $merchant = explode(',', $_REQUEST['merchantreserved']);
            $res['pay_id'] = $merchant[0];
            $res['res_result'] = $_REQUEST['resultcode'];
            $res['res_msg'] = $_REQUEST['resultmsg'];
            $pay = $this->payment_m->select_payment_temp($merchant[0]);
            $subscribe = $this->payment_m->select_subscribe_for_diagnosis($pay['order_id']);

            if (empty($pay['res_result'])) {
                if (strcmp("01", $_REQUEST["resultcode"]) == 0) {
                    if (strpos($_REQUEST['resultmsg'], '[cancel]') !== false) {
                        $res['status'] = 'CANCEL';
                        $res['result_msg'] = $_REQUEST['resultmsg'];
                        $this->payment_m->payment_response($res);
                        header('Location: /diagnosis/detail?seq=' . $merchant[1]);
                    } else {
                        $res['status'] = 'ERROR';
                        $res['result_msg'] = $_REQUEST['resultmsg'];
                        $this->payment_m->payment_response($res);
//						header('Location: /diagnosis/detail?seq=' . $merchant[1]);
                    }
                } else {
                    $res['status'] = 'OK';
                    if ($pay['order_mem_type'] === 'guest') {
                        $pay['mem_id'] = $this->payment_m->payment_adduser($pay);
                        $val = array();
                        $val['mem_id'] = $pay['mem_id'];
                        $val['cdg_id'] = $merchant[1];
                        $this->diagnosis_m->update_diagnosis($val);
                    }

                    if ($_REQUEST["resultcode"] === "00") {     // 인증이 P_STATUS===00 일 경우만 승인 요청
                        $map['tid'] = $_REQUEST['tid'];
                        $map['applDate'] = $_REQUEST['pgauthdate'];
                        $map['applTime'] = $_REQUEST['pgauthtime'];
                        $map['payMethod'] = 'CARD';
                        $map['goodsName'] = $pay['product_name'];
                        $map['payDevice'] = 'MO';

                        $map['CARD_Code'] = $_REQUEST['cardcd'];
                        $map['CARD_Num'] = $_REQUEST['cardno'];
                        $map['CARD_BillKey'] = $_REQUEST['billkey'];

                        $map['vactBankName'] = '';
                        $map['VACT_BankCode'] = '';
                        $map['VACT_Name'] = '';
                        $map['VACT_Num'] = '';
                        $map['VACT_InputName'] = '';
                        $map['VACT_Date'] = '';

                        $map['ACCT_BankName'] = '';
                        $map['ACCT_BankCode'] = '';
                        $map['ACCT_Num'] = '';
                        $map['CSHR_ResultCode'] = '';
                        $map['CSHR_Type'] = '';

                        $map['P_FN_NM'] = '';
                        $pay['status'] = 'PAYMENT';
                        $this->payment_m->diagnosis_payment_exec($res['res_result'], $pay, $map);

                        if ($pay['order_type'] == 'subscribe' || $pay['order_type'] == 'starter') {
                            if ($pay['order_type'] == 'subscirbe') {
                                $pay['csu_id'] = $subscribe['csu_id'];
                            }
                            $this->order_sms($pay);
                        }
                        if ($pay['order_type'] === 'with') {
                            require_once('application/third_party/inicis/INIStdPayUtil.php');
                            $util = new INIStdPayUtil();
                            $billing = $this->payment_m->select_subscribe_info($pay['order_id'])->row_array();
                            $pay['csu_id'] = $billing['csu_id'];

                            if ($pay['total_price'] > 0) {
                                $sub['order_id'] = 'CDA' . $util->getTimestamp();
                                $sub['mem_username'] = $pay['mem_username'];
                                $sub['mem_phone'] = $pay['mem_phone'];
                                $sub['mem_email'] = $pay['mem_email'];
                                $sub['price'] = $pay['total_price'];
                                $sub['product_name'] = $pay['product_name'];
                                $sub['billKey'] = $billing['billing_key'];
                                $sub['csu_id'] = $billing['csu_id'];

                                if ($this->billing_payment($sub, $result)) {
                                    $this->payment_m->insert_diagnosis_billing_order($sub, $pay, $result);
                                    $pay['billing_id'] = $sub['order_id'];
                                }
                            } else {
                                $sub['order_id'] = 'CDF' . $util->getTimestamp();
                                $result = array();
                                $result['goodsName'] = $pay['product_name'];
                                $result['tid'] = '';
                                $result['payMethod'] = '';
                                $result['CARD_Code'] = '';
                                $result['CARD_BankCode'] = '';
                                $result['P_FN_NM'] = '';
                                $result['CARD_Num'] = '';
                                $result['payDevice'] = '';
                                $result['payDate'] = '';
                                $result['payTime'] = '';
                                $this->payment_m->insert_diagnosis_billing_order($sub, $pay, $result);
                                $pay['billing_id'] = $sub['order_id'];
                            }
                            $tmp = $pay;
                            $tmp['order_type'] = 'item';
                            $this->order_sms($tmp);

                            $tmp['order_type'] = 'subscribe';
                            $tmp['list'] = $this->subscribe_m->subscribe_detail_list($billing['csu_id'])->result_array();
                            $tmp['csu_id'] = $subscribe['csu_id'];
                            $this->order_sms($tmp);
                        }
                    } else {   //  인증이 P_STATUS===00 아닐경우 아래 인증 실패를 출력함
                        $res['status'] = 'ERROR';
                    }
                    $this->payment_m->payment_response($res);
                }
            } else {
                $res['res_result'] = $pay['res_result'];
                $res['res_msg'] = $pay['res_msg'];
                $res['status'] = $pay['status'];
            }
            if ($res['status'] == 'OK') {
                $order = $this->payment_m->select_order_info($pay['order_id']);
                $order['order_mem_type'] = $pay['order_mem_type'];
            } else {
                $order = $pay;
            }

            $this->data['res'] = $res;
            $this->data['order'] = $order;
            $this->data['cdg_id'] = $merchant[1];
            $this->data['subscribe'] = $subscribe;
            $this->load->view('header_v', $this->data);
            $this->load->view('diagnosis/order_complete_v');
            $this->load->view('footer_v');
        }
    }

    public function payment_result() {
        if (empty($_REQUEST['merchantData'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {


            require_once('application/third_party/inicis/INIStdPayUtil.php');
            require_once('application/third_party/inicis/HttpClient.php');

            $util = new INIStdPayUtil();

            $merchant = explode(',', $_REQUEST['merchantData']);
            $pay = $this->payment_m->select_payment_temp($merchant[0]);
            $subscribe = $this->payment_m->select_subscribe_for_diagnosis($pay['order_id']);

            $res = array();

            if ($pay['payment_type'] == 'kakao') {
                list($order, $pay, $res) = $this->kakao_payment($pay, $res, $_REQUEST, $merchant);
            } else if ($pay['payment_type'] == 'naver') {
                list($order, $pay, $res) = $this->naver_payment($pay, $res, $_REQUEST, $merchant);
            } else {
                if (empty($pay['res_result'])) {
                    try {
                        $res['pay_id'] = $_REQUEST['merchantData'];
                        $res['res_result'] = $_REQUEST['resultCode'];
                        $res['res_msg'] = $_REQUEST['resultMsg'];
                        if (strcmp("V801", $_REQUEST["resultCode"]) == 0) {
                            $res['status'] = 'CANCEL';
                            $this->payment_m->payment_response($res);
                            header('Location: /diagnosis/detail?seq=' . $merchant[1]);
                        } else {
                            if ($pay['order_mem_type'] === 'guest') {
                                $pay['mem_id'] = $this->payment_m->payment_adduser($pay);
                                $val = array();
                                $val['mem_id'] = $pay['mem_id'];
                                $val['cdg_id'] = $merchant[1];
                                $this->diagnosis_m->update_diagnosis($val);
                            }
                            if (strcmp("0000", $_REQUEST["resultCode"]) == 0) {
                                $mid = $_REQUEST["mid"];          // 가맹점 ID 수신 받은 데이터로 설정
                                $signKey = "SU5JTElURV9UUklQTEVERVNfS0VZU1RS";   // 가맹점에 제공된 키(이니라이트키) (가맹점 수정후 고정) !!!절대!! 전문 데이터로 설정금지
                                $timestamp = $util->getTimestamp();        // util에 의해서 자동생성
                                $charset = "UTF-8";               // 리턴형식[UTF-8,EUC-KR](가맹점 수정후 고정)
                                $format = "JSON";               // 리턴형식[XML,JSON,NVP](가맹점 수정후 고정)

                                $authToken = $_REQUEST["authToken"];       // 취소 요청 tid에 따라서 유동적(가맹점 수정후 고정)
                                $authUrl = $_REQUEST["authUrl"];         // 승인요청 API url(수신 받은 값으로 설정, 임의 세팅 금지)
                                $netCancel = $_REQUEST["netCancelUrl"];       // 망취소 API url(수신 받은f값으로 설정, 임의 세팅 금지)

                                $mKey = hash("sha256", $signKey);     // 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
                                $signParam["authToken"] = $authToken;   // 필수
                                $signParam["timestamp"] = $timestamp;   // 필수
                                // signature 데이터 생성 (모듈에서 자동으로 signParam을 알파벳 순으로 정렬후 NVP 방식으로 나열해 hash)
                                $signature = $util->makeSignature($signParam);

                                $authMap["mid"] = $mid;     // 필수
                                $authMap["authToken"] = $authToken;  // 필수
                                $authMap["signature"] = $signature;  // 필수
                                $authMap["timestamp"] = $timestamp;  // 필수
                                $authMap["charset"] = $charset;   // default=UTF-8
                                $authMap["format"] = $format;   // default=XML

                                try {

                                    $httpUtil = new HttpClient();
                                    $authResultString = "";
                                    if ($httpUtil->processHTTP($authUrl, $authMap)) {
                                        $authResultString = $httpUtil->body;
                                    } else {
                                        echo "Http Connect Error\n";
                                        echo $httpUtil->errormsg;

                                        throw new Exception("Http Connect Error");
                                    }
                                    $resultMap = json_decode($authResultString, true);

                                    /*                                     * ***********************  결제보안 추가 2016-05-18 START *************************** */
                                    $secureMap["mid"] = $mid;       //mid
                                    $secureMap["tstamp"] = $timestamp;     //timestemp
                                    $secureMap["MOID"] = $resultMap["MOID"];   //MOID
                                    $secureMap["TotPrice"] = $resultMap["TotPrice"];  //TotPrice
                                    // signature 데이터 생성 
                                    $secureSignature = $util->makeSignatureAuth($secureMap);
                                    /*                                     * ***********************  결제보안 추가 2016-05-18 END *************************** */

                                    if ((strcmp("0000", $resultMap["resultCode"]) == 0) && (strcmp($secureSignature, $resultMap["authSignature"]) == 0)) { //결제보안 추가 2016-05-18
                                        $res['status'] = 'OK';
                                        $this->payment_m->payment_response($res);
                                    } else {
                                        $res['res_result'] = @(in_array($resultMap["resultCode"], $resultMap) ? $resultMap["resultCode"] : "null");
                                        //결제보안키가 다른 경우.
                                        $res['status'] = 'FAIL';
                                        if (!empty($resultMap["authSignature"]) && strcmp($secureSignature, $resultMap["authSignature"]) != 0) {
                                            $res['res_msg'] = '* 데이터 위변조 체크 실패';
                                            //망취소
                                            if (strcmp("0000", $resultMap["resultCode"]) == 0) {
                                                throw new Exception("데이터 위변조 체크 실패");
                                            }
                                        } else {
                                            $res['res_msg'] = @(in_array($resultMap["resultMsg"], $resultMap) ? $resultMap["resultMsg"] : "null");
                                        }
                                        $this->payment_m->payment_response($res);
                                    }

                                    if ($res['status'] == 'OK') {
                                        $pay['status'] = 'PAYMENT';
                                        if (isset($resultMap["payMethod"]) && strcmp("VBank", $resultMap["payMethod"]) == 0) { //가상계좌
                                            $resultMap['P_FN_NM'] = '';
                                            $resultMap['CARD_Code'] = '';
                                            $resultMap['CARD_Num'] = '';

                                            $resultMap['ACCT_BankName'] = '';
                                            $resultMap['ACCT_BankCode'] = '';
                                            $resultMap['ACCT_Num'] = '';
                                            $resultMap['CSHR_ResultCode'] = '';
                                            $resultMap['CSHR_Type'] = '';
                                            $pay['status'] = 'REQUEST';
                                        } else if (isset($resultMap["payMethod"]) && strcmp("DirectBank", $resultMap["payMethod"]) == 0) { //실시간계좌이체
                                            $resultMap['P_FN_NM'] = '';
                                            $resultMap['CARD_Code'] = '';
                                            $resultMap['CARD_Num'] = '';

                                            $resultMap['vactBankName'] = '';
                                            $resultMap['VACT_BankCode'] = '';
                                            $resultMap['VACT_Name'] = '';
                                            $resultMap['VACT_Num'] = '';
                                            $resultMap['VACT_InputName'] = '';
                                            $resultMap['VACT_Date'] = '';
                                        } else if (isset($resultMap["payMethod"]) && strcmp("Auth", $resultMap["payMethod"]) == 0) {//빌링결제
                                            $resultMap['P_FN_NM'] = '';

                                            $resultMap['vactBankName'] = '';
                                            $resultMap['VACT_BankCode'] = '';
                                            $resultMap['VACT_Name'] = '';
                                            $resultMap['VACT_Num'] = '';
                                            $resultMap['VACT_InputName'] = '';
                                            $resultMap['VACT_Date'] = '';

                                            $resultMap['ACCT_BankName'] = '';
                                            $resultMap['ACCT_BankCode'] = '';
                                            $resultMap['ACCT_Num'] = '';
                                            $resultMap['CSHR_ResultCode'] = '';
                                            $resultMap['CSHR_Type'] = '';
                                        } else if (isset($resultMap["payMethod"]) && strcmp("VCard", $resultMap["payMethod"]) == 0) {//카드결제
                                            $resultMap['vactBankName'] = '';
                                            $resultMap['VACT_BankCode'] = '';
                                            $resultMap['VACT_Name'] = '';
                                            $resultMap['VACT_Num'] = '';
                                            $resultMap['VACT_InputName'] = '';
                                            $resultMap['VACT_Date'] = '';

                                            $resultMap['ACCT_BankName'] = '';
                                            $resultMap['ACCT_BankCode'] = '';
                                            $resultMap['ACCT_Num'] = '';
                                            $resultMap['CSHR_ResultCode'] = '';
                                            $resultMap['CSHR_Type'] = '';
                                        } else if (isset($resultMap["payMethod"]) && strcmp("Card", $resultMap["payMethod"]) == 0) {//카드결제
                                            $resultMap['vactBankName'] = '';
                                            $resultMap['VACT_BankCode'] = '';
                                            $resultMap['VACT_Name'] = '';
                                            $resultMap['VACT_Num'] = '';
                                            $resultMap['VACT_InputName'] = '';
                                            $resultMap['VACT_Date'] = '';

                                            $resultMap['ACCT_BankName'] = '';
                                            $resultMap['ACCT_BankCode'] = '';
                                            $resultMap['ACCT_Num'] = '';
                                            $resultMap['CSHR_ResultCode'] = '';
                                            $resultMap['CSHR_Type'] = '';
                                        }

                                        // 수신결과를 파싱후 resultCode가 "0000"이면 승인성공 이외 실패
                                        // 가맹점에서 스스로 파싱후 내부 DB 처리 후 화면에 결과 표시
                                        // payViewType을 popup으로 해서 결제를 하셨을 경우
                                        // 내부처리후 스크립트를 이용해 opener의 화면 전환처리를 하세요
                                        //throw new Exception("강제 Exception");
                                        $pay['card_name'] = $resultMap['P_FN_NM'];
                                        $this->payment_m->diagnosis_payment_exec($res['res_result'], $pay, $resultMap);

                                        if ($pay['order_type'] == 'subscribe' || $pay['order_type'] == 'starter') {
                                            $this->order_sms($pay);
                                        }
                                        if ($pay['order_type'] === 'with') {
                                            $billing = $this->payment_m->select_subscribe_info($pay['order_id'])->row_array();
                                            $pay['csu_id'] = $billing['csu_id'];

                                            if ($pay['total_price'] > 0) {
                                                $sub['order_id'] = 'CDA' . $util->getTimestamp();
                                                $sub['mem_username'] = $pay['mem_username'];
                                                $sub['mem_phone'] = $pay['mem_phone'];
                                                $sub['mem_email'] = $pay['mem_email'];
                                                $sub['price'] = $pay['total_price'];
                                                $sub['product_name'] = $pay['product_name'];
                                                $sub['billKey'] = $billing['billing_key'];
                                                $sub['csu_id'] = $billing['csu_id'];

                                                if ($this->billing_payment($sub, $result)) {
                                                    $this->payment_m->insert_diagnosis_billing_order($sub, $pay, $result);
                                                    $pay['billing_id'] = $sub['order_id'];
                                                }
                                            } else {
                                                $sub['order_id'] = 'CDF' . $util->getTimestamp();
                                                $result = array();
                                                $result['goodsName'] = $pay['product_name'];
                                                $result['tid'] = '';
                                                $result['payMethod'] = '';
                                                $result['CARD_Code'] = '';
                                                $result['CARD_BankCode'] = '';
                                                $result['P_FN_NM'] = '';
                                                $result['CARD_Num'] = '';
                                                $result['payDevice'] = '';
                                                $result['payDate'] = '';
                                                $result['payTime'] = '';
                                                $this->payment_m->insert_diagnosis_billing_order($sub, $pay, $result);
                                                $pay['billing_id'] = $sub['order_id'];
                                            }

                                            $tmp = $pay;
                                            $tmp['order_type'] = 'item';
                                            $this->order_sms($tmp);

                                            $tmp['order_type'] = 'subscribe';
                                            $tmp['list'] = $this->subscribe_m->subscribe_detail_list($billing['csu_id'])->result_array();
                                            $this->order_sms($tmp);
                                        }
                                    }
                                } catch (Exception $e) {
                                    echo $e->getCode() . '<br><br>';
                                    echo $e->getMessage();

                                    //#####################
                                    // 망취소 API
                                    //#####################

                                    $netcancelResultString = ""; // 망취소 요청 API url(고정, 임의 세팅 금지)

                                    if ($httpUtil->processHTTP($netCancel, $authMap)) {
                                        $netcancelResultString = $httpUtil->body;
                                    } else {
                                        $res['res_result'] = 'Http Connect Error';
                                        $res['res_msg'] = $httpUtil->errormsg;
                                        $res['status'] = 'ERROR';
                                        $this->payment_m->payment_response($res);

                                        throw new Exception("Http Connect Error");
                                    }
                                }
                            } else {
                                $res['status'] = 'ERROR';
                                $this->payment_m->payment_response($res);
                            }
                        }
                    } catch (Exception $e) {
                        echo $e->getCode() . '<br><br>';
                        echo $e->getMessage();
                    }
                } else {
                    $res['res_result'] = $pay['res_result'];
                    $res['res_msg'] = $pay['res_msg'];
                    $res['status'] = $pay['status'];
                }

                if ($res['status'] == 'OK') {
                    $order = $this->payment_m->select_order_info($pay['order_id']);
                    $order['order_mem_type'] = $pay['order_mem_type'];
                } else {
                    $order = $pay;
                }
            }
            $this->data['res'] = $res;
            $this->data['order'] = $order;
            $this->data['cdg_id'] = $merchant[1];
            $this->data['subscribe'] = $subscribe;
            $this->load->view('header_v', $this->data);
            $this->load->view('diagnosis/order_complete_v');
            $this->load->view('footer_v');
        }
    }
    
        private function billing_payment_kakao($pay) {
        $util = new INIStdPayUtil();

        $billing = $this->payment_m->select_subscribe_info($pay['order_id'])->row_array();

        $sub = array();
        $sub['csu_id'] = $billing['csu_id'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, KAKAO_BASE_URL . '/v1/payment/subscription');
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        $aPostData = array();
        $aPostData['cid'] = KAKAO_CID_SUBSCRIP;
        $aPostData['sid'] = $billing['billing_key'];
        $aPostData['partner_order_id'] = $pay['order_id'];
        $aPostData['partner_user_id'] = $pay['mem_username'];
        $aPostData['item_name'] = $pay['product_name'];
        $aPostData['quantity'] = $pay['total_qty'];
        $aPostData['total_amount'] = $pay['total_price'];
        $aPostData['tax_free_amount'] = 0;
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
        $header = Array(
            'POST /v1/payment/subscription HTTP/1.1',
            'Host: kapi.kakao.com',
            'Authorization: KakaoAK 8c01888f64dffa0104a05ef170b7ba2b',
            'Content-type: application/x-www-form-urlencoded;charset=utf-8'
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $gData = curl_exec($curl);
        $recv = json_decode($gData, true);

        $sub['card_no'] = '';
        if (isset($recv['card_info'])) {
            $sub['card_name'] = $recv['card_info']['kakaopay_purchase_corp'];
            $sub['card_code'] = $recv['card_info']['kakaopay_purchase_corp_code'];
        } else {
            $sub['card_name'] = '';
            $sub['card_code'] = '';
        }

        $sub['billKey'] = $billing['billing_key'];

        $sub['order_id'] = $recv['tid'];

        if (isset($recv['code'])) {
            $sub['result_code'] = $recv['code'];
            $sub['result_msg'] = $recv['msg'];
        } else {

            $sub['result_msg'] = 'KakaoPay 정기결제';
            $sub['result_code'] = '00';
        }

        $this->payment_m->insert_subscribe_hitory($sub);

        return $sub;
    }

    private function billing_payment_naver($pay, &$map) {

        $util = new INIStdPayUtil();

        $billing = $this->payment_m->select_subscribe_info($pay['order_id'])->row_array();

        $header = Array(
            'X-Naver-Client-Id:f07MEDKDQ478StuME1ea',
            'X-Naver-Client-Secret:HkH1m3sQQg',
            'X-NaverPay-Chain-Id:dkVsWi9VSEF4N0x'
        );

        $curl = curl_init();
        $url = 'https://dev.apis.naver.com/naverpay-partner/naverpay/payments/recurrent/pay/v3/reserve';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);

        $aPostData = array();
        $aPostData['recurrentId'] = $billing['billing_key'];
        $aPostData['totalPayAmount'] = $pay['total_price'];
        $aPostData['taxScopeAmount'] = $pay['total_price'];
        $aPostData['taxExScopeAmount'] = 0;
        $aPostData['productName'] = $pay['product_name'];
        $aPostData['merchantPayId'] = $pay['order_id'];
        $aPostData['merchantUserId'] = $pay['mem_username'];

        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $gData = curl_exec($curl);
        $reserve = json_decode($gData, true);

        if ($reserve['code'] == "Success") {

            $sub = array();
            $sub['csu_id'] = $billing['csu_id'];

            $curl = curl_init();
            $url = 'https://dev.apis.naver.com/naverpay-partner/naverpay/payments/recurrent/pay/v3/approval';
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);

            $aPostData = array();
            $aPostData['recurrentId'] = $reserve['body']['recurrentId'];
            $aPostData['paymentId'] = $reserve['body']['paymentId'];
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            $gData = curl_exec($curl);
            $recv = json_decode($gData, true);
            $recvbody = $recv['body'];
            if ($recv['code'] == "Success") {
                $sub['order_id'] = $recvbody['paymentId'];
                $map['tid'] = $recvbody['paymentId'];
                $sub['result_code'] = $recv['code'];
                $sub['result_msg'] = 'NaverPay 정기결제';
                $sub['billKey'] = $recvbody['recurrentId'];

                $naverpay_card_com = array(
                    'C0' => '신한', 'C1' => '비씨', 'C2' => '광주', 'C3' => 'KB국민', 'C4' => 'NH',
                    'C5' => '롯데', 'C6' => '산업', 'C7' => '삼성', 'C8' => '수협', 'C9' => '씨티', 'CA' => '외환',
                    'CB' => '우리', 'CC' => '전북', 'CD' => '제주', 'CF' => '하나-외환', 'CH' => '현대'
                );

                $sub['card_no'] = (isset($recvbody['detail']['cardNo'])) ? $recvbody['detail']['cardNo'] : '';
                $sub['card_code'] = (isset($recvbody['detail']['cardCorpCode'])) ? $recvbody['detail']['cardCorpCode'] : '';
                $sub['card_name'] = (isset($recvbody['detail']['cardCorpCode'])) ? $naverpay_card_com[$sub['card_code']] : '';
            } else {
                $sub['result_code'] = $reserve['code'];
                $sub['result_msg'] = $reserve['message'];
                $sub['billKey'] = $billing['billing_key'];
                $sub['card_name'] = '';
                $sub['card_code'] = '';
            }
            $this->payment_m->insert_subscribe_hitory($sub);
        } else {

            $sub = array(
                'code' => $reserve['code'],
                'csu_id' => $billing['csu_id'],
                'order_id' => 'CDA' . $util->getTimestamp(),
                'result_code' => $reserve['code'],
                'result_msg' => '결제실패',
                'billKey' => $billing['billing_key'],
            );
            $sub['card_name'] = '';
            $sub['card_code'] = '';
            $this->payment_m->insert_subscribe_hitory($sub);
        }

        return $sub;
    }
    
    private function billing_payment_inicis($req, &$res) {
        $timestamp = date("YmdHis");
        $ip = '118.67.134.168';
        $type = 'Billing';
        $paymethod = 'Card';
        
        $aPostData = array(
            'type' => $type,
            'paymethod' => $paymethod,
            'timestamp' => $timestamp,
            'clientIp' => $ip,
            'mid' => MIDB,
            'url' => $this->data['base_url'],
            'moid' => $req['order_id'],
            'goodName' => $req['product_name'],
            'buyerName' => $req['mem_username'],
            'buyerEmail' => $req['mem_email'],
            'buyerTel' => $req['mem_phone'],
            'price' => $req['price'],
            'billKey' => $req['billKey'],
            'authentification' => '00',
            'regNo' => $req['regNo'],
            'cardPw' => $req['cardPw'],
            'hashData' => hash('sha512', BIL_INI_API_KEY . $type . $paymethod . $timestamp . $ip . MIDB . $req['order_id'] . $req['price'] . $req['billKey'])
        );

        $curl = curl_init();
        $url = 'https://iniapi.inicis.com/api/v1/billing';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
        $header = Array(
            'POST /v1/payment/approve HTTP/1.1',
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8'
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $gData = curl_exec($curl);
        $recv = json_decode($gData, true);
        
        if (!isset($recv['resultCode'])) {
            $req['result_code'] = 'N/A';
            $req['result_msg'] = 'N/A';
            $req['card_no'] = '';
            $req['card_name'] = '';
            $req['card_code'] = '';
            $this->payment_m->insert_subscribe_hitory($req);
            return false;
        } else if ($recv['resultCode'] !== '00') {
            $req['result_code'] = $recv['resultCode'];
            $req['result_msg'] = $result['resultMsg'];
            $req['card_no'] = '';
            $req['card_name'] = '';
            $req['card_code'] = '';
            $this->payment_m->insert_subscribe_hitory($req);
            return false;
        } else {
            $req['result_code'] = $recv['resultCode'];
            $req['result_msg'] = $recv['resultMsg'];
            $req['card_no'] = $req['cardNumber'];
            $req['card_name'] = '';
            $req['card_code'] = $recv['cardCode'];
            $this->payment_m->insert_subscribe_hitory($req);
            $res['goodsName'] = $req['product_name'];
            $res['tid'] = $recv['tid'];
            $res['payMethod'] = $paymethod;
            $res['CARD_Code'] = $recv['cardCode'];
            $res['CARD_BankCode'] = '0';
            $res['P_FN_NM'] = '';
            $res['CARD_Num'] = $req['cardNumber'];
            $res['payDevice'] = '';
            $res['payDate'] = $recv['payDate'];
            $res['payTime'] = $recv['payTime'];
            return true;
        }
    }
    
    private function billing_payment($req, &$res) {
        $timestamp = date("YmdHis");
        $ip = '118.67.134.168';
        $type = 'Billing';
        $paymethod = 'Card';

        $params = array(
            'type' => $type,
            'paymethod' => $paymethod,
            'timestamp' => $timestamp,
            'clientIp' => $ip,
            'mid' => MIDB,
            'url' => $this->data['base_url'],
            'moid' => $req['order_id'],
            'goodName' => $req['product_name'],
            'buyerName' => $req['mem_username'],
            'buyerEmail' => $req['mem_email'],
            'buyerTel' => $req['mem_phone'],
            'price' => $req['price'],
            'billKey' => $req['billKey'],
            'authentification' => '00',
            'hashData' => hash('sha512', BIL_INI_API_KEY . $type . $paymethod . $timestamp . $ip . MIDB . $req['order_id'] . $req['price'] . $req['billKey'])
        );

        $ch = curl_init();                                 //curl 초기화
        curl_setopt($ch, CURLOPT_URL, BILL_URL);               //URL 지정하기
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
            $req['result_code'] = 'N/A';
            $req['result_msg'] = 'N/A';
            $req['card_no'] = '';
            $req['card_name'] = '';
            $req['card_code'] = '';
            $this->payment_m->insert_subscribe_hitory($req);
            return false;
        } else if ($result->resultCode !== '00') {
            $req['result_code'] = $result->resultCode;
            $req['result_msg'] = $result->resultMsg;
            $req['card_no'] = '';
            $req['card_name'] = '';
            $req['card_code'] = '';
            $this->payment_m->insert_subscribe_hitory($req);
            return false;
        } else {
            $res['goodsName'] = $req['product_name'];
            $res['tid'] = $result->tid;
            $res['payMethod'] = $paymethod;
            $res['CARD_Code'] = $result->cardCode;
            $res['CARD_BankCode'] = '0';
            $res['P_FN_NM'] = '';
            $res['CARD_Num'] = $result->cardNumber;
            $res['payDevice'] = '';
            $res['payDate'] = $result->payDate;
            $res['payTime'] = $result->payTime;
            return true;
        }
    }

    private function order_sms($req) {
        $product = '';
        foreach ($req['list'] as $row) {
            $product .= $row['cit_name'] . (!empty($row['cde_title']) ? '(' . $row['cde_title'] . ')' : '') . '/' . $row['qty'] . '
';
        }
        $msg = '';
        $id = '';
        $url = '';

        $base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $base_url .= "://" . $_SERVER['HTTP_HOST'];

        if ($req['order_type'] == 'subscribe') {
            $id = '50078';
            $url = 'https://www.cleand.kr/my/subscribe/detail/0?seq=' . $req['csu_id'];
            $msg = '[[클린디]]
' . $req['mem_username'] . '님, 맞춤덴탈케어 클린디 정기구독이 신청되었습니다.

- 수취인 : ' . $req['recipient_name'] . '
- 배송지 : ' . $req['recipient_zip'] . ' ' . $req['recipient_addr1'] . ' ' . $req['recipient_addr2'] . '
- 클린디 제품 : 
' . $product . '
- 주문번호 : ' . $req['order_id'] . '

이번 달부터 건강하게 배송하겠습니다.

※ 평일 기준으로 2~3일 이내 도착합니다. (택배사 사정에 따라 다소 달라질 수 있습니다.)';

            $email = $this->email_m->email_detail('subscribe')->row_array();

            if (!empty($email)) {
                $html = $email['mail_content'];
                $html = str_replace('[=BASE_URL]', $base_url, $html);
                $html = str_replace('[=NAME]', $req['mem_username'], $html);
                $html = str_replace('[=PRODUCT]', $product, $html);
                $html = str_replace('[=RECIPIENT]', $req['recipient_name'], $html);
                $html = str_replace('[=ADDR]', $req['recipient_zip'] . ' ' . $req['recipient_addr1'] . ' ' . $req['recipient_addr2'], $html);
                $html = str_replace('[=ORDERID]', $req['order_id'], $html);
                $this->email_m->email_insert($req['mem_email'], $email['mail_title'], $html);
            }
        } else {
            $id = '50067';
            $url = 'https://www.cleand.kr/my/order/order_list';
            $msg = '[[클린디]]
안녕하세요. ' . $req['mem_username'] . '님
클린디 주문 결제가 완료되었습니다.

주문에 감사드리며, 상품 준비 후 배송이 시작되면 다시 안내드리겠습니다.

- 주문일자 : ' . date('Y-m-d') . '
- 주문번호 : ' . $req['order_id'] . '
- 상품명/수량 : 
' . $product . '

구강 건강을 위해 클린디로 올바르게 양치해주세요';

            $email = $this->email_m->email_detail('payment')->row_array();

            if (!empty($email)) {
                $html = $email['mail_content'];
                $html = str_replace('[=BASE_URL]', $base_url, $html);
                $html = str_replace('[=NAME]', $req['mem_username'], $html);
                $html = str_replace('[=PRODUCT]', $product, $html);
                $html = str_replace('[=DATE]', date('Y-m-d'), $html);
                $html = str_replace('[=ORDERID]', $req['order_id'], $html);
                $this->email_m->email_insert($req['mem_email'], $email['mail_title'], $html);
            }
        }

        list($microtime, $timestamp) = explode(' ', microtime());
        $time = $timestamp . substr($microtime, 2, 3);

        $messages = array();

        $message = array();
        $message['no'] = '0';
        $message['tel_num'] = $req['mem_phone'];
        $message['custom_key'] = $time . '000';
        $message['msg_content'] = $msg;
        $message['sms_content'] = $msg;
        $message['use_sms'] = '1';
        $message['btn_url'] = array();
        $button = array();
        $button['url_pc'] = $url;
        $button['url_mobile'] = $url;
        $message['btn_url'][] = $button;
        $messages[] = $message;

        $this->sendBizMessage($id, $messages);
    }

}
