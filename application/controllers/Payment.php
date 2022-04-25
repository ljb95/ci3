<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CD_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('common');
        $this->load->library('email_l');
        $this->load->model('payment_m');
        $this->load->model('subscribe_m');
        $this->load->model('email_m');
    }

    public function payment_request() {
        $req = $this->input->post();

        $result = array();
        if (empty($req)) {
            $result['status'] = 'fail';
            $result['msg'] = '잘못된 요청입니다.';
            $result['target'] = 'recipient_name';
        } else if($req['pay_type'] == 'naver' && $req['total_price'] <= 100){
            $result['status'] = 'fail';
            $result['msg'] = '100원 미만의 금액은 네이버페이 이용이 불가능합니다';
            $result['target'] = '';
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


		
	    if($req['pay_type'] == 'card' && $req['cart_type'] == 'subscribe'){
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


    if(isset($result['status']) && $result['status']  == 'fail'){
		    echo json_encode($result);
                    return;
                }
            }
		    
            require_once('application/third_party/inicis/INIStdPayUtil.php');
            $SignatureUtil = new INIStdPayUtil();
            $mid = ($req['cart_type'] == 'subscribe' ? MIDB : MID0);
            //인증
            $signKey = ($req['cart_type'] == 'subscribe' ? BIL_KEY : WEB_KEY); // 가맹점에 제공된 웹 표준 사인키(가맹점 수정후 고정)
            $orderNumber = 'CD' . ($req['cart_type'] == 'subscribe' ? 'B' : 'I') . $SignatureUtil->getTimestamp(); // 가맹점 주문번호(가맹점에서 직접 설정)


            $timestamp = $SignatureUtil->getTimestamp();   // util에 의해서 자동생성

            $price = $req['total_price'];        // 상품가격(특수기호 제외, 가맹점에서 직접 설정)

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
            /* 정기결제 */
            
            $result['mem_id'] = $req['mem_id'];
            $req['mem_username'] = $req['mem_name'];
            $result['order_mem_type'] = $req['order_mem_type'];
            
            if ($req['cart_type'] == 'subscribe') {
                if ($req['pay_type'] == 'card') {
                    $val['gopaymethod'] = 'CARD';
                    $val['acceptmethod'] = 'BILLAUTH(Card):FULLVERIFY';
                    if ($req['order_mem_type'] === 'guest') {
                        $result['mem_id'] = $this->payment_m->payment_adduser_easypay($req);
                        $req['mem_id'] = $result['mem_id'];
                    }
                } else if ($req['pay_type'] == 'kakao') {
                    $val['gopaymethod'] = 'kakao';
                    $val['acceptmethod'] = '';
                    if ($req['order_mem_type'] === 'guest') {
                        $result['mem_id'] = $this->payment_m->payment_adduser_easypay($req);
                        $req['mem_id'] = $result['mem_id'];
                    }
                } else if ($req['pay_type'] == 'naver') {
                    $val['gopaymethod'] = 'naver';
                    $val['acceptmethod'] = '';
                    if ($req['order_mem_type'] === 'guest') {
                        $result['mem_id'] = $this->payment_m->payment_adduser_easypay($req);
                        $req['mem_id'] = $result['mem_id'];
                    }
                }
            } else {
                /* 1회결제 */
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
                    /* LJB 0303 kakao 추가 */
                    $val['gopaymethod'] = 'kakao';
                    $val['acceptmethod'] = '';
                    $val['bank_dt'] = '';
                    $val['bank_tm'] = '';
                    if ($req['order_mem_type'] === 'guest') {
                        $result['mem_id'] = $this->payment_m->payment_adduser_easypay($req);
                        $req['mem_id'] = $result['mem_id'];
                    }
                } else if ($req['pay_type'] == 'onlyssp') {
                    /* LJB 0304 samsung 추가 */
                    $val['gopaymethod'] = $req['device_type'] == 'PC' ? 'onlyssp' : 'CARD';
                    $val['acceptmethod'] = ($req['device_type'] == 'PC')? 'cardonly':"d_samsungpay=Y" ;
                    $val['bank_dt'] = '';
                    $val['bank_tm'] = '';
                    if ($req['order_mem_type'] === 'guest') {
                        $result['mem_id'] = $this->payment_m->payment_adduser_easypay($req);
                        $req['mem_id'] = $result['mem_id'];
                    }
                } else if ($req['pay_type'] == 'naver') {
                    /* LJB 0304 naver 추가 */
                    $val['gopaymethod'] = 'naver';
                    $val['acceptmethod'] = '';
                    $val['bank_dt'] = '';
                    $val['bank_tm'] = '';
                    if ($req['order_mem_type'] === 'guest') {
                        $result['mem_id'] = $this->payment_m->payment_adduser_easypay($req);
                        $req['mem_id'] = $result['mem_id'];
                    }
                }
            }

            $res = $this->payment_m->payment_request($req, $val);
           
            if ($res) {
                $val['timestamp2'] = date('YmdHis');
                $val['hashdata'] = hash('sha256', $mid . $val['oid'] . $val['timestamp2'] . INILITE_KEY);
		$result['status'] = 'succ';
		$sum = 0;
		foreach($req['qty'] as $row) {
			$sum += $row;
		}
                $result['quantity'] = $sum;
                $result['data'] = $val;
            } else {
                $result['status'] = 'fail';
                $result['msg'] = '요청에 실패하였습니다.';
            }
        }
        echo json_encode($result);
    }

    public function change_request() {
        $req = $this->input->post();

        $result = array();
        if (empty($req)) {
            $result['status'] = 'fail';
            $result['msg'] = '잘못된 요청입니다.';
            $result['target'] = 'recipient_name';
        } else if (empty($req['pay_type'])) {
            $result['status'] = 'fail';
            $result['msg'] = '결제수단을 선택해주세요.';
            $result['target'] = '';
        } else {
            $val = array();

            require_once('application/third_party/inicis/INIStdPayUtil.php');
            $SignatureUtil = new INIStdPayUtil();
            $mid = MIDB;
            //인증
            $signKey = BIL_KEY; // 가맹점에 제공된 웹 표준 사인키(가맹점 수정후 고정)
            $orderNumber = 'CDB' . $SignatureUtil->getTimestamp(); // 가맹점 주문번호(가맹점에서 직접 설정)
            $timestamp = $SignatureUtil->getTimestamp();   // util에 의해서 자동생성

            $price = $req['total_price'];        // 상품가격(특수기호 제외, 가맹점에서 직접 설정)

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

            $val['gopaymethod'] = 'CARD';
            $val['acceptmethod'] = 'BILLAUTH(Card):FULLVERIFY';

            $res = $this->payment_m->change_request($req, $val);

            if ($res) {
                $val['timestamp2'] = date('YmdHis');
                $val['hashdata'] = hash('sha256', $mid . $val['oid'] . $val['timestamp2'] . INILITE_KEY);
                $result['status'] = 'succ';
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
            $this->data['msg'] = '잘못 된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $res = array();
            $res['pay_id'] = $_REQUEST['P_NOTI'];
            $res['res_result'] = $_REQUEST['P_STATUS'];
            $res['res_msg'] = iconv("EUC-KR", "UTF-8", $_REQUEST['P_RMESG1']);
            $pay = $this->payment_m->select_payment_temp($_REQUEST['P_NOTI']);
            if (empty($pay['res_result'])) {
                if (strcmp("01", $_REQUEST["P_STATUS"]) == 0) {
                    $res['status'] = 'CANCEL';
                    $this->payment_m->payment_response($res);
                    header('Location: /cart/cart_list?type=' . $pay['order_type']);
                } else {
                    $res['status'] = 'OK';
                    
                    if ($pay['order_mem_type'] == 'guest' && $pay['mem_id'] == 0) {
                        $pay['mem_id'] = $this->payment_m->payment_adduser($pay);
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
                        $map['aid'] = '';

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
                            $this->payment_m->payment_exec($res['res_result'], $pay, $map);
                            $this->session->set_userdata('cart', '');
                            if ($pay['status'] == 'PAYMENT') {
                                $this->order_sms($pay);
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
            $this->load->view('header_v', $this->data);
            $this->load->view('cart/order_complete_v');
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
            $res['pay_id'] = $_REQUEST['merchantreserved'];
            $res['res_result'] = $_REQUEST['resultcode'];
            $res['res_msg'] = $_REQUEST['resultmsg'];
            $pay = $this->payment_m->select_payment_temp($_REQUEST['merchantreserved']);
            if (empty($pay['res_result'])) {
                if (strcmp("01", $_REQUEST["resultcode"]) == 0) {
                    if (strpos($_REQUEST['resultmsg'], '[cancel]') !== false) {
                        $res['status'] = 'CANCEL';
                        $res['result_msg'] = $_REQUEST['resultmsg'];
                        $this->payment_m->payment_response($res);
                        header('Location: /cart/cart_list?type=' . $pay['order_type']);
                    } else {
                        $res['status'] = 'ERROR';
                        $res['result_msg'] = $_REQUEST['resultmsg'];
                        $this->payment_m->payment_response($res);
//						header('Location: /cart/cart_list?type=' . $pay['order_type']);
                    }
                } else {
                    $res['status'] = 'OK';
                    if ($pay['order_mem_type'] === 'guest') {
                        $pay['mem_id'] = $this->payment_m->payment_adduser($pay);
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

                        $map['aid'] = '';
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
                        $this->payment_m->payment_exec($res['res_result'], $pay, $map);
                        $this->session->set_userdata('cart', '');
                        if ($pay['order_type'] === 'subscribe' && $pay['start_date'] == date('Y-m-d')) {
                            require_once('application/third_party/inicis/INIStdPayUtil.php');
                            require_once('application/third_party/inicis/HttpClient.php');

                            $util = new INIStdPayUtil();
                            $billing = $this->payment_m->select_subscribe_info($pay['order_id'])->row_array();

                            $sub = array();
                            $sub['order_id'] = 'CDA' . $util->getTimestamp();
                            $sub['mem_username'] = $pay['mem_username'];
                            $sub['mem_phone'] = $pay['mem_phone'];
                            $sub['mem_email'] = $pay['mem_email'];
                            $sub['price'] = $pay['total_price'];
                            $sub['product_name'] = $pay['product_name'];
                            $sub['billKey'] = $billing['billing_key'];
                            $sub['csu_id'] = $billing['csu_id'];

                            if ($this->billing_payment($sub, $result)) {
                                $this->payment_m->insert_billing_order($sub, $pay, $result);
                                $pay['billing_id'] = $sub['order_id'];
                            }
                        }
                        $this->order_sms($pay);
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
            $this->load->view('header_v', $this->data);
            $this->load->view('cart/order_complete_v');
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
            $url = ($pay['order_type'] == 'subscribe') ? NAVERPAY_BASE_URL_BILL.'/naverpay/payments/recurrent/regist/v1/approval' : NAVERPAY_BASE_URL.'/naverpay/payments/v2.2/apply/payment';
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60);
	    curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
	    $aPostData = array();
	    $chain_id = ($pay['order_type'] == 'subscribe') ?NAVERPAY_CHAINID_BILL:NAVERPAY_CHAINID;

            if ($pay['order_type'] == 'subscribe') {
                $aPostData['reserveId'] = $data['reserveId'];
                $aPostData['tempReceiptId'] = $data['tempReceiptId'];
            } else {
                $aPostData['paymentId'] = $data['paymentId'];
            }
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));
            $header = Array(
                'X-Naver-Client-Id:'.NAVERPAY_CLIENTID,
                'X-Naver-Client-Secret:'.NAVERPAY_SECRET,
                'X-NaverPay-Chain-Id:'.$chain_id
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

            $gData = curl_exec($curl);
            $recv = json_decode($gData, true);

            if ($recv['code'] == "Success") {

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

                $resultMap['P_FN_NM'] = ($pay['order_type'] == 'subscribe' || !isset($naverpay_card_com[$recv['body']['detail']['cardCorpCode']])) ? '' : $naverpay_card_com[$recv['body']['detail']['cardCorpCode']];
                $resultMap['CARD_Code'] = ($pay['order_type'] == 'subscribe' || !isset($recv['body']['detail']['cardCorpCode'])) ? '' : $recv['body']['detail']['cardCorpCode'];
                $resultMap['CARD_Num'] = '';
                $resultMap['payMethod'] = 'NaverPay';

                $this->payment_m->payment_exec($res['res_result'], $pay, $resultMap);
                
                
                $order = $this->payment_m->select_order_info($pay['order_id']);
                $order['order_mem_type'] = $pay['order_mem_type'];
                
                /* sid update */
                if ($pay['order_type'] == 'subscribe') {
                    $sid = array();
                    $sid['order_id'] = $pay['order_id'];
                    $sid['sid'] = $recv['body']['recurrentId'];
                    
                    $pay['csu_id'] = $order['billing_order_id'];

                    $this->payment_m->payment_easypay_subscript($sid);

                    if ($pay['start_date'] == date('Y-m-d')) {

                        $resultMap['payDate'] = $resultMap['applDate'];
                        $resultMap['payTime'] = $resultMap['applTime'];

                        $sub = $this->billing_payment_naver($pay, $resultMap);
                        if($sub['code'] == 'Success'){
                        $this->payment_m->insert_billing_order($sub, $pay, $resultMap);
                        $pay['billing_id'] = $sub['order_id'];
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
        
        $aPostData['cid'] = ($pay['order_type'] == 'subscribe') ? KAKAO_CID_SUBSCRIP : KAKAO_CID_EASYPAY;
        $aPostData['tid'] = $pay['tid'];
        $aPostData['partner_order_id'] = $pay['order_id'];
        $aPostData['partner_user_id'] = $pay['mem_id'];
        $aPostData['pg_token'] = $data['pg_token'];
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

            if ($pay['order_type'] == 'subscribe') {
                /* ToDo */
                $sid = array();
                $sid['order_id'] = $pay['order_id'];
                $sid['sid'] = $recv['sid'];
                $this->payment_m->payment_easypay_subscript($sid);
                
                $pay['csu_id'] = $order['billing_order_id'];

                if ($pay['start_date'] == date('Y-m-d')) {
                    $sub = $this->billing_payment_kakao($pay,$resultMap);
                    
                    $pay['csu_id'] = $sub['csu_id'];
                    $pay['billing_id'] = $sub['order_id'];

                    $resultMap['payDate'] = date('Ymd', strtotime($recv['approved_at']));
                    $resultMap['payTime'] = date('his', strtotime($recv['approved_at']));

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

    public function payment_result() {
        if (empty($_REQUEST['merchantData'])) {
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
            } else if ($pay['order_type'] == 'subscribe') {
                $pay = $this->payment_m->select_payment_temp_inicis($_REQUEST['merchantData']);
                list($order, $pay, $res) = $this->inicis_payment($pay, $res, $_REQUEST);
            } else {

                $util = new INIStdPayUtil();

                if (empty($pay['res_result'])) {
                    try {
                        $res['pay_id'] = $_REQUEST['merchantData'];
                        $res['res_result'] = $_REQUEST['resultCode'];
                        $res['res_msg'] = $_REQUEST['resultMsg'];
                        if (strcmp("V801", $_REQUEST["resultCode"]) == 0) {
                            $res['status'] = 'CANCEL';
                            $this->payment_m->payment_response($res);
                            header('Location: /cart/cart_list?type=' . $pay['order_type']);
                        } else {
                            if ($pay['order_mem_type'] === 'guest') {
                                $pay['mem_id'] = $this->payment_m->payment_adduser($pay);
                            }
                            if (strcmp("0000", $_REQUEST["resultCode"]) == 0) {
                                //############################################
                                // 1.전문 필드 값 설정(***가맹점 개발수정***)
                                //############################################;

                                $mid = $_REQUEST["mid"];          // 가맹점 ID 수신 받은 데이터로 설정
                                $signKey = "SU5JTElURV9UUklQTEVERVNfS0VZU1RS";   // 가맹점에 제공된 키(이니라이트키) (가맹점 수정후 고정) !!!절대!! 전문 데이터로 설정금지
                                $timestamp = $util->getTimestamp();        // util에 의해서 자동생성
                                $charset = "UTF-8";               // 리턴형식[UTF-8,EUC-KR](가맹점 수정후 고정)
                                $format = "JSON";               // 리턴형식[XML,JSON,NVP](가맹점 수정후 고정)

                                $authToken = $_REQUEST["authToken"];       // 취소 요청 tid에 따라서 유동적(가맹점 수정후 고정)
                                $authUrl = $_REQUEST["authUrl"];         // 승인요청 API url(수신 받은 값으로 설정, 임의 세팅 금지)
                                $netCancel = $_REQUEST["netCancelUrl"];       // 망취소 API url(수신 받은f값으로 설정, 임의 세팅 금지)

                                $mKey = hash("sha256", $signKey);     // 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
                                //#####################
                                // 2.signature 생성
                                //#####################
                                $signParam["authToken"] = $authToken;   // 필수
                                $signParam["timestamp"] = $timestamp;   // 필수
                                // signature 데이터 생성 (모듈에서 자동으로 signParam을 알파벳 순으로 정렬후 NVP 방식으로 나열해 hash)
                                $signature = $util->makeSignature($signParam);

                                //#####################
                                // 3.API 요청 전문 생성
                                //#####################
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
                                    $resultMap['aid'] = '';
                                    // signature 데이터 생성 
                                    $secureSignature = $util->makeSignatureAuth($secureMap);
                                    /*                                     * ***********************  결제보안 추가 2016-05-18 END *************************** */
                                    if ((strcmp("0000", $resultMap["resultCode"]) == 0) && (strcmp($secureSignature, $resultMap["authSignature"]) == 0)) { //결제보안 추가 2016-05-18
                                        /*                                         * ***************************************************************************
                                         * 여기에 가맹점 내부 DB에 결제 결과를 반영하는 관련 프로그램 코드를 구현한다.  

                                          [중요!] 승인내용에 이상이 없음을 확인한 뒤 가맹점 DB에 해당건이 정상처리 되었음을 반영함
                                          처리중 에러 발생시 망취소를 한다.
                                         * **************************************************************************** */

                                        $res['status'] = 'OK';
                                        $this->payment_m->payment_response($res);
                                    } else {
                                        $res['status'] = 'FAIL';
                                        $res['res_result'] = @(in_array($resultMap["resultCode"], $resultMap) ? $resultMap["resultCode"] : "null");
                                        //결제보안키가 다른 경우.
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
                                        } else if (isset($resultMap["payMethod"]) && strcmp("VCard", $resultMap["payMethod"]) == 0) {//카드결제 ISP
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
                                        } else if (isset($resultMap["payMethod"]) && strcmp("Card", $resultMap["payMethod"]) == 0) {//카드결제 안심클릭
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
                                        $this->payment_m->payment_exec($res['res_result'], $pay, $resultMap);
                                        $this->session->set_userdata('cart', '');

                                        if ($pay['status'] == 'PAYMENT') {
                                            $this->order_sms($pay);
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
            $this->load->view('header_v', $this->data);
            $this->load->view('cart/order_complete_v');
            $this->load->view('footer_v');
        }
    }

    public function change_result_mo() {
        if (empty($_REQUEST['merchantreserved'])) {
            $this->data['msg'] = '잘못된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            $res = array();
            $res['pay_id'] = $_REQUEST['merchantreserved'];
            $res['res_result'] = $_REQUEST['resultcode'];
            $res['res_msg'] = $_REQUEST['resultmsg'];
            $pay = $this->payment_m->select_payment_temp($_REQUEST['merchantreserved']);
            if (empty($pay['res_result'])) {
                if (strcmp("01", $_REQUEST["resultcode"]) == 0) {
                    $res['status'] = 'CANCEL';
                    $res['result_msg'] = $_REQUEST['resultmsg'];
                    $this->payment_m->payment_response($res);
                    header('Location: /my/subscribe/detail/0?seq=' . $pay['delivery_period']);
                } else {
                    $res['status'] = 'OK';
                    if ($pay['order_mem_type'] === 'guest') {
                        $pay['mem_id'] = $this->payment_m->payment_adduser($pay);
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
                        ;
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
                        $pay['card_name'] = $map['P_FN_NM'];
                        $this->payment_m->change_exec($res['res_result'], $pay, $map);
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
            header('Location: /my/subscribe/detail/0?seq=' . $pay['delivery_period']);
        }
    }

    public function change_result() {
        if (empty($_REQUEST['merchantData'])) {
            $this->data['msg'] = '잘 못 된 접근입니다.';
            $this->load->view('header_v', $this->data);
            $this->load->view('errors/invalid_seq');
            $this->load->view('footer_v');
        } else {
            require_once('application/third_party/inicis/INIStdPayUtil.php');
            require_once('application/third_party/inicis/HttpClient.php');

            $util = new INIStdPayUtil();

            $pay = $this->payment_m->select_payment_temp($_REQUEST['merchantData']);
            $res = array();
            if (empty($pay['res_result'])) {
                try {
                    $res['pay_id'] = $_REQUEST['merchantData'];
                    $res['res_result'] = $_REQUEST['resultCode'];
                    $res['res_msg'] = $_REQUEST['resultMsg'];
                    if (strcmp("V801", $_REQUEST["resultCode"]) == 0) {
                        $res['status'] = 'CANCEL';
                        $this->payment_m->payment_response($res);
                        header('Location: /my/subscribe/detail/0?seq=' . $pay['delivery_period']);
                    } else {
                        if ($pay['order_mem_type'] === 'guest') {
                            $pay['mem_id'] = $this->payment_m->payment_adduser($pay);
                        }
                        if (strcmp("0000", $_REQUEST["resultCode"]) == 0) {
                            //############################################
                            // 1.전문 필드 값 설정(***가맹점 개발수정***)
                            //############################################;

                            $mid = $_REQUEST["mid"];          // 가맹점 ID 수신 받은 데이터로 설정
                            $signKey = "SU5JTElURV9UUklQTEVERVNfS0VZU1RS";   // 가맹점에 제공된 키(이니라이트키) (가맹점 수정후 고정) !!!절대!! 전문 데이터로 설정금지
                            $timestamp = $util->getTimestamp();        // util에 의해서 자동생성
                            $charset = "UTF-8";               // 리턴형식[UTF-8,EUC-KR](가맹점 수정후 고정)
                            $format = "JSON";               // 리턴형식[XML,JSON,NVP](가맹점 수정후 고정)

                            $authToken = $_REQUEST["authToken"];       // 취소 요청 tid에 따라서 유동적(가맹점 수정후 고정)
                            $authUrl = $_REQUEST["authUrl"];         // 승인요청 API url(수신 받은 값으로 설정, 임의 세팅 금지)
                            $netCancel = $_REQUEST["netCancelUrl"];       // 망취소 API url(수신 받은f값으로 설정, 임의 세팅 금지)

                            $mKey = hash("sha256", $signKey);     // 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
                            //#####################
                            // 2.signature 생성
                            //#####################
                            $signParam["authToken"] = $authToken;   // 필수
                            $signParam["timestamp"] = $timestamp;   // 필수
                            // signature 데이터 생성 (모듈에서 자동으로 signParam을 알파벳 순으로 정렬후 NVP 방식으로 나열해 hash)
                            $signature = $util->makeSignature($signParam);

                            //#####################
                            // 3.API 요청 전문 생성
                            //#####################
                            $authMap["mid"] = $mid;     // 필수
                            $authMap["authToken"] = $authToken;  // 필수
                            $authMap["signature"] = $signature;  // 필수
                            $authMap["timestamp"] = $timestamp;  // 필수
                            $authMap["charset"] = $charset;   // default=UTF-8
                            $authMap["format"] = $format;   // default=XML

                            try {

                                $httpUtil = new HttpClient();

                                //#####################
                                // 4.API 통신 시작
                                //#####################

                                $authResultString = "";

                                if ($httpUtil->processHTTP($authUrl, $authMap)) {
                                    $authResultString = $httpUtil->body;
                                } else {
                                    echo "Http Connect Error\n";
                                    echo $httpUtil->errormsg;

                                    throw new Exception("Http Connect Error");
                                }
                                //############################################################
                                //5.API 통신결과 처리(***가맹점 개발수정***)
                                //############################################################
                                //## 승인 API 결과 ##
                                $resultMap = json_decode($authResultString, true);

                                /*                                 * ***********************  결제보안 추가 2016-05-18 START *************************** */
                                $secureMap["mid"] = $mid;       //mid
                                $secureMap["tstamp"] = $timestamp;     //timestemp
                                $secureMap["MOID"] = $resultMap["MOID"];   //MOID
                                $secureMap["TotPrice"] = $resultMap["TotPrice"];  //TotPrice
                                // signature 데이터 생성 
                                $secureSignature = $util->makeSignatureAuth($secureMap);
                                /*                                 * ***********************  결제보안 추가 2016-05-18 END *************************** */

                                if ((strcmp("0000", $resultMap["resultCode"]) == 0) && (strcmp($secureSignature, $resultMap["authSignature"]) == 0)) { //결제보안 추가 2016-05-18
                                    /*                                     * ***************************************************************************
                                     * 여기에 가맹점 내부 DB에 결제 결과를 반영하는 관련 프로그램 코드를 구현한다.  

                                      [중요!] 승인내용에 이상이 없음을 확인한 뒤 가맹점 DB에 해당건이 정상처리 되었음을 반영함
                                      처리중 에러 발생시 망취소를 한다.
                                     * **************************************************************************** */

                                    $res['status'] = 'OK';
                                    $this->payment_m->payment_response($res);
                                } else {
                                    $res['res_result'] = @(in_array($resultMap["resultCode"], $resultMap) ? $resultMap["resultCode"] : "null");
                                    //결제보안키가 다른 경우.
                                    if (strcmp($secureSignature, $resultMap["authSignature"]) != 0) {
                                        $res['res_msg'] = '* 데이터 위변조 체크 실패';
                                        //망취소
                                        if (strcmp("0000", $resultMap["resultCode"]) == 0) {
                                            throw new Exception("데이터 위변조 체크 실패");
                                        }
                                    } else {
                                        $res['res_msg'] = @(in_array($resultMap["resultMsg"], $resultMap) ? $resultMap["resultMsg"] : "null");
                                    }
                                    $res['status'] = 'FAIL';
                                    $this->payment_m->payment_response($res);
                                }

                                if ($res['status'] = 'OK') {
                                    $pay['status'] = 'PAYMENT';
                                    if (isset($resultMap["payMethod"]) && strcmp("Auth", $resultMap["payMethod"]) == 0) {//빌링결제
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
                                    }

                                    // 수신결과를 파싱후 resultCode가 "0000"이면 승인성공 이외 실패
                                    // 가맹점에서 스스로 파싱후 내부 DB 처리 후 화면에 결과 표시
                                    // payViewType을 popup으로 해서 결제를 하셨을 경우
                                    // 내부처리후 스크립트를 이용해 opener의 화면 전환처리를 하세요
                                    //throw new Exception("강제 Exception");
                                    $pay['card_name'] = $resultMap['P_FN_NM'];
                                    $this->payment_m->change_exec($res['res_result'], $pay, $resultMap);
                                    //								$this->session->unset_userdata('cart');
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
            header('Location: /my/subscribe/detail/0?seq=' . $pay['delivery_period']);
        }
    }

    public function ajaxMemberCheck() {
        $req = $this->input->post();

        $result = array();

        if (empty($req)) {
            $result['status'] = 'fail';
            $result['msg'] = '잘못된 접근입니다.';
            $result['target'] = 'mem_phone';
        } else if (empty($req['auth'])) {
            $result['status'] = 'fail';
            $result['msg'] = '휴대폰 인증을 해주세요.';
            $result['target'] = 'mem_phone';
        } else if (empty($req['mem_phone'])) {
            $result['status'] = 'fail';
            $result['msg'] = '회원정보의 휴대폰번호를 입력해주세요.';
            $result['target'] = 'mem_phone';
        } else if (empty(trim($req['mem_name']))) {
            $result['status'] = 'fail';
            $result['msg'] = '회원정보의 회원이름을 입력해주세요.';
            $result['target'] = 'mem_name';
        } else if (empty(trim($req['mem_email']))) {
            $result['status'] = 'fail';
            $result['msg'] = '회원정보의 이메일을 입력해주세요.';
            $result['target'] = 'mem_email';
        } else if (empty($req['mem_password'])) {
            $result['status'] = 'fail';
            $result['msg'] = '비밀번호를 입력해 주세요.';
            $result['target'] = 'mem_password';
        } else if (empty($req['mem_password']) || $this->common->passwordCheck($req['mem_password']) === false) {//preg_match('/^.*(?=^.{6,15}$)(?=.*\d)(?=.*[a-zA-Z]).*$/', $req['mem_password']) === false) {
            $result['status'] = 'fail';
            $result['msg'] = '비밀번호는 숫자,영문을 조합한 6~15자리로 입력하여 주세요.';
            $result['target'] = 'mem_password';
        } else if ($req['mem_password'] !== $req['password_confirm']) {
            $result['status'] = 'fail';
            $result['msg'] = '비밀번호 확인이 일치하지 않습니다.';
            $result['target'] = 'password_confirm';
        } else if (!isset($req['terms'])) {
            $result['status'] = 'fail';
            $result['msg'] = '이용약관에 동의해 주세요.';
            $result['target'] = 'terms';
        } else if (!isset($req['private'])) {
            $result['status'] = 'fail';
            $result['msg'] = '개인정보처 수집 및 이용에 동의해 주세요.';
            $result['target'] = 'private';
        } else if (!isset($req['overage'])) {
            $result['status'] = 'fail';
            $result['msg'] = '만 14세 이상임을 확인해 주세요.';
            $result['target'] = 'overage';
        } else {
            $member = $this->payment_m->member_exists($req['mem_email'])->row_array();
            if (empty($member)) {
                $result['status'] = 'succ';
                $result['msg'] = '';
            } else {
                $result['status'] = 'fail';
                $result['msg'] = '이미 존재하는 아이디 입니다.';
                $result['target'] = 'mem_email';
            }
        }
        echo json_encode($result);
    }

    private function billing_payment_kakao($pay,&$map) {
        $util = new INIStdPayUtil();

        $billing = $this->payment_m->select_subscribe_info($pay['order_id'])->row_array();

        $sub = array();
        $sub['csu_id'] = $billing['csu_id'];
        $sub['order_id'] = 'CDA' . $util->getTimestamp();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, KAKAO_BASE_URL . '/v1/payment/subscription');
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        $aPostData = array();
        $aPostData['cid'] = KAKAO_CID_SUBSCRIP;
        $aPostData['sid'] = $billing['billing_key'];
        $aPostData['partner_order_id'] = $sub['order_id'];
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
        $sub['order_id'] = 'CDA' . $util->getTimestamp();

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
            $url = NAVERPAY_BASE_URL_BILL.'/naverpay/payments/recurrent/pay/v3/approval';
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
                $sub['card_name'] = (isset($naverpay_card_com[$sub['card_code']])) ? $naverpay_card_com[$sub['card_code']] : '';
            } else {
                $sub['result_code'] = $recv['code'];
                $sub['result_msg'] = $recv['message'];
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
                'order_id' => $sub['order_id'],
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
            $req['result_code'] = $result->resultCode;
            $req['result_msg'] = $result->resultMsg;
            $req['card_no'] = $result->cardNumber;
            $req['card_name'] = '';
            $req['card_code'] = $result->cardCode;
            $this->payment_m->insert_subscribe_hitory($req);
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

    public function vbank_complete() {
        @extract($_SERVER);

        $TEMP_IP = getenv("REMOTE_ADDR");
        $PG_IP = substr($TEMP_IP, 0, 10);

//		if($PG_IP == "203.238.37" || $PG_IP == "39.115.212" || $PG_IP == "183.109.71" || $PG_IP == '121.133.10') //PG에서 보냈는지 IP로 체크
//		{
        // 이니시스 NOTI 서버에서 받은 Value
        $req['no_tid'] = $_REQUEST["no_tid"];                      // 거래TID
        $req['no_oid'] = $_REQUEST["no_oid"];                    // 상점주문번호
        $req['cd_bank'] = $_REQUEST["cd_bank"];                // 은행코드
        $req['cd_deal'] = $_REQUEST["cd_deal"];                  // 거래취급 기관코드(실제입금은행)
        $req['dt_trans'] = $_REQUEST["dt_trans"];                 // 금융기관 발생 거래일자
        $req['tm_trans'] = $_REQUEST["tm_trans"];               // 금융기관 발생 거래시각
        $req['no_vacct'] = $_REQUEST["no_vacct"];               // 계좌번호
        $req['amt_input'] = $_REQUEST["amt_input"];           // 입금금액
        $req['flg_close'] = $_REQUEST["flg_close"];               // 마감구분[0:당일마감전, 1:당일마감후]
        $req['cl_close'] = $_REQUEST["cl_close"];                  // 마감구분코드[0:당일마감전, 1:당일마감후]
        $req['type_msg'] = $_REQUEST["type_msg"];             // 거래구분[0200:정상]
        $req['nm_inputbank'] = iconv("EUC-KR", "UTF-8", $_REQUEST["nm_inputbank"]); // 입금은행명
        $req['nm_input'] = iconv("EUC-KR", "UTF-8", $_REQUEST["nm_input"]);             // 입금자명
        $req['dt_inputstd'] = $_REQUEST["dt_inputstd"];        // 입금기준일자
        $req['dt_calculstd'] = $_REQUEST["dt_calculstd"];       // 정산기준일자
        $req['dt_transbase'] = $_REQUEST["dt_transbase"];     // 거래기준일자
        $req['cl_trans'] = $_REQUEST["cl_trans"];                  // 거래구분코드
        $req['cl_kor'] = $_REQUEST["cl_kor"];                      // 한글구분코드
        $req['dt_cshr'] = $_REQUEST["dt_cshr"];                   // 현금영수증 발급일자
        $req['tm_cshr'] = $_REQUEST["tm_cshr"];                 // 현금영수증 발급시간
        $req['no_cshr_appl'] = $_REQUEST["no_cshr_appl"];   // 현금영수증 발급번호
        $req['no_cshr_tid'] = $_REQUEST["no_cshr_tid"];       // 현금영수증 발급TID
        // if(데이터베이스 등록 성공 유무 조건변수 = true) 
        // 주의 : DB처리후 정상일경우만 OK출력

        $device_type = 'PC';
        $res = $this->payment_m->insert_vbank_log($device_type, $req);

        if ($res)
            echo "OK";
        else
            echo 'FAIL';
        // else
        //echo "FAIL";
//		}
//		else {
//			echo 'FAIL';	
//		} 
    }

    public function vbank_complete_mo() {
        @extract($_SERVER);

        $TEMP_IP = getenv("REMOTE_ADDR");
        $PG_IP = substr($TEMP_IP, 0, 10);

//		if($PG_IP == "203.238.37" || $PG_IP == "39.115.212" || $PG_IP == "183.109.71" || $PG_IP == '121.133.10') //PG에서 보냈는지 IP로 체크
//		{

        $req['P_STATUS'] = $_REQUEST["P_STATUS"];                         // 거래상태 [00:가상계좌 채번, 02:가상계좌입금통보]
        $req['P_TID'] = $_REQUEST["P_TID"];                                     // 거래TID
        $req['P_TYPE'] = $_REQUEST["P_TYPE"];                                 // 지불수단[VBANK: 가상계좌]
        $req['P_AUTH_DT'] = $_REQUEST["P_AUTH_DT"];                     // 승인일자 [YYMMDDhhmmss]
        $req['P_MID'] = $_REQUEST["P_MID"];                                   // 상점아이디
        $req['P_OID'] = $_REQUEST["P_OID"];                                    // 상점주문번호
        $req['P_FN_CD1'] = $_REQUEST["P_FN_CD1"];                         // 은행코드
        $req['P_FN_CD2'] = $_REQUEST["P_FN_CD2"];                         // 금융사코드 (빈값으로 전달)
        $req['P_FN_NM'] = iconv("EUC-KR", "UTF-8", $_REQUEST["P_FN_NM"]);                          // 은행명
        $req['P_AMT'] = $_REQUEST["P_AMT"];                                 // 거래금액
        $req['P_UNAME'] = iconv("EUC-KR", "UTF-8", $_REQUEST["P_UNAME"]);                         // 주문자명
        $req['P_RMESG1'] = $_REQUEST["P_RMESG1"];                        // 메시지1 [채번된 가상계좌번호|입금기한]
        $req['P_RMESG2'] = $_REQUEST["P_RMESG2"];                        // 메시지2 (빈값전달)
        $req['P_NOTI'] = $_REQUEST["P_NOTI"];                                // 주문정보 [거래요청시 입력한 P_NOTI 값을 그대로 반환합니다]
        $req['P_AUTH_NO'] = $_REQUEST["P_AUTH_NO"];                    // 승인번호 (빈값전달)
        $req['P_CSHR_AMT'] = $_REQUEST["P_CSHR_AMT"];                 // 현금영수증 거래 금액
        $req['P_CSHR_SUP_AMT'] = $_REQUEST["P_CSHR_SUP_AMT"];    // 현금영수증 공급가액
        $req['P_CSHR_TAX'] = $_REQUEST["P_CSHR_TAX"];                   // 현금영수증 부가가치세
        $req['P_CSHR_SRVC_AMT'] = $_REQUEST["P_CSHR_SRVC_AMT"]; // 현금영수증 봉사료
        $req['P_CSHR_TYPE'] = $_REQUEST["P_CSHR_TYPE"];                 // 현금영수증 거래자 구분 [0:소비자소득공제용, 1:사업자지출증빙용]
        $req['P_CSHR_DT'] = $_REQUEST["P_CSHR_DT"];                      // 현금영수증 발행일자 [YYYYMMDDhhmmss]
        $req['P_CSHR_AUTH_NO'] = $_REQUEST["P_CSHR_AUTH_NO"];   // 현금영수증 발행승인번호

        $device_type = 'MO';
        $res = $this->payment_m->insert_vbank_log($device_type, $req);

        if ($res)
            echo "OK";
        else
            echo 'FAIL';
        // else
        //echo "FAIL";
//		}
//		else {
//			echo 'FAIL';	
//		}
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
            'Authorization: KakaoAK '.KAKAO_APP_KEY,
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

    public function inicis() {
        $res = $this->payment_m->payment_inicis($_REQUEST);
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
        $aPostData['expireReason'] = '결제실패에 의한 시스템 구독해지';

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aPostData, '', '&'));

        $gData = curl_exec($curl);

        curl_close($curl);
    }
    
}
