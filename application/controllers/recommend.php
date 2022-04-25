<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recommend extends CD_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('common');
        $this->load->model('common_m');
        $this->load->model('dentist_m');
        $this->load->model('recommend_m');
        $this->load->model('email_m');
        $this->load->model('member_m');
        $this->load->model('payment_m');
        $this->load->model('subscribe_m');
    }

    public function index() {
        $req = $this->input->get();
        $req = $req['req'];
        
        if (empty($req)) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {

            if (isset($_SESSION['mem_userid'])) {
                $member_info = $this->member_m->member_info_for_dentist($_SESSION['user']['mem_id'])->row_array();
                if ($member_info['is_recommend'] == 'y') {
                    header('Location: /');
                    die();
                }
            } 

            $this->data['dentist_info'] = $this->dentist_m->dentist_info($req)->row_array();

            $this->load->view('header_v', $this->data);
            $this->load->view('recommend/index');
            $this->load->view('footer_v');
        }
    }

    public function recommend_subscribe() {
        
        if (empty($_REQUEST['cmall_item'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
        

        $cde_title = $_POST['cmall_item_deatil'] . " 4줄,그레이";

        $item1 = $this->db->query("select i.* , 
		(select cde_id from cmall_item_detail where cit_id = i.cit_id and cde_title ='" . $cde_title . "') cde_id
		from cmall_item as i where i.cit_id = '" . $_POST['cmall_item'] . "' ")->row_array();

        $item2 = $this->db->query("select i.* 
		from cmall_item as i where i.cit_id = '" . $_POST['cmall_item2'] . "' ")->row_array();

        $this->data['item1'] = $item1;
        $this->data['item2'] = $item2;

        //$this->template->assign('item1_result',$item1_result);
        $this->load->view('header_v', $this->data);
        $this->load->view('recommend/recommend_subscribe');
        $this->load->view('footer_v');
        }
    }

    public function recommend_calendar() {
        if (empty($_REQUEST['cmall_item'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
        
            $hour = date('H');
            $start_date = date('Y-m-d');
            if ($hour >= 15) {
                $start_date = date('Y-m-d', strtotime('+1 days'));
            }

            $this->data['start_date'] = $start_date;
            $this->load->view('header_v', $this->data);
            $this->load->view('recommend/recommend_calendar');
            $this->load->view('footer_v');
        }
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
        } else if($req['pay_type'] == 'naver' && $req['total_price'] <= 100){
            $result['status'] = 'fail';
            $result['msg'] = '100원 미만의 금액은 네이버 페이 이용이 불가능합니다';
            $result['target'] = '';
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

            if($req['pay_type'] == 'card'){
                if (empty($req['card_number'])) {
                    $result['status'] = 'fail';
                    $result['msg'] = '카드번호를 입력해주세요.';
                    $result['target'] = 'card_number';
                } else if (empty($req['card_birth'])) {
                    $result['status'] = 'fail';
                    $result['msg'] = '생년월일 앞6자리를 입력해주세요.';
                    $result['target'] = 'card_birth';
                } else if (empty($req['card_vaild'])) {
                    $result['status'] = 'fail';
                    $result['msg'] = '카드번호를 입력해주세요.';
                    $result['target'] = 'card_vaild';
                } else if (empty($req['card_pwd'])) {
                    $result['status'] = 'fail';
                    $result['msg'] = '카드비밀번호 앞자리를 입력해주세요.';
                    $result['target'] = 'card_pwd';
                }

                
                if(isset($result['status']) && $result['status'] == 'fail'){
                    echo json_encode($result);
                    return;
                }
            }
            
            $val = array();
            $req['order_type'] = 'recommend';
            
            require_once('application/third_party/inicis/INIStdPayUtil.php');
            $SignatureUtil = new INIStdPayUtil();

            $timestamp = $SignatureUtil->getTimestamp();

            $orderNumber = 'CDB' . $timestamp; // 가맹점 주문번호(가맹점에서 직접 설정)
            $price = $req['subscribe_total_price'];
            $req['total_price'] = $price;
            $val['mid'] = MIDB;
            
            $val['oid'] = $orderNumber;
            $val['price'] = $price;
            $val['buyername'] = '';
            $val['buyertel'] = '';
            $val['buyeremail'] = '';
            $val['timestamp'] = $timestamp;
            
            $result['mem_id'] = $req['mem_id'];
            $req['mem_username'] = $req['mem_name'];
            $result['order_mem_type'] = $req['order_mem_type'];
            
            $params = array(
                "oid" => $orderNumber,
                "price" => $price,
                "timestamp" => $timestamp
                );
            $val['signature'] = $SignatureUtil->makeSignature($params, "sha256");
            $val['mKey'] = $SignatureUtil->makeHash(BIL_KEY, "sha256");
            
            if ($req['order_mem_type'] === 'guest') {
                
                $result['mem_id'] = $this->payment_m->payment_adduser_easypay($req);
                $req['mem_id'] = $result['mem_id'];
            }

            if ($req['pay_type'] == 'naver') {
                    $val['gopaymethod'] = 'naver';
                    $val['acceptmethod'] = '';
                
            } else if ($req['pay_type'] == 'kakao') {
                    $val['gopaymethod'] = 'kakao';
                    $val['acceptmethod'] = '';
            } else {
                $val['gopaymethod'] = 'CARD';
                $val['acceptmethod'] = 'BILLAUTH(Card):FULLVERIFY';

            }
            
            $res = $this->payment_m->recommend_payment_request($req, $val);
            $result['order_type'] = $req['order_type'];
            if ($res) {
                $val['timestamp2'] = date('YmdHis');
                $val['hashdata'] = hash('sha256', $val['mid'] . $val['oid'] . $val['timestamp2'] . INILITE_KEY);
                $result['status'] = 'succ';
                $result['quantity'] =  6;
                $result['data'] = $val;
                $result['den_id'] = $req['den_id'];
            } else {
                $result['status'] = 'fail';
                $result['msg'] = '요청에 실패하였습니다.';
            }
            
            
        }

        echo json_encode($result);
    }
    
    public function payment_result(){
        if (empty($_REQUEST['merchantData']) || empty($_REQUEST['den_id'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $pay = $this->payment_m->select_payment_temp($_REQUEST['merchantData']);
            require_once('application/third_party/inicis/INIStdPayUtil.php');
            require_once('application/third_party/inicis/HttpClient.php');
            
            $res = array();
            
            if ($pay['payment_type'] == 'kakao') {
                list($order, $pay, $res) = $this->kakao_payment($pay, $res, $_REQUEST);
            } else if ($pay['payment_type'] == 'naver') {
                list($order, $pay, $res) = $this->naver_payment($pay, $res, $_REQUEST);
            } else {
                $pay = $this->payment_m->select_payment_temp_inicis($_REQUEST['merchantData']);
                list($order, $pay, $res) = $this->inicis_payment($pay, $res, $_REQUEST);
            }
            
            if($res['status'] == 'OK'){
                $this->recommend_m->recommend_update(array('mem_id'=>$pay['mem_id'],'den_id'=>$_REQUEST['den_id']));
                 $_SESSION['user']['is_recommend'] = 'y';
                 $this->data['user']['is_recommend'] = 'y';
            }
            
            
            $this->data['res'] = $res;
            $this->data['order'] = $order;
            $this->load->view('header_v', $this->data);
            $this->load->view('recommend/order_complete_v');
            $this->load->view('footer_v');
            
        }
    }
    
    private function naver_payment($pay, $res, $data) {
        if ($pay['status'] == 'OK') {
            header('Location: /my/order/order_list');
            die();
        }
        if($data['resultCode'] == 'Fail'){
            $msg = array(
                "userCancel" =>"결제를 취소하셨습니다.주문 내용 확인 후 다시 결제해주세요.",
                "OwnerAuthFail"=> "타인 명의 카드는 결제가 불가능합니다.회원 본인 명의의 카드로 결제해주세요.",
                "paymentTimeExpire"=> "결제 가능한 시간이 지났습니다.주문 내용 확인 후 다시 결제해주세요."
            );

            $res['res_result'] = 'fail';
            $res['res_msg'] = isset($msg[$_REQUEST['resultMessage']])?$msg[$_REQUEST['resultMessage']]:$_REQUEST['resultMessage'];
            $res['pay_id'] = $pay['pay_id'];
            $res['status'] = 'CANCEL';
            $this->payment_m->payment_response($res);
            $order = $pay;
            return array($order, $pay, $res);
        }

        $res['status'] = (isset($data['resultCode']) && $data['resultCode'] == 'Success') ? 'OK' : 'fail';

        if ($res['status'] == 'OK') {
            $curl = curl_init();
            $url =NAVERPAY_BASE_URL_BILL.'/naverpay/payments/recurrent/regist/v1/approval';
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            $aPostData = array();

 
                $aPostData['reserveId'] = $data['reserveId'];
                $aPostData['tempReceiptId'] = $data['tempReceiptId'];

            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
            $header = Array(
                'X-Naver-Client-Id:'.NAVERPAY_CLIENTID,
                'X-Naver-Client-Secret:'.NAVERPAY_SECRET,
                'X-NaverPay-Chain-Id:'.NAVERPAY_CHAINID_BILL
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

            $gData = curl_exec($curl);
            $recv = json_decode($gData, true);

            if ($recv['code'] == "Success") {

                $res['res_result'] =  $recv['body']['reserveId'];
                $res['res_msg'] = '성공';
                $res['status'] = 'OK';
                $res['pay_id'] = $pay['pay_id'];
                $this->payment_m->payment_response($res);

                $pay['status'] = 'PAYMENT';

                $resultMap = array();
                $resultMap['goodsName'] = $pay['product_name'];
                $resultMap['tid'] = $recv['body']['reserveId'] ;
                $resultMap['payDevice'] = 'PC';
                $resultMap['vactBankName'] = '';
                $resultMap['VACT_BankCode'] =  '' ;
                $resultMap['CARD_BillKey'] = $recv['body']['recurrentId'];
                $resultMap['VACT_Name'] = '';
                $resultMap['VACT_Num'] = '';
                $resultMap['VACT_InputName'] = '';
                $resultMap['VACT_Date'] = '';
                $resultMap['ACCT_BankName'] = '';
                $resultMap['ACCT_BankCode'] = '';
                $resultMap['ACCT_Num'] = '';
                $resultMap['CSHR_ResultCode'] = '';
                $resultMap['CSHR_Type'] = '';
                $resultMap['aid'] =$recv['body']['tempReceiptId'] ;
                $resultMap['applDate'] = date('Ymd') ;
                $resultMap['applTime'] = date('his') ;

                $naverpay_card_com = array(
                    'C0' => '신한', 'C1' => '비씨', 'C2' => '광주', 'C3' => 'KB국민', 'C4' => 'NH',
                    'C5' => '롯데', 'C6' => '산업', 'C7' => '삼성', 'C8' => '수협', 'C9' => '씨티', 'CA' => '외환',
                    'CB' => '우리', 'CC' => '전북', 'CD' => '제주', 'CF' => '하나-외환', 'CH' => '현대'
                );

                $resultMap['P_FN_NM'] = '' ;
                $resultMap['CARD_Code'] = '';
                $resultMap['CARD_Num'] = '';
                $resultMap['payMethod'] = 'NaverPay';

                $this->payment_m->payment_exec($res['res_result'], $pay, $resultMap);
                
                
                $order = $this->payment_m->select_order_info($pay['order_id']);
                $order['order_mem_type'] = $pay['order_mem_type'];
                
                    $sid = array();
                    $sid['order_id'] = $pay['order_id'];
                    $sid['sid'] = $recv['body']['recurrentId'];
                    
                    $pay['csu_id'] = $order['billing_order_id'];

                    $this->payment_m->payment_easypay_subscript($sid);

                    if ($pay['start_date'] == date('Y-m-d')) {

                        $resultMap['payDate'] = $resultMap['applDate'];
                        $resultMap['payTime'] = $resultMap['applTime'];

                        $sub = $this->billing_payment_naver($pay, $resultMap);
                        
                        $pay['billing_id'] = $sub['order_id'];
                        
                        if($sub['code'] == 'Success'){
                            $this->payment_m->insert_billing_order($sub, $pay, $resultMap);
                        } else {
                            $res['res_msg'] = '결제 실패 - '.$sub['result_msg'];
                            $res['status'] = 'Fail';
                            $pay['status'] = $res['status'];
                            $res['pay_id'] = $pay['pay_id'];
                            $this->payment_m->payment_response($res);
                            $this->subscribe_m->cancel_subscribe($sub);
                            $this->cancelNaverPay($sub['billKey']);
                            $order = $pay;
                        }
                    }
                
                if ($pay['status'] == 'PAYMENT') {
                    $this->order_sms($pay);
                }
            } else {
                $res['res_result'] = 'fail';
                $res['res_msg'] = $recv['message'];
                $res['pay_id'] = $pay['pay_id'];
                $res['status'] = 'CANCEL';
                $this->payment_m->payment_response($res);
                $order = $pay;
            }

            curl_close($curl);
        }


        return array($order, $pay, $res);
    }
    
    private function kakao_payment($pay, $res, $data) {
        if ($pay['status'] == 'OK') {
            header('Location: /my/order/order_list');
            die();
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, KAKAO_BASE_URL . '/v1/payment/approve');
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        $aPostData = array();
        
        $aPostData['cid'] = KAKAO_CID_SUBSCRIP;
        $aPostData['tid'] = $pay['tid'];
        $aPostData['partner_order_id'] = $pay['order_id'];
        $aPostData['partner_user_id'] = $pay['mem_id'];
        $aPostData['pg_token'] = isset($data['pg_token'])?$data['pg_token']:'';
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
        $header = Array(
            'POST /v1/payment/approve HTTP/1.1',
            'Host: kapi.kakao.com',
            'Authorization: KakaoAK '.KAKAO_APP_KEY,
            'Content-type: application/x-www-form-urlencoded;charset=utf-8'
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $gData = curl_exec($curl);
        $recv = json_decode($gData, true);
        
        curl_close($curl);
        
        $res['pay_id'] = $pay['pay_id'];
 
        if (!isset($recv['code'])) {

            $res['res_result'] = $recv['aid'];
            $res['res_msg'] = '성공';
            $res['status'] = 'OK';
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
            $resultMap['CARD_BillKey'] = $recv['sid'];
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
                $resultMap['P_FN_NM'] = (isset($recv['card_info']['issuer_corp']))?$recv['card_info']['issuer_corp']:'';
                $resultMap['CARD_Code'] = (isset($recv['card_info']['issuer_corp_code']))?$recv['card_info']['issuer_corp_code']:'';
            } else {
                $resultMap['P_FN_NM'] = '';
                $resultMap['CARD_Code'] = '';
            }
            $resultMap['CARD_Num'] = '';
            $resultMap['payMethod'] = 'KakaoPay';

            $this->payment_m->payment_exec($res['res_result'], $pay, $resultMap);

            $order = $this->payment_m->select_order_info($pay['order_id']);
            $order['order_mem_type'] = $pay['order_mem_type'];

  
                $sid = array();
                $sid['order_id'] = $pay['order_id'];
                $sid['sid'] = $recv['sid'];
                $this->payment_m->payment_easypay_subscript($sid);
                
                $pay['csu_id'] = $order['billing_order_id'];

                if ($pay['start_date'] == date('Y-m-d')) {
                    $sub = $this->billing_payment_kakao($pay,$resultMap);
                    
                    $pay['csu_id'] = $sub['csu_id'];

                    $resultMap['payDate'] = date('Ymd', strtotime($recv['approved_at']));
                    $resultMap['payTime'] = date('his', strtotime($recv['approved_at']));
                    
                    $pay['billing_id'] = $sub['order_id'];
                    
                    if($sub['result_code'] == '00'){
                        $this->payment_m->insert_billing_order($sub, $pay, $resultMap);
                    } else {
                        $res['res_msg'] = '결제 실패 - '.$sub['result_msg'];
                        $res['status'] = 'Fail';
                        $res['pay_id'] = $pay['pay_id'];
                        $pay['status'] = $res['status'];
                        $this->payment_m->payment_response($res);
                        $this->subscribe_m->cancel_subscribe($sub);
                        $this->cancelKakaoPay($sub['billKey']);
                        $order = $pay;
                    }
                }
            

            if ($pay['status'] == 'PAYMENT') {
                $this->order_sms($pay);
            }
        } else {
            $res['status'] = 'CANCEL';
            $res['res_result'] = '결제실패';
            $res['res_msg'] = $recv['msg'];
            $this->payment_m->payment_response($res);
            $order = $pay;
        }

        return array($order, $pay, $res);
    }
    
    private function inicis_payment($pay, $res, $data) {
        if ($pay['status'] == 'OK') {
            header('Location: /my/order/order_list');
        }

        $util = new INIStdPayUtil();

        $timestamp = date("YmdHis");
        $ip = '118.67.134.168';
        $type = 'Auth';
        $paymethod = 'Card';

        
        $cardNumber = @openssl_encrypt($pay['cardNumber'], "aes-128-cbc", BIL_INI_API_KEY, true, BIL_INI_API_IV);
        $cardNumber = base64_encode($cardNumber);
        $cardExpire = @openssl_encrypt(substr($pay['cardExpire'],2,2).substr($pay['cardExpire'],0,2), "aes-128-cbc", BIL_INI_API_KEY, true, BIL_INI_API_IV);
        $cardExpire = base64_encode($cardExpire);
        $regNo = @openssl_encrypt($pay['regNo'], "aes-128-cbc", BIL_INI_API_KEY, true, BIL_INI_API_IV);
        $regNo = base64_encode($regNo);
        $cardPw = @openssl_encrypt($pay['cardPw'], "aes-128-cbc", BIL_INI_API_KEY, true, BIL_INI_API_IV);
        $cardPw = base64_encode($cardPw);

        $aPostData = array(
            'type' => $type,
            'paymethod' => $paymethod,
            'timestamp' => $timestamp,
            'clientIp' => $ip,
            'mid' => MIDB,
            'url' => $this->data['base_url'],
            'moid' => $pay['order_id'],
            'goodName' => $pay['product_name'],
            'buyerName' => $pay['mem_username'],
            'buyerEmail' => $pay['mem_email'],
            'buyerTel' => $pay['mem_phone'],
            'price' => $pay['total_price'],
            'cardNumber' => $cardNumber,
            'cardExpire' => $cardExpire,
            'regNo' => $regNo,
            'cardPw' => $cardPw,
            'hashData' => hash('sha512', BIL_INI_API_KEY . $type . $paymethod . $timestamp . $ip . MIDB . $pay['order_id'] . $pay['total_price'] . $cardNumber)
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
        
        curl_close($curl);
        
        if ($recv['resultCode'] == '00') {
            
            $res['res_result'] = $recv['tid'];
            $res['res_msg'] = $recv['resultMsg'];
            $res['status'] = 'OK';
            $res['pay_id'] = $pay['pay_id'];
            $this->payment_m->payment_response($res);
            
            $sid = array();
            $sid['order_id'] = $pay['order_id'];
            $sid['sid'] = $recv['billKey'];
            $this->payment_m->payment_easypay_subscript($sid);
            
            
            $pay['status'] = 'PAYMENT';

            $resultMap = array();
            $resultMap['goodsName'] = $pay['product_name'];
            $resultMap['tid'] = $recv['tid'];
            $resultMap['payMethod'] = $paymethod;
            $resultMap['payDevice'] = 'PC';
            $resultMap['vactBankName'] = '';
            $resultMap['VACT_BankCode'] = '';
            $resultMap['VACT_Name'] = '';
            $resultMap['VACT_Num'] = '';
            $resultMap['VACT_InputName'] = '';
            $resultMap['CARD_BillKey'] = $recv['billKey'];
            $resultMap['VACT_Date'] = '';
            $resultMap['ACCT_BankName'] = '';
            $resultMap['ACCT_BankCode'] = '';
            $resultMap['ACCT_Num'] = '';
            $resultMap['CSHR_ResultCode'] = '';
            $resultMap['CSHR_Type'] = '';
            $resultMap['aid'] = $recv['tid'];
            $resultMap['applDate'] =$recv['payDate'];
            $resultMap['applTime'] =$recv['payTime'];

            $fn_nm = array(
                '11'=>'BC카드','12'=>'삼성카드','14'=>'신한카드','15'=>'한미카드','16'=>'NH카드','17'=>'하나 SK카드',
                '21'=>'글로벌 VISA','22'=>'글로벌 MASTER','23'=>'글로벌 JCB','24'=>'글로벌 아멕스','25'=>'글로벌 다이너스',
                '91'=>'네이버포인트','93'=>'토스머니','94'=>'SSG머니','96'=>'엘포인트','97'=>'카카오머니','98'=>'페이코',
                '01'=>'외환카드','03'=>'롯데카드','04'=>'현대카드','06'=>'국민카드'
                );
           
            $resultMap['P_FN_NM'] = (isset($fn_nm[$recv['cardCode']]))?$fn_nm[$recv['cardCode']]:'';
            $resultMap['CARD_Code'] = $recv['cardCode'];
            $resultMap['CARD_Num'] = $recv['cardNumber'];

            $this->payment_m->payment_exec($res['res_result'], $pay, $resultMap);
            
            $order = $this->payment_m->select_order_info($pay['order_id']);
            $order['order_mem_type'] = $pay['order_mem_type'];
            
            if ($pay['start_date'] == date('Y-m-d')) {
                $billing = $this->payment_m->select_subscribe_info($pay['order_id'])->row_array();

                $sub['order_id'] = 'CDA' . $util->getTimestamp();
                $sub['mem_username'] = $pay['mem_username'];
                $sub['mem_phone'] = $pay['mem_phone'];
                $sub['mem_email'] = $pay['mem_email'];
                $sub['price'] = $pay['total_price'];
                $sub['product_name'] = $pay['product_name'];
                $sub['billKey'] = $billing['billing_key'];
                $sub['csu_id'] = $billing['csu_id'];
                
                $sub['regNo'] = $regNo;
                $sub['cardPw'] = $cardPw;
                $sub['cardNumber'] = $pay['cardNumber'];

                if ($this->billing_payment_inicis($sub, $result)) {
                    $this->payment_m->insert_billing_order($sub, $pay, $result);
                    $pay['billing_id'] = $sub['order_id'];
                } else {
                    $res['status'] = 'Fail';
                    $res['res_result'] = 'Fail';
                    $res['res_msg'] = '결제실패';
                    $res['pay_id'] = $pay['pay_id'];
                    $this->payment_m->payment_response($res);
                    
                    $this->subscribe_m->cancel_subscribe($sub);
                    
                    $order = $pay;
                }
            }
        } else {
            $res['status'] = 'Fail';
            $res['res_result'] = 'Fail';
            $res['res_msg'] = $recv['resultMsg'];
            $res['pay_id'] = $pay['pay_id'];
            $this->payment_m->payment_response($res);
            $order = $pay;
        }


        return array($order, $pay, $res);
    }
    
        private function billing_payment_kakao($pay,&$map) {
        $util = new INIStdPayUtil();

        $billing = $this->payment_m->select_subscribe_info($pay['order_id'])->row_array();

        $sub = array();
        $sub['csu_id'] = $billing['csu_id'];
        $sub['order_id'] = 'CDA' . $util->getTimestamp(); // $recvbody['paymentId'];

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
        $aPostData['partner_user_id'] = $pay['mem_id'];
        $aPostData['item_name'] = $pay['product_name'];
        $aPostData['quantity'] = $pay['total_qty'];
        $aPostData['total_amount'] = $pay['total_price'];
        $aPostData['tax_free_amount'] = 0;
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
        $header = Array(
            'POST /v1/payment/subscription HTTP/1.1',
            'Host: kapi.kakao.com',
            'Authorization: KakaoAK '.KAKAO_APP_KEY,
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

        if (isset($recv['code'])) {
            $sub['result_code'] = $recv['code'];
            $sub['result_msg'] = $recv['msg'];
        } else {
            $map['tid'] =  $recv['tid'];
            $sub['result_msg'] = 'KakaoPay 정기결제';
            $sub['result_code'] = '00';
        }

        $this->payment_m->insert_subscribe_hitory($sub);

        return $sub;
    }

    private function billing_payment_naver($pay, &$map) {

        $util = new INIStdPayUtil();
        
        $sub = array();
        $sub['order_id'] = 'CDA' . $util->getTimestamp(); // $recvbody['paymentId'];
        
        $billing = $this->payment_m->select_subscribe_info($pay['order_id'])->row_array();

        $header = Array(
            'X-Naver-Client-Id:'.NAVERPAY_CLIENTID,
            'X-Naver-Client-Secret:'.NAVERPAY_SECRET,
            'X-NaverPay-Chain-Id:'.NAVERPAY_CHAINID_BILL
        );

        $curl = curl_init();
        $url = NAVERPAY_BASE_URL_BILL.'/naverpay/payments/recurrent/pay/v3/reserve';
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);

        $aPostData = array();
        $aPostData['recurrentId'] = $billing['billing_key'];
        $aPostData['totalPayAmount'] = $pay['total_price'];
        $aPostData['taxScopeAmount'] = $pay['total_price'];
        $aPostData['taxExScopeAmount'] = 0;
        $aPostData['productName'] = $pay['list'][0]['cit_name'];
        $aPostData['merchantPayId'] = $sub['order_id'];
        $aPostData['merchantUserId'] = $pay['mem_id'];

        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $gData = curl_exec($curl);
        $reserve = json_decode($gData, true);

        if ($reserve['code'] == "Success") {


            $sub['csu_id'] = $billing['csu_id'];

            $curl = curl_init();
            $url = NAVERPAY_BASE_URL.'/naverpay/payments/recurrent/pay/v3/approval';
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
            $sub['code'] = $recv['code'];
            if ($recv['code'] == "Success") {
                $recvbody = $recv['body'];
                $map['tid'] = $recvbody['paymentId'];
                $sub['result_code'] = '00';
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
                $sub['card_no'] = '';
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
    
    function guest_subscribe() {
        if (!isset($_SERVER['HTTP_REFERER'])) {
            $this->data['move'] = '/';
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else if (isset($_SESSION['mem_userid'])) {
                    header('Location: /');
                    die();
        } else {
            $req = $this->input->post();
            
            $item = $this->recommend_m->recommend_items($req)->result_array();
            
            $delivery_date = array(
                'delivery_day'=>$req['delivery_day'],
                'delivery_period'=>$req['delivery_period'],
                'start_date'=>$req['start_date']
            );
                      
            $this->data['delivery_date'] = $delivery_date;
            
            $this->data['den_id'] = $req['den_id'];
            $this->data['item'] = $item;
            $this->data['member'] = array();

            $this->load->view('header_v', $this->data);
            $this->load->view('/recommend/guest_subscribe');
            $this->load->view('footer_v');
        }
    }

    function member_subscribe() {
        if (!isset($_SERVER['HTTP_REFERER'])) {
            $this->data['move'] = '/';
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            if (!empty($this->data['user'])) {
                $this->data['member'] = $this->member_m->member_info($this->data['user']['mem_id'])->row_array();
                $this->data['delivery'] = $this->cart_m->delivery_address_default($this->data['user']['mem_id'])->row_array();
            } else {
                    $this->data['member'] = array();
            }
            $req = $this->input->post();
            
            $item = $this->recommend_m->recommend_items($req)->result_array();
            
            $delivery_date = array(
                'delivery_day'=>$req['delivery_day'],
                'delivery_period'=>$req['delivery_period'],
                'start_date'=>$req['start_date']
            );
            
            $this->data['delivery_date'] = $delivery_date;
            $this->data['den_id'] = $req['den_id'];
            $this->data['item'] = $item;
            $this->load->view('header_v', $this->data);
            $this->load->view('/recommend/member_subscribe');
            $this->load->view('footer_v');
        }
    }
    
    private function cancelKakaoPay($billing_key) {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, KAKAO_BASE_URL . '/v1/payment/manage/subscription/inactive');
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
            'Authorization: KakaoAK ' . KAKAO_APP_KEY,
            'Content-type: application/x-www-form-urlencoded;charset=utf-8'
        );

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $gData = curl_exec($curl);

        curl_close($curl);
    }

    /* 네이퍼페이 추가 */

    private function cancelNaverPay($billing_key) {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, NAVERPAY_BASE_URL_BILL . '/naverpay/payments/recurrent/expire/v1/request');
        $header = Array(
            'X-Naver-Client-Id:' . NAVERPAY_CLIENTID,
            'X-Naver-Client-Secret:' . NAVERPAY_SECRET,
            'X-NaverPay-Chain-Id:' . NAVERPAY_CHAINID_BILL
        );
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);

        $aPostData = array();
        $aPostData['recurrentId'] = $billing_key;
        $aPostData['expireRequester'] = 2;
        $aPostData['expireReason'] = '결제 실패에 의한 시스템 구독 취소';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));

        $gData = curl_exec($curl);

        curl_close($curl);
    }
}
