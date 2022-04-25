<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payment_m extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function member_exists($id) {
        $sql = "SELECT 
					t1.mem_id
					, FN_DECRYPT(t1.mem_userid) as mem_userid
				FROM 
					member t1
				WHERE
					t1.mem_userid = FN_ENCRYPT(?)";
        return $this->db->query($sql, array($id));
    }

    public function payment_request($req, &$pay) {

        $this->db->trans_begin();

        $pay['buyername'] = $req['mem_name'];
        $pay['buyertel'] = $req['mem_phone'];
        $pay['buyeremail'] = $req['mem_email'];
        if (count($req['cit_name']) == 1) {
            $pay['goodname'] = $req['cit_name'][0];
        } else {
            $pay['goodname'] = $req['cit_name'][0] . ' 외' . (count($req['cit_name']) - 1) . '건';
        }

        $sum = 0;
        foreach ($req['qty'] as $row) {
            $sum += $row;
        }
        if ($req['cart_type'] === 'subscribe') {
            $delivery_day = $req['delivery_day'];
            $delivery_period = $req['delivery_period'];
            $start_date = $req['start_date'];
        } else {
            $delivery_day = '';
            $delivery_period = 0;
            $start_date = '';
        }

        $sql = "insert into 
					cmall_payment_temp
				(
					order_id
					, order_type
					, order_mem_type
					, mem_id
					, mem_username
					, mem_phone
					, mem_email
					, mem_password
					, product_price
					, total_price
					, total_qty
					, use_point
					, delivery_price
					, recipient_name
					, recipient_phone
					, recipient_zip
					, recipient_addr1
					, recipient_addr2
					, recipient_memo
					, product_name
					, payment_type
					, mKey
					, sign
					, req_dtm
					, status
					, delivery_day
					, delivery_period
					, start_date
					, device_type
					, use_coupon
					, use_coupon_id
					, use_coupon_type
				)
				VALUES
				(
					?
					, ?
					, ?
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, SHA2(?, 512)
					, ?
					, ?
					, ?
					, ?
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, ?
					, ?
					, ?
					, ?
					, ?
					, now()
					, 'REQ'
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
				)";

        $this->db->query($sql, array($pay['oid']
            , $req['cart_type']
            , $req['order_mem_type']
            , $req['mem_id']
            , trim($req['mem_name'])
            , $req['mem_phone']
            , trim($req['mem_email'])
            , $req['mem_password']
            , $req['product_price']
            , $req['total_price']
            , $sum
            , $req['use_point']
            , $req['set_delivery']
            , $req['recipient_name']
            , $req['recipient_phone']
            , $req['zipcode']
            , $req['road_addr']
            , $req['addr2']
            , $req['memo']
            , $pay['goodname']
            , $pay['gopaymethod']
            , $pay['mKey']
            , $pay['signature']
            , $delivery_day
            , $delivery_period
            , $start_date
            , $req['device_type']
            , $req['use_coupon']
            , $req['use_coupon_id']
            , $req['use_coupon_type']));
        $pay_id = $this->db->insert_id();
        $pay['merchantData'] = $pay_id;

        $item = array();
        for ($i = 0; $i < count($req['cit_id']); $i++) {
            $tmp = "(" . $pay_id . ", " . $req['cct_id'][$i] . ", " . $req['cit_id'][$i] . ", " . $req['cde_id'][$i] . ", '" . $req['cit_name'][$i] . "', " . $req['cit_price'][$i] . ", " . $req['cit_sale_price'][$i] . ", 
					" . $req['cit_subscribe_price'][$i] . ", " . $req['qty'][$i] . ", '" . $req['cde_title'][$i] . "', '" . $req['product_code'][$i] . "', '" . $req['barcode_no'][$i] . "', now())";
            $item[] = $tmp;
        }
        $sql = "insert into cmall_payment_temp_detail
				(pay_id
				, cct_id
				, cit_id
				, cde_id
				, cit_name
				, cit_price
				, cit_sale_price
				, cit_subscribe_price
				, qty
				, cde_title
				, product_code
				, barcode_no
				, ins_dtm)
				VALUES " . implode(',', $item);
        $this->db->query($sql, array());

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function change_request($req, &$pay) {

        $this->db->trans_begin();

        $pay['buyername'] = $req['mem_name'];
        $pay['buyertel'] = $req['mem_phone'];
        $pay['buyeremail'] = $req['mem_email'];
        $pay['goodname'] = $req['csu_title'];

        $sql = "insert into 
					cmall_payment_temp
				(
					order_id
					, order_type
					, order_mem_type
					, mem_id
					, mem_username
					, mem_phone
					, mem_email
					, mem_password
					, product_price
					, total_price
					, total_qty
					, use_point
					, delivery_price
					, recipient_name
					, recipient_phone
					, recipient_zip
					, recipient_addr1
					, recipient_addr2
					, recipient_memo
					, product_name
					, payment_type
					, mKey
					, sign
					, req_dtm
					, status
					, delivery_day
					, start_date
					, delivery_period
					, device_type
				)
				VALUES
				(
					?
					, ?
					, 'member'
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, ''
					, ?
					, ?
					, ?
					, 0
					, 0
					, ''
					, ''
					, ''
					, ''
					, ''
					, ''
					, ?
					, ?
					, ?
					, ?
					, now()
					, 'REQ'
					, ''
					, ''
					, ?
					, ?
				)";

        $this->db->query($sql, array($pay['oid']
            , $req['cart_type']
            , $req['mem_id']
            , $req['mem_name']
            , $req['mem_phone']
            , $req['mem_email']
            , $req['total_price']
            , $req['total_price']
            , $req['total_qty']
            , $pay['goodname']
            , $pay['gopaymethod']
            , $pay['mKey']
            , $pay['signature']
            , $req['csu_id']
            , $req['device_type']));
        $pay_id = $this->db->insert_id();
        $pay['merchantData'] = $pay_id;

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function payment_response($val) {
        $sql = "update cmall_payment_temp
				set
					res_result = ?
					, res_msg = ?
					, res_dtm = now()
					, status = ? 
				where
					pay_id = ? ";
        $this->db->query($sql, array($val['res_result'], $val['res_msg'], $val['status'], $val['pay_id']));
    }

    public function select_payment_temp($seq) {
        $sql = "select
					t1.pay_id
					, t1.order_id
					, t1.order_type
					, t1.order_mem_type
					, t1.mem_id
                                        , t1.tid 
					, FN_DECRYPT(t1.mem_username) as mem_username
					, FN_DECRYPT(t1.mem_phone) as mem_phone
					, FN_DECRYPT(t1.mem_email) as mem_email
					, mem_password
					, product_price
					, total_price
					, total_qty
					, use_point
					, delivery_price
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, FN_DECRYPT(t1.recipient_phone) as recipient_phone
					, FN_DECRYPT(t1.recipient_zip) as recipient_zip
					, FN_DECRYPT(t1.recipient_addr1) as recipient_addr1
					, FN_DECRYPT(t1.recipient_addr2) as recipient_addr2
					, recipient_memo
					, payment_type
					, product_name
					, mKey
					, sign
					, req_dtm
					, status
					, delivery_day
					, delivery_period
					, start_date
					, ifnull(t1.res_result, '') as res_result
					, ifnull(t1.res_msg, '') as res_msg
					, use_coupon
					, use_coupon_id
					, use_coupon_type 
				from 
					cmall_payment_temp t1
				where
					t1.pay_id = ? ";
        $result = $this->db->query($sql, array($seq))->row_array();

        $sql = "select
					t1.cpd_id
					, t1.pay_id
					, t1.cct_id
					, t1.cit_id
					, t1.cde_id
					, t1.cit_name
					, t1.cit_price
					, t1.cit_sale_price
					, t1.cit_subscribe_price
					, t1.qty
					, t1.cde_title
					, t1.product_code
					, t1.barcode_no
					, t1.ins_dtm
					, t2.cit_file_1
					, t2.is_sale
				from
					cmall_payment_temp_detail t1
				inner join cmall_item t2 on t2.cit_id = t1.cit_id 
				where 
					t1.pay_id = ? ";
        $result['list'] = $this->db->query($sql, array($seq))->result_array();

        return $result;
    }
    
    public function select_payment_temp_inicis($seq) {
        $sql = "select
					t1.pay_id
					, t1.order_id
					, t1.order_type
					, t1.order_mem_type
					, t1.mem_id
                                        , t1.tid 
					, FN_DECRYPT(t1.mem_username) as mem_username
					, FN_DECRYPT(t1.mem_phone) as mem_phone
					, FN_DECRYPT(t1.mem_email) as mem_email
					, mem_password
					, product_price
					, total_price
					, total_qty
					, use_point
					, delivery_price
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, FN_DECRYPT(t1.recipient_phone) as recipient_phone
					, FN_DECRYPT(t1.recipient_zip) as recipient_zip
					, FN_DECRYPT(t1.recipient_addr1) as recipient_addr1
					, FN_DECRYPT(t1.recipient_addr2) as recipient_addr2
					, recipient_memo
					, payment_type
					, product_name
					, mKey
					, sign
					, req_dtm
					, status
					, delivery_day
					, delivery_period
					, start_date
					, ifnull(t1.res_result, '') as res_result
					, ifnull(t1.res_msg, '') as res_msg
					, use_coupon
					, use_coupon_id
					, use_coupon_type 
                                        , FN_DECRYPT(cardNumber) as cardNumber
                                        , FN_DECRYPT(cardExpire) as cardExpire
                                        , FN_DECRYPT(regNo) as regNo 
                                        , FN_DECRYPT(cardPw) as cardPw 
				from 
					cmall_payment_temp t1
				where
					t1.pay_id = ? ";
        $result = $this->db->query($sql, array($seq))->row_array();

        $sql = "select
					t1.cpd_id
					, t1.pay_id
					, t1.cct_id
					, t1.cit_id
					, t1.cde_id
					, t1.cit_name
					, t1.cit_price
					, t1.cit_sale_price
					, t1.cit_subscribe_price
					, t1.qty
					, t1.cde_title
					, t1.product_code
					, t1.barcode_no
					, t1.ins_dtm
					, t2.cit_file_1
					, t2.is_sale
				from
					cmall_payment_temp_detail t1
				inner join cmall_item t2 on t2.cit_id = t1.cit_id 
				where 
					t1.pay_id = ? ";
        $result['list'] = $this->db->query($sql, array($seq))->result_array();

        return $result;
    }
    
    public function payment_kakao($val) {
        $sql = "update cmall_payment_temp
                                    set
                                            tid = ?
                                    where
                                            pay_id = ? ";
        $this->db->query($sql, array($val['tid'], $val['pid']));
    }

    public function payment_inicis($val) {
        $sql = "update cmall_payment_temp
                                    set 
                                        cardNumber = FN_ENCRYPT(?),
                                        cardExpire = FN_ENCRYPT(?),
                                        regNo = FN_ENCRYPT(?),
                                        cardPw = FN_ENCRYPT(?)
                                    where 
                                        pay_id = ?
                ";
        $this->db->query($sql, array($val['cardNumber'], $val['cardExpire'], $val['regNo'], $val['cardPw'], $val['pid']));
    }

    public function payment_easypay_subscript($val) {
        $sql = "update cmall_order
                                    set
                                            sid = ?
                                    where
                                            order_id = ? ";
        $this->db->query($sql, array($val['sid'], $val['order_id']));
    }

    public function payment_exec($status, &$req, $map) {
        $this->db->trans_begin();

        $sql = "insert into 
					cmall_order
				(
					order_id
					, order_type
					, mem_id
					, mem_username
					, mem_email
					, mem_phone
					, product_price
					, total_price
					, total_qty
					, use_point
					, delivery_price
					, recipient_name
					, recipient_phone
					, recipient_zip
					, recipient_addr1
					, recipient_addr2
					, recipient_memo
					, product_name
					, status
					, tid
					, payMethod
					, card_code
					, card_name
					, card_num
					, vbank_name
					, vbank_code
					, vbank_owner
					, vbank_num
					, vbank_sender
					, vbank_date
					, bank_name
					, bank_code
					, bank_num
					, bank_billscode
					, bank_billstype
					, payDevice
					, applDate
					, applTime
					, ins_dtm
					, upd_dtm
					, use_coupon
					, use_coupon_id
					, use_coupon_type
                                        , aid 
				)
				VALUES
				(
					?
					, ?
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, ?
					, ?
					, ?
					, ?
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?

					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?

					, ?
					, ?
					, ?
					, now()
					, now()

					, ?
					, ?
					, ?
                                        , ?
				)";

        $this->db->query($sql, array($req['order_id']
            , $req['order_type']
            , $req['mem_id']
            , $req['mem_username']
            , $req['mem_email']
            , $req['mem_phone']
            , $req['product_price']
            , $req['total_price']
            , $req['total_qty']
            , $req['use_point']
            , $req['delivery_price']
            , $req['recipient_name']
            , $req['recipient_phone']
            , $req['recipient_zip']
            , $req['recipient_addr1']
            , $req['recipient_addr2']
            , $req['recipient_memo']
            , $map['goodsName']
            , $req['status']
            , $map['tid']
            , $map['payMethod']
            , $map['CARD_Code']
            , $map['P_FN_NM']
            , $map['CARD_Num']
            , $map['vactBankName']
            , $map['VACT_BankCode']
            , $map['VACT_Name']
            , $map['VACT_Num']
            , $map['VACT_InputName']
            , $map['VACT_Date']
            , $map['ACCT_BankName']
            , $map['ACCT_BankCode']
            , $map['ACCT_Num']
            , $map['CSHR_ResultCode']
            , $map['CSHR_Type']
            , $map['payDevice']
            , $map['applDate']
            , $map['applTime']
            , $req['use_coupon']
            , $req['use_coupon_id']
            , $req['use_coupon_type']
            , $map['aid']));

        $item = array();
        $cart = array();
        foreach ($req['list'] as $row) {
            $tmp = "('" . $req['order_id'] . "', " . $row['cit_id'] . ", " . $row['cde_id'] . ", '" . $row['cit_name'] . "', " . $row['cit_price'] . ", " . $row['cit_sale_price'] . ", 
					" . $row['cit_subscribe_price'] . ", " . $row['qty'] . ", '" . $row['cde_title'] . "', '" . $row['product_code'] . "', '" . $row['barcode_no'] . "', now())";
            $item[] = $tmp;
            $cart[] = $row['cct_id'];
        }

        $sql = "insert into cmall_order_detail
				(order_id
				, cit_id
				, cde_id
				, cit_name
				, cit_price
				, cit_sale_price
				, cit_subscribe_price
				, qty
				, cde_title
				, product_code
				, barcode_no
				, ins_dtm)
				VALUES " . implode(',', $item);
        $this->db->query($sql, array());

        if ($req['use_point'] > 0) {
            $sql = "update member
					set
						mem_point = mem_point - " . $req['use_point'] . "
					where
						mem_id = ? ";
            $this->db->query($sql, array($req['mem_id']));

            $sql = "insert into
						member_point_log
					(
						mem_id
						, point_type
						, point_val
						, point_dir
						, ins_dtm
					)
					VALUES
					(
						?
						, '구매시 포인트 사용'
						, ?
						, 'minus'
						, now()
					) ";
            $this->db->query($sql, array($req['mem_id'], $req['use_point']));

            $sql = "SELECT * FROM member_point where mem_id = ? and add_val > use_val and exp_dtm >= date_format(now(), '%Y-%m-%d') order by exp_dtm ";
            $res = $this->db->query($sql, array($req['mem_id']))->result_array();
            $use_point = $req['use_point'];
            foreach ($res as $row) {
                $rest_val = $row['add_val'] - $row['use_val'];
                if ($use_point > $rest_val) {
                    $this->db->where('mpo_id', $row['mpo_id']);
                    $this->db->set('use_val', $row['add_val']);
                    $this->db->update('member_point');
                } else {
                    $this->db->where('mpo_id', $row['mpo_id']);
                    $this->db->set('use_val', $use_point + $row['use_val']);
                    $this->db->update('member_point');
                    break;
                }
                $use_point = $use_point - $rest_val;
            }
        }

        if (!empty($req['use_coupon_id'])) {
            $sql = "update cmall_coupon_log
					set
						is_use = 'y'
						, use_dtm = now()
						, use_val = ?
						, order_id = ?
					where
						ccl_id = ? ";
            $this->db->query($sql, array($req['use_coupon'], $req['order_id'], $req['use_coupon_id']));
        }

        if ($req['order_type'] == 'subscribe') {
            $sql = "insert into
						cmall_subscribe
					(
						csu_title
						, order_id
						, mem_id
						, delivery_day
						, delivery_period
						, start_date
						, ins_dtm
						, billing_key
						, card_num
						, card_name
						, card_code
						, recipient_name
						, recipient_phone
						, recipient_zip
						, recipient_addr1
						, recipient_addr2
						, recipient_memo 
                                                , payMethod
					)
					VALUES
					(
						?
						, ?
						, ?
						, ?
						, ?
						, ?
						, now()
						, ?
						, ?
						, ?
						, ?
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, ?
                                                , ?
					)";
            $this->db->query($sql, array($map['goodsName'], $req['order_id'], $req['mem_id'], $req['delivery_day'], $req['delivery_period'], $req['start_date'], $map['CARD_BillKey'],
                $map['CARD_Num'], $map['P_FN_NM'], $map['CARD_Code'], $req['recipient_name'], $req['recipient_phone'], $req['recipient_zip'], $req['recipient_addr1'],
                $req['recipient_addr2'], $req['recipient_memo'], $map['payMethod']));
            $csu_id = $this->db->insert_id();

            $req['csu_id'] = $csu_id;

            $item2 = array();
            foreach ($req['list'] as $row) {
                $tmp = "('" . $csu_id . "', " . $row['cit_id'] . ", " . $row['cde_id'] . ", '" . $row['cit_name'] . "', " . $row['cit_price'] . ", " . $row['cit_sale_price'] . ", 
						" . $row['cit_subscribe_price'] . ", " . $row['qty'] . ", '" . $row['cde_title'] . "', '" . $row['product_code'] . "', '" . $row['barcode_no'] . "', now())";
                $item2[] = $tmp;
            }

            $sql = "insert into cmall_subscribe_detail
					(csu_id
					, cit_id
					, cde_id
					, cit_name
					, cit_price
					, cit_sale_price
					, cit_subscribe_price
					, qty
					, cde_title
					, product_code
					, barcode_no
					, ins_dtm)
					VALUES " . implode(',', $item2);
            $this->db->query($sql, array());

            $sql = "select count(*) as cnt from cmall_subscribe where mem_id = ? ";
            $tmp = $this->db->query($sql, array($req['mem_id']))->row_array();
            if ($tmp['cnt'] == 1) {
                $sql = "SELECT
							*
						FROM
							(SELECT
								t2.ccp_id
								, t2.ccp_name
								, t2.ccp_type
								, t2.down_type
								, t2.point_type
								, t2.price_type
								, t2.event_type
								, t2.use_start_date
								, t2.use_start_time
								, t2.use_end_date
								, t2.use_end_time
								, t2.ccp_val
								, t2.use_max
								, t2.max_val
								, t2.use_min
								, t2.min_val
								, ifnull((select a.ccl_id from cmall_coupon_log a where a.ccp_id = t2.ccp_id and a.mem_id = ?), '') as ccl_id
							FROM
								cmall_coupon t2
							WHERE
								date_format(now(), '%Y-%m-%d %H:%i') between concat(t2.down_start_date, ' ', t2.down_start_time) and concat(t2.down_end_date, ' ', t2.down_end_time) 
								and down_type = '2'
								and event_type = '1'
								and is_delete = 'n'
						) TB1
						WHERE
							TB1.ccl_id = '' ";
                $coupon = $this->db->query($sql, array($req['mem_id']))->result_array();

                if (!empty($coupon)) {
                    foreach ($coupon as $row) {
                        if ($row['ccp_type'] === '3' && $row['point_type'] === '1') {
                            $this->db->set('mem_id', $req['mem_id']);
                            $this->db->set('ccp_id', $row['ccp_id']);
                            $this->db->set('is_use', 'y');
                            $this->db->set('use_dtm', 'now()', false);
                            $this->db->set('use_val', $row['ccp_val']);
                            $this->db->set('down_type', 'auto');
                            $this->db->set('down_dtm', 'now()', false);
                            $this->db->set('down_user', '');
                            $this->db->insert('cmall_coupon_log');

                            $sql = "update member
									set
										mem_point = mem_point + " . $row['ccp_val'] . "
									where 
										mem_id = ? ";
                            $this->db->query($sql, array($req['mem_id']));

                            $sql = "insert into member_point
									(
										mem_id
										, add_type
										, add_val
										, rest_val
										, use_val
										, ins_dtm
										, exp_dtm
									)
									values
									(
										?
										, 'coupon'
										, " . $row['ccp_val'] . "
										, 0
										, 0
										, now()
										, date_format(date_add(now(), INTERVAL 1 YEAR), '%Y-%m-%d')
									) ";
                            $this->db->query($sql, array($req['mem_id']));

                            $sql = "insert into member_point_log
									(
										mem_id
										, point_type
										, point_val
										, point_dir
										, ins_dtm
										, user_type
										, ins_user
									)
									values
									(
										?
										, '쿠폰증정(자동)'
										, " . $row['ccp_val'] . "
										, 'plus'
										, now()
										, 'admin'
										, ?
									) ";
                            $this->db->query($sql, array($req['mem_id'], $req['mem_id']));
                        } else {
                            $this->db->set('mem_id', $req['mem_id']);
                            $this->db->set('ccp_id', $row['ccp_id']);
                            $this->db->set('is_use', 'n');
                            $this->db->set('down_type', 'auto');
                            $this->db->set('down_dtm', 'now()', false);
                            $this->db->set('down_user', '');
                            $this->db->insert('cmall_coupon_log');
                        }
                    }
                }
            }
        }

        $sql = "update cmall_cart
				set
					is_order = 'y'
				where
					cct_id in (" . implode(',', $cart) . ") ";
        $this->db->query($sql, array());

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function change_exec($status, $req, $map) {
        $this->db->trans_begin();

        $sql = "insert into 
					cmall_order
				(
					order_id
					, order_type
					, mem_id
					, mem_username
					, mem_email
					, mem_phone
					, product_price
					, total_price
					, total_qty
					, use_point
					, delivery_price
					, recipient_name
					, recipient_phone
					, recipient_zip
					, recipient_addr1
					, recipient_addr2
					, recipient_memo
					, product_name
					, status
					, tid
					, payMethod
					, card_code
					, card_name
					, card_num
					, vbank_name
					, vbank_code
					, vbank_owner
					, vbank_num
					, vbank_sender
					, vbank_date
					, bank_name
					, bank_code
					, bank_num
					, bank_billscode
					, bank_billstype
					, payDevice
					, applDate
					, applTime
					, ins_dtm
					, upd_dtm
				)
				VALUES
				(
					?
					, ?
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, ?
					, ?
					, ?
					, 0
					, 0
					, ''
					, ''
					, ''
					, ''
					, ''
					, ''
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?

					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?

					, ?
					, ?
					, ?
					, now()
					, now()
				)";

        $this->db->query($sql, array($req['order_id']
            , $req['order_type']
            , $req['mem_id']
            , $req['mem_username']
            , $req['mem_phone']
            , $req['mem_email']
            , $req['total_price']
            , $req['total_price']
            , $req['total_qty']
            , $map['goodsName']
            , $req['status']
            , $map['tid']
            , $map['payMethod']
            , $map['CARD_Code']
            , $map['P_FN_NM']
            , $map['CARD_Num']
            , $map['vactBankName']
            , $map['VACT_BankCode']
            , $map['VACT_Name']
            , $map['VACT_Num']
            , $map['VACT_InputName']
            , $map['VACT_Date']
            , $map['ACCT_BankName']
            , $map['ACCT_BankCode']
            , $map['ACCT_Num']
            , $map['CSHR_ResultCode']
            , $map['CSHR_Type']
            , $map['payDevice']
            , $map['applDate']
            , $map['applTime']));

        $this->db->reset_query();
        $this->db->where('csu_id', $req['delivery_period']);
        $this->db->set('card_code', $map['CARD_Code']);
        $this->db->set('card_name', $map['P_FN_NM']);
        $this->db->set('card_num', $map['CARD_Num']);
        $this->db->set('billing_key', $map['CARD_BillKey']);
        $this->db->update('cmall_subscribe');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function payment_adduser($val) {
        $sql = "select
					FN_DECRYPT(t1.mem_id) as mem_id
				from
					member t1
				where
					FN_DECRYPT(t1.mem_userid) = ? ";
        $res = $this->db->query($sql, array($val['mem_email']))->row_array();
        if (empty($res)) {
            $sql = "insert into
						member
					(
						mem_userid
						, mem_email
						, mem_password
						, mem_username
						, mem_phone
						, mem_register_datetime
						, mem_is_admin
						, mem_sns_type
					)
					VALUES
					(
						FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, ?
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, now()
						, '0'
						, 'email'
					) ";
            $this->db->query($sql, array($val['mem_email'], $val['mem_email'], $val['mem_password'], $val['mem_username'], $val['mem_phone']));
            $mem_id = $this->db->insert_id();

            $sql = "insert into
						member_delivery
					(
						mde_title
						, mem_id
						, recipient_name
						, recipient_phone
						, zipcode
						, road_addr
						, detail_addr
						, is_default
						, is_delete
						, ins_dtm
						, upd_dtm
						, memo
					)
					VALUES
					(
						?
						, ?
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, 'y'
						, 'n'
						, now()
						, now()
						, ?
					) ";
            $this->db->query($sql, array($val['recipient_name']
                , $mem_id
                , $val['recipient_name']
                , $val['recipient_phone']
                , $val['recipient_zip']
                , $val['recipient_addr1']
                , $val['recipient_addr2']
                , $val['recipient_memo']));
            return $mem_id;
        } else {
            return $res['mem_id'];
        }
    }

    public function select_order_info($seq) {
        $sql = "select
					t1.order_id
					, t1.order_type
					, FN_DECRYPT(t1.mem_username) as mem_username
					, FN_DECRYPT(t1.mem_phone) as mem_phone
					, FN_DECRYPT(t1.mem_email) as mem_email
					, t1.product_price
					, t1.total_price
					, t1.total_qty
					, t1.use_point
					, t1.delivery_price
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, FN_DECRYPT(t1.recipient_phone) as recipient_phone
					, FN_DECRYPT(t1.recipient_zip) as recipient_zip
					, FN_DECRYPT(t1.recipient_addr1) as recipient_addr1
					, FN_DECRYPT(t1.recipient_addr2) as recipient_addr2
					, t1.recipient_memo
					, t1.product_name
					, t1.status
					, t1.tid
					, t1.payMethod
					, t1.card_code
					, t1.card_name
					, t1.card_num
					, t1.vbank_name
					, t1.vbank_code
					, t1.vbank_owner
					, t1.vbank_num
					, t1.vbank_sender
					, t1.vbank_date
					, t1.bank_name
					, t1.bank_code
					, t1.bank_num
					, t1.bank_billscode
					, t1.bank_billstype
					, t1.payDevice
					, t1.applDate
					, t1.applTime
					, t1.payDevice
					, t1.use_coupon
					, ifnull(t1.use_coupon_type, '') as use_coupon_type
					, ifnull(t2.csu_id, '') as csu_id
					, ifnull(t2.delivery_day, '') as delivery_day
					, ifnull(t2.delivery_period, '') as delivery_period
					, ifnull(t2.start_date, '') as start_date
					, (select a.name from cmall_inicis_code a where a.code = t1.card_code and a.code_type = 'card') as card_name2
					, case when t2.csu_id is null then ''
						else ifnull((select a.order_id from cmall_subscribe_history a where a.csu_id = t2.csu_id order by ins_dtm limit 1), '') end as billing_order_id
					, (select order_type from cmall_payment_temp a where a.order_id = t1.order_id) as order_type_temp
				from 
					cmall_order t1
				LEFT OUTER JOIN cmall_subscribe t2 on t2.order_id = t1.order_id 
				where
					t1.order_id = ? ";
        $result = $this->db->query($sql, array($seq))->row_array();

        $sql = "select
					t1.cod_id
					, t1.order_id
					, t1.cit_id
					, t1.cde_id
					, t1.cit_name
					, t1.cit_price
					, t1.cit_sale_price
					, t1.cit_subscribe_price
					, t1.qty
					, t1.cde_title
					, t1.product_code
					, t1.barcode_no
					, t1.ins_dtm
					, t2.cit_file_1
					, t2.is_sale
				from
					cmall_order_detail t1
				inner join cmall_item t2 on t2.cit_id = t1.cit_id 
				where 
					t1.order_id = ? ";
        $result['list'] = $this->db->query($sql, array($seq))->result_array();

        return $result;
    }

    public function select_subscribe_info($seq) {
        $sql = "select
					*
				from
					cmall_subscribe
				where
					order_id = ? ";
        return $this->db->query($sql, array($seq));
    }

    public function select_subscribe_for_diagnosis($seq) {
        $this->db->where('order_id', $seq);
        $info = $this->db->get('cmall_subscribe')->row_array();

        if (!empty($info)) {
            $sql = "select
						t1.csu_id
						, t1.csd_id
						, t1.cit_id
						, t1.cde_id
						, t1.cit_name
						, t1.cit_price
						, t1.cit_sale_price
						, t1.cit_subscribe_price
						, t1.qty
						, t1.cde_title
						, t1.product_code
						, t1.barcode_no
						, t1.ins_dtm
						, t2.cit_file_1
					from
						cmall_subscribe_detail t1
					inner join cmall_item t2 on t2.cit_id = t1.cit_id 
					where 
						t1.csu_id = ? ";
            $info['list'] = $this->db->query($sql, array($info['csu_id']))->result_array();
        }
        return $info;
    }

    public function insert_subscribe_hitory($val) {
        $sql = "insert into
						cmall_subscribe_history
					(
						csu_id
						, order_id
						, result_code
						, result_msg
						, billing_key
						, card_no
						, card_name
						, card_code
						, ins_dtm
					)
					VALUES
					(
						?
						, ?
						, ?
						, ?
						, ?
						, ?
						, ?
						, ?
						, now()
					) ";
        $this->db->query($sql, array($val['csu_id'], $val['order_id'], $val['result_code'], $val['result_msg'], $val['billKey'], $val['card_no'], $val['card_name'], $val['card_code']));
    }

    public function insert_billing_order($sub, $req, $map) {
        $this->db->trans_begin();

        $sql = "insert into 
					cmall_order
				(
					order_id
					, order_type
					, mem_id
					, mem_username
					, mem_email
					, mem_phone
					, product_price
					, total_price
					, total_qty
					, use_point
					, delivery_price
					, recipient_name
					, recipient_phone
					, recipient_zip
					, recipient_addr1
					, recipient_addr2
					, recipient_memo
					, product_name
					, status
					, tid
					, payMethod
					, card_code
					, card_name
					, card_num
					, payDevice
					, applDate
					, applTime
					, ins_dtm
					, upd_dtm
				)
				VALUES
				(
					?
					, 'billing'
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, ?
					, ?
					, ?
					, ?
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, ?
					, ?
					, 'PAYMENT'
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, now()
					, now()
				)";

        $this->db->query($sql, array($sub['order_id']
            , $req['mem_id']
            , $req['mem_username']
            , $req['mem_phone']
            , $req['mem_email']
            , $req['total_price']
            , $req['total_price']
            , $req['total_qty']
            , $req['use_point']
            , $req['delivery_price']
            , $req['recipient_name']
            , $req['recipient_phone']
            , $req['recipient_zip']
            , $req['recipient_addr1']
            , $req['recipient_addr2']
            , $req['recipient_memo']
            , $map['goodsName']
            , $map['tid']
            , $map['payMethod']
            , $map['CARD_Code']
            , $map['P_FN_NM']
            , $map['CARD_Num']
            , $map['payDevice']
            , $map['payDate']
            , $map['payTime']));

        $item = array();
        foreach ($req['list'] as $row) {
            $tmp = "('" . $sub['order_id'] . "', " . $row['cit_id'] . ", " . $row['cde_id'] . ", '" . $row['cit_name'] . "', " . $row['cit_price'] . ", " . $row['cit_sale_price'] . ", 
					" . $row['cit_subscribe_price'] . ", " . $row['qty'] . ", '" . $row['cde_title'] . "', '" . $row['product_code'] . "', '" . $row['barcode_no'] . "', now())";
            $item[] = $tmp;
        }

        $sql = "insert into cmall_order_detail
				(order_id
				, cit_id
				, cde_id
				, cit_name
				, cit_price
				, cit_sale_price
				, cit_subscribe_price
				, qty
				, cde_title
				, product_code
				, barcode_no
				, ins_dtm)
				VALUES " . implode(',', $item);
        $this->db->query($sql, array());

        $sql = "update cmall_subscribe
				set
					last_date = date_format(now(), '%Y-%m-%d')
					, order_cnt = order_cnt + 1
				where
					csu_id = ? ";
        $this->db->query($sql, array($sub['csu_id']));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function insert_vbank_log($device_type, $req) {
//		$this->db->trans_begin();

        if ($device_type == 'PC') {
            $this->db->insert('cmall_vbank_log', $req);
            $order_id = $req['no_oid'];
            $price = $req['amt_input'];
            $auth_date = $req['dt_trans'] . $req['tm_trans'];
            $bills_code = $req['no_cshr_appl'];
        } else {
            $this->db->insert('cmall_vbank_mo_log', $req);
            $order_id = $req['P_OID'];
            $price = $req['P_AMT'];
            $auth_date = $req['P_AUTH_DT'];
            $bills_code = $req['P_CSHR_AUTH_NO'];
        }

        $this->db->where('order_id', $order_id);
        $res = $this->db->get('cmall_order')->row_array();

        if ($device_type == 'MO' && !empty($req['P_STATUS']) && $req['P_STATUS'] !== '02') {
            return true;
        } else {
            if (empty($res)) {
                return false;
            } else {
                $total_price = $res['vbank_in_price'] + $price;
                $this->db->where('order_id', $order_id);
                if ($total_price >= $res['total_price']) {
                    $this->db->set('status', 'PAYMENT');
                }
                $this->db->set('vbank_in_dtm', $auth_date);
                $this->db->set('vbank_in_price', $total_price);
                $this->db->set('bank_billscode', $bills_code);
                $this->db->update('cmall_order');

                return true;
            }
        }
        /* 		if($this->db->trans_status() === FALSE){
          $this->db->trans_rollback();
          return false;
          }else{
          $this->db->trans_commit();
          return true;
          } */
    }

    public function diagnosis_payment_request($req, &$pay) {

        $this->db->trans_begin();

        $pay['buyername'] = $req['mem_name'];
        $pay['buyertel'] = $req['mem_phone'];
        $pay['buyeremail'] = $req['mem_email'];

        $subscribe_name = '';
        if ($req['order_type'] == 'subscribe') {
            if (count($req['cit_name']) == 1) {
                $pay['goodname'] = $req['cit_name'][0];
                $subscribe_name = $req['cit_name'][0];
            } else {
                $pay['goodname'] = $req['cit_name'][0] . ' 외' . (count($req['cit_name']) - 1) . '건';
                $subscribe_name = $req['cit_name'][0] . ' 외' . (count($req['cit_name']) - 1) . '건';
            }
        } else if ($req['order_type'] == 'starter') {
            $pay['goodname'] = '스타터 패키지';
        } else {
            $pay['goodname'] = '스타터 패키지';
            if (count($req['cit_name']) == 1) {
                $subscribe_name = $req['cit_name'][0];
            } else {
                $subscribe_name = $req['cit_name'][0] . ' 외' . (count($req['cit_name']) - 1) . '건';
            }
        }

        $sum = 0;
        foreach ($req['qty'] as $row) {
            $sum += $row;
        }
        if ($req['order_type'] === 'starter') {
            $delivery_day = '';
            $delivery_period = 0;
            $start_date = '';
            $sum = 6;
        } else {
            if ($req['order_type'] === 'with') {
                $sum = 6;
            }
            $delivery_day = '0';
            $delivery_period = '12';
            $start_date = date('Y-m-d', strtotime('+' . (12 * 7) . ' days')); //date('Y-m-d');
            $new_date = '';
//			$new_date = date('Y-m-d', strtotime('+' . (12 * 7) . ' days'));
        }

        $sql = "insert into 
					cmall_payment_temp
				(
					order_id
					, order_type
					, order_mem_type
					, mem_id
					, mem_username
					, mem_phone
					, mem_email
					, mem_password
					, product_price
					, total_price
					, total_qty
					, use_point
					, delivery_price
					, recipient_name
					, recipient_phone
					, recipient_zip
					, recipient_addr1
					, recipient_addr2
					, recipient_memo
					, product_name
					, payment_type
					, mKey
					, sign
					, req_dtm
					, status
					, delivery_day
					, delivery_period
					, start_date
					, device_type
				)
				VALUES
				(
					?
					, ?
					, ?
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, SHA2(?, 512)
					, ?
					, ?
					, ?
					, ?
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, ?
					, ?
					, ?
					, ?
					, ?
					, now()
					, 'REQ'
					, ?
					, ?
					, ?
					, ?
				)";

        $this->db->query($sql, array($pay['oid']
            , $req['order_type']
            , $req['order_mem_type']
            , $req['mem_id']
            , $req['mem_name']
            , $req['mem_phone']
            , $req['mem_email']
            , $req['mem_password']
            , $req['total_price']
            , $req['total_price']
            , $sum
            , $req['use_point']
            , $req['set_delivery']
            , $req['recipient_name']
            , $req['recipient_phone']
            , $req['zipcode']
            , $req['road_addr']
            , $req['addr2']
            , $req['memo']
            , $pay['goodname']
            , $pay['gopaymethod']
            , $pay['mKey']
            , $pay['signature']
            , $delivery_day
            , $delivery_period
            , $start_date
            , $req['device_type']));
        $pay_id = $this->db->insert_id();
        $pay['merchantData'] = $pay_id . ',' . $req['cdg_id'];

        if ($req['order_type'] == 'starter' || $req['order_type'] == 'with') {
            $item = array();
            $sql = "SELECT
						t2.cit_id
						, t1.cde_id
						, t2.cit_name
						, t2.cit_price
						, t2.cit_sale_price
						, t2.cit_subscribe_price
						, t1.cde_title
						, t1.product_code
						, t1.barcode_no
					FROM
						cmall_item_detail t1
					INNER JOIN cmall_item t2 on t2.cit_id = t1.cit_id
					WHERE
						t1.cit_id = ? 
						and FIND_IN_SET('" . $req['ra4'] . "', t1.cde_title)
						and FIND_IN_SET('" . $req['ra3'] . "', t1.cde_title) ";
            $res = $this->db->query($sql, array($req['brush_id1']))->row_array();
            $tmp = "(" . $pay_id . ", 0, " . $res['cit_id'] . ", " . $res['cde_id'] . ", '" . $res['cit_name'] . "', " . $res['cit_price'] . ", " . $res['cit_sale_price'] . ", " . $res['cit_subscribe_price'] . ", 
					1, '" . $res['cde_title'] . "', '" . $res['product_code'] . "', '" . $res['barcode_no'] . "', now())";
            $item[] = $tmp;

            $sql = "SELECT
						t2.cit_id
						, t1.cde_id
						, t2.cit_name
						, t2.cit_price
						, t2.cit_sale_price
						, t2.cit_subscribe_price
						, t1.cde_title
						, t1.product_code
						, t1.barcode_no
					FROM
						cmall_item_detail t1
					INNER JOIN cmall_item t2 on t2.cit_id = t1.cit_id
					WHERE
						t1.cit_id = ? 
						and FIND_IN_SET('" . $req['ra5'] . "', t1.cde_title)
						and FIND_IN_SET('" . $req['ra6'] . "', t1.cde_title) ";
            $res = $this->db->query($sql, array($req['brush_id2']))->row_array();

            $tmp = "(" . $pay_id . ", 0, " . $res['cit_id'] . ", " . $res['cde_id'] . ", '" . $res['cit_name'] . "', " . $res['cit_price'] . ", " . $res['cit_sale_price'] . ", " . $res['cit_subscribe_price'] . ", 
					1, '" . $res['cde_title'] . "', '" . $res['product_code'] . "', '" . $res['barcode_no'] . "', now())";
            $item[] = $tmp;

            $other = '';
            if ($req['ra8'] == '1') {
                $main = 5;
                $other = '(10, 11, 12)';
            } else if ($req['ra8'] == '2') {
                $main = 7;
                $other = '(9, 10, 12)';
            } else if ($req['ra8'] == '3') {
                $main = 8;
                $other = '(9, 10, 11)';
            } else if ($req['ra8'] == '4') {
                $main = 6;
                $other = '(9, 11, 12)';
            }

            $sql = "SELECT
						t2.cit_id
						, t1.cde_id
						, t2.cit_name
						, t2.cit_price
						, t2.cit_sale_price
						, t2.cit_subscribe_price
						, t1.cde_title
						, t1.product_code
						, t1.barcode_no
					FROM
						cmall_item_detail t1
					INNER JOIN cmall_item t2 on t2.cit_id = t1.cit_id
					WHERE
						t1.cit_id = ? ";
            $res = $this->db->query($sql, array($main))->row_array();

            $tmp = "(" . $pay_id . ", 0, " . $res['cit_id'] . ", " . $res['cde_id'] . ", '" . $res['cit_name'] . "', " . $res['cit_price'] . ", " . $res['cit_sale_price'] . ", " . $res['cit_subscribe_price'] . ", 
					1, '" . $res['cde_title'] . "', '" . $res['product_code'] . "', '" . $res['barcode_no'] . "', now())";
            $item[] = $tmp;

            $sql = "SELECT
						t2.cit_id
						, t1.cde_id
						, t2.cit_name
						, t2.cit_price
						, t2.cit_sale_price
						, t2.cit_subscribe_price
						, t1.cde_title
						, t1.product_code
						, t1.barcode_no
					FROM
						cmall_item_detail t1
					INNER JOIN cmall_item t2 on t2.cit_id = t1.cit_id
					WHERE
						t1.cit_id in " . $other;
            $res = $this->db->query($sql, array())->result_array();

            foreach ($res as $row) {
                $tmp = "(" . $pay_id . ", 0, " . $row['cit_id'] . ", " . $row['cde_id'] . ", '" . $row['cit_name'] . "', " . $row['cit_price'] . ", " . $row['cit_sale_price'] . ", " . $row['cit_subscribe_price'] . ", 
						1, '" . $row['cde_title'] . "', '" . $row['product_code'] . "', '" . $row['barcode_no'] . "', now())";
                $item[] = $tmp;
            }

            $sql = "insert into cmall_payment_temp_detail
					(pay_id
					, cct_id
					, cit_id
					, cde_id
					, cit_name
					, cit_price
					, cit_sale_price
					, cit_subscribe_price
					, qty
					, cde_title
					, product_code
					, barcode_no
					, ins_dtm)
					VALUES " . implode(',', $item);
            $this->db->query($sql, array());
        } else if ($req['order_type'] == 'subscribe') {
            $item = array();
            for ($i = 0; $i < count($req['cit_id']); $i++) {
                $sql = "SELECT
							t2.cit_id
							, t1.cde_id
							, t2.cit_name
							, t2.cit_price
							, t2.cit_sale_price
							, t2.cit_subscribe_price
							, t1.cde_title
							, t1.product_code
							, t1.barcode_no
						FROM
							cmall_item_detail t1
						INNER JOIN cmall_item t2 on t2.cit_id = t1.cit_id
						WHERE
							t1.cit_id = ? ";
                if (!empty($req['option1'][$i])) {
                    $sql .= " and FIND_IN_SET('" . $req['option1'][$i] . "', t1.cde_title) ";
                }
                if (!empty($req['option2'][$i])) {
                    $sql .= " and FIND_IN_SET('" . $req['option2'][$i] . "', t1.cde_title) ";
                }
                $res = $this->db->query($sql, array($req['cit_id'][$i]))->row_array();

                $tmp = "(" . $pay_id . ", 0, " . $req['cit_id'][$i] . ", " . $res['cde_id'] . ", '" . $res['cit_name'] . "', " . $res['cit_price'] . ", " . $res['cit_sale_price'] . ", " . $res['cit_subscribe_price'] . " 
						, " . $req['qty'][$i] . ", '" . $res['cde_title'] . "', '" . $res['product_code'] . "', '" . $res['barcode_no'] . "', now())";
                $item[] = $tmp;
            }

            $sql = "insert into cmall_payment_temp_detail
					(pay_id
					, cct_id
					, cit_id
					, cde_id
					, cit_name
					, cit_price
					, cit_sale_price
					, cit_subscribe_price
					, qty
					, cde_title
					, product_code
					, barcode_no
					, ins_dtm)
					VALUES " . implode(',', $item);
            $this->db->query($sql, array());
        }

        if ($req['order_type'] == 'subscribe' || $req['order_type'] == 'with') {
            $sql = "insert into
						cmall_subscribe
					(
						csu_title
						, order_id
						, mem_id
						, delivery_day
						, delivery_period
						, start_date
						, new_date
						, ins_dtm
						, billing_key
						, card_num
						, card_name
						, card_code
						, recipient_name
						, recipient_phone
						, recipient_zip
						, recipient_addr1
						, recipient_addr2
						, recipient_memo
					)
					VALUES
					(
						?
						, ?
						, 0
						, ?
						, ?
						, ?
						, ?
						, now()
						, ''
						, ''
						, ''
						, ''
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, FN_ENCRYPT(?)
						, ?
					)";
            $this->db->query($sql, array($subscribe_name, $pay['oid'], $delivery_day, $delivery_period, $start_date, $new_date, $req['recipient_name'], $req['recipient_phone'], $req['zipcode']
                , $req['road_addr'], $req['addr2'], $req['memo']));
            $csu_id = $this->db->insert_id();

            $item = array();
            for ($i = 0; $i < count($req['cit_id']); $i++) {
                $sql = "SELECT
							t2.cit_id
							, t1.cde_id
							, t2.cit_name
							, t2.cit_price
							, t2.cit_sale_price
							, t2.cit_subscribe_price
							, t1.cde_title
							, t1.product_code
							, t1.barcode_no
						FROM
							cmall_item_detail t1
						INNER JOIN cmall_item t2 on t2.cit_id = t1.cit_id
						WHERE
							t1.cit_id = ? ";
                if (!empty($req['option1'][$i])) {
                    $sql .= " and FIND_IN_SET('" . $req['option1'][$i] . "', t1.cde_title) ";
                }
                if (!empty($req['option2'][$i])) {
                    $sql .= " and FIND_IN_SET('" . $req['option2'][$i] . "', t1.cde_title) ";
                }
                $res = $this->db->query($sql, array($req['cit_id'][$i]))->row_array();

                $tmp = "('" . $csu_id . "', " . $req['cit_id'][$i] . ", " . $res['cde_id'] . ", '" . $res['cit_name'] . "', " . $res['cit_price'] . ", " . $res['cit_sale_price'] . ", 
						" . $res['cit_subscribe_price'] . ", " . $req['qty'][$i] . ", '" . $res['cde_title'] . "', '" . $res['product_code'] . "', '" . $res['barcode_no'] . "', now())";
                $item[] = $tmp;
            }

            $sql = "insert into cmall_subscribe_detail
					(csu_id
					, cit_id
					, cde_id
					, cit_name
					, cit_price
					, cit_sale_price
					, cit_subscribe_price
					, qty
					, cde_title
					, product_code
					, barcode_no
					, ins_dtm)
					VALUES " . implode(',', $item);
            $this->db->query($sql, array());
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function diagnosis_payment_exec($status, $req, $map) {
        $this->db->trans_begin();
        
        if(!isset($map['aid'])){
            $map['aid'] = '';
        }
        
        $sql = "insert into 
					cmall_order
				(
					order_id
					, order_type
					, mem_id
					, mem_username
					, mem_email
					, mem_phone
					, product_price
					, total_price
					, total_qty
					, use_point
					, delivery_price
					, recipient_name
					, recipient_phone
					, recipient_zip
					, recipient_addr1
					, recipient_addr2
					, recipient_memo
					, product_name
					, status
					, tid
					, payMethod
					, card_code
					, card_name
					, card_num
					, vbank_name
					, vbank_code
					, vbank_owner
					, vbank_num
					, vbank_sender
					, vbank_date
					, bank_name
					, bank_code
					, bank_num
					, bank_billscode
					, bank_billstype
					, payDevice
					, applDate
					, applTime
					, ins_dtm
					, upd_dtm 
                                        , aid 
				)
				VALUES
				(
					?
					, ?
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, ?
					, ?
					, ?
					, ?
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?

					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?

					, ?
					, ?
					, ?
					, now()
					, now()
                                        , ? 
				)";

        $this->db->query($sql, array($req['order_id']
            , ($req['order_type'] == 'with' ? 'subscribe' : $req['order_type'])
            , $req['mem_id']
            , $req['mem_username']
            , $req['mem_email']
            , $req['mem_phone']
            , $req['product_price']
            , $req['total_price']
            , $req['total_qty']
            , $req['use_point']
            , $req['delivery_price']
            , $req['recipient_name']
            , $req['recipient_phone']
            , $req['recipient_zip']
            , $req['recipient_addr1']
            , $req['recipient_addr2']
            , $req['recipient_memo']
            , $map['goodsName']
            , $req['status']
            , $map['tid']
            , $map['payMethod']
            , $map['CARD_Code']
            , $map['P_FN_NM']
            , $map['CARD_Num']
            , $map['vactBankName']
            , $map['VACT_BankCode']
            , $map['VACT_Name']
            , $map['VACT_Num']
            , $map['VACT_InputName']
            , $map['VACT_Date']
            , $map['ACCT_BankName']
            , $map['ACCT_BankCode']
            , $map['ACCT_Num']
            , $map['CSHR_ResultCode']
            , $map['CSHR_Type']
            , $map['payDevice']
            , $map['applDate']
            , $map['applTime']
            , $map['aid']));

        $item = array();
        foreach ($req['list'] as $row) {
            $tmp = "('" . $req['order_id'] . "', " . $row['cit_id'] . ", " . $row['cde_id'] . ", '" . $row['cit_name'] . "', " . $row['cit_price'] . ", " . $row['cit_sale_price'] . ", 
					" . $row['cit_subscribe_price'] . ", " . $row['qty'] . ", '" . $row['cde_title'] . "', '" . $row['product_code'] . "', '" . $row['barcode_no'] . "', now())";
            $item[] = $tmp;
        }

        if (!empty($item)) {
            $sql = "insert into cmall_order_detail
					(order_id
					, cit_id
					, cde_id
					, cit_name
					, cit_price
					, cit_sale_price
					, cit_subscribe_price
					, qty
					, cde_title
					, product_code
					, barcode_no
					, ins_dtm)
					VALUES " . implode(',', $item);
            $this->db->query($sql, array());
        }

        if ($req['order_type'] == 'subscribe' || $req['order_type'] == 'with') {
            $sql = "update cmall_subscribe
					set
						mem_id = ?
						, billing_key = ?
						, card_num = ?
						, card_name = ?
						, card_code = ?
					where
						order_id = ? ";
            $this->db->query($sql, array($req['mem_id'], $map['CARD_BillKey'], $map['CARD_Num'], $map['P_FN_NM'], $map['CARD_Code'], $req['order_id']));

            $sql = "select count(*) as cnt from cmall_subscribe where mem_id = ? ";
            $tmp = $this->db->query($sql, array($req['mem_id']))->row_array();
            if ($tmp['cnt'] == 1) {
                $sql = "SELECT
							*
						FROM
							(SELECT
								t2.ccp_id
								, t2.ccp_name
								, t2.ccp_type
								, t2.down_type
								, t2.point_type
								, t2.price_type
								, t2.event_type
								, t2.use_start_date
								, t2.use_start_time
								, t2.use_end_date
								, t2.use_end_time
								, t2.ccp_val
								, t2.use_max
								, t2.max_val
								, t2.use_min
								, t2.min_val
								, ifnull((select a.ccl_id from cmall_coupon_log a where a.ccp_id = t2.ccp_id and a.mem_id = ?), '') as ccl_id
							FROM
								cmall_coupon t2
							WHERE
								date_format(now(), '%Y-%m-%d %H:%i') between concat(t2.down_start_date, ' ', t2.down_start_time) and concat(t2.down_end_date, ' ', t2.down_end_time) 
								and down_type = '2'
								and event_type = '1'
								and is_delete = 'n'
						) TB1
						WHERE
							TB1.ccl_id = '' ";
                $coupon = $this->db->query($sql, array($req['mem_id']))->result_array();

                if (!empty($coupon)) {
                    foreach ($coupon as $row) {
                        if ($row['ccp_type'] === '3' && $row['point_type'] === '1') {
                            $this->db->set('mem_id', $req['mem_id']);
                            $this->db->set('ccp_id', $row['ccp_id']);
                            $this->db->set('is_use', 'y');
                            $this->db->set('use_dtm', 'now()', false);
                            $this->db->set('use_val', $row['ccp_val']);
                            $this->db->set('down_type', 'auto');
                            $this->db->set('down_dtm', 'now()', false);
                            $this->db->set('down_user', '');
                            $this->db->insert('cmall_coupon_log');

                            $sql = "update member
									set
										mem_point = mem_point + " . $row['ccp_val'] . "
									where 
										mem_id = ? ";
                            $this->db->query($sql, array($req['mem_id']));

                            $sql = "insert into member_point
									(
										mem_id
										, add_type
										, add_val
										, rest_val
										, use_val
										, ins_dtm
										, exp_dtm
									)
									values
									(
										?
										, 'coupon'
										, " . $row['ccp_val'] . "
										, 0
										, 0
										, now()
										, date_format(date_add(now(), INTERVAL 1 YEAR), '%Y-%m-%d')
									) ";
                            $this->db->query($sql, array($req['mem_id']));

                            $sql = "insert into member_point_log
									(
										mem_id
										, point_type
										, point_val
										, point_dir
										, ins_dtm
										, user_type
										, ins_user
									)
									values
									(
										?
										, '쿠폰증정(자동)'
										, " . $row['ccp_val'] . "
										, 'plus'
										, now()
										, 'admin'
										, ?
									) ";
                            $this->db->query($sql, array($req['mem_id'], $req['mem_id']));
                        } else {
                            $this->db->set('mem_id', $req['mem_id']);
                            $this->db->set('ccp_id', $row['ccp_id']);
                            $this->db->set('is_use', 'n');
                            $this->db->set('down_type', 'auto');
                            $this->db->set('down_dtm', 'now()', false);
                            $this->db->set('down_user', '');
                            $this->db->insert('cmall_coupon_log');
                        }
                    }
                }
            }
        }

        if ($req['order_type'] == 'starter' || $req['order_type'] == 'with') {
            $this->db->reset_query();
            $this->db->where('mem_id', $req['mem_id']);
            $this->db->set('is_starter', 'y');
            $this->db->update('member');
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function insert_diagnosis_billing_order($sub, $req, $map) {
        $this->db->trans_begin();

        $sql = "insert into 
					cmall_order
				(
					order_id
					, order_type
					, mem_id
					, mem_username
					, mem_email
					, mem_phone
					, product_price
					, total_price
					, total_qty
					, use_point
					, delivery_price
					, recipient_name
					, recipient_phone
					, recipient_zip
					, recipient_addr1
					, recipient_addr2
					, recipient_memo
					, product_name
					, status
					, tid
					, payMethod
					, card_code
					, card_name
					, card_num
					, payDevice
					, applDate
					, applTime
					, ins_dtm
					, upd_dtm
				)
				VALUES
				(
					?
					, 'starter'
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, ?
					, ?
					, ?
					, ?
					, ?
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, FN_ENCRYPT(?)
					, ?
					, ?
					, 'PAYMENT'
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, ?
					, now()
					, now()
				)";

        $this->db->query($sql, array($sub['order_id']
            , $req['mem_id']
            , $req['mem_username']
            , $req['mem_email']
            , $req['mem_phone']
            , $req['total_price']
            , $req['total_price']
            , $req['total_qty']
            , $req['use_point']
            , $req['delivery_price']
            , $req['recipient_name']
            , $req['recipient_phone']
            , $req['recipient_zip']
            , $req['recipient_addr1']
            , $req['recipient_addr2']
            , $req['recipient_memo']
            , $map['goodsName']
            , $map['tid']
            , $map['payMethod']
            , $map['CARD_Code']
            , $map['P_FN_NM']
            , $map['CARD_Num']
            , $map['payDevice']
            , $map['payDate']
            , $map['payTime']));

        $item = array();
        foreach ($req['list'] as $row) {
            $tmp = "('" . $sub['order_id'] . "', " . $row['cit_id'] . ", " . $row['cde_id'] . ", '" . $row['cit_name'] . "', " . $row['cit_price'] . ", " . $row['cit_sale_price'] . ", 
					" . $row['cit_subscribe_price'] . ", " . $row['qty'] . ", '" . $row['cde_title'] . "', '" . $row['product_code'] . "', '" . $row['barcode_no'] . "', now())";
            $item[] = $tmp;
        }

        $sql = "insert into cmall_order_detail
				(order_id
				, cit_id
				, cde_id
				, cit_name
				, cit_price
				, cit_sale_price
				, cit_subscribe_price
				, qty
				, cde_title
				, product_code
				, barcode_no
				, ins_dtm)
				VALUES " . implode(',', $item);
        $this->db->query($sql, array());

        /* 		$sql = "update cmall_subscribe
          set
          last_date = date_format(now(), '%Y-%m-%d')
          , order_cnt = order_cnt + 1
          where
          csu_id = ? ";
          $this->db->query($sql, array($sub['csu_id'])); */

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function diagnosis_select_order_info($seq) {
        $sql = "select
					t1.order_id
					, t1.order_type
					, FN_DECRYPT(t1.mem_username) as mem_username
					, FN_DECRYPT(t1.mem_phone) as mem_phone
					, FN_DECRYPT(t1.mem_email) as mem_email
					, t1.product_price
					, t1.total_price
					, t1.total_qty
					, t1.use_point
					, t1.delivery_price
					, FN_DECRYPT(t1.recipient_name) as recipient_name
					, FN_DECRYPT(t1.recipient_phone) as recipient_phone
					, FN_DECRYPT(t1.recipient_zip) as recipient_zip
					, FN_DECRYPT(t1.recipient_addr1) as recipient_addr1
					, FN_DECRYPT(t1.recipient_addr2) as recipient_addr2
					, t1.recipient_memo
					, t1.product_name
					, t1.status
					, t1.tid
					, t1.payMethod
					, t1.card_code
					, t1.card_name
					, t1.card_num
					, t1.vbank_name
					, t1.vbank_code
					, t1.vbank_owner
					, t1.vbank_num
					, t1.vbank_sender
					, t1.vbank_date
					, t1.bank_name
					, t1.bank_code
					, t1.bank_num
					, t1.bank_billscode
					, t1.bank_billstype
					, t1.payDevice
					, t1.applDate
					, t1.applTime
					, t1.payDevice
					, ifnull(t2.csu_id, '') as csu_id
					, ifnull(t2.delivery_day, '') as delivery_day
					, ifnull(t2.delivery_period, '') as delivery_period
					, ifnull(t2.start_date, '') as start_date
					, (select a.name from cmall_inicis_code a where a.code = t1.card_code and a.code_type = 'card') as card_name2
					, case when t2.csu_id is null then ''
						else ifnull((select a.order_id from cmall_subscribe_history a where a.csu_id = t2.csu_id order by ins_dtm limit 1), '') end as billing_order_id
				from 
					cmall_order t1
				LEFT OUTER JOIN cmall_subscribe t2 on t2.order_id = t1.order_id 
				where
					t1.order_id = ? ";
        $result = $this->db->query($sql, array($seq))->row_array();

        if ($result['order_type'] !== 'subscribe') {
            $sql = "select
						t1.cod_id
						, t1.order_id
						, t1.cit_id
						, t1.cde_id
						, t1.cit_name
						, t1.cit_price
						, t1.cit_sale_price
						, t1.cit_subscribe_price
						, t1.qty
						, t1.cde_title
						, t1.product_code
						, t1.barcode_no
						, t1.ins_dtm
						, t2.cit_file_1
					from
						cmall_order_detail t1
					inner join cmall_item t2 on t2.cit_id = t1.cit_id 
					where 
						t1.order_id = ? ";
            $result['list'] = $this->db->query($sql, array($seq))->result_array();
        } else {
            $sql = "select
						t1.cod_id
						, t1.order_id
						, t1.cit_id
						, t1.cde_id
						, t1.cit_name
						, t1.cit_price
						, t1.cit_sale_price
						, t1.cit_subscribe_price
						, t1.qty
						, t1.cde_title
						, t1.product_code
						, t1.barcode_no
						, t1.ins_dtm
						, t2.cit_file_1
					from
						cmall_order_detail t1
					inner join cmall_item t2 on t2.cit_id = t1.cit_id 
					inner join cmall_subscribe_detail t3 on t3.cit_id = t1.cit_id
					inner join cmall_subscribe t4 on t4.order_id = t1.order_id
					where 
						t4.order_id = ? ";
            $result['list'] = $this->db->query($sql, array($seq))->result_array();
        }
        return $result;
    }

}
